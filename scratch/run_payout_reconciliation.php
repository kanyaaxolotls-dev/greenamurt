<?php
/**
 * Payout Ledger Reconciliation & Audit Script for User 1001 & All Pending Withdraw Requests
 */
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

$out = "=======================================================\n";
$out .= "DATABASE AUDIT FOR USER 1001 & UN-PAID WITHDRAWALS\n";
$out .= "=======================================================\n\n";

// 1. Fetch withdraw_request for 1001
$res_w = $conn->query("SELECT * FROM withdraw_request WHERE userid = '1001' OR status = 'Un-Paid'");
$out .= "WITHDRAW_REQUEST ROWS:\n";
if ($res_w && $res_w->num_rows > 0) {
    while ($w = $res_w->fetch_assoc()) {
        $out .= sprintf("ID: %-5d | UserID: %-8s | Amount: ₹%-10.2f | Status: %-10s | Date: %-10s\n",
            $w['id'], $w['userid'], $w['amount'], $w['status'], $w['date']);
    }
} else {
    $out .= "No withdraw_request rows found.\n";
}

// 2. Fetch earning rows for 1001
$out .= "\nEARNING LEDGER ROWS FOR USER 1001:\n";
$res_e = $conn->query("SELECT * FROM earning WHERE userid = '1001' ORDER BY id ASC");
$total_earning_1001 = 0;
if ($res_e && $res_e->num_rows > 0) {
    while ($e = $res_e->fetch_assoc()) {
        $total_earning_1001 += floatval($e['amount']);
        $out .= sprintf("Earning ID: %-5d | Type: %-30s | RefID: %-8s | Amount: ₹%-8.2f | Status: %-8s | Date: %-10s | Lvl: %-2s\n",
            $e['id'], $e['type'], $e['ref_id'], $e['amount'], $e['status'], $e['date'], $e['levlno']);
    }
    $out .= sprintf("TOTAL SUM of earning.amount for User 1001: ₹%.2f\n", $total_earning_1001);
} else {
    $out .= "No earning rows found for User 1001.\n";
}

// 3. Fetch downline joins of 1001
$out .= "\nDIRECT DOWNLINE JOINS OF USER 1001:\n";
$res_d = $conn->query("SELECT id, name, sponsor, signup_package, topup FROM member WHERE sponsor = '1001'");
if ($res_d) {
    while ($d = $res_d->fetch_assoc()) {
        $pkg_id = !empty($d['signup_package']) ? $d['signup_package'] : 'N/A';
        $res_p = $conn->query("SELECT prod_name, prod_price, direct_income, level_income FROM product WHERE id = '$pkg_id'");
        $p_info = "Pkg Info: N/A";
        if ($res_p && $p = $res_p->fetch_assoc()) {
            $p_info = sprintf("Pkg: %s (MRP ₹%.2f, DirectInc: %s%%, LevelInc: %s)", $p['prod_name'], $p['prod_price'], $p['direct_income'], $p['level_income']);
        }
        $out .= sprintf("Join ID: %-8s | Name: %-15s | PkgID: %-4s | Topup: ₹%-8s | %s\n",
            $d['id'], $d['name'], $pkg_id, $d['topup'], $p_info);
    }
}

file_put_contents(__DIR__ . '/payout_reconciliation_result.txt', $out);
echo "Reconciliation output written to scratch/payout_reconciliation_result.txt\n";
