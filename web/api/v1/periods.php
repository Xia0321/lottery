<?php
/**
 * 期号管理 API
 *
 * GET /api/v1/periods.php/current - 查询当前期号
 * GET /api/v1/periods.php - 查询期号列表
 * GET /api/v1/periods.php/{period} - 查询指定期号详情
 */

require_once(__DIR__ . '/BaseController.php');

class PeriodsController extends BaseController {

    public function handle() {
        // 要求 GET 方法
        $this->requireMethod('GET');

        // 解析路径
        $requestUri = $_SERVER['REQUEST_URI'];

        // GET /api/v1/periods/current
        if (preg_match('#/api/v1/periods/current#', $requestUri)) {
            $this->getCurrentPeriod();
        }
        // GET /api/v1/periods/{period}
        elseif (preg_match('#/api/v1/periods/([0-9]+)$#', $requestUri, $matches)) {
            $this->getPeriodByNumber($matches[1]);
        }
        // GET /api/v1/periods
        elseif (preg_match('#/api/v1/periods(\?.*)?$#', $requestUri)) {
            $this->getPeriodsList();
        }
        else {
            $this->respondError('Invalid request path', ErrorCode::PARAM_INVALID, 400);
        }
    }

    /**
     * 查询当前期号
     */
    private function getCurrentPeriod() {
        try {
            // 获取游戏 ID
            $gameId = $this->getOptionalParam('game_id', 'int', 1, 'GET');

            // 简化版：根据时间生成期号
            // 实际应从游戏配置表或期号表查询
            $currentPeriod = $this->generateCurrentPeriod();

            // 计算下一期开奖时间（简化版：每小时一期）
            $nextDrawTime = date('Y-m-d H:00:00', strtotime('+1 hour'));

            // 计算剩余时间（秒）
            $remainingSeconds = strtotime($nextDrawTime) - time();

            $data = [
                'game_id' => $gameId,
                'current_period' => $currentPeriod,
                'next_period' => $this->getNextPeriod($currentPeriod),
                'next_draw_time' => $nextDrawTime,
                'remaining_seconds' => $remainingSeconds,
                'status' => 'open', // open, closed, drawing
                'server_time' => date('Y-m-d H:i:s')
            ];

            $this->respondSuccess($data);

        } catch (Exception $e) {
            error_log("Get current period error: " . $e->getMessage());
            $this->respondError('Database error', ErrorCode::SYS_DATABASE_ERROR, 500);
        }
    }

    /**
     * 查询期号列表
     */
    private function getPeriodsList() {
        try {
            // 获取查询参数
            $gameId = $this->getOptionalParam('game_id', 'int', 1, 'GET');
            $startDate = $this->getOptionalParam('start_date', 'string', null, 'GET');
            $endDate = $this->getOptionalParam('end_date', 'string', null, 'GET');

            // 分页参数
            list($page, $pageSize) = $this->getPagination();
            $offset = ($page - 1) * $pageSize;

            // 从开奖结果表查询期号列表
            global $tb_lottery_result;

            // 构建查询条件
            $where = ['gameid = ?'];
            $params = [$gameId];
            $types = 'i';

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
            $countSql = "SELECT COUNT(*) as total FROM `$tb_lottery_result` WHERE $whereClause";
            $countStmt = $this->mysqli->prepare($countSql);
            $countStmt->bind_param($types, ...$params);
            $countStmt->execute();
            $countResult = $countStmt->get_result();
            $total = $countResult->fetch_assoc()['total'];
            $countStmt->close();

            // 查询列表
            $sql = "
                SELECT id, gameid, qishu, result, result_sum, created_at
                FROM `$tb_lottery_result`
                WHERE $whereClause
                ORDER BY created_at DESC
                LIMIT ? OFFSET ?
            ";

            $stmt = $this->mysqli->prepare($sql);
            $params[] = $pageSize;
            $params[] = $offset;
            $types .= 'ii';

            $stmt->bind_param($types, ...$params);
            $stmt->execute();
            $result = $stmt->get_result();

            $periods = [];
            while ($row = $result->fetch_assoc()) {
                $periods[] = [
                    'result_id' => (int)$row['id'],
                    'game_id' => (int)$row['gameid'],
                    'period' => $row['qishu'],
                    'result' => $row['result'],
                    'result_sum' => (int)$row['result_sum'],
                    'status' => 'completed',
                    'draw_time' => $row['created_at']
                ];
            }
            $stmt->close();

            // 构建响应
            $data = [
                'list' => $periods,
                'pagination' => [
                    'page' => $page,
                    'page_size' => $pageSize,
                    'total' => (int)$total,
                    'total_pages' => (int)ceil($total / $pageSize)
                ]
            ];

            $this->respondSuccess($data);

        } catch (Exception $e) {
            error_log("Get periods list error: " . $e->getMessage());
            $this->respondError('Database error', ErrorCode::SYS_DATABASE_ERROR, 500);
        }
    }

    /**
     * 查询指定期号详情
     */
    private function getPeriodByNumber($period) {
        try {
            global $tb_lottery_result;

            // 查询期号
            $sql = "
                SELECT id, gameid, qishu, result, result_sum, created_at
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
                $this->respondError('Period not found', ErrorCode::BIZ_RESOURCE_NOT_FOUND, 404);
            }

            // 统计该期投注信息
            global $tb_bet;
            $stmt = $this->mysqli->prepare("
                SELECT COUNT(*) as bet_count, SUM(money) as total_amount
                FROM `$tb_bet`
                WHERE qishu = ?
            ");
            $stmt->bind_param('s', $period);
            $stmt->execute();
            $betResult = $stmt->get_result();
            $betStats = $betResult->fetch_assoc();
            $stmt->close();

            $data = [
                'result_id' => (int)$row['id'],
                'game_id' => (int)$row['gameid'],
                'period' => $row['qishu'],
                'result' => $row['result'],
                'result_sum' => (int)$row['result_sum'],
                'status' => 'completed',
                'draw_time' => $row['created_at'],
                'statistics' => [
                    'bet_count' => (int)$betStats['bet_count'],
                    'total_amount' => floatval($betStats['total_amount'] ?? 0)
                ]
            ];

            $this->respondSuccess($data);

        } catch (Exception $e) {
            error_log("Get period by number error: " . $e->getMessage());
            $this->respondError('Database error', ErrorCode::SYS_DATABASE_ERROR, 500);
        }
    }

    /**
     * 生成当前期号（简化版）
     */
    private function generateCurrentPeriod() {
        // 格式：YYYYMMDDHH (年月日时)
        return date('YmdH') . '00';
    }

    /**
     * 获取下一期号
     */
    private function getNextPeriod($currentPeriod) {
        // 简化版：当前期号 + 1
        return (string)((int)$currentPeriod + 100);
    }
}

// 执行控制器
$controller = new PeriodsController();
$controller->handle();
