<?php
/*|========================================
 Chinax3  http://www.chinax3.com
===========================================
author : 小三伢 <371880026@qq.com>
===========================================
className: View          视图控制器
========================================|*/
namespace Drive;
class View{
	// 变量
	private $V = array();
	// 视图
	public function v($temp){
		$content = $this->fetch($temp);		
		$this->show($content);
	}
	// 变量分配方法
	public function s($names,$v = ''){
		if(is_array($names)){
			foreach($names as $k=>$name)$this->V[$k] = $name;
		}else{
			$this->V[$names] = isset($v) ? $v : false;
		}	
	}
	// 模板解释
	public function fetch($temp){
		$temp_file = c("tempDir") .'/'.($temp ? $temp . '.html'  : d('Url')->m . '/' . d('Url')->a . '.html');
		$temp = e('Temp',$temp_file);
		$html = $temp->assign($this->V);	
		ob_start();
		extract($this->V);
		c("tempConst") && extract(c("tempConst"));
		eval("?>".$html);
		$newhtml = ob_get_contents();
		ob_clean();
		return $newhtml;
	}
	// 显示内容
	public function show($content){		
		header('Content-Type:text/html; charset=utf-8');
		header('X-Powered-By:Chinax3');
		ob_start('ob_gzhandler');
		// 输出模板文件
		if(c('htmlComp')){
			echo $this->compress_html($content);
		}else{
			echo $content;
		}
		ob_end_flush();
	}
	//页面过滤
	private function compress_html($string) {
		$pattern = array ("/<!--[^!]*-->/","/ +/","/\t/","/[\r\n]+/");
		$replace = array (""," ",'',"\r\n");
		$html = preg_replace($pattern, $replace, $string);				
		return $html;
	}
}