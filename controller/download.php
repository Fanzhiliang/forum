<?php

$fileName = $_GET['filename']; //得到文件名
header( "Content-Disposition:  attachment;  filename=".$fileName); //告诉浏览器通过附件形式来处理文件
header('Content-Length: ' . filesize('../resources/'.$fileName)); //下载文件大小
readfile('../resources/'.$fileName);  //读取文件内容

?>