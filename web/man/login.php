<?php
include "../data/config.inc.php";
include "../data/db.php";
include "../global/db.inc.php";
include "../global/session.class.php";
include "./config.php";
include "../data/pan.inc.php";
include '../data/uservar.php';
include '../func/func.php';
$user = strtoupper($_POST["account"]);
$pass = $_POST["password"];
$code = $_POST["code"];
$type = $_POST["type"];
$pass = md5(md5($pass) . $config['upass']);
if (preg_match("/^[a-zA-Z0-9]{1}([a-zA-Z0-9]|[._]){1,10}\$/", $user) && preg_match("/^[a-z\\d_]{16,64}\$/", $pass)) {
    error_reporting(E_ALL);
    include "../global/client.php";

    // 安全修复：使用 prepared statement 防止 SQL 注入 (2026-02-03)
    $stmt = $msql->mysqli->prepare("SELECT * FROM `{$tb_user}` WHERE username = ? AND userpass = ? AND ifagent = 0 AND ifson = 0");
    $stmt->bind_param("ss", $user, $pass);
    $stmt->execute();
    $result = $stmt->get_result();
    $userData = $result->fetch_assoc();
    $stmt->close();

    $ip = getip();
    $time = time();
    $sv = rserver();

    $_SESSION['sv'] = $sv;
    $os = getbrowser($_SERVER['HTTP_USER_AGENT']) . '  ' . getos($_SERVER['HTTP_USER_AGENT']);
    if (!$userData || $userData['username'] != $user || $userData['userpass'] != $pass) {
        // 安全修复：使用 prepared statement
        $stmt = $msql->mysqli->prepare("INSERT INTO `{$tb_user_login}` (server, xtype, ip, time, ifok, username, userpass, os) VALUES (?, 1, ?, NOW(), '0', ?, ?, ?)");
        $stmt->bind_param("sssss", $sv, $ip, $user, $pass, $os);
        $stmt->execute();
        $stmt->close();

        $stmt = $msql->mysqli->prepare("UPDATE `{$tb_user}` SET errortimes = errortimes + 1 WHERE username = ?");
        $stmt->bind_param("s", $user);
        $stmt->execute();
        $stmt->close();
        echo file_get_contents("./html/login_err4.html");
        exit;
    }
    if ($userData['status'] == 0) {
        echo file_get_contents("./html/login_err5.html");
        exit;
    }

    // 安全修复：使用 prepared statement (2026-02-03)
    $stmt = $fsql->mysqli->prepare("INSERT INTO `{$tb_user_login}` (xtype, ip, time, ifok, username, userpass, server, os) VALUES ('2', ?, NOW(), '1', ?, 'OK', ?, ?)");
    $stmt->bind_param("ssss", $ip, $user, $sv, $os);
    $stmt->execute();
    $stmt->close();

    $stmt = $fsql->mysqli->prepare("UPDATE `{$tb_user}` SET errortimes = 0, logintimes = logintimes + 1, lastloginip = ?, lastlogintime = NOW(), online = 1 WHERE username = ?");
    $stmt->bind_param("ss", $ip, $user);
    $stmt->execute();
    $stmt->close();

    $passcode = bin2hex(random_bytes(32)); // 安全修复：使用加密安全的随机数

    $stmt = $fsql->mysqli->prepare("DELETE FROM `{$tb_online}` WHERE xtype = 2 AND userid = ?");
    $stmt->bind_param("i", $userData['userid']);
    $stmt->execute();
    $stmt->close();

    $stmt = $fsql->mysqli->prepare("INSERT INTO `{$tb_online}` (page, passcode, xtype, userid, logintime, savetime, ip, server, wid, layer, os) VALUES ('make', ?, '2', ?, NOW(), NOW(), ?, '8', ?, ?, ?)");
    $stmt->bind_param("sissss", $passcode, $userData['userid'], $ip, $wid, $userData['layer'], $os);
    $stmt->execute();
    $stmt->close();

    $_SESSION['upasscode'] = $passcode;
    $_SESSION['uuid'] = $userData['userid'];
    $_SESSION['ucheck'] = md5($config['allpass'] . $userData['userid']);
    $_SESSION['sv'] = $sv;
    $_SESSION['ip'] = $ip;
    $_SESSION['username'] = trim($_POST['account']);
    setcookie("ucheck", md5($config['allpass'] . $userData['userid']) . $userData['userid']);
    header("Location:/member/agreement?_OLID_=4f8c06822114da8a4f0b484e0d17c02ce4fd5d43");
}