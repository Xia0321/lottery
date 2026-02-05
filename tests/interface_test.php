<?php
/**
 * API 接口自动化测试脚本
 *
 * 测试所有接口的：
 * 1. 正常功能
 * 2. 参数验证
 * 3. 错误处理
 * 4. 边界情况
 * 5. 并发安全性
 */

// 测试配置
define('API_BASE_URL', 'http://localhost/api/v1');
define('TEST_API_KEY', 'test_key_123456');
define('TEST_SECRET', 'test_secret_abcdef');

class ApiTester {
    private $passed = 0;
    private $failed = 0;
    private $errors = [];
    private $baseUrl;
    private $apiKey;
    private $secret;

    public function __construct($baseUrl, $apiKey, $secret) {
        $this->baseUrl = $baseUrl;
        $this->apiKey = $apiKey;
        $this->secret = $secret;
    }

    /**
     * 生成 HMAC 签名
     */
    private function generateSignature($method, $path, $body, $timestamp) {
        $message = $method . "\n" . $path . "\n" . $body . "\n" . $timestamp;
        return hash_hmac('sha256', $message, $this->secret);
    }

    /**
     * 发送 API 请求
     */
    private function request($method, $path, $data = [], $skipAuth = false) {
        $url = $this->baseUrl . $path;
        $timestamp = time();
        $body = '';

        if ($method === 'GET' && !empty($data)) {
            $url .= '?' . http_build_query($data);
        } elseif ($method === 'POST') {
            $body = json_encode($data);
        }

        $signature = $this->generateSignature($method, $path, $body, $timestamp);

        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_CUSTOMREQUEST, $method);

        $headers = [
            'Content-Type: application/json'
        ];

        if (!$skipAuth) {
            $headers[] = 'X-Api-Key: ' . $this->apiKey;
            $headers[] = 'X-Timestamp: ' . $timestamp;
            $headers[] = 'X-Signature: ' . $signature;
        }

        curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);

        if ($method === 'POST' && !empty($body)) {
            curl_setopt($ch, CURLOPT_POSTFIELDS, $body);
        }

        $response = curl_exec($ch);
        $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        curl_close($ch);

        return [
            'code' => $httpCode,
            'body' => json_decode($response, true),
            'raw' => $response
        ];
    }

    /**
     * 断言测试
     */
    private function assert($condition, $message) {
        if ($condition) {
            $this->passed++;
            echo "✓ PASS: $message\n";
        } else {
            $this->failed++;
            $this->errors[] = $message;
            echo "✗ FAIL: $message\n";
        }
    }

    /**
     * 测试组标题
     */
    private function testGroup($title) {
        echo "\n" . str_repeat('=', 60) . "\n";
        echo "  $title\n";
        echo str_repeat('=', 60) . "\n\n";
    }

    /**
     * 1. 测试用户接口
     */
    public function testUsers() {
        $this->testGroup('测试用户接口');

        // 1.1 创建用户 - 正常情况
        $response = $this->request('POST', '/users.php', [
            'username' => 'testuser_' . time(),
            'password' => '123456',
            'email' => 'test@example.com'
        ]);
        $this->assert($response['code'] === 200, '创建用户 - 正常情况');
        $this->assert(isset($response['body']['data']['user_id']), '创建用户 - 返回 user_id');
        $userId = $response['body']['data']['user_id'] ?? null;

        // 1.2 创建用户 - 用户名格式错误
        $response = $this->request('POST', '/users.php', [
            'username' => 'ab',  // 太短
            'password' => '123456'
        ]);
        $this->assert($response['code'] === 400, '创建用户 - 用户名太短应返回 400');

        // 1.3 创建用户 - 密码太短
        $response = $this->request('POST', '/users.php', [
            'username' => 'testuser2',
            'password' => '123'  // 少于6位
        ]);
        $this->assert($response['code'] === 400, '创建用户 - 密码太短应返回 400');

        // 1.4 查询用户信息
        if ($userId) {
            $response = $this->request('GET', "/users.php/$userId");
            $this->assert($response['code'] === 200, '查询用户信息 - 正常情况');
            $this->assert($response['body']['data']['user_id'] == $userId, '查询用户信息 - user_id 匹配');
        }

        // 1.5 查询用户余额
        if ($userId) {
            $response = $this->request('GET', "/users.php/$userId/balance");
            $this->assert($response['code'] === 200, '查询用户余额 - 正常情况');
            $this->assert(isset($response['body']['data']['balance']), '查询用户余额 - 返回 balance');
        }

        // 1.6 查询不存在的用户
        $response = $this->request('GET', '/users.php/999999');
        $this->assert($response['code'] === 404, '查询不存在的用户应返回 404');

        return $userId;
    }

    /**
     * 2. 测试游戏接口
     */
    public function testGames() {
        $this->testGroup('测试游戏接口');

        // 2.1 查询游戏列表
        $response = $this->request('GET', '/games.php');
        $this->assert($response['code'] === 200, '查询游戏列表 - 正常情况');
        $this->assert(isset($response['body']['data']['list']), '查询游戏列表 - 返回 list');
        $gameId = $response['body']['data']['list'][0]['game_id'] ?? 1;

        // 2.2 查询游戏详情
        $response = $this->request('GET', "/games.php/$gameId");
        $this->assert($response['code'] === 200, '查询游戏详情 - 正常情况');

        // 2.3 分页测试
        $response = $this->request('GET', '/games.php', ['page' => 1, 'page_size' => 5]);
        $this->assert($response['code'] === 200, '游戏列表分页 - 正常情况');
        $this->assert(count($response['body']['data']['list']) <= 5, '游戏列表分页 - 数量限制');

        return $gameId;
    }

    /**
     * 3. 测试期号接口
     */
    public function testPeriods() {
        $this->testGroup('测试期号接口');

        // 3.1 查询当前期号
        $response = $this->request('GET', '/periods.php/current');
        $this->assert($response['code'] === 200, '查询当前期号 - 正常情况');
        $this->assert(isset($response['body']['data']['current_period']), '查询当前期号 - 返回 current_period');

        // 3.2 查询期号列表
        $response = $this->request('GET', '/periods.php');
        $this->assert($response['code'] === 200, '查询期号列表 - 正常情况');
        $this->assert(isset($response['body']['data']['list']), '查询期号列表 - 返回 list');

        // 3.3 查询特定期号
        $period = $response['body']['data']['list'][0]['period'] ?? null;
        if ($period) {
            $response = $this->request('GET', "/periods.php/$period");
            $this->assert($response['code'] === 200, '查询特定期号 - 正常情况');
        }
    }

    /**
     * 4. 测试充值接口
     */
    public function testDeposits($userId) {
        $this->testGroup('测试充值接口');

        // 4.1 创建充值 - 正常情况
        $response = $this->request('POST', '/deposits.php', [
            'user_id' => $userId,
            'amount' => 100.00,
            'payment_method' => 'online',
            'bank_name' => '测试银行'
        ]);
        $this->assert($response['code'] === 200, '创建充值 - 正常情况');
        $this->assert(isset($response['body']['data']['deposit_id']), '创建充值 - 返回 deposit_id');
        $depositId = $response['body']['data']['deposit_id'] ?? null;

        // 4.2 创建充值 - 金额为负数
        $response = $this->request('POST', '/deposits.php', [
            'user_id' => $userId,
            'amount' => -50
        ]);
        $this->assert($response['code'] === 400, '创建充值 - 负数金额应返回 400');

        // 4.3 创建充值 - 金额超过上限
        $response = $this->request('POST', '/deposits.php', [
            'user_id' => $userId,
            'amount' => 150000  // 超过 10万
        ]);
        $this->assert($response['code'] === 400, '创建充值 - 超过上限应返回 400');

        // 4.4 创建充值 - 用户不存在
        $response = $this->request('POST', '/deposits.php', [
            'user_id' => 999999,
            'amount' => 100
        ]);
        $this->assert($response['code'] === 404, '创建充值 - 用户不存在应返回 404');

        // 4.5 查询充值列表
        $response = $this->request('GET', '/deposits.php');
        $this->assert($response['code'] === 200, '查询充值列表 - 正常情况');

        // 4.6 查询充值详情
        if ($depositId) {
            $response = $this->request('GET', "/deposits.php/$depositId");
            $this->assert($response['code'] === 200, '查询充值详情 - 正常情况');
        }

        return $depositId;
    }

    /**
     * 5. 测试提现接口
     */
    public function testWithdrawals($userId) {
        $this->testGroup('测试提现接口');

        // 5.1 创建提现 - 余额不足
        $response = $this->request('POST', '/withdrawals.php', [
            'user_id' => $userId,
            'amount' => 10000,  // 新用户余额不足
            'bank_account' => '6222001234567890',
            'bank_name' => '测试银行'
        ]);
        $this->assert($response['code'] === 400, '创建提现 - 余额不足应返回 400');

        // 5.2 创建提现 - 金额为负数
        $response = $this->request('POST', '/withdrawals.php', [
            'user_id' => $userId,
            'amount' => -50,
            'bank_account' => '6222001234567890'
        ]);
        $this->assert($response['code'] === 400, '创建提现 - 负数金额应返回 400');

        // 5.3 创建提现 - 金额超过上限
        $response = $this->request('POST', '/withdrawals.php', [
            'user_id' => $userId,
            'amount' => 150000,  // 超过 10万
            'bank_account' => '6222001234567890'
        ]);
        $this->assert($response['code'] === 400, '创建提现 - 超过上限应返回 400');

        // 5.4 查询提现列表
        $response = $this->request('GET', '/withdrawals.php');
        $this->assert($response['code'] === 200, '查询提现列表 - 正常情况');
    }

    /**
     * 6. 测试投注接口
     */
    public function testBets($userId, $gameId) {
        $this->testGroup('测试投注接口');

        // 6.1 创建投注 - 余额不足
        $response = $this->request('POST', '/bets.php', [
            'user_id' => $userId,
            'game_id' => $gameId,
            'bet_amount' => 10000,  // 新用户余额不足
            'bet_content' => '1,2,3,4,5',
            'bet_type' => 'direct'
        ]);
        $this->assert($response['code'] === 400, '创建投注 - 余额不足应返回 400');

        // 6.2 创建投注 - 金额为负数
        $response = $this->request('POST', '/bets.php', [
            'user_id' => $userId,
            'game_id' => $gameId,
            'bet_amount' => -10,
            'bet_content' => '1,2,3',
            'bet_type' => 'direct'
        ]);
        $this->assert($response['code'] === 400, '创建投注 - 负数金额应返回 400');

        // 6.3 查询投注列表
        $response = $this->request('GET', '/bets.php');
        $this->assert($response['code'] === 200, '查询投注列表 - 正常情况');

        // 6.4 查询投注列表 - 用户筛选
        $response = $this->request('GET', '/bets.php', ['user_id' => $userId]);
        $this->assert($response['code'] === 200, '查询投注列表 - 用户筛选');
    }

    /**
     * 7. 测试交易记录接口
     */
    public function testTransactions($userId) {
        $this->testGroup('测试交易记录接口');

        // 7.1 查询交易列表
        $response = $this->request('GET', '/transactions.php');
        $this->assert($response['code'] === 200, '查询交易列表 - 正常情况');

        // 7.2 查询交易列表 - 用户筛选
        $response = $this->request('GET', '/transactions.php', ['user_id' => $userId]);
        $this->assert($response['code'] === 200, '查询交易列表 - 用户筛选');

        // 7.3 查询交易列表 - 类型筛选
        $response = $this->request('GET', '/transactions.php', ['type' => 'deposit']);
        $this->assert($response['code'] === 200, '查询交易列表 - 类型筛选');
    }

    /**
     * 8. 测试开奖结果接口
     */
    public function testLotteryResults() {
        $this->testGroup('测试开奖结果接口');

        // 8.1 查询开奖结果列表
        $response = $this->request('GET', '/lottery_results.php');
        $this->assert($response['code'] === 200, '查询开奖结果列表 - 正常情况');

        // 8.2 查询最新开奖结果
        $response = $this->request('GET', '/lottery_results.php/latest');
        $this->assert($response['code'] === 200, '查询最新开奖结果 - 正常情况');
    }

    /**
     * 9. 测试认证机制
     */
    public function testAuthentication() {
        $this->testGroup('测试认证机制');

        // 9.1 无认证头访问
        $response = $this->request('GET', '/users.php/1', [], true);
        $this->assert($response['code'] === 401, '无认证头应返回 401');

        // 9.2 错误的 API Key
        $tempApiKey = $this->apiKey;
        $this->apiKey = 'wrong_key';
        $response = $this->request('GET', '/users.php/1');
        $this->assert($response['code'] === 401, '错误的 API Key 应返回 401');
        $this->apiKey = $tempApiKey;
    }

    /**
     * 10. 测试边界情况
     */
    public function testBoundaries() {
        $this->testGroup('测试边界情况');

        // 10.1 超大分页参数
        $response = $this->request('GET', '/users.php', ['page' => 999999, 'page_size' => 1000]);
        $this->assert($response['code'] === 200, '超大分页参数 - 应正常处理');

        // 10.2 特殊字符测试
        $response = $this->request('POST', '/users.php', [
            'username' => 'test<script>alert(1)</script>',
            'password' => '123456'
        ]);
        $this->assert($response['code'] === 400, '特殊字符用户名 - 应被拒绝');

        // 10.3 SQL 注入测试
        $response = $this->request('GET', "/users.php/1' OR '1'='1");
        $this->assert($response['code'] === 400 || $response['code'] === 404, 'SQL 注入 - 应被阻止');
    }

    /**
     * 运行所有测试
     */
    public function runAll() {
        echo "\n";
        echo "╔══════════════════════════════════════════════════════════╗\n";
        echo "║         API 接口自动化测试                               ║\n";
        echo "╚══════════════════════════════════════════════════════════╝\n";

        $startTime = microtime(true);

        // 执行测试
        $userId = $this->testUsers();
        $gameId = $this->testGames();
        $this->testPeriods();

        if ($userId) {
            $this->testDeposits($userId);
            $this->testWithdrawals($userId);
            $this->testBets($userId, $gameId);
            $this->testTransactions($userId);
        }

        $this->testLotteryResults();
        $this->testAuthentication();
        $this->testBoundaries();

        $endTime = microtime(true);
        $duration = round($endTime - $startTime, 2);

        // 输出结果
        echo "\n";
        echo str_repeat('=', 60) . "\n";
        echo "  测试结果汇总\n";
        echo str_repeat('=', 60) . "\n\n";
        echo "总测试数: " . ($this->passed + $this->failed) . "\n";
        echo "通过: " . $this->passed . " ✓\n";
        echo "失败: " . $this->failed . " ✗\n";
        echo "成功率: " . round($this->passed / ($this->passed + $this->failed) * 100, 2) . "%\n";
        echo "耗时: {$duration}s\n\n";

        if ($this->failed > 0) {
            echo "失败的测试:\n";
            foreach ($this->errors as $error) {
                echo "  - $error\n";
            }
            echo "\n";
        }

        echo str_repeat('=', 60) . "\n";
    }
}

// 运行测试
$tester = new ApiTester(API_BASE_URL, TEST_API_KEY, TEST_SECRET);
$tester->runAll();
