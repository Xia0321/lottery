<?php
require("./check.php");
include("./manfunc.php");

$game = $_GET["lottery"];
$gid = getgidman($game);
$fenlei = getfenleiman($game);
if(!is_numeric($gid)){
    $arr["currentTime"] = msectime();
    echo json_encode($arr);
    die;
}
$msql->query("select editstart,zcmode from `$tb_config`");
$msql->next_record();
$editstart = $msql->f("editstart");
$zcmode = $msql->f("zcmode");

if (date("His") <= str_replace(':', '', $editstart)) {
    $dates = sqldate(time() - 86400);
} else {
    $dates = sqldate(time());
}
$kjtime = date("Y-m-d H:i:s");
$kj = $msql->arr("select * from `$tb_kj` where gid='$gid' and dates='$dates' and kjtime>'$kjtime' order by kjtime limit 1",1);
$kj = $kj[0];
$arr = [];
$arr["closeTime"] = strtotime($kj["closetime"])*1000;
$arr["currentTime"] = msectime();
$arr["drawDate"] = strtotime($kj["dates"])*1000;
$arr["drawNumber"] = $kj["qishu"];
$arr["drawTime"] = strtotime($kj["kjtime"])*1000;
$arr["openTime"] = strtotime($kj["opentime"])*1000;
$msql->query("select qishu from `$tb_kj` where gid='$gid' and qishu<'{$kj['qishu']}' order by qishu desc limit 1");
$msql->next_record();
$arr["pnumber"] = $msql->f("qishu");
$arr["status"] = 1;

header('Content-type: application/json');  
echo json_encode($arr);


/*
{"closeTime":1564575387000,"currentTime":1564575373000,"drawDate":1564502400000,"drawNumber":"31156894","drawTime":1564575405000,"openTime":1564575333000,"pnumber":"31156893","status":1}
 */