<?php 

defined('BASEPATH') OR exit('No direct script access allowed');

class Test extends CI_Controller
{

    
    public function send_earning_msgs(){
        $cname   = config_item('company_name');
        $web     = $_SERVER['HTTP_HOST'];
        $this->db->select('userid, SUM(amount) as total_amount');
        $this->db->where('date', date('Y-m-d'));
        $this->db->group_by('userid');
        $query = $this->db->get('earning');
        $data  = $query->result();
        foreach($data as $tr){
            $phone   = $this->db_model->select('phone', 'member', array('id' => $tr->userid)); 
            $name    = $this->db_model->select('name', 'member', array('id' => $tr->userid)); 
            $amount  = $tr->total_amount;
            $message = "🏢 $cname\n\nDear $name,\nYour *payout of Rs. $amount.* Generated successfully !! \nVisit  $web";
            $this->common_model->send_message($phone, $message);
        }
    }

    public function email_test(){
        $this->load->library('email');
        $sub   = "Welcome to " . config_item('company_name');
        $pwd   = '123456';
        $email = 'akshay.axolotls@gmail.com';
        $web   = $_SERVER['HTTP_HOST'];
        $cname = config_item('company_name');
        $msg   = "
            <html>
            <head>
            <title>Thank you for registration</title>
            </head>
            <body>
            
            <p>Dear Akshay,</p>
            
            <p>Thank you for choosing  $cname. We hope you had good experience ! </p>
            
            <p>We always strive to keep improving the services we offer. Our highest priority is to ensure that these services meet your expectations.</p>
            
            <p>Your user account details as below !</p>
            
            <p><b>Username</b> :  1001</p>
            <p><b>Password</b> :  $pwd </p>
            <b>Website URL </b> : www.$web <br>
            
            <p>Thank you for your time !</p>
            
            <p>Best regards !</p>
            <b>$cname</b>
            
            </body>
            </html>
            ";
        $this->email->from('', $cname);
        $this->email->to($email);
        $this->email->subject($sub);
        $this->email->message($msg);
        var_dump($this->email->send());
    }

    public function msg_test(){
        $cname   = 'Axolotls';
        $name    = 'Sak';
        $user_id = '10001';
        $pwd     = '';
        $web     = 'www.com';
        $message = "🏢 $cname\n\nDear $name,\nThank you for becoming a distributor of $cname.\nYour member login details are as follows :\n\nUserid : $user_id\nPassword : $pwd\nVisit  $web \n\n*Please edit your profile and update your KYC its compulsary*";
        $user    = $this->common_model->send_message('7387070819', $message);
        var_dump($user);
    }


    public function gen_cron(){
        $pay_type   = $this->input->post('pay_type');
        $start_date = $this->input->post('sdate');
        $end_date   = $this->input->post('edate');
        $start_date = date('Y-m-d', strtotime(str_replace('/', '-', $start_date)));
        $end_date   = date('Y-m-d', strtotime(str_replace('/', '-', $end_date)));
        $this->db->select('userid, SUM(amount) as total_amount')->from('earning');
        $this->db->where('type', $pay_type);
        $this->db->where('status', 'Pending');
        $this->db->where('date >=', $start_date);
        $this->db->where('date <=', $end_date);
        $this->db->group_by('userid');
        $dataa      = $this->db->get()->result_array();
    
        foreach($dataa as $tr){
            $array = array(
                'status'       => 'Paid',
                'payout_date'  => date('Y-m-d'),
            );
            $this->db->where('userid', $tr['userid']);
            $this->db->where('type', $pay_type);
            $this->db->where('status', 'Pending');
            $this->db->where('date >=', $start_date);
            $this->db->where('date <=', $end_date);
            $this->db->update('earning', $array);
            $data = array(
                'userid' => $tr['userid'],
                'amount' => $tr['total_amount'],
                'date'   => date('Y-m-d'),
            );
            $this->db->insert('withdraw_request', $data);
            echo $tr['userid'].' = '.$tr['total_amount'].'<br>';
        }
        redirect('income/withdraws_list/Un-Paid');
    }
    
    public function monthly_pair_update(){
        $this->db->where('total_pairs >', 0);
        $members = $this->db->get('member')->result();
        foreach ($members as $member) {
            $this->db->select('userid, DATE(date) as date, status, SUM(DISTINCT pair_match) as total_pair_match');
            $this->db->from('earning');
            $this->db->where('userid', $member->id); 
            $this->db->where('type', 'Matching Income');  
            $this->db->where('MONTH(date)', date('m'));  
            $this->db->where('YEAR(date)', date('Y')); 
            $this->db->group_by(['userid', 'DATE(date)', 'status']); 
            $query    = $this->db->get();
            $result   = $query->result_array();
            $totalSum = 0;
            foreach ($result as $item) {
                $totalSum += (int)$item['total_pair_match'];  
            }
            $data = array('monthly_pairs' => $totalSum);
			$this->db->where('id', $member->id);
			$this->db->update('member', $data);
        }
    }
    
    public function wallet_update(){
        $this->db->select('*');
        $this->db->from('wallet');
        $this->db->where('balance >', 0);
        $data = $this->db->get()->result();
        foreach ($data as $tr) {
            $sum = $this->db_model->sum('amount','earning',array('userid' => $tr->userid));
            echo $tr->userid.'<br>';
            $array = array(
                'balance'  =>  $sum,
            );
            $this->db->where('userid', $tr->userid);
            $this->db->update('wallet', $array); 
        } 
    }
    
    public function clear_pack(){
        $this->db->select('*');
        $this->db->from('member');
        $this->db->where('topup >', 0);
        $data = $this->db->get()->result();
        foreach ($data as $tr) {
            $date     = new DateTime($tr->activation_date);
            $date->modify('+28 days');
            $today    = new DateTime();
            $interval = $today->diff($date);
            $remaining_days = $interval->invert ? 0 : $interval->days;
            if ($remaining_days <= 0) {
                $array = array(
                    're_topup'  =>  0,
                );
                $this->db->where('id', $tr->id);
                $this->db->update('member', $array); 
            }
        } 
    }

}

?>