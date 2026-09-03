<?php
    include ('head.php');
    $gstAmount          = ($product->prod_price * $product->gst) / 100;
    $totalPrice         = $product->prod_price + $gstAmount;
    $percentageDiscount = (($product->prod_price - $totalPrice) / $product->prod_price) * 100;
    $savePrice          = $product->prod_price - $product->prod_price;
    $bv               = $this->db_model->select('pv', 'product', array('id' => $product->id));
?>
            <div id="page-content">
                <!-- Bredcrumbs -->
                <div class="bredcrumbWrap bredcrumb-style2">
                    <div class="container breadcrumbs">
                        <a href="#" title="Back to the home page">Home</a><span aria-hidden="true">|</span><span class="title-bold">Product Details</span>
                    </div>
                </div>
                <!-- End Bredcrumbs -->
                <div class="container">
                    <!-- Main Content -->
                    <div id="ProductSection-product-template" class="product-template__container prstyle2">
                        <!-- #ProductSection product template -->
                        <div class="product-single product-single-1">
                            <div class="row">
                                <div class="col-lg-6 col-md-6 col-sm-12 col-12">
                                    <div class="product-details-img product-single__photos bottom">
                                        <!-- Product Images -->
                                        <div class="zoompro-wrap product-zoom-right pl-20">
                                            <div class="zoompro-span">
                                                <img class="blur-up lazyload zoompro img-fluid" data-zoom-image="<?php echo base_url('uploads/' . ($product->image ? $product->image : 'default.jpg')) ?>" alt="" src="<?php echo base_url('uploads/' . ($product->image ? $product->image : 'default.jpg')) ?>" height="440px" width="440px"/>
                                            </div>
                                            <div class="product-labels"><span class="lbl pr-label1">new</span><span class="lbl on-sale">Exclusive</span></div>
                                        </div>
                                        <div class="product-thumb product-thumb-1">
                                            <div id="gallery" class="product-dec-slider-1 product-tab-left">
                                                <a data-image="<?php echo base_url('uploads/' . ($product->image ? $product->image : 'default.jpg')) ?>" data-zoom-image="<?php echo base_url('uploads/' . ($product->image ? $product->image : 'default.jpg')) ?>" class="slick-slide slick-cloned active" data-slick-index="-4" aria-hidden="true" tabindex="-1" height="200px">
                                                    <img class="blur-up lazyload" src="<?php echo base_url('uploads/' . ($product->image ? $product->image : 'default.jpg')) ?>" alt="" height="100px" />
                                                </a>
                                                <a data-image="<?php echo base_url('uploads/' . ($product->image1 ? $product->image1 : 'default.jpg')) ?>" data-zoom-image="<?php echo base_url('uploads/' . ($product->image1 ? $product->image1 : 'default.jpg')) ?>" class="slick-slide slick-cloned" data-slick-index="-3" aria-hidden="true" tabindex="-1" height="200px">
                                                    <img class="blur-up lazyload" src="<?php echo base_url('uploads/' . ($product->image1 ? $product->image1 : 'default.jpg')) ?>" alt="" height="100px" />
                                                </a>
                                                <a data-image="<?php echo base_url('uploads/' . ($product->image2 ? $product->image2 : 'default.jpg')) ?>" data-zoom-image="<?php echo base_url('uploads/' . ($product->image2 ? $product->image2 : 'default.jpg')) ?>" class="slick-slide slick-cloned" data-slick-index="-2" aria-hidden="true" tabindex="-1" height="200px">
                                                    <img class="blur-up lazyload" src="<?php echo base_url('uploads/' . ($product->image2 ? $product->image2 : 'default.jpg')) ?>" alt="" height="100px" />
                                                </a>
                                                <a data-image="<?php echo base_url('uploads/' . ($product->image3 ? $product->image3 : 'default.jpg')) ?>" data-zoom-image="<?php echo base_url('uploads/' . ($product->image3 ? $product->image3 : 'default.jpg')) ?>" class="slick-slide slick-cloned" data-slick-index="-1" aria-hidden="true" tabindex="-1" height="200px">
                                                    <img class="blur-up lazyload" src="<?php echo base_url('uploads/' . ($product->image3 ? $product->image3 : 'default.jpg')) ?>" alt="" height="100px" />
                                                </a>
                                                <a data-image="<?php echo base_url('uploads/' . ($product->image4 ? $product->image4 : 'default.jpg')) ?>" data-zoom-image="<?php echo base_url('uploads/' . ($product->image4 ? $product->image4 : 'default.jpg')) ?>" class="slick-slide slick-cloned" data-slick-index="0" aria-hidden="true" tabindex="-1" height="200px">
                                                    <img class="blur-up lazyload" src="<?php echo base_url('uploads/' . ($product->image4 ? $product->image4 : 'default.jpg')) ?>" alt="" height="100px" />
                                                </a>
                                            </div>
                                        </div>
                                        <div class="lightboximages">
                                            <a href="<?php echo base_url('uploads/' . ($product->image  ? $product->image  : 'default.jpg')) ?>" data-size="1462x2048"></a>
                                            <a href="<?php echo base_url('uploads/' . ($product->image1 ? $product->image1 : 'default.jpg')) ?>" data-size="1462x2048"></a>
                                            <a href="<?php echo base_url('uploads/' . ($product->image2 ? $product->image2 : 'default.jpg')) ?>" data-size="1462x2048"></a>
                                            <a href="<?php echo base_url('uploads/' . ($product->image3 ? $product->image3 : 'default.jpg')) ?>" data-size="1462x2048"></a>
                                            <a href="<?php echo base_url('uploads/' . ($product->image4 ? $product->image4 : 'default.jpg')) ?>" data-size="1462x2048"></a>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-lg-6 col-md-6 col-sm-12 col-12">
                                    <!-- Product Info -->
                                    <div class="product-single__meta">
                                        <h1 class="product-single__title"><?php echo $product->prod_name ?></h1>
                                        <!-- Product Reviews -->
                                        <div class="prInfoRow d-flex flex-wrap">
                                            <div class="product-review">
                                                <a class="reviewLink d-flex flex-wrap align-items-center" href="#tab2">
                                                    <i class="an an-star"></i><i class="an an-star"></i><i class="an an-star"></i><i class="an an-star"></i><i class="an an-star-half-alt"></i>
                                                    <span class="spr-badge-caption">6 reviews</span>
                                                </a>
                                            </div>
                                        </div>
                                        <div class="product-single__price product-single__price-product-template">
                                            <span class="visually-hidden">Regular price</span>
                                            <s id="ComparePrice-product-template"><span class="money"><?php echo config_item('currency').$product->prod_price ?></span></s>
                                            <span class="product-price__price product-price__price-product-template product-price__sale product-price__sale--single">
                                                <span id="ProductPrice-product-template"><span class="money"><?php echo config_item('currency').$product->prod_price ?></span></span>
                                            </span>
                                            <?php 
                                            if($savePrice > 0){
                                            ?>
                                            <span class="discount-badge"> <span class="devider">|</span>&nbsp;
                                                <span>You Save</span>
                                                <span id="SaveAmount-product-template" class="product-single__save-amount">
                                                    <span class="money"><?php echo config_item('currency').$savePrice ?></span>
                                                </span>
                                                <!--<span class="off">(<span><?php echo floor($percentageDiscount) ?></span>%)</span>-->
                                            </span>
                                            <?php } ?>
                                            <!--<div class="product__policies rte" data-product-policies="">Tax included.</div>-->
                                        </div>
                                       <span class="text-success">BV : <?php echo $bv  = $this->db_model->select('pv', 'product', array('id' => $product->id)); ?></span>

                                        <div class="orderMsg mb-2 pb-1 d-flex flex-wrap align-items-center" data-user="17" data-time="16">
                                            <img src="<?php echo base_url('assets2/images/order-icon.jpg') ?>" alt="order" />
                                            <strong class="ms-1 me-1 items">16</strong> sold in last <strong class="ms-1 me-1 time">6</strong> hours
                                        </div>
                                        <div class="product-single__description rte">
                                            <p class="mb-2">
                                                <?php echo $product->prod_desc ?>
                                            </p>
                                            <!--<ul class="checkmark">-->
                                            <!--    <li>Wash Care: Hand Wash Cold</li>-->
                                            <!--    <li>Size And Fit: The specifications for the model are: Height 5 feet 8 inches, bust 34 inches, waist 28 inches. The model is wearing size S.</li>-->
                                            <!--</ul>-->
                                        </div>
                                        <div id="quantity_message">Hurry! Only  <span class="items"><?php echo $product->qty ?></span>  left in stock.</div>
                                        <!--<div class="saleTime product-countdown style3 pb-1" data-countdown="2022/03/01"></div>-->

                                        <form method="post" action="" id="product_form_10508262282" accept-charset="UTF-8" class="product-form product-form-product-template product-form-border hidedropdown" enctype="multipart/form-data">
                                            <!-- Size Swatch -->
                                            <?php if($product->size != NULL and $product->size != 'N;'){ ?>
                                            <div class="swatch clearfix swatch-1 option2 w-100" data-option-index="1">
                                                <div class="product-form__item">
                                                    <label>Select Size : </label>
                                                    <?php
                                                        $unserialized_data = unserialize($product->size);
                                                        $values            = array();
                                                        foreach ($unserialized_data as $value) {
                                                        $values[] = $value;
                                                    ?>
                                                    <div data-value="<?= $value ?>" class="swatch-element <?= $value ?> available">
                                                        <input class="swatchInput" id="swatch-1-<?= $value ?>-1" type="radio" name="option-1" value="<?= $value ?>">
                                                        <label class="swatchLbl medium" for="swatch-1-<?= $value ?>-1" data-bs-toggle="tooltip" data-bs-placement="top" title="<?= $value ?>"><?= $value ?></label>
                                                    </div>
                                                    <?php } ?>
                                                    
                                                </div>
                                            </div>
                                            <?php } ?>
                                            <!-- End Size Swatch -->
                                            <!--<div class="swatch clearfix swatch-0 option1 w-100" data-option-index="0">-->
                                            <!--    <div class="product-form__item">-->
                                            <!--        <label>Color: <span class="slVariant">Blue</span></label>-->
                                            <!--        <div data-value="Red" class="swatch-element color red available">-->
                                            <!--            <input class="swatchInput" id="swatch-0-red1" type="radio" name="option-0" value="Red">-->
                                            <!--            <label class="swatchLbl color large" for="swatch-0-red1" style="background-image:url(assets2/images/product-detail-page/cape-dress-1.jpg);" data-bs-toggle="tooltip" data-bs-placement="top" title="Red"></label>-->
                                            <!--        </div>-->
                                            <!--        <div data-value="Blue" class="swatch-element color blue available">-->
                                            <!--            <input class="swatchInput" id="swatch-0-blue1" type="radio" name="option-0" value="Blue">-->
                                            <!--            <label class="swatchLbl color large" for="swatch-0-blue1" style="background-image:url(assets2/images/product-detail-page/cape-dress-2.jpg);" data-bs-toggle="tooltip" data-bs-placement="top" title="Blue"></label>-->
                                            <!--        </div>-->
                                            <!--        <div data-value="Green" class="swatch-element color green available">-->
                                            <!--            <input class="swatchInput" id="swatch-0-green1" type="radio" name="option-0" value="Green">-->
                                            <!--            <label class="swatchLbl color large" for="swatch-0-green1" style="background-image:url(assets2/images/product-detail-page/cape-dress-3.jpg);" data-bs-toggle="tooltip" data-bs-placement="top" title="Green"></label>-->
                                            <!--        </div>-->
                                            <!--        <div data-value="Gray" class="swatch-element color gray available">-->
                                            <!--            <input class="swatchInput" id="swatch-0-gray1" type="radio" name="option-0" value="Gray">-->
                                            <!--            <label class="swatchLbl color large" for="swatch-0-gray1" style="background-image:url(assets2/images/product-detail-page/cape-dress-4.jpg);" data-bs-toggle="tooltip" data-bs-placement="top" title="Gray"></label>-->
                                            <!--        </div>-->
                                            <!--        <div data-value="Orange" class="swatch-element color orange available">-->
                                            <!--            <input class="swatchInput" id="swatch-0-orange1" type="radio" name="option-0" value="Orange">-->
                                            <!--            <label class="swatchLbl color large" for="swatch-0-orange1" style="background-image:url(assets2/images/product-detail-page/cape-dress-5.jpg);" data-bs-toggle="tooltip" data-bs-placement="top" title="Orange"></label>-->
                                            <!--        </div>-->
                                            <!--    </div>-->
                                            <!--</div>-->
                                            <!-- Product Action -->
                                            <div class="product-action clearfix">
                                                <div class="product-form__item--quantity">
                                                    <div class="wrapQtyBtn">
                                                        <div class="qtyField">
                                                            <a class="qtyBtn minus" href="javascript:void(0);"><i class="icon an an-minus" aria-hidden="true"></i></a>
                                                            <input type="text" name="quantity" value="1" class="product-form__input qty" />
                                                            <a class="qtyBtn plus" href="javascript:void(0);"><i class="icon an an-plus" aria-hidden="true"></i></a>
                                                        </div>
                                                    </div>
                                                </div>                                
                                                <div class="product-form__item--submit">
                                                    <a href="<?php echo base_url('home/add_to_cart/'.$product->id);?>" class="btn product-form__cart-submit"><span>Add to cart</span></a>
                                                </div>
                                                <div class="payment-button" data-shopify="payment-button">
                                                    <a href="<?php echo base_url('home/add_to_cart/'.$product->id);?>" ><button type="button" class="payment-button__button payment-button__button--unbranded">Buy it now</button></a>
                                                </div>
                                            </div>
                                            <!-- End Product Action -->
                                            <!-- Wishlist - Compare -->
                                            <div class="infolinks d-flex flex-wrap align-items-center px-0 mb-0">
                                                <a class="wishlist add-to-wishlist d-flex align-items-center" href="<?php echo base_url('home/add_to_wishlist/'.$product->id);?>"><i class="icon an an-heart me-1"></i> <span>Add to Wishlist</span></a>
                                                <a class="wishlist emaillink d-flex align-items-center" href="#productInquiry"><i class="icon an an-envelope me-1" style="margin-top:-1px;"></i> <span>Enquiry</span></a>
                                                <a class="wishlist shippingInfo d-flex align-items-center" href="#ShippingInfo"><i class="icon an an-telegram-plane me-1"></i> <span>Delivery &amp; Returns</span></a>
                                            </div>
                                            <!-- End Wishlist - Compare -->
                                        </form>
                                        <!-- End Form -->

                                        <!-- Product Feature -->
                                        <div class="safecheckout row my-3">
                                            <div class="item col-lg-3 mb-1 mb-sm-0">
                                                <div class="icon"><i class="icon an an-truck"></i></div>
                                                <div class="content">Free & fast shipping</div>
                                            </div>
                                            <div class="item col-lg-3 mb-1 mb-sm-0">
                                                <div class="icon"><i class="icon an an-certificate"></i></div>
                                                <div class="content">Secure checkout</div>
                                            </div>
                                            <div class="item col-lg-3">
                                                <div class="icon"><i class="icon an an-thumbs-up"></i></div>
                                                <div class="content">Satisfaction guarantee</div>
                                            </div>
                                            <div class="item col-lg-3">
                                                <div class="icon"><i class="icon an an-lock"></i></div>
                                                <div class="content">Privacy protected</div>
                                            </div>
                                        </div>
                                        <!-- End Product Feature -->

                                        <!--<div id="freeShipMsg" class="freeShipMsg rte mb-1" data-price="199"><i class="icon an an-truck" aria-hidden="true"></i> GETTING CLOSER! ONLY <b class="freeShip"><span class="money" data-currency-usd="$199.00" data-currency="USD">$199.00</span></b> AWAY FROM <b>FREE SHIPPING!</b></div>-->
                                        <div class="shippingMsg rte mb-1"><i class="icon an an-clock-o an-2x" aria-hidden="true"></i> ESTIMATED DELIVERY BETWEEN <b id="fromDate">10 Days</b>.</div>
                                        <div class="userViewMsg rte" data-user="20" data-time="2000"><i class="icon an an-users" aria-hidden="true"></i> <strong class="uersView">25</strong> People are looking at this product right now</div>
                                    </div>
                                    <!-- End Product Info -->
                                </div>
                            </div>
                            <!-- End Product single -->
                            <br><hr>
                            <!-- Related Product Slider -->
                <div class="product-rows section">
                    <div class="container">
                        <div class="row">
                            <div class="col-12 col-sm-12 col-md-12 col-lg-12">
                                <div class="section-header text-center">
                                    <h2 class="h2">Related Products</h2>
                                </div>
                            </div>
                        </div>
                        <div class="grid-products grid-products-hover-btn">
                            <div class="productSlider-style1">
                                <?php 
                                    if(empty($productt)){
                                        echo "<span class='h2 text-danger text-center'>No Related Products Found ! <br> ☹  <br️></span>"; 
                                    }
                                    else{
                                    foreach($productt as $prodd){ 
                                ?>
                                <div class="col-12 item">
                                    <div class="product-image">
                                        <a href="<?php echo base_url('home/view_product/'.$prodd['id']) ?>" >
                                            <img class="primary blur-up lazyload" data-src="<?php echo base_url('uploads/' . ($prodd['image'] ? $prodd['image'] : 'default.jpg')) ?>" src="<?php echo base_url('uploads/' . ($prodd['image'] ? $prodd['image'] : 'default.jpg')) ?>" alt="image" title="product"/>
                                            <img class="hover blur-up lazyload" data-src="<?php echo base_url('uploads/' . ($prodd['image1'] ? $prodd['image1'] : $prodd['image'])) ?>" src="<?php echo base_url('uploads/' . ($prodd['image1'] ? $prodd['image1'] : $prodd['image'])) ?>" alt="image" title="product"/>
                                            <div class="product-labels rectangular"><span class="lbl on-sale">-16%</span> <span class="lbl pr-label1">new</span></div>
                                        </a>
                                        <!--<div class="saleTime desktop" data-countdown="2023/12/01"></div>-->
                                        <div class="button-set">
                                            <!--<div class="quickview-btn" data-bs-toggle="tooltip" data-bs-placement="top" title="quick view">-->
                                            <!--    <a href="#open-quickview-popup" class="btn quick-view-popup quick-view"><i class="icon an an-search"></i></a>-->
                                            <!--</div>-->
                                            <div class="variants add" data-bs-toggle="tooltip" data-bs-placement="top" title="add to cart">
                                                <a href="<?php echo base_url('home/add_to_cart/'.$prodd['id']);?>" class="btn cartIcon btn-addto-cart"><i class="icon an an-shopping-bag"></i></a>
                                            </div>
                                            <div class="wishlist-btn" data-bs-toggle="tooltip" data-bs-placement="top" title="add to wishlist">
                                                <a href="<?php echo base_url('home/add_to_wishlist/'.$prod['id']);?>" class="wishlist add-to-wishlist"><i class="icon an an-heart"></i></a>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="product-details text-center">
                                        <div class="product-name">
                                            <a href="<?php echo base_url('home/view_product/'.$prodd['id']) ?>" ><?php echo $prodd['prod_name'] ?></a>
                                        </div>
                                        <div class="product-price">
                                            <span class="old-price"><?php echo config_item('currency') . $prodd['prod_price'] ?></span>
                                            <span class="price"><?php echo config_item('currency') . $prodd['prod_price'] ?></span></br>
                                            <span class="text-success">BV : <?php echo config_item('pv') . $prodd['pv'] ?></span>
                                        </div>
                                        <div class="product-review">
                                            <i class="an an-star"></i>
                                            <i class="an an-star"></i>
                                            <i class="an an-star"></i>
                                            <i class="an an-star"></i>
                                            <i class="an an-star-half-alt"></i>
                                        </div>
                                    </div>
                                </div>
                                <?php }} ?>
                            </div>
                        </div>
                        </div>
                        </div>
                        </div>
                    </div>
                </div>                
            </div>
            <!-- End Body Content -->
<?php
    include ('footer.php');
?>


