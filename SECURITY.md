# 彩票系统安全文档

## 安全修复概览

本文档记录了2026年2月3日完成的全面安全加固工作。

### 修复的漏洞统计

#### P0 - 严重威胁 (已完成)
- ✅ WebShell 后门移除 (1个文件)
- ✅ 硬编码后门账号移除 (1个后门)
- ✅ SQL 注入修复 - 登录页面 (4个文件)

#### P1 - 高危漏洞 (已完成)
- ✅ SQL 注入修复 - 管理后台 (3个文件, 15+处漏洞)
- ✅ 任意文件读取修复 (4个文件)
- ✅ XSS 跨站脚本修复 (3个文件, 12处漏洞)

#### P2 - 中危漏洞 (已完成)
- ✅ SQL 注入修复 - 业务逻辑 (2个文件)
- ✅ 数据库层已使用 MySQLi (无需迁移)
- ✅ 不安全会话ID生成修复
- ✅ 调试代码移除

#### P3 - 安全增强 (已完成)
- ✅ CSRF 保护机制
- ✅ 输入验证增强
- ✅ 安全日志记录

**总计修复: 40+ 个安全漏洞**

---

## 安全功能使用指南

### 1. CSRF 保护

CSRF (跨站请求伪造) 保护已通过 `csrf_helper.php` 实现。

#### 在表单中添加 CSRF Token

```php
<?php
require_once('../global/csrf_helper.php');
?>

<form method="POST" action="submit.php">
    <?php csrfTokenField(); ?>
    <!-- 其他表单字段 -->
    <input type="text" name="username">
    <button type="submit">提交</button>
</form>
```

#### 验证 CSRF Token

```php
<?php
require_once('../global/csrf_helper.php');

// 方法1: 自动验证并在失败时终止
validateCsrfToken('POST'); // 验证失败会自动返回403错误

// 方法2: 手动验证
if (!validateCsrfToken('POST', false)) {
    // 处理验证失败
    echo json_encode(['error' => 'CSRF验证失败']);
    exit;
}

// 继续处理请求...
?>
```

#### AJAX 请求中使用 CSRF Token

```javascript
// 在页面中输出 token
<script>
const csrfToken = '<?php echo getCsrfToken(); ?>';
</script>

// AJAX 请求中包含 token
fetch('/api/endpoint', {
    method: 'POST',
    headers: {
        'Content-Type': 'application/json'
    },
    body: JSON.stringify({
        csrf_token: csrfToken,
        // 其他数据...
    })
});
```

---

### 2. 输入验证

使用 `InputValidator` 类进行统一的输入验证和清理。

#### 基本使用

```php
<?php
require_once('../global/input_validator.php');

// 获取并验证整数
$userId = InputValidator::getInput('user_id', 'int', 'POST', 0, [
    'min' => 1,
    'max' => 999999
]);

// 获取并验证用户名
$username = InputValidator::getInput('username', 'username', 'POST', '', [
    'minLength' => 3,
    'maxLength' => 20
]);

// 获取并验证邮箱
$email = InputValidator::getInput('email', 'email', 'POST');

// 获取并验证手机号
$phone = InputValidator::getInput('phone', 'phone', 'POST');

// 获取并验证日期
$date = InputValidator::getInput('date', 'date', 'POST', '', [
    'format' => 'Y-m-d'
]);
?>
```

#### 批量验证

```php
<?php
// 定义验证规则
$rules = [
    'user_id' => [
        'type' => 'int',
        'default' => 0,
        'options' => ['min' => 1]
    ],
    'username' => [
        'type' => 'username',
        'default' => '',
        'options' => ['minLength' => 3, 'maxLength' => 20]
    ],
    'email' => [
        'type' => 'email',
        'default' => ''
    ],
    'amount' => [
        'type' => 'float',
        'default' => 0.0,
        'options' => ['min' => 0.01, 'max' => 10000.00]
    ]
];

// 批量获取和验证
$data = InputValidator::getBatchInput($rules, 'POST');

echo $data['user_id'];
echo $data['username'];
echo $data['email'];
echo $data['amount'];
?>
```

#### 支持的数据类型

| 类型 | 说明 | 选项 |
|------|------|------|
| `int` | 整数 | min, max |
| `float` | 浮点数 | min, max |
| `string` | 字符串 | maxLength |
| `username` | 用户名 | minLength, maxLength |
| `email` | 邮箱地址 | - |
| `phone` | 手机号 | - |
| `date` | 日期 | format |
| `ip` | IP地址 | - |
| `url` | URL | - |
| `filename` | 文件名 | - |
| `html` | HTML内容 | allowedTags |
| `array_int` | 整数数组 | min, max |

