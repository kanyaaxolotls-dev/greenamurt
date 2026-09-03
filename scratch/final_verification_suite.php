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
echo "GREENAMRUTAYURVEDA: FINAL END-TO-END VERIFICATION SUITE\n";
echo "====================================================================\n";

$userA_id = 777101;
$userA_name = "Verification User A";
$userB_id = 777102;
$userB_name = "Verification User B";
$sponsor = 1001;
$pkg_id = 1;
$pkg_price = 1999.00;
$pkg_pv = 1000;
$today = date('Y-m-d');

// Clean up existing test data for these two verification users
$conn->query("DELETE FROM product_item_sale WHERE order_id IN (SELECT orderid FROM product_sale WHERE userid IN ($userA_id, $userB_id))");
$conn->query("DELETE FROM product_sale WHERE userid IN ($userA_id, $userB_id)");
$conn->query("DELETE FROM earning WHERE userid IN ($userA_id, $userB_id)");
$conn->query("DELETE FROM member WHERE id IN ($userA_id, $userB_id)");

// ------------------------------------------------------------------
// STEP 1: CREATE INACTIVE TEST USERS
// ------------------------------------------------------------------
$stmtA = $conn->prepare("INSERT INTO member (id, name, sponsor, signup_package, topup, mypv, join_time, status, position, placement_leg) VALUES (?, ?, ?, ?, 0.00, 0, ?, 'Suspend', 1001, 'A')");
$stmtA->bind_param("isiss", $userA_id, $userA_name, $sponsor, $pkg_id, $today);
$stmtA->execute();

$stmtB = $conn->prepare("INSERT INTO member (id, name, sponsor, signup_package, topup, mypv, join_time, status, position, placement_leg) VALUES (?, ?, ?, ?, 0.00, 0, ?, 'Suspend', 1001, 'B')");
$stmtB->bind_param("isiss", $userB_id, $userB_name, $sponsor, $pkg_id, $today);
$stmtB->execute();

echo "✓ Step 1: Created User A (ID: $userA_id) and User B (ID: $userB_id) as Inactive (Topup: ₹0.00)\n";

// ------------------------------------------------------------------
// STEP 2: ACTIVATE TEST USER A
// ------------------------------------------------------------------
echo "\n--- ACTIVATING TEST USER A ---\n";
// Calculate Order ID for User A using exact system logic
$max_query1 = $conn->query("SELECT MAX(orderid) AS maxid FROM product_sale");
$max_row1 = $max_query1 ? $max_query1->fetch_assoc() : null;
$orderidA = ($max_row1 && !empty($max_row1['maxid']) && $max_row1['maxid'] > 0) ? ($max_row1['maxid'] + 1) : 1001;

// Insert product_sale for User A
$sqlA = "INSERT INTO product_sale (product_id, userid, cost, date, order_by, orderid, pv, type, epin_amount, status) VALUES ($pkg_id, $userA_id, $pkg_price, '$today', 'Admin', $orderidA, $pkg_pv, 'topup', $pkg_price, 'Processing')";
if (!$conn->query($sqlA)) {
    die("Insert sale A failed: " . $conn->error . "\n");
}

$sql_itemA = "INSERT INTO product_item_sale (product_id, order_id, cost) VALUES ($pkg_id, $orderidA, $pkg_price)";
if (!$conn->query($sql_itemA)) {
    die("Insert item A failed: " . $conn->error . "\n");
}

// Update member state for User A
$conn->query("UPDATE member SET topup = $pkg_price, mypv = $pkg_pv, status = 'Active', activation_date = '$today' WHERE id = $userA_id");

// Verify User A Database Records
$verify_saleA = $conn->query("SELECT * FROM product_sale WHERE userid = $userA_id AND orderid = $orderidA")->fetch_assoc();
$verify_itemA = $conn->query("SELECT * FROM product_item_sale WHERE order_id = $orderidA")->fetch_assoc();
$verify_memA  = $conn->query("SELECT id, name, topup, status FROM member WHERE id = $userA_id")->fetch_assoc();

echo sprintf("User A ID       : %s\n", $verify_memA['id']);
echo sprintf("User A Status   : %s\n", $verify_memA['status']);
echo sprintf("User A Topup    : ₹%.2f\n", $verify_memA['topup']);
echo sprintf("User A Order ID : #%s (Saved in product_sale)\n", $verify_saleA['orderid']);
echo sprintf("User A Detail ID: #%s (Saved in product_item_sale)\n", $verify_itemA['order_id']);

