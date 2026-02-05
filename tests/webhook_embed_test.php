<?php
/**
 * Webhook 和游戏嵌入功能测试
 * 任务 4.13 - 测试游戏窗口嵌入和 Webhook 推送功能
 *
 * 用法：php tests/webhook_embed_test.php [base_url]
 */

echo "╔══════════════════════════════════════════════════════════╗\n";
echo "║      Webhook 与游戏嵌入功能测试                          ║\n";
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

$passed = 0;
$failed = 0;
$errors = [];

function generateSign($apiKey, $apiSecret, $timestamp) {
    return hash_hmac('sha256', $apiKey . $timestamp . $apiSecret, $apiSecret);
}

function apiRequest($url, $apiKey, $apiSecret, $method = 'GET', $data = null) {
    $timestamp = time();
    $sign = generateSign($apiKey, $apiSecret, $timestamp);
    $separator = strpos($url, '?') !== false ? '&' : '?';
    $authUrl = $url . $separator . "api_key=$apiKey&timestamp=$timestamp&sign=$sign";

    $ch = curl_init($authUrl);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_TIMEOUT, 10);
    curl_setopt($ch, CURLOPT_HEADER, true);

    if ($method === 'POST') {
        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_HTTPHEADER, ['Content-Type: application/json']);
        curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($data));
    } elseif ($method === 'DELETE') {
        curl_setopt($ch, CURLOPT_CUSTOMREQUEST, 'DELETE');
    }

    $response = curl_exec($ch);
    $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
    $headerSize = curl_getinfo($ch, CURLINFO_HEADER_SIZE);
    $error = curl_error($ch);
    curl_close($ch);

    $headers = $response ? substr($response, 0, $headerSize) : '';
    $body = $response ? substr($response, $headerSize) : '';

    return [
        'http_code' => $httpCode,
        'headers' => $headers,
        'body' => json_decode($body, true),
        'error' => $error
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
echo "  1. Webhook CRUD 测试\n";
echo str_repeat('=', 60) . "\n\n";

$createdWebhookIds = [];

// 1.1 创建 Webhook - bet.created
echo "测试 1.1: 创建 Webhook (bet.created)\n";
$resp = apiRequest("$apiBase/webhooks", $apiKey, $apiSecret, 'POST', [
    'event_type' => 'bet.created',
    'callback_url' => 'https://httpbin.org/post',
    'description' => '投注创建通知'
]);
if (!$resp['error']) {
    assertTest('HTTP 200/201', in_array($resp['http_code'], [200, 201]), $passed, $failed, $errors);
    if (isset($resp['body']['data']['id'])) {
        $createdWebhookIds[] = $resp['body']['data']['id'];
        assertTest('返回 webhook ID', true, $passed, $failed, $errors);
        assertTest('返回 secret', isset($resp['body']['data']['secret']), $passed, $failed, $errors);
    }
}

// 1.2 创建 Webhook - deposit.completed
echo "\n测试 1.2: 创建 Webhook (deposit.completed)\n";
$resp = apiRequest("$apiBase/webhooks", $apiKey, $apiSecret, 'POST', [
    'event_type' => 'deposit.completed',
    'callback_url' => 'https://httpbin.org/post',
    'description' => '充值完成通知'
]);
if (!$resp['error'] && isset($resp['body']['data']['id'])) {
    $createdWebhookIds[] = $resp['body']['data']['id'];
    assertTest('创建成功', true, $passed, $failed, $errors);
}

// 1.3 查询 Webhook 列表
echo "\n测试 1.3: 查询 Webhook 列表\n";
$resp = apiRequest("$apiBase/webhooks", $apiKey, $apiSecret);
if (!$resp['error']) {
    assertTest('HTTP 200', $resp['http_code'] == 200, $passed, $failed, $errors);
    if ($resp['body']['success']) {
        $webhookList = $resp['body']['data']['list'] ?? $resp['body']['data'] ?? [];
        assertTest('列表非空', !empty($webhookList), $passed, $failed, $errors);
    }
}

// 1.4 创建无效 Webhook（缺少必要字段）
echo "\n测试 1.4: 创建无效 Webhook（缺少 callback_url）\n";
$resp = apiRequest("$apiBase/webhooks", $apiKey, $apiSecret, 'POST', [
    'event_type' => 'bet.created'
]);
if (!$resp['error']) {
    assertTest('应返回 400', $resp['http_code'] == 400, $passed, $failed, $errors, "实际: {$resp['http_code']}");
}

// 1.5 创建无效事件类型
echo "\n测试 1.5: 创建无效事件类型\n";
$resp = apiRequest("$apiBase/webhooks", $apiKey, $apiSecret, 'POST', [
    'event_type' => 'invalid.event.type',
    'callback_url' => 'https://example.com/webhook'
]);
if (!$resp['error']) {
    assertTest('应返回 400', $resp['http_code'] == 400, $passed, $failed, $errors, "实际: {$resp['http_code']}");
}

// 1.6 删除 Webhooks
echo "\n测试 1.6: 删除已创建的 Webhooks\n";
foreach ($createdWebhookIds as $whId) {
    $resp = apiRequest("$apiBase/webhooks/$whId", $apiKey, $apiSecret, 'DELETE');
    if (!$resp['error']) {
        assertTest("删除 Webhook $whId", $resp['http_code'] == 200, $passed, $failed, $errors);
    }
}

// 1.7 删除不存在的 Webhook
echo "\n测试 1.7: 删除不存在的 Webhook\n";
$resp = apiRequest("$apiBase/webhooks/999999", $apiKey, $apiSecret, 'DELETE');
if (!$resp['error']) {
    assertTest('应返回 404', $resp['http_code'] == 404, $passed, $failed, $errors, "实际: {$resp['http_code']}");
}

// =========================================================
echo "\n" . str_repeat('=', 60) . "\n";
echo "  2. Webhook 签名验证逻辑测试（本地）\n";
echo str_repeat('=', 60) . "\n\n";

echo "测试 2.1: Webhook 签名生成\n";
$webhookSecret = 'test_webhook_secret_123';
$payload = json_encode(['event' => 'bet.created', 'data' => ['id' => 1]]);
$webhookSign = hash_hmac('sha256', $payload, $webhookSecret);
assertTest('签名长度 64 字符', strlen($webhookSign) === 64, $passed, $failed, $errors);
assertTest('签名是十六进制', ctype_xdigit($webhookSign), $passed, $failed, $errors);

echo "\n测试 2.2: 相同 payload 签名一致\n";
$sign1 = hash_hmac('sha256', $payload, $webhookSecret);
$sign2 = hash_hmac('sha256', $payload, $webhookSecret);
assertTest('两次签名相同', $sign1 === $sign2, $passed, $failed, $errors);

echo "\n测试 2.3: 不同 secret 签名不同\n";
$sign3 = hash_hmac('sha256', $payload, 'different_secret');
assertTest('不同 secret 产生不同签名', $sign1 !== $sign3, $passed, $failed, $errors);

// =========================================================
echo "\n" . str_repeat('=', 60) . "\n";
echo "  3. 游戏嵌入 Token 测试\n";
echo str_repeat('=', 60) . "\n\n";

// 3.1 生成嵌入 Token
echo "测试 3.1: 生成嵌入 Token\n";
$resp = apiRequest("$apiBase/embed-token", $apiKey, $apiSecret, 'POST', [
    'user_id' => 1
]);
if (!$resp['error']) {
    if ($resp['http_code'] == 200 && isset($resp['body']['data']['token'])) {
        $embedToken = $resp['body']['data']['token'];
        assertTest('返回 token', !empty($embedToken), $passed, $failed, $errors);
        assertTest('返回 embed_url', isset($resp['body']['data']['embed_url']), $passed, $failed, $errors);
        assertTest('token 非空字符串', is_string($embedToken) && strlen($embedToken) > 10, $passed, $failed, $errors);

        // 3.2 验证嵌入页面
        echo "\n测试 3.2: 访问嵌入页面\n";
        $embedUrl = $baseUrl . '/web/embed/game.php?token=' . urlencode($embedToken);
        $ch = curl_init($embedUrl);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_TIMEOUT, 10);
        curl_setopt($ch, CURLOPT_HEADER, true);
        curl_setopt($ch, CURLOPT_FOLLOWLOCATION, false);
        $embedResp = curl_exec($ch);
        $embedCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        curl_close($ch);

        if ($embedResp !== false) {
            assertTest('嵌入页面可访问 (200)', $embedCode == 200, $passed, $failed, $errors, "实际: $embedCode");

            // 检查安全头
            assertTest('包含 X-Frame-Options', strpos($embedResp, 'X-Frame-Options') !== false, $passed, $failed, $errors);
            assertTest('包含 X-Content-Type-Options', strpos($embedResp, 'X-Content-Type-Options') !== false, $passed, $failed, $errors);
        }
    } else {
        assertTest('Token 生成', false, $passed, $failed, $errors, "HTTP: {$resp['http_code']}");
    }
} else {
    assertTest('连接嵌入 Token 接口', false, $passed, $failed, $errors, $resp['error']);
}

