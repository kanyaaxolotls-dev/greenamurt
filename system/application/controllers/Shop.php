<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Shop extends CI_Controller
{

    public function __construct()
    {
        parent::__construct();
      
        $this->load->library('pagination');
        $this->load->library('cart');
    } 

    public function new_purchase()
    {
        $this->db->select('id,cat_name,description');
        $data['categories'] = $this->db->get('product_categories')->result();
        $this->db->select('id,prod_name,prod_price,image,dealer_price')->where('status', 'Selling')->limit(10);
        $data['product_top'] = $this->db->get('product')->result();
        $data['title']       = 'Shop ';
        // $data['layout']      = 'buy.php';
        $this->load->view('shop/index',$data);

    }
 
    public function show_products()
    {

        $this->db->select('id,cat_name,description');
        $data['categories'] = $this->db->get('product_categories')->result();
     

        $this->db->select('id,prod_name,prod_price,image,dealer_price');
        $this->db->where(array(
                             'status'   => 'Selling',
                             'category' => $this->uri->segment(3),
                         ));
        $data['product'] = $this->db->get('product')->result();
   

        $data['title']   = 'Select a Product Below: ';
        $data['layout']  = 'buy.php';
        $this->load->view('shop/base', $data);
    }

    public function buy_2($product_id)
    {
        $product_data = $this->db_model->select_multi('prod_name, prod_price, qty, gst', 'product', array('id' => $product_id));

        if ($product_data->qty == 0) {
            $this->session->set_flashdata('common_flash', '<div class="alert alert-danger">Stock has less qty.</div>');
            redirect('shop/new_purchase');
        }
        $datas                          = array(
            'id'    => $product_id,
            'qty'   => 1,
            'price' => $product_data->prod_price + $product_data->gst,
            'name'  => $product_data->prod_name,
        );
        $this->cart->product_name_rules = '[:print:]';
        $this->cart->insert($datas);
        $this->session->set_flashdata('common_flash', '<div class="alert alert-success">Product Added to Cart. Want to purchase more ?.</div>');
        redirect('shop/pre_checkout');
    }

    public function pre_checkout()
    {
         $this->db->select('id,cat_name,description');
        $data['categories'] = $this->db->get('product_categories')->result();
     

        $data['title']  = 'Checkout';
        $data['layout'] = 'pre_checkout.php';
        $this->load->view('shop/base', $data);
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
        redirect('shop/pre_checkout');

    }

    function checkout()
    {    
        if ($this->login->check_member() == FALSE) {
            redirect(site_url('site/login'));
            return;
        }

        $cart_total = (float)$this->cart->total();
        if ($cart_total <= 0) {
            $this->session->set_flashdata('common_flash', '<div class="alert alert-danger">Your shopping cart is empty.</div>');
            redirect('shop/pre_checkout');
            return;
        }

        $target_table = (config_item('wallet_type') == "Yes") ? 'product_wallet' : 'wallet';
        $w_row = $this->db->get_where($target_table, array('userid' => $this->session->user_id))->row();
        $get_balance = $w_row ? (float)$w_row->balance : 0.0;

        if ($get_balance < $cart_total) {
            $this->session->set_flashdata('common_flash', '<div class="alert alert-danger">Oops! You do not have sufficient funds in your ' . ($target_table == 'product_wallet' ? 'Product Wallet' : 'E-Wallet') . '. Required: ' . config_item('currency') . number_format($cart_total, 2) . ', Available: ' . config_item('currency') . number_format($get_balance, 2) . '</div>');
            redirect('shop/pre_checkout');
            return;
        }

        $this->db->trans_start();

        // 1. Deduct balance
        $new_bal = round($get_balance - $cart_total, 2);
        $this->db->where('userid', $this->session->user_id)->update($target_table, array('balance' => $new_bal));

        // 2. Generate Order and insert sales
        $max_row_shop = $this->db->query('SELECT MAX(orderid) AS maxid FROM product_sale')->row();
        $gen_orderid_shop = ($max_row_shop && $max_row_shop->maxid > 0) ? ($max_row_shop->maxid + 1) : 1001;

        if ($cart = $this->cart->contents()) {
            foreach ($cart as $item):
                $array = array(
                    'product_id' => $item['id'],
                    'userid'     => $this->session->user_id,
                    'qty'        => $item['qty'],
                    'cost'       => $item['price'],
                    'date'       => date('Y-m-d'),
                    'orderid'    => $gen_orderid_shop,
                );
                $this->db->insert('product_sale', $array);
            endforeach;
        }

        // 3. Log debit in wallet_transaction ledger
        $w_transData = array(
            'userid'       => $this->session->user_id,
            'type'         => 'Debit',
            'amount'       => $cart_total,
            'ref_id'       => 'ORDER_' . $gen_orderid_shop,
            'other'        => 'Product Purchase (Order #' . $gen_orderid_shop . ' from ' . ($target_table == 'product_wallet' ? 'Product Wallet' : 'E-Wallet') . ')',
            'created_date' => date('Y-m-d H:i:s'),
        );
        $this->db->insert('wallet_transaction', $w_transData);

        $this->db->trans_complete();

        if ($this->db->trans_status() === FALSE) {
            $this->session->set_flashdata('common_flash', '<div class="alert alert-danger">Checkout transaction failed. Please try again.</div>');
            redirect('shop/pre_checkout');
            return;
        }

        $this->cart->destroy();
        $this->session->set_flashdata('common_flash', '<div class="alert alert-success">Thank you for purchasing with us! Order #' . $gen_orderid_shop . ' has been placed successfully.</div>');
        redirect('shop/checkout_complete');
    }

    public function checkout_complete()
    {
        $data['title']  = 'Invoice';
        $data['layout'] = 'checkout_complete.php';
        $this->load->view('shop/base', $data);
    }

    public function old_purchase()
    {
        $config['base_url']   = site_url('cart/old_purchase');
        $config['per_page']   = 50;
        $config['total_rows'] = $this->db_model->count_all('product_sale', array('userid' => $this->session->user_id));
        $page = ($this->uri->segment(3)) ? $this->uri->segment(3) : 0;
        $this->pagination->initialize($config);

        $this->db->select('id, product_id, status, cost, qty, deliver_date, date, franchisee_id')->from('product_sale')
                 ->where('userid', $this->session->user_id)->limit($config['per_page'], $page);

        $data['data']   = $this->db->get()->result();
        $data['title']  = 'My Old Purchases';
        $data['layout'] = 'my_purchases.php';
        $this->load->view('shop/base', $data);

    }
    
    public function remove_item($rowid) 
    {
        $data = array(
            'rowid' => $rowid,
            'qty'   => 0
        );
        $this->cart->update($data);
    
        $this->session->set_flashdata('common_flash', '<div class="alert alert-success">Item removed from the cart.</div>');
        redirect('franchisee/pre_checkout');
    }
}
