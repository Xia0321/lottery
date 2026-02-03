<?php
/**
 * API 错误码定义
 *
 * 错误码格式：类别_编号
 * - AUTH_xxx: 认证相关错误
 * - PARAM_xxx: 参数相关错误
 * - BIZ_xxx: 业务逻辑错误
 * - SYS_xxx: 系统错误
 */

class ErrorCode {

    // ========== 认证相关错误 (AUTH_xxx) ==========

    const AUTH_MISSING_API_KEY = 'AUTH_001';
    const AUTH_INVALID_API_KEY = 'AUTH_002';
    const AUTH_API_KEY_DISABLED = 'AUTH_003';
    const AUTH_MISSING_SIGNATURE = 'AUTH_004';
    const AUTH_INVALID_SIGNATURE = 'AUTH_005';
    const AUTH_MISSING_TIMESTAMP = 'AUTH_006';
    const AUTH_TIMESTAMP_EXPIRED = 'AUTH_007';
    const AUTH_RATE_LIMIT_EXCEEDED = 'AUTH_008';
    const AUTH_INVALID_TOKEN = 'AUTH_009';
    const AUTH_TOKEN_EXPIRED = 'AUTH_010';
    const AUTH_TOKEN_USED = 'AUTH_011';

    // ========== 参数相关错误 (PARAM_xxx) ==========

    const PARAM_MISSING = 'PARAM_001';
    const PARAM_INVALID_TYPE = 'PARAM_002';
    const PARAM_INVALID_FORMAT = 'PARAM_003';
    const PARAM_OUT_OF_RANGE = 'PARAM_004';
    const PARAM_INVALID_VALUE = 'PARAM_005';

    // ========== 业务逻辑错误 (BIZ_xxx) ==========

    const BIZ_USER_NOT_FOUND = 'BIZ_001';
    const BIZ_USER_DISABLED = 'BIZ_002';
    const BIZ_INSUFFICIENT_BALANCE = 'BIZ_003';
    const BIZ_BET_NOT_FOUND = 'BIZ_004';
    const BIZ_TRANSACTION_NOT_FOUND = 'BIZ_005';
    const BIZ_LOTTERY_RESULT_NOT_FOUND = 'BIZ_006';
    const BIZ_WEBHOOK_NOT_FOUND = 'BIZ_007';
    const BIZ_WEBHOOK_URL_INVALID = 'BIZ_008';
    const BIZ_DATA_ACCESS_DENIED = 'BIZ_009';

    // ========== 系统错误 (SYS_xxx) ==========

    const SYS_INTERNAL_ERROR = 'SYS_001';
    const SYS_DATABASE_ERROR = 'SYS_002';
    const SYS_REDIS_ERROR = 'SYS_003';
    const SYS_NETWORK_ERROR = 'SYS_004';
    const SYS_SERVICE_UNAVAILABLE = 'SYS_005';


    /**
     * 错误信息映射
     */
    private static $messages = [
        // 认证相关
        self::AUTH_MISSING_API_KEY => '缺少 API Key',
        self::AUTH_INVALID_API_KEY => '无效的 API Key',
        self::AUTH_API_KEY_DISABLED => 'API Key 已被禁用',
        self::AUTH_MISSING_SIGNATURE => '缺少签名参数',
        self::AUTH_INVALID_SIGNATURE => '签名验证失败',
        self::AUTH_MISSING_TIMESTAMP => '缺少时间戳参数',
        self::AUTH_TIMESTAMP_EXPIRED => '请求已过期（时间戳超过 5 分钟）',
        self::AUTH_RATE_LIMIT_EXCEEDED => '请求频率超限，请稍后再试',
        self::AUTH_INVALID_TOKEN => '无效的 Token',
        self::AUTH_TOKEN_EXPIRED => 'Token 已过期',
        self::AUTH_TOKEN_USED => 'Token 已被使用',

        // 参数相关
        self::PARAM_MISSING => '缺少必填参数',
        self::PARAM_INVALID_TYPE => '参数类型错误',
        self::PARAM_INVALID_FORMAT => '参数格式错误',
        self::PARAM_OUT_OF_RANGE => '参数超出有效范围',
        self::PARAM_INVALID_VALUE => '参数值无效',

        // 业务逻辑
        self::BIZ_USER_NOT_FOUND => '用户不存在',
        self::BIZ_USER_DISABLED => '用户账号已被禁用',
        self::BIZ_INSUFFICIENT_BALANCE => '余额不足',
        self::BIZ_BET_NOT_FOUND => '投注记录不存在',
        self::BIZ_TRANSACTION_NOT_FOUND => '流水记录不存在',
        self::BIZ_LOTTERY_RESULT_NOT_FOUND => '开奖结果不存在',
        self::BIZ_WEBHOOK_NOT_FOUND => 'Webhook 不存在',
        self::BIZ_WEBHOOK_URL_INVALID => 'Webhook URL 必须是 HTTPS 地址',
        self::BIZ_DATA_ACCESS_DENIED => '无权访问该数据',

        // 系统错误
        self::SYS_INTERNAL_ERROR => '系统内部错误',
        self::SYS_DATABASE_ERROR => '数据库错误',
        self::SYS_REDIS_ERROR => 'Redis 错误',
        self::SYS_NETWORK_ERROR => '网络错误',
        self::SYS_SERVICE_UNAVAILABLE => '服务暂时不可用',
    ];


    /**
     * 获取错误信息
     *
     * @param string $code 错误码
     * @return string 错误信息
     */
    public static function getMessage($code) {
        return isset(self::$messages[$code]) ? self::$messages[$code] : '未知错误';
    }


    /**
     * HTTP 状态码映射
     *
     * @param string $code 错误码
     * @return int HTTP 状态码
     */
    public static function getHttpCode($code) {
        // 认证错误 -> 401
        if (strpos($code, 'AUTH_') === 0) {
            if ($code === self::AUTH_RATE_LIMIT_EXCEEDED) {
                return 429; // Too Many Requests
            }
            return 401; // Unauthorized
        }

        // 参数错误 -> 400
        if (strpos($code, 'PARAM_') === 0) {
            return 400; // Bad Request
        }

        // 业务错误
        if (strpos($code, 'BIZ_') === 0) {
            if (in_array($code, [
                self::BIZ_USER_NOT_FOUND,
                self::BIZ_BET_NOT_FOUND,
                self::BIZ_TRANSACTION_NOT_FOUND,
                self::BIZ_LOTTERY_RESULT_NOT_FOUND,
                self::BIZ_WEBHOOK_NOT_FOUND
            ])) {
                return 404; // Not Found
            }
            if (in_array($code, [
                self::BIZ_DATA_ACCESS_DENIED,
                self::BIZ_USER_DISABLED
            ])) {
                return 403; // Forbidden
            }
            return 400; // Bad Request
        }

        // 系统错误 -> 500
        if (strpos($code, 'SYS_') === 0) {
            if ($code === self::SYS_SERVICE_UNAVAILABLE) {
                return 503; // Service Unavailable
            }
            return 500; // Internal Server Error
        }

        return 500; // 默认
    }
}
