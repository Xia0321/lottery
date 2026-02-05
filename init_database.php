<?php
/**
 * 数据库初始化脚本
 *
 * 用途：
 * 1. 创建数据库
 * 2. 导入初始数据
 * 3. 创建 API Key
 * 4. 验证表结构
 */

echo "╔══════════════════════════════════════════════════════════╗\n";
echo "║         彩票系统数据库初始化脚本                         ║\n";
echo "╚══════════════════════════════════════════════════════════╝\n\n";

// 数据库配置
$dbConfig = [
    'host' => '127.0.0.1',
    'port' => 3306,
    'user' => 'root',
    'pass' => '123456',
    'name' => 'lottery_db'  // 数据库名
];

// SQL 文件路径
$sqlFile = __DIR__ . '/cs1381328.sql';

echo "配置信息：\n";
echo "  数据库主机: {$dbConfig['host']}:{$dbConfig['port']}\n";
echo "  数据库用户: {$dbConfig['user']}\n";
echo "  数据库名称: {$dbConfig['name']}\n";
echo "  SQL 文件: $sqlFile\n\n";

// 第一步：连接数据库（不指定数据库）
echo "[1/5] 连接数据库服务器...\n";
$conn = new mysqli($dbConfig['host'], $dbConfig['user'], $dbConfig['pass']);

if ($conn->connect_error) {
    die("✗ 连接失败: " . $conn->connect_error . "\n");
}
echo "✓ 连接成功\n\n";

// 第二步：创建数据库（如果不存在）
echo "[2/5] 创建数据库 {$dbConfig['name']}...\n";
$sql = "CREATE DATABASE IF NOT EXISTS `{$dbConfig['name']}` CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci";
if ($conn->query($sql)) {
    echo "✓ 数据库已创建或已存在\n\n";
} else {
    die("✗ 创建数据库失败: " . $conn->error . "\n");
}

// 选择数据库
$conn->select_db($dbConfig['name']);

// 第三步：导入 SQL 文件
echo "[3/5] 导入初始数据...\n";

if (!file_exists($sqlFile)) {
    die("✗ SQL 文件不存在: $sqlFile\n");
}

echo "  读取 SQL 文件...\n";
$sqlContent = file_get_contents($sqlFile);
$fileSize = round(filesize($sqlFile) / 1024 / 1024, 2);
echo "  文件大小: {$fileSize} MB\n";

// 分割 SQL 语句（以分号结尾）
echo "  执行 SQL 语句...\n";
$statements = explode(';', $sqlContent);
$total = count($statements);
$executed = 0;
$errors = 0;

// 执行每条 SQL
foreach ($statements as $statement) {
    $statement = trim($statement);
    if (empty($statement)) {
        continue;
    }

    // 执行语句
    if ($conn->multi_query($statement . ';')) {
        do {
            if ($result = $conn->store_result()) {
                $result->free();
            }
        } while ($conn->more_results() && $conn->next_result());

        $executed++;

        // 每 100 条显示进度
        if ($executed % 100 == 0) {
            $progress = round(($executed / $total) * 100, 1);
            echo "\r  进度: $executed/$total ($progress%)";
        }
    } else {
        // 忽略某些常见的非致命错误
        $error = $conn->error;
        if (strpos($error, 'already exists') === false &&
            strpos($error, 'Duplicate entry') === false) {
            $errors++;
        }
    }
}

echo "\r  完成: $executed 条语句已执行";
if ($errors > 0) {
    echo " ($errors 个错误)";
}
echo "\n";
echo "✓ 数据导入完成\n\n";

// 第四步：创建 API 相关表（如果不存在）
echo "[4/6] 创建 API 相关表...\n";

