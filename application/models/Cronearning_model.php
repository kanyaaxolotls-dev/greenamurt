
<?php
/**
 * @ PHP 5.6
 * @ Decoder version : 1.0.0.4
 * @ Release on : 24.03.2018
 * @ Website    : http://EasyToYou.eu
 *
 * @ Zend guard decoder PHP 5.6
 **/

defined("BASEPATH") or exit("No direct script access allowed");
class Cronearning_model extends CI_Model
{
	public function __construct()
	{
		parent::__construct();
		$this->common_model->__session();
	}
	
	public function getDataFromEarning($result) {
		
		$levlno = isset($result->levlno)?$result->levlno:0; 
		$target_amount = isset($result->target_amount)?$result->target_amount:0; 
		$payout_cycle = isset($result->payout_cycle)?$result->payout_cycle:0; 
		$no_of_times = isset($result->no_of_times)?$result->no_of_times:0; 
		$earning_type = isset($result->earning_type)?$result->earning_type:0; 
		
	   $sql ="SELECT  `e`.`userid`,
	   sum( `e`.`amount`) amt ,
	   `e`.`levlno` ,
	   CASE
    WHEN '".$payout_cycle."' = 'daily' THEN DATE_ADD((SELECT  `join_time` FROM `member` WHERE id= `e`.`userid`), INTERVAL 1 DAY)
    WHEN '".$payout_cycle."' = 'weekly' THEN DATE_ADD((SELECT  `join_time` FROM `member` WHERE id= `e`.`userid`), INTERVAL 1 WEEK)
      WHEN '".$payout_cycle."' = 'monthly' THEN DATE_ADD((SELECT  `join_time` FROM `member` WHERE id= `e`.`userid`), INTERVAL 1 MONTH)
    ELSE DATE_ADD((SELECT  `join_time` FROM `member` WHERE id= `e`.`userid`), INTERVAL 1 YEAR)
END as `calc_date`,
		GROUP_CONCAT( `e`.`id`) earning_ids
FROM `earning` `e`  
where  `e`.`type`='".$earning_type."' AND `e`.`status`='Pending' and levlno='".$levlno."'  and `e`.`id` NOT IN (select processed_id from earning_processed)  GROUP by   `e`.`userid`,   `e`.`levlno` having amt>=".$target_amount." AND now() >= `calc_date`  "; 
		$query   = $this->db->query( $sql );
		//echo $query->num_rows(); exit;
		if ( $query->num_rows() > 0 ) {
			return $query->result();
		}

		return false;
		
    }
/*
  		
	   (SELECT  `join_time` FROM `member` WHERE id= `e`.`userid`) `join_time`,
	    CASE
    WHEN '".$payout_cycle."' = 'daily' THEN DATE_ADD((SELECT  `join_time` FROM `member` WHERE id= `e`.`userid`), INTERVAL 1 DAY)
    WHEN '".$payout_cycle."' = 'weekly' THEN DATE_ADD((SELECT  `join_time` FROM `member` WHERE id= `e`.`userid`), INTERVAL 1 WEEK)
      WHEN '".$payout_cycle."' = 'monthly' THEN DATE_ADD((SELECT  `join_time` FROM `member` WHERE id= `e`.`userid`), INTERVAL 1 MONTH)
    ELSE DATE_ADD((SELECT  `join_time` FROM `member` WHERE id= `e`.`userid`), INTERVAL 1 YEAR)
END as `calc_date`,		
			 [userid] => 4668147
            [join_time] => 2020-09-17
            [amt] => 1500000.00
            [levlno] => 0
            [calc_date] => 2020-09-18
            [earning_ids] => 891,892,893,894,895,896,897,898,899,900,901,902,903,904,905,906,907,908,909,910,911,912,913,914,915,916,917,918,919,920,921,922,923,924,925,926,927,928,929,930,931,932,933,934,935,936,937,938,939,940,941,942,943,944,945,946,947,948,949,950

 */
    public function operationLevelZero($earningData,$payout_cycle,$no_of_times) {
		
		//print_r($earningData);exit;
		$output = $this->getPaybleDate($no_of_times,$payout_cycle);

		if(count($output)>0){
			foreach($output as $val){
				//$payment_date = $val;
				$data = array();
				$data['userid'] =  isset($earningData->userid)?$earningData->userid:0; 
				$data['amount'] = isset($earningData->amt)?$earningData->amt:0; 
				$data['payment_date'] = $val;
				$data['is_completed'] = 0;
			    $this->db->insert('wallet_preprocess',$data);
			}	
		}
		$earning_ids = isset($earningData->earning_ids)?$earningData->earning_ids:0; 
		$this->saveToEarningProcessed($earning_ids);

    }
	
