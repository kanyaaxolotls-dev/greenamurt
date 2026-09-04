<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Income extends CI_Controller
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

    public function fran_payout($type = 'All')
    {
        if ($type == 'All') {
            $this->db->select('*');
        } else {
            $this->db->select('*')->where('status', $type);
        }
        $this->db->from('fran_withdraw_request');
        $this->db->order_by('id', 'DESC');
    
        $query = $this->db->get();
        if (!$query) {
            $error = $this->db->error();
            echo 'Database error: ' . $error['message'];
            return;
        }
    
        $data['data']       = $query->result();
        $data['title']      = 'Franchisee Payout Requests';
        $data['breadcrumb'] = $type . ' Payments';
        $data['layout']     = 'franchisee/payout.php';
        $this->load->view('admin/index', $data);
    }
    
    public function update_payout_new()
    {
        redirect('cron/newcron2');
    }

    public function fran_pay() 
    {
        $payid    = $this->input->post('payid');
        $tdetail  = $this->input->post('tdetail');

        $data = array(
            'status'    => 'Paid',
            'paid_date' => date('Y-m-d'),
            'tid'       => $tdetail
        );
        $this->db->where('id', $payid);
        $this->db->update('fran_withdraw_request', $data);

        $this->session->set_flashdata('common_flash', '<div class="alert alert-success">Marked as Paid successfully.</div>');
        redirect('income/fran_payout');
    }
    
    public function fran_hold($id)
    {
        $data = array(
            'status' => 'Hold',
        );
        $this->db->where('id', $id);
        $this->db->update('fran_withdraw_request', $data);
        $this->session->set_flashdata('common_flash', '<div class="alert alert-success">Hold the payment  successfully.</div>');
        redirect('income/fran_payout');
    }

    public function fran_unhold($id)
    {
        $data = array(
            'status' => 'Un-Paid',
        );
        $this->db->where('id', $id);
        $this->db->update('fran_withdraw_request', $data);
        $this->session->set_flashdata('common_flash', '<div class="alert alert-success">Un-Hold the payment  successfully.</div>');
        redirect('income/fran_payout');
    }

    public function process_payouts()
    {
        $selected_ids = $this->input->post('selected_ids');
        $status       = $this->input->post('status');
        #echo "<pre>";print_r($_POST);die();
        if ($selected_ids) {
            $id_array = explode(',', $selected_ids);
            foreach ($id_array as $id) {
                $amount  = $this->db_model->select_multi('userid,amount', 'withdraw_request', array('id' => $id));
                $total_deduction_rate = (float)config_item('admin_charges') + (float)config_item('payout_tax');
                $data = array(
                    'status'    => $status,
                    'paid_date' => date('Y-m-d'),
                    'tid'       => '',
                    'tax'       => ($amount->amount * $total_deduction_rate / 100),
                );
                $this->db->where('id', $id);
                $result = $this->db->update('withdraw_request', $data);
        
                if($status == 'Paid'){
                #if($result && $this->db->affected_rows() > 0){
                    $data = array(
                        'userid'     => $amount->userid,
                        'amount'     => $amount->amount,
                        'payout_id'  => $id,
                        'tax_amount' => ($amount->amount * config_item('payout_tax') / 100),
                        'tax_percnt' => config_item('payout_tax'),
                        'date'       => date('Y-m-d'),
                    );
                    $this->db->insert('tax_report', $data);
                }
            }
            $this->session->set_flashdata('common_flash', '<div class="alert alert-success">Selected payouts processed successfully.</div>');
        } else {
            $this->session->set_flashdata('common_flash', '<div class="alert alert-success">No payouts selected.</div>');
        }
        redirect('income/withdraws_list/'.$status);
    }

    public function withdraws_list($type = 'All')
    {
        $from_date = trim($this->input->post('from_date') ?? '');
        $to_date   = trim($this->input->post('to_date') ?? '');

        $this->db->select('*');
        if ($type != 'All' && !empty($type)) {
            $this->db->where('status', $type);
        }
        if (!empty($from_date)) {
            $this->db->where('date >=', $from_date);
        }
        if (!empty($to_date)) {
            $this->db->where('date <=', $to_date);
        }
        $this->db->from('withdraw_request');
        $this->db->order_by('id', 'DESC');
    
        $query = $this->db->get();
        if (!$query) {
            $error = $this->db->error();
            echo 'Database error: ' . $error['message'];
            return;
        }
    
        $data['data']       = $query->result();
        $data['title']      = 'Payout Requests';
        $data['breadcrumb'] = $type . ' Payments';
        $data['typee']      = $type;
        $data['layout']     = 'income/makepayment.php';
        $this->load->view('admin/index', $data);
    }

    
    public function pay_cycle() {
        $pay_type   = $this->input->post('pay_type') ?? NULL;
        $start_date = $this->input->post('sdate') ?? NULL;
        $end_date   = $this->input->post('edate') ?? NULL;
        if ($pay_type != NULL && $start_date != NULL) {
            $start_date = date('Y-m-d', strtotime(str_replace('/', '-', $start_date)));
            $end_date   = date('Y-m-d', strtotime(str_replace('/', '-', $end_date)));
            // echo $start_date.'<br>'.$end_date;die();
            $this->db->select('*')->from('earning');
            $this->db->where('type', $pay_type);
            $this->db->where('status', 'Pending');
            $this->db->where('date >=', $start_date);
            $this->db->where('date <=', $end_date);
            $dataa = $this->db->get()->result_array();
        }
        else{
            $dataa    = array();
        }
        $data['title']   = 'Generate payouts';
        $data['type']    = $pay_type;
        $data['sdate']   = $start_date;
        $data['edate']   = $end_date;
        $data['data']    = $dataa;
        $data['layout']  = 'income/ak_payouts.php';
        $this->load->view('admin/index', $data);
    }
    
    public function autopool_one(){
        $config['base_url']   = site_url('income/autopool_one');
        $config['per_page']   = 100;
        $config['total_rows'] = $this->db_model->count_all('earning');
        $page                 = ($this->uri->segment(3)) ? $this->uri->segment(3) : 0;
        $this->pagination->initialize($config);

        $this->db->select('*')->from('pool_one')
                 ->limit($config['per_page'], $page);

        $data['earning'] = $this->db->get()->result_array();

        $data['title']      = 'matrix 1 Member';
        $data['breadcrumb'] = 'matrix 1 Member';
        $data['layout']     = 'income/autopool_one.php';
        $this->load->view('admin/index', $data);
    }
    public function pay_earning()
{
    $this->db->select('id, userid, amount, type, ref_id, date, pair_match,levlno')->from('earning')->where('type','sponsor inc')->order_by('date', 'DESC')
    ->limit($config['per_page'], $page);
    $data['earning'] = $this->db->get()->result_array();
    $data['title']      = 'Make Payment';
    $data['breadcrumb'] = 'Withdrawl List';
    $data['layout']     = 'income/pay_earn.php';
    $this->load->view('admin/index', $data);
}
public function pay_post_earning()//in process
{
    $income_name   = $this->input->post('income_name');
    $amount = $this->input->post('amount');
    $userid   = $this->input->post('sponsor');
    $cust_name = $this->input->post('cust_name');
    $trans_id=rand(1000,999999);
 
    $this->load->model('earning');
    $this->earning->pay_earning($userid, $trans_id, $income_name,$amount,0,0,0);
    $this->session->set_flashdata('common_flash', '<div class="alert alert-success">Payout successfully added..</div>');
    redirect('income/pay_earning');
}

    public function autopool_two(){
        $config['base_url']   = site_url('income/autopool_two');
        $config['per_page']   = 100;
        $config['total_rows'] = $this->db_model->count_all('earning');
        $page                 = ($this->uri->segment(3)) ? $this->uri->segment(3) : 0;
        $this->pagination->initialize($config);

        $this->db->select('*')->from('pool_two')
                 ->limit($config['per_page'], $page);

        $data['earning'] = $this->db->get()->result_array();

        $data['title']      = 'B-Zone Member';
        $data['breadcrumb'] = 'B-Zone Member';
        $data['layout']     = 'income/autopool_two.php';
        $this->load->view('admin/index', $data);

    }
    public function autopool_three(){
        $config['base_url']   = site_url('income/autopool_three');
        $config['per_page']   = 100;
        $config['total_rows'] = $this->db_model->count_all('earning');
        $page                 = ($this->uri->segment(3)) ? $this->uri->segment(3) : 0;
        $this->pagination->initialize($config);

        $this->db->select('*')->from('pool_three')
                 ->limit($config['per_page'], $page);

        $data['earning'] = $this->db->get()->result_array();

        $data['title']      = 'C-Zone Member';
        $data['breadcrumb'] = 'C-Zone Member';
        $data['layout']     = 'income/autopool_three.php';
        $this->load->view('admin/index', $data);


    }
    public function sponsor_income(){
    $config['base_url']   = site_url('income/sponsor_income');
    $config['per_page']   = 100;
    $config['total_rows'] = $this->db_model->count_all('earning');
    $page                 = ($this->uri->segment(3)) ? $this->uri->segment(3) : 0;
    $this->pagination->initialize($config);

    $this->db->select('*')->from('member')
             ->limit($config['per_page'], $page);

    $data['earning'] = $this->db->get()->result_array();

    $data['title']      = 'sponsor income';
    $data['breadcrumb'] = 'sponsor income';
    $data['layout']     = 'income/sponsor_income.php';
    $this->load->view('admin/index', $data);   
}

