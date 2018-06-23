<?php
	include_once('controller/Dao.php');
	$dao = new Dao();
	session_start();
	$user = $_SESSION["user"];
	$currUser = $dao->checkLogin($user['account'],$user['password']);
	$floorId = intval($_GET['param0']);
	if(!$user || $currUser['user_id'] < 1){
		sendPost("$PREFIX/login",['message'=>'还没登录,请登录!','url'=>"$controller/$floorId"]);
	}
	setcookie(session_name(),session_id(),time()+(24*3600),"/");
	$_SESSION["user"] = $currUser;

	$floorData = $dao->getFloorById($floorId);//楼层信息
	$floorUser = $dao->getUserById($floorData['user_id']);////发表楼层用户信息
	$replyData = $dao->getAllReplyByFloorId($floorId);//所有回复

	if(!$floorData){
		header('location:/tip?message='.urlencode('楼层不存在!'));
	}

?>
<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="UTF-8">
	<meta name="viewport" content="width=device-width,initial-scale=1.0,maximum-scale=1.0,user-scalable=no">
	<!-- 初始比例=1 最大缩放=1 禁止缩放=移动端 -->
	<meta http-equiv="X-UA-Compatible" content="IE=edge">
	<!-- 使ie以最高级渲染 -->
	<title>论坛帖子</title>
	<script src="<?php echo $PREFIX;?>/static/js/init.js"></script>
	<link rel="stylesheet" href="<?php echo $PREFIX;?>/static/css/mobile/publish_replys.css">
</head>
<body>
	<div class="wrap">
		<!--头部-->
		<div class="header box">
			<a href="" class="left" id="back">
				<img src="<?php echo $PREFIX;?>/static/img/green-arrow-left.png" alt="返回">
			</a>
			
			<a href="/" class="logo">
				<img src="<?php echo $PREFIX;?>/static/img/logo.png" alt="logo">
			</a>
		</div>
		<!--中部-->
		<div class="main">
			<!--内容-->
			<div class="body">
				<div class="floor">
					<div class="up-row">
						<div class="left-col">
							<img src="<?php echo $floorUser['head_img']; ?>" alt="用户头像">
						</div>
						<div class="middle-col">
							<div class="author">
								<?php echo $floorUser['name']; ?>
								<img src="<?php echo $PREFIX;?>/static/img/<?php echo $floorUser['sex']; ?>.svg" alt="<?php echo $floorUser['sex']; ?>" class="sex">
								<img src="<?php echo $PREFIX;?>/static/img/level-<?php echo $floorUser['level']; ?>.svg" alt="等级" class="level">
							</div>
							<!--除1楼外显示楼数-->
							<div class="age">8小时前</div>
						</div>
						<div class="right-col">
							<img src="<?php echo $PREFIX;?>/static/img/more.png" alt="操作" class="more">
							<ul class="ctrl-nav">
								<li><a href="<?php echo $floorData['floor_id'];?>" class="delete-floor"><img src="<?php echo $PREFIX;?>/static/img/delete.svg"><span>删除</span></a></li>
							</ul>
						</div>
					</div>
					<div class="down-row">
						<?php echoTags(json_decode($floorData['value'],true)); ?>
						<div class="reply-frame">
							<!--楼中楼部分-->
							<?php 
							foreach ($replyData['list'] as $reply) { 
								$thatUser = $dao->getUserById($reply['user_id']);
								if($thatUser['user_id'] > 0){
							?>
							<div class="reply-row">
								<span class="name"><?php echo $thatUser['name']; ?> : </span>
								<?php echoTags(json_decode($reply['value'],true),false); ?>
								<span class="time"><?php echo $reply['time']; ?></span>
								<?php if($currUser['user_id'] == $reply['user_id']){ ?>
								<a href="<?php echo $reply['reply_id']; ?>" class="delete-reply">删除</a>
								<?php } ?>
							</div>
							<?php }} ?>
						</div>
					</div>
				</div>
			</div>
		</div>
		<!--尾部-->
		<div class="footer" method="post">
			<div class="reply-editor" id="reply-editor"></div>
			<div class="emoticon" id="reply-bar"></div>
			<input type="hidden" id="floor_id" name="floor_id" value="<?php echo $floorData['floor_id'];?>">
			<input type="hidden" id="session_id" name="session_id" value="<?php echo session_id();?>">
			<button class="publish-reply" id="reply-btn">回复</button>
		</div>
	</div>
	<script src="<?php echo $PREFIX;?>/static/js/jquery-1.12.4.min.js"></script>
	<script src="<?php echo $PREFIX;?>/static/js/Tip.js"></script>
	<script src="<?php echo $PREFIX;?>/static/js/wangEditor/wangEditor.min.js"></script>
	<script src="<?php echo $PREFIX;?>/static/js/publish.js"></script>
	<script src="<?php echo $PREFIX;?>/static/js/publish_replys.js"></script>
</body>
</html>