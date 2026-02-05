#!/bin/bash
############################################
# Redis 安装和配置脚本
# 用途: 为彩票系统 API 速率限制安装 Redis
#
# 支持: CentOS 7/8, Ubuntu 18/20/22
# 用法: sudo bash scripts/setup_redis.sh
############################################

set -e

echo "============================================"
echo " Redis 安装与配置"
echo "============================================"

# 检测操作系统
if [ -f /etc/redhat-release ]; then
    OS="centos"
    echo "检测到 CentOS/RHEL 系统"
elif [ -f /etc/debian_version ]; then
    OS="ubuntu"
    echo "检测到 Ubuntu/Debian 系统"
else
    echo "不支持的操作系统，请手动安装 Redis"
    exit 1
fi

# 1. 安装 Redis
echo ""
echo "[1/5] 安装 Redis..."

if [ "$OS" = "centos" ]; then
    yum install -y epel-release
    yum install -y redis
elif [ "$OS" = "ubuntu" ]; then
    apt-get update
    apt-get install -y redis-server
fi

echo "OK: Redis 已安装"

# 2. 配置 Redis
echo ""
echo "[2/5] 配置 Redis..."

REDIS_CONF=""
if [ -f /etc/redis.conf ]; then
    REDIS_CONF="/etc/redis.conf"
elif [ -f /etc/redis/redis.conf ]; then
    REDIS_CONF="/etc/redis/redis.conf"
fi

if [ -n "$REDIS_CONF" ]; then
    # 备份原配置
    cp "$REDIS_CONF" "${REDIS_CONF}.bak.$(date +%Y%m%d%H%M%S)"

    # 绑定本地地址（安全）
    sed -i 's/^bind .*/bind 127.0.0.1/' "$REDIS_CONF"

    # 设置最大内存 128MB（可按需调整）
    if grep -q "^maxmemory " "$REDIS_CONF"; then
        sed -i 's/^maxmemory .*/maxmemory 128mb/' "$REDIS_CONF"
    else
        echo "maxmemory 128mb" >> "$REDIS_CONF"
    fi

    # 设置内存淘汰策略
    if grep -q "^maxmemory-policy " "$REDIS_CONF"; then
        sed -i 's/^maxmemory-policy .*/maxmemory-policy allkeys-lru/' "$REDIS_CONF"
    else
        echo "maxmemory-policy allkeys-lru" >> "$REDIS_CONF"
    fi

    # 禁用 RDB 持久化（速率限制不需要持久化）
    sed -i 's/^save /#save /' "$REDIS_CONF"

    # 设置密码（可选，取消注释并修改）
    # echo "requirepass your_redis_password" >> "$REDIS_CONF"

    echo "OK: 配置已更新 ($REDIS_CONF)"
else
    echo "WARN: 未找到 Redis 配置文件，使用默认配置"
fi

# 3. 安装 PHP Redis 扩展
echo ""
echo "[3/5] 安装 PHP Redis 扩展..."

if php -m 2>/dev/null | grep -qi redis; then
    echo "OK: PHP Redis 扩展已安装"
else
    if [ "$OS" = "centos" ]; then
        yum install -y php-pecl-redis 2>/dev/null || pecl install redis
    elif [ "$OS" = "ubuntu" ]; then
        apt-get install -y php-redis 2>/dev/null || pecl install redis
    fi

    # 确认安装
    if php -m 2>/dev/null | grep -qi redis; then
        echo "OK: PHP Redis 扩展安装成功"
    else
        echo "WARN: PHP Redis 扩展安装失败，速率限制将以降级模式运行（无限制）"
    fi
fi

# 4. 启动 Redis
echo ""
echo "[4/5] 启动 Redis 服务..."

if command -v systemctl &>/dev/null; then
    systemctl enable redis 2>/dev/null || systemctl enable redis-server 2>/dev/null
    systemctl start redis 2>/dev/null || systemctl start redis-server 2>/dev/null
else
    service redis start 2>/dev/null || service redis-server start 2>/dev/null
fi

echo "OK: Redis 服务已启动"

# 5. 验证
echo ""
echo "[5/5] 验证 Redis..."

if redis-cli ping 2>/dev/null | grep -q PONG; then
    echo "OK: Redis 运行正常"
    REDIS_VERSION=$(redis-cli info server 2>/dev/null | grep redis_version | cut -d: -f2 | tr -d '\r')
    echo "    版本: $REDIS_VERSION"
    echo "    地址: 127.0.0.1:6379"
else
    echo "FAIL: Redis 连接失败，请检查服务状态"
    exit 1
fi

echo ""
echo "============================================"
echo " Redis 配置完成"
echo "============================================"
echo ""
echo "PHP 中使用 Redis："
echo "  \$redis = new Redis();"
echo "  \$redis->connect('127.0.0.1', 6379);"
echo ""
echo "如需设置密码，编辑 $REDIS_CONF："
echo "  requirepass your_password"
echo ""
echo "然后更新 web/data/config.inc.php："
echo "  define('REDIS_PASS', 'your_password');"
echo ""
