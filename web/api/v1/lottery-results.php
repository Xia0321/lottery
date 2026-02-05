<?php
/**
 * 彩票结果查询 API
 *
 * GET /api/v1/lottery-results/latest - 查询最新开奖结果
 * GET /api/v1/lottery-results - 查询历史开奖结果
 */

require_once(__DIR__ . '/BaseController.php');

class LotteryResultsController extends BaseController {

    private $redis = null;

    public function __construct($requireAuth = true) {
        parent::__construct($requireAuth);

        // 初始化 Redis（用于缓存）
        if (class_exists('Redis')) {
            try {
                $this->redis = new Redis();
                $this->redis->connect('127.0.0.1', 6379);
            } catch (Exception $e) {
                error_log("Redis connection failed: " . $e->getMessage());
                // Redis 不可用时降级到数据库
            }
        }
    }

    public function handle() {
        // 要求 GET 方法
        $this->requireMethod('GET');

        // 解析路径
        $requestUri = $_SERVER['REQUEST_URI'];

        // GET /api/v1/lottery-results/latest
        if (preg_match('#/api/v1/lottery-results/latest(\?.*)?$#', $requestUri)) {
            $this->getLatestResult();
        }
        // GET /api/v1/lottery-results
        elseif (preg_match('#/api/v1/lottery-results(\?.*)?$#', $requestUri)) {
            $this->getResultsList();
        }
        else {
            $this->respondError('Invalid request path', ErrorCode::PARAM_INVALID, 400);
        }
    }

    /**
     * 查询最新开奖结果
     */
    private function getLatestResult() {
        try {
            // 获取游戏 ID 参数
            $gameId = $this->getOptionalParam('game_id', 'int', null, 'GET');

            // 构建缓存键
            $cacheKey = "lottery:latest:" . ($gameId ?? 'all');
            $cacheTTL = 300; // 缓存 5 分钟

            // 尝试从 Redis 获取缓存
            if ($this->redis) {
                $cached = $this->redis->get($cacheKey);
                if ($cached !== false) {
                    $data = json_decode($cached, true);
                    $this->respondSuccess($data, 'Latest lottery result (cached)');
                    return;
                }
            }

            // 从数据库查询
            global $tb_lottery_result;

            $sql = "
                SELECT id, gameid, qishu, result, result_sum, created_at
                FROM `$tb_lottery_result`
            ";

            $params = [];
            $types = '';

            if ($gameId !== null) {
                $sql .= " WHERE gameid = ?";
                $params[] = $gameId;
                $types = 'i';
            }

            $sql .= " ORDER BY created_at DESC LIMIT 1";

            $stmt = $this->mysqli->prepare($sql);
            if (!$stmt) {
                throw new Exception('Prepare failed: ' . $this->mysqli->error);
            }

            if (!empty($params)) {
                $stmt->bind_param($types, ...$params);
            }

            $stmt->execute();
            $result = $stmt->get_result();
            $row = $result->fetch_assoc();
            $stmt->close();

            if (!$row) {
                $this->respondError('No lottery results found', ErrorCode::BIZ_RESOURCE_NOT_FOUND, 404);
            }

            // 格式化响应数据
            $data = [
                'result_id' => (int)$row['id'],
                'game_id' => (int)$row['gameid'],
                'period' => $row['qishu'],
                'result' => $row['result'],
                'result_sum' => (int)$row['result_sum'],
                'created_at' => $row['created_at']
            ];

            // 缓存到 Redis
            if ($this->redis) {
                $this->redis->setex($cacheKey, $cacheTTL, json_encode($data));
            }

            $this->respondSuccess($data, 'Latest lottery result');

        } catch (Exception $e) {
            error_log("Get latest lottery result error: " . $e->getMessage());
            $this->respondError('Database error', ErrorCode::SYS_DATABASE_ERROR, 500);
        }
    }

    /**
     * 查询历史开奖结果列表
     */
    private function getResultsList() {
        try {
            // 获取表名
            global $tb_lottery_result;

            // 获取查询参数
            $gameId = $this->getOptionalParam('game_id', 'int', null, 'GET');
            $startDate = $this->getOptionalParam('start_date', 'string', null, 'GET');
            $endDate = $this->getOptionalParam('end_date', 'string', null, 'GET');

            // 分页参数
            list($page, $pageSize) = $this->getPagination();
            $offset = ($page - 1) * $pageSize;

            // 构建查询条件
            $where = ['1=1'];
            $params = [];
            $types = '';

            // 游戏 ID 过滤
            if ($gameId !== null) {
                $where[] = "gameid = ?";
                $params[] = $gameId;
                $types .= 'i';
            }

            // 日期范围过滤
            if ($startDate !== null) {
                $where[] = "created_at >= ?";
                $params[] = $startDate;
                $types .= 's';
            }

            if ($endDate !== null) {
                $where[] = "created_at <= ?";
                $params[] = $endDate;
                $types .= 's';
            }

            $whereClause = implode(' AND ', $where);

            // 查询总数
            $countSql = "
                SELECT COUNT(*) as total
                FROM `$tb_lottery_result`
                WHERE $whereClause
            ";
            $countStmt = $this->mysqli->prepare($countSql);

            if (!empty($params)) {
                $countStmt->bind_param($types, ...$params);
            }

            $countStmt->execute();
            $countResult = $countStmt->get_result();
            $total = $countResult->fetch_assoc()['total'];
            $countStmt->close();

            // 查询列表数据
            $sql = "
                SELECT id, gameid, qishu, result, result_sum, created_at
                FROM `$tb_lottery_result`
                WHERE $whereClause
                ORDER BY created_at DESC
                LIMIT ? OFFSET ?
            ";

            $stmt = $this->mysqli->prepare($sql);
            if (!$stmt) {
                throw new Exception('Prepare failed: ' . $this->mysqli->error);
            }

            // 添加分页参数
            $params[] = $pageSize;
            $params[] = $offset;
            $types .= 'ii';

            $stmt->bind_param($types, ...$params);
            $stmt->execute();
            $result = $stmt->get_result();

            $results = [];
            while ($row = $result->fetch_assoc()) {
                $results[] = [
                    'result_id' => (int)$row['id'],
                    'game_id' => (int)$row['gameid'],
                    'period' => $row['qishu'],
                    'result' => $row['result'],
                    'result_sum' => (int)$row['result_sum'],
                    'created_at' => $row['created_at']
                ];
            }
            $stmt->close();

            // 构建响应
            $data = [
                'list' => $results,
                'pagination' => [
                    'page' => $page,
                    'page_size' => $pageSize,
                    'total' => (int)$total,
                    'total_pages' => (int)ceil($total / $pageSize)
                ]
            ];

            $this->respondSuccess($data);

        } catch (Exception $e) {
            error_log("Get lottery results list error: " . $e->getMessage());
            $this->respondError('Database error', ErrorCode::SYS_DATABASE_ERROR, 500);
        }
    }

    /**
     * 析构函数 - 关闭 Redis 连接
     */
    public function __destruct() {
        if ($this->redis) {
            try {
                $this->redis->close();
            } catch (Exception $e) {
                // Ignore close errors
            }
        }
        parent::__destruct();
    }
}

// 执行控制器
$controller = new LotteryResultsController();
$controller->handle();
