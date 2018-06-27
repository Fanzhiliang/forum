<?php
	$name = trim($_POST['name']);
	$password = trim($_POST['password']);
	$email = trim($_POST['email']);
	$message = '';
	$user = null;
	if(strlen($name)>0 && strlen($password)>0 && strlen($email)>0){
		include_once('Dao.php');
		$dao = new Dao();
		$user = $dao->register($name,$password,$email);
		switch ($user['user_id']) {
			case -1:
				header("location:/register?message=".urlencode('昵称已被注册'));
				break;
			case -2:
				header("location:/register?message=".urlencode('邮箱已被注册'));
				break;
			case 0:
				header("location:/register?message=".urlencode('注册失败,不知道为什么?'));
				break;
			default://添加成功，发送邮件
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
				header("location:/tip?message=注册成功,请前往你的邮箱验证");//成功
		}
	}
?>