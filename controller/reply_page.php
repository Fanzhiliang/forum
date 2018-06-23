<?php
	$get = $_POST['body'];
	$send = ['code' => 500,'data' => ['message' => '']];

	if(isset($get['pageNo']) && isset($get['floor_id'])){
		include_once('Dao.php');
		$dao = new Dao();
		$floorId = intval($get['floor_id']);
		$pageNo = intval($get['pageNo']);
		$totalCount = $dao->getReplyCountById($floorId);
		if($pageNo>0 && $totalCount>0 && $pageNo<=$totalCount){
			$replyData = $dao->getReplyList($floorId,$pageNo);
			include_once('strToTags.php');
			if(count($replyData['list']) > 0){
				$list = [];
				foreach ($replyData['list'] as $reply) {
					$replyUser = $dao->getUserById($reply['user_id']);
					$list[] = [
						'head' => $replyUser['head_img'],
						'author' => $replyUser['name'],
						'time' => $reply['time'],
						'value' => strToTags(json_decode($reply['value'],true),false)
					];
				}
				$send['code'] = 200;
				$send['data']['list'] = $list;
				$send['data']['pageNo'] = $replyData['pageNo'];
				$send['data']['totalPage'] = $replyData['totalPage'];
			}else{
				$send['data']['message'] = '无更多回复';
			}
		}else{
			$send['data']['message'] = '数据错误';
		}
	}else{
		$send['data']['message'] = '数据不完整';
	}

	echo json_encode($send);
