<?php
include_once("DBpools.php");
header("Content-Type:text/html;charset=utf-8");
class Dao{
	static $_level_config = [];
	const filePaths = [
		"config/level-config.php",
		"../config/level-config.php"
	];
	private $connect = null;

	function __construct(){
		error_reporting(E_WARNING);//关闭警告提示
		if(count(self::$_level_config)<1){
			foreach (self::filePaths as $path) {
				if(file_exists($path)){
					self::$_level_config = include_once $path;
					break;
				}
			}
		}
		$this->connect = new DBpools();
	}
	#方法
	public function exchangeTime($time){//根据时间戳返回是多久前 
		date_default_timezone_set("PRC");
		$currTime = time();
		$gap = $currTime - $time;

		if($gap < 60*60*24){//一天以内
			if($gap < 60*60){//一小时以内
				$gapTime = round($gap/60);
				return $gapTime>0 ? $gapTime.'分钟前' : '1分钟内';
			}else{
				return round($gap/(60*60)).'小时前';
			}
		}else{
			return date('Y-m-d',$time);
		}
	}

	public function resetModelUser($user){//重置空的或用户不易理解的属性，给默认值
		if(!$user['name']){
			$user['name'] = 'userid_'.$user['user_id'];
		}
		if(!$user['head_img']){
			$user['head_img'] = $PREFIX.'/static/img/default-head.png';
		}
		switch ($user['sex']) {
			case 1:
			case 'male':
				$user['sex'] = 'male';
				break;
			case 2:
			case 'female':
				$user['sex'] = 'female';
				break;
			default:
				$user['sex'] = '';
				break;
		}
		if(count(self::$_level_config)<1){
			echo "level-config.php配置文件出错或不存在!";
		}else{
			$user['max_credits'] = self::$_level_config[$user['level'].''];
			if($user['credits'] >= $user['max_credits']){
				$this->levelUp($user['user_id']);
				$user['level'] = $user['level']+1;
				$user['max_credits'] = self::$_level_config[$user['level'].''];
			}
		}
		date_default_timezone_set("PRC");
		$currTime = time();
		//签到时间
		if($currTime-$user['sign_time']>=24*60*60 && intval($user['is_sign'])===1){
			if($this->resetSignIn($user['user_id'])){
				$user['is_sign']==0;
			}
		}
		return $user;
	}

	public function getPageSize(){//获得pageSize
		return DBpools::$_config['pageSize'];
	}
	#database
	public function getUserById($userId){//根据id获得用户信息
		$sql = "select * from tb_user where user_id=".$userId;
		$result = $this->connect->query($sql);
		if($result->num_rows > 0 && $row = mysqli_fetch_array($result)){
			return $this->resetModelUser($row);
		}
		return false;
	}

	public function getUserByToken($token){//根据token获得用户
		$sql = "select * from tb_user where token = '$token'";
		$result = $this->connect->query($sql);
		if($result->num_rows == 1){
			if($row = mysqli_fetch_array($result)){
				//不需resetModelUser重设属性，因为只需要id,token,token_time
				return $row;
			}
		}
		return false;
	}

	public function getUserByEmail($email){//根据email获得用户
		$sql = "select * from tb_user where email = '$email'";
		$result = $this->connect->query($sql);
		if($result->num_rows == 1){
			if($row = mysqli_fetch_array($result)){
				//不需resetModelUser重设属性，因为只需要id,token,token_time
				return $row;
			}
		}
		return false;
	}

	public function register($name,$password,$email){//注册
		date_default_timezone_set("PRC");
		if($this->isExistName($name) === 1){
			return ['user_id'=>-1];
		}
		if($this->isExistEmail($email) === 1){
			return ['user_id'=>-2];
		}
		$currTime = time();
		$token = md5($email.$password.$currTime);
		$password = md5($password);
		$sql = "insert into tb_user(account,password,name,email,token_time,token) values('$email','$password','$name','$email',$currTime,'$token')";
		// echo var_dump($sql);
		$id = $this->connect->insertGetId($sql);
		if($id>0){
			return $this->getUserById($id);
		}
		return ['user_id'=>0];//注册失败
	}

