
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
    <div class="card-body">
 
<div class="table-responsive">
    <table class="table table-striped table-bordered">
        <tr> 
            <th>SN</th>
            <th>User ID</th>
            <th>Amount</th>
            <th>Date</th>
            <th>Reference</th>
            <th>Status</th>
            
        </tr>
        <?php
        $sn = 1; 
        foreach ($earning as $e) { ?>
            <tr>
                <td><?php echo $sn++; ?></td> 
                <td><a href="<?php echo site_url('users/user_detail/' . $e['userid']) ?>"
                       target="_blank"><?php echo config_item('ID_EXT') . $e['userid']; ?></a></td>
              
                <td><?php echo $e['amount']; ?></td>
                <td><?php echo date('Y-m-d', strtotime($e['date']));?></td>
              
                <td><?php echo $e['ref_id'];?></td>
              
                <td><?php echo $e['status'] ?></td>
                 
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
</div></div>