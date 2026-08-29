
<?php

defined("BASEPATH") or exit("No direct script access allowed");
class Plan_model extends CI_Model
{
	public function __construct()
	{
		parent::__construct();
		$this->common_model->__session();
	}


	public function create_tree2($pool,$id, $above_id = '', $position = 'A')
	{
		$my_tree    = 'auto_tree';
		$data2      = $this->db_model->select_multi('*', $pool, array('id' => $id));
		$data       = $this->db_model->select_multi('*', 'member', array('id' => $data2->userid));
		$color      = 'green';
		$myimg      = ($data->my_img ? base_url('uploads/' . $data->my_img) : base_url('uploads/site_img/' . $color . '.png'));
		
        if ($data2->id) {
            $echo = '<a href="' . site_url('tree/' . $my_tree . '/' . $id.'/'.$pool) . '" style="text-decoration: none; color: ' . $color . '; margin: 5px" data-toggle="tooltip" data-html="true" data-placement="top" ><img class="img-circle" style="height: 40px" src="' . $myimg . '"><br/>' . $data->name . '<br/></a>';
            return array('A' => $data2->A, 'B' => $data2->B,'data' => $echo, 'id' => $data->id);
        }

		return array('data' => '<img style="height: 40px" src="' . base_url('uploads/site_img/new.png') . '">');
	}


	public function create_leg()
	{
		$leg = config_item('leg');

		if ($leg == '1') {
			return array('A' => 'Left');
		}

		if ($leg == '2') {
			return array('A' => 'Left', 'B' => 'Right');
		}

		if ($leg == '3') {
			return array('A' => 'A', 'B' => 'B', 'C' => 'C');
		}

		if ($leg == '4') {
			return array('A' => 'A', 'B' => 'B', 'C' => 'C', 'D' => 'D');
		}

		if ($leg == '5') {
			return array('A' => 'A', 'B' => 'B', 'C' => 'C', 'D' => 'D', 'E' => 'E');
		}

		if ($leg == '6') {
			return array('A' => 'A', 'B' => 'B', 'C' => 'C', 'D' => 'D', 'E' => 'E', 'F' => 'F');
		}
	}

	public function find_extreme_position($id, $leg)
	{
		$this->db->select($leg)->from('member')->where(array('id' => $id));
		$result = $this->db->get()->row();

		if ($result->{$leg} == 0) {
			return $id;
		}

		return $this->find_extreme_position($result->{$leg}, $leg);
	}

	public function check_position($position, $leg)
	{
		$this->db->select($leg)->from('member')->where('id', $position);
		$result = $this->db->get()->row();

		if ($result->{$leg} == 0) {
			return $position;
		}

		return false;
	}

