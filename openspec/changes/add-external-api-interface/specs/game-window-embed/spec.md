## ADDED Requirements

### Requirement: 系统必须提供可嵌入的游戏窗口接口
系统必须提供一个独立的游戏窗口页面，外部系统可以通过 iframe 或 Web Component 方式嵌入到自己的页面中。

#### Scenario: 外部系统通过 iframe 嵌入游戏窗口
- **WHEN** 外部系统在其页面中使用 `<iframe src="https://lottery.example.com/embed/game?token=xxx">` 嵌入游戏窗口
- **THEN** 系统返回完整的游戏界面，用户可以正常进行游戏操作

#### Scenario: 未经授权的嵌入请求
- **WHEN** 外部系统尝试嵌入游戏窗口但未提供有效的 Token 参数
- **THEN** 系统显示授权错误页面，提示需要有效的访问凭证

#### Scenario: 跨域嵌入的安全验证
- **WHEN** 外部系统从已授权的域名嵌入游戏窗口
- **THEN** 系统正确设置 CORS 头，允许跨域访问

### Requirement: 游戏窗口必须支持自定义配置
外部系统必须能够通过 URL 参数或配置对象自定义游戏窗口的外观和行为。

#### Scenario: 自定义游戏窗口主题颜色
- **WHEN** 外部系统在嵌入 URL 中传入 `theme=blue` 参数
- **THEN** 游戏窗口应用蓝色主题样式

#### Scenario: 隐藏特定功能模块
- **WHEN** 外部系统在嵌入 URL 中传入 `hide=menu,footer` 参数
- **THEN** 游戏窗口不显示菜单和底部栏

#### Scenario: 指定默认游戏类型
- **WHEN** 外部系统在嵌入 URL 中传入 `game=ssc` 参数
- **THEN** 游戏窗口默认打开时时彩游戏

### Requirement: 游戏窗口必须支持用户身份传递
外部系统必须能够安全地传递用户身份信息到游戏窗口，实现单点登录。

#### Scenario: 使用加密 Token 传递用户身份
- **WHEN** 外部系统在嵌入 URL 中传入经过签名的用户 Token
- **THEN** 系统验证 Token 签名，自动以该用户身份登录游戏窗口

#### Scenario: Token 验证失败
- **WHEN** 外部系统传入的用户 Token 签名无效或已过期
- **THEN** 系统拒绝登录，显示错误提示

#### Scenario: 用户信息不存在
- **WHEN** Token 中的用户 ID 在系统中不存在
- **THEN** 系统根据配置自动创建用户或返回错误提示

### Requirement: 游戏窗口必须支持与父页面的通信
游戏窗口必须能够通过 postMessage API 与外部系统的父页面进行安全通信。

#### Scenario: 游戏窗口向父页面发送余额变化事件
- **WHEN** 用户在游戏窗口中完成投注，账户余额发生变化
- **THEN** 游戏窗口通过 postMessage 向父页面发送 `{type: 'balance_changed', balance: 1000}` 消息

#### Scenario: 父页面向游戏窗口发送刷新指令
- **WHEN** 外部系统的父页面通过 postMessage 发送 `{type: 'refresh_game'}` 消息
- **THEN** 游戏窗口刷新当前游戏数据

#### Scenario: 验证 postMessage 来源
- **WHEN** 游戏窗口接收到 postMessage 消息
- **THEN** 系统验证消息来源域名是否在白名单中，拒绝非法来源的消息

### Requirement: 游戏窗口必须适配移动端和响应式布局
游戏窗口必须能够在不同设备和屏幕尺寸下正常显示和操作。

#### Scenario: 在移动端浏览器中嵌入游戏窗口
- **WHEN** 用户在手机浏览器中访问包含游戏窗口的外部页面
- **THEN** 游戏窗口自动适配移动端布局，所有操作按钮可触摸操作

#### Scenario: 窗口尺寸变化时自动调整
- **WHEN** 外部页面调整 iframe 的尺寸
- **THEN** 游戏窗口内容自动响应式调整布局

#### Scenario: 横竖屏切换
- **WHEN** 移动设备从竖屏切换到横屏
- **THEN** 游戏窗口重新布局以适应新的屏幕方向

### Requirement: 游戏窗口必须支持多语言
游戏窗口必须能够根据外部系统的语言设置显示对应语言的界面。

#### Scenario: 指定游戏窗口显示语言
- **WHEN** 外部系统在嵌入 URL 中传入 `lang=en` 参数
- **THEN** 游戏窗口以英文显示所有界面文本

#### Scenario: 使用默认语言
- **WHEN** 外部系统未指定语言参数
- **THEN** 游戏窗口使用简体中文作为默认语言

#### Scenario: 动态切换语言
- **WHEN** 父页面通过 postMessage 发送语言切换指令
- **THEN** 游戏窗口动态切换到指定语言，无需刷新页面

### Requirement: 游戏窗口必须支持性能优化
游戏窗口必须优化加载速度和运行性能，确保良好的用户体验。

#### Scenario: 首次加载游戏窗口
- **WHEN** 外部系统首次嵌入游戏窗口
- **THEN** 系统在 3 秒内完成页面加载并显示可交互界面

#### Scenario: 资源懒加载
- **WHEN** 游戏窗口加载完成
- **THEN** 非关键资源（历史记录、规则说明等）延迟加载，不影响主功能使用

#### Scenario: 缓存静态资源
- **WHEN** 用户再次访问游戏窗口
- **THEN** 系统利用浏览器缓存，减少重复资源加载时间
