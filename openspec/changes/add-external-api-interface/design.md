## 背景

当前彩票系统是一个基于 PHP 的网页应用，主要面向终端用户提供游戏界面。系统架构包括：

- **前端**：基于 jQuery 和 Angular 的传统 Web 页面（位于 `138.979500.com_YzwL5/` 和 `web/templates/`）
- **后端**：PHP 脚本处理业务逻辑（位于 `web/hide/`、`web/main/`、`web/mxj/` 等目录）
- **数据库**：MySQL 数据库（cs1381328.sql），存储用户、投注、流水等数据
- **现有功能**：用户登录、游戏投注、资金管理、报表查询、代理系统等

**当前限制**：
- 系统仅提供 Web 界面，无法被外部系统集成
- 缺乏标准化的 API 接口
- 无跨域访问支持
- 无外部系统的身份认证机制

**业务需求**：
领导要求将系统改造为白标解决方案，允许合作伙伴通过 API 接入，在自己的平台上嵌入游戏窗口并同步数据。

**技术约束**：
- 必须保持现有 Web 界面的功能不变
- 需要支持 PHP 5.6+ 环境（当前系统的运行环境）
- 需要最小化对现有代码的修改，降低风险
- 需要考虑服务器性能，避免过度消耗资源

## 目标与非目标

**目标（第一期）：**
- 提供核心的 RESTful API 查询接口（用户、投注、流水、开奖）
- 实现简单有效的认证机制（API Key + 签名验证）
- 提供可嵌入的游戏窗口（iframe）
- 实现 Webhook 推送机制（投注、中奖、充值、提款）
- 提供基础 API 文档（Markdown + Postman）
- 确保基础安全（防 SQL 注入、签名验证、HTTPS、速率限制）
- **6 周内完成并上线**

**非目标（第一期不做）：**
- 不重构现有 Web 界面代码
- 不修改现有数据库表（只新增 4 张表）
- 不提供复杂的权限控制（IP 白名单、Scope 等后期再做）
- 不提供批量操作接口（让外部系统循环调用）
- 不提供统计分析接口（后期再做）
- 不提供 SDK（只提供文档和示例）
- 不提供 WebSocket 实时推送（用 Webhook 代替）
- 不提供沙箱环境（用测试 API Key 代替）
- 不提供监控面板（先看日志）
- 不做游戏窗口主题定制（使用默认样式）

## 设计决策

### 1. 接口架构设计

**决策**：采用 RESTful API 架构，使用 JSON 作为数据交换格式

**理由**：
- REST 是业界标准，易于理解和集成
- JSON 格式轻量、易解析，所有编程语言都有良好支持
- 与现有 PHP 技术栈兼容性好

**备选方案**：
- GraphQL：灵活性更高，但学习曲线陡峭，PHP 生态支持不如 REST 成熟
- SOAP：过于复杂和冗余，不适合现代 Web API

**实现方式**：
- 创建 `web/api/` 目录存放所有 API 控制器
- 使用 URL 路由格式：`/api/v1/{resource}/{action}`
- 采用标准 HTTP 方法：GET（查询）、POST（创建）、PUT/PATCH（更新）、DELETE（删除）

### 2. 认证与授权机制（简化方案）

**决策**：采用 API Key + 签名验证机制（类似支付宝开放平台）

**理由**：
- 实现简单，100 行代码搞定
- 无需 Token 刷新，降低外部系统集成复杂度
- Secret 不在网络传输，更安全
- 签名验证可防止参数篡改和重放攻击

**放弃的方案**：
- ~~JWT Token~~：过于复杂，需要处理 Token 刷新，外部系统集成成本高
- ~~OAuth 2.0~~：过度设计，不适合 B2B 简单场景

**实现方式**：

1. **管理后台生成 API Key**：
   ```php
   $apiKey = 'ak_' . uniqid() . bin2hex(random_bytes(16));
   $secret = 'sk_' . bin2hex(random_bytes(32));
   // 存储到 api_keys 表，Secret 使用 bcrypt 加密
   ```

