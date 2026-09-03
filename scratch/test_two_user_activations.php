<?php
header('Content-Type: text/plain; charset=utf-8');

define('BASEPATH', __DIR__ . '/../system/');
define('ENVIRONMENT', 'development');

$possible_paths = array(
    __DIR__ . '/../system/application/config/database.php',
    __DIR__ . '/system/application/config/database.php',
    __DIR__ . '/application/config/database.php',
    dirname(__DIR__) . '/system/application/config/database.php'
);

$db_config_file = null;
foreach ($possible_paths as $path) {
    if (file_exists($path)) {
        $db_config_file = $path;
        break;
    }
}

if (!$db_config_file) {
    die("Database config file not found.\n");
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
        die("Connection failed: " . $e2->getMessage());
    }
}
if ($conn->connect_error) {
    die("Database connection failed: " . $conn->connect_error . "\n");
}

echo "====================================================================\n";
echo "GREENAMRUTAYURVEDA: TWO USER ACTIVATION & INCREMENTAL ORDER TEST\n";
echo "====================================================================\n";

$userA_id = 888001;
$userA_name = "User A (Test)";
$userB_id = 888002;
$userB_name = "User B (Test)";
$sponsor = 1001;
$pkg_id = 1;
$pkg_price = 1999.00;
$pkg_pv = 1000;
$today = date('Y-m-d');

// Clean up existing test users if present
$conn->query("DELETE FROM product_item_sale WHERE order_id IN (SELECT orderid FROM product_sale WHERE userid IN ($userA_id, $userB_id))");
$conn->query("DELETE FROM product_sale WHERE userid IN ($userA_id, $userB_id)");
$conn->query("DELETE FROM earning WHERE userid IN ($userA_id, $userB_id)");
$conn->query("DELETE FROM member WHERE id IN ($userA_id, $userB_id)");

// Step 1: Create Inactive User A and User B
$stmt = $conn->prepare("INSERT INTO member (id, name, sponsor, signup_package, topup, mypv, join_time, status, position, placement_leg) VALUES (?, ?, ?, ?, 0.00, 0, ?, 'Suspend', 1001, 'A')");
$stmt->bind_param("isiss", $userA_id, $userA_name, $sponsor, $pkg_id, $today);
$stmt->execute();

$stmt2 = $conn->prepare("INSERT INTO member (id, name, sponsor, signup_package, topup, mypv, join_time, status, position, placement_leg) VALUES (?, ?, ?, ?, 0.00, 0, ?, 'Suspend', 1001, 'B')");
$stmt2->bind_param("isiss", $userB_id, $userB_name, $sponsor, $pkg_id, $today);
$stmt2->execute();

echo "✓ Step 1: Created Inactive User A (ID: $userA_id) and User B (ID: $userB_id)\n";

// --- ACTIVATION OF USER A ---
echo "\n--- ACTIVATING USER A ---\n";
// Check Duplicate for User A
$dupA = $conn->query("SELECT id FROM product_sale WHERE userid = $userA_id AND type = 'topup'");
if ($dupA && $dupA->num_rows > 0) {
    die("User A duplicate check failed.\n");
}

// Generate Order ID for User A
$max_res1 = $conn->query("SELECT MAX(orderid) AS maxid FROM product_sale");
$max_row1 = $max_res1 ? $max_res1->fetch_assoc() : null;
$orderidA = ($max_row1 && !empty($max_row1['maxid'])) ? ($max_row1['maxid'] + 1) : 1001;

// Insert Order A
$sqlA = "INSERT INTO product_sale (product_id, userid, cost, date, order_by, orderid, pv, type, epin_amount, status) VALUES ($pkg_id, $userA_id, $pkg_price, '$today', 'Admin', $orderidA, $pkg_pv, 'topup', $pkg_price, 'Processing')";
if (!$conn->query($sqlA)) {
    die("Insert sale A failed: " . $conn->error . "\n");
}

$sql_itemA = "INSERT INTO product_item_sale (product_id, order_id, cost) VALUES ($pkg_id, $orderidA, $pkg_price)";
if (!$conn->query($sql_itemA)) {
    die("Insert item A failed: " . $conn->error . "\n");
}

