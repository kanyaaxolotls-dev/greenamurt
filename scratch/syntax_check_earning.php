<?php
/**
 * PHP Syntax Validation Script for Earning.php
 */
header('Content-Type: text/plain; charset=utf-8');

$file = __DIR__ . '/system/application/models/Earning.php';

if (!file_exists($file)) {
    die("File not found: " . $file . "\n");
}

$code = file_get_contents($file);

// Check using token_get_all and php lint if possible
$tokens = token_get_all($code);
$has_errors = false;

// Basic syntax check: try parsing with php lint or include evaluation in dry mode
exec("php -l " . escapeshellarg($file) . " 2>&1", $output, $return_var);

if ($return_var === 0) {
    echo "PHP SYNTAX CHECK PASSED!\n";
    echo implode("\n", $output) . "\n";
} else {
    echo "PHP SYNTAX ERROR DETECTED!\n";
    echo implode("\n", $output) . "\n";
}
