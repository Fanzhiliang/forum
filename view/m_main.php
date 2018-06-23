<?php
try {
	session_start();
	$user = $_SESSION["user"];
	include_once('controller/Dao.php');
	$dao = new Dao();
	$currUser = $dao->checkLogin($user['account'],$user['password']);;
	if($currUser['user_id']>0){
		setcookie(session_name(),session_id(),time()+(24*3600),"/");
		$_SESSION["user"] = $currUser;
	}else{
		$currUser = null;
		$_SESSION["user"] = null;
	}

	$pageNo = intval($_POST['pageNo']);
	$keywords = $_POST['keywords'];
	if(!$pageNo || is_nan($pageNo)){
		$pageNo = 1;
	}
	
	$postData = $dao->getPostingsList($keywords,$pageNo);
?>
<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="UTF-8">
	<meta name="viewport" content="width=device-width,initial-scale=1.0,maximum-scale=1.0,user-scalable=no">
	<!-- 初始比例=1 最大缩放=1 禁止缩放=移动端 -->
	<meta http-equiv="X-UA-Compatible" content="IE=edge">
	<!-- 使ie以最高级渲染 -->
	<title>论坛主页</title>
	<script src="<?php echo $PREFIX;?>/static/js/init.js"></script>
	<link rel="stylesheet" href="<?php echo $PREFIX;?>/static/css/mobile/main.css">
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

			<a href="/home" class="right">
				<img src="<?php echo $PREFIX;?>/static/img/user.svg" alt="用户">
			</a>

			<a href="/publishPost" class="right">
				<img src="<?php echo $PREFIX;?>/static/img/edit.svg" alt="发帖">
			</a>

			<a href="" class="right" id="toggle-search">
				<img src="<?php echo $PREFIX;?>/static/img/search.svg" alt="搜索">
			</a>

			<form action="/" method="post" id="search" <?php if(strlen($keywords)>0){echo 'style="width: 10.5rem; display: block;"';}?>>
				<input type="hidden" id="pageNo" name="pageNo" value="<?php echo $pageNo;?>">
				<input type="text" id="keywords" name="keywords" value="<?php echo $keywords;?>">
				<img src="<?php echo $PREFIX;?>/static/img/close.svg" alt="关闭" id="close-search">
			</form>
		</div>
		<!--中部-->
		<div class="main">
			<!--内容-->
			<div class="body">
				<!--循环输出部分开始-->
				<?php
				if(count($postData['list'])>0){
				foreach ($postData['list'] as $post) {
					$user = $dao->getUserById($post['user_id']);
					if($user['user_id'] > 0){
						$replyCount = $dao->getFloorCountById($post['postings_id']);
				?>
				<a href="/postings/<?php echo $post['postings_id'];?>" class="postings">
					<div class="up-row">
						<div class="left-col">
							<img src="<?php echo $user['head_img'];?>" alt="用户头像">
						</div>
						<div class="middle-col">
							<div class="author"><?php echo $user['name'];?></div>
							<div class="age"><?php echo $post['time'];?></div>
						</div>
						<div class="right-col">
							<img src="<?php echo $PREFIX;?>/static/img/reply.svg" alt="评论图标">
							<span class="reply-count"><?php echo $post['reply_count'];?></span>
						</div>
					</div>
					<div class="down-row">
						<div class="title">
							<?php echo $post['title'];?>
						</div>
					</div>
				</a>
				<?php }}} else {?>
				<h1 class="no-postings">没有找到贴子</h1>
				<?php } ?>
				<!--循环输出部分结束-->
			</div>
		</div>
		<!--尾部-->
		<?php if(count($postData['list'])>0 && $postData['totalPage'] != 1){ ?>
		<div class="footer">
			<div class="pager">
				<div class="prev">
					<a href="">
						<img src="<?php echo $PREFIX;?>/static/img/green-arrow-left.png">
					</a>
				</div>
				<div class="page">
					<span>
						<?php echo $postData['pageNo']?>/
						<span class="totalPage"><?php echo $postData['totalPage']?></span>
					</span>
				</div>
				<div class="next">
					<a href="">
						<img src="<?php echo $PREFIX;?>/static/img/green-arrow-right.png">
					</a>
				</div>
			</div>
		</div>
		<?php } ?>
	</div>
	<script src="<?php echo $PREFIX;?>/static/js/jquery-1.12.4.min.js"></script>
	<script src="<?php echo $PREFIX;?>/static/js/main.js"></script>
</body>
</html>
<?php
} catch (Exception $e) {
	echo $e->getMessage()."<br>";
}
?>

	

