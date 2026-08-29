<?php
    include ('head.php');
    $id = $this->uri->segment(3);
?>
            <!-- Body Content -->
            <div id="page-content">
                <!-- Page Title -->
                <div class="page section-header text-center mb-0">
                    <div class="page-title">
                        <div class="wrapper"><h1 class="page-title">Payment Successfull !!</h1></div>
                    </div>
                </div>
                <!-- End Page Title -->
                <!-- Breadcrumbs -->
                <div class="bredcrumbWrap bredcrumb-style2">
                    <div class="container breadcrumbs">
                        <a href="#" title="Back to the home page">Home</a><span aria-hidden="true">|</span><span class="title-bold">Checkout Success</span>
                    </div>
                </div>
                <!-- End Breadcrumbs -->

                <div class="container checkout-success-content">
                    <!--<div class="row">-->
                    <!--    <div class="col-12 col-sm-12 col-md-12 col-lg-12">-->
                    <!--        <div class="checkout-success-banner mb-4 mb-lg-5">-->
                    <!--            <img class="blur-up lazyload" data-src="<?php echo base_url('assets2/images/checkout-success-banner.jpg') ?>" src="<?php echo base_url('assets2/images/checkout-success-banner.jpg') ?>" alt="Checkout Success Banner" />-->
                    <!--        </div>-->
                    <!--    </div>-->
                    <!--</div>-->

                    <div class="row">
                        <div class="col-12 col-sm-12 col-md-12 col-lg-6">
                            <div class="card border-0 rounded-0">
                                <div class="card-body text-center">
                                    <p class="checkout-success-icon"><i class="icon an an-check-square"></i></p>
                                    <h4 class="card-title">Thank you for your purchase!</h4>
                                    <p class="card-text mb-1">Your order Id is : <b><?php echo '#'.$id ?></b>.</p>
                                    <p class="card-text mb-1">You will receive an order confirmation email with details of your order and a link to track its progress.</p>
                                    <p class="card-text mb-1">All necessary information about the delivery, we sent to your email</p>
                                    <a href="<?php echo base_url('home') ?>" class="btn btn-primary mt-3">Continue Shopping</a>
                                </div>
                            </div>
                        </div>

                        <div class="col-12 col-sm-12 col-md-12 col-lg-6">
                            <div class="checkout-item-ordered">
                                <div class="ship-info-details billing-payment-details">
                                    <div class="row">
                                        <div class="col-12 col-sm-6 col-md-6 col-lg-6">
                                            <div class="shipping-details mb-4 mb-sm-0 clearfix">
                                                <h3>Billing Address</h3>
                                                <?php 
                                                   $adrs   = $this->db_model->select('shipping', 'product_sale', array('orderid' => $id)); 
                                                   $adress = json_decode($adrs);
                                                ?>
                                                <p><?php echo $adress[3].' , ' .$adress[4]  ?> ,</p>
                                                <p><?php echo $adress[5].' , <br>'.$adress[6].' - '.$adress[7]  ?> .</p>
                                            </div>
                                        </div>
                                        <div class="col-12 col-sm-6 col-md-6 col-lg-6">
                                            <div class="billing-details clearfix">
                                                <h3>Payment Method</h3>
                                                <p><?php echo strtoupper($this->db_model->select('payment_method', 'product_sale', array('orderid' => $id))); ?></p>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="delivery-date"><strong>Delivery Date:</strong> N/A</div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <!-- End Body Content -->
<?php
    include ('footer.php');
?>