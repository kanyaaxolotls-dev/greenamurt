<?php
defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * Class Site
 */
class Tree extends CI_Controller
{
    public function __construct()
    {
        parent::__construct();
        if ($this->login->check_session() == FALSE && $this->login->check_member() == FALSE) {
            redirect(site_url('site/login'));
        }
        $this->load->model('plan_model');
    }

    public function auto_tree($id = NULL, $pool = 'autopool_1'){
        if($id == NULL){
            $data['id'] = $this->db_model->select("id", $pool, array("userid" => $this->session->user_id));
        }
        else{
            $data['id'] = $id;
        }
        $data['pool']       = $pool;
        $data['title']      = 'Universal Tree';
        $data['breadcrumb'] = 'report';
        $data['layout']     = 'tree/auto_tree.php';
        $this->load->view('member/index', $data);
    }

    public function auto_tree_admin($id = NULL, $pool = 'autopool_1'){
        $data['id']         = $id ?? 1;
        $data['pool']       =  $pool;
        $data['title']      = 'Admin club tree';
        $data['breadcrumb'] = 'report';
        $data['layout']     = 'tree/auto_tree.php';
        $this->load->view('admin/index', $data);
    }

    public function user_report(){
        $data['title']      = 'User count report';
        $data['breadcrumb'] = 'report';
        $data['layout']     = 'tree/user_count.php';
        $this->load->view('member/index', $data);
    }
    
    public function user_tree()
    {
        if ($this->login->check_session() == FALSE) {
            exit('<h3 align="center">Something is wrong ! Error at user tree</h3>');
        }
        $top_id = $this->common_model->filter($this->input->post('top_id'));

        if (trim($top_id) == ""):
            $data['title']      = 'User Tree';
            $data['breadcrumb'] = 'Tree';
            $data['layout']     = 'tree/user_tree.php';
            $this->load->view('admin/index', $data);

        else:
            if (!($this->session->admin_id)) {
                $this->session->set_flashdata('common_flash', '<div class="alert alert-danger">You cannot view upline tree !</div>');
                redirect('tree/user_tree/');
            }
            redirect(site_url('tree/user_tree/' . $top_id));
        endif;
    }
    
    public function user_tree2()
    {
        if ($this->login->check_session() == FALSE) {
            exit('<h3 align="center">Something is wrong ! Error at user tree</h3>');
        }
        $top_id = $this->common_model->filter($this->input->post('top_id'));

        if (trim($top_id) == ""):
            $data['title']      = 'Booster Tree';
            $data['breadcrumb'] = 'Tree';
            $data['layout']     = 'tree/user_tree2.php';
            $this->load->view('admin/index', $data);

        else:
            if (!($this->session->admin_id)) {
                $this->session->set_flashdata('common_flash', '<div class="alert alert-danger">You cannot view upline tree !</div>');
                redirect('tree/user_tree2/');
            }
            redirect(site_url('tree/user_tree2/' . $top_id));
        endif;
    }
    
    public function user_tree3()
    {
        if ($this->login->check_session() == FALSE) {
            exit('<h3 align="center">Something is wrong ! Error at user tree</h3>');
        }
        $top_id = $this->common_model->filter($this->input->post('top_id'));

        if (trim($top_id) == ""):
            $data['title']      = 'User Tree';
            $data['breadcrumb'] = 'Tree';
            $data['layout']     = 'tree/user_tree3.php';
            $this->load->view('admin/index', $data);

        else:
            if (!($this->session->admin_id)) {
                $this->session->set_flashdata('common_flash', '<div class="alert alert-danger">You cannot view upline tree !</div>');
                redirect('tree/user_tree3/');
            }
            redirect(site_url('tree/user_tree3/' . $top_id));
        endif;
    }
    
    public function user_tree4()
    {
        if ($this->login->check_session() == FALSE) {
            exit('<h3 align="center">Something is wrong ! Error at user tree</h3>');
        }
        $top_id = $this->common_model->filter($this->input->post('top_id'));

        if (trim($top_id) == ""):
            $data['title']      = 'User Tree';
            $data['breadcrumb'] = 'Tree';
            $data['layout']     = 'tree/user_tree4.php';
            $this->load->view('admin/index', $data);

        else:
            if (!($this->session->admin_id)) {
                $this->session->set_flashdata('common_flash', '<div class="alert alert-danger">You cannot view upline tree !</div>');
                redirect('tree/user_tree4/');
            }
            redirect(site_url('tree/user_tree4/' . $top_id));
        endif;
    }
    