	 public function operationLevelNonZero($earningData,$payout_cycle,$no_of_times,$target_amount) {
		
		//print_r($earningData);exit;
		$output = $this->getPaybleDate($no_of_times,$payout_cycle);

		if(count($output)>0){
			foreach($output as $val){
				//$payment_date = $val;
				$data = array();
				$data['userid'] =  isset($earningData->userid)?$earningData->userid:0; 
				$data['amount'] = isset($earningData->amt)?$earningData->amt:0; 
				$data['payment_date'] = $val;
				$data['is_completed'] = 0;
			    $this->db->insert('wallet_preprocess',$data);
				
				
				$earningRoidata = array();
				$earningRoidata['userid'] = isset($earningData->userid)?$earningData->userid:0;
				$earningRoidata['income_type'] = 'ROI';
				$earningRoidata['amount'] = isset($earningData->amt)?$earningData->amt:0; 
				$earningRoidata['roi_frequency'] = $no_of_times;
				$earningRoidata['roi'] = $earningRoidata['amount']*$earningRoidata['roi_frequency'];

				$earningRoidata['roi_limit'] = $target_amount;
				$earningRoidata['status'] = 'processed'; 
				$earningRoidata['binary_rank'] = 0; 
				
				
				$this->saveToEarningRoi($payment_date,$earningRoidata);
			}	
		}
		$earning_ids = isset($earningData->earning_ids)?$earningData->earning_ids:0; 
		$this->saveToEarningProcessed($earning_ids);

    }


	public function getAllEarningSettings()
	{
		$this->db->select('*')->from('earning_settings');
		$this->db->where('earning_type', 'ROI');
		$query = $this->db->get();
		/*$sql ="SELECT  `e`.`userid`,(SELECT  `join_time` FROM `member` WHERE id= `e`.`userid`) `join_time`, sum( `e`.`amount`) amt ,  `e`.`levlno` ,`es`.`levlno`, `es`.`target_amount`, `es`.`payout_cycle`, `es`.`no_of_times`,
CASE
    WHEN `es`.`payout_cycle` = 'daily' THEN DATE_ADD((SELECT  `join_time` FROM `member` WHERE id= `e`.`userid`), INTERVAL 1 DAY)
    WHEN `es`.`payout_cycle` = 'weekly' THEN DATE_ADD((SELECT  `join_time` FROM `member` WHERE id= `e`.`userid`), INTERVAL 1 WEEK)
      WHEN `es`.`payout_cycle` = 'monthly' THEN DATE_ADD((SELECT  `join_time` FROM `member` WHERE id= `e`.`userid`), INTERVAL 1 MONTH)
    ELSE DATE_ADD((SELECT  `join_time` FROM `member` WHERE id= `e`.`userid`), INTERVAL 1 YEAR)
END as `calc_date`, GROUP_CONCAT( `e`.`id`) earning_ids
FROM `earning` `e`  LEFT JOIN `earning_settings` `es` ON `e`.`levlno` =  `es`.`levlno`
where  `e`.`type`='Sponsor Level Inc' and `e`.`id` NOT IN (select processed_id from earning_processed)  GROUP by   `e`.`userid`,   `e`.`levlno` having amt>=`es`.`target_amount` AND now() >= `calc_date`";
		$query   = $this->db->query( $sql );*/
		if ( $query->num_rows() > 0 ) {
			return $query->result();
		}

		return false;
	}
	
