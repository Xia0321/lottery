<?php
/**
 * API 错误码定义
 * 创建日期: 2026-02-03
 * 说明: 定义所有 API 错误码
 *
 * 错误码规则:
 * - 0: 成功
 * - 1000-1999: 认证相关 (AUTH_xxx)
 * - 2000-2999: 参数相关 (PARAM_xxx)
 * - 3000-3999: 业务逻辑相关 (BIZ_xxx)
 * - 5000-5999: 系统相关 (SYS_xxx)
 */

class ErrorCode {

    // ============================================
    // 认证相关错误 (1000-1999)
    // ============================================

    /** API Key 缺失 */
    const AUTH_API_KEY_MISSING = 1001;

    /** API Key 无效 */
    const AUTH_API_KEY_INVALID = 1002;

    /** API Key 已禁用 */
    const AUTH_API_KEY_DISABLED = 1003;

    /** API Key 已过期 */
    const AUTH_API_KEY_EXPIRED = 1004;

    /** 签名缺失 */
    const AUTH_SIGNATURE_MISSING = 1011;

    /** 签名无效 */
    const AUTH_SIGNATURE_INVALID = 1012;

    /** 时间戳缺失 */
    const AUTH_TIMESTAMP_MISSING = 1021;

    /** 时间戳无效 */
    const AUTH_TIMESTAMP_INVALID = 1022;

    /** 请求已过期 (防重放攻击) */
    const AUTH_REQUEST_EXPIRED = 1023;

    /** IP 地址不在白名单 */
    const AUTH_IP_NOT_ALLOWED = 1031;

    /** 权限不足 */
    const AUTH_INSUFFICIENT_PERMISSIONS = 1041;

    /** 无效的凭证 */
    const AUTH_INVALID_CREDENTIALS = 1042;

    // ============================================
    // 参数相关错误 (2000-2999)
    // ============================================

    /** 参数缺失 */
    const PARAM_MISSING = 2001;

    /** 参数无效 */
    const PARAM_INVALID = 2002;

    /** 参数类型错误 */
    const PARAM_TYPE_ERROR = 2003;

    /** 参数超出范围 */
    const PARAM_OUT_OF_RANGE = 2004;

    /** 参数格式错误 */
    const PARAM_FORMAT_ERROR = 2005;

    /** 参数长度错误 */
    const PARAM_LENGTH_ERROR = 2006;

    // ============================================
    // 业务逻辑相关错误 (3000-3999)
    // ============================================

    /** 资源未找到 */
    const BIZ_RESOURCE_NOT_FOUND = 3001;

    /** 资源已存在 */
    const BIZ_RESOURCE_ALREADY_EXISTS = 3002;

    /** 操作失败 */
    const BIZ_OPERATION_FAILED = 3003;

    /** 余额不足 */
    const BIZ_INSUFFICIENT_BALANCE = 3011;

    /** 投注金额无效 */
    const BIZ_INVALID_BET_AMOUNT = 3012;

    /** 投注已关闭 */
    const BIZ_BETTING_CLOSED = 3013;

    /** 游戏不可用 */
    const BIZ_GAME_UNAVAILABLE = 3014;

    /** 用户不存在 */
    const BIZ_USER_NOT_FOUND = 3021;

    /** 用户已被禁用 */
    const BIZ_USER_DISABLED = 3022;

    /** Webhook 配置不存在 */
    const BIZ_WEBHOOK_NOT_FOUND = 3031;

    /** Webhook 已存在 */
    const BIZ_WEBHOOK_ALREADY_EXISTS = 3032;

    // ============================================
    // 系统相关错误 (5000-5999)
    // ============================================

    /** 内部服务器错误 */
    const SYS_INTERNAL_ERROR = 5001;

    /** 数据库错误 */
    const SYS_DATABASE_ERROR = 5002;

    /** Redis 错误 */
    const SYS_REDIS_ERROR = 5003;

    /** 速率限制超出 */
    const SYS_RATE_LIMIT_EXCEEDED = 5011;

