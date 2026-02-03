<?php
/**
 * Webhook 管理类
 *
 * 负责 Webhook 的创建、发送、重试等
 */

class WebhookManager {

    private $pdo;


    public function __construct($pdo) {
        $this->pdo = $pdo;
    }


    /**
     * 创建 Webhook 订阅
     *
     * @param int $apiKeyId API Key ID
     * @param string $url 回调 URL
     * @param array $events 订阅的事件类型数组
     * @return int Webhook ID
     */
    public function create($apiKeyId, $url, $events) {
        // 验证 URL 格式（必须是 HTTPS）
        if (!filter_var($url, FILTER_VALIDATE_URL) || strpos($url, 'https://') !== 0) {
            throw new Exception('Webhook URL 必须是有效的 HTTPS 地址');
        }

        // 生成 Webhook 签名密钥
        $secret = bin2hex(random_bytes(32));

        try {
            $stmt = $this->pdo->prepare("
                INSERT INTO webhooks (api_key_id, url, secret, events, status, created_at)
                VALUES (?, ?, ?, ?, 1, NOW())
            ");

            $stmt->execute([
                $apiKeyId,
                $url,
                $secret,
                json_encode($events)
            ]);

            $webhookId = $this->pdo->lastInsertId();

            return [
                'id' => $webhookId,
                'url' => $url,
                'secret' => $secret,
                'events' => $events
            ];

        } catch (Exception $e) {
            error_log("Failed to create webhook: " . $e->getMessage());
            throw new Exception('创建 Webhook 失败');
        }
    }


    /**
     * 触发 Webhook 事件
     *
     * @param string $eventType 事件类型（如 bet.created, bet.won 等）
     * @param array $data 事件数据
     * @param int|null $apiKeyId 指定 API Key ID（可选，为空则通知所有订阅该事件的 Webhook）
     * @return void
     */
    public function trigger($eventType, $data, $apiKeyId = null) {
        // 查询订阅了该事件的 Webhook
        $sql = "
            SELECT id, url, secret
            FROM webhooks
            WHERE status = 1
            AND JSON_CONTAINS(events, ?)
        ";

        $params = [json_encode($eventType)];

        if ($apiKeyId !== null) {
            $sql .= " AND api_key_id = ?";
            $params[] = $apiKeyId;
        }

        try {
            $stmt = $this->pdo->prepare($sql);
            $stmt->execute($params);
            $webhooks = $stmt->fetchAll(PDO::FETCH_ASSOC);

            // 遍历所有订阅了该事件的 Webhook
            foreach ($webhooks as $webhook) {
                $this->send($webhook['id'], $webhook['url'], $webhook['secret'], $eventType, $data);
            }

        } catch (Exception $e) {
            error_log("Failed to trigger webhook: " . $e->getMessage());
        }
    }


    /**
     * 发送 Webhook 请求
     *
     * @param int $webhookId Webhook ID
     * @param string $url 回调 URL
     * @param string $secret 签名密钥
     * @param string $eventType 事件类型
     * @param array $data 事件数据
     * @return void
     */
    private function send($webhookId, $url, $secret, $eventType, $data) {
        // 构建请求负载
        $payload = json_encode([
            'event' => $eventType,
            'data' => $data,
            'timestamp' => time()
        ]);

        // 生成签名（HMAC-SHA256）
        $signature = hash_hmac('sha256', $payload, $secret);

        // 发送 HTTP POST 请求
        $ch = curl_init($url);
        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $payload);
        curl_setopt($ch, CURLOPT_HTTPHEADER, [
            'Content-Type: application/json',
            'X-Webhook-Signature: ' . $signature,
            'X-Webhook-Timestamp: ' . time()
        ]);
        curl_setopt($ch, CURLOPT_TIMEOUT, 5); // 5 秒超时
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

        $response = curl_exec($ch);
        $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        $error = curl_error($ch);
        curl_close($ch);

        // 判断是否成功
        $success = ($httpCode >= 200 && $httpCode < 300);

        // 记录日志
        $this->logWebhookCall($webhookId, $eventType, $httpCode, $success ? 'success' : 'failed', 0);

        // 如果失败，简单重试 1 次
        if (!$success) {
            sleep(1);
            $this->retryWebhook($webhookId, $url, $secret, $eventType, $data);
        }
    }


    /**
     * 重试 Webhook
     *
     * @param int $webhookId
     * @param string $url
     * @param string $secret
     * @param string $eventType
     * @param array $data
     * @return void
     */
    private function retryWebhook($webhookId, $url, $secret, $eventType, $data) {
        // 构建请求负载
        $payload = json_encode([
            'event' => $eventType,
            'data' => $data,
            'timestamp' => time()
        ]);

        // 生成签名
        $signature = hash_hmac('sha256', $payload, $secret);

        // 发送请求
        $ch = curl_init($url);
        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $payload);
        curl_setopt($ch, CURLOPT_HTTPHEADER, [
            'Content-Type: application/json',
            'X-Webhook-Signature: ' . $signature,
            'X-Webhook-Timestamp: ' . time()
        ]);
        curl_setopt($ch, CURLOPT_TIMEOUT, 5);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

        $response = curl_exec($ch);
        $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        curl_close($ch);

        $success = ($httpCode >= 200 && $httpCode < 300);

        // 记录重试日志
        $this->logWebhookCall($webhookId, $eventType, $httpCode, $success ? 'success' : 'failed', 1);
    }


    /**
     * 记录 Webhook 调用日志
     *
     * @param int $webhookId
     * @param string $eventType
     * @param int $httpCode
     * @param string $status
     * @param int $retryCount
     * @return void
     */
    private function logWebhookCall($webhookId, $eventType, $httpCode, $status, $retryCount) {
        try {
            $stmt = $this->pdo->prepare("
                INSERT INTO webhook_logs (webhook_id, event_type, http_code, status, retry_count, created_at)
                VALUES (?, ?, ?, ?, ?, NOW())
            ");

            $stmt->execute([
                $webhookId,
                $eventType,
                $httpCode,
                $status,
                $retryCount
            ]);

        } catch (Exception $e) {
            error_log("Failed to log webhook call: " . $e->getMessage());
        }
    }


    /**
     * 获取指定 API Key 的所有 Webhook
     *
     * @param int $apiKeyId
     * @return array
     */
    public function getByApiKey($apiKeyId) {
        try {
            $stmt = $this->pdo->prepare("
                SELECT id, url, events, status, created_at, updated_at
                FROM webhooks
                WHERE api_key_id = ?
                ORDER BY created_at DESC
            ");

            $stmt->execute([$apiKeyId]);
            $webhooks = $stmt->fetchAll(PDO::FETCH_ASSOC);

            // 解析 JSON events
            foreach ($webhooks as &$webhook) {
                $webhook['events'] = json_decode($webhook['events'], true);
            }

            return $webhooks;

        } catch (Exception $e) {
            error_log("Failed to get webhooks: " . $e->getMessage());
            return [];
        }
    }


    /**
     * 删除 Webhook
     *
     * @param int $webhookId
     * @param int $apiKeyId 用于验证权限
     * @return bool
     */
    public function delete($webhookId, $apiKeyId) {
        try {
            $stmt = $this->pdo->prepare("
                DELETE FROM webhooks
                WHERE id = ? AND api_key_id = ?
            ");

            $stmt->execute([$webhookId, $apiKeyId]);

            return $stmt->rowCount() > 0;

        } catch (Exception $e) {
            error_log("Failed to delete webhook: " . $e->getMessage());
            return false;
        }
    }
}
