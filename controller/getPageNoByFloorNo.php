<?php
	include_once 'Dao.php';
	$dao = new Dao();
	$get = $_POST['body'];
	$postingsId = $get['postings_id'];
	$floorNo = $get['floor_no'];
	$list = $dao->getAllFLoorByPostingsId($postingsId);
	$send = [
		'code' => 500,
		'data' => [
			'message' => '参数错误'
		]
	];

	for($i = 0;$i<count($list);++$i){
		if($list[$i]['floor_no'] == $floorNo){
			$pageSize = $dao->getPageSize();
			$send['code'] = 200;
			$send['data']['message'] = 'success';
			++$i;
			$send['data']['pageNo'] = $i%$pageSize==0 ? intval($i/$pageSize) : intval($i/$pageSize)+1;
			break;
		}
	}

	echo json_encode($send);

	