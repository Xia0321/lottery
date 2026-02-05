# 🚀 彩票系统快速启动指南

## 📋 准备工作

### 1. 环境要求
- ✅ PHP >= 7.4
- ✅ MySQL >= 5.7
- ✅ Nginx 或 Apache
- ⚙️ Redis (可选，用于缓存)

### 2. PHP 扩展
- mysqli
- json
- curl
- openssl
- mbstring

检查扩展：
```bash
php -m | grep -E 'mysqli|json|curl|openssl|mbstring'
```

---

## 🔧 第一步：初始化数据库

### 数据库配置信息
```
主机: 127.0.0.1
端口: 3306
用户: root
密码: 123456
数据库名: lottery_db (将自动创建)
```

### 运行初始化脚本
```bash
cd D:\lottery
php init_database.php
```

**脚本功能**:
1. ✅ 连接数据库服务器
2. ✅ 创建数据库 `lottery_db`
3. ✅ 导入初始数据 (cs1381328.sql)
4. ✅ 验证表结构
5. ✅ 创建测试 API Key

**预期输出**:
```
╔══════════════════════════════════════════════════════════╗
║         彩票系统数据库初始化脚本                         ║
╚══════════════════════════════════════════════════════════╝

[1/5] 连接数据库服务器...
✓ 连接成功

[2/5] 创建数据库 lottery_db...
✓ 数据库已创建或已存在

[3/5] 导入初始数据...
  完成: XXXX 条语句已执行
✓ 数据导入完成

[4/5] 验证表结构...
  ✓ 用户表 (x_user)
  ✓ 充值提现表 (x_money)
  ✓ 交易日志表 (x_money_log)
  ✓ 投注表 (x_lib)
  ✓ 开奖结果表 (x_kj)
  ✓ 游戏表 (x_game)
✓ 所有必要的表都已创建

[5/5] 创建测试 API Key...
✓ API Key 创建成功

═══════════════════════════════════════
  API 凭证信息 (请妥善保存)
═══════════════════════════════════════
API Key:    test_xxxxxxxxxxxxxxxx
API Secret: xxxxxxxxxxxxxxxxxxxxxxxx
═══════════════════════════════════════

✓ 数据库初始化完成！
```

**API 凭证会保存在**: `D:\lottery\api_credentials.txt`

---

## 🌐 第二步：配置 Web 服务器

### 方案 A: 使用 PHP 内置服务器 (快速测试)

```bash
cd D:\lottery\web
php -S localhost:8080
```

访问测试：http://localhost:8080/api/v1/test.php

### 方案 B: 使用 Nginx (推荐生产环境)

**Nginx 配置**:
```nginx
server {
    listen 80;
    server_name localhost;
    root D:/lottery/web;
    index index.php index.html;

    # API 路由
    location /api/ {
        try_files $uri $uri/ =404;
        location ~ \.php$ {
            fastcgi_pass   127.0.0.1:9000;
            fastcgi_index  index.php;
            fastcgi_param  SCRIPT_FILENAME  $document_root$fastcgi_script_name;
            include        fastcgi_params;
        }
    }

    # 静态文件
    location / {
        try_files $uri $uri/ /index.php?$query_string;
    }

    # PHP 处理
    location ~ \.php$ {
        fastcgi_pass   127.0.0.1:9000;
        fastcgi_index  index.php;
        fastcgi_param  SCRIPT_FILENAME  $document_root$fastcgi_script_name;
        include        fastcgi_params;
    }

    # 安全配置
    location ~ /\. {
        deny all;
    }
}
```

重启 Nginx：
```bash
nginx -s reload
```

### 方案 C: 使用 Apache

**Apache 配置** (.htaccess):
```apache
<Directory "D:/lottery/web">
    Options Indexes FollowSymLinks
    AllowOverride All
    Require all granted
</Directory>

<VirtualHost *:80>
    ServerName localhost
    DocumentRoot "D:/lottery/web"

    <Directory "D:/lottery/web">
        AllowOverride All
    </Directory>
</VirtualHost>
```

---

## ✅ 第三步：测试 API 接口

### 1. 测试数据库连接
```bash
cd D:\lottery\tests
php db_consistency_test.php
```

**预期输出**:
```
✓ 数据库连接成功
✓ 所有必要的表都存在
✓ 用户表结构正确
✓ x_money 表结构正确
```

### 2. 测试接口语法
```bash
cd D:\lottery\tests
php syntax_check.php
```

**预期结果**: 成功率 98.11% ✅

### 3. 测试 API 接口 (需要先启动 Web 服务器)

**修改测试配置**:
```bash
# 编辑 D:\lottery\tests\interface_test.php
# 修改以下配置：

define('API_BASE_URL', 'http://localhost:8080/api/v1');  # 或 http://localhost/api/v1
define('TEST_API_KEY', 'test_你的API_Key');
define('TEST_SECRET', '你的API_Secret');
```

**运行测试**:
```bash
cd D:\lottery\tests
php interface_test.php
```

---

## 🎯 第四步：验证功能

### 1. 测试 API 认证

使用 curl 或 Postman 测试：

