<?php
/**
 * GreenAmrutAyurveda — Complete Tree Payout Calculation & Statement Engine (Standalone & Resilient)
 * Works directly in Browser (XAMPP / Apache) and CLI without routing conflicts.
 * 
 * Incomes calculated:
 * 1. Direct Sponsor Income (₹890 / 1 PV, ₹445 / 0.5 PV)
 * 2. Sales Matching Income (2:1 or 1:2 first tail, 1:1 subsequent @ ₹890 / 1 PV)
 * 3. Direct Referral Bonus Level 1 (30% of direct downline Matching Income)
 * 4. Direct Referral Bonus Level 2 (20% of 2nd level downline Matching Income)
 */

header('Content-Type: text/html; charset=utf-8');
ini_set('display_errors', 1);
error_reporting(E_ALL);

// Locate database.php relative to file location
$possible_paths = [
    __DIR__ . '/system/application/config/database.php',
    __DIR__ . '/application/config/database.php',
    dirname(__DIR__) . '/application/config/database.php',
];

$db_config_file = null;
foreach ($possible_paths as $p) {
    if (file_exists($p)) {
        $db_config_file = $p;
        break;
    }
}

if (!$db_config_file) {
    die("Database configuration file not found in paths: " . implode(', ', $possible_paths));
}

if (!defined('BASEPATH')) {
    define('BASEPATH', dirname($db_config_file) . '/');
}
if (!defined('ENVIRONMENT')) {
    define('ENVIRONMENT', 'development');
}
include($db_config_file);

$db_config = $db['default'];
$host   = !empty($db_config['hostname']) ? $db_config['hostname'] : 'localhost';
$user   = $db_config['username'];
$pass   = $db_config['password'];
$dbname = $db_config['database'];

$conn = null;
try {
    $conn = new mysqli($host, $user, $pass, $dbname);
} catch (Exception $e) {
    try {
        $conn = new mysqli('localhost', 'root', '', $dbname);
    } catch (Exception $e2) {
        die("Connection to database failed: " . $e2->getMessage());
    }
}
if ($conn->connect_error) {
    die("Connection error: " . $conn->connect_error);
}

// -------------------------------------------------------------
// HELPER FUNCTIONS
// -------------------------------------------------------------
function credit_wallet_direct($conn, $userid, $amount) {
    if ($amount <= 0) return;
    $res = $conn->query("SELECT balance FROM wallet WHERE userid = '$userid'");
    if ($res && $res->num_rows > 0) {
        $conn->query("UPDATE wallet SET balance = balance + $amount WHERE userid = '$userid'");
    } else {
        $conn->query("INSERT INTO wallet (userid, balance) VALUES ('$userid', $amount)");
    }
}

function count_pv_recursive($conn, $node_id) {
    if (empty($node_id) || $node_id === '0') return 0;
    $total = 0;
    $res = $conn->query("SELECT mypv, team_power FROM member WHERE id = '$node_id'");
    if ($res && $res->num_rows > 0) {
        $r = $res->fetch_assoc();
        $total += floatval($r['mypv'] ?? 0) + floatval($r['team_power'] ?? 0);
    }
    $children = $conn->query("SELECT id FROM member WHERE position = '$node_id'");
    if ($children) {
        while ($ch = $children->fetch_assoc()) {
            $total += count_pv_recursive($conn, $ch['id']);
        }
    }
    return $total;
}

function update_all_tree_legs($conn) {
    $members_res = $conn->query("SELECT id, A, B, self_power_a, self_power_b FROM member");
    if ($members_res) {
        while ($m = $members_res->fetch_assoc()) {
            $left_pv = (!empty($m['A']) && $m['A'] !== '0') ? count_pv_recursive($conn, $m['A']) : 0;
            $right_pv = (!empty($m['B']) && $m['B'] !== '0') ? count_pv_recursive($conn, $m['B']) : 0;

            $left_pv  += floatval($m['self_power_a'] ?? 0);
            $right_pv += floatval($m['self_power_b'] ?? 0);

            $conn->query("UPDATE member SET total_a_pv = $left_pv, total_b_pv = $right_pv WHERE id = '{$m['id']}'");
        }
    }
}

