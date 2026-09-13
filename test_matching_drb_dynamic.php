<?php
/**
 * Automated Test Suite: Dynamic Direct Referral Bonus (DRB) Based on Matching Income
 * GreenAmrutAyurveda
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
echo "TEST SUITE: DYNAMIC DIRECT REFERRAL BONUS (DRB) FROM MATCHING INCOME\n";
echo "Date: " . date('Y-m-d H:i:s') . "\n";
echo "=================================================================\n\n";

$test_results = array();

// -------------------------------------------------------------
// HELPER: CLEANUP TEST USERS & PACKAGES
// -------------------------------------------------------------
$all_test_uids = array(770001, 770002, 770003, 770004, 770005, 770006, 770007, 770008, 770009, 770010, 770011, 770012, 770013, 770014, 770015);
$test_pkg_ids  = array(9901, 9902);

function cleanup_all($CI, $test_uids, $pkg_ids) {
    $CI->db->where_in('id', $test_uids)->delete('member');
    $CI->db->where_in('userid', $test_uids)->delete('wallet');
    $CI->db->where_in('userid', $test_uids)->delete('earning');
    $CI->db->where_in('ref_id', $test_uids)->delete('earning');
    $CI->db->where_in('userid', $test_uids)->delete('laps_earning');
    $CI->db->where_in('id', $pkg_ids)->delete('product');
}

cleanup_all($CI, $all_test_uids, $test_pkg_ids);

// Create Test Packages
// Package 9901: level_income = "30,20", matching_income = 1000
$CI->db->insert('product', array(
    'id'              => 9901,
    'prod_name'       => 'Test 30-20 Package',
    'prod_price'      => 8900,
    'pv'              => 1,
    'direct_income'   => 890,
    'matching_income' => 1000,
    'level_income'    => '30,20',
    'capping'         => 100000,
));

// Package 9902: level_income = "25,15", matching_income = 1000 (Dynamic Test)
$CI->db->insert('product', array(
    'id'              => 9902,
    'prod_name'       => 'Test 25-15 Package',
    'prod_price'      => 8900,
    'pv'              => 1,
    'direct_income'   => 890,
    'matching_income' => 1000,
    'level_income'    => '25,15',
    'capping'         => 100000,
));

// =========================================================================
// TEST 1 — Binary Matching: Verify 2:1, 1:2, and 1:1 Matching
// =========================================================================

// Setup User 770001 (Root Earner)
$CI->db->insert('member', array(
    'id'             => 770001,
    'name'           => 'Test Binary Earner',
    'status'         => 'Active',
    'topup'          => 8900,
    'signup_package' => 9901,
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
$CI->db->insert('wallet', array('userid' => 770001, 'balance' => 0.00));

// Setup 2 Left and 1 Right for 2:1 Match
// Left 1 (770002), Left 2 (770003 under 770002), Right 1 (770004)
$CI->db->insert('member', array('id' => 770002, 'name' => 'L1', 'status' => 'Active', 'topup' => 8900, 'signup_package' => 9901, 'mypv' => 1, 'sponsor' => 770001, 'position' => 770001, 'A' => 0, 'B' => 0));
$CI->db->insert('member', array('id' => 770003, 'name' => 'L2', 'status' => 'Active', 'topup' => 8900, 'signup_package' => 9901, 'mypv' => 1, 'sponsor' => 770001, 'position' => 770002, 'A' => 0, 'B' => 0));
$CI->db->insert('member', array('id' => 770004, 'name' => 'R1', 'status' => 'Active', 'topup' => 8900, 'signup_package' => 9901, 'mypv' => 1, 'sponsor' => 770001, 'position' => 770001, 'A' => 0, 'B' => 0));

$CI->db->where('id', 770001)->update('member', array('A' => 770002, 'B' => 770004));
$CI->db->where('id', 770002)->update('member', array('A' => 770003));

$CI->earning->update_legs();
$CI->earning->process_binary(770001, array());

$m1_after_21 = $CI->db->get_where('member', array('id' => 770001))->row();
$match1_earning = $CI->db->get_where('earning', array('userid' => 770001, 'type' => 'Matching Income'))->row();
$pass_21 = ($m1_after_21 && $m1_after_21->total_pairs == 1 && $m1_after_21->paid_a_pv == 2 && $m1_after_21->paid_b_pv == 1 && $match1_earning && floatval($match1_earning->amount) == 1000.0);

// Setup 1:1 Subsequent match: Add 1 PV Left (770005) and 1 PV Right (770006)
$CI->db->insert('member', array('id' => 770005, 'name' => 'L3', 'status' => 'Active', 'topup' => 8900, 'signup_package' => 9901, 'mypv' => 1, 'sponsor' => 770001, 'position' => 770003, 'A' => 0, 'B' => 0));
$CI->db->where('id', 770003)->update('member', array('A' => 770005));

$CI->db->insert('member', array('id' => 770006, 'name' => 'R2', 'status' => 'Active', 'topup' => 8900, 'signup_package' => 9901, 'mypv' => 1, 'sponsor' => 770001, 'position' => 770004, 'A' => 0, 'B' => 0));
$CI->db->where('id', 770004)->update('member', array('A' => 770006));

$CI->earning->update_legs();
$CI->earning->process_binary(770001, array());

$m1_after_11 = $CI->db->get_where('member', array('id' => 770001))->row();
$matches_11 = $CI->db->where('userid', 770001)->where('type', 'Matching Income')->get('earning')->result();
$pass_11 = ($m1_after_11 && $m1_after_11->total_pairs == 2 && $m1_after_11->paid_a_pv == 3 && $m1_after_11->paid_b_pv == 2 && count($matches_11) == 2);

// Setup a new user 770007 for 1:2 first match (Left = 1 PV, Right = 2 PV)
$CI->db->insert('member', array('id' => 770007, 'name' => 'Test 1:2 Earner', 'status' => 'Active', 'topup' => 8900, 'signup_package' => 9901, 'mypv' => 1, 'sponsor' => 1001, 'position' => 1001, 'A' => 0, 'B' => 0, 'total_pairs' => 0, 'paid_a_pv' => 0, 'paid_b_pv' => 0));
$CI->db->insert('wallet', array('userid' => 770007, 'balance' => 0.00));

$CI->db->insert('member', array('id' => 770008, 'name' => 'L_12', 'status' => 'Active', 'topup' => 8900, 'signup_package' => 9901, 'mypv' => 1, 'sponsor' => 770007, 'position' => 770007, 'A' => 0, 'B' => 0));
$CI->db->insert('member', array('id' => 770009, 'name' => 'R1_12', 'status' => 'Active', 'topup' => 8900, 'signup_package' => 9901, 'mypv' => 1, 'sponsor' => 770007, 'position' => 770007, 'A' => 0, 'B' => 0));
$CI->db->insert('member', array('id' => 770010, 'name' => 'R2_12', 'status' => 'Active', 'topup' => 8900, 'signup_package' => 9901, 'mypv' => 1, 'sponsor' => 770007, 'position' => 770009, 'A' => 0, 'B' => 0));

$CI->db->where('id', 770007)->update('member', array('A' => 770008, 'B' => 770009));
$CI->db->where('id', 770009)->update('member', array('A' => 770010));

$CI->earning->update_legs();
$CI->earning->process_binary(770007, array());

$m7_after_12 = $CI->db->get_where('member', array('id' => 770007))->row();
$match12_earning = $CI->db->get_where('earning', array('userid' => 770007, 'type' => 'Matching Income'))->row();
$pass_12 = ($m7_after_12 && $m7_after_12->total_pairs == 1 && $m7_after_12->paid_a_pv == 1 && $m7_after_12->paid_b_pv == 2 && $match12_earning && floatval($match12_earning->amount) == 1000.0);

$test1_pass = ($pass_21 && $pass_11 && $pass_12);
$test_results['Test 1 — Binary Matching (2:1, 1:2, 1:1)'] = $test1_pass
    ? "PASS (2:1 verified: 1 Pair, Paid A=2, Paid B=1; 1:2 verified: 1 Pair, Paid A=1, Paid B=2; 1:1 verified: 2nd Pair, Paid A=3, Paid B=2)"
    : "FAIL (2:1: " . ($pass_21?'OK':'FAIL') . ", 1:2: " . ($pass_12?'OK':'FAIL') . ", 1:1: " . ($pass_11?'OK':'FAIL') . ")";


// =========================================================================
// TEST 2 & TEST 3 — Level 1 DRB (30% = ₹300) and Level 2 DRB (20% = ₹200)
// Tree Structure:
// Level 2 Sponsor: 770011 (Active, topup > 0)
// Level 1 Sponsor: 770012 (Active, topup > 0, sponsor = 770011)
// Downline Earner: 770013 (Active, signup_package = 9901 with level_income="30,20", sponsor = 770012)
// =========================================================================

$CI->db->insert('member', array('id' => 770011, 'name' => 'L2 Sponsor', 'status' => 'Active', 'topup' => 8900, 'signup_package' => 9901, 'mypv' => 1, 'sponsor' => 1001, 'position' => 1001, 'A' => 0, 'B' => 0));
$CI->db->insert('wallet', array('userid' => 770011, 'balance' => 0.00));

$CI->db->insert('member', array('id' => 770012, 'name' => 'L1 Sponsor', 'status' => 'Active', 'topup' => 8900, 'signup_package' => 9901, 'mypv' => 1, 'sponsor' => 770011, 'position' => 770011, 'A' => 0, 'B' => 0));
$CI->db->insert('wallet', array('userid' => 770012, 'balance' => 0.00));

$CI->db->insert('member', array('id' => 770013, 'name' => 'Downline Earner', 'status' => 'Active', 'topup' => 8900, 'signup_package' => 9901, 'mypv' => 1, 'sponsor' => 770012, 'position' => 770012, 'A' => 0, 'B' => 0));
$CI->db->insert('wallet', array('userid' => 770013, 'balance' => 0.00));

// Process Matching Income of ₹1,000 for member 770013
$CI->earning->process_lvl(770013, 1000.0);

// Check Level 1 DRB on Sponsor 770012
$drb_l1_entry = $CI->db->get_where('earning', array('userid' => 770012, 'ref_id' => 770013, 'type' => 'Direct Referral Bonus', 'levlno' => 1))->row();
$test2_pass = ($drb_l1_entry && floatval($drb_l1_entry->amount) == 300.0 && $drb_l1_entry->type === 'Direct Referral Bonus' && (int)$drb_l1_entry->levlno === 1);
$test_results['Test 2 — Level 1 DRB (30% of ₹1,000 = ₹300, levlno = 1)'] = $test2_pass
    ? "PASS (Found 'Direct Referral Bonus', Amount: ₹{$drb_l1_entry->amount}, levlno: {$drb_l1_entry->levlno})"
    : "FAIL (Found: " . ($drb_l1_entry ? "Type: {$drb_l1_entry->type}, Amount: {$drb_l1_entry->amount}, levlno: {$drb_l1_entry->levlno}" : "None") . ")";

// Check Level 2 DRB on Grand Sponsor 770011
$drb_l2_entry = $CI->db->get_where('earning', array('userid' => 770011, 'ref_id' => 770013, 'type' => 'Direct Referral Bonus (Level 2)', 'levlno' => 2))->row();
$test3_pass = ($drb_l2_entry && floatval($drb_l2_entry->amount) == 200.0 && $drb_l2_entry->type === 'Direct Referral Bonus (Level 2)' && (int)$drb_l2_entry->levlno === 2);
$test_results['Test 3 — Level 2 DRB (20% of ₹1,000 = ₹200, levlno = 2)'] = $test3_pass
    ? "PASS (Found 'Direct Referral Bonus (Level 2)', Amount: ₹{$drb_l2_entry->amount}, levlno: {$drb_l2_entry->levlno})"
    : "FAIL (Found: " . ($drb_l2_entry ? "Type: {$drb_l2_entry->type}, Amount: {$drb_l2_entry->amount}, levlno: {$drb_l2_entry->levlno}" : "None") . ")";


// =========================================================================
// TEST 4 — Dynamic Configuration: Package 9902 with level_income = "25,15"
// Matching Income = ₹1,000 => Expected: Level 1 = ₹250, Level 2 = ₹150
// =========================================================================

$CI->db->insert('member', array('id' => 770014, 'name' => 'Dynamic Downline Earner', 'status' => 'Active', 'topup' => 8900, 'signup_package' => 9902, 'mypv' => 1, 'sponsor' => 770012, 'position' => 770013, 'A' => 0, 'B' => 0));

// Process Matching Income of ₹1,000 for member 770014
$CI->earning->process_lvl(770014, 1000.0);

$dyn_l1 = $CI->db->get_where('earning', array('userid' => 770012, 'ref_id' => 770014, 'type' => 'Direct Referral Bonus', 'levlno' => 1))->row();
$dyn_l2 = $CI->db->get_where('earning', array('userid' => 770011, 'ref_id' => 770014, 'type' => 'Direct Referral Bonus (Level 2)', 'levlno' => 2))->row();

$test4_pass = ($dyn_l1 && floatval($dyn_l1->amount) == 250.0 && $dyn_l2 && floatval($dyn_l2->amount) == 150.0);
$test_results['Test 4 — Dynamic Configuration (level_income = "25,15" => L1 = ₹250, L2 = ₹150)'] = $test4_pass
    ? "PASS (Level 1 DRB: ₹{$dyn_l1->amount} [25%], Level 2 DRB: ₹{$dyn_l2->amount} [15%] - Dynamic config confirmed)"
    : "FAIL (L1: " . ($dyn_l1 ? $dyn_l1->amount : 'None') . ", L2: " . ($dyn_l2 ? $dyn_l2->amount : 'None') . ")";


// =========================================================================
// TEST 5 — Inactive Sponsor Handling
// Case A: Sponsor status != 'Active' (e.g. 'Inactive')
// Case B: Sponsor topup <= 0
// Verify: No earning credited to sponsor, recorded in laps_earning
// =========================================================================

// Setup Inactive L1 Sponsor 770015 (status = 'Inactive', topup = 0) sponsored by Active 770011
$CI->db->insert('member', array('id' => 770015, 'name' => 'Inactive L1 Sponsor', 'status' => 'Inactive', 'topup' => 0, 'signup_package' => 9901, 'mypv' => 0, 'sponsor' => 770011, 'position' => 770011, 'A' => 0, 'B' => 0));

// Temporary Downline Earner under 770015
$CI->db->insert('member', array('id' => 770008, 'name' => 'Downline of Inactive Sponsor', 'status' => 'Active', 'topup' => 8900, 'signup_package' => 9901, 'mypv' => 1, 'sponsor' => 770015, 'position' => 770015, 'A' => 0, 'B' => 0));

// Process Matching Income of ₹1,000 for member 770008
$CI->earning->process_lvl(770008, 1000.0);

// Verify Inactive L1 Sponsor 770015 did NOT receive active earning, but got laps_earning
$inact_earning = $CI->db->get_where('earning', array('userid' => 770015, 'ref_id' => 770008))->row();
$inact_laps    = $CI->db->get_where('laps_earning', array('userid' => 770015, 'type' => 'Direct Referral Bonus'))->row();

$test5_pass = (!$inact_earning && $inact_laps && floatval($inact_laps->amount) == 300.0);
$test_results['Test 5 — Inactive Sponsor (status != Active / topup <= 0 => Laps Earning)'] = $test5_pass
    ? "PASS (No active earning credited to inactive sponsor; laps_earning record created with Amount: ₹{$inact_laps->amount}, Reason: '{$inact_laps->reason}')"
    : "FAIL (Active earning: " . ($inact_earning ? 'Credited' : 'None') . ", Laps: " . ($inact_laps ? "₹{$inact_laps->amount}" : 'None') . ")";


// =========================================================================
// SUMMARY OF RESULTS
// =========================================================================
echo "\n=================================================================\n";
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

// Cleanup test data
cleanup_all($CI, $all_test_uids, $test_pkg_ids);

echo "Test suite cleanup completed.\n";
echo "=================================================================\n";
