<?php
/**
 * READ-ONLY Complete Income System Audit
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
echo "1. SYSTEM CONFIGURATION & GLOBAL SETTINGS\n";
echo "=======================================================\n";
$res_cfg = $conn->query("SELECT * FROM config");
if ($res_cfg) {
    while ($row = $res_cfg->fetch_assoc()) {
        printf("%-35s => %s\n", $row['type'], $row['value']);
    }
}

echo "\n=======================================================\n";
echo "2. PRODUCT / PACKAGE COMPENSATION CONFIGURATION\n";
echo "=======================================================\n";
$res_prod = $conn->query("SELECT * FROM product");
if ($res_prod) {
    while ($row = $res_prod->fetch_assoc()) {
        echo "Product ID: " . $row['id'] . " | Name: " . $row['prod_name'] . "\n";
        echo "  Price: ₹" . $row['prod_price'] . " | Dealer Price: ₹" . (isset($row['dealer_price']) ? $row['dealer_price'] : 'N/A') . " | PV: " . $row['pv'] . "\n";
        echo "  Direct Income: ₹" . (isset($row['direct_income']) ? $row['direct_income'] : 'N/A') . " | Matching Income: ₹" . (isset($row['matching_income']) ? $row['matching_income'] : 'N/A') . " | Capping: ₹" . (isset($row['capping']) ? $row['capping'] : 'N/A') . "\n";
        echo "  Sponsor Level Inc: " . (isset($row['sponser_level_inc']) ? $row['sponser_level_inc'] : 'N/A') . " | Level Income: " . (isset($row['level_income']) ? $row['level_income'] : 'N/A') . "\n\n";
    }
}

echo "=======================================================\n";
echo "3. EARNING TABLE STATS & LAPS_EARNING TABLE\n";
echo "=======================================================\n";
$cnt_e = $conn->query("SELECT COUNT(*) as total FROM earning")->fetch_assoc()['total'];
echo "Total rows in `earning` table: " . $cnt_e . "\n";

$cnt_laps = $conn->query("SELECT COUNT(*) as total FROM laps_earning")->fetch_assoc()['total'];
echo "Total rows in `laps_earning` table: " . $cnt_laps . "\n";
if ($cnt_laps > 0) {
    $res_laps = $conn->query("SELECT * FROM laps_earning ORDER BY id DESC LIMIT 10");
    while ($l = $res_laps->fetch_assoc()) {
        echo "  Laps ID: " . $l['id'] . " | User ID: " . $l['userid'] . " | Amount: ₹" . $l['amount'] . " | Type: " . $l['type'] . " | Reason: " . $l['reason'] . "\n";
    }
}

echo "\n=======================================================\n";
echo "4. CRON CONFIGURATION & RECENT CRON RUN EVIDENCE\n";
echo "=======================================================\n";
$res_cron = $conn->query("SELECT * FROM earning_roi_cron");
if ($res_cron) {
    while ($r = $res_cron->fetch_assoc()) {
        echo "Cron ID: " . $r['id'] . " | Name: " . (isset($r['name']) ? $r['name'] : 'N/A') . " | Active: " . $r['is_active'] . "\n";
        echo "  Last Run: " . (isset($r['last_run_at']) ? $r['last_run_at'] : 'N/A') . " | Next Run: " . (isset($r['next_run_at']) ? $r['next_run_at'] : 'N/A') . "\n";
    }
} else {
    echo "Table `earning_roi_cron` does not exist or has no rows.\n";
}

echo "\n=======================================================\n";
echo "5. TARGET USER STATE & DOWNLINE TREE (User 994323)\n";
echo "=======================================================\n";
$res_u = $conn->query("SELECT * FROM member WHERE id = '994323'");
if ($res_u && $res_u->num_rows > 0) {
    $u = $res_u->fetch_assoc();
    echo "User 994323:\n";
    echo "  Name: " . $u['name'] . " | Status: " . $u['status'] . "\n";
    echo "  signup_package: " . $u['signup_package'] . " | join_package: " . (isset($u['join_package']) ? $u['join_package'] : 'N/A') . " | topup: ₹" . $u['topup'] . " | mypv: " . $u['mypv'] . "\n";
    echo "  Left Child (A): " . $u['A'] . " | Right Child (B): " . $u['B'] . "\n";
    echo "  total_a (Left Team): " . $u['total_a'] . " | total_b (Right Team): " . $u['total_b'] . "\n";
    echo "  total_a_pv (Left BV): " . $u['total_a_pv'] . " | total_b_pv (Right BV): " . $u['total_b_pv'] . "\n";
    echo "  paid_a_pv: " . (isset($u['paid_a_pv']) ? $u['paid_a_pv'] : 0) . " | paid_b_pv: " . (isset($u['paid_b_pv']) ? $u['paid_b_pv'] : 0) . "\n";
    echo "  Sponsor ID: " . $u['sponsor'] . " | Position ID: " . $u['position'] . "\n";
} else {
    echo "User 994323 not found.\n";
}

$res_down = $conn->query("SELECT id, name, position, placement_leg, signup_package, join_package, topup, mypv, total_a_pv, total_b_pv FROM member WHERE position = '994323' OR sponsor = '994323'");
if ($res_down) {
    echo "\nDownline Nodes for User 994323:\n";
    while ($d = $res_down->fetch_assoc()) {
        echo "  Downline ID: " . $d['id'] . " | Name: " . $d['name'] . " | Leg: " . $d['placement_leg'] . " | topup: ₹" . $d['topup'] . " | mypv: " . $d['mypv'] . " | signup_pkg: " . $d['signup_package'] . " | join_pkg: " . (isset($d['join_package']) ? $d['join_package'] : 'N/A') . "\n";
    }
}
