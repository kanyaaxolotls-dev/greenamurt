<?php
/**
 * Create Backup of Earning.php
 */
header('Content-Type: text/plain; charset=utf-8');

$source = __DIR__ . '/system/application/models/Earning.php';
$dest   = __DIR__ . '/scratch/Earning.php.bak';

if (!file_exists($source)) {
    die("Source file does not exist: " . $source . "\n");
}

$copied = copy($source, $dest);
if ($copied && file_exists($dest)) {
    $src_size = filesize($source);
    $dst_size = filesize($dest);
    echo "Backup successful!\n";
    echo "Source size: " . $src_size . " bytes\n";
    echo "Backup size: " . $dst_size . " bytes\n";
    if ($src_size === $dst_size) {
        echo "VERIFIED: Backup is complete and identical in size.\n";
    } else {
        echo "WARNING: Size mismatch!\n";
    }
} else {
    echo "ERROR: Backup failed!\n";
}
