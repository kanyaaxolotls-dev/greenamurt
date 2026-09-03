<?php
/**
 * READ-ONLY Deep Audit Script for Income Flow & User 994323
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
echo "1. CURRENT DATABASE VALUES FOR TEST USER 994323\n";
echo "=======================================================\n";
$res_u = $conn->query("SELECT * FROM member WHERE id = '994323'");
if ($res_u && $res_u->num_rows > 0) {
    $u = $res_u->fetch_assoc();
    foreach ($u as $k => $v) {
        printf("%-25s => %s\n", $k, var_export($v, true));
    }
} else {
    echo "User 994323 not found.\n";
}

echo "\n=======================================================\n";
echo "2. DOWNLINE MEMBERS OF USER 994323\n";
echo "=======================================================\n";
$res_down = $conn->query("SELECT id, name, position, placement_leg, sponsor, signup_package, join_package, topup, mypv, total_a_pv, total_b_pv, status FROM member WHERE position = '994323' OR sponsor = '994323'");
if ($res_down) {
    while ($d = $res_down->fetch_assoc()) {
        printf("ID: %-8s | Name: %-15s | Leg: %-2s | topup: ₹%-6s | mypv: %-2s | signup_pkg: %-2s | join_pkg: %s\n", 
            $d['id'], $d['name'], $d['placement_leg'], $d['topup'], $d['mypv'], $d['signup_package'], var_export($d['join_package'], true));
    }
}

echo "\n=======================================================\n";
echo "3. PRODUCT COMPENSATION CONFIGURATION\n";
echo "=======================================================\n";
$res_p = $conn->query("SELECT * FROM product");
if ($res_p) {
    while ($p = $res_p->fetch_assoc()) {
        echo "Product ID: " . $p['id'] . " | Name: " . $p['prod_name'] . "\n";
        echo "  prod_price: ₹" . $p['prod_price'] . " | dealer_price: ₹" . (isset($p['dealer_price']) ? $p['dealer_price'] : 'N/A') . " | pv: " . $p['pv'] . "\n";
        echo "  direct_income: ₹" . (isset($p['direct_income']) ? $p['direct_income'] : 'N/A') . " | matching_income: ₹" . (isset($p['matching_income']) ? $p['matching_income'] : 'N/A') . " | capping: ₹" . (isset($p['capping']) ? $p['capping'] : 'N/A') . "\n";
        echo "  sponser_level_inc: " . (isset($p['sponser_level_inc']) ? $p['sponser_level_inc'] : 'N/A') . " | level_income: " . (isset($p['level_income']) ? $p['level_income'] : 'N/A') . "\n\n";
    }
}

echo "=======================================================\n";
echo "4. EARNING TABLE BREAKDOWN BY TYPE & STATUS\n";
echo "=======================================================\n";
$res_eb = $conn->query("SELECT type, status, COUNT(*) as cnt, SUM(amount) as total_amt FROM earning GROUP BY type, status");
if ($res_eb && $res_eb->num_rows > 0) {
    while ($eb = $res_eb->fetch_assoc()) {
        printf("Type: %-30s | Status: %-10s | Count: %-5d | Total Amt: ₹%.2f\n", $eb['type'], $eb['status'], $eb['cnt'], $eb['total_amt']);
    }
} else {
    echo "No records found in `earning` table.\n";
}

echo "\n=======================================================\n";
echo "5. LAPS EARNING BREAKDOWN BY TYPE & REASON\n";
echo "=======================================================\n";
$res_lb = $conn->query("SELECT type, reason, COUNT(*) as cnt, SUM(amount) as total_amt FROM laps_earning GROUP BY type, reason");
if ($res_lb && $res_lb->num_rows > 0) {
    while ($lb = $res_lb->fetch_assoc()) {
        printf("Type: %-30s | Reason: %-25s | Count: %-5d | Total Amt: ₹%.2f\n", $lb['type'], $lb['reason'], $lb['cnt'], $lb['total_amt']);
    }
} else {
    echo "No records found in `laps_earning` table.\n";
}

echo "\n=======================================================\n";
echo "6. GLOBAL CONFIG & SETTINGS evidence\n";
echo "=======================================================\n";
$res_c = $conn->query("SELECT * FROM config");
if ($res_c) {
    while ($c = $res_c->fetch_assoc()) {
        printf("%-35s => %s\n", $c['type'], $c['value']);
    }
}
