<?php
/**
 * API 测试端点
 * 用于验证 API 认证和基础功能
 *
 * GET /api/v1/test.php?api_key=xxx&timestamp=xxx&sign=xxx
 */

require_once(__DIR__ . '/BaseController.php');

class TestController extends BaseController {

    public function handle() {
        // 要求 GET 方法
        $this->requireMethod('GET');

        // 获取可选的消息参数
        $message = $this->getOptionalParam('message', 'string', 'Hello from Lottery API!');

        // 构建响应数据
        $data = [
            'message' => $message,
            'server_time' => date('Y-m-d H:i:s'),
            'api_version' => '1.0',
            'authenticated' => true,
            'api_key_info' => [
                'partner_name' => $this->apiKeyData['partner_name'],
                'agent_id' => $this->agentId,
                'rate_limit' => $this->apiKeyData['rate_limit']
            ]
        ];

        // 响应成功
        $this->respondSuccess($data, 'API is working correctly');
    }
}

// 执行控制器
$controller = new TestController();
$controller->handle();
