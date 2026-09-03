<?php
/**
 * Database Backup Script (Pre BV Fix)
 */
header('Content-Type: text/plain; charset=utf-8');

$db_config_file = __DIR__ . '/../system/application/config/database.php';
if (!file_exists($db_config_file)) {
    $db_config_file = __DIR__ . '/system/application/config/database.php';
}
define('BASEPATH', __DIR__ . '/../system/');
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

$tables = ['product', 'member', 'product_sale', 'epin', 'earning'];
$backup_file = __DIR__ . '/db_backup_pre_bv_fix.sql';
$fp = fopen($backup_file, 'w');

fwrite($fp, "-- Database Backup (Pre BV Fix)\n-- Date: " . date('Y-m-d H:i:s') . "\n\n");

foreach ($tables as $table) {
    fwrite($fp, "-- Table: $table --\n");
    $res = $conn->query("SELECT * FROM $table");
    if ($res) {
        while ($row = $res->fetch_assoc()) {
            $keys = array_keys($row);
            $values = array_values($row);
            $escaped_vals = array_map(function($v) use ($conn) {
                if ($v === null) return 'NULL';
                return "'" . $conn->real_escape_string($v) . "'";
            }, $values);
            $sql = "INSERT INTO $table (" . implode(', ', $keys) . ") VALUES (" . implode(', ', $escaped_vals) . ");\n";
            fwrite($fp, $sql);
        }
    }
    fwrite($fp, "\n");
}
fclose($fp);

echo "Database backup successfully saved to: " . $backup_file . "\n";
