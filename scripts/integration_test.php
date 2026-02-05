<?php
/**
 * 生产环境联调测试脚本
 *
 * 在部署到生产环境后运行此脚本，验证所有功能正常
 *
 * 用法:
 *   php scripts/integration_test.php <base_url> <api_key> <api_secret>
 *
 * 示例:
 *   php scripts/integration_test.php https://api.lottery.com lk_abc123 sk_secret456
 */

if (php_sapi_name() !== 'cli') {
    http_response_code(403);
    exit('CLI only');
}

$baseUrl   = isset($argv[1]) ? rtrim($argv[1], '/') : '';
$apiKey    = isset($argv[2]) ? $argv[2] : '';
$apiSecret = isset($argv[3]) ? $argv[3] : '';

if (empty($baseUrl) || empty($apiKey) || empty($apiSecret)) {
    echo "用法: php integration_test.php <base_url> <api_key> <api_secret>\n";
    exit(1);
}

$apiBase = $baseUrl . '/api/v1';
$pass = 0;
$fail = 0;

echo "╔══════════════════════════════════════════╗\n";
echo "║       生产环境联调测试                     ║\n";
echo "╚══════════════════════════════════════════╝\n\n";
echo "目标: $baseUrl\n";
echo "时间: " . date('Y-m-d H:i:s') . "\n\n";

function sign($apiKey, $timestamp, $apiSecret) {
    return hash_hmac('sha256', $apiKey . $timestamp . $apiSecret, $apiSecret);
}

function apiGet($url, $apiKey, $apiSecret) {
    $timestamp = time();
    $sign = sign($apiKey, $timestamp, $apiSecret);
    $separator = (strpos($url, '?') !== false) ? '&' : '?';
    $fullUrl = $url . $separator . http_build_query([
        'api_key' => $apiKey,
        'timestamp' => $timestamp,
        'sign' => $sign
    ]);

    $ch = curl_init($fullUrl);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_TIMEOUT, 15);
    curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
    $response = curl_exec($ch);
    $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
    $error = curl_error($ch);
    curl_close($ch);

    return ['code' => $httpCode, 'body' => $response, 'data' => json_decode($response, true), 'error' => $error];
}

function apiPost($url, $data, $apiKey, $apiSecret) {
    $timestamp = time();
    $sign = sign($apiKey, $timestamp, $apiSecret);
    $fullUrl = $url . '?' . http_build_query([
        'api_key' => $apiKey,
        'timestamp' => $timestamp,
        'sign' => $sign
    ]);

    $ch = curl_init($fullUrl);
    curl_setopt($ch, CURLOPT_POST, true);
    curl_setopt($ch, CURLOPT_HTTPHEADER, ['Content-Type: application/json']);
    curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($data));
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_TIMEOUT, 15);
    curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
    $response = curl_exec($ch);
    $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
    $error = curl_error($ch);
    curl_close($ch);

    return ['code' => $httpCode, 'body' => $response, 'data' => json_decode($response, true), 'error' => $error];
}

function test($name, $result, $expectedCode = 200) {
    global $pass, $fail;
    $ok = ($result['code'] == $expectedCode) && !$result['error'];

    if ($ok) {
        $pass++;
        echo "  PASS: $name (HTTP {$result['code']})\n";
    } else {
        $fail++;
        echo "  FAIL: $name (HTTP {$result['code']}";
        if ($result['error']) echo ", Error: {$result['error']}";
        echo ")\n";
    }
    return $ok;
}

// ============================================
// 1. 基础连通性
// ============================================
echo "═══ 1. 基础连通性 ═══\n\n";

// Health Check
$ch = curl_init($apiBase . '/health.php');
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
curl_setopt($ch, CURLOPT_TIMEOUT, 10);
curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
$resp = curl_exec($ch);
$code = curl_getinfo($ch, CURLINFO_HTTP_CODE);
curl_close($ch);

if ($code == 200) {
    $pass++;
    echo "  PASS: Health Check (HTTP $code)\n";
} else {
    $fail++;
    echo "  FAIL: Health Check (HTTP $code)\n";
}

// HTTPS 检查
if (strpos($baseUrl, 'https://') === 0) {
    $pass++;
    echo "  PASS: HTTPS 已启用\n";
} else {
    $fail++;
    echo "  WARN: 未使用 HTTPS\n";
}

echo "\n";

// ============================================
// 2. 认证和签名
// ============================================
echo "═══ 2. 认证和签名 ═══\n\n";

// 正常认证
$result = apiGet($apiBase . '/lottery_results/latest', $apiKey, $apiSecret);
test('正常签名认证', $result, 200);

// 无认证
$ch = curl_init($apiBase . '/lottery_results/latest');
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
curl_setopt($ch, CURLOPT_TIMEOUT, 10);
curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
$resp = curl_exec($ch);
$code = curl_getinfo($ch, CURLINFO_HTTP_CODE);
curl_close($ch);
if ($code == 401) {
    $pass++;
    echo "  PASS: 无认证拒绝 (HTTP $code)\n";
} else {
    $fail++;
    echo "  FAIL: 无认证应返回 401, 实际 HTTP $code\n";
}

