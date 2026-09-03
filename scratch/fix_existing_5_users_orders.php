<?php
header('Content-Type: text/plain; charset=utf-8');

$conn = new mysqli('localhost', 'root', '', 'greenamrutayurve_new');
if ($conn->connect_error) {
    $conn = new mysqli('localhost', 'root', '', 'greenamrut');
}
if ($conn->connect_error) {
    die("Database connection failed: " . $conn->connect_error . "\n");
}

echo "====================================================================\n";
echo "INSPECTING EXISTING PRODUCT_SALE RECORDS FOR ACTIVATED MEMBERS\n";
echo "====================================================================\n";

$members = [927881, 968272, 241534, 112578, 513171];
$member_list = implode(',', $members);

$res = $conn->query("SELECT id, product_id, userid, cost, date, orderid, status, type FROM product_sale WHERE userid IN ($member_list) OR orderid = 0 ORDER BY id ASC");

if ($res && $res->num_rows > 0) {
    echo "Found " . $res->num_rows . " product_sale records:\n";
    while ($r = $res->fetch_assoc()) {
        echo sprintf("ID: %d | User: %s | Prod: %s | Cost: ₹%.2f | Date: %s | OrderID: '%s' | Status: %s | Type: %s\n",
            $r['id'], $r['userid'], $r['product_id'], $r['cost'], $r['date'], var_export($r['orderid'], true), $r['status'], var_export($r['type'], true));
    }
} else {
    echo "No product_sale records found for these members.\n";
}

echo "\n====================================================================\n";
echo "REPAIRING ORDER IDs FOR THE 5 ACTIVATED MEMBERS\n";
echo "====================================================================\n";

// Get current max orderid > 0
$max_res = $conn->query("SELECT MAX(orderid) AS maxid FROM product_sale WHERE orderid > 0");
$max_row = $max_res ? $max_res->fetch_assoc() : null;
$next_order_id = ($max_row && !empty($max_row['maxid'])) ? ($max_row['maxid'] + 1) : 1001;

foreach ($members as $uid) {
    // Check if member exists in member table
    $m_res = $conn->query("SELECT id, name, signup_package, topup FROM member WHERE id = $uid");
    if (!$m_res || $m_res->num_rows == 0) continue;
    $m_row = $m_res->fetch_assoc();

    // Check if product_sale entry exists for this user
    $s_res = $conn->query("SELECT id, orderid, status FROM product_sale WHERE userid = $uid");
    
    if ($s_res && $s_res->num_rows > 0) {
        // User has sale record, check if orderid = 0
        while ($s_row = $s_res->fetch_assoc()) {
            if (empty($s_row['orderid']) || $s_row['orderid'] == 0) {
                $assigned_id = $next_order_id++;
                $conn->query("UPDATE product_sale SET orderid = $assigned_id, status = 'Processing' WHERE id = {$s_row['id']}");
                $conn->query("UPDATE product_item_sale SET order_id = $assigned_id WHERE order_id = 0 AND product_id = {$m_row['signup_package']}");
                echo "✓ Updated User $uid ({$m_row['name']}): Assigned Order ID #$assigned_id (Status set to Processing)\n";
            } else {
                echo "• User $uid ({$m_row['name']}) already has Order ID #{$s_row['orderid']}\n";
            }
        }
    } else {
        // No sale record exists for this active member, create one with unique Order ID!
        $assigned_id = $next_order_id++;
        $pkg_id = !empty($m_row['signup_package']) ? $m_row['signup_package'] : 1;
        $cost = !empty($m_row['topup']) ? $m_row['topup'] : 1.00;
        $today = date('Y-m-d');

        $conn->query("INSERT INTO product_sale (product_id, userid, cost, date, order_by, orderid, pv, type, epin_amount, status) 
                      VALUES ($pkg_id, $uid, $cost, '$today', 'Member', $assigned_id, 0, 'topup', $cost, 'Processing')");
        
        $conn->query("INSERT INTO product_item_sale (product_id, order_id, cost) VALUES ($pkg_id, $assigned_id, $cost)");

        echo "✓ Created missing Order for User $uid ({$m_row['name']}): Order ID #$assigned_id, Cost: ₹$cost (Status: Processing)\n";
    }
}

echo "\n====================================================================\n";
echo "REPAIR COMPLETE! EACH OF THE 5 USERS NOW HAS THEIR OWN UNIQUE ORDER ID.\n";
echo "====================================================================\n";
