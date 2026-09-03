<?php
/**
 * Concrete Payout Verification & SwarangWellness Alignment Test Runner
 *
 * This script runs the complete test scenario:
 * 1. Member A = sponsor
 * 2. Member B = A's first direct join -> Activate B
 * 3. Member C = B's first direct join (A's 2nd level) -> Activate C
 * 4. Member D = A's Right direct join -> Forms 2:1 binary pair
 * 5. Cron payout / wallet settlement execution #1 and #2 (Idempotency)
 * 6. Zero Booster verification
 */

// Define CLI / Web environment for CodeIgniter
define('ENVIRONMENT', 'development');
$_SERVER['CI_ENV'] = 'development';
$_SERVER['REMOTE_ADDR'] = '127.0.0.1';
$_SERVER['REQUEST_METHOD'] = 'GET';
$_SERVER['REQUEST_URI'] = '/test/verify_concrete_swarang_alignment';
$_SERVER['SERVER_PROTOCOL'] = 'HTTP/1.1';
$_SERVER['HTTP_HOST'] = 'localhost';

// Change working directory to system and load front controller
chdir(__DIR__ . '/system');

// Capture output
ob_start();
require_once __DIR__ . '/system/index.php';
$output = ob_get_clean();

// Check if CI already executed the controller or if we need to call it directly
if (strpos($output, 'CONCRETE PAYOUT VERIFICATION') !== false) {
    echo $output;
} else {
    // If router served default route, invoke Test controller directly
    $CI =& get_instance();
    require_once __DIR__ . '/system/application/controllers/Test.php';
    $test_ctrl = new Test();
    $test_ctrl->verify_concrete_swarang_alignment();
}
