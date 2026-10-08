 <!-- Footer -->
 <footer id="footer">
                <div class="site-footer">
                    <div class="footer-top">
                        <div class="container">
                            <!-- Footer Links -->
                            <div class="row">
                                <div class="col-12 col-sm-12 col-md-4 col-lg-4 footer-links">
                                    <h4 class="h4">Quick Links</h4>
                                    <ul>
                                        <?php 
                                            $this->db->select('*');
                                            $this->db->limit(5);
                                            $this->db->from('product_categories');
                                            $categories3 = $this->db->get()->result_array();
                                            foreach($categories3 as $cat3){ 
                                        ?>
                                        <li><a href="<?php echo base_url('home/products/'.$cat3['id']) ?>"><?php echo $cat3['cat_name'] ?></a></li>
                                        <?php } ?>
                                    </ul>
                                </div>
                                <!--<div class="col-12 col-sm-12 col-md-3 col-lg-3 footer-links">-->
                                <!--    <h4 class="h4">Help</h4>-->
                                <!--    <ul>-->
                                <!--        <li><a href="<?php echo base_url('home/faq'); ?>">FAQs</a></li>-->
                                <!--        <li><a href="<?php echo base_url('home/terms'); ?>">Terms And Conditions</a></li>-->
                                <!--        <li><a href="<?php echo base_url('home/shipping'); ?>">Shipping Policy</a></li>-->
                                <!--        <li><a href="<?php echo base_url('home/return'); ?>">Returns &amp; Exchange</a></li>-->
                                <!--    </ul>-->
                                <!--</div>-->
                                <div class="col-12 col-sm-12 col-md-4 col-lg-4 contact-box">
                                    <h4 class="h4">Contact Us</h4>
                                    <ul class="addressFooter">
                                        <li>
                                            <i class="icon an an-map-marker"></i>
                                            <p><?php echo config_item('company_address') ?></p>
                                        </li>
                                        <!--<li class="phone">-->
                                        <!--    <i class="icon an an-phone-volume"></i>-->
                                        <!--    <p><a href="tel:<?php echo config_item('web_phone') ?>">+91 <?php echo config_item('web_phone') ?></a></p>-->
                                        <!--</li>-->
                                        <li class="email">
                                            <i class="icon an an-envelope"></i>
                                            <p><a href="mailto:<?php echo config_item('web_email') ?>"><?php echo config_item('web_email') ?></a></p>
                                        </li>
                                    </ul>
                                </div>
                                <div class="col-12 col-sm-12 col-md-4 col-lg-4 newsletter">
                                    <div class="display-table">
                                        <div class="display-table-cell footer-newsletter">
                                            <form action="#" method="post">
                                                <label class="h4">Newsletter</label>
                                                <p>sign up for newsletter to know our latest news and offers.</p>
                                                <div class="input-group">
                                                    <input type="email" class="input-group__field newsletter__input" name="EMAIL" value="" placeholder="Email address" required />
                                                    <span class="input-group__btn">
                                                        <button type="submit" class="btn newsletter__submit" name="commit" id="Subscribe"><span class="newsletter__submit-text--large">Sign Up</span></button>
                                                    </span>
                                                </div>
                                            </form>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <!-- End Footer Links -->
                    </div>

                    <div class="footer-middle">
                        <div class="container">
                            <div class="row">
                                <div class="col-12 col-sm-12 col-md-6 col-lg-6 d-flex justify-content-Start align-items-center">
                                    <div class="footer-social w-100 text-start d-flex align-items-center">
                                        <h3 class="h4">Stay Connected</h3>
                                        <ul class="list--inline site-footer__social-icons social-icons">
                                            <li><a class="social-icons__link d-inline-block" href="#" target="_blank" data-bs-toggle="tooltip" data-bs-placement="top" title="Facebook"><i class="icon an an-facebook"></i></a></li>
                                            <li><a class="social-icons__link d-inline-block" href="#" target="_blank" data-bs-toggle="tooltip" data-bs-placement="top" title="Twitter"><i class="icon an an-twitter"></i> <span class="icon__fallback-text">Twitter</span></a></li>
                                            <li><a class="social-icons__link d-inline-block" href="#" target="_blank" data-bs-toggle="tooltip" data-bs-placement="top" title="Pinterest"><i class="icon an an-pinterest-p"></i> <span class="icon__fallback-text">Pinterest</span></a></li>
                                            <li><a class="social-icons__link d-inline-block" href="#" target="_blank" data-bs-toggle="tooltip" data-bs-placement="top" title="Instagram"><i class="icon an an-instagram"></i> <span class="icon__fallback-text">Instagram</span></a></li>
                                            <li><a class="social-icons__link d-inline-block" href="#" target="_blank" data-bs-toggle="tooltip" data-bs-placement="top" title="YouTube"><i class="icon an an-youtube"></i> <span class="icon__fallback-text">YouTube</span></a></li>
                                        </ul>
                                    </div>
                                </div>
                                <!--Footer Payment Icon-->
                                <div class="col-12 col-sm-12 col-md-6 col-lg-6 d-flex justify-content-end align-items-center">
                                    <ul class="payment-icons list--inline w-100 justify-content-end text-end">
                                        <li><i class="icon an an-cc-visa" aria-hidden="true"></i></li>
                                        <li><i class="icon an an-cc-mastercard" aria-hidden="true"></i></li>
                                        <li><i class="icon an an-cc-amex" aria-hidden="true"></i></li>
                                        <li><i class="icon an an-cc-paypal" aria-hidden="true"></i></li>
                                        <li><i class="icon an an-cc-discover" aria-hidden="true"></i></li>
                                    </ul>
                                </div>
                                <!-- End Footer Payment Icon-->
                            </div>
                        </div>
                    </div>

                    <div class="footer-bottom">
                        <div class="container">
                            <div class="row">
                                <!-- Footer Copyright -->
                                <div class="col-12 col-sm-12 col-md-12 col-lg-12 copyright text-center"><span>&copy; <?php echo date('Y') ?> <?php echo config_item('company_name') ?>.</span> All Rights Reserved.</div>
                                <!-- End Footer Copyright -->
                            </div>
                        </div>
                    </div>
                </div>
            </footer>
            <!-- End Footer -->

            <!-- Scoll Top -->
            <div id="site-scroll"><i class="icon an an-angle-up"></i></div>
            <!-- End Scoll Top -->

            <!-- Minicart Drawer -->
            <div class="minicart-right-drawer right modal fade" id="minicart-drawer" tabindex="-1" aria-hidden="true">
                <div class="modal-dialog">
                    <div class="modal-content">
                        <div class="minicart-header">
                            <button type="button" class="close" data-bs-dismiss="modal" aria-label="Close"><i class="an an-times" aria-hidden="true" data-bs-toggle="tooltip" data-bs-placement="left" title="Close"></i></button>
                            <h4 class="modal-title" id="myModalLabel2">Shopping Cart 
                            <strong>
                            <?php 
                                if($cart_data == NULL){
                                    echo  0;
                                }
                                else{
                                   echo count($cart_data);
                                }
                            ?>
                            </strong> items</h4>
                        </div>
                        <div class="minicart-body">
                            <?php
                                $data  = $this->session->userdata('cart_data');
                                if($data == NULL){
                            ?>
                            <div class="">
                                <h2 class="text-center text-danger">You have no items in your shopping cart ☹️.</h2>
                            </div>
                            <?php
                                }
                                else{
                            ?>
                            <div id="drawer-minicart" class="block block-cart">
                                <ul class="mini-products-list">
                                    <?php
                                        $subtotal = 0;
                                        $total    = 0;
                                        foreach ($data as $product_id => $data) {
                                            $price = $this->db_model->select('dealer_price', 'product', array('id' => $product_id));
                                            $mrp   = $this->db_model->select('prod_price', 'product', array('id' => $product_id));
                                            $img   = $this->db_model->select('image', 'product', array('id' => $product_id));
                                            $name  = $this->db_model->select('prod_name', 'product', array('id' => $product_id));
                                            $tot   = $mrp*$data['qty'];
                                            $subtotal += $tot;
                                            $tot2   = $price*$data['qty'];
                                            $total += $tot2;
                                    ?>
                                    <li class="item">
                                        <a class="product-image" href="<?php echo base_url('home/view_product/'.$product_id) ?>"><img src="<?php echo base_url('uploads/' . ($img ? $img : 'default.jpg')) ?>" alt="<?php echo $name; ?>" title=""></a>
                                        <div class="product-details">
                                            <a href="<?php echo base_url('home/remove_from_cart/'.$product_id);?>" class="remove" data-bs-toggle="tooltip" data-bs-placement="top" title="Remove"><i class="an an-times" aria-hidden="true"></i></a>
                                            <a class="pName" href="<?php echo base_url('home/view_product/'.$product_id) ?>"><?php echo $name; ?></a>
                                            <!--<div class="variant-cart">Red / XL</div>-->
                                            <div class="wrapQtyBtn clearfix">
                                                <span class="label">Qty:</span>
                                                <div class="qtyField clearfix">
                                                    <a class="qtyBtn minus" href="<?php echo base_url('home/remove_qty/'.$product_id);?>"><i class="an an-minus" aria-hidden="true"></i></a>
                                                    <input type="text" name="quantity" value="<?php echo $data['qty']; ?>" class="product-form__input qty">
                                                    <a class="qtyBtn plus" href="<?php echo base_url('home/add_qty/'.$product_id);?>"><i class="an an-plus" aria-hidden="true"></i></a>
                                                </div>
                                            </div>
                                            <div class="priceRow clearfix">
                                                <div class="product-price">
                                                    <span class="money"><?php echo config_item('currency') . number_format($tot2); ?></span>
                                                </div>
                                            </div>
                                        </div>
                                    </li>
                                    <?php } ?>
                                </ul>
                            </div>
                            <?php } ?>
                        </div>
                        <div class="minicart-footer minicart-action">
                            <div class="total-in">
                                <p class="label"><b>Subtotal : </b><span class="item product-price"><span class="money"><?php echo config_item('currency') . number_format($subtotal) ?></span></span></p>
                                <p class="label"><b>Shipping : </b><span class="item product-price"><span class="shipping">Free</span></span></p>
                                <p class="label"><b>Tax : </b><span class="item product-price"><span class="tax"><?php echo config_item('currency') .number_format(0) ?></span></span></p>
                                <p class="label"><b>Total : </b><span class="item product-price"><span class="totals"><?php echo config_item('currency') .number_format($total) ?></span></span></p>
                            </div>
                            <div class="buttonSet d-flex flex-row align-items-center text-center">
                                <a href="<?php echo base_url('home/cart') ?>" class="btn btn-secondary w-50 me-3">View Cart</a>
                                <a href="<?php echo base_url('home/checkout') ?>" class="btn btn-secondary w-50">Checkout</a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <!-- End Minicart Drawer -->

            <!-- Addtocart Added Popup -->
            <div id="open-addtocart-popup" class="addtocart-popup magnific-popup mfp-hide">
                <div class="addtocart-inner text-center clearfix">
                    <h4>Added to your shopping cart.</h4>
                    <div class="pro-img">
                        <img class="img-fluid blur-up lazyload" src="<?php echo base_url('assets2/images/product-images/addtocart-popup.jpg') ?>" data-src="<?php echo base_url('assets2/images/product-images/addtocart-popup.jpg') ?>" alt="image" title="image" />
                    </div>
                    <div class="pro-details">
                        <p class="pro-name mb-0">Floral Lined Jacket</p>
                        <p class="pro-cz mb-0">Gray / XL</p>
                        <p class="mb-0 qty-tol">1 X $113.88</p>
                        <div class="addcart-total">
                            Total: <b class="price">$113.88</b>
                        </div>
                        <div class="button-action">
                            <button class="btn btn-secondary continue-shopping close-popup">Continue Shopping</button>
                            <a href="<?php echo base_url('home/cart') ?>" class="btn btn-primary view-cart">View Cart</a>
                        </div>
                    </div>
                </div>
            </div>
            <!-- End Addtocart Added Popup -->

            <!-- Wishlist Added Popup -->
            <div id="open-wishlist-popup" class="wishlist-popup magnific-popup mfp-hide">
                <div class="wishlist-inner text-center clearfix">
                    <h4>Successfully added in wishlist</h4>
                    <div class="pro-img">
                        <img class="img-fluid blur-up lazyload" src="<?php echo base_url('assets2/images/product-images/addwishlist-popup.jpg') ?>" data-src="<?php echo base_url('assets2/images/product-images/addwishlist-popup.jpg') ?>" alt="image" title="image" />
                    </div>
                    <div class="pro-details">
                        <p class="pro-name mb-2">Frayed Layered Sleeve</p>
                        <div class="button-action">
                            <button class="btn btn-secondary mb-2 continue-shopping close-popup">Continue Shopping</button>
                            <a href="wishlist.html" class="btn btn-primary view-wishlist">View Wishlist</a>
                        </div>
                    </div>
                </div>
            </div>
            <!-- End Wishlist Added Popup -->

            <!-- Including Javascript -->
            <!-- Plugins JS -->
            <script src="<?php echo base_url('assets2/js/plugins.js') ?>"></script>
            <!-- Main JS -->
            <script src="<?php echo base_url('assets2/js/main.js') ?>"></script>
            <!-- For Newsletter Popup -->
            <!-- End For Newsletter Popup -->

        </div>
        <!-- End Page Wrapper -->
    </body>

</html>

