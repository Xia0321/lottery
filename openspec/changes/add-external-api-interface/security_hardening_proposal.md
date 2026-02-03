# 彩票系统安全加固提案

## 背景

根据代码审计报告，现有彩票系统存在多个严重安全漏洞，包括：
- 1 个 WebShell 后门文件
- 15+ 处 SQL 注入漏洞
- 4 处任意文件读取漏洞
- 4 处 XSS 跨站脚本漏洞
- 1 个硬编码后门账号
- 使用已弃用的 `mysql_*` 函数

这些漏洞已经构成**严重安全威胁**，必须立即修复，以防止：
- 数据库被篡改或窃取
- 服务器被植入恶意代码
- 用户信息泄露
- 系统被完全接管

---

## 修复优先级

### 🔴 P0 - 严重威胁（立即修复）

#### 1. WebShell 后门移除
**文件**: `web/class/plugins/function.accign_debug_infb.php`
**威胁**: 攻击者可以通过 POST 请求执行任意 PHP 代码
**影响**: 完全控制服务器
**修复方案**: 直接删除该文件
**工作量**: 5 分钟

#### 2. 硬编码后门账号移除
**文件**: `web/hide/login.php` (第 41-63 行)
**威胁**: 用户名 `mankk_sg` + 基于时间的密码算法可被利用
**影响**: 绕过认证进入管理后台
**修复方案**: 删除相关代码
**工作量**: 10 分钟

#### 3. SQL 注入 - 登录页面
**文件**:
- `web/agent/login.php` (第 40 行)
- `web/man/login.php` (第 18 行)
- `web/mxj/login.php` (第 51 行)
- `web/uxj/login.php` (第 41 行)

**威胁**: 攻击者可以绕过登录验证或获取敏感数据
**影响**: 高 - 直接影响认证安全
**修复方案**: 使用 PDO prepared statements
**工作量**: 2 小时

---

### 🟠 P1 - 高危漏洞（本周内修复）

#### 4. SQL 注入 - 管理后台
**文件**:
- `web/hide/user.php` (多处)
- `web/hide/message.php` (第 38, 46, 55 行)
- `web/hide/err.php` (第 149 行)

**威胁**: 管理员账号被利用后可以篡改/删除数据
**影响**: 中高 - 数据完整性受威胁
**修复方案**: 全部使用参数化查询
**工作量**: 4 小时

#### 5. 任意文件读取
**文件**:
- `web/agent/myscript.php` (第 8 行)
- `web/hide/myscript.php` (第 10 行)
- `web/uxj/myscript.php` (第 8 行)
- `web/mxj/myscript.php` (第 8 行)

**威胁**: 攻击者可以读取服务器上的任意文件（如 `/etc/passwd`, `config.inc.php`）
**影响**: 高 - 敏感信息泄露
**修复方案**: 使用白名单验证文件名
**工作量**: 2 小时

#### 6. XSS 跨站脚本
**文件**:
- `web/uxj/reg.php` (多处)
- `web/mxj/reg.php` (多处)
- `web/api/embed/game.php` (第 134 行)

**威胁**: 攻击者可以窃取用户会话或执行恶意操作
**影响**: 中高 - 用户账号安全
**修复方案**: 使用 `htmlspecialchars()` 转义输出
**工作量**: 2 小时

---

### 🟡 P2 - 中危漏洞（2 周内修复）

#### 7. SQL 注入 - 其他业务逻辑
**文件**:
- `web/tools/autokjs.php` (多处)
- `web/tools/api123.php` (多处)

**修复方案**: 统一使用 PDO
**工作量**: 6 小时

#### 8. 使用已弃用的 mysql_* 函数
**文件**:
- `web/global/mysql.inc.php`
- `web/global/db_mysql.class.php`

**威胁**: PHP 7.0+ 不支持，存在安全隐患
**修复方案**: 迁移到 MySQLi 或 PDO
**工作量**: 8 小时

#### 9. 不安全的会话 ID 生成
**文件**: `web/agent/login.php` (第 76 行)

**修复方案**: 使用 `random_bytes()` 生成加密安全的随机数
**工作量**: 1 小时

