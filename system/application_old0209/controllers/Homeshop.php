<?php
/***************************************************************************************************

 **************************************************************************************************/

defined('BASEPATH') OR exit('No direct script access allowed');

class Homeshop extends CI_Controller
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
        $this->db->select('id,prod_name,prod_price,image')->where('status', 'Selling')->limit(10);
        $data['product_top'] = $this->db->get('product')->result();

        $data['title']       = 'Select a Category Below: ';
        $data['layout']      = 'buy.php';

        $this->load->view('homeshop/base',$data);

    }

    public function show_products()
    {
        $this->db->select('id,prod_name,prod_price,image');
        $this->db->where(array(
                             'status'   => 'Selling',
                             'category' => $this->uri->segment(3),
                         ));
        $data['product'] = $this->db->get('product')->result();
        $data['title']   = 'Select a Product Below: ';
        $data['layout']  = 'show_products.php';
        $this->load->view('homeshop/base', $data);
    }

    public function buy_2($product_id)
    {
        $product_data = $this->db_model->select_multi('prod_name, prod_price, qty, gst', 'product', array('id' => $product_id));

        if ($product_data->qty == 0) {
            $this->session->set_flashdata('common_flash', '<div class="alert alert-danger">Stock has less qty.</div>');
            redirect('homeshop/new_purchase');
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
        redirect('homeshop/pre_checkout');
    }

    public function pre_checkout()
    {
        $data['title']  = 'Checkout';
        $data['layout'] = 'pre_checkout.php';
        $this->load->view('homeshop/base', $data);
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
        redirect('homeshop/pre_checkout');

    }

    function checkout()
    {   
        
        if ($this->login->check_member() == FALSE) {
            redirect(site_url('site/login'));
        }else{


        if (config_item('wallet_type')!="Yes"){
            $get_balance = $this->db_model->select('balance', 'wallet', array('userid' => $this->session->user_id));
        }else{
             $get_balance = $this->db_model->select('balance', 'product_wallet', array('userid' => $this->session->user_id));
        }
        if ($get_balance < $this->cart->total()) {
            $this->session->set_flashdata('common_flash', '<div class="alert alert-danger">Oops! you dont have sufficient fund in wallet, you can add Fund: '. config_item('currency') . $this->cart->total() . '</div>');
            redirect('homeshop/pre_checkout');
        }

        $data = array(
            'balance' => ($get_balance - $this->cart->total()),
        );

        $this->db->where('userid', $this->session->user_id);
        if (config_item('wallet_type')!="Yes"){
            $this->db->update('wallet', $data);
        }else{
            $this->db->update('product_wallet', $data);
        }
        if ($cart = $this->cart->contents()) {
            foreach ($cart as $item):

                $array = array(
                    'product_id' => $item['id'],
                    'userid'     => $this->session->user_id,
                    'qty'        => $item['qty'],
                    'cost'       => $item['price'],
                    'date'       => date('Y-m-d'),
                );

              //var_dump($array);die();
                $this->db->insert('product_sale', $array);

            endforeach;

        }
    }


        $this->session->set_flashdata('common_flash', '<div class="alert alert-success">Thank you for Purchasing with us</div>');
        redirect('homeshop/checkout_complete');
    }

     public function login_guest()
    {
        $this->form_validation->set_rules('phone', 'Phone', 'trim|required');
        $this->form_validation->set_rules('password', 'Password', 'trim|required');
        if ($this->form_validation->run() == FALSE) {
                $data['layout'] = 'login_guest.php';
                $this->load->view('homeshop/base', $data);

        } else {

            $phone     = $this->common_model->filter($this->input->post('phone'));
            $password = $this->input->post('password');
            $data     = $this->db_model->select_multi("id, name,password, email, last_login_ip, last_login, status", 'member', array('phone' => $phone));
           
           
            if ($data->status !== "Active") {
                $this->session->set_flashdata('site_flash', '<div class="alert alert-danger">Login is invalid or Your account is not active. Account status is: ' . ($data->status ? $data->status : 'N/A') . '.</div>');
                redirect(site_url('homeshop/login_guest'));
            }

            if (password_verify($password, $data->password)) {
               // session_unset();
                $session = md5($user . time());
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
            } else {
                $this->session->set_flashdata('site_flash', '<div class="alert alert-danger">Invalid Username or Password.</div>');
                redirect(site_url('homeshop/login_guest'));
            }
        }

       
    }

    
    public function guest_signup()
    {   
        $this->load->model('plan_model');
        
        $this->form_validation->set_rules('name', 'Name', 'trim|required');
        $this->form_validation->set_rules('email', 'email ID', 'trim|required');
        $this->form_validation->set_rules('address_1', 'Address Line 1', 'trim|required');
        $this->form_validation->set_rules('password', 'Password', 'trim|required|min_length[5]');
        $this->form_validation->set_rules('password_2', 'Retype Password', 'trim|required|matches[password]');
        $this->form_validation->set_rules('phone', 'Phone No', 'trim|required');

        if ($this->form_validation->run() !== FALSE) {
            $name      = $this->input->post('name');
            $email     = $this->input->post('email');
            $phone     = $this->input->post('phone');
            $user_type = "Guest";
            $product   = $this->input->post('product');
          
            $pg        = $this->input->post('pg');
            $address_1 = $this->input->post('address_1');
            $address_2 = $this->input->post('address_2');
            $username  = time();
            $password  = password_hash($this->input->post('password'), PASSWORD_DEFAULT);
            $divert_pg = FALSE;

            $check_user_exist = $this->db_model->select_multi('id,password,phone, email', 'member', array('phone' => $phone,'email'=>$email));
            if($check_user_exist == true){
                $this->session->set_flashdata('site_flash', '<div class="alert alert-danger">User already exists</div>');
                 redirect(site_url('homeshop/login_guest'));
            }
          
            $id = rand(1000000, 9999999);
            if ($this->db_model->count_all('member', array('id' => $id)) > 0) {
                $id = $id + 1;
                if ($this->db_model->count_all('member', array('id' => $id)) > 0) {
                    $id = $id + 2;
                    if ($this->db_model->count_all('member', array('id' => $id)) > 0) {
                        $id = $id + 3;
                    }

                }
            }
            
            $data = array(
                'id'              => $id,
                'name'            => $name,
                'email'           => $email,
                'phone'           => $phone,
                'username'        => $username,
                'password'        => $password,
                'user_type'       => $user_type,
                'signup_package'  => $product,
                
                'address'         => $address_1 . "<br/>" . $address_2,
                'join_time'       => date('Y-m-d'),
                
                'registration_ip' => $this->input->ip_address(),
                'topup'           => $this->cart->format_number($items['subtotal']),
                'my_business'     => $this->cart->format_number($items['subtotal']),
                'status'          => 'Active',
            );

            $this->db->insert('member', $data);
            $user_id = $this->db_model->select('id', 'member', array(
                'phone'           => $phone,
                'username'        => $username,
                'registration_ip' => $this->input->ip_address,
                'epin'            => $epin,
            ));

             if (config_item('sms_on_join') == "Yes"):
                /* Textlocal sms code */
                $sms = rawurlencode("Hi " . $name . " ,Thank you for register with " . config_item('company_name') . ". Your UserID is: " . config_item('ID_EXT') . $user_id . " and Password is: " . $this->input->post('password') ."\nwww." . $_SERVER['HTTP_HOST']."\n"); 

                $this->common_model->sms($phone, $sms);
            endif;


            $sub = "Welcome to " . config_item('company_name');
            $msg = "Hello " . $name . "<br/> Welcome to " . config_item('company_name') . "Just now you have successfully registered with us. Hope your journey with us will remain exciting and rewarding. <hr/>  <strong>User ID :</strong> " . config_item('ID_EXT') . $user_id . "<br/>
            <strong>Password :</strong> " . $this->input->post('password') . "
          <hr/><---<br/>Regards,<br/>www." . $_SERVER['HTTP_HOST'];

            $this->load->config('email');
            if (trim(config_item('smtp_host')) !== "") {
                $this->common_model->mail($email, $sub, $msg);
            } 

            ##########################################################################
            #
            # Now will Redirect to Payment Gateway (If need) or Success Page. At that
            # Page we'll generate income or rewards. Here we'll save some basic
            # important Data with session.
            #
            ##########################################################################
            $this->session->set_userdata('_user_id_', $user_id);
            $this->session->set_tempdata('_auto_user_id_', $user_id, '300');
            $this->session->set_tempdata('_inv_id_', $user_id);
            $this->session->set_tempdata('_type_', 'userid');
            $this->session->set_userdata('_user_name_', $name);
            $this->session->set_userdata('_sponsor_', $sponsor);
            $this->session->set_userdata('_position_', $position);
            $this->session->set_userdata('_address_', $address_1);
            $this->session->set_userdata('_email_', $email);
            $this->session->set_userdata('_phone_', $phone);
            $this->session->set_userdata('_product_', $product);
            $this->session->set_userdata('_price_', $prod_price);
            //var_dump($divert_pg);die();
            if ($divert_pg == TRUE):
                redirect(site_url('gateway/registration_form'));

            else:
                redirect(site_url('homeshop/pre_checkout'));
            endif;
             // $data['data']   = "guest_signup.php";
             // $this->load->view('homeshop/base', $data);


        }else {
            ####################################################
            #
            # If form is not submitted then, will show the
            # default registration form.
            #
            ####################################################
            $this->db->select('id, prod_name, prod_price, gst')->where(array(
                'status'          => 'Selling',
                'show_on_regform' => 'Yes',
            ))->order_by('prod_name', 'ASC');
            $data['products'] = $this->db->get('product')->result_array();
            // $layout['leg']      = $this->plan_model->create_leg();
          

        }
        // var_dump("I am in homeshop controller ! line no 276");
        $data['title']  = 'Guest Checkout';
        $data['layout'] = 'guest_signup.php';
        $this->load->view('homeshop/base', $data);
    }

    public function complete_registration(){

        if ($this->session->_user_id_ > 0) { 

            $data['title']  = 'Guest Checkout';
            $data['layout'] = 'checkout_complete.php';
            $this->load->view('homeshop/base', $data);


        if (config_item('show_join_product') == "Yes"):
                $product_detail = $this->db_model->select_multi('qty, sold_qty,prod_price', 'product', array('id' => $this->session->userdata('_product_')));
                if ($product_detail->qty !== "-1") {
                    $array = array('qty' => ($product_detail->qty - 1));
                    $this->db->where('id', $this->session->userdata('_product_'));
                    $this->db->update('product', $array);
                } else {

                }

                $array = array('sold_qty' => ($product_detail->sold_qty + 1));
                $this->db->where('id', $this->session->userdata('_product_'));
                $this->db->update('product', $array);
                
                ########################## AIT: 13/04/2019 ########################
                ## Prevent Join product : Makes entry in product sale table if join product and free registration is set
                ########################## AIT: 13/04/2019 ########################
                if (config_item('prevent_join_product_entry') == "Yes") {
                    $array = array(
                        'product_id' => $this->session->userdata('_product_'),
                        'userid'     => $this->session->userdata('_user_id_'),
                        'cost'       => $product_detail->prod_price,
                        'date'       => date('Y-m-d'),
                    );
                    
                    $this->db->insert('product_sale', $array);
                }
            endif;

             if ($status == TRUE) {
                ######## UNSET SOME PREVIOUS VALUES  #########

                // $this->session->unset_userdata('_user_id_');
                // $this->session->unset_userdata('_user_name_');
                $this->session->unset_userdata('_sponsor_');
                $this->session->unset_userdata('_position_');
                $this->session->unset_userdata('_address_');
                $this->session->unset_userdata('_email_');
                $this->session->unset_userdata('_phone_');
                $this->session->unset_userdata('_product_');
                $this->session->unset_userdata('_price_');

                ##############################################
 
            }
            } else {

                redirect(site_url('site/login'));
            }


         
    }


    public function checkout_complete()
    {
        $data['title']  = 'Invoice';
        $data['layout'] = 'checkout_complete.php';
        $this->load->view('homeshop/base', $data);
    }
    public function product_view($id){
        $product_data = $this->db_model->select_multi('*', 'product', array('id' => $id));

        $data['title']      = 'Product Detail';
        $data['breadcrumb'] = 'Products View';
        $data['layout']     = 'product_view.php';
        $data['data']       = $product_data;
        $this->load->view('homeshop/base', $data);
    }

    public function shop_more($id){
        $this->db->select('id,cat_name,description');
        $data['categories'] = $this->db->get('product_categories')->result();

        
        
        $this->db->select('id,prod_name,prod_price,image');
        $this->db->where(array(
                             'status'   => 'Selling',
                             'category' => $this->uri->segment(3),
                         ));


        $data['product'] = $this->db->get('product')->result();
        

        $data['title']      = 'shop_more';
        $data['breadcrumb'] = 'shop_more';
        $data['layout']     = 'shop_more.php';
        
        $this->load->view('homeshop/base',$data);
    }


    public function about_us(){
        $data['title']      = 'About Us';
        $data['breadcrumb'] = 'About-Us';
        $data['layout']     = 'about_us.php';
        
        $this->load->view('homeshop/base',$data);
    }

    public function privacy_policy(){
        $data['title']      = 'Privacy Policy';
        $data['breadcrumb'] = 'Privacy Policy';
        $data['layout']     = 'privacy_policy.php';
        
        $this->load->view('homeshop/base',$data);
    }
      public function contact_us(){
        $data['title']      = 'Contact Us';
        $data['breadcrumb'] = 'Contact Us';
        $data['layout']     = 'contact_us.php';
        
        $this->load->view('homeshop/base',$data);
    }

    public function terms_of_service(){
        $data['title']      = 'Terms of Service';
        $data['breadcrumb'] = 'Terms of Service';
        $data['layout']     = 'terms_of_service.php';
        
        $this->load->view('homeshop/base',$data);
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
        $this->load->view('homeshop/base', $data);

    }
}
