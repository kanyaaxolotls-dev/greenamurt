  <div class="col">
    <div class="card bg-secondary shadow">  
        <div class="card-header bg-white border-0">  
        <div class="row align-items-center">
            <div class="col-8">
                  <h3 class="mb-0"><?php echo $title ?></h3>
            </div>
        <div class="col-4 text-right"> 
            <a href="<?php echo site_url('admin')?>" class="btn btn-sm btn-primary">Home</a>
        </div>
    </div>
</div>
<div class="table-responsive">
    <table class="table table-striped">
        <tr>
            <th>SN</th>
            <th>User / Fran Id</th>
            <th>Gateway</th>
            <th>Transaction Id</th>
            <th>Amount</th>
            <th>Date</th>
            <th>Actions</th>
        </tr>
        <?php
        $sn = 1;
        foreach ($result as $e) { ?>
            <tr>
                <td><?php echo $sn++; ?></td>
                <td><?php echo $e->userid; ?></td>
                <td><?php echo $e->gateway; ?></td>
                <td><?php echo $e->transaction_id; ?></td>
                <td><?php echo config_item('currency') . $e->amount; ?></td>
                <td><?php echo date('d/m/Y', $e->time); ?></td>
                <td>
                    <a onclick="return confirm('Are you sure you want to delete this Record ?')"
                       href="<?php echo site_url('accounting/remove_tlog/' . $e->id); ?>" class="btn btn-danger btn-xs">Delete</a>
                </td>
            </tr>
        <?php } ?>
    </table>
</div>
<div class="pull-right">
    <?php echo $this->pagination->create_links(); ?>
</div>
</div>
</div>