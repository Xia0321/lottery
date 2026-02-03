<?php
include('../data/comm.inc.php');include('../data/myadminvar.php');
include('../func/func.php');
include('../func/agentfunc.php');
include('../include.php');
include('./checklogin.php');
if ($_POST['sf'] != '') {
    // 安全修复：使用白名单验证文件名 (2026-02-03)
    $sf = $_POST['sf'];

    // 只允许字母、数字、下划线
    if (!preg_match('/^[a-zA-Z0-9_]+$/', $sf)) {
        http_response_code(400);
        die('Invalid file name');
    }

    // 构建文件路径
    $filePath = "../js/" . $config['skins'] . '/js' . $config['adi'] . "/" . $sf . $config['adi'] . ".js";

    // 验证文件存在且在允许的目录内
    $realPath = realpath($filePath);
    $baseDir = realpath("../js");

    if (!$realPath || strpos($realPath, $baseDir) !== 0 || !is_file($realPath)) {
        http_response_code(404);
        die('File not found');
    }

    echo file_get_contents($realPath);
    exit;
}
?>