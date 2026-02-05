<?php
/**
 * 基础负载测试脚本
 *
 * 使用 PHP curl_multi 模拟并发请求，测试 API 性能
 *
 * 用法:
 *   php tests/load_test.php [base_url] [api_key] [api_secret]
 *
 * 示例:
 *   php tests/load_test.php http://localhost lk_abc123 sk_secret456
 */

// ============================================
// 配置
// ============================================

$baseUrl   = isset($argv[1]) ? rtrim($argv[1], '/') : 'http://localhost';
$apiKey    = isset($argv[2]) ? $argv[2] : '';
$apiSecret = isset($argv[3]) ? $argv[3] : '';

if (empty($apiKey) || empty($apiSecret)) {
    echo "用法: php load_test.php <base_url> <api_key> <api_secret>\n";
    echo "示例: php load_test.php http://localhost lk_abc123 sk_secret456\n";
    exit(1);
}

$concurrency = 100; // 并发数
$apiBase = $baseUrl . '/api/v1';

echo "============================================\n";
echo " 彩票系统 API 负载测试\n";
echo "============================================\n";
echo "目标: $baseUrl\n";
echo "API Key: $apiKey\n";
echo "并发数: $concurrency\n";
echo "============================================\n\n";

// ============================================
// 工具函数
// ============================================

function generateSign($apiKey, $timestamp, $apiSecret) {
    $signString = $apiKey . $timestamp . $apiSecret;
    return hash_hmac('sha256', $signString, $apiSecret);
}

function buildAuthQuery($apiKey, $apiSecret) {
    $timestamp = time();
    $sign = generateSign($apiKey, $timestamp, $apiSecret);
    return http_build_query([
        'api_key'   => $apiKey,
        'timestamp' => $timestamp,
        'sign'      => $sign
    ]);
}

/**
 * 并发执行多个 GET 请求
 * @param array $urls URL 列表
 * @param int $timeout 超时秒数
 * @return array 结果 [success_count, fail_count, total_time_ms, response_times, status_codes]
 */
function runConcurrentRequests($urls, $timeout = 10) {
    $mh = curl_multi_init();
    $handles = [];
    $startTimes = [];

    foreach ($urls as $i => $url) {
        $ch = curl_init($url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_TIMEOUT, $timeout);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
        curl_multi_add_handle($mh, $ch);
        $handles[$i] = $ch;
    }

    $totalStart = microtime(true);

    // 执行所有请求
    $running = null;
    do {
        $status = curl_multi_exec($mh, $running);
        if ($running) {
            curl_multi_select($mh, 1);
        }
    } while ($running > 0 && $status === CURLM_OK);

    $totalTime = (microtime(true) - $totalStart) * 1000;

    // 收集结果
    $successCount = 0;
    $failCount = 0;
    $responseTimes = [];
    $statusCodes = [];

    foreach ($handles as $i => $ch) {
        $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        $totalTimeSec = curl_getinfo($ch, CURLINFO_TOTAL_TIME);
        $responseTimeMs = $totalTimeSec * 1000;
        $error = curl_error($ch);

        $responseTimes[] = $responseTimeMs;
        $statusCodes[] = $httpCode;

        if ($error || $httpCode < 200 || $httpCode >= 500) {
            $failCount++;
        } else {
            $successCount++;
        }

        curl_multi_remove_handle($mh, $ch);
        curl_close($ch);
    }

    curl_multi_close($mh);

    return [
        'success' => $successCount,
        'fail' => $failCount,
        'total_time_ms' => $totalTime,
        'response_times' => $responseTimes,
        'status_codes' => $statusCodes
    ];
}

function printStats($testName, $result, $concurrency) {
    $responseTimes = $result['response_times'];
    sort($responseTimes);

    $count = count($responseTimes);
    $avg = array_sum($responseTimes) / max($count, 1);
    $min = $responseTimes[0] ?? 0;
    $max = $responseTimes[$count - 1] ?? 0;
    $p50 = $responseTimes[(int)($count * 0.5)] ?? 0;
    $p90 = $responseTimes[(int)($count * 0.9)] ?? 0;
    $p99 = $responseTimes[(int)($count * 0.99)] ?? 0;
    $rps = $count / ($result['total_time_ms'] / 1000);

    // 统计状态码分布
    $codeDistribution = array_count_values($result['status_codes']);
    ksort($codeDistribution);

    echo "--- $testName ---\n";
    echo "  并发数:    $concurrency\n";
    echo "  成功:      {$result['success']}\n";
    echo "  失败:      {$result['fail']}\n";
    echo "  总耗时:    " . number_format($result['total_time_ms'], 1) . " ms\n";
    echo "  RPS:       " . number_format($rps, 1) . " req/s\n";
    echo "  平均响应:  " . number_format($avg, 1) . " ms\n";
    echo "  最小响应:  " . number_format($min, 1) . " ms\n";
    echo "  最大响应:  " . number_format($max, 1) . " ms\n";
    echo "  P50:       " . number_format($p50, 1) . " ms\n";
    echo "  P90:       " . number_format($p90, 1) . " ms\n";
    echo "  P99:       " . number_format($p99, 1) . " ms\n";
    echo "  状态码:    ";
    foreach ($codeDistribution as $code => $cnt) {
        echo "$code($cnt) ";
    }
    echo "\n";

    // 判断是否通过
    $passed = ($result['fail'] / max($count, 1)) < 0.05; // 失败率低于 5%
    echo "  结果:      " . ($passed ? 'PASS' : 'FAIL') . "\n";
    echo "\n";

    return $passed;
}

