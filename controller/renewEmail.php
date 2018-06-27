<?php
	$token = trim($_GET['token']);//获得旧token
	if(strlen($token) > 0){
		include_once('Dao.php');
		$dao = new Dao();
		$user = $dao->getUserByToken($token);
		$user = $dao->updateUserToken($user);
		if($user){//修改成功，发送邮件
			include_once('sendEmail.php');
			$HTTP_TYPE = ((isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] == 'on') || (isset($_SERVER['HTTP_X_FORWARDED_PROTO']) && $_SERVER['HTTP_X_FORWARDED_PROTO'] == 'https')) ? 'https://' : 'http://';
				$PREFIX = $HTTP_TYPE.$_SERVER['SERVER_NAME'];//地址前缀
			$smtpemailto = $user['email'];//发送给谁
			$limitTime = date('Y年m月d日 H:i:s',intval($user['token_time'])+24*60*60);
			$mailcontent = "亲爱的".$user['name']."：<br/>感谢您在我站注册了新帐号。<br/>请点击链接激活您的帐号。<br/><a href='$PREFIX/controller/checkEmail.php?token=".$user['token']."' target='_blank'>$PREFIX/controller/checkEmail.php?token=".$user['token']."</a><br/>如果以上链接无法点击，请将它复制到你的浏览器地址栏中进入访问，该链接24小时内有效(".$limitTime."前)。<br/>如果此次激活请求非你本人所发，请忽略本邮件。<br/>";//邮件内容
			$state = sendEmail($smtpemailto,'forum论坛注册',$mailcontent);
			if(!$state){//邮件发送失败
				header("location:/register?message=".urlencode('邮件发送失败'));
			}
			header("location:/tip?message=重新发送邮件,请前往你的邮箱验证");//成功
		}
	}else{
		header("location:/tip?message=".urlencode('token错误或账号不存在'));
	}
?>