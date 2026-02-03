<?php
/**
 * API 功能测试脚本
 *
 * 使用方法：php tests/api_test.php
 *
 * 环境要求：
 * - PHP 7.4+
 * - cURL 扩展
 * - 已配置的 API Key 和 Secret
 */

class ApiTester {

    private $baseUrl;
    private $apiKey;
    private $apiSecret;
    private $results = [];
    private $passed = 0;
    private $failed = 0;

    public function __construct($baseUrl, $apiKey, $apiSecret) {
        $this->baseUrl = rtrim($baseUrl, '/');
        $this->apiKey = $apiKey;
        $this->apiSecret = $apiSecret;
    }

    /**
     * 运行所有测试
     */
    public function runAllTests() {
        echo "=================================\n";
        echo "     API 功能测试开始\n";
        echo "=================================\n\n";

        // 测试认证
        $this->testAuthentication();

        // 测试查询接口
        $this->testTestEndpoint();
        $this->testUsersEndpoint();
        $this->testBetsEndpoint();
        $this->testTransactionsEndpoint();
        $this->testLotteryResultsEndpoint();

        // 测试嵌入功能
        $this->testEmbedTokenGeneration();

        // 测试 Webhook
        $this->testWebhookCreation();

        // 输出结果
        $this->printResults();
    }

    /**
     * 测试认证功能
     */
    private function testAuthentication() {
        echo "📋 测试: API 认证\n";

        // 测试无签名请求
        $response = $this->request('GET', '/api/v1/test.php', [], false);
        $this->assert(
            'auth_missing_signature',
            $response['http_code'] == 401 || $response['http_code'] == 400,
            "缺少签名应返回 401/400"
        );

        // 测试错误签名
        $params = [
            'api_key' => $this->apiKey,
            'timestamp' => time(),
            'sign' => 'invalid_signature'
        ];
        $response = $this->requestRaw('GET', '/api/v1/test.php', $params);
        $this->assert(
            'auth_invalid_signature',
            $response['http_code'] == 401,
            "错误签名应返回 401"
        );

        // 测试过期时间戳
        $timestamp = time() - 400; // 6分40秒前
        $params = [
            'api_key' => $this->apiKey,
            'timestamp' => $timestamp,
            'sign' => $this->generateSignature($timestamp)
        ];
        $response = $this->requestRaw('GET', '/api/v1/test.php', $params);
        $this->assert(
            'auth_expired_timestamp',
            $response['http_code'] == 401,
            "过期时间戳应返回 401"
        );

        echo "\n";
    }

    /**
     * 测试 test 端点
     */
    private function testTestEndpoint() {
        echo "📋 测试: Test 端点\n";

        $response = $this->request('GET', '/api/v1/test.php', ['message' => 'Hello']);

        $this->assert(
            'test_success',
            $response['success'] === true,
            "Test 端点应返回成功"
        );

        $this->assert(
            'test_message',
            isset($response['data']['message']) && $response['data']['message'] == 'Hello',
            "Test 端点应返回自定义消息"
        );

        $this->assert(
            'test_authenticated',
            $response['data']['authenticated'] === true,
            "Test 端点应显示已认证"
        );

        echo "\n";
    }

    /**
     * 测试用户查询端点
     */
    private function testUsersEndpoint() {
        echo "📋 测试: 用户查询端点\n";

        // 测试查询用户信息
        $response = $this->request('GET', '/api/v1/users/1');

        $this->assert(
            'users_get_by_id',
            $response['success'] === true || $response['http_code'] == 404,
            "查询用户应成功或返回 404"
        );

        if ($response['success']) {
            $this->assert(
                'users_data_structure',
                isset($response['data']['user_id']) && isset($response['data']['username']),
                "用户数据应包含 user_id 和 username"
            );
        }

        // 测试查询用户余额
        $response = $this->request('GET', '/api/v1/users/1/balance');

        $this->assert(
            'users_get_balance',
            $response['success'] === true || $response['http_code'] == 404,
            "查询余额应成功或返回 404"
        );

        if ($response['success']) {
            $this->assert(
                'users_balance_structure',
                isset($response['data']['balance']) && is_numeric($response['data']['balance']),
                "余额数据应包含数值型 balance"
            );
        }

        echo "\n";
    }