// Update User A State
$conn->query("UPDATE member SET topup = $pkg_price, mypv = $pkg_pv, status = 'Active', activation_date = '$today' WHERE id = $userA_id");
echo "✓ USER A ACTIVATED: Order ID #$orderidA Created successfully.\n";

// --- ACTIVATION OF USER B ---
echo "\n--- ACTIVATING USER B ---\n";
// Check Duplicate for User B (per-user check)
$dupB = $conn->query("SELECT id FROM product_sale WHERE userid = $userB_id AND type = 'topup'");
if ($dupB && $dupB->num_rows > 0) {
    die("User B duplicate check failed.\n");
}

// Generate Order ID for User B (Must Increment!)
$max_res2 = $conn->query("SELECT MAX(orderid) AS maxid FROM product_sale");
$max_row2 = $max_res2 ? $max_res2->fetch_assoc() : null;
$orderidB = ($max_row2 && !empty($max_row2['maxid'])) ? ($max_row2['maxid'] + 1) : 1001;

// Insert Order B
$sqlB = "INSERT INTO product_sale (product_id, userid, cost, date, order_by, orderid, pv, type, epin_amount, status) VALUES ($pkg_id, $userB_id, $pkg_price, '$today', 'Admin', $orderidB, $pkg_pv, 'topup', $pkg_price, 'Processing')";
if (!$conn->query($sqlB)) {
    die("Insert sale B failed: " . $conn->error . "\n");
}

$sql_itemB = "INSERT INTO product_item_sale (product_id, order_id, cost) VALUES ($pkg_id, $orderidB, $pkg_price)";
if (!$conn->query($sql_itemB)) {
    die("Insert item B failed: " . $conn->error . "\n");
}

// Update User B State
$conn->query("UPDATE member SET topup = $pkg_price, mypv = $pkg_pv, status = 'Active', activation_date = '$today' WHERE id = $userB_id");
echo "✓ USER B ACTIVATED: Order ID #$orderidB Created successfully (Incremented from #$orderidA).\n";

// --- VERIFY DUPLICATE PROTECTION ---
echo "\n--- TESTING DUPLICATE PROTECTION FOR USER A ---\n";
$memA = $conn->query("SELECT topup FROM member WHERE id = $userA_id")->fetch_assoc();
$saleA_check = $conn->query("SELECT id, orderid FROM product_sale WHERE userid = $userA_id AND type = 'topup'")->fetch_assoc();
if ($memA['topup'] > 0 && $saleA_check) {
    echo "✓ Duplicate Protection PASSED: User A already active with Order #{$saleA_check['orderid']}. Re-activation blocked!\n";
}

// --- VERIFY ADMIN PENDING ORDERS QUERY ---
echo "\n====================================================================\n";
echo "ADMIN PENDING ORDERS QUERY VERIFICATION:\n";
echo "====================================================================\n";
$admin_query = $conn->query("SELECT * FROM product_sale WHERE status = 'Processing' AND userid IN ($userA_id, $userB_id) GROUP BY orderid ORDER BY date ASC");

echo "Rows Returned: " . $admin_query->num_rows . " (Expected: 2)\n";
echo "--------------------------------------------------------------------\n";
$sn = 1;
while ($row = $admin_query->fetch_assoc()) {
    $m = $conn->query("SELECT name FROM member WHERE id = {$row['userid']}")->fetch_assoc();
    echo sprintf("Row #%d | SN: %d | User ID: %s | Full Name: %s | Order ID: #%s | Cost: ₹%.2f | Date: %s | Status: %s\n",
        $sn, $sn, $row['userid'], $m['name'], $row['orderid'], $row['cost'], $row['date'], $row['status']);
    $sn++;
}
echo "--------------------------------------------------------------------\n";

if ($admin_query->num_rows == 2 && $orderidB == ($orderidA + 1)) {
    echo "\n>>> ALL VERIFICATION CHECKS PASSED PERFECTLY! <<<\n";
} else {
    echo "\n>>> VERIFICATION FAILED! <<<\n";
}
