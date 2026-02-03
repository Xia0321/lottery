<?php
/**
 * 用户查询 API
 *
 * GET /api/v1/users/{id} - 查询单个用户
 * GET /api/v1/users/{id}/balance - 查询用户余额
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
if (preg_match('#/api/v1/users/(\d+)$#', $requestUri, $matches)) {
    // GET /api/v1/users/{id}
    if ($requestMethod === 'GET') {
        getUserById($pdo, $matches[1], $agentId, $apiKeyData['id']);
    } else {
        ApiResponse::error(ErrorCode::PARAM_INVALID_VALUE, '不支持的请求方法');
    }
} elseif (preg_match('#/api/v1/users/(\d+)/balance$#', $requestUri, $matches)) {
    // GET /api/v1/users/{id}/balance
    if ($requestMethod === 'GET') {
        getUserBalance($pdo, $matches[1], $agentId, $apiKeyData['id']);
    } else {
        ApiResponse::error(ErrorCode::PARAM_INVALID_VALUE, '不支持的请求方法');
    }
} else {
    ApiResponse::error(ErrorCode::PARAM_INVALID_VALUE, '无效的请求路径');
}


/**
 * 查询用户信息
 *
 * @param PDO $pdo
 * @param int $userId
 * @param int|null $agentId
 * @param int $apiKeyId
 * @return void
 */
function getUserById($pdo, $userId, $agentId, $apiKeyId) {
    try {
        // 构建查询（如果有 agent_id 则进行数据隔离）
        $sql = "
            SELECT userid, username, maxmoney, money, status, ifagent, layer, created_at
            FROM x_user
            WHERE userid = ?
        ";

        $params = [$userId];

        // 数据隔离：只能查询本代理下的用户
        if ($agentId !== null) {
            $sql .= " AND fid = ?";
            $params[] = $agentId;
        }

        $stmt = $pdo->prepare($sql);
        $stmt->execute($params);
        $user = $stmt->fetch();

        if (!$user) {
            // 记录日志
            ApiResponse::logApiCall($apiKeyId, $_SERVER['REQUEST_URI'], 'GET', 404, $_SERVER['REMOTE_ADDR']);
            ApiResponse::error(ErrorCode::BIZ_USER_NOT_FOUND);
        }

        // 格式化响应数据
        $data = [
            'user_id' => $user['userid'],
            'username' => $user['username'],
            'max_money' => floatval($user['maxmoney']),
            'balance' => floatval($user['money']),
            'status' => $user['status'],
            'is_agent' => $user['ifagent'] == 1,
            'layer' => $user['layer'],
            'created_at' => $user['created_at']
        ];

        // 记录日志
        ApiResponse::logApiCall($apiKeyId, $_SERVER['REQUEST_URI'], 'GET', 200, $_SERVER['REMOTE_ADDR']);

        // 返回成功响应
        ApiResponse::success($data);

    } catch (Exception $e) {
        error_log("Get user error: " . $e->getMessage());
        ApiResponse::error(ErrorCode::SYS_DATABASE_ERROR);
    }
}


/**
 * 查询用户余额
 *
 * @param PDO $pdo
 * @param int $userId
 * @param int|null $agentId
 * @param int $apiKeyId
 * @return void
 */
function getUserBalance($pdo, $userId, $agentId, $apiKeyId) {
    try {
        // 构建查询
        $sql = "
            SELECT userid, username, money, maxmoney
            FROM x_user
            WHERE userid = ?
        ";

        $params = [$userId];

        // 数据隔离
        if ($agentId !== null) {
            $sql .= " AND fid = ?";
            $params[] = $agentId;
        }

        $stmt = $pdo->prepare($sql);
        $stmt->execute($params);
        $user = $stmt->fetch();

        if (!$user) {
            ApiResponse::logApiCall($apiKeyId, $_SERVER['REQUEST_URI'], 'GET', 404, $_SERVER['REMOTE_ADDR']);
            ApiResponse::error(ErrorCode::BIZ_USER_NOT_FOUND);
        }

        // 返回余额信息
        $data = [
            'user_id' => $user['userid'],
            'username' => $user['username'],
            'balance' => floatval($user['money']),
            'credit_limit' => floatval($user['maxmoney'])
        ];

        // 记录日志
        ApiResponse::logApiCall($apiKeyId, $_SERVER['REQUEST_URI'], 'GET', 200, $_SERVER['REMOTE_ADDR']);

        // 返回成功响应
        ApiResponse::success($data);

    } catch (Exception $e) {
        error_log("Get user balance error: " . $e->getMessage());
        ApiResponse::error(ErrorCode::SYS_DATABASE_ERROR);
    }
}