public function autopool_four(){
        $config['base_url']   = site_url('income/autopool_four');
        $config['per_page']   = 100;
        $config['total_rows'] = $this->db_model->count_all('earning');
        $page                 = ($this->uri->segment(3)) ? $this->uri->segment(3) : 0;
        $this->pagination->initialize($config);

        $this->db->select('*')->from('pool_four')
                 ->limit($config['per_page'], $page);

        $data['earning'] = $this->db->get()->result_array();

        $data['title']      = 'D-Zone Member';
        $data['breadcrumb'] = 'D-Zone Member';
        $data['layout']     = 'income/autopool_four.php';
        $this->load->view('admin/index', $data);


    }

    public function autopool_five(){
        $config['base_url']   = site_url('income/autopool_five');
        $config['per_page']   = 100;
        $config['total_rows'] = $this->db_model->count_all('earning');
        $page                 = ($this->uri->segment(3)) ? $this->uri->segment(3) : 0;
        $this->pagination->initialize($config);

        $this->db->select('*')->from('pool_five')
                 ->limit($config['per_page'], $page);

        $data['earning'] = $this->db->get()->result_array();

        $data['title']      = 'E-Zone Member';
        $data['breadcrumb'] = 'E-Zone Member';
        $data['layout']     = 'income/autopool_five.php';
        $this->load->view('admin/index', $data);

    }

    public function autopool_six(){
        $config['base_url']   = site_url('income/autopool_six');
        $config['per_page']   = 100;
        $config['total_rows'] = $this->db_model->count_all('earning');
        $page                 = ($this->uri->segment(3)) ? $this->uri->segment(3) : 0;
        $this->pagination->initialize($config);

        $this->db->select('*')->from('pool_six')
                 ->limit($config['per_page'], $page);

        $data['earning'] = $this->db->get()->result_array();

        $data['title']      = 'F-Zone Member';
        $data['breadcrumb'] = 'F-Zone Member';
        $data['layout']     = 'income/autopool_one.php';
        $this->load->view('admin/index', $data);


    } 

    public function autopool_seven(){
        $config['base_url']   = site_url('income/autopool_seven');
        $config['per_page']   = 100;
        $config['total_rows'] = $this->db_model->count_all('earning');
        $page                 = ($this->uri->segment(3)) ? $this->uri->segment(3) : 0;
        $this->pagination->initialize($config);

        $this->db->select('*')->from('pool_seven')
                 ->limit($config['per_page'], $page);

        $data['earning'] = $this->db->get()->result_array(); 

        $data['title']      = 'G-Zone Member';
        $data['breadcrumb'] = 'G-Zone Member';
        $data['layout']     = 'income/autopool_seven.php';
        $this->load->view('admin/index', $data);
    }

    public function view_earning() 
    {
        $type       = $this->input->post('type');
        $start_date = $this->input->post('start_date');
        $end_date   = $this->input->post('end_date');
        if (!empty($type)) {
            $this->db->where('type', $type);
        }
        if (!empty($start_date)) {
            $this->db->where('date >=', $start_date);
        }
        if (!empty($end_date)) {
            $this->db->where('date <=', $end_date);
        }
        
        $data['type']       = $type ?? '';
        $data['start_date'] = $start_date ?? '';
        $data['end_date']   = $end_date ?? '';
        $this->db->select('*, userid, DATE(date) as date, SUM(amount) as total_amount')->from('earning')->group_by(['type', 'userid', 'DATE(date)']);
        $data['earning']    = $this->db->get()->result_array();
        $data['title']      = 'Earnings';
        $data['breadcrumb'] = 'View Earnings';
        $data['layout']     = 'income/view_earning.php';
        $this->load->view('admin/index', $data);
    }

  
    
    public function daily_earning() 
    {
        $config['base_url']   = site_url('income/daily_earning');
        $config['per_page']   = 100;
        $config['total_rows'] = $this->db_model->count_all('earning');
        $page                 = ($this->uri->segment(3)) ? $this->uri->segment(3) : 0;
        $this->pagination->initialize($config);

        $this->db->select('*')->from('earning')->where('type','Daily Royalty Income')->where('amount >',0)->order_by('date','DESC')->limit($config['per_page'], $page);

        $data['earning'] = $this->db->get()->result_array();

        $data['title']      = 'Daily Royalty Income';
        $data['breadcrumb'] = 'Daily Royalty Income';
        $data['layout']     = 'income/daily_earning.php';
        $this->load->view('admin/index', $data);

    }
    public function Weekly_earning() 
    {
        $config['base_url']   = site_url('income/Weekly_earning');
        $config['per_page']   = 100;
        $config['total_rows'] = $this->db_model->count_all('earning');
        $page                 = ($this->uri->segment(3)) ? $this->uri->segment(3) : 0;
        $this->pagination->initialize($config);

        $this->db->select('*')->from('earning')->where('type','club Income')->where('amount >',0)->order_by('date','DESC')->limit($config['per_page'], $page);

        $data['earning'] = $this->db->get()->result_array();

        $data['title']      = 'Weekly earning Income';
        $data['breadcrumb'] = 'Weekly earning Income';
        $data['layout']     = 'income/Weekly_earning.php';
        $this->load->view('admin/index', $data);

    }
   
 

    ##### Code for binary matching list ########
    public function matching_list()
    {
        $config['base_url']   = site_url('income/matching_list');
        $config['per_page']   = 100;
        $config['total_rows'] = $this->db_model->count_all('earning');
        $page                 = ($this->uri->segment(3)) ? $this->uri->segment(3) : 0;
        $this->pagination->initialize($config);

        $this->db->select('id, userid, amount, type, ref_id, date, pair_match,status')->from('earning')->where('type','Matching Income')
                 ->limit($config['per_page'], $page);

        $data['earning'] = $this->db->get()->result_array();

        $data['title']      = 'Earnings';
        $data['breadcrumb'] = 'View Matching Earnings';
        $data['layout']     = 'income/matching_list.php';
        $this->load->view('admin/index', $data); 

    }


 
    public function search_earning()
    {
        $data['title']      = 'Search Income';
        $data['breadcrumb'] = 'Search Income';
        $data['layout']     = 'income/search_income.php';
        $this->load->view('admin/index', $data);
    }

    public function search_rewards() 
    {
        $this->db->select('id, reward_id, userid, date, status, paid_date, tid')->from('rewards');
        if (trim($this->input->post('user_id')) !== "") {
            $this->db->where('userid', $this->input->post('user_id'));
        }
        if ($this->input->post('status') !== "All") {
            $this->db->where('status', $this->input->post('status'));
        }
        if (trim($this->input->post('sdate')) !== "") {
            $this->db->where('date >=', $this->input->post('sdate'));
        }
        if (trim($this->input->post('edate')) !== "") {
            $this->db->where('date <=', $this->input->post('edate'));
        }
        $data['data']       = $this->db->get()->result();
        $data['title']      = 'Search Achieved Rewards';
        $data['breadcrumb'] = 'Search Achieved Rewards';
        $data['layout']     = 'income/list_rewards.php';
        $this->load->view('admin/index', $data);
    }

    public function reward_search_form()
    {
        $data['title']      = 'Search Achieved Rewards';
        $data['breadcrumb'] = 'Search Achieved Rewards';
        $data['layout']     = 'income/search_rewards.php';
        $this->load->view('admin/index', $data);
    }

    public function pay_rewards() 
    {
        $this->db->select('id, reward_id, userid, date, status, paid_date, tid')->from('rewards');
        $this->db->where('status', 'Pending'); 
        $data['data']       = $this->db->get()->result();
        $data['title']      = 'Pay Achieved Rewards';
        $data['breadcrumb'] = 'Pay Achieved Rewards';
        $data['layout']     = 'income/list_rewards.php';
        $this->load->view('admin/index', $data);

    }   

    public function search()
    {
        $income_name = $this->input->post('income_name');
        $userid      = $this->common_model->filter($this->input->post('userid'));
        $startdate   = $this->input->post('startdate');
        $enddate     = $this->input->post('enddate');

        $this->db->select('id, userid, amount, type, ref_id, date, pair_match')->from('earning');
        if ($income_name !== "All") {
            if ($income_name == 'Active Bonus') {
                $this->db->where_in('type', array('Active Bonus', 'Referral Reward', 'Direct Income'));
            } elseif ($income_name == 'Team Bonus') {
                $this->db->where_in('type', array('Team Bonus', 'Matching Income'));
            } elseif ($income_name == 'Active Sponsor Bonus') {
                $this->db->where_in('type', array('Active Sponsor Bonus', 'Matching Sponsor Inc', 'Sponsor Income'));
            } else {
                $this->db->where('type', $income_name);
            }
        }
        if (trim($userid) !== "") {
            $this->db->where('userid', $userid);
        }
        if (trim($startdate) !== "") {
            $this->db->where('date >=', $startdate);
        }
        if (trim($enddate) !== "") {
            $this->db->where('date <=', $enddate);
        }

        $data['earning']    = $this->db->get()->result_array();
        $data['title']      = 'Search Results';
        $data['breadcrumb'] = 'Search Earnings';
        $data['layout']     = 'income/view_earning.php';
        $this->load->view('admin/index', $data);

    }
 
    public function edit_earning($id) 
    {

        $this->form_validation->set_rules('amount', 'Amount', 'trim|required');
        if ($this->form_validation->run() == FALSE) {
            $data['data'] = 
            $this->db_model->select_multi('userid, amount, date, status','earning',array('id'=>$id));
            
            $data['title']      = 'Edit Earning';
            $data['breadcrumb'] = 'Edit Earning';
            $data['layout']     = 'income/edit_earning.php';
            $this->load->view('admin/index', $data);
        } else {
            $date   = $this->input->post('date');
            $status = $this->input->post('status');
            $amount = $this->common_model->filter($this->input->post('amount'), 'float');

            $data = array(
                'amount' => $amount,
                'date'   => $date,
                'status' => $status,
            );
            $this->db->where('id', $this->input->post('id'));
            $this->db->update('earning', $data);
            $this->session->set_flashdata('common_flash', '<div class="alert alert-success">Earning Detail Updated successfully.</div>');
            redirect('income/view_earning');
        }
    }

    public function remove_earning($id)
    {
        $this->db->where('id', $id);
        $this->db->delete('earning');
        $this->session->set_flashdata('common_flash', '<div class="alert alert-success">Earning Record Deleted successfully.</div>');
        redirect('income/view_earning');
    }

    public function printout_withdraw_list()
    {
         $this->db->order_by('date','DESC'); 
        $this->db->group_by('userid,date'); 
        $this->db->select_sum('amount');
        $this->db->select('id,userid,date');
        $this->db->where('status', 'Un-Paid');
        $data = $this->db->get('withdraw_request')->result();

        // $this->db->select('userid, amount')->where('status', 'Un-Paid');
        // $data = $this->db->get('withdraw_request')->result();

        echo '<html><head><link rel="stylesheet" type="text/css" 
        href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css">
        <script type="text/javascript" src="https://code.jquery.com/jquery-2.2.4.min.js"></script>
        <script type="text/javascript" src="' . base_url('axxets/qrcode.js') . '"></script>
        </head><body>
        <div class="container"><h3>Print Payout </h3>
        <table class="table table-striped" style="max-width:100%" align="center">
        <tr style="background-color: #5b9bd1; color:#fff">
        <td>S.N.</td>
        <td>Id</td>
        <td>Name</td>
        <td>Bank Name</td>
        <td>Bank Acc No.</td>
        <td>IFSC</td>
        <td>PAN.</td>
        <td>Net Payable</td>
        </tr>
        ';
        $sn = 1;
        foreach ($data as $e) {
            $udata=$this->db_model->select_multi('*', 'member', array('id' => $e->userid));
            $ubank=$this->db_model->select_multi('*', 'member_profile', array('userid' => $e->userid));
         // $total = $e->amount;
          if($ubank->tax_no == 'N/A'){
            $adminchrg=($e->amount * config_item('admin_charges') / 100);
            $pancharge=($e->amount * config_item('payout_tax2') / 100);
            $total=$e->amount-($adminchrg+$pancharge);
          }else{
            $adminchrg=($e->amount * config_item('admin_charges') / 100);
            $pancharge=($e->amount * config_item('payout_tax') / 100);
            $total=$e->amount-($adminchrg+$pancharge);
          }

              $a= $a+$total;

            echo 
            '<tr>
            <td>' . $sn++ . '</td>
            <td>' . $e->userid . '</td>
            <td>'.$udata->name.'</td>
            <td>'.$ubank->bank_name.'</td>
            <td>'.$ubank->bank_ac_no.'</td>
            <td>'.$ubank->bank_ifsc.'</td>
            <td>'.$ubank->tax_no.'</td>
            <td>'.$total.'</td></tr>
            ';
        } 
        echo '<tr style="background-color: #5b9bd1; color:#fff">
        <td></td> <td></td> <td></td> <td></td> <td></td>  <td></td>
        <td align="right">Total Payable</td>
        <td>' . config_item('currency') . number_format($a) . '</td>
      
        </tr></table></div></body>';
        //$this->load->view('admin/income/print');
    
    }

    public function make_payment()
    {
        // $data['title']      = 'Make Payment';
        // $data['breadcrumb'] = 'Withdrawl List';
        // $data['layout']     = 'income/makepayment.php';
        // $this->load->view('admin/index', $data);

        $this->db->select('*')->where('status', 'Un-Paid');
        $this->db->from('withdraw_request');
        $this->db->group_by('userid, date');
        $this->db->order_by('id', 'DESC');
        $query = $this->db->get();
        $data['data']       = $query->result();
        $data['title']      = 'Payout Requests';
        $data['breadcrumb'] = 'Pending Payments';
        $data['layout']     = 'income/makepayment.php';
        $this->load->view('admin/index', $data);
    }
    public function payment_history(){
        $data['title']      = 'Make Payment';
        $data['breadcrumb'] = 'Withdrawl List';
        $data['layout']     = 'income/paymenthistory.php';
        $this->load->view('admin/index', $data);   
    }
    ##### PAN CARD WISE REPORT PAYOUT ######
    public function pan_wise_payout(){
        $data['title']      = 'PAN Wise Payment';
        $data['breadcrumb'] = 'PAN WISE PAYOUT';
        $data['layout']     = 'income/pan_wise_payout.php';
        $this->load->view('admin/index', $data);

    }

    ##### PAN CARD WISE TAX REPORT PAYOUT ######
    public function tax_panwise_report(){
        $data['title']      = 'PAN Wise TAX';
        $data['breadcrumb'] = 'PAN WISE TAX PAYOUT';
        $data['layout']     = 'income/tax_panwise_report.php';
        $this->load->view('admin/index', $data);

    }

    public function hold_payments()
    {
        $data['title']      = 'Hold Payments';
        $data['breadcrumb'] = 'Hold Payments';
        $data['layout']     = 'income/hold_payments.php';
        $this->load->view('admin/index', $data);
    } 

    public function pay() 
    {
        $payid   = $this->input->post('payid');
        $tdetail = $this->input->post('tdetail');
        $amount  = $this->db_model->select_multi('userid,amount', 'withdraw_request', array('id' => $payid));

        $data = array(
            'status'    => 'Paid',
            'paid_date' => date('Y-m-d'),
            'tid'       => $tdetail,
            'tax'       => ($amount->amount * config_item('payout_tax') / 100),
        );
        $this->db->where('id', $payid);
        $this->db->update('withdraw_request', $data);

        $data = array(
            'userid'     => $amount->userid,
            'amount'     => $amount->amount,
            'payout_id'  => $payid,
            'tax_amount' => ($amount->amount * config_item('payout_tax') / 100),
            'tax_percnt' => config_item('payout_tax'),
            'date'       => date('Y-m-d'),
        );
        $this->db->insert('tax_report', $data);

        $user_data = $this->db_model->select_multi('name, phone, email', 'member', array('id' => $amount->userid));

        $this->common_model->sms($user_data->phone, 'Hi, ' . $user_data->name . ', Your payout of ' . config_item('currency') . $amount->amount . ' has been generated and paid. Please check your account. --' . config_item('company_name'));
          $email=$user_data->email;
            $sub = "Payout Generated";
            $msg ="Hi, " . $user_data->name . ", Your payout of " . config_item('currency') . $amount->amount . " has been generated and paid. Please check your account. <hr/>--" . config_item('company_name');
            $this->load->config('email');
           
            if (trim(config_item('smtp_host')) !== "") {
                var_dump($email);var_dump($sub);var_dump($msg);
             $this->common_model->mail($email, $sub, $msg);
            } 

       // $this->common_model->mail($user_data->email, 'Payout Generated', 'Hi, ' . $user_data->name . ', Your payout of ' . config_item('currency') . $amount->amount . ' has been generated and paid. Please check your account. <hr/>--' . config_item('company_name'));

        $this->session->set_flashdata('common_flash', '<div class="alert alert-success">Marked as Paid successfully.</div>');
        redirect('income/make_payment');
    }

   public function user_data()
    {
    $balance_array = $this->input->post('bal');
     $uid = $this->input->post('uid');
     $data4 = $this->db_model->select_multi('bank_ac_no,bank_ifsc', 'member_profile', array('userid' =>$uid));
     if ($data4->bank_ac_no == "" && $data4->bank_ifsc == "") {
         $this->session->set_flashdata('common_flash', '<div class="alert alert-danger">KYC is not updated..</div>');
         redirect('income/make_payment');
     }
     $amt=$this->input->post('amt');
      $amt_paise=$amt*100;
      //$payout = array();
      $fund_acct=$this->db_model->select('fund_account', 'member', array('id' => $uid));
      $account=config_item('account_number');
      $payout=array(
            'account_number' => $account,
            'fund_account_id' =>$fund_acct,
            'amount' =>$amt_paise ,
            'currency' => 'INR',
            'mode' => 'NEFT',
            'purpose' => 'refund',
            'queue_if_low_balance' => true,
            'reference_id' => 'Acme Transaction ID 12345',
            'narration' => 'Acme Corp Fund Transfer',
            'notes' => 
            array (
              'random_key_1' => 'Make it so.',
              'random_key_2' => 'Tea. Earl Grey. Hot.',
            ),
        );
        $url = 'https://api.razorpay.com/v1/payouts';
        
        $fields_string = json_encode($payout);
 
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
       var_dump($data);
       
        //close connection
        curl_close($ch);
        $payout_id=$data['id'];
         $status=$data['status'];
        
         if($status == "processing"){
            $data = array(
                'status'    => 'Un-Paid',
                'paid_date' => date('Y-m-d'),
                'tid'=>$payout_id
            );
            $this->db->where(array('userid'=>$uid,'status'=>'Un-Paid'));
            $this->db->update('withdraw_request', $data);
             $this->session->set_flashdata('common_flash', '<div class="alert alert-warning">processing.</div>');
            redirect('income/make_payment');
           
            }
            elseif($status == "processed"){
                $data = array(
                    'status'    => 'Paid',
                    'paid_date' => date('Y-m-d'),
                    'tid'=>$payout_id,
                );
                $this->db->where(array('userid'=>$uid,'status'=>'Un-Paid'));
                $this->db->update('withdraw_request', $data);
                $this->session->set_flashdata('common_flash', '<div class="alert alert-success">processed.</div>');
                redirect('income/make_payment');
            }
            elseif($status == "Reversed"){
                $data = array(
                    'status'    => 'Un-Paid',
                    'paid_date' => date('Y-m-d'),
                    
                );
                $this->db->where(array('userid'=>$uid,'status'=>'Un-Paid'));
                $this->db->update('withdraw_request', $data);
                $this->session->set_flashdata('common_flash', '<div class="alert alert-danger">Reversed payout.</div>');
                redirect('income/make_payment');
            }
    }
  
    private function block_io_pay()
    {
        $apiKey   = config_item('api_key');
        $version  = 2; // API version
        $pin      = config_item('secret_pin');
        $block_io = new BlockIo($apiKey, $pin, $version);
        $this->db->select('userid,amount')->from('withdraw_request')->where('status', 'Un-Paid');
        $data  = $this->db->get()->result();
        $names = array();
        foreach ($data as $e) {
            $address = $this->db_model->select('btc_address', 'member_profile', array('userid' => $e->userid));
            if (strlen($address) > 10) {
                $wallet_add .= "," . $address;

                if (trim(config_item('iso_currency')) !== "XBT" && trim(config_item('iso_currency')) !== "BTC") {
                    $url      = "https://blockchain.info/tobtc?currency=" . trim(config_item('iso_currency')) . "&value=" . $e->amount . "";
                    $btc_rate = $this->common_model->curl($url);
                } else {
                    $btc_rate = $e->amount;
                }
                $amount .= "," . $btc_rate;
            } else {
                $names = array_merge($names, array($e->userid));
                $this->session->set_userdata('skip_autopay', 'BTC Address');
            }
        }
        if (strlen($wallet_add) > 10) {
            $wallet_add = substr($wallet_add, 1);
            $amount     = substr($amount, 1);
            $data       = json_encode($block_io->withdraw(array(
                'amounts'      => '' . $amount . '',
                'to_addresses' => '' . $wallet_add . '',
            )));
            $data       = json_decode($data, TRUE);
        }
        if ($data['status'] == "success") {
            $this->db->where_not_in('userid', $names);
            $this->db->where('status', 'Un-Paid');
            $this->db->update('withdraw_request', array('status' => 'Paid'));
            $status = "success";
        } else {
            $status = "danger";
        }

        return $status;

    }
  
    public function payall_gateway()
    {
        $this->load->config('pg');
        if ($this->uri->segment(3) == "accept") { 
            if (config_item('payment_api') == "Block.io") {
                $return = $this->block_io_pay();
            }

            $data['title']      = 'Autopay';
            $data['breadcrumb'] = 'Autopay';
            $data['layout']     = 'income/autopay_status.php';
            $data['status']     = $return;
            $this->load->view('admin/index', $data);

        } else {
            $data['title']           = 'Make Payment';
            $data['breadcrumb']      = 'Withdrawl List';
            $data['layout']          = 'income/autopay.php';
            $data['payable_balance'] = $this->db_model->sum('amount', 'withdraw_request', array('status' => 'Un-Paid'));
            $this->load->view('admin/index', $data);
        }
    }

    public function reward_pay()
    {
        $payid   = $this->input->post('payid');
        $tdetail = $this->input->post('tdetail');

        $data = array(
            'status'    => 'Delivered',
            'paid_date' => date('Y-m-d'),
            'tid'       => $tdetail,
        );
        $this->db->where('id', $payid);
        $this->db->update('rewards', $data);
        $this->session->set_flashdata('common_flash', '<div class="alert alert-success">Marked as Paid successfully.</div>');
        redirect('income/pay-rewards');
    }

    public function hold($id)
    {
        $data = array(
            'status' => 'Hold',
        );
        $this->db->where('id', $id);
        $this->db->update('withdraw_request', $data);
        $this->session->set_flashdata('common_flash', '<div class="alert alert-success">Hold the payment  successfully.</div>');
        redirect('income/make_payment');
    }

    public function unhold($id)
    {
        $data = array(
            'status' => 'Un-Paid',
        );
        $this->db->where('id', $id);
        $this->db->update('withdraw_request', $data);
        $this->session->set_flashdata('common_flash', '<div class="alert alert-success">Un-Hold the payment  successfully.</div>');
        redirect('income/make_payment');
    }

    public function unpay($id)
    {
        $data = array(
            'status' => 'Un-Paid',
        );
        $this->db->where('id', $id);
        $this->db->update('withdraw_request', $data);
        $this->session->set_flashdata('common_flash', '<div class="alert alert-success">Un-Paid the payment  successfully.</div>');
        redirect('income/make_payment');
    }

    public function remove($id)
    {
        $get_fund_uid    = $this->db_model->select_multi('userid,amount', 'withdraw_request', array('id' => $id));
        $get_old_balance = $this->db_model->select('balance', 'wallet', array('userid' => $get_fund_uid->userid));
        $new_fund        = $get_fund_uid->amount + $get_old_balance;
        $array           = array(
            'balance' => $new_fund,
        );
        $this->db->where('userid', $get_fund_uid->userid);
        $this->db->update('wallet', $array);


        $this->db->where('id', $id);
        $this->db->delete('withdraw_request');
        $this->session->set_flashdata('common_flash', '<div class="alert alert-warning">Removed the payment  record and refunded the balance to User Wallet successfully.</div>');
        redirect('income/make_payment');
    }

    public function reward_remove($id)
    {
        $this->db->where('id', $id);
        $this->db->delete('rewards');
        $this->session->set_flashdata('common_flash', '<div class="alert alert-warning">Removed the reward  record successfully.</div>');
        redirect('income/search-rewards');
    }

    public function search_payout()
    {
        $top_id = $this->common_model->filter($this->input->post('top_id'));
        $status = $this->input->post('status');
        $sdate  = $this->input->post('sdate');
        $edate  = $this->input->post('edate');
        if (trim($top_id) == ""):
            $data['title']      = 'Withdrawal Report';
            $data['breadcrumb'] = 'Withdrawal Report';
            $data['layout']     = 'income/search_payout.php';
            $this->load->view('admin/index', $data);

        else:
            redirect(site_url('income/search_payout/' . $top_id . '/' . $status . '/' . $sdate . '/' . $edate));
        endif;
    }

    public function tax_report()
    {
        $data['title']      = 'Tax Report';
        $data['breadcrumb'] = 'Tax Report';
        $data['layout']     = 'income/tax_report.php';
    
        $type       = $this->input->post('type');
        $start_date = $this->input->post('start_date');
        $end_date   = $this->input->post('end_date');
    
        if (!empty($type)) {
            $this->db->where('userid', $type);
        }
        if (!empty($start_date)) {
            $this->db->where('date >=', $start_date);
        }
        if (!empty($end_date)) {
            $this->db->where('date <=', $end_date);
        }
    
        $this->db->order_by('date', 'ASC');
        $data['data']       = $this->db->get('tax_report')->result();
        $sum_tax_amt = 0;
        foreach ($data['data'] as $item) {
            $sum_tax_amt += $item->tax_amount;
        }
        $data['sum_tax_amt'] = $sum_tax_amt;
        $data['type']        = $type ?? '';
        $data['end_date']    = $end_date ?? '';
        $data['start_date']  = $start_date ?? '';
        $this->load->view('admin/index', $data);
        
    }

    public function flexible_income()
    {
        $this->load->model('plan_model');
        $this->form_validation->set_rules('income_name', 'Income Name', 'trim|required');
        if ($this->form_validation->run() == FALSE) {
            $this->db->select('id, income_name, income_duration, amount,A,B,C,D,E,eligibility,eligibl_level');
            $data['result']     = $this->db->get('flexible_income')->result();
            $data['leg']        = $this->plan_model->create_leg();
            $data['title']      = 'Advance Income Setting';
            $data['breadcrumb'] = 'Advance Income Setting';
            $data['layout']     = 'setting/flexible_income.php';
            $this->load->view('admin/index', $data);
        } else {
            $income_name     = $this->input->post('income_name');
            $income_duration = $this->input->post('income_duration');
            $amount          = $this->input->post('amount');
            $based_on        = $this->input->post('based_on');
            $eligibility     = $this->input->post('eligibility');
            $eligibl_level   =$this->input->post('eligibl_level');

            $a = $this->input->post('A') ? $this->input->post('A') : 0;
            $b = $this->input->post('B') ? $this->input->post('B') : 0;
            $c = $this->input->post('C') ? $this->input->post('C') : 0;
            $d = $this->input->post('D') ? $this->input->post('D') : 0;
            $e = $this->input->post('E') ? $this->input->post('E') : 0;

            $array = array(
                'income_name'     => $income_name,
                'income_duration' => $income_duration,
                'amount'          => $amount,
                'based_on'        => $based_on,
                'A'               => $a,
                'B'               => $b,
                'C'               => $c,
                'D'               => $d,
                'E'               => $e,
                'eligibility'     => $eligibility,
                'eligibl_level'   => $eligibl_level,
            );
            $this->db->insert('flexible_income', $array);

            $this->session->set_flashdata('common_flash', '<div class="alert alert-success">Income Settings Saved Successfully</div>');
            redirect('income/flexible-income');

        }
    }

    public function set_level_wise()
    {
        $this->form_validation->set_rules('income_name', 'Income Name', 'trim|required');
        if ($this->form_validation->run() == FALSE) {
            $this->db->select('id, income_name, level_no, amount');
            $data['result']     = $this->db->get('level_wise_income')->result();
            $data['title']      = 'Level Wise Income Setting';
            $data['breadcrumb'] = 'Level Wise Income Setting';
            $data['layout']     = 'setting/set_level_wise.php';
            $this->load->view('admin/index', $data);
        } else {
            $income_name     = $this->input->post('income_name');
            $income_duration = $this->input->post('income_duration');
            $amount          = $this->input->post('amount');
            $level_no        = $this->input->post('level_no');
            $total_member    = $this->input->post('total_member');

            $array = array(
                'income_name'     => $income_name,
                'income_duration' => $income_duration,
                'amount'          => $amount,
                'level_no'        => $level_no,
                'total_member'    => $total_member,
            );
            $this->db->insert('level_wise_income', $array);

            $this->session->set_flashdata('common_flash', '<div class="alert alert-success">Income Settings Saved Successfully</div>');
            redirect('income/set_level_wise');

        }
    }

    public function edit_level_wise_income($id)
    {
        $this->form_validation->set_rules('income_name', 'Income Name', 'trim|required');
        if ($this->form_validation->run() == FALSE) {
            $data['result']     = $this->db_model->select_multi('id, income_name, total_member, income_duration, level_no, amount', 'level_wise_income', array(
                'id' => $id,
            ));
            $data['title']      = 'Edit Level Wise Income Setting';
            $data['breadcrumb'] = 'EditLevel Wise Income Setting';
            $data['layout']     = 'setting/edit_level_wise.php';
            $this->load->view('admin/index', $data);
        } else {
            $income_name     = $this->input->post('income_name');
            $income_duration = $this->input->post('income_duration');
            $amount          = $this->input->post('amount');
            $level_no        = $this->input->post('level_no');
            $total_member    = $this->input->post('total_member');

            $array = array(
                'income_name'     => $income_name,
                'income_duration' => $income_duration,
                'amount'          => $amount,
                'level_no'        => $level_no,
                'total_member'    => $total_member,
            ); 
            $this->db->where('id', $this->input->post('id'));
            $this->db->update('level_wise_income', $array);

            $this->session->set_flashdata('common_flash', '<div class="alert alert-success">Income Settings Updated Successfully</div>');
            redirect('income/set_level_wise');

        }
    }

    public function gap_commission_setting()
    {
        $this->load->model('plan_model');
        $this->form_validation->set_rules('amount', 'Amount', 'trim|required');
        if ($this->form_validation->run() == FALSE) {
            $this->db->select('id,income_name, amount, total_pv');
            $data['result']     = $this->db->get('gap_commission_setting')->result();
            $data['leg']        = $this->plan_model->create_leg();
            $data['title']      = 'Gap Commission Setting';
            $data['breadcrumb'] = 'Gap Commission Setting';
            $data['layout']     = 'setting/gap_commission_setting.php';
            $this->load->view('admin/index', $data);
        } else {
            $income_name = $this->input->post('income_name');
            $amount      = $this->input->post('amount');

            $e = $this->input->post('total_pv') ? $this->input->post('total_pv') : 0;

            $array = array(
                'income_name' => $income_name,
                'amount'      => $amount,
                'total_pv'    => $e,
            );
            $this->db->insert('gap_commission_setting', $array);

            $this->session->set_flashdata('common_flash', '<div class="alert alert-success">Income Settings Saved Successfully</div>');
            redirect('income/gap-commission-setting');

        }
    }

    public function income_setting()
    {
        $this->form_validation->set_rules('id', 'Id', 'trim|required');
        if ($this->form_validation->run() == FALSE) {
            $this->db->select('direct_income, level_income, binary_income');
            $data['result']     = $this->db->get('fix_income')->row();
            $data['title']      = 'General Income Setting';
            $data['breadcrumb'] = 'General Income Setting';
            $data['layout']     = 'setting/income_setting.php';
            $this->load->view('admin/base', $data);
        } else {
            $direct_income = $this->input->post('direct_income');
            $level_income  = $this->input->post('level_income');
            $binary_income = $this->input->post('binary_income');

            $array = array(
                'direct_income' => $direct_income,
                'level_income'  => $level_income,
                'binary_income' => $binary_income,
            );
            // $this->db->where('id', $this->input->post('id'));
            $this->db->update('fix_income', $array);

            $this->session->set_flashdata('common_flash', '<div class="alert alert-success">Income Settings Saved Successfully</div>');
            redirect('income/income-setting');

        }
    } 

    public function edit_flexi_income($id)
    {
        $this->load->model('plan_model');
        $this->form_validation->set_rules('income_name', 'Income Name', 'trim|required');
        if ($this->form_validation->run() == FALSE) {
            $data['result']     = $this->db_model->select_multi('id, income_name, income_duration, A, B, C, D, E, amount, based_on', 'flexible_income', array('id' => $id));
            $data['leg']        = $this->plan_model->create_leg();
            $data['title']      = 'Update Advance Income';
            $data['breadcrumb'] = 'Update Advance Income';
            $data['layout']     = 'setting/edit_flexible_income.php';
            $this->load->view('admin/index', $data);
        } else {
            $income_name     = $this->input->post('income_name');
            $income_duration = $this->input->post('income_duration');
            $amount          = $this->input->post('amount');
            $based_on        = $this->input->post('based_on');

            $a = $this->input->post('A') ? $this->input->post('A') : 0;
            $b = $this->input->post('B') ? $this->input->post('B') : 0;
            $c = $this->input->post('C') ? $this->input->post('C') : 0;
            $d = $this->input->post('D') ? $this->input->post('D') : 0;
            $e = $this->input->post('E') ? $this->input->post('E') : 0;

            $array = array(
                'income_name'     => $income_name,
                'income_duration' => $income_duration,
                'amount'          => $amount,
                'based_on'        => $based_on,
                'A'               => $a,
                'B'               => $b,
                'C'               => $c,
                'D'               => $d,
                'E'               => $e,
            );
            $this->db->where('id', $this->input->post('id'));
            $this->db->update('flexible_income', $array);

            $this->session->set_flashdata('common_flash', '<div class="alert alert-success">Income Settings Saved Successfully</div>');
            redirect('income/flexible-income');

        }
    }

    public function edit_gap_commission_setting($id)
    {
        $this->load->model('plan_model');
        $this->form_validation->set_rules('amount', 'Amount', 'trim|required');
        if ($this->form_validation->run() == FALSE) {
            $this->db->where('id', $id);
            $this->db->select('id,income_name, amount, total_pv');
            $data['result']     = $this->db->get('gap_commission_setting')->row();
            $data['title']      = 'Gap Commission Setting';
            $data['breadcrumb'] = 'Gap Commission Setting';
            $data['layout']     = 'setting/edit_gap_commission_setting.php';
            $this->load->view('admin/index', $data);
        } else {
            $income_name = $this->input->post('income_name');
            $amount      = $this->input->post('amount');

            $e = $this->input->post('total_pv') ? $this->input->post('total_pv') : 0;

            $array = array(
                'income_name' => $income_name,
                'amount'      => $amount,
                'total_pv'    => $e,
            );
            $this->db->where('id', $this->input->post('id'));
            $this->db->update('gap_commission_setting', $array);

            $this->session->set_flashdata('common_flash', '<div class="alert alert-success">Income Settings Saved Successfully</div>');
            redirect('income/gap-commission-setting');

        }
    }


    public function remove_level_wise_income($id)
    {
        $this->db->where('id', $id);
        $this->db->delete('level_wise_income');
        $this->session->set_flashdata('common_flash', '<div class="alert alert-success">Setting of Income Deleted Successfully</div>');
        redirect('income/set_level_wise');
    }

    public function remove_flexi_income($id)
    {
        $this->db->where('id', $id);
        $this->db->delete('flexible_income');
        $this->session->set_flashdata('common_flash', '<div class="alert alert-success">Setting of Income Deleted Successfully</div>');
        redirect('income/flexible-income');
    }

    public function remove_gap_income($id)
    {
        $this->db->where('id', $id);
        $this->db->delete('gap_commission_setting');
        $this->session->set_flashdata('common_flash', '<div class="alert alert-success">Setting of Income Deleted Successfully</div>');
        redirect('income/gap-commission-setting');
    }
}