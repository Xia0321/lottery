## ADDED Requirements

### Requirement: 系统必须支持 Webhook 配置管理
外部系统必须能够配置和管理 Webhook 订阅，指定接收事件通知的 URL 和事件类型。

#### Scenario: 创建 Webhook 订阅
- **WHEN** 外部系统调用 `/api/webhooks` 接口，提交回调 URL 和订阅的事件类型
- **THEN** 系统创建 Webhook 配置，返回 Webhook ID 和密钥

#### Scenario: URL 格式验证
- **WHEN** 外部系统提交的回调 URL 格式不正确或不是 HTTPS 协议
- **THEN** 系统返回 400 Bad Request 错误，提示 URL 必须是有效的 HTTPS 地址

#### Scenario: 查询所有 Webhook 配置
- **WHEN** 外部系统调用 `/api/webhooks` 接口，使用 GET 方法
- **THEN** 系统返回该外部系统的所有 Webhook 配置列表

#### Scenario: 更新 Webhook 配置
- **WHEN** 外部系统调用 `/api/webhooks/{webhook_id}` 接口，使用 PUT 方法更新订阅事件
- **THEN** 系统更新 Webhook 配置，新配置立即生效

#### Scenario: 删除 Webhook 订阅
- **WHEN** 外部系统调用 `/api/webhooks/{webhook_id}` 接口，使用 DELETE 方法
- **THEN** 系统删除该 Webhook 配置，不再发送事件通知

### Requirement: 系统必须在用户投注时触发 Webhook
当用户完成投注时，系统必须向订阅该事件的外部系统发送 Webhook 通知。

#### Scenario: 用户投注成功触发通知
- **WHEN** 用户成功提交一笔投注
- **THEN** 系统向订阅 `bet.created` 事件的 Webhook URL 发送 POST 请求，包含投注详情

#### Scenario: Webhook 负载包含完整投注信息
- **WHEN** 系统发送投注 Webhook
- **THEN** 请求体包含用户 ID、投注 ID、游戏类型、投注内容、投注金额、投注时间等完整信息

#### Scenario: 投注取消触发通知
- **WHEN** 用户或管理员取消某笔投注
- **THEN** 系统向订阅 `bet.cancelled` 事件的 Webhook URL 发送通知

#### Scenario: 投注状态变更触发通知
- **WHEN** 投注从"待开奖"状态变为"已中奖"或"未中奖"
- **THEN** 系统向订阅 `bet.settled` 事件的 Webhook URL 发送通知

### Requirement: 系统必须在用户中奖时触发 Webhook
当投注中奖并派奖时，系统必须向外部系统发送中奖通知。

#### Scenario: 用户中奖触发通知
- **WHEN** 系统完成开奖计算，确认某用户投注中奖
- **THEN** 系统向订阅 `bet.won` 事件的 Webhook URL 发送通知，包含中奖金额和投注详情

#### Scenario: 大额中奖特殊通知
- **WHEN** 用户单笔投注中奖金额超过配置的阈值（如 10000 元）
- **THEN** 系统向订阅 `bet.big_win` 事件的 Webhook URL 发送特殊通知

#### Scenario: 派奖完成通知
- **WHEN** 系统将中奖金额加到用户余额
- **THEN** 系统向订阅 `prize.paid` 事件的 Webhook URL 发送通知

### Requirement: 系统必须在用户充值时触发 Webhook
当用户账户发生充值时，系统必须通知外部系统。

#### Scenario: 用户充值成功触发通知
- **WHEN** 用户充值成功到账
- **THEN** 系统向订阅 `deposit.completed` 事件的 Webhook URL 发送通知，包含充值金额、充值方式、用户余额等

#### Scenario: 充值待审核通知
- **WHEN** 用户提交充值申请，等待审核
- **THEN** 系统向订阅 `deposit.pending` 事件的 Webhook URL 发送通知

#### Scenario: 充值失败通知
- **WHEN** 用户充值失败或被拒绝
- **THEN** 系统向订阅 `deposit.failed` 事件的 Webhook URL 发送通知，包含失败原因

### Requirement: 系统必须在用户提款时触发 Webhook
当用户发起提款或提款状态变更时，系统必须通知外部系统。

#### Scenario: 用户申请提款触发通知
- **WHEN** 用户提交提款申请
- **THEN** 系统向订阅 `withdrawal.requested` 事件的 Webhook URL 发送通知，包含提款金额、提款方式等

#### Scenario: 提款审核通过通知
- **WHEN** 管理员审核通过提款申请
- **THEN** 系统向订阅 `withdrawal.approved` 事件的 Webhook URL 发送通知

#### Scenario: 提款完成通知
- **WHEN** 提款打款完成
- **THEN** 系统向订阅 `withdrawal.completed` 事件的 Webhook URL 发送通知

