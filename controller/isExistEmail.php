<?php
	$email = $_POST['body']['value'];
	$send = ['code'=>500,'data'=>['message'=>'数据错误']];
	if($email && strlen(trim($email))>0){
		include_once 'Dao.php';
		$dao = new Dao();
		$result = $dao->isExistEmail($email);
		if($result == 0){//不存在
			$send['code'] = 200;
			$send['data']['message'] = '邮箱可以注册';
		}else if($result == 1){//存在
			$send['data']['message'] = '邮箱已被注册';
		}
	}
	echo json_encode($send);
?>