<?php 
	$message = urldecode(trim($_GET['message']));
?>
<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="UTF-8">
	<title>提示</title>
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