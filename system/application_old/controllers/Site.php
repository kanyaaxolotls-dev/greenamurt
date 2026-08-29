<?php


// defined('BASEPATH') OR exit('No direct script access allowed');
 
/** 
 * Class Site
 */
class Site extends CI_Controller
{ 
    public function __construct()
    {
        parent::__construct();
        // $this->output->enable_profiler(TRUE);
        require_once APPPATH.'third_party/src/Google_Client.php';
        require_once APPPATH.'third_party/src/contrib/Google_Oauth2Service.php';
    }

    public function index()
    {

        $this->load->view('theme/default/index');
    }

    public function documentation()
    {
        $layout['layout'] = "documentation.php";
        $this->load->view('theme/default/index', $layout);
    }
     
    public function googleLogin(){


        $clientId = '951747205825-i2m4b6vfsui5a2tjj94ju6edp5orm2ej.apps.googleusercontent.com'; //Google client ID
        $clientSecret = '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'; //Google client secret
        $redirectURL = site_url('site/login');
        
        //https://curl.haxx.se/docs/caextract.html
 
        //Call Google API
        $gClient = new Google_Client();
        $gClient->setApplicationName('Login');
        $gClient->setClientId($clientId);
        $gClient->setClientSecret($clientSecret);
        $gClient->setRedirectUri($redirectURL);
        $google_oauthV2 = new Google_Oauth2Service($gClient);
        
        if(isset($_GET['code']))
        {
            $gClient->authenticate($_GET['code']);
            $_SESSION['token'] = $gClient->getAccessToken();
            header('Location: ' . filter_var($redirectURL, FILTER_SANITIZE_URL));
        }

        if (isset($_SESSION['token'])) 
        {
            $gClient->setAccessToken($_SESSION['token']);
        }
        if ($gClient->getAccessToken()) {
            $userProfile = $google_oauthV2->userinfo->get();
         
            //echo $userProfile['name'];

            $googleData = $this->db_model->select_multi('*', 'member', array('email' => $userProfile['email']));

                if($googleData != null){
                $session = md5($googleData->id . time());
                $this->session->set_userdata(array(
                    'user_id' => $googleData->id,
                    'email' => $googleData->email,
                    'name' => $googleData->name,
                    'ip' => $googleData->last_login_ip,
                    'last_login' => $googleData->last_login,
                    'session' => $session,
                ));
                $data2 = array(
                    'last_login_ip' => $this->input->ip_address(),
                    'last_login' => time(),
                    'session' => $session,
                );
            
                $this->db_model->update($data2, 'member', array('id' => $googleData->id));
                }

                // if($googleData != null){
                  $this->load->view('member/index');    
                // }else{
                //     $this->session->set_flashdata('site_flash', '<div class="alert alert-danger">Something is wrong with google signin</div>');
                //     redirect(site_url('site/login'));
                // }
        } 
        else 
        {

            $url = $gClient->createAuthUrl();

            header("Location: $url");
            exit;
        }

    }
    
    private function find_vacant_node($start_id) {
    $queue = array($start_id);
    while (!empty($queue)) {
        $id = array_shift($queue);
        $row = $this->db->select('id, A, B')->where('id', $id)->get('member')->row();
        if (!$row) continue;
        if (empty($row->A)) return array('id' => $id, 'leg' => 'A');
        if (empty($row->B)) return array('id' => $id, 'leg' => 'B');
        $queue[] = $row->A; $queue[] = $row->B;
    }
    return array('id' => $start_id, 'leg' => 'A');
}

public function get_states($id) {
    echo json_encode($this->db->where('country_id', $id)->get('geo_states')->result());
}
public function get_districts($id) {
    echo json_encode($this->db->where('state_id', $id)->get('geo_districts')->result());
}
public function get_tehsils($id) {
    echo json_encode($this->db->where('district_id', $id)->get('geo_tehsils')->result());
}

    public function register1807()
{
    $this->load->model('plan_model');

    // 1. Validation Rules (Sponsor is NOT required to allow for default)
    $this->form_validation->set_rules('name', 'Name', 'trim|required');
    $this->form_validation->set_rules('sponsor', 'Sponsor ID', 'trim');
    $this->form_validation->set_rules('password', 'Password', 'trim|required|min_length[5]');
    $this->form_validation->set_rules('phone', 'Phone No', 'trim|required');

    if ($this->form_validation->run() !== FALSE) {

        // 2. Handle Sponsor (Default to 1001 if blank)
        $sp_post = $this->input->post('sponsor');
        $sponsor = (!empty($sp_post)) ? $this->common_model->filter($sp_post) : 1001;

        // 3. Handle Auto-Placement (Find first vacant spot if blank)
        $pos_post = $this->input->post('position');
        if (empty($pos_post)) {
            // Find open spot starting from the sponsor
            $auto_data = $this->find_vacant_node($sponsor); 
            $position  = $auto_data['id'];
            $leg       = $auto_data['leg'];
        } else {
            $position = $this->common_model->filter($pos_post);
            $leg      = $this->input->post('leg') ? $this->input->post('leg') : 'A';
        }

        // 4. Handle Package & Pricing
        $package_input = $this->input->post('join_package');
        if (empty($package_input)) {
            $first_pkg = $this->db->limit(1)->get('product')->row();
            $join_package_id = $first_pkg->id;
            $join_package_price = $first_pkg->prod_price;
        } else {
            list($join_package_id, $join_package_price) = explode('|', $package_input);
        }

        // 5. Gather other inputs
        $name       = $this->input->post('name');
        $email      = $this->input->post('email') ?? '';
        $phone      = $this->input->post('phone');
        $epin       = $this->input->post('epin');
        $pg         = $this->input->post('pg');
        $pan        = $this->input->post('pan');
        $adhar      = $this->input->post('adhar');
        $username   = time();
        $password   = password_hash($this->input->post('password'), PASSWORD_DEFAULT);
        $divert_pg  = FALSE;

        // 6. Security/Integrity Checks
        // Only validate Sponsor if a value was actually entered
        if (!empty($sp_post)) {
            $spnumber = preg_replace("/[^0-9]+/", "", $sp_post);
            $sp_count = $this->db_model->count_all('member', array('id' => $spnumber));
            if ($sp_count == 0) {
                $this->session->set_flashdata('site_flash', '<div class="alert alert-danger">Entered Sponsor ID does not exist.</div>');
                redirect(site_url('site/register'));
            }
        }

        if ($pan && $this->db_model->count_all('member_profile', array('tax_no' => $pan)) >= 3) {
            $this->session->set_flashdata('site_flash', '<div class="alert alert-danger">PAN card limit reached (Max 3 IDs).</div>');
            redirect(site_url('site/register'));
        }

        // 7. E-pin Validation
        $prod_price = $join_package_price;
        if (trim($epin) !== "") {
            $epin_data = $this->db_model->select_multi('amount, type, status', 'epin', array('epin' => $epin, 'status' => 'Un-used'));
            if (!$epin_data) {
                $this->session->set_flashdata('site_flash', '<div class="alert alert-danger">Invalid or used e-PIN.</div>');
                redirect(site_url('site/register'));
            }
            if ($epin_data->amount < $prod_price) {
                $this->session->set_flashdata('site_flash', '<div class="alert alert-danger">e-PIN value is insufficient for this package.</div>');
                redirect(site_url('site/register'));
            }
            $epin_type = $epin_data->type;
        }

        // 8. Generate Unique Member ID
        $id = rand(100000, 999999);
        while ($this->db_model->count_all('member', array('id' => $id)) > 0) { $id++; }

        // 9. Prepare Data for Member Table
        $data = array(
            'id'              => $id,
            'pass'            => $this->input->post('password'),
            'name'            => $name,
            'email'           => $email,
            'phone'           => $phone,
            'username'        => $username,
            'password'        => $password,
            'sponsor'         => $sponsor,
            'position'        => $position,
            'signup_package'  => $join_package_id,
            'epin'            => $epin,
            'join_time'       => date('Y-m-d'),
            'placement_leg'   => $leg,
            'registration_ip' => $this->input->ip_address(),
            'topup'           => $prod_price,
            'status'          => 'Suspend', // Activated in complete_registration
            'activation_type' => ($join_package_id == 3) ? 'free' : 'paid',
            'join_package'    => $join_package_id,
            'join_package_price' => $join_package_price,
        );
        
        $this->db->insert('member', $data);

        // 10. Link Member to Tree (Update Parent)
        $this->db->where('id', $position)->update('member', array($leg => $id));

        // 11. Initialize Related Tables
        $this->db->insert('wallet', array('userid' => $id, 'balance' => 0.00));
        $this->db->insert('product_wallet', array('userid' => $id, 'balance' => ($data['activation_type'] == 'free' ? -5000 : 0)));
        $this->db->insert('member_profile', array('userid' => $id, 'tax_no' => $pan, 'aadhar_no' => $adhar));
        $this->db->insert('level', array('userid' => $id));
        
        $this->update_level($id); 

        // 12. Handle E-PIN Status
        if (trim($epin) !== '') {
            $up_epin = array('used_by' => $id, 'used_time' => date('Y-m-d'));
            if ($epin_type !== "Multi Use") { $up_epin['status'] = 'Used'; }
            $this->db->where('epin', $epin)->update('epin', $up_epin);
        }

        // Redirect to success/payment gateway
        $this->session->set_userdata('_user_id_', $id);
        redirect(site_url('site/complete_registration'));

    } else {
        // Load default registration form data
        $this->db->select('id, prod_name, prod_price, gst')->where('status', 'Selling')->order_by('prod_name', 'ASC');
        $layout['products'] = $this->db->get('product')->result_array();
        $layout['countries'] = $this->db
        ->order_by('name','ASC')
        ->get('geo_countries')
        ->result_array();
        $layout['leg']      = $this->plan_model->create_leg();
        $layout['layout']   = "register.php";
        $this->load->view('theme/default/index', $layout);
    }
}


