<?php
/**
 * API 数据库连接配置
 *
 * 使用 PDO 连接数据库
 */

// 加载主配置文件（复用现有配置）
require_once __DIR__ . '/../data/config.inc.php';

// 创建 PDO 连接
try {
    $dsn = "mysql:host={$dbHost};dbname={$dbName};charset=utf8mb4";
    $options = [
        PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
        PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
        PDO::ATTR_EMULATE_PREPARES => false,
    ];

    $pdo = new PDO($dsn, $dbUser, $dbPass, $options);

} catch (PDOException $e) {
    error_log("Database connection failed: " . $e->getMessage());

    // 返回友好错误
    http_response_code(503);
    header('Content-Type: application/json; charset=utf-8');
    echo json_encode([
        'success' => false,
        'error' => [
            'code' => 'SYS_DATABASE_ERROR',
            'message' => '数据库连接失败，请稍后重试'
        ]
    ], JSON_UNESCAPED_UNICODE);
    exit;
}

// 创建 Redis 连接
try {
    $redis = new Redis();
    $redis->connect('127.0.0.1', 6379);

    // 如果 Redis 有密码，取消注释下面这行
    // $redis->auth('your_redis_password');

} catch (Exception $e) {
    error_log("Redis connection failed: " . $e->getMessage());

    // Redis 连接失败不阻止 API（速率限制功能会降级）
    $redis = null;
}

// 加载核心类
require_once __DIR__ . '/../class/ErrorCode.php';
require_once __DIR__ . '/../class/ApiResponse.php';
require_once __DIR__ . '/../class/ApiAuth.php';
require_once __DIR__ . '/../class/WebhookManager.php';
