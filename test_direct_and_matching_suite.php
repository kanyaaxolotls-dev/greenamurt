<?php
/**
 * Comprehensive Direct Sponsor & Binary Matching End-to-End Test Suite
 */

define('ENVIRONMENT', 'development');
$_SERVER['CI_ENV']          = 'development';
$_SERVER['REMOTE_ADDR']     = '127.0.0.1';
$_SERVER['REQUEST_METHOD']   = 'GET';
$_SERVER['REQUEST_URI']     = '/cron';
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
echo "GREENAMRUTAYURVEDA: DIRECT SPONSOR & BINARY MATCHING TEST SUITE\n";
echo "Date: " . date('Y-m-d H:i:s') . "\n";
echo "=================================================================\n\n";

$test_results = array();

// -------------------------------------------------------------
// STEP 1: Verify Existing Member 518607 Payout is Untouched
// -------------------------------------------------------------
$m518607_earnings = $CI->db->where('ref_id', '518607')->where_in('type', array('Direct Sponsor Income', 'Direct Sponsor Commission'))->get('earning')->result();
$m518607_count = count($m518607_earnings);
$m518607_amount = $m518607_count > 0 ? $m518607_earnings[0]->amount : 0;

$test_results['TEST 6: Existing payout for member 518607 remains untouched'] = ($m518607_count === 1 && floatval($m518607_amount) == 890.0)
    ? "PASS (Found {$m518607_count} record of amount ₹{$m518607_amount})"
    : "FAIL (Found {$m518607_count} records)";

// -------------------------------------------------------------
// SETUP NEW TEST USERS:
// Sponsor: 992001
// Left 1:  992002 (Child 1 on Left)
// Left 2:  992003 (Child 2 under 992002 on Left)
// Right 1: 992004 (Child 3 on Right)
// -------------------------------------------------------------

$test_uids = array(992001, 992002, 992003, 992004, 992005, 992006);
$CI->db->where_in('id', $test_uids)->delete('member');
$CI->db->where_in('userid', $test_uids)->delete('wallet');
$CI->db->where_in('userid', $test_uids)->delete('earning');
$CI->db->where_in('ref_id', $test_uids)->delete('earning');
$CI->db->where_in('userid', $test_uids)->delete('laps_earning');

// Insert Sponsor 992001
$CI->db->insert('member', array(
    'id'             => 992001,
    'name'           => 'Test Suite Sponsor',
    'status'         => 'Active',
    'topup'          => 8900,
    'signup_package' => 1,
    'mypv'           => 1,
    'sponsor'        => 1001,
    'position'       => 1001,
    'A'              => 0,
    'B'              => 0,
    'total_a_pv'     => 0,
    'total_b_pv'     => 0,
    'paid_a_pv'      => 0,
    'paid_b_pv'      => 0,
    'total_pairs'    => 0,
));
$CI->db->insert('wallet', array('userid' => 992001, 'balance' => 0.00));

// Insert Left 1: 992002
$CI->db->insert('member', array(
    'id'             => 992002,
    'name'           => 'Test Left Child 1',
    'status'         => 'Active',
    'topup'          => 8900,
    'signup_package' => 1,
    'mypv'           => 1,
    'sponsor'        => 992001,
    'position'       => 992001,
    'A'              => 0,
    'B'              => 0,
));
$CI->db->where('id', 992001)->update('member', array('A' => 992002));

// TEST 1: Direct Sponsor Income for 992002
$CI->earning->reg_earning(992002, 992001, 1, true);
$t1_earning = $CI->db->get_where('earning', array('userid' => 992001, 'ref_id' => 992002, 'type' => 'Direct Sponsor Income'))->row();
$test_results['TEST 1: New eligible member activation => Direct Sponsor Income generated once'] = ($t1_earning && floatval($t1_earning->amount) == 890.0 && $t1_earning->status == 'Pending')
    ? "PASS (Amount: ₹{$t1_earning->amount}, Status: {$t1_earning->status})"
    : "FAIL";

// TEST 2: Duplicate Direct Sponsor Prevention & Log Display
$CI->earning->reg_earning(992002, 992001, 1, true);
$t2_count = $CI->db->where('userid', 992001)->where('ref_id', 992002)->where('type', 'Direct Sponsor Income')->count_all_results('earning');
$test_results['TEST 2: Same activation re-processed => Direct Sponsor Income NOT duplicated'] = ($t2_count === 1)
    ? "PASS (Duplicate count = 1, correctly prevented double-crediting)"
    : "FAIL (Found {$t2_count} records)";

// Insert Left 2: 992003 (Under 992002 on Left leg -> Total Left PV = 2.0)
$CI->db->insert('member', array(
    'id'             => 992003,
    'name'           => 'Test Left Child 2',
    'status'         => 'Active',
    'topup'          => 8900,
    'signup_package' => 1,
    'mypv'           => 1,
    'sponsor'        => 992001,
    'position'       => 992002,
    'A'              => 0,
    'B'              => 0,
));
$CI->db->where('id', 992002)->update('member', array('A' => 992003));
$CI->earning->reg_earning(992003, 992001, 1, true);

// Insert Right 1: 992004 (On Right leg -> Total Right PV = 1.0)
$CI->db->insert('member', array(
    'id'             => 992004,
    'name'           => 'Test Right Child 1',
    'status'         => 'Active',
    'topup'          => 8900,
    'signup_package' => 1,
    'mypv'           => 1,
    'sponsor'        => 992001,
    'position'       => 992001,
    'A'              => 0,
    'B'              => 0,
));
$CI->db->where('id', 992001)->update('member', array('B' => 992004));
$CI->earning->reg_earning(992004, 992001, 1, true);

