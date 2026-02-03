<?php
include "../data/config.inc.php";
include "../data/db.php";
include "../global/db.inc.php";
include "../global/session.class.php";
include "./config.php";
include "../data/pan.inc.php";
include '../data/uservar.php';
include '../func/func.php';
include('../func/userfunc.php');
include('../func/csfunc.php');
include('./include.php');
if($_SESSION['ip']!=getip()){
    sessiondelu();
    header("Location:/login");
    exit;
}

$check=0;
//echo $_COOKIE["ucheck"];
if($_COOKIE["ucheck"]){
	$uuid  = substr($_COOKIE["ucheck"],-8);
  	$checkstr = str_replace($uuid, '', $_COOKIE["ucheck"]);
}
//exit;
if($_SESSION['ucheck']==md5($config['allpass'].$_SESSION['uuid'])){
	 $check=1;
}else if($checkstr==md5($config['allpass'].$uuid)){
	 $check=1;
}
if($check==0 | $config['ifopen']==0 | $config['ifopens']==0){
    sessiondelu();
    header("Location:/login");
    exit;
}
if($_SESSION['uuid']){
	$userid=$_SESSION['uuid'];
}else if($_COOKIE["ucheck"]){
	$userid=$uuid;
}
$msql->query("update `$tb_online` set savetime=NOW(),page='make' where userid='$userid'");
//echo $userid;exit;
