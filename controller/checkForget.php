<?php
	include_once('sendPost.php');
	$HTTP_TYPE = ((isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] == 'on') || (isset($_SERVER['HTTP_X_FORWARDED_PROTO']) && $_SERVER['HTTP_X_FORWARDED_PROTO'] == 'https')) ? 'https://' : 'http://';
	$PREFIX = $HTTP_TYPE.$_SERVER['SERVER_NAME'];//地址前缀
	$token = trim($_GET['token']);
	$newPassword = trim($_GET['newPassword']);//已经md5编码了
	if(strlen($token) > 0 && strlen($newPassword)>0){
		include_once('Dao.php');
		$dao = new Dao();
		$user = $dao->getUserByToken($token);
		$user = $dao->updatePassword($user['account'],$user['password'],$newPassword);
		if($user){
			sendPost("$PREFIX/login",['message'=>'密码已修改']);
		}else{
			header("location:/tip?message=".urlencode('数据错误或用户不存在'));
		}
	}else{
		header("location:/tip?message=".urlencode('数据不完整'));
	}
?>