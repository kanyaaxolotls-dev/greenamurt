<?php
/**
 * Update Product PV settings for Greenamrut Ayurveda
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

echo "=== Current Products in Database ===\n";
$res = $conn->query("SELECT id, prod_name, prod_price, pv, dealer_price FROM product");
if ($res) {
    while ($row = $res->fetch_assoc()) {
        echo sprintf("ID: %d | Name: %s | Price: ₹%.2f | PV: %s | Dealer Price: ₹%.2f\n", 
            $row['id'], $row['prod_name'], $row['prod_price'], $row['pv'], $row['dealer_price']);
    }
}

echo "\n=== Updating PV = 1 for ₹8,900 and ₹4,450 packages ===\n";
$update_sql = "UPDATE product SET pv = 1 WHERE prod_price IN (8900, 4450) OR dealer_price IN (8900, 4450)";
if ($conn->query($update_sql)) {
    echo "Successfully updated rows: " . $conn->affected_rows . "\n";
} else {
    echo "Update Error: " . $conn->error . "\n";
}

echo "\n=== Updated Products in Database ===\n";
$res = $conn->query("SELECT id, prod_name, prod_price, pv, dealer_price FROM product");
if ($res) {
    while ($row = $res->fetch_assoc()) {
        echo sprintf("ID: %d | Name: %s | Price: ₹%.2f | PV: %s | Dealer Price: ₹%.2f\n", 
            $row['id'], $row['prod_name'], $row['prod_price'], $row['pv'], $row['dealer_price']);
    }
}
