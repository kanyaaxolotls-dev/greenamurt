<?php

defined('BASEPATH') OR exit('No direct script access allowed');
include(APPPATH.'libraries/razorpay-php/Razorpay.php');
use Razorpay\Api\Api;
use Razorpay\Api\Errors\SignatureVerificationError;
class Cart extends CI_Controller
{

    public function __construct()
    {
        parent::__construct();
        if ($this->login->check_member() == FALSE) {
            redirect(site_url('site/login'));
        }
        $this->load->library('pagination');
        $this->load->library('cart');
    }

    public function new_purchase() 
    {
        // Fetch Category
        $this->db->select('id,cat_name,description');
        $data['categories'] = $this->db->get('product_categories')->result();

        $this->db->select('*')->where('status', 'Selling');
        #$data['product_top'] = $this->db->get('product')->result();
        $data['product_top'] = $this->db->where('show_on_repurchase', 'Yes')->get('product')->result();

        // Recent product 
        // $this->db->select('*')->limit(8);
        $this->db->order_by('created_date');
        $recent_products = $this->db->get('product')->result();

        $data['recent_products'] = $recent_products;
       
        $data['title']       = 'Buy Product Online ';
        $data['layout']      = 'shop/buy.php';
        $this->load->view('member/index', $data);
    }

    public function show_products() 
    {
        // Fetch Category
        $this->db->select('id,cat_name,description');
        $data['categories'] = $this->db->get('product_categories')->result();

       // Fetch selling products
        $this->db->select('*');
        if($this->uri->segment(3)!= 0){
              $this->db->where(array( 'status'   => 'Selling','category' => $this->uri->segment(3),));
        }else{
              $this->db->where(array('status'   => 'Selling',));
        }
        $data['product_top'] = $this->db->get('product')->result();

        // Recent product 
        $this->db->select('*')->limit(8);
        $this->db->order_by('created_date');
        $recent_products = $this->db->get('product')->result();
        $data['recent_products'] = $recent_products;

        $data['title']   = 'Select a Product Below: ';
        $data['layout']  = 'shop/buy.php'; 
        $this->load->view('member/index', $data);
    }
 
    public function view_product($id) 
    { 
        
        $product_data = $this->db_model->select_multi('*', 'product', array('id' => $id));
        $this->db->select('*')->where('status', 'Selling');
        $this->db->like('prod_name', '%');
        $related_products = $this->db->get('product')->result();

        // Recent product 
        $this->db->select('*')->where('status', 'Selling')->where('show_on_repurchase','Yes');
        $this->db->order_by('created_date');
        $recent_products = $this->db->get('product')->result();
        $data['recent_products'] = $recent_products;
        
        $data['title']      = 'Product Detail';
        $data['breadcrumb'] = 'Manage Products';
        $data['layout']     = 'shop/view_product.php';
        $data['prod_data']       = $product_data;
        $data['related_products'] = $related_products;
        $this->load->view('member/index', $data);
    }

    public function shop_list(){

        $this->db->select('*');
        $shop_list = $this->db->get('franchisee')->result();
      
        $data['title']      = 'Shop List';
        $data['breadcrumb'] = 'Shop List';

        $data['layout']     = 'shop/shop_list.php';
        $data['shop_list'] = $shop_list;
        $this->load->view('member/index', $data);
    }

    public function search() 
    { 
        $skeyword = $this->input->post('search');
        // Fetch Category
        $this->db->select('id,cat_name,description');
        $data['categories'] = $this->db->get('product_categories')->result();

        // Fetch selling products
        $this->db->select('*')->where('status', 'Selling')->where('show_on_repurchase','Yes');
        $this->db->like('prod_name', $skeyword);
        $data['product_top'] = $this->db->get('product')->result();

        // Recent product 
        $this->db->select('*')->where('status', 'Selling')->where('show_on_repurchase','Yes');
        $this->db->order_by('created_date');
        $recent_products = $this->db->get('product')->result();
        
        $this->db->select('*')->where('status', 'Selling')->where('show_on_repurchase','Yes');
        $this->db->like('prod_name', $skeyword);
        $related_products = $this->db->get('product')->result();
        
        $data['title']      = 'Search Result';
        $data['breadcrumb'] = 'Search Result';
        $data['layout']     = 'shop/buy.php';
        $data['recent_products']       = $recent_products;
        $data['related_products'] = $related_products;
        
        $this->load->view('member/index', $data);
    }


