<?php
/**
 * 开奖结果查询 API
 *
 * GET /api/v1/lottery_results - 查询开奖结果列表（支持时间范围、分页）
 * GET /api/v1/lottery_results/{period} - 查询指定期号的开奖结果
 * GET /api/v1/lottery_results/latest - 查询最新开奖结果
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
if (preg_match('#/api/v1/lottery_results/latest$#', $requestUri)) {
    // GET /api/v1/lottery_results/latest
    if ($requestMethod === 'GET') {
        getLatestResult($pdo, $apiKeyData['id']);
    } else {
        ApiResponse::error(ErrorCode::PARAM_INVALID_VALUE, '不支持的请求方法');
    }
} elseif (preg_match('#/api/v1/lottery_results/([^/]+)$#', $requestUri, $matches)) {
    // GET /api/v1/lottery_results/{period}
    if ($requestMethod === 'GET') {
        getResultByPeriod($pdo, $matches[1], $apiKeyData['id']);
    } else {
        ApiResponse::error(ErrorCode::PARAM_INVALID_VALUE, '不支持的请求方法');
    }
} elseif (preg_match('#/api/v1/lottery_results$#', $requestUri)) {
    // GET /api/v1/lottery_results
    if ($requestMethod === 'GET') {
        getResults($pdo, $apiKeyData['id']);
    } else {
        ApiResponse::error(ErrorCode::PARAM_INVALID_VALUE, '不支持的请求方法');
    }
} else {
    ApiResponse::error(ErrorCode::PARAM_INVALID_VALUE, '无效的请求路径');
}


/**
 * 查询开奖结果列表
 *
 * @param PDO $pdo
 * @param int $apiKeyId
 * @return void
 */
function getResults($pdo, $apiKeyId) {
    try {
        // 获取查询参数
        $startTime = $_GET['start_time'] ?? null;
        $endTime = $_GET['end_time'] ?? null;
        $page = isset($_GET['page']) ? intval($_GET['page']) : 1;
        $pageSize = isset($_GET['page_size']) ? intval($_GET['page_size']) : 20;

        // 参数验证
        if ($page < 1) $page = 1;
        if ($pageSize < 1 || $pageSize > 100) $pageSize = 20;

        // 构建查询条件
        $sql = "
            SELECT qishu, number, created_at
            FROM x_lottery
            WHERE 1=1
        ";
        $params = [];

        // 时间范围筛选
        if ($startTime !== null) {
            $sql .= " AND created_at >= ?";
            $params[] = $startTime;
        }
        if ($endTime !== null) {
            $sql .= " AND created_at <= ?";
            $params[] = $endTime;
        }

        // 计算总数
        $countSql = "SELECT COUNT(*) as total FROM (" . $sql . ") as count_query";
        $stmt = $pdo->prepare($countSql);
        $stmt->execute($params);
        $total = $stmt->fetch()['total'];

        // 分页查询
        $sql .= " ORDER BY created_at DESC LIMIT ? OFFSET ?";
        $params[] = $pageSize;
        $params[] = ($page - 1) * $pageSize;

        $stmt = $pdo->prepare($sql);
        $stmt->execute($params);
        $results = $stmt->fetchAll();

        // 格式化响应数据
        $data = [];
        foreach ($results as $result) {
            $data[] = formatLotteryResult($result);
        }

        // 记录日志
        ApiResponse::logApiCall($apiKeyId, $_SERVER['REQUEST_URI'], 'GET', 200, $_SERVER['REMOTE_ADDR']);

        // 返回分页响应
        ApiResponse::paginated($data, $total, $page, $pageSize);

    } catch (Exception $e) {
        error_log("Get lottery results error: " . $e->getMessage());
        ApiResponse::error(ErrorCode::SYS_DATABASE_ERROR);
    }
}


/**
 * 查询指定期号的开奖结果
 *
 * @param PDO $pdo
 * @param string $period
 * @param int $apiKeyId
 * @return void
 */
function getResultByPeriod($pdo, $period, $apiKeyId) {
    try {
        $stmt = $pdo->prepare("
            SELECT qishu, number, created_at
            FROM x_lottery
            WHERE qishu = ?
        ");
        $stmt->execute([$period]);
        $result = $stmt->fetch();

        if (!$result) {
            ApiResponse::logApiCall($apiKeyId, $_SERVER['REQUEST_URI'], 'GET', 404, $_SERVER['REMOTE_ADDR']);
            ApiResponse::error(ErrorCode::BIZ_LOTTERY_RESULT_NOT_FOUND);
        }

        // 格式化响应数据
        $data = formatLotteryResult($result);

        // 记录日志
        ApiResponse::logApiCall($apiKeyId, $_SERVER['REQUEST_URI'], 'GET', 200, $_SERVER['REMOTE_ADDR']);

        // 返回成功响应
        ApiResponse::success($data);

    } catch (Exception $e) {
        error_log("Get lottery result by period error: " . $e->getMessage());
        ApiResponse::error(ErrorCode::SYS_DATABASE_ERROR);
    }
}


/**
 * 查询最新开奖结果
 *
 * @param PDO $pdo
 * @param int $apiKeyId
 * @return void
 */
function getLatestResult($pdo, $apiKeyId) {
    try {
        $stmt = $pdo->query("
            SELECT qishu, number, created_at
            FROM x_lottery
            ORDER BY created_at DESC
            LIMIT 1
        ");
        $result = $stmt->fetch();

        if (!$result) {
            ApiResponse::logApiCall($apiKeyId, $_SERVER['REQUEST_URI'], 'GET', 404, $_SERVER['REMOTE_ADDR']);
            ApiResponse::error(ErrorCode::BIZ_LOTTERY_RESULT_NOT_FOUND);
        }

        // 格式化响应数据
        $data = formatLotteryResult($result);

        // 记录日志
        ApiResponse::logApiCall($apiKeyId, $_SERVER['REQUEST_URI'], 'GET', 200, $_SERVER['REMOTE_ADDR']);

        // 返回成功响应
        ApiResponse::success($data);

    } catch (Exception $e) {
        error_log("Get latest lottery result error: " . $e->getMessage());
        ApiResponse::error(ErrorCode::SYS_DATABASE_ERROR);
    }
}


/**
 * 格式化开奖结果数据
 *
 * @param array $result
 * @return array
 */
function formatLotteryResult($result) {
    // 解析开奖号码（假设格式为逗号分隔的数字）
    $numbers = explode(',', $result['number']);

    return [
        'period' => $result['qishu'],
        'numbers' => array_map('intval', $numbers),
        'numbers_string' => $result['number'],
        'draw_time' => $result['created_at']
    ];
}