function find_sponsor_level($conn, $userid, $level) {
    if ($level <= 0 || empty($userid) || $userid === '0') return false;
    $cur = $userid;
    for ($i = 1; $i <= $level; $i++) {
        $res = $conn->query("SELECT sponsor FROM member WHERE id = '$cur' LIMIT 1");
        if (!$res || $res->num_rows == 0) return false;
        $row = $res->fetch_assoc();
        $cur = trim($row['sponsor'] ?? '');
        if (empty($cur) || $cur === '0') return false;
    }
    return $cur;
}

$action = isset($_GET['action']) ? $_GET['action'] : '';
$today = date('Y-m-d');
$processed_logs = [];

// First update all tree PVs
update_all_tree_legs($conn);

// -------------------------------------------------------------
// PROCESS PAYOUT ACTION / CLEAN RECALCULATE ACTION
// -------------------------------------------------------------
if ($action === 'clean_recalculate') {
    // 1. Reset all member paid PVs and pairs so matching evaluates fresh
    $conn->query("UPDATE member SET paid_a_pv = 0, paid_b_pv = 0, total_pairs = 0");

    // 2. Clear old earnings, laps_earning, wallets, and pending withdrawals
    $conn->query("TRUNCATE TABLE earning");
    $conn->query("TRUNCATE TABLE laps_earning");
    $conn->query("TRUNCATE TABLE wallet");
    $conn->query("DELETE FROM withdraw_request WHERE status = 'Un-Paid' OR status = 'Pending'");

    // 3. Ensure product level income is set to standard 30,20
    $conn->query("UPDATE product SET level_income = '30,20' WHERE level_income IS NULL OR level_income = '' OR level_income NOT LIKE '%,%'");

    $action = 'process_payout';
    $processed_logs[] = "🧹 सर्व जुने चुकीचे पेआउट्स यशस्वीरीत्या रिसेट केले!";
}

