<?php
	$send = [
		'code' => 500,
		'data' => ['message' => '']//可以添加href属性，返回后跳转的地址
	];
	$get = $_POST['body'];
	if(!isset($get['session_id'])){
		$send['data']['message'] = 'session id 不正确!';
	}else{
		session_id($get['session_id']);
		session_start();
		unset($_SESSION['user']);
		$send['code'] = 200;
		$send['data']['message'] = 'success';
		$send['data']['href'] = '/login';
	}

	echo json_encode($send);
	die;
	
