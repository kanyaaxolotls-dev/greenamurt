<?php
defined('BASEPATH') OR exit('No direct script access allowed');

    class Franch extends CI_Controller
    { 
    
    public function reject($orderid)
    {
        $array = array(
            'status'  => "Reject",
        );
        $this->db->where('orderid', $orderid);
        $this->db->update('fran_req', $array);
        $this->session->set_flashdata('common_flash', '<div class="alert alert-success">Order Rejected</div>');
        redirect('Franch/fran_request');
    }
    
    public function approve($orderid)
    {
        $userid  = $this->db_model->select('userid','fran_req',array('orderid' => $orderid));
        $this->db->select('*')->where('orderid',$orderid);
        $data = $this->db->get('fran_req')->result_array();
        $total_amt = 0;
        foreach($data as $tr){
            $ct   = $this->db_model->count_all('franchisee_stock',array('product_id' => $tr['prod_id'],'franchisee_id' => $tr['userid']));
            $data = array( 
                'product_id'    => $tr['prod_id'],
                'franchisee_id' => $tr['userid'],            
            );
            if($ct == 0){
                $data['available_qty'] = $tr['quantity'];
                $this->db->insert('franchisee_stock', $data);
            }else{
                $last_qty = $this->db_model->select('available_qty', 'franchisee_stock', array('product_id' => $tr['prod_id'], 'franchisee_id' => $tr['userid']));
                $data['available_qty'] = $last_qty + $tr['quantity'];
                $this->db->where('franchisee_id', $tr['userid']);
                $this->db->where('product_id', $tr['prod_id']);
                $this->db->update('franchisee_stock', $data);
            }
            $total_amt += $tr['item_price'] * $tr['quantity'];
        }
        $per_inc = 4 / 100 * $total_amt;
        
        // $array = array(
        //     'userid'    => $userid,
        //     'amount'    => $per_inc,
        //     'orderid'   => $orderid,
        //     'type'      => 'Franchisee Income',
        // );
        // $this->db->insert('franchisee_earning',$array);
        
        $array = array(
            'status'  => "Approve",
        );
        $this->db->where('orderid', $orderid);
        $this->db->update('fran_req', $array);
        
        $this->session->set_flashdata('common_flash', '<div class="alert alert-success">Order Approved</div>');
        redirect('Franch/fran_request');
    }

    public function sale_request($status = null)
    {
        $this->db->select('*');
        if($status){
            $this->db->where('admin_status', $status);
        }
        $this->db->order_by('id', 'DESC');
        $data['data'] = $this->db->get('pre_product_sale')->result();
        $data['title']  = 'Sale History';
        $data['layout'] = 'franchisee/sale_request.php';
        $this->load->view('admin/index', $data);
    }

    public function view_order($orderid)
    {
        $userid         = $this->db_model->select('userid','fran_req',array('orderid' => $orderid));
        $this->db->select('*')->where('orderid',$orderid);
        $data['orders'] = $this->db->get('fran_req')->result_array();
        $total_amt      = round($this->cart->total());
        $data['total']  = $total_amt;
        $data['title']  = 'Order Details';
        $data['layout'] = 'view_order.php';
        $this->load->view('admin/index', $data);
    }
    
    public function fran_request($status = null)
    {
        if($status == ''){
            $this->db->select('*, SUM(item_price * quantity) as total_price');
        }else{
            $this->db->select('*, SUM(item_price * quantity) as total_price')->where('status', $status);
        }
        $this->db->from('fran_req');
        $this->db->group_by('orderid');
        $data['req']        = $this->db->get()->result_array();
        $data['title']      = 'Franchisee Requests';
        $data['breadcrumb'] = 'View Franchisee Requests';
        $data['layout']     = 'fran_req.php';
        $this->load->view('admin/index', $data);
    }

}
?>