    public function get_main_member_info($main_id = 0)
    {
        $main_id = trim($main_id);
        if (empty($main_id)) {
            echo json_encode(['status' => 'error', 'message' => 'Please enter a valid Main Member ID.']);
            return;
        }

        $main_member = $this->db->get_where('member', ['id' => $main_id])->row();
        if (!$main_member) {
            echo json_encode(['status' => 'error', 'message' => 'Main Member ID not found.']);
            return;
        }

        $main_profile = $this->db->get_where('member_profile', ['userid' => $main_id])->row();
        $tax_no = (isset($main_profile->tax_no)) ? $main_profile->tax_no : '';

        $person_count = $this->db_model->get_person_id_count(
            $main_member->phone,
            $main_member->email,
            $main_member->name,
            $tax_no
        );

        if ($person_count >= 3) {
            echo json_encode([
                'status'  => 'error',
                'message' => 'Maximum 3 IDs are allowed for the same information.'
            ]);
            return;
        }

        if ($person_count == 1) {
            if (!empty($main_member->A)) {
                echo json_encode(['status' => 'error', 'message' => 'Left (A) leg of Main ID is already occupied.']);
                return;
            }
            $target_info = 'Creating Additional ID 2 (Placing on Left / A leg of Main ID ' . $main_id . ')';
        } else if ($person_count == 2) {
            if (!empty($main_member->B)) {
                echo json_encode(['status' => 'error', 'message' => 'Right (B) leg of Main ID is already occupied.']);
                return;
            }
            $target_info = 'Creating Additional ID 3 (Placing on Right / B leg of Main ID ' . $main_id . ')';
        } else {
            echo json_encode(['status' => 'error', 'message' => 'Maximum 3 IDs are allowed for the same information.']);
            return;
        }

        $c_row = $this->db->get_where('geo_countries', ['id' => $main_member->country_id])->row();
        $s_row = $this->db->get_where('geo_states', ['id' => $main_member->state_id])->row();
        $d_row = $this->db->get_where('geo_districts', ['id' => $main_member->district_id])->row();
        $t_row = $this->db->get_where('geo_tehsils', ['id' => $main_member->tehsil_id])->row();

        $state_name    = $s_row ? $s_row->name : $main_member->state;
        $district_name = $d_row ? $d_row->name : '';
        $tehsil_name   = $t_row ? $t_row->name : $main_member->city;

        $join_package_val = ($main_member->signup_package ? $main_member->signup_package : 1) . '|' . ($main_member->join_package_price ? $main_member->join_package_price : 0);

        echo json_encode([
            'status'           => 'success',
            'main_id'          => $main_id,
            'name'             => $main_member->name,
            'phone'            => $main_member->phone,
            'email'            => $main_member->email,
            'country_id'       => $main_member->country_id,
            'state_id'         => $main_member->state_id,
            'state_name'       => $state_name,
            'district_id'      => $main_member->district_id,
            'district_name'    => $district_name,
            'tehsil_id'        => $main_member->tehsil_id,
            'tehsil_name'      => $tehsil_name,
            'join_package_val' => $join_package_val,
            'count'            => $person_count,
            'target_info'      => $target_info
        ]);
    }

    public function register()
    {
        $this->load->model('plan_model');

        // Validation Rules
        $this->form_validation->set_rules('name', 'Name', 'trim|required');
        $this->form_validation->set_rules('phone', 'Phone No', 'trim|required');
        $this->form_validation->set_rules('password', 'Password', 'trim|required|min_length[5]');
        $this->form_validation->set_rules('country_id', 'Country', 'required');
        $this->form_validation->set_rules('state_id', 'State', 'required');
        $this->form_validation->set_rules('district_id', 'District', 'required');
        $this->form_validation->set_rules('tehsil_id', 'Tehsil', 'required');
        $this->form_validation->set_rules('email', 'Email', 'trim|required|valid_email');

        if ($this->form_validation->run() !== FALSE) {

            $sub_name  = trim($this->input->post('name'));
            $sub_phone = trim($this->input->post('phone'));
            $sub_email = trim($this->input->post('email'));

            // Check how many existing IDs belong to this exact person
            $person_count = $this->db_model->get_person_id_count($sub_phone, $sub_email, $sub_name);

            if ($person_count >= 3) {
                // 4th ID block rule
                $this->session->set_flashdata('site_flash', '<div class="alert alert-danger">Maximum 3 IDs are allowed for the same information.</div>');
                redirect(site_url('site/register'));
            }

            if ($person_count == 1 || $person_count == 2) {
                // Additional ID flow (ID 2 or ID 3)
                $main_member = $this->db->select('*')
                                        ->from('member')
                                        ->where('LOWER(TRIM(phone))', strtolower($sub_phone))
                                        ->where('LOWER(TRIM(email))', strtolower($sub_email))
                                        ->where('LOWER(TRIM(name))', strtolower($sub_name))
                                        ->order_by('id', 'ASC')
                                        ->get()->row();

                if (!$main_member) {
                    $this->session->set_flashdata('site_flash', '<div class="alert alert-danger">Main Member ID not found.</div>');
                    redirect(site_url('site/register'));
                }

                $main_id = $main_member->id;

                if ($person_count == 1) {
                    // Creating ID 2 -> Place on ID 1's Left / A leg
                    if (!empty($main_member->A)) {
                        $this->session->set_flashdata('site_flash', '<div class="alert alert-danger">Left (A) leg of Main ID is already occupied.</div>');
                        redirect(site_url('site/register'));
                    }
                    $leg = 'A';
                } else {
                    // Creating ID 3 -> Place on ID 1's Right / B leg
                    if (!empty($main_member->B)) {
                        $this->session->set_flashdata('site_flash', '<div class="alert alert-danger">Right (B) leg of Main ID is already occupied.</div>');
                        redirect(site_url('site/register'));
                    }
                    $leg = 'B';
                }

                $sponsor  = $main_id;
                $position = $main_id;

                // Package & Pricing
                $package_input = $this->input->post('join_package');
                list($join_package_id, $join_package_price) = explode('|', $package_input);

                // Geographic info
                $c_id = $this->input->post('country_id') ? $this->input->post('country_id') : $main_member->country_id;
                $s_id = $this->input->post('state_id') ? $this->input->post('state_id') : $main_member->state_id;
                $d_id = $this->input->post('district_id') ? $this->input->post('district_id') : $main_member->district_id;
                $t_id = $this->input->post('tehsil_id') ? $this->input->post('tehsil_id') : $main_member->tehsil_id;

                $c_row = $this->db->get_where('geo_countries', ['id' => $c_id])->row();
                $s_row = $this->db->get_where('geo_states', ['id' => $s_id])->row();
                $d_row = $this->db->get_where('geo_districts', ['id' => $d_id])->row();
                $t_row = $this->db->get_where('geo_tehsils', ['id' => $t_id])->row();

                $country_name  = $c_row ? $c_row->name : $main_member->country;
                $state_name    = $s_row ? $s_row->name : $main_member->state;
                $district_name = $d_row ? $d_row->name : '';
                $tehsil_name   = $t_row ? $t_row->name : $main_member->city;

                $username = time() . rand(10, 99);
                $id = rand(100000, 999999);
                while ($this->db_model->count_all('member', ['id' => $id]) > 0) { $id++; }

                $data = array(
                    'id'                 => $id,
                    'name'               => $main_member->name,
                    'phone'              => $main_member->phone,
                    'email'              => $main_member->email,
                    'username'           => $username,
                    'password'           => password_hash($this->input->post('password'), PASSWORD_DEFAULT),
                    'pass'               => $this->input->post('password'),
                    'sponsor'            => $sponsor,
                    'position'           => $position,
                    'signup_package'     => $join_package_id,
                    'join_time'          => date('Y-m-d'),
                    'placement_leg'      => $leg,
                    'registration_ip'    => $this->input->ip_address(),
                    'topup'              => $join_package_price,
                    'status'             => 'Suspend',
                    'country_id'         => $c_id,
                    'state_id'           => $s_id,
                    'district_id'        => $d_id,
                    'tehsil_id'          => $t_id,
                    'country'            => $country_name,
                    'state'              => $state_name,
                    'city'               => $tehsil_name,
                    'activation_type'    => ($join_package_id == 3) ? 'free' : 'paid',
                    'join_package'       => $join_package_id,
                    'join_package_price' => $join_package_price,
                );

                if ($this->db->insert('member', $data)) {
                    // Update Main ID's tree slot
                    $this->db->where('id', $position)->update('member', array($leg => $id));

                    // Initialize linked tables
                    $this->db->insert('wallet', array('userid' => $id, 'balance' => 0.00));
                    $this->db->insert('product_wallet', array('userid' => $id, 'balance' => ($data['activation_type'] == 'free' ? -5000 : 0)));
                    
                    $main_profile = $this->db->get_where('member_profile', ['userid' => $main_id])->row();
                    $prof_data = array('userid' => $id);
                    if (isset($main_profile->tax_no) && !empty($main_profile->tax_no)) {
                        $prof_data['tax_no'] = $main_profile->tax_no;
                    }
                    $this->db->insert('member_profile', $prof_data);
                    $this->db->insert('level', array('userid' => $id));

                    $this->update_level($id);

                    $this->session->set_userdata('_user_id_', $id);
                    redirect(site_url('site/complete_registration'));
                }

            } else {
                // person_count == 0 -> Normal Single ID Flow (ID 1)
                $c_id = $this->input->post('country_id');
                $s_id = $this->input->post('state_id');
                $d_id = $this->input->post('district_id');
                $t_id = $this->input->post('tehsil_id');

                $c_row = $this->db->get_where('geo_countries', ['id' => $c_id])->row();
                $s_row = $this->db->get_where('geo_states', ['id' => $s_id])->row();
                $d_row = $this->db->get_where('geo_districts', ['id' => $d_id])->row();
                $t_row = $this->db->get_where('geo_tehsils', ['id' => $t_id])->row();

                $country_name  = $c_row ? $c_row->name : '';
                $state_name    = $s_row ? $s_row->name : '';
                $district_name = $d_row ? $d_row->name : '';
                $tehsil_name   = $t_row ? $t_row->name : '';

                // C. TEAM ASSIGNMENT LOGIC (Founding 100 Flow)
                $total_members = $this->db->where('id !=', 1001)->count_all_results('member');

                if ($total_members < 100) {
                    $sponsor = 1001;
                } else {
                    $founders = $this->db->select('id, tehsil_id')
                                         ->where('id !=', 1001)
                                         ->order_by('id', 'ASC')
                                         ->limit(100)
                                         ->get('member')->result();

                    $local_founders = [];
                    foreach ($founders as $f) {
                        if ($f->tehsil_id == $t_id) {
                            $local_founders[] = $f->id;
                        }
                    }

                    if (!empty($local_founders)) {
                        $this->db->where('tehsil_id', $t_id);
                        $this->db->where_not_in('id', $local_founders);
                        $after_count = $this->db->count_all_results('member');

                        $turn = $after_count % count($local_founders);
                        $sponsor = $local_founders[$turn];
                    } else {
                        $sponsor = 1001;
                    }
                }

                // D. Auto-Placement Logic
                $auto_data = $this->find_vacant_node($sponsor); 
                $position  = $auto_data['id'];
                $leg       = $auto_data['leg'];

                // E. Package & Pricing
                $package_input = $this->input->post('join_package');
                list($join_package_id, $join_package_price) = explode('|', $package_input);

                // F. Security & ID Generation
                $username = time() . rand(10,99);
                $id = rand(100000, 999999);
                while ($this->db_model->count_all('member', ['id' => $id]) > 0) { $id++; }

                // G. Prepare Data Array
                $data = array(
                    'id'              => $id,
                    'name'            => $sub_name,
                    'phone'           => $sub_phone,
                    'email'           => $sub_email,
                    'username'        => $username,
                    'password'        => password_hash($this->input->post('password'), PASSWORD_DEFAULT),
                    'pass'            => $this->input->post('password'),
                    'sponsor'         => $sponsor,
                    'position'        => $position,
                    'signup_package'  => $join_package_id,
                    'join_time'       => date('Y-m-d'),
                    'placement_leg'   => $leg,
                    'registration_ip' => $this->input->ip_address(),
                    'topup'           => $join_package_price,
                    'status'          => 'Suspend',
                    
                    // Geography IDs
                    'country_id'      => $c_id,
                    'state_id'        => $s_id,
                    'district_id'     => $d_id,
                    'tehsil_id'       => $t_id,

                    // Geography Names
                    'country'         => $country_name,
                    'state'           => $state_name,
                    'city'            => $tehsil_name,
                    
                    'activation_type' => ($join_package_id == 3) ? 'free' : 'paid',
                    'join_package'    => $join_package_id,
                    'join_package_price' => $join_package_price,
                );
                
                if ($this->db->insert('member', $data)) {
                    // Update Parent Tree
                    $this->db->where('id', $position)->update('member', array($leg => $id));

                    // Initialize Tables
                    $this->db->insert('wallet', array('userid' => $id, 'balance' => 0.00));
                    $this->db->insert('product_wallet', array('userid' => $id, 'balance' => ($data['activation_type'] == 'free' ? -5000 : 0)));
                    $this->db->insert('member_profile', array('userid' => $id));
                    $this->db->insert('level', array('userid' => $id));
                    
                    $this->update_level($id); 

                    $this->session->set_userdata('_user_id_', $id);
                    redirect(site_url('site/complete_registration'));
                }

            }
        } else {
            // Initial form load
            $this->db->select('id, prod_name, prod_price, gst')->where('status', 'Selling')->order_by('prod_name', 'ASC');
            $layout['products'] = $this->db->get('product')->result_array();
            $layout['countries'] = $this->db->order_by('name','ASC')->get('geo_countries')->result_array();
            $layout['layout']   = "register.php";
            $this->load->view('theme/default/index', $layout);
        }
    }



