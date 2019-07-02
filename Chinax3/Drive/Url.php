<?php
/*|========================================
 Chinax3  http://www.chinax3.com
===========================================
author : 小三伢 <371880026@qq.com>
===========================================
className: Chinax3 路由器
========================================|*/
namespace Drive;
class Url extends Drive{
	public $m = "Index";
	public $a = "Index";
	public function _bgin(){
		if(c('urlType') == false){
			if(!$_GET[m])$_GET[m] = $this->m;
			if(!$_GET[a])$_GET[a] = $this->a;	
		}else{
			$this->geturl();
		}
		$this->m = $_GET['m'];
		$this->a = $_GET['a'];
	}
	private function geturl(){
		unset($_GET);
		$url = $_SERVER['QUERY_STRING'];
		$url = str_replace('.html','',$url);
		$urls = explode('/',$url);
		if(strstr($urls[0], "=")){
			$urls[0] = false;
		}
		$_GET['m'] = $this->m = $urls[0] ? $urls[0] : $this->m;
		$_GET['a'] = $this->a = $urls[1] ? $urls[1] : $this->a;
		$que = array_filter(explode('-',$urls[2]));
		while($que){
			$q['k'] = array_shift($que);
			$q['v'] = array_shift($que);
			$_GET[$q[k]] = $q[v];
		}
		if($pos = strpos($_SERVER['REQUEST_URI'],'?')){
			parse_str(substr($_SERVER['REQUEST_URI'],$pos+1),$arrs);
			$_GET = array_merge($_GET,$arrs);
		}		
	}
}