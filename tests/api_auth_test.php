<?php
/**
 * API 认证、签名验证和速率限制功能测试
 * 任务 2.11 - 测试认证、签名验证和速率限制功能
 *
 * 用法：php tests/api_auth_test.php [base_url]
 * 默认 base_url: http://localhost
 *
 * 要求：数据库中已有可用的 API Key
 */

echo "╔══════════════════════════════════════════════════════════╗\n";
echo "║      API 认证与安全测试                                  ║\n";
echo "╚══════════════════════════════════════════════════════════╝\n\n";

// 配置
$baseUrl = isset($argv[1]) ? rtrim($argv[1], '/') : 'http://localhost';
$apiBase = $baseUrl . '/api/v1';

// 从凭证文件或命令行读取
$credFile = __DIR__ . '/../api_credentials.txt';
$apiKey = '';
$apiSecret = '';

if (file_exists($credFile)) {
    $lines = file($credFile, FILE_IGNORE_NEW_LINES);
    foreach ($lines as $line) {
        if (strpos($line, 'API Key:') === 0) {
            $apiKey = trim(substr($line, 8));
        }
        if (strpos($line, 'API Secret:') === 0) {
            $apiSecret = trim(substr($line, 11));
        }
    }
}

if (empty($apiKey) || empty($apiSecret)) {
    echo "错误: 未找到 API 凭证。请先运行 init_database.php 或手动创建 api_credentials.txt\n";
    echo "文件格式:\n";
    echo "  API Key: your_api_key\n";
    echo "  API Secret: your_api_secret\n";
    exit(1);
}

echo "配置信息:\n";
echo "  API Base URL: $apiBase\n";
echo "  API Key: $apiKey\n";
echo "  API Secret: " . substr($apiSecret, 0, 8) . "...\n\n";

$passed = 0;
$failed = 0;
$errors = [];

/**
 * 生成 HMAC-SHA256 签名
 */
function generateSign($apiKey, $apiSecret, $timestamp) {
    $signString = $apiKey . $timestamp . $apiSecret;
    return hash_hmac('sha256', $signString, $apiSecret);
}

/**
 * 发送 HTTP 请求
 */
function httpRequest($url, $method = 'GET', $headers = [], $body = null) {
    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, $url);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_TIMEOUT, 10);
    curl_setopt($ch, CURLOPT_HEADER, true);
    curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);

    if ($method === 'POST') {
        curl_setopt($ch, CURLOPT_POST, true);
        if ($body) {
            curl_setopt($ch, CURLOPT_POSTFIELDS, $body);
        }
    } elseif ($method === 'DELETE') {
        curl_setopt($ch, CURLOPT_CUSTOMREQUEST, 'DELETE');
    }

    $response = curl_exec($ch);
    $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
    $headerSize = curl_getinfo($ch, CURLINFO_HEADER_SIZE);
    $error = curl_error($ch);
    curl_close($ch);

    if ($error) {
        return ['http_code' => 0, 'headers' => '', 'body' => null, 'error' => $error];
    }

    $responseHeaders = substr($response, 0, $headerSize);
    $responseBody = substr($response, $headerSize);

    return [
        'http_code' => $httpCode,
        'headers' => $responseHeaders,
        'body' => json_decode($responseBody, true),
        'raw_body' => $responseBody,
        'error' => null
    ];
}

/**
 * 构建认证 URL
 */
function buildAuthUrl($url, $apiKey, $apiSecret) {
    $timestamp = time();
    $sign = generateSign($apiKey, $apiSecret, $timestamp);
    $separator = strpos($url, '?') !== false ? '&' : '?';
    return $url . $separator . "api_key=$apiKey&timestamp=$timestamp&sign=$sign";
}

/**
 * 断言测试
 */
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
echo "  1. 认证测试\n";
echo str_repeat('=', 60) . "\n\n";

// 测试 1.1: 无 API Key
echo "测试 1.1: 无 API Key 请求\n";
$resp = httpRequest("$apiBase/lottery_results/latest");
if ($resp['error']) {
    echo "  ✗ 连接失败: {$resp['error']}\n";
    echo "\n⚠ 无法连接到 API 服务器，请确保 Web 服务器已启动。\n";
    echo "后续测试将跳过网络请求部分。\n\n";
    $failed++;
} else {
    assertTest('应返回 401', $resp['http_code'] == 401, $passed, $failed, $errors);
    assertTest('应包含错误码 1001', isset($resp['body']['code']) && $resp['body']['code'] == 1001, $passed, $failed, $errors);
}