#### 10. 调试代码移除
**文件**: `web/uxj/login.php` (第 21 行 - 写入 `pwd.txt`)

**修复方案**: 删除调试代码
**工作量**: 30 分钟

---

### 🟢 P3 - 低危/增强（1 个月内）

#### 11. CSRF 保护
**影响**: 所有 POST 表单
**修复方案**: 实现 CSRF Token 验证
**工作量**: 4 小时

#### 12. 输入验证增强
**修复方案**: 统一输入验证和清理函数
**工作量**: 4 小时

#### 13. 安全日志记录
**修复方案**: 记录所有认证失败、权限拒绝等安全事件
**工作量**: 3 小时

---

## 修复计划

### 第 1 天（4 小时）
- [ ] **删除 WebShell 后门文件** (P0-1)
- [ ] **删除硬编码后门账号** (P0-2)
- [ ] **修复 4 个登录页面的 SQL 注入** (P0-3)
- [ ] **测试登录功能是否正常**

### 第 2-3 天（8 小时）
- [ ] **修复管理后台 SQL 注入** (P1-4)
- [ ] **修复任意文件读取漏洞** (P1-5)
- [ ] **修复 XSS 漏洞** (P1-6)
- [ ] **测试管理后台功能**

### 第 4-5 天（8 小时）
- [ ] **修复其他 SQL 注入** (P2-7)
- [ ] **迁移 mysql_* 函数到 PDO** (P2-8)
- [ ] **修复会话 ID 生成** (P2-9)
- [ ] **删除调试代码** (P2-10)

### 第 6-7 天（8 小时）
- [ ] **实现 CSRF 保护** (P3-11)
- [ ] **输入验证增强** (P3-12)
- [ ] **安全日志记录** (P3-13)
- [ ] **全面回归测试**

**总工作量**: 约 28 小时（3.5 个工作日）

---

## 修复方法

### 1. SQL 注入修复示例

#### ❌ 修复前（不安全）
```php
$user = $_POST['username'];
$pass = $_POST['password'];
$sql = "SELECT * FROM x_user WHERE username='$user' AND userpass='$pass'";
$result = mysqli_query($conn, $sql);
```

#### ✅ 修复后（安全）
```php
$user = $_POST['username'];
$pass = $_POST['password'];

$stmt = $pdo->prepare("SELECT * FROM x_user WHERE username = ? AND userpass = ?");
$stmt->execute([$user, $pass]);
$result = $stmt->fetch();
```

---

### 2. XSS 修复示例

#### ❌ 修复前（不安全）
```html
<input type="text" value="<?php echo $_POST['username']; ?>" />
```

#### ✅ 修复后（安全）
```html
<input type="text" value="<?php echo htmlspecialchars($_POST['username'], ENT_QUOTES, 'UTF-8'); ?>" />
```

---

### 3. 任意文件读取修复示例

#### ❌ 修复前（不安全）
```php
$file = $_POST['sf'];
echo file_get_contents("../js/" . $file . ".js");
```

#### ✅ 修复后（安全）
```php
$file = $_POST['sf'];

// 白名单验证
$allowedFiles = ['common', 'admin', 'user', 'agent'];
if (!in_array($file, $allowedFiles)) {
    die('Invalid file');
}

echo file_get_contents("../js/" . $file . ".js");
```

---

### 4. CSRF 保护实现

#### 生成 Token
```php
session_start();
if (empty($_SESSION['csrf_token'])) {
    $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
}
```

#### 表单中添加 Token
```html
<form method="POST">
    <input type="hidden" name="csrf_token" value="<?php echo $_SESSION['csrf_token']; ?>">
    <!-- 其他表单字段 -->
</form>
```

#### 验证 Token
```php
if ($_POST['csrf_token'] !== $_SESSION['csrf_token']) {
    die('CSRF token validation failed');
}
```

---

## 工具和脚本

为了提高修复效率，我将创建以下工具：

### 1. 安全扫描脚本
**文件**: `tools/security_scanner.php`
**功能**: 自动扫描代码中的常见安全问题
**输出**: 带行号的问题报告

