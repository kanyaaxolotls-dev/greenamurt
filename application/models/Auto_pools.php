<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Auto_pools extends CI_Model
{

    public function __construct()
    {
        parent::__construct();
        $this->common_model->__session();
    }
   
    public function pay_earning($userid, $ref_id, $income_name, $amount, $levlno, $pair_match = 0, $secret = 0, $status = 'Inactive')
    {
        $deduct_amount = ($amount * 10) / 100;
        $check         = $this->db->get_where('earning_deduct', ['userid' => $userid, 'type' => $income_name])->row();
        $final_amount  = $amount;
        if ($check) {
            $new_total = $check->amount + $deduct_amount;
            if ($check->amount < 2000) {
                if ($new_total > 2000) {
                    $deduct_amount = 2000 - $check->amount;
                    $new_total = 2000;
                }
                $this->db->where('id', $check->id)->update('earning_deduct', [
                    'amount' => $new_total,
                    'date'   => date('Y-m-d')
                ]);
                $final_amount = $amount - $deduct_amount;
            }
        } else {
            if ($deduct_amount > 2000) {
                $deduct_amount = 2000;
            }
            $this->db->insert('earning_deduct', [
                'userid' => $userid,
                'amount' => $deduct_amount,
                'type'   => $income_name,
                'date'   => date('Y-m-d')
            ]);
            $final_amount = $amount - $deduct_amount;
        }
    
        $data = array(
            'userid'     => $userid,
            'amount'     => $final_amount,
            'type'       => $income_name,
            'type2'      => 'Autopool Income',
            'ref_id'     => $ref_id,
            'date'       => date('Y-m-d'),
            'pair_match' => $pair_match,
            'secret'     => $secret,
            'levlno'     => $levlno,
            'status'     => $status,
        );
        /* Autopool Income generation commented out
        $this->db->insert('earning', $data);
        */
        return TRUE;
    } 

    public function find_position($pool)
    {    
        $auto_setting  = $this->db_model->select_multi('leg', 'autopool_setting', array('id' => 1));
        $autopool_legs = $auto_setting->leg; 
    
        $this->db->select('*');
        $this->db->from($pool);
        for ($i = 0; $i < $autopool_legs; $i++) {
            $column = chr(65 + $i);  
            if ($i === 0) {
                $this->db->where($column, NULL);
            } else {
                $this->db->or_where($column, NULL);
            }
        }
        $this->db->limit(1);
        $query  = $this->db->get();
        $result = $query->result();
        if (empty($result)) {
            return false;  
        }
        $id = $result[0]->id;
        $position = '';
        for ($i = 0; $i < $autopool_legs; $i++) {
            $column = chr(65 + $i);
            $val = $this->db_model->select($column, $pool, array('id' => $id));
            if ($val === NULL) {
                $position = $column;
                break;
            }
        }
        return array($id, $position);
    }

    public function autopool_1($userid,$amount = 0)
    {
        list($id, $position) = $this->find_position('autopool_1');
        if($id == NULL){
            $id = 0;
        }
        else{
            $id = $id;
        }
        $data = array(
            'userid'   => $userid,
            'amount'   => $amount,
            'position' => $id,
        );
        $this->db->insert('autopool_1', $data);
        $idd  = $this->db->insert_id();
        
        $data2 = array(
            $position  => $idd,
        );
        $this->db->where('id', $id);
        $this->db->update('autopool_1', $data2);
        
        if($this->db_model->select('autopool_method', 'autopool_setting', array('id' => 1)) != 'tree'){
            $this->process_lvl($idd,1);
        }
    }
    
    public function autopool_2($userid,$amount = 0)
    {
        list($id, $position) = $this->find_position('autopool_2');
        if($id == NULL){
            $id = 0;
        }
        else{
            $id = $id;
        }
        $data=array(
            'userid'   => $userid,
            'amount'   => $amount,
            'position' => $id,
        );
        $this->db->insert('autopool_2', $data);
        $idd = $this->db->insert_id();
        
        $data2 = array(
            $position  => $idd,
        );
        $this->db->where('id', $id);
        $this->db->update('autopool_2', $data2);
        
        if($this->db_model->select('autopool_method', 'autopool_setting', array('id' => 1)) != 'tree'){
            $this->process_lvl($idd,2);
        }
    }
    
    public function autopool_3($userid,$amount = 0)
    {
        list($id, $position) = $this->find_position('autopool_3');
        if($id == NULL){
            $id = 0;
        }
        else{
            $id = $id;
        }
        $data=array(
            'userid'   => $userid,
            'amount'   => $amount,
            'position' => $id,
        );
        $this->db->insert('autopool_3', $data);
        $idd = $this->db->insert_id();
        
        $data2 = array(
            $position  => $idd,
        );
        $this->db->where('id', $id);
        $this->db->update('autopool_3', $data2);
        
        if($this->db_model->select('autopool_method', 'autopool_setting', array('id' => 1)) != 'tree'){
            $this->process_lvl($idd,3);
        }
    }
    
    public function autopool_4($userid,$amount = 0)
    {
        list($id, $position) = $this->find_position('autopool_4');
        if($id == NULL){
            $id = 0;
        }
        else{
            $id = $id;
        }
        $data=array(
            'userid'   => $userid,
            'amount'   => $amount,
            'position' => $id,
        );
        $this->db->insert('autopool_4', $data);
        $idd = $this->db->insert_id();
        
        $data2 = array(
            $position  => $idd,
        );
        $this->db->where('id', $id);
        $this->db->update('autopool_4', $data2);
        
        if($this->db_model->select('autopool_method', 'autopool_setting', array('id' => 1)) != 'tree'){
            $this->process_lvl($idd,4);
        }
    }
    
    public function autopool_5($userid,$amount = 0)
    {
        list($id, $position) = $this->find_position('autopool_5');
        if($id == NULL){
            $id = 0;
        }
        else{
            $id = $id;
        }
        $data=array(
            'userid'   => $userid,
            'amount'   => $amount,
            'position' => $id,
        );
        $this->db->insert('autopool_5', $data);
        $idd = $this->db->insert_id();
        
        $data2 = array(
            $position  => $idd,
        );
        $this->db->where('id', $id);
        $this->db->update('autopool_5', $data2);
        
        if($this->db_model->select('autopool_method', 'autopool_setting', array('id' => 1)) != 'tree'){
            $this->process_lvl($idd,5);
        }
    }
    
    public function autopool_6($userid,$amount = 0)
    {
        list($id, $position) = $this->find_position('autopool_6');
        if($id == NULL){
            $id = 0;
        }
        else{
            $id = $id;
        }
        $data=array(
            'userid'   => $userid,
            'amount'   => $amount,
            'position' => $id,
        );
        $this->db->insert('autopool_6', $data);
        $idd = $this->db->insert_id();
        
        $data2 = array(
            $position  => $idd,
        );
        $this->db->where('id', $id);
        $this->db->update('autopool_6', $data2);
        
        if($this->db_model->select('autopool_method', 'autopool_setting', array('id' => 1)) != 'tree'){
            $this->process_lvl($idd,6);
        }
    }
    
    public function autopool_7($userid,$amount = 0)
    {
        list($id, $position) = $this->find_position('autopool_7');
        if($id == NULL){
            $id = 0;
        }
        else{
            $id = $id;
        }
        $data=array(
            'userid'   => $userid,
            'amount'   => $amount,
            'position' => $id,
        );
        $this->db->insert('autopool_7', $data);
        $idd = $this->db->insert_id();
        
        $data2 = array(
            $position  => $idd,
        );
        $this->db->where('id', $id);
        $this->db->update('autopool_7', $data2);
        
        if($this->db_model->select('autopool_method', 'autopool_setting', array('id' => 1)) != 'tree'){
            $this->process_lvl($idd,7);
        }
    }
    
    public function autopool_8($userid,$amount = 0)
    {
        list($id, $position) = $this->find_position('autopool_8');
        if($id == NULL){
            $id = 0;
        }
        else{
            $id = $id;
        }
        $data=array(
            'userid'   => $userid,
            'amount'   => $amount,
            'position' => $id,
        );
        $this->db->insert('autopool_8', $data);
        $idd = $this->db->insert_id();
        
        $data2 = array(
            $position  => $idd,
        );
        $this->db->where('id', $id);
        $this->db->update('autopool_8', $data2);
        
        if($this->db_model->select('autopool_method', 'autopool_setting', array('id' => 1)) != 'tree'){
            $this->process_lvl($idd,8);
        }
    }
    
    public function autopool_9($userid,$amount = 0)
    {
        list($id, $position) = $this->find_position('autopool_9');
        if($id == NULL){
            $id = 0;
        }
        else{
            $id = $id;
        }
        $data=array(
            'userid'   => $userid,
            'amount'   => $amount,
            'position' => $id,
        );
        $this->db->insert('autopool_9', $data);
        $idd = $this->db->insert_id();
        
        $data2 = array(
            $position  => $idd,
        );
        $this->db->where('id', $id);
        $this->db->update('autopool_9', $data2);
        
        if($this->db_model->select('autopool_method', 'autopool_setting', array('id' => 1)) != 'tree'){
            $this->process_lvl($idd,9);
        }
    }
    
    public function process_lvl($userid, $inc_id)
    {
        $auto_data    = $this->db_model->select_multi('*', 'autopool_incomes', array('id' => $inc_id));
        $incomeString = $auto_data->income; 
        $amounts      = explode(',', $incomeString);
        $sponsor      = $this->db_model->select('position', 'autopool_'.$inc_id, array('id' => $userid));
        $userid2      = $this->db_model->select('userid', 'autopool_'.$inc_id, array('id' => $userid));
        $i            = 1;
        foreach ($amounts as $e) { 
            $e  = trim($e);
            if ($i == 0) {
               $pay_sponsor = $sponsor;
            }
            else {
               $pay_gen_sponsor = $this->find_level_sponsor($userid, $i, $inc_id);                      
            }            
            echo $i.'<br>';
            if ($pay_gen_sponsor > 0 && $e > 0) {
                $userid_pay   = $this->db_model->select('userid', 'autopool_'.$inc_id, array('id' => $pay_gen_sponsor));
                if($i == 2){
                    $this->pay_earning($userid_pay, $userid2, $auto_data->name, $e, $i, $pay_gen_sponsor , $inc_id,'upgrade');
                } else{
                    $this->pay_earning($userid_pay, $userid2, $auto_data->name, $e, $i, $pay_gen_sponsor , $inc_id);
                }
                $this->next_pool($userid_pay, $inc_id, $pay_gen_sponsor);
            } 
            $i++;
        }
    }

    public function next_pool($userid, $inc_id, $id)
    {
        $ct   = $this->db_model->count_all('earning', array('userid' => $userid, 'secret' => $inc_id, 'pair_match' => $id, 'levlno' => 2));
        if ($ct >= 25) {
            $next_pool = 'autopool_' . ($inc_id + 1);
            $cur_pool  = 'autopool_' . ($inc_id);
            
            if (method_exists($this, $next_pool)) {
                $status   = $this->db_model->select('status', $cur_pool, array('id' => $id));
                if($status == 0){
                    $data2 = array(
                        'status'  => 1,
                    );
                    $this->db->where('id', $id);
                    $this->db->update($cur_pool, $data2);
                    $this->$next_pool($userid);
                }
            } else {
                log_message('error', 'Method ' . $next_pool . ' does not exist');
            }
        }
        return true;
    }

    private function find_level_sponsor($sponsor, $i, $inc_id)
    {
        if ($i > 0) {
            $this->db->select('position')->from('autopool_'.$inc_id)->where(array('id' => $sponsor));
            $result = $this->db->get()->row();
            if (!$result) {
                return FALSE;
            } 
            else {
                $i = ($i - 1);
                return $this->find_level_sponsor($result->position, $i, $inc_id);                 
            }
        } 
        else {
            return $sponsor;
        }
    }

    public function update_legs($table = 'autopool_1')
    {
        $this->db->select('*')->from($table);
        $data = $this->db->get()->result();
        foreach ($data as $row) {
            $total_a = ($row->A != 0) ? $this->count_node($table, $row->A) : 0;
            $total_b = ($row->B != 0) ? $this->count_node($table, $row->B) : 0;
            $update_data = array(
                'total_a' => $total_a,
                'total_b' => $total_b
            );
            $this->db->where('id', $row->id);
            $this->db->update($table, $update_data);
        }
    }
    
    private function count_node($table, $id)
    {
        $node = $this->db->get_where($table, array('id' => $id))->row();
        if (!$node) return 0;
        $count = 1; 
        if ($node->A != 0) {
            $count += $this->count_node($table, $node->A);
        }
        if ($node->B != 0) {
            $count += $this->count_node($table, $node->B);
        }
        return $count;
    }

    public function autopool_binary($table = 'autopool_1', $tbl_id = 1)
    {
        $auto_data   = $this->db_model->select_multi('*', 'autopool_incomes', array('id' => $tbl_id));
        $this->db->select('*')->from($table)->where('total_a >', '0')->where('total_b >', '0');
		$data = $this->db->get()->result();
		foreach ($data as $tr) {
		    $binary_amount  = $auto_data->binary;
		    $capping_amount = $auto_data->capping;
            $total_pair     = $tr->total_pairs;
            $pair_match     = min(($tr->total_a - $tr->paid_a), ($tr->total_b - $tr->paid_b));
            $pair_max       = max(($tr->total_a - $tr->paid_a), ($tr->total_b - $tr->paid_b));
            $pair_match_inc = $binary_amount * $pair_match;
		    echo $binary_amount.'<hr>';
            if($pair_match_inc > $capping_amount){
                $pair_match_inc = $capping_amount;
            } else{
                $pair_match_inc = $pair_match_inc;
            }
            
            if($pair_match_inc > 0){
                $array = array(
                    'paid_a'        => $tr->paid_a      + $pair_match,
                    'paid_b'        => $tr->paid_b      + $pair_match,
                    'total_pair'    => $tr->total_pairs + $pair_match,
                );
                $this->db->where('id', $tr->id);
                $this->db->update($table, $array);
                // $this->pay_earning($tr->userid, '', 'Autopool Matching Income', $pair_match_inc,'', $pair_match);
            }
        }
    }

}


