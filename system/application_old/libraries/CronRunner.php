<?php
defined('BASEPATH') or exit('No direct script access allowed');
require FCPATH . 'vendor/autoload.php';
class CronRunner
{
 private $CI;

 public function __construct()
 {
    $this->CI =& get_instance();
    ##### Set this setting to cpanel:
    #### */1 	* 	* 	* 	* 	/usr/local/bin/php /home/pantraders/public_html/system/index.php cronpayout run
    
 }

 public function calculateNextRun()
 {
    //return (time() + $obj->interval_sec);
	// minute (0-59)	hour (0 - 23)	day of the month (1 - 31)	month (1 - 12)	day of the week (0 - 6)
	$exp = config_item('cron_minutes').' '.config_item('cron_hours').' '.config_item('cron_day').' '.config_item('cron_month').' '.config_item('cron_weekday');
	$cron = Cron\CronExpression::factory($exp);
    return $cron->getNextRunDate()->format('Y-m-d H:i:s');
 }

 public function run()
 {
      
   $query = $this->CI->db->where('is_active', 1)->where('now() >= next_run_at OR next_run_at IS NULL', '', false)->from('cron')->get();
   if ($query->num_rows() > 0) {
	   
	   $this->generate_payout();
	   $this->binary_payout();
	   $this->payout();
       foreach ($query->result() as $row) {
       	 $this->CI->db->set('next_run_at', $this->calculateNextRun())->where('id', $row->id)->update('cron');
         $this->CI->db->set('last_run_at', 'now()', false)->where('id', $row->id)->update('cron');

       }
	   
    } 
  }
  
  
  	public function payout()
	{
	    $this->CI->load->model('Db_model','db_model');
		$this->CI->db->select('id, userid,type,amount')->where('status', 'Pending');
		$data = $this->CI->db->get('earning')->result();
		
			foreach ($data as $e) { 
			
				if($e->type=="Repurchase Income"){
				    
				    }elseif($e->type=="Matching Income"){
						$data = array('status' => 'Paid');
						$this->CI->db->where('id', $e->id);
						$this->CI->db->update('earning', $data);
						
					}else{
						$cur_balance = $this->CI->db_model->select('balance', 'wallet', array('userid' => $e->userid));
						$data = array('balance' => $e->amount + $cur_balance);
						$this->CI->db->where('userid', $e->userid);
						$this->CI->db->update('wallet', $data);

						$data = array('status' => 'Paid');
						$this->CI->db->where('id', $e->id);
						$this->CI->db->update('earning', $data);

					}

					// if($e->type=="Binary ROI"){
					// $cur_balance = $this->db_model->select('balance', 'wallet', array('userid' => $e->userid));
					// $data = array('balance' => $e->amount + $cur_balance);
					// $this->db->where('userid', $e->userid);
					// $this->db->update('wallet', $data);
					// 	$data = array('status' => 'Pending');
					// }else{
					// 	$data = array('status' => 'Paid');
					// }
					
				
		}

	}
	
	
 private function binary_payout()
	{
		$count_product_binary = $this->CI->db_model->count_all('product', array('matching_income >' => 0));
		$count_fix_binary = $this->CI->db_model->select('binary_income', 'fix_income', array('1 >' => 0));
		$count_invst_binary = $this->CI->db_model->select('matching_income', 'investment_pack', array(0));

		if ((0 < $count_product_binary) || (0 < $count_fix_binary) || (0 < $count_invst_binary)) {
			$this->CI->db->select('id,total_a,total_b,paid_a,paid_b,signup_package,mypv,total_a_matching_incm,total_b_matching_incm, total_c_matching_incm, paid_a_matching_incm, paid_b_matching_incm')->from('member')->where('topup >', '0')->where('total_a >', 0)->where('total_b >', 0)->where('paid_a <', 'total_a', false)->where('paid_b <', 'total_b', false);
			$data = $this->CI->db->get()->result();
			
			foreach ($data as $result) {
				$this->CI->load->model('earning');
				$data2 = array('total_a' => $result->total_a, 'total_b' => $result->total_b, 'paid_a' => $result->paid_a, 'paid_b' => $result->paid_b, 'signup_package' => $result->signup_package, 'mypv' => $result->mypv, 'total_a_matching_incm' => $result->total_a_matching_incm, 'total_b_matching_incm' => $result->total_b_matching_incm, 'total_c_matching_incm' => $result->total_c_matching_incm, 'paid_a_matching_incm' => $result->paid_a_matching_incm, 'paid_b_matching_incm' => $result->paid_b_matching_incm);
			
				$this->CI->earning->process_binary($result->id, $data2);
			}

		}
	}


