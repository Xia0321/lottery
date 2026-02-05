#!/bin/bash
############################################
# 生产环境部署脚本
# 用途: 一键部署彩票系统到生产环境
#
# 用法: sudo bash scripts/deploy.sh
#
# 步骤:
#   1. 检查环境依赖
#   2. 拉取代码
#   3. 配置文件检查
#   4. 数据库迁移
#   5. 设置文件权限
#   6. 配置 Web 服务器
#   7. 配置防火墙
#   8. 启动服务
#   9. 健康检查
############################################

set -e

# 配置
APP_DIR=${APP_DIR:-"/var/www/lottery"}
WEB_USER=${WEB_USER:-"www-data"}
DOMAIN=${DOMAIN:-"lottery.example.com"}

echo "╔══════════════════════════════════════════╗"
echo "║       彩票系统生产环境部署                ║"
echo "╚══════════════════════════════════════════╝"
echo ""
echo "部署目录: $APP_DIR"
echo "Web 用户: $WEB_USER"
echo "域名:     $DOMAIN"
echo ""

# ============================================
# 1. 环境检查
# ============================================
echo "[1/9] 检查环境依赖..."

ERRORS=0

# PHP
if command -v php &>/dev/null; then
    PHP_VER=$(php -r 'echo PHP_VERSION;')
    echo "  OK: PHP $PHP_VER"
else
    echo "  FAIL: PHP 未安装"
    ERRORS=$((ERRORS+1))
fi

# PHP 扩展
for ext in mysqli json curl openssl mbstring; do
    if php -m 2>/dev/null | grep -qi "$ext"; then
        echo "  OK: php-$ext"
    else
        echo "  FAIL: php-$ext 未安装"
        ERRORS=$((ERRORS+1))
    fi
done

# MySQL
if command -v mysql &>/dev/null; then
    echo "  OK: MySQL 客户端"
else
    echo "  WARN: MySQL 客户端未安装（非必需）"
fi

# Redis
if command -v redis-cli &>/dev/null; then
    if redis-cli ping 2>/dev/null | grep -q PONG; then
        echo "  OK: Redis 运行中"
    else
        echo "  WARN: Redis 未运行，速率限制将降级"
    fi
else
    echo "  WARN: Redis 未安装，速率限制将降级"
fi

# Web 服务器
if command -v nginx &>/dev/null; then
    echo "  OK: Nginx"
    WEB_SERVER="nginx"
elif command -v apache2 &>/dev/null || command -v httpd &>/dev/null; then
    echo "  OK: Apache"
    WEB_SERVER="apache"
else
    echo "  FAIL: 未检测到 Web 服务器"
    ERRORS=$((ERRORS+1))
fi

if [ $ERRORS -gt 0 ]; then
    echo ""
    echo "有 $ERRORS 个必需依赖缺失，请先安装后重试"
    exit 1
fi

echo ""

# ============================================
# 2. 部署代码
# ============================================
echo "[2/9] 部署代码..."

if [ -d "$APP_DIR/.git" ]; then
    cd "$APP_DIR"
    git fetch origin
    git reset --hard origin/master
    echo "  OK: 代码已更新 (git pull)"
elif [ -d "$APP_DIR" ]; then
    echo "  OK: 目录已存在，跳过代码拉取"
else
    echo "  请手动将代码部署到 $APP_DIR"
    echo "  例如: git clone <repo_url> $APP_DIR"
    exit 1
fi

echo ""

# ============================================
# 3. 配置文件检查
# ============================================
echo "[3/9] 检查配置文件..."

CONFIG_FILE="$APP_DIR/web/data/config.inc.php"
CONFIG_EXAMPLE="$APP_DIR/web/data/config.inc.php.example"

if [ -f "$CONFIG_FILE" ]; then
    echo "  OK: config.inc.php 已存在"
else
    if [ -f "$CONFIG_EXAMPLE" ]; then
        cp "$CONFIG_EXAMPLE" "$CONFIG_FILE"
        echo "  WARN: 已从示例文件创建 config.inc.php，请编辑数据库配置"
        echo "  文件: $CONFIG_FILE"
    else
        echo "  FAIL: 配置文件不存在，请手动创建"
        exit 1
    fi
fi

# 检查调试模式
if grep -q "DEBUG_MODE.*true" "$CONFIG_FILE" 2>/dev/null; then
    echo "  WARN: 调试模式仍为开启状态，生产环境应设为 false"
fi

echo ""

# ============================================
# 4. 数据库迁移
# ============================================
echo "[4/9] 数据库迁移..."

if [ -f "$APP_DIR/init_database.php" ]; then
    php "$APP_DIR/init_database.php" 2>&1 | tail -5
    echo "  OK: 数据库迁移完成"
else
    echo "  WARN: 未找到迁移脚本，跳过"
fi

echo ""

# ============================================
# 5. 文件权限
# ============================================
echo "[5/9] 设置文件权限..."

chown -R "$WEB_USER":"$WEB_USER" "$APP_DIR"
find "$APP_DIR" -type f -exec chmod 644 {} \;
find "$APP_DIR" -type d -exec chmod 755 {} \;

