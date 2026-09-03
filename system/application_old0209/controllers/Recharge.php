<?php


defined('BASEPATH') OR exit('No direct script access allowed');

class Recharge extends CI_Controller
{
    /**
     * Check Valid Login or display login page. 
     */
    public function __construct()
    {
        parent::__construct();
        if ($this->login->check_session() == FALSE && $this->login->check_member() == FALSE) {
            redirect(site_url('site/login'));
        }
        $this->load->library('pagination');
    }



    public function recharge_dashboard()
    {        
       
        $data['title']  = 'Recharge Dashboard';
        $data['breadcrumb'] = 'Recharge';
        $data['layout'] = 'recharge/recharge_dashboard.php';
        $this->load->view('member/index', $data); 
    }


    public function records()
    { 
        if ($this->login->check_session() == FALSE) {
            exit('<h3 align="center">Oops !! Something is wrong please try again..</h3>');
        }

        if (trim($this->input->post('userid')) !== "") {
            $where = array(
                'userid' => $this->common_model->filter($this->input->post('userid')),
                'status' => 'Completed',
            );
        }
        else {
            $where = array(
                'status' => 'Completed',
            );

        }

        $config['base_url']   = site_url('recharge/records');
        $config['per_page']   = 50;
        $config['total_rows'] = $this->db_model->count_all('recharge_entry', $where);
        $page                 = ($this->uri->segment(3)) ? $this->uri->segment(3) : 0;
        $this->pagination->initialize($config);

        $this->db->from('recharge_entry')->order_by('id', 'DESC')->limit($config['per_page'], $page);
        $this->db->where('status', 'Completed');
        if (trim($this->input->post('userid')) !== "") {
            $this->db->where(array('userid' => $this->common_model->filter($this->input->post('userid'))));
        }

        $data['rcg']        = $this->db->get()->result();
        $data['title']      = 'Recharge Records';
        $data['breadcrumb'] = 'Recharge Records';
        $data['layout']     = 'recharge/records.php';
        $this->load->view('admin/base', $data);
    }

    public function remove_record($id)
    {

        if ($this->login->check_session() == FALSE) {
            exit('<h3 align="center">Yuk !! Go and have a bath..</h3>');
        }
        $this->db->where('id', $id); 
        $this->db->delete('recharge_entry');
        $this->session->set_flashdata('common_flash', '<div class="alert alert-success">Record Deleted successfully.</div>');

        redirect('recharge/records');
    }

