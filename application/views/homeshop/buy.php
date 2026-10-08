<div class="row"  style="padding: 0px 5px; box-shadow: 0px 3px 10px #ddd;margin: 2px;">
     <div class="col-md-9">
      <div class="panel panel-body" >
     <div class="mainbanner">
        <div id="main-banner" class="owl-carousel home-slider">
          <div class="item"> <a href="#"><img src="<?php echo base_url('axxets/shop/image/banners/Main-Banner1.jpg')?>" alt="main-banner1" class="img-responsive" /></a> </div>
          <div class="item"> <a href="#"><img src="<?php echo base_url('axxets/shop/image/banners/Main-Banner2.jpg')?>" alt="main-banner2" class="img-responsive" /></a> </div>
          <div class="item"> <a href="#"><img src="<?php echo base_url('axxets/shop/image/banners/Main-Banner3.jpg')?>" alt="main-banner3" class="img-responsive" /></a> </div>
        </div>
      </div>
    </div>
    </div>
    <div class="col-md-3">
      <div class="panel panel-body">
        <img src="<?php echo base_url('axxets/shop/image/banners/subbanner3.jpg')?>" alt="Sub Banner1" class="img-responsive">
      </div>
    </div>
</div>

  <div class="container">
    <div class="cms_banner">
     
    </div>


  <div class="row">
      <?php
          if (!$product) {
            foreach ($categories as $e) { ?>
            <div class="col-xs-12 col-sm-4 col-md-4">
                <div class="banner sub-hover">
                <a href="#"><img src="<?php echo base_url('axxets/shop/image/banners/subbanner1.jpg')?>" alt="Sub Banner1" class="img-responsive"></a>
                <div class="bannertext">
                <h2> <?php echo $e->cat_name ?>
                     <?php //echo $e->id ?>
                      </h2>
                <p>From Top Brands</p>
                <a class="btn btn-danger" href="<?php echo site_url('homeshop/shop_more/' . $e->id) ?>">Shop Now</a>

                </div>        
              </div>
          </div>
          <?php }
        } ?> 
    </div> 
    <a href="<?php echo site_url('cart/buy_2/' . $e->id) ?>">
        <div class="row">
            
        <?php foreach ($product as $e) { ?>
        <div class="col-3">
             <div class="card" style="width: 15rem;">
                  <img class="card-img-top" src="<?php echo base_url('uploads/' . ($e->image ? $e->image : 'default.jpg')) ?>" alt="product name">
                  <div class="card-body">
                    <h2 class="card-title"><?php echo $e->prod_name ?></h2>
                    <h2>MRP: INR <?php echo $e->prod_price ?></h2>
                    <p class="card-text">Dealer Price: INR <?php echo $e->dealer_price ?></p> 
                     <p class="card-text">PV: <?php if($e->pv ==''){
                        echo $e->pv=0;
                        }else{
                            echo $e->pv;
                        } ?>
                             

                        </p> 
                    <p class="card-text"><?php echo $e->prod_desc ?></p> 
                    

                    <a href="<?php echo site_url('cart/buy_2/' . $e->id) ?>" class="btn addtocart-btn btn-sm">Add to Cart</a>
                     <a href="<?php echo site_url('homeshop/product_view/' . $e->id) ?>" class="btn addtocart-btn btn-sm">View</a> 
                  </div>
                </div>
        </div>
        <?php } ?>
    </div>
    </a>


  <div class="content col-sm-12">
        <div class="customtab">
          <h3 class="productblock-title">Trending Products</h3>
          <div id="tabs" class="customtab-wrapper">
           <!--  <ul class='customtab-inner'>
              <li class='tab'><a href="#tab-furnitur">Popular</a></li>
              <li class='tab'><a href="#tab-livin">Best Sellers</a></li>
              <li class='tab'><a href="#tab-kitche">Specials</a></li>
              <li class='tab'><a href="#tab-outdoo">New product</a></li>
            </ul> -->
          </div>
        </div>
     
        <!-- tab-living-->
        <div id="tab-kitche" class="tab-content">
            <div class="row">

            <?php if (isset($product_top)) { ?><?php 
                foreach ($product_top as $e) {?>
            <div class="product-layout  product-grid  col-lg-3 col-md-4 col-sm-6 col-xs-12">
              <div class="item">
                <div class="product-thumb">
                  <div class="image product-imageblock"> <a href="product.html"> <img height="500" src="<?php 

                  echo base_url('uploads/' . ($e->image ? $e->image : 'default.jpg')) ?>" alt="<?php echo $e->prod_name ?>" title="<?php echo $e->prod_name ?>" class="img-responsive" /> <img src="<?php echo base_url('uploads/' . ($e->image ? $e->image : 'default.jpg')) ?>" alt="iPod Classic" title="iPod Classic" class="img-responsive" /> </a>
                    <ul class="button-group">
                      <li>
                        <button type="button" class="wishlist" data-toggle="tooltip" data-placement="top" title="Add to Wish List" ><i class="fa fa-heart-o"></i></button>
                      </li>
                      <li>
                        <button type="button" class="compare" data-toggle="tooltip" data-placement="top" title="Compare this Product" ><i class="fa fa-exchange"></i></button>
                      </li>
                      <li>
                        <button type="button" class="quick-view" data-toggle="tooltip" data-placement="top" title="Quick View" ><i class="fa fa-eye"></i></button>
                      </li>
                      <li>
                        <!-- <button type="button" class="addtocart-btn" title="Add to Cart"  > Add to Cart </button> -->
                          <a href="<?php echo site_url('homeshop/buy_2/' . $e->id) ?>" class="addtocart-btn"
                              role="button">Add to Cart</a>
                              <a href="<?php echo site_url('homeshop/product_view/' . $e->id) ?>" class="btn baddtocart-btn btn-sm">View</a> 
                      </li>
                    </ul>
                  </div>
                  <div class="caption product-detail">
                    <div class="rating"> <span class="fa fa-stack"><i class="fa fa-star-o fa-stack-2x"></i><i class="fa fa-star fa-stack-2x"></i></span> <span class="fa fa-stack"><i class="fa fa-star-o fa-stack-2x"></i><i class="fa fa-star fa-stack-2x"></i></span> <span class="fa fa-stack"><i class="fa fa-star-o fa-stack-2x"></i><i class="fa fa-star fa-stack-2x"></i></span> <span class="fa fa-stack"><i class="fa fa-star-o fa-stack-2x"></i><i class="fa fa-star fa-stack-2x"></i></span> <span class="fa fa-stack"><i class="fa fa-star-o fa-stack-2x"></i></span> </div>
                    <h4 class="product-name"><a href="#" title="Casual Shirt With Ruffle Hem"><?php echo $e->prod_name ?></a></h4>
                    <p class="price product-price">INR <?php echo $e->prod_price ?><span class="price-tax">Ex Tax: $100.00</span></p>
                    <p class="product-detail">
                      <?php echo $e->prod_desc ?>
                    </p>
                  </div>
                </div>
              </div>
            </div>
              <?php } ?>
            <?php } ?>
                    
                  </div>
            </div>
       </div>
</div>