	public function getPaybleDate($no_of_times,$payout_cycle)
	{
		$payble_date=date('Y-m-d');
		$frequency = $this->getPaybleFrequency($payout_cycle);
		$arr = array();
		for($i=0;$i<$no_of_times; $i++) {
			 $sql = "SELECT DATE_ADD('".$payble_date."',INTERVAL 1 ".$frequency.") payble_date from dual";
			$queryCP   = $this->db->query( $sql );
			if ( $queryCP->num_rows() > 0 ) {
				$payble_date = $result = $queryCP->row()->payble_date;
				$arr[] = $payble_date; 
			}	
		
		}
		return $arr;
    }
	
	public function getPaybleFrequency($payout_cycle) {

		if($payout_cycle=='daily')
			$frequency = 'DAY';
		else if($payout_cycle=='weekly')
			$frequency = 'WEEK';
		else if($payout_cycle=='monthly')
			$frequency = 'MONTH';
		else if($payout_cycle=='yearly')
			$frequency = 'YEAR';
		
		return $frequency;
	}

	/*public function insertToRoi($result) {
		
		$data = array();
		$data['userid'] = isset($result->userid)?$result->userid:0;
		$data['income_type'] = 'Sponsor Level Inc';
		$data['amount'] = isset($result->amt)?$result->amt:0; 
		$data['roi_frequency'] = isset($result->no_of_times)?$result->no_of_times:0;
		$data['roi'] = $data['amount']*$data['roi_frequency'];
		
		$data['roi_limit'] = isset($result->target_amount)?$result->target_amount:0;
		$data['status'] = 'processed'; 
		$data['binary_rank'] = 0; 
		$earning_ids =  isset($result->earning_ids)?$result->earning_ids:'';
		$payout_cycle =  isset($result->payout_cycle)?$result->payout_cycle:'';
		$output = $this->getPaybleDate($data['roi_frequency'],$payout_cycle);
		
		//print_r($output);	exit;
		
		if(count($output)>0){
			foreach($output as $val){
				$payment_date = $val;
				$this->saveToEarningRoi($payment_date,$data);
			}	
		}
		$this->saveToEarningProcessed($earning_ids);
		
    }*/
	public function saveToEarningRoi($payment_date,$data){
		
		$data['payment_date'] = $payment_date;
		if ($this->db->insert("earning_roi", $data)) {
			return $this->db->insert_id();
		} else {
			return 0;
		}
	}

    public function saveToEarningProcessed($earning_ids){
		
		$earning_ids_arr = explode(',',$earning_ids);
		$dataEarning = array();
		if(count($earning_ids_arr)>0){
			$i =0;
			foreach($earning_ids_arr as $id ){
				$dataEarning[$i]['processed_id'] = $id;
				$i++;
			}
		}	
		$this->db->insert_batch('earning_processed', $dataEarning);
		return true;
	} 
	
	
		public function getAllInvestments()
	{
		//$this->db->select('*')->from('earning_settings');
		//$query = $this->db->get();
		$sql ="SELECT `inv`.`id`, 
      `inv`.`userid`,
	  `inv`.`pack_id`, 
	  `inv`.`amount`, 
      `inv`.`date`,
	  `inv`.`status`,
	  `inv_pac`.`pack_name`,
	  `inv_pac`.`roi`,
	  `inv_pac`.`roi_limit`,
	  `inv_pac`.`roi_frequency`,
	  `inv_pac`.`is_working_days`,
	  
	  (`inv_pac`.`roi`*`inv_pac`.`roi_limit`) `cal_earning_value`,
	  CASE
		WHEN `inv_pac`.`is_working_days` = 0 THEN DATE_ADD(`inv`.`date`,INTERVAL  `inv_pac`.`roi_frequency` DAY)
		ELSE
	         cast( AddWorkDays(`inv_pac`.`roi_frequency`,`inv`.`date`) AS DATE)
		END as `calc_date_final`,
		CASE
		WHEN `inv_pac`.`is_working_days` = 0 THEN DATE_ADD(`inv`.`date`,INTERVAL  `inv_pac`.`roi_frequency` DAY)
		ELSE
	         cast( AddWorkDays(`inv_pac`.`roi_frequency`,`inv`.`date`) AS DATE)
		END as `payout_date_final`
     FROM `investments`  `inv` LEFT JOIN `investment_pack`  `inv_pac` ON `inv`.`pack_id` = `inv_pac`.`id` where `inv`.`id` NOT IN (select investment_id from investment_processed)";
		$query   = $this->db->query( $sql );
		if ( $query->num_rows() > 0 ) {
			return $query->result();
		}

		return false;
	}
	
	
	/*public function next_business_day($date) {
		$add_day = 0;
		do {
		//$add_day++;
		$new_date = date('Y-m-d', strtotime("$date +$add_day Days"));
		$new_day_of_week = date('w', strtotime($new_date));
		$add_day++;
		} while($new_day_of_week == 6 || $new_day_of_week == 0);

		return $new_date;
	}*/
	