---

### 3. 安全日志记录

使用 `SecurityLogger` 类记录安全相关事件。

#### 登录相关日志

```php
<?php
require_once('../global/security_logger.php');

// 记录登录失败
SecurityLogger::logLoginFailure($username, 'Invalid password');

// 记录登录成功
SecurityLogger::logLoginSuccess($username, $userId);

// 记录登出
SecurityLogger::logLogout($username, $userId);
?>
```

#### 访问控制日志

```php
<?php
// 记录权限拒绝
SecurityLogger::logAccessDenied('/admin/users', 'DELETE', $username);

// 记录CSRF验证失败
SecurityLogger::logCsrfFailure('user_update');
?>
```

#### 攻击检测日志

```php
<?php
// 记录SQL注入尝试
if (preg_match('/union|select|insert|update|delete/i', $input)) {
    SecurityLogger::logSqlInjectionAttempt($input, 'search_query');
}

// 记录XSS尝试
if (preg_match('/<script|javascript:|onerror=/i', $input)) {
    SecurityLogger::logXssAttempt($input, 'comment');
}

// 记录路径遍历尝试
if (preg_match('/\.\.\/|\.\.\\\\/', $path)) {
    SecurityLogger::logPathTraversalAttempt($path, 'file_path');
}
?>
```

#### 数据操作日志

```php
<?php
// 记录密码修改
SecurityLogger::logPasswordChange($username, $userId);

// 记录账户锁定
SecurityLogger::logAccountLocked($username, 'Too many failed login attempts');

// 记录权限提升
SecurityLogger::logPrivilegeEscalation($username, 'user', 'admin', $adminUsername);

// 记录数据导出
SecurityLogger::logDataExport('user_data', 1000, $username);

// 记录批量删除
SecurityLogger::logBulkDelete('x_user', 50, $username);
?>
```

#### 查看日志

```php
<?php
// 获取最近100条日志
$logs = SecurityLogger::getRecentLogs(100);

// 获取最近50条WARNING级别日志
$warnings = SecurityLogger::getRecentLogs(50, 'WARNING');

// 显示日志
foreach ($logs as $log) {
    echo $log['timestamp'] . ' - ' . $log['event'] . ': ' . $log['message'];
    echo ' (IP: ' . $log['ip'] . ')';
}
?>
```

#### 日志级别

| 级别 | 说明 | 使用场景 |
|------|------|---------|
| `INFO` | 信息 | 正常操作 (登录、登出、文件上传) |
| `WARNING` | 警告 | 可疑活动 (登录失败、权限拒绝) |
| `ERROR` | 错误 | 系统错误 |
| `CRITICAL` | 严重 | 安全攻击 (SQL注入、XSS、路径遍历) |

---

## SQL 注入防护

所有数据库查询已使用 **MySQLi Prepared Statements** 进行参数化。

### 正确示例

```php
<?php
// ✅ 正确：使用 prepared statement
$stmt = $msql->mysqli->prepare("SELECT * FROM x_user WHERE username = ? AND userpass = ?");
$stmt->bind_param("ss", $username, $password);
$stmt->execute();
$result = $stmt->get_result();
$user = $result->fetch_assoc();
$stmt->close();
?>
```

### 错误示例

```php
<?php
// ❌ 错误：直接拼接SQL（存在SQL注入风险）
$sql = "SELECT * FROM x_user WHERE username = '$username' AND userpass = '$password'";
$result = $msql->query($sql);
?>
```

### 批量操作

```php
<?php
require_once('../global/sql_helper.php');

// 安全的批量删除
$affectedRows = batchDelete($msql->mysqli, 'x_user', $userIdList, 'userid', [99999999]);

// 安全的批量查询
$ids = parseUserIds($idList); // "1,2,3" => [1, 2, 3]
$inClause = prepareInClause($ids);

$stmt = $msql->mysqli->prepare("SELECT * FROM x_user WHERE userid IN ({$inClause['placeholders']})");
$stmt->bind_param($inClause['types'], ...$inClause['values']);
$stmt->execute();
?>
```

---

## XSS 防护

所有用户输入在输出到 HTML 时必须使用 `htmlspecialchars()` 转义。

### HTML 上下文

```php
<!-- ✅ 正确 -->
<input type="text" value="<?php echo htmlspecialchars($username, ENT_QUOTES, 'UTF-8'); ?>">
<p><?php echo htmlspecialchars($message, ENT_QUOTES, 'UTF-8'); ?></p>

<!-- ❌ 错误 -->
<input type="text" value="<?php echo $username; ?>">
<p><?php echo $message; ?></p>
```

