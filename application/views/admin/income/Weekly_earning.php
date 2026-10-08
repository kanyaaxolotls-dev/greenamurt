<div class="col">
    <div class="card shadow">  
        <div class="card-header bg-white border-0"> 
            <div class="row align-items-center">
                    <div class="col-8">
                          <h3 class="mb-0">Club royalty Earning</h3>
                    </div>
                <div class="col-4 text-right"> 
                    <a href="<?php echo site_url('income/view_earning')?>" class="btn btn-sm btn-primary">Home</a>
                </div> 
            </div> 
      </div>
 <div class="table-responsive">
   <table class="table align-items-center table-flush">
        <thead class="thead-light">

        <tr>
            <th scope="col">SN</th>
            <th scope="col">User ID</th>
            <th scope="col">Amount</th>
            <th scope="col">Type</th>
            <th scope="col">Status</th>
            <th scope="col">Date</th>
      
            
        </tr>
    </thead>
        <?php
        $sn = 1;
        foreach ($earning as $e) { ?>
            <tr>
                <td><?php echo $sn++; ?></td>
                <td><a href="<?php echo site_url('users/user_detail/' . $e['userid']) ?>"
                       target="_blank"><?php echo config_item('ID_EXT') . $e['userid']; ?></a></td>
                <td><?php echo config_item('currency') . $e['amount']; ?></td>
                <td><?php echo $e['type']; ?></td>
                <td><?php echo $e['status']; ?></td>

                <td><?php echo $e['date']; ?></td>
     
                
            </tr>
        <?php } ?>
    </table>
</div>
<div class="pull-right">
    <?php echo $this->pagination->create_links(); ?>
</div>
<div class="card-footer">
    <a href="<?php echo site_url('income/search-earning') ?>" class="btn btn-sm btn-primary">&larr; Go Back</a>
</div>
</div>