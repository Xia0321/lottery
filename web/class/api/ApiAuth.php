<?php
/**
 * API 认证类
 * 创建日期: 2026-02-03
 * 说明: 提供 API Key + 签名验证机制
 */

require_once(__DIR__ . '/ErrorCode.php');
require_once(__DIR__ . '/ApiResponse.php');
require_once(__DIR__ . '/../../global/security_logger.php');

class ApiAuth {

    private $mysqli;
    private $apiKeyData = null;

    /**
     * 构造函数
     * @param mysqli $mysqli 数据库连接
     */
    public function __construct($mysqli) {
        $this->mysqli = $mysqli;
    }

    /**
     * 验证请求
     * @return array API Key 数据
     */
    public function authenticate() {
        // 1. 验证 API Key
        $apiKey = $this->getApiKey();
        if (!$apiKey) {
            SecurityLogger::log('WARNING', 'API_AUTH_FAILURE', 'Missing API Key');
            ApiResponse::error(
                ErrorCode::getMessage(ErrorCode::AUTH_API_KEY_MISSING),
                ErrorCode::AUTH_API_KEY_MISSING,
                401
            );
        }

        // 2. 查询 API Key 信息
        $this->apiKeyData = $this->loadApiKey($apiKey);
        if (!$this->apiKeyData) {
            SecurityLogger::log('WARNING', 'API_AUTH_FAILURE', 'Invalid API Key', ['api_key' => $apiKey]);
            ApiResponse::error(
                ErrorCode::getMessage(ErrorCode::AUTH_API_KEY_INVALID),
                ErrorCode::AUTH_API_KEY_INVALID,
                401
            );
        }

        // 3. 检查 API Key 状态
        if ($this->apiKeyData['status'] != 1) {
            SecurityLogger::log('WARNING', 'API_AUTH_FAILURE', 'Disabled API Key', ['api_key' => $apiKey]);
            ApiResponse::error(
                ErrorCode::getMessage(ErrorCode::AUTH_API_KEY_DISABLED),
                ErrorCode::AUTH_API_KEY_DISABLED,
                401
            );
        }

        // 4. 检查过期时间
        if ($this->apiKeyData['expires_at'] && strtotime($this->apiKeyData['expires_at']) < time()) {
            SecurityLogger::log('WARNING', 'API_AUTH_FAILURE', 'Expired API Key', ['api_key' => $apiKey]);
            ApiResponse::error(
                ErrorCode::getMessage(ErrorCode::AUTH_API_KEY_EXPIRED),
                ErrorCode::AUTH_API_KEY_EXPIRED,
                401
            );
        }

        // 5. 检查 IP 白名单
        if (!$this->checkIPWhitelist()) {
            $clientIP = $this->getClientIP();
            SecurityLogger::log('WARNING', 'API_AUTH_FAILURE', 'IP not allowed', [
                'api_key' => $apiKey,
                'ip' => $clientIP
            ]);
            ApiResponse::error(
                ErrorCode::getMessage(ErrorCode::AUTH_IP_NOT_ALLOWED),
                ErrorCode::AUTH_IP_NOT_ALLOWED,
                403
            );
        }

        // 6. 验证时间戳
        $timestamp = $this->getTimestamp();
        if (!$timestamp) {
            SecurityLogger::log('WARNING', 'API_AUTH_FAILURE', 'Missing timestamp', ['api_key' => $apiKey]);
            ApiResponse::error(
                ErrorCode::getMessage(ErrorCode::AUTH_TIMESTAMP_MISSING),
                ErrorCode::AUTH_TIMESTAMP_MISSING,
                401
            );
        }

        if (!$this->validateTimestamp($timestamp)) {
            SecurityLogger::log('WARNING', 'API_AUTH_FAILURE', 'Invalid or expired timestamp', [
                'api_key' => $apiKey,
                'timestamp' => $timestamp
            ]);
            ApiResponse::error(
                ErrorCode::getMessage(ErrorCode::AUTH_REQUEST_EXPIRED),
                ErrorCode::AUTH_REQUEST_EXPIRED,
                401
            );
        }

        // 7. 验证签名
        $signature = $this->getSignature();
        if (!$signature) {
            SecurityLogger::log('WARNING', 'API_AUTH_FAILURE', 'Missing signature', ['api_key' => $apiKey]);
            ApiResponse::error(
                ErrorCode::getMessage(ErrorCode::AUTH_SIGNATURE_MISSING),
                ErrorCode::AUTH_SIGNATURE_MISSING,
                401
            );
        }

        if (!$this->validateSignature($apiKey, $timestamp, $signature)) {
            SecurityLogger::log('WARNING', 'API_AUTH_FAILURE', 'Invalid signature', ['api_key' => $apiKey]);
            ApiResponse::error(
                ErrorCode::getMessage(ErrorCode::AUTH_SIGNATURE_INVALID),
                ErrorCode::AUTH_SIGNATURE_INVALID,
                401
            );
        }

        // 8. 更新最后使用时间
        $this->updateLastUsed();

        // 9. 记录 API 访问日志
        SecurityLogger::logApiAccess(
            $_SERVER['REQUEST_URI'],
            $_SERVER['REQUEST_METHOD'],
            200,
            $apiKey
        );

        return $this->apiKeyData;
    }

