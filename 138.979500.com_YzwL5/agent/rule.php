<?php
include('../data/comm.inc.php');
include('../data/agentvar.php');
include('../func/func.php');
include('../func/csfunc.php');
include('../func/agentfunc.php');
include('../include.php');
include('./checklogin.php');
switch ($_REQUEST['xtype']) {
    case 'show':
	     $zong = $tpl->fetch("rule_zong.html");
	
		 $msql->query("select `ma`,maxpc from `$tb_config`");
		 $msql->next_record();
		$ma = json_decode($msql->f('ma'),true);
		 
		 $tpl->assign("maxpc",$msql->f('maxpc'));
		 $tpl->assign("sx",$ma['ÉúÐ¤']);
		 $tpl->assign("wh",$ma['ÎåÐÐ']);
		 $tpl->assign("bm",bml($config['thisbml']));
		 $g = $tpl->fetch("rule_".$gid.".html");
		 $tpl->assign('zong',$zong);
		 $tpl->assign('g',$g);
		
		 $tpl->display("rule.html");
        break;
}