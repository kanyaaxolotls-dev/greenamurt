
<div class="breadcrumb">
  <!-- 
  <h1>View Product</h1> -->
   <!--  <ul>
      <li><a href="index.html">Home</a></li>
      <li><a href="category.html">Desktops</a></li>
      <li><a href="#">Casual Shirt With Ruffle Hem</a></li>
    </ul> -->
</div>

    <div class="content col-sm-12">
      <div class="row">
        <div class="col-sm-5"> 
          <div class="thumbnails"> 
            <div><a class="thumbnail fancybox" href="<?php echo base_url('uploads/' . ($data->image ? $data->image : 'default.jpg')) ?>" title="Casual Shirt With Ruffle Hem"><img src="<?php echo base_url('uploads/' . ($data->image ? $data->image : 'default.jpg')) ?>" title="Buy Product" alt="<?php $data->name;?>" /></a></div>
          
            <div id="product-thumbnail" class="owl-carousel">
              <div class="item">
                <div class="image-additional"><a class="thumbnail fancybox" href="<?php echo base_url('uploads/' . ($data->image ? $data->image : 'default.jpg')) ?>" title="iPod Classic"> <img src="<?php echo base_url('uploads/' . ($data->image ? $data->image : 'default.jpg')) ?>" title="iPod Classic" alt="iPod Classic" /></a></div>
              </div>
              <div class="item">
                <div class="image-additional"><a class="thumbnail fancybox" href="image/product/product2.jpg" title="iPod Classic"> <img src="<?php echo base_url('uploads/' . ($data->image ? $data->image : 'default.jpg')) ?>" title="iPod Classic" alt="iPod Classic" /></a></div>
              </div>
              <div class="item">
                <div class="image-additional"><a class="thumbnail fancybox" href="image/product/product3.jpg" title="iPod Classic"> <img src="<?php echo base_url('uploads/' . ($e->image ? $e->image : 'default.jpg')) ?>" title="iPod Classic" alt="iPod Classic" /></a></div>
              </div>
              <div class="item">
                <div class="image-additional"><a class="thumbnail fancybox" href="image/product/product4.jpg" title="iPod Classic"> <img src="<?php echo base_url('uploads/' . ($e->image ? $e->image : 'default.jpg')); ?>" title="iPod Classic" alt="iPod Classic" /></a></div>
              </div>
              <div class="item">
                <div class="image-additional"><a class="thumbnail fancybox" href="image/product/product5.jpg" title="iPod Classic"> <img src="<?php echo base_url('uploads/' . ($e->image ? $e->image : 'default.jpg')); ?>" title="iPod Classic" alt="iPod Classic" /></a></div>
              </div>
             
            </div>
          </div>
        </div>
        <div class="col-sm-7 prodetail">
          <h1 class="productpage-title"><?php echo $data->prod_name ?></h1>
          <div class="rating"> <span class="fa fa-stack"><i class="fa fa-star-o fa-stack-2x"></i><i class="fa fa-star fa-stack-2x"></i></span> <span class="fa fa-stack"><i class="fa fa-star-o fa-stack-2x"></i><i class="fa fa-star fa-stack-2x"></i></span> <span class="fa fa-stack"><i class="fa fa-star-o fa-stack-2x"></i><i class="fa fa-star fa-stack-2x"></i></span> <span class="fa fa-stack"><i class="fa fa-star-o fa-stack-2x"></i><i class="fa fa-star fa-stack-2x"></i></span> <span class="fa fa-stack"><i class="fa fa-star-o fa-stack-2x"></i></span><span class="riview"><a href="#">1 reviews</a> / <a href="#">Write a review</a></span> </div>
          <ul class="list-unstyled productinfo-details-top">
            <li>
              <h2 class="productpage-price"><?php echo config_item('currency') . $data->prod_price ?></h2>
            </li>
          <!--   <li><span class="productinfo-tax">Ex Tax: $100.00</span></li> -->
          </ul>
          <hr>
          <ul class="list-unstyled product_info">
            <li>
              <label>Category:</label>
              <span> <a href="#"><?php echo $data->prod_name ?></a></span></li>
         <!--    <li>
              <label>Product Code:</label>
              <span> product 20</span></li> -->
            <li>
              <label>Availability:</label>
              <span style="color: red"> <?php if ($data->qty < 0){ echo "No stock"; ?></span>
              <span>
              <?php }else{echo $data->qty;}?>
              </span>
            </li>
            <li>
              <label>Discounted Price:</label>
              <span style="color: red"> <?php echo $data->sold_qty ?></span>
            </li>

          </ul>
          <hr>
          <p class="product-desc"> <?php echo $data->prod_desc ?></p>
          <div id="product">
            <div class="form-group">
          <!--     <div class="row">
                <div class="Sort-by col-md-6">
                  <label>Sort by</label>
                  <select id="select-by-size" class="selectpicker form-control">
                    <option value="#" selected="selected">Small</option>
                    <option value="#">Medium</option>
                    <option value="#">Large</option>
                  </select>
                </div>
                <div class="Color col-md-6">
                  <label>Color</label>
                  <select id="select-by-color" class="selectpicker form-control">
                    <option value="#" selected="selected">Blue</option>
                    <option value="#">Green</option>
                    <option value="#">Orange</option>
                    <option value="#">White</option>
                  </select>
                </div>
              </div> -->
              <div class="qty">
                <label>Qty</label>
                <input id="qty" placeholder="1" type="number">
                <ul class="button-group list-btn">
                   <li>
                   <a href="<?php echo site_url('homeshop/buy_2/' . $data->id) ?>" class="addtocart-btn" role="button">Add to cart</a>
                  </li>
 
                  <li>
                    <button type="button" class="wishlist" data-toggle="tooltip" data-placement="top" title="Add to Wish List" ><i class="fa fa-heart-o"></i></button>
                  </li>
                 
                  <li>
                    <button type="button" class="compare" data-toggle="tooltip" data-placement="top" title="Compare this Product" ><i class="fa fa-exchange"></i></button>
                  </li>
                  <li>
                    <button type="button" class="quick-view" data-toggle="tooltip" data-placement="top" title="Quick View" ><i class="fa fa-eye"></i></button>
                  </li>
                 
                </ul>
                
              </div>
            </div>
          </div>
        </div>
      </div>