    // public function register()
    // {

    //     #echo "<pre>";print_r($_POST);die();
    //     $this->load->model('plan_model');
    //     /*************************************************************
    //      * We'll register user here using epin or payment gateway
    //      *
    //      * 1) First we'll check if form submitted or not. if not, then will
    //      * display registration form.
    //      * 2) After submiting form, will check for validation error and unique
    //      * field error.
    //      * 3) If everything fine, will find placement location and register user below
    //      * the placement ID.
    //      * 4) if epin selected as payment method, will check valid epin or not and will finalize the
    //      * registration else will show epin error. 
    //      * 5) Else will redirect use to payment gateway. till user make payment ID will
    //      *  be in block state and after successful payment ID will get activated.
    //      * 6) Commissions will generate after successful registration and will show success message.
    //      */ 

    //     $this->form_validation->set_rules('name', 'Name', 'trim|required');
    //     $this->form_validation->set_rules('sponsor', 'Sponsor ID', 'trim');
    //     $this->form_validation->set_rules('password', 'Password', 'trim|required|min_length[5]');
    //     // $this->form_validation->set_rules('password_2', 'Retype Password', 'trim|required|matches[password]');
    //     $this->form_validation->set_rules('phone', 'Phone No', 'trim|required');

    //     if ($this->form_validation->run() !== FALSE) {

    //         // $sponsor   = $this->common_model->filter($this->input->post('sponsor'));

    //         // if ($this->input->post('position')=='' || $this->input->post('position')!=$sponsor){

    //         //     $position1=$this->common_model->filter($this->input->post('position'));

    //         // }else{
    //         //     $position1=$this->input->post('sponsor');
    //         // }


    //         // $package_id_and_price = $this->input->post('join_package');
    //         // list($join_package_id, $join_package_price) = explode('|', $package_id_and_price);
            
    //           $sp_post = $this->input->post('sponsor');
    //           $sponsor = (!empty($sp_post)) ? $this->common_model->filter($sp_post) : 1001;

    //             // MODIFICATION 3: Handle Auto-Placement (Find first vacant spot if blank)
    //             $pos_post = $this->input->post('position');
    //             if (empty($pos_post)) {
    //                 $auto_data = $this->find_vacant_node($sponsor); 
    //                 $position  = $auto_data['id'];
    //                 $leg       = $auto_data['leg'];
    //             } else {
    //                 $position = $this->common_model->filter($pos_post);
    //                 $leg      = $this->input->post('leg') ? $this->input->post('leg') : 'A';
    //             }
        
    //             // MODIFICATION 4: Handle Default Package (Select ID 1 if blank)
    //             $package_id_and_price = $this->input->post('join_package');
    //             if (empty($package_id_and_price)) {
    //                 $first_pkg = $this->db->limit(1)->get('product')->row();
    //                 $join_package_id = $first_pkg->id;
    //                 $join_package_price = $first_pkg->prod_price;
    //             } else {
    //                 list($join_package_id, $join_package_price) = explode('|', $package_id_and_price);
    //             }

    //         $name       = $this->input->post('name');
    //         $sponsor    = $this->common_model->filter($this->input->post('sponsor')); 
    //         $email      = $this->input->post('email') ?? '';
    //         $phone      = $this->input->post('phone');
    //         $leg        = $this->input->post('leg') ? $this->input->post('leg') : 'A';
    //         //$position = $this->input->post('position') ? $this->common_model->filter($this->input->post('position')) : $sponsor;
    //         $position   = $position1;
    //         $product    = $this->input->post('product');
    //         $epin       = $this->input->post('epin');
    //         $pg         = $this->input->post('pg');
    //         $pan        = $this->input->post('pan');
    //         $adhar      = $this->input->post('adhar');
    //         $username   = time();
    //         $password   = password_hash($this->input->post('password'), PASSWORD_DEFAULT);
    //         $divert_pg  = FALSE;

    //         ##############################################################
    //         #
    //         # Check Product Price the validate against epin (If epin
    //         # is selected and not Payment Gateway.
    //         # Here e-PIN amount or PG Amount is Product price + GST/TAX
    //         #
    //         ##############################################################

    //       if (trim($epin) !== "") {
    //             $epin_value = $this->db_model->select_multi('amount, type', 'epin', array(
    //                 'epin'   => $epin,
    //                 'status' => 'Un-used',
    //             ));
    //             $epin_type  = $epin_value->type; 
    //             $epin_value = $epin_value->amount;
    //         }
    //          /* Check Valid sponsor id */
    //          $spid = $this->input->post('sponsor');
    //          $words = explode(config_item('ID_EXT'), $spid);
 
    //          $s2 = $words[1]; 
    //          $valid_sp = strpos($spid, "config_item('ID_EXT')"===0);

    //          $ext = preg_replace("/[^a-zA-Z]+/", "", $spid);
    //          $spnumber = preg_replace("/[^0-9]+/", "", $spid);

    //          $sp = $this->db_model->select_multi('id', 'member', array('id' => $spnumber));

            
    //         if (config_item('ID_EXT')!=$ext and $ext!=''){
    //              $this->session->set_flashdata('site_flash', '<div class="alert alert-danger">Enter Correct Sponsor id with Extension</div>');
    //              redirect(site_url('site/register'));
    //         }
          
    //         if ($spnumber !=$sp->id){
    //              $this->session->set_flashdata('site_flash', '<div class="alert alert-danger">Enter Correct Sponsor id </div>');
    //              redirect(site_url('site/register'));
    //         }

    //         if ($this->db_model->count_all('member_profile', array('tax_no' => $pan)) >= 3) {
    //             $this->session->set_flashdata('site_flash', '<div class="alert alert-danger">Already used 3 times please try other !</div>');
    //             redirect(site_url('site/register'));
    //         }

    //         //  if ($this->db_model->count_all('member', array('email' => $email)) > 0) {
    //         //     $this->session->set_flashdata('site_flash', '<div class="alert alert-danger">Email already Registered With Us !</div>');
    //         //     redirect(site_url('site/register'));
    //         //  }
                        
    //         if (trim($epin) !== "") {
    //             $epin_value = $this->db_model->select_multi('amount, type', 'epin', array(
    //                 'epin'   => $epin,
    //                 'status' => 'Un-used',
    //             ));
    //             $epin_type  = $epin_value->type;
    //             $epin_value = $epin_value->amount;
    //         }
            
    //         if (config_item('show_join_product') == "Yes"):
    //             $product_detail = $this->db_model->select_multi('prod_price, gst, pv, qty, matching_income', 'product', array('id' => $product));
    //             $mybusiness = $product_detail->matching_income;
    //             if ($product_detail->qty == "0") {
    //                 $this->session->set_flashdata('site_flash', '<div class="alert alert-danger">The selected product/service is out of stock. Please contact admin.</div>');
    //                 redirect(site_url('site/register'));
    //             }
    //         endif;

    //         #####################################################################
    //         #
    //         # Check if either epin or payment gateway field is selected or not.
    //         #
    //         #####################################################################

    //         if (trim($epin) == "" && trim($pg) == "" && config_item('free_registration') == "No") {

    //             if (config_item('enable_epin') == "Yes" && config_item('enable_pg') == "Yes") {
    //                 $this->session->set_flashdata('site_flash', '<div class="alert alert-danger">Please enter correct e-PIN or Choose Payment Gateway Option</div>');
    //             } else {
    //                 if (config_item('enable_epin') == "Yes" && config_item('enable_pg') == "No") {
    //                     $this->session->set_flashdata('site_flash', '<div class="alert alert-danger">Please enter correct e-PIN.</div>');
    //                 } else {
    //                     if (config_item('enable_epin') == "No" && config_item('enable_pg') == "Yes") {
    //                         $this->session->set_flashdata('site_flash', '<div class="alert alert-danger">Please choose Payment Gateway option.</div>');
    //                     }
    //                 }
    //             }
    //             redirect(site_url('site/register'));
    //         }

