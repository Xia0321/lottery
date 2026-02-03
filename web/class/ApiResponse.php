<?php
/**
 * API 统一响应格式类
 *
 * 提供统一的 JSON 响应格式
 */

require_once __DIR__ . '/ErrorCode.php';

class ApiResponse {

    /**
     * 成功响应
     *
     * @param mixed $data 响应数据
     * @param array $meta 元信息（可选）
     * @return void
     */
    public static function success($data = null, $meta = []) {
        $response = [
            'success' => true,
            'data' => $data,
            'meta' => array_merge([
                'request_id' => self::generateRequestId(),
                'timestamp' => time()
            ], $meta)
        ];

        self::output($response, 200);
    }


    /**
     * 分页响应
     *
     * @param array $data 数据列表
     * @param int $total 总记录数
     * @param int $page 当前页码
     * @param int $pageSize 每页大小
     * @return void
     */
    public static function paginated($data, $total, $page, $pageSize) {
        $totalPages = ceil($total / $pageSize);

        $response = [
            'success' => true,
            'data' => $data,
            'pagination' => [
                'total' => $total,
                'page' => $page,
                'page_size' => $pageSize,
                'total_pages' => $totalPages
            ],
            'meta' => [
                'request_id' => self::generateRequestId(),
                'timestamp' => time()
            ]
        ];

        self::output($response, 200);
    }


    /**
     * 错误响应
     *
     * @param string $code 错误码（使用 ErrorCode 常量）
     * @param string|null $message 自定义错误信息（可选，默认使用错误码对应的信息）
     * @param array $details 错误详情（可选）
     * @return void
     */
    public static function error($code, $message = null, $details = []) {
        // 如果没有提供自定义消息，使用错误码对应的默认消息
        if ($message === null) {
            $message = ErrorCode::getMessage($code);
        }

        $response = [
            'success' => false,
            'error' => [
                'code' => $code,
                'message' => $message
            ],
            'meta' => [
                'request_id' => self::generateRequestId(),
                'timestamp' => time()
            ]
        ];

        // 如果有详情，添加到错误信息中
        if (!empty($details)) {
            $response['error']['details'] = $details;
        }

        // 根据错误码获取对应的 HTTP 状态码
        $httpCode = ErrorCode::getHttpCode($code);

        self::output($response, $httpCode);
    }


    /**
     * 输出 JSON 响应并退出
     *
     * @param array $response 响应数据
     * @param int $httpCode HTTP 状态码
     * @return void
     */
    private static function output($response, $httpCode = 200) {
        // 设置 HTTP 状态码
        http_response_code($httpCode);

        // 设置响应头
        header('Content-Type: application/json; charset=utf-8');

        // CORS 设置（根据实际需求配置）
        // header('Access-Control-Allow-Origin: *'); // 注意：实际生产环境应该限制具体域名

        // 禁用缓存
        header('Cache-Control: no-cache, no-store, must-revalidate');
        header('Pragma: no-cache');
        header('Expires: 0');

        // 输出 JSON
        echo json_encode($response, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES);

        exit;
    }


    /**
     * 生成唯一的请求 ID
     *
     * @return string
     */
    private static function generateRequestId() {
        return uniqid('req_', true);
    }


    /**
     * 记录 API 日志到数据库
     *
     * @param int $apiKeyId API Key ID
     * @param string $endpoint 请求路径
     * @param string $method HTTP 方法
     * @param int $responseCode 响应状态码
     * @param string|null $ipAddress IP 地址
     * @return void
     */
    public static function logApiCall($apiKeyId, $endpoint, $method, $responseCode, $ipAddress = null) {
        global $pdo; // 假设使用 PDO

        if (!$pdo) {
            return; // 如果数据库连接不可用，不记录日志
        }

        try {
            $stmt = $pdo->prepare("
                INSERT INTO api_logs (api_key_id, endpoint, method, response_code, ip_address, created_at)
                VALUES (?, ?, ?, ?, ?, NOW())
            ");

            $stmt->execute([
                $apiKeyId,
                $endpoint,
                $method,
                $responseCode,
                $ipAddress ?? $_SERVER['REMOTE_ADDR'] ?? null
            ]);
        } catch (Exception $e) {
            // 记录日志失败不应该影响 API 响应
            // 可以写入错误日志文件
            error_log("Failed to log API call: " . $e->getMessage());
        }
    }
}