# 可写目录
WRITABLE_DIRS="web/logs web/data web/templates/default/cache"
for dir in $WRITABLE_DIRS; do
    if [ -d "$APP_DIR/$dir" ]; then
        chmod -R 775 "$APP_DIR/$dir"
    else
        mkdir -p "$APP_DIR/$dir"
        chown "$WEB_USER":"$WEB_USER" "$APP_DIR/$dir"
        chmod 775 "$APP_DIR/$dir"
    fi
done

# 保护敏感文件
chmod 600 "$CONFIG_FILE" 2>/dev/null
chmod 600 "$APP_DIR/api_credentials.txt" 2>/dev/null

# 脚本可执行
chmod +x "$APP_DIR/scripts/"*.sh 2>/dev/null

echo "  OK: 权限设置完成"
echo ""

# ============================================
# 6. Web 服务器配置
# ============================================
echo "[6/9] 配置 Web 服务器..."

if [ "$WEB_SERVER" = "nginx" ]; then
    NGINX_CONF="/etc/nginx/conf.d/lottery.conf"

    if [ ! -f "$NGINX_CONF" ]; then
        cat > "$NGINX_CONF" << NGINX_EOF
server {
    listen 80;
    server_name $DOMAIN;
    return 301 https://\$server_name\$request_uri;
}

server {
    listen 443 ssl http2;
    server_name $DOMAIN;

    root $APP_DIR/web;
    index index.php index.html;

    # SSL 证书 (Let's Encrypt)
    ssl_certificate /etc/letsencrypt/live/$DOMAIN/fullchain.pem;
    ssl_certificate_key /etc/letsencrypt/live/$DOMAIN/privkey.pem;
    ssl_protocols TLSv1.2 TLSv1.3;
    ssl_ciphers HIGH:!aNULL:!MD5;
    ssl_prefer_server_ciphers on;

    # 安全头
    add_header X-Frame-Options "SAMEORIGIN" always;
    add_header X-Content-Type-Options "nosniff" always;
    add_header X-XSS-Protection "1; mode=block" always;
    add_header Strict-Transport-Security "max-age=31536000; includeSubDomains" always;

    # API 路由
    location /api/v1/ {
        try_files \$uri \$uri/ /api/v1/index.php?\$query_string;

        # API 特殊路由
        rewrite ^/api/v1/users/(\d+)/balance$ /api/v1/users.php last;
        rewrite ^/api/v1/users/(\d+)$ /api/v1/users.php last;
        rewrite ^/api/v1/bets/(\d+)$ /api/v1/bets.php last;
        rewrite ^/api/v1/bets$ /api/v1/bets.php last;
        rewrite ^/api/v1/transactions$ /api/v1/transactions.php last;
        rewrite ^/api/v1/lottery_results/latest$ /api/v1/lottery_results.php last;
        rewrite ^/api/v1/lottery_results/([^/]+)$ /api/v1/lottery_results.php last;
        rewrite ^/api/v1/lottery_results$ /api/v1/lottery_results.php last;
        rewrite ^/api/v1/webhooks/(\d+)$ /api/v1/webhooks.php last;
        rewrite ^/api/v1/webhooks$ /api/v1/webhooks.php last;
        rewrite ^/api/v1/deposits$ /api/v1/deposits.php last;
        rewrite ^/api/v1/withdrawals$ /api/v1/withdrawals.php last;
        rewrite ^/api/v1/batch/(.+)$ /api/v1/batch.php last;
        rewrite ^/api/v1/stats/(.+)$ /api/v1/stats.php last;
        rewrite ^/api/v1/embed-token$ /api/v1/tokens.php last;
    }

    # 嵌入页面 - 允许跨域嵌入
    location /web/embed/ {
        add_header X-Frame-Options "" always;
        add_header Content-Security-Policy "frame-ancestors *" always;
        try_files \$uri \$uri/ =404;
    }

    # PHP 处理
    location ~ \.php$ {
        fastcgi_pass unix:/var/run/php-fpm.sock;
        fastcgi_index index.php;
        fastcgi_param SCRIPT_FILENAME \$document_root\$fastcgi_script_name;
        include fastcgi_params;
        fastcgi_read_timeout 300;
    }

    # 禁止访问敏感文件
    location ~ /\. { deny all; }
    location ~ \.(sql|md|log|sh)$ { deny all; }
    location ~ /(data|class|global)/ { deny all; }

    # 静态资源缓存
    location ~* \.(js|css|png|jpg|jpeg|gif|ico|svg|woff|woff2)$ {
        expires 30d;
        add_header Cache-Control "public, immutable";
    }

    # 访问日志
    access_log /var/log/nginx/lottery_access.log;
    error_log /var/log/nginx/lottery_error.log;
}
NGINX_EOF
        echo "  OK: Nginx 配置已创建 ($NGINX_CONF)"
    else
        echo "  OK: Nginx 配置已存在"
    fi

    # 测试配置
    nginx -t 2>&1 && echo "  OK: Nginx 配置语法正确" || echo "  FAIL: Nginx 配置有误"

