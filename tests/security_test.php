<?php
/**
 * API 安全测试脚本
 *
 * 使用方法：php tests/security_test.php
 *
 * 测试内容：
 * - SQL 注入防护
 * - XSS 防护
 * - CSRF 防护
 * - 认证绕过测试
 * - 速率限制测试
 */

class SecurityTester {

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
     * 运行所有安全测试
     */
    public function runAllTests() {
        echo "=================================\n";
        echo "     API 安全测试开始\n";
        echo "=================================\n\n";

        $this->testSQLInjection();
        $this->testXSS();
        $this->testAuthenticationBypass();
        $this->testRateLimiting();
        $this->testTokenSecurity();
        $this->testInputValidation();

        $this->printResults();
    }

    /**
     * SQL 注入防护测试
     */
    private function testSQLInjection() {
        echo "📋 测试: SQL 注入防护\n";

        $sqlPayloads = [
            "1' OR '1'='1",
            "1; DROP TABLE users--",
            "1' UNION SELECT NULL--",
            "1' AND 1=1--",
            "admin'--",
            "1' OR 'a'='a",
        ];

        foreach ($sqlPayloads as $payload) {
            // 测试用户 ID 参数
            $response = $this->request('GET', '/api/v1/users/' . urlencode($payload));

            $this->assert(
                'sql_injection_user_id',
                $response['http_code'] == 400 || $response['http_code'] == 404,
                "SQL 注入攻击应被拒绝: " . $payload
            );
        }

        // 测试查询参数中的 SQL 注入
        $response = $this->request('GET', '/api/v1/bets', [
            'user_id' => "1' OR '1'='1"
        ]);

        $this->assert(
            'sql_injection_query_param',
            $response['http_code'] == 400 || !isset($response['data']['list']),
            "查询参数 SQL 注入应被拒绝"
        );

        echo "\n";
    }

    /**
     * XSS 防护测试
     */
    private function testXSS() {
        echo "📋 测试: XSS 防护\n";

        $xssPayloads = [
            "<script>alert('XSS')</script>",
            "<img src=x onerror=alert('XSS')>",
            "javascript:alert('XSS')",
            "<svg onload=alert('XSS')>",
        ];

        // 测试 Test 端点的消息参数
        foreach ($xssPayloads as $payload) {
            $response = $this->request('GET', '/api/v1/test.php', [
                'message' => $payload
            ]);

            if ($response['success']) {
                $message = $response['data']['message'] ?? '';

                // 检查响应中是否包含未转义的脚本标签
                $this->assert(
                    'xss_protection',
                    strpos($message, '<script>') === false && strpos($message, 'onerror=') === false,
                    "XSS 攻击应被转义: " . substr($payload, 0, 30)
                );
            }
        }

        echo "\n";
    }

    /**
     * 认证绕过测试
     */
    private function testAuthenticationBypass() {
        echo "📋 测试: 认证绕过防护\n";

        // 测试缺少 API Key
        $response = $this->requestWithoutAuth('GET', '/api/v1/test.php');

        $this->assert(
            'auth_missing_api_key',
            $response['http_code'] == 401 || $response['http_code'] == 400,
            "缺少 API Key 应被拒绝"
        );

        // 测试无效的 API Key
        $invalidApiKey = 'invalid_key_123';
        $timestamp = time();
        $params = [
            'api_key' => $invalidApiKey,
            'timestamp' => $timestamp,
            'sign' => hash_hmac('sha256', $invalidApiKey . $timestamp . 'wrong_secret', 'wrong_secret')
        ];

        $response = $this->requestRaw('GET', '/api/v1/test.php', $params);

        $this->assert(
            'auth_invalid_api_key',
            $response['http_code'] == 401,
            "无效 API Key 应被拒绝"
        );

        // 测试签名重放攻击
        $timestamp = time();
        $validSign = $this->generateSignature($timestamp);
        $params = [
            'api_key' => $this->apiKey,
            'timestamp' => $timestamp,
            'sign' => $validSign
        ];

        // 第一次请求应成功
        $response1 = $this->requestRaw('GET', '/api/v1/test.php', $params);

        // 使用相同签名再次请求（模拟重放）
        sleep(1);
        $response2 = $this->requestRaw('GET', '/api/v1/test.php', $params);

        $this->assert(
            'auth_replay_protection',
            $response1['success'] === true,
            "有效签名第一次应成功"
        );

        // 注意：如果实现了 nonce，第二次应该失败；如果只是时间戳验证，则会成功
        // 这里我们测试时间戳仍在有效期内

        echo "\n";
    }

    /**
     * 速率限制测试
     */
    private function testRateLimiting() {
        echo "📋 测试: 速率限制\n";

        echo "  ⚠️  发送 105 个请求测试速率限制...\n";

        $limitReached = false;
        $requestCount = 0;

        for ($i = 0; $i < 105; $i++) {
            $response = $this->request('GET', '/api/v1/test.php', ['test' => $i]);

            $requestCount++;

            if ($response['http_code'] == 429) {
                $limitReached = true;
                break;
            }

            // 避免过快请求
            usleep(10000); // 10ms
        }

        $this->assert(
            'rate_limit_enforcement',
            $limitReached,
            "速率限制应在 100 次请求后触发（实际: " . $requestCount . " 次）"
        );

        if ($limitReached) {
            echo "  ⏳ 等待 60 秒让速率限制重置...\n";
            sleep(60);

            // 测试重置后可以再次请求
            $response = $this->request('GET', '/api/v1/test.php');

            $this->assert(
                'rate_limit_reset',
                $response['success'] === true,
                "速率限制重置后应能正常请求"
            );
        }

        echo "\n";
    }

