<?php
/*|========================================
 Chinax3  http://www.chinax3.com
===========================================
author : 小三伢 <371880026@qq.com>
===========================================
className: Control 行为控制器
========================================|*/
namespace C;
class Control{
	public function __construct(){
		$this->_bgin();
	}
	public function _bgin(){
		// 路由器规则
		$url = d('Url');
		$m = $url->m;
		$a = $url->a;
		$class = \Chinax3::$conf['classDir'].'\\'. $m ;
		if(!class_exists($class)){
			throw new \Exception ("模型 {$class} 不存在 ",3);
		}
		$c = new $class;
		$c->$a();
	}
}