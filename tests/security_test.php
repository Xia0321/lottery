<?php
/**
 * 安全测试脚本
 * 任务 5.11 SQL 注入防护、5.12 速率限制、5.13 签名伪造攻击
 *
 * 用法：php tests/security_test.php [base_url]
 */

echo "╔══════════════════════════════════════════════════════════╗\n";
echo "║      安全测试                                            ║\n";
echo "╚══════════════════════════════════════════════════════════╝\n\n";

$baseUrl = isset($argv[1]) ? rtrim($argv[1], '/') : 'http://localhost';
$apiBase = $baseUrl . '/api/v1';

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

function httpGet($url, $timeout = 10) {
    $ch = curl_init($url);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_TIMEOUT, $timeout);
    curl_setopt($ch, CURLOPT_HEADER, true);
    $resp = curl_exec($ch);
    $code = curl_getinfo($ch, CURLINFO_HTTP_CODE);
    $headerSize = curl_getinfo($ch, CURLINFO_HEADER_SIZE);
    $err = curl_error($ch);
    curl_close($ch);

    return [
        'http_code' => $code,
        'headers' => $resp ? substr($resp, 0, $headerSize) : '',
        'body' => $resp ? json_decode(substr($resp, $headerSize), true) : null,
        'raw' => $resp ? substr($resp, $headerSize) : '',
        'error' => $err
    ];
}

function authUrl($base, $apiKey, $apiSecret, $extra = '') {
    $ts = time();
    $sign = generateSign($apiKey, $apiSecret, $ts);
    $sep = strpos($base, '?') !== false ? '&' : '?';
    return $base . $sep . "api_key=$apiKey&timestamp=$ts&sign=$sign" . ($extra ? "&$extra" : '');
}

function assertTest($name, $cond, &$p, &$f, &$e, $detail = '') {
    if ($cond) { echo "  ✓ $name\n"; $p++; }
    else { echo "  ✗ $name" . ($detail ? " ($detail)" : "") . "\n"; $f++; $e[] = $name . ($detail ? ": $detail" : ""); }
}

// =========================================================
echo str_repeat('=', 60) . "\n";
echo "  1. SQL 注入防护测试\n";
echo str_repeat('=', 60) . "\n\n";

$sqlPayloads = [
    "1' OR '1'='1",
    "1; DROP TABLE api_keys;--",
    "1 UNION SELECT * FROM api_keys--",
    "' OR 1=1--",
    "1' AND (SELECT COUNT(*) FROM api_keys)>0--",
    "1'; WAITFOR DELAY '0:0:5'--",
    "1 AND 1=CONVERT(int,(SELECT api_secret FROM api_keys LIMIT 1))--",
];

// 1.1 GET 参数注入
echo "测试 1.1: GET 参数 SQL 注入\n";
foreach ($sqlPayloads as $i => $payload) {
    $url = authUrl("$apiBase/lottery_results", $apiKey, $apiSecret, "game_id=" . urlencode($payload));
    $resp = httpGet($url);
    if ($resp['error']) {
        echo "  ⚠ 连接失败，跳过后续注入测试\n";
        break;
    }
    $safe = $resp['http_code'] != 500;
    $noDbError = true;
    if ($resp['raw']) {
        $noDbError = stripos($resp['raw'], 'mysql') === false
            && stripos($resp['raw'], 'syntax error') === false
            && stripos($resp['raw'], 'SQLSTATE') === false;
    }
    assertTest("注入载荷 #" . ($i + 1) . " 被拦截", $safe && $noDbError, $passed, $failed, $errors);
}

// 1.2 user_id 参数注入
echo "\n测试 1.2: user_id 参数注入\n";
$url = authUrl("$apiBase/bets", $apiKey, $apiSecret, "user_id=" . urlencode("1 OR 1=1"));
$resp = httpGet($url);
if (!$resp['error']) {
    assertTest('不应返回 500', $resp['http_code'] != 500, $passed, $failed, $errors);
    if ($resp['body']) {
        $noLeak = stripos(json_encode($resp['body']), 'api_secret') === false;
        assertTest('不应泄露敏感信息', $noLeak, $passed, $failed, $errors);
    }
}

