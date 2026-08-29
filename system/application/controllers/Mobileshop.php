<?php

defined('BASEPATH') OR exit('No direct script access allowed');
 
class Mobileshop extends CI_Controller
{

    public function __construct()
    {
        parent::__construct();
        // if ($this->login->check_member() == false) {
        //     redirect(site_url('Mobileshop/index'));
        // }
        $this->load->library('pagination'); 
        $this->load->library('cart');
    
    } 

    public function home()
    {
        $data['detail']     = $this->db_model->select_multi('total_a, total_b, total_c, total_d, total_e', 'member', array('id' => $this->session->user_id));
        $data['title']      = 'Dashboard';
        $data['breadcrumb'] = 'dashboard';
       // $this->load->view('mobileshop/header');
        $this->load->view('mobileshop/index',$data);
      // $this->load->view('mobileshop/footer');
        
    } 


    public function login()
    {
        $this->form_validation->set_rules('username', 'Username', 'trim|required');
        $this->form_validation->set_rules('password', 'Password', 'trim|required');
        if ($this->form_validation->run() == FALSE) {
         
        
        $this->load->view('mobileshop/sign-in');
       

        } else {

            $user     = $this->common_model->filter($this->input->post('username'));
            $password = $this->input->post('password');
            $data     = $this->db_model->select_multi("id, name, password, email, last_login_ip, last_login, status", 'member', array('id' => $user));

            if ($data->status !== "Active") {
                $this->session->set_flashdata('site_flash', '<div class="alert alert-danger">Login is invalid or Your account is not active. Account status is: ' . ($data->status ? $data->status : 'N/A') . '.</div>');
                redirect(site_url('mobileshop/login'));
            }
         
            if (password_verify($password, $data->password)) {
                session_unset();
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
                redirect(site_url('site/login'));
            }
        }
    }

    public function getCountry(){
    
            $ch = curl_init();
            curl_setopt($ch, CURLOPT_URL, "http://topups.reloadly.com/countries");
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, TRUE);
            curl_setopt($ch, CURLOPT_HEADER, FALSE);
           
            curl_setopt($this->curl, CURLOPT_SSL_VERIFYHOST, 0);
            curl_setopt($this->curl, CURLOPT_SSL_VERIFYPEER, 0);

            curl_setopt($ch, CURLOPT_HTTPHEADER, array(
              "Accept: application/com.reloadly.topups-v1+json",
              "Authorization: Bearer eyJ0eXAiOiJKV1QiLCJhbGciOiJSUzI1NiIsImtpZCI6Ik0wWXpRa"
            ));

            $response = curl_exec($ch);
            curl_close($ch);

            $result = json_decode($response);

            var_dump($result);
            $i=1;
            foreach ($result as $sss) {
                echo $i++ .") ".$sss->isoName."<br>";
            }
    }

  
    
    public function logout()
    {
        $this->session->sess_destroy();
        $this->session->set_flashdata('site_flash', '<div class="alert alert-info">You have been logged out !</div>');
        redirect(site_url('mobileshop/home'));
    }
}
?>
