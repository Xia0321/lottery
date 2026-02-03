<?php
/**
 * 输入验证和清理辅助函数
 * 创建日期: 2026-02-03
 * 说明: 提供统一的输入验证、清理和类型转换功能
 */

class InputValidator {

    /**
     * 验证并清理整数
     * @param mixed $value 输入值
     * @param int $min 最小值
     * @param int $max 最大值
     * @param int|null $default 默认值
     * @return int|null
     */
    public static function sanitizeInt($value, $min = null, $max = null, $default = null) {
        if ($value === '' || $value === null) {
            return $default;
        }

        $value = filter_var($value, FILTER_VALIDATE_INT);

        if ($value === false) {
            return $default;
        }

        if ($min !== null && $value < $min) {
            return $default;
        }

        if ($max !== null && $value > $max) {
            return $default;
        }

        return $value;
    }

    /**
     * 验证并清理浮点数
     * @param mixed $value 输入值
     * @param float $min 最小值
     * @param float $max 最大值
     * @param float|null $default 默认值
     * @return float|null
     */
    public static function sanitizeFloat($value, $min = null, $max = null, $default = null) {
        if ($value === '' || $value === null) {
            return $default;
        }

        $value = filter_var($value, FILTER_VALIDATE_FLOAT);

        if ($value === false) {
            return $default;
        }

        if ($min !== null && $value < $min) {
            return $default;
        }

        if ($max !== null && $value > $max) {
            return $default;
        }

        return $value;
    }

    /**
     * 验证并清理字符串
     * @param mixed $value 输入值
     * @param int $maxLength 最大长度
     * @param string $default 默认值
     * @return string
     */
    public static function sanitizeString($value, $maxLength = null, $default = '') {
        if ($value === null) {
            return $default;
        }

        $value = trim($value);
        $value = strip_tags($value);

        if ($maxLength !== null && mb_strlen($value, 'UTF-8') > $maxLength) {
            $value = mb_substr($value, 0, $maxLength, 'UTF-8');
        }

        return $value;
    }

    /**
     * 验证用户名 (字母、数字、下划线、点号)
     * @param string $username 用户名
     * @param int $minLength 最小长度
     * @param int $maxLength 最大长度
     * @return string|false
     */
    public static function validateUsername($username, $minLength = 3, $maxLength = 20) {
        $username = trim($username);

        if (strlen($username) < $minLength || strlen($username) > $maxLength) {
            return false;
        }

        if (!preg_match('/^[a-zA-Z0-9_\.]+$/', $username)) {
            return false;
        }

        return $username;
    }

    /**
     * 验证邮箱地址
     * @param string $email 邮箱地址
     * @return string|false
     */
    public static function validateEmail($email) {
        $email = trim($email);
        $email = filter_var($email, FILTER_VALIDATE_EMAIL);

        return $email;
    }

    /**
     * 验证手机号 (中国大陆)
     * @param string $phone 手机号
     * @return string|false
     */
    public static function validatePhone($phone) {
        $phone = trim($phone);
        $phone = preg_replace('/[^0-9]/', '', $phone);

        if (!preg_match('/^1[3-9]\d{9}$/', $phone)) {
            return false;
        }

        return $phone;
    }

    /**
     * 验证日期格式
     * @param string $date 日期字符串
     * @param string $format 日期格式 (默认 Y-m-d)
     * @return string|false
     */
    public static function validateDate($date, $format = 'Y-m-d') {
        $date = trim($date);
        $d = DateTime::createFromFormat($format, $date);

        if ($d && $d->format($format) === $date) {
            return $date;
        }

        return false;
    }

    /**
     * 验证 IP 地址
     * @param string $ip IP地址
     * @param int $flags FILTER_FLAG_IPV4 或 FILTER_FLAG_IPV6
     * @return string|false
     */
    public static function validateIP($ip, $flags = null) {
        $ip = trim($ip);

        if ($flags === null) {
            return filter_var($ip, FILTER_VALIDATE_IP);
        }

        return filter_var($ip, FILTER_VALIDATE_IP, $flags);
    }

