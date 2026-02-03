<?php
/**
 * Webhook 管理 API
 *
 * POST /api/v1/webhooks.php - 创建 Webhook 订阅
 * GET /api/v1/webhooks.php - 查询 Webhook 列表
 * DELETE /api/v1/webhooks.php/{id} - 删除 Webhook
 * GET /api/v1/webhooks.php/{id}/logs - 查询 Webhook 调用日志
 */

require_once(__DIR__ . '/BaseController.php');

class WebhooksController extends BaseController {

    public function handle() {
        // 解析路径
        $requestUri = $_SERVER['REQUEST_URI'];
        $method = $this->getMethod();

        // GET /api/v1/webhooks/{id}/logs
        if (preg_match('#/api/v1/webhooks/(\d+)/logs#', $requestUri, $matches)) {
            $this->requireMethod('GET');
            $this->getWebhookLogs($matches[1]);
        }
        // DELETE /api/v1/webhooks/{id}
        elseif (preg_match('#/api/v1/webhooks/(\d+)#', $requestUri, $matches)) {
            $this->requireMethod('DELETE');
            $this->deleteWebhook($matches[1]);
        }
        // POST/GET /api/v1/webhooks
        elseif (preg_match('#/api/v1/webhooks#', $requestUri)) {
            if ($method === 'POST') {
                $this->createWebhook();
            } elseif ($method === 'GET') {
                $this->getWebhooks();
            } else {
                $this->respondError('Method not allowed', ErrorCode::PARAM_INVALID, 405);
            }
        }
        else {
            $this->respondError('Invalid request path', ErrorCode::PARAM_INVALID, 400);
        }
    }

    /**
     * 创建 Webhook 订阅
     */
    private function createWebhook() {
        try {
            // 获取 POST 数据
            $eventType = $this->getRequiredParam('event_type', 'string', 'POST');
            $callbackUrl = $this->getRequiredParam('callback_url', 'string', 'POST');
            $description = $this->getOptionalParam('description', 'string', '', 'POST');

            // 验证事件类型
            $validEvents = ['bet.created', 'bet.settled', 'deposit.completed', 'withdrawal.completed'];
            if (!in_array($eventType, $validEvents)) {
                $this->respondError("Invalid event type: {$eventType}", ErrorCode::PARAM_INVALID, 400);
            }

            // 验证 URL 格式
            if (!filter_var($callbackUrl, FILTER_VALIDATE_URL)) {
                $this->respondError('Invalid callback URL format', ErrorCode::PARAM_INVALID, 400);
            }

            // 生成 Webhook Secret
            $secret = 'whsec_' . bin2hex(random_bytes(32));

            // 插入数据库
            global $tb_webhooks;
            $sql = "
                INSERT INTO webhooks (api_key_id, event_type, callback_url, secret, description, status, created_at)
                VALUES (?, ?, ?, ?, ?, 1, NOW())
            ";

            $stmt = $this->mysqli->prepare($sql);
            if (!$stmt) {
                throw new Exception('Prepare failed: ' . $this->mysqli->error);
            }

            $stmt->bind_param('issss', $this->apiKeyData['id'], $eventType, $callbackUrl, $secret, $description);
            $stmt->execute();
            $webhookId = $stmt->insert_id;
            $stmt->close();

            // 返回数据
            $data = [
                'id' => $webhookId,
                'event_type' => $eventType,
                'callback_url' => $callbackUrl,
                'secret' => $secret,
                'description' => $description,
                'status' => 1
            ];

            $this->respondSuccess($data, 'Webhook created successfully');

        } catch (Exception $e) {
            error_log("Create webhook error: " . $e->getMessage());
            $this->respondError('Failed to create webhook', ErrorCode::SYS_DATABASE_ERROR, 500);
        }
    }

