<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Franchisee extends CI_Controller
{

    public function __construct()
    {
        parent::__construct();
        if ($this->session->fran_id == "") {
            redirect(site_url('site/franchisee'));
        }
        $this->load->library('pagination');
    }

  public function index()
{
    $this->db->select('*')->from('franchisee_stock')->where(array('franchisee_id'=> $this->session->fran_id));
    $stock_data = $this->db->get()->result();
    
    $total_stock = 0;
    $sold_stock  = 0;
    foreach($stock_data as $tr){
        $cost = $this->db_model->select('dealer_price','product', array('id' => $tr->product_id));
        $total_stock += $cost * $tr->available_qty;
        $sold_stock  += $cost * $tr->sold_qty;
    }

    // === Filter logic ===
$this->db->select('*');
$this->db->from('product_sale');
$this->db->group_by('orderid');
$this->db->order_by('id', 'DESC'); // Optional: show latest orders first
$this->db->limit(15);
$data['orders'] = $this->db->get()->result_array();

    $data['total_stock'] = $total_stock;
    $data['sold_stock']  = $sold_stock;
    $data['title']       = 'Dashboard';
    $data['breadcrumb']  = 'dashboard';

    $this->load->view('franchisee/index', $data);
}


    public function approve($orderid)
    {
        $userid        = $this->db_model->select('userid', 'fran_req', array('orderid' => $orderid));
        $this->db->select('*')->where('orderid', $orderid);
        $order_data    = $this->db->get('fran_req')->result_array();
        $login_fran_id = $this->session->fran_id;
        $total_amt     = 0;
    
        foreach ($order_data as $tr) {
            $prod_id = $tr['prod_id'];
            $req_qty = $tr['quantity'];
            $login_fran_qty = $this->db_model->select('available_qty', 'franchisee_stock', ['product_id'  => $prod_id, 'franchisee_id' => $login_fran_id ]);
            if ($login_fran_qty === null || $login_fran_qty < $req_qty) {
                $this->session->set_flashdata('common_flash', '<div class="alert alert-danger">Insufficient stock for product ID ' . $prod_id . '</div>');
                redirect('franchisee/purchase_request');
                return;
            }
        }
    
        foreach ($order_data as $tr) {
            $prod_id    = $tr['prod_id'];
            $req_qty    = $tr['quantity'];
            $to_fran_id = $tr['userid'];
    
            $ct = $this->db_model->count_all('franchisee_stock', ['product_id' => $prod_id, 'franchisee_id' => $to_fran_id ]);
            $insert_update_data = [
                'product_id'     => $prod_id,
                'franchisee_id'  => $to_fran_id,
            ];
    
            if ($ct == 0) {
                $insert_update_data['available_qty'] = $req_qty;
                $this->db->insert('franchisee_stock', $insert_update_data);
            } else {
                $last_qty = $this->db_model->select('available_qty', 'franchisee_stock', [ 'product_id' => $prod_id, 'franchisee_id' => $to_fran_id ]);
                $insert_update_data['available_qty'] = $last_qty + $req_qty;
                $this->db->where('franchisee_id', $to_fran_id);
                $this->db->where('product_id', $prod_id);
                $this->db->update('franchisee_stock', $insert_update_data);
            }
    
            $login_fran_qty = $this->db_model->select('available_qty', 'franchisee_stock', ['product_id' => $prod_id, 'franchisee_id' => $login_fran_id ]);
            $sold_fran_qty  = $this->db_model->select('sold_qty', 'franchisee_stock', ['product_id' => $prod_id, 'franchisee_id' => $login_fran_id ]);
            $new_qty        = $login_fran_qty - $req_qty;
            $new_sold_qty   = $sold_fran_qty  + $req_qty;
            $this->db->where('franchisee_id', $login_fran_id);
            $this->db->where('product_id', $prod_id);
            $this->db->update('franchisee_stock', ['available_qty' => $new_qty, 'sold_qty' => $new_sold_qty]);
            $total_amt += $tr['item_price'] * $req_qty;
        }
        $this->db->where('orderid', $orderid);
        $this->db->update('fran_req', ['status' => "Approve"]);
    
        $this->session->set_flashdata('common_flash', '<div class="alert alert-success">Order Approved and Stock Transferred</div>');
        redirect('franchisee/purchase_request');
    }


    public function purchase_request($status = null)
    {
        if($status == ''){
            $this->db->select('*, SUM(item_price * quantity) as total_price');
        }else{
           $this->db->select('*, SUM(item_price * quantity) as total_price')->where('status',$status);
        }
        $this->db->where('show_to', $this->session->fran_id);
        $this->db->from('fran_req');
        $this->db->group_by('orderid');
        $data['req']          = $this->db->get()->result_array();
        $data['title']        = 'Franchisee Requests';
        $data['breadcrumb']   = 'View Franchisee Requests';
        $data['layout']       = 'purchases/list.php';
        $this->load->view('franchisee/index', $data);
    }

    public function view_order($orderid)
    {
        $userid         = $this->db_model->select('userid','fran_req',array('orderid' => $orderid));
        $this->db->select('*')->where('orderid',$orderid);
        $data['orders'] = $this->db->get('fran_req')->result_array();
        $total_amt      = round($this->cart->total());
        $data['total']  = $total_amt;
        $data['title']  = 'Order Details';
        $data['layout'] = 'purchases/view.php';
        $this->load->view('franchisee/index', $data);
    }

    /*
    public function order_deliver($orderid = 1)
    {   echo $orderid;
        $otp           = $this->input->post('otp');
        $tdetail       = ($this->input->post('tdetail')) ? $this->input->post('tdetail') : "N/A" ;
        $order_detail  = $this->db_model->select_multi('*', 'product_sale', array('orderid' => $orderid));

        // if($order_detail->proof != $otp){
        //     $this->session->set_flashdata('common_flash', '<div class="alert alert-danger">OTP missmatched for this order .</div>');
        //     redirect('Franchisee/orders/Processing');
        // }
        $data = array(
            'tid'          => $tdetail,
            'status'       => 'Completed',
            'deliver_date' => date('Y-m-d'),
            
        );
        $this->db->where('orderid', $orderid);
        $this->db->update('product_sale', $data);

        ############ INVOICE ENTRY #################################
        $this->db->select('orderid,product_id, userid, cost, qty,franchisee_id')->from('product_sale')->where(array('orderid'=> $orderid));
        $order_data = $this->db->get()->result();
        foreach($order_data as $order_detail){
            $member_detail= $this->db_model->select_multi('name, address, phone', 'member', array('id' => $order_detail->userid));
            $invoice_name = 'Sale Invoice';
            $user_id      = $order_detail->userid;
            $invoice_date = date('Y-m-d');
            $user_type    = 'Member';
            $company_add  = config_item('company_address');
            $bill_add     = $member_detail->name . "<br/>" . $member_detail->address . "<br/>" . $member_detail->phone;
            $total_amt    = $order_detail->cost;
            $paid_amt     = $order_detail->cost;
            $prod_detail  = $this->db_model->select_multi('*', 'product', array('id' => $order_detail->product_id));
            $item_name    = $prod_detail->prod_name;
            $price        = $prod_detail->dealer_price;
            $tax          = $prod_detail->gst;
            $qty          = $order_detail->qty;
            $orderid      = $order_detail->orderid;
    
            $array  = array($item_name => $price);
            $array2 = array($item_name => $tax);
            $array3 = array($item_name => $qty);
    
            $array  = serialize($array);
            $array2 = serialize($array2);
            $array3 = serialize($array3);
            $params = array(
                'invoice_name'     => $invoice_name,
                'userid'           => $user_id,
                'invoice_data'     => $array,
                'invoice_data_tax' => $array2,
                'invoice_data_qty' => $array3,
                'company_address'  => $company_add,
                'bill_to_address'  => $bill_add,
                'total_amt'        => $total_amt,
                'paid_amt'         => $paid_amt,
                'date'             => $invoice_date,
                'user_type'        => $user_type,
                'orderid'          => $orderid,
            );
            $this->db->insert('invoice', $params);
        }
        ########## END ENTRY #######################################
        $this->session->set_flashdata('common_flash', '<div class="alert alert-success">Order Marked As Delivered.</div>');
        redirect('Franchisee/orders/Processing');
    }*/
    
    public function order_deliver($orderid = 1)
    {   
        #echo "<pre>";print_r($_POST);die();
        $otp           = $this->input->post('otp');
        $tdetail       = ($this->input->post('tdetail')) ? $this->input->post('tdetail') : "N/A" ;
        $order_detail  = $this->db_model->select_multi('*', 'product_sale', array('orderid' => $orderid));

        $selected_franchisee_id = $this->session->fran_id;
        
        #echo $selected_franchisee_id;die();

        ############ INVOICE ENTRY #################################
        $this->db->select('orderid,product_id, userid, cost, qty,franchisee_id')->from('product_sale')->where(array('orderid'=> $orderid));
        $order_data = $this->db->get()->result();

        ### If all ordered products are available at the franchise, the order will be processed for delivery.
            foreach ($order_data as $item):

               $stock = $this->db->get_where('franchisee_stock', ['franchisee_id' => $selected_franchisee_id, 'product_id'  => $item->product_id])->row();

               $prod_name = $this->db_model->select('prod_name', 'product', array('id' => $item->product_id));

                if (!$stock || $stock->available_qty < $item->qty) 
                {
                    $this->session->set_flashdata('common_flash', '<div class="alert alert-danger">Insufficient stock for product: ' . $prod_name . '. Available: ' . ($stock ? $stock->available_qty : 0) . '</div>');
                    redirect('Franchisee/check_order');
                    return;
                }

            endforeach;

        ###

        $data = array(
            'tid'           => $tdetail,
            'status'        => 'Completed',
            'deliver_date'  => date('Y-m-d'),
            'franchisee_id' => $selected_franchisee_id,
            
        );
        $this->db->where('orderid', $orderid);
        $this->db->update('product_sale', $data);
        

        foreach($order_data as $order_detail)
        {   
            #########
                    
                    $stock = $this->db->get_where('franchisee_stock', ['franchisee_id' => $selected_franchisee_id, 'product_id'  => $order_detail->product_id])->row();
                    if ($stock) {
                        $updated_stock = [
                            'available_qty' => $stock->available_qty - $order_detail->qty,
                            'sold_qty'      => $stock->sold_qty + $order_detail->qty
                        ];
                    
                        $this->db->where([
                            'franchisee_id' => $selected_franchisee_id,
                            'product_id'    => $order_detail->product_id
                        ]);
                        $this->db->update('franchisee_stock', $updated_stock);

                        #$fran_inc = $this->db_model->select('fran_inc', 'product', array('id' => $order_detail->product_id)) * $order_detail->qty;
                    }

                    /** 
                     *The franchise assignment logic has been moved to the Franchise Controller for this project only, as per the client’s requirement.
                    * where the order is received along with the user ID and order details, and processed accordingly.
                    * Franchisee wallet handles here
                    */
                        #if($this->db_model->select('franchisee', 'global_setting', array('id' => 1)) == 1){

                            $franch_info = $this->db_model->select_multi('level,state,district,taluka,under_district_id', 'franchisee', array('id' => $selected_franchisee_id));
                            
                            ##########level wise franchisee income Start  
                            if (!empty($franch_info)) {

                                $qty     = $order_detail->qty;
                                $orderid = $order_detail->orderid;
                                
                                ##for level franchise income get
                                $upline_fran_inc = $this->db_model->select('upline_fran_inc', 'product', array('id' => $order_detail->product_id));
                                
                                    // अगर null या empty हो तो डिफ़ॉल्ट "0,0,0" इस्तेमाल करो
                                $arr = explode(",", $upline_fran_inc ?: "0,0,0");

                                $first3_level_inc = array_slice($arr, 0, 3);

                                // प्रत्येक लेवल के कमीशन की calculation, अगर वैल्यू न हो या नॉन-नंबर हो तो 0
                                $district_inc   = intval($first3_level_inc[0] ?? 0) * $qty;
                                $state_inc      = intval($first3_level_inc[1] ?? 0) * $qty;
                                $company_fran_inc  = intval($first3_level_inc[2] ?? 0) * $qty;

                                #

                                /* =========================
                                LEVEL 3 – TALUKA
                                ========================= */
                                if ($franch_info->level == 3) {

                                    // Self → product fran_inc
                                    $fran_inc = $this->db_model->select('fran_inc', 'product', ['id' => $order_detail->product_id]) * $qty;

                                    $this->db->insert('franchisee_earning', [
                                        'userid'  => $selected_franchisee_id,
                                        'amount'  => $fran_inc,
                                        'orderid' => $orderid,
                                        'type'    => 'Franchisee Income',
                                    ]);

                                    // District → ₹5
                                    if (!empty($franch_info->under_district_id)) {
                                        $this->db->insert('franchisee_earning', [
                                            'userid'  => $franch_info->under_district_id,
                                            'amount'  => $district_inc,
                                            'orderid' => $orderid,
                                            'type'    => 'Franchisee Income',
                                            'ref_id'  => $selected_franchisee_id,
                                        ]);
                                    }
                                }

                                /* =========================
                                LEVEL 2 – DISTRICT
                                ========================= */
                                if ($franch_info->level == 2) {

                                    // Self → ₹5
                                    $this->db->insert('franchisee_earning', [
                                        'userid'  => $selected_franchisee_id,
                                        'amount'  => $district_inc,
                                        'orderid' => $orderid,
                                        'type'    => 'Franchisee Income',
                                    ]);
                                }

                                /* =========================
                                LEVEL 1 – STATE
                                ========================= */
                                if ($franch_info->level == 1) {

                                    // Self → ₹3
                                    $this->db->insert('franchisee_earning', [
                                        'userid'  => $selected_franchisee_id,
                                        'amount'  => $state_inc,
                                        'orderid' => $orderid,
                                        'type'    => 'Franchisee Income',
                                    ]);
                                }

                                /* =========================
                                STATE INCOME (FROM TALUKA / DISTRICT)
                                ========================= */
                                if (in_array($franch_info->level, [2, 3])) {

                                    $state_fran = $this->db_model->select('id','franchisee',['state' => $franch_info->state, 'level' => 1]);

                                    if (!empty($state_fran)) {
                                        $this->db->insert('franchisee_earning', [
                                            'userid'  => $state_fran,
                                            'amount'  => $state_inc,
                                            'orderid' => $orderid,
                                            'type'    => 'Franchisee Income',
                                            'ref_id'  => $selected_franchisee_id,
                                        ]);
                                    }
                                }

                                /* =========================
                                company SHOP INCOME (FROM ALL EXCEPT company)
                                ========================= */
                                if (in_array($franch_info->level, [1, 2, 3])) {

                                    $company_fran = $this->db_model->select('id', 'franchisee', ['level' => 4]);

                                    if (!empty($company_fran)) {
                                        $this->db->insert('franchisee_earning', [
                                            'userid'  => $company_fran,
                                            'amount'  =>  $company_fran_inc,
                                            'orderid' => $orderid,
                                            'type'    => 'Franchisee Income',
                                            'ref_id'  => $selected_franchisee_id,
                                        ]);
                                    }
                                }

                                /* =========================
                                LEVEL 4 – company SHOP
                                ========================= */
                                if ($franch_info->level == 4) {

                                    // Self → ₹2
                                    $this->db->insert('franchisee_earning', [
                                        'userid'  => $selected_franchisee_id,
                                        'amount'  => $company_fran_inc,
                                        'orderid' => $orderid,
                                        'type'    => 'Franchisee Income',
                                    ]);
                                }
                            }

                            ##########level wise franchisee income end  

                            $franch_wallet = $this->db_model->select('wallet', 'franchisee', array('id' => $selected_franchisee_id));
                            $prod_dealer_price = $this->db_model->select('dealer_price', 'product', array('id' => $order_detail->product_id));
                            $sale_amount   = $prod_dealer_price * $order_detail->qty;
                            
                            $fdata = array(
                                'wallet'           => $franch_wallet + $sale_amount,
                            );
                            $this->db->where('id', $selected_franchisee_id);
                            $this->db->update('franchisee', $fdata);
                            
                            $franch_amount = $this->db_model->sum('amount', 'frnch_transaction', array('orderid' => $order_detail->orderid));
                            $f_transData = array(
                                'fran_id'    => $selected_franchisee_id,
                                'type'       => 'credit',
                                'amount'     => $sale_amount + $franch_amount,
                                'orderid'    => $order_detail->orderid,
                            );

                            if($this->db_model->count_all('frnch_transaction', array('orderid' => $order_detail->orderid)) == 0)
                            {
                                $this->db->insert('frnch_transaction', $f_transData);
                            } else{
                                $this->db->where('orderid', $order_detail->orderid);
                                $this->db->update('franchisee', $f_transData);
                            }
                        #}
                        ## Franchisee wallet handle code end

            ###############

            $member_detail= $this->db_model->select_multi('name, address, phone', 'member', array('id' => $order_detail->userid));
            $invoice_name = 'Sale Invoice';
            $user_id      = $order_detail->userid;
            $invoice_date = date('Y-m-d');
            $user_type    = 'Member';
            $company_add  = config_item('company_address');
            $bill_add     = $member_detail->name . "<br/>" . $member_detail->address . "<br/>" . $member_detail->phone;
            $total_amt    = $order_detail->cost;
            $paid_amt     = $order_detail->cost;
            $prod_detail  = $this->db_model->select_multi('*', 'product', array('id' => $order_detail->product_id));
            $item_name    = $prod_detail->prod_name;
            $price        = $prod_detail->dealer_price;
            $tax          = $prod_detail->gst;
            $qty          = $order_detail->qty;
            $orderid      = $order_detail->orderid;
    
            $array  = array($item_name => $price);
            $array2 = array($item_name => $tax);
            $array3 = array($item_name => $qty);
    
            $array  = serialize($array);
            $array2 = serialize($array2);
            $array3 = serialize($array3);
            $params = array(
                'invoice_name'     => $invoice_name,
                'userid'           => $user_id,
                'invoice_data'     => $array,
                'invoice_data_tax' => $array2,
                'invoice_data_qty' => $array3,
                'company_address'  => $company_add,
                'bill_to_address'  => $bill_add,
                'total_amt'        => $total_amt,
                'paid_amt'         => $paid_amt,
                'date'             => $invoice_date,
                'user_type'        => $user_type,
                'orderid'          => $orderid,
            );
            $this->db->insert('invoice', $params);

            
        }
        ########## END ENTRY #######################################
        $this->session->set_flashdata('common_flash', '<div class="alert alert-success">Order Marked As Delivered.</div>');
        #redirect('Franchisee/orders/Processing');
        redirect('Franchisee/orders/check_order');
    }

    public function order_view($orderid = 1)
    {
        $userid         = $this->db_model->select('userid','product_sale',array('orderid' => $orderid));
        $this->db->select('*')->where('orderid',$orderid);
        $data['orders'] = $this->db->get('product_sale')->result_array();
        $total_amt      = round($this->cart->total());
        $data['total']  = $total_amt;
        $data['title']  = 'Checkout';
        $data['orderid']  = $orderid;
        $data['phone']  = $this->db_model->select('phone','member',array('id' => $userid));
        $data['layout'] = 'shop/pre_check.php';
        $this->load->view('franchisee/index', $data);
    }

    public function order_view2($orderid = 1)
    {
        $userid         = $this->db_model->select('userid','fran_req',array('orderid' => $orderid));
        $this->db->select('*')->where('orderid',$orderid);
        $data['orders'] = $this->db->get('fran_req')->result_array();
        $total_amt      = round($this->cart->total());
        $data['total']  = $total_amt;
        $data['title']  = 'Checkout';
        $data['layout'] = 'shop/pre_check2.php';
        $this->load->view('franchisee/index', $data);
    }

    public function buy_2($product_id)
    {
       
        $product_data = $this->db_model->select_multi('prod_name, prod_price, qty,image,prod_desc,gst,dealer_price,franchisee_price', 'product', array('id' => $product_id));

        if ($product_data->qty == 0) {
            $this->session->set_flashdata('common_flash', '<div class="alert alert-danger">Stock has less qty.</div>');
            redirect('cart/new_purchase');
        }
        $maxid = 0;
        $row = $this->db->query('SELECT MAX(orderid) AS `maxid` FROM `product_sale`')->row();
        if ($row) {
            $maxid = $row->maxid; 
            $o_id = $maxid + 1 ;
        }

        $discount = $product_data->prod_price - $product_data->dealer_price;
        $datas                          = array(
            'id'    => $product_id,
            'qty'   => 1,
            'prod_price'=> $product_data->prod_price,
            'dealer_price' =>$product_data->dealer_price,
            'gst'   => $product_data->gst,
            'gst_amt' =>$product_data->dealer_price*($product_data->gst/100),
            'price' => $product_data->franchisee_price,
            'product_cost' => $product_data->dealer_price + ($product_data->dealer_price*($product_data->gst/100)),
            'discount' => 0,
            'name'  => $product_data->prod_name,
            'prod_desc'  => $product_data->prod_desc,
            'image' => $product_data->image,
            'o_id' => $o_id,
            );

        
        $this->cart->product_name_rules = '[:print:]';
        $this->cart->insert($datas);
        $this->session->set_flashdata('common_flash', '<div class="alert alert-success">Product Added to Cart. Want to purchase more ?.</div>');
        redirect('franchisee/pre_checkout');
    }

    public function pre_checkout()
    {   
        $total_amt      = round($this->cart->total());
        $data['total']  = $total_amt;
        $data['title']  = 'Checkout';
        $data['layout'] = 'purchases/buy.php';
        $this->load->view('franchisee/index', $data);
    }

    public function logout()
    {
        $this->session->sess_destroy();
        $this->session->set_flashdata('site_flash', '<div class="alert alert-info">You have been logged out !</div>');
        redirect(site_url('site/franchisee'));
    }

    // CORE MEMBER PARTS HERE NOW ############################################################ STARTS :
    
    public function sale_product()
    {
        $this->load->model('earning');
        $this->form_validation->set_rules('pname', 'Product', 'required');
        
        if ($this->form_validation->run() == FALSE) {
            $data['title']  = 'Sale Product';
            $data['layout'] = 'product/sale.php';
            $this->load->view('franchisee/index', $data);
        } else {
            $o_id         = $this->db->select_max('orderid')->get('pre_product_sale')->row()->orderid + 1;
            $user_id      = $this->input->post('userid');
            $order_type   = $this->input->post('order_type');
            $product_ids  = explode(',', $this->input->post('product_ids'));
            $quantities   = explode(',', $this->input->post('quantities'));
            $amounts      = explode(',', $this->input->post('amounts'));
            $total_amount = 0;
            $no           = 0;
    
            if ($this->db_model->count_all('member', array('id' => $user_id)) == 0) {
                $this->session->set_flashdata('common_flash', '<div class="alert alert-danger">Invalid userid entered.</div>');
                redirect('franchisee/sale_product/');
            }
    
            foreach ($product_ids as $prodid) {
                if (!empty($prodid)) {
                    $qty          = $quantities[$no];
                    $amount       = $amounts[$no];
                    $p_id         = $this->db_model->select('id', 'product', array('id' => $prodid));
                    $prod_name    = $this->db_model->select('prod_name', 'product', array('id' => $prodid));
                    $tax          = $this->db_model->select('gst', 'product', array('id' => $prodid));
                    $dealer_price = $this->db_model->select('dealer_price', 'product', array('id' => $prodid));
                    $mypv         = $this->db_model->select('pv', 'product', array('id' => $prodid));
                    $cat_id       = $this->db_model->select('category', 'product', array('id' => $prodid));
                    $data_stock   = $this->db_model->select_multi('id, available_qty, sold_qty', 'franchisee_stock', array('franchisee_id' => $this->session->fran_id, 'product_id' => $prodid,));
    
                    if ($qty > $data_stock->available_qty) {
                        $this->session->set_flashdata('common_flash', '<div class="alert alert-danger">Your stock has fewer units available than entered.</div>');
                        redirect('franchisee/sale_product/');
                    }
    
                    $update_qty = $data_stock->available_qty - $qty;
                    $sold_qty   = $data_stock->sold_qty      + $qty;
                    $update     = array(
                        'available_qty' => $update_qty,
                        'sold_qty'      => $sold_qty,
                    );
                    $this->db->where('id', $data_stock->id);
                    $this->db->update('franchisee_stock', $update);
    
                    $sale_data = array(
                        'orderid'       => $o_id,
                        'product_id'    => $p_id,
                        'cat_id'        => $cat_id,
                        'userid'        => $user_id,
                        'cost'          => $dealer_price * $qty,
                        'franchisee_id' => $this->session->fran_id,
                        'date'          => date('Y-m-d'),
                        'deliver_date'  => date('Y-m-d'),
                        'status'        => 'Processing',
                        'admin_status'  => 'Waiting',
                        'qty'           => $qty,
                        'order_type'    => $order_type,
                        'pv'            => $mypv * $qty,
                    );
                    $this->db->insert('pre_product_sale', $sale_data);
                }
            }
    
            $array  = serialize(array_combine($product_ids, $quantities));
            $array2 = serialize(array_combine($product_ids, $amounts));
            $franchisee_stock_sale_data = array(
                'stock_data'         => $array,
                'stock_data_price'   => $array2,
                'fran_id'            => $this->session->fran_id,
                'date'               => date('Y-m-d'),
                'userid'             => $user_id,
                'orderid'            => $o_id,
            );
            $this->db->insert('franchisee_stock_sale_bill', $franchisee_stock_sale_data);
            $invid = $this->db->insert_id();
    
            $this->session->set_flashdata('common_flash', '<div class="alert alert-success">Sale request send to the admin successfully.</div>');
            redirect('franchisee/sale-history');
        }
    }

	public function calculate_monthly_business($userid)
	{
        $this->db->select('*, SUM(pv) as total_pv')->from('product_sale')->where('DATE_FORMAT(date, "%Y-%m") =', date('Y-m'))->where('userid', $userid)->group_by('userid');
        $data = $this->db->get()->result();
        foreach($data as $tr){
            $count = $this->db_model->count_all('month_business', array('userid' => $tr->userid,'date' => date('Y-m')));
            $data  = array(
                'userid' => $tr->userid,
                'pv'     => $tr->total_pv,
                'date'   => date('Y-m'),
            );
            if($count == 0){
                $this->db->insert('month_business', $data);
            }else{
			    $this->db->where('userid', $tr->userid);
			    $this->db->where('date', date('Y-m'));
			    $this->db->update('month_business', $data);
            }
        
            $array = array(
				'month_business' => $tr->total_pv,
			);
			$this->db->where('id', $tr->userid);
			$this->db->update('member', $array);
        }
	}

    public function new_purchase()
    {
        $this->db->select('id,cat_name,description');
        $data['categories'] = $this->db->get('product_categories')->result();
        $this->db->select('id,prod_name,prod_price,image,dealer_price,franchisee_price')->where('status', 'Selling');
        $data['product_top'] = $this->db->get('product')->result();
        $data['title']       = 'Shop ';
        $data['layout']      = 'buy.php';
        $this->load->view('franchisee/index',$data);
    }

    public function my_stocks()
    {
        $fran_id = $this->session->fran_id;
        $query   = $this->db->query("SELECT product.*, franchisee_stock.* FROM product INNER JOIN franchisee_stock ON product.id = franchisee_stock.product_id WHERE franchisee_stock.franchisee_id = $fran_id");
        $data['products']    = $query->result_array();
        $data['title']       = 'Shop ';
        $data['layout']      = 'shop/buy.php';
        $this->load->view('franchisee/index',$data);
    }

    public function payout()
    {
        $from  = $this->input->post('from') ?? '';  
        $to    = $this->input->post('to') ?? '';  
        $this->db->select('*')->where('userid', $this->session->fran_id);
        if ($from) {
            $this->db->where('DATE(date)', $from);
            // $this->db->where('DATE(date) <=', $to);
        }
        $data['withdraw']        = $this->db->get('fran_withdraw_request')->result_array();
        $data['from'] = $from;
        $data['to']   = $to;
        $data['title']           = 'Payout history';
        $data['layout']          = 'income/payout.php';
        $this->load->view('franchisee/index', $data);
    }

    public function commision_histry() {
        $from  = $this->input->post('from');  
        $to    = $this->input->post('to');  
        $this->db->select('*');
        $this->db->where('userid', $this->session->fran_id);
        if ($from && $to) {
            $this->db->where('date >=', $from)->where('date <=', $to);
        }
        $data['earning'] = $this->db->get('franchisee_earning')->result_array();
        
        $this->db->select('*')->where('franchisee_id', $this->session->fran_id)->where('status', 'Completed');
        $this->db->where('payment_sataus !=', 'Processing')->where('payment_sataus !=', 'failed');
        if ($from && $to) {
            $this->db->where('DATE(deliver_date) >=', $from);
            $this->db->where('DATE(deliver_date) <=', $to);
        }
        $this->db->group_by('orderid');
        $data['orders'] = $this->db->get('product_sale')->result_array();
        
        $this->db->select('*')->where('userid', $this->session->fran_id);
        if ($from && $to) {
            $this->db->where('DATE(date) >=', $from);
            $this->db->where('DATE(date) <=', $to);
        }
        $data['withdrawals']     = $this->db->get('fran_withdraw_request')->result_array();
        $data['start_week_date'] = $from;
        $data['end_week_date']   = $to;
        $data['selected_month']  = $month;
        $data['selected_week']   = $week;
        $data['title']           = 'Commision History';
        $data['layout']          = 'income/commision_histry.php';
        $this->load->view('franchisee/index', $data);
    }

    public function wallet_transaction() {
        $from  = $this->input->post('from');  
        $to    = $this->input->post('to');  
        $this->db->select('*');
        $this->db->where('fran_id', $this->session->fran_id);
        if ($from && $to) {
            $this->db->where('created_at >=', $from)->where('created_at <=', $to);
        }
        $data['earning'] = $this->db->get('frnch_transaction')->result_array();
        $data['start_week_date'] = $from;
        $data['end_week_date']   = $to;
        $data['title']           = 'Wallet History';
        $data['layout']          = 'income/wallet_transaction.php';
        $this->load->view('franchisee/index', $data);
    }

    public function invoice($id)
    {
        $data['result']     = $this->db_model->select_multi('*', 'franchisee_stock_sale_bill', array('orderid' => $id));
        $data['title']      = 'Invoice : #' . $id;
        $data['breadcrumb'] = 'Invoice';
        $data['layout']     = 'product/invoice.php';
        $this->load->view('franchisee/index', $data);
    }
    
    public function invoice_view2($id)
    {
        $this->db->select('*');
        $this->db->where('orderid', $id); 
        $data['result']    = $this->db->get('invoice')->result();
        $data['title']      = 'Invoice : #' . $id;
        $data['id']         = $id;
        $data['breadcrumb'] = 'Invoice';
        $data['layout']     = 'shop/invoice.php';
        $this->load->view('franchisee/index', $data);
    }


    public function print_fran_invoice2($id)
    {
        $this->db->select('*');
        $this->db->where('orderid', $id); 
        $data['result']    = $this->db->get('franchisee_stock_sale_bill')->result();
        $data['id']         = $id;
        $this->load->view('franchisee/shop/invoice_print', $data);
    }

    public function my_invoices()
    {
        $config['base_url']   = site_url('franchisee/my_invoices');
        $config['per_page']   = 50;
        $config['total_rows'] = $this->db_model->count_all('invoice', array(
            'userid'    => $this->session->fran_id,
            'user_type' => 'Franchisee',
        ));
        $page                 = ($this->uri->segment(3)) ? $this->uri->segment(3) : 0;
        $this->pagination->initialize($config);

        $this->db->from('invoice')->where(array('userid'    => $this->session->fran_id,'user_type' => 'Franchisee'))->order_by('id', 'DESC')->limit($config['per_page'], $page);
        $data['invoice']    = $this->db->get()->result();
        $data['title']      = 'My Invoices';
        $data['breadcrumb'] = 'My Invoices';
        $data['layout']     = 'invoice/my_invoices.php';
        $this->load->view('franchisee/index', $data);
    }

    public function invoice_view($id)
    {
        $data['result'] = $this->db_model->select_multi('*', 'invoice', array('id' => $id));
        $this->load->view('franchisee/invoice/print_invoice.php', $data);
    }


    public function print_fran_invoice($id)
    {
        $data['result'] = $this->db_model->select_multi('*', 'franchisee_stock_sale_bill', array('id' => $id));
        $this->load->view('franchisee/product/invoice_print', $data);
    }

    public function sale_history()
    {
        $this->db->select('*');
        $this->db->where(array('franchisee_id' => $this->session->fran_id));
        $this->db->order_by('id', 'DESC');
        $data['data'] = $this->db->get('pre_product_sale')->result();
        $data['title']  = 'Sale History';
        $data['layout'] = 'product/sale_history.php';
        $this->load->view('franchisee/index', $data);
    }

    public function profile()
    {
        $this->form_validation->set_rules('name', 'Name', 'trim|required');
        $this->form_validation->set_rules('phone', 'Phone No', 'trim|required');
    
        $data['result'] = $this->db_model->select_multi('*', 'franchisee', ['id' => $this->session->fran_id]);
    
        if ($this->form_validation->run() == FALSE) {
            $data['title']      = 'My Profile';
            $data['breadcrumb'] = 'Profile';
            $data['layout']     = 'misc/profile.php';
            $this->load->view('franchisee/index', $data);
        } else {
            $array = array(
                'name'         => $this->input->post('name'),
                'state'        => $this->input->post('state'),
                'district'     => $this->input->post('city'),
                'pincode'      => $this->input->post('pincode'),
                'address'      => $this->input->post('address'),
                'email'        => $this->input->post('email'),
                'phone'        => $this->input->post('phone'),
                'gstin'        => $this->input->post('gstin'),
                'adhar'        => $this->input->post('adhar'),
                'pan'          => $this->input->post('pan'),
                'bank_branch'  => $this->input->post('bank_branch'),
                'bank_ifsc'    => $this->input->post('bank_ifsc'),
                'acc_number'   => $this->input->post('acc_number'),
                'bank_name'    => $this->input->post('bank_name'),
            );
    
            if (trim($this->input->post('newpass')) !== "") {
                $array['password'] = $this->input->post('newpass');
            }
    
            $this->db->where('id', $this->session->fran_id);
            $this->db->update('franchisee', $array);
    
            $this->session->set_flashdata('common_flash', '<div class="alert alert-success">Profile updated successfully.</div>');
            redirect('franchisee/profile/');
        }
    }

    public function update()
    {
        $i = 0;
        foreach ($this->cart->contents() as $item) {
            $qty1 = count($this->input->post('qty'));
            for ($i = 0; $i < $qty1; $i++) {
                $data = array(
                    'rowid' => $_POST['rowid'][$i],
                    'qty'   => $_POST['qty'][$i],
                );
                $this->cart->update($data);
            }
    
        }
        $this->session->set_flashdata('common_flash', '<div class="alert alert-success">Cart Updated.</div>');
        redirect('franchisee/pre_checkout');
    }
    
    public function unused_epin()
    {

        $config['base_url']   = site_url('franchisee/unused_epin');
        $config['per_page']   = 50;
        $config['total_rows'] = $this->db_model->count_all('epin', array(
            'status'   => 'Un-used',
            'issue_to' => $this->session->fran_id,
        ));
        $page                 = ($this->uri->segment(3)) ? $this->uri->segment(3) : 0;
        $this->pagination->initialize($config);

        $this->db->select('id, epin, amount, issue_to, generate_time, generate_time, type')->from('epin')->where(array(
                                                                                                                     'status'   => 'Un-used',
                                                                                                                     'issue_to' => $this->session->fran_id,
                                                                                                                 ))
                 ->limit($config['per_page'], $page);

        $data['epin'] = $this->db->get()->result_array();

        $data['title']      = 'Unused e-PINs';
        $data['breadcrumb'] = 'Un-used e-pin';
        $data['layout']     = 'epin/unused.php';
        $this->load->view('franchisee/index', $data);
    }

    public function used_epin()
    {

        $config['base_url']   = site_url('franchisee/used_epin');
        $config['per_page']   = 50;
        $config['total_rows'] = $this->db_model->count_all('epin', array(
            'status'   => 'Used',
            'issue_to' => $this->session->fran_id,
        ));
        $page                 = ($this->uri->segment(3)) ? $this->uri->segment(3) : 0;
        $this->pagination->initialize($config);

        $this->db->select('id, epin, amount, used_by, used_time, type')->from('epin')->where(array(
                                                                                                 'status'   => 'Used',
                                                                                                 'issue_to' => $this->session->fran_id,
                                                                                             ))
                 ->limit($config['per_page'], $page);

        $data['epin'] = $this->db->get()->result_array();

        $data['title']      = 'Used e-PINs';
        $data['breadcrumb'] = 'Used e-pin';
        $data['layout']     = 'epin/used.php';
        $this->load->view('franchisee/index', $data);
    }
    public function add_product()
    {
        $this->form_validation->set_rules('prod_name', 'Product Name', 'trim|required');
        $this->form_validation->set_rules('category', 'Product Category', 'trim|required');
        $this->form_validation->set_rules('prod_price', 'Product Price', 'trim|required');
        $this->form_validation->set_rules('purchase_price', 'Purchase Price', 'trim|required');
        
        if ($this->form_validation->run() == FALSE) {
            $data['title']      = 'Add Product';
            $data['breadcrumb'] = 'Add Product';
            $data['layout']     = 'product/add_product.php';
            $this->db->select('id, cat_name')->order_by('cat_name', 'ASC');
            $data['parents'] = $this->db->get('product_categories')->result_array();
            $this->load->view('franchisee/index', $data);
        } else {
            $prod_name        = $this->input->post('prod_name');
            $category         = $this->input->post('category');
            $prod_price       = $this->common_model->filter($this->input->post('prod_price'), 'float');
            $dealer_price     = $this->common_model->filter($this->input->post('dealer_price'), 'float');
            $purchase_price   = $this->common_model->filter($this->input->post('purchase_price'), 'float');
                
            $prod_desc        = $this->input->post('prod_desc');
            $pv               = $this->input->post('pv');
            $qty              = $this->input->post('qty');
            $gst              = $this->common_model->filter($this->input->post('gst'), 'float');
            $image            = 'default.jpg';
            $show_on_reg_form = $this->input->post('join_form');
            if ($show_on_reg_form !== "Yes") {
                $show_on_reg_form = "No";
            }
            $direct_income   = $this->common_model->filter($this->input->post('direct_income'), 'float');
            $level_income    = $this->input->post('level_income');
            $matching_income = $this->common_model->filter($this->input->post('matching_income'), 'float');
            $capping         = $this->common_model->filter($this->input->post('capping'), 'float');
            $roi             = $this->common_model->filter($this->input->post('roi'), 'float');
            $roi_frequency   = $this->input->post('roi_frequency');
            $roi_limit       = $this->input->post('roi_limit');

            if (trim($_FILES['img']['name'] !== "")) { 

                $this->load->library('upload');

                if (!$this->upload->do_upload('img')) {
                    $this->session->set_flashdata('common_flash', '<div class="alert alert-danger">Image not uploaded. Also select category.<br/>' . $this->upload->display_errors() . '</div>');
                    redirect('franchisee/add_product');
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
            $datap = array(
                'prod_name'       => $prod_name,
                'category'        => $category,
                'prod_price'      => $prod_price,
                'dealer_price'    => $dealer_price,
                'purchase_price'  => $purchase_price,
                'prod_desc'       => $prod_desc,
                'pv'              => $pv,
                'qty'             => $qty,
                'gst'             => $gst,
                'image'           => $image,
                'show_on_regform' => $show_on_reg_form,
                'direct_income'   => $direct_income,
                'level_income'    => $level_income,
                'matching_income' => $matching_income,
                'capping'         => $capping,
                'roi'             => $roi,
                'roi_frequency'   => $roi_frequency,
                'roi_limit'       => $roi_limit,
                'status'          => 'Pending',
                'publish_by'      => $this->session->name,
            );
            //var_dump($data);die();

            $this->db->insert('product', $datap);
            $this->session->set_flashdata('common_flash', '<div class="alert alert-success">Product Added successfully.</div>');
            redirect('franchisee/manage_products');
        }
    }
       public function manage_products()
    {
        $data['title']      = 'Manage Products';
        $data['breadcrumb'] = 'Manage Products';
        $data['layout']     = 'product/manage_products.php';
        $this->db->select('id, cat_name')->order_by('cat_name', 'ASC');
        $data['parents'] = $this->db->get('product_categories')->result_array();
        $this->db->select('id, prod_name, prod_price, gst, image, qty, sold_qty, show_on_regform')
                 ->order_by('prod_name', 'ASC')->where('publish_by',$this->session->name);
        $data['prod'] = $this->db->get('product')->result_array();
        // var_dump($data);die();
        $this->load->view('franchisee/index', $data);

    }
      public function view_prod($id)
    {
    

        $data['title']      = 'Product Detail';
        $data['breadcrumb'] = 'Manage Products';
        $data['layout']     = 'product/view_product.php';
       // $data['data']       = $product;
        $this->load->view('franchisee/index',$data);
    }
      public function edit($id)
    {
        $this->form_validation->set_rules('prod_name', 'Product Name', 'trim|required');
        $this->form_validation->set_rules('category', 'Product Category', 'trim|required');
        $this->form_validation->set_rules('prod_price', 'Product Price', 'trim|required');
        $this->form_validation->set_rules('purchase_price', 'Purchase Price', 'trim|required');

        if ($this->form_validation->run() == FALSE) {
            $product_data_edit      = $this->db_model->select_multi('*', 'product', array('id' => $id . $this->input->post('id')));
          /*  var_dump( $product_data_edit);die();*/
            $data_edit['title']      = 'Edit Product';
            $data_edit['breadcrumb'] = 'Manage Products';
            $data_edit['layout']     = 'product/edit_product.php';
            $data_edit['data']       = $product_data_edit;
            $data_edit['parents']    = $this->db->get('product_categories')->result_array();
           //   var_dump($data_edit);die();
            $this->load->view('franchisee/index', $data_edit);
        } else {
            $prod_name        = $this->input->post('prod_name');
            $category         = $this->input->post('category');
            $prod_price       = $this->common_model->filter($this->input->post('prod_price'), 'float');
            $dealer_price     = $this->common_model->filter($this->input->post('dealer_price'), 'float');
            $purchase_price   = $this->common_model->filter($this->input->post('purchase_price'), 'float');

            $prod_desc        = $this->input->post('prod_desc');
            $pv               = $this->input->post('pv');
            $qty              = $this->input->post('qty');
            $status           = $this->input->post('status');
            $gst              = $this->common_model->filter($this->input->post('gst'), 'float');
            $image            = $old_img = $this->input->post('image');
            $show_on_reg_form = $this->input->post('join_form');
            if ($show_on_reg_form !== "Yes") {
                $show_on_reg_form = "No";
            }
            $direct_income   = $this->common_model->filter($this->input->post('direct_income'), 'float');
            $level_income    = $this->input->post('level_income');
            $matching_income = $this->common_model->filter($this->input->post('matching_income'), 'float');
            $capping         = $this->common_model->filter($this->input->post('capping'), 'float');
            $roi             = $this->common_model->filter($this->input->post('roi'), 'float');
            $roi_frequency   = $this->input->post('roi_frequency');
            $roi_limit       = $this->input->post('roi_limit');

            if (trim($_FILES['img']['name'] !== "")) {

                $this->load->library('upload');

                if (!$this->upload->do_upload('img')) {
                    $this->session->set_flashdata('common_flash', '<div class="alert alert-danger">Image not uploaded. Also select category.<br/>' . $this->upload->display_errors() . '</div>');
                    redirect('franchisee/edit_product/' . $id . $this->input->post('id'));
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
                    if (trim($image_data['file_name']) !== "") {
                        unlink(FCPATH . '/uploads/' . $old_img);
                    }
                }
            }
            $data_p = array(
                'prod_name'       => $prod_name,
                'category'        => $category,
                'prod_price'      => $prod_price,
                'dealer_price'    => $dealer_price,
                'purchase_price'  => $purchase_price,
                'prod_desc'       => $prod_desc,
                'pv'              => $pv,
                'qty'             => $qty,
                'gst'             => $gst,
                'image'           => $image,
                'show_on_regform' => $show_on_reg_form,
                'direct_income'   => $direct_income,
                'level_income'    => $level_income,
                'matching_income' => $matching_income,
                'capping'         => $capping,
                'roi'             => $roi,
                'roi_frequency'   => $roi_frequency,
                'roi_limit'       => $roi_limit,
                'status'          => $status,
            );

            $this->db->where('id', $this->input->post('id'));
            $this->db->update('product', $data_p);
            $this->session->set_flashdata('common_flash', '<div class="alert alert-success">Product Updated successfully.</div>');
            redirect('franchisee/manage_products');
        }
    }
       public function remove($id)
    {
        $count = $this->db_model->count_all('product_sale', array(
            'product_id' => $id,
            'status'     => 'Processing',
        ));
        if ($count > 0) {
            $this->session->set_flashdata('common_flash', '<div class="alert alert-danger">Product Cannot be deleted as there are ' . $count . ' Un-Delivered Orders.</div>');
            redirect('franchisee/manage_products');
        } else {
            $img = $this->db_model->select('image', 'product', array('id' => $id));
            $this->db->where('id', $id);
            $this->db->delete('product');
            unlink(FCPATH . '/uploads/' . $img);
            $this->session->set_flashdata('common_flash', '<div class="alert alert-success">Product Deleted successfully.</div>');
            redirect('franchisee/manage_products');
        }
    }
        public function manage_cat()
    {
        $this->form_validation->set_rules('cat_name', 'Category Name', 'trim|required');

        if ($this->form_validation->run() !== FALSE) {
            $data = array( 
                'cat_name'    => $this->input->post('cat_name'),
                'parent_cat'  => $this->input->post('parent_cat'),
                'description' => $this->input->post('description'),
                'publish_by'  => $this->session->name,

            );
            $this->db->insert('product_categories', $data);
            $this->session->set_flashdata('common_flash', '<div class="alert alert-success">Category Created Successfully.</div>');
            redirect('franchisee/manage_cat');
        } else {
            $config['base_url']   = site_url('franchisee/manage_cat');
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
            $this->load->view('franchisee/index', $data);

        }
    }

    public function category()
    {
        $type = $this->uri->segment(3);
        $id   = $this->uri->segment(4);

        switch ($type) {
            case $type == "edit":
                redirect('franchisee/category_edit/' . $id);
                break;
            case $type == "remove":
                $this->db->where('id', $id);
                $this->db->delete('product_categories');
                $this->session->set_flashdata("common_flash", "<div class='alert alert-success'>Category deleted successfully.</div>");
                redirect('franchisee/manage_cat');

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
            $this->load->view('franchisee/index', $data);
        } else {
            $this->db->where('id', $this->input->post('id'));
            $data = array(
                'cat_name'    => $this->input->post('cat_name'),
                'parent_cat'  => $this->input->post('parent_cat'),
                'description' => $this->input->post('description'),
            );
            $this->db->update('product_categories', $data);
            $this->session->set_flashdata('common_flash', '<div class="alert alert-success">Category Updated Successfully.</div>');
            redirect('franchisee/manage_cat');
        }

    }
        public function sale_details()
    {
        // $this->form_validation->set_rules('cat_name', 'Category Name', 'trim|required');
        // if ($this->form_validation->run() == FALSE) {
        //     $data['title']      = 'Sale Details';
        //     $data['breadcrumb'] = 'Sale Details';
            $data['layout']     = 'product/sale_details.php';
            // $data['data']       = $this->db_model->select_multi('id, name);
            $this->db->select('id, prod_name')->order_by('prod_name', 'ASC');
            $data['parents'] = $this->db->get('product')->result_array();
            $this->load->view('franchisee/index', $data);
        // } else {
            // $this->db->where('id', $this->input->post('id'));
            // $data = array(
            //     'cat_name'    => $this->input->post('cat_name'),
            //     'parent_cat'  => $this->input->post('parent_cat'),
            //     'description' => $this->input->post('description'),
            // );
            // $this->db->update('product_categories', $data);
            // $this->session->set_flashdata('common_flash', '<div class="alert alert-success">Category Updated Successfully.</div>');
            // redirect('franchisee/manage_cat');
        // }

    }
        public function add_quotation_temp()
    {
        $data['id'] = $this->input->post('id');
        $data['product'] = $this->input->post('product');
        $data['price'] = $this->input->post('price');
        $data['quantity'] = $this->input->post('quantity');
        $data['gst_per'] = $this->input->post('gst_per');
        $data['payable_amount'] = $this->input->post('payable_amount');


        $this->db->insert('temp',$data);
       
        $data['record'] = $this->db->get_where("temp", array('id' => $data['id']))->result_array();
         // var_dump($data['id']);die();
        // $data['layout']     = 'product/temp.php';
        $this->load->view('franchisee/product/temp', $data);
    }
    
    public function delete_quotation_temp()
    {
        $id   = $this->input->post('id');
        $temp = $this->db->get_where("temp", array('id' => $id))->result_array();
        $this->db->where('id',$id);
        $this->db->delete("temp");    
        $data['record'] = $this->db->get_where("temp", array('id' => $temp[0]['id']))->result_array();
        $this->load->view('franchisee/product/temp', $data);               
    } 
    
    public function getServiceAmount()
    {
        $product = $this->input->post('product');
        $gst = $this->input->post('gst');
        $data['result'] = $this->db->get_where("product", array('id' => $product))->result_array();    
        echo $data['result'][0]['prod_price'];
    }
    
    public function getServiceGST()
    {
        $product = $this->input->post('product');
        $gst = $this->input->post('gst');
        $data['result'] = $this->db->get_where("product", array('id' => $product))->result_array();        
        echo $data['result'][0]['gst'];
    }   
    
    public function getAMCAmount()
    {
        $quantity = $this->input->post('quantity');
        $product = $this->input->post('product');
        $data['result'] = $this->db->get_where("product", array('id' => $product))->result_array();        
        echo $data['result'][0]['amc_amount'] * $quantity;
    }
   
    public function add_quotation()
    {
        $id = $this->input->post('id');
        $record = $this->db->get_where("temp", array('id' => $id))->result_array();
        $o_id = rand(1000000, 9999999);
        $u_id = rand(1000000, 9999999);
        $cname = $this->input->post('cname');
        $m_id = $this->input->post('userid');
        //var_dump($m_id);die();
         
            if ($cname == 'customer') 
            {
                 $name = $this->input->post('name');
                    $email = $this->input->post('email');
                    $phone = $this->input->post('phone');
                    $address1 = $this->input->post('address1');
                    $address2 = $this->input->post('address2');
                    
               $dataa = array(
                    
                    'id' => $u_id,
                    'name' => $name,
                    'username' => time(),
                    'email' => $email,
                    'phone' => $phone,
                    'join_time'=> date('Y-m-d'),
                    'address' => $address1 . "<br/>" . $address2,
                    'f_type' => $cname,
                
                  );
               
                    $this->db->insert('member', $dataa);
                    foreach($record as $val)
                    {
                       // $data['id'] = $val['id'];
                        $data['product_id'] = $val['product'];
                        $data['cost'] = $val['price'];
                        $data['qty'] = $val['quantity'];
                        $data['franchisee_id'] = $this->session->name;
                        $data['orderid'] = $o_id;
                        $data['userid'] = $u_id;
                        $data['date'] = date('Y-m-d');

                        $this->db->insert('product_sale',$data);
                        
                        $this->db->where('id',$val['id']);
                        $this->db->delete("temp"); 
                    }
            }
            else
            {  
              
                    foreach($record as $val)
                    {
                       /// $data['id'] = $val['id'];
                        $data['product_id'] = $val['product'];
                        $data['cost'] = $val['price'];
                        $data['qty'] = $val['quantity'];
                        $data['franchisee_id'] = $this->session->name;
                        $data['orderid'] = $o_id;
                        $data['userid'] = $m_id;
                        $data['date'] = date('Y-m-d');
//var_dump($data);die();
                        $this->db->insert('product_sale',$data);
                        
                        $this->db->where('id',$val['id']);
                        $this->db->delete("temp"); 
                    }

            }
     

        $this->session->set_flashdata('common_flash', '<div class="alert alert-success">Order placed Successfully.</div>');
            redirect('franchisee/sale_details');
         
    }
    
    
    public function orders($status = null) {
        if($status == ''){
            $this->db->select('*')->where('franchisee_id',$this->session->fran_id);
        }else{
            $this->db->select('*')->where('status',$status)->where('franchisee_id',$this->session->fran_id);
        }
        $this->db->group_by('orderid');
        $data['orders']     = $this->db->get('product_sale')->result_array();
        $data['title']      = 'Orders';
        $data['layout']     = 'shop/orders.php';
        $this->load->view('franchisee/index', $data);
    }
    
    public function old_purchases() {
        $this->db->select('*, SUM(item_price * quantity) AS total_price');
        $this->db->where('userid', $this->session->fran_id);
        $this->db->group_by('orderid');
        $data['orders']     = $this->db->get('fran_req')->result_array();
        $data['title']      = 'Old Purchases';
        $data['layout']     = 'shop/old.php';
        $this->load->view('franchisee/index', $data);
    }
    
    public function checkout2() {
        $farnid = $this->session->fran_id;
        $row    = $this->db->query('SELECT MAX(orderid) AS `maxid` FROM `fran_req`')->row();
        $o_id   = ($row && $row->maxid) ? $row->maxid + 1 : 1;
    
        $total_cart_amount = $this->cart->total();
        $wallet_balance    = $this->db_model->select('wallet', 'franchisee', array('id' => $farnid));
        $cart_items        = $this->cart->contents();
    
        if ($total_cart_amount > $wallet_balance) {
            $this->session->set_flashdata('common_flash', '<div class="alert alert-danger">Insufficient funds in your wallet to complete the purchase.</div>');
            redirect('franchisee/pre_checkout');
            return;
        }
    
        $insert_data     = [];
        $total_deduction = 0;
    
        foreach ($cart_items as $item) {
            // $fstock = $this->db_model->select_multi('id, available_qty', 'franchisee_stock', [
            //     'franchisee_id' => $farnid,
            //     'product_id'    => $item['id']
            // ]);
            // if (!$fstock || $fstock->available_qty === null) {
            //     $this->db->insert('franchisee_stock', [
            //         'franchisee_id' => $farnid,
            //         'product_id'    => $item['id'],
            //         'available_qty' => $item['qty']
            //     ]);
            // } else {
            //     $new_qty = $fstock->available_qty + $item['qty'];
            //     $this->db->where('id', $fstock->id)->update('franchisee_stock', [
            //         'available_qty' => $new_qty
            //     ]);
            // }
            $product_price   = $this->db_model->select('franchisee_price', 'product', ['id' => $item['id']]);
            $item_total      = $product_price * $item['qty'];
            $total_deduction += $item_total;
            $insert_data[] = [
                'prod_id'    => $item['id'],
                'item_price' => $product_price,
                'quantity'   => $item['qty'],
                'userid'     => $farnid,
                'show_to'    => $farnid,
                'level'      => '',
                // 'status'     => 'Approve',
                'orderid'    => $o_id,
            ];
        }
    
        if (!empty($insert_data)) {
            $this->db->insert_batch('fran_req', $insert_data);
        }
    
        $new_balance = $wallet_balance - $total_deduction;
        $this->db->where('id', $farnid)->update('franchisee', ['wallet' => $new_balance]);
    
        $transaction_data = [
            'fran_id' => $farnid,
            'type'    => 'debit',
            'amount'  => $total_deduction,
            'orderid' => $o_id
        ];
    
        if ($this->db_model->count_all('frnch_transaction', ['orderid' => $o_id]) == 0) {
            $this->db->insert('frnch_transaction', $transaction_data);
        } else {
            $this->db->where('orderid', $o_id)->update('frnch_transaction', $transaction_data);
        }
        
        $this->cart->destroy();
        $this->session->set_flashdata('common_flash', '<div class="alert alert-success">Stock purchased successfully. Wallet debited ₹' . number_format($total_deduction, 2) . '</div>');
        redirect('franchisee/old_purchases');
    }

    
    public function checkout() {
        if (trim($_FILES['img']['name'] !== "")) {
            $img = '';
            $this->load->library('upload');
            if (!$this->upload->do_upload('img')) {
                $this->session->set_flashdata('common_flash', '<div class="alert alert-danger">Image not uploaded..<br/>' . $this->upload->display_errors() . '</div>');
                redirect('franchisee/pre_checkout');
            }
            else{
                $image_data = $this->upload->data();
                $img        = $image_data['file_name'];
            }
        }
        if($img == NULL){
            $this->session->set_flashdata('common_flash', '<div class="alert alert-danger">Payment proof not uploaded..<br/></div>');
            redirect('franchisee/pre_checkout');
        }
        else{
            $maxid = 0;
            $o_id  = 0;
            $row   = $this->db->query('SELECT MAX(orderid) AS `maxid` FROM `fran_req`')->row();
            if ($row) {
                $maxid = $row->maxid; 
                $o_id  = $maxid + 1 ;
            }
            $farnid        = $this->session->fran_id;
            $totl          = $this->cart->format_number($this->cart->total()); 
            $insert_data   = array();
            $cart_items    = $this->cart->contents();
            $pre_order     = $this->db_model->count_all('fran_req',array('userid' => $farnid));
            $my_data = $this->db_model->select_multi('*', 'franchisee', array('id' => $farnid));;
            $level   = $this->input->post('level') ?? '';
            if($level == 3){
                $show_to = $this->db_model->select('id', 'franchisee', array('taluka' => $my_data->taluka, 'level' => 3));
            } elseif($level == 2){
                $show_to = $this->db_model->select('id', 'franchisee', array('district' => $my_data->district, 'level' => 2));
            } elseif($level == 1){
                $show_to = $this->db_model->select('id', 'franchisee', array('state'  => $my_data->state, 'level' => 1));
            } else{
                $show_to = 0;
            }
            foreach ($cart_items as $item) {
                $subtotal = $item['subtotal']; 
                $data = array(
                    'prod_id'      => $item['id'],
                    'item_price'   => $this->db_model->select('franchisee_price', 'product', array('id' => $item['id'])), 
                    'quantity'     => $item['qty'], 
                    'userid'       => $farnid,
                    'show_to'      => $show_to,
                    'level'        => $level,
                    'orderid'      => $o_id,
                    'img'          => $img,
                );
                $insert_data[] = $data;
            }
            
            if (!empty($insert_data)) {
                $this->db->insert_batch('fran_req', $insert_data);
                if ($this->db->affected_rows() > 0) {
                    $this->cart->destroy();
                    $this->session->set_flashdata('common_flash', '<div class="alert alert-success">Request Send Successfully !.</div>');
                    redirect('franchisee/old_purchases');
                } else {
                    $this->session->set_flashdata('common_flash', '<div class="alert alert-danger">Failed to insert data into product_sale table.</div>');
                    redirect('franchisee/old_purchases');
                }
            } 
            else {
                echo json_encode(array('status' => 'error', 'message' => 'No data to insert.'));
            }
        }
        
    }
    
    
    ################################
    public function check_order() {
        
        $data['title']      = 'Check Orders';
        $data['layout']     = 'shop/check_order.php';
        $this->load->view('franchisee/index', $data);
    }

    public function search_orders()
    {
        $orderid = $this->input->post('orderid');
        $userid  = $this->input->post('userid');

        if(empty($orderid) || empty($userid)){
            echo json_encode(['status' => false, 'message' => 'Order ID & User ID required']);
            return;
        }

        // Check order exists
        $check = $this->db
            ->where('orderid', $orderid)
            ->where('userid', $userid)
            ->get('product_sale')
            ->row_array();

        if(!$check){
            echo json_encode(['status' => false, 'message' => 'Invalid Order ID or User ID']);
            return;
        }
        // FETCH ORDER DETAILS WITH GROUP BY
        
        $this->db->select('product_sale.orderid,
                   product_sale.userid,
                   member.phone,
                   SUM(product_sale.cost) as total_amount,
                   product_sale.payment_method,
                   product_sale.date,
                   product_sale.status');

        $this->db->from('product_sale');
        $this->db->join('member', 'member.id = product_sale.userid', 'left');
        $this->db->where('product_sale.orderid', $orderid);
        $this->db->where('product_sale.userid', $userid);
        $this->db->group_by('product_sale.orderid');
        $orders = $this->db->get()->result_array();
        echo json_encode(['status' => true, 'data' => $orders]);
    }


}