<?php
/**
 * CSRF 保护辅助函数
 * 创建日期: 2026-02-03
 * 说明: 提供 CSRF Token 生成和验证功能
 */

/**
 * 生成 CSRF Token
 * @return string CSRF Token
 */
function generateCsrfToken() {
    if (session_status() === PHP_SESSION_NONE) {
        session_start();
    }

    if (empty($_SESSION['csrf_token'])) {
        $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
    }

    return $_SESSION['csrf_token'];
}

/**
 * 获取当前的 CSRF Token
 * @return string|null CSRF Token 或 null
 */
function getCsrfToken() {
    if (session_status() === PHP_SESSION_NONE) {
        session_start();
    }

    return $_SESSION['csrf_token'] ?? null;
}

/**
 * 验证 CSRF Token
 * @param string $token 要验证的 token
 * @return bool 验证是否成功
 */
function verifyCsrfToken($token) {
    if (session_status() === PHP_SESSION_NONE) {
        session_start();
    }

    if (empty($_SESSION['csrf_token'])) {
        return false;
    }

    return hash_equals($_SESSION['csrf_token'], $token);
}

/**
 * 输出 CSRF Token 隐藏字段
 * @return void
 */
function csrfTokenField() {
    $token = generateCsrfToken();
    echo '<input type="hidden" name="csrf_token" value="' . htmlspecialchars($token, ENT_QUOTES, 'UTF-8') . '">';
}

/**
 * 验证请求中的 CSRF Token
 * @param string $method 请求方法 (POST, GET, REQUEST)
 * @param bool $dieOnFailure 验证失败时是否终止执行
 * @return bool 验证是否成功
 */
function validateCsrfToken($method = 'POST', $dieOnFailure = true) {
    $methodArray = null;

    switch (strtoupper($method)) {
        case 'POST':
            $methodArray = $_POST;
            break;
        case 'GET':
            $methodArray = $_GET;
            break;
        case 'REQUEST':
            $methodArray = $_REQUEST;
            break;
        default:
            $methodArray = $_POST;
    }

    $token = $methodArray['csrf_token'] ?? '';

    if (!verifyCsrfToken($token)) {
        if ($dieOnFailure) {
            http_response_code(403);
            die(json_encode([
                'status' => 'error',
                'message' => 'CSRF token validation failed',
                'code' => 'CSRF_VALIDATION_FAILED'
            ]));
        }
        return false;
    }

    return true;
}

/**
 * 刷新 CSRF Token (用于登录后或其他需要刷新的场景)
 * @return string 新的 CSRF Token
 */
function refreshCsrfToken() {
    if (session_status() === PHP_SESSION_NONE) {
        session_start();
    }

    $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
    return $_SESSION['csrf_token'];
}
