<?php
	$send = ['code' => 500,'data' => ['message' => '']];
	$get = $_POST['body'];
	session_id($get['session_id']);
	session_start();
	$user = $_SESSION['user'];
	if(!$user){
		$send['data']['message'] = '还没登录,请登录!';
		$send['data']['href'] = '/login';
		echo json_encode($send);
		die;
	}

	include_once('Dao.php');
	$dao = new Dao();
	//http类型
    $HTTP_TYPE = ((isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] == 'on') || (isset($_SERVER['HTTP_X_FORWARDED_PROTO']) && $_SERVER['HTTP_X_FORWARDED_PROTO'] == 'https')) ? 'https://' : 'http://';
    //地址前缀
    $PREFIX = $HTTP_TYPE.$_SERVER['SERVER_NAME'];
	switch ($get['type']) {
		case 'myPostings':
			$postData = $dao->getPostingsByUserId($user['user_id'],$get['pageNo']);
			if(count($postData['list']) > 0){
				$send['code'] = 200;
				$send['data'] = $postData;
				$send['data']['prefix'] = $PREFIX;
			}else{
				$send['data']['message'] = '没有更多内容!';
			}
			break;
		case 'myReply':
			include_once('strToTags.php');
			$replyData = $dao->getFloorReplyByUserId($user['user_id'],$get['pageNo']);
			if(count($replyData['list']) > 0){
				$send['code'] = 200;
				$send['data'] = $replyData;
				$send['data']['list'] = [];//清空list
				foreach ($replyData['list'] as $obj) {
					$obj['value'] = strToTags(json_decode($obj['value'],true),false,false);
					if($obj['type'] == 'floor'){
						$obj['post'] = $dao->getPostingByPostingsId($obj['postings_id']);
					}else if($obj['type'] == 'reply'){
						$obj['floor'] = $dao->getFloorById($obj['floor_id']);
						$obj['floor']['value'] = strToTags(json_decode($obj['floor']['value'],true),false,false);
					}
					$send['data']['list'][] = $obj;
				}
				$send['data']['prefix'] = $PREFIX;
			}else{
				$send['data']['message'] = '没有更多内容!';
			}
			break;
		case 'myKeep':
			$keepData = $dao->getKeepByUserId($user['user_id'],$get['pageNo']);
			if(count($keepData['list']) > 0){
				$send['code'] = 200;
				$send['data'] = $keepData;
				$send['data']['prefix'] = $PREFIX;
			}else{
				$send['data']['message'] = '没有更多内容!';
			}
			break;
		default:
			$send['data']['message'] = '数据不完整!';
			break;
	}

	echo json_encode($send);
?>