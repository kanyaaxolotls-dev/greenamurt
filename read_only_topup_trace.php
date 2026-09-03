<?php
/**
 * READ-ONLY Diagnostic Script to trace member topup state and tree BV
 */
header('Content-Type: text/plain; charset=utf-8');
ini_set('display_errors', 1);
error_reporting(E_ALL);

$db_config_file = __DIR__ . '/system/application/config/database.php';
if (!file_exists($db_config_file)) {
    die("Database config file not found.\n");
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

echo "====================================================================\n";
echo "1. PRODUCTS TABLE (`product`)\n";
echo "====================================================================\n";
$res_prod = $conn->query("SELECT id, prod_name, prod_price, pv, dealer_price, direct_income, matching_income, status FROM product");
if ($res_prod) {
    while ($row = $res_prod->fetch_assoc()) {
        echo sprintf("ID: %d | Name: %s | Price: ₹%.2f | PV: '%s' | Status: %s\n",
            $row['id'], $row['prod_name'], $row['prod_price'], var_export($row['pv'], true), $row['status']);
    }
}

echo "\n====================================================================\n";
echo "2. PRODUCT SALES TABLE (`product_sale`)\n";
echo "====================================================================\n";
$res_sale = $conn->query("SELECT id, product_id, userid, cost, date FROM product_sale ORDER BY id DESC LIMIT 20");
if ($res_sale && $res_sale->num_rows > 0) {
    while ($row = $res_sale->fetch_assoc()) {
        echo sprintf("Sale ID: %d | User ID: %s | Product ID: %s | Cost: ₹%.2f | Date: %s\n",
            $row['id'], $row['userid'], $row['product_id'], $row['cost'], $row['date']);
    }
} else {
    echo "No product_sale records found.\n";
}

echo "\n====================================================================\n";
echo "3. MEMBERS WITH TOPUP > 0 (`member`)\n";
echo "====================================================================\n";
$res_mem = $conn->query("SELECT id, name, signup_package, topup, mypv, total_a_pv, total_b_pv, position, placement_leg, A, B, status FROM member WHERE topup > 0 OR signup_package > 0 ORDER BY id DESC LIMIT 20");
if ($res_mem && $res_mem->num_rows > 0) {
    while ($row = $res_mem->fetch_assoc()) {
        echo sprintf("User ID: %s | Name: %s | Pkg: %s | Topup: ₹%.2f | mypv: '%s' | Left BV: %s | Right BV: %s | Pos: %s | A: %s | B: %s\n",
            $row['id'], $row['name'], $row['signup_package'], $row['topup'], var_export($row['mypv'], true), $row['total_a_pv'], $row['total_b_pv'], $row['position'], $row['A'], $row['B']);
    }
} else {
    echo "No topup members found.\n";
}

echo "\n====================================================================\n";
echo "4. RECENT MEMBERS IN `member` TABLE\n";
echo "====================================================================\n";
$res_recent = $conn->query("SELECT id, name, signup_package, topup, mypv, total_a_pv, total_b_pv, position, A, B FROM member ORDER BY id DESC LIMIT 10");
if ($res_recent) {
    while ($row = $res_recent->fetch_assoc()) {
        echo sprintf("User ID: %s | Name: %s | Pkg: %s | Topup: ₹%.2f | mypv: '%s' | Left BV: %s | Right BV: %s\n",
            $row['id'], $row['name'], $row['signup_package'], $row['topup'], var_export($row['mypv'], true), $row['total_a_pv'], $row['total_b_pv']);
    }
}
