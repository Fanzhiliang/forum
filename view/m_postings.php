<?php
try {
	include_once('controller/Dao.php');
	$dao = new Dao();
	session_start();
	$user = $_SESSION["user"];
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
	<meta name="viewport" content="width=device-width,initial-scale=1.0,maximum-scale=1.0,user-scalable=no">
	<!-- 初始比例=1 最大缩放=1 禁止缩放=移动端 -->
	<meta http-equiv="X-UA-Compatible" content="IE=edge">
	<!-- 使ie以最高级渲染 -->
	<title><?php echo $thatPostings['title'];?></title>
	<script src="<?php echo $PREFIX;?>/static/js/init.js"></script>
	<link rel="stylesheet" href="<?php echo $PREFIX;?>/static/css/mobile/postings.css">
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

			<?php if(!$currUser || !$keepData || !$isKeep){ ?>
			<a href="" class="right" id="keep">
				<img src="<?php echo $PREFIX;?>/static/img/keep.svg" alt="收藏">
				<input type="hidden" id="user_id" value="<?php echo $currUser['user_id'];?>">
				<input type="hidden" id="postings_id" value="<?php echo $thatPostings['postings_id'];?>">
			</a>
			<?php } else{?>
			<a href="" class="right" id="keep">
				<img src="<?php echo $PREFIX;?>/static/img/kept.svg" alt="取消收藏">
				<input type="hidden" id="keep_id" value="<?php echo $isKeep['keep_id'];?>">
			</a>
			<?php } ?>
			<input type="hidden" id="session_id" value="<?php echo session_id();?>">
			<input type="hidden" id="floor_no" value="<?php echo $floorNo;?>">

			<a href="/publishFloor/<?php echo $thatPostings['postings_id'];?>" class="right">
				<img src="<?php echo $PREFIX;?>/static/img/edit.svg" alt="回帖">
			</a>
		</div>
		<!--中部-->
		<div class="main">
			<!--内容-->
			<div class="body">
				<!--循环输出部分开始-->
				<?php
				foreach ($floorData['list'] as $floor) {
					$floorUser = $dao->getUserById($floor['user_id']);
					if($floorUser['user_id'] > 0){
				?>
				<div class="floor" id="floor-<?php echo $floor['floor_no'];?>">
					<div class="up-row">
						<div class="left-col">
							<span>
								<img src="<?php echo $floorUser['head_img'];?>" alt="">
							</span>
						</div>
						<div class="middle-col">
							<span class="author">
								<?php echo $floorUser['name']; ?>
								<img src="<?php echo $PREFIX;?>/static/img/<?php echo $floorUser['sex']; ?>.svg" alt="<?php echo $floorUser['sex']; ?>">
								<?php if($thatPostings['user_id'] == $floorUser['user_id']){ ?>
								<div class="louzhu">楼主</div>
								<?php } ?>
								<img src="<?php echo $PREFIX;?>/static/img/level-<?php echo $floorUser['level']; ?>.svg" alt="等级" class="level">
							</span>
							<!--1楼不显示楼数-->
							<div class="age">
								<?php if($floor['floor_no']>1){echo '第'.$floor['floor_no'].'楼&nbsp;&nbsp;';} ?>
								<?php echo $floor['time']; ?>
							</div>
						</div>
						<div class="right-col"><!--1楼删除是删除整个帖子-->
							<img src="<?php echo $PREFIX;?>/static/img/more.png" alt="操作" class="more">
							<ul class="ctrl-nav">
								<?php
								if(!!$currUser && $floor['user_id']==$currUser['user_id']){
									if($floor['floor_no']==1){
								?>
								<li><a href="<?php echo $thatPostings['postings_id'];?>" class="delete-postings"><img src="<?php echo $PREFIX;?>/static/img/delete.svg"><span>删除</span></a></li>
								<?php }else{ ?>
								<li><a href="<?php echo $floor['floor_id'];?>" class="delete-floor"><img src="<?php echo $PREFIX;?>/static/img/delete.svg"><span>删除</span></a></li>
								<?php }}?>

								<?php if($floor['floor_no']==1){ ?>
								<li><a href="/publishFloor/<?php echo $thatPostings['postings_id'];?>"><img src="<?php echo $PREFIX;?>/static/img/talk.svg"><span>回复</span></a></li>
								<?php }else{ ?>
								<li><a href="/publishReply/<?php echo $floor['floor_id'];?>"><img src="<?php echo $PREFIX;?>/static/img/talk.svg"><span>回复</span></a></li>
								<?php } ?>
							</ul>
						</div>
					</div>
					<div class="down-row">
						<?php if($floor['floor_no']==1){ ?>
						<div class="title">
							<?php echo $thatPostings['title'];?>
						</div>
						<?php } ?>
						<?php
						echoTags(json_decode($floor['value'],true));
						?>
						<div class="reply-frame">
							<?php
							$replyData = $dao->getReplyByFloorId($floor['floor_id']);
							if(count($replyData['list'])>0 && $floor['floor_no']>1){
							?>
							<div class="reply-head">
								<span class="toggle-reply">查看回复</span>
							</div>
							<!--楼中楼部分-->
							<?php
							foreach ($replyData['list'] as $reply) {
								$replyUser = $dao->getUserById($reply['user_id']);
								if($replyUser['user_id'] > 0){
							?>
							<div class="reply-row">
								<span class="name"><?php echo $replyUser['name'];?> : </span>
								<?php
								echoTags(json_decode($reply['value'],true),false);
								?>
								<span class="time"><?php echo $reply['time'];?></span>
								<?php if($replyUser['user_id'] == $currUser['user_id']){ ?>
								<a href="<?php echo $reply['reply_id']; ?>" class="delete-reply">删除</a>
								<?php } ?>
							</div>
							<?php }} ?>
							<!--最多输10条回复-->
							<div class="reply-foot">
								<a href="/publishReply/<?php echo $floor['floor_id'];?>">
								查看更多回复
								</a>
							</div>
							<?php } ?>	
						</div>		
					</div>
				</div>
				<?php }} ?>
				<!--循环输出部分结束-->
			</div>
		</div>
		<!--尾部-->
		<?php if($floorData['totalPage']>1){ ?>
		<!--     /postings.php?param0=1-->
		<form action="/postings/<?php echo $postingsId;?>" method="post" id="pageForm" style="display: none;">
			<input type="hidden" id="pageNo" name="pageNo" value="<?php echo $pageNo;?>"/>
		</form>
		<div class="footer">
			<div class="pager">
				<div class="prev">
					<a href="">
						<img src="<?php echo $PREFIX;?>/static/img/green-arrow-left.png">
					</a>
				</div>
				<div class="page">
					<span>
						<?php echo $floorData['pageNo'];?>/
						<span class="totalPage"><?php echo $floorData['totalPage'];?></span>
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
	<script src="<?php echo $PREFIX;?>/static/js/Tip.js"></script>
	<script src="<?php echo $PREFIX;?>/static/js/postings.js"></script>
</body>
</html>
<?php
} catch (Exception $e) {
	echo $e->getMessage()."<br>";
}
?>