<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Earning extends CI_Model
{

    public function __construct()
    {
        parent::__construct();
        $this->common_model->__session();
    }

    public function get_package_ratio($packageid = 0, $userid = null)
    {
        $ratio = 1.0;
        if ($userid) {
            $member = $this->db_model->select_multi('*', 'member', array('id' => $userid));
            if ($member) {
                if (isset($member->plan_percentage) && (float)$member->plan_percentage > 0) {
                    return ((float)$member->plan_percentage) / 100;
                }
                if (!$packageid && !empty($member->signup_package)) {
                    $packageid = $member->signup_package;
                }
                if (!$packageid && !empty($member->join_package)) {
                    $packageid = $member->join_package;
                }
            }
        }

        if ($packageid) {
            $product = $this->db_model->select_multi('*', 'product', array('id' => $packageid));
            if ($product) {
                if (isset($product->plan_percentage) && (float)$product->plan_percentage > 0) {
                    return ((float)$product->plan_percentage) / 100;
                }
                $prod_name_lower = isset($product->prod_name) ? strtolower($product->prod_name) : '';
                if (strpos($prod_name_lower, '50%') !== false || strpos($prod_name_lower, '50 percent') !== false || strpos($prod_name_lower, 'half') !== false) {
                    return 0.50;
                }
            }
        }

        return $ratio;
    }

    public function get_retail_profit($mrp, $dp = 0)
    {
        if (is_numeric($mrp) && $dp > 0) {
            return (float)($mrp - $dp);
        }
        if (is_numeric($mrp)) {
            $product = $this->db_model->select_multi('*', 'product', array('id' => $mrp));
            if ($product) {
                $mrp_val = floatval($product->prod_price);
                $dp_val  = floatval($product->dealer_price);
                if ($mrp_val > 0 && $dp_val > 0) {
                    return $mrp_val - $dp_val;
                }
            }
        }
        return 0;
    }

    public function pay_earning($userid, $ref_id, $income_name, $amount, $levlno = 0, $pair_match = 0, $secret = 0)
    {
        $earning_ct    = $this->db_model->sum('amount', 'earning', array('userid' => $userid, 'type' => 'Matching Income', 'date' => date('Y-m-d'))) + 0;
        $pair_ct       = $this->db_model->count_all('pair_cuts', array('userid' => $userid,'DATE(date)' => date('Y-m-d')));
        $member        = $this->db_model->select_multi('signup_package, status, topup, earning_freeze', 'member', array('id' => $userid));
        $package       = $member ? $member->signup_package : null;

        $is_frozen = ($member && !empty($member->earning_freeze));
        $is_active = ($member && $package != null && $member->topup > 0 && $member->status == 'Active');

        if ($amount > 0 && $is_active && $is_frozen) {
            $this->db->insert('laps_earning', array(
                'userid' => $userid,
                'amount' => $amount,
                'type'   => $income_name,
                'reason' => 'Frozen',
            ));
            return TRUE;
        }

        if($amount > 0 and $is_active){
            $data = array(
                'userid'        => $userid,
                'amount'        => $amount,
                'type'          => $income_name,
                'ref_id'        => $ref_id,
                'date'          => date('Y-m-d'),
                'pair_match'    => $pair_match,
                'secret'        => $secret,
                'levlno'        => $levlno,
            );
            $this->db->insert('earning', $data);

            if($income_name == 'Matching Income'){
                $this->process_lvl($userid, $amount);
            }
        } else{
            if ($amount <= 0) {
                $reason = 'Zero / invalid amount';
            } elseif (!$member || $package == null) {
                $reason = 'User not active (not activated)';
            } elseif ($member->status != 'Active') {
                $reason = 'User not active (status: ' . $member->status . ')';
            } else {
                $reason = 'User not active (no topup)';
            }
            $data = array(
                'userid'        => $userid,
                'amount'        => $amount,
                'type'          => $income_name,
                'reason'        => $reason,
            );
            $this->db->insert('laps_earning', $data);
        }

        return TRUE;
    }

    public function process_lvl($userid, $amount){
        $lvl1_amt = (50 / 100) * $amount;
        $lvl2_amt = (50  / 100) * $amount;
        $amounts  = array($lvl1_amt, $lvl2_amt);
        $sponsor  = $this->db_model->select('sponsor', 'member', array('id' => $userid));
        $i        = 1;
        foreach ($amounts as $amount) {
            $amount = trim($amount);
            if ($i == 1) {
                $pay_gen_sponsor = $sponsor;
            } else {
                $pay_gen_sponsor = $this->find_sp_level_sponsor($userid, $i);
            }
            if ($pay_gen_sponsor > 0 && $amount > 0) {
                $amount = $amount * 1;
                $this->pay_earning($pay_gen_sponsor, $userid, 'Matching Sponsor Inc', $amount, $i);
            }
            $i++;
        }
    }

    public function is_eligible_for_booster($userid)
    {
        // Booster functionality is completely disabled in GreenAmrutAyurveda
        return false;
    }

    public function process_booster_bonus($userid, $sponsor_id = 0, $package_id = 0)
    {
        // Booster functionality is completely disabled in GreenAmrutAyurveda
        return false;
    }

    public function count_active_directs_by_leg($userid)
    {
        $user = $this->db_model->select_multi('A, B', 'member', array('id' => $userid));
        if (!$user) {
            return array('left' => 0, 'right' => 0);
        }

        $left_active = 0;
        if (!empty($user->A) && $user->A !== '0') {
            $left_active = $this->count_active_directs_in_subtree($user->A, $userid);
        }

        $right_active = 0;
        if (!empty($user->B) && $user->B !== '0') {
            $right_active = $this->count_active_directs_in_subtree($user->B, $userid);
        }

        return array('left' => $left_active, 'right' => $right_active);
    }

    private function count_active_directs_in_subtree($node_id, $sponsor_id)
    {
        $count = 0;
        $node = $this->db_model->select_multi('id, sponsor, topup, mypv, status', 'member', array('id' => $node_id));
        if ($node) {
            $is_active = ($node->topup > 0 || $node->mypv > 0 || $node->status == 'Active');
            if ($node->sponsor == $sponsor_id && $is_active) {
                $count++;
            }
            
            $children = $this->db->select('id')->from('member')->where('position', $node_id)->get()->result();
            foreach ($children as $child) {
                $count += $this->count_active_directs_in_subtree($child->id, $sponsor_id);
            }
        }
        return $count;
    }

    public function _log_binary_entry($function_name, $id)
    {
        $log_file = dirname(__DIR__) . DIRECTORY_SEPARATOR . 'logs' . DIRECTORY_SEPARATOR . 'payout_debug.log';
        if (!is_dir(dirname($log_file))) {
            @mkdir(dirname($log_file), 0777, true);
        }
        $entry = "========================================\n"
               . "[BINARY DEBUG]\n"
               . "TIME: " . date('Y-m-d H:i:s') . "\n"
               . "FUNCTION ENTERED: {$function_name}\n"
               . "USER ID: {$id}\n"
               . "========================================\n\n";
        @file_put_contents($log_file, $entry, FILE_APPEND);
        @file_put_contents(dirname(dirname(dirname(__DIR__))) . DIRECTORY_SEPARATOR . 'payout_debug.log', $entry, FILE_APPEND);
    }

    public function process_binary($id, $data, $debug = false)
    {
        $this->_log_binary_entry('process_binary', $id);

        $log_lines   = array();
        $log_lines[] = "========================================";
        $log_lines[] = "[BINARY DEBUG]";
        $log_lines[] = "TIME: " . date('Y-m-d H:i:s');
        $log_lines[] = "USER ID: {$id}";
        $log_lines[] = "FUNCTION: process_binary";
        $log_lines[] = "========================================";
        $log_lines[] = "";

        $member_data = $this->db_model->select_multi('*', 'member', array('id' => $id));
        if (!$member_data) {
            $log_lines[] = "FAIL: Member ID {$id} not found in member table.";
            $this->_write_payout_log($log_lines);
            return $debug ? $log_lines : false;
        }

        // 1. MEMBER INFORMATION
        $log_lines[] = "MEMBER INFORMATION:";
        $log_lines[] = "Member ID: " . $id;
        $log_lines[] = "Member Name: " . ($member_data->name ?? 'N/A');
        $log_lines[] = "Status: " . ($member_data->status ?? 'N/A');
        $log_lines[] = "Rank: " . ($member_data->rank ?? 'Member');
        $log_lines[] = "Topup: " . ($member_data->topup ?? 0);
        $log_lines[] = "Package: " . ($member_data->signup_package ?? 'N/A');
        $log_lines[] = "";

        // 2. TREE / PV INFORMATION
        $a_side      = (float) ($member_data->total_a_pv  ?? 0);
        $b_side      = (float) ($member_data->total_b_pv  ?? 0);
        $a_side_p    = (float) ($member_data->paid_a_pv   ?? 0);
        $b_side_p    = (float) ($member_data->paid_b_pv   ?? 0);
        $total_pair  = (int)   ($member_data->total_pairs ?? 0);
        $available_a = max(0, $a_side - $a_side_p);
        $available_b = max(0, $b_side - $b_side_p);
        $fa          = floor($available_a);
        $fb          = floor($available_b);

        $log_lines[] = "TREE / PV INFORMATION:";
        $log_lines[] = "Total Left PV: " . $a_side;
        $log_lines[] = "Paid Left PV: " . $a_side_p;
        $log_lines[] = "Available Left PV: " . $available_a;
        $log_lines[] = "";
        $log_lines[] = "Total Right PV: " . $b_side;
        $log_lines[] = "Paid Right PV: " . $b_side_p;
        $log_lines[] = "Available Right PV: " . $available_b;
        $log_lines[] = "";
        $log_lines[] = "Lifetime Paid Pairs: " . $total_pair;
        $log_lines[] = "";

        // 3. DIRECT SPONSOR INFORMATION (Informational ONLY)
        $directs = $this->count_active_directs_by_leg($id);
        $log_lines[] = "DIRECT SPONSOR INFORMATION:";
        $log_lines[] = "Direct Left: " . $directs['left'];
        $log_lines[] = "Direct Right: " . $directs['right'];
        $log_lines[] = "(Informational ONLY - Does not block 2:1 / 1:2 PV matching)";
        $log_lines[] = "";

        // 4. PAIR CALCULATION (Strictly 2:1 OR 1:2)
        $check_2_1_l = ($fa >= 2) ? "YES (Left = {$fa} >= 2)" : "NO (Left = {$fa} < 2)";
        $check_2_1_r = ($fb >= 1) ? "YES (Right = {$fb} >= 1)" : "NO (Right = {$fb} < 1)";

        $check_1_2_l = ($fa >= 1) ? "YES (Left = {$fa} >= 1)" : "NO (Left = {$fa} < 1)";
        $check_1_2_r = ($fb >= 2) ? "YES (Right = {$fb} >= 2)" : "NO (Right = {$fb} < 2)";

        $pairs          = 0;
        $deduct_a       = 0;
        $deduct_b       = 0;
        $selected_ratio = "NONE";

        if ($fa >= 2 && $fb >= 1 && $fa >= $fb) {
            $selected_ratio = "2:1";
            $pairs          = (int) min(floor($fa / 2), $fb);
            $deduct_a       = $pairs * 2;
            $deduct_b       = $pairs * 1;
        } elseif ($fb >= 2 && $fa >= 1 && $fb > $fa) {
            $selected_ratio = "1:2";
            $pairs          = (int) min($fa, floor($fb / 2));
            $deduct_a       = $pairs * 1;
            $deduct_b       = $pairs * 2;
        }

        $log_lines[] = "PAIR CALCULATION:";
        $log_lines[] = "BINARY RATIO CHECK:";
        $log_lines[] = "Left Available: " . $available_a;
        $log_lines[] = "Right Available: " . $available_b;
        $log_lines[] = "";
        $log_lines[] = "Checking 2:1:";
        $log_lines[] = "Left >= 2? " . $check_2_1_l;
        $log_lines[] = "Right >= 1? " . $check_2_1_r;
        $log_lines[] = "";
        $log_lines[] = "Checking 1:2:";
        $log_lines[] = "Left >= 1? " . $check_1_2_l;
        $log_lines[] = "Right >= 2? " . $check_1_2_r;
        $log_lines[] = "";
        $log_lines[] = "Selected Ratio:";
        $log_lines[] = $selected_ratio;
        $log_lines[] = "";
        $log_lines[] = "Pairs Calculated: " . $pairs;
        $log_lines[] = "Left PV To Consume: " . $deduct_a;
        $log_lines[] = "Right PV To Consume: " . $deduct_b;
        $top_id          = config_item('top_id');
        $is_root_company = (!empty($top_id) && $id == $top_id);
        $is_active_topup = ((float)$member_data->topup > 0 || $is_root_company);

        if ($pairs <= 0 || !$is_active_topup) {
            if (!$is_active_topup) {
                $log_lines[] = "TOPUP CHECK: FAILED (Topup = {$member_data->topup}, Needs > 0)";
                $log_lines[] = "";
            }
            $log_lines[] = "FINAL RESULT = NO PAIR FORMED";
            $log_lines[] = "Pairs Generated = 0";
            $log_lines[] = "Payout Generated = 0";
            $log_lines[] = "Left PV Remaining = " . $available_a;
            $log_lines[] = "Right PV Remaining = " . $available_b;
            $log_lines[] = "========================================\n";

            $this->_write_payout_log($log_lines);
            return $debug ? $log_lines : true;
        }

        // 5. PAYOUT CALCULATION (Configuration-Driven)
        $pkg_id = (int)($member_data->signup_package ?? 0);
        $prod   = null;
        if ($pkg_id > 0) {
            $prod = $this->db_model->select_multi('matching_income, capping, prod_price', 'product', array('id' => $pkg_id));
        }
        if (!$prod) {
            $prod = $this->db->order_by('id', 'ASC')->get('product')->row();
        }
        $per_pair  = 0;
        $cfg_val   = $prod ? ($prod->matching_income ?? 0) : 0;
        if ($prod && isset($prod->matching_income) && (float)$prod->matching_income > 0) {
            if ((float)$prod->matching_income <= 100 && isset($prod->prod_price) && (float)$prod->prod_price > 0) {
                $per_pair = (float)$prod->prod_price * ((float)$prod->matching_income / 100.0);
            } else {
                $per_pair = (float)$prod->matching_income;
            }
        }
        $daily_cap = ($prod && $prod->capping > 0) ? (float) $prod->capping : 0;

        if ($per_pair <= 0) {
            $log_lines[] = "--> PACKAGE CHECK FAILED: Package matching_income rate is 0 INR in product table.";
            $log_lines[] = "FINAL RESULT = PAYOUT FAILED";
            $log_lines[] = "========================================\n";
            $this->_write_payout_log($log_lines);
            return $debug ? $log_lines : false;
        }

        $cap_pairs        = ($daily_cap > 0 && $per_pair > 0) ? floor($daily_cap / $per_pair) : $pairs;
        $paid_pairs_today = (int)$this->db_model->sum('pair_match', 'earning', array(
            'userid' => $id,
            'type'   => 'Matching Income',
            'date'   => date('Y-m-d'),
        ));

        $remaining_cap = max(0, $cap_pairs - $paid_pairs_today);
        $payable_pairs = min($pairs, $remaining_cap);
        $flushed_pairs = $pairs - $payable_pairs;

        $pay_amount = 0;
        for ($i = 1; $i <= $payable_pairs; $i++) {
            $lifetime_pair_no = $total_pair + $i;
            if ($lifetime_pair_no % 5 === 0) {
                $pay_amount += $per_pair * 0.5;
            } else {
                $pay_amount += $per_pair;
            }
        }

        $log_lines[] = "PAYOUT CALCULATION:";
        $log_lines[] = "BINARY PAYOUT CALCULATION:";
        $log_lines[] = "Package ID: " . ($member_data->signup_package ?? 'N/A');
        $log_lines[] = "Matching Income Configuration: " . $cfg_val;
        $log_lines[] = "Payout Per Pair: " . $per_pair;
        $log_lines[] = "Daily Cap: " . $daily_cap;
        $log_lines[] = "Pairs Calculated: " . $pairs;
        $log_lines[] = "Pairs Allowed By Cap: " . $cap_pairs;
        $log_lines[] = "Payable Pairs: " . $payable_pairs;
        $log_lines[] = "Total Payout: " . $pay_amount;
        $log_lines[] = "";

        // 6. DATABASE TRANSACTION
        $log_lines[] = "DATABASE TRANSACTION:";
        $log_lines[] = "[BINARY TRANSACTION]";
        $log_lines[] = "BEGIN";
        $log_lines[] = "";

        $this->db->trans_begin();

        $tx_ref           = "BIN-" . $id . "-" . date('YmdHis') . "-" . rand(1000, 9999);
        $earning_id       = 0;
        $earning_ok       = false;
        $earning_err      = '';
        $wallet_ok        = false;
        $wallet_err       = '';
        $cur_bal          = 0;
        $new_bal          = 0;

        if ($pay_amount > 0) {
            // 1. EARNING INSERT
            $earning_data = array(
                'userid'     => $id,
                'amount'     => $pay_amount,
                'type'       => 'Matching Income',
                'ref_id'     => '',
                'date'       => date('Y-m-d'),
                'pair_match' => $payable_pairs,
                'secret'     => $tx_ref,
                'status'     => 'Paid',
            );
            $earning_ok = $this->db->insert('earning', $earning_data);
            $earning_id = $this->db->insert_id();
            if (!$earning_ok) {
                $db_err = $this->db->error();
                $earning_err = $db_err['message'] ?? 'Earning insert failed';
            }

            // 2. WALLET CREDIT
            $wallet_row = $this->db->get_where('wallet', array('userid' => $id))->row();
            if ($wallet_row) {
                $cur_bal = (float)$wallet_row->balance;
                $new_bal = $cur_bal + $pay_amount;
                $wallet_ok = $this->db->where('userid', $id)->update('wallet', array('balance' => $new_bal));
            } else {
                $cur_bal = 0;
                $new_bal = $pay_amount;
                $wallet_ok = $this->db->insert('wallet', array('userid' => $id, 'balance' => $new_bal));
            }
            if (!$wallet_ok) {
                $db_err = $this->db->error();
                $wallet_err = $db_err['message'] ?? 'Wallet credit failed';
            }
        } else {
            $earning_ok = true;
            $wallet_ok  = true;
        }

        // Flushed pairs to laps_earning (if daily cap exceeded)
        if ($flushed_pairs > 0) {
            $this->db->insert('laps_earning', array(
                'userid' => $id,
                'amount' => $flushed_pairs * $per_pair,
                'type'   => 'Matching Income',
                'reason' => 'Daily Flush Out (above ' . (int) $cap_pairs . ' pairs)',
            ));
        }

        // 3. PV CONSUMPTION & 4. LIFETIME PAIR UPDATE
        $new_paid_a = $a_side_p + $deduct_a;
        $new_paid_b = $b_side_p + $deduct_b;
        $new_pairs  = $total_pair + $payable_pairs;

        $member_update_ok = $this->db->where('id', $id)->update('member', array(
            'paid_a_pv'   => $new_paid_a,
            'paid_b_pv'   => $new_paid_b,
            'total_pairs' => $new_pairs,
        ));
        $member_err = '';
        if (!$member_update_ok) {
            $db_err = $this->db->error();
            $member_err = $db_err['message'] ?? 'Member PV update failed';
        }

        // 5. TRANSACTION COMMIT OR ROLLBACK
        if ($this->db->trans_status() === FALSE || !$earning_ok || !$wallet_ok || !$member_update_ok) {
            $db_err = $this->db->error();
            $this->db->trans_rollback();
            $tx_committed = false;
            $tx_error = $db_err['message'] ?? ($earning_err ?: ($wallet_err ?: ($member_err ?: 'Transaction query failed')));
        } else {
            $this->db->trans_commit();
            $tx_committed = true;
            if ($pay_amount > 0) {
                $this->process_lvl($id, $pay_amount);
            }
        }

        // Operation 1 Log
        $log_lines[] = "1. EARNING INSERT";
        $log_lines[] = "BINARY EARNING INSERT:";
        if ($earning_ok && $tx_committed) {
            $log_lines[] = "SUCCESS";
            $log_lines[] = "Insert ID: " . $earning_id;
            $log_lines[] = "User ID: " . $id;
            $log_lines[] = "Amount: " . $pay_amount;
            $log_lines[] = "Type: Matching Income";
            $log_lines[] = "Pair Count: " . $payable_pairs;
            $log_lines[] = "Transaction Reference: " . $tx_ref;
        } else {
            $log_lines[] = "FAILED";
            $log_lines[] = "DATABASE ERROR:";
            $log_lines[] = ($earning_err ?: ($tx_error ?? 'Transaction failed'));
        }
        $log_lines[] = "";

        // Operation 2 Log
        $log_lines[] = "2. WALLET CREDIT";
        $log_lines[] = "WALLET CREDIT:";
        if ($wallet_ok && $tx_committed) {
            $log_lines[] = "SUCCESS";
            $log_lines[] = "User ID: " . $id;
            $log_lines[] = "Previous Balance: " . $cur_bal;
            $log_lines[] = "Credit Amount: " . $pay_amount;
            $log_lines[] = "New Balance: " . $new_bal;
        } else {
            $log_lines[] = "FAILED";
            $log_lines[] = "DATABASE ERROR:";
            $log_lines[] = ($wallet_err ?: ($tx_error ?? 'Transaction failed'));
        }
        $log_lines[] = "";

        // Operation 3 Log
        $log_lines[] = "3. PV CONSUMPTION";
        $log_lines[] = "PV UPDATE:";
        $log_lines[] = "Before Paid Left: " . $a_side_p;
        $log_lines[] = "Before Paid Right: " . $b_side_p;
        $log_lines[] = "Left PV Consumed: " . ($tx_committed ? $deduct_a : 0);
        $log_lines[] = "Right PV Consumed: " . ($tx_committed ? $deduct_b : 0);
        $log_lines[] = "After Paid Left: " . ($tx_committed ? $new_paid_a : $a_side_p);
        $log_lines[] = "After Paid Right: " . ($tx_committed ? $new_paid_b : $b_side_p);
        $log_lines[] = "";

        // Operation 4 Log
        $log_lines[] = "4. LIFETIME PAIR UPDATE";
        $log_lines[] = "LIFETIME PAIR UPDATE:";
        $log_lines[] = "Before: " . $total_pair;
        $log_lines[] = "Added: " . ($tx_committed ? $payable_pairs : 0);
        $log_lines[] = "After: " . ($tx_committed ? $new_pairs : $total_pair);
        $log_lines[] = "";

        // Operation 5 Log
        $log_lines[] = "5. TRANSACTION";
        $log_lines[] = "BINARY TRANSACTION:";
        if ($tx_committed) {
            $log_lines[] = "COMMITTED";
        } else {
            $log_lines[] = "ROLLED BACK";
            $log_lines[] = "DATABASE ERROR:";
            $log_lines[] = ($tx_error ?? 'Transaction failed');
        }
        $log_lines[] = "";

        // Final Result Log
        $left_rem  = $tx_committed ? ($available_a - $deduct_a) : $available_a;
        $right_rem = $tx_committed ? ($available_b - $deduct_b) : $available_b;

        if ($tx_committed && $payable_pairs > 0) {
            $log_lines[] = "FINAL RESULT = PAYOUT GENERATED";
        } elseif ($tx_committed) {
            $log_lines[] = "FINAL RESULT = PAIRS CONSUMED (FLUSHED BY CAP)";
        } else {
            $log_lines[] = "FINAL RESULT = PAYOUT FAILED";
        }
        $log_lines[] = "Pairs Generated = " . ($tx_committed ? $payable_pairs : 0);
        $log_lines[] = "Payout Generated = " . ($tx_committed ? $pay_amount : 0);
        $log_lines[] = "Left PV Remaining = " . $left_rem;
        $log_lines[] = "Right PV Remaining = " . $right_rem;
        $log_lines[] = "========================================\n";

        $this->_write_payout_log($log_lines);
        return $debug ? $log_lines : $tx_committed;
    }

    private function _write_payout_log($lines)
    {
        $log_entry = implode("\n", $lines) . "\n";

        // Guaranteed absolute path in system/application/logs/
        $app_log_dir = dirname(__DIR__) . DIRECTORY_SEPARATOR . 'logs';
        if (!is_dir($app_log_dir)) {
            @mkdir($app_log_dir, 0777, true);
        }
        $app_log_file = $app_log_dir . DIRECTORY_SEPARATOR . 'payout_debug.log';
        $write_res = file_put_contents($app_log_file, $log_entry, FILE_APPEND);
        if ($write_res === false) {
            error_log("Failed to write to payout_debug.log at: " . $app_log_file);
        }

        // Also write to project root for convenient inspection
        $root_log_file = dirname(dirname(dirname(__DIR__))) . DIRECTORY_SEPARATOR . 'payout_debug.log';
        @file_put_contents($root_log_file, $log_entry, FILE_APPEND);

        foreach ($lines as $l) {
            log_message('error', $l);
        }
    }

    public function reverse_invalid_binary()
    {
        $this->db->select('userid, SUM(pair_match) as pairs_to_revert, SUM(amount) as total_amount, status')
                 ->from('earning')
                 ->where_in('type', array('Matching Income'))
                 ->group_by(array('userid', 'status'));
        $earners = $this->db->get()->result();

        $by_user = array();
        foreach ($earners as $e) {
            $uid = $e->userid;
            if (!isset($by_user[$uid])) {
                $by_user[$uid] = array('pairs' => 0, 'paid_amount' => 0);
            }
            $by_user[$uid]['pairs'] += (int) $e->pairs_to_revert;
            if ($e->status === 'Paid') {
                $by_user[$uid]['paid_amount'] += (float) $e->total_amount;
            }
        }

        $reversed = array();
        foreach ($by_user as $uid => $info) {
            $directs = $this->count_active_directs_by_leg($uid);
            if ($directs['left'] >= 1 && $directs['right'] >= 1) {
                continue;
            }

            $pairs       = $info['pairs'];
            $paid_amount = $info['paid_amount'];

            $this->db->select('userid, SUM(amount) as total_amount')
                     ->from('earning')
                     ->where('ref_id', $uid)
                     ->where('type', 'Matching Sponsor Inc')
                     ->where('status', 'Paid')
                     ->group_by('userid');
            $upline_paid = $this->db->get()->result();

            foreach ($upline_paid as $up) {
                $cur = (float) $this->db_model->select('balance', 'wallet', array('userid' => $up->userid));
                $new = max(0, $cur - (float) $up->total_amount);
                $this->db->where('userid', $up->userid)->update('wallet', array('balance' => $new));
            }

            $this->db->where('ref_id', $uid)
                     ->where('type', 'Matching Sponsor Inc')
                     ->delete('earning');

            $this->db->where('userid', $uid)
                     ->where('type', 'Matching Income')
                     ->delete('earning');

            if ($paid_amount > 0) {
                $cur = (float) $this->db_model->select('balance', 'wallet', array('userid' => $uid));
                $new = max(0, $cur - $paid_amount);
                $this->db->where('userid', $uid)->update('wallet', array('balance' => $new));
            }

            if ($pairs > 0) {
                $member        = $this->db_model->select_multi('total_pairs, paid_a_pv, paid_b_pv', 'member', array('id' => $uid));
                $current_total = (int) ($member->total_pairs ?? 0);

                if ($current_total <= $pairs) {
                    $this->db->where('id', $uid)
                             ->update('member', array('paid_a_pv' => 0, 'paid_b_pv' => 0, 'total_pairs' => 0));
                } else {
                    $this->db->set('paid_a_pv',   'GREATEST(0, paid_a_pv   - ' . $pairs . ')', FALSE);
                    $this->db->set('paid_b_pv',   'GREATEST(0, paid_b_pv   - ' . $pairs . ')', FALSE);
                    $this->db->set('total_pairs', 'GREATEST(0, total_pairs - ' . $pairs . ')', FALSE);
                    $this->db->where('id', $uid)->update('member');
                }
            }

            $reversed[] = $uid;
        }
        return $reversed;
    }

    public function process_binary_old($id, $data)
    {
        $this->_log_binary_entry('process_binary_old', $id);
        // Routed strictly to process_binary (2:1 or 1:2 ratio) to eliminate legacy 1:1 matching
        return $this->process_binary($id, $data);
    }

    public function process_binary2($id, $data)
    {
        $this->_log_binary_entry('process_binary2', $id);
        // Routed strictly to process_binary (2:1 or 1:2 ratio) to eliminate legacy 1:1 matching
        return $this->process_binary($id, $data);
    }
    
    public function reg_earning($userid, $sponsor, $packageid, $need_topup = TRUE, $qty = 1)
    {    
        $get_topup = floatval($this->db_model->select('topup', 'member', array('id' => $userid)));
        if ($need_topup == TRUE && $get_topup <= 0) {
            return TRUE; // Only trigger referral earnings when member is topped up / activated
        }
        if ($need_topup == TRUE || $get_topup > 0):
            $package_ratio = $this->get_package_ratio($packageid, $userid);
            $prod          = $this->db_model->select_multi('*', 'product', array('id' => $packageid)); 

            ###############################################################
            # Direct Sponsor Income (Configured % of Package Price - PDF 3)
            ##############################################################
            
            if ($prod && isset($prod->prod_price) && floatval($prod->prod_price) > 0 && trim($sponsor) !== '') { 
                $check_dup = $this->db_model->count_all('earning', array(
                    'userid' => $sponsor,
                    'ref_id' => $userid,
                    'type'   => 'Direct Sponsor Commission'
                ));
                if ($check_dup <= 0) {
                    $direct_pct  = (isset($prod->direct_income) && floatval($prod->direct_income) > 0)
                        ? floatval($prod->direct_income)
                        : 0;
                    if ($direct_pct > 0) {
                        $direct_rate = ($direct_pct <= 100) ? (floatval($prod->prod_price) * ($direct_pct / 100.0)) : $direct_pct;
                        $direct_amt  = ($direct_rate * $qty) * $package_ratio;
                        if ($direct_amt > 0) {
                            $this->pay_earning($sponsor, $userid, 'Direct Sponsor Commission', $direct_amt, 0);
                        }
                    }
                }
            } 
            
            ###############################################################
            # Direct Referral Bonus: 1st Level (Configured % - PDF 4 & 5)
            ##############################################################

            if ($prod && isset($prod->prod_price) && floatval($prod->prod_price) > 0 && trim($sponsor) !== '') {
                $check_dup_drb1 = $this->db_model->count_all('earning', array(
                    'userid' => $sponsor,
                    'ref_id' => $userid,
                    'type'   => 'Direct Referral Bonus',
                    'levlno' => 1
                ));
                if ($check_dup_drb1 <= 0) {
                    $drb_l1_pct = 0;
                    if (isset($prod->level_income) && trim($prod->level_income) !== '') {
                        $levels = explode(',', $prod->level_income);
                        $drb_l1_pct = floatval(trim($levels[0] ?? 0));
                    }
                    if ($drb_l1_pct > 0) {
                        $direct_bonus = (floatval($prod->prod_price) * ($drb_l1_pct / 100.0) * $qty) * $package_ratio; 
                        if ($direct_bonus > 0) { 
                            $this->pay_earning($sponsor, $userid, 'Direct Referral Bonus', $direct_bonus, 1);
                        }
                    }
                }
            }
            
            ###############################################################
            # Direct Referral Bonus: 2nd Level (Configured % - PDF 4 & 6)
            ##############################################################
            
            $lvl2_sponsor = $this->find_sp_level_sponsor($userid, 2);
            if ($lvl2_sponsor > 0 && $prod && isset($prod->prod_price) && floatval($prod->prod_price) > 0) {
                $check_dup_drb2 = $this->db_model->count_all('earning', array(
                    'userid' => $lvl2_sponsor,
                    'ref_id' => $userid,
                    'type'   => 'Direct Referral Bonus (Level 2)',
                    'levlno' => 2
                ));
                if ($check_dup_drb2 <= 0) {
                    $drb_l2_pct = 0;
                    if (isset($prod->level_income) && trim($prod->level_income) !== '') {
                        $levels = explode(',', $prod->level_income);
                        $drb_l2_pct = floatval(trim($levels[1] ?? 0));
                    }
                    if ($drb_l2_pct > 0) {
                        $lvl2_bonus = (floatval($prod->prod_price) * ($drb_l2_pct / 100.0) * $qty) * $package_ratio;
                        if ($lvl2_bonus > 0) {
                            $this->pay_earning($lvl2_sponsor, $userid, 'Direct Referral Bonus (Level 2)', $lvl2_bonus, 2);
                        }
                    }
                }
            } 

            ###############################################################
            # Sponsor Level Income (Configured in sponser_level_inc)
            ##############################################################
            if (isset($prod->sponser_level_inc) && trim($prod->sponser_level_inc) !== '') {
                $ex1 = explode(',', $prod->sponser_level_inc);
                $i   = 1;
                foreach ($ex1 as $e1) {
                    $e1 = trim($e1);
                    $pay_gen_sponsor = ($i == 1) ? $sponsor : $this->find_sp_level_sponsor($userid, $i);
                    if ($pay_gen_sponsor > 0 && floatval($e1) > 0) {
                        $check_dup_sli = $this->db_model->count_all('earning', array(
                            'userid' => $pay_gen_sponsor,
                            'ref_id' => $userid,
                            'type'   => 'Sponsor Level Inc',
                            'levlno' => $i
                        ));
                        if ($check_dup_sli <= 0) {
                            $rate_sli = (floatval($e1) <= 100) ? (floatval($prod->prod_price) * (floatval($e1) / 100.0)) : floatval($e1);
                            $amt_sli  = ($rate_sli * $qty) * $package_ratio;
                            if ($amt_sli > 0) {
                                $this->pay_earning($pay_gen_sponsor, $userid, 'Sponsor Level Inc', $amt_sli, $i);
                            }
                        }
                    }
                    $i++;
                }
            }

            ###############################################################
            #
            #This function distributes the Repurchase Income across 15 levels of upline positions
            #Akki Bhoite
            ##############################################################
            
            /* Repurchase Income commented out
            $purchasecount = $this->db_model->count_all('product_sale', ['payment_sataus' => 'success','userid' => $userid]);

            if ($purchasecount == 1) { $qty = $qty - 1; }

            if ($purchasecount >= 1 && $qty >= 1) 
            {
                if (!empty($data->prod_price) && $data->prod_price > 0) {

                    for ($level = 1; $level <= 15; $level++) {

                        $pay_up_position_id = $this->find_level_sponsor($userid, $level);
                        if (!$pay_up_position_id) continue; 

                        switch ($level) {
                            case 1:  $amount = $data->prod_price * 0.03; break;
                            case 2:  $amount = $data->prod_price * 0.02; break;
                            case 3:
                            case 4:  $amount = $data->prod_price * 0.015; break;
                            case 5:  $amount = $data->prod_price * 0.01; break;
                            case 6:  $amount = $data->prod_price * 0.005; break;
                            case 7:
                            case 8:
                            case 9:
                            case 10:
                            case 11:
                            case 12: $amount = $data->prod_price * 0.0025; break;
                            case 13:
                            case 14: $amount = $data->prod_price * 0.01; break;
                            case 15: $amount = $data->prod_price * 0.02; break;
                            default: $amount = 0; break;
                        }

                        $amt = $amount * $qty;
                        $pos_user_topup = $this->db_model->select('topup', 'member', ['id' => $pay_up_position_id]);

                        if ($pay_up_position_id > 0) 
                        {
                            if ($pos_user_topup > 0) {
                                $this->pay_earning($pay_up_position_id, $userid, 'Repurchase Income', $amt, $level);
                            } 
                            else {
                                $rec = [
                                    'userid' => $pay_up_position_id,
                                    'amount' => $amt,
                                    'type'   => 'Repurchase Income',
                                    'reason' => 'Member not activated',
                                    'date' => date('Y-m-d'),
                                    'ref_id' => $userid,
                                    'levlno' => $level,
                                ];
                                $this->db->insert('laps_earning', $rec);
                            }
                        }
                    }
                }
            }
            */

            ####End 
            
        endif;  
        return TRUE;
    }

	public function update_legs()
	{
		$this->db->select('*')->from('member');
		$data = $this->db->get()->result();
		foreach ($data as $result) {
			if ($result->A !== '0') {
				$total_a_pv   = $this->count_pv($result->A) + $this->db_model->select('mypv', 'member', array('id' => $result->A)) + $this->db_model->select('team_power', 'member', array('id' => $result->A));
				$count_a      = $this->count_node($result->A);
			}
			else {
				$count_a      = 0;
				$total_a_pv   = 0;
			}
			if ($result->B !== '0') {
				$total_b_pv   = $this->count_pv($result->B) + $this->db_model->select('mypv', 'member', array('id' => $result->B)) + $this->db_model->select('team_power', 'member', array('id' => $result->B));
				$count_b      = $this->count_node($result->B);
			}
			else {
				$count_b      = 0;
				$total_b_pv   = 0;
			}
			$left_pv  = $total_a_pv + $result->self_power_a;
			$right_pv = $total_b_pv + $result->self_power_b;
			$data     =  array('total_a_pv'=> $left_pv,'total_b_pv'=> $right_pv,'total_a' => $count_a,'total_b' => $count_b);
			$this->db->where('id', $result->id);
			$this->db->update('member', $data);
		}
	}

	private function count_pv($id, $i = 0)
	{
		$this->db->select('id, mypv')->where('position', $id);
		$data = $this->db->get('member')->result();
		$countdata = $this->db_model->sum('mypv', 'member', array('position' => $id, 'mypv !=' => '0')) + $this->db_model->sum('team_power', 'member', array('position' => $id, 'team_power !=' => '0'));
		$i = $i + $countdata;

		foreach ($data as $result) {
			if ($result->id) {
				$i = $this->count_pv($result->id, $i);
			}
		}
		return $i;
	}

	private function count_node($id, $i = 0)
	{
		if ($i == 0) {
			$top_up = $this->db_model->sum('cost', 'product_sale',array('userid' => $id));

			if (1 <= $top_up) {
				$i = $i + 1;
			}
		}

		$this->db->select('id,topup')->where('position', $id);
		$data = $this->db->get('member')->result();
		$countdata = $this->db_model->count_all('member', array('position' => $id, 'topup >=' => '1'));
		$i = $i + $countdata;

		foreach ($data as $result) {
			if ($result->id) {
				$i = $this->count_node($result->id, $i);
			}
		}
		return $i;
	}

	private function count_node2($id, $i = 0)
	{
		if ($i == 0) {
			$top_up = $this->db_model->select('total_pairs', 'member', array('id' => $id));

			if (45 <= $top_up) {
				$i = $i + 1;
			}
		}

		$this->db->select('id,total_pairs')->where('position', $id);
		$data = $this->db->get('member')->result();
		$countdata = $this->db_model->count_all('member', array('position' => $id, 'total_pairs >=' => 45));
		$i = $i + $countdata;

		foreach ($data as $result) {
			if ($result->id) {
				$i = $this->count_node2($result->id, $i);
			}
		}
		return $i;
	}

	private function count_node3($id, $i = 0)
	{
		if ($i == 0) {
			$top_up = $this->db_model->select('total_pairs', 'member', array('id' => $id));

			if (60 <= $top_up) {
				$i = $i + 1;
			}
		}

		$this->db->select('id,total_pairs')->where('position', $id);
		$data = $this->db->get('member')->result();
		$countdata = $this->db_model->count_all('member', array('position' => $id, 'total_pairs >=' => 60));
		$i = $i + $countdata;

		foreach ($data as $result) {
			if ($result->id) {
				$i = $this->count_node2($result->id, $i);
			}
		}
		return $i;
	}

    // private function count_pv2($id, $i = 0, $sponsor)
    // {
    //     $this->db->select('id, mypv')->where('position', $id)->where('sponsor', $sponsor);
    //     $data = $this->db->get('member')->result();
    //     $countdata = $this->db_model->sum('mypv', 'member', array('position' => $id, 'topup >' => '0','sponsor' => $sponsor));
    //     $i = $i + $countdata;
    //     foreach ($data as $result) {
    //         if ($result->id) {
    //             $i = $this->count_pv2($result->id, $i, $sponsor);
    //         }
    //     }
    //     return $i;
    // }


	private function count_pv2($id, $i = 0)
	{
		$this->db->select('id,mypv')->where('position', $id);
		$data = $this->db->get('member')->result();
		$countdata = $this->db_model->sum('mypv', 'member', array('position' => $id, 'mypv !=' => '0'));
		$i = $i + $countdata;

		foreach ($data as $result) {
			if ($result->id) {
				$i = $this->count_pv($result->id, $i);
			}
		}
		return $i;
	}

    public function count_investment($id, $i = 0)
    {
        $this->db->select('id,topup')->where('position', $id);
        $data      = $this->db->get('member')->result();
        $countdata = $this->db_model->sum('topup', 'member', array('position' => $id, 'topup >' => '0'));
        $i         = $i + $countdata;
        foreach ($data as $result) {
            if ($result->id) {
                $i = $this->count_investment($result->id, $i);
            }
        }
        return $i;
    }

    public function count_matching($id, $i = 0)
    {
        $this->db->select('id,my_business')->where('position', $id);
        $data = $this->db->get('member')->result();
        $countdata = $this->db_model->sum('my_business', 'member', array('position' => $id, 'my_business !=' => '0'));
        $i = $i + $countdata;
        foreach ($data as $result) {
            if ($result->id) {
                $i = $this->count_matching($result->id, $i);
            }
        }
        return $i;
    }

    public function sponcer_roi()
    {
        /* Sponcer ROI commented out
        $this->db->select('*')->from('member')->where(array('topup >' => 0));
        $users = $this->db->get()->result();
        foreach ($users as $users){
            $roi          = $this->db_model->select('roi', 'product', array('id' => $users->signup_package));
            $currentMonth = date('m');
            $currentYear  = date('Y');
            $data_count   = $this->db_model->count_all('earning', array('userid' => $users->sponsor,'ref_id' => $users->id,'date >= ' => "$currentYear-$currentMonth-01",'date < ' => date('Y-m-d', strtotime("$currentYear-$currentMonth-01 +1 month"))));
            if($data_count <= 0){
                $this->pay_earning($users->sponsor, $users->id, 'Sponcer Roi', $roi,$users->signup_package, 1);
            }
        }
        */
        return true;
    }

    public function roi_earning()
    {
        /* ROI Earning commented out
        $this->db->select('id,signup_package')->from('member')->where(array('topup >' => 0));
        $users = $this->db->get()->result();
        foreach ($users as $users):
            $userid = $users->id;
            $roi    = $this->db_model->select_multi('roi,roi_limit,roi_frequency', 'product', array('id' => $users->signup_package));
            $sp     = $this->db_model->select('sponsor', 'product', array('id' => $users->signup_package));
            $roi_frequency = $roi->roi_frequency;
            $roi_limit     = $roi->roi_limit;
            $roi           = $roi->roi;
          
            if ($roi > 0) {
                $get_last_roi = $this->db_model->select('date', 'earning', array(
                    'userid' => $userid,
                    'type'   => 'ROI',
                    'secret' => $users->signup_package,
                ));
                $date = date('Y-m-d', strtotime($get_last_roi) + (86400 * $roi_frequency));
       
               if (date('Y-m-d') > $date) {
                    if ($this->db_model->count_all('earning', array( 
                            'userid' => $userid,
                            'type' => 'ROI',
                            'secret' => $users->signup_package,
                        )) < $roi_limit) {
                        $levlno='0';
                        $trans_id = rand(1000000,9999999);
                        $this->pay_earning($userid, $trans_id, 'ROI', $roi,$users->signup_package, $levlno);
                    }
                }
            }
 
        endforeach;
        */
        return true;
    }
	
public function travel_fund($data){
        $per_cal=$data['10']*(3/100);
        if($per_cal>0){
            $total=$data['1']+$data['2']+$data['3'];
            $cal_per=$per_cal*(9/100);
            $calculation=$cal_per/$total;

            $total1=$data['4']+$data['5']+$data['6']+$data['7'];
            $cal_per3=$per_cal*(10/100);
            $calculation1=$cal_per3/$total1;

            $total3=$data['8'];
            $cal_per5=$per_cal*(11/100);
            $calculation3=$cal_per5/$total3;

            $total4=$data['9'];
            $cal_per6=$per_cal*(12/100);
            $calculation4=$cal_per6/$total4;
    
            $this->db->select('*')->from('member');
            $data1 = $this->db->get()->result();
            foreach ($data1 as $result){
                if($result->rank=="PEARL DIRECTOR" || $result->rank=="SAPPHIRE DIRECTOR" || $result->rank=="EMERALD DIRECTOR"){
                    $this->pay_earning($result->id,1, 'TRAVELFUND',$calculation,1);
                }elseif($result->rank=="CROWN DIRECTOR" || $result->rank=="SILVER CROWN DIRECTOR" || $result->rank=="GOLD CROWN DIRECTOR" || $result->rank=="DIAMOND CROWN DIRECTOR"){
                    $this->pay_earning($result->id,1, 'TRAVELFUND',$calculation1,1);
                }elseif($result->rank=="UNIVERSAL CROWN DIRECTOR"){
                    $this->pay_earning($result->id,1, 'TRAVELFUND',$calculation3,1);
                }elseif($result->rank=="GALAXIAN"){
                    $this->pay_earning($result->id,1, 'TRAVELFUND',$calculation4,1);
            }
        }   
    }
    $this->car_fund($data);
}
public function car_fund($data){
        $per_cal=$data['10']*(3/100);
            if($per_cal>0){
                $total=$data['2']+$data['3']+$data['4']+$data['5'];
                $cal_per=$per_cal*(10/100);
                $calculation=$cal_per/$total;

                $total1=$data['6'];
                $cal_per3=$per_cal*(12/100);
                $calculation1=$cal_per3/$total1;

                $total1=$data['7'];
                $cal_per3=$per_cal*(13/100);
                $calculation2=$cal_per3/$total1;

                $total3=$data['8'];
                $cal_per5=$per_cal*(14/100);
                $calculation3=$cal_per5/$total3;

                $total4=$data['9'];
                $cal_per6=$per_cal*(15/100);
                $calculation4=$cal_per6/$total4;
    
                $this->db->select('*')->from('member');
                $data1 = $this->db->get()->result();
                foreach ($data1 as $result){
                if($result->rank=="SAPPHIRE DIRECTOR" || $result->rank=="EMERALD DIRECTOR" || $result->rank=="CROWN DIRECTOR" || $result->rank=="SILVER CROWN DIRECTOR"){
                    $this->pay_earning($result->id,1, 'CARFUND',$calculation,1);
                }elseif($result->rank=="GOLD CROWN DIRECTOR"){
                    $this->pay_earning($result->id,1, 'CARFUND',$calculation1,1);
                }elseif($result->rank=="DIAMOND CROWN DIRECTOR"){
                        $this->pay_earning($result->id,1, 'CARFUND',$calculation2,1);
                }elseif($result->rank=="UNIVERSAL CROWN DIRECTOR"){
                    $this->pay_earning($result->id,1, 'CARFUND',$calculation3,1);
                }elseif($result->rank=="GALAXIAN"){
                    $this->pay_earning($result->id,1, 'CARFUND',$calculation4,1);
                            }
        }   
    }
    $this->family_fund($data);
}
public function family_fund($data){
            $per_cal=$data['10']*(3/100);
            if($per_cal>0){
                $total=$data['3']+$data['4']+$data['5']+$data['6'];
                $cal_per=$per_cal*(12/100);
                $calculation=$cal_per/$total;
                
                $total1=$data['7'];
                $cal_per3=$per_cal*(13/100);
                $calculation2=$cal_per3/$total1;
            
                $total3=$data['8'];
                $cal_per5=$per_cal*(14/100);
                $calculation3=$cal_per5/$total3;
            
                $total4=$data['9'];
                $cal_per6=$per_cal*(15/100);
                $calculation4=$cal_per6/$total4;
        
                $this->db->select('*')->from('member');
                $data1 = $this->db->get()->result();
                foreach ($data1 as $result){
                if($result->rank=="EMERALD DIRECTOR" || $result->rank=="CROWN DIRECTOR" || $result->rank=="SILVER CROWN DIRECTOR" || $result->rank=="GOLD CROWN DIRECTOR"){
                    $this->pay_earning($result->id,1, 'FAMILYFUND',$calculation,1);
                }elseif($result->rank=="DIAMOND CROWN DIRECTOR"){
                        $this->pay_earning($result->id,1, 'FAMILYFUND',$calculation2,1);
                }elseif($result->rank=="UNIVERSAL CROWN DIRECTOR"){
                    $this->pay_earning($result->id,1, 'FAMILYFUND',$calculation3,1);
                }elseif($result->rank=="GALAXIAN"){
                    $this->pay_earning($result->id,1, 'FAMILYFUND',$calculation4,1);
                            }
                        }   
                    }
    }
    
    public function process_binary_old_old($id, $data)
    {
        $this->_log_binary_entry('process_binary_old_old', $id);
        $total_pair    = $this->db_model->select('paid_pairs', 'member', array('id' => $id));
        $a_side        = $this->db_model->select('tree_a', 'member', array('id' => $id));
        $b_side        = $this->db_model->select('tree_b', 'member', array('id' => $id));
        $check         = FALSE;
        if ($total_pair == 0 and $a_side != 0 and $b_side != 0) {
        
            $array = array(
                'paid_pairs' => 1
            );
        
            if ($a_side > $b_side && $b_side != 0) {
                $array['tree_a_paid'] = 2;
                $array['tree_b_paid'] = 1;
                $check         = TRUE;
            } elseif ($b_side > $a_side && $a_side != 0) {
                $array['tree_a_paid'] = 1;
                $array['tree_b_paid'] = 2;
                $check         = TRUE;
            } elseif ($b_side + $a_side >= 3) {
                $array['tree_a_paid'] = 1;
                $array['tree_b_paid'] = 2;
                $check         = TRUE;
            }
            
            if($check){
                $this->db->where('id', $id);
                $this->db->update('member', $array);
                // Booster Matching Income disabled - Booster = ZERO
            }
        }

        $total_pair    = $this->db_model->select('paid_pairs', 'member', array('id' => $id));
        
        if($total_pair > 0){
            $a_side         = $this->db_model->select('tree_a', 'member', array('id' => $id));
            $b_side         = $this->db_model->select('tree_b', 'member', array('id' => $id)); 
            $a_side_p       = $this->db_model->select('tree_a_paid', 'member', array('id' => $id));
            $b_side_p       = $this->db_model->select('tree_b_paid', 'member', array('id' => $id)); 
            $pair_match     = min(($a_side - $a_side_p), ($b_side - $b_side_p));
            $pair_max       = max(($a_side - $a_side_p), ($b_side - $b_side_p));
            $pair_match_inc = $pair_match * 100;
            if($pair_match >= 0 and $pair_max >= 0 ){
                $array = array(
                    'tree_a_paid'     => $a_side_p   + $pair_match,
                    'tree_b_paid'     => $b_side_p   + $pair_match,
                    'paid_pairs'      => $total_pair + $pair_match,
                );
                $this->db->where('id', $id);
                $this->db->update('member', $array);
            }
            $i = 0;
            while($i < $pair_match){
                $i++;
                if($pair_match > 0 and $pair_max > 0 ){
                    // Booster Matching Income disabled - Booster = ZERO
                }
            }
        }
    }
    
    
    public function process_binary3($id, $data)
    {
        $this->_log_binary_entry('process_binary3', $id);
        $total_pair    = $this->db_model->select('re_paid_pairs', 'member', array('id' => $id));
        $a_side        = $this->db_model->select('re_tree_a', 'member', array('id' => $id));
        $b_side        = $this->db_model->select('re_tree_b', 'member', array('id' => $id));
        $check         = FALSE;
        if ($total_pair == 0 and $a_side != 0 and $b_side != 0) {
        
            $array = array(
                're_paid_pairs' => 1
            );
        
            if ($a_side > $b_side && $b_side != 0) {
                $array['re_tree_a_paid'] = 2;
                $array['re_tree_b_paid'] = 1;
                $check         = TRUE;
            } elseif ($b_side > $a_side && $a_side != 0) {
                $array['re_tree_a_paid'] = 1;
                $array['re_tree_b_paid'] = 2;
                $check         = TRUE;
            } elseif ($b_side + $a_side >= 3) {
                $array['tree_a_paid'] = 1;
                $array['re_tree_b_paid'] = 2;
                $check         = TRUE;
            }
            
            if($check){
                $this->db->where('id', $id);
                $this->db->update('member', $array);
                $stat =  $this->pay_earning($id, '0000', 'Auto Repurchase Matching Income',40,'',$total_pair);
            }
        }

        $total_pair    = $this->db_model->select('re_paid_pairs', 'member', array('id' => $id));
        
        if($total_pair > 0){
            $a_side         = $this->db_model->select('re_tree_a', 'member', array('id' => $id));
            $b_side         = $this->db_model->select('re_tree_b', 'member', array('id' => $id)); 
            $a_side_p       = $this->db_model->select('re_tree_a_paid', 'member', array('id' => $id));
            $b_side_p       = $this->db_model->select('re_tree_b_paid', 'member', array('id' => $id)); 
            $pair_match     = min(($a_side - $a_side_p), ($b_side - $b_side_p));
            $pair_max       = max(($a_side - $a_side_p), ($b_side - $b_side_p));
            $pair_match_inc = $pair_match * 40;
            if($pair_match >= 0 and $pair_max >= 0 ){
                $array = array(
                    're_tree_a_paid'     => $a_side_p   + $pair_match,
                    're_tree_b_paid'     => $b_side_p   + $pair_match,
                    're_paid_pairs'         => $total_pair + $pair_match,
                );
                $this->db->where('id', $id);
                $this->db->update('member', $array);
            }
            $i = 0;
            while($i < $pair_match){
                $i++;
                if($pair_match > 0 and $pair_max > 0 ){
                    $this->pay_earning($id, '', 'Auto Repurchase Matching Income',40,'',$pair_match);
                }
            }
        }
    }
    
    
    // public function process_binary($id, $data)
    // {
    //     $pair_match     = min(($data['total_a_pv'] - $data['paid_a_pv']), ($data['total_b_pv'] - $data['paid_b_pv']));
    //     $pair_max       = max(($data['total_a_pv'] - $data['paid_a_pv']), ($data['total_b_pv'] - $data['paid_b_pv']));
    //     $pair_match_inc = $pair_match * 500;
        
    //     if($pair_match >= 0 and $pair_max >= 0 ){
    //         $array = array(
    //             'paid_a_pv'        => $data['paid_a_pv'] + $pair_match,
    //             'paid_b_pv'        => $data['paid_b_pv'] + $pair_match,
    //         );
    //         $this->db->where('id', $id);
    //         $this->db->update('member', $array);
    //     }

    //     if($pair_match > 0 and $pair_max > 0 ){
    //         $this->pay_earning($id, '', 'Matching Income',$pair_match_inc,'',$pair_match);
    //     }
    // }
 
    public function repurchase($order_id)
    {
        $this->db->select('*')->from('product_sale')->where(array('orderid'=> $order_id));
        $order_data = $this->db->get()->result();
        foreach($order_data as $order_detail){
            $userid      = $order_detail->userid;
            $product_id  = $order_detail->product_id;
            $qty         = $order_detail->qty;
            $cost        = $order_detail->cost;
            $totalCost   = $cost;
            $direct_inc  = 0.20 * $totalCost;
            // echo $direct_inc;die();
            $sponsor     = $this->db_model->select('position', 'member', array('id' => $userid));
            $sponsor2    = $this->db_model->select('sponsor', 'member', array('id' => $userid));
            // if ($direct_inc > "0" && trim($sponsor2) !== '' and $order_detail->is_repurchase == 1) {
            //     $this->pay_earning($sponsor2, $userid, 'Referral Income', $direct_inc);
            // }
            $this->repurchase_earning($userid, $sponsor, $product_id, $qty);
        }

    }


    public function repurchase_earning($userid, $sponsor, $packageid, $qty)
    {  
        /* Repurchase Earning commented out
        var_dump($userid);
        var_dump(config_item('enable_gap_commission') !== "Yes");
                if (config_item('enable_gap_commission') !== "Yes") {
            $this->reg_earning($userid, $sponsor, $packageid, false, $qty);
        } else {

            $data     = $this->db_model->select_multi('mypv, total_a_pv, total_b_pv, total_c_pv, total_d_pv, total_e_pv', 'member', array('id' => $userid));
            $total_pv = $data->total_a_pv + $data->total_b_pv + $data->total_c_pv + $data->total_d_pv + $data->total_e_pv + $data->mypv;
            $this->db->where(array('total_pv <=' => $total_pv,)); 
            $this->db->order_by('id', 'DESC');
            $result        = $this->db->get('gap_commission_setting')->result();
            $dataxs        = $this->db_model->select_multi('prod_price,dealer_price,purchase_price,pv,direct_income, level_income', 'product', array('id' => $packageid));
            $pv            = $dataxs->pv;
            $product_price = $dataxs->prod_price;
            $dealer_price=$dataxs->dealer_price;
            $profit_amt=$dataxs->purchase_price;
            if ($pv > "0") {
                $mypv = $data->mypv + ($pv * $qty);
                $arr  = array('mypv' => $mypv);

                $this->db->where('id', $userid);
                $this->db->update('member', $arr);
            }

                $dt1=date('Y-m-01');
                $dt2=date('Y-m-d');
               
                    $i = 1;
                    foreach ($result as $e) {
                        if ($i == 0) {
                            $pay_sponsor = $sponsor;
                         } else {
                            $pay_sponsor = $this->find_level_sponsor($userid, $i);                       
                         } 
                     
                        
                        
                       var_dump($pay_sponsor > 0);
                        if ($pay_sponsor > 0) {
                            
                            $amt = $e->amount;
                         var_dump($amt);
                         var_dump($pay_sponsor);

                            if ($amt > 0) {
                                $levlno=$i;
                                  
                                $this->pay_earning($pay_sponsor, $userid, $e->income_name, $amt,$levlno);
                            }
                        }
                        $i++;
                    }
        }
        */
        return true;
    }

     

    // public function repurchase_earning($userid, $sponsor, $packageid, $qty)
    // {
    //     if (config_item('enable_gap_commission') !== "Yes") {
    //         $this->reg_earning($userid, $sponsor, $packageid, false, $qty);
    //     } else {
    //         $data     = $this->db_model->select_multi('mypv, total_a_pv, total_b_pv, total_c_pv, total_d_pv, total_e_pv', 'member', array('id' => $userid));
    //         $total_pv = $data->total_a_pv + $data->total_b_pv + $data->total_c_pv + $data->total_d_pv + $data->total_e_pv + $data->mypv;
    //         $this->db->where(array(
    //                              'total_pv <=' =>
    //                                  $total_pv,
    //                          ));
    //         $this->db->order_by('id', 'DESC');
    //         $result        = $this->db->get('gap_commission_setting')->row();
    //         $dataxs        = $this->db_model->select_multi('prod_price,pv,direct_income, level_income', 'product', array('id' => $packageid));
    //         $pv            = $dataxs->pv;
    //         $product_price = $dataxs->prod_price;
    //         if ($pv > "0") { 
    //             $mypv = $data->mypv + ($pv * $qty);
    //             $arr  = array('mypv' => $mypv);
    //             $this->db->where('id', $userid);
    //             $this->db->update('member', $arr);
    //         }
    //         if ($dataxs->direct_income > "0" && trim($sponsor) !== '') {
    //             $this->pay_earning($sponsor, $userid, 'Referral Income', $dataxs->direct_income);
    //         }
    //         $levlno="0";
    //         $earning = ($product_price * $result->amount / 100) * $qty;
    //         $this->pay_earning($userid, 'Self', $result->income_name, $earning,$levlno);
    //         $this->db->select('amount, income_name');
    //         $this->db->where(array(
    //                              'total_pv <=' =>
    //                              $total_pv,
    //          ));
    //         $this->db->order_by('id', 'DESC');
    //         $ex = $this->db->get('gap_commission_setting')->result();
    //         var_dump($ex);die();
    //         $i = 0;
    //         foreach ($ex as $e) {
    //             $e = trim($e);
    //             if ($i == 0) {
    //                 $pay_sponsor = $sponsor;
    //             } else {
    //                 $pay_sponsor = $this->find_level_sponsor($userid, $i);
    //             }
    //             if ($pay_sponsor > 0) {
    //                 $amt = ($product_price * ($result->amount - $e->amount) / 100) * $qty;
    //                 if ($amt > 0) {
    //                     $levlno=$i;
    //                     $this->pay_earning($pay_sponsor, $userid, $e->income_name, $amt,$levlno);
    //                 }
    //             }
    //             $i++;
    //         }
    //     }
    // }

    /**
     * @param $userid
     * @param $sponsor
     * @param $packageid
     * @param bool $need_topup
     * @param int $qty
     * @return bool
     */

    private function find_level_sponsor($sponsor, $i)
    {
      
        if ($i > 0) {
            
            #For Matrix Level 
            $this->db->select('position')->from('member')->where(array('id' => $sponsor));
            $result = $this->db->get()->row();

            if (!$result) {
                return FALSE;
            } else {
                $i = ($i - 1);
                 #For Positional Level Sponsor               
                return $this->find_level_sponsor($result->position, $i);                 
            }

        } else {
            return $sponsor;
        }
    }

    private function find_sp_level_sponsor($sponsor, $i)
    {      
        if ($i > 0) {
           
            $this->db->select('sponsor')->from('member')->where(array('id' => $sponsor));
            $result = $this->db->get()->row();

            if (!$result) {
                return FALSE;
            } else {
                $i = ($i - 1);
                 #For Sponsored Level 
                return $this->find_sp_level_sponsor($result->sponsor, $i);               
            }

        } else {
            return $sponsor;
        }
    }

    private function find_first_pool_sponsor($sponsor, $i)
    {      
        if ($i > 0) {            
            #For Matrix Level 
         
            $this->db->select('position')->from('member')->where(array('id' => $sponsor),array('topup' => 3000));

            $result = $this->db->get()->row();
           
            if (!$result) {
                return FALSE;
            } else {
                $i = ($i - 1);
                 #For Positional Level Sponsor               
                return $this->find_first_pool_sponsor($result->position, $i);                 
            }

        } else {
            return $sponsor;
        }
    }

    private function find_second_pool_sponsor($sponsor, $i)
    {      
        if ($i > 0) {            
            #For Matrix Level 
        
            $this->db->select('position')->from('member')->where(array('id' => $sponsor),array('topup' => 20000));

            $result = $this->db->get()->row();
           
            if (!$result) {
                return FALSE;
            } else {
                $i = ($i - 1);
                 #For Positional Level Sponsor               
                return $this->find_second_pool_sponsor($result->position, $i);                 
            }

        } else {
            return $sponsor;
        }
    }

   private function find_third_pool_sponsor($sponsor, $i)
    {      
        if ($i > 0) {            
            #For Matrix Level 
         
            $this->db->select('position')->from('member')->where(array('id' => $sponsor),array('topup' => 100000));

            $result = $this->db->get()->row();
           
            if (!$result) {
                return FALSE;
            } else {
                $i = ($i - 1);
                 #For Positional Level Sponsor               
                return $this->find_third_pool_sponsor($result->position, $i);                 
            }

        } else {
            return $sponsor;
        }
    }

    private function find_fourth_pool_sponsor($sponsor, $i)
    {      
        if ($i > 0) {     

            #For Matrix Level          
            $this->db->select('position')->from('member')->where(array('id' => $sponsor),array('topup' => 500000));
            $result = $this->db->get()->row();
           
            if (!$result) {
                return FALSE;
            } else {
                $i = ($i - 1);
                #For Positional Level Sponsor               
                return $this->find_fourth_pool_sponsor($result->position, $i);                 
            }

        } else {
            return $sponsor;
        }
    }

    private function find_fifth_pool_sponsor($sponsor, $i)
    {      
        if ($i > 0) {     

            #For Matrix Level          
            $this->db->select('position')->from('member')->where(array('id' => $sponsor),array('topup' => 2500000));

            $result = $this->db->get()->row();
           
            if (!$result) {
                return FALSE;
            } else {
                $i = ($i - 1);
                #For Positional Level Sponsor               
                return $this->find_fifth_pool_sponsor($result->position, $i);                 
            }

        } else {
            return $sponsor;
        }
    }



    public function fix_income($userid, $sponsor, $amount)
    {
        $get_topup = $this->db_model->select('topup', 'member', array('id' => $userid));
        if ($get_topup > "0"):

            ###############################################################
            #
            # Direct or Referal Income First
            #
            ##############################################################
            
            $data = $this->db_model->select_multi('direct_income, level_income, binary_income', 'fix_income', array('1' => 1));
            if ($data->binary_income > "0") {
                $my_business = $amount * $data->binary_income / 100;
                $arr = array('my_business' => $my_business);
                $this->db->where('id', $userid);
                $this->db->update('member', $arr);
            }
            if ($data->direct_income > "0") {
                
                // $this->pay_earning($sponsor, $userid, 'Referral Income', ($amount * $data->direct_income / 100));
            }

            ## NOW Level Income
            if (trim($data->level_income) !== "") {
                $ex = explode(',', $data->level_income);
                $i = 0;
                foreach ($ex as $e) {
                    $e = trim($e);
                    if ($i == 0) {
                        $pay_sponsor = $sponsor;
                    } else {
                        $pay_sponsor = $this->find_level_sponsor($sponsor, $i);
                    }
                    if ($pay_sponsor > 0 && $e > 0) {
                        $this->pay_earning($pay_sponsor, $userid, 'Level Income', ($amount * $e / 100));
                    }
                    $i++;
                }
            }

        endif;

        return TRUE;

    }

    public function advt_level($userid, $ad_id, $level_income, $need_topup = TRUE)
    {
        $get_topup = $this->db_model->select_multi('sponsor,topup', 'member', array('id' => $userid));
        $sponsor = $get_topup->sponsor;
        $get_topup = $get_topup->topup;
        if ($get_topup > "0.00" or $need_topup !== TRUE):

            ###############################################################
            #
            # Direct or Referal Income For Ads. Also check whether
            # he/she has received ad income before for this ad
            #
            ##############################################################
            $data = $this->db_model->count_all('ad_user', array(
                'ad_id' => $ad_id,
                'userid' => $userid,
            ));
            if ($data <= 0) {
                ## NOW Level Income
                $ex = explode(',', $level_income);
                $i = 0;
                foreach ($ex as $e) {
                    $e = trim($e);
                    if ($i == 0) {
                        $pay_sponsor = $this->session->user_id;
                    } else {
                        $pay_sponsor = $this->find_level_sponsor($sponsor, $i);
                    }
                    if ($pay_sponsor > 0 && $e > 0) {
                        $this->pay_earning($pay_sponsor, $userid, 'Advt Income', $e);
                    }
                    $i++;
                }

                $array = array(
                    'ad_id' => $ad_id,
                    'userid' => $userid,
                    'date' => time(),
                );
                $this->db->insert('ad_user', $array);
            }

        endif;

        return TRUE;

    }

    public function survey_level($userid, $survey_id, $level_income, $data_arr = "", $need_topup = TRUE)
    {
        $get_topup = $this->db_model->select_multi('sponsor,topup', 'member', array('id' => $userid));
        $sponsor = $get_topup->sponsor;
        $get_topup = $get_topup->topup;
          
        if ($get_topup > "0.00" or $need_topup !== TRUE):

            ###############################################################
            #
            # Direct or Referal Income For Completed Surveys. Also check whether
            # he/she has received ad income before for this ad
            #
            ##############################################################
            $data = $this->db_model->count_all('survey_user', array(
                'survey_id' => $survey_id,
                'userid' => $userid,
            ));

            if ($data <= 0) {
                ## NOW Level Income
                $ex = explode(',', $level_income);
                $i = 0;
                foreach ($ex as $e) {
                    $e = trim($e);
                    if ($i == 0) {
                        $pay_sponsor = $this->session->user_id;
                    } else {
                        $pay_sponsor = $this->find_level_sponsor($sponsor, $i);
                    }
                    if ($pay_sponsor > 0 && $e > 0) {
                        $levlno="0";
                        $this->pay_earning($pay_sponsor, $userid, 'Survey Income', $e,$levlno);
                    }
                    $i++;
                }

                $array = array(
                    'survey_id' => $survey_id,
                    'userid' => $userid,
                    'date' => time(),
                    'data' => $data_arr,
                );

                $this->db->insert('survey_user', $array);
            }

        endif;

        return TRUE;
    }

    /*
    public function reward_process()
    {
        $reward = $this->db->get('reward_setting')->result();
        foreach ($reward as $res) {
            $duration = date('Y-m-d', '-' . $res->reward_duration);
            if ($duration == date('Y-m-d')) {
                $duration = date('Y-m-d', '-10 Years');
            }
            
            $this->db->select('id')->from('member')->where(array(
                    'rank' => $res->based_on,
                 'join_time >= ' . $duration,
            ));
            $data = $this->db->get()->result();
          
            foreach ($data as $result) {
                
                $count = $this->db_model->count_all('rewards', array(
                    'userid' => $result->id,
                    'reward_id' => $res->id,
                ));
            
                if ($count <= 0) {
                    $array = array(
                        'reward_id' => $res->id,
                        'userid' => $result->id,
                        'date' => date('Y-m-d'),
                    );
                    $this->db->insert('rewards', $array);
                }
            }
        }
    }*/
    
    public function reward_process()
    {
        $reward = $this->db->get('reward_setting')->result();
        foreach ($reward as $res) 
        {
            $this->db->where(['topup >' => 0,'signup_package !=' => '','total_a >' => 9,'total_b >' => 9]);
            $data = $this->db->get('member')->result();
          
            foreach ($data as $result) {
                
                $pair_match = min($result->total_a, $result->total_b);

                if($pair_match < 9){ continue; }

                if($pair_match >= $res->total_member)
                {
                    $tbl_rewaed = $this->db_model->select('balance', 'rewards', array('userid' => $result->id ,'reward_id' => $res->id ));
                
                    if ($tbl_rewaed == "") 
                    {
                        $array = array(
                            'reward_id' => $res->id,
                            'userid' => $result->id,
                            'date' => date('Y-m-d'),
                            'status' => 'Pending',
                            'reward_gift' => $res->reward_gift,
                            'reward_amt' => $res->reward_amt,
                        );
                        $inserted = $this->db->insert('rewards', $array);

                        if($inserted)
                        {
                            $reward_array = array('reward_rank'  => $res->reward_name);
                            $this->db->where('id', $result->id);
                            $this->db->update('member', $reward_array);
                        }
                    }
                }
            }
        }
    }

    public function rank_process()
    {
        $rank = $this->db->get('rank_system')->result();
        foreach ($rank as $res) {
            $total_member = $res->total_member;
            $this->db->where('total_pairs >=', $total_member);
            $data = $this->db->get('member')->result();
            foreach ($data as $result) {
                $count  = $this->db_model->count_all('earning', array('type' => $res->rank_name.' Rank Income','userid' => $result->id));
                if($count < 1){
                    $this->pay_earning($result->id, '', $res->rank_name.' Rank Income', $res->amount, 1000, $res->id);
                    $array = array(
                        'rank'  => $res->rank_name,
                    );
                    $this->db->where('id', $result->id);
                    $this->db->update('member', $array);
                }
            }
        }
    }

    public function single_leg_income($userid)
    {
        // Single leg income level wise with sponsor condition
        $eligible_member = $this->db->get('member')->result();

        foreach ($eligible_member as $em) {
         
                $count_sp = $this->db_model->count_all('member', array(
                    'sponsor' => $em->id));
                if($count_sp >= 1 && $em->total_a == 50)
                {
                  $this->pay_earning($em->id, $userid,'Single Leg Inc','500',1);
                }
                else if($count_sp >= 1 && $em->total_a == 150)
                {
                  $this->pay_earning($em->id, $userid,'Single Leg Inc','1000',2);
                }
                else if($count_sp >= 2 && $em->total_a ==350)
                { 
                  $this->pay_earning($em->id, $userid,'Single Leg Inc','2000',3);
                }
                else if($count_sp >= 3 && $em->total_a == 750)
                {
                  $this->pay_earning($em->id, $userid,'Single Leg Inc','3500',4);
                }
                else if($count_sp >= 4 && $em->total_a == 1550)
                { 
                  $this->pay_earning($em->id, $userid,'Single Leg Inc','5000',5);
                }
                else if($count_sp >= 5 && $em->total_a == 3150)
                { 
                  $this->pay_earning($em->id, $userid,'Single Leg Inc','10000',6);
                }
                else if($count_sp >= 6 && $em->total_a == 6350)
                { 
                    $array = array('topup' => 20000);
                    $this->db->where('id', $em->id);
                    $this->db->update('member', $array);

                     $array = array( 
                        'userid' => $em->id,
                        'upgrade_amt' => '20000',
                        'package_id' => 'Silver Pool',
                        'status' => 'Active',
                    );
                    $this->db->insert('upgrad_level', $array);
                }

            }
    }

    public function starter_income($topup,$uid){

           $this->db->select('id,total_a')->from('member')->where('topup',$topup);
            $data = $this->db->get()->result();
            $count_sp = $this->db_model->count_all('member', array(
                    'sponsor' => $em->id));
            foreach ($data as $d1) {
                           
                if($count_sp >=2 && $d1->total_a <=2){
                    $this->pay_earning($d1->id, $uid,'Starter Income',5,1);
                }else if($count_sp >=2 && $d1->total_a <=4 && $d1->total_a >2){
                    $this->pay_earning($d1->id, $uid,'Starter Income',5,2);
                }else if($count_sp >=2 && $d1->total_a <=8 && $d1->total_a >4){
                    $this->pay_earning($d1->id, $uid,'Starter Income',5,3);
                }else if($count_sp >=2 && $d1->total_a <=16 && $d1->total_a >8){
                    $this->pay_earning($d1->id, $uid,'Starter Income',5,4);
                }else if($count_sp >=2 && $d1->total_a <=32 && $d1->total_a >16){
                    $this->pay_earning($d1->id, $uid,'Starter Income',5,5);
                }else if($count_sp >=2 && $d1->total_a <=64 && $d1->total_a >32){
                    $this->pay_earning($d1->id, $uid,'Starter Income',5,6);
                }else if($count_sp >=2 && $d1->total_a == 128 && $d1->total_a >64){
                    $this->pay_earning($d1->id, $uid,'Starter Income',5,7);
                    
                    $wallet_bal = $this->db_model->select('balance', 'wallet', array('userid' => $d1->id));
         
                    $new_wbal=array('balance'=>$wallet_bal - 600 );$this->db->update('wallet', $new_wbal);

                    $array = array('topup' => 600);$this->db->where('id', $d1->id);$this->db->update('member', $array);
                    $up_data = array( 
                        'userid' => $d1->id,
                        'upgrade_amt' => '600',
                        'package_id' => 'Deluxe Pool',
                        'status' => 'Active',
                    );
                    $this->db->insert('upgrad_level', $up_data);

                }
       }
    }

     public function deluxe_income($topup,$uid){

           $this->db->select('id,total_a')->from('member')->where('topup',$topup);
            $data = $this->db->get()->result();

            foreach ($data as $d1) {
               
                if($d1->total_a <=2){
                    $this->pay_earning($d1->id, $uid,'Deluxe Income',30,1);
                }else if($d1->total_a <=4 && $d1->total_a >2){
                    $this->pay_earning($d1->id, $uid,'Deluxe Income',30,2);
                }else if($d1->total_a <=8 && $d1->total_a >4){
                    $this->pay_earning($d1->id, $uid,'Deluxe Income',30,3);
                }else if($d1->total_a <=16 && $d1->total_a >8){
                    $this->pay_earning($d1->id, $uid,'Deluxe Income',30,4);
                }else if($d1->total_a <=32 && $d1->total_a >16){
                    $this->pay_earning($d1->id, $uid,'Deluxe Income',30,5);
                }else if($d1->total_a <=64 && $d1->total_a >32){
                    $this->pay_earning($d1->id, $uid,'Deluxe Income',30,6);
                }else if($d1->total_a == 128 && $d1->total_a >64){
                    $this->pay_earning($d1->id, $uid,'Deluxe Income',30,7);
                    
                    $wallet_bal = $this->db_model->select('balance', 'wallet', array('userid' => $d1->id));
         
                    $new_wbal=array('balance'=>$wallet_bal - 3000 );$this->db->where('userid', $d1->id);$this->db->update('wallet', $new_wbal);

                    $array = array('topup' => 3000);$this->db->where('id', $d1->id);$this->db->update('member', $array);
                    $up_data = array( 
                        'userid' => $d1->id,
                        'upgrade_amt' => '3000',
                        'package_id' => 'Premium Pool',
                        'status' => 'Active',
                    );
                    $this->db->insert('upgrad_level', $up_data);

                }
            }
    }

     public function premium_income($topup,$uid){
     
           $this->db->select('id,total_a,total_b')->from('member')->where('topup',3000);
           $data = $this->db->get()->result();

           
            foreach ($data as $d1) {
                $tota_team = $d1->total_a + $d1->total_b;
                if($tota_team <=2){
                    $this->pay_earning($d1->id, $uid,'Premium Income',150,1);
                }else if($tota_team <=4 && $tota_team >2){
                    $this->pay_earning($d1->id, $uid,'Premium Income',150,2);
                }else if($tota_team <=8 && $tota_team >4){
                    $this->pay_earning($d1->id, $uid,'Premium Income',150,3);
                }else if($tota_team <=16 && $tota_team >8){
                    $this->pay_earning($d1->id, $uid,'Premium Income',150,4);
                }else if($tota_team <=32 && $tota_team >16){
                    $this->pay_earning($d1->id, $uid,'Premium Income',150,5);
                }else if($tota_team <=64 && $tota_team >32){
                    $this->pay_earning($d1->id, $uid,'Premium Income',150,6);
                }else if($tota_team == 128 && $tota_team >64){
                    $this->pay_earning($d1->id, $uid,'Premium Income',150,7);
                    
                    $wallet_bal = $this->db_model->select('balance', 'wallet', array('userid' => $d1->id));
         
                    $new_wbal=array('balance'=>$wallet_bal - 20000 );$this->db->where('id', $d1->id);$this->db->update('wallet', $new_wbal);

                    $array = array('topup' => 20000);$this->db->where('id', $d1->id);$this->db->update('member', $array);
                    $up_data = array( 
                        'userid' => $d1->id,
                        'upgrade_amt' => '20000',
                        'package_id' => 'Silver Pool',
                        'status' => 'Active',
                    );
                    $this->db->insert('upgrad_level', $up_data);

                    // $upgrade_id = $this->db_model->select('id', 'member', array('id' => $d1->id),array('topup' => 20000)) ;  

                    // $this->silver_income($topup = 20000,$upgrade_id);   


                }
            }
    }


    public function silver_income($topup,$uid){

           $this->db->select('id,total_a,total_b')->from('member')->where('topup',$topup);
            $data = $this->db->get()->result();

            foreach ($data as $d1) {
                 $tota_team = $d1->total_a + $d1->total_b;
                if($tota_team <=2){
                    $this->pay_earning($d1->id, $uid,'Silver Income',1000,1);
                }else if($tota_team <=4 && $tota_team >2){
                    $this->pay_earning($d1->id, $uid,'Silver Income',1000,2);
                }else if($tota_team <=8 && $tota_team >4){
                    $this->pay_earning($d1->id, $uid,'Silver Income',1000,3);
                }else if($tota_team <=16 && $tota_team >8){
                    $this->pay_earning($d1->id, $uid,'Silver Income',1000,4);
                }else if($tota_team <=32 && $tota_team >16){
                    $this->pay_earning($d1->id, $uid,'Silver Income',1000,5);
                }else if($tota_team <=64 && $tota_team >32){
                    $this->pay_earning($d1->id, $uid,'Silver Income',1000,6);
                }else if($tota_team == 128 && $tota_team >64){
                    $this->pay_earning($d1->id, $uid,'Silver Income',1000,7);
               
                    $wallet_bal = $this->db_model->select('balance', 'wallet', array('userid' => $d1->id));
         
                    $new_wbal=array('balance'=>$wallet_bal - 100000 );$this->db->where('id', $d1->id);$this->db->update('wallet', $new_wbal);

                    $array = array('topup' => 100000);$this->db->where('id', $d1->id);$this->db->update('member', $array);
                    $up_data = array( 
                        'userid' => $d1->id,
                        'upgrade_amt' => '100000',
                        'package_id' => 'Gold Pool',
                        'status' => 'Active',
                    );
                    $this->db->insert('upgrad_level', $up_data);
                    // $upgrade_id = $this->db_model->select('id', 'member', array('id' => $d1->id),array('topup' => 100000)) ;  

                    // $this->gold_income($topup = 100000,$upgrade_id);


                }
       }
    }

    public function gold_income($topup,$uid){

           $this->db->select('id,total_a,total_b')->from('member')->where('topup',$topup);
            $data = $this->db->get()->result();

            foreach ($data as $d1) {
                  $tota_team = $d1->total_a + $d1->total_b;
                if($tota_team <=2){
                    $this->pay_earning($d1->id, $uid,'Gold Income',5000,1);
                }else if($tota_team <=4 && $tota_team >2){
                    $this->pay_earning($d1->id, $uid,'Gold Income',5000,2);
                }else if($tota_team <=8 && $tota_team >4){
                    $this->pay_earning($d1->id, $uid,'Gold Income',5000,3);
                }else if($tota_team <=16 && $tota_team >8){
                    $this->pay_earning($d1->id, $uid,'Gold Income',5000,4);
                }else if($tota_team <=32 && $tota_team >16){
                    $this->pay_earning($d1->id, $uid,'Gold Income',5000,5);
                }else if($tota_team <=64 && $tota_team >32){
                    $this->pay_earning($d1->id, $uid,'Gold Income',5000,6);
                }else if($tota_team == 128 && $tota_team >64){
                    $this->pay_earning($d1->id, $uid,'Gold Income',5000,7);

                     $wallet_bal = $this->db_model->select('balance', 'wallet', array('userid' => $d1->id));
         
                    $new_wbal=array('balance'=>$wallet_bal - 500000 );$this->db->where('id', $d1->id);$this->db->update('wallet', $new_wbal);

                    $array = array('topup' => 500000);$this->db->where('id', $d1->id);$this->db->update('member', $array);
                    $up_data = array( 
                        'userid' => $d1->id,
                        'upgrade_amt' => '500000',
                        'package_id' => 'Super Gold Pool',
                        'status' => 'Active',
                    );
                    $this->db->insert('upgrad_level', $up_data);
                    $upgrade_id = $this->db_model->select('id', 'member', array('id' => $d1->id),array('topup' => 500000)) ;  

                    $this->super_income($topup = 500000,$upgrade_id);

                }
       }
    }


    public function super_income($topup,$uid){
        
        $this->db->select('id,total_a,total_b')->from('member')->where('topup',$topup);
        $data = $this->db->get()->result();

           foreach ($data as $d1) {
                  $tota_team = $d1->total_a + $d1->total_b;
                if($tota_team <=2){
                    $this->pay_earning($d1->id, $uid,'Super Income',25000,1);
                }else if($tota_team <=4 && $tota_team >2){
                    $this->pay_earning($d1->id, $uid,'Super Income',25000,2);
                }else if($tota_team <=8 && $tota_team >4){
                    $this->pay_earning($d1->id, $uid,'Super Income',25000,3);
                }else if($tota_team <=16 && $tota_team >8){
                    $this->pay_earning($d1->id, $uid,'Super Income',25000,4);
                }else if($tota_team <=32 && $tota_team >16){
                    $this->pay_earning($d1->id, $uid,'Super Income',25000,5);
                }else if($tota_team <=64 && $tota_team >32){
                    $this->pay_earning($d1->id, $uid,'Super Income',25000,6);
                }else if($tota_team == 128 && $tota_team >64){
                    $this->pay_earning($d1->id, $uid,'Super Income',25000,7);

                    $wallet_bal = $this->db_model->select('balance', 'wallet', array('userid' => $d1->id));
         
                    $new_wbal=array('balance'=>$wallet_bal - 2500000 );$this->db->where('id', $d1->id);$this->db->update('wallet', $new_wbal);

                    $array = array('topup' => 2500000);$this->db->where('id', $d1->id);$this->db->update('member', $array);
                    $up_data = array( 
                        'userid' => $d1->id,
                        'upgrade_amt' => '2500000',
                        'package_id' => 'Diamond Pool',
                        'status' => 'Active',
                    );
                    $this->db->insert('upgrad_level', $up_data);

                    $upgrade_id = $this->db_model->select('id', 'member', array('id' => $d1->id),array('topup' => 2500000)) ;  

                    $this->diamond_income($topup = 2500000,$upgrade_id);

                }

           }
    }    

    public function diamond_income($topup,$uid){
        
        $this->db->select('id,total_a,total_b')->from('member')->where('topup',$topup);
        $data = $this->db->get()->result();

           foreach ($data as $d1) {
                $tota_team = $d1->total_a + $d1->total_b;
                if($tota_team <=2){
                    $this->pay_earning($d1->id, $uid,'Diamond Income',125000,1);
                }else if($tota_team <=4 && $tota_team >2){
                    $this->pay_earning($d1->id, $uid,'Diamond Income',125000,2);
                }else if($tota_team <=8 && $tota_team >4){
                    $this->pay_earning($d1->id, $uid,'Diamond Income',125000,3);
                }else if($tota_team <=16 && $tota_team >8){
                    $this->pay_earning($d1->id, $uid,'Diamond Income',125000,4);
                }else if($tota_team <=32 && $tota_team >16){
                    $this->pay_earning($d1->id, $uid,'Diamond Income',125000,5);
                }else if($tota_team <=64 && $tota_team >32){
                    $this->pay_earning($d1->id, $uid,'Diamond Income',125000,6);
                }else if($tota_team == 128 && $tota_team >64){
                    $this->pay_earning($d1->id, $uid,'Diamond Income',125000,7);
                }

           }
    }    



    public function flexi_income()
    {
        $reward = $this->db->get('flexible_income')->result();

        foreach ($reward as $res) {
            $duration = date('Y-m-d', '-' . $res->income_duration);
            if ($duration == date('Y-m-d')) {
                $duration = date('Y-m-d', '-20 Years');
            }
            if ($res->based_on == "Member"):

                $this->db->select('id')->from('member')->where(array(
                    'total_a >=' => $res->A,
                    'total_b >=' => $res->B,
                    'total_c >=' => $res->C,
                    'total_d >=' => $res->D,
                    'total_e >=' => $res->E,
                    'join_time >= ' . $duration,
                    'topup >' => '0',
                ));
            else:

                $this->db->select('id')->from('member')->where(array(
                    'total_a_pv >=' => $res->A,
                    'total_b_pv >=' => $res->B,
                    'total_c_pv >=' => $res->C,
                    'total_d_pv >=' => $res->D,
                    'total_e_pv >=' => $res->E,
                    'join_time >= ' . $duration,
                    'topup >' => '0',
                ));

            endif;
            $data = $this->db->get()->result();

            foreach ($data as $result) {

                $count = $this->db_model->count_all('earning', array(
                    'userid' => $result->id,
                    'secret' => $res->id,
                    'type' => $res->income_name,
                ));


                if ($count <= 0) {
                    $array = array( 
                        'userid' => $result->id,
                        'amount' => $res->amount,
                        'type' => $res->income_name,
                        'secret' => $res->id,
                        'date' => date('Y-m-d'),
                        'levlno'=>$res->id,
                    );
                    
                    $this->db->insert('earning', $array);
                } 
            }
        }
    }

    public function level_wise_income()
    {
        $reward = $this->db->get('level_wise_income')->result();
        foreach ($reward as $res) {
            $duration = date('Y-m-d', '-' . $res->income_duration);
            if ($duration == date('Y-m-d')) {
                $duration = date('Y-m-d', '-20 Years');
            }
            $this->db->select('userid')->from('level')->where(array(
                'level' . $res->level_no . ' >=' => $res->total_member,
                'join_time >= ' . $duration
                
            ));

            $data = $this->db->get()->result();
            foreach ($data as $result) {
                $count = $this->db_model->count_all('earning', array(
                    'userid' => $result->userid,
                    'secret' => $res->id,
                    'type' => $res->income_name,
                ));
                if ($count <= 0) {
                    $array = array(
                        'userid' => $result->userid,
                        'amount' => $res->amount,
                        'type' => $res->income_name,
                        'secret' => $res->id,
                        'date' => date('Y-m-d'),
                    );
                    $this->db->insert('earning', $array);
                }
            }
        }
    }

    /*
    public function process_binary($id, $data)
    {
        $package    = $this->db_model->select('signup_package', 'member', array('id' => $id));
        //$capping    = $this->db_model->select('capping', 'product', array('id' => $package)) ?? 1000000;
        $matching   = $this->db_model->select('matching_income', 'product', array('id' => $package)) ?? 100;
        $total_pair = $this->db_model->select('total_pairs', 'member', array('id' => $id));
        $a_side     = $this->db_model->select('total_a_pv', 'member', array('id' => $id)) ?? 0;
        $b_side     = $this->db_model->select('total_b_pv', 'member', array('id' => $id)) ?? 0;
        $sponsor_count = $this->db_model->count_all('member', array('sponsor' => $id,'topup >' => 0));
        
        $rank = $this->db_model->select('rank', 'member', array('id' => $id));

        switch (trim($rank)) {
            case 'Silver Associate':
                $capping = 3500;
                break;

            case 'Gold Associate':
                $capping = 4000;
                break;

            case 'Platinum Associate':
                $capping = 4500;
                break;

            case 'Ruby Associate':
                $capping = 5000;
                break;

            default:
                $capping = 3000; 
                break;
        }

        $check      = FALSE;
        if($sponsor_count >= 2){
            if ($total_pair == 0 && $a_side != 0 && $b_side != 0) {
                $array = array(
                    'total_pairs' => 1
                );
        
                if ($a_side > $b_side && $b_side != 0) {
                    $array['paid_a_pv'] = 1;
                    $array['paid_b_pv'] = 1;
                    $check              = TRUE;
                } elseif ($b_side > $a_side && $a_side != 0) {
                    $array['paid_a_pv'] = 1;
                    $array['paid_b_pv'] = 1;
                    $check              = TRUE;
                } elseif ($b_side + $a_side >= 3 && $a_side > 0 && $b_side > 0) {
                    $array['paid_a_pv'] = 1;
                    $array['paid_b_pv'] = 1;
                    $check              = TRUE;
                }
        
                if ($check) {
                    $this->db->where('id', $id);
                    $this->db->update('member', $array);
                    $this->pay_earning($id, '0000', 'Matching Income', $matching, 0, 1);
                }
            }
            $total_pair = $this->db_model->select('total_pairs', 'member', array('id' => $id));
            $a_side     = $this->db_model->select('total_a_pv', 'member', array('id' => $id)) ?? 0;
            $b_side     = $this->db_model->select('total_b_pv', 'member', array('id' => $id)) ?? 0;
            $a_side_p   = $this->db_model->select('paid_a_pv', 'member', array('id' => $id));
            $b_side_p   = $this->db_model->select('paid_b_pv', 'member', array('id' => $id));
            $pair_match = floor(min(max(0, ($a_side - $a_side_p)), max(0, ($b_side - $b_side_p))) / 1);
            $pair_max   = floor(max(($a_side - $a_side_p), ($b_side - $b_side_p)) / 1);
            if ($pair_match >= 1 and $total_pair > 0) {
                $pair_match_inc = $pair_match * $matching;
                $earning_ct     = $this->db_model->sum('amount', 'earning', array('userid' => $id, 'type' => 'Matching Income', 'date' => date('Y-m-d'))) + 0;
                $package        = $this->db_model->select('signup_package', 'member', array('id' => $id));
                #$capping        = $this->db_model->select('capping', 'product', array('id' => $package)) ?? 10000;
                if($pair_match_inc > $capping){
                    $data = array(
                        'userid'        => $id,
                        'amount'        => $pair_match_inc - $capping,
                        'type'          => 'Matching Income',
                        'reason'        => 'Capping Reached',
                    );
                    $this->db->insert('laps_earning', $data);
                    $pair_match_inc = $capping;
                    
                } else{
                    $pair_match_inc = $pair_match_inc;
                }
                $array = array(
                    'paid_a_pv'   => $a_side_p   + $pair_match,
                    'paid_b_pv'   => $b_side_p   + $pair_match,
                    'total_pairs' => $total_pair + $pair_match,
                );
                $this->db->where('id', $id);
                $this->db->update('member', $array);
                $this->pay_earning($id, '', 'Matching Income', $pair_match_inc, '', $pair_match);
            }
        }
    }

    */
        ######################Start Royalty Income Send Function: This is a custom function, not a core function, used for the Daimora software
    #18-11-2025 
    public function royalty_income_process_month($year_month = null)
    {
        /* Royalty Income process month commented out
        if (empty($year_month)) {
            $year_month = date('Y-m', strtotime('-1 month'));
        }
        
        $start_date = $year_month . '-01';
        $end_date = date('Y-m-t', strtotime($start_date));

        // Get all active members with topup > 0
        $active_members = $this->db->select('id,name, royalty_rank, A, B, self_power_a, self_power_b')->from('member')->where('topup >', 0)->get()->result();

        $processing_results = [
            'month' => $year_month,
            'total_processed' => 0,
            'total_income' => 0,
            'details' => []
        ];

        // Arrays to store higher rank members for later processing
        $innovator_members = [];    $entrepreneur_members = []; $diamond_director_members = [];

        // Define rank names
        $rank_names = [
            0 => 'Member',
            1 => 'Founder Diamond',
            2 => 'Royal Visionary', 
            3 => 'Innovator',
            4 => 'Entrepreneur',
            5 => 'Diamond Director'
        ];

        foreach ($active_members as $member) 
        {
            $userid = $member->id;
            
            // 1. Check TOTAL leg counts (all active members in entire leg history)
            $left_count = $this->count_total_active_ids_in_subtree($member->A);
            $right_count = $this->count_total_active_ids_in_subtree($member->B);
            
            #Apply this calculation to determine the power whenever a user requests it for any side
            $left_count_new  = $left_count + $member->self_power_a;
            $right_count_new  = $right_count + $member->self_power_b;
            
            $power_leg = max($left_count_new, $right_count_new);
            $weaker_leg = min($left_count_new, $right_count_new); 
            #
            #echo "Power Leg ".$power_leg ."<br> Weaker Leg". $weaker_leg;die();
            // 2. Determine eligible rank (check from highest to lowest)
            $eligible_rank = 0;
            $eligible_rank_name = 'Member';
            
            if ($power_leg >= 10000 && $weaker_leg >= 15000) { // Diamond Director 10000 & 15000
                $eligible_rank = 5; $eligible_rank_name = $rank_names[5];
            } elseif ($power_leg >= 6000 && $weaker_leg >= 9000) { // Entrepreneur 6000 & 9000
                $eligible_rank = 4; $eligible_rank_name = $rank_names[4];
            } elseif ($power_leg >= 3000 && $weaker_leg >= 4500) { // Innovator 3000 & 4500
                $eligible_rank = 3; $eligible_rank_name = $rank_names[3];
            } elseif ($power_leg >= 1200 && $weaker_leg >= 1800) { // Royal Visionary 1200 & 1800
                $eligible_rank = 2; $eligible_rank_name = $rank_names[2];
            } elseif ($power_leg >= 200 && $weaker_leg >= 300) { // Founder Diamond 200 & 300
                $eligible_rank = 1; $eligible_rank_name = $rank_names[1];
            }
            
            // Skip if no eligible rank found
            if ($eligible_rank <= 0) {
                continue;
            }
            
            // 3. Check if user needs rank upgrade
            $current_rank = $member->royalty_rank;
            $current_rank_level = 0;

            foreach ($rank_names as $level => $name) {
                if ($current_rank == $name) {
                    $current_rank_level = $level;
                    break;
                }
            }
            
            $needs_upgrade = ($eligible_rank > $current_rank_level);

            if ($needs_upgrade) {
                // Update user rank to higher rank
                $this->db->where('id', $userid)
                    ->update('member', [
                        'royalty_rank' => $eligible_rank_name,
                    ]);
            }
            
            // 4. Remove ALL previous royalty income for this user (if any)
            $this->db->where('userid', $userid)->where('type LIKE', 'royalty%')->delete('earning');
            
            // 5. Calculate income for CURRENT eligible rank
            $income_data = $this->calculate_rank_income($eligible_rank, $userid, $year_month);
            
            if ($income_data['amount'] > 0) {
                // Founder Diamond & Royal Visionary - Insert immediately
                if($income_data['rank_name'] == 'Founder Diamond' || $income_data['rank_name'] == 'Royal Visionary') 
                {
                    $earning_data = [
                        'userid' => $userid,
                        'amount' => $income_data['amount'],
                        'type' => 'royalty_' . $eligible_rank_name,
                        'date' => $start_date,
                        'month_period' => $year_month,
                    ];
                    
                    $this->db->insert('earning', $earning_data);
                    
                    $processing_results['total_processed']++;
                    $processing_results['total_income'] += $income_data['amount'];
                }
                // Higher ranks - Store for later distribution
                elseif($income_data['rank_name'] == 'Innovator') {
                    $innovator_members[] = [
                        'id' => $userid,
                        'name' => $member->name,
                        'amount' => $income_data['amount']
                    ];
                }
                elseif($income_data['rank_name'] == 'Entrepreneur') {
                    $entrepreneur_members[] = [
                        'id' => $userid,
                        'name' => $member->name,
                        'amount' => $income_data['amount']
                    ];
                }
                elseif($income_data['rank_name'] == 'Diamond Director') {
                    $diamond_director_members[] = [
                        'id' => $userid,
                        'name' => $member->name,
                        'amount' => $income_data['amount']
                    ];
                }
                
                $processing_results['details'][] = [
                    'id' => $userid,
                    'name' => $member->name,
                    'old_rank' => $current_rank,
                    'new_rank' => $eligible_rank_name,
                    'amount' => $income_data['amount'],
                    'power_leg' => $power_leg,
                    'weaker_leg' => $weaker_leg,
                    'upgraded' => $needs_upgrade
                ];
            }
        }
        
        // 6. Process higher ranks after all members are updated
        $this->process_higher_ranks_income($innovator_members, $entrepreneur_members, $diamond_director_members, $year_month, $start_date);
        
        return $processing_results;
        */
        return ['total_processed' => 0, 'total_income' => 0];
    }

    private function process_higher_ranks_income($innovator_members, $entrepreneur_members, $diamond_director_members, $year_month, $start_date)
    {
        /* Process higher ranks commented out
        // Get company turnover for the month
        $company_turnover = $this->get_company_turnover_month($year_month);
        
        // Process Innovators
        if (!empty($innovator_members)) {
            $innovator_count = count($innovator_members);
            #$innovator_count = 5;
            $percentage = 3; // 3% of company turnover
            $total_pool = ($company_turnover * $percentage) / 100;
            $amount_per_member = $total_pool / $innovator_count;
            
            foreach ($innovator_members as $member) {
                $earning_data = [
                    'userid' => $member['id'],
                    'amount' => $amount_per_member,
                    'type' => 'royalty_Innovator',
                    'date' => $start_date,
                    'month_period' => $year_month,
                ];
                $this->db->insert('earning', $earning_data);
            }
        }
        
        // Process Entrepreneurs (similar logic with different percentage)
        if (!empty($entrepreneur_members)) {
            $entrepreneur_count = count($entrepreneur_members);
            #$entrepreneur_count = 5;
            $percentage = 2; // Example percentage
            $total_pool = ($company_turnover * $percentage) / 100;
            $amount_per_member = $total_pool / $entrepreneur_count;
            
            foreach ($entrepreneur_members as $member) {
                $earning_data = [
                    'userid' => $member['id'],
                    'amount' => $amount_per_member,
                    'type' => 'royalty_Entrepreneur',
                    'date' => $start_date,
                    'month_period' => $year_month,
                ];
                $this->db->insert('earning', $earning_data);
            }
        }
        
        // Process Diamond Directors (similar logic with different percentage)
        if (!empty($diamond_director_members)) {
            $director_count = count($diamond_director_members);
            #$director_count = 5;
            $percentage = 1; // Example percentage
            $total_pool = ($company_turnover * $percentage) / 100;
            $amount_per_member = $total_pool / $director_count;
            
            foreach ($diamond_director_members as $member) {
                $earning_data = [
                    'userid' => $member['id'],
                    'amount' => $amount_per_member,
                    'type' => 'royalty_Diamond Director',
                    'date' => $start_date,
                    'month_period' => $year_month,
                ];
                $this->db->insert('earning', $earning_data);
            }
        }
        */
        return true;
    }

    // NEW FUNCTION: Count ALL active IDs in subtree (no date restriction)
    public function count_total_active_ids_in_subtree($start_node_id)
    {
        if (empty($start_node_id) || $start_node_id <= 0) return 0;

        $ids = $this->get_downline_ids($start_node_id);
        array_unshift($ids, $start_node_id);
        $ids = array_values(array_unique($ids));

        if (empty($ids)) return 0;

        $count = $this->db
            ->from('member')
            ->where_in('id', $ids)
            ->where('topup >', 0)  // Only active members (topup > 0)
            ->count_all_results();

        return (int)$count;
    }

    public function calculate_rank_income($rank, $userid, $year_month)
    { 
        $rank_config = [
            1 => ['name' => 'Founder Diamond', 'percent' => 2.5, 'type' => 'team'],
            2 => ['name' => 'Royal Visionary', 'percent' => 1.5, 'type' => 'team'],
            3 => ['name' => 'Innovator', 'percent' => 3.0, 'type' => 'company'],
            4 => ['name' => 'Entrepreneur', 'percent' => 2.0, 'type' => 'company'],
            5 => ['name' => 'Diamond Director', 'percent' => 1.0, 'type' => 'company']
        ];
        
        $config = $rank_config[$rank];
        
        // Calculate turnover based on rank type (STILL uses monthly data)
        if ($config['type'] === 'team') {
            $turnover = $this->get_team_turnover_month($userid, $year_month);
        } else {
            $turnover = $this->get_company_turnover_month($year_month);
        }
        #echo $turnover;die();
        $amount = ($turnover * $config['percent']) / 100;
        
        return [
            'rank_name' => $config['name'],
            'amount' => round($amount, 2),
            'turnover' => $turnover
        ];
    }

    public function get_downline_ids($userid)
    {
        $seen = [];
        $queue = [$userid];

        while (!empty($queue)) {
            $cur = array_shift($queue);
            $row = $this->db->select('A, B')->from('member')->where('id', $cur)->get()->row();

            if (!$row) continue;

            if (!empty($row->A) && $row->A > 0 && !in_array($row->A, $seen) && $row->A != $userid) {
                $seen[] = $row->A;
                $queue[] = $row->A;
            }

            if (!empty($row->B) && $row->B > 0 && !in_array($row->B, $seen) && $row->B != $userid) {
                $seen[] = $row->B;
                $queue[] = $row->B;
            }
        }

        return array_values(array_unique($seen));
    }

   
    public function get_team_turnover_month($userid, $year_month)
    {
        $start = $year_month . '-01';
        $end   = date('Y-m-t', strtotime($start));

        $downline_ids = $this->get_downline_ids($userid);
        
        if (empty($downline_ids)) return 0;

        $downline_ids = array_map('intval', $downline_ids);

        $this->db
            ->select('SUM(topup) as total_turnover', false)  // Custom SELECT with alias
            ->from('member')
            ->where_in('id', $downline_ids)
            ->where('topup >', 0)
            ->where('activation_date >=', $start)
            ->where('activation_date <=', $end);

        $query = $this->db->get();
        #echo "SQL Query: " . $this->db->last_query();exit(); 
        if ($query === FALSE) return 0;
        $result = $query->row();
        return (float)($result->total_turnover ?? 0);
    }

    public function get_company_turnover_month($year_month)
    {
        $start = $year_month . '-01';
        $end   = date('Y-m-t', strtotime($start));

        $result = $this->db
            ->select_sum('topup', 'total_turnover')  
            ->from('member')
            ->where('topup >', 0)
            ->where('activation_date >=', $start)
            ->where('activation_date <=', $end)
            ->get()
            ->row();

        return (float)($result->total_turnover ?? 0);
    }

    ######################End Royalty Income Send Functions
    
}




