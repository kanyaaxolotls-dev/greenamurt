<?php
/**
 * Diagnostic Script for User 888001 (Robust DB Config Resolution)
 */
header('Content-Type: text/html; charset=utf-8');

// Define CodeIgniter constants required by config files
if (!defined('BASEPATH')) {
    define('BASEPATH', __DIR__ . '/../system/');
}
if (!defined('ENVIRONMENT')) {
    define('ENVIRONMENT', 'development');
}

// Robust resolution for database.php
$possible_paths = array(
    __DIR__ . '/../system/application/config/database.php',
    __DIR__ . '/system/application/config/database.php',
    'c:/xampp/htdocs/greenspe/system/application/config/database.php',
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
    die("<b style='color:red;'>Database config file not found in any standard path.</b>");
}

include($db_config_file);

$db_config = isset($db['default']) ? $db['default'] : array();
$host     = !empty($db_config['hostname']) ? $db_config['hostname'] : 'localhost';
$user     = isset($db_config['username']) ? $db_config['username'] : 'root';
$pass     = isset($db_config['password']) ? $db_config['password'] : '';
$dbname   = isset($db_config['database']) ? $db_config['database'] : 'greenamrutayurve_new';

$conn = null;
try {
    $conn = new mysqli($host, $user, $pass, $dbname);
} catch (Exception $e) {
    try {
        $conn = new mysqli('localhost', 'root', '', $dbname);
    } catch (Exception $e2) {
        die("Database Connection Failed: " . $e2->getMessage());
    }
}

if ($conn->connect_error) {
    try {
        $conn = new mysqli('localhost', 'root', '', $dbname);
    } catch (Exception $e3) {
        die("Database Connection Error: " . $conn->connect_error);
    }
}

echo "<h2>Diagnostic Report for User 888001</h2>";

echo "<h3>1. Member Record (`member` Table)</h3>";
$res = $conn->query("SELECT id, username, name, signup_package, topup, total_a, total_b, total_a_pv, total_b_pv, paid_a_pv, paid_b_pv, self_power_a, self_power_b, total_pairs FROM member WHERE id = '888001' OR username = '888001' OR name LIKE '%Test Parent%'");
if ($res && $res->num_rows > 0) {
    echo "<table border='1' cellpadding='6' style='border-collapse:collapse;'>";
    echo "<tr style='background:#f2f2f2;'><th>ID</th><th>Username</th><th>Name</th><th>Package ID</th><th>Topup</th><th>Left Team</th><th>Right Team</th><th>Left PV</th><th>Right PV</th><th>Paid Left PV</th><th>Paid Right PV</th><th>Total Pairs</th></tr>";
    while ($row = $res->fetch_assoc()) {
        echo "<tr>";
        echo "<td>{$row['id']}</td>";
        echo "<td>{$row['username']}</td>";
        echo "<td>" . htmlspecialchars($row['name']) . "</td>";
        echo "<td>{$row['signup_package']}</td>";
        echo "<td>₹{$row['topup']}</td>";
        echo "<td>{$row['total_a']}</td>";
        echo "<td>{$row['total_b']}</td>";
        echo "<td>{$row['total_a_pv']}</td>";
        echo "<td>{$row['total_b_pv']}</td>";
        echo "<td>{$row['paid_a_pv']}</td>";
        echo "<td>{$row['paid_b_pv']}</td>";
        echo "<td>{$row['total_pairs']}</td>";
        echo "</tr>";
    }
    echo "</table>";
} else {
    echo "<p style='color:orange;'>User 888001 / Test Parent not found in database.</p>";
}

echo "<h3>2. Downline Accounts</h3>";
$res_down = $conn->query("SELECT id, username, name, position, placement, signup_package, topup, mypv FROM member WHERE position = '888001' OR sponsor = '888001'");
if ($res_down && $res_down->num_rows > 0) {
    echo "<table border='1' cellpadding='6' style='border-collapse:collapse;'>";
    echo "<tr style='background:#f2f2f2;'><th>ID</th><th>Username</th><th>Name</th><th>Position</th><th>Placement</th><th>Package ID</th><th>Topup</th><th>mypv</th></tr>";
    while ($row = $res_down->fetch_assoc()) {
        echo "<tr>";
        echo "<td>{$row['id']}</td>";
        echo "<td>{$row['username']}</td>";
        echo "<td>" . htmlspecialchars($row['name']) . "</td>";
        echo "<td>{$row['position']}</td>";
        echo "<td>{$row['placement']}</td>";
        echo "<td>{$row['signup_package']}</td>";
        echo "<td>₹{$row['topup']}</td>";
        echo "<td>{$row['mypv']}</td>";
        echo "</tr>";
    }
    echo "</table>";
} else {
    echo "<p>No downline accounts found for User 888001.</p>";
}

echo "<h3>3. Earnings Records (`earning` Table)</h3>";
$res_e = $conn->query("SELECT * FROM earning WHERE userid = '888001'");
if ($res_e && $res_e->num_rows > 0) {
    echo "<table border='1' cellpadding='6' style='border-collapse:collapse;'>";
    echo "<tr style='background:#f2f2f2;'><th>ID</th><th>User ID</th><th>Type</th><th>Amount</th><th>Ref ID</th><th>Pair Match</th><th>Date</th></tr>";
    while ($row = $res_e->fetch_assoc()) {
        echo "<tr>";
        echo "<td>{$row['id']}</td>";
        echo "<td>{$row['userid']}</td>";
        echo "<td>" . htmlspecialchars($row['type']) . "</td>";
        echo "<td>₹{$row['amount']}</td>";
        echo "<td>{$row['ref_id']}</td>";
        echo "<td>{$row['pair_match']}</td>";
        echo "<td>{$row['date']}</td>";
        echo "</tr>";
    }
    echo "</table>";
} else {
    echo "<p style='color:orange;'>No earnings recorded in `earning` table for User 888001.</p>";
}
