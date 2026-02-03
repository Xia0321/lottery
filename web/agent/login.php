<?php

include('../data/comm.inc.php');
include('../data/agentvar.php');
include('../func/func.php');
include('../include.php');
if ($_SESSION['auid2'] != '' && $_SESSION['acheck'] == md5($config['allpass'] . $_SESSION['auid2'])) {
    //header("Location:/Agent/index");
    //exit;
}
switch ($_REQUEST['xtype']) {
    case "login":
        include('../global/client.php');
        include("../global/Iplocation_Class.php");
        $sv = rserver();
        $_SESSION['sv'] = $sv;
        $os = getbrowser($_SERVER['HTTP_USER_AGENT']) . '  ' . getos($_SERVER['HTTP_USER_AGENT']);
        $user = strtoupper($_POST['username']);
        $pass = md5($_POST['pass']. $config['upass']);//var_dump($pass);die;
        $code = $_POST['code'];//var_dump($_SESSION['login_check_number']."====".$code);die;
        if ($code != $_SESSION['login_check_number']) {
            echo outjs("验证码错误，请重新输入。");
            echo openurl('/agent/login.php');
            exit;
        }

        if (!preg_match("/^[a-zA-Z0-9]{1}([a-zA-Z0-9]|[._]){1,10}$/", $user) | !preg_match("/^[a-z\d_]{16,64}$/", $pass)) {
            echo outjs("账号或密码错误1111。");
            echo openurl('/agent/login.php');
            exit;
        }

        // 安全修复：使用 prepared statement 防止 SQL 注入 (2026-02-03)
        $stmt = $msql->mysqli->prepare("SELECT errortimes FROM `$tb_user` WHERE username = ?");
        $stmt->bind_param("s", $user);
        $stmt->execute();
        $result = $stmt->get_result();
        $errorData = $result->fetch_assoc();
        $stmt->close();

        if ($errorData && $errorData['errortimes'] >= 5) {
            echo outjs("您的密码错误次数超过5次,请联系上级修改密码!");
            echo openurl('/agent/login.php');
            exit;
        }

        // 安全修复：使用 prepared statement
        $stmt = $msql->mysqli->prepare("SELECT * FROM `$tb_user` WHERE username = ? AND userpass = ? AND ifagent = 1");
        $stmt->bind_param("ss", $user, $pass);
        $stmt->execute();
        $result = $stmt->get_result();
        $userData = $result->fetch_assoc();
        $stmt->close();
        $ip = getip();

        $time = time();
        if (!$userData || $userData['username'] != $user || $userData['userpass'] != $pass) {
            // 安全修复：使用 prepared statement
            $stmt = $msql->mysqli->prepare("INSERT INTO `$tb_user_login` (server, xtype, ip, time, ifok, username, userpass, os) VALUES (?, 1, ?, NOW(), '0', ?, ?, ?)");
            $stmt->bind_param("sssss", $sv, $ip, $user, $pass, $os);
            $stmt->execute();
            $stmt->close();

            $stmt = $msql->mysqli->prepare("UPDATE `$tb_user` SET errortimes = errortimes + 1 WHERE username = ?");
            $stmt->bind_param("s", $user);
            $stmt->execute();
            $stmt->close();

            echo outjs("账号或密码错误。");
            echo openurl('/agent/login.php');
            exit;
        }
        unset($_SESSION['login_check_number']);
        if ($userData['status'] == 0) {
            echo outjs($userdeny);
            echo openurl('/agent/login.php');
            exit;
        }
        $wid = $userData['wid'];
        $err = true;
        if ($wid != $_SESSION['wid']) {
            $err = false;
        }
        if (!$err) {
            //echo outjs("用户名不正确!" . $_SESSION['wid']);
            //echo openurl('/Login');
            //exit;
        }
        if($ipa['i'.$userData['userid']]!=""){
            $ip = $ipa['i'.$userData['userid']];
        }
        $_SESSION['gid'] = $userData['gid'];

        // 安全修复：使用 prepared statement (2026-02-03)
        $stmt = $fsql->mysqli->prepare("INSERT INTO `$tb_user_login` (xtype, ip, time, ifok, username, userpass, server, os) VALUES ('1', ?, NOW(), '1', ?, 'OK', ?, ?)");
        $stmt->bind_param("ssss", $ip, $user, $sv, $os);
        $stmt->execute();
        $stmt->close();

        $stmt = $fsql->mysqli->prepare("UPDATE `$tb_user` SET logintimes = logintimes + 1, lastloginip = ?, lastlogintime = NOW(), online = 1, errortimes = 0 WHERE username = ?");
        $stmt->bind_param("ss", $ip, $user);
        $stmt->execute();
        $stmt->close();

        $passcode = bin2hex(random_bytes(32)); // 安全修复：使用加密安全的随机数

        $stmt = $fsql->mysqli->prepare("INSERT INTO `$tb_online` (page, passcode, xtype, userid, logintime, savetime, ip, server, wid, layer, os) VALUES ('welcome', ?, '1', ?, NOW(), NOW(), ?, ?, ?, ?, ?)");
        $stmt->bind_param("sisssis", $passcode, $userData['userid'], $ip, $sv, $wid, $userData['layer'], $os);
        $stmt->execute();
        $stmt->close();
        $_SESSION['apasscode'] = $passcode;
        $_SESSION['auid2'] = $userData['userid'];
        $_SESSION['acheck'] = md5($config['allpass'] . $userData['userid']);
        $_SESSION['sv'] = $sv;
        $_SESSION['ip'] = $ip;
        if ($userData['ifson'] == 0) {
            $_SESSION['atype'] = 1;
            $_SESSION['auid'] = $userData['userid'];
        } else {
            $_SESSION['auid'] = $userData['fid'];
        }
        if ((($time - strtotime($userData['passtime'])) / (60 * 60 * 24)) >= $config['passtime'] & $config['passtime'] != 0) {
            echo openurl('/agent/changepass.php?xtype=show&url=login&type=1');
            exit;
        }
        
        echo openurl('/agent/top.php?xtype=this');
        break;
    default:
        $tpl->assign("aurl", $config['aurl']);
        $tpl->assign("bgimg", $config['aimg']);
        $tpl->assign('rkey', $config['rkey']);
        if (ismobi() && 1==2) {
            $tpl->display("loginmobi.html");
        } else {
            $tpl->display("login.html");
        }

        break;
}
function ismobi()
{
    // 如果有HTTP_X_WAP_PROFILE则一定是移动设备
    if (isset($_SERVER['HTTP_X_WAP_PROFILE'])) {
        return true;
    }
    // 如果via信息含有wap则一定是www.hnzwz.com移动设备,部分服务商会屏蔽该信息
    if (isset($_SERVER['HTTP_VIA'])) {
        // 找不到为flase,否则为true
        return stristr($_SERVER['HTTP_VIA'], "wap") ? true : false;
    }
    // 脑残法，判断手机发送的客户端标志,兼容性有待提高
    if (isset($_SERVER['HTTP_USER_AGENT'])) {
        $clientkeywords = array(
            'nokia',
            'sony',
            'ericsson',
            'mot',
            'samsung',
            'htc',
            'sgh',
            'lg',
            'sharp',
            'sie-',
            'philips',
            'panasonic',
            'alcatel',
            'lenovo',
            'iphone',
            'ipod',
            'blackberry',
            'meizu',
            'android',
            'netfront',
            'symbian',
            'ucweb',
            'windowsce',
            'palm',
            'operamini',
            'operamobi',
            'openwave',
            'nexusone',
            'cldc',
            'midp',
            'wap',
            'mobile'
        );
        // 从HTTP_USER_AGENT中查找手机浏览器的关键字
        if (preg_match("/(" . implode('|', $clientkeywords) . ")/i", strtolower($_SERVER['HTTP_USER_AGENT']))) {
            return true;
        }
    }
    // 协议法，因为有可能不准确，放到最后判断
    if (isset($_SERVER['HTTP_ACCEPT'])) {
        // 如果只支持wml并且不支持html那一定是移动设备
        // 如果支持wml和html但是wml在html之前则是移动设备
        if ((strpos($_SERVER['HTTP_ACCEPT'], 'vnd.wap.wml') !== false) && (strpos($_SERVER['HTTP_ACCEPT'], 'text/html') === false || (strpos($_SERVER['HTTP_ACCEPT'], 'vnd.wap.wml') < strpos($_SERVER['HTTP_ACCEPT'], 'text/html')))) {
            return true;
        }
    }
    return false;
    /*
    $agent = $_SERVER['HTTP_USER_AGENT'];  
    if(strpos($agent,"NetFront") || strpos($agent,"iPhone") || strpos($agent,"MIDP-2.0") || strpos($agent,"Opera Mini") || strpos($agent,"UCWEB") || strpos($agent,"Android") || strpos($agent,"Windows CE") || strpos($agent,"SymbianOS")){
    return true;
    }
    return false;*/
}

?>