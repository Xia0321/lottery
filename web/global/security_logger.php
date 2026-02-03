<?php
/**
 * 安全日志记录辅助类
 * 创建日期: 2026-02-03
 * 说明: 记录安全相关事件，包括认证失败、权限拒绝、可疑活动等
 */

class SecurityLogger {

    private static $logDir = null;
    private static $logFile = 'security.log';
    private static $maxFileSize = 10485760; // 10MB

    /**
     * 初始化日志目录
     */
    private static function init() {
        if (self::$logDir === null) {
            self::$logDir = __DIR__ . '/../logs/security';

            if (!is_dir(self::$logDir)) {
                @mkdir(self::$logDir, 0755, true);
            }
        }
    }

    /**
     * 写入日志
     * @param string $level 日志级别
     * @param string $event 事件类型
     * @param string $message 消息
     * @param array $context 上下文信息
     */
    private static function writeLog($level, $event, $message, $context = []) {
        self::init();

        $logPath = self::$logDir . '/' . self::$logFile;

        // 日志轮转
        if (file_exists($logPath) && filesize($logPath) > self::$maxFileSize) {
            $backupPath = self::$logDir . '/' . date('Y-m-d_His') . '_' . self::$logFile;
            @rename($logPath, $backupPath);
        }

        // 构建日志条目
        $timestamp = date('Y-m-d H:i:s');
        $ip = self::getClientIP();
        $userAgent = $_SERVER['HTTP_USER_AGENT'] ?? 'Unknown';
        $requestUri = $_SERVER['REQUEST_URI'] ?? 'Unknown';

        $logEntry = [
            'timestamp' => $timestamp,
            'level' => $level,
            'event' => $event,
            'message' => $message,
            'ip' => $ip,
            'user_agent' => $userAgent,
            'request_uri' => $requestUri,
            'context' => $context
        ];

        $logLine = json_encode($logEntry, JSON_UNESCAPED_UNICODE) . PHP_EOL;

        // 写入日志文件
        @file_put_contents($logPath, $logLine, FILE_APPEND | LOCK_EX);
    }

    /**
     * 获取客户端真实IP
     * @return string
     */
    private static function getClientIP() {
        $ip = 'Unknown';

        if (!empty($_SERVER['HTTP_X_FORWARDED_FOR'])) {
            $ips = explode(',', $_SERVER['HTTP_X_FORWARDED_FOR']);
            $ip = trim($ips[0]);
        } elseif (!empty($_SERVER['HTTP_X_REAL_IP'])) {
            $ip = $_SERVER['HTTP_X_REAL_IP'];
        } elseif (!empty($_SERVER['REMOTE_ADDR'])) {
            $ip = $_SERVER['REMOTE_ADDR'];
        }

        return $ip;
    }

    /**
     * 记录登录失败
     * @param string $username 用户名
     * @param string $reason 失败原因
     */
    public static function logLoginFailure($username, $reason = 'Invalid credentials') {
        self::writeLog('WARNING', 'LOGIN_FAILURE', 'Login attempt failed', [
            'username' => $username,
            'reason' => $reason
        ]);
    }

    /**
     * 记录登录成功
     * @param string $username 用户名
     * @param int $userId 用户ID
     */
    public static function logLoginSuccess($username, $userId) {
        self::writeLog('INFO', 'LOGIN_SUCCESS', 'User logged in successfully', [
            'username' => $username,
            'user_id' => $userId
        ]);
    }

    /**
     * 记录登出
     * @param string $username 用户名
     * @param int $userId 用户ID
     */
    public static function logLogout($username, $userId) {
        self::writeLog('INFO', 'LOGOUT', 'User logged out', [
            'username' => $username,
            'user_id' => $userId
        ]);
    }

    /**
     * 记录权限拒绝
     * @param string $resource 资源名称
     * @param string $action 操作
     * @param string $username 用户名
     */
    public static function logAccessDenied($resource, $action, $username = null) {
        self::writeLog('WARNING', 'ACCESS_DENIED', 'Access denied to resource', [
            'resource' => $resource,
            'action' => $action,
            'username' => $username
        ]);
    }

    /**
     * 记录 CSRF 验证失败
     * @param string $action 操作
     */
    public static function logCsrfFailure($action = '') {
        self::writeLog('WARNING', 'CSRF_FAILURE', 'CSRF token validation failed', [
            'action' => $action
        ]);
    }

    /**
     * 记录 SQL 注入尝试
     * @param string $input 可疑输入
     * @param string $parameter 参数名
     */
    public static function logSqlInjectionAttempt($input, $parameter) {
        self::writeLog('CRITICAL', 'SQL_INJECTION_ATTEMPT', 'Possible SQL injection detected', [
            'input' => substr($input, 0, 200),
            'parameter' => $parameter
        ]);
    }

    /**
     * 记录 XSS 尝试
     * @param string $input 可疑输入
     * @param string $parameter 参数名
     */
    public static function logXssAttempt($input, $parameter) {
        self::writeLog('CRITICAL', 'XSS_ATTEMPT', 'Possible XSS attack detected', [
            'input' => substr($input, 0, 200),
            'parameter' => $parameter
        ]);
    }

