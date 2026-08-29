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

<div class="alert alert-danger">
    <strong>Warning !</strong> if you don't know what you are doing. Please do not modify this setting. Call our support
    team if you have any query or you want to learn to how to use this setting. From here you can set when an user will
    get
    commission and on which condition basis.
</div>
<?php echo form_open() ?>
<div class="row">
    
    <div class="col-sm-6">
        <label>Matching Patern</label>
        <select class="form-control form-control-alternative form-control-alternative-alternative" name="income_name">
            <option selected><?php echo $result->income_name ?></option>
            <option>Total Member</option>
            <option>Matching Income</option>
            <option>Direct Income</option>
            <option>Other Income</option>
        </select>
    </div>
    <div class="col-sm-6">
        <label>Earning Name</label>
        <select class="form-control form-control-alternative form-control-alternative-alternative" name="income_name">
            <option selected><?php echo $result->income_name ?></option>
            <option>Level Income</option>
            <option>Matching Income</option>
            <option>Direct Income</option>
            <option>Other Income</option>
        </select>
    </div>
    <div class="col-sm-6">
        <label>Counting Based On</label>
        <select class="form-control form-control-alternative form-control-alternative-alternative" name="based_on">
            <option selected><?php echo $result->based_on ?></option>
            <option>Member</option>
            <option>PV</option>
        </select>
    </div> 
    <div class="col-sm-6">
        <label>Earning Amount</label>
        <input type="text" class="form-control form-control-alternative form-control-alternative-alternative" value="<?php echo set_value('amount', $result->amount) ?>" name="amount">
    </div>
    <div class="col-sm-6">
        <label>Achieve Duration <br/> </label>
        <input type="text" class="form-control form-control-alternative form-control-alternative-alternative" value="<?php echo set_value('income_duration', $result->income_duration) ?>"
               name="income_duration"><span style="font-size: 11px">( Within how many days he/she should achieve this  ? 0 for no duration )</span>
    </div>
    <div class="col-sm-6">
        <?php
        ?>
        <?php foreach ($leg as $key => $val) { ?>
            <label><?php echo $key ?></label><input type="text" class="form-control form-control-alternative form-control-alternative-alternative"
                                                    placeholder="How many people at <?php echo $key ?> side ?"
                                                    name="<?php echo $key ?>" value="<?php echo set_value($key, $result->$key) ?>">
        <?php } ?>
    </div>
     <div class="col-sm-4">
        <label>Achieve Duration <br/> </label>
        <input type="text" class="form-control form-control-alternative form-control-alternative-alternative" value="<?php echo set_value('income_duration', '0') ?>"
               name="income_duration"><span style="font-size: 11px">( Within how many days he/she should achieve this  ? 0 for no duration )</span>
    </div>
    <div class="col-sm-4">
        <?php
        ?>
        <?php foreach ($leg as $key => $val) { ?>
            <label>Total People/PV at <?php echo $key ?> Side</label><input type="text" class="form-control form-control-alternative form-control-alternative-alternative"
                                                    placeholder="How many people or PV at <?php echo $key ?> side ?"
                                                    name="<?php echo $key ?>" value="<?php echo set_value($key) ?>">
        <?php } ?>
    </div>
    <input type="hidden" name="id" value="<?php echo $result->id ?>">
    <div class="col-sm-6"><br/>
        <input type="submit" class="btn btn-success" value="Save" onclick="this.value='Saving..'">
    </div>
</div>
    <?php echo form_close() ?>
</div>
</div>
</div>