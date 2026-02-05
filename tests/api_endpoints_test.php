<?php
/**
 * API 接口功能测试
 * 任务 3.8 - 编写接口测试脚本，验证所有查询接口功能
 *
 * 用法：php tests/api_endpoints_test.php [base_url]
 */

echo "╔══════════════════════════════════════════════════════════╗\n";
echo "║      API 接口功能测试                                    ║\n";
echo "╚══════════════════════════════════════════════════════════╝\n\n";

$baseUrl = isset($argv[1]) ? rtrim($argv[1], '/') : 'http://localhost';
$apiBase = $baseUrl . '/api/v1';

// 读取凭证
$credFile = __DIR__ . '/../api_credentials.txt';
$apiKey = '';
$apiSecret = '';

if (file_exists($credFile)) {
    $lines = file($credFile, FILE_IGNORE_NEW_LINES);
    foreach ($lines as $line) {
        if (strpos($line, 'API Key:') === 0) $apiKey = trim(substr($line, 8));
        if (strpos($line, 'API Secret:') === 0) $apiSecret = trim(substr($line, 11));
    }
}

if (empty($apiKey) || empty($apiSecret)) {
    echo "错误: 未找到 API 凭证。\n";
    exit(1);
}

echo "API Base: $apiBase\n";
echo "API Key: " . substr($apiKey, 0, 12) . "...\n\n";

$passed = 0;
$failed = 0;
$errors = [];

function generateSign($apiKey, $apiSecret, $timestamp) {
    $signString = $apiKey . $timestamp . $apiSecret;
    return hash_hmac('sha256', $signString, $apiSecret);
}

function apiGet($url, $apiKey, $apiSecret, $params = []) {
    $timestamp = time();
    $sign = generateSign($apiKey, $apiSecret, $timestamp);
    $params['api_key'] = $apiKey;
    $params['timestamp'] = $timestamp;
    $params['sign'] = $sign;

    $fullUrl = $url . '?' . http_build_query($params);

    $ch = curl_init($fullUrl);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_TIMEOUT, 10);
    $resp = curl_exec($ch);
    $code = curl_getinfo($ch, CURLINFO_HTTP_CODE);
    $err = curl_error($ch);
    curl_close($ch);

    return [
        'http_code' => $code,
        'body' => json_decode($resp, true),
        'error' => $err
    ];
}

function apiPost($url, $apiKey, $apiSecret, $data = []) {
    $timestamp = time();
    $sign = generateSign($apiKey, $apiSecret, $timestamp);

    $fullUrl = $url . '?api_key=' . $apiKey . '&timestamp=' . $timestamp . '&sign=' . $sign;

    $ch = curl_init($fullUrl);
    curl_setopt($ch, CURLOPT_POST, true);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_TIMEOUT, 10);
    curl_setopt($ch, CURLOPT_HTTPHEADER, ['Content-Type: application/json']);
    curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($data));
    $resp = curl_exec($ch);
    $code = curl_getinfo($ch, CURLINFO_HTTP_CODE);
    $err = curl_error($ch);
    curl_close($ch);

    return [
        'http_code' => $code,
        'body' => json_decode($resp, true),
        'error' => $err
    ];
}

function assertTest($testName, $condition, &$passed, &$failed, &$errors, $detail = '') {
    if ($condition) {
        echo "  ✓ $testName\n";
        $passed++;
    } else {
        echo "  ✗ $testName" . ($detail ? " ($detail)" : "") . "\n";
        $failed++;
        $errors[] = $testName . ($detail ? ": $detail" : "");
    }
}

// =========================================================
echo str_repeat('=', 60) . "\n";
echo "  1. 开奖结果接口 (lottery_results)\n";
echo str_repeat('=', 60) . "\n\n";

// 1.1 查询最新开奖结果
echo "测试 1.1: GET /lottery_results/latest\n";
$resp = apiGet("$apiBase/lottery_results/latest", $apiKey, $apiSecret);
if ($resp['error']) {
    echo "  ✗ 连接失败: {$resp['error']}\n";
    $failed++;
} else {
    assertTest('HTTP 200 或 404', in_array($resp['http_code'], [200, 404]), $passed, $failed, $errors);
    if ($resp['http_code'] == 200 && $resp['body']['success']) {
        $data = $resp['body']['data'];
        assertTest('包含 game_id', isset($data['game_id']), $passed, $failed, $errors);
        assertTest('包含 period', isset($data['period']), $passed, $failed, $errors);
        assertTest('包含 result', isset($data['result']), $passed, $failed, $errors);
        assertTest('包含 draw_time', isset($data['draw_time']), $passed, $failed, $errors);
    }
}

// 1.2 按游戏查询最新结果
echo "\n测试 1.2: GET /lottery_results/latest?game_id=1\n";
$resp = apiGet("$apiBase/lottery_results/latest", $apiKey, $apiSecret, ['game_id' => 1]);
if (!$resp['error']) {
    assertTest('HTTP 200 或 404', in_array($resp['http_code'], [200, 404]), $passed, $failed, $errors);
}

