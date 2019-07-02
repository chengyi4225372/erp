<?php 
namespace MyClass;
class Index extends \MyClass\Common{
	public function Index(){
		$this->v();
	}
	// 网站
	public function Webset(){
		$this->v();
	}
	public function WebsetDo(){
		F('title',$_POST['title']);
		F('discption',$_POST['discption']);
		F('keywords',$_POST['keywords']);
		$this->success();
	}
	
	public function pay(){
		$header[] = "Content-type: text/xml";
		$header[] = "charset=utf-8";
		
		$key = "hhhhhhhhhhhhhhhhhhhhHuiqiyun2019";
		$sign['appid'] = "wx3a73dfa030531cf9";
		//$sign['openid'] = "oSsKH5KQSYVMlAy8ii1-qqBeYFy0";
		$sign['attach'] = "test";
		$sign['body'] = "fdsfd";
		$sign['mch_id'] = "1533215911";
		$sign['nonce_str'] = "123156481231213";
		$sign['out_trade_no'] = "9999";
		$sign['notify_url'] = "https://test.zxrhgb.com/inveCustomer/wxNotify";
		$sign['spbill_create_ip'] = "59.175.70.0";
		$sign['total_fee'] = "1";
		$sign['trade_type'] = "MICROPAY";
		$sign['sign_type'] = "MD5";
		ksort($sign);
		foreach($sign as $k => $v){
			$str .= "{$k}=$v&";
		}
		$str = trim($str,"&");
		$code = md5($str."&key=".$key);
		$code = strtoupper($code);
		
		$data .= "<xml>";
		$data .= "<appid>wx3a73dfa030531cf9</appid>";
		//$data .= "<openid>oSsKH5KQSYVMlAy8ii1-qqBeYFy0</openid>";
		$data .= "<attach>test</attach>";
		$data .= "<body>fdsfd</body>";
		$data .= "<openid>fdsfd</openid>";	
		$data .= "<mch_id>1533215911</mch_id>";
		$data .= "<nonce_str>123156481231213</nonce_str>";	
		$data .= "<notify_url>https://test.zxrhgb.com/inveCustomer/wxNotify</notify_url>";		
		$data .= "<out_trade_no>9999</out_trade_no>";
		$data .= "<spbill_create_ip>59.175.70.0</spbill_create_ip>";
		$data .= "<total_fee>1</total_fee>";		
		$data .= "<trade_type>MICROPAY</trade_type>";
		$data .= "<sign_type>MD5</sign_type>";
		$data .= "<sign>{$code}</sign>";
		$data .= "</xml>";
		
		echo $data;
		
		$this->post($header,$data);
	}
	public function post($head,$data) {
        $url  = "https://api.mch.weixin.qq.com/pay/unifiedorder";
        $ch = curl_init();//初始化curl
        curl_setopt($ch, CURLOPT_URL,$url);//抓取指定网页
        curl_setopt($ch, CURLOPT_HEADER, $head);//设置header
		curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, FALSE); // https请求 不验证证书和hosts
		//curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, FALSE);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);//要求结果为字符串且输出到屏幕上
        curl_setopt($ch, CURLOPT_POST, 1);//post提交方式
        curl_setopt($ch, CURLOPT_POSTFIELDS, $data);
        $data = curl_exec($ch);//运行curl
		curl_close($ch); 
        var_dump($data);
    }
	
}