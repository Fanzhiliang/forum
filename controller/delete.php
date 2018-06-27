<?php
	$send = [
		'code' => 500,
		'data' => [
			'message' => '删除失败'
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

	//删除前都要判断要删除的记录是否属于该sessionId的用户
	if(isset($get['reply_id'])){
		$replyData = $dao->getReplyByUserId($user['user_id']);
		foreach ($replyData['list'] as $reply) {
			if($reply['user_id'] == $user['user_id']){
				$result = $dao->deleteReply($get['reply_id']);
				if($result){
					$send['code'] = 200;
					$send['data']['message'] = 'success';
				}
				echo json_encode($send);
				die;
			}
		}
		$send['data']['message'] = '该回复不属于你';
		echo json_encode($send);
	}else if(isset($get['floor_id'])){
		$floorData = $dao->getFloorByUserId($user['user_id']);
		foreach ($floorData['list'] as $floor) {
			if($floor['user_id'] == $user['user_id']){
				$result = $dao->deleteFloor($get['floor_id']);
				if($result){
					$send['code'] = 200;
					$send['data']['message'] = 'success';
				}
				echo json_encode($send);
				die;
			}
		}
		$send['data']['message'] = '该楼层不属于你';
		echo json_encode($send);
	}else if(isset($get['postings_id'])){
		$postData = $dao->getPostingsByUserId($user['user_id']);
		foreach ($postData['list'] as $post) {
			if($post['user_id'] == $user['user_id']){
				$result = $dao->deletePostings($get['postings_id']);
				if($result){
					$send['code'] = 200;
					$send['data']['message'] = 'success';
				}
				echo json_encode($send);
				die;
			}
		}
		$send['data']['message'] = '该贴子不属于你';
		echo json_encode($send);
	}else{
		$send['data']['message'] = '数据不完整';
		echo json_encode($send);
	}