    //         ########################################################
    //         #
    //         # check if e-pin value is matched with product or no
    //         #
    //         ########################################################
    //         $prod_price = 0;
    //         if (config_item('free_registration') == "No") {
    //             if ((trim($epin) !== "" || trim($pg) !== "")) {
    //                 if (trim($epin) !== "") {
    //                     if (config_item('show_join_product') == "Yes") {
    //                         $prod_price = $product_detail->prod_price + ($product_detail->prod_price * $product_detail->gst / 100);
    //                         if (trim($prod_price) > trim($epin_value)) {
    //                             $this->session->set_flashdata('site_flash', '<div class="alert alert-danger">e-PIN value is less then product price + GST/Tax. Minimum e-PIN value must be: ' . config_item('currency') . ' ' . $prod_price . ' and you entered e-pin value of: ' . config_item('currency') . ' ' . $epin_value . '</div>');
    //                             redirect(site_url('site/register'));
    //                         }
    //                     }
    //                 } else {
    //                     $prod_price = $product_detail->prod_price + ($product_detail->prod_price * $product_detail->gst / 100);
    //                     $divert_pg  = TRUE;
    //                 }
    //             }
    //         }

    //         if (config_item('show_join_product') == "No" && config_item('free_registration') == "No" && trim($pg) == "") {
    //             $prod_price = $this->input->post('amt_to_pay');
    //             $product    = 'N/A';
    //             if ($epin_value < $prod_price) {
    //                 $this->session->set_flashdata('site_flash', '<div class="alert alert-danger">Please enter correct e-PIN of worth: ' . config_item('currency') . $prod_price . ' or more.</div>');
    //                 redirect(site_url('site/register'));

    //             }
    //         }

    //         ###############################################################################
    //         #
    //         # Now get selected blank Leg (eg: A, B, C) of position ID
    //         # If Position id is blank, sponsor ID will become position ID
    //         # If selected leg of position is not blank, will return error.
    //         #
    //         ###############################################################################
    //         if (config_item('autopool_registration') !== "Yes"):
    //             if (trim($position) == ""):
    //                 $position = $sponsor;
    //             endif;
    //             if ($position == $sponsor):
    //                 $position = $this->plan_model->find_extreme_position($sponsor, $leg);
    //             else:
    //                 if ($this->plan_model->check_position($position, $leg) !== $position):
    //                     $this->session->set_flashdata('site_flash', '<div class="alert alert-danger">The selected Position of Placement ID is not empty.</div>');
    //                     redirect(site_url('site/register'));
    //                 endif;
    //             endif;
    //         else:
    //             $autopool_data = $this->plan_model->find_autopool_field($sponsor);
    //             $position      = $autopool_data['id'];
    //             $leg           = $autopool_data['position'];
    //         endif;
    //         ##############################################################################
    //         #
    //         # Everything seems fine, Now will make database entry
    //         #
    //         ##############################################################################
    //         $id = rand(100000, 999999);
    //         if ($this->db_model->count_all('member', array('id' => $id)) > 0) {
    //             $id = $id + 1;
    //             if ($this->db_model->count_all('member', array('id' => $id)) > 0) {
    //                 $id = $id + 2;
    //                 if ($this->db_model->count_all('member', array('id' => $id)) > 0) {
    //                     $id = $id + 3;
    //                 }
    //             }
    //         }
        
    //         // $userinfo = $this->db_model->select_multi('max(id) as id', 'member');
    //         // $akid = $userinfo->id + 1;
    //         // $id = $akid;
        
    //         if (config_item('show_join_product') !== "Yes"):
    //             $mybusiness = $prod_price;
    //         endif;
            
    //         $activation_type = strtolower(trim($this->input->post('free_join') ?? ''));

    //         if ($activation_type === '' || $activation_type === 'no') {
    //             $activation_type = 'paid';
    //         } else {
    //             $activation_type = 'free';
    //         }

    //         if ($join_package_id == 3 && $join_package_price == 5000) 
    //         {
    //             $activation_type = 'free';
    //         }

    //         $position_id_valid = $this->db_model->select('id', 'member', array('id'=> $position));

    //         if($position_id_valid == "")
    //         {
    //             $this->session->set_flashdata('site_flash', '<div class="alert alert-danger">The Position ID of the user is not valid.</div>');
    //             redirect(site_url('site/register'));
    //         }
            

    //         $data = array(
    //             'id'              => $id,
    //             'pass'            => $this->input->post('password'),
    //             'name'            => $name,
    //             'email'           => $email,
    //             'phone'           => $phone,
    //             'username'        => $username,
    //             'password'        => $password,
    //             'sponsor'         => $sponsor,
    //             'position'        => $position,
    //             'signup_package'  => $product,
    //             'epin'            => $epin,
    //             'country'         => $this->input->post('country') ?? '',
    //             'trans_password'  => $this->input->post('tran_pass') ?? '',
    //             'state'           => $this->input->post('state') ?? '',
    //             'city'            => $this->input->post('city') ?? '',
    //             'address'         => $this->input->post('address') ?? '',
    //             'pincode'         => $this->input->post('pincode') ?? '',
    //             'join_time'       => date('Y-m-d'),
    //             'placement_leg'   => $leg,
    //             'registration_ip' => $this->input->ip_address(),
    //             'topup'           => $prod_price,
    //             'my_business'     => $mybusiness,
    //             'mypv'            => $product_detail->pv ? $product_detail->pv : 0,
    //             'status'          => 'Suspend',
    //             'activation_type' => $activation_type,
    //             'join_package' => $join_package_id,
    //             'join_package_price' => $join_package_price,
    //         );
            
    //         $this->db->insert('member', $data);
    //         $user_id = $this->db_model->select('id', 'member', array(
    //             'phone'           => $phone,
    //             'username'        => $username,
    //             'registration_ip' => $this->input->ip_address,
    //             'epin'            => $epin,
    //         ));

    //         if ($user_id):

    //             if($activation_type == 'free')
    //             {
    //                 $balance = "-5000";
    //             }else
    //             {
    //                 $balance = "0";
    //             }

    //             $data = array(
    //                 'userid'  => $user_id,
    //                 'balance' => "0.00",
    //             );
    //             $this->db->insert('wallet', $data);

    //             $data = array(
    //                 'userid'  => $user_id,
    //                 'balance' => $balance,
    //             );
    //             $this->db->insert('product_wallet', $data);

    //             $data = array(
    //                 'userid'          => $user_id,
    //                 'tax_no'          => $pan ?? '',
    //                 'aadhar_no'       => $adhar ?? '',
    //                 'bank_ifsc'       => $this->input->post('ifsc') ?? '',
    //                 'bank_name'       => $this->input->post('bank_name') ?? '',
    //                 'bank_ac_no'      => $this->input->post('acc_name') ?? '',
    //                 'bank_branch'     => $this->input->post('branch_name') ?? '',
    //             );
    //             $this->db->insert('member_profile', $data);

    //             $data = array(
    //                 'userid' => $user_id,
    //             );
    //             $this->db->insert('level', $data);
    //             $this->update_level($user_id); 

    //         else:
    //             $this->session->set_flashdata('site_flash', '<div class="alert alert-danger">Some error occured while registering. please contact admin or try again.</div>');
    //             redirect(site_url('site/register'));
    //         endif;

    //         $data = array(
    //             $leg => $user_id,
    //         );
    //         $this->db->where('id', $position);
    //         $this->db->update('member', $data);
    //         if (trim($epin) !== '' && $epin_type == "Multi Use"):
    //             $amount = $epin_value - $prod_price;
    //             if ($amount <= 0):
    //                 $data = array(
    //                     'status'    => 'Used',
    //                     'used_by'   => $user_id,
    //                     'used_time' => date('Y-m-d'),
    //                 );
    //             else:
    //                 $data = array(
    //                     'amount'    => $amount,
    //                     'used_by'   => $user_id,
    //                     'used_time' => date('Y-m-d'),
    //                 );
    //             endif;
    //             $this->db->where('epin', $epin);
    //             $this->db->update('epin', $data);
    //         else:
    //             $data = array(
    //                 'status'    => 'Used',
    //                 'used_by'   => $user_id,
    //                 'used_time' => date('Y-m-d'),
    //             );
    //             $this->db->where('epin', $epin);
    //             $this->db->update('epin', $data);
    //         endif;

    //         ##########################################################################
    //         #
    //         # Now will send welcome email and SMS.
    //         #
    //         ##########################################################################
           
    //         $this->load->library('email');
    //         $sub   = "Welcome to " . config_item('company_name');
    //         $pwd   = $this->input->post('password');
    //         $twd   = $this->input->post('tran_pass');
    //         $web   = $_SERVER['HTTP_HOST'].'system/site/login';
    //         $cname = config_item('company_name');
    //         $msg = "
    //             <html>
    //             <head>
    //             <title>Thank you for registration</title>
    //             </head>
    //             <body>
                
    //             <p>Dear $name,</p>
                
    //             <p>Thank you for choosing  $cname. We hope you had good experience ! </p>
                
    //             <p>We always strive to keep improving the services we offer. Our highest priority is to ensure that these services meet your expectations.</p>
                
    //             <p>Your user account details as below !</p>
                
    //             <p><b>Username</b> :  $user_id</p>
    //             <p><b>Login Password</b> :  $pwd </p>
    //             <p><b>Transaction Password</b> :  $twd </p>
    //             <b>Website URL </b> : www.$web <br>
                
    //             <p>Thank you for your time !</p>
                
    //             <p>Best regards !</p>
    //             <b>$cname</b>
                
    //             </body>
    //             </html>
    //             ";
    //         $this->email->from('', $cname);
    //         $this->email->to($email);
    //         $this->email->subject($sub);
    //         $this->email->message($msg);
    //         $this->email->send();
    //         // $message = "🏢 $cname\n\nDear $name,\nThank you for becoming a distributor of $cname.\nYour member login details are as follows :\n\nUserid : $user_id\nPassword : $pwd\Transaction Password : $twd\nVisit  $web \n\n*Please edit your profile and update your KYC its compulsary*";
    //         // $user    = $this->common_model->send_message($phone, $message);
            
    //         ##########################################################################
    //         #
    //         # Now will Redirect to Payment Gateway (If need) or Success Page. At that
    //         # Page we'll generate income or rewards. Here we'll save some basic
    //         # important Data with session.
    //         #
    //         ##########################################################################
    //         $this->session->set_userdata('_user_id_', $user_id);
    //         $this->session->set_tempdata('_auto_user_id_', $user_id, '300');
    //         $this->session->set_tempdata('_inv_id_', $user_id);
    //         $this->session->set_tempdata('_type_', 'userid');
    //         $this->session->set_userdata('_user_name_', $name);
    //         $this->session->set_userdata('_sponsor_', $sponsor);
    //         $this->session->set_userdata('_position_', $position);
    //         $this->session->set_userdata('_address_', $address_1);
    //         $this->session->set_userdata('_email_', $email);
    //         $this->session->set_userdata('_phone_', $phone);
    //         $this->session->set_userdata('_product_', $product); 
    //         $this->session->set_userdata('_price_', $prod_price);
           
