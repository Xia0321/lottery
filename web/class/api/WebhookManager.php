<?php
/**
 * Webhook 管理器
 * 创建日期: 2026-02-03
 * 说明: 管理 Webhook 订阅和发送
 */

class WebhookManager {

    private $mysqli;

    // 支持的事件类型
    const EVENT_BET_CREATED = 'bet.created';
    const EVENT_BET_SETTLED = 'bet.settled';
    const EVENT_DEPOSIT_COMPLETED = 'deposit.completed';
    const EVENT_WITHDRAWAL_COMPLETED = 'withdrawal.completed';

    /**
     * 构造函数
     * @param mysqli $mysqli 数据库连接
     */
    public function __construct($mysqli) {
        $this->mysqli = $mysqli;
    }

    /**
     * 创建 Webhook 订阅
     * @param int $apiKeyId API Key ID
     * @param string $eventType 事件类型
     * @param string $callbackUrl 回调 URL
     * @param string $description 描述
     * @return array|false
     */
    public function create($apiKeyId, $eventType, $callbackUrl, $description = null) {
        // 验证事件类型
        if (!$this->isValidEventType($eventType)) {
            return false;
        }

        // 验证 URL
        if (!filter_var($callbackUrl, FILTER_VALIDATE_URL)) {
            return false;
        }

        // 检查是否已存在相同的订阅
        $existing = $this->findByEventAndUrl($apiKeyId, $eventType, $callbackUrl);
        if ($existing) {
            return false; // 已存在
        }

        // 生成 Secret
        $secret = bin2hex(random_bytes(32));

        // 插入数据库
        $stmt = $this->mysqli->prepare("
            INSERT INTO webhooks (
                api_key_id,
                event_type,
                callback_url,
                secret,
                description,
                status
            ) VALUES (?, ?, ?, ?, ?, 1)
        ");

        $stmt->bind_param("issss", $apiKeyId, $eventType, $callbackUrl, $secret, $description);
        $result = $stmt->execute();
        $webhookId = $stmt->insert_id;
        $stmt->close();

        if ($result) {
            return $this->findById($webhookId);
        }

        return false;
    }

    /**
     * 查找 Webhook
     * @param int $id Webhook ID
     * @return array|null
     */
    public function findById($id) {
        $stmt = $this->mysqli->prepare("SELECT * FROM webhooks WHERE id = ?");
        $stmt->bind_param("i", $id);
        $stmt->execute();
        $result = $stmt->get_result();
        $webhook = $result->fetch_assoc();
        $stmt->close();

        return $webhook;
    }

    /**
     * 根据事件和 URL 查找
     * @param int $apiKeyId API Key ID
     * @param string $eventType 事件类型
     * @param string $callbackUrl 回调 URL
     * @return array|null
     */
    private function findByEventAndUrl($apiKeyId, $eventType, $callbackUrl) {
        $stmt = $this->mysqli->prepare("
            SELECT * FROM webhooks
            WHERE api_key_id = ? AND event_type = ? AND callback_url = ?
            LIMIT 1
        ");

        $stmt->bind_param("iss", $apiKeyId, $eventType, $callbackUrl);
        $stmt->execute();
        $result = $stmt->get_result();
        $webhook = $result->fetch_assoc();
        $stmt->close();

        return $webhook;
    }

    /**
     * 获取 API Key 的所有 Webhook
     * @param int $apiKeyId API Key ID
     * @return array
     */
    public function getByApiKey($apiKeyId) {
        $stmt = $this->mysqli->prepare("
            SELECT * FROM webhooks
            WHERE api_key_id = ?
            ORDER BY created_at DESC
        ");

        $stmt->bind_param("i", $apiKeyId);
        $stmt->execute();
        $result = $stmt->get_result();

        $webhooks = [];
        while ($row = $result->fetch_assoc()) {
            $webhooks[] = $row;
        }

        $stmt->close();

        return $webhooks;
    }

    /**
     * 更新 Webhook
     * @param int $id Webhook ID
     * @param array $data 更新数据
     * @return bool
     */
    public function update($id, $data) {
        $fields = [];
        $params = [];
        $types = '';

        if (isset($data['callback_url'])) {
            $fields[] = "callback_url = ?";
            $params[] = $data['callback_url'];
            $types .= 's';
        }

        if (isset($data['status'])) {
            $fields[] = "status = ?";
            $params[] = $data['status'];
            $types .= 'i';
        }

        if (isset($data['retry_times'])) {
            $fields[] = "retry_times = ?";
            $params[] = $data['retry_times'];
            $types .= 'i';
        }

        if (isset($data['timeout'])) {
            $fields[] = "timeout = ?";
            $params[] = $data['timeout'];
            $types .= 'i';
        }

        if (isset($data['description'])) {
            $fields[] = "description = ?";
            $params[] = $data['description'];
            $types .= 's';
        }

        if (empty($fields)) {
            return false;
        }

        $params[] = $id;
        $types .= 'i';

        $sql = "UPDATE webhooks SET " . implode(', ', $fields) . " WHERE id = ?";
        $stmt = $this->mysqli->prepare($sql);
        $stmt->bind_param($types, ...$params);
        $result = $stmt->execute();
        $stmt->close();

        return $result;
    }

    /**
     * 删除 Webhook
     * @param int $id Webhook ID
     * @param int $apiKeyId API Key ID (用于验证权限)
     * @return bool
     */
    public function delete($id, $apiKeyId) {
        $stmt = $this->mysqli->prepare("
            DELETE FROM webhooks
            WHERE id = ? AND api_key_id = ?
        ");

        $stmt->bind_param("ii", $id, $apiKeyId);
        $result = $stmt->execute();
        $stmt->close();

        return $result;
    }

    /**
     * 触发事件
     * @param string $eventType 事件类型
     * @param array $eventData 事件数据
     * @param int $apiKeyId API Key ID (可选，为空则发送给所有订阅者)
     * @return int 触发的 Webhook 数量
     */
    public function trigger($eventType, $eventData, $apiKeyId = null) {
        // 查找订阅该事件的 Webhook
        if ($apiKeyId) {
            $stmt = $this->mysqli->prepare("
                SELECT * FROM webhooks
                WHERE event_type = ? AND api_key_id = ? AND status = 1
            ");
            $stmt->bind_param("si", $eventType, $apiKeyId);
        } else {
            $stmt = $this->mysqli->prepare("
                SELECT * FROM webhooks
                WHERE event_type = ? AND status = 1
            ");
            $stmt->bind_param("s", $eventType);
        }

        $stmt->execute();
        $result = $stmt->get_result();

        $count = 0;
        while ($webhook = $result->fetch_assoc()) {
            $this->send($webhook, $eventData);
            $count++;
        }

        $stmt->close();

        return $count;
    }

    /**
     * 发送 Webhook
     * @param array $webhook Webhook 配置
     * @param array $eventData 事件数据
     * @return bool
     */
    private function send($webhook, $eventData) {
        $webhookId = $webhook['id'];
        $callbackUrl = $webhook['callback_url'];
        $secret = $webhook['secret'];
        $timeout = $webhook['timeout'] ?? 5;
        $retryTimes = $webhook['retry_times'] ?? 1;

        // 准备请求体
        $payload = [
            'event' => $webhook['event_type'],
            'data' => $eventData,
            'timestamp' => time(),
            'webhook_id' => $webhookId
        ];

        $jsonPayload = json_encode($payload, JSON_UNESCAPED_UNICODE);

        // 生成签名
        $signature = hash_hmac('sha256', $jsonPayload, $secret);

        // 准备请求头
        $headers = [
            'Content-Type: application/json',
            'X-Webhook-Signature: ' . $signature,
            'X-Webhook-Event: ' . $webhook['event_type'],
            'User-Agent: LotteryAPI-Webhook/1.0'
        ];

        // 发送请求 (使用 cURL)
        $success = false;
        $responseCode = null;
        $responseBody = null;
        $errorMessage = null;
        $retryCount = 0;

        for ($i = 0; $i <= $retryTimes; $i++) {
            $startTime = microtime(true);

            $ch = curl_init($callbackUrl);
            curl_setopt($ch, CURLOPT_POST, true);
            curl_setopt($ch, CURLOPT_POSTFIELDS, $jsonPayload);
            curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
            curl_setopt($ch, CURLOPT_TIMEOUT, $timeout);
            curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false); // 生产环境应设为 true

            $responseBody = curl_exec($ch);
            $responseCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
            $executionTime = (int)((microtime(true) - $startTime) * 1000);

            if (curl_errno($ch)) {
                $errorMessage = curl_error($ch);
                $retryCount = $i;
            } else {
                // HTTP 2xx 视为成功
                if ($responseCode >= 200 && $responseCode < 300) {
                    $success = true;
                    curl_close($ch);
                    break;
                } else {
                    $errorMessage = "HTTP $responseCode";
                    $retryCount = $i;
                }
            }

            curl_close($ch);

            // 如果还有重试机会，等待一下再重试
            if ($i < $retryTimes) {
                sleep(1);
            }
        }

        // 记录日志
        $this->logWebhook(
            $webhookId,
            $webhook['event_type'],
            $jsonPayload,
            $callbackUrl,
            $headers,
            $responseCode,
            $responseBody,
            $retryCount,
            $success ? 'success' : 'failed',
            $errorMessage,
            $executionTime ?? 0
        );

        // 更新 Webhook 最后触发时间
        if ($success) {
            $stmt = $this->mysqli->prepare("
                UPDATE webhooks SET last_triggered_at = NOW() WHERE id = ?
            ");
            $stmt->bind_param("i", $webhookId);
            $stmt->execute();
            $stmt->close();
        }

        return $success;
    }

    /**
     * 记录 Webhook 日志
     */
    private function logWebhook($webhookId, $eventType, $requestBody, $callbackUrl, $headers, $responseCode, $responseBody, $retryCount, $status, $errorMessage, $executionTime) {
        $headersJson = json_encode($headers);
        $responseBodyTruncated = substr($responseBody, 0, 1000);

        $stmt = $this->mysqli->prepare("
            INSERT INTO webhook_logs (
                webhook_id,
                event_type,
                event_data,
                callback_url,
                request_headers,
                request_body,
                response_code,
                response_body,
                retry_count,
                status,
                error_message,
                execution_time
            ) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)
        ");

        $stmt->bind_param(
            "isssssisissi",
            $webhookId,
            $eventType,
            $requestBody,
            $callbackUrl,
            $headersJson,
            $requestBody,
            $responseCode,
            $responseBodyTruncated,
            $retryCount,
            $status,
            $errorMessage,
            $executionTime
        );

        $stmt->execute();
        $stmt->close();
    }

    /**
     * 验证事件类型
     * @param string $eventType 事件类型
     * @return bool
     */
    private function isValidEventType($eventType) {
        $validTypes = [
            self::EVENT_BET_CREATED,
            self::EVENT_BET_SETTLED,
            self::EVENT_DEPOSIT_COMPLETED,
            self::EVENT_WITHDRAWAL_COMPLETED
        ];

        return in_array($eventType, $validTypes);
    }

    /**
     * 获取所有支持的事件类型
     * @return array
     */
    public static function getSupportedEvents() {
        return [
            self::EVENT_BET_CREATED => '投注创建',
            self::EVENT_BET_SETTLED => '投注结算',
            self::EVENT_DEPOSIT_COMPLETED => '充值完成',
            self::EVENT_WITHDRAWAL_COMPLETED => '提款完成'
        ];
    }
}
