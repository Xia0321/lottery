# 彩票系统外部 API 文档

## 目录

1. [概述](#概述)
2. [认证说明](#认证说明)
3. [接口列表](#接口列表)
4. [错误码列表](#错误码列表)
5. [Webhook 配置指南](#webhook-配置指南)
6. [游戏窗口嵌入](#游戏窗口嵌入)

---

## 概述

### 基本信息

| 项目 | 说明 |
|------|------|
| Base URL | `https://your-domain.com/api/v1` |
| 协议 | HTTPS（必须） |
| 数据格式 | JSON |
| 字符编码 | UTF-8 |
| 时区 | 服务器本地时区 |

### 请求方式

- 查询类接口使用 `GET`
- 创建类接口使用 `POST`（Body 为 JSON 格式）
- 删除类接口使用 `DELETE`

### 统一响应格式

所有接口返回统一的 JSON 格式：

```json
{
    "success": true,
    "code": 0,
    "message": "Success",
    "data": { ... },
    "timestamp": 1700000000,
    "request_id": "req_abc123"
}
```

| 字段 | 类型 | 说明 |
|------|------|------|
| success | bool | 请求是否成功 |
| code | int | 业务错误码，0 表示成功 |
| message | string | 消息描述 |
| data | object/array | 返回数据 |
| timestamp | int | 服务器时间戳 |
| request_id | string | 请求唯一标识（用于排查问题） |

### 分页参数

列表类接口支持分页：

| 参数 | 类型 | 默认值 | 说明 |
|------|------|--------|------|
| page | int | 1 | 页码（从 1 开始） |
| page_size | int | 20 | 每页数量（最大 100） |

分页响应格式：

```json
{
    "list": [...],
    "pagination": {
        "page": 1,
        "page_size": 20,
        "total": 100,
        "total_pages": 5
    }
}
```

---

## 认证说明

### 认证方式

采用 **API Key + HMAC-SHA256 签名** 机制，类似支付宝开放平台。

### 获取凭证

联系管理员创建 API Key，获得以下凭证：

| 凭证 | 说明 |
|------|------|
| api_key | API 标识，公开传输 |
| api_secret | 签名密钥，**仅在创建时显示一次，务必妥善保存** |

### 请求参数

每个请求必须携带以下认证参数（通过 Query String 或 Header）：

| 参数 | 传递方式 | 说明 |
|------|----------|------|
| api_key | Query String 或 Header `X-API-Key` | API Key |
| timestamp | Query String | 当前 Unix 时间戳（秒），5 分钟内有效 |
| sign | Query String | HMAC-SHA256 签名 |

### 签名算法

**第一步**：拼接签名字符串

```
signString = api_key + timestamp + api_secret
```

**第二步**：计算 HMAC-SHA256 签名

```
sign = HMAC-SHA256(signString, api_secret)
```

### 签名示例

假设：
- `api_key` = `ak_1234567890abcdef`
- `api_secret` = `secret_abcdef1234567890`
- `timestamp` = `1700000000`

```
signString = "ak_1234567890abcdef" + "1700000000" + "secret_abcdef1234567890"
           = "ak_1234567890abcdef1700000000secret_abcdef1234567890"

sign = HMAC-SHA256(signString, "secret_abcdef1234567890")
     = "a1b2c3d4e5f6..."  (64 位十六进制字符串)
```

### PHP 签名代码

```php
function generateSign($apiKey, $apiSecret, $timestamp) {
    $signString = $apiKey . $timestamp . $apiSecret;
    return hash_hmac('sha256', $signString, $apiSecret);
}

// 使用示例
$apiKey = 'ak_1234567890abcdef';
$apiSecret = 'secret_abcdef1234567890';
$timestamp = time();
$sign = generateSign($apiKey, $apiSecret, $timestamp);

$url = "https://your-domain.com/api/v1/lottery_results/latest"
     . "?api_key=$apiKey&timestamp=$timestamp&sign=$sign";
```

### Python 签名代码

```python
import hmac
import hashlib
import time

def generate_sign(api_key, api_secret, timestamp):
    sign_string = f"{api_key}{timestamp}{api_secret}"
    return hmac.new(
        api_secret.encode(),
        sign_string.encode(),
        hashlib.sha256
    ).hexdigest()
```

### 防重放攻击

- 请求中的 `timestamp` 必须在服务器时间的 **前后 5 分钟** 内
- 超过 5 分钟的请求会返回 `1023 Request has expired`
- 请确保客户端和服务器时钟同步（建议使用 NTP）

### 速率限制

- 每个 API Key 有独立的速率限制（默认 100 次/分钟）
- 响应头中包含速率限制信息：

| Header | 说明 |
|--------|------|
| X-RateLimit-Limit | 每分钟允许的最大请求数 |
| X-RateLimit-Remaining | 当前窗口内剩余请求数 |
| X-RateLimit-Reset | 限制重置的 Unix 时间戳 |

- 超过限制时返回 HTTP 429，并包含 `Retry-After` 头

---

## 接口列表

### 开奖结果

#### GET /lottery_results/latest

查询最新开奖结果（带 5 分钟 Redis 缓存）。

**参数：**

| 参数 | 类型 | 必填 | 说明 |
|------|------|------|------|
| game_id | int | 否 | 游戏 ID，不传则返回全局最新 |

**响应示例：**

```json
{
    "success": true,
    "code": 0,
    "data": {
        "id": 12345,
        "game_id": 1,
        "period": "20250203001",
        "result": "3,5,2,8,1",
        "result_sum": 19,
        "draw_time": "2025-02-03 15:30:00"
    }
}
```

#### GET /lottery_results

查询历史开奖结果列表。

**参数：**

| 参数 | 类型 | 必填 | 说明 |
|------|------|------|------|
| game_id | int | 否 | 游戏 ID 过滤 |
| start_date | string | 否 | 开始日期 (YYYY-MM-DD HH:MM:SS) |
| end_date | string | 否 | 结束日期 |
| page | int | 否 | 页码，默认 1 |
| page_size | int | 否 | 每页数量，默认 20 |

**响应示例：**

```json
{
    "success": true,
    "code": 0,
    "data": {
        "list": [
            {
                "id": 12345,
                "game_id": 1,
                "period": "20250203001",
                "result": "3,5,2,8,1",
                "result_sum": 19,
                "draw_time": "2025-02-03 15:30:00"
            }
        ],
        "pagination": {
            "page": 1,
            "page_size": 20,
            "total": 500,
            "total_pages": 25
        }
    }
}
```

#### GET /lottery_results/{period}

查询指定期号的开奖结果（带 1 小时缓存）。

**响应：** 同 `/lottery_results/latest`

---

### 投注记录

#### GET /bets

查询投注记录列表。

**参数：**

| 参数 | 类型 | 必填 | 说明 |
|------|------|------|------|
| user_id | int | 否 | 用户 ID |
| game_id | int | 否 | 游戏 ID |
| status | int | 否 | 状态过滤 |
| start_date | string | 否 | 开始日期 |
| end_date | string | 否 | 结束日期 |
| page | int | 否 | 页码 |
| page_size | int | 否 | 每页数量 |

**响应示例：**

```json
{
    "success": true,
    "code": 0,
    "data": {
        "list": [
            {
                "id": 1001,
                "user_id": 100,
                "game_id": 1,
                "period": "20250203001",
                "bet_content": "大",
                "bet_amount": 100.00,
                "odds": 1.98,
                "win_amount": 0,
                "status": 0,
                "created_at": "2025-02-03 15:20:00"
            }
        ],
        "pagination": { ... }
    }
}
```

#### GET /bets/{id}

查询单条投注记录详情。

#### POST /bets

创建投注记录。

**请求 Body：**

```json
{
    "user_id": 100,
    "game_id": 1,
    "period": "20250203001",
    "bet_content": "大",
    "bet_amount": 100.00
}
```

---

### 交易记录

#### GET /transactions

查询交易流水记录。

**参数：**

| 参数 | 类型 | 必填 | 说明 |
|------|------|------|------|
| user_id | int | 否 | 用户 ID |
| type | int | 否 | 交易类型 |
| start_date | string | 否 | 开始日期 |
| end_date | string | 否 | 结束日期 |
| page | int | 否 | 页码 |
| page_size | int | 否 | 每页数量 |

#### GET /transactions/{id}

查询单条交易记录详情。

---

### 充值

#### GET /deposits

查询充值记录列表。

**参数：**

| 参数 | 类型 | 必填 | 说明 |
|------|------|------|------|
| user_id | int | 否 | 用户 ID |
| page | int | 否 | 页码 |
| page_size | int | 否 | 每页数量 |

#### GET /deposits/{id}

查询单条充值记录。

#### POST /deposits

创建充值记录。

**请求 Body：**

```json
{
    "user_id": 100,
    "amount": 500.00,
    "remark": "充值"
}
```

---

### 提现

#### GET /withdrawals

查询提现记录列表。

#### GET /withdrawals/{id}

查询单条提现记录。

#### POST /withdrawals

创建提现申请。

**请求 Body：**

```json
{
    "user_id": 100,
    "amount": 200.00,
    "remark": "提现"
}
```

---

### 用户

#### GET /users/{id}

查询用户信息。

**响应示例：**

```json
{
    "success": true,
    "code": 0,
    "data": {
        "user_id": 100,
        "username": "test_user",
        "balance": 1500.00,
        "status": 1,
        "created_at": "2025-01-01 00:00:00"
    }
}
```

#### GET /users/{id}/balance

查询用户余额。

#### POST /users

创建用户。

---

### Webhook 管理

#### POST /webhooks

创建 Webhook 订阅。

**请求 Body：**

```json
{
    "event_type": "bet.created",
    "callback_url": "https://your-domain.com/webhook",
    "description": "投注通知"
}
```

**支持的事件类型：**

| 事件类型 | 说明 |
|----------|------|
| bet.created | 新投注创建 |
| bet.settled | 投注结算 |
| deposit.completed | 充值完成 |
| withdrawal.completed | 提现完成 |

**响应示例：**

```json
{
    "success": true,
    "code": 0,
    "data": {
        "id": 1,
        "event_type": "bet.created",
        "callback_url": "https://your-domain.com/webhook",
        "secret": "wh_abc123...",
        "status": 1,
        "created_at": "2025-02-03 10:00:00"
    }
}
```

#### GET /webhooks

查询 Webhook 订阅列表。

#### DELETE /webhooks/{id}

删除 Webhook 订阅。

#### GET /webhooks/{id}/logs

查询 Webhook 调用日志。

---

### 嵌入 Token

#### POST /embed-token

生成游戏窗口嵌入 Token。

**请求 Body：**

```json
{
    "user_id": 100
}
```

**响应示例：**

```json
{
    "success": true,
    "code": 0,
    "data": {
        "token": "encrypted_token_string...",
        "embed_url": "https://your-domain.com/web/embed/game.php?token=...",
        "expires_in": 300
    }
}
```

---

## 错误码列表

### 认证相关 (1000-1099)

| 错误码 | 常量名 | HTTP 状态码 | 说明 |
|--------|--------|-------------|------|
| 1001 | AUTH_API_KEY_MISSING | 401 | 缺少 API Key |
| 1002 | AUTH_API_KEY_INVALID | 401 | API Key 无效 |
| 1003 | AUTH_API_KEY_DISABLED | 401 | API Key 已禁用 |
| 1004 | AUTH_API_KEY_EXPIRED | 401 | API Key 已过期 |
| 1011 | AUTH_SIGNATURE_MISSING | 401 | 缺少签名 |
| 1012 | AUTH_SIGNATURE_INVALID | 401 | 签名无效 |
| 1021 | AUTH_TIMESTAMP_MISSING | 401 | 缺少时间戳 |
| 1022 | AUTH_TIMESTAMP_INVALID | 401 | 时间戳格式无效 |
| 1023 | AUTH_REQUEST_EXPIRED | 401 | 请求已过期（超过 5 分钟） |
| 1031 | AUTH_IP_NOT_ALLOWED | 403 | IP 不在白名单 |
| 1041 | AUTH_INSUFFICIENT_PERMISSIONS | 403 | 权限不足 |

### 参数相关 (2000-2099)

| 错误码 | 常量名 | HTTP 状态码 | 说明 |
|--------|--------|-------------|------|
| 2001 | PARAM_MISSING | 400 | 缺少必要参数 |
| 2002 | PARAM_INVALID | 400 | 参数值无效 |
| 2003 | PARAM_TYPE_ERROR | 400 | 参数类型错误 |
| 2004 | PARAM_OUT_OF_RANGE | 400 | 参数超出范围 |
| 2005 | PARAM_FORMAT_ERROR | 400 | 参数格式错误 |

### 业务逻辑相关 (3000-3099)

| 错误码 | 常量名 | HTTP 状态码 | 说明 |
|--------|--------|-------------|------|
| 3001 | BIZ_RESOURCE_NOT_FOUND | 404 | 资源未找到 |
| 3002 | BIZ_RESOURCE_ALREADY_EXISTS | 409 | 资源已存在 |
| 3003 | BIZ_OPERATION_FAILED | 400 | 操作失败 |
| 3011 | BIZ_INSUFFICIENT_BALANCE | 400 | 余额不足 |
| 3012 | BIZ_INVALID_BET_AMOUNT | 400 | 投注金额无效 |
| 3013 | BIZ_BETTING_CLOSED | 400 | 投注已关闭 |
| 3014 | BIZ_GAME_UNAVAILABLE | 400 | 游戏不可用 |
| 3021 | BIZ_USER_NOT_FOUND | 404 | 用户不存在 |
| 3022 | BIZ_USER_DISABLED | 400 | 用户已禁用 |
| 3031 | BIZ_WEBHOOK_NOT_FOUND | 404 | Webhook 不存在 |

### 系统相关 (5000-5099)

| 错误码 | 常量名 | HTTP 状态码 | 说明 |
|--------|--------|-------------|------|
| 5001 | SYS_INTERNAL_ERROR | 500 | 内部服务器错误 |
| 5002 | SYS_DATABASE_ERROR | 500 | 数据库错误 |
| 5003 | SYS_REDIS_ERROR | 500 | Redis 错误 |
| 5011 | SYS_RATE_LIMIT_EXCEEDED | 429 | 速率限制超出 |
| 5021 | SYS_SERVICE_UNAVAILABLE | 503 | 服务暂时不可用 |

---

## Webhook 配置指南

### 工作流程

1. 通过 `POST /webhooks` 创建 Webhook 订阅，指定事件类型和回调 URL
2. 创建成功后获得 `secret`（用于验证签名）
3. 当对应事件发生时，系统会向 callback_url 发送 POST 请求
4. 你的服务器验证签名后处理数据

### Webhook 请求格式

系统向你的回调 URL 发送 POST 请求：

**请求头：**

| Header | 说明 |
|--------|------|
| Content-Type | application/json |
| X-Webhook-Signature | HMAC-SHA256 签名 |
| X-Webhook-Event | 事件类型 |
| X-Webhook-Id | Webhook 配置 ID |
| X-Webhook-Timestamp | 发送时间戳 |

**请求 Body 示例（投注事件）：**

```json
{
    "event": "bet.created",
    "timestamp": 1700000000,
    "data": {
        "id": 1001,
        "user_id": 100,
        "game_id": 1,
        "period": "20250203001",
        "bet_content": "大",
        "bet_amount": 100.00,
        "created_at": "2025-02-03 15:20:00"
    }
}
```

### 签名验证

验证 Webhook 签名以确保数据来源可信：

```php
// 获取原始请求体
$payload = file_get_contents('php://input');

// 获取签名
$signature = $_SERVER['HTTP_X_WEBHOOK_SIGNATURE'];

// 验证签名
$expectedSign = hash_hmac('sha256', $payload, $webhookSecret);
$isValid = hash_equals($expectedSign, $signature);

if (!$isValid) {
    http_response_code(401);
    echo "Invalid signature";
    exit;
}

// 处理事件数据
$event = json_decode($payload, true);
// ... 业务处理
```

### 响应要求

| 你的响应 | 系统行为 |
|----------|----------|
| HTTP 2xx | 标记为成功 |
| HTTP 非 2xx 或超时 | 标记为失败，触发重试 |
| 超时（默认 5 秒） | 标记为失败 |

### 重试机制

- 失败后重试 1 次
- 重试间隔：立即重试
- 可在创建 Webhook 时配置 `retry_times` 和 `timeout`

### 最佳实践

1. 回调接口应在 3 秒内响应，避免超时
2. 收到 Webhook 后先返回 200，再异步处理业务
3. 做好幂等处理，同一事件可能收到多次推送
4. 定期检查 Webhook 调用日志（`GET /webhooks/{id}/logs`）

---

## 游戏窗口嵌入

### 嵌入流程

1. 调用 `POST /embed-token` 生成嵌入 Token（有效期 5 分钟）
2. 使用返回的 `embed_url` 创建 iframe
3. 用户在 iframe 中自动登录并加载游戏

### 嵌入代码示例

```html
<iframe
    src="https://your-domain.com/web/embed/game.php?token=YOUR_TOKEN"
    width="100%"
    height="600"
    frameborder="0"
    allowfullscreen
></iframe>
```

### 安全说明

- Token 采用 AES-256-CBC 加密，包含用户 ID、过期时间和 nonce
- 每个 Token 只能使用一次（nonce 防重放）
- Token 有效期 5 分钟
- 仅允许该 API Key 关联代理下的用户生成 Token
- 嵌入页面设置了 `X-Frame-Options` 和 `Content-Security-Policy` 安全头

### 注意事项

- 生产环境应配置 `X-Frame-Options` 为具体域名
- iframe 内的页面已隐藏导航栏和页脚
- 会话有效期取决于服务器 Session 配置
