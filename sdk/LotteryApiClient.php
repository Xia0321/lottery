<?php
/**
 * 彩票系统 PHP SDK
 *
 * 用法：
 *   $client = new LotteryApiClient('your_api_key', 'your_api_secret', 'https://your-domain.com');
 *   $result = $client->getLatestResult();
 *   $bets = $client->getBets(['user_id' => 100, 'page' => 1]);
 */

class LotteryApiClient {

    private $apiKey;
    private $apiSecret;
    private $baseUrl;
    private $timeout;
    private $lastResponse;
    private $lastHttpCode;

    /**
     * @param string $apiKey API Key
     * @param string $apiSecret API Secret
     * @param string $baseUrl 基础 URL（不含 /api/v1）
     * @param int $timeout 请求超时（秒）
     */
    public function __construct($apiKey, $apiSecret, $baseUrl = 'http://localhost', $timeout = 10) {
        $this->apiKey = $apiKey;
        $this->apiSecret = $apiSecret;
        $this->baseUrl = rtrim($baseUrl, '/') . '/api/v1';
        $this->timeout = $timeout;
    }

    // ============================================
    // 开奖结果
    // ============================================

    /**
     * 查询最新开奖结果
     * @param int|null $gameId 游戏 ID
     * @return array|null
     */
    public function getLatestResult($gameId = null) {
        $params = [];
        if ($gameId !== null) $params['game_id'] = $gameId;
        return $this->get('/lottery_results/latest', $params);
    }

    /**
     * 查询历史开奖列表
     * @param array $params [game_id, start_date, end_date, page, page_size]
     * @return array|null
     */
    public function getResults($params = []) {
        return $this->get('/lottery_results', $params);
    }

    /**
     * 查询指定期号
     * @param string $period 期号
     * @return array|null
     */
    public function getResultByPeriod($period) {
        return $this->get('/lottery_results/' . urlencode($period));
    }

    // ============================================
    // 投注
    // ============================================

    /**
     * 查询投注列表
     * @param array $params [user_id, game_id, status, start_date, end_date, page, page_size]
     * @return array|null
     */
    public function getBets($params = []) {
        return $this->get('/bets', $params);
    }

    /**
     * 查询投注详情
     * @param int $betId
     * @return array|null
     */
    public function getBet($betId) {
        return $this->get('/bets/' . intval($betId));
    }

    /**
     * 创建投注
     * @param int $userId 用户 ID
     * @param int $gameId 游戏 ID
     * @param string $period 期号
     * @param string $betContent 投注内容
     * @param float $betAmount 投注金额
     * @return array|null
     */
    public function createBet($userId, $gameId, $period, $betContent, $betAmount) {
        return $this->post('/bets', [
            'user_id' => $userId,
            'game_id' => $gameId,
            'period' => $period,
            'bet_content' => $betContent,
            'bet_amount' => $betAmount
        ]);
    }

    // ============================================
    // 交易记录
    // ============================================

    /**
     * 查询交易列表
     * @param array $params [user_id, type, start_date, end_date, page, page_size]
     * @return array|null
     */
    public function getTransactions($params = []) {
        return $this->get('/transactions', $params);
    }

    // ============================================
    // 充值
    // ============================================

    /**
     * 查询充值列表
     */
    public function getDeposits($params = []) {
        return $this->get('/deposits', $params);
    }

    /**
     * 创建充值
     */
    public function createDeposit($userId, $amount, $remark = '') {
        return $this->post('/deposits', [
            'user_id' => $userId,
            'amount' => $amount,
            'remark' => $remark
        ]);
    }

    // ============================================
    // 提现
    // ============================================

    /**
     * 查询提现列表
     */
    public function getWithdrawals($params = []) {
        return $this->get('/withdrawals', $params);
    }

    /**
     * 创建提现
     */
    public function createWithdrawal($userId, $amount, $remark = '') {
        return $this->post('/withdrawals', [
            'user_id' => $userId,
            'amount' => $amount,
            'remark' => $remark
        ]);
    }

    // ============================================
    // 用户
    // ============================================

    /**
     * 查询用户信息
     */
    public function getUser($userId) {
        return $this->get('/users/' . intval($userId));
    }

    /**
     * 查询用户余额
     */
    public function getUserBalance($userId) {
        return $this->get('/users/' . intval($userId) . '/balance');
    }

    // ============================================
    // Webhook
    // ============================================

    /**
     * 创建 Webhook
     * @param string $eventType 事件类型
     * @param string $callbackUrl 回调 URL
     * @param string $description 描述
     * @return array|null
     */
    public function createWebhook($eventType, $callbackUrl, $description = '') {
        return $this->post('/webhooks', [
            'event_type' => $eventType,
            'callback_url' => $callbackUrl,
            'description' => $description
        ]);
    }

    /**
     * 查询 Webhook 列表
     */
    public function getWebhooks() {
        return $this->get('/webhooks');
    }

    /**
     * 删除 Webhook
     */
    public function deleteWebhook($webhookId) {
        return $this->delete('/webhooks/' . intval($webhookId));
    }

    // ============================================
    // 游戏嵌入
    // ============================================