// 测试 1.2: 无效 API Key
echo "\n测试 1.2: 无效 API Key\n";
$fakeUrl = buildAuthUrl("$apiBase/lottery_results/latest", 'invalid_key_12345', 'fake_secret');
$resp = httpRequest($fakeUrl);
if (!$resp['error']) {
    assertTest('应返回 401', $resp['http_code'] == 401, $passed, $failed, $errors);
    assertTest('应包含错误码 1002', isset($resp['body']['code']) && $resp['body']['code'] == 1002, $passed, $failed, $errors);
}

// 测试 1.3: 有效 API Key + 正确签名
echo "\n测试 1.3: 有效认证请求\n";
$authUrl = buildAuthUrl("$apiBase/lottery_results/latest", $apiKey, $apiSecret);
$resp = httpRequest($authUrl);
if (!$resp['error']) {
    assertTest('应返回 200 或 404', in_array($resp['http_code'], [200, 404]), $passed, $failed, $errors,
        "实际: {$resp['http_code']}");
    assertTest('应包含 success 字段', isset($resp['body']['success']), $passed, $failed, $errors);
}

// =========================================================
echo "\n" . str_repeat('=', 60) . "\n";
echo "  2. 签名验证测试\n";
echo str_repeat('=', 60) . "\n\n";

// 测试 2.1: 缺少签名
echo "测试 2.1: 缺少签名\n";
$timestamp = time();
$resp = httpRequest("$apiBase/lottery_results/latest?api_key=$apiKey&timestamp=$timestamp");
if (!$resp['error']) {
    assertTest('应返回 401', $resp['http_code'] == 401, $passed, $failed, $errors);
}

// 测试 2.2: 错误签名
echo "\n测试 2.2: 错误签名\n";
$timestamp = time();
$resp = httpRequest("$apiBase/lottery_results/latest?api_key=$apiKey&timestamp=$timestamp&sign=invalid_sign_abc123");
if (!$resp['error']) {
    assertTest('应返回 401', $resp['http_code'] == 401, $passed, $failed, $errors);
    assertTest('应包含签名错误码 1007', isset($resp['body']['code']) && $resp['body']['code'] == 1007, $passed, $failed, $errors);
}

// 测试 2.3: 过期时间戳（6分钟前）
echo "\n测试 2.3: 过期时间戳\n";
$expiredTimestamp = time() - 360; // 6分钟前
$expiredSign = generateSign($apiKey, $apiSecret, $expiredTimestamp);
$resp = httpRequest("$apiBase/lottery_results/latest?api_key=$apiKey&timestamp=$expiredTimestamp&sign=$expiredSign");
if (!$resp['error']) {
    assertTest('应返回 401', $resp['http_code'] == 401, $passed, $failed, $errors);
    assertTest('应包含时间戳错误码 1005', isset($resp['body']['code']) && $resp['body']['code'] == 1005, $passed, $failed, $errors);
}

// 测试 2.4: 缺少时间戳
echo "\n测试 2.4: 缺少时间戳\n";
$resp = httpRequest("$apiBase/lottery_results/latest?api_key=$apiKey&sign=some_sign");
if (!$resp['error']) {
    assertTest('应返回 401', $resp['http_code'] == 401, $passed, $failed, $errors);
}

// 测试 2.5: 签名算法验证（本地）
echo "\n测试 2.5: 签名算法本地验证\n";
$testKey = 'test_api_key_123';
$testSecret = 'test_api_secret_456';
$testTimestamp = 1700000000;
$expectedSign = hash_hmac('sha256', $testKey . $testTimestamp . $testSecret, $testSecret);
$actualSign = generateSign($testKey, $testSecret, $testTimestamp);
assertTest('签名算法一致', $expectedSign === $actualSign, $passed, $failed, $errors);
assertTest('签名长度 64 字符', strlen($actualSign) === 64, $passed, $failed, $errors);

// =========================================================
echo "\n" . str_repeat('=', 60) . "\n";
echo "  3. 防重放攻击测试\n";
echo str_repeat('=', 60) . "\n\n";

// 测试 3.1: 时间窗口内有效
echo "测试 3.1: 4分钟内的时间戳应有效\n";
$ts4min = time() - 240;
$sign4min = generateSign($apiKey, $apiSecret, $ts4min);
$resp = httpRequest("$apiBase/lottery_results/latest?api_key=$apiKey&timestamp=$ts4min&sign=$sign4min");
if (!$resp['error']) {
    assertTest('4分钟内应允许', in_array($resp['http_code'], [200, 404]), $passed, $failed, $errors,
        "实际: {$resp['http_code']}");
}

