<?php
/**
 * Direct Runner to Trigger Direct Sponsor Income and Display Evaluation Log
 * Usage:
 * - Browser: http://localhost/greenamrutayurveda/system/generate_direct_sponsor_log_now.php?userid=MEMBER_ID
 * - CLI: php generate_direct_sponsor_log_now.php MEMBER_ID
 */

define('ENVIRONMENT', 'development');
$_SERVER['CI_ENV']          = 'development';
$_SERVER['REMOTE_ADDR']     = '127.0.0.1';
$_SERVER['REQUEST_METHOD']   = 'GET';
$_SERVER['REQUEST_URI']     = '/cron/debug_direct_sponsor';
$_SERVER['SERVER_PROTOCOL'] = 'HTTP/1.1';
$_SERVER['HTTP_HOST']       = 'localhost';

chdir(__DIR__);
ob_start();
require_once __DIR__ . '/index.php';
$output = ob_get_clean();

$CI =& get_instance();
$CI->load->model('earning');

if (!headers_sent()) {
    header('Content-Type: text/plain; charset=utf-8');
}

echo "=================================================================\n";
echo "TRIGGERING DIRECT SPONSOR INCOME EVALUATION\n";
echo "Date: " . date('Y-m-d H:i:s') . "\n";
echo "=================================================================\n\n";

$target_user = isset($_GET['userid']) ? trim($_GET['userid']) : (isset($argv[1]) ? trim($argv[1]) : '');

if (empty($target_user)) {
    // Pick the most recent active member for demonstration
    $recent = $CI->db->select('id')->where('status', 'Active')->where('topup >', 0)->order_by('id', 'DESC')->limit(1)->get('member')->row();
    $target_user = $recent ? $recent->id : '1001';
}

echo "Evaluating Member ID: {$target_user}...\n\n";

$member = $CI->db_model->select_multi('id, name, sponsor, topup, signup_package, mypv, status', 'member', array('id' => $target_user));
if (!$member) {
    echo "ERROR: Member ID {$target_user} not found in database.\n";
    exit(1);
}

echo "Member Details:\n";
echo "- ID: {$member->id}\n";
echo "- Name: {$member->name}\n";
echo "- Sponsor ID: {$member->sponsor}\n";
echo "- Topup: {$member->topup}\n";
echo "- Signup Package: {$member->signup_package}\n";
echo "- PV: {$member->mypv}\n";
echo "- Status: {$member->status}\n\n";

// Trigger reg_earning
$CI->earning->reg_earning($target_user, $member->sponsor, $member->signup_package, true);

$log_file = __DIR__ . '/application/logs/direct_sponsor_debug.log';
$root_log = dirname(__DIR__) . '/direct_sponsor_debug.log';

echo "Evaluation Complete. Latest log entries:\n";
echo "-----------------------------------------------------------------\n";

if (file_exists($log_file)) {
    $content = file_get_contents($log_file);
    if (strpos($content, "DIRECT SPONSOR DEBUG") !== false) {
        $blocks = explode("DIRECT SPONSOR DEBUG", $content);
        $last_block = end($blocks);
        echo "DIRECT SPONSOR DEBUG" . $last_block;
    } else {
        $blocks = explode("DIRECT SPONSOR EVALUATION", $content);
        $last_block = end($blocks);
        echo "DIRECT SPONSOR EVALUATION" . $last_block;
    }
} else {
    echo "Log file not found at: {$log_file}\n";
}

echo "\n=================================================================\n";
echo "Log File Locations:\n";
echo "1. system/application/logs/direct_sponsor_debug.log (" . (file_exists($log_file) ? filesize($log_file) . " bytes" : "pending") . ")\n";
echo "2. direct_sponsor_debug.log in root (" . (file_exists($root_log) ? filesize($root_log) . " bytes" : "pending") . ")\n";
echo "=================================================================\n";