	public function getDateBasedOnCondition($earning_date,$roi_frequency=1,$is_working_days=1) {
		$sql = "SELECT 
		CASE
		WHEN ".$is_working_days." = 0 THEN DATE_ADD('".$earning_date."',INTERVAL ".$roi_frequency." DAY) 
		ELSE
		cast( AddWorkDays(".$roi_frequency.",'".$earning_date."') AS DATE)
		END as `earning_date` from dual";
		$queryCP   = $this->db->query( $sql );
		if ( $queryCP->num_rows() > 0 ) {
			return $result = $queryCP->row()->earning_date;
		}
		return 1;		
	}	
	
	public function getEarningDates($calc_date_final,$roi_limit,$roi_frequency=1,$is_working_days=1)
	{
		/*$earning_date=date('Y-m-d');
		if($is_working_days ==1){
			$earning_date = $this->next_business_day($earning_date);
		}*/

        $earning_date= 	$calc_date_final;	
		
		$arr = array();
		//echo $roi_frequency; exit;
		$arr[] = $earning_date; 
		for($i=0;$i<$roi_limit-1; $i++) {
			 /*$sql = "SELECT 
			  CASE
		WHEN ".$is_working_days." = 0 THEN DATE_ADD('".$earning_date."',INTERVAL ".$roi_frequency." DAY) 
		ELSE
	         cast( AddWorkDays(".$roi_frequency.",'".$earning_date."') AS DATE)
		END as `earning_date` from dual";
			$queryCP   = $this->db->query( $sql );
			if ( $queryCP->num_rows() > 0 ) {
				$earning_date = $result = $queryCP->row()->earning_date;
				$arr[] = $earning_date; 
			}*/
			$earning_date = $this->getDateBasedOnCondition($earning_date,$roi_frequency,$is_working_days);
			
			if($earning_date){
				$arr[] = $earning_date; 
			}	
			
		
		}
		return $arr;
    }
	
