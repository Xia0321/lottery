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
    $sql = "SELECT * FROM `{$tb_user}` WHERE username='{$user}' and userpass='{$pass}' and ifagent=0 and ifson=0 ";
    $msql->query($sql);
    $msql->next_record();
    $ip = getip();
    $time = time();
    $sv = rserver();

    $_SESSION['sv'] = $sv;
    $os = getbrowser($_SERVER['HTTP_USER_AGENT']) . '  ' . getos($_SERVER['HTTP_USER_AGENT']);
    if ($msql->f('username') != $user || $msql->f('userpass') != $pass) {
        $msql->query("insert into `{$tb_user_login}` set server='{$sv}',xtype=1,ip='{$ip}',time=NOW(),ifok='0',username='{$user}',userpass='{$pass}',os='{$os}'");
        $msql->query("update `{$tb_user}` set errortimes=errortimes+1 where username='{$user}'");
        echo file_get_contents("./html/login_err4.html");
        exit;
    }
    if ($msql->f('status') == 0) {
        echo file_get_contents("./html/login_err5.html");
        exit;
    }
    $fsql->query("insert into `{$tb_user_login}` set xtype='2',ip='{$ip}',time=NOW(),ifok='1',username='{$user}',userpass='OK',server='{$sv}',os='{$os}'");
    $fsql->query("update `{$tb_user}` set errortimes=0,logintimes=logintimes+1,lastloginip='{$ip}',lastlogintime=NOW(),online=1 where username='{$user}'");
    $passcode = getmicrotime() * 100000000 . $time;
    $fsql->query("delete from `{$tb_online}` where xtype=2 and userid='" . $msql->f('userid') . "'");
    $fsql->query("insert into `{$tb_online}` set page='make',passcode='{$passcode}',xtype='2',userid='" . $msql->f('userid') . "',logintime=NOW(),savetime=NOW(),ip='{$ip}',server='8',wid='{$wid}',layer='" . $msql->f('layer') . "',os='{$os}'");
    $_SESSION['upasscode'] = $passcode;
    $_SESSION['uuid'] = $msql->f('userid');
    $_SESSION['ucheck'] = md5($config['allpass'] . $msql->f('userid'));
    $_SESSION['sv'] = $sv;
    $_SESSION['ip'] = $ip;
    $_SESSION['username'] = trim($_POST['account']);
    setcookie("ucheck",md5($config['allpass'] . $msql->f('userid')).$msql->f('userid'));
    header("Location:/member/agreement?_OLID_=4f8c06822114da8a4f0b484e0d17c02ce4fd5d43");
}