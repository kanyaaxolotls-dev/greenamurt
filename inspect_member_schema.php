<?php
/**
 * READ-ONLY Schema Inspection for `member` Table
 */
header('Content-Type: text/plain; charset=utf-8');
ini_set('display_errors', 1);
error_reporting(E_ALL);

$db_config_file = __DIR__ . '/system/application/config/database.php';
if (!file_exists($db_config_file)) {
    die("Database config file not found.");
}
define('BASEPATH', __DIR__ . '/system/');
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

echo "=== MEMBER TABLE COLUMNS & CONSTRAINTS ===\n";
$res = $conn->query("SHOW FULL COLUMNS FROM member");
if ($res) {
    while ($row = $res->fetch_assoc()) {
        printf("%-20s %-20s Null:%-4s Default:%s\n", $row['Field'], $row['Type'], $row['Null'], var_export($row['Default'], true));
    }
}

echo "\n=== TEST INSERT ERROR CHECK ===\n";
$conn->query("DELETE FROM member WHERE id IN ('888001', '888002', '888003')");
$sql1 = "INSERT INTO member (id, name, email, phone, sponsor, position, A, B, topup, signup_package, mypv, total_a, total_b, total_a_pv, total_b_pv, status) VALUES ('888001', 'Test Parent', 'tp@test.com', '8888888801', '0', '0', '888002', '888003', 8900, 1, 1, 1, 1, 0, 0, 'Active')";
if (!$conn->query($sql1)) {
    echo "SQL1 Error: " . $conn->error . "\n";
} else {
    echo "SQL1 Insert Success!\n";
}
