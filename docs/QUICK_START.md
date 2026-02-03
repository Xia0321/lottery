# API 快速入门指南

10 分钟上手彩票系统 API

## 步骤 1: 获取 API Key (2 分钟)

联系管理员创建 API Key，您将收到:

```
API Key: api_xxxxxxxxxxxxxxxx
API Secret: xxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxx
```

⚠️ **重要**: 请妥善保管 API Secret，不要泄露给他人。

---

## 步骤 2: 测试连接 (3 分钟)

### 使用 PHP

创建文件 `test_api.php`:

```php
<?php

// 配置
$apiKey = 'test_api_key_12345678';
$apiSecret = 'test_secret_87654321abcdefgh';
$apiUrl = 'http://localhost/lottery/web/api/v1/test.php';

// 生成签名
$timestamp = time();
$signString = $apiKey . $timestamp . $apiSecret;
$sign = hash_hmac('sha256', $signString, $apiSecret);

// 构建请求 URL
$url = $apiUrl . '?api_key=' . $apiKey . '&timestamp=' . $timestamp . '&sign=' . $sign;

// 发送请求
$response = file_get_contents($url);
$data = json_decode($response, true);

// 输出结果
echo "连接成功!\n";
print_r($data);
```

运行:
```bash
php test_api.php
```

### 使用 cURL 命令行

```bash
# 设置变量
API_KEY="test_api_key_12345678"
API_SECRET="test_secret_87654321abcdefgh"
TIMESTAMP=$(date +%s)

# 生成签名
SIGN_STRING="${API_KEY}${TIMESTAMP}${API_SECRET}"
SIGN=$(echo -n "$SIGN_STRING" | openssl dgst -sha256 -hmac "$API_SECRET" | cut -d' ' -f2)

# 发送请求
curl "http://localhost/lottery/web/api/v1/test.php?api_key=${API_KEY}&timestamp=${TIMESTAMP}&sign=${SIGN}"
```

### 预期响应

```json
{
    "success": true,
    "code": 0,
    "message": "API is working correctly",
    "data": {
        "message": "Hello from Lottery API!",
        "server_time": "2026-02-03 15:30:00",
        "api_version": "1.0",
        "authenticated": true
    }
}
```

✅ 如果看到 `"success": true`，说明连接成功！

---

## 步骤 3: 创建 Webhook (2 分钟)

### 准备接收端点

创建 `webhook_receiver.php`:

```php
<?php

// 接收 Webhook
$payload = file_get_contents('php://input');
$event = json_decode($payload, true);

// 验证签名
$signature = $_SERVER['HTTP_X_WEBHOOK_SIGNATURE'] ?? '';
$secret = 'your_webhook_secret'; // 从创建响应中获取

$expectedSignature = hash_hmac('sha256', $payload, $secret);

if (!hash_equals($expectedSignature, $signature)) {
    http_response_code(401);
    die('Invalid signature');
}

// 处理事件
file_put_contents('webhook.log', date('Y-m-d H:i:s') . ' - ' . $event['event'] . "\n", FILE_APPEND);

// 返回成功
http_response_code(200);
echo json_encode(['received' => true]);
```

### 创建 Webhook 订阅

```php
<?php

// 配置
$apiKey = 'your_api_key';
$apiSecret = 'your_api_secret';
$apiUrl = 'http://localhost/lottery/web/api/v1/webhooks.php';

// 生成签名
$timestamp = time();
$signString = $apiKey . $timestamp . $apiSecret;
$sign = hash_hmac('sha256', $signString, $apiSecret);

// 准备数据
$postData = [
    'api_key' => $apiKey,
    'timestamp' => $timestamp,
    'sign' => $sign,
    'event_type' => 'bet.created',
    'callback_url' => 'https://your-site.com/webhook_receiver.php',
    'description' => '接收投注创建事件'
];

// 发送 POST 请求
$ch = curl_init($apiUrl);
curl_setopt($ch, CURLOPT_POST, true);
curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query($postData));
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

$response = curl_exec($ch);
curl_close($ch);

$data = json_decode($response, true);
print_r($data);

// 保存 Webhook Secret
if ($data['success']) {
    echo "Webhook Secret: " . $data['data']['secret'] . "\n";
}
```

---

## 步骤 4: 嵌入游戏窗口 (3 分钟)

### 生成嵌入 URL

```php
<?php

// 配置
$apiKey = 'your_api_key';
$apiSecret = 'your_api_secret';
$userId = 123; // 您的用户 ID

// 生成 Token
$timestamp = time();
$tokenData = json_encode([
    'user_id' => $userId,
    'exp' => $timestamp + 300, // 5分钟后过期
    'nonce' => bin2hex(random_bytes(8))
]);

$token = base64_encode(openssl_encrypt(
    $tokenData,
    'AES-256-CBC',
    $apiSecret,
    0,
    substr($apiSecret, 0, 16)
));

// 构建嵌入 URL
$embedUrl = "https://your-domain.com/embed/game.php?token=" . urlencode($token);

echo "嵌入 URL: $embedUrl\n";
```

### 在页面中嵌入

```html
<!DOCTYPE html>
<html>
<head>
    <title>游戏</title>
    <style>
        .game-container {
            width: 100%;
            height: 600px;
            border: none;
        }
    </style>
</head>
<body>
    <h1>我的游戏</h1>
    <iframe src="<?php echo $embedUrl; ?>" class="game-container"></iframe>
</body>
</html>
```

---

## 常见问题

### Q: 签名验证失败怎么办？

A: 检查以下几点:
1. API Key 和 API Secret 是否正确
2. 时间戳是否为当前时间 (秒级)
3. 签名算法是否正确: `HMAC-SHA256(api_key + timestamp + api_secret, api_secret)`
4. 服务器时间是否准确 (误差不超过 5 分钟)

### Q: 速率限制如何计算？

A: 默认每个 API Key 限制 100 次/分钟，从第一次请求开始计时，60 秒后重置。

### Q: Webhook 没有收到通知？

A: 检查以下几点:
1. 回调 URL 是否可以从外网访问
2. 回调端点是否返回 2xx 状态码
3. 查看 Webhook 日志确认发送状态
4. 确认防火墙没有阻止请求

### Q: 如何测试 Webhook？

A: 使用工具如 [Webhook.site](https://webhook.site) 或 [RequestBin](https://requestbin.com) 获取临时回调 URL。

---

## 下一步

- 📖 查看[完整 API 文档](API.md)
- 🔐 阅读[安全文档](../SECURITY.md)
- 💻 查看[代码示例](../examples/)
- 🐛 [报告问题](https://github.com/Xia0321/lottery/issues)

---

**需要帮助？** 联系技术支持: support@example.com
