<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2017/7/3 0003
 * Time: 11:11
 */
namespace Extend;
date_default_timezone_set('PRC');//设置时区
/**
 * 发送API
 * demo仅供参考，demo最低运行环境PHP5.3
 * 请确认开启PHP CURL 扩展
 */

class SendMsg {
    private $apikey = "13a72908c165971ece45499f38ab9dcd";
	public  $mobile = '';
	public  $text   = '';
	public  $url = "https://sms.yunpian.com/v2/sms/single_send.json";
	public  $data = array();
	
    private function http(){ // 模拟提交数据函数
        $curl = curl_init(); // 启动一个CURL会话
        curl_setopt($curl, CURLOPT_URL, $this->url); // 要访问的地址
        curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, false); // 对认证证书来源的检查
        curl_setopt($curl, CURLOPT_SSL_VERIFYHOST, false); // 从证书中检查SSL加密算法是否存在
        curl_setopt($curl, CURLOPT_POST, true); // 发送一个常规的Post请求
        curl_setopt($curl, CURLOPT_POSTFIELDS,  http_build_query($this->data)); // Post提交的数据包
        curl_setopt($curl, CURLOPT_TIMEOUT, $this->timeout); // 设置超时限制防止死循环
        curl_setopt($curl, CURLOPT_HEADER, false); // 显示返回的Header区域内容
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, true); // 获取的信息以文件流的形式返回
        $result = curl_exec($curl); // 执行操作
        if (curl_errno($curl)) {
            echo 'Error POST'.curl_error($curl);
        }
        curl_close($curl); // 关键CURL会话
        return $result; // 返回数据
    }
    public function send($phone) {
		$m = D('Model');
		$code = rand(0000,9999);
		$codes = array(
			'code'  => $code,
			'phone' => $phone,
			'outtime' => time() + 600,
			'ip'	=> $_SERVER['REMOTE_ADDR']
		);
		$i = $m->table('code')->where("to_days( from_unixtime(outtime)) = to_days(now()) and ip = '".$codes[ip] . "'")->count();
		if($i > 2){
			die("非法");
		}
		$m->table('code')->add($codes);
        $this->data['text']   = "【急需购】您的验证码是".$code;
        $this->data['mobile'] = $phone;
        $this->data['apikey'] = $this->apikey;
		echo $this->http();		
    }
	public function check($phone,$val) {
		$m = D('Model');
		$w = array('phone'=>$phone);
		$code = $m->table('code')->where($w)->order('id DESC')->find();
		if($code['code'] == $val && $code['outtime'] > time()){
			return true;
		}else{
			return false;
		}	
    }
}
