<?php
/**
 * Prometheus 监控指标端点
 *
 * GET /api/v1/metrics
 *
 * 输出 Prometheus 文本格式的指标数据
 * 配合 Prometheus + Grafana 实现专业监控
 *
 * Prometheus 配置示例:
 *   scrape_configs:
 *     - job_name: 'lottery-api'
 *       metrics_path: '/api/v1/metrics'
 *       static_configs:
 *         - targets: ['your-domain.com']
 *       params:
 *         metrics_key: ['your_metrics_secret_key']
 *
 * Grafana 面板导入后可查看:
 *   - API 请求量 / 错误率
 *   - 响应时间分布
 *   - 活跃用户数
 *   - 投注量 / 营收
 *   - Webhook 队列状态
 *   - Redis / 数据库状态
 */

// 简单密钥认证（防止指标泄露）
$metricsKey = isset($_GET['metrics_key']) ? $_GET['metrics_key'] : '';
$expectedKey = defined('METRICS_KEY') ? METRICS_KEY : 'change_me_in_production';

if (empty($metricsKey) || $metricsKey !== $expectedKey) {
    http_response_code(403);
    exit('Forbidden');
}

// 加载数据库
require_once(__DIR__ . '/../../data/config.inc.php');
require_once(__DIR__ . '/../../data/db.php');
require_once(__DIR__ . '/../../global/db.inc.php');

global $msql;
$mysqli = $msql->get_mysqli();

header('Content-Type: text/plain; version=0.0.4; charset=utf-8');

$output = '';

// ============================================
// API 请求指标
// ============================================

