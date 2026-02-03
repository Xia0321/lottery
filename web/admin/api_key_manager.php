<?php
/**
 * API Key 管理工具
 *
 * 用于生成、查看、禁用 API Key
 */

require_once __DIR__ . '/../api/db_config.php';

// 简单的身份验证（生产环境应该使用更安全的认证方式）
session_start();
if (!isset($_SESSION['admin_logged_in'])) {
    // 这里应该跳转到管理员登录页面
    // header('Location: /admin/login.php');
    // exit;
}

$action = $_GET['action'] ?? 'list';

?>
<!DOCTYPE html>
<html lang="zh-CN">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>API Key 管理</title>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: Arial, sans-serif;
            background: #f5f5f5;
            padding: 20px;
        }

        .container {
            max-width: 1200px;
            margin: 0 auto;
            background: #fff;
            padding: 30px;
            border-radius: 8px;
            box-shadow: 0 2px 10px rgba(0,0,0,0.1);
        }

        h1 {
            color: #333;
            margin-bottom: 30px;
            padding-bottom: 15px;
            border-bottom: 2px solid #007bff;
        }

        .action-bar {
            margin-bottom: 20px;
        }

        .btn {
            display: inline-block;
            padding: 10px 20px;
            background: #007bff;
            color: #fff;
            text-decoration: none;
            border-radius: 4px;
            border: none;
            cursor: pointer;
            margin-right: 10px;
        }

        .btn:hover {
            background: #0056b3;
        }

        .btn-danger {
            background: #dc3545;
        }

        .btn-danger:hover {
            background: #c82333;
        }

        .btn-success {
            background: #28a745;
        }

        .btn-success:hover {
            background: #218838;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
        }

        th, td {
            padding: 12px;
            text-align: left;
            border-bottom: 1px solid #ddd;
        }

        th {
            background: #f8f9fa;
            font-weight: bold;
            color: #333;
        }

        tr:hover {
            background: #f8f9fa;
        }

        .status-active {
            color: #28a745;
            font-weight: bold;
        }

        .status-disabled {
            color: #dc3545;
            font-weight: bold;
        }

        .form-group {
            margin-bottom: 20px;
        }

        label {
            display: block;
            margin-bottom: 5px;
            color: #333;
            font-weight: bold;
        }

        input[type="text"],
        input[type="number"],
        select {
            width: 100%;
            padding: 10px;
            border: 1px solid #ddd;
            border-radius: 4px;
            font-size: 14px;
        }

        .alert {
            padding: 15px;
            margin-bottom: 20px;
            border-radius: 4px;
        }

        .alert-success {
            background: #d4edda;
            color: #155724;
            border: 1px solid #c3e6cb;
        }

        .alert-danger {
            background: #f8d7da;
            color: #721c24;
            border: 1px solid #f5c6cb;
        }

        .alert-warning {
            background: #fff3cd;
            color: #856404;
            border: 1px solid #ffeaa7;
        }

        .code-block {
            background: #f4f4f4;
            padding: 15px;
            border-radius: 4px;
            font-family: monospace;
            overflow-x: auto;
            margin-top: 10px;
        }
    </style>
</head>
<body>
    <div class="container">
        <h1>API Key 管理</h1>

        <div class="action-bar">
            <a href="?action=list" class="btn">查看所有</a>
            <a href="?action=create" class="btn btn-success">生成新 Key</a>
        </div>

        <?php
        switch ($action) {
            case 'create':
                handleCreate($pdo, $redis);
                break;

            case 'disable':
                handleDisable($pdo);
                break;

            case 'enable':
                handleEnable($pdo);
                break;

            case 'list':
            default:
                showList($pdo);
                break;
        }
        ?>
    </div>
</body>
</html>

<?php

/**
 * 显示 API Key 列表
 */