// api_keys 表
$conn->query("
    CREATE TABLE IF NOT EXISTS `api_keys` (
      `id` int(11) NOT NULL AUTO_INCREMENT,
      `api_key` varchar(64) NOT NULL COMMENT 'API Key',
      `api_secret` varchar(255) NOT NULL COMMENT 'API Secret',
      `partner_name` varchar(100) NOT NULL DEFAULT '' COMMENT '合作伙伴名称',
      `agent_id` int(11) DEFAULT NULL COMMENT '关联代理 ID（数据隔离）',
      `status` tinyint(1) DEFAULT 1 COMMENT '状态：1=启用 0=禁用',
      `rate_limit` int(11) DEFAULT 100 COMMENT '每分钟请求限制',
      `allowed_ips` text DEFAULT NULL COMMENT 'IP 白名单（JSON 数组，支持 CIDR）',
      `scopes` text DEFAULT NULL COMMENT '权限范围（JSON 数组，如 [\"bets.read\",\"users.read\"]，空为全部权限）',
      `expires_at` datetime DEFAULT NULL COMMENT '过期时间',
      `last_used_at` datetime DEFAULT NULL COMMENT '最后使用时间',
      `created_at` datetime DEFAULT CURRENT_TIMESTAMP,
      `updated_at` datetime DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
      PRIMARY KEY (`id`),
      UNIQUE KEY `uk_api_key` (`api_key`),
      KEY `idx_agent_id` (`agent_id`)
    ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COMMENT='API 密钥表'
");
echo "  ✓ api_keys 表\n";

// api_logs 表
$conn->query("
    CREATE TABLE IF NOT EXISTS `api_logs` (
      `id` bigint(20) NOT NULL AUTO_INCREMENT,
      `api_key_id` int(11) DEFAULT NULL,
      `api_key` varchar(64) DEFAULT NULL,
      `endpoint` varchar(255) NOT NULL COMMENT '请求路径',
      `method` varchar(10) NOT NULL COMMENT 'HTTP 方法',
      `request_params` text DEFAULT NULL COMMENT '请求参数',
      `response_code` int(11) NOT NULL COMMENT '响应状态码',
      `response_body` text DEFAULT NULL COMMENT '响应内容',
      `ip_address` varchar(45) DEFAULT NULL COMMENT '请求 IP',
      `user_agent` varchar(500) DEFAULT NULL COMMENT 'User Agent',
      `execution_time` int(11) DEFAULT NULL COMMENT '执行时间(毫秒)',
      `error_message` text DEFAULT NULL COMMENT '错误信息',
      `created_at` datetime DEFAULT CURRENT_TIMESTAMP,
      PRIMARY KEY (`id`),
      KEY `idx_api_key_created` (`api_key_id`, `created_at`),
      KEY `idx_endpoint` (`endpoint`(191))
    ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COMMENT='API 访问日志'
");
echo "  ✓ api_logs 表\n";

// webhooks 表
$conn->query("
    CREATE TABLE IF NOT EXISTS `webhooks` (
      `id` int(11) NOT NULL AUTO_INCREMENT,
      `api_key_id` int(11) NOT NULL,
      `event_type` varchar(50) NOT NULL COMMENT '事件类型',
      `callback_url` varchar(500) NOT NULL COMMENT '回调 URL',
      `secret` varchar(128) NOT NULL COMMENT 'Webhook 签名密钥',
      `description` varchar(255) DEFAULT '' COMMENT '描述',
      `status` tinyint(1) DEFAULT 1 COMMENT '状态：1=启用 0=禁用',
      `retry_times` int(11) DEFAULT 1 COMMENT '重试次数',
      `timeout` int(11) DEFAULT 5 COMMENT '超时时间(秒)',
      `last_triggered_at` datetime DEFAULT NULL COMMENT '最后触发时间',
      `created_at` datetime DEFAULT CURRENT_TIMESTAMP,
      `updated_at` datetime DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
      PRIMARY KEY (`id`),
      KEY `idx_api_key` (`api_key_id`),
      KEY `idx_event_type` (`event_type`)
    ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COMMENT='Webhook 配置表'
");
echo "  ✓ webhooks 表\n";

// webhook_logs 表
$conn->query("
    CREATE TABLE IF NOT EXISTS `webhook_logs` (
      `id` bigint(20) NOT NULL AUTO_INCREMENT,
      `webhook_id` int(11) NOT NULL,
      `event_type` varchar(50) NOT NULL COMMENT '事件类型',
      `event_data` text DEFAULT NULL COMMENT '事件数据',
      `callback_url` varchar(500) DEFAULT NULL COMMENT '回调 URL',
      `request_headers` text DEFAULT NULL COMMENT '请求头',
      `request_body` text DEFAULT NULL COMMENT '请求体',
      `response_code` int(11) DEFAULT NULL COMMENT '响应状态码',
      `response_body` text DEFAULT NULL COMMENT '响应内容',
      `retry_count` tinyint(4) DEFAULT 0 COMMENT '重试次数',
      `status` varchar(20) NOT NULL COMMENT '状态：success/failed',
      `error_message` text DEFAULT NULL COMMENT '错误信息',
      `execution_time` int(11) DEFAULT NULL COMMENT '执行时间(毫秒)',
      `created_at` datetime DEFAULT CURRENT_TIMESTAMP,
      PRIMARY KEY (`id`),
      KEY `idx_webhook_created` (`webhook_id`, `created_at`),
      KEY `idx_event_type` (`event_type`)
    ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COMMENT='Webhook 调用日志'
");
echo "  ✓ webhook_logs 表\n";

// embed_token_nonces 表（游戏嵌入 Token 防重放）
$conn->query("
    CREATE TABLE IF NOT EXISTS `embed_token_nonces` (
      `id` int(11) NOT NULL AUTO_INCREMENT,
      `nonce` varchar(32) NOT NULL,
      `expires_at` datetime NOT NULL,
      PRIMARY KEY (`id`),
      UNIQUE KEY `uk_nonce` (`nonce`),
      KEY `idx_expires` (`expires_at`)
    ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COMMENT='嵌入 Token 防重放表'
");
echo "  ✓ embed_token_nonces 表\n";

// webhook_queue 表（异步队列）
$conn->query("
    CREATE TABLE IF NOT EXISTS `webhook_queue` (
      `id` bigint(20) NOT NULL AUTO_INCREMENT,
      `webhook_id` int(11) NOT NULL COMMENT '关联的 Webhook ID',
      `event_type` varchar(50) NOT NULL COMMENT '事件类型',
      `payload` text NOT NULL COMMENT '事件数据 JSON',
      `status` tinyint(1) DEFAULT 0 COMMENT '0=待处理 1=处理中 2=已完成 3=失败',
      `attempts` tinyint(4) DEFAULT 0 COMMENT '已尝试次数',
      `max_attempts` tinyint(4) DEFAULT 3 COMMENT '最大尝试次数',
      `next_retry_at` datetime DEFAULT NULL COMMENT '下次重试时间',
      `locked_at` datetime DEFAULT NULL COMMENT '锁定时间（处理中）',
      `completed_at` datetime DEFAULT NULL COMMENT '完成时间',
      `error_message` text DEFAULT NULL COMMENT '最后一次错误',
      `created_at` datetime DEFAULT CURRENT_TIMESTAMP,
      PRIMARY KEY (`id`),
      KEY `idx_status_next_retry` (`status`, `next_retry_at`),
      KEY `idx_webhook_id` (`webhook_id`),
      KEY `idx_created_at` (`created_at`)
    ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COMMENT='Webhook 异步发送队列'
");
echo "  ✓ webhook_queue 表\n\n";

// 添加性能优化索引（安全添加，已存在则忽略）
echo "  添加查询性能优化索引...\n";

$indexes = [
    // 投注表索引
    "ALTER TABLE `x_lib` ADD INDEX `idx_userid_created` (`userid`, `created_at`)" ,
    "ALTER TABLE `x_lib` ADD INDEX `idx_gameid_status` (`gameid`, `status`)",
    "ALTER TABLE `x_lib` ADD INDEX `idx_created_at` (`created_at`)",
    // 开奖结果索引
    "ALTER TABLE `x_kj` ADD INDEX `idx_gameid_created` (`gameid`, `created_at`)",
    "ALTER TABLE `x_kj` ADD INDEX `idx_qishu` (`qishu`)",
    // 交易日志索引
    "ALTER TABLE `x_money_log` ADD INDEX `idx_userid_created` (`userid`, `created_at`)",
    "ALTER TABLE `x_money_log` ADD INDEX `idx_type_created` (`type`, `created_at`)",
    // 充值提现表索引
    "ALTER TABLE `x_money` ADD INDEX `idx_userid_mtype` (`userid`, `mtype`)",
    "ALTER TABLE `x_money` ADD INDEX `idx_tjtime` (`tjtime`)",
    // 用户表索引
    "ALTER TABLE `x_user` ADD INDEX `idx_fid` (`fid`)",
];

$addedIndexes = 0;
foreach ($indexes as $sql) {
    if ($conn->query($sql)) {
        $addedIndexes++;
    }
    // 忽略 "Duplicate key name" 错误
}
echo "  ✓ 索引优化完成 ($addedIndexes 个新索引)\n\n";

// 升级已有表结构（安全添加新字段）
echo "  升级表结构...\n";
$upgrades = [
    "ALTER TABLE `api_keys` ADD COLUMN `scopes` text DEFAULT NULL COMMENT '权限范围（JSON 数组）' AFTER `allowed_ips`",
];
foreach ($upgrades as $sql) {
    $conn->query($sql); // 忽略 "Duplicate column" 错误
}
echo "  ✓ 表结构升级完成\n\n";

// 第五步：验证表结构
echo "[5/6] 验证表结构...\n";

$requiredTables = [
    'x_user' => '用户表',
    'x_money' => '充值提现表',
    'x_money_log' => '交易日志表',
    'x_lib' => '投注表',
    'x_kj' => '开奖结果表',
    'x_game' => '游戏表',
    'api_keys' => 'API密钥表',
    'api_logs' => 'API日志表',
    'webhooks' => 'Webhook配置表',
    'webhook_logs' => 'Webhook日志表'
];

$missingTables = [];
foreach ($requiredTables as $table => $desc) {
    $result = $conn->query("SHOW TABLES LIKE '$table'");
    if ($result && $result->num_rows > 0) {
        echo "  ✓ $desc ($table)\n";
    } else {
        echo "  ✗ $desc ($table) - 不存在\n";
        $missingTables[] = $table;
    }
}

if (!empty($missingTables)) {
    echo "\n⚠ 警告: 某些表不存在，可能需要手动创建\n";
} else {
    echo "✓ 所有必要的表都已创建\n";
}
echo "\n";

// 第六步：创建 API Key
echo "[6/6] 创建测试 API Key...\n";

$apiKey = 'test_' . bin2hex(random_bytes(16));
$apiSecret = bin2hex(random_bytes(32));

$sql = "INSERT INTO api_keys (api_key, api_secret, partner_name, agent_id, rate_limit, status, created_at)
        VALUES (?, ?, '测试合作伙伴', 1, 100, 1, NOW())
        ON DUPLICATE KEY UPDATE api_secret = VALUES(api_secret)";

$stmt = $conn->prepare($sql);
if ($stmt) {
    $stmt->bind_param('ss', $apiKey, $apiSecret);
    if ($stmt->execute()) {
        echo "✓ API Key 创建成功\n\n";
        echo "═══════════════════════════════════════\n";
        echo "  API 凭证信息 (请妥善保存)\n";
        echo "═══════════════════════════════════════\n";
        echo "API Key:    $apiKey\n";
        echo "API Secret: $apiSecret\n";
        echo "═══════════════════════════════════════\n\n";

        // 保存到文件
        $credFile = __DIR__ . '/api_credentials.txt';
        file_put_contents($credFile, "API Key: $apiKey\nAPI Secret: $apiSecret\n");
        echo "✓ API 凭证已保存到: $credFile\n\n";
    } else {
        echo "✗ 创建 API Key 失败: " . $stmt->error . "\n";
    }
    $stmt->close();
} else {
    echo "⚠ api_keys 表可能不存在，跳过 API Key 创建\n\n";
}

// 关闭连接
$conn->close();

// 输出配置文件示例
echo "════════════════════════════════════════════════════════════\n";
echo "  下一步操作\n";
echo "════════════════════════════════════════════════════════════\n\n";
echo "1. 更新数据库配置文件:\n";
echo "   编辑: D:\\lottery\\web\\data\\config.inc.php\n\n";
echo "   示例配置:\n";
echo "   <?php\n";
echo "   define('DB_HOST', '{$dbConfig['host']}');\n";
echo "   define('DB_USER', '{$dbConfig['user']}');\n";
echo "   define('DB_PASS', '{$dbConfig['pass']}');\n";
echo "   define('DB_NAME', '{$dbConfig['name']}');\n";
echo "   define('DB_PORT', {$dbConfig['port']});\n";
echo "   ?>\n\n";

echo "2. 配置 Web 服务器 (Nginx/Apache)\n\n";

echo "3. 测试 API 接口:\n";
echo "   访问: http://localhost/api/v1/test.php\n\n";

echo "4. 运行测试脚本:\n";
echo "   cd D:\\lottery\\tests\n";
echo "   php syntax_check.php\n";
echo "   php db_consistency_test.php\n\n";

echo "✓ 数据库初始化完成！\n\n";
