<?php
//file_put_contents("./temp_dc/11.txt", $_GET['t'].date("H:i:s")."\r\n",FILE_APPEND);
//rewrite ^/(agent/index|agent/notice|agent/user/list|agent/report/bets|login|code)$  /man/man.php?t=$1;
switch ($_GET['t']) {
    case "agent/index":
        require("./checkagent.php");
        $tpl->assign("webname",$config['webname']);
        $tpl->assign("username",$_SESSION['username']);
        
        $tpl->display("agent_index.html");
    break;
    case "agent/notice":
        echo '<span>公告：因本系统涉及现金结算，若凌晨6：30分前官网没公布开奖结果，将取消没开奖期的注单，如若第二天开奖或更改， 将不再结算！一切按报表交收！谢谢合作！！</span>';
    break;
    case "agent/user/list":
        require("agent_user.php");
    break;
    case "agent/report/bets":

        require("agent_bao.php");
    break;
    case "agent/report/list":
        require("agent_baolist.php");
    break;
    case "login":
        if($_REQUEST['a']==2){
            if($_POST['account'] && $_POST['password'] && $_POST['code']){
                require("./loginagent.php");
            }else{
                echo file_get_contents("./html/loginagent.html");
            }
        }else{
            if($_POST['account'] && $_POST['password'] && $_POST['code']){
                require("./login.php");
            }else{
                echo file_get_contents("./html/login.html"); 
            }
        }
        
        break;
    case "ssid1":
//session_start();
header( "HTTP/1.1 302 found" );
header("Server: nginx/1.14.1");
header('Content-Type: text/html');

setcookie("ssid1","1859b1345e742f213f4a80547b36da30");
        setcookie("random",rand(1111,9999));

        //echo "";
        echo "<html>
<head><title>302 Found</title></head>
<body>
<center><h1>302 Found</h1></center>
<hr><center>openresty</center>
</body>
</html>
";
        
        header("Location:/login");
    break;
    case "time":
       echo msectime();
    break;
    case "member/notice":
       echo "<span>公告：各级管理员,会员请注意，登陆本糸统必须认真阅读游戏规则;由于本公司不是网络商，无权操作开奖结果和结算报表，如出现各种网络问题，本公司凭着公正公平的理念，一切结算按星期一报表交收（如中途交收者或星期一交收后数据变动，一律不接受任何投诉）如有异议者请不要投注。投注者必须服从本公司规则！</span>";
    break;
    case "member/dresult":
        require("./dresult.php");
    break;
    case "member/period":
        require("./period.php");
    break;    
    case "member/index":
        require("./check.php");
        //error_reporting(E_ALL);
        $us = $msql->arr("select * from `$tb_user` where userid='$userid'",1);
        $tpl->assign("us",$us[0]);
        $tpl->assign("username",$_SESSION["username"]);
        $tpl->assign("webname",$config['webname']);
        $tpl->display("member_index.html");
    break;
    case "member/odds":
       require("./odds.php");
    break;
    case "member/lastResult":
       require("./lastresult.php");
    break;
    case "member/agreement":
        require("./check.php");
        $tpl->display("agreement.html");
    break; 
    case "member/info":
        require("./check.php");
        require "./manfunc.php";
        $us = $msql->arr("select * from `$tb_user` where userid='$userid'",1);
        $tpl->assign("us",$us[0]);
        $gid = getgidman($_REQUEST['lottery']);
        $abcd = strtolower($us[0]["defaultpan"]);
        //echo "select $abcd as point,maxje,cmaxje,class from `$tb_points` where userid='$userid' and gid='$gid'";
        $points = $msql->arr("select $abcd as point,maxje,cmaxje,class from `$tb_points` where userid='$userid' and gid='$gid'",1);
        $tpl->assign("points",$points);
        $tpl->display("member_info.html");
    break;    
    case "member/bets":
        require("./bets.php");
    break;  
    case "member/bet":
        //file_put_contents("./temp_dc/88.txt", $_GET['t'].date("H:i:s")."\r\n",FILE_APPEND);
        require("./bet.php");
    break;  
    case "member/accounts":
        require("./accounts.php");
    break;
    case "member/logout":
        require("./check.php");
        sessiondelu();
        header("Location:/login");
    break;
    case "code":
        include '../data/config.inc.php';
        include "../global/session.class.php";
        include '../global/img.class.5.php';
        $n = new imgdata();
        $list = scandir("../code");
        //print_r($list);exit;
        $cl = count($list);
        while (1) {
            $code = $list[rand(2, $cl - 1)];
            $_SESSION['login_check_number'] = substr($code, 0, 4);
            $n->getdir("../code/" . $code);
            $n->img2data();
            if ($n->imgform == "image/jpeg" && is_numeric($_SESSION['login_check_number'])) {
                $n->data2img();
                break;
            }
        }
        break;
}

function msectime()
{
    list($msec, $sec) = explode(' ', microtime());

    return (float)sprintf('%.0f', (floatval($msec) + floatval($sec)) * 1000);
}