# 安全功能集成指南

本指南说明如何将新的安全功能集成到现有代码中。

## 快速开始

### 1. 在登录页面添加安全日志

#### 示例：修改 web/uxj/login.php

**在文件开头添加:**
```php
require_once('../global/security_logger.php');
```

**在登录失败时记录日志:**
```php
// 找到验证失败的地方，添加日志
if ($code != $_SESSION['login_check_number']) {
    SecurityLogger::logLoginFailure($user, 'Invalid verification code');
    echo outjs("验证码错误，请重新输入。");
    exit;
}

// 在密码错误时
if ($userData["userpass"] != $pass) {
    SecurityLogger::logLoginFailure($user, 'Invalid password');
    echo outjs("账号或密码错误。");
    exit;
}
```

**在登录成功时记录日志:**
```php
// 在设置 session 后添加
$_SESSION['userid'] = $userData['userid'];
$_SESSION['username'] = $userData['username'];

SecurityLogger::logLoginSuccess($userData['username'], $userData['userid']);
```

**在登出时记录日志 (logout.php):**
```php
require_once('../global/security_logger.php');

$username = $_SESSION['username'] ?? 'unknown';
$userId = $_SESSION['userid'] ?? 0;

SecurityLogger::logLogout($username, $userId);

session_destroy();
```

---

### 2. 在注册表单添加 CSRF 保护

#### 示例：修改 web/uxj/reg.php

**在表单 HTML 部分添加:**
```php
<?php require_once('../global/csrf_helper.php'); ?>

<form method="post" onSubmit="return checkform();">
    <?php csrfTokenField(); ?>
    <input type="hidden" name='tj' value="1" />
    <!-- 其他表单字段 -->
</form>
```

**在处理注册的 PHP 代码开头添加:**
```php
require_once('../global/csrf_helper.php');

if ($_POST['tj'] == '1') {
    // 验证 CSRF token
    validateCsrfToken('POST');

    // 继续处理注册...
}
```

---

### 3. 使用输入验证增强现有代码

#### 示例：修改用户输入处理

**旧代码:**
```php
$userId = intval($_POST['user_id']);
$username = trim($_POST['username']);
$amount = floatval($_POST['amount']);
```

**新代码 (更安全):**
```php
require_once('../global/input_validator.php');

$userId = InputValidator::getInput('user_id', 'int', 'POST', 0, ['min' => 1]);
$username = InputValidator::getInput('username', 'username', 'POST');
$amount = InputValidator::getInput('amount', 'float', 'POST', 0, ['min' => 0.01, 'max' => 100000]);
```

---

### 4. 监控可疑活动

#### 在敏感操作前添加安全检查

**示例：批量删除操作**
```php
require_once('../global/security_logger.php');

// 在批量删除前检查并记录
if ($recordCount > 100) {
    SecurityLogger::log('WARNING', 'LARGE_BULK_DELETE',
        "User attempting to delete $recordCount records",
        ['username' => $username, 'table' => 'x_user']
    );
}

// 执行删除
$affected = batchDelete($msql->mysqli, 'x_user', $idList, 'userid', [99999999]);

// 记录删除操作
SecurityLogger::logBulkDelete('x_user', $affected, $username);
```

**示例：权限检查失败**
```php
require_once('../global/security_logger.php');

if ($_SESSION['admin_level'] < 5) {
    SecurityLogger::logAccessDenied('/admin/config', 'UPDATE', $_SESSION['username']);
    die(json_encode(['error' => '权限不足']));
}
```

---

## 逐步集成计划

### 第1阶段：日志记录 (1-2小时)

优先级：高
风险：低

**任务:**
1. 在所有登录页面添加安全日志
   - [ ] web/uxj/login.php
   - [ ] web/mxj/login.php
   - [ ] web/agent/login.php
   - [ ] web/man/login.php
   - [ ] web/hide/login.php

2. 在登出页面添加日志
   - [ ] web/uxj/logout.php
   - [ ] web/mxj/logout.php
   - [ ] web/agent/logout.php
   - [ ] web/hide/logout.php

**验证:**
```bash
# 测试登录失败
# 检查日志文件
tail web/logs/security/security.log
```

---

### 第2阶段：关键表单 CSRF 保护 (2-3小时)

优先级：高
风险：中

**任务:**
1. 注册表单
   - [ ] web/uxj/reg.php
   - [ ] web/mxj/reg.php

2. 密码修改表单
   - [ ] web/uxj/password.php
   - [ ] web/mxj/password.php

3. 充值提现表单
   - [ ] web/uxj/deposit.php
   - [ ] web/uxj/withdraw.php

**验证:**
```bash
# 尝试不带 CSRF token 提交表单
# 应该返回 403 错误
curl -X POST http://localhost/uxj/reg.php -d "username=test&password=test"
```

---

### 第3阶段：管理后台 CSRF 保护 (3-4小时)

优先级：中
风险：中

**任务:**
1. 用户管理
   - [ ] web/hide/user.php
   - [ ] web/agent/user.php

2. 配置管理
   - [ ] web/hide/config.php

3. 其他敏感操作

**验证:**
- 手动测试每个表单
- 确保正常功能不受影响

---

### 第4阶段：输入验证替换 (4-6小时)

优先级：中
风险：低

**任务:**
1. 识别所有直接使用 `$_POST`/`$_GET` 的地方
2. 逐步替换为 `InputValidator::getInput()`
3. 重点关注：
   - 用户注册/登录
   - 投注金额
   - 用户ID查询
   - 日期时间输入

**脚本辅助查找:**
```bash
# 查找直接使用 $_POST 的地方
grep -r "\$_POST\[" web/ --include="*.php" | grep -v "csrf_token"
```