    //         if ($divert_pg == TRUE):
    //           redirect(site_url('gateway/registration_form'));
    //             //redirect(site_url('gateway/payu'));
    //         else:
    //             redirect(site_url('site/complete_registration'));
    //         endif;

    //     } else {
    //         ####################################################
    //         #
    //         # If form is not submitted then, will show the
    //         # default registration form.
    //         #
    //         ####################################################
    //         $this->db->select('id, prod_name, prod_price, gst')->where(array(
    //             'status'          => 'Selling',
    //             'show_on_regform' => 'Yes',
    //         ))->order_by('prod_name', 'ASC');
    //         $layout['products'] = $this->db->get('product')->result_array();
    //         $layout['leg']      = $this->plan_model->create_leg();
    //         $layout['layout']   = "register.php";
    //         $this->load->view('theme/default/index', $layout);
    //     }
    // }
    
    // public function register0807()
    // {

    //     #echo "<pre>";print_r($_POST);die();
    //     $this->load->model('plan_model');
    //     /*************************************************************
    //      * We'll register user here using epin or payment gateway
    //      *
    //      * 1) First we'll check if form submitted or not. if not, then will
    //      * display registration form.
    //      * 2) After submiting form, will check for validation error and unique
    //      * field error.
    //      * 3) If everything fine, will find placement location and register user below
    //      * the placement ID.
    //      * 4) if epin selected as payment method, will check valid epin or not and will finalize the
    //      * registration else will show epin error. 
    //      * 5) Else will redirect use to payment gateway. till user make payment ID will
    //      *  be in block state and after successful payment ID will get activated.
    //      * 6) Commissions will generate after successful registration and will show success message.
    //      */ 

    //     $this->form_validation->set_rules('name', 'Name', 'trim|required');
    //     $this->form_validation->set_rules('sponsor', 'Sponsor ID', 'trim|required');
    //     $this->form_validation->set_rules('password', 'Password', 'trim|required|min_length[5]');
    //     // $this->form_validation->set_rules('password_2', 'Retype Password', 'trim|required|matches[password]');
    //     $this->form_validation->set_rules('phone', 'Phone No', 'trim|required');

    //     if ($this->form_validation->run() !== FALSE) {

    //         $sponsor   = $this->common_model->filter($this->input->post('sponsor'));

    //         if ($this->input->post('position')=='' || $this->input->post('position')!=$sponsor){

    //             $position1=$this->common_model->filter($this->input->post('position'));

    //         }else{
    //             $position1=$this->input->post('sponsor');
    //         }


    //         $package_id_and_price = $this->input->post('join_package');
    //         list($join_package_id, $join_package_price) = explode('|', $package_id_and_price);

    //         $name       = $this->input->post('name');
    //         $sponsor    = $this->common_model->filter($this->input->post('sponsor')); 
    //         $email      = $this->input->post('email') ?? '';
    //         $phone      = $this->input->post('phone');
    //         $leg        = $this->input->post('leg') ? $this->input->post('leg') : 'A';
    //         //$position = $this->input->post('position') ? $this->common_model->filter($this->input->post('position')) : $sponsor;
    //         $position   = $position1;
    //         $product    = $this->input->post('product');
    //         $epin       = $this->input->post('epin');
    //         $pg         = $this->input->post('pg');
    //         $pan        = $this->input->post('pan');
    //         $adhar      = $this->input->post('adhar');
    //         $username   = time();
    //         $password   = password_hash($this->input->post('password'), PASSWORD_DEFAULT);
    //         $divert_pg  = FALSE;

    //         ##############################################################
    //         #
    //         # Check Product Price the validate against epin (If epin
    //         # is selected and not Payment Gateway.
    //         # Here e-PIN amount or PG Amount is Product price + GST/TAX
    //         #
    //         ##############################################################

    //       if (trim($epin) !== "") {
    //             $epin_value = $this->db_model->select_multi('amount, type', 'epin', array(
    //                 'epin'   => $epin,
    //                 'status' => 'Un-used',
    //             ));
    //             $epin_type  = $epin_value->type; 
    //             $epin_value = $epin_value->amount;
    //         }
    //          /* Check Valid sponsor id */
    //          $spid = $this->input->post('sponsor');
    //          $words = explode(config_item('ID_EXT'), $spid);
 
    //          $s2 = $words[1]; 
    //          $valid_sp = strpos($spid, "config_item('ID_EXT')"===0);

    //          $ext = preg_replace("/[^a-zA-Z]+/", "", $spid);
    //          $spnumber = preg_replace("/[^0-9]+/", "", $spid);

    //          $sp = $this->db_model->select_multi('id', 'member', array('id' => $spnumber));

            
    //         if (config_item('ID_EXT')!=$ext and $ext!=''){
    //              $this->session->set_flashdata('site_flash', '<div class="alert alert-danger">Enter Correct Sponsor id with Extension</div>');
    //              redirect(site_url('site/register'));
    //         }
          
    //         if ($spnumber !=$sp->id){
    //              $this->session->set_flashdata('site_flash', '<div class="alert alert-danger">Enter Correct Sponsor id </div>');
    //              redirect(site_url('site/register'));
    //         }

    //         if ($this->db_model->count_all('member_profile', array('tax_no' => $pan)) >= 3) {
    //             $this->session->set_flashdata('site_flash', '<div class="alert alert-danger">Already used 3 times please try other !</div>');
    //             redirect(site_url('site/register'));
    //         }

    //         //  if ($this->db_model->count_all('member', array('email' => $email)) > 0) {
    //         //     $this->session->set_flashdata('site_flash', '<div class="alert alert-danger">Email already Registered With Us !</div>');
    //         //     redirect(site_url('site/register'));
    //         //  }
                        
    //         if (trim($epin) !== "") {
    //             $epin_value = $this->db_model->select_multi('amount, type', 'epin', array(
    //                 'epin'   => $epin,
    //                 'status' => 'Un-used',
    //             ));
    //             $epin_type  = $epin_value->type;
    //             $epin_value = $epin_value->amount;
    //         }
            
    //         if (config_item('show_join_product') == "Yes"):
    //             $product_detail = $this->db_model->select_multi('prod_price, gst, pv, qty, matching_income', 'product', array('id' => $product));
    //             $mybusiness = $product_detail->matching_income;
    //             if ($product_detail->qty == "0") {
    //                 $this->session->set_flashdata('site_flash', '<div class="alert alert-danger">The selected product/service is out of stock. Please contact admin.</div>');
    //                 redirect(site_url('site/register'));
    //             }
    //         endif;

    //         #####################################################################
    //         #
    //         # Check if either epin or payment gateway field is selected or not.
    //         #
    //         #####################################################################

    //         if (trim($epin) == "" && trim($pg) == "" && config_item('free_registration') == "No") {

    //             if (config_item('enable_epin') == "Yes" && config_item('enable_pg') == "Yes") {
    //                 $this->session->set_flashdata('site_flash', '<div class="alert alert-danger">Please enter correct e-PIN or Choose Payment Gateway Option</div>');
    //             } else {
    //                 if (config_item('enable_epin') == "Yes" && config_item('enable_pg') == "No") {
    //                     $this->session->set_flashdata('site_flash', '<div class="alert alert-danger">Please enter correct e-PIN.</div>');
    //                 } else {
    //                     if (config_item('enable_epin') == "No" && config_item('enable_pg') == "Yes") {
    //                         $this->session->set_flashdata('site_flash', '<div class="alert alert-danger">Please choose Payment Gateway option.</div>');
    //                     }
    //                 }
    //             }
    //             redirect(site_url('site/register'));
    //         }

    //         ########################################################
    //         #
    //         # check if e-pin value is matched with product or no
    //         #
    //         ########################################################
    //         $prod_price = 0;
    //         if (config_item('free_registration') == "No") {
    //             if ((trim($epin) !== "" || trim($pg) !== "")) {
    //                 if (trim($epin) !== "") {
    //                     if (config_item('show_join_product') == "Yes") {
    //                         $prod_price = $product_detail->prod_price + ($product_detail->prod_price * $product_detail->gst / 100);
    //                         if (trim($prod_price) > trim($epin_value)) {
    //                             $this->session->set_flashdata('site_flash', '<div class="alert alert-danger">e-PIN value is less then product price + GST/Tax. Minimum e-PIN value must be: ' . config_item('currency') . ' ' . $prod_price . ' and you entered e-pin value of: ' . config_item('currency') . ' ' . $epin_value . '</div>');
    //                             redirect(site_url('site/register'));
    //                         }
    //                     }
    //                 } else {
    //                     $prod_price = $product_detail->prod_price + ($product_detail->prod_price * $product_detail->gst / 100);
    //                     $divert_pg  = TRUE;
    //                 }
    //             }
    //         }

    //         if (config_item('show_join_product') == "No" && config_item('free_registration') == "No" && trim($pg) == "") {
    //             $prod_price = $this->input->post('amt_to_pay');
    //             $product    = 'N/A';
    //             if ($epin_value < $prod_price) {
    //                 $this->session->set_flashdata('site_flash', '<div class="alert alert-danger">Please enter correct e-PIN of worth: ' . config_item('currency') . $prod_price . ' or more.</div>');
    //                 redirect(site_url('site/register'));

    //             }
    //         }

    //         ###############################################################################
    //         #
    //         # Now get selected blank Leg (eg: A, B, C) of position ID
    //         # If Position id is blank, sponsor ID will become position ID
    //         # If selected leg of position is not blank, will return error.
    //         #
    //         ###############################################################################
    //         if (config_item('autopool_registration') !== "Yes"):
    //             if (trim($position) == ""):
    //                 $position = $sponsor;
    //             endif;
    //             if ($position == $sponsor):
    //                 $position = $this->plan_model->find_extreme_position($sponsor, $leg);
    //             else:
    //                 if ($this->plan_model->check_position($position, $leg) !== $position):
    //                     $this->session->set_flashdata('site_flash', '<div class="alert alert-danger">The selected Position of Placement ID is not empty.</div>');
    //                     redirect(site_url('site/register'));
    //                 endif;
    //             endif;
    //         else:
    //             $autopool_data = $this->plan_model->find_autopool_field($sponsor);
    //             $position      = $autopool_data['id'];
    //             $leg           = $autopool_data['position'];
    //         endif;
    //         ##############################################################################
    //         #
    //         # Everything seems fine, Now will make database entry
    //         #
    //         ##############################################################################
    //         $id = rand(100000, 999999);
    //         if ($this->db_model->count_all('member', array('id' => $id)) > 0) {
    //             $id = $id + 1;
    //             if ($this->db_model->count_all('member', array('id' => $id)) > 0) {
    //                 $id = $id + 2;
    //                 if ($this->db_model->count_all('member', array('id' => $id)) > 0) {
    //                     $id = $id + 3;
    //                 }
    //             }
    //         }
        