	public function find_autopool_field($sponsor = '')
	{ 
		if (config_item('leg') == '1') {
			$this->db->select('id,A')->from('member')->where('A', 0)->order_by('secret', 'ASC')->limit(1);
			$result = $this->db->get()->row();
			$id = $result->id;

			if (trim($result->A) == '0') {
				$position = 'A';
			}
		}

		if (config_item('leg') == '2') {
			$this->db->select('id,A,B')->from('member')->where('A', 0)->or_where('B', 0)->order_by('secret', 'ASC')->limit(1);
			$result = $this->db->get()->row();
			$id = $result->id;

			if (trim($result->A) == '0') {
				$position = 'A';
			}
			else {
				$position = 'B';
			}
		}

		if (config_item('leg') == '3') {
			$this->db->select('id,A,B,C')->from('member')->where('A', 0)->or_where('B', 0)->or_where('C', 0)->order_by('secret', 'ASC')->limit(1);
			$result = $this->db->get()->row();

			$id = $result->id;

			if (trim($result->A) == '0') {
				$position = 'A';
			}
			else if (trim($result->B) == '0') {
				$position = 'B';
			}
			else {
				$position = 'C';
			}
		}

		if (config_item('leg') == '4') {
			$this->db->select('id,A,B,C,D')->from('member')->where('A', 0)->or_where('B', 0)->or_where('C', 0)->or_where('D', 0)->order_by('secret', 'ASC')->limit(1);
			$result = $this->db->get()->row();
			$id = $result->id;

			if (trim($result->A) == '0') {
				$position = 'A';
			}
			else if (trim($result->B) == '0') {
				$position = 'B';
			}
			else if (trim($result->C) == '0') {
				$position = 'C';
			}
			else {
				$position = 'D';
			}
		}

		if (config_item('leg') == '5') {
			$this->db->select('id,A,B,C,D,E')->from('member')->where('A', 0)->or_where('B', 0)->or_where('C', 0)->or_where('D', 0)->or_where('E', 0)->order_by('secret', 'ASC')->limit(1);
			$result = $this->db->get()->row();
			$id = $result->id;

			if (trim($result->A) == '0') {
				$position = 'A';
			}
			else if (trim($result->B) == '0') {
				$position = 'B';
			}
			else if (trim($result->C) == '0') {
				$position = 'C';
			}
			else if (trim($result->D) == '0') {
				$position = 'D';
			}
			else {
				$position = 'E';
			}
		}

		return array('id' => $id, 'position' => $position);
		
	}

	public function create_tree($id, $above_id = '', $position = 'A')
	{
        $prod_sale = $this->db_model->sum('cost', 'product_sale',array('userid' => $id));
		$data      = $this->db_model->select_multi('*', 'member', array('id' => $id));
	    $my_tree   = 'my_tree';
		
		if ($this->session->admin_id) {
		    $my_tree = 'user_tree';
		}
        /* hide on 24-11-2025 akki
        if($data->epin_type == 'Zero'){
            $color = 'orange';
        } elseif ($prod_sale >= $this->db_model->select('active_topup', 'global_setting', array('id' => 1)) and $prod_sale != 0) {
			$color = 'green';
		} else {
			$color = 'red';
		}*/
		
		if($data->signup_package == '' && $data->join_package_price == '5000' && $data->activation_type == 'free'){
			$color = 'free_id';
		}elseif($data->rank == 'Ruby Associate'){
			$color = 'ruby_id';
		}
		elseif($data->rank == 'Platinum Associate'){
			$color = 'platinum_id';
		}elseif($data->rank == 'Gold Associate'){
			$color = 'gold_id';
		}elseif($data->rank == 'Silver Associate'){
			$color = 'silver_id';
		}elseif($data->rank == 'Bronze Associate'){
			$color = 'bronze_id';
		}elseif($data->signup_package != '' && $data->topup > 0){
			$color = 'green_id';
		}else{
		 	$color = 'red_id';
		}
		

		$myimg     = ($data->my_img ? base_url('uploads/' . $data->my_img) : base_url('uploads/site_img/' . $color . '.png'));
		$tota_node = '';

		if (config_item('leg') == '1') {
			$tota_node = 'Total Downline: ' . ($data->total_a + $data->total_b + $data->total_c + $data->total_d + $data->total_e) . '';
		}

		if (config_item('leg') == '2') {
			$total_a_pv     = 'My Business Left: ' . $data->total_a_pv;
			$total_b_pv     = 'My Business Right: ' . $data->total_b_pv;
		    $tota_node      = 'Left BV : ' . ($data->total_a_pv ). ' | Right BV : ' . ($data->total_b_pv ). ' | Left Team : ' . ($data->total_a -  $data->paid_a). ' | Right Team : ' . ($data->total_b -  $data->paid_b);
		    $tota_node_html = htmlentities($tota_node);
		} else if (config_item('leg') == '3') {
			$tota_node = 'Total A: ' . $data->total_a . '<br/>' . 'Total B: ' . $data->total_b . '<br/>Total C: ' . $data->total_c . '<br/>';
		} else if (config_item('leg') == '4') {
			$tota_node = 'Total A: ' . $data->total_a . '<br/>' . 'Total B: ' . $data->total_b . '<br/>Total C: ' . $data->total_c . '<br/>Total D: ' . $data->total_d . '<br/>';
		} else if (config_item('leg') == '5') {
			$tota_node = 'Total A: ' . $data->total_a . '<br/>' . 'Total B: ' . $data->total_b . '<br/>Total C: ' . $data->total_c . '<br/>Total D: ' . $data->total_d . '<br/>Total E: ' . $data->total_e . '<br/>';
		}
 
        if ($data->id) {
            $ref_count = $this->db_model->count_all('member',array('sponsor' => $data->id));
            $title     = 'Name : ' . $data->name . '|' .'Total Refferals : ' . $ref_count . '|' .'Joining Date : ' . $data->join_time . '|' . 'Refferal Id  : ' .config_item('ID_EXT'). $data->sponsor . '|' . $tota_node_html . " | My BV: " . $data->mypv;
            $echo      = '<a href="' . site_url('tree/' . $my_tree . '/' . $id) . '" class="hover-element" style="text-decoration: none; color: ' . $color . '; margin: 5px" data-toggle="popover" data-html="true" data-placement="top" data-content="' . htmlentities($title) . '"><img class="img-circle" style="height: 40px" src="' . $myimg . '"><br/>' . $data->name . '<br/></a>';
            return array('A' => $data->A, 'B' => $data->B, 'C' => $data->C, 'D' => $data->D, 'E' => $data->E, 'data' => $echo, 'id' => config_item('ID_EXT').$data->id);
        }

		return array('data' => '<a target="blank" title="Register New Member." href="' . site_url('site/register/' . $position . '/' . $this->session->user_id. '/' . $above_id) . '"><img style="height: 40px" src="' . base_url('uploads/site_img/new.png') . '"></a>');
	}

