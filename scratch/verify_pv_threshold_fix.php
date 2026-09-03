<?php
/**
 * Read-Only Verification Script for Binary PV Threshold Adjustment
 */

function simulate_process_binary($left_pv, $right_pv, $paid_left = 0, $paid_right = 0, $pkg_price = 8900, $pkg_ratio = 1.0) {
    $available_left  = $left_pv - $paid_left;
    $available_right = $right_pv - $paid_right;

    // Logic under test (from Earning.php):
    // Check available PV eligibility (requires positive available PV on both sides)
    if ($available_left <= 0 || $available_right <= 0) {
        return [
            'qualified' => false,
            'pair_match' => 0,
            'gross_matching' => 0,
            'sponsor_deduct' => 0,
            'reserve_deduct' => 0,
            'repurchase_deduct' => 0,
            'total_deductions' => 0,
            'net_matching' => 0,
            'unmatched_left' => max(0, $available_left),
            'unmatched_right' => max(0, $available_right),
            'new_paid_left' => $paid_left,
            'new_paid_right' => $paid_right,
        ];
    }

    $pair_match = floor(min($available_left, $available_right));

    if ($pair_match < 1) {
        return [
            'qualified' => false,
            'pair_match' => 0,
            'gross_matching' => 0,
            'sponsor_deduct' => 0,
            'reserve_deduct' => 0,
            'repurchase_deduct' => 0,
            'total_deductions' => 0,
            'net_matching' => 0,
            'unmatched_left' => max(0, $available_left),
            'unmatched_right' => max(0, $available_right),
            'new_paid_left' => $paid_left,
            'new_paid_right' => $paid_right,
        ];
    }

    // Matching Income calculations for Product 1 (10% of Package Value per pair)
    $gross_matching = ($pair_match * ($pkg_price * 0.10)) * $pkg_ratio;

    // Deductions
    $sponsor_deduct    = ($gross_matching * 6) / 100;
    $reserve_deduct    = ($gross_matching * 5) / 100;
    $repurchase_deduct = ($gross_matching * 3) / 100;
    $total_deductions  = $sponsor_deduct + $reserve_deduct + $repurchase_deduct;
    $net_matching      = $gross_matching - $total_deductions;

    $new_paid_left  = $paid_left + $pair_match;
    $new_paid_right = $paid_right + $pair_match;
    $unmatched_left  = $left_pv - $new_paid_left;
    $unmatched_right = $right_pv - $new_paid_right;

    return [
        'qualified' => true,
        'pair_match' => $pair_match,
        'gross_matching' => $gross_matching,
        'sponsor_deduct' => $sponsor_deduct,
        'reserve_deduct' => $reserve_deduct,
        'repurchase_deduct' => $repurchase_deduct,
        'total_deductions' => $total_deductions,
        'net_matching' => $net_matching,
        'unmatched_left' => $unmatched_left,
        'unmatched_right' => $unmatched_right,
        'new_paid_left' => $new_paid_left,
        'new_paid_right' => $new_paid_right,
    ];
}

// Run Test Cases
$all_passed = true;
echo "=== BINARY PV THRESHOLD READ-ONLY VERIFICATION ===\n\n";

// TEST 1: Left = 1, Right = 1
$t1 = simulate_process_binary(1, 1);
echo "TEST 1 (Left PV = 1, Right PV = 1):\n";
echo "  - Qualified: " . ($t1['qualified'] ? 'YES' : 'NO') . "\n";
echo "  - Pair Match: {$t1['pair_match']}\n";
echo "  - Gross Matching: ₹" . number_format($t1['gross_matching'], 2) . "\n";
echo "  - 14% Deductions: ₹" . number_format($t1['total_deductions'], 2) . "\n";
echo "  - Net Matching: ₹" . number_format($t1['net_matching'], 2) . "\n";
echo "  - Unmatched PV (Left/Right): {$t1['unmatched_left']} / {$t1['unmatched_right']}\n";
if ($t1['pair_match'] == 1 && $t1['gross_matching'] == 890 && round($t1['net_matching'], 2) == 765.40) {
    echo "  => STATUS: PASSED [OK]\n\n";
} else {
    echo "  => STATUS: FAILED [MISMATCH]\n\n";
    $all_passed = false;
}

// TEST 2: Left = 0, Right = 1
$t2 = simulate_process_binary(0, 1);
echo "TEST 2 (Left PV = 0, Right PV = 1):\n";
echo "  - Qualified: " . ($t2['qualified'] ? 'YES' : 'NO') . "\n";
echo "  - Pair Match: {$t2['pair_match']}\n";
if (!$t2['qualified'] && $t2['pair_match'] == 0) {
    echo "  => STATUS: PASSED [OK]\n\n";
} else {
    echo "  => STATUS: FAILED [MISMATCH]\n\n";
    $all_passed = false;
}

// TEST 3: Left = 1, Right = 0
$t3 = simulate_process_binary(1, 0);
echo "TEST 3 (Left PV = 1, Right PV = 0):\n";
echo "  - Qualified: " . ($t3['qualified'] ? 'YES' : 'NO') . "\n";
echo "  - Pair Match: {$t3['pair_match']}\n";
if (!$t3['qualified'] && $t3['pair_match'] == 0) {
    echo "  => STATUS: PASSED [OK]\n\n";
} else {
    echo "  => STATUS: FAILED [MISMATCH]\n\n";
    $all_passed = false;
}

// TEST 4: Left = 2, Right = 1
$t4 = simulate_process_binary(2, 1);
echo "TEST 4 (Left PV = 2, Right PV = 1):\n";
echo "  - Qualified: " . ($t4['qualified'] ? 'YES' : 'NO') . "\n";
echo "  - Pair Match: {$t4['pair_match']}\n";
echo "  - Unmatched PV Carry-Forward (Left/Right): {$t4['unmatched_left']} / {$t4['unmatched_right']}\n";
if ($t4['pair_match'] == 1 && $t4['unmatched_left'] == 1 && $t4['unmatched_right'] == 0) {
    echo "  => STATUS: PASSED [OK]\n\n";
} else {
    echo "  => STATUS: FAILED [MISMATCH]\n\n";
    $all_passed = false;
}

// TEST 5: Left = 5, Right = 3
$t5 = simulate_process_binary(5, 3);
echo "TEST 5 (Left PV = 5, Right PV = 3):\n";
echo "  - Qualified: " . ($t5['qualified'] ? 'YES' : 'NO') . "\n";
echo "  - Pair Match: {$t5['pair_match']}\n";
echo "  - Unmatched PV Carry-Forward (Left/Right): {$t5['unmatched_left']} / {$t5['unmatched_right']}\n";
if ($t5['pair_match'] == 3 && $t5['unmatched_left'] == 2 && $t5['unmatched_right'] == 0) {
    echo "  => STATUS: PASSED [OK]\n\n";
} else {
    echo "  => STATUS: FAILED [MISMATCH]\n\n";
    $all_passed = false;
}

echo "OVERALL VERIFICATION RESULT: " . ($all_passed ? "ALL 5 TEST SCENARIOS PASSED PERFECTLY!" : "SOME TESTS FAILED") . "\n";
