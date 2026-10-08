<?php
defined("BASEPATH") or exit("No direct script access allowed");
class Apiusers_model extends CI_Model
{
	public function __construct()
	{
		parent::__construct();
	}
	function get_users($start,$limit)
	{
	    $valid_users = array();//who mapped mobile no with website1
	    $invalid_users = array();//who do not mapped mobile no with website1
	    $this->db->select('id, name, phone,join_time,signup_package,total_a, total_b, total_c, total_d, total_e')->from('member'); 

        $this->db->limit($start, $limit);
        
        $result = $this->db->get();
        
        if($result->num_rows() > 0)
        {
            $users = $result->result_array();
            $mobile_list = array();
            foreach($users as $row)
                $mobile_list[] = $row['phone'];
            
            //fetch wallet details from website1
            $wallet_details = $this->fetch_wallet($mobile_list);
            foreach($users as $key=>$row)
            {
                if(array_key_exists($row['phone'],$wallet_details)) 
                {
                    $row['wallet_balance'] = $wallet_details[$row['phone']];
                    $valid_users[] = $row;
                }
                else
                {
                    $row['wallet_balance'] = '-';
                    $invalid_users[] = $row;
                }
            }
            return array('valid_users'=>$valid_users,'invalid_users'=>$invalid_users); 
        }
        else
            return array();
	}
	
	function fetch_wallet($mobiles)
	{
	    $url = 'https://sdv71shops.com/apiusers/fetch_wallet';

        $fields = array(
                'mobiles'=>$mobiles,
        );
    
        $fields_string = json_encode($fields);
 
        //open connection
        $ch = curl_init();
    
        //set the url, number of POST vars, POST data
        curl_setopt($ch,CURLOPT_URL,$url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_POST, 1);
        curl_setopt($ch,CURLOPT_POSTFIELDS,$fields_string);
        curl_setopt($ch, CURLOPT_HTTPHEADER, array(
                    'Content-Type:application/json',
                ));
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, FALSE);
    
        //execute post
        $result = curl_exec($ch);
        
        $data = json_decode($result,true);
        //close connection
        curl_close($ch);
        return $data;
	}
	
	function update_wallet($data)
	{
	    $url = 'https://sdv71shops.com/apiusers/save_wallet';
    
        $fields_string = json_encode($data);
 
        //open connection
        $ch = curl_init();
    
        //set the url, number of POST vars, POST data
        curl_setopt($ch,CURLOPT_URL,$url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_POST, 1);
        curl_setopt($ch,CURLOPT_POSTFIELDS,$fields_string);
        curl_setopt($ch, CURLOPT_HTTPHEADER, array(
                    'Content-Type:application/json',
                ));
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, FALSE);
    
        //execute post
        $result = curl_exec($ch);
        
        $data = json_decode($result,true);
        //close connection
        curl_close($ch);
        return $data;
	}

    function bank_transfer($data){
        //var_dump($data);
        $url = 'https://api.razorpay.com/v1/payouts';
        
        $fields_string = json_encode($payout);
 
        //open connection
        $ch = curl_init();
    
        //set the url, number of POST vars, POST data
        curl_setopt($ch,CURLOPT_URL,$url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_POST, 1);
        curl_setopt($ch,CURLOPT_POSTFIELDS,$fields_string);
        curl_setopt($ch, CURLOPT_HTTPHEADER, array(
            'X-Payout-Idempotency: ',
            'Authorization: Basic cnpwX3Rlc3RfRGVjOVBjSEVuSVdsNE46RWU1cnBpU0R5bXRwM2toM0haTzlmb29J',
            'Content-Type: application/json'
          ));
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, FALSE);
    
        //execute post
        $result = curl_exec($ch);
        
        $data = json_decode($result,true);
       // var_dump($data);
       
        //close connection
        curl_close($ch);
       return $data;
    }
}	