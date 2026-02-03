# API 测试套件

完整的 API 测试工具集，包含功能测试、安全测试和性能测试。

---

## 📋 测试脚本

### 1. 功能测试 (`api_test.php`)

测试所有 API 端点的基本功能。

**测试内容：**
- ✅ API 认证机制
- ✅ Test 端点
- ✅ 用户查询接口
- ✅ 投注记录查询
- ✅ 交易记录查询
- ✅ 开奖结果查询
- ✅ 游戏嵌入 Token 生成
- ✅ Webhook 创建和管理

**运行方法：**
```bash
php tests/api_test.php
```

**配置：**
修改脚本中的配置部分：
```php
$config = [
    'base_url' => 'http://localhost/lottery/web',
    'api_key' => 'your_api_key',
    'api_secret' => 'your_api_secret'
];
```

### 2. 安全测试 (`security_test.php`)

测试 API 的安全防护措施。

**测试内容：**
- 🔒 SQL 注入防护
- 🔒 XSS 防护
- 🔒 认证绕过防护
- 🔒 速率限制
- 🔒 Token 安全性
- 🔒 输入验证

**运行方法：**
```bash
php tests/security_test.php
```

**注意事项：**
- 速率限制测试会发送大量请求，需要等待 60 秒重置
- 建议在测试环境运行，避免影响生产环境

### 3. 性能测试 (`performance_test.php`)

测试 API 的性能和响应时间。

**测试内容：**
- ⚡ 响应时间测试
- ⚡ Redis 缓存效果
- ⚡ 并发性能
- ⚡ 数据库查询性能

**运行方法：**
```bash
php tests/performance_test.php
```

**注意事项：**
- 缓存测试需要等待 35 秒（缓存 TTL + 5秒）
- 并发测试会同时发送多个请求

---

## 🚀 快速开始

### 1. 安装依赖

确保 PHP 环境已安装必需扩展：

```bash
php -m | grep -E 'curl|json|mysqli|openssl'
```

### 2. 配置 API 凭证

在数据库中创建测试 API Key：

```sql
INSERT INTO api_keys (api_key, api_secret, partner_name, agent_id, rate_limit, status, created_at)
VALUES (
    'test_api_key_12345678',
    'test_secret_87654321abcdefgh',
    '测试合作伙伴',
    1,
    100,
    1,
    NOW()
);
```

### 3. 运行所有测试

```bash
# 功能测试
php tests/api_test.php

# 安全测试
php tests/security_test.php

# 性能测试
php tests/performance_test.php
```

---

## 📊 测试结果示例

### 功能测试

```
=================================
     API 功能测试开始
=================================

📋 测试: API 认证
  ✅ 缺少签名应返回 401/400
  ✅ 错误签名应返回 401
  ✅ 过期时间戳应返回 401

📋 测试: Test 端点
  ✅ Test 端点应返回成功
  ✅ Test 端点应返回自定义消息
  ✅ Test 端点应显示已认证

...

=================================
        测试结果汇总
=================================

总测试数: 25
通过: 24 ✅
失败: 1 ❌
通过率: 96.0%
```

### 安全测试

```
=================================
     API 安全测试开始
=================================

📋 测试: SQL 注入防护
  ✅ SQL 注入攻击应被拒绝: 1' OR '1'='1
  ✅ SQL 注入攻击应被拒绝: 1; DROP TABLE users--
  ✅ SQL 注入攻击应被拒绝: 1' UNION SELECT NULL--

...

=================================
      安全测试结果汇总
=================================

总测试数: 18
通过: 18 ✅
失败: 0 ❌
通过率: 100.0%

✅ 所有安全测试通过！
```

### 性能测试

```
=================================
     API 性能测试开始
=================================

📋 测试: 响应时间

  ✅ /api/v1/test.php: 45.23 ms (平均)
  ✅ /api/v1/users/1: 67.89 ms (平均)
  ✅ /api/v1/bets: 123.45 ms (平均)

📋 测试: Redis 缓存性能

  冷缓存: 145.67 ms
  热缓存: 12.34 ms
  性能提升: 91.5%
  ✅ 缓存效果: 优秀

...

=================================
      性能测试结果汇总
=================================

响应时间统计：
  平均: 89.45 ms
  最快: 45.23 ms
  最慢: 156.78 ms

缓存效果：
  性能提升: 91.5%
  状态: 优秀

并发性能：
  吞吐量: 45.6 请求/秒
  平均响应: 438.6 ms

整体评级: 🌟 优秀 (92.3%)
```

---

## 🔧 故障排查

### 测试失败常见原因

1. **连接失败**
   - 检查 `base_url` 是否正确
   - 确认 Web 服务器已启动

2. **认证失败**
   - 检查 API Key 和 Secret 是否正确
   - 确认 API Key 在数据库中已启用

3. **数据库错误**
   - 确认数据库已运行
   - 检查数据库表是否存在

4. **Redis 错误**
   - 确认 Redis 已启动
   - 检查 Redis 连接配置

### 调试技巧

启用详细输出：

```bash
# 设置 PHP 错误报告
php -d display_errors=On -d error_reporting=E_ALL tests/api_test.php
```

查看 API 日志：

```bash
tail -f /path/to/lottery/logs/api_errors.log
```

---

## 📝 自定义测试

### 添加新测试

在测试类中添加新的测试方法：

```php
private function testMyNewFeature() {
    echo "📋 测试: 我的新功能\n";

    $response = $this->request('GET', '/api/v1/my-endpoint');

    $this->assert(
        'my_test_name',
        $response['success'] === true,
        "功能描述"
    );

    echo "\n";
}
```

在 `runAllTests()` 方法中调用：

```php
public function runAllTests() {
    // ... 现有测试
    $this->testMyNewFeature();
    // ...
}
```

---

## 🎯 最佳实践

1. **定期运行测试**
   - 每次代码更改后运行功能测试
   - 每周运行完整测试套件
   - 部署前必须运行所有测试

2. **CI/CD 集成**
   ```yaml
   # GitHub Actions 示例
   - name: Run API Tests
     run: |
       php tests/api_test.php
       php tests/security_test.php
   ```

3. **测试环境隔离**
   - 使用独立的测试数据库
   - 使用专用的 API Key
   - 不要在生产环境运行测试

4. **监控测试结果**
   - 记录测试结果
   - 追踪性能趋势
   - 及时修复失败的测试

---

## 📚 相关文档

- [API 文档](../docs/API.md)
- [快速入门指南](../docs/QUICK_START.md)
- [安全文档](../SECURITY.md)
- [部署指南](../docs/DEPLOYMENT.md)

---

## 🆘 获取帮助

如果测试遇到问题：

1. 查看 [故障排查](#故障排查) 部分
2. 检查 API 日志文件
3. 在 GitHub 提交 Issue: https://github.com/Xia0321/lottery/issues

---

**测试检查清单**

- [ ] 所有依赖已安装
- [ ] API 凭证已配置
- [ ] 数据库连接正常
- [ ] Redis 连接正常
- [ ] 功能测试通过
- [ ] 安全测试通过
- [ ] 性能测试通过

祝测试顺利！ 🎉
