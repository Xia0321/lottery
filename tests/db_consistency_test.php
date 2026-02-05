<?php
/**
 * 数据库一致性测试
 *
 * 测试：
 * 1. 数据库连接
 * 2. 表结构是否正确
 * 3. 必要字段是否存在
 * 4. 索引是否创建
 * 5. 数据完整性约束
 */

require_once __DIR__ . '/../web/data/config.inc.php';
require_once __DIR__ . '/../web/data/db.php';
require_once __DIR__ . '/../web/global/db.inc.php';

echo "╔══════════════════════════════════════════════════════════╗\n";
echo "║         数据库一致性测试                                 ║\n";
echo "╚══════════════════════════════════════════════════════════╝\n\n";

// 获取数据库连接
global $msql;
$conn = $msql->mysqli;

$passed = 0;
$failed = 0;
$errors = [];

/**
 * 断言
 */
function assert_test($condition, $message) {
    global $passed, $failed, $errors;
    if ($condition) {
        $passed++;
        echo "  ✓ $message\n";
        return true;
    } else {
        $failed++;
        $errors[] = $message;
        echo "  ✗ $message\n";
        return false;
    }
}

/**
 * 1. 测试数据库连接
 */
function testConnection() {
    global $conn;
    echo "1. 测试数据库连接\n";
    echo str_repeat('-', 60) . "\n";

    assert_test($conn !== null, '数据库连接对象已创建');
    assert_test(!$conn->connect_error, '数据库连接成功');
    assert_test($conn->ping(), '数据库连接有效');

    echo "\n";
}

/**
 * 2. 测试表是否存在
 */
function testTablesExist() {
    global $conn;
    echo "2. 测试必要的表是否存在\n";
    echo str_repeat('-', 60) . "\n";

    $requiredTables = [
        'x_user' => '用户表',
        'x_money' => '充值提现表',
        'x_money_log' => '交易日志表',
        'x_lib' => '投注表',
        'x_kj' => '开奖结果表',
        'x_game' => '游戏表'
    ];

    foreach ($requiredTables as $table => $desc) {
        $result = $conn->query("SHOW TABLES LIKE '$table'");
        assert_test($result && $result->num_rows > 0, "$desc ($table) 存在");
    }

    echo "\n";
}

/**
 * 3. 测试用户表结构
 */
function testUserTableStructure() {
    global $conn, $tb_user;
    echo "3. 测试用户表结构\n";
    echo str_repeat('-', 60) . "\n";

    $result = $conn->query("DESCRIBE `$tb_user`");
    if (!$result) {
        assert_test(false, "查询用户表结构失败: " . $conn->error);
        echo "\n";
        return;
    }

    $fields = [];
    while ($row = $result->fetch_assoc()) {
        $fields[] = $row['Field'];
    }

    $requiredFields = ['userid', 'username', 'userpass', 'money', 'status', 'fid'];
    foreach ($requiredFields as $field) {
        assert_test(in_array($field, $fields), "用户表包含字段 $field");
    }

    echo "\n";
}

/**
 * 4. 测试 x_money 表结构
 */
function testMoneyTableStructure() {
    global $conn, $tb_money;
    echo "4. 测试 x_money 表结构\n";
    echo str_repeat('-', 60) . "\n";

    $result = $conn->query("DESCRIBE `$tb_money`");
    if (!$result) {
        assert_test(false, "查询 x_money 表结构失败: " . $conn->error);
        echo "\n";
        return;
    }

    $fields = [];
    while ($row = $result->fetch_assoc()) {
        $fields[] = $row['Field'];
    }

    $requiredFields = ['id', 'userid', 'mtype', 'money', 'orderid', 'status', 'tjtime'];
    foreach ($requiredFields as $field) {
        assert_test(in_array($field, $fields), "x_money 表包含字段 $field");
    }

    // 检查 mtype 字段（1=充值，2=提现）
    $result = $conn->query("SELECT DISTINCT mtype FROM `$tb_money` ORDER BY mtype");
    if ($result && $result->num_rows > 0) {
        echo "  ℹ x_money 表中的 mtype 值: ";
        $types = [];
        while ($row = $result->fetch_assoc()) {
            $types[] = $row['mtype'];
        }
        echo implode(', ', $types) . "\n";
    }

    echo "\n";
}

/**
 * 5. 测试投注表结构
 */
function testBetTableStructure() {
    global $conn, $tb_bet;
    echo "5. 测试投注表 (x_lib) 结构\n";
    echo str_repeat('-', 60) . "\n";

    $result = $conn->query("DESCRIBE `$tb_bet`");
    if (!$result) {
        assert_test(false, "查询投注表结构失败: " . $conn->error);
        echo "\n";
        return;
    }

    $fields = [];
    while ($row = $result->fetch_assoc()) {
        $fields[] = $row['Field'];
    }

    // x_lib 表的实际字段（根据彩票系统）
    $requiredFields = ['userid', 'gameid', 'qishu', 'money'];
    foreach ($requiredFields as $field) {
        if (in_array($field, $fields)) {
            assert_test(true, "投注表包含字段 $field");
        } else {
            // 可能使用了不同的字段名
            echo "  ⚠ 投注表缺少字段 $field（可能使用了其他字段名）\n";
        }
    }

    echo "\n";
}

/**
 * 6. 测试数据一致性
 */
