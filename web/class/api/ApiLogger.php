<?php
/**
 * API 日志记录器
 * 创建日期: 2026-02-03
 * 说明: 记录所有 API 请求和响应到数据库
 */

class ApiLogger {

    private $mysqli;
    private $startTime;
    private $apiKeyId = null;
    private $apiKey = null;

    /**
     * 构造函数
     * @param mysqli $mysqli 数据库连接
     */
    public function __construct($mysqli) {
        $this->mysqli = $mysqli;
        $this->startTime = microtime(true);
    }

    /**
     * 设置 API Key 信息
     * @param int $apiKeyId API Key ID
     * @param string $apiKey API Key
     */
    public function setApiKey($apiKeyId, $apiKey) {
        $this->apiKeyId = $apiKeyId;
        $this->apiKey = $apiKey;
    }

    /**
     * 记录 API 请求
     * @param int $responseCode HTTP 响应码
     * @param mixed $responseBody 响应内容
     * @param string $errorMessage 错误信息
     * @return bool
     */
    public function log($responseCode, $responseBody = null, $errorMessage = null) {
        try {
            $endpoint = $_SERVER['REQUEST_URI'] ?? '';
            $method = $_SERVER['REQUEST_METHOD'] ?? 'GET';
            $ipAddress = $this->getClientIP();
            $userAgent = $_SERVER['HTTP_USER_AGENT'] ?? null;

            // 获取请求参数
            $requestParams = $this->getRequestParams();

            // 限制响应体大小 (最多记录前 1000 字符)
            if ($responseBody !== null) {
                $responseBody = is_string($responseBody)
                    ? substr($responseBody, 0, 1000)
                    : substr(json_encode($responseBody, JSON_UNESCAPED_UNICODE), 0, 1000);
            }

            // 计算执行时间 (毫秒)
            $executionTime = (int)((microtime(true) - $this->startTime) * 1000);

            // 插入日志
            $stmt = $this->mysqli->prepare("
                INSERT INTO api_logs (
                    api_key_id,
                    api_key,
                    endpoint,
                    method,
                    request_params,
                    response_code,
                    response_body,
                    ip_address,
                    user_agent,
                    execution_time,
                    error_message
                ) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)
            ");

            $stmt->bind_param(
                "issssisssis",
                $this->apiKeyId,
                $this->apiKey,
                $endpoint,
                $method,
                $requestParams,
                $responseCode,
                $responseBody,
                $ipAddress,
                $userAgent,
                $executionTime,
                $errorMessage
            );

            $result = $stmt->execute();
            $stmt->close();

            return $result;

        } catch (Exception $e) {
            error_log('API Logger error: ' . $e->getMessage());
            return false;
        }
    }

    /**
     * 获取请求参数 (JSON 格式)
     * @return string
     */
    private function getRequestParams() {
        $params = [];

        // GET 参数
        if (!empty($_GET)) {
            $params['get'] = $this->sanitizeParams($_GET);
        }

        // POST 参数
        if (!empty($_POST)) {
            $params['post'] = $this->sanitizeParams($_POST);
        }

        // JSON body
        if ($_SERVER['REQUEST_METHOD'] === 'POST' || $_SERVER['REQUEST_METHOD'] === 'PUT') {
            $rawBody = file_get_contents('php://input');
            if (!empty($rawBody)) {
                $jsonData = json_decode($rawBody, true);
                if ($jsonData) {
                    $params['json'] = $this->sanitizeParams($jsonData);
                }
            }
        }

        return json_encode($params, JSON_UNESCAPED_UNICODE);
    }

    /**
     * 清理敏感参数
     * @param array $params 参数数组
     * @return array
     */
    private function sanitizeParams($params) {
        $sensitiveKeys = [
            'password',
            'userpass',
            'api_secret',
            'secret',
            'token',
            'sign',
            'signature',
            'credit_card',
            'cvv',
            'ssn'
        ];

        foreach ($params as $key => $value) {
            if (in_array(strtolower($key), $sensitiveKeys)) {
                $params[$key] = '***REDACTED***';
            } elseif (is_array($value)) {
                $params[$key] = $this->sanitizeParams($value);
            }
        }

        return $params;
    }

    /**
     * 获取客户端 IP
     * @return string
     */
    private function getClientIP() {
        if (!empty($_SERVER['HTTP_X_FORWARDED_FOR'])) {
            $ips = explode(',', $_SERVER['HTTP_X_FORWARDED_FOR']);
            return trim($ips[0]);
        } elseif (!empty($_SERVER['HTTP_X_REAL_IP'])) {
            return $_SERVER['HTTP_X_REAL_IP'];
        } elseif (!empty($_SERVER['REMOTE_ADDR'])) {
            return $_SERVER['REMOTE_ADDR'];
        }

        return 'Unknown';
    }