    /**
     * 记录文件上传
     * @param string $filename 文件名
     * @param int $size 文件大小
     * @param string $username 用户名
     */
    public static function logFileUpload($filename, $size, $username) {
        self::writeLog('INFO', 'FILE_UPLOAD', 'File uploaded', [
            'filename' => $filename,
            'size' => $size,
            'username' => $username
        ]);
    }

    /**
     * 记录可疑的文件上传
     * @param string $filename 文件名
     * @param string $reason 原因
     */
    public static function logSuspiciousFileUpload($filename, $reason) {
        self::writeLog('WARNING', 'SUSPICIOUS_FILE_UPLOAD', 'Suspicious file upload attempt', [
            'filename' => $filename,
            'reason' => $reason
        ]);
    }

    /**
     * 记录路径遍历尝试
     * @param string $path 可疑路径
     * @param string $parameter 参数名
     */
    public static function logPathTraversalAttempt($path, $parameter) {
        self::writeLog('CRITICAL', 'PATH_TRAVERSAL_ATTEMPT', 'Path traversal attempt detected', [
            'path' => $path,
            'parameter' => $parameter
        ]);
    }

    /**
     * 记录密码修改
     * @param string $username 用户名
     * @param int $userId 用户ID
     */
    public static function logPasswordChange($username, $userId) {
        self::writeLog('INFO', 'PASSWORD_CHANGE', 'User password changed', [
            'username' => $username,
            'user_id' => $userId
        ]);
    }

    /**
     * 记录账户锁定
     * @param string $username 用户名
     * @param string $reason 原因
     */
    public static function logAccountLocked($username, $reason) {
        self::writeLog('WARNING', 'ACCOUNT_LOCKED', 'Account locked', [
            'username' => $username,
            'reason' => $reason
        ]);
    }

    /**
     * 记录权限提升
     * @param string $username 用户名
     * @param string $oldRole 原角色
     * @param string $newRole 新角色
     * @param string $changedBy 操作人
     */
    public static function logPrivilegeEscalation($username, $oldRole, $newRole, $changedBy) {
        self::writeLog('INFO', 'PRIVILEGE_ESCALATION', 'User privilege changed', [
            'username' => $username,
            'old_role' => $oldRole,
            'new_role' => $newRole,
            'changed_by' => $changedBy
        ]);
    }

    /**
     * 记录数据导出
     * @param string $dataType 数据类型
     * @param int $recordCount 记录数
     * @param string $username 用户名
     */
    public static function logDataExport($dataType, $recordCount, $username) {
        self::writeLog('INFO', 'DATA_EXPORT', 'Data exported', [
            'data_type' => $dataType,
            'record_count' => $recordCount,
            'username' => $username
        ]);
    }

    /**
     * 记录批量删除操作
     * @param string $table 表名
     * @param int $recordCount 删除记录数
     * @param string $username 用户名
     */
    public static function logBulkDelete($table, $recordCount, $username) {
        self::writeLog('WARNING', 'BULK_DELETE', 'Bulk delete operation', [
            'table' => $table,
            'record_count' => $recordCount,
            'username' => $username
        ]);
    }

    /**
     * 记录 API 访问
     * @param string $endpoint API端点
     * @param string $method 请求方法
     * @param int $statusCode 响应状态码
     * @param string $apiKey API密钥 (部分)
     */
    public static function logApiAccess($endpoint, $method, $statusCode, $apiKey = null) {
        $maskedKey = $apiKey ? substr($apiKey, 0, 8) . '...' : null;

        self::writeLog('INFO', 'API_ACCESS', 'API endpoint accessed', [
            'endpoint' => $endpoint,
            'method' => $method,
            'status_code' => $statusCode,
            'api_key' => $maskedKey
        ]);
    }

    /**
     * 记录异常错误
     * @param Exception $exception 异常对象
     */
    public static function logException($exception) {
        self::writeLog('ERROR', 'EXCEPTION', $exception->getMessage(), [
            'file' => $exception->getFile(),
            'line' => $exception->getLine(),
            'trace' => $exception->getTraceAsString()
        ]);
    }

    /**
     * 记录自定义安全事件
     * @param string $level 日志级别 (INFO, WARNING, ERROR, CRITICAL)
     * @param string $event 事件类型
     * @param string $message 消息
     * @param array $context 上下文信息
     */
    public static function log($level, $event, $message, $context = []) {
        self::writeLog($level, $event, $message, $context);
    }

    /**
     * 获取最近的日志条目
     * @param int $limit 数量限制
     * @param string $level 日志级别过滤
     * @return array
     */
    public static function getRecentLogs($limit = 100, $level = null) {
        self::init();

        $logPath = self::$logDir . '/' . self::$logFile;

        if (!file_exists($logPath)) {
            return [];
        }

        $lines = file($logPath, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);
        $logs = [];

        // 从后往前读取
        for ($i = count($lines) - 1; $i >= 0 && count($logs) < $limit; $i--) {
            $logEntry = json_decode($lines[$i], true);

            if ($logEntry) {
                if ($level === null || $logEntry['level'] === $level) {
                    $logs[] = $logEntry;
                }
            }
        }

        return $logs;
    }
}
