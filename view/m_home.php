<?php
	include_once('controller/Dao.php');
	$dao = new Dao();
	session_start();
	$user = $_SESSION["user"];
	$currUser = $dao->checkLogin($user['account'],$user['password']);
	if($currUser && $currUser['user_id'] < 1){
		sendPost("$PREFIX/login",['message'=>'还没登录,请登录!','url'=>$controller]);
	}
	setcookie(session_name(),session_id(),time()+(24*3600),"/");
	$_SESSION["user"] = $currUser;
	
	$postData = $dao->getPostingsByUserId($currUser['user_id'],1);
	$keepData = $dao->getKeepByUserId($currUser['user_id'],1);
	$ReplyData = $dao->getFloorReplyByUserId($currUser['user_id'],1);
	
	$marginLeft = '-'.(($currUser['level']-1)*100).'%';
?>
<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="UTF-8">
	<meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no">
	<!-- 初始比例=1 最大缩放=1 禁止缩放=移动端 -->
	<title>用户中心</title>
	<script src="<?php echo $PREFIX;?>/static/js/init.js"></script>
	<link rel="stylesheet" href="<?php echo $PREFIX;?>/static/css/mobile/home.css">
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

			<a href="/editUser" class="right">
				<img src="<?php echo $PREFIX;?>/static/img/edit.svg" alt="编辑">
			</a>

			<input type="hidden" id="session_id" value="<?php echo session_id();?>">
		</div>
		<!--中部-->
		<div class="main">
			<!--内容-->
			<div class="body" id="list-scroll">
				<div class="up-row">
					<a href="/editUser" class="left-col">
						<img src="<?php echo $currUser['head_img'];?>" alt="头像">
					</a>
					<div class="right-col">
						<div class="name">
							<a href="/editUser" class="name-text"><?php echo $currUser['name'];?></a>
							<?php if(strlen($currUser['sex'])>0){?>
							<img src="<?php echo $PREFIX;?>/static/img/<?php echo $currUser['sex'];?>.svg" alt="<?php echo $currUser['sex'];?>" class="sex">
							<?php }?>
							<div class="level">
								<img src="<?php echo $PREFIX;?>/static/img/level-<?php echo $currUser['level'];?>.svg" level="<?php echo $currUser['level'];?>">
							</div>
						</div>
						<div class="credits">积分 : 
							<div class="credits-rate">
								<div class="credits-move"></div>
								<div class="rate-text">
									<span class="molecular"><?php echo $currUser['credits'];?></span>
									/
									<span class="denominator"><?php echo $currUser['max_credits'];?></span>
								</div>
							</div>
						</div>
						<div class="btns">
							<?php if($currUser['is_sign']==0){ ?>
								<a href="" id="sign-in" class="sign-in">签到</a>
							<?php }else if($currUser['is_sign']==1){ ?>
								<div class="sign-in final">已签到</div>
							<?php } ?>
							<a href="" id="logout" class="logout">注销</a>
						</div>
					</div>
				</div>
				<div class="slider-trig down-row" belong="list-scroll">
					<span class="on">我的帖子</span>
					<span class="">我的回复</span>
					<span class="">我的收藏</span>
				</div>
				<div class="list-row">
					<div class="slider-item scroll-obj" belong="list-scroll">
						<div class="postings-list">
							<!--循环输出部分开始-->
							<?php 
							if($postData && count($postData['list'])>0){
								foreach ($postData['list'] as $post) { 
							?>
							<a href="/postings/<?php echo $post['postings_id'];?>" class="postings">
								<div class="title">
									<?php echo $post['title'];?>
								</div>
								<div class="time"><?php echo $post['time'];?></div>
								<div class="reply-col">
									<span class="reply-count"><?php echo $post['reply_count'];?></span>
									<img src="<?php echo $PREFIX;?>/static/img/reply.svg" alt="">
								</div>
							</a>
							<?php }}else{ ?>
							<h1 class="no-list">没有发布过帖子</h1>
							<?php } ?>
							<?php if($postData['pageNo'] < $postData['totalPage']){ ?>
							<a href="/myPostings" tag="myPostings&<?php echo $postData['pageNo']+1;?>" class="getMore">
								<span class="text">获得更多内容</span>
								<img class="loading" src="<?php echo $PREFIX;?>/static/img/loading.gif">
							</a>
							<?php } ?>
							<!--循环输出部分结束-->
						</div>
						<div class="reply-list">
							<!--循环输出部分开始-->
							<form action="/postings" method="post" id="postToPostings">
								<input type="hidden" name="pageNo" value="">
								<input type="hidden" name="floor_no" value="">
							</form>
							<?php
							if($ReplyData && count($ReplyData['list'])>0){
								foreach ($ReplyData['list'] as $object) {
									$post = $dao->getPostingByPostingsId($object['postings_id']);
							?>
							<div class="replys">
								<?php if($object['type'] == 'floor'){ ?>
								<a href="/postings/<?php echo $post['postings_id'];?>" tag="<?php echo $post['postings_id'];?>&1" class="post">
									<span>原帖: </span>
									<span><?php echo $post['title'];?></span>
								</a>
								<a href="/postings/<?php echo $post['postings_id'];?>" tag="<?php echo $post['postings_id'];?>&<?php echo $object['floor_no'];?>" class="you-reply">
									<span>你的楼层: </span>
									<?php echoTags(json_decode($object['value'],true),false,false); ?>
								</a>
								<?php } ?>

								<?php if($object['type'] == 'reply'){ 
										$floor = $dao->getFloorById($object['floor_id']);
								?>
								<a href="/postings/<?php echo $post['postings_id'];?>" tag="<?php echo $post['postings_id'];?>&<?php echo $floor['floor_no'];?>" class="post">
									<span>楼层: </span>
									<span>
										<?php echoTags(json_decode($floor['value'],true),false,false);?>
									</span>
								</a>
								<a href="/postings/<?php echo $post['postings_id'];?>" tag="<?php echo $post['postings_id'];?>&<?php echo $floor['floor_no'];?>" class="you-reply">
									<span>你的回复: </span>
									<?php echoTags(json_decode($object['value'],true),false,false); ?>
								</a>
								<?php } ?>
								
								<div class="reply-you">
									<span class="time"><?php echo $object['time']; ?></span>
								</div>
							</div>
							<?php }}else{ ?>
							<h1 class="no-list">没有过回复</h1>
							<?php } ?>
							<?php if($ReplyData['pageNo'] < $ReplyData['totalPage']){ ?>
							<a href="/myReply" tag="myReply&<?php echo $ReplyData['pageNo']+1;?>" class="getMore">
								<span class="text">获得更多内容</span>
								<img class="loading" src="<?php echo $PREFIX;?>/static/img/loading.gif">
							</a>
							<?php } ?>
							<!--循环输出部分结束-->
						</div>
						<div class="keep-list">
							<!--循环输出部分开始-->
							<?php 
							if($keepData && count($keepData['list'])>0){
								foreach ($keepData['list'] as $keep) {
							?>
							<a href="/postings/<?php echo $keep['postings_id'];?>" class="postings">
								<div class="title">
									<?php echo $keep['title'];?>
								</div>
								<div class="time"><?php echo $keep['time'];?></div>
								<div class="reply-col">
									<span class="reply-count"><?php echo $keep['reply_count'];?></span>
									<img src="<?php echo $PREFIX;?>/static/img/reply.svg" alt="评论图标">
								</div>
							</a>
							<?php }}else{ ?>
							<h1 class="no-list">没有收藏贴子</h1>
							<?php } ?>
							<?php if($keepData['pageNo'] < $keepData['totalPage']){ ?>
							<a href="/myKeep" tag="myKeep&<?php echo $keepData['pageNo']+1;?>" class="getMore">
								<span class="text">获得更多内容</span>
								<img class="loading" src="<?php echo $PREFIX;?>/static/img/loading.gif">
							</a>
							<?php } ?>
							<!--循环输出部分结束-->
						</div>
					</div>
				</div>
			</div>
		</div>
		<div class="footer">
			<img src="<?php echo $PREFIX;?>/static/img/arrow-top.svg" alt="回到顶部">
		</div>
	</div>
	<script src="<?php echo $PREFIX;?>/static/js/jquery-1.12.4.min.js"></script>
	<script src="<?php echo $PREFIX;?>/static/js/Tip.js"></script>
	<script src="<?php echo $PREFIX;?>/static/js/Slider.js"></script>
	<script src="<?php echo $PREFIX;?>/static/js/home.js"></script>
</body>
</html>