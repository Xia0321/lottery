## ADDED Requirements

### Requirement: 系统必须提供用户基本信息查询接口
外部系统必须能够查询用户的基本信息，包括用户名、昵称、注册时间等。

#### Scenario: 查询单个用户信息
- **WHEN** 外部系统调用 `/api/users/{user_id}` 接口
- **THEN** 系统返回该用户的基本信息，包括用户 ID、用户名、昵称、注册时间、状态等

#### Scenario: 查询不存在的用户
- **WHEN** 外部系统查询不存在的用户 ID
- **THEN** 系统返回 404 Not Found 错误

#### Scenario: 批量查询用户信息
- **WHEN** 外部系统调用 `/api/users?ids=1,2,3` 接口
- **THEN** 系统返回这些用户的基本信息列表

#### Scenario: 根据用户名查询用户
- **WHEN** 外部系统调用 `/api/users?username=test001` 接口
- **THEN** 系统返回用户名为 test001 的用户信息

### Requirement: 系统必须提供用户账户余额查询接口
外部系统必须能够实时查询用户的账户余额。

#### Scenario: 查询用户当前余额
- **WHEN** 外部系统调用 `/api/users/{user_id}/balance` 接口
- **THEN** 系统返回该用户的当前可用余额和冻结余额

#### Scenario: 查询多个用户的余额
- **WHEN** 外部系统调用 `/api/users/balances?ids=1,2,3` 接口
- **THEN** 系统返回这些用户的余额列表

#### Scenario: 余额变化通知
- **WHEN** 用户余额发生变化（投注、中奖、充值、提款）
- **THEN** 系统通过 Webhook 或 WebSocket 通知外部系统余额变化事件

#### Scenario: 查询余额历史记录
- **WHEN** 外部系统调用 `/api/users/{user_id}/balance-history?limit=30` 接口
- **THEN** 系统返回该用户最近 30 条余额变化记录

### Requirement: 系统必须提供用户投注历史查询接口
外部系统必须能够查询用户的所有投注记录。

#### Scenario: 查询用户所有投注
- **WHEN** 外部系统调用 `/api/users/{user_id}/bets` 接口
- **THEN** 系统返回该用户的所有投注记录列表

#### Scenario: 按时间范围查询投注
- **WHEN** 外部系统调用 `/api/users/{user_id}/bets?start_date=2024-01-01&end_date=2024-01-31` 接口
- **THEN** 系统返回该用户在指定时间范围内的投注记录

#### Scenario: 按投注状态筛选
- **WHEN** 外部系统调用 `/api/users/{user_id}/bets?status=win` 接口
- **THEN** 系统只返回该用户已中奖的投注记录

#### Scenario: 按游戏类型筛选
- **WHEN** 外部系统调用 `/api/users/{user_id}/bets?game_type=ssc` 接口
- **THEN** 系统只返回该用户的时时彩投注记录

### Requirement: 系统必须提供用户资金流水查询接口
外部系统必须能够查询用户的所有资金变动记录。

#### Scenario: 查询用户所有流水
- **WHEN** 外部系统调用 `/api/users/{user_id}/transactions` 接口
- **THEN** 系统返回该用户的所有流水记录，包括类型、金额、时间、余额等

#### Scenario: 按流水类型筛选
- **WHEN** 外部系统调用 `/api/users/{user_id}/transactions?type=deposit` 接口
- **THEN** 系统只返回该用户的充值流水记录

#### Scenario: 查询流水统计
- **WHEN** 外部系统调用 `/api/users/{user_id}/transactions/summary?period=monthly` 接口
- **THEN** 系统返回该用户按月统计的充值、提款、投注、中奖金额汇总

#### Scenario: 导出流水记录
- **WHEN** 外部系统调用 `/api/users/{user_id}/transactions/export?format=excel` 接口
- **THEN** 系统生成该用户的流水记录 Excel 文件供下载

### Requirement: 系统必须提供用户创建和更新接口 **（第二期功能）**
外部系统必须能够创建新用户或更新现有用户信息。

**注**：第一期只提供查询接口，用户通过管理后台创建。第二期根据需要开放创建接口。

