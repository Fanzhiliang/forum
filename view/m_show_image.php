<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="UTF-8">
	<meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no">
	<!-- 初始比例=1 最大缩放=1 禁止缩放=移动端 -->
	<meta http-equiv="X-UA-Compatible" content="IE=edge">
	<!-- 使ie以最高级渲染 -->
	<title>展示图片</title>
	<script src="<?php echo $PREFIX;?>/static/js/init.js"></script>
	<link rel="stylesheet" href="<?php echo $PREFIX;?>/static/css/mobile/showimage.css">
</head>
<body>
	<div class="wrap">
		<!--中部-->
		<img class="back" src="<?php echo $PREFIX;?>/static/img/white-arrow-left.svg" alt="">
		<a href="<?php echo $PREFIX;?>/controller/download.php?filename=<?php echo $_GET['filename'];?>" class="save">保存</a>
		<div class="main">
			<img class="main-img" src="<?php echo $PREFIX;?>/resources/<?php echo $_GET['filename'];?>" alt="">
		</div>
	</div>
	<script src="<?php echo $PREFIX;?>/static/js/jquery-1.12.4.min.js"></script>
	<script src="<?php echo $PREFIX;?>/static/js/showimage.js"></script>
</body>
</html>