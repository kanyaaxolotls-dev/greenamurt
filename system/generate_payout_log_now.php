<?php
/**
 * Direct Runner to Trigger Binary Payout and Generate Log for User 329389
 */

define('ENVIRONMENT', 'development');
$_SERVER['CI_ENV']        = 'development';
$_SERVER['REMOTE_ADDR']   = '127.0.0.1';
$_SERVER['REQUEST_METHOD'] = 'GET';
$_SERVER['REQUEST_URI']   = '/cron/debug_payout/329389';
$_SERVER['SERVER_PROTOCOL'] = 'HTTP/1.1';
$_SERVER['HTTP_HOST']     = 'localhost';

chdir(__DIR__);
ob_start();
require_once __DIR__ . '/index.php';
$output = ob_get_clean();

$CI =& get_instance();
$CI->load->model('earning');

header('Content-Type: text/plain; charset=utf-8');
echo "=================================================================\n";
echo "TRIGGERING BINARY PAYOUT EVALUATION NOW\n";
echo "Date: " . date('Y-m-d H:i:s') . "\n";
echo "=================================================================\n\n";

$target_user = isset($_GET['userid']) ? $_GET['userid'] : '329389';
echo "Running process_binary for Member ID: {$target_user}...\n\n";

$log = $CI->earning->process_binary($target_user, array(), true);

if (is_array($log)) {
    foreach ($log as $line) {
        echo $line . "\n";
    }
} else {
    echo "Result: " . var_export($log, true) . "\n";
}

echo "\n=================================================================\n";
echo "Log has been written to:\n";
echo "1. system/application/logs/payout_debug.log\n";
echo "2. payout_debug.log (in project root)\n";
echo "=================================================================\n";