#### Scenario: 创建新用户
- **WHEN** 外部系统调用 `/api/users` 接口，提交用户名、密码等信息
- **THEN** 系统创建新用户并返回用户 ID 和基本信息

#### Scenario: 用户名已存在
- **WHEN** 外部系统尝试创建已存在用户名的用户
- **THEN** 系统返回 409 Conflict 错误，提示用户名已被使用

#### Scenario: 更新用户信息
- **WHEN** 外部系统调用 `/api/users/{user_id}` 接口，使用 PATCH 方法更新昵称
- **THEN** 系统更新用户昵称并返回更新后的用户信息

#### Scenario: 批量创建用户
- **WHEN** 外部系统调用 `/api/users/batch` 接口，提交多个用户的信息
- **THEN** 系统批量创建用户，返回成功和失败的结果列表

### Requirement: 系统必须提供用户状态管理接口 **（第二期功能）**
外部系统必须能够管理用户的状态，如启用、禁用、锁定等。

**注**：第一期暂不提供，通过管理后台操作。

#### Scenario: 禁用用户账户
- **WHEN** 外部系统调用 `/api/users/{user_id}/disable` 接口
- **THEN** 系统将该用户状态设置为禁用，用户无法登录和投注

#### Scenario: 启用用户账户
- **WHEN** 外部系统调用 `/api/users/{user_id}/enable` 接口
- **THEN** 系统将该用户状态设置为正常，用户可以正常使用

#### Scenario: 锁定用户账户
- **WHEN** 外部系统调用 `/api/users/{user_id}/lock` 接口，提供锁定原因
- **THEN** 系统锁定该用户账户，并记录锁定原因和时间

#### Scenario: 查询用户状态历史
- **WHEN** 外部系统调用 `/api/users/{user_id}/status-history` 接口
- **THEN** 系统返回该用户的所有状态变更记录

### Requirement: 系统必须提供用户充值和提款接口 **（第二期功能）**
外部系统必须能够为用户发起充值或提款操作。

**注**：第一期暂不提供，通过管理后台操作。第二期需要增加事务、幂等性等安全措施后再开放。

#### Scenario: 为用户充值
- **WHEN** 外部系统调用 `/api/users/{user_id}/deposit` 接口，提交充值金额和备注
- **THEN** 系统增加用户余额，记录充值流水，返回充值成功信息

#### Scenario: 充值金额验证
- **WHEN** 外部系统提交的充值金额小于或等于 0
- **THEN** 系统返回 400 Bad Request 错误，提示充值金额必须大于 0

#### Scenario: 用户申请提款
- **WHEN** 外部系统调用 `/api/users/{user_id}/withdraw` 接口，提交提款金额
- **THEN** 系统创建提款申请，冻结相应金额，返回提款申请 ID

#### Scenario: 余额不足提款
- **WHEN** 用户可用余额小于提款金额
- **THEN** 系统返回 400 Bad Request 错误，提示余额不足

#### Scenario: 审核提款申请
- **WHEN** 外部系统调用 `/api/withdrawals/{withdrawal_id}/approve` 接口
- **THEN** 系统完成提款，扣除用户余额，更新提款状态为已完成

### Requirement: 系统必须提供用户投注限额查询和设置接口 **（第二期功能）**
外部系统必须能够查询和设置用户的投注限额。

**注**：第一期可提供查询接口，设置接口第二期再开放。

#### Scenario: 查询用户投注限额
- **WHEN** 外部系统调用 `/api/users/{user_id}/bet-limits` 接口
- **THEN** 系统返回该用户的单注最高金额、单期最高金额等限额配置

#### Scenario: 设置用户投注限额
- **WHEN** 外部系统调用 `/api/users/{user_id}/bet-limits` 接口，使用 PUT 方法更新限额
- **THEN** 系统更新用户的投注限额配置，并记录变更日志

#### Scenario: 查询限额使用情况
- **WHEN** 外部系统调用 `/api/users/{user_id}/bet-limits/usage` 接口
- **THEN** 系统返回用户当前已使用的投注额度和剩余额度

#### Scenario: 重置投注限额
- **WHEN** 外部系统调用 `/api/users/{user_id}/bet-limits/reset` 接口
- **THEN** 系统将用户投注限额重置为默认值
