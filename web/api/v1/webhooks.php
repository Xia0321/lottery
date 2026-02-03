<?php
/**
 * Webhook 管理 API
 *
 * POST /api/v1/webhooks - 创建 Webhook 订阅
 * GET /api/v1/webhooks - 查询 Webhook 列表
 * DELETE /api/v1/webhooks/{id} - 删除 Webhook
 * GET /api/v1/webhooks/{id}/logs - 查询 Webhook 调用日志
 */

require_once __DIR__ . '/../db_config.php';

// 强制 HTTPS
if (empty($_SERVER['HTTPS']) || $_SERVER['HTTPS'] === 'off') {
    http_response_code(403);
    ApiResponse::error(ErrorCode::SYS_SERVICE_UNAVAILABLE, '必须使用 HTTPS 访问');
}

// 认证
$apiAuth = new ApiAuth($pdo, $redis);
$apiKeyData = $apiAuth->authenticate();

// 路由处理
$requestUri = $_SERVER['REQUEST_URI'];
$requestMethod = $_SERVER['REQUEST_METHOD'];

// 解析路径
if (preg_match('#/api/v1/webhooks/(\d+)/logs$#', $requestUri, $matches)) {
    // GET /api/v1/webhooks/{id}/logs
    if ($requestMethod === 'GET') {
        getWebhookLogs($pdo, $matches[1], $apiKeyData['id']);
    } else {
        ApiResponse::error(ErrorCode::PARAM_INVALID_VALUE, '不支持的请求方法');
    }
} elseif (preg_match('#/api/v1/webhooks/(\d+)$#', $requestUri, $matches)) {
    // DELETE /api/v1/webhooks/{id}
    if ($requestMethod === 'DELETE') {
        deleteWebhook($pdo, $matches[1], $apiKeyData['id']);
    } else {
        ApiResponse::error(ErrorCode::PARAM_INVALID_VALUE, '不支持的请求方法');
    }
} elseif (preg_match('#/api/v1/webhooks$#', $requestUri)) {
    if ($requestMethod === 'POST') {
        // POST /api/v1/webhooks
        createWebhook($pdo, $apiKeyData['id']);
    } elseif ($requestMethod === 'GET') {
        // GET /api/v1/webhooks
        getWebhooks($pdo, $apiKeyData['id']);
    } else {
        ApiResponse::error(ErrorCode::PARAM_INVALID_VALUE, '不支持的请求方法');
    }
} else {
    ApiResponse::error(ErrorCode::PARAM_INVALID_VALUE, '无效的请求路径');
}


/**
 * 创建 Webhook 订阅
 *
 * @param PDO $pdo
 * @param int $apiKeyId
 * @return void
 */
function createWebhook($pdo, $apiKeyId) {
    try {
        // 获取 POST 数据
        $input = json_decode(file_get_contents('php://input'), true);

        if (!$input) {
            ApiResponse::error(ErrorCode::PARAM_INVALID_FORMAT, '无效的 JSON 格式');
        }

        // 验证必填字段
        if (empty($input['url'])) {
            ApiResponse::error(ErrorCode::PARAM_MISSING, '缺少参数: url');
        }

        if (empty($input['events']) || !is_array($input['events'])) {
            ApiResponse::error(ErrorCode::PARAM_MISSING, '缺少参数: events（必须是数组）');
        }

        // 验证事件类型
        $validEvents = ['bet.created', 'bet.won', 'deposit.completed', 'withdrawal.requested'];
        foreach ($input['events'] as $event) {
            if (!in_array($event, $validEvents)) {
                ApiResponse::error(ErrorCode::PARAM_INVALID_VALUE, "无效的事件类型: {$event}");
            }
        }

        // 创建 Webhook
        $webhookManager = new WebhookManager($pdo);
        $webhook = $webhookManager->create($apiKeyId, $input['url'], $input['events']);

        // 记录日志
        ApiResponse::logApiCall($apiKeyId, $_SERVER['REQUEST_URI'], 'POST', 201, $_SERVER['REMOTE_ADDR']);

        // 返回成功响应
        ApiResponse::success($webhook, ['http_code' => 201]);

    } catch (Exception $e) {
        error_log("Create webhook error: " . $e->getMessage());
        ApiResponse::error(ErrorCode::SYS_DATABASE_ERROR, $e->getMessage());
    }
}


