## ADDED Requirements

### Requirement: 系统必须提供投注记录同步接口
外部系统必须能够实时或定期获取用户的投注记录数据。

#### Scenario: 查询指定时间范围的投注记录
- **WHEN** 外部系统调用 `/api/bets?start_time=xxx&end_time=xxx` 接口
- **THEN** 系统返回该时间范围内的所有投注记录，包括投注号码、金额、时间、状态等信息

#### Scenario: 查询指定用户的投注记录
- **WHEN** 外部系统调用 `/api/bets?user_id=123` 接口
- **THEN** 系统返回该用户的投注记录列表

#### Scenario: 分页查询大量投注记录
- **WHEN** 外部系统调用 `/api/bets?page=1&page_size=100` 接口
- **THEN** 系统返回第一页的 100 条记录，并包含总记录数和分页信息

#### Scenario: 查询单条投注详情
- **WHEN** 外部系统调用 `/api/bets/{bet_id}` 接口
- **THEN** 系统返回该投注的完整详细信息，包括所有投注项和赔率

### Requirement: 系统必须提供账户流水同步接口
外部系统必须能够获取用户账户的所有资金变动记录。

#### Scenario: 查询账户流水记录
- **WHEN** 外部系统调用 `/api/transactions?user_id=123&type=all` 接口
- **THEN** 系统返回该用户的所有流水记录，包括充值、提款、投注、中奖、退水等

#### Scenario: 按流水类型筛选
- **WHEN** 外部系统调用 `/api/transactions?type=bet` 接口
- **THEN** 系统只返回投注类型的流水记录

#### Scenario: 查询账户余额变化
- **WHEN** 外部系统调用 `/api/balance?user_id=123` 接口
- **THEN** 系统返回当前余额和余额变化历史

#### Scenario: 实时监听流水变化
- **WHEN** 外部系统订阅流水变化事件
- **THEN** 每当有新的流水记录产生时，系统推送通知到外部系统

### Requirement: 系统必须提供开奖结果同步接口
外部系统必须能够获取各彩种的开奖结果数据。

#### Scenario: 查询最新开奖结果
- **WHEN** 外部系统调用 `/api/lottery-results/latest?game_type=ssc` 接口
- **THEN** 系统返回时时彩的最新一期开奖号码和开奖时间

#### Scenario: 查询历史开奖记录
- **WHEN** 外部系统调用 `/api/lottery-results?game_type=ssc&limit=50` 接口
- **THEN** 系统返回时时彩最近 50 期的开奖记录

#### Scenario: 订阅开奖结果推送
- **WHEN** 外部系统通过 WebSocket 订阅开奖结果
- **THEN** 每次开奖后，系统立即推送开奖结果到外部系统

#### Scenario: 查询指定期号的开奖结果
- **WHEN** 外部系统调用 `/api/lottery-results/{issue_number}` 接口
- **THEN** 系统返回该期号的开奖详情

### Requirement: 系统必须提供游戏统计数据同步接口
外部系统必须能够获取各种统计数据，用于分析和报表。

#### Scenario: 查询用户投注统计
- **WHEN** 外部系统调用 `/api/statistics/bets?user_id=123&period=daily` 接口
- **THEN** 系统返回该用户按日统计的投注金额、笔数、中奖金额等数据

#### Scenario: 查询游戏类型统计
- **WHEN** 外部系统调用 `/api/statistics/games?date=2024-01-01` 接口
- **THEN** 系统返回各游戏类型当天的投注总额、中奖总额、参与人数等

#### Scenario: 查询代理下级数据汇总
- **WHEN** 外部系统调用 `/api/statistics/agent?agent_id=456` 接口
- **THEN** 系统返回该代理下所有用户的数据汇总

#### Scenario: 导出统计报表
- **WHEN** 外部系统调用 `/api/statistics/export?format=csv` 接口
- **THEN** 系统生成 CSV 格式的统计报表文件供下载

### Requirement: 系统必须支持增量数据同步
外部系统必须能够只获取自上次同步后新增或变更的数据，提高同步效率。

#### Scenario: 使用时间戳进行增量同步
- **WHEN** 外部系统调用 `/api/bets?since=1640000000` 接口
- **THEN** 系统只返回该时间戳之后创建或更新的投注记录

#### Scenario: 使用同步标记进行增量同步
- **WHEN** 外部系统调用 `/api/sync?cursor=xxx` 接口，传入上次同步返回的游标
- **THEN** 系统返回从该游标位置之后的所有变更数据

#### Scenario: 获取同步状态
- **WHEN** 外部系统调用 `/api/sync/status` 接口
- **THEN** 系统返回当前同步进度、最后同步时间、待同步数据量等信息

### Requirement: 系统必须支持批量数据同步
外部系统必须能够一次性获取多个用户或多种类型的数据，减少 API 调用次数。

#### Scenario: 批量查询多个用户的数据
- **WHEN** 外部系统调用 `/api/users/batch?ids=1,2,3&include=balance,bets` 接口
- **THEN** 系统返回这些用户的余额和投注数据

#### Scenario: 一次性获取多种数据类型
- **WHEN** 外部系统调用 `/api/sync/full?types=bets,transactions,results` 接口
- **THEN** 系统返回包含投注、流水、开奖结果的完整数据包

#### Scenario: 批量操作限制
- **WHEN** 外部系统批量查询超过 1000 个用户的数据
- **THEN** 系统返回 400 Bad Request 错误，提示批量查询数量超限

### Requirement: 系统必须保证数据同步的一致性
同步的数据必须与系统内部数据保持一致，不能出现数据不一致的情况。

#### Scenario: 事务性数据同步
- **WHEN** 外部系统查询某个投注的相关流水记录
- **THEN** 系统确保投注记录和对应的流水记录状态一致

#### Scenario: 数据校验接口
- **WHEN** 外部系统调用 `/api/sync/verify` 接口，提供本地数据的校验和
- **THEN** 系统返回数据是否一致，以及不一致的数据项

#### Scenario: 处理并发更新
- **WHEN** 多个外部系统同时查询同一数据
- **THEN** 系统使用数据库事务隔离，确保每个查询返回的数据是一致的快照

### Requirement: 系统必须支持数据同步的错误重试
当数据同步失败时，外部系统必须能够重试获取数据，系统需支持幂等性。

#### Scenario: 使用请求 ID 实现幂等性
- **WHEN** 外部系统在请求头中传入 `X-Request-Id`，并重试相同的请求
- **THEN** 系统识别重复请求，返回相同的响应结果

#### Scenario: 查询失败的同步任务
- **WHEN** 外部系统调用 `/api/sync/failures` 接口
- **THEN** 系统返回所有同步失败的任务列表和失败原因

#### Scenario: 重新执行失败的同步
- **WHEN** 外部系统调用 `/api/sync/retry?task_id=xxx` 接口
- **THEN** 系统重新执行该同步任务并返回结果
