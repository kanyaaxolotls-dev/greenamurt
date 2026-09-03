<?php
/**
 * READ-ONLY Detailed Audit Script for Earning Table 14 Rows & View Earning Query
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

echo "=======================================================\n";
echo "1. ALL ROWS IN `earning` TABLE (Detailed Dump)\n";
echo "=======================================================\n";
$res_e = $conn->query("SELECT * FROM earning ORDER BY id ASC");
if ($res_e && $res_e->num_rows > 0) {
    echo "Found " . $res_e->num_rows . " rows:\n\n";
    while ($row = $res_e->fetch_assoc()) {
        printf("ID: %-4d | UserID: %-10s | RefID: %-10s | Type: %-30s | Amount: ₹%-10.2f | Date: %-10s | Status: %-10s | PairMatch: %s\n",
            $row['id'], $row['userid'], $row['ref_id'], $row['type'], $row['amount'], $row['date'], $row['status'], isset($row['pair_match']) ? $row['pair_match'] : 'N/A');
    }
} else {
    echo "No rows found in `earning` table.\n";
}

echo "\n=======================================================\n";
echo "2. TEST USER 994323 DETAILED RECORD IN `member` TABLE\n";
echo "=======================================================\n";
$res_u = $conn->query("SELECT * FROM member WHERE id = '994323'");
if ($res_u && $res_u->num_rows > 0) {
    $u = $res_u->fetch_assoc();
    foreach ($u as $k => $v) {
        printf("%-25s => %s\n", $k, var_export($v, true));
    }
}

echo "\n=======================================================\n";
echo "3. ALL DOWNLINE NODES OF 994323\n";
echo "=======================================================\n";
$res_d = $conn->query("SELECT * FROM member WHERE position = '994323' OR sponsor = '994323'");
if ($res_d) {
    while ($d = $res_d->fetch_assoc()) {
        printf("ID: %-8s | Name: %-15s | Leg: %-2s | Sponsor: %-8s | Position: %-8s | topup: ₹%-6s | mypv: %-2s | signup_pkg: %-2s | join_pkg: %s\n",
            $d['id'], $d['name'], $d['placement_leg'], $d['sponsor'], $d['position'], $d['topup'], $d['mypv'], $d['signup_package'], var_export($d['join_package'], true));
    }
}

echo "\n=======================================================\n";
echo "4. PRODUCT TABLE COMPLETE CONFIGURATION\n";
echo "=======================================================\n";
$res_p = $conn->query("SELECT * FROM product");
if ($res_p) {
    while ($p = $res_p->fetch_assoc()) {
        echo "Product ID: " . $p['id'] . "\n";
        foreach ($p as $k => $v) {
            printf("  %-20s => %s\n", $k, var_export($v, true));
        }
        echo "\n";
    }
}
