<?php
header('Content-Type: text/plain; charset=utf-8');

$conn = new mysqli('localhost', 'root', '', 'greenamrutayurve_new');
if ($conn->connect_error) {
    $conn = new mysqli('localhost', 'root', '', 'greenamrut');
}
if ($conn->connect_error) {
    die("Database connection failed: " . $conn->connect_error);
}

echo "=== FIXING LEGACY ORDERS WITH ORDERID = 0 OR NULL ===\n";

$max_res = $conn->query("SELECT MAX(orderid) AS maxid FROM product_sale WHERE orderid > 0");
$max_row = $max_res ? $max_res->fetch_assoc() : null;
$next_id = ($max_row && !empty($max_row['maxid'])) ? ($max_row['maxid'] + 1) : 1001;

$zero_orders = $conn->query("SELECT id, userid, product_id, cost, date FROM product_sale WHERE orderid = 0 OR orderid IS NULL ORDER BY id ASC");
if ($zero_orders && $zero_orders->num_rows > 0) {
    echo "Found " . $zero_orders->num_rows . " legacy orders with orderid = 0 or NULL.\n";
    while ($row = $zero_orders->fetch_assoc()) {
        $order_pk_id = $row['id'];
        $new_order_id = $next_id++;
        
        $conn->query("UPDATE product_sale SET orderid = $new_order_id WHERE id = $order_pk_id");
        $conn->query("UPDATE product_item_sale SET order_id = $new_order_id WHERE order_id = 0 OR order_id IS NULL");
        
        echo "Updated product_sale ID $order_pk_id -> Order ID #$new_order_id (User: {$row['userid']}, Cost: ₹{$row['cost']})\n";
    }
} else {
    echo "No legacy orders with orderid = 0 or NULL found.\n";
}

echo "\nDone!\n";
