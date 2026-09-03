<?php

?>
<div class="alert alert-danger">
    <strong>Warning !</strong> if you don't know what you are doing. Please do not modify this setting. Call our support
    team if you have any query or you want to learn to how to use this setting.
</div>
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
                <label>Earning Name</label>
                <select class="form-control form-control-alternative form-control-alternative-alternative" name="income_name">
                    <option>Level Income</option>
                    <option>Matching Income</option>
                    <option>Direct Income</option>
                    <option>Other Income</option>
                    <option>Repurchase Income</option>
                </select>
            </div>
            <div class="col-sm-4">
                <label>Commission Percentage</label>
                <input type="text" class="form-control form-control-alternative form-control-alternative-alternative" value="<?php echo set_value('amount') ?>" name="amount">
            </div>
            <div class="col-sm-2">
                <label>Total PV <br/> </label>
                <input type="text" class="form-control form-control-alternative form-control-alternative-alternative" value="<?php echo set_value('total_pv', '0') ?>"
                       name="total_pv">
            </div>
            <div class="col-sm-2"><br/>
                <input type="submit" class="btn btn-primary" value="Save" onclick="this.value='Saving..'">
            </div>
          
        </div><!------------- MANAGE REWARDS -------------------------------->
  <?php echo form_close() ?>
<br>

  <div class="table-responsive">
                <table class="table align-items-center table-flush">
                   <thead class="thead-light">
        <tr>
            <th>Sn./Level</th>
            <th>Income Name</th>
            <th>Total Distributor/PV/BV</th>
            <th>Commission (%)</th>
            <th>Action</th>
        </tr>
    </thead>
        <?php
        $sn = 1;
        foreach ($result as $e) { ?>
            <tr>
                <td><?php echo $sn++; ?></td>
                <td><?php echo $e->income_name; ?></td>
                <td><?php echo $e->total_pv; ?></td>
                <td><?php echo $e->amount; ?> %</td>
                <td>
                    <a class="btn btn-success btn-sm" href="<?php echo site_url('income/edit-gap-commission-setting/' . $e->id); ?>"
                      >Edit</a>
                    <a class="btn btn-danger btn-sm" onclick="return confirm('Are you sure you want to delete this Income ?')"
                       href="<?php echo site_url('income/remove-gap-income/' . $e->id); ?>"
                       >Delete</a>
                </td>
            </tr>
        <?php } ?>
    </table>
</div>
</div>
</div>
</div>