/**
 * 查询 Webhook 列表
 *
 * @param PDO $pdo
 * @param int $apiKeyId
 * @return void
 */
function getWebhooks($pdo, $apiKeyId) {
    try {
        $webhookManager = new WebhookManager($pdo);
        $webhooks = $webhookManager->getByApiKey($apiKeyId);

        // 记录日志
        ApiResponse::logApiCall($apiKeyId, $_SERVER['REQUEST_URI'], 'GET', 200, $_SERVER['REMOTE_ADDR']);

        // 返回成功响应
        ApiResponse::success($webhooks);

    } catch (Exception $e) {
        error_log("Get webhooks error: " . $e->getMessage());
        ApiResponse::error(ErrorCode::SYS_DATABASE_ERROR);
    }
}


/**
 * 删除 Webhook
 *
 * @param PDO $pdo
 * @param int $webhookId
 * @param int $apiKeyId
 * @return void
 */
function deleteWebhook($pdo, $webhookId, $apiKeyId) {
    try {
        $webhookManager = new WebhookManager($pdo);
        $success = $webhookManager->delete($webhookId, $apiKeyId);

        if (!$success) {
            ApiResponse::logApiCall($apiKeyId, $_SERVER['REQUEST_URI'], 'DELETE', 404, $_SERVER['REMOTE_ADDR']);
            ApiResponse::error(ErrorCode::BIZ_WEBHOOK_NOT_FOUND);
        }

        // 记录日志
        ApiResponse::logApiCall($apiKeyId, $_SERVER['REQUEST_URI'], 'DELETE', 200, $_SERVER['REMOTE_ADDR']);

        // 返回成功响应
        ApiResponse::success(['message' => 'Webhook 已删除']);

    } catch (Exception $e) {
        error_log("Delete webhook error: " . $e->getMessage());
        ApiResponse::error(ErrorCode::SYS_DATABASE_ERROR);
    }
}


/**
 * 查询 Webhook 调用日志
 *
 * @param PDO $pdo
 * @param int $webhookId
 * @param int $apiKeyId
 * @return void
 */
function getWebhookLogs($pdo, $webhookId, $apiKeyId) {
    try {
        // 验证 Webhook 是否属于当前 API Key
        $stmt = $pdo->prepare("
            SELECT id FROM webhooks WHERE id = ? AND api_key_id = ?
        ");
        $stmt->execute([$webhookId, $apiKeyId]);

        if (!$stmt->fetch()) {
            ApiResponse::error(ErrorCode::BIZ_WEBHOOK_NOT_FOUND);
        }

        // 获取查询参数
        $page = isset($_GET['page']) ? intval($_GET['page']) : 1;
        $pageSize = isset($_GET['page_size']) ? intval($_GET['page_size']) : 20;

        // 参数验证
        if ($page < 1) $page = 1;
        if ($pageSize < 1 || $pageSize > 100) $pageSize = 20;

        // 查询日志
        $countStmt = $pdo->prepare("SELECT COUNT(*) as total FROM webhook_logs WHERE webhook_id = ?");
        $countStmt->execute([$webhookId]);
        $total = $countStmt->fetch()['total'];

        $stmt = $pdo->prepare("
            SELECT id, event_type, http_code, status, retry_count, created_at
            FROM webhook_logs
            WHERE webhook_id = ?
            ORDER BY created_at DESC
            LIMIT ? OFFSET ?
        ");
        $stmt->execute([$webhookId, $pageSize, ($page - 1) * $pageSize]);
        $logs = $stmt->fetchAll();

        // 记录日志
        ApiResponse::logApiCall($apiKeyId, $_SERVER['REQUEST_URI'], 'GET', 200, $_SERVER['REMOTE_ADDR']);

        // 返回分页响应
        ApiResponse::paginated($logs, $total, $page, $pageSize);

    } catch (Exception $e) {
        error_log("Get webhook logs error: " . $e->getMessage());
        ApiResponse::error(ErrorCode::SYS_DATABASE_ERROR);
    }
}
