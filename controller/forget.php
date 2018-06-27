<?php
	$email = trim($_POST['email']);//获得邮箱
	$newPassword = md5(trim($_POST['newPassword']));//新密码
	if(strlen($email) > 0 && strlen($newPassword)>0){
		include_once('Dao.php');
		$dao = new Dao();
		$user = $dao->getUserByEmail($email);
		$user = $dao->updateUserToken($user);
		if($user['user_id']>0){//修改成功，发送邮件
			include_once('sendEmail.php');
			$HTTP_TYPE = ((isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] == 'on') || (isset($_SERVER['HTTP_X_FORWARDED_PROTO']) && $_SERVER['HTTP_X_FORWARDED_PROTO'] == 'https')) ? 'https://' : 'http://';
				$PREFIX = $HTTP_TYPE.$_SERVER['SERVER_NAME'];//地址前缀
			$smtpemailto = $user['email'];//发送给谁
			$limitTime = date('Y年m月d日 H:i:s',intval($user['token_time'])+60*60);
			$mailcontent = "亲爱的".$user['name']."：<br/>已接受你修改密码的请求。<br/>请点击链接确认修改密码。<br/><a href='$PREFIX/controller/checkForget.php?token=".$user['token']."&newPassword=".$newPassword."' target='_blank'>$PREFIX/controller/checkForget.php?token=".$user['token']."&newPassword=".$newPassword."</a><br/>如果以上链接无法点击，请将它复制到你的浏览器地址栏中进入访问，该链接1小时内有效(".$limitTime."前)。<br/>如果此次激活请求非你本人所发，请忽略本邮件。<br/>";//邮件内容
			$state = sendEmail($smtpemailto,'forum论坛找回密码',$mailcontent);
			echo var_dump($state)."<br>";
			die;
			if(!$state){//邮件发送失败
				header("location:/forget?message=".urlencode('邮件发送失败'));
			}
			header("location:/tip?message=".urlencode('成功发送邮件,请前往你的邮箱验证'));//成功
		}
	}else{
		header("location:/forget?message=".urlencode('邮箱错误或账号不存在'));
	}
?>