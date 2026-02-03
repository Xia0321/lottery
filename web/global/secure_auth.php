<?php
/**
 * 安全认证类
 *
 * 使用 PDO 预处理语句防止 SQL 注入
 * 2026-02-03 安全加固
 */

class SecureAuth {
    private $pdo;

    public function __construct($pdo) {
        $this->pdo = $pdo;
    }

    /**
     * 验证代理登录
     *
     * @param string $username 用户名
     * @param string $password 已哈希的密码
     * @return array|false 用户信息或 false
     */
    public function verifyAgentLogin($username, $password) {
        try {
            $stmt = $this->pdo->prepare("
                SELECT * FROM x_user
                WHERE username = ? AND userpass = ? AND ifagent = 1
            ");
            $stmt->execute([$username, $password]);
            return $stmt->fetch(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            error_log("Agent login error: " . $e->getMessage());
            return false;
        }
    }

    /**
     * 验证用户登录
     *
     * @param string $username 用户名
     * @param string $password 已哈希的密码
     * @return array|false 用户信息或 false
     */
    public function verifyUserLogin($username, $password) {
        try {
            $stmt = $this->pdo->prepare("
                SELECT * FROM x_user
                WHERE username = ? AND userpass = ? AND ifagent = 0 AND ifson = 0
            ");
            $stmt->execute([$username, $password]);
            return $stmt->fetch(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            error_log("User login error: " . $e->getMessage());
            return false;
        }
    }

    /**
     * 验证管理员登录
     *
     * @param string $username 用户名
     * @param string $password 已哈希的密码
     * @return array|false 用户信息或 false
     */
    public function verifyAdminLogin($username, $password) {
        try {
            $stmt = $this->pdo->prepare("
                SELECT * FROM x_user
                WHERE username = ? AND userpass = ? AND ifagent = 0 AND ifson = 0
            ");
            $stmt->execute([$username, $password]);
            return $stmt->fetch(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            error_log("Admin login error: " . $e->getMessage());
            return false;
        }
    }

    /**
     * 验证隐藏管理员登录
     *
     * @param string $username 用户名
     * @param string $password 已哈希的密码
     * @return array|false 管理员信息或 false
     */
    public function verifyHideAdminLogin($username, $password) {
        try {
            $stmt = $this->pdo->prepare("
                SELECT * FROM x_admins
                WHERE adminname = ? AND adminpass = ? AND ifhide = 1
            ");
            $stmt->execute([$username, $password]);
            return $stmt->fetch(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            error_log("Hide admin login error: " . $e->getMessage());
            return false;
        }
    }

    /**
     * 获取错误次数
     *
     * @param string $username 用户名
     * @return int 错误次数
     */
    public function getErrorTimes($username) {
        try {
            $stmt = $this->pdo->prepare("
                SELECT errortimes FROM x_user WHERE username = ?
            ");
            $stmt->execute([$username]);
            $result = $stmt->fetch(PDO::FETCH_ASSOC);
            return $result ? intval($result['errortimes']) : 0;
        } catch (PDOException $e) {
            error_log("Get error times error: " . $e->getMessage());
            return 0;
        }
    }

    /**
     * 增加错误次数
     *
     * @param string $username 用户名
     * @return bool
     */
    public function incrementErrorTimes($username) {
        try {
            $stmt = $this->pdo->prepare("
                UPDATE x_user SET errortimes = errortimes + 1 WHERE username = ?
            ");
            return $stmt->execute([$username]);
        } catch (PDOException $e) {
            error_log("Increment error times error: " . $e->getMessage());
            return false;
        }
    }

    /**
     * 重置错误次数
     *
     * @param string $username 用户名
     * @return bool
     */
    public function resetErrorTimes($username) {
        try {
            $stmt = $this->pdo->prepare("
                UPDATE x_user SET errortimes = 0 WHERE username = ?
            ");
            return $stmt->execute([$username]);
        } catch (PDOException $e) {
            error_log("Reset error times error: " . $e->getMessage());
            return false;
        }
    }

    /**
     * 记录登录日志
     *
     * @param string $server 服务器
     * @param int $xtype 类型
     * @param string $ip IP地址
     * @param string $ifok 是否成功
     * @param string $username 用户名
     * @param string $password 密码（已哈希）
     * @param string $os 操作系统
     * @return bool
     */
    public function logLogin($server, $xtype, $ip, $ifok, $username, $password, $os) {
        try {
            $stmt = $this->pdo->prepare("
                INSERT INTO x_user_login
                (server, xtype, ip, time, ifok, username, userpass, os)
                VALUES (?, ?, ?, NOW(), ?, ?, ?, ?)
            ");
            return $stmt->execute([$server, $xtype, $ip, $ifok, $username, $password, $os]);
        } catch (PDOException $e) {
            error_log("Log login error: " . $e->getMessage());
            return false;
        }
    }

    /**
     * 更新最后登录信息
     *
     * @param int $userId 用户ID
     * @param string $ip IP地址
     * @param string $os 操作系统
     * @return bool
     */
    public function updateLastLogin($userId, $ip, $os) {
        try {
            $stmt = $this->pdo->prepare("
                UPDATE x_user
                SET lastlogintime = NOW(),
                    lastloginip = ?,
                    lastos = ?,
                    errortimes = 0
                WHERE userid = ?
            ");
            return $stmt->execute([$ip, $os, $userId]);
        } catch (PDOException $e) {
            error_log("Update last login error: " . $e->getMessage());
            return false;
        }
    }
}
