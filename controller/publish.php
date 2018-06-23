<?php
	$send = [
		'code' => 500,
		'data' => [
			'message' => '回复失败'
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
	if(isset($get['title'])){//发表帖子
		$send['data']['message'] = '发表失败';
		$postingsId = $dao->insertPostings($user['user_id'],$get['title']);
		if($postingsId > 0){//添加到贴子表成功
			$result = $dao->insertFloor($postingsId,$user['user_id'],json_encode($get['value'],JSON_UNESCAPED_UNICODE));
			if($result){
				$send['code'] = 200;
				$send['data']['message'] = 'success';
				$send['data']['href'] = '/';
			}
			echo json_encode($send);
		}else{
			echo json_encode($send);
		}
	}else if(!isset($get['title']) && isset($get['postings_id'])){//回复帖子
		$result = $dao->insertFloor($get['postings_id'],$user['user_id'],json_encode($get['value'],JSON_UNESCAPED_UNICODE));
		if($result){
			$send['code'] = 200;
			$send['data']['message'] = 'success';
			// $send['data']['href'] = '/postings/'.$get['postings_id'];
			$thatFloor = $dao->getFloorById($result);
			$list = $dao->getAllFLoorByPostingsId($get['postings_id']);
			$pageNo = 1;
			for($i = 0;$i<count($list);++$i){
				if($list[$i]['floor_no'] == $thatFloor['floor_no']){
					$pageSize = $dao->getPageSize();
					++$i;
					$pageNo = $i%$pageSize==0 ? intval($i/$pageSize) : intval($i/$pageSize)+1;
					break;
				}
			}
			$send['data']['action'] = '/postings/'.$thatFloor['postings_id'];
			$send['data']['params'] = [
				'floor_no'=>$thatFloor['floor_no'],
				'pageNo'=>$pageNo
			];
		}
		echo json_encode($send);
	}else if(!isset($get['title']) && isset($get['floor_id'])){//回复楼层
		$result = $dao->insertReply($user['user_id'],$get['floor_id'],json_encode($get['value'],JSON_UNESCAPED_UNICODE));
		if($result){
			$send['code'] = 200;
			$send['data']['message'] = 'success';
			$thatFloor = $dao->getFloorById($get['floor_id']);
			$list = $dao->getAllFLoorByPostingsId($thatFloor['postings_id']);
			$pageNo = 1;
			for($i = 0;$i<count($list);++$i){
				if($list[$i]['floor_no'] == $thatFloor['floor_no']){
					$pageSize = $dao->getPageSize();
					++$i;
					$pageNo = $i%$pageSize==0 ? intval($i/$pageSize) : intval($i/$pageSize)+1;
					break;
				}
			}
			$send['data']['action'] = '/postings/'.$thatFloor['postings_id'];
			$send['data']['params'] = [
				'floor_no'=>$thatFloor['floor_no'],
				'pageNo'=>$pageNo
			];
		}
		echo json_encode($send);
	}else{
		$send['data']['message'] = '数据不完整';
		echo json_encode($send);
	}
