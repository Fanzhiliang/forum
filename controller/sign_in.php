<?php
	$send = [
		'code' => 500,
		'data' => [
			'message' => '签到失败'
		]//可以添加href属性，返回后跳转的地址
	];
	function notLogin(){
		$send['data']['message'] = '还没登录,请登录!';
		$send['data']['href'] = '/login';
		echo json_encode($send);
		die;
	}
	$get = $_POST['body'];
	if(!isset($get['session_id'])){
		notLogin();
	}
	session_id($get['session_id']);
	session_start();
	$user = $_SESSION['user'];
	if(!$user){
		notLogin();
	}
	
	include_once 'Dao.php';
	$dao = new Dao();
	$newUser = $dao->signIn($user['user_id']);
	if($newUser['user_id']>0){
		$send['code'] = 200;
		$send['data']['message'] = 'success';
		$send['data']['credits'] = $newUser['credits'];
		$send['data']['max_credits'] = $newUser['max_credits'];
		$send['data']['level'] = $newUser['level'];
	}else if($newUser['user_id']==0){
		$send['data']['message'] = '已经签到';
	}
	echo json_encode($send);