# 安全漏洞修复示例代码

本文档提供所有安全漏洞的具体修复示例，可直接用于代码修复。

---

## 1. SQL 注入修复

### 1.1 登录页面修复

#### 修复前（不安全）

**文件**: `web/agent/login.php` (第 40 行)

```php
$user = $_POST['user'];
$pass = md5($_POST['pass'] . $config['apass']);
$sql = "SELECT * FROM `$tb_user` WHERE username='$user' and userpass='$pass' and ifagent=1 ";
$temp = $msql->arr($sql, 1);
```

#### 修复后（安全）

```php
$user = $_POST['user'];
$pass = md5($_POST['pass'] . $config['apass']);

// 方案 1: 使用 PDO (推荐)
$stmt = $pdo->prepare("SELECT * FROM `x_user` WHERE username = ? AND userpass = ? AND ifagent = 1");
$stmt->execute([$user, $pass]);
$temp = $stmt->fetch(PDO::FETCH_ASSOC);

// 方案 2: 使用 mysqli prepared statement
$stmt = $mysqli->prepare("SELECT * FROM `x_user` WHERE username = ? AND userpass = ? AND ifagent = 1");
$stmt->bind_param("ss", $user, $pass);
$stmt->execute();
$result = $stmt->get_result();
$temp = $result->fetch_assoc();
```

---

### 1.2 批量操作修复

#### 修复前（不安全）

**文件**: `web/hide/user.php` (第 651 行)

```php
$ugroup = $_POST['ugroup'];  // 例如: "1,2,3,4,5"
$msql->query("update `$tb_user` set status=0 where instr('$ugroup',userid)");
```

#### 修复后（安全）

```php
$ugroup = $_POST['ugroup'];

// 解析用户 ID 列表
$userIds = explode(',', $ugroup);
$userIds = array_filter($userIds, 'is_numeric');  // 只保留数字

if (empty($userIds)) {
    die('Invalid user IDs');
}

// 生成占位符
$placeholders = implode(',', array_fill(0, count($userIds), '?'));

// 使用 PDO
$stmt = $pdo->prepare("UPDATE `x_user` SET status = 0 WHERE userid IN ($placeholders)");
$stmt->execute($userIds);

// 或者使用 mysqli
$types = str_repeat('i', count($userIds));  // 'iii...' (i for integer)
$stmt = $mysqli->prepare("UPDATE `x_user` SET status = 0 WHERE userid IN ($placeholders)");
$stmt->bind_param($types, ...$userIds);
$stmt->execute();
```

---

### 1.3 INSTR() 函数替代方案

#### 修复前（不安全）

**文件**: `web/hide/message.php` (第 38 行)

```php
$id = $_POST['id'];
$sql = "delete from `$tb_message` where instr('$id',concat('|',id,'|'))";
```

#### 修复后（安全）

```php
$id = $_POST['id'];

// 假设 $id 格式为 "|1|2|3|"
$ids = array_filter(explode('|', trim($id, '|')), 'is_numeric');

if (empty($ids)) {
    die('Invalid IDs');
}

$placeholders = implode(',', array_fill(0, count($ids), '?'));
$stmt = $pdo->prepare("DELETE FROM `x_message` WHERE id IN ($placeholders)");
$stmt->execute($ids);
```

---

### 1.4 UPDATE 语句修复

#### 修复前（不安全）

**文件**: `web/hide/message.php` (第 46 行)

```php
$message = $_REQUEST['message'];
$id = $_REQUEST['id'];
$sql = "update `$tb_message` set response='$message' where id='$id'";
```

#### 修复后（安全）

```php
$message = $_REQUEST['message'];
$id = intval($_REQUEST['id']);

$stmt = $pdo->prepare("UPDATE `x_message` SET response = ? WHERE id = ?");
$stmt->execute([$message, $id]);
```

---

### 1.5 INSERT 语句修复

#### 修复前（不安全）

**文件**: `web/hide/message.php` (第 55 行)

```php
$news = $_REQUEST['news'];
$uid = $_REQUEST['uid'];
$sql = "INSERT into `$tb_message` set content='$news',userid='$uid',time=NOW()";
```

