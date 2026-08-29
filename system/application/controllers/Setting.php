<?php


defined('BASEPATH') OR exit('No direct script access allowed');

class Setting extends CI_Controller
{
    /**
     * Check Valid Login or display login page.
     */
    public function __construct()
    {
        parent::__construct();
        if ($this->login->check_session() == FALSE) {
            redirect(site_url('site/admin'));
        }
    }

    public function common_setting()
    {
        $this->config->load('email', TRUE);
        $this->form_validation->set_rules('pass', 'Password', 'trim|required');
        // $this->form_validation->set_rules('smtp_user', 'SMTP User', 'valid_email|required');
        
        if ($this->form_validation->run() == FALSE) {
            $data['title']      = 'Basic Setting';
            $data['breadcrumb'] = 'Basic Setting';
            $data['layout']     = 'setting/common_setting.php';
            $this->load->view('admin/index', $data);
        } else {
            // Handle logo upload
            if (trim($_FILES['logo']['name'] !== "")) {
                move_uploaded_file($_FILES['logo']['tmp_name'], FCPATH . 'uploads/logo.png');
            }
    
            // Handle favicon upload
            if (trim($_FILES['favicon']['name'] !== "")) {
                move_uploaded_file($_FILES['favicon']['tmp_name'], FCPATH . 'uploads/favicon.ico');
            }
    
            // Handle QR code upload
            if (trim($_FILES['qr_code']['name'] !== "")) {
                move_uploaded_file($_FILES['qr_code']['tmp_name'], FCPATH . 'uploads/qr_code.png');
            }
    
            // Handle business plan upload
            if (trim($_FILES['business_plan']['name'] !== "")) {
                move_uploaded_file($_FILES['business_plan']['tmp_name'], FCPATH . 'uploads/business_plan.pdf');
            }
    
            // $file = '<?php
            //             $config[\'protocol\']    = \'smtp\';
            //             $config[\'smtp_host\']   = \'' . $this->input->post('smtp_host') . '\';
            //             $config[\'smtp_user\']   = \'' . $this->input->post('smtp_user') . '\';
            //             $config[\'smtp_pass\']   = \'' . $this->input->post('smtp_pass') . '\';
            //             $config[\'smtp_port\']   = \'' . $this->input->post('smtp_port') . '\';
            //             $config[\'smtp_crypto\'] = \'ssl\';
            //             $config[\'mailtype\']    = \'html\';
            //             $config[\'wtrans\']      = \'' . $this->input->post('wtrans') . '\';
            //         ';
            // file_put_contents(APPPATH . 'config/email.php', $file);
    
            // Set success message and redirect
            $this->session->set_flashdata('common_flash', '<div class="alert alert-success">Settings Updated Successfully</div>');
            redirect('setting/common-setting');
        }
    }