2. **外部系统调用 API**：
   ```php
   // 1. 准备参数
   $params = [
       'api_key' => 'ak_xxx',
       'user_id' => 123,
       'timestamp' => time()
   ];

   // 2. 参数排序并签名（使用 HMAC-SHA256）
   ksort($params);
   $sign = hash_hmac('sha256', http_build_query($params), $secret);
   $params['sign'] = $sign;

   // 3. 发送请求
   GET /api/v1/users/123?api_key=ak_xxx&timestamp=xxx&sign=xxx
   ```

3. **服务端验证**：
   ```php
   function verifySign($params) {
       // 1. 获取 API Key 对应的 Secret
       $apiKey = $params['api_key'];
       $secret = getSecretByApiKey($apiKey);

       // 2. 提取签名
       $receivedSign = $params['sign'];
       unset($params['sign']);

       // 3. 重新计算签名（使用 HMAC-SHA256）
       ksort($params);
       $expectedSign = hash_hmac('sha256', http_build_query($params), $secret);

       // 4. 验证签名
       if ($receivedSign !== $expectedSign) {
           return false;
       }

       // 5. 验证时间戳（防重放攻击，5 分钟内有效）
       if (abs(time() - $params['timestamp']) > 300) {
           return false;
       }

       return true;
   }
   ```

**权限控制（MVP 阶段简化）**：
- 每个 API Key 只记录所属外部系统，暂不做细粒度权限
- 所有 API Key 都有相同权限（只读查询）
- 后期需要时再增加 Scope 和 IP 白名单

### 3. 游戏窗口嵌入方案（极简版）

**决策**：提供独立的嵌入页面，仅使用 iframe（postMessage 可选，后期再加）

**理由**：
- iframe 是最成熟的嵌入方案，兼容性好
- 无需修改现有游戏页面代码，只需创建包装页面
- 先实现基础嵌入，复杂交互后期再加

**放弃的功能（MVP 阶段）**：
- ~~postMessage 双向通信~~：大部分场景不需要，后期有需求再加
- ~~主题定制~~：先用默认样式
- ~~多语言切换~~：默认简体中文

**实现方式（极简版）**：

1. **创建嵌入页面**：
   ```php
   // web/embed/game.php
   <?php
   // 1. 验证 token（简单的加密 user_id）
   $token = $_GET['token'];
   $userId = decryptToken($token); // 使用 openssl_decrypt

   // 2. 自动登录该用户
   $_SESSION['uid'] = $userId;
   $_SESSION['check'] = md5($config['allpass'] . $userId);

   // 3. 包含现有游戏页面（去掉导航栏）
   $hideNav = true;
   include '../hide/play.php';
   ?>
   ```

2. **外部系统嵌入**：
   ```html
   <!-- 外部系统生成 token -->
   <?php
   $token = encryptUserId($userId); // 使用 openssl_encrypt
   ?>

   <!-- 嵌入游戏窗口 -->
   <iframe src="https://lottery.com/embed/game?token=<?=$token?>"
           width="100%" height="600" frameborder="0">
   </iframe>
   ```

3. **Token 生成/验证（带时效性和防重放）**：
   ```php
   // Token 生成（有效期 5 分钟）
   function generateEmbedToken($userId, $ttl = 300) {
       global $config;

       $payload = json_encode([
           'user_id' => $userId,
           'exp' => time() + $ttl,  // 过期时间
           'nonce' => bin2hex(random_bytes(8))  // 随机数防重放
       ]);

       $key = hash('sha256', $config['secret_key']);
       $iv = substr(hash('sha256', $config['iv_key']), 0, 16);
       $encrypted = openssl_encrypt($payload, 'AES-256-CBC', $key, 0, $iv);
       return base64_encode($encrypted);
   }

   // Token 验证
   function verifyEmbedToken($token, $redis) {
       global $config;

       $key = hash('sha256', $config['secret_key']);
       $iv = substr(hash('sha256', $config['iv_key']), 0, 16);
       $decrypted = openssl_decrypt(base64_decode($token), 'AES-256-CBC', $key, 0, $iv);

       if (!$decrypted) {
           throw new Exception('Invalid token');
       }

       $payload = json_decode($decrypted, true);

       // 验证过期时间
       if (!isset($payload['exp']) || $payload['exp'] < time()) {
           throw new Exception('Token expired');
       }

       // 验证是否已使用（防重放攻击）
       $nonceKey = "token_nonce:{$payload['nonce']}";
       if ($redis->exists($nonceKey)) {
           throw new Exception('Token already used');
       }

       // 标记 Token 已使用（设置过期时间为 Token 剩余有效期）
       $redis->setex($nonceKey, $payload['exp'] - time(), 1);

       return $payload['user_id'];
   }
   ```

