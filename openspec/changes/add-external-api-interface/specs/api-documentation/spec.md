## ADDED Requirements

### Requirement: 系统必须提供完整的 API 文档
系统必须提供详细的 API 接口文档，包括所有接口的请求方法、参数、响应格式和示例。

#### Scenario: 访问在线 API 文档
- **WHEN** 用户访问 `/api/docs` 路径
- **THEN** 系统展示基于 Swagger/OpenAPI 的交互式 API 文档界面

#### Scenario: 查看单个接口详情
- **WHEN** 用户在文档中点击某个 API 接口
- **THEN** 系统展示该接口的完整说明，包括请求路径、方法、参数列表、响应示例、错误码等

#### Scenario: 在文档中测试 API
- **WHEN** 用户在文档页面填写参数并点击"试一试"按钮
- **THEN** 系统发送真实的 API 请求并展示响应结果

#### Scenario: 文档支持多语言
- **WHEN** 用户切换文档语言为英文
- **THEN** 系统展示英文版本的 API 文档

### Requirement: API 文档必须包含认证说明
文档必须详细说明如何进行 API 认证，包括获取 API Key、生成 Token 等步骤。

#### Scenario: 查看认证指南
- **WHEN** 用户访问文档的"认证"章节
- **THEN** 系统展示完整的认证流程说明，包括如何申请 API Key、如何获取 Token、如何在请求中使用 Token

#### Scenario: 认证相关的代码示例
- **WHEN** 用户查看认证章节
- **THEN** 系统提供多种编程语言（PHP、Python、JavaScript、Java）的认证代码示例

#### Scenario: 查看权限范围说明
- **WHEN** 用户查看 API Key 权限文档
- **THEN** 系统展示所有可用的权限范围（Scope）及其含义

### Requirement: API 文档必须包含完整的请求和响应示例
每个 API 接口的文档必须包含真实的请求和响应示例，帮助开发者快速理解。

#### Scenario: 查看请求示例
- **WHEN** 用户查看某个 API 接口的请求示例
- **THEN** 系统展示包含所有必需参数和可选参数的完整请求示例（curl、HTTP 原始请求）

#### Scenario: 查看响应示例
- **WHEN** 用户查看某个 API 接口的响应示例
- **THEN** 系统展示成功响应和各种错误响应的 JSON 示例

#### Scenario: 查看分页响应格式
- **WHEN** 用户查看支持分页的接口文档
- **THEN** 系统说明分页参数（page、page_size）和响应中的分页元数据格式

### Requirement: 系统必须提供 API 参考手册下载
开发者必须能够下载离线版本的 API 文档，便于无网络环境下查阅。

#### Scenario: 下载 PDF 格式文档
- **WHEN** 用户点击"下载 PDF"按钮
- **THEN** 系统生成并下载完整的 API 文档 PDF 文件

#### Scenario: 下载 OpenAPI 规范文件
- **WHEN** 用户点击"下载 OpenAPI 规范"按钮
- **THEN** 系统下载 openapi.json 或 openapi.yaml 文件，可用于代码生成工具

#### Scenario: 导出 Postman Collection
- **WHEN** 用户点击"导出 Postman Collection"按钮
- **THEN** 系统生成并下载 Postman Collection JSON 文件，可直接导入 Postman

### Requirement: 系统必须提供多种语言的 SDK
系统必须提供主流编程语言的 SDK，降低集成难度。

#### Scenario: 下载 PHP SDK
- **WHEN** 用户访问 SDK 下载页面，选择 PHP
- **THEN** 系统提供 PHP SDK 的下载链接或 Composer 安装命令

#### Scenario: 查看 SDK 使用文档
- **WHEN** 用户查看某个 SDK 的文档
- **THEN** 系统展示该 SDK 的安装方法、初始化配置、常用功能示例

#### Scenario: SDK 包含所有 API 接口封装
- **WHEN** 开发者使用 SDK 调用 API
- **THEN** SDK 提供所有 API 接口的封装方法，自动处理认证、签名、错误处理等

#### Scenario: SDK 支持多个语言版本
- **WHEN** 用户访问 SDK 下载页面
- **THEN** 系统提供 PHP、Python、JavaScript（Node.js）、Java、Go 等主流语言的 SDK

### Requirement: 系统必须提供快速入门指南
文档必须包含快速入门教程，帮助新用户在 10 分钟内完成首次 API 调用。

#### Scenario: 查看快速入门教程
- **WHEN** 用户访问文档的"快速入门"章节
- **THEN** 系统展示从零开始的步骤指南：注册账号 → 获取 API Key → 安装 SDK → 发起第一次请求

