
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
            <th>ROI Issued</th>
            <th>Date</th>
            <th>Days to next ROI</th>
            <th>ROI Tenure</th>
            <th>ROI Amt. </th>
            <th>Recieved</th>
            <th>ROI Pending</th>
            <th>Status</th>
            
        </tr>
        <?php
        $sn = 1; 
        foreach ($earning as $e) { ?>
            <tr>
                <td><?php echo $sn++; ?></td> 
                <td><a href="<?php echo site_url('users/user_detail/' . $e['userid']) ?>"
                       target="_blank"><?php echo config_item('ID_EXT') . $e['userid']; ?></a></td>
              
                <td><?php echo $e['income_type']; ?></td>
                <td><?php if($e['binary_rank']=='1'){echo "First";}else{echo "Onwords";}; ?></td>
                <td><?php echo  $nextdate =  date('Y-m-d', strtotime($e['created_at']));   ?></td>
                <td style="text-align: center;">
                    <?php
                       $timestamp=strtotime($e['created_at']."+30 Days");
                       $nextdate =  date('Y-m-d', $timestamp);   
                       $days_laps = date('Y-m-d') - strtotime('Y-m-d' . strtotime($e['created_at']));
                       $daydiff1 = time() - strtotime($e['created_at']);
                       $day_count1 = round($daydiff1 / (60 * 60 * 24));

                       $daydiff2 = strtotime($nextdate) - strtotime($e['created_at']);
                       $day_count2 = round($daydiff2 / (60 * 60 * 24));
                       echo $day_count2-$day_count1;
                    ?> 
                </td>
                <td><?php echo $e['roi_limit']." Months"; ?></td>
                <td><?php echo $e['roi'];?></td>
                <td> <?php echo $e['amount'] - ($days * $broi)?> </td>
            
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