<?php
/**
 * 游戏窗口嵌入页面
 *
 * GET /api/embed/game.php?token={token}
 *
 * 外部平台通过 Token 嵌入游戏窗口（iframe）
 */

require_once(__DIR__ . '/../../data/config.inc.php');
require_once(__DIR__ . '/../../data/db.php');
require_once(__DIR__ . '/../../global/db.inc.php');

// 配置 X-Frame-Options 允许嵌入
header('X-Frame-Options: SAMEORIGIN'); // 或使用 ALLOW-FROM https://your-partner-domain.com

// 获取 Token 参数
$token = $_GET['token'] ?? null;

if (!$token) {
    showError('Missing token parameter');
}

// 验证 Token
global $msql, $tb_user;
$mysqli = $msql->mysqli;

try {
    // 简化版：使用 JWT 或加密 token（此处使用简单的加密方案）
    $tokenData = decryptToken($token);

    if (!$tokenData || !isset($tokenData['user_id']) || !isset($tokenData['exp'])) {
        showError('Invalid or expired token');
    }

    // 检查过期时间
    if ($tokenData['exp'] < time()) {
        showError('Token has expired');
    }

    $userId = (int)$tokenData['user_id'];
    $gameId = $tokenData['game_id'] ?? 1;

    // 查询用户信息
    $stmt = $mysqli->prepare("
        SELECT userid, username, money, status
        FROM `$tb_user`
        WHERE userid = ?
    ");
    $stmt->bind_param('i', $userId);
    $stmt->execute();
    $result = $stmt->get_result();
    $user = $result->fetch_assoc();
    $stmt->close();

    if (!$user) {
        showError('User not found');
    }

    if ($user['status'] != 1) {
        showError('User account is disabled');
    }

    // 构建游戏 URL（根据实际游戏路径）
    $gameUrl = "/web/game.php?gameid=" . (int)$gameId;

} catch (Exception $e) {
    error_log("Token validation error: " . $e->getMessage());
    showError('System error, please try again later');
}

?>
<!DOCTYPE html>
<html lang="zh-CN">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Game Window</title>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: -apple-system, BlinkMacSystemFont, "Segoe UI", Roboto, "Helvetica Neue", Arial, sans-serif;
            background: #f5f5f5;
        }

        .game-container {
            width: 100%;
            height: 100vh;
            display: flex;
            flex-direction: column;
        }

        .game-header {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            padding: 12px 20px;
            box-shadow: 0 2px 4px rgba(0,0,0,0.1);
            display: flex;
            justify-content: space-between;
            align-items: center;
        }

        .user-info {
            display: flex;
            align-items: center;
            gap: 20px;
        }

        .username {
            font-weight: 600;
            color: #fff;
            font-size: 14px;
        }

        .balance {
            color: #ffd700;
            font-weight: 700;
            font-size: 16px;
        }

        .game-frame {
            flex: 1;
            border: none;
            width: 100%;
            background: #fff;
        }

        .loading {
            display: flex;
            align-items: center;
            justify-content: center;
            height: 100vh;
            font-size: 16px;
            color: #666;
        }
    </style>
</head>
<body>
    <div class="game-container">
        <div class="game-header">
            <div class="user-info">
                <span class="username"><?php echo htmlspecialchars($user['username'], ENT_QUOTES, 'UTF-8'); ?></span>
                <span class="balance" id="balance">Balance: ¥<?php echo number_format($user['money'], 2); ?></span>
            </div>
        </div>
        <iframe class="game-frame" src="<?php echo htmlspecialchars($gameUrl, ENT_QUOTES, 'UTF-8'); ?>" allowfullscreen></iframe>
    </div>

    <script>
        // 定期刷新余额（每 10 秒）
        const userId = <?php echo $userId; ?>;

        function refreshBalance() {
            fetch('/web/api/embed/balance.php?user_id=' + userId + '&token=<?php echo urlencode($token); ?>')
                .then(response => response.json())
                .then(data => {
                    if (data.success && data.balance !== undefined) {
                        document.getElementById('balance').textContent = 'Balance: ¥' + parseFloat(data.balance).toFixed(2);
                    }
                })
                .catch(err => console.error('Failed to refresh balance:', err));
        }

        // 每 10 秒刷新一次余额
        setInterval(refreshBalance, 10000);
    </script>
</body>
</html>

<?php

/**
 * 解密 Token
 *
 * @param string $token
 * @return array|null Token 数据
 */
function decryptToken($token) {
    try {
        // 使用配置文件中的密钥
        global $config;
        $secret = $config['api_secret'] ?? 'default_secret_key_change_this';

        // Base64 解码
        $encrypted = base64_decode($token);
        if ($encrypted === false) {
            return null;
        }

        // AES-256-CBC 解密
        $iv = substr($secret, 0, 16);
        $decrypted = openssl_decrypt($encrypted, 'AES-256-CBC', $secret, 0, $iv);

        if ($decrypted === false) {
            return null;
        }

        // 解析 JSON
        $data = json_decode($decrypted, true);

        return $data;

    } catch (Exception $e) {
        error_log("Token decryption error: " . $e->getMessage());
        return null;
    }
}

/**
 * 显示错误信息
 *
 * @param string $message
 */
function showError($message) {
    ?>
    <!DOCTYPE html>
    <html lang="en">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>Error</title>
        <style>
            * {
                margin: 0;
                padding: 0;
                box-sizing: border-box;
            }

            body {
                font-family: -apple-system, BlinkMacSystemFont, "Segoe UI", Roboto, "Helvetica Neue", Arial, sans-serif;
            }

            .error-container {
                display: flex;
                flex-direction: column;
                align-items: center;
                justify-content: center;
                height: 100vh;
                background: #f5f5f5;
                padding: 20px;
            }

            .error-icon {
                font-size: 48px;
                color: #d32f2f;
                margin-bottom: 20px;
            }

            .error-message {
                color: #d32f2f;
                font-size: 18px;
                text-align: center;
            }
        </style>
    </head>
    <body>
        <div class="error-container">
            <div class="error-icon">⚠</div>
            <div class="error-message">
                <?php echo htmlspecialchars($message, ENT_QUOTES, 'UTF-8'); ?>
            </div>
        </div>
    </body>
    </html>
    <?php
    exit;
}
?>