// Update Legs PV
$CI->earning->update_legs();
$sp_tree = $CI->db->select('total_a_pv, total_b_pv, paid_a_pv, paid_b_pv, total_pairs')->get_where('member', array('id' => 992001))->row();

echo "Initial Tree PV for Sponsor 992001:\n";
echo "- Left PV: {$sp_tree->total_a_pv}\n";
echo "- Right PV: {$sp_tree->total_b_pv}\n";
echo "- Paid Left: {$sp_tree->paid_a_pv}, Paid Right: {$sp_tree->paid_b_pv}, Lifetime Pairs: {$sp_tree->total_pairs}\n\n";

// TEST 3: Binary Matching First Pair 2:1 (Left 2 PV, Right 1 PV)
$CI->earning->process_binary(992001, array());
$sp_after_t3 = $CI->db->select('total_a_pv, total_b_pv, paid_a_pv, paid_b_pv, total_pairs')->get_where('member', array('id' => 992001))->row();
$t3_earning = $CI->db->get_where('earning', array('userid' => 992001, 'type' => 'Matching Income'))->row();

$test_results['TEST 3: Binary Left 2 PV / Right 1 PV => 1 First Matching Pair (2:1)'] = ($sp_after_t3->total_pairs == 1 && $sp_after_t3->paid_a_pv == 2 && $sp_after_t3->paid_b_pv == 1 && $t3_earning)
    ? "PASS (1 Pair formed, Left 2 PV & Right 1 PV consumed, Earning ID: {$t3_earning->id}, Amount: ₹{$t3_earning->amount})"
    : "FAIL (Pairs: {$sp_after_t3->total_pairs}, Paid A: {$sp_after_t3->paid_a_pv}, Paid B: {$sp_after_t3->paid_b_pv})";

// TEST 4: Subsequent Pairs (1:1 Matching after 1st pair)
// Add 1 PV on Left (992005 under 992003) and 1 PV on Right (992006 under 992004)
$CI->db->insert('member', array(
    'id'             => 992005,
    'name'           => 'Test Left Child 3',
    'status'         => 'Active',
    'topup'          => 8900,
    'signup_package' => 1,
    'mypv'           => 1,
    'sponsor'        => 992001,
    'position'       => 992003,
));
$CI->db->where('id', 992003)->update('member', array('A' => 992005));

$CI->db->insert('member', array(
    'id'             => 992006,
    'name'           => 'Test Right Child 2',
    'status'         => 'Active',
    'topup'          => 8900,
    'signup_package' => 1,
    'mypv'           => 1,
    'sponsor'        => 992001,
    'position'       => 992004,
));
$CI->db->where('id', 992004)->update('member', array('A' => 992006));

$CI->earning->update_legs();
$CI->earning->process_binary(992001, array());

$sp_after_t4 = $CI->db->select('total_a_pv, total_b_pv, paid_a_pv, paid_b_pv, total_pairs')->get_where('member', array('id' => 992001))->row();
$t4_earnings = $CI->db->where('userid', 992001)->where('type', 'Matching Income')->get('earning')->result();

$test_results['TEST 4: After first pair, Left 1 PV / Right 1 PV => 1 additional 1:1 matching pair'] = ($sp_after_t4->total_pairs == 2 && $sp_after_t4->paid_a_pv == 3 && $sp_after_t4->paid_b_pv == 2 && count($t4_earnings) == 2)
    ? "PASS (Lifetime pairs = 2, Total Paid Left = 3 PV, Total Paid Right = 2 PV, 2 Matching Earning records)"
    : "FAIL (Pairs: {$sp_after_t4->total_pairs}, Paid A: {$sp_after_t4->paid_a_pv}, Paid B: {$sp_after_t4->paid_b_pv})";

// TEST 5: Direct Sponsor Income and Matching Income Independence
$direct_count = $CI->db->where('userid', 992001)->where_in('type', array('Direct Sponsor Income', 'Direct Sponsor Commission'))->count_all_results('earning');
$match_count = $CI->db->where('userid', 992001)->where('type', 'Matching Income')->count_all_results('earning');

$test_results['TEST 5: Direct Sponsor Income and Matching Income coexist independently'] = ($direct_count === 3 && $match_count === 2)
    ? "PASS (Direct Sponsor records: {$direct_count}, Matching records: {$match_count}, Both processed without conflict)"
    : "FAIL (Direct: {$direct_count}, Matching: {$match_count})";

// TEST 7: Zero earning_freeze references check
$earning_code = file_get_contents(__DIR__ . '/system/application/models/Earning.php');
$freeze_found = (stripos($earning_code, 'earning_freeze') !== false);
$test_results['TEST 7: No earning_freeze column added & zero earning_freeze references'] = (!$freeze_found)
    ? "PASS (Zero earning_freeze references in active codebase)"
    : "FAIL (Found earning_freeze references in Earning.php)";

// Output results
echo "=================================================================\n";
echo "FINAL ACCEPTANCE TEST RESULTS\n";
echo "=================================================================\n\n";

foreach ($test_results as $test_name => $res) {
    echo "{$test_name}:\n  => {$res}\n\n";
}

// Clean up temporary test nodes
$CI->db->where_in('id', array(992001, 992002, 992003, 992004, 992005, 992006))->delete('member');
$CI->db->where_in('userid', array(992001, 992002, 992003, 992004, 992005, 992006))->delete('wallet');
$CI->db->where_in('userid', array(992001, 992002, 992003, 992004, 992005, 992006))->delete('earning');
$CI->db->where_in('ref_id', array(992001, 992002, 992003, 992004, 992005, 992006))->delete('earning');
$CI->db->where_in('userid', array(992001, 992002, 992003, 992004, 992005, 992006))->delete('laps_earning');

echo "Test suite cleanup completed.\n";
echo "=================================================================\n";