#### 修复后（安全）

```php
$news = $_REQUEST['news'];
$uid = intval($_REQUEST['uid']);

$stmt = $pdo->prepare("INSERT INTO `x_message` (content, userid, time) VALUES (?, ?, NOW())");
$stmt->execute([$news, $uid]);
```

---

## 2. XSS 修复

### 2.1 输出到 HTML 属性

#### 修复前（不安全）

**文件**: `web/uxj/reg.php` (第 189 行)

```html
<input size="40" id="reg_agent" maxlength="15" value="<?php echo $_GET['agent']; ?>" />
```

#### 修复后（安全）

```html
<input size="40" id="reg_agent" maxlength="15" value="<?php echo htmlspecialchars($_GET['agent'] ?? '', ENT_QUOTES, 'UTF-8'); ?>" />
```

---

### 2.2 输出到 JavaScript

#### 修复前（不安全）

**文件**: `web/api/embed/game.php` (第 134 行)

```javascript
fetch('<?php echo '/api/v1/users/' . $userId . '/balance'; ?>?api_key=<?php echo $_GET['api_key'] ?? ''; ?>')
```

#### 修复后（安全）

```javascript
fetch('<?php echo '/api/v1/users/' . $userId . '/balance'; ?>?api_key=<?php echo htmlspecialchars($_GET['api_key'] ?? '', ENT_QUOTES); ?>&sign=<?php echo htmlspecialchars($_GET['sign'] ?? '', ENT_QUOTES); ?>')

// 更好的方案：使用 JSON 编码
<script>
const apiKey = <?php echo json_encode($_GET['api_key'] ?? ''); ?>;
const sign = <?php echo json_encode($_GET['sign'] ?? ''); ?>;
fetch(`/api/v1/users/<?php echo $userId; ?>/balance?api_key=${encodeURIComponent(apiKey)}&sign=${encodeURIComponent(sign)}`);
</script>
```

---

### 2.3 输出到 HTML 内容

#### 修复前（不安全）

```php
echo "<div>欢迎 " . $_POST['username'] . "</div>";
```

#### 修复后（安全）

```php
echo "<div>欢迎 " . htmlspecialchars($_POST['username'], ENT_QUOTES, 'UTF-8') . "</div>";
```

---

### 2.4 创建安全输出函数

**文件**: `web/global/functions.php` (新建或追加)

```php
/**
 * 安全输出到 HTML
 */
function h($string) {
    return htmlspecialchars($string, ENT_QUOTES, 'UTF-8');
}

/**
 * 安全输出到 JavaScript
 */
function js($value) {
    return json_encode($value, JSON_HEX_TAG | JSON_HEX_AMP | JSON_HEX_APOS | JSON_HEX_QUOT);
}

/**
 * 安全输出到 URL
 */
function u($string) {
    return urlencode($string);
}
```

**使用示例**:
```php
<input value="<?php echo h($_GET['name']); ?>" />
<script>const name = <?php echo js($_GET['name']); ?>;</script>
<a href="/search?q=<?php echo u($_GET['query']); ?>">搜索</a>
```

---

## 3. 任意文件读取修复

### 3.1 文件路径白名单

#### 修复前（不安全）

**文件**: `web/agent/myscript.php` (第 8 行)

```php
echo file_get_contents("../js/" . $config['skins'] . '/js' . $config['adi'] . "/" . $_POST['sf'] . $config['adi'] . ".js");
```

#### 修复后（安全）

```php
$sf = $_POST['sf'] ?? '';

// 方案 1: 白名单验证
$allowedFiles = [
    'common',
    'admin',
    'user',
    'agent',
    'game',
    'bet'
];

if (!in_array($sf, $allowedFiles, true)) {
    http_response_code(400);
    die('Invalid file');
}

$filePath = "../js/" . $config['skins'] . '/js' . $config['adi'] . "/" . $sf . $config['adi'] . ".js";

// 验证文件存在且在允许的目录内
$realPath = realpath($filePath);
$allowedDir = realpath("../js/" . $config['skins']);

if (!$realPath || strpos($realPath, $allowedDir) !== 0 || !is_file($realPath)) {
    http_response_code(404);
    die('File not found');
}

echo file_get_contents($realPath);
```

