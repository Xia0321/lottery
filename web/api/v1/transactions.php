<?php
/**
 * 交易记录查询 API
 *
 * GET /api/v1/transactions - 查询交易记录列表
 * GET /api/v1/transactions/{id} - 查询单个交易记录
 */

require_once(__DIR__ . '/BaseController.php');

class TransactionsController extends BaseController {

    public function handle() {
        // 要求 GET 方法
        $this->requireMethod('GET');

        // 解析路径
        $requestUri = $_SERVER['REQUEST_URI'];

        // GET /api/v1/transactions/{id}
        if (preg_match('#/api/v1/transactions/(\d+)$#', $requestUri, $matches)) {
            $this->getTransactionById($matches[1]);
        }
        // GET /api/v1/transactions
        elseif (preg_match('#/api/v1/transactions(\?.*)?$#', $requestUri)) {
            $this->getTransactionsList();
        }
        else {
            $this->respondError('Invalid request path', ErrorCode::PARAM_INVALID, 400);
        }
    }

    /**
     * 查询交易记录列表
     */
    private function getTransactionsList() {
        try {
            // 获取表名
            global $tb_money_log, $tb_user;

            // 获取查询参数
            $userId = $this->getOptionalParam('user_id', 'int', null, 'GET');
            $type = $this->getOptionalParam('type', 'string', null, 'GET');
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
                $where[] = "u.fid = ?";
                $params[] = $this->agentId;
                $types .= 'i';
            }

            // 用户 ID 过滤
            if ($userId !== null) {
                $where[] = "t.userid = ?";
                $params[] = $userId;
                $types .= 'i';
            }

            // 类型过滤
            if ($type !== null) {
                $where[] = "t.type = ?";
                $params[] = $type;
                $types .= 's';
            }

            // 日期范围过滤
            if ($startDate !== null) {
                $where[] = "t.time >= ?";
                $params[] = $startDate;
                $types .= 's';
            }

            if ($endDate !== null) {
                $where[] = "t.time <= ?";
                $params[] = $endDate;
                $types .= 's';
            }

            $whereClause = implode(' AND ', $where);

            // 查询总数
            $countSql = "
                SELECT COUNT(*) as total
                FROM `$tb_money_log` t
                LEFT JOIN `$tb_user` u ON t.userid = u.userid
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

            // 查询列表数据（适配 x_money_log 实际列名）
            $sql = "
                SELECT t.id, t.userid, u.username, t.money, t.usermoney,
                       t.type, t.bz, t.time
                FROM `$tb_money_log` t
                LEFT JOIN `$tb_user` u ON t.userid = u.userid
                WHERE $whereClause
                ORDER BY t.time DESC
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

            $transactions = [];
            while ($row = $result->fetch_assoc()) {
                $transactions[] = [
                    'transaction_id' => (int)$row['id'],
                    'user_id' => (int)$row['userid'],
                    'username' => $row['username'],
                    'amount' => floatval($row['money']),
                    'balance' => floatval($row['usermoney']),
                    'type' => $row['type'],
                    'remark' => $row['bz'],
                    'created_at' => $row['time']
                ];
            }
            $stmt->close();

            // 构建响应
            $data = [
                'list' => $transactions,
                'pagination' => [
                    'page' => $page,
                    'page_size' => $pageSize,
                    'total' => (int)$total,
                    'total_pages' => (int)ceil($total / $pageSize)
                ]
            ];

            $this->respondSuccess($data);

        } catch (Exception $e) {
            error_log("Get transactions list error: " . $e->getMessage());
            $this->respondError('Database error', ErrorCode::SYS_DATABASE_ERROR, 500);
        }
    }

    /**
     * 查询单个交易记录
     *
     * @param int $transactionId 交易 ID
     */
    private function getTransactionById($transactionId) {
        try {
            // 获取表名
            global $tb_money_log, $tb_user;

            // 构建查询（适配 x_money_log 实际列名）
            $sql = "
                SELECT t.id, t.userid, u.username, t.money, t.usermoney,
                       t.type, t.bz, t.time
                FROM `$tb_money_log` t
                LEFT JOIN `$tb_user` u ON t.userid = u.userid
                WHERE t.id = ?
            ";

            $params = [$transactionId];
            $types = 'i';

            // 数据隔离
            if ($this->agentId !== null) {
                $sql .= " AND u.fid = ?";
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
            $transaction = $result->fetch_assoc();
            $stmt->close();

            if (!$transaction) {
                $this->respondError('Transaction not found', ErrorCode::BIZ_RESOURCE_NOT_FOUND, 404);
            }

            // 格式化响应数据
            $data = [
                'transaction_id' => (int)$transaction['id'],
                'user_id' => (int)$transaction['userid'],
                'username' => $transaction['username'],
                'amount' => floatval($transaction['money']),
                'balance' => floatval($transaction['usermoney']),
                'type' => $transaction['type'],
                'remark' => $transaction['bz'],
                'created_at' => $transaction['time']
            ];

            $this->respondSuccess($data);

        } catch (Exception $e) {
            error_log("Get transaction error: " . $e->getMessage());
            $this->respondError('Database error', ErrorCode::SYS_DATABASE_ERROR, 500);
        }
    }
}

// 执行控制器
$controller = new TransactionsController();
$controller->handle();
