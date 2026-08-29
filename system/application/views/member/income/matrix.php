
    <div class=" bg-secondary shadow">   
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
            <th>SN</th>
            <th>User ID</th>
            <th>Name</th>
            <th>Mobile</th>
           
            <th>Status</th>
            
        </tr>
        </thead>
        <?php
        $sn = 1; 
        foreach ($data as $e) { ?>
            <tr>
                <td><?php echo $sn++; ?></td> 
                <td><?php echo $e['userid'] ?></td> 
                <td><?php echo $this->db_model->select('name', 'member', array('id' =>$e['userid']));  ?></td> 
                <td><?php echo $this->db_model->select('phone', 'member', array('id' =>$e['userid']));  ?></td>
                <td><?php echo $e['status']  ?></td> 
            </tr>
        <?php } ?>
    </table>
</div>
<div class="pull-right">
    <?php echo $this->pagination->create_links(); ?>
</div>
<a href="<?php echo site_url('income/search-earning') ?>" class="btn btn-xs btn-su">&larr; Go Back</a>
</div>
</div>
</div>