    /*To Find list off all downline */
	public function all_level_list($id, $sn = 1)
	{
			$this->db->select('*')
			->from('member')->where(array('position' => htmlentities($id)));
			$data = $this->db->get()->result();
			
			foreach ($data as $e) {
					echo '<tr>'.
					'<td><div class="form-check font-size-16">
                        <input class="form-check-input" type="checkbox" id="orderidcheck01">
                        <label class="form-check-label" for="orderidcheck01"></label>
                    </div></td>'.
					'<td>' . $sn++ . '</td>' .
					'<td>' . $e->id . '</td>' .
					'<td>' . $e->name . '</td>' .
					'<td>' . $e->position . '</td>' .
					'<td>' . $e->sponsor . '</td>' .
					'<td>' . $e->phone . '</td>' .
					'<td>' . $e->join_time . '</td>' .
					'<td>' . $e->topup . '</td>' .
					'<td>' . ($e->total_a+$e->total_b+$e->total_c+$e->total_d+$e->total_e) . '</td>' .
					'</tr>';
				$last_id = $e->id;

			}

			if (trim($last_id) !== '') {
				
				$this->all_level_list($last_id, $sn);
			}
		
	}
	
	public function ref_list($id, $sn = 1) 
	{
		$this->db->select('id, name, phone,topup, join_time,rank, total_a, sponsor,position, total_b, total_c, total_d, total_e')
			->from('member')->where(array('sponsor' => htmlentities($id)));
			$data = $this->db->get()->result();
			
			foreach ($data as $e) {
					echo '<tr>'.
					'<td><div class="form-check font-size-16">
                        <input class="form-check-input" type="checkbox" id="orderidcheck01">
                        <label class="form-check-label" for="orderidcheck01"></label>
                    </div></td>'.
					'<td>' . $sn++ . '</td>' .
					'<td>' . $e->id . '</td>' .
					'<td>' . $e->name . '</td>' .
					'<td>' . $e->rank . '</td>' .
					'<td>' . $e->position . '</td>' .
					'<td>' . $e->sponsor . '</td>' .
				// 	'<td>' . $e->phone . '</td>' .
					'<td>' . $e->join_time . '</td>' .
					'<td>' . $e->topup . '</td>' .
					
					'</tr>';
				$last_id = $e->id;

			}

// 			if (trim($last_id) !== '') {
				
// 				$this->ref_list($last_id, $sn);
// 			}
	}

	

