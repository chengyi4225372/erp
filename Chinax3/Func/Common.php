<?php
// 联接
function c($name){
	return \Chinax3::$conf[$name];
}
function d($name){
	$class =  "\\Drive\\".$name;
	return new $class;
}
function e($name,$args = null){	
	$class =  "\\Extend\\".$name;
	return new $class($args);
}
if(!function_exists("T")){
	function T($url){
		$urls = parse_url($url);
		list($m,$a) = explode('/',$urls['path']);
		if(c(urlType) == false){
			$new = "?m={$m}&a={$a}&{$urls['query']}";
			$new = trim($new,"&");
		}else{
			if($m == "Index" && $a == 'Index'){
				$new = "";
			}else{
				$new = "{$m}/{$a}/";
			}
			if($urls['query']){
				$arr = explode('&',$urls['query']);
				foreach($arr as $a){
					list($k,$v) = explode('=',$a);
					if($v){
						$new .= strtr($a,'=','-').'-';
					}					
				}
				$hz = ".html";
			}
			$new = trim($new,"-").$hz;
			$new = $scp.'/'.$new;
		}
		return $new;
	}
}
if(!function_exists("F")){
	function F($name,$value=false,$time=0){
		if(!isset($cache)){
			$cache = new \Extend\Cache;
		}
		if($value === false){
			return $cache->get($name);
		}
		if($value){
			return $cache->set($name,$value,$time);
		}
		if($value === null){
			return $cache->rm($name);
		}
	}
}

if(!function_exists("session")){
	function session($name,$value=false){
		session_set_cookie_params(0 , '/', c('domian'));
		session_start();
		if($value === false){
			return $_SESSION[$name];
		}else{
			return $_SESSION[$name] = $value;
		}
		if($value === null){
			unset($_SESSION[$name]);
		}
	}
}
if(!function_exists("msubstr")){
	function msubstr($str, $start=0, $length,$suffix=false, $charset="utf-8"){
		if(function_exists("mb_substr")){
				if ($suffix && strlen($str)>$length)
					return mb_substr($str, $start, $length, $charset)."...";
			else
					 return mb_substr($str, $start, $length, $charset);
		}
		elseif(function_exists('iconv_substr')) {
				if ($suffix && strlen($str)>$length)
					return iconv_substr($str,$start,$length,$charset)."...";
			else
					return iconv_substr($str,$start,$length,$charset);
		}
		$re['utf-8']   = "/[\x01-\x7f]|[\xc2-\xdf][\x80-\xbf]|[\xe0-\xef][\x80-\xbf]{2}|[\xf0-\xff][\x80-\xbf]{3}/";
		$re['gb2312'] = "/[\x01-\x7f]|[\xb0-\xf7][\xa0-\xfe]/";
		$re['gbk']    = "/[\x01-\x7f]|[\x81-\xfe][\x40-\xfe]/";
		$re['big5']   = "/[\x01-\x7f]|[\x81-\xfe]([\x40-\x7e]|\xa1-\xfe])/";
		preg_match_all($re[$charset], $str, $match);
		$slice = join("",array_slice($match[0], $start, $length));
		if($suffix) return $slice."…";
		return $slice;
	}
}