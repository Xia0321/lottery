<?php
require("./check.php");
$us = $msql->arr("select * from `$tb_user` where userid='$userid'",1);
$wjs = $msql->arr("select sum(je) as je from `$tb_lib` where userid='$userid' and z=9",1);
$us = $us[0];
$arr=[];
$arr[0] = ["balance"=>pr1($us["kmoney"]+0),"betting"=>pr0($wjs[0]["je"]),"maxLimit"=>$us["kmaxmoney"]+0,"result"=>pr1($us["sy"]+0),"type"=>0];
$arr[1] = ["balance"=>0.0,"maxLimit"=>0.0,"type"=>1];
$arr[2] = ["balance"=>0.0,"maxLimit"=>0.0,"type"=>2];
$arr[3] = ["balance"=>0.0,"maxLimit"=>0.0,"type"=>3];
echo json_encode($arr);
/*
[{"balance":199.6424,"betting":0.0,"maxLimit":200.0,"result":-0.3576,"type":0},{"balance":0.0,"maxLimit":0.0,"type":1},{"balance":0.0,"maxLimit":0.0,"type":2},{"balance":0.0,"maxLimit":0.0,"type":3}]
 */