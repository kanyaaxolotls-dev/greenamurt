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
        <label>Reward Name</label>
        <input type="text" class="form-control form-control-alternative" value="<?php echo set_value('reward_name', $result->reward_name) ?>"
               name="reward_name">
    </div>
    <div class="col-sm-4">
        <label>Alternative Gift Option</label>
        <input type="text" class="form-control form-control-alternative" value="<?php echo set_value('reward_gift', $result->reward_gift ?? '') ?>"
               name="reward_gift">
    </div>
    <input type="hidden" name="id" value="<?php echo $result->id ?>">
    <div class="col-sm-4">
        <label>Reward Duration (Days)</label>
        <input type="number" class="form-control form-control-alternative"
               value="<?php echo set_value('reward_duration', $result->reward_duration) ?>" name="reward_duration"><span
                style="font-size: 11px">(Days to achieve 100% cash; 0 for no limit)</span>
    </div>
     <div class="col-sm-4">
        <label>100% Cash Reward Amount (₹)</label>
        <input type="number" step="0.01" class="form-control form-control-alternative" value="<?php echo set_value('reward_amt', $result->reward_amt)?>" name="reward_amt">
    </div> 
     <div class="col-sm-4">
        <label>50% Grace Reward Amount (₹)</label>
        <input type="number" step="0.01" class="form-control form-control-alternative" value="<?php echo set_value('grace_amt', $result->grace_amt ?? ($result->reward_amt / 2))?>" name="grace_amt">
    </div> 
      <div class="col-sm-4"> 
            <label>Condition (Direct Sponsors)</label>
            <input type="number" class="form-control form-control-alternative" value="<?php echo set_value('no_of_sponsor',$result->no_of_sponsor) ?>" name="no_of_sponsor">
        </div> 
 
    <div class="col-sm-6">
        <?php foreach ($leg as $key => $val) { ?>
            <label>Side <?php echo $key ?> Pairs (PV)</label><input type="number" class="form-control form-control-alternative"
                                                    placeholder="Pairs required on side <?php echo $key ?>"
                                                    name="<?php echo $key ?>"
                                                    value="<?php echo set_value($key, $result->$key) ?>">
        <?php } ?>
    </div>

    <div class="col-sm-12 mt-3">
        <input type="submit" class="btn btn-primary" value="Update Reward Setting" onclick="this.value='Updating..'">
    </div>
  
</div>
  <?php echo form_close() ?>
 </div>
</div>
</div>