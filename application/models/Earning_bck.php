<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Earning extends CI_Model
{

    public function __construct()
    {
        parent::__construct();
        $this->common_model->__session();
        $this->update_legs();
    }

   public function update_legs()
    {

        $this->db->select('id,A,B,C,D,E')->from('member')->where('topup >', '0');
        $data = $this->db->get()->result();

        foreach ($data as $result) {
            if ($result->A !== '0') {
                $count_a = $this->count_node($result->A);
                $total_a_pv = $this->count_pv($result->A) + $this->db_model->select('mypv', 'member', array('id' => $result->A));
                $total_a_matching = $this->count_matching($result->A) + $this->db_model->select('my_business', 'member', array('id' => $result->A));
                $total_a_investment = $this->count_investment($result->A) + $this->db_model->select('topup', 'member', array('id' => $result->A));
            }
            else {
                $count_a = 0;
                $total_a_pv = 0;
                $total_a_matching = 0;
                $total_a_investment = 0;
            }

            if ($result->B !== '0') {
                $count_b = $this->count_node($result->B);
                $total_b_pv = $this->count_pv($result->B) + $this->db_model->select('mypv', 'member', array('id' => $result->B));
                $total_b_matching = $this->count_matching($result->B) + $this->db_model->select('my_business', 'member', array('id' => $result->B));
                $total_b_investment = $this->count_investment($result->B) + $this->db_model->select('topup', 'member', array('id' => $result->B));
            }
            else {
                $count_b = 0;
                $total_b_pv = 0;
                $total_b_matching = 0;
                $total_b_investment = 0;
            }

            if ($result->C !== '0') {
                $count_c = $this->count_node($result->C);
                $total_c_pv = $this->count_pv($result->C) + $this->db_model->select('mypv', 'member', array('id' => $result->C));
                $total_c_matching = $this->count_matching($result->C) + $this->db_model->select('my_business', 'member', array('id' => $result->C));
            }
            else {
                $count_c = 0;
            }

            if ($result->D !== '0') {
                $count_d = $this->count_node($result->D);
                $total_d_pv = $this->count_pv($result->D) + $this->db_model->select('mypv', 'member', array('id' => $result->D));
                $total_d_matching = $this->count_matching($result->D) + $this->db_model->select('my_business', 'member', array('id' => $result->D));
            }
            else {
                $count_d = 0;
            }

            if ($result->E !== '0') {
                $count_e = $this->count_node($result->E);
                $total_e_pv = $this->count_pv($result->E) + $this->db_model->select('mypv', 'member', array('id' => $result->E));
                $total_e_matching = $this->count_matching($result->E) + $this->db_model->select('my_business', 'member', array('id' => $result->E));
            }
            else {
                $count_e = 0;
            }
            $total_member_pv = $count_a + $count_b + $count_c + $count_d + $count_e;
            
            $data = array('total_a' => $count_a, 'total_b' => $count_b, 'total_c' => $count_c, 'total_d' => $count_d, 'total_e' => $count_e,'total_member_pv'=>$total_member_pv, 'total_a_pv' => $total_a_pv, 'total_b_pv' => $total_b_pv, 'total_c_pv' => $total_c_pv, 'total_d_pv' => $total_d_pv, 'total_e_pv' => $total_e_pv, 'total_a_matching_incm' => $total_a_matching, 'total_b_matching_incm' => $total_b_matching, 'total_c_matching_incm' => $total_c_matching, 'total_d_matching_incm' => $total_d_matching, 'total_e_matching_incm' => $total_e_matching, 'total_a_investment' => $total_a_investment, 'total_b_investment' => $total_b_investment);
            
            $this->db->where('id', $result->id);
            $this->db->update('member', $data);
        }
    }

    public function count_node($id, $i = 0)
    {
        if ($i == 0) {
            $top_up = $this->db_model->select('topup', 'member', array('id' => $id));

            if (0 < $top_up) {
                $i = $i + 1;
            }
        }

        $this->db->select('id,topup')->where('position', $id);
        $data = $this->db->get('member')->result();
        $countdata = $this->db_model->count_all('member', array('position' => $id, 'topup >' => '0'));
        $i = $i + $countdata;

        foreach ($data as $result) {
            if ($result->id) {
                $i = $this->count_node($result->id, $i);
            }
        }

        return $i;
    }

    public function count_pv($id, $i = 0)
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
        $data = $this->db->get('member')->result();
        $countdata = $this->db_model->sum('topup', 'member', array('position' => $id, 'topup >' => '0'));
        $i = $i + $countdata;

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


    public function roi_earning()
    {
        $this->db->select('id,signup_package')->from('member')->where(array('topup >' => 0));
        $users = $this->db->get()->result();
      

        foreach ($users as $users):
            $userid = $users->id;
           
            $roi = $this->db_model->select_multi('roi,roi_limit,roi_frequency', 'product', array('id' => $users->signup_package));
             $sp = $this->db_model->select('sponsor', 'product', array('id' => $users->signup_package));
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
       
               if (date('Y-m-d') > $date) {
                    if ($this->db_model->count_all('earning', array( 
                            'userid' => $userid,
                            'type' => 'ROI',
                            'secret' => $users->signup_package,
                        )) < $roi_limit) {
                        $levlno='0';
                          $trans_id = rand(10,1001);

                        $this->pay_earning($userid, $trans_id, 'ROI', $roi,$users->signup_package, $levlno);
                    }
                }
            }
 
        endforeach;
 
    }

    public function pay_earning($userid, $ref_id, $income_name,$amount,$levlno,$pair_match = 0, $secret = 0)
     
    {
        $data = array(
            'userid' => $userid,
            'amount' => $amount,
            'type' => $income_name,
            'ref_id' => $ref_id,
            'date' => date('Y-m-d'),
            'pair_match' => $pair_match,
            'secret' => $secret,
            'levlno' => $levlno,
        );
        
        $this->db->insert('earning', $data);
        return TRUE;

    }

    //New code updated on 03/04/2019
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

        if ($paid_pair <= 0 && $pair_max >= config_item('binary_frst_ratio') && $pair_match >= config_item('binary_2nd_ratio' && $min>0)) {
   // print_r('Paid Pair: '.$paid_pair);print_r('<br>Min Pair : '.$pair_match); print_r('<br>Max Pair : '.$pair_max);print_r('<br>Min Paid Matching '.$min);die();

            if ($min > $capping) {
                $min = $capping;
            }
            # First Binary.
           
            $this->pay_earning($id, '', 'Matching Income', $min, $pair_match);
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

        } else if ($pair_match >= config_item('binary_2nd_ratio') && $paid_pair > 0 && $min>0) {
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
            $this->pay_earning($id, '', 'Matching Income', $min, $pair_match);
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

    // public function process_binary($id, $data)
    // {

    //    var_dump("TEada");die();
    //     $min = min(($data['total_a_matching_incm'] - $data['paid_a_matching_incm']), ($data['total_b_matching_incm'] - $data['paid_b_matching_incm']));
    //     $pair_match = min(($data['total_a'] - $data['paid_a']), ($data['total_b'] - $data['paid_b']));
    //     $pair_max = max(($data['total_a'] - $data['paid_a']), ($data['total_b'] - $data['paid_b']));
    //     $paid_pair = min($data['paid_a'], $data['paid_b']);
    //     $per_user_earning = $min / $pair_match;
    //     $prod_capping = $this->db_model->select('capping', 'product', array('id' => $data['signup_package']));
    //     $capping = $this->db_model->sum('amount', 'earning', array('userid' => $id, 'date' => date('Y-m-d')));
    //     $capping1 = $prod_capping - $capping;
        
    //     // print_r("Pay to:".$id);print_r("<br>Matching Income: ".$min."<br>Pair Match :". $pair_match."<br>Pair Max: ".$pair_max."<br>Paid Pair: ".$paid_pair."<br>Per User Earning: ".$per_user_earning."<br>Prod Capping:".$prod_capping);die();

    //     if ($paid_pair <= 0 && $pair_max >= config_item('binary_frst_ratio') && $pair_match >= config_item('binary_2nd_ratio')) {

    //         // if ($min > $capping) {
    //         //     $min = $capping;
    //         // }
      
    //         # First Binary.
    //         $this->pay_earning($id, '', 'Matching Income3', $min, $pair_match);
    //         if ($data['total_a'] > 0) {
    //             $paid_a = config_item('binary_frst_ratio');
    //             $paid_b = 1;
    //             $paid_a_match = ($per_user_earning * config_item('binary_frst_ratio'));
    //             $paid_b_match = $per_user_earning;
    //         } else {
    //             $paid_a = 1;
    //             $paid_b = config_item('binary_frst_ratio');
    //             $paid_a_match = $per_user_earning;
    //             $paid_b_match = $per_user_earning * config_item('binary_frst_ratio');
    //         }
    //         $array = array(
    //             'paid_a' => $paid_a,
    //             'paid_b' => $paid_b,
    //             'paid_a_matching_incm' => $paid_a_match,
    //             'paid_b_matching_incm' => $paid_b_match,
    //         );
    //         $this->db->where('id', $id);
    //         $this->db->update('member', $array);

    //     } else if ($pair_match >= config_item('binary_2nd_ratio') && $paid_pair > 0) {
    //         # Second and after ward binary
    //         //print_r($paid_pair);print_r($pair_match); print_r($pair_max);die();
    //         /* ******************* For Conditional Binary ********************

    //          for ($i = 0; $i < $pair_match; $i++) {
    //                         $paid_pair = $paid_pair + 1;
    //                         if ($paid_pair == "4") {
    //                             $this->pay_earning($id, '', 'Matching Income5', '1', '1');
    //                         } else {
    //                             $this->pay_earning($id, '', 'Matching Income6', $per_user_earning, '1');
    //                         }
    //                     }


    //           /****************************************************************/
       
    //         // if ($min > $capping) {
    //         //     $min = $capping;
    //         // }
    //        //print_r("Pay to:".$id);print_r("<br>Min: ".$min."<br>Pair Match :". $pair_match."<br>Pair Max: ".$pair_max."<br>Paid Pair: ".$paid_pair."<br>Per User Earning: ".$per_user_earning."<br>Prod Capping:".$prod_capping."Capping1:".$capping1);die();

    //         $this->pay_earning($id, '', 'Matching Income4', $min, $pair_match);
    //         $array = array(
    //             'paid_a' => $data['paid_a'] + $pair_match,
    //             'paid_b' => $data['paid_b'] + $pair_match,
    //             'paid_a_matching_incm' => $data['paid_a_matching_incm'] + $min,
    //             'paid_b_matching_incm' => $data['paid_b_matching_incm'] + $min,
    //         );
    //         $this->db->where('id', $id);
    //         $this->db->update('member', $array);
    //     } else {
        
    //    // print_r($paid_pair);print_r($pair_match); print_r($pair_max);die();
    //     // 1L:1R is satisfied and remaining is 0, still it will hold the payout till 1 new member is added

         
    //   }

    // } 
 
 
  public function repurchase($order_id)
    {

         $this->db->select('product_id, userid, cost, qty')->from('product_sale')->where(array('orderid'=> $order_id));
         $order_data = $this->db->get()->result();
   
         foreach($order_data as $order_detail)
         {
        $userid = $order_detail->userid;
        $product_id = $order_detail->product_id;
        $qty = $order_detail->qty;
    
        $sponsor = $this->db_model->select('position', 'member', array('id' => $userid));

        $this->repurchase_earning($userid, $sponsor, $product_id, $qty);
        }

    }

  

    public function repurchase_earning($userid, $sponsor, $packageid, $qty)
    {  
        if (config_item('enable_gap_commission') !== "Yes") {
            $this->reg_earning($userid, $sponsor, $packageid, false, $qty);
        } else {

            $data     = $this->db_model->select_multi('mypv, total_a_pv, total_b_pv, total_c_pv, total_d_pv, total_e_pv', 'member', array('id' => $userid));

            $total_pv = $data->total_a_pv + $data->total_b_pv + $data->total_c_pv + $data->total_d_pv + $data->total_e_pv + $data->mypv;

            $this->db->where(array(
                                 'total_pv <=' =>
                                     $total_pv,
                             )); 
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

       

            // if($dealer_price<=0){
            //     $net_profit=$product_price-$profit_amt;
            // }else{
            //      $net_profit=$dealer_price-$profit_amt;
            // }
            
         
            
            // $earning = ($net_profit * $result->amount / 100) * $qty;

            // $levlno="0";
            // $this->pay_earning($userid, 'Self', $result->income_name, $earning,$levlno);

            // $this->db->where(array(
            //                      'total_pv <=' =>
            //                          $total_pv,
            //                  )); 
            // $this->db->order_by('id', 'ASC');
            // $ex = $this->db->get('gap_commission_setting')->result();
          
                $dt1=date('Y-m-01');
                $dt2=date('Y-m-d');


                    $i = 0;
                    foreach ($result as $e) {
                       // $e = trim($e);
                        if ($i == 0) {
                          
                            $pay_sponsor = $userid;
                        } else {
                            $pay_sponsor = $this->find_level_sponsor($userid, $i);
                        }
                        
                       
                        if ($pay_sponsor > 0) {
                            
                            $amt = ($product_price * $e->amount / 100) * $qty;
                         
                            if ($amt > 0) {
                                $levlno=$i;
                                  
                                $this->pay_earning($pay_sponsor, $userid, $e->income_name, $amt,$levlno);
                            }
                        }
                        $i++;
                    }
        }
    }

     

    //   public function repurchase_earning($userid, $sponsor, $packageid, $qty)
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
    //                                  $total_pv,
    //                          ));
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
    
    public function reg_earning($userid, $sponsor, $packageid, $need_topup = TRUE, $qty = 1)
    {   

        $get_topup = $this->db_model->select('topup', 'member', array('id' => $userid));

        if ($get_topup > "0" or $need_topup !== TRUE):

            ###############################################################
            #
            # Direct or Referal Income First
            #
            ##############################################################
            $data = $this->db_model->select_multi('prod_price, direct_income, level_income,sponser_level_inc, pv', 'product', array('id' => $packageid));  

             // $total_sp = $this->db_model->count_all('member', array(
             //    'sponsor' => $sponsor, ));

            if ($data->direct_income > "0.00" && trim($sponsor) !== '') { 
                $levlno='0';
                $this->pay_earning($sponsor, $userid, 'Referral Reward', $data->direct_income,$levlno);
            } 


            // $this->flexi_income();
            //  $this->single_leg_income($userid);

            //Auto upgrade plan with pool 
            // if($get_topup==100){
            //     $this->starter_income($get_topup,$userid);
            // }
            // if($get_topup==600){
            //     $this->deluxe_income($get_topup,$userid);
            // }else 


            // if($get_topup==3000){
            //     $this->premium_income($get_topup,$userid);
            // }else if($get_topup==20000){
            //     $this->silver_income($get_topup,$userid);
            // }else if($get_topup==100000){
            //     $this->gold_income($get_topup,$userid);
            // }else if($get_topup==500000){
            //     $this->super_income($get_topup,$userid);
            // }else if($get_topup==2500000){
            //     $this->diamond_income($get_topup,$userid);
            // }
 
            ## Insert Sponsor Level Income (Level wise sponsor income)
            if (trim($data->sponser_level_inc) !== "") {              
                    $ex1 = explode(',', $data->sponser_level_inc);
                    $i = 1;

                    foreach ($ex1 as $e1) {                    
                        $e1 = trim($e1);
                        if ($i == 0) {
                            $pay_gen_sponsor = $sponsor;
                        } else {                     
                            $pay_gen_sponsor =  $this->find_sp_level_sponsor($userid, $i);
                        }

                        if ($pay_gen_sponsor > 0 && $e1 > 0) {                      
                            $this->pay_earning($pay_gen_sponsor, $userid,'Sp Level Inc',$e1,$i);    
                         }
                        $i++;
                    }
                }           
            // return TRUE;

        
            ## NOW Level Income
            if (trim($data->level_income) !== "") {
                $ex = explode(',', $data->level_income);               
                $i = 1;

                foreach ($ex as $e) {                    
                    $e = trim($e);
                    if ($i == 0) {
                       $pay_sponsor = $sponsor;
                    } else {
                       $pay_sponsor = $this->find_level_sponsor($userid, $i);                       
                    }                   
                    $total = $this->db_model->select_multi('total_a,total_b,topup', 'member', array('id' => $pay_sponsor));
                    $total_sp = $this->db_model->count_all('member', array('sponsor' => $sponsor, ));

                    ## code for upgrade to next level                        
                    $tota_team = $total->total_a + $total->total_b;

                    if ($pay_sponsor > 0 && $e > 0) {
                                                 
                        if($tota_team == 128){
                     
                          $wallet_bal = $this->db_model->select('balance', 'wallet', array('userid' => $pay_sponsor));         
                          $new_wbal=array('balance'=>$wallet_bal - 3000);$this->db->where('userid', $pay_sponsor);$this->db->update('wallet', $new_wbal);
                          $array = array('topup' => 3000);$this->db->where('id', $pay_sponsor);$this->db->update('member', $array);
                          $total_sp = $this->db_model->count_all('member', array('sponsor' => $pay_sponsor));
                         
                          $up_data = array( 
                                'userid' => $pay_sponsor,
                                'upgrade_amt' => '3000',
                                'package_id' => 'Premium Income',
                                'status' => 'Active',
                                'upgrade_at' => date('Y-m-d H:i:s'),
                            );
                        $this->db->insert('upgrad_level', $up_data); 

                        $this->pay_earning($pay_sponsor, $userid,'Level Income',$e,$i);
                        
                        $delux_id = $this->db_model->select('id', 'member', array('id' => $pay_sponsor),array('topup' => 3000)) ;  

                        $this->premium_income($topup =3000,$delux_id);                  
                       
                        }else if($tota_team < 128 && $total_sp>= 2){
                            $this->pay_earning($pay_sponsor, $userid,'Level Income',$e,$i);  
                        }
                       
                       }

                    $findTopup = $this->db_model->select_multi('id,topup', 'member', array('id' => $pay_sponsor)); 

                    if($findTopup->topup == 20000){
                        $this->silver_income($topup = 20000,$pay_sponsor); 
                    }else if($findTopup->topup == 100000){
                        $this->gold_income($topup = 100000,$pay_sponsor);
                    }else if($findTopup->topup == 500000){
                        $this->super_income($topup = 500000,$pay_sponsor);
                    }else if($findTopup->topup == 2500000){
                        $this->diamond_income($topup = 2500000,$pay_sponsor);
                    }

                    $i++;
                }
            }

        endif;  
        return TRUE;

    }

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
                    'total_a >=' =>$res->A,
                    'total_b >=' => $res->B,
                    'total_c >=' => $res->C,
                    'total_d >=' => $res->D,
                    'total_e >=' => $res->E,
                    'join_time >= ' . $duration,
                    'topup >' => '0',
                ));


             elseif ($res->based_on == "Other"):

                $this->db->select('id')->from('member')->where(array(
                    'total_member_pv >=' => $res->total_member,
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
                    'mypv >=' => $res->total_member,
                    'join_time >= '. $duration,
                    'topup >' => '0',
                ));

                $this->db->select('id')->from('member')->where(array(
                 
                    'mypv >=' => $res->total_member,
                    'join_time >= '. $duration,
                    'topup >' => '0',
                ));

                
            endif;

            $data = $this->db->get()->result();
          
            foreach ($data as $result) {
                $array = array(
                    'rank' => $res->rank_name,
                );
                 // var_dump($array);die();
                $this->db->where('id', $result->id);
                $this->db->update('member', $array);
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
                {$this->pay_earning($em->id, $userid,'Single Leg Inc','500',1);
                 }else if($count_sp >= 1 && $em->total_a == 150)
                {$this->pay_earning($em->id, $userid,'Single Leg Inc','1000',2);
                }else if($count_sp >= 2 && $em->total_a ==350)
                { $this->pay_earning($em->id, $userid,'Single Leg Inc','2000',3);
                }else if($count_sp >= 3 && $em->total_a == 750)
                {$this->pay_earning($em->id, $userid,'Single Leg Inc','3500',4);
                }else if($count_sp >= 4 && $em->total_a == 1550)
                { $this->pay_earning($em->id, $userid,'Single Leg Inc','5000',5);
                }else if($count_sp >= 5 && $em->total_a == 3150)
                { $this->pay_earning($em->id, $userid,'Single Leg Inc','10000',6);
                }else if($count_sp >= 6 && $em->total_a == 6350)
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

                    //$this->pay_earning($em->id, $userid,'Single Leg Inc','20000',7);
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
     
           $this->db->select('id,total_a,total_b')->from('member')->where('topup',$topup);
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
}