    /**
     * 生成嵌入 Token
     * @param int $userId 用户 ID
     * @return array|null 包含 token 和 embed_url
     */
    public function generateEmbedToken($userId) {
        return $this->post('/embed-token', ['user_id' => $userId]);
    }

    // ============================================
    // 批量查询
    // ============================================

    /**
     * 批量查询用户
     * @param array $userIds 用户 ID 数组
     * @return array|null
     */
    public function batchUsers($userIds) {
        return $this->post('/batch/users', ['user_ids' => $userIds]);
    }

    /**
     * 批量查询余额
     * @param array $userIds
     * @return array|null
     */
    public function batchBalances($userIds) {
        return $this->post('/batch/balances', ['user_ids' => $userIds]);
    }

    /**
     * 批量查询投注
     * @param array $betIds
     * @return array|null
     */
    public function batchBets($betIds) {
        return $this->post('/batch/bets', ['bet_ids' => $betIds]);
    }

    // ============================================
    // 统计
    // ============================================

    /**
     * 总览统计
     */
    public function getStatsOverview() {
        return $this->get('/stats/overview');
    }

    /**
     * 投注统计
     * @param string $groupBy day|game
     * @param int $days 天数
     */
    public function getBetStats($groupBy = 'day', $days = 7) {
        return $this->get('/stats/bets', ['group_by' => $groupBy, 'days' => $days]);
    }

    /**
     * 营收统计
     */
    public function getRevenueStats($days = 7) {
        return $this->get('/stats/revenue', ['days' => $days]);
    }

    /**
     * 用户统计
     */
    public function getUserStats($days = 7) {
        return $this->get('/stats/users', ['days' => $days]);
    }

    // ============================================
    // Webhook 签名验证工具
    // ============================================

    /**
     * 验证 Webhook 回调签名
     * @param string $payload 原始请求体
     * @param string $signature X-Webhook-Signature 头
     * @param string $webhookSecret Webhook Secret
     * @return bool
     */
    public static function verifyWebhookSignature($payload, $signature, $webhookSecret) {
        $expected = hash_hmac('sha256', $payload, $webhookSecret);
        return hash_equals($expected, $signature);
    }

    // ============================================
    // 内部方法
    // ============================================

    /**
     * 生成签名
     */
    private function sign($timestamp) {
        $signString = $this->apiKey . $timestamp . $this->apiSecret;
        return hash_hmac('sha256', $signString, $this->apiSecret);
    }

    /**
     * 构建认证参数
     */
    private function authParams() {
        $timestamp = time();
        return [
            'api_key' => $this->apiKey,
            'timestamp' => $timestamp,
            'sign' => $this->sign($timestamp)
        ];
    }

    /**
     * GET 请求
     */
    private function get($path, $params = []) {
        $params = array_merge($params, $this->authParams());
        $url = $this->baseUrl . $path . '?' . http_build_query($params);
        return $this->request($url, 'GET');
    }

    /**
     * POST 请求
     */
    private function post($path, $data = []) {
        $authParams = $this->authParams();
        $url = $this->baseUrl . $path . '?' . http_build_query($authParams);
        return $this->request($url, 'POST', $data);
    }

    /**
     * DELETE 请求
     */
    private function delete($path) {
        $authParams = $this->authParams();
        $url = $this->baseUrl . $path . '?' . http_build_query($authParams);
        return $this->request($url, 'DELETE');
    }

    /**
     * 发送 HTTP 请求
     */
    private function request($url, $method, $body = null) {
        $ch = curl_init($url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_TIMEOUT, $this->timeout);

        if ($method === 'POST') {
            curl_setopt($ch, CURLOPT_POST, true);
            curl_setopt($ch, CURLOPT_HTTPHEADER, ['Content-Type: application/json']);
            curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($body));
        } elseif ($method === 'DELETE') {
            curl_setopt($ch, CURLOPT_CUSTOMREQUEST, 'DELETE');
        }

        $response = curl_exec($ch);
        $this->lastHttpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        $error = curl_error($ch);
        curl_close($ch);

        if ($error) {
            throw new Exception("HTTP request failed: $error");
        }

        $this->lastResponse = json_decode($response, true);

        if ($this->lastResponse === null) {
            throw new Exception("Invalid JSON response");
        }

        return $this->lastResponse;
    }

    /**
     * 获取最后一次响应的 HTTP 状态码
     */
    public function getLastHttpCode() {
        return $this->lastHttpCode;
    }

    /**
     * 获取最后一次响应数据
     */
    public function getLastResponse() {
        return $this->lastResponse;
    }

    /**
     * 判断最后一次请求是否成功
     */
    public function isSuccess() {
        return isset($this->lastResponse['success']) && $this->lastResponse['success'] === true;
    }

    /**
     * 获取最后一次响应的 data 部分
     */
    public function getData() {
        return $this->lastResponse['data'] ?? null;
    }

    /**
     * 获取最后一次响应的错误信息
     */
    public function getError() {
        if ($this->isSuccess()) return null;
        return $this->lastResponse['message'] ?? 'Unknown error';
    }
}
