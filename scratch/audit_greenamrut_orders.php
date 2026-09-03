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
echo "1. MEMBERS IN DATABASE (`member`)\n";
echo "====================================================================\n";
$res_m = $conn->query("SELECT id, name, topup, signup_package, status, activation_date, join_time FROM member ORDER BY id DESC LIMIT 15");
if ($res_m) {
    while ($r = $res_m->fetch_assoc()) {
        echo sprintf("User ID: %s | Name: %s | Topup: ₹%.2f | Pkg: %s | Status: %s | ActDate: %s | JoinDate: %s\n",
            $r['id'], $r['name'], $r['topup'], $r['signup_package'], $r['status'], $r['activation_date'], $r['join_time']);
    }
}

echo "\n====================================================================\n";
echo "2. ORDERS IN DATABASE (`product_sale`)\n";
echo "====================================================================\n";
$res_s = $conn->query("SELECT id, product_id, userid, cost, date, orderid, status, type, order_by FROM product_sale ORDER BY id ASC");
if ($res_s && $res_s->num_rows > 0) {
    while ($r = $res_s->fetch_assoc()) {
        echo sprintf("Row ID: %d | User ID: %s | OrderID: '%s' | ProdID: %s | Cost: ₹%.2f | Date: %s | Status: %s | Type: %s | OrderBy: %s\n",
            $r['id'], $r['userid'], var_export($r['orderid'], true), $r['product_id'], $r['cost'], $r['date'], $r['status'], var_export($r['type'], true), var_export($r['order_by'], true));
    }
} else {
    echo "No records in product_sale.\n";
}

echo "\n====================================================================\n";
echo "3. ORDER DETAILS IN DATABASE (`product_item_sale`)\n";
echo "====================================================================\n";
$res_i = $conn->query("SELECT id, product_id, order_id, cost FROM product_item_sale ORDER BY id ASC");
if ($res_i && $res_i->num_rows > 0) {
    while ($r = $res_i->fetch_assoc()) {
        echo sprintf("Row ID: %d | ProductID: %s | OrderID: '%s' | Cost: ₹%.2f\n",
            $r['id'], $r['product_id'], var_export($r['order_id'], true), $r['cost']);
    }
} else {
    echo "No records in product_item_sale.\n";
}

echo "\n====================================================================\n";
echo "4. ADMIN PENDING ORDERS QUERY EMULATION\n";
echo "====================================================================\n";
// Emulate: SELECT * FROM product_sale WHERE status = 'Processing' GROUP BY orderid ORDER BY date ASC
$res_p = $conn->query("SELECT * FROM product_sale WHERE status = 'Processing' GROUP BY orderid ORDER BY date ASC");
if ($res_p && $res_p->num_rows > 0) {
    echo "Query returned " . $res_p->num_rows . " row(s):\n";
    while ($r = $res_p->fetch_assoc()) {
        echo sprintf("OrderID: '%s' | UserID: %s | Cost: ₹%.2f | Date: %s | Status: %s\n",
            var_export($r['orderid'], true), $r['userid'], $r['cost'], $r['date'], $r['status']);
    }
} else {
    echo "Query returned 0 rows.\n";
}
