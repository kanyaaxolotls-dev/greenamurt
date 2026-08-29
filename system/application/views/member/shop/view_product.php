    <!-- start page title -->
                        <div class="row">
                            <div class="col-12">
                                <div class="page-title-box d-sm-flex align-items-center justify-content-between">
                                    <h4 class="mb-sm-0 font-size-18">Product Detail</h4>

                                    <div class="page-title-right">
                                        <ol class="breadcrumb m-0">
                                            <li class="breadcrumb-item"><a href="javascript: void(0);">Ecommerce</a></li>
                                            <li class="breadcrumb-item active">Product Detail</li>
                                        </ol>
                                    </div>

                                </div>
                            </div>
                        </div>
                        <!-- end page title -->

                        <div class="row">
                            <div class="col-lg-12">
                                <div class="card">
                                    <div class="card-body">
                                        <div class="row">
                                            <div class="col-xl-6">
                                                <div class="product-detai-imgs">
                                                    <div class="row">
                                                        <div class="col-md-12 col-sm-12 col-12">
                                                            <div class="nav flex-column nav-pills " id="v-pills-tab" role="tablist" aria-orientation="vertical">
                                                                <img class="card-img-top" src="<?php echo base_url('uploads/' . ($prod_data->image ? $prod_data->image : 'default.jpg')) ?>" alt="Card image cap" height="320px">
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
    
                                            <div class="col-xl-6">
                                                <div class="mt-4 mt-xl-3">
                                                    <h4 class="mt-1 mb-3"><?php echo $prod_data->prod_name ?></h4>
                                                    <p class="text-muted float-start me-3">
                                                        <span class="bx bxs-star text-warning"></span>
                                                        <span class="bx bxs-star text-warning"></span>
                                                        <span class="bx bxs-star text-warning"></span>
                                                        <span class="bx bxs-star text-warning"></span>
                                                        <span class="bx bxs-star"></span>
                                                    </p>
                                                    <p class="text-muted mb-4">( <?php echo $this->db_model->count_all('product_item_sale', array('product_id' => $prod_data->id));?> Customers Buy This Product )</p>
                                                    <!--<br>-->
    
                                                    <h6 class="text-success text-uppercase">
                                                    <?php
                                                        $gstAmount          = ($prod_data->dealer_price * $prod_data->gst) / 100;
                                                        $totalPrice         = $prod_data->dealer_price + $gstAmount;
                                                        $originalPrice      = $prod_data->prod_price; 
                                                        $discountedPrice    = $totalPrice;
                                                        $percentageDiscount = (($originalPrice - $discountedPrice) / $originalPrice) * 100;
                                                        echo floor($percentageDiscount) .' % Off';
                                                    ?>
                                                    </h6>
                                                    <h5 class="mb-4">Price : <span class="text-muted me-2">
                                                        <del><?php echo config_item('currency') ?><?php echo number_format($prod_data->prod_price); ?></del></span> 
                                                        <b>
                                                        <?php echo config_item('currency') ?>
                                                        <?php 
                                                          echo number_format($totalPrice);
                                                        ?>
                                                        </b>
                                                    </h5>
                                                    <!--<h5 class="mb-4">Point Value : <span class="text-muted me-2"><?php echo $prod_data->pv ?></span></h5>-->
                                                    <h5>Stock : <?php echo $prod_data->qty ?></h5>
                                                    <p class="text-muted mb-4"><b>Description</b> : <?php echo $prod_data->prod_desc ? $prod_data->prod_desc:" Not Available"; ?></p>
                                                    <div class="row mb-3">
                                                      <div class="col-md-12">
                                                      <div class="">
                                                                <a type="button" href=" <?php echo site_url('cart/buy_2/' . $prod_data->id) ?>" class="btn btn-success waves-effect waves-light mt-2 me-1">
                                                                    <i class="bx bx-cart me-2"></i> Add to cart
                                                                </a>
                                                         </div> 
                                                      </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <!-- end row -->
                                    </div>
                                </div>
                                <!-- end card -->
                            </div>
                        </div>
                        <!-- end row -->

                        <div class="row mt-3">
                            <div class="col-lg-12">
                                <div>
                                    <h5 class="mb-3">Recent product :</h5>
                                      <div class="row">
                                       <?php foreach ($recent_products as $rk) { ?>
                                        <div class="col-xl-4 col-sm-6 col-xs-12">
                                            <div class="card">
                                                <div class="card-body">
                                                    <div class="row align-items-center">
                                                        <div class="col-md-4">
                                                            <img class="card-img-top" src="<?php echo base_url('uploads/' . ($rk->image ? $rk->image : 'default.jpg')) ?>" alt="Card image cap" height="100px">
                                                        </div>
                                                        <div class="col-md-8">
                                                            <div class="text-center text-md-start pt-3 pt-md-0">
                                                                <h5 class="text-truncate"><a href="javascript: void(0);" class="text-dark"> <?php echo $rk->prod_name;?> </a></h5>
                                                                <h5 class="text-muted"></h5>
                                                                <p class="text-muted mb-4">
                                                                    <i class="bx bxs-star text-warning"></i>
                                                                    <i class="bx bxs-star text-warning"></i>
                                                                    <i class="bx bxs-star text-warning"></i>
                                                                    <i class="bx bxs-star text-warning"></i>
                                                                    <i class="bx bxs-star"></i>
                                                                </p>
                                                                <h5 class="my-0"><span class="text-muted me-2"><del><?php echo config_item('currency') . $rk->prod_price; ?></del></span> <b><?php echo $rk->dealer_price; ?></b></h5>
                                                            </div>
                                                            
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="card-footer">
                                                        <a type="button" href=" <?php echo site_url('cart/buy_2/' . $rk->id) ?>" class="btn btn-sm btn-primary waves-effect waves-light mt-2 me-1">
                                                            <i class="bx bx-cart me-2"></i> Add to cart
                                                        </a>
                                                        <a type="button" href=" <?php echo site_url('cart/view_product/' . $rk->id) ?>" class="btn btn-sm btn-success waves-effect waves-light mt-2 me-1">
                                                            <i class="bx bx-cart me-2"></i> View Product
                                                        </a>
                                                </div>
                                            </div>
                                        </div>
                                        <?php } ?>
                               
                                    </div>
                                    <!-- end row -->
                                </div>
                            </div>
                        </div>
                        <!-- end row -->
