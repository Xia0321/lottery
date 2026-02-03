<?php
require("./checkagent.php");
require "./manfunc.php";
///agent/report/bets?username=99kk01&lottery=BJPK10&begin=2020-01-14&end=2020-01-14&settle=false&page=2
$f = $msql->arr("select userid,layer,username,ifagent from `$tb_user` where userid='$userid'",1);
$f = $f[0];
$tpl->assign("f",$f);
$dates =getthisdate();
$js = 0;
$tpl->assign("dates",$dates);

$bao =topuser($f["userid"]);

foreach($us as $k => $v){
    if($v['ifagent']==1){
        $b = $msql->query("select sum(je),count(id) from `$tb_lib` where uid{$v['layer']}='{v['userid']}' and z=9");
    }else{
        $b = $msql->query("select sum(je),count(id) from `$tb_lib` where userid='{v['userid']}' and z=9");
    }
    $bao[$k]['zje'] = pr1($msql->f(0));
    $bao[$k]['zs'] = pr0($msql->f(1));

}

$tpl->assign("bao",$bao);
$tpl->display("agent_reportlist.html");