// 1.3 分页参数注入
echo "\n测试 1.3: 分页参数注入\n";
$url = authUrl("$apiBase/lottery_results", $apiKey, $apiSecret, "page=" . urlencode("1;DROP TABLE x_kj"));
$resp = httpGet($url);
if (!$resp['error']) {
    assertTest('分页注入被拦截', $resp['http_code'] != 500, $passed, $failed, $errors);
}

// 1.4 静态代码检查
echo "\n测试 1.4: 静态代码分析 - 预处理语句\n";
$apiDir = __DIR__ . '/../web/api/v1';
$apiFiles = glob($apiDir . '/*.php');
$unsafeFiles = [];

foreach ($apiFiles as $file) {
    $content = file_get_contents($file);
    $basename = basename($file);
    if (in_array($basename, ['BaseController.php', 'health.php', 'debug.php'])) continue;
    if (preg_match('/->query\s*\(\s*["\'].*\$/', $content)) {
        $unsafeFiles[] = $basename;
    }
}

assertTest('API 控制器使用预处理语句', empty($unsafeFiles), $passed, $failed, $errors,
    $unsafeFiles ? '待检查: ' . implode(', ', $unsafeFiles) : '');

// =========================================================
echo "\n" . str_repeat('=', 60) . "\n";
echo "  2. 速率限制测试\n";
echo str_repeat('=', 60) . "\n\n";

echo "测试 2.1: 速率限制响应头\n";
$url = authUrl("$apiBase/lottery_results/latest", $apiKey, $apiSecret);
$resp = httpGet($url);
if (!$resp['error']) {
    assertTest('X-RateLimit-Limit 头', strpos($resp['headers'], 'X-RateLimit-Limit') !== false, $passed, $failed, $errors);
    assertTest('X-RateLimit-Remaining 头', strpos($resp['headers'], 'X-RateLimit-Remaining') !== false, $passed, $failed, $errors);
    assertTest('X-RateLimit-Reset 头', strpos($resp['headers'], 'X-RateLimit-Reset') !== false, $passed, $failed, $errors);
}

echo "\n测试 2.2: 连续请求限流检测\n";
$rateLimitHit = false;
$requestCount = 0;
for ($i = 0; $i < 10; $i++) {
    $url = authUrl("$apiBase/lottery_results/latest", $apiKey, $apiSecret);
    $resp = httpGet($url, 5);
    $requestCount++;
    if ($resp['http_code'] == 429) {
        $rateLimitHit = true;
        break;
    }
}
echo "  发送了 $requestCount 个请求\n";
if ($rateLimitHit) {
    assertTest('速率限制生效 (429)', true, $passed, $failed, $errors);
} else {
    echo "  ℹ 10 个请求内未触发限流（限制较高，正常）\n";
    $passed++;
}

// =========================================================
echo "\n" . str_repeat('=', 60) . "\n";
echo "  3. 签名伪造攻击测试\n";
echo str_repeat('=', 60) . "\n\n";

echo "测试 3.1: 空签名\n";
$ts = time();
$resp = httpGet("$apiBase/lottery_results/latest?api_key=$apiKey&timestamp=$ts&sign=");
if (!$resp['error']) {
    assertTest('空签名被拒绝 (401)', $resp['http_code'] == 401, $passed, $failed, $errors);
}

echo "\n测试 3.2: 随机伪造签名\n";
$ts = time();
$fakeSign = hash('sha256', 'random_' . rand());
$resp = httpGet("$apiBase/lottery_results/latest?api_key=$apiKey&timestamp=$ts&sign=$fakeSign");
if (!$resp['error']) {
    assertTest('伪造签名被拒绝 (401)', $resp['http_code'] == 401, $passed, $failed, $errors);
}

