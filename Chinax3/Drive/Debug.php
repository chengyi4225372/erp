<?php
/*|========================================
 Chinax3  http://www.chinax3.com
===========================================
author : 小三伢 <371880026@qq.com>
===========================================
className: Chinax3 错误控制器
========================================|*/
namespace Drive;
class Debug extends Drive {
	public static function Error($code,$msg,$file,$line){
		switch($code){
			case 1    : self::showBug("系统出错啦！",500);
			break;
			default:false;
		}
	}
	public static function Exception($e){
		self::showBug($e->GetMessage(),404);
	}
	public static function Fatal(){
		$err = error_get_last();
		if($err){
			print_r($err);exit;
			self::showBug("可恶的程序员又写了一个BUG！".$err[message],500);	
		}			
	}
	public static function showBug($err,$level){
			if(c(debug)){
				print_r($err);exit;
			}			
			if($level == 404){
				d('View')->v('404');
				exit;
			}
			if($level == 500){
				echo "<div class='content' style='text-align:center;height:300px;margin-top:120px;width:600px;'><div style='font-size:150px;color:red;line-height:150px;font-weight:bold;float:left;width:300px;'>500</div>
				<div style='color:#000;font-size:26px;text-align:left;float:left;width:300px;'>{$err}<div style='font-size:16px;text-align:left;line-height:30px;margin-top:20px;'>用户爸爸们请不要着急，网站程序员正在跪着给您调试代码！</div>
				<div style='font-size:14px;text-align:left;line-height:30px;margin-top:20px;'><a href='/'>返回首页</a> &nbsp;&nbsp;&nbsp;&nbsp;<a href='javascript:history.go(-1)'>返回上一页</a></div></div></div>";
				exit;
			}		
	}
}