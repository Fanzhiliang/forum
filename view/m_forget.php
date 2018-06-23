<?php 
	$message = trim($_GET['message']);
	$email = trim($_GET['email']);
?>
<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="UTF-8">
	<meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no">
	<!-- 初始比例=1 最大缩放=1 禁止缩放=移动端 -->
	<meta http-equiv="X-UA-Compatible" content="IE=edge">
	<!-- 使ie以最高级渲染 -->
	<title>修改密码</title>
	<script src="<?php echo $PREFIX;?>/static/js/init.js"></script>
	<link rel="stylesheet" href="<?php echo $PREFIX;?>/static/css/mobile/login.css">
	<style>
		.main .form-row .input-frame label{
			width: 2.5rem;
			letter-spacing: 0;
			margin-left: 0.2rem;
			margin-right: 0.2rem;
		}
	</style>
</head>
<body>
	<div class="wrap">
		<!--头部-->
		<div class="header box">
			<a href="/" class="logo center">
				<img src="<?php echo $PREFIX;?>/static/img/logo.png" alt="">
			</a>
		</div>
		<!--中部-->
		<div class="main">
			<!--内容-->
			<div class="body">
				<form action="/controller/forget.php" method="post">
					<input type="hidden" name="url" value="<?php echo $url;?>">
						<div class="form-row">
							<div class="input-frame">
								<label for="email">邮&nbsp;&nbsp;箱</label>
								<em></em>
								<input type="text" name="email" id="email" placeholder="绑定的邮箱" value="<?php echo $email;?>">
							</div>
						</div>
						<div class="form-row">
							<div class="input-frame">
								<label for="newPassword">新密码</label>
								<em></em>
								<input type="password" name="newPassword" id="newPassword" placeholder="8-30位数字或字母">
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
						<button type="submit" class="easy-shadow">找回</button>
					</div>
					<div class="form-row error-tip">
						<!--错误提示-->
						<?php echo $message;?>
					</div>
				</form>
			</div>
		</div>
		<!--尾部-->
		<div class="footer"></div>
	</div>
	<script src="<?php echo $PREFIX;?>/static/js/jquery-1.12.4.min.js"></script>
	<script src="<?php echo $PREFIX;?>/static/js/formUtils.js"></script>
	<script src="<?php echo $PREFIX;?>/static/js/forget.js"></script>
</body>
</html>