**方案 2: 使用正则表达式验证**

```php
$sf = $_POST['sf'] ?? '';

// 只允许字母、数字、下划线
if (!preg_match('/^[a-zA-Z0-9_]+$/', $sf)) {
    http_response_code(400);
    die('Invalid file name');
}

$filePath = "../js/" . $config['skins'] . '/js' . $config['adi'] . "/" . $sf . $config['adi'] . ".js";

// 防止路径遍历
$realPath = realpath($filePath);
$baseDir = realpath("../js");

if (!$realPath || strpos($realPath, $baseDir) !== 0) {
    http_response_code(403);
    die('Access denied');
}

if (!is_file($realPath)) {
    http_response_code(404);
    die('File not found');
}

echo file_get_contents($realPath);
```

---

## 4. CSRF 保护

### 4.1 生成 CSRF Token

**文件**: `web/global/csrf.php` (新建)

```php
<?php
/**
 * CSRF 保护函数
 */

/**
 * 生成 CSRF Token
 */
function csrf_token() {
    if (session_status() === PHP_SESSION_NONE) {
        session_start();
    }

    if (empty($_SESSION['csrf_token'])) {
        $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
    }

    return $_SESSION['csrf_token'];
}

/**
 * 验证 CSRF Token
 */
function csrf_verify($token = null) {
    if (session_status() === PHP_SESSION_NONE) {
        session_start();
    }

    if ($token === null) {
        $token = $_POST['csrf_token'] ?? $_GET['csrf_token'] ?? '';
    }

    if (empty($_SESSION['csrf_token']) || !hash_equals($_SESSION['csrf_token'], $token)) {
        http_response_code(403);
        die('CSRF token validation failed');
    }

    return true;
}

/**
 * 输出 CSRF Token 隐藏字段
 */
function csrf_field() {
    return '<input type="hidden" name="csrf_token" value="' . htmlspecialchars(csrf_token()) . '">';
}

/**
 * 获取 CSRF Token 元标签
 */
function csrf_meta() {
    return '<meta name="csrf-token" content="' . htmlspecialchars(csrf_token()) . '">';
}
```

---

### 4.2 表单中使用 CSRF Token

#### HTML 表单

**文件**: `web/hide/user.php` (修改)

```php
<?php require_once '../global/csrf.php'; ?>

<form method="POST" action="user.php?type=add">
    <?php echo csrf_field(); ?>

    <input type="text" name="username" />
    <input type="password" name="password" />
    <button type="submit">提交</button>
</form>
```

#### 验证 CSRF Token

**文件**: `web/hide/user.php` (页面顶部)

```php
<?php
require_once '../global/csrf.php';

// 对所有 POST 请求验证 CSRF Token
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    csrf_verify();
}

// 继续处理业务逻辑...
```

---

### 4.3 AJAX 请求中使用 CSRF Token

#### HTML Head 部分

```html
<head>
    <?php echo csrf_meta(); ?>
</head>
```

#### JavaScript 代码

```javascript
// 方案 1: 从 meta 标签获取
const csrfToken = document.querySelector('meta[name="csrf-token"]').getAttribute('content');

fetch('/api/endpoint', {
    method: 'POST',
    headers: {
        'Content-Type': 'application/json',
        'X-CSRF-Token': csrfToken
    },
    body: JSON.stringify(data)
});

// 方案 2: 包含在请求体中
fetch('/api/endpoint', {
    method: 'POST',
    headers: {
        'Content-Type': 'application/json'
    },
    body: JSON.stringify({
        ...data,
        csrf_token: csrfToken
    })
});
```

#### PHP 验证（支持多种方式）

```php
require_once '../global/csrf.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // 支持从多个位置获取 token
    $token = $_POST['csrf_token']
          ?? $_SERVER['HTTP_X_CSRF_TOKEN']
          ?? $_SERVER['HTTP_X_XSRF_TOKEN']
          ?? null;

    csrf_verify($token);
}
```