// ============================================
// 测试场景
// ============================================

$totalPass = 0;
$totalFail = 0;

// 测试 1: 开奖结果查询 (100 并发)
echo "[Test 1] 开奖结果最新查询 - {$concurrency} 并发\n";
$urls = [];
for ($i = 0; $i < $concurrency; $i++) {
    $auth = buildAuthQuery($apiKey, $apiSecret);
    $urls[] = $apiBase . '/lottery_results/latest?' . $auth;
}
$result = runConcurrentRequests($urls);
if (printStats('GET /lottery_results/latest', $result, $concurrency)) {
    $totalPass++;
} else {
    $totalFail++;
}

// 测试 2: 开奖历史列表 (100 并发)
echo "[Test 2] 开奖历史列表查询 - {$concurrency} 并发\n";
$urls = [];
for ($i = 0; $i < $concurrency; $i++) {
    $auth = buildAuthQuery($apiKey, $apiSecret);
    $urls[] = $apiBase . '/lottery_results?' . $auth . '&page=1&page_size=10';
}
$result = runConcurrentRequests($urls);
if (printStats('GET /lottery_results', $result, $concurrency)) {
    $totalPass++;
} else {
    $totalFail++;
}

// 测试 3: 投注记录查询 (100 并发)
echo "[Test 3] 投注记录查询 - {$concurrency} 并发\n";
$urls = [];
for ($i = 0; $i < $concurrency; $i++) {
    $auth = buildAuthQuery($apiKey, $apiSecret);
    $urls[] = $apiBase . '/bets?' . $auth . '&page=1&page_size=10';
}
$result = runConcurrentRequests($urls);
if (printStats('GET /bets', $result, $concurrency)) {
    $totalPass++;
} else {
    $totalFail++;
}

// 测试 4: 交易记录查询 (100 并发)
echo "[Test 4] 交易记录查询 - {$concurrency} 并发\n";
$urls = [];
for ($i = 0; $i < $concurrency; $i++) {
    $auth = buildAuthQuery($apiKey, $apiSecret);
    $urls[] = $apiBase . '/transactions?' . $auth . '&page=1&page_size=10';
}
$result = runConcurrentRequests($urls);
if (printStats('GET /transactions', $result, $concurrency)) {
    $totalPass++;
} else {
    $totalFail++;
}

// 测试 5: 统计接口 (100 并发)
echo "[Test 5] 总览统计查询 - {$concurrency} 并发\n";
$urls = [];
for ($i = 0; $i < $concurrency; $i++) {
    $auth = buildAuthQuery($apiKey, $apiSecret);
    $urls[] = $apiBase . '/stats/overview?' . $auth;
}
$result = runConcurrentRequests($urls);
if (printStats('GET /stats/overview', $result, $concurrency)) {
    $totalPass++;
} else {
    $totalFail++;
}

// 测试 6: 混合请求 (100 并发，随机端点)
echo "[Test 6] 混合请求 - {$concurrency} 并发\n";
$endpoints = [
    '/lottery_results/latest',
    '/lottery_results?page=1&page_size=5',
    '/bets?page=1&page_size=5',
    '/transactions?page=1&page_size=5',
    '/stats/overview',
];
$urls = [];
for ($i = 0; $i < $concurrency; $i++) {
    $endpoint = $endpoints[$i % count($endpoints)];
    $auth = buildAuthQuery($apiKey, $apiSecret);
    $separator = (strpos($endpoint, '?') !== false) ? '&' : '?';
    $urls[] = $apiBase . $endpoint . $separator . $auth;
}
$result = runConcurrentRequests($urls);
if (printStats('混合端点', $result, $concurrency)) {
    $totalPass++;
} else {
    $totalFail++;
}

// 测试 7: 速率限制测试 (超过默认限制)
echo "[Test 7] 速率限制压力测试 - 200 请求快速发送\n";
$urls = [];
for ($i = 0; $i < 200; $i++) {
    $auth = buildAuthQuery($apiKey, $apiSecret);
    $urls[] = $apiBase . '/lottery_results/latest?' . $auth;
}
$result = runConcurrentRequests($urls);
$rateLimited = 0;
foreach ($result['status_codes'] as $code) {
    if ($code == 429) $rateLimited++;
}
echo "--- 速率限制压力 ---\n";
echo "  总请求: 200\n";
echo "  成功:   {$result['success']}\n";
echo "  429:    $rateLimited (被限流)\n";
echo "  总耗时: " . number_format($result['total_time_ms'], 1) . " ms\n";
echo "  结果:   INFO (仅观察速率限制行为)\n\n";

// ============================================
// 汇总
// ============================================

echo "============================================\n";
echo " 负载测试结果汇总\n";
echo "============================================\n";
echo "通过: $totalPass / " . ($totalPass + $totalFail) . "\n";
echo "失败: $totalFail / " . ($totalPass + $totalFail) . "\n";
echo "============================================\n";

exit($totalFail > 0 ? 1 : 0);
