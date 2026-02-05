#!/bin/bash
############################################
# HTTPS 证书配置脚本 (Let's Encrypt)
# 用途: 为彩票系统配置 HTTPS
#
# 前置条件:
#   - 已有域名并解析到本服务器
#   - 80 端口可访问
#
# 用法: sudo bash scripts/setup_https.sh your-domain.com
############################################

set -e

DOMAIN=${1:-""}

if [ -z "$DOMAIN" ]; then
    echo "用法: sudo bash scripts/setup_https.sh <域名>"
    echo "示例: sudo bash scripts/setup_https.sh api.lottery.com"
    exit 1
fi

echo "============================================"
echo " HTTPS 证书配置 (Let's Encrypt)"
echo "============================================"
echo "域名: $DOMAIN"
echo ""

# 检测 Web 服务器
WEB_SERVER=""
if command -v nginx &>/dev/null; then
    WEB_SERVER="nginx"
elif command -v apache2 &>/dev/null || command -v httpd &>/dev/null; then
    WEB_SERVER="apache"
fi

echo "Web 服务器: ${WEB_SERVER:-未检测到}"
echo ""

# 1. 安装 Certbot
echo "[1/4] 安装 Certbot..."

if command -v certbot &>/dev/null; then
    echo "OK: Certbot 已安装"
else
    if [ -f /etc/redhat-release ]; then
        yum install -y epel-release
        yum install -y certbot
        if [ "$WEB_SERVER" = "nginx" ]; then
            yum install -y python3-certbot-nginx 2>/dev/null || yum install -y certbot-nginx
        elif [ "$WEB_SERVER" = "apache" ]; then
            yum install -y python3-certbot-apache 2>/dev/null || yum install -y certbot-apache
        fi
    elif [ -f /etc/debian_version ]; then
        apt-get update
        apt-get install -y certbot
        if [ "$WEB_SERVER" = "nginx" ]; then
            apt-get install -y python3-certbot-nginx
        elif [ "$WEB_SERVER" = "apache" ]; then
            apt-get install -y python3-certbot-apache
        fi
    fi
    echo "OK: Certbot 安装完成"
fi

# 2. 申请证书
echo ""
echo "[2/4] 申请 SSL 证书..."

if [ "$WEB_SERVER" = "nginx" ]; then
    certbot --nginx -d "$DOMAIN" --non-interactive --agree-tos --email "admin@${DOMAIN}" --redirect
elif [ "$WEB_SERVER" = "apache" ]; then
    certbot --apache -d "$DOMAIN" --non-interactive --agree-tos --email "admin@${DOMAIN}" --redirect
else
    # Standalone 模式
    certbot certonly --standalone -d "$DOMAIN" --non-interactive --agree-tos --email "admin@${DOMAIN}"
fi

echo "OK: SSL 证书已申请"

# 3. 设置自动续期
echo ""
echo "[3/4] 配置自动续期..."

# 检查是否已有 cron
if ! crontab -l 2>/dev/null | grep -q certbot; then
    (crontab -l 2>/dev/null; echo "0 3 * * * certbot renew --quiet --post-hook 'systemctl reload ${WEB_SERVER:-nginx}'") | crontab -
    echo "OK: 已添加自动续期 cron (每天凌晨 3 点)"
else
    echo "OK: 自动续期 cron 已存在"
fi

# 4. 验证
echo ""
echo "[4/4] 验证证书..."

if [ -f "/etc/letsencrypt/live/${DOMAIN}/fullchain.pem" ]; then
    EXPIRY=$(openssl x509 -enddate -noout -in "/etc/letsencrypt/live/${DOMAIN}/fullchain.pem" | cut -d= -f2)
    echo "OK: 证书有效"
    echo "    域名: $DOMAIN"
    echo "    证书路径: /etc/letsencrypt/live/${DOMAIN}/"
    echo "    过期时间: $EXPIRY"
else
    echo "WARN: 证书文件未找到，请检查 Certbot 输出"
fi

echo ""
echo "============================================"
echo " HTTPS 配置完成"
echo "============================================"
echo ""
echo "证书文件位置:"
echo "  证书: /etc/letsencrypt/live/${DOMAIN}/fullchain.pem"
echo "  私钥: /etc/letsencrypt/live/${DOMAIN}/privkey.pem"
echo ""
echo "更新 web/data/config.inc.php:"
echo "  define('SITE_URL', 'https://${DOMAIN}');"
echo ""
