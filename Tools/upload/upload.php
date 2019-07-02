<?php
header("Content-Type: text/html; charset=utf-8");
error_reporting(E_ERROR);
include_once "../../../Chinax3/Extend/Up.php";
$conf = array(
	'type' => array('jpg','gif','png','bmp','jpeg')
);
$img = new Extend\Up($conf);
$info = $img->info();
if($info['status'] == 0){
	 $info["state"] = "SUCCESS";
}
echo json_encode($info);
?>