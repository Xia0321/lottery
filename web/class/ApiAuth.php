<?php
/**
 * API 认证类
 *
 * 负责 API Key 验证、签名验证、速率限制等
 */

require_once __DIR__ . '/ErrorCode.php';
require_once __DIR__ . '/ApiResponse.php';

class ApiAuth {

    private $pdo;
    private $redis;
    private $apiKeyData = null;


    public function __construct($pdo, $redis) {
        $this->pdo = $pdo;
        $this->redis = $redis;
    }


    /**
     * 验证 API 请求（完整流程）
     *
     * 验证顺序：API Key -> 速率限制 -> 签名验证
     *
     * @return array API Key 数据（包含 id, api_key, agent_id 等）
     */
    public function authenticate() {
        // 1. 验证 API Key
        $this->verifyApiKey();

        // 2. 检查速率限制
        $this->checkRateLimit();

        // 3. 验证签名
        $this->verifySignature();

        // 返回 API Key 数据供后续使用
        return $this->apiKeyData;
    }


    /**
     * 验证 API Key
     *
     * @return void
     */
    private function verifyApiKey() {
        // 从请求参数或请求头获取 API Key
        $apiKey = $_GET['api_key'] ?? $_POST['api_key'] ?? $_SERVER['HTTP_X_API_KEY'] ?? null;

        if (!$apiKey) {
            ApiResponse::error(ErrorCode::AUTH_MISSING_API_KEY);
        }

        // 查询数据库验证 API Key
        try {
            $stmt = $this->pdo->prepare("
                SELECT id, api_key, api_secret, name, agent_id, status, rate_limit
                FROM api_keys
                WHERE api_key = ?
            ");
            $stmt->execute([$apiKey]);
            $data = $stmt->fetch(PDO::FETCH_ASSOC);

            if (!$data) {
                ApiResponse::error(ErrorCode::AUTH_INVALID_API_KEY);
            }

            // 检查是否被禁用
            if ($data['status'] != 1) {
                ApiResponse::error(ErrorCode::AUTH_API_KEY_DISABLED);
            }

            // 保存 API Key 数据
            $this->apiKeyData = $data;

            // 更新最后使用时间（异步更新，不影响响应速度）
            $this->updateLastUsedTime($data['id']);

        } catch (Exception $e) {
            error_log("API Key verification error: " . $e->getMessage());
            ApiResponse::error(ErrorCode::SYS_DATABASE_ERROR);
        }
    }


    /**
     * 检查速率限制
     *
     * @return void
     */
    private function checkRateLimit() {
        $apiKeyId = $this->apiKeyData['id'];
        $rateLimit = $this->apiKeyData['rate_limit']; // 每分钟限制次数

        // Redis Key: rate_limit:{api_key_id}:{当前分钟}
        $minute = date('YmdHi');
        $redisKey = "rate_limit:{$apiKeyId}:{$minute}";

        try {
            // 增加计数
            $count = $this->redis->incr($redisKey);

            // 第一次访问时设置过期时间
            if ($count == 1) {
                $this->redis->expire($redisKey, 60);
            }

            // 检查是否超限
            if ($count > $rateLimit) {
                // 在响应头中返回限流信息
                header("X-RateLimit-Limit: {$rateLimit}");
                header("X-RateLimit-Remaining: 0");
                header("X-RateLimit-Reset: " . (time() + 60));

                ApiResponse::error(ErrorCode::AUTH_RATE_LIMIT_EXCEEDED);
            }

            // 在响应头中返回限流信息
            $remaining = max(0, $rateLimit - $count);
            header("X-RateLimit-Limit: {$rateLimit}");
            header("X-RateLimit-Remaining: {$remaining}");
            header("X-RateLimit-Reset: " . (time() + 60));

        } catch (Exception $e) {
            error_log("Rate limit check error: " . $e->getMessage());
            // Redis 错误不应该阻止 API 访问，记录日志继续执行
        }
    }


    /**
     * 验证签名
     *
     * @return void
     */
    private function verifySignature() {
        // 获取请求参数
        $params = $_REQUEST; // 包含 GET 和 POST 参数

        // 检查是否有签名
        if (!isset($params['sign'])) {
            ApiResponse::error(ErrorCode::AUTH_MISSING_SIGNATURE);
        }

        // 检查是否有时间戳
        if (!isset($params['timestamp'])) {
            ApiResponse::error(ErrorCode::AUTH_MISSING_TIMESTAMP);
        }

        // 验证时间戳（防重放攻击：5 分钟内有效）
        $timestamp = intval($params['timestamp']);
        $now = time();
        if (abs($now - $timestamp) > 300) {
            ApiResponse::error(ErrorCode::AUTH_TIMESTAMP_EXPIRED);
        }

        // 提取客户端签名
        $clientSign = $params['sign'];
        unset($params['sign']); // 移除签名参数

        // 参数排序
        ksort($params);

        // 生成签名字符串
        $signString = http_build_query($params);

        // 使用 API Secret 生成签名
        $secret = $this->apiKeyData['api_secret'];
        $serverSign = hash_hmac('sha256', $signString, $secret);

        // 比较签名
        if (!hash_equals($serverSign, $clientSign)) {
            ApiResponse::error(ErrorCode::AUTH_INVALID_SIGNATURE);
        }
    }


    /**
     * 更新 API Key 最后使用时间
     *
     * @param int $apiKeyId
     * @return void
     */
    private function updateLastUsedTime($apiKeyId) {
        try {
            $stmt = $this->pdo->prepare("
                UPDATE api_keys
                SET last_used_at = NOW()
                WHERE id = ?
            ");
            $stmt->execute([$apiKeyId]);
        } catch (Exception $e) {
            // 更新失败不影响主流程
            error_log("Failed to update last_used_at: " . $e->getMessage());
        }
    }


    /**
     * 获取当前 API Key 数据
     *
     * @return array|null
     */
    public function getApiKeyData() {
        return $this->apiKeyData;
    }


    /**
     * 生成新的 API Key 和 Secret
     *
     * @param string $name 外部系统名称
     * @param int|null $agentId 关联的代理 ID
     * @param int $rateLimit 速率限制（默认 100 次/分钟）
     * @return array ['api_key' => 'ak_xxx', 'api_secret' => 'sk_xxx']
     */
    public function generateApiKey($name, $agentId = null, $rateLimit = 100) {
        // 生成 API Key（ak_ 前缀）
        $apiKey = 'ak_' . uniqid() . bin2hex(random_bytes(16));

        // 生成 API Secret（sk_ 前缀）
        $apiSecret = 'sk_' . bin2hex(random_bytes(32));

        // 使用 bcrypt 加密存储 Secret
        $hashedSecret = password_hash($apiSecret, PASSWORD_BCRYPT);

        try {
            $stmt = $this->pdo->prepare("
                INSERT INTO api_keys (api_key, api_secret, name, agent_id, rate_limit, status, created_at)
                VALUES (?, ?, ?, ?, ?, 1, NOW())
            ");

            $stmt->execute([$apiKey, $hashedSecret, $name, $agentId, $rateLimit]);

            return [
                'api_key' => $apiKey,
                'api_secret' => $apiSecret  // 只返回一次，不再显示
            ];

        } catch (Exception $e) {
            error_log("Failed to generate API key: " . $e->getMessage());
            throw new Exception("生成 API Key 失败");
        }
    }
}
