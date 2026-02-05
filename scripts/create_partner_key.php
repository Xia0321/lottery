<?php
/**
 * 创建合作伙伴 API Key 脚本（生产环境用）
 *
 * 用法:
 *   php scripts/create_partner_key.php <合作伙伴名称> <代理ID> [每分钟限制]
 *
 * 示例:
 *   php scripts/create_partner_key.php "一号合作伙伴" 1 200
 */

if (php_sapi_name() !== 'cli') {
    http_response_code(403);
    exit('CLI only');
}

$partnerName = isset($argv[1]) ? $argv[1] : '';
$agentId     = isset($argv[2]) ? (int)$argv[2] : 0;
$rateLimit   = isset($argv[3]) ? (int)$argv[3] : 100;

if (empty($partnerName) || $agentId <= 0) {
    echo "用法: php create_partner_key.php <合作伙伴名称> <代理ID> [每分钟限制]\n";
    echo "示例: php create_partner_key.php \"一号合作伙伴\" 1 200\n";
    exit(1);
}

// 加载数据库连接
require_once(__DIR__ . '/../web/data/config.inc.php');
require_once(__DIR__ . '/../web/data/db.php');
require_once(__DIR__ . '/../web/global/db.inc.php');

global $msql;
$mysqli = $msql->get_mysqli();

// 生成 Key 和 Secret
$apiKey = 'lk_' . bin2hex(random_bytes(16));
$apiSecret = bin2hex(random_bytes(32));

// 默认全权限
$scopes = json_encode(['*']);

$stmt = $mysqli->prepare("
    INSERT INTO api_keys (api_key, api_secret, partner_name, agent_id, rate_limit, scopes, status, created_at)
    VALUES (?, ?, ?, ?, ?, ?, 1, NOW())
");
$stmt->bind_param('sssiss', $apiKey, $apiSecret, $partnerName, $agentId, $rateLimit, $scopes);

if ($stmt->execute()) {
    $id = $stmt->insert_id;
    echo "\n";
    echo "============================================\n";
    echo " API Key 创建成功\n";
    echo "============================================\n";
    echo "ID:          $id\n";
    echo "合作伙伴:    $partnerName\n";
    echo "代理 ID:     $agentId\n";
    echo "速率限制:    {$rateLimit}/分钟\n";
    echo "权限:        全部\n";
    echo "--------------------------------------------\n";
    echo "API Key:     $apiKey\n";
    echo "API Secret:  $apiSecret\n";
    echo "--------------------------------------------\n";
    echo "\n";
    echo "请将以上凭证安全地发送给合作伙伴\n";
    echo "\n";
} else {
    echo "创建失败: " . $stmt->error . "\n";
    exit(1);
}

$stmt->close();
