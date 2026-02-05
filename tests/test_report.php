<?php
/**
 * 自动化测试报告生成器
 *
 * 运行所有测试脚本并生成汇总报告
 *
 * 用法: php tests/test_report.php [base_url] [api_key] [api_secret]
 */

if (php_sapi_name() !== 'cli') {
    http_response_code(403);
    exit('CLI only');
}

$baseUrl   = isset($argv[1]) ? $argv[1] : 'http://localhost';
$apiKey    = isset($argv[2]) ? $argv[2] : '';
$apiSecret = isset($argv[3]) ? $argv[3] : '';

echo "╔══════════════════════════════════════════╗\n";
echo "║         彩票系统 API 测试报告              ║\n";
echo "╚══════════════════════════════════════════╝\n\n";
echo "时间: " . date('Y-m-d H:i:s') . "\n";
echo "目标: $baseUrl\n";
echo "PHP:  " . PHP_VERSION . "\n\n";

$testDir = __DIR__;
$results = [];

// ============================================
// 1. 语法检查
// ============================================
echo "═══ 1. PHP 语法检查 ═══\n\n";

$phpFiles = [];
$scanDirs = [
    __DIR__ . '/../web/api/v1/',
    __DIR__ . '/../web/class/api/',
    __DIR__ . '/../web/embed/',
    __DIR__ . '/../sdk/',
    __DIR__ . '/../scripts/',
];

foreach ($scanDirs as $dir) {
    if (!is_dir($dir)) continue;
    $files = glob($dir . '*.php');
    $phpFiles = array_merge($phpFiles, $files);
}

$syntaxPass = 0;
$syntaxFail = 0;

foreach ($phpFiles as $file) {
    $output = [];
    $returnCode = 0;
    exec("php -l " . escapeshellarg($file) . " 2>&1", $output, $returnCode);
    $baseName = basename($file);

    if ($returnCode === 0) {
        $syntaxPass++;
    } else {
        $syntaxFail++;
        echo "  FAIL: $baseName\n";
        echo "    " . implode("\n    ", $output) . "\n";
    }
}

echo "  语法检查: $syntaxPass 通过, $syntaxFail 失败\n\n";
$results['syntax'] = ['pass' => $syntaxPass, 'fail' => $syntaxFail];

// ============================================
// 2. 类加载检查
// ============================================
echo "═══ 2. 类加载检查 ═══\n\n";

$classFiles = [
    'ApiAuth'          => __DIR__ . '/../web/class/api/ApiAuth.php',
    'ApiResponse'      => __DIR__ . '/../web/class/api/ApiResponse.php',
    'ErrorCode'        => __DIR__ . '/../web/class/api/ErrorCode.php',
    'RateLimiter'      => __DIR__ . '/../web/class/api/RateLimiter.php',
    'WebhookManager'   => __DIR__ . '/../web/class/api/WebhookManager.php',
    'EmbedTokenManager'=> __DIR__ . '/../web/class/api/EmbedTokenManager.php',
    'LotteryApiClient' => __DIR__ . '/../sdk/LotteryApiClient.php',
];

$classPass = 0;
$classFail = 0;

foreach ($classFiles as $className => $file) {
    if (file_exists($file)) {
        // 检查文件是否包含类定义
        $content = file_get_contents($file);
        if (strpos($content, "class $className") !== false) {
            echo "  OK: $className ($file)\n";
            $classPass++;
        } else {
            echo "  FAIL: $className 类定义未找到 ($file)\n";
            $classFail++;
        }
    } else {
        echo "  FAIL: 文件不存在 ($file)\n";
        $classFail++;
    }
}

echo "\n  类加载: $classPass 通过, $classFail 失败\n\n";
$results['classes'] = ['pass' => $classPass, 'fail' => $classFail];

// ============================================
// 3. API 端点文件检查
// ============================================
echo "═══ 3. API 端点文件检查 ═══\n\n";

$endpoints = [
    'bets.php'           => 'GET /api/v1/bets',
    'transactions.php'   => 'GET /api/v1/transactions',
    'lottery_results.php'=> 'GET /api/v1/lottery_results',
    'deposits.php'       => 'GET /api/v1/deposits',
    'withdrawals.php'    => 'GET /api/v1/withdrawals',
    'webhooks.php'       => 'GET/POST/DELETE /api/v1/webhooks',
    'batch.php'          => 'POST /api/v1/batch/*',
    'stats.php'          => 'GET /api/v1/stats/*',
    'metrics.php'        => 'GET /api/v1/metrics',
    'health.php'         => 'GET /api/v1/health',
];

