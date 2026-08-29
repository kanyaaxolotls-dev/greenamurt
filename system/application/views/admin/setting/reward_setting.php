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
        <div class="col-sm-6">
            <label>Reward Name</label>
            <input type="text" class="form-control form-control-alternative" value="<?php echo set_value('reward_name') ?>" name="reward_name">
        </div>
        <div class="col-sm-6">
            <label>Reward Duration <br/> </label>
            <input type="text" class="form-control form-control-alternative" value="<?php echo set_value('reward_duration', '0') ?>"
                   name="reward_duration"><span style="font-size: 11px">( Within how many days he/she should achieve this  ? 0 for no duration )</span>
        </div>
  
         <div class="col-sm-6">
            <label>Reward Amount</label>
            <input type="text" class="form-control form-control-alternative" value="<?php echo set_value('reward_amt','0') ?>" name="reward_amt">
        </div>  
        <div class="col-sm-6"> 
            <label>Condition(Direct)</label>
            <input type="text" class="form-control form-control-alternative" value="<?php echo set_value('no_of_sponsor','0') ?>" name="no_of_sponsor">
        </div> 
        
        <div class="col-sm-6">
      
            <?php foreach ($leg as $key => $val) { ?>
                <label>Side <?php echo $key ?></label><input type="text" class="form-control form-control-alternative" placeholder="How many people at <?php echo $key ?> side ?"
                        name="<?php echo $key ?>" value="<?php echo set_value($key) ?>">
            <?php } ?>
        </div>
        
       
        <div class="col-sm-2"><br/>
            <input type="submit" class="btn btn-primary" value="SAVE CHANGES" onclick="this.value='Saving..'">
        </div>
       
    </div>
<?php echo form_close() ?>

</div>



<div class="table-responsive">
    <table class="table table-striped table-bordered" id="example">
        <tr>
            <th>S.N</th>
            <th>Reward Name</th>
            <th>Duration</th>
            <th>Achievers</th>
            <th>Reward Amt.</th>
            <th>#</th>  
        </tr>
        <?php 
        $sn = 1;
        foreach ($result as $e) { ?>
            <tr>
                <td><?php echo $sn++; ?></td>
                <td><?php echo $e->reward_name; ?></td>
                <td><?php echo $e->reward_duration; ?></td>
                <td><?php echo $e->achievers; ?></td>
                <td><?php echo $e->reward_amt; ?></td>
                <td>
                    <a href="<?php echo site_url('setting/edit-reward/' . $e->id); ?>"
                       class="btn btn-info btn-sm glyphicon glyphicon-pencil">Edit</a>
                    <a onclick="return confirm('Are you sure you want to delete this Setting ?')"
                       href="<?php echo site_url('setting/remove-reward/' . $e->id); ?>"
                       class="btn btn-danger btn-sm glyphicon glyphicon-remove">Delete</a>
                </td>
            </tr>
        <?php } ?>
    </table>
</div>
<div class="card-footer">
<p> Note: Reward amount added in reward setting . In database (reward_amt column added)</p>
</div>
</div>