    public function user_tree5()
    {
        if ($this->login->check_session() == FALSE) {
            exit('<h3 align="center">Something is wrong ! Error at user tree</h3>');
        }
        $top_id = $this->common_model->filter($this->input->post('top_id'));

        if (trim($top_id) == ""):
            $data['title']      = 'User Tree';
            $data['breadcrumb'] = 'Tree';
            $data['layout']     = 'tree/user_tree5.php';
            $this->load->view('admin/index', $data);

        else:
            if (!($this->session->admin_id)) {
                $this->session->set_flashdata('common_flash', '<div class="alert alert-danger">You cannot view upline tree !</div>');
                redirect('tree/user_tree5/');
            }
            redirect(site_url('tree/user_tree5/' . $top_id));
        endif;
    }


    public function downline_report()
    {
        if ($this->login->check_session() == FALSE) {
            exit('<h3 align="center">Something is wrong ! Contact administrater. </h3>');
        }

        $top_id = $this->common_model->filter($this->input->post('top_id'));
        
        if (trim($top_id) == ""):
            $data['title']      = 'Downline Report Of '.$top_id;
            $data['breadcrumb'] = 'Downline Report';
            $data['layout']     = 'tree/downlinereport.php';
            $this->load->view('admin/index', $data);

        else:
            if (trim($this->session->user_id) !== "" && $top_id < $this->session->user_id) {
                $this->session->set_flashdata('common_flash', '<div class="alert alert-danger">You cannot view upline Detail !</div>');
                redirect('tree/downline-report/');
            }
            redirect(site_url('tree/downline-report/' . $top_id));
        endif;
    }
   
    public function referred_list()
    {
        $data['title']      = 'Reffered Members';
        $data['breadcrumb'] = 'Manage Inactive Members';
        $data['layout']     = 'member/list_member.php';
    
        $type       = $this->input->post('type');
        $start_date = $this->input->post('start_date');
        $end_date   = $this->input->post('end_date');
    
        if (!empty($type)) {
            $this->db->where('signup_package', $type);
        }
        if (!empty($start_date)) {
            $this->db->where('join_time >=', $start_date);
        }
        if (!empty($end_date)) {
            $this->db->where('join_time <=', $end_date);
        }
        $top_id = $this->common_model->filter($this->input->post('top_id'));
        $this->db->select('*')->from('member')->where('sponsor', $top_id)->order_by('secret','asc'); 
        $data['members'] = $this->db->get()->result_array();
        $data['type']       = $type ?? '';
        $data['end_date']   = $end_date ?? '';
        $data['start_date'] = $start_date ?? '';
        $this->load->view('admin/index', $data); 
    }


