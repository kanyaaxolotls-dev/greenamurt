<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Member extends CI_Controller
{

    public function __construct()
    { 
        parent::__construct(); 
        if ($this->login->check_member() == false) {
            redirect(site_url('site/login'));
        }   
        $this->load->library('pagination'); 
        $this->load->library('cart');
        
    }

    public function index()
    {
        $data['detail']     = $this->db_model->select_multi('total_a, total_b, total_c, total_d, total_e', 'member', array('id' => $this->session->user_id));
        
        $data['title']      = 'Dashboard';
        $data['breadcrumb'] = 'dashboard';
        $this->load->view('member/index', $data);
    }
    public function business_plan()
    {
        $data['title']     = 'Business Plan';
        $data['layout']    = "profile/business_plan.php";
        $this->load->view('member/index', $data); 
    }


    public function logout()
    {
        $this->session->sess_destroy();
        $this->session->set_flashdata('site_flash', '<div class="alert alert-info">You have been logged out !</div>');
        redirect(site_url('site/login'));
    }

    // CORE MEMBER PARTS HERE NOW ############################################################ STARTS :

    public function used_epin()
    {
        $config['base_url']   = site_url('member/used_epin');
        $config['per_page']   = 50;
        $config['total_rows'] = $this->db_model->count_all('epin', array(
            'status'   => 'Un-used',
            'issue_to' => $this->session->user_id,
        ));
        $page                 = ($this->uri->segment(3)) ? $this->uri->segment(3) : 0;
        $this->pagination->initialize($config);

        $this->db->select('id, epin, amount, used_by, used_time')->from('epin')->where('status', 'Used')
                 ->where('issue_to', $this->session->user_id)->limit($config['per_page'], $page);

        $data['epin'] = $this->db->get()->result_array();

        $data['title']  = 'In-Active E-Pins';
        $data['layout'] = 'epin/used.php';
        $this->load->view('member/index', $data);

    }

    public function unused_epin()
    {
        $config['base_url']   = site_url('member/unused_epin');
        $config['per_page']   = 50;
        $config['total_rows'] = $this->db_model->count_all('epin', array(
            'status'   => 'Un-used',
            'issue_to' => $this->session->user_id,
        ));
        $page                 = ($this->uri->segment(3)) ? $this->uri->segment(3) : 0;
        $this->pagination->initialize($config);

        $this->db->select('id, epin, amount, issue_to, generate_time, generate_time')->from('epin')
                 ->where('status', 'Un-used')->where('issue_to', $this->session->user_id)
                 ->limit($config['per_page'], $page);

        $data['epin'] = $this->db->get()->result_array();

        $data['title']  = 'Active E-Pin';
        $data['layout'] = 'epin/unused.php';
        $this->load->view('member/index', $data);

    }
        public function matrix(){
            $config['base_url']   = site_url('member/matrix');
            $config['per_page']   = 50;
            $config['total_rows'] = $this->db_model->count_all('pool_one', array(
                 'sponsor' => $this->session->user_id,
            ));
            $page                 = ($this->uri->segment(3)) ? $this->uri->segment(3) : 0;
            $this->pagination->initialize($config);
    
            $this->db->select('*')->from('pool_one')
                     ->where('sponsor',$this->session->user_id)
                     ->limit($config['per_page'], $page);
    
            $data['data'] = $this->db->get()->result_array();
    
            $data['title']  = 'Matrix direct';
            $data['layout'] = 'income/matrix.php';
            $this->load->view('member/index', $data);   
        }
    public function transfer_epin()
    {

        $this->form_validation->set_rules('amount', 'e-PIN Amount', 'trim|required');
        $this->form_validation->set_rules('to', 'To User ID', 'trim|required');
        $this->form_validation->set_rules('qty', 'Number of e-PINs', 'trim|required');
        if ($this->form_validation->run() == FALSE) {
            $data['title']  = 'Transfer e-PIN';
            $data['layout'] = 'epin/transfer_epin.php';
            $this->load->view('member/index', $data);
        } else {
            $amount = $this->common_model->filter($this->input->post('amount'), 'float');
            $to     = $this->common_model->filter($this->input->post('to'));
            $from   = $this->session->user_id;
            $qty    = $this->common_model->filter($this->input->post('qty'), 'number');

            $avl_qty = $this->db_model->count_all('epin', array(
                'issue_to' => $from,
                'amount'   => $amount,
                'status'   => 'Un-used',
            ));
            if ($avl_qty < $qty) {
                $this->session->set_flashdata('common_flash', '<div class="alert alert-danger">The User ID have only ' . $avl_qty . ' Un-used epin of ' . config_item('currency') . ' ' . $amount . '.</div>');
                $data['title']  = 'Transfer e-PIN';
                $data['layout'] = 'epin/transfer_epin.php';
                $this->load->view('member/index', $data);
            } else {
                $this->db->where(array(
                    'issue_to' => $from,
                    'amount'   => $amount,
                    'status'   => 'Un-used',
                ));
                $vals = array(
                    'issue_to'      => $to,
                    'transfer_by'   => $from,
                    'transfer_time' => date('Y-m-d'),
                );
                $this->db->limit($qty);
                $this->db->update('epin', $vals);

                $this->session->set_flashdata('common_flash', '<div class="alert alert-success">' . $qty . ' e-PIN transferred to  ' . $this->input->post('to') . ' of ' . $this->input->post('from') . ' of ' . config_item('currency') . ' ' . $amount . '.</div>');
                redirect('member/transfer_epin');
            }
        }
    }

    public function generate_epin()
    {
        $this->form_validation->set_rules('amount', 'e-PIN Amount', 'trim|required');
        $this->form_validation->set_rules('userid', 'Issue to ID', 'trim|required');
        $this->form_validation->set_rules('number', 'Number of e-PINs', 'trim|required|max_length[3]');
        if ($this->form_validation->run() == FALSE) {
            $data['title']  = 'Generate e-PIN';
            $data['layout'] = 'epin/generate.php'; 
            $this->load->view('member/index', $data);
        } else {
            $amount           = $this->common_model->filter($this->input->post('amount'), 'float');
            $userid           = $this->common_model->filter($this->input->post('userid'),'number');
            $qty              = $this->common_model->filter($this->input->post('number'), 'number');
     
                $ucnt=$this->db_model->count_all('member', array('id' =>$userid));
                if($ucnt == 0){
                    $this->session->set_flashdata('common_flash', '<div class="alert alert-danger">Userid is wrong..</div>');
                    redirect('member/generate-epin');  
                }
                if($userid = ""){
                    $this->session->set_flashdata('common_flash', '<div class="alert alert-danger">please enter userid..</div>');
                    redirect('member/generate-epin');  
                }
        
        
            $per_amt=$amount * (10/100);
            
            $cal_amt=$amount+$per_amt;

           
            $total_amt = $cal_amt * $qty;
        
            if (config_item('wallet_type')!=="Yes"){
                $get_user_balance = $this->db_model->select('balance', 'wallet', array('userid' => $this->session->user_id));
            }else{
                $get_user_balance = $this->db_model->select('balance', 'product_wallet', array('userid' => $this->session->user_id));
            }

            if ($get_user_balance < $total_amt) {
                $this->session->set_flashdata("common_flash", "<div class='alert alert-danger'>You wallet donot have sufficient balance to generate $qty e-PIN. Your wallet need to have " . config_item('currency') . $total_amt . "</div>");
                redirect('member/generate-epin');
            }

            $data = array();
            for ($i = 0; $i < $qty; $i++) {
                $rand = mt_rand(10000000, 99999999);
                $epin = $this->db_model->select("id", "epin", array("epin" => $rand));
                if ($rand == $epin) {
                    $rand = $rand + 1;
                }
                $array = array(
                    'epin'          => $rand,
                    'amount'        => $amount,
                    'issue_to'      => $this->input->post('userid'),
                    'generated_by'  => $this->session->user_id,
                    'generate_time' => date('Y-m-d'),
                );
                array_push($data, $array);
            }
            $this->db->insert_batch('epin', $data);
            $data10 = array(
                'userid' =>'1001',
                'amount' =>$per_amt,
                'type' =>"E-pin income",
                'ref_id' =>$this->session->user_id,
                'date' => date('Y-m-d'),
               );
            
            $this->db->insert('earning', $data10);
            $arra = array(
                'balance' => ($get_user_balance - $total_amt),
            );
            $this->db->where('userid', $this->session->user_id);

            if (config_item('wallet_type')!=="Yes"){
                $this->db->update('wallet', $arra);
            }else{
                $this->db->update('product_wallet', $arra);
            }
            $this->session->set_flashdata("common_flash", "<div class='alert alert-success'>$qty e-PIN created successfully.</div>");
            $this->common_model->mail($this->db_model->select('email', 'member', array('id' => $userid)), 'e-PIN Issued', 'Dear Sir, <br/> e-PIN of Qty ' . $qty . ', has been issued to your account from user id: ' . config_item('ID_EXT') . $this->session->user_id . ' on behalf of us.<br/><br/>---<br/>Regards,<br/>' . config_item('company_name'));
            redirect('member/generate_epin');

        }
    }

    public function view_earning()
    {
        $config['base_url']   = site_url('member/view_earning');
        $config['per_page']   = 100;
        $config['total_rows'] = $this->db_model->count_all('earning', array('userid' => $this->session->user_id));
        $page                 = ($this->uri->segment(3)) ? $this->uri->segment(3) : 0;
        $this->pagination->initialize($config);

        $this->db->select('id, userid, amount, type, ref_id, date, pair_match,levlno')->from('earning')
                 ->where('userid', $this->session->user_id)->where('amount >',0)->limit($config['per_page'], $page);

        $data['earning'] = $this->db->get()->result_array();

        $data['title']  = 'Earnings List';
        $data['layout'] = 'income/view_earning.php';
        $this->load->view('member/index', $data); 

    }
 
    ###### Matching earning list ####
   
    public function binary_roi_earning()
    { 
        $config['base_url']   = site_url('member/binary_roi_earning');
        $config['per_page']   = 100;
        $config['total_rows'] = $this->db_model->count_all('earning_roi', array('userid' => $this->session->user_id));
        $page                 = ($this->uri->segment(3)) ? $this->uri->segment(3) : 0;
        $this->pagination->initialize($config);

        $this->db->select('*')->from('earning_roi')->where('income_type','Matching ROI')
                 ->where('userid', $this->session->user_id)->limit($config['per_page'], $page);

        $data['earning'] = $this->db->get()->result_array();

        $data['title']  = 'Matching Earning List';
        $data['layout'] = 'income/binary_roi_earning.php';
        $this->load->view('member/index', $data);

    }

   public function daily_direct_roi_earning()
    { 
        $config['base_url']   = site_url('member/daily_direct_roi_earning');
        $config['per_page']   = 10;
        $config['total_rows'] = $this->db_model->count_all('earning_roi', array('userid' => $this->session->user_id));
        $page                 = ($this->uri->segment(3)) ? $this->uri->segment(3) : 0;
        $this->pagination->initialize($config);

        $this->db->select('*')->from('earning_roi')->where('income_type','Referral Reward')->where('userid', $this->session->user_id)->order_by('created_at','ASC')->limit($config['per_page'], $page);
        $data['earning'] = $this->db->get()->result_array();

        $data['title']  = 'Daily Direct Earning List';
        $data['layout'] = 'income/daily_direct_roi_earning.php';
        $this->load->view('member/index', $data);

    }
 
     public function daily_roi_earning()
    { 
       $config['base_url']   = site_url('member/daily_roi_report');
        $config['per_page']   = 100;
        $config['total_rows'] = $this->db_model->count_all('earning', array('userid' => $this->session->user_id));
        $page                 = ($this->uri->segment(3)) ? $this->uri->segment(3) : 0;
        $this->pagination->initialize($config);

        $this->db->select('*')->from('earning')->where('type','ROI')
                 ->where('userid', $this->session->user_id)->limit($config['per_page'], $page);

        $data['earning'] = $this->db->get()->result_array();

        $data['title']  = 'Daily ROI Earning List';
        $data['layout'] = 'income/daily_roi_report.php';
        $this->load->view('member/index', $data);

    }

    public function daily_level_roi_earning()
    { 
        $config['base_url']   = site_url('member/daily_level_roi_earning');
        $config['per_page']   = 100;
        $config['total_rows'] = $this->db_model->count_all('earning_roi', array('userid' => $this->session->user_id));
        $page                 = ($this->uri->segment(3)) ? $this->uri->segment(3) : 0;
        $this->pagination->initialize($config);

        $this->db->select('*')->from('earning_roi')->where('income_type','Sponsor Level Inc')
                 ->where('userid', $this->session->user_id)->order_by('created_at','DESC')->limit($config['per_page'], $page);
        $data['earning'] = $this->db->get()->result_array();

        $data['title']  = 'Daily Level Earning List';
        $data['layout'] = 'income/daily_level_roi_earning.php';
        $this->load->view('member/index', $data);

    }


     public function roi_earning()
    { 
        $config['base_url']   = site_url('member/roi_earning');
        $config['per_page']   = 100;
        $config['total_rows'] = $this->db_model->count_all('earning_roi', array('userid' => $this->session->user_id));
        $page                 = ($this->uri->segment(3)) ? $this->uri->segment(3) : 0;
        $this->pagination->initialize($config);

        $this->db->select('*')->from('earning_roi')->where('income_type','Referral Reward')
                 ->where('userid', $this->session->user_id)->limit($config['per_page'], $page);

        $data['earning'] = $this->db->get()->result_array();

        $data['title']  = 'ROI Earning';
        $data['layout'] = 'income/roi_earning.php';
        $this->load->view('member/index', $data);

    }


      public function matching_earning()
    { 
        $config['base_url']   = site_url('member/matching_earning');
        $config['per_page']   = 100;
        $config['total_rows'] = $this->db_model->count_all('earning', array('userid' => $this->session->user_id));
        $page                 = ($this->uri->segment(3)) ? $this->uri->segment(3) : 0;
        $this->pagination->initialize($config);

        $this->db->select('*')->from('earning')->where('type','Matching Income')
                 ->where('userid', $this->session->user_id)->limit($config['per_page'], $page);

        $data['earning'] = $this->db->get()->result_array();

        $data['title']  = 'Matching Earning List';
        $data['layout'] = 'income/matching_earning.php';
        $this->load->view('member/index', $data);

    }

    public function sponsor_earning()
    { 
        $config['base_url']   = site_url('member/sponsor_income');
        $config['per_page']   = 100;
        $config['total_rows'] = $this->db_model->count_all('earning_roi', array('userid' => $this->session->user_id));
        $page                 = ($this->uri->segment(3)) ? $this->uri->segment(3) : 0;
        $this->pagination->initialize($config);

        $this->db->select('*')->from('earning')->where('type','Referral Reward')
                 ->where('userid', $this->session->user_id)->limit($config['per_page'], $page);

        $data['earning'] = $this->db->get()->result_array();

        $data['title']  = 'Referral Earning List';
        $data['layout'] = 'income/sponsor_income.php';
        $this->load->view('member/index', $data);

    }


    public function topup_wallet() 
    {
        if (!isset($_POST['amount']) && !isset($_POST['epin'])) {
            $data['title']  = 'Fund My Wallet';
            $data['layout'] = 'wallet/topup-wallet.php';
            $this->load->view('member/index', $data);
        } else {
 
            $epin   = trim($this->input->post('epin'));
            $amount = trim($this->input->post('amount'));
            
            if ($epin !== "") {
                $epin_value = $this->db_model->select('amount', 'epin', array(
                    'epin'   => $epin,
                    'status' => 'Un-used', 
                ));
 
                if ($epin_value <= 0) {
                    $this->session->set_flashdata('common_flash', '<div class="alert alert-danger">The entered e-PIN is invalid or doesn\'t exist.</div>');
                    redirect(site_url('member/topup-wallet'));
                } else {
                    
                    if (config_item('wallet_type')!="Yes"){
                    $wallet_balance = $this->db_model->select('balance', 'wallet', array('userid' => $this->session->user_id));

                    }else{
                        $wallet_balance = $this->db_model->select('balance', 'product_wallet', array('userid' => $this->session->user_id));
                    }
                   

                    if (config_item('wallet_type')!="Yes"){
                          // MAIN WALLET TOPUP
                        $result=$this->db_model->select_multi('userid', 'wallet', array('userid' =>$this->session->user_id));
                      
                         if ($result==""){
                             $array1=array(
                                 'userid'=>$this->session->user_id,
                                 'balance'=>$epin_value,
                                 'type'=>'Topup',
                            
                                 );
                                $this->db->insert('wallet',$array1);
                            }else{

                                $this->db->where(array('userid' => $this->session->user_id));
                                $this->db->update('wallet', array('balance' => $wallet_balance + $epin_value));       
                            }

                         
                    }else{
                      
                        // PRODUCT WALLET TOPUP
                       $result=$this->db_model->select_multi('userid', 'product_wallet', array('userid' =>$this->session->user_id));
                      
                         if ($result==""){
                             $array1=array( 
                                 'userid'=>$this->session->user_id,
                                 'balance'=>$epin_value,
                                 'type'=>'Topup',
                             
                                 );
                                $this->db->insert('product_wallet',$array1);

                         }else{
                             $this->db->where(array('userid' => $this->session->user_id));
                            $this->db->update('product_wallet', array('balance' => $wallet_balance + $epin_value));

                        }
                    }
                    
                    $data = array(
                        'status'    => 'Used',
                        'used_by'   => $this->session->user_id,
                        'used_time' => date('Y-m-d'),
                    );

                    $this->db->where('epin', $epin);
                    $this->db->update('epin', $data);

                    $this->session->set_flashdata('common_flash', '<div class="alert alert-success">Fund is added to your wallet.</div>');
                    redirect(site_url('member/topup-wallet'));
                }

            } else {
 
                $user_data = $this->db_model->select_multi('sponsor, address, email, phone', 'member', array('id' => $this->session->user_id));

                $this->session->set_userdata('_user_id_', $this->session->user_id);
                $this->session->set_userdata('_user_name_', $this->session->name);
                $this->session->set_userdata('_inv_id_', rand());
                $this->session->set_userdata('_sponsor_', $user_data->sponsor);
                $this->session->set_userdata('_address_', $user_data->address);
                $this->session->set_userdata('_email_', $user_data->email);
                $this->session->set_userdata('_phone_', $user_data->phone);
                $this->session->set_userdata('_product_', 'Add Wallet Fund');
                $this->session->set_userdata('_price_', $amount);
                $this->session->set_userdata('_type_', 'wallet');
                $this->session->set_userdata('_coin_', $this->input->post('coin_wallet'));
                
                redirect('gateway/payu');

            } 
        }
    }

   
    public function failed_fund()
    {
        //var_dump("expression2");die();
        $this->session->set_flashdata("common_flash", "<div class='alert alert-danger'>Your payment is not completed. So your fund was not added.</div>");
        redirect(site_url('member/topup-wallet'));
    }

    public function complete_add_fund()
    {
        $wallet_balance = $this->db_model->select('balance', 'wallet', array('userid' => $this->session->user_id));
        $this->db->where(array('userid' => $this->session->user_id));
        $this->db->update('wallet', array('balance' => $wallet_balance + $this->session->_price_));
        $this->session->set_flashdata('common_flash', '<div class="alert alert-success">Fund is added to your wallet.</div>');
        redirect(site_url('member/topup-wallet'));
    }


    public function my_rewards() 
    {
        $config['base_url']   = site_url('member/my_rewards');
        $config['per_page']   = 100;
        $config['total_rows'] = $this->db_model->count_all('rewards', array('userid' => $this->session->user_id));
        $page                 = ($this->uri->segment(3)) ? $this->uri->segment(3) : 0;
        $this->pagination->initialize($config);

        $this->db->select('id, reward_id, date, paid_date, tid')->from('rewards')
                 ->where('userid', $this->session->user_id)->limit($config['per_page'], $page);

        $data['rewards'] = $this->db->get()->result_array();

        $data['title']  = 'Earned Rewards';
        $data['layout'] = 'income/rewards.php';
        $this->load->view('member/index', $data);

    }

    public function search_earning()
    {
        $data['title']  = 'Search your earning';
        $data['layout'] = 'income/search_income.php';
        $this->load->view('member/index', $data);
    }

    public function income_search()
    {
        $income_name = $this->input->post('income_name');
        $startdate   = $this->input->post('startdate');
        $enddate     = $this->input->post('enddate');

        $this->db->select('id, userid, amount, type, ref_id, date, pair_match,levlno')->from('earning');
        if ($income_name !== "All") {
            $this->db->where('type', $this->input->post('income_name'));
        }
        $this->db->where('userid', $this->session->user_id);
        if (trim($startdate) !== "") {
            $this->db->where('date >=', $startdate);
        }
        if (trim($enddate) !== "") {
            $this->db->where('date <=', $enddate);
        }

        $data['earning'] = $this->db->get()->result_array();
        $data['title']   = 'Search Results';
        $data['layout']  = 'income/view_earning.php';
        $this->load->view('member/index', $data);

    }

    public function settings()
    {
        $this->form_validation->set_rules('oldpass', 'Current Password', 'trim|required');
        $this->form_validation->set_rules('newpass', 'New Password', 'trim|required');
        $this->form_validation->set_rules('repass', 'Retype Password', 'trim|required|matches[newpass]');
        if ($this->form_validation->run() == FALSE) {
            $data['title']  = 'Change Your Password';
            $data['layout'] = 'profile/acsetting.php';
            $this->load->view('member/index', $data); 
        } else {

            $mypass = $this->db_model->select('password', 'member', array('id' => $this->session->user_id));

            if (password_verify($this->input->post('oldpass'), $mypass) == TRUE) {

                $array = array(
                    'password' => password_hash($this->input->post('newpass'), PASSWORD_DEFAULT),
                );
                $this->db->where('id', $this->session->user_id);
                $this->db->update('member', $array);
                $this->session->set_flashdata('common_flash', '<div class="alert alert-success">Settings Saved Successfully.</div>');
                redirect('member/settings');
            } else {
                $this->session->set_flashdata('common_flash', '<div class="alert alert-danger">The entered "Current Password" is wrong.</div>');
                redirect('member/settings');
            }
        }
    }

 
    public function proile()
    {
        $this->form_validation->set_rules('oldpass', 'Current Password', 'trim|required');
     
        if ($this->form_validation->run() == FALSE) {
            //$data['data']   = $this->db_model->select_multi('*', 'member_profile', array('userid' => $this->session->user_id));
            $data['my']     = $this->db_model->select_multi('phone, email', 'member', array('id' => $this->session->user_id));
            $data['title']  = 'Manage Profile';
            $data['layout'] = 'profile/profile.php';
            $this->load->view('member/index', $data);
        } else {

            $mypass = $this->db_model->select('password', 'member', array('id' => $this->session->user_id));

            if (password_verify($this->input->post('oldpass'), $mypass) == TRUE) {
                $query = $this->db->get_where('member_profile', array('userid' => $this->session->user_id));
                $result = $query->row_array();
                $this->load->library('upload');

                    if (!$this->upload->do_upload('add_proof')) 
                    {
                        $this->session->set_flashdata('common_flash', '<div class="alert alert-danger">Address Proof not uploaded..<br/>' . $this->upload->display_errors() . '</div>');
                       $add_proof=$result['add_proof'];
                       
                    } 
                    else 
                    {
                       
                         $data = array('upload_data' => $this->upload->data());
                       
                        $add_proof=$data['upload_data']['file_name'];
                    }
              
                    $this->load->library('upload');

                    if (!$this->upload->do_upload('id_proof')) 
                    {
                        $this->session->set_flashdata('common_flash', '<div class="alert alert-danger">ID Proof not uploaded..<br/>' . $this->upload->display_errors() . '</div>');
                          $id_proof=$result['id_proof'];
                      
                    } 
                    else
                    {
                       
                         $data = array('upload_data' => $this->upload->data());
                        
                        $id_proof=$data['upload_data']['file_name'];
                    }
              

                $array = array(
                    'tax_no'           => $this->input->post('tax_no'),
                    'aadhar_no'        => $this->input->post('aadhar_no'),
                    'bank_ac_no'       => $this->input->post('bank_ac_no'),
                    'bank_name'        => $this->input->post('bank_name'),
                    'bank_ifsc'        => $this->input->post('bank_ifsc'),
                    'bank_branch'      => $this->input->post('bank_branch'),
                    'btc_address'      => $this->input->post('btc_address'),
                    'nominee_name'     => $this->input->post('nominee_name'),
                    'nominee_add'      => $this->input->post('nominee_add'),
                    'nominee_relation' => $this->input->post('nominee_relation'),
                    'date_of_birth'    => $this->input->post('date_of_birth'),
                    'gstin'            => $this->input->post('gstin'),
                    'add_proof' => $add_proof,
                    'id_proof' => $id_proof,
                    'prof_pic' => $prof_pic,
                );
               
                if(strlen($array['tax_no'])!=10){
                    $this->session->set_flashdata('common_flash', '<div class="alert alert-danger"> Please Enter valid PAN number</div>');
                redirect('member/proile');
                }


                $this->db->where('userid', $this->session->user_id);
                $this->db->update('member_profile', $array);

                $array = array(
                    'name'  => $this->input->post('my_name'),
                    'phone' => $this->input->post('my_phone'),
                    'email' => $this->input->post('my_email'),
                   
                );
                $this->db->where('id', $this->session->user_id);
                $this->db->update('member', $array);

                $this->session->set_userdata('name', $this->input->post('my_name'));
                if(config_item('auto_payout') == "Yes"){
                    $bank_ifsc=$this->input->post('bank_ifsc');
                    $bank_acc=$this->input->post('bank_ac_no');
                    $name=$this->db_model->select_multi("name,contact_id", 'member', array('id' =>$this->session->user_id));
                
                    $detail=array (
                        'contact_id' =>$name->contact_id,
                        'account_type' => 'bank_account',
                        'bank_account' => 
                        array (
                          'name' =>$name->name,
                          'ifsc' =>$bank_ifsc,
                          'account_number' =>$bank_acc,
                        ),
                    );
    
                    $url = 'https://api.razorpay.com/v1/fund_accounts';
            
                    $fields_string = json_encode($detail);
             
                    //open connection
                    $ch = curl_init();
                
                    //set the url, number of POST vars, POST data
                    curl_setopt($ch,CURLOPT_URL,$url);
                    curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
                    curl_setopt($ch, CURLOPT_POST, 1);
                    curl_setopt($ch,CURLOPT_POSTFIELDS,$fields_string);
                    curl_setopt($ch, CURLOPT_HTTPHEADER, array(
                        'X-Payout-Idempotency: ',
                        'Authorization: Basic cnpwX3Rlc3RfTHNZNnhQS3FBdW1SN0k6M0lMcUhKZmN4WDloVnZ5RlhaV2toS0dl',
                        'Content-Type: application/json'
                      ));
                    curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, FALSE);
                
                    //execute post
                    $result = curl_exec($ch);
                    
                    $data = json_decode($result,true);
                  //  var_dump($data);
                    $cid=$data['id'];
                    $fund_id = array(
                        'fund_account'=>$cid,
                     );
                     $this->db->where('id',$this->session->user_id);
                     $this->db->update('member', $fund_id);
                    // //close connection
                    curl_close($ch);
                  // return $data;
                
                  }
                    $email=$this->input->post('my_email');
                    $sub = "profile";
                    $msg = "Profile Updated Successfully " . $_SERVER['HTTP_HOST'];
                     $this->load->config('email');
                
                    if (trim(config_item('smtp_host')) !== "") {
                  
                     $this->common_model->mail($email, $sub, $msg);
                    } 

                $this->session->set_flashdata('common_flash', '<div class="alert alert-success">Successfully updated your profile.</div>');
                
               redirect('member/proile');
            } else {
                $this->session->set_flashdata('common_flash', '<div class="alert alert-danger">The entered "Current Password" is wrong.</div>');
                redirect('member/proile');
            }
        }
    }


    public function welcome_letter()
    {
        $data['file_data'] = file_get_contents(FCPATH . "uploads/welcome_letter.txt");
        $data['title']     = 'Welcome Letter';
        $data['layout']    = "profile/welcome_letter.php";
        $this->load->view('member/index', $data);
    }
    public function mail(){
        $data['title']      = 'Send Mail';
        $data['breadcrumb'] = 'Send Mail';
        $data['layout']     = 'support/email_send.php';
        $this->load->view('member/index', $data);   
    }
    public function send(){
        $to = 'archana.g@axolotls.in';  
        $subject =$this->input->post('Subject'); 
        $from = $this->input->post('from');  
       
        $emailContent .= $this->input->post('message');  //   Post message available here
        $config['protocol']    = 'smtp';
        $config['smtp_host']    = 'mail.axolotls.in';
        $config['smtp_port']    = '465';
        $config['smtp_timeout'] = '60';
    
        $config['smtp_user']    = 'archana.g@axolotls.in';    //Important
        $config['smtp_pass']    = 'yMzX@5WQPm!=';  //Important
    
        $config['charset']    = 'utf-8';
        $config['newline']    = "\r\n";
        $config['mailtype'] = 'html'; // or html
        $config['validation'] = TRUE; // bool whether to validate email or not     
     
        $this->email->initialize($config);
        $this->email->set_mailtype("html");
        $this->email->from($from);
        $this->email->to($to);
        $this->email->subject($subject);
        $this->email->message($emailContent);
        $this->email->send();
    
        $this->session->set_flashdata('msg',"Mail has been sent successfully");
        $this->session->set_flashdata('msg_class','alert-success');
        redirect('member/mail');
    }
   
    public function welcome_letter_details()
    {
        $data['file_data'] = file_get_contents(FCPATH . "uploads/welcome_letter.txt");
        $data['title']     = 'Welcome Letter';
        $data['layout']    = "profile/welcome_letter_print.php";
        $this->load->view('member/welcome_letter_base', $data);
    }


    // public function topup() 
    // {
    //     $epin_value = $this->db_model->select('amount', 'epin', array(
    //         'epin'   => trim($this->input->post('topup')),
    //         'status' => 'Un-used',
    //     ));

    //     $this->load->model('earning');
    //     if (config_item('fix_income') == "Yes" && $epin_value > 0 && config_item('give_income_on_topup') == "Yes") {
    //         $this->earning->fix_income($this->session->user_id, $this->db_model->select('sponsor', 'member', array('id' => $this->session->user_id)), $epin_value);
    //     } else if (config_item('fix_income') !== "Yes" && $epin_value > 0 && config_item('give_income_on_topup') == "Yes") {
    //         $this->earning->reg_earning($this->session->user_id, $this->db_model->select('sponsor', 'member', array('id' => $this->session->user_id)), $this->db_model->select('signup_package', 'member', array('id' => $this->session->user_id)));
    //     }
       
    //     $signup_package=$this->input->post('signup_package');
    //     $sign_package_epin=$this->input->post('topup'); 
    //     $package_cost=$this->db_model->select_multi('prod_price,pv', 'product', array('id' => $signup_package));
       
          
    //     if ($epin_value > 0 && $epin_value == $package_cost->prod_price) {
    //         $data = array( 
    //             'topup' => $epin_value,
    //             'signup_package'=>$signup_package,
    //             'mypv'=>$package_cost->pv,
    //             'epin'=>$sign_package_epin,
    //         ); 
            
    //         $this->db->where('id', $this->session->user_id);
    //         $this->db->update('member', $data); 

    //         //  $data1 = array(
    //         //     'cost' => $epin_value, 
    //         //     'product_id' => $signup_package,
    //         // ); 

    //         /* First purchse earning on topup */
    //           if (config_item('prevent_join_product_entry') == "Yes") {
    //                     $data1 = array(
    //                         'product_id' => $signup_package,
    //                         'userid'     => $this->session->user_id,
    //                         'cost'       => $epin_value,
    //                         'date'       => date('Y-m-d'),
    //                     );
    //                     $this->db->insert('product_sale', $data1);
    //                 }
    //             else
    //              {   $this->db->where('userid', $this->session->user_id);
    //                  $this->db->update('product_sale', $data1); 
                
    //             } 

    //         // /* For repurchase income on Topup */
    //         // $this->load->model('earning');
    //         // $this->earning->repurchase($orderid);
             

    //         $data = array(
    //             'status'    => 'Used',
    //             'used_by'   => $this->session->user_id,
    //             'used_time' => date('Y-m-d'),
    //         );
    //         $this->db->where('epin', trim($this->input->post('topup')));
    //         $this->db->update('epin', $data);

            
    //         $this->session->set_flashdata('common_flash', '<div class="alert alert-success">Successfully Top-uped your account.</div>');
    //         redirect(site_url('member'));
    //     } else {
    //         $this->session->set_flashdata('common_flash', '<div class="alert alert-danger">The entered e-PIN is not valid or used or product cost not matched with epin value.</div>');
    //         redirect(site_url('member'));
    //     }
    // }
  public function upgrade(){
        $date= $this->db_model->select('upgrade_at','upgrad_level', array('userid'=>$this->session->user_id));
        if($date == date('Y-m-d')){
            $this->session->set_flashdata('common_flash', '<div class="alert alert-danger">User can invest only one time..</div>');
            redirect(site_url('member'));
        }
        $amount=($this->input->post('amount'));
       if($amount<50){
        $this->session->set_flashdata('common_flash', '<div class="alert alert-danger">Topup Amount Should greater than 50 USDT</div>');
        redirect(site_url('member'));   
       }
       else{
        $user_amt = $this->db_model->select('balance', 'wallet', array(
            'userid'=>$this->session->user_id));
            if($user_amt<$amount){
                $this->session->set_flashdata('common_flash', '<div class="alert alert-danger">Insufficient Wallet Balance</div>');
                redirect(site_url('member'));   
            } else{
          $sub_amt=$user_amt-$amount;
      
          $data = array(
           'balance'=>$sub_amt
        );
        $this->db->where('userid',$this->session->user_id);
        $this->db->update('wallet', $data);
        
        $data = array(
            'balance'=>$sub_amt
         );
         $this->db->where('userid',$this->session->user_id);
         $this->db->update('wallet', $data);
        
         $data = array(
            'userid'  => $this->session->user_id,
            'upgrade_amt' =>$amount,
            'status'=>'Active',
            'upgrade_at'=>date('Y-m-d')
        );
        $this->db->insert('upgrad_level', $data);
       
        $this->session->set_flashdata('common_flash', '<div class="alert alert-success">Upgrade successfully</div>');
                redirect(site_url('member')); 
    }

    }
    }
    public function profit_earning(){
        $config['base_url']   = site_url('member/profit_earning');
        $config['per_page']   = 100;
        $config['total_rows'] = $this->db_model->count_all('earning', array('userid' => $this->session->user_id));
        $page                 = ($this->uri->segment(3)) ? $this->uri->segment(3) : 0;
        $this->pagination->initialize($config);

        $this->db->select('*')->group_by('date')->select_sum('amount')->from('earning')->order_by('date','DESC')->where('status','Paid')->where('userid',$this->session->user_id)->where('type','Profit Income')->limit($config['per_page'], $page);

        $data['earning'] = $this->db->get()->result_array();

        $data['title']      = 'My Profit';
        $data['breadcrumb'] = 'My Profit';
        $data['layout']     = 'invoice/profit_list_member.php';
        $this->load->view('member/index', $data);

    }

    public function topup()   
    { 
        $epin_value = $this->db_model->select('amount', 'epin', array(
            'epin'   => trim($this->input->post('checktopup')),
            'status' => 'Un-used',
        ));

        ### Get Wallet Fund AS PER TYPE ####
            //  if (config_item('wallet_type')!="Yes"){
            //     $wallet_balance = $this->db_model->select('balance', 'wallet', array('userid' => $this->session->user_id));
            //  }else{
            //     $wallet_balance = $this->db_model->select('balance', 'product_wallet', array('userid' => $this->session->user_id));
            // }
      
       $getFrmWallet = $this->input->post('checktopup');
        $signup_package=$this->input->post('signup_package');
        $sign_package_epin=$this->input->post('checktopup'); 
        $package_cost=$this->db_model->select_multi('prod_price,pv,matching_income', 'product', array('id' => $signup_package));
        $user_mypv = $this->db_model->select('mypv', 'member', array('id' => $this->session->user_id));
        $active_package_cost = $this->db_model->select('topup', 'member', array('id' => $this->session->user_id));
        $spccnt = $this->db_model->count_all('member', array('sponsor' => $this->session->user_id));

        $activation_date = date('Y-m-d H:i:s');

        if ($epin_value > 0 && $epin_value == $package_cost->prod_price) {
            $data = array( 
                'topup' => $epin_value,
                'signup_package'=>$signup_package,
                'mypv'=>$package_cost->pv,
                'epin'=>$sign_package_epin,
                'activation_date' =>$activation_date,
                'my_business'=>$package_cost->matching_income,
            ); 
              
            $this->db->where('id', $this->session->user_id);
            $this->db->update('member', $data); 

            $data = array(
                'status'    => 'Used',
                'used_by'   => $this->session->user_id,
                'used_time' => date('Y-m-d'),
            );
           
            $this->db->where('epin',$sign_package_epin);
            $this->db->update('epin', $data);


            $this->load->model('earning');
        
           if (config_item('fix_income') == "Yes" && $epin_value > 0 && config_item('give_income_on_topup') == "Yes") {
                $this->earning->fix_income($this->session->user_id, $this->db_model->select('sponsor', 'member', array('id' => $this->session->user_id)), $epin_value);

            } else if (config_item('fix_income') !== "Yes" && $epin_value > 0 && config_item('give_income_on_topup') == "Yes") {
          
                $this->earning->reg_earning($this->session->user_id, $this->db_model->select('sponsor', 'member', array('id' => $this->session->user_id)), $this->db_model->select('signup_package', 'member', array('id' => $this->session->user_id)));
            }

            /* First purchse earning on topup */
            var_dump(config_item('prevent_join_product_entry') == "Yes");
              if (config_item('prevent_join_product_entry') == "Yes") {
                        $data1 = array(
                            'product_id' => $signup_package,
                            'userid'     => $this->session->user_id,
                            'cost'       => $epin_value,
                            'date'       => date('Y-m-d'),
                        );
                        $this->db->insert('product_sale', $data1);
                    }
                else
                 {   $this->db->where('userid', $this->session->user_id);
                     $this->db->update('product_sale', $data1); 
                
                } 

                // PRODUCT WALLET TOPUP

                $result=$this->db_model->select_multi('userid', 'product_wallet', array('userid' =>$this->session->user_id));
  
                if ($result!=""){
                    $array1=array( 
                        'userid'=>$this->session->user_id,
                        'balance'=>$epin_value,
                        'type'=>'Bonus',
                        );
                    $this->db->where('userid', $this->session->user_id);
                    $this->db->update('product_wallet',$array1);

                }


            

            // /* For repurchase income on Topup */
            // $this->load->model('earning');
            // $this->earning->repurchase($orderid);
             

            
            $this->session->set_flashdata('common_flash', '<div class="alert alert-success">Successfully Top-uped your account.</div>');
            redirect(site_url('member')); 

        }else if($getFrmWallet ="Yes" && $package_cost->prod_price>0 && $getFrmWallet !=''){
         
           $this->load->model('earning');
      
           
           ### GET BOTH WALLET FUND FOR CUSTOM CALCULATION####
           $wallet_balance = $this->db_model->select('balance', 'wallet', array('userid' => $this->session->user_id));
           // $p_wallet_balance = $this->db_model->select('balance', 'product_wallet', array('userid' => $this->session->user_id));
           //$user_pv= $this->db_model->select('mypv', 'member', array('id' => $this->session->user_id));

           
            // PRODUCT WALLET TOPUP
            $result=$this->db_model->select_multi('userid', 'product_wallet', array('userid' =>$this->session->user_id));

                if ($result==""){
                    $array1=array( 
                        'userid'=>$this->session->user_id,
                        'balance'=>$epin_value,
                        'type'=>'Topup',
                        );
                    $this->db->insert('product_wallet',$array1);

                }

                ## Upgrade from Combined balance from both wallet ###
                //  $pack_price2 =$user_mypv-$package_cost->prod_price*0.0; ##0% debit from pv
                $pack_price3 = $wallet_balance - $package_cost->prod_price*1; ##100% debit remain cost from wallet
                
                if($pack_price3>=0 && $user_mypv >=0){
                   
                    $this->db->where(array('userid' => $this->session->user_id));
                    $this->db->update('wallet', array('balance' => $pack_price3));

                    $activation_date = date('Y-m-d H:i:s');
                 
                    $data1 = array( 
                        'signup_package'=>$signup_package,
                        'topup' => $package_cost->prod_price,
                        'mypv'=>$user_mypv+$package_cost->pv,
                        'activation_date' =>$activation_date,
                    ); 
                  

                    $this->db->where('id', $this->session->user_id);
                    $this->db->update('member', $data1); 


                    $data2 = array(
                            'product_id' => $signup_package,
                            'userid'     => $this->session->user_id,
                            'cost'       => $package_cost->prod_price,
                            'date'       => date('Y-m-d'),
                    );
                    
                    $this->db->insert('product_sale', $data2);
                   
                    if (config_item('fix_income') !== "Yes" && $user_mypv > 0 && config_item('give_income_on_topup') == "Yes") {
    
                        $this->earning->reg_earning($this->session->user_id, $this->db_model->select('sponsor', 'member', array('id' => $this->session->user_id)), $this->db_model->select('signup_package', 'member', array('id' => $this->session->user_id)));
                    }
                    $sp = $this->db_model->select('signup_package', 'member', array('id' => $this->session->user_id));
                    if ($sp >1 ) { 
                    $type = array(
                            'type' => 1,
                         );

                    $this->db->where('id', $this->session->user_id);
                    $this->db->update('member',$type); 
                    }
                   
                    $this->session->set_flashdata('common_flash', '<div class="alert alert-success">Successfully Top-uped your account.</div>');
                        redirect(site_url('member'));
                    }
                      $this->session->set_flashdata('common_flash', '<div class="alert alert-danger">
                        You dont have sufficient fund in wallet or points</div>');
                    redirect(site_url('member'));
                    
                    

                } else {
                    $this->session->set_flashdata('common_flash', '<div class="alert alert-danger">
                        E-Pin Invalid / Mismatch Product cost & ePin / Can not Downgrade</div>');
                    redirect(site_url('member'));
        }
    }

      public function my_invoices()
    {
        $config['base_url']   = site_url('member/my_invoices');
        $config['per_page']   = 50;
        $config['total_rows'] = $this->db_model->count_all('invoice', array(
            'userid'    => $this->session->fran_id,
            'user_type' => 'Franchisee',
        ));
        $page                 = ($this->uri->segment(3)) ? $this->uri->segment(3) : 0;
        $this->pagination->initialize($config);

         // $this->db->order_by('date','DESC'); 
         //    $this->db->group_by('orderid'); 
         //    $this->db->select_sum('total_amt');
         //    $this->db->select('*');
         //    $this->db->where('userid', $this->session->user_id);
         //    $data = $this->db->get('Invoices')->result();

        $this->db->from('invoice')->select('*')->where(array(
            'userid'    => $this->session->user_id,
            'user_type' => 'Member',
        ))->order_by('id', 'DESC')->group_by('orderid')->select_sum('total_amt')->select_sum('paid_amt')->limit($config['per_page'], $page);
        $data['invoice']    = $this->db->get()->result();
        $data['title']      = 'My Invoices';
        $data['breadcrumb'] = 'My Invoices';
        $data['layout']     = 'invoice/my_invoices.php';
        $this->load->view('member/index', $data);
    }

    public function invoice_view($orderid)
    {
        $this->db->from('invoice')->select('*')->where(array('id'=> $orderid));
        $data['result1']    = $this->db->get()->result();


        // $data['result'] = $this->db_model->select_multi('*', 'invoice', array('orderid' => $orderid));
        $this->load->view('member/invoice/print_invoice.php', $data);
    }

    public function deposite()
    {
        $this->form_validation->set_rules('amount', 'amount', 'trim|required');
         $this->form_validation->set_rules('tnumber', 'tnumber', 'trim|required');
        if ($this->form_validation->run() == FALSE)
         {
            
            $data['title']  = 'Fund Deposit Details';
            $data['layout'] = 'support/deposit.php';
            $this->load->view('member/index', $data);
        } 
        else 
        {
            if (trim($_FILES['receipt']['name'] !== ""))
             {
               $receipt='';
                 $this->load->library('upload');
               
                  if (!$this->upload->do_upload('recepit')) 
                  {
                        $this->session->set_flashdata('common_flash', '<div class="alert alert-danger">receipt not uploaded..<br/>' . $this->upload->display_errors() . '</div>');
                         redirect('member/deposite');
                    } 
                    else
                     {
                        $image_data = $this->upload->data();
                        $recepit = $image_data['file_name'];
                        unlink('uploads/'.$data['data']->recepit);
                    }
            }
            

                $array = array(
                     'userid'        => $this->session->user_id,
                    'amount' => $this->input->post('amount'),
                    'type' => $this->input->post('type'),
                    'tnumber' => $this->input->post('tnumber'),
                     'recepit' => $recepit,
                    'date'          => date('Y-m-d')
                   
                );
                // $this->db->where('userid', $this->session->user_id);
                $this->db->insert('deposite', $array);

            $this->session->set_flashdata('common_flash', '<div class="alert alert-success">Deposit request Added Successfully</div>');
             redirect('member/deposite');
         }

    }
     public function deposit_history()
        {
            $data['title']  = 'Deposit History';
            $data['layout'] = 'support/deposit_history.php';
            $this->load->view('member/index', $data);
        }

 
  public function remove_add_proof($userid){

        $data=array(
            'add_proof' =>'',
        );
        $this->db->where('userid', $userid);
        $this->db->update('member_profile', $data);


         redirect('member/proile');
    }

    public function remove_id_proof($userid){

        $data=array(
            'add_proof' =>'',
        );
        $this->db->where('userid', $userid);
        $this->db->update('member_profile', $data);


         redirect('member/proile');
    }
    
    public function remove_prof_pic($userid){

        $data=array(
            'prof_pic' =>'',
        );
        $this->db->where('userid', $userid);
        $this->db->update('member_profile', $data);


         redirect('member/proile');
    }
    
        public function setup_company()
    {
        $this->form_validation->set_rules('name', 'Name', 'trim|required');
        $this->form_validation->set_rules('address', 'address', 'trim|required');
        $this->form_validation->set_rules('email', 'Email', 'trim|required');
        if ($this->form_validation->run() == FALSE)
        {
            $data['title']  = 'Set Up Your Company';
            $data['layout'] = 'support/company.php'; 
            $this->load->view('member/index', $data);
        } 
        else 
        {
            $name      = $this->input->post('name');
            $address   = $this->input->post('address');
            $state   = $this->input->post('state');   
            $country   = $this->input->post('country');
            $email     = $this->input->post('email');
            $phone     = $this->input->post('phone');
            $username  =$this->input->post('username');
            $password      = str_rot13($username);

            if (trim($_FILES['img']['name'] !== "")) { 

                $this->load->library('upload');

                if (!$this->upload->do_upload('img')) {
                    $this->session->set_flashdata('common_flash', '<div class="alert alert-danger">Image not uploaded. Also select image.<br/>' . $this->upload->display_errors() . '</div>');
                    redirect('member/setup_company');
                } else {
                    $image_data               = $this->upload->data();
                    $config['image_library']  = 'gd2';
                    $config['source_image']   = $image_data['full_path']; //get original image
                    $config['maintain_ratio'] = TRUE;
                    $config['width']          = 600;
                    $config['height']         = 500;
                    $this->load->library('image_lib', $config);
                    $this->image_lib->resize();
                    $image = $image_data['file_name'];
                }
            }
          $signup_package = $this->db_model->select('signup_package', 'member', array('id' => $this->session->user_id));
            $data = array(
                'name'            => $name,
                'userid'          => $this->session->user_id,
                'address'        => $address,
                'state'          => $state,
                'country'        => $country,
                'email'           => $email,
                'phone'           => $phone,
                'username'        => $username, 
                'password'        => $password,
                //'registration_ip' => $this->input->ip_address(),
                'logo'            => $image,
                'type'             =>$signup_package,
               );

            $this->db->insert('franchisee', $data);
            $fran_id = $this->db->insert_id();
            $this->session->set_flashdata('common_flash', '<div class="alert alert-success">Franchisee Added successfully. Franchisee ID: <strong>' . $fran_id . '</strong>Franchisee Username: <strong>' . $username . '</strong>. Password is: <strong>' . $password . '</strong></div>');

            $sub = "Franchisee Login Detail";
            $msg = "Hellow " . $name . "<br/> Welcome to " . config_item('company_name') . "Just now you have successfully registered with us as a franchisee. Below is your login detail. <hr/>  <strong>User Name :</strong> " . $username . "<br/>
Franchisee ID: <strong>' . $fran_id . '</strong><br/>
        <strong>Password :</strong> " . $password . "
      <hr/><---<br/>Regards,<br/>www." . $_SERVER['HTTP_HOST'];
            $this->common_model->mail($email, $sub, $msg);
            redirect(site_url('member/setup_company'));
          }
    }

    
}