#### Scenario: 提款拒绝通知
- **WHEN** 提款申请被拒绝
- **THEN** 系统向订阅 `withdrawal.rejected` 事件的 Webhook URL 发送通知，包含拒绝原因

### Requirement: 系统必须确保 Webhook 调用的安全性
Webhook 请求必须包含签名，外部系统需验证签名确保请求来自合法来源。

#### Scenario: Webhook 请求包含签名
- **WHEN** 系统发送 Webhook 请求
- **THEN** 请求头包含 `X-Webhook-Signature` 字段，值为请求体的 HMAC-SHA256 签名

#### Scenario: 外部系统验证签名
- **WHEN** 外部系统收到 Webhook 请求
- **THEN** 使用 Webhook 密钥验证签名，签名不匹配则拒绝请求

#### Scenario: 请求包含时间戳防重放
- **WHEN** 系统发送 Webhook 请求
- **THEN** 请求头包含 `X-Webhook-Timestamp` 字段，外部系统可验证请求时效性

#### Scenario: Webhook 请求来源 IP 验证
- **WHEN** 系统发送 Webhook 请求
- **THEN** 请求头包含 `X-Webhook-Source` 字段，标识请求来源服务器 IP

### Requirement: 系统必须支持 Webhook 重试机制
当 Webhook 调用失败时，系统必须自动重试，确保事件通知的可靠性。

#### Scenario: Webhook 调用失败自动重试
- **WHEN** 外部系统的 Webhook URL 返回 5xx 错误或请求超时
- **THEN** 系统在 1 分钟、5 分钟、30 分钟后分别重试，最多重试 3 次

#### Scenario: Webhook 最终失败记录
- **WHEN** Webhook 重试 3 次后仍然失败
- **THEN** 系统将该事件标记为失败，记录到失败日志，不再重试

#### Scenario: 查询失败的 Webhook 记录
- **WHEN** 外部系统调用 `/api/webhooks/failed` 接口
- **THEN** 系统返回所有发送失败的 Webhook 事件列表

#### Scenario: 手动重新发送失败的 Webhook
- **WHEN** 外部系统调用 `/api/webhooks/retry/{event_id}` 接口
- **THEN** 系统重新发送该 Webhook 事件

### Requirement: 系统必须记录所有 Webhook 调用日志
所有 Webhook 发送记录（成功和失败）都必须被记录，便于追踪和排查问题。

#### Scenario: 记录 Webhook 发送日志
- **WHEN** 系统发送 Webhook 请求
- **THEN** 系统记录发送时间、目标 URL、事件类型、请求体、响应状态码、响应时间等信息

#### Scenario: 查询 Webhook 调用历史
- **WHEN** 外部系统调用 `/api/webhooks/{webhook_id}/logs` 接口
- **THEN** 系统返回该 Webhook 的调用历史记录

#### Scenario: 按事件类型筛选日志
- **WHEN** 外部系统调用 `/api/webhooks/logs?event_type=bet.created` 接口
- **THEN** 系统只返回投注创建事件的 Webhook 日志

#### Scenario: 查看单次 Webhook 调用详情
- **WHEN** 外部系统调用 `/api/webhooks/logs/{log_id}` 接口
- **THEN** 系统返回该次 Webhook 调用的完整详情，包括请求和响应内容

### Requirement: 系统必须支持 Webhook 测试功能
外部系统必须能够测试 Webhook 配置是否正确，无需等待真实事件发生。

#### Scenario: 发送测试 Webhook
- **WHEN** 外部系统调用 `/api/webhooks/{webhook_id}/test` 接口
- **THEN** 系统向该 Webhook URL 发送测试事件，包含模拟数据

#### Scenario: 测试 Webhook 响应验证
- **WHEN** 测试 Webhook 发送成功
- **THEN** 系统验证外部系统返回的状态码，并将测试结果返回给调用者

#### Scenario: 批量测试所有 Webhook
- **WHEN** 外部系统调用 `/api/webhooks/test-all` 接口
- **THEN** 系统向所有已配置的 Webhook URL 发送测试事件，返回测试结果汇总

### Requirement: 系统必须支持 Webhook 禁用和启用
外部系统必须能够临时禁用或启用 Webhook，而不删除配置。

#### Scenario: 禁用 Webhook
- **WHEN** 外部系统调用 `/api/webhooks/{webhook_id}/disable` 接口
- **THEN** 系统停止向该 Webhook URL 发送事件通知，但保留配置

#### Scenario: 启用已禁用的 Webhook
- **WHEN** 外部系统调用 `/api/webhooks/{webhook_id}/enable` 接口
- **THEN** 系统恢复向该 Webhook URL 发送事件通知

#### Scenario: 自动禁用连续失败的 Webhook
- **WHEN** 某个 Webhook 连续失败超过 10 次
- **THEN** 系统自动禁用该 Webhook，并向外部系统发送告警邮件或通知