    public function add_new()
    {
        if ($this->login->check_session() == FALSE) {
            exit('<h3 align="center">You are smelling rotten ! Go and have a bath..</h3>');
        }

        $this->load->model('plan_model');
        $this->form_validation->set_rules('name', 'Name', 'trim|required');
        $this->form_validation->set_rules('sponsor', 'Sponsor ID', 'trim|required');
        $this->form_validation->set_rules('address_1', 'Address Line 1', 'trim|required');
        $this->form_validation->set_rules('password', 'Password', 'trim|required|min_length[5]');
        $this->form_validation->set_rules('password_2', 'Retype Password', 'trim|required|matches[password]');
        $this->form_validation->set_rules('phone', 'Phone No', 'trim|required');

        if ($this->form_validation->run() !== FALSE) {

            $name           = $this->input->post('name');
            $sponsor        = $this->common_model->filter($this->input->post('sponsor'));
            $email          = $this->input->post('email');
            $phone          = $this->input->post('phone');
            $leg            = $this->input->post('leg') ? $this->input->post('leg') : '';
            $position       = $this->common_model->filter($this->input->post('position'));
            $product        = $this->input->post('product');
            $backdate       = $this->input->post('backdate');
            $userid         = $this->input->post('userid');
            $address_1      = $this->input->post('address_1');
            $address_2      = $this->input->post('address_2');
            $username       = $userid ? $userid : time();
            $password       = password_hash($this->input->post('password'), PASSWORD_DEFAULT);
            $product_detail = $this->db_model->select_multi('prod_price, gst, pv, qty, matching_income', 'product', array('id' => $product));

            $prod_price = $product_detail->prod_price;

            ###############################################################################
            #
            # Now get selected blank Leg (eg: A, B, C) of position ID
            # If Position id is blank, sponsor ID will become position ID
            # If selected leg of position is not blank, will return error.
            #
            ###############################################################################
            if (config_item('autopool_registration') !== "Yes"):
                if (trim($position) == ""):
                    $position = $sponsor;
                endif;

                if ($position == $sponsor):
                    $position = $this->plan_model->find_extreme_position($sponsor, $leg);
                else:
                    if ($this->plan_model->check_position($position, $leg) !== $position):
                        $this->session->set_flashdata('site_flash', '<div class="alert alert-danger">The selected Position of Placement ID is not empty.</div>');
                        redirect(site_url('site/register'));
                    endif;
                endif;
            else:
                $autopool_data = $this->plan_model->find_autopool_field($sponsor);
                $position      = $autopool_data['position'];
                $leg           = $autopool_data['leg'];

            endif;

            ##############################################################################
            #
            # Everything seems fine, Now will make database entry
            #
            ##############################################################################
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
            if (config_item('show_join_product') == "No" && config_item('free_registration') == "No") {
                $prod_price = $this->input->post('amt_to_pay');
                $product    = 'N/A';
            }

            if (config_item('show_join_product') !== "Yes"):
                $mybusiness = $prod_price;
            endif;

            $data = array(
                'id'              => $userid ? $userid : $id,
                'name'            => $name,
                'email'           => $email,
                'phone'           => $phone,
                'username'        => $username,
                'password'        => $password,
                'sponsor'         => $sponsor,
                'position'        => $position,
                'signup_package'  => $product,
                'address'         => $address_1 . "<br/>" . $address_2,
                'join_time'       => $backdate ? $backdate : date('Y-m-d'),
                'placement_leg'   => $leg,
                'registration_ip' => $this->input->ip_address(),
                'topup'           => $prod_price,
                'my_business'     => $mybusiness,
                'mypv'            => $product_detail->pv ? $product_detail->pv : 0,
            );

            $this->db->insert('member', $data);
            $user_id = $this->db_model->select('id', 'member', array(
                'phone'           => $phone,
                'username'        => $username,
                'registration_ip' => $this->input->ip_address,
            ));
            if ($user_id):

                $data = array(
                    'userid'  => $user_id,
                    'balance' => "0.00",
                );
                $this->db->insert('wallet', $data);
                $data = array(
                    'userid'  => $user_id,
                    'balance' => "0.00",
                );
                $this->db->insert('other_wallet', $data);

                $data = array(
                    'userid' => $user_id,
                );
                $this->db->insert('member_profile', $data);

            else:
                $this->session->set_flashdata('site_flash', '<div class="alert alert-danger">Some error occured while registering. please contact admin or try again.</div>');
                //  redirect(site_url('tree/add-new'));
            endif;

            $data = array(
                $leg => $user_id,
            );
            $this->db->where('id', $position);
            $this->db->update('member', $data);
            ##########################################################################
            #
            # Now will send welcome email and SMS.
            #
            ##########################################################################

            if (config_item('sms_on_join') == "Yes"):
                $sms = "Hi, " . $name . ", Thank you for registering with " . config_item('company_name') . ". Your UserID is: " . config_item('ID_EXT') . $user_id . " and Password is: " . $this->input->post('password') . "
                    -- www." . $_SERVER['HTTP_HOST'];
                $this->common_model->sms($phone, $sms);
            endif;
            $sub = "Welcome to " . config_item('company_name');
            $msg = "Hellow " . $name . "<br/> Welcome to " . config_item('company_name') . "Just now you have successfully registered with us. Hope your journey with us will remain exciting and rewarding. <hr/>  <strong>User ID :</strong> " . config_item('ID_EXT') . $user_id . "<br/>
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
            $this->session->set_userdata('_inv_id_', $user_id);
            $this->session->set_userdata('_type_', 'userid');
            $this->session->set_userdata('_user_name_', $name);
            $this->session->set_userdata('_sponsor_', $sponsor);
            $this->session->set_userdata('_address_', $address_1);
            $this->session->set_userdata('_email_', $email);
            $this->session->set_userdata('_phone_', $phone);
            $this->session->set_userdata('_product_', $product);
            $this->session->set_userdata('_price_', $prod_price);
            redirect(site_url('site/complete_registration'));
        } else {

            $data['title']      = 'Add New Member';
            $data['breadcrumb'] = 'New Member';
            $data['layout']     = 'tree/new_member.php';
            $this->db->select('id, prod_name, prod_price, gst')->where(array(
                'status'          => 'Selling', 
                'show_on_regform' => 'Yes',
            ))->order_by('prod_name', 'ASC');
            $data['products'] = $this->db->get('product')->result_array();
            $data['leg']      = $this->plan_model->create_leg();

            $this->load->view('admin/index', $data);
        } 
    }
 

