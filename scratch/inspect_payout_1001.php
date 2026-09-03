<?php
/**
 * Trace exact database records for User 1001, 601134, 393413
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

$out = "";
$out .= "=======================================================\n";
$out .= "1. USER 1001 RECORD IN `member` TABLE\n";
$out .= "=======================================================\n";
$res_m = $conn->query("SELECT * FROM member WHERE id = '1001'");
if ($res_m && $row = $res_m->fetch_assoc()) {
    foreach ($row as $k => $v) {
        $out .= sprintf("%-25s => %s\n", $k, var_export($v, true));
    }
}

$out .= "\n=======================================================\n";
$out .= "2. DIRECT DOWNLINE MEMBERS OF 1001\n";
$out .= "=======================================================\n";
$res_down = $conn->query("SELECT * FROM member WHERE sponsor = '1001' OR position = '1001'");
if ($res_down) {
    while ($d = $res_down->fetch_assoc()) {
        $out .= sprintf("ID: %-8s | Name: %-15s | Sponsor: %-8s | Pos: %-8s | Leg: %-2s | signup_pkg: %-2s | topup: ₹%-6s | mypv: %-5s\n",
            $d['id'], $d['name'], $d['sponsor'], $d['position'], $d['placement_leg'], $d['signup_package'], $d['topup'], $d['mypv']);
    }
}

$out .= "\n=======================================================\n";
$out .= "3. ALL PRODUCT RECORDS IN `product` TABLE\n";
$out .= "=======================================================\n";
$res_p = $conn->query("SELECT * FROM product");
if ($res_p) {
    while ($p = $res_p->fetch_assoc()) {
        $out .= sprintf("ID: %-3s | Name: %-25s | Price: ₹%-8.2f | Dealer: ₹%-8.2f | DirectInc: %-6s | MatchInc: %-6s | LevelInc: %s\n",
            $p['id'], $p['prod_name'], $p['prod_price'], isset($p['dealer_price']) ? $p['dealer_price'] : 0,
            isset($p['direct_income']) ? $p['direct_income'] : '0',
            isset($p['matching_income']) ? $p['matching_income'] : '0',
            isset($p['level_income']) ? $p['level_income'] : 'N/A');
    }
}

$out .= "\n=======================================================\n";
$out .= "4. ALL ROWS IN `earning` TABLE FOR USER 1001\n";
$out .= "=======================================================\n";
$res_e = $conn->query("SELECT * FROM earning WHERE userid = '1001' ORDER BY id ASC");
$sum_e = 0;
if ($res_e && $res_e->num_rows > 0) {
    while ($e = $res_e->fetch_assoc()) {
        $sum_e += floatval($e['amount']);
        $out .= sprintf("Earning ID: %-5d | Type: %-30s | RefID: %-8s | Amount: ₹%-8.2f | Status: %-8s | Date: %-10s | Lvl: %-2s | PairMatch: %s\n",
            $e['id'], $e['type'], $e['ref_id'], $e['amount'], $e['status'], $e['date'], $e['levlno'], isset($e['pair_match']) ? $e['pair_match'] : 'N/A');
    }
    $out .= sprintf("TOTAL SUM of earning.amount for 1001 = ₹%.2f\n", $sum_e);
} else {
    $out .= "No earning records found for User 1001.\n";
}

$out .= "\n=======================================================\n";
$out .= "5. ALL ROWS IN `withdraw_request` TABLE FOR USER 1001\n";
$out .= "=======================================================\n";
$res_w = $conn->query("SELECT * FROM withdraw_request WHERE userid = '1001' ORDER BY id DESC");
if ($res_w && $res_w->num_rows > 0) {
    while ($w = $res_w->fetch_assoc()) {
        $out .= sprintf("WR ID: %-5d | UserID: %-8s | Amount: ₹%-8.2f | Status: %-10s | Date: %-10s | Tax: %s\n",
            $w['id'], $w['userid'], $w['amount'], $w['status'], $w['date'], isset($w['tax']) ? $w['tax'] : '0');
    }
} else {
    $out .= "No withdraw_request found for User 1001.\n";
}

$out .= "\n=======================================================\n";
$out .= "6. ALL ROWS IN `earning` TABLE GLOBALLY\n";
$out .= "=======================================================\n";
$res_ge = $conn->query("SELECT * FROM earning ORDER BY id ASC");
if ($res_ge && $res_ge->num_rows > 0) {
    while ($ge = $res_ge->fetch_assoc()) {
        $out .= sprintf("ID: %-5d | UserID: %-8s | RefID: %-8s | Type: %-30s | Amount: ₹%-8.2f | Status: %-8s | Date: %-10s\n",
            $ge['id'], $ge['userid'], $ge['ref_id'], $ge['type'], $ge['amount'], $ge['status'], $ge['date']);
    }
}

file_put_contents(__DIR__ . '/payout_1001_trace.txt', $out);
echo "Written payout trace to scratch/payout_1001_trace.txt\n";