    /**
     * 获取 API Key
     * @return string|null
     */
    private function getApiKey() {
        // 优先从 Header 获取
        if (isset($_SERVER['HTTP_X_API_KEY'])) {
            return trim($_SERVER['HTTP_X_API_KEY']);
        }

        // 其次从查询参数获取
        if (isset($_GET['api_key'])) {
            return trim($_GET['api_key']);
        }

        return null;
    }

    /**
     * 获取时间戳
     * @return int|null
     */
    private function getTimestamp() {
        $timestamp = $_GET['timestamp'] ?? $_POST['timestamp'] ?? null;
        return $timestamp ? (int)$timestamp : null;
    }

    /**
     * 获取签名
     * @return string|null
     */
    private function getSignature() {
        return $_GET['sign'] ?? $_POST['sign'] ?? null;
    }

    /**
     * 加载 API Key 数据
     * @param string $apiKey API Key
     * @return array|null
     */
    private function loadApiKey($apiKey) {
        $stmt = $this->mysqli->prepare("SELECT * FROM api_keys WHERE api_key = ? LIMIT 1");
        $stmt->bind_param("s", $apiKey);
        $stmt->execute();
        $result = $stmt->get_result();
        $data = $result->fetch_assoc();
        $stmt->close();

        return $data;
    }

    /**
     * 验证时间戳 (5分钟内有效)
     * @param int $timestamp 时间戳
     * @return bool
     */
    private function validateTimestamp($timestamp) {
        $now = time();
        $diff = abs($now - $timestamp);

        // 时间戳需要在 5 分钟内
        return $diff <= 300;
    }

    /**
     * 验证签名
     * @param string $apiKey API Key
     * @param int $timestamp 时间戳
     * @param string $signature 签名
     * @return bool
     */
    private function validateSignature($apiKey, $timestamp, $signature) {
        // 构建签名字符串
        $signString = $apiKey . $timestamp . $this->apiKeyData['api_secret'];

        // 计算期望的签名
        $expectedSign = hash_hmac('sha256', $signString, $this->apiKeyData['api_secret']);

        // 使用 hash_equals 防止时序攻击
        return hash_equals($expectedSign, $signature);
    }

    /**
     * 检查 IP 白名单（支持 CIDR）
     * @return bool
     */
    private function checkIPWhitelist() {
        $allowedIPs = $this->apiKeyData['allowed_ips'];

        // 如果没有配置白名单，则允许所有 IP
        if (empty($allowedIPs) || $allowedIPs === 'null') {
            return true;
        }

        $allowedIPArray = json_decode($allowedIPs, true);
        if (!is_array($allowedIPArray) || empty($allowedIPArray)) {
            return true;
        }

        $clientIP = $this->getClientIP();

        // 检查 IP 是否在白名单中（支持精确匹配和 CIDR）
        foreach ($allowedIPArray as $allowed) {
            if (strpos($allowed, '/') !== false) {
                // CIDR 匹配
                if ($this->ipInCidr($clientIP, $allowed)) {
                    return true;
                }
            } else {
                // 精确匹配
                if ($clientIP === $allowed) {
                    return true;
                }
            }
        }

        return false;
    }