    ################ MEMBER PART ########################
    public function my_tree()
    {
        $top_id = $this->common_model->filter($this->input->post('top_id'));
      
        if (trim($top_id) == ""):
            $data['title']      = 'User Tree';
            $data['breadcrumb'] = 'Tree';
            $data['layout']     = 'tree/user_tree.php';
            $this->load->view('member/index', $data);

        else:
            if (trim($this->session->user_id) !== "" && $top_id < $this->session->user_id) {
                $this->session->set_flashdata('common_flash', '<div class="alert alert-danger">You cannot view upline tree !</div>');
                redirect('tree/my_tree/');
            }

            redirect(site_url('tree/my_tree/' . $top_id));
        endif;
    }
    
    public function my_tree2()
    {
        $top_id = $this->common_model->filter($this->input->post('top_id'));
      
        if (trim($top_id) == ""):
            $data['title']      = 'User Tree';
            $data['breadcrumb'] = 'Tree';
            $data['layout']     = 'tree/user_tree2.php';
            $this->load->view('member/index', $data);

        else:
            if (trim($this->session->user_id) !== "" && $top_id < $this->session->user_id) {
                $this->session->set_flashdata('common_flash', '<div class="alert alert-danger">You cannot view upline tree !</div>');
                redirect('tree/my_tree2/');
            }

            redirect(site_url('tree/my_tree2/' . $top_id));
        endif;
    }
    
    public function my_tree3()
    {
        $top_id = $this->common_model->filter($this->input->post('top_id'));
      
        if (trim($top_id) == ""):
            $data['title']      = 'User Tree';
            $data['breadcrumb'] = 'Tree';
            $data['layout']     = 'tree/user_tree3.php';
            $this->load->view('member/index', $data);

        else:
            if (trim($this->session->user_id) !== "" && $top_id < $this->session->user_id) {
                $this->session->set_flashdata('common_flash', '<div class="alert alert-danger">You cannot view upline tree !</div>');
                redirect('tree/my_tree3/');
            }

            redirect(site_url('tree/my_tree3/' . $top_id));
        endif;
    }
    
    public function my_tree4()
    {
        $top_id = $this->common_model->filter($this->input->post('top_id'));
      
        if (trim($top_id) == ""):
            $data['title']      = 'User Tree';
            $data['breadcrumb'] = 'Tree';
            $data['layout']     = 'tree/user_tree4.php';
            $this->load->view('member/index', $data);

        else:
            if (trim($this->session->user_id) !== "" && $top_id < $this->session->user_id) {
                $this->session->set_flashdata('common_flash', '<div class="alert alert-danger">You cannot view upline tree !</div>');
                redirect('tree/my_tree4/');
            }

            redirect(site_url('tree/my_tree4/' . $top_id));
        endif;
    }
    
    public function my_tree5()
    {
        $top_id = $this->common_model->filter($this->input->post('top_id'));
      
        if (trim($top_id) == ""):
            $data['title']      = 'User Tree';
            $data['breadcrumb'] = 'Tree';
            $data['layout']     = 'tree/user_tree5.php';
            $this->load->view('member/index', $data);

        else:
            if (trim($this->session->user_id) !== "" && $top_id < $this->session->user_id) {
                $this->session->set_flashdata('common_flash', '<div class="alert alert-danger">You cannot view upline tree !</div>');
                redirect('tree/my_tree5/');
            }

            redirect(site_url('tree/my_tree5/' . $top_id));
        endif;
    }
  
