<?php

defined('BASEPATH') OR exit('No direct script access allowed');
class Cron extends CI_Controller
{

	public function __construct()
	{
		parent::__construct();
		date_default_timezone_set('Asia/Kolkata');
        $this->send_wp     = $this->db_model->select('send_wp', 'global_setting', array('id' => 1));
        $this->send_mail   = $this->db_model->select('send_mail', 'global_setting', array('id' => 1));
        $this->payout_day  = $this->db_model->select('payout_days', 'global_setting', array('id' => 1));
	} 

// public function reprocess_matching_income()
// {
//     $this->load->model('earning'); // adjust to your actual model name

// 		$this->db->select('*')->where('type', 'Matching Income');
// 		$records = $this->db->get('earning')->result();
//     foreach ($records as $row) {
//         $this->earning->process_lvl($row->userid, $row->amount);
//     }

//     return TRUE;
// }

	public function index()
	{
		$this->daily_payout();
	}

    public function newcron2(){

        $this->load->model('earning');

        $this->update_legs();
        $this->binary_payout();
        $this->rank_update();

        $this->update_payout_new();
        $this->generate_withdrawals();

		redirect('income/withdraws_list/Un-Paid');
    }

    public function daily_payout(){

        $this->generate_withdrawals();
        $this->fran_update_payout_new();

		redirect('income/withdraws_list/Un-Paid');
    }

    public function weekly_payout(){
        // Payout is converted to DAILY - forward directly to daily_payout
        $this->daily_payout();
    }

	public function update_legs()
	{
		$this->load->model('earning');
		$this->earning->update_legs();
	}

	public function binary_payout()
	{
		$top_id = config_item('top_id');
		$this->db->select('*')->from('member');
		if (!empty($top_id)) {
			$this->db->group_start()->where('topup >', 0)->or_where('id', $top_id)->group_end();
		} else {
			$this->db->where('topup >', 0);
		}
		$this->db->where('total_a_pv >', 0)->where('total_b_pv >', 0);
		$data = $this->db->get()->result();

		$log_dir = APPPATH . 'logs';
		if (!is_dir($log_dir)) {
			@mkdir($log_dir, 0777, true);
		}
		$cron_match_log = $log_dir . DIRECTORY_SEPARATOR . 'matching_bonus_' . date('Y-m-d') . '.log';
		$header = "\n========================================\n"
				. "[MATCHING BONUS / BINARY RUN]\n"
				. "TIME: " . date('Y-m-d H:i:s') . "\n"
				. "MEMBERS EVALUATED: " . (is_array($data) ? count($data) : 0) . "\n"
				. "========================================\n";
		@file_put_contents($cron_match_log, $header, FILE_APPEND);

		foreach ($data as $result) {
			$this->load->model('earning');
			$data2 = array('total_a' => $result->total_a, 'total_b' => $result->total_b,'total_a_pv' => $result->total_a_pv, 'total_b_pv' => $result->total_b_pv, 'paid_a_pv' => $result->paid_a_pv, 'paid_b_pv' => $result->paid_b_pv, 'paid_a' => $result->paid_a, 'paid_b' => $result->paid_b, 'signup_package' => $result->signup_package, 'mypv' => $result->mypv, 'total_a_matching_incm' => $result->total_a_matching_incm, 'total_b_matching_incm' => $result->total_b_matching_incm, 'total_c_matching_incm' => $result->total_c_matching_incm, 'paid_a_matching_incm' => $result->paid_a_matching_incm, 'paid_b_matching_incm' => $result->paid_b_matching_incm);
			$log_result = $this->earning->process_binary($result->id, $data2, true);
			if (is_array($log_result)) {
				@file_put_contents($cron_match_log, implode("\n", $log_result) . "\n\n", FILE_APPEND);
			}
		}
	}
	
	public function reward()
	{
		$this->load->model('earning');
		$this->earning->reward_process();
	}

	public function rank_update()
	{
		$this->load->model('earning');
		$this->earning->rank_process();
	}

	public function roi()
	{ 
		$count_product_roi = $this->db_model->count_all('product', array('roi >' =>0.00));
		if (0 < $count_product_roi) {
			$this->load->model('earning');
			$this->earning->roi_earning();
		}
	}

	public function payout()
	{
		$this->db->select('id,userid,type,amount')->where('status', 'Pending');
		$data = $this->db->get('earning')->result();
		foreach ($data as $e) { 
			if($e->type == "Repurchase Income"){
			    
			}
			else{
				$cur_balance = $this->db_model->select('balance', 'wallet', array('userid' => $e->userid));
				$data = array('balance' => $e->amount + $cur_balance);
				$this->db->where('userid', $e->userid);
				$this->db->update('wallet', $data);
				
				$data = array('status' => 'Paid');
				$this->db->where('id',$e->id);
				$this->db->update('earning', $data);
			}
	    } 
	}
	
