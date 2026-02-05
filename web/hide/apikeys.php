<?php
/**
 * API Key 管理后台
 *
 * 功能：创建、查看、启用/禁用、删除 API Key
 * 权限：仅超级管理员（hides=1）可访问
 */
include('../data/comm.inc.php');
include('../data/myadminvar.php');
include('../func/func.php');
include('../func/adminfunc.php');
include('../global/page.class.php');
include('../include.php');
include('./checklogin.php');

// 仅超级管理员可访问
if ($_SESSION['hides'] != 1) {
    echo "<script>alert('无权限访问');history.back();</script>";
    exit;
}

switch ($_REQUEST['xtype']) {
    case "list":
        apiKeyList();
        break;
    case "add":
        apiKeyAdd();
        break;
    case "toggle":
        apiKeyToggle();
        break;
    case "del":
        apiKeyDel();
        break;
    case "detail":
        apiKeyDetail();
        break;
    case "updatelimit":
        apiKeyUpdateLimit();
        break;
    case "updateips":
        apiKeyUpdateIPs();
        break;
    case "updatescopes":
        apiKeyUpdateScopes();
        break;
    default:
        apiKeyList();
        break;
}

/**
 * API Key 列表
 */
function apiKeyList() {
    global $msql, $tpl, $config;

    $psize = $config['psize'] ?: 20;

    // 总数
    $msql->query("SELECT COUNT(*) as cnt FROM `api_keys`");
    $msql->next_record();
    $rcount = $msql->f('cnt');

    $thispage = isset($_REQUEST['PB_page']) ? max(1, intval($_REQUEST['PB_page'])) : 1;
    $page = new page(array(
        'total' => $rcount,
        'perpage' => $psize,
        'nowindex' => $thispage
    ));

    $offset = ($thispage - 1) * $psize;
    $msql->query("SELECT * FROM `api_keys` ORDER BY id DESC LIMIT $offset, $psize");

    $data = array();
    $i = 0;
    while ($msql->next_record()) {
        $data[$i]['id'] = $msql->f('id');
        $data[$i]['api_key'] = $msql->f('api_key');
        $data[$i]['partner_name'] = $msql->f('partner_name');
        $data[$i]['agent_id'] = $msql->f('agent_id');
        $data[$i]['status'] = $msql->f('status');
        $data[$i]['rate_limit'] = $msql->f('rate_limit');
        $data[$i]['last_used_at'] = $msql->f('last_used_at') ?: '从未使用';
        $data[$i]['created_at'] = $msql->f('created_at');
        $data[$i]['expires_at'] = $msql->f('expires_at') ?: '永不过期';
        $i++;
    }

    $tpl->assign('data', $data);
    $tpl->assign('page', $page->show());
    $tpl->assign('total', $rcount);
    $tpl->display('apikeys_list.html');
}

/**
 * 创建 API Key
 */
function apiKeyAdd() {
    global $msql;

    $partnerName = isset($_POST['partner_name']) ? trim($_POST['partner_name']) : '';
    $agentId = isset($_POST['agent_id']) ? intval($_POST['agent_id']) : 0;
    $rateLimit = isset($_POST['rate_limit']) ? intval($_POST['rate_limit']) : 100;
    $expiresAt = isset($_POST['expires_at']) ? trim($_POST['expires_at']) : '';

    if (empty($partnerName)) {
        echo json_encode(['code' => 1, 'msg' => '合作伙伴名称不能为空']);
        exit;
    }

    if ($agentId <= 0) {
        echo json_encode(['code' => 1, 'msg' => '请选择关联代理']);
        exit;
    }

    if ($rateLimit < 1 || $rateLimit > 10000) {
        echo json_encode(['code' => 1, 'msg' => '速率限制范围: 1-10000']);
        exit;
    }

    // 生成 API Key 和 Secret
    $apiKey = 'ak_' . bin2hex(random_bytes(16));
    $apiSecret = bin2hex(random_bytes(32));

    $expiresValue = !empty($expiresAt) ? "'" . $msql->escape($expiresAt) . "'" : "NULL";

    $sql = "INSERT INTO `api_keys` (api_key, api_secret, partner_name, agent_id, rate_limit, status, expires_at, created_at)
            VALUES ('" . $msql->escape($apiKey) . "',
                    '" . $msql->escape($apiSecret) . "',
                    '" . $msql->escape($partnerName) . "',
                    " . intval($agentId) . ",
                    " . intval($rateLimit) . ",
                    1,
                    $expiresValue,
                    NOW())";

    $msql->query($sql);

    echo json_encode([
        'code' => 0,
        'msg' => '创建成功',
        'data' => [
            'api_key' => $apiKey,
            'api_secret' => $apiSecret
        ]
    ]);
    exit;
}

