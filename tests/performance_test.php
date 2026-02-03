<?php
/**
 * API 性能测试脚本
 *
 * 使用方法：php tests/performance_test.php
 *
 * 测试内容：
 * - 响应时间测试
 * - 并发性能测试
 * - Redis 缓存效果测试
 * - 数据库查询性能
 */

class PerformanceTester {

    private $baseUrl;
    private $apiKey;
    private $apiSecret;
    private $results = [];

    public function __construct($baseUrl, $apiKey, $apiSecret) {
        $this->baseUrl = rtrim($baseUrl, '/');
        $this->apiKey = $apiKey;
        $this->apiSecret = $apiSecret;
    }

    /**
     * 运行所有性能测试
     */
    public function runAllTests() {
        echo "=================================\n";
        echo "     API 性能测试开始\n";
        echo "=================================\n\n";

        $this->testResponseTime();
        $this->testCachePerformance();
        $this->testConcurrency();
        $this->testDatabasePerformance();

        $this->printResults();
    }

    /**
     * 响应时间测试
     */
    private function testResponseTime() {
        echo "📋 测试: 响应时间\n\n";

        $endpoints = [
            ['GET', '/api/v1/test.php', []],
            ['GET', '/api/v1/users/1', []],
            ['GET', '/api/v1/bets', ['page' => 1, 'page_size' => 20]],
            ['GET', '/api/v1/transactions', ['page' => 1, 'page_size' => 20]],
            ['GET', '/api/v1/lottery-results/latest', []],
        ];

        $times = [];

        foreach ($endpoints as $endpoint) {
            list($method, $url, $params) = $endpoint;

            $totalTime = 0;
            $iterations = 10;

            for ($i = 0; $i < $iterations; $i++) {
                $start = microtime(true);
                $this->request($method, $url, $params);
                $time = microtime(true) - $start;
                $totalTime += $time;
            }

            $avgTime = $totalTime / $iterations;
            $times[$url] = $avgTime;

            $status = $avgTime < 0.5 ? '✅' : ($avgTime < 1.0 ? '⚠️' : '❌');

            echo sprintf(
                "  %s %s: %.2f ms (平均)\n",
                $status,
                $url,
                $avgTime * 1000
            );

            $this->results[] = [
                'test' => 'response_time',
                'endpoint' => $url,
                'time_ms' => $avgTime * 1000,
                'status' => $avgTime < 1.0 ? 'good' : 'slow'
            ];
        }

        echo "\n";
    }

    /**
     * 缓存性能测试
     */
    private function testCachePerformance() {
        echo "📋 测试: Redis 缓存性能\n\n";

        // 清除缓存（通过等待 TTL 过期）
        echo "  ⏳ 等待缓存过期（35秒）...\n";
        sleep(35);

        // 第一次请求（冷缓存）
        $start = microtime(true);
        $this->request('GET', '/api/v1/lottery-results/latest');
        $coldTime = microtime(true) - $start;

        // 第二次请求（热缓存）
        $start = microtime(true);
        $this->request('GET', '/api/v1/lottery-results/latest');
        $hotTime = microtime(true) - $start;

        $improvement = (($coldTime - $hotTime) / $coldTime) * 100;

        echo sprintf("  冷缓存: %.2f ms\n", $coldTime * 1000);
        echo sprintf("  热缓存: %.2f ms\n", $hotTime * 1000);
        echo sprintf("  性能提升: %.1f%%\n", $improvement);

        $status = $improvement > 20 ? '✅' : ($improvement > 0 ? '⚠️' : '❌');
        echo "  " . $status . " 缓存效果: " . ($improvement > 20 ? '优秀' : ($improvement > 0 ? '一般' : '无效')) . "\n";

        $this->results[] = [
            'test' => 'cache_performance',
            'cold_ms' => $coldTime * 1000,
            'hot_ms' => $hotTime * 1000,
            'improvement' => $improvement,
            'status' => $improvement > 20 ? 'excellent' : 'poor'
        ];

        echo "\n";
    }

    /**
     * 并发性能测试
     */
    private function testConcurrency() {
        echo "📋 测试: 并发性能\n\n";

        $concurrentRequests = 20;
        $endpoint = '/api/v1/test.php';

        echo "  发送 " . $concurrentRequests . " 个并发请求...\n";

        $start = microtime(true);

        // 使用多进程模拟并发（简化版本）
        $handles = [];

        for ($i = 0; $i < $concurrentRequests; $i++) {
            $handles[] = $this->requestAsync('GET', $endpoint);
        }

        // 等待所有请求完成
        $responses = [];
        foreach ($handles as $handle) {
            $responses[] = curl_multi_getcontent($handle);
        }

        $totalTime = microtime(true) - $start;
        $avgTime = $totalTime / $concurrentRequests;

        echo sprintf("  总时间: %.2f 秒\n", $totalTime);
        echo sprintf("  平均响应时间: %.2f ms\n", $avgTime * 1000);
        echo sprintf("  吞吐量: %.1f 请求/秒\n", $concurrentRequests / $totalTime);

        $status = $avgTime < 0.5 ? '✅' : ($avgTime < 1.0 ? '⚠️' : '❌');
        echo "  " . $status . " 并发性能: " . ($avgTime < 0.5 ? '优秀' : ($avgTime < 1.0 ? '良好' : '需优化')) . "\n";

        $this->results[] = [
            'test' => 'concurrency',
            'concurrent_requests' => $concurrentRequests,
            'total_time' => $totalTime,
            'avg_time_ms' => $avgTime * 1000,
            'throughput' => $concurrentRequests / $totalTime,
            'status' => $avgTime < 1.0 ? 'good' : 'poor'
        ];

        echo "\n";
    }

