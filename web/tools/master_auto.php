<?php
// 统一入口文件 - 自动结算与开奖 (Unified Entry Point - Auto Settlement & Drawing)
// 包含: autokjs.php (开奖), autos.php (结算), autoflys.php, autobus.php
// 用法:
// 1. 浏览器访问此文件: http://yoursite/tools/master_auto.php
// 2. 命令行运行: php tools/master_auto.php (确保 php 在环境变量中)

$scripts = [
    'autoflys.php',
    'autos.php',
    'autokjs.php',
    'autobus.php'
];

$secret = 'toor'; // 脚本中硬编码的密钥

// 设置时区，避免警告
if (function_exists('date_default_timezone_set')) {
    date_default_timezone_set("Asia/Shanghai");
}

if (php_sapi_name() == 'cli') {
    // CLI 模式 (命令行)
    echo "Starting Automation in CLI mode...\n";
    echo "Press Ctrl+C to stop.\n\n";
    
    // 获取 runner.php 的绝对路径
    $runnerPath = __DIR__ . DIRECTORY_SEPARATOR . 'runner.php';
    
    while (true) {
        foreach ($scripts as $script) {
            echo "[" . date('H:i:s') . "] Executing $script...\n";
            
            // 使用 runner.php 来执行目标脚本
            // 这样避免了在命令行中直接传递 PHP 代码导致的引用/转义问题 (尤其是 Windows PowerShell 下)
            $cmd = "php \"" . $runnerPath . "\" \"$script\" \"$secret\"";
            
            // 执行命令
            system($cmd); 
            echo "\n";
        }
        
        echo "[" . date('H:i:s') . "] Sleeping for 5 seconds...\n";
        echo "---------------------------------------------------\n";
        sleep(5);
    }
} else {
    // 浏览器模式
    ?>
    <!DOCTYPE html>
    <html>
    <head>
        <meta charset="utf-8">
        <meta http-equiv="refresh" content="600"> <!-- 10分钟整体刷新一次防止内存泄漏 -->
        <title>自动结算与开奖控制台 (Auto Control Panel)</title>
        <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
        <style>
            body { font-family: Arial, sans-serif; padding: 20px; background: #f0f0f0; }
            h1 { color: #333; }
            .container { display: flex; flex-wrap: wrap; gap: 20px; }
            .card { background: #fff; padding: 15px; border-radius: 8px; box-shadow: 0 2px 5px rgba(0,0,0,0.1); width: 300px; }
            iframe { width: 100%; height: 150px; border: 1px solid #eee; margin-top: 10px; background: #fafafa; }
            .status { font-size: 12px; color: #666; margin-top: 5px; }
            .running { color: green; font-weight: bold; }
        </style>
    </head>
    <body>
        <h1>🚀 自动结算与开奖系统</h1>
        <p>请保持此页面开启，系统将自动执行任务。</p>
        
        <div class="container" id="container"></div>

        <script>
            var scripts = <?php echo json_encode($scripts); ?>;
            var secret = '<?php echo $secret; ?>';
            
            function init() {
                var container = $("#container");
                scripts.forEach(function(script) {
                    var frameId = "frame_" + script.replace('.', '_');
                    var html = `
                        <div class="card">
                            <h3>${script}</h3>
                            <div class="status">状态: <span class="running">运行中...</span> <span id="time_${frameId}"></span></div>
                            <iframe id="${frameId}" src="about:blank"></iframe>
                        </div>
                    `;
                    container.append(html);
                });
                runLoop();
            }

            function runLoop() {
                scripts.forEach(function(script) {
                    var frameId = "frame_" + script.replace('.', '_');
                    var url = script + "?admin=" + secret + "&t=" + Math.random();
                    $("#" + frameId).attr("src", url);
                    
                    var now = new Date();
                    $("#time_" + frameId).text(now.toLocaleTimeString());
                });
                
                // 每 10 秒重新加载一次
                setTimeout(runLoop, 10000);
            }

            $(document).ready(init);
        </script>
    </body>
    </html>
    <?php
}
?>
