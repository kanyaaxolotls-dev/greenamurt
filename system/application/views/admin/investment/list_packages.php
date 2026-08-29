 <div class="col"> 
    <div class="card bg-secondary shadow">   
        <div class="card-header bg-white border-0"> 
                <div class="row align-items-center">
                    <div class="col-9">
                          <h4 class="mb-0"><?php echo $title ?></h4>
                    </div> 
                <div class="col-3 text-right"> 
                   <a href="<?php echo site_url('admin/index')?>" class="btn btn-sm btn-primary">Home</a>
                </div>
            </div>
        </div>
 <div class="card-body"> 
<div class="table-responsive">
    <table class="table table-striped table-bordered">
        <tr>
            <th>SN</th>
            <th>Package Name</th>
            <th>Amount</th>
            <th>ROI</th>
            <th>ROI Payout</th>
            <th>Actions</th>
        </tr>
        <?php 
        $sn = 1;
        foreach ($result

                 as $e) { ?>
            <tr>
                <td><?php echo $sn++; ?></td>
                <td><?php echo $e->pack_name; ?></td>
                <td><?php echo config_item('currency') . $e->amount; ?></td>
                <td><?php echo $e->roi; ?></td>
                <td>Every <?php echo $e->roi_frequency; ?> days</td>
                <td>
                    <a href="<?php echo site_url('investments/edit_pack/' . $e->id); ?>" class="btn btn-info btn-xs">Edit</a>
                    <a onclick="return confirm('Are you sure you want to delete this Package ?')"
                       href="<?php echo site_url('investments/remove_inv_pack/' . $e->id); ?>"
                       class="btn btn-danger btn-xs">Delete</a>
                </td>
            </tr>
        <?php } ?>
    </table>
</div>
<div class="pull-right">
    <?php echo $this->pagination->create_links(); ?>
</div>
</div></div>