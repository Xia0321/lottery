<?php
/**
 * 充值管理 API
 *
 * POST /api/v1/deposits.php - 创建充值
 * GET /api/v1/deposits.php - 查询充值记录
 * GET /api/v1/deposits.php/{id} - 查询单条充值
 */

require_once(__DIR__ . '/BaseController.php');

class DepositsController extends BaseController {

    public function handle() {
        // 解析路径
        $requestUri = $_SERVER['REQUEST_URI'];
        $method = $this->getMethod();

        // GET /api/v1/deposits/{id}
        if (preg_match('#/api/v1/deposits/(\d+)$#', $requestUri, $matches)) {
            $this->requireMethod('GET');
            $this->getDepositById($matches[1]);
        }
        // POST/GET /api/v1/deposits
        elseif (preg_match('#/api/v1/deposits(\?.*)?$#', $requestUri)) {
            if ($method === 'GET') {
                $this->getDepositsList();
            } elseif ($method === 'POST') {
                $this->createDeposit();
            } else {
                $this->respondError('Method not allowed', ErrorCode::PARAM_INVALID, 405);
            }
        }
        else {
            $this->respondError('Invalid request path', ErrorCode::PARAM_INVALID, 400);
        }
    }

    /**
     * 创建充值
     */
    private function createDeposit() {
        try {
            // 获取参数
            $userId = $this->getRequiredParam('user_id', 'int', 'POST');
            $amount = $this->getRequiredParam('amount', 'float', 'POST');
            $paymentMethod = $this->getOptionalParam('payment_method', 'string', 'online', 'POST');
            $remark = $this->getOptionalParam('remark', 'string', '', 'POST');

            // 验证金额
            if ($amount <= 0) {
                $this->respondError('Invalid amount', ErrorCode::PARAM_INVALID, 400);
            }

            // 验证用户
            global $tb_user, $tb_deposit;

            $sql = "SELECT userid, username, status, fid FROM `$tb_user` WHERE userid = ?";
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

            // 生成订单号
            $orderNo = 'DEP' . date('YmdHis') . rand(1000, 9999);

            // 创建充值记录
            $sql = "
                INSERT INTO x_deposit (userid, order_no, amount, payment_method, status, remark, created_at)
                VALUES (?, ?, ?, ?, 0, ?, NOW())
            ";

            $stmt = $this->mysqli->prepare($sql);
            if (!$stmt) {
                throw new Exception('Prepare failed: ' . $this->mysqli->error);
            }

            $stmt->bind_param('isdss', $userId, $orderNo, $amount, $paymentMethod, $remark);
            $stmt->execute();
            $depositId = $stmt->insert_id;
            $stmt->close();

            // 返回数据
            $data = [
                'deposit_id' => $depositId,
                'order_no' => $orderNo,
                'user_id' => $userId,
                'amount' => $amount,
                'payment_method' => $paymentMethod,
                'status' => 0,
                'status_text' => 'pending',
                'remark' => $remark,
                'created_at' => date('Y-m-d H:i:s')
            ];

            $this->respondSuccess($data, 'Deposit created successfully');

        } catch (Exception $e) {
            error_log("Create deposit error: " . $e->getMessage());
            $this->respondError('Failed to create deposit', ErrorCode::SYS_DATABASE_ERROR, 500);
        }
    }

