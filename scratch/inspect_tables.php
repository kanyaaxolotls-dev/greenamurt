<?php
header('Content-Type: text/plain');
$conn = new mysqli('localhost', 'root', '', 'greenamrutayurve_new');
if ($conn->connect_error) {
    // Try alternate DB name if needed
    $conn = new mysqli('localhost', 'root', '', 'greenamrut');
}
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

echo "=== PRODUCT_SALE SCHEMA ===\n";
$res = $conn->query("DESCRIBE product_sale");
if ($res) {
    while ($r = $res->fetch_assoc()) {
        echo $r['Field'] . " | " . $r['Type'] . " | " . $r['Null'] . " | " . $r['Key'] . " | " . var_export($r['Default'], true) . "\n";
    }
} else {
    echo "Error describing product_sale: " . $conn->error . "\n";
}

echo "\n=== PRODUCT_ITEM_SALE SCHEMA ===\n";
$res2 = $conn->query("DESCRIBE product_item_sale");
if ($res2) {
    while ($r = $res2->fetch_assoc()) {
        echo $r['Field'] . " | " . $r['Type'] . " | " . $r['Null'] . " | " . $r['Key'] . " | " . var_export($r['Default'], true) . "\n";
    }
} else {
    echo "Error describing product_item_sale: " . $conn->error . "\n";
}

echo "\n=== RECENT ORDERS IN PRODUCT_SALE ===\n";
$res3 = $conn->query("SELECT * FROM product_sale ORDER BY id DESC LIMIT 5");
if ($res3 && $res3->num_rows > 0) {
    while ($r = $res3->fetch_assoc()) {
        print_r($r);
    }
} else {
    echo "No product_sale records found.\n";
}
