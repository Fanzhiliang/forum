<?php
	$account = trim($_POST['account']);
	$password = trim($_POST['password']);
	$message =  urldecode(trim($_POST['message']));
	$url = $_POST['url'];
	if(strlen($account)>0 && strlen($password)>0){
		include_once('controller/Dao.php');
		$dao = new Dao();
		$user = $dao->checkLogin($account,$password);
		switch ($user['user_id']) {
			case 0:
				$message = "账号不存在!";
				break;
			case -1:
				$message = "密码错误!";
				break;
			case -2:
				$message = "账号未激活,请<a href='$PREFIX/controller/renewEmail.php?token=".$user['token']."'>重新验证</a>";
				break;
			default:
				session_start();
				setcookie(session_name(),session_id(),time()+(24*3600),"/");
				$_SESSION["user"] = $user; 
				if(strlen($url)>0){
					header('location:'.$url);
				}else{
					header('location:/');
				}
				break;
		}
	}else{
		session_start();
		$user = $_SESSION["user"];
		if($user == null){
			$account = $_GET['account'];
		}else{
			$account = $user['account'];
		}
	}
?>
<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="UTF-8">
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<!-- 初始比例=1 -->
	<meta http-equiv="X-UA-Compatible" content="IE=edge">
	<!-- 使ie以最高级渲染 -->
	<title>论坛登录</title>
	<script src="<?php echo $PREFIX;?>/static/js/init.js"></script>
	<!-- [if lt IE 9]>
		<script src="<?php echo $PREFIX;?>/static/js/html5shiv.min.js"></script>
	<![endif]-->
	<link rel="stylesheet" href="<?php echo $PREFIX;?>/static/css/pc/login.css">
</head>
<body>
	<div class="wrap">
		<!--头部-->
		<div class="header box">
			<div class="header-primary">
				<div class="header-seach">
					<a href="/" class="logo left">
						<img src="<?php echo $PREFIX;?>/static/img/logo.png" alt="">
					</a>
				</div>
			</div>
		</div>
		<!--中部-->
		<div class="main">
			<div class="main-primary">
				<!--边栏-->
				<div class="sidebar">
					<a href="">
						<img src="<?php echo $PREFIX;?>/static/img/ex-postings-2.jpg" alt="广告">
					</a>
				</div>
				<!--内容-->
				<div class="body box">
					<form action="/login" method="post">
						<input type="hidden" name="url" value="<?php echo $url;?>">
						<div class="form-row">
							<div class="input-frame <?php if($user['user_id']===0){echo ' selected warn ';}?>">
								<label for="account">邮箱</label>
								<em></em>
								<input type="text" name="account" id="account" placeholder="3-20位数字" value="<?php echo $account;?>">
							</div>
						</div>
						<div class="form-row">
							<div class="input-frame <?php if($user['user_id']===-1){echo ' selected warn ';}?>">
								<label for="password">密码</label>
								<em></em>
								<input type="password" name="password" id="password" placeholder="8-30位数字或字母">
							</div>
						</div>
						<div class="form-row">
							<div class="input-frame drag box">
								<div class="drag-text">
									<span>滑动滑块验证</span>
								</div>
								<div class="drag-bg box"></div>
								<div class="drag-obj box">
									<img src="<?php echo $PREFIX;?>/static/img/double-arrow-right.png" alt="滑动图标">
								</div>
								<input type="hidden" id="test" name="test" value="false">
							</div>
						</div>
						<div class="form-row">
							<a href="/register" class="tl">还没账号?点击注册</a>
							<a href="/forget" class="tr forget">忘记密码</a>
						</div>
						<div class="form-row">
							<button type="submit" class="easy-shadow">登录</button>
						</div>
						<div class="form-row error-tip">
							<?php echo $message; ?>
						</div>
					</form>
				</div>
			</div>
		</div>
		<!--尾部-->
		<div class="footer">
			
		</div>
	</div>
	<script src="<?php echo $PREFIX;?>/static/js/jquery-1.12.4.min.js"></script>
	<script src="<?php echo $PREFIX;?>/static/js/formUtils.js"></script>
	<script src="<?php echo $PREFIX;?>/static/js/login.js"></script>
</body>
</html>