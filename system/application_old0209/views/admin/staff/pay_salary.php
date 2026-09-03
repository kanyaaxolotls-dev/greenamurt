<div class="col">
        <div class="card bg-secondary shadow">  
            <div class="card-header bg-white border-0"> 
                <div class="row align-items-center">
                    <div class="col-8"> 
                          <h3 class="mb-0">Staff Salary Managment</h3>
                    </div>
                    <div class="col-4 text-right">  
                     
                       <a href="<?php echo site_url('admin')?>" class="btn btn-sm btn-primary">Home</a>
                    </div> 
                </div> 
            </div>  
        <div class="card-body">
               <?php echo form_open() ?>
<div class="row">
 
    <div class="col-sm-6">
        <label>Select Staff</label>
        <select name="staff_id" class="form-control">
            <?php foreach ($staff as $e) { ?>
                <option value="<?php echo $e->id ?>"><?php echo $e->name ?> (<?php echo $e->phone ?>)</option>
            <?php } ?>
        </select>
    </div>
    <div class="col-sm-6">
        <label>Salary (in <?php echo config_item('currency') ?> )</label>
        <input type="text" class="form-control" value="<?php echo set_value('salary') ?>" name="salary">
    </div>

    <div class="col-sm-6">
        <label>Salary Month</label>
        <select name="month" class="form-control">
            <option value="01">Jan</option>
            <option value="02">Feb</option>
            <option value="03">Mar</option>
            <option value="04">Apr</option>
            <option value="05">May</option>
            <option value="06">Jun</option>
            <option value="07">Jul</option>
            <option value="08">Aug</option>
            <option value="09">Sep</option>
            <option value="10">Oct</option>
            <option value="11">Nov</option>
            <option value="12">Dec</option>
        </select>
    </div>
    <div class="col-sm-6">
        <label>Salary Year</label>
        <select name="year" class="form-control">
            <option><?php echo date('Y', strtotime('last year')) ?></option>
            <option><?php echo date('Y') ?></option>
            <option><?php echo date('Y', strtotime('next year')) ?></option>
        </select>
    </div>
    <div class="col-sm-6">
        <label>Salary Pay Date</label>
        <input type="text" class="form-control datepicker" name="paydate" value="<?php echo date('Y-m-d') ?>">
    </div>

    <div class="col-sm-6"><br/>
        <input type="submit" class="btn btn-success" value="Pay &rarr;" onclick="this.value='Just a Moment..'">
    </div>
  
</div>  <?php echo form_close() ?>
</div>
</div>
</div>
