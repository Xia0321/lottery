<?php
/**
 * 游戏窗口嵌入页面
 *
 * 用途：供外部系统通过 iframe 嵌入游戏窗口
 *
 * 访问方式：
 *   GET /web/embed/game.php?token=<embed_token>
 *
 * 流程：
 *   1. 验证 Token（AES-256-CBC 加密，包含 user_id、过期时间、nonce）
 *   2. 自动登录用户（设置 Session）
 *   3. 加载游戏页面（去掉导航栏、页脚）
 */

// 设置 X-Frame-Options 允许嵌入（不再使用 DENY）
// 生产环境应配置为具体域名：ALLOW-FROM https://partner.com
header('X-Frame-Options: SAMEORIGIN');
header('Content-Security-Policy: frame-ancestors *');
header('X-Content-Type-Options: nosniff');
header('X-XSS-Protection: 1; mode=block');

// 加载配置和数据库连接
require_once(__DIR__ . '/../data/config.inc.php');
require_once(__DIR__ . '/../data/db.php');
require_once(__DIR__ . '/../global/db.inc.php');
require_once(__DIR__ . '/../class/api/EmbedTokenManager.php');

// 获取数据库连接
global $msql;
$mysqli = $msql->get_mysqli();

// 错误页面函数
function showError($message, $code = 403) {
    http_response_code($code);
    ?>
    <!DOCTYPE html>
    <html lang="zh-CN">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>访问错误</title>
        <style>
            body { font-family: -apple-system, BlinkMacSystemFont, "Segoe UI", Roboto, sans-serif; display: flex; justify-content: center; align-items: center; min-height: 100vh; margin: 0; background: #f5f5f5; }
            .error-box { text-align: center; padding: 40px; background: #fff; border-radius: 8px; box-shadow: 0 2px 10px rgba(0,0,0,0.1); max-width: 400px; }
            .error-box h2 { color: #e74c3c; margin-bottom: 10px; }
            .error-box p { color: #666; }
        </style>
    </head>
    <body>
        <div class="error-box">
            <h2>访问失败</h2>
            <p><?php echo htmlspecialchars($message, ENT_QUOTES, 'UTF-8'); ?></p>
        </div>
    </body>
    </html>
    <?php
    exit;
}

// 1. 获取并验证 Token
$token = isset($_GET['token']) ? trim($_GET['token']) : '';
if (empty($token)) {
    showError('缺少访问令牌');
}

// 2. 验证 Token
$tokenManager = new EmbedTokenManager($mysqli);
$tokenManager->ensureNonceTable(); // 确保 nonce 表存在

$tokenData = $tokenManager->verifyToken($token);
if ($tokenData === false) {
    showError('访问令牌无效或已过期');
}

$userId = $tokenData['user_id'];

// 3. 验证用户状态
global $tb_user;
$stmt = $mysqli->prepare("SELECT userid, username, status FROM `$tb_user` WHERE userid = ?");
$stmt->bind_param('i', $userId);
$stmt->execute();
$result = $stmt->get_result();
$user = $result->fetch_assoc();
$stmt->close();

if (!$user) {
    showError('用户不存在');
}

if ($user['status'] != 1) {
    showError('用户账号已被禁用');
}

// 4. 自动登录：设置 Session
// 使用现有 session 机制
if (!session_id()) {
    session_start();
}

global $config;
$_SESSION['uid'] = $user['userid'];
$_SESSION['check'] = md5($config['allpass'] . $user['userid']);
$_SESSION['username'] = $user['username'];

// 标记为嵌入模式（去掉导航栏等）
$_SESSION['embed_mode'] = true;

// 清理过期 nonce
$tokenManager->cleanupNonces();

// 5. 加载游戏页面（嵌入模式）
// 设置标志位，让游戏页面知道当前是嵌入模式
$isEmbedMode = true;
$hideNav = true;
$hideFooter = true;
$hideHeader = true;

?>
<!DOCTYPE html>
<html lang="zh-CN">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>游戏</title>
    <style>
        /* 嵌入模式：全屏、无边距 */
        * { margin: 0; padding: 0; box-sizing: border-box; }
        html, body { width: 100%; height: 100%; overflow: hidden; }
        iframe { width: 100%; height: 100%; border: none; }

        /* 隐藏导航栏和页脚 */
        .embed-hide { display: none !important; }
        #nav, .navbar, .header, .footer, .sidebar,
        .top-bar, .bottom-bar, .menu-bar { display: none !important; }

        /* 主题定制 */
        .theme-dark { background: #1a1a2e; }
        .theme-dark iframe { filter: invert(0.85) hue-rotate(180deg); }
        .theme-light { background: #ffffff; }
    </style>
</head>
<body>
    <iframe id="game-frame" src="/web/hide/index.php" allowfullscreen></iframe>
    <script>
        // 监听 iframe 加载完成后隐藏导航栏
        document.getElementById('game-frame').addEventListener('load', function() {
            try {
                var iframeDoc = this.contentDocument || this.contentWindow.document;
                var hideSelectors = ['#nav', '.navbar', '.header', '.footer', '.sidebar', '.top-bar', '.bottom-bar', '.menu-bar'];
                hideSelectors.forEach(function(sel) {
                    var els = iframeDoc.querySelectorAll(sel);
                    els.forEach(function(el) { el.style.display = 'none'; });
                });
            } catch (e) {
                // 跨域限制，忽略
            }

            // 通知父窗口 iframe 已加载
            notifyParent('game.loaded', { user_id: <?php echo (int)$userId; ?> });
        });

        /**
         * postMessage 双向通信
         * 发送消息到父窗口
         */
        function notifyParent(event, data) {
            if (window.parent !== window) {
                window.parent.postMessage({
                    source: 'lottery-embed',
                    event: event,
                    data: data || {},
                    timestamp: Date.now()
                }, '*');
            }
        }

        /**
         * 接收父窗口消息
         * 支持的命令:
         *   { action: 'getStatus' }     - 获取当前状态
         *   { action: 'setTheme', theme: 'dark'|'light' }  - 切换主题
         *   { action: 'navigate', page: 'home'|'history' } - 页面导航
         *   { action: 'resize' }        - 请求调整大小
         */
        window.addEventListener('message', function(e) {
            // 安全检查：验证消息格式
            if (!e.data || typeof e.data !== 'object') return;
            if (e.data.target !== 'lottery-embed') return;

            var action = e.data.action;
            var response = { source: 'lottery-embed', requestId: e.data.requestId };

            switch (action) {
                case 'getStatus':
                    response.event = 'status';
                    response.data = {
                        loaded: true,
                        user_id: <?php echo (int)$userId; ?>,
                        embed_mode: true
                    };
                    break;

                case 'setTheme':
                    var theme = e.data.theme || 'light';
                    applyTheme(theme);
                    response.event = 'theme.changed';
                    response.data = { theme: theme };
                    break;

                case 'navigate':
                    var page = e.data.page || 'home';
                    var frame = document.getElementById('game-frame');
                    if (page === 'home') frame.src = '/web/hide/index.php';
                    response.event = 'navigated';
                    response.data = { page: page };
                    break;

                case 'resize':
                    response.event = 'size';
                    response.data = {
                        width: document.documentElement.scrollWidth,
                        height: document.documentElement.scrollHeight
                    };
                    break;

                default:
                    response.event = 'error';
                    response.data = { message: 'Unknown action: ' + action };
            }

            response.timestamp = Date.now();
            e.source.postMessage(response, e.origin !== 'null' ? e.origin : '*');
        });

        /**
         * 主题切换
         */
        function applyTheme(theme) {
            document.body.className = 'theme-' + theme;
            try {
                var frame = document.getElementById('game-frame');
                var doc = frame.contentDocument || frame.contentWindow.document;
                doc.body.className += ' theme-' + theme;
            } catch (e) { /* 跨域忽略 */ }
        }
    </script>
</body>
</html>