// 测试 3.2: 未来时间戳（6分钟后）
echo "\n测试 3.2: 未来过远的时间戳\n";
$futureTs = time() + 360;
$futureSign = generateSign($apiKey, $apiSecret, $futureTs);
$resp = httpRequest("$apiBase/lottery_results/latest?api_key=$apiKey&timestamp=$futureTs&sign=$futureSign");
if (!$resp['error']) {
    assertTest('6分钟后应拒绝', $resp['http_code'] == 401, $passed, $failed, $errors,
        "实际: {$resp['http_code']}");
}

// =========================================================
echo "\n" . str_repeat('=', 60) . "\n";
echo "  4. 速率限制测试（需要 Redis）\n";
echo str_repeat('=', 60) . "\n\n";

// 测试 4.1: 检查速率限制响应头
echo "测试 4.1: 速率限制响应头\n";
$authUrl = buildAuthUrl("$apiBase/lottery_results/latest", $apiKey, $apiSecret);
$resp = httpRequest($authUrl);
if (!$resp['error']) {
    $hasRateHeader = strpos($resp['headers'], 'X-RateLimit-Limit') !== false;
    assertTest('应包含 X-RateLimit-Limit 头', $hasRateHeader, $passed, $failed, $errors);

    $hasRemaining = strpos($resp['headers'], 'X-RateLimit-Remaining') !== false;
    assertTest('应包含 X-RateLimit-Remaining 头', $hasRemaining, $passed, $failed, $errors);
}

// =========================================================
echo "\n" . str_repeat('=', 60) . "\n";
echo "  5. CORS 测试\n";
echo str_repeat('=', 60) . "\n\n";

// 测试 5.1: OPTIONS 预检请求
echo "测试 5.1: OPTIONS 预检请求\n";
$ch = curl_init("$apiBase/lottery_results/latest");
curl_setopt($ch, CURLOPT_CUSTOMREQUEST, 'OPTIONS');
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
curl_setopt($ch, CURLOPT_HEADER, true);
curl_setopt($ch, CURLOPT_HTTPHEADER, ['Origin: https://example.com']);
curl_setopt($ch, CURLOPT_TIMEOUT, 5);
$optResp = curl_exec($ch);
$optCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
curl_close($ch);

if ($optResp !== false) {
    assertTest('OPTIONS 应返回 204', $optCode == 204, $passed, $failed, $errors, "实际: $optCode");
    assertTest('应包含 CORS Allow-Methods', strpos($optResp, 'Access-Control-Allow-Methods') !== false, $passed, $failed, $errors);
}

// =========================================================
echo "\n" . str_repeat('=', 60) . "\n";
echo "  6. 错误处理测试\n";
echo str_repeat('=', 60) . "\n\n";

// 测试 6.1: 不允许的请求方法
echo "测试 6.1: POST 请求到 GET 接口\n";
$authUrl = buildAuthUrl("$apiBase/lottery_results/latest", $apiKey, $apiSecret);
$resp = httpRequest($authUrl, 'POST');
if (!$resp['error']) {
    assertTest('应返回 405', $resp['http_code'] == 405, $passed, $failed, $errors, "实际: {$resp['http_code']}");
}

// 测试 6.2: 无效路径
echo "\n测试 6.2: 无效的 API 路径\n";
$authUrl = buildAuthUrl("$apiBase/nonexistent_endpoint", $apiKey, $apiSecret);
$resp = httpRequest($authUrl);
if (!$resp['error']) {
    assertTest('应返回 4xx', $resp['http_code'] >= 400, $passed, $failed, $errors, "实际: {$resp['http_code']}");
}

// 测试 6.3: 响应格式验证
echo "\n测试 6.3: 统一响应格式\n";
$authUrl = buildAuthUrl("$apiBase/lottery_results/latest", $apiKey, $apiSecret);
$resp = httpRequest($authUrl);
if (!$resp['error'] && $resp['body']) {
    assertTest('包含 success 字段', array_key_exists('success', $resp['body']), $passed, $failed, $errors);
    assertTest('包含 code 字段', array_key_exists('code', $resp['body']), $passed, $failed, $errors);
    assertTest('包含 timestamp 字段', array_key_exists('timestamp', $resp['body']), $passed, $failed, $errors);
    assertTest('包含 request_id 字段', array_key_exists('request_id', $resp['body']), $passed, $failed, $errors);
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
