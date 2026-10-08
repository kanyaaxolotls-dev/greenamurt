<?php
 $this->db->order_by('join_time','DESC'); 
 $this->db->group_by('id,join_time'); 
 $this->db->select('*');
$data = $this->db->get('member')->result();
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
            </tr>
        </thead>
        <tbody>
        <?php
          $sn = 1;
          foreach($data as $e){
        ?>
         <tr>
         <td><?php echo $sn ?></td>
         </tr>
         <?php } ?>
        </tbody>
       6
    </table>
</div>
</div>
</div>
</div>
</div>
</div>