    public function genealogy()
    {

        $data['data']   = $this->db_model->select_multi('total_a, total_b, total_c, total_a_investment, total_b_investment, total_d, total_e, paid_a, paid_b, paid_c, paid_d, paid_e, rank, mypv, total_a_pv, total_b_pv, total_c_pv, A, B, C, D, E', 'member', array('id' =>$this->session->user_id));

        $data['title']      = 'Genealogy ';
        $data['breadcrumb'] = 'Genealogy Report';
        $data['layout']     = 'tree/downlinereport.php';
        $this->load->view('member/index', $data);

    }
    public function total_member(){
        $data['title']      = 'Total Member Report';
        $data['breadcrumb'] = 'Total Member Report';
        $data['layout']     = 'tree/member_list1.php';
        $this->load->view('member/index', $data);

    }
  
    public function new_user($position = '', $sponsor = '')
    {
        redirect(site_url('site/register/'. $position .'/'. $sponsor));
    }

    /* Level Wise 10x10 Matrix report for 7 level */
    public function sponsor_level_list()
    {
        $data['title']      = 'All Direct Level Member';
        $data['breadcrumb'] = 'All Direct Level Member';
        $data['layout']     = 'tree/sponsorwise_level_report.php';
        $this->load->view('member/index', $data);

    }

    public function autopool_one()
    {
        $data['title']      = 'Silver Club Member';
        $data['breadcrumb'] = 'Silver Club Member';
        $data['layout']     = 'tree/autopool_one.php';
        $this->load->view('member/index', $data);

    } 

    public function autopool_second()
    {
        $data['title']      = 'Gold Club Member';
        $data['breadcrumb'] = 'Gold Club Member';
        $data['layout']     = 'tree/autopool_second.php';
        $this->load->view('member/index', $data);

    } 

     public function autopool_third()
    {
        $data['title']      = 'PEARL Club Member';
        $data['breadcrumb'] = 'PEARL Club Member';
        $data['layout']     = 'tree/autopool_third.php';
        $this->load->view('member/index', $data);

    }
    public function autopool_fourth()
    {
        $data['title']      = 'DIAMOND Club Member';
        $data['breadcrumb'] = 'DIAMOND Club Member';
        $data['layout']     = 'tree/autopool_fourth.php';
        $this->load->view('member/index', $data);

    } 

    public function autopool_fifth()
    {
        $data['title']      = 'EMERALD Club Member';
        $data['breadcrumb'] = 'EMERALD Club Member';
        $data['layout']     = 'tree/autopool_fifth.php';
        $this->load->view('member/index', $data);
    }


    public function autopool_team()
    {
        $data['title']      = 'Auto Pool Team Member';
        $data['breadcrumb'] = 'Auto Pool Team Member';
        $data['layout']     = 'tree/autopool_team.php';
        $this->load->view('member/index', $data);

    } 
 
    public function single_leg()
    {
        $data['title']      = 'Single Leg Team Member';
        $data['breadcrumb'] = 'Single Leg Team Member';
        $data['layout']     = 'tree/single_leg_list.php';
        $this->load->view('member/index', $data);

    }

    
    public function alldownline()
    { 
        $config       = $this->db_model->select_multi('*', 'global_setting', array('id' => 1));
        $show_by      = $config->show_by;
        $levelData    = [];
        $ak_data      = [];
        $currentLevel = [$this->session->user_id];
        for ($level   = 1; $level <= 50; $level++) {
            if (empty($currentLevel)) {
                break;
            }
            $placeholders = implode(',', array_fill(0, count($currentLevel), '?'));
            $sql   = "SELECT * FROM member WHERE $show_by IN ($placeholders)";
            $query = $this->db->query($sql, $currentLevel);
            $currentLevel = [];
            foreach ($query->result() as $row) {
                $levelData[$level][] = $row;
                $currentLevel[] = $row->id;
            }
        }
        foreach ($levelData as $level => $members) {
            foreach ($members as $member) {
                $ak_data[] = $member->id;
            }
        }
        $data['show_position']  = $config->show_position;
        $data['active_topup']   = $config->active_topup;
        $data['ak_data']        = $ak_data;
        $data['title']          = 'All Downline List';
        $data['breadcrumb']     = 'All Downline List';
        $data['layout']         = 'tree/downline_list.php';
        $this->load->view('member/index', $data);
    }

