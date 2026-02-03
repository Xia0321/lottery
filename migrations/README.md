# 数据库迁移说明

本目录包含数据库迁移脚本，用于创建和更新数据库表结构。

## 迁移脚本列表

| 文件名 | 说明 | 创建日期 |
|--------|------|---------|
| `001_create_api_tables.sql` | 创建 API 相关表 (api_keys, api_logs, webhooks, webhook_logs) | 2026-02-03 |

## 执行迁移

### 方法 1: 使用 MySQL 命令行

```bash
# 进入 migrations 目录
cd D:\lottery\migrations

# 执行迁移
mysql -u lottery_user -p lottery < 001_create_api_tables.sql

# 或者指定主机
mysql -h localhost -u lottery_user -p lottery < 001_create_api_tables.sql
```

### 方法 2: 使用 phpMyAdmin

1. 登录 phpMyAdmin
2. 选择数据库 `lottery`
3. 点击"SQL"选项卡
4. 复制 `001_create_api_tables.sql` 的内容
5. 粘贴到 SQL 编辑器
6. 点击"执行"

### 方法 3: 使用 PHP 脚本

```bash
cd D:\lottery\migrations
php run_migration.php 001_create_api_tables.sql
```

## 验证迁移

执行以下 SQL 验证表是否创建成功:

```sql
-- 查看新建的表
SHOW TABLES LIKE 'api_%';
SHOW TABLES LIKE 'webhook%';

-- 查看表结构
DESCRIBE api_keys;
DESCRIBE api_logs;
DESCRIBE webhooks;
DESCRIBE webhook_logs;

-- 查看测试数据
SELECT * FROM api_keys;
```

预期输出:
```
+--------------------+
| Tables_in_lottery  |
+--------------------+
| api_keys           |
| api_logs           |
+--------------------+

+----------------------+
| Tables_in_lottery    |
+----------------------+
| webhook_logs         |
| webhooks             |
+----------------------+
```

## 回滚迁移

如需回滚迁移，执行以下 SQL:

```sql
DROP TABLE IF EXISTS `webhook_logs`;
DROP TABLE IF EXISTS `webhooks`;
DROP TABLE IF EXISTS `api_logs`;
DROP TABLE IF EXISTS `api_keys`;
```

## 测试数据

迁移脚本会自动插入一个测试 API Key:

- **API Key**: `test_api_key_12345678`
- **API Secret**: `test_secret_87654321abcdefgh`
- **关联代理**: ID = 1
- **合作伙伴**: 测试合作伙伴

⚠️ **警告**: 生产环境部署时请删除测试数据!

```sql
-- 删除测试数据
DELETE FROM api_keys WHERE api_key = 'test_api_key_12345678';
```

## 表结构说明

### api_keys 表
存储外部系统的 API Key 和 Secret。

**重要字段**:
- `api_key`: 公开的 API Key
- `api_secret`: 保密的 API Secret (用于签名)
- `agent_id`: 关联的代理 ID (数据隔离)
- `rate_limit`: 速率限制 (每分钟请求数)
- `status`: 0=禁用, 1=启用

### api_logs 表
记录所有 API 请求和响应。

**重要字段**:
- `endpoint`: API 端点
- `method`: HTTP 方法
- `response_code`: HTTP 响应码
- `execution_time`: 执行时间 (毫秒)
- `ip_address`: 客户端 IP

### webhooks 表
存储 Webhook 订阅配置。

**支持的事件类型**:
- `bet.created`: 投注创建
- `bet.settled`: 投注结算
- `deposit.completed`: 充值完成
- `withdrawal.completed`: 提款完成

### webhook_logs 表
记录 Webhook 推送日志。

**状态类型**:
- `pending`: 等待发送
- `success`: 发送成功
- `failed`: 发送失败

## 注意事项

1. **备份数据库**: 执行迁移前务必备份数据库
2. **权限检查**: 确保 MySQL 用户有 CREATE, ALTER, INSERT 权限
3. **字符集**: 所有表使用 utf8mb4 字符集
4. **存储引擎**: 所有表使用 InnoDB 引擎
5. **索引优化**: 已为常用查询字段创建索引

## 常见问题

### Q: 执行迁移时报错 "Table already exists"

A: 表已存在，可以跳过此迁移或先删除现有表。

### Q: 如何修改表结构？

A: 创建新的迁移脚本（如 `002_xxx.sql`），使用 ALTER TABLE 语句。

### Q: 如何生成 API Key？

A: 使用管理后台的 API Key 管理功能，或使用以下 PHP 代码:

```php
$apiKey = 'api_' . bin2hex(random_bytes(16));
$apiSecret = bin2hex(random_bytes(32));
```

## 相关文档

- [API 文档](../docs/API.md)
- [安全文档](../SECURITY.md)
- [集成指南](../INTEGRATION_GUIDE.md)