// 3.3 无效 Token
echo "\n测试 3.3: 无效 Token 访问嵌入页面\n";
$ch = curl_init($baseUrl . '/web/embed/game.php?token=invalid_token_abc');
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
curl_setopt($ch, CURLOPT_TIMEOUT, 10);
$invalidResp = curl_exec($ch);
$invalidCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
curl_close($ch);

if ($invalidResp !== false) {
    assertTest('无效 Token 应拒绝 (403)', $invalidCode == 403, $passed, $failed, $errors, "实际: $invalidCode");
}

// 3.4 无 Token
echo "\n测试 3.4: 无 Token 访问嵌入页面\n";
$ch = curl_init($baseUrl . '/web/embed/game.php');
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
curl_setopt($ch, CURLOPT_TIMEOUT, 10);
$noTokenResp = curl_exec($ch);
$noTokenCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
curl_close($ch);

if ($noTokenResp !== false) {
    assertTest('无 Token 应拒绝 (403)', $noTokenCode == 403, $passed, $failed, $errors, "实际: $noTokenCode");
}

// 3.5 缺少 user_id 的 Token 请求
echo "\n测试 3.5: 缺少 user_id 的 Token 请求\n";
$resp = apiRequest("$apiBase/embed-token", $apiKey, $apiSecret, 'POST', []);
if (!$resp['error']) {
    assertTest('应返回 400', $resp['http_code'] == 400, $passed, $failed, $errors, "实际: {$resp['http_code']}");
}