	public function checkLogin($account,$password){//检查登录
		$sql = "select * from tb_user where account = '$account'";
		$result = $this->connect->query($sql);
		if($result->num_rows > 0 && $row = mysqli_fetch_array($result)) {
			if($row['status'] == 1){
				if(md5($password) == $row['password'] || $password == $row['password']){
					return $this->resetModelUser($row);
				}else{
					return ['user_id'=>-1];//密码错误
				}
			}else{
				return ['user_id'=>-2,'token'=>$row['token']];//未激活 需要token重新验证
			}
		}
		return ['user_id'=>0];//用户不存在
	}

	public function updatePassword($account,$password,$newPassword){//直接修改密码 不会进行md5编码
		$sql = "select * from tb_user where account = '$account'";
		$result = $this->connect->query($sql);
		if($result->num_rows > 0 && $row = mysqli_fetch_array($result)) {
			if(md5($password) == $row['password'] || $password == $row['password']){
				$updateSql = "update tb_user set password = '$newPassword' where account = '$account'";
				$updateRes = $this->connect->query($updateSql);
				if($updateRes){
					$row['password'] = $newPassword;
					return $row;
				}
			}
		}
		return false;
	}

	public function updateUserStatus($userId){//邮件验证成功后根据用户id修改status
		$sql = "update tb_user set status = 1 where user_id = ".$userId;
		return $this->connect->query($sql);
	}

	public function updateUserToken($user){//根据用户给新的token_time和token
		$user = $this->getUserByToken($user['token']);
		date_default_timezone_set("PRC");
		$currTime = time();
		$token = md5($user['email'].$user['password'].$currTime);
		$sql = "update tb_user set token_time=$currTime,token='$token' where user_id=".$user['user_id'];
		if($this->connect->query($sql)){
			$user['token_time'] = $currTime;
			$user['token'] = $token;
			return $user;
		}
		return false;
	}

	public function isExistName($name){//检测昵称是否注册
		$sql = "select * from tb_user where name = '$name'";
		$result = $this->connect->query($sql);
		if($result->num_rows == 1){
			return 1;//存在
		}else if($result->num_rows > 1 ){
			return -1;//竟然有多个？
		}else{
			return 0;//不存在
		}
	}

	public function isExistEmail($email){//检测邮箱是否注册
		$sql = "select * from tb_user where email = '$email'";
		$result = $this->connect->query($sql);
		if($result->num_rows == 1){
			return 1;//存在
		}else if($result->num_rows > 1 ){
			return -1;//竟然有多个？
		}else{
			return 0;//不存在
		}
	}

	public function levelUp($userId){//升级
		$sql = "update tb_user set level = level + 1 where user_id = ".$userId;
		return $this->connect->query($sql);
	}

	public function signIn($userId){//签到并增加积分
		date_default_timezone_set("PRC");
		$oldUser = $this->getUserById($userId);
		if($oldUser['is_sign'] == 0){
			$sqlUpdate = "update tb_user set credits=credits+".self::$_level_config['stepCredits'].",is_sign=1,sign_time=".time()." where user_id=".$userId;
			$result = $this->connect->query($sqlUpdate);
			if($result){
				return $this->getUserById($userId);
			}else{
				return false;//签到失败
			}
		}else{
			return ['user_id'=>0];//已经签到
		}
	}

	public function addCredits($userId){//增加积分
		$sql = "update tb_user set credits=credits+".self::$_level_config['stepCredits']."where user_id=".$userId;
		return $this->connect->query($sql);
	}

	public function resetSignIn($userId){//重置签到字段
		$sql = "update tb_user set is_sign = 0 where user_id=".$userId;
		return $this->connect->query($sql);
	}

	public function getPostingsCount(){//获得贴子表总记录数
		$sql = "select count(*) from tb_postings where is_ban=0";
		$result = $this->connect->query($sql);
		if($result->num_rows > 0){
			return intval(mysqli_fetch_array($result)['count(*)']);
		}else{
			return 0;
		}
	}

	public function getFloorCountById($postingsId){//根据贴子id获得楼层总数
		$sql = "select count(*) from tb_floor where is_ban=0 and postings_id=".$postingsId;
		$result = $this->connect->query($sql);
		if($result->num_rows > 0){
			return intval(mysqli_fetch_array($result)['count(*)']);
		}else{
			return 0;
		}
	}

	public function getReplyCountById($floorId){//根据楼层id获得回复总数
		$sql = "select count(*) from tb_reply where is_ban=0 and floor_id=".$floorId;
		$result = $this->connect->query($sql);
		if($result->num_rows > 0){
			return intval(mysqli_fetch_array($result)['count(*)']);
		}else{
			return 0;
		}
	}