// 1.3 查询历史开奖列表
echo "\n测试 1.3: GET /lottery_results (列表)\n";
$resp = apiGet("$apiBase/lottery_results", $apiKey, $apiSecret, ['page' => 1, 'page_size' => 5]);
if (!$resp['error']) {
    assertTest('HTTP 200', $resp['http_code'] == 200, $passed, $failed, $errors);
    if ($resp['http_code'] == 200 && $resp['body']['success']) {
        $data = $resp['body']['data'];
        assertTest('包含 list 数组', isset($data['list']) && is_array($data['list']), $passed, $failed, $errors);
        assertTest('包含 pagination', isset($data['pagination']), $passed, $failed, $errors);
        if (isset($data['pagination'])) {
            assertTest('pagination.page = 1', $data['pagination']['page'] == 1, $passed, $failed, $errors);
            assertTest('pagination.page_size = 5', $data['pagination']['page_size'] == 5, $passed, $failed, $errors);
            assertTest('pagination.total >= 0', $data['pagination']['total'] >= 0, $passed, $failed, $errors);
        }
    }
}

// 1.4 日期范围过滤
echo "\n测试 1.4: GET /lottery_results (日期过滤)\n";
$resp = apiGet("$apiBase/lottery_results", $apiKey, $apiSecret, [
    'start_date' => '2024-01-01',
    'end_date' => '2025-12-31'
]);
if (!$resp['error']) {
    assertTest('HTTP 200', $resp['http_code'] == 200, $passed, $failed, $errors);
}

// =========================================================
echo "\n" . str_repeat('=', 60) . "\n";
echo "  2. 投注记录接口 (bets)\n";
echo str_repeat('=', 60) . "\n\n";

// 2.1 查询投注列表
echo "测试 2.1: GET /bets\n";
$resp = apiGet("$apiBase/bets", $apiKey, $apiSecret, ['page' => 1, 'page_size' => 5]);
if (!$resp['error']) {
    assertTest('HTTP 200', $resp['http_code'] == 200, $passed, $failed, $errors);
    if ($resp['http_code'] == 200 && isset($resp['body']['data'])) {
        $data = $resp['body']['data'];
        assertTest('包含 list', isset($data['list']), $passed, $failed, $errors);
        assertTest('包含 pagination', isset($data['pagination']), $passed, $failed, $errors);
    }
}

// 2.2 按用户查询
echo "\n测试 2.2: GET /bets?user_id=1\n";
$resp = apiGet("$apiBase/bets", $apiKey, $apiSecret, ['user_id' => 1]);
if (!$resp['error']) {
    assertTest('HTTP 200', $resp['http_code'] == 200, $passed, $failed, $errors);
}

// =========================================================
echo "\n" . str_repeat('=', 60) . "\n";
echo "  3. 交易记录接口 (transactions)\n";
echo str_repeat('=', 60) . "\n\n";

echo "测试 3.1: GET /transactions\n";
$resp = apiGet("$apiBase/transactions", $apiKey, $apiSecret, ['page' => 1, 'page_size' => 5]);
if (!$resp['error']) {
    assertTest('HTTP 200', $resp['http_code'] == 200, $passed, $failed, $errors);
    if ($resp['http_code'] == 200 && isset($resp['body']['data'])) {
        assertTest('包含 list', isset($resp['body']['data']['list']), $passed, $failed, $errors);
        assertTest('包含 pagination', isset($resp['body']['data']['pagination']), $passed, $failed, $errors);
    }
}

echo "\n测试 3.2: GET /transactions?type=1\n";
$resp = apiGet("$apiBase/transactions", $apiKey, $apiSecret, ['type' => 1]);
if (!$resp['error']) {
    assertTest('HTTP 200', $resp['http_code'] == 200, $passed, $failed, $errors);
}

// =========================================================
echo "\n" . str_repeat('=', 60) . "\n";
echo "  4. 充值接口 (deposits)\n";
echo str_repeat('=', 60) . "\n\n";

echo "测试 4.1: GET /deposits\n";
$resp = apiGet("$apiBase/deposits", $apiKey, $apiSecret, ['page' => 1, 'page_size' => 5]);
if (!$resp['error']) {
    assertTest('HTTP 200', $resp['http_code'] == 200, $passed, $failed, $errors);
    if ($resp['http_code'] == 200 && isset($resp['body']['data'])) {
        assertTest('包含 list', isset($resp['body']['data']['list']), $passed, $failed, $errors);
    }
}

// =========================================================
echo "\n" . str_repeat('=', 60) . "\n";
echo "  5. 提现接口 (withdrawals)\n";
echo str_repeat('=', 60) . "\n\n";

