# 生产环境部署指南

版本: v1.0
更新日期: 2026-02-03

---

## 📋 目录

1. [部署前准备](#部署前准备)
2. [环境要求](#环境要求)
3. [部署步骤](#部署步骤)
4. [安全配置](#安全配置)
5. [性能优化](#性能优化)
6. [监控告警](#监控告警)
7. [故障排查](#故障排查)

---

## 部署前准备

### 1. 运行测试

在部署到生产环境前，务必运行所有测试：

```bash
# 功能测试
php tests/api_test.php

# 安全测试
php tests/security_test.php

# 性能测试
php tests/performance_test.php
```

确保所有测试通过后再继续部署。

### 2. 备份数据库

```bash
# 备份数据库
mysqldump -u root -p lottery > backup_$(date +%Y%m%d_%H%M%S).sql

# 验证备份文件
ls -lh backup_*.sql
```

### 3. 检查配置文件

确保以下配置文件已正确设置：

- `web/data/config.inc.php` - 数据库配置
- `.htaccess` - Apache 重写规则
- `php.ini` - PHP 配置

---

## 环境要求

### 服务器要求

| 组件 | 最低要求 | 推荐配置 |
|------|---------|---------|
| 操作系统 | Linux (Ubuntu 20.04+) | Ubuntu 22.04 LTS |
| PHP | 7.4+ | 8.1+ |
| MySQL | 5.7+ | 8.0+ |
| Redis | 5.0+ | 7.0+ |
| Web 服务器 | Apache 2.4+ / Nginx 1.18+ | Nginx 1.24+ |
| 内存 | 2GB | 4GB+ |
| CPU | 2 核 | 4 核+ |
| 磁盘 | 20GB | 50GB+ SSD |

### PHP 扩展

必需扩展：
```bash
php -m | grep -E 'mysqli|json|openssl|mbstring|curl'
```

- mysqli - 数据库连接
- json - JSON 处理
- openssl - 加密功能
- mbstring - 多字节字符串
- curl - HTTP 请求
- redis - Redis 缓存（可选但推荐）

---

## 部署步骤

### 1. 上传代码

```bash
# 使用 Git 部署（推荐）
cd /var/www/
git clone https://github.com/Xia0321/lottery.git
cd lottery

# 或者使用 FTP/SFTP 上传
```

### 2. 设置文件权限

```bash
# 设置 Web 服务器用户（www-data 或 nginx）
chown -R www-data:www-data /var/www/lottery

# 设置目录权限
find /var/www/lottery -type d -exec chmod 755 {} \;

# 设置文件权限
find /var/www/lottery -type f -exec chmod 644 {} \;

# 日志目录需要写权限
chmod 777 /var/www/lottery/logs
```

### 3. 配置数据库

```bash
# 创建数据库
mysql -u root -p << EOF
CREATE DATABASE IF NOT EXISTS lottery CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
CREATE USER 'lottery_user'@'localhost' IDENTIFIED BY 'strong_password_here';
GRANT ALL PRIVILEGES ON lottery.* TO 'lottery_user'@'localhost';
FLUSH PRIVILEGES;
EOF

# 导入数据库结构
mysql -u lottery_user -p lottery < database.sql

# 运行 API 表迁移
php migrations/run_migration.php
```

### 4. 配置文件设置

编辑 `web/data/config.inc.php`：

```php
<?php
// 数据库配置
$db_host = 'localhost';
$db_user = 'lottery_user';
$db_pass = 'strong_password_here';
$db_name = 'lottery';

// API 密钥配置
$config['api_secret'] = 'your_random_secret_key_at_least_32_chars';

// Redis 配置
$config['redis_host'] = '127.0.0.1';
$config['redis_port'] = 6379;

// 环境设置
$config['debug'] = false; // 生产环境设为 false
$config['log_level'] = 'error'; // 只记录错误日志
```

### 5. Web 服务器配置

#### Nginx 配置示例

创建 `/etc/nginx/sites-available/lottery`：

```nginx
server {
    listen 80;
    server_name api.yourdomain.com;
    root /var/www/lottery/web;
    index index.php;

    # 强制 HTTPS
    return 301 https://$server_name$request_uri;
}

server {
    listen 443 ssl http2;
    server_name api.yourdomain.com;
    root /var/www/lottery/web;
    index index.php;

    # SSL 证书
    ssl_certificate /etc/ssl/certs/your_cert.crt;
    ssl_certificate_key /etc/ssl/private/your_key.key;
    ssl_protocols TLSv1.2 TLSv1.3;
    ssl_ciphers HIGH:!aNULL:!MD5;

    # 日志
    access_log /var/log/nginx/lottery_access.log;
    error_log /var/log/nginx/lottery_error.log;

    # PHP 处理
    location ~ \.php$ {
        fastcgi_pass unix:/var/run/php/php8.1-fpm.sock;
        fastcgi_index index.php;
        fastcgi_param SCRIPT_FILENAME $document_root$fastcgi_script_name;
        include fastcgi_params;
    }

    # API 路由
    location /api/ {
        try_files $uri $uri/ /api/index.php?$query_string;
    }

    # 安全头
    add_header X-Frame-Options "SAMEORIGIN" always;
    add_header X-Content-Type-Options "nosniff" always;
    add_header X-XSS-Protection "1; mode=block" always;
    add_header Strict-Transport-Security "max-age=31536000; includeSubDomains" always;

    # 隐藏版本信息
    server_tokens off;

    # 禁止访问隐藏文件
    location ~ /\. {
        deny all;
    }
}
```

启用配置：

```bash
ln -s /etc/nginx/sites-available/lottery /etc/nginx/sites-enabled/
nginx -t
systemctl reload nginx
```

#### Apache 配置示例

创建 `/etc/apache2/sites-available/lottery.conf`：

```apache
<VirtualHost *:80>
    ServerName api.yourdomain.com
    Redirect permanent / https://api.yourdomain.com/
</VirtualHost>

<VirtualHost *:443>
    ServerName api.yourdomain.com
    DocumentRoot /var/www/lottery/web

    SSLEngine on
    SSLCertificateFile /etc/ssl/certs/your_cert.crt
    SSLCertificateKeyFile /etc/ssl/private/your_key.key

    <Directory /var/www/lottery/web>
        Options -Indexes +FollowSymLinks
        AllowOverride All
        Require all granted
    </Directory>

    # 日志
    ErrorLog ${APACHE_LOG_DIR}/lottery_error.log
    CustomLog ${APACHE_LOG_DIR}/lottery_access.log combined
</VirtualHost>
```

启用配置：

```bash
a2ensite lottery
a2enmod ssl rewrite headers
systemctl reload apache2
```

### 6. 配置 Redis

编辑 `/etc/redis/redis.conf`：

```conf
# 绑定到本地
bind 127.0.0.1

# 设置密码（可选但推荐）
requirepass your_redis_password

# 持久化
save 900 1
save 300 10
save 60 10000

# 最大内存
maxmemory 256mb
maxmemory-policy allkeys-lru
```

重启 Redis：

```bash
systemctl restart redis
systemctl enable redis
```

---

## 安全配置

### 1. HTTPS 配置

**必须使用 HTTPS**，可使用 Let's Encrypt 免费证书：

```bash
# 安装 Certbot
apt install certbot python3-certbot-nginx

# 获取证书
certbot --nginx -d api.yourdomain.com

# 自动续期
certbot renew --dry-run
```

### 2. 防火墙配置

```bash
# 使用 UFW
ufw allow 22/tcp    # SSH
ufw allow 80/tcp    # HTTP
ufw allow 443/tcp   # HTTPS
ufw enable

# 限制 SSH 访问
ufw limit 22/tcp
```

### 3. API Key 管理

生成强随机 API Key：

```bash
# 生成 API Key
php -r "echo 'api_' . bin2hex(random_bytes(16)) . PHP_EOL;"

# 生成 API Secret
php -r "echo bin2hex(random_bytes(32)) . PHP_EOL;"
```

在数据库中创建 API Key：

```sql
INSERT INTO api_keys (api_key, api_secret, partner_name, agent_id, rate_limit, status)
VALUES ('api_xxx', 'secret_xxx', 'Partner Name', 1, 100, 1);
```

### 4. IP 白名单（可选）

编辑 API Key 配置，添加允许的 IP 地址：

```sql
UPDATE api_keys SET ip_whitelist = '1.2.3.4,5.6.7.8' WHERE id = 1;
```

### 5. 日志安全

```bash
# 设置日志轮转
cat > /etc/logrotate.d/lottery << EOF
/var/www/lottery/logs/*.log {
    daily
    rotate 30
    compress
    delaycompress
    missingok
    notifempty
    create 0640 www-data www-data
}
EOF

# 测试配置
logrotate -d /etc/logrotate.d/lottery
```

---

## 性能优化

### 1. PHP 优化

编辑 `php.ini`：

```ini
# OPcache 配置
opcache.enable=1
opcache.memory_consumption=128
opcache.interned_strings_buffer=8
opcache.max_accelerated_files=10000
opcache.revalidate_freq=2

# 性能调优
max_execution_time=30
memory_limit=256M
post_max_size=20M
upload_max_filesize=20M

# 错误报告（生产环境）
display_errors=Off
log_errors=On
error_log=/var/log/php-errors.log
```

### 2. MySQL 优化

编辑 `/etc/mysql/my.cnf`：

```ini
[mysqld]
# InnoDB 优化
innodb_buffer_pool_size=1G
innodb_log_file_size=256M
innodb_flush_method=O_DIRECT

# 查询缓存
query_cache_type=1
query_cache_size=64M

# 连接池
max_connections=200
thread_cache_size=16

# 慢查询日志
slow_query_log=1
slow_query_log_file=/var/log/mysql/slow.log
long_query_time=2
```

添加索引优化查询：

```sql
-- 用户表
CREATE INDEX idx_fid ON x_user(fid);
CREATE INDEX idx_status ON x_user(status);

-- 投注表
CREATE INDEX idx_userid_created ON x_bet(userid, created_at);
CREATE INDEX idx_status ON x_bet(status);

-- 交易表
CREATE INDEX idx_userid_created ON x_money_log(userid, created_at);

-- 开奖表
CREATE INDEX idx_gameid_created ON x_lottery_result(gameid, created_at);
```

### 3. Redis 优化

```conf
# 持久化策略
appendonly yes
appendfsync everysec

# 内存管理
maxmemory 512mb
maxmemory-policy allkeys-lru

# 连接优化
timeout 300
tcp-keepalive 60
```

### 4. 开启 Gzip 压缩

Nginx 配置：

```nginx
gzip on;
gzip_vary on;
gzip_proxied any;
gzip_comp_level 6;
gzip_types text/plain text/css text/xml text/javascript application/json application/javascript application/xml+rss;
```

---

## 监控告警

### 1. 健康检查端点

创建 `web/api/health.php`：

```php
<?php
header('Content-Type: application/json');

$health = [
    'status' => 'healthy',
    'timestamp' => time(),
    'checks' => []
];

// 数据库检查
try {
    $pdo = new PDO("mysql:host=localhost;dbname=lottery", "user", "pass");
    $health['checks']['database'] = 'ok';
} catch (Exception $e) {
    $health['status'] = 'unhealthy';
    $health['checks']['database'] = 'failed';
}

// Redis 检查
try {
    $redis = new Redis();
    $redis->connect('127.0.0.1', 6379);
    $redis->ping();
    $health['checks']['redis'] = 'ok';
} catch (Exception $e) {
    $health['checks']['redis'] = 'degraded';
}

http_response_code($health['status'] === 'healthy' ? 200 : 503);
echo json_encode($health);
```

### 2. 日志监控

使用脚本监控错误日志：

```bash
#!/bin/bash
# /usr/local/bin/check_api_errors.sh

ERROR_LOG="/var/www/lottery/logs/api_errors.log"
LAST_CHECK="/tmp/last_api_error_check"

if [ ! -f "$LAST_CHECK" ]; then
    touch "$LAST_CHECK"
fi

# 查找新错误
NEW_ERRORS=$(find "$ERROR_LOG" -newer "$LAST_CHECK" -exec grep -c "ERROR\|CRITICAL" {} \;)

if [ "$NEW_ERRORS" -gt 10 ]; then
    echo "警告: 发现 $NEW_ERRORS 个新错误" | mail -s "API 错误告警" admin@example.com
fi

touch "$LAST_CHECK"
```

添加到 crontab：

```bash
*/5 * * * * /usr/local/bin/check_api_errors.sh
```

### 3. 性能监控

推荐使用以下工具：

- **Prometheus + Grafana** - 指标收集和可视化
- **New Relic** - APM 性能监控
- **Datadog** - 全栈监控
- **Sentry** - 错误追踪

---

## 故障排查

### 常见问题

#### 1. API 返回 500 错误

检查步骤：
```bash
# 查看 PHP 错误日志
tail -f /var/log/php-errors.log

# 查看 Web 服务器日志
tail -f /var/log/nginx/lottery_error.log

# 查看 API 日志
tail -f /var/www/lottery/logs/api_errors.log
```

#### 2. 数据库连接失败

```bash
# 检查 MySQL 状态
systemctl status mysql

# 测试连接
mysql -u lottery_user -p -e "SELECT 1"

# 查看连接数
mysql -u root -p -e "SHOW PROCESSLIST"
```

#### 3. Redis 连接失败

```bash
# 检查 Redis 状态
systemctl status redis

# 测试连接
redis-cli ping

# 查看 Redis 日志
tail -f /var/log/redis/redis-server.log
```

#### 4. 性能下降

```bash
# 查看服务器负载
top
htop

# 查看 MySQL 慢查询
mysql -u root -p -e "SELECT * FROM mysql.slow_log ORDER BY start_time DESC LIMIT 10"

# 查看 PHP-FPM 状态
curl http://localhost/status

# 查看 Redis 统计
redis-cli info stats
```

---

## 回滚计划

如果部署出现问题，按以下步骤回滚：

```bash
# 1. 切换到上一个版本
cd /var/www/lottery
git checkout <previous-commit-hash>

# 2. 恢复数据库（如有变更）
mysql -u root -p lottery < backup_YYYYMMDD_HHMMSS.sql

# 3. 重启服务
systemctl restart php8.1-fpm
systemctl restart nginx

# 4. 清除缓存
redis-cli FLUSHALL
```

---

## 联系支持

- **技术支持**: support@example.com
- **紧急联系**: +86-xxx-xxxx-xxxx
- **GitHub Issues**: https://github.com/Xia0321/lottery/issues

---

**部署检查清单**

- [ ] 所有测试通过
- [ ] 数据库已备份
- [ ] HTTPS 已配置
- [ ] 防火墙已设置
- [ ] API Key 已生成
- [ ] Redis 已配置
- [ ] 日志轮转已设置
- [ ] 监控已启用
- [ ] 性能已优化
- [ ] 回滚计划已准备

部署完成后，务必进行完整的功能测试验证！