	public function getActivePostings($count){//获得最新回复的帖子
		$sql = "select * from tb_postings where is_ban=0 order by update_time desc limit 0,".$count;
		$result = $this->connect->query($sql);
		$list = [];
		if($result->num_rows > 0){
			while ($row = mysqli_fetch_array($result)) {
				$row['time'] = $this->exchangeTime($row['update_time']);
				$list[] = $row;
			}
		}
		return $list;
	}

	public function getPostingsList($keywords,$pageNo,$hotLength=3){//获得贴子  首页
		$data = ['list' => []];
		$pageSize = DBpools::$_config['pageSize']>0?DBpools::$_config['pageSize']:15;//每页多少条记录
		$totalCount = $this->getPostingsCount();//一共多少条记录
		$totalPage = $totalCount%$pageSize==0?$totalCount/$pageSize:intval($totalCount/$pageSize)+1;//总页数
		if($pageNo<1 || $pageNo>$totalPage){
			return $data;
		}
		$startIndex = ($pageNo-1)*$pageSize;
		$insertSql = "";
		$insertOrder = "";
		$isSearch = is_string($keywords) && strlen(trim($keywords))>0;//是否在搜索

		if($isSearch){
			$insertSql .= " and (title like '%".$keywords."%' ";
			$arr = mbStrSplit($keywords);
			foreach ($arr as $value) {
				$insertSql .= " or title like '%".$value."%' ";
			}
			$insertSql .= ") ";
		}else{
			$insertOrder = " order by postings_id desc ";
		}
		$sql = "select * from tb_postings where is_ban=0 ".$insertSql." ".$insertOrder." limit ".$startIndex.",".$pageSize;
		try {
			$result = $this->connect->query($sql);
			if($result->num_rows > 0){
				while($row = mysqli_fetch_array($result)){
					$row['time'] = $this->exchangeTime($row['update_time']);
					$data['list'][] = $row;
				}
				$data['pageNo'] = $pageNo;//当前页数
				$data['pageSize'] = $pageSize;//每页多少条记录
				//$data['totalCount'] = $totalCount;//一个多少条记录 不需要
				if($pageNo == 1 && !$isSearch){//第一页并且不搜索
					$activeList = $this->getActivePostings($hotLength);
					for ($i=0; $i < count($data['list']); $i++) {
						foreach ($activeList as $active) {
							if($active['postings_id'] == $data['list'][$i]['postings_id']){
								unset($data['list'][$i]);
							}
						}
					}
					if(count($activeList) == $hotLength){
						$data['list'] = array_merge($activeList,$data['list']);
					}
				}
				//搜索和普通分页是总页数是不一样的
				if($isSearch){//搜索
					$totalSql="select count(*) from tb_postings where is_ban=0 ".$insertSql." ".$insertOrder;
					$totalRes = $this->connect->query($totalSql);
					$count = intval(mysqli_fetch_array($totalRes)['count(*)']);
					$data['totalPage'] = $count%$pageSize==0?intval($count/$pageSize):intval($count/$pageSize)+1;//总页数
				}else{//不搜索 普通分页
					$data['totalPage'] = $totalPage;//总页数
				}

				return $data;
			}
		} catch (Exception $e) {
			echo 'error:'.$e->getMessage();
		}
		return $data;
	}

	public function uploadImg($userId,$data){//上传图片地址
		$sqlList = [];
		foreach ($data as $src) {
			$sqlList[] = "insert into tb_img(user_id,src) values(".$userId.",'".$src."')";
		}
		try {
			return $this->connect->queryCommit($sqlList);
		} catch (Exception $e) {
			echo 'error:'.$e->getMessage();
		}
		return false;
	}

	public function insertPostings($userId,$title){//插入到贴子表
		date_default_timezone_set("PRC");
		$currTime = time();
		$sql = "insert into tb_postings(user_id,title,create_time,update_time) values(".$userId.",'".$title."',".$currTime.",".$currTime.")";
		try {
			return $this->connect->insertGetId($sql);
		} catch (Exception $e) {
			echo 'error:'.$e->getMessage();
		}
		return false;
	}

