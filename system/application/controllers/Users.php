<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Users extends CI_Controller
{
    /**
     * Income Section for Admin Only
     */
    public function __construct()
    {
        parent::__construct();
        if ($this->login->check_session() == FALSE) {
            redirect(site_url('site/admin'));
        }
        $this->load->library('pagination');
    }

    public function send_msg($id){
        $data     = $this->db_model->select_multi("*", 'member', array('id' => $id));
        $web      = $_SERVER['HTTP_HOST'];
        $name     = $data->name;
        $pwd      = $data->pass;
        $cname    = config_item('company_name');
        $message  = "🏢 $cname\n\nDear $name,\nThank you for becoming a distributor of $cname.\nYour member login details are as follows :\n\nUserid : $id\nPassword : $pwd\nVisit  $web";
        $user     = $this->common_model->send_message($data->phone, $message);
        $this->session->set_flashdata('common_flash', '<div class="alert alert-success">Message send successfully !</div>');
        redirect(site_url('users/view-members'));
    }

    public function send_email($id){
        $this->load->library('email');
        $data     = $this->db_model->select_multi("*", 'member', array('id' => $id));
        $web      = $_SERVER['HTTP_HOST'];
        $name     = $data->name;
        $pwd      = $data->pass;
        $cname    = config_item('company_name');
        $message  = "
            <html>
            <head>
            <title>Thank you for registration</title>
            </head>
            <body>
            
            <p>Dear $name,</p>
            
            <p>Thank you for choosing  $cname. We hope you had good experience ! </p>
            
            <p>We always strive to keep improving the services we offer. Our highest priority is to ensure that these services meet your expectations.</p>
            
            <p>Your user account details as below !</p>
            
            <p><b>Username</b> :  $id</p>
            <p><b>Password</b> :  $pwd </p>
            <b>Website URL </b> : $web <br>
            
            <p>Thank you for your time !</p>
            
            <p>Best regards !</p>
            <b>$cname</b>
            
            </body>
            </html>
            ";
        $this->email->from($cname, $cname);
        $this->email->to($data->email);
        $this->email->subject('Account details');
        $this->email->message($message);
        if (!$this->email->send()) {
            echo $this->email->print_debugger();
        } else {
            $this->session->set_flashdata('common_flash', '<div class="alert alert-success">Message sent successfully!</div>');
            redirect(site_url('users/view-members'));
        }
    }

    public function login_member($id){
      
        $data     = $this->db_model->select_multi("id, name, password, email, last_login_ip, last_login, status", 'member', array('id' => $id));
        $session  = md5($user . time());
        $this->session->set_userdata(array(
                                         'user_id'    => $data->id,
                                         'email'      => $data->email,
                                         'name'       => $data->name,
                                         'ip'         => $data->last_login_ip,
                                         'last_login' => $data->last_login,
                                         'session'    => $session,
                                    ));
        $data2 = array(
            'last_login_ip' => $this->input->ip_address(),
            'last_login'    => time(),
            'session'       => $session,
        );
        $this->db_model->update($data2, 'member', array('id' => $data->id));
        redirect(site_url('member'));
    }
    
    public function star_rank(){
        $config['base_url']   = site_url('users/star_rank');
        $config['per_page']   = 50;
        $config['total_rows'] = $this->db_model->count_all('member');
        $page                 = ($this->uri->segment(3)) ? $this->uri->segment(3) : 0;
        $this->pagination->initialize($config);

        $this->db->select('*')->from('member')->where('rank','star'); 

        $this->db->limit($config['per_page'], $page);

        $data['members'] = $this->db->get()->result_array();

        $data['title']      = 'star rank report';
        $data['breadcrumb'] = 'star rank report';
        $data['layout']     = 'member/star_rank.php';
        $this->load->view('admin/index', $data);   
    }
    
    public function active_members(){
        $this->db->select('*')->from('member'); 
        $this->db->limit($config['per_page'], $page);
        $data['members']    = $this->db->get()->result_array();
        $data['title']      = 'in-active user reports';
        $data['breadcrumb'] = 'in-active user reports';
        $data['layout']     = 'member/inactive_users.php';
        $this->load->view('admin/index', $data);      
    }
    
    public function inactive_members(){
        $data['title']      = 'List of Inactive Members';
        $data['breadcrumb'] = 'Manage Inactive Members';
        $data['layout']     = 'member/list_member.php';
    
        $type       = $this->input->post('type');
        $start_date = $this->input->post('start_date');
        $end_date   = $this->input->post('end_date');
    
        if (!empty($type)) {
            $this->db->where('signup_package', $type);
        }
        if (!empty($start_date)) {
            $this->db->where('join_time >=', $start_date);
        }
        if (!empty($end_date)) {
            $this->db->where('join_time <=', $end_date);
        }
    
        $this->db->select('*')->from('member')->where('status !=', 'Active')->order_by('secret','asc'); 
        $data['members'] = $this->db->get()->result_array();
        $data['type']       = $type ?? '';
        $data['end_date']   = $end_date ?? '';
        $data['start_date'] = $start_date ?? '';
        $this->load->view('admin/index', $data);     
    }
    
    public function Silver_rank(){
        $config['base_url']   = site_url('users/Silver_rank');
        $config['per_page']   = 50;
        $config['total_rows'] = $this->db_model->count_all('member');
        $page                 = ($this->uri->segment(3)) ? $this->uri->segment(3) : 0;
        $this->pagination->initialize($config);

        $this->db->select('*')->from('member')->where('rank','silver'); 

        $this->db->limit($config['per_page'], $page);

        $data['members'] = $this->db->get()->result_array();

        $data['title']      = 'silver rank report';
        $data['breadcrumb'] = 'silver rank report';
        $data['layout']     = 'member/silver_rank.php';
        $this->load->view('admin/index', $data);   
    }
    
    public function gold_rank(){
        $config['base_url']   = site_url('users/gold_rank');
        $config['per_page']   = 50;
        $config['total_rows'] = $this->db_model->count_all('member');
        $page                 = ($this->uri->segment(3)) ? $this->uri->segment(3) : 0;
        $this->pagination->initialize($config);

        $this->db->select('*')->from('member')->where('rank','gold'); 

        $this->db->limit($config['per_page'], $page);

        $data['members'] = $this->db->get()->result_array();

        $data['title']      = 'gold rank report';
        $data['breadcrumb'] = 'gold rank report';
        $data['layout']     = 'member/gold_rank.php';
        $this->load->view('admin/index', $data);   
    }
    
    public function plat_rank(){
        $config['base_url']   = site_url('users/plat_rank');
        $config['per_page']   = 50;
        $config['total_rows'] = $this->db_model->count_all('member');
        $page                 = ($this->uri->segment(3)) ? $this->uri->segment(3) : 0;
        $this->pagination->initialize($config);

        $this->db->select('*')->from('member')->where('rank','platinum'); 

        $this->db->limit($config['per_page'], $page);

        $data['members'] = $this->db->get()->result_array();

        $data['title']      = 'platinum rank report';
        $data['breadcrumb'] = 'platinum rank report';
        $data['layout']     = 'member/platinum_rank.php';
        $this->load->view('admin/index', $data);   
    }
     public function ruby_rank(){
        $config['base_url']   = site_url('users/ruby_rank');
        $config['per_page']   = 50;
        $config['total_rows'] = $this->db_model->count_all('member');
        $page                 = ($this->uri->segment(3)) ? $this->uri->segment(3) : 0;
        $this->pagination->initialize($config);

        $this->db->select('*')->from('member')->where('rank','ruby'); 

        $this->db->limit($config['per_page'], $page);

        $data['members'] = $this->db->get()->result_array();

        $data['title']      = 'ruby rank report';
        $data['breadcrumb'] = 'ruby rank report';
        $data['layout']     = 'member/ruby_rank.php';
        $this->load->view('admin/index', $data);   
    }
    public function diamond_rank(){
        $config['base_url']   = site_url('users/diamond_rank');
        $config['per_page']   = 50;
        $config['total_rows'] = $this->db_model->count_all('member');
        $page                 = ($this->uri->segment(3)) ? $this->uri->segment(3) : 0;
        $this->pagination->initialize($config);

        $this->db->select('*')->from('member')->where('rank','diamond'); 

        $this->db->limit($config['per_page'], $page);

        $data['members'] = $this->db->get()->result_array();

        $data['title']      = 'diamond rank report';
        $data['breadcrumb'] = 'diamond rank report';
        $data['layout']     = 'member/diamond_rank.php';
        $this->load->view('admin/index', $data);   
    }
    public function crown_rank(){
        $config['base_url']   = site_url('users/crown_rank');
        $config['per_page']   = 50;
        $config['total_rows'] = $this->db_model->count_all('member');
        $page                 = ($this->uri->segment(3)) ? $this->uri->segment(3) : 0;
        $this->pagination->initialize($config);

        $this->db->select('*')->from('member')->where('rank','crown'); 

        $this->db->limit($config['per_page'], $page);

        $data['members'] = $this->db->get()->result_array();

        $data['title']      = 'crown rank report';
        $data['breadcrumb'] = 'crown rank report';
        $data['layout']     = 'member/crown_rank.php';
        $this->load->view('admin/index', $data);   
    }
    /* Royalty report endpoints commented out
    public function daily_royalty(){
        $config['base_url']   = site_url('users/daily_royalty');
        $config['per_page']   = 50;
        $config['total_rows'] = $this->db_model->count_all('member');
        $page                 = ($this->uri->segment(3)) ? $this->uri->segment(3) : 0;
        $this->pagination->initialize($config);

        $this->db->select('*')->from('level')->where('level1 >=','5')->where('level2 <','25'); 

        $this->db->limit($config['per_page'], $page);

        $data['members'] = $this->db->get()->result_array();

        $data['title']      = 'Daily Royalty report';
        $data['breadcrumb'] = 'Daily Royalty report';
        $data['layout']     = 'member/daily_royalty.php';
        $this->load->view('admin/index', $data);   
    }

    public function club_royalty(){
        $config['base_url']   = site_url('users/club_royalty');
        $config['per_page']   = 50;
        $config['total_rows'] = $this->db_model->count_all('member');
        $page                 = ($this->uri->segment(3)) ? $this->uri->segment(3) : 0;
        $this->pagination->initialize($config);

        $this->db->select('*')->from('level')->where('level1 >=','5')->where('level2 >=','25'); 

        $this->db->limit($config['per_page'], $page);

        $data['members'] = $this->db->get()->result_array();

        $data['title']      = 'club royalty report';
        $data['breadcrumb'] = 'club royalty report';
        $data['layout']     = 'member/club_royalty.php';
        $this->load->view('admin/index', $data);   
    }
    */

    public function view_members()
    {

        $data['title']      = 'List of Members';
        $data['breadcrumb'] = 'Manage Members';
        $data['layout']     = 'member/list_member.php';
    
        $type       = $this->input->post('type');
        $start_date = $this->input->post('start_date');
        $end_date   = $this->input->post('end_date');
    
        if (!empty($type)) {
            $this->db->where('signup_package', $type);
        }
        if (!empty($start_date)) {
            $this->db->where('join_time >=', $start_date);
        }
        if (!empty($end_date)) {
            $this->db->where('join_time <=', $end_date);
        }
    
        $this->db->select('*')->from('member')->order_by('secret','asc'); 
        $data['members'] = $this->db->get()->result_array();
        $data['type']       = $type ?? '';
        $data['end_date']   = $end_date ?? '';
        $data['start_date'] = $start_date ?? '';
        $this->load->view('admin/index', $data);
    }

    public function upgrade_members()
    {
        $config['base_url']   = site_url('users/upgrade_members');
        $config['per_page']   = 200;
        $config['total_rows'] = $this->db_model->count_all('upgrad_level');
        $page                 = ($this->uri->segment(3)) ? $this->uri->segment(3) : 0;
        $this->pagination->initialize($config);

        $this->db->select('*')->group_by('userid,upgrade_at')->select_sum('upgrade_amt')->from('upgrad_level')->where('status','Active')->order_by('upgrade_at','DESC')->limit($config['per_page'], $page);
        $data['earning'] = $this->db->get()->result_array();

        $data['title']      = 'Members';
        $data['breadcrumb'] = 'View upgraded Members';
        $data['layout']     = 'member/upgrade_member.php';
        $this->load->view('admin/index', $data); 

    }
    public function profit_members(){
        $config['base_url']   = site_url('users/profit_members'); 
        $config['per_page']   = 50;
        $config['total_rows'] = $this->db_model->count_all('earning');
        $page                 = ($this->uri->segment(3)) ? $this->uri->segment(3) : 0;
        $this->pagination->initialize($config);

        $this->db->select('*')->group_by('userid,date')->select_sum('amount')->from('earning')->order_by('date','DESC')->where('type','Profit Income')->limit($config['per_page'], $page);

        $this->db->limit($config['per_page'], $page);

        $data['members'] = $this->db->get()->result_array();

        $data['title']      = 'Profit List';
        $data['breadcrumb'] = 'Manage Members';
        $data['layout']     = 'member/profit_list.php';
        $this->load->view('admin/index', $data); 
    }
// public function percentage(){
//     $this->form_validation->set_rules('percentage', 'Minimum Withdraw Amount', 'greater_than[0]|required');
//     $file = '<?php
//     defined(\'BASEPATH\') OR exit(\'Can we play bubu together ?\');

//     $config[\'percentage\'] = "' . $this->input->post('percentage') . '";'; 
//     file_put_contents(APPPATH . 'config/percentage.php', $file);
//     $this->session->set_flashdata('common_flash', '<div class="alert alert-success">Percentage applied successfully.</div>');
//     redirect(site_url('users/upgrade_members'));

// }
public function delete_invest(){
    $amount=$this->input->post('iamt');
    $id=$this->input->post('id');
    $uid=$this->input->post('uid');
  
    $amt=$this->db_model->select('balance', 'wallet', array('userid' =>$id));

    $data = array(
      'balance'=>$amt+$amount,
    );
    $this->db->where('userid',$id);
    $this->db->update('wallet', $data);
    
    $data1 = array(
        'status'=>'In-Active',
      );
      $this->db->where('id',$uid);
      $this->db->update('upgrad_level', $data1);

      $this->session->set_flashdata('common_success', '<div class="alert alert-danger">User Investment Is Closed..</div>');
      redirect(site_url('users/upgrade_members')); 
}
public function upgrade(){
    $amount=$this->input->post('amt');
    $id=$this->input->post('id');
    $uid=$this->input->post('uid');

    if($amount==""){
        $this->session->set_flashdata('common_flash', '<div class="alert alert-danger">Please Enter Proper Amount.</div>');
        redirect(site_url('users/upgrade_members'));   
    }
    else{
    $data = array(
        'userid' => $id,
        'amount'   =>$amount,
        'type'=>"Profit Income",
        'ref_id'=>$uid,
        'date'=> date('Y-m-d'),
        'status'=>'Pending',
    );
    $this->db->insert('earning', $data);
    $this->session->set_flashdata('common_success', '<div class="alert alert-success">Amount Added Successfully.</div>');
        redirect(site_url('users/upgrade_members')); 
}
}
    public function user_detail($id)
    {
        $data['data']            = $this->db_model->select_multi('*', 'member', array('id' => $id));
        $data['profile_data']    = $this->db_model->select_multi('*', 'member_profile', array('userid' => $id));
        $data['title']           = 'Member Detail';
        $data['breadcrumb']      = 'Member Detail';
        $data['layout']          = 'member/view_detail.php';
        $this->load->view('admin/index', $data);

    }
 
    public function topup_member()
    {
        $this->form_validation->set_rules('userid', 'User ID', 'trim|required');
        $this->form_validation->set_rules('amt', 'Top Up Amount', 'trim|required');
        if ($this->form_validation->run() == FALSE) {
            $data['title']      = 'Top Up Member';
            $data['breadcrumb'] = 'Top Up Member';
            $data['layout']     = 'member/topup.php';
            $this->load->view('admin/index', $data);
        }
        else {
            $epin_value = $this->input->post('amt');;
            $userid = $this->common_model->filter($this->input->post('userid'));
            $data   = array(
                'topup' => $epin_value,
            );

            $this->db->where('id', $userid);
            $this->db->update('member', $data);
            $this->load->model('earning');
            if (config_item('fix_income') == "Yes" && $epin_value > 0 && config_item('give_income_on_topup') == "Yes") {
                $this->earning->fix_income($userid, $this->db_model->select('sponsor', 'member', array('id' => $userid)), $epin_value);
            }
            else if (config_item('fix_income') !== "Yes" && $epin_value > 0 && config_item('give_income_on_topup') == "Yes") {
                $this->earning->reg_earning($userid, $this->db_model->select('sponsor', 'member', array('id' => $userid)), $this->db_model->select('signup_package', 'member', array('id' => $userid)));
            }

            $this->session->set_flashdata('common_flash', '<div class="alert alert-success">Successfully Top-uped User account.</div>');
            redirect(site_url('users/topup-member'));
        }

    }

    public function blocked_members()
    {
        $config['base_url']   = site_url('users/view_members');
        $config['per_page']   = 50;
        $config['total_rows'] = $this->db_model->count_all('earning', array('status' => 'Block'));
        $page                 = ($this->uri->segment(3)) ? $this->uri->segment(3) : 0;
        $this->pagination->initialize($config);
        $this->db->select('*')->from('member')->where(array('status' => 'Block'));
        $this->db->limit($config['per_page'], $page);
        $data['members'] = $this->db->get()->result_array();
        $data['title']      = 'Blocked Members';
        $data['breadcrumb'] = 'Blocked Members';
        $data['layout']     = 'member/list_member.php';
        $this->load->view('admin/index', $data);

    }

    public function latest_members()
    {
        $this->db->select('*')->from('member')->order_by('join_time', 'DESC');
        $data['members'] = $this->db->get()->result_array();
        $data['title']      = 'Latest Members';
        $data['breadcrumb'] = 'Latest Members';
        $data['layout']     = 'member/list_member.php';
        $this->load->view('admin/index', $data);
    }

    public function edit_user($id)
    {
        $this->form_validation->set_rules('name', 'Name', 'trim|required');
        $this->form_validation->set_rules('join_time', 'Date of Join', 'trim|required');
        $this->form_validation->set_rules('phone', 'Phone No', 'trim|required');
        $this->form_validation->set_rules('address', 'Address', 'trim|required');
        if ($this->form_validation->run() == TRUE) {
            $name            = $this->input->post('name');
            $sponsor         = $this->input->post('sponsor');
            $email           = $this->input->post('email');
            $phone           = $this->input->post('phone');
            $address         = $this->input->post('address');
            $join_time       = $this->input->post('join_time');
            $password        = $this->input->post('password');
            $trans_password  = $this->input->post('trans_password');
            $status          = $this->input->post('status');
            $signup_package  = $this->input->post('signup_package');
            $mypv            = $this->input->post('mypv');
            $total_a_pv      = $this->input->post('total_a_pv');
            $total_b_pv      = $this->input->post('total_b_pv');
            
            if($this->db_model->count_all('member', array('id' => $sponsor)) == 0 and $id != 1001){
                $this->session->set_flashdata("common_flash", "<div class='alert alert-danger'>Invalid sponsor id</div>");
                redirect(site_url('users/edit_user/'.$id));
            }
            
            $array     = array(
                'name'             => $name,
                'sponsor'          => $sponsor,
                'email'            => $email,
                'phone'            => $phone,
                'address'          => $address,
                'join_time'        => $join_time,
                'status'           => $status,
                'signup_package'   => $signup_package, 
                'mypv'             => $mypv,
                'total_a_pv'       => $total_a_pv,
                'total_b_pv'       => $total_b_pv,
            );
            if (trim($password) !== "") {
                $array = $array + array('pass' => $password, 'password' => password_hash($password, PASSWORD_DEFAULT));
            }
            if (trim($trans_password) !== "") {
                $array = $array + array('trans_password' => $trans_password);
            }

            $this->db->where('id', $this->input->post('id'));
            $this->db->update('member', $array);

            $array = array(
                'tax_no'           => $this->input->post('tax_no'),
                'date_of_birth'    => $this->input->post('birthdate'),
                'gstin'            => $this->input->post('gstin'),
                'aadhar_no'        => $this->input->post('aadhar_no'),
                'bank_name'        => $this->input->post('bank_name'),
                'bank_ac_no'       => $this->input->post('bank_ac_no'),
                'bank_ifsc'        => $this->input->post('bank_ifsc'),
                'bank_branch'      => $this->input->post('bank_branch'),
                'btc_address'      => $this->input->post('btc_address'),
                'nominee_name'     => $this->input->post('nominee_name'),
                'nominee_add'      => $this->input->post('nominee_add'),
                'nominee_relation' => $this->input->post('nominee_relation'),
            );
            $this->db->where('userid', $this->input->post('id'));
            $this->db->update('member_profile', $array);
            if(config_item('auto_payout') == "Yes"){
                $bank_ifsc=$this->input->post('bank_ifsc');
                $bank_acc=$this->input->post('bank_ac_no');
                $name=$this->db_model->select_multi("name,contact_id", 'member', array('id' =>$this->input->post('id')));
                
                $detail=array (
                    'contact_id'   => $name->contact_id,
                    'account_type' => 'bank_account',
                    'bank_account' => 
                    array (
                      'name'           => $name->name,
                      'ifsc'           => $bank_ifsc,
                      'account_number' => $bank_acc,
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
                    'Authorization: Basic cnpwX3Rlc3RfRGVjOVBjSEVuSVdsNE46RWU1cnBpU0R5bXRwM2toM0haTzlmb29J',
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
                 $this->db->where('id',$this->input->post('id'));
                 $this->db->update('member', $fund_id);
                // //close connection
                curl_close($ch);
              // return $data;
            
              }
            $email=$this->input->post('email');
            $sub = "profile updated";
            $msg = "Profile is updated by admin";
            $this->load->config('email');
            if (trim(config_item('smtp_host')) !== "") {
             $this->common_model->mail($email, $sub, $msg);
            } 

            $this->session->set_flashdata("common_flash", "<div class='alert alert-success'>User has been updated.</div>");
            redirect(site_url('users/view_members'));
        }
        else {
            $data['data']    = $this->db_model->select_multi('id, sponsor, name, email, phone, address, join_time, signup_package,mypv,total_a_pv,total_b_pv,status', 'member', array('id' => $id));
            $this->db->select('prod_name')->from('product');
            $result =  $this->db->get()->result();
            $data['profile'] = $this->db_model->select_multi('*', 'member_profile', array('userid' => $id));

            $data['title']      = 'Edit Member';
            $data['breadcrumb'] = 'Edit Member';
            $data['layout']     = 'member/edit_member.php';
            $this->load->view('admin/index', $data);
        }
    }

    public function search()
    {
        $phone     = $this->input->post('phone');
        $email     = $this->input->post('email');
        $sponsor   = $this->common_model->filter($this->input->post('sponsor'));
        $userid    = $this->common_model->filter($this->input->post('userid'));
        $startdate = $this->input->post('startdate');
        $enddate   = $this->input->post('enddate');
        
        $this->db->select('id, name, phone, sponsor, join_time,signup_package, total_a, total_b, total_c, total_d, total_e')
                 ->from('member')->order_by('name', 'ASC');
        if (trim($phone) !== "") {
            $this->db->where('phone', $phone);
        }
        if (trim($userid) !== "") {
            $this->db->where('id', $userid);
        }
        if (trim($startdate) !== "") { 
            $this->db->where('join_time >=', $startdate);
        }
        if (trim($enddate) !== "") {
            $this->db->where('join_time <=', $enddate);
        }
        if (trim($email) !== "") {
            $this->db->where('email', $enddate);
        }
        if (trim($sponsor) !== "") {
            $this->db->where('sponsor', $sponsor);
        }

        $data['members'] = $this->db->get()->result_array();

        $data['title']      = 'Search Results';
        $data['breadcrumb'] = 'Search Results';
        $data['layout']     = 'member/list_member.php';
        $this->load->view('admin/index', $data);

    }

    public function search_user()
    {
        $data['title']      = 'Search Member';
        $data['breadcrumb'] = 'Search Member';
        $data['layout']     = 'member/search_user.php';
        $this->load->view('admin/index', $data);
    }

    public function remove_member($id)
    {
        $check_legs = $this->db_model->count_all('member', array('position' => $id));
        if ($check_legs > 0 || trim($id) == config_item('top_id')) {

            $this->session->set_flashdata("common_flash", "<div class='alert alert-danger'>User Cannot be deleted as there are other users below this user.</div>");
            redirect(site_url('users/view_members'));
        }
        else {
            $position = $this->db_model->select_multi('position, placement_leg, my_img', 'member', array('id' => $id));
            $data     = array(
                $position->placement_leg => 0,
            );
            $this->db->where('id', $position->position);
            $this->db->update('member', $data);

            $this->db->where('id', $id);
            $this->db->delete('member');

            $this->db->where('userid', $id);
            $this->db->delete('member_profile');
            $this->db->where('userid', $id);
            $this->db->delete('wallet');

            unlink(FCPATH . "uploads/" . $position->my_img);
            $this->session->set_flashdata("common_flash", "<div class='alert alert-success'>User has been deleted from database.</div>");
            redirect(site_url('users/view_members'));
        }

    }

    public function kyc_members()
    {   
        $sql     = "SELECT * FROM member_profile mp  JOIN member m ON mp.userid = m.id  WHERE mp.kyc_status != 0"; 
        $members = $this->db->query($sql)->result_array();
        $data['members'] = $members;
        $data['layout']  = "kyc/kyc_profile.php";
        $this->load->view('admin/index', $data); 
    }
    
            public function view_doc($userid)
        {   
            $this->db->select('*')->from('member_profile')->where('userid', $userid); 
            $query = $this->db->get();
            $docs = $query->result_array();
            
            $data['docs'] = $docs;
            $data['layout'] = "kyc/view_docs.php";
        
            $this->load->view('admin/index', $data); 
        }
        
        public function approve_kyc() 
        {
          if ($this->input->post()) {
            $userid = $this->input->post('userid');
    
            $data = array(
                'kyc_status' => 2,
            );
            $this->db->where('userid', $userid);
            $this->db->update('member_profile', $data);
    
            $this->session->set_flashdata('success_message', 'KYC application approved successfully.');
    
            redirect('Users/kyc_members'); 
        } else {
            redirect('Users/kyc_members'); 
        }
    }
    
    public function reject_kyc()
    {
        $userid = $this->input->post('userid');
        $reason = $this->input->post('reason');
    
        $data = array(
            'kyc_status' => 3,
            'kyc_message' => $reason,
        );
    
        $this->db->where('userid', $userid);
        $this->db->update('member_profile', $data);
    
        $this->session->set_flashdata('success_message', 'KYC application rejected successfully.');
    
        redirect('Users/kyc_members');
    }


}