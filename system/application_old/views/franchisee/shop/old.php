
    <div class="card bg-secondary shadow">   
        <div class="card-header bg-white border-0"> 
                <div class="row align-items-center">
                    <div class="col-8">
                          <h3 class="mb-0"><?php echo $title ?></h3>
                    </div> 
                <div class="col-4 text-right"> 
                    <a href="<?php echo site_url('franchisee')?>" class="btn btn-sm btn-primary">Home</a>
                </div>
            </div>
        </div> 
    <div class="card-body table-responsive"> 

    <table class="table align-items-center table-flush" id="example">
    <thead class="thead-dark">
    <tr>
        <th>SN</th>
        <th>Userid</th>
        <th>Orderid</th>
        <th>Total Amt</th>
        <th>Status</th>
        <th>Date</th>
        <th>Actions</th>
    </tr>
    </thead>
        <?php
        $sn = 1;
        foreach ($orders as $e) {
            if($e['status'] == 'Approve'){
                $clr = 'success';
                $txt = 'Approved';
            } elseif($e['status'] == 'Reject'){
                $clr = 'danger';
                $txt = 'Rejected';
            } else{
                $clr = 'info';
                $txt = 'Pending';
            }
        ?>
            <tr>
                <td class="text-dark"><?php echo $sn++; ?></td>
                <td class="text-dark"><?php echo $e['userid']; ?></td>
                <td class="text-dark">#<?php echo $e['orderid']; ?></td>
                <td class="text-dark"><?php echo config_item('currency') . $e['total_price']; ?></td>
                <td class="text-<?= $clr; ?> h5"><?= $txt; ?></td>
                <td class="text-dark"><?php echo $e['date']; ?></td>
                <td class="text-dark">
                    <a href="<?php echo site_url('franchisee/order_view2/'.$e['orderid']); ?>" class="btn btn-info btn-sm">View Order</a>
                </td>
            </tr>
        <?php } ?>
    </table>
</div>
</div>
</div></div></div>
