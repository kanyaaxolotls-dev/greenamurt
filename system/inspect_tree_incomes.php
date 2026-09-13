<?php
/**
 * Tree Income Diagnostic & Synchronization Tool (Standalone & Resilient)
 * GreenAmrutAyurveda
 */

header('Content-Type: text/html; charset=utf-8');
ini_set('display_errors', 1);
error_reporting(E_ALL);

// Locate database.php relative to file location
$possible_paths = [
    __DIR__ . '/application/config/database.php',
    __DIR__ . '/system/application/config/database.php',
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
$host = !empty($db_config['hostname']) ? $db_config['hostname'] : 'localhost';
$user = $db_config['username'];
$pass = $db_config['password'];
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

// Tree Users to inspect (in hierarchical top-down order)
$tree_uids = ['538279', '392145', '538214', '801532', '420510', '142478', '795509'];

$action = isset($_GET['action']) ? $_GET['action'] : '';
$sync_log = [];

// Helper to credit wallet
function credit_wallet($conn, $userid, $amount) {
    if ($amount <= 0) return;
    $res = $conn->query("SELECT balance FROM wallet WHERE userid = '$userid'");
    if ($res && $res->num_rows > 0) {
        $conn->query("UPDATE wallet SET balance = balance + $amount WHERE userid = '$userid'");
    } else {
        $conn->query("INSERT INTO wallet (userid, balance) VALUES ('$userid', $amount)");
    }
}

if ($action === 'sync') {
    // 1. Fetch Product 1 configuration (default fallback)
    $prod_res = $conn->query("SELECT * FROM product WHERE id = 1 LIMIT 1");
    $prod = $prod_res ? $prod_res->fetch_assoc() : null;

    $direct_rate   = ($prod && floatval($prod['direct_income']) > 0) ? floatval($prod['direct_income']) : 890.0;
    $matching_rate = ($prod && floatval($prod['matching_income']) > 0) ? floatval($prod['matching_income']) : 890.0;
    
    $default_level_str = ($prod && !empty($prod['level_income'])) ? $prod['level_income'] : '30,20';

    $today = date('Y-m-d');
    $created_count = 0;

    // 2. Iterate each member and process Direct Sponsor Income (Independent registration-time earning)
    foreach ($tree_uids as $uid) {
        $m_res = $conn->query("SELECT * FROM member WHERE id = '$uid' LIMIT 1");
        if (!$m_res || $m_res->num_rows == 0) continue;
        $m = $m_res->fetch_assoc();

        if (empty($m['status']) || $m['status'] !== 'Active') continue;
        $pv = floatval($m['mypv']) > 0 ? floatval($m['mypv']) : 1.0;
        $sponsor_id = trim($m['sponsor'] ?? '');

        if (!empty($sponsor_id) && $sponsor_id !== '0') {
            // Check Sponsor Status
            $sp_res = $conn->query("SELECT * FROM member WHERE id = '$sponsor_id' LIMIT 1");
            $sp = ($sp_res && $sp_res->num_rows > 0) ? $sp_res->fetch_assoc() : null;

            if ($sp && $sp['status'] === 'Active') {
                // Direct Sponsor Income
                $check_dir = $conn->query("SELECT id FROM earning WHERE userid = '$sponsor_id' AND ref_id = '$uid' AND type = 'Direct Sponsor Income' LIMIT 1");
                if (!$check_dir || $check_dir->num_rows == 0) {
                    $dir_amt = $direct_rate * $pv;
                    $secret = "DIR-{$sponsor_id}-" . date('YmdHis') . "-" . rand(100, 999);
                    $conn->query("INSERT INTO earning (userid, amount, type, ref_id, date, secret, status) VALUES ('$sponsor_id', '$dir_amt', 'Direct Sponsor Income', '$uid', '$today', '$secret', 'Paid')");
                    credit_wallet($conn, $sponsor_id, $dir_amt);
                    $sync_log[] = "✅ User #{$sponsor_id} ला User #{$uid} चा Direct Sponsor Income: ₹" . number_format($dir_amt, 2) . " जमा झाला.";
                    $created_count++;
                }
            }
        }
    }

    // 3. Process Dynamic Direct Referral Bonus (DRB Level 1 & Level 2) for Matching Earners
    $match_res = $conn->query("SELECT * FROM earning WHERE type = 'Matching Income' AND amount > 0");
    if ($match_res) {
        while ($me = $match_res->fetch_assoc()) {
            $m_uid = $me['userid'];
            $m_amt = floatval($me['amount']);

            // Get downline member and their package level_income configuration
            $u_q = $conn->query("SELECT sponsor, signup_package, join_package FROM member WHERE id = '$m_uid' LIMIT 1");
            if ($u_q && $u_q->num_rows > 0) {
                $u_row = $u_q->fetch_assoc();
                $sp1_id = trim($u_row['sponsor'] ?? '');
                $pkg_id = !empty($u_row['signup_package']) ? $u_row['signup_package'] : ($u_row['join_package'] ?? 0);

                // Fetch dynamic level_income for member's package
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
                $drb_l1_pct = (isset($levels[0]) && is_numeric($levels[0])) ? floatval($levels[0]) : 0.0;
                $drb_l2_pct = (isset($levels[1]) && is_numeric($levels[1])) ? floatval($levels[1]) : 0.0;

                // Level 1: Direct Referral Bonus
                if (!empty($sp1_id) && $sp1_id !== '0' && $drb_l1_pct > 0) {
                    $sp1_q = $conn->query("SELECT * FROM member WHERE id = '$sp1_id' LIMIT 1");
                    $sp1_row = ($sp1_q && $sp1_q->num_rows > 0) ? $sp1_q->fetch_assoc() : null;

                    if ($sp1_row && $sp1_row['status'] === 'Active' && floatval($sp1_row['topup'] ?? 0) > 0) {
                        $chk_drb1 = $conn->query("SELECT id FROM earning WHERE userid = '$sp1_id' AND ref_id = '$m_uid' AND type = 'Direct Referral Bonus' AND levlno = 1 LIMIT 1");
                        if (!$chk_drb1 || $chk_drb1->num_rows == 0) {
                            $drb1_amt = $m_amt * ($drb_l1_pct / 100.0);
                            $secret = "DRB1-{$sp1_id}-" . date('YmdHis') . "-" . rand(100, 999);
                            $conn->query("INSERT INTO earning (userid, amount, type, ref_id, levlno, date, secret, status) VALUES ('$sp1_id', '$drb1_amt', 'Direct Referral Bonus', '$m_uid', 1, '$today', '$secret', 'Paid')");
                            credit_wallet($conn, $sp1_id, $drb1_amt);
                            $sync_log[] = "✅ User #{$sp1_id} ला User #{$m_uid} च्या मॅचिंग इन्कमवर DRB Level 1 ({$drb_l1_pct}%): ₹" . number_format($drb1_amt, 2) . " जमा झाला.";
                            $created_count++;
                        }
                    }

                    // Level 2: Direct Referral Bonus Level 2
                    $sp2_id = trim($sp1_row['sponsor'] ?? '');
                    if (!empty($sp2_id) && $sp2_id !== '0' && $drb_l2_pct > 0) {
                        $sp2_q = $conn->query("SELECT * FROM member WHERE id = '$sp2_id' LIMIT 1");
                        $sp2_row = ($sp2_q && $sp2_q->num_rows > 0) ? $sp2_q->fetch_assoc() : null;

                        if ($sp2_row && $sp2_row['status'] === 'Active' && floatval($sp2_row['topup'] ?? 0) > 0) {
                            $chk_drb2 = $conn->query("SELECT id FROM earning WHERE userid = '$sp2_id' AND ref_id = '$m_uid' AND type = 'Direct Referral Bonus (Level 2)' AND levlno = 2 LIMIT 1");
                            if (!$chk_drb2 || $chk_drb2->num_rows == 0) {
                                $drb2_amt = $m_amt * ($drb_l2_pct / 100.0);
                                $secret = "DRB2-{$sp2_id}-" . date('YmdHis') . "-" . rand(100, 999);
                                $conn->query("INSERT INTO earning (userid, amount, type, ref_id, levlno, date, secret, status) VALUES ('$sp2_id', '$drb2_amt', 'Direct Referral Bonus (Level 2)', '$m_uid', 2, '$today', '$secret', 'Paid')");
                                credit_wallet($conn, $sp2_id, $drb2_amt);
                                $sync_log[] = "✅ User #{$sp2_id} ला User #{$m_uid} च्या मॅचिंग इन्कमवर DRB Level 2 ({$drb_l2_pct}%): ₹" . number_format($drb2_amt, 2) . " जमा झाला.";
                                $created_count++;
                            }
                        }
                    }
                }
            }
        }
    }

    if ($created_count == 0) {
        $sync_log[] = "सर्व इन्कम्स आधीच अप-टू-डेट आहेत (कोणतेही डुप्लिकेट इन्कम टाळले गेले).";
    }
}

// Fetch Package Settings
$pkg_res = $conn->query("SELECT * FROM product WHERE id = 1 LIMIT 1");
$package = $pkg_res ? $pkg_res->fetch_assoc() : null;

// Fetch Tree Members Data
$members_data = [];
$uids_in = "'" . implode("','", $tree_uids) . "'";
$res_m = $conn->query("SELECT * FROM member WHERE id IN ($uids_in) ORDER BY FIELD(id, $uids_in)");
if ($res_m) {
    while ($r = $res_m->fetch_assoc()) {
        $members_data[$r['id']] = $r;
    }
}

// Fetch Earnings Data
$earnings_data = [];
$res_e = $conn->query("SELECT * FROM earning WHERE userid IN ($uids_in) OR ref_id IN ($uids_in) ORDER BY id DESC");
if ($res_e) {
    while ($r = $res_e->fetch_assoc()) {
        $earnings_data[] = $r;
    }
}

// Fetch Laps Data
$laps_data = [];
$res_l = $conn->query("SELECT * FROM laps_earning WHERE userid IN ($uids_in) ORDER BY id DESC");
if ($res_l) {
    while ($r = $res_l->fetch_assoc()) {
        $laps_data[] = $r;
    }
}

// Fetch Wallets
$wallets = [];
$res_w = $conn->query("SELECT * FROM wallet WHERE userid IN ($uids_in)");
if ($res_w) {
    while ($r = $res_w->fetch_assoc()) {
        $wallets[$r['userid']] = $r['balance'];
    }
}

// Fetch Withdraw Requests
$withdraws = [];
$res_wr = $conn->query("SELECT * FROM withdraw_request WHERE userid IN ($uids_in) ORDER BY id DESC");
if ($res_wr) {
    while ($r = $res_wr->fetch_assoc()) {
        $withdraws[] = $r;
    }
}
?>
<!DOCTYPE html>
<html lang="mr">
<head>
    <meta charset="UTF-8">
    <title>Tree Income Diagnostic & Verification - GreenAmrutAyurveda</title>
    <style>
        body { font-family: -apple-system, BlinkMacSystemFont, "Segoe UI", Roboto, Arial, sans-serif; background: #f0f2f5; margin: 0; padding: 20px; color: #333; }
        .container { max-width: 1200px; margin: 0 auto; background: #fff; border-radius: 12px; padding: 25px; box-shadow: 0 4px 15px rgba(0,0,0,0.08); }
        h1, h2, h3 { color: #1e3a8a; }
        .badge { display: inline-block; padding: 4px 10px; border-radius: 20px; font-size: 12px; font-weight: 600; }
        .badge-success { background: #dcfce7; color: #15803d; }
        .badge-warning { background: #fef3c7; color: #b45309; }
        .badge-danger { background: #fee2e2; color: #b91c1c; }
        .badge-info { background: #e0f2fe; color: #0369a1; }
        table { width: 100%; border-collapse: collapse; margin-top: 15px; margin-bottom: 25px; }
        th, td { padding: 10px 14px; text-align: left; border-bottom: 1px solid #e5e7eb; font-size: 14px; }
        th { background: #f8fafc; color: #475569; font-weight: 600; }
        tr:hover { background: #f8fafc; }
        .btn { display: inline-block; padding: 12px 24px; background: #2563eb; color: #fff; text-decoration: none; border-radius: 8px; font-weight: 700; font-size: 15px; border: none; cursor: pointer; }
        .btn:hover { background: #1d4ed8; }
        .btn-success { background: #16a34a; box-shadow: 0 4px 10px rgba(22, 163, 74, 0.3); }
        .btn-success:hover { background: #15803d; }
        .alert { padding: 15px; border-radius: 8px; margin-bottom: 20px; font-size: 14px; line-height: 1.6; }
        .alert-info { background: #eff6ff; border-left: 5px solid #3b82f6; }
        .card-grid { display: grid; grid-template-columns: repeat(auto-fit, minmax(240px, 1fr)); gap: 15px; margin-bottom: 25px; }
        .card { background: #f8fafc; border: 1px solid #e2e8f0; border-radius: 8px; padding: 15px; }
        .card h4 { margin: 0 0 10px 0; color: #1e293b; }
        .amount { font-size: 20px; font-weight: 700; color: #16a34a; }
    </style>
</head>
<body>

<div class="container">
    <h1>📊 GreenAmrutAyurveda — Tree Income Diagnostic Report</h1>
    <p>हा रिपोर्ट ट्रीमधील सर्व ७ युझर्सचे (538279, 392145, 538214, 801532, 420510, 142478, 795509) सर्व इन्कम्स, वॉलेट आणि पेआउट तपासून दाखवतो.</p>

    <div style="margin-bottom: 20px; display: flex; gap: 10px;">
        <a href="?action=sync" class="btn btn-success" onclick="return confirm('ट्रीमधील सर्व ७ युझर्सचे Direct Income, DRB (30% व 20%) आणि Single Leg Income मोजून क्रेडिट करायचे का?');">🚀 [क्लिक करा] सर्व इन्कम्स Sync व Credit करा</a>
        <a href="<?php echo parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH); ?>" class="btn">🔄 रिफ्रेश करा</a>
    </div>

    <?php if (!empty($sync_log)): ?>
        <div class="alert alert-info">
            <strong style="font-size: 16px;">🎉 सिंक्रोनाइझेशन पूर्ण झाले (Results):</strong><br><br>
            <?php foreach ($sync_log as $msg): ?>
                <?php echo htmlspecialchars($msg); ?><br>
            <?php endforeach; ?>
        </div>
    <?php endif; ?>

    <!-- Package Configuration Section -->
    <h2>⚙️ १. Health Package Configuration (Product ID #1)</h2>
    <div class="card-grid">
        <div class="card">
            <h4>Direct Income</h4>
            <div class="amount">₹<?php echo number_format($package['direct_income'] ?? 0, 2); ?></div>
            <small>(10% / ₹890 per 1 PV)</small>
        </div>
        <div class="card">
            <h4>Level Income (DRB & Single Leg)</h4>
            <div class="amount" style="color: #2563eb;"><?php echo htmlspecialchars($package['level_income'] ?? '0'); ?></div>
            <small><?php echo ($package && $package['level_income'] === '30,20') ? '✅ Level 1: 30%, Level 2: 20%' : '⚠️ कृपया 30,20 करा'; ?></small>
        </div>
        <div class="card">
            <h4>Sales Matching Income</h4>
            <div class="amount">₹<?php echo number_format($package['matching_income'] ?? 0, 2); ?></div>
            <small>(₹890 per Pair, 2:1/1:2 & 1:1)</small>
        </div>
        <div class="card">
            <h4>Daily Capping</h4>
            <div class="amount" style="color: #d97706;">₹<?php echo number_format($package['capping'] ?? 0, 2); ?></div>
            <small>(16 Pairs = ₹14,240)</small>
        </div>
    </div>

    <!-- Tree Members Section -->
    <h2>👥 २. Tree Members Status (७ मेंबर्स)</h2>
    <table>
        <thead>
            <tr>
                <th>User ID</th>
                <th>नाव</th>
                <th>Sponsor ID</th>
                <th>Position</th>
                <th>Status</th>
                <th>Topup (₹)</th>
                <th>Left PV / Right PV</th>
                <th>Paid Pairs</th>
                <th>Wallet Balance</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($tree_uids as $uid): 
                $m = $members_data[$uid] ?? null;
                if (!$m) continue;
                $w_bal = $wallets[$uid] ?? 0;
            ?>
            <tr>
                <td><strong><?php echo $m['id']; ?></strong></td>
                <td><?php echo htmlspecialchars($m['name']); ?></td>
                <td><?php echo $m['sponsor']; ?></td>
                <td><?php echo $m['position']; ?> (<?php echo $m['signup_package'] ? 'Pkg #'.$m['signup_package'] : 'N/A'; ?>)</td>
                <td>
                    <span class="badge <?php echo ($m['status'] == 'Active' && $m['topup'] > 0) ? 'badge-success' : 'badge-danger'; ?>">
                        <?php echo $m['status']; ?>
                    </span>
                </td>
                <td>₹<?php echo number_format($m['topup'], 2); ?> (<?php echo $m['mypv']; ?> PV)</td>
                <td>Left: <strong><?php echo $m['total_a_pv']; ?></strong> / Right: <strong><?php echo $m['total_b_pv']; ?></strong></td>
                <td><?php echo $m['total_pairs']; ?> Pairs (Paid A: <?php echo $m['paid_a_pv']; ?>, Paid B: <?php echo $m['paid_b_pv']; ?>)</td>
                <td><strong style="color: #16a34a; font-size: 16px;">₹<?php echo number_format($w_bal, 2); ?></strong></td>
            </tr>
            <?php endforeach; ?>
        </tbody>
    </table>

    <!-- Earning Entries -->
    <h2>💰 ३. Earning Table Entries (क्रेडिट झालेले सर्व इन्कम्स)</h2>
    <?php if (empty($earnings_data)): ?>
        <p style="color: #b91c1c;">कोणतेही Earning रेकॉर्ड सापडले नाही.</p>
    <?php else: ?>
        <table>
            <thead>
                <tr>
                    <th>Earning ID</th>
                    <th>User ID</th>
                    <th>इन्कमचा प्रकार (Income Type)</th>
                    <th>रक्कम (Amount)</th>
                    <th>Ref ID (From User)</th>
                    <th>Level</th>
                    <th>Pairs</th>
                    <th>तारीख (Date)</th>
                    <th>Status</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($earnings_data as $e): ?>
                <tr>
                    <td>#<?php echo $e['id']; ?></td>
                    <td><strong><?php echo $e['userid']; ?></strong></td>
                    <td>
                        <span class="badge badge-info"><?php echo htmlspecialchars($e['type']); ?></span>
                    </td>
                    <td><strong style="color: #16a34a;">₹<?php echo number_format($e['amount'], 2); ?></strong></td>
                    <td><?php echo htmlspecialchars($e['ref_id']); ?></td>
                    <td><?php echo $e['levlno']; ?></td>
                    <td><?php echo $e['pair_match']; ?></td>
                    <td><?php echo $e['date']; ?></td>
                    <td>
                        <span class="badge <?php echo ($e['status'] == 'Paid') ? 'badge-success' : 'badge-warning'; ?>">
                            <?php echo $e['status']; ?>
                        </span>
                    </td>
                </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    <?php endif; ?>

    <!-- Laps Earning Entries -->
    <h2>⚠️ ४. Laps Earning (अट पूर्ण न झाल्यामुळे हुकलेले इन्कम्स)</h2>
    <?php if (empty($laps_data)): ?>
        <p style="color: #15803d;">कोणतेही Laps Earning नाही (सर्व कमिशन योग्यरीत्या पात्र झाले आहेत).</p>
    <?php else: ?>
        <table>
            <thead>
                <tr>
                    <th>Laps ID</th>
                    <th>User ID</th>
                    <th>रक्कम (Amount)</th>
                    <th>Income Type</th>
                    <th>कारण (Reason)</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($laps_data as $l): ?>
                <tr>
                    <td>#<?php echo $l['id']; ?></td>
                    <td><strong><?php echo $l['userid']; ?></strong></td>
                    <td>₹<?php echo number_format($l['amount'], 2); ?></td>
                    <td><?php echo htmlspecialchars($l['type']); ?></td>
                    <td style="color: #b91c1c;"><?php echo htmlspecialchars($l['reason']); ?></td>
                </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    <?php endif; ?>

    <!-- Withdraw Requests -->
    <h2>🏦 ५. Payout / Withdrawal Requests (`withdraw_request` Table)</h2>
    <?php if (empty($withdraws)): ?>
        <p>कोणतीही Payout रिक्वेस्ट नाही.</p>
    <?php else: ?>
        <table>
            <thead>
                <tr>
                    <th>ID</th>
                    <th>User ID</th>
                    <th>एकूण रक्कम (Amount)</th>
                    <th>Tax (TDS + Admin)</th>
                    <th>देय रक्कम (Net Payable)</th>
                    <th>Date</th>
                    <th>Status</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($withdraws as $w): ?>
                <tr>
                    <td>#<?php echo $w['id']; ?></td>
                    <td><strong><?php echo $w['userid']; ?></strong></td>
                    <td>₹<?php echo number_format($w['amount'], 2); ?></td>
                    <td>₹<?php echo number_format($w['tax'], 2); ?></td>
                    <td><strong style="color: #16a34a;">₹<?php echo number_format($w['amount'] - $w['tax'], 2); ?></strong></td>
                    <td><?php echo $w['date']; ?></td>
                    <td>
                        <span class="badge <?php echo ($w['status'] == 'Paid') ? 'badge-success' : 'badge-danger'; ?>">
                            <?php echo $w['status']; ?>
                        </span>
                    </td>
                </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    <?php endif; ?>

</div>

</body>
</html>
