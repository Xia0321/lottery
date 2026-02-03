-- 彩票系统 API 表结构迁移
-- 创建日期: 2026-02-03
-- 说明: 创建 API Key、API 日志、Webhook 配置和 Webhook 日志表

-- ============================================
-- 表1: api_keys - 存储外部系统的 API Key 和 Secret
-- ============================================
CREATE TABLE IF NOT EXISTS `api_keys` (
    `id` INT UNSIGNED NOT NULL AUTO_INCREMENT COMMENT 'API Key ID',
    `api_key` VARCHAR(64) NOT NULL COMMENT 'API Key (公开)',
    `api_secret` VARCHAR(128) NOT NULL COMMENT 'API Secret (保密)',
    `agent_id` INT UNSIGNED NOT NULL COMMENT '关联的代理 ID',
    `partner_name` VARCHAR(100) NOT NULL COMMENT '合作伙伴名称',
    `description` VARCHAR(255) DEFAULT NULL COMMENT '描述',
    `status` TINYINT NOT NULL DEFAULT 1 COMMENT '状态: 0=禁用, 1=启用',
    `rate_limit` INT UNSIGNED NOT NULL DEFAULT 100 COMMENT '速率限制 (每分钟请求数)',
    `allowed_ips` TEXT DEFAULT NULL COMMENT '允许的 IP 地址 (JSON 数组，为空表示不限制)',
    `scopes` TEXT DEFAULT NULL COMMENT '权限范围 (JSON 数组)',
    `expires_at` DATETIME DEFAULT NULL COMMENT '过期时间 (NULL 表示永久有效)',
    `last_used_at` DATETIME DEFAULT NULL COMMENT '最后使用时间',
    `created_at` DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP COMMENT '创建时间',
    `updated_at` DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP COMMENT '更新时间',
    PRIMARY KEY (`id`),
    UNIQUE KEY `uk_api_key` (`api_key`),
    KEY `idx_agent_id` (`agent_id`),
    KEY `idx_status` (`status`),
    KEY `idx_created_at` (`created_at`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COMMENT='API Key 配置表';

-- ============================================
-- 表2: api_logs - 记录 API 访问日志
-- ============================================
CREATE TABLE IF NOT EXISTS `api_logs` (
    `id` BIGINT UNSIGNED NOT NULL AUTO_INCREMENT COMMENT '日志 ID',
    `api_key_id` INT UNSIGNED DEFAULT NULL COMMENT 'API Key ID',
    `api_key` VARCHAR(64) DEFAULT NULL COMMENT 'API Key (冗余存储)',
    `endpoint` VARCHAR(255) NOT NULL COMMENT 'API 端点',
    `method` VARCHAR(10) NOT NULL COMMENT 'HTTP 方法',
    `request_params` TEXT DEFAULT NULL COMMENT '请求参数 (JSON)',
    `response_code` INT NOT NULL COMMENT 'HTTP 响应码',
    `response_body` TEXT DEFAULT NULL COMMENT '响应内容 (JSON，记录前1000字符)',
    `ip_address` VARCHAR(45) NOT NULL COMMENT '客户端 IP',
    `user_agent` VARCHAR(255) DEFAULT NULL COMMENT 'User-Agent',
    `execution_time` INT UNSIGNED DEFAULT NULL COMMENT '执行时间 (毫秒)',
    `error_message` TEXT DEFAULT NULL COMMENT '错误信息',
    `created_at` DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP COMMENT '创建时间',
    PRIMARY KEY (`id`),
    KEY `idx_api_key_id` (`api_key_id`),
    KEY `idx_endpoint` (`endpoint`),
    KEY `idx_response_code` (`response_code`),
    KEY `idx_created_at` (`created_at`),
    KEY `idx_ip_address` (`ip_address`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COMMENT='API 访问日志表';

-- ============================================
-- 表3: webhooks - 存储 Webhook 配置
-- ============================================
CREATE TABLE IF NOT EXISTS `webhooks` (
    `id` INT UNSIGNED NOT NULL AUTO_INCREMENT COMMENT 'Webhook ID',
    `api_key_id` INT UNSIGNED NOT NULL COMMENT 'API Key ID',
    `event_type` VARCHAR(50) NOT NULL COMMENT '事件类型: bet.created, bet.settled, deposit.completed, withdrawal.completed',
    `callback_url` VARCHAR(500) NOT NULL COMMENT '回调 URL',
    `secret` VARCHAR(128) NOT NULL COMMENT '签名密钥',
    `status` TINYINT NOT NULL DEFAULT 1 COMMENT '状态: 0=禁用, 1=启用',
    `retry_times` TINYINT NOT NULL DEFAULT 1 COMMENT '重试次数',
    `timeout` INT UNSIGNED NOT NULL DEFAULT 5 COMMENT '超时时间 (秒)',
    `description` VARCHAR(255) DEFAULT NULL COMMENT '描述',
    `last_triggered_at` DATETIME DEFAULT NULL COMMENT '最后触发时间',
    `created_at` DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP COMMENT '创建时间',
    `updated_at` DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP COMMENT '更新时间',
    PRIMARY KEY (`id`),
    KEY `idx_api_key_id` (`api_key_id`),
    KEY `idx_event_type` (`event_type`),
    KEY `idx_status` (`status`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COMMENT='Webhook 配置表';

-- ============================================
-- 表4: webhook_logs - 记录 Webhook 调用日志
-- ============================================
CREATE TABLE IF NOT EXISTS `webhook_logs` (
    `id` BIGINT UNSIGNED NOT NULL AUTO_INCREMENT COMMENT '日志 ID',
    `webhook_id` INT UNSIGNED NOT NULL COMMENT 'Webhook ID',
    `event_type` VARCHAR(50) NOT NULL COMMENT '事件类型',
    `event_data` TEXT NOT NULL COMMENT '事件数据 (JSON)',
    `callback_url` VARCHAR(500) NOT NULL COMMENT '回调 URL',
    `request_headers` TEXT DEFAULT NULL COMMENT '请求头 (JSON)',
    `request_body` TEXT NOT NULL COMMENT '请求体 (JSON)',
    `response_code` INT DEFAULT NULL COMMENT 'HTTP 响应码',
    `response_body` TEXT DEFAULT NULL COMMENT '响应内容',
    `retry_count` TINYINT NOT NULL DEFAULT 0 COMMENT '重试次数',
    `status` VARCHAR(20) NOT NULL COMMENT '状态: pending, success, failed',
    `error_message` TEXT DEFAULT NULL COMMENT '错误信息',
    `execution_time` INT UNSIGNED DEFAULT NULL COMMENT '执行时间 (毫秒)',
    `created_at` DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP COMMENT '创建时间',
    `updated_at` DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP COMMENT '更新时间',
    PRIMARY KEY (`id`),
    KEY `idx_webhook_id` (`webhook_id`),
    KEY `idx_event_type` (`event_type`),
    KEY `idx_status` (`status`),
    KEY `idx_created_at` (`created_at`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COMMENT='Webhook 调用日志表';

-- ============================================
-- 插入测试数据 (可选，用于开发测试)
-- ============================================

-- 生成一个测试 API Key
-- API Key: test_api_key_12345678
-- API Secret: test_secret_87654321abcdefgh
-- 注意: 生产环境应使用随机生成的强密钥
INSERT INTO `api_keys` (
    `api_key`,
    `api_secret`,
    `agent_id`,
    `partner_name`,
    `description`,
    `status`,
    `rate_limit`
) VALUES (
    'test_api_key_12345678',
    'test_secret_87654321abcdefgh',
    1,
    '测试合作伙伴',
    '仅用于开发测试，生产环境请删除',
    1,
    100
);

-- ============================================
-- 权限说明
-- ============================================
-- 确保 MySQL 用户有以下权限:
-- GRANT CREATE, ALTER, INSERT ON lottery.* TO 'lottery_user'@'localhost';
-- FLUSH PRIVILEGES;

-- ============================================
-- 验证表创建
-- ============================================
-- 执行以下命令验证表是否创建成功:
-- SHOW TABLES LIKE 'api_%';
-- SHOW TABLES LIKE 'webhook%';
-- DESCRIBE api_keys;
-- DESCRIBE api_logs;
-- DESCRIBE webhooks;
-- DESCRIBE webhook_logs;

-- ============================================
-- 回滚脚本 (如需回滚，执行以下命令)
-- ============================================
-- DROP TABLE IF EXISTS `webhook_logs`;
-- DROP TABLE IF EXISTS `webhooks`;
-- DROP TABLE IF EXISTS `api_logs`;
-- DROP TABLE IF EXISTS `api_keys`;
