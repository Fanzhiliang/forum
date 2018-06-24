<?php 
	$message = urldecode(trim($_GET['message']));
?>
<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="UTF-8">
	<title>提示</title>
	<link rel="shortcut icon" type="image/x-icon" href="<?php echo $PREFIX;?>/static/img/logo.ico">
</head>
<style>
	h1{
		text-align: center;
	}
</style>
<body>
	<h1><?php echo $message;?></h1>
</body>
</html>