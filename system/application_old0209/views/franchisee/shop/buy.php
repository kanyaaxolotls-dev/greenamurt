<div class="row g-4">
    <?php foreach ($products as $product) { ?>
        <div class="col-12 col-md-6 col-lg-4 mb-4">
            <div class="card h-100 shadow-sm border-0">
                <img class="card-img-top" src="<?php echo base_url('uploads/' . ($product['image'] ? $product['image'] : 'default.jpg')) ?>"alt="Product Image">
                <div class="card-body d-flex flex-column">
                    <h5 class="card-title fw-bold mb-2"><?php echo $product['prod_name'] ?></h5>
                    <p class="text-dark mb-1">MRP: <strong>INR <?php echo number_format($product['prod_price'] + $product['prod_price'] * $product['gst'] / 100, 2) ?></strong></p>
                    <p class="text-dark mb-3">Dealer Price: <strong>INR <?php echo number_format($product['dealer_price'] + $product['prod_price'] * $product['gst'] / 100, 2) ?></strong></p>
                    <div class="d-flex justify-content-between align-items-center mb-3">
                        <span class="badge bg-success">Available Qty: <?php echo $product['available_qty']; ?></span>
                        <span class="badge bg-danger">Sold Qty: <?php echo $product['sold_qty']; ?></span>
                    </div>
                    <?php if ($product['available_qty'] <= 5 && $product['available_qty'] > 0) { ?>
                        <button class="btn btn-warning mt-auto w-100"><i class="fas fa-exclamation-triangle me-2"></i>Low Stock</button>
                    <?php } elseif ($product['available_qty'] > 5) { ?>
                        <button class="btn btn-success mt-auto w-100"><i class="fas fa-check-circle me-2"></i>Available</button>
                    <?php } else { ?>
                        <button class="btn btn-danger mt-auto w-100"><i class="fas fa-times-circle me-2"></i>Out Of Stock</button>
                    <?php } ?>
                </div>
            </div>
        </div>
    <?php } ?>
</div>
