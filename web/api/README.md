# 彩票系统 API 接口文档

## 部署指南

### 1. 环境要求

- PHP 7.4+
- MySQL 5.7+ / MariaDB 10.3+
- Redis 5.0+
- HTTPS 证书（必须）
- PHP 扩展：PDO、Redis、cURL、JSON

### 2. 数据库迁移

执行数据库迁移脚本创建必要的表：

```bash
# 创建 API 相关表
mysql -u root -p your_database < web/migrations/001_create_api_tables.sql

# 创建游戏 Token 表
mysql -u root -p your_database < web/migrations/002_create_game_tokens_table.sql
```

### 3. 配置 Redis

确保 Redis 服务正在运行：

```bash
# 启动 Redis
redis-server

# 测试连接
redis-cli ping
```

如果 Redis 需要密码认证，在 `web/api/db_config.php` 中取消注释并配置：

```php
$redis->auth('your_redis_password');
```

### 4. 配置 HTTPS

**重要：** API 强制使用 HTTPS，请确保已配置 SSL 证书。

Nginx 配置示例：

```nginx
server {
    listen 443 ssl http2;
    server_name your-domain.com;

    ssl_certificate /path/to/cert.pem;
    ssl_certificate_key /path/to/key.pem;

    root /path/to/lottery/web;
    index index.php;

    location ~ \.php$ {
        fastcgi_pass unix:/var/run/php/php7.4-fpm.sock;
        fastcgi_index index.php;
        include fastcgi_params;
        fastcgi_param SCRIPT_FILENAME $document_root$fastcgi_script_name;
    }

    location /api/ {
        try_files $uri $uri/ /index.php?$query_string;
    }
}
```

### 5. 生成 API Key

使用管理脚本生成 API Key：

```php
<?php
require_once 'web/api/db_config.php';

$apiAuth = new ApiAuth($pdo, $redis);

// 生成 API Key
// 参数：name（外部系统名称）、agentId（代理ID，null表示无限制）、rateLimit（速率限制）
$credentials = $apiAuth->generateApiKey('外部平台A', 1001, 100);

echo "API Key: " . $credentials['api_key'] . "\n";
echo "API Secret: " . $credentials['api_secret'] . "\n";
echo "注意：API Secret 只显示一次，请妥善保管！\n";
```

### 6. 测试 API

使用 cURL 测试 API 连接：

```bash
# 生成签名
timestamp=$(date +%s)
api_key="your_api_key"
api_secret="your_api_secret"
params="api_key=${api_key}&timestamp=${timestamp}"
sign=$(echo -n "$params" | openssl dgst -sha256 -hmac "$api_secret" | awk '{print $2}')

# 测试查询用户
curl -X GET "https://your-domain.com/api/v1/users/1?api_key=${api_key}&timestamp=${timestamp}&sign=${sign}"
```

---

## API 接口列表

### 认证机制

所有 API 请求必须包含以下参数：

- `api_key`: API 密钥
- `timestamp`: 当前时间戳（Unix 时间戳，5 分钟内有效）
- `sign`: 签名（HMAC-SHA256）

**签名生成规则：**

1. 将所有请求参数（除 `sign`）按键名升序排序
2. 使用 `http_build_query()` 生成查询字符串
3. 使用 API Secret 进行 HMAC-SHA256 签名

```php
// PHP 签名示例
$params = [
    'api_key' => 'your_api_key',
    'timestamp' => time(),
    'user_id' => 123
];

ksort($params);
$signString = http_build_query($params);
$sign = hash_hmac('sha256', $signString, $apiSecret);

$params['sign'] = $sign;
```

### 用户查询 API

#### 1. 查询用户信息

```
GET /api/v1/users/{id}
```

**响应示例：**

```json
{
  "success": true,
  "data": {
    "user_id": 123,
    "username": "user001",
    "max_money": 10000.00,
    "balance": 5000.50,
    "status": 1,
    "is_agent": false,
    "layer": 1,
    "created_at": "2024-01-01 10:00:00"
  },
  "meta": {
    "request_id": "req_123456",
    "timestamp": 1640000000
  }
}
```

#### 2. 查询用户余额

```
GET /api/v1/users/{id}/balance
```

**响应示例：**

```json
{
  "success": true,
  "data": {
    "user_id": 123,
    "username": "user001",
    "balance": 5000.50,
    "credit_limit": 10000.00
  }
}
```

### 投注记录 API

#### 3. 查询投注列表

```
GET /api/v1/bets?user_id=123&start_time=2024-01-01&page=1&page_size=20
```

**查询参数：**

- `user_id`: 用户 ID（可选）
- `start_time`: 开始时间（可选，格式：YYYY-MM-DD HH:MM:SS）
- `end_time`: 结束时间（可选）
- `status`: 状态筛选（可选，0=未开奖，1=已中奖，2=未中奖）
- `page`: 页码（默认 1）
- `page_size`: 每页大小（默认 20，最大 100）

**响应示例：**

```json
{
  "success": true,
  "data": [
    {
      "bet_id": 1001,
      "user_id": 123,
      "username": "user001",
      "period": "20240101001",
      "bet_amount": 100.00,
      "win_amount": 0.00,
      "status": 0,
      "status_text": "未开奖",
      "created_at": "2024-01-01 10:00:00",
      "updated_at": "2024-01-01 10:00:00"
    }
  ],
  "pagination": {
    "total": 100,
    "page": 1,
    "page_size": 20,
    "total_pages": 5
  }
}
```

#### 4. 查询单条投注记录

```
GET /api/v1/bets/{id}
```

### 交易记录 API

#### 5. 查询交易列表

```
GET /api/v1/transactions?user_id=123&type=deposit&page=1
```

**查询参数：**

