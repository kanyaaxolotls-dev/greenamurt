<?php if (isset($product_top) && count($product_top)) { ?>
    <div class="row">
        <?php foreach ($product_top as $e) { ?>
            <div class="col-12 col-sm-6 col-lg-3 mb-4">
                <div class="card prodcard h-100 shadow-sm border-0">
                    <img class="card-img-top" src="<?php echo base_url('uploads/' . ($e->image ? $e->image : 'default.jpg')) ?>" style="height: 180px; object-fit: cover;" alt="Product image">
                    
                    <div class="card-body text-center text-dark p-3 pb-0">
                        <h5 class="card-title fw-bold mb-2"><?php echo $e->prod_name ?></h5>
                        <p class="text-dark mb-1">MRP: <strong>INR <?php echo number_format($e->prod_price + $e->prod_price * $e->gst / 100, 2); ?></strong></p>
                        <p class="text-dark mb-1">Franchisee Price: INR <?php echo number_format($e->franchisee_price, 2); ?></p>
                        <p class="text-dark mb-0"><?php echo $e->prod_desc ?></p>
                    </div>

                    <div class="card-footer bg-transparent border-0 px-3 pb-3 pt-2">
                        <a href="<?php echo site_url('franchisee/buy_2/' . $e->id) ?>" class="btn btn-primary btn-sm w-100 mb-2">
                            <i class="fas fa-shopping-cart me-1"></i> Add to Cart
                        </a>
                        <a href="<?php echo site_url('franchisee/view_prod/' . $e->id) ?>" class="btn btn-success btn-sm w-100">
                            <i class="fas fa-eye me-1"></i> View
                        </a>
                    </div>
                </div>
            </div>
        <?php } ?>
    </div>
<?php } ?>
