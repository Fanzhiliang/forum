<?php
try {
	session_start();
	$user = $_SESSION["user"];
	$pageNo = intval($_POST['pageNo']);
	$keywords = $_POST['keywords'];
	if(!$pageNo || is_nan($pageNo)){
		$pageNo = 1;
	}
	include_once('controller/Dao.php');
	$dao = new Dao();
	$currUser = $dao->checkLogin($user['account'],$user['password']);
	if($currUser['user_id']>0){
		setcookie(session_name(),session_id(),time()+(24*3600),"/");
		$_SESSION["user"] = $currUser;
	}else{//未激活
		$currUser = null;
		$_SESSION["user"] = null;
	}
	
	$postData = $dao->getPostingsList($keywords,$pageNo);
?>
<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="UTF-8">
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<!-- 初始比例=1 -->
	<meta http-equiv="X-UA-Compatible" content="IE=edge">
	<!-- 使ie以最高级渲染 -->
	<title>论坛</title>
	<script src="<?php echo $PREFIX;?>/static/js/init.js"></script>
	<!-- [if lt IE 9]>
		<script src="<?php echo $PREFIX;?>/static/js/html5shiv.min.js"></script>
		<script src="<?php echo $PREFIX;?>/static/js/respond.min.js"></script>
	<![endif]-->
	<link rel="stylesheet" href="<?php echo $PREFIX;?>/static/css/pc/main.css">
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
					<div class="search-frame">
						<form action="/" method="post" id="search">
							<input type="text" id="keywords" name="keywords" value="<?php echo $keywords;?>">
							<input type="hidden" id="session_id" name="session_id" value="<?php echo session_id(); ?>">
							<input type="hidden" id="pageNo" name="pageNo" value="<?php echo $postData['pageNo'];?>">
						</form>
						<a href="" id="toggle-search">
							<img src="<?php echo $PREFIX;?>/static/img/search.svg" alt="搜索">
						</a>
					</div>
				</div>
			</div>
		</div>
		<!--中部-->
		<div class="main">
			<div class="main-primary">
				<!--内容-->
				<div class="body box">
					<!--循环输出部分开始-->
					<?php
					if(count($postData['list']) > 0){
						foreach ($postData['list'] as $post) {
							$user = $dao->getUserById($post['user_id']);
							if($user['user_id'] > 0){
							$replyCount = $dao->getFloorCountById($post['postings_id']);
							$floorOne = $dao->getFloorOne($post['postings_id']);
					?>
					<a href="/postings/<?php echo $post['postings_id']; ?>" class="postings" target="_blank">
						<div class="up-row">
							<div class="left-col">
								<div class="reply-count"><?php echo $post['reply_count'];?></div>
							</div>
							<div class="middle-col">
								<div class="title">
									<?php echo $post['title']; ?>
								</div>
							</div>
							<div class="right-col">
								<div class="author">
									<?php echo $user['name']; ?>
								</div>
							</div>
						</div>
						<div class="down-row">
							<div class="value">
								<?php echoTags(json_decode($floorOne['value'],true),false,true,false); ?>
							</div>
							<div class="time"><?php echo $post['time'];?></div>
						</div>
					</a>
					<?php }}}else{ ?>
					<h1 class="no-postings">找不到贴子</h1>
					<?php } ?>
					<!--循环输出部分结束-->
					<ul class="pager">
						<?php if($postData['pageNo'] > 1){ ?>
						<li><a href="1">首页</a></li>
						<li><a href="<?php echo $postData['pageNo']-1;?>" class="Previous"><span>&laquo;</span>上一页</a></li>
						<?php } ?>
						<?php if($postData['pageNo']-2 >= 1){ ?>
						<li><a href="<?php echo $postData['pageNo']-2;?>"><?php echo $postData['pageNo']-2;?></a></li>
						<?php } ?>
						<?php if($postData['pageNo']-1 >= 1){ ?>
						<li><a href="<?php echo $postData['pageNo']-1;?>"><?php echo $postData['pageNo']-1;?></a></li>
						<?php } ?>
						<li class="on"><a href="#"><?php echo $postData['pageNo'];?></a></li>
						<?php if($postData['pageNo']+1 <= $postData['totalPage']){ ?>
						<li><a href="<?php echo $postData['pageNo']+1;?>"><?php echo $postData['pageNo']+1;?></a></li>
						<?php } ?>
						<?php if($postData['pageNo']+2 <= $postData['totalPage']){ ?>
						<li><a href="<?php echo $postData['pageNo']+2;?>"><?php echo $postData['pageNo']+2;?></a></li>
						<?php } ?>
						<?php if($postData['pageNo'] < $postData['totalPage']){ ?>
						<li><a href="<?php echo $postData['pageNo']+1;?>" class="Next">下一页<span>&raquo;</span></a></li>
						<?php } ?>
						<li><a href="<?php echo $postData['totalPage'];?>" class="rear">尾页</a></li>
					</ul>
					<div class="editor-row">
						<div class="editor-header">
							<img src="<?php echo $PREFIX;?>/static/img/edit-icon.svg" alt="">
							<div class="text">发表新帖</div>
						</div>
						<div class="editor-frame">
							<input type="text" id="title" name="title" placeholder="请输入标题" />
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
						<a href="/home" class="left-col" target="_blank">
							<img src="<?php echo $currUser['head_img'];?>" alt="头像">
						</a>
						<div class="right-col">
							<div class="name">
								<a href="/home" class="name-text" title="<?php echo $currUser['name'];?>" target="_blank"><?php echo $currUser['name'];?></a>
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
	<script src="<?php echo $PREFIX;?>/static/js/main.js"></script>
	<script src="<?php echo $PREFIX;?>/static/js/pc-sidebar.js"></script>
</body>
</html>
<?php
} catch (Exception $e) {
	echo $e->getMessage()."<br>";
}
?>