<?php
	include_once('sendPost.php');
	$HTTP_TYPE = ((isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] == 'on') || (isset($_SERVER['HTTP_X_FORWARDED_PROTO']) && $_SERVER['HTTP_X_FORWARDED_PROTO'] == 'https')) ? 'https://' : 'http://';
	$PREFIX = $HTTP_TYPE.$_SERVER['SERVER_NAME'];//地址前缀
	date_default_timezone_set("PRC");
	$token = trim($_GET['token']);
	if(strlen($token) > 0){
		include_once('Dao.php');
		$dao = new Dao();
		$user = $dao->getUserByToken($token);
		if($user['user_id']<1){
			header("location:/tip?message=".urlencode('数据错误或用户不存在'));
		}
		if($user['status'] === 1){
			sendPost("$PREFIX/login",['message'=>'账号已验证']);
		}
		if(time() <= intval($user['token_time'])+24*60*60){//24小时内验证
			if($dao->updateUserStatus($user['user_id'])){
				sendPost("$PREFIX/login",['message'=>'验证成功,请登录']);
			}else{
				header("location:/tip?message=".urlencode('修改数据时发生错误'));
			}
		}else{//超时验证
			header("location:/tip?message=".urlencode("验证超时,请<a href='$PREFIX/controller/renewEmail.php?token=$token'>重新验证</a>"));
		}
	}else{
		header("location:/tip?message=".urlencode('token错误或账号不存在'));
	}
?>	