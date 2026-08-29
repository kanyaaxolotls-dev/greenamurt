<div class="content col-sm-12">
        <div class="customtab">
          <h3 class="productblock-title"><?php echo $title ?></h3>
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

         <?php 
                foreach ($product as $e)  { ?>
            <div class="product-layout  product-grid  col-lg-3 col-md-4 col-sm-6 col-xs-12">
              <div class="item">
                <div class="product-thumb">
                  <div class="image product-imageblock"> <a href="product.html"> <img src="<?php echo base_url('uploads/' . ($e->image ? $e->image : 'default.jpg')) ?>" alt="<?php echo $e->prod_name ?>" title="<?php echo $e->prod_name ?>" class="img-responsive" /> <img src="<?php echo base_url('uploads/' . ($e->image ? $e->image : 'default.jpg')) ?>" alt="iPod Classic" title="iPod Classic" class="img-responsive" /> </a>
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
     
                    
                  </div>
            </div>
       </div>