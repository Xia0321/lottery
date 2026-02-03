<?php
include('../data/comm.inc.php');
include('../data/mobivar.php');
include('../func/func.php');
include('../func/csfunc.php');
include('../func/userfunc.php');
include('../include.php');
include('./checklogin.php');
include('../func/jsfunc.php');
switch ($_REQUEST['xtype']) {
    case 'show':
        $sdate = week();
        $tpl->assign('sdate', $sdate);
        $tpl->assign('moneytype', $config['moneytype']);
        $msql->query("select kfurl from `$tb_config`");
        $msql->next_record();
        $tpl->assign('kfurl', $msql->f('kfurl'));

        $tpl->assign('gname', $config['gname']);
        $gamecs = getgamecs($userid);
        $gamecs = getgamename($gamecs);
        $tpl->assign('gamecs', $gamecs);
        $tpl->assign('gid', $gid);
        $tpl->assign('b', $b);
        $tpl->assign('webname', $config['webname']);
        $tpl->assign('gname', $config['gname']);
        $tpl->assign('class', $config['class']);
        $tpl->assign('kjurl', $config['kjurl']);
        $tpl->assign('fenlei', $config['fenlei']);
        $tpl->assign('fast', $config['fast']);
        $msql->query("select * from `$tb_user` where userid='$userid'");
        $msql->next_record();
        $tpl->assign('username', $msql->f('username').'('.$msql->f('defaultpan').'')');
        $tpl->display('other.html');
    break;
    
}