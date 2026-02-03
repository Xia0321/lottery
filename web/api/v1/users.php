<?php
/**
 * 用户管理 API
 *
 * POST /api/v1/users - 创建/注册用户
 * GET /api/v1/users/{id} - 查询单个用户
 * GET /api/v1/users/{id}/balance - 查询用户余额
 */

require_once(__DIR__ . '/BaseController.php');

class UsersController extends BaseController {

    public function handle() {
        // 解析路径
        $requestUri = $_SERVER['REQUEST_URI'];
        $method = $this->getMethod();

        // GET /api/v1/users/{id}/balance
        if (preg_match('#/api/v1/users/(\d+)/balance$#', $requestUri, $matches)) {
            $this->requireMethod('GET');
            $this->getUserBalance($matches[1]);
        }
        // GET /api/v1/users/{id}
        elseif (preg_match('#/api/v1/users/(\d+)$#', $requestUri, $matches)) {
            $this->requireMethod('GET');
            $this->getUserById($matches[1]);
        }
        // POST/GET /api/v1/users
        elseif (preg_match('#/api/v1/users(\?.*)?$#', $requestUri)) {
            if ($method === 'POST') {
                $this->createUser();
            } else {
                $this->respondError('Method not allowed', ErrorCode::PARAM_INVALID, 405);
            }
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

    /**
     * 创建/注册用户
     */
    private function createUser() {
        try {
            // 获取参数
            $username = $this->getRequiredParam('username', 'string', 'POST');
            $password = $this->getRequiredParam('password', 'string', 'POST');
            $realname = $this->getOptionalParam('realname', 'string', '', 'POST');
            $mobile = $this->getOptionalParam('mobile', 'string', '', 'POST');
            $email = $this->getOptionalParam('email', 'string', '', 'POST');

            // 验证用户名格式
            if (!preg_match('/^[a-zA-Z0-9_]{4,20}$/', $username)) {
                $this->respondError('Invalid username format (4-20 characters, letters/numbers/underscore only)', ErrorCode::PARAM_INVALID, 400);
            }

            // 验证密码长度
            if (strlen($password) < 6) {
                $this->respondError('Password must be at least 6 characters', ErrorCode::PARAM_INVALID, 400);
            }

            // 验证邮箱格式（如果提供）
            if ($email && !filter_var($email, FILTER_VALIDATE_EMAIL)) {
                $this->respondError('Invalid email format', ErrorCode::PARAM_INVALID, 400);
            }

            // 获取表名
            global $tb_user;

            // 检查用户名是否已存在
            $stmt = $this->mysqli->prepare("SELECT userid FROM `$tb_user` WHERE username = ?");
            $stmt->bind_param('s', $username);
            $stmt->execute();
            $result = $stmt->get_result();

            if ($result->num_rows > 0) {
                $stmt->close();
                $this->respondError('Username already exists', ErrorCode::BIZ_USER_EXISTS, 400);
            }
            $stmt->close();

            // 加密密码
            $hashedPassword = md5($password); // 使用项目现有的加密方式

            // 确定上级代理（使用 API Key 绑定的代理）
            $fid = $this->agentId ?? 0;

            // 创建用户
            $sql = "
                INSERT INTO `$tb_user` (username, userpass, realname, mobile, email, fid, money, maxmoney, status, ifagent, layer, created_at)
                VALUES (?, ?, ?, ?, ?, ?, 0, 0, 1, 0, 1, NOW())
            ";

            $stmt = $this->mysqli->prepare($sql);
            if (!$stmt) {
                throw new Exception('Prepare failed: ' . $this->mysqli->error);
            }

            $stmt->bind_param('sssssi', $username, $hashedPassword, $realname, $mobile, $email, $fid);
            $stmt->execute();
            $userId = $stmt->insert_id;
            $stmt->close();

            // 返回数据
            $data = [
                'user_id' => $userId,
                'username' => $username,
                'realname' => $realname,
                'mobile' => $mobile,
                'email' => $email,
                'balance' => 0.00,
                'status' => 1,
                'created_at' => date('Y-m-d H:i:s')
            ];

            $this->respondSuccess($data, 'User created successfully');

        } catch (Exception $e) {
            error_log("Create user error: " . $e->getMessage());
            $this->respondError('Failed to create user', ErrorCode::SYS_DATABASE_ERROR, 500);
        }
    }
}

// 执行控制器
$controller = new UsersController();
$controller->handle();
