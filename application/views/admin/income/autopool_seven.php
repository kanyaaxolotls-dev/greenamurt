
<div class="col">
    <div class="card bg-secondary shadow">  
        <div class="card-header bg-white border-0"> 
        <div class="row align-items-center"> 
        <div class="col-8"> 
                  <h3 class="mb-0">matrix 7 Members</h3>
            </div>
        <div class="col-4 text-right"> 
            <a href="<?php echo site_url('admin')?>" class="btn btn-sm btn-primary">Home</a>
        </div> 
    </div>
</div>
<div class="card-body">
 
 <div class="table-responsive">
 <table id="example" class="display" style="width:100%">         
 <thead class="thead-light">
        <tr>
        <th scope="col">S.N.</th>
            <th scope="col">User ID</th>
            <th scope="col">User Name</th>
            <th scope="col">Join Date</th>
            <th scope="col">Sponsors</th>
            <th scope="col">count</th>
            <th scope="col">Amount</th>
            <th scope="col">Status</th>
            
        </tr> 
    </thead>
        <?php
        $sn = 1; 
        foreach ($earning as $e) { ?>
            <tr>
            <td><?php echo $sn++; ?></td>
            <td><?php echo $e['userid']; ?></td>
            <td><?php echo $data = $this->db_model->select('name', 'member', array('id' => $e['userid']));?></td>
            <td><?php echo $data = $this->db_model->select('join_time', 'member', array('id' => $e['userid']));?></td>
            <td><?php echo $e['sponsor'];?></td>
            <td><?php echo $data1 = $this->db_model->select('count1', 'pool_one', array('userid' => $e['userid']));?>
            </td>
  
            <td><?php echo $e['upgrade_amt'];
            ?>
            
            </td>
            
            <td><?php echo $e['status'];?></td>
             </tr>
        <?php } ?>
    </table>
</div>
</div>

</div>
</div>