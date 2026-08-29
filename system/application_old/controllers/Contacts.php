<?php
defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * Class Site
 */
class Contacts extends CI_Controller
{
    public function __construct()
    {
        parent::__construct();
        if ($this->login->check_session() == FALSE && $this->login->check_member() == FALSE) {
            redirect(site_url('site/login'));
        }
     	$this->load->model('plan_model');
    }


    public function my_contact(){

    	##Fetch contacts details
    	$this->db->select('*')->limit(50);
    	$this->db->where('userid',$this->session->user_id);
        $recent_contacts= $this->db->get('contacts')->result();

        $data['title']      = 'My Contacts';
        $data['breadcrumb'] = 'My Contacts';
        $data['layout']     = 'tree/my_contact_grid.php';
      
         $data['recent_contacts']       = $recent_contacts;
        $this->load->view('member/index', $data);

    }

    public function create_contact(){

    	$this->form_validation->set_rules('fname', 'fname', 'trim|required');
        $this->form_validation->set_rules('lname', 'lname', 'trim|required');
        if ($this->form_validation->run() == FALSE) {
            $data['title']      = 'Create Contact';
            $data['breadcrumb'] = 'Create Contact';
            $data['layout']     = 'tree/create_contact.php';
            $this->load->view('member/index', $data);
        } else {


    	$fname = $this->input->post('fname');
    	$lname = $this->input->post('lname');
    	$state = $this->input->post('state');
    	$city = $this->input->post('city');
    	$email = $this->input->post('email');
    	$phone = $this->input->post('phone');
    	$status = $this->input->post('status');

    	$save_data = array(
    			'userid'	=> $this->session->user_id,
                 'fname'     => $fname,
                'lname'       =>$lname,
                'email'     => $email,
                'city'     => $city,
                'state'     => $state,
                'phone'     => $phone,
                'status'      => $status,
           );

    	
         
        $this->session->set_flashdata('common_flash', '<div class="alert alert-success">Contact created Successfully.</div>');
        $this->db->insert('contacts', $save_data); 

    	$data['title']      = 'Create Contacts';
        $data['breadcrumb'] = 'Create Contacts';
        $data['layout']     = 'tree/create_contact.php';
        $this->load->view('member/index', $data);
    	}
	}



	 public function edit_my_contact(){

    	##Update contacts details
    	$this->form_validation->set_rules('fname', 'fname', 'trim|required');
        $this->form_validation->set_rules('lname', 'lname', 'trim|required');
        if ($this->form_validation->run() == FALSE) {
            $data['title']      = 'Create Contact';
            $data['breadcrumb'] = 'Create Contact';
            $data['layout']     = 'tree/create_contact.php';
            $this->load->view('member/index', $data);
        } else {


    	$fname = $this->input->post('fname');
    	$lname = $this->input->post('lname');
    	$state = $this->input->post('state');
    	$city = $this->input->post('city');
    	$email = $this->input->post('email');
    	$phone = $this->input->post('phone');
    	$status = $this->input->post('status');

    	$save_data = array(
    			'userid'	=> $this->session->user_id,
                 'fname'     => $fname,
                'lname'       =>$lname,
                'email'     => $email,
                'city'     => $city,
                'state'     => $state,
                'phone'     => $phone,
                'status'      => $status,
           );

    	
        $this->session->set_flashdata('common_flash', '<div class="alert alert-success">Contact created Successfully.</div>');
        $this->db->where('userid', $this->session->user_id);
        $this->db->update('contacts', $save_data); 

    	$data['title']      = 'Create Contacts';
        $data['breadcrumb'] = 'Create Contacts';
        $data['layout']     = 'tree/create_contact.php';
        $this->load->view('member/index', $data);
    	}

    }

    public function calender_details(){

    	$this->db->select('*')->limit(50);
    	$this->db->where('userid',$this->session->user_id);
        $cal_data= $this->db->get('contacts')->result();

        $data['title']      = 'Calender data';
        $data['breadcrumb'] = 'Calender data';
        $data['layout']     = 'tree/dash_calender.php';
      
         $data['cal_data']       = $cal_data;
        $this->load->view('member/index', $data);

    }

    
}