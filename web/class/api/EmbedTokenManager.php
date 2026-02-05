<?php
/**
 * 游戏嵌入 Token 管理器
 * 说明: 生成和验证用于 iframe 嵌入游戏窗口的 Token
 */

class EmbedTokenManager {

    private $mysqli;
    private $encryptionKey;
    private $iv;

    /**
     * @param mysqli $mysqli 数据库连接
     * @param string $secretKey 加密密钥（从配置文件读取）
     */
    public function __construct($mysqli, $secretKey = null) {
        $this->mysqli = $mysqli;

        // 使用配置中的密钥，或回退到默认值
        if ($secretKey === null) {
            // 从全局配置获取
            global $config;
            $secretKey = isset($config['allpass']) ? $config['allpass'] : 'lottery_embed_secret_key_2026';
        }

        $this->encryptionKey = hash('sha256', $secretKey, true);
        $this->iv = substr(hash('sha256', 'embed_iv_' . $secretKey), 0, 16);
    }

    /**
     * 生成嵌入 Token
     * @param int $userId 用户 ID
     * @param int $apiKeyId 关联的 API Key ID
     * @param int $ttl Token 有效期（秒，默认 300 = 5 分钟）
     * @return array ['token' => string, 'expires_at' => string]
     */
    public function generateToken($userId, $apiKeyId, $ttl = 300) {
        $nonce = bin2hex(random_bytes(8));
        $expiresAt = time() + $ttl;

        $payload = json_encode([
            'uid' => (int)$userId,
            'kid' => (int)$apiKeyId,
            'exp' => $expiresAt,
            'nonce' => $nonce
        ]);

        $encrypted = openssl_encrypt($payload, 'AES-256-CBC', $this->encryptionKey, 0, $this->iv);
        $token = rtrim(strtr(base64_encode($encrypted), '+/', '-_'), '=');

        return [
            'token' => $token,
            'expires_at' => date('Y-m-d H:i:s', $expiresAt),
            'ttl' => $ttl
        ];
    }

    /**
     * 验证并解析 Token
     * @param string $token 加密 Token
     * @return array|false 成功返回 ['user_id' => int, 'api_key_id' => int]，失败返回 false
     */
    public function verifyToken($token) {
        // 还原 base64
        $encrypted = base64_decode(strtr($token, '-_', '+/'));
        if ($encrypted === false) {
            return false;
        }

        $decrypted = openssl_decrypt($encrypted, 'AES-256-CBC', $this->encryptionKey, 0, $this->iv);
        if ($decrypted === false) {
            return false;
        }

        $payload = json_decode($decrypted, true);
        if (!$payload || !isset($payload['uid']) || !isset($payload['exp']) || !isset($payload['nonce'])) {
            return false;
        }

        // 验证过期时间
        if ($payload['exp'] < time()) {
            return false;
        }

        // 防重放：检查 nonce 是否已使用（基于数据库，不依赖 Redis）
        $nonce = $payload['nonce'];
        $stmt = $this->mysqli->prepare("SELECT id FROM embed_token_nonces WHERE nonce = ?");
        if ($stmt) {
            $stmt->bind_param('s', $nonce);
            $stmt->execute();
            $result = $stmt->get_result();

            if ($result->num_rows > 0) {
                $stmt->close();
                return false; // nonce 已使用
            }
            $stmt->close();

            // 记录 nonce
            $expiresAt = date('Y-m-d H:i:s', $payload['exp']);
            $stmt = $this->mysqli->prepare("INSERT INTO embed_token_nonces (nonce, expires_at) VALUES (?, ?)");
            if ($stmt) {
                $stmt->bind_param('ss', $nonce, $expiresAt);
                $stmt->execute();
                $stmt->close();
            }
        }
        // 如果 nonce 表不存在，跳过防重放（降级运行）

        return [
            'user_id' => (int)$payload['uid'],
            'api_key_id' => (int)$payload['kid']
        ];
    }

    /**
     * 清理过期的 nonce 记录
     */
    public function cleanupNonces() {
        $this->mysqli->query("DELETE FROM embed_token_nonces WHERE expires_at < NOW()");
    }

    /**
     * 确保 nonce 表存在
     */
    public function ensureNonceTable() {
        $this->mysqli->query("
            CREATE TABLE IF NOT EXISTS `embed_token_nonces` (
              `id` int(11) NOT NULL AUTO_INCREMENT,
              `nonce` varchar(32) NOT NULL,
              `expires_at` datetime NOT NULL,
              PRIMARY KEY (`id`),
              UNIQUE KEY `uk_nonce` (`nonce`),
              KEY `idx_expires` (`expires_at`)
            ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COMMENT='嵌入 Token 防重放表'
        ");
    }
}