// 错误签名
$timestamp = time();
$fullUrl = $apiBase . '/lottery_results/latest?' . http_build_query([
    'api_key' => $apiKey,
    'timestamp' => $timestamp,
    'sign' => 'invalid_signature_here'
]);
$ch = curl_init($fullUrl);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
curl_setopt($ch, CURLOPT_TIMEOUT, 10);
curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
$resp = curl_exec($ch);
$code = curl_getinfo($ch, CURLINFO_HTTP_CODE);
curl_close($ch);
if ($code == 401) {
    $pass++;
    echo "  PASS: 错误签名拒绝 (HTTP $code)\n";
} else {
    $fail++;
    echo "  FAIL: 错误签名应返回 401, 实际 HTTP $code\n";
}

echo "\n";

// ============================================
// 3. 查询接口
// ============================================
echo "═══ 3. 查询接口 ═══\n\n";

$result = apiGet($apiBase . '/lottery_results/latest', $apiKey, $apiSecret);
test('GET /lottery_results/latest', $result);

$result = apiGet($apiBase . '/lottery_results?page=1&page_size=5', $apiKey, $apiSecret);
test('GET /lottery_results (列表)', $result);

$result = apiGet($apiBase . '/bets?page=1&page_size=5', $apiKey, $apiSecret);
test('GET /bets', $result);

$result = apiGet($apiBase . '/transactions?page=1&page_size=5', $apiKey, $apiSecret);
test('GET /transactions', $result);

$result = apiGet($apiBase . '/deposits?page=1&page_size=5', $apiKey, $apiSecret);
test('GET /deposits', $result);

$result = apiGet($apiBase . '/withdrawals?page=1&page_size=5', $apiKey, $apiSecret);
test('GET /withdrawals', $result);

echo "\n";

// ============================================
// 4. 统计接口
// ============================================
echo "═══ 4. 统计接口 ═══\n\n";

$result = apiGet($apiBase . '/stats/overview', $apiKey, $apiSecret);
test('GET /stats/overview', $result);

$result = apiGet($apiBase . '/stats/bets?group_by=day&days=7', $apiKey, $apiSecret);
test('GET /stats/bets', $result);

$result = apiGet($apiBase . '/stats/revenue?days=7', $apiKey, $apiSecret);
test('GET /stats/revenue', $result);

$result = apiGet($apiBase . '/stats/users?days=7', $apiKey, $apiSecret);
test('GET /stats/users', $result);

echo "\n";

// ============================================
// 5. Webhook CRUD
// ============================================
echo "═══ 5. Webhook 管理 ═══\n\n";

// 创建
$result = apiPost($apiBase . '/webhooks', [
    'event_type' => 'bet.created',
    'callback_url' => 'https://httpbin.org/post',
    'description' => '联调测试 Webhook'
], $apiKey, $apiSecret);
test('POST /webhooks (创建)', $result);
$webhookId = isset($result['data']['data']['id']) ? $result['data']['data']['id'] : null;

// 列表
$result = apiGet($apiBase . '/webhooks', $apiKey, $apiSecret);
test('GET /webhooks (列表)', $result);

// 删除
if ($webhookId) {
    $timestamp = time();
    $sign = sign($apiKey, $timestamp, $apiSecret);
    $delUrl = $apiBase . '/webhooks/' . $webhookId . '?' . http_build_query([
        'api_key' => $apiKey,
        'timestamp' => $timestamp,
        'sign' => $sign
    ]);
    $ch = curl_init($delUrl);
    curl_setopt($ch, CURLOPT_CUSTOMREQUEST, 'DELETE');
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_TIMEOUT, 10);
    curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
    $resp = curl_exec($ch);
    $code = curl_getinfo($ch, CURLINFO_HTTP_CODE);
    curl_close($ch);
    if ($code == 200) {
        $pass++;
        echo "  PASS: DELETE /webhooks/$webhookId\n";
    } else {
        $fail++;
        echo "  FAIL: DELETE /webhooks/$webhookId (HTTP $code)\n";
    }
} else {
    echo "  SKIP: DELETE webhook (无 ID)\n";
}

echo "\n";

// ============================================
// 6. 游戏嵌入
// ============================================
echo "═══ 6. 游戏嵌入 ═══\n\n";

// 嵌入页面无 token 应返回错误
$ch = curl_init($baseUrl . '/web/embed/game.php');
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
curl_setopt($ch, CURLOPT_TIMEOUT, 10);
curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
$resp = curl_exec($ch);
$code = curl_getinfo($ch, CURLINFO_HTTP_CODE);
curl_close($ch);

if ($code == 403) {
    $pass++;
    echo "  PASS: 嵌入页面无 Token 返回 403\n";
} else {
    $fail++;
    echo "  FAIL: 嵌入页面无 Token 应返回 403, 实际 HTTP $code\n";
}

echo "\n";

// ============================================
// 7. 速率限制
// ============================================
echo "═══ 7. 速率限制 ═══\n\n";

$result = apiGet($apiBase . '/lottery_results/latest', $apiKey, $apiSecret);
echo "  INFO: 速率限制头检查 (需在服务端启用 Redis)\n";
echo "  请手动验证响应头包含 X-RateLimit-* 头\n";

echo "\n";

// ============================================
// 汇总
// ============================================
echo "╔══════════════════════════════════════════╗\n";
echo "║           联调测试结果                     ║\n";
echo "╚══════════════════════════════════════════╝\n\n";
echo "  通过: $pass\n";
echo "  失败: $fail\n";
echo "  时间: " . date('Y-m-d H:i:s') . "\n\n";

if ($fail > 0) {
    echo "  结论: $fail 项测试未通过，请检查并修复\n";
} else {
    echo "  结论: 所有联调测试通过\n";
}

echo "\n";
exit($fail > 0 ? 1 : 0);