    public function buy_2($product_id)
    {
       
        $product_data = $this->db_model->select_multi('prod_name, prod_price, qty,image,prod_desc,gst,dealer_price', 'product', array('id' => $product_id));

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
            'price' => $product_data->dealer_price + $product_data->dealer_price*($product_data->gst/100),
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
        redirect('cart/pre_checkout');
    }



     public function apply_coupon(){

        // $ccode = $this->input->post('ccode');
        // $coupdata = $this->db_model->select_multi('*', 'coupon', array('coupon' => $ccode,'userid'=>$this->session->user_id));

        //         $datas = array(
        //             'discount'   => '5',
        //         );
        //         $this->cart->product_name_rules = '[:print:]';
        //     $this->cart->insert($datas);
        
        
        // $this->session->set_flashdata('common_flash', '<div class="alert alert-success">Cart Updated.</div>');
        // redirect('cart/pre_checkout');

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
        redirect('cart/pre_checkout');

    }


public function pre_checkout()
{
    $total_amt = $this->amount_inword(round($this->cart->total()));
    
    // Get unique cities (districts)
    $data['talukas'] = $this->db->select('taluka')->distinct()->get('franchisee')->result();

    // Send all franchisees (optional – or fetch via AJAX later)
    $data['franchisee'] = $this->db->get('franchisee')->result();

    $data['total'] = $total_amt;
    $data['title']  = 'Checkout';
    $data['layout'] = 'shop/pre_checkout.php';

    $this->load->view('member/index', $data);
}

    function amount_inword(float $number)
    {
        $decimal = round($number - ($no = floor($number)), 2) * 100;
        $hundred = null;
        $digits_length = strlen($no);
        $i = 0;
        $str = array();
        $words = array(0 => '', 1 => 'one', 2 => 'two',
            3 => 'three', 4 => 'four', 5 => 'five', 6 => 'six',
            7 => 'seven', 8 => 'eight', 9 => 'nine',
            10 => 'ten', 11 => 'eleven', 12 => 'twelve',
            13 => 'thirteen', 14 => 'fourteen', 15 => 'fifteen',
            16 => 'sixteen', 17 => 'seventeen', 18 => 'eighteen',
            19 => 'nineteen', 20 => 'twenty', 30 => 'thirty',
            40 => 'forty', 50 => 'fifty', 60 => 'sixty',
            70 => 'seventy', 80 => 'eighty', 90 => 'ninety');
            $digits = array('', 'hundred','thousand','lakh', 'crore');
            while( $i < $digits_length ) {
                $divider = ($i == 2) ? 10 : 100;
                $number = floor($no % $divider);
                $no = floor($no / $divider);
                $i += $divider == 10 ? 1 : 2;
                if ($number) {
                    $plural = (($counter = count($str)) && $number > 9) ? 's' : null;
                    $hundred = ($counter == 1 && $str[0]) ? ' and ' : null;
                    $str [] = ($number < 21) ? $words[$number].' '. $digits[$counter]. $plural.' '.$hundred:$words[floor($number / 10) * 10].' '.$words[$number % 10]. ' '.$digits[$counter].$plural.' '.$hundred;
                } else $str[] = null;
            }
            $Rupees = implode('', array_reverse($str));
            $paise = ($decimal > 0) ? "." . ($words[$decimal / 10] . " " . $words[$decimal % 10]) . ' Paise' : '';
        return ($Rupees ? $Rupees . 'Rupees ' : '') . $paise;
    }


