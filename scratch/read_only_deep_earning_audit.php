<?php
/**
 * READ-ONLY Deep Audit for Earning Table Rows & User 994323
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

echo "=== 1. ALL ROWS IN `earning` TABLE (Grouped by User ID) ===\n";
$res_user_group = $conn->query("SELECT userid, COUNT(*) as cnt, SUM(amount) as total_amt FROM earning GROUP BY userid");
if ($res_user_group && $res_user_group->num_rows > 0) {
    while ($ug = $res_user_group->fetch_assoc()) {
        printf("User ID: %-15s | Count: %-5d | Total Amount: ₹%.2f\n", $ug['userid'], $ug['cnt'], $ug['total_amt']);
    }
} else {
    echo "0 rows in earning table.\n";
}

echo "\n=== 2. DETAILED DUMP OF ALL ROWS IN `earning` TABLE ===\n";
$res_all = $conn->query("SELECT * FROM earning ORDER BY id ASC");
if ($res_all && $res_all->num_rows > 0) {
    while ($e = $res_all->fetch_assoc()) {
        printf("ID: %-4d | UserID: %-10s | RefID: %-10s | Type: %-30s | Amount: ₹%-10.2f | Date: %-10s | Status: %-8s\n",
            $e['id'], $e['userid'], $e['ref_id'], $e['type'], $e['amount'], $e['date'], $e['status']);
    }
} else {
    echo "0 rows in earning table.\n";
}

echo "\n=== 3. USER 994323 EARNING ROWS CHECK ===\n";
$res_u_e = $conn->query("SELECT * FROM earning WHERE userid = '994323'");
if ($res_u_e) {
    echo "Total earning rows for userid '994323': " . $res_u_e->num_rows . "\n";
}

echo "\n=== 4. USER 994323 MEMBER TABLE RECORD ===\n";
$res_u_m = $conn->query("SELECT id, name, username, sponsor, position, signup_package, join_package, topup, mypv, total_a, total_b, total_a_pv, total_b_pv, paid_a_pv, paid_b_pv, status FROM member WHERE id = '994323'");
if ($res_u_m && $row = $res_u_m->fetch_assoc()) {
    foreach ($row as $k => $v) {
        printf("%-20s => %s\n", $k, var_export($v, true));
    }
}
