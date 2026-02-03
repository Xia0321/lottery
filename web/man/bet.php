<?php
require "./check.php";
require "./manfunc.php";
//file_put_contents("./temp_dc/222.txt", $_GET['t'].date("H:i:s")."\r\n",FILE_APPEND);
$data = json_decode(file_get_contents('php://input'), true);
if(!is_array($data)){
    $str = file_get_contents('php://input');
    $str = str_replace(':,', ':1,', $str);
    $str = str_replace('},,{', '},{,', $str);
    $str = str_replace('},]', '}]', $str);
    $str = str_replace('{,', '{', $str);
    //echo $str;
    $data = json_decode($str,true);
}
if(!is_array($data)){
    $arr = ["status" => 0, "message" => "号码格式不正确"];
    echo json_encode($arr,JSON_UNESCAPED_UNICODE);
    die;
}
$msql->query("select editstart,zcmode from `$tb_config`");
$msql->next_record();
$editstart = $msql->f("editstart");
$zcmode = $msql->f("zcmode");

$game = $data["lottery"];
$qishu = $data["drawNumber"];
$bets = $data["bets"];
$gid = getgidman($game);
$fenlei = getfenleiman($game);

$us = $msql->arr("select * from `{$tb_user}` where userid='{$userid}'", 1);
$us = $us[0];
if ($us["status"] != 1) {
    $arr = ["status" => 10, "message" => "用户已经冻结"];
    echo json_encode($arr,JSON_UNESCAPED_UNICODE);
    die;
}
$ab = "A";
$abcd = $us["defaultpan"];
$ifexe = $us["ifexe"];
$pself = $us["pself"];
$wid = $us["wid"];
if ($us["layer"] > 1) {
    $msql->query("select ifexe,pself,wid from `{$tb_user}` where userid='{$us['fid1']}'");
    $msql->next_record();
    $ifexe = $msql->f('ifexe');
    $pself = $msql->f('pself');
    $wid = $msql->f("wid");
}


$msql->query("select patt from `$tb_web` where wid='$wid'");
$msql->next_record();
$patt= $msql->f("patt");

$msql->query("select * from `{$tb_kj}` where gid='{$gid}' and qishu='{$qishu}'");
$msql->next_record();
$fsql->query("select * from `{$tb_game}` where gid='{$gid}'");
$fsql->next_record();
//echo "select * from `{$tb_kj}` where gid='{$gid}' and qishu='{$qishu}'";
if ($fsql->f('panstatus') == 0 || time() > strtotime($msql->f("closetime")) - $fsql->f("userclosetime")) {
    //echo $fsql->f('panstatus');
    $arr = ["status" => 20, "message" => "已经关盘"];
    echo json_encode($arr,JSON_UNESCAPED_UNICODE);
    die;
}
$cs = json_decode($fsql->f("cs"), true);
$pan = json_decode($fsql->f("pan"), true);
$patt = json_decode($fsql->f("patt" . $patt), true);

if ($_SESSION['exe'] == 1 & time() - $_SESSION['exetime'] < 30) {
    $arr = ["status" => 30, "message" => "系统忙,请重试!"];
    echo json_encode($arr,JSON_UNESCAPED_UNICODE);
    die;
}
if (time() - $_SESSION['exetime'] < 3) {
    $arr = ["status" => 40, "message" => "系统忙!"];
    echo json_encode($arr,JSON_UNESCAPED_UNICODE);
    die;
}
$_SESSION['exe'] = 1;
$_SESSION['exetime'] = time();
$je = 0;
foreach ($bets as $k => $v) {
    if (!is_numeric($v["amount"]) || $v["amount"] % 1 != 0) {
        $arr = ["status" => 50, "message" => "金额不正确!"];
        echo json_encode($arr,JSON_UNESCAPED_UNICODE);
        die;
    }
    $je += $v["amount"];
}
if ($je > $us['kmoney']) {
    $arr = ["status" => 60, "message" => "余额不足!"];
    echo json_encode($arr,JSON_UNESCAPED_UNICODE);
    die;
}
if ($us["fudong"] == 1 & time() - strtotime($us['ftime']) > 86400) {
    $arr = ["status" => 70, "message" => "系统清算中,请稍后投注!"];
    echo json_encode($arr,JSON_UNESCAPED_UNICODE);
    die;
}
if ($us["yingdeny"] == 1) {
    $arr = ["status" => 80, "message" => "赢利超限,请明日再投注!"];
    echo json_encode($arr,JSON_UNESCAPED_UNICODE);
}

