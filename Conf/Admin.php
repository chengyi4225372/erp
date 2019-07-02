<?php
return array(
// 数据库配置
	'db' => include_once "db.php",
// 模版常量
	'tempConst'=> array(              
		'_public' => "Public"
	),
// URL 类型， 0  GET , 1 伪静态 
	'urlType'  => 0,
// Class 文件夹
	'classDir' => 'MyClass',
// 模版文件夹
	'tempDir'  => 'Temp',
// 输入压缩
	'htmlComp' => false,
// 常用函数库
	'commonFunc' => 'Func',
// 文件缓存时，缓存目录
	'cacheDir' => '../Cache',
	'Tools'    => 'Tools',
	'debug'	   => true,
	'domian'   => 'www.hqyonline.com',
);

