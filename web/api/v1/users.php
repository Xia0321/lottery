<?php
/**
 * 用户查询 API
 *
 * GET /api/v1/users/{id} - 查询单个用户
 * GET /api/v1/users/{id}/balance - 查询用户余额
 */

require_once(__DIR__ . '/BaseController.php');

class UsersController extends BaseController {

    public function handle() {
        // 要求 GET 方法
        $this->requireMethod('GET');

        // 解析路径
        $requestUri = $_SERVER['REQUEST_URI'];

        // GET /api/v1/users/{id}
        if (preg_match('#/api/v1/users/(\d+)$#', $requestUri, $matches)) {
            $this->getUserById($matches[1]);
        }
        // GET /api/v1/users/{id}/balance
        elseif (preg_match('#/api/v1/users/(\d+)/balance$#', $requestUri, $matches)) {
            $this->getUserBalance($matches[1]);
        }
        else {
            $this->respondError('Invalid request path', ErrorCode::PARAM_INVALID, 400);
        }
    }

    /**
     * 查询用户信息
     *
     * @param int $userId 用户 ID
     */
    private function getUserById($userId) {
        try {
            // 获取表名
            global $tb_user;

            // 构建查询（数据隔离）
            $sql = "
                SELECT userid, username, maxmoney, money, status, ifagent, layer, created_at
                FROM `$tb_user`
                WHERE userid = ?
            ";

            $params = [$userId];
            $types = 'i';

            // 数据隔离：只能查询本代理下的用户
            if ($this->agentId !== null) {
                $sql .= " AND fid = ?";
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
            $user = $result->fetch_assoc();
            $stmt->close();

            if (!$user) {
                $this->respondError('User not found', ErrorCode::BIZ_USER_NOT_FOUND, 404);
            }

            // 格式化响应数据
            $data = [
                'user_id' => (int)$user['userid'],
                'username' => $user['username'],
                'max_money' => floatval($user['maxmoney']),
                'balance' => floatval($user['money']),
                'status' => (int)$user['status'],
                'is_agent' => $user['ifagent'] == 1,
                'layer' => (int)$user['layer'],
                'created_at' => $user['created_at']
            ];

            // 返回成功响应
            $this->respondSuccess($data);

        } catch (Exception $e) {
            error_log("Get user error: " . $e->getMessage());
            $this->respondError('Database error', ErrorCode::SYS_DATABASE_ERROR, 500);
        }
    }

    /**
     * 查询用户余额
     *
     * @param int $userId 用户 ID
     */
    private function getUserBalance($userId) {
        try {
            // 获取表名
            global $tb_user;

            // 构建查询
            $sql = "
                SELECT userid, username, money, maxmoney
                FROM `$tb_user`
                WHERE userid = ?
            ";

            $params = [$userId];
            $types = 'i';

            // 数据隔离
            if ($this->agentId !== null) {
                $sql .= " AND fid = ?";
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
            $user = $result->fetch_assoc();
            $stmt->close();

            if (!$user) {
                $this->respondError('User not found', ErrorCode::BIZ_USER_NOT_FOUND, 404);
            }

            // 返回余额信息
            $data = [
                'user_id' => (int)$user['userid'],
                'username' => $user['username'],
                'balance' => floatval($user['money']),
                'credit_limit' => floatval($user['maxmoney'])
            ];

            // 返回成功响应
            $this->respondSuccess($data);

        } catch (Exception $e) {
            error_log("Get user balance error: " . $e->getMessage());
            $this->respondError('Database error', ErrorCode::SYS_DATABASE_ERROR, 500);
        }
    }
}

// 执行控制器
$controller = new UsersController();
$controller->handle();