    //         // $userinfo = $this->db_model->select_multi('max(id) as id', 'member');
    //         // $akid = $userinfo->id + 1;
    //         // $id = $akid;
        
    //         if (config_item('show_join_product') !== "Yes"):
    //             $mybusiness = $prod_price;
    //         endif;
            
    //         $activation_type = strtolower(trim($this->input->post('free_join') ?? ''));

    //         if ($activation_type === '' || $activation_type === 'no') {
    //             $activation_type = 'paid';
    //         } else {
    //             $activation_type = 'free';
    //         }

    //         if ($join_package_id == 3 && $join_package_price == 5000) 
    //         {
    //             $activation_type = 'free';
    //         }

    //         $position_id_valid = $this->db_model->select('id', 'member', array('id'=> $position));

    //         if($position_id_valid == "")
    //         {
    //             $this->session->set_flashdata('site_flash', '<div class="alert alert-danger">The Position ID of the user is not valid.</div>');
    //             redirect(site_url('site/register'));
    //         }
            

    //         $data = array(
    //             'id'              => $id,
    //             'pass'            => $this->input->post('password'),
    //             'name'            => $name,
    //             'email'           => $email,
    //             'phone'           => $phone,
    //             'username'        => $username,
    //             'password'        => $password,
    //             'sponsor'         => $sponsor,
    //             'position'        => $position,
    //             'signup_package'  => $product,
    //             'epin'            => $epin,
    //             'country'         => $this->input->post('country') ?? '',
    //             'trans_password'  => $this->input->post('tran_pass') ?? '',
    //             'state'           => $this->input->post('state') ?? '',
    //             'city'            => $this->input->post('city') ?? '',
    //             'address'         => $this->input->post('address') ?? '',
    //             'pincode'         => $this->input->post('pincode') ?? '',
    //             'join_time'       => date('Y-m-d'),
    //             'placement_leg'   => $leg,
    //             'registration_ip' => $this->input->ip_address(),
    //             'topup'           => $prod_price,
    //             'my_business'     => $mybusiness,
    //             'mypv'            => $product_detail->pv ? $product_detail->pv : 0,
    //             'status'          => 'Suspend',
    //             'activation_type' => $activation_type,
    //             'join_package' => $join_package_id,
    //             'join_package_price' => $join_package_price,
    //         );
            
    //         $this->db->insert('member', $data);
    //         $user_id = $this->db_model->select('id', 'member', array(
    //             'phone'           => $phone,
    //             'username'        => $username,
    //             'registration_ip' => $this->input->ip_address,
    //             'epin'            => $epin,
    //         ));

    //         if ($user_id):

    //             if($activation_type == 'free')
    //             {
    //                 $balance = "-5000";
    //             }else
    //             {
    //                 $balance = "0";
    //             }

    //             $data = array(
    //                 'userid'  => $user_id,
    //                 'balance' => "0.00",
    //             );
    //             $this->db->insert('wallet', $data);

    //             $data = array(
    //                 'userid'  => $user_id,
    //                 'balance' => $balance,
    //             );
    //             $this->db->insert('product_wallet', $data);

    //             $data = array(
    //                 'userid'          => $user_id,
    //                 'tax_no'          => $pan ?? '',
    //                 'aadhar_no'       => $adhar ?? '',
    //                 'bank_ifsc'       => $this->input->post('ifsc') ?? '',
    //                 'bank_name'       => $this->input->post('bank_name') ?? '',
    //                 'bank_ac_no'      => $this->input->post('acc_name') ?? '',
    //                 'bank_branch'     => $this->input->post('branch_name') ?? '',
    //             );
    //             $this->db->insert('member_profile', $data);

    //             $data = array(
    //                 'userid' => $user_id,
    //             );
    //             $this->db->insert('level', $data);
    //             $this->update_level($user_id); 

    //         else:
    //             $this->session->set_flashdata('site_flash', '<div class="alert alert-danger">Some error occured while registering. please contact admin or try again.</div>');
    //             redirect(site_url('site/register'));
    //         endif;

    //         $data = array(
    //             $leg => $user_id,
    //         );
    //         $this->db->where('id', $position);
    //         $this->db->update('member', $data);
    //         if (trim($epin) !== '' && $epin_type == "Multi Use"):
    //             $amount = $epin_value - $prod_price;
    //             if ($amount <= 0):
    //                 $data = array(
    //                     'status'    => 'Used',
    //                     'used_by'   => $user_id,
    //                     'used_time' => date('Y-m-d'),
    //                 );
    //             else:
    //                 $data = array(
    //                     'amount'    => $amount,
    //                     'used_by'   => $user_id,
    //                     'used_time' => date('Y-m-d'),
    //                 );
    //             endif;
    //             $this->db->where('epin', $epin);
    //             $this->db->update('epin', $data);
    //         else:
    //             $data = array(
    //                 'status'    => 'Used',
    //                 'used_by'   => $user_id,
    //                 'used_time' => date('Y-m-d'),
    //             );
    //             $this->db->where('epin', $epin);
    //             $this->db->update('epin', $data);
    //         endif;

    //         ##########################################################################
    //         #
    //         # Now will send welcome email and SMS.
    //         #
    //         ##########################################################################
           
    //         $this->load->library('email');
    //         $sub   = "Welcome to " . config_item('company_name');
    //         $pwd   = $this->input->post('password');
    //         $twd   = $this->input->post('tran_pass');
    //         $web   = $_SERVER['HTTP_HOST'].'system/site/login';
    //         $cname = config_item('company_name');
    //         $msg = "
    //             <html>
    //             <head>
    //             <title>Thank you for registration</title>
    //             </head>
    //             <body>
                
    //             <p>Dear $name,</p>
                
    //             <p>Thank you for choosing  $cname. We hope you had good experience ! </p>
                
    //             <p>We always strive to keep improving the services we offer. Our highest priority is to ensure that these services meet your expectations.</p>
                
    //             <p>Your user account details as below !</p>
                
    //             <p><b>Username</b> :  $user_id</p>
    //             <p><b>Login Password</b> :  $pwd </p>
    //             <p><b>Transaction Password</b> :  $twd </p>
    //             <b>Website URL </b> : www.$web <br>
                
    //             <p>Thank you for your time !</p>
                
    //             <p>Best regards !</p>
    //             <b>$cname</b>
                
    //             </body>
    //             </html>
    //             ";
    //         $this->email->from('', $cname);
    //         $this->email->to($email);
    //         $this->email->subject($sub);
    //         $this->email->message($msg);
    //         $this->email->send();
    //         // $message = "🏢 $cname\n\nDear $name,\nThank you for becoming a distributor of $cname.\nYour member login details are as follows :\n\nUserid : $user_id\nPassword : $pwd\Transaction Password : $twd\nVisit  $web \n\n*Please edit your profile and update your KYC its compulsary*";
    //         // $user    = $this->common_model->send_message($phone, $message);
            
    //         ##########################################################################
    //         #
    //         # Now will Redirect to Payment Gateway (If need) or Success Page. At that
    //         # Page we'll generate income or rewards. Here we'll save some basic
    //         # important Data with session.
    //         #
    //         ##########################################################################
    //         $this->session->set_userdata('_user_id_', $user_id);
    //         $this->session->set_tempdata('_auto_user_id_', $user_id, '300');
    //         $this->session->set_tempdata('_inv_id_', $user_id);
    //         $this->session->set_tempdata('_type_', 'userid');
    //         $this->session->set_userdata('_user_name_', $name);
    //         $this->session->set_userdata('_sponsor_', $sponsor);
    //         $this->session->set_userdata('_position_', $position);
    //         $this->session->set_userdata('_address_', $address_1);
    //         $this->session->set_userdata('_email_', $email);
    //         $this->session->set_userdata('_phone_', $phone);
    //         $this->session->set_userdata('_product_', $product); 
    //         $this->session->set_userdata('_price_', $prod_price);
           
    //         if ($divert_pg == TRUE):
    //           redirect(site_url('gateway/registration_form'));
    //             //redirect(site_url('gateway/payu'));
    //         else:
    //             redirect(site_url('site/complete_registration'));
    //         endif;

    //     } else {
    //         ####################################################
    //         #
    //         # If form is not submitted then, will show the
    //         # default registration form.
    //         #
    //         ####################################################
    //         $this->db->select('id, prod_name, prod_price, gst')->where(array(
    //             'status'          => 'Selling',
    //             'show_on_regform' => 'Yes',
    //         ))->order_by('prod_name', 'ASC');
    //         $layout['products'] = $this->db->get('product')->result_array();
    //         $layout['leg']      = $this->plan_model->create_leg();
    //         $layout['layout']   = "register.php";
    //         $this->load->view('theme/default/index', $layout);
    //     }
    // }
    

