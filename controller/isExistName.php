<?php
	$name = $_POST['body']['value'];
	$send = ['code'=>500,'data'=>['message'=>'数据错误']];
	if($name && strlen(trim($name))>0){
		include_once 'Dao.php';
		$dao = new Dao();
		$result = $dao->isExistName($name);
		if($result == 0){//不存在
			$send['code'] = 200;
			$send['data']['message'] = '昵称可以注册';
		}else if($result == 1){//存在
			$send['data']['message'] = '昵称已被注册';
		}
	}
	echo json_encode($send);
?>