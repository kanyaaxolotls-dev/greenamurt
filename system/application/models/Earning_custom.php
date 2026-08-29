<?php
/***************************************************************************************************

 **************************************************************************************************/

defined('BASEPATH') OR exit('No direct script access allowed');

class Earning extends CI_Model
{

    public function __construct()
    {
        parent::__construct();
        $this->common_model->__session();
    }

    public function roi_earning()
    {
        $this->db->select('id,signup_package')->from('member')->where(array('topup >' => "0"));
        $users = $this->db->get()->result();
        foreach ($users as $users):
            $userid = $users->id;
            $roi = $this->db_model->select_multi('roi,roi_limit,roi_frequency', 'product', array('id' => $users->signup_package));
            $roi_frequency = $roi->roi_frequency;
            $roi_limit = $roi->roi_limit;
            $roi = $roi->roi;
            if ($roi > 0) {
                $get_last_roi = $this->db_model->select('date', 'earning', array(
                    'userid' => $userid,
                    'type' => 'ROI',
                    'secret' => $users->signup_package,
                ));
                $date = date('Y-m-d', strtotime($get_last_roi) + (86400 * $roi_frequency));
                if (date('Y-m-d') >= $date) {
                    if ($this->db_model->count_all('earning', array(
                            'userid' => $userid,
                            'type' => 'ROI',
                            'secret' => $users->signup_package,
                        )) < $roi_limit) {
                        $this->pay_earning($userid, '', 'ROI', $roi, '', $users->signup_package);
                    }
                }
            }

        endforeach;

    }

    public function pay_earning($userid, $ref_id, $income_name, $amount, $pair_match = 0, $secret = 0)
    {
        $data = array(
            'userid' => $userid,
            'amount' => $amount,
            'type' => $income_name,
            'ref_id' => $ref_id,
            'date' => date('Y-m-d'),
            'pair_match' => $pair_match,
            'secret' => $secret,
        );

        $this->db->insert('earning', $data);

        return TRUE;

    }

    public function process_binary($id, $data)
    {
        $min = min(($data['total_a_matching_incm'] - $data['paid_a_matching_incm']), ($data['total_b_matching_incm'] - $data['paid_b_matching_incm']));
        $pair_match = min(($data['total_a'] - $data['paid_a']), ($data['total_b'] - $data['paid_b']));
        $pair_max = max(($data['total_a'] - $data['paid_a']), ($data['total_b'] - $data['paid_b']));
        $paid_pair = min($data['paid_a'], $data['paid_b']);
        $per_user_earning = $min / $pair_match;
        $prod_capping = $this->db_model->select('capping', 'product', array('id' => $data['signup_package']));
        $capping = $this->db_model->sum('amount', 'earning', array('userid' => $id, 'date' => date('Y-m-d')));
        $capping = $prod_capping - $capping;

        if ($paid_pair <= 0 && $pair_max >= config_item('binary_frst_ratio') && $pair_match >= config_item('binary_2nd_ratio')) {
   // print_r('Paid Pair: '.$paid_pair);print_r('<br>Min Pair : '.$pair_match); print_r('<br>Max Pair : '.$pair_max);print_r('<br>Min Paid Matching '.$min);die();

            if ($min > $capping) {
                $min = $capping;
            }
            # First Binary.

            $this->pay_earning($id, '', 'Matching Income33', $min, $pair_match);
            if ($data['total_a'] > 0) {
                $paid_a = config_item('binary_frst_ratio');
                $paid_b = 1;
                $paid_a_match = ($per_user_earning * config_item('binary_frst_ratio'));
                $paid_b_match = $per_user_earning;
            } else {
                $paid_a = 1;
                $paid_b = config_item('binary_frst_ratio');
                $paid_a_match = $per_user_earning;
                $paid_b_match = $per_user_earning * config_item('binary_frst_ratio');
            }
            $array = array(
                'paid_a' => $paid_a,
                'paid_b' => $paid_b,
                'paid_a_matching_incm' => $paid_a_match,
                'paid_b_matching_incm' => $paid_b_match,
            );
           
            $this->db->where('id', $id);
            $this->db->update('member', $array);

        } else if ($pair_match >= config_item('binary_2nd_ratio') && $paid_pair > 0) {
            # Second and after ward binary
//print_r($paid_pair);print_r($pair_match); print_r($pair_max);die();
            /* ******************* For Conditional Binary ********************

             for ($i = 0; $i < $pair_match; $i++) {
                            $paid_pair = $paid_pair + 1;
                            if ($paid_pair == "4") {
                                $this->pay_earning($id, '', 'Matching Income5', '1', '1');
                            } else {
                                $this->pay_earning($id, '', 'Matching Income6', $per_user_earning, '1');
                            }
                        }


              /****************************************************************/
            //die("dnnnnnnnnnnnnnndfdf");

            if ($min > $capping) {
                $min = $capping;
            }
            $this->pay_earning($id, '', 'Matching Income4', $min, $pair_match);
            $array = array(
                'paid_a' => $data['paid_a'] + $pair_match,
                'paid_b' => $data['paid_b'] + $pair_match,
                'paid_a_matching_incm' => $data['paid_a_matching_incm'] + $min,
                'paid_b_matching_incm' => $data['paid_b_matching_incm'] + $min,
            );

            $this->db->where('id', $id);
            $this->db->update('member', $array);
        } else {
        
        //print_r($paid_pair);print_r($pair_match); print_r($pair_max);die();
        // 1L:1R is satisfied and remaining is 0, still it will hold the payout till 1 new member is added

         
      }

    }

