<?php
/**
 * API 速率限制器
 * 创建日期: 2026-02-03
 * 说明: 基于 Redis 的速率限制实现
 */

require_once(__DIR__ . '/ErrorCode.php');
require_once(__DIR__ . '/ApiResponse.php');

class RateLimiter {

    private $redis = null;
    private $enabled = true;

    /**
     * 构造函数
     */
    public function __construct() {
        // 尝试连接 Redis
        try {
            if (class_exists('Redis')) {
                $this->redis = new Redis();
                $this->redis->connect('127.0.0.1', 6379, 2);

                // 如果配置了密码，需要认证
                // $this->redis->auth('your_password');

                // 测试连接
                $this->redis->ping();
            } else {
                error_log('Redis extension not installed, rate limiting disabled');
                $this->enabled = false;
            }
        } catch (Exception $e) {
            error_log('Redis connection failed: ' . $e->getMessage() . ', rate limiting disabled');
            $this->enabled = false;
        }
    }

    /**
     * 检查速率限制
     * @param string $key 限制键 (通常是 API Key)
     * @param int $limit 限制次数
     * @param int $window 时间窗口 (秒)
     * @return bool 是否允许请求
     */
    public function check($key, $limit, $window = 60) {
        // 如果 Redis 不可用，跳过限制
        if (!$this->enabled) {
            return true;
        }

        $redisKey = "rate_limit:{$key}";

        try {
            // 获取当前计数
            $current = $this->redis->get($redisKey);

            if ($current === false) {
                // 首次请求，设置计数为 1
                $this->redis->setex($redisKey, $window, 1);
                return true;
            }

            $current = (int)$current;

            if ($current >= $limit) {
                // 超过限制
                return false;
            }

            // 增加计数
            $this->redis->incr($redisKey);
            return true;

        } catch (Exception $e) {
            error_log('Rate limiter error: ' . $e->getMessage());
            // 出错时允许请求通过
            return true;
        }
    }

    /**
     * 获取剩余请求次数
     * @param string $key 限制键
     * @param int $limit 限制次数
     * @return int 剩余次数
     */
    public function getRemaining($key, $limit) {
        if (!$this->enabled) {
            return $limit;
        }

        $redisKey = "rate_limit:{$key}";

        try {
            $current = $this->redis->get($redisKey);
            if ($current === false) {
                return $limit;
            }

            $remaining = $limit - (int)$current;
            return max(0, $remaining);

        } catch (Exception $e) {
            error_log('Rate limiter error: ' . $e->getMessage());
            return $limit;
        }
    }

    /**
     * 获取重置时间 (秒)
     * @param string $key 限制键
     * @return int 距离重置的秒数
     */
    public function getResetTime($key) {
        if (!$this->enabled) {
            return 0;
        }

        $redisKey = "rate_limit:{$key}";

        try {
            $ttl = $this->redis->ttl($redisKey);
            return $ttl > 0 ? $ttl : 0;

        } catch (Exception $e) {
            error_log('Rate limiter error: ' . $e->getMessage());
            return 0;
        }
    }

    /**
     * 重置限制
     * @param string $key 限制键
     * @return bool
     */
    public function reset($key) {
        if (!$this->enabled) {
            return true;
        }

        $redisKey = "rate_limit:{$key}";

        try {
            $this->redis->del($redisKey);
            return true;

        } catch (Exception $e) {
            error_log('Rate limiter error: ' . $e->getMessage());
            return false;
        }
    }

    /**
     * 检查并强制限制
     * @param string $apiKey API Key
     * @param int $limit 限制次数
     * @param int $window 时间窗口 (秒)
     * @return void (超限时直接响应并退出)
     */
    public function enforce($apiKey, $limit, $window = 60) {
        $allowed = $this->check($apiKey, $limit, $window);

        if (!$allowed) {
            $resetTime = $this->getResetTime($apiKey);

            // 添加速率限制响应头
            header("X-RateLimit-Limit: $limit");
            header("X-RateLimit-Remaining: 0");
            header("X-RateLimit-Reset: " . (time() + $resetTime));

            ApiResponse::tooManyRequests($resetTime);
        }

        // 添加速率限制信息到响应头
        $remaining = $this->getRemaining($apiKey, $limit);
        header("X-RateLimit-Limit: $limit");
        header("X-RateLimit-Remaining: $remaining");
        header("X-RateLimit-Reset: " . (time() + $this->getResetTime($apiKey)));
    }

    /**
     * 滑动窗口算法 (更精确但开销更大)
     * @param string $key 限制键
     * @param int $limit 限制次数
     * @param int $window 时间窗口 (秒)
     * @return bool 是否允许请求
     */
    public function checkSlidingWindow($key, $limit, $window = 60) {
        if (!$this->enabled) {
            return true;
        }

        $redisKey = "rate_limit_sw:{$key}";
        $now = microtime(true);
        $windowStart = $now - $window;

        try {
            // 使用 Sorted Set 存储请求时间戳
            // 移除窗口外的记录
            $this->redis->zRemRangeByScore($redisKey, 0, $windowStart);

            // 获取当前窗口内的请求数
            $current = $this->redis->zCard($redisKey);

            if ($current >= $limit) {
                return false;
            }

            // 添加当前请求
            $this->redis->zAdd($redisKey, $now, $now);

            // 设置过期时间
            $this->redis->expire($redisKey, $window + 1);

            return true;

        } catch (Exception $e) {
            error_log('Rate limiter error: ' . $e->getMessage());
            return true;
        }
    }

    /**
     * 令牌桶算法
     * @param string $key 限制键
     * @param int $capacity 桶容量
     * @param float $refillRate 每秒补充速率
     * @return bool 是否允许请求
     */
    public function checkTokenBucket($key, $capacity, $refillRate) {
        if (!$this->enabled) {
            return true;
        }

        $redisKey = "rate_limit_tb:{$key}";
        $now = microtime(true);

        try {
            // 获取桶状态
            $bucket = $this->redis->hGetAll($redisKey);

            if (empty($bucket)) {
                // 初始化桶
                $bucket = [
                    'tokens' => $capacity - 1,
                    'last_refill' => $now
                ];
                $this->redis->hMSet($redisKey, $bucket);
                $this->redis->expire($redisKey, 3600);
                return true;
            }

            // 计算应补充的令牌数
            $tokens = (float)$bucket['tokens'];
            $lastRefill = (float)$bucket['last_refill'];
            $elapsed = $now - $lastRefill;
            $tokensToAdd = $elapsed * $refillRate;

            $tokens = min($capacity, $tokens + $tokensToAdd);

            if ($tokens < 1) {
                return false;
            }

            // 消耗一个令牌
            $tokens -= 1;

            // 更新桶状态
            $this->redis->hMSet($redisKey, [
                'tokens' => $tokens,
                'last_refill' => $now
            ]);

            return true;

        } catch (Exception $e) {
            error_log('Rate limiter error: ' . $e->getMessage());
            return true;
        }
    }

    /**
     * 检查 Redis 连接状态
     * @return bool
     */
    public function isEnabled() {
        return $this->enabled;
    }

    /**
     * 获取 Redis 实例
     * @return Redis|null
     */
    public function getRedis() {
        return $this->redis;
    }

    /**
     * 关闭连接
     */
    public function close() {
        if ($this->redis) {
            try {
                $this->redis->close();
            } catch (Exception $e) {
                // Ignore errors on close
            }
        }
    }

    /**
     * 析构函数
     */
    public function __destruct() {
        $this->close();
    }
}