	public function getMaxFloorNo($postingsId){//根据贴子id找到最大的楼层数
		$sql = "select max(floor_no) from tb_floor where postings_id = ".$postingsId;
		$result = $this->connect->query($sql);
		try {
			if($result->num_rows > 0){
				return intval(mysqli_fetch_array($result)['max(floor_no)']);
			}else{
				return 0;
			}
		} catch (Exception $e) {
			return 0;
		}
	}

	public function insertFloor($postingsId,$userId,$value){//插入到楼层表
		date_default_timezone_set("PRC");
		$currTime = time();
		$maxFloorNo = $this->getMaxFloorNo($postingsId)+1;
		$sqlList = [
			"insert into tb_floor(postings_id,user_id,value,create_time,floor_no) values(".$postingsId.",".$userId.",'".$value."',".$currTime.",".$maxFloorNo.")",
			"update tb_postings set update_time=".$currTime.",reply_count=reply_count+1 where postings_id=".$postingsId
		];
		//这里要获得id  不用事务了
		// return $this->connect->queryCommit($sqlList);
		$id = $this->connect->insertGetId($sqlList[0]);
		if($id > 0){//插入成功
			if($this->connect->query($sqlList[1])){//修改成功
				return $id;
			}else{
				$this->deleteFloor($id);
			}
		}
		return false;
	}

	public function getPostingByPostingsId($postingsId){//获得贴子  单个
		$sql = "select * from tb_postings where postings_id = ".$postingsId;
		$result = $this->connect->query($sql);
		if($result->num_rows >0){
			if($row = mysqli_fetch_array($result)){
				$row['time'] = $this->exchangeTime($row['create_time']);
				return $row;
			}
		}
		return false;
	}

	public function getReplyByFloorId($floorId){//获得所有回复  移动端楼层下的回复
		$data = ['list'=>[]];
		$replySize = isset(DBpools::$_config['replySize']) ? DBpools::$_config['replySize'] : 10;
		$sql = "select * from tb_reply where floor_id = ".$floorId." and is_ban=0 order by reply_id desc limit 0,".$replySize;
		$result = $this->connect->query($sql);
		try {
			if($result->num_rows > 0){
				while($row = mysqli_fetch_array($result)){
					$row['time'] = $this->exchangeTime($row['create_time']);
					$data['list'][] = $row;
				}
				return $data;
			}
		} catch (Exception $e) {
			echo 'error:'.$e->getMessage();
		}
		return $data;
	}

	public function getAllReplyByFloorId($floorId){
		$data = ['list'=>[]];
		$sql = "select * from tb_reply where floor_id = ".$floorId." and is_ban=0 order by reply_id desc";
		$result = $this->connect->query($sql);
		if($result->num_rows > 0){
			while($row = mysqli_fetch_array($result)){
				$row['time'] = $this->exchangeTime($row['create_time']);
				$data['list'][] = $row;
			}
		}
		return $data;
	}

	public function getFloorList($postingsId,$pageNo){//根据贴子id获得贴子内的楼层  分页
		$data = ['list'=>[]];
		$pageSize = DBpools::$_config['pageSize'];//每页多少条记录
		$totalCount = $this->getFloorCountById($postingsId);//一共多少条记录
		$totalPage = $totalCount%$pageSize==0?$totalCount/$pageSize:intval($totalCount/$pageSize)+1;//总页数
		if($pageNo<1 || $pageNo>$totalPage){
			return $data;
		}
		$startIndex = ($pageNo-1)*$pageSize;
		$sql = "select * from tb_floor  where is_ban=0 and postings_id=".$postingsId." limit ".$startIndex.",".$pageSize;
		$result = $this->connect->query($sql);
		try {
			if($result->num_rows > 0){
				while($row = mysqli_fetch_array($result)){
					$row['time'] = $this->exchangeTime($row['create_time']);
					$data['list'][] = $row;
				}
				$data['pageNo'] = $pageNo;//当前页数
				$data['pageSize'] = $pageSize;//每页多少条记录
				$data['totalCount'] = $totalCount;//一个多少条记录
				$data['totalPage'] = $totalPage;//总页数
				return $data;
			}
		} catch (Exception $e) {
			echo 'error:'.$e->getMessage();
		}
		return $data;
	}