---

### 第5阶段：全面测试 (2-3小时)

**测试清单:**
- [ ] 用户注册流程
- [ ] 用户登录/登出
- [ ] 密码修改
- [ ] 充值提现
- [ ] 投注功能
- [ ] 管理后台所有功能
- [ ] 安全日志正常记录
- [ ] CSRF 保护正常工作

---

## 注意事项

### ⚠️ 兼容性

1. **不要破坏现有功能**
   - 集成安全功能时要确保现有功能继续工作
   - 先在测试环境测试

2. **CSRF Token 对 AJAX 的影响**
   - AJAX 请求需要在请求中包含 CSRF token
   - 参考 SECURITY.md 中的 AJAX 示例

3. **输入验证可能影响的功能**
   - 某些特殊字符可能被过滤
   - 需要根据业务需求调整验证规则

### 🔍 测试建议

1. **先在开发环境测试**
   - 不要直接在生产环境集成

2. **增量部署**
   - 一次集成一个模块
   - 验证无问题后再继续

3. **准备回滚方案**
   - 使用 Git 管理代码
   - 每次修改都提交一个 commit
   - 如有问题可快速回滚

### 📊 监控

集成完成后，定期检查:

```bash
# 查看安全日志
tail -f web/logs/security/security.log

# 统计登录失败次数（可能的暴力破解）
grep "LOGIN_FAILURE" web/logs/security/security.log | wc -l

# 查看最近1小时的警告
grep "WARNING" web/logs/security/security.log | tail -n 50
```

---

## 示例：完整集成一个登录页面

### web/uxj/login.php 集成示例

```php
<?php
// 1. 引入安全相关的库
require_once('../data/comm.inc.php');
require_once('../data/uservar.php');
require_once('../func/func.php');
require_once('../func/userfunc.php');
require_once('../include.php');
require_once('../global/security_logger.php');  // 新增
require_once('../global/input_validator.php');  // 新增

$act = $_REQUEST['act'];

if ($act == 'login') {
    session_start();
    $config['ifyzm'] = 1; // 强制验证码
    if ($config['ifyzm'] == 1) {
        $sv = rserver();
        $_SESSION['sv'] = $sv;
        $os = getbrowser($_SERVER['HTTP_USER_AGENT']) . '  ' . getos($_SERVER['HTTP_USER_AGENT']);

        // 2. 使用 InputValidator 验证输入
        $user = InputValidator::getInput('username', 'username', 'POST');
        if (!$user) {
            SecurityLogger::logLoginFailure('', 'Invalid username format');
            echo outjs("用户名格式错误");
            exit;
        }

        $pass = md5($_POST['pass'] . $config['upass']);
        $code = InputValidator::getInput('code', 'string', 'POST');

        // 3. 验证码检查 + 日志
        if ($code != $_SESSION['login_check_number']) {
            SecurityLogger::logLoginFailure($user, 'Invalid verification code');
            echo outjs("验证码错误，请重新输入。");
            echo openurl('/uxj/login.php');
            exit;
        }

        // 4. 数据库查询（已使用 prepared statement）
        $stmt = $msql->mysqli->prepare("SELECT * FROM `$tb_user` WHERE username = ? AND userpass = ? AND ifagent = 0");
        $stmt->bind_param("ss", $user, $pass);
        $stmt->execute();
        $result = $stmt->get_result();
        $userData = $result->fetch_assoc();
        $stmt->close();

        if (!$userData) {
            // 5. 登录失败日志
            SecurityLogger::logLoginFailure($user, 'Invalid credentials');
            echo outjs("账号或密码错误。");
            exit;
        }

        // 检查账号状态
        if ($userData['status'] != 0) {
            SecurityLogger::logLoginFailure($user, 'Account locked');
            echo outjs("此账号已被锁定，请联系上级代理。");
            exit;
        }

        // 6. 登录成功
        $passcode = bin2hex(random_bytes(32));  // 已修复
        $_SESSION['passcode'] = $passcode;
        $_SESSION['userid'] = $userData['userid'];
        $_SESSION['username'] = $userData['username'];
        $_SESSION['userstatus'] = $userData['status'];

        // 7. 登录成功日志
        SecurityLogger::logLoginSuccess($userData['username'], $userData['userid']);

        // 更新登录信息
        $stmt = $msql->mysqli->prepare("UPDATE `$tb_user` SET lltime = NOW(), llip = ?, llsystem = ? WHERE userid = ?");
        $stmt->bind_param("ssi", $sv, $os, $userData['userid']);
        $stmt->execute();
        $stmt->close();

        echo outjs("登录成功");
        echo openurl('/uxj/index.php');
    }
}
?>
```

---

## 常见问题

### Q: CSRF Token 验证失败怎么办？

A: 检查以下几点：
1. 表单中是否包含 `<?php csrfTokenField(); ?>`
2. Session 是否正常启动
3. Token 是否在表单提交时被传递

### Q: InputValidator 过滤掉了合法输入怎么办？

A: 根据业务需求调整验证规则：
```php
// 例如：允许更长的用户名
$username = InputValidator::getInput('username', 'username', 'POST', '', [
    'minLength' => 3,
    'maxLength' => 30  // 从20改为30
]);
```

### Q: 安全日志文件太大怎么办？

A: SecurityLogger 会自动轮转（超过10MB），旧日志会被重命名为带时间戳的文件。定期清理旧日志文件即可。

---

## 获取帮助

遇到问题时：
1. 查阅 SECURITY.md 文档
2. 检查安全日志文件
3. 联系开发团队

**最后更新**: 2026-02-03