    /**
     * 测试投注记录端点
     */
    private function testBetsEndpoint() {
        echo "📋 测试: 投注记录端点\n";

        // 测试查询投注列表
        $response = $this->request('GET', '/api/v1/bets', [
            'page' => 1,
            'page_size' => 10
        ]);

        $this->assert(
            'bets_list',
            $response['success'] === true,
            "查询投注列表应成功"
        );

        if ($response['success']) {
            $this->assert(
                'bets_pagination',
                isset($response['data']['list']) && isset($response['data']['pagination']),
                "投注列表应包含分页信息"
            );

            $this->assert(
                'bets_list_array',
                is_array($response['data']['list']),
                "投注列表应为数组"
            );
        }

        // 测试带筛选的查询
        $response = $this->request('GET', '/api/v1/bets', [
            'status' => 0,
            'page' => 1
        ]);

        $this->assert(
            'bets_filter_status',
            $response['success'] === true,
            "带状态筛选的查询应成功"
        );

        echo "\n";
    }

    /**
     * 测试交易记录端点
     */
    private function testTransactionsEndpoint() {
        echo "📋 测试: 交易记录端点\n";

        // 测试查询交易列表
        $response = $this->request('GET', '/api/v1/transactions', [
            'page' => 1,
            'page_size' => 10
        ]);

        $this->assert(
            'transactions_list',
            $response['success'] === true,
            "查询交易列表应成功"
        );

        if ($response['success']) {
            $this->assert(
                'transactions_pagination',
                isset($response['data']['list']) && isset($response['data']['pagination']),
                "交易列表应包含分页信息"
            );
        }

        echo "\n";
    }

    /**
     * 测试开奖结果端点
     */
    private function testLotteryResultsEndpoint() {
        echo "📋 测试: 开奖结果端点\n";

        // 测试查询最新开奖
        $response = $this->request('GET', '/api/v1/lottery-results/latest');

        $this->assert(
            'lottery_latest',
            $response['success'] === true || $response['http_code'] == 404,
            "查询最新开奖应成功或返回 404"
        );

        if ($response['success']) {
            $this->assert(
                'lottery_latest_structure',
                isset($response['data']['result_id']) && isset($response['data']['result']),
                "开奖数据应包含 result_id 和 result"
            );
        }

        // 测试查询历史开奖
        $response = $this->request('GET', '/api/v1/lottery-results', [
            'page' => 1,
            'page_size' => 10
        ]);

        $this->assert(
            'lottery_history',
            $response['success'] === true,
            "查询历史开奖应成功"
        );

        // 测试 Redis 缓存（两次请求最新开奖，第二次应该更快）
        $start1 = microtime(true);
        $this->request('GET', '/api/v1/lottery-results/latest');
        $time1 = microtime(true) - $start1;

        $start2 = microtime(true);
        $this->request('GET', '/api/v1/lottery-results/latest');
        $time2 = microtime(true) - $start2;

        $this->assert(
            'lottery_cache',
            $time2 <= $time1,
            "缓存应使第二次请求更快或相同 (第一次: " . round($time1 * 1000, 2) . "ms, 第二次: " . round($time2 * 1000, 2) . "ms)"
        );

        echo "\n";
    }

    /**
     * 测试嵌入 Token 生成
     */
    private function testEmbedTokenGeneration() {
        echo "📋 测试: 游戏嵌入 Token\n";

        // 测试生成 Token
        $response = $this->request('POST', '/api/v1/embed-token.php', [
            'user_id' => 1,
            'game_id' => 1,
            'expires_in' => 300
        ]);

        $this->assert(
            'embed_token_generation',
            $response['success'] === true || $response['http_code'] == 404,
            "生成嵌入 Token 应成功或返回 404（用户不存在）"
        );

        if ($response['success']) {
            $this->assert(
                'embed_token_structure',
                isset($response['data']['token']) && isset($response['data']['embed_url']),
                "Token 数据应包含 token 和 embed_url"
            );

            $this->assert(
                'embed_token_expires',
                isset($response['data']['expires_at']),
                "Token 数据应包含过期时间"
            );
        }

        echo "\n";
    }