    public function complete_registration()
    {
        if ($this->session->_user_id_ > 0) { 
            
            $layout['layout'] = "success.php";
            $this->load->view('theme/default/index', $layout);

            $this->db->where('id', $this->session->_user_id_);
            $this->db->update('member', array('status' => 'Active'));
            
        //     $lst_usrid=$this->db->select('userid')->order_by('id',"desc")->limit(1)->get('pool_one')->row();
        //     $chk_id_pool = $this->db_model->select('userid', 'pool_one', array('userid' =>$this->session->_user_id_));
    
        //     if ($chk_id_pool=='')
        //     {
        //             if($lst_usrid==''){
        //             $lst_usrid=1000;
        //             }else{
        //                 $lst_usrid=$lst_usrid->userid;  
        //             }

        //          $data = array('userid'=>$this->session->_user_id_,'sponsor'=>$lst_usrid,'upgrade_amt' =>500,'status'=>'Active');
    
        //       $this->db->insert('pool_one',$data);
          
        //    }
        
            $this->db->select('sponsor,A,B')->from('member')->where('id', $this->session->_user_id_);
            $sponsor_id1=$this->db->get()->row();
          
            $this->db->select('sponsor')->from('member')->where('id', $sponsor_id1->sponsor);
            $sponsor_id2=$this->db->get()->row();
            
            $this->db->select('id')->from('member')->where(array('A'=>$sponsor_id2->A, 'B'=>$sponsor_id2->B));
            $id1=$this->db->get()->row();
         
            /*****************************************************************
             *
             *  This part is important here.
             *  When a person makes payment, He'll be eligible to earn set of
             *  income as stated in plan or joining package. 
             *  Lets say direct income or level income.
             *
             *****************************************************************/
            if (config_item('enable_help_plan') == "Yes") {
                $this->load->model('help_plan');
                $this->help_plan->first_help($this->session->_user_id_, $this->session->_sponsor_, $this->session->_position_);
            }

            /*****************************************************************
             *
             *  Now we'll update product as sold and update sold qty and
             * vice versa.. :) u know what I mean..
             *
             *****************************************************************/
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
                    $maxid = 0;
                    $row = $this->db->query('SELECT MAX(orderid) AS `maxid` FROM `product_sale`')->row();
                    if ($row) {
                        $maxid = $row->maxid; 
                        $o_id = $maxid + 1 ;
                    }
                    $array = array(
                        'product_id' => $this->session->userdata('_product_'),
                        'userid'     => $this->session->userdata('_user_id_'),
                        'cost'       => $product_detail->prod_price,
                        'date'       => date('Y-m-d'),
                        'orderid'   =>$o_id,
                    );
                    
                    $this->db->insert('product_sale', $array);
                }
            endif;
            $this->load->model('earning');
            if (config_item('fix_income') == "Yes" && $this->session->userdata('_price_') > 0) {
                $status = $this->earning->fix_income($this->session->userdata('_user_id_'), $this->session->userdata('_sponsor_'), $this->session->userdata('_price_'));
            } else {
                
                $status = $this->earning->reg_earning($this->session->userdata('_user_id_'), $this->session->userdata('_sponsor_'), $this->session->userdata('_product_'), $need_topup = TRUE);
            
            }
            if ($status == TRUE) {
                ######## UNSET SOME PREVIOUS VALUES  #########

                $this->session->unset_userdata('_user_id_');
                $this->session->unset_userdata('_user_name_');
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

    public function failed_registration()
    {
        if ($this->session->_user_id_ > 0) {
            $layout['layout'] = "fail.php";
            $this->load->view('theme/default/index', $layout);
            /*****************************************************************
             *
             * Registration Complete but Payment Failed. Hence ID is deleted.
             *
             *****************************************************************/

            $id = $this->session->_user_id_;

            $check_legs = $this->db_model->count_all('member', array('position' => $id));
            if ($check_legs > 0 || trim($id) == config_item('top_id')) {
            } else {
                $position = $this->db_model->select_multi('position, placement_leg, my_img', 'member', array('id' => $id));
                $data     = array(
                    $position->placement_leg => 0,
                );
                $this->db->where('id', $position->position);
                $this->db->update('member', $data);

                $this->db->where('id', $id);
                $this->db->delete('member');

                $this->db->where('userid', $id);
                $this->db->delete('member_profile');
                $this->db->where('userid', $id); 
                $this->db->delete('wallet');

                unlink(FCPATH . "uploads/" . $position->my_img);
            }


        } else {
            redirect(site_url('site/login'));
        }

    }

    private function update_level($user_id, $i = 20, $e = 1)
    {
        if ($i > 0) {
            if(config_item('gen_list')=="Yes"){
                $lvl_position_id = $this->db_model->select('sponsor', 'member', array('id' => $user_id));
            }else{
                 $lvl_position_id = $this->db_model->select('position', 'member', array('id' => $user_id));
            }
 
        //     if($user_id != 1001 && $user_id != 1000){
        //     $chk_id_pool = $this->db_model->select('userid', 'pool_one', array('userid' =>$user_id));
        //         if($chk_id_pool == ''){
        //         $lst_usrid=$this->db->select('userid')->order_by('id',"desc")->limit(1)->get('pool_one')->row();
        //             if($lst_usrid==''){
        //                 $lst_usrid=$user_id; 
        //                 $data = array('userid'=>$user_id,'sponsor'=>$lst_usrid,'upgrade_amt' =>500,'count1'=>1,'status'=>'Active'); 
        //                 $this->db->insert('pool_one',$data);
        //             }else{
        //             $chk_count = $this->db_model->select('count1', 'pool_one', array('userid' =>$lst_usrid->userid));
        //              $lst_usrid = $this->db_model->select('userid', 'pool_one', array('id' =>$chk_count)); 
        //              $count=$this->db_model->count_all('pool_one', array('sponsor' =>$lst_usrid));
        //               if($count <= 5){
        //              $data = array('userid'=>$user_id,'sponsor'=>$lst_usrid,'upgrade_amt' =>500,'count1'=>$chk_count,'status'=>'Active'); 
        //                 $this->db->insert('pool_one',$data);
        //               }else{
        //                 $update_count=$chk_count+1;
        //                 $lst_usrid = $this->db_model->select('userid', 'pool_one', array('id' =>$update_count)); 
        //                  $data = array('userid'=>$user_id,'sponsor'=>$lst_usrid,'upgrade_amt' =>500,'count1'=>$update_count,'status'=>'Active'); 
        //                 $this->db->insert('pool_one',$data);
                        
        //               }
        //             }
        // } 
           // die();
            // $lst_usrid=$this->db->select('userid')->order_by('id',"desc")->limit(1)->get('pool_one')->row();
            
            // $chk_id_pool = $this->db_model->select('userid', 'pool_one', array('userid' =>$user_id));
            // if($chk_id_pool==''){
            //     $id=1;
            //     $chk_id = $this->db_model->select('userid', 'pool_one', array('id' =>$id));
            //     var_dump($chk_id);
            //     $count=$this->db_model->count_all('pool_one', array('sponsor' =>$chk_id));
            //     var_dump($count);die();
            //     $lst_usrid = $this->db_model->select('userid', 'pool_one', array('id' =>1));
			// 		if($lst_usrid==''){
			// 			$lst_usrid=$user_id;
			// 			}else{
            //                 if($count <= 5){
			// 				$lst_usrid=$chk_id;  
            //                 }
			// 			}
            //         }  
                 //  }
            // $data = array('userid'=>$user_id,'sponsor'=>$lst_usrid,'upgrade_amt' =>500,'status'=>'Active');
            
            // $this->db->insert('pool_one',$data);
            // $this->load->model('earning');
            // $this->earning->autopool_one($user_id);

            
        

            if (strlen($lvl_position_id) > 2) {
                $get_level = $this->db_model->select('level' . $e, 'level', array('userid' => $lvl_position_id));
                $this->db->set('level' . $e, $get_level + 1);
                $this->db->where('userid', $lvl_position_id);
                $this->db->update('level');
                $this->update_level($lvl_position_id, $i - 1, $e + 1);

            }

        }
    }

    

    public function login()
    {

        $this->form_validation->set_rules('username', 'Username', 'trim|required');
        $this->form_validation->set_rules('password', 'Password', 'trim|required');


        if ($this->form_validation->run() == FALSE) {
            $layout['layout'] = "login.php";     
           
            $this->load->view('theme/default/index', $layout);
        } else {
            $user = $this->common_model->filter($this->input->post('username'));
            $password = $this->input->post('password');
            $data = $this->db_model->select_multi("id, name, password, email, last_login_ip, last_login, status", 'member', array('id' => $user));

            if ($data->status !== "Active") {
                $this->session->set_flashdata('site_flash', '<div class="alert alert-danger">Login is invalid or Your account is not active. Account status is: ' . ($data->status ? $data->status : 'N/A') . '.</div>');
                redirect(site_url('site/login'));
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
                    'last_login' => time(),
                    'session' => $session,
                );
                $this->db_model->update($data2, 'member', array('id' => $data->id));
                $this->session->set_userdata('show_popup', 1);
                redirect(site_url('member'));
            } else {
                $this->session->set_flashdata('site_flash', '<div class="alert alert-danger">Invalid Username or Password.</div>');
                redirect(site_url('site/login'));
            }
        }
    }

    public function franchisee()
    {
        $this->form_validation->set_rules('username', 'Username', 'trim|required');
        $this->form_validation->set_rules('password', 'Password', 'trim|required');
        if ($this->form_validation->run() == FALSE) {
            $layout['layout'] = "franchisee_login.php";
            $this->load->view('theme/default/index', $layout);
        } else {
            $user     = $this->input->post('username');
            $password = $this->input->post('password');
            $data     = $this->db_model->select_multi("id, name, password, business_name, last_login, address, status", 'franchisee', array('username' => $user));
            if ($data->status !== "Active") {
                $this->session->set_flashdata('site_flash', '<div class="alert alert-danger">Login is invalid or account is blocked. Your account status is ' . $data->status . '.</div>');
                redirect(site_url('site/franchisee'));
            }
            if ($data->password == $password && trim($password) !== "") {
                // session_unset();
                $session = md5($user . time());
                $this->session->set_userdata(array(
                    'fran_id'       => $data->id,
                    'business_name' => $data->business_name,
                    'name'          => $data->name,
                    'last_login'    => $data->last_login,
                    'session'       => $session,
                ));
                $data2 = array(
                    'last_login_ip' => $this->input->ip_address(),
                    'last_login'    => date('Y-m-d'),
                    'session'       => $session,
                );
                $this->db_model->update($data2, 'franchisee', array('id' => $data->id));
                redirect(site_url('franchisee'));
            } else {
                $this->session->set_flashdata('site_flash', '<div class="alert alert-danger">Invalid Username or Password.</div>');
                redirect(site_url('site/franchisee'));
            }
        }
    }


   public function get_otp()
    {

        if (config_item('sms_on_join') == "Yes"){

      
        $to_number    = $this->input->post('username');      
        $code         = rand(1000, 9999);
        $_SESSION["code"] = $code;
        $country_code = "91";
        $smsApiKey    = "284460AflpGWLGwi623a0557P1";        
        $smsKeyword   = "AXOLTS";
        $template_id  = "1307164430008264745";
        $message = "Hello, Welcome to $to_number. Your OTP Verification code is $code . - AXOLOTLS TECHNOLOGIES";
        $data = $this->db_model->select_multi("id,name, phone, password", 'member', array('phone' => $to_number));
              
        $this->session->set_userdata('_user_id_', $data->phone,300);
        $this->session->set_userdata('otp', $code,300);  
        

        $curl = curl_init();

        curl_setopt_array($curl, array(
          CURLOPT_URL => "https://api.msg91.com/api/v2/sendsms",
          CURLOPT_RETURNTRANSFER => true,
          CURLOPT_ENCODING => "",
          CURLOPT_MAXREDIRS => 10,
          CURLOPT_TIMEOUT => 30,
          CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
          CURLOPT_CUSTOMREQUEST => "POST",
          CURLOPT_POSTFIELDS => "{ \"sender\": \"$smsKeyword\",  \"DLT_TE_ID\": \"$template_id\", \"route\": \"4\", \"country\": \"$country_code\", \"sms\": [ { \"message\": \"$message\", \"to\": [ \"$data->phone\" ] } 
        ] }",
          CURLOPT_SSL_VERIFYHOST => 0,
          CURLOPT_SSL_VERIFYPEER => 0,
          CURLOPT_HTTPHEADER => array(
            "authkey: $smsApiKey",
            "content-type: application/json"
          ),
        ));

        $response = curl_exec($curl);
        $err = curl_error($curl);
        curl_close($curl);
      
        if ($err) {
          echo "cURL Error #:" . $err;
        } else {
              echo $response;
        }     

        if($data->phone==null){
             $this->session->set_flashdata('site_flash', '<div class="alert alert-danger">Invalid Userid/Phone number </div>');
               redirect(site_url('site/reset_password'));
        }else{
            $this->session->set_flashdata('site_flash', '<div class="alert alert-success">OTP has been sent your mobile number1 </div>');
               redirect(site_url('site/reset_password'));
        }
       
        }
    }
 
     public function send_details(){
        $phone  = $this->input->post('phone');
        $this->db->where('phone', $phone);
        $this->db->or_where('id', $phone); 
        $query  = $this->db->get('member');

        if ($query->num_rows() > 0) {
            $data     = $query->row();
            $name     = $data->name;
            $phone    = $data->phone;
            $pwd      = $data->pass;
            $user_id  = $data->id;
            $web      = base_url('site/login');
            $cname    = config_item('company_name');
            $message  = "🏢 $cname\n\nDear $name,\n\nYour member login details are as follows :\n\nUserid : $user_id\nPassword : $pwd\nVisit  $web \n\n";
            $this->common_model->send_message($phone, $message);
            
            $this->session->set_flashdata('site_flash', '<div class="alert alert-success">Login details send on your registered mobile number.</div>');
            redirect(site_url('site/login'));
        } else {
            $this->session->set_flashdata('site_flash', '<div class="alert alert-danger">Phone number or userid not registered with us.</div>');
            redirect(site_url('site/forgotpw'));
        }
    }
 
    public function forgotpw()
    {
        $this->form_validation->set_rules('user', 'User Id', 'trim|required');
        $userid=$this->input->post('user');

        if ($this->form_validation->run() == FALSE) {
            $layout['layout'] = "reset_password.php";          
            $this->load->view('theme/default/index', $layout);
        } else {
            if (config_item('sms_on_join') == "Yes"):
                $data  = $this->db_model->select_multi("id,name, phone, password", 'member', array('id' => $userid));
                $this->session->set_userdata('_user_id_', $data->id,300);
                $phone = $data->phone;
                $otp   = rand(1000, 99999);
                $this->session->set_userdata('otp', $otp, 300);   
                $sms   = rawurlencode("Dear " . $data->name . " , OTP for password reset is : " . $otp . ". OTP will expire in 15 min. \nwww." . $_SERVER['HTTP_HOST']."");
                $this->common_model->send_message('7387070819', $sms);
            endif;
            $this->session->set_flashdata('site_flash', '<div class="alert alert-success">OTP has been sent your mobile number </div>');
            redirect(site_url('site/reset_password'));
        }

    }

