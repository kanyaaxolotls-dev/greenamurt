        <?php
            include ('head.php');
        ?>
            <style>
                @media (max-width: 768px) {
                    .product-image a img {
                        height: 300px;
                    }
                }
                
                @media (min-width: 769px) {
                    .product-image a img {
                        height: 270px;
                    }
                }
                
                @media (min-width: 769px) {
                    #img_height{
                        height: 600px; 
                    }
                }
                
                @media (max-width: 769px) {
                    #img_height{
                        height: 250px; 
                    }
                }
            
                .img-modal {
                  display: none;
                  position: fixed;
                  z-index: 1050;
                  left: 0;
                  top: 0;
                  width: 100%;
                  height: 100%;
                  background-color: rgba(0, 0, 0, 0.9);
                  justify-content: center;
                  align-items: center;
                }
              
                .img-modal-content {
                  max-width: 90%;
                  max-height: 80vh;
                  object-fit: contain;
                  box-shadow: 0 0 20px rgba(255, 255, 255, 0.3);
                }
              
                .close {
                  position: absolute;
                  top: 20px;
                  right: 30px;
                  color: #fff;
                  font-size: 35px;
                  font-weight: bold;
                  cursor: pointer;
                  z-index: 1100;
                }
              
                .close:hover {
                  color: #ccc;
                }
            </style>
            <!-- Body Content -->
            
            <div id="page-content">
                <!-- Home Banner slider -->
                <div class="slideshow slideshow-wrapper pb-section sliderFull">
                    <div class="home-slideshow">
                        <?php foreach($slider as $sl){ ?>
                            <a href="<?php echo base_url('home/'.$sl['link']) ?>"> 
                                <img class="lazyload" data-src="<?php echo base_url('uploads/' . ($sl['image'] ? $sl['image'] : 'default.jpg')) ?>" src="<?php echo base_url('uploads/' . ($sl['image'] ? $sl['image'] : 'default.jpg')) ?>" alt="" title="" id="img_height"/>
                            </a>
                        <?php } ?>
                    </div>
                </div>
                <!-- End Home Banner slider -->

                <!-- Collection Tab slider -->
                <div class="tab-slider-product section">
                    <div class="container">
                        <div class="row">
                            <div class="col-12 col-sm-12 col-md-12 col-lg-12">
                                <div class="section-header text-center">
                                    <h2 class="h2">best selling products</h2>
                                </div>
                                <div class="tabs-listing">
                                    <ul class="tabs clearfix">
                                        <?php foreach ($categories as $key => $cat) : ?>
                                            <?php
                                            $isActive = $key === 0 ? 'active' : '';
                                            $cat_id   = $cat['id'];
                                            $cat_name = $cat['cat_name'];
                                            $products = $this->db->get_where('product', ['category' => $cat_id], 125)->result_array();
                                            if (!empty($products)) : ?>
                                                <li class="category-tab <?php echo $isActive ?>" rel="<?php echo $cat_name ?>"><?php echo $cat_name ?></li>
                                            <?php endif; ?>
                                        <?php endforeach; ?>
                                    </ul>
                                    <div class="tab_container">
                                        <?php foreach ($categories as $key => $cat) : ?>
                                            <?php
                                            $cat_id   = $cat['id'];
                                            $cat_name = $cat['cat_name'];
                                            $products = $this->db->get_where('product', ['category' => $cat_id], 125)->result_array();
                                            if (!empty($products)) : ?>
                                                <div id="<?php echo $cat_name ?>" class="tab_content grid-products grid-products-hover-btn">
                                                    <div class="productSlider">
                                                        <?php foreach ($products as $prod) : ?>
                                                            <?php
                                                                $gstAmount          = ($prod['prod_price'] * $prod['gst']) / 100;
                                                                $totalPrice         = $prod['prod_price'] + $gstAmount;
                                                                $originalPrice      = $prod['prod_price'];
                                                                $discountedPrice    = $totalPrice;
                                                                $percentageDiscount = (($originalPrice - $discountedPrice) / $originalPrice) * 100;
                                                            ?>
                                                            <div class="col-12 item">
                                                                <div class="product-image">
                                                                    <a href="<?php echo base_url('home/view_product/'.$prod['id']) ?>">
                                                                        <img class="primary blur-up lazyload" data-src="<?php echo base_url('uploads/' . ($prod['image'] ? $prod['image'] : 'default.jpg')) ?>" src="<?php echo base_url('uploads/' . ($prod['image'] ? $prod['image'] : 'default.jpg')) ?>" alt="image" title="product" height="350px"/>
                                                                        <img class="hover blur-up lazyload" data-src="<?php echo base_url('uploads/' . ($prod['image1'] ? $prod['image1'] : $prod['image'])) ?>" src="<?php echo base_url('uploads/' . ($prod['image1'] ? $prod['image1'] : $prod['image'])) ?>" alt="image" title="product" height="350px"/>
                                                                        <div class="product-labels rectangular">
                                                                            <?php if($percentageDiscount > 0){ ?>
                                                                            <span class="lbl on-sale"><?php echo floor($percentageDiscount) . ' % Off'; ?></span> 
                                                                            <?php } ?>
                                                                            <span class="lbl pr-label1">new</span>
                                                                        </div>
                                                                    </a>
                                                                    <!--<div class="saleTime desktop" data-countdown="2022/03/01"></div>-->
                                                                    <div class="button-set">
                                                                        <div class="variants add" data-bs-toggle="tooltip" data-bs-placement="top" title="add to cart">
                                                                            <form class="addtocart" action="#" method="post">
                                                                                <a href="<?php echo base_url('home/add_to_cart/'.$prod['id']);?>" class="btn cartIcon btn-addto-cart"><i class="icon an an-shopping-bag"></i></a>
                                                                            </form>
                                                                        </div>
                                                                        <div class="wishlist-btn" data-bs-toggle="tooltip" data-bs-placement="top" title="add to wishlist">
                                                                            <a href="<?php echo base_url('home/add_to_wishlist/'.$prod['id']);?>" class="wishlist add-to-wishlist"><i class="icon an an-heart"></i></a>
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                                <div class="product-details text-center">
                                                                    <div class="product-name">
                                                                        <a href="<?php echo base_url('home/view_product/'.$prod['id']) ?>"><?php echo $prod['prod_name'] ?></a>
                                                                    </div>
                                                                    <div class="product-price">
                                                                        <span class="old-price"><?php echo config_item('currency') . $prod['prod_price'] ?></span>
                                                                        <span class="price"><?php echo config_item('currency') . $prod['prod_price'] ?></span></br>
                                                                        <!--<span class="text-success">BV : <?php echo config_item('pv') . $prod['pv'] ?></span>-->
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
                                                        <?php endforeach; ?>
                                                    </div>
                                                </div>
                                            <?php endif; ?>
                                        <?php endforeach; ?>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                <!-- End Collection Tab slider -->

                <!-- Collection Box slider -->
                <div class="collection-box section">
                    <div class="container-fluid">
                        <div class="section-header text-center">
                            <h2 class="h2">Shop By Categories</h2>
                        </div>
                        <div class="collection-grid-4item">
                            <?php foreach($categories2 as $cat2){ ?>
                                <div class="collection-grid-item">
                                    <img data-src="<?php echo base_url('uploads/' . ($cat2['image'] ? $cat2['image'] : 'default.jpg')) ?>" src="<?php echo base_url('uploads/' . ($cat2['image'] ? $cat2['image'] : 'default.jpg')) ?>" alt="Shoes" class="blur-up lazyload" height="310px"/>
                                    <a href="<?php echo base_url('home/products/'.$cat2['id']) ?>" class="collection-grid-item__title-wrapper">
                                        <div class="title-wrapper">
                                            <h3 class="collection-grid-item__title"><?php echo $cat2['cat_name'] ?></h3>
                                            <span class="btn btn--secondary border-btn-1">Shop <?php echo $cat2['cat_name'] ?></span>
                                        </div>
                                    </a>
                                </div>
                            <?php } ?>
                        </div>
                    </div>
                </div>
                <!-- End Collection Box slider -->
                
                <!-- Featured Product -->
                <div class="product-rows section">
                    <div class="container">
                        <div class="row">
                            <div class="col-12 col-sm-12 col-md-12 col-lg-12">
                                <div class="section-header text-center">
                                    <h2 class="h2"><?php echo config_item('company_name') ?>'s Picks</h2>
                                    <p>Shop <?php echo config_item('company_name') ?>'s favourite pieces from the Spring Summer '18 collections</p>
                                </div>
                            </div>
                        </div>
                        <div class="grid-products grid-products-hover-btn">
                            <div class="productSlider-style1">
                                <?php foreach($productt as $prodd){ ?>
                                <div class="col-12 item">
                                    <div class="product-image">
                                        <a href="<?php echo base_url('home/view_product/'.$prodd['id']) ?>" >
                                            <img class="primary blur-up lazyload" data-src="<?php echo base_url('uploads/' . ($prodd['image'] ? $prodd['image'] : 'default.jpg')) ?>" src="<?php echo base_url('uploads/' . ($prodd['image'] ? $prodd['image'] : 'default.jpg')) ?>" alt="image" title="product"/>
                                            <img class="hover blur-up lazyload" data-src="<?php echo base_url('uploads/' . ($prodd['image1'] ? $prodd['image1'] : $prodd['image'])) ?>" src="<?php echo base_url('uploads/' . ($prodd['image1'] ? $prodd['image1'] : $prodd['image'])) ?>" alt="image" title="product"/>
                                            <div class="product-labels rectangular">
                                                <!--<span class="lbl on-sale">-16%</span>-->
                                                <span class="lbl pr-label1">new</span>
                                            </div>
                                        </a>
                                        
                                        <div class="button-set">
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
                                            <!--<span class="text-success">BV : <?php echo config_item('pv') . $prodd['pv'] ?></span>-->
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
                                <?php } ?>
                            </div>
                        </div>
                    </div>
                </div>
                <!-- End Featured Product -->

                <!-- Store Feature -->
                <div class="store-feature section no-pb-section">
                    <div class="container">
                        <div class="row">
                            <div class="col-12 col-sm-12 col-md-12 col-lg-12">
                                <ul class="display-table store-info">
                                    <li class="display-table-cell">
                                        <i class="icon an an-truck"></i>
                                        <div class="store-info-text">
                                            <h5>Free Worldwide Shipping</h5>
                                            <span class="sub-text">Free shipping on all US orders</span>
                                        </div>
                                    </li>
                                    <li class="display-table-cell">
                                        <i class="icon an an-money-bill-alt"></i>
                                        <div class="store-info-text">
                                            <h5>Money Guarantee</h5>
                                            <span class="sub-text">30 days money back guarantee</span>
                                        </div>
                                    </li>
                                    <li class="display-table-cell">
                                        <i class="icon an an-question-circle"></i>
                                        <div class="store-info-text">
                                            <h5>Top Notch Support</h5>
                                            <span class="sub-text">We support online 24/7 on day</span>
                                        </div>
                                    </li>
                                    <li class="display-table-cell">
                                        <i class="icon an an-lock"></i>
                                        <div class="store-info-text">
                                            <h5>Secure Payments</h5>
                                            <span class="sub-text">All payment are Secured and trusted.</span>
                                        </div>
                                    </li>
                                </ul>
                            </div>
                        </div>
                    </div>
                </div>
                <!-- End Store Feature -->
            </div>
        </div>
<!-- End Body Content -->

<div id="imageModal" class="img-modal">
  <span class="close">&times;</span>
  <img class="img-modal-content" id="modalImage" src="" alt="Popup Image">
</div>

<script>
    const images     = <?php echo json_encode(array_column($popups, 'img')); ?>;
    let currentIndex = 0;
    
    const modal      = document.getElementById('imageModal');
    const modalImage = document.getElementById('modalImage');
    const closeBtn   = document.querySelector('.close');
    
    function showNextImage() {
        if (currentIndex < images.length) {
          modalImage.src = images[currentIndex];
          modal.style.display = 'flex';
        }
    }
    
    function closeModal() {
        modal.style.display = 'none';
        currentIndex++;
        setTimeout(showNextImage, 300);  
    }
    
    window.onload = function () {
        if (images.length > 0) {
          showNextImage();
        }
    };
    
    closeBtn.onclick = closeModal;
    
    modal.onclick = function (e) {
        if (e.target === modal) {
          closeModal();
        }
    };
</script>

<?php
    include ('footer.php');
?>
