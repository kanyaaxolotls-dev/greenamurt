<?php
/**
 * Test MLM Plan Specification
 * Validates all required test cases for Sales Matching Income and Direct Referral Bonus (DRB)
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
echo "MLM COMPENSATION PLAN VERIFICATION SUITE\n";
echo "Timestamp: " . date('Y-m-d H:i:s') . "\n";
echo "=================================================================\n\n";

$test_uids = [880001, 880002, 880003, 880004, 880005, 880006, 880007, 880008, 880009];
$test_pkg  = 8899;

function cleanup($CI, $test_uids, $test_pkg) {
    $CI->db->where_in('id', $test_uids)->delete('member');
    $CI->db->where_in('userid', $test_uids)->delete('wallet');
    $CI->db->where_in('userid', $test_uids)->delete('earning');
    $CI->db->where_in('ref_id', $test_uids)->delete('earning');
    $CI->db->where_in('userid', $test_uids)->delete('laps_earning');
    $CI->db->where('id', $test_pkg)->delete('product');
}

cleanup($CI, $test_uids, $test_pkg);

// Setup product package
$CI->db->insert('product', [
    'id'              => $test_pkg,
    'prod_name'       => 'Plan Spec Verification Package',
    'prod_price'      => 8900,
    'pv'              => 1,
    'direct_income'   => 890,
    'matching_income' => 890,
    'level_income'    => '30,20',
    'capping'         => 100000,
]);

$all_passed = true;

// -------------------------------------------------------------
// TEST 1: 2 Left PV + 1 Right PV (2:1 Ratio First Match)
// -------------------------------------------------------------
echo "TEST 1: 2 Left PV + 1 Right PV (2:1 First Match)\n";
$uid1 = 880001;
$CI->db->insert('member', [
    'id' => $uid1, 'name' => 'User 1', 'status' => 'Active', 'topup' => 8900, 'signup_package' => $test_pkg,
    'total_a_pv' => 2, 'total_b_pv' => 1, 'paid_a_pv' => 0, 'paid_b_pv' => 0, 'total_pairs' => 0
]);
$CI->earning->process_binary($uid1, ['total_a_pv' => 2, 'total_b_pv' => 1]);
$m1 = $CI->db->get_where('member', ['id' => $uid1])->row();
$e1 = $CI->db->get_where('earning', ['userid' => $uid1, 'type' => 'Matching Income'])->row();

$t1_pass = ($m1->paid_a_pv == 2 && $m1->paid_b_pv == 1 && $m1->total_pairs == 1 && $e1 && $e1->amount == 890);
echo "  - Paid Left PV: {$m1->paid_a_pv} (Expected: 2)\n";
echo "  - Paid Right PV: {$m1->paid_b_pv} (Expected: 1)\n";
echo "  - Total Pairs: {$m1->total_pairs} (Expected: 1)\n";
echo "  - Matching Amount: ₹" . ($e1 ? $e1->amount : 0) . " (Expected: ₹890)\n";
echo "  Result: " . ($t1_pass ? "PASSED [OK]" : "FAILED [X]") . "\n\n";
if (!$t1_pass) $all_passed = false;

// -------------------------------------------------------------
// TEST 2: 1 Left PV + 2 Right PV (1:2 Ratio First Match)
// -------------------------------------------------------------
echo "TEST 2: 1 Left PV + 2 Right PV (1:2 First Match)\n";
$uid2 = 880002;
$CI->db->insert('member', [
    'id' => $uid2, 'name' => 'User 2', 'status' => 'Active', 'topup' => 8900, 'signup_package' => $test_pkg,
    'total_a_pv' => 1, 'total_b_pv' => 2, 'paid_a_pv' => 0, 'paid_b_pv' => 0, 'total_pairs' => 0
]);
$CI->earning->process_binary($uid2, ['total_a_pv' => 1, 'total_b_pv' => 2]);
$m2 = $CI->db->get_where('member', ['id' => $uid2])->row();
$e2 = $CI->db->get_where('earning', ['userid' => $uid2, 'type' => 'Matching Income'])->row();

$t2_pass = ($m2->paid_a_pv == 1 && $m2->paid_b_pv == 2 && $m2->total_pairs == 1 && $e2 && $e2->amount == 890);
echo "  - Paid Left PV: {$m2->paid_a_pv} (Expected: 1)\n";
echo "  - Paid Right PV: {$m2->paid_b_pv} (Expected: 2)\n";
echo "  - Total Pairs: {$m2->total_pairs} (Expected: 1)\n";
echo "  - Matching Amount: ₹" . ($e2 ? $e2->amount : 0) . " (Expected: ₹890)\n";
echo "  Result: " . ($t2_pass ? "PASSED [OK]" : "FAILED [X]") . "\n\n";
if (!$t2_pass) $all_passed = false;

// -------------------------------------------------------------
// TEST 3: Left = 3 PV, Right = 2 PV (2:1 first, then 1:1 remaining)
// -------------------------------------------------------------
echo "TEST 3: Left = 3 PV, Right = 2 PV (First 2:1 then 1:1 on remaining)\n";
$uid3 = 880003;
$CI->db->insert('member', [
    'id' => $uid3, 'name' => 'User 3', 'status' => 'Active', 'topup' => 8900, 'signup_package' => $test_pkg,
    'total_a_pv' => 3, 'total_b_pv' => 2, 'paid_a_pv' => 0, 'paid_b_pv' => 0, 'total_pairs' => 0
]);
$CI->earning->process_binary($uid3, ['total_a_pv' => 3, 'total_b_pv' => 2]);
$m3 = $CI->db->get_where('member', ['id' => $uid3])->row();
$e3 = $CI->db->get_where('earning', ['userid' => $uid3, 'type' => 'Matching Income'])->row();

$t3_pass = ($m3->paid_a_pv == 3 && $m3->paid_b_pv == 2 && $m3->total_pairs == 2 && $e3 && $e3->amount == 1780);
echo "  - Paid Left PV: {$m3->paid_a_pv} (Expected: 3 = 2 for 2:1 + 1 for 1:1)\n";
echo "  - Paid Right PV: {$m3->paid_b_pv} (Expected: 2 = 1 for 2:1 + 1 for 1:1)\n";
echo "  - Total Pairs: {$m3->total_pairs} (Expected: 2)\n";
echo "  - Matching Amount: ₹" . ($e3 ? $e3->amount : 0) . " (Expected: ₹1,780)\n";
echo "  Result: " . ($t3_pass ? "PASSED [OK]" : "FAILED [X]") . "\n\n";
if (!$t3_pass) $all_passed = false;

// -------------------------------------------------------------
// TEST 4: DRB on Single ₹890 Matching Income (YOU -> Sponsor L1 -> Sponsor L2)
// -------------------------------------------------------------
echo "TEST 4: DRB on Single ₹890 Matching Income (YOU -> Sponsor L1 -> Sponsor L2 with completed tail)\n";
$you = 880004;
$sp1 = 880005;
$sp2 = 880006;

$CI->db->insert('member', ['id' => $sp2, 'name' => 'Grand Sponsor (L2)', 'status' => 'Active', 'topup' => 8900, 'sponsor' => 1001, 'signup_package' => $test_pkg, 'total_pairs' => 1]);
$CI->db->insert('member', ['id' => $sp1, 'name' => 'Direct Sponsor (L1)', 'status' => 'Active', 'topup' => 8900, 'sponsor' => $sp2, 'signup_package' => $test_pkg, 'total_pairs' => 1]);
$CI->db->insert('member', ['id' => $you, 'name' => 'Matching Earner', 'status' => 'Active', 'topup' => 8900, 'sponsor' => $sp1, 'signup_package' => $test_pkg]);

$CI->earning->process_lvl($you, 890, 'TEST4');
$drb1 = $CI->db->get_where('earning', ['userid' => $sp1, 'ref_id' => $you, 'type' => 'Direct Referral Bonus Level 1', 'levlno' => 1])->row();
$drb2 = $CI->db->get_where('earning', ['userid' => $sp2, 'ref_id' => $you, 'type' => 'Direct Referral Bonus Level 2', 'levlno' => 2])->row();

$t4_pass = ($drb1 && $drb1->amount == 267 && $drb2 && $drb2->amount == 178);
echo "  - Level 1 DRB (30% of ₹890): ₹" . ($drb1 ? $drb1->amount : 0) . " (Expected: ₹267)\n";
echo "  - Level 2 DRB (20% of ₹890): ₹" . ($drb2 ? $drb2->amount : 0) . " (Expected: ₹178)\n";
echo "  Result: " . ($t4_pass ? "PASSED [OK]" : "FAILED [X]") . "\n\n";
if (!$t4_pass) $all_passed = false;

// -------------------------------------------------------------
// TEST 4B: Unqualified Sponsor (Tail not complete / total_pairs = 0) gets NO DRB
// -------------------------------------------------------------
echo "TEST 4B: Unqualified Sponsor with total_pairs = 0 (Tail Not Complete)\n";
$unq_sp = 880015;
$unq_down = 880016;
$CI->db->where_in('id', [$unq_sp, $unq_down])->delete('member');
$CI->db->where_in('userid', [$unq_sp, $unq_down])->delete('earning');
$CI->db->where_in('ref_id', [$unq_sp, $unq_down])->delete('earning');

$CI->db->insert('member', ['id' => $unq_sp, 'name' => 'Unqualified Sponsor', 'status' => 'Active', 'topup' => 8900, 'sponsor' => 1001, 'signup_package' => $test_pkg, 'total_pairs' => 0]);
$CI->db->insert('member', ['id' => $unq_down, 'name' => 'Downline Earner', 'status' => 'Active', 'topup' => 8900, 'sponsor' => $unq_sp, 'signup_package' => $test_pkg]);

$CI->earning->process_lvl($unq_down, 890, 'TEST4B');
$unq_drb = $CI->db->get_where('earning', ['userid' => $unq_sp, 'ref_id' => $unq_down])->row();

$t4b_pass = ($unq_drb === null);
echo "  - DRB for Unqualified Sponsor: " . ($unq_drb ? "₹" . $unq_drb->amount : "0 (Blocked)") . " (Expected: 0 / Blocked)\n";
echo "  Result: " . ($t4b_pass ? "PASSED [OK]" : "FAILED [X]") . "\n\n";
if (!$t4b_pass) $all_passed = false;

// -------------------------------------------------------------
// TEST 5: Level 1 Multi-Member (Ref Image 2: A=30,000, B=20,000 -> Total=15,000)
// -------------------------------------------------------------
echo "TEST 5: Level 1 Multi-Member DRB (A = ₹30,000, B = ₹20,000 -> 30% each)\n";
$you_l1 = 880007;
$mem_a  = 880008;
$mem_b  = 880009;

$CI->db->insert('member', ['id' => $you_l1, 'name' => 'YOU (L1 Collector)', 'status' => 'Active', 'topup' => 8900, 'sponsor' => 1001, 'signup_package' => $test_pkg, 'total_pairs' => 1]);
$CI->db->insert('member', ['id' => $mem_a, 'name' => 'Member A', 'status' => 'Active', 'topup' => 8900, 'sponsor' => $you_l1, 'signup_package' => $test_pkg]);
$CI->db->insert('member', ['id' => $mem_b, 'name' => 'Member B', 'status' => 'Active', 'topup' => 8900, 'sponsor' => $you_l1, 'signup_package' => $test_pkg]);

$CI->earning->process_lvl($mem_a, 30000, 'TEST5_A');
$CI->earning->process_lvl($mem_b, 20000, 'TEST5_B');

$drb_a = $CI->db->get_where('earning', ['userid' => $you_l1, 'ref_id' => $mem_a, 'type' => 'Direct Referral Bonus Level 1', 'levlno' => 1])->row();
$drb_b = $CI->db->get_where('earning', ['userid' => $you_l1, 'ref_id' => $mem_b, 'type' => 'Direct Referral Bonus Level 1', 'levlno' => 1])->row();
$tot_l1 = ($drb_a ? $drb_a->amount : 0) + ($drb_b ? $drb_b->amount : 0);

$t5_pass = ($drb_a && $drb_a->amount == 9000 && $drb_b && $drb_b->amount == 6000 && $tot_l1 == 15000);
echo "  - Member A DRB (30% of ₹30,000): ₹" . ($drb_a ? $drb_a->amount : 0) . " (Expected: ₹9,000)\n";
echo "  - Member B DRB (30% of ₹20,000): ₹" . ($drb_b ? $drb_b->amount : 0) . " (Expected: ₹6,000)\n";
echo "  - Total Level 1 DRB: ₹" . $tot_l1 . " (Expected: ₹15,000)\n";
echo "  Result: " . ($t5_pass ? "PASSED [OK]" : "FAILED [X]") . "\n\n";
if (!$t5_pass) $all_passed = false;

// -------------------------------------------------------------
// TEST 6: Level 2 Multi-Member (Ref Image 1: C=40k, D=30k, E=20k, F=50k -> Total=28,000)
// -------------------------------------------------------------
echo "TEST 6: Level 2 Multi-Member DRB (C=40k, D=30k, E=20k, F=50k -> 20% each)\n";
// Let's create C, D under A (under YOU), E, F under B (under YOU)
$mem_c = 880010; $mem_d = 880011; $mem_e = 880012; $mem_f = 880013;
$CI->db->where_in('id', [$mem_c, $mem_d, $mem_e, $mem_f])->delete('member');
$CI->db->where_in('ref_id', [$mem_c, $mem_d, $mem_e, $mem_f])->delete('earning');

$CI->db->insert('member', ['id' => $mem_c, 'name' => 'Member C', 'status' => 'Active', 'topup' => 8900, 'sponsor' => $mem_a, 'signup_package' => $test_pkg]);
$CI->db->insert('member', ['id' => $mem_d, 'name' => 'Member D', 'status' => 'Active', 'topup' => 8900, 'sponsor' => $mem_a, 'signup_package' => $test_pkg]);
$CI->db->insert('member', ['id' => $mem_e, 'name' => 'Member E', 'status' => 'Active', 'topup' => 8900, 'sponsor' => $mem_b, 'signup_package' => $test_pkg]);
$CI->db->insert('member', ['id' => $mem_f, 'name' => 'Member F', 'status' => 'Active', 'topup' => 8900, 'sponsor' => $mem_b, 'signup_package' => $test_pkg]);

$CI->earning->process_lvl($mem_c, 40000, 'TEST6_C');
$CI->earning->process_lvl($mem_d, 30000, 'TEST6_D');
$CI->earning->process_lvl($mem_e, 20000, 'TEST6_E');
$CI->earning->process_lvl($mem_f, 50000, 'TEST6_F');

$drb_c = $CI->db->get_where('earning', ['userid' => $you_l1, 'ref_id' => $mem_c, 'type' => 'Direct Referral Bonus Level 2', 'levlno' => 2])->row();
$drb_d = $CI->db->get_where('earning', ['userid' => $you_l1, 'ref_id' => $mem_d, 'type' => 'Direct Referral Bonus Level 2', 'levlno' => 2])->row();
$drb_e = $CI->db->get_where('earning', ['userid' => $you_l1, 'ref_id' => $mem_e, 'type' => 'Direct Referral Bonus Level 2', 'levlno' => 2])->row();
$drb_f = $CI->db->get_where('earning', ['userid' => $you_l1, 'ref_id' => $mem_f, 'type' => 'Direct Referral Bonus Level 2', 'levlno' => 2])->row();

$tot_l2 = ($drb_c ? $drb_c->amount : 0) + ($drb_d ? $drb_d->amount : 0) + ($drb_e ? $drb_e->amount : 0) + ($drb_f ? $drb_f->amount : 0);
$grand_drb = $tot_l1 + $tot_l2;

$t6_pass = ($drb_c && $drb_c->amount == 8000 && $drb_d && $drb_d->amount == 6000 && $drb_e && $drb_e->amount == 4000 && $drb_f && $drb_f->amount == 10000 && $tot_l2 == 28000 && $grand_drb == 43000);
echo "  - Member C DRB (20% of ₹40,000): ₹" . ($drb_c ? $drb_c->amount : 0) . " (Expected: ₹8,000)\n";
echo "  - Member D DRB (20% of ₹30,000): ₹" . ($drb_d ? $drb_d->amount : 0) . " (Expected: ₹6,000)\n";
echo "  - Member E DRB (20% of ₹20,000): ₹" . ($drb_e ? $drb_e->amount : 0) . " (Expected: ₹4,000)\n";
echo "  - Member F DRB (20% of ₹50,000): ₹" . ($drb_f ? $drb_f->amount : 0) . " (Expected: ₹10,000)\n";
echo "  - Total Level 2 DRB: ₹" . $tot_l2 . " (Expected: ₹28,000)\n";
echo "  - Total DRB (Level 1 + Level 2): ₹" . $grand_drb . " (Expected: ₹43,000)\n";
echo "  Result: " . ($t6_pass ? "PASSED [OK]" : "FAILED [X]") . "\n\n";
if (!$t6_pass) $all_passed = false;

// -------------------------------------------------------------
// TEST 7: Daily Cap Verification
// -------------------------------------------------------------
echo "TEST 7: Daily Capping Enforcement\n";
$cap_pkg = 8898;
$CI->db->where('id', $cap_pkg)->delete('product');
$CI->db->insert('product', [
    'id' => $cap_pkg, 'prod_name' => 'Capping Test Package', 'prod_price' => 8900, 'pv' => 1,
    'direct_income' => 890, 'matching_income' => 890, 'capping' => 890 // Cap = 1 pair per day (₹890)
]);
$cap_uid = 880014;
$CI->db->where('id', $cap_uid)->delete('member');
$CI->db->where('userid', $cap_uid)->delete('earning');
$CI->db->where('userid', $cap_uid)->delete('laps_earning');

$CI->db->insert('member', [
    'id' => $cap_uid, 'name' => 'Capping User', 'status' => 'Active', 'topup' => 8900, 'signup_package' => $cap_pkg,
    'total_a_pv' => 4, 'total_b_pv' => 3, 'paid_a_pv' => 0, 'paid_b_pv' => 0, 'total_pairs' => 0
]);
$CI->earning->process_binary($cap_uid, ['total_a_pv' => 4, 'total_b_pv' => 3]);
$cap_earn = $CI->db->get_where('earning', ['userid' => $cap_uid, 'type' => 'Matching Income'])->result();
$cap_laps = $CI->db->get_where('laps_earning', ['userid' => $cap_uid, 'type' => 'Matching Income'])->result();

$t7_pass = (count($cap_earn) == 1 && $cap_earn[0]->amount == 890 && count($cap_laps) >= 1 && $cap_laps[0]->amount == 1780);
echo "  - Paid Matching Income: ₹" . ($cap_earn ? $cap_earn[0]->amount : 0) . " (Expected: ₹890)\n";
echo "  - Flushed Amount (in laps_earning): ₹" . ($cap_laps ? $cap_laps[0]->amount : 0) . " (Expected: ₹1,780)\n";
echo "  Result: " . ($t7_pass ? "PASSED [OK]" : "FAILED [X]") . "\n\n";
if (!$t7_pass) $all_passed = false;

// -------------------------------------------------------------
// TEST 8: Duplicate Prevention / Idempotency
// -------------------------------------------------------------
echo "TEST 8: Duplicate Prevention on Re-execution\n";
// Re-running process_binary on User 1 (already matched)
$CI->earning->process_binary($uid1, ['total_a_pv' => 2, 'total_b_pv' => 1]);
$match_count = $CI->db->where('userid', $uid1)->where('type', 'Matching Income')->count_all_results('earning');

// Re-running process_lvl on Member A (already processed)
$CI->earning->process_lvl($mem_a, 30000, 'TEST5_A');
$drb_count = $CI->db->where('userid', $you_l1)->where('ref_id', $mem_a)->where('type', 'Direct Referral Bonus')->where('levlno', 1)->count_all_results('earning');

$t8_pass = ($match_count == 1 && $drb_count == 1);
echo "  - Matching Records for User 1 after 2nd run: {$match_count} (Expected: 1)\n";
echo "  - Level 1 DRB Records for Sponsor after 2nd run: {$drb_count} (Expected: 1)\n";
echo "  Result: " . ($t8_pass ? "PASSED [OK]" : "FAILED [X]") . "\n\n";
if (!$t8_pass) $all_passed = false;

// Cleanup
cleanup($CI, array_merge($test_uids, [$mem_c, $mem_d, $mem_e, $mem_f, $cap_uid]), $test_pkg);
$CI->db->where('id', $cap_pkg)->delete('product');

echo "=================================================================\n";
echo "SUMMARY: " . ($all_passed ? "ALL 8 TESTS PASSED PERFECTLY!" : "SOME TESTS FAILED") . "\n";
echo "=================================================================\n";
