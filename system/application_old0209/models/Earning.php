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
                if (isset($product->prod_price) && (float)$product->prod_price > 0) {
                    $this->db->select_max('prod_price');
                    $this->db->where('show_on_regform', 'Yes');
                    $max_row = $this->db->get('product')->row();
                    if ($max_row && (float)$max_row->prod_price > 0) {
                        return (float)$product->prod_price / (float)$max_row->prod_price;
                    }
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
        $earning_ct = $this->db_model->sum('amount', 'earning', ['userid' => $userid, 'type' => 'Matching Income', 'date' => date('Y-m-d')]) + 0;
        $pair_ct    = $this->db_model->count_all('pair_cuts', ['userid' => $userid, 'DATE(date)' => date('Y-m-d')]);
        $package = $this->db_model->select('signup_package', 'member', ['id' => $userid]);
        if (empty($package)) {
            $package = $this->db_model->select('join_package', 'member', ['id' => $userid]);
        }
        if (empty($package)) {
            $package = $this->db_model->select('topup', 'member', ['id' => $userid]);
        }
        $sponsor = $this->db_model->select('sponsor', 'member', ['id' => $userid]);
        
        if ($amount > 0 && !empty($package)) {
    
            // if ($income_name == 'Matching Income') {
    
            //     $deduct_amount = ($amount * 10) / 100;
            //     $check = $this->db->get_where('earning_deduct', ['userid' => $userid, 'type' => $income_name])->row();
            //     $final_amount = $amount;
    
            //     if ($check) {
            //         $new_total = $check->amount + $deduct_amount;
            //         if ($check->amount < 2000) {
            //             if ($new_total > 2000) {
            //                 $deduct_amount = 2000 - $check->amount;
            //                 $new_total = 2000;
            //             }
    
            //             $this->db->where('id', $check->id)->update('earning_deduct', [
            //                 'amount' => $new_total,
            //                 'date'   => date('Y-m-d')
            //             ]);
            //             $final_amount = $amount - $deduct_amount;
            //         }
            //     } else {
            //         if ($deduct_amount > 2000) {
            //             $deduct_amount = 2000;
            //         }
    
            //         $this->db->insert('earning_deduct', [
            //             'userid' => $userid,
            //             'amount' => $deduct_amount,
            //             'type'   => $income_name,
            //             'date'   => date('Y-m-d')
            //         ]);
    
            //         $final_amount = $amount - $deduct_amount;
            //     }
            // } else {
            //     $final_amount = $amount;
            // }

            $final_amount = $amount;
    
            $data = [
                'userid'     => $userid,
                'amount'     => $final_amount,
                'type'       => $income_name,
                'ref_id'     => $ref_id,
                'date'       => date('Y-m-d'),
                'pair_match' => $pair_match,
                'secret'     => $secret,
                'levlno'     => $levlno,
            ];
            $this->db->insert('earning', $data);
    
        } else {
            $data = [
                'userid' => $userid,
                'amount' => $amount,
                'type'   => $income_name,
                'reason' => 'Member not activate',
                'ref_id' => $ref_id,
                'levlno' => $levlno,
            ];
            $this->db->insert('laps_earning', $data);
        }
        return TRUE;
    }

    // public function process_binary($id, $data)
    // {
    //     $package    = $this->db_model->select('signup_package', 'member', array('id' => $id));
    //     //$capping    = $this->db_model->select('capping', 'product', array('id' => $package)) ?? 1000000;
    //     $matching   = $this->db_model->select('matching_income', 'product', array('id' => $package)) ?? 100;
    //     $total_pair = $this->db_model->select('total_pairs', 'member', array('id' => $id));
    //     $a_side     = $this->db_model->select('total_a_pv', 'member', array('id' => $id)) ?? 0;
    //     $b_side     = $this->db_model->select('total_b_pv', 'member', array('id' => $id)) ?? 0;
    //     $sponsor_count = $this->db_model->count_all('member', array('sponsor' => $id,'topup >' => 0));
        
    //     $rank = $this->db_model->select('rank', 'member', array('id' => $id));

    //     switch (trim($rank)) {
    //         case 'Silver Associate':
    //             $capping = 3500;
    //             break;

    //         case 'Gold Associate':
    //             $capping = 4000;
    //             break;

    //         case 'Platinum Associate':
    //             $capping = 4500;
    //             break;

    //         case 'Ruby Associate':
    //             $capping = 5000;
    //             break;

    //         default:
    //             $capping = 3000; 
    //             break;
    //     }
        
    //     #$check      = FALSE;
    //     #if($sponsor_count >= 2){
    //         // if ($total_pair == 0 && $a_side != 0 && $b_side != 0) {
    //         //     $array = array(
    //         //         'total_pairs' => 1
    //         //     );
        
    //         //     if ($a_side > $b_side && $b_side != 0) {
    //         //         $array['paid_a_pv'] = 1;
    //         //         $array['paid_b_pv'] = 1;
    //         //         $check              = TRUE;
    //         //     } elseif ($b_side > $a_side && $a_side != 0) {
    //         //         $array['paid_a_pv'] = 1;
    //         //         $array['paid_b_pv'] = 1;
    //         //         $check              = TRUE;
    //         //     } elseif ($b_side + $a_side >= 3 && $a_side > 0 && $b_side > 0) {
    //         //         $array['paid_a_pv'] = 1;
    //         //         $array['paid_b_pv'] = 1;
    //         //         $check              = TRUE;
    //         //     }
        
    //         //     if ($check) {
    //         //         $this->db->where('id', $id);
    //         //         $this->db->update('member', $array);
    //         //         $this->pay_earning($id, '0000', 'Matching Income', $matching, 0, 1);
    //         //     }
    //         // }

    //         $total_pair = $this->db_model->select('total_pairs', 'member', array('id' => $id));
    //         $a_side     = $this->db_model->select('total_a_pv', 'member', array('id' => $id)) ?? 0;
    //         $b_side     = $this->db_model->select('total_b_pv', 'member', array('id' => $id)) ?? 0;
    //         $a_side_p   = $this->db_model->select('paid_a_pv', 'member', array('id' => $id));
    //         $b_side_p   = $this->db_model->select('paid_b_pv', 'member', array('id' => $id));
    //         $pair_match = floor(min(max(0, ($a_side - $a_side_p)), max(0, ($b_side - $b_side_p))) / 1);
    //         $pair_max   = floor(max(($a_side - $a_side_p), ($b_side - $b_side_p)) / 1);

    //         $available_left  = $a_side - $a_side_p;
    //         $available_right = $b_side - $b_side_p;

    //         if($available_left < 400 || $available_right < 400){
    //             return; 
    //         }

    //         $pair_match = floor(min($available_left, $available_right) / 1);
            
    //         #if ($pair_match >= 1 and $total_pair > 0) {
    //         if ($pair_match >= 1) 
    //         {
    //             #$pair_match_inc = $pair_match * $matching;
    //             $pair_match_inc = $pair_match;
    //             $earning_ct     = $this->db_model->sum('amount', 'earning', array('userid' => $id, 'type' => 'Matching Income', 'date' => date('Y-m-d'))) + 0;
    //             $package        = $this->db_model->select('signup_package', 'member', array('id' => $id));
    //             #$capping        = $this->db_model->select('capping', 'product', array('id' => $package)) ?? 10000;
    //             if($pair_match_inc > $capping){
    //                 $data = array(
    //                     'userid'        => $id,
    //                     'amount'        => $pair_match_inc - $capping,
    //                     'type'          => 'Matching Income',
    //                     'reason'        => 'Capping Reached',
    //                 );
    //                 $this->db->insert('laps_earning', $data);
    //                 $pair_match_inc = $capping;
                    
    //             } else{
    //                 $pair_match_inc = $pair_match_inc;
    //             }

    //         #
    //             // 1 Sponcer Income Deduction (6%)
    //             $sponsor_deduct = ($pair_match_inc * 6) / 100;
    //             $this->db->insert('earning_deduct', [
    //                 'userid' => $id,
    //                 'amount' => $sponsor_deduct,
    //                 'type'   => 'SPONSOR INCOME 6% DEDUCTION',
    //                 'date'   =>  date('Y-m-d')
    //             ]);

    //             // 2 Reserve Fund Deduction (5%)
    //             $reserve_deduct = ($pair_match_inc * 5) / 100;
    //             $this->db->insert('earning_deduct', [
    //                 'userid' => $id,
    //                 'amount' => $reserve_deduct,
    //                 'type'   => 'Reserve Fund 5%',
    //                 'date'   =>  date('Y-m-d')
    //             ]);
                
    //             // 3 Repurchase Fund Deduction (3%) And send in product wallet for use  purchase 
    //             $repurchase_fund = ($pair_match_inc * 3) / 100;
    //             $this->db->insert('earning_deduct', [
    //                 'userid' => $id,
    //                 'amount' => $repurchase_fund,
    //                 'type'   => 'Repurchase Fund 3%',
    //                 'date'   =>  date('Y-m-d')
    //             ]);

    //             $product_wallet = $this->db_model->select('balance', 'product_wallet', array('userid' => $id));

    //             if ($product_wallet) {
    //                 $new_balance = $product_wallet + $repurchase_fund;
    //                 $this->db->set('balance', $new_balance)
    //                         ->where('userid', $id)
    //                         ->update('product_wallet');
    //             } else {
    //                 $this->db->insert('product_wallet', [
    //                     'userid'  => $id,
    //                     'balance' => $repurchase_fund,
    //                 ]);
    //             }

    //             #

    //             $final_amount = $pair_match_inc - ($sponsor_deduct + $reserve_deduct + $repurchase_fund);

    //             $this->db->set('reserve_fund', 'reserve_fund + '.$reserve_deduct, FALSE)
    //             ->where('id', $id)
    //             ->update('member');
    //             #print_r($this->db->last_query());die();

    //         # 
                
    //             $reserve_fund = $this->db_model->select('reserve_fund', 'member', array('id' => $id));
                
    //             while ($reserve_fund >= 5000) {

    //                 $new_rank = $rank;

    //                 switch ($rank) {
    //                     case 'Bronze Associate':
    //                         $new_rank = 'Silver Associate';
    //                         break;

    //                     case 'Silver Associate':
    //                         $new_rank = 'Gold Associate';
    //                         break;

    //                     case 'Gold Associate':
    //                         $new_rank = 'Platinum Associate';
    //                         break;

    //                     case 'Platinum Associate':
    //                         $new_rank = 'Ruby Associate';
    //                         break;

    //                     case 'Ruby Associate':
    //                         $new_rank = 'Ruby Associate'; // Final Rank
    //                         break;

    //                     default:
    //                         $new_rank = $rank;
    //                         break;
    //                 }

    //                 $this->db->set('rank', $new_rank)
    //                         ->where('id', $id)
    //                         ->update('member');

    //                 $this->db->set('reserve_fund', 'reserve_fund - 5000', FALSE)
    //                         ->where('id', $id)
    //                         ->update('member');

    //                 $reserve_fund -= 5000;

    //                 if ($new_rank == 'Ruby Associate') {
    //                     $product_wallet = $this->db_model->select('balance', 'product_wallet', array('userid' => $id));

    //                     if ($product_wallet) {
    //                         $new_balance = $product_wallet + 5000;
    //                         $this->db->set('balance', $new_balance)
    //                                 ->where('userid', $id)
    //                                 ->update('product_wallet');
    //                     } 

    //                     $this->db->insert('earning', [
    //                         'userid'      => $id,
    //                         'amount'      => 5000,
    //                         'type'        => 'Reserve Fund Amount Deposited into Product Wallet',
    //                         'date'        => date('Y-m-d'),
    //                         'status'      => 'paid',
    //                         'payout_date' => date('Y-m-d'),
    //                     ]);
    //                 }

    //                 $rank = $new_rank;
    //             }

    //         #

    //             $array = array(
    //                 'paid_a_pv'   => $a_side_p   + $pair_match,
    //                 'paid_b_pv'   => $b_side_p   + $pair_match,
    //                 'total_pairs' => $total_pair + $pair_match,
    //             );
    //             $this->db->where('id', $id);
    //             $this->db->update('member', $array);
    //             $this->pay_earning($id, '', 'Matching Income', $final_amount, '', $pair_match);
    //             #print_r($this->db->last_query());die();
    //             #
                
    //                 // ----------- SPONSOR INCOME DISTRIBUTION ON MATCHING -------------
    //                 $sponsor_id = $this->db_model->select('sponsor', 'member', array('id' => $id)); // Direct sponsor
    //                 $pair_income = $pair_match_inc; // Total matching income
    //                 $level = 1;

    //                 while($sponsor_id && $level <= 5){
                        
    //                     // Check sponsor active direct users
    //                     $direct_paid = $this->db_model->count_all('member', array('sponsor' => $sponsor_id, 'topup >' => 0));

    //                     if($direct_paid >= 6){
                            
    //                         if($level == 1){
    //                             $amount = $pair_income * 0.04;
    //                         } elseif($level == 2){
    //                             $amount = $pair_income * 0.01;
    //                         } elseif($level == 3){
    //                             $amount = $pair_income * 0.005;
    //                         } elseif($level == 4){
    //                             $amount = $pair_income * 0.0025;
    //                         } elseif($level == 5){
    //                             $amount = $pair_income * 0.0025;
    //                         }

    //                         // Pay sponsor income
    //                         $this->pay_earning($sponsor_id, $id, 'Sponsor Income (Level '.$level.')', $amount, $level, $pair_match);
    //                     }

    //                     // Move to next sponsor
    //                     $sponsor_id = $this->db_model->select('sponsor', 'member', array('id' => $sponsor_id));
    //                     $level++;
    //                 }


    //             #
    //         }
    //     #}
    // }
    
    public function process_binary($id, $data)
    {
        // Fetch member data and package info
        $member = $this->db_model->select_multi('*', 'member', array('id' => $id));
        if (!$member) return;
        $package_id = !empty($member->signup_package) ? $member->signup_package : $member->join_package;
        $package_ratio = $this->get_package_ratio($package_id, $id);

        $product = $this->db_model->select_multi('*', 'product', array('id' => $package_id));
        $capping = floatval($product->capping ?? 0);
        $package_price = floatval($product->prod_price ?? 0);

        // Sales Matching Bonus percentage dynamically fetched from product configuration
        $matching_pct = (isset($product->matching_income) && floatval($product->matching_income) > 0)
            ? floatval($product->matching_income)
            : 0;

        if ($matching_pct <= 0 || $package_price <= 0) {
            return;
        }

        $a_side = ($member->total_a_pv + $member->self_power_a) ?? 0;
        $b_side = ($member->total_b_pv + $member->self_power_b) ?? 0;
        
        $a_side_p = $member->paid_a_pv;
        $b_side_p = $member->paid_b_pv;
        $total_pair = $member->total_pairs;

        $available_left  = $a_side - $a_side_p;
        $available_right = $b_side - $b_side_p;

        if ($available_left <= 0 || $available_right <= 0) {
            return; 
        }

        $pair_match = floor(min($available_left, $available_right));
        
        if ($pair_match >= 1) 
        {
            // Dynamic Sales Matching Bonus: package_price * (matching_pct / 100) * package_ratio
            $pair_match_inc = ($pair_match * ($package_price * ($matching_pct / 100.0))) * $package_ratio; 

            // Capping logic (if defined on product table)
            $effective_capping = $capping * $package_ratio;
            if ($pair_match_inc > $effective_capping && $effective_capping > 0) {
                $laps_data = array(
                    'userid' => $id,
                    'amount' => $pair_match_inc - $effective_capping,
                    'type'   => 'Sales Matching Bonus',
                    'reason' => 'Capping Reached',
                );
                $this->db->insert('laps_earning', $laps_data);
                $pair_match_inc = $effective_capping;
            }

            $final_amount = $pair_match_inc;

            $array = array(
                'paid_a_pv'   => $a_side_p   + $pair_match,
                'paid_b_pv'   => $b_side_p   + $pair_match,
                'total_pairs' => $total_pair + $pair_match,
            );
            $this->db->where('id', $id)->update('member', $array);

            $this->pay_earning($id, '', 'Sales Matching Bonus', $final_amount, '', $pair_match);
        }
    }

    public function process_lvl($userid, $amount){
        $package_ratio = $this->get_package_ratio(0, $userid);
        $lvl1_amt = ((50 / 100) * $amount) * $package_ratio;
        // $lvl2_amt = (50  / 100) * $amount;
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
    
    public function process_binary_old($id, $data)
    {
        // For 2:1 or 1:2
        $total_pair = $this->db_model->select('total_pairs', 'member', array('id' => $id));
        $a_side     = $this->db_model->select('total_a_pv', 'member', array('id' => $id)) ?? 0;
        $b_side     = $this->db_model->select('total_b_pv', 'member', array('id' => $id)) ?? 0;
        $check      = FALSE;
        $package    = $this->db_model->select('signup_package', 'member', array('id' => $id));
        $capping    = $this->db_model->select('capping', 'product', array('id' => $package)) ?? 1000000;
        $matching   = $this->db_model->select('matching_income', 'product', array('id' => $package)) ?? 100;
            
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
            } elseif ($b_side + $a_side >= 3) {
                $array['paid_a_pv'] = 1;
                $array['paid_b_pv'] = 1;
                $check              = TRUE;
            }
    
            if ($check) {
                $this->db->where('id', $id);
                $this->db->update('member', $array);
                $stat = $this->pay_earning($id, '0000', 'Matching Income', $matching, '', $total_pair);
            }
        }
        
        $member_data = $this->db_model->select_multi('*', 'member', array('id' => $id));
        $a_side      = $member_data->total_a_pv ?? 0;
        $b_side      = $member_data->total_b_pv ?? 0;
        $a_side_team = $member_data->total_a ?? 0;
        $b_side_team = $member_data->total_b ?? 0;
        $a_side_p    = $member_data->paid_a_pv  ?? 0;
        $b_side_p    = $member_data->paid_b_pv  ?? 0;
        $total_pair  = $member_data->total_pairs  ?? 0;
        $available_a = $a_side - $a_side_p;
        $available_b = $b_side - $b_side_p;
        $pair_match  = min(floor($available_a), floor($available_b));

        $sponsor_count = $this->db_model->count_all('member', array('sponsor' => $id,'topup >' => 0));
        if ($pair_match >= 1 and $sponsor_count >= 2 and $total_pair >= 1) {
            $earning_ct     = $this->db_model->sum('amount', 'earning', array('userid' => $id, 'type' => 'Matching Income', 'date' => date('Y-m-d'))) + 0;
            $pair_match_inc = $pair_match * $matching;
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

    public function process_binary2($id, $data)
    {
        $member_data    = $this->db_model->select_multi('*', 'member', array('id' => $id));
        $binary_amt     = $this->db_model->select('matching_income', 'product', array('id' => $member_data->signup_package));
        $total_pair     = $member_data->total_pairs;
        $pair_match     = min(($member_data->total_a_pv - $member_data->paid_a_pv), ($member_data->total_b_pv - $member_data->paid_b_pv));
        $pair_max       = max(($member_data->total_a_pv - $member_data->paid_a_pv), ($member_data->total_b_pv - $member_data->paid_b_pv));
        $pair_match_inc = $binary_amt * floor($pair_match / 25);
        // $pair_match_inc = (10 / 100) * $pair_match;
        if($pair_match_inc > 0){
            $member_data   = $this->db_model->select_multi('*', 'member', array('id' => $id));
            $this->pay_earning($id, '', 'Matching Income', $pair_match_inc,'', $pair_match);
            $array = array(
                'paid_a_pv'        => $member_data->paid_a_pv   + $pair_match,
                'paid_b_pv'        => $member_data->paid_b_pv   + $pair_match,
                'total_pairs'      => $member_data->total_pairs + $pair_match,
            );
            $this->db->where('id', $id);
            $this->db->update('member', $array);
        }
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
                $stat =  $this->pay_earning($id, '0000', 'Booster Matching Income',100,'',$total_pair);
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
                    $this->pay_earning($id, '', 'Booster Matching Income',100,'',$pair_match);
                }
            }
        }
    }
    
    
    public function process_binary3($id, $data)
    {
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