    public function pg_status_message(){
        $this->session->set_flashdata('common_flash', '<div class="alert alert-danger">Faild to add money or payment gateway error!</div>');
        redirect(site_url('cart/pre_checkout'));

    }
    
    
//     public function walletPaymentProcess($o_id, $userid, $wallet, $selected_franchisee_id) {
//         $this->load->model('earning');
// 		$get_balance = $this->db_model->select('balance', $wallet, array('userid' => $this->session->user_id));
// 		$wallet_prod = $this->db_model->select('balance', 'product_wallet', array('userid' => $this->session->user_id));
//         $userinfo    = $this->db_model->select_multi('name,phone,username', 'member', array('id' => $this->session->user_id));
//         $coupon_id   = $this->input->post('coupon');
//         $coupon_data = $this->db_model->select_multi('*', 'coupon', array('id' => $coupon_id));
//         $coupon      = $coupon_data->coupon_amt;
//         $prod_wall   = $this->input->post('prod_wallet');
//         $percent_20  = $this->cart->total() * 0.10;
//         if(empty($selected_franchisee_id) or $selected_franchisee_id == null){
//             $selected_franchisee_id = 1;
//         }
        
//         /** Calculation for Cart To get final amount for next part **/
        
//         if($coupon != NULL and $prod_wall != NULL){
//             $coupn_wallet = $coupon + $wallet_prod_amt;
//             $cart_total   = $this->cart->total() - $coupn_wallet;
//         }
        
//         elseif($coupon != NULL){
//             $cart_total  = $this->cart->total() - $coupon;
//         }
        
//         elseif($prod_wall != NULL){
//             $cart_total  = $this->cart->total() - $wallet_prod_amt;
//         }
        
//         else{
//             $cart_total = $this->cart->total();
//         }
        
//         /***************************************************** END  ***********************************************/

//         if ($get_balance < $cart_total){
//             $add_money = $cart_total - $get_balance; 
//             $this->session->set_flashdata('common_flash', '<div class="alert alert-danger">Your Wallet donot have suficient fund to complete this purchase. Wallet need to have atleast: ' . config_item('currency') . $add_money . '<a href="../gateway/registration_form"> Add Money</a></div>');
//             $this->session->set_userdata('_order_id_', $o_id);
//             $this->session->set_userdata('_user_name_', $userinfo->name);
//             $this->session->set_userdata('_phone_', $userinfo->phone);
//             $this->session->set_userdata('_price_', $add_money);
//             redirect(site_url('cart/pre_checkout'));
//         }
//         else{
//                 $this->session->set_userdata('_order_id_', $o_id);
//                 if($this->db_model->select('franchisee', 'global_setting', array('id' => 1)) == 1){
//                     if ($cart = $this->cart->contents()){
//                         foreach ($cart as $item):
//                             $stock = $this->db->get_where('franchisee_stock', ['franchisee_id' => $selected_franchisee_id, 'product_id'  => $item['id']])->row();
//                             if (!$stock || $stock->available_qty < $item['qty']) {
//                                 $this->session->set_flashdata('common_flash', '<div class="alert alert-danger">Insufficient stock for product: ' . $item['name'] . '. Available: ' . ($stock ? $stock->available_qty : 0) . '</div>');
//                                 redirect(site_url('cart/pre_checkout'));
//                                 return;
//                             }
//                         endforeach;
//                     }
//                 }
//                 $data = array(
//                     'balance' => ($get_balance - $cart_total),                   
//                 );
//                 $this->db->where('userid', $this->session->user_id);
//                 $this->db->update($wallet, $data);
                
//                 if($prod_wall != NULL){ 
//                     $data = array(
//                         'balance' => $wallet_prod - $wallet_prod_amt,                   
//                     );
//                     $this->db->where('userid', $this->session->user_id);
//                     $this->db->update('product_wallet', $data);
//                 }   
//                 if ($cart = $this->cart->contents()){
//                     foreach ($cart as $item):
//                         if($this->db_model->select('franchisee', 'global_setting', array('id' => 1)) == 1){
//                             $stock = $this->db->get_where('franchisee_stock', ['franchisee_id' => $selected_franchisee_id, 'product_id'  => $item['id']])->row();
//                             if (!$stock || $stock->available_qty < $item['qty']) {
//                                 $this->session->set_flashdata('common_flash', '<div class="alert alert-danger">Insufficient stock for product: ' . $item['name'] . '. Available: ' . ($stock ? $stock->available_qty : 0) . '</div>');
//                                 redirect(site_url('cart/pre_checkout'));
//                                 return;
//                             }
                            
//                             if ($stock) {
//                                 $updated_stock = [
//                                     'available_qty' => $stock->available_qty - $item['qty'],
//                                     'sold_qty'      => $stock->sold_qty + $item['qty']
//                                 ];
                            
//                                 $this->db->where([
//                                     'franchisee_id' => $selected_franchisee_id,
//                                     'product_id'    => $item['id']
//                                 ]);
//                                 $this->db->update('franchisee_stock', $updated_stock);
//                             }
//                         }
                        
//                         $ad_cost  = $this->db_model->select('purchase_price', 'product', array('id' => $item['id']));
//                         $category = $this->db_model->select('category', 'product', array('id' => $item['id']));
//                         $pv       = $this->db_model->select('pv', 'product', array('id' => $item['id']));
//                         $fran_inc = $this->db_model->select('fran_inc', 'product', array('id' => $item['id'])) * $item['qty'];
                        
//                         $array = array(
//                             'product_id'       => $item['id'],
//                             'cat_id'           => $category,
//                             'userid'           => $userid,
//                             'qty'              => $item['qty'],
//                             'cost'             => $item['price'] * $item['qty'],
//                             'date'             => date('Y-m-d'),
//                             'gst'              => $item['dealer_price'] * $item['qty'] * $item['gst'] / 100,
//                             'orderid'          => $o_id,
//                             'purchase_cost'    => $cart_total,
//                             'is_repurchase'    => 1,
//                             'pv'               => $pv * $item['qty'],
//                             'franchisee_id'    => $selected_franchisee_id
//                         );
//                         $this->db->insert('product_sale', $array);

//                         $w_transData = array(
//                             'userid'     => $this->session->user_id,
//                             'type'       =>'Debit',
//                             'amount'     => $item['price'] * $item['qty'],
//                             'ref_id'     => $o_id,
//                         );                       
//                         $this->db->insert('wallet_transaction', $w_transData);

//                         $data = array(
//                             'product_id' => $item['id'],
//                             'order_id'   => $o_id,
//                             'cost'       => $item['price'] * $item['qty'],
//                         );
//                         $this->db->insert('product_item_sale', $data);

//                         $mypv  = $this->db_model->select('mypv', 'member', array('id' => $userid)) + ($pv * $item['qty']);
//                         $topup = $this->db_model->select('topup', 'member', array('id' => $userid)) + $item['price'] * $item['qty'];
                        
//                         /*Hided this, as the client said it is only for repurchase, not for ID activation
//                         $data = array(
//                             'topup'           => $topup,
//                             'signup_package'  => $item['id'],
//                             'mypv'            => $mypv,
//                             'activation_date' => date('Y-m-d'),
//                         );
//                         $this->db->where('id', $userid);
//                         $this->db->update('member', $data);*/
                        
//                         $this->load->model('earning'); 
//                         $this->earning->reg_earning($userid, $this->db_model->select('sponsor', 'member', array('id' => $userid)), $item['id'], TRUE, $item['qty']);
                        
//                         /** 
//                          * Franchisee wallet handles here
//                         */
//                         if($this->db_model->select('franchisee', 'global_setting', array('id' => 1)) == 1){
//                             $franch_wallet = $this->db_model->select('wallet', 'franchisee', array('id' => $selected_franchisee_id));
//                             $array2 = array(
//                                 'userid'    => $selected_franchisee_id,
//                                 'amount'    => $fran_inc,
//                                 'orderid'   => $o_id,
//                                 'type'      => 'Franchisee Income',
//                             );
//                             $this->db->insert('franchisee_earning', $array2);
//                             $sale_amount   = $item['price'] * $item['qty'];
//                             $fdata = array(
//                                 'wallet'           => $franch_wallet + $sale_amount,
//                             );
//                             $this->db->where('id', $selected_franchisee_id);
//                             $this->db->update('franchisee', $fdata);
                            
//                             $franch_amount = $this->db_model->sum('amount', 'frnch_transaction', array('orderid' => $o_id));
//                             $f_transData = array(
//                                 'fran_id'    => $selected_franchisee_id,
//                                 'type'       => 'credit',
//                                 'amount'     => $sale_amount + $franch_amount,
//                                 'orderid'    => $o_id,
//                             );
//                             if($this->db_model->count_all('frnch_transaction', array('orderid' => $o_id)) == 0){
//                                 $this->db->insert('frnch_transaction', $f_transData);
//                             } else{
//                                 $this->db->where('orderid', $o_id);
//                                 $this->db->update('franchisee', $f_transData);
//                             }
//                         }
//                     endforeach;
//                 } 
//             $this->session->unset_userdata('_user_id_');
//             $this->session->set_flashdata('common_flash', '<div class="alert alert-success">Thank you for Purchasing with us</div>');
//             redirect('cart/checkout_complete');
//         }
       
// 	}

