<?php 
defined('BASEPATH') OR exit('No direct script access allowed');
class Cronearning extends CI_Controller
{

 public function __construct()
 {
	parent::__construct();
	$this->load->model('cronearning_model');
	if (!$this->input->is_cli_request()) {
		// show_error('Direct access is not allowed');
	}
 }

 private function calculateNextRun($obj)
 {
    return (time() + $obj->interval_sec);
 }

	public function run()
	{
	  $this->roi_calculation(); // temporary
		$query = $this->db->where('is_active', 1)->where('now() >= next_run_at OR next_run_at IS NULL', '', false)->where('id', 1)->from('earning_roi_cron')->get();
		if ($query->num_rows() > 0) {
		   $row = $query->row();
			 $this->db->set('next_run_at', 'FROM_UNIXTIME('.$this->calculateNextRun($row).')', false)->update('earning_roi_cron');
			 $this->db->set('last_run_at', 'now()', false)->where('id', $row->id)->update('earning_roi_cron');
			// $this->roi_calculation();
		  
		} 
	}
 

	public function roi_calculation()
	{
		$allSettings =  $this->cronearning_model->getAllEarningSettings(); 

		
		if($allSettings) {
			foreach($allSettings as $result) {

				$levlno = isset($result->levlno)?$result->levlno:0; 
				$earningData = $this->cronearning_model->getDataFromEarning($result);
				$target_amount = isset($result->target_amount)?$result->target_amount:0; 
				if($earningData) {
					//echo "Working";
					foreach($earningData as $earning_row) {
					
						$payout_cycle = isset($result->payout_cycle)?$result->payout_cycle:0; 
						$no_of_times = isset($result->no_of_times)?$result->no_of_times:0; 
						if($levlno==0){
							$this->cronearning_model->operationLevelZero($earning_row,$payout_cycle,$no_of_times);
							
						}
						else{
							// and insert "Wallet" ; Table "Earning" -> table "Earning_roi"
							$this->cronearning_model->operationLevelNonZero($earning_row,$payout_cycle,$no_of_times,$target_amount);
						}
					}
				}				
			}
		}  
		
	}

	/* 
	Earning calculation
	a) Earning will be calculated for each member as per their Investment package
	) In instance, If the Invstment amt. 6500, ROI: 200, ROI Frequncy: 1,ROI Limit :60
	then Earning will be calculated daily as 200*60 = 12000 upto next 60 days from the package activation date. calculated date cab be either "working days" Or "normal days" as per corresponding member's package. Calculated values will be inserted on the "earning" table MySQL.
	*/
	  
	public function run_earning()
	{

		$query = $this->db->where('is_active', 1)->where('now() >= next_run_at OR next_run_at IS NULL', '', false)->where('id', 2)->from('earning_roi_cron')->get();
		if ($query->num_rows() > 0) {
		   $row = $query->row();
			 $this->db->set('next_run_at', 'FROM_UNIXTIME('.$this->calculateNextRun($row).')', false)->update('earning_roi_cron');
			 $this->db->set('last_run_at', 'now()', false)->where('id', $row->id)->update('earning_roi_cron');
			 $this->earning_calculation();
		} 
	}
	 
	public function earning_calculation()
	{

		$allSettings =  $this->cronearning_model->getAllInvestments(); 
		// print_r( $allSettings);exit;
		if($allSettings) {
			foreach($allSettings as $result) {
			  $this->cronearning_model->insertToEarning($result);
			}  
		} 
		echo "done"; exit;	   

	}
	  
	  
	public function run_wallet()
	{
		//$this->wallet_calculation();

		$query = $this->db->where('is_active', 1)->where('now() >= next_run_at OR next_run_at IS NULL', '', false)->where('id', 3)->from('earning_roi_cron')->get();
		if ($query->num_rows() > 0) {
		   $row = $query->row();
			 $this->db->set('next_run_at', 'FROM_UNIXTIME('.$this->calculateNextRun($row).')', false)->update('earning_roi_cron');
			 $this->db->set('last_run_at', 'now()', false)->where('id', $row->id)->update('earning_roi_cron');
			 $this->wallet_calculation();
		  
		} 
	}
	  
	public function wallet_calculation()
	{
		
	   $earningToBeTras =  $this->cronearning_model->getAllEarningToBeTras(); 
	  // echo "<pre>";
	 // print_r($earningToBeTras);exit;
	   if($earningToBeTras) {
		   foreach($earningToBeTras as $result) {
			  $this->cronearning_model->insertORUpdateWallet($result);
		   }  
	   } 
	  // echo "done"; exit;	   

	}
  
}


 /*
            [id] => 1
            [levlno] => 1
            [target_amount] => 44.00
            [payout_cycle] => daily
            [no_of_times] => 2
            [created_at] => 2020-10-04 22:16:52
            [earning_type] => Premium Income
			
			 [userid] => 4668147
            [join_time] => 2020-09-17
            [amt] => 1500000.00
            [levlno] => 0
            [calc_date] => 2020-09-18
            [earning_ids] => 891,892,893,894,895,896,897,898,899,900,901,902,903,904,905,906,907,908,909,910,911,912,913,914,915,916,917,918,919,920,921,922,923,924,925,926,927,928,929,930,931,932,933,934,935,936,937,938,939,940,941,942,943,944,945,946,947,948,949,950

 */