    public function repurchase($order_id)
    {
        $order_detail = $this->db_model->select_multi('product_id, userid, cost, qty', 'product_sale', array('id' => $order_id));

        $userid = $order_detail->userid;
        $product_id = $order_detail->product_id;
        $qty = $order_detail->qty;

        $sponsor = $this->db_model->select('sponsor', 'member', array('id' => $userid));
        $this->repurchase_earning($userid, $sponsor, $product_id, $qty);

    }

    public function repurchase_earning($userid, $sponsor, $packageid, $qty)
    {
        if (config_item('enable_gap_commission') !== "Yes") {
            $this->reg_earning($userid, $sponsor, $packageid, FALSE, $qty);
        } else {
            $data = $this->db_model->select_multi('mypv, total_a_pv, total_b_pv, total_c_pv, total_d_pv, total_e_pv', 'member', array('id' => $userid));
            $total_pv = $data->total_a_pv + $data->total_b_pv + $data->total_c_pv + $data->total_d_pv + $data->total_e_pv + $data->mypv;

            $this->db->where(array(
                'total_pv <=' =>
                    $total_pv,
            ));
            $this->db->order_by('id', 'DESC');
            $result = $this->db->get('gap_commission_setting')->row();
            $product_price = $this->db_model->select_multi('prod_price,pv', 'product', array('id' => $packageid));
            $pv = $product_price->pv;
            $product_price = $product_price->prod_price;
            if ($pv > "0") {
                $mypv = $data->mypv + ($pv * $qty);
                $arr = array('mypv' => $mypv);
                $this->db->where('id', $userid);
                $this->db->update('member', $arr);
            }

            $earning = ($product_price * $result->amount / 100) * $qty;
            $this->pay_earning($userid, 'Self', $result->income_name, $earning);

            $this->db->select('amount, income_name');
            $this->db->where(array(
                'total_pv <=' =>
                    $total_pv,
            ));
            $this->db->order_by('id', 'DESC');
            $ex = $this->db->get('gap_commission_setting')->result();

            $i = 0;
            foreach ($ex as $e) {
                $e = trim($e);
                if ($i == 0) {
                    $pay_sponsor = $sponsor;
                } else {
                    $pay_sponsor = $this->find_level_sponsor($sponsor, $i);
                }
                if ($pay_sponsor > 0) {
                    $amt = ($product_price * ($result->amount - $e->amount) / 100) * $qty;
                    if ($amt > 0) {
                        $this->pay_earning($pay_sponsor, $userid, $e->income_name, $amt);
                    }
                }
                $i++;
            }

        }
    }

