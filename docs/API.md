# 彩票系统 API 文档

版本: v1.0
更新日期: 2026-02-03

## 目录

- [概述](#概述)
- [认证](#认证)
- [速率限制](#速率限制)
- [错误处理](#错误处理)
- [接口列表](#接口列表)
- [Webhook](#webhook)
- [代码示例](#代码示例)

---

## 概述

彩票系统提供 RESTful API，允许外部系统集成游戏功能、查询数据和接收事件通知。

**基础 URL**:
```
https://your-domain.com/api/v1/
```

**数据格式**:
- 请求: `application/x-www-form-urlencoded` 或 `application/json`
- 响应: `application/json`

**字符编码**: UTF-8

---

## 认证

### 认证方式

API 使用 **API Key + 签名验证** 机制。

### 获取 API Key

联系管理员创建 API Key，您将获得:
- `api_key`: 公开的 API Key
- `api_secret`: 保密的 API Secret (用于签名)

### 请求签名

每个请求必须包含以下参数:

| 参数 | 说明 | 位置 |
|------|------|------|
| `api_key` | API Key | Header 或 Query |
| `timestamp` | 当前时间戳 (秒) | Query 或 POST |
| `sign` | 请求签名 | Query 或 POST |

### 签名算法

```
sign = HMAC-SHA256(api_key + timestamp + api_secret, api_secret)
```

**步骤**:
1. 拼接字符串: `api_key` + `timestamp` + `api_secret`
2. 使用 `api_secret` 作为密钥，计算 HMAC-SHA256
3. 结果为十六进制字符串

### PHP 示例

```php
$apiKey = 'your_api_key';
$apiSecret = 'your_api_secret';
$timestamp = time();

$signString = $apiKey . $timestamp . $apiSecret;
$sign = hash_hmac('sha256', $signString, $apiSecret);

// 构建请求 URL
$url = "https://api.example.com/v1/test.php?api_key=$apiKey&timestamp=$timestamp&sign=$sign";
```

### JavaScript 示例

```javascript
const crypto = require('crypto');

const apiKey = 'your_api_key';
const apiSecret = 'your_api_secret';
const timestamp = Math.floor(Date.now() / 1000);

const signString = apiKey + timestamp + apiSecret;
const sign = crypto.createHmac('sha256', apiSecret)
    .update(signString)
    .digest('hex');

// 构建请求 URL
const url = `https://api.example.com/v1/test.php?api_key=${apiKey}&timestamp=${timestamp}&sign=${sign}`;
```

### 防重放攻击

- 时间戳必须在 **5 分钟内**
- 超过 5 分钟的请求将被拒绝

### 认证失败响应

```json
{
    "success": false,
    "code": 1012,
    "message": "Invalid signature",
    "data": null,
    "timestamp": 1675324800,
    "request_id": "req_63d9f5a0b2c41"
}
```

---

## 速率限制

### 限制规则

每个 API Key 有独立的速率限制配置，默认:
- **100 次/分钟**

### 响应头

每个响应包含速率限制信息:

```
X-RateLimit-Limit: 100
X-RateLimit-Remaining: 95
X-RateLimit-Reset: 1675324860
```

| Header | 说明 |
|--------|------|
| `X-RateLimit-Limit` | 限制次数 |
| `X-RateLimit-Remaining` | 剩余次数 |
| `X-RateLimit-Reset` | 重置时间 (Unix 时间戳) |

### 超限响应

```json
{
    "success": false,
    "code": 5011,
    "message": "Rate limit exceeded",
    "data": {
        "retry_after": 45
    },
    "timestamp": 1675324800,
    "request_id": "req_63d9f5a0b2c41"
}
```

HTTP 状态码: `429 Too Many Requests`

---

## 错误处理

### 响应格式

所有响应遵循统一格式:

```json
{
    "success": true,
    "code": 0,
    "message": "Success",
    "data": { ... },
    "timestamp": 1675324800,
    "request_id": "req_63d9f5a0b2c41"
}
```

| 字段 | 类型 | 说明 |
|------|------|------|
| `success` | boolean | 是否成功 |
| `code` | integer | 业务码 (0=成功) |
| `message` | string | 消息 |
| `data` | mixed | 数据 |
| `timestamp` | integer | 响应时间戳 |
| `request_id` | string | 请求 ID (用于追踪) |

### 错误码

#### 认证相关 (1000-1999)

| 错误码 | 说明 |
|--------|------|
| 1001 | API Key 缺失 |
| 1002 | API Key 无效 |
| 1003 | API Key 已禁用 |
| 1004 | API Key 已过期 |
| 1011 | 签名缺失 |
| 1012 | 签名无效 |
| 1021 | 时间戳缺失 |
| 1022 | 时间戳无效 |
| 1023 | 请求已过期 |
| 1031 | IP 地址不在白名单 |
| 1041 | 权限不足 |

#### 参数相关 (2000-2999)

| 错误码 | 说明 |
|--------|------|
| 2001 | 参数缺失 |
| 2002 | 参数无效 |
| 2003 | 参数类型错误 |
| 2004 | 参数超出范围 |
| 2005 | 参数格式错误 |

#### 业务逻辑相关 (3000-3999)

| 错误码 | 说明 |
|--------|------|
| 3001 | 资源未找到 |
| 3002 | 资源已存在 |
| 3011 | 余额不足 |
| 3012 | 投注金额无效 |
| 3013 | 投注已关闭 |
| 3021 | 用户不存在 |
| 3022 | 用户已被禁用 |

#### 系统相关 (5000-5999)

| 错误码 | 说明 |
|--------|------|
| 5001 | 内部服务器错误 |
| 5002 | 数据库错误 |
| 5011 | 速率限制超出 |
| 5021 | 服务暂时不可用 |

---

## 接口列表

### 测试接口

#### GET /api/v1/test.php

测试 API 连接和认证。

**请求参数**:

| 参数 | 类型 | 必需 | 说明 |
|------|------|------|------|
| `api_key` | string | 是 | API Key |
| `timestamp` | integer | 是 | 时间戳 |
| `sign` | string | 是 | 签名 |
| `message` | string | 否 | 测试消息 |

**响应示例**:

```json
{
    "success": true,
    "code": 0,
    "message": "API is working correctly",
    "data": {
        "message": "Hello from Lottery API!",
        "server_time": "2026-02-03 15:30:00",
        "api_version": "1.0",
        "authenticated": true,
        "api_key_info": {
            "partner_name": "测试合作伙伴",
            "agent_id": 1,
            "rate_limit": 100
        }
    },
    "timestamp": 1675324800,
    "request_id": "req_63d9f5a0b2c41"
}
```

---

## Webhook

### 概述

Webhook 允许您在特定事件发生时接收实时通知。

### 支持的事件

| 事件类型 | 说明 |
|---------|------|
| `bet.created` | 投注创建 |
| `bet.settled` | 投注结算 |
| `deposit.completed` | 充值完成 |
| `withdrawal.completed` | 提款完成 |

### 创建 Webhook

**请求**:
```
POST /api/v1/webhooks.php
```

**参数**:

| 参数 | 类型 | 必需 | 说明 |
|------|------|------|------|
| `event_type` | string | 是 | 事件类型 |
| `callback_url` | string | 是 | 回调 URL |
| `description` | string | 否 | 描述 |

**响应**:

```json
{
    "success": true,
    "code": 0,
    "message": "Webhook created successfully",
    "data": {
        "id": 1,
        "event_type": "bet.created",
        "callback_url": "https://your-site.com/webhook",
        "secret": "whsec_...",
        "status": 1
    }
}
```

### Webhook 签名验证

每个 Webhook 请求包含签名头:

```
X-Webhook-Signature: a1b2c3d4...
X-Webhook-Event: bet.created
```

**验证签名**:

```php
$payload = file_get_contents('php://input');
$signature = $_SERVER['HTTP_X_WEBHOOK_SIGNATURE'];
$secret = 'your_webhook_secret';

$expectedSignature = hash_hmac('sha256', $payload, $secret);

if (!hash_equals($expectedSignature, $signature)) {
    http_response_code(401);
    die('Invalid signature');
}

// 处理 Webhook
$event = json_decode($payload, true);
```

### Webhook 请求示例

```json
{
    "event": "bet.created",
    "data": {
        "bet_id": 12345,
        "user_id": 100,
        "game_id": 1,
        "amount": 100.00,
        "created_at": "2026-02-03 15:30:00"
    },
    "timestamp": 1675324800,
    "webhook_id": 1
}
```

### 重试机制

- 如果回调失败 (非 2xx 响应)，系统会重试 **1 次**
- 重试间隔: **1 秒**

### 超时设置

- 默认超时: **5 秒**
- 可在创建 Webhook 时配置

---

## 代码示例

### PHP cURL 示例

```php
<?php

class LotteryAPIClient {
    private $apiKey;
    private $apiSecret;
    private $baseUrl;

    public function __construct($apiKey, $apiSecret, $baseUrl) {
        $this->apiKey = $apiKey;
        $this->apiSecret = $apiSecret;
        $this->baseUrl = $baseUrl;
    }

    public function request($endpoint, $params = []) {
        $timestamp = time();
        $sign = $this->generateSign($timestamp);

        $params['api_key'] = $this->apiKey;
        $params['timestamp'] = $timestamp;
        $params['sign'] = $sign;

        $url = $this->baseUrl . $endpoint . '?' . http_build_query($params);

        $ch = curl_init($url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);

        $response = curl_exec($ch);
        $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        curl_close($ch);

        return [
            'http_code' => $httpCode,
            'data' => json_decode($response, true)
        ];
    }

    private function generateSign($timestamp) {
        $signString = $this->apiKey . $timestamp . $this->apiSecret;
        return hash_hmac('sha256', $signString, $this->apiSecret);
    }
}

// 使用示例
$client = new LotteryAPIClient(
    'test_api_key_12345678',
    'test_secret_87654321abcdefgh',
    'https://your-domain.com/api/v1/'
);

$result = $client->request('test.php', ['message' => 'Hello']);
print_r($result);
```

---

## 获取帮助

- **技术支持**: support@example.com
- **API 问题**: api-support@example.com
- **文档更新**: [GitHub](https://github.com/Xia0321/lottery)

---

**最后更新**: 2026-02-03
**版本**: 1.0
