<?php
// Force error display
ini_set('display_errors', 1);
error_reporting(E_ALL);

echo "<pre>\n";
echo "DEBUG PROBE START\n";

// 1. Check PHP Version
echo "PHP Version: " . phpversion() . "\n";

// 2. Check JSON constant
if (defined('JSON_UNESCAPED_UNICODE')) {
    echo "JSON_UNESCAPED_UNICODE is defined.\n";
} else {
    echo "JSON_UNESCAPED_UNICODE is NOT defined.\n";
}

// 3. Include files one by one
echo "Including ../data/comm.inc.php...\n";
include('../data/comm.inc.php');
echo "comm.inc.php included.\n";

echo "Including ../data/uservar.php...\n";
include('../data/uservar.php');
echo "uservar.php included.\n";

echo "Including ../func/func.php...\n";
include('../func/func.php');
echo "func.php included.\n";

echo "Including ../func/csfunc.php...\n";
include('../func/csfunc.php');
echo "csfunc.php included.\n";

echo "Including ../func/userfunc.php...\n";
include('../func/userfunc.php');
echo "userfunc.php included.\n";

echo "Including ../include.php...\n";
include('../include.php');
echo "include.php included.\n";

// 4. Check DB objects
global $psql, $msql;
if (isset($psql) && is_object($psql)) {
    echo "PSQL object exists.\n";
} else {
    echo "PSQL object MISSING.\n";
}

if (isset($msql) && is_object($msql)) {
    echo "MSQL object exists.\n";
} else {
    echo "MSQL object MISSING.\n";
}

// 5. Check DB connection
echo "Testing DB connection...\n";
// lib_mysqli::query returns result set or calls die() on error
// But our modified db.inc.php might die with JSON.
// Let's hope it works or dies with message.
if ($psql) {
    $psql->query("SELECT 1");
    echo "DB Connection OK (query executed).\n";
} else {
    echo "DB Connection FAILED (psql null).\n";
}

// 6. Check Session
if (session_id() == '') session_start();
echo "Session ID: " . session_id() . "\n";
if (isset($_SESSION['uuid'])) {
    echo "Session UUID: " . $_SESSION['uuid'] . "\n";
    $userid = $_SESSION['uuid'];
    
    // 7. Test transuser
    if (function_exists('transuser')) {
        echo "Testing transuser($userid, 'status')...\n";
        $status = transuser($userid, 'status');
        echo "Status: " . var_export($status, true) . "\n";
    } else {
        echo "transuser function missing.\n";
    }

} else {
    echo "No Session UUID. Please login first.\n";
}

echo "DEBUG PROBE END\n";
echo "</pre>";
?>
