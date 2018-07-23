<?php
	$message = urldecode($_GET['message']);
?>
<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="UTF-8">
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<!-- 初始比例=1 -->
	<meta http-equiv="X-UA-Compatible" content="IE=edge">
	<!-- 使ie以最高级渲染 -->
	<title>论坛注册</title>
	<link rel="shortcut icon" type="image/x-icon" href="<?php echo $PREFIX;?>/static/img/logo.ico">
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
					<form action="/controller/register.php" method="post">
						<input type="hidden" name="url" value="<?php echo $url;?>">
						<div class="form-row">
							<div class="input-frame <?php if($user['user_id']===0){echo ' selected warn ';}?>">
								<label for="name">昵称</label>
								<em></em>
								<input type="text" name="name" id="name" placeholder="3-20位数字">
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
							<div class="input-frame <?php if($user['user_id']===-1){echo ' selected warn ';}?>">
								<label for="email">邮箱</label>
								<em></em>
								<input type="email" name="email" id="email" placeholder="8-30位数字或字母">
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
							<a href="/login" class="tc">已有账号?点击登录</a>
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