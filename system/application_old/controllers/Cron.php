<?php

defined('BASEPATH') OR exit('No direct script access allowed');
class Cron extends CI_Controller
{

	public function __construct()
	{
		parent::__construct();
        $this->send_wp          = $this->db_model->select('send_wp', 'global_setting', array('id' => 1));
        $this->send_mail        = $this->db_model->select('send_mail', 'global_setting', array('id' => 1));
        $this->payout_day       = $this->db_model->select('payout_days', 'global_setting', array('id' => 1));
        $this->payout_method    = $this->db_model->select('payout_method', 'global_setting', array('id' => 1));
        $this->enable_autopool  = $this->db_model->select('enable_autopool', 'global_setting', array('id' => 1));
	} 

	public function index()
	{
		$this->newcron2();
		if($this->enable_autopool == 1){
		    $this->autopool_cron();
		}
		
        // Manual method add earning in wallet 
        // Auto method will generate payout according to conditions
        if($this->payout_method == 'manual'){
            $this->payout();
        }
        
        // 0 for daily payout 
        if (date('w') == $this->payout_day or $this->payout_day == 0) {  
            $this->update_payout_new();
            #$this->fran_update_payout_new();
            
            if (date('t') == date('j')) 
            { 
                // If today is the last day of the month, send the repurchase payout.
                $this->update_repurchase_payout();
            }
        }
        
        #Royalty Income Month Wise And royalty_rank Wise
		if (date('j') == 1)  
		{  
			$this->load->model('earning');
			$this->earning->royalty_income_process_month(date('Y-m', strtotime('-1 month')));
			#$this->earning->royalty_income_process_month(date('Y-m'));
		}
        
		//redirect('wallet/generate-payout');
		redirect($_SERVER['HTTP_REFERER']);

	}

    public function autopool_cron(){
        $this->autopool_legs();
        $this->autopool_binary();
        
        if($this->payout_method == 'manual'){
            $this->payout();
        }
        
        if (date('w') == $this->payout_day or $this->payout_day == 0) {  
            $this->update_payout_new();
            $this->fran_update_payout_new();
            if (date('t') == date('j')) 
            { 
                // If today is the last day of the month, send the repurchase payout.
                $this->update_repurchase_payout();
            }
        }
    }

	public function autopool_legs()
	{
		$this->load->model('auto_pools');
		$this->auto_pools->update_legs();
		$i = 0;
		$j = $this->db_model->select('total_pool', 'autopool_setting', array('id' => 1));
		while($i < $j){
		    $i++;
		    $table = 'autopool_'.$i;
		    $this->auto_pools->update_legs($table);
		}
	}
	
	public function autopool_binary()
	{
		$this->load->model('auto_pools');
		$i = 0;
		$j = $this->db_model->select('total_pool', 'autopool_setting', array('id' => 1));
		while($i < $j){
		    $i++;
		    $table = 'autopool_'.$i;
		    $this->auto_pools->autopool_binary($table, $i);
		}
	}
	
    public function newcron2()
	{
		$this->update_first_rank();
        $this->update_legs();
        $this->binary_payout();
        $this->rank_update();
        $this->reward();
        #$this->roi();
    }

	public function update_first_rank()
	{
		$this->db->select('*')->from('member')->where('topup >', 0)->where('rank', 'Member');
		$data = $this->db->get()->result();

		foreach ($data as $result) 
		{
			$sponsor_count = $this->db_model->count_all('member', array('sponsor' => $result->id, 'topup >' => 0));

			if ($sponsor_count >= 6) 
			{
				if (trim($result->rank) == 'Member') 
				{
					$array = array('rank' => 'Bronze Associate',);
					$this->db->where('id', $result->id)->update('member', $array);
				}
			}
		}
	}


	public function update_legs()
	{
		$this->load->model('earning');
		$this->earning->update_legs();
	}