if ($action === 'process_payout') {
    $created_count = 0;

    // 1. Fetch default product 1 settings
    $p1_res = $conn->query("SELECT * FROM product WHERE id = 1 LIMIT 1");
    $p1 = $p1_res ? $p1_res->fetch_assoc() : null;
    $default_direct_rate = ($p1 && floatval($p1['direct_income']) > 0) ? floatval($p1['direct_income']) : 890.0;
    $default_match_rate  = ($p1 && floatval($p1['matching_income']) > 0) ? floatval($p1['matching_income']) : 890.0;
    $default_level_str   = '30,20';

    // 2. Process Direct Sponsor Income for all active members
    // 2. Process Direct Sponsor Income for all members
    $all_members_res = $conn->query("SELECT * FROM member WHERE id NOT IN ('1000') ORDER BY id ASC");
    if ($all_members_res) {
        while ($m = $all_members_res->fetch_assoc()) {
            $uid = $m['id'];
            if ($uid === '1001') continue;

            $sp_id = trim($m['sponsor'] ?? '');
            if (empty($sp_id) || $sp_id === '0' || $sp_id === '1000') continue;

            $pv = floatval($m['mypv'] ?? 0) > 0 ? floatval($m['mypv']) : 1.0;

            $chk_dir = $conn->query("SELECT id FROM earning WHERE userid = '$sp_id' AND ref_id = '$uid' AND type = 'Direct Sponsor Income' LIMIT 1");
            if (!$chk_dir || $chk_dir->num_rows == 0) {
                $dir_amt = $default_direct_rate * $pv;
                $secret = "DIR-{$sp_id}-" . date('YmdHis') . "-" . rand(100, 999);
                $conn->query("INSERT INTO earning (userid, amount, type, ref_id, date, secret, status) VALUES ('$sp_id', '$dir_amt', 'Direct Sponsor Income', '$uid', '$today', '$secret', 'Paid')");
                credit_wallet_direct($conn, $sp_id, $dir_amt);
                $processed_logs[] = "✅ Direct Income: User #{$sp_id} ला User #{$uid} चा Direct Sponsor Income: ₹" . number_format($dir_amt, 2) . " जमा झाला.";
                $created_count++;
            }
        }
    }

    // 3. Process Binary Matching Income (Sales Matching) for all members
    update_all_tree_legs($conn);
    $earners_res = $conn->query("SELECT * FROM member WHERE id NOT IN ('1000') ORDER BY id ASC");
    if ($earners_res) {
        while ($em = $earners_res->fetch_assoc()) {
            $eid = $em['id'];
            $a_side   = floatval($em['total_a_pv'] ?? 0);
            $b_side   = floatval($em['total_b_pv'] ?? 0);
            $paid_a   = floatval($em['paid_a_pv'] ?? 0);
            $paid_b   = floatval($em['paid_b_pv'] ?? 0);
            $tot_pair = intval($em['total_pairs'] ?? 0);

            $avail_a  = max(0, $a_side - $paid_a);
            $avail_b  = max(0, $b_side - $paid_b);
            $fa       = floor($avail_a);
            $fb       = floor($avail_b);

            $pairs = 0; $deduct_a = 0; $deduct_b = 0;

            if ($tot_pair == 0) {
                // First Match: 2:1 or 1:2
                if ($fa >= 2 && $fb >= 1 && ($fa >= $fb || $fb < 2)) {
                    $pairs    = 1;
                    $deduct_a = 2;
                    $deduct_b = 1;
                    $rem_a = floor($fa - 2);
                    $rem_b = floor($fb - 1);
                    $subsequent = (int)min($rem_a, $rem_b);
                    if ($subsequent > 0) {
                        $deduct_a += $subsequent;
                        $deduct_b += $subsequent;
                        $pairs    += $subsequent;
                    }
                } elseif ($fb >= 2 && $fa >= 1) {
                    $pairs    = 1;
                    $deduct_a = 1;
                    $deduct_b = 2;
                    $rem_a = floor($fa - 1);
                    $rem_b = floor($fb - 2);
                    $subsequent = (int)min($rem_a, $rem_b);
                    if ($subsequent > 0) {
                        $deduct_a += $subsequent;
                        $deduct_b += $subsequent;
                        $pairs    += $subsequent;
                    }
                }
            } else {
                // Subsequent: 1:1
                if ($fa >= 1 && $fb >= 1) {
                    $pairs    = (int)min($fa, $fb);
                    $deduct_a = $pairs;
                    $deduct_b = $pairs;
                }
            }

            if ($pairs > 0) {
                $pay_amt = $pairs * $default_match_rate;
                $tx_ref = "BIN-{$eid}-" . date('YmdHis') . "-" . rand(1000, 9999);

                // Insert Earning
                $conn->query("INSERT INTO earning (userid, amount, type, ref_id, date, pair_match, secret, status) VALUES ('$eid', '$pay_amt', 'Matching Income', '', '$today', '$pairs', '$tx_ref', 'Paid')");
                credit_wallet_direct($conn, $eid, $pay_amt);

                // Update Member PV & Pairs
                $new_paid_a = $paid_a + $deduct_a;
                $new_paid_b = $paid_b + $deduct_b;
                $new_pairs  = $tot_pair + $pairs;
                $conn->query("UPDATE member SET paid_a_pv = $new_paid_a, paid_b_pv = $new_paid_b, total_pairs = $new_pairs WHERE id = '$eid'");

                $processed_logs[] = "⚡ Matching Income: User #{$eid} ला {$pairs} Pairs चे Matching Income: ₹" . number_format($pay_amt, 2) . " जमा झाले (Paid A: +{$deduct_a}, Paid B: +{$deduct_b}).";
                $created_count++;
            }
        }
    }

    // 4. Process Direct Referral Bonus (DRB Level 1 = 30% & Level 2 = 20%) for all Matching Earners
    $match_res = $conn->query("SELECT * FROM earning WHERE type = 'Matching Income' AND amount > 0");
    if ($match_res) {
        while ($me = $match_res->fetch_assoc()) {
            $m_uid = $me['userid'];
            $m_amt = floatval($me['amount']);

            // Get downline member details
            $u_q = $conn->query("SELECT sponsor, signup_package, join_package FROM member WHERE id = '$m_uid' LIMIT 1");
            if ($u_q && $u_q->num_rows > 0) {
                $u_row = $u_q->fetch_assoc();
                $sp1_id = trim($u_row['sponsor'] ?? '');
                $pkg_id = !empty($u_row['signup_package']) ? $u_row['signup_package'] : ($u_row['join_package'] ?? 0);

                // Read package level_income
                $pkg_level_str = '';
                if (!empty($pkg_id)) {
                    $p_q = $conn->query("SELECT level_income FROM product WHERE id = '$pkg_id' LIMIT 1");
                    if ($p_q && $p_q->num_rows > 0) {
                        $p_row = $p_q->fetch_assoc();
                        $pkg_level_str = $p_row['level_income'] ?? '';
                    }
                }
                if (empty($pkg_level_str)) {
                    $pkg_level_str = $default_level_str;
                }

                $levels = array_map('trim', explode(',', $pkg_level_str));
                $drb_l1_pct = (isset($levels[0]) && is_numeric($levels[0])) ? floatval($levels[0]) : 30.0;
                $drb_l2_pct = (isset($levels[1]) && is_numeric($levels[1])) ? floatval($levels[1]) : 20.0;

                // Level 1 DRB (30%)
                if (!empty($sp1_id) && $sp1_id !== '0' && $sp1_id !== '1000' && $drb_l1_pct > 0) {
                    $sp1_q = $conn->query("SELECT * FROM member WHERE id = '$sp1_id' LIMIT 1");
                    $sp1_row = ($sp1_q && $sp1_q->num_rows > 0) ? $sp1_q->fetch_assoc() : null;

                    if ($sp1_row) {
                        $sp1_pairs = intval($sp1_row['total_pairs'] ?? 0);
                        if ($sp1_pairs >= 1) {
                            $chk_drb1 = $conn->query("SELECT id FROM earning WHERE userid = '$sp1_id' AND ref_id = '$m_uid' AND type IN ('Direct Referral Bonus Level 1', 'Direct Referral Bonus') AND levlno = 1 LIMIT 1");
                            if (!$chk_drb1 || $chk_drb1->num_rows == 0) {
                                $drb1_amt = $m_amt * ($drb_l1_pct / 100.0);
                                $secret = "DRB1-{$sp1_id}-" . date('YmdHis') . "-" . rand(100, 999);
                                $conn->query("INSERT INTO earning (userid, amount, type, ref_id, levlno, date, secret, status) VALUES ('$sp1_id', '$drb1_amt', 'Direct Referral Bonus Level 1', '$m_uid', 1, '$today', '$secret', 'Paid')");
                                credit_wallet_direct($conn, $sp1_id, $drb1_amt);
                                $processed_logs[] = "🎁 DRB Level 1: User #{$sp1_id} ला User #{$m_uid} च्या मॅचिंगवर DRB Level 1 ({$drb_l1_pct}%): ₹" . number_format($drb1_amt, 2) . " जमा झाला.";
                                $created_count++;
                            }
                        }
                    }

                    // Level 2 DRB (20%)
                    if ($sp1_row) {
                        $sp2_id = trim($sp1_row['sponsor'] ?? '');
                        if (!empty($sp2_id) && $sp2_id !== '0' && $sp2_id !== '1000' && $drb_l2_pct > 0) {
                            $sp2_q = $conn->query("SELECT * FROM member WHERE id = '$sp2_id' LIMIT 1");
                            $sp2_row = ($sp2_q && $sp2_q->num_rows > 0) ? $sp2_q->fetch_assoc() : null;

                            if ($sp2_row) {
                                $sp2_pairs = intval($sp2_row['total_pairs'] ?? 0);
                                if ($sp2_pairs >= 1) {
                                    $chk_drb2 = $conn->query("SELECT id FROM earning WHERE userid = '$sp2_id' AND ref_id = '$m_uid' AND type IN ('Direct Referral Bonus Level 2', 'Direct Referral Bonus') AND levlno = 2 LIMIT 1");
                                    if (!$chk_drb2 || $chk_drb2->num_rows == 0) {
                                        $drb2_amt = $m_amt * ($drb_l2_pct / 100.0);
                                        $secret = "DRB2-{$sp2_id}-" . date('YmdHis') . "-" . rand(100, 999);
                                        $conn->query("INSERT INTO earning (userid, amount, type, ref_id, levlno, date, secret, status) VALUES ('$sp2_id', '$drb2_amt', 'Direct Referral Bonus Level 2', '$m_uid', 2, '$today', '$secret', 'Paid')");
                                        credit_wallet_direct($conn, $sp2_id, $drb2_amt);
                                        $processed_logs[] = "🎁 DRB Level 2: User #{$sp2_id} ला User #{$m_uid} च्या मॅचिंगवर DRB Level 2 ({$drb_l2_pct}%): ₹" . number_format($drb2_amt, 2) . " जमा झाला.";
                                        $created_count++;
                                    }
                                }
                            }
                        }
                    }
                }
            }
        }
    }

    if ($created_count == 0) {
        $processed_logs[] = "सर्व पेआउट आणि इन्कम्स आधीच पूर्णपणे अप-टू-डेट आहेत (कोणतेही डुप्लिकेट इन्कम टाळले गेले).";
    }
}

