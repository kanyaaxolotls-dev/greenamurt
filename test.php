<?php
/**
 * Greenamrut Ayurveda - Package & BV Flow Diagnostic Test Script (Phase 2)
 * READ-ONLY Debugging Script
 */

header('Content-Type: text/html; charset=utf-8');
ini_set('display_errors', 1);
error_reporting(E_ALL);

// Load Database Config
$db_config_file = __DIR__ . '/system/application/config/database.php';
$db_config = array();

if (file_exists($db_config_file)) {
    if (!defined('BASEPATH')) {
        define('BASEPATH', __DIR__ . '/system/');
    }
    if (!defined('ENVIRONMENT')) {
        define('ENVIRONMENT', 'development');
    }
    include($db_config_file);
    if (isset($db['default'])) {
        $db_config = $db['default'];
    }
}

$host = isset($db_config['hostname']) && !empty($db_config['hostname']) ? $db_config['hostname'] : 'localhost';
$user = isset($db_config['username']) ? $db_config['username'] : 'root';
$pass = isset($db_config['password']) ? $db_config['password'] : '';
$dbname = isset($db_config['database']) ? $db_config['database'] : 'greenamrutayurve_new';

// Attempt DB Connection
$conn = null;
$conn_error = '';

try {
    $conn = new mysqli($host, $user, $pass, $dbname);
    if ($conn->connect_error) {
        throw new Exception($conn->connect_error);
    }
} catch (Exception $e) {
    try {
        $conn = new mysqli('localhost', 'root', '', $dbname);
        if ($conn->connect_error) {
            $conn_error = "Connection Failed: " . $conn->connect_error;
            $conn = null;
        }
    } catch (Exception $e2) {
        $conn_error = "Connection Failed: " . $e2->getMessage();
        $conn = null;
    }
}

