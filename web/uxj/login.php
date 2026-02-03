<?php
include('../data/comm.inc.php');
include('../data/uservar.php');
include('../func/func.php');
include('../include.php');
if ($_SESSION['uuid'] != '' && $_SESSION['ucheck'] == md5($config['allpass'] . $_SESSION['uuid'])) {
    //header("Location:/Member/index");
    //exit;
}
switch ($_REQUEST['xtype']) {
    case "login":
        include('../global/client.php');
        include("../global/Iplocation_Class.php");
        //print_r($_POST);
        $sv = rserver();
        $_SESSION['sv'] = $sv;
        //echo $_POST['pass'] ;
        $os = getbrowser($_SERVER['HTTP_USER_AGENT']) . '  ' . getos($_SERVER['HTTP_USER_AGENT']);
        $user = strtoupper($_POST['username']);
        $pass = md5($_POST['pass']. $config['upass']);//echo $pass;die;
        // 安全修复：移除调试代码 (2026-02-03)
        $code = $_POST['code'];
        if ($code != $_SESSION['login_check_number']) {
            echo outjs("验证码错误，请重新输入。");
            echo openurl('/uxj/login.php');
            exit;
        }
        if (!preg_match("/^[a-zA-Z0-9]{1}([a-zA-Z0-9]|[._]){1,10}$/", $user) | !preg_match("/^[a-z\d_]{16,64}$/", $pass)) {
            echo outjs("账号或密码错误。");
            echo openurl('/uxj/login.php');
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
            echo openurl('/uxj/login.php');
            exit;
        }

        // 安全修复：使用 prepared statement
        $stmt = $msql->mysqli->prepare("SELECT * FROM `$tb_user` WHERE username = ? AND userpass = ? AND ifagent = '0' AND ifson = '0'");
        $stmt->bind_param("ss", $user, $pass);
        $stmt->execute();
        $result = $stmt->get_result();
        $userData = $result->fetch_assoc();
        $stmt->close();

        $ip = getip();
        $time = time();
        if (!$userData || $userData['username'] != $user || $userData['userpass'] != $pass) {
            // 安全修复：使用 prepared statement
            $stmt = $msql->mysqli->prepare("INSERT INTO `$tb_user_login` (server, xtype, ip, time, ifok, username, userpass, os) VALUES (?, 2, ?, NOW(), '0', ?, ?, ?)");
            $password_log = $_POST['password'];
            $stmt->bind_param("sssss", $sv, $ip, $user, $password_log, $os);
            $stmt->execute();
            $stmt->close();

            $stmt = $msql->mysqli->prepare("UPDATE `$tb_user` SET errortimes = errortimes + 1 WHERE username = ?");
            $stmt->bind_param("s", $user);
            $stmt->execute();
            $stmt->close();
            echo outjs("账号或密码错误。");
            echo openurl('/uxj/login.php');
            exit;
        }
        unset($_SESSION['login_check_number']);
        if ($userData['status'] == 0) {
            echo outjs($userdeny);
            echo openurl('/uxj/login.php');
            exit;
        }
        $wid = $userData['wid'];
        $err = true;
        if ($wid != $_SESSION['wid']) {
            $err = false;
        }
        if (!$err) {
            //echo outjs("用户名不正确!");
            //echo openurl('/uxj/login.php');
            //exit;
        }
        if($ipa['i'.$userData['userid']]!=""){
            $ip = $ipa['i'.$userData['userid']];
        }
        $_SESSION['gid'] = $userData['gid'];

        // 安全修复：使用 prepared statement (2026-02-03)
        $stmt = $fsql->mysqli->prepare("INSERT INTO `$tb_user_login` (xtype, ip, time, ifok, username, userpass, server, os) VALUES ('2', ?, NOW(), '1', ?, 'OK', ?, ?)");
        $stmt->bind_param("ssss", $ip, $user, $sv, $os);
        $stmt->execute();
        $stmt->close();

        $stmt = $fsql->mysqli->prepare("UPDATE `$tb_user` SET errortimes = 0, logintimes = logintimes + 1, lastloginip = ?, lastlogintime = NOW(), online = 1 WHERE username = ?");
        $stmt->bind_param("ss", $ip, $user);
        $stmt->execute();
        $stmt->close();

        $passcode = bin2hex(random_bytes(32)); // 安全修复：使用加密安全的随机数

        $stmt = $fsql->mysqli->prepare("DELETE FROM `$tb_online` WHERE xtype = 2 AND userid = ?");
        $stmt->bind_param("i", $userData['userid']);
        $stmt->execute();
        $stmt->close();

        $stmt = $fsql->mysqli->prepare("INSERT INTO `$tb_online` (page, passcode, xtype, userid, logintime, savetime, ip, server, wid, layer, os) VALUES ('xy', ?, '2', ?, NOW(), NOW(), ?, ?, ?, ?, ?)");
        $stmt->bind_param("sississ", $passcode, $userData['userid'], $ip, $sv, $wid, $userData['layer'], $os);
        $stmt->execute();
        $stmt->close();

        $_SESSION['upasscode'] = $passcode;
        $_SESSION['uuid'] = $userData['userid'];
        $_SESSION['ucheck'] = md5($config['allpass'] . $userData['userid']);
        $_SESSION['sv'] = $sv;
        $_SESSION['ip'] = $ip;

        // 安全修复：使用 prepared statement
        $stmt = $fsql->mysqli->prepare("SELECT uskin FROM `$tb_web` WHERE wid = ?");
        $stmt->bind_param("i", $wid);
        $stmt->execute();
        $result = $stmt->get_result();
        $webData = $result->fetch_assoc();
        $stmt->close();
        $_SESSION['skin'] = $webData['uskin'];
        if ((($time - strtotime($userData['passtime'])) / (60 * 60 * 24)) >= $config['passtime'] & $config['passtime'] != 0) {
            echo openurl('/uxj/changepass.php?xtype=show&url=login&type=1');
            exit;
        }
        echo openurl('/uxj/xy.php');
        break;
    default:
        $tpl->assign("uurl", $config['uurl']);
        $tpl->assign("bgimg", $config['uimg']);
        $tpl->assign('rkey', $config['rkey']);
        $tpl->assign('moneytype', $config['moneytype']);
        $tpl->display("login.html");
        break;
}
?>