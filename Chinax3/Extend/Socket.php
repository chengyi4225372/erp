<?php
namespace Extend;
class Socket {
    private $server;
    public $users;
	private $sockets;
	public $host = "112.124.110.250:8080";

    public function __construct($class){
		$this->callback = $class;
		try{			 
			$this->server = socket_create(AF_INET, SOCK_STREAM, SOL_TCP)  ;
			socket_set_option($this->server, SOL_SOCKET, SO_REUSEADDR, 1) ;
			list($ip,$port) = explode(':',$this->host);
			socket_bind($this->server, $ip, $port);
			socket_listen($this->server,200);
        }catch(\Exception $e){
			$this->say(socket_strerror(socket_last_error()));
		}
		$this->sockets[] = $this->server;
        $this->say("Connect OK : ".$this->server." at Time: ".date('Y-m-d H:i:s'));
	}
	
	public function run(){
		ignore_user_abort(true); 
		set_time_limit(0);
		$except = $write = null;
		while(F('socket_isopen')){			
			$sk = $this->sockets;
			$read_num = socket_select($sk, $write, $except, NULL);
			$id = array_pop($sk);
			if($this->server == $id){
				$this->connect();
			}else{
				$len = socket_recv($id,$chr,1000,0);
				if($len < 1){
					$this->close($id);
					$this->callback->close((int)$id);
				}else{
					if($this->users[(int)$id]['sign']){
						$msg = $this->decode($chr);
						$this->callback->send((int)$id,$msg);
					}else{
						$this->open($id,$chr);
						$this->callback->open($this->users[(int)$id],$this->getRequest($chr));
					}
					
				}
			}
		}			
	}
	private function connect(){
		$client = socket_accept($this->server);
		if($client !== false){
			$this->sockets[] = $client;
			socket_getpeername($client, $ip, $pt);
			$this->users[(int)$client] = array(
				'sk'   => $client,
				'sgin' => false,
				'ip'   => $ip
			);
		}
		$this->say('Link ' . $client);
	}
	private function close($sk){
		$index = array_search($sk, $this->sockets);
        socket_close($sk);	
        if ($index >= 0){    
            unset($this->sockets[$index]);
			unset($this->users[(int)$sk]);
        }		
	}
	private function open($id,$chr){
		$key = $this->getKey($chr);
		$upgrade_message = "HTTP/1.1 101 Switching Protocols\r\n";
        $upgrade_message .= "Upgrade: websocket\r\n";
        $upgrade_message .= "Connection: Upgrade\r\n";
        $upgrade_message .= "Sec-WebSocket-Accept:" . $key . "\r\n\r\n";
        socket_write($id, $upgrade_message, strlen($upgrade_message));
        $this->users[(int)$id]['sign'] = true;
	}
	
	public function send($id,$msg){
		$msg = $this->encode($msg);
		socket_write($id, $msg ,strlen($msg));
    }
	private function decode($buffer) {
        $len = $masks = $data = $decoded = null;
        $len = ord($buffer[1]) & 127;
        if ($len === 126) {
            $masks = substr($buffer, 4, 4);
            $data = substr($buffer, 8);
        } 
        else if ($len === 127) {
            $masks = substr($buffer, 10, 4);
            $data = substr($buffer, 14);
        } 
        else {
            $masks = substr($buffer, 2, 4);
            $data = substr($buffer, 6);
        }
        for ($index = 0; $index < strlen($data); $index++) {
            $decoded .= $data[$index] ^ $masks[$index % 4];
        }
        return $decoded;
    }
	private function encode($msg){	
		$frame = array();
        $frame[0] = '81';
        $len = strlen($msg);
        if ($len < 126) {
            $frame[1] = $len < 16 ? '0' . dechex($len) : dechex($len);
        } else if ($len < 65025) {
            $s = dechex($len);
            $frame[1] = '7e' . str_repeat('0', 4 - strlen($s)) . $s;
        } else {
            $s = dechex($len);
            $frame[1] = '7f' . str_repeat('0', 16 - strlen($s)) . $s;
        }
        $data = '';
        $l = strlen($msg);
        for ($i = 0; $i < $l; $i++) {
            $data .= dechex(ord($msg{$i}));
        }
        $frame[2] = $data;
        $data = implode('', $frame);
        return pack("H*", $data);
	}
	private function getKey($chr){
		$line_with_key = substr($chr, strpos($chr, 'Sec-WebSocket-Key:') + 18);
        $key = trim(substr($line_with_key, 0, strpos($line_with_key, "\r\n")));
        return base64_encode(sha1($key . "258EAFA5-E914-47DA-95CA-C5AB0DC85B11", true));
	}	
	private function getRequest($chr){
		preg_match('/GET.*?\/(.*?)\s/s',$chr,$arr);
		return $arr[1];
	}	
    private function say($msg = ""){
        echo $msg . "\n";
    }
}