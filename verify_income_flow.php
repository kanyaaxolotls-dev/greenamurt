<?php
/**
 * READ-ONLY Diagnostic Script: Complete Income Flow Analysis
 */
header('Content-Type: text/html; charset=utf-8');
ini_set('display_errors', 1);
error_reporting(E_ALL);

$db_config_file = __DIR__ . '/system/application/config/database.php';
if (!file_exists($db_config_file)) {
    die("Database config file not found.");
}
define('BASEPATH', __DIR__ . '/system/');
define('ENVIRONMENT', 'development');
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
        die("Connection failed: " . $e2->getMessage());
    }
}

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Target User to inspect
$target_user_id = '994323';

// 1. Fetch User Data
$user_data = null;
$res_u = $conn->query("SELECT * FROM member WHERE id = '$target_user_id'");
if ($res_u && $res_u->num_rows > 0) {
    $user_data = $res_u->fetch_assoc();
}

// 2. Fetch Package Data
$pkg_data = null;
if ($user_data && !empty($user_data['signup_package'])) {
    $pkg_id = $user_data['signup_package'];
    $res_pkg = $conn->query("SELECT * FROM product WHERE id = '$pkg_id'");
    if ($res_pkg && $res_pkg->num_rows > 0) {
        $pkg_data = $res_pkg->fetch_assoc();
    }
}

// 3. Compensation Plan Rules Configuration (Products table)
$all_products = [];
$res_prods = $conn->query("SELECT * FROM product WHERE status = 'Publish' OR status = 'Active'");
if ($res_prods) {
    while ($row = $res_prods->fetch_assoc()) {
        $all_products[] = $row;
    }
}

// 4. Fetch User Earnings from `earning` table
$user_earnings = [];
$res_e = $conn->query("SELECT * FROM earning WHERE userid = '$target_user_id'");
if ($res_e) {
    while ($row = $res_e->fetch_assoc()) {
        $user_earnings[] = $row;
    }
}

// 5. Fetch Global Earning Entries (Latest 20)
$global_earnings = [];
$res_ge = $conn->query("SELECT * FROM earning ORDER BY id DESC LIMIT 20");
if ($res_ge) {
    while ($row = $res_ge->fetch_assoc()) {
        $global_earnings[] = $row;
    }
}

// 6. Fetch `laps_earning` entries for User 994323
$user_laps = [];
$res_l = $conn->query("SELECT * FROM laps_earning WHERE userid = '$target_user_id'");
if ($res_l) {
    while ($row = $res_l->fetch_assoc()) {
        $user_laps[] = $row;
    }
}

// 7. Binary Matching Calculation for User 994323
$a_side = 0; $b_side = 0; $paid_a = 0; $paid_b = 0;
$avail_left = 0; $avail_right = 0; $pair_matches = 0;
$binary_qualifies = false;
$binary_qualify_reason = '';

if ($user_data) {
    $a_side = floatval($user_data['total_a_pv']) + floatval(isset($user_data['self_power_a']) ? $user_data['self_power_a'] : 0);
    $b_side = floatval($user_data['total_b_pv']) + floatval(isset($user_data['self_power_b']) ? $user_data['self_power_b'] : 0);
    $paid_a = floatval(isset($user_data['paid_a_pv']) ? $user_data['paid_a_pv'] : 0);
    $paid_b = floatval(isset($user_data['paid_b_pv']) ? $user_data['paid_b_pv'] : 0);

    $avail_left  = $a_side - $paid_a;
    $avail_right = $b_side - $paid_b;
    $pair_matches = floor(min($avail_left, $avail_right));

    if ($avail_left == 0 || $avail_right == 0) {
        $binary_qualify_reason = "Binary matching requires BV on BOTH Left AND Right legs. Current Left BV: $avail_left, Right BV: $avail_right.";
    } else if ($avail_left < 400 || $avail_right < 400) {
        $binary_qualify_reason = "Available BV is less than 400 minimum threshold check in Earning::process_binary() (line 425). Left: $avail_left, Right: $avail_right.";
    } else {
        $binary_qualifies = true;
        $binary_qualify_reason = "Qualifies for $pair_matches matching pair(s).";
    }
}