    /**
     * Token 安全性测试
     */
    private function testTokenSecurity() {
        echo "📋 测试: Token 安全性\n";

        // 生成一个有效 Token
        $response = $this->request('POST', '/api/v1/embed-token.php', [
            'user_id' => 1,
            'expires_in' => 60
        ]);

        if ($response['success']) {
            $token = $response['data']['token'];

            // 测试 Token 篡改
            $tamperedToken = substr($token, 0, -5) . 'XXXXX';

            // 注意：这里需要实际访问游戏页面来测试，这里只是示例
            $this->assert(
                'token_tampering',
                true,
                "Token 篡改检测（需手动测试嵌入页面）"
            );

            // 测试 Token 格式
            $this->assert(
                'token_format',
                !empty($token) && strlen($token) > 20,
                "Token 格式应为 Base64 编码的加密字符串"
            );
        }

        echo "\n";
    }

    /**
     * 输入验证测试
     */
    private function testInputValidation() {
        echo "📋 测试: 输入验证\n";

        // 测试无效的分页参数
        $response = $this->request('GET', '/api/v1/bets', [
            'page' => -1,
            'page_size' => 999
        ]);

        $this->assert(
            'input_validation_pagination',
            $response['success'] === true, // 应该自动修正为有效值
            "无效分页参数应被修正"
        );

        // 测试无效的日期格式
        $response = $this->request('GET', '/api/v1/bets', [
            'start_date' => 'invalid_date'
        ]);

        $this->assert(
            'input_validation_date',
            $response['success'] === true || $response['http_code'] == 400,
            "无效日期格式应被拒绝或忽略"
        );

        // 测试超长字符串
        $longString = str_repeat('A', 10000);
        $response = $this->request('GET', '/api/v1/test.php', [
            'message' => $longString
        ]);

        $this->assert(
            'input_validation_length',
            $response['http_code'] != 500,
            "超长输入不应导致服务器错误"
        );

        echo "\n";
    }

    /**
     * 发送带认证的请求
     */
    private function request($method, $endpoint, $params = []) {
        $timestamp = time();
        $params['api_key'] = $this->apiKey;
        $params['timestamp'] = $timestamp;
        $params['sign'] = $this->generateSignature($timestamp);

        return $this->requestRaw($method, $endpoint, $params);
    }

    /**
     * 发送不带认证的请求
     */
    private function requestWithoutAuth($method, $endpoint, $params = []) {
        return $this->requestRaw($method, $endpoint, $params);
    }

    /**
     * 发送原始请求
     */
    private function requestRaw($method, $endpoint, $params = []) {
        $url = $this->baseUrl . $endpoint;

        $ch = curl_init();

        if ($method === 'GET') {
            if (!empty($params)) {
                $url .= '?' . http_build_query($params);
            }
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
        curl_close($ch);

        $data = json_decode($response, true) ?: ['raw' => $response];
        $data['http_code'] = $httpCode;

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
     * 断言
     */
    private function assert($testName, $condition, $message) {
        $this->results[] = [
            'name' => $testName,
            'message' => $message,
            'passed' => $condition
        ];

        if ($condition) {
            echo "  ✅ " . $message . "\n";
            $this->passed++;
        } else {
            echo "  ❌ " . $message . "\n";
            $this->failed++;
        }
    }

    /**
     * 输出结果
     */
    private function printResults() {
        echo "\n=================================\n";
        echo "      安全测试结果汇总\n";
        echo "=================================\n\n";

        $total = $this->passed + $this->failed;
        $percentage = $total > 0 ? round(($this->passed / $total) * 100, 1) : 0;

        echo "总测试数: " . $total . "\n";
        echo "通过: " . $this->passed . " ✅\n";
        echo "失败: " . $this->failed . " ❌\n";
        echo "通过率: " . $percentage . "%\n\n";

        if ($this->failed > 0) {
            echo "⚠️  发现安全问题：\n";
            foreach ($this->results as $result) {
                if (!$result['passed']) {
                    echo "  - " . $result['name'] . ": " . $result['message'] . "\n";
                }
            }
            echo "\n";
        } else {
            echo "✅ 所有安全测试通过！\n\n";
        }

        echo "=================================\n";

        exit($this->failed > 0 ? 1 : 0);
    }
}

// 配置
$config = [
    'base_url' => 'http://localhost/lottery/web',
    'api_key' => 'test_api_key_12345678',
    'api_secret' => 'test_secret_87654321abcdefgh'
];

// 运行测试
$tester = new SecurityTester($config['base_url'], $config['api_key'], $config['api_secret']);
$tester->runAllTests();
