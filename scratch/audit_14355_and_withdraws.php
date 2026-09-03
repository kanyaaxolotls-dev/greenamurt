<?php
/**
 * DB Audit Script for Un-Paid Withdraw Requests & Earning Reconciliation
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
$out .= "1. UN-PAID & PENDING WITHDRAW REQUESTS AUDIT\n";
$out .= "=======================================================\n";

$res_wr = $conn->query("SELECT * FROM withdraw_request ORDER BY id DESC");
if ($res_wr && $res_wr->num_rows > 0) {
    $out .= "Found " . $res_wr->num_rows . " rows in withdraw_request:\n\n";
    while ($wr = $res_wr->fetch_assoc()) {
        $out .= sprintf("WR ID: %-5d | UserID: %-10s | Amount: ₹%-10.2f | Status: %-10s | Date: %-10s | Tax: %s\n",
            $wr['id'], $wr['userid'], $wr['amount'], $wr['status'], $wr['date'], isset($wr['tax']) ? $wr['tax'] : '0');
    }
} else {
    $out .= "No rows found in withdraw_request.\n";
}

$out .= "\n=======================================================\n";
$out .= "2. TRACING ₹14,355 (OR ANY UN-PAID/RECENT WITHDRAWAL)\n";
$out .= "=======================================================\n";

$res_14k = $conn->query("SELECT * FROM withdraw_request WHERE amount = 14355 OR amount LIKE '14355%' OR status = 'Un-Paid'");
if ($res_14k && $res_14k->num_rows > 0) {
    while ($w = $res_14k->fetch_assoc()) {
        $out .= "\n-------------------------------------------------------\n";
        $out .= "Withdraw Request ID: " . $w['id'] . "\n";
        $out .= "User ID: " . $w['userid'] . "\n";
        $out .= "Gross Amount: ₹" . $w['amount'] . "\n";
        $out .= "Status: " . $w['status'] . "\n";
        $out .= "Date: " . $w['date'] . "\n";
        
        $uid = $w['userid'];
        $wdate = $w['date'];
        
        // Find earnings for this user around this date or status
        $res_e = $conn->query("SELECT * FROM earning WHERE userid = '$uid' ORDER BY id ASC");
        $sum_e = 0;
        $out .= "\nContributing / Member Earning Transactions for User $uid:\n";
        if ($res_e && $res_e->num_rows > 0) {
            while ($e = $res_e->fetch_assoc()) {
                $sum_e += floatval($e['amount']);
                
                // Get source member & product info if ref_id exists
                $ref_id = $e['ref_id'];
                $ref_pkg_info = "N/A";
                $cfg_val = "N/A";
                if (!empty($ref_id) && $ref_id !== '0' && $ref_id !== '0000') {
                    $res_m = $conn->query("SELECT signup_package, join_package, topup FROM member WHERE id = '$ref_id'");
                    if ($res_m && $m_row = $res_m->fetch_assoc()) {
                        $pkg_id = !empty($m_row['signup_package']) ? $m_row['signup_package'] : $m_row['join_package'];
                        $res_p = $conn->query("SELECT prod_name, prod_price, direct_income, matching_income, level_income FROM product WHERE id = '$pkg_id'");
                        if ($res_p && $p_row = $res_p->fetch_assoc()) {
                            $ref_pkg_info = "Pkg ID: $pkg_id ({$p_row['prod_name']}) Amt: ₹{$p_row['prod_price']}";
                            if (strpos($e['type'], 'Direct Sponsor') !== false) {
                                $cfg_val = "direct_income: " . $p_row['direct_income'] . "%";
                            } elseif (strpos($e['type'], 'Level 2') !== false) {
                                $cfg_val = "level_income[1]: " . $p_row['level_income'];
                            } elseif (strpos($e['type'], 'Direct Referral') !== false) {
                                $cfg_val = "level_income[0]: " . $p_row['level_income'];
                            }
                        }
                    }
                }
                
                $out .= sprintf("  Earning ID: %-5d | Type: %-30s | RefID: %-8s | Amt: ₹%-8.2f | Status: %-8s | Date: %-10s | %s | %s\n",
                    $e['id'], $e['type'], $e['ref_id'], $e['amount'], $e['status'], $e['date'], $ref_pkg_info, $cfg_val);
            }
            $out .= sprintf("SUM of all earning.amount for User %s = ₹%.2f\n", $uid, $sum_e);
            $out .= sprintf("Withdraw Request Amount = ₹%.2f\n", floatval($w['amount']));
            $out .= sprintf("Match Result: %s\n", (abs($sum_e - floatval($w['amount'])) < 0.01) ? "EXACT MATCH!" : "MISMATCH (Diff: " . ($sum_e - floatval($w['amount'])) . ")");
        } else {
            $out .= "  No earning transactions found for user $uid.\n";
        }
    }
} else {
    $out .= "No withdraw_request found matching 14355 or status Un-Paid.\n";
}

$out .= "\n=======================================================\n";
$out .= "3. SCAN ALL EARNING TYPES IN `earning` TABLE\n";
$out .= "=======================================================\n";
$res_types = $conn->query("SELECT type, COUNT(*) as cnt, SUM(amount) as total_amt FROM earning GROUP BY type");
if ($res_types) {
    while ($t = $res_types->fetch_assoc()) {
        $out .= sprintf("Type: %-35s | Count: %-5d | Total Amt: ₹%.2f\n", $t['type'], $t['cnt'], $t['total_amt']);
    }
}

file_put_contents(__DIR__ . '/audit_14355_output.txt', $out);
echo "Audit output written to scratch/audit_14355_output.txt successfully.\n";