### JavaScript 上下文

```php
<!-- ✅ 正确 -->
<script>
const username = <?php echo json_encode($username, JSON_HEX_TAG | JSON_HEX_AMP); ?>;
</script>

<!-- ❌ 错误 -->
<script>
const username = '<?php echo $username; ?>';
</script>
```

### URL 上下文

```php
<!-- ✅ 正确 -->
<a href="profile.php?id=<?php echo urlencode($userId); ?>">查看</a>

<!-- ❌ 错误 -->
<a href="profile.php?id=<?php echo $userId; ?>">查看</a>
```

---

## 文件上传安全

### 验证文件名

```php
<?php
require_once('../global/input_validator.php');

$filename = $_FILES['upload']['name'];
$safeFilename = InputValidator::validateFilename($filename);

if (!$safeFilename) {
    SecurityLogger::logSuspiciousFileUpload($filename, 'Invalid filename');
    die('非法文件名');
}

// 继续处理文件...
?>
```

### 验证文件类型

```php
<?php
$allowedTypes = ['image/jpeg', 'image/png', 'image/gif'];
$fileType = $_FILES['upload']['type'];

if (!in_array($fileType, $allowedTypes)) {
    SecurityLogger::logSuspiciousFileUpload($filename, 'Invalid file type: ' . $fileType);
    die('不支持的文件类型');
}
?>
```

### 验证文件大小

```php
<?php
$maxSize = 5 * 1024 * 1024; // 5MB
$fileSize = $_FILES['upload']['size'];

if ($fileSize > $maxSize) {
    die('文件大小超过限制');
}

// 记录文件上传
SecurityLogger::logFileUpload($safeFilename, $fileSize, $username);
?>
```

---

## 会话安全

### 安全的会话 ID 生成

```php
<?php
// ✅ 正确：使用加密安全的随机数
$sessionId = bin2hex(random_bytes(32));

// ❌ 错误：使用时间戳（可预测）
$sessionId = (getmicrotime() * 100000000) . time();
?>
```

### 会话配置

```php
<?php
// 启动会话时设置安全选项
session_start([
    'cookie_httponly' => true,  // 防止 JavaScript 访问 cookie
    'cookie_secure' => true,    // 仅通过 HTTPS 传输 (生产环境)
    'cookie_samesite' => 'Lax'  // 防止 CSRF 攻击
]);
?>
```

---

## 密码安全

### 密码哈希

```php
<?php
// ✅ 正确：使用 password_hash
$hashedPassword = password_hash($password, PASSWORD_DEFAULT);

// 验证密码
if (password_verify($inputPassword, $hashedPassword)) {
    // 密码正确
}

// ⚠️ 当前系统使用：md5($password . $config['upass'])
// 建议未来迁移到 password_hash
?>
```

---

## 安全检查清单

### 代码审查清单

- [ ] 所有 SQL 查询使用 prepared statements
- [ ] 所有用户输入经过验证和清理
- [ ] 所有 HTML 输出使用 htmlspecialchars()
- [ ] 所有表单包含 CSRF token
- [ ] 文件上传进行类型和大小验证
- [ ] 敏感操作记录安全日志
- [ ] 不存在硬编码的密码或密钥
- [ ] 错误信息不泄露敏感信息
- [ ] 会话 ID 使用加密安全的随机数
- [ ] 权限检查在每个敏感操作前执行

### 部署前检查

- [ ] 删除或禁用所有调试代码
- [ ] 关闭错误显示 (display_errors = Off)
- [ ] 启用错误日志 (log_errors = On)
- [ ] 设置合适的文件权限 (644 for files, 755 for dirs)
- [ ] 配置 HTTPS (生产环境)
- [ ] 定期备份数据库和代码
- [ ] 设置日志轮转
- [ ] 配置防火墙规则

---

## 监控和维护

### 日志监控

定期检查安全日志:

```bash
# 查看最近的警告和严重事件
tail -n 100 web/logs/security/security.log | grep -E "WARNING|CRITICAL"

# 统计登录失败次数
grep "LOGIN_FAILURE" web/logs/security/security.log | wc -l

# 查找SQL注入尝试
grep "SQL_INJECTION_ATTEMPT" web/logs/security/security.log
```

### 定期安全审计

建议每季度进行一次安全审计:

1. 代码审查
2. 依赖更新检查
3. 漏洞扫描
4. 渗透测试
5. 日志分析

---

## 联系方式

如发现安全问题，请立即联系开发团队。

**创建日期**: 2026-02-03
**最后更新**: 2026-02-03
**版本**: 1.0
