<?php
	//http类型
	$HTTP_TYPE=((isset($_SERVER['HTTPS'])&&$_SERVER['HTTPS'] == 'on')||(isset($_SERVER['HTTP_X_FORWARDED_PROTO'])&&$_SERVER['HTTP_X_FORWARDED_PROTO']=='https'))?'https://':'http://';
	//地址前缀
	$PREFIX = $HTTP_TYPE.$_SERVER['SERVER_NAME'];
	$send = [
		'code' => 500,
		'data' => ['message' => '修改失败!']//可以添加href属性，返回后跳转的地址
	];
	function notLogin(){
		$send['data']['message'] = '还没登录,请登录!';
		$send['data']['href'] = '/login';
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
	}else{
		include_once('Dao.php');
		$dao = new Dao();

		$imageSrc = str_replace($PREFIX, '..', $get['imageSrc']);
		$name = $get['name'];
		$sex = $get['sex'];
		switch ($sex) {
			case 1:
			case 'male':
				$sex = 1;
				break;
			case 2:
			case 'female':
				$sex = 2;
				break;
			default:
				$sex = 0;
				break;
		}
		$nameLen = strlen(trim($name));
		if($nameLen>0 && ($nameLen<3 || $nameLen>20)){
			$send['data']['message'] = '呢称长度需大于2,小于21!';
		}else{
			if($nameLen==0){
				$name = '';
			}
			//如果图片存在
			if(file_exists($imageSrc)){
				$startX = floatval($get['startX']);
				$startY = floatval($get['startY']);
				$endX = floatval($get['endX']);
				$endY = floatval($get['endY']);
				if($startX<$endX&&$startY<$endY){
					//获得图片
					$src = imagecreatefromstring(file_get_contents($imageSrc));
					// 获取原图尺寸
					list($src_w,$src_h)=getimagesize($imageSrc);
					//裁剪开区域左上角的点的坐标
					$poslX = $src_w * $startX;
					$poslY = $src_h * $startY;
					//裁剪开区域右下角的点的坐标
					$posrX = $src_w * $endX;
					$posrY = $src_h * $endY;
					//裁剪区域的宽和高
					$width = $posrX - $poslX;
					$height = $posrY - $poslY;
					//最终保存成图片的宽和高，和源要等比例，否则会变形
					$final_width = 300;
					$final_height = round($final_width * $height / $width);
					//将裁剪区域复制到新图片上，并根据源和目标的宽高进行缩放或者拉升
					$new_image = imagecreatetruecolor($final_width, $final_height);
					imagecopyresampled($new_image, $src, 0, 0, $poslX, $poslY, $final_width, $final_height, $width, $height);
					//保存
					date_default_timezone_set("PRC");
					$newPath = '../resources/'.time().'.jpg';
					imagejpeg($new_image,$newPath);
					imagedestroy($src);
					imagedestroy($new_image);
					//获得图片网络路径
					$imageSrc = str_replace('..', $PREFIX, $newPath);
					//上传图片路径和用户id到图片表
					$dao->uploadImg($user['user_id'],[$imageSrc]);

				}
			}else{
				$imageSrc = $user['head_img'];
			}
			if($dao->updateUser($user['user_id'],$name,$sex,$imageSrc)){
				$send['code'] = 200;
				$send['data']['message'] = 'success';
			}
		}	
	}
	
	echo json_encode($send);