function showList($pdo) {
    try {
        $stmt = $pdo->query("
            SELECT id, api_key, name, agent_id, rate_limit, status,
                   created_at, last_used_at
            FROM api_keys
            ORDER BY created_at DESC
        ");
        $keys = $stmt->fetchAll();

        echo '<table>';
        echo '<thead>';
        echo '<tr>';
        echo '<th>ID</th>';
        echo '<th>名称</th>';
        echo '<th>API Key</th>';
        echo '<th>代理 ID</th>';
        echo '<th>速率限制</th>';
        echo '<th>状态</th>';
        echo '<th>最后使用</th>';
        echo '<th>创建时间</th>';
        echo '<th>操作</th>';
        echo '</tr>';
        echo '</thead>';
        echo '<tbody>';

        foreach ($keys as $key) {
            $statusClass = $key['status'] == 1 ? 'status-active' : 'status-disabled';
            $statusText = $key['status'] == 1 ? '启用' : '禁用';

            echo '<tr>';
            echo '<td>' . htmlspecialchars($key['id']) . '</td>';
            echo '<td>' . htmlspecialchars($key['name']) . '</td>';
            echo '<td>' . htmlspecialchars($key['api_key']) . '</td>';
            echo '<td>' . ($key['agent_id'] ?: '全部') . '</td>';
            echo '<td>' . htmlspecialchars($key['rate_limit']) . ' 次/分钟</td>';
            echo '<td class="' . $statusClass . '">' . $statusText . '</td>';
            echo '<td>' . ($key['last_used_at'] ?: '从未使用') . '</td>';
            echo '<td>' . htmlspecialchars($key['created_at']) . '</td>';
            echo '<td>';

            if ($key['status'] == 1) {
                echo '<a href="?action=disable&id=' . $key['id'] . '" class="btn btn-danger" onclick="return confirm(\'确定要禁用吗？\')">禁用</a>';
            } else {
                echo '<a href="?action=enable&id=' . $key['id'] . '" class="btn btn-success">启用</a>';
            }

            echo '</td>';
            echo '</tr>';
        }

        echo '</tbody>';
        echo '</table>';

    } catch (Exception $e) {
        echo '<div class="alert alert-danger">查询失败: ' . htmlspecialchars($e->getMessage()) . '</div>';
    }
}


/**
 * 处理创建 API Key
 */
function handleCreate($pdo, $redis) {
    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        try {
            $name = $_POST['name'] ?? '';
            $agentId = !empty($_POST['agent_id']) ? intval($_POST['agent_id']) : null;
            $rateLimit = intval($_POST['rate_limit'] ?? 100);

            if (empty($name)) {
                throw new Exception('名称不能为空');
            }

            if ($rateLimit < 1 || $rateLimit > 10000) {
                throw new Exception('速率限制必须在 1-10000 之间');
            }

            $apiAuth = new ApiAuth($pdo, $redis);
            $credentials = $apiAuth->generateApiKey($name, $agentId, $rateLimit);

            echo '<div class="alert alert-success">';
            echo '<strong>API Key 生成成功！</strong><br>';
            echo '<div class="code-block">';
            echo '<strong>API Key:</strong> ' . htmlspecialchars($credentials['api_key']) . '<br>';
            echo '<strong>API Secret:</strong> ' . htmlspecialchars($credentials['api_secret']) . '<br>';
            echo '</div>';
            echo '<div class="alert alert-warning" style="margin-top: 15px;">';
            echo '<strong>重要提示：</strong> API Secret 只显示一次，请立即复制保存！';
            echo '</div>';
            echo '</div>';

            echo '<a href="?action=list" class="btn">返回列表</a>';
            return;

        } catch (Exception $e) {
            echo '<div class="alert alert-danger">生成失败: ' . htmlspecialchars($e->getMessage()) . '</div>';
        }
    }

    // 显示创建表单
    ?>
    <h2>生成新的 API Key</h2>

    <form method="POST">
        <div class="form-group">
            <label for="name">名称 *</label>
            <input type="text" id="name" name="name" required placeholder="例如：外部平台A">
        </div>

        <div class="form-group">
            <label for="agent_id">代理 ID（可选）</label>
            <input type="number" id="agent_id" name="agent_id" placeholder="留空表示无限制">
            <small>设置后只能查询该代理下的数据（数据隔离）</small>
        </div>

        <div class="form-group">
            <label for="rate_limit">速率限制（次/分钟）*</label>
            <input type="number" id="rate_limit" name="rate_limit" value="100" required min="1" max="10000">
        </div>

        <button type="submit" class="btn btn-success">生成 API Key</button>
        <a href="?action=list" class="btn">取消</a>
    </form>
    <?php
}


/**
 * 禁用 API Key
 */
function handleDisable($pdo) {
    try {
        $id = intval($_GET['id'] ?? 0);

        if ($id <= 0) {
            throw new Exception('无效的 ID');
        }

        $stmt = $pdo->prepare("UPDATE api_keys SET status = 0 WHERE id = ?");
        $stmt->execute([$id]);

        echo '<div class="alert alert-success">API Key 已禁用</div>';
        echo '<a href="?action=list" class="btn">返回列表</a>';

    } catch (Exception $e) {
        echo '<div class="alert alert-danger">禁用失败: ' . htmlspecialchars($e->getMessage()) . '</div>';
        echo '<a href="?action=list" class="btn">返回列表</a>';
    }
}


/**
 * 启用 API Key
 */
function handleEnable($pdo) {
    try {
        $id = intval($_GET['id'] ?? 0);

        if ($id <= 0) {
            throw new Exception('无效的 ID');
        }

        $stmt = $pdo->prepare("UPDATE api_keys SET status = 1 WHERE id = ?");
        $stmt->execute([$id]);

        echo '<div class="alert alert-success">API Key 已启用</div>';
        echo '<a href="?action=list" class="btn">返回列表</a>';

    } catch (Exception $e) {
        echo '<div class="alert alert-danger">启用失败: ' . htmlspecialchars($e->getMessage()) . '</div>';
        echo '<a href="?action=list" class="btn">返回列表</a>';
    }
}
?>
