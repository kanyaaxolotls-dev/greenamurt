
    <div class="card bg-secondary shadow">   
        <div class="card-header bg-white border-0"> 
                <div class="row align-items-center">
                    <div class="col-8">
                          <h3 class="mb-0"><?php echo $title ?></h3>
                    </div> 
                <div class="col-4 text-right"> 
                    <a href="<?php echo site_url('member/index')?>" class="btn btn-sm btn-primary">Home</a>
                </div>
            </div>
        </div> 
    <div class="card-body"> 

   <table class="table align-items-center table-flush">
        <thead class="thead-dark">
        <tr>
            <th>SN</th>
            <th>Invoice Name</th>
            <th>Total Amt</th>
            <th>Paid Amt</th>
            <th>Date</th>
            <th>Actions</th>
        </tr>
    </thead>
        <?php
        $sn = 1;
        foreach ($invoice as $e) { ?>
            <tr>
                <td><?php echo $sn++; ?></td>
                <td><?php echo $e->invoice_name; ?></td>
                <td><?php echo config_item('currency') . $e->total_amt; ?></td>
                <td><?php echo config_item('currency') . $e->paid_amt; ?></td>
                <td><?php echo $e->date; ?></td>
                <td>
                    <a target="_blank" href="<?php echo site_url('franchisee/invoice_view/' . $e->id); ?>" class="btn btn-info btn-sm">Print</a>
                </td>
            </tr>
        <?php } ?>
    </table>
</div>

<div class="card-footer">
    <?php echo $this->pagination->create_links(); ?>
</div>
</div>
</div></div></div>
