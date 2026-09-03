<?php
/**
 * READ-ONLY Check for join_package vs signup_package and laps_earning
 */
header('Content-Type: text/plain; charset=utf-8');
ini_set('display_errors', 1);
error_reporting(E_ALL);

$db_config_file = __DIR__ . '/../system/application/config/database.php';
if (!file_exists($db_config_file)) {
    $db_config_file = __DIR__ . '/system/application/config/database.php';
}
define('BASEPATH', __DIR__ . '/../system/');
define('ENVIRONMENT', 'development');
include($db_config_file);

$db_config = $db['default'];
$host = !empty($db_config['hostname']) ? $db_config['hostname'] : 'localhost';
$user = $db_config['username'];
$pass = $db_config['password'];
$dbname = $db_config['database'];

$conn = new mysqli($host, $user, $pass, $dbname);
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

echo "=== USER 994323 PACKAGE FIELDS ===\n";
$res = $conn->query("SELECT id, name, signup_package, join_package, topup, mypv FROM member WHERE id = '994323'");
if ($res && $row = $res->fetch_assoc()) {
    echo "signup_package: " . var_export($row['signup_package'], true) . "\n";
    echo "join_package:   " . var_export($row['join_package'], true) . "\n";
    echo "topup:          " . var_export($row['topup'], true) . "\n";
    echo "mypv:           " . var_export($row['mypv'], true) . "\n";
}

echo "\n=== ALL LAPS_EARNING RECORDS ===\n";
$res_laps = $conn->query("SELECT * FROM laps_earning ORDER BY id DESC LIMIT 20");
if ($res_laps && $res_laps->num_rows > 0) {
    while ($l = $res_laps->fetch_assoc()) {
        echo "ID: " . $l['id'] . " | User: " . $l['userid'] . " | Type: " . $l['type'] . " | Amount: ₹" . $l['amount'] . " | Reason: " . $l['reason'] . "\n";
    }
} else {
    echo "laps_earning table is completely empty (0 rows).\n";
}

echo "\n=== ALL EARNING RECORDS ===\n";
$res_e = $conn->query("SELECT * FROM earning ORDER BY id DESC LIMIT 20");
if ($res_e && $res_e->num_rows > 0) {
    while ($e = $res_e->fetch_assoc()) {
        echo "ID: " . $e['id'] . " | User: " . $e['userid'] . " | Type: " . $e['type'] . " | Amount: ₹" . $e['amount'] . " | Date: " . $e['date'] . "\n";
    }
} else {
    echo "earning table is completely empty (0 rows).\n";
}