/**
 * 启用/禁用 API Key
 */
function apiKeyToggle() {
    global $msql;

    $id = isset($_POST['id']) ? intval($_POST['id']) : 0;
    if ($id <= 0) {
        echo json_encode(['code' => 1, 'msg' => '无效 ID']);
        exit;
    }

    // 获取当前状态
    $msql->query("SELECT status FROM `api_keys` WHERE id = $id");
    $msql->next_record();
    $currentStatus = $msql->f('status');
    $newStatus = ($currentStatus == 1) ? 0 : 1;

    $msql->query("UPDATE `api_keys` SET status = $newStatus WHERE id = $id");

    echo json_encode([
        'code' => 0,
        'msg' => $newStatus == 1 ? '已启用' : '已禁用'
    ]);
    exit;
}

/**
 * 删除 API Key
 */
function apiKeyDel() {
    global $msql;

    $id = isset($_POST['id']) ? intval($_POST['id']) : 0;
    if ($id <= 0) {
        echo json_encode(['code' => 1, 'msg' => '无效 ID']);
        exit;
    }

    $msql->query("DELETE FROM `api_keys` WHERE id = $id");

    echo json_encode(['code' => 0, 'msg' => '已删除']);
    exit;
}

/**
 * API Key 详情（含 Secret、日志统计）
 */
function apiKeyDetail() {
    global $msql, $tpl;

    $id = isset($_REQUEST['id']) ? intval($_REQUEST['id']) : 0;
    if ($id <= 0) {
        echo "无效 ID";
        exit;
    }

    $msql->query("SELECT * FROM `api_keys` WHERE id = $id");
    $msql->next_record();

    $detail = array(
        'id' => $msql->f('id'),
        'api_key' => $msql->f('api_key'),
        'api_secret' => $msql->f('api_secret'),
        'partner_name' => $msql->f('partner_name'),
        'agent_id' => $msql->f('agent_id'),
        'status' => $msql->f('status'),
        'rate_limit' => $msql->f('rate_limit'),
        'allowed_ips' => $msql->f('allowed_ips') ?: '',
        'allowed_ips_display' => $msql->f('allowed_ips') ?: '不限制',
        'scopes' => $msql->f('scopes') ?: '',
        'scopes_display' => $msql->f('scopes') ?: '全部权限',
        'expires_at' => $msql->f('expires_at') ?: '永不过期',
        'last_used_at' => $msql->f('last_used_at') ?: '从未使用',
        'created_at' => $msql->f('created_at')
    );

    // 最近7天调用统计
    $msql->query("SELECT COUNT(*) as cnt FROM `api_logs` WHERE api_key_id = $id AND created_at >= DATE_SUB(NOW(), INTERVAL 7 DAY)");
    $msql->next_record();
    $detail['call_count_7d'] = $msql->f('cnt');

    // 最近7天错误统计
    $msql->query("SELECT COUNT(*) as cnt FROM `api_logs` WHERE api_key_id = $id AND response_code >= 400 AND created_at >= DATE_SUB(NOW(), INTERVAL 7 DAY)");
    $msql->next_record();
    $detail['error_count_7d'] = $msql->f('cnt');

    // 最近10条调用日志
    $msql->query("SELECT endpoint, method, response_code, ip_address, execution_time, created_at FROM `api_logs` WHERE api_key_id = $id ORDER BY id DESC LIMIT 10");
    $logs = array();
    $j = 0;
    while ($msql->next_record()) {
        $logs[$j]['endpoint'] = $msql->f('endpoint');
        $logs[$j]['method'] = $msql->f('method');
        $logs[$j]['response_code'] = $msql->f('response_code');
        $logs[$j]['ip_address'] = $msql->f('ip_address');
        $logs[$j]['execution_time'] = $msql->f('execution_time');
        $logs[$j]['created_at'] = $msql->f('created_at');
        $j++;
    }

    $tpl->assign('detail', $detail);
    $tpl->assign('logs', $logs);
    $tpl->display('apikeys_detail.html');
}

/**
 * 更新速率限制
 */