// -------------------------------------------------------------
// FETCH COMPLETE STATEMENT DATA
// -------------------------------------------------------------
update_all_tree_legs($conn);
$all_m_res = $conn->query("SELECT * FROM member WHERE id != '1000' ORDER BY id ASC");
$statement = [];
$tot_direct = 0; $tot_matching = 0; $tot_drb1 = 0; $tot_drb2 = 0; $tot_gross = 0;

if ($all_m_res) {
    while ($m = $all_m_res->fetch_assoc()) {
        $uid = $m['id'];

        // Direct
        $r_d = $conn->query("SELECT SUM(amount) as s FROM earning WHERE userid = '$uid' AND type IN ('Direct Sponsor Income', 'Direct Sponsor Commission')");
        $dir = $r_d ? floatval($r_d->fetch_assoc()['s'] ?? 0) : 0;

        // Matching
        $r_m = $conn->query("SELECT SUM(amount) as s, SUM(pair_match) as p FROM earning WHERE userid = '$uid' AND type = 'Matching Income'");
        $m_row = $r_m ? $r_m->fetch_assoc() : null;
        $match = floatval($m_row['s'] ?? 0);
        $pairs = intval($m_row['p'] ?? 0);

        // DRB1
        $r_drb1 = $conn->query("SELECT SUM(amount) as s FROM earning WHERE userid = '$uid' AND type IN ('Direct Referral Bonus Level 1', 'Direct Referral Bonus') AND (levlno = 1 OR type = 'Direct Referral Bonus Level 1')");
        $drb1 = $r_drb1 ? floatval($r_drb1->fetch_assoc()['s'] ?? 0) : 0;

        // DRB2
        $r_drb2 = $conn->query("SELECT SUM(amount) as s FROM earning WHERE userid = '$uid' AND type IN ('Direct Referral Bonus Level 2', 'Direct Referral Bonus') AND (levlno = 2 OR type = 'Direct Referral Bonus Level 2')");
        $drb2 = $r_drb2 ? floatval($r_drb2->fetch_assoc()['s'] ?? 0) : 0;

        // Gross
        $gross = $dir + $match + $drb1 + $drb2;

        // Wallet
        $r_w = $conn->query("SELECT balance FROM wallet WHERE userid = '$uid' LIMIT 1");
        $w_bal = $r_w && $r_w->num_rows > 0 ? floatval($r_w->fetch_assoc()['balance'] ?? 0) : 0;

        $statement[] = [
            'id'             => $uid,
            'name'           => $m['name'],
            'sponsor'        => $m['sponsor'],
            'position'       => $m['position'],
            'status'         => $m['status'],
            'topup'          => floatval($m['topup'] ?? 0),
            'pv'             => floatval($m['mypv'] ?? 0),
            'left_pv'        => floatval($m['total_a_pv'] ?? 0),
            'right_pv'       => floatval($m['total_b_pv'] ?? 0),
            'paid_a'         => floatval($m['paid_a_pv'] ?? 0),
            'paid_b'         => floatval($m['paid_b_pv'] ?? 0),
            'lifetime_pairs' => intval($m['total_pairs'] ?? 0),
            'direct_income'  => $dir,
            'matching_income'=> $match,
            'pairs_matched'  => $pairs,
            'drb1_income'    => $drb1,
            'drb2_income'    => $drb2,
            'gross_income'   => $gross,
            'wallet_balance' => $w_bal,
        ];

        $tot_direct   += $dir;
        $tot_matching += $match;
        $tot_drb1     += $drb1;
        $tot_drb2     += $drb2;
        $tot_gross    += $gross;
    }
}
?>
<!DOCTYPE html>
<html lang="mr">
<head>
    <meta charset="UTF-8">
    <title>GreenAmrutAyurveda — संपूर्ण पेआउट व इन्कम कॅल्क्युलेशन रिपोर्ट</title>
    <style>
        body { font-family: -apple-system, BlinkMacSystemFont, "Segoe UI", Roboto, Arial, sans-serif; background: #f0f4f8; margin: 0; padding: 25px; color: #1e293b; }
        .container { max-width: 1440px; margin: 0 auto; background: #fff; border-radius: 14px; padding: 30px; box-shadow: 0 5px 25px rgba(0,0,0,0.08); }
        h1 { color: #0f172a; margin-top: 0; font-size: 26px; }
        .subtitle { color: #64748b; font-size: 15px; margin-bottom: 25px; }
        .btn-box { display: flex; gap: 12px; margin-bottom: 25px; }
        .btn { display: inline-block; padding: 12px 24px; border-radius: 8px; font-weight: 700; font-size: 15px; text-decoration: none; cursor: pointer; border: none; }
        .btn-primary { background: #2563eb; color: #fff; }
        .btn-primary:hover { background: #1d4ed8; }
        .btn-success { background: #16a34a; color: #fff; box-shadow: 0 4px 12px rgba(22,163,74,0.3); }
        .btn-success:hover { background: #15803d; }
        .stats-grid { display: grid; grid-template-columns: repeat(auto-fit, minmax(220px, 1fr)); gap: 18px; margin-bottom: 30px; }
        .stat-card { background: #f8fafc; border: 1px solid #e2e8f0; border-radius: 10px; padding: 18px; }
        .stat-card h4 { margin: 0 0 8px 0; color: #64748b; font-size: 13px; text-transform: uppercase; font-weight: 600; }
        .stat-card .val { font-size: 24px; font-weight: 800; color: #0f172a; }
        .stat-card.c-green .val { color: #16a34a; }
        .stat-card.c-blue .val { color: #2563eb; }
        .stat-card.c-purple .val { color: #7c3aed; }
        .stat-card.c-amber .val { color: #d97706; }
        table { width: 100%; border-collapse: collapse; margin-top: 15px; font-size: 13px; }
        th, td { padding: 11px 12px; text-align: left; border-bottom: 1px solid #e2e8f0; }
        th { background: #f1f5f9; color: #475569; font-weight: 700; position: sticky; top: 0; }
        tr:hover { background: #f8fafc; }
        .badge { display: inline-block; padding: 3px 8px; border-radius: 12px; font-size: 11px; font-weight: 700; }
        .badge-active { background: #dcfce7; color: #15803d; }
        .badge-inactive { background: #fee2e2; color: #b91c1c; }
        .amount-pos { font-weight: 700; color: #16a34a; }
        .amount-zero { color: #94a3b8; }
        .alert-success { background: #dcfce7; border-left: 5px solid #16a34a; color: #166534; padding: 16px; border-radius: 8px; margin-bottom: 25px; font-size: 14px; line-height: 1.6; }
    </style>
</head>
<body>

<div class="container">
    <h1>💼 GreenAmrutAyurveda — संपूर्ण पेआउट व इन्कम कॅल्क्युलेशन रिपोर्ट</h1>
    <p class="subtitle">हा रिपोर्ट सिस्टीममधील सर्व मेंबर्सचे <strong>Direct Sponsor Income, Sales Matching Income (2:1/1:2 & 1:1), Direct Referral Bonus (DRB Level 1 - 30%) आणि Level 2 (20%)</strong> अचूक मोजून दाखवतो.</p>

    <div class="btn-box">
        <a href="?action=clean_recalculate" class="btn" style="background:#dc2626; color:#fff;" onclick="return confirm('जुने चुकीचे पेआउट्स रिसेट करून सर्व जुन्या IDs वर नवीन नियमांनुसार (30% व 20% DRB) फ्रेश कॅल्क्युलेशन करायचे का?');">🔄 [येथे क्लिक करा] जुन्या सर्व IDs वर फ्रेश कॅल्क्युलेट करा (Clean Recalculate)</a>
        <a href="?action=process_payout" class="btn btn-success" onclick="return confirm('सर्व मेंबर्सचे Direct Income, Matching Income आणि DRB (30% व 20%) मोजून पेआउट प्रोसेस करायचे का?');">⚡ सर्व पेआउट मोजा व क्रेडिट करा (Generate Payout)</a>
        <a href="<?php echo parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH); ?>" class="btn btn-primary">🔄 रिफ्रेश करा</a>
    </div>

    <?php if (!empty($processed_logs)): ?>
        <div class="alert-success">
            <strong>🎉 पेआउट प्रोसेस पूर्ण झाले (Results):</strong><br><br>
            <?php foreach ($processed_logs as $l): ?>
                <?php echo htmlspecialchars($l); ?><br>
            <?php endforeach; ?>
        </div>
    <?php endif; ?>

    <!-- Summary Stats -->
    <div class="stats-grid">
        <div class="stat-card c-green">
            <h4>एकूण Direct Sponsor Income</h4>
            <div class="val">₹<?php echo number_format($tot_direct, 2); ?></div>
            <small>(₹890 / ₹445 प्रति Direct)</small>
        </div>
        <div class="stat-card c-blue">
            <h4>एकूण Sales Matching Income</h4>
            <div class="val">₹<?php echo number_format($tot_matching, 2); ?></div>
            <small>(2:1, 1:2 व 1:1 @ ₹890)</small>
        </div>
        <div class="stat-card c-purple">
            <h4>एकूण DRB Level 1 (30%)</h4>
            <div class="val">₹<?php echo number_format($tot_drb1, 2); ?></div>
            <small>(Direct डाऊनलाइन मॅचिंगवर 30%)</small>
        </div>
        <div class="stat-card c-purple">
            <h4>एकूण DRB Level 2 (20%)</h4>
            <div class="val">₹<?php echo number_format($tot_drb2, 2); ?></div>
            <small>(2nd Level डाऊनलाइन मॅचिंगवर 20%)</small>
        </div>
        <div class="stat-card c-amber">
            <h4>एकूण कंपनी पेआउट (Gross Payout)</h4>
            <div class="val">₹<?php echo number_format($tot_gross, 2); ?></div>
            <small>(सर्व इन्कम्सची बेरीज)</small>
        </div>
    </div>

    <!-- Payout Statement Table -->
    <h2>📊 सर्व मेंबर्सचे पेआउट स्टेटमेंट (Member-wise Payout Statement)</h2>
    <table>
        <thead>
            <tr>
                <th>User ID</th>
                <th>मेंबरचे नाव</th>
                <th>Sponsor ID</th>
                <th>Status / Topup</th>
                <th>Left / Right PV</th>
                <th>Lifetime Pairs</th>
                <th>Direct Sponsor Income</th>
                <th>Sales Matching Income</th>
                <th>DRB Level 1 (30%)</th>
                <th>DRB Level 2 (20%)</th>
                <th>एकूण पेआउट (Gross)</th>
                <th>Wallet Balance</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($statement as $row): ?>
            <tr>
                <td><strong><?php echo $row['id']; ?></strong></td>
                <td><?php echo htmlspecialchars($row['name']); ?></td>
                <td><?php echo $row['sponsor']; ?></td>
                <td>
                    <span class="badge <?php echo $row['status'] == 'Active' && $row['topup'] > 0 ? 'badge-active' : 'badge-inactive'; ?>">
                        <?php echo $row['status']; ?>
                    </span>
                    <br><small>₹<?php echo number_format($row['topup'], 2); ?> (<?php echo $row['pv']; ?> PV)</small>
                </td>
                <td>
                    L: <strong><?php echo $row['left_pv']; ?></strong> / R: <strong><?php echo $row['right_pv']; ?></strong>
                    <br><small>Paid: L <?php echo $row['paid_a']; ?> / R <?php echo $row['paid_b']; ?></small>
                </td>
                <td><strong><?php echo $row['lifetime_pairs']; ?></strong> Pairs</td>
                <td class="<?php echo $row['direct_income'] > 0 ? 'amount-pos' : 'amount-zero'; ?>">
                    ₹<?php echo number_format($row['direct_income'], 2); ?>
                </td>
                <td class="<?php echo $row['matching_income'] > 0 ? 'amount-pos' : 'amount-zero'; ?>">
                    ₹<?php echo number_format($row['matching_income'], 2); ?>
                </td>
                <td class="<?php echo $row['drb1_income'] > 0 ? 'amount-pos' : 'amount-zero'; ?>">
                    ₹<?php echo number_format($row['drb1_income'], 2); ?>
                </td>
                <td class="<?php echo $row['drb2_income'] > 0 ? 'amount-pos' : 'amount-zero'; ?>">
                    ₹<?php echo number_format($row['drb2_income'], 2); ?>
                </td>
                <td>
                    <strong style="color: #0f172a; font-size: 15px;">₹<?php echo number_format($row['gross_income'], 2); ?></strong>
                </td>
                <td>
                    <strong style="color: #16a34a; font-size: 15px;">₹<?php echo number_format($row['wallet_balance'], 2); ?></strong>
                </td>
            </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
</div>

</body>
</html>