    /**
     * 查询 Webhook 列表
     */
    private function getWebhooks() {
        try {
            global $tb_webhooks;

            $sql = "
                SELECT id, event_type, callback_url, secret, description, status, created_at
                FROM webhooks
                WHERE api_key_id = ?
                ORDER BY created_at DESC
            ";

            $stmt = $this->mysqli->prepare($sql);
            if (!$stmt) {
                throw new Exception('Prepare failed: ' . $this->mysqli->error);
            }

            $stmt->bind_param('i', $this->apiKeyData['id']);
            $stmt->execute();
            $result = $stmt->get_result();

            $webhooks = [];
            while ($row = $result->fetch_assoc()) {
                $webhooks[] = [
                    'id' => (int)$row['id'],
                    'event_type' => $row['event_type'],
                    'callback_url' => $row['callback_url'],
                    'secret' => $row['secret'],
                    'description' => $row['description'],
                    'status' => (int)$row['status'],
                    'created_at' => $row['created_at']
                ];
            }
            $stmt->close();

            $this->respondSuccess(['list' => $webhooks, 'total' => count($webhooks)]);

        } catch (Exception $e) {
            error_log("Get webhooks error: " . $e->getMessage());
            $this->respondError('Database error', ErrorCode::SYS_DATABASE_ERROR, 500);
        }
    }

    /**
     * 删除 Webhook
     */
    private function deleteWebhook($webhookId) {
        try {
            global $tb_webhooks;

            // 检查 Webhook 是否存在且属于当前 API Key
            $sql = "DELETE FROM webhooks WHERE id = ? AND api_key_id = ?";

            $stmt = $this->mysqli->prepare($sql);
            if (!$stmt) {
                throw new Exception('Prepare failed: ' . $this->mysqli->error);
            }

            $stmt->bind_param('ii', $webhookId, $this->apiKeyData['id']);
            $stmt->execute();
            $affected = $stmt->affected_rows;
            $stmt->close();

            if ($affected === 0) {
                $this->respondError('Webhook not found', ErrorCode::BIZ_RESOURCE_NOT_FOUND, 404);
            }

            $this->respondSuccess(['message' => 'Webhook deleted successfully']);

        } catch (Exception $e) {
            error_log("Delete webhook error: " . $e->getMessage());
            $this->respondError('Database error', ErrorCode::SYS_DATABASE_ERROR, 500);
        }
    }

    /**
     * 查询 Webhook 调用日志
     */
    private function getWebhookLogs($webhookId) {
        try {
            global $tb_webhook_logs;

            // 验证 Webhook 是否属于当前 API Key
            $sql = "SELECT id FROM webhooks WHERE id = ? AND api_key_id = ?";
            $stmt = $this->mysqli->prepare($sql);
            $stmt->bind_param('ii', $webhookId, $this->apiKeyData['id']);
            $stmt->execute();
            $result = $stmt->get_result();

            if ($result->num_rows === 0) {
                $this->respondError('Webhook not found', ErrorCode::BIZ_RESOURCE_NOT_FOUND, 404);
            }
            $stmt->close();

            // 分页参数
            list($page, $pageSize) = $this->getPagination();
            $offset = ($page - 1) * $pageSize;

            // 查询总数
            $countSql = "SELECT COUNT(*) as total FROM webhook_logs WHERE webhook_id = ?";
            $countStmt = $this->mysqli->prepare($countSql);
            $countStmt->bind_param('i', $webhookId);
            $countStmt->execute();
            $countResult = $countStmt->get_result();
            $total = $countResult->fetch_assoc()['total'];
            $countStmt->close();

            // 查询日志列表
            $sql = "
                SELECT id, event_type, http_code, response_body, status, retry_count, created_at
                FROM webhook_logs
                WHERE webhook_id = ?
                ORDER BY created_at DESC
                LIMIT ? OFFSET ?
            ";

            $stmt = $this->mysqli->prepare($sql);
            $stmt->bind_param('iii', $webhookId, $pageSize, $offset);
            $stmt->execute();
            $result = $stmt->get_result();

            $logs = [];
            while ($row = $result->fetch_assoc()) {
                $logs[] = [
                    'id' => (int)$row['id'],
                    'event_type' => $row['event_type'],
                    'http_code' => (int)$row['http_code'],
                    'response_body' => $row['response_body'],
                    'status' => $row['status'],
                    'retry_count' => (int)$row['retry_count'],
                    'created_at' => $row['created_at']
                ];
            }
            $stmt->close();

            $data = [
                'list' => $logs,
                'pagination' => [
                    'page' => $page,
                    'page_size' => $pageSize,
                    'total' => (int)$total,
                    'total_pages' => (int)ceil($total / $pageSize)
                ]
            ];

            $this->respondSuccess($data);

        } catch (Exception $e) {
            error_log("Get webhook logs error: " . $e->getMessage());
            $this->respondError('Database error', ErrorCode::SYS_DATABASE_ERROR, 500);
        }
    }
}

// 执行控制器
$controller = new WebhooksController();
$controller->handle();
