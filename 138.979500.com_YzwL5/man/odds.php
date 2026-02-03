<?php
require("./check.php");
include("./manfunc.php");

$game = $_GET["lottery"];
$gid = getgidman($game);
$fenlei = getfenleiman($game);
if(!is_numeric($gid)){
    $arr = ["status" => 0, "message" => "彩种不正确"];
    echo json_encode($arr);
    die;
}
$msql->query("select fid1,defaultpan from `$tb_user` where userid='$userid'");
$msql->next_record();
$fid1= $msql->f("fid1");
$abcd = strtolower($msql->f("defaultpan"));

$msql->query("select ifexe,pself,wid from `$tb_user` where userid='$fid1'");
$msql->next_record();
$ifexe = $msql->f("ifexe");
$pself = $msql->f("pself");
$wid = $msql->f("wid");
$msql->query("select patt from `$tb_web` where wid='$wid'");
$msql->next_record();
$patt = $msql->f("patt");

$msql->query("select patt".$patt." as patt from `$tb_game` where gid='$gid'");
$msql->next_record();
$patt = json_decode($msql->f("patt"),true);

$ctype = $_GET["games"];
$ctype = explode(',', $ctype);
$ctype=implode("','", $ctype);
$ctype = "'".$ctype."'";
//echo $ctype;exit;
if(empty($_GET["games"])){
    $rs = $msql->arr("select * from `x_splay` where gid='$fenlei'",1);
}else{
	$rs = $msql->arr("select * from `x_splay` where gid='$fenlei' and ctype in($ctype)",1);
}

$parr=[];
$c=[];
foreach($rs as $k => $v){
    $parr[] = $v["pid"];
    $c['p'.$v['pid']] = $v["type"];
}

$pstr = implode(',', $parr);

$odds=[];
$rs = $msql->arr("select peilv1,cid,pid from `$tb_play` where gid='$gid' and pid in($pstr)",1);
foreach($rs as $k => $v){
	$ftype = transc8("ftype",$v["cid"],$fenlei);
	if ($ifexe == 1 & $pself == 1) {
        $peilvcha = getuserpeilvcha2s($userid, $ftype, $fenlei);
    } else {
        $peilvcha = getuserpeilvchas($userid, $ftype, $fenlei);
    }
    $odds[$c["p".$v["pid"]]] = $v["peilv1"] - $peilvcha - $patt[$ftype][$abcd];
}
echo json_encode($odds);

                
            

/*
https://3756529778-xlc.cp168.ws/member/odds?lottery=PK10JSC&games=DX1
 */