	public function level_list_summery($id, $sn = 1)
	{
		$this->db->select('id')
		->from('member')
		->where('sponsor',$id)
		->or_where('position',$id);
		$data = $this->db->get()->result();

		foreach ($data as $e) {

			$this->db->select('id, userid, level1,level2,level3,level4,level5,level6,level7,level8,level9,level10')
			->from('level')
			->where('userid',$e->id);
			$data1 = $this->db->get()->result();

			foreach ($data1 as $e1) {
				echo '<tr>'.'<td>' . $sn++ . '</td>' .
				'<td>' . $e1->userid . '</td>' .
				'<td>' . $e1->level1 . '</td>' .
				'<td>' . $e1->level2 . '</td>' .
				'<td>' . $e1->level3 . '</td>' .
				'<td>' . $e1->level4 . '</td>' .
				'<td>' . $e1->level5 . '</td>' .
				'<td>' . $e1->level6 . '</td>' .
				'<td>' . $e1->level7 . '</td>' .
				'<td>' . $e1->level8 . '</td>' .
				'<td>' . $e1->level9 . '</td>' .
				'<td>' . $e1->level10 . '</td>' .
		

				'</tr>';

				$last_id = $e1->id;
			}

			
		}

		// if (trim($last_id) !== '') {
		// 	$this->level_list_summery($last_id, $sn);
		// } 
	}
		public function sponsor_level_list($id, $sn = 1)
	    	{ 
	    
	    		$this->db->select('*')->from('earning')
	    		->where(array('userid' => $this->session->user_id,'type'=>'Sponsor Level Inc','levlno'=>$this->uri->segment(3)))->order_by('id','ASC');
	    		$data = $this->db->get()->result();
	    		
	    		foreach ($data as $e) {  
	 
	    				$this->db->select('*')->from('member') 
	    				->where(array('id' => $e->ref_id));
	    				$data1 = $this->db->get()->result();
	    
	    				foreach ($data1 as $de) {
	    					if($de->topup>0){  $stat = 'Active';}else{  $stat = 'InActive'; };

	    					echo 
	    						'<tr>' . "\n" . 
	    						'<td>' . $sn++ . '</td>' . "\n" .
	    						'<td>'.$de->id .'</td>' ."\n" .
	    						'<td>'.$de->name .'</td>' . "\n" . 
	    						'<td>' . $de->join_time . '</td>' . "\n" . 
	    						'<td>' . config_item('ID_EXT') . $de->sponsor . '</td>' . "\n" .
	    						'<td>' . $stat . '</td>' . "\n" . 
	    						'<td>' . config_item('currency'). $de->topup . '</td>' . "\n" . 
	    						'</tr>';
	    				}
	    				
	    			}
	    	} 