    /**
     * @param $userid
     * @param $sponsor
     * @param $packageid
     * @param bool $need_topup
     * @param int $qty
     * @return bool
     */
    public function reg_earning($userid, $sponsor, $packageid, $need_topup = TRUE, $qty = 1)
    {
        $get_topup = $this->db_model->select('topup', 'member', array('id' => $userid));
        if ($get_topup > "0" or $need_topup !== TRUE):

            ###############################################################
            #
            # Direct or Referal Income First
            #
            ##############################################################
            $data = $this->db_model->select_multi('prod_price, direct_income, level_income, pv', 'product', array('id' => $packageid));
           /* if ($data->pv > "0") {
                
                   $id = $this->db_model->select('id', 'member', array('A >'=>'0', 'B >'=> '0', 'id'=>$sponsor));
                if($id > 0){
                    $sponsor = $this->db_model->select('sponsor', 'member', array('id'=>$sponsor));
                 $array=array(
                     'userid'=>$sponsor,
                     'amount'=>'500',
                     'type'=>'Referral Income',
                     'ref_id'=>$id,
                     'date'=>date('Y-m-d'),
                     'status'=>'Paid',
                     );
                $this->db->insert('earning',$array);
                }
                $mypv = $this->db->select('mypv', 'member', array('id' => $userid)) + ($data->pv * $qty);
                $arr = array('mypv' => $mypv);
                $this->db->where('id', $userid);
                $this->db->update('member', $arr);
            }*/
            if ($data->direct_income > "0.00" && trim($sponsor) !== '') {
                $this->pay_earning($sponsor, $userid, 'Referral Income1', $data->direct_income);
            }

            ## NOW Level Income
            if (trim($data->level_income) !== "") {
                $ex = explode(',', $data->level_income);
                $i = 0;
                foreach ($ex as $e) {
                    $e = trim($e);
                    echo "Lavel :".$i.", Income :".$e."<br>";
                    if ($i == 0) {
                        $pay_sponsor = $sponsor;
                    //  if ($pay_sponsor > 0 && $e > 0) {
                    //     $this->pay_earning($pay_sponsor, $userid, 'Level Income', $e);
                    // }
                    } else {
                        $result = $this->db_model->select_multi('position, sponsor', 'member', array('id' => $userid));
                        if ($result->position!=$result->sponsor ){
                            echo "I Am In result->position sponsor ";
                            $pay_sponsor  = $result->position;
                            $pay_sponsor = $this->find_level_sponsor($result->position, $i);
                            if ($pay_sponsor > 0 && $e > 0) {$this->pay_earning($pay_sponsor, $userid, 'Level Income', $e);}}

                            if ($pay_sponsor == ''){return TRUE;}      

                        else
                        {

                            $pay_sponsor = $this->find_level_sponsor($sponsor, $i);
                            if ($pay_sponsor == ''){
                               return TRUE;
                            }
                            if ($pay_sponsor > 0 && $e > 0) {
                                $this->pay_earning($pay_sponsor, $userid, 'Level Income', $e);
                            }
                       }
                    }
                    $i++;
                }
            }

        endif;
        return TRUE;

    }



    private function find_level_sponsor($sponsor, $i)
    {
            echo "var_dump CODE PRINTING";
            var_dump($sponsor);
            echo "test".$i;
            echo "var_dump  END <br>";
        if ($i > 0) {

            $this->db->select('sponsor')->from('member')->where(array('id' => $sponsor));
            $result = $this->db->get()->row(); 
            if (!$result) {
                return FALSE;
            } else {
                $i = ($i - 1);
                return $this->find_level_sponsor($result->sponsor, $i);
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
                
                $this->pay_earning($sponsor, $userid, 'Referral Income', ($amount * $data->direct_income / 100));
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
                        $this->pay_earning($pay_sponsor, $userid, 'Survey Income', $e);
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

    public function reward_process()
    {
        $reward = $this->db->get('reward_setting')->result();
        foreach ($reward as $res) {
            $duration = date('Y-m-d', '-' . $res->reward_duration);
            if ($duration == date('Y-m-d')) {
                $duration = date('Y-m-d', '-10 Years');
            }
            $this->db->select('id')->from('member')->where(array(
                'total_a >=' => $res->A,
                'total_b >=' => $res->B,
                'total_c >=' => $res->C,
                'total_d >=' => $res->D,
                'total_e >=' => $res->E,
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
    }

    public function rank_process()
    {
        $this->db->order_by('id', 'ASC');
        $rank = $this->db->get('rank_system')->result();
        foreach ($rank as $res) {
            $duration = date('Y-m-d', '-' . $res->rank_duration);
            if ($duration == date('Y-m-d')) {
                $duration = date('Y-m-d', '-10 Years');
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
                $array = array(
                    'rank' => $res->rank_name,
                );
                $this->db->where('id', $result->id);
                $this->db->update('member', $array);
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
}