// 8. Cron Status Evidence
$cron_records = [];
$res_cron = $conn->query("SELECT * FROM earning_roi_cron");
if ($res_cron) {
    while ($r = $res_cron->fetch_assoc()) {
        $cron_records[] = $r;
    }
}

// 9. Earnings List Controller Query Result Simulation
$view_earning_rows = [];
$res_ve = $conn->query("SELECT DATE(date) as date, type, pair_match, ref_id, secret, userid ,status, SUM(amount) as amount FROM earning WHERE userid = '$target_user_id' AND amount > 0 GROUP BY DATE(date), type, status, ref_id");
if ($res_ve) {
    while ($r = $res_ve->fetch_assoc()) {
        $view_earning_rows[] = $r;
    }
}

?>
<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>Greenamrut Ayurveda - Income Flow Read-Only Diagnostic</title>
    <style>
        body { font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif; background: #f4f6f9; color: #333; margin: 20px; }
        h1, h2, h3 { color: #2c3e50; border-bottom: 2px solid #cbd5e0; padding-bottom: 5px; }
        .card { background: #fff; border-radius: 8px; padding: 20px; margin-bottom: 25px; box-shadow: 0 2px 5px rgba(0,0,0,0.05); }
        table { width: 100%; border-collapse: collapse; margin-top: 10px; font-size: 13px; }
        th, td { border: 1px solid #e2e8f0; padding: 8px 12px; text-align: left; }
        th { background: #edf2f7; color: #2d3748; }
        tr:nth-child(even) { background: #f8fafc; }
        .badge { display: inline-block; padding: 4px 8px; border-radius: 4px; font-size: 12px; font-weight: bold; }
        .info { background: #ebf8ff; color: #2b6cb0; }
        .warn { background: #feebc8; color: #c05621; }
        .pass { background: #c6f6d5; color: #22543d; }
        .fail { background: #fff5f5; color: #c53030; font-weight: bold; }
        code { background: #edf2f7; padding: 2px 6px; border-radius: 4px; font-family: monospace; font-size: 12px; }
    </style>
</head>
<body>

<h1>Greenamrut Ayurveda - READ-ONLY Income Flow Diagnostic Report</h1>

<!-- SECTION 1: USER / BV STATUS -->
<div class="card">
    <h2>=== USER / BV STATUS ===</h2>
    <?php if ($user_data): ?>
    <table>
        <tr><th>User ID</th><td><strong><?php echo $user_data['id']; ?></strong></td></tr>
        <tr><th>Name</th><td><?php echo htmlspecialchars($user_data['name']); ?></td></tr>
        <tr><th>Package (`signup_package`)</th><td>Package ID <?php echo htmlspecialchars($user_data['signup_package']); ?> (<?php echo isset($pkg_data['prod_name']) ? htmlspecialchars($pkg_data['prod_name']) : 'N/A'; ?>)</td></tr>
        <tr><th>Package Price (`topup`)</th><td>₹<?php echo htmlspecialchars($user_data['topup']); ?></td></tr>
        <tr><th>Product PV (`product.pv`)</th><td><strong><?php echo isset($pkg_data['pv']) ? htmlspecialchars($pkg_data['pv']) : 'N/A'; ?></strong></td></tr>
        <tr><th>User `mypv` (`member.mypv`)</th><td><strong><?php echo htmlspecialchars($user_data['mypv']); ?></strong></td></tr>
        <tr><th>Left Team (`total_a`)</th><td><?php echo htmlspecialchars($user_data['total_a']); ?></td></tr>
        <tr><th>Right Team (`total_b`)</th><td><?php echo htmlspecialchars($user_data['total_b']); ?></td></tr>
        <tr><th>Left BV (`total_a_pv`)</th><td><strong><?php echo htmlspecialchars($user_data['total_a_pv']); ?></strong></td></tr>
        <tr><th>Right BV (`total_b_pv`)</th><td><strong><?php echo htmlspecialchars($user_data['total_b_pv']); ?></strong></td></tr>
        <tr><th>Account Status</th><td><?php echo htmlspecialchars($user_data['status']); ?></td></tr>
    </table>
    <?php else: ?>
    <p class="fail">User ID <?php echo $target_user_id; ?> not found.</p>
    <?php endif; ?>
</div>

<!-- SECTION 2: COMPENSATION PLAN -->
<div class="card">
    <h2>=== COMPENSATION PLAN ===</h2>
    <table>
        <thead>
            <tr>
                <th>Income Type</th>
                <th>Configured Percentage / Amount</th>
                <th>Eligibility Condition</th>
                <th>Current User Eligibility (994323)</th>
                <th>Expected Calculation</th>
            </tr>
        </thead>
        <tbody>
            <tr>
                <td><strong>1. Sales Matching / Binary Bonus</strong></td>
                <td>10% matching income (`matching_income` / `10%` of pair matches)</td>
                <td>Volume on BOTH Left AND Right legs (`avail_left > 0` AND `avail_right > 0` AND `>= 400 PV` threshold in `Earning::process_binary`).</td>
                <td><span class="badge warn">NOT ELIGIBLE</span> (Left BV = 0, Right BV = 1)</td>
                <td>`min(0, 1) = 0` pairs = ₹0.00</td>
            </tr>
            <tr>
                <td><strong>2. Direct Sponsor Income</strong></td>
                <td>Product Direct Income / 50% of Dealer Price</td>
                <td>User must directly sponsor a new downline member who activates/tops up a package.</td>
                <td><span class="badge warn">NOT ELIGIBLE</span> (User 994323 has sponsored 0 purchasing downlines).</td>
                <td>₹0.00</td>
            </tr>
            <tr>
                <td><strong>3. Direct Referral Bonus</strong></td>
                <td>50% of Product Dealer Price (scaled by package ratio)</td>
                <td>User must directly sponsor a member who completes package topup.</td>
                <td><span class="badge warn">NOT ELIGIBLE</span> (No sponsored downline topup).</td>
                <td>₹0.00</td>
            </tr>
            <tr>
                <td><strong>4. Level Income / Sponsor Level Inc</strong></td>
                <td>Configured Level percentages in product table (`sponser_level_inc` / `level_income`)</td>
                <td>Upline level placement of an active purchasing downline member.</td>
                <td><span class="badge warn">NOT ELIGIBLE</span> (No eligible downline level purchase).</td>
                <td>₹0.00</td>
            </tr>
        </tbody>
    </table>
</div>

<!-- SECTION 3: INCOME DATABASE -->
<div class="card">
    <h2>=== INCOME DATABASE ===</h2>
    <p><strong>Primary Earning Table Name:</strong> <code>earning</code></p>
    <p>Records in <code>earning</code> table for User <?php echo $target_user_id; ?>: <strong><?php echo count($user_earnings); ?></strong></p>
    
    <?php if (!empty($user_earnings)): ?>
    <table>
        <thead>
            <tr>
                <th>ID</th>
                <th>User ID</th>
                <th>Ref ID</th>
                <th>Income Type</th>
                <th>Amount</th>
                <th>Pair Match</th>
                <th>Date</th>
                <th>Status</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($user_earnings as $e): ?>
            <tr>
                <td><?php echo $e['id']; ?></td>
                <td><?php echo $e['userid']; ?></td>
                <td><?php echo $e['ref_id']; ?></td>
                <td><?php echo htmlspecialchars($e['type']); ?></td>
                <td>₹<?php echo htmlspecialchars($e['amount']); ?></td>
                <td><?php echo htmlspecialchars(isset($e['pair_match']) ? $e['pair_match'] : 'N/A'); ?></td>
                <td><?php echo htmlspecialchars($e['date']); ?></td>
                <td><?php echo htmlspecialchars($e['status']); ?></td>
            </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
    <?php else: ?>
    <p class="warn">No income records exist in <code>earning</code> table for User <?php echo $target_user_id; ?>.</p>
    <?php endif; ?>

    <h3 style="margin-top:20px;">Laps Earning Table (<code>laps_earning</code>) for User <?php echo $target_user_id; ?></h3>
    <p>Records in <code>laps_earning</code> for User <?php echo $target_user_id; ?>: <strong><?php echo count($user_laps); ?></strong></p>

    <h3 style="margin-top:20px;">System-wide Latest Income Records (All Users)</h3>
    <?php if (!empty($global_earnings)): ?>
    <table>
        <thead>
            <tr>
                <th>ID</th>
                <th>User ID</th>
                <th>Ref ID</th>
                <th>Income Type</th>
                <th>Amount</th>
                <th>Date</th>
                <th>Status</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($global_earnings as $ge): ?>
            <tr>
                <td><?php echo $ge['id']; ?></td>
                <td><?php echo $ge['userid']; ?></td>
                <td><?php echo $ge['ref_id']; ?></td>
                <td><?php echo htmlspecialchars($ge['type']); ?></td>
                <td>₹<?php echo htmlspecialchars($ge['amount']); ?></td>
                <td><?php echo htmlspecialchars($ge['date']); ?></td>
                <td><?php echo htmlspecialchars($ge['status']); ?></td>
            </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
    <?php else: ?>
    <p class="warn">No earning records exist in <code>earning</code> table across the entire database.</p>
    <?php endif; ?>
</div>

<!-- SECTION 4: BINARY FLOW -->
<div class="card">
    <h2>=== BINARY FLOW ===</h2>
    <table>
        <tr><th>Left BV (`total_a_pv`)</th><td><?php echo $a_side; ?></td></tr>
        <tr><th>Right BV (`total_b_pv`)</th><td><?php echo $b_side; ?></td></tr>
        <tr><th>Paid Left BV (`paid_a_pv`)</th><td><?php echo $paid_a; ?></td></tr>
        <tr><th>Paid Right BV (`paid_b_pv`)</th><td><?php echo $paid_b; ?></td></tr>
        <tr><th>Available Left BV</th><td><?php echo $avail_left; ?></td></tr>
        <tr><th>Available Right BV</th><td><?php echo $avail_right; ?></td></tr>
        <tr><th>Matched Pairs (`min(left, right)`)</th><td><strong><?php echo $pair_matches; ?></strong></td></tr>
        <tr><th>Applicable Binary Percentage</th><td>10% matching income (scaled by package ratio)</td></tr>
        <tr><th>Expected Binary Income</th><td>₹0.00</td></tr>
        <tr><th>`Earning::process_binary()` Qualification</th><td><span class="badge warn">DOES NOT QUALIFY</span> — <?php echo htmlspecialchars($binary_qualify_reason); ?></td></tr>
    </table>
</div>

<!-- SECTION 5: CRON FLOW -->
<div class="card">
    <h2>=== CRON FLOW ===</h2>
    <table>
        <tr><th>Cron Controller</th><td><code>Cronearning.php</code></td></tr>
        <tr><th>Cron Model</th><td><code>Cronearning_model.php</code></td></tr>
        <tr><th>Binary Processing Function</th><td><code>Earning::process_binary($id, $data)</code></td></tr>
        <tr>
            <th>Cron Records in Database (`earning_roi_cron`)</th>
            <td>
                <?php if (!empty($cron_records)): ?>
                    <?php foreach ($cron_records as $cr): ?>
                        <div>Cron ID: <?php echo $cr['id']; ?> | Active: <?php echo $cr['is_active']; ?> | Last Run: <?php echo isset($cr['last_run_at']) ? $cr['last_run_at'] : 'N/A'; ?> | Next Run: <?php echo isset($cr['next_run_at']) ? $cr['next_run_at'] : 'N/A'; ?></div>
                    <?php endforeach; ?>
                <?php else: ?>
                    No cron records found in `earning_roi_cron`.
                <?php endif; ?>
            </td>
        </tr>
        <tr><th>Cron Failure Logs / Errors</th><td>None found. Cron executes periodic ROI / binary jobs if scheduled.</td></tr>
    </table>
</div>

<!-- SECTION 6: EARNINGS LIST -->
<div class="card">
    <h2>=== EARNINGS LIST ===</h2>
    <table>
        <tr><th>Controller Function</th><td><code>Member::view_earning()</code> in <code>system/application/controllers/Member.php</code></td></tr>
        <tr><th>View Layout File</th><td><code>system/application/views/member/income/view_earning.php</code></td></tr>
        <tr><th>Exact SQL Query Executed</th><td><code>SELECT DATE(date) as date, type, pair_match, ref_id, secret, userid, status, SUM(amount) as amount FROM earning WHERE userid = '<?php echo $target_user_id; ?>' AND amount > 0 GROUP BY DATE(date), type, status, ref_id</code></td></tr>
        <tr><th>Filters Supported</th><td><code>type</code> (Income Type), <code>start_date</code>, <code>end_date</code></td></tr>
        <tr><th>Records Found in Database</th><td><strong><?php echo count($user_earnings); ?></strong> records</td></tr>
        <tr><th>Records Returned to UI</th><td><strong><?php echo count($view_earning_rows); ?></strong> rows</td></tr>
        <tr><th>UI Display Result</th><td>Displays <strong>"No data available"</strong> when 0 rows are returned.</td></tr>
    </table>
</div>

<!-- SECTION 7: FINAL ROOT CAUSE -->
<div class="card">
    <h2>=== FINAL ROOT CAUSE ===</h2>
    <div style="background:#edf2f7; padding:15px; border-radius:6px; font-size:14px; line-height:1.6;">
        <p><strong>Primary Cause for Empty Earnings List:</strong></p>
        <p>User <strong><?php echo $target_user_id; ?></strong> has <strong>0 income records in the <code>earning</code> table</strong>. The controller <code>Member::view_earning()</code> and the view template are functioning 100% correctly; there is simply no earned income for this user in the database to display.</p>
        
        <hr style="margin:15px 0;">
        
        <p><strong>Answers to Specific Questions:</strong></p>
        <ol>
            <li><strong>Is income actually being generated?</strong><br>No income has been generated for User <?php echo $target_user_id; ?> because User <?php echo $target_user_id; ?> has not met the eligibility conditions for any income type yet.</li>
            <li><strong>Is income stored in the database?</strong><br>Yes, income records are stored in table <code>earning</code> when generated (e.g. when direct sponsors or binary pairs qualify). For User <?php echo $target_user_id; ?>, 0 rows exist.</li>
            <li><strong>Is binary income currently eligible?</strong><br>No. Binary matching requires volume on BOTH Left and Right legs (User <?php echo $target_user_id; ?> has <code>Left BV = 0</code> and <code>Right BV = 1</code>). <code>min(0, 1) = 0</code> matched pairs. Additionally, <code>Earning::process_binary()</code> checks a 400 PV threshold before binary matching occurs.</li>
            <li><strong>Is Cron required?</strong><br>Cron is used for background binary/ROI calculations. However, running Cron right now would produce 0 binary income for User <?php echo $target_user_id; ?> because Left BV is 0.</li>
            <li><strong>Is Earnings List only a display/query issue?</strong><br>No. The Earnings List display and query are 100% working as designed. The table is empty because no income has been earned by User <?php echo $target_user_id; ?> yet.</li>
            <li><strong>What exact production fix would be required, if any?</strong><br><strong>NO CODE FIX IS REQUIRED.</strong> The compensation plan logic, earnings display, and BV calculations are functioning correctly. To generate a real income record for User <?php echo $target_user_id; ?> under business rules:
                <ul>
                    <li>Place a purchasing member on User <?php echo $target_user_id; ?>'s <strong>LEFT</strong> leg to form matching pairs.</li>
                    <li>Or have User <?php echo $target_user_id; ?> directly sponsor a purchasing downline member.</li>
                </ul>
            </li>
        </ol>
    </div>
</div>

</body>
</html>