    /**
     * 测试 Webhook 创建
     */
    private function testWebhookCreation() {
        echo "📋 测试: Webhook 功能\n";

        // 测试创建 Webhook
        $response = $this->request('POST', '/api/v1/webhooks.php', [
            'event_type' => 'bet.created',
            'callback_url' => 'https://example.com/webhook',
            'description' => 'Test webhook'
        ]);

        $this->assert(
            'webhook_creation',
            $response['success'] === true,
            "创建 Webhook 应成功"
        );

        if ($response['success']) {
            $this->assert(
                'webhook_has_secret',
                isset($response['data']['secret']),
                "Webhook 应返回 secret"
            );

            // 保存 webhook ID 用于后续测试
            $webhookId = $response['data']['id'] ?? null;

            // 测试查询 Webhook 列表
            $response = $this->request('GET', '/api/v1/webhooks.php');

            $this->assert(
                'webhook_list',
                $response['success'] === true,
                "查询 Webhook 列表应成功"
            );
        }

        echo "\n";
    }

    /**
     * 发送 HTTP 请求（自动添加签名）
     */
    private function request($method, $endpoint, $params = [], $withAuth = true) {
        if ($withAuth) {
            $timestamp = time();
            $params['api_key'] = $this->apiKey;
            $params['timestamp'] = $timestamp;
            $params['sign'] = $this->generateSignature($timestamp);
        }

        return $this->requestRaw($method, $endpoint, $params);
    }

    /**
     * 发送原始 HTTP 请求
     */
    private function requestRaw($method, $endpoint, $params = []) {
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
        $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        $error = curl_error($ch);
        curl_close($ch);

        $data = json_decode($response, true);

        if ($data === null) {
            $data = ['raw' => $response];
        }

        $data['http_code'] = $httpCode;
        $data['curl_error'] = $error;

        return $data;
    }

    /**
     * 生成签名
     */
    private function generateSignature($timestamp) {
        $signString = $this->apiKey . $timestamp . $this->apiSecret;
        return hash_hmac('sha256', $signString, $this->apiSecret);
    }

    /**
     * 断言测试结果
     */
    private function assert($testName, $condition, $message) {
        $result = [
            'name' => $testName,
            'message' => $message,
            'passed' => $condition
        ];

        $this->results[] = $result;

        if ($condition) {
            echo "  ✅ " . $message . "\n";
            $this->passed++;
        } else {
            echo "  ❌ " . $message . "\n";
            $this->failed++;
        }
    }

    /**
     * 输出测试结果
     */
    private function printResults() {
        echo "\n=================================\n";
        echo "        测试结果汇总\n";
        echo "=================================\n\n";

        $total = $this->passed + $this->failed;
        $percentage = $total > 0 ? round(($this->passed / $total) * 100, 1) : 0;

        echo "总测试数: " . $total . "\n";
        echo "通过: " . $this->passed . " ✅\n";
        echo "失败: " . $this->failed . " ❌\n";
        echo "通过率: " . $percentage . "%\n\n";

        if ($this->failed > 0) {
            echo "失败的测试：\n";
            foreach ($this->results as $result) {
                if (!$result['passed']) {
                    echo "  - " . $result['name'] . ": " . $result['message'] . "\n";
                }
            }
            echo "\n";
        }

        echo "=================================\n";

        // 退出代码
        exit($this->failed > 0 ? 1 : 0);
    }
}

// 配置
$config = [
    'base_url' => 'http://localhost/lottery/web',  // 修改为你的 API 地址
    'api_key' => 'test_api_key_12345678',          // 修改为你的 API Key
    'api_secret' => 'test_secret_87654321abcdefgh' // 修改为你的 API Secret
];

// 运行测试
$tester = new ApiTester($config['base_url'], $config['api_key'], $config['api_secret']);
$tester->runAllTests();
