<?php
/**
 * 投注记录查询 API
 *
 * GET /api/v1/bets - 查询投注记录列表
 * GET /api/v1/bets/{id} - 查询单个投注记录
 */

require_once(__DIR__ . '/BaseController.php');

class BetsController extends BaseController {

    public function handle() {
        // 要求 GET 方法
        $this->requireMethod('GET');

        // 解析路径
        $requestUri = $_SERVER['REQUEST_URI'];

        // GET /api/v1/bets/{id}
        if (preg_match('#/api/v1/bets/(\d+)$#', $requestUri, $matches)) {
            $this->getBetById($matches[1]);
        }
        // GET /api/v1/bets
        elseif (preg_match('#/api/v1/bets(\?.*)?$#', $requestUri)) {
            $this->getBetsList();
        }
        else {
            $this->respondError('Invalid request path', ErrorCode::PARAM_INVALID, 400);
        }
    }

    /**
     * 查询投注记录列表
     */
    private function getBetsList() {
        try {
            // 获取表名
            global $tb_bet, $tb_user;

            // 获取查询参数
            $userId = $this->getOptionalParam('user_id', 'int', null, 'GET');
            $gameId = $this->getOptionalParam('game_id', 'int', null, 'GET');
            $status = $this->getOptionalParam('status', 'int', null, 'GET', ['min' => 0, 'max' => 2]);
            $startDate = $this->getOptionalParam('start_date', 'string', null, 'GET');
            $endDate = $this->getOptionalParam('end_date', 'string', null, 'GET');

            // 分页参数
            list($page, $pageSize) = $this->getPagination();
            $offset = ($page - 1) * $pageSize;

            // 构建查询条件
            $where = ['1=1'];
            $params = [];
            $types = '';

            // 数据隔离：只能查询本代理下的用户投注
            if ($this->agentId !== null) {
                $where[] = "b.userid IN (SELECT userid FROM `$tb_user` WHERE fid = ?)";
                $params[] = $this->agentId;
                $types .= 'i';
            }

            // 用户 ID 过滤
            if ($userId !== null) {
                $where[] = "b.userid = ?";
                $params[] = $userId;
                $types .= 'i';
            }

            // 游戏 ID 过滤
            if ($gameId !== null) {
                $where[] = "b.gameid = ?";
                $params[] = $gameId;
                $types .= 'i';
            }

            // 状态过滤
            if ($status !== null) {
                $where[] = "b.status = ?";
                $params[] = $status;
                $types .= 'i';
            }

            // 日期范围过滤
            if ($startDate !== null) {
                $where[] = "b.created_at >= ?";
                $params[] = $startDate;
                $types .= 's';
            }

            if ($endDate !== null) {
                $where[] = "b.created_at <= ?";
                $params[] = $endDate;
                $types .= 's';
            }

            $whereClause = implode(' AND ', $where);

            // 查询总数
            $countSql = "
                SELECT COUNT(*) as total
                FROM `$tb_bet` b
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
                SELECT b.betid, b.userid, u.username, b.gameid, b.qishu, b.money,
                       b.win_money, b.status, b.bet_content, b.created_at, b.settled_at
                FROM `$tb_bet` b
                LEFT JOIN `$tb_user` u ON b.userid = u.userid
                WHERE $whereClause
                ORDER BY b.created_at DESC
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

            $bets = [];
            while ($row = $result->fetch_assoc()) {
                $bets[] = [
                    'bet_id' => (int)$row['betid'],
                    'user_id' => (int)$row['userid'],
                    'username' => $row['username'],
                    'game_id' => (int)$row['gameid'],
                    'period' => $row['qishu'],
                    'bet_amount' => floatval($row['money']),
                    'win_amount' => floatval($row['win_money']),
                    'status' => (int)$row['status'],
                    'status_text' => $this->getBetStatusText($row['status']),
                    'bet_content' => $row['bet_content'],
                    'created_at' => $row['created_at'],
                    'settled_at' => $row['settled_at']
                ];
            }
            $stmt->close();

            // 构建响应
            $data = [
                'list' => $bets,
                'pagination' => [
                    'page' => $page,
                    'page_size' => $pageSize,
                    'total' => (int)$total,
                    'total_pages' => (int)ceil($total / $pageSize)
                ]
            ];

            $this->respondSuccess($data);

        } catch (Exception $e) {
            error_log("Get bets list error: " . $e->getMessage());
            $this->respondError('Database error', ErrorCode::SYS_DATABASE_ERROR, 500);
        }
    }

    /**
     * 查询单个投注记录
     *
     * @param int $betId 投注 ID
     */
    private function getBetById($betId) {
        try {
            // 获取表名
            global $tb_bet, $tb_user;

            // 构建查询
            $sql = "
                SELECT b.betid, b.userid, u.username, b.gameid, b.qishu, b.money,
                       b.win_money, b.status, b.bet_content, b.bet_type, b.odds,
                       b.created_at, b.settled_at
                FROM `$tb_bet` b
                LEFT JOIN `$tb_user` u ON b.userid = u.userid
                WHERE b.betid = ?
            ";

            $params = [$betId];
            $types = 'i';

            // 数据隔离：只能查询本代理下的用户投注
            if ($this->agentId !== null) {
                $sql .= " AND b.userid IN (SELECT userid FROM `$tb_user` WHERE fid = ?)";
                $params[] = $this->agentId;
                $types .= 'i';
            }

            $stmt = $this->mysqli->prepare($sql);
            if (!$stmt) {
                throw new Exception('Prepare failed: ' . $this->mysqli->error);
            }

            $stmt->bind_param($types, ...$params);
            $stmt->execute();
            $result = $stmt->get_result();
            $bet = $result->fetch_assoc();
            $stmt->close();

            if (!$bet) {
                $this->respondError('Bet not found', ErrorCode::BIZ_RESOURCE_NOT_FOUND, 404);
            }

            // 格式化响应数据
            $data = [
                'bet_id' => (int)$bet['betid'],
                'user_id' => (int)$bet['userid'],
                'username' => $bet['username'],
                'game_id' => (int)$bet['gameid'],
                'period' => $bet['qishu'],
                'bet_amount' => floatval($bet['money']),
                'win_amount' => floatval($bet['win_money']),
                'status' => (int)$bet['status'],
                'status_text' => $this->getBetStatusText($bet['status']),
                'bet_content' => $bet['bet_content'],
                'bet_type' => $bet['bet_type'],
                'odds' => floatval($bet['odds']),
                'created_at' => $bet['created_at'],
                'settled_at' => $bet['settled_at']
            ];

            $this->respondSuccess($data);

        } catch (Exception $e) {
            error_log("Get bet error: " . $e->getMessage());
            $this->respondError('Database error', ErrorCode::SYS_DATABASE_ERROR, 500);
        }
    }

    /**
     * 获取投注状态文本
     *
     * @param int $status
     * @return string
     */
    private function getBetStatusText($status) {
        switch ((int)$status) {
            case 0:
                return 'pending';
            case 1:
                return 'won';
            case 2:
                return 'lost';
            default:
                return 'unknown';
        }
    }
}

// 执行控制器
$controller = new BetsController();
$controller->handle();
