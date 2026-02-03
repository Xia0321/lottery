<?php
include('./data/config.inc.php');
include("./global/session.class.php");
//include('./global/img3.class.php');
include('./global/img.class.5.php');

//session_register("login_check_number");
 
//$_vc = new ValidateCode();  //实例化一个对象
//$_vc->doimg();
//$_SESSION['login_check_number'] = $_vc->getCode();//验证码保存到SESSION中

$n = new imgdata;
$list = scandir("./code");
$cl = count($list);
//print_r($list);exit;
while(1){
	$code = $list[rand(2,$cl-1)];
    $_SESSION['login_check_number']  = substr($code,0,4);
	
	//var_dump($_SESSION['login_check_number'] );die;
	
	//file_put_contents("liml.log",$_SESSION['login_check_number'] ,FILE_APPEND|LOCK_EX);
    $n->getdir("./code/".$code);
    $n->img2data();
    if($n->imgform=="image/jpeg" && is_numeric($_SESSION['login_check_number'])){
    	$n->data2img();
    	break;
    }    
}
