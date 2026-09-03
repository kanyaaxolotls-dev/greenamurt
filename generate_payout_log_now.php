<?php
/**
 * Direct Runner to Trigger Binary Payout and Generate Log
 * Usage:
 * - Browser: http://localhost/greenamrutayurveda/generate_payout_log_now.php?userid=329389
 * - CLI: php generate_payout_log_now.php
 */

define('ENVIRONMENT', 'development');
$_SERVER['CI_ENV']          = 'development';
$_SERVER['REMOTE_ADDR']     = '127.0.0.1';
$_SERVER['REQUEST_METHOD']   = 'GET';
$_SERVER['REQUEST_URI']     = '/cron/debug_payout/329389';
$_SERVER['SERVER_PROTOCOL'] = 'HTTP/1.1';
$_SERVER['HTTP_HOST']       = 'localhost';

chdir(__DIR__ . '/system');
ob_start();
require_once __DIR__ . '/system/index.php';
$output = ob_get_clean();

$CI =& get_instance();
$CI->load->model('earning');

if (!headers_sent()) {
    header('Content-Type: text/plain; charset=utf-8');
}

echo "=================================================================\n";
echo "TRIGGERING BINARY PAYOUT EVALUATION\n";
echo "Date: " . date('Y-m-d H:i:s') . "\n";
echo "=================================================================\n\n";

$target_user = isset($_GET['userid']) ? $_GET['userid'] : (isset($argv[1]) ? $argv[1] : '329389');

echo "Processing process_binary for Member ID: {$target_user}...\n\n";
$log = $CI->earning->process_binary($target_user, array(), true);

if (is_array($log)) {
    foreach ($log as $line) {
        echo $line . "\n";
    }
} else {
    echo "Result: " . var_export($log, true) . "\n";
}

$log_file = __DIR__ . '/system/application/logs/payout_debug.log';
echo "\n=================================================================\n";
echo "Log File Status: " . (file_exists($log_file) ? "EXISTS (" . filesize($log_file) . " bytes)" : "NOT FOUND") . "\n";
echo "Path: {$log_file}\n";
echo "=================================================================\n";