	public function update_payout_new()
    {
        $this->db->select('userid, SUM(amount) AS total_balance');
        $this->db->from('earning');
        $this->db->where('status', 'Pending');
        $this->db->group_by('userid');
        $groups = $this->db->get()->result_array();

        foreach ($groups as $grp)
        {
            if ($grp['total_balance'] <= 0) {
                continue;
            }

            $cur_balance = $this->db_model->select('balance', 'wallet', ['userid' => $grp['userid']]) + 0;
            $this->db->where('userid', $grp['userid']);
            $this->db->update('wallet', ['balance' => $cur_balance + $grp['total_balance']]);

            $this->db->where('userid', $grp['userid']);
            $this->db->where('status', 'Pending');
            $this->db->update('earning', ['status' => 'Paid']);
        }
    }

	public function generate_withdrawals()
    {
        $min          = floatval(config_item('min_withdraw'));
        $admin_charge = floatval(config_item('admin_charges'));
        $payout_tax   = floatval(config_item('payout_tax'));
        $deduct_pc    = $admin_charge + $payout_tax;

        $log_dir = APPPATH . 'logs';
        if (!is_dir($log_dir)) {
            @mkdir($log_dir, 0777, true);
        }
        $log_file         = $log_dir . DIRECTORY_SEPARATOR . 'payout_daily_' . date('Y-m-d') . '.log';
        $payout_debug_log = $log_dir . DIRECTORY_SEPARATOR . 'payout_debug.log';

        $this->db->select('userid, balance, pan_no')->where('balance >=', $min);
        $res = $this->db->get('wallet')->result();
        $eligible_count = is_array($res) ? count($res) : 0;

        $log_header = "\n========================================\n"
                    . "[DAILY PAYOUT START]\n"
                    . "TIME: " . date('Y-m-d H:i:s') . "\n"
                    . "MIN WITHDRAW: " . $min . "\n"
                    . "DEDUCT %: " . $deduct_pc . "% (Admin Charge: {$admin_charge}%, Payout Tax: {$payout_tax}%)\n"
                    . "ELIGIBLE WALLET COUNT: " . $eligible_count . "\n"
                    . "========================================\n";

        @file_put_contents($log_file, $log_header, FILE_APPEND);
        @file_put_contents($payout_debug_log, $log_header, FILE_APPEND);

        foreach ($res as $row) {
            $user_id = $row->userid;
            $gross   = (float) $row->balance;
            if ($gross < $min) {
                continue;
            }

            $this->db->trans_begin();

            $wallet_fresh = $this->db->select('balance, pan_no')->where('userid', $user_id)->get('wallet')->row();
            if (!$wallet_fresh || (float)$wallet_fresh->balance < $min) {
                $this->db->trans_rollback();
                $entry = "USER ID: {$user_id} | SKIPPED: Balance below minimum threshold ({$min})\n";
                @file_put_contents($log_file, $entry, FILE_APPEND);
                @file_put_contents($payout_debug_log, $entry, FILE_APPEND);
                continue;
            }

            $cur_balance = (float)$wallet_fresh->balance;
            $tax_amount  = round($cur_balance * $deduct_pc / 100, 2);
            $pan_no      = !empty($wallet_fresh->pan_no) ? $wallet_fresh->pan_no : ($row->pan_no ?? '');

            $withdraw_data = array(
                'userid'      => $user_id,
                'amount'      => round($cur_balance),
                'tax'         => $tax_amount,
                'pan_no'      => $pan_no,
                'date'        => date('Y-m-d'),
                'withdraw_in' => 'Bank',
                'status'      => 'Un-Paid',
            );

            $insert_ok = $this->db->insert('withdraw_request', $withdraw_data);
            $insert_id = $this->db->insert_id();

            if (!$insert_ok || !$insert_id) {
                $db_err = $this->db->error();
                $this->db->trans_rollback();
                $entry = "USER ID: {$user_id} | WITHDRAW INSERT FAILED: " . json_encode($db_err) . " | TRANSACTION ROLLBACK\n";
                @file_put_contents($log_file, $entry, FILE_APPEND);
                @file_put_contents($payout_debug_log, $entry, FILE_APPEND);
                continue;
            }

            $this->db->where('userid', $user_id);
            $update_ok = $this->db->update('wallet', array('balance' => 0));

            if (!$update_ok) {
                $db_err = $this->db->error();
                $this->db->trans_rollback();
                $entry = "USER ID: {$user_id} | WALLET UPDATE FAILED: " . json_encode($db_err) . " | TRANSACTION ROLLBACK\n";
                @file_put_contents($log_file, $entry, FILE_APPEND);
                @file_put_contents($payout_debug_log, $entry, FILE_APPEND);
                continue;
            }

            if ($this->db->trans_status() === FALSE) {
                $db_err = $this->db->error();
                $this->db->trans_rollback();
                $entry = "USER ID: {$user_id} | TRANSACTION STATUS FALSE: " . json_encode($db_err) . " | TRANSACTION ROLLBACK\n";
                @file_put_contents($log_file, $entry, FILE_APPEND);
                @file_put_contents($payout_debug_log, $entry, FILE_APPEND);
            } else {
                $this->db->trans_commit();
                $entry = "USER ID: {$user_id} | WALLET BALANCE: {$cur_balance} | GROSS: {$withdraw_data['amount']} | TAX: {$tax_amount} | INSERT ID: {$insert_id} | WALLET UPDATE: SUCCESS (New Balance: 0) | TRANSACTION COMMITTED\n";
                @file_put_contents($log_file, $entry, FILE_APPEND);
                @file_put_contents($payout_debug_log, $entry, FILE_APPEND);
            }
        }
    }

