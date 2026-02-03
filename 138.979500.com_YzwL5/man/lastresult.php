<?php
require("./check.php");
include("./manfunc.php");

$game = $_GET["lottery"];
$gid = getgidman($game);
$fenlei = getfenleiman($game);
if(!is_numeric($gid)){
    die;
}

$arr["lottery"] = $game;
$time = date("Y-m-d H:i:s");
$msql->query("select * from `$tb_kj` where gid='$gid' and kjtime<'$time' and m1!='' order by gid,qishu desc limit 1");
$msql->next_record();
$arr["drawNumber"] = $msql->f("qishu");
$mnum = transgame($gid,"mnum");
$res=[];
for($i=0;$i<$mnum;$i++){
    $res[] = $msql->f("m".($i+1));
}
$arr["result"] = implode(',',$res);
$arr["detail"] = "";
$arr["drawTime"] = strtotime($msql->f("kjtime"))*1000;
echo json_encode($arr,JSON_UNESCAPED_UNICODE);
/*
{"lottery":"BJPK10","drawNumber":"741959","result":"9,8,10,3,4,5,6,2,1,7","detail":"B6=5,2,第六名-5;DX2=D,2,亚军-大;DX3=D,2,第三名-大;DX5=X,2,第五名-小;DX6=X,3,第六名-小;DX9=X,3,第九名-小;DX10=D,3,第十名-大;DS1=D,5,冠军-单;DS3=S,2,第三名-双;DS6=D,3,第六名-单;DS7=S,3,第七名-双;LH2=L,5,亚军-龙;LH5=H,2,第五名-虎;","drawTime":1575109800000}
 */