	public function getReplyList($floorId,$pageNo){//根据楼层id获得楼层的回复  分页
		$data = ['list'=>[]];
		$pageSize = isset(DBpools::$_config['replySize']) ? DBpools::$_config['replySize'] : 10;
		$totalCount = $this->getReplyCountById($floorId);
		$totalPage = $totalCount%$pageSize==0?$totalCount/$pageSize:intval($totalCount/$pageSize)+1;//总页数
		if($pageNo<1 || $pageNo>$totalPage){
			return $data;
		}
		$startIndex = ($pageNo-1)*$pageSize;
		$sql = "select * from tb_reply  where is_ban=0 and floor_id=".$floorId." order by reply_id desc limit ".$startIndex.",".$pageSize;
		$result = $this->connect->query($sql);
		try {
			if($result->num_rows > 0){
				while($row = mysqli_fetch_array($result)){
					$row['time'] = $this->exchangeTime($row['create_time']);
					$data['list'][] = $row;
				}
				$data['pageNo'] = $pageNo;//当前页数
				$data['pageSize'] = $pageSize;//每页多少条记录
				$data['totalCount'] = $totalCount;//一个多少条记录
				$data['totalPage'] = $totalPage;//总页数
				return $data;
			}
		} catch (Exception $e) {
			echo 'error:'.$e->getMessage();
		}
		return $data;
	}

	public function getKeepCountByUserId($userId){
		$sql = "select count(*) from tb_keep where user_id=$userId";
		$result = $this->connect->query($sql);
		if($result->num_rows > 0){
			return intval(mysqli_fetch_array($result)['count(*)']);
		}else{
			return 0;
		}
	}

	public function getKeepByUserId($userId,$pageNo=1){//根据用户id获得分页收藏
		$data = ['list'=>[]];
		$pageSize = DBpools::$_config['homeSize']>0?DBpools::$_config['homeSize']:8;
		$totalCount = $this->getKeepCountByUserId($userId);//一共多少条记录
		$totalPage = $totalCount%$pageSize==0?$totalCount/$pageSize:intval($totalCount/$pageSize)+1;//总页数
		if($pageNo<1 || $pageNo>$totalPage){
			return $data;
		}
		$startIndex = ($pageNo-1)*$pageSize;
		$sql = "select * from tb_keep where user_id = $userId order by keep_id desc limit $startIndex,$pageSize";
		$result = $this->connect->query($sql);
		try {
			if($result->num_rows > 0){
				while($row = mysqli_fetch_array($result)){
					$post = $this->getPostingByPostingsId($row['postings_id']);
					if(!!$post){
						$post['keep_id'] = $row['keep_id'];
						$data['list'][] = $post;
					}
				}
				$data['pageNo'] = $pageNo;//当前页数
				$data['pageSize'] = $pageSize;//每页多少条记录
				$data['totalCount'] = $totalCount;//一个多少条记录
				$data['totalPage'] = $totalPage;//总页数
				return $data;
			}
		} catch (Exception $e) {
			echo 'error:'.$e->getMessage();
		}
		return $data;
	}

	public function isKeepByUserId($userId,$postingsId){//根据用户id获得所有收藏
		$sql="select * from tb_keep where user_id=$userId and postings_id=$postingsId";
		$result = $this->connect->query($sql);
		if($result->num_rows >0){
			if($row = mysqli_fetch_array($result)){
				return $row;
			}
		}
		return 0;
	}

	public function insertKeep($userId,$postingsId){//添加收藏
		$sql = "insert into tb_keep(user_id,postings_id) values(".$userId.",".$postingsId.")";
		try {
			$id = $this->connect->insertGetId($sql);
			return $id>0 ? $id : false;
		} catch (Exception $e) {
			echo 'error:'.$e->getMessage();
		}
		return false;
	}

	public function deleteKeep($keepId){//删除收藏
		$selectSql = "select * from tb_keep where keep_id = ".$keepId;
		$deleteSql = "delete from tb_keep where keep_id = ".$keepId;
		try {
			$selectRes = $this->connect->query($selectSql);
			if($selectRes && $row = mysqli_fetch_array($selectRes)){
				$deleteRes = $this->connect->query($deleteSql);
				if($deleteRes){
					return [
						'user_id' => $row['user_id'],
						'postings_id' => $row['postings_id']
					];
				}
			}
			return false;
		} catch (Exception $e) {
			echo 'error:'.$e->getMessage();
		}
		return false;
	}

	public function getFloorById($floorId){//根据楼层id获得楼层信息  单个
		$sql = "select * from tb_floor where is_ban = 0 and floor_id = ".$floorId;
		$result = $this->connect->query($sql);
		try {
			if($result->num_rows > 0 && $row = mysqli_fetch_array($result)){
				return $row;
			}
			return false;
		} catch (Exception $e) {
			echo 'error:'.$e->getMessage();
		}
		return false;
	}

