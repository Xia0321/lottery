## ADDED Requirements

### Requirement: 外部系统必须通过 API Key 进行身份认证
系统必须为每个外部系统分配唯一的 API Key，用于标识和验证外部系统的身份。API Key 必须在请求头中传递。

#### Scenario: 使用有效 API Key 访问接口
- **WHEN** 外部系统在请求头中提供有效的 API Key（格式：`X-API-Key: <key>`）
- **THEN** 系统验证通过，允许访问 API 资源

#### Scenario: 使用无效 API Key 访问接口
- **WHEN** 外部系统提供无效或已过期的 API Key
- **THEN** 系统返回 401 Unauthorized 错误，包含错误提示信息

#### Scenario: 缺少 API Key
- **WHEN** 外部系统请求中未包含 API Key
- **THEN** 系统返回 401 Unauthorized 错误，提示需要提供 API Key

### Requirement: 系统必须支持请求签名验证机制
外部系统的每个请求必须包含签名，防止参数篡改和未授权访问。签名使用 HMAC-SHA256 算法。

#### Scenario: 使用正确签名访问接口
- **WHEN** 外部系统对请求参数进行排序并使用 API Secret 生成 HMAC-SHA256 签名，在请求中传入 `sign` 参数
- **THEN** 系统验证签名正确，允许访问资源

#### Scenario: 签名验证失败
- **WHEN** 外部系统提供的签名不正确或缺少签名参数
- **THEN** 系统返回 401 Unauthorized 错误，提示签名验证失败

#### Scenario: 防重放攻击 - 时间戳验证
- **WHEN** 外部系统在请求中包含 `timestamp` 参数（当前时间戳）
- **THEN** 系统验证时间戳在 5 分钟内有效，超过 5 分钟的请求被拒绝

### Requirement: 系统必须提供 API Key 管理功能
管理员必须能够为外部系统创建、查看、禁用和删除 API Key。

#### Scenario: 管理员创建新的 API Key
- **WHEN** 管理员在管理后台创建新的 API Key，并指定外部系统名称和权限范围
- **THEN** 系统生成唯一的 API Key 和 API Secret，显示给管理员（仅显示一次）

#### Scenario: 管理员禁用 API Key
- **WHEN** 管理员禁用某个 API Key
- **THEN** 该 API Key 立即失效，使用该 Key 的所有 Token 也同时失效

#### Scenario: 管理员查看 API Key 使用记录
- **WHEN** 管理员查看某个 API Key 的使用记录
- **THEN** 系统显示该 Key 的调用次数、最后调用时间和调用来源 IP

### Requirement: 系统必须记录所有 API 认证日志
所有认证相关的操作（成功和失败）都必须被记录，用于安全审计。

#### Scenario: 记录成功的认证请求
- **WHEN** 外部系统成功通过 API Key 或 Token 认证
- **THEN** 系统记录认证时间、外部系统标识、请求 IP 和访问的资源路径

#### Scenario: 记录失败的认证请求
- **WHEN** 外部系统认证失败（无效 Key/Token、权限不足等）
- **THEN** 系统记录失败原因、请求 IP、尝试使用的 Key/Token（脱敏处理）

### Requirement: 系统必须支持权限范围控制 **（第二期功能）**
每个 API Key 必须能够配置访问权限范围（Scope），限制其可访问的 API 资源。

**注**：第一期所有 API Key 权限相同，第二期再实现细粒度权限控制。

#### Scenario: API Key 访问允许范围内的资源
- **WHEN** 外部系统使用具有 `read:user` 权限的 API Key 访问用户数据查询接口
- **THEN** 系统验证权限通过，返回数据

#### Scenario: API Key 访问超出权限范围的资源
- **WHEN** 外部系统使用仅具有 `read:user` 权限的 API Key 尝试访问游戏数据接口
- **THEN** 系统返回 403 Forbidden 错误，提示权限不足

#### Scenario: 管理员修改 API Key 权限范围
- **WHEN** 管理员修改某个 API Key 的权限范围
- **THEN** 系统立即应用新的权限设置，后续请求使用新权限验证

### Requirement: 系统必须支持 IP 白名单限制 **（第二期功能）**
API Key 必须能够配置 IP 白名单，只允许来自指定 IP 地址的请求。

**注**：第一期暂不做 IP 限制，第二期根据需要增加。

#### Scenario: 来自白名单 IP 的请求
- **WHEN** 外部系统从配置的白名单 IP 地址发起请求
- **THEN** 系统允许继续认证流程

#### Scenario: 来自非白名单 IP 的请求
- **WHEN** 外部系统从未在白名单中的 IP 地址发起请求
- **THEN** 系统返回 403 Forbidden 错误，拒绝访问并记录日志

#### Scenario: 管理员更新 IP 白名单
- **WHEN** 管理员为某个 API Key 添加或移除白名单 IP
- **THEN** 系统立即应用新的 IP 限制规则