**安全措施（简化版）**：
- Token 有时效性（加入时间戳，5 分钟内有效）
- 配置 X-Frame-Options 允许指定域名嵌入
- HTTPS 加密传输

### 4. 数据同步策略（简化方案）

**决策**：提供 RESTful API 查询接口（只读）+ 简单的 Webhook 推送

**理由**：
- 拉取模式：满足大部分数据查询需求
- 推送模式：关键事件通知，降低轮询频率
- MVP 阶段只做查询，不做写入（降低风险）

**放弃的功能（MVP 阶段）**：
- ~~批量查询接口~~：让外部系统循环调用
- ~~游标分页~~：用传统的 offset 分页
- ~~复杂的 Webhook 重试队列~~：简单重试 1 次
- ~~异步 Webhook 发送~~：同步发送（性能够用）
- ~~数据写入接口~~：暂不提供（充值、提款走现有后台）

**实现方式**：

**拉取接口（MVP 核心接口）**：
```
GET /api/v1/users/{id}                    # 查询用户
GET /api/v1/users/{id}/balance            # 查询余额
GET /api/v1/bets?user_id=xxx&start_time=xxx&end_time=xxx  # 查询投注
GET /api/v1/transactions?user_id=xxx      # 查询流水
GET /api/v1/lottery-results/latest?game_type=xxx  # 最新开奖
GET /api/v1/lottery-results?game_type=xxx&limit=50  # 历史开奖
```

**分页格式（传统分页）**：
```json
{
  "data": [...],
  "pagination": {
    "page": 1,
    "page_size": 50,
    "total": 1000,
    "total_pages": 20
  }
}
```

**Webhook 推送（简化版）**：
```php
// 触发事件时（如用户投注）
function triggerWebhook($event, $data) {
    $webhooks = getWebhooksByEvent($event);

    foreach ($webhooks as $webhook) {
        $payload = json_encode([
            'event' => $event,
            'data' => $data,
            'timestamp' => time()
        ]);

        // 签名
        $signature = hash_hmac('sha256', $payload, $webhook['secret']);

        // 同步发送（最多重试 1 次）
        $success = sendWebhook($webhook['url'], $payload, $signature);
        if (!$success) {
            sleep(1);
            sendWebhook($webhook['url'], $payload, $signature);
        }

        // 记录日志
        logWebhook($webhook['id'], $event, $success ? 'success' : 'failed');
    }
}
```

**数据一致性（简化版）**：
- 只做查询，不涉及写入，数据一致性问题较小
- 后期需要写入接口时再考虑事务和幂等性

### 5. 数据库设计（简化版）

**决策**：只新增 4 张表（InnoDB 引擎），不修改现有表

**理由**：
- 降低对现有系统的影响
- InnoDB 支持事务（为后期写入接口做准备）
- 独立的表便于维护

**新增表结构（简化版）**：

**api_keys** 表：
```sql
CREATE TABLE `api_keys` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `api_key` varchar(64) NOT NULL COMMENT 'API Key',
  `api_secret` varchar(255) NOT NULL COMMENT 'API Secret（bcrypt 加密）',
  `name` varchar(100) NOT NULL COMMENT '外部系统名称',
  `agent_id` int(11) DEFAULT NULL COMMENT '关联代理 ID（用于数据隔离）',
  `status` tinyint(1) DEFAULT 1 COMMENT '状态：1=启用 0=禁用',
  `rate_limit` int(11) DEFAULT 100 COMMENT '每分钟请求限制',
  `created_at` datetime DEFAULT CURRENT_TIMESTAMP,
  `updated_at` datetime DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `last_used_at` datetime DEFAULT NULL COMMENT '最后使用时间',
  PRIMARY KEY (`id`),
  UNIQUE KEY `uk_api_key` (`api_key`),
  KEY `idx_agent_id` (`agent_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COMMENT='API 密钥表';
