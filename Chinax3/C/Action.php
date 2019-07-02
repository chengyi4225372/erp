<?php
/*|========================================
 Chinax3  http://www.chinax3.com
===========================================
author : 小三伢 <371880026@qq.com>
===========================================
className: Action 动作
========================================|*/
namespace C;
class Action{
	// 视图
	private $v = null;
	//模型
	private $m = null;
	
	public function __call($name,$ags){
		throw new \Exception ("引用了不存在的方法 {$name} ",3);
	}
	// 引用构造方法
	public function __construct(){
		$this->_bgin();
	}
	protected function _bgin(){}
	// 解悉视图模板
	protected function v($temp = ''){
		$this->iniview();
		return $this->v->v($temp);
	}
	//解悉变量
	protected function s($name,$v = ''){
		$this->iniview();
		$this->v->s($name,$v);
		return $this;
	}	
	// 视图类实例
	private function iniview(){
		if($this->v == null)$this->v = d('View');
	}
	// 模型
	protected function m($m){
		$this->inimodel();
		return $this->m->table($m);
	}
	private function inimodel(){
		$this->m = d('Model');
	}
	protected function ajax($status=false,$info = '',$url = false){
		$info = array(
			'msg'		=> $info,
			'status'	=> $status,
			'url'		=> $url
		);
		echo json_encode($info);
		exit;
	}
	protected function success($msg,$url=''){
		if(!$url) {
			$url = $_SERVER['HTTP_REFERER'];
		}
		$this->s('msg',$msg);
		$this->s('url',$url);
		$this->v('Public/success');
	}
	protected function error($err,$url=''){
		if(!$url) {
			$url = $_SERVER['HTTP_REFERER'];
		}
		$this->s("err",$err);
		$this->s('url',$url);
		$this->v("Public/error");
		exit;
	}

}