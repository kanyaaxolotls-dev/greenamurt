  <div class="col">
    <div class="card bg-secondary shadow">  
        <div class="card-header bg-white border-0"> 
        <div class="row align-items-center">
            <div class="col-8"> 
                  <h3 class="mb-0">Editing e-PIN: <?php echo $data->epin ?></h3>
            </div>
        <div class="col-4 text-right"> 
            <a href="<?php echo site_url('admin')?>" class="btn btn-sm btn-primary">Home</a>
        </div>
    </div>
</div> 
<div class="card-body">
<?php echo form_open() ?>
<div class="row">
    <div class="col-sm-4">
        <label>e-PIN Amount*</label>
        <div class="form-group">
            <span class="input-group-addon"><span class="glyphicon glyphicon-usd"></span></span>
            <input type="text" class="form-control" value="<?php echo set_value('amount', $data->amount) ?>"
                   placeholder="For free e-pin enter 0" name="amount">
        </div>
    </div>
    <input type="hidden" name="id" value="<?php echo $data->id ?>">
    <div class="col-sm-4">
        <label>User ID (Whom to issue)*</label>
        <div class="form-group">
            <span class="input-group-addon"><span class="glyphicon glyphicon-user"></span></span>
            <input type="text" value="<?php echo set_value('userid', config_item('ID_EXT') . $data->issue_to) ?>"
                   class="form-control" placeholder="1001" name="userid">
        </div>
    </div>
    <!-- <div class="col-sm-1"></div>
 -->
    <div class="col-sm-3">
        <label>Status*</label>
        <div class="form-group">
            <span class="input-group-addon"><span class="glyphicon glyphicon-arrow-right"></span></span>
            <select name="status" class="form-control">
                <option selected><?php echo $data->status ?></option>
                <option>Used</option>
                <option>Un-used</option>
            </select>
        </div>
    </div>
    <div class="col-sm-5">
        <div>&nbsp;</div>
        <input type="submit" class="btn btn-primary" onclick="this.value='Please Wait..'" value="Edit e-PIN">
    </div>
    <div class="col-sm-1"></div>
</div>
<?php echo form_close() ?>
</div>
</div>
</div>