### 2. SQL 注入自动修复工具
**文件**: `tools/sql_injection_fixer.php`
**功能**: 自动将简单的 SQL 拼接转换为参数化查询
**注意**: 复杂情况仍需手动修复

### 3. 数据库迁移脚本
**文件**: `migrations/003_add_security_fields.sql`
**功能**: 添加安全相关的数据库字段（如 CSRF tokens, 登录日志等）

### 4. 安全测试脚本
**文件**: `tests/security_test.php`
**功能**: 自动化安全测试（SQL 注入、XSS、认证绕过等）

---

## 验证和测试

### 自动化测试
- [ ] SQL 注入测试（使用 sqlmap 或自定义脚本）
- [ ] XSS 测试
- [ ] 认证绕过测试
- [ ] 文件读取测试

### 手动测试
- [ ] 登录功能（用户、代理、管理员）
- [ ] 用户注册
- [ ] 投注功能
- [ ] 充值提现
- [ ] 管理后台操作

### 性能测试
- [ ] 参数化查询对性能的影响（预期：轻微提升）
- [ ] PDO vs mysqli 性能对比

---

## 回滚计划

### 备份策略
1. **代码备份**: 修复前完整备份 `web/` 目录
2. **数据库备份**: 导出完整数据库（包括结构和数据）
3. **Git 提交**: 每个修复项独立提交，便于回滚

### 回滚步骤
```bash
# 代码回滚
cp -r web_backup/ web/

# 数据库回滚
mysql -u root -p lottery < backup.sql
```

---

## 风险评估

### 修复风险
| 修复项 | 功能影响风险 | 缓解措施 |
|--------|------------|---------|
| SQL 注入修复 | 低 | 充分测试所有查询场景 |
| mysql_* 迁移 | 中 | 分阶段迁移，保留旧代码作为备份 |
| XSS 修复 | 低 | 仅影响显示，不改变逻辑 |
| CSRF 保护 | 中 | 可能影响 AJAX 请求，需统一处理 |

### 不修复的风险
- **数据泄露**: 客户信息、交易记录被窃取
- **资金损失**: 数据库被篡改，余额被修改
- **法律责任**: 未尽到安全保护义务
- **声誉损害**: 系统被攻击后的负面影响

---

## 成本估算

### 人力成本
- **开发人员**: 1 人 × 3.5 天 = 28 小时
- **测试人员**: 0.5 人 × 2 天 = 8 小时
- **总计**: 36 工时

### 时间成本
- **开发阶段**: 1 周
- **测试阶段**: 2 天
- **总计**: 9 个工作日

### 停机时间
- **维护窗口**: 建议在低峰时段（凌晨 2:00-6:00）
- **预计停机**: 每次 30 分钟（分 3 次部署）

---

## 后续维护

### 持续安全监控
1. **定期代码审计**: 每季度一次
2. **依赖更新**: 及时更新 PHP 版本和第三方库
3. **日志分析**: 每周分析安全日志
4. **渗透测试**: 每年一次外部渗透测试

### 安全开发规范
1. **代码审查**: 所有代码必须经过安全审查
2. **安全培训**: 开发团队定期安全培训
3. **安全检查清单**: 发布前必须通过安全检查清单

---

## 结论

根据审计报告，现有系统存在**严重安全隐患**，必须立即采取行动：

1. **第一阶段（1 天）**: 删除后门、修复登录 SQL 注入
2. **第二阶段（2-3 天）**: 修复高危漏洞（管理后台、文件读取、XSS）
3. **第三阶段（4-7 天）**: 修复中低危漏洞，实现增强安全功能

**总工作量**: 约 36 工时（1 周）
**建议开始时间**: 立即
**预计完成时间**: 2026-02-10

修复完成后，系统安全性将得到**显著提升**，可以支撑外部 API 接口的安全运行。

---

## 批准签字

**提案人**: Claude AI
**日期**: 2026-02-03
**状态**: 待审批

---

## 附录

### 附录 A: 完整漏洞清单
见 `security_audit_report.md`

### 附录 B: 修复代码示例
见 `security_fix_examples.md`

### 附录 C: 测试用例
见 `security_test_cases.md`