// Reset password through OTP
    public function reset_password(){

        $this->form_validation->set_rules('otp', 'OTP', 'trim|required');
        $this->form_validation->set_rules('password', 'Password', 'trim|required');
        $this->form_validation->set_rules('cpassword', 'Confirm Password', 'trim|required|matches[password]');
        
       if ($this->form_validation->run() !== FALSE) {
            $password1 = password_hash($this->input->post('password'), PASSWORD_DEFAULT);
            $otp_entered=$this->input->post('otp');

            $otp_generated=$this->session->userdata('otp');
            $userid=$this->session->userdata('_user_id_');
       
            if($otp_generated == $otp_entered){

                $this->db->set('password',$password1);
                $this->db->where('id',$userid);
                $this->db->or_where('phone',$userid);
                $this->db->update('member');

                if (config_item('sms_on_join') == "Yes"):

                $data = $this->db_model->select_multi("id,name, phone, password", 'member', array('id' => $userid));
                $this->session->set_userdata('_user_id_', $data->id,300);
                $phone=$data->phone;
                $sms = rawurlencode("Dear " . $data->name . " ,Your password has been changed sucessfully. \nwww." . $_SERVER['HTTP_HOST']."");
                $this->common_model->sms($phone, $sms);
                endif;

                $this->session->unset_userdata('_user_id_');
                $this->session->unset_userdata('otp');
                $this->session->set_flashdata('site_flash', '<div class="alert alert-success">Passoword changed successfully.</div>');
                redirect(site_url('site/login'));
            }

           
        }else{
            $layout['layout'] = "reset_password.php";
            $this->load->view('theme/default/index', $layout);
        } 
    }

    public function staff()
    {
        $this->form_validation->set_rules('username', 'Staff Username', 'trim|required');
        $this->form_validation->set_rules('password', 'Password', 'trim|required');
        if ($this->form_validation->run() == FALSE) {
            $this->load->view('admin/staff');
        } else {
            $user     = $this->input->post('username');
            $password = $this->input->post('password');
            $data     = $this->db_model->select_multi("id, name, password, email, ip, last_login, designtion", 'staffs', array('username' => $user));

            if ($password == $data->password) {
                // session_unset();
                $session = md5($user . time());
                $this->session->set_userdata(array(
                    'admin_id'    => $data->id,
                    'email'       => $data->email,
                    'name'        => $data->name,
                    'ip'          => $data->ip,
                    'last_login'  => $data->last_login,
                    'session'     => $session,
                    'staff'       => TRUE,
                    'designation' => unserialize($this->db_model->select('des_permission', 'staff_designation', array('id' => $data->designtion))),
                ));

                $data2 = array(
                    'ip'         => $this->input->ip_address(),
                    'last_login' => date('Y-m-d'),
                    'session'    => $session,
                );
                $this->db_model->update($data2, 'staffs', array('id' => $data->id));
                redirect(site_url('admin'));
            } else {
                $this->session->set_flashdata('admin_flash', '<div class="alert alert-danger">Invalid Username or Password.</div>');
                redirect(site_url('site/staff'));
            }
        }
    }

    public function admin()
    {
        $this->form_validation->set_rules('username', 'Username', 'trim|required');
        $this->form_validation->set_rules('password', 'Password', 'trim|required');
        if ($this->form_validation->run() == FALSE) {
            $this->load->view('admin/login');
        } else {
            $user     = $this->input->post('username');
            $password = $this->input->post('password');
            $data     = $this->db_model->select_multi("*", 'admin', array('username' => $user));
            if (password_verify($password, $data->password)) {
                $session = md5($user . time());
                $this->session->set_userdata(array(
                    'admin_id'   => $data->id,
                    'email'      => $data->email,
                    'name'       => $data->name,
                    'ip'         => $data->ip,
                    'last_login' => $data->last_login,
                    'session'    => $session,
                ));
                $data2 = array(
                    'ip'         => $this->input->ip_address(),
                    'last_login' => time(),
                    'session'    => $session,
                );
                $this->db_model->update($data2, 'admin', array('id' => $data->id));
                $role_id   = $this->db_model->select('role_id', 'admin', ['id' => $data->id]);
                $tasks     = $this->db_model->select('tasks', 'tbl_roles', ['id' => $role_id]);
                $task_ids  = explode(',', $tasks);
                if (!in_array('1', $task_ids)) {
                    foreach ($task_ids as $task_id) {
                        $task = $this->db_model->select_multi('url', 'tbl_task_manager', ['id' => trim($task_id)]);
                        if (!empty($task) && !empty($task->url)) {
                            redirect(site_url($task->url));
                            exit;
                        }
                    }
                    redirect(site_url('admin'));
                } else {
                    redirect(site_url('admin'));
                }
            } else {
                $this->session->set_flashdata('admin_flash', '<div class="alert alert-danger">Invalid Username or Password.</div>');
                redirect(site_url('site/admin'));
            }
        }
    }

    public function admin_forget()
    {
        $this->load->view('admin/forget');
    }

    public function auto_login()
    {
        if (isset($this->session->_auto_user_id_)) {
            $data = $this->db_model->select_multi("id, name, password, email, last_login_ip, last_login", 'member', array('id' => $this->session->_auto_user_id_));
            $session = md5($this->session->_auto_user_id_ . time());
            $this->session->set_userdata(array(
                'user_id' => $data->id,
                'email' => $data->email,
                'name' => $data->name,
                'ip' => $data->last_login_ip,
                'last_login' => $data->last_login,
                'session' => $session,
            ));
            $data2 = array(
                'last_login_ip' => $this->input->ip_address(),
                'last_login' => time(),
                'session' => $session,
            );
            $this->db_model->update($data2, 'member', array('id' => $data->id));
            redirect(site_url('member'));


        } else {
            redirect(site_url('site/login'));
        }
    }

    public function _404()
    {
        $this->output->set_status_header('404');
        $this->load->view('404');
    }
    
    public function get_user_name($uid = 0)
    {
        $uid  = $this->common_model->filter($uid);
        $name = $this->db_model->select('name', 'member', array('id' => $uid));
        if ($name) {
            echo $name;
        } else {
            echo "User Not Found";
        }
    }

    public function get_user_name_for_epin($uid = 0)
    {
        $uid = $this->common_model->filter($uid);
        $user = $this->db_model->select_multi('name, activation_type, join_package, join_package_price', 'member', array('id' => $uid));

        if ($user) {
            echo json_encode(array(
                'status' => 'success',
                'name' => $user->name,
                'activation_type' => $user->activation_type,
                'join_package' => $user->join_package,
                'join_package_price' => $user->join_package_price,
            ));
        } else {
            echo json_encode(array('status' => 'error', 'message' => 'User Not Found'));
        }
    }
    
     public function rewards_achievers($type = 'All')
    {
        if($type == "D")
        {
            $type = 'Delivered';

        }elseif($type == "P")
        {
            $type = 'Pending';
        }
        
        if ($type == 'All') {
            $this->db->select('rewards.*, member.name');
        } else {
            $this->db->select('rewards.*, member.name');
            $this->db->where('rewards.status', $type);
        }

        $this->db->from('rewards');
        $this->db->join('member', 'member.id = rewards.userid', 'left');

        $this->db->order_by('rewards.id', 'DESC');
        $data['data'] = $this->db->get()->result_array();


        $data['title']      = 'Rewards Achievers';
        $data['breadcrumb'] = 'Rewards Achievers';
        $data['layout']     = 'staff/rewards_achievers.php';
        $this->load->view('admin/index', $data);
    }

    public function update_status()
    {
        $id     = $this->input->post('id');
        $status = $this->input->post('status');

        if (!$id) {
            $this->session->set_flashdata('error', 'Invalid Request!');
            redirect('site/rewards_achievers');
        }

        $update = $this->db->where('id', $id)
                        ->update('rewards', [
                            'status' => $status,
                            'paid_date'   => date('Y-m-d H:i:s'),
                        ]);

        if ($update) {
            $this->session->set_flashdata('success', 'Reward Delivered Successfully!');
        } else {
            $this->session->set_flashdata('error', 'Failed to Update Reward.');
        }

        redirect('site/rewards_achievers');
    }



}
