<?php
/*|========================================
 Chinax3  http://www.chinax3.com
===========================================
author : 小三伢 <371880026@qq.com>
===========================================
className: Chinax3 启动控制
========================================|*/
class Chinax3{
// ================================
// 配置
//=================================
	public static $conf ;
// ================================
// 启动程序
//=================================
	static public function start($conf){
		ini_set('display_errors','Off');
		ini_set('date.timezone','Asia/Shanghai');
		error_reporting(E_ALL);
		spl_autoload_register(array('Chinax3',"autoload"));
		set_exception_handler(array('\Drive\Debug','Exception'));
		set_error_handler(array('\Drive\Debug','Error'));
		register_shutdown_function(array('\Drive\Debug','Fatal'));
		define('ROOT',dirname(__FILE__));
		self::$conf = $conf;
		$funcs = $conf['commonFunc'] ? scandir($conf['commonFunc']) : false;
		if($funcs){
			foreach($funcs as $v){
				if(trim($v,".")){
					include_once $conf['commonFunc'].'/'.$v;
				}
			}
		}
		require ROOT.'/Func/Common.php';
		new \C\Control();
	}
	public static function autoload($name){
		$className = str_replace("\\","/",$name).".php";		
		if(!include_once $className){
			throw new Exception ("加载 {$className} 失败 ",3);
		}
	}
}
