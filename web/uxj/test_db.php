<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);

echo "DEBUG: Starting DB test (v3)...<br>";

// Check for mysqli extension
if (!class_exists('mysqli')) {
    echo "ERROR: mysqli class not found! Is the extension installed?<br>";
    exit;
} else {
    echo "DEBUG: mysqli class exists.<br>";
}

include("../data/config.inc.php");
echo "DEBUG: Config loaded. Host=$dbHost, User=$dbUser, Port=$dbPort<br>";

// Manual connection attempt
echo "DEBUG: Attempting raw mysqli connection...<br>";
try {
    $mysqli = new mysqli($dbHost, $dbUser, $dbPass, $dbName, $dbPort);
    if ($mysqli->connect_errno) {
        echo "ERROR: Raw connection failed: " . $mysqli->connect_error . "<br>";
    } else {
        echo "DEBUG: Raw connection successful.<br>";
        $mysqli->close();
    }
} catch (Throwable $e) {
    echo "ERROR: Raw connection threw exception: " . $e->getMessage() . "<br>";
}

// Now include the class definition
include("../data/db.php");
include("../global/db.inc.php");

echo "DEBUG: Instantiating lib_mysqli...<br>";
try {
    $psql = new lib_mysqli($dbHost, $dbUser, $dbPass, $dbName, $dbPort);
    echo "DEBUG: lib_mysqli instantiated.<br>";
} catch (Throwable $e) {
    echo "ERROR: lib_mysqli instantiation failed: " . $e->getMessage() . "<br>";
}

if (isset($psql) && is_object($psql)) {
    echo "DEBUG: \$psql is ready.<br>";
} else {
    echo "DEBUG: \$psql is NOT ready.<br>";
}
