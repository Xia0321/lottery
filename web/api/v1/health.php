<?php
/**
 * 健康检查接口（无需认证）
 */

header('Content-Type: application/json; charset=utf-8');

// 检查数据库连接
try {
    require_once(__DIR__ . '/../../data/config.inc.php');
    require_once(__DIR__ . '/../../data/db.php');
    require_once(__DIR__ . '/../../global/db.inc.php');

    global $msql;
    $conn = $msql->get_mysqli();

    $dbStatus = 'connected';
    if (!$conn || $conn->connect_error) {
        $dbStatus = 'disconnected';
    } else {
        // 测试查询
        $result = $conn->query("SELECT 1");
        if (!$result) {
            $dbStatus = 'error';
        }
    }
} catch (Exception $e) {
    $dbStatus = 'error: ' . $e->getMessage();
}

// 返回健康状态
$response = [
    'status' => 'ok',
    'message' => 'API is running',
    'timestamp' => time(),
    'datetime' => date('Y-m-d H:i:s'),
    'php_version' => PHP_VERSION,
    'database' => $dbStatus,
    'server' => $_SERVER['SERVER_SOFTWARE'] ?? 'Unknown'
];

echo json_encode($response, JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT);
