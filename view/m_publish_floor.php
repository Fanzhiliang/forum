<?php
	include_once('controller/Dao.php');
	$dao = new Dao();
	session_start();
	$user = $_SESSION["user"];
	$currUser = $dao->checkLogin($user['account'],$user['password']);
	$postingsId = $_GET['param0'];
	if(!$user || $currUser['user_id'] < 1){
		sendPost("$PREFIX/login",['message'=>'还没登录,请登录!','url'=>"$controller/$postingsId"]);
	}
	setcookie(session_name(),session_id(),time()+(24*3600),"/");
	$_SESSION["user"] = $currUser;
?>
<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="UTF-8">
	<meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no">
	<!-- 初始比例=1 最大缩放=1 禁止缩放=移动端 -->
	<meta http-equiv="X-UA-Compatible" content="IE=edge">
	<!-- 使ie以最高级渲染 -->
	<title>发布帖子</title>
	<script src="<?php echo $PREFIX;?>/static/js/init.js"></script>
	<link rel="stylesheet" href="<?php echo $PREFIX;?>/static/css/mobile/publish.css">
</head>
<body>
	<div class="wrap">
		<!--头部-->
		<div class="header box">
			<a href="" class="left" id="back">
				<img src="<?php echo $PREFIX;?>/static/img/green-arrow-left.png" alt="返回">
			</a>
			<a href="/" class="logo">
				<img src="<?php echo $PREFIX;?>/static/img/logo.png" alt="">
			</a>
			<a href="" class="right" id="publish" type="reply">
				<img src="<?php echo $PREFIX;?>/static/img/ok.svg" alt="确认">
			</a>
		</div>
		<!--中部-->
		<div class="main">
			<!--内容-->
			<div class="body">
				<input type="hidden" id="session_id" name="session_id" value="<?php echo session_id();?>">
				<input type="hidden" id="postings_id" name="postings_id" value="<?php echo $postingsId;?>">
				<div id="toolbar" class="toolbar"></div>
				<div id="editor" class="editor"></div>
			</div>
		</div>
		<!--尾部-->
		<div class="footer"></div>
	</div>
	<script src="<?php echo $PREFIX;?>/static/js/jquery-1.12.4.min.js"></script>
	<script src="<?php echo $PREFIX;?>/static/js/Tip.js"></script>
	<script src="<?php echo $PREFIX;?>/static/js/wangEditor/wangEditor.min.js"></script>
	<script src="<?php echo $PREFIX;?>/static/js/publish.js"></script>
</body>
</html>