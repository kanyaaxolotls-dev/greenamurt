<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Report extends CI_Controller
{
    /**
     * Income Section for Admin Only
     */
    public function __construct()
    {
        parent::__construct();
        if ($this->login->check_session() == FALSE && $this->login->check_member() == FALSE) {
            redirect(site_url('site/login'));
        }
       
        $this->load->library('pagination');
    }

   

    public function earning_roi()
    {
       /* $status = $this->input->post('status');
        $s_payment_date  = $this->input->post('s_payment_date');
        $e_payment_date  = $this->input->post('e_payment_date');
        if (trim($status) == ""):
            $data['title']      = 'Earning Report';
            $data['breadcrumb'] = 'Earning Report';
			//$data['loadCSS']  = "alert.css";
			$data['loadJS']     = "datatables/jquery.dataTables.js,datatables/dataTables.bootstrap4.js,earning_roi_report.js";
            $data['layout']     = 'report/earning_roi_report.php';
            $this->load->view('member/index', $data);

        else:
            redirect(site_url('report/earning_roi/' . $status . '/' . $s_payment_date . '/' . $e_payment_date));
        endif;*/
		$data['title']      = 'Earning Report';
		$data['breadcrumb'] = 'Earning Report';
		//$data['loadCSS']  = "alert.css";
		$data['loadJS']     = "datatables/jquery.dataTables.js,datatables/dataTables.bootstrap4.js,earning_roi_report.js";
		$data['layout']     = 'report/earning_roi_report.php';
		$this->load->view('member/index', $data);
    }
 

    public function getallearningroi(){        
        $this->load->model('report_model');
		$sort = $_REQUEST['order'][0]['dir'];
		$limit = $_REQUEST['length'];
        $offset = $_REQUEST['start'];
        $draw = $_REQUEST["draw"];
        $conditions = array();
        $conditions['status'] = $_REQUEST["status"];
        $conditions['s_payment_date'] = $_REQUEST["s_payment_date"];
        $conditions['e_payment_date'] = $_REQUEST["e_payment_date"];       
        
      
		//print_r($conditions); die;
        
        $data_all = $this->report_model->get_earning_roi($offset,$limit,$conditions,$sort);
		//echo $this->db->last_query();exit;
		//echo "<pre>";print_r($data_all);exit;
        $data = $data_all['filtereddata'];
        $recordsTotal = count($data_all['totaldata']);
      
        $datafinal = array();
        if(!empty($data)){
            foreach($data as $key=>$row){
				$datafinal[$key]['userid'] =  config_item('ID_EXT') . $row->userid;
                $datafinal[$key]['income_type'] = $row->income_type;
                $datafinal[$key]['amount'] = $row->amount;
                $datafinal[$key]['roi'] = $row->roi;
                $datafinal[$key]['roi_frequency'] = $row->roi_frequency;
				$datafinal[$key]['roi_limit'] = $row->roi_limit;
				$datafinal[$key]['payment_date'] = $row->payment_date;
				$datafinal[$key]['status'] = $row->status;
               
            }
        }
       
        $response = array(
            "draw" => intval($draw),
            "recordsTotal" => $recordsTotal,
            "recordsFiltered" => $recordsTotal,
            "data" => $datafinal
        );
        echo json_encode($response);
       
    }
	
	
	
	
	 public function earning_roi_admin()
    {

		$data['title']      = 'Earning Report (Admin)';
		$data['breadcrumb'] = 'Earning Report';
		$data['loadJS']     = "datatables/jquery.dataTables.js,datatables/dataTables.bootstrap4.js,earning_roi_report_admin.js";
		$data['layout']     = 'report/earning_roi_report.php';
		$this->load->view('admin/index', $data);

    }
 

    public function getallearningroiadmin(){        
        $this->load->model('report_model');
		$sort = $_REQUEST['order'][0]['dir'];
		$limit = $_REQUEST['length'];
        $offset = $_REQUEST['start'];
        $draw = $_REQUEST["draw"];
        $conditions = array();
        $conditions['status'] = $_REQUEST["status"];
        $conditions['s_payment_date'] = $_REQUEST["s_payment_date"];
        $conditions['e_payment_date'] = $_REQUEST["e_payment_date"];       
        
      
		//print_r($conditions); die;
        
        $data_all = $this->report_model->get_earning_roi_admin($offset,$limit,$conditions,$sort);
		//echo $this->db->last_query();exit;
		//echo "<pre>";print_r($data_all);exit;
        $data = $data_all['filtereddata'];
        $recordsTotal = count($data_all['totaldata']);
      
        $datafinal = array();
        if(!empty($data)){
            foreach($data as $key=>$row){
				$datafinal[$key]['userid'] =  config_item('ID_EXT') . $row->userid;
                $datafinal[$key]['income_type'] = $row->income_type;
                $datafinal[$key]['amount'] = $row->amount;
                $datafinal[$key]['roi'] = $row->roi;
                $datafinal[$key]['roi_frequency'] = $row->roi_frequency;
				$datafinal[$key]['roi_limit'] = $row->roi_limit;
				$datafinal[$key]['payment_date'] = $row->payment_date;
				$datafinal[$key]['status'] = $row->status;
               
            }
        }
       
        $response = array(
            "draw" => intval($draw),
            "recordsTotal" => $recordsTotal,
            "recordsFiltered" => $recordsTotal,
            "data" => $datafinal
        );
        echo json_encode($response);
       
    }
   
   
   
   
     public function daily_roi()
    {

		$data['title']      = 'Earning Report';
		$data['breadcrumb'] = 'Earning Report';
		$data['loadJS']     = "datatables/jquery.dataTables.js,datatables/dataTables.bootstrap4.js,daily_roi_report_admin.js";
		$data['layout']     = 'report/daily_roi_report.php';
		$this->load->view('admin/index', $data);
    }

  

    public function getalldailyroiadmin(){        
        $this->load->model('report_model');
		$sort = $_REQUEST['order'][0]['dir'];
		$limit = $_REQUEST['length'];
        $offset = $_REQUEST['start'];
        $draw = $_REQUEST["draw"];
        $conditions = array();
        $conditions['status'] = $_REQUEST["status"];
        $conditions['s_date'] = $_REQUEST["s_date"];
        $conditions['e_date'] = $_REQUEST["e_date"];       
        
      
		//print_r($conditions); die;
        
        $data_all = $this->report_model->get_daily_roi_admin($offset,$limit,$conditions,$sort);
		//echo $this->db->last_query();exit;
		//echo "<pre>";print_r($data_all);exit;
        $data = $data_all['filtereddata'];
        $recordsTotal = count($data_all['totaldata']);
      
        $datafinal = array();
        if(!empty($data)){
            foreach($data as $key=>$row){
				$datafinal[$key]['userid'] =  config_item('ID_EXT') . $row->userid;
                $datafinal[$key]['date'] = $row->date;
                $datafinal[$key]['no_of_roi'] = '1';
                $datafinal[$key]['amount'] = $row->amount;
				$tax_pct = floatval(config_item('payout_tax'));
				$admin_pct = floatval(config_item('admin_charges'));
				$tax = number_format($row->amount * ($tax_pct / 100.0), 2, '.', '');
				$admin_charges = number_format($row->amount * ($admin_pct / 100.0), 2, '.', '');
                $datafinal[$key]['tax'] = $tax;
				$datafinal[$key]['admin_charges'] = $admin_charges;
				$datafinal[$key]['net_amount'] = number_format($row->amount - ($tax + $admin_charges), 2, '.', '');
				$datafinal[$key]['type'] = $row->type;
				$datafinal[$key]['status'] = $row->status;
               
            }
        }
       
        $response = array(
            "draw" => intval($draw),
            "recordsTotal" => $recordsTotal,
            "recordsFiltered" => $recordsTotal,
            "data" => $datafinal
        );
        echo json_encode($response);
       
    }




}