	public function getReplyById($replyId){//根据回复id获得回复信息  单个
		$sql = "select * from tb_reply where is_ban = 0 and reply_id = ".$replyId;
		$result = $this->connect->query($sql);
		try {
			if($result->num_rows > 0 && $row = mysqli_fetch_array($result)){
				return $row;
			}
			return false;
		} catch (Exception $e) {
			echo 'error:'.$e->getMessage();
		}
		return false;
	}

	public function insertReply($userId,$floorId,$value){//插入回复表
		date_default_timezone_set("PRC");
		$currTime = time();
		$floor = $this->getFloorById($floorId);
		$sqlList=[
			"insert into tb_reply(user_id,floor_id,postings_id,value,create_time) values(".$userId.",".$floorId.",".$floor['postings_id'].",'".$value."',".$currTime.")",
			"update tb_postings set update_time=".$currTime.",reply_count=reply_count+1 where postings_id=".$floor['postings_id']
		];
		try {
			return $this->connect->queryCommit($sqlList);
		} catch (Exception $e) {
			echo 'error:'.$e->getMessage();
		}
		return false;
	}

	public function getFloorReplyByUserId($userId,$pageNo=1){//根据用户id获得用户发的楼层和回复
		$data = ['list'=>[]];
		$floorSql="select * from tb_floor where is_ban=0 and user_id=".$userId." order by floor_id desc";
		$floorRes = $this->connect->query($floorSql);
		if($floorRes->num_rows > 0){
			while($row = mysqli_fetch_array($floorRes)){
				$row['time'] = $this->exchangeTime($row['create_time']);
				$row['type'] = 'floor';//区分类型 方便输出
				$data['list'][] = $row;
			}
		}
		$replySql="select * from tb_reply where is_ban=0 and user_id=".$userId." order by reply_id desc";
		$replyRes = $this->connect->query($replySql);
		if($replyRes->num_rows > 0){
			while($row = mysqli_fetch_array($replyRes)){
				$row['time'] = $this->exchangeTime($row['create_time']);
				$row['type'] = 'reply';//区分类型 方便输出
				$data['list'][] = $row;
			}
		}
		//排序
		for ($i=0; $i < count($data['list']); $i++) { 
			for ($j=$i+1; $j < count($data['list']); $j++) {
				if($data['list'][$i]['create_time']<$data['list'][$j]['create_time']){
					$temp = $data['list'][$i];
					$data['list'][$i] = $data['list'][$j];
					$data['list'][$j] = $temp;
				}
			}
		}

		$pageSize = DBpools::$_config['homeSize']>0?DBpools::$_config['homeSize']:8;
		$totalCount = count($data['list']);//一共多少条记录
		$totalPage = $totalCount%$pageSize==0?$totalCount/$pageSize:intval($totalCount/$pageSize)+1;//总页数
		if($pageNo<1 || $pageNo>$totalPage){
			return ['list'=>[]];
		}
		$startIndex = ($pageNo-1)*$pageSize;
		$data['list'] = array_slice($data['list'], $startIndex ,$pageSize);//分隔数组
		$data['pageNo'] = $pageNo;//当前页数
		$data['pageSize'] = $pageSize;//每页多少条记录
		$data['totalCount'] = $totalCount;//一个多少条记录
		$data['totalPage'] = $totalPage;//总页数
		return $data;
	}

	public function getReplyByUserId($userId){//根据用户id获得用户发的回复
		$data = ['list'=>[]];
		$sql = "select * from tb_reply where is_ban = 0 and  user_id = ".$userId." order by reply_id desc";
		$result = $this->connect->query($sql);
		try {
			if($result->num_rows > 0){
				while($row = mysqli_fetch_array($result)){
					$row['time'] = $this->exchangeTime($row['create_time']);
					$data['list'][] = $row;
				}
				return $data;
			}
		} catch (Exception $e) {
			echo 'error:'.$e->getMessage();
		}
		return $data;
	}