    /** 服务暂时不可用 */
    const SYS_SERVICE_UNAVAILABLE = 5021;

    /** 外部服务错误 */
    const SYS_EXTERNAL_SERVICE_ERROR = 5031;

    /**
     * 获取错误消息
     * @param int $code 错误码
     * @return string
     */
    public static function getMessage($code) {
        $messages = [
            // 认证相关
            self::AUTH_API_KEY_MISSING => 'API Key is required',
            self::AUTH_API_KEY_INVALID => 'Invalid API Key',
            self::AUTH_API_KEY_DISABLED => 'API Key is disabled',
            self::AUTH_API_KEY_EXPIRED => 'API Key has expired',
            self::AUTH_SIGNATURE_MISSING => 'Signature is required',
            self::AUTH_SIGNATURE_INVALID => 'Invalid signature',
            self::AUTH_TIMESTAMP_MISSING => 'Timestamp is required',
            self::AUTH_TIMESTAMP_INVALID => 'Invalid timestamp',
            self::AUTH_REQUEST_EXPIRED => 'Request has expired',
            self::AUTH_IP_NOT_ALLOWED => 'IP address not allowed',
            self::AUTH_INSUFFICIENT_PERMISSIONS => 'Insufficient permissions',
            self::AUTH_INVALID_CREDENTIALS => 'Invalid credentials',

            // 参数相关
            self::PARAM_MISSING => 'Required parameter is missing',
            self::PARAM_INVALID => 'Invalid parameter',
            self::PARAM_TYPE_ERROR => 'Parameter type error',
            self::PARAM_OUT_OF_RANGE => 'Parameter out of range',
            self::PARAM_FORMAT_ERROR => 'Parameter format error',
            self::PARAM_LENGTH_ERROR => 'Parameter length error',

            // 业务逻辑相关
            self::BIZ_RESOURCE_NOT_FOUND => 'Resource not found',
            self::BIZ_RESOURCE_ALREADY_EXISTS => 'Resource already exists',
            self::BIZ_OPERATION_FAILED => 'Operation failed',
            self::BIZ_INSUFFICIENT_BALANCE => 'Insufficient balance',
            self::BIZ_INVALID_BET_AMOUNT => 'Invalid bet amount',
            self::BIZ_BETTING_CLOSED => 'Betting is closed',
            self::BIZ_GAME_UNAVAILABLE => 'Game is unavailable',
            self::BIZ_USER_NOT_FOUND => 'User not found',
            self::BIZ_USER_DISABLED => 'User is disabled',
            self::BIZ_WEBHOOK_NOT_FOUND => 'Webhook not found',
            self::BIZ_WEBHOOK_ALREADY_EXISTS => 'Webhook already exists',

            // 系统相关
            self::SYS_INTERNAL_ERROR => 'Internal server error',
            self::SYS_DATABASE_ERROR => 'Database error',
            self::SYS_REDIS_ERROR => 'Redis error',
            self::SYS_RATE_LIMIT_EXCEEDED => 'Rate limit exceeded',
            self::SYS_SERVICE_UNAVAILABLE => 'Service temporarily unavailable',
            self::SYS_EXTERNAL_SERVICE_ERROR => 'External service error',
        ];

        return $messages[$code] ?? 'Unknown error';
    }

    /**
     * 是否为认证相关错误
     * @param int $code 错误码
     * @return bool
     */
    public static function isAuthError($code) {
        return $code >= 1000 && $code < 2000;
    }

    /**
     * 是否为参数相关错误
     * @param int $code 错误码
     * @return bool
     */
    public static function isParamError($code) {
        return $code >= 2000 && $code < 3000;
    }

    /**
     * 是否为业务逻辑错误
     * @param int $code 错误码
     * @return bool
     */
    public static function isBusinessError($code) {
        return $code >= 3000 && $code < 4000;
    }

    /**
     * 是否为系统错误
     * @param int $code 错误码
     * @return bool
     */
    public static function isSystemError($code) {
        return $code >= 5000 && $code < 6000;
    }
}
