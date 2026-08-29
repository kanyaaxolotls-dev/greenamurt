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

<div class="card-body">

    <?php echo form_open_multipart() ?>
    <div class="form-group">
       <div class="card" style="background-color: #f7fafc;">
          <div class="card-header"> Product Information</div>
            <div class="card-body">
                <div class="row">
                <div class="col-sm-4">
                    <label class="prolabel">Product/Service Name</label>
                    <input type="text" class="form-control form-control-alternative" name="prod_name" value="<?php echo set_value('prod_name') ?>">
                </div>
                <div class="col-sm-4">
                    <label class="prolabel">Product/Service Category</label>
                    <select class="form-control form-control-alternative" name="category">
                        <?php foreach ($parents as $val) {
                            echo '<option value="' . $val['id'] . '">' . $val['cat_name'] . '</option>';
                        } ?>
                    </select>
                </div>
                  <div class="col-sm-4">
                        <label class="prolabel">Product Description</label>
                        <textarea class="form-control form-control-alternative" id="editor" name="prod_desc"><?php echo set_value('prod_desc') ?></textarea>
                    </div>
                </div> 
            </div>
        </div>
        <br>
          <div class="card" style="background-color: #f7fafc;">
            <div class="card-header"> Product Pricing</div>
                <div class="card-body">
                    <div class="row">
                        <div class="col">
                            <label class="prolabel">Product MRP</label>
                            <input type="text" class="form-control form-control-alternative" name="prod_price" value="<?php echo set_value('prod_price') ?>">
                        </div>
                        <div class="col">
                            <label class="prolabel">Distributing Price</label>
                            <input type="text" class="form-control form-control-alternative" name="dealer_price" value="<?php echo set_value('dealer_price') ?>">
                        </div>
                        <div class="col">
                            <label class="prolabel">Distributing Income</label>
                            <input type="text" class="form-control form-control-alternative" name="distributing_price" value="<?php echo set_value('distributing_price') ?>">
                        </div>
                        <div class="col"> 
                            <label class="prolabel">Purchase Cost</label>
                            <input type="text" class="form-control form-control-alternative" name="purchase_price" value="<?php echo set_value('purchase_price') ?>">
                        </div>
                        <div class="col"> 
                            <label>Franchisee Cost</label>
                            <input type="text" class="form-control form-control-alternative" name="franchisee_price" value="<?php echo set_value('franchisee_price') ?>">
                        </div>
                        <div class="col"> 
                            <label>Franchisee Income</label>
                            <input type="text" class="form-control form-control-alternative" name="franchisee_inc" value="<?php echo set_value('franchisee_inc') ?>">
                        </div>
                        <div class="col"> 
                            <label>Franchisee upline Income <span style="color: red;">Comma separate each level income. eg: 5,3,2</span></label>
                            <input type="text" class="form-control form-control-alternative"  name="upline_franchisee_inc" value="<?php echo set_value('upline_franchisee_inc') ?>">
                        </div>
                    </div>
                </div>
            </div>
        <br>

        <div class="card" style="background-color: #f7fafc;">
            <div class="card-header"> Product Information</div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-sm-3">
                            <label class="prolabel">Business Value or PV</label>
                            <input type="text" class="form-control form-control-alternative" name="pv" value="<?php echo set_value('pv') ?>">
                        </div>
                        <div class="col-sm-3">
                            <label class="prolabel">Available Qty</label>
                            <input type="number" class="form-control form-control-alternative" placeholder="-1 for unlimited" name="qty"
                                   value="<?php echo set_value('qty', '-1') ?>">
                        </div>
                        <div class="col-sm-3">
                            <label class="prolabel">GST/TAX (%)</label>
                            <input type="text" class="form-control form-control-alternative" name="gst" value="<?php echo set_value('gst') ?>">
                        </div>
                         <div class="col-sm-3">
                            <label class="prolabel">Show for Repurchase Product</label>
                            <select class="form-control form-control-alternative" name="show_on_repurchase" id="show_on_repurchase">
                                <option value="yes">Yes</option>
                                <option value="no">No</option> 
                            </select>
                        </div>
                    </div>
                </div>
        </div>
