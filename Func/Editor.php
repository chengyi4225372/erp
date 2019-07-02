<?php
// 编辑器
function editor($id){
	echo	'<link rel="stylesheet" type="text/css" src="'. Chinax3::$conf['Tools'] . '/editor/ueditor/themes/udefault/ueditor.css" /> ';
	echo	'<script type="text/javascript" charset="utf-8" src="'. Chinax3::$conf['Tools']. '/editor/ueditor/ueditor.config.js" ></script> ';
	echo	'<script type="text/javascript" charset="utf-8" src="' . Chinax3::$conf['Tools']. '/editor/ueditor/ueditor.all.js" ></script> ';
	echo 	'<script type="text/javascript">' ;
	foreach(func_get_args() as $v){
		echo		"var {$v}ued  = new baidu.editor.ui.Editor();";
		echo		"{$v}ued.render('{$v}');";
	}
	echo 	'</script>';
}

function ckeditor($id){
	echo	'<script type="text/javascript" src="'. Chinax3::$conf['Tools'] . '/editor/ckeditor/ckeditor.js" /> </script>';
	echo	"<script tyle='text/javascript'>
			CKEDITOR.replace( '{$id}', {
				toolbar: [
					[ 'Bold', 'Italic', '-', 'NumberedList', 'BulletedList', '-', 'Link', 'Unlink' ],
					[ 'FontSize', 'TextColor', 'BGColor' ]
				]
			});
			</script>";
}