---

## 5. 密码安全

### 5.1 使用 password_hash()

#### 修复前（不安全）

```php
$pass = md5($_POST['pass'] . $config['apass']);

// 存储
$sql = "INSERT INTO x_user (username, userpass) VALUES ('$user', '$pass')";

// 验证
$sql = "SELECT * FROM x_user WHERE username='$user' AND userpass='$pass'";
```

#### 修复后（安全）

```php
// 注册/创建用户
$password = $_POST['pass'];
$hashedPassword = password_hash($password, PASSWORD_BCRYPT);

$stmt = $pdo->prepare("INSERT INTO x_user (username, userpass) VALUES (?, ?)");
$stmt->execute([$username, $hashedPassword]);

// 登录验证
$username = $_POST['user'];
$password = $_POST['pass'];

$stmt = $pdo->prepare("SELECT * FROM x_user WHERE username = ?");
$stmt->execute([$username]);
$user = $stmt->fetch();

if ($user && password_verify($password, $user['userpass'])) {
    // 登录成功

    // 如果需要 rehash（例如算法升级）
    if (password_needs_rehash($user['userpass'], PASSWORD_BCRYPT)) {
        $newHash = password_hash($password, PASSWORD_BCRYPT);
        $stmt = $pdo->prepare("UPDATE x_user SET userpass = ? WHERE userid = ?");
        $stmt->execute([$newHash, $user['userid']]);
    }
} else {
    // 登录失败
    die('Invalid username or password');
}
```

---

### 5.2 会话 ID 安全生成

#### 修复前（不安全）

**文件**: `web/agent/login.php` (第 76 行)

```php
$passcode = (getmicrotime() * 100000000) . $time;
```

#### 修复后（安全）

```php
// 生成加密安全的随机会话 ID
$passcode = bin2hex(random_bytes(32));  // 64 个字符

// 或使用 UUID
function generate_uuid_v4() {
    $data = random_bytes(16);
    $data[6] = chr(ord($data[6]) & 0x0f | 0x40);
    $data[8] = chr(ord($data[8]) & 0x3f | 0x80);
    return vsprintf('%s%s-%s-%s-%s-%s%s%s', str_split(bin2hex($data), 4));
}

$passcode = generate_uuid_v4();
```

---

## 6. 数据库迁移

### 6.1 从 mysql_* 迁移到 PDO

#### 修复前（mysql_*）

**文件**: `web/global/mysql.inc.php`

```php
$conn = mysql_connect($dbHost, $dbUser, $dbPass);
mysql_select_db($dbName, $conn);

$sql = "SELECT * FROM x_user WHERE userid = " . $_GET['id'];
$result = mysql_query($sql);
$user = mysql_fetch_assoc($result);
```

#### 修复后（PDO）

```php
try {
    $dsn = "mysql:host={$dbHost};dbname={$dbName};charset=utf8mb4";
    $options = [
        PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
        PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
        PDO::ATTR_EMULATE_PREPARES => false,
    ];

    $pdo = new PDO($dsn, $dbUser, $dbPass, $options);

    $stmt = $pdo->prepare("SELECT * FROM x_user WHERE userid = ?");
    $stmt->execute([$_GET['id']]);
    $user = $stmt->fetch();

} catch (PDOException $e) {
    error_log("Database error: " . $e->getMessage());
    die("Database connection failed");
}
```

---

### 6.2 创建 PDO 包装类

**文件**: `web/global/database.class.php` (新建)

