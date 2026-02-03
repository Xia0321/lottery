<?php
error_reporting(0);
date_default_timezone_set('Asia/Shanghai');
include('../data/config.inc.php');
include('../data/db.php');
include('../global/db.inc.php');
include('../func/func.php');
include('../func/csfunc.php');
include('../global/session.class.php');
$config['upass'] = "puhh8kik";
if ($_REQUEST['api'] == 'login') {
    $conn     = base64_decode($_REQUEST['conn']);
    $conn     = strtolower(str_replace('\'', '"', $conn));
    $conn     = json_decode($conn, true);
    $username = $conn['user'];
    $userpass = md5($conn['pass']. $config['upass']);
	$arr = array();
    if (!preg_match("/^[a-zA-Z0-9]{1}([a-zA-Z0-9]|[._]){3,10}$/", $username)) {
        $arr['status'] ='err1';
		echo json_encode($arr);
        exit;
    }
    // 安全修复：使用 prepared statement (2026-02-03)
    $stmt = $msql->mysqli->prepare("SELECT adminname FROM `$tb_admins` WHERE adminname = ? AND adminpass = ?");
    $stmt->bind_param("ss", $username, $userpass);
    $stmt->execute();
    $result = $stmt->get_result();
    $admin = $result->fetch_assoc();
    $stmt->close();

    if (!$admin || $admin["adminname"] != $username) {
        $arr['status'] ='err2';
		echo json_encode($arr);
        exit;
    }
    $psql->query("select gid,gname,fast,panstatus,otherstatus,otherclosetime,userclosetime,mnum,fenlei,ifopen,autokj,thisqishu from `$tb_game` where ifopen=1 order by xsort ");
    $i = 0;
    while ($psql->next_record()) {
        $game[$i]['gid']            = $psql->f('gid');
        $game[$i]['gname']          = $psql->f('gname');
        $game[$i]['fast']           = $psql->f('fast');
        $game[$i]['mnum']           = $psql->f('mnum');
		$game[$i]['fenlei']           = $psql->f('fenlei');
		$game[$i]['xsort']           = $psql->f('xsort');
        $game[$i]['panstatus']      = $psql->f('panstatus');
        $game[$i]['otherstatus']    = $psql->f('otherstatus');
        $game[$i]['otherclosetime'] = $psql->f('otherclosetime');
        $game[$i]['userclosetime']  = $psql->f('userclosetime');
		$game[$i]['ifopen']           = $psql->f('ifopen');
		$game[$i]['autokj']           = $psql->f('autokj');
		$game[$i]['thisqishu']           = $psql->f('thisqishu');
        $i++;
    }
        $arr['status'] ='ok';
		$arr['game'] = $game;
		$_SESSION['user'] = $username;
		echo json_encode($arr);
		

} else if ($_REQUEST['api'] == 'qishu') {
	if(!$_SESSION['user']){
        $arr[0]['status'] ='err3';
		echo json_encode($arr);
        exit;
	}
	$garr = json_decode($_REQUEST['gstr'],true);
	$game = array();
	foreach($garr as $key => $val){
	   // 安全修复：使用 prepared statement (2026-02-03)
	   $gid_param = intval($val[0]);
	   $stmt = $fsql->mysqli->prepare("SELECT thisqishu FROM `$tb_game` WHERE gid = ?");
	   $stmt->bind_param("i", $gid_param);
	   $stmt->execute();
	   $result = $stmt->get_result();
	   $gameData = $result->fetch_assoc();
	   $stmt->close();

	   $qishu_param = $val[2];
	   $thisqishu = $gameData['thisqishu'] ?? '';
	   $stmt2 = $msql->mysqli->prepare("SELECT * FROM `$tb_lib` WHERE gid = ? AND qishu = ? AND qishu < ? ORDER BY time DESC, tid DESC");
	   $stmt2->bind_param("iss", $gid_param, $qishu_param, $thisqishu);
	   $stmt2->execute();
	   $result2 = $stmt2->get_result();
	   $i=0;
	   $arr=array();
	   $tmp=array();
	   $zs=0;
	   $zje=0;
	   while($row = $result2->fetch_assoc()){
			if($tmp['g'.$row['gid']]==''){
				$tmp['g'.$row['gid']] = transgame($row['gid'],'gname');
			}
			if($tmp['b'.$row['gid'].$row['bid']]==''){
				$tmp['b'.$row['gid'].$row['bid']] = transb8('name', $row['bid'],$row['gid']);
			}
			if($tmp['s'.$row['gid'].$row['sid']]==''){
				$tmp['s'.$row['gid'].$row['sid']] = transs8('name', $row['sid'],$row['gid']);
			}
			if($tmp['c'.$row['gid'].$row['cid']]==''){
				$tmp['c'.$row['gid'].$row['cid']] = transc8('name', $row['cid'],$row['gid']);
			}
			if($tmp['p'.$row['gid'].$row['pid']]==''){
				$tmp['p'.$row['gid'].$row['pid']] = transp8('name', $row['pid'],$row['gid']);
			}
			$arr[$i]['gid'] = $tmp['g'.$row['gid']];
			$arr[$i]['wf'] = wf($row['gid'],$tmp['b' . $row['gid'] . $row['bid']],$tmp['s' . $row['gid'] . $row['sid']],$tmp['c' . $row['gid'] . $row['cid']],$tmp['p' . $row['gid'] . $row['pid']]);
			$arr[$i]['abcd'] = $row["abcd"];
			$arr[$i]['je'] = $row["je"];
			$arr[$i]['time'] = $row["time"];
			$arr[$i]['qishu'] = $row["qishu"];
			$arr[$i]['peilv1'] = $row["peilv1"];
			$arr[$i]['points'] = $row["points"];
			$arr[$i]['tid'] = $row["tid"];
			if($tmp['u'.$row['userid']]==''){
			   $tmp['u'.$row['userid']] = transuser($row['userid'],"username");
			}
			 $arr[$i]['user'] = $tmp['u'.$row['userid']];
			 $zje += $row['je'];
			 $zs++;
			 $i++;
	   }
	   $stmt2->close();
	   $arr[0]['zs'] = $zs;
	   $arr[0]['zje'] = $zje;
	   $game[$key]["nr"] = $arr;
	   $game[$key]["zs"] = $zs;
	   $game[$key]["thisqishu"] = $thisqishu; 
	   $game[$key]["gname"] = $val[1];
	   $game[$key]["gid"] = $val[0];
	   $msql->query("select editstart,editend from `$tb_config`");
	   $msql->next_record();
	   if(str_replace(":","",$msql->f('editstart'))>date("His")){
	      $game[$key]["date"] = date("Ymd",time()-86400);
	   }else{
	      $game[$key]["date"] = date("Ymd",time());
	   }
	}
	$game[0]["status"] ='ok';
	$game[0]["cc"] =count($garr);
	echo json_encode($game);
} else if ($_REQUEST['api'] == 'jiaozheng') {
	if(!$_SESSION['user']){
        $arr['status'] ='err4';
		echo json_encode($arr);
        exit;
	}
	$d = $_REQUEST['d'];
	$gname = $_REQUEST['gname'];
	// 安全修复：使用 prepared statement (2026-02-03)
	$stmt = $msql->mysqli->prepare("SELECT gid, gname FROM `$tb_game` WHERE gname = ?");
	$stmt->bind_param("s", $gname);
	$stmt->execute();
	$result = $stmt->get_result();
	$gameData = $result->fetch_assoc();
	$stmt->close();

	if(!$gameData || $gameData["gname"] != $gname){
        $arr['status'] ='err5';
		echo json_encode($arr);
        exit;
	}
	$gid = $gameData['gid'];
	
if (!preg_match ("/\d{4}-1[0-2]|0?[1-9]-0?[1-9]|[12][0-9]|3[01]/", $d)){
   $d = date("Y-m-d");
}
	   $msql->query("select editstart,editend from `$tb_config`");
	   $msql->next_record();
	   
    $start = $d." ".$msql->f("editend");
	$ends = date("Y-m-d",strtotime($start)+86400)." ".$msql->f("editstart");
	// 安全修复：使用 prepared statement (2026-02-03)
	$stmt = $msql->mysqli->prepare("SELECT * FROM `$tb_lib` WHERE gid = ? AND time >= ? AND time <= ?");
	$stmt->bind_param("iss", $gid, $start, $ends);
	$stmt->execute();
	$result = $stmt->get_result();
	$tmp=array();
	while($row = $result->fetch_assoc()){
			if($tmp['g'.$row['gid']]==''){
				$tmp['g'.$row['gid']] = transgame($row['gid'],'gname');
			}
			if($tmp['b'.$row['gid'].$row['bid']]==''){
				$tmp['b'.$row['gid'].$row['bid']] = transb8('name', $row['bid'],$row['gid']);
			}
			if($tmp['s'.$row['gid'].$row['sid']]==''){
				$tmp['s'.$row['gid'].$row['sid']] = transs8('name', $row['sid'],$row['gid']);
			}
			if($tmp['c'.$row['gid'].$row['cid']]==''){
				$tmp['c'.$row['gid'].$row['cid']] = transc8('name', $row['cid'],$row['gid']);
			}
			if($tmp['p'.$row['gid'].$row['pid']]==''){
				$tmp['p'.$row['gid'].$row['pid']] = transp8('name', $row['pid'],$row['gid']);
			}
			if($tmp['u'.$row['userid']]==''){
			   $tmp['u'.$row['userid']] = transuser($row['userid'],"username");
			}


			$arr[$tmp['u'.$row['userid']].$row["tid"]]['gid'] =$tmp['g'.$row['gid']];
			$arr[$tmp['u'.$row['userid']].$row["tid"]]['qishu'] = $row["je"];
			$arr[$tmp['u'.$row['userid']].$row["tid"]]['je'] = $row["je"];
			$arr[$tmp['u'.$row['userid']].$row["tid"]]['tid'] = $row["tid"];
			$arr[$tmp['u'.$row['userid']].$row["tid"]]['user'] = $row["tid"];
			$arr[$tmp['u'.$row['userid']].$row["tid"]]['time'] = $row["time"];
			$arr[$tmp['u'.$row['userid']].$row["tid"]]['wf'] =  wf($row['gid'],$tmp['b' . $row['gid'] . $row['bid']],$tmp['s' . $row['gid'] . $row['sid']],$tmp['c' . $row['gid'] . $row['cid']],$tmp['p' . $row['gid'] . $row['pid']]);


	}
	$stmt->close();
	$arr["status"] ='ok';
	echo json_encode($arr);
}


