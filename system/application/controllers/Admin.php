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
            // Also ensure package 2 is activated if not yet active
            $this->_activate_package2_member($userid);
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
            $this->_activate_package2_member($userid);
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
            $this->_activate_package2_member($userid);
            $this->session->set_flashdata('common_flash', '<div class="alert alert-success">New manual access granted to User ID: '.$userid.'</div>');
        }

    } else {
        $this->session->set_flashdata('common_flash', '<div class="alert alert-danger">Error: User ID '.$userid.' not found in Member table.</div>');
    }

    redirect('admin/manual_quiz_approve');
}

private function _activate_package2_member($userid)
{
    $member = $this->db->get_where('member', array('id' => $userid))->row();
    if (!$member) {
        return false;
    }

    $pkg_id = !empty($member->signup_package) ? $member->signup_package : (!empty($member->join_package) ? $member->join_package : 1);

    // Only activate directly for Package 2 (or non-Package 1)
    if ($pkg_id != 1) {
        $is_already_active = ($member->status == 'Active' && !empty($member->activation_date) && $member->topup > 0);
        if (!$is_already_active) {
            // 1. Calculate status2 (Binary Leg Logic)
            $sp_o    = $member->sponsor;
            $count   = $this->db_model->count_all('member', array('sponsor' => $sp_o, 'topup >' => 0));
            $status2 = ($count >= 2) ? 3 : 2;

            // 2. Determine price and PV
            $prod = $this->db->get_where('product', array('id' => $pkg_id))->row();
            $activation_amount = ($prod && floatval($prod->prod_price) > 0) ? floatval($prod->prod_price) : (($prod && floatval($prod->dealer_price) > 0) ? floatval($prod->dealer_price) : 4450);
            $prod_pv = ($prod && isset($prod->pv)) ? floatval($prod->pv) : 0;

            $current_mypv = floatval($member->mypv ?? 0);
            $current_topup = floatval($member->topup ?? 0);
            $current_business = floatval($member->my_business ?? 0);
            $new_mypv = $current_mypv + $prod_pv;
            $new_topup = ($current_topup > 0) ? ($current_topup + $activation_amount) : $activation_amount;
            $new_business = ($current_business > 0) ? ($current_business + $activation_amount) : $activation_amount;

            // 3. Update Member Table to Active & Increment mypv
            $member_update = array(
                'topup'           => $new_topup,
                'my_business'     => $new_business,
                'signup_package'  => $pkg_id,
                'join_package'    => $pkg_id,
                'mypv'            => $new_mypv,
                'activation_date' => date('Y-m-d'),
                'status2'         => $status2,
                'status'          => 'Active'
            );
            $this->db->where('id', $userid)->update('member', $member_update);

            // 4. Generate next Order ID and create activation order
            $max_row = $this->db->query('SELECT MAX(orderid) AS maxid FROM product_sale')->row();
            $gen_orderid = ($max_row && isset($max_row->maxid) && $max_row->maxid > 0) ? ($max_row->maxid + 1) : 1001;

            $sale_data = array(
                'product_id' => $pkg_id,
                'userid'     => $userid,
                'cost'       => $activation_amount,
                'pv'         => $prod_pv,
                'date'       => date('Y-m-d'),
                'order_by'   => 'Admin Approval',
                'orderid'    => $gen_orderid,
                'status'     => 'Processing',
            );
            $this->db->insert('product_sale', $sale_data);

            $item_data = array(
                'product_id' => $pkg_id,
                'order_id'   => $gen_orderid,
                'cost'       => $activation_amount,
            );
            $this->db->insert('product_item_sale', $item_data);

            // 5. Trigger SwarangWellness Business Engine (Earning, Leg Updates, MLM)
            $this->load->model('earning');
            $this->earning->reg_earning($userid, $sp_o, $pkg_id, TRUE, 1);
            $this->earning->update_legs();

            return true;
        }
    }
    return false;
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
        // Join with member and product tables to see the name of the person who paid and their package details
        $this->db->select('quiz_payments.*, member.name, member.phone, member.signup_package, member.join_package, product.prod_name, product.prod_price');
        $this->db->from('quiz_payments');
        $this->db->join('member', 'member.id = quiz_payments.userid');
        $this->db->join('product', 'product.id = member.signup_package', 'left');
        $this->db->order_by('quiz_payments.id', 'DESC');
        
        $data['requests'] = $this->db->get()->result();
        $data['title'] = 'Payment Approvals';
        $data['layout'] = 'quiz_approvals.php';
        $this->load->view('admin/index', $data);
    }
    
    public function update_quiz_status($id, $status) {
        // Status will be 'Approved' or 'Rejected'
        $this->db->where('id', $id);
        $this->db->update('quiz_payments', array('status' => $status));
        
        $payment = $this->db->get_where('quiz_payments', array('id' => $id))->row();
        if ($payment && $status == 'Approved') {
            $activated = $this->_activate_package2_member($payment->userid);
            if ($activated) {
                $this->session->set_flashdata('common_flash', '<div class="alert alert-success">Payment Approved & User ID: '.$payment->userid.' directly ACTIVATED (Package 2 - No Exam Required).</div>');
                redirect('admin/quiz_payment_requests');
                return;
            }
        }
        
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
        $max_row     = $this->db->query('SELECT MAX(orderid) AS maxid FROM product_sale')->row();
        $orderid     = ($max_row && $max_row->maxid > 0) ? ($max_row->maxid + 1) : 1001;

        $idd         = $this->input->post('user_id');
        preg_match('/\d+/', $idd, $matches);
        $user_id     = isset($matches[0]) ? $matches[0] : 0;

        $find_user   = $this->db->get_where('member', array('id' => $user_id))->row();
        $epin_code   = trim($this->input->post('epin'));
        $epin_row    = $this->db->get_where('epin', array('epin' => $epin_code, 'status' => 'Un-used'))->row();
        $epin_value  = $epin_row ? floatval($epin_row->amount) : 0;

        if (!$find_user) {
            $this->session->set_flashdata('common_flash', '<div class="alert alert-danger">Invalid User ID.</div>');
            redirect(site_url('admin/zero_epin'));
            return;
        }

        $ak_global = $this->db_model->select_multi('*', 'global_setting', array('id' => 1));
        $min_active_topup = ($ak_global && isset($ak_global->active_topup) && $ak_global->active_topup > 0) ? floatval($ak_global->active_topup) : 1;
        $prod_sale = $this->db_model->sum('cost', 'product_sale', array('userid' => $user_id));

        if ($prod_sale >= $min_active_topup && !empty($find_user->activation_date)) {
            $this->session->set_flashdata('common_flash', '<div class="alert alert-danger">User ID ' . $user_id . ' is already Activated.</div>');
            redirect(site_url('admin/zero_epin'));
            return;
        }

        if (!$epin_row) {
            $this->session->set_flashdata('common_flash', '<div class="alert alert-danger">Invalid or already used E-Pin.</div>');
            redirect(site_url('admin/zero_epin'));
            return;
        }

        // Determine Package ID
        $package_id = $this->input->post('signup_package');
        if (empty($package_id)) {
            $package_id = !empty($find_user->signup_package) ? $find_user->signup_package : $find_user->join_package;
        }
        if (empty($package_id)) {
            $matching_prod = $this->db->get_where('product', array('dealer_price' => $epin_value))->row();
            $package_id = $matching_prod ? $matching_prod->id : 1;
        }

        $prod_data = $this->db->get_where('product', array('id' => $package_id))->row();
        if (!$prod_data) {
            $prod_data = $this->db->get_where('product', array('id' => 1))->row();
        }

        $activation_cost = $prod_data ? floatval($prod_data->dealer_price) : $epin_value;
        $prod_pv = $prod_data ? $prod_data->pv : 0;
        $prod_id = $prod_data ? $prod_data->id : 1;

        // Calculate status2 (Binary Leg Logic)
        $sp_o    = $find_user->sponsor;
        $count   = $this->db_model->count_all('member', array('sponsor' => $sp_o, 'topup >' => 0));
        $status2 = ($count >= 2) ? 3 : 2;

        $mypv = floatval($find_user->mypv);

        // 1. Order Creation & Order ID Generation (Saved FIRST)
        $sale_data = array(
            'product_id'  => $prod_id,
            'userid'      => $user_id,
            'cost'        => $activation_cost,
            'date'        => date('Y-m-d'),
            'order_by'    => 'Admin',
            'orderid'     => $orderid,
            'pv'          => $prod_pv,
            'type'        => 'topup',
            'epin_amount' => $epin_value,
            'status'      => 'Processing',
        );
        $this->db->insert('product_sale', $sale_data);

        $item_data = array(
            'product_id' => $prod_id,
            'order_id'   => $orderid,
            'cost'       => $activation_cost,
        );
        $this->db->insert('product_item_sale', $item_data);

        // 2. Member Activation Update
        $data = array(
            'topup'           => $activation_cost,
            'signup_package'  => $prod_id,
            'epin'            => $epin_code,
            'mypv'            => $prod_pv,
            'activation_date' => date('Y-m-d'),
            'status2'         => $status2,
            'status'          => 'Active',
        );
        $this->db->where('id', $user_id);
        $this->db->update('member', $data);

        // 3. Change epin status
        $data = array(
            'status'    => 'Used',
            'used_by'   => $user_id,
            'used_time' => date('Y-m-d'),
        );
        $this->db->where('epin', $epin_code);
        $this->db->update('epin', $data);

        // 4. Trigger SwarangWellness Business Engine (Earning, Leg Updates, MLM)
        $this->load->model('earning');
        $this->earning->reg_earning($user_id, $sp_o, $prod_id, TRUE, 1);
        $this->earning->update_legs();

        // 5. Ensure Quiz / Certification is marked Pass
        $chk_quiz = $this->db->get_where('quiz_results', array('userid' => $user_id, 'status' => 'Pass'))->row();
        if (!$chk_quiz) {
            $this->db->insert('quiz_results', [
                'userid'       => $user_id,
                'score'        => 50,
                'status'       => 'Pass',
                'attempt_date' => date('Y-m-d H:i:s')
            ]);
        }

        $this->session->set_flashdata('common_flash', '<div class="alert alert-success">Successfully activated User ID: ' . $user_id . '. Account is now ACTIVE! Order #' . $orderid . ' created.</div>');
        redirect(site_url('admin/zero_epin'));
    }
     
}

