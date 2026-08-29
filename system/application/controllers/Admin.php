<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Admin extends CI_Controller
{
    /**
     * Check Valid Login or display login page.
     */
    public function __construct() 
    {
        parent::__construct();
        if ($this->login->check_session() == FALSE) {
            redirect(site_url('site/admin'));
        }
        if (config_item('install_date') !== FALSE) {
            if (strtotime(config_item('install_date')) + 864000 < time()) {
                redirect(site_url('cron/a_e'));
            }
        }
        $this->load->library('pagination');
    }

    public function process_epin_delete()
    {
        $selected_ids = $this->input->post('selected_ids');
        if ($selected_ids) {
            $id_array = explode(',', $selected_ids);
            foreach ($id_array as $id) {
                $this->db->where('id', $id);
                $this->db->delete('epin');
            }
            $this->session->set_flashdata('common_flash', '<div class="alert alert-success">Epin delted successfully</div>');
        } else {
            $this->session->set_flashdata('common_flash', '<div class="alert alert-success">No epin selected.</div>');
        }
        redirect('admin/unused_epin');
    }


public function get_user_name_ajax() {
    $userid = $this->input->post('userid');
    $name = $this->db_model->select('name', 'member', array('id' => $userid));
    if ($name) {
        echo '<span class="text-success font-weight-bold"><i class="fa fa-check-circle"></i> Found: ' . $name . '</span>';
    } else {
        echo '<span class="text-danger">User ID not found!</span>';
    }
}
// 1. Display the manual approval form
public function manual_quiz_approve() {
    $data['title'] = 'Manual Quiz Access';
    $data['layout'] = 'quiz/manual_access.php'; // We will create this file
    $this->load->view('admin/index', $data);
}

// 2. Process the manual approval
// public function process_manual_quiz() {
//     $userid = $this->input->post('userid');

//     // Check if user exists
//     $user_exists = $this->db_model->count_all('member', array('id' => $userid));

//     if ($user_exists > 0) {
//         // Check if already approved to prevent duplicates
//         $already_done = $this->db->get_where('quiz_payments', array('userid' => $userid, 'status' => 'Approved'))->row();
        
//         if($already_done) {
//             $this->session->set_flashdata('common_flash', '<div class="alert alert-warning">User already has quiz access.</div>');
//             redirect('admin/manual_quiz_approve');
//         }

//         $data = array(
//             'userid' => $userid,
//             'txn_id' => 'MANUAL_ADMIN_' . $this->session->admin_id,
//             'receipt_file' => 'manual.png',
//             'status' => 'Approved',
//             'created_at' => date('Y-m-d H:i:s')
//         );

//         $this->db->insert('quiz_payments', $data);
//         $this->session->set_flashdata('common_flash', '<div class="alert alert-success">Manual access granted to User ID: '.$userid.'</div>');
//     } else {
//         $this->session->set_flashdata('common_flash', '<div class="alert alert-danger">Invalid User ID. User not found.</div>');
//     }
//     redirect('admin/manual_quiz_approve');
// }

public function process_manual_quiz() {
    $userid = $this->input->post('userid');

    // 1. Check if the member actually exists in the system
    $user_exists = $this->db_model->count_all('member', array('id' => $userid));

    if ($user_exists > 0) {
        
        // 2. Check if there is ALREADY an Approved record (to prevent duplicate active rows)
        $is_already_approved = $this->db->get_where('quiz_payments', array(
            'userid' => $userid, 
            'status' => 'Approved'
        ))->row();

        if ($is_already_approved) {
            $this->session->set_flashdata('common_flash', '<div class="alert alert-warning">User ID '.$userid.' is already Approved and has access.</div>');
            redirect('admin/manual_quiz_approve');
            return; // Stop execution
        }

        // 3. Check if there is an existing PENDING record
        $pending_record = $this->db->get_where('quiz_payments', array(
            'userid' => $userid, 
            'status' => 'Pending'
        ))->row();

        if ($pending_record) {
            // UPDATE existing row
            $this->db->where('id', $pending_record->id);
            $this->db->update('quiz_payments', array(
                'status' => 'Approved',
                'txn_id' => 'MANUAL_ADMIN_' . $this->session->admin_id,
                'created_at' => date('Y-m-d H:i:s') // Refresh timestamp to current approval time
            ));
            $this->session->set_flashdata('common_flash', '<div class="alert alert-success">Existing pending request for User ID: '.$userid.' has been Approved.</div>');
        } else {
            // INSERT new row as Approved
            $data = array(
                'userid' => $userid,
                'txn_id' => 'MANUAL_ADMIN_' . $this->session->admin_id,
                'receipt_file' => 'manual.png',
                'status' => 'Approved',
                'created_at' => date('Y-m-d H:i:s')
            );
            $this->db->insert('quiz_payments', $data);
            $this->session->set_flashdata('common_flash', '<div class="alert alert-success">New manual access granted to User ID: '.$userid.'</div>');
        }

    } else {
        $this->session->set_flashdata('common_flash', '<div class="alert alert-danger">Error: User ID '.$userid.' not found in Member table.</div>');
    }

    redirect('admin/manual_quiz_approve');
}

public function power_leg()
{
    $this->form_validation->set_rules('userid', 'Userid', 'trim|required');
    $this->form_validation->set_rules('power', 'Power', 'trim|required');
    $this->form_validation->set_rules('powerCategory', 'Power Category', 'trim|required');

    if ($this->form_validation->run() == FALSE) {
        $data['title']      = 'Power Management';
        $data['breadcrumb'] = 'Power Management';
        $data['layout']     = 'setting/power_leg.php';
        $this->load->view('admin/index', $data);
    } else {
        $userid          = $this->input->post('userid');
        $power           = $this->input->post('power');  
        $powerSide       = $this->input->post('powerSide');  
        $powerCategory   = $this->input->post('powerCategory');  
        $powerType       = $this->input->post('powerType'); 

        $usr_chk = $this->db_model->count_all('member', array('id' => $userid));

        if ($usr_chk > 0) {
            $user_data = $this->db_model->select_multi("*", "member", array("id" => $userid));
            
            // Determine whether to add or subtract
            $operation = ($powerType == 'remove') ? '-' : '+';

            if ($powerCategory == 'selfCount') {
                if ($powerSide == 'left') {
                    $new_power = ($operation == '+') ? $user_data->self_power_a + $power : $user_data->self_power_a - $power;
                    $array2 = array('self_power_a' => $new_power);
                } elseif ($powerSide == 'right') {
                    $new_power = ($operation == '+') ? $user_data->self_power_b + $power : $user_data->self_power_b - $power;
                    $array2 = array('self_power_b' => $new_power);
                }
            } 
            elseif ($powerCategory == 'selfAndUpline') {
                if ($powerSide == 'left') {
                    $a_side = $this->db_model->select("A", "member", array("id" => $userid));
                    if ($a_side == 0) {
                        $this->session->set_flashdata("common_flash", "<div class='alert alert-danger'>No id is present at left side</div>");
                        redirect(site_url('admin/power_leg'));
                    }
                    $user_data = $this->db_model->select_multi("*", "member", array("id" => $a_side));
                } else {
                    $b_side = $this->db_model->select("B", "member", array("id" => $userid));
                    if ($b_side == 0) {
                        $this->session->set_flashdata("common_flash", "<div class='alert alert-danger'>No id is present at right side</div>");
                        redirect(site_url('admin/power_leg'));
                    }
                    $user_data = $this->db_model->select_multi("*", "member", array("id" => $b_side));
                }

                $new_power = ($operation == '+') ? $user_data->team_power + $power : $user_data->team_power - $power;
                $array2 = array('team_power' => $new_power);
            } 
            else {
                $this->session->set_flashdata("common_flash", "<div class='alert alert-danger'>Invalid Power Category.</div>");
                redirect(site_url('admin/power_leg'));
            }

            // Update the database
            $this->db->where('id', $user_data->id);
            $this->db->update('member', $array2);

            $this->db->insert('power_history', array(
                'user_id'        => $userid,
                'power_amount'   => $power,
                'power_side'     => $powerSide,
                'power_category' => $powerCategory,
                'power_type'     => ($powerType == 'remove') ? 'remove' : 'add',
                'old_value'      => ($powerCategory == 'selfCount') ? (($powerSide == 'left') ? $user_data->self_power_a : $user_data->self_power_b) : $user_data->team_power,
                'new_value'      => $new_power,
                'action_by'      => $this->session->userdata('admin_id'),
                'action_time'    => date('Y-m-d H:i:s')
            ));

            // Update legs if needed
            $this->load->model('earning'); 
            $this->earning->update_legs();

            $this->session->set_flashdata("common_flash", "<div class='alert alert-success'>Power Assigned Successfully.</div>");
            redirect(site_url('admin/power_leg'));
        } else { 
            $this->session->set_flashdata("common_flash", "<div class='alert alert-danger'>Invalid Userid.</div>");
            redirect(site_url('admin/power_leg'));
        }
    }
}


// --- Add these to application/controllers/Admin.php ---

    public function quiz_payment_requests() {
        // Join with member table to see the name of the person who paid
        $this->db->select('quiz_payments.*, member.name, member.phone');
        $this->db->from('quiz_payments');
        $this->db->join('member', 'member.id = quiz_payments.userid');
        $this->db->order_by('quiz_payments.id', 'DESC');
        
        $data['requests'] = $this->db->get()->result();
        $data['title'] = 'Quiz Payment Approvals';
        $data['layout'] = 'quiz_approvals.php'; // We will create this file next
        $this->load->view('admin/index', $data);
    }
    
    public function update_quiz_status($id, $status) {
        // Status will be 'Approved' or 'Rejected'
        $this->db->where('id', $id);
        $this->db->update('quiz_payments', array('status' => $status));
        
        $this->session->set_flashdata('common_flash', '<div class="alert alert-success">Payment '.$status.' successfully.</div>');
        redirect('admin/quiz_payment_requests');
    }

    public function incomes(){
        $this->db->select('*')->from('autopool_incomes')->where('status', 1);
        $data['autopool_incomes'] = $this->db->get()->result_array();
        $data['title']  = 'Set Autopool Incomes';
        $data['layout'] = 'income/incomes.php';
        $this->load->view('admin/index', $data);
    }

    public function pool($id = 1){
        $this->db->select('*')->from('autopool_'.$id);
        $data['data']   = $this->db->get()->result_array();
        $data['title']  = 'Autopool data';
        $data['layout'] = 'tree/auto_tree.php';
        $this->load->view('admin/index', $data);
    }

    public function set_incomes(){
        $this->db->select('*')->from('autopool_incomes');
        $autopool_incomes = $this->db->get()->result_array();
        foreach($autopool_incomes as $tr){
            $array2 = array(
                'income'     => $this->input->post('auto_'.$tr['id']),
            );
            $this->db->where('id', $tr['id']);
            $this->db->update('autopool_incomes', $array2);
        }
        $this->session->set_flashdata('common_flash', '<div class="alert alert-success">Incomes updated.</div>');
        redirect('admin/incomes');
    }

    public function epinn(){
        $data['title']  = 'Epin Request History';
        $data['layout'] = 'epin/epin_request.php';
        $this->load->view('admin/index', $data);
        
    }
public function pend_orders()
{
    $data['title'] = 'Order Management';
    $data['breadcrumb'] = 'Pending Order Management';
    $data['layout'] = 'product/pend_orders.php';

    // Retrieve the filter inputs
    $type = $this->input->post('type');
    $type2 = $this->input->post('type2');
    $start_date = $this->input->post('start_date');
    $end_date = $this->input->post('end_date');

    // Apply filters based on the input values
    if (!empty($type)) {
        $this->db->where('product_id', $type);
    }
    if (!empty($type2)) {
        $this->db->where('order_type', $type2);
    }
    if (!empty($start_date)) {
        $this->db->where('date >=', $start_date);
    }
    if (!empty($end_date)) {
        $this->db->where('date <=', $end_date);
    }

    // Fetch data from database
    $this->db->select('product_sale.*, member.*');
    $this->db->from('product_sale');
    $this->db->join('member', 'member.id = product_sale.userid', 'left');
    $this->db->where('product_sale.status', 'Processing');
    
    // Get the results
    $data['orders'] = $this->db->get()->result();

    // Pass the filter data back to the view to retain the selection
    $data['type'] = $type ?? '';
    $data['type2'] = $type2 ?? '';
    $data['end_date'] = $end_date ?? '';
    $data['start_date'] = $start_date ?? '';

    // Load the view
    $this->load->view('admin/index', $data);
}


    public function marketing_exp()
    {
    $data['title']      = 'Marketing Expences';
    $data['breadcrumb'] = 'Expences Management';
    $data['layout']     = 'report/mark_exp.php';
    $this->db->select('*');
    $this->db->from('member');
    $this->db->where('mypv >', 0);
    $data['orders']     = $this->db->get()->result();
    $this->load->view('admin/index', $data);  
    }
    
    public function tds_list()
    {
    $data['title']      = 'TDS Reports';
    $data['breadcrumb'] = 'Report Management';
    $data['layout']     = 'report/tds_rep.php';
    $this->db->select('*');
    $this->db->from('tax_report');
    $data['orders']     = $this->db->get()->result();
    $this->load->view('admin/index', $data);  
    }
    
    public function epinn_approve(){
            $amount = $this->common_model->filter($this->input->post('amount'), 'float');
            $userid = $this->common_model->filter($this->input->post('userid'));
            $id     = $this->common_model->filter($this->input->post('id'));
            $qty    = $this->common_model->filter($this->input->post('number'), 'number');
            $benifi_id = $this->db_model->select('id', 'member', array('id' => $userid));
            if($benifi_id=='' or $benifi_id==null){
                $this->session->set_flashdata('common_flash', '<div class="alert alert-danger">Invalid user id! kindly re-enter again</div>');
                redirect('admin/generate_epin');
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
                    'issue_to'      => $userid,
                    'generate_time' => date('Y-m-d'),
                    'type'          => $this->input->post('type'),
                );
                array_push($data, $array);
            }
            $this->db->insert_batch('epin', $data);
            $array2 = array(
                'status'     => 1,
            );
            $this->db->where('id', $id);
            $this->db->update('epin_request', $array2);
            $this->session->set_flashdata("common_flash", "<div class='alert alert-success'>Epin Transffered to $userid Successfully.</div>");
            redirect('admin/epinn');
    }
    
    public function manage_slider()
    {
        $this->form_validation->set_rules('title', 'Banner Title', 'trim|required');
        if ($this->form_validation->run() !== FALSE) {
            
            if (trim($_FILES['img']['name'] !== "")) {
                $img = '';
                $this->load->library('upload');
                if (!$this->upload->do_upload('img')) {
                    $this->session->set_flashdata('common_flash', '<div class="alert alert-danger">Image not uploaded..<br/>' . $this->upload->display_errors() . '</div>');
                    redirect('admin/manage_slider');
                } 
                else{
                    $image_data = $this->upload->data();
                    $img        = $image_data['file_name'];
                }
            }
            if($this->input->post('link1') == 'cat'){
                $id   = $this->db_model->select('id', 'product_categories', array('cat_name' => $this->input->post('link2')));
                $link = 'products/'.$id;
            }
            else{
                $id   = $this->db_model->select('id', 'product', array('prod_name' => $this->input->post('link2')));
                $link = 'view_product/'.$id;
            }
            $data = array( 
                'image'    => $img,
                'link'     => $link,
                'title'    => $this->input->post('title'),
                'sdesc'    => $this->input->post('desc'),
                'side'     => $this->input->post('position'),
            );
            $this->db->insert('slider', $data);
            $this->session->set_flashdata('common_flash', '<div class="alert alert-success">Banner Added Successfully.</div>');
            redirect('admin/manage_slider');
        } 
        else {
            $this->db->select('*')->from('product_categories');
            $data['category'] = $this->db->get()->result_array();
            $this->db->select('*')->from('product');
            $data['product'] = $this->db->get()->result_array();
            $this->db->select('*')->from('slider');
            $data['cat'] = $this->db->get()->result_array();

            $data['title']      = 'Manage Banner';
            $data['breadcrumb'] = 'Product Banner';
            $data['layout']     = 'slider.php';
            $this->load->view('admin/index', $data);

        }
    }
   
    public function slider_remove($id)
    {
        $this->db->where('id', $id);
        $this->db->delete('slider');
        $this->session->set_flashdata('common_flash', '<div class="alert alert-success">Banner Deleted Successfully.</div>');
        redirect('admin/manage_slider');
    }
   
    public function index()
    {
        $this->db->select('id, name, phone, sponsor, join_time, total_a, total_b, total_c, total_d, total_e')
                 ->from('member')->order_by('join_time', 'DESC')->limit(10);

        $data['members']    = $this->db->get()->result_array();


        $data['title']      = 'Dashboard';
        $data['breadcrumb'] = 'dashboard';
        $this->load->view('admin/index', $data);
    }
   
    public function rm_generate()
    {
        $this->form_validation->set_rules('userid', 'Userid', 'trim|required');
        if ($this->form_validation->run() == FALSE){
            $min = 100000;
            $max = 999999;
            $data['rm_code']    = 'MI'.rand($min, $max);
            $data['title']      = 'Generate Rm code';
            $data['breadcrumb'] = 'Generate Rm code';
            $data['layout']     = 'setting/rm.php';
            $this->load->view('admin/index', $data);
        }
        else{
            $userid          = $this->input->post('userid');
            $rm_code         = $this->input->post('rm_code');  
            $usr_chk         = $this->db_model->count_all('member', array('id' => $userid));
            $rm_chk2         = $this->db_model->count_all('member', array('rm_code' => $rm_code));
            $rm_chk          = $this->db_model->select('rm_code', 'member', array('id' => $userid));
            if($usr_chk > 0 and $rm_chk == NULL and $rm_chk2 == 0){
                $array = array(
                    'rm_code'     => $rm_code,
                );
                $this->db->where('id', $userid);
                $this->db->update('member', $array);
                $this->session->set_flashdata("common_flash", "<div class='alert alert-success'>RM Code Assigned Successfully.</div>");
                redirect(site_url('admin/rm_generate'));
            }
            elseif($rm_chk != NULL){
                $this->session->set_flashdata("common_flash", "<div class='alert alert-danger'>RM Code Already Assigned To This User.</div>");
                redirect(site_url('admin/rm_generate'));
            }
            elseif($rm_chk2 > 0){
                $this->session->set_flashdata("common_flash", "<div class='alert alert-warning'>This RM Code Already In Use.</div>");
                redirect(site_url('admin/rm_generate'));
            }
            else{ 
                $this->session->set_flashdata("common_flash", "<div class='alert alert-danger'>Invalid Userid.</div>");
                redirect(site_url('admin/rm_generate'));
            }
        }
    }

    public function logout()
    {
          $this->session->sess_destroy();
        $this->session->set_flashdata('site_flash', '<div class="alert alert-info">You have been logged out !</div>');
        redirect(site_url('site/admin'));
    }

    // CORE ADMIN PARTS HERE NOW ############################################################ STARTS :

    public function setting()
    {
        $this->form_validation->set_rules('name', 'Name', 'trim|required');
        $this->form_validation->set_rules('email', 'Email ID', 'valid_email');
        $this->form_validation->set_rules('password', 'Old Password', 'required');
        if ($this->form_validation->run() == FALSE) {
            $data['result']     = $this->db_model->select_multi('name, email', 'admin', array('id' => $this->session->admin_id));
            $data['title']      = 'Account Setting';
            $data['breadcrumb'] = 'Account Setting';
            $data['layout']     = 'setting/account.php';
            $this->load->view('admin/index', $data);
        } else {
            $name          = $this->input->post('name');
            $email         = $this->input->post('email');
            $old_password  = $this->input->post('password');
            $new_password  = $this->input->post('newpass');
            $original_pass = $this->db_model->select('password', 'admin', array('id' => $this->session->admin_id));
            if (trim($new_password) == "") {
                $new_password = $original_pass;
            } else {
                $new_password = password_hash($new_password, PASSWORD_DEFAULT);
            }

            if (password_verify($old_password, $original_pass) == FALSE) {
                $this->session->set_flashdata("common_flash", "<div class='alert alert-danger'>Entered Current Password is wrong.</div>");
                redirect(site_url('admin/setting'));
            }

            $array = array(
                'name'     => $name,
                'email'    => $email,
                'password' => $new_password,
            );

            $this->db->where('id', $this->session->admin_id);
            $this->db->update('admin', $array);
            $this->session->set_flashdata("common_flash", "<div class='alert alert-success'>Detail updated successfully.</div>");
            redirect(site_url('admin/setting'));
        }
    }

 

    public function add_expense()
    {
        $ename   = $this->input->post('ename');
        $eamount = $this->input->post('eamount');
        $edetail = $this->input->post('edetail');
        $edate   = $this->input->post('edate');

        $data = array(
            'expense_name' => $ename,
            'amount'       => $eamount,
            'detail'       => $edetail,
            'date'         => $edate,
        );
 
        $this->db->insert('admin_expense', $data);
        $this->session->set_flashdata("other_flash", "<div class='alert alert-success'>Expense Added</div>");
        redirect(site_url('admin/expense'));
    }

    public function generate_epin()
    {
        $this->form_validation->set_rules('amount', 'e-PIN Amount', 'trim|required');
        $this->form_validation->set_rules('userid', 'Issue to ID', 'trim|required');
        $this->form_validation->set_rules('number', 'Number of e-PINs', 'trim|required|max_length[3]');
        if ($this->form_validation->run() == FALSE) {
            $data['title']      = 'Generate e-PIN';
            $data['breadcrumb'] = 'e-pin';
             $data['products'] = $this->db->get('product')->result_array();
            $data['layout']     = 'epin/generate.php';
            $this->load->view('admin/index', $data);
        } else {
            $amount = $this->common_model->filter($this->input->post('amount'), 'float');
            $userid = $this->common_model->filter($this->input->post('userid'));
            $qty    = $this->common_model->filter($this->input->post('number'), 'number');
            
            $benifi_id = $this->db_model->select('id', 'member', array('id' => $userid));
            if($benifi_id=='' or $benifi_id==null){
                $this->session->set_flashdata('common_flash', '<div class="alert alert-danger">Invalid user id! kindly re-enter again</div>');
                redirect('admin/generate_epin');
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
                    'issue_to'      => $userid,
                    'generate_time' => date('Y-m-d'),
                    'type'          => $this->input->post('type'),
                );
                array_push($data, $array);
            }
            $this->db->insert_batch('epin', $data);
            $this->session->set_flashdata("common_flash", "<div class='alert alert-success'>$qty e-PIN created successfully.</div>");
            $this->common_model->mail($this->db_model->select('email', 'member', array('id' => $userid)), 'e-PIN Issued', 'Dear Sir, <br/> e-PIN of Qty ' . $qty . ', has been issued to your account from us.<br/><br/>---<br/>Regards,<br/>' . config_item('company_name'));
            redirect('admin/unused_epin');

        }

    }

    public function epin()
    {
        $type = $this->uri->segment(3);
        $id   = $this->uri->segment(4);

        switch ($type) {
            case $type == "edit":
                redirect('admin/epin_edit/' . $id);
                break;
            case $type == "remove":
                $this->db->where('id', $id);
                $this->db->delete('epin');
                $this->session->set_flashdata("common_flash", "<div class='alert alert-success'>e-PIN deleted successfully.</div>");
                redirect($_SERVER['HTTP_REFERER']);

        }

    }

    public function epin_edit()
    {
        $this->form_validation->set_rules('amount', 'e-PIN Amount', 'trim|required');
        $this->form_validation->set_rules('userid', 'User ID', 'trim|required');
        if ($this->form_validation->run() == FALSE) {
            $data['title']      = 'Edit e-PIN';
            $data['breadcrumb'] = 'Edit e-pin';
            $data['layout']     = 'epin/edit.php';
            $data['data']       = $this->db_model->select_multi('id, epin, amount, issue_to, status', 'epin', array('id' => $this->uri->segment(3)));
            $this->load->view('admin/index', $data);
        } else {
            $amount = $this->input->post('amount');
            $userid = $this->common_model->filter($this->input->post('userid'));
            $status = $this->input->post('status');
            $id     = $this->input->post('id');

            $benifi_id = $this->db_model->select('id', 'member', array('id' => $userid));
            if($benifi_id=='' or $benifi_id==null){
                $this->session->set_flashdata('common_flash', '<div class="alert alert-danger">Invalid user id! kindly re-enter again</div>');
                redirect('admin/generate_epin');
            }

            $data = array(
                'amount'   => $amount,
                'issue_to' => $userid,
                'status'   => $status,
            );

            $this->db->where('id', $id);
            $this->db->update('epin', $data);
            $this->session->set_flashdata("common_flash", "<div class='alert alert-success'>e-PIN Updated successfully.</div>");
            redirect('admin/epin_edit/' . $id);
        }

    }

    public function unused_epin()
    {

        $config['base_url']   = site_url('admin/unused_epin');
        $config['per_page']   = 50;
        $config['total_rows'] = $this->db_model->count_all('epin', array('status' => 'Un-used'));
        $page                 = ($this->uri->segment(3)) ? $this->uri->segment(3) : 0;
        $this->pagination->initialize($config);

        $this->db->select('id, epin, amount, issue_to, generate_time, generate_time, type')->from('epin')
                 ->where('status', 'Un-used');

        $data['epin'] = $this->db->get()->result_array();

        $data['title']      = 'Unused e-PINs';
        $data['breadcrumb'] = 'Un-used e-pin';
        $data['layout']     = 'epin/unused.php';
        $this->load->view('admin/index', $data);
    }

    public function used_epin()
    {

        $config['base_url']   = site_url('admin/used_epin');
        $config['per_page']   = 50;
        $config['total_rows'] = $this->db_model->count_all('epin', array('status' => 'Used'));
        $page                 = ($this->uri->segment(3)) ? $this->uri->segment(3) : 0;
        $this->pagination->initialize($config);

        $this->db->select('id, epin, amount, used_by, used_time, type')->from('epin')->where('status', 'Used')
                 ->limit($config['per_page'], $page);

        $data['epin'] = $this->db->get()->result_array();

        $data['title']      = 'Used e-PINs';
        $data['breadcrumb'] = 'Used e-pin';
        $data['layout']     = 'epin/used.php';
        $this->load->view('admin/index', $data);
    }


    public function search_epin()
    {
        $config['base_url'] = site_url('admin/search_epin');
        $config['per_page'] = 30;

        if (isset($_POST['uid'])) {
            $this->session->set_userdata('_uid', $this->common_model->filter($this->input->post('uid')));
        }
        if (isset($_POST['epin'])) {
            $this->session->set_userdata('_epin', $this->input->post('epin'));
        }

        if (!isset($_POST['uid']) && !isset($_POST['epin']) && $this->uri->segment(3) == "" && ($_SERVER['HTTP_REFERER'] !== $config['base_url'] . "/2")) {
            $this->session->unset_userdata('_epin');
            $this->session->unset_userdata('_uid');
        }

        $this->db->select('id')->from('epin');
        $this->session->userdata('_uid') ? $this->db->where('issue_to', $this->session->userdata('_uid')) : '';
        $this->session->userdata('_epin') ? $this->db->where('epin', $this->session->userdata('_epin')) : '';

        $config['total_rows'] = $this->db->count_all_results();

        $page = ($this->uri->segment(3)) ? $this->uri->segment(3) : 0;
        $this->pagination->initialize($config);

        $this->db->select('id, epin, amount, issue_to, transfer_by, used_by, used_time')->from('epin')
                 ->limit($config['per_page'], $page);
        $this->session->userdata('_uid') ? $this->db->where('issue_to', $this->session->userdata('_uid')) : '';
        $this->session->userdata('_epin') ? $this->db->where('epin', $this->session->userdata('_epin')) : '';

        $data['epin'] = $this->db->get()->result_array();


        $data['title']      = 'Search e-PINs';
        $data['breadcrumb'] = 'Search e-pin';
        $data['layout']     = 'epin/search_epin.php';
        $this->load->view('admin/index', $data);
    }
    public function Processing_report(){
        $config['base_url']   = site_url('admin/Processing_report');
        $config['per_page']   = 100;
        $config['total_rows'] = $this->db_model->count_all('withdraw_request');
        $page                 = ($this->uri->segment(3)) ? $this->uri->segment(3) : 0;
        $this->pagination->initialize($config);

        $this->db->select('*')->from('withdraw_request')->where('status','Un-Paid')->where('tid !=','')->limit($config['per_page'], $page);

        $data['earning'] = $this->db->get()->result();

        $data['title']      = 'Processing Report';
        $data['breadcrumb'] = 'Processing Report';
        $data['layout']     = 'member/processing_report.php';
        $this->load->view('admin/index', $data);

    }
    public function proceed_report()
    {
        $config['base_url']   = site_url('admin/proceed_report');
        $config['per_page']   = 100;
        $config['total_rows'] = $this->db_model->count_all('withdraw_request');
        $page                 = ($this->uri->segment(3)) ? $this->uri->segment(3) : 0;
        $this->pagination->initialize($config);

        $this->db->select('*')->from('withdraw_request')->where('status','Paid')->limit($config['per_page'], $page);

        $data['earning'] = $this->db->get()->result();

        $data['title']      = 'Processed Report';
        $data['breadcrumb'] = 'Processed Report';
        $data['layout']     = 'member/proceed_report.php';
        $this->load->view('admin/index', $data);

    }

    public function transfer_epin()
    { 

        $this->form_validation->set_rules('amount', 'e-PIN Amount', 'trim|required');
        $this->form_validation->set_rules('to', 'To User ID', 'trim|required');
        $this->form_validation->set_rules('from', 'From User ID', 'trim|required');
        $this->form_validation->set_rules('qty', 'Number of e-PINs', 'trim|required');
        if ($this->form_validation->run() == FALSE) {
            $data['title']      = 'Transfer e-PIN';
            $data['breadcrumb'] = 'Transfer e-pin';
            $data['products'] = $this->db->get('product')->result_array();
            $data['layout']     = 'epin/transfer_epin.php';
            $this->load->view('admin/index', $data);
        } else {
            $amount = $this->common_model->filter($this->input->post('amount'), 'float');
            $to     = $this->common_model->filter($this->input->post('to'));
            $from   = $this->common_model->filter($this->input->post('from'));
            $qty    = $this->common_model->filter($this->input->post('qty'), 'number');

            $recivr_id = $this->db_model->select('id', 'member', array('id' => $to));
            $sender_id = $this->db_model->select('id', 'member', array('id' => $from));
            if($sender_id == '' or $sender_id == null || $recivr_id == '' || $recivr_id == null){
                $this->session->set_flashdata('common_flash', '<div class="alert alert-danger">Invalid user id! kindly re-enter again</div>');
                redirect('admin/transfer_epin');
            }

            $avl_qty = $this->db_model->count_all('epin', array('issue_to' => $from,'amount'   => $amount, 'status'   => 'Un-used'));
            if ($avl_qty < $qty) {
                $this->session->set_flashdata('common_flash', '<div class="alert alert-danger">The User ID have only ' . $avl_qty . ' Un-used epin of ' . config_item('currency') . ' ' . $amount . '.</div>');
                $data['title']      = 'Transfer e-PIN';
                $data['breadcrumb'] = 'Transfer e-pin';
                $data['layout']     = 'epin/transfer_epin.php';
                $this->load->view('admin/index', $data);
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

                $this->session->set_flashdata('common_flash', '<div class="alert alert-success">' . $qty . ' e-PIN transferred from  ' . $this->input->post('from') . ' to ' . $this->input->post('to') . ' of ' . config_item('currency') . ' ' . $amount . '.</div>');
                redirect('admin/transfer_epin');
            }
        }
    }

    public function manage_cat()
    {
        $this->form_validation->set_rules('cat_name', 'Category Name', 'trim|required');

        if ($this->form_validation->run() !== FALSE) {
              if (trim($_FILES['img']['name'] !== "")) {
                $img = '';
                $this->load->library('upload');
                if (!$this->upload->do_upload('img')) {
                    $this->session->set_flashdata('common_flash', '<div class="alert alert-danger">Image not uploaded..<br/>' . $this->upload->display_errors() . '</div>');
                    redirect('admin/manage_cat');
                } 
                else{
                    $image_data = $this->upload->data();
                    $img        = $image_data['file_name'];
                }
            }
            $data = array( 
                'cat_name'    => $this->input->post('cat_name'),
                'parent_cat'  => $this->input->post('parent_cat'),
                'description' => $this->input->post('description'),
                'image'       => $img,
            );
            $this->db->insert('product_categories', $data);
            $this->session->set_flashdata('common_flash', '<div class="alert alert-success">Category Created Successfully.</div>');
            redirect('admin/manage_cat');
        } else {
            $config['base_url']   = site_url('admin/manage_cat');
            $config['per_page']   = 50;
            $config['total_rows'] = $this->db_model->count_all('product_categories');
            $page                 = ($this->uri->segment(3)) ? $this->uri->segment(3) : 0;
            $this->pagination->initialize($config);

            $this->db->select('id, cat_name, parent_cat, description')->from('product_categories')
                     ->order_by('cat_name', 'DESC')->limit($config['per_page'], $page);

            $data['cat'] = $this->db->get()->result_array();
            $this->db->select('id, cat_name');
            $data['parents'] = $this->db->get('product_categories')->result_array();

            $data['title']      = 'Manage Product Categories';
            $data['breadcrumb'] = 'Product Categories';
            $data['layout']     = 'product/categories.php';
            $this->load->view('admin/index', $data);

        }
    }

    public function category()
    {
        $type = $this->uri->segment(3);
        $id   = $this->uri->segment(4);

        switch ($type) {
            case $type == "edit":
                redirect('admin/category_edit/' . $id);
                break;
            case $type == "remove":
                $this->db->where('id', $id);
                $this->db->delete('product_categories');
                $this->session->set_flashdata("common_flash", "<div class='alert alert-success'>Category deleted successfully.</div>");
                redirect('admin/manage_cat');
        }

    }

    public function category_edit()
    {
        $this->form_validation->set_rules('cat_name', 'Category Name', 'trim|required');
        if ($this->form_validation->run() == FALSE) {
            $data['title']      = 'Edit Category';
            $data['breadcrumb'] = 'Edit Category';
            $data['layout']     = 'product/edit_category.php';
            $data['data']       = $this->db_model->select_multi('id, cat_name, parent_cat, description', 'product_categories', array('id' => $this->uri->segment(3)));
            $this->db->select('id, cat_name');
            $data['parents'] = $this->db->get('product_categories')->result_array();
            $this->load->view('admin/index', $data);
        } else {
            $this->db->where('id', $this->input->post('id'));
            $data = array(
                'cat_name'    => $this->input->post('cat_name'),
                'parent_cat'  => $this->input->post('parent_cat'),
                'description' => $this->input->post('description'),
            );
            $this->db->update('product_categories', $data);
            $this->session->set_flashdata('common_flash', '<div class="alert alert-success">Category Updated Successfully.</div>');
            redirect('admin/manage_cat');
        }

    }
    public function expense()
    {
        $config['base_url']   = site_url('admin/expense');
        $config['per_page']   = 50;
        $config['total_rows'] = $this->db_model->count_all('admin_expense');
        $page                 = ($this->uri->segment(3)) ? $this->uri->segment(3) : 0;
        $this->pagination->initialize($config);

        $this->db->order_by('id', 'DESC');
        $this->db->limit($config['per_page'], $page);

        $data['expense']    = $this->db->get('admin_expense')->result();
        $data['title']      = 'Manage Expenses';
        $data['breadcrumb'] = 'Manage Expenses';
        $data['layout']     = 'misc/expenses.php';
        $this->load->view('admin/index', $data);
    }

    public function expense_remove($id)
    {
        $this->db->where('id', $id);
        $this->db->delete('admin_expense');
        $this->session->set_flashdata('common_flash', '<div class="alert alert-success">Expense Entry Deleted Successfully.</div>');
        redirect('admin/expense');
    }

    public function deposit_history()
    {
        $data['title']  = 'Deposit Request List';
        $data['layout'] = 'support/deposit_history.php';
        $this->load->view('admin/base', $data);
    }
    
    public function manage_popup()
    {
        if ($_FILES['popup_image']['name']) {
            $this->load->library('upload');
            $config['upload_path']    = './uploads/popups/';  
            $config['allowed_types']  = 'gif|jpg|png|jpeg|webp';
            $config['max_size']       = 5000; // 2MB
            $config['encrypt_name']   = TRUE;
        
            $this->upload->initialize($config);
        
            if (!$this->upload->do_upload('popup_image')) {
                $error = $this->upload->display_errors();
                $this->session->set_flashdata('common_flash', '<div class="alert alert-danger">'.$error.'</div>');
                redirect('admin/manage_popup');
            } else {
                $upload_data = $this->upload->data();
                $image_name  = $upload_data['file_name'];
        
                $data = array(
                    'img'        => base_url('uploads/popups/'.$image_name),
                    'status'     => 1,  
                );
                $this->db->insert('popups', $data);
        
                $this->session->set_flashdata('common_flash', '<div class="alert alert-success">Popup Added Successfully.</div>');
                redirect('admin/manage_popup');
            } 
        }
        else{
            $this->db->select('*')->from('popups');
            $data['popups']    = $this->db->get()->result_array();
            $data['title']      = 'Manage Popup';
            $data['breadcrumb'] = 'Popup';
            $data['layout']     = 'popup.php';
            $this->load->view('admin/index', $data);
        }
    }

    public function popup_remove($id)
    {
        $popup = $this->db->get_where('popups', ['id' => $id])->row_array();
    
        if ($popup) {
            $image_url     = $popup['img'];
            $base_url      = base_url();
            $relative_path = str_replace($base_url, '', $image_url);
            $image_path    = FCPATH . $relative_path;
    
            if (file_exists($image_path) && is_file($image_path)) {
                unlink($image_path);
            }
            $this->db->delete('popups', ['id' => $id]);
    
            $this->session->set_flashdata('common_flash', '<div class="alert alert-success">Popup deleted successfully.</div>');
        } else {
            $this->session->set_flashdata('error', 'Popup not found.');
        }
        redirect('admin/manage_popup');
    }
    
    public function zero_epin()
    {
        $admin_id = $this->session->admin_id;
        if($admin_id == 1 || $admin_id == 2){ $issue_to = '1001'; } 
        $config['base_url']   = site_url('admin/zero_epin');
        $config['per_page']   = 50;
        $config['total_rows'] = $this->db_model->count_all('epin', array(
            'status'   => 'Un-used',
            'amount'   => 0,
            'issue_to' => $issue_to,
        ));
        $page                 = ($this->uri->segment(3)) ? $this->uri->segment(3) : 0;
        $this->pagination->initialize($config);

        $this->db->select('id, epin, amount, issue_to, generate_time, generate_time,type')->from('epin')->where('status', 'Un-used')->where('amount', 0)->where('issue_to', $issue_to);
        $data['epin'] = $this->db->get()->result_array();
        #print_r($this->db->last_query());die();
        $data['title']  = 'Active Zero E-Pin';
        $data['layout'] = 'epin/zeroepin.php';
        $this->load->view('admin/index', $data);

    }

    public function get_user_name_for_zero_epin($uid = 0)
    {
        $uid = $this->common_model->filter($uid);
        $user = $this->db_model->select_multi('name, activation_type, join_package, join_package_price', 'member', array('id' => $uid));

        if ($user) {

            if ($user->join_package_price == 1999) {
                echo json_encode(array(
                    'status' => 'success',
                    'name' => $user->name,
                    'activation_type' => $user->activation_type,
                    'join_package' => $user->join_package,
                    'join_package_price' => $user->join_package_price,
                ));

            }else{

                echo json_encode(array(
                    'status' => 'success',
                    'name' => $user->name,
                    'activation_type' => $user->activation_type,
                    'join_package' => $user->join_package,
                    'join_package_price' => $user->join_package_price,
                    'message' => 'You cannot activate this user using a zero PIN. The package price is ' . $user->join_package_price . '.'

                ));

            }


        } else {
            echo json_encode(array('status' => 'error', 'message' => 'User Not Found'));
        }
    }

    public function epin_activation_by_member() 
    {
        $userinfo    = $this->db_model->select_multi('max(orderid) as id', 'product_sale');
        $akid        = $userinfo->id + 1;
        $orderid     = $akid;
        $idd         =  $this->input->post('user_id');
        preg_match('/\d+/', $idd, $matches);
        $number      = $matches[0];
        $user_id     = $number;
        $sp_o        = $this->db_model->select('sponsor', 'member', array('id' => $user_id));
        $status2     = $this->db_model->count_all('member', array('sponsor' => $sp_o,'topup >' => 0));
        if($status2 == 2){
            $status2 = 3;
        }
        else{
            $status2 = 2;
        }
        
        $prod_data   =  $this->db_model->select_multi('*', 'product', array('id' => $this->input->post('signup_package')));  
        $epin_value  = $this->db_model->select('amount', 'epin', array('epin'   => trim($this->input->post('epin')),'status' => 'Un-used',));
        $find_user   = $this->db_model->select('id', 'member', array('id' => $user_id));
        $al_active   = $this->db_model->select('signup_package', 'member', array('id' => $user_id));
        $epin        = $this->input->post('epin');

        if($find_user != NULL and $al_active == NULL or $al_active < 0){
           if($epin_value == $prod_data->dealer_price or $epin_value == 0) {
               if($epin_value != 0){
                   $prod_pv = $prod_data->pv;
               } else{
                   $prod_pv = 0;
               }    
               $mypv = $this->db_model->select('mypv', 'member', array('id' => $user_id));
               $data = array(
                   'topup'           => $prod_data->dealer_price,
                   'signup_package'  => $prod_data->id,
                   'epin'            => $epin,
                   'mypv'            => $mypv + $prod_pv,
                   'activation_date' => date('Y-m-d'),
                   'status2'         => $status2,
               );

                
               $this->db->where('id', $user_id);
               $this->db->update('member', $data);

               // chanage epin status
               $data = array(
                   'status'    => 'Used',
                   'used_by'   => $user_id,
                   'used_time' => date('Y-m-d'),
               );
               $this->db->where('epin', $epin);
               $this->db->update('epin', $data);

               $this->load->model('earning'); 
               if($epin_value != 0){
                $this->earning->reg_earning($user_id, $this->db_model->select('sponsor', 'member', array('id' => $user_id)), $prod_data->id);
               }
               /* First purchse earning on topup */
               $data = array(
                   'product_id' => $prod_data->id,
                   'userid'     => $user_id,
                   'cost'       => $prod_data->dealer_price,
                   'date'       => date('Y-m-d'),
                   'order_by'   => 'Member',
                   'orderid'    => $orderid,
                   'pv'         => $prod_data->pv,
                   'type'        => 'topup',
                   'epin_amount' => $epin_value,
               );
               $this->db->insert('product_sale', $data);

               // Insert into product item table
               $data = array(
                   'product_id' => $prod_data->id,
                   'order_id'   => $orderid,
                   'cost'       => $prod_data->dealer_price,
               );
               $this->db->insert('product_item_sale', $data);
            // $this->earning->repurchase($orderid);
            if($this->db_model->count_all('member') <= 100){
                $this->earning->update_legs();
            }
            $this->session->set_flashdata('common_flash', '<div class="alert alert-success">Successfully activated your account.</div>');
            redirect(site_url('admin/zero_epin'));
        }
        else{
            $this->session->set_flashdata('common_flash', '<div class="alert alert-danger">Epin and Package Amount Not Matched.</div>');
            redirect(site_url('admin/zero_epin')); 
        }
        }
        elseif($al_active!=NULL or $al_active > 0){
            $this->session->set_flashdata('common_flash', '<div class="alert alert-danger"> Userid Already Activated.</div>');
            redirect(site_url('admin/zero_epin'));
        }
        elseif($find_user == NULL){
            $this->session->set_flashdata('common_flash', '<div class="alert alert-danger">Invalid Userid.</div>');
            redirect(site_url('admin/zero_epin'));
        }
        else{
            $this->session->set_flashdata('common_flash', '<div class="alert alert-danger">Something Went Wrong Please Try Again later.</div>');
            redirect(site_url('admin/zero_epin'));
        }
    }
     
}