```php
<?php
class Database {
    private static $instance = null;
    private $pdo;

    private function __construct($config) {
        $dsn = "mysql:host={$config['host']};dbname={$config['name']};charset=utf8mb4";
        $options = [
            PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
            PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
            PDO::ATTR_EMULATE_PREPARES => false,
        ];

        $this->pdo = new PDO($dsn, $config['user'], $config['pass'], $options);
    }

    public static function getInstance($config) {
        if (self::$instance === null) {
            self::$instance = new self($config);
        }
        return self::$instance;
    }

    public function getPDO() {
        return $this->pdo;
    }

    public function query($sql, $params = []) {
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute($params);
        return $stmt;
    }

    public function fetchOne($sql, $params = []) {
        $stmt = $this->query($sql, $params);
        return $stmt->fetch();
    }

    public function fetchAll($sql, $params = []) {
        $stmt = $this->query($sql, $params);
        return $stmt->fetchAll();
    }

    public function execute($sql, $params = []) {
        $stmt = $this->query($sql, $params);
        return $stmt->rowCount();
    }

    public function lastInsertId() {
        return $this->pdo->lastInsertId();
    }
}
```

**使用示例**:

```php
require_once 'global/database.class.php';

$db = Database::getInstance([
    'host' => $dbHost,
    'name' => $dbName,
    'user' => $dbUser,
    'pass' => $dbPass
]);

// 查询单条
$user = $db->fetchOne("SELECT * FROM x_user WHERE userid = ?", [$userId]);

// 查询多条
$users = $db->fetchAll("SELECT * FROM x_user WHERE fid = ?", [$agentId]);

// 执行更新
$affected = $db->execute("UPDATE x_user SET status = ? WHERE userid = ?", [0, $userId]);

// 插入数据
$db->execute("INSERT INTO x_user (username, userpass) VALUES (?, ?)", [$username, $password]);
$newId = $db->lastInsertId();
```

---

## 7. 输入验证

### 7.1 创建输入验证函数

**文件**: `web/global/validation.php` (新建)

```php
<?php
/**
 * 输入验证函数
 */

/**
 * 验证整数
 */
function validate_int($value, $min = null, $max = null) {
    if (!is_numeric($value) || intval($value) != $value) {
        return false;
    }

    $value = intval($value);

    if ($min !== null && $value < $min) {
        return false;
    }

    if ($max !== null && $value > $max) {
        return false;
    }

    return $value;
}

/**
 * 验证字符串长度
 */
function validate_string($value, $minLen = 0, $maxLen = 255) {
    $len = mb_strlen($value, 'UTF-8');

    if ($len < $minLen || $len > $maxLen) {
        return false;
    }

    return $value;
}

/**
 * 验证用户名
 */
function validate_username($username) {
    // 只允许字母、数字、下划线，3-20 个字符
    if (!preg_match('/^[a-zA-Z0-9_]{3,20}$/', $username)) {
        return false;
    }

    return $username;
}

/**
 * 验证手机号
 */
function validate_phone($phone) {
    if (!preg_match('/^1[3-9]\d{9}$/', $phone)) {
        return false;
    }

    return $phone;
}

/**
 * 验证邮箱
 */
function validate_email($email) {
    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        return false;
    }

    return $email;
}

/**
 * 验证 URL
 */
function validate_url($url) {
    if (!filter_var($url, FILTER_VALIDATE_URL)) {
        return false;
    }

    return $url;
}

/**
 * 验证日期
 */
function validate_date($date, $format = 'Y-m-d') {
    $d = DateTime::createFromFormat($format, $date);
    return $d && $d->format($format) === $date;
}
```

**使用示例**:

```php
require_once 'global/validation.php';

// 验证用户 ID
$userId = validate_int($_GET['id'], 1);
if ($userId === false) {
    die('Invalid user ID');
}

// 验证用户名
$username = validate_username($_POST['username']);
if ($username === false) {
    die('Invalid username. Only letters, numbers, and underscores allowed (3-20 characters)');
}

// 验证手机号
$phone = validate_phone($_POST['phone']);
if ($phone === false) {
    die('Invalid phone number');
}
```

---

## 8. 错误处理

### 8.1 安全的错误处理

#### 不安全的错误显示

```php
// 直接暴露错误信息给用户
ini_set('display_errors', 1);
error_reporting(E_ALL);

mysqli_query($conn, $sql) or die(mysqli_error($conn));
```

#### 安全的错误处理

