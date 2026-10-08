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
     
        <?php echo form_open_multipart() ?>
        <div class="form-group">
            <div class="row">
                    <div class="col-sm-6">
                        <label>Product/Service Name</label>
                        <input type="text" class="form-control" name="prod_name" value="<?php echo set_value('prod_name', $data->prod_name) ?>">
                    </div>
                    <input type="hidden" name="id" value="<?php echo $data->id ?>">
                    <input type="hidden" name="image" value="<?php echo $data->image ?>">
                    <div class="col-sm-6">
                        <label>Product/Service Category</label>
                        <select class="form-control" name="category">
                            <option selected value="<?php $category = set_value('category', $data->category);
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
                              name="prod_desc"><?php echo set_value('prod_desc', $data->prod_desc) ?></textarea>
                </div>
            </div>

            <div class="row mt-2">
                    <div class="col">
                        <label>Product MRP</label>
                        <input type="text" class="form-control" name="prod_price"
                               value="<?php echo set_value('prod_price', $data->prod_price) ?>">
                    </div>
                    <div class="col">
                        <label>Distributing Price</label>
                        <input type="text" class="form-control" name="dealer_price"
                               value="<?php echo set_value('dealer_price', $data->dealer_price) ?>">
                    </div>
                            <div class="col">
                                <label class="prolabel">Distributing Income</label>
                                <input type="text" class="form-control form-control-alternative" name="distributing_price" value="<?php echo set_value('distributing_price', $data->distributing_price) ?>">
                            </div>
                     <div class="col"> 
                        <label>Purchase Cost</label>
                        <input type="text" class="form-control" name="purchase_price" value="<?php echo set_value('purchase_price',$data->purchase_price) ?>">
                    </div>

                    <div class="col"> 
                        <label>Franchisee Cost</label>
                        <input type="text" class="form-control" name="franchisee_price" value="<?php echo set_value('franchisee_price',$data->franchisee_price) ?>">
                    </div>

                    <div class="col"> 
                        <label>Franchisee Income</label>
                        <input type="text" class="form-control form-control-alternative" name="franchisee_inc" value="<?php echo set_value('fran_inc',$data->fran_inc) ?>">
                    </div>
                    <div class="col"> 
                        <label>Franchisee upline Income <span style="color: red;">Comma separate each level income. eg: 5,3,2</span></label>
                        <input type="text" class="form-control form-control-alternative"  name="upline_franchisee_inc" value="<?php echo set_value('upline_franchisee_inc',$data->upline_fran_inc) ?>">
                    </div>
            </div>

            <div class="row">
                    <div class="col-sm-4">
                        <label>BV/PV</label>
                        <input type="text" class="form-control" name="pv" value="<?php echo set_value('pv', $data->pv) ?>">
                    </div>
                    <div class="col-sm-4">
                        <label>Stock Qty (-1 for no limit)</label>
                        <input type="number" class="form-control" placeholder="-1 for unlimited" name="qty"
                               value="<?php echo set_value('qty', $data->qty) ?>">
                    </div>
                    <div class="col-sm-4">
                        <label>GST/TAX (%)</label>
                        <input type="text" class="form-control" name="gst" value="<?php echo set_value('gst', $data->gst) ?>">
                    </div>
            </div>

            <div class="row">
                    <div class="col-sm-4">
                        <label>Product Photo (Please leave blank if you donot want to change image)</label>
                        <input type="file" name="img">
                    </div>
                    <div class="col-sm-4">
                        <label>Show on Registration Form ?</label>
                        <input type="checkbox" value="Yes" name="join_form" <?php if ($data->show_on_regform == "Yes") {
                            echo 'checked';
                        } ?>> Yes
                    </div>
                    <div class="col-sm-4"><br>
                        <button type="button" class="btn btn-danger" data-toggle="collapse" data-target="#commission">SET
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
                               value="<?php echo set_value('direct_income', $data->direct_income) ?>">
                    </div>
                    <div class="col-sm-4">
                        <label>Level Income (In <?php echo config_item('currency') ?>)</label>
                        <input type="text" class="form-control" placeholder="Comma separate each level income. eg: 12,3,4"
                               name="level_income" value="<?php echo set_value('level_income', $data->level_income) ?>">
                    </div>
                    <div class="col-sm-4">
                        <label>Matching Income (In <?php echo config_item('currency') ?>)</label>
                        <input type="text" class="form-control" name="matching_income"
                               value="<?php echo set_value('matching_income', $data->matching_income) ?>">
                    </div>
            </div>
            <div class="row">
                    <div class="col-sm-4">
                        <label>Capping Amount (In <?php echo config_item('currency') ?>)</label>
                        <input type="text" class="form-control" name="capping"
                               value="<?php echo set_value('capping', $data->capping) ?>">
                    </div>
                    <div class="col-sm-4">
                        <label>ROI (In <?php echo config_item('currency') ?>)</label>
                        <input type="text" class="form-control" placeholder="Return of Investment"
                               value="<?php echo set_value('roi', $data->roi) ?>" name="roi">
                    </div>
                    <div class="col-sm-4">
                        <label>ROI Frequency (In Days)</label>
                        <input type="number" class="form-control" placeholder="How frequently you'll pay ROI" name="roi_frequency"
                               value="<?php echo set_value('roi_frequency', $data->roi_frequency) ?>">
                    </div>
            </div>
            <div class="row">
                    <div class="col-sm-4">
                        <label>ROI Limit (In Number)</label>
                        <input type="number" class="form-control" placeholder="How many time you'll pay ROI ?" name="roi_limit"
                               value="<?php echo set_value('roi_limit', $data->roi_limit) ?>">
                    </div>
                    <div class="col-sm-4">
                        <label>Sponsor Amount (In <?php echo config_item('currency') ?>)</label>
                        <input type="text" class="form-control form-control-alternative" name="sponser_level_inc" value="<?php echo set_value('sponser_level_inc', $data->sponser_level_inc) ?>">
                    </div>
                    <div class="col-sm-4">
                        <label>Selling Status</label>
                        <select name="status" class="form-control">
                            <option><?php echo $data->status ?></option>
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
        <input type="submit" class="btn btn-primary" value="Update" onclick="this.value='Updating..'">
    </div>
</div>
<?php } ?>
<?php echo form_close() ?>

</div>

