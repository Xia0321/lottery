<?php
/**
 * 投注记录查询 API
 *
 * GET /api/v1/bets - 查询投注列表（支持时间范围、分页、状态筛选）
 * GET /api/v1/bets/{id} - 查询单条投注记录
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
if (preg_match('#/api/v1/bets/(\d+)$#', $requestUri, $matches)) {
    // GET /api/v1/bets/{id}
    if ($requestMethod === 'GET') {
        getBetById($pdo, $matches[1], $agentId, $apiKeyData['id']);
    } else {
        ApiResponse::error(ErrorCode::PARAM_INVALID_VALUE, '不支持的请求方法');
    }
} elseif (preg_match('#/api/v1/bets$#', $requestUri)) {
    // GET /api/v1/bets
    if ($requestMethod === 'GET') {
        getBets($pdo, $agentId, $apiKeyData['id']);
    } else {
        ApiResponse::error(ErrorCode::PARAM_INVALID_VALUE, '不支持的请求方法');
    }
} else {
    ApiResponse::error(ErrorCode::PARAM_INVALID_VALUE, '无效的请求路径');
}


/**
 * 查询投注列表
 *
 * @param PDO $pdo
 * @param int|null $agentId
 * @param int $apiKeyId
 * @return void
 */
function getBets($pdo, $agentId, $apiKeyId) {
    try {
        // 获取查询参数
        $userId = $_GET['user_id'] ?? null;
        $startTime = $_GET['start_time'] ?? null;
        $endTime = $_GET['end_time'] ?? null;
        $status = $_GET['status'] ?? null;
        $page = isset($_GET['page']) ? intval($_GET['page']) : 1;
        $pageSize = isset($_GET['page_size']) ? intval($_GET['page_size']) : 20;

        // 参数验证
        if ($page < 1) $page = 1;
        if ($pageSize < 1 || $pageSize > 100) $pageSize = 20;

        // 构建查询条件
        $sql = "
            SELECT b.id, b.userid, u.username, b.qishu, b.money, b.win_money,
                   b.status, b.created_at, b.updated_at
            FROM x_bet b
            INNER JOIN x_user u ON b.userid = u.userid
            WHERE 1=1
        ";
        $params = [];

        // 数据隔离
        if ($agentId !== null) {
            $sql .= " AND u.fid = ?";
            $params[] = $agentId;
        }

        // 用户筛选
        if ($userId !== null) {
            $sql .= " AND b.userid = ?";
            $params[] = intval($userId);
        }

        // 时间范围筛选
        if ($startTime !== null) {
            $sql .= " AND b.created_at >= ?";
            $params[] = $startTime;
        }
        if ($endTime !== null) {
            $sql .= " AND b.created_at <= ?";
            $params[] = $endTime;
        }

        // 状态筛选（0=未开奖，1=已中奖，2=未中奖）
        if ($status !== null && in_array($status, ['0', '1', '2'])) {
            $sql .= " AND b.status = ?";
            $params[] = intval($status);
        }

        // 计算总数
        $countSql = "SELECT COUNT(*) as total FROM (" . $sql . ") as count_query";
        $stmt = $pdo->prepare($countSql);
        $stmt->execute($params);
        $total = $stmt->fetch()['total'];

        // 分页查询
        $sql .= " ORDER BY b.created_at DESC LIMIT ? OFFSET ?";
        $params[] = $pageSize;
        $params[] = ($page - 1) * $pageSize;

        $stmt = $pdo->prepare($sql);
        $stmt->execute($params);
        $bets = $stmt->fetchAll();

        // 格式化响应数据
        $data = [];
        foreach ($bets as $bet) {
            $data[] = [
                'bet_id' => $bet['id'],
                'user_id' => $bet['userid'],
                'username' => $bet['username'],
                'period' => $bet['qishu'],
                'bet_amount' => floatval($bet['money']),
                'win_amount' => floatval($bet['win_money']),
                'status' => intval($bet['status']),
                'status_text' => getBetStatusText($bet['status']),
                'created_at' => $bet['created_at'],
                'updated_at' => $bet['updated_at']
            ];
        }

        // 记录日志
        ApiResponse::logApiCall($apiKeyId, $_SERVER['REQUEST_URI'], 'GET', 200, $_SERVER['REMOTE_ADDR']);

        // 返回分页响应
        ApiResponse::paginated($data, $total, $page, $pageSize);

    } catch (Exception $e) {
        error_log("Get bets error: " . $e->getMessage());
        ApiResponse::error(ErrorCode::SYS_DATABASE_ERROR);
    }
}


/**
 * 查询单条投注记录
 *
 * @param PDO $pdo
 * @param int $betId
 * @param int|null $agentId
 * @param int $apiKeyId
 * @return void
 */
function getBetById($pdo, $betId, $agentId, $apiKeyId) {
    try {
        // 构建查询
        $sql = "
            SELECT b.id, b.userid, u.username, b.qishu, b.money, b.win_money,
                   b.status, b.created_at, b.updated_at
            FROM x_bet b
            INNER JOIN x_user u ON b.userid = u.userid
            WHERE b.id = ?
        ";

        $params = [$betId];

        // 数据隔离
        if ($agentId !== null) {
            $sql .= " AND u.fid = ?";
            $params[] = $agentId;
        }

        $stmt = $pdo->prepare($sql);
        $stmt->execute($params);
        $bet = $stmt->fetch();

        if (!$bet) {
            ApiResponse::logApiCall($apiKeyId, $_SERVER['REQUEST_URI'], 'GET', 404, $_SERVER['REMOTE_ADDR']);
            ApiResponse::error(ErrorCode::BIZ_BET_NOT_FOUND);
        }

        // 格式化响应数据
        $data = [
            'bet_id' => $bet['id'],
            'user_id' => $bet['userid'],
            'username' => $bet['username'],
            'period' => $bet['qishu'],
            'bet_amount' => floatval($bet['money']),
            'win_amount' => floatval($bet['win_money']),
            'status' => intval($bet['status']),
            'status_text' => getBetStatusText($bet['status']),
            'created_at' => $bet['created_at'],
            'updated_at' => $bet['updated_at']
        ];

        // 记录日志
        ApiResponse::logApiCall($apiKeyId, $_SERVER['REQUEST_URI'], 'GET', 200, $_SERVER['REMOTE_ADDR']);

        // 返回成功响应
        ApiResponse::success($data);

    } catch (Exception $e) {
        error_log("Get bet by id error: " . $e->getMessage());
        ApiResponse::error(ErrorCode::SYS_DATABASE_ERROR);
    }
}


/**
 * 获取投注状态文本
 *
 * @param int $status
 * @return string
 */
function getBetStatusText($status) {
    switch ($status) {
        case 0:
            return '未开奖';
        case 1:
            return '已中奖';
        case 2:
            return '未中奖';
        default:
            return '未知';
    }
}
