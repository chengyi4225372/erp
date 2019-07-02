<?php
namespace Extend;
Class Temp{
	private $V = '';
	private $temp = '';
	public function __construct($temp){
			$this->temp =$temp;
	}
	public function assign($v){
		$this->V = $v;
		//echo $this->fetch($this->temp);exit;
		return $this->fetch($this->temp);
	}
	public function fetch($temp_path){
		if(!file_exists($temp_path)){
			throw new \Exception("模版 {$temp_path} 不存在");
		}
		$content = file_get_contents($temp_path);
		return $this->compress($content);
	}
	public function compress($content){
		$content = $this->includeCpr($content);
		$oldtag = array(
			"/\{(\\\$[\w\[\]\.\'\"\$]+)\}/s",			
			"/<if\s+(.*?)>/s",
			"/<\/if>|<\/foreach>/s",
			"/<else\s+\/>/s",
			"/<foreach\s+(.*?)\s+as\s+(.*?)>/s",
			"/<php>(.*?)<\/php>/s",
			"/\{:(.*?)\}/s",
		);
		$newtag = array(
			'<?php if(isset($1))echo $1 ;?>',			
			'<?php if(\1){ ?>',
			'<?php }?>',
			"<?php }else{ ?>",
			'<?php if(!is_array($\1)){$\1 = array();}foreach(\1 as $key=>\2){ ?>',
			'<?php \1 ;?>',
			'<?php echo \1 ;?>'
		);
		return preg_replace($oldtag,$newtag,$content);
	}
	public function includeCpr($con){
		$reg = "/<include\s+file=[\'\"](.*)[\'\"] \/>/";
		$arr = array();
		if(preg_match_all($reg,$con,$arr)){	
			foreach($arr[1] as $k=>$f){
				$file = c('tempDir'). '/'.$f.'.html';
				$content = @file_get_contents($file);
				$con = str_replace($arr[0][$k],$content,$con);
			}
		}
		return $con;
	} 
}