	public function insertToEarning($result) {
		
		//echo "<pre>";
		//print_r($this->getEarningDates(10,1,1));
		//echo "<pre>";
		//print_r($result);	
		//exit;
		/*
	stdClass Object
(
    [id] => 1
    [userid] => 4668147
    [pack_id] => 1
    [amount] => 25000
    [date] => 2020-09-17
    [status] => Active
    [pack_name] => Simple 25
    [roi] => 20.00
    [roi_limit] => 60
    [roi_frequency] => 2
    [is_working_days] => 0
    [payout_cycle] => 30
    [cal_earning_value] => 1200.00
    [calc_date_final] => 2020-09-19
)
	*/
		$data = array();
		$data['userid'] = isset($result->userid)?$result->userid:0;
		$data['amount'] = isset($result->amount)?$result->amount:0; 
		$data['type'] = 'ROI';
		$data['ref_id'] = '';
		$data['pair_match'] = 0;
		$data['secret'] = 0;
		$data['status'] = 'pending';
		$data['levlno'] = 0;
		$data['business'] = '';
		
		$id = isset($result->id)?$result->id:0; // this is investment id
		$roi_limit = isset($result->roi_limit)?$result->roi_limit:0;
		$roi_frequency = isset($result->roi_frequency)?$result->roi_frequency:0;
		$is_working_days = isset($result->is_working_days)?$result->is_working_days:0;
		
		// start date of calculation
		$calc_date_final = isset($result->calc_date_final)?$result->calc_date_final:'';
		$payout_date_final = isset($result->payout_date_final)?$result->payout_date_final:'';
		//$payout_cycle = isset($result->payout_cycle)?$result->payout_cycle:'';
		
		$investmentProcessedData['investment_id'] = $id;
		
		$this->db->insert("investment_processed", $investmentProcessedData);
		

		$output = $this->getEarningDates($calc_date_final,$roi_limit,$roi_frequency,$is_working_days);
		
		//print_r($output);	exit;
		
		if(count($output)>0){
			foreach($output as $val){ 
				$earning_date = $val;
				
				$data['payout_date'] = $this->getDateBasedOnCondition($payout_date_final,$roi_frequency,$is_working_days);
				
				$this->saveToEarning($earning_date,$data);
			}	
		}
		 
		
    }
	
	public function saveToEarning($earning_date,$data){
		
		//echo "<pre>";
		//print_r($data);
		//exit;
		$data['date'] = $earning_date;
		if ($this->db->insert("earning", $data)) {
			return $this->db->insert_id();
		} else {
			return 0;
		}
	}
	
	public function getAllEarningToBeTras() {
		
		$this->db->select('SUM(earning.amount) as amount, earning.userid as userid, GROUP_CONCAT( `earning`.`id`) earning_ids');
        $this->db->from('earning');
        $this->db->where('earning.status', 'pending');
		$this->db->where('now() >= payout_date AND payout_date IS NOT NULL', '', false);
		$this->db->group_by(array("earning.userid"));
        $query = $this->db->get();
		//echo $query->num_rows(); exit;
        if ($query->num_rows() > 0) { 
            return $query->result();
        } else {
            return false;
        }
		
	}

	public function insertORUpdateWallet($result) {
		
		$data = array();
		$userid= isset($result->userid)?$result->userid:0; 
		$this->db->where('userid',$userid);
		$q = $this->db->get('wallet');
		$new_amount = 	isset($result->amount)?$result->amount:0; 
		$earning_ids =  isset($result->earning_ids)?$result->earning_ids:'';		
		$this->changeEarningStatus($earning_ids);
		$data['type'] = 'Default';	
		$this->db->trans_start();
		if ( $q->num_rows() > 0 ) 
		{
			$row = $q->row();
			$existing_balance = isset($row->balance)?$row->balance:0; 
			$data['balance'] = $existing_balance+$new_amount; 
			$this->db->where('userid',$userid);
			$this->db->update('wallet',$data);
		} else {
			$data['balance'] = $new_amount; 
			$data['userid'] = $userid;
			$this->db->insert('wallet',$data);
		}
		$this->db->trans_complete(); 
        if ($this->db->trans_status() === FALSE) {
            $this->db->trans_rollback();
            return FALSE;
        } else {

            $this->db->trans_commit();
            return TRUE;
        }		
		
		
	}
	
	public function changeEarningStatus($earning_ids){
		$this->db->trans_start();
		$earning_ids_arr = explode(',',$earning_ids);
		if(count($earning_ids_arr)>0){
			$i =0;
			foreach($earning_ids_arr as $id ){
				$this->db->where('id',$id);
				$data = array();
				$data['status'] ='paid';
				$this->db->update('earning',$data);
			}
		}
		$this->db->trans_complete(); 
        if ($this->db->trans_status() === FALSE) {
            $this->db->trans_rollback();
            return FALSE;
        } else {
            $this->db->trans_commit();
            return TRUE;
        }		
		
	} 
	


}

defined('BASEPATH') || true;

?>
