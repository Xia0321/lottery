<?php
/**
 * 投注记录 API
 *
 * GET /api/v1/bets - 查询投注记录列表
 * GET /api/v1/bets/{id} - 查询单个投注记录
 * POST /api/v1/bets - 创建投注
 */

require_once(__DIR__ . '/BaseController.php');

class BetsController extends BaseController {

    public function handle() {
        // 解析路径
        $requestUri = $_SERVER['REQUEST_URI'];
        $method = $this->getMethod();

        // GET /api/v1/bets/{id}
        if (preg_match('#/api/v1/bets/(\d+)$#', $requestUri, $matches)) {
            $this->requireMethod('GET');
            $this->getBetById($matches[1]);
        }
        // POST/GET /api/v1/bets
        elseif (preg_match('#/api/v1/bets(\?.*)?$#', $requestUri)) {
            if ($method === 'GET') {
                $this->getBetsList();
            } elseif ($method === 'POST') {
                $this->createBet();
            } else {
                $this->respondError('Method not allowed', ErrorCode::PARAM_INVALID, 405);
            }
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
     * 创建投注
     */
    private function createBet() {
        try {
            // 获取参数
            $userId = $this->getRequiredParam('user_id', 'int', 'POST');
            $gameId = $this->getRequiredParam('game_id', 'int', 'POST');
            $betAmount = $this->getRequiredParam('bet_amount', 'float', 'POST');
            $betContent = $this->getRequiredParam('bet_content', 'string', 'POST');
            $betType = $this->getOptionalParam('bet_type', 'string', 'normal', 'POST');

            // 数据隔离：只能为本代理下的用户创建投注
            global $tb_user, $tb_bet, $tb_game;

            // 验证用户
            $sql = "SELECT userid, money, status, fid FROM `$tb_user` WHERE userid = ?";
            $params = [$userId];
            $types = 'i';

            if ($this->agentId !== null) {
                $sql .= " AND fid = ?";
                $params[] = $this->agentId;
                $types .= 'i';
            }

            $stmt = $this->mysqli->prepare($sql);
            $stmt->bind_param($types, ...$params);
            $stmt->execute();
            $result = $stmt->get_result();
            $user = $result->fetch_assoc();
            $stmt->close();

            if (!$user) {
                $this->respondError('User not found or access denied', ErrorCode::BIZ_USER_NOT_FOUND, 404);
            }

            if ($user['status'] != 1) {
                $this->respondError('User account is disabled', ErrorCode::BIZ_USER_DISABLED, 403);
            }

            // 验证游戏
            $stmt = $this->mysqli->prepare("
                SELECT gameid, minmoney, maxmoney, status FROM `$tb_game` WHERE gameid = ?
            ");
            $stmt->bind_param('i', $gameId);
            $stmt->execute();
            $result = $stmt->get_result();
            $game = $result->fetch_assoc();
            $stmt->close();

            if (!$game) {
                $this->respondError('Game not found', ErrorCode::BIZ_RESOURCE_NOT_FOUND, 404);
            }

            if ($game['status'] != 1) {
                $this->respondError('Game is not available', ErrorCode::BIZ_GAME_UNAVAILABLE, 400);
            }

            // 验证投注金额
            if ($betAmount < $game['minmoney']) {
                $this->respondError("Bet amount below minimum ({$game['minmoney']})", ErrorCode::BIZ_BET_AMOUNT_INVALID, 400);
            }

            if ($betAmount > $game['maxmoney']) {
                $this->respondError("Bet amount exceeds maximum ({$game['maxmoney']})", ErrorCode::BIZ_BET_AMOUNT_INVALID, 400);
            }

            // 验证余额
            if ($user['money'] < $betAmount) {
                $this->respondError('Insufficient balance', ErrorCode::BIZ_INSUFFICIENT_BALANCE, 400);
            }

            // 开始事务
            $this->mysqli->begin_transaction();

            try {
                // 扣除余额
                $newBalance = $user['money'] - $betAmount;
                $stmt = $this->mysqli->prepare("
                    UPDATE `$tb_user` SET money = ? WHERE userid = ?
                ");
                $stmt->bind_param('di', $newBalance, $userId);
                $stmt->execute();
                $stmt->close();

                // 创建投注记录
                // 获取当前期数（简化版，实际应从游戏表获取）
                $currentPeriod = date('Ymd') . str_pad(date('H'), 3, '0', STR_PAD_LEFT);

                $stmt = $this->mysqli->prepare("
                    INSERT INTO `$tb_bet` (userid, gameid, qishu, money, bet_content, bet_type, status, created_at)
                    VALUES (?, ?, ?, ?, ?, ?, 0, NOW())
                ");
                $stmt->bind_param('iisdss', $userId, $gameId, $currentPeriod, $betAmount, $betContent, $betType);
                $stmt->execute();
                $betId = $stmt->insert_id;
                $stmt->close();

                // 记录交易日志
                $beforeMoney = $user['money'];
                $afterMoney = $newBalance;
                $remark = "Bet created - Game ID: {$gameId}, Bet ID: {$betId}";

                $stmt = $this->mysqli->prepare("
                    INSERT INTO x_money_log (userid, money, before_money, after_money, type, remark, created_at)
                    VALUES (?, ?, ?, ?, 'bet', ?, NOW())
                ");
                $stmt->bind_param('iddds', $userId, $betAmount, $beforeMoney, $afterMoney, $remark);
                $stmt->execute();
                $stmt->close();

                // 提交事务
                $this->mysqli->commit();

                // 触发 Webhook（如果有）
                $this->triggerWebhook('bet.created', [
                    'bet_id' => $betId,
                    'user_id' => $userId,
                    'game_id' => $gameId,
                    'bet_amount' => $betAmount,
                    'period' => $currentPeriod
                ]);

                // 返回成功响应
                $data = [
                    'bet_id' => $betId,
                    'user_id' => $userId,
                    'game_id' => $gameId,
                    'period' => $currentPeriod,
                    'bet_amount' => $betAmount,
                    'bet_content' => $betContent,
                    'bet_type' => $betType,
                    'balance_before' => floatval($beforeMoney),
                    'balance_after' => floatval($afterMoney),
                    'status' => 0,
                    'created_at' => date('Y-m-d H:i:s')
                ];

                $this->respondSuccess($data, 'Bet created successfully');

            } catch (Exception $e) {
                // 回滚事务
                $this->mysqli->rollback();
                throw $e;
            }

        } catch (Exception $e) {
            error_log("Create bet error: " . $e->getMessage());
            $this->respondError('Failed to create bet', ErrorCode::SYS_DATABASE_ERROR, 500);
        }
    }

    /**
     * 触发 Webhook
     */
    private function triggerWebhook($eventType, $data) {
        try {
            // 查询该事件类型的 Webhook
            $sql = "
                SELECT id, callback_url, secret
                FROM webhooks
                WHERE api_key_id = ? AND event_type = ? AND status = 1
            ";

            $stmt = $this->mysqli->prepare($sql);
            $stmt->bind_param('is', $this->apiKeyData['id'], $eventType);
            $stmt->execute();
            $result = $stmt->get_result();

            while ($webhook = $result->fetch_assoc()) {
                // 异步发送 Webhook（简化版，实际应使用队列）
                $this->sendWebhook($webhook['callback_url'], $webhook['secret'], $eventType, $data);
            }
            $stmt->close();

        } catch (Exception $e) {
            error_log("Trigger webhook error: " . $e->getMessage());
            // 不中断主流程
        }
    }

    /**
     * 发送 Webhook
     */
    private function sendWebhook($url, $secret, $eventType, $data) {
        // 构建 payload
        $payload = json_encode([
            'event' => $eventType,
            'data' => $data,
            'timestamp' => time()
        ]);

        // 生成签名
        $signature = hash_hmac('sha256', $payload, $secret);

        // 发送请求（使用 curl）
        $ch = curl_init($url);
        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $payload);
        curl_setopt($ch, CURLOPT_HTTPHEADER, [
            'Content-Type: application/json',
            'X-Webhook-Signature: ' . $signature,
            'X-Webhook-Event: ' . $eventType
        ]);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_TIMEOUT, 5);

        curl_exec($ch);
        curl_close($ch);
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