```bash
# 1. 生成签名
API_KEY="test_你的API_Key"
API_SECRET="你的API_Secret"
TIMESTAMP=$(date +%s)
METHOD="GET"
PATH="/api/v1/test.php"
BODY=""

MESSAGE="$METHOD\n$PATH\n$BODY\n$TIMESTAMP"
SIGNATURE=$(echo -n "$MESSAGE" | openssl dgst -sha256 -hmac "$API_SECRET" | cut -d' ' -f2)

# 2. 发送请求
curl -X GET "http://localhost:8080/api/v1/test.php" \
  -H "X-Api-Key: $API_KEY" \
  -H "X-Timestamp: $TIMESTAMP" \
  -H "X-Signature: $SIGNATURE"
```

**预期响应**:
```json
{
    "success": true,
    "message": "API is working properly",
    "data": {
        "authenticated": true,
        "timestamp": "2026-02-03 10:30:00"
    }
}
```

### 2. 测试用户创建

```bash
# 创建用户
curl -X POST "http://localhost:8080/api/v1/users.php" \
  -H "X-Api-Key: $API_KEY" \
  -H "X-Timestamp: $TIMESTAMP" \
  -H "X-Signature: $SIGNATURE" \
  -H "Content-Type: application/json" \
  -d '{"username":"testuser123","password":"123456","email":"test@example.com"}'
```

### 3. 测试游戏列表

```bash
curl -X GET "http://localhost:8080/api/v1/games.php" \
  -H "X-Api-Key: $API_KEY" \
  -H "X-Timestamp: $TIMESTAMP" \
  -H "X-Signature: $SIGNATURE"
```

---

## 📊 完整功能清单

### 已实现的 API 接口 (10个)

1. ✅ **Test 接口** - `/api/v1/test.php`
   - 测试 API 连接和认证

2. ✅ **用户管理** - `/api/v1/users.php`
   - POST: 创建用户
   - GET /{id}: 查询用户信息
   - GET /{id}/balance: 查询余额

3. ✅ **游戏管理** - `/api/v1/games.php`
   - GET: 查询游戏列表
   - GET /{id}: 查询游戏详情

4. ✅ **期号管理** - `/api/v1/periods.php`
   - GET /current: 查询当前期号
   - GET: 查询期号列表
   - GET /{period}: 查询指定期号

5. ✅ **投注管理** - `/api/v1/bets.php`
   - POST: 创建投注
   - GET: 查询投注列表
   - GET /{id}: 查询投注详情

6. ✅ **充值管理** - `/api/v1/deposits.php`
   - POST: 创建充值
   - GET: 查询充值列表
   - GET /{id}: 查询充值详情

7. ✅ **提现管理** - `/api/v1/withdrawals.php`
   - POST: 创建提现
   - GET: 查询提现列表
   - GET /{id}: 查询提现详情

8. ✅ **交易记录** - `/api/v1/transactions.php`
   - GET: 查询交易流水

9. ✅ **开奖结果** - `/api/v1/lottery_results.php`
   - GET: 查询开奖列表
   - GET /latest: 查询最新结果
   - GET /{period}: 查询指定期号

10. ✅ **Webhook** - `/api/v1/webhooks.php`
    - POST: 创建 Webhook
    - GET: 查询 Webhook 列表
    - DELETE /{id}: 删除 Webhook

---

## 🔍 故障排查

### 问题1: 数据库连接失败
```
错误: Access denied for user 'root'@'localhost'
```
**解决**:
1. 检查 MySQL 是否运行：`mysql -u root -p`
2. 验证密码是否正确
3. 检查 config.inc.php 配置

### 问题2: API 返回 401
```
{"success": false, "error": {"code": 1001, "message": "Invalid API key"}}
```
**解决**:
1. 检查 API Key 是否正确
2. 检查签名生成是否正确
3. 检查时间戳是否在有效期内

### 问题3: 找不到表
```
Table 'lottery_db.x_user' doesn't exist
```
**解决**:
1. 重新运行 init_database.php
2. 检查 SQL 文件是否完整
3. 手动导入：`mysql -u root -p lottery_db < cs1381328.sql`

### 问题4: PHP 扩展缺失
```
Fatal error: Call to undefined function mysqli_connect()
```
**解决**:
1. 检查 php.ini 中 extension=mysqli 是否启用
2. 重启 PHP-FPM
3. 验证：`php -m | grep mysqli`

---

## 📝 开发建议

### 1. 开发环境配置
```php
// 在 config.inc.php 中
define('DEBUG_MODE', true);
error_reporting(E_ALL);
ini_set('display_errors', 1);
```

### 2. 启用错误日志
```php
// 错误日志文件
error_log("Error message", 3, __DIR__ . "/../logs/error.log");
```

### 3. 性能优化
- 启用 Redis 缓存
- 添加数据库索引
- 使用连接池

---

## 🎉 完成！

如果所有测试都通过，恭喜你！系统已经成功启动。

**下一步**:
1. 📱 集成前端界面
2. 🔒 配置 HTTPS
3. 📊 部署监控系统
4. 🚀 上线生产环境

---

## 📞 需要帮助？

- 查看日志：`D:\lottery\logs\`
- 运行测试：`D:\lottery\tests\`
- 查看文档：`D:\lottery\docs\`

**测试总结报告**: `D:\lottery\tests\TEST_SUMMARY.md`

---

**祝您使用愉快！** 🎊
