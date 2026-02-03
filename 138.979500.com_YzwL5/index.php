<?php
//认准技术人员唯一TG @zh626666 接开奖修复 二开 定制
ini_set("display_errors", "On"); 
error_reporting(E_ALL | E_STRICT);
//echo phpinfo();die;
include("./data/config.inc.php");

include("./data/db.php");

include("./global/db.inc.php");

include("./global/session.class.php");

$po = $_SERVER['SERVER_PORT'];


if(substr($po,-2)=='01'){
    header("Location:/login");
    exit;
}
$url  = $_SERVER['HTTP_HOST'];
$uym0="9247336501-ys.979500.com";
$uym1="9247336501-ys.979500.com";
$uym2="9247336502-ys.1in3mpzu8k.com";
$uym3="9247336503-ys.1in3mpzu8k.com";
$uym4="9247336504-ys.1in3mpzu8k.com";
$uym5="www.7fq0i9.ml";
$uym6="9247336501-ys.7fq0i9.ml";
$uym7="9247336502-ys.7fq0i9.ml";
$uym8="9247336503-ys.7fq0i9.ml";
$uym9="9247336504-ys.7fq0i9.ml";
$aym1="32958576-ys.1in3mpzu8k.com";
$aym2="32958577-ys.1in3mpzu8k.com";
$aym3="32958578-ys.1in3mpzu8k.com";
$aym4="32958579-ys.1in3mpzu8k.com";
$aym6="32958576-ys.7fq0i9.ml";
$aym7="32958577-ys.7fq0i9.ml";
$aym8="32958578-ys.7fq0i9.ml";
$aym9="32958579-ys.7fq0i9.ml";
$aym6d="32958576-ys.7fq0i9.ml:12345";
$aym7d="32958577-ys.7fq0i9.ml:12345";
$aym8d="test2.1c5rsc.ml";
$aym9d="32958579-ys.7fq0i9.ml:12345";
$gym="a.979500.com";
$gym1="a.zh62666.com";
if ($url==$uym6 || $url==$uym7 || $url==$uym8 || $url==$uym9 || $url==$uym5 || $url==$uym0 || $url==$uym1 || $url==$uym2 || $url==$uym3 || $url==$uym4){
if (ismobi()) {
                $_SESSION['mobi'] = 1;
                header("Location:http://".$url."/m");
                exit;
            } else {
             header("Location:http://".$url."/hy");
            }
}else if ($url==$aym6 || $url==$aym7 || $url==$aym8 || $url==$aym9 || $url==$aym1 || $url==$aym2 || $url==$aym3 || $url==$aym4 || $url==$aym6d || $url==$aym7d || $url==$aym8d || $url==$aym9d){
             header("Location:http://".$url."/login");
}else if ($url==$gym || $url==$gym1){
             header("Location:http://".$url."/hide");
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
