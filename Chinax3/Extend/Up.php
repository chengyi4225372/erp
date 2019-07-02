<?php
namespace Extend;
Class Up{
	private $conf = array(
		'url'  => 'https://img.jixugo.com/',
		'size' => 1028000,
		'type' => array('jpg','gif','png','bmp','jpeg')
	);
	public function __construct($conf){
		$this->file = array_shift($_FILES);
		$this->check();
	}
	private function check(){
		$this->status = 0;
		if($this->file['error'] > 0) {
			$this->status = 1;
		}
		if($this->file['size'] > $this->conf['size']){
			$this->status = 2;
		}
		$type = explode('.',$this->file['name']);
		$hz = array_pop($type);
		if(!in_array($hz,$this->conf['type'])){
			$this->status = 3;
		}
	}
	public function info(){
		$this->upload();
		return array(
			'url'    => $this->conf['url'].$this->url,
			'title'  => $this->title,
			'status' => $this->status,
			'err'	 => $this->msg
		);
	}
	public function upload(){
		if($this->status > 0 ) {
			return $this->msg = "file type is not Ok";
		}			
		$data = array(
			"file" => $this->base64(),
			"name"   => $this->file['name'],
			"time"   => time()
		);
		$key = md5("cnm");
		$token = md5($data['name'].$data['time'].$key);
		$header = array('token:'.$token,'appid:1');
		$info = json_decode($this->http($data,$header));
		if($info->stuats){
			$this->title = $info->title;
			$this->url = $info->url;
			$this->msg = $info->msg;
		}else{
			$this->msg = "Access is not ok";
		}
	}
	private function base64(){
		$tmp = file_get_contents($this->file['tmp_name']);
		return base64_encode($tmp);
	}
	public function http($data,$header){
		$ch = curl_init();
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
		curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
		curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, false);
		curl_setopt($ch, CURLOPT_URL, $this->conf['url']);
		curl_setopt($ch, CURLOPT_TIMEOUT, 5000);
		curl_setopt($ch, CURLOPT_HTTPHEADER, $header);
		curl_setopt($ch, CURLOPT_POSTFIELDS, $data);
		$output = curl_exec($ch);
		curl_close($ch);
		return $output;
	}
}