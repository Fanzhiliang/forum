<?php
#变量
    include_once('controller/isMobile.php');
    //是否是移动端
    $IS_MOBILE = isMobile();
    //http类型
    $HTTP_TYPE = ((isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] == 'on') || (isset($_SERVER['HTTP_X_FORWARDED_PROTO']) && $_SERVER['HTTP_X_FORWARDED_PROTO'] == 'https')) ? 'https://' : 'http://';
    //地址前缀
    $PREFIX = $HTTP_TYPE.$_SERVER['SERVER_NAME'];
#方法
    //根据关联数组输出属性
    function echoAttrs($attrs){
        foreach ($attrs as $attr) {
            //移动端图片只要这个src属性
            if($attr['name'] == 'src'){
                echo ' '.$attr['name'].'="'.$attr['value'].'" ';
            }
            if($IS_MOBILE){

            }else{//pc端
                //需要width  除了首页
                if($attr['name'] == 'style'){
                    echo ' '.$attr['name'].'="'.$attr['value'].'" ';
                }
            }
        }
    }
    //根据关联数组输出节点
    function echoTags($values,$isEchoBr = true,$isEchoImg = true){
        if(is_array($values)){
            foreach ($values as $value) {
                if(!isset($value['tag'])){
                    if($value != '<br/>' && $value != '<br/><br/>'){
                        echo $value;
                    }
                }else{
                    $parentTag = $value['tag'];
                    if($parentTag == 'div'){
                        continue;
                    }else if($parentTag == 'br'){
                        if($isEchoBr == true){
                            echo '<br>';
                        }
                    }else if($parentTag == 'img' && $isEchoImg == false){
                        echo '&nbsp;[图片]&nbsp;';
                    }else{
                        echo '<'.$parentTag;
                        if(isset($value['attrs'])){
                            echoAttrs($value['attrs']);
                        }
                        echo '>';
                        echoTags($value['children'],$isEchoBr,$isEchoImg);
                        echo '</'.$parentTag.'>';
                    }
                }
            }
        }
    }
    //分隔汉字
    function mbStrSplit ($string, $len=1){
      $start = 0;
      $strlen = mb_strlen($string);
      while ($strlen) {
        $array[] = mb_substr($string,$start,$len,"utf8");
        $string = mb_substr($string, $len, $strlen,"utf8");
        $strlen = mb_strlen($string);
      }
      return $array;
    }
    //post 跳转
    include_once('controller/sendPost.php');
    
//设置路由参数
$request_uri = explode('?', $_SERVER['REQUEST_URI'], 2);
$uri = substr($request_uri[0],1);
$controller = '';
$param = '';
if(strpos($uri, '/') > -1){
    $controller = '/'.substr($uri,0,strpos($uri, '/'));
    $params = explode('/',substr($uri,strpos($uri, '/')+1));
    for ($i=0; $i < count($params) ; $i++) {
        $_GET['param'.$i] = $params[$i];
    }
}else{
    $controller = '/'.$uri;
}

switch ($controller) {
    case '/test':
        require 'view/test.php';
        die;
        break;
    case '/tip':
        if($IS_MOBILE){
            require 'view/m_tip.php';
        }else{
            require 'view/pc_tip.php';
        }
        die;
        break;
    case '':
    case '/':
    case '/main':
        if($IS_MOBILE){
            require 'view/m_main.php';
        }else{
            require 'view/pc_main.php';
        }
        die;
        break;
    case '/login':
        if($IS_MOBILE){
            require 'view/m_login.php';
        }else{
            require 'view/pc_login.php';
        }
        die;
        break;
    case '/register':
        if($IS_MOBILE){
            require 'view/m_register.php';
        }else{
            require 'view/pc_register.php';
        }
        die;
        break;
    case '/forget':
        if($IS_MOBILE){
            require 'view/m_forget.php';
        }else{
            require 'view/pc_forget.php';
        }
        die;
        break;
    case '/publishPost':
        if($IS_MOBILE){
            require 'view/m_publish_post.php';
        }else{
            header('location:/tip?message=!'.urlencode('404 Not Found'));
        }
        die;
        break;
    case '/publishFloor':
        if($IS_MOBILE){
            require 'view/m_publish_floor.php';
        }else{
            header('location:/tip?message=!'.urlencode('404 Not Found'));
        }
        die;
        break;
    case '/home':
        if($IS_MOBILE){
            require 'view/m_home.php';
        }else{
            require 'view/pc_home.php';
        }
        die;
        break;
    case '/postings':
        if($IS_MOBILE){
            require 'view/m_postings.php';
        }else{
            require 'view/pc_postings.php';
        }
        die;
        break;
    case '/publishReply':
        if($IS_MOBILE){
            require 'view/m_publish_reply.php';
        }else{
            header('location:/tip?message=!'.urlencode('404 Not Found'));
        }
        die;
        break;
    case '/showImage':
        if($IS_MOBILE){
            require 'view/m_show_image.php';
        }else{
            header('location:/tip?message=!'.urlencode('404 Not Found'));
        }
        die;
        break;
    case '/editUser':
        if($IS_MOBILE){
            require 'view/m_edit_user.php';
        }else{
            header('location:/tip?message=!'.urlencode('404 Not Found'));
        }
        die;
        break;
    default:
        header('location:/tip?message=!'.urlencode('404 Not Found'));
        break;
}


?>