<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Member extends CI_Controller
{ 

    // public function __construct()
    // { 
    //     parent::__construct(); 
    //     if ($this->login->check_member() == false) {
    //         redirect(site_url('site/login'));
    //     }   
    //     $this->load->library('pagination'); 
    //     $this->load->library('cart');
    // } 
    
    public function __construct()
    { 
        parent::__construct(); 
        
        // 1. Check if user is logged in
        if ($this->login->check_member() == false) {
            redirect(site_url('site/login'));
        }   
        
        $this->load->library('pagination'); 
        $this->load->library('cart');
    
        // 2. QUIZ RESTRICTION LOGIC
        // Get the current method name the user is trying to access
        $current_method = $this->router->fetch_method();
    
        // List of methods the user IS allowed to access even if they haven't done the quiz
        $allowed_methods = array(
            'quiz_center', 
            'submit_quiz_payment', 
            'start_quiz', 
            'answer_sheet',
            'process_quiz', 
            'logout'
        );
    
        // If the user is trying to access a restricted page (like dashboard/index)
        if (!in_array($current_method, $allowed_methods)) {
            
            // Check database if this user has a 'Pass' result
            $quiz_passed = $this->db->get_where('quiz_results', array(
                'userid' => $this->session->user_id, 
                'status' => 'Pass'
            ))->row();
    
            // If no 'Pass' record found, force redirect to quiz center
            if (!$quiz_passed) {
                $this->session->set_flashdata('common_flash', '<div class="alert alert-warning">Please complete and pass the Nadi Vigyan Quiz to access your dashboard.</div>');
                redirect(site_url('member/quiz_center'));
            }
        }
    }
    
    public function id()
    {   
        $data['detail']     = $this->db_model->select_multi('*', 'member', array('id' => $this->session->user_id));
        $data['layout']     = "profile/id.php";
        //         echo "<pre>";
        // print_r($data);
        // echo "<pre>";
        // die;
        $this->load->view('member/index', $data); 
    }

	public function update_legs()
	{
		$this->load->model('earning');
		$this->earning->update_legs();
        $this->session->set_flashdata('common_flash', '<div class="alert alert-success">Pair Updated</div>');
        redirect(site_url('tree/my-tree'));
	}
	
	public function requested_epin()
    {
        $config['base_url']   = site_url('member/requested_epin');
        $config['per_page']   = 50;
        $config['total_rows'] = $this->db_model->count_all('epin_request', array(
            //'status'   => 'Un-used',
            'userid' => $this->session->user_id,
        ));
        $page                 = ($this->uri->segment(3)) ? $this->uri->segment(3) : 0;
        $this->pagination->initialize($config);

        $this->db->select('id, epins, amount, userid, payment_method,file,status,date')->from('epin_request')
                 ->where('userid', $this->session->user_id)->limit($config['per_page'], $page);

        $data['epin'] = $this->db->get()->result_array();

        $data['title']  = 'Requested E-Pins'; 
        $data['layout'] = 'epin/requested_epin.php';
        $this->load->view('member/index', $data);

    } 

    public function epin_dashboard()
    {
        $this->db->select('*')->from('epin')->where('issue_to', $this->session->user_id)->where('status', 'Un-used')->order_by('id', 'desc')->limit(100);
        $data['epin']   = $this->db->get()->result_array();
        $data['title']  = 'Epin Dashboard'; 
        $data['layout'] = 'epin/dash.php';
        $this->load->view('member/index', $data);
    }

    public function epin_request() {
        $npins   = $this->input->post('npins');
        $amount  = $this->input->post('amount');
        $this->load->library('upload');
        if (!$this->upload->do_upload('file') and $this->input->post('type')!='Cash'){
           $this->session->set_flashdata('common_flash', '<div class="alert alert-danger">Payment Proof not uploaded..<br/>' . $this->upload->display_errors() . '</div>');
           $file_proof=$result['file']; 
        } 
        else {
            $data = array('upload_data' => $this->upload->data());
            $file_proof=$data['upload_data']['file_name'];
        }
        
        if($this->input->post('type')=='Cash'){
            $file_proof = 'default.jpg';
        }
        
        $data = array(
            'amount'            => $amount,
            'epins'             => $npins,
            'userid'            => $this->session->user_id,
            'file'              => $file_proof,
            'payment_method'    => $this->input->post('type'),
        );
        $this->db->insert('epin_request', $data);
        $this->session->set_flashdata('common_flash', '<div class="alert alert-success">Request Send To The Admin Successfully .</div>');
        redirect(site_url('member/epin_dashboard'));
    }
 
    public function index()
    {
        $data['detail']       = $this->db_model->select_multi('*', 'member', array('id' => $this->session->user_id));
        $data['my_profile']   = $this->db_model->select_multi('*', 'member_profile', array('userid' => $this->session->user_id));
        $data['active_popup'] = $this->db->where('status', 1)->order_by('id', 'DESC')->get('popups')->row();
        $this->db->select('*')->from('earning')->group_by('type')->where('userid', $this->session->user_id)->limit($config['per_page'], $page);
        $data['earning_data'] = $this->db->get()->result_array();
        $this->db->select('*')->from('product')->where('status', 'Selling')->where('show_on_regform', 'No')->order_by('id','desc');
        $data['products_data'] = $this->db->get()->result_array();
        $this->db->select('*');
        $this->db->where('member.sponsor', $this->session->user_id);
        $this->db->from('member');
        // $this->db->join('wallet', 'wallet.userid = member.id');
        // $this->db->join('member_profile', 'member_profile.userid = member.id');
        $data['all_member'] = $this->db->get()->result_array();
        $this->db->select('*');
        $this->db->where('member.id', $this->session->user_id);
        $this->db->from('logs');
        $this->db->join('member', 'member.id = logs.userid');
        $data['notification'] = $this->db->get()->result_array();
        $this->db->select('*');
        $this->db->from('member');
        $data['allmember']= $this->db->get()->result_array();  
        $data['title']      = 'Dashboard';
        $data['breadcrumb'] = 'dashboard';
        #echo "<pre>";print_r($data['detail']);die();
        $this->load->view('member/index', $data);
    }

// This is method for search 
    public function search(){
        $search_keyword = $this->input->post('search_key');
        $this->db->select('*');
        $this->db->or_where('member.name like', $search_keyword);
        $this->db->or_where('member.email like', $search_keyword);
        $this->db->where('member.id', $search_keyword);
        $this->db->from('member');
        $this->db->join('member_profile', 'member_profile.userid = member.id');
        $data['all_member'] = $this->db->get()->result_array();
        redirect('member/index');
    }
    
public function certificate() {
    $userid = $this->session->user_id;

    // 1. Fetch User and Quiz Result Data 
    // Note: I changed 'quiz_results.created_at' to 'quiz_results.attempt_date'
    $this->db->select('member.name, member.id as member_id, quiz_results.status, quiz_results.attempt_date as pass_date');
    $this->db->from('quiz_results');
    $this->db->join('member', 'member.id = quiz_results.userid');
    $this->db->where('quiz_results.userid', $userid);
    $this->db->where('quiz_results.status', 'Pass');
    $this->db->order_by('quiz_results.id', 'DESC');
    
    $query = $this->db->get();

    // 2. Safety Check to prevent the "row() on boolean" error
    if ($query && $query->num_rows() > 0) {
        $data['user'] = $query->row();
        
        // Generate Certificate Details using the correct date column
        $year = date('Y', strtotime($data['user']->pass_date));
        $data['certificate_no'] = "GA/NTP/" . $year . "/" . $data['user']->member_id;
        $data['display_date'] = date('d-m-Y', strtotime($data['user']->pass_date));

        $this->load->view('member/quiz/certificate_template', $data);
    } else {
        // Redirect if no 'Pass' record is found for this specific user
        $this->session->set_flashdata('common_flash', '<div class="alert alert-danger">Certificate not found. Please ensure you have passed the exam.</div>');
        redirect('member/quiz_center');
    }
}
   
   public function answer_sheet() {
        // Check if approved
        $check = $this->db->get_where('quiz_payments', array('userid' => $this->session->user_id, 'status' => 'Approved'))->row();
        if(!$check) { redirect('member/quiz_center'); }
    
        // Full Questions with Correct Answer Key
        $data['questions'] = [
        1 => ['q' => 'नाडी परीक्षणात मुख्यतः किती दोष पाहिले जातात?', 'ans' => '3'],
        2 => ['q' => 'वात दोषाचे प्रमुख गुणधर्म कोणते?', 'ans' => 'चल'],
        3 => ['q' => 'पित्त दोषाचा संबंध कोणाशी असतो?', 'ans' => 'अग्नी'],
        4 => ['q' => 'कफ दोषाचा प्रमुख गुण कोणता?', 'ans' => 'स्थिर'],
        5 => ['q' => 'नाडी परीक्षण कोणत्या हातावर केले जाते?', 'ans' => 'दोन्ही'],
        6 => ['q' => 'पुरुषांची नाडी सामान्यतः कोणत्या हातावर तपासतात?', 'ans' => 'उजवा'],
        7 => ['q' => 'स्त्रियांची नाडी सामान्यतः कोणत्या हातावर तपासतात?', 'ans' => 'डावा'],
        8 => ['q' => 'वात नाडीची गती कशासारखी वर्णन केली जाते?', 'ans' => 'सर्प'],
        9 => ['q' => 'पित्त नाडी कशासारखी असते?', 'ans' => 'बेडूक'],
        10 => ['q' => 'कफ नाडी कशासारखी असते?', 'ans' => 'हंस'],
        11 => ['q' => 'वाताचे किती उपप्रकार आहेत?', 'ans' => '5'],
        12 => ['q' => 'प्राण वायू हा कोणाचा उपप्रकार आहे?', 'ans' => 'वात'],
        13 => ['q' => 'समान वायूचे मुख्य कार्य काय?', 'ans' => 'पचन'],
        14 => ['q' => 'पित्ताचे किती उपप्रकार आहेत?', 'ans' => '5'],
        15 => ['q' => 'पाचक पित्त कुठे कार्य करते?', 'ans' => 'आमाशय'],
        16 => ['q' => 'कफाचे किती उपप्रकार आहेत?', 'ans' => '5'],
        17 => ['q' => 'तर्पक कफ कुठे असतो?', 'ans' => 'मेंदू'],
        18 => ['q' => 'नाडी परीक्षणाचा सर्वोत्तम वेळ कोणता?', 'ans' => 'सकाळ'],
        19 => ['q' => 'नाडी परीक्षणात किती बोटांचा वापर होतो?', 'ans' => '3'],
        20 => ['q' => 'तर्जनी बोट कोणता दोष दर्शवते?', 'ans' => 'वात'],
        21 => ['q' => 'मधले बोट कोणता दोष दर्शवते?', 'ans' => 'पित्त'],
        22 => ['q' => 'अनामिका बोट कोणता दोष दर्शवते?', 'ans' => 'कफ'],
        23 => ['q' => 'आयुर्वेदानुसार शरीराचे मूलभूत दोष किती आहेत?', 'ans' => '3'],
        24 => ['q' => 'नाडी परीक्षण करताना रुग्णाची अवस्था कशी असावी?', 'ans' => 'शांत व आरामदायी'],
        25 => ['q' => 'नाडी परीक्षणासाठी दिवसातील सर्वोत्तम वेळ कोणती?', 'ans' => 'रिकाम्या पोटी सकाळी'],
        26 => ['q' => 'वात दोषाचे प्रमुख तत्त्व कोणते?', 'ans' => 'वायू + आकाश'],
        27 => ['q' => 'पित्त दोषाचे प्रमुख तत्त्व कोणते?', 'ans' => 'अग्नी + जल'],
        28 => ['q' => 'कफ दोषाचे प्रमुख तत्त्व कोणते?', 'ans' => 'पृथ्वी + जल'],
        29 => ['q' => 'वात वाढल्यास सामान्यतः कोणते लक्षण दिसते?', 'ans' => 'बद्धकोष्ठता'],
        30 => ['q' => 'पित्त वाढल्यास कोणते लक्षण दिसते?', 'ans' => 'भूक वाढणे'],
        31 => ['q' => 'कफ वाढल्यास कोणते लक्षण दिसते?', 'ans' => 'आळस व जडपणा'],
        32 => ['q' => 'नाडी परीक्षणात कोणत्या गोष्टीचा अंदाज घेतला जातो?', 'ans' => 'दोषांचे संतुलन'],
        33 => ['q' => 'प्राण वायूचे मुख्य कार्य काय आहे?', 'ans' => 'श्वसन'],
        34 => ['q' => 'अपान वायूचे मुख्य कार्य काय आहे?', 'ans' => 'उत्सर्जन'],
        35 => ['q' => 'व्यान वायूचे मुख्य कार्य काय आहे?', 'ans' => 'रक्ताभिसरण'],
        36 => ['q' => 'उदान वायूचे मुख्य कार्य काय आहे?', 'ans' => 'बोलणे'],
        37 => ['q' => 'रंजक पित्ताचे कार्य काय आहे?', 'ans' => 'रक्ताला रंग देणे'],
        38 => ['q' => 'साधक पित्त मुख्यतः कुठे कार्य करते?', 'ans' => 'हृदय'],
        39 => ['q' => 'आलोचक पित्ताचा संबंध कोणाशी आहे?', 'ans' => 'दृष्टी'],
        40 => ['q' => 'भ्राजक पित्त कुठे कार्य करते?', 'ans' => 'त्वचा'],
        41 => ['q' => 'अवलंबक कफ मुख्यतः कुठे असतो?', 'ans' => 'छाती'],
        42 => ['q' => 'बोधक कफाचा संबंध कशाशी आहे?', 'ans' => 'चव'],
        43 => ['q' => 'क्लेदक कफ कुठे कार्य करतो?', 'ans' => 'आमाशय'],
        44 => ['q' => 'श्लेषक कफ कुठे आढळतो?', 'ans' => 'सांधे'],
        45 => ['q' => 'आयुर्वेदाचे प्रमुख उद्दिष्ट काय आहे?', 'ans' => 'आरोग्याचे रक्षण आणि रोगनिवारण'],
        46 => ['q' => 'नाडी परीक्षणात काय अनुभवले जाते?', 'ans' => 'स्पंदन'],
        47 => ['q' => 'निरोगी व्यक्तीमध्ये दोषांची अवस्था कशी असते?', 'ans' => 'संतुलित'],
        48 => ['q' => 'नाडी परीक्षण ही कोणती पद्धत आहे?', 'ans' => 'निरीक्षण व स्पर्श आधारित'],
        49 => ['q' => 'आयुर्वेदाचे जनक म्हणून कोणाला ओळखले जाते?', 'ans' => 'चरक'],
        50 => ['q' => 'नाडी परीक्षणाचा मुख्य उद्देश काय आहे?', 'ans' => 'दोषांचे संतुलन समजणे'],
    ];
    
        $data['title'] = 'Official Answer Key';
        $this->load->view('member/quiz/answer_sheet', $data);
    }

    public function business_plan()
    {    
        $data['title']     = 'Business Plan';
        $data['layout']    = "profile/business_plan.php";
        $this->load->view('member/index', $data); 
    }
    
    public function legal_documents()
    {    
        $data['title']     = 'Legal Documents';
        $data['layout']    = "profile/legal_documents.php";
        $this->load->view('member/index', $data); 
    }
    
 
    public function logout()
    {
        $this->session->unset_userdata('user_id');
        $this->session->unset_userdata('email');
        $this->session->unset_userdata('name');
        $this->session->unset_userdata('ip');
        $this->session->unset_userdata('last_login');
        $this->session->unset_userdata('session');
        // $this->session->sess_destroy();
        $this->session->set_flashdata('site_flash', '<div class="alert alert-info">You have been logged out !</div>');
         $layout['layout'] = "logout.php";
            
        $this->load->view('theme/default/index', $layout);

       
    }

    // CORE MEMBER PARTS HERE NOW ############################################################ STARTS :

    public function used_epin()
    {
        $config['base_url']   = site_url('member/used_epin');
        $config['per_page']   = 50;
        $config['total_rows'] = $this->db_model->count_all('epin', array(
            'status'   => 'Un-used',
            'issue_to' => $this->session->user_id,
        ));
        $page                 = ($this->uri->segment(3)) ? $this->uri->segment(3) : 0;
        $this->pagination->initialize($config);

        $this->db->select('id, epin, amount, used_by, used_time,type')->from('epin')->where('status', 'Used')
                 ->where('issue_to', $this->session->user_id)->limit($config['per_page'], $page);

        $data['epin'] = $this->db->get()->result_array();

        $data['title']  = 'In-Active E-Pins'; 
        $data['layout'] = 'epin/used.php';
        $this->load->view('member/index', $data);

    } 

    public function epin_transfer_history()
    {
        $config['base_url']   = site_url('member/epin_transfer_history');
        $config['per_page']   = 50;
       
        $config['total_rows'] = $this->db_model->count_all('epin', array(
            'transfer_by'   =>$this->session->user_id,
        ));
        $page                 = ($this->uri->segment(3)) ? $this->uri->segment(3) : 0;
        $this->pagination->initialize($config);

        $this->db->select('id, epin, amount,transfer_time, used_by,issue_to,status, used_time,type')->from('epin')->where('transfer_by', $this->session->user_id)
                 ->limit($config['per_page'], $page);

        $data['epin'] = $this->db->get()->result_array();

        $data['title']  = 'Epin Transfer History';
        $data['layout'] = 'epin/epin_transfer_history.php';
        $this->load->view('member/index', $data);

    }

    public function get_user_name()
    {
        echo $this->db_model->select('name', 'member', array('id' => $this->uri->segment(3)));
    }
    public function unused_epin()
    {
        $config['base_url']   = site_url('member/unused_epin');
        $config['per_page']   = 50;
        $config['total_rows'] = $this->db_model->count_all('epin', array(
            'status'   => 'Un-used',
            'issue_to' => $this->session->user_id,
        ));
        $page                 = ($this->uri->segment(3)) ? $this->uri->segment(3) : 0;
        $this->pagination->initialize($config);

        $this->db->select('id, epin, amount, issue_to, generate_time, generate_time,type')->from('epin')->where('status', 'Un-used')->where('amount >', 0)->where('issue_to', $this->session->user_id);

        $data['epin'] = $this->db->get()->result_array();

        $data['title']  = 'Active E-Pin';
        $data['layout'] = 'epin/unused.php';
        $this->load->view('member/index', $data);

    }
        public function matrix(){
            $config['base_url']   = site_url('member/matrix');
            $config['per_page']   = 50;
            $config['total_rows'] = $this->db_model->count_all('pool_one', array(
                 'sponsor' => $this->session->user_id,
            ));
            $page                 = ($this->uri->segment(3)) ? $this->uri->segment(3) : 0;
            $this->pagination->initialize($config);
    
            $this->db->select('*')->from('pool_one')
                     ->where('sponsor',$this->session->user_id)
                     ->limit($config['per_page'], $page);
    
            $data['data'] = $this->db->get()->result_array();
    
            $data['title']  = 'Matrix direct';
            $data['layout'] = 'income/matrix.php';
            $this->load->view('member/index', $data);   
        }


    public function transfer_epin()
    {

        $this->form_validation->set_rules('amount', 'e-PIN Amount', 'trim|required');
        $this->form_validation->set_rules('to', 'To User ID', 'trim|required');
        $this->form_validation->set_rules('qty', 'Number of e-PINs', 'trim|required');

        if ($this->form_validation->run() == FALSE) {
            $data['title']  = 'Transfer e-PIN';
             $data['products'] = $this->db->get('product')->result_array();
            $data['layout'] = 'epin/transfer_epin.php';
            $this->load->view('member/index', $data);

        } else {
            $amount = $this->common_model->filter($this->input->post('amount'), 'float');
            $to     = $this->common_model->filter($this->input->post('to'), 'number');
            $from   = $this->session->user_id;
            $qty    = $this->common_model->filter($this->input->post('qty'), 'number');

            $trans_pass = $this->db_model->select('trans_password', 'member', array('id' => $this->session->user_id));
            if($this->input->post('trans_password') != $trans_pass){
                $this->session->set_flashdata('common_flash', '<div class="alert alert-danger">Incorrect Transaction Password</div>');
                redirect('member/transfer-epin');
            }

            $avl_qty = $this->db_model->count_all('epin', array(
                'issue_to' => $from,
                'amount'   => $amount,
                'status'   => 'Un-used',
            ));
            if ($avl_qty < $qty) {

                $this->session->set_flashdata('common_flash', '<div class="alert alert-danger">The User ID have only ' . $avl_qty . ' Un-used epin of ' . config_item('currency') . ' ' . $amount . '.</div>');
                $data['title']  = 'Transfer e-PIN';
                $data['layout'] = 'epin/transfer_epin.php';
                $this->load->view('member/index', $data);

            } else {
                $this->db->where(array(
                    'issue_to' => $from,
                    'amount'   => $amount,
                    'status'   => 'Un-used',
                ));
                $vals = array(
                    'issue_to'      => $to,
                    'transfer_by'   => $from,
                    'transfer_time' => date('Y-m-d'),
                );
                $this->db->limit($qty);
                $this->db->update('epin', $vals);

                $this->session->set_flashdata('common_flash', '<div class="alert alert-success">' . $qty . ' e-PIN transferred to  ' . $this->input->post('to') . ' of ' . $this->input->post('from') . ' of ' . config_item('currency') . ' ' . $amount . '.</div>');
                redirect('member/transfer_epin');
            }
        }
    }

    public function generate_epin()
    {
        $this->form_validation->set_rules('amount', 'e-PIN Amount', 'trim|required');
        $this->form_validation->set_rules('userid', 'Issue to ID', 'trim|required');
        $this->form_validation->set_rules('number', 'Number of e-PINs', 'trim|required|max_length[3]');
        if ($this->form_validation->run() == FALSE) {
            $data['title']  = 'Generate e-PIN';
            $data['layout'] = 'epin/generate.php'; 
            $this->load->view('member/index', $data);
        } else {
            $amount           = $this->common_model->filter($this->input->post('amount'), 'float');
            $userid           = $this->common_model->filter($this->input->post('userid'),'number');
            $qty              = $this->common_model->filter($this->input->post('number'), 'number');
            $paymentmethod    = $this->input->post('paymentmethod');
            $ucnt             = $this->db_model->count_all('member', array('id' => $userid));
            
            // $trans_pass = $this->db_model->select('trans_password', 'member', array('id' => $this->session->user_id));
            // if($this->input->post('trans_password') != $trans_pass){
            //     $this->session->set_flashdata('common_flash', '<div class="alert alert-danger">Incorrect Transaction Password</div>');
            //     redirect('member/generate-epin');
            // }
            
            if($ucnt == 0){
                $this->session->set_flashdata('common_flash', '<div class="alert alert-danger">Userid is wrong..</div>');
                redirect('member/generate-epin');  
            }
            if($userid == ""){
                $this->session->set_flashdata('common_flash', '<div class="alert alert-danger">please enter userid..</div>');
                redirect('member/generate-epin');  
            }
    
            // $per_amt    = $amount  * (10 /100);
            $cal_amt          = $amount  + 0;
            $total_amt        = $cal_amt * $qty;
            $get_user_balance = $this->db_model->select('balance', $paymentmethod, array('userid' => $this->session->user_id));

            if ($get_user_balance < $total_amt) {
                $this->session->set_flashdata("common_flash", "<div class='alert alert-danger'>You wallet donot have sufficient balance to generate $qty e-PIN. Your wallet need to have " . config_item('currency') . $total_amt . "</div>");
                redirect('member/generate-epin');
            }
            $data = array();
            for ($i = 0; $i < $qty; $i++) {
                $rand = mt_rand(10000000, 99999999);
                $epin = $this->db_model->select("id", "epin", array("epin" => $rand));
                if ($rand == $epin) {
                    $rand = $rand + 1;
                }
                $array = array(
                    'epin'          => $rand,
                    'amount'        => $amount,
                    'issue_to'      => $userid,
                    'generated_by'  => $this->session->user_id,
                    'generate_time' => date('Y-m-d'),
                );
                array_push($data, $array);
            }
            $this->db->insert_batch('epin', $data);
            
            $data10 = array(
                'userid'   => $this->session->user_id,
                'amount'   => $total_amt,
                'type'     => "Debit",
                'other'    => "Epin generated by ".$paymentmethod,
            );
            $this->db->insert('wallet_transaction', $data10);
            
            $arra = array(
                'balance' => ($get_user_balance - $total_amt),
            );
            $this->db->where('userid', $this->session->user_id);

            if (config_item('wallet_type')!=="Yes"){
                $this->db->update($paymentmethod, $arra);
            }else{
                $this->db->update($paymentmethod, $arra);
            }
            $this->session->set_flashdata("common_flash", "<div class='alert alert-success'>( $qty ) e-PIN created successfully. View E-Pins in Un-used Section</div>");
            $this->common_model->mail($this->db_model->select('email', 'member', array('id' => $userid)), 'e-PIN Issued', 'Dear Sir, <br/> e-PIN of Qty ' . $qty . ', has been issued to your account from user id: ' . config_item('ID_EXT') . $this->session->user_id . ' on behalf of us.<br/><br/>---<br/>Regards,<br/>' . config_item('company_name'));
            redirect('member/generate_epin');

        } 
    } 

    public function earning_dashboard($value = null)
    {
        $data['detail']           = $this->db_model->select_multi('*', 'member', array('id' => $this->session->user_id));
        $data['referral_income']  = $this->db->select('*')->group_by('date')->select_sum('amount')->from('earning')->order_by('date','DESC')->where('status','Paid')->where('userid',$this->session->user_id)->where('type','Referral Reward')->limit($config['per_page'], $page);
        $data['earning']          = $this->db->get()->result_array();
        $data['total_income']     = $this->db_model->sum('amount', 'earning', array('userid' => $this->session->user_id));
        $data['referral_income']  = $this->db_model->sum('amount', 'earning', array('type' => 'Referral Reward','userid' => $this->session->user_id));
        $data['car_fund_income']  = $this->db_model->sum('amount', 'earning', array('type' => 'Car Fund Inc','userid' => $this->session->user_id));
        $data['matching']         = $this->db_model->sum('amount', 'earning', array('type' => 'Matching Income','userid' => $this->session->user_id));
        $data['roi_income']       = $this->db_model->sum('amount', 'earning', array('type' => 'ROI','userid' => $this->session->user_id));
        $data['level_income']     = $this->db_model->sum('amount', 'earning', array('type' => 'Level Income','userid' => $this->session->user_id));
        $data['rank_income']      = $this->db_model->sum('amount', 'earning', array('levlno' => 'Reward','userid' => $this->session->user_id));
        $data['sp_level_income']  = $this->db_model->sum('amount', 'earning', array('type' => 'Sponsor Level Inc','userid' => $this->session->user_id));
        $data['sp_level_income2'] = $this->db_model->sum('amount', 'earning', array('type' => 'Matching Sponsor Inc','userid' => $this->session->user_id));
        $data['sp_level_income3'] = $this->db_model->sum('amount', 'earning', array('type' => 'Sponsor Level Inc','levlno >' => 1,'userid' => $this->session->user_id));
        $data['today_comission']  = $this->db_model->sum('amount', 'earning', array('date' => date('Y-m-d'),'userid' => $this->session->user_id));
        $data['p_Paid']           = $this->db_model->sum('amount', 'withdraw_request', array('status' => 'Paid','userid' => $this->session->user_id));
        $data['u_Paid']           = $this->db_model->sum('amount', 'withdraw_request', array('status' => 'Un-Paid','userid' => $this->session->user_id));
        $data['h_Paid']           = $this->db_model->sum('amount', 'withdraw_request', array('status' => 'Hold','userid' => $this->session->user_id));
        
        #

            $data['total_repurchase_inc']         = $this->db_model->sum('amount', 'earning', array('type' => 'Repurchase Income','userid' => $this->session->user_id));
            $data['total_royalty_inc']         = $this->db_model->sum('amount', 'earning', array('type LIKE' => '%royalty%','userid' => $this->session->user_id)); 
            #print_r($this->db->last_query());die();
            $start = date('Y-m-01 00:00:00');
            $end   = date('Y-m-t 23:59:59');
            
            $where = array('type' => 'Repurchase Income','userid' => $this->session->user_id,'date >='  => $start,'date <='  => $end);
            $data['monthly_repurchase_inc'] = $this->db_model->sum('amount','earning',$where);

            $where2 = array('type LIKE' => '%royalty%','userid' => $this->session->user_id,'date >='  => $start,'date <='  => $end);
            $data['monthly_royalty_inc'] = $this->db_model->sum('amount','earning',$where2);
            #print_r($this->db->last_query());die();

        #
        
        $data['title']            = 'Earnings Dashboard';
        $data['breadcrumb']       = 'Earning Dashboard';
        $data['layout']           = 'income/earning_dashboard.php';
        $this->load->view('member/index', $data); 
    }
   
    public function view_earning()
    {
        $config['base_url'] = site_url('member/view_earning');
        $type   = $this->input->post('type') ?? null;
        $s_date = $this->input->post('start_date') ?? null;
        $e_date = $this->input->post('end_date') ?? null;
        $this->db->select('DATE(date) as date, type, pair_match, ref_id, secret, userid ,status, SUM(amount) as amount')->from('earning')->where('userid', $this->session->user_id)->where('amount >', 0)->group_by(['DATE(date)', 'type','status', 'ref_id']);
        if ($type) {
            $this->db->where('type', $type);
        }
        if ($s_date) {
            $this->db->where('date >=', $s_date);
        }
        if ($e_date) {
            $this->db->where('date <=', $e_date);
        }
                
        $data['earning'] = $this->db->get()->result_array();
        $data['title']   = 'Earnings List';
        $data['type']    = $type;
        $data['sdate']   = $s_date;
        $data['edate']   = $e_date;
        $data['layout']  = 'income/view_earning.php';
        $this->load->view('member/index', $data); 
    }
 
    ###### Matching earning list ####
   
    public function binary_roi_earning()
    { 
        $config['base_url']   = site_url('member/binary_roi_earning');
        $config['per_page']   = 100;
        $config['total_rows'] = $this->db_model->count_all('earning_roi', array('userid' => $this->session->user_id));
        $page                 = ($this->uri->segment(3)) ? $this->uri->segment(3) : 0;
        $this->pagination->initialize($config);

        $this->db->select('*')->from('earning_roi')->where('income_type','Matching ROI')
                 ->where('userid', $this->session->user_id)->limit($config['per_page'], $page);

        $data['earning'] = $this->db->get()->result_array();

        $data['title']  = 'Matching Earning List';
        $data['layout'] = 'income/binary_roi_earning.php';
        $this->load->view('member/index', $data);

    }

   public function daily_direct_roi_earning()
    { 
        $config['base_url']   = site_url('member/daily_direct_roi_earning');
        $config['per_page']   = 10;
        $config['total_rows'] = $this->db_model->count_all('earning_roi', array('userid' => $this->session->user_id));
        $page                 = ($this->uri->segment(3)) ? $this->uri->segment(3) : 0;
        $this->pagination->initialize($config);

        $this->db->select('*')->from('earning_roi')->where('income_type','Referral Reward')->where('userid', $this->session->user_id)->order_by('created_at','ASC')->limit($config['per_page'], $page);
        $data['earning'] = $this->db->get()->result_array();

        $data['title']  = 'Daily Direct Earning List';
        $data['layout'] = 'income/daily_direct_roi_earning.php';
        $this->load->view('member/index', $data);

    }
 
     public function daily_roi_earning()
    { 
       $config['base_url']   = site_url('member/daily_roi_report');
        $config['per_page']   = 100;
        $config['total_rows'] = $this->db_model->count_all('earning', array('userid' => $this->session->user_id));
        $page                 = ($this->uri->segment(3)) ? $this->uri->segment(3) : 0;
        $this->pagination->initialize($config);

        $this->db->select('*')->from('earning')->where('type','ROI')
                 ->where('userid', $this->session->user_id)->limit($config['per_page'], $page);

        $data['earning'] = $this->db->get()->result_array();

        $data['title']  = 'Daily ROI Earning List';
        $data['layout'] = 'income/daily_roi_report.php';
        $this->load->view('member/index', $data);

    }

    public function daily_level_roi_earning()
    { 
        $config['base_url']   = site_url('member/daily_level_roi_earning');
        $config['per_page']   = 100;
        $config['total_rows'] = $this->db_model->count_all('earning_roi', array('userid' => $this->session->user_id));
        $page                 = ($this->uri->segment(3)) ? $this->uri->segment(3) : 0;
        $this->pagination->initialize($config);

        $this->db->select('*')->from('earning_roi')->where('income_type','Sponsor Level Inc')
                 ->where('userid', $this->session->user_id)->order_by('created_at','DESC')->limit($config['per_page'], $page);
        $data['earning'] = $this->db->get()->result_array();

        $data['title']  = 'Daily Level Earning List';
        $data['layout'] = 'income/daily_level_roi_earning.php';
        $this->load->view('member/index', $data);

    }


     public function roi_earning()
    { 
        $config['base_url']   = site_url('member/roi_earning');
        $config['per_page']   = 100;
        $config['total_rows'] = $this->db_model->count_all('earning_roi', array('userid' => $this->session->user_id));
        $page                 = ($this->uri->segment(3)) ? $this->uri->segment(3) : 0;
        $this->pagination->initialize($config);

        $this->db->select('*')->from('earning_roi')->where('income_type','Referral Reward')
                 ->where('userid', $this->session->user_id)->limit($config['per_page'], $page);

        $data['earning'] = $this->db->get()->result_array();

        $data['title']  = 'ROI Earning';
        $data['layout'] = 'income/roi_earning.php';
        $this->load->view('member/index', $data);

    }


      public function matching_earning()
    { 
        $config['base_url']   = site_url('member/matching_earning');
        $config['per_page']   = 100;
        $config['total_rows'] = $this->db_model->count_all('earning', array('userid' => $this->session->user_id));
        $page                 = ($this->uri->segment(3)) ? $this->uri->segment(3) : 0;
        $this->pagination->initialize($config);

        $this->db->select('*')->from('earning')->where('type','Matching Income')
                 ->where('userid', $this->session->user_id)->limit($config['per_page'], $page);

        $data['earning'] = $this->db->get()->result_array();

        $data['title']  = 'Matching Earning List';
        $data['layout'] = 'income/matching_earning.php';
        $this->load->view('member/index', $data);

    }

    public function sponsor_earning()
    { 
        $config['base_url']   = site_url('member/sponsor_income');
        $config['per_page']   = 100;
        $config['total_rows'] = $this->db_model->count_all('earning_roi', array('userid' => $this->session->user_id));
        $page                 = ($this->uri->segment(3)) ? $this->uri->segment(3) : 0;
        $this->pagination->initialize($config);

        $this->db->select('*')->from('earning')->where('type','Referral Reward')
                 ->where('userid', $this->session->user_id)->limit($config['per_page'], $page);

        $data['earning'] = $this->db->get()->result_array();

        $data['title']  = 'Referral Earning List';
        $data['layout'] = 'income/sponsor_income.php';
        $this->load->view('member/index', $data);

    }


    public function topup_wallet() 
    {
        if (!isset($_POST['amount']) && !isset($_POST['epin'])) {
            $data['title']  = 'Fund My Wallet';
            $data['layout'] = 'wallet/topup-wallet.php';
            $this->load->view('member/index', $data);
        } 
        else {
            $epin   = trim($this->input->post('epin'));
            $amount = trim($this->input->post('amount'));
            if ($epin !== "") {
                $epin_value = $this->db_model->select('amount', 'epin', array('epin'   => $epin,'status' => 'Un-used'));
                if ($epin_value <= 0) {
                    $this->session->set_flashdata('common_flash', '<div class="alert alert-danger">The entered e-PIN is invalid or doesn\'t exist.</div>');
                    redirect(site_url('member/topup-wallet'));
                } 
                else {
                    if (config_item('wallet_type')!="Yes"){
                        $wallet_balance = $this->db_model->select('balance', 'wallet', array('userid' => $this->session->user_id));
                    }
                    else{
                        $wallet_balance = $this->db_model->select('balance', 'product_wallet', array('userid' => $this->session->user_id));
                    }

                    if (config_item('wallet_type')!="Yes"){
                        $result=$this->db_model->select_multi('userid', 'wallet', array('userid' =>$this->session->user_id));
                      
                         if ($result==""){
                             $array1=array(
                                'userid'=>$this->session->user_id,
                                'balance'=>$epin_value,
                                'type'=>'Topup',
                            );
                            $this->db->insert('wallet',$array1);
                            }
                            else{
                                $this->db->where(array('userid' => $this->session->user_id));
                                $this->db->update('wallet', array('balance' => $wallet_balance + $epin_value));       
                            }
                    }
                    
                    else{
                       $result=$this->db_model->select_multi('userid', 'product_wallet', array('userid' =>$this->session->user_id));
                         if ($result==""){
                            $array1=array( 
                                'userid'=>$this->session->user_id,
                                'balance'=>$epin_value,
                                'type'=>'Topup',
                            );
                            $this->db->insert('product_wallet',$array1);
                         }
                         else{
                             $this->db->where(array('userid' => $this->session->user_id));
                            $this->db->update('product_wallet', array('balance' => $wallet_balance + $epin_value));
                        }
                    }
                    
                    $data = array(
                        'status'    => 'Used',
                        'used_by'   => $this->session->user_id,
                        'used_time' => date('Y-m-d'),
                    );
                    $this->db->where('epin', $epin);
                    $this->db->update('epin', $data);

                    $this->session->set_flashdata('common_flash', '<div class="alert alert-success">Fund is added to your wallet.</div>');
                    redirect(site_url('member/topup-wallet'));
                }
            } 
            else {
                $user_data = $this->db_model->select_multi('sponsor, address, email, phone', 'member', array('id' => $this->session->user_id));
                $this->session->set_userdata('_user_id_', $this->session->user_id);
                $this->session->set_userdata('_user_name_', $this->session->name);
                $this->session->set_userdata('_inv_id_', rand());
                $this->session->set_userdata('_sponsor_', $user_data->sponsor);
                $this->session->set_userdata('_address_', $user_data->address);
                $this->session->set_userdata('_email_', $user_data->email);
                $this->session->set_userdata('_phone_', $user_data->phone);
                $this->session->set_userdata('_product_', 'Add Wallet Fund');
                $this->session->set_userdata('_price_', $amount);
                $this->session->set_userdata('_type_', 'wallet');
                $this->session->set_userdata('_coin_', $this->input->post('coin_wallet'));
                redirect('gateway/payu');
            } 
        }
    }

   
    public function failed_fund()
    {
        //var_dump("expression2");die();
        $this->session->set_flashdata("common_flash", "<div class='alert alert-danger'>Your payment is not completed. So your fund was not added.</div>");
        redirect(site_url('member/topup-wallet'));
    }

    public function complete_add_fund()
    {
        $wallet_balance = $this->db_model->select('balance', 'wallet', array('userid' => $this->session->user_id));
        $this->db->where(array('userid' => $this->session->user_id));
        $this->db->update('wallet', array('balance' => $wallet_balance + $this->session->_price_));
        $this->session->set_flashdata('common_flash', '<div class="alert alert-success">Fund is added to your wallet.</div>');
        redirect(site_url('member/topup-wallet'));
    }


    public function my_rewards() 
    {
        $config['base_url']   = site_url('member/my_rewards');
        $config['per_page']   = 100;
        $config['total_rows'] = $this->db_model->count_all('rewards', array('userid' => $this->session->user_id));
        $page                 = ($this->uri->segment(3)) ? $this->uri->segment(3) : 0;
        $this->pagination->initialize($config);

        $this->db->select('*')->from('rewards')
                 ->where('userid', $this->session->user_id)->limit($config['per_page'], $page);

        $data['rewards'] = $this->db->get()->result_array();

        $data['title']  = 'Earned Rewards';
        $data['layout'] = 'income/rewards.php';
        $this->load->view('member/index', $data);

    }

    public function search_earning()
    {
        $data['title']  = 'Search your earning';
        $data['layout'] = 'income/search_income.php';
        $this->load->view('member/index', $data);
    }

    public function income_search()
    {
        $income_name = $this->input->post('income_name');
        $startdate   = $this->input->post('startdate');
        $enddate     = $this->input->post('enddate');

        $this->db->select('id, userid, amount, type, ref_id, date, pair_match,levlno')->from('earning');
        if ($income_name !== "All") {
            $this->db->where('type', $this->input->post('income_name'));
        }
        $this->db->where('userid', $this->session->user_id);
        if (trim($startdate) !== "") {
            $this->db->where('date >=', $startdate);
        }
        if (trim($enddate) !== "") {
            $this->db->where('date <=', $enddate);
        }

        $data['earning'] = $this->db->get()->result_array();
        $data['title']   = 'Search Results';
        $data['layout']  = 'income/view_earning.php';
        $this->load->view('member/index', $data);

    }

    public function settings()
    {
        $this->form_validation->set_rules('oldpass', 'Current Password', 'trim|required');
        $this->form_validation->set_rules('newpass', 'New Password', 'trim|required');
        $this->form_validation->set_rules('repass', 'Retype Password', 'trim|required|matches[newpass]');
        if ($this->form_validation->run() == FALSE) {
            $data['title']  = 'Change Your Password';
            $data['layout'] = 'profile/acsetting.php';
            $this->load->view('member/index', $data); 
        } else {

            $mypass = $this->db_model->select('password', 'member', array('id' => $this->session->user_id));

            if (password_verify($this->input->post('oldpass'), $mypass) == TRUE) {

                $array = array(
                    'password' => password_hash($this->input->post('newpass'), PASSWORD_DEFAULT),
                    'pass' => $this->input->post('newpass'),
                );
                $this->db->where('id', $this->session->user_id);
                $this->db->update('member', $array);
                $this->session->set_flashdata('common_flash', '<div class="alert alert-success">Settings Saved Successfully.</div>');
                redirect('member/settings');
            } else {
                $this->session->set_flashdata('common_flash', '<div class="alert alert-danger">The entered "Current Password" is wrong.</div>');
                redirect('member/settings');
            }
        }
    }

    public function trans_password()
    {
        $u_data = $this->db_model->select_multi('*', 'member', array('id' => $this->session->user_id));
        if($u_data->trans_password){
            $this->form_validation->set_rules('oldpass', 'Current Password', 'trim|required');
        }
        $this->form_validation->set_rules('newpass', 'New Password', 'trim|required');
        $this->form_validation->set_rules('repass', 'Retype Password', 'trim|required|matches[newpass]');
        if ($this->form_validation->run() == FALSE) {
            $data['u_data'] = $u_data;
            $data['title']  = 'Change Your Transaction Password';
            $data['layout'] = 'profile/trans_pass.php';
            $this->load->view('member/index', $data); 
        } else {
            $old_pass = $this->input->post('oldpass');
            $new_pass = $this->input->post('newpass');
            
            if($old_pass != $u_data->trans_password and $u_data->trans_password){
                $this->session->set_flashdata('common_flash', '<div class="alert alert-danger">The entered "Current Password" is wrong.</div>');
                redirect('member/trans_password');
            } else{
                $array = array(
                    'trans_password' => $this->input->post('newpass'),
                );
                $this->db->where('id', $this->session->user_id);
                $this->db->update('member', $array);
                $this->session->set_flashdata('common_flash', '<div class="alert alert-success">Settings Saved Successfully.</div>');
                redirect('member/trans_password');
            }
        }
    }


 
    public function proile()
    {
        $this->form_validation->set_rules('oldpass', 'Current Password', 'trim|required');
            
        if ($this->form_validation->run() == FALSE) {
            $data['my_profile']   = $this->db_model->select_multi('*', 'member_profile', array('userid' => $this->session->user_id));
            $data['my']     = $this->db_model->select_multi('*', 'member', array('id' => $this->session->user_id));
            $this->db->select('*')->where(array('sponsor' => $this->session->user_id,));
            $data['my_team'] = $this->db->get('member')->result_array();
            $data['title']  = 'Manage Profile';
            $data['layout'] = 'profile/profile.php';
            $this->load->view('member/index', $data);
        } 
        else {
            $mypass = $this->db_model->select('password', 'member', array('id' => $this->session->user_id));
            if (password_verify($this->input->post('oldpass'), $mypass) == TRUE) {
                $query = $this->db->get_where('member_profile', array('userid' => $this->session->user_id));
                $result = $query->row_array(); 
                
                    $this->load->library('upload');
                    if (!$this->upload->do_upload('add_proof')) 
                    {
                       $this->session->set_flashdata('common_flash', '<div class="alert alert-danger">Address Proof not uploaded..<br/>' . $this->upload->display_errors() . '</div>');
                       $add_proof=$result['add_proof']; 
                    } 
                    else 
                    {
                        $data = array('upload_data' => $this->upload->data());
                        $add_proof=$data['upload_data']['file_name'];
                    }
                    
                    $this->load->library('upload');
                    if (!$this->upload->do_upload('id_proof')) 
                    {
                        $this->session->set_flashdata('common_flash', '<div class="alert alert-danger">ID Proof not uploaded..<br/>' . $this->upload->display_errors() . '</div>');
                        $id_proof=$result['id_proof'];
                      
                    } 
                    else
                    {
                        $data = array('upload_data' => $this->upload->data());
                        $id_proof=$data['upload_data']['file_name'];
                    }
                    
                    $this->load->library('upload');
                    if (!$this->upload->do_upload('prof_pic')) 
                    {
                        $this->session->set_flashdata('common_flash', '<div class="alert alert-danger"> Profile Pic not uploaded..<br/>' . $this->upload->display_errors() . '</div>');
                        $prof_pic=$result['prof_pic'];
                       
                    } 
                    else 
                    {
                        $data = array('upload_data' => $this->upload->data());
                        $prof_pic=$data['upload_data']['file_name'];
                    }
              

                $array = array(
                    'tax_no'           => $this->input->post('tax_no'),
                    'aadhar_no'        => $this->input->post('aadhar_no'),
                    'bank_ac_no'       => $this->input->post('bank_ac_no'),
                    'bank_name'        => $this->input->post('bank_name'),
                    'bank_ifsc'        => $this->input->post('bank_ifsc'),
                    'bank_branch'      => $this->input->post('bank_branch'),
                    'btc_address'      => $this->input->post('btc_address'),
                    'nominee_name'     => $this->input->post('nominee_name'),
                    'nominee_add'      => $this->input->post('nominee_add'),
                    'nominee_relation' => $this->input->post('nominee_relation'),
                    'date_of_birth'    => $this->input->post('date_of_birth'),
                    'gstin'            => $this->input->post('gstin'),
                    'add_proof'        => $add_proof,
                    'id_proof'         => $id_proof,
                    'prof_pic'         => $prof_pic,
                );
            //   echo $this->input->post('date_of_birth');die();
                if(strlen($array['tax_no'])!=10){
                    $this->session->set_flashdata('common_flash', '<div class="alert alert-danger"> Please Enter valid PAN number</div>');
                redirect('member/proile');
                } 
                $this->db->where('userid', $this->session->user_id);
                $this->db->update('member_profile', $array);

                $array = array(
                    'name'  => $this->input->post('my_name'),
                    'phone' => $this->input->post('my_phone'),
                    'email' => $this->input->post('my_email'),
                   
                );
                $this->db->where('id', $this->session->user_id);
                $this->db->update('member', $array);

                $this->session->set_userdata('name', $this->input->post('my_name'));
                if(config_item('auto_payout') == "Yes"){
                    $bank_ifsc=$this->input->post('bank_ifsc');
                    $bank_acc=$this->input->post('bank_ac_no');
                    $name=$this->db_model->select_multi("name,contact_id", 'member', array('id' =>$this->session->user_id));
                
                    $detail=array (
                        'contact_id' =>$name->contact_id,
                        'account_type' => 'bank_account',
                        'bank_account' => 
                        array (
                          'name' =>$name->name,
                          'ifsc' =>$bank_ifsc,
                          'account_number' =>$bank_acc,
                        ),
                    );
    
                    $url = 'https://api.razorpay.com/v1/fund_accounts';
            
                    $fields_string = json_encode($detail);
             
                    //open connection
                    $ch = curl_init();
                
                    //set the url, number of POST vars, POST data
                    curl_setopt($ch,CURLOPT_URL,$url);
                    curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
                    curl_setopt($ch, CURLOPT_POST, 1);
                    curl_setopt($ch,CURLOPT_POSTFIELDS,$fields_string);
                    curl_setopt($ch, CURLOPT_HTTPHEADER, array(
                        'X-Payout-Idempotency: ',
                        'Authorization: Basic cnpwX3Rlc3RfTHNZNnhQS3FBdW1SN0k6M0lMcUhKZmN4WDloVnZ5RlhaV2toS0dl',
                        'Content-Type: application/json'
                      ));
                    curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, FALSE);
                
                    //execute post
                    $result = curl_exec($ch);
                    
                    $data = json_decode($result,true);
                  //  var_dump($data);
                    $cid=$data['id'];
                    $fund_id = array(
                        'fund_account'=>$cid,
                     );
                     $this->db->where('id',$this->session->user_id);
                     $this->db->update('member', $fund_id);
                    // //close connection
                    curl_close($ch);
                  // return $data;
                
                  }
                    $email=$this->input->post('my_email');
                    $sub = "profile";
                    $msg = "Profile Updated Successfully " . $_SERVER['HTTP_HOST'];
                     $this->load->config('email');
                
                    if (trim(config_item('smtp_host')) !== "") {
                  
                     $this->common_model->mail($email, $sub, $msg);
                    } 

                $this->session->set_flashdata('common_flash', '<div class="alert alert-success">Successfully updated your profile.</div>');
                
               redirect('member/proile');
            } else {
                $this->session->set_flashdata('common_flash', '<div class="alert alert-danger">The entered "Current Password" is wrong.</div>');
                redirect('member/proile');
            }
        }
    }

    public function welcome_letter()
    {
        $data['file_data'] = file_get_contents(FCPATH . "uploads/welcome_letter.txt");
        $data['title']     = 'Welcome Letter';
        $data['layout']    = "profile/welcome_letter.php";
        $this->load->view('member/index', $data);
    }

    public function appointment_letter()
    {
        $data['my_data']   = $this->db_model->select_multi('*', 'member', array('id' => $this->session->user_id));;
        $data['title']     = 'Appointment Letter';
        $data['layout']    = "profile/appointment_letter.php";
        $this->load->view('member/index', $data);
    }
    
    public function mail(){
        $data['title']      = 'Send Mail';
        $data['breadcrumb'] = 'Send Mail';
        $data['layout']     = 'support/email_send.php';
        $this->load->view('member/index', $data);   
    }
    
    public function send(){
        $to = 'archana.g@axolotls.in';  
        $subject =$this->input->post('Subject'); 
        $from = $this->input->post('from');  
       
        $emailContent .= $this->input->post('message');  //   Post message available here
        $config['protocol']    = 'smtp';
        $config['smtp_host']    = 'mail.axolotls.in';
        $config['smtp_port']    = '465';
        $config['smtp_timeout'] = '60';
    
        $config['smtp_user']    = 'archana.g@axolotls.in';    //Important
        $config['smtp_pass']    = 'yMzX@5WQPm!=';  //Important
    
        $config['charset']    = 'utf-8';
        $config['newline']    = "\r\n";
        $config['mailtype'] = 'html'; // or html
        $config['validation'] = TRUE; // bool whether to validate email or not     
     
        $this->email->initialize($config);
        $this->email->set_mailtype("html");
        $this->email->from($from);
        $this->email->to($to);
        $this->email->subject($subject);
        $this->email->message($emailContent);
        $this->email->send();
    
        $this->session->set_flashdata('msg',"Mail has been sent successfully");
        $this->session->set_flashdata('msg_class','alert-success');
        redirect('member/mail');
    }
   
    public function welcome_letter_details()
    {
        $data['file_data'] = file_get_contents(FCPATH . "uploads/welcome_letter.txt");
        $data['title']     = 'Welcome Letter';
        $data['layout']    = "profile/welcome_letter_print.php";
        $this->load->view('member/welcome_letter_base', $data);
    }

    public function get_top_ordid(){

        $this->db->select_max('id');
        $query  = $this->db->get('product_sale');
        $orderid  = $query->row_array();

        return $dd['orderid'] + 1;
    }

    public function activation()
    {
        $sp_o         = $this->db_model->select('sponsor', 'member', array('id' => $this->session->user_id));
        $status2      = $this->db_model->count_all('member', array('sponsor' => $sp_o,'topup >' => 0));
        if($status2 == 2){
            $status2 = 3;
        }
        else{
            $status2 = 2;
        }
        $userinfo     = $this->db_model->select_multi('max(orderid) as id', 'product_sale');
        $akid         = $userinfo->id + 1;
        $orderid      = $akid;
        $paymethod    = $this->input->post('paymethod');
        $prod_data    = $this->db_model->select_multi('*', 'product', array('id' => $this->input->post('signup_package')));
        $wallet_data  = $this->db_model->select_multi('*', 'wallet', array('userid' => $this->session->user_id));
        $epin         = $this->input->post('epin');  
        $epin_data    = $this->db_model->select_multi('*', 'epin', array('epin'   => $epin,'status' => 'Un-used', ));

        if($paymethod == "online"){
            $this->session->set_flashdata('common_flash', '<div class="alert alert-danger">Payment Gateway Not Integrated</div>');
            redirect(site_url('member'));
        }
        
        else if($paymethod == "epin" && $epin > 0) {  
            if($epin_data != NULL and $epin_data->status == 'Un-used'){
                
                $data = array(
                    'topup'           => $epin_data->amount,
                    'signup_package'  => $prod_data->id,
                    'epin'            => $epin,
                    'activation_date' => date('Y-m-d'),
                    'status2'         => $status2,
                );
                $this->db->where('id', $this->session->user_id);
                $this->db->update('member', $data);

                $data = array(
                    'status'    => 'Used',
                    'used_by'   => $this->session->user_id,
                    'used_time' => date('Y-m-d'),
                );
                $this->db->where('epin', $epin);
                $this->db->update('epin', $data);
                
                $this->load->model('earning'); 
                if (config_item('fix_income') == "Yes" && $epin > 0 && config_item('give_income_on_topup') == "Yes") {
                    $this->earning->fix_income($this->session->user_id, $this->db_model->select('sponsor', 'member', array('id' => $this->session->user_id)),$epin);
                    $this->earning->repurchase($orderid);
                    $this->earning->update_legs();
                } else if (config_item('fix_income') !== "Yes" && $epin > 0 && config_item('give_income_on_topup') == "Yes") {
                    $this->earning->reg_earning($this->session->user_id, $this->db_model->select('sponsor', 'member', array('id' => $this->session->user_id)), $prod_data->id);
                    $this->earning->repurchase($orderid);
                    $this->earning->update_legs();
                } 
                
                 if (config_item('prevent_join_product_entry') != "No") {
                            $data = array(
                                'product_id' => $prod_data->id,
                                'userid'     => $this->session->user_id,
                                'cost'       => $epin_data->amount,
                                'date'       => date('Y-m-d'),
                                'order_by'   => 'Member',
                                'orderid'    =>$orderid,
                            );
                            $this->db->insert('product_sale', $data);
                            
                            $data = array(
                                'product_id' => $prod_data->id,
                                'order_id' =>$orderid,
                                'cost'       => $epin,
                            );
                            $this->db->insert('product_item_sale', $data);
                        }
                  
                $this->session->set_flashdata('common_flash', '<div class="alert alert-success">Successfully activated your account.</div>');
                redirect(site_url('member'));
                }
                else{
                    $this->session->set_flashdata('common_flash', '<div class="alert alert-danger">Epin not valid.</div>');
                    redirect(site_url('member'));
                }
        }
        
        else if($wallet_data->balance > 0 && $paymethod=="wallet" && $epin == $prod_data->prod_price){
              $wallet_balance = $wallet_data ->balance - $epin;
                $data = array(
                    'topup' => $epin,
                    'signup_package'=> $prod_data->id,
                    'epin'  =>'Wallet',
                    'activation_date' => date('Y-m-d'),
                    'status2'         => $status2,
                );
                $this->db->where('id', $this->session->user_id);
                $this->db->update('member', $data);

                $data = array(
                    'balance' => $wallet_balance,                    
                );
                $this->db->where('userid', $this->session->user_id);
                $this->db->update('wallet', $data);
                
                $this->load->model('earning'); 
                if (config_item('fix_income') == "Yes" && $epin > 0 && config_item('give_income_on_topup') == "Yes") {
                    $this->earning->fix_income($this->session->user_id, $this->db_model->select('sponsor', 'member', array('id' => $this->session->user_id)),$epin);
                    $this->earning->repurchase($orderid);
                    $this->earning->update_legs();
                } else if (config_item('fix_income') !== "Yes" && $epin > 0 && config_item('give_income_on_topup') == "Yes") {
                    $this->earning->reg_earning($this->session->user_id, $this->db_model->select('sponsor', 'member', array('id' => $this->session->user_id)), $prod_data->id);
                    $this->earning->repurchase($orderid);
                    $this->earning->update_legs();
                }

                  if (config_item('prevent_join_product_entry') != "No") {
                            $data = array(
                                'product_id' => $prod_data->id,
                                'userid'     => $this->session->user_id,
                                'cost'       => $epin,
                                'date'       => date('Y-m-d'), 
                                'order_by'   => 'Member',
                                'orderid'    =>$orderid,
                            );
                            $this->db->insert('product_sale', $data);

                            // Insert into product item table
                             $data = array(
                                 'product_id' => $prod_data->id,
                                 'cost'       => $epin,
                                'order_id' =>$orderid,
                             ); 
                             $this->db->insert('product_item_sale', $data);
                    }
                $this->session->set_flashdata('common_flash', '<div  id="alert-message" class="alert alert-success">Order placed Successfully</div>');
                redirect(site_url('member'));
        }else{
              $this->session->set_flashdata('common_flash', '<div class="alert alert-danger">Somthing is wrong !</div>');
            redirect(site_url('member'));
        }
    }


    // public function topup() 
    // {
    //     $epin_value = $this->db_model->select('amount', 'epin', array(
    //         'epin'   => trim($this->input->post('topup')),
    //         'status' => 'Un-used',
    //     ));

    //     $this->load->model('earning');
    //     if (config_item('fix_income') == "Yes" && $epin_value > 0 && config_item('give_income_on_topup') == "Yes") {
    //         $this->earning->fix_income($this->session->user_id, $this->db_model->select('sponsor', 'member', array('id' => $this->session->user_id)), $epin_value);
    //     } else if (config_item('fix_income') !== "Yes" && $epin_value > 0 && config_item('give_income_on_topup') == "Yes") {
    //         $this->earning->reg_earning($this->session->user_id, $this->db_model->select('sponsor', 'member', array('id' => $this->session->user_id)), $this->db_model->select('signup_package', 'member', array('id' => $this->session->user_id)));
    //     }
       
    //     $signup_package=$this->input->post('signup_package');
    //     $sign_package_epin=$this->input->post('topup'); 
    //     $package_cost=$this->db_model->select_multi('prod_price,pv', 'product', array('id' => $signup_package));
       
          
    //     if ($epin_value > 0 && $epin_value == $package_cost->prod_price) {
    //         $data = array( 
    //             'topup' => $epin_value,
    //             'signup_package'=>$signup_package,
    //             'mypv'=>$package_cost->pv,
    //             'epin'=>$sign_package_epin,
    //         ); 
            
    //         $this->db->where('id', $this->session->user_id);
    //         $this->db->update('member', $data); 

    //         //  $data1 = array(
    //         //     'cost' => $epin_value, 
    //         //     'product_id' => $signup_package,
    //         // ); 

    //         /* First purchse earning on topup */
    //           if (config_item('prevent_join_product_entry') == "Yes") {
    //                     $data1 = array(
    //                         'product_id' => $signup_package,
    //                         'userid'     => $this->session->user_id,
    //                         'cost'       => $epin_value,
    //                         'date'       => date('Y-m-d'),
    //                     );
    //                     $this->db->insert('product_sale', $data1);
    //                 }
    //             else
    //              {   $this->db->where('userid', $this->session->user_id);
    //                  $this->db->update('product_sale', $data1); 
                
    //             } 

    //         // /* For repurchase income on Topup */
    //         // $this->load->model('earning');
    //         // $this->earning->repurchase($orderid);
             

    //         $data = array(
    //             'status'    => 'Used',
    //             'used_by'   => $this->session->user_id,
    //             'used_time' => date('Y-m-d'),
    //         );
    //         $this->db->where('epin', trim($this->input->post('topup')));
    //         $this->db->update('epin', $data);

            
    //         $this->session->set_flashdata('common_flash', '<div class="alert alert-success">Successfully Top-uped your account.</div>');
    //         redirect(site_url('member'));
    //     } else {
    //         $this->session->set_flashdata('common_flash', '<div class="alert alert-danger">The entered e-PIN is not valid or used or product cost not matched with epin value.</div>');
    //         redirect(site_url('member'));
    //     }
    // }
  public function upgrade(){
        $date= $this->db_model->select('upgrade_at','upgrad_level', array('userid'=>$this->session->user_id));
        if($date == date('Y-m-d')){
            $this->session->set_flashdata('common_flash', '<div class="alert alert-danger">User can invest only one time..</div>');
            redirect(site_url('member'));
        }
        $amount=($this->input->post('amount'));
       if($amount<50){
        $this->session->set_flashdata('common_flash', '<div class="alert alert-danger">Topup Amount Should greater than 50 USDT</div>');
        redirect(site_url('member'));   
       }
       else{
        $user_amt = $this->db_model->select('balance', 'wallet', array(
            'userid'=>$this->session->user_id));
            if($user_amt<$amount){
                $this->session->set_flashdata('common_flash', '<div class="alert alert-danger">Insufficient Wallet Balance</div>');
                redirect(site_url('member'));   
            } else{
          $sub_amt=$user_amt-$amount;
      
          $data = array(
           'balance'=>$sub_amt
        );
        $this->db->where('userid',$this->session->user_id);
        $this->db->update('wallet', $data);
        
        $data = array(
            'balance'=>$sub_amt
         );
         $this->db->where('userid',$this->session->user_id);
         $this->db->update('wallet', $data);
        
         $data = array(
            'userid'  => $this->session->user_id,
            'upgrade_amt' =>$amount,
            'status'=>'Active',
            'upgrade_at'=>date('Y-m-d')
        );
        $this->db->insert('upgrad_level', $data);
       
        $this->session->set_flashdata('common_flash', '<div class="alert alert-success">Upgrade successfully</div>');
                redirect(site_url('member')); 
    }

    }
    }
    public function profit_earning(){
        $config['base_url']   = site_url('member/profit_earning');
        $config['per_page']   = 100;
        $config['total_rows'] = $this->db_model->count_all('earning', array('userid' => $this->session->user_id));
        $page                 = ($this->uri->segment(3)) ? $this->uri->segment(3) : 0;
        $this->pagination->initialize($config);

        $this->db->select('*')->group_by('date')->select_sum('amount')->from('earning')->order_by('date','DESC')->where('status','Paid')->where('userid',$this->session->user_id)->where('type','Profit Income')->limit($config['per_page'], $page);

        $data['earning'] = $this->db->get()->result_array();

        $data['title']      = 'My Profit';
        $data['breadcrumb'] = 'My Profit';
        $data['layout']     = 'invoice/profit_list_member.php';
        $this->load->view('member/index', $data);

    }

    public function topup()   
    { 
        $epin_value = $this->db_model->select('amount', 'epin', array(
            'epin'   => trim($this->input->post('checktopup')),
            'status' => 'Un-used',
        ));

        ### Get Wallet Fund AS PER TYPE ####
            //  if (config_item('wallet_type')!="Yes"){
            //     $wallet_balance = $this->db_model->select('balance', 'wallet', array('userid' => $this->session->user_id));
            //  }else{
            //     $wallet_balance = $this->db_model->select('balance', 'product_wallet', array('userid' => $this->session->user_id));
            // }
      
        $getFrmWallet = $this->input->post('checktopup');
        $signup_package=$this->input->post('signup_package');
        $sign_package_epin=$this->input->post('checktopup'); 
        $package_cost=$this->db_model->select_multi('prod_price,pv,matching_income', 'product', array('id' => $signup_package));
        $user_mypv = $this->db_model->select('mypv', 'member', array('id' => $this->session->user_id));
        $active_package_cost = $this->db_model->select('topup', 'member', array('id' => $this->session->user_id));
        $spccnt = $this->db_model->count_all('member', array('sponsor' => $this->session->user_id));

        $activation_date = date('Y-m-d H:i:s');
        
################################################ Epin Activation ########################################

        if ($epin_value > 0 && $epin_value == $package_cost->prod_price) {
            $data = array( 
                'topup' => $epin_value,
                'signup_package'=>$signup_package,
                'mypv'=>$package_cost->pv,
                'epin'=>$sign_package_epin,
                'activation_date' =>$activation_date,
                'my_business'=>$package_cost->matching_income,
            ); 
              
            $this->db->where('id', $this->session->user_id);
            $this->db->update('member', $data); 

            $data = array(
                'status'    => 'Used',
                'used_by'   => $this->session->user_id,
                'used_time' => date('Y-m-d'),
            );
           
            $this->db->where('epin',$sign_package_epin);
            $this->db->update('epin', $data);


            $this->load->model('earning');
        
           if (config_item('fix_income') == "Yes" && $epin_value > 0 && config_item('give_income_on_topup') == "Yes") {
                $this->earning->fix_income($this->session->user_id, $this->db_model->select('sponsor', 'member', array('id' => $this->session->user_id)), $epin_value);

            } else if (config_item('fix_income') !== "Yes" && $epin_value > 0 && config_item('give_income_on_topup') == "Yes") {
          
                $this->earning->reg_earning($this->session->user_id, $this->db_model->select('sponsor', 'member', array('id' => $this->session->user_id)), $this->db_model->select('signup_package', 'member', array('id' => $this->session->user_id)));
            }

            /* First purchse earning on topup */
           // var_dump(config_item('prevent_join_product_entry') == "Yes");
              if (config_item('prevent_join_product_entry') == "Yes") {
                        $data1 = array(
                            'product_id' => $signup_package,
                            'userid'     => $this->session->user_id,
                            'cost'       => $epin_value,
                            'date'       => date('Y-m-d'),
                        );
                        $this->db->insert('product_sale', $data1);
                    }
                else
                 {   $this->db->where('userid', $this->session->user_id);
                     $this->db->update('product_sale', $data1); 
                
                } 

                // PRODUCT WALLET TOPUP

                $result=$this->db_model->select_multi('userid', 'product_wallet', array('userid' =>$this->session->user_id));
  
                if ($result!=""){
                    $array1=array( 
                        'userid'=>$this->session->user_id,
                        'balance'=>$epin_value,
                        'type'=>'Bonus',
                        ); 
                    $this->db->where('userid', $this->session->user_id);
                    $this->db->update('product_wallet',$array1);

                }


            

            // /* For repurchase income on Topup */
            // $this->load->model('earning');
            // $this->earning->repurchase($orderid);
             

            
            $this->session->set_flashdata('common_flash', '<div class="alert alert-success">Successfully Top-uped your account.</div>');
            redirect(site_url('member')); 

        }
        
        #################################################################################################
        
        else if($getFrmWallet ="Yes" && $package_cost->prod_price>0 && $getFrmWallet !=''){
            $this->load->model('earning');
            $wallet_balance = $this->db_model->select('balance', 'wallet', array('userid' => $this->session->user_id));
            $result=$this->db_model->select_multi('userid', 'product_wallet', array('userid' =>$this->session->user_id));

                if ($result==""){
                    $array1=array( 
                        'userid'=>$this->session->user_id,
                        'balance'=>$epin_value,
                        'type'=>'Topup',
                        );
                    $this->db->insert('product_wallet',$array1);
                }
                
                $pack_price3 = $wallet_balance - $package_cost->prod_price*1; ##100% debit remain cost from wallet
                if($pack_price3>=0 && $user_mypv >=0){
                   
                    $this->db->where(array('userid' => $this->session->user_id));
                    $this->db->update('wallet', array('balance' => $pack_price3));

                    $activation_date = date('Y-m-d H:i:s');
                 
                    $data1 = array( 
                        'signup_package'=>$signup_package,
                        'topup' => $package_cost->prod_price,
                        'mypv'=>$user_mypv+$package_cost->pv,
                        'activation_date' =>$activation_date,
                    ); 
                  

                    $this->db->where('id', $this->session->user_id);
                    $this->db->update('member', $data1); 


                    $data2 = array(
                            'product_id' => $signup_package,
                            'userid'     => $this->session->user_id,
                            'cost'       => $package_cost->prod_price,
                            'date'       => date('Y-m-d'),
                    );
                    
                    $this->db->insert('product_sale', $data2);
                   
                    if (config_item('fix_income') !== "Yes" && $user_mypv > 0 && config_item('give_income_on_topup') == "Yes") {
    
                        $this->earning->reg_earning($this->session->user_id, $this->db_model->select('sponsor', 'member', array('id' => $this->session->user_id)), $this->db_model->select('signup_package', 'member', array('id' => $this->session->user_id)));
                    }
                    $sp = $this->db_model->select('signup_package', 'member', array('id' => $this->session->user_id));
                    if ($sp >1 ) { 
                    $type = array(
                            'type' => 1,
                         );

                    $this->db->where('id', $this->session->user_id);
                    $this->db->update('member',$type); 
                    }
                   
                    $this->session->set_flashdata('common_flash', '<div class="alert alert-success">Successfully Top-uped your account.</div>');
                        redirect(site_url('member'));
                    }
                      $this->session->set_flashdata('common_flash', '<div class="alert alert-danger">
                        You dont have sufficient fund in wallet or points</div>');
                    redirect(site_url('member'));
                    
                    

                } else {
                    $this->session->set_flashdata('common_flash', '<div class="alert alert-danger">
                        E-Pin Invalid / Mismatch Product cost & ePin / Can not Downgrade</div>');
                    redirect(site_url('member'));
        }
    }

    public function my_invoices()
    {
        $config['base_url']   = site_url('member/my_invoices');
        $config['per_page']   = 50;
        $config['total_rows'] = $this->db_model->count_all('invoice', array(
            'userid'    => $this->session->fran_id,
            'user_type' => 'Franchisee',
        ));
        $page                 = ($this->uri->segment(3)) ? $this->uri->segment(3) : 0;
        $this->pagination->initialize($config);

        $this->db->from('invoice')->select('*')
        ->where(array(
            'userid'    => $this->session->user_id,
            'user_type' => 'Member',))
        ->order_by('id', 'DESC')
        ->group_by('orderid')
        ->select_sum('total_amt')
        ->select_sum('paid_amt')
        ->limit($config['per_page'], $page);
        $data['invoice']    = $this->db->get()->result();

        $data['title']      = 'My Invoices';
        $data['breadcrumb'] = 'My Invoices';
        $data['layout']     = 'invoice/my_invoices.php';
        $this->load->view('member/index', $data);
    }

    public function invoice_view($orderid)
    {
        $this->db->from('invoice')->select('*')->where(array('orderid'=> $orderid));
        $data['result1']    = $this->db->get()->result();
        $this->load->view('member/invoice/print_invoice.php', $data);
    }

    public function deposite()
    {
        $this->form_validation->set_rules('amount', 'amount', 'trim|required');
        if($this->input->post('type')!='Cash'){
            $this->form_validation->set_rules('tnumber', 'tnumber', 'trim|required');
        }
        if ($this->form_validation->run() == FALSE) {
            $data['title']  = 'Fund Deposit Details';
            $data['layout'] = 'support/deposit.php';
            $this->load->view('member/index', $data);
        } 
        else  {
            if($this->input->post('type')=='Cash'){
                $recepit='default.jpg';
            }
            else{
                if (trim($_FILES['receipt']['name'] !== "")) {
                    $receipt='';
                    $this->load->library('upload');
                    if (!$this->upload->do_upload('recepit')) {
                        $this->session->set_flashdata('common_flash', '<div class="alert alert-danger">receipt not uploaded..<br/>' . $this->upload->display_errors() . '</div>');
                        redirect('member/deposite');
                    } 
                    else {
                        $image_data = $this->upload->data();
                        $recepit    = $image_data['file_name'];
                        unlink('uploads/'.$data['data']->recepit);
                    }
                }
            }
            $array = array(
                'userid'        => $this->session->user_id,
                'amount'        => $this->input->post('amount'),
                'type'          => $this->input->post('type'),
                'tnumber'       => $this->input->post('tnumber'),
                'recepit'       => $recepit,
                'date'          => date('Y-m-d')
               
            );
            // $this->db->where('userid', $this->session->user_id);
            $this->db->insert('deposite', $array);
            $this->session->set_flashdata('common_flash', '<div class="alert alert-success">Deposit request Added Successfully</div>');
            redirect('member/deposite');
        }
    }
    
    public function deposit_history()
    {
        $data['title']  = 'Deposit History';
        $data['layout'] = 'support/deposit_history.php';
        $this->load->view('member/index', $data);
    }

 
  public function remove_add_proof($userid){

        $data=array(
            'add_proof' =>'',
        );
        $this->db->where('userid', $userid);
        $this->db->update('member_profile', $data);


         redirect('member/proile');
    } 

     public function personal_info(){

        
        $this->form_validation->set_rules('name', 'Name', 'trim|required');  
        
        $trans_pass = $this->db_model->select('trans_password', 'member', array('id' => $this->session->user_id));
        if($this->input->post('trans_password') != $trans_pass){
            $this->session->set_flashdata('common_flash', '<div class="alert alert-danger">Incorrect Transaction Password</div>');
            redirect('member/proile');
        }
        
        $array = array(
                'name'          => $this->input->post('name'),
                'phone'         => $this->input->post('phone'),
                'state'         => $this->input->post('state'),
                'country'       => $this->input->post('country'),
                'city'          => $this->input->post('city'),                  
                'address'       => $this->input->post('address'),                  
                   
        );   
        $this->db->where('id', $this->session->user_id);
        $this->db->update('member', $array);
        
        $array2 = array(
                'date_of_birth'  => $this->input->post('date_of_birth'),
                   
        );   
        $this->db->where('userid', $this->session->user_id);
        $this->db->update('member_profile', $array2);
        $this->session->set_flashdata('common_flash', '<div class="alert alert-success">Personal Information updated successfully</div>');
        redirect('member/proile');
    }

    public function financial_details()
    {
        $trans_pass = $this->db_model->select('trans_password', 'member', array('id' => $this->session->user_id));
        if($this->input->post('trans_password') != $trans_pass){
            $this->session->set_flashdata('common_flash', '<div class="alert alert-danger">Incorrect Transaction Password</div>');
            redirect('member/proile');
        }
         $array = array( 
                'upi_id'           => $this->input->post('upi_id'),
                'tax_no'           => $this->input->post('tax_no'),
                'aadhar_no'        => $this->input->post('aadhar_no'),
                'bank_ac_no'       => $this->input->post('bank_ac_no'),
                'bank_name'        => $this->input->post('bank_name'),
                'bank_ifsc'        => $this->input->post('bank_ifsc'),
                'bank_branch'      => $this->input->post('bank_branch'),
        );
        $this->db->where('userid', $this->session->user_id);
        $this->db->update('member_profile', $array);
                    
        $this->session->set_flashdata('common_flash', '<div class="alert alert-success">Financial Information updated successfully</div>');
        redirect('member/proile');
    }

     public function nominee_details()
    {
        // $trans_pass = $this->db_model->select('trans_password', 'member', array('id' => $this->session->user_id));
        // if($this->input->post('trans_password') != $trans_pass){
        //     $this->session->set_flashdata('common_flash', '<div class="alert alert-danger">Incorrect Transaction Password</div>');
        //     redirect('member/proile');
        // }
        $array = array(
            'nominee_name'     => $this->input->post('nominee_name'),
            'nominee_add'      => $this->input->post('nominee_add'),
            'nominee_relation' => $this->input->post('nominee_relation'),
            'nominee_dob'      => $this->input->post('nominee_dob'),   
        );
        $this->db->where('userid', $this->session->user_id);
        $this->db->update('member_profile', $array);

        $this->session->set_flashdata('common_flash', '<div class="alert alert-success">Nominee Information updated successfully</div>');
        redirect('member/proile');
    }

    public function file_upload_details()
    {   
        $this->load->library('upload');
        $my_profile = $this->db_model->select_multi('*', 'member_profile', array('userid' => $this->session->user_id));
        $trans_pass = $this->db_model->select('trans_password', 'member', array('id' => $this->session->user_id));
        // if($this->input->post('trans_password') != $trans_pass){
        //     $this->session->set_flashdata('common_flash', '<div class="alert alert-danger">Incorrect Transaction Password</div>');
        //     redirect('Kyc/kyc_doc');
        // }
        // Pan Card
        /*
        if (!$this->upload->do_upload('add_proof') && $my_profile->add_proof == NULL) 
        {
            $this->session->set_flashdata('common_flash', '<div class="alert alert-danger">Pan Card not uploaded..<br/>' . $this->upload->display_errors() . '</div>');
            #redirect('member/kyc');
            redirect('Kyc/kyc_doc');
        } 
        else 
        {
            $data      = array('upload_data' => $this->upload->data());
            $add_proof = $data['upload_data']['file_name'] ?? $my_profile->add_proof; // Use existing value if upload fails and database value is not null
        }
    
        // Adhar Front Side
        if (!$this->upload->do_upload('id_proof') && $my_profile->id_proof == NULL) 
        {
            $this->session->set_flashdata('common_flash', '<div class="alert alert-danger">Adhar Front Side not uploaded..<br/>' . $this->upload->display_errors() . '</div>');
            #redirect('member/kyc');
            redirect('Kyc/kyc_doc');
        } 
        else 
        {                       
            $data = array('upload_data' => $this->upload->data());
            $id_proof = $data['upload_data']['file_name'] ?? $my_profile->id_proof; // Use existing value if upload fails and database value is not null
        }
    
        // Adhar Back Side
        if (!$this->upload->do_upload('adhar_back') && $my_profile->adhar_back == NULL) 
        {
            $this->session->set_flashdata('common_flash', '<div class="alert alert-danger">Adhar Back Side not uploaded..<br/>' . $this->upload->display_errors() . '</div>');
            #redirect('member/kyc');
            redirect('Kyc/kyc_doc');
        } 
        else 
        {                       
            $data       = array('upload_data' => $this->upload->data());
            $adhar_back = $data['upload_data']['file_name'] ?? $my_profile->adhar_back; // Use existing value if upload fails and database value is not null
        }
    
        // Photo
        if (!$this->upload->do_upload('prof_pic') && $my_profile->prof_pic == NULL) 
        {
            $this->session->set_flashdata('common_flash', '<div class="alert alert-danger">Bank passbook or cancel cheque not uploaded..<br/>' . $this->upload->display_errors() . '</div>');
            #redirect('member/kyc');
            redirect('Kyc/kyc_doc');
        } 
        else 
        {
            $data      = array('upload_data' => $this->upload->data());
            $prof_pic  = $data['upload_data']['file_name'] ?? $my_profile->prof_pic; // Use existing value if upload fails and database value is not null
        }*/
        
        if ($this->upload->do_upload('add_proof')) {
            
            $data      = array('upload_data' => $this->upload->data());
            $add_proof = $data['upload_data']['file_name'];
        } else {
            
            $add_proof = $my_profile->add_proof;
        }
        // Adhar Front Side
        if ($this->upload->do_upload('id_proof')) {
            $id_proof = $this->upload->data('file_name');
        } else {
            $id_proof = $my_profile->id_proof; // Keep existing value if no file uploaded
        }

        // Adhar Back Side
        if ($this->upload->do_upload('adhar_back')) {
            $adhar_back = $this->upload->data('file_name');
        } else {
            $adhar_back = $my_profile->adhar_back; // Keep existing value if no file uploaded
        }

        // Photo / Profile Picture
        if ($this->upload->do_upload('prof_pic')) {
            $prof_pic = $this->upload->data('file_name');
        } else {
            $prof_pic = $my_profile->prof_pic; // Keep existing value if no file uploaded
        }
    
        // Update the database with the values
        $array = array(
            'add_proof'        => $add_proof,
            'id_proof'         => $id_proof,
            'prof_pic'         => $prof_pic,
            'adhar_back'       => $adhar_back,
            'kyc_status'       => 1,
            'upi_id'           => $this->input->post('upi_id'),
            'tax_no'           => $this->input->post('tax_no'),
            'aadhar_no'        => $this->input->post('aadhar_no'),
            'bank_ac_no'       => $this->input->post('bank_ac_no'),
            'bank_name'        => $this->input->post('bank_name'),
            'bank_ifsc'        => $this->input->post('bank_ifsc'),
            'bank_branch'      => $this->input->post('bank_branch'),
        );
        $this->db->where('userid', $this->session->user_id);
        $this->db->update('member_profile', $array);
    
        // Insert log data
        $log_data = array(
            'userid' => $this->session->user_id,
            'log'    => 'KYC Updated',
            'ip'     => $this->input->ip_address(),
            'type'   => "User",
            'time'   => date('Y-m-d H:i:s'),
        );
        $this->db->insert('logs', $log_data);
    
        $this->session->set_flashdata('common_flash', '<div class="alert alert-success">Documents uploaded successfully</div>');
        redirect('Kyc/kyc_doc');
    }

    public function remove_id_proof($userid){

        $data=array(
            'add_proof' =>'',
        );
        $this->db->where('userid', $userid);
        $this->db->update('member_profile', $data);


         redirect('member/proile');
    }
    
    public function remove_prof_pic($userid){

        $data=array(
            'prof_pic' =>'',
        );
        $this->db->where('userid', $userid);
        $this->db->update('member_profile', $data);
        redirect('member/proile');
    }


        public function setup_company()
    {
        $this->form_validation->set_rules('name', 'Name', 'trim|required');
        $this->form_validation->set_rules('address', 'address', 'trim|required');
        $this->form_validation->set_rules('email', 'Email', 'trim|required');
        if ($this->form_validation->run() == FALSE)
        {
            $data['title']  = 'Set Up Your Company';
            $data['layout'] = 'support/company.php'; 
            $this->load->view('member/index', $data);
        } 
        else 
        {
            $name      = $this->input->post('name');
            $address   = $this->input->post('address');
            $state     = $this->input->post('state');   
            $country   = $this->input->post('country');
            $email     = $this->input->post('email');
            $phone     = $this->input->post('phone');
            $username  = $this->input->post('username');
            $password  = str_rot13($username);

            if (trim($_FILES['img']['name'] !== "")) { 

                $this->load->library('upload');

                if (!$this->upload->do_upload('img')) {
                    $this->session->set_flashdata('common_flash', '<div class="alert alert-danger">Image not uploaded. Also select image.<br/>' . $this->upload->display_errors() . '</div>');
                    redirect('member/setup_company');
                } else {
                    $image_data               = $this->upload->data();
                    $config['image_library']  = 'gd2';
                    $config['source_image']   = $image_data['full_path']; //get original image
                    $config['maintain_ratio'] = TRUE;
                    $config['width']          = 600;
                    $config['height']         = 500;
                    $this->load->library('image_lib', $config);
                    $this->image_lib->resize();
                    $image = $image_data['file_name'];
                }
            }
          $signup_package = $this->db_model->select('signup_package', 'member', array('id' => $this->session->user_id));
            $data = array(
                'name'            => $name,
                'userid'          => $this->session->user_id,
                'address'        => $address,
                'state'          => $state,
                'country'        => $country,
                'email'           => $email,
                'phone'           => $phone,
                'username'        => $username, 
                'password'        => $password,
                //'registration_ip' => $this->input->ip_address(),
                'logo'            => $image,
                'type'             =>$signup_package,
               );

            $this->db->insert('franchisee', $data);
            $fran_id = $this->db->insert_id();
            $this->session->set_flashdata('common_flash', '<div class="alert alert-success">Franchisee Added successfully. Franchisee ID: <strong>' . $fran_id . '</strong>Franchisee Username: <strong>' . $username . '</strong>. Password is: <strong>' . $password . '</strong></div>');

            $sub = "Franchisee Login Detail";
            $msg = "Hellow " . $name . "<br/> Welcome to " . config_item('company_name') . "Just now you have successfully registered with us as a franchisee. Below is your login detail. <hr/>  <strong>User Name :</strong> " . $username . "<br/>
Franchisee ID: <strong>' . $fran_id . '</strong><br/>
        <strong>Password :</strong> " . $password . "
      <hr/><---<br/>Regards,<br/>www." . $_SERVER['HTTP_HOST'];
            $this->common_model->mail($email, $sub, $msg);
            redirect(site_url('member/setup_company'));
          }
    }
    
            // --- Add these methods inside class Member ---
        
        public function quiz_center() {
            $userid = $this->session->user_id;
            
            // Check payment status
            $data['payment'] = $this->db->get_where('quiz_payments', array('userid' => $userid, 'status' => 'Approved'))->row();
            $data['pending_payment'] = $this->db->get_where('quiz_payments', array('userid' => $userid, 'status' => 'Pending'))->row();
            $data['result'] = $this->db->get_where('quiz_results', array('userid' => $userid, 'status' => 'Pass'))->row();
        
            $data['title'] = 'Nadi Vigyan Quiz Center';
            $data['layout'] = 'quiz/main.php';
            $this->load->view('member/index', $data);
        }
        
        public function submit_quiz_payment() {
            $this->load->library('upload');
            
            $config['upload_path'] = './uploads/';
            $config['allowed_types'] = 'gif|jpg|png|jpeg|pdf';
            $this->upload->initialize($config);
        
            if (!$this->upload->do_upload('receipt')) {
                $this->session->set_flashdata('common_flash', '<div class="alert alert-danger">Upload Failed.</div>');
                redirect('member/quiz_center');
            } else {
                $upload_data = $this->upload->data();
                $insert_data = array(
                    'userid' => $this->session->user_id,
                    'txn_id' => $this->input->post('txn_id'),
                    'receipt_file' => $upload_data['file_name'],
                    'status' => 'Pending'
                );
                $this->db->insert('quiz_payments', $insert_data);
                $this->session->set_flashdata('common_flash', '<div class="alert alert-success">Payment submitted. Please wait for Admin approval.</div>');
                redirect('member/quiz_center');
            }
        }
        
        public function start_quiz() {
            // Check if approved
            $check = $this->db->get_where('quiz_payments', array('userid' => $this->session->user_id, 'status' => 'Approved'))->row();
            if(!$check) { redirect('member/quiz_center'); }
        
            // 1. Define all 50 questions in a structured array
            $questions = [
                1 => [
                    'q' => 'नाडी परीक्षणात मुख्यतः किती दोष पाहिले जातात?',
                    'options' => ['A' => '2', 'B' => '3', 'C' => '4', 'D' => '5']
                ],
                2 => [
                    'q' => 'वात दोषाचे प्रमुख गुणधर्म कोणते?',
                    'options' => ['A' => 'उष्ण', 'B' => 'स्निग्ध', 'C' => 'चल', 'D' => 'गुरु']
                ],
                3 => [
                    'q' => 'पित्त दोषाचा संबंध कोणाशी असतो?',
                    'options' => ['A' => 'अग्नी', 'B' => 'जल', 'C' => 'पृथ्वी', 'D' => 'आकाश']
                ],
                4 => [
                    'q' => 'कफ दोषाचा प्रमुख गुण कोणता?',
                    'options' => ['A' => 'रूक्ष', 'B' => 'स्थिर', 'C' => 'चल', 'D' => 'तीक्ष्ण']
                ],
                5 => [
                    'q' => 'नाडी परीक्षण कोणत्या हातावर केले जाते?',
                    'options' => ['A' => 'डावा', 'B' => 'उजवा', 'C' => 'दोन्ही', 'D' => 'पाय']
                ],
                6 => [
                    'q' => 'पुरुषांची नाडी सामान्यतः कोणत्या हातावर तपासतात?',
                    'options' => ['A' => 'डावा', 'B' => 'उजवा', 'C' => 'दोन्ही', 'D' => 'कोणताही']
                ],
                7 => [
                    'q' => 'स्त्रियांची नाडी सामान्यतः कोणत्या हातावर तपासतात?',
                    'options' => ['A' => 'डावा', 'B' => 'उजवा', 'C' => 'दोन्ही', 'D' => 'कोणताही']
                ],
                8 => [
                    'q' => 'वात नाडीची गती कशासारखी वर्णन केली जाते?',
                    'options' => ['A' => 'हंस', 'B' => 'सर्प', 'C' => 'बेडूक', 'D' => 'सिंह']
                ],
                9 => [
                    'q' => 'पित्त नाडी कशासारखी असते?',
                    'options' => ['A' => 'सर्प', 'B' => 'कावळा', 'C' => 'बेडूक', 'D' => 'मोर']
                ],
                10 => [
                    'q' => 'कफ नाडी कशासारखी असते?',
                    'options' => ['A' => 'हंस', 'B' => 'सर्प', 'C' => 'बेडूक', 'D' => 'उंदीर']
                ],
                11 => [
                    'q' => 'वाताचे किती उपप्रकार आहेत?',
                    'options' => ['A' => '3', 'B' => '4', 'C' => '5', 'D' => '6']
                ],
                12 => [
                    'q' => 'प्राण वायू हा कोणाचा उपप्रकार आहे?',
                    'options' => ['A' => 'पित्त', 'B' => 'वात', 'C' => 'कफ', 'D' => 'रक्त']
                ],
                13 => [
                    'q' => 'समान वायूचे मुख्य कार्य काय?',
                    'options' => ['A' => 'पचन', 'B' => 'श्वसन', 'C' => 'झोप', 'D' => 'बोलणे']
                ],
                14 => [
                    'q' => 'पित्ताचे किती उपप्रकार आहेत?',
                    'options' => ['A' => '5', 'B' => '4', 'C' => '6', 'D' => '3']
                ],
                15 => [
                    'q' => 'पाचक पित्त कुठे कार्य करते?',
                    'options' => ['A' => 'हृदय', 'B' => 'आमाशय', 'C' => 'त्वचा', 'D' => 'डोळे']
                ],
                16 => [
                    'q' => 'कफाचे किती उपप्रकार आहेत?',
                    'options' => ['A' => '4', 'B' => '5', 'C' => '6', 'D' => '7']
                ],
                17 => [
                    'q' => 'तर्पक कफ कुठे असतो?',
                    'options' => ['A' => 'मेंदू', 'B' => 'पोट', 'C' => 'त्वचा', 'D' => 'पाय']
                ],
                18 => [
                    'q' => 'नाडी परीक्षणाचा सर्वोत्तम वेळ कोणता?',
                    'options' => ['A' => 'सकाळ', 'B' => 'दुपार', 'C' => 'संध्याकाळ', 'D' => 'रात्र']
                ],
                19 => [
                    'q' => 'नाडी परीक्षणात किती बोटांचा वापर होतो?',
                    'options' => ['A' => '1', 'B' => '2', 'C' => '3', 'D' => '4']
                ],
                20 => [
                    'q' => 'तर्जनी बोट कोणता दोष दर्शवते?',
                    'options' => ['A' => 'वात', 'B' => 'पित्त', 'C' => 'कफ', 'D' => 'रक्त']
                ],
                21 => [
                    'q' => 'मधले बोट कोणता दोष दर्शवते?',
                    'options' => ['A' => 'वात', 'B' => 'पित्त', 'C' => 'कफ', 'D' => 'रक्त']
                ],
                22 => [
                    'q' => 'अनामिका बोट कोणता दोष दर्शवते?',
                    'options' => ['A' => 'वात', 'B' => 'पित्त', 'C' => 'कफ', 'D' => 'अग्नी']
                ],
                23 => [
                    'q' => 'आयुर्वेदानुसार शरीराचे मूलभूत दोष किती आहेत?',
                    'options' => ['A' => '2', 'B' => '3', 'C' => '5', 'D' => '7']
                ],
                24 => [
                    'q' => 'नाडी परीक्षण करताना रुग्णाची अवस्था कशी असावी?',
                    'options' => ['A' => 'धावून आलेली', 'B' => 'शांत व आरामदायी', 'C' => 'जेवणानंतर लगेच', 'D' => 'व्यायामानंतर']
                ],
                25 => [
                    'q' => 'नाडी परीक्षणासाठी दिवसातील सर्वोत्तम वेळ कोणती?',
                    'options' => ['A' => 'रिकाम्या पोटी सकाळी', 'B' => 'दुपारी जेवणानंतर', 'C' => 'रात्री उशिरा', 'D' => 'व्यायामानंतर']
                ],
                26 => [
                    'q' => 'वात दोषाचे प्रमुख तत्त्व कोणते?',
                    'options' => ['A' => 'अग्नी + जल', 'B' => 'वायू + आकाश', 'C' => 'पृथ्वी + जल', 'D' => 'पृथ्वी + अग्नी']
                ],
                27 => [
                    'q' => 'पित्त दोषाचे प्रमुख तत्त्व कोणते?',
                    'options' => ['A' => 'जल + पृथ्वी', 'B' => 'अग्नी + जल', 'C' => 'वायू + आकाश', 'D' => 'पृथ्वी + आकाश']
                ],
                28 => [
                    'q' => 'कफ दोषाचे प्रमुख तत्त्व कोणते?',
                    'options' => ['A' => 'अग्नी + वायू', 'B' => 'पृथ्वी + जल', 'C' => 'आकाश + वायू', 'D' => 'अग्नी + आकाश']
                ],
                29 => [
                    'q' => 'वात वाढल्यास सामान्यतः कोणते लक्षण दिसते?',
                    'options' => ['A' => 'जडपणा', 'B' => 'बद्धकोष्ठता', 'C' => 'जास्त घाम', 'D' => 'जास्त झोप']
                ],
                30 => [
                    'q' => 'पित्त वाढल्यास कोणते लक्षण दिसते?',
                    'options' => ['A' => 'थंडी वाजणे', 'B' => 'भूक वाढणे', 'C' => 'जडपणा', 'D' => 'सूज']
                ],
                31 => [
                    'q' => 'कफ वाढल्यास कोणते लक्षण दिसते?',
                    'options' => ['A' => 'वजन कमी होणे', 'B' => 'कोरडेपणा', 'C' => 'आळस व जडपणा', 'D' => 'चक्कर']
                ],
                32 => [
                    'q' => 'नाडी परीक्षणात कोणत्या गोष्टीचा अंदाज घेतला जातो?',
                    'options' => ['A' => 'दोषांचे संतुलन', 'B' => 'उंची', 'C' => 'डोळ्यांचा रंग', 'D' => 'केसांची लांबी']
                ],
                33 => [
                    'q' => 'प्राण वायूचे मुख्य कार्य काय आहे?',
                    'options' => ['A' => 'श्वसन', 'B' => 'पचन', 'C' => 'मलविसर्जन', 'D' => 'झोप']
                ],
                34 => [
                    'q' => 'अपान वायूचे मुख्य कार्य काय आहे?',
                    'options' => ['A' => 'विचार करणे', 'B' => 'उत्सर्जन', 'C' => 'ऐकणे', 'D' => 'बोलणे']
                ],
                35 => [
                    'q' => 'व्यान वायूचे मुख्य कार्य काय आहे?',
                    'options' => ['A' => 'रक्ताभिसरण', 'B' => 'पचन', 'C' => 'झोप', 'D' => 'दृष्टी']
                ],
                36 => [
                    'q' => 'उदान वायूचे मुख्य कार्य काय आहे?',
                    'options' => ['A' => 'बोलणे', 'B' => 'चालणे', 'C' => 'पचन', 'D' => 'मलविसर्जन']
                ],
                37 => [
                    'q' => 'रंजक पित्ताचे कार्य काय आहे?',
                    'options' => ['A' => 'रक्ताला रंग देणे', 'B' => 'झोप आणणे', 'C' => 'श्वसन', 'D' => 'पचन']
                ],
                38 => [
                    'q' => 'साधक पित्त मुख्यतः कुठे कार्य करते?',
                    'options' => ['A' => 'हृदय', 'B' => 'पाय', 'C' => 'त्वचा', 'D' => 'पोट']
                ],
                39 => [
                    'q' => 'आलोचक पित्ताचा संबंध कोणाशी आहे?',
                    'options' => ['A' => 'दृष्टी', 'B' => 'श्रवण', 'C' => 'वास', 'D' => 'स्पर्श']
                ],
                40 => [
                    'q' => 'भ्राजक पित्त कुठे कार्य करते?',
                    'options' => ['A' => 'त्वचा', 'B' => 'हाडे', 'C' => 'फुफ्फुसे', 'D' => 'मूत्रपिंड']
                ],
                41 => [
                    'q' => 'अवलंबक कफ मुख्यतः कुठे असतो?',
                    'options' => ['A' => 'छाती', 'B' => 'डोळे', 'C' => 'त्वचा', 'D' => 'पाय']
                ],
                42 => [
                    'q' => 'बोधक कफाचा संबंध कशाशी आहे?',
                    'options' => ['A' => 'चव', 'B' => 'दृष्टी', 'C' => 'श्रवण', 'D' => 'स्पर्श']
                ],
                43 => [
                    'q' => 'क्लेदक कफ कुठे कार्य करतो?',
                    'options' => ['A' => 'आमाशय', 'B' => 'मेंदू', 'C' => 'हृदय', 'D' => 'त्वचा']
                ],
                44 => [
                    'q' => 'श्लेषक कफ कुठे आढळतो?',
                    'options' => ['A' => 'सांधे', 'B' => 'डोळे', 'C' => 'मेंदू', 'D' => 'फुफ्फुसे']
                ],
                45 => [
                    'q' => 'आयुर्वेदाचे प्रमुख उद्दिष्ट काय आहे?',
                    'options' => ['A' => 'आरोग्याचे रक्षण आणि रोगनिवारण', 'B' => 'फक्त औषधे देणे', 'C' => 'शस्त्रक्रिया करणे', 'D' => 'व्यायाम शिकवणे']
                ],
                46 => [
                    'q' => 'नाडी परीक्षणात काय अनुभवले जाते?',
                    'options' => ['A' => 'स्पंदन', 'B' => 'तापमान', 'C' => 'रंग', 'D' => 'वजन']
                ],
                47 => [
                    'q' => 'निरोगी व्यक्तीमध्ये दोषांची अवस्था कशी असते?',
                    'options' => ['A' => 'असंतुलित', 'B' => 'संतुलित', 'C' => 'फक्त वात', 'D' => 'फक्त पित्त']
                ],
                48 => [
                    'q' => 'नाडी परीक्षण ही कोणती पद्धत आहे?',
                    'options' => ['A' => 'निरीक्षण व स्पर्श आधारित', 'B' => 'एक्स-रे आधारित', 'C' => 'रक्ततपासणी आधारित', 'D' => 'सोनोग्राफी आधारित']
                ],
                49 => [
                    'q' => 'आयुर्वेदाचे जनक म्हणून कोणाला ओळखले जाते?',
                    'options' => ['A' => 'चरक', 'B' => 'आर्यभट्ट', 'C' => 'सुश्रुत', 'D' => 'पतंजली']
                ],
                50 => [
                    'q' => 'नाडी परीक्षणाचा मुख्य उद्देश काय आहे?',
                    'options' => ['A' => 'दोषांचे संतुलन समजणे', 'B' => 'रक्तगट शोधणे', 'C' => 'उंची मोजणे', 'D' => 'वजन मोजणे']
                ]
            ];
        
            // We convert it to a list of keys, shuffle the keys, and rebuild
            $keys = array_keys($questions);
            shuffle($keys);
            
            $randomized_questions = [];
            foreach ($keys as $key) {
                $randomized_questions[$key] = $questions[$key];
            }
        
            $data['questions'] = $randomized_questions;
            $data['title'] = 'Examination';
            $data['layout'] = 'quiz/test.php';
            $this->load->view('member/index', $data);
        }

        // public function start_quiz() {
        //     // Check if approved
        //     $check = $this->db->get_where('quiz_payments', array('userid' => $this->session->user_id, 'status' => 'Approved'))->row();
        //     if(!$check) { redirect('member/quiz_center'); }
        
        //     $data['title'] = 'Examination';
        //     $data['layout'] = 'quiz/test.php';
        //     $this->load->view('member/index', $data);
        // }
        
        // public function process_quiz() {
        //     $answers = $this->input->post('q');
        //     $correct_count = 0;
            
        //     // Correct keys (Example for first few, you must map all 50)
        //      $solution = [
        //             // नाडी विज्ञान (1-20)
        //             1=>'B', 2=>'C', 3=>'A', 4=>'B', 5=>'C', 6=>'B', 7=>'A', 8=>'B', 9=>'C', 10=>'A',
        //             11=>'C', 12=>'B', 13=>'A', 14=>'A', 15=>'B', 16=>'B', 17=>'A', 18=>'A', 19=>'C', 20=>'A',
                    
        //             // Direct Selling (21-30)
        //             21=>'B', 22=>'B', 23=>'A', 24=>'A', 25=>'A', 26=>'A', 27=>'A', 28=>'A', 29=>'A', 30=>'A',
                    
        //             // Binary Plan (31-50)
        //             31=>'B', 32=>'A', 33=>'A', 34=>'A', 35=>'A', 36=>'A', 37=>'A', 38=>'D', 39=>'B', 40=>'A',
        //             41=>'A', 42=>'A', 43=>'B', 44=>'A', 45=>'A', 46=>'A', 47=>'A', 48=>'A', 49=>'A', 50=>'A'
        //         ]; 
                
        //     foreach($answers as $index => $user_ans) {
        //         if(isset($solution[$index]) && $solution[$index] == $user_ans) {
        //             $correct_count++;
        //         }
        //     }
        
        //     $status = ($correct_count >= 20) ? 'Pass' : 'Fail';
            
        //     $this->db->insert('quiz_results', [
        //         'userid' => $this->session->user_id,
        //         'score' => $correct_count,
        //         'status' => $status
        //     ]);
        
        //     if($status == 'Pass') {
        //         $this->session->set_flashdata('common_flash', '<div class="alert alert-success">Congratulations! You passed with '.$correct_count.' marks.</div>');
        //     } else {
        //         $this->session->set_flashdata('common_flash', '<div class="alert alert-danger">You failed. Correct answers: '.$correct_count.'. (Minimum 20 required)</div>');
        //     }
        //     redirect('member/quiz_center');
        // }
        public function process_quiz() {
        $answers = $this->input->post('q');
        $correct_count = 0;
        
        // Solution Key
       $solution = [
            1=>'B',
            2=>'C',
            3=>'A',
            4=>'B',
            5=>'C',
            6=>'B',
            7=>'A',
            8=>'B',
            9=>'C',
            10=>'A',
            11=>'C',
            12=>'B',
            13=>'A',
            14=>'A',
            15=>'B',
            16=>'B',
            17=>'A',
            18=>'A',
            19=>'C',
            20=>'A',
            21=>'B',
            22=>'C',
            23=>'B',
            24=>'B',
            25=>'A',
            26=>'B',
            27=>'B',
            28=>'B',
            29=>'B',
            30=>'B',
            31=>'C',
            32=>'A',
            33=>'A',
            34=>'B',
            35=>'A',
            36=>'A',
            37=>'A',
            38=>'A',
            39=>'A',
            40=>'A',
            41=>'A',
            42=>'A',
            43=>'A',
            44=>'A',
            45=>'A',
            46=>'A',
            47=>'B',
            48=>'A',
            49=>'A',
            50=>'A'
        ];
            
        foreach($answers as $index => $user_ans) {
            if(isset($solution[$index]) && $solution[$index] == $user_ans) {
                $correct_count++;
            }
        }
    
        $status ='Pass';
        
        // Save Result
        $this->db->insert('quiz_results', [
            'userid' => $this->session->user_id,
            'score'  => $correct_count,
            'status' => $status
        ]);
    
        if($status == 'Pass') {
            // --- START AUTOMATIC ACTIVATION ---
            $user_id = $this->session->user_id;
    
            // 1. Calculate status2 (Binary Leg Logic)
            $sp_o    = $this->db_model->select('sponsor', 'member', array('id' => $user_id));
            $count   = $this->db_model->count_all('member', array('sponsor' => $sp_o, 'topup >' => 0));
            $status2 = ($count >= 2) ? 3 : 2;
    
            // 2. Get the global active topup requirement from your setting
            $ak_global = $this->db_model->select_multi('*', 'global_setting', array('id' => 1));
            $activation_amount = $ak_global->active_topup;
    
            // 3. Update Member Table
            $member_update = array(
                'topup'           => $activation_amount,
                'activation_date' => date('Y-m-d'),
                'status2'         => $status2,
                'status'          => 'Active' 
            );
            $this->db->where('id', $user_id)->update('member', $member_update);
    
            // 4. Insert into Product Sale (This makes the dashboard "Inactive" turn into "Active")
            $sale_data = array(
                'product_id' => 0, // 0 indicates activated via Quiz
                'userid'     => $user_id,
                'cost'       => $activation_amount,
                'date'       => date('Y-m-d'),
                'order_by'   => 'Quiz System'
            );
            $this->db->insert('product_sale', $sale_data);
    
            // 5. Update Binary Legs
            $this->load->model('earning');
            $this->earning->update_legs();
            // --- END AUTOMATIC ACTIVATION ---
    
            $this->session->set_flashdata('common_flash', '<div class="alert alert-success">Congratulations! You passed and your account is now ACTIVE.</div>');
        } else {
            $this->session->set_flashdata('common_flash', '<div class="alert alert-danger">You failed. Correct answers: '.$correct_count.'. (Minimum 20 required to activate account)</div>');
        }
        
        redirect('member/quiz_center');
    }

    public function epin_activation_by_member() {
        
        // $trans_pass = $this->db_model->select('trans_password', 'member', array('id' => $this->session->user_id));
        // if($this->input->post('trans_password') != $trans_pass){
        //     $this->session->set_flashdata('common_flash', '<div class="alert alert-danger">Incorrect Transaction Password</div>');
        //     redirect('member/unused-epin');
        // }
        
        $userinfo    = $this->db_model->select_multi('max(orderid) as id', 'product_sale');
        $akid        = $userinfo->id + 1;
        $orderid     = $akid;
        $idd         =  $this->input->post('user_id');
        preg_match('/\d+/', $idd, $matches);
        $number      = $matches[0];
        $user_id     = $number;
        $sp_o        = $this->db_model->select('sponsor', 'member', array('id' => $user_id));
        $status2     = $this->db_model->count_all('member', array('sponsor' => $sp_o,'topup >' => 0));
        if($status2 == 2){
            $status2 = 3;
        }
        else{
            $status2 = 2;
        }
        #echo "";print_r($_POST);die();
        $prod_data   =  $this->db_model->select_multi('*', 'product', array('id' => $this->input->post('signup_package')));  
        $epin_value  = $this->db_model->select('amount', 'epin', array('epin'   => trim($this->input->post('epin')),'status' => 'Un-used',));
        $find_user   = $this->db_model->select('id', 'member', array('id' => $user_id));
        $al_active   = $this->db_model->select('signup_package', 'member', array('id' => $user_id));
        #$al_active   = $this->db_model->select('join_package', 'member', array('id' => $user_id));
        $epin        = $this->input->post('epin');

        // echo "e pin value ".$epin_value."<br>"."prod data dealer price ".$prod_data->dealer_price."<br>".
        //      "find user ".$find_user."<br>".
        //      "al active ".$al_active."<br>";die();

        if($find_user != NULL and $al_active == NULL or $al_active < 0){
           if($epin_value == $prod_data->dealer_price or $epin_value == 0) {
               if($epin_value != 0){
                   $prod_pv = $prod_data->pv;
               } else{
                   $prod_pv = 0;
               }    
               $mypv = $this->db_model->select('mypv', 'member', array('id' => $user_id));
               $data = array(
                   'topup'           => $prod_data->dealer_price,
                   'signup_package'  => $prod_data->id,
                   'epin'            => $epin,
                   'mypv'            => $mypv + $prod_pv,
                   'activation_date' => date('Y-m-d'),
                   'status2'         => $status2,
               );
               $this->db->where('id', $user_id);
               $this->db->update('member', $data);

               // chanage epin status
               $data = array(
                   'status'    => 'Used',
                   'used_by'   => $user_id,
                   'used_time' => date('Y-m-d'),
               );
               $this->db->where('epin', $epin);
               $this->db->update('epin', $data);

               $this->load->model('earning'); 
               if($epin_value != 0){
                $this->earning->reg_earning($user_id, $this->db_model->select('sponsor', 'member', array('id' => $user_id)), $prod_data->id);
               }
               /* First purchse earning on topup */
               $data = array(
                   'product_id' => $prod_data->id,
                   'userid'     => $user_id,
                   'cost'       => $prod_data->dealer_price,
                   'date'       => date('Y-m-d'),
                   'order_by'   => 'Member',
                   'orderid'    => $orderid,
                   'pv'         => $prod_data->pv,
                   'type'         => 'topup',
                   'epin_amount'         => $epin_value,
               );
               $this->db->insert('product_sale', $data);

               // Insert into product item table
               $data = array(
                   'product_id' => $prod_data->id,
                   'order_id'   => $orderid,
                   'cost'       => $prod_data->dealer_price,
               );
               $this->db->insert('product_item_sale', $data);
            // $this->earning->repurchase($orderid);
            if($this->db_model->count_all('member') <= 100){
                $this->earning->update_legs();
            }
            $this->session->set_flashdata('common_flash', '<div class="alert alert-success">Successfully activated your account.</div>');
            redirect(site_url('member/unused-epin'));
        }
        else{
            $this->session->set_flashdata('common_flash', '<div class="alert alert-danger">Epin and Package Amount Not Matched.</div>');
            redirect(site_url('member/unused-epin')); 
        }
        }
        elseif($al_active!=NULL or $al_active > 0){
            $this->session->set_flashdata('common_flash', '<div class="alert alert-danger"> Userid Already Activated.</div>');
            redirect(site_url('member/unused-epin'));
        }
        elseif($find_user == NULL){
            $this->session->set_flashdata('common_flash', '<div class="alert alert-danger">Invalid Userid.</div>');
            redirect(site_url('member/unused-epin'));
        }
        else{
            $this->session->set_flashdata('common_flash', '<div class="alert alert-danger">Something Went Wrong Please Try Again later.</div>');
            redirect(site_url('member/unused-epin'));
        }
    }
    
}

