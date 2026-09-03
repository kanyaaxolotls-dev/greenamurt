<?php
header('Content-Type: text/plain; charset=utf-8');

define('BASEPATH', __DIR__);
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

if ($db_config_file) {
    include($db_config_file);
    $db_config = isset($db['default']) ? $db['default'] : null;
}

$host = !empty($db_config['hostname']) ? $db_config['hostname'] : 'localhost';
$user = !empty($db_config['username']) ? $db_config['username'] : 'root';
$pass = isset($db_config['password']) ? $db_config['password'] : '';
$dbname = !empty($db_config['database']) ? $db_config['database'] : 'greenamrutayurve_new';

$conn = null;
try {
    $conn = new mysqli($host, $user, $pass, $dbname);
} catch (Exception $e) {
    try {
        $conn = new mysqli('localhost', 'root', '', 'greenamrutayurve_new');
    } catch (Exception $e2) {
        die("Connection failed: " . $e2->getMessage() . "\n");
    }
}
if ($conn->connect_error) {
    die("Database connection failed: " . $conn->connect_error . "\n");
}

echo "=== CURRENT PRODUCT_SALE TABLE IN GREENAMRUTAYURVEDA ===\n";
$res = $conn->query("SELECT * FROM product_sale ORDER BY id ASC");
if ($res && $res->num_rows > 0) {
    while ($r = $res->fetch_assoc()) {
        echo sprintf("ID: %d | UserID: %s | OrderID: %s | Cost: ₹%.2f | Date: %s | Status: %s | Type: %s\n",
            $r['id'], $r['userid'], var_export($r['orderid'], true), $r['cost'], $r['date'], $r['status'], var_export($r['type'], true));
    }
} else {
    echo "No product_sale records found in database!\n";
}

echo "\n=== CURRENT MEMBERS WITH TOPUP > 0 IN MEMBER TABLE ===\n";
$res_m = $conn->query("SELECT id, name, topup, signup_package, status, activation_date FROM member WHERE topup > 0 OR status = 'Active' ORDER BY id ASC");
if ($res_m && $res_m->num_rows > 0) {
    while ($r = $res_m->fetch_assoc()) {
        echo sprintf("User ID: %s | Name: %s | Topup: ₹%.2f | Pkg: %s | Status: %s | ActDate: %s\n",
            $r['id'], $r['name'], $r['topup'], $r['signup_package'], $r['status'], $r['activation_date']);
    }
} else {
    echo "No active members found.\n";
}
