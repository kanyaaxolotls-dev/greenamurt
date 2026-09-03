<?php
    include ('head.php');
?>
            <!-- Body Content -->
            <div id="page-content">
                <!-- Page Title -->
                <div class="page section-header text-center">
                    <div class="page-title">
                        <div class="wrapper"><h1 class="page-title">Checkout</h1></div>
                    </div>
                </div>
                <!-- End Page Title -->

                <div class="container">
                    <div class="row">
                        <!-- Main Content -->
                        <div class="col-xl-6 col-lg-6 col-md-6 col-sm-12 mb-3">
                            <div class="customer-box returning-customer">
                                <h3><i class="icon an an-user"></i>Address Details</h3>
                            </div>
                        </div>
                        <div class="col-xl-6 col-lg-6 col-md-6 col-sm-12 mb-3">
                            <div class="customer-box customer-coupon">
                                <h3 class="font-15 xs-font-13"><i class="icon an an-gift"></i> Have a coupon? <a href="#have-coupon" class="text-white" data-bs-toggle="collapse">Click here to enter your code</a></h3>
                                <div id="have-coupon" class="collapse coupon-checkout-content">
                                    <div class="discount-coupon">
                                        <div id="coupon" class="coupon-dec tab-pane active">
                                            <p class="mb-3">Enter your coupon code if you have one.</p>
                                            <form method="post" action="#">
                                                <div class="form-group">
                                                    <label class="required get" for="coupon-code"><span class="required-f">*</span> Coupon</label>
                                                    <input id="coupon-code" required type="text" class="mb-3">
                                                    <button class="coupon-btn btn" type="submit">Apply Coupon</button>
                                                </div>
                                            </form>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="row billing-fields">
                        <div class="col-xl-6 col-lg-6 col-md-6 col-sm-12 mb-3 mb-md-0">
                            <div class="create-ac-content">
                                <form method="post" action="<?php echo base_url('home/checkout') ?>">
                                    <fieldset>
                                        <h2 class="login-title mb-3">Billing details</h2>
                                        <div class="row">
                                            <div class="form-group col-md-6 col-lg-6 col-xl-6 required">
                                                <label for="input-firstname">Full Name <span class="required-f">*</span></label>
                                                <input name="data[]" required value="<?php echo $userss->name ?>" id="input-firstname" type="text">
                                            </div>
                                            <div class="form-group col-md-6 col-lg-6 col-xl-6 required">
                                                <label for="input-email">E-Mail <span class="required-f">*</span></label>
                                                <input name="data[]"  value="<?php echo $userss->email ?>" id="input-email" type="email">
                                            </div>
                                            <div class="form-group col-md-6 col-lg-6 col-xl-6 required">
                                                <label for="input-telephone">Mobile <span class="required-f">*</span></label>
                                                <input name="data[]" required value="<?php echo $userss->phone ?>" id="input-telephone" type="tel">
                                            </div>
                                            <div class="form-group col-md-6 col-lg-6 col-xl-6">
                                                <label for="input-company">Country</label>
                                                <input name="data[]" value="India" id="input-company" type="text" readonly>
                                            </div>
                                            <div class="form-group col-md-6 col-lg-6 col-xl-6">
                                                <label for="input-company">State</label>
                                                <input name="data[]" required value="<?php echo $userss->state ?>" id="input-company" type="text">
                                            </div>
                                            <div class="form-group col-md-6 col-lg-6 col-xl-6 required">
                                                <label for="input-address-1">City <span class="required-f">*</span></label>
                                                <input name="data[]" required value="<?php echo $userss->city ?>" id="input-address-1" type="text">
                                            </div>
                                            <div class="form-group col-md-6 col-lg-6 col-xl-6">
                                                <label for="input-address-2">Local Address <span class="required-f">*</span></label>
                                                <input name="data[]" value="<?php echo $userss->address ?>" id="input-address-2" type="text">
                                            </div>
                                            <div class="form-group col-md-6 col-lg-6 col-xl-6 required">
                                                <label for="input-postcode">Post Code <span class="required-f">*</span></label>
                                                <input name="data[]" required value="<?php echo $userss->pincode ?>" id="input-postcode" type="text">
                                            </div>
                                            <div class="form-group col-md-12 col-lg-12 col-xl-12 mb-0">
                                                <label for="input-company">Order Notes <span class="required-f">*</span></label>
                                                <textarea class="form-control resize-both" rows="3" name="note"></textarea>
                                            </div>
                                        </div>
                                    </fieldset>
                            </div>
                        </div>
                        <div class="col-xl-6 col-lg-6 col-md-6 col-sm-12">
                            <div class="your-order-payment">
                                <div class="your-order">
                                    <h2 class="order-title mb-4">Your Order</h2>
                                    <div class="table-responsive-sm order-table"> 
                                        <table class="bg-white table table-bordered table-hover text-center">
                                            <thead>
                                                <tr>
                                                    <th class="text-start">Product Name</th>
                                                    <th>Price</th>
                                                    <th>Qty</th>
                                                    <th>Subtotal</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                <?php
                                                    $subtotal = 0;
                                                    $total    = 0;
                                                    foreach ($cart_data as $product_id => $data) {
                                                        $price = $this->db_model->select('dealer_price', 'product', array('id' => $product_id));
                                                        $mrp   = $this->db_model->select('prod_price', 'product', array('id' => $product_id));
                                                        $img   = $this->db_model->select('image', 'product', array('id' => $product_id));
                                                        $name  = $this->db_model->select('prod_name', 'product', array('id' => $product_id));
                                                        $tot   = $mrp*$data['qty'];
                                                        $subtotal += $tot;
                                                        $tot2   = $price*$data['qty'];
                                                        $total += $tot2;
                                                ?>
                                                <tr>
                                                    <td class="text-start"><?php echo $name; ?></td>
                                                    <td><?php echo config_item('currency') . number_format($price); ?></td>
                                                    <td><?php echo $data['qty']; ?></td>
                                                    <td><?php echo config_item('currency') . number_format($tot2); ?></td>
                                                </tr>
                                                <?php } ?>
                                            </tbody>
                                            <tfoot class="font-weight-600">
                                                <tr>
                                                    <td colspan="3" class="text-end">Shipping </td>
                                                    <td>Free</td>
                                                </tr>
                                                <tr>
                                                    <td colspan="3" class="text-end">Total</td>
                                                    <td><?php echo config_item('currency') .number_format($total) ?></td>
                                                </tr>
                                            </tfoot>
                                        </table>
                                    </div>
                                </div>

                                <hr>

                                <div class="your-payment">
                                    <h2 class="payment-title mb-3">payment method</h2>
                                    <div class="payment-method">
                                        <div class="payment-accordion">
                                            <div id="accordion" class="payment-section">
                                                <div class="card mb-2">
                                                        <div class="card-body">
                                                            <!-- Form Group for Label and Select Box -->
                                                            <div class="form-group">
                                                                <!--<label for="paymentMethod">Select Payment Method:</label>-->
                                                                <select class="form-control" id="paymentMethod" name="payment" required>
                                                                    <option value="" disabled selected>Select Payment Method</option>
                                                                    <option value="cod">Cash on Delivery</option>
                                                                    <option value="wallet">User Wallet</option>
                                                                </select>
                                                        </div>
                                                    </div>
                                                </div>
                                                <!--<div class="card mb-2">-->
                                                <!--    <div class="card-header">-->
                                                <!--        <a class="collapsed card-link" data-bs-toggle="collapse" href="#collapseFour"> Payment Information </a>-->
                                                <!--    </div>-->
                                                <!--    <div id="collapseFour" class="collapse" data-parent="#accordion">-->
                                                <!--        <div class="card-body">-->
                                                <!--            <fieldset>-->
                                                <!--                <div class="row">-->
                                                <!--                    <div class="form-group col-md-6 col-lg-6 col-xl-6 required">-->
                                                <!--                        <label for="input-cardname">Name on Card <span class="required-f">*</span></label>-->
                                                <!--                        <input name="cardname" value="" placeholder="Card Name" id="input-cardname" class="form-control" type="text">-->
                                                <!--                    </div>-->
                                                <!--                    <div class="form-group col-md-6 col-lg-6 col-xl-6 required">-->
                                                <!--                        <label for="input-country">Credit Card Type <span class="required-f">*</span></label>-->
                                                <!--                        <select name="country_id" class="form-control">-->
                                                <!--                            <option value="">Please Select</option>-->
                                                <!--                            <option value="1">American Express</option>-->
                                                <!--                            <option value="2">Visa Card</option>-->
                                                <!--                            <option value="3">Master Card</option>-->
                                                <!--                            <option value="4">Discover Card</option>-->
                                                <!--                        </select>-->
                                                <!--                    </div>-->
                                                <!--                </div>-->
                                                <!--                <div class="row">-->
                                                <!--                    <div class="form-group col-md-6 col-lg-6 col-xl-6 required">-->
                                                <!--                        <label for="input-cardno">Credit Card Number  <span class="required-f">*</span></label>-->
                                                <!--                        <input name="cardno" value="" placeholder="Credit Card Number" id="input-cardno" class="form-control" type="text">-->
                                                <!--                    </div>-->
                                                <!--                    <div class="form-group col-md-6 col-lg-6 col-xl-6 required">-->
                                                <!--                        <label for="input-cvv">CVV Code <span class="required-f">*</span></label>-->
                                                <!--                        <input name="cvv" value="" placeholder="Card Verification Number" id="input-cvv" class="form-control" type="text">-->
                                                <!--                    </div>-->
                                                <!--                </div>-->
                                                <!--                <div class="row">-->
                                                <!--                    <div class="form-group col-md-6 col-lg-6 col-xl-6 required">-->
                                                <!--                        <label>Expiration Date <span class="required-f">*</span></label>-->
                                                <!--                        <input type="date" name="exdate" class="form-control">-->
                                                <!--                    </div>-->
                                                <!--                    <div class="form-group col-md-6 col-lg-6 col-xl-6 required">-->
                                                <!--                        <img class="padding-25px-top xs-padding-5px-top" src="assets/images/payment-img.jpg" alt="card" title="card">-->
                                                <!--                    </div>-->
                                                <!--                </div>-->
                                                <!--            </fieldset>-->
                                                <!--        </div>-->
                                                <!--    </div>-->
                                                <!--</div>-->
                                            <!--</div>-->
                                        </div>
                                        <div class="order-button-payment">
                                            <input name="total" value="<?php echo $total ?>" id="input-postcode" type="hidden">
                                            <input type="submit" class="btn" value="Place order">
                                            <!--<a href="<?php echo base_url('home/checkout_success/'.$total) ?>" class="btn">Place order</a>-->
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <!--End Main Content-->        
                </div>
                </form>
            </div>
            <!-- End Body Content -->

<?php
    include ('footer.php');
?>