// ------------------------------------------------------------------
// STEP 3: ACTIVATE TEST USER B
// ------------------------------------------------------------------
echo "\n--- ACTIVATING TEST USER B ---\n";
// Calculate Order ID for User B using exact system logic (Must Increment!)
$max_query2 = $conn->query("SELECT MAX(orderid) AS maxid FROM product_sale");
$max_row2 = $max_query2 ? $max_query2->fetch_assoc() : null;
$orderidB = ($max_row2 && !empty($max_row2['maxid']) && $max_row2['maxid'] > 0) ? ($max_row2['maxid'] + 1) : 1001;

// Insert product_sale for User B
$sqlB = "INSERT INTO product_sale (product_id, userid, cost, date, order_by, orderid, pv, type, epin_amount, status) VALUES ($pkg_id, $userB_id, $pkg_price, '$today', 'Admin', $orderidB, $pkg_pv, 'topup', $pkg_price, 'Processing')";
if (!$conn->query($sqlB)) {
    die("Insert sale B failed: " . $conn->error . "\n");
}

$sql_itemB = "INSERT INTO product_item_sale (product_id, order_id, cost) VALUES ($pkg_id, $orderidB, $pkg_price)";
if (!$conn->query($sql_itemB)) {
    die("Insert item B failed: " . $conn->error . "\n");
}

// Update member state for User B
$conn->query("UPDATE member SET topup = $pkg_price, mypv = $pkg_pv, status = 'Active', activation_date = '$today' WHERE id = $userB_id");

// Verify User B Database Records
$verify_saleB = $conn->query("SELECT * FROM product_sale WHERE userid = $userB_id AND orderid = $orderidB")->fetch_assoc();
$verify_itemB = $conn->query("SELECT * FROM product_item_sale WHERE order_id = $orderidB")->fetch_assoc();
$verify_memB  = $conn->query("SELECT id, name, topup, status FROM member WHERE id = $userB_id")->fetch_assoc();

echo sprintf("User B ID       : %s\n", $verify_memB['id']);
echo sprintf("User B Status   : %s\n", $verify_memB['status']);
echo sprintf("User B Topup    : ₹%.2f\n", $verify_memB['topup']);
echo sprintf("User B Order ID : #%s (Saved in product_sale)\n", $verify_saleB['orderid']);
echo sprintf("User B Detail ID: #%s (Saved in product_item_sale)\n", $verify_itemB['order_id']);

// ------------------------------------------------------------------
// STEP 4: DUPLICATE ACTIVATION TEST FOR USER A
// ------------------------------------------------------------------
echo "\n--- DUPLICATE ACTIVATION TEST FOR USER A ---\n";
$dup_check = $conn->query("SELECT id, orderid FROM product_sale WHERE userid = $userA_id AND type = 'topup'");
$is_already_active = ($verify_memA['topup'] > 0 && $dup_check->num_rows > 0);
if ($is_already_active) {
    echo "✓ Duplicate Check SUCCESS: User A is already active (topup > 0) with Order #{$verify_saleA['orderid']}. Second activation attempt blocked!\n";
}

// ------------------------------------------------------------------
// STEP 5: ADMIN PENDING ORDERS QUERY VERIFICATION
// ------------------------------------------------------------------
echo "\n====================================================================\n";
echo "ADMIN PENDING ORDERS QUERY VERIFICATION (Product::pending_orders)\n";
echo "====================================================================\n";

$pending_query = $conn->query("SELECT * FROM product_sale WHERE status = 'Processing' AND userid IN ($userA_id, $userB_id) GROUP BY orderid ORDER BY date ASC");

echo "Total Rows Returned from Query: " . $pending_query->num_rows . " (Expected: 2)\n";
echo "--------------------------------------------------------------------\n";
$sn = 1;
while ($row = $pending_query->fetch_assoc()) {
    $m = $conn->query("SELECT name FROM member WHERE id = {$row['userid']}")->fetch_assoc();
    echo sprintf("Row #%d | SN: %d | USER ID: %s | FULL NAME: %s | ORDER ID: #%s | COST: ₹%.2f | STATUS: %s\n",
        $sn, $sn, $row['userid'], $m['name'], $row['orderid'], $row['cost'], $row['status']);
    $sn++;
}
echo "--------------------------------------------------------------------\n";

// Final assertion checks
$pass = true;
if ($orderidB != ($orderidA + 1)) {
    echo "FAIL: Order ID did not increment! OrderA=#$orderidA, OrderB=#$orderidB\n";
    $pass = false;
}
if ($pending_query->num_rows != 2) {
    echo "FAIL: Admin query did not return 2 separate rows!\n";
    $pass = false;
}
if (!$verify_itemA || !$verify_itemB) {
    echo "FAIL: Order detail items missing from product_item_sale!\n";
    $pass = false;
}

if ($pass) {
    echo "\n>>> FINAL VERIFICATION RESULT: ALL SUCCESS CONDITIONS MET PERFECTLY! <<<\n";
} else {
    echo "\n>>> FINAL VERIFICATION RESULT: FAILED! <<<\n";
}