    /**
     * 验证 URL
     * @param string $url URL地址
     * @return string|false
     */
    public static function validateURL($url) {
        $url = trim($url);
        return filter_var($url, FILTER_VALIDATE_URL);
    }

    /**
     * 清理 HTML (防止 XSS)
     * @param string $html HTML内容
     * @param array $allowedTags 允许的标签
     * @return string
     */
    public static function sanitizeHTML($html, $allowedTags = []) {
        if (empty($allowedTags)) {
            return strip_tags($html);
        }

        $allowedTagsString = '<' . implode('><', $allowedTags) . '>';
        return strip_tags($html, $allowedTagsString);
    }

    /**
     * 验证文件名 (防止路径遍历)
     * @param string $filename 文件名
     * @return string|false
     */
    public static function validateFilename($filename) {
        $filename = basename($filename);

        // 只允许字母、数字、下划线、连字符、点号
        if (!preg_match('/^[a-zA-Z0-9_\-\.]+$/', $filename)) {
            return false;
        }

        // 防止双扩展名攻击
        if (substr_count($filename, '.') > 1) {
            return false;
        }

        return $filename;
    }

    /**
     * 验证数组中的所有整数
     * @param array $values 值数组
     * @param int $min 最小值
     * @param int $max 最大值
     * @return array 清理后的整数数组
     */
    public static function sanitizeIntArray($values, $min = null, $max = null) {
        if (!is_array($values)) {
            return [];
        }

        $sanitized = [];
        foreach ($values as $value) {
            $clean = self::sanitizeInt($value, $min, $max);
            if ($clean !== null) {
                $sanitized[] = $clean;
            }
        }

        return array_unique($sanitized);
    }

    /**
     * 从请求中获取并验证参数
     * @param string $key 参数名
     * @param string $type 类型 (int, float, string, email, phone, date, etc.)
     * @param string $method 请求方法 (POST, GET, REQUEST)
     * @param mixed $default 默认值
     * @param array $options 额外选项 (min, max, maxLength, etc.)
     * @return mixed
     */
    public static function getInput($key, $type = 'string', $method = 'POST', $default = null, $options = []) {
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

        if (!isset($methodArray[$key])) {
            return $default;
        }

        $value = $methodArray[$key];

        switch ($type) {
            case 'int':
            case 'integer':
                return self::sanitizeInt(
                    $value,
                    $options['min'] ?? null,
                    $options['max'] ?? null,
                    $default
                );

            case 'float':
            case 'double':
                return self::sanitizeFloat(
                    $value,
                    $options['min'] ?? null,
                    $options['max'] ?? null,
                    $default
                );

            case 'string':
                return self::sanitizeString(
                    $value,
                    $options['maxLength'] ?? null,
                    $default
                );

            case 'username':
                return self::validateUsername(
                    $value,
                    $options['minLength'] ?? 3,
                    $options['maxLength'] ?? 20
                ) ?: $default;

            case 'email':
                return self::validateEmail($value) ?: $default;

            case 'phone':
                return self::validatePhone($value) ?: $default;

            case 'date':
                return self::validateDate(
                    $value,
                    $options['format'] ?? 'Y-m-d'
                ) ?: $default;

            case 'ip':
                return self::validateIP($value) ?: $default;

            case 'url':
                return self::validateURL($value) ?: $default;

            case 'filename':
                return self::validateFilename($value) ?: $default;

            case 'html':
                return self::sanitizeHTML(
                    $value,
                    $options['allowedTags'] ?? []
                );

            case 'array_int':
                return self::sanitizeIntArray(
                    $value,
                    $options['min'] ?? null,
                    $options['max'] ?? null
                );

            default:
                return $value;
        }
    }

    /**
     * 批量获取并验证输入
     * @param array $rules 验证规则
     * @param string $method 请求方法
     * @return array
     */
    public static function getBatchInput($rules, $method = 'POST') {
        $result = [];

        foreach ($rules as $key => $rule) {
            $result[$key] = self::getInput(
                $key,
                $rule['type'] ?? 'string',
                $method,
                $rule['default'] ?? null,
                $rule['options'] ?? []
            );
        }

        return $result;
    }
}
