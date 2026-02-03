<?php
/**
 * 游戏窗口嵌入页面
 *
 * GET /api/embed/game?token={token}
 *
 * 外部平台通过 Token 嵌入游戏窗口（iframe）
 */

require_once __DIR__ . '/../db_config.php';

// 获取 Token 参数
$token = $_GET['token'] ?? null;

if (!$token) {
    showError('缺少 Token 参数');
}

// 验证 Token
try {
    $tokenData = validateToken($pdo, $redis, $token);

    if (!$tokenData) {
        showError('Token 无效或已过期');
    }

    $userId = $tokenData['user_id'];
    $gameUrl = $tokenData['game_url'];

    // 查询用户信息
    $stmt = $pdo->prepare("
        SELECT userid, username, money, status
        FROM x_user
        WHERE userid = ?
    ");
    $stmt->execute([$userId]);
    $user = $stmt->fetch();

    if (!$user) {
        showError('用户不存在');
    }

    if ($user['status'] != 1) {
        showError('用户账号已被禁用');
    }

} catch (Exception $e) {
    error_log("Token validation error: " . $e->getMessage());
    showError('系统错误，请稍后重试');
}

?>
<!DOCTYPE html>
<html lang="zh-CN">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>游戏窗口</title>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: Arial, sans-serif;
            background: #f5f5f5;
        }

        .game-container {
            width: 100%;
            height: 100vh;
            display: flex;
            flex-direction: column;
        }

        .game-header {
            background: #fff;
            padding: 10px 20px;
            border-bottom: 1px solid #e0e0e0;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }

        .user-info {
            display: flex;
            align-items: center;
            gap: 15px;
        }

        .username {
            font-weight: bold;
            color: #333;
        }

        .balance {
            color: #ff6b00;
            font-weight: bold;
        }

        .game-frame {
            flex: 1;
            border: none;
            width: 100%;
        }

        .error-message {
            display: flex;
            align-items: center;
            justify-content: center;
            height: 100vh;
            background: #f5f5f5;
            color: #d32f2f;
            font-size: 18px;
        }
    </style>
</head>
<body>
    <div class="game-container">
        <div class="game-header">
            <div class="user-info">
                <span class="username"><?php echo htmlspecialchars($user['username']); ?></span>
                <span class="balance">余额: ¥<?php echo number_format($user['money'], 2); ?></span>
            </div>
        </div>
        <iframe class="game-frame" src="<?php echo htmlspecialchars($gameUrl); ?>" allowfullscreen></iframe>
    </div>

    <script>
        // 定期刷新余额（每 5 秒）
        setInterval(function() {
            fetch('<?php echo '/api/v1/users/' . $userId . '/balance'; ?>?api_key=<?php echo urlencode($_GET['api_key'] ?? ''); ?>&timestamp=<?php echo time(); ?>&sign=<?php echo urlencode($_GET['sign'] ?? ''); ?>')
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        document.querySelector('.balance').textContent = '余额: ¥' + data.data.balance.toFixed(2);
                    }
                })
                .catch(err => console.error('Failed to refresh balance:', err));
        }, 5000);
    </script>
</body>
</html>

<?php

/**
 * 验证 Token
 *
 * @param PDO $pdo
 * @param Redis|null $redis
 * @param string $token
 * @return array|null Token 数据（user_id, game_url）
 */
function validateToken($pdo, $redis, $token) {
    try {
        // 从数据库查询 Token（假设存储在 game_tokens 表）
        $stmt = $pdo->prepare("
            SELECT user_id, game_url, expires_at, nonce
            FROM game_tokens
            WHERE token = ? AND status = 1
        ");
        $stmt->execute([$token]);
        $tokenData = $stmt->fetch();

        if (!$tokenData) {
            return null;
        }

        // 验证过期时间
        if (strtotime($tokenData['expires_at']) < time()) {
            return null;
        }

        // 验证 Nonce（防重放攻击）
        if ($redis) {
            $nonceKey = "token_nonce:{$tokenData['nonce']}";
            if ($redis->exists($nonceKey)) {
                // Token 已被使用
                return null;
            }

            // 标记 Token 为已使用（TTL 设置为 Token 过期时间）
            $ttl = strtotime($tokenData['expires_at']) - time();
            $redis->setex($nonceKey, max($ttl, 1), 1);
        }

        return [
            'user_id' => $tokenData['user_id'],
            'game_url' => $tokenData['game_url']
        ];

    } catch (Exception $e) {
        error_log("Token validation error: " . $e->getMessage());
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
    <html lang="zh-CN">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>错误</title>
        <style>
            * {
                margin: 0;
                padding: 0;
                box-sizing: border-box;
            }

            .error-message {
                display: flex;
                align-items: center;
                justify-content: center;
                height: 100vh;
                background: #f5f5f5;
                color: #d32f2f;
                font-size: 18px;
                font-family: Arial, sans-serif;
            }
        </style>
    </head>
    <body>
        <div class="error-message">
            <?php echo htmlspecialchars($message); ?>
        </div>
    </body>
    </html>
    <?php
    exit;
}
?>
