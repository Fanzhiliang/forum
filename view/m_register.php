<?php
	$message = urldecode($_GET['message']);
?>
<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="UTF-8">
	<meta name="viewport" content="width=device-width,initial-scale=1.0,maximum-scale=1.0,user-scalable=no">
	<!-- 初始比例=1 最大缩放=1 禁止缩放=移动端 -->
	<meta http-equiv="X-UA-Compatible" content="IE=edge">
	<!-- 使ie以最高级渲染 -->
	<title>论坛注册</title>
	<script src="<?php echo $PREFIX;?>/static/js/init.js"></script>
	<link rel="stylesheet" href="<?php echo $PREFIX;?>/static/css/mobile/login.css">
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
				<form action="/controller/register.php" method="post">
					<div class="form-row">
						<div class="input-frame">
							<label for="name">昵称</label>
							<em></em>
							<input type="text" name="name" id="name" value="<?php echo $name;?>" placeholder="3-20位字符">
						</div>
					</div>
					<div class="form-row">
						<div class="input-frame">
							<label for="password">密码</label>
							<em></em>
							<input type="password" name="password" id="password" placeholder="8-30位数字或字母">
						</div>
					</div>
					<div class="form-row">
						<div class="input-frame">
							<label for="email">邮箱</label>
							<em></em>
							<input type="text" name="email" id="email" value="<?php echo $email;?>" placeholder="请输入正确格式">
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
							<input type="hidden" id="test" name="test">
						</div>
					</div>
					<div class="form-row">
						<a href="/login" class="tc">已有账号?点击登录</a>
					</div>
					<div class="form-row">
						<button type="submit" class="easy-shadow">注册</button>
					</div>
					<div class="form-row error-tip">
						<!--错误提示-->
						<?php echo $message; ?>
					</div>
				</form>
			</div>
		</div>
		<!--尾部-->
		<div class="footer"></div>
	</div>
	<script src="<?php echo $PREFIX;?>/static/js/jquery-1.12.4.min.js"></script>
	<script src="<?php echo $PREFIX;?>/static/js/formUtils.js"></script>
	<script src="<?php echo $PREFIX;?>/static/js/login.js"></script>
</body>
</html>