    public function walletPaymentProcess($o_id, $userid, $wallet, $selected_franchisee_id) 
    {
        $this->load->model('earning');
		$get_balance = $this->db_model->select('balance', $wallet, array('userid' => $this->session->user_id));
		$wallet_prod = $this->db_model->select('balance', 'product_wallet', array('userid' => $this->session->user_id));
        $userinfo    = $this->db_model->select_multi('name,phone,username', 'member', array('id' => $this->session->user_id));
        $coupon_id   = $this->input->post('coupon');
        $coupon_data = $this->db_model->select_multi('*', 'coupon', array('id' => $coupon_id));
        $coupon      = $coupon_data->coupon_amt;
        $prod_wall   = $this->input->post('prod_wallet');
        $percent_20  = $this->cart->total() * 0.10;
        /* The franchise assignment logic has been moved to the Franchise Controller for this project only, as per the client’s requirement.
        if(empty($selected_franchisee_id) or $selected_franchisee_id == null){
            $selected_franchisee_id = 1;
        }*/
        
        /** Calculation for Cart To get final amount for next part **/
        
        if($coupon != NULL and $prod_wall != NULL){
            $coupn_wallet = $coupon + $wallet_prod_amt;
            $cart_total   = $this->cart->total() - $coupn_wallet;
        }
        
        elseif($coupon != NULL){
            $cart_total  = $this->cart->total() - $coupon;
        }
        
        elseif($prod_wall != NULL){
            $cart_total  = $this->cart->total() - $wallet_prod_amt;
        }
        
        else{
            $cart_total = $this->cart->total();
        }
        
        /***************************************************** END  ***********************************************/

        if ($get_balance < $cart_total){
            $add_money = $cart_total - $get_balance; 
            $this->session->set_flashdata('common_flash', '<div class="alert alert-danger">Your Wallet donot have suficient fund to complete this purchase. Wallet need to have atleast: ' . config_item('currency') . $add_money . '<a href="../gateway/registration_form"> Add Money</a></div>');
            $this->session->set_userdata('_order_id_', $o_id);
            $this->session->set_userdata('_user_name_', $userinfo->name);
            $this->session->set_userdata('_phone_', $userinfo->phone);
            $this->session->set_userdata('_price_', $add_money);
            redirect(site_url('cart/pre_checkout'));
        }
        else{
                $this->session->set_userdata('_order_id_', $o_id);
                #The franchise assignment logic has been moved to the Franchise Controller for this project only, as per the client’s requirement.
                /*
                if($this->db_model->select('franchisee', 'global_setting', array('id' => 1)) == 1){
                    if ($cart = $this->cart->contents()){
                        foreach ($cart as $item):
                            $stock = $this->db->get_where('franchisee_stock', ['franchisee_id' => $selected_franchisee_id, 'product_id'  => $item['id']])->row();
                            if (!$stock || $stock->available_qty < $item['qty']) {
                                $this->session->set_flashdata('common_flash', '<div class="alert alert-danger">Insufficient stock for product: ' . $item['name'] . '. Available: ' . ($stock ? $stock->available_qty : 0) . '</div>');
                                redirect(site_url('cart/pre_checkout'));
                                return;
                            }
                        endforeach;
                    }
                }*/
                ###########
                $data = array(
                    'balance' => ($get_balance - $cart_total),                   
                );
                $this->db->where('userid', $this->session->user_id);
                $this->db->update($wallet, $data);
                
                if($prod_wall != NULL){ 
                    $data = array(
                        'balance' => $wallet_prod - $wallet_prod_amt,                   
                    );
                    $this->db->where('userid', $this->session->user_id);
                    $this->db->update('product_wallet', $data);
                }   
                if ($cart = $this->cart->contents()){
                    foreach ($cart as $item):

                    ## Franchisee stock handles code start 
                    ##The franchise assignment logic has been moved to the Franchise Controller for this project only, as per the client’s requirement.
                    /*15-12-2025 Akki
                        if($this->db_model->select('franchisee', 'global_setting', array('id' => 1)) == 1){
                            $stock = $this->db->get_where('franchisee_stock', ['franchisee_id' => $selected_franchisee_id, 'product_id'  => $item['id']])->row();
                            if (!$stock || $stock->available_qty < $item['qty']) {
                                $this->session->set_flashdata('common_flash', '<div class="alert alert-danger">Insufficient stock for product: ' . $item['name'] . '. Available: ' . ($stock ? $stock->available_qty : 0) . '</div>');
                                redirect(site_url('cart/pre_checkout'));
                                return;
                            }
                            
                            if ($stock) {
                                $updated_stock = [
                                    'available_qty' => $stock->available_qty - $item['qty'],
                                    'sold_qty'      => $stock->sold_qty + $item['qty']
                                ];
                            
                                $this->db->where([
                                    'franchisee_id' => $selected_franchisee_id,
                                    'product_id'    => $item['id']
                                ]);
                                $this->db->update('franchisee_stock', $updated_stock);
                            }
                        }

                        $fran_inc = $this->db_model->select('fran_inc', 'product', array('id' => $item['id'])) * $item['qty'];
                        #15-12-2025 Akki End
                    */
                    ## Franchisee stock handles code end 

                        $ad_cost  = $this->db_model->select('purchase_price', 'product', array('id' => $item['id']));
                        $category = $this->db_model->select('category', 'product', array('id' => $item['id']));
                        $pv       = $this->db_model->select('pv', 'product', array('id' => $item['id']));
                        #$fran_inc = $this->db_model->select('fran_inc', 'product', array('id' => $item['id'])) * $item['qty'];
                        
                        $array = array(
                            'product_id'       => $item['id'],
                            'cat_id'           => $category,
                            'userid'           => $userid,
                            'qty'              => $item['qty'],
                            'cost'             => $item['price'] * $item['qty'],
                            'date'             => date('Y-m-d'),
                            'gst'              => $item['dealer_price'] * $item['qty'] * $item['gst'] / 100,
                            'orderid'          => $o_id,
                            'purchase_cost'    => $cart_total,
                            'is_repurchase'    => 1,
                            'pv'               => $pv * $item['qty'],
                            //'franchisee_id'    => $selected_franchisee_id
                        );
                        $this->db->insert('product_sale', $array);

                        $w_transData = array(
                            'userid'     => $this->session->user_id,
                            'type'       =>'Debit',
                            'amount'     => $item['price'] * $item['qty'],
                            'ref_id'     => $o_id,
                        );                       
                        $this->db->insert('wallet_transaction', $w_transData);

                        $data = array(
                            'product_id' => $item['id'],
                            'order_id'   => $o_id,
                            'cost'       => $item['price'] * $item['qty'],
                        );
                        $this->db->insert('product_item_sale', $data);

                        $mypv  = $this->db_model->select('mypv', 'member', array('id' => $userid)) + ($pv * $item['qty']);
                        $topup = $this->db_model->select('topup', 'member', array('id' => $userid)) + $item['price'] * $item['qty'];
                        /*Hided this, as the client said it is only for repurchase, not for ID activation
                        $data = array(
                            'topup'           => $topup,
                            'signup_package'  => $item['id'],
                            'mypv'            => $mypv,
                            'activation_date' => date('Y-m-d'),
                        );
                        $this->db->where('id', $userid);
                        $this->db->update('member', $data); */
                        
                        $this->load->model('earning'); 
                        $this->earning->reg_earning($userid, $this->db_model->select('sponsor', 'member', array('id' => $userid)), $item['id'], TRUE, $item['qty']);
                        
                        /** 
                         *
                         *The franchise assignment logic has been moved to the Franchise Controller for this project only, as per the client’s requirement.
                         * where the order is received along with the user ID and order details, and processed accordingly.
                         * Franchisee wallet handles here
                        */
                        /* 15-12-2025 Akki start
                        if($this->db_model->select('franchisee', 'global_setting', array('id' => 1)) == 1){
                            $franch_wallet = $this->db_model->select('wallet', 'franchisee', array('id' => $selected_franchisee_id));
                            $array2 = array(
                                'userid'    => $selected_franchisee_id,
                                'amount'    => $fran_inc,
                                'orderid'   => $o_id,
                                'type'      => 'Franchisee Income',
                            );
                            $this->db->insert('franchisee_earning', $array2);
                            $sale_amount   = $item['price'] * $item['qty'];
                            $fdata = array(
                                'wallet'           => $franch_wallet + $sale_amount,
                            );
                            $this->db->where('id', $selected_franchisee_id);
                            $this->db->update('franchisee', $fdata);
                            
                            $franch_amount = $this->db_model->sum('amount', 'frnch_transaction', array('orderid' => $o_id));
                            $f_transData = array(
                                'fran_id'    => $selected_franchisee_id,
                                'type'       => 'credit',
                                'amount'     => $sale_amount + $franch_amount,
                                'orderid'    => $o_id,
                            );
                            if($this->db_model->count_all('frnch_transaction', array('orderid' => $o_id)) == 0){
                                $this->db->insert('frnch_transaction', $f_transData);
                            } else{
                                $this->db->where('orderid', $o_id);
                                $this->db->update('franchisee', $f_transData);
                            }
                        } */
                        ## Franchisee wallet handle code end 15-12-2025 Akki

                    endforeach;
                } 
            $this->session->unset_userdata('_user_id_');
            $this->session->set_flashdata('common_flash', '<div class="alert alert-success">Thank you for Purchasing with us</div>');
            redirect('cart/checkout_complete');
        }
       
	}

