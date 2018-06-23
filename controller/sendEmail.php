<?php
date_default_timezone_set("PRC");
header("Content-Type:text/html;charset=utf-8");
function sendEmail($smtpemailto,$mailtitle,$mailcontent){//发给谁  标题  内容
	include_once('SMTP.class.php');
	$smtp_config = include_once('SMTP-config.php');
	$smtpserver = $smtp_config['smtpserver'];//SMTP服务器
	$smtpserverport = $smtp_config['smtpserverport'];//SMTP服务器端口
	$smtpusermail = $smtp_config['smtpusermail'];//SMTP服务器的用户邮箱
	$smtpuser = $smtp_config['smtpuser'];//SMTP服务器的用户帐号，注：部分邮箱只需@前面的用户名
	$smtppass = $smtp_config['smtppass'];//SMTP服务器的用户密码
	$mailtype = "HTML";//邮件格式（HTML/TXT）,TXT为文本邮件
	//************************ 配置信息 ****************************
	$smtp = new SMTP($smtpserver,$smtpserverport,true,$smtpuser,$smtppass);
	$smtp->debug = false;//是否显示发送的调试信息
	return $smtp->sendmail($smtpemailto, $smtpusermail, $mailtitle, $mailcontent, $mailtype);
}
?>