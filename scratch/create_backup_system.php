<?php
/**
 * Standalone Script to Create Backup of Earning.php
 */
header('Content-Type: text/plain; charset=utf-8');

$source = __DIR__ . '/../system/application/models/Earning.php';
$dest   = __DIR__ . '/../system/application/models/Earning.php.bak';

if (!file_exists($source)) {
    die("Source file does not exist: " . $source . "\n");
}

$copied = copy($source, $dest);
if ($copied && file_exists($dest)) {
    echo "Backup created successfully at: " . realpath($dest) . "\n";
    echo "Size: " . filesize($dest) . " bytes\n";
} else {
    echo "Backup failed!\n";
}