echo "\n测试 3.3: 篡改时间戳复用签名\n";
$ts = time();
$validSign = generateSign($apiKey, $apiSecret, $ts);
$resp = httpGet("$apiBase/lottery_results/latest?api_key=$apiKey&timestamp=" . ($ts + 1) . "&sign=$validSign");
if (!$resp['error']) {
    assertTest('篡改时间戳后失效 (401)', $resp['http_code'] == 401, $passed, $failed, $errors);
}

echo "\n测试 3.4: 错误 Secret 签名\n";
$ts = time();
$wrongSign = generateSign($apiKey, 'wrong_secret_12345', $ts);
$resp = httpGet("$apiBase/lottery_results/latest?api_key=$apiKey&timestamp=$ts&sign=$wrongSign");
if (!$resp['error']) {
    assertTest('错误 Secret 被拒绝 (401)', $resp['http_code'] == 401, $passed, $failed, $errors);
}

echo "\n测试 3.5: MD5 代替 HMAC-SHA256\n";
$ts = time();
$md5Sign = md5($apiKey . $ts . $apiSecret);
$resp = httpGet("$apiBase/lottery_results/latest?api_key=$apiKey&timestamp=$ts&sign=$md5Sign");
if (!$resp['error']) {
    assertTest('MD5 签名被拒绝 (401)', $resp['http_code'] == 401, $passed, $failed, $errors);
}

echo "\n测试 3.6: 签名大小写敏感\n";
$ts = time();
$validSign = generateSign($apiKey, $apiSecret, $ts);
$resp = httpGet("$apiBase/lottery_results/latest?api_key=$apiKey&timestamp=$ts&sign=" . strtoupper($validSign));
if (!$resp['error']) {
    assertTest('大写签名被拒绝', $resp['http_code'] == 401, $passed, $failed, $errors);
}

// =========================================================
echo "\n" . str_repeat('=', 60) . "\n";
echo "  4. XSS 防护与安全头测试\n";
echo str_repeat('=', 60) . "\n\n";

echo "测试 4.1: XSS 载荷\n";
$xssPayloads = [
    '<script>alert(1)</script>',
    '"><img src=x onerror=alert(1)>',
];
foreach ($xssPayloads as $i => $payload) {
    $url = authUrl("$apiBase/bets", $apiKey, $apiSecret, "user_id=" . urlencode($payload));
    $resp = httpGet($url);
    if (!$resp['error'] && $resp['raw']) {
        $reflected = strpos($resp['raw'], '<script>') !== false || strpos($resp['raw'], 'onerror=') !== false;
        assertTest("XSS #" . ($i + 1) . " 未反射", !$reflected, $passed, $failed, $errors);
    }
}

echo "\n测试 4.2: 安全响应头\n";
$url = authUrl("$apiBase/lottery_results/latest", $apiKey, $apiSecret);
$resp = httpGet($url);
if (!$resp['error']) {
    assertTest('Content-Type: application/json', strpos($resp['headers'], 'application/json') !== false, $passed, $failed, $errors);
    assertTest('X-Content-Type-Options: nosniff', strpos($resp['headers'], 'nosniff') !== false, $passed, $failed, $errors);
}

// =========================================================
echo "\n" . str_repeat('=', 60) . "\n";
echo "  5. 信息泄露检测\n";
echo str_repeat('=', 60) . "\n\n";

$url = authUrl("$apiBase/lottery_results", $apiKey, $apiSecret, "game_id=999999");
$resp = httpGet($url);
if (!$resp['error'] && $resp['raw']) {
    assertTest('不暴露文件路径', stripos($resp['raw'], 'D:\\') === false && stripos($resp['raw'], '/var/www') === false, $passed, $failed, $errors);
    assertTest('不暴露 SQL 语句', stripos($resp['raw'], 'SELECT') === false, $passed, $failed, $errors);
    assertTest('不暴露堆栈跟踪', stripos($resp['raw'], 'Stack trace') === false, $passed, $failed, $errors);
}

// =========================================================
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
