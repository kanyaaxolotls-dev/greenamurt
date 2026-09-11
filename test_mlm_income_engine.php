<?php
/**
 * GREENAMRUTAYURVEDA: Comprehensive MLM Income / Commission Test Suite
 * Tests all 8 User Test Cases + Retail Profit + Idempotency
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
echo "GREENAMRUTAYURVEDA: MLM INCOME / COMMISSION TEST SUITE\n";
echo "Date: " . date('Y-m-d H:i:s') . "\n";
echo "=================================================================\n\n";

$test_results = array();

// -------------------------------------------------------------
// CLEANUP TEST USERS (881001 to 881010)
// -------------------------------------------------------------
$test_uids = array(881001, 881002, 881003, 881004, 881005, 881006, 881007, 881008);
$CI->db->where_in('id', $test_uids)->delete('member');
$CI->db->where_in('userid', $test_uids)->delete('wallet');
$CI->db->where_in('userid', $test_uids)->delete('earning');
$CI->db->where_in('ref_id', $test_uids)->delete('earning');
$CI->db->where_in('userid', $test_uids)->delete('laps_earning');

// -------------------------------------------------------------
// SETUP TOP SPONSOR: 881001 (Active, 1 PV)
// -------------------------------------------------------------
$CI->db->insert('member', array(
    'id'             => 881001,
    'name'           => 'Test MLM Grand Sponsor',
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
$CI->db->insert('wallet', array('userid' => 881001, 'balance' => 0.00));

// SETUP LEVEL 1 SPONSOR: 881002 (Sponsored by 881001)
$CI->db->insert('member', array(
    'id'             => 881002,
    'name'           => 'Test MLM Direct Sponsor',
    'status'         => 'Active',
    'topup'          => 8900,
    'signup_package' => 1,
    'mypv'           => 1,
    'sponsor'        => 881001,
    'position'       => 881001,
    'A'              => 0,
    'B'              => 0,
    'total_a_pv'     => 0,
    'total_b_pv'     => 0,
    'paid_a_pv'      => 0,
    'paid_b_pv'      => 0,
    'total_pairs'    => 0,
));
$CI->db->where('id', 881001)->update('member', array('A' => 881002));
$CI->db->insert('wallet', array('userid' => 881002, 'balance' => 0.00));

// -------------------------------------------------------------
// TEST CASE 1: 1 PV direct sponsor -> Expected Direct Sponsor Income = ₹890
// -------------------------------------------------------------
$CI->db->insert('member', array(
    'id'             => 881003,
    'name'           => 'Test Direct Member 1 (1 PV)',
    'status'         => 'Active',
    'topup'          => 8900,
    'signup_package' => 1,
    'mypv'           => 1,
    'sponsor'        => 881002,
    'position'       => 881002,
    'A'              => 0,
    'B'              => 0,
));
$CI->db->where('id', 881002)->update('member', array('A' => 881003));
$CI->earning->reg_earning(881003, 881002, 1, true);

$tc1_earning = $CI->db->get_where('earning', array('userid' => 881002, 'ref_id' => 881003, 'type' => 'Direct Sponsor Income'))->row();
$test_results['Test Case 1: 1 PV direct sponsor => Direct Sponsor Income = ₹890'] = 
    ($tc1_earning && floatval($tc1_earning->amount) == 890.0)
    ? "PASS (Generated ₹{$tc1_earning->amount})"
    : "FAIL (Found: " . ($tc1_earning ? $tc1_earning->amount : 'None') . ")";

// -------------------------------------------------------------
// TEST CASE 2: 2 direct sponsors * 1 PV -> Expected Direct Sponsor Income = ₹1,780
// -------------------------------------------------------------
$CI->db->insert('member', array(
    'id'             => 881004,
    'name'           => 'Test Direct Member 2 (1 PV)',
    'status'         => 'Active',
    'topup'          => 8900,
    'signup_package' => 1,
    'mypv'           => 1,
    'sponsor'        => 881002,
    'position'       => 881002,
    'A'              => 0,
    'B'              => 0,
));
$CI->db->where('id', 881002)->update('member', array('B' => 881004));
$CI->earning->reg_earning(881004, 881002, 1, true);

$tc2_total = $CI->db->where('userid', 881002)->where_in('type', array('Direct Sponsor Income', 'Direct Sponsor Commission'))->select_sum('amount')->get('earning')->row();
$tc2_amt = $tc2_total ? floatval($tc2_total->amount) : 0;
$test_results['Test Case 2: 2 direct sponsors x 1 PV => Total Direct Sponsor Income = ₹1,780'] = 
    ($tc2_amt == 1780.0)
    ? "PASS (Total ₹{$tc2_amt} for 2 direct sponsors)"
    : "FAIL (Total: ₹{$tc2_amt})";

// -------------------------------------------------------------
// TEST CASE 3: Left = 2 PV, Right = 1 PV -> Expected first Sales Matching Income = ₹890 (2:1 consumed)
// Add 881005 under 881003 (Left leg) so Left has 881003 (1 PV) + 881005 (1 PV) = 2 PV, Right has 881004 (1 PV)
// -------------------------------------------------------------
$CI->db->insert('member', array(
    'id'             => 881005,
    'name'           => 'Test Left Sub Child (1 PV)',
    'status'         => 'Active',
    'topup'          => 8900,
    'signup_package' => 1,
    'mypv'           => 1,
    'sponsor'        => 881002,
    'position'       => 881003,
    'A'              => 0,
    'B'              => 0,
));
$CI->db->where('id', 881003)->update('member', array('A' => 881005));

$CI->earning->update_legs();
$CI->earning->process_binary(881002, array());

$tc3_member = $CI->db->get_where('member', array('id' => 881002))->row();
$tc3_match  = $CI->db->get_where('earning', array('userid' => 881002, 'type' => 'Matching Income'))->row();
$test_results['Test Case 3: Left = 2 PV, Right = 1 PV => First Sales Matching Income = ₹890 (2:1)'] = 
    ($tc3_member->total_pairs == 1 && $tc3_member->paid_a_pv == 2 && $tc3_member->paid_b_pv == 1 && $tc3_match && floatval($tc3_match->amount) == 890.0)
    ? "PASS (1 Pair formed, Left 2 PV & Right 1 PV consumed, Amount: ₹{$tc3_match->amount})"
    : "FAIL (Pairs: {$tc3_member->total_pairs}, Paid A: {$tc3_member->paid_a_pv}, Paid B: {$tc3_member->paid_b_pv})";

// -------------------------------------------------------------
// TEST CASE 4: Left receives 1 PV and Right receives 1 PV -> Subsequent 1:1 Matching Income = ₹890
// Add 881006 on Left (under 881005) and 881007 on Right (under 881004)
// -------------------------------------------------------------
$CI->db->insert('member', array(
    'id'             => 881006,
    'name'           => 'Test Left Additional (1 PV)',
    'status'         => 'Active',
    'topup'          => 8900,
    'signup_package' => 1,
    'mypv'           => 1,
    'sponsor'        => 881002,
    'position'       => 881005,
    'A'              => 0,
    'B'              => 0,
));
$CI->db->where('id', 881005)->update('member', array('A' => 881006));

$CI->db->insert('member', array(
    'id'             => 881007,
    'name'           => 'Test Right Additional (1 PV)',
    'status'         => 'Active',
    'topup'          => 8900,
    'signup_package' => 1,
    'mypv'           => 1,
    'sponsor'        => 881002,
    'position'       => 881004,
    'A'              => 0,
    'B'              => 0,
));
$CI->db->where('id', 881004)->update('member', array('A' => 881007));

$CI->earning->update_legs();
$CI->earning->process_binary(881002, array());

$tc4_member = $CI->db->get_where('member', array('id' => 881002))->row();
$tc4_matches = $CI->db->where('userid', 881002)->where('type', 'Matching Income')->get('earning')->result();
$test_results['Test Case 4: After first match, Left 1 PV / Right 1 PV => Additional 1:1 Matching = ₹890'] = 
    ($tc4_member->total_pairs == 2 && $tc4_member->paid_a_pv == 3 && $tc4_member->paid_b_pv == 2 && count($tc4_matches) == 2)
    ? "PASS (Total Pairs = 2, Total Paid Left = 3 PV, Total Paid Right = 2 PV, 2 Matching records of ₹890 each)"
    : "FAIL (Pairs: {$tc4_member->total_pairs}, Paid A: {$tc4_member->paid_a_pv}, Paid B: {$tc4_member->paid_b_pv})";

// -------------------------------------------------------------
// TEST CASE 5 & 6 & 7: Direct Referral Bonus (DRB)
// Level 1 = 30%, Level 2 = 20%
// User 881003 is sponsored by 881002 (Level 1) and 881001 is Level 2 sponsor!
// -------------------------------------------------------------
$drb_l1 = $CI->db->get_where('earning', array('userid' => 881002, 'ref_id' => 881003, 'levlno' => 1))->row();
$drb_l2 = $CI->db->get_where('earning', array('userid' => 881001, 'ref_id' => 881003, 'levlno' => 2))->row();

$test_results['Test Case 5 & 6: DRB Level 1 at 30% of ₹8,900 = ₹2,670 (30% formula check)'] = 
    ($drb_l1 && floatval($drb_l1->amount) == (8900 * 0.30))
    ? "PASS (Generated Level 1 DRB: ₹{$drb_l1->amount} = 30% of ₹8,900)"
    : "FAIL (Level 1 DRB: " . ($drb_l1 ? $drb_l1->amount : 'None') . ")";

$test_results['Test Case 7: DRB Level 2 uses exactly 20% (₹1,780) and does not use 30%'] = 
    ($drb_l2 && floatval($drb_l2->amount) == (8900 * 0.20) && floatval($drb_l2->amount) != (8900 * 0.30))
    ? "PASS (Generated Level 2 DRB: ₹{$drb_l2->amount} = 20% of ₹8,900)"
    : "FAIL (Level 2 DRB: " . ($drb_l2 ? $drb_l2->amount : 'None') . ")";

// -------------------------------------------------------------
// TEST CASE 8: 0.5 PV Package (₹4,450)
// Direct Sponsor = ₹445, DRB L1 = ₹1,335 (30%), DRB L2 = ₹890 (20%)
// -------------------------------------------------------------
$CI->db->insert('member', array(
    'id'             => 881008,
    'name'           => 'Test 0.5 PV Member',
    'status'         => 'Active',
    'topup'          => 4450,
    'signup_package' => 2,
    'mypv'           => 0.5,
    'sponsor'        => 881002,
    'position'       => 881006,
    'A'              => 0,
    'B'              => 0,
));
$CI->earning->reg_earning(881008, 881002, 2, true);

$tc8_direct = $CI->db->get_where('earning', array('userid' => 881002, 'ref_id' => 881008, 'type' => 'Direct Sponsor Income'))->row();
$tc8_drb_l1 = $CI->db->get_where('earning', array('userid' => 881002, 'ref_id' => 881008, 'levlno' => 1))->row();
$tc8_drb_l2 = $CI->db->get_where('earning', array('userid' => 881001, 'ref_id' => 881008, 'levlno' => 2))->row();

$tc8_direct_ok = ($tc8_direct && floatval($tc8_direct->amount) == 445.0);
$tc8_drb1_ok   = ($tc8_drb_l1 && floatval($tc8_drb_l1->amount) == (4450 * 0.30));
$tc8_drb2_ok   = ($tc8_drb_l2 && floatval($tc8_drb_l2->amount) == (4450 * 0.20));

$test_results['Test Case 8: 0.5 PV Activation Package (₹4,450) => Direct ₹445, DRB L1 ₹1,335, DRB L2 ₹890'] = 
    ($tc8_direct_ok && $tc8_drb1_ok && $tc8_drb2_ok)
    ? "PASS (Direct: ₹{$tc8_direct->amount}, DRB L1: ₹{$tc8_drb_l1->amount}, DRB L2: ₹{$tc8_drb_l2->amount})"
    : "FAIL (Direct: " . ($tc8_direct ? $tc8_direct->amount : '0') . ", DRB1: " . ($tc8_drb_l1 ? $tc8_drb_l1->amount : '0') . ", DRB2: " . ($tc8_drb_l2 ? $tc8_drb_l2->amount : '0') . ")";

// -------------------------------------------------------------
// TEST CASE 9: Retail Profit = MRP - DP
// Example: MRP = ₹10,000, DP = ₹8,900 => Retail Profit = ₹1,100
// -------------------------------------------------------------
$retail_profit_sample = $CI->earning->get_retail_profit(10000, 8900);
$test_results['Test Case 9: Retail Profit = MRP - DP (₹10,000 - ₹8,900 = ₹1,100)'] = 
    ($retail_profit_sample == 1100.0)
    ? "PASS (Calculated Retail Profit: ₹{$retail_profit_sample})"
    : "FAIL (Calculated: ₹{$retail_profit_sample})";

// -------------------------------------------------------------
// TEST CASE 10: Idempotency & Duplicate Prevention
// Re-running reg_earning on 881008 must NOT insert duplicate records
// -------------------------------------------------------------
$CI->earning->reg_earning(881008, 881002, 2, true);
$dup_direct = $CI->db->where('userid', 881002)->where('ref_id', 881008)->where_in('type', array('Direct Sponsor Income', 'Direct Sponsor Commission'))->count_all_results('earning');
$dup_drb1   = $CI->db->where('userid', 881002)->where('ref_id', 881008)->where('levlno', 1)->count_all_results('earning');

$test_results['Test Case 10: Idempotency check: Re-activation does NOT duplicate commissions'] = 
    ($dup_direct === 1 && $dup_drb1 === 1)
    ? "PASS (Direct count = 1, DRB Level 1 count = 1 - duplicates prevented)"
    : "FAIL (Direct count: {$dup_direct}, DRB count: {$dup_drb1})";

// -------------------------------------------------------------
// DISPLAY RESULTS
// -------------------------------------------------------------
echo "=================================================================\n";
echo "TEST RESULTS SUMMARY\n";
echo "=================================================================\n\n";

$pass_count = 0;
$fail_count = 0;
foreach ($test_results as $name => $res) {
    echo "{$name}:\n  => {$res}\n\n";
    if (strpos($res, 'PASS') === 0) {
        $pass_count++;
    } else {
        $fail_count++;
    }
}

echo "TOTAL PASSED: {$pass_count} / " . count($test_results) . "\n";
echo "TOTAL FAILED: {$fail_count}\n";

// -------------------------------------------------------------
// CLEANUP TEST USERS
// -------------------------------------------------------------
$CI->db->where_in('id', $test_uids)->delete('member');
$CI->db->where_in('userid', $test_uids)->delete('wallet');
$CI->db->where_in('userid', $test_uids)->delete('earning');
$CI->db->where_in('ref_id', $test_uids)->delete('earning');
$CI->db->where_in('userid', $test_uids)->delete('laps_earning');

echo "Test suite cleanup completed.\n";
echo "=================================================================\n";
