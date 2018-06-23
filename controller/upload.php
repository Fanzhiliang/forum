<?php
	//http类型
	$HTTP_TYPE=((isset($_SERVER['HTTPS'])&&$_SERVER['HTTPS'] == 'on')||(isset($_SERVER['HTTP_X_FORWARDED_PROTO'])&&$_SERVER['HTTP_X_FORWARDED_PROTO']=='https'))?'https://':'http://';
	//地址前缀
	$PREFIX = $HTTP_TYPE.$_SERVER['SERVER_NAME'];
	include_once('Dao.php');
	$dao = new Dao();
	$sessionId = $_GET['session_id'];
	session_id($sessionId);
	session_start();
	$user = $_SESSION["user"];
	$data = [];
	if(count($_FILES)>0 && $user['user_id']>0){
		foreach($_FILES as $fileinfo){
			if($fileinfo['size']<1000000 && $fileinfo['size']>0){
				date_default_timezone_set("PRC");
				$fileType = substr($fileinfo['name'], strripos($fileinfo['name'], '.'));
				$newFileName = time().'_'.$user['user_id'].$fileType;//已时间戳_id保存
				$src = $PREFIX."/resources/".$newFileName;
				move_uploaded_file($fileinfo['tmp_name'], '../resources/'.$newFileName);
				$data[] = $src;
			}
		}
	}
	$dao->uploadImg($user['user_id'],$data);//上传图片
	$result = [
		"errno"=>0,
		"data"=>$data
	];
	if(count($data)<1){
		$result['errno'] = 500;
	}
	echo json_encode($result);
?>