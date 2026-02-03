<?php
require("./checkagent.php");
$f = $msql->arr("select userid,layer,username,ifagent from `$tb_user` where userid='$userid'",1);
$f = $f[0];
$tpl->assign("f",$f);
$type = r1p($_REQUEST['type']);
$page = r1p($_REQUEST['page']);
$psize  = $config['psize1'];


$msql->query("select count(id) from `$tb_user` where fid{$f['layer']}='{$f['userid']}' and ifagent=0");
$msql->next_record();
$rcount = pr0($msql->f(0));

$pcount = $rcount % $psize == 0 ? $rcount / $psize : (($rcount - $rcount % $psize) / $psize + 1);
//$page>$pcount && $page=$pcount;
$page<1 && $page=1;   

          
$tpl->assign("page",$page);     
$tpl->assign("pcount",$pcount);    
$tpl->assign("rcount",$rcount);
    

$us = $msql->arr("select * from `$tb_user` where fid{$f['layer']}='{$f['userid']}' and ifagent=0 order by userid desc limit ".(($page-1)*$psize).",".$psize,1);

$tpl->assign("us",$us);
$tpl->display("agent_user.html");