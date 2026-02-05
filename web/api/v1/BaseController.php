<?php
/**
 * API 基础控制器
 * 创建日期: 2026-02-03
 * 说明: 所有 API 控制器的基类
 */

require_once(__DIR__ . '/../../data/config.inc.php');
require_once(__DIR__ . '/../../data/db.php');
require_once(__DIR__ . '/../../global/db.inc.php');
require_once(__DIR__ . '/../../class/api/ApiResponse.php');
require_once(__DIR__ . '/../../class/api/ErrorCode.php');
require_once(__DIR__ . '/../../class/api/ApiAuth.php');
require_once(__DIR__ . '/../../class/api/RateLimiter.php');
require_once(__DIR__ . '/../../class/api/ApiLogger.php');
require_once(__DIR__ . '/../../global/input_validator.php');

class BaseController {

    protected $mysqli;
    protected $auth;
    protected $rateLimiter;
    protected $logger;
    protected $apiKeyData;
    protected $agentId;

    /**
     * 构造函数
     * @param bool $requireAuth 是否需要认证 (默认 true)
     */
    public function __construct($requireAuth = true) {
        // 设置错误处理
        set_exception_handler([$this, 'handleException']);
        set_error_handler([$this, 'handleError']);

        // 规范化 URI：去除 .php 后缀，兼容直接文件访问和 URL 重写两种模式
        $_SERVER['REQUEST_URI'] = preg_replace('#\.php(?=\?|/|$)#', '', $_SERVER['REQUEST_URI']);

        // 设置 CORS 头 (如需要)
        $this->setCorsHeaders();

        // 获取数据库连接
        global $msql;
        $this->mysqli = $msql->get_mysqli();

        // 初始化日志记录器
        $this->logger = new ApiLogger($this->mysqli);

        // 如果需要认证
        if ($requireAuth) {
            // 初始化认证
            $this->auth = new ApiAuth($this->mysqli);

            // 执行认证
            $this->apiKeyData = $this->auth->authenticate();
            $this->agentId = $this->apiKeyData['agent_id'];

            // 设置日志记录器的 API Key 信息
            $this->logger->setApiKey(
                $this->apiKeyData['id'],
                $this->apiKeyData['api_key']
            );

            // 初始化速率限制器
            $this->rateLimiter = new RateLimiter();

            // 执行速率限制检查
            $rateLimit = $this->apiKeyData['rate_limit'] ?? 100;
            $this->rateLimiter->enforce($this->apiKeyData['api_key'], $rateLimit, 60);
        }
    }