$endpointPass = 0;
$endpointFail = 0;

foreach ($endpoints as $file => $desc) {
    $path = __DIR__ . '/../web/api/v1/' . $file;
    if (file_exists($path)) {
        echo "  OK: $desc ($file)\n";
        $endpointPass++;
    } else {
        echo "  MISS: $desc ($file)\n";
        $endpointFail++;
    }
}

echo "\n  端点文件: $endpointPass 存在, $endpointFail 缺失\n\n";
$results['endpoints'] = ['pass' => $endpointPass, 'fail' => $endpointFail];

// ============================================
// 4. 数据库表检查
// ============================================
echo "═══ 4. 数据库表检查 ═══\n\n";

$dbCheckPass = 0;
$dbCheckFail = 0;

try {
    require_once(__DIR__ . '/../web/data/config.inc.php');
    require_once(__DIR__ . '/../web/data/db.php');
    require_once(__DIR__ . '/../web/global/db.inc.php');

    global $msql;
    $mysqli = $msql->get_mysqli();

    $requiredTables = [
        'api_keys', 'api_logs', 'webhooks', 'webhook_logs',
        'webhook_queue', 'embed_token_nonces'
    ];

    foreach ($requiredTables as $table) {
        $result = $mysqli->query("SHOW TABLES LIKE '$table'");
        if ($result && $result->num_rows > 0) {
            echo "  OK: $table\n";
            $dbCheckPass++;
        } else {
            echo "  MISS: $table (运行 php init_database.php 创建)\n";
            $dbCheckFail++;
        }
    }
} catch (Exception $e) {
    echo "  ERROR: 数据库连接失败 - " . $e->getMessage() . "\n";
    $dbCheckFail = count($requiredTables ?? []);
}

echo "\n  数据库表: $dbCheckPass 存在, $dbCheckFail 缺失\n\n";
$results['database'] = ['pass' => $dbCheckPass, 'fail' => $dbCheckFail];

// ============================================
// 5. 安全特性检查
// ============================================
echo "═══ 5. 安全特性检查 ═══\n\n";

$secPass = 0;
$secFail = 0;

// 检查预处理语句使用
$apiDir = __DIR__ . '/../web/api/v1/';
$unsafeFiles = [];
foreach (glob($apiDir . '*.php') as $file) {
    $content = file_get_contents($file);
    // 排除注释
    if (preg_match('/\$mysqli->query\s*\(\s*["\'].*\$/', $content)) {
        $unsafeFiles[] = basename($file);
    }
}

if (empty($unsafeFiles)) {
    echo "  OK: API 文件全部使用预处理语句\n";
    $secPass++;
} else {
    echo "  WARN: 以下文件可能包含直接拼接 SQL: " . implode(', ', $unsafeFiles) . "\n";
    $secFail++;
}

// 检查 HMAC 签名
$authFile = __DIR__ . '/../web/class/api/ApiAuth.php';
if (file_exists($authFile)) {
    $content = file_get_contents($authFile);
    if (strpos($content, 'hash_hmac') !== false && strpos($content, 'sha256') !== false) {
        echo "  OK: HMAC-SHA256 签名验证已实现\n";
        $secPass++;
    } else {
        echo "  FAIL: HMAC 签名验证未检测到\n";
        $secFail++;
    }
    if (strpos($content, 'ipInCidr') !== false) {
        echo "  OK: IP 白名单 CIDR 支持已实现\n";
        $secPass++;
    } else {
        echo "  WARN: IP 白名单 CIDR 支持未检测到\n";
        $secFail++;
    }
    if (strpos($content, 'requireScope') !== false) {
        echo "  OK: Scope 权限控制已实现\n";
        $secPass++;
    } else {
        echo "  WARN: Scope 权限控制未检测到\n";
        $secFail++;
    }
}