   public function generate_payout()
    {
       

 			$this->CI->load->model('Db_model','db_model');
			
// 			  $data = array(
//                                     'userid'    => 1001,
//                                     'income_type' => 'Matching ROI',
//                                     'amount' => 22,
//                                     'roi'   => 11,
//                                     'roi_frequency' => '30',
//                                     'roi_limit' =>'10',  
//                                     'status' =>'Pending',
//                                     'binary_rank' =>1,
//             );
                               
           // $this->db->insert('earning_roi', $data); 
             // $this->CI->db->insert('earning_roi', $data);                 
                                
            #### ROI PAYOUT GENERATION AS PER OPTION SELECT PAYOUT TYPE 'ROI'
            $payout_type = 'roi'; //$this->input->post('pay_type');
            $count_product_roi =  $this->CI->db_model->count_all('product', array('roi >' =>0.00));
              
            if (0 < $count_product_roi && $payout_type=='roi') {  
                $this->CI->load->model('earning');
                $this->CI->earning->roi_earning();  
            }
            ############## BINARY PAYOUT GENERATION AS PER OPTION SELECT PAYOUT TYPE 'BINARY'#######
       
            $count_binary_roi_income = $this->CI->db_model->count_all('earning', array('type' =>'Matching Income','status'=>'Pending'));
             $count_binary_income = $this->CI->db_model->count_all('earning', array('type' =>'Matching Income','status'=>'Pending'));
         
             
            if(0 < $count_binary_roi_income && $payout_type=='binary_roi')
             {  
                $this->CI->db->select('id, userid,type,amount')->where('status', 'Pending');
                $data = $this->CI->db->get('earning')->result();
                  
                    foreach ($data as $e) {
                    
                            $cur_balance = $this->CI->db_model->select('balance', 'wallet', array('userid' => $e->userid));
                            $matching_total = $this->CI->db_model->sum('amount', 'earning', array('userid' => $e->userid,'status'=>'Pending'));

                            if($matching_total>0){
                                $check_userid_existince = $this->CI->db_model->select('userid', 'earning_roi', array('userid' => $e->userid));
                                if($check_userid_existince ==false){
                                    $roi_rank = "1";
                                }else{$roi_rank="0";}
                                $data = array(
                                    'userid'    => $e->userid,
                                    'income_type' => 'Matching ROI',
                                    'amount' => $matching_total,
                                    'roi'   => $matching_total,
                                    'roi_frequency' => '30',
                                    'roi_limit' =>'10',  
                                    'status' =>'Pending',
                                    'binary_rank' =>$roi_rank,
                                 );
                               
                                $this->CI->db->insert('earning_roi', $data);                                 
                                $data = array('status' => 'Paid');
                                $this->CI->db->where('userid', $e->userid);
                                $this->CI->db->update('earning', $data); 

                            }
                        }
                }else if(0 < $count_binary_income && $payout_type=='binary'){
                

                    $this->CI->db->select('id, userid,type,amount')->where('status', 'Pending');
                    $data = $this->CI->db->get('earning')->result();
                   
                    foreach ($data as $e) {
                    
                            $cur_balance = $this->CI->db_model->select('balance', 'wallet', array('userid' => $e->userid));
                            $matching_total = $this->CI->db_model->sum('amount', 'earning', array('userid' => $e->userid,'status'=>'Pending'));

                            if($matching_total>0){
                                $data = array('balance' => $cur_balance + $e->amount);  
                                 $this->CI->db->where('userid', $e->userid);
                                $this->CI->db->update('wallet', $data);
                               
                                $data = array('status' => 'Paid');
                                $this->CI->db->where('id', $e->id);
                                $this->CI->db->update('earning', $data); 

                            }
                        }
             }else{

               
             }

        
        
            ##########################################################

             #### REPURCHASE PAYOUT GENERATION AS PER OPTION SELECT PAYOUT TYPE 'REPURCHASE'
         
            $count_repurchase_income = $this->CI->db_model->count_all('earning', array('type' =>'Repurchase Income','status'=>'Pending'));
           
            if (0 < $count_repurchase_income && $payout_type=='repurchase') { 

                $this->CI->db->select('id, userid,type,amount')->where('status', 'Pending');
                $data = $this->CI->db->get('earning')->result();
        
                foreach ($data as $e) {
                
                        $cur_balance = $this->CI->db_model->select('balance', 'wallet', array('userid' => $e->userid));
                        $data = array('balance' => $e->amount + $cur_balance);
                        $this->CI->db->where('userid', $e->userid);
                        $this->CI->db->update('wallet', $data);
                        $data = array('status' => 'Paid');
                        $this->CI->db->where('id', $e->id);
                        $this->CI->db->update('earning', $data);
                    }

                     $this->CI->db->select('userid, balance')->where('balance >=', config_item('min_withdraw'));
                        $res = $this->CI->db->get('wallet')->result();
                       
               
                        foreach ($res as $result) { 
                            $e       = 1;
                            $uid     = $result->userid;
                            $balance = $result->balance;

                            $array = array(
                                'balance' => 0,
                            );
                            $this->CI->db->where('userid', $uid);
                            $this->CI->db->update('wallet', $array);

                            $data = array(
                                'userid' => $uid,
                                'amount' => $balance,
                                'date'   => date('Y-m-d'),
                            );
                           
                            $this->CI->db->insert('withdraw_request', $data);
                            //$this->session->set_flashdata('common_flash', '<div class="alert alert-success">Payout calculated Successfully.</div>');
                        }
                        
                    
                }


                #### Calculate Rank Bonus #####
                $count_rank_bonus = $this->CI->db_model->count_all('earning', array('type' =>'Rank Bonus','status'=>'Pending'));
           
            if (0 < $count_rank_bonus && $payout_type=='rank_bonus') { 

                $this->CI->db->select('id, userid,type,amount')->where('status', 'Pending');
                $data = $this->CI->db->get('earning')->result();
        
                foreach ($data as $e) {
                
                        $cur_balance = $this->CI->db_model->select('balance', 'wallet', array('userid' => $e->userid));
                        $data = array('balance' => $e->amount + $cur_balance);
                        $this->CI->db->where('userid', $e->userid);
                        $this->CI->db->update('wallet', $data);
                        $data = array('status' => 'Paid');
                        $this->CI->db->where('id', $e->id);
                        $this->CI->db->update('earning', $data);
                    }

                     $this->CI->db->select('userid, balance')->where('balance >=', config_item('min_withdraw'));
                        $res = $this->CI->db->get('wallet')->result();
                       
               
                        foreach ($res as $result) { 
                            $e       = 1;
                            $uid     = $result->userid;
                            $balance = $result->balance;

                            $array = array(
                                'balance' => 0,
                            );
                            $this->CI->db->where('userid', $uid);
                            $this->CI->db->update('wallet', $array);

                            $data = array(
                                'userid' => $uid,
                                'amount' => $balance,
                                'date'   => date('Y-m-d'),
                            );
                           
                            $this->CI->db->insert('withdraw_request', $data);
                           // $this->session->set_flashdata('common_flash', '<div class="alert alert-success">Rank Bonus calculated Successfully.</div>');
                        }
                        
                    
                }

                   ######################### ROI Sponsor Income################

            $count_sponsor_income = $this->CI->db_model->count_all('earning', array('type' =>'Sponsor Income','status'=>'Pending'));
           
            if (0 < $count_sponsor_income && $payout_type=='sponsor') { 

                $this->CI->db->select('id, userid,type,amount')->where('status', 'Pending');
                $data = $this->CI->db->get('earning')->result();
        
                foreach ($data as $e) {
                
                        $cur_balance = $this->CI->db_model->select('balance', 'wallet', array('userid' => $e->userid));
                        $data = array('balance' => $e->amount + $cur_balance);
                        $this->CI->db->where('userid', $e->userid);
                        $this->CI->db->update('wallet', $data);
                        $data = array('status' => 'Paid');
                        $this->CI->db->where('id', $e->id);
                        $this->CI->db->update('earning', $data);
                    }

                     $this->CI->db->select('userid, balance')->where('balance >=', config_item('min_withdraw'));
                        $res = $this->CI->db->get('wallet')->result();
                       
               
                        foreach ($res as $result) { 
                            $e       = 1;
                            $uid     = $result->userid;
                            $balance = $result->balance;

                            $array = array(
                                'balance' => 0,
                            );
                            $this->CI->db->where('userid', $uid);
                            $this->CI->db->update('wallet', $array);

                            $data = array(
                                'userid' => $uid,
                                'amount' => $balance,
                                'date'   => date('Y-m-d'),
                            );
                           
                            $this->CI->db->insert('withdraw_request', $data);
                            //$this->session->set_flashdata('common_flash', '<div class="alert alert-success">Payout calculated Successfully.</div>');
                        }
                        
                    
                }
                #######################End ROI Sponsor Income###################
 

                ################ We will generate payout now ################
                $this->CI->db->select('userid, balance')->where('balance >=', config_item('min_withdraw'));
                $res = $this->CI->db->get('wallet')->result();
                
                foreach ($res as $result) {
                    $e       = 1;
                    $uid     = $result->userid;
                    $balance = $result->balance;

                    $array = array(
                        'balance' => 0,
                    );
                    $this->CI->db->where('userid', $uid);
                    $this->CI->db->update('wallet', $array);

                    $data = array(
                        'userid' => $uid,
                        'amount' => $balance,
                        'date'   => date('Y-m-d'),
                    );
                    
                    $this->CI->db->insert('withdraw_request', $data);
                    
                    /* SMS code for SMS on payout */
                    $this->CI->db->select('name, phone')->where('id',$uid);
                    $udata = $this->CI->db->get('member')->result();
                    
                    foreach ($udata as $ud) {
                    
                    $cur_balance = $this->CI->db_model->select('balance', 'wallet', array('userid' => $id));
                    if (config_item('sms_on_join') == "Yes"):
                        /* Textlocal sms code */
                       // $sms = rawurlencode("Hi " . $udata->name . " ,We have credited INR ".$balance ." in your wallet. Available bal: INR ".$new_fund.". Thanks \nwww." . $_SERVER['HTTP_HOST']."\n"); 
                        $sms = "Hi " . $ud->name. ",Payout generated successfully. We have credited INR.".$balance ." in your wallet. Thanks \nwww." . $_SERVER['HTTP_HOST']."\n";
                        // $sms = "Hi, " . $name . ", Thank you for registering with " . config_item('company_name') . ". Your UserID is: " . config_item('ID_EXT') . $user_id . " and Password is: " . $this->input->post('password') . "
                         //    -- www." . $_SERVER['HTTP_HOST'];
                        /* MSG91 code */

                        // $this->sms($phone, rawurlencode($sms));
                        $this->CI->common_model->sms($ud->phone, $sms);
                    endif;

                     }

                  //$this->session->set_flashdata('common_flash', '<div class="alert alert-success">Payout Generated Successfully.</div>');
                }
                if ($e !== 1) {
                    //$this->session->set_flashdata('common_flash', '<div class="alert alert-info">No User Id has sufficient balance, Hence No Payout Generated.</div>');
                }
                //redirect('income/make-payment');

            #############################################################
       
    }  
  
  
}