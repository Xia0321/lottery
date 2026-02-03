<?php
/**
 * Token 生成 API
 *
 * POST /api/v1/tokens/game - 生成游戏嵌入 Token
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

// 获取 agent_id 用于数据隔离
$agentId = $apiKeyData['agent_id'];

// 路由处理
$requestUri = $_SERVER['REQUEST_URI'];
$requestMethod = $_SERVER['REQUEST_METHOD'];

// 解析路径
if (preg_match('#/api/v1/tokens/game$#', $requestUri)) {
    // POST /api/v1/tokens/game
    if ($requestMethod === 'POST') {
        generateGameToken($pdo, $redis, $agentId, $apiKeyData['id']);
    } else {
        ApiResponse::error(ErrorCode::PARAM_INVALID_VALUE, '不支持的请求方法');
    }
} else {
    ApiResponse::error(ErrorCode::PARAM_INVALID_VALUE, '无效的请求路径');
}


/**
 * 生成游戏嵌入 Token
 *
 * @param PDO $pdo
 * @param Redis|null $redis
 * @param int|null $agentId
 * @param int $apiKeyId
 * @return void
 */
function generateGameToken($pdo, $redis, $agentId, $apiKeyId) {
    try {
        // 获取 POST 数据
        $input = json_decode(file_get_contents('php://input'), true);

        if (!$input) {
            ApiResponse::error(ErrorCode::PARAM_INVALID_FORMAT, '无效的 JSON 格式');
        }

        // 验证必填字段
        if (empty($input['user_id'])) {
            ApiResponse::error(ErrorCode::PARAM_MISSING, '缺少参数: user_id');
        }

        $userId = intval($input['user_id']);
        $gameUrl = $input['game_url'] ?? '/game/index.php'; // 默认游戏页面
        $ttl = isset($input['ttl']) ? intval($input['ttl']) : 3600; // 默认 1 小时

        // TTL 限制（最长 24 小时）
        if ($ttl < 60 || $ttl > 86400) {
            ApiResponse::error(ErrorCode::PARAM_INVALID_VALUE, 'TTL 必须在 60-86400 秒之间');
        }

        // 验证用户是否存在且属于当前代理
        $sql = "SELECT userid, username, status FROM x_user WHERE userid = ?";
        $params = [$userId];

        if ($agentId !== null) {
            $sql .= " AND fid = ?";
            $params[] = $agentId;
        }

        $stmt = $pdo->prepare($sql);
        $stmt->execute($params);
        $user = $stmt->fetch();

        if (!$user) {
            ApiResponse::logApiCall($apiKeyId, $_SERVER['REQUEST_URI'], 'POST', 404, $_SERVER['REMOTE_ADDR']);
            ApiResponse::error(ErrorCode::BIZ_USER_NOT_FOUND);
        }

        if ($user['status'] != 1) {
            ApiResponse::error(ErrorCode::BIZ_USER_DISABLED, '用户账号已被禁用');
        }

        // 生成 Token
        $token = bin2hex(random_bytes(32));
        $nonce = bin2hex(random_bytes(8));
        $expiresAt = date('Y-m-d H:i:s', time() + $ttl);

        // 存储 Token 到数据库
        $stmt = $pdo->prepare("
            INSERT INTO game_tokens (token, user_id, game_url, nonce, expires_at, status, created_at)
            VALUES (?, ?, ?, ?, ?, 1, NOW())
        ");
        $stmt->execute([$token, $userId, $gameUrl, $nonce, $expiresAt]);

        // 构建嵌入 URL
        $embedUrl = getBaseUrl() . "/api/embed/game?token={$token}";

        // 返回响应数据
        $data = [
            'token' => $token,
            'embed_url' => $embedUrl,
            'expires_at' => $expiresAt,
            'ttl' => $ttl,
            'user' => [
                'user_id' => $user['userid'],
                'username' => $user['username']
            ]
        ];

        // 记录日志
        ApiResponse::logApiCall($apiKeyId, $_SERVER['REQUEST_URI'], 'POST', 201, $_SERVER['REMOTE_ADDR']);

        // 返回成功响应
        ApiResponse::success($data, ['http_code' => 201]);

    } catch (Exception $e) {
        error_log("Generate game token error: " . $e->getMessage());
        ApiResponse::error(ErrorCode::SYS_DATABASE_ERROR);
    }
}


/**
 * 获取基础 URL
 *
 * @return string
 */
function getBaseUrl() {
    $protocol = (!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off') ? 'https' : 'http';
    $host = $_SERVER['HTTP_HOST'];
    return "{$protocol}://{$host}";
}
