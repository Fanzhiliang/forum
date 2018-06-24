<?php
try {
	session_start();
	$user = $_SESSION["user"];

	include_once('controller/Dao.php');
	$dao = new Dao();

	$currUser = $dao->checkLogin($user['account'],$user['password']);;
	$part = $_GET['param0'];
	if($currUser['user_id'] > 0){
		setcookie(session_name(),session_id(),time()+(24*3600),"/");
		$_SESSION["user"] = $currUser;
	}else{
		sendPost("$PREFIX/login",['message'=>'还没登录,请登录!','url'=>"$controller/$part"]);
	}

	$isHome = false;
	$postingsData = null;
	$floorData = null;
	$keepData = null;
	$pageNo = $_POST['pageNo'];
	if(!$pageNo){
		$pageNo = 1;
	}

	if($part == 'myPostings'){
		$postingsData = $dao->getPostingsByUserId($currUser['user_id'],$pageNo);
	}else if($part == 'myReply'){
		$ReplyData = $dao->getFloorReplyByUserId($currUser['user_id'],$pageNo);
	}else if($part == 'myKeep'){
		$keepData = $dao->getKeepByUserId($currUser['user_id'],$pageNo);
	}else if(!$part){
		$isHome = true;
	}else{
		header('location:/tip?message=!'.urlencode('404 Not Found'));
        die;
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
	<title>个人中心</title>
	<link rel="shortcut icon" type="image/x-icon" href="<?php echo $PREFIX;?>/static/img/logo.ico">
	<script src="<?php echo $PREFIX;?>/static/js/init.js"></script>
	<!-- [if lt IE 9]>
		<script src="<?php echo $PREFIX;?>/static/js/html5shiv.min.js"></script>
		<script src="<?php echo $PREFIX;?>/static/js/respond.min.js"></script>
	<![endif]-->
	<link rel="stylesheet" href="<?php echo $PREFIX;?>/static/css/pc/home.css">
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
					<a href="/" class="logo left freenet">
						<img src="<?php echo $PREFIX;?>/static/img/freenet.png" alt="">
					</a>
				</div>
				<input type="hidden" id="session_id" value="<?php echo session_id();?>">
			</div>
		</div>
		<!--中部-->
		<div class="main">
			<div class="main-primary">
				<!--内容-->
				<div class="body box">
					<ul class="nav">
						<li <?php if($isHome){echo 'class="on"';} ?> ><a href="/home">修改信息</a></li>
						<li <?php if($part == 'myPostings'){echo 'class="on"';} ?> ><a href="/home/myPostings">我的贴子</a></li>
						<li <?php if($part == 'myReply'){echo 'class="on"';} ?> ><a href="/home/myReply">我的回复</a></li>
						<li <?php if($part == 'myKeep'){echo 'class="on"';} ?> ><a href="/home/myKeep">收藏贴子</a></li>
						<li><a href="/forget?email=<?php echo $currUser['email'];?>">修改密码</a></li>
						<!-- 第一个li的class为on move-0  第二个li的class为on move-1 如此类推-->
						<div class="move-obj move-2"></div>
					</ul>
					<!-- 个人信息页面 -->
					<?php if($isHome){ ?>
					<div class="info">
					<form action="" id="updateForm" method="post">
						<div class="left-col">
							<div id="start-upload">
								<img src="<?php echo $currUser['head_img'];?>" alt="头像" class="head">
								<img src="<?php echo $PREFIX;?>/static/img/upload.png" alt="上传" class="upload-img">
							</div>
							<div class="preview" id="preview">
								<div class="title">预览头像</div>
								<div class="preview-img-frame">
									<img src="" id="preview-img">
								</div>
								<button id="save-head" class="btn">保存头像</button>
								<button id="no-save-head" class="btn">取消选择</button>
							</div>
							<input type="file" id="upload-image" name="upload-image">
						</div>
						<div class="right-col">
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
							<div class="info-frame">
								<input type="hidden" id="upload-param" name="upload-param">
								<div class="frame-row">
									<label for="name">昵称:</label>
									<input type="text" id="name" name="name" value="<?php echo $currUser['name'];?>" placeholder="<?php echo 'userid_'.$currUser['user_id'];?>">
								</div>
								<div class="frame-row">
									<input type="hidden" id="sex" name="sex" value="<?php echo $currUser['sex'];?>">
									<label for="sex">性别:</label>
									<div class="radio <?php if($currUser['sex']=='male'){echo 'on';}?>" value="male">男</div>
									<div class="radio <?php if($currUser['sex']=='female'){echo 'on';}?>" value="female">女</div>
									<span class="sex-tip">(请选择性别)</span>
								</div>
							</div>
						</div>
						<div class="bottom-row">
							<button id="save-all" type="submit">保存</button>
						</div>
					</form>
					</div>
					<?php }else if($part == 'myPostings'){ ?>
					<!-- 我的贴子页面 -->
					<?php if(count($postingsData['list'])>0){ ?>
					<div class="postings-list">
						<form action="/home/myPostings" id="myForm" method="post" style="display: none;">
							<input type="hidden" id="pageNo" name="pageNo" value="<?php echo $pageNo;?>">
						</form>
						<?php foreach ($postingsData['list'] as $post) { ?>
						<div class="postings">
							<img src="<?php echo $PREFIX;?>/static/img/text.svg" alt="" class="text-icon">
							<a href="/postings/<?php echo $post['postings_id'];?>" class="title" target="_blank">
								<?php echo $post['title']; ?>
							</a>
							<div class="reply-col">
								<img src="<?php echo $PREFIX;?>/static/img/reply.svg" alt="">
								<span class="reply-count"><?php echo $post['reply_count']; ?></span>
							</div>
							<div class="time"><?php echo $post['time']; ?></div>
						</div>
						<?php } ?>
					</div>
					<!-- 分页 -->
					<ul class="pager">
						<?php if($postingsData['pageNo'] > 1){ ?>
						<li><a href="<?php echo $postingsData['pageNo']-1;?>" class="Previous"><span>&laquo;</span>上一页</a></li>
						<?php } ?>
						<?php if($postingsData['pageNo']-2 >= 1){ ?>
						<li><a href="<?php echo $postingsData['pageNo']-2;?>"><?php echo $postingsData['pageNo']-2;?></a></li>
						<?php } ?>
						<?php if($postingsData['pageNo']-1 >= 1){ ?>
						<li><a href="<?php echo $postingsData['pageNo']-1;?>"><?php echo $postingsData['pageNo']-1;?></a></li>
						<?php } ?>
						<li class="on"><a href="#"><?php echo $postingsData['pageNo'];?></a></li>
						<?php if($postingsData['pageNo']+1 <= $postingsData['totalPage']){ ?>
						<li><a href="<?php echo $postingsData['pageNo']+1;?>"><?php echo $postingsData['pageNo']+1;?></a></li>
						<?php } ?>
						<?php if($postingsData['pageNo']+2 <= $postingsData['totalPage']){ ?>
						<li><a href="<?php echo $postingsData['pageNo']+2;?>"><?php echo $postingsData['pageNo']+2;?></a></li>
						<?php } ?>
						<?php if($postingsData['pageNo'] < $postingsData['totalPage']){ ?>
						<li><a href="<?php echo $postingsData['pageNo']+1;?>" class="Next">下一页<span>&raquo;</span></a></li>
						<?php } ?>
						<li><a href="<?php echo $postingsData['totalPage'];?>" class="rear">尾页</a></li>
					</ul>
					<?php }else{ ?>
					<h1 class="no-list">没有发布过贴子</h1>
					<?php }}else if($part == 'myReply'){ ?>
					<!-- 我的回复页面 -->
					<?php if(count($ReplyData['list'])>0){ ?>
					<div class="reply-list">
						<form action="/home/myReply" id="myForm" method="post" style="display: none;">
							<input type="hidden" id="pageNo" name="pageNo" value="<?php echo $pageNo;?>">
						</form>
						<form action="" method="post" id="postToPostings" style="display: none;" target="_blank">
							<input type="hidden" name="pageNo" value="">
							<input type="=hidden" name="floor_no" value="">
						</form>
						<?php foreach ($ReplyData['list'] as $object) { 
								$post = $dao->getPostingByPostingsId($object['postings_id']);
						?>
						<div class="replys">
							<?php if($object['type'] == 'floor'){ ?>
							<img src="<?php echo $PREFIX;?>/static/img/text.svg" alt="" class="text-icon">
							<div class="post">
								<span>原帖: </span>
								<a href="/postings/<?php echo $post['postings_id'];?>" tag="<?php echo $post['postings_id'];?>&1" target="_blank">
									<?php echo $post['title']; ?>
								</a>
							</div>
							<div class="time"><?php echo $object['time'];?></div>
							<div class="you-reply">
								<span>你的楼层: </span>
								<a href="/postings/<?php echo $post['postings_id'];?>" tag="<?php echo $post['postings_id'];?>&<?php echo $object['floor_no'];?>" target="_blank">
									<?php echoTags(json_decode($object['value'],true),false,false); ?>
								</a>
							</div>
							<?php } ?>
							<?php if($object['type'] == 'reply'){ 
									$floor = $dao->getFloorById($object['floor_id']);
							?>
							<img src="<?php echo $PREFIX;?>/static/img/building.svg" alt="" class="text-icon">
							<div class="post">
								<span>楼层: </span>
								<a href="/postings/<?php echo $post['postings_id'];?>" tag="<?php echo $post['postings_id'];?>&<?php echo $floor['floor_no'];?>" target="_blank">
									<?php echoTags(json_decode($floor['value'],true),false,false); ?>
								</a>
							</div>
							<div class="time"><?php echo $object['time'];?></div>
							<div class="you-reply">
								<span>你的回复: </span>
								<a href="/postings/<?php echo $post['postings_id'];?>" tag="<?php echo $post['postings_id'];?>&<?php echo $floor['floor_no'];?>" target="_blank">
									<?php echoTags(json_decode($object['value'],true),false,false); ?>
								</a>
							</div>
							<?php } ?>
						</div>
						<?php  }?>
					</div>
					<!-- 分页 -->
					<ul class="pager">
						<?php if($ReplyData['pageNo'] > 1){ ?>
						<li><a href="<?php echo $ReplyData['pageNo']-1;?>" class="Previous"><span>&laquo;</span>上一页</a></li>
						<?php } ?>
						<?php if($ReplyData['pageNo']-2 >= 1){ ?>
						<li><a href="<?php echo $ReplyData['pageNo']-2;?>"><?php echo $ReplyData['pageNo']-2;?></a></li>
						<?php } ?>
						<?php if($ReplyData['pageNo']-1 >= 1){ ?>
						<li><a href="<?php echo $ReplyData['pageNo']-1;?>"><?php echo $ReplyData['pageNo']-1;?></a></li>
						<?php } ?>
						<li class="on"><a href="#"><?php echo $ReplyData['pageNo'];?></a></li>
						<?php if($ReplyData['pageNo']+1 <= $ReplyData['totalPage']){ ?>
						<li><a href="<?php echo $ReplyData['pageNo']+1;?>"><?php echo $ReplyData['pageNo']+1;?></a></li>
						<?php } ?>
						<?php if($ReplyData['pageNo']+2 <= $ReplyData['totalPage']){ ?>
						<li><a href="<?php echo $ReplyData['pageNo']+2;?>"><?php echo $ReplyData['pageNo']+2;?></a></li>
						<?php } ?>
						<?php if($ReplyData['pageNo'] < $ReplyData['totalPage']){ ?>
						<li><a href="<?php echo $ReplyData['pageNo']+1;?>" class="Next">下一页<span>&raquo;</span></a></li>
						<?php } ?>
						<li><a href="<?php echo $ReplyData['totalPage'];?>" class="rear">尾页</a></li>
					</ul>
					<?php }else{ ?>
					<h1 class="no-list">没有过回复</h1>
					<?php }}else if($part == 'myKeep'){ ?>
					<!-- 收藏贴子页面 -->
					<?php if(count($keepData['list'])>0){ ?>
					<div class="postings-list">
						<form action="/home/myKeep" id="myForm" method="post" style="display: none;">
							<input type="hidden" id="pageNo" name="pageNo" value="<?php echo $pageNo;?>">
						</form>
						<?php foreach ($keepData['list'] as $post) { ?>
						<div class="postings">
							<img src="<?php echo $PREFIX;?>/static/img/text.svg" alt="" class="text-icon">
							<a href="/postings/<?php echo $post['postings_id'];?>" class="title" target="_blank">
								<?php echo $post['title']; ?>
							</a>
							<div class="reply-col">
								<img src="<?php echo $PREFIX;?>/static/img/reply.svg" alt="">
								<span class="reply-count"><?php echo $post['reply_count']; ?></span>
							</div>
							<div class="time"><?php echo $post['time']; ?></div>
						</div>
						<?php } ?>
					</div>
					<!-- 分页 -->
					<ul class="pager">
						<?php if($keepData['pageNo'] > 1){ ?>
						<li><a href="<?php echo $keepData['pageNo']-1;?>" class="Previous"><span>&laquo;</span>上一页</a></li>
						<?php } ?>
						<?php if($keepData['pageNo']-2 >= 1){ ?>
						<li><a href="<?php echo $keepData['pageNo']-2;?>"><?php echo $keepData['pageNo']-2;?></a></li>
						<?php } ?>
						<?php if($keepData['pageNo']-1 >= 1){ ?>
						<li><a href="<?php echo $keepData['pageNo']-1;?>"><?php echo $keepData['pageNo']-1;?></a></li>
						<?php } ?>
						<li class="on"><a href="#"><?php echo $keepData['pageNo'];?></a></li>
						<?php if($keepData['pageNo']+1 <= $keepData['totalPage']){ ?>
						<li><a href="<?php echo $keepData['pageNo']+1;?>"><?php echo $keepData['pageNo']+1;?></a></li>
						<?php } ?>
						<?php if($keepData['pageNo']+2 <= $keepData['totalPage']){ ?>
						<li><a href="<?php echo $keepData['pageNo']+2;?>"><?php echo $keepData['pageNo']+2;?></a></li>
						<?php } ?>
						<?php if($keepData['pageNo'] < $keepData['totalPage']){ ?>
						<li><a href="<?php echo $keepData['pageNo']+1;?>" class="Next">下一页<span>&raquo;</span></a></li>
						<?php } ?>
						<li><a href="<?php echo $keepData['totalPage'];?>" class="rear">尾页</a></li>
					</ul>
					<?php }else{ ?>
					<h1 class="no-list">没有收藏贴子</h1>
					<?php }} ?>
				</div>
				<!--边栏-->
				<div class="sidebar box">
					<!--未登录 display: block 下面的user-area不输出-->
					<div class="no-login" style="<?php if($currUser){echo 'display: none;';} ?>">
						<a href="/login">还没登录,请登录!</a>
					</div>
					<!--已登录 display: none 上面的no-login依然输出-->
					<?php if($currUser){ ?>
					<div class="user-area">
						<a href="/home" class="left-col">
							<img src="<?php echo $currUser['head_img'];?>" alt="头像">
						</a>
						<div class="right-col">
							<div class="name">
								<a href="/home" class="name-text" title="<?php echo $currUser['name'];?>"><?php echo $currUser['name'];?></a>
								<img src="<?php echo $PREFIX;?>/static/img/<?php echo $currUser['sex'];?>.svg" alt="<?php echo $currUser['sex'];?>" class="sex">
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
								<?php if(intval($currUser['is_sign']) === 0){ ?>
								<a href="" id="sign-in" class="sign-in btn">签到</a>
								<?php }else{ ?>
								<div class="sign-in final btn">已签到</div>
								<?php } ?>
								<a href="" id="logout" class="logout btn">注销</a>
							</div>
						</div>
					</div>
					<?php } ?>
					<div class="ctrl">
						<a href="" class="ctrl-item">
							<img src="<?php echo $PREFIX;?>/static/img/refresh.svg" alt="刷新" titleTip="刷新">
						</a>
						<div class="ctrl-item" id="back-top">
							<img src="<?php echo $PREFIX;?>/static/img/arrow-top.svg" alt="回到顶部" titleTip="回到顶部">
						</div>
					</div>
					<div class="download-app">
						<div class="title-1">扫二维码下载app、访问官网</div>
						<div class="title-2">
							<div class="up-row">下载freenet</div>
							<div class="down-row">共享账号,轻松上网!</div>
						</div>
						<div class="code">
							<img src="<?php echo $PREFIX;?>/static/img/code.png" alt="二维码">
						</div>
						<a href="/" class="logo">
							<img src="<?php echo $PREFIX;?>/static/img/freenet.png" alt="官网">
						</a>
					</div>
				</div>
			</div>
		</div>
		<!--尾部-->
		<div class="footer"></div>
	</div>
	<script src="<?php echo $PREFIX;?>/static/js/jquery-1.12.4.min.js"></script>
	<script src="<?php echo $PREFIX;?>/static/js/TitleTip.js"></script>
	<script src="<?php echo $PREFIX;?>/static/js/Tip.js"></script>
	<script src="<?php echo $PREFIX;?>/static/js/home.js"></script>
	<script src="<?php echo $PREFIX;?>/static/js/edit_user.js"></script>
	<script src="<?php echo $PREFIX;?>/static/js/pc-sidebar.js"></script>
</body>
</html>
<?php
} catch (Exception $e) {
	echo $e->getMessage()."<br>";
}
?>