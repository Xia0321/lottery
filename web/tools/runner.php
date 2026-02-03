<?php
// runner.php
// 这个脚本是一个包装器，用于在正确的目录和环境中运行遗留的自动化脚本。
// 它解决了 CLI 模式下的路径依赖问题和变量传递问题。

// 1. 切换到当前目录 (tools/)，确保相对路径 include (如 ../data/config.inc.php) 能正确解析
chdir(__DIR__);

// 2. 获取参数
$script = isset($argv[1]) ? $argv[1] : '';
$admin = isset($argv[2]) ? $argv[2] : 'toor';

if (empty($script)) {
    die("Usage: php runner.php <script_name> <admin_code>\n");
}

if (!file_exists($script)) {
    die("Error: Script '$script' not found in " . __DIR__ . "\n");
}

// 3. 模拟 Web 环境的全局变量
// 这些遗留脚本通常依赖 $_REQUEST['admin'] 来进行权限验证
$_REQUEST['admin'] = $admin;
$_GET['admin'] = $admin; // 保险起见
$_SERVER['REMOTE_ADDR'] = '127.0.0.1'; // 绕过 IP 限制 (如果有)

// 4. 包含目标脚本
// 使用 include 而不是 system/exec，这样可以共享当前 PHP 进程的某些配置，
// 但由于 master_auto.php 是通过 system() 调用 runner.php 的，所以每次 runner.php 都是一个新进程，这也是我们想要的（隔离）。
include $script;
?>
