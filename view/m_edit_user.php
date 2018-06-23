<?php
	include_once('controller/Dao.php');
	$dao = new Dao();
	session_start();
	$user = $_SESSION["user"];
	$currUser = $dao->checkLogin($user['account'],$user['password']);
	if(!$user || $currUser['user_id'] < 1){
		sendPost("$PREFIX/login",['message'=>'还没登录,请登录!','url'=>$controller]);
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
	<title>修改用户</title>
	<script src="<?php echo $PREFIX;?>/static/js/init.js"></script>
	<link rel="stylesheet" href="<?php echo $PREFIX;?>/static/css/mobile/edit_user.css">
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
			<form action="" id="updateForm" method="post">
				<!--内容-->
				<div class="body">
					<ul class="nav">
						<li><span>修改信息</span></li>
						<li><a href="/forget?email=<?php echo $currUser['email'];?>">修改密码</a></li>
					</ul>
					<div class="head">
						<div class="head-main" id="start-upload">
							<img src="<?php echo $currUser['head_img'];?>" alt="用户头像" class="head-img">
							<img src="<?php echo $PREFIX;?>/static/img/upload.png" alt="上传" class="upload-img">
						</div>
					</div>
					<input type="hidden" id="sex" name="sex" value="<?php echo $currUser['sex'];?>">
					<input type="hidden" id="head-img-url" name="head-img-url">
					<input type="hidden" id="session_id" value="<?php echo session_id(); ?>">
					<div class="input-row">
						<label for="name">昵称</label>
						<input type="text" id="name" name="name" placeholder="<?php echo 'userid_'.$currUser['user_id'];?>" value="<?php echo $currUser['name'];?>">
					</div>
					<div class="input-row">
						<label for="sex">性别</label>
						<span id="sex-result">
							<?php echo $currUser['sex']=='male'?'男':'女'; ?><img src="<?php echo $PREFIX;?>/static/img/<?php echo $currUser['sex'];?>.svg" alt="<?php echo $currUser['sex'];?>">
						</span>
						<span id="sex-list" <?php if($currUser['sex']!='male'&&$currUser['sex']!='female'){echo 'style="display:block"';}?>>
							<span value="male">
								男<img src="<?php echo $PREFIX;?>/static/img/male.svg" alt="male">
							</span>
							<span value="female">
								女<img src="<?php echo $PREFIX;?>/static/img/female.svg" alt="female">
							</span>
							<span class="sex-tip">(请选择性别)</span>
						</span>
					</div>
					<div class="input-row">
						<button type="submit" id="save">保存</button>
					</div>
				</div>
				<div class="sidebar">
					<div class="preview" id="preview">
						<div class="preview-img-frame">
							<img src="" id="preview-img">
						</div>
						<button id="save-head" class="btn">保存头像</button>
						<button id="no-save-head" class="btn">取消选择</button>
					</div>
					<input type="file" id="upload-image" name="upload-image">
					<div class="image-frame" id="image-frame">
						<img src="" class="bg" id="bg">
						<div class="mask"></div>
						<div class="selecter" id="selecter">
							<div class="selecter-frame" id="selecter-frame">
								<img src="" class="selecter-bg" id="selecter-bg">
							</div>
							<div class="point tl"></div>
							<div class="point tc"></div>
							<div class="point tr"></div>
							<div class="point cl"></div>
							<div class="point cr"></div>
							<div class="point dl"></div>
							<div class="point dc"></div>
							<div class="point dr"></div>
						</div>
					</div>
				</div>
			</form>
		</div>
	</div>
	<script src="<?php echo $PREFIX;?>/static/js/jquery-1.12.4.min.js"></script>
	<script src="<?php echo $PREFIX;?>/static/js/Tip.js"></script>
	<script src="<?php echo $PREFIX;?>/static/js/edit_user.js"></script>
</body>
</html>