```php
// 生产环境配置
ini_set('display_errors', 0);
ini_set('log_errors', 1);
ini_set('error_log', '/var/log/php/error.log');
error_reporting(E_ALL);

// 使用异常处理
try {
    $stmt = $pdo->prepare($sql);
    $stmt->execute($params);
} catch (PDOException $e) {
    // 记录详细错误到日志
    error_log("Database error: " . $e->getMessage() . " in " . $e->getFile() . " on line " . $e->getLine());

    // 给用户友好的错误信息
    http_response_code(500);
    die('An error occurred. Please try again later.');
}
```

---

### 8.2 自定义错误处理器

**文件**: `web/global/error_handler.php` (新建)

```php
<?php
/**
 * 自定义错误和异常处理器
 */

// 设置错误处理器
set_error_handler(function($errno, $errstr, $errfile, $errline) {
    // 记录错误到日志
    error_log("Error [$errno]: $errstr in $errfile on line $errline");

    // 生产环境不显示错误详情
    if (getenv('ENVIRONMENT') === 'production') {
        return true;  // 不显示错误
    }

    return false;  // 显示错误（开发环境）
});

// 设置异常处理器
set_exception_handler(function($exception) {
    // 记录异常到日志
    error_log("Uncaught exception: " . $exception->getMessage() . " in " . $exception->getFile() . " on line " . $exception->getLine());
    error_log("Stack trace: " . $exception->getTraceAsString());

    // 返回友好的错误页面
    http_response_code(500);

    if (getenv('ENVIRONMENT') === 'production') {
        echo "An error occurred. Please try again later.";
    } else {
        echo "<pre>";
        echo "Exception: " . $exception->getMessage() . "\n";
        echo "File: " . $exception->getFile() . "\n";
        echo "Line: " . $exception->getLine() . "\n";
        echo "Trace:\n" . $exception->getTraceAsString();
        echo "</pre>";
    }

    exit(1);
});

// 设置致命错误处理器
register_shutdown_function(function() {
    $error = error_get_last();

    if ($error && in_array($error['type'], [E_ERROR, E_PARSE, E_CORE_ERROR, E_COMPILE_ERROR])) {
        error_log("Fatal error: {$error['message']} in {$error['file']} on line {$error['line']}");

        http_response_code(500);
        echo "A fatal error occurred. Please try again later.";
    }
});
```

---

## 9. 会话安全

### 9.1 安全的会话配置

**文件**: `web/global/session.php` (新建)

```php
<?php
/**
 * 安全的会话配置
 */

// 会话配置
ini_set('session.cookie_httponly', 1);  // 防止 JavaScript 访问 Cookie
ini_set('session.cookie_secure', 1);    // 仅通过 HTTPS 传输
ini_set('session.use_only_cookies', 1); // 仅使用 Cookie 存储会话 ID
ini_set('session.cookie_samesite', 'Strict');  // 防止 CSRF

// 设置会话名称（不使用默认的 PHPSESSID）
session_name('LOTTERY_SESSION');

// 会话过期时间（30 分钟）
ini_set('session.gc_maxlifetime', 1800);
ini_set('session.cookie_lifetime', 1800);

// 启动会话
session_start();

// 会话固定攻击防护
if (!isset($_SESSION['initiated'])) {
    session_regenerate_id(true);
    $_SESSION['initiated'] = true;
}

// 会话劫持防护：验证 User-Agent 和 IP
if (!isset($_SESSION['user_agent'])) {
    $_SESSION['user_agent'] = $_SERVER['HTTP_USER_AGENT'] ?? '';
    $_SESSION['ip_address'] = $_SERVER['REMOTE_ADDR'] ?? '';
} else {
    if ($_SESSION['user_agent'] !== ($_SERVER['HTTP_USER_AGENT'] ?? '') ||
        $_SESSION['ip_address'] !== ($_SERVER['REMOTE_ADDR'] ?? '')) {
        // 可能的会话劫持
        session_destroy();
        die('Session validation failed');
    }
}

// 会话超时检查
if (isset($_SESSION['last_activity']) && (time() - $_SESSION['last_activity'] > 1800)) {
    // 超过 30 分钟未活动
    session_destroy();
    header('Location: /login.php?timeout=1');
    exit;
}

$_SESSION['last_activity'] = time();
```

