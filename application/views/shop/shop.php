<?php
    include ('head.php');
?>
            <div id="page-content">
                <!-- Bredcrumbs -->
                <div class="bredcrumbWrap bredcrumb-style2">
                    <div class="container breadcrumbs">
                        <a href="#" title="Back to the home page">Home</a><span aria-hidden="true">|</span><span class="title-bold"><?php echo $title ?></span>
                    </div>
                </div>
                <!-- End Bredcrumbs -->

                <div class="container">
                    <div class="row">
                        <!-- Main Content -->
                        <div class="col-12 col-sm-12 col-md-12 col-lg-12 main-col">
                            <div class="productList">
                                <div class="grid-products grid--view-items">
                                    <div class="row">
                                        <?php 
                                            foreach($productt as $prodd){
                                            $salePrice  = $prodd['prod_price'] - $prodd['prod_price']; 
                                        ?>
                                        <div class="col-6 col-sm-6 col-md-4 col-lg-3 item">
                                            <div class="product-image">
                                                <a href="<?php echo base_url('home/view_product/'.$prodd['id']) ?>" >
                                                    <img class="primary blur-up lazyload" data-src="<?php echo base_url('uploads/' . ($prodd['image'] ? $prodd['image'] : 'default.jpg')) ?>" src="<?php echo base_url('uploads/' . ($prodd['image'] ? $prodd['image'] : 'default.jpg')) ?>" alt="image" title="product" height="300px"/>
                                                    <img class="hover blur-up lazyload" data-src="<?php echo base_url('uploads/' . ($prodd['image1'] ? $prodd['image1'] : $prodd['image'])) ?>" src="<?php echo base_url('uploads/' . ($prodd['image1'] ? $prodd['image1'] : $prodd['image'])) ?>" alt="image" title="product" height="300px"/>
                                                    <?php if($salePrice > 0){ ?>
                                                    <div class="product-labels"><span class="lbl on-sale">Sale</span></div>
                                                    <?php } ?>
                                                </a>
                                                <div class="button-set mobile">
                                                    <!--<div class="quickview-btn" data-bs-toggle="tooltip" data-bs-placement="top" title="quick view">-->
                                                    <!--    <a href="#open-quickview-popup" class="btn quick-view-popup quick-view"><i class="icon an an-search"></i></a>-->
                                                    <!--</div>-->
                                                    <div class="variants add" data-bs-toggle="tooltip" data-bs-placement="top" title="add to cart">
                                                        <a href="<?php echo base_url('home/add_to_cart/'.$prodd['id']);?>" class="btn cartIcon btn-addto-cart"><i class="icon an an-shopping-bag"></i></a>
                                                    </div>
                                                    <div class="wishlist-btn" data-bs-toggle="tooltip" data-bs-placement="top" title="add to wishlist">
                                                        <a href="<?php echo base_url('home/add_to_wishlist/'.$prodd['id']);?>" class="wishlist add-to-wishlist"><i class="icon an an-heart"></i></a>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="product-details text-center">
                                                <!-- Product Name -->
                                                <div class="product-name">
                                                    <a href="<?php echo base_url('home/view_product/'.$prodd['id']) ?>"><?php echo $prodd['prod_name'] ?></a>
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
                                                <!--<ul class="swatches color-style color-style1">-->
                                                <!--    <li class="swatch small rounded" data-bs-toggle="tooltip" data-bs-placement="top" title="Black">-->
                                                <!--        <label class="color black"></label>-->
                                                <!--    </li>-->
                                                <!--    <li class="swatch small rounded" data-bs-toggle="tooltip" data-bs-placement="top" title="White">-->
                                                <!--        <label class="color white"></label>-->
                                                <!--    </li>-->
                                                <!--    <li class="swatch small rounded" data-bs-toggle="tooltip" data-bs-placement="top" title="Red">-->
                                                <!--        <label class="color red"></label>-->
                                                <!--    </li>-->
                                                <!--    <li class="swatch small rounded" data-bs-toggle="tooltip" data-bs-placement="top" title="Blue">-->
                                                <!--        <label class="color blue"></label>-->
                                                <!--    </li>-->
                                                <!--</ul>-->
                                            </div>
                                        </div>
                                        <?php } ?>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
<?php
    include ('footer.php');
?>