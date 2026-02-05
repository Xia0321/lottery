<?php
/**
 * 开奖结果查询 API
 *
 * GET /api/v1/lottery_results.php - 查询开奖结果列表
 * GET /api/v1/lottery_results.php/latest - 查询最新开奖结果
 * GET /api/v1/lottery_results.php/{period} - 查询指定期号的开奖结果
 */

require_once(__DIR__ . '/BaseController.php');

class LotteryResultsController extends BaseController {

    private $redis = null;
    private $cacheEnabled = false;
    private $cacheTTL = 300; // 5 分钟缓存

    /**
     * 初始化 Redis 缓存连接
     */
    private function initCache() {
        try {
            if (class_exists('Redis')) {
                $this->redis = new Redis();
                $this->redis->connect('127.0.0.1', 6379, 2);
                $this->redis->ping();
                $this->cacheEnabled = true;
            }
        } catch (Exception $e) {
            // Redis 不可用时降级，直接查数据库
            $this->cacheEnabled = false;
        }
    }

    /**
     * 从缓存获取数据
     */
    private function getFromCache($key) {
        if (!$this->cacheEnabled) return null;
        try {
            $data = $this->redis->get($key);
            return $data !== false ? json_decode($data, true) : null;
        } catch (Exception $e) {
            return null;
        }
    }

    /**
     * 写入缓存
     */
    private function setCache($key, $data, $ttl = null) {
        if (!$this->cacheEnabled) return;
        try {
            $this->redis->setex($key, $ttl ?? $this->cacheTTL, json_encode($data));
        } catch (Exception $e) {
            // 忽略缓存写入失败
        }
    }

    public function handle() {
        $this->initCache();
        // 要求 GET 方法
        $this->requireMethod('GET');

        // 解析路径
        $requestUri = $_SERVER['REQUEST_URI'];

        // GET /api/v1/lottery_results/latest
        if (preg_match('#/api/v1/lottery_results/latest#', $requestUri)) {
            $this->getLatestResult();
        }
        // GET /api/v1/lottery_results/{period}
        elseif (preg_match('#/api/v1/lottery_results/([^/?]+)$#', $requestUri, $matches)) {
            $this->getResultByPeriod($matches[1]);
        }
        // GET /api/v1/lottery_results
        elseif (preg_match('#/api/v1/lottery_results(\?.*)?$#', $requestUri)) {
            $this->getResults();
        }
        else {
            $this->respondError('Invalid request path', ErrorCode::PARAM_INVALID, 400);
        }
    }

    /**
     * 构建开奖号码字符串 (从 m1-m10 列拼接)
     */
    private function buildResultString($row) {
        $nums = [];
        for ($i = 1; $i <= 10; $i++) {
            $val = $row["m{$i}"] ?? '';
            if ($val !== '') {
                $nums[] = $val;
            }
        }
        return implode(',', $nums);
    }

