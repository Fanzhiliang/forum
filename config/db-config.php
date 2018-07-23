<?php
return [
	'dbms' => 'mysql',
	'dbName' => 'forum',
	#alcyh.com
	'user' => 'forum',
	'pwd' => '47789.321',
	#localhost
	//'user' => 'root',
	//'pwd' => '123',
	'host' => 'localhost',
	'charset' => 'utf8',
	'poolsize' => 10,
	'pageSize' => 15,//首页，贴子分页每页的记录数
	'replySize' => 5,//移动端楼层下的回复有多少条
	'homeSize' => 8,//pc端个人信息页面分页每页的记录数
]
?>