    /**
     * 设置 CORS 头
     */
    private function setCorsHeaders() {
        // 允许的来源 (生产环境应配置白名单)
        $allowedOrigins = ['*']; // TODO: 配置允许的域名

        if (isset($_SERVER['HTTP_ORIGIN'])) {
            if (in_array('*', $allowedOrigins)) {
                header('Access-Control-Allow-Origin: ' . $_SERVER['HTTP_ORIGIN']);
            } elseif (in_array($_SERVER['HTTP_ORIGIN'], $allowedOrigins)) {
                header('Access-Control-Allow-Origin: ' . $_SERVER['HTTP_ORIGIN']);
            }
        }

        header('Access-Control-Allow-Methods: GET, POST, PUT, DELETE, OPTIONS');
        header('Access-Control-Allow-Headers: Content-Type, X-API-Key, X-Requested-With');
        header('Access-Control-Max-Age: 86400');

        // 处理 OPTIONS 预检请求
        if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') {
            http_response_code(204);
            exit;
        }
    }

    /**
     * 获取请求方法
     * @return string
     */
    protected function getMethod() {
        return $_SERVER['REQUEST_METHOD'];
    }

    /**
     * 检查请求方法
     * @param string|array $allowedMethods 允许的方法
     */
    protected function requireMethod($allowedMethods) {
        $allowedMethods = is_array($allowedMethods) ? $allowedMethods : [$allowedMethods];
        $method = $this->getMethod();

        if (!in_array($method, $allowedMethods)) {
            ApiResponse::error(
                'Method not allowed',
                ErrorCode::PARAM_INVALID,
                405
            );
        }
    }

    /**
     * 获取必需参数
     * @param string $name 参数名
     * @param string $type 类型
     * @param string $method 来源 (GET, POST, REQUEST)
     * @param array $options 选项
     * @return mixed
     */
    protected function getRequiredParam($name, $type = 'string', $method = 'REQUEST', $options = []) {
        $value = InputValidator::getInput($name, $type, $method, null, $options);

        if ($value === null || $value === '') {
            ApiResponse::badRequest("Missing required parameter: $name", $name);
        }

        return $value;
    }

    /**
     * 获取可选参数
     * @param string $name 参数名
     * @param string $type 类型
     * @param mixed $default 默认值
     * @param string $method 来源
     * @param array $options 选项
     * @return mixed
     */
    protected function getOptionalParam($name, $type = 'string', $default = null, $method = 'REQUEST', $options = []) {
        return InputValidator::getInput($name, $type, $method, $default, $options);
    }

    /**
     * 获取分页参数
     * @return array [page, pageSize]
     */
    protected function getPagination() {
        $page = $this->getOptionalParam('page', 'int', 1, 'GET', ['min' => 1]);
        $pageSize = $this->getOptionalParam('page_size', 'int', 20, 'GET', ['min' => 1, 'max' => 100]);

        return [$page, $pageSize];
    }

    /**
     * 验证权限 (检查资源是否属于当前代理)
     * @param int $resourceAgentId 资源的代理 ID
     */
    protected function checkPermission($resourceAgentId) {
        if ($resourceAgentId != $this->agentId) {
            ApiResponse::forbidden('You do not have permission to access this resource');
        }
    }

    /**
     * 要求指定的 API Scope 权限
     * @param string $scope 权限标识，例如 bets.read, bets.write
     */
    protected function requireScope($scope) {
        if ($this->auth) {
            $this->auth->requireScope($scope);
        }
    }

    /**
     * 记录并响应成功
     * @param mixed $data 数据
     * @param string $message 消息
     */
    protected function respondSuccess($data = null, $message = 'Success') {
        // 记录日志
        $this->logger->log(200, $data);

        // 响应
        ApiResponse::success($data, $message);
    }

    /**
     * 记录并响应错误
     * @param string $message 错误消息
     * @param int $code 错误码
     * @param int $httpCode HTTP 状态码
     */
    protected function respondError($message, $code, $httpCode = 400) {
        // 记录日志
        $this->logger->log($httpCode, null, $message);

        // 响应
        ApiResponse::error($message, $code, $httpCode);
    }

    /**
     * 异常处理器
     * @param Throwable $e 异常
     */
    public function handleException($e) {
        error_log('API Exception: ' . $e->getMessage() . ' in ' . $e->getFile() . ':' . $e->getLine());

        // 记录日志
        if ($this->logger) {
            $this->logger->log(500, null, $e->getMessage());
        }

        // 响应错误
        ApiResponse::serverError('An unexpected error occurred');
    }

    /**
     * 错误处理器
     * @param int $errno 错误级别
     * @param string $errstr 错误消息
     * @param string $errfile 错误文件
     * @param int $errline 错误行号
     */
    public function handleError($errno, $errstr, $errfile, $errline) {
        // 只处理严重错误
        if (!(error_reporting() & $errno)) {
            return;
        }

        error_log("API Error [$errno]: $errstr in $errfile:$errline");

        // 抛出异常，由异常处理器处理
        throw new ErrorException($errstr, 0, $errno, $errfile, $errline);
    }

    /**
     * 析构函数 - 清理资源
     */
    public function __destruct() {
        if ($this->rateLimiter) {
            $this->rateLimiter->close();
        }
    }
}
