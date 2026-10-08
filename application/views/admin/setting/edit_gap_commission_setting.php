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
        <label>Earning Name</label>
        <select class="form-control form-control-alternative form-control-alternative-alternative" name="income_name">
            <option selected><?php echo $result->income_name ?></option>
            <option>Level Income</option>
            <option>Matching Income</option>
            <option>Direct Income</option>
            <option>Other Income</option>
            <option>Repurchase Income</option>
        </select>
    </div>
    <input type="hidden" name="id" value="<?php echo $result->id ?>">
    <div class="col-sm-6">
        <label>Commission Percentage</label>
        <input type="text" class="form-control form-control-alternative form-control-alternative-alternative" value="<?php echo set_value('amount', $result->amount) ?>" name="amount">
    </div>
    <div class="col-sm-6">
        <label>Total PV <br/> </label>
        <input type="text" class="form-control form-control-alternative form-control-alternative-alternative" value="<?php echo set_value('total_pv', $result->total_pv) ?>"
               name="total_pv">
    </div>
    <div class="col-sm-6"><br/>
        <input type="submit" class="btn btn-primary" value="Update" onclick="this.value='Saving..'">
    </div>
 
</div>
   <?php echo form_close() ?>

</div>
</div>
</div>