	public function binary_payout()
	{	
		#$this->db->select('*')->from('member')->where('topup >', '0')->where('total_a_pv >', 0)->where('total_b_pv >', 0);
		$this->db->select('*')->from('member')->where('total_a_pv >', 0)->where('total_b_pv >', 0);
		$data = $this->db->get()->result();
		foreach ($data as $result) {
			$this->load->model('earning');
			$data2 = array('total_a' => $result->total_a, 'total_b' => $result->total_b,'total_a_pv' => $result->total_a_pv, 'total_b_pv' => $result->total_b_pv, 'paid_a_pv' => $result->paid_a_pv, 'paid_b_pv' => $result->paid_b_pv, 'paid_a' => $result->paid_a, 'paid_b' => $result->paid_b, 'signup_package' => $result->signup_package, 'mypv' => $result->mypv, 'total_a_matching_incm' => $result->total_a_matching_incm, 'total_b_matching_incm' => $result->total_b_matching_incm, 'total_c_matching_incm' => $result->total_c_matching_incm, 'paid_a_matching_incm' => $result->paid_a_matching_incm, 'paid_b_matching_incm' => $result->paid_b_matching_incm);
			$this->earning->process_binary($result->id, $data2);
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
		$count_product_roi = $this->db_model->count_all('product', array('roi >' => 0.00));
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

    /*hide on 26-02-2026 for now we send repurechase and others income seprately
	public function update_payout_new()
	{
		$cname = config_item('company_name');
		$web   = $_SERVER['HTTP_HOST'];

		// Global Setting से Social Welfare Percentage
		$social_welfare_percent = floatval($this->db_model->select('social_welfare_fund', 'global_setting', ['id' => 1]));

		// सभी pending earning को group करें userid के हिसाब से
		$this->db->select('userid, status, SUM(amount) AS total_balance');
		$this->db->from('earning');
		$this->db->where('status', 'Pending');
		$this->db->group_by(['userid', 'status']);
		$groups = $this->db->get()->result_array();

		foreach ($groups as $grp) 
		{
			$userid = $grp['userid'];
			$total_balance = $grp['total_balance'];

			// Member की activation_type निकालें
			$activation_type = $this->db_model->select('activation_type', 'member', ['id' => $userid]);

			// ===== FREE USER CASE =====
			if (strtolower(trim($activation_type)) == 'free') 
			{
				if ($total_balance >= 5000) 
				{
					// Step 1️⃣: 5000 deduct करें और earning_deduct table में डालें
					$this->db->insert('earning_deduct', [
						'userid' => $userid,
						'amount' => 5000,
						'type'   => 'For Your Free Activation Charges',
						'date'   => date('Y-m-d')
					]);

					// Step 2️⃣: Member को activate करें
					$this->db->set([
						'signup_package'  => 3,
						'topup'           => 5000,
						'activation_date' => date('Y-m-d'),
						'activation_type' => 'paid'
					])
					->where('id', $userid)
					->update('member');

					// Step 3️⃣: Product Wallet अपडेट करें
					$product_wallet = $this->db_model->select('balance', 'product_wallet', array('userid' => $userid));
					if ($product_wallet) 
					{
						$new_balance = $product_wallet + 5000;
						$this->db->set('balance', $new_balance)
						->where('userid', $userid)
						->update('product_wallet');
					}
					
					// ✅ Step 4️⃣: Product Sale Entry करें (as product dispatch)
					$this->db->insert('product_sale', [
						'product_id'     => 3,
						'userid'         => $userid,
						'order_by'       => 'Member',
						'cost'           => 5000,
						'qty'            => 1,
						'pv'             => 1000,
						'payment_sataus' => 'Success',
						'date'           => date('Y-m-d')
					]);
					

					// Step 4️⃣: बचा हुआ amount payout में भेजें
					$payout_amount = $total_balance - 5000;

					// सभी pending earning को paid करें (चाहे payout_amount 0 हो या >0)
					$this->db->where('userid', $userid)
							->where('status', 'Pending')
							->update('earning', ['status' => 'Paid']);

					$social_fund  = $payout_amount * ($social_welfare_percent / 100);
					$final_payout = $payout_amount - $social_fund;

					if ($payout_amount > 0) 
					{
						// Withdraw entry (after 1% deduction)
						$this->db->insert('withdraw_request', [
							'userid' => $userid,
							'amount' => $final_payout,
							'date'   => date('Y-m-d')
						]);

						// Social Welfare Fund entry
						$this->db->insert('social_welfare_fund', [
							'userid' => $userid,
							'amount' => $social_fund,
							'type'   => $social_welfare_percent . '% Social Welfare Deduction',
							'date'   => date('Y-m-d')
						]);
					}
				} 
				else 
				{
					continue; // ₹5000 से कम होने पर hold
				}
			} 
			else 
			{
				// ===== PAID USER CASE =====

				$payout_amount = $total_balance;

				// 1% Social Welfare Fund deduction
				$social_fund  = $payout_amount * ($social_welfare_percent / 100);
				$final_payout = $payout_amount - $social_fund;


				// सभी pending earning को paid करें
				$this->db->where('userid', $userid)
						->where('status', 'Pending')
						->update('earning', ['status' => 'Paid']);

				// Withdraw entry (after 1% deduction)
				$this->db->insert('withdraw_request', [
					'userid' => $userid,
					'amount' => $final_payout,
					'date'   => date('Y-m-d')
				]);

				// Social Welfare Fund entry
				$this->db->insert('social_welfare_fund', [
					'userid' => $userid,
					'amount' => $social_fund,
					'type'   => $social_welfare_percent . '% Social Welfare Deduction',
					'date'   => date('Y-m-d')
				]);
			}
		}
	}
	*/


	/*
    public function update_payout_new()
	{
        $cname   = config_item('company_name');
        $web     = $_SERVER['HTTP_HOST'];
    
        $this->db->select('userid, status, SUM(amount) AS total_balance');
        $this->db->from('earning');
        $this->db->where('status', 'Pending');
        $this->db->group_by(['userid', 'status']);
        $groups = $this->db->get()->result_array();
        foreach ($groups as $grp) {
            $this->db->where('userid', $grp['userid']);
            $this->db->where('status', $grp['status']);
            $this->db->update('earning', ['status' => 'Paid']);
    
            $withdraw_data = array(
                'userid' => $grp['userid'],
                'amount' => $grp['total_balance'],
                'date'   => date('Y-m-d')
            );
            $this->db->insert('withdraw_request', $withdraw_data);
        }
    }*/

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
		redirect('wallet/generate-payout');
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
	###
	public function update_payout_new()
	{
		$cname = config_item('company_name');
		$web   = $_SERVER['HTTP_HOST'];

		// Global Setting से Social Welfare Percentage
		$social_welfare_percent = floatval($this->db_model->select('social_welfare_fund', 'global_setting', ['id' => 1]));

		// सभी pending earning को group करें userid के हिसाब से
		$this->db->select('userid, status, SUM(amount) AS total_balance');
		$this->db->from('earning');
		$this->db->where('status', 'Pending');
        $this->db->where('type !=', 'Repurchase Income');//26-02-2026
		$this->db->group_by(['userid', 'status']);
		$groups = $this->db->get()->result_array();

		foreach ($groups as $grp) 
		{
			$userid = $grp['userid'];
			$total_balance = $grp['total_balance'];

			// Member की activation_type निकालें
			$activation_type = $this->db_model->select('activation_type', 'member', ['id' => $userid]);

			// ===== FREE USER CASE =====
			if (strtolower(trim($activation_type)) == 'free') 
			{
				if ($total_balance >= 5000) 
				{
					// Step 1️⃣: 5000 deduct करें और earning_deduct table में डालें
					$this->db->insert('earning_deduct', [
						'userid' => $userid,
						'amount' => 5000,
						'type'   => 'For Your Free Activation Charges',
						'date'   => date('Y-m-d')
					]);

					// Step 2️⃣: Member को activate करें
					$this->db->set([
						'signup_package'  => 3,
						'topup'           => 5000,
						'activation_date' => date('Y-m-d'),
						'activation_type' => 'paid'
					])
					->where('id', $userid)
					->update('member');

					// Step 3️⃣: Product Wallet अपडेट करें
					$product_wallet = $this->db_model->select('balance', 'product_wallet', array('userid' => $userid));
					if ($product_wallet) 
					{
						$new_balance = $product_wallet + 5000;
						$this->db->set('balance', $new_balance)
						->where('userid', $userid)
						->update('product_wallet');
					}
					
					// ✅ Step 4️⃣: Product Sale Entry करें (as product dispatch)
					$this->db->insert('product_sale', [
						'product_id'     => 3,
						'userid'         => $userid,
						'order_by'       => 'Member',
						'cost'           => 5000,
						'qty'            => 1,
						'pv'             => 1000,
						'payment_sataus' => 'Success',
						'date'           => date('Y-m-d')
					]);
					

					// Step 4️⃣: बचा हुआ amount payout में भेजें
					$payout_amount = $total_balance - 5000;

					// सभी pending earning को paid करें (चाहे payout_amount 0 हो या >0)
					$this->db->where('userid', $userid)
							->where('status', 'Pending')
                            ->where('type !=', 'Repurchase Income') //26-02-2026
							->update('earning', ['status' => 'Paid']);

					$social_fund  = $payout_amount * ($social_welfare_percent / 100);
					$final_payout = $payout_amount - $social_fund;

					if ($payout_amount > 0) 
					{
						// Withdraw entry (after 1% deduction)
						$this->db->insert('withdraw_request', [
							'userid' => $userid,
							'amount' => $final_payout,
							'date'   => date('Y-m-d')
						]);

						// Social Welfare Fund entry
						$this->db->insert('social_welfare_fund', [
							'userid' => $userid,
							'amount' => $social_fund,
							'type'   => $social_welfare_percent . '% Social Welfare Deduction',
							'date'   => date('Y-m-d')
						]);
					}
				} 
				else 
				{
					continue; // ₹5000 से कम होने पर hold
				}
			} 
			else 
			{
				// ===== PAID USER CASE =====

				$payout_amount = $total_balance;

				// 1% Social Welfare Fund deduction
				$social_fund  = $payout_amount * ($social_welfare_percent / 100);
				$final_payout = $payout_amount - $social_fund;


				// सभी pending earning को paid करें
				$this->db->where('userid', $userid)
						->where('status', 'Pending')
                        ->where('type !=', 'Repurchase Income')//26-02-2026
						->update('earning', ['status' => 'Paid']);

				// Withdraw entry (after 1% deduction)
				$this->db->insert('withdraw_request', [
					'userid' => $userid,
					'amount' => $final_payout,
					'date'   => date('Y-m-d')
				]);

				// Social Welfare Fund entry
				$this->db->insert('social_welfare_fund', [
					'userid' => $userid,
					'amount' => $social_fund,
					'type'   => $social_welfare_percent . '% Social Welfare Deduction',
					'date'   => date('Y-m-d')
				]);
			}
		}
	}


    ##
    public function update_repurchase_payout()
	{
		$cname = config_item('company_name');
		$web   = $_SERVER['HTTP_HOST'];

		// Global Setting से Social Welfare Percentage
		$social_welfare_percent = floatval($this->db_model->select('social_welfare_fund', 'global_setting', ['id' => 1]));

		// सभी pending earning को group करें userid के हिसाब से
		$this->db->select('userid, status, SUM(amount) AS total_balance');
		$this->db->from('earning');
		$this->db->where('status', 'Pending');
        $this->db->where('type', 'Repurchase Income');//26-02-2026
		$this->db->group_by(['userid', 'status']);
		$groups = $this->db->get()->result_array();

		foreach ($groups as $grp) 
		{
			$userid = $grp['userid'];
			$total_balance = $grp['total_balance'];

			// Member की activation_type निकालें
			$activation_type = $this->db_model->select('activation_type', 'member', ['id' => $userid]);

			// ===== FREE USER CASE =====
			if (strtolower(trim($activation_type)) == 'free') 
			{
				if ($total_balance >= 5000) 
				{
					// Step 1️⃣: 5000 deduct करें और earning_deduct table में डालें
					$this->db->insert('earning_deduct', [
						'userid' => $userid,
						'amount' => 5000,
						'type'   => 'For Your Free Activation Charges',
						'date'   => date('Y-m-d')
					]);

					// Step 2️⃣: Member को activate करें
					$this->db->set([
						'signup_package'  => 3,
						'topup'           => 5000,
						'activation_date' => date('Y-m-d'),
						'activation_type' => 'paid'
					])
					->where('id', $userid)
					->update('member');

					// Step 3️⃣: Product Wallet अपडेट करें
					$product_wallet = $this->db_model->select('balance', 'product_wallet', array('userid' => $userid));
					if ($product_wallet) 
					{
						$new_balance = $product_wallet + 5000;
						$this->db->set('balance', $new_balance)
						->where('userid', $userid)
						->update('product_wallet');
					}
					
					// ✅ Step 4️⃣: Product Sale Entry करें (as product dispatch)
					$this->db->insert('product_sale', [
						'product_id'     => 3,
						'userid'         => $userid,
						'order_by'       => 'Member',
						'cost'           => 5000,
						'qty'            => 1,
						'pv'             => 1000,
						'payment_sataus' => 'Success',
						'date'           => date('Y-m-d')
					]);
					

					// Step 4️⃣: बचा हुआ amount payout में भेजें
					$payout_amount = $total_balance - 5000;

					// सभी pending earning को paid करें (चाहे payout_amount 0 हो या >0)
					$this->db->where('userid', $userid)
							->where('status', 'Pending')
                            ->where('type', 'Repurchase Income')//26-02-2026
							->update('earning', ['status' => 'Paid']);

					$social_fund  = $payout_amount * ($social_welfare_percent / 100);
					$final_payout = $payout_amount - $social_fund;

					if ($payout_amount > 0) 
					{
						// Withdraw entry (after 1% deduction)
						$this->db->insert('withdraw_request', [
							'userid' => $userid,
							'amount' => $final_payout,
							'date'   => date('Y-m-d')
						]);

						// Social Welfare Fund entry
						$this->db->insert('social_welfare_fund', [
							'userid' => $userid,
							'amount' => $social_fund,
							'type'   => $social_welfare_percent . '% Social Welfare Deduction',
							'date'   => date('Y-m-d')
						]);
					}
				} 
				else 
				{
					continue; // ₹5000 से कम होने पर hold
				}
			} 
			else 
			{
				// ===== PAID USER CASE =====

				$payout_amount = $total_balance;

				// 1% Social Welfare Fund deduction
				$social_fund  = $payout_amount * ($social_welfare_percent / 100);
				$final_payout = $payout_amount - $social_fund;


				// सभी pending earning को paid करें
				$this->db->where('userid', $userid)
						->where('status', 'Pending')
                        ->where('type', 'Repurchase Income')//26-02-2026
						->update('earning', ['status' => 'Paid']);

				// Withdraw entry (after 1% deduction)
				$this->db->insert('withdraw_request', [
					'userid' => $userid,
					'amount' => $final_payout,
					'date'   => date('Y-m-d')
				]);

				// Social Welfare Fund entry
				$this->db->insert('social_welfare_fund', [
					'userid' => $userid,
					'amount' => $social_fund,
					'type'   => $social_welfare_percent . '% Social Welfare Deduction',
					'date'   => date('Y-m-d')
				]);
			}
		}
	}
	
	###

}


?>