    /**
     * 数据库性能测试
     */
    private function testDatabasePerformance() {
        echo "📋 测试: 数据库查询性能\n\n";

        $queries = [
            ['name' => '简单查询', 'endpoint' => '/api/v1/users/1'],
            ['name' => '列表查询（小）', 'endpoint' => '/api/v1/bets?page_size=10'],
            ['name' => '列表查询（大）', 'endpoint' => '/api/v1/bets?page_size=100'],
            ['name' => '复杂筛选', 'endpoint' => '/api/v1/bets?status=0&page_size=50'],
        ];

        foreach ($queries as $query) {
            $start = microtime(true);
            $this->request('GET', $query['endpoint']);
            $time = microtime(true) - $start;

            $status = $time < 0.5 ? '✅' : ($time < 1.0 ? '⚠️' : '❌');

            echo sprintf(
                "  %s %s: %.2f ms\n",
                $status,
                $query['name'],
                $time * 1000
            );

            $this->results[] = [
                'test' => 'database_query',
                'query_type' => $query['name'],
                'time_ms' => $time * 1000,
                'status' => $time < 1.0 ? 'good' : 'slow'
            ];
        }

        echo "\n";
    }

    /**
     * 发送同步请求
     */
    private function request($method, $endpoint, $params = []) {
        $timestamp = time();
        $params['api_key'] = $this->apiKey;
        $params['timestamp'] = $timestamp;
        $params['sign'] = $this->generateSignature($timestamp);

        $url = $this->baseUrl . $endpoint;

        $ch = curl_init();

        if ($method === 'GET') {
            $url .= '?' . http_build_query($params);
            curl_setopt($ch, CURLOPT_URL, $url);
        } else {
            curl_setopt($ch, CURLOPT_URL, $url);
            curl_setopt($ch, CURLOPT_POST, true);
            curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query($params));
        }

        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
        curl_setopt($ch, CURLOPT_TIMEOUT, 10);

        $response = curl_exec($ch);
        curl_close($ch);

        return json_decode($response, true);
    }

    /**
     * 发送异步请求（简化版）
     */
    private function requestAsync($method, $endpoint) {
        $timestamp = time();
        $params = [
            'api_key' => $this->apiKey,
            'timestamp' => $timestamp,
            'sign' => $this->generateSignature($timestamp)
        ];

        $url = $this->baseUrl . $endpoint . '?' . http_build_query($params);

        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
        curl_setopt($ch, CURLOPT_TIMEOUT, 10);

        curl_exec($ch);

        return $ch;
    }

    /**
     * 生成签名
     */
    private function generateSignature($timestamp) {
        $signString = $this->apiKey . $timestamp . $this->apiSecret;
        return hash_hmac('sha256', $signString, $this->apiSecret);
    }

    /**
     * 输出结果
     */
    private function printResults() {
        echo "=================================\n";
        echo "      性能测试结果汇总\n";
        echo "=================================\n\n";

        // 响应时间统计
        $responseTimes = array_filter($this->results, function($r) {
            return $r['test'] === 'response_time';
        });

        if (!empty($responseTimes)) {
            $times = array_map(function($r) { return $r['time_ms']; }, $responseTimes);
            $avgTime = array_sum($times) / count($times);
            $maxTime = max($times);
            $minTime = min($times);

            echo "响应时间统计：\n";
            echo sprintf("  平均: %.2f ms\n", $avgTime);
            echo sprintf("  最快: %.2f ms\n", $minTime);
            echo sprintf("  最慢: %.2f ms\n", $maxTime);
            echo "\n";
        }

        // 缓存效果
        $cacheResults = array_filter($this->results, function($r) {
            return $r['test'] === 'cache_performance';
        });

        if (!empty($cacheResults)) {
            $cache = reset($cacheResults);
            echo "缓存效果：\n";
            echo sprintf("  性能提升: %.1f%%\n", $cache['improvement']);
            echo sprintf("  状态: %s\n", $cache['status'] === 'excellent' ? '优秀' : '需优化');
            echo "\n";
        }

        // 并发性能
        $concResults = array_filter($this->results, function($r) {
            return $r['test'] === 'concurrency';
        });

        if (!empty($concResults)) {
            $conc = reset($concResults);
            echo "并发性能：\n";
            echo sprintf("  吞吐量: %.1f 请求/秒\n", $conc['throughput']);
            echo sprintf("  平均响应: %.2f ms\n", $conc['avg_time_ms']);
            echo "\n";
        }

        // 性能评级
        $goodResults = array_filter($this->results, function($r) {
            return isset($r['status']) && $r['status'] === 'good';
        });

        $totalTests = count(array_filter($this->results, function($r) {
            return isset($r['status']);
        }));

        $percentage = $totalTests > 0 ? round((count($goodResults) / $totalTests) * 100, 1) : 0;

        echo "整体评级: " . $this->getRating($percentage) . " (" . $percentage . "%)\n";
        echo "\n";
        echo "=================================\n";
    }

    /**
     * 获取评级
     */
    private function getRating($percentage) {
        if ($percentage >= 90) return '🌟 优秀';
        if ($percentage >= 70) return '✅ 良好';
        if ($percentage >= 50) return '⚠️  一般';
        return '❌ 需优化';
    }
}

// 配置
$config = [
    'base_url' => 'http://localhost/lottery/web',
    'api_key' => 'test_api_key_12345678',
    'api_secret' => 'test_secret_87654321abcdefgh'
];

// 运行测试
$tester = new PerformanceTester($config['base_url'], $config['api_key'], $config['api_secret']);
$tester->runAllTests();
