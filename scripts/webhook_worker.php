<?php
/**
 * Webhook 异步队列 Worker
 *
 * 从 webhook_queue 表中取出待发送的任务，依次处理。
 *
 * 运行方式:
 *   1. Cron 定时执行（推荐每分钟一次）:
 *      * * * * * php /path/to/scripts/webhook_worker.php
 *
 *   2. 常驻进程（配合 supervisor 等）:
 *      php /path/to/scripts/webhook_worker.php --daemon
 *
 * 参数:
 *   --daemon       常驻运行模式（循环处理）
 *   --batch=N      每次取 N 条任务（默认 20）
 *   --sleep=N      daemon 模式下空闲等待秒数（默认 5）
 *   --cleanup      执行队列清理（删除7天前已完成/失败的记录）
 *   --stats        显示队列状态统计
 */

// 防止浏览器访问
if (php_sapi_name() !== 'cli') {
    http_response_code(403);
    exit('CLI only');
}

// 解析参数
$daemon = in_array('--daemon', $argv);
$batch = 20;
$sleep = 5;

foreach ($argv as $arg) {
    if (strpos($arg, '--batch=') === 0) {
        $batch = (int)substr($arg, 8);
        $batch = max(1, min(100, $batch));
    }
    if (strpos($arg, '--sleep=') === 0) {
        $sleep = (int)substr($arg, 8);
        $sleep = max(1, min(60, $sleep));
    }
}

// 加载配置和数据库
require_once(__DIR__ . '/../web/data/config.inc.php');
require_once(__DIR__ . '/../web/data/db.php');
require_once(__DIR__ . '/../web/global/db.inc.php');
require_once(__DIR__ . '/../web/class/api/WebhookManager.php');

global $msql;
$mysqli = $msql->get_mysqli();
$manager = new WebhookManager($mysqli);

// --stats: 显示统计
if (in_array('--stats', $argv)) {
    $stats = $manager->getQueueStats();
    echo "Webhook 队列状态:\n";
    echo "  总计:    {$stats['total']}\n";
    echo "  待处理:  {$stats['pending']}\n";
    echo "  处理中:  {$stats['processing']}\n";
    echo "  已完成:  {$stats['completed']}\n";
    echo "  重试中:  {$stats['retrying']}\n";
    echo "  已失败:  {$stats['failed']}\n";
    exit(0);
}

// --cleanup: 清理旧记录
if (in_array('--cleanup', $argv)) {
    $cleaned = $manager->cleanupQueue(7);
    echo "已清理 $cleaned 条过期队列记录\n";
    exit(0);
}

// 主处理逻辑
$processedTotal = 0;
$successTotal = 0;
$failTotal = 0;

$logPrefix = date('Y-m-d H:i:s');
echo "[$logPrefix] Webhook Worker 启动 (batch=$batch" . ($daemon ? ", daemon, sleep={$sleep}s" : "") . ")\n";

do {
    $jobs = $manager->dequeue($batch);

    if (empty($jobs)) {
        if (!$daemon) {
            break; // 非 daemon 模式，无任务则退出
        }
        sleep($sleep);
        continue;
    }

    foreach ($jobs as $job) {
        $processedTotal++;
        $prefix = date('Y-m-d H:i:s');
        echo "[$prefix] 处理 #{$job['id']} event={$job['event_type']} webhook={$job['webhook_id']} attempt={$job['attempts']}... ";

        $ok = $manager->processJob($job);

        if ($ok) {
            $successTotal++;
            echo "OK\n";
        } else {
            $failTotal++;
            echo "FAIL\n";
        }
    }

    // daemon 模式下短暂休眠避免 CPU 空转
    if ($daemon) {
        usleep(100000); // 100ms
    }

} while ($daemon);

$logPrefix = date('Y-m-d H:i:s');
echo "[$logPrefix] Worker 结束: 处理=$processedTotal 成功=$successTotal 失败=$failTotal\n";