    /**
     * 查询开奖结果列表
     */
    private function getResults() {
        try {
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

            // 游戏筛选
            if ($gameId !== null) {
                $where[] = "gid = ?";
                $params[] = $gameId;
                $types .= 'i';
            }

            // 日期范围
            if ($startDate !== null) {
                $where[] = "kjtime >= ?";
                $params[] = $startDate;
                $types .= 's';
            }

            if ($endDate !== null) {
                $where[] = "kjtime <= ?";
                $params[] = $endDate;
                $types .= 's';
            }

            $whereClause = implode(' AND ', $where);

            // 查询总数
            $countSql = "SELECT COUNT(*) as total FROM `$tb_lottery_result` WHERE $whereClause";
            $countStmt = $this->mysqli->prepare($countSql);

            if (!empty($params)) {
                $countStmt->bind_param($types, ...$params);
            }

            $countStmt->execute();
            $countResult = $countStmt->get_result();
            $total = $countResult->fetch_assoc()['total'];
            $countStmt->close();

            // 查询列表
            $sql = "
                SELECT id, gid, qishu, m1, m2, m3, m4, m5, m6, m7, m8, m9, m10, kjtime
                FROM `$tb_lottery_result`
                WHERE $whereClause
                ORDER BY kjtime DESC
                LIMIT ? OFFSET ?
            ";

            $stmt = $this->mysqli->prepare($sql);
            $params[] = $pageSize;
            $params[] = $offset;
            $types .= 'ii';

            $stmt->bind_param($types, ...$params);
            $stmt->execute();
            $result = $stmt->get_result();

            $results = [];
            while ($row = $result->fetch_assoc()) {
                $resultStr = $this->buildResultString($row);
                $results[] = [
                    'id' => (int)$row['id'],
                    'game_id' => (int)$row['gid'],
                    'period' => $row['qishu'],
                    'result' => $resultStr,
                    'draw_time' => $row['kjtime']
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
            error_log("Get lottery results error: " . $e->getMessage());
            $this->respondError('Database error', ErrorCode::SYS_DATABASE_ERROR, 500);
        }
    }

    /**
     * 查询最新开奖结果（带 Redis 缓存，5 分钟过期）
     */
    private function getLatestResult() {
        try {
            global $tb_lottery_result;

            // 获取游戏 ID（可选）
            $gameId = $this->getOptionalParam('game_id', 'int', null, 'GET');

            // 尝试从缓存获取
            $cacheKey = 'lottery:latest' . ($gameId !== null ? ":game_{$gameId}" : ':all');
            $cached = $this->getFromCache($cacheKey);
            if ($cached !== null) {
                $this->respondSuccess($cached);
            }

            $sql = "
                SELECT id, gid, qishu, m1, m2, m3, m4, m5, m6, m7, m8, m9, m10, kjtime
                FROM `$tb_lottery_result`
            ";

            $params = [];
            $types = '';

            if ($gameId !== null) {
                $sql .= " WHERE gid = ?";
                $params[] = $gameId;
                $types .= 'i';
            }

            $sql .= " ORDER BY kjtime DESC LIMIT 1";

            $stmt = $this->mysqli->prepare($sql);

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

            // 格式化响应
            $resultStr = $this->buildResultString($row);
            $data = [
                'id' => (int)$row['id'],
                'game_id' => (int)$row['gid'],
                'period' => $row['qishu'],
                'result' => $resultStr,
                'draw_time' => $row['kjtime']
            ];

            // 写入缓存
            $this->setCache($cacheKey, $data);

            $this->respondSuccess($data);

        } catch (Exception $e) {
            error_log("Get latest result error: " . $e->getMessage());
            $this->respondError('Database error', ErrorCode::SYS_DATABASE_ERROR, 500);
        }
    }

    /**
     * 查询指定期号的开奖结果（带缓存，历史结果长期缓存）
     */
    private function getResultByPeriod($period) {
        try {
            global $tb_lottery_result;

            // 尝试从缓存获取（历史结果不变，缓存更久）
            $cacheKey = "lottery:period:{$period}";
            $cached = $this->getFromCache($cacheKey);
            if ($cached !== null) {
                $this->respondSuccess($cached);
            }

            // 查询期号
            $sql = "
                SELECT id, gid, qishu, m1, m2, m3, m4, m5, m6, m7, m8, m9, m10, kjtime
                FROM `$tb_lottery_result`
                WHERE qishu = ?
                LIMIT 1
            ";

            $stmt = $this->mysqli->prepare($sql);
            $stmt->bind_param('s', $period);
            $stmt->execute();
            $result = $stmt->get_result();
            $row = $result->fetch_assoc();
            $stmt->close();

            if (!$row) {
                $this->respondError('Lottery result not found', ErrorCode::BIZ_RESOURCE_NOT_FOUND, 404);
            }

            // 格式化响应
            $resultStr = $this->buildResultString($row);
            $data = [
                'id' => (int)$row['id'],
                'game_id' => (int)$row['gid'],
                'period' => $row['qishu'],
                'result' => $resultStr,
                'draw_time' => $row['kjtime']
            ];

            // 历史结果不会变，缓存 1 小时
            $this->setCache($cacheKey, $data, 3600);

            $this->respondSuccess($data);

        } catch (Exception $e) {
            error_log("Get result by period error: " . $e->getMessage());
            $this->respondError('Database error', ErrorCode::SYS_DATABASE_ERROR, 500);
        }
    }
}

// 执行控制器
$controller = new LotteryResultsController();
$controller->handle();