#### Scenario: 快速入门包含可运行代码
- **WHEN** 用户查看快速入门代码示例
- **THEN** 所有代码示例都是完整可运行的，用户只需替换 API Key 即可执行

#### Scenario: 快速入门包含常见场景
- **WHEN** 用户完成基础快速入门
- **THEN** 系统提供常见业务场景的示例：嵌入游戏窗口、查询用户余额、获取投注记录等

### Requirement: API 文档必须包含错误码说明
文档必须详细列出所有可能的错误码及其含义和解决方法。

#### Scenario: 查看错误码列表
- **WHEN** 用户访问文档的"错误码"章节
- **THEN** 系统展示所有错误码的列表，包括错误码、HTTP 状态码、错误描述、可能原因、解决方法

#### Scenario: 搜索特定错误码
- **WHEN** 用户在文档中搜索错误码"AUTH_001"
- **THEN** 系统跳转到该错误码的详细说明页面

#### Scenario: 错误码分类展示
- **WHEN** 用户查看错误码文档
- **THEN** 系统按类别（认证错误、参数错误、业务错误、系统错误）组织错误码

### Requirement: 系统必须提供 Webhook 集成文档
文档必须详细说明如何配置和接收 Webhook 事件通知。

#### Scenario: 查看 Webhook 配置指南
- **WHEN** 用户访问 Webhook 文档
- **THEN** 系统展示如何创建 Webhook 订阅、验证签名、处理重试的完整指南

#### Scenario: 查看 Webhook 事件类型
- **WHEN** 用户查看 Webhook 事件列表
- **THEN** 系统列出所有可订阅的事件类型及其触发时机和负载格式

#### Scenario: Webhook 验证签名示例
- **WHEN** 用户查看 Webhook 安全文档
- **THEN** 系统提供多种语言验证 HMAC 签名的代码示例

### Requirement: 系统必须提供游戏窗口嵌入文档
文档必须详细说明如何在外部网站中嵌入游戏窗口。

#### Scenario: 查看游戏窗口嵌入指南
- **WHEN** 用户访问游戏嵌入文档
- **THEN** 系统展示如何使用 iframe 嵌入游戏窗口，包括参数说明、安全配置、样式定制等

#### Scenario: 查看 postMessage 通信文档
- **WHEN** 用户查看游戏窗口与父页面通信文档
- **THEN** 系统列出所有支持的 postMessage 事件及其数据格式

#### Scenario: 游戏窗口定制化示例
- **WHEN** 用户查看游戏窗口定制文档
- **THEN** 系统提供修改主题、隐藏模块、自定义布局的示例代码

### Requirement: API 文档必须包含最佳实践建议
文档必须提供 API 使用的最佳实践和性能优化建议。

#### Scenario: 查看性能优化建议
- **WHEN** 用户访问最佳实践章节
- **THEN** 系统提供 API 调用频率控制、批量请求、缓存策略等优化建议

#### Scenario: 查看安全性建议
- **WHEN** 用户查看安全最佳实践
- **THEN** 系统说明 API Key 保护、HTTPS 使用、签名验证等安全措施

#### Scenario: 查看错误处理建议
- **WHEN** 用户查看错误处理最佳实践
- **THEN** 系统提供重试策略、超时设置、降级方案等建议

### Requirement: 系统必须提供 API 更新日志
文档必须包含 API 的版本历史和更新说明。

#### Scenario: 查看 API 更新日志
- **WHEN** 用户访问更新日志页面
- **THEN** 系统按时间倒序展示 API 的所有版本更新记录

#### Scenario: 查看某个版本的变更详情
- **WHEN** 用户点击某个版本号
- **THEN** 系统展示该版本的新增功能、修改功能、废弃功能、破坏性变更等详细信息

#### Scenario: 订阅 API 更新通知
- **WHEN** 用户在文档中订阅更新通知
- **THEN** 系统在 API 有重要更新时通过邮件通知用户

### Requirement: 系统必须提供开发者社区和支持渠道
文档必须引导开发者获取技术支持和参与社区交流。

#### Scenario: 查看技术支持联系方式
- **WHEN** 用户访问文档的"技术支持"页面
- **THEN** 系统展示技术支持邮箱、在线客服、工单系统等联系方式

#### Scenario: 访问开发者论坛
- **WHEN** 用户点击"开发者社区"链接
- **THEN** 系统跳转到开发者论坛或讨论区

#### Scenario: 查看 FAQ 常见问题
- **WHEN** 用户访问 FAQ 页面
- **THEN** 系统展示开发者常见问题和解答