function apiKeyUpdateLimit() {
    global $msql;

    $id = isset($_POST['id']) ? intval($_POST['id']) : 0;
    $rateLimit = isset($_POST['rate_limit']) ? intval($_POST['rate_limit']) : 100;

    if ($id <= 0) {
        echo json_encode(['code' => 1, 'msg' => '无效 ID']);
        exit;
    }

    if ($rateLimit < 1 || $rateLimit > 10000) {
        echo json_encode(['code' => 1, 'msg' => '速率限制范围: 1-10000']);
        exit;
    }

    $msql->query("UPDATE `api_keys` SET rate_limit = $rateLimit WHERE id = $id");

    echo json_encode(['code' => 0, 'msg' => '已更新']);
    exit;
}

/**
 * 更新 IP 白名单
 */
function apiKeyUpdateIPs() {
    global $msql;

    $id = isset($_POST['id']) ? intval($_POST['id']) : 0;
    $ipsStr = isset($_POST['allowed_ips']) ? trim($_POST['allowed_ips']) : '';

    if ($id <= 0) {
        echo json_encode(['code' => 1, 'msg' => '无效 ID']);
        exit;
    }

    if (empty($ipsStr)) {
        // 清空白名单（允许所有 IP）
        $msql->query("UPDATE `api_keys` SET allowed_ips = NULL WHERE id = $id");
        echo json_encode(['code' => 0, 'msg' => '已清空 IP 白名单（允许所有 IP）']);
        exit;
    }

    // 解析 IP 列表（逗号或换行分隔）
    $ips = preg_split('/[\s,]+/', $ipsStr, -1, PREG_SPLIT_NO_EMPTY);
    $validIps = [];

    foreach ($ips as $ip) {
        $ip = trim($ip);
        // 验证 IP 或 CIDR 格式
        if (filter_var($ip, FILTER_VALIDATE_IP)) {
            $validIps[] = $ip;
        } elseif (preg_match('#^(\d{1,3}\.){3}\d{1,3}/\d{1,2}$#', $ip)) {
            $validIps[] = $ip;
        }
    }

    if (empty($validIps)) {
        echo json_encode(['code' => 1, 'msg' => '未找到有效的 IP 地址']);
        exit;
    }

    $jsonIps = json_encode($validIps);
    $escaped = $msql->escape($jsonIps);
    $msql->query("UPDATE `api_keys` SET allowed_ips = '$escaped' WHERE id = $id");

    echo json_encode(['code' => 0, 'msg' => '已更新 IP 白名单（' . count($validIps) . ' 个）']);
    exit;
}

/**
 * 更新权限范围（Scopes）
 */
function apiKeyUpdateScopes() {
    global $msql;

    $id = isset($_POST['id']) ? intval($_POST['id']) : 0;
    $scopesStr = isset($_POST['scopes']) ? trim($_POST['scopes']) : '';

    if ($id <= 0) {
        echo json_encode(['code' => 1, 'msg' => '无效 ID']);
        exit;
    }

    if (empty($scopesStr) || $scopesStr === '*') {
        // 清空 = 全部权限
        $msql->query("UPDATE `api_keys` SET scopes = NULL WHERE id = $id");
        echo json_encode(['code' => 0, 'msg' => '已设为全部权限']);
        exit;
    }

    // 解析 scope 列表
    $scopes = preg_split('/[\s,]+/', $scopesStr, -1, PREG_SPLIT_NO_EMPTY);
    $validScopes = [];

    $allowedScopes = [
        '*',
        'bets.*', 'bets.read', 'bets.write',
        'users.*', 'users.read', 'users.write',
        'transactions.*', 'transactions.read',
        'deposits.*', 'deposits.read', 'deposits.write',
        'withdrawals.*', 'withdrawals.read', 'withdrawals.write',
        'lottery_results.*', 'lottery_results.read',
        'webhooks.*', 'webhooks.read', 'webhooks.write',
        'embed.*', 'embed.token',
    ];

    foreach ($scopes as $scope) {
        $scope = trim(strtolower($scope));
        if (in_array($scope, $allowedScopes)) {
            $validScopes[] = $scope;
        }
    }

    if (empty($validScopes)) {
        echo json_encode(['code' => 1, 'msg' => '未找到有效的权限标识']);
        exit;
    }

    $jsonScopes = json_encode($validScopes);
    $escaped = $msql->escape($jsonScopes);
    $msql->query("UPDATE `api_keys` SET scopes = '$escaped' WHERE id = $id");

    echo json_encode(['code' => 0, 'msg' => '已更新权限（' . count($validScopes) . ' 项）']);
    exit;
}
?>
