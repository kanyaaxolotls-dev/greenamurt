  <div class="col">
    <div class="card bg-secondary shadow">  
        <div class="card-header bg-white border-0"> 
        <div class="row align-items-center">
            <div class="col-8">
                  <h3 class="mb-0">Create E-Pin / Value Key</h3>
            </div>
        <div class="col-4 text-right"> 
            <a href="<?php echo site_url('admin/index')?>" class="btn btn-sm btn-primary">Home</a>
        </div>
    </div>

<div class="card-body">

<?php echo form_open() ?>

<div class="row">
     
    <div class="col-sm-5">
        <label>e-PIN Amount*</label>
        <div class="form-group">
            <span class="input-group-addon"><span class="glyphicon glyphicon-usd"></span></span>

            <input type="text"  class="form-control form-control-alternative form-control-alternative-alternative" placeholder="Enter Amount" name="amount">

        </div>
    </div>
    <div class="col-sm-5">
        <label>User ID *</label>
        <div class="input-control">
            <span class="input-group-addon"><span class="glyphicon glyphicon-user"></span></span>
            <input type="text" value="<?php echo set_value('userid') ?>" class="form-control form-control-alternative form-control-alternative-alternative" placeholder="1001"
                   name="userid">
        </div>
    </div>
    <div class="col-sm-1"></div>
</div>
<div>&nbsp;</div>
<div class="row">
    
    <div class="col-sm-5">
        <label>Number of e-PINs*</label>
        <div class="input-control">
            <span class="input-group-addon"><span class="glyphicon glyphicon-arrow-right"></span></span>
            <input type="number" value="<?php echo set_value('number') ?>" placeholder="Maximum 999 epin at a time"
                   class="form-control form-control-alternative form-control-alternative-alternative" name="number">
        </div>
    </div>
    <div class="col-sm-5">
        <label>e-PIN Type*</label>
        <div class="input-control">
            <span class="input-group-addon"><span class="glyphicon glyphicon-adjust"></span></span>
            <select class="form-control form-control-alternative form-control-alternative-alternative" name="type">
                <option>Single Use</option>
                <!--<option>Multi Use</option>-->
            </select>
        </div>
    </div>
    
</div>

</div>
<div class="card-footer">
     <input type="submit" class="btn btn-danger" onclick="this.value='Please Wait..'" value="Generate e-PINs">
</div>
<?php echo form_close() ?>


</div>
</div>
