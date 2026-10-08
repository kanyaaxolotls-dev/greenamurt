<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Wallet extends CI_Controller
{
    /**
     * Income Section for Admin Only
     */
    public function __construct()
    {
        parent::__construct();
        if ($this->login->check_session() == FALSE && $this->login->check_member() == FALSE) {
            redirect(site_url('site/login'));
        }
        if ($this->login->check_session() == FALSE && $this->login->check_member() == TRUE) {
            $member = $this->db->get_where('member', array('id' => $this->session->user_id))->row();
            $pkg_id = ($member && !empty($member->signup_package)) ? $member->signup_package : (($member && !empty($member->join_package)) ? $member->join_package : 1);
            $is_active = ($member && $member->status == 'Active' && !empty($member->activation_date));

            if ($pkg_id != 1) {
                if (!$is_active) {
                    redirect(site_url('member/quiz_center'));
                }
            } else {
                $quiz_passed = $this->db->get_where('quiz_results', array(
                    'userid' => $this->session->user_id, 
                    'status' => 'Pass'
                ))->row();
                if (!$quiz_passed) {
                    redirect(site_url('member/quiz_center'));
                }
            }
        }
       
        $this->load->library('pagination');
    }
    public function support($t = null){
        if($t=='R'){
           $t = "Rejected";
        }
        elseif($t=='A'){
           $t = "Approved";
        }
        else{
           $t = "pending";
        }
        $this->db->select('*')->where('status',$t);
        $data['data']   = $this->db->get('deposite')->result();
        // var_dump($data['data']);die();
        $data['title']  = 'Deposit History';
        $data['layout'] = 'support/deposit_history.php';
        $this->load->view('admin/index', $data);
    }
    
     public function reject_fund_request($id){
        $array = array(
            'status' => 'Rejected', 
        );
        $this->db->where('id', $id);
        $this->db->update('deposite', $array);
        $this->session->set_flashdata('common_flash', '<div class="alert alert-danger">Request Rejected</div>');
        redirect('wallet/support');
     }
     
     public function approve_fund_request($id){
            $balance = $this->db_model->select_multi('amount,userid,status', 'deposite', array('id' => $id));
            if($balance && $balance->status != 'Approved'){
                $this->db->trans_start();

                $array = array(
                    'status' => 'Approved', 
                );
                $this->db->where('id', $id);
                $this->db->update('deposite', $array);
                
                $w_id    = $balance->userid;
                $w_amt   = (float)$balance->amount;
                
                // Credit to Main E-Wallet
                $chk_w = $this->db->get_where('wallet', array('userid' => $w_id))->row();
                if ($chk_w) {
                    $new_b = round((float)$chk_w->balance + $w_amt, 2);
                    $this->db->where('userid', $w_id)->update('wallet', array('balance' => $new_b));
                } else {
                    $this->db->insert('wallet', array('userid' => $w_id, 'balance' => $w_amt));
                }

                $data10 = array(
                    'userid'       => $balance->userid,
                    'amount'       => $w_amt,
                    'type'         => "Credit",
                    'ref_id'       => "DEPOSIT_REQ_" . $id,
                    'other'        => "Deposit Request #" . $id . " Approved by Admin",
                    'created_date' => date('Y-m-d H:i:s'),
                );
                $this->db->insert('wallet_transaction', $data10);

                $this->db->trans_complete();

                if ($this->db->trans_status() === FALSE) {
                    $this->session->set_flashdata('common_flash', '<div class="alert alert-danger">Transaction failed while approving deposit.</div>');
                } else {
                    $this->session->set_flashdata('common_flash', '<div class="alert alert-success">Deposit Request #' . $id . ' approved and ₹' . number_format($w_amt, 2) . ' credited to User #' . $w_id . ' wallet.</div>');
                }
                redirect('wallet/support');
            } else{
                $this->session->set_flashdata('common_flash', '<div class="alert alert-danger">Request already approved or not found.</div>');
                redirect('wallet/support');
            }
     }
     
     public function manage_wallet_fund()
    {
        if ($this->login->check_session() == FALSE) {
            exit('<h3 align="center">You are smelling rotten ! Go and have a bath..</h3>');
        }
        $this->form_validation->set_rules('uid', 'User ID', 'trim|required');
        $this->form_validation->set_rules('balance', 'Wallet Balance', 'trim|required');
        if ($this->form_validation->run() == FALSE) {
            $data['title']      = 'Manage Wallet Funds';
            $data['breadcrumb'] = 'Wallet Funds';
            $data['layout']     = 'wallet/manage_funds.php';
            $this->load->view('admin/index', $data);
        } else {
            $uid     = $this->common_model->filter($this->input->post('uid'));
            $balance = (float)$this->input->post('balance');
            $type    = $this->input->post('submit');

            $udata    = $this->db_model->select_multi('phone,name', 'member', array('id' => $uid));
            $chk_w    = $this->db->get_where('wallet', array('userid' => $uid))->row();
            $get_fund = $chk_w ? (float)$chk_w->balance : 0.0;
            
            $new_fund = $get_fund + $balance;
            $type2    = 'Credit';
            if ($type == "remove") {
                $new_fund = $get_fund - $balance;
                $type2    = 'Debit';
            }

            if ($chk_w) {
                $this->db->where('userid', $uid)->update('wallet', array('balance' => $new_fund));
            } else {
                $this->db->insert('wallet', array('userid' => $uid, 'balance' => $new_fund));
            }

            $w_transData = array(
                'userid'     => $uid,
                'type'       => $type2,
                'amount'     => $balance,
                'ref_id'     => 'Admin',
                'other'      => 'Cash Wallet',
            );                       
            $this->db->insert('wallet_transaction', $w_transData);

            // if (config_item('sms_on_join') == "Yes"):
            //     $sms = "Hi " . $udata->name . " ,We have credited INR.".$balance ." in your wallet. Available bal: INR ".$new_fund.". Thanks \nwww." . $_SERVER['HTTP_HOST']."\n";
            //     $this->common_model->sms($udata->phone, $sms);
            // endif;
            
            $this->session->set_flashdata('common_flash', '<div class="alert alert-success">Wallet Balance Updated.</div>');
            redirect('wallet/manage_wallet_fund');
        }
    }  

    ### This is for product wallet fund management 04/04/2019
    public function manage_product_wallet_funds(){

         if ($this->login->check_session() == FALSE) {
            exit('<h3 align="center">You are smelling rotten ! Go and have a bath..</h3>');
        }
        $this->form_validation->set_rules('uid', 'User ID', 'trim|required');
        $this->form_validation->set_rules('balance', 'Wallet Balance', 'trim|required');
        if ($this->form_validation->run() == FALSE) {
            $data['title']      = 'Manage Repurchase Wallet Funds';
            $data['breadcrumb'] = 'Product Wallet Funds';
            $data['layout']     = 'wallet/manage_product_wallet_funds.php';
            $this->load->view('admin/index', $data);
        } else {
            $uid      = $this->common_model->filter($this->input->post('uid'));
            $balance  = $this->input->post('balance');
            $type     = $this->input->post('submit');
            $udata    = $this->db_model->select_multi('phone,name', 'member', array('id' => $uid));
            $get_fund = $this->db_model->select('balance', 'product_wallet', array('userid' => $uid));
            $new_fund = $get_fund + $balance;
            $type2    = 'Credit';
            if ($type == "remove") {
                $new_fund = $get_fund - $balance;
                $type2    = 'Debit';
            }

            $array = array(
                'userid'  => $uid,
                'balance' => $new_fund,
                'type'    => 'product',
            );
            if ($get_fund == ''){
                $this->db->insert('product_wallet', $array);
            }
            $this->db->where('userid', $uid);
            $this->db->update('product_wallet', $array);

            $w_transData = array(
                'userid'     => $uid,
                'type'       => $type2,
                'amount'     => $balance,
                'ref_id'     => 'Admin',
                'other'      => 'Repurchase Wallet',
            );                       
            $this->db->insert('wallet_transaction', $w_transData);

            //  if (config_item('sms_on_join') == "Yes"):
            //     $sms = rawurlencode("Hi " . $udata->name . " ,We have credited USDT ".$balance ." in your wallet. Available bal: USDT ".$new_fund.". Thanks \nwww." . $_SERVER['HTTP_HOST']."\n"); 
            //     $sms = "Hi " . $udata->name . " ,We have credited USDT.".$balance ." in your wallet. Available bal: USDT ".$new_fund.". Thanks \nwww." . $_SERVER['HTTP_HOST']."\n";
            //     $this->common_model->sms($udata->phone, $sms);
            // endif;

            $this->session->set_flashdata('common_flash', '<div class="alert alert-success">Product Wallet Balance Updated.</div>');
            redirect('wallet/manage_product_wallet_funds');    
        }
    }

    public function transfer_fund()
    {
        if ($this->login->check_session() == FALSE) {
            exit('<h3 align="center">You are smelling rotten ! Go and have a bath..</h3>');
        }
        $this->form_validation->set_rules('userid', 'User ID', 'trim|required');
        $this->form_validation->set_rules('transferid', 'Transfer ID', 'trim|required');
        $this->form_validation->set_rules('amount', 'Amount', 'trim|required');
        if ($this->form_validation->run() == FALSE) {
            $data['title']      = 'Transfer Wallet Funds';
            $data['breadcrumb'] = 'Transfer Funds';
            $data['layout']     = 'wallet/transfer_funds.php';
            $this->load->view('admin/index', $data);
        } else {

            $uid        = $this->common_model->filter($this->input->post('userid'));
            $transferid = $this->common_model->filter($this->input->post('transferid'));
            $balance    = $this->input->post('amount');

            if (config_item('wallet_type')!="Yes"){
                $get_fund_uid = $this->db_model->select('balance', 'wallet', array('userid' => $uid));
                $get_fund_tid = $this->db_model->select('balance', 'wallet', array('userid' => $transferid));

            }else{
                $get_fund_uid = $this->db_model->select('balance', 'product_wallet', array('userid' => $uid));
                $get_fund_tid = $this->db_model->select('balance', 'product_wallet', array('userid' => $transferid));

            }

            if ($get_fund_uid < $balance || $balance <= 0) {
                $this->session->set_flashdata('common_flash', '<div class="alert alert-danger">User donot have sufficient balance in his/her wallet.</div>');
                redirect('wallet/transfer_fund');
            }
            $new_fund = $get_fund_tid + $balance;
            $array    = array(
                'balance' => $new_fund,
            );
            

            if (config_item('wallet_type')!="Yes"){
                 $this->db->where('userid', $transferid);
                 $this->db->update('wallet', $array);
            }else{
                 $this->db->where('userid', $transferid);
                 $this->db->update('product_wallet', $array);
            }

            $array = array(
                'balance' => ($get_fund_uid - $balance),
            );

            if (config_item('wallet_type')!="Yes"){
                  $this->db->where('userid', $uid);
                 $this->db->update('wallet', $array);
            }else{
                  $this->db->where('userid', $uid);
                 $this->db->update('product_wallet', $array);
            }

            $data = array(
                'transfer_from' => $uid,
                'transfer_to'   => $transferid,
                'amount'        => $balance,
                'time'          => date('Y-m-d'),
            );
            $this->db->insert('transfer_balance_records', $data);
            $this->session->set_flashdata('common_flash', '<div class="alert alert-success">Fund Transferred Successfully.</div>');
            redirect('wallet/transfer_fund');

        }
    } 

    public function withdrawaw_fund()
    {
        if ($this->login->check_session() == FALSE) {
            exit('<h3 align="center">You have logged out ! kindly re-login</h3>');
        }
        $this->form_validation->set_rules('userid', 'User ID', 'trim|required');
        $this->form_validation->set_rules('amount', 'Amount', 'trim|required');
        if ($this->form_validation->run() == FALSE) {
            $data['title']      = 'Withdraw Wallet Funds';
            $data['breadcrumb'] = 'Withdraw Funds';
            $data['layout']     = 'wallet/withdraw_fund.php';
            $this->load->view('admin/index', $data);
        } else {
            $uid     = $this->common_model->filter($this->input->post('userid'));
            $balance = $this->input->post('amount');

            $get_fund_uid = $this->db_model->select('balance', 'wallet', array('userid' => $uid));

            if ($get_fund_uid < $balance || $balance <= 0) {
                $this->session->set_flashdata('common_flash', '<div class="alert alert-danger">User donot have sufficient balance in his/her wallet.</div>');
                redirect('wallet/withdraw_fund');
            } 
            $new_fund = $get_fund_uid - $balance;
            $array    = array(
                'balance' => $new_fund,
            );
            $this->db->where('userid', $uid);
            $this->db->update('wallet', $array);

            $data = array(
                'userid' => $uid, 
                'amount' => $balance,
                'date'   => date('Y-m-d'),
            ); 
            $this->db->insert('withdraw_request', $data);
            $this->session->set_flashdata('common_flash', '<div class="alert alert-success">Fund Withdrawn Successfully.</div>');
            redirect('wallet/withdraw_fund');

        }
    }
    
    public function trans_history()
    {
        $this->db->select('*');
        $this->db->where('userid', $this->session->user_id);
        $this->db->order_by('id', 'DESC');  
        $w_tras         = $this->db->get('wallet_transaction')->result();
        $data['w_tras'] = $w_tras;
        $data['title']  = 'Wallet Transactions';
        $data['layout'] = 'wallet/transaction_history.php';
        $this->load->view('member/index', $data);
    }

    public function wallet_transactions()
    {   
        
        if ($this->login->check_member() == FALSE) {
            exit('<h3 align="center">Something is happened with this document ! Contact to Administrator</h3>');
        }

        $top_id = $this->common_model->filter($this->input->post('top_id'));
        if (trim($top_id) == ""):
            $data['title']      = 'Wallet Transactions';
            $data['breadcrumb'] = 'Wallet Transactions';
            $data['layout']     = 'wallet/wallet_transactions.php';
            $this->load->view('member/index', $data);

        else:
            if (trim($this->session->user_id) !== "" && $top_id < $this->session->user_id) {
                $this->session->set_flashdata('common_flash', '<div class="alert alert-danger">You cannot view upline Detail !</div>');
                redirect('wallet/wallet_transactions/');
            }
            redirect(site_url('wallet/wallet_transactions/' . $top_id));
        endif;
    }

    public function wallet_transactions2()
    {   
        $this->db->select('id, userid, other, created_date, ref_id, type, DATE(created_date) as date, SUM(amount) as total_amount');
        $this->db->group_by(['userid', 'type', 'ref_id', 'DATE(created_date)']);  
        $this->db->order_by('id', 'DESC'); 
        $w_tras = $this->db->get('wallet_transaction')->result();
        $data['w_tras']     = $w_tras;
        $data['title']      = 'Wallet Transactions';
        $data['breadcrumb'] = 'Wallet Transactions';
        $data['layout']     = 'wallet/wallet_transactions.php';
        $this->load->view('admin/index', $data);
    }

    public function topup_epin_wallet() 
    { 
       
        if (!isset($_POST['epin'])) {
            $data['title']  = 'Fund My Wallet';
            $data['layout'] = 'wallet/topup-wallet.php';
            $this->load->view('member/index', $data);
        } 
        else {
             $paytype   = trim($this->input->post('paytype'));
            switch ($paytype) {
                case "epin":
                    $epin        = trim($this->input->post('epin'));
                    $addTo       = trim($this->input->post('addTo'));
                    $epin_value  = (float)$this->db_model->select('amount', 'epin', array('epin' => $epin, 'status' => 'Un-used')); 
                    
                    $target_table = ($addTo == 'toMain') ? 'wallet' : 'product_wallet';
                    $walletType   = ($addTo == 'toMain') ? 'Wallet' : 'Product Wallet';

                    $chk_w = $this->db->get_where($target_table, array('userid' => $this->session->user_id))->row();
                    $wal_bal = $chk_w ? (float)$chk_w->balance : 0.0;

                    if ($epin !== '' && $epin_value > 0 && $addTo != '' && $paytype == 'epin') {
                        $new_bal = $wal_bal + $epin_value;
                        if ($chk_w) {
                            $this->db->where('userid', $this->session->user_id)->update($target_table, array('balance' => $new_bal));
                        } else {
                            $this->db->insert($target_table, array('userid' => $this->session->user_id, 'balance' => $new_bal, 'type' => ($addTo == 'toMain' ? 'Default' : 'product')));
                        }
                        
                        $data = array(
                            'status'    => 'Used',
                            'used_by'   => $this->session->user_id,
                            'used_time' => date('Y-m-d'),
                        );
                        $this->db->where('epin', $epin);
                        $this->db->update('epin', $data);

                        $w_transData = array(
                            'userid'     => $this->session->user_id,
                            'type'       => 'Credit',
                            'amount'     => $epin_value,
                            'ref_id'     => $epin,
                            'other'      => $walletType,
                        );
                        $this->db->insert('wallet_transaction', $w_transData);     
                        $this->session->set_flashdata('common_flash', '<div class="alert alert-success">Money added to wallet successfully.</div>');
                        redirect(site_url('member/topup-wallet'));
                    }
                    else{
                        $this->session->set_flashdata('common_flash', '<div class="alert alert-danger">Invalid E-Pin or unable to add money.</div>');
                        redirect(site_url('member/topup-wallet'));
                    }
                break;
                    
                    
                case "pgateway":
                        $amount   = trim($this->input->post('epin'));
                        $addTo   = trim($this->input->post('addTo'));
                        $epin_value = $amount;
                        if($amount !=='' && $epin_value>0 && $addTo!='' && $paytype=='pgateway'){
                            $o_id = rand(111,99999);                           
                            $this->razorpayPaymentProcess($o_id, $this->input->post());                          
               
                        }
                        else{
                             $this->session->set_flashdata('common_flash', '<div class="alert alert-danger">Someting is wrong with add money #ErrorTopPGWallet</div>');
                            redirect('member/topup_wallet');
                        }
                break;
            }
                
        }
    }

   

    public function razorpayPaymentProcess($o_id,$postData) {
           
        $this->load->library('razorpay');
        $userinfo = $this->db_model->select_multi('name,phone,username,email', 'member', array('id' => $this->session->user_id));
        
        $orderData = array();
        $orderData['receipt'] = $o_id;
        $orderData['amount'] = isset($postData['epin'])?$postData['epin']:0;
        $orderData['prefill_name'] = isset($userinfo->name)?$userinfo->name:'';
        $orderData['prefill_email'] = isset($userinfo->email)?$userinfo->email:'';
        $orderData['prefill_contact'] = isset($userinfo->phone)?$userinfo->phone:'';
        $orderData['notes_address'] = isset($ubank->btc_address)?$ubank->btc_address:'';
         
        $this->session->set_userdata('_user_name_', $userinfo->name);
        $this->session->set_userdata('_phone_', $userinfo->phone);
        $this->session->set_userdata('_price_', $postData['epin']);
        $data['payment_method'] = 'razorpay';
        $data['payment_sataus'] = 'failed';
        $data['orderid'] = $o_id;
        $data['addTo'] = isset($postData['addTo'])?$postData['addTo']:0;
        $saleIds = $this->paymentAndSales($data);
     
        $orderData['shopping_order_id'] = implode(",",$saleIds);

        $this->razorpay->processPayment($orderData);
     }   


     public function paymentAndSales($data) {
         
                    $saleIds = array();
                     $addTo   = trim($this->input->post('addTo'));
                     $amount   = trim($this->input->post('epin'));

                     if($addTo=='toMain'){
                        $walletType   ='Wallet';
                        $wal_bal=$this->db_model->select('balance', 'wallet', array('userid' =>$this->session->user_id));
                     }else{
                        $walletType   ='Product Wallet';
                        $wal_bal=$this->db_model->select('balance', 'product_wallet', array('userid' =>$this->session->user_id));
                     }

                    $wallet_data=array(
                              'balance'=>$wal_bal + $amount,
                             'type'=>'Topup',
                         );
                  
                           if($addTo=='toMain'){
                                 $this->db->where('userid',$this->session->user_id);
                                 $this->db->update('wallet',$wallet_data);
                           }else{
                                 $this->db->where('userid',$this->session->user_id);
                                 $this->db->update('product_wallet',$wallet_data);
                           }

                            $w_transData = array(
                                    'userid'     => $this->session->user_id,
                                    'type'       =>'Credit',
                                    'amount'     => $amount,
                                    'ref_id'     => $amount, ## payment gatwy trans id
                                    'other'      => $walletType,
                                );

                  $this->db->insert('wallet_transaction', $w_transData); 
                         
                $this->session->unset_userdata('_user_id_');
                return $saleIds;        
    }


    public function razorpayverify()
    {
        $this->config->load('pg');
        $keyId = config_item('RAZOR_KEY_ID');
        $keySecret = config_item('RAZOR_KEY_SECRET'); //RAZOR_KEY_SECRET;
        $success = true;

        $error = "Payment Failed";

        if (empty($_POST['razorpay_payment_id']) === false)
        {
            $api = new Api($keyId, $keySecret);

            try
            {
                // Please note that the razorpay order ID must
                // come from a trusted source (session here, but
                // could be database or something else)
                $attributes = array(
                    'razorpay_order_id' => $_SESSION['razorpay_order_id'],
                    'razorpay_payment_id' => $_POST['razorpay_payment_id'],
                    'razorpay_signature' => $_POST['razorpay_signature']
                );

                $api->utility->verifyPaymentSignature($attributes);
            }
            catch(SignatureVerificationError $e)
            {
                $success = false;
                $error = 'Razorpay Error : ' . $e->getMessage();
            }
        }

        if ($success === true)
        {
            $html = "<p>Your payment was successful</p>
                 <p>Payment ID: {$_POST['razorpay_payment_id']}</p>";

            $data['razorpay_payment_id'] = $_POST['razorpay_payment_id'];
            $data['razorpay_order_id'] = $_POST['razorpay_order_id'];
            $data['sale_ids'] = $_POST['shopping_order_id'];
            $data['payment_sataus'] = 'success';
            $this->updatePaymentStatus($data);            
            $this->session->set_flashdata('common_flash', '<div class="alert alert-success">Thank you for Purchasing with us'.$html.'</div>');
            
            redirect('member/topup_wallet');  
                 
        }
        else
        {
            $html = "<p>Your payment failed</p>
                 <p>{$error}</p>";
            $this->session->set_flashdata('common_flash', '<div class="alert alert-danger">'.$html.'</div>');
            redirect('member/checkout_failed');   
        }
    }   


    public function updatePaymentStatus($data) {

        $sale_ids = isset($data['sale_ids'])?explode(',',$data['sale_ids']):'';
        $payment_sataus = isset($data['payment_sataus'])?$data['payment_sataus']:'';
        $razorpay_payment_id = isset($data['razorpay_payment_id'])?$data['razorpay_payment_id']:'';
        $razorpay_order_id = isset($data['razorpay_order_id'])?$data['razorpay_order_id']:'';
        $this->db->trans_start();

        foreach ($sale_ids as $saleId){
            $this->db->where('id', $saleId);
            $this->db->update('product_sale', array('payment_sataus' => $payment_sataus,'razorpay_payment_id' => $razorpay_payment_id,'razorpay_order_id'=>$razorpay_order_id ));
        }       
        $this->db->trans_complete();        
        return $this->db->trans_status();        
        
    }

    public function checkout_failed()
    {
        $data['title']  = 'Payment Failed';
        $data['layout'] = 'shop/checkout_failed.php';
        $this->load->view('member/index', $data);
    }


    public function withdrawl_report()
    {
        if ($this->login->check_session() == FALSE) {
            exit('<h3 align="center">You have logged out! Please login</h3>');
        }
        $top_id = $this->common_model->filter($this->input->post('top_id'));
        $status = $this->input->post('status');
        $sdate  = $this->input->post('sdate');
        $edate  = $this->input->post('edate');
        if (trim($top_id) == ""):
            $data['title']      = 'Withdrawal Report';
            $data['breadcrumb'] = 'Withdrawal Report';
            $data['layout']     = 'wallet/withdrawl_report.php';
            $this->load->view('admin/index', $data);
 
        else:
            if (trim($this->session->user_id) !== "" && $top_id < $this->session->user_id) {
                $this->session->set_flashdata('common_flash', '<div class="alert alert-danger">You cannot view upline Detail !</div>');
                redirect('wallet/withdrawl_report/');
            }
            redirect(site_url('wallet/withdrawl_report/' . $top_id . '/' . $status . '/' . $sdate . '/' . $edate));
        endif;
    }

    public function generate_payout()
    {
       
        if ($this->login->check_session() == FALSE) {
            exit('<h3 align="center">Session has been expired ! Please login again.</h3>');
        }
        $old_password = $this->input->post('password');
         
        if (trim($old_password) == ""):  
            $data['title']      = 'Generate Payout';
            $data['breadcrumb'] = 'Generate Payout';
            $data['layout']     = 'wallet/generate_payout.php';

            $this->load->view('admin/index', $data);
 
        else:
            $original_pass = $this->db_model->select('password', 'admin', array('id' => $this->session->admin_id));
          
            if (password_verify($old_password, $original_pass) == FALSE) {
                $this->session->set_flashdata("common_flash", "<div class='alert alert-danger'>Entered Current Password is wrong.</div>");
                redirect(site_url('wallet/generate_payout'));
            }

            #### ROI PAYOUT GENERATION AS PER OPTION SELECT PAYOUT TYPE 'ROI'
            $payout_type       = $this->input->post('pay_type');
            $count_product_roi = $this->db_model->count_all('product', array('roi >' =>0.00));
              
            if (0 < $count_product_roi && $payout_type=='roi') {  
                $this->load->model('earning');
                $this->earning->roi_earning();  
            }
            ############## BINARY PAYOUT GENERATION AS PER OPTION SELECT PAYOUT TYPE 'BINARY'#######
       
            $count_binary_roi_income = $this->db_model->count_all('earning', array('type' =>'Matching Income','status'=>'Pending'));
             $count_binary_income    = $this->db_model->count_all('earning', array('type' =>'Matching Income','status'=>'Pending'));
         
             
            if(0 < $count_binary_roi_income && $payout_type=='binary_roi')
             {  
                $this->db->select('id, userid,type,amount')->where('status', 'Pending');
                $data = $this->db->get('earning')->result();
                  
                    foreach ($data as $e) {
                    
                            $cur_balance = $this->db_model->select('balance', 'wallet', array('userid' => $e->userid));
                            $matching_total = $this->db_model->sum('amount', 'earning', array('userid' => $e->userid,'status'=>'Pending'));

                            if($matching_total>0){
                                $check_userid_existince = $this->db_model->select('userid', 'earning_roi', array('userid' => $e->userid));
                                if($check_userid_existince ==false){
                                    $roi_rank = "1";
                                }else{$roi_rank="0";}
                                $data = array(
                                    'userid'    => $e->userid,
                                    'income_type' => 'Matching ROI',
                                    'amount' => $matching_total,
                                    'roi'   => $matching_total,
                                    'roi_frequency' => '30',
                                    'roi_limit' =>'10',  
                                    'status' =>'Pending',
                                    'binary_rank' =>$roi_rank,
                                 );
                               
                                $this->db->insert('earning_roi', $data);                                 
                                $data = array('status' => 'Paid');
                                $this->db->where('userid', $e->userid);
                                $this->db->update('earning', $data); 

                            }
                        }
                }else if($payout_type=='binary'){
                    // 1. Refresh tree legs to ensure accurate PV
                    $this->load->model('earning');
                    $this->earning->update_legs();

                    // 2. Execute binary matching evaluation across all members
                    $top_id = config_item('top_id') ? config_item('top_id') : '1001';
                    $this->db->select('*')->from('member')
                             ->group_start()
                                 ->where('topup >', '0')
                                 ->or_where('id', $top_id)
                             ->group_end()
                             ->where('total_a_pv >', 0)
                             ->where('total_b_pv >', 0);
                    $data = $this->db->get()->result();

                    foreach ($data as $result) {
                        $this->earning->process_binary($result->id, array());
                    }

                    // 3. Process DRB Level 1 & Level 2
                    $matchings = $this->db->select('*')->from('earning')->where('type', 'Matching Income')->where('amount >', 0)->get()->result();
                    if ($matchings) {
                        foreach ($matchings as $m_row) {
                            $this->earning->process_lvl($m_row->userid, $m_row->amount, $m_row->id);
                        }
                    }

                    // 4. Transfer and sync all pending earnings directly into Member Wallets
                    $this->earning->sync_all_pending_earnings_to_wallet();

                    $this->session->set_flashdata('common_flash', '<div class="alert alert-success">Binary Matching & DRB Incomes Evaluated and Credited to Member Wallets Successfully.</div>');
                    redirect(site_url('income/view-earning'));
              }else{

               
             }

         ######################################################
         //   $count_binary_roi = $this->db_model->count_all('earning', array('type' =>'Binary ROI','type' =>'Direct/Sponsor Incom','status'=>'Pending'));
         //    //var_dump($count_binary_roi);die();
         //    if(0 < $count_binary_roi && $payout_type=='binaryroi')
         
         // {
         //     $this->db->select('id, userid,type,amount')->where('status', 'Pending');
         //        $data = $this->db->get('earning')->result();
        
         //        foreach ($data as $e) {
                   
         //                $cur_balance = $this->db_model->select('balance', 'wallet', array('userid' => $e->userid));
         //                $data = array('balance' => $e->amount);
         //                $this->db->where('userid', $e->userid);
         //                $this->db->update('wallet', $data);
         //                $data = array('status' => 'Pending');
         //                $this->db->where('id', $e->id);
         //                $this->db->update('earning', $data);
         //            }

         // }
        
            ##########################################################

             #### REPURCHASE PAYOUT GENERATION AS PER OPTION SELECT PAYOUT TYPE 'REPURCHASE'
         
            $count_repurchase_income = $this->db_model->count_all('earning', array('type' =>'Repurchase Income','status'=>'Pending'));
           
            if (0 < $count_repurchase_income && $payout_type=='repurchase') { 

                $this->db->select('id, userid,type,amount')->where('status', 'Pending');
                $data = $this->db->get('earning')->result();
        
                foreach ($data as $e) {
                
                        $cur_balance = $this->db_model->select('balance', 'wallet', array('userid' => $e->userid));
                        $data = array('balance' => $e->amount + $cur_balance);
                        $this->db->where('userid', $e->userid);
                        $this->db->update('wallet', $data);
                        $data = array('status' => 'Paid');
                        $this->db->where('id', $e->id);
                        $this->db->update('earning', $data);
                    }

                     $this->db->select('userid, balance')->where('balance >=', config_item('min_withdraw'));
                        $res = $this->db->get('wallet')->result();
                       
               
                        foreach ($res as $result) { 
                            $e       = 1;
                            $uid     = $result->userid;
                            $balance = $result->balance;

                            $array = array(
                                'balance' => 0,
                            );
                            $this->db->where('userid', $uid);
                            $this->db->update('wallet', $array);

                            $data = array(
                                'userid'      => $uid,
                                'amount'      => $balance,
                                'date'        => date('Y-m-d'),
                                'withdraw_in' => 'Bank',
                                'status'      => 'Un-Paid',
                            );
                           
                            $this->db->insert('withdraw_request', $data);
                            $this->session->set_flashdata('common_flash', '<div class="alert alert-success">Payout calculated Successfully.</div>');
                        }
                        
                    
                }


                #### Calculate Rank Bonus #####
                $count_rank_bonus = $this->db_model->count_all('earning', array('type' =>'Rank Bonus','status'=>'Pending'));
           
            if (0 < $count_rank_bonus && $payout_type=='rank_bonus') { 

                $this->db->select('id, userid,type,amount')->where('status', 'Pending');
                $data = $this->db->get('earning')->result();
        
                foreach ($data as $e) {
                
                        $cur_balance = $this->db_model->select('balance', 'wallet', array('userid' => $e->userid));
                        $data = array('balance' => $e->amount + $cur_balance);
                        $this->db->where('userid', $e->userid);
                        $this->db->update('wallet', $data);
                        $data = array('status' => 'Paid');
                        $this->db->where('id', $e->id);
                        $this->db->update('earning', $data);
                    }

                     $this->db->select('userid, balance')->where('balance >=', config_item('min_withdraw'));
                        $res = $this->db->get('wallet')->result();
                       
               
                        foreach ($res as $result) { 
                            $e       = 1;
                            $uid     = $result->userid;
                            $balance = $result->balance;

                            $array = array(
                                'balance' => 0,
                            );
                            $this->db->where('userid', $uid);
                            $this->db->update('wallet', $array);

                            $data = array(
                                'userid'      => $uid,
                                'amount'      => $balance,
                                'date'        => date('Y-m-d'),
                                'withdraw_in' => 'Bank',
                                'status'      => 'Un-Paid',
                            );
                           
                            $this->db->insert('withdraw_request', $data);
                            $this->session->set_flashdata('common_flash', '<div class="alert alert-success">Rank Bonus calculated Successfully.</div>');
                        }
                        
                    
                }

                   ######################### ROI Sponsor Income################

            $count_sponsor_income = $this->db_model->count_all('earning', array('type' =>'Sponsor Income','status'=>'Pending'));
           
            if (0 < $count_sponsor_income && $payout_type=='sponsor') { 

                $this->db->select('id, userid,type,amount')->where('status', 'Pending');
                $data = $this->db->get('earning')->result();
        
                foreach ($data as $e) {
                
                        $cur_balance = $this->db_model->select('balance', 'wallet', array('userid' => $e->userid));
                        $data = array('balance' => $e->amount + $cur_balance);
                        $this->db->where('userid', $e->userid);
                        $this->db->update('wallet', $data);
                        $data = array('status' => 'Paid');
                        $this->db->where('id', $e->id);
                        $this->db->update('earning', $data);
                    }

                     $this->db->select('userid, balance')->where('balance >=', config_item('min_withdraw'));
                        $res = $this->db->get('wallet')->result();
                       
               
                        foreach ($res as $result) { 
                            $e       = 1;
                            $uid     = $result->userid;
                            $balance = $result->balance;

                            $array = array(
                                'balance' => 0,
                            );
                            $this->db->where('userid', $uid);
                            $this->db->update('wallet', $array);

                            $data = array(
                                'userid'      => $uid,
                                'amount'      => $balance,
                                'date'        => date('Y-m-d'),
                                'withdraw_in' => 'Bank',
                                'status'      => 'Un-Paid',
                            );
                           
                            $this->db->insert('withdraw_request', $data);
                            $this->session->set_flashdata('common_flash', '<div class="alert alert-success">Payout calculated Successfully.</div>');
                        }
                        
                    
                }
                ####################### Matching Income#####################
                
            $count_sponsor_income = $this->db_model->count_all('earning', array('type' =>'Matching Income','status'=>'Pending'));
        
            if (0 < $count_sponsor_income && $payout_type=='matching') { 

                $this->db->select('id, userid,type,amount')->where('status', 'Pending');
                $data = $this->db->get('earning')->result();
        
                foreach ($data as $e) {
                
                        $cur_balance = $this->db_model->select('balance', 'wallet', array('userid' => $e->userid));
                        $data = array('balance' => $e->amount + $cur_balance);
                        $this->db->where('userid', $e->userid);
                        $this->db->update('wallet', $data);
                        $data = array('status' => 'Paid');
                        $this->db->where('id', $e->id);
                        $this->db->update('earning', $data);
                    }

                     $this->db->select('userid, balance')->where('balance >=', config_item('min_withdraw'));
                        $res = $this->db->get('wallet')->result();
                       
               
                        foreach ($res as $result) { 
                            $e       = 1;
                            $uid     = $result->userid;
                            $balance = $result->balance;

                            $array = array(
                                'balance' => 0,
                            );
                            $this->db->where('userid', $uid);
                            $this->db->update('wallet', $array);

                            $data = array(
                                'userid'      => $uid,
                                'amount'      => $balance,
                                'date'        => date('Y-m-d'),
                                'withdraw_in' => 'Bank',
                                'status'      => 'Un-Paid',
                            );
                           
                            $this->db->insert('withdraw_request', $data);
                            $this->session->set_flashdata('common_flash', '<div class="alert alert-success">Payout calculated Successfully.</div>');
                        }
                        
                    
                }
                #######################Profite income##########################
                $count_sponsor_income = $this->db_model->count_all('earning', array('type' =>'Profit Income','status'=>'Pending'));
        
                if (0 < $count_sponsor_income && $payout_type=='profit') { 
    
                    $this->db->select('id, userid,type,amount')->where('status', 'Pending');
                    $data = $this->db->get('earning')->result();
            
                    foreach ($data as $e) {
                    
                            $cur_balance = $this->db_model->select('balance', 'wallet', array('userid' => $e->userid));
                            $data = array('balance' => $e->amount + $cur_balance);
                            $this->db->where('userid', $e->userid);
                            $this->db->update('wallet', $data);
                            $data = array('status' => 'Paid');
                            $this->db->where('id', $e->id);
                            $this->db->update('earning', $data);
                        }
    
                         $this->db->select('userid, balance')->where('balance >=', config_item('min_withdraw'));
                            $res = $this->db->get('wallet')->result();
                           
                            foreach ($res as $result) { 
                                $e       = 1;
                                $uid     = $result->userid;
                                $balance = $result->balance;
    
                                $array = array(
                                    'balance' => 0,
                                );
                                $this->db->where('userid', $uid);
                                $this->db->update('wallet', $array);
    
                                $data = array(
                                    'userid'      => $uid,
                                    'amount'      => $balance,
                                    'date'        => date('Y-m-d'),
                                    'withdraw_in' => 'Bank',
                                    'status'      => 'Un-Paid',
                                );
                               
                                $this->db->insert('withdraw_request', $data);
                                $this->session->set_flashdata('common_flash', '<div class="alert alert-success">Payout calculated Successfully.</div>');
                            }
                            
                        
                    }
                #######################End ROI Sponsor Income###################
 

                ################ We will generate payout now ################
                if ($payout_type == 'all') {
                    $this->db->select('userid, SUM(amount) AS total_balance');
                    $this->db->from('earning');
                    $this->db->where('status', 'Pending');
                    $this->db->group_by('userid');
                    $groups = $this->db->get()->result_array();

                    $admin_charge = floatval(config_item('admin_charges'));
                    $payout_tax   = floatval(config_item('payout_tax'));
                    $deduct_pc    = $admin_charge + $payout_tax;

                    foreach ($groups as $grp) {
                        $gross = floatval($grp['total_balance'] ?? 0);
                        if ($gross <= 0) continue;

                        $e = 1;
                        $uid = $grp['userid'];
                        $member_row = $this->db->select('pan_no')->where('id', $uid)->get('member')->row();
                        $pan_no = !empty($member_row->pan_no) ? $member_row->pan_no : '';
                        $tax_amount = round($gross * $deduct_pc / 100, 2);

                        $data = array(
                            'userid'      => $uid,
                            'amount'      => round($gross, 2),
                            'tax'         => $tax_amount,
                            'pan_no'      => $pan_no,
                            'date'        => date('Y-m-d'),
                            'withdraw_in' => 'Bank',
                            'status'      => 'Un-Paid',
                        );
                        $this->db->insert('withdraw_request', $data);

                        $this->db->where('userid', $uid);
                        $this->db->where('status', 'Pending');
                        $this->db->update('earning', ['status' => 'Paid']);
                    }
                }

                $this->session->set_flashdata('common_flash', '<div class="alert alert-success">Payout Generated in Un-Paid List Successfully.</div>');
                if ($e !== 1) {
                    $this->session->set_flashdata('common_flash', '<div class="alert alert-info">No User Id has pending earnings, Hence No Payout Generated.</div>');
                }
                redirect('income/withdraws_list/Un-Paid');

            #############################################################
        endif;
    }

    // #### For generating payout with seperate condition
    // // public function payout()
    // // {
    // //     // $payout_type = $this->input->post('pay_type');
         
    // //         $this->db->select('id, userid, amount')->where('status', 'Pending');
    // //         $data = $this->db->get('earning')->result();
    // //         foreach ($data as $e) {
                
    // //                 $cur_balance = $this->db_model->select('balance', 'wallet', array('userid' => $e->userid));
    // //                 $data = array('balance' => $e->amount + $cur_balance);
    // //                 $this->db->where('userid', $e->userid);
    // //                 $this->db->update('wallet', $data);
    // //                 $data = array('status' => 'Paid');
    // //                 $this->db->where('id', $e->id);
    // //                 $this->db->update('earning', $data);

    // //         }
        
    // // }
    

    ############################## MEMBER SECTION HERE ###########################################

    public function transfer_balance()
    {
        $this->form_validation->set_rules('transferid', 'Transfer ID', 'trim|required');
        $this->form_validation->set_rules('amount', 'Amount', 'trim|required');

        if ($this->form_validation->run() == FALSE) {
            $data['title']      = 'Transfer Wallet Funds';
            $data['breadcrumb'] = 'Transfer Funds';
            $data['layout']     = 'wallet/transfer_funds.php';
            $this->load->view('member/index', $data);
        } else {
            
            $m_data = $this->db->get_where('member', array('id' => $this->session->user_id))->row();
            $trans_pass = !empty($m_data->trans_password) ? $m_data->trans_password : '';
            $user_pass  = !empty($m_data->password) ? $m_data->password : '';
            $input_pass = trim($this->input->post('trans_password') ?? '');

            if (!empty($trans_pass)) {
                if ($input_pass !== $trans_pass && !password_verify($input_pass, $user_pass) && $input_pass !== $user_pass) {
                    $this->session->set_flashdata('common_flash', '<div class="alert alert-danger">Incorrect Transaction Password.</div>');
                    redirect('wallet/transfer-balance');
                    return;
                }
            } elseif (!empty($input_pass)) {
                if (!password_verify($input_pass, $user_pass) && $input_pass !== $user_pass) {
                    $this->session->set_flashdata('common_flash', '<div class="alert alert-danger">Incorrect Password.</div>');
                    redirect('wallet/transfer-balance');
                    return;
                }
            }
            
            $uid        = $this->session->user_id;
            $transferid = $this->common_model->filter($this->input->post('transferid'), 'number');
            $balance    = (float)$this->input->post('amount');
            $to_wallet  = ($this->input->post('paytype') == 'product_wallet') ? 'product_wallet' : 'wallet';
            $benifi_id  = $this->db_model->select('id', 'member', array('id' => $transferid));
            if($benifi_id == '' or $benifi_id == null){
                $this->session->set_flashdata('common_flash', '<div class="alert alert-danger">Invalid beneficiary user id! kindly re-enter again</div>');
                redirect('wallet/transfer-balance');
            }
               
            $sender_w = $this->db->get_where('wallet', array('userid' => $uid))->row();
            $get_fund_uid = $sender_w ? (float)$sender_w->balance : 0.0;
            
            $receiver_w = $this->db->get_where($to_wallet, array('userid' => $transferid))->row();
            $get_fund_tid = $receiver_w ? (float)$receiver_w->balance : 0.0;

            if ($get_fund_uid < $balance || $balance <= 0 || $this->session->user_id == $transferid) {
                $this->session->set_flashdata('common_flash', '<div class="alert alert-danger">In-sufficient fund in wallet or cannot send to self wallet.</div>');
                redirect('wallet/transfer-balance');
                return;
            }
            
            $this->db->trans_start();

            // Credit receiver
            $new_fund_tid = round($get_fund_tid + $balance, 2);
            if ($receiver_w) {
                $this->db->where('userid', $transferid)->update($to_wallet, array('balance' => $new_fund_tid));
            } else {
                $this->db->insert($to_wallet, array('userid' => $transferid, 'balance' => $new_fund_tid, 'type' => ($to_wallet == 'product_wallet' ? 'product' : 'Default')));
            }
            
            // Debit sender
            $new_fund_uid = round($get_fund_uid - $balance, 2);
            $this->db->where('userid', $uid)->update('wallet', array('balance' => $new_fund_uid));
            
            // Ledger logs
            $w_transData = array(
                'userid'       => $uid,
                'type'         => 'Debit',
                'amount'       => $balance,
                'ref_id'       => 'P2P_TO_' . $transferid,
                'other'        => 'Transfer to ' . $transferid . ' (' . $to_wallet . ')',
                'created_date' => date('Y-m-d H:i:s'),
            );
            $this->db->insert('wallet_transaction', $w_transData); 
            
            $w_transData2 = array(
                'userid'       => $transferid,
                'type'         => 'Credit',
                'amount'       => $balance,
                'ref_id'       => 'P2P_FROM_' . $uid,
                'other'        => 'Transfer received from ' . $uid . ' into ' . $to_wallet,
                'created_date' => date('Y-m-d H:i:s'),
            );
            $this->db->insert('wallet_transaction', $w_transData2); 

            $data = array(
                'transfer_from' => $uid,
                'transfer_to'   => $transferid,
                'amount'        => $balance,
                'time'          => date('Y-m-d H:i:s'),
            );
            $this->db->insert('transfer_balance_records', $data);

            $this->db->trans_complete();
            
            if ($this->db->trans_status() === FALSE) {
                $this->session->set_flashdata('common_flash', '<div class="alert alert-danger">Transfer transaction failed. Please try again.</div>');
            } else {
                $this->session->set_flashdata('common_flash', '<div class="alert alert-success">Fund Transferred Successfully (₹' . number_format($balance, 2) . ' to User #' . $transferid . ').</div>');
            }
            redirect('wallet/transfer-balance');
        }
    }

    public function withdrawal_list($status = '', $sdate = '', $edate = '')  
    {
        if ($this->login->check_member() == FALSE) {
            redirect(site_url('site/login'));
        }

        if ($this->input->post('status') !== null) {
            $status = $this->input->post('status');
            $sdate  = $this->input->post('sdate');
            $edate  = $this->input->post('edate');
        }

        $this->db->select('*')->from('withdraw_request')->where('userid', $this->session->user_id);
        if (!empty($status) && $status != 'All') {
            $this->db->where('status', $status);
        }
        if (!empty($sdate)) {
            $this->db->where('date >=', $sdate);
        }
        if (!empty($edate)) {
            $this->db->where('date <=', $edate);
        }
        $this->db->order_by('id', 'DESC');
        $data['withdraw_request'] = $this->db->get()->result_array();
        
        $data['title']      = 'Withdrawal Report';
        $data['breadcrumb'] = 'Withdrawal Report';
        $data['layout']     = 'wallet/withdrawl_report.php';
        $this->load->view('member/index', $data);
    }

    public function withdraw_request() 
    {
        $this->withdrawal_list();
    }
 
    public function withdraw_payouts()
    {
        $this->db_model->check_and_update_wallet_schema();
        $min_w = floatval(config_item('min_withdraw'));
        $this->form_validation->set_rules('amount', 'Amount', 'trim|required|numeric');
        
        if ($this->form_validation->run() == FALSE) {
            $data['title']          = 'Withdraw Wallet Funds';
            $data['breadcrumb']     = 'Withdraw Funds';
            $data['wallet_summary'] = $this->db_model->get_wallet_summary($this->session->user_id);
            $data['layout']         = 'wallet/withdraw_fund.php';
            $this->load->view('member/index', $data);
        } else {
            $uid     = $this->session->user_id;
            $balance = round((float)$this->input->post('amount'), 2);
            
            $sender_w     = $this->db->get_where('wallet', array('userid' => $uid))->row();
            $get_fund_uid = $sender_w ? (float)$sender_w->balance : 0.0;
            $get_pan_uid  = $this->db_model->select('tax_no', 'member_profile', array('userid' => $uid));
            
            if (empty($get_pan_uid) || $get_pan_uid == 'N/A') {
                $this->session->set_flashdata('common_flash', '<div class="alert alert-danger">Valid PAN card number is required in your KYC profile before requesting a withdrawal. Please update your profile.</div>');
                redirect('wallet/withdraw-payouts');
                return;
            }

            if ($balance < $min_w) {
                $this->session->set_flashdata('common_flash', '<div class="alert alert-danger">Minimum withdrawal amount is ' . config_item('currency') . number_format($min_w, 2) . '.</div>');
                redirect('wallet/withdraw-payouts');
                return;
            }

            if ($balance > $get_fund_uid || $balance <= 0) {
                $this->session->set_flashdata('common_flash', '<div class="alert alert-danger">Insufficient balance in your wallet. Available: ' . config_item('currency') . number_format($get_fund_uid, 2) . '</div>');
                redirect('wallet/withdraw-payouts');
                return;
            }

            $selfid = $this->input->post('pay_type');
            
            if ($selfid == $uid || $selfid == 'product_wallet') {
                // Transfer from Main Wallet to Self Product Wallet
                $this->db->trans_start();

                $new_fund = round($get_fund_uid - $balance, 2);
                $this->db->where('userid', $uid)->update('wallet', array('balance' => $new_fund));

                $chk_pw = $this->db->get_where('product_wallet', array('userid' => $uid))->row();
                $cur_pw = $chk_pw ? (float)$chk_pw->balance : 0.0;
                $new_pw = round($cur_pw + $balance, 2);
                if ($chk_pw) {
                    $this->db->where('userid', $uid)->update('product_wallet', array('balance' => $new_pw));
                } else {
                    $this->db->insert('product_wallet', array('userid' => $uid, 'balance' => $new_pw, 'type' => 'product'));
                }

                $w_transData = array(
                    'userid'       => $uid,
                    'type'         => 'Debit',
                    'amount'       => $balance,
                    'ref_id'       => $uid,
                    'other'        => 'Transfer to Self Product Wallet',
                    'created_date' => date('Y-m-d H:i:s'),
                );
                $this->db->insert('wallet_transaction', $w_transData);

                $w_transData2 = array(
                    'userid'       => $uid,
                    'type'         => 'Credit',
                    'amount'       => $balance,
                    'ref_id'       => $uid,
                    'other'        => 'Credit from Main Wallet to Product Wallet',
                    'created_date' => date('Y-m-d H:i:s'),
                );
                $this->db->insert('wallet_transaction', $w_transData2);

                $this->db->trans_complete();

                $this->session->set_flashdata('common_flash', '<div class="alert alert-success">Fund transferred to Product Wallet successfully.</div>');
                redirect('wallet/withdraw-payouts');
            } else {
                // Bank / UPI Payout request
                $admin_pct  = floatval(config_item('admin_charges'));
                $tds_pct    = floatval(config_item('payout_tax'));
                $admin_tax  = round(($balance * $admin_pct) / 100.0, 2);
                $tds_tax    = round(($balance * $tds_pct) / 100.0, 2);
                $total_tax  = round($admin_tax + $tds_tax, 2);
                $net_paid   = round($balance - $total_tax, 2);

                $this->db->trans_start();

                $new_fund = round($get_fund_uid - $balance, 2);
                $this->db->where('userid', $uid)->update('wallet', array('balance' => $new_fund));

                $data = array(
                    'userid'      => $uid,
                    'amount'      => $balance,
                    'tax'         => $total_tax,
                    'admin_tax'   => $admin_tax,
                    'tds_tax'     => $tds_tax,
                    'net_paid'    => $net_paid,
                    'pan_no'      => $get_pan_uid,
                    'withdraw_in' => $selfid, // 'other' (Bank) or 'upi'
                    'status'      => 'Un-Paid',
                    'date'        => date('Y-m-d'),
                );
                $this->db->insert('withdraw_request', $data);
                $req_id = $this->db->insert_id();

                $w_transData = array(
                    'userid'       => $uid,
                    'type'         => 'Debit',
                    'amount'       => $balance,
                    'ref_id'       => 'WITHDRAW_REQ_' . $req_id,
                    'other'        => 'Payout Withdrawal Request #' . $req_id . ' (' . ($selfid == 'upi' ? 'UPI' : 'Bank Account') . ')',
                    'created_date' => date('Y-m-d H:i:s'),
                );
                $this->db->insert('wallet_transaction', $w_transData);

                $this->db->trans_complete();

                $this->session->set_flashdata('common_flash', '<div class="alert alert-success">Withdrawal request for ' . config_item('currency') . number_format($balance, 2) . ' submitted successfully. (Net Payable: ' . config_item('currency') . number_format($net_paid, 2) . ').</div>');
                redirect('wallet/withdraw-payouts');
            }
        }
    }


    public function balance_transfer_list()
    {
        $data['title']      = 'Wallet Transactions';
        $data['breadcrumb'] = 'Wallet Transactions';
        $data['layout']     = 'wallet/wallet_transactions.php';
        $this->load->view('member/index', $data);
    }
    public function get_wallet_balance($uid)
    {
        $uid = $this->common_model->filter($uid);
        $balance = $this->db_model->select('balance', 'wallet', array('userid' => $uid));
        
        if ($balance==''){
            echo $balance=0;
        }else{
            echo $balance;
        }
    }


    public function get_product_wallet_balance($uid)
    {
        $uid = $this->common_model->filter($uid);
        $balance = $this->db_model->select('balance', 'product_wallet', array('userid' => $uid));
        if ($balance==''){
            echo $balance=0;
        }else{
            echo $balance;
        }
    }




}