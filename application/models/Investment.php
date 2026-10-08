<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Investment extends CI_Model
{

    public function __construct()
    {
        parent::__construct();
        $this->common_model->__session();
         $this->update_legs();
    }

    public function generate()
    {
        $this->roi_earning();
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
        $this->db->select('userid,pack_id,amount')->from('investments')->where(array('status'  => "Active",
                                                                                     
        ));
        $users = $this->db->get()->result();

        foreach ($users as $users):
            $userid        = $users->userid;
            $roi           = $this->db_model->select_multi('roi,roi_limit,roi_frequency,based', 'investment_pack', array('id' => $users->pack_id));
            $roi_frequency = $roi->roi_frequency;
            $roi_limit     = $roi->roi_limit;
            $based         = $roi->based;
            $roi           = $roi->roi;
            if ($based == "Percent") {
                $roi = ($users->amount * $roi) / 100;
            }

            if ($roi > 0) {
                $get_last_roi = $this->db_model->select('date', 'earning', array(
                    'userid' => $userid,
                    'type'   => 'ROI',
                    'secret' => $users->pack_id,
                ));
                $date         = date('Y-m-d', strtotime($get_last_roi) + (86400 * $roi_frequency));

                if (date('Y-m-d') >= $date) {
                    if ($this->db_model->count_all('earning', array(
                            'userid' => $userid,
                            'type'   => 'ROI',
                            'secret' => $users->pack_id,
                        )) < $roi_limit) {
                        $this->pay_earning($userid, '', 'ROI', $roi, '', $users->pack_id);
                    }
                }
            }

        endforeach;

    }

   public function pay_earning($userid, $ref_id, $income_name, $amount,$levlno=0, $pair_match = 0, $secret = 0)
    {
        $data = array(
            'userid'     => $userid,
            'amount'     => $amount,
            'type'       => $income_name,
            'ref_id'     => $ref_id,
            'date'       => date('Y-m-d'),
            'pair_match' => $pair_match,
            'secret'     => $secret,
            'levlno'     => $levlno,
          
        );

        $this->db->insert('earning', $data);

        return TRUE;

    }

    public function inv_earning($userid, $sponsor, $packageid)
    {

        ###############################################################
        #
        # Direct or Referal Income First
        #
        ##############################################################
        $packageid = $this->db_model->select('pack_id', 'investments', array('id' => $packageid));
        $data      = $this->db_model->select_multi('amount, direct_income, level_income,sp_level_income, matching_income, based', 'investment_pack', array('id' => $packageid));
        if ($data->matching_income > "0") {

            if ($data->based == "Fixed") {
                $my_business = $data->matching_income;
            } else {
                $my_business = $data->amount * $data->matching_income / 100;
            }
            $arr = array('my_business' => $my_business);
            $this->db->where('id', $userid); 
            $this->db->update('member', $arr);
        }

        if ($data->direct_income > "0" && trim($sponsor) !== '') {
            if ($data->based == "Fixed") {
                $direct_income = $data->direct_income;
            } else {
                $direct_income = ($data->amount * $data->direct_income / 100);
            }
            $this->pay_earning($sponsor, $userid, 'Referral Income', $direct_income);
        }

        ## Insert Sponsor Level Income (Level wise sponsor income)
            if (trim($data->sp_level_income) !== "") {              
                    $ex1 = explode(',', $data->sp_level_income);
                    $i = 1;

                    foreach ($ex1 as $e1) {                    
                        $e1 = trim($e1);
                        if ($i == 0) {
                            $pay_gen_sponsor = $sponsor;
                        } else {                     
                            $pay_gen_sponsor =  $this->find_sp_level_sponsor($userid, $i);
                        }
                        

                        if ($pay_gen_sponsor > 0 && $e1 > 0) {                      
                            if ($data->based == "Fixed") {
                            $sp_level_income = $e1;
                            } else {
                                $sp_level_income = ($data->amount * $e1 / 100);
                            }
                             $topup = $this->db_model->select('topup', 'member', array('id' => $pay_gen_sponsor));
                            $levlno=$i;
                            if($topup ==1000){
                            $this->pay_earning($pay_gen_sponsor, $userid, 'Sponsor Level Inc', $sp_level_income,$levlno);
                            }
                        }
                $i++;
            }
        }           


        ## NOW Level Income
        if (trim($data->level_income) !== "") {
            $ex = explode(',', $data->level_income);
            $i  = 0;
            foreach ($ex as $e) {
                $e = trim($e);
                if ($i == 0) {
                    $pay_sponsor = $sponsor;
                } else {
                    $pay_sponsor = $this->find_level_sponsor($sponsor, $i);
                }
                if ($pay_sponsor > 0 && $e > 0) {
                    if ($data->based == "Fixed") {
                        $level_income = $e;
                    } else {
                        $level_income = ($data->amount * $e / 100);
                    }
                    $this->pay_earning($pay_sponsor, $userid, 'Level Income', $level_income);
                }
                $i++;
            }
        }


        return TRUE;

    }

    private function find_level_sponsor($sponsor, $i)
    {
        if ($i > 0) {
            $this->db->select('position')->from('member')->where(array('id' => $sponsor));
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


}