---

## 10. 完整示例：登录页面重构

**文件**: `web/agent/login_secure.php` (新建)

```php
<?php
require_once '../data/config.inc.php';
require_once '../global/database.class.php';
require_once '../global/csrf.php';
require_once '../global/validation.php';
require_once '../global/session.php';
require_once '../global/error_handler.php';

// 初始化数据库
$db = Database::getInstance([
    'host' => $dbHost,
    'name' => $dbName,
    'user' => $dbUser,
    'pass' => $dbPass
]);

// 处理登录
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // 验证 CSRF Token
    csrf_verify();

    // 获取并验证输入
    $username = validate_username($_POST['user'] ?? '');
    $password = $_POST['pass'] ?? '';

    if (!$username) {
        $error = '用户名格式错误';
    } elseif (empty($password)) {
        $error = '密码不能为空';
    } else {
        try {
            // 查询用户
            $user = $db->fetchOne(
                "SELECT * FROM x_user WHERE username = ? AND ifagent = 1",
                [$username]
            );

            if ($user && password_verify($password, $user['userpass'])) {
                // 登录成功

                // 重新生成会话 ID（防止会话固定攻击）
                session_regenerate_id(true);

                // 保存用户信息到会话
                $_SESSION['user_id'] = $user['userid'];
                $_SESSION['username'] = $user['username'];
                $_SESSION['is_agent'] = true;

                // 更新最后登录时间
                $db->execute(
                    "UPDATE x_user SET lastlogintime = NOW(), lastloginip = ? WHERE userid = ?",
                    [$_SERVER['REMOTE_ADDR'], $user['userid']]
                );

                // 记录登录日志
                $db->execute(
                    "INSERT INTO login_logs (userid, ip, user_agent, status, created_at) VALUES (?, ?, ?, 'success', NOW())",
                    [$user['userid'], $_SERVER['REMOTE_ADDR'], $_SERVER['HTTP_USER_AGENT'] ?? '']
                );

                // 跳转到仪表板
                header('Location: /agent/index.php');
                exit;

            } else {
                // 登录失败
                $error = '用户名或密码错误';

                // 记录失败尝试
                $db->execute(
                    "INSERT INTO login_logs (username, ip, user_agent, status, created_at) VALUES (?, ?, ?, 'failed', NOW())",
                    [$username, $_SERVER['REMOTE_ADDR'], $_SERVER['HTTP_USER_AGENT'] ?? '']
                );
            }

        } catch (Exception $e) {
            error_log("Login error: " . $e->getMessage());
            $error = '系统错误，请稍后重试';
        }
    }
}
?>
<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>代理登录</title>
    <?php echo csrf_meta(); ?>
</head>
<body>
    <h1>代理登录</h1>

    <?php if (isset($error)): ?>
        <div class="error"><?php echo htmlspecialchars($error); ?></div>
    <?php endif; ?>

    <form method="POST">
        <?php echo csrf_field(); ?>

        <label>
            用户名:
            <input type="text" name="user" value="<?php echo h($_POST['user'] ?? ''); ?>" required>
        </label>

        <label>
            密码:
            <input type="password" name="pass" required>
        </label>

        <button type="submit">登录</button>
    </form>
</body>
</html>
```

---

## 总结

以上修复示例涵盖了所有主要的安全漏洞类型：

1. ✅ SQL 注入 → 使用 PDO prepared statements
2. ✅ XSS → 使用 htmlspecialchars() 转义
3. ✅ 任意文件读取 → 使用白名单验证
4. ✅ CSRF → 实现 Token 验证
5. ✅ 密码安全 → 使用 password_hash()
6. ✅ 会话安全 → 安全的会话配置
7. ✅ 输入验证 → 创建验证函数
8. ✅ 错误处理 → 不暴露敏感信息

所有示例代码都可以直接使用，只需根据实际情况调整变量名和路径即可。