    	public function single_leg_list($id, $sn = 1) 
    	{ 

     
    		$this->db->select('*')->from('earning')
    		->where(array('userid' => $this->session->user_id,'type'=>'Level Income','levlno'=>$this->uri->segment(3)))->order_by('id','ASC');
    		$data = $this->db->get()->result();
    		
    		foreach ($data as $e) {  
 
    				$this->db->select('*')->from('member') 
    				->where(array('id' => $e->ref_id));
    				$data1 = $this->db->get()->result();
    
    				foreach ($data1 as $de) {
    					if($de->topup>0){  $stat = 'Active';}else{  $stat = 'InActive'; };

    					echo 
    						'<tr>' . "\n" . 
    						'<td>' . $sn++ . '</td>' . "\n" .
    						'<td>'.$de->id .'</td>' ."\n" .
    						'<td>'.$de->name .'</td>' . "\n" . 
    						'<td>' . $de->join_time . '</td>' . "\n" . 
    						'<td>' . config_item('ID_EXT') . $de->sponsor . '</td>' . "\n" .
    						'<td>' .  $type. '</td>'."\n".
    						'<td>' . $stat . '</td>' . "\n" . 
    						'<td>' . config_item('currency'). $de->topup . '</td>' . "\n" . 
    						'</tr>';
    				}
    				
    			}
    
    	} 

    	 
    	public function autopool_team($id, $sn = 1)
    	{  
    		if($this->uri->segment(3)=='deluxe'){
    			$type="Level Income";
    		}
    	
    		if($this->uri->segment(3)=='premium'){
    			$type="Premium Income";
    		}
    		if($this->uri->segment(3)=='silver'){
    			$type="Silver Income";
    		}
    		if($this->uri->segment(3)=='gold'){
    			$type="Gold Income";
    		}
    		if($this->uri->segment(3)=='supergold'){
    			$type="Super Income";
    		}
    		if($this->uri->segment(3)=='diamond'){
    			$type="Diamond Income";
    		}
    	
    		$this->db->select('*')->from('earning')->where(array('userid' => $this->session->user_id,'type'=>$type))->order_by('id','ASC');
    		$data = $this->db->get()->result();

    		foreach ($data as $e) {  
    				$this->db->select('*')->from('member') 
    				->where(array('id' => $e->ref_id));
    				$data1 = $this->db->get()->result();
    
    				foreach ($data1 as $de) {
    					if($de->topup>0){  $stat = 'Active';}else{  $stat = 'InActive'; };
    					echo 
    						'<tr>' . "\n" . 
    						'<td>' . $sn++ . '</td>' . "\n" .
    						'<td>'.$de->id .'</td>' ."\n" .
    						'<td>'.$de->name .'</td>' . "\n" . 
    						'<td>' . $de->join_time . '</td>' . "\n" . 
    						'<td>' . config_item('ID_EXT') . $de->sponsor . '</td>' . "\n" .
    						'<td>' . $stat . '</td>' . "\n" . 
    						'<td>' . config_item('currency'). $de->topup . '</td>' . "\n" . 
    						'</tr>';
    				}
    				
    			}
    
    	} 

	//To view report of all direct members levelwise 10x10 matrix 7 leve 
	public function direct_level_list($id, $sn = 1)
	{
		$this->db->select('id, name,phone,sponsor,rank,status')
		->from('member')->where(array('sponsor' => htmlentities($id)));
		$data = $this->db->get()->result();
		
		foreach ($data as $e) {
				echo '<tr>'.'<td>' . $sn++ . '</td>' .
				'<td>' . $e->id . '</td>' .
				'<td>' . $e->name . '</td>' .
				'<td>' . $e->sponsor . '</td>' .
			
				'<td>' . $e->status . '</td>' .
				'</tr>';
			$last_id = $e->id;

		}

		if (trim($last_id) !== '') {
			
			$this->direct_level_list($last_id, $sn);
		}
	}

  
   public function auto_pool_one($id, $sn = 1)
	{
		
		$this->db->select('id,userid,upgrade_amt,count,status,position')
		->from('pool_one')->where(array('userid' => htmlentities($id)));
		$data = $this->db->get()->result();
		
		foreach ($data as $e) {	
			
				echo '<tr>'.'<td>' . $sn++ . '</td>' .
				'<td>' . $e->userid . '</td>' .
				'<td>' . $this->db_model->select('name', 'member', array('id' => $e->userid)). '</td>' .
				'<td>' . $this->db_model->select('sponsor', 'member', array('id' => $e->userid)). '</td>' .
				'<td>' . $this->db_model->select('join_time', 'member', array('id' => $e->userid)) . '</td>' .
				'<td>' . $e->upgrade_amt . '</td>' .
				'<td>' . $e->status . '</td>' .
				'<td>' . $e->position . '</td>' .
				'</tr>';
				
		$last_id = $e->userid;
		}
		if (trim($last_id) !== '') {
			$this->auto_pool_one($last_id, $sn);
		}
	}
 
	
	public function auto_pool_second($id, $sn = 1)
	{
		$this->db->select('id,userid,upgrade_amt,count,status')
		->from('pool_two');
		$data = $this->db->get()->result();
	      foreach ($data as $e) {
				$data = $this->db_model->select_multi('id,name,phone,join_time', 'member', array('id' => $e->userid));
					echo '<tr>'.'<td>' . $sn++ . '</td>' .
					'<td>' . $e->userid . '</td>' .
				
					'<td>' . $data->name . '</td>' .
					'<td>' . $data->join_time . '</td>' .
					'<td>' . $e->upgrade_amt . '</td>' .
					'<td>' . $e->status . '</td>' .
					'</tr>';
				$last_id = $e->userid;

			}

		if (trim($last_id) !== '') {
			
			$this->auto_pool_second($last_id, $sn);
		}
	} 

