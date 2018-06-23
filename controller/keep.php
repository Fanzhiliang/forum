<?php
	$send = [
		'code' => 500,
		'data' => [
			'message' => '收藏失败'
		]//可以添加href属性，返回后跳转的地址
	];
	function notLogin(){
		$send['data']['message'] = '还没登录,请登录!';
		$send['data']['href'] = '/login';
		echo json_encode($send);
		die;
	}
	$get = $_POST['body'];
	if(!isset($get['session_id'])){
		notLogin();
	}
	session_id($get['session_id']);
	session_start();
	$user = $_SESSION['user'];
	if(!$user){
		notLogin();
	}

	include_once 'Dao.php';
	$dao = new Dao();

	if(isset($get['user_id']) && isset($get['postings_id'])){//收藏
		//判断是否已经收藏
		$keepData = $dao->getKeepByUserId($get['user_id']);
		foreach ($keepData['list'] as $keep) {
			if($keep['postings_id'] == $get['postings_id']){
				$send['data']['message'] = '已收藏,请刷新';
				echo json_encode($send);
				die;
			}
		}
		//未收藏
		$keepId = $dao->insertKeep($get['user_id'],$get['postings_id']);
		if($keepId){
			$send['code'] = 200;
			$send['data']['toggle'] = 'keep=>kept';
			$send['data']['keep_id'] = $keepId;
			$send['data']['message'] = '收藏成功';
		}
		echo json_encode($send);
	}else if(isset($get['keep_id'])){//取消收藏
		$deleteRes = $dao->deleteKeep($get['keep_id']);
		if(isset($deleteRes['user_id']) && isset($deleteRes['postings_id'])){
			$send['code'] = 200;
			$send['data']['toggle'] = 'kept=>keep';
			$send['data']['user_id'] = $deleteRes['user_id'];
			$send['data']['postings_id'] = $deleteRes['postings_id'];
			$send['data']['message'] = '取消成功';
		}
		echo json_encode($send);
	}else{
		$send['data']['message'] = '数据不完整';
		echo json_encode($send);
	}


		