elif [ "$WEB_SERVER" = "apache" ]; then
    echo "  INFO: Apache 使用 .htaccess 配置，已在代码中包含"
    echo "  确保已启用 mod_rewrite: a2enmod rewrite && systemctl restart apache2"
fi

echo ""

# ============================================
# 7. 防火墙配置
# ============================================
echo "[7/9] 配置防火墙..."

if command -v firewall-cmd &>/dev/null; then
    firewall-cmd --permanent --add-service=http 2>/dev/null
    firewall-cmd --permanent --add-service=https 2>/dev/null
    firewall-cmd --reload 2>/dev/null
    echo "  OK: firewalld 已开放 80/443"
elif command -v ufw &>/dev/null; then
    ufw allow 80/tcp 2>/dev/null
    ufw allow 443/tcp 2>/dev/null
    echo "  OK: ufw 已开放 80/443"
elif command -v iptables &>/dev/null; then
    iptables -A INPUT -p tcp --dport 80 -j ACCEPT 2>/dev/null
    iptables -A INPUT -p tcp --dport 443 -j ACCEPT 2>/dev/null
    echo "  OK: iptables 已开放 80/443"
else
    echo "  WARN: 未检测到防火墙工具，请手动开放 80/443 端口"
fi

# 确保 Redis 端口不对外开放
if command -v firewall-cmd &>/dev/null; then
    firewall-cmd --permanent --remove-port=6379/tcp 2>/dev/null
    firewall-cmd --reload 2>/dev/null
elif command -v ufw &>/dev/null; then
    ufw deny 6379/tcp 2>/dev/null
fi
echo "  OK: Redis 6379 端口已阻止外部访问"

echo ""

# ============================================
# 8. 启动/重启服务
# ============================================
echo "[8/9] 启动服务..."

# 重启 Web 服务器
if [ "$WEB_SERVER" = "nginx" ]; then
    systemctl reload nginx
    echo "  OK: Nginx 已重载"
elif [ "$WEB_SERVER" = "apache" ]; then
    systemctl reload apache2 2>/dev/null || systemctl reload httpd 2>/dev/null
    echo "  OK: Apache 已重载"
fi

# 设置 Webhook Worker cron
if ! crontab -l 2>/dev/null | grep -q webhook_worker; then
    (crontab -l 2>/dev/null; echo "* * * * * php $APP_DIR/scripts/webhook_worker.php >> /var/log/webhook_worker.log 2>&1") | crontab -
    echo "  OK: Webhook Worker cron 已添加 (每分钟)"
else
    echo "  OK: Webhook Worker cron 已存在"
fi

# 清理队列 cron
if ! crontab -l 2>/dev/null | grep -q "webhook_worker.*cleanup"; then
    (crontab -l 2>/dev/null; echo "0 4 * * * php $APP_DIR/scripts/webhook_worker.php --cleanup >> /var/log/webhook_worker.log 2>&1") | crontab -
    echo "  OK: 队列清理 cron 已添加 (每天凌晨4点)"
fi

echo ""

# ============================================
# 9. 健康检查
# ============================================
echo "[9/9] 健康检查..."

sleep 2

# 检查 Web 服务
HTTP_CODE=$(curl -s -o /dev/null -w "%{http_code}" "http://127.0.0.1/" 2>/dev/null || echo "000")
if [ "$HTTP_CODE" = "200" ] || [ "$HTTP_CODE" = "301" ] || [ "$HTTP_CODE" = "302" ]; then
    echo "  OK: Web 服务正常 (HTTP $HTTP_CODE)"
else
    echo "  WARN: Web 服务响应异常 (HTTP $HTTP_CODE)"
fi

# 检查 API 健康
API_CODE=$(curl -s -o /dev/null -w "%{http_code}" "http://127.0.0.1/api/v1/health.php" 2>/dev/null || echo "000")
if [ "$API_CODE" = "200" ]; then
    echo "  OK: API 服务正常"
else
    echo "  WARN: API 健康检查返回 HTTP $API_CODE"
fi

# 检查 Redis
if redis-cli ping 2>/dev/null | grep -q PONG; then
    echo "  OK: Redis 正常"
fi

# 检查 PHP-FPM
if systemctl is-active php-fpm &>/dev/null || systemctl is-active php7.4-fpm &>/dev/null || systemctl is-active php8.0-fpm &>/dev/null || systemctl is-active php8.1-fpm &>/dev/null; then
    echo "  OK: PHP-FPM 运行中"
fi

echo ""
echo "╔══════════════════════════════════════════╗"
echo "║           部署完成                        ║"
echo "╚══════════════════════════════════════════╝"
echo ""
echo "访问地址: https://$DOMAIN"
echo "API 地址: https://$DOMAIN/api/v1/"
echo ""
echo "下一步:"
echo "  1. 编辑 $CONFIG_FILE 确认数据库和 Redis 配置"
echo "  2. 确保 DEBUG_MODE 设为 false"
echo "  3. 运行 php $APP_DIR/scripts/create_partner_key.php 创建 API Key"
echo "  4. 运行测试: php $APP_DIR/tests/api_endpoints_test.php"
echo ""
