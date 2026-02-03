<?php
include '../data/comm.inc.php';
include '../data/mobivar.php';
include '../func/func.php';
include '../include.php';
if ($_SESSION['uuid'] != '' && $_SESSION['ucheck'] == md5($config['allpass'] . $_SESSION['uuid'])) {
    header("Location:/creditmobile/home");
    exit;
}
switch ($_REQUEST['xtype']) {
    case "login":
        include '../global/client.php';
        include "../global/Iplocation_Class.php";
        $sv = rserver();
        $_SESSION['sv'] = $sv;
        $os = getbrowser($_SERVER['HTTP_USER_AGENT']) . '  ' . getos($_SERVER['HTTP_USER_AGENT']);
        $user = strtoupper($_POST['username']);
        $pass = md5($_POST['pass'] . $config['upass']);
        $code = $_POST['code'];
        $app = $_POST['app'];
 
        if($app=='app'){
            $_SESSION['app'] = 1;
        }
        if ($code != $_SESSION['login_check_number'] && $app != "app") {
            echo outjs("验证码错误，请重新输入。");
            echo openurl('/creditmobile/login');
            exit;
        }
        if (!preg_match("/^[a-zA-Z0-9]{1}([a-zA-Z0-9]|[._]){1,10}\$/", $user) | !preg_match("/^[a-z\\d_]{16,64}\$/", $pass)) {
            if ($app == "app") {
                header("Location:http://138t.co/app?err=账号或密码错误");
            } else {
                echo outjs("账号或密码错误。");
                echo openurl('/creditmobile/login');
            }
            exit;
        }
        // 安全修复：使用 prepared statement 防止 SQL 注入 (2026-02-03)
        $stmt = $msql->mysqli->prepare("SELECT errortimes FROM `{$tb_user}` WHERE username = ?");
        $stmt->bind_param("s", $user);
        $stmt->execute();
        $result = $stmt->get_result();
        $errorData = $result->fetch_assoc();
        $stmt->close();

        if ($errorData && $errorData['errortimes'] >= 5) {
            if ($app == "app") {
                header("Location:http://138t.co/app?err=您的密码错误次数超过5次,请联系上级修改密码!");
            } else {
                echo outjs("您的密码错误次数超过5次,请联系上级修改密码!");
                echo openurl('/creditmobile/login');
            }
            exit;
        }

        // 安全修复：使用 prepared statement
        $stmt = $msql->mysqli->prepare("SELECT * FROM `{$tb_user}` WHERE username = ? AND userpass = ? AND ifagent = '0' AND ifson = '0'");
        $stmt->bind_param("ss", $user, $pass);
        $stmt->execute();
        $result = $stmt->get_result();
        $userData = $result->fetch_assoc();
        $stmt->close();

        $ip = getip();
        $time = time();
        if (!$userData || $userData['username'] != $user || $userData['userpass'] != $pass) {
            // 安全修复：使用 prepared statement
            $stmt = $msql->mysqli->prepare("INSERT INTO `{$tb_user_login}` (server, xtype, ip, time, ifok, username, userpass, os) VALUES (?, 2, ?, NOW(), '0', ?, ?, ?)");
            $stmt->bind_param("sssss", $sv, $ip, $user, $pass, $os);
            $stmt->execute();
            $stmt->close();

            $stmt = $msql->mysqli->prepare("UPDATE `{$tb_user}` SET errortimes = errortimes + 1 WHERE username = ?");
            $stmt->bind_param("s", $user);
            $stmt->execute();
            $stmt->close();
            if ($app == "app") {
                header("Location:http://138t.co/app?err=账号或密码错误");
            } else {
                echo outjs("账号或密码错误。");
                echo openurl('/creditmobile/login');
            }
            exit;
        }
        unset($_SESSION['login_check_number']);
        if ($userData['status'] == 0) {
            if ($app == "app") {
                header("Location:http://138t.co/app?err={$userdeny}");
            } else {
                echo outjs($userdeny);
                echo openurl('/creditmobile/login');
            }
            exit;
        }
        $wid = $userData['wid'];
        $err = true;
        if ($wid != $_SESSION['wid']) {
            $err = false;
        }
        if (!$err) {
            //echo outjs("用户名不正确!");
            //echo openurl('/creditmobile/login');
            //exit;
        }
        if($ipa['i'.$userData['userid']]!=""){
            $ip = $ipa['i'.$userData['userid']];
        }
        $_SESSION['gid'] = $userData['gid'];

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

        $stmt = $fsql->mysqli->prepare("INSERT INTO `{$tb_online}` (page, passcode, xtype, userid, logintime, savetime, ip, server, wid, layer, os) VALUES ('xy', ?, '2', ?, NOW(), NOW(), ?, '2', ?, ?, ?)");
        $stmt->bind_param("sissss", $passcode, $userData['userid'], $ip, $wid, $userData['layer'], $os);
        $stmt->execute();
        $stmt->close();
        $_SESSION['upasscode'] = $passcode;
        $_SESSION['uuid'] = $userData['userid'];
        $_SESSION['ucheck'] = md5($config['allpass'] . $userData['userid']);
        $_SESSION['sv'] = $sv;
        $_SESSION['ip'] = $ip;
        if (($time - strtotime($userData['passtime'])) / (60 * 60 * 24) >= $config['passtime']) {
            //echo outjs("您初次登录,需在电脑端更改密码才能登录!");
            header("Location:/creditmobile/password");
            exit;
        }
        //echo openurl("./make.php?xtype=show");
        echo openurl("/creditmobile/home");
        break;
    default:
?>
<!DOCTYPE html>
<html lang="zh-Hans">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo $config['webname']; ?> - 登录</title>
    <script src="/js/jquery-1.8.3.js"></script>
    <script src="/js/md5.js"></script>
    <style>
        body { margin: 0; padding: 0; background: #000; color: #fff; font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif; overflow: hidden; }
        .tech-bg { position: fixed; top: 0; left: 0; width: 100%; height: 100%; z-index: -1; background: radial-gradient(circle at center, #1a2a6c, #b21f1f, #fdbb2d); background: linear-gradient(to bottom, #0f0c29, #302b63, #24243e); }
        .login-container {
            position: absolute; top: 50%; left: 50%; transform: translate(-50%, -50%);
            width: 90%; max-width: 400px; padding: 40px;
            background: rgba(255, 255, 255, 0.05);
            backdrop-filter: blur(10px); border-radius: 20px;
            border: 1px solid rgba(255, 255, 255, 0.1);
            box-shadow: 0 0 20px rgba(0, 255, 255, 0.2);
            text-align: center;
        }
        .logo { margin-bottom: 30px; font-size: 24px; font-weight: bold; color: #00ffff; text-shadow: 0 0 10px #00ffff; }
        .input-group { position: relative; margin-bottom: 20px; }
        .input-group input {
            width: 100%; padding: 15px; background: rgba(0, 0, 0, 0.3); border: 1px solid #333;
            border-radius: 5px; color: #fff; font-size: 16px; outline: none; transition: all 0.3s;
            box-sizing: border-box;
        }
        .input-group input:focus { border-color: #00ffff; box-shadow: 0 0 10px rgba(0, 255, 255, 0.3); }
        .input-group label {
            position: absolute; left: 15px; top: 15px; color: #aaa; pointer-events: none; transition: all 0.3s;
            text-transform: uppercase; font-size: 12px; letter-spacing: 1px;
        }
        .input-group input:focus ~ label, .input-group input:not(:placeholder-shown) ~ label {
            top: -10px; left: 10px; font-size: 10px; color: #00ffff; background: #000; padding: 0 5px;
        }
        .btn-login {
            width: 100%; padding: 15px; background: linear-gradient(45deg, #00ffff, #0099ff);
            border: none; border-radius: 5px; color: #000; font-size: 18px; font-weight: bold;
            cursor: pointer; transition: all 0.3s; text-transform: uppercase; letter-spacing: 2px;
        }
        .btn-login:hover { transform: scale(1.05); box-shadow: 0 0 20px rgba(0, 255, 255, 0.6); }
        .verify-code { position: absolute; right: 5px; top: 5px; height: 40px; border-radius: 3px; cursor: pointer; }
        .footer-link { margin-top: 20px; font-size: 12px; color: #888; }
        .footer-link a { color: #aaa; text-decoration: none; }
        .footer-link a:hover { color: #fff; }
    </style>
</head>
<body>
    <div class="tech-bg"></div>
    <div class="login-container">
        <div class="logo">SYSTEM ACCESS</div>
        <form id="form" name="form" method="post" onsubmit="return checkform();">
            <input type="hidden" name="xtype" value="login">
            <input type="hidden" name="pass" id="pass" value="">

            <div class="input-group">
                <input type="text" id="username" name="username" placeholder=" " autocomplete="off">
                <label>Username</label>
            </div>
            <div class="input-group">
                <input type="password" id="password" name="password" placeholder=" " autocomplete="off">
                <label>Password</label>
            </div>
            <div class="input-group">
                <input type="text" id="code" name="code" placeholder=" " autocomplete="off">
                <label>Verify Code</label>
                <img src="../imgcode.php?act=init" class="verify-code" id="imgcode" onclick="changeimg()">
            </div>
            <button type="submit" class="btn-login">Login</button>
        </form>
        <div class="footer-link">
            <a href="../uxj/">Desktop Version</a>
        </div>
    </div>

    <script>
        $(document).ready(function() {
            $("#username").focus();
            $("#password").blur(function() {
                $("#pass").val(MD5($("#password").val()));
            });
        });

        function changeimg() {
            $("#imgcode").attr("src", "../imgcode.php?act=init&" + Math.random());
        }

        function checkform() {
            if ($("#username").val() == '') {
                alert("请输入账号");
                $("#username").focus();
                return false;
            } else if ($("#password").val() == '') {
                alert("请输入密码");
                $("#password").focus();
                return false;
            } else if ($("#code").val() == '') {
                alert("请输入验证码");
                $("#code").focus();
                return false;
            }
             $("#pass").val(MD5($("#password").val()));
            return true;
        }
    </script>
</body>
</html>
<?php
        break;
}