	public function auto_pool_third($id, $sn = 1)
	{
		$this->db->select('id,userid,upgrade_amt,count,status')
		->from('pool_three');
		$data = $this->db->get()->result();
	      foreach ($data as $e) {
				$data = $this->db_model->select_multi('id,name,phone,join_time', 'member', array('id' => $e->userid));
					echo '<tr>'.'<td>' . $sn++ . '</td>' .
					'<td>' . $e->userid . '</td>' .
				
					'<td>' . $data->name . '</td>' .
					'<td>' . $data->join_time . '</td>' .
					'<td>' . $e->upgrade_amt . '</td>' .
					'<td>' . $e->status . '</td>' .
					'</tr>';
				$last_id = $e->userid;

			}

		if (trim($last_id) !== '') {
			
			$this->auto_pool_third($last_id, $sn);
		}
	}

	public function auto_pool_fourth($id, $sn = 1)
	{
		$this->db->select('id,userid,upgrade_amt,count,status')
		->from('pool_four');
		$data = $this->db->get()->result();
	      foreach ($data as $e) {
				$data = $this->db_model->select_multi('id,name,phone,join_time', 'member', array('id' => $e->userid));
					echo '<tr>'.'<td>' . $sn++ . '</td>' .
					'<td>' . $e->userid . '</td>' .
					
					'<td>' . $data->name . '</td>' .
					'<td>' . $data->join_time . '</td>' .
					'<td>' . $e->upgrade_amt . '</td>' .
					'<td>' . $e->status . '</td>' .
					'</tr>';
				$last_id = $e->userid;

			}

		if (trim($last_id) !== '') {			
			$this->auto_pool_fourth($last_id, $sn);
		}
	}

	public function auto_pool_fifth($id, $sn = 1)
	{
		$this->db->select('id,userid,upgrade_amt,count,status')
		->from('pool_five');
		$data = $this->db->get()->result();
	      foreach ($data as $e) {
				$data = $this->db_model->select_multi('id,name,phone,join_time', 'member', array('id' => $e->userid));
					echo '<tr>'.'<td>' . $sn++ . '</td>' .
					'<td>' . $e->userid . '</td>' .
					
					'<td>' . $data->name . '</td>' .
					'<td>' . $data->join_time . '</td>' .
					'<td>' . $e->upgrade_amt . '</td>' .
					'<td>' . $e->status . '</td>' .
					'</tr>';
				$last_id = $e->userid;

			}

		if (trim($last_id) !== '') {
			
			$this->auto_pool_fifth($last_id, $sn);
		}
	}

	


	
}

defined('BASEPATH') || true;

?>
