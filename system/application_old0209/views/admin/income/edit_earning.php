
<?php echo form_open() ?>
<div class="col">
    <div class="card bg-secondary shadow">  
        <div class="card-header bg-white border-0"> 
        <div class="row align-items-center">
            <div class="col-8"> 
                  <h3 class="mb-0">Edit Earning</h3>
            </div>
        <div class="col-4 text-right"> 
            <a href="<?php echo site_url('income/view_earning')?>" class="btn btn-sm btn-primary">Home</a>
        </div> 
    </div>
</div>  
<div class="card-body">
<div class="row">
 
    <div class="col-sm-5">
        <label>Earning Amount*</label>
        
        <div class="form-group">
            <span class="input-group-addon"><span class="glyphicon glyphicon-usd"></span></span>
            <input type="text" class="form-control form-control-alternative" value="<?php echo set_value('amount', $data->amount) ?>"
                   name="amount">
        </div>


    </div>
    <input type="hidden" name="id" value="<?php echo $data->id ?>">
    <div class="col-sm-5">
        <label>Date*</label>
        <div class="form-group">
            <span class="input-group-addon"><span class="glyphicon glyphicon-calendar"></span></span>
            <input type="text" value="<?php echo set_value('userid', $data->date) ?>"
                   class="form-control form-control-alternative datepicker" name="date">
        </div>
    </div>
    <div class="col-sm-1"></div>
</div>
<div>&nbsp;</div>
<div class="row">

    <div class="col-sm-5">
        <label>Status*</label>
        <div class="form-group">
            <span class="input-group-addon"><span class="glyphicon glyphicon-arrow-right"></span></span>
            <select name="status" class="form-control form-control-alternative">
                <option selected><?php echo $data->status ?></option>
                <option>Pending</option>
                <option>Paid</option>
            </select>
        </div>
    </div>
    <div class="col-sm-5">
        <div>&nbsp;</div>
        <input type="submit" class="btn btn-primary" onclick="this.value='Working..'" value="Update Earning">
    </div>
    <div class="col-sm-1"></div>
</div>
</div>
</div>
</div>

<?php echo form_close() ?>