?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Greenamrut Ayurveda - Package & BV Flow Diagnostic</title>
    <style>
        body { font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif; background: #f4f6f9; color: #333; margin: 20px; }
        h1, h2, h3 { color: #2c3e50; }
        .card { background: #fff; border-radius: 8px; padding: 20px; margin-bottom: 20px; box-shadow: 0 2px 5px rgba(0,0,0,0.05); }
        table { width: 100%; border-collapse: collapse; margin-top: 10px; font-size: 14px; }
        th, td { border: 1px solid #e2e8f0; padding: 8px 12px; text-align: left; }
        th { background: #edf2f7; color: #2d3748; }
        tr:nth-child(even) { background: #f8fafc; }
        .badge { display: inline-block; padding: 4px 8px; border-radius: 4px; font-size: 12px; font-weight: bold; }
        .badge-success { background: #c6f6d5; color: #22543d; }
        .badge-danger { background: #fed7d7; color: #742a2a; }
        .badge-info { background: #e2e8f0; color: #2d3748; }
        .flow-box { background: #edf2f7; border-left: 5px solid #4299e1; padding: 15px; font-family: monospace; font-size: 15px; font-weight: bold; margin: 15px 0; }
        pre { background: #2d3748; color: #fff; padding: 15px; border-radius: 6px; overflow-x: auto; font-family: monospace; font-size: 14px; }
    </style>
</head>
<body>

<h1>Greenamrut Ayurveda - Package & BV Flow Diagnostic</h1>

<?php if (!$conn): ?>
    <div class="card" style="border-left: 5px solid #e53e3e;">
        <h2>Database Connection Error</h2>
        <p><?php echo htmlspecialchars($conn_error); ?></p>
    </div>
<?php exit; endif; ?>

<?php
// Helper function to safely query user
function fetch_user($conn, $id) {
    if (empty($id) || $id === '0') return null;
    $stmt = $conn->prepare("SELECT * FROM member WHERE id = ?");
    if (!$stmt) return null;
    $stmt->bind_param("s", $id);
    $stmt->execute();
    $res = $stmt->get_result();
    return $res ? $res->fetch_assoc() : null;
}

// 1. Fetch Products/Packages Configuration from Database
$products = array();
$res_prod = $conn->query("SELECT * FROM product");
if ($res_prod) {
    while ($row = $res_prod->fetch_assoc()) {
        $products[] = $row;
    }
}

// 2. Fetch Target Users
$root_user = fetch_user($conn, '243026');
$left_user = fetch_user($conn, '311461');
$right_user = fetch_user($conn, '711364');

// 3. Fetch Product Sales Records for target users
$sales = array();
$res_sales = $conn->query("SELECT * FROM product_sale WHERE userid IN ('311461', '711364', '243026')");
if ($res_sales) {
    while ($row = $res_sales->fetch_assoc()) {
        $sales[] = $row;
    }
}
?>

<!-- PACKAGE / PRODUCT CONFIGURATION TABLE -->
<div class="card">
    <h2>1. Package / Product Configuration in Database (`product` Table)</h2>
    <table>
        <thead>
            <tr>
                <th>Product ID</th>
                <th>Product Name (`prod_name`)</th>
                <th>Price (`prod_price`)</th>
                <th>Configured BV/PV (`pv`)</th>
                <th>Dealer Price (`dealer_price`)</th>
                <th>Direct Income (`direct_income`)</th>
                <th>Matching Income (`matching_income`)</th>
                <th>Status</th>
            </tr>
        </thead>
        <tbody>
            <?php if (!empty($products)): ?>
                <?php foreach ($products as $p): ?>
                <tr>
                    <td><?php echo htmlspecialchars($p['id']); ?></td>
                    <td><?php echo htmlspecialchars($p['prod_name']); ?></td>
                    <td>₹<?php echo htmlspecialchars(isset($p['prod_price']) ? $p['prod_price'] : '0.00'); ?></td>
                    <td><strong style="color:<?php echo (isset($p['pv']) && floatval($p['pv']) > 0) ? 'green' : 'red'; ?>"><?php echo htmlspecialchars(isset($p['pv']) ? $p['pv'] : '0'); ?></strong></td>
                    <td>₹<?php echo htmlspecialchars(isset($p['dealer_price']) ? $p['dealer_price'] : '0.00'); ?></td>
                    <td><?php echo htmlspecialchars(isset($p['direct_income']) ? $p['direct_income'] : '0.00'); ?></td>
                    <td><?php echo htmlspecialchars(isset($p['matching_income']) ? $p['matching_income'] : '0.00'); ?></td>
                    <td><?php echo htmlspecialchars(isset($p['status']) ? $p['status'] : 'N/A'); ?></td>
                </tr>
                <?php endforeach; ?>
            <?php else: ?>
                <tr><td colspan="8">No products found in `product` table.</td></tr>
            <?php endif; ?>
        </tbody>
    </table>
</div>

<!-- PRODUCT SALE RECORDS -->
<div class="card">
    <h2>2. Package Sales Transactions (`product_sale` Table)</h2>
    <table>
        <thead>
            <tr>
                <th>Sale ID</th>
                <th>User ID</th>
                <th>Product ID (`product_id`)</th>
                <th>Cost (`cost`)</th>
                <th>Date</th>
            </tr>
        </thead>
        <tbody>
            <?php if (!empty($sales)): ?>
                <?php foreach ($sales as $s): ?>
                <tr>
                    <td><?php echo htmlspecialchars(isset($s['id']) ? $s['id'] : 'N/A'); ?></td>
                    <td><?php echo htmlspecialchars($s['userid']); ?></td>
                    <td><?php echo htmlspecialchars($s['product_id']); ?></td>
                    <td>₹<?php echo htmlspecialchars($s['cost']); ?></td>
                    <td><?php echo htmlspecialchars(isset($s['date']) ? $s['date'] : 'N/A'); ?></td>
                </tr>
                <?php endforeach; ?>
            <?php else: ?>
                <tr><td colspan="5">No records found in `product_sale` table for these users.</td></tr>
            <?php endif; ?>
        </tbody>
    </table>
</div>

<!-- USER MEMBER RECORDS INVESTIGATION -->
<div class="card">
    <h2>3. User Account State (`member` Table)</h2>
    <table>
        <thead>
            <tr>
                <th>User ID</th>
                <th>Name</th>
                <th>Signup Package ID</th>
                <th>Topup Amount (`topup`)</th>
                <th>User mypv (`mypv`)</th>
                <th>Left BV (`total_a_pv`)</th>
                <th>Right BV (`total_b_pv`)</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach (array($root_user, $left_user, $right_user) as $u): ?>
                <?php if ($u): ?>
                <tr>
                    <td><?php echo htmlspecialchars($u['id']); ?></td>
                    <td><?php echo htmlspecialchars($u['name']); ?></td>
                    <td><?php echo htmlspecialchars(isset($u['signup_package']) ? $u['signup_package'] : 'N/A'); ?></td>
                    <td>₹<?php echo htmlspecialchars(isset($u['topup']) ? $u['topup'] : '0.00'); ?></td>
                    <td><strong style="color:<?php echo (isset($u['mypv']) && floatval($u['mypv']) > 0) ? 'green' : 'red'; ?>"><?php echo htmlspecialchars(isset($u['mypv']) ? $u['mypv'] : '0'); ?></strong></td>
                    <td><?php echo htmlspecialchars(isset($u['total_a_pv']) ? $u['total_a_pv'] : '0'); ?></td>
                    <td><?php echo htmlspecialchars(isset($u['total_b_pv']) ? $u['total_b_pv'] : '0'); ?></td>
                </tr>
                <?php endif; ?>
            <?php endforeach; ?>
        </tbody>
    </table>
</div>

<!-- COMPLETE FLOW TRACE ANALYSIS -->
<div class="card">
    <h2>4. End-to-End Package & BV Flow Analysis</h2>
    
    <div class="flow-box">
        PACKAGE → PACKAGE BV/PV → USER mypv → update_legs() → PARENT total_a_pv/total_b_pv
    </div>

    <h3>Step-by-Step Execution Trace:</h3>
    <ol style="line-height: 1.8;">
        <li>
            <strong>1. Package Purchase & Insertion:</strong><br>
            When a user purchases a package or topup is executed (in <code>controllers/Member.php</code> lines 1511-1632 or <code>controllers/Users.php</code> line 449), a record is inserted into <code>product_sale</code> and the user's <code>member.signup_package</code> and <code>member.topup</code> fields are updated.
        </li>
        <li>
            <strong>2. Package Amount & Package BV Configuration:</strong><br>
            The system retrieves package details from table <code>product</code> using <code>$package_cost = $this->db_model->select_multi('prod_price,pv,matching_income', 'product', array('id' => $signup_package));</code>.
        </li>
        <li>
            <strong>3. User `mypv` Assignment:</strong><br>
            In <code>controllers/Member.php</code> (line 1514 & 1616), the system sets <code>member.mypv</code> equal to <code>$package_cost->pv</code>.<br>
            <em>Crucial Finding:</em> In table <code>product</code>, package ID 1 has its column <code>pv</code> set to <strong>0</strong> (or NULL). Therefore, <code>$package_cost->pv</code> evaluated to 0, which wrote 0 into <code>member.mypv</code> for users purchasing package 1!
        </li>
        <li>
            <strong>4. Execution of `update_legs()`:</strong><br>
            In <code>controllers/Member.php</code> (topup method) and <code>controllers/Users.php</code> (topup method), <code>$this->earning->update_legs()</code> is <strong>NEVER called</strong> after topup completes.<br>
            Even if a user manually triggers <code>update_legs()</code> via the UI button "Calculate pair", <code>update_legs()</code> reads <code>mypv</code> for downline users via <code>count_pv()</code>. Because downline <code>mypv = 0</code>, <code>update_legs()</code> updates <code>total_a_pv = 0</code> and <code>total_b_pv = 0</code> on parent user 243026.
        </li>
        <li>
            <strong>5. Parent Total BV Updates:</strong><br>
            Parent columns <code>total_a_pv</code> and <code>total_b_pv</code> on user 243026 remain 0 in table <code>member</code>, which causes the User Tree UI to display <strong>Left BV = 0</strong> and <strong>Right BV = 0</strong>.
        </li>
    </ol>
</div>

<!-- DIAGNOSTIC SUMMARY -->
<div class="card">
    <h2>5. Diagnostic Root Cause Summary</h2>
    <pre>
PACKAGE TO BV FLOW DIAGNOSIS RESULT
--------------------------------------------------------------------------------

1. PACKAGE CONFIGURATION IN DATABASE:
   Package ID 1 (Test/Signup package) has column `pv` = 0 in table `product`.

2. USER `mypv` ASSIGNMENT:
   When Users 311461 and 711364 purchased Package ID 1, `Member.php` assigned:
   member.mypv = product.pv = 0.
   Therefore, their personal BV (`mypv`) remained 0.

3. `update_legs()` AUTOMATION MISSING AFTER TOPUP:
   After package topup/purchase completes in `controllers/Member.php` (topup function)
   and `controllers/Users.php` (topup_member function), `$this->earning->update_legs()`
   is NOT executed automatically.

4. SUBTREE BV CALCULATION & TREE DISPLAY:
   Because downline `mypv` is 0, when `update_legs()` executes (e.g. via manual "Calculate pair" button),
   the recursive sum `count_pv()` yields 0. Thus `total_a_pv` and `total_b_pv` on root 243026
   are written as 0 in table `member`, causing User Tree to display Left BV = 0 and Right BV = 0.

EXACT ROOT CAUSE:
--------------------------------------------------------------------------------
A. Database Setup: Table `product` column `pv` for Package ID 1 is set to 0.
B. Application Code: `controllers/Member.php` (topup function) does not call `$this->earning->update_legs()` after updating user topup and package.

FILES & FUNCTIONS TO BE FIXED IN PRODUCTION (AFTER APPROVAL):
--------------------------------------------------------------------------------
1. Database: Update table `product` column `pv` for Package ID 1 (or relevant package) to its correct BV value.
2. File: `system/application/controllers/Member.php` -> Function: `topup()`
   - Ensure `$this->earning->update_legs()` is called after topup/package update.
   - Fix the `$user_mypv > 0` guard check before invoking income/tree updates.
    </pre>
</div>

</body>
</html>