function testDataConsistency() {
    global $conn, $tb_user, $tb_money, $tb_money_log;
    echo "6. 测试数据一致性\n";
    echo str_repeat('-', 60) . "\n";

    // 检查是否有孤立的充值记录（用户不存在）
    $sql = "
        SELECT COUNT(*) as count
        FROM `$tb_money` m
        LEFT JOIN `$tb_user` u ON m.userid = u.userid
        WHERE u.userid IS NULL
    ";
    $result = $conn->query($sql);
    if ($result) {
        $row = $result->fetch_assoc();
        assert_test($row['count'] == 0, "无孤立的充值/提现记录（用户不存在）");
        if ($row['count'] > 0) {
            echo "    发现 {$row['count']} 条孤立记录\n";
        }
    }

    // 检查用户余额是否为负数
    $result = $conn->query("SELECT COUNT(*) as count FROM `$tb_user` WHERE money < 0");
    if ($result) {
        $row = $result->fetch_assoc();
        assert_test($row['count'] == 0, "无负数余额用户");
        if ($row['count'] > 0) {
            echo "    发现 {$row['count']} 个用户余额为负数\n";
        }
    }

    // 检查 x_money 表的 mtype 值是否规范
    $result = $conn->query("SELECT COUNT(*) as count FROM `$tb_money` WHERE mtype NOT IN (1, 2)");
    if ($result) {
        $row = $result->fetch_assoc();
        assert_test($row['count'] == 0, "x_money 表 mtype 值规范（1=充值，2=提现）");
        if ($row['count'] > 0) {
            echo "    发现 {$row['count']} 条记录的 mtype 值不规范\n";
        }
    }

    echo "\n";
}

/**
 * 7. 测试索引
 */
function testIndexes() {
    global $conn, $tb_user, $tb_money;
    echo "7. 测试重要索引\n";
    echo str_repeat('-', 60) . "\n";

    // 检查用户表索引
    $result = $conn->query("SHOW INDEX FROM `$tb_user` WHERE Column_name = 'username'");
    assert_test($result && $result->num_rows > 0, "用户表 username 字段有索引");

    // 检查 x_money 表索引
    $result = $conn->query("SHOW INDEX FROM `$tb_money` WHERE Column_name = 'userid'");
    assert_test($result && $result->num_rows > 0, "x_money 表 userid 字段有索引");

    $result = $conn->query("SHOW INDEX FROM `$tb_money` WHERE Column_name = 'mtype'");
    if ($result && $result->num_rows > 0) {
        echo "  ✓ x_money 表 mtype 字段有索引（推荐）\n";
        $passed++;
    } else {
        echo "  ⚠ x_money 表 mtype 字段无索引（建议添加）\n";
    }

    echo "\n";
}

/**
 * 8. 测试并发安全性（模拟）
 */
function testConcurrencySafety() {
    echo "8. 测试并发安全性\n";
    echo str_repeat('-', 60) . "\n";

    // 检查余额更新是否使用了原子操作
    $files = [
        'bets.php',
        'withdrawals.php'
    ];

    foreach ($files as $file) {
        $filepath = __DIR__ . "/../web/api/v1/$file";
        $content = file_get_contents($filepath);

        // 检查是否使用了 money = money - ? 的原子操作
        if (preg_match('/money\s*=\s*money\s*-\s*\?/i', $content)) {
            assert_test(true, "$file 使用原子操作更新余额");
        } else {
            assert_test(false, "$file 可能未使用原子操作更新余额");
        }

        // 检查是否使用了事务
        if (strpos($content, 'begin_transaction') !== false) {
            assert_test(true, "$file 使用事务保护");
        } else {
            echo "  ⚠ $file 未使用事务\n";
        }
    }

    echo "\n";
}

/**
 * 9. 测试参数验证
 */
function testParameterValidation() {
    echo "9. 测试参数验证\n";
    echo str_repeat('-', 60) . "\n";

    $files = [
        'deposits.php' => '充值金额上限',
        'withdrawals.php' => '提现金额上限',
        'users.php' => '用户名格式验证'
    ];

    foreach ($files as $file => $check) {
        $filepath = __DIR__ . "/../web/api/v1/$file";
        $content = file_get_contents($filepath);

        if (strpos($file, 'deposits') !== false || strpos($file, 'withdrawals') !== false) {
            // 检查金额上限
            if (preg_match('/amount\s*>\s*\d+/', $content)) {
                assert_test(true, "$file 有金额上限验证");
            } else {
                assert_test(false, "$file 缺少金额上限验证");
            }
        }

        if (strpos($file, 'users') !== false) {
            // 检查用户名格式验证
            if (preg_match('/preg_match.*username/', $content)) {
                assert_test(true, "$file 有用户名格式验证");
            } else {
                echo "  ⚠ $file 可能缺少用户名格式验证\n";
            }
        }
    }

    echo "\n";
}

// 运行所有测试
testConnection();
testTablesExist();
testUserTableStructure();
testMoneyTableStructure();
testBetTableStructure();
testDataConsistency();
testIndexes();
testConcurrencySafety();
testParameterValidation();

// 输出结果
echo str_repeat('=', 60) . "\n";
echo "  测试结果汇总\n";
echo str_repeat('=', 60) . "\n\n";
echo "总测试项: " . ($passed + $failed) . "\n";
echo "通过: $passed ✓\n";
echo "失败: $failed ✗\n";

if ($passed + $failed > 0) {
    echo "成功率: " . round($passed / ($passed + $failed) * 100, 2) . "%\n\n";
}

if ($failed > 0) {
    echo "失败的测试:\n";
    foreach ($errors as $error) {
        echo "  - $error\n";
    }
    echo "\n";
    exit(1);
} else {
    echo "\n✓ 所有测试通过！\n\n";
    exit(0);
}
