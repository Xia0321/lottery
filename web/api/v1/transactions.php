<?php
/**
 * 交易记录查询 API
 *
 * GET /api/v1/transactions - 查询交易列表（支持时间范围、分页、类型筛选）
 * GET /api/v1/transactions/{id} - 查询单条交易记录
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
if (preg_match('#/api/v1/transactions/(\d+)$#', $requestUri, $matches)) {
    // GET /api/v1/transactions/{id}
    if ($requestMethod === 'GET') {
        getTransactionById($pdo, $matches[1], $agentId, $apiKeyData['id']);
    } else {
        ApiResponse::error(ErrorCode::PARAM_INVALID_VALUE, '不支持的请求方法');
    }
} elseif (preg_match('#/api/v1/transactions$#', $requestUri)) {
    // GET /api/v1/transactions
    if ($requestMethod === 'GET') {
        getTransactions($pdo, $agentId, $apiKeyData['id']);
    } else {
        ApiResponse::error(ErrorCode::PARAM_INVALID_VALUE, '不支持的请求方法');
    }
} else {
    ApiResponse::error(ErrorCode::PARAM_INVALID_VALUE, '无效的请求路径');
}


/**
 * 查询交易列表
 *
 * @param PDO $pdo
 * @param int|null $agentId
 * @param int $apiKeyId
 * @return void
 */
function getTransactions($pdo, $agentId, $apiKeyId) {
    try {
        // 获取查询参数
        $userId = $_GET['user_id'] ?? null;
        $startTime = $_GET['start_time'] ?? null;
        $endTime = $_GET['end_time'] ?? null;
        $type = $_GET['type'] ?? null;
        $page = isset($_GET['page']) ? intval($_GET['page']) : 1;
        $pageSize = isset($_GET['page_size']) ? intval($_GET['page_size']) : 20;

        // 参数验证
        if ($page < 1) $page = 1;
        if ($pageSize < 1 || $pageSize > 100) $pageSize = 20;

        // 构建查询条件
        $sql = "
            SELECT t.id, t.userid, u.username, t.money, t.before_money, t.after_money,
                   t.type, t.remark, t.created_at
            FROM x_money_log t
            INNER JOIN x_user u ON t.userid = u.userid
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
            $sql .= " AND t.userid = ?";
            $params[] = intval($userId);
        }

        // 时间范围筛选
        if ($startTime !== null) {
            $sql .= " AND t.created_at >= ?";
            $params[] = $startTime;
        }
        if ($endTime !== null) {
            $sql .= " AND t.created_at <= ?";
            $params[] = $endTime;
        }

        // 类型筛选
        if ($type !== null && in_array($type, ['deposit', 'withdraw', 'bet', 'win', 'rebate', 'other'])) {
            $sql .= " AND t.type = ?";
            $params[] = $type;
        }

        // 计算总数
        $countSql = "SELECT COUNT(*) as total FROM (" . $sql . ") as count_query";
        $stmt = $pdo->prepare($countSql);
        $stmt->execute($params);
        $total = $stmt->fetch()['total'];

        // 分页查询
        $sql .= " ORDER BY t.created_at DESC LIMIT ? OFFSET ?";
        $params[] = $pageSize;
        $params[] = ($page - 1) * $pageSize;

        $stmt = $pdo->prepare($sql);
        $stmt->execute($params);
        $transactions = $stmt->fetchAll();

        // 格式化响应数据
        $data = [];
        foreach ($transactions as $transaction) {
            $data[] = [
                'transaction_id' => $transaction['id'],
                'user_id' => $transaction['userid'],
                'username' => $transaction['username'],
                'amount' => floatval($transaction['money']),
                'balance_before' => floatval($transaction['before_money']),
                'balance_after' => floatval($transaction['after_money']),
                'type' => $transaction['type'],
                'type_text' => getTransactionTypeText($transaction['type']),
                'remark' => $transaction['remark'],
                'created_at' => $transaction['created_at']
            ];
        }

        // 记录日志
        ApiResponse::logApiCall($apiKeyId, $_SERVER['REQUEST_URI'], 'GET', 200, $_SERVER['REMOTE_ADDR']);

        // 返回分页响应
        ApiResponse::paginated($data, $total, $page, $pageSize);

    } catch (Exception $e) {
        error_log("Get transactions error: " . $e->getMessage());
        ApiResponse::error(ErrorCode::SYS_DATABASE_ERROR);
    }
}


/**
 * 查询单条交易记录
 *
 * @param PDO $pdo
 * @param int $transactionId
 * @param int|null $agentId
 * @param int $apiKeyId
 * @return void
 */
function getTransactionById($pdo, $transactionId, $agentId, $apiKeyId) {
    try {
        // 构建查询
        $sql = "
            SELECT t.id, t.userid, u.username, t.money, t.before_money, t.after_money,
                   t.type, t.remark, t.created_at
            FROM x_money_log t
            INNER JOIN x_user u ON t.userid = u.userid
            WHERE t.id = ?
        ";

        $params = [$transactionId];

        // 数据隔离
        if ($agentId !== null) {
            $sql .= " AND u.fid = ?";
            $params[] = $agentId;
        }

        $stmt = $pdo->prepare($sql);
        $stmt->execute($params);
        $transaction = $stmt->fetch();

        if (!$transaction) {
            ApiResponse::logApiCall($apiKeyId, $_SERVER['REQUEST_URI'], 'GET', 404, $_SERVER['REMOTE_ADDR']);
            ApiResponse::error(ErrorCode::BIZ_TRANSACTION_NOT_FOUND);
        }

        // 格式化响应数据
        $data = [
            'transaction_id' => $transaction['id'],
            'user_id' => $transaction['userid'],
            'username' => $transaction['username'],
            'amount' => floatval($transaction['money']),
            'balance_before' => floatval($transaction['before_money']),
            'balance_after' => floatval($transaction['after_money']),
            'type' => $transaction['type'],
            'type_text' => getTransactionTypeText($transaction['type']),
            'remark' => $transaction['remark'],
            'created_at' => $transaction['created_at']
        ];

        // 记录日志
        ApiResponse::logApiCall($apiKeyId, $_SERVER['REQUEST_URI'], 'GET', 200, $_SERVER['REMOTE_ADDR']);

        // 返回成功响应
        ApiResponse::success($data);

    } catch (Exception $e) {
        error_log("Get transaction by id error: " . $e->getMessage());
        ApiResponse::error(ErrorCode::SYS_DATABASE_ERROR);
    }
}


/**
 * 获取交易类型文本
 *
 * @param string $type
 * @return string
 */
function getTransactionTypeText($type) {
    switch ($type) {
        case 'deposit':
            return '充值';
        case 'withdraw':
            return '提现';
        case 'bet':
            return '投注';
        case 'win':
            return '中奖';
        case 'rebate':
            return '返水';
        case 'other':
            return '其他';
        default:
            return '未知';
    }
}