	public function getFloorByUserId($userId){//根据用户id获得用户发的楼层
		$data = ['list'=>[]];
		$sql = "select * from tb_floor where is_ban = 0 and floor_no!=1 and  user_id = ".$userId." order by floor_id desc";
		$result = $this->connect->query($sql);
		try {
			if($result->num_rows > 0){
				while($row = mysqli_fetch_array($result)){
					$row['time'] = $this->exchangeTime($row['create_time']);
					$data['list'][] = $row;
				}
				return $data;
			}
		} catch (Exception $e) {
			echo 'error:'.$e->getMessage();
		}
		return $data;
	}

	public function getPostCountByUserId($userId){//根据用户id获得楼层总数
		$sql = "select count(*) from tb_postings where is_ban=0 and user_id=$userId";
		$result = $this->connect->query($sql);
		if($result->num_rows > 0){
			return intval(mysqli_fetch_array($result)['count(*)']);
		}else{
			return 0;
		}
	}

	public function getPostingsByUserId($userId,$pageNo=1){//根据用户id获得用户发的贴子
		$data = ['list'=>[]];
		$pageSize = DBpools::$_config['homeSize']>0?DBpools::$_config['homeSize']:8;
		$totalCount = $this->getPostCountByUserId($userId);//一共多少条记录
		$totalPage = $totalCount%$pageSize==0?$totalCount/$pageSize:intval($totalCount/$pageSize)+1;//总页数
		if($pageNo<1 || $pageNo>$totalPage){
			return $data;
		}
		$startIndex = ($pageNo-1)*$pageSize;
		$sql="select * from tb_postings where is_ban=0 and user_id=".$userId." order by postings_id desc limit $startIndex,$pageSize";
		$result = $this->connect->query($sql);
		try {
			if($result->num_rows > 0){
				while($row = mysqli_fetch_array($result)){
					$row['time'] = $this->exchangeTime($row['create_time']);
					$data['list'][] = $row;
				}
				$data['pageNo'] = $pageNo;//当前页数
				$data['pageSize'] = $pageSize;//每页多少条记录
				$data['totalCount'] = $totalCount;//一个多少条记录
				$data['totalPage'] = $totalPage;//总页数
				return $data;
			}
		} catch (Exception $e) {
			echo 'error:'.$e->getMessage();
		}
		return $data;
	}

	public function deleteReply($replyId){//删除回复 不是真正删除 是把is_ban改为1
		$reply = $this->getReplyById($replyId);
		$sqlList = [
			"update tb_reply set is_ban = 1 where reply_id = ".$replyId,
			"update tb_postings set reply_count=reply_count-1 where postings_id=".$reply['postings_id']
		];
		try {
			return $this->connect->queryCommit($sqlList);
		} catch (Exception $e) {
			echo 'error:'.$e->getMessage();
		}
		return false;
	}

	public function deleteFloor($floorId){//删除楼层 不是真正删除 是把is_ban改为1
		$floor = $this->getFloorById($floorId);
		$sqlList = [
			"update tb_floor set is_ban = 1 where floor_id = ".$floorId,
			"update tb_postings set reply_count=reply_count-1 where postings_id=".$floor['postings_id']
		];
		try {
			return $this->connect->queryCommit($sqlList);
		} catch (Exception $e) {
			echo 'error:'.$e->getMessage();
		}
		return false;
	}

	public function deletePostings($postingsId){//删除贴子 不是真正删除 是把is_ban改为1 
		$sql = "update tb_postings set is_ban = 1 where postings_id = ".$postingsId;
		return $this->connect->query($sql);
	}

	public function getAllFLoorByPostingsId($PostingsId){
		$sql ="select * from tb_floor where is_ban=0 and postings_id=".$PostingsId;
		$result = $this->connect->query($sql);
		$list = [];
		try {
			if($result->num_rows > 0){
				while ($row = mysqli_fetch_array($result)) {
					$list[] = $row;
				}
			}
		} catch (Exception $e) {
			echo 'error:'.$e->getMessage();
		}
		return $list;
	}

	public function updateUser($userId,$name,$sex,$imageSrc){
		$sql="update tb_user set name = '".$name."',sex = ".$sex.",head_img = '".$imageSrc."' where user_id = ".$userId;
		return $this->connect->query($sql);
	}

	public function getFloorOne($postingsId){//根据贴子id获得一楼
		$sql = "select * from tb_floor where floor_no=1 and postings_id=".$postingsId;
		$result = $this->connect->query($sql);
		if($result->num_rows > 0){
			if($row = mysqli_fetch_array($result)){
				return $row;
			}
		}else{
			return false;
		}
	}

}