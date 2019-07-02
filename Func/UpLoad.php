<?php
function upLoad($emt,$opt){
	$code = '<script charset="utf-8" type="text/javascript" src="'. Chinax3::$conf["Tools"] .'/upload/upLoad.js"></script>';
	$code .= '<script type="text/javascript">$("'.$emt.'").upLoad('.json_encode($opt).');</script>';
	echo $code;
}
function getimg($id){
	return "//img.chouwazi.com/?m=GetFile&a=getImg&id=".$id;
}