     public function razorpayPaymentProcess($o_id,$postData) {
		   
		$this->load->library('razorpay');
		$userinfo = $this->db_model->select_multi('name,phone,username,email', 'member', array('id' => $this->session->user_id));
		$ubank=$this->db_model->select_multi('btc_address', 'member_profile', array('userid' => $e->userid)); $ubank=$this->db_model->select_multi('*', 'member_profile', array('userid' => $e->userid));
       
		$orderData = array();
		$orderData['receipt'] = $o_id;
		$orderData['amount'] = isset($postData['amount'])?$postData['amount']:0;
		$orderData['prefill_name'] = isset($userinfo->name)?$userinfo->name:'';
		$orderData['prefill_email'] = isset($userinfo->email)?$userinfo->email:'';
		$orderData['prefill_contact'] = isset($userinfo->phone)?$userinfo->phone:'';
		$orderData['notes_address'] = isset($ubank->btc_address)?$ubank->btc_address:'';
		 
		$this->session->set_userdata('_user_name_', $userinfo->name);
		$this->session->set_userdata('_phone_', $userinfo->phone);
		$this->session->set_userdata('_price_', $postData['amount']);
		$data['payment_method'] = 'razorpay';
		$data['payment_sataus'] = 'failed';
		$data['orderid'] = $o_id;
        $saleIds = $this->paymentAndSales($data);
		$orderData['shopping_order_id'] = implode(",",$saleIds);

		$this->razorpay->processPayment($orderData);
	 }	 

