<?php
//$this->db->select('join_time')->from('member');
$date="0000-00-00";
$this->db->select('*')->group_by('join_time')->select_sum('topup')->where('join_time !=',$date)->from('member'); 
$data = $this->db->get()->result();
?>
  <div class="col"> 
    <div class="card bg-secondary shadow">   
        <div class="card-header bg-white border-0"> 
                <div class="row align-items-center">
                    <div class="col-9">
                          <h4 class="mb-0"><?php echo $title ?></h4>
                    </div>
                <div class="col-3 text-right"> 
                    <a href="<?php echo site_url('member/index')?>" class="btn btn-sm btn-primary">Home</a>
                </div>
            </div>
        </div>

    <div class="table-responsive">
    <table class="table align-items-center table-flush">
        <thead class="thead-light">
            <tr>
                <th>S.N.</th>
               <th>date</th>
               <th>user count(registered)</th>
               <th>Daily Royalty(per day)</th>
            </tr>
        </thead>
        <tbody>
        <?php
          $sn = 1;
          foreach($data as $e){
        ?>
         <tr>
         <td><?php echo $sn++ ?></td>
         <td><?php echo $e->join_time?></td>
         <td><?php echo $this->db_model->count_all('member', array('topup >'=>0,'join_time ='=>$e->join_time));?></td>
         <td><?php 
         $d = $this->db_model->sum('topup','member', array('join_time'=>$e->join_time));
          //  echo $d.'<br>';
        $u= $this->db_model->count_all('level', array('level1 >='=>'5'));
       // echo $u.'<br>';
        $per=$d*(5/100);
       // echo $per.'<br>';
        $amt=$per/$u;
        echo number_format($amt);
         ?></td>
         </tr>
         <?php } ?>
        </tbody>
      
    </table>
</div>
</div>
</div>
</div>
</div>
</div>
