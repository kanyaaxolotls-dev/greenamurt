<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Apiusers extends CI_Controller
{
    /**
     * Income Section for Admin Only
     */
    public function __construct()
    {
        parent::__construct();
        if ($this->login->check_session() == FALSE) {
            redirect(site_url('site/admin'));
        }
        $this->load->library('pagination');
        $this->load->model('apiusers_model');
    }

    public function view_members()
    {
        $config['base_url']   = site_url('apiusers/view_members');
        $config['full_tag_open'] = "<ul class='pagination justify-content-end mb-0'>";
        $config['full_tag_close'] ="</ul>";
        $config['num_tag_open'] = "<li class='page-link'>";
        $config['num_tag_close'] = "</li>";
        $config['cur_tag_open'] = "<li class='disabled'><li class='active'><a class='page-link' href='#'>";
        $config['cur_tag_close'] = "<span class='sr-only'></span></a></li>";
        $config['next_tag_open'] = "<li class='page-link'>";
        $config['next_tagl_close'] = "</li>";
        $config['prev_tag_open'] = "<li class='page-link'>";
        $config['prev_tagl_close'] = "</li>";
        $config['first_tag_open'] = "<li class='page-link'>";
        $config['first_tagl_close'] = "</li>";
        $config['last_tag_open'] = "<li class='page-link'>";
        $config['last_tagl_close'] = "</li>";
        $config['per_page']   = 10;
        $config['total_rows'] = $this->db_model->count_all('member');
        $page                 = ($this->uri->segment(3)) ? $this->uri->segment(3) : 0;
        $this->pagination->initialize($config);

        $users = $this->apiusers_model->get_users($config['per_page'], $page);
        $data['members'] = $users['valid_users'];
        $data['unmapped_members'] = $users['invalid_users'];
        

        $data['title']      = 'List of Members';
        $data['breadcrumb'] = 'Manage Members';
        $data['layout']     = 'apimember/list_member.php';
        $this->load->view('admin/index', $data);
    }
    
    public function update_wallet()
    {
       // $balance_array = $this->input->post('bal');
        $uid = $this->input->post('uid');
        $mobile_data = array();
        if(!empty($uid)){
                        
                        foreach($uid as $uid1=>$uids){
                            

                            $mobile_data[]=array('mobile'=>$mobile,'bal'=>$sum);  
                             }

            if($this->apiusers_model->update_wallet($mobile_data))
                $this->session->set_flashdata('message', 'Wallet Updated successfully');
            else
               $this->session->set_flashdata('message', 'Some Error occured in Wallet Update');
                
    }
      redirect('apiusers/view_members');
    }
    
    
}    