echo "测试 5.1: GET /withdrawals\n";
$resp = apiGet("$apiBase/withdrawals", $apiKey, $apiSecret, ['page' => 1, 'page_size' => 5]);
if (!$resp['error']) {
    assertTest('HTTP 200', $resp['http_code'] == 200, $passed, $failed, $errors);
    if ($resp['http_code'] == 200 && isset($resp['body']['data'])) {
        assertTest('包含 list', isset($resp['body']['data']['list']), $passed, $failed, $errors);
    }
}

// =========================================================
echo "\n" . str_repeat('=', 60) . "\n";
echo "  6. Webhook 接口\n";
echo str_repeat('=', 60) . "\n\n";

// 6.1 查询 Webhook 列表
echo "测试 6.1: GET /webhooks\n";
$resp = apiGet("$apiBase/webhooks", $apiKey, $apiSecret);
if (!$resp['error']) {
    assertTest('HTTP 200', $resp['http_code'] == 200, $passed, $failed, $errors);
}

// 6.2 创建 Webhook
echo "\n测试 6.2: POST /webhooks (创建)\n";
$resp = apiPost("$apiBase/webhooks", $apiKey, $apiSecret, [
    'event_type' => 'bet.created',
    'callback_url' => 'https://example.com/webhook/test',
    'description' => '测试 Webhook'
]);
if (!$resp['error']) {
    assertTest('HTTP 200 或 201', in_array($resp['http_code'], [200, 201]), $passed, $failed, $errors,
        "实际: {$resp['http_code']}");
    if ($resp['body']['success'] && isset($resp['body']['data']['id'])) {
        $webhookId = $resp['body']['data']['id'];
        echo "  创建的 Webhook ID: $webhookId\n";

        // 6.3 删除刚创建的 Webhook
        echo "\n测试 6.3: DELETE /webhooks/$webhookId\n";
        $timestamp = time();
        $sign = generateSign($apiKey, $apiSecret, $timestamp);
        $delUrl = "$apiBase/webhooks/$webhookId?api_key=$apiKey&timestamp=$timestamp&sign=$sign";

        $ch = curl_init($delUrl);
        curl_setopt($ch, CURLOPT_CUSTOMREQUEST, 'DELETE');
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_TIMEOUT, 10);
        $delResp = curl_exec($ch);
        $delCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        curl_close($ch);

        assertTest('删除返回 200', $delCode == 200, $passed, $failed, $errors, "实际: $delCode");
    }
}

// =========================================================
echo "\n" . str_repeat('=', 60) . "\n";
echo "  7. 分页参数验证\n";
echo str_repeat('=', 60) . "\n\n";

// 7.1 超大 page_size
echo "测试 7.1: page_size 超过最大值\n";
$resp = apiGet("$apiBase/lottery_results", $apiKey, $apiSecret, ['page_size' => 500]);
if (!$resp['error'] && $resp['http_code'] == 200) {
    $actualSize = $resp['body']['data']['pagination']['page_size'] ?? 0;
    assertTest('page_size 应被限制为最大 100', $actualSize <= 100, $passed, $failed, $errors, "实际: $actualSize");
}

// 7.2 page=0
echo "\n测试 7.2: page=0 应被修正\n";
$resp = apiGet("$apiBase/lottery_results", $apiKey, $apiSecret, ['page' => 0]);
if (!$resp['error'] && $resp['http_code'] == 200) {
    $actualPage = $resp['body']['data']['pagination']['page'] ?? 0;
    assertTest('page 应 >= 1', $actualPage >= 1, $passed, $failed, $errors, "实际: $actualPage");
}

// =========================================================
echo "\n" . str_repeat('=', 60) . "\n";
echo "  8. 数据隔离验证\n";
echo str_repeat('=', 60) . "\n\n";

echo "测试 8.1: 响应中不应泄露敏感信息\n";
$resp = apiGet("$apiBase/lottery_results/latest", $apiKey, $apiSecret);
if (!$resp['error'] && $resp['body']) {
    $jsonStr = json_encode($resp['body']);
    assertTest('不包含密码字段', strpos($jsonStr, 'password') === false && strpos($jsonStr, 'userpass') === false,
        $passed, $failed, $errors);
    assertTest('不包含 secret 字段', strpos($jsonStr, 'api_secret') === false,
        $passed, $failed, $errors);
}

// =========================================================
// 结果汇总
echo "\n" . str_repeat('=', 60) . "\n";
echo "  测试结果汇总\n";
echo str_repeat('=', 60) . "\n\n";

$total = $passed + $failed;
echo "总测试项: $total\n";
echo "通过: $passed ✓\n";
echo "失败: $failed ✗\n";
echo "通过率: " . ($total > 0 ? round($passed / $total * 100, 1) : 0) . "%\n\n";

if ($failed > 0) {
    echo "失败项:\n";
    foreach ($errors as $err) {
        echo "  - $err\n";
    }
    echo "\n";
}

exit($failed > 0 ? 1 : 0);
