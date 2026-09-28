<?php
/**
 * GreenAmrutAyurveda — One-Click Instant Wallet Sync Script
 * 
 * Transfers all pending/uncredited earnings (Direct, Matching, DRB L1, DRB L2, etc.)
 * directly into members' wallet balances with complete transaction logs.
 */

header('Content-Type: text/html; charset=utf-8');
ini_set('display_errors', 1);
error_reporting(E_ALL);

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
    die("Database configuration file not found.");
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

// Fetch all Pending earnings
$query = "SELECT e.*, m.name as member_name 
          FROM earning e 
          LEFT JOIN member m ON e.userid = m.id 
          WHERE e.status = 'Pending' 
          ORDER BY e.id ASC";

$res = $conn->query($query);
$pending_rows = [];
if ($res) {
    while ($row = $res->fetch_assoc()) {
        $pending_rows[] = $row;
    }
}

$synced_count = 0;
$total_credited = 0;
$user_summary = [];

foreach ($pending_rows as $row) {
    $eid    = (int)$row['id'];
    $uid    = $row['userid'];
    $name   = $row['member_name'] ?? 'Member #' . $uid;
    $amount = (float)$row['amount'];
    $type   = $row['type'];
    $ref_id = $row['ref_id'] ?? '';
    $levlno = (int)($row['levlno'] ?? 0);

    if ($amount <= 0 || empty($uid)) {
        continue;
    }

    // 1. Credit wallet
    $w_res = $conn->query("SELECT balance FROM wallet WHERE userid = '$uid'");
    $cur_bal = 0;
    if ($w_res && $w_res->num_rows > 0) {
        $w_row = $w_res->fetch_assoc();
        $cur_bal = (float)$w_row['balance'];
        $new_bal = $cur_bal + $amount;
        $conn->query("UPDATE wallet SET balance = $new_bal WHERE userid = '$uid'");
    } else {
        $new_bal = $amount;
        $conn->query("INSERT INTO wallet (userid, balance) VALUES ('$uid', $new_bal)");
    }

    // 2. Insert wallet_transaction ledger
    $other_text = $type . ($levlno > 0 ? " (Level {$levlno})" : "");
    $other_esc  = $conn->real_escape_string($other_text);
    $ref_esc    = $conn->real_escape_string($ref_id);
    $conn->query("INSERT INTO wallet_transaction (userid, type, amount, ref_id, other) VALUES ('$uid', 'Credit', $amount, '$ref_esc', '$other_esc')");

    // 3. Mark earning as Paid
    $conn->query("UPDATE earning SET status = 'Paid' WHERE id = $eid");

    $synced_count++;
    $total_credited += $amount;

    if (!isset($user_summary[$uid])) {
        $user_summary[$uid] = [
            'name'           => $name,
            'prev_balance'   => $cur_bal,
            'credited'       => 0,
            'new_balance'    => $new_bal,
            'records_count'  => 0,
            'breakdown'      => [],
        ];
    }
    $user_summary[$uid]['credited'] += $amount;
    $user_summary[$uid]['new_balance'] = $new_bal;
    $user_summary[$uid]['records_count']++;
    $user_summary[$uid]['breakdown'][] = "{$type}: ₹" . number_format($amount, 2);
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>GreenAmrut Ayurveda — Wallet Sync Status</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body { background: #f8fafc; font-family: 'Segoe UI', sans-serif; padding: 30px; }
        .card-custom { border-radius: 12px; box-shadow: 0 4px 12px rgba(0,0,0,0.06); border: none; }
        .badge-income { background: #e8f5e9; color: #2e7d32; font-weight: 600; padding: 6px 12px; border-radius: 6px; }
    </style>
</head>
<body>
<div class="container">
    <div class="card card-custom p-4 mb-4">
        <div class="d-flex justify-content-between align-items-center mb-3">
            <div>
                <h3 class="fw-bold text-success mb-1">⚡ Wallet Balance Instant Sync Complete</h3>
                <p class="text-muted mb-0">सर्व पूर्वीचे Pending Payouts युझर्सच्या Wallet मध्ये जमा करण्यात आले आहेत.</p>
            </div>
            <div>
                <a href="<?php echo site_url ? 'system/application/' : 'index.php'; ?>" class="btn btn-outline-secondary">Go to Website</a>
            </div>
        </div>
        <hr>
        <div class="row text-center mb-2">
            <div class="col-md-4">
                <div class="p-3 bg-light rounded">
                    <span class="text-muted d-block small">Total Records Synced</span>
                    <h3 class="fw-bold text-primary mb-0"><?= $synced_count; ?></h3>
                </div>
            </div>
            <div class="col-md-4">
                <div class="p-3 bg-light rounded">
                    <span class="text-muted d-block small">Total Amount Credited</span>
                    <h3 class="fw-bold text-success mb-0">₹<?= number_format($total_credited, 2); ?></h3>
                </div>
            </div>
            <div class="col-md-4">
                <div class="p-3 bg-light rounded">
                    <span class="text-muted d-block small">Members Updated</span>
                    <h3 class="fw-bold text-dark mb-0"><?= count($user_summary); ?></h3>
                </div>
            </div>
        </div>
    </div>

    <div class="card card-custom p-4">
        <h5 class="fw-bold mb-3">Member-wise Wallet Update Summary:</h5>
        <?php if (empty($user_summary)): ?>
            <div class="alert alert-info mb-0">
                ✅ सर्व Payouts आधीपासूनच युझर्सच्या Wallet मध्ये जमा आहेत (कोणतेही Pending Earning शिल्लक नाही).
            </div>
        <?php else: ?>
            <div class="table-responsive">
                <table class="table table-bordered table-hover align-middle">
                    <thead class="table-dark">
                        <tr>
                            <th>User ID</th>
                            <th>Member Name</th>
                            <th>Incomes Synced</th>
                            <th>Credited Amount</th>
                            <th>New Wallet Balance</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($user_summary as $uid => $u): ?>
                            <tr>
                                <td class="fw-bold text-primary"><?= $uid; ?></td>
                                <td><?= htmlspecialchars($u['name']); ?></td>
                                <td>
                                    <small class="text-muted"><?= implode(', ', $u['breakdown']); ?></small>
                                </td>
                                <td class="fw-bold text-success">+ ₹<?= number_format($u['credited'], 2); ?></td>
                                <td class="fw-bold fs-6">₹<?= number_format($u['new_balance'], 2); ?></td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        <?php endif; ?>
    </div>
</div>
</body>
</html>