    /**
     * 检查 IP 是否在 CIDR 范围内
     * @param string $ip
     * @param string $cidr 例如 192.168.1.0/24
     * @return bool
     */
    private function ipInCidr($ip, $cidr) {
        list($subnet, $bits) = explode('/', $cidr);
        $ip = ip2long($ip);
        $subnet = ip2long($subnet);
        if ($ip === false || $subnet === false) {
            return false;
        }
        $mask = -1 << (32 - (int)$bits);
        return ($ip & $mask) === ($subnet & $mask);
    }

    /**
     * 获取客户端 IP
     * @return string
     */
    private function getClientIP() {
        if (!empty($_SERVER['HTTP_X_FORWARDED_FOR'])) {
            $ips = explode(',', $_SERVER['HTTP_X_FORWARDED_FOR']);
            return trim($ips[0]);
        } elseif (!empty($_SERVER['HTTP_X_REAL_IP'])) {
            return $_SERVER['HTTP_X_REAL_IP'];
        } elseif (!empty($_SERVER['REMOTE_ADDR'])) {
            return $_SERVER['REMOTE_ADDR'];
        }

        return 'Unknown';
    }

    /**
     * 更新最后使用时间
     */
    private function updateLastUsed() {
        // 消费可能残留的查询结果
        while ($this->mysqli->more_results()) {
            $this->mysqli->next_result();
        }

        $stmt = $this->mysqli->prepare("UPDATE api_keys SET last_used_at = NOW() WHERE id = ?");
        if ($stmt === false) {
            error_log("updateLastUsed prepare failed: " . $this->mysqli->error);
            return;
        }
        $stmt->bind_param("i", $this->apiKeyData['id']);
        $stmt->execute();
        $stmt->close();
    }

    /**
     * 获取当前 API Key 的数据
     * @return array|null
     */
    public function getApiKeyData() {
        return $this->apiKeyData;
    }

    /**
     * 获取关联的代理 ID
     * @return int
     */
    public function getAgentId() {
        return $this->apiKeyData['agent_id'] ?? 0;
    }

    /**
     * 获取当前 API Key 的权限范围
     * @return array
     */
    public function getScopes() {
        $scopes = $this->apiKeyData['scopes'] ?? null;
        if (empty($scopes) || $scopes === 'null') {
            // 无 scopes 字段或为空，返回全部权限
            return ['*'];
        }
        $decoded = json_decode($scopes, true);
        return is_array($decoded) ? $decoded : ['*'];
    }

    /**
     * 检查是否拥有指定权限
     * 权限格式: resource.action，例如 bets.read, bets.write, users.read
     *
     * @param string $scope 要检查的权限
     * @return bool
     */
    public function hasScope($scope) {
        $scopes = $this->getScopes();

        // 全部权限
        if (in_array('*', $scopes)) {
            return true;
        }

        // 精确匹配
        if (in_array($scope, $scopes)) {
            return true;
        }

        // 资源通配符匹配，例如 bets.* 匹配 bets.read
        $parts = explode('.', $scope);
        if (count($parts) === 2) {
            if (in_array($parts[0] . '.*', $scopes)) {
                return true;
            }
        }

        return false;
    }

    /**
     * 要求指定权限，无权限则返回 403
     * @param string $scope
     */
    public function requireScope($scope) {
        if (!$this->hasScope($scope)) {
            SecurityLogger::log('WARNING', 'API_AUTH_FAILURE', 'Insufficient scope', [
                'api_key' => $this->apiKeyData['api_key'],
                'required_scope' => $scope,
                'granted_scopes' => $this->getScopes()
            ]);
            ApiResponse::error(
                'Insufficient permissions: ' . $scope . ' scope required',
                ErrorCode::AUTH_INSUFFICIENT_PERMISSIONS,
                403
            );
        }
    }

    /**
     * 生成 API Key 和 Secret
     * @return array ['api_key' => string, 'api_secret' => string]
     */
    public static function generateCredentials() {
        return [
            'api_key' => 'api_' . bin2hex(random_bytes(16)),
            'api_secret' => bin2hex(random_bytes(32))
        ];
    }

    /**
     * 生成签名示例 (用于文档)
     * @param string $apiKey API Key
     * @param string $apiSecret API Secret
     * @param int $timestamp 时间戳
     * @return string
     */
    public static function generateSignature($apiKey, $apiSecret, $timestamp) {
        $signString = $apiKey . $timestamp . $apiSecret;
        return hash_hmac('sha256', $signString, $apiSecret);
    }
}
