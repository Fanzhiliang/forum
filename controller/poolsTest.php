<?php 
	include("DBpools.php");
	header("Content-Type:text/html;charset=utf-8");
	$name= "xl999";
	$password= "777777";
	$sex= "男";
	$email= "35456464@qq.com";
	$info= "3213213211";
	$pdo= null;
	try {
		// $sql="insert into user(name,password,sex,email,info) values('".$name."','".$password."','".$sex."','".$email."','".$info."')";//添加
		// $sql="delete from user where name='".$name."'";//删除
		$sql = "select * from user";//查询
		$connect = new DBpools();
		$result = $connect->query($sql);
		while ($row = mysqli_fetch_object($result)) {
			echo $row->name."<br>";
		}
	} catch (Exception $e) {
		echo $e->getMessage()."<br>";
	}