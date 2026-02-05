<?php
/**
 * 语法和逻辑检查脚本
 *
 * 检查所有 API 文件的：
 * 1. PHP 语法错误
 * 2. 文件是否能正常加载
 * 3. 类定义是否正确
 * 4. 方法是否存在
 */

echo "╔══════════════════════════════════════════════════════════╗\n";
echo "║         API 文件语法和逻辑检查                           ║\n";
echo "╚══════════════════════════════════════════════════════════╝\n\n";

$apiDir = __DIR__ . '/../web/api/v1';
$passed = 0;
$failed = 0;
$errors = [];

/**
 * 检查文件语法
 */
function checkSyntax($file) {
    $output = [];
    $return = 0;
    exec("php -l \"$file\"", $output, $return);
    return $return === 0;
}

/**
 * 检查文件
 */
function checkFile($file, $className, $expectedMethods = []) {
    global $passed, $failed, $errors;

    $filename = basename($file);
    echo "检查 $filename...\n";

    // 1. 检查文件是否存在
    if (!file_exists($file)) {
        $failed++;
        $errors[] = "$filename - 文件不存在";
        echo "  ✗ 文件不存在\n";
        return;
    }
    echo "  ✓ 文件存在\n";
    $passed++;

    // 2. 检查语法
    if (!checkSyntax($file)) {
        $failed++;
        $errors[] = "$filename - 语法错误";
        echo "  ✗ 语法错误\n";
        return;
    }
    echo "  ✓ 语法正确\n";
    $passed++;

    // 3. 检查类定义
    $content = file_get_contents($file);
    if (strpos($content, "class $className") === false) {
        $failed++;
        $errors[] = "$filename - 缺少类定义 $className";
        echo "  ✗ 缺少类定义 $className\n";
        return;
    }
    echo "  ✓ 类定义正确\n";
    $passed++;

    // 4. 检查继承
    if (strpos($content, "extends BaseController") === false) {
        $failed++;
        $errors[] = "$filename - 未继承 BaseController";
        echo "  ✗ 未继承 BaseController\n";
    } else {
        echo "  ✓ 继承 BaseController\n";
        $passed++;
    }

    // 5. 检查必要的方法
    foreach ($expectedMethods as $method) {
        if (strpos($content, "function $method") === false) {
            $failed++;
            $errors[] = "$filename - 缺少方法 $method";
            echo "  ✗ 缺少方法 $method\n";
        } else {
            echo "  ✓ 方法 $method 存在\n";
            $passed++;
        }
    }

    echo "\n";
}

/**
 * 检查表名变量
 */
function checkTableNames() {
    global $passed, $failed, $errors;

    echo "检查表名变量定义...\n";

    $dbFile = __DIR__ . '/../web/data/db.php';
    if (!file_exists($dbFile)) {
        $failed++;
        $errors[] = "db.php - 文件不存在";
        echo "  ✗ db.php 文件不存在\n";
        return;
    }

    $content = file_get_contents($dbFile);

    $requiredTables = [
        'tb_user' => 'x_user',
        'tb_money' => 'x_money',
        'tb_money_log' => 'x_money_log',
        'tb_bet' => 'x_lib',
        'tb_lottery_result' => 'x_kj',
        'tb_game' => 'x_game'
    ];

    foreach ($requiredTables as $var => $table) {
        if (strpos($content, "\$$var") !== false) {
            echo "  ✓ $var 已定义\n";
            $passed++;
        } else {
            $failed++;
            $errors[] = "db.php - 缺少变量 \$$var";
            echo "  ✗ 缺少变量 \$$var\n";
        }
    }

    echo "\n";
}

/**
 * 检查常见问题
 */
function checkCommonIssues($file) {
    global $passed, $failed, $errors;

    $filename = basename($file);
    $content = file_get_contents($file);

    // 检查 PDO 使用（应该使用 mysqli）
    if (strpos($content, '$pdo') !== false || strpos($content, 'PDO') !== false) {
        $failed++;
        $errors[] = "$filename - 使用了 PDO（应使用 mysqli）";
        echo "  ✗ 使用了 PDO\n";
    } else {
        $passed++;
        echo "  ✓ 未使用 PDO\n";
    }

    // 检查 SQL 注入风险（未使用 prepare）
    if (preg_match('/mysqli.*query\s*\(.*\$/', $content)) {
        $failed++;
        $errors[] = "$filename - 可能存在 SQL 注入风险";
        echo "  ✗ 可能存在 SQL 注入\n";
    } else {
        $passed++;
        echo "  ✓ 无明显 SQL 注入\n";
    }

    // 检查子查询（应优化为 JOIN）
    $subqueryCount = preg_match_all('/IN\s*\(\s*SELECT/', $content);
    if ($subqueryCount > 0) {
        echo "  ⚠ 发现 $subqueryCount 个子查询（建议优化为 JOIN）\n";
    } else {
        $passed++;
        echo "  ✓ 无子查询\n";
    }
}

// 开始检查
echo str_repeat('=', 60) . "\n";
echo "  1. 检查 BaseController\n";
echo str_repeat('=', 60) . "\n\n";

checkFile(
    "$apiDir/BaseController.php",
    'BaseController',
    ['__construct', 'getMethod', 'requireMethod', 'respondSuccess', 'respondError']
);

echo str_repeat('=', 60) . "\n";
echo "  2. 检查各接口文件\n";
echo str_repeat('=', 60) . "\n\n";

$controllers = [
    'users.php' => ['UsersController', ['handle', 'getUserById', 'getUserBalance', 'createUser']],
    'bets.php' => ['BetsController', ['handle', 'getBetsList', 'getBetById', 'createBet']],
    'games.php' => ['GamesController', ['handle', 'getGamesList', 'getGameById']],
    'periods.php' => ['PeriodsController', ['handle', 'getCurrentPeriod', 'getPeriodsList']],
    'deposits.php' => ['DepositsController', ['handle', 'createDeposit', 'getDepositsList']],
    'withdrawals.php' => ['WithdrawalsController', ['handle', 'createWithdrawal', 'getWithdrawalsList']],
    'transactions.php' => ['TransactionsController', ['handle', 'getTransactionsList']],
    'lottery_results.php' => ['LotteryResultsController', ['handle', 'getResults', 'getLatestResult']],
    'webhooks.php' => ['WebhooksController', ['handle', 'createWebhook', 'getWebhooksList']]
];

foreach ($controllers as $file => list($className, $methods)) {
    checkFile("$apiDir/$file", $className, $methods);
    checkCommonIssues("$apiDir/$file");
    echo "\n";
}

echo str_repeat('=', 60) . "\n";
echo "  3. 检查数据库配置\n";
echo str_repeat('=', 60) . "\n\n";

checkTableNames();

// 输出结果
echo str_repeat('=', 60) . "\n";
echo "  检查结果汇总\n";
echo str_repeat('=', 60) . "\n\n";
echo "总检查项: " . ($passed + $failed) . "\n";
echo "通过: $passed ✓\n";
echo "失败: $failed ✗\n";
echo "成功率: " . round($passed / max(1, $passed + $failed) * 100, 2) . "%\n\n";

if ($failed > 0) {
    echo "发现的问题:\n";
    foreach ($errors as $error) {
        echo "  - $error\n";
    }
    echo "\n";
    exit(1);
} else {
    echo "✓ 所有检查通过！\n\n";
    exit(0);
}
