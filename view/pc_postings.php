<?php
try {
	session_start();
	$user = $_SESSION["user"];

	include_once('controller/Dao.php');
	$dao = new Dao();

	$currUser = null;
	if($user){
		$currUser = $dao->checkLogin($user['account'],$user['password']);
		setcookie(session_name(),session_id(),time()+(24*3600),"/");
		$_SESSION["user"] = $currUser;
	}

	$postingsId = intval($_GET['param0']);
	$thatPostings = $dao->getPostingByPostingsId($postingsId);
	if(!$thatPostings || $thatPostings['is_ban'] == 1){//没有这个贴子
		header('location:/tip?message='.urlencode('贴子不存在!'));
	}

	$pageNo = intval($_POST['pageNo']);
	if(!$pageNo || is_nan($pageNo)){
		$pageNo = 1;
	}

	$floorNo = intval($_POST['floor_no']);
	if(!$floorNo || is_nan($floorNo)){
		$floorNo = 0;
	}

	$floorData = $dao->getFloorList($postingsId,$pageNo);
	$keepData = null;
	$isKeep = false;

	if($currUser){
		$keepData = $dao->isKeepByUserId($currUser['user_id'],$thatPostings['postings_id']);
		if($keepData){
			$isKeep = $keepData;
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
	<title><?php echo $thatPostings['title'];?></title>
	<link rel="shortcut icon" type="image/x-icon" href="<?php echo $PREFIX;?>/static/img/logo.ico">
	<script src="<?php echo $PREFIX;?>/static/js/init.js"></script>
	<!-- [if lt IE 9]>
		<script src="<?php echo $PREFIX;?>/static/js/html5shiv.min.js"></script>
		<script src="<?php echo $PREFIX;?>/static/js/respond.min.js"></script>
	<![endif]-->
	<link rel="stylesheet" href="<?php echo $PREFIX;?>/static/css/pc/postings.css">
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
			</div>
			<form action="/postings/<?php echo $thatPostings['postings_id'];?>" method="post" id="myForm">
				<input type="hidden" id="session_id" value="<?php echo session_id();?>">
				<input type="hidden" id="pageNo" name="pageNo" value="<?php echo $floorData['pageNo'];?>">
				<input type="hidden" id="floor_no" value="<?php echo $floorNo;?>" >
				<input type="hidden" id="postings_id" value="<?php echo $thatPostings['postings_id'];?>" >
			</form>
		</div>
		<!--中部-->
		<div class="main">
			<div class="main-primary">
				<!--内容-->
				<div class="body box">
					<div class="title" title="<?php echo $thatPostings['title'];?>">
						<?php echo $thatPostings['title'];?>
						<?php if(!$currUser || !$keepData || !$isKeep){ ?>
						<a href="" class="right" id="keep">
							<img src="<?php echo $PREFIX;?>/static/img/keep.svg" alt="收藏" title="收藏">
							<input type="hidden" id="user_id" value="<?php echo $currUser['user_id'];?>">
							<input type="hidden" id="postings_id" value="<?php echo $thatPostings['postings_id'];?>">
						</a>
						<?php } else{?>
						<a href="" class="right" id="keep">
							<img src="<?php echo $PREFIX;?>/static/img/kept.svg" alt="取消收藏" title="取消收藏">
							<input type="hidden" id="keep_id" value="<?php echo $isKeep['keep_id'];?>">
						</a>
						<?php } ?>
					</div>
					<!--循环输出部分开始-->
					<?php
					foreach ($floorData['list'] as $floor) {
						$floorUser = $dao->getUserById($floor['user_id']);
						if($floorUser['user_id'] > 0){
					?>
					<div class="floor" id="floor-<?php echo $floor['floor_no'];?>">
						<div class="author">
							<?php if($thatPostings['user_id'] == $floor['user_id']){ ?>
							<img src="<?php echo $PREFIX;?>/static/img/louzhu.png" class="louzhu-img">
							<?php } ?>
							<span class="head">
								<img src="<?php echo $floorUser['head_img'];?>" alt="头像">
							</span>
							<div class="name">
								<span class="name-text" title="<?php echo $floorUser['name'];?>"><?php echo $floorUser['name'];?></span>
								<div class="icon-row">
									<img src="<?php echo $PREFIX;?>/static/img/<?php echo $floorUser['sex'];?>.svg" alt="<?php echo $floorUser['sex'];?>" class="sex">
									<img src="<?php echo $PREFIX;?>/static/img/level-<?php echo $floorUser['level'];?>.svg" alt="等级" class="level">
								</div>
							</div>
						</div>
						<div class="value">
							<?php echoTags(json_decode($floor['value'],true)) ?>
						</div>
						<div class="bottom-row">
							<div class="time"><?php echo $floor['floor_no'];?>楼&nbsp;&nbsp;<?php echo $floor['time'];?></div>
							<?php 
							if($currUser && $floor['user_id'] == $currUser['user_id']){ 
								if($floor['floor_no'] == 1){
							?>
							<a href="<?php echo $thatPostings['postings_id']; ?>" class="delete-postings">删除</a>
							<?php }else{ ?>
							<a href="<?php echo $floor['floor_id']; ?>" class="delete-floor">删除</a>
							<?php }} ?>
							<?php 
							$replyData = $dao->getReplyList($floor['floor_id'],1);
							if($floor['floor_no'] == 1){
							?>
							<div class="goto-reply">回复</div>
							<?php }else{ ?>
							<div class="toggle-reply">回复(<?php echo count($replyData['list']); ?>)</div>
							<?php } ?>
						</div>
						<?php if($floor['floor_no'] != 1){ ?>
						<div class="replys">
							<!--循环输出部分开始-->
							<div class="reply-frame">
							<?php 
								
								foreach ($replyData['list'] as $reply) {
									$replyUser = $dao->getUserById($reply['user_id']);
									if($replyUser['user_id'] > 0){
							?>
								<div class="reply">
									<span class="left-col">
										<img src="<?php echo $replyUser['head_img'];?>" alt="头像">
									</span>
									<div class="right-col">
										<div class="up-row">
											<span class="name"><?php echo $replyUser['name'];?>:</span>
											<?php echoTags(json_decode($reply['value'],true),false) ?>
										</div>
										<div class="down-row">
											<span class="time"><?php echo $reply['time'];?></span>
											<?php if($currUser['user_id'] == $reply['user_id']){ ?>
											<a href="<?php echo $reply['reply_id'];?>" class="delete-reply">删除</a>
											<?php } ?>
										</div>
									</div>
								</div>
							<?php }} ?>
							</div>
							<div class="reply-bottom">
							<?php if(count($replyData['list'])>0){ ?>
								<input type="hidden" value="<?php echo $floor['floor_id'];?>">
								<ul class="reply-pager">
									<?php if($replyData['pageNo'] > 1){ ?>
									<li><a href="1">首页</a></li>
									<li><a href="<?php echo $replyData['pageNo']-1;?>">上一页</a></li>
									<?php if($replyData['pageNo']-2 >= 1){ ?>
									<li><a href="<?php echo $replyData['pageNo']-2;?>"><?php echo $replyData['pageNo']-2;?></a></li>
									<?php } ?>
									<?php if($replyData['pageNo']-1 >= 1){ ?>
									<li><a href="<?php echo $replyData['pageNo']-1;?>"><?php echo $replyData['pageNo']-1;?></a></li>
									<?php } ?>
									<?php } ?>
									<li><a class="selected"><?php echo $replyData['pageNo'];?></a></li>
									<?php if($replyData['pageNo'] < $replyData['totalPage']){ ?>
									<?php if($replyData['pageNo']+1 <= $replyData['totalPage']){ ?>
									<li><a href="<?php echo $replyData['pageNo']+1;?>"><?php echo $replyData['pageNo']+1;?></a></li>
									<?php } ?>
									<?php if($replyData['pageNo']+2 <= $replyData['totalPage']){ ?>
									<li><a href="<?php echo $replyData['pageNo']+2;?>"><?php echo $replyData['pageNo']+2;?></a></li>
									<?php } ?>
									<li><a href="<?php echo $replyData['pageNo']+1;?>">下一页</a></li>
									<?php } ?>
									<li><a href="<?php echo $replyData['totalPage'];?>">尾页</a></li>
								</ul>
							<?php } ?>
								<!-- 每层楼下面id要唯一 -->
								<button class="publish-reply" id="reply-btn-<?php echo $floor['floor_id'];?>">回复</button>
								<div class="emoticon" id="reply-bar-<?php echo $floor['floor_id'];?>"></div>
								<div class="reply-editor" id="reply-editor-<?php echo $floor['floor_id'];?>"></div>
								<input type="hidden" name="floor_id" value="<?php echo $floor['floor_id'];?>">
							</div>
						</div>
						<?php } ?>
					</div>
					<?php }} ?>
					<!--循环输出部分结束-->
					<ul class="pager">
						<?php if($floorData['pageNo'] > 1){ ?>
						<li><a href="1">首页</a></li>
						<li><a href="<?php echo $floorData['pageNo']-1;?>" class="Previous"><span>&laquo;</span>上一页</a></li>
						<?php } ?>
						<?php if($floorData['pageNo']-2 >= 1){ ?>
						<li><a href="<?php echo $floorData['pageNo']-2;?>"><?php echo $floorData['pageNo']-2;?></a></li>
						<?php } ?>
						<?php if($floorData['pageNo']-1 >= 1){ ?>
						<li><a href="<?php echo $floorData['pageNo']-1;?>"><?php echo $floorData['pageNo']-1;?></a></li>
						<?php } ?>
						<li class="on"><a href="#"><?php echo $floorData['pageNo'];?></a></li>
						<?php if($floorData['pageNo']+1 <= $floorData['totalPage']){ ?>
						<li><a href="<?php echo $floorData['pageNo']+1;?>"><?php echo $floorData['pageNo']+1;?></a></li>
						<?php } ?>
						<?php if($floorData['pageNo']+2 <= $floorData['totalPage']){ ?>
						<li><a href="<?php echo $floorData['pageNo']+2;?>"><?php echo $floorData['pageNo']+2;?></a></li>
						<?php } ?>
						<?php if($floorData['pageNo'] < $floorData['totalPage']){ ?>
						<li><a href="<?php echo $floorData['pageNo']+1;?>" class="Next">下一页<span>&raquo;</span></a></li>
						<?php } ?>
						<li><a href="<?php echo $floorData['totalPage'];?>" class="rear">尾页</a></li>
					</ul>
					<div class="editor-row">
						<div class="editor-header">
							<img src="<?php echo $PREFIX;?>/static/img/edit-icon.svg" alt="">
							<div class="text">回复</div>
						</div>
						<div class="editor-frame">
							<div id="toolbar" class="toolbar"></div>
							<div id="editor" class="editor"></div>
							<button id="publish">发布</button>
						</div>
					</div>
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
						<a href="/home" class="ctrl-item" target="_blank">
							<img src="<?php echo $PREFIX;?>/static/img/account.svg" alt="用户中心" titleTip="用户中心">
						</a>
						<div class="ctrl-item" id="back-bottom">
							<img src="<?php echo $PREFIX;?>/static/img/gray-edit.svg" alt="发帖" titleTip="发帖">
						</div>
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
	<script src="<?php echo $PREFIX;?>/static/js/wangEditor/wangEditor.min.js"></script>
	<script src="<?php echo $PREFIX;?>/static/js/publish.js"></script>
	<script src="<?php echo $PREFIX;?>/static/js/postings.js"></script>
	<script src="<?php echo $PREFIX;?>/static/js/pc-sidebar.js"></script>
</body>
</html>
<?php
} catch (Exception $e) {
	echo $e->getMessage()."<br>";
}
?>