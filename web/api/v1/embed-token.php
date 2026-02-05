<?php
/**
 * 生成游戏嵌入 Token API
 *
 * POST /api/v1/embed-token.php
 *
 * 请求参数:
 * - user_id: 用户 ID
 * - game_id: 游戏 ID (可选，默认 1)
 * - expires_in: Token 有效期（秒），默认 300 (5分钟)
 */

require_once(__DIR__ . '/BaseController.php');
require_once(__DIR__ . '/../../class/api/EmbedTokenManager.php');

class EmbedTokenController extends BaseController {

    public function handle() {
        // 要求 POST 方法
        $this->requireMethod('POST');

        $this->generateToken();
    }

    /**
     * 生成嵌入 Token
     */
    private function generateToken() {
        try {
            // 获取参数
            $userId = $this->getRequiredParam('user_id', 'int', 'POST');
            $gameId = $this->getOptionalParam('game_id', 'int', 1, 'POST');
            $expiresIn = $this->getOptionalParam('expires_in', 'int', 300, 'POST', ['min' => 60, 'max' => 3600]);

            // 验证用户是否存在
            global $tb_user;

            $sql = "SELECT userid, username, status FROM `$tb_user` WHERE userid = ?";
            $params = [$userId];
            $types = 'i';

            // 数据隔离：只能为本代理下的用户生成 Token
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

            if ($user['status'] != 1) {
                $this->respondError('User account is disabled', ErrorCode::BIZ_USER_DISABLED, 403);
            }

            // 使用 EmbedTokenManager 生成 Token
            $tokenManager = new EmbedTokenManager($this->mysqli);
            $tokenResult = $tokenManager->generateToken($userId, $this->apiKeyData['id'], $expiresIn);

            // 构建嵌入 URL
            $protocol = (!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off') ? 'https' : 'http';
            $host = $_SERVER['HTTP_HOST'];
            $embedUrl = $protocol . '://' . $host . '/web/embed/game.php?token=' . urlencode($tokenResult['token']);

            // 响应数据
            $data = [
                'token' => $tokenResult['token'],
                'embed_url' => $embedUrl,
                'user_id' => (int)$userId,
                'username' => $user['username'],
                'game_id' => $gameId,
                'expires_at' => $tokenResult['expires_at'],
                'expires_in' => $expiresIn
            ];

            $this->respondSuccess($data, 'Embed token generated successfully');

        } catch (Exception $e) {
            error_log("Generate embed token error: " . $e->getMessage());
            $this->respondError('Failed to generate token', ErrorCode::SYS_DATABASE_ERROR, 500);
        }
    }
}

// 执行控制器
$controller = new EmbedTokenController();
$controller->handle();