    public function recharge()
    {

        $param = "mobile";

        switch ($param) { 
            case ($param == "mobile"): 

                $number =$this->input->post('number');
                $recharge_amount =$this->input->post('recharge_amount');
                $operator_code = $this->input->post('op_code');
                $circle_code = $this->input->post('circle_code');
                $user_id = $this->session->user_id;
                
                $get_w_bal = $this->db_model->select('balance', 'wallet', array('userid' => $this->session->user_id));


                // Recharge API for Cyrus Pay

                $curl = curl_init();
                $memberid ="AP550118"; // Customer ID provided by Cyrus recharge
                $pin="EB98DE6ECA"; // API Key provided by Cyrus recharge
                

                $operator = $operator_code;
                $circle = $circle_code;
                $amount = $recharge_amount;
                $usertx= bin2hex(random_bytes(10));
                $format = "json";

                
                  if($get_w_bal >= $amount){
                      
                      curl_setopt_array($curl, array(
                        
                      CURLOPT_URL => "https://cyrusrecharge.in/api/recharge.aspx"."?memberid=".$memberid."&pin=".$pin."&number=".$number."&operator=".$operator."&circle=".$circle."&amount=".$amount."&usertx=".$usertx."&format=".$format,
                      
                      CURLOPT_RETURNTRANSFER => true,
                      CURLOPT_ENCODING => '',
                      CURLOPT_MAXREDIRS => 10,
                      CURLOPT_TIMEOUT => 0,
                      CURLOPT_FOLLOWLOCATION => true,
                      CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
                      CURLOPT_CUSTOMREQUEST => 'GET',
                    ));
                    
                    $response = curl_exec($curl);
                    $curl_error = curl_errno($curl);
                    $getserver= curl_getinfo($curl);
                    curl_close($curl);
                 
                    
                    if($getserver["http_code"]==200)
                    {
                         $json = json_decode($response);
                       
                         if($json->Status =="Success" || $json->Status =="Pending"){
                                // Cashback amount calculatio
                                $cashback = (float) ($amount * 0.01);
                                $fin_bal = (float)($get_w_bal - $amount) +  $cashback;
                                 

                                $this->db->where(array('userid' => $user_id));
                                $this->db->update('wallet', array('balance' => $fin_bal));   


                                ## Insert into recharge history table 
                                $array = array(
                                    'userid'       => $user_id,
                                    'service_type' => $param,
                                    'recharge_no'  => $number,
                                    'amount'       => $recharge_amount,
                                    'time'         => time(),
                                    'status'       => 'Completed',
                                    'area'         => $circle,
                                    'operator'     => $operator,
                                    'trnid'        => $usertx,
                                    'ApiTransID'   => $$json->ApiTransID,
                                );
                                $this->db->insert('recharge_entry', $array);
                                $this->session->set_flashdata('common_flash', '<div class="alert alert-success">Your Recharge is Successfull. Transaction Id: ' . $usertx . '</div>');
                                redirect('recharge/new_recharge/'. $this->session->service_type);

                                
                                
                                $data = [ 'usertx' => $usertx, 'status' => $json->Status ];
                                echo json_encode($data); 
                             
                         }else{
                             
                            $data = [ 'ErrorMessage' => $json->ErrorMessage, 'status' => $json->Status ,'message' =>'Insufficient Balance' ];
                              $this->session->set_flashdata('common_flash', '<div class="alert alert-danger">Erro Message: '.$json->ErrorMessage.' Transaction is : ' . $json->Status .'</div>');
                               redirect('recharge/new_recharge/'.$this->session->service_type);
                           // echo json_encode("Wait Time 15 Min: ".$response); 
                         }
                           
                    }
                    
                  }else{
                      
                        $data = [ 'status' => "Failure" ,'message' =>'Insufficient Balance' ];
                        echo json_encode($data);
                       
                  }               
                break;
        }

        ## Configure Json Response or plain text response as per your API doc.

        // if ($status !== "Success") {

        //     $this->session->set_flashdata('common_flash', '<div class="alert alert-danger">Some Error Occured while doing Recharge: ' . $error . '.</div>');
        //     redirect('recharge/new_recharge');
        // }
        
    }

    public function new_recharge() 
    { 
        
        $this->db->select('*');
        if($this->session->service_type !=''){
            $this->db->where('op_type',$this->session->service_type);    
        }
        
        $this->db->from('recharge_operators');
        $data['rech_operators']= $this->db->get()->result_array();     

        $this->session->set_userdata('service_type', $this->uri->segment(3));

        $data['title']      = 'New Recharge';
        $data['breadcrumb'] = 'New Recharge';
        $data['layout']     = 'recharge/new_recharge.php';
        $this->load->view('member/index', $data);
    }

    public function old_recharges()
    {
        $where                = array(
            'userid' => $this->session->user_id,
            'status' => 'Completed',
        );
        $config['base_url']   = site_url('recharge/old-recharges');
        $config['per_page']   = 50;
        $config['total_rows'] = $this->db_model->count_all('recharge_entry', $where);
        $page                 = ($this->uri->segment(3)) ? $this->uri->segment(3) : 0;
        $this->pagination->initialize($config);

        $this->db->from('recharge_entry')->order_by('id', 'DESC')->limit($config['per_page'], $page);
        $this->db->where($where);

        $data['rcg']        = $this->db->get()->result();
        $data['title']      = 'My Recharges';
        $data['breadcrumb'] = 'My Recharges';
        $data['layout']     = 'recharge/old_recharge.php';
        $this->load->view('member/index', $data);
    }
}