- `user_id`: 用户 ID（可选）
- `start_time`: 开始时间（可选）
- `end_time`: 结束时间（可选）
- `type`: 交易类型（可选：deposit, withdraw, bet, win, rebate, other）
- `page`: 页码
- `page_size`: 每页大小

#### 6. 查询单条交易记录

```
GET /api/v1/transactions/{id}
```

### 开奖结果 API

#### 7. 查询开奖结果列表

```
GET /api/v1/lottery_results?start_time=2024-01-01&page=1
```

#### 8. 查询指定期号的开奖结果

```
GET /api/v1/lottery_results/{period}
```

#### 9. 查询最新开奖结果

```
GET /api/v1/lottery_results/latest
```

### Webhook 管理 API

#### 10. 创建 Webhook

```
POST /api/v1/webhooks
Content-Type: application/json

{
  "url": "https://your-domain.com/webhook/callback",
  "events": ["bet.created", "bet.won", "deposit.completed"]
}
```

**支持的事件类型：**

- `bet.created`: 投注创建
- `bet.won`: 中奖
- `deposit.completed`: 充值完成
- `withdrawal.requested`: 提现请求

**响应示例：**

```json
{
  "success": true,
  "data": {
    "id": 1,
    "url": "https://your-domain.com/webhook/callback",
    "secret": "wh_secret_abc123...",
    "events": ["bet.created", "bet.won"]
  }
}
```

#### 11. 查询 Webhook 列表

```
GET /api/v1/webhooks
```

#### 12. 删除 Webhook

```
DELETE /api/v1/webhooks/{id}
```

#### 13. 查询 Webhook 调用日志

```
GET /api/v1/webhooks/{id}/logs?page=1
```

### 游戏嵌入 API

#### 14. 生成游戏嵌入 Token

```
POST /api/v1/tokens/game
Content-Type: application/json

{
  "user_id": 123,
  "game_url": "/game/index.php",
  "ttl": 3600
}
```

**参数说明：**

- `user_id`: 用户 ID（必填）
- `game_url`: 游戏页面 URL（可选，默认 `/game/index.php`）
- `ttl`: Token 有效期（秒，默认 3600，范围 60-86400）

**响应示例：**

```json
{
  "success": true,
  "data": {
    "token": "abc123...",
    "embed_url": "https://your-domain.com/api/embed/game?token=abc123...",
    "expires_at": "2024-01-01 11:00:00",
    "ttl": 3600,
    "user": {
      "user_id": 123,
      "username": "user001"
    }
  }
}
```

**使用方式：**

```html
<iframe src="https://your-domain.com/api/embed/game?token=abc123..." width="100%" height="600" frameborder="0"></iframe>
```

---

## Webhook 回调

### Webhook 签名验证

Webhook 请求包含以下请求头：

- `X-Webhook-Signature`: HMAC-SHA256 签名
- `X-Webhook-Timestamp`: 时间戳

**验证签名（PHP 示例）：**

```php
$payload = file_get_contents('php://input');
$signature = $_SERVER['HTTP_X_WEBHOOK_SIGNATURE'];
$secret = 'your_webhook_secret'; // 创建 Webhook 时返回的 secret

$computedSignature = hash_hmac('sha256', $payload, $secret);

if (!hash_equals($computedSignature, $signature)) {
    http_response_code(401);
    exit('Invalid signature');
}

$data = json_decode($payload, true);
// 处理事件...
```

### Webhook 事件格式

```json
{
  "event": "bet.created",
  "data": {
    "bet_id": 1001,
    "user_id": 123,
    "amount": 100.00,
    "period": "20240101001"
  },
  "timestamp": 1640000000
}
```

---

## 错误处理

### 错误响应格式

```json
{
  "success": false,
  "error": {
    "code": "AUTH_INVALID_SIGNATURE",
    "message": "签名验证失败"
  },
  "meta": {
    "request_id": "req_123456",
    "timestamp": 1640000000
  }
}
```

### 常见错误码

| 错误码 | HTTP 状态码 | 说明 |
|--------|-------------|------|
| AUTH_001 | 401 | 缺少 API Key |
| AUTH_002 | 401 | 无效的 API Key |
| AUTH_005 | 401 | 签名验证失败 |
| AUTH_007 | 401 | 请求已过期 |
| AUTH_008 | 429 | 请求频率超限 |
| PARAM_001 | 400 | 缺少必填参数 |
| PARAM_005 | 400 | 参数值无效 |
| BIZ_001 | 404 | 用户不存在 |
| BIZ_002 | 403 | 用户账号已被禁用 |
| SYS_002 | 500 | 数据库错误 |

---

## 速率限制

每个 API Key 默认限制为 **100 次/分钟**。

响应头会包含速率限制信息：

```
X-RateLimit-Limit: 100
X-RateLimit-Remaining: 95
X-RateLimit-Reset: 1640000060
```

当超过限制时，返回 429 错误：

```json
{
  "success": false,
  "error": {
    "code": "AUTH_008",
    "message": "请求频率超限，请稍后再试"
  }
}
```

---

## 安全建议

1. **保护 API Secret**：绝不在客户端代码中暴露 API Secret
2. **使用 HTTPS**：所有 API 请求必须使用 HTTPS
3. **验证签名**：接收 Webhook 时务必验证签名
4. **限制权限**：为不同外部系统分配不同的 API Key
5. **监控日志**：定期检查 `api_logs` 表，监控异常访问
6. **定期轮换**：建议每 3-6 个月轮换一次 API Key

---

## 数据隔离

如果 API Key 关联了 `agent_id`，则只能查询该代理下的用户数据。

这确保了不同代理的数据相互隔离，提高了数据安全性。

---

## 联系支持

如有问题，请联系技术支持团队。