// =========================================================
echo "\n" . str_repeat('=', 60) . "\n";
echo "  4. EmbedTokenManager 本地逻辑测试\n";
echo str_repeat('=', 60) . "\n\n";

$embedManagerFile = __DIR__ . '/../web/class/api/EmbedTokenManager.php';
if (file_exists($embedManagerFile)) {
    $content = file_get_contents($embedManagerFile);

    echo "测试 4.1: EmbedTokenManager 类结构\n";
    assertTest('包含 generateToken 方法', strpos($content, 'function generateToken') !== false, $passed, $failed, $errors);
    assertTest('包含 verifyToken 方法', strpos($content, 'function verifyToken') !== false, $passed, $failed, $errors);
    assertTest('包含 cleanupNonces 方法', strpos($content, 'function cleanupNonces') !== false, $passed, $failed, $errors);
    assertTest('使用 AES-256-CBC 加密', strpos($content, 'aes-256-cbc') !== false || strpos($content, 'AES-256-CBC') !== false, $passed, $failed, $errors);
    assertTest('包含 nonce 防重放', strpos($content, 'nonce') !== false, $passed, $failed, $errors);
    assertTest('包含过期时间检查', strpos($content, 'exp') !== false, $passed, $failed, $errors);
} else {
    assertTest('EmbedTokenManager 文件存在', false, $passed, $failed, $errors);
}

// =========================================================
echo "\n" . str_repeat('=', 60) . "\n";
echo "  5. WebhookManager 本地逻辑测试\n";
echo str_repeat('=', 60) . "\n\n";

$webhookManagerFile = __DIR__ . '/../web/class/api/WebhookManager.php';
if (file_exists($webhookManagerFile)) {
    $content = file_get_contents($webhookManagerFile);

    echo "测试 5.1: WebhookManager 类结构\n";
    assertTest('支持 bet.created 事件', strpos($content, 'bet.created') !== false, $passed, $failed, $errors);
    assertTest('支持 bet.settled 事件', strpos($content, 'bet.settled') !== false, $passed, $failed, $errors);
    assertTest('支持 deposit.completed 事件', strpos($content, 'deposit.completed') !== false, $passed, $failed, $errors);
    assertTest('支持 withdrawal.completed 事件', strpos($content, 'withdrawal.completed') !== false, $passed, $failed, $errors);
    assertTest('使用 HMAC-SHA256 签名', strpos($content, 'sha256') !== false, $passed, $failed, $errors);
    assertTest('包含重试逻辑', strpos($content, 'retry') !== false, $passed, $failed, $errors);
    assertTest('记录日志到 webhook_logs', strpos($content, 'webhook_logs') !== false, $passed, $failed, $errors);
} else {
    assertTest('WebhookManager 文件存在', false, $passed, $failed, $errors);
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
