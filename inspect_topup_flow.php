<?php
/**
 * READ-ONLY Diagnostic Script to search for topup and package logic across system/application
 */
header('Content-Type: text/plain; charset=utf-8');

function search_in_dir($dir, $patterns) {
    $iterator = new RecursiveIteratorIterator(new RecursiveDirectoryIterator($dir));
    foreach ($iterator as $file) {
        if ($file->isDir()) continue;
        if ($file->getExtension() !== 'php') continue;
        
        $content = file_get_contents($file->getPathname());
        $lines = explode("\n", $content);
        
        foreach ($lines as $line_num => $line) {
            foreach ($patterns as $pattern) {
                if (stripos($line, $pattern) !== false) {
                    $rel_path = str_replace(__DIR__, '', $file->getPathname());
                    echo sprintf("[%s:%d] Matches '%s': %s\n", $rel_path, $line_num + 1, $pattern, trim($line));
                }
            }
        }
    }
}

$search_dir = __DIR__ . '/system/application';
echo "=== Searching for 'mypv', 'topup', 'update_legs', 'signup_package' in system/application ===\n\n";

search_in_dir($search_dir, ['mypv', 'update_legs', 'signup_package', 'join_package']);
