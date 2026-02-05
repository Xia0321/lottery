<?php
/**
 * PHP 内置开发服务器路由文件
 *
 * 启动方式:
 *   php -S localhost:8080 -t web web/router.php
 *
 * 仅用于开发和测试，生产环境使用 Nginx/Apache
 */

$uri = $_SERVER['REQUEST_URI'];
$path = parse_url($uri, PHP_URL_PATH);

// 静态文件直接返回
if (preg_match('/\.(css|js|png|jpg|gif|ico|svg|woff|woff2)$/', $path)) {
    return false;
}

// API v1 路由
$apiRoutes = [
    // 用户
    '#^/api/v1/users/(\d+)/balance#'    => '/api/v1/users.php',
    '#^/api/v1/users/(\d+)#'            => '/api/v1/users.php',

    // 投注
    '#^/api/v1/bets/(\d+)#'             => '/api/v1/bets.php',
    '#^/api/v1/bets#'                   => '/api/v1/bets.php',

    // 交易
    '#^/api/v1/transactions#'           => '/api/v1/transactions.php',

    // 开奖结果
    '#^/api/v1/lottery_results/latest#'  => '/api/v1/lottery_results.php',
    '#^/api/v1/lottery_results/([^/]+)#' => '/api/v1/lottery_results.php',
    '#^/api/v1/lottery_results#'         => '/api/v1/lottery_results.php',

    // 充值提现
    '#^/api/v1/deposits#'               => '/api/v1/deposits.php',
    '#^/api/v1/withdrawals#'            => '/api/v1/withdrawals.php',

    // Webhook
    '#^/api/v1/webhooks/(\d+)#'         => '/api/v1/webhooks.php',
    '#^/api/v1/webhooks#'               => '/api/v1/webhooks.php',

    // 批量查询
    '#^/api/v1/batch/#'                 => '/api/v1/batch.php',

    // 统计
    '#^/api/v1/stats/#'                 => '/api/v1/stats.php',

    // 嵌入 Token
    '#^/api/v1/embed-token#'            => '/api/v1/tokens.php',

    // 监控指标
    '#^/api/v1/metrics#'                => '/api/v1/metrics.php',

    // 健康检查
    '#^/api/v1/health#'                 => '/api/v1/health.php',
];

foreach ($apiRoutes as $pattern => $target) {
    if (preg_match($pattern, $path)) {
        $_SERVER['SCRIPT_NAME'] = $target;
        require __DIR__ . $target;
        return true;
    }
}

// 游戏嵌入
if (preg_match('#^/web/embed/game#', $path)) {
    require __DIR__ . '/embed/game.php';
    return true;
}

// 直接匹配 PHP 文件
$filePath = __DIR__ . $path;
if (is_file($filePath) && pathinfo($filePath, PATHINFO_EXTENSION) === 'php') {
    return false; // 让 PHP 内置服务器处理
}

// 默认 index
if ($path === '/' || $path === '') {
    if (is_file(__DIR__ . '/index.php')) {
        return false;
    }
}

// 404
http_response_code(404);
echo json_encode(['error' => 'Not Found', 'path' => $path]);
return true;