<br>
        <div class="card" style="background-color: #f7fafc;">
            <div class="card-header">
           Product Photo & other
            </div>
        <div class="card-body">
            <div class="row">
                <div class="col-sm-4">
                    <label class="prolabel">Product Image</label>
                    <input type="file" name="img">
                </div>
                <div class="col-sm-4">
                    <label class="prolabel">Show on Registration Form ?</label>
                    <input type="checkbox" value="Yes" checked name="join_form"> Yes
                </div>
               
            </div>
            </div>
        </div>
    </div>

<!--- COMMISSION PART ----->
<?php if (config_item('fix_income') == 'Yes') { ?>
    <div class="alert alert-danger prolabel">
        <strong>Warning !</strong> You have enabled "Give Fix Income" option at Advance Setting section of Business
        Setting. This means, product/service based income setting will not work now. So Income Setting is disabled
        Here..
    </div>
<?php } else { ?>
    
        <div class="form-group collapse" id="commission">
            <p class="prolabel">
            <span style="color: #90111A">(Please leave unnecessary fields blank)</span>
            </p>
            <div class="card" style="background-color: #f7fafc;">
                <div class="card-header">
                   General Income Setting
                </div>
                    <div class="card-body"> 
                        <div class="row">
                            <div class="col-sm-4">
                                <label class="prolabel">Direct Income (In <?php echo config_item('currency') ?>)</label>
                                <input type="text" class="form-control form-control-alternative" name="direct_income"
                                       value="<?php echo set_value('direct_income') ?>">
                            </div>
                            <div class="col-sm-4">
                                <label class="prolabel">Level Income (In <?php echo config_item('currency'); ?>). <?php  echo "Generation: ". config_item('gen_list');?></label>
                                <input type="text" class="form-control form-control-alternative" placeholder="Comma separate each level income. eg: 12,3,4"
                                       name="level_income" value="<?php echo set_value('level_income') ?>">
                            </div> 
                            <div class="col-sm-4">
                                <label class="prolabel">Level Sponsor Income (In <?php echo config_item('currency') ?>)</label>
                                <input type="text" class="form-control form-control-alternative" name="sponser_level_inc" value="<?php echo set_value('sponser_level_inc') ?>">
                        </div>
                    </div>
                </div>
            </div>
        <br>
        <div class="card" style="background-color: #f7fafc;">
            <div class="card-header">
                Matching Income Setting
            </div>
                <div class="card-body">            
                        <div class="row">
                             <div class="col-sm-6">
                                <label class="prolabel">Matching Income (In <?php echo config_item('currency') ?>)</label>
                                <input type="text" class="form-control form-control-alternative" name="matching_income"
                                       value="<?php echo set_value('matching_income') ?>">
                            </div>

                            <div class="col-sm-6">
                                <label class="prolabel">Capping Amount (In <?php echo config_item('currency') ?>)</label>
                                <input type="text" class="form-control form-control-alternative" name="capping" value="<?php echo set_value('capping') ?>">
                            </div>
                          
                     </div>
                </div>
            </div>

            <br>

            <div class="card" style="background-color: #f7fafc;">
                <div class="card-header">
                   ROI & Capping Setting
                </div>
                    <div class="card-body">
                        <div class="row">
                            <div class="col-sm-4">
                                <label class="prolabel">ROI (In <?php echo config_item('currency') ?>)</label>
                                <input type="text" class="form-control form-control-alternative" placeholder="Return of Investment"
                                       value="<?php echo set_value('roi') ?>" name="roi">
                            </div>
                            <div class="col-sm-4">
                                <label class="prolabel">ROI Frequency (In Days)</label>
                                <input type="number" class="form-control form-control-alternative" placeholder="How frequently you'll pay ROI" name="roi_frequency"
                                       value="<?php echo set_value('roi_frequency') ?>">
                            </div>
                            <div class="col-sm-4">
                                <label class="prolabel">ROI Limit (In Number)</label>
                                <input type="number" class="form-control form-control-alternative" placeholder="How many time you'll pay ROI ?" name="roi_limit"
                                       value="<?php echo set_value('roi_limit') ?>">
                            </div>
                        </div>
                    </div>
            </div>
            <!--- END ----------------->
        </div>
    </div>
    <div class="card-footer">
        <?php } ?>
          <button type="button" class="btn btn-danger" data-toggle="collapse" data-target="#commission">Add Commission
                    ?
                </button>

         <input type="submit" class="btn btn-success pull-right" value="Create Product" onclick="this.value='Creating..'">
    <?php echo form_close() ?>
    </div>






</div>
 
</div>
