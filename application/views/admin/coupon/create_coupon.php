
  <div class="col">
    <div class="card bg-secondary shadow">  
        <div class="card-header bg-white border-0">  
        <div class="row align-items-center">
            <div class="col-8">
                  <h3 class="mb-0"><?php echo $title ?></h3>
            </div>
        <div class="col-4 text-right"> 
            <a href="<?php echo site_url('admin')?>" class="btn btn-sm btn-primary">Home</a>
        </div>
    </div>
</div> 
    <?php echo form_open() ?>
    <div class="col-sm-12">
        <label>User ID</label>
        <input type="text" class="form-control" value="<?php echo set_value('userid') ?>" name="userid">
    </div>
    <div class="col-sm-12">
        <label>Coupon Category</label>
        <select class="form-control" value="<?php echo set_value('coupon_cat') ?>" name="coupon_cat">
            <?php
            foreach ($result as $e) {
                echo '<option value="' . $e->id . '">' . $e->cat_name . '</option>';
            }
            ?>
        </select>
    </div>
    <div class="col-sm-12">
        <label>Coupon Amount</label>
        <input type="text" class="form-control" value="<?php echo set_value('coupon_amt') ?>" name="coupon_amt">
    </div>
    <div class="col-sm-12">
        <label>Coupon Code</label>
        <input type="text" class="form-control" value="<?php echo $cp ?>"  name="coupon_code">
    </div>
    <div class="col-sm-12 mb-3"><br/>
        <input type="submit" class="btn btn-success" value="Create" onclick="this.value='Generating..'">
    </div>
    <?php echo form_close() ?>
</div>
</div>

