<?php
include "../data/config.inc.php";
include "../data/db.php";
include "../global/db.inc.php";
include "../global/session.class.php";
include "./config.php";
include "../data/pan.inc.php";
include '../data/agentvar.php';
include '../func/func.php';
include('../func/agentfunc.php');
include('../func/csfunc.php');
include('./include.php');
if($_SESSION['ip']!=getip()){
    sessiondela();
    header("Location:/login");
    exit;
}

$check=0;
//echo $_COOKIE["ucheck"];
if($_COOKIE["acheck"]){
	$auid  = substr($_COOKIE["acheck"],-8);
  	$checkstr = str_replace($auid, '', $_COOKIE["acheck"]);
}
//exit;
if($_SESSION['acheck']==md5($config['allpass'].$_SESSION['auid2'])){
	 $check=1;
}else if($checkstr==md5($config['allpass'].$auid)){
	 $check=1;
}
if($check==0 | $config['ifopen']==0 | $config['ifopens']==0){
    sessiondela();
    header("Location:/login");
    exit;
}
if($_SESSION['auid']){
	$userid=$_SESSION['auid'];
	$msql->query("update `$tb_online` set savetime=NOW(),page='bao' where userid='".$_SESSION['auid2']."'");
}else if($_COOKIE["acheck"]){
	$msql->query("select fid from `$tb_user` where userid='$auid'");
	$msql->next_record();
	if($msql->f("ifson")==1){
		$userid=$msql->f("fid");
	}else{
        $userid=$auid;
	}
	$msql->query("update `$tb_online` set savetime=NOW(),page='bao' where userid='$auid'");	
}