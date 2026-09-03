<?php
header('Content-Type: text/plain; charset=utf-8');

$conn = new mysqli('localhost', 'root', '', 'greenamrutayurve_new');
if ($conn->connect_error) {
    $conn = new mysqli('localhost', 'root', '', 'greenamrut');
}
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

echo "=== PRODUCT_SALE RECORDS ===\n";
$res = $conn->query("SELECT id, product_id, userid, cost, date, orderid, status FROM product_sale ORDER BY id DESC LIMIT 20");
if ($res && $res->num_rows > 0) {
    while ($r = $res->fetch_assoc()) {
        echo sprintf("ID: %d | User: %s | Prod: %s | Cost: ₹%.2f | Date: %s | OrderID: '%s' | Status: %s\n",
            $r['id'], $r['userid'], $r['product_id'], $r['cost'], $r['date'], var_export($r['orderid'], true), $r['status']);
    }
} else {
    echo "No product_sale records found.\n";
}

echo "\n=== COUNT BY ORDERID ===\n";
$res2 = $conn->query("SELECT orderid, COUNT(*) as cnt FROM product_sale GROUP BY orderid");
if ($res2) {
    while ($r = $res2->fetch_assoc()) {
        echo sprintf("OrderID: '%s' -> %d records\n", var_export($r['orderid'], true), $r['cnt']);
    }
}