    /**
     * 查询 API 日志
     * @param array $filters 过滤条件
     * @param int $page 页码
     * @param int $pageSize 每页大小
     * @return array
     */
    public static function query($mysqli, $filters = [], $page = 1, $pageSize = 50) {
        $where = [];
        $params = [];
        $types = '';

        // API Key 过滤
        if (!empty($filters['api_key_id'])) {
            $where[] = "api_key_id = ?";
            $params[] = $filters['api_key_id'];
            $types .= 'i';
        }

        // 端点过滤
        if (!empty($filters['endpoint'])) {
            $where[] = "endpoint LIKE ?";
            $params[] = '%' . $filters['endpoint'] . '%';
            $types .= 's';
        }

        // 响应码过滤
        if (!empty($filters['response_code'])) {
            $where[] = "response_code = ?";
            $params[] = $filters['response_code'];
            $types .= 'i';
        }

        // IP 过滤
        if (!empty($filters['ip_address'])) {
            $where[] = "ip_address = ?";
            $params[] = $filters['ip_address'];
            $types .= 's';
        }

        // 时间范围过滤
        if (!empty($filters['start_date'])) {
            $where[] = "created_at >= ?";
            $params[] = $filters['start_date'];
            $types .= 's';
        }

        if (!empty($filters['end_date'])) {
            $where[] = "created_at <= ?";
            $params[] = $filters['end_date'];
            $types .= 's';
        }

        $whereClause = !empty($where) ? 'WHERE ' . implode(' AND ', $where) : '';

        // 统计总数
        $countSql = "SELECT COUNT(*) as total FROM api_logs $whereClause";
        $stmt = $mysqli->prepare($countSql);

        if (!empty($params)) {
            $stmt->bind_param($types, ...$params);
        }

        $stmt->execute();
        $result = $stmt->get_result();
        $total = $result->fetch_assoc()['total'];
        $stmt->close();

        // 查询数据
        $offset = ($page - 1) * $pageSize;
        $dataSql = "SELECT * FROM api_logs $whereClause ORDER BY created_at DESC LIMIT ? OFFSET ?";

        $stmt = $mysqli->prepare($dataSql);

        $params[] = $pageSize;
        $params[] = $offset;
        $types .= 'ii';

        $stmt->bind_param($types, ...$params);
        $stmt->execute();
        $result = $stmt->get_result();

        $logs = [];
        while ($row = $result->fetch_assoc()) {
            $logs[] = $row;
        }

        $stmt->close();

        return [
            'items' => $logs,
            'total' => $total,
            'page' => $page,
            'page_size' => $pageSize,
            'total_pages' => ceil($total / $pageSize)
        ];
    }

    /**
     * 清理旧日志 (保留最近 N 天)
     * @param mysqli $mysqli 数据库连接
     * @param int $days 保留天数
     * @return int 删除的记录数
     */
    public static function cleanup($mysqli, $days = 30) {
        $stmt = $mysqli->prepare("
            DELETE FROM api_logs
            WHERE created_at < DATE_SUB(NOW(), INTERVAL ? DAY)
        ");

        $stmt->bind_param("i", $days);
        $stmt->execute();
        $affected = $stmt->affected_rows;
        $stmt->close();

        return $affected;
    }

    /**
     * 获取 API 使用统计
     * @param mysqli $mysqli 数据库连接
     * @param int $apiKeyId API Key ID
     * @param string $startDate 开始日期
     * @param string $endDate 结束日期
     * @return array
     */
    public static function getStats($mysqli, $apiKeyId = null, $startDate = null, $endDate = null) {
        $where = [];
        $params = [];
        $types = '';

        if ($apiKeyId) {
            $where[] = "api_key_id = ?";
            $params[] = $apiKeyId;
            $types .= 'i';
        }

        if ($startDate) {
            $where[] = "created_at >= ?";
            $params[] = $startDate;
            $types .= 's';
        }

        if ($endDate) {
            $where[] = "created_at <= ?";
            $params[] = $endDate;
            $types .= 's';
        }

        $whereClause = !empty($where) ? 'WHERE ' . implode(' AND ', $where) : '';

        // 统计查询
        $sql = "
            SELECT
                COUNT(*) as total_requests,
                COUNT(CASE WHEN response_code >= 200 AND response_code < 300 THEN 1 END) as successful_requests,
                COUNT(CASE WHEN response_code >= 400 THEN 1 END) as failed_requests,
                AVG(execution_time) as avg_execution_time,
                MAX(execution_time) as max_execution_time,
                MIN(execution_time) as min_execution_time
            FROM api_logs
            $whereClause
        ";

        $stmt = $mysqli->prepare($sql);

        if (!empty($params)) {
            $stmt->bind_param($types, ...$params);
        }

        $stmt->execute();
        $result = $stmt->get_result();
        $stats = $result->fetch_assoc();
        $stmt->close();

        return $stats;
    }
}
