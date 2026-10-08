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
        <label>Counting Based On ( Bv Match )</label>
        <select class="form-control form-control-alternative form-control-alternative-alternative" name="based_on">
            <option value="Pair" selected>Pair ( Bv Match )</option>
        </select> 
    </div>
    <div class="col-sm-6">
        <label>Rank Name</label>
        <input type="text" class="form-control form-control-alternative form-control-alternative-alternative" value="<?php echo set_value('rank_name', $result->rank_name) ?>" name="rank_name">
    </div>
    <div class="col-sm-6">
        <label>Achieve Duration <br/> </label>
        <input type="text" class="form-control form-control-alternative form-control-alternative-alternative" value="<?php echo set_value('rank_duration', $result->rank_duration) ?>"name="rank_duration"><span style="font-size: 11px">( Within how many days he/she should achieve this  ? 0 for no duration )</span>
    </div>
    <input type="hidden" name="id" value="<?php echo $result->id ?>">
    
    <div class="col-sm-6">
        <label>Total Pairs</label>
        <input type="text" class="form-control form-control-alternative form-control-alternative-alternative" value="<?php echo set_value('total_member', $result->total_member) ?>"name="total_member"><span style="font-size: 11px">(How many members required to achive rank )</span> 

    </div>
        <div class="col-sm-12 mt-2">
            <label>Reward Amount</label>
            <input type="number"  class="form-control form-control-alternative form-control-alternative-alternative"  name="amount" value="<?php echo set_value('amount', $result->amount) ?>">
        </div>
    <div class="col-sm-4 mt-4">
        <input type="submit" class="btn btn-success" value="Update" onclick="this.value='Updating..'">
    </div>
  
</div>
  <?php echo form_close() ?>
  </div>
</div>
</div>