// 检查速率限制
if (file_exists(__DIR__ . '/../web/class/api/RateLimiter.php')) {
    echo "  OK: 速率限制器已实现\n";
    $secPass++;
} else {
    echo "  FAIL: 速率限制器缺失\n";
    $secFail++;
}

echo "\n  安全特性: $secPass 通过, $secFail 失败\n\n";
$results['security'] = ['pass' => $secPass, 'fail' => $secFail];

// ============================================
// 6. SDK 检查
// ============================================
echo "═══ 6. PHP SDK 检查 ═══\n\n";

$sdkPass = 0;
$sdkFail = 0;

$sdkFile = __DIR__ . '/../sdk/LotteryApiClient.php';
if (file_exists($sdkFile)) {
    $content = file_get_contents($sdkFile);
    $methods = [
        'getLatestResult', 'getResults', 'getBets', 'createBet',
        'getTransactions', 'getDeposits', 'createDeposit',
        'getWithdrawals', 'createWithdrawal', 'getUser', 'getUserBalance',
        'createWebhook', 'getWebhooks', 'deleteWebhook',
        'generateEmbedToken', 'batchUsers', 'batchBalances', 'batchBets',
        'getStatsOverview', 'getBetStats', 'getRevenueStats', 'getUserStats',
        'verifyWebhookSignature'
    ];

    foreach ($methods as $method) {
        if (strpos($content, "function $method") !== false) {
            $sdkPass++;
        } else {
            echo "  MISS: SDK 方法 $method\n";
            $sdkFail++;
        }
    }
    echo "  SDK 方法: $sdkPass 存在, $sdkFail 缺失\n";
} else {
    echo "  FAIL: SDK 文件不存在\n";
    $sdkFail = 1;
}

echo "\n";
$results['sdk'] = ['pass' => $sdkPass, 'fail' => $sdkFail];

// ============================================
// 7. 部署工具检查
// ============================================
echo "═══ 7. 部署工具检查 ═══\n\n";

$deployPass = 0;
$deployFail = 0;

$deployFiles = [
    'scripts/setup_redis.sh'          => 'Redis 安装脚本',
    'scripts/setup_https.sh'          => 'HTTPS 配置脚本',
    'scripts/deploy.sh'               => '部署脚本',
    'scripts/webhook_worker.php'      => 'Webhook Worker',
    'scripts/create_partner_key.php'  => 'API Key 创建脚本',
    'scripts/production_checklist.php' => '生产检查清单',
    'scripts/grafana_dashboard.json'  => 'Grafana 面板',
];

foreach ($deployFiles as $file => $desc) {
    $path = __DIR__ . '/../' . $file;
    if (file_exists($path)) {
        echo "  OK: $desc ($file)\n";
        $deployPass++;
    } else {
        echo "  MISS: $desc ($file)\n";
        $deployFail++;
    }
}

echo "\n  部署工具: $deployPass 存在, $deployFail 缺失\n\n";
$results['deploy'] = ['pass' => $deployPass, 'fail' => $deployFail];

// ============================================
// 汇总
// ============================================
echo "╔══════════════════════════════════════════╗\n";
echo "║              测试结果汇总                 ║\n";
echo "╚══════════════════════════════════════════╝\n\n";

$totalPass = 0;
$totalFail = 0;

$categories = [
    'syntax'    => 'PHP 语法检查',
    'classes'   => '类加载检查',
    'endpoints' => 'API 端点',
    'database'  => '数据库表',
    'security'  => '安全特性',
    'sdk'       => 'PHP SDK',
    'deploy'    => '部署工具',
];

foreach ($categories as $key => $label) {
    $p = $results[$key]['pass'];
    $f = $results[$key]['fail'];
    $totalPass += $p;
    $totalFail += $f;
    $status = $f === 0 ? 'PASS' : 'FAIL';
    printf("  %-16s %s  (通过: %d, 失败: %d)\n", $label, $status, $p, $f);
}

echo "\n";
echo "  总计: 通过 $totalPass, 失败 $totalFail\n";
echo "  测试时间: " . date('Y-m-d H:i:s') . "\n\n";

if ($totalFail > 0) {
    echo "  结论: 有 $totalFail 项检查未通过，请修复后重新测试\n";
} else {
    echo "  结论: 所有检查通过\n";
}

echo "\n";

exit($totalFail > 0 ? 1 : 0);