    /**
     * 查询充值记录列表
     */
    private function getDepositsList() {
        try {
            global $tb_deposit, $tb_user;

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
                $where[] = "d.userid IN (SELECT userid FROM `$tb_user` WHERE fid = ?)";
                $params[] = $this->agentId;
                $types .= 'i';
            }

            // 用户筛选
            if ($userId !== null) {
                $where[] = "d.userid = ?";
                $params[] = $userId;
                $types .= 'i';
            }

            // 状态筛选
            if ($status !== null) {
                $where[] = "d.status = ?";
                $params[] = $status;
                $types .= 'i';
            }

            // 日期范围
            if ($startDate !== null) {
                $where[] = "d.created_at >= ?";
                $params[] = $startDate;
                $types .= 's';
            }

            if ($endDate !== null) {
                $where[] = "d.created_at <= ?";
                $params[] = $endDate;
                $types .= 's';
            }

            $whereClause = implode(' AND ', $where);

            // 查询总数
            $countSql = "
                SELECT COUNT(*) as total
                FROM x_deposit d
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
                SELECT d.id, d.userid, u.username, d.order_no, d.amount, d.payment_method,
                       d.status, d.remark, d.created_at, d.completed_at
                FROM x_deposit d
                LEFT JOIN `$tb_user` u ON d.userid = u.userid
                WHERE $whereClause
                ORDER BY d.created_at DESC
                LIMIT ? OFFSET ?
            ";

            $stmt = $this->mysqli->prepare($sql);
            $params[] = $pageSize;
            $params[] = $offset;
            $types .= 'ii';

            $stmt->bind_param($types, ...$params);
            $stmt->execute();
            $result = $stmt->get_result();

            $deposits = [];
            while ($row = $result->fetch_assoc()) {
                $deposits[] = [
                    'deposit_id' => (int)$row['id'],
                    'user_id' => (int)$row['userid'],
                    'username' => $row['username'],
                    'order_no' => $row['order_no'],
                    'amount' => floatval($row['amount']),
                    'payment_method' => $row['payment_method'],
                    'status' => (int)$row['status'],
                    'status_text' => $this->getDepositStatus($row['status']),
                    'remark' => $row['remark'],
                    'created_at' => $row['created_at'],
                    'completed_at' => $row['completed_at']
                ];
            }
            $stmt->close();

            // 构建响应
            $data = [
                'list' => $deposits,
                'pagination' => [
                    'page' => $page,
                    'page_size' => $pageSize,
                    'total' => (int)$total,
                    'total_pages' => (int)ceil($total / $pageSize)
                ]
            ];

            $this->respondSuccess($data);

        } catch (Exception $e) {
            error_log("Get deposits list error: " . $e->getMessage());
            $this->respondError('Database error', ErrorCode::SYS_DATABASE_ERROR, 500);
        }
    }

    /**
     * 查询单条充值记录
     */
    private function getDepositById($depositId) {
        try {
            global $tb_deposit, $tb_user;

            $sql = "
                SELECT d.id, d.userid, u.username, d.order_no, d.amount, d.payment_method,
                       d.status, d.remark, d.created_at, d.completed_at
                FROM x_deposit d
                LEFT JOIN `$tb_user` u ON d.userid = u.userid
                WHERE d.id = ?
            ";

            $params = [$depositId];
            $types = 'i';

            // 数据隔离
            if ($this->agentId !== null) {
                $sql .= " AND d.userid IN (SELECT userid FROM `$tb_user` WHERE fid = ?)";
                $params[] = $this->agentId;
                $types .= 'i';
            }

            $stmt = $this->mysqli->prepare($sql);
            $stmt->bind_param($types, ...$params);
            $stmt->execute();
            $result = $stmt->get_result();
            $deposit = $result->fetch_assoc();
            $stmt->close();

            if (!$deposit) {
                $this->respondError('Deposit not found', ErrorCode::BIZ_RESOURCE_NOT_FOUND, 404);
            }

            // 格式化响应
            $data = [
                'deposit_id' => (int)$deposit['id'],
                'user_id' => (int)$deposit['userid'],
                'username' => $deposit['username'],
                'order_no' => $deposit['order_no'],
                'amount' => floatval($deposit['amount']),
                'payment_method' => $deposit['payment_method'],
                'status' => (int)$deposit['status'],
                'status_text' => $this->getDepositStatus($deposit['status']),
                'remark' => $deposit['remark'],
                'created_at' => $deposit['created_at'],
                'completed_at' => $deposit['completed_at']
            ];

            $this->respondSuccess($data);

        } catch (Exception $e) {
            error_log("Get deposit error: " . $e->getMessage());
            $this->respondError('Database error', ErrorCode::SYS_DATABASE_ERROR, 500);
        }
    }

    /**
     * 获取充值状态文本
     */
    private function getDepositStatus($status) {
        switch ((int)$status) {
            case 0:
                return 'pending';
            case 1:
                return 'completed';
            case 2:
                return 'failed';
            default:
                return 'unknown';
        }
    }
}

// 执行控制器
$controller = new DepositsController();
$controller->handle();
