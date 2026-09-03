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
            $balance = $this->db_model->select_multi('amount,userid', 'deposite', array('id' => $id));
            if($balance->status != 'Approved'){
                $array = array(
                    'status' => 'Approved', 
                );
                $this->db->where('id', $id);
                $this->db->update('deposite', $array);
                
                $w_id    = $balance->userid;
                $w_amt   = $balance->amount;
                
                $this->db->query("UPDATE product_wallet SET balance = balance+$w_amt WHERE userid = '$w_id'"); 
                $data10 = array(
                    'userid'   => $balance->userid,
                    'amount'   => $balance->amount,
                    'type'     => "Credit",
                    'other'    => "Deposit Request Accepted By Admin",
                );
                $this->db->insert('wallet_transaction', $data10);
    
                $this->session->set_flashdata('common_flash', '<div class="alert alert-success">Wallet Balance Approved</div>');
                redirect('wallet/support');
            } else{
                $this->session->set_flashdata('common_flash', '<div class="alert alert-danger">Request Already Approved</div>');
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
            $balance = $this->input->post('balance');
            $type    = $this->input->post('submit');

            $udata    = $this->db_model->select_multi('phone,name', 'member', array('id' => $uid));
            $get_fund = $this->db_model->select('balance', 'wallet', array('userid' => $uid));
            
            $new_fund = $get_fund + $balance;
            $type2    = 'Credit';
            if ($type == "remove") {
                $new_fund = $get_fund - $balance;
                $type2    = 'Debit';
            }

            $array = array(
                'balance' => $new_fund,
            );
            $this->db->where('userid', $uid);
            $this->db->update('wallet', $array);

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
                    $epin_value  = $this->db_model->select('amount', 'epin', array('epin'   => $epin,'status' => 'Un-used')); 
                    if($addTo=='toMain'){
                        $walletType   ='Wallet';
                        $wal_bal=$this->db_model->select('balance', 'wallet', array('userid' =>$this->session->user_id));
                    }
                    else{
                        $walletType   ='Product Wallet';
                        $wal_bal=$this->db_model->select('balance', 'product_wallet', array('userid' =>$this->session->user_id));
                    }
                    if($epin !=='' && $epin_value>0 && $addTo!='' && $wal_bal !=null && $paytype=='epin'){
                        $wallet_data=array('balance'=>$wal_bal + $epin_value);
                        if($addTo=='toMain'){
                            $this->db->where('userid',$this->session->user_id);
                            $this->db->update('wallet',$wallet_data);
                            $data = array(
                                'status'    => 'Used',
                                'used_by'   => $this->session->user_id,
                                'used_time' => date('Y-m-d'),
                            );
                            $this->db->where('epin', $epin);
                            $this->db->update('epin', $data);
                        }
                        else{
                            $this->db->where('userid',$this->session->user_id);
                            $this->db->update('product_wallet',$wallet_data);
                            $data = array(
                                'status'    => 'Used',
                                'used_by'   => $this->session->user_id,
                                'used_time' => date('Y-m-d'),
                            );
                            $this->db->where('epin', $epin);
                            $this->db->update('epin', $data);
                       }
                        $w_transData = array(
                            'userid'     => $this->session->user_id,
                            'type'       =>'Credit',
                            'amount'     => $epin_value,
                            'ref_id'     => $epin,
                            'other'      => $walletType,
                        );
                        $this->db->insert('wallet_transaction', $w_transData);     
                        $this->session->set_flashdata('common_flash', '<div class="alert alert-success">Money added to wallet</div>');
                        redirect(site_url('member/topup-wallet'));
                    }
                    else{
                        $this->session->set_flashdata('common_flash', '<div class="alert alert-danger">Someting is wrong with add money #ErrorTopEpinWallet</div>');
                        redirect('member/topup_wallet');
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
                }else if(0 < $count_binary_income && $payout_type=='binary'){
                

                    $this->db->select('id, userid,type,amount')->where('status', 'Pending');
                    $data = $this->db->get('earning')->result();
                   
                    foreach ($data as $e) {
                    
                            $cur_balance = $this->db_model->select('balance', 'wallet', array('userid' => $e->userid));
                            $matching_total = $this->db_model->sum('amount', 'earning', array('userid' => $e->userid,'status'=>'Pending'));

                            if($matching_total>0){
                                $data = array('balance' => $cur_balance + $e->amount);  
                                 $this->db->where('userid', $e->userid);
                                $this->db->update('wallet', $data);
                               
                                $data = array('status' => 'Paid');
                                $this->db->where('id', $e->id);
                                $this->db->update('earning', $data); 

                            }
                        }
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
                                'userid' => $uid,
                                'amount' => $balance,
                                'date'   => date('Y-m-d'),
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
                                'userid' => $uid,
                                'amount' => $balance,
                                'date'   => date('Y-m-d'),
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
                                'userid' => $uid,
                                'amount' => $balance,
                                'date'   => date('Y-m-d'),
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
                                'userid' => $uid,
                                'amount' => $balance,
                                'date'   => date('Y-m-d'),
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
                                    'userid' => $uid,
                                    'amount' => $balance,
                                    'date'   => date('Y-m-d'),
                                );
                               
                                $this->db->insert('withdraw_request', $data);
                                $this->session->set_flashdata('common_flash', '<div class="alert alert-success">Payout calculated Successfully.</div>');
                            }
                            
                        
                    }
                #######################End ROI Sponsor Income###################
 

                ################ We will generate payout now ################
                $this->db->select('userid, balance, pan_no')->where('balance >=', floatval(config_item('min_withdraw')));
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
                        'userid' => $uid,
                        'amount' => $balance,
                        'pan_no' => $result->pan_no,
                        'date'   => date('Y-m-d'),
                    );
                    
                    $this->db->insert('withdraw_request', $data);
                    
                    /* SMS code for SMS on payout */
                    $this->db->select('name, phone')->where('id',$uid);
                    $udata = $this->db->get('member')->result();
                    
                    foreach ($udata as $ud) {
                    
                    $cur_balance = $this->db_model->select('balance', 'wallet', array('userid' => $id));
                    if (config_item('sms_on_join') == "Yes"):
                        $sms = "Hi " . $ud->name. ",Payout generated successfully. We have credited USDT.".$balance ." in your wallet. Thanks \nwww." . $_SERVER['HTTP_HOST']."\n";
                        $this->common_model->sms($ud->phone, $sms);
                    endif;
                }
                $this->session->set_flashdata('common_flash', '<div class="alert alert-success">Payout Generated Successfully.</div>');
                }
                if ($e !== 1) {
                    $this->session->set_flashdata('common_flash', '<div class="alert alert-info">No User Id has sufficient balance, Hence No Payout Generated.</div>');
                }
                redirect('income/make-payment');

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
            
            $trans_pass = $this->db_model->select('trans_password', 'member', array('id' => $this->session->user_id));
            if($this->input->post('trans_password') != $trans_pass){
                $this->session->set_flashdata('common_flash', '<div class="alert alert-danger">Incorrect Transaction Password</div>');
                redirect('wallet/transfer-balance');
            }
            
            $uid        = $this->session->user_id;
            $transferid = $this->common_model->filter($this->input->post('transferid'), 'number');
            $balance    = $this->input->post('amount');
            $to_wallet  = $this->input->post('paytype');
            $benifi_id  = $this->db_model->select('id', 'member', array('id' => $transferid));
            if($benifi_id == '' or $benifi_id == null){
                $this->session->set_flashdata('common_flash', '<div class="alert alert-danger">Invalid beneficiary user id! kindly re-enter again</div>');
                redirect('wallet/transfer-balance');
            }
               
            $get_fund_uid = $this->db_model->select('balance', 'wallet', array('userid' => $uid));
            $get_fund_tid = $this->db_model->select('balance', $to_wallet, array('userid' => $transferid));

            if ($get_fund_uid < $balance || $balance <= 0 || $this->session->user_id == $transferid) {
                $this->session->set_flashdata('common_flash', '<div class="alert alert-danger">In-sufficiant fund in wallet or cannot send in self wallet.</div>');
                redirect('wallet/transfer-balance');
            }
            
            $new_fund = $get_fund_tid + $balance;
            $array    = array(
                'balance' => $new_fund,
            );
            $this->db->where('userid', $transferid);
            $this->db->update($to_wallet, $array);
            
            $w_transData = array(
                            'userid'     => $uid,
                            'type'       =>'Debit',
                            'amount'     => $balance,
                            'ref_id'     => $transferid,
                            'other'      => $to_wallet,
                        );
            $this->db->insert('wallet_transaction', $w_transData); 
            
            $w_transData2 = array(
                            'userid'     => $transferid,
                            'type'       =>'Credit',
                            'amount'     => $balance,
                            'ref_id'     => $uid,
                            'other'      => $to_wallet,
                        );
            $this->db->insert('wallet_transaction', $w_transData2); 

            $array = array(
                'balance' => ($get_fund_uid - $balance),
            );
            $this->db->where('userid', $uid);
            $this->db->update('wallet', $array);

            $data = array(
                'transfer_from' => $uid,
                'transfer_to'   => $transferid,
                'amount'        => $balance,
                'time'          => date('Y-m-d'),
            );
            $this->db->insert('transfer_balance_records', $data);
            
            $this->session->set_flashdata('common_flash', '<div class="alert alert-success">Fund Transferred Successfully.</div>');
            redirect('wallet/transfer-balance');
        }
    }

    public function withdrawal_list()  
    {
        $status = $this->input->post('status');
        $sdate  = $this->input->post('sdate');
        $edate  = $this->input->post('edate');
        if (trim($status) == ""):
            $data['title']      = 'Withdrawal Report';
            $data['breadcrumb'] = 'Withdrawal Report';
            $data['layout']     = 'wallet/withdrawl_report.php';
            $this->load->view('member/index', $data);

        else:
            redirect(site_url('wallet/withdrawal_list/' . $status . '/' . $sdate . '/' . $edate));
        endif;
    }

    public function withdraw_request() 
    {
        $config['per_page']   = 100;
        $this->db->select('*')->from('withdraw_request')->where('userid', $this->session->user_id);
        $data['withdraw_request'] = $this->db->get()->result_array();
        $data['title']        = 'Payout';
        $data['breadcrumb']   = 'Payout Report';
        $data['layout']       = 'wallet/withdrawl_report.php';
        $this->load->view('member/index', $data);
    }
 
    public function withdraw_payouts()
    {
        $this->form_validation->set_rules('amount', 'Amount', 'trim|required|greater_than[' . config_item('min_withdraw') . ']');
        if ($this->form_validation->run() == FALSE) {
            $data['title']      = 'Withdraw Wallet Funds';
            $data['breadcrumb'] = 'Withdraw Funds';
            $data['layout']     = 'wallet/withdraw_fund.php';
            $this->load->view('member/index', $data);
        } else {
            
            // $trans_pass = $this->db_model->select('trans_password', 'member', array('id' => $this->session->user_id));
            // if($this->input->post('trans_password') != $trans_pass){
            //     $this->session->set_flashdata('common_flash', '<div class="alert alert-danger">Incorrect Transaction Password</div>');
            //     redirect('wallet/withdraw-payouts');
            // }
            
            $uid     = $this->session->user_id;
            $balance = $this->input->post('amount');
            $divide  = $balance/500;
            $get_fund_uid = $this->db_model->select('balance', 'wallet', array('userid' => $uid));
            $get_pan_uid  = $this->db_model->select('tax_no', 'member_profile', array('userid' => $uid));
            if ($get_pan_uid == '' or $get_pan_uid == 'N/A') {
                $this->session->set_flashdata('common_flash', '<div class="alert alert-danger">Pancard number must for sending withdraw request...</div>');
                redirect('wallet/withdraw_payouts');
            }
            if ($get_fund_uid < $balance || $balance < config_item('min_withdraw')) {
                $this->session->set_flashdata('common_flash', '<div class="alert alert-danger"> In-sufficient balance in your wallet or withdraw minimum: ' . config_item('currency') . config_item('min_withdraw') . '</div>');
                redirect('wallet/withdraw_payouts');
            }
            $new_fund = $get_fund_uid - $balance;
            $array    = array(
                'balance' => $new_fund,
            );
           
            ## This self transfer main wallet to product wallet if product wallet enabled
            $selfid = $this->input->post('pay_type');
           
            if (config_item('wallet_type')=="Yes" && $selfid=="other"){
               
                $this->db->where('userid', $uid);
                $this->db->update('wallet', $array);
               
            }else{
                $this->db->where('userid', $uid);
                $this->db->update('wallet', $array);
                $get_fund_uid_pr_wallet = $this->db_model->select('balance', 'product_wallet', array('userid' => $uid));
                $new_fund_prod_wallet = $get_fund_uid_pr_wallet + $balance;
                $array2    = array(
                    'balance' => $new_fund_prod_wallet,
                );
                $this->db->where('userid', $uid);
                $this->db->update('product_wallet', $array2);
            }
           
            $data = array(
                'userid' => $uid,
                'amount' => $balance,
                'withdraw_in' => $selfid,
                'date'   => date('Y-m-d'),
            );
            $this->db->insert('withdraw_request', $data);
           
            $this->session->set_flashdata('common_flash', '<div class="alert alert-success">Fund withdraw request sent sucessfully.</div>');
            redirect('wallet/withdraw_payouts');
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