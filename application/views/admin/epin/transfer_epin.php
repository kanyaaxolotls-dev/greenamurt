  <div class="col">
    <div class="card bg-secondary shadow">  
        <div class="card-header bg-white border-0"> 
        <div class="row align-items-center">
            <div class="col-8">
                  <h3 class="mb-0">Transfer E-Pin / Value Key</h3>
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
            <label>From User ID *</label>
            <div class="form-group">
                <input placeholder="From where to deduct epin" value="<?php echo set_value('from') ?>" class="form-control form-control-alternative form-control-alternative-alternative" name="from">
            </div>
        </div>
        <div class="col-sm-6">
            <div class="form-group">
                <label>To User ID *</label>
                <input placeholder="Where to transfer epins" value="<?php echo set_value('to') ?>" class="form-control form-control-alternative form-control-alternative-alternative"
                       name="to">
                   </div>
            </div> 
            <div class="col-sm-6">
                <label>Select Package / Amount *</label>
                <div class="form-group">
                 <input placeholder="Amount of e-pins" value="<?php echo set_value('amount') ?>" class="form-control form-control-alternative form-control-alternative-alternative"
                       name="amount">
                        <!--<select class="form-control form-control-alternative" id="amount" name="amount">-->
                            <?php 
                            // foreach ($products as $val) {
                            //     echo '<option value="' . $val['id'] . '">' . $val['prod_name'] . '. Price :' . config_item('currency') . number_format($val['prod_price'] + ($val['prod_price'] * $val['gst'] / 100), 2) . ' </option>';
                            // }
                            ?>
                        <!--</select>-->
                   </div>
            </div>
            <div class="col-sm-6">
                <label>Number of Pins *</label>
                <div class="form-group">
                <input placeholder="How many epin to transfer" value="<?php echo set_value('qty') ?>"
                       class="form-control form-control-alternative form-control-alternative-alternative" name="qty">
                   </div>
            </div>
        
        </div>
    </div>
    <div class="card-footer">
         <div class="form-group">
                <input type="submit" class="btn btn-primary btn-block" value="Transfer" onclick="this.value='Transferring..'">
            </div>
    </div>
  </div>
</div>
<?php echo form_close() ?>

