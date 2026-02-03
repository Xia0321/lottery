<?php
/**
 * 数据库迁移执行脚本
 * 使用方法: php run_migration.php <migration_file.sql>
 * 示例: php run_migration.php 001_create_api_tables.sql
 */

// 加载数据库配置
require_once(__DIR__ . '/../web/data/config.inc.php');

if (php_sapi_name() !== 'cli') {
    die("This script must be run from command line.\n");
}

// 获取迁移文件名
$migrationFile = $argv[1] ?? null;

if (!$migrationFile) {
    echo "Usage: php run_migration.php <migration_file.sql>\n";
    echo "Example: php run_migration.php 001_create_api_tables.sql\n";
    exit(1);
}

// 检查文件是否存在
$migrationPath = __DIR__ . '/' . $migrationFile;
if (!file_exists($migrationPath)) {
    echo "Error: Migration file not found: $migrationFile\n";
    exit(1);
}

echo "==============================================\n";
echo "Database Migration Tool\n";
echo "==============================================\n";
echo "Migration file: $migrationFile\n";
echo "Database: {$config['dbname']}\n";
echo "Host: {$config['dbhost']}\n";
echo "==============================================\n\n";

// 连接数据库
try {
    $mysqli = new mysqli($dbHost, $dbUser, $dbPass, $dbName, $dbPort);

    if ($mysqli->connect_error) {
        throw new Exception("Connection failed: " . $mysqli->connect_error);
    }

    echo "✓ Connected to database successfully\n\n";

    // 设置字符集
    $mysqli->set_charset("utf8mb4");

    // 读取 SQL 文件
    $sql = file_get_contents($migrationPath);

    if ($sql === false) {
        throw new Exception("Failed to read migration file");
    }

    echo "✓ Migration file loaded\n\n";

    // 分割 SQL 语句
    $statements = explode(';', $sql);
    $executedCount = 0;
    $errorCount = 0;

    echo "Executing SQL statements...\n";
    echo "----------------------------------------------\n";

    foreach ($statements as $statement) {
        $statement = trim($statement);

        // 跳过空语句和注释
        if (empty($statement) || strpos($statement, '--') === 0) {
            continue;
        }

        // 执行语句
        if ($mysqli->query($statement)) {
            $executedCount++;

            // 提取语句类型
            preg_match('/^\s*(CREATE|INSERT|ALTER|DROP|UPDATE)/i', $statement, $matches);
            $type = $matches[1] ?? 'SQL';

            // 提取表名
            preg_match('/TABLE\s+`?(\w+)`?/i', $statement, $matches);
            $table = $matches[1] ?? '';

            if ($table) {
                echo "✓ $type TABLE $table\n";
            } else {
                echo "✓ $type statement executed\n";
            }
        } else {
            $errorCount++;
            $error = $mysqli->error;

            // 如果是 "Table already exists" 错误，只显示警告
            if (strpos($error, 'already exists') !== false) {
                preg_match('/TABLE\s+`?(\w+)`?/i', $statement, $matches);
                $table = $matches[1] ?? 'unknown';
                echo "⚠ WARNING: Table $table already exists (skipped)\n";
            } else {
                echo "✗ ERROR: $error\n";
                echo "   Statement: " . substr($statement, 0, 100) . "...\n";
            }
        }
    }

    echo "----------------------------------------------\n\n";
    echo "Migration Summary:\n";
    echo "  Total statements: " . ($executedCount + $errorCount) . "\n";
    echo "  Executed: $executedCount\n";
    echo "  Errors: $errorCount\n\n";

    // 验证表是否创建成功
    echo "Verifying tables...\n";
    echo "----------------------------------------------\n";

    $tables = ['api_keys', 'api_logs', 'webhooks', 'webhook_logs'];
    $missingTables = [];

    foreach ($tables as $table) {
        $result = $mysqli->query("SHOW TABLES LIKE '$table'");
        if ($result && $result->num_rows > 0) {
            // 获取行数
            $countResult = $mysqli->query("SELECT COUNT(*) as cnt FROM `$table`");
            $count = $countResult->fetch_assoc()['cnt'];
            echo "✓ Table $table exists (rows: $count)\n";
        } else {
            echo "✗ Table $table NOT FOUND\n";
            $missingTables[] = $table;
        }
    }

    echo "----------------------------------------------\n\n";

    if (empty($missingTables)) {
        echo "✓ Migration completed successfully!\n\n";

        // 显示测试 API Key 信息
        $result = $mysqli->query("SELECT * FROM api_keys WHERE api_key = 'test_api_key_12345678'");
        if ($result && $result->num_rows > 0) {
            $testKey = $result->fetch_assoc();
            echo "Test API Key created:\n";
            echo "  API Key: " . $testKey['api_key'] . "\n";
            echo "  API Secret: " . $testKey['api_secret'] . "\n";
            echo "  Partner: " . $testKey['partner_name'] . "\n";
            echo "  ⚠ WARNING: Remove test data in production!\n\n";
        }

        exit(0);
    } else {
        echo "✗ Migration completed with errors\n";
        echo "Missing tables: " . implode(', ', $missingTables) . "\n\n";
        exit(1);
    }

} catch (Exception $e) {
    echo "✗ ERROR: " . $e->getMessage() . "\n";
    exit(1);
} finally {
    if (isset($mysqli) && $mysqli) {
        $mysqli->close();
    }
}
