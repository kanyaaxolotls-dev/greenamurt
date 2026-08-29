<div class="col">
    <div class="card bg-secondary shadow">  
        <div class="card-header bg-white border-0"> 
        <div class="row align-items-center"> 
            <div class="col-8">
                  <h3 class="mb-0">Matching List</h3>
            </div>
        <div class="col-4 text-right"> 
            <a href="<?php echo site_url('admin')?>" class="btn btn-sm btn-primary">Home</a>
        </div> 
    </div>
</div>
<div class="card-body">
 
 <div class="table-responsive">
       <table id="data-table" class="table align-items-center table-flush">
        <thead class="thead-light">
        <tr>
            <th>SN</th>
            <th>User ID</th>
            <th>Amount</th>
            <th>Type</th>
            <th>Ref ID</th>
            <th>Date</th>
            <th>Total days</th>
            <th>Binary ROI</th>
            <th>Total Earned</th>
            <th>ROI Remains</th>
            <th>Total Balance</th>
            <th>Status</th>
            
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
                <td><?php echo $e['ref_id'] ? config_item('ID_EXT') . $e['ref_id'] : ""; ?></td>
                <td><?php echo $e['date']; ?></td>
               
                <td>
                    <?php

                        $this->db->select('signup_package')->from('member')->where(array('id '=>$e['userid']));
                        $users = $this->db->get()->result();
                     
                        foreach ($users as $users):
                            $userid = $users->id;
                            $binroi = $this->db_model->select_multi('roi,roi_limit,roi_frequency', 'product', array('matching_income' => $e['amount']));
                            $broi= $binroi->roi;
                            $broi_limit= $binroi->roi_limit;
                        endforeach;
                    //var_dump($broi_limit);
                        echo $broi_limit;
                    ?>  

                </td> 
                <td><?php echo $broi;?> </td>
                <td style="color: green;text-align: center;">
                    <?php
                         $date1      = date_create($e['date']);
                        $date2      = date_create(date('y-m-d'));
                        $diff       = date_diff($date1,$date2);
                        $days       = $diff->format("%a")+1;
                        echo $days * $broi;
                    ?>
                </td>
                <td> <?php echo $broi_limit-$days;?>
                </td>

                <td> <?php echo $e['amount'] - ($days * $broi)?>
                </td>

                <td style="color: red"><?php echo $e['status'] ?></td>
                 
            </tr>
        <?php } ?>
    </table>
</div>
</div>
<div class="card-footer">
<a href="<?php echo site_url('income/search-earning') ?>" class="btn btn-xs btn-su">&larr; Go Back</a>
    <?php echo $this->pagination->create_links(); ?>
</div>
</div>
</div>