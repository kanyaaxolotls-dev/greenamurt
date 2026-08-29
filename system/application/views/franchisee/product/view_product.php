<div class="col">
    <div class="card bg-secondary shadow mb-4">
        <div class="card-header border-bottom d-flex justify-content-between align-items-center">
            <h4 class="mb-0"><?php echo $title; ?></h4>
            <a href="<?php echo site_url('franchisee') ?>" class="btn btn-sm btn-primary">Home</a>
        </div>

        <div class="card-body">
            <?php $product = $this->db_model->select_multi('*', 'product', array('id' => $this->uri->segment(3))); ?>

            <!-- Basic Product Info -->
            <h5 class="mb-3 text-primary">Product Information</h5>
            <div class="row">
                <div class="col-md-6 mb-2"><strong>Product Name:</strong> <?php echo $product->prod_name ?></div>
                <div class="col-md-6 mb-2"><strong>MRP:</strong> <?php echo config_item('currency') . $product->prod_price ?></div>
                <div class="col-md-6 mb-2"><strong>Member Price:</strong> <?php echo config_item('currency') . $product->dealer_price ?></div>
                <div class="col-md-6 mb-2"><strong>Franchisee Price:</strong> <?php echo config_item('currency') . $product->franchisee_price ?></div>
                <div class="col-md-6 mb-2"><strong>BV/PV:</strong> <?php echo $product->pv ?></div>
                <div class="col-md-6 mb-2"><strong>GST / Tax:</strong> <?php echo $product->gst ?>%</div>
                <div class="col-md-6 mb-2"><strong>Available Qty:</strong> <?php echo $product->qty ?></div>
            </div>

            <!-- Product Description -->
            <div class="my-4">
                <h5 class="text-primary">Product Description</h5>
                <p><?php echo $product->prod_desc ?></p>
            </div>

            <!-- Other Details -->
            <h5 class="mb-3 text-primary">Other Information</h5>
            <div class="row">
                <div class="col-md-6 mb-2"><strong>Franchisee Income:</strong> <?php echo $product->fran_inc ?></div>
                <div class="col-md-6 mb-2"><strong>Selling Status:</strong> <?php echo $product->status ?></div>
            </div>

            <!-- Product Image -->
            <div class="text-center mt-4">
                <img src="<?php echo $product->image ? base_url('uploads/' . $product->image) : base_url('uploads/default.jpg'); ?>"
                     class="img-thumbnail" style="max-width: 300px;">
                <p class="mt-2"><strong>Product Photo</strong></p>
            </div>
        </div>
    </div>
</div>