    function checkout($o_id)
    {
        $selected_franchisee_id = $this->input->post('franchisee_id');

		$paymentmethod = $this->input->post('paymentmethod');
		$userid        = $this->input->post('userid');
		$check_user    = $this->db_model->count_all('member', array('id' => $userid));
		$check_eligibl = $this->db_model->count_all('product_sale', array('userid' => $userid, 'product_id' => 2));
// 		echo "<pre>";
// 		print_r($this->input->post());
// 		echo "</pre>";
// 		die;
		if ($check_user == 0) {
			$this->session->set_flashdata('common_flash', '<div class="alert alert-danger">Invalid userid</div>');
			redirect('cart/pre_checkout');
		}
		
// 		if ($check_eligibl == 0) {
// 			$this->session->set_flashdata('common_flash', '<div class="alert alert-danger">Activate your id first by pakcage 2 of 6400 to purchase this item</div>');
// 			redirect('cart/pre_checkout');
// 		}
		
		if (empty($paymentmethod) || empty($o_id) ) {
			$this->session->set_flashdata('common_flash', '<div class="alert alert-success">The payment method / Order # was missing</div>');
			redirect('cart/pre_checkout');
		}
		
		if($paymentmethod =='wallet' or $paymentmethod =='product_wallet'){
			$this->walletPaymentProcess($o_id,$userid,$paymentmethod,$selected_franchisee_id);
		}
		else if($paymentmethod =='razorpay') {
			$this->razorpayPaymentProcess($o_id,$this->input->post());
		}
		else{
			redirect('cart/pre_checkout');
		}	
		
        
    }
	
