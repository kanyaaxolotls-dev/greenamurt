<th scope="col">SN</th>
                        <th scope="col">Task Name</th>
                        <th scope="col">Description</th>
                        <th scope="col">Category</th>
                        <th scope="col">Link</th>
                        <th scope="col">amount</th>

                        <th scope="col">Expiry date</th>
                        <th scope="col">Activation Date</th>
                        <th scope="col">Created date</th>
                        <th scope="col">Image </th>
                        <th scope="col">status </th>
                        <th scope="col">Actions</th>

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

    <div class="row">
        <div class="col-sm-6"><label>Product Name: </label><?php echo $data->prod_name ?></div>
        <div class="col-sm-6"><label>Member Price: </label> <?php echo config_item('currency') . $data->prod_price ?>
        </div>
        <div class="col-sm-6"><label>Dealer/Franchisee
                Price: </label> <?php echo config_item('currency') . $data->dealer_price ?></div>
        <div class="col-sm-6"><label>BV/PV: </label> <?php echo $data->pv ?></div>
        <div class="col-sm-6"><label>GST / Tax: </label> <?php echo $data->gst ?></div>
        <div class="col-sm-6"><label>Available Qty: </label> <?php echo $data->qty ?></div>
    </div>  

    <div class="row">
        <div class="col-sm-12"><label>Product Detail: </label> <?php echo $data->prod_desc ?></div>
    </div>
     
    <div class="row">
        <div class="col-sm-6"><label>Direct Referral Income: </label> <?php echo $data->direct_income ?></div>
        <div class="col-sm-6"><label>Level Incomes: </label> <?php echo $data->level_income ?></div>
        <div class="col-sm-6"><label>Matching Incomes: </label> <?php echo $data->matching_income ?></div>
        <div class="col-sm-6"><label>Matching Income Capping: </label> <?php echo $data->capping ?></div>
        <div class="col-sm-6"><label>ROI Income: </label> <?php echo $data->roi ?></div>
        <div class="col-sm-6"><label>ROI payout frequency: </label> Every <?php echo $data->roi_frequency ?> days</div>
        <div class="col-sm-6"><label>ROI Validity Limit: </label> <?php echo $data->roi_limit ?> days</div>
        <div class="col-sm-6"><label>ROI Validity Limit: </label> <?php echo $data->roi_limit ?> days</div>
        <div class="col-sm-6"><label>Total Sold: </label> <?php echo $data->sold_qty ?> qty</div>
        <div class="col-sm-6"><label>Selling Status: </label> <?php echo $data->status ?></div>
    </div>
  <br>
    <div align="center">
        <img src="<?php echo $data->image ? base_url('uploads/' . $data->image) : base_url('uploads/default.jpg'); ?>"
             class="img-responsive img-thumbnail" style="max-width: 300px">
            Product Photo
    </div>
</div>
</div>
</div>