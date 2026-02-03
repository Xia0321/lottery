<?php

ini_set("display_errors", "On"); 
error_reporting(E_ALL | E_STRICT);
//echo phpinfo();die;
include("./data/config.inc.php");
include("./data/db.php");
include("./global/db.inc.php");
include("./global/session.class.php");

$po = $_SERVER['SERVER_PORT'];

// 特殊端口01跳转
if(substr($po,-2)=='01'){
    header("Location:/login");
    exit;
}

// 移动设备检测
if (ismobi()) {
    header("Location:/mxj");  // 手机访问跳转
} else {
    header("Location:/uxj");  // 电脑访问跳转
}
exit;
/**
 * 检测是否为移动设备
 */
function ismobi()
{
    // 如果有HTTP_X_WAP_PROFILE则一定是移动设备
    if (isset($_SERVER['HTTP_X_WAP_PROFILE'])) {
        return true;
    }
    // 如果via信息含有wap则一定是移动设备,部分服务商会屏蔽该信息
    if (isset($_SERVER['HTTP_VIA'])) {
        return stristr($_SERVER['HTTP_VIA'], "wap") ? true : false;
    }
    // 通过用户代理判断移动设备
    if (isset($_SERVER['HTTP_USER_AGENT'])) {
        $clientkeywords = array(
            'nokia','sony','ericsson','mot','samsung','htc','sgh','lg',
            'sharp','sie-','philips','panasonic','alcatel','lenovo',
            'iphone','ipod','blackberry','meizu','android','netfront',
            'symbian','ucweb','windowsce','palm','operamini','operamobi',
            'openwave','nexusone','cldc','midp','wap','mobile'
        );
        // 从HTTP_USER_AGENT中查找手机浏览器的关键字
        if (preg_match("/(" . implode('|', $clientkeywords) . ")/i", strtolower($_SERVER['HTTP_USER_AGENT']))) {
            return true;
        }
    }
    // 通过HTTP_ACCEPT协议判断
    if (isset($_SERVER['HTTP_ACCEPT'])) {
        if ((strpos($_SERVER['HTTP_ACCEPT'], 'vnd.wap.wml') !== false) && 
            (strpos($_SERVER['HTTP_ACCEPT'], 'text/html') === false || 
             (strpos($_SERVER['HTTP_ACCEPT'], 'vnd.wap.wml') < strpos($_SERVER['HTTP_ACCEPT'], 'text/html')))) {
            return true;
        }
    }
    return false;
}