```

**api_logs** 表（简化版，只记录关键信息）：
```sql
CREATE TABLE `api_logs` (
  `id` bigint(20) NOT NULL AUTO_INCREMENT,
  `api_key_id` int(11) NOT NULL,
  `endpoint` varchar(255) NOT NULL COMMENT '请求路径',
  `method` varchar(10) NOT NULL COMMENT 'HTTP 方法',
  `response_code` int(11) NOT NULL COMMENT '响应状态码',
  `ip_address` varchar(45) DEFAULT NULL COMMENT '请求 IP',
  `created_at` datetime DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  KEY `idx_api_key_created` (`api_key_id`, `created_at`),
  KEY `idx_endpoint` (`endpoint`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COMMENT='API 访问日志';
```

**webhooks** 表（简化版）：
```sql
CREATE TABLE `webhooks` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `api_key_id` int(11) NOT NULL,
  `url` varchar(500) NOT NULL COMMENT '回调 URL',
  `secret` varchar(64) NOT NULL COMMENT 'Webhook 签名密钥',
  `events` text COMMENT '订阅的事件类型（JSON 数组）',
  `status` tinyint(1) DEFAULT 1 COMMENT '状态：1=启用 0=禁用',
  `created_at` datetime DEFAULT CURRENT_TIMESTAMP,
  `updated_at` datetime DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  KEY `idx_api_key` (`api_key_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COMMENT='Webhook 配置表';
```

**webhook_logs** 表（简化版）：
```sql
CREATE TABLE `webhook_logs` (
  `id` bigint(20) NOT NULL AUTO_INCREMENT,
  `webhook_id` int(11) NOT NULL,
  `event_type` varchar(50) NOT NULL COMMENT '事件类型',
  `http_code` int(11) DEFAULT NULL COMMENT '响应状态码',
  `retry_count` tinyint(4) DEFAULT 0 COMMENT '重试次数',
  `status` varchar(20) NOT NULL COMMENT '状态：success/failed',
  `created_at` datetime DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  KEY `idx_webhook_created` (`webhook_id`, `created_at`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COMMENT='Webhook 调用日志';
```

### 6. API 响应格式标准化

**决策**：统一的 JSON 响应格式

**成功响应**：
```json
{
  "success": true,
  "data": { ... },
  "meta": {
    "request_id": "uuid",
    "timestamp": 1234567890
  }
}
```

**分页响应**：
```json
{
  "success": true,
  "data": [ ... ],
  "pagination": {
    "total": 1000,
    "page": 1,
    "page_size": 50,
    "total_pages": 20,
    "next_cursor": "xxx"
  }
}
```

**错误响应**：
```json
{
  "success": false,
  "error": {
    "code": "AUTH_001",
    "message": "无效的访问令牌",
    "details": { ... }
  },
  "meta": {
    "request_id": "uuid",
    "timestamp": 1234567890
  }
}
```

### 7. API 版本控制

**决策**：URL 路径版本控制（`/api/v1/...`）

**理由**：
- 直观明了，易于理解
- 便于反向代理和缓存配置
- 支持同时运行多个版本

**备选方案**：
- Header 版本控制：不够直观
- 查询参数版本控制：容易遗漏

**版本策略**：
- 当前版本：v1
- 破坏性变更才升级主版本号
- 兼容性变更在同一版本内迭代
- 旧版本至少保持 6 个月的兼容期

### 6. 安全措施（MVP 阶段必须做的）

**决策**：基础安全防护，确保不被恶意利用

**必须实施（P0）**：

1. **SQL 注入防护**（最高优先级）：
   ```php
   // 所有查询改为 PDO 预处理
   $stmt = $pdo->prepare("SELECT * FROM users WHERE id = ?");
   $stmt->execute([$userId]);
   ```

2. **移除后门代码**：
   - 删除 `login.php` 中的硬编码后门逻辑
   - 代码审查，确保无其他后门

3. **HTTPS 强制**：
   - 所有 API 请求必须 HTTPS
   - 证书配置（Let's Encrypt 免费证书）

4. **签名验证**：
   - API Key + 时间戳 + 参数签名
   - 防止参数篡改和重放攻击（5 分钟时效）

5. **速率限制（简化版）**：
   ```php
   // 使用 Redis 计数器
   $key = "rate_limit:{$apiKey}:" . date('YmdHi');
   $count = $redis->incr($key);
   $redis->expire($key, 60);
   if ($count > 100) { // 每分钟 100 次
       http_response_code(429);
       die(json_encode(['error' => 'Too Many Requests']));
   }
   ```

6. **基础输入验证**：
   - 白名单验证必填参数
   - 类型检查（整数、字符串、时间戳）

**后期优化（P1）**：
- IP 白名单
- CORS 细粒度配置
- 详细操作审计
- CSP 头部配置

### 7. 性能优化（MVP 阶段够用就行）

**决策**：基础性能保障，后期有需要再优化

**MVP 阶段**：
- 强制分页：默认 50 条/页，最大 200 条/页
- 开奖结果缓存（Redis，5 分钟过期）
- 为新表添加必要索引

**后期优化**：
- 响应压缩（Gzip）
- 复杂查询的缓存
- 异步 Webhook 队列
- CDN 加速

### 8. API 文档（MVP 阶段简化版）

**决策**：Markdown 文档 + Postman Collection（放弃 Swagger）

**理由**：
- Swagger 配置复杂，开发周期长
- Markdown 文档够用，易于维护
- Postman Collection 方便测试

**实现方式**：

1. **编写 API.md 文档**：
   ```markdown
   # 彩票系统 API 文档

   ## 认证说明
   - 签名算法
   - 示例代码

   ## 接口列表
   ### 查询用户
   - 路径：GET /api/v1/users/{id}
   - 参数说明
   - 请求示例
   - 响应示例

   ## 错误码
   - AUTH_001: 签名验证失败
   - ...
   ```

2. **导出 Postman Collection**：
   - 所有接口的示例请求
   - 环境变量配置（API Key、Base URL）

3. **快速入门（10 分钟教程）**：
   - 获取 API Key
   - 第一次调用
   - 嵌入游戏窗口

**放弃的功能**：
- ~~Swagger UI~~
- ~~PHP SDK~~（后期有需求再做）
- ~~PDF 导出~~

## 风险与权衡

### 风险 1：现有代码兼容性问题

**风险**：修改现有代码可能影响 Web 界面功能

**缓解措施**：
- 最小化对现有代码的修改
- API 代码与 Web 代码隔离（独立目录）
- 充分的回归测试
- 灰度发布，先在测试环境验证

### 风险 2：API 性能影响系统稳定性

**风险**：大量 API 请求可能导致数据库和服务器压力增大

**缓解措施**：
- 实施速率限制，防止滥用
- 使用缓存减少数据库查询
- 监控 API 响应时间和错误率
- 必要时增加服务器资源或使用负载均衡

### 风险 3：安全漏洞导致数据泄露

**风险**：API 可能成为新的攻击面

**缓解措施**：
- 严格的输入验证和输出编码
- 使用 HTTPS 加密传输
- 定期安全审计和渗透测试
- 记录详细日志，快速发现异常

### 风险 4：Webhook 可靠性问题

**风险**：外部系统不稳定可能导致 Webhook 失败

**缓解措施**：
- 实现重试机制（最多 3 次）
- 记录失败日志，提供手动重试接口
- 连续失败自动禁用 Webhook 并告警
- 提供 Webhook 测试功能

### 权衡 1：功能完整性 vs 开发周期

**权衡**：完整实现所有功能需要较长时间

**决策**：分阶段实施
- 第一阶段：核心功能（认证、游戏嵌入、基础数据查询）
- 第二阶段：高级功能（Webhook、统计接口、批量操作）
- 第三阶段：优化和扩展（多语言 SDK、高级文档）

### 权衡 2：性能 vs 功能丰富度

**权衡**：某些功能（如实时推送）会增加服务器负担

**决策**：
- 提供配置开关，允许禁用非必需功能
- 使用异步处理降低实时影响
- 建议外部系统使用拉取模式为主，推送为辅

## 实施计划（6周完成）

### Week 1：安全加固（必须做）

**目标**：修复现有安全漏洞，为 API 打好基础

- [ ] 修复所有 SQL 注入漏洞（改为 PDO 预处理）
- [ ] 移除已知后门代码
- [ ] 创建 4 张新表（InnoDB 引擎）
- [ ] 安装 Redis（用于速率限制）
- [ ] 配置 HTTPS 证书

**交付物**：
- 数据库迁移脚本
- 安全修复代码
- Redis 环境

### Week 2：认证和基础框架

**目标**：搭建 API 基础架构

- [ ] 创建目录结构（`web/api/v1/`、`web/embed/`）
- [ ] 实现签名验证中间件
- [ ] API Key 管理后台界面（创建、禁用、查看）
- [ ] 统一响应格式封装
- [ ] 速率限制（Redis 实现）

**交付物**：
- API 基础框架
- API Key 管理界面

### Week 3：核心查询接口

**目标**：实现核心数据查询功能

- [ ] GET /api/v1/users/{id} - 查询用户
- [ ] GET /api/v1/users/{id}/balance - 查询余额
- [ ] GET /api/v1/bets - 查询投注
- [ ] GET /api/v1/transactions - 查询流水
- [ ] GET /api/v1/lottery-results/latest - 最新开奖
- [ ] GET /api/v1/lottery-results - 历史开奖

**交付物**：
- 6 个核心查询接口
- 接口测试脚本

### Week 4：游戏窗口嵌入 + Webhook

**目标**：完成嵌入和推送功能

- [ ] `web/embed/game.php` 页面
- [ ] Token 生成和验证
- [ ] POST /api/v1/webhooks - 创建订阅
- [ ] GET /api/v1/webhooks - 查询订阅
- [ ] DELETE /api/v1/webhooks/{id} - 删除订阅
- [ ] Webhook 发送逻辑（投注、中奖、充值、提款事件）

**交付物**：
- 游戏嵌入页面
- Webhook 系统

### Week 5：文档和测试

**目标**：编写文档并测试

- [ ] 编写 API.md 文档（认证、接口列表、错误码）
- [ ] 导出 Postman Collection
- [ ] 编写快速入门教程（10 分钟教程）
- [ ] 接口功能测试
- [ ] 签名验证测试
- [ ] Webhook 测试
- [ ] 性能测试（基础负载测试）

**交付物**：
- API 文档
- Postman Collection
- 测试报告

### Week 6：上线和联调

**目标**：部署并与外部系统联调

- [ ] 部署到生产环境
- [ ] 配置防火墙和 HTTPS
- [ ] 与第一个外部系统联调
- [ ] 问题修复
- [ ] 正式上线

**交付物**：
- 生产环境
- 联调报告
- 上线公告

### 回滚策略

**回滚条件**：
- 发现严重安全漏洞
- API 导致现有系统不稳定
- 数据一致性问题

**回滚步骤**：
1. 禁用所有 API 接口（返回维护页面）
2. 回滚数据库迁移（如有必要）
3. 恢复代码到上一个稳定版本
4. 通知所有接入的外部系统

**数据备份**：
- 每天自动备份数据库
- 部署前手动创建备份点
- 保留至少 7 天的备份数据

## 待解决问题

### 问题 1：数据隔离方案？

**背景**：不同外部系统的数据需要隔离。

**MVP 决策**：使用逻辑隔离
- 每个 API Key 关联特定的代理 ID
- 查询时自动过滤只返回该代理下的数据
- 后期有需求再考虑物理隔离

### 问题 2：是否需要测试环境？

**背景**：外部系统需要测试环境。

**MVP 决策**：使用测试 API Key
- 创建 API Key 时标记为"测试"或"生产"
- 测试 Key 访问测试数据（手动创建一些测试用户和订单）
- 不需要独立的沙箱环境（节省成本）

### 问题 3：监控和告警？

**MVP 决策**：先看日志
- 记录到 api_logs 表
- 定期（每天）检查错误日志
- 后期有需求再接入专业监控工具

### 问题 4：后期优化优先级？

**待与领导确认**：
1. IP 白名单
2. 批量查询接口
3. 统计分析接口
4. WebSocket 实时推送
5. 多语言 SDK

根据外部系统的实际需求决定优先级