    public function direct_list()
    {   
        $config  = $this->db_model->select_multi('*', 'global_setting', array('id' => 1));
        $type    = $this->input->post('type') ?? null;
        $s_date  = $this->input->post('start_date') ?? null;
        $e_date  = $this->input->post('end_date') ?? null;
        
        $this->db->select('*')->from('member')->where('sponsor', $this->session->user_id);
        $dat_type = ($type == 'join_time') ? 'join_time' : 'activation_date';
        if ($s_date) {
            $this->db->where($dat_type . ' >=', $s_date);
        }
        if ($e_date) {
            $this->db->where($dat_type . ' <=', $e_date);
        }
        $data['data']           = $this->db->get()->result_array();
        $data['active_topup']   = $config->active_topup;
        $data['show_position']  = $config->show_position;
        $data['title']          = 'My Direct List';
        $data['breadcrumb']     = 'My Direct List';
        $data['type']           = $type;
        $data['sdate']          = $s_date;
        $data['edate']          = $e_date;
        $data['layout']         = 'tree/referred_list.php';
        $this->load->view('member/index', $data);
    }
    
    // Generation list view
    public function generation_list($id = null)
    {
        $config       = $this->db_model->select_multi('*', 'global_setting', array('id' => 1));
        $gen_level    = $config->gen_level;
        $id           = $id ?? $this->session->user_id;
        $levelData    = [];
        $currentLevel = [$id];
        $allMembers   = []; 
        
        for ($level = 1; $level <= $gen_level; $level++) {
            if (empty($currentLevel)) {
                break;
            }
            $placeholders = implode(',', array_fill(0, count($currentLevel), '?'));
            $sql = "SELECT * FROM member WHERE sponsor IN ($placeholders)";
            $query = $this->db->query($sql, $currentLevel);
            $currentLevel = [];
            
            if ($query->num_rows() > 0) {
                foreach ($query->result() as $row) {
                    $levelData[$level][] = $row;
                    $allMembers[]   = $row;  
                    $currentLevel[] = $row->id;
                }
            }
        }
        $data['show_position']  = $config->show_position;
        $data['active_topup']   = $config->active_topup;
        $data['gen_level']      = $gen_level;
        $data['levelData']      = $levelData;
        $data['allMembers']     = $allMembers;
        $data['breadcrumb']     = 'Generation Wise List';
        $data['layout']         = 'tree/generation_list.php';
        $this->load->view('member/index', $data);
    } 
    
    public function leveldata()
    {
        $data['title']      = 'All Level Data';
        $data['breadcrumb'] = 'All Level Details';
        $data['layout']     = 'tree/level_report.php';
        $this->load->view('member/index', $data);

    }
    /* Level Wise 10x10 Matrix report for 7 level */

    public function level_direct_data()
    {
         $data['title']      = 'All Direct Level Member';
        $data['breadcrumb'] = 'All Direct Level Member';
        $data['layout']     = 'tree/level_direct_report.php';
        $this->load->view('member/index', $data);
    }
    
    // Generation list view
    public function level_list()
    {
        $data['title']      = 'Level Wise List';
        $data['breadcrumb'] = 'Level Wise List';
        $data['layout']     = 'tree/level_list.php';
        $this->load->view('member/index', $data);
    } 
    
    // Level chart view
    public function level_chart()
    {
        $data['title']      = 'Level Chart List';
        $data['breadcrumb'] = 'Level Chart List';
        $data['layout']     = 'tree/level_chart.php';
        $this->load->view('member/index', $data);
    } 
    
    // All list view
    public function all_list()
    {
        $data['title']      = 'All level List';
        $data['breadcrumb'] = 'All level List';
        $data['layout']     = 'tree/all_list.php';
        $this->load->view('member/index', $data);
    }

}