    public function debug_payout($userid = null)
    {
        $this->matching_bonus_log($userid);
    }

    public function matching_log($userid = null)
    {
        $this->matching_bonus_log($userid);
    }

    public function matching_bonus_log($userid = null)
    {
        if (empty($userid)) {
            $userid = $this->input->get('userid');
        }
        if (empty($userid)) {
            $userid = config_item('top_id');
        }
        if (empty($userid)) {
            $userid = '1001';
        }

        $this->load->model('earning');

        if (!headers_sent()) {
            header('Content-Type: text/plain; charset=utf-8');
        }

        echo "=================================================================\n";
        echo "MATCHING BONUS DIAGNOSTIC / PAYOUT LOG\n";
        echo "TIME: " . date('Y-m-d H:i:s') . "\n";
        echo "USER ID: " . $userid . "\n";
        echo "=================================================================\n\n";

        $logs = $this->earning->process_binary($userid, array(), true);

        if (is_array($logs)) {
            foreach ($logs as $line) {
                echo $line . "\n";
            }
        } else {
            echo "Result: " . var_export($logs, true) . "\n";
        }

        echo "\n=================================================================\n";
        echo "WALLET STATUS FOR USER " . $userid . ":\n";
        $wallet = $this->db->get_where('wallet', array('userid' => $userid))->row();
        if ($wallet) {
            echo "Current Wallet Balance: Rs. " . $wallet->balance . "\n";
            echo "PAN: " . ($wallet->pan_no ?? 'N/A') . "\n";
        } else {
            echo "No wallet record found for user " . $userid . "\n";
        }

        $min = floatval(config_item('min_withdraw'));
        echo "Minimum Withdrawal Threshold: Rs. " . $min . "\n";
        echo "Eligible for Daily Withdrawal? " . (($wallet && (float)$wallet->balance >= $min) ? "YES" : "NO") . "\n";
        echo "=================================================================\n";
    }

    public function debug_daily_payout()
    {
        if (!headers_sent()) {
            header('Content-Type: text/plain; charset=utf-8');
        }

        $min          = floatval(config_item('min_withdraw'));
        $admin_charge = floatval(config_item('admin_charges'));
        $payout_tax   = floatval(config_item('payout_tax'));
        $deduct_pc    = $admin_charge + $payout_tax;

        echo "=================================================================\n";
        echo "DAILY PAYOUT DIAGNOSTIC RUN\n";
        echo "TIME: " . date('Y-m-d H:i:s') . "\n";
        echo "MIN WITHDRAW: Rs. " . $min . "\n";
        echo "ADMIN CHARGE: " . $admin_charge . "%\n";
        echo "PAYOUT TAX: " . $payout_tax . "%\n";
        echo "TOTAL DEDUCTION: " . $deduct_pc . "%\n";
        echo "=================================================================\n\n";

        $this->db->select('w.userid, w.balance, w.pan_no, m.name, m.phone')
                 ->from('wallet w')
                 ->join('member m', 'm.id = w.userid', 'left')
                 ->where('w.balance >=', $min);
        $res = $this->db->get()->result();

        echo "Eligible Wallets (Balance >= " . $min . "): " . count($res) . "\n\n";

        foreach ($res as $row) {
            $gross = (float)$row->balance;
            $tax   = round($gross * $deduct_pc / 100, 2);
            $net   = $gross - $tax;
            echo "User ID: {$row->userid} | Name: {$row->name} | Balance: Rs. {$gross} | Tax/Admin: Rs. {$tax} | Net: Rs. {$net}\n";
        }

        echo "\n=================================================================\n";
        echo "To execute daily payout and create Un-Paid withdraw requests, visit:\n";
        echo site_url('cron/daily_payout') . "\n";
        echo "=================================================================\n";
    }