	public function razorpayverify()
    {
		//$keyId = 'rzp_test_eop7PF7LKTnUyZ';
		//$keySecret = 'rcud74C7SaKDk1SrnFbQxR6P';
		$this->config->load('pg');
		//echo config_item('RAZOR_KEY_ID');exit;
		$keyId = config_item('RAZOR_KEY_ID');
        $keySecret = config_item('RAZOR_KEY_SECRET'); //RAZOR_KEY_SECRET;
		$success = true;

		$error = "Payment Failed";
		/*echo "<pre>";
		print_r($_POST);
		exit;*/

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
            
			redirect('cart/checkout_complete');	 
				 
		}
		else
		{
			$html = "<p>Your payment failed</p>
				 <p>{$error}</p>";
			$this->session->set_flashdata('common_flash', '<div class="alert alert-danger">'.$html.'</div>');
			redirect('cart/checkout_failed');	
		}

		//echo $html;
    }		
	
	public function updatePaymentStatus($data) {
		$sale_ids = isset($data['sale_ids'])?explode(',',$data['sale_ids']):'';
		$payment_sataus = isset($data['payment_sataus'])?$data['payment_sataus']:'';
		$razorpay_payment_id = isset($data['razorpay_payment_id'])?$data['razorpay_payment_id']:'';
		$razorpay_order_id = isset($data['razorpay_order_id'])?$data['razorpay_order_id']:'';
		$this->db->trans_start();

		foreach ($sale_ids as $saleId){
			//$query = $this->db->where('id', $saleId)->from('cron')->get();	
			//$this->db->set('last_run_at', 'now()', false)->where('id', $row->id)->update('cron');
			$this->db->where('id', $saleId);
			$this->db->update('product_sale', array('payment_sataus' => $payment_sataus,'razorpay_payment_id' => $razorpay_payment_id,'razorpay_order_id'=>$razorpay_order_id ));
	    }		
        $this->db->trans_complete();
		
		return $this->db->trans_status();
		
		
	}
	
	public function paymentAndSales($data) {
		
		 if ($cart = $this->cart->contents())
         {
            $saleIds = array();
            foreach ($cart as $item):
              $array = array(
                    'product_id' => $item['id'],
                    'userid'     => $this->session->user_id,
                    'qty'        => $item['qty'],
                    'cost'       => $item['price'] * $item['qty'],
                    'date'       => date('Y-m-d'),
                    'orderid'    => $data['orderid'],
					'payment_method' =>$data['payment_method'],
					'payment_sataus' =>$data['payment_sataus']

                );
              
                $this->db->insert('product_sale', $array);
                $saleIds[] = $this->db->insert_id();
				 
            endforeach;

        }
        $this->session->unset_userdata('_user_id_');
        return $saleIds;        

       

	}
	
	public function checkout_failed()
    {
        $data['title']  = 'Payment Failed';
        $data['layout'] = 'shop/checkout_failed.php';
        $this->load->view('member/index', $data);
    }
    
    public function checkout_complete()
    {

        $this->db->select('*'); 
        $this->db->group_by('product_sale.orderid');
         $this->db->where('product_sale.orderid', $this->session->_order_id_);
        $this->db->from('product_sale');
        $this->db->join('member', 'product_sale.userid = member.id');
        $data['invoice_data'] = $this->db->get()->result_array();

        $data['title']  = 'Invoice';
        $data['layout'] = 'shop/checkout_complete.php';
        $this->load->view('member/index', $data);
    }

     public function my_customers()
    {
        $this->db->select('*');
        $this->db->select_sum('cost');
        $this->db->where('member.sponsor',$this->session->user_id);
        $this->db->group_by('orderid');
        $this->db->from('product_sale');
        $this->db->join('member', 'product_sale.userid = member.id');

        $data['purchase_data'] = $this->db->get()->result_array();

        $data['title']  = 'My Customers';
        $data['layout'] = 'shop/my_customers.php';
        $this->load->view('member/index', $data);

    }


    public function old_purchase()
    {
        $config['base_url']   = site_url('cart/old_purchase');
        $config['per_page']   = 50;
        $config['total_rows'] = $this->db_model->count_all('product_sale', array('userid' => $this->session->user_id));
        $page = ($this->uri->segment(3)) ? $this->uri->segment(3) : 0;
        $this->pagination->initialize($config);

        $this->db->select('*')->from('product_sale')
                 ->where('userid', $this->session->user_id)->limit($config['per_page'], $page);

        $data['data']   = $this->db->get()->result();


        $this->db->select('*'); 
        $this->db->select_sum('cost');
        $this->db->group_by('orderid');
        $this->db->where('member.id', $this->session->user_id);
        $this->db->from('member');
        $this->db->join('product_sale', 'product_sale.userid = member.id');
       
        $data['purchase_data'] = $this->db->get()->result_array();
        $data['title']  = 'My Old Purchases';
        $data['layout'] = 'shop/my_purchases.php';
        $this->load->view('member/index', $data);

    }
   
}