    public function advance_setting()
    {
        $this->form_validation->set_rules('company_name', 'Company Name', 'trim|required');
        $this->form_validation->set_rules('leg', 'No of Legs', 'required');
        $this->form_validation->set_rules('currency', 'Currency', 'required');
        $this->form_validation->set_rules('dev_pass', 'Developer Password', 'trim|required');
        if ($this->form_validation->run() == FALSE) {
            $data['title']      = 'Advance Setting';
            $data['breadcrumb'] = 'Advance Setting';
            $data['layout']     = 'setting/advance_setting.php';
            $this->load->view('admin/index', $data);
        }
        else {
            if (trim($this->input->post('dev_pass')) !== trim(config_item('dev_pass'))) {
                $this->session->set_flashdata('common_flash', '<div class="alert alert-danger">Developer Password is Wrong</div>');
                redirect('setting/advance_setting');
            }
        $file = '<?php
        defined(\'BASEPATH\') OR exit(\'Can we play bubu together ?\');
        $config[\'company_name\'] = "' . $this->input->post('company_name') . '";
        $config[\'select_temp\'] = "' . $this->input->post('select_temp') . '";
        $config[\'web_phone\'] = "' . $this->input->post('web_phone') . '";
        $config[\'web_status\'] = "' . $this->input->post('web_status') . '";
        $config[\'web_email\'] = "' . $this->input->post('web_email') . '";
        $config[\'company_address\'] = "' . $this->input->post('company_address') . '";
        $config[\'ID_EXT\'] = \'' . $this->input->post('id_ext') . '\'; # ID Extension eg: DM1001
        $config[\'currency\'] = "' . $this->input->post('currency') . ' "; # Sitewide currency
        $config[\'iso_currency\'] = "' . $this->input->post('iso_currency') . ' "; # ISO Code of currency
        $config[\'leg\'] = "' . $this->input->post('leg') . '"; # 1, 2, 3, 4, 5(eg: for binary plan leg is 2)
        $config[\'show_leg_choose\'] = "' . $this->input->post('show_leg_choose') . '"; ## Whether to show placement ID box at registration
        $config[\'show_placement_id\'] = "' . $this->input->post('show_placement_id') . '"; ## Whether to show select position option or not
        $config[\'autopool_registration\'] = "' . $this->input->post('autopool_registration') . '";
        $config[\'show_join_product\'] = "' . $this->input->post('show_join_product') . '";
        $config[\'enable_epin\'] = "' . $this->input->post('enable_epin') . '";
        $config[\'enable_pg\'] = "' . $this->input->post('enable_pg') . '"; # Payment Gateway
        $config[\'free_registration\'] = "' . $this->input->post('free_registration') . '";
        $config[\'sms_on_join\'] = "' . $this->input->post('sms_on_join') . '";
        $config[\'top_id\'] = "1001";
        $config[\'prevent_join_product_entry\'] = "' . $this->input->post('prevent_join_product_entry') . '";
        $config[\'enable_gap_commission\'] = "' . $this->input->post('enable_gap_commission') . '";
        $config[\'sms_api\'] = "' . $this->input->post('sms_api') . '";
        ## Format: https://apiurl.com?no={{phone}}&msg={{msg}}&other_parameters.
        $config[\'disable_registration\'] = "' . $this->input->post('disable_registration') . '";
        $config[\'fix_income\'] = "' . $this->input->post('fix_income') . '";
        $config[\'task_based\'] = "' . $this->input->post('task_based') . '";
        $config[\'auto_payout\'] = "' . $this->input->post('auto_payout') . '";
        
        $config[\'account_number\'] = "' . $this->input->post('account_number') . '";
        $config[\'ifsc_Code\'] = "' . $this->input->post('ifsc_Code') . '";
        $config[\'bank_branch\'] = "' . $this->input->post('bank_branch') . '";
        $config[\'account_name\'] = "' . $this->input->post('account_name') . '";
        $config[\'bank_name\'] = "' . $this->input->post('bank_name') . '";
        
        $config[\'give_income_on_topup\'] = "' . $this->input->post('give_income_on_topup') . '";
        ####################### MODULE SETTING ############################## 
        $config[\'enable_topup\'] = "' . $this->input->post('enable_topup') . '";
        $config[\'enable_repurchase\'] = "' . $this->input->post('enable_repurchase') . '";
        $config[\'enable_coupon\'] = "' . $this->input->post('enable_coupon') . '";
        $config[\'enable_ad_incm\'] = "' . $this->input->post('enable_ad_incm') . '";
        $config[\'enable_survey\'] = "' . $this->input->post('enable_survey') . '";
        $config[\'enable_recharge\'] = "' . $this->input->post('enable_recharge') . '";
        $config[\'enable_reward\'] = "' . $this->input->post('enable_reward') . '";
        $config[\'enable_help_plan\'] = "' . $this->input->post('enable_help_plan') . '";
        $config[\'enable_product\'] = "' . $this->input->post('enable_product') . '";
        $config[\'member_theme\']=\''. $this->input->post('member_theme').'\';
        $config[\'enable_upgrade\']=\''. $this->input->post('enable_upgrade').'\';
        $config[\'investment_mode\'] = "' . $this->input->post('investment_mode') . '"; ## AUTO, EPIN, MANUAL
        $config[\'cron_minutes\'] = "' . $this->input->post('cron_minutes') . '"; 
        $config[\'cron_hours\'] = "' . $this->input->post('cron_hours') . '"; 
        $config[\'cron_day\'] = "' . $this->input->post('cron_day') . '"; 
        $config[\'cron_month\'] = "' . $this->input->post('cron_month') . '"; 
        $config[\'cron_weekday\'] = "' . $this->input->post('cron_weekday') . '"; 
        $config[\'enable_investment\'] = "' . $this->input->post('enable_investment') . '"; ## This will convert existing software to a investment plan software and will turn off many features.';
        $this->resetCron();
            if (file_put_contents(APPPATH . 'config/global.php', $file)) {
                $this->session->set_flashdata('common_flash', '<div class="alert alert-success">Settings Updated Successfully</div>');
                redirect('setting/main_screen');
            }

        }
    }
    
 public function resetCron() {
        $this->load->library('CronRunner');
        $cron = new CronRunner();
        //echo $cron->calculateNextRun(); exit;
        $query = $this->db->where('is_active', 1)->where('id', 1)->from('cron')->get();
        if ($query->num_rows() > 0) {

            foreach ($query->result() as $row) {
                $this->db->set('next_run_at', $cron->calculateNextRun())->where('id', $row->id)->update('cron');
                $this->db->set('last_run_at', 'now()', false)->where('id', $row->id)->update('cron');
            }
        } 
    }
 
    public function payout_setting()
    {
        $this->form_validation->set_rules('payout_tax', 'Payout Tax', 'trim|required');
        $this->form_validation->set_rules('min_withdraw', 'Minimum Withdraw Amount', 'greater_than[0]|required');
        if ($this->form_validation->run() == FALSE) {
            $data['title']      = 'Payout Setting';
            $data['breadcrumb'] = 'Payout Setting';
            $data['layout']     = 'setting/payout_setting.php';
            $this->load->view('admin/index', $data);
        } else {

            $file = '<?php
            defined(\'BASEPATH\') OR exit(\'Can we play bubu together ?\');

            $config[\'payout_tax\'] = "' . $this->input->post('payout_tax') . '";
             $config[\'payout_tax2\'] = "' . $this->input->post('payout_tax2') . '";
            $config[\'admin_charges\'] = "' . $this->input->post('admin_charges') . '";
            $config[\'user_withdraw\'] = "' . $this->input->post('user_withdraw') . '";
            $config[\'min_withdraw\'] = "' . $this->input->post('min_withdraw') . '"; 
            $config[\'wallet_type\'] = "'.$this->input->post('wallet_type').'";#This is for seperate wallet
            $config[\'payment_api\'] = "' . $this->input->post('payment_api') . '"; #What gateway to use for auto payment
            $config[\'gen_list\']="'.$this->input->post('gen_list').'";#enable generations
            $config[\'auto_debit\']="'.$this->input->post('auto_debit').'";#Auto debit
            $config[\'rank_bonus\']="'.$this->input->post('rank_bonus').'";
            $config[\'percentage\']="'.$this->input->post('percentage').'";
            '; 

            file_put_contents(APPPATH . 'config/payout.php', $file);
            $this->session->set_flashdata('common_flash', '<div class="alert alert-success">Settings Updated Successfully</div>');
            redirect('setting/main_screen');

        }
    }  

      public function plan_setting()
    {
        $this->form_validation->set_rules('plantype', 'Plan Setting', 'trim|required');
        
        if ($this->form_validation->run() == FALSE) {
            $data['title']      = 'Plan Setting';
            $data['breadcrumb'] = 'Plan Setting';
            $data['layout']     = 'setting/plan_setting.php';
            $this->load->view('admin/index', $data);
        } else { 

            $file = '<?php
            defined(\'BASEPATH\') OR exit(\'Can we play bubu together ?\');

            $config[\'plantype\'] = "' . $this->input->post('plantype') . '";
           
            '; 

            file_put_contents(APPPATH . 'config/plan.php', $file);
            $this->session->set_flashdata('common_flash', '<div class="alert alert-success">Settings Updated Successfully</div>');
            redirect('setting/main_screen');

        }
    }


   public function reward_setting()
    {
        $this->load->model('plan_model');
        $this->form_validation->set_rules('reward_name', 'Reward Name', 'trim|required');
        if ($this->form_validation->run() == FALSE) {
            $this->db->select('id, reward_name, reward_duration, achievers,reward_amt');
            $data['result']     = $this->db->get('reward_setting')->result();
            $data['leg']        = $this->plan_model->create_leg();
            $data['title']      = 'Reward Setting';
            $data['breadcrumb'] = 'Reward Setting';
            $data['layout']     = 'setting/reward_setting.php';
            $this->load->view('admin/index', $data);
        } else {
            $reward_name     = $this->input->post('reward_name');
            $reward_duration = $this->input->post('reward_duration');
            $reward_amt = $this->input->post('reward_amt');
            $no_of_sponsor = $this->input->post('no_of_sponsor');
            $a = $this->input->post('A') ? $this->input->post('A') : 0;
            $b = $this->input->post('B') ? $this->input->post('B') : 0;
            $c = $this->input->post('C') ? $this->input->post('C') : 0;
            $d = $this->input->post('D') ? $this->input->post('D') : 0;
            $e = $this->input->post('E') ? $this->input->post('E') : 0;
 
            $array = array(
                'reward_name'     => $reward_name,
                'reward_duration' => $reward_duration,  
                'A'               => $a,
                'B'               => $b,
                'C'               => $c,
                'D'               => $d,
                'E'               => $e,
                'reward_amt'      => $reward_amt,
                'no_of_sponsor'   => $no_of_sponsor,
            );
            $this->db->insert('reward_setting', $array);

            $this->session->set_flashdata('common_flash', '<div class="alert alert-success">Reward Settings Saved Successfully</div>');
            redirect('setting/reward-setting');

        }
    }

    public function remove_reward($id)
    { 
        $this->db->where('id', $id);
        $this->db->delete('reward_setting');
        $this->session->set_flashdata('common_flash', '<div class="alert alert-success">Reward Deleted Successfully</div>');
        redirect('setting/reward-setting');
    }

   public function edit_reward($id)
    { 
        $this->load->model('plan_model');
        $this->form_validation->set_rules('reward_name', 'Reward Name', 'trim|required');
        if ($this->form_validation->run() == FALSE) {
            $data['result']     = $this->db_model->select_multi('id, reward_name, reward_duration, A, B, C, D, E,no_of_sponsor,reward_amt', 'reward_setting', array('id' => $id));
            $data['leg']        = $this->plan_model->create_leg();
            $data['title']      = 'Edit Reward';
            $data['breadcrumb'] = 'Edit Reward';
            $data['layout']     = 'setting/edit_reward.php';
            $this->load->view('admin/index', $data);
        } else {
            $reward_name     = $this->input->post('reward_name');
            $reward_duration = $this->input->post('reward_duration');
            $reward_amt = $this->input->post('reward_amt');
            $no_of_sponsor = $this->input->post('no_of_sponsor');

            $a = $this->input->post('A') ? $this->input->post('A') : 0; 
            $b = $this->input->post('B') ? $this->input->post('B') : 0;
            $c = $this->input->post('C') ? $this->input->post('C') : 0;
            $d = $this->input->post('D') ? $this->inputt->post('D') : 0;
            $e = $this->input->post('E') ? $this->input->post('E') : 0;

            $array = array(
                'reward_name'     => $reward_name,
                'reward_duration' => $reward_duration,
                'A'               => $a,
                'B'               => $b,
                'C'               => $c,
                'D'               => $d,
                'E'               => $e,
                'reward_amt'      => $reward_amt,
                'no_of_sponsor'   => $no_of_sponsor,
            );

            $this->db->where('id', $this->input->post('id'));
            $this->db->update('reward_setting', $array);

            $this->session->set_flashdata('common_flash', '<div class="alert alert-success">Reward Updated Successfully</div>');
            redirect('setting/reward-setting');
        }
    }
    public function cms()
    {
        redirect('../wp-admin');
    }

    public function welcome_letter()
    {
        $this->form_validation->set_rules('welcome_letter', 'Welcome Letter Content', 'required|trim');
        if ($this->form_validation->run() == FALSE) {
            $data['title']      = 'Edit Welcome Letter';
            $data['breadcrumb'] = 'Edit Welcome Letter';
            $data['layout']     = 'setting/edit_welcomeletter.php';
            $this->load->view('admin/index', $data);
        } else {
            file_put_contents(FCPATH . '/uploads/welcome_letter.txt', $_POST['welcome_letter']);
            $this->session->set_flashdata('common_flash', '<div class="alert alert-success">Welcome Letter Updated Successfully</div>');
            redirect('setting/main_screen');
        }
    }

    public function payment_gateway()
    {
        $this->form_validation->set_rules('paypal_email', 'All', 'trim');
        if ($this->form_validation->run() == FALSE) {
            $this->load->config('pg');
            $data['title']      = 'Manage Payment Gateways';
            $data['breadcrumb'] = 'Manage Payment Gateways';
            $data['layout']     = 'setting/pg_setting.php';
            $this->load->view('admin/index', $data);
        } else {
            $file = '<?php
defined(\'BASEPATH\') OR exit(\'Can we play bubu together ?\');

$config[\'enable_paypal\'] = "' . $this->input->post('enable_paypal') . '";
$config[\'paypal_email\'] = "' . $this->input->post('paypal_email') . '";
$config[\'paypal_currency\'] = "' . $this->input->post('paypal_currency') . '";

$config[\'enable_instamojo\'] = "' . $this->input->post('enable_instamojo') . '";
$config[\'instamojo_api_key\'] = "' . $this->input->post('instamojo_api_key') . '";
$config[\'instamojo_auth\'] = "' . $this->input->post('instamojo_auth') . '";
$config[\'instamojo_salt\'] = "' . $this->input->post('instamojo_salt') . '";

$config[\'enable_payumoney\'] = "' . $this->input->post('enable_payumoney') . '";
$config[\'payumoney_key\'] = "' . $this->input->post('payumoney_key') . '";
$config[\'payumoney_salt\'] = "' . $this->input->post('payumoney_salt') . '";

$config[\'enable_paytm\'] = "' . $this->input->post('enable_paytm') . '";
$config[\'PAYTM_MERCHANT_KEY\'] = "' . $this->input->post('PAYTM_MERCHANT_KEY') . '";
$config[\'PAYTM_MERCHANT_MID\'] = "' . $this->input->post('PAYTM_MERCHANT_MID') . '";

$config[\'RAZOR_KEY_ID\'] = "' . $this->input->post('RAZOR_KEY_ID') . '";
$config[\'RAZOR_KEY_SECRET\'] = "' . $this->input->post('RAZOR_KEY_SECRET') . '";


$config[\'enable_block_io\'] = "' . $this->input->post('enable_block_io') . '";
$config[\'api_key\'] = "' . $this->input->post('api_key') . '";
$config[\'secret_pin\'] = "' . $this->input->post('secret_pin') . '";
';
            file_put_contents(APPPATH . 'config/pg.php', $file);
            $this->session->set_flashdata('common_flash', '<div class="alert alert-success">Payment Gateway Updated Successfully</div>');
            redirect('setting/main_screen');
        }
    }

    public function export()
    { 
        $data['title']      = 'Export & Import Tools';
        $data['breadcrumb'] = 'Export & Import Tools';
        $data['layout']     = 'setting/export_import.php';
        $this->load->view('admin/index', $data);
    }

    public function export_db()
    {
        $data['title']      = 'Backup Database';
        $data['breadcrumb'] = 'Export & Backup Database';
        $data['layout']     = 'setting/export_to_sql.php';
        $this->load->view('admin/index', $data);
    }

    public function import_db()
    {
        if ($_FILES['file']['name'] == "") {
            $data['title']      = 'Import Database';
            $data['breadcrumb'] = 'Import & Restore Database';
            $data['layout']     = 'setting/import_from_sql.php';
            $this->load->view('admin/index', $data);
        } else {
            $templine = '';
            move_uploaded_file($_FILES['file']['tmp_name'], FCPATH . 'sql_backup.txt');
            $lines = file(FCPATH . 'sql_backup.txt');
            foreach ($lines as $line) {
                if (substr($line, 0, 2) == '--' || $line == '') continue;
                $templine .= $line;
                if (substr(trim($line), -1, 1) == ';') {
                    $this->db->query($templine);
                    $templine = '';
                }
            }
            $this->session->set_flashdata('common_flash', '<div class="alert alert-success">Database Imported Successfully.</div>');
            redirect('setting/import_db');
        }
    } 

    public function export_final()
    {
        $this->load->dbutil();
        $backup = $this->dbutil->backup();
        $this->load->helper('download');
        force_download(FCPATH . 'sql_backup.gz', $backup);
    }

    public function excel()
    {
        $this->form_validation->set_rules('table', 'Table', 'trim|required');
        if ($this->form_validation->run() == TRUE) {
            $this->load->dbutil();
            $query = $this->db->query("SELECT * FROM " . $_POST['table']);
            $data  = $this->dbutil->csv_from_result($query);
            $this->load->helper('download');
            force_download(FCPATH . 'sql_backup.csv', $data);
        } else {
            $data['title']      = 'Export to Excel';
            $data['breadcrumb'] = 'Export Database to Excel';
            $data['layout']     = 'setting/export_to_excel.php';
            $this->load->view('admin/index', $data);
        }
    }

    public function marketing()
    {
        $data['title']      = 'Marketing Toools';
        $data['breadcrumb'] = 'Marketing Toools';
        $data['layout']     = 'setting/marketing_tools.php';
        $this->load->view('admin/index', $data);
    }

    public function sms_marketing()
    {
        $this->form_validation->set_rules('msg', 'Message Content', 'trim|required');
        if ($this->form_validation->run() == FALSE) {
            $data['title']      = 'SMS Marketing';
            $data['breadcrumb'] = 'SMS Marketing';
            $data['layout']     = 'setting/sms_marketing.php';
            $this->load->view('admin/index', $data);
        } else {
            $phoneNO = $this->input->post('phone_no');
            if ($this->input->post('all') == "1") {
                $phoneNO = '';
                $this->db->select('phone');
                $res = $this->db->get('member')->result();
                foreach ($res as $phone) {
                    $phoneNO = $phoneNO . "," . $phone->phone;
                }
                $phoneNO = substr($phoneNO, 1);
            }
            $this->common_model->sms($phoneNO, $this->input->post('msg'));

            $this->session->set_flashdata('common_flash', '<div class="alert alert-success">SMS Sent Successfully.</div>');
            redirect('setting/sms_marketing');
        }
    }

    public function email_marketing()
    {
        $this->form_validation->set_rules('subject', 'Subject', 'trim|required');
        $this->form_validation->set_rules('msg', 'Message Content', 'trim|required');
        if ($this->form_validation->run() == FALSE) {
            $data['title']      = 'Email Marketing';
            $data['breadcrumb'] = 'Email Marketing';
            $data['layout']     = 'setting/email_marketing.php';
            $this->load->view('admin/index', $data);
        } else {

            $email_id = $this->input->post('email_id');
            if ($this->input->post('all') == "1") {
                $email_id = '';
                $this->db->select('email');
                $res = $this->db->get('member')->result();
                foreach ($res as $email) {
                    $email_id = $email_id . "," . $email->email;
                }
                $email_id = substr($email_id, 1);
            }

            $this->common_model->mail($email_id, $this->input->post('subject'), $this->input->post('msg'));

            $this->session->set_flashdata('common_flash', '<div class="alert alert-success">e-Mail Sent Successfully.</div>');
            redirect('setting/email_marketing');
        } 
    }

    public function main_screen()
    {
        $data['title']      = 'System Setting';
        $data['breadcrumb'] = 'System Setting';
        $data['layout']     = 'setting/main_screen.php';
        $this->load->view('admin/index', $data);
    }

    public function clear_database() 
    {
        $this->form_validation->set_rules('password', 'Current Password', 'trim|required');
        $this->form_validation->set_rules('agree', 'Agree Box', 'trim|required');
        if ($this->form_validation->run() == FALSE) {
            $data['title']      = 'Clear/Reset Database';
            $data['breadcrumb'] = 'Clear/Reset Database';
            $data['layout']     = 'setting/clear_database.php';
            $this->load->view('admin/index', $data);
        } else {
            $original_pass = $this->db_model->select('password', 'admin', array('id' => $this->session->admin_id));
            if (trim($original_pass) == "" || password_verify($this->input->post('password'), $original_pass) == FALSE) {
                $this->session->set_flashdata('common_flash', '<div class="alert alert-danger">Entered Password is wrong.</div>');
                redirect('setting/clear_database');
            }
            ####### Will Clear Database Values Here ###################

            $this->db->truncate('admin_expense');
            $this->db->truncate('ads');
            $this->db->truncate('ad_user');
            $this->db->truncate('coupon');
            $this->db->truncate('coupon_categories');
            $this->db->truncate('earning');
            $this->db->truncate('epin');
            $this->db->truncate('flexible_income');
            $this->db->truncate('level_wise_income');
            $this->db->truncate('franchisee_stock');
            $this->db->truncate('franchisee_earning');
            $this->db->truncate('fran_req');
            $this->db->truncate('fran_withdraw_request');
            $this->db->truncate('frnch_transaction');
            $this->db->truncate('laps_earning');
            $this->db->truncate('wallet_transaction');
            $this->db->truncate('product_item_sale');
            // $this->db->truncate('product');
            $this->db->truncate('product_sale');
            $this->db->truncate('purchase');
            $this->db->truncate('recharge_entry');
            $this->db->truncate('rewards');
            // $this->db->truncate('rank_system');
            $this->db->truncate('reward_setting');
            $this->db->truncate('salary');
            $this->db->truncate('staffs');
            $this->db->truncate('staff_designation');
            $this->db->truncate('pair_cuts');
            $this->db->truncate('survey');
            $this->db->truncate('survey_user');
            $this->db->truncate('tax_report');
            $this->db->truncate('ticket');
            $this->db->truncate('ticket_reply');
            $this->db->truncate('transaction');
            $this->db->truncate('transfer_balance_records');
            $this->db->truncate('withdraw_request');
            $this->db->truncate('gap_commission_setting');
            $this->db->truncate('investment_pack');
            $this->db->truncate('donations');
            $this->db->truncate('donation_package');
            $this->db->truncate('investments');
            $this->db->truncate('level');
            $this->db->truncate('deposite');
            $this->db->truncate('news');
            $this->db->truncate('upgrad_level');
            $this->db->truncate('pool_one');
            $this->db->truncate('pool_two');
            $this->db->truncate('pool_three'); 
            $this->db->truncate('pool_four'); 
            
            $this->db->truncate('autopool_1');   
            $this->db->truncate('autopool_2');   
            $this->db->truncate('autopool_3');   
            $this->db->truncate('autopool_4');   
            $this->db->truncate('autopool_5');   
            $this->db->truncate('autopool_6');   
            $this->db->truncate('autopool_7');   
            $this->db->truncate('autopool_8');   
            $this->db->truncate('autopool_9'); 
            $this->db->truncate('earning_deduct'); 
            $this->db->truncate('epin_request'); 
            $this->db->truncate('power_history'); 
            
            $this->db->empty_table('franchisee');
            $this->db->empty_table('invoice');
            $this->db->empty_table('franchisee_stock_sale_bill');
            $this->db->empty_table('activity_log');
            $this->db->delete('member', array('id !=' => config_item('top_id')));
            $this->db->insert('level', array('userid' => config_item('top_id')));
            $this->db->delete('member_profile', array('userid !=' => config_item('top_id')));
            $this->db->delete('other_wallet', array('userid !=' => config_item('top_id')));
            $this->db->delete('wallet', array('userid !=' => config_item('top_id')));
            $this->db->delete('product_wallet', array('userid !=' => config_item('top_id')));
            // $this->db->delete('product_categories', array('cat_name !=' => 'Default Category'));

            $array = array(
                'direct_income' => 0,
                'level_income'  => 0,
                'binary_income' => 0,
            );
            $this->db->update('fix_income', $array);

            $array = array(
                'balance' => 0,
            );
            $this->db->update('wallet', $array);
            $this->db->update('other_wallet', $array);

            $array = array(
                'A'                    => 0,
                'B'                    => 0,
                'C'                    => 0,
                'D'                    => 0,
                'E'                    => 0,
                'total_a'              => 0,
                'total_b'              => 0,
                'total_c'              => 0,
                'paid_a'               => 0,
                'paid_b'               => 0,
                'paid_c'               => 0,
                'paid_d'               => 0,
                'paid_e'               => 0,
                'rank'                 => 'Member',
                'mypv'                 => 0,
                'status2'              => 2,
                'total_a_pv'           => 0,
                'total_b_pv'           => 0,
                'paid_a_pv'            => 0,
                'paid_b_pv'            => 0,
                'total_pairs'          => 0,
                'paid_a_matching_incm' => 0,
                'paid_b_matching_incm' => 0,
                'total_a_matching_incm'=> 0,
                'total_b_matching_incm'=> 0,
                'my_business'          => 0,
                'total_a_investment'   => 0,
                'total_b_investment'   => 0,
                'signup_package'       => null,
                'topup'                => 0,
            );
            $this->db->update('member', $array);

            ############### END CLEARNING #############################
            $array = array(
                'userid' => $this->session->admin_id,
                'log'    => 'Database Cleared',
                'ip'     => $this->input->ip_address(),
                'time'   => date('Y-m-d H:i:s'),
            );

            $dir         = FCPATH . "uploads";
            // $leave_files = array('default.jpg', 'favicon.ico', 'logo.png', 'load.gif', 'welcome_letter.txt','business_plan.pdf');

            // foreach (glob("$dir/*") as $file) {
            //     if (!in_array(basename($file), $leave_files))
            //         unlink($file);
            // }

            $this->db->insert('logs', $array);
            $this->session->set_flashdata('common_flash', '<div class="alert alert-success">Database Cleared Successfully.</div>');
            redirect('setting/clear_database');
        }
    }

    public function rank_setting()
    {
        $this->load->model('plan_model');
        $this->form_validation->set_rules('rank_name', 'Rank Name', 'trim|required');
        if ($this->form_validation->run() == FALSE) {
            $this->db->select('*');
            $data['result']     = $this->db->get('rank_system')->result();
            $data['leg']        = $this->plan_model->create_leg();
            $data['title']      = 'Member Rank Setting';
            $data['breadcrumb'] = 'Member Rank Setting';
            $data['layout']     = 'setting/rank_setting.php';
            $this->load->view('admin/index', $data);
        } 
        else {
            $rank_name     = $this->input->post('rank_name');
            $amount        = $this->input->post('amount');
            $rank_duration = $this->input->post('rank_duration');
            $based_on      = $this->input->post('based_on');
            $a = $this->input->post('A') ? $this->input->post('A') : 0;
            $b = $this->input->post('B') ? $this->input->post('B') : 0;
            $c = $this->input->post('C') ? $this->input->post('C') : 0;
            $d = $this->input->post('D') ? $this->input->post('D') : 0;
            $e = $this->input->post('E') ? $this->input->post('E') : 0;
            $total_member  = $this->input->post('total_member') ? $this->input->post('total_member') :0;

            $array = array(
                'rank_name'          => $rank_name,
                'rank_duration '     => $rank_duration,
                'amount'             => $amount,
                'based_on'           => $based_on,
                'A'                  => $a,
                'B'                  => $b,
                'C'                  => $c,
                'D'                  => $d,
                'E'                  => $e,
                'total_member'       => $total_member,
            );
            $this->db->insert('rank_system', $array);
            $this->session->set_flashdata('common_flash', '<div class="alert alert-success">Member Rank Settings Saved Successfully</div>');
            redirect('setting/rank-setting');
        }
    }

    public function remove_rank($id)
    {
        $this->db->where('id', $id);
        $this->db->delete('rank_system');
        $this->session->set_flashdata('common_flash', '<div class="alert alert-success">Rank Setting has been Deleted Successfully</div>');
        redirect('setting/rank-setting');
    }


      public function edit_rank($id)
    {
        $this->load->model('plan_model');
        $this->form_validation->set_rules('rank_name', 'Rank Name', 'trim|required');
        if ($this->form_validation->run() == FALSE) {
            $data['result']     = $this->db_model->select_multi('*', 'rank_system', array('id' => $id));
            $data['leg']        = $this->plan_model->create_leg();
            $data['title']      = 'Update Member Rank Setting';
            $data['breadcrumb'] = 'Update Member Rank Setting';
            $data['layout']     = 'setting/edit_rank.php';
            $this->load->view('admin/index', $data);
        } 
        else {
            $rank_name     = $this->input->post('rank_name');
            $amount        = $this->input->post('amount');
            $based_on      = $this->input->post('based_on');
            $rank_duration = $this->input->post('rank_duration');
            $a = $this->input->post('A') ? $this->input->post('A') : 0;
            $b = $this->input->post('B') ? $this->input->post('B') : 0;
            $c = $this->input->post('C') ? $this->input->post('C') : 0;
            $d = $this->input->post('D') ? $this->input->post('D') : 0;
            $e = $this->input->post('E') ? $this->input->post('E') : 0;
            $total_member  = $this->input->post('total_member')?$total_member  = $this->input->post('total_member'):0;

            $array = array(
                'rank_name'          => $rank_name,
                'amount'             => $amount,
                'rank_duration '     => $rank_duration,
                'based_on'           => $based_on,
                'A'                  => $a,
                'B'                  => $b,
                'C'                  => $c,
                'D'                  => $d,
                'E'                  => $e,
                'total_member'       => $total_member,
            );
            $this->db->where('id', $this->input->post('id'));
            $this->db->update('rank_system', $array);

            $this->session->set_flashdata('common_flash', '<div class="alert alert-success">Rank Settings Updated Successfully</div>');
            redirect('setting/rank-setting');

        }
    }
    function is_unique($str,$field){
       
        $tablefield = explode(".",$field);

        $table = $tablefield[0];
        $field = $tablefield[1];
        
        $textMsg = $tablefield[2];
        // $get_row = $this->users_model->getRow(".{$table}","count(*) as total",array(),array("{$field}"=>trim($str),"deleted"=>1));

        $this->db->select("count(*) as total");
        $this->db->from($table);
        $this->db->where($field, trim($str));
        $query  = $this->db->get();
        $get_row = $query->row();
            //echo $this->db->last_query();exit;
            //echo $get_row->total; exit;
        if($get_row->total == 0){
            return true;
        }else{
        $this->form_validation->set_message('is_unique',"{$textMsg} {$str} is already in use");
            return false;
        }
    }

    function is_unique_update($str,$field){
       
        $tablefield = explode(".",$field);

        $table = $tablefield[0];
        $field = $tablefield[1];
        
        $textMsg = $tablefield[2];
        $id = $tablefield[3];
        // $get_row = $this->users_model->getRow(".{$table}","count(*) as total",array(),array("{$field}"=>trim($str),"deleted"=>1));

        $this->db->select("count(*) as total");
        $this->db->from($table);
        $this->db->where($field, trim($str));
        $this->db->where(array('id !=' => $id));
        
        
        $query  = $this->db->get();
        $get_row = $query->row();
            //echo $this->db->last_query();exit;
            //echo $get_row->total; exit;
        if($get_row->total == 0){
            return true;
        }else{
        $this->form_validation->set_message('is_unique_update',"{$textMsg} {$str} is already in use");
            return false;
        }
    }        
    
    
     public function earning_setting()
    {
        
        $this->load->model('earningsetting_model');
        $this->form_validation->set_rules('levlno', 'Level', 'trim|required|callback_is_unique[earning_settings.levlno.Level No]');
        $this->form_validation->set_rules('target_amount', 'Target Amount', 'trim|numeric|max_length[10]|required');
        $this->form_validation->set_rules('payout_cycle', 'Payout Cycle', 'trim|required');
        $this->form_validation->set_rules('no_of_times', 'No.of Times', 'trim|required');
        $this->form_validation->set_rules('earning_type', 'Earning Type', 'trim|required');
        
        if ($this->form_validation->run() == FALSE) {
            
            $this->db->select('*');
            $data['result']     = $this->earningsetting_model->getAllEarningSettings(); //$this->db->get('earning_settings')->result();
            $data['MAX_LEVEL']        = 10;
           // $data['leg']        = $this->plan_model->create_leg();
            $data['title']      = 'Member Earning Setting';
            $data['breadcrumb'] = 'Member Earning Setting';
            $data['loadCSS']  = "alert.css";
            $data['loadJS']     = "validate.js,earning_settings.js";
            $data['layout']     = 'setting/earning_setting.php';
            $this->load->view('admin/index', $data);
        } else {
            $levlno     = $this->input->post('levlno');
            $target_amount = $this->input->post('target_amount');
            $payout_cycle      = $this->input->post('payout_cycle');
            $no_of_times      = $this->input->post('no_of_times');
            $earning_type      = $this->input->post('earning_type');
            $array = array(
                'levlno'     => $levlno,
                'target_amount' => $target_amount,
                'payout_cycle'      => $payout_cycle,
                'no_of_times'             => $no_of_times,
                'earning_type'             => $earning_type
               
            );
            //$this->db->insert('earning_settings', $array);
            $this->earningsetting_model->insertEarningSettings($array);

            $this->session->set_flashdata('common_flash', '<div class="alert alert-success">Member earning Settings Saved Successfully</div>');
            redirect('setting/earning-setting');

        }
    }
    
     public function remove_earning($id)
    {
        $this->load->model('earningsetting_model');
        // $this->db->where('id', $id);
        //$this->db->delete('earning_settings');
        $where['id'] = $id;
        if ( $this->earningsetting_model->delete( $where ) ) {
            $this->session->set_flashdata('common_flash', '<div class="alert alert-success">Member earning Settings(#'.$id.') has been Deleted Successfully</div>');
        }
        else {
            $this->session->set_flashdata('common_flash', '<div class="alert alert-danger">Deletion Failed!</div>');
        }     
        redirect('setting/earning-setting');
    }

     public function edit_earning($id)
    {
        
        $this->load->model('earningsetting_model');
        //$this->load->model('plan_model');|callback_is_unique[earning_settings.levlno.Level No]
        $this->form_validation->set_rules('levlno', 'Level', "trim|required|callback_is_unique_update[earning_settings.levlno.Level No.$id]");
        $this->form_validation->set_rules('target_amount', 'Target Amount', 'trim|numeric|max_length[10]|required');
        $this->form_validation->set_rules('payout_cycle', 'Payout Cycle', 'trim|required');
        $this->form_validation->set_rules('no_of_times', 'No.of Times', 'trim|required');
        $this->form_validation->set_rules('earning_type', 'Earning Type', 'trim|required');
        if ($this->form_validation->run() == FALSE) {
            
            $data['result']     = $this->earningsetting_model->getSingleRecord($id);//$this->db_model->select_multi('*', 'earning_settings', array('id' => $id));
            $data['MAX_LEVEL']        = 10;
            $data['title']      = 'Member Earning Setting';
            $data['breadcrumb'] = 'Member Earning Setting';
            $data['loadCSS']  = "alert.css";
            $data['loadJS']     = "validate.js,earning_settings.js";
            $data['layout']     = 'setting/edit_earning.php';
            $this->load->view('admin/index', $data);
        } else {
            $levlno     = $this->input->post('levlno');
            $target_amount = $this->input->post('target_amount');
            $payout_cycle      = $this->input->post('payout_cycle');
            $no_of_times      = $this->input->post('no_of_times');
            $earning_type      = $this->input->post('earning_type');
            $array = array(
                'levlno'     => $levlno,
                'target_amount' => $target_amount,
                'payout_cycle'      => $payout_cycle,
                'no_of_times'             => $no_of_times,
                'earning_type'             => $earning_type
               
            );
            //print_r($_POST);
           // exit;
            
            //$this->db->where('id', $this->input->post('id'));
           // $this->db->update('earning_settings', $array);
           $id = $this->input->post('id');
            if ( $this->earningsetting_model->update( $array, array( 'id' => intval( $id ) ) ) ) {

                $this->session->set_flashdata('common_flash', '<div class="alert alert-success">Member Earning Settings Updated Successfully</div>');
            }
            redirect('setting/earning-setting');

        }
    }


}