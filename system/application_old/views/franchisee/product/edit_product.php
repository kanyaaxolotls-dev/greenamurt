<div class="col">
    <div class="card bg-secondary shadow">  
        <div class="card-header bg-white border-0"> 
        <div class="row align-items-center">
            <div class="col-9">
                  <h4 class="mb-0"><?php echo $title;?></h4>
            </div>
        <div class="col-3 text-right"> 
            <a href="<?php echo site_url('member/index')?>" class="btn btn-sm btn-primary">Home</a>
        </div>
    </div>
</div> <?php 
   $product_data_edit      = $this->db_model->select_multi('*', 'product', array('id' => $this->uri->segment(3)));
//var_dump($product_data_edit);die();?>
<div class="card-body">
     
        <?php echo form_open_multipart() ?>
        <div class="form-group">
            <div class="row">
                    <div class="col-sm-6">
                        <label>Product/Service Name</label> 
                       
                        <input type="text" class="form-control" name="prod_name"
                               value="<?php echo set_value('prod_name', $product_data_edit->prod_name) ?>">
                    </div>
                    <input type="hidden" name="id" value="<?php echo $product_data_edit->id ?>">
                    <input type="hidden" name="image" value="<?php echo $product_data_edit->image ?>">
                    <div class="col-sm-6">
                        <label>Product/Service Category</label>
                        <select class="form-control" name="category">
                            <option selected value="<?php $category = set_value('category', $product_data_edit>category);
                            echo $category ?>"><?php echo $this->db_model->select('cat_name', 'product_categories', array('id' => $category)) ?></option>
                            <?php foreach ($parents as $val) {
                                echo '<option value="' . $val['id'] . '">' . $val['cat_name'] . '</option>';
                            } ?>
                        </select>
                    </div>
            </div>
            <div class="row">
                <div class="col-sm-12">
                    <label>Product/Service Description</label>
                    <textarea class="form-control" id="editor"
                              name="prod_desc"><?php echo set_value('prod_desc', $product_data_edit->prod_desc) ?></textarea>
                </div>
            </div>

            <div class="row">
                    <div class="col-sm-4">
                        <label>Product/Service Price (Member Price)</label>
                        <input type="text" class="form-control" name="prod_price"
                               value="<?php echo set_value('prod_price', $product_data_edit->prod_price) ?>">
                    </div>
                    <div class="col-sm-4">
                        <label>Dealer Price (Franchisee Price)</label>
                        <input type="text" class="form-control" name="dealer_price"
                               value="<?php echo set_value('dealer_price', $product_data_edit->dealer_price) ?>">
                    </div>
                     <div class="col-sm-4"> 
                        <label>Purchase Cost</label>
                        <input type="text" class="form-control" name="purchase_price" value="<?php echo set_value('purchase_price',$product_data_edit->purchase_price) ?>">
                    </div>
            </div>

            <div class="row">
                    <div class="col-sm-4">
                        <label>BV/PV</label>
                        <input type="text" class="form-control" name="pv" value="<?php echo set_value('pv', $product_data_edit->pv) ?>">
                    </div>
                    <div class="col-sm-4">
                        <label>Stock Qty (-1 for no limit)</label>
                        <input type="number" class="form-control" placeholder="-1 for unlimited" name="qty"
                               value="<?php echo set_value('qty', $product_data_edit->qty) ?>">
                    </div>
                    <div class="col-sm-4">
                        <label>GST/TAX (%)</label>
                        <input type="text" class="form-control" name="gst" value="<?php echo set_value('gst', $product_data_edit->gst) ?>">
                    </div>
            </div>

            <div class="row">
                    <div class="col-sm-4">
                        <label>Product Photo (Please leave blank if you donot want to change image)</label>
                        <input type="file" name="img">
                    </div>
                    <div class="col-sm-4">
                        <label>Show on Registration Form ?</label>
                        <input type="checkbox" value="Yes" name="join_form" <?php if ($product_data_edit->show_on_regform == "Yes") {
                            echo 'checked';
                        } ?>> Yes
                    </div>
                    <div class="col-sm-4"><br>
                        <button type="button" class="btn btn-danger btn-block" data-toggle="collapse" data-target="#commission">SET
                            Commission
                        </button>
                    </div>
            </div>
        </div>


<?php if (config_item('fix_income') == 'Yes') { ?>
    <div class="alert alert-danger">
        <strong>Warning !</strong> You have enabled "Give Fix Income" option at Advance Setting section of Business
        Setting. This means, product/service based income setting will not work now. So Income Setting is disabled
        Here..
    </div>
<?php } else { ?>

        <!--- COMMISSION PART ----->
        <div class="form-group collapse" id="commission">
            <p><span style="color: #90111A">(Please leave unnecessary fields blank)</span>
            </p>
            <div class="row">
                    <div class="col-sm-4">
                        <label>Direct Income (In <?php echo config_item('currency') ?>)</label>
                        <input type="text" class="form-control" name="direct_income"
                               value="<?php echo set_value('direct_income', $product_data_edit->direct_income) ?>">
                    </div>
                    <div class="col-sm-4">
                        <label>Level Income (In <?php echo config_item('currency') ?>)</label>
                        <input type="text" class="form-control" placeholder="Comma separate each level income. eg: 12,3,4"
                               name="level_income" value="<?php echo set_value('level_income', $product_data_edit->level_income) ?>">
                    </div>
                    <div class="col-sm-4">
                        <label>Matching Income (In <?php echo config_item('currency') ?>)</label>
                        <input type="text" class="form-control" name="matching_income"
                               value="<?php echo set_value('matching_income', $product_data_edit->matching_income) ?>">
                    </div>
            </div>
            <div class="row">
                    <div class="col-sm-4">
                        <label>Capping Amount (In <?php echo config_item('currency') ?>)</label>
                        <input type="text" class="form-control" name="capping"
                               value="<?php echo set_value('capping', $product_data_edit->capping) ?>">
                    </div>
                    <div class="col-sm-4">
                        <label>ROI (In <?php echo config_item('currency') ?>)</label>
                        <input type="text" class="form-control" placeholder="Return of Investment"
                               value="<?php echo set_value('roi', $product_data_edit->roi) ?>" name="roi">
                    </div>
                    <div class="col-sm-4">
                        <label>ROI Frequency (In Days)</label>
                        <input type="number" class="form-control" placeholder="How frequently you'll pay ROI" name="roi_frequency"
                               value="<?php echo set_value('roi_frequency', $product_data_edit->roi_frequency) ?>">
                    </div>
            </div>
            <div class="row">
                    <div class="col-sm-4">
                        <label>ROI Limit (In Number)</label>
                        <input type="number" class="form-control" placeholder="How many time you'll pay ROI ?" name="roi_limit"
                               value="<?php echo set_value('roi_limit', $product_data_edit->roi_limit) ?>">
                    </div>
                    <div class="col-sm-4">
                        <label>Selling Status</label>
                        <select name="status" class="form-control">
                            <option><?php echo $product_data_edit->status ?></option>
                            <option>Selling</option>
                            <option>Not-Selling</option>
                        </select>
                    </div>
            </div>
            <!--- END ----------------->
        </div>
    </div>


<div class="card-footer">
    <div class="col-sm-12"><br/>
        <input type="submit" class="btn btn-primary btn-block " value="Update" onclick="this.value='Updating..'">
    </div>
</div>
<?php } ?>
<?php echo form_close() ?>

</div>
</div>
</div>
</div>
</div>
</div>


