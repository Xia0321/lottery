<?php
/**
 * 余额刷新 API（用于嵌入游戏窗口）
 *
 * GET /api/embed/balance.php?user_id={id}&token={token}
 */

require_once(__DIR__ . '/../../data/config.inc.php');
require_once(__DIR__ . '/../../data/db.php');
require_once(__DIR__ . '/../../global/db.inc.php');

header('Content-Type: application/json');

// 获取参数
$userId = $_GET['user_id'] ?? null;
$token = $_GET['token'] ?? null;

if (!$userId || !$token) {
    echo json_encode(['success' => false, 'message' => 'Missing parameters']);
    exit;
}

try {
    // 验证 Token
    $tokenData = decryptToken($token);

    if (!$tokenData || !isset($tokenData['user_id']) || $tokenData['user_id'] != $userId) {
        echo json_encode(['success' => false, 'message' => 'Invalid token']);
        exit;
    }

    // 检查过期时间
    if ($tokenData['exp'] < time()) {
        echo json_encode(['success' => false, 'message' => 'Token expired']);
        exit;
    }

    // 查询余额
    global $msql, $tb_user;
    $mysqli = $msql->mysqli;

    $stmt = $mysqli->prepare("SELECT money FROM `$tb_user` WHERE userid = ?");
    $stmt->bind_param('i', $userId);
    $stmt->execute();
    $result = $stmt->get_result();
    $user = $result->fetch_assoc();
    $stmt->close();

    if (!$user) {
        echo json_encode(['success' => false, 'message' => 'User not found']);
        exit;
    }

    echo json_encode([
        'success' => true,
        'balance' => floatval($user['money'])
    ]);

} catch (Exception $e) {
    error_log("Balance refresh error: " . $e->getMessage());
    echo json_encode(['success' => false, 'message' => 'System error']);
}

/**
 * 解密 Token
 *
 * @param string $token
 * @return array|null
 */
function decryptToken($token) {
    try {
        global $config;
        $secret = $config['api_secret'] ?? 'default_secret_key_change_this';

        $encrypted = base64_decode($token);
        if ($encrypted === false) {
            return null;
        }

        $iv = substr($secret, 0, 16);
        $decrypted = openssl_decrypt($encrypted, 'AES-256-CBC', $secret, 0, $iv);

        if ($decrypted === false) {
            return null;
        }

        return json_decode($decrypted, true);

    } catch (Exception $e) {
        return null;
    }
}
