<?php
require("./checkagent.php");
require "./manfunc.php";
///agent/report/bets?username=99kk01&lottery=BJPK10&begin=2020-01-14&end=2020-01-14&settle=false&page=2
$f = $msql->arr("select userid,layer,username,ifagent from `$tb_user` where userid='$userid'",1);
$f = $f[0];
$tpl->assign("f",$f);
$dates =getthisdate();
$js = 0;
$gid = getgidman($_REQUEST['lottery']);
$tpl->assign("lottery",$_REQUEST["lottery"]);


$username = $_REQUEST['username'];
if(!preg_match("/^[a-zA-Z0-9]{1}([a-zA-Z0-9]|[._]){1,10}\$/", $username)){
    exit;
}

;
$u = $msql->arr("select userid,layer,username,ifagent from `$tb_user` where fid{$f['layer']}='{$f['userid']}' and username='$username' and ifagent=0",1);
if(!$u){
	exit;
}
$u = $u[0];
$tpl->assign("u",$u);

if(is_numeric($gid) && strlen($gid)==3){
    $whi = " gid='$gid' and userid='{$u['userid']}' and z=9 ";
}else{
    $whi = " userid='{$u['userid']}' and z=9 ";
}

$page = r1p($_REQUEST['page']);
$psize  = $config['psize1'];
$psize=300;
$msql->query("select count(id) from `$tb_lib` where $whi ");
$msql->next_record();
$rcount = pr0($msql->f(0));
$pcount = $rcount % $psize == 0 ? $rcount / $psize : (($rcount - $rcount % $psize) / $psize + 1);
//$page>$pcount && $page=$pcount;
$page<1 && $page=1; 
       
$tpl->assign("dates",$dates);       
$tpl->assign("page",$page);     
$tpl->assign("pcount",$pcount);    
$tpl->assign("rcount",$rcount);

//echo "select * from `$tb_lib` where gid='$gid' and dates='$dates' and userid='{$u['userid']}' and z=9 limit ".($page-1).",".$psize;
$bao = $msql->arr("select * from `$tb_lib` where $whi order by gid,qishu,id desc limit ".(($page-1)*$psize).",".$psize,1);
$tmp=[];
foreach($bao as $k => $v){
	$bao[$k]['week'] = rweek(date("w",strtotime($v['time'])));
            if($tmp['f'.$v['gid']]==''){
                $msql->query("select gname,fenlei from `$tb_game` where gid='{$v['gid']}'");
                $msql->next_record();
                $tmp['f'.$v['gid']] = $msql->f("fenlei");
                $tmp['g'.$v['gid']] = $msql->f("gname");
            }
	        if($tmp['b'.$v['gid'].$v['bid']]==''){
				$tmp['b'.$v['gid'].$v['bid']] = transb8('name', $v['bid'],$v['gid']);
			}
			if($tmp['s'.$v['gid'].$v['sid']]==''){
				$tmp['s'.$v['gid'].$v['sid']] = transs8('name', $v['sid'],$v['gid']);
			}
			if($tmp['c'.$v['gid'].$v['cid']]==''){
				$tmp['c'.$v['gid'].$v['cid']] = transc8('name', $v['cid'],$v['gid']);
			}
			if($tmp['p'.$v['gid'].$v['pid']]==''){
				$tmp['p'.$v['gid'].$v['pid']] = transp8('name', $v['pid'],$v['gid']);
			}
            $bao[$k]['gname'] = $tmp['g'.$v['gid']];
			$bao[$k]['gid'] = $tmp['g'.$gid];
			$bao[$k]['wf'] = gettitle($tmp['f'.$v['gid']],$tmp['s' . $v['gid'] . $v['sid']],$tmp['p' .$v['gid'] . $v['pid']]);
}

$tpl->assign("bao",$bao);
$tpl->display("agent_report.html");

function gettitle($fenlei, $sname, $name)
{
    $v = "";
    switch ($fenlei) {
        case 107:
            if ($sname == "冠亞軍組合") {
                if (!is_numeric($name)) {
                    $v = "冠亞『" . $name . "』";
                } else {
                    $v = "冠亞和『" . $name . "』";
                }
            } else {
                $v = $sname . "『" . $name . "』";
            }
            $v = pk10dx($v);
            break;
        case 101:
            $v = $sname . "『" . str_replace("總和", "", $name) . "』";
            break;
        case 103:
            if ($sname == '連碼') {
                $v = $name;
            } else {
                $v = $sname . "『" . str_replace("總和", "", $name) . "』";
            }
            break;
        case 161:
            $v = $sname . "『" . $name . "』";
            break;
    }
    return $v;
}

function pk10dx($v){
    $v = str_replace('第3名', '第三名', $v);
    $v = str_replace('第4名', '第四名', $v);
    $v = str_replace('第5名', '第五名', $v);
    $v = str_replace('第6名', '第六名', $v);
    $v = str_replace('第7名', '第七名', $v);
    $v = str_replace('第8名', '第八名', $v);
    $v = str_replace('第9名', '第九名', $v);
    $v = str_replace('第10名', '第十名', $v);
    return $v;
}
