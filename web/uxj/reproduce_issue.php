<?php
// Simulate environment for makelib.php
error_reporting(E_ALL);
ini_set('display_errors', 1);

// Mock Session
session_start();
$_SESSION['uuid'] = '29467712';
$_SESSION['ip'] = '127.0.0.1'; // Need to match getip()
$_SESSION['gid'] = '100';

// Mock POST data
$_POST['xtype'] = 'make';
$_POST['gid'] = '100';
$_POST['ab'] = 'A';
$_POST['abcd'] = 'A';
$_POST['bid'] = 'test_bid';
// JSON payload from user
$_POST['pstr'] = '[{"pid":"25586764","je":"1","name":"\u5927","peilv1":"1\u002e9819","classx":"\u51a0\u519b\u003a","con":"","bz":""}]';

// Mock REQUEST
$_REQUEST['xtype'] = 'make';

// Helper to mock getip if needed, but getip() is likely in func.php
// We will let it use the real getip() which usually looks at $_SERVER
$_SERVER['REMOTE_ADDR'] = '127.0.0.1';
$_SERVER['HTTP_X_FORWARDED_FOR'] = '127.0.0.1';

echo "Starting reproduction script...\n";

// Capture output
ob_start();

try {
    include 'makelib.php';
} catch (Exception $e) {
    echo "Caught Exception: " . $e->getMessage() . "\n";
} catch (Error $e) {
    echo "Caught Error: " . $e->getMessage() . "\n";
}

$output = ob_get_clean();
echo "Output from makelib.php:\n";
echo $output;
echo "\nReproduction script finished.\n";
