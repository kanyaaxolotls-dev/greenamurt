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
            <th>Date</th>
            <th>Amount</th>
            <th>ROI Issued</th>
            <th>Status</th>
        </tr> 
        <?php
        $sn = 1; 
        foreach ($earning as $e) { ?> 
            <tr>
                <td><?php echo $sn++; ?></td> 
                 <td>
                    <?php echo $nextdate = date('Y-m-d', strtotime($e['date']));?>
                 </td>
                 <td>Rs. <?php echo $e['amount'];?></td>
                <td><?php echo $e['type']; ?></td>
                <td><?php echo $e['status'] ?></td>
                 
            </tr>
        <?php } ?>
    </table>
</div>
<div class="pull-right"> 
   <?php echo $this->pagination->create_links(); ?>
</div>
<a href="<?php echo site_url('income/search-earning') ?>" class="btn">&larr; Go Back</a>
</div>
</div>
</div></div></div>