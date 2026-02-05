<?php
/**
 * 生产环境检查清单脚本
 *
 * 在上线前运行此脚本，检查所有配置是否正确
 *
 * 用法: php scripts/production_checklist.php
 */

if (php_sapi_name() !== 'cli') {
    http_response_code(403);
    exit('CLI only');
}

echo "╔══════════════════════════════════════════╗\n";
echo "║      生产环境检查清单                     ║\n";
echo "╚══════════════════════════════════════════╝\n\n";

$pass = 0;
$warn = 0;
$fail = 0;

function check_pass($msg) { global $pass; $pass++; echo "  [PASS] $msg\n"; }
function check_warn($msg) { global $warn; $warn++; echo "  [WARN] $msg\n"; }
function check_fail($msg) { global $fail; $fail++; echo "  [FAIL] $msg\n"; }

// ============================================
// 1. PHP 环境
// ============================================
echo "--- PHP 环境 ---\n";

$phpVersion = PHP_VERSION;
if (version_compare($phpVersion, '7.0', '>=')) {
    check_pass("PHP 版本: $phpVersion");
} else {
    check_warn("PHP 版本较旧: $phpVersion (建议 7.0+)");
}

$requiredExts = ['mysqli', 'json', 'curl', 'openssl', 'mbstring'];
foreach ($requiredExts as $ext) {
    if (extension_loaded($ext)) {
        check_pass("PHP 扩展: $ext");
    } else {
        check_fail("PHP 扩展缺失: $ext");
    }
}

if (extension_loaded('redis')) {
    check_pass("PHP 扩展: redis");
} else {
    check_warn("PHP Redis 扩展未安装，速率限制将降级");
}

echo "\n";

// ============================================
// 2. 配置文件
// ============================================
echo "--- 配置文件 ---\n";

$configFile = __DIR__ . '/../web/data/config.inc.php';
if (file_exists($configFile)) {
    check_pass("config.inc.php 存在");
    $configContent = file_get_contents($configFile);
    if (strpos($configContent, 'DEBUG_MODE') !== false) {
        if (preg_match("/DEBUG_MODE.*true/i", $configContent)) {
            check_warn("DEBUG_MODE 为 true，生产环境应设为 false");
        } else {
            check_pass("DEBUG_MODE 已关闭");
        }
    }
} else {
    check_fail("config.inc.php 不存在");
}

echo "\n";

// ============================================
// 3. 数据库连接
// ============================================
echo "--- 数据库 ---\n";

try {
    require_once(__DIR__ . '/../web/data/config.inc.php');
    require_once(__DIR__ . '/../web/data/db.php');
    require_once(__DIR__ . '/../web/global/db.inc.php');

    global $msql;
    $mysqli = $msql->get_mysqli();

    if ($mysqli && !$mysqli->connect_error) {
        check_pass("MySQL 连接正常");

        // 检查必要的表
        $tables = ['api_keys', 'api_logs', 'webhooks', 'webhook_logs', 'webhook_queue', 'embed_token_nonces'];
        foreach ($tables as $table) {
            $result = $mysqli->query("SHOW TABLES LIKE '$table'");
            if ($result && $result->num_rows > 0) {
                check_pass("数据表: $table");
            } else {
                check_fail("数据表缺失: $table (运行 php init_database.php)");
            }
        }

        // 检查是否有 API Key
        $result = $mysqli->query("SELECT COUNT(*) as cnt FROM api_keys WHERE status = 1");
        $row = $result->fetch_assoc();
        if ($row['cnt'] > 0) {
            check_pass("已有 {$row['cnt']} 个有效 API Key");
        } else {
            check_warn("没有有效的 API Key，运行 php scripts/create_partner_key.php 创建");
        }
    } else {
        check_fail("MySQL 连接失败");
    }
} catch (Exception $e) {
    check_fail("数据库检查异常: " . $e->getMessage());
}

echo "\n";

// ============================================
// 4. Redis
// ============================================
echo "--- Redis ---\n";

if (extension_loaded('redis')) {
    try {
        $redis = new Redis();
        $redis->connect('127.0.0.1', 6379, 2);
        $redis->ping();
        check_pass("Redis 连接正常");
        $redis->close();
    } catch (Exception $e) {
        check_warn("Redis 连接失败: " . $e->getMessage());
    }
} else {
    check_warn("Redis 扩展未安装");
}

echo "\n";

// ============================================
// 5. 文件权限
// ============================================
echo "--- 文件权限 ---\n";

$writableDirs = [
    __DIR__ . '/../web/logs',
    __DIR__ . '/../web/templates/default/cache',
];

foreach ($writableDirs as $dir) {
    if (is_dir($dir) && is_writable($dir)) {
        check_pass("可写目录: " . basename($dir));
    } elseif (!is_dir($dir)) {
        check_warn("目录不存在: $dir");
    } else {
        check_fail("目录不可写: $dir");
    }
}

// 检查敏感文件不可通过 Web 访问
$sensitiveFiles = [
    __DIR__ . '/../api_credentials.txt',
    __DIR__ . '/../init_database.php',
];
foreach ($sensitiveFiles as $f) {
    if (file_exists($f)) {
        check_warn("敏感文件存在于项目根目录: " . basename($f) . " (部署后应移除或限制访问)");
    }
}

echo "\n";

// ============================================
// 6. 安全设置
// ============================================
echo "--- 安全设置 ---\n";

if (ini_get('display_errors')) {
    check_warn("display_errors 为 On，生产环境应关闭");
} else {
    check_pass("display_errors 已关闭");
}

if (ini_get('expose_php')) {
    check_warn("expose_php 为 On，建议关闭");
} else {
    check_pass("expose_php 已关闭");
}

$htaccess = __DIR__ . '/../web/api/.htaccess';
if (file_exists($htaccess)) {
    check_pass(".htaccess API 路由配置存在");
} else {
    check_warn(".htaccess 不存在，需要在 Web 服务器中配置路由");
}

echo "\n";

// ============================================
// 7. Cron 任务
// ============================================
echo "--- 定时任务 ---\n";

$cronOutput = shell_exec('crontab -l 2>/dev/null');
if ($cronOutput && strpos($cronOutput, 'webhook_worker') !== false) {
    check_pass("Webhook Worker cron 已配置");
} else {
    check_warn("Webhook Worker cron 未配置 (添加: * * * * * php scripts/webhook_worker.php)");
}

echo "\n";

// ============================================
// 汇总
// ============================================
echo "============================================\n";
echo " 检查结果汇总\n";
echo "============================================\n";
echo "  通过: $pass\n";
echo "  警告: $warn\n";
echo "  失败: $fail\n";
echo "============================================\n";

if ($fail > 0) {
    echo "\n有 $fail 项检查失败，请修复后再上线\n";
    exit(1);
} elseif ($warn > 0) {
    echo "\n有 $warn 项警告，建议处理后再上线\n";
    exit(0);
} else {
    echo "\n所有检查通过，可以上线！\n";
    exit(0);
}
