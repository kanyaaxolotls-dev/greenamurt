<div class="col">
    <div class="card bg-secondary shadow">  
        <div class="card-header bg-white border-0"> 
        <div class="row align-items-center">
            <div class="col-8">
                  <h3 class="mb-0"><?php echo $title;?></h3>
            </div>
        <div class="col-4 text-right"> 
            <a href="<?php echo site_url('admin/index')?>" class="btn btn-sm btn-primary">Home</a>
        </div>
    </div>
</div> 
<div class="card-body">
    <?php echo form_open() ?>
<div class="row"> 
        <div class="col-sm-4">
            <label>Reward Name (Rank)</label>
            <input type="text" class="form-control form-control-alternative" value="<?php echo set_value('reward_name') ?>" name="reward_name" placeholder="e.g. Pioneer">
        </div>
        <div class="col-sm-4">
            <label>Alternative Gift Option</label>
            <input type="text" class="form-control form-control-alternative" value="<?php echo set_value('reward_gift') ?>" name="reward_gift" placeholder="e.g. Smart Watch / Mixer Grinder">
        </div>
        <div class="col-sm-4">
            <label>Reward Duration (Days)</label>
            <input type="number" class="form-control form-control-alternative" value="<?php echo set_value('reward_duration', '0') ?>"
                   name="reward_duration"><span style="font-size: 11px">(Days to achieve 100% cash; 0 for No Limit)</span>
        </div>
        <div class="col-sm-4">
            <label>100% Cash Reward Amount (₹)</label>
            <input type="number" step="0.01" class="form-control form-control-alternative" value="<?php echo set_value('reward_amt','0') ?>" name="reward_amt">
        </div>
        <div class="col-sm-4">
            <label>50% Grace Reward Amount (₹)</label>
            <input type="number" step="0.01" class="form-control form-control-alternative" value="<?php echo set_value('grace_amt','0') ?>" name="grace_amt">
        </div>
        <div class="col-sm-4"> 
            <label>Condition (Direct Sponsors)</label>
            <input type="number" class="form-control form-control-alternative" value="<?php echo set_value('no_of_sponsor','0') ?>" name="no_of_sponsor">
        </div> 
        
        <div class="col-sm-6">
            <?php foreach ($leg as $key => $val) { ?>
                <label>Side <?php echo $key ?> Pairs (PV)</label><input type="number" class="form-control form-control-alternative" placeholder="Pairs required on side <?php echo $key ?>"
                        name="<?php echo $key ?>" value="<?php echo set_value($key) ?>">
            <?php } ?>
        </div>
        
        <div class="col-sm-12 mt-3">
            <input type="submit" class="btn btn-primary" value="SAVE REWARD SETTING" onclick="this.value='Saving..'">
        </div>
       
    </div>
<?php echo form_close() ?>

</div>



<div class="table-responsive">
    <table class="table table-striped table-bordered align-items-center" id="example">
        <thead class="thead-light">
            <tr>
                <th>Lvl</th>
                <th>Rank Name</th>
                <th>Alternative Gift</th>
                <th>Pairs (PV)</th>
                <th>Duration</th>
                <th>100% Cash Reward</th>
                <th>50% Reward (Grace)</th>
                <th>#</th>  
            </tr>
        </thead>
        <tbody>
            <?php 
            $sn = 1;
            foreach ($result as $e) { ?>
                <tr>
                    <td><strong><?php echo $e->level_no ?? $sn++; ?></strong></td>
                    <td><strong class="text-primary"><?php echo $e->reward_name; ?></strong></td>
                    <td><span class="badge badge-info"><?php echo !empty($e->reward_gift) ? $e->reward_gift : '-'; ?></span></td>
                    <td><strong><?php echo $e->total_member ?? ($e->A ?? '-'); ?> Pairs</strong></td>
                    <td><?php echo (!empty($e->reward_duration) && $e->reward_duration > 0) ? ($e->reward_duration . ' Days') : '<span class="badge badge-success">No Limit</span>'; ?></td>
                    <td><strong class="text-success">₹<?php echo number_format(floatval($e->reward_amt), 2); ?></strong></td>
                    <td><strong class="text-warning">₹<?php echo number_format(floatval($e->grace_amt ?? ($e->reward_amt / 2)), 2); ?></strong></td>
                    <td>
                        <a href="<?php echo site_url('setting/edit-reward/' . $e->id); ?>"
                           class="btn btn-info btn-sm">Edit</a>
                        <a onclick="return confirm('Are you sure you want to delete this Setting ?')"
                           href="<?php echo site_url('setting/remove-reward/' . $e->id); ?>"
                           class="btn btn-danger btn-sm">Delete</a>
                    </td>
                </tr>
            <?php } ?>
        </tbody>
    </table>
</div>
<div class="card-footer">
<p> Note: Reward amount added in reward setting . In database (reward_amt column added)</p>
</div>
</div>
