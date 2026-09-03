<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Home extends CI_Controller {
    
    public function __construct()
    {
        parent::__construct();
        if (config_item('select_temp') == 'Static') {
            redirect(site_url('web'));
        }
    }
    
    public function contact_us() {
        if ($this->input->post('contactus')) {
            $name     = $this->input->post('name');
            $email    = $this->input->post('email');
            $phone    = $this->input->post('phone');
            $subject  = $this->input->post('subject');
            $message  = $this->input->post('message');

            $data = [
                'name'         => $name,
                'email'        => $email,
                'phone'        => $phone,
                'subject'      => $subject,
                'message'      => $message,
                'created_date' => date('Y-m-d H:i:s') 
            ];
            $inserted = $this->db->insert('contact_us', $data);

            if ($inserted) {
                $this->session->set_flashdata('common_flash', '<div class="alert alert-success">Your message has been sent successfully!</div>');
            } else {
                $this->session->set_flashdata('common_flash', '<div class="alert alert-danger">Failed to send your message. Please try again.</div>');
            }
            redirect(base_url('home/contact'));
        }
    }
    
	public function index()
	{
	    if (config_item('web_status') == 'Coming Soon') {
            $this->load->view('shop/coming-soon.php');
        }
        else{
            $this->db->select('*');
            $this->db->limit(1);
            $this->db->from('product_categories');
            $data['categories'] = $this->db->get()->result_array();
            
            $this->db->select('*');
            $this->db->from('product_categories');
            $data['categories2'] = $this->db->get()->result_array();
            
            $this->db->select('*');
            $this->db->from('slider');
            $data['slider']   = $this->db->get()->result_array();
            
            $this->db->select('*');
            $this->db->where('status', 1);
            $this->db->from('popups');
            $data['popups']   = $this->db->get()->result_array();
            
            $this->db->select('*');
            $this->db->from('product');
            $data['productt']   = $this->db->get()->result_array();
		    $this->load->view('shop/index.php', $data);
        }
	}

	public function products($id = NULL)
	{
	    $title = $this->db_model->select('cat_name', 'product_categories', array('id' => $id));
	    if($id == NULL){
	        $title = 'All Products';
	    }
	    $this->db->select('*');
	    if($id != NULL){
	        $this->db->where('category',$id);
	    }
        $this->db->from('product');
        $data['productt']   = $this->db->get()->result_array();
        $data['title']      = $title;
        $p_count            = $this->db_model->count_all('product', array('category' => $id));
        if($p_count <= 0 and $id != NULL){
           $page = 'empty-category.php';  
        }
        else{
            $page = 'shop.php'; 
        }
		$this->load->view('shop/'.$page, $data);
	}

	public function docs()
	{
		$this->load->view('shop/docs.php');
	}

	public function about()
	{
		$this->load->view('shop/about.php');
	}

	public function contact()
	{
		$this->load->view('shop/contact.php');
	}

	public function login()
	{
	    $this->form_validation->set_rules('phone', 'phone', 'required');
        if ($this->form_validation->run() == FALSE) {
            $this->load->view('shop/login.php');
        } 
        else {
            $sess_otp      = $this->session->userdata('otp_data');
            $phone         = $this->input->post('phone');
            $input_otp     = $this->input->post('otp');
            $generated_otp = $this->input->post('gen_otp');
            if ($sess_otp['phone'] == $phone &&  $input_otp == $generated_otp) {
                $user_data = array(
                    'phone' => $phone,
                );
                $this->session->set_userdata('web_user_data', $user_data);
                $this->session->unset_userdata('otp_data');
                redirect('home/account');
            } 
            else {
                $this->session->set_flashdata('common_flash', '<div class="alert alert-danger">Invalid OTP. Please try again.</div>');
                redirect('home/login');
            }
        }
	}

	public function account()
	{
	    $web_ses_data['data']     = $this->session->userdata('web_user_data');
	    $web_ses_data['userss']   = $this->db_model->select_multi('*', 'web_users', array('phone' => $web_ses_data['data']["phone"]));  
        
	    $this->db->from('product_sale');
	    $this->db->where('userid', $web_ses_data['data']["phone"]);
        $web_ses_data['orders']   = $this->db->get()->result_array();
        
	    $web_ses_data['wishlist'] = $this->session->userdata('wishlist');
	    if($web_ses_data['data'] == NULL){
	        redirect('home/login');
	    }
	    else{  
		    $this->load->view('shop/my_account.php',$web_ses_data);
	    }
	}

	public function privacy()
	{
		$this->load->view('shop/privacy-policy.php');
	}

	public function terms()
	{
		$this->load->view('shop/Help/Terms.php');
	}
	
		public function FAQ()
	{
		$this->load->view('shop/Help/FAQ.php');
	}

	public function Shipping()
	{
		$this->load->view('shop/Help/Shipping.php');
	}
	
		public function Return()
	{
		$this->load->view('shop/Help/Return.php');
	}
	public function view_product($id)
	{
	    $cat = $this->db_model->select('category', 'product', array('id' => $id));
	    $this->db->select('*');
	    $this->db->where('id',$cat);
        $this->db->from('product');
        $data['productt']   = $this->db->get()->result_array();
	    $data['product']    = $this->db_model->select_multi('*', 'product', array('id' => $id));  
		$this->load->view('shop/product-detail.php', $data);
	}

	public function cart()
	{
	    $sessionData = $this->session->userdata('cart_data');
        if (is_array($sessionData)) {
            foreach ($sessionData as $prod_id => $product) {
                if (isset($product['qty']) && $product['qty'] == 0) {
                    unset($sessionData[$prod_id]);
                }
            }
            $this->session->set_userdata('cart_data', $sessionData);
        }
	    $cart_data['data'] = $this->session->userdata('cart_data');
        if (isset($sessionData[$id])) {
            unset($sessionData[$id]);
            $this->session->set_userdata('cart_data', $sessionData);
        }
        $user_id   = $this->input->ip_address(); 
        if($cart_data['data'] == NULL){
           $page = 'empty-cart.php';  
        }
        else{
            $page = 'cart.php'; 
        }
		$this->load->view('shop/'.$page,$cart_data);
	}

	public function checkout_success($id)
	{
		$this->load->view('shop/checkout-success.php');
	}

	public function wishlist()
	{
	    $sessionData = $this->session->userdata('wishlist');
        if (is_array($sessionData)) {
            foreach ($sessionData as $prod_id => $product) {
                if (isset($product['qty']) && $product['qty'] == 0) {
                    unset($sessionData[$prod_id]);
                }
            }
            $this->session->set_userdata('wishlist', $sessionData);
        }
	    $wish_data['data'] = $this->session->userdata('wishlist');
        if (isset($sessionData[$id])) {
            unset($sessionData[$id]);
            $this->session->set_userdata('wishlist', $sessionData);
        }
        if($wish_data['data'] == NULL){
           $page = 'empty-wishlist.php';  
        }
        else{
            $page = 'wishlist.php'; 
        }
		$this->load->view('shop/'.$page,$wish_data);
	}
	
    public function add_to_cart($prod_id = 0)
    {
        if(config_item('select_temp') == 'Eccomerce'){
            $cart_data = $this->session->userdata('cart_data') ?? array();
            if (!isset($cart_data[$prod_id]['qty'])) {
                $cart_data[$prod_id]['qty'] = 0;
            }
            $cart_data[$prod_id]['qty']++;
            $this->session->set_userdata('cart_data', $cart_data);
            redirect('home/cart');
        }
        else{
            redirect('member');
        }
    }
	
    public function add_to_wishlist($prod_id = 0)
    {
        if(config_item('select_temp') == 'Eccomerce'){
            $wishlist = $this->session->userdata('wishlist');
            if (!$wishlist) {
                $wishlist = array();
            }
            if (!in_array($prod_id, $wishlist)) {
                $wishlist[] = $prod_id;
                $this->session->set_userdata('wishlist', $wishlist);
                redirect('home/wishlist');
            } 
            else {
                redirect('home/wishlist');
            }
        }
        else{
            redirect('member');
        }
    }
	
	public function add_qty($prod_id = 0) {
        $cart_data = $this->session->userdata('cart_data') ?? array();
        if (!isset($cart_data[$prod_id]['qty'])) {
            $cart_data[$prod_id]['qty'] = 0;
        }
        $cart_data[$prod_id]['qty']++;
        $this->session->set_userdata('cart_data', $cart_data);
        redirect('home/cart');
    }

    public function remove_qty($prod_id = 0) {
        $cart_data = $this->session->userdata('cart_data') ?? array();
        if (!isset($cart_data[$prod_id]['qty'])) {
            $cart_data[$prod_id]['qty'] = 0;
        }
        $cart_data[$prod_id]['qty']--;
        $this->session->set_userdata('cart_data', $cart_data);
        redirect('home/cart');
    }

    public function remove_from_cart($id = 0) {
        $sessionData = $this->session->userdata('cart_data');
        if (isset($sessionData[$id])) {
            unset($sessionData[$id]);
            $this->session->set_userdata('cart_data', $sessionData);
        }
        redirect('home/cart');
    }

    public function remove_from_wishlist($item_id = 0)
    {
        $wishlist = $this->session->userdata('wishlist');
        if ($wishlist) {
            $key = array_search($item_id, $wishlist);
            if ($key !== false) {
                unset($wishlist[$key]);
                $this->session->set_userdata('wishlist', $wishlist);
            }
        }
        redirect('home/wishlist'); 
    }


    public function clear_cart() {
       $this->session->unset_userdata('cart_data');
       redirect('home/cart');
    }

    public function clear_wishlist() {
       $this->session->unset_userdata('wishlist');
       redirect('home/wishlist');
    }
    
    public function pre_checkout()
	{
	    $web_ses_data     = $this->session->userdata('web_user_data');
	    $data['userss']   = $this->db_model->select_multi('*', 'web_users', array('phone' => $web_ses_data["phone"]));  
	    if($this->session->user_id == NULL){
           $user_id = $this->input->ip_address(); 
        }
        else{
            $user_id    = $this->session->user_id;
        }
		$this->load->view('shop/checkout.php',$data);
	}
    
    public function checkout() {
        $data2          = $this->input->post('data');
        $user_id        = $data2[2]; 
        $serializedData = json_encode($data2);
        $paymentMethod  = $this->input->post('payment');
        $note           = $this->input->post('note');
        $total          = $this->input->post('total');
        $max_row_home   = $this->db->query('SELECT MAX(orderid) AS maxid FROM product_sale')->row();
        $orderid        = ($max_row_home && $max_row_home->maxid > 0) ? ($max_row_home->maxid + 1) : 1001;
        $cart_items     = $this->session->userdata('cart_data');
        if($paymentMethod == 'cod'){
            foreach ($cart_items as $product_id => $data) {
                $sale_data = array(
                    'userid'          => $user_id,
                    'product_id'      => $product_id,
                    'payment_sataus'  => 'Pending',
                    'qty'             => $data['qty'],
                    'cost'            => $this->db_model->select('dealer_price', 'product', array('id' => $product_id)) * $data['qty'],
                    'note'            => $note,
                    'payment_method'  => $paymentMethod,
                    'orderid'         => $orderid,
                    'shipping'        => $serializedData,
                    'date'            => date('Y-m-d H:i:s'),
                    'note'            => $this->input->post('note')
                );
                $this->db->insert('product_sale', $sale_data);
                
                $found_user = $this->db_model->count_all('web_users', array('phone' => $user_id));
                if($found_user <= 0){
                    $address_data = array(
                        'phone'          => $user_id,
                        'name'           => $data2[0],
                        'email'          => $data2[1],
                        'state'          => $data2[4],
                        'city'           => $data2[5],
                        'address'        => $data2[6],
                        'pincode'        => $data2[7],
                    );
                    $this->db->insert('web_users', $address_data);
                }
                unset($cart_items[$product_id]);
                $this->session->set_userdata('cart_data', $cart_items);
            }
            redirect('home/checkout_success/'.$orderid);
        }
        elseif($paymentMethod == 'wallet'){
            $get_wallet = $this->db_model->select('balance', 'wallet', array('userid' => $user_id));
            $new_fund   = $get_wallet - $total;
            if($get_wallet < $total){
                $this->session->set_flashdata('site_flash', '<div class="alert alert-danger">Insufficient Balance In Your Account. You Need ₹'.abs($new_fund).' More In Your Wallet</div>');
                redirect('home/pre_checkout');
            }
            foreach ($cart_items as $item) {
                $sale_data = array(
                    'userid'          => $user_id,
                    'product_id'      => $item['product_id'],
                    'qty'             => $item['qty'],
                    'cost'            => $total,
                    'payment_method'  => $paymentMethod,
                    'orderid'         => $orderid,
                    'shipping'        => $data,
                    'date'            => date('Y-m-d H:i:s'),
                    'note'            => $this->input->post('note')
                );
                $this->db->insert('product_sale', $sale_data);
            }
            $this->db->delete('cart', array('userid' => $user_id));
            $array    = array(
                'balance' => $new_fund,
            );
            $this->db->where('userid', $user_id);
            $this->db->update('wallet', $array);
            redirect('home/checkout_success/'.$orderid);
        }
        else{
            $this->session->set_flashdata('site_flash', '<div class="alert alert-danger">Payment Method Undefined.</div>');
            redirect('home/pre_checkout');
        }
    }
    
    public function send_otp() {
        $phone    = $this->input->post('phone');
        $found_user = $this->db_model->count_all('web_users', array('phone' => $phone));
        if($found_user <= 0){
            $this->session->set_flashdata('common_flash', '<div class="alert alert-warning">Mobile Number Not Found .</div>');
            redirect('home/account');
        }
        $otp      = rand(50000, 60000);
        $otp_data = array(
            'phone' => $phone,
            'otp'   => $otp
        );
        $this->session->set_userdata('otp_data', $otp_data);

        $fields = array(
            'variables_values' => "$otp",
            'route'            => 'otp',
            'numbers'          => "$phone",
        );
        $curl = curl_init();
        curl_setopt_array($curl, array(
            CURLOPT_URL              => "https://www.fast2sms.com/dev/bulkV2",
            CURLOPT_RETURNTRANSFER   => true,
            CURLOPT_ENCODING         => "",
            CURLOPT_MAXREDIRS        => 10,
            CURLOPT_TIMEOUT          => 30,
            CURLOPT_SSL_VERIFYHOST   => 0,
            CURLOPT_SSL_VERIFYPEER   => 0,
            CURLOPT_HTTP_VERSION     => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST    => "POST",
            CURLOPT_POSTFIELDS       => json_encode($fields),
            CURLOPT_HTTPHEADER       => array(
                "authorization: BgT4uekgBdn99h75RCsQZ39OJvkprg0Nm41otdWS0ZGBquuCMdPlo3DDoxz3",
                "accept: */*",
                "cache-control: no-cache",
                "content-type: application/json"
            ),
        ));
        $response = curl_exec($curl);
        $err      = curl_error($curl);
        curl_close($curl);
        if ($err) {
            $this->session->set_flashdata('common_flash', '<div class="alert alert-danger">'.$err.'</div>');
        } else {
            $this->session->set_flashdata('common_flash', '<div class="alert alert-success">OTP has been sent successfully to your mobile number.</div>');
            redirect('home/login');
        }
    }
    
    public function logout() {
        $this->session->unset_userdata('web_user_data');
        redirect('home/login');
    }

}