$u = getfid($userid);
$zc = getzcnew($userid, $u, $us["layer"], $gid, $zcmode);
$czc = count($zc) - 1;
$tid = setuptid();
$ip = getip();
if (date("His") <= str_replace(':', '', $editstart)) {
    $dates = sqldate(time() - 86400);
} else {
    $dates = sqldate(time());
}
$fsql->query("delete from `{$tb_libu}` where  userid='{$userid}'");
$tmp = [];
$play = [];
$jex = 0;
foreach ($bets as $i => $v) {
    $ptype = $v['game'] . '_' . $v['contents'];
    $msql->query("select * from `x_splay` where gid='{$fenlei}' and type='{$ptype}'");
    $msql->next_record();
    $bets[$i]['pid'] = $msql->f("pid");
    $msql->query("select bid,sid,cid,peilv1,peilv2,ifok,name,pl,yautocs,ystart from `{$tb_play}` where gid='{$gid}' and pid='" . $bets[$i]['pid'] . "'");
    $msql->next_record();
    $bid = $msql->f('bid');
    $sid = $msql->f('sid');
    $cid = $msql->f('cid');
    $pid = $bets[$i]['pid'];
    $pname = $msql->f('name');
    $ifok = $msql->f('ifok');
    $yautocs = $msql->f('yautocs');
    $ystart = $msql->f('ystart');
    $pl = $msql->f('pl');
    $peilv1 = 0;
    $peilv2 = 0;
    $peilv1s = 0;
    $peilv2s = 0;
    $peilv1 = $msql->f('peilv1');
    $peilv2 = $msql->f('peilv2');
    if ($us["layer"] > 1 & $ifexe == 1) {
        $fsql->query("select peilv1,peilv2,ystart,yautocs from `{$tb_play_user}` where userid='{$us['fid1']}' and gid='{$gid}' and  pid='" . $bets[$i]['pid'] . "' ");
        $fsql->next_record();
        $peilv1s = $fsql->f('peilv1');
        $peilv2s = $fsql->f('peilv2');
        $yautocs = $msql->f('yautocs');
        $ystart = $msql->f('ystart');
    }
    if ($ifok != 1) {
        continue;
    }
    if (!isset($tmp["maxje" . $cid])) {
        $fsql->query("select ftype,dftype from `{$tb_class}` where gid='{$gid}' and cid='{$cid}'");
        $fsql->next_record();
        $ftype = $fsql->f('ftype');
        $dftype = $fsql->f('dftype');
        $abcha = 0;
        $abcdcha = 0;
        $tmpabcd = 0;
        $tmpab = 0;
        if ($pan[$dftype]['ab'] == 1) {
            if ($ab == 'B') {
                $abcha = $patt[$ftype]['ab'];
            }
            $tmpab = $ab;
        }
        if ($pan[$dftype]['abcd'] == 1) {
            if ($abcd != 'A') {
                $abcdcha = $patt[$ftype][strtolower($abcd)];
            }
            $tmpabcd = $abcd;
        }
        $points = getpoints8($dftype, $tmpabcd, $tmpab, $userid, $gid);
        $sqle = ",points='" . $points . "'";
        $tmppeilvcha = 0;
        for ($j = 0; $j < $czc; $j++) {
            $sqle .= ",zc" . $j . "='" . $zc[$j]['zc'] . "'";
            if ($j > 0) {
                $arr = getzcs8($ftype, $u[$j], $gid);
                $tmppeilvcha += $arr['peilvcha'];
                $tmp["lowpeilv" . $cid][$j] = $arr['lowpeilv'];
                $tmp["peilvcha" . $cid][$j] = $tmppeilvcha + $abcdcha - $abcha;
                $points = getpoints8($dftype, $tmpabcd, $tmpab, $u[$j], $gid);
                $sqle .= ",points" . $j . "='" . $points . "'";
                $sqle .= ",uid" . $j . "='" . $u[$j] . "'";
                if ($j == 1 & $ifexe == 1 & $pself == 1) {
                    $tmppeilvcha = 0;
                }
            }
        }
        $arr = getzcs8($ftype, $userid, $gid);
        $tmppeilvcha += $arr['peilvcha'];
        $peilvchax = $tmppeilvcha + $abcdcha - $abcha;
        $lowpeilvx = $arr['lowpeilv'];
        $arr = getjes8($dftype, $userid, $gid);
        $cmaxjex = $arr['cmaxje'];
        $maxjex = $arr['maxje'];
        $tmp["maxje" . $cid] = $maxjex;
        $tmp["cmaxje" . $cid] = $cmaxjex;
        $tmp["lowpeilvx" . $cid] = $lowpeilvx;
        $tmp["peilvchax" . $cid] = $peilvchax;
        $tmp["sqle" . $cid] = $sqle;
    }
    if ($v["amount"] > $tmp["maxje" . $cid]) {
        continue;
    }
    $fsql->query("select sum(je) from `{$tb_lib}` where gid='{$gid}' and pid='{$pid}' and userid='{$userid}' and qishu='{$qishu}' ");
    $fsql->next_record();
    if ($fsql->f(0) + $v['amount'] > $tmp["cmaxje" . $cid]) {
        continue;
    }
    $tmppeilv = 0;
    $tmppeilv2 = 0;
    if ($us["layer"] > 1 & $ifexe == 1 & $pself == 1) {
        $tmppeilv = moren($peilv1s - $tmp["peilvchax" . $cid], $tmp["lowpeilvx" . $cid]);
    } else {
        if ($us["layer"] > 1 & $ifexe == 1) {
            $tmppeilv = moren($peilv1 - $tmp["peilvchax" . $cid] - $peilv1s, $tmp["lowpeilvx" . $cid]);
        } else {
            $tmppeilv = moren($peilv1 - $tmp["peilvchax" . $cid], $tmp["lowpeilvx" . $cid]);
        }
    }
    if ($cp > 5) {
        $sql = " insert into `{$tb_libu}` ";
    } else {
        $sql = " insert into `{$tb_lib}` ";
    }
    $sql .= " set dates='{$dates}',gid='{$gid}',qishu='{$qishu}',tid='{$tid}',userid='{$userid}',bid='{$bid}',sid='{$sid}',cid='{$cid}',pid='{$pid}',abcd='{$abcd}',ab='{$ab}',content='',time=NOW(),je='" . $v['amount'] . "',xtype='0',z='9',bs=1,peilv1='" . $tmppeilv . "',peilv2='" . $tmppeilv2 . "',sv='" . $_SESSION['sv'] . "',ip='{$ip}',code=''";
    $sql .= $tmp["sqle" . $cid];
    for ($j = 1; $j < $czc; $j++) {
        if ($ifexe == 1 & $pself == 1 & $j > 1) {
            $sql .= ",peilv1" . $j . "='" . moren($peilv1s - $tmp["peilvcha" . $cid][$j], $tmp["lowpeilv" . $cid][$j]) . "',peilv2" . $j . "='0'";
        } else {
            if ($ifexe == 1 & $j > 1) {
                $sql .= ",peilv1" . $j . "='" . moren($peilv1 - $tmp["peilvcha" . $cid][$j] - $peilv1s, $tmp["lowpeilv" . $cid][$j]) . "',peilv2" . $j . "='0'";
            } else {
                $sql .= ",peilv1" . $j . "='" . moren($peilv1 - $tmp["peilvcha" . $cid][$j], $tmp["lowpeilv" . $cid][$j]) . "',peilv2" . $j . "='0'";
            }
        }
    }
    $sql .= ",bz=''";
    $msql->query("insert into `$tb_log` set ip='$ip',userid='$userid',gid='$gid',time=NOW(),type='man',content='".str_replace("'","",$sql)."'");
    if ($msql->query($sql)) {
        $jex += $v["amount"];
        $play["ids"][] = $tid;
        $play["odds"][] = $tmppeilv;
    }
    $tid++;
}
if ($cp > 5) {
    $msql->query("insert into `{$tb_lib}` select NULL,tid,userid,dates,qishu,gid,bid,sid,cid,pid,abcd,ab,peilv1,peilv2,points,content,je,time,xtype,z,prize,znum,zc0,zc1,zc2,zc3,zc4,zc5,zc6,zc7,zc8,points1,points2,points3,points4,points5,points6,points7,points8,peilv11,peilv12,peilv13,peilv14,peilv15,peilv16,peilv17,peilv18,peilv21,peilv22,peilv23,peilv24,peilv25,peilv26,peilv27,peilv28,uid1,uid2,uid3,uid4,uid5,uid6,uid7,uid8,flytype,sv,bz,bs,ip,code,kk from `{$tb_libu}` where userid='{$userid}' order by id");
    $msql->query("delete from x_libu where userid='{$userid}'");
}
$play["account"] = ["balance" => p2($us["kmoney"] - $jex), "betting" => $jex, "maxLimit" => $us["kmaxmoney"], "result" => $us["sy"], "type" => 0, "userid" => $us["username"]];
$play["status"] = 0;
$msql->query("update `{$tb_user}` set kmoney=kmoney-{$jex} where userid='{$userid}'");
usermoneylog($userid, 0 - $jex, $us["kmoney"] - $jex, '投注');
echo json_encode($play);
unset($play);
unset($_SESSION['exe']);

/*
{"lottery":"BJPK10","drawNumber":"736867","bets":[{"game":"DX1","contents":"D","amount":2,"odds":1.9404,"title":"冠军"},{"game":"DX1","contents":"X","amount":2,"odds":1.9404,"title":"冠军"}],"ignore":false}

{"account":{"balance":195.8808,"betting":4.0,"maxLimit":200.0,"result":-0.1192,"type":0,"userid":"xlc008-hao661"},"ids":["2019073113315223953808800001","2019073113315223953808800002"],"odds":["1.9404","1.9404"],"status":0}
*/