// 过去 5 分钟的请求统计
$result = $mysqli->query("
    SELECT
        COUNT(*) as total_requests,
        SUM(response_code >= 200 AND response_code < 400) as success_count,
        SUM(response_code >= 400 AND response_code < 500) as client_error_count,
        SUM(response_code >= 500) as server_error_count,
        AVG(execution_time) as avg_response_time,
        MAX(execution_time) as max_response_time
    FROM api_logs
    WHERE created_at >= DATE_SUB(NOW(), INTERVAL 5 MINUTE)
");

if ($result) {
    $row = $result->fetch_assoc();
    $output .= "# HELP lottery_api_requests_total Total API requests in last 5 minutes\n";
    $output .= "# TYPE lottery_api_requests_total gauge\n";
    $output .= "lottery_api_requests_total " . (int)$row['total_requests'] . "\n\n";

    $output .= "# HELP lottery_api_requests_success Successful API requests in last 5 minutes\n";
    $output .= "# TYPE lottery_api_requests_success gauge\n";
    $output .= "lottery_api_requests_success " . (int)$row['success_count'] . "\n\n";

    $output .= "# HELP lottery_api_requests_client_error Client error requests (4xx) in last 5 minutes\n";
    $output .= "# TYPE lottery_api_requests_client_error gauge\n";
    $output .= "lottery_api_requests_client_error " . (int)$row['client_error_count'] . "\n\n";

    $output .= "# HELP lottery_api_requests_server_error Server error requests (5xx) in last 5 minutes\n";
    $output .= "# TYPE lottery_api_requests_server_error gauge\n";
    $output .= "lottery_api_requests_server_error " . (int)$row['server_error_count'] . "\n\n";

    $output .= "# HELP lottery_api_response_time_avg_ms Average response time in ms (last 5 min)\n";
    $output .= "# TYPE lottery_api_response_time_avg_ms gauge\n";
    $output .= "lottery_api_response_time_avg_ms " . round((float)$row['avg_response_time'], 2) . "\n\n";

    $output .= "# HELP lottery_api_response_time_max_ms Max response time in ms (last 5 min)\n";
    $output .= "# TYPE lottery_api_response_time_max_ms gauge\n";
    $output .= "lottery_api_response_time_max_ms " . (int)$row['max_response_time'] . "\n\n";
}

// 按端点统计
$result = $mysqli->query("
    SELECT endpoint, COUNT(*) as cnt, AVG(execution_time) as avg_time
    FROM api_logs
    WHERE created_at >= DATE_SUB(NOW(), INTERVAL 5 MINUTE)
    GROUP BY endpoint
    ORDER BY cnt DESC
    LIMIT 20
");

if ($result && $result->num_rows > 0) {
    $output .= "# HELP lottery_api_endpoint_requests Requests per endpoint (last 5 min)\n";
    $output .= "# TYPE lottery_api_endpoint_requests gauge\n";
    while ($row = $result->fetch_assoc()) {
        $endpoint = str_replace('"', '\\"', $row['endpoint']);
        $output .= "lottery_api_endpoint_requests{endpoint=\"$endpoint\"} " . (int)$row['cnt'] . "\n";
    }
    $output .= "\n";
}

// ============================================
// API Key 指标
// ============================================

$result = $mysqli->query("
    SELECT
        COUNT(*) as total_keys,
        SUM(status = 1) as active_keys,
        SUM(status = 0) as disabled_keys
    FROM api_keys
");

if ($result) {
    $row = $result->fetch_assoc();
    $output .= "# HELP lottery_api_keys_total Total API keys\n";
    $output .= "# TYPE lottery_api_keys_total gauge\n";
    $output .= "lottery_api_keys_total " . (int)$row['total_keys'] . "\n\n";

    $output .= "# HELP lottery_api_keys_active Active API keys\n";
    $output .= "# TYPE lottery_api_keys_active gauge\n";
    $output .= "lottery_api_keys_active " . (int)$row['active_keys'] . "\n\n";
}

// ============================================
// Webhook 指标
// ============================================

$result = $mysqli->query("
    SELECT
        COUNT(*) as total_webhooks,
        SUM(status = 1) as active_webhooks
    FROM webhooks
");

if ($result) {
    $row = $result->fetch_assoc();
    $output .= "# HELP lottery_webhooks_total Total webhook subscriptions\n";
    $output .= "# TYPE lottery_webhooks_total gauge\n";
    $output .= "lottery_webhooks_total " . (int)$row['total_webhooks'] . "\n\n";

    $output .= "# HELP lottery_webhooks_active Active webhook subscriptions\n";
    $output .= "# TYPE lottery_webhooks_active gauge\n";
    $output .= "lottery_webhooks_active " . (int)$row['active_webhooks'] . "\n\n";
}

// Webhook 队列指标
$result = $mysqli->query("
    SELECT
        COUNT(*) as total,
        SUM(status = 0) as pending,
        SUM(status = 1) as processing,
        SUM(status = 2) as completed,
        SUM(status = 3) as failed
    FROM webhook_queue
");

if ($result) {
    $row = $result->fetch_assoc();
    $output .= "# HELP lottery_webhook_queue_total Total items in webhook queue\n";
    $output .= "# TYPE lottery_webhook_queue_total gauge\n";
    $output .= "lottery_webhook_queue_total " . (int)$row['total'] . "\n\n";

    $output .= "# HELP lottery_webhook_queue_pending Pending items in webhook queue\n";
    $output .= "# TYPE lottery_webhook_queue_pending gauge\n";
    $output .= "lottery_webhook_queue_pending " . (int)$row['pending'] . "\n\n";

    $output .= "# HELP lottery_webhook_queue_processing Processing items in webhook queue\n";
    $output .= "# TYPE lottery_webhook_queue_processing gauge\n";
    $output .= "lottery_webhook_queue_processing " . (int)$row['processing'] . "\n\n";

    $output .= "# HELP lottery_webhook_queue_failed Failed items in webhook queue\n";
    $output .= "# TYPE lottery_webhook_queue_failed gauge\n";
    $output .= "lottery_webhook_queue_failed " . (int)$row['failed'] . "\n\n";
}

// 最近 1 小时 Webhook 发送统计
$result = $mysqli->query("
    SELECT
        COUNT(*) as total,
        SUM(status = 'success') as success,
        SUM(status = 'failed') as failed,
        AVG(execution_time) as avg_time
    FROM webhook_logs
    WHERE created_at >= DATE_SUB(NOW(), INTERVAL 1 HOUR)
");

if ($result) {
    $row = $result->fetch_assoc();
    $output .= "# HELP lottery_webhook_sends_1h Webhook sends in last hour\n";
    $output .= "# TYPE lottery_webhook_sends_1h gauge\n";
    $output .= "lottery_webhook_sends_1h " . (int)$row['total'] . "\n\n";

    $output .= "# HELP lottery_webhook_sends_success_1h Successful webhook sends in last hour\n";
    $output .= "# TYPE lottery_webhook_sends_success_1h gauge\n";
    $output .= "lottery_webhook_sends_success_1h " . (int)$row['success'] . "\n\n";

    $output .= "# HELP lottery_webhook_sends_failed_1h Failed webhook sends in last hour\n";
    $output .= "# TYPE lottery_webhook_sends_failed_1h gauge\n";
    $output .= "lottery_webhook_sends_failed_1h " . (int)$row['failed'] . "\n\n";
}

// ============================================
// 业务指标
// ============================================

global $tb_user, $tb_bet, $tb_money;

// 用户统计（表名来自系统全局变量，非用户输入）
$sql = sprintf("SELECT COUNT(*) as cnt FROM `%s`", $tb_user);
$result = $mysqli->query($sql);
if ($result) {
    $row = $result->fetch_assoc();
    $output .= "# HELP lottery_users_total Total registered users\n";
    $output .= "# TYPE lottery_users_total gauge\n";
    $output .= "lottery_users_total " . (int)$row['cnt'] . "\n\n";
}

// 今日投注
$sql = sprintf("SELECT COUNT(*) as cnt, COALESCE(SUM(money), 0) as amount FROM `%s` WHERE created_at >= CURDATE()", $tb_bet);
$result = $mysqli->query($sql);
if ($result) {
    $row = $result->fetch_assoc();
    $output .= "# HELP lottery_bets_today_count Today's bet count\n";
    $output .= "# TYPE lottery_bets_today_count gauge\n";
    $output .= "lottery_bets_today_count " . (int)$row['cnt'] . "\n\n";

    $output .= "# HELP lottery_bets_today_amount Today's bet amount\n";
    $output .= "# TYPE lottery_bets_today_amount gauge\n";
    $output .= "lottery_bets_today_amount " . (float)$row['amount'] . "\n\n";
}

// 今日充值/提现
$sql = sprintf("SELECT COALESCE(SUM(CASE WHEN mtype=1 THEN money ELSE 0 END), 0) as deposit, COALESCE(SUM(CASE WHEN mtype=2 THEN money ELSE 0 END), 0) as withdrawal FROM `%s` WHERE status = 1 AND tjtime >= CURDATE()", $tb_money);
$result = $mysqli->query($sql);
if ($result) {
    $row = $result->fetch_assoc();
    $output .= "# HELP lottery_deposit_today Today's deposit amount\n";
    $output .= "# TYPE lottery_deposit_today gauge\n";
    $output .= "lottery_deposit_today " . (float)$row['deposit'] . "\n\n";

    $output .= "# HELP lottery_withdrawal_today Today's withdrawal amount\n";
    $output .= "# TYPE lottery_withdrawal_today gauge\n";
    $output .= "lottery_withdrawal_today " . (float)$row['withdrawal'] . "\n\n";
}

// ============================================
// 系统指标
// ============================================

// Redis 状态
$redisUp = 0;
if (extension_loaded('redis')) {
    try {
        $redis = new Redis();
        $redis->connect('127.0.0.1', 6379, 2);
        $redis->ping();
        $redisUp = 1;
        $redis->close();
    } catch (Exception $e) {
        $redisUp = 0;
    }
}
$output .= "# HELP lottery_redis_up Redis connection status (1=up, 0=down)\n";
$output .= "# TYPE lottery_redis_up gauge\n";
$output .= "lottery_redis_up $redisUp\n\n";

// MySQL 状态
$output .= "# HELP lottery_mysql_up MySQL connection status (1=up, 0=down)\n";
$output .= "# TYPE lottery_mysql_up gauge\n";
$output .= "lottery_mysql_up 1\n\n";

// PHP 内存使用
$memUsage = memory_get_usage(true);
$output .= "# HELP lottery_php_memory_bytes PHP memory usage\n";
$output .= "# TYPE lottery_php_memory_bytes gauge\n";
$output .= "lottery_php_memory_bytes $memUsage\n\n";

echo $output;