    public function fran_update_payout_new() {
        $this->db->trans_start();
        
        try {
            $this->db->select('userid, DATE(date) as payout_date2, GROUP_CONCAT(id) as record_ids, SUM(amount) AS total_balance');
            $this->db->from('franchisee_earning');
            $this->db->where('status', 'Pending');
            $this->db->group_by(['userid', 'payout_date2']);
            $groups = $this->db->get()->result_array();
    
            foreach ($groups as $grp) {
                $record_ids = explode(',', $grp['record_ids']);
                $this->db->where_in('id', $record_ids);
                $this->db->update('franchisee_earning', ['status' => 'Paid']);
                
                $withdraw_data = array(
                    'userid' => $grp['userid'],
                    'amount' => $grp['total_balance'],
                    'date'   => $grp['payout_date2']
                );
                $this->db->insert('fran_withdraw_request', $withdraw_data);
            }
            
            $this->db->trans_complete();
            return $this->db->trans_status();
            
        } catch (Exception $e) {
            $this->db->trans_rollback();
            log_message('error', 'Error: ' . $e->getMessage());
            return false;
        }
    }
    
    public function update_payout(){
        $cname   = config_item('company_name');
        $web     = $_SERVER['HTTP_HOST'];
        $this->db->select('*')->from('wallet');
        $this->db->where('balance >', 0);
        $dataa   = $this->db->get()->result_array();
        foreach($dataa as $tr){
            
            $data = array(
                'userid' => $tr['userid'],
                'amount' => $tr['balance'],
                'date'   => date('Y-m-d'),
            );
            $this->db->insert('withdraw_request', $data);
            
            $array = array(
                'balance'       => '0',
            );
            $this->db->where('userid', $tr['userid']);
            $this->db->update('wallet', $array);
            
            $u_data  = $this->db_model->select_multi('*', 'member', array('id' => $tr['userid']));
            $phone   = $u_data->phone;
            $name    = $u_data->name;
            $amount  = $tr['balance'];
            if($this->send_wp){
                $message = "🏢 $cname\n\nDear $name,\nYour *payout of Rs. $amount.* Generated successfully !! \nVisit  $web";
                $this->common_model->send_message($phone, $message);
            }
        }
        return true;
    }
	
    public function reverse_invalid_binary()
    {
        $this->load->model('earning');
        $reversed = $this->earning->reverse_invalid_binary();
        $count    = count($reversed);
        echo 'Reversed binary income for ' . $count . ' member(s) without 3 active directs.'
           . ($count > 0 ? ' IDs: ' . implode(', ', $reversed) : '');
    }



	public function admin_topup()
	{
		redirect('users/topup-member');
	}

	public function member()
	{
		redirect('member'); 
	}

	public function generate_payout()
	{
		redirect('income/withdraws_list');
	}

	public function complete_registration()
	{
		$this->load->dbutil();
		$this->dbutil->optimize_database();
		redirect('site/complete_registration');
	}

	public function check_user()
	{
		$user = trim($this->input->post('user'));
		if (0 < $this->db_model->count_all('franchisee', array('username' => $user))) {
			echo '<span style="color: red; background-color: moccasin">The Username is Taken !</span>';
		}
		else {
			echo '<span style="color: green; background-color: #d6e9c6">The Username is Available !</span>';
		}
	}

	public function get_stock_qty()
	{
		$fran_id   = $this->input->post('fran_id');
		$prod_name = $this->input->post('prod');
		$prodid    = $this->db_model->select('id', 'product', array('prod_name' => $prod_name));
		$qty       = $this->db_model->select('available_qty', 'franchisee_stock', array('franchisee_id' => $fran_id, 'product_id' => $prodid));
		if ($qty == '') {
			$qty = 0;
		}
		echo $qty;
	}

	public function get_products()
	{
		$data = trim($this->input->get('term'));
		$this->db->select('prod_name')->from('product')->where('status', 'Selling')->where('qty !=', '0')->like('prod_name', $data, 'BOTH');
		$data = $this->db->get()->result();
		foreach ($data as $val) {
			$res[] = $val->prod_name;
		}
		echo json_encode($res);
	}

	public function get_own_products()
	{
		$data = trim($this->input->get('term'));
		$this->db->select('id')->from('product')->like('prod_name', $data, 'BOTH');
		$data = $this->db->get()->result();
		foreach ($data as $val) {
			$res[] = $val->prod_name;
		}
		echo json_encode($res);
	}

	public function get_wallet_balance($uid)
	{
		$uid = $this->common_model->filter($uid);
		$balance = $this->db_model->select('balance', 'wallet', array('userid' => $uid));
		echo $balance;
	}

}


?>
