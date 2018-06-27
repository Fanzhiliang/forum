<?php
class DBpools{
	static $_pools = [];
	static $_config =[];
	const filePaths = [
		"config/db-config.php",
		"../config/db-config.php"
	];
	
	function __construct(){
		error_reporting(E_WARNING);//关闭警告提示
		if(count(self::$_config)<1){
			foreach (self::filePaths as $path) {
				if(file_exists($path)){
					self::$_config = include_once $path;
					break;
				}
			}
		}
		if(count(self::$_config)<1){
			echo "db-config.php配置文件出错或不存在!";
		}
		for($i=0;$i<self::$_config['poolsize'];++$i){
			$conn=mysqli_connect(self::$_config['host'],self::$_config['user'],self::$_config['pwd'],self::$_config['dbName']);
			array_push(self::$_pools, $conn);
		}
	}

	private function getConnection(){
		if(count(self::$_pools)>0){
			$conn = array_pop(self::$_pools);
			return $conn;
		}else{
			throw new ErrorException("连接池中已无资源!");
		}
	}

	private function release($conn){
		if(count(self::$_pools)>=self::$_config['poolsize']){
			throw new ErrorException("连接池中资源已满!");
		}else{
			array_push(self::$_pools, $conn);
		}
	}

	public function query($sql){
		try {
			$conn = $this->getConnection();
			$result = mysqli_query($conn,$sql);
			$this->release($conn);
			return $result;
		} catch (Exception $e) {
			echo 'error:'.$e->getMessage();
			die;
		}
	}

	public function insertGetId($sql){
		try {
			$conn = $this->getConnection();
			$result = mysqli_query($conn,$sql);
			$this->release($conn);
			return mysqli_insert_id($conn);
		} catch (Exception $e) {
			echo 'error:'.$e->getMessage();
			die;
		}
	}

	public function queryCommit($sqlList){
		try {
			$conn = $this->getConnection();
			$conn->autocommit(false); //关闭自动提交功能
			foreach ($sqlList as $sql) {
				mysqli_query($conn,$sql);
			}
			$conn->commit(); //提交事务
			$conn->autocommit(true); //开启自动提交功能
			$this->release($conn);
			$id = mysqli_insert_id($conn);
			if($id>0){
				return $id;
			}
			return true;
		} catch (Exception $e) {
			$conn->rollback();//事务回滚
			echo 'error:'.$e->getMessage();
		}
		return false;
	}
}