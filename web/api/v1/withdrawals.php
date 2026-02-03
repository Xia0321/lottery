<?php
/**
 * 提现管理 API
 *
 * POST /api/v1/withdrawals.php - 创建提现
 * GET /api/v1/withdrawals.php - 查询提现记录
 * GET /api/v1/withdrawals.php/{id} - 查询单条提现
 */

require_once(__DIR__ . '/BaseController.php');

class WithdrawalsController extends BaseController {

    public function handle() {
        // 解析路径
        $requestUri = $_SERVER['REQUEST_URI'];
        $method = $this->getMethod();

        // GET /api/v1/withdrawals/{id}
        if (preg_match('#/api/v1/withdrawals/(\d+)$#', $requestUri, $matches)) {
            $this->requireMethod('GET');
            $this->getWithdrawalById($matches[1]);
        }
        // POST/GET /api/v1/withdrawals
        elseif (preg_match('#/api/v1/withdrawals(\?.*)?$#', $requestUri)) {
            if ($method === 'GET') {
                $this->getWithdrawalsList();
            } elseif ($method === 'POST') {
                $this->createWithdrawal();
            } else {
                $this->respondError('Method not allowed', ErrorCode::PARAM_INVALID, 405);
            }
        }
        else {
            $this->respondError('Invalid request path', ErrorCode::PARAM_INVALID, 400);
        }
    }

    /**
     * 创建提现
     */
    private function createWithdrawal() {
        try {
            // 获取参数
            $userId = $this->getRequiredParam('user_id', 'int', 'POST');
            $amount = $this->getRequiredParam('amount', 'float', 'POST');
            $bankAccount = $this->getRequiredParam('bank_account', 'string', 'POST');
            $bankName = $this->getOptionalParam('bank_name', 'string', '', 'POST');
            $remark = $this->getOptionalParam('remark', 'string', '', 'POST');

            // 验证金额
            if ($amount <= 0) {
                $this->respondError('Invalid amount', ErrorCode::PARAM_INVALID, 400);
            }

            // 验证用户
            global $tb_user, $tb_withdrawal;

            $sql = "SELECT userid, username, money, status, fid FROM `$tb_user` WHERE userid = ?";
            $params = [$userId];
            $types = 'i';

            // 数据隔离
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
                $this->respondError('User not found', ErrorCode::BIZ_USER_NOT_FOUND, 404);
            }

            if ($user['status'] != 1) {
                $this->respondError('User account is disabled', ErrorCode::BIZ_USER_DISABLED, 403);
            }

            // 验证余额
            if ($user['money'] < $amount) {
                $this->respondError('Insufficient balance', ErrorCode::BIZ_INSUFFICIENT_BALANCE, 400);
            }

            // 开始事务
            $this->mysqli->begin_transaction();

            try {
                // 冻结余额（扣除）
                $newBalance = $user['money'] - $amount;
                $stmt = $this->mysqli->prepare("
                    UPDATE `$tb_user` SET money = ? WHERE userid = ?
                ");
                $stmt->bind_param('di', $newBalance, $userId);
                $stmt->execute();
                $stmt->close();

                // 生成订单号
                $orderNo = 'WD' . date('YmdHis') . rand(1000, 9999);

                // 创建提现记录
                $sql = "
                    INSERT INTO x_withdrawal (userid, order_no, amount, bank_account, bank_name, status, remark, created_at)
                    VALUES (?, ?, ?, ?, ?, 0, ?, NOW())
                ";

                $stmt = $this->mysqli->prepare($sql);
                if (!$stmt) {
                    throw new Exception('Prepare failed: ' . $this->mysqli->error);
                }

                $stmt->bind_param('isdsss', $userId, $orderNo, $amount, $bankAccount, $bankName, $remark);
                $stmt->execute();
                $withdrawalId = $stmt->insert_id;
                $stmt->close();

                // 记录交易日志
                $beforeMoney = $user['money'];
                $afterMoney = $newBalance;
                $logRemark = "Withdrawal - Order: {$orderNo}";

                $stmt = $this->mysqli->prepare("
                    INSERT INTO x_money_log (userid, money, before_money, after_money, type, remark, created_at)
                    VALUES (?, ?, ?, ?, 'withdraw', ?, NOW())
                ");
                $stmt->bind_param('iddds', $userId, $amount, $beforeMoney, $afterMoney, $logRemark);
                $stmt->execute();
                $stmt->close();

                // 提交事务
                $this->mysqli->commit();

                // 触发 Webhook
                $this->triggerWebhook('withdrawal.requested', [
                    'withdrawal_id' => $withdrawalId,
                    'user_id' => $userId,
                    'amount' => $amount,
                    'order_no' => $orderNo
                ]);

                // 返回数据
                $data = [
                    'withdrawal_id' => $withdrawalId,
                    'order_no' => $orderNo,
                    'user_id' => $userId,
                    'amount' => $amount,
                    'bank_account' => $bankAccount,
                    'bank_name' => $bankName,
                    'status' => 0,
                    'status_text' => 'pending',
                    'balance_before' => floatval($beforeMoney),
                    'balance_after' => floatval($afterMoney),
                    'remark' => $remark,
                    'created_at' => date('Y-m-d H:i:s')
                ];

                $this->respondSuccess($data, 'Withdrawal created successfully');

            } catch (Exception $e) {
                // 回滚事务
                $this->mysqli->rollback();
                throw $e;
            }

        } catch (Exception $e) {
            error_log("Create withdrawal error: " . $e->getMessage());
            $this->respondError('Failed to create withdrawal', ErrorCode::SYS_DATABASE_ERROR, 500);
        }
    }

    /**
     * 查询提现记录列表
     */
    private function getWithdrawalsList() {
        try {
            global $tb_withdrawal, $tb_user;

            // 获取查询参数
            $userId = $this->getOptionalParam('user_id', 'int', null, 'GET');
            $status = $this->getOptionalParam('status', 'int', null, 'GET');
            $startDate = $this->getOptionalParam('start_date', 'string', null, 'GET');
            $endDate = $this->getOptionalParam('end_date', 'string', null, 'GET');

            // 分页参数
            list($page, $pageSize) = $this->getPagination();
            $offset = ($page - 1) * $pageSize;

            // 构建查询条件
            $where = ['1=1'];
            $params = [];
            $types = '';

            // 数据隔离
            if ($this->agentId !== null) {
                $where[] = "w.userid IN (SELECT userid FROM `$tb_user` WHERE fid = ?)";
                $params[] = $this->agentId;
                $types .= 'i';
            }

            // 用户筛选
            if ($userId !== null) {
                $where[] = "w.userid = ?";
                $params[] = $userId;
                $types .= 'i';
            }

            // 状态筛选
            if ($status !== null) {
                $where[] = "w.status = ?";
                $params[] = $status;
                $types .= 'i';
            }

            // 日期范围
            if ($startDate !== null) {
                $where[] = "w.created_at >= ?";
                $params[] = $startDate;
                $types .= 's';
            }

            if ($endDate !== null) {
                $where[] = "w.created_at <= ?";
                $params[] = $endDate;
                $types .= 's';
            }

            $whereClause = implode(' AND ', $where);

            // 查询总数
            $countSql = "
                SELECT COUNT(*) as total
                FROM x_withdrawal w
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

            // 查询列表
            $sql = "
                SELECT w.id, w.userid, u.username, w.order_no, w.amount, w.bank_account,
                       w.bank_name, w.status, w.remark, w.created_at, w.completed_at
                FROM x_withdrawal w
                LEFT JOIN `$tb_user` u ON w.userid = u.userid
                WHERE $whereClause
                ORDER BY w.created_at DESC
                LIMIT ? OFFSET ?
            ";

            $stmt = $this->mysqli->prepare($sql);
            $params[] = $pageSize;
            $params[] = $offset;
            $types .= 'ii';

            $stmt->bind_param($types, ...$params);
            $stmt->execute();
            $result = $stmt->get_result();

            $withdrawals = [];
            while ($row = $result->fetch_assoc()) {
                $withdrawals[] = [
                    'withdrawal_id' => (int)$row['id'],
                    'user_id' => (int)$row['userid'],
                    'username' => $row['username'],
                    'order_no' => $row['order_no'],
                    'amount' => floatval($row['amount']),
                    'bank_account' => $row['bank_account'],
                    'bank_name' => $row['bank_name'],
                    'status' => (int)$row['status'],
                    'status_text' => $this->getWithdrawalStatus($row['status']),
                    'remark' => $row['remark'],
                    'created_at' => $row['created_at'],
                    'completed_at' => $row['completed_at']
                ];
            }
            $stmt->close();

            // 构建响应
            $data = [
                'list' => $withdrawals,
                'pagination' => [
                    'page' => $page,
                    'page_size' => $pageSize,
                    'total' => (int)$total,
                    'total_pages' => (int)ceil($total / $pageSize)
                ]
            ];

            $this->respondSuccess($data);

        } catch (Exception $e) {
            error_log("Get withdrawals list error: " . $e->getMessage());
            $this->respondError('Database error', ErrorCode::SYS_DATABASE_ERROR, 500);
        }
    }

    /**
     * 查询单条提现记录
     */
    private function getWithdrawalById($withdrawalId) {
        try {
            global $tb_withdrawal, $tb_user;

            $sql = "
                SELECT w.id, w.userid, u.username, w.order_no, w.amount, w.bank_account,
                       w.bank_name, w.status, w.remark, w.created_at, w.completed_at
                FROM x_withdrawal w
                LEFT JOIN `$tb_user` u ON w.userid = u.userid
                WHERE w.id = ?
            ";

            $params = [$withdrawalId];
            $types = 'i';

            // 数据隔离
            if ($this->agentId !== null) {
                $sql .= " AND w.userid IN (SELECT userid FROM `$tb_user` WHERE fid = ?)";
                $params[] = $this->agentId;
                $types .= 'i';
            }

            $stmt = $this->mysqli->prepare($sql);
            $stmt->bind_param($types, ...$params);
            $stmt->execute();
            $result = $stmt->get_result();
            $withdrawal = $result->fetch_assoc();
            $stmt->close();

            if (!$withdrawal) {
                $this->respondError('Withdrawal not found', ErrorCode::BIZ_RESOURCE_NOT_FOUND, 404);
            }

            // 格式化响应
            $data = [
                'withdrawal_id' => (int)$withdrawal['id'],
                'user_id' => (int)$withdrawal['userid'],
                'username' => $withdrawal['username'],
                'order_no' => $withdrawal['order_no'],
                'amount' => floatval($withdrawal['amount']),
                'bank_account' => $withdrawal['bank_account'],
                'bank_name' => $withdrawal['bank_name'],
                'status' => (int)$withdrawal['status'],
                'status_text' => $this->getWithdrawalStatus($withdrawal['status']),
                'remark' => $withdrawal['remark'],
                'created_at' => $withdrawal['created_at'],
                'completed_at' => $withdrawal['completed_at']
            ];

            $this->respondSuccess($data);

        } catch (Exception $e) {
            error_log("Get withdrawal error: " . $e->getMessage());
            $this->respondError('Database error', ErrorCode::SYS_DATABASE_ERROR, 500);
        }
    }

    /**
     * 触发 Webhook
     */
    private function triggerWebhook($eventType, $data) {
        try {
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
                // 简化版发送（实际应使用队列）
                $payload = json_encode([
                    'event' => $eventType,
                    'data' => $data,
                    'timestamp' => time()
                ]);

                $signature = hash_hmac('sha256', $payload, $webhook['secret']);

                $ch = curl_init($webhook['callback_url']);
                curl_setopt($ch, CURLOPT_POST, true);
                curl_setopt($ch, CURLOPT_POSTFIELDS, $payload);
                curl_setopt($ch, CURLOPT_HTTPHEADER, [
                    'Content-Type: application/json',
                    'X-Webhook-Signature: ' . $signature
                ]);
                curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
                curl_setopt($ch, CURLOPT_TIMEOUT, 5);
                curl_exec($ch);
                curl_close($ch);
            }
            $stmt->close();

        } catch (Exception $e) {
            error_log("Trigger webhook error: " . $e->getMessage());
        }
    }

    /**
     * 获取提现状态文本
     */
    private function getWithdrawalStatus($status) {
        switch ((int)$status) {
            case 0:
                return 'pending';
            case 1:
                return 'completed';
            case 2:
                return 'rejected';
            default:
                return 'unknown';
        }
    }
}

// 执行控制器
$controller = new WithdrawalsController();
$controller->handle();
