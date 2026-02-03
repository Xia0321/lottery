<?php
/**
 * API 响应格式化类
 * 创建日期: 2026-02-03
 * 说明: 提供统一的 API 响应格式
 */

class ApiResponse {

    /**
     * 成功响应
     * @param mixed $data 数据
     * @param string $message 消息
     * @param int $code 业务码 (默认 0 表示成功)
     * @return void
     */
    public static function success($data = null, $message = 'Success', $code = 0) {
        self::send(200, true, $data, $message, $code);
    }

    /**
     * 错误响应
     * @param string $message 错误消息
     * @param int $code 错误码
     * @param int $httpCode HTTP 状态码
     * @param mixed $data 额外数据
     * @return void
     */
    public static function error($message, $code, $httpCode = 400, $data = null) {
        self::send($httpCode, false, $data, $message, $code);
    }

    /**
     * 认证失败响应
     * @param string $message 错误消息
     * @return void
     */
    public static function unauthorized($message = 'Unauthorized') {
        self::error($message, ErrorCode::AUTH_INVALID_CREDENTIALS, 401);
    }

    /**
     * 权限不足响应
     * @param string $message 错误消息
     * @return void
     */
    public static function forbidden($message = 'Forbidden') {
        self::error($message, ErrorCode::AUTH_INSUFFICIENT_PERMISSIONS, 403);
    }

    /**
     * 资源未找到响应
     * @param string $message 错误消息
     * @return void
     */
    public static function notFound($message = 'Resource not found') {
        self::error($message, ErrorCode::BIZ_RESOURCE_NOT_FOUND, 404);
    }

    /**
     * 参数错误响应
     * @param string $message 错误消息
     * @param string $field 字段名
     * @return void
     */
    public static function badRequest($message = 'Bad request', $field = null) {
        $data = $field ? ['field' => $field] : null;
        self::error($message, ErrorCode::PARAM_INVALID, 400, $data);
    }

    /**
     * 速率限制响应
     * @param int $retryAfter 重试等待秒数
     * @return void
     */
    public static function tooManyRequests($retryAfter = 60) {
        header('Retry-After: ' . $retryAfter);
        self::error('Too many requests', ErrorCode::SYS_RATE_LIMIT_EXCEEDED, 429, [
            'retry_after' => $retryAfter
        ]);
    }

    /**
     * 服务器错误响应
     * @param string $message 错误消息
     * @return void
     */
    public static function serverError($message = 'Internal server error') {
        self::error($message, ErrorCode::SYS_INTERNAL_ERROR, 500);
    }

    /**
     * 发送响应
     * @param int $httpCode HTTP 状态码
     * @param bool $success 是否成功
     * @param mixed $data 数据
     * @param string $message 消息
     * @param int $code 业务码
     * @return void
     */
    private static function send($httpCode, $success, $data, $message, $code) {
        // 设置 HTTP 状态码
        http_response_code($httpCode);

        // 设置响应头
        header('Content-Type: application/json; charset=utf-8');
        header('X-Content-Type-Options: nosniff');
        header('X-Frame-Options: DENY');
        header('X-XSS-Protection: 1; mode=block');

        // 构建响应体
        $response = [
            'success' => $success,
            'code' => $code,
            'message' => $message,
            'data' => $data,
            'timestamp' => time(),
            'request_id' => self::getRequestId()
        ];

        // 输出 JSON
        echo json_encode($response, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES);
        exit;
    }

    /**
     * 获取或生成请求 ID
     * @return string
     */
    private static function getRequestId() {
        static $requestId = null;

        if ($requestId === null) {
            $requestId = isset($_SERVER['HTTP_X_REQUEST_ID'])
                ? $_SERVER['HTTP_X_REQUEST_ID']
                : uniqid('req_', true);
        }

        return $requestId;
    }

    /**
     * 分页响应
     * @param array $items 数据项
     * @param int $total 总数
     * @param int $page 当前页
     * @param int $pageSize 每页大小
     * @return void
     */
    public static function paginated($items, $total, $page, $pageSize) {
        $totalPages = ceil($total / $pageSize);

        self::success([
            'items' => $items,
            'pagination' => [
                'total' => $total,
                'page' => $page,
                'page_size' => $pageSize,
                'total_pages' => $totalPages,
                'has_next' => $page < $totalPages,
                'has_prev' => $page > 1
            ]
        ]);
    }
}
