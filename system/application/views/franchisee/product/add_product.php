<div class="col">
    <div class="card bg-secondary shadow">  
        <div class="card-header bg-white border-0"> 
        <div class="row align-items-center">
            <div class="col-9">
                  <h4 class="mb-0"><?php echo $title;?></h4>
            </div>
        <div class="col-3 text-right"> 
            <a href="<?php echo site_url('franchisee/index')?>" class="btn btn-sm btn-primary">Home</a>
        </div>
    </div>
</div> 
<div class="card-body">

    <?php echo form_open_multipart() ?>
    <div class="form-group">
        <div class="row">
            <div class="col-sm-4">
                <label>Product/Service Name</label>
                <input type="text" class="form-control form-control-alternative" name="prod_name" value="<?php echo set_value('prod_name') ?>">
            </div>
            <div class="col-sm-4">
                <label>Product/Service Category</label>
                <select class="form-control form-control-alternative" name="category">
                    <?php foreach ($parents as $val) {
                        echo '<option value="' . $val['id'] . '">' . $val['cat_name'] . '</option>';
                    } ?>
                </select>
            </div>
              <div class="col-sm-4">
                    <label>Product Description</label>
                    <textarea class="form-control form-control-alternative" id="editor" name="prod_desc"><?php echo set_value('prod_desc') ?></textarea>
                </div>
        </div> 
        <div class="row">
              
                <div class="col-sm-4">
                    <label>Product MRP</label>
                    <input type="text" class="form-control form-control-alternative" name="prod_price" value="<?php echo set_value('prod_price') ?>">
                </div>
                <div class="col-sm-4">
                    <label>Dealer/Franchisee Price</label>
                    <input type="text" class="form-control form-control-alternative" name="dealer_price" value="<?php echo set_value('dealer_price') ?>">
                </div>
                <div class="col-sm-4"> 
                    <label>Purchase Cost</label>
                    <input type="text" class="form-control form-control-alternative" name="purchase_price" value="<?php echo set_value('purchase_price') ?>">
                </div>
        </div>
        <div class="row">

            <div class="col-sm-4">
                <label>Business Value or PV</label>
                <input type="text" class="form-control form-control-alternative" name="pv" value="<?php echo set_value('pv') ?>">
            </div>
            <div class="col-sm-4">
                <label>Available Qty</label>
                <input type="number" class="form-control form-control-alternative" placeholder="-1 for unlimited" name="qty"
                       value="<?php echo set_value('qty', '-1') ?>">
            </div>
            <div class="col-sm-4">
                <label>GST/TAX (%)</label>
                <input type="text" class="form-control form-control-alternative" name="gst" value="<?php echo set_value('gst') ?>">
            </div>
        </div>

        <div class="row">
            <div class="col-sm-4">
                <label>Product Image</label>
                <input type="file" name="img">
            </div>
            <div class="col-sm-4">
                <label>Show on Registration Form ?</label>
                <input type="checkbox" value="Yes" checked name="join_form"> Yes
            </div>
           
        </div>
    </div>

<!--- COMMISSION PART ----->
<?php if (config_item('fix_income') == 'Yes') { ?>
    <div class="alert alert-danger">
        <strong>Warning !</strong> You have enabled "Give Fix Income" option at Advance Setting section of Business
        Setting. This means, product/service based income setting will not work now. So Income Setting is disabled
        Here..
    </div>
<?php } else { ?>
    
        <div class="form-group collapse" id="commission">
            <p>
                &nbsp;&nbsp;&nbsp;<span style="color: #90111A">(Please leave unnecessary fields blank)</span>
            </p>
            <div class="row">
                <div class="col-sm-4">
                    <label>Direct Income (In <?php echo config_item('currency') ?>)</label>
                    <input type="text" class="form-control form-control-alternative" name="direct_income"
                           value="<?php echo set_value('direct_income') ?>">
                </div>
                <div class="col-sm-4">
                    <label>Level Income (In <?php echo config_item('currency'); ?>). <?php  echo "Generation: ". config_item('gen_list');?></label>
                    <input type="text" class="form-control form-control-alternative" placeholder="Comma separate each level income. eg: 12,3,4"
                           name="level_income" value="<?php echo set_value('level_income') ?>">
                </div>
                <div class="col-sm-4">
                    <label>Matching Income (In <?php echo config_item('currency') ?>)</label>
                    <input type="text" class="form-control form-control-alternative" name="matching_income"
                           value="<?php echo set_value('matching_income') ?>">
                </div>
            </div>
            <div class="row">
                <div class="col-sm-4">
                    <label>Capping Amount (In <?php echo config_item('currency') ?>)</label>
                    <input type="text" class="form-control form-control-alternative" name="capping" value="<?php echo set_value('capping') ?>">
                </div>
                <div class="col-sm-4">
                    <label>ROI (In <?php echo config_item('currency') ?>)</label>
                    <input type="text" class="form-control form-control-alternative" placeholder="Return of Investment"
                           value="<?php echo set_value('roi') ?>" name="roi">
                </div>
                <div class="col-sm-4">
                    <label>ROI Frequency (In Days)</label>
                    <input type="number" class="form-control form-control-alternative" placeholder="How frequently you'll pay ROI" name="roi_frequency"
                           value="<?php echo set_value('roi_frequency') ?>">
                </div>
            </div>
            <div class="row">
                <div class="col-sm-6">
                    <label>ROI Limit (In Number)</label>
                    <input type="number" class="form-control form-control-alternative" placeholder="How many time you'll pay ROI ?" name="roi_limit"
                           value="<?php echo set_value('roi_limit') ?>">
                </div>
            </div>
            <!--- END ----------------->
        </div>
    </div>
    <div class="card-footer">
        <?php } ?>
          <button type="button" class="btn btn-danger btn-block" data-toggle="collapse" data-target="#commission">Add Commission
                    ?
                </button>

         <input type="submit" class="btn btn-success btn-block" value="Create Product" onclick="this.value='Creating..'">
    <?php echo form_close() ?>
    </div>
</div>




</div>
 
</div>
</div>

</div>
 
</div>
</div>
