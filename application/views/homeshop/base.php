<!DOCTYPE html>
<html lang="en">
<head> 
<title>Shop | <?php echo config_item('company_name') ?></title>
<meta http-equiv="content-type" content="text/html;charset=utf-8" />
<meta name="viewport" content="width=device-width, initial-scale=1">
<meta name="description" content="e-commerce site well design with responsive view." />
<meta http-equiv="X-UA-Compatible" content="IE=edge">
<link href="image/favicon.png" rel="icon" type="image/png" >
<link href="<?php echo base_url('axxets/shop/css/bootstrap.min.css')?>" rel="stylesheet" media="screen" />
<link href="<?php echo base_url('axxets/shop/javascript/font-awesome/css/font-awesome.css')?>" rel="stylesheet" type="text/css" />
<link href="https://fonts.googleapis.com/css?family=Montserrat:300,400,700" rel="stylesheet"/>
<link href="<?php echo base_url('axxets/shop/css/stylesheet.css')?>" rel="stylesheet">
<link href="<?php echo base_url('axxets/shop/css/responsive.css')?>" rel="stylesheet">
<link href="<?php echo base_url('axxets/shop/javascript/owl-carousel/owl.carousel.css')?>" type="text/css" rel="stylesheet" media="screen" />
<link href="<?php echo base_url('axxets/shop/javascript/owl-carousel/owl.transitions.css')?>" type="text/css" rel="stylesheet" media="screen" />
<script type="text/javascript" src="<?php echo base_url('axxets/shop/javascript/jquery-2.1.1.min.js')?>" ></script>
<script type="text/javascript" src="<?php echo base_url('axxets/shop/javascript/bootstrap/js/bootstrap.min.js')?>" ></script>
<script type="text/javascript" src="<?php echo base_url('axxets/shop/javascript/template_js/jstree.min.js')?>"></script>
<script type="text/javascript" src="<?php echo base_url('axxets/shop/javascript/template_js/template.js')?>"></script>
<script type="text/javascript" src="<?php echo base_url('axxets/shop/javascript/common.js')?>" ></script>
<script type="text/javascript" src="<?php echo base_url('axxets/shop/javascript/global.js')?>" ></script>
<script type="text/javascript" src="<?php echo base_url('axxets/shop/javascript/owl-carousel/owl.carousel.min.js')?>" ></script>

</head>
<body class="index">
<div class="preloader loader" style="display: block;"> <img src="image/loader.gif"  alt="#"/></div>
<header>
   <?php  
          $this->db->select('rank,id,name,sponsor');
          $this->db->where('id', config_item('IT_EXT') . $this->session->user_id);
          $q = $this->db->get('member');
          $data1 = $q->result_array(); ?>
  <div class="header-top">
    <div class="container">
      <div class="row">
        <div class="col-sm-12">
          <div class="top-left pull-left">
            <div class="language">
              <form action="#" method="post" enctype="multipart/form-data" id="language">
                <div class="btn-group">
                  <button class="btn btn-link dropdown-toggle" data-toggle="dropdown" aria-expanded="false"> English <span class="caret"></span></button>
                  <ul class="dropdown-menu">
                    <li><a href="#">Arabic</a></li>
                    <li><a href="#"> English</a></li>
                    <li><a href="#"> French</a></li>
                  </ul>
                </div>
              </form>
            </div>
            <div class="currency">
              <form action="#" method="post" enctype="multipart/form-data" id="currency">
                <div class="btn-group">
                  <button class="btn btn-link dropdown-toggle" data-toggle="dropdown"> <strong>USD</strong> <span class="caret"></span> </button>
                  <ul class="dropdown-menu">
                    <li><a href="#">Euro</a></li>
                    <li><a href="#">Pound</a></li>
                    <li><a href="#">USD</a></li>
                  </ul>
                </div>
              </form>
            </div>
            <div class="wel-come-msg"> Welcome to our online store! </div>
          </div>
          <div class="top-right pull-right">
            <div id="top-links" class="nav pull-right">

              <ul class="list-inline">
                <li> <a style="color:#fff">Hello! <?php if($this->session->_user_name_==''){
                    echo "Guest";
                } else{echo $this->session->_user_name_;} ?></a> </li>
                <li class="dropdown"><a href="#" title="My Account" class="dropdown-toggle" data-toggle="dropdown"><i class="fa fa-user" aria-hidden="true"></i><span>My Account</span> <span class="caret"></span></a>
                  <ul class="dropdown-menu dropdown-menu-right">
                    <li><a href="<?php echo site_url('site/register') ?>">Register</a></li>
                    <li><a href="<?php echo site_url('site/login') ?>">Login</a></li>
                    <li><a href="<?php echo site_url('site/franchisee') ?>">Franchisee</a></li>
                    
                  </ul>
                </li>
                <li><a href="#" id="wishlist-total" title="Wish List (0)"><i class="fa fa-heart" aria-hidden="true"></i><span>Wish List</span><span> (0)</span></a></li>
              </ul>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
  <div class="container">
    <div class="header-inner">
      <div class="col-sm-3 col-xs-3 header-left">
        <div id="logo"> <a href="<?php echo base_url('member/index') ?>"><img src="<?php echo base_url('uploads/logo.png') ?>" title="E-Commerce" alt="E-Commerce" class="img-responsive" /></a> </div>
      </div>
      <div class="col-sm-9 col-xs-9 header-right">
        <div id="search" class="input-group">
          <input type="text" name="search" value="" placeholder="Enter your keyword ..." class="form-control input-lg" />
          <span class="input-group-btn">
          <button type="button" class="btn btn-default btn-lg"><span>Search</span></button>
          </span> </div>
        <div id="cart" class="btn-group btn-block">
          <button type="button" class="btn btn-inverse btn-block btn-lg dropdown-toggle cart-dropdown-button"> <span id="cart-total">
          
          <a style="color: #ff8f00" href="<?php echo site_url('homeshop/pre_checkout') ?>">
          <span>Shopping Cart</span><br>  
          <?php echo count($this->cart->contents()) ?>  item(s) - <?php 
           echo "INR ".$this->cart->format_number($this->cart->total());?></span></a> 
         </button>

         <!--  <ul class="dropdown-menu pull-right cart-dropdown-menu">
            <li>
              <table class="table table-striped">
                <tbody>
                  <tr>
                    <td class="text-center"><a href="#"><img class="img-thumbnail" title="lorem ippsum dolor dummy" alt="lorem ippsum dolor dummy" src="image/product/7product56x72.jpg"></a></td>
                    <td class="text-left"><a href="#">lorem ippsum dolor dummy</a></td>
                    <td class="text-right">x 1</td>
                    <td class="text-right">$254.00</td>
                    <td class="text-center"><button class="btn btn-danger btn-xs" title="Remove" type="button"><i class="fa fa-times"></i></button></td>
                  </tr>
                </tbody>
              </table>
            </li>
            <li>
              <table class="table table-striped">
                <tbody>
                  <tr>
                    <td class="text-center"><a href="#"><img class="img-thumbnail" title="lorem ippsum dolor dummy" alt="lorem ippsum dolor dummy" src="<?php echo base_url('axxets/shop/image/product/7product56x72.jpg')?>"></a></td>
                    <td class="text-left"><a href="#">lorem ippsum dolor dummy</a></td>
                    <td class="text-right">x 1</td>
                    <td class="text-right">$254.00</td>
                    <td class="text-center"><button class="btn btn-danger btn-xs" title="Remove" type="button"><i class="fa fa-times"></i></button></td>
                  </tr>
                </tbody>
              </table>
            </li>
            <li>
              <table class="table table-striped">
                <tbody>
                  <tr>
                    <td class="text-center"><a href="#"><img class="img-thumbnail" title="lorem ippsum dolor dummy" alt="lorem ippsum dolor dummy" src="<?php echo base_url('axxets/shop/image/product/7product56x72.jpg')?>"></a></td>
                    <td class="text-left"><a href="#">lorem ippsum dolor dummy</a></td>
                    <td class="text-right">x 1</td>
                    <td class="text-right">$254.00</td>
                    <td class="text-center"><button class="btn btn-danger btn-xs" title="Remove" type="button"><i class="fa fa-times"></i></button></td>
                  </tr>
                </tbody>
              </table>
            </li>
            <li>
              <div>
                <table class="table table-bordered">
                  <tbody>
                    <tr>
                      <td class="text-right"><strong>Sub-Total</strong></td>
                      <td class="text-right">$210.00</td>
                    </tr>
                    <tr>
                      <td class="text-right"><strong>Eco Tax (-2.00)</strong></td>
                      <td class="text-right">$2.00</td>
                    </tr>
                    <tr>
                      <td class="text-right"><strong>VAT (20%)</strong></td>
                      <td class="text-right">$42.00</td>
                    </tr>
                    <tr>
                      <td class="text-right"><strong>Total</strong></td>
                      <td class="text-right">$254.00</td>
                    </tr>
                  </tbody>
                </table>
                <p class="text-right"> <span class="btn-viewcart"><a href="cart.html"><strong><i class="fa fa-shopping-cart"></i> View Cart</strong></a></span> <span class="btn-checkout"><a href="checkout.html"><strong><i class="fa fa-share"></i> Checkout</strong></a></span> </p>
              </div>
            </li>
          </ul> -->
        </div>
      </div>
    </div>
  </div>
</header>
<nav id="menu" class="navbar">
  <div class="nav-inner">
    <div class="navbar-header"><span id="category" class="visible-xs">Categories</span>
      <button type="button" class="btn btn-navbar navbar-toggle" ><i class="fa fa-bars"></i></button>
    </div>
    <div class="navbar-collapse">
      <ul class="main-navigation"> 
       <!--   <?php
          if (!$product) {
            foreach ($categories as $e) { ?>
               <li><a href="<?php echo site_url('homeshop/show_products/' . $e->id) ?>"   class="parent"  ><?php echo $e->cat_name ?></a>  </li>
                      
            <?php }
          } ?>  -->

        <li><a href="<?php echo site_url('homeshop/new-purchase') ?>" class="parent"  >Home</a> </li>
        <li><a href="<?php echo site_url('homeshop/shop_more/16') ?>" class="parent"  >All Categories</a> </li>
        <li><a href="category.html"   class="parent"  >Women</a> </li>
        <li><a href="category.html"   class="parent"  >Men</a> </li>
        <li><a href="#" class="active parent">Page</a>
          <ul> 
            <li><a href="<?php echo site_url('homeshop/shop_more') ?>">Category Page</a></li>
            <li><a href="cart.html">Cart Page</a></li>
            <li><a href="checkout.html">Checkout Page</a></li>
            <li><a href="blog.html" >Blog Page</a></li>
            <li><a href="single-blog.html" >Singale Blog Page</a></li>
            <li><a href="register.html">Register Page</a></li>
            <li><a href="contact.html">Contact Page</a></li>
            <li><a href="affiliate.html">Affiliate</a></li>
            <li><a href="forgetpassword.html">Forget Password</a></li>
          </ul>
        </li>
        <li><a href="blog.html" class="parent"  >Blog</a></li>
        <li><a href="<?php //echo site_url('homeshop/about_us') ?>" >About us</a></li>
        <li><a href="contact.html" >Contact Us</a> </li>
      </ul>
    </div>
  </div>
</nav>
    <div class="row">
      <div class="container">
      <?php 
                    echo validation_errors('<div class="alert alert-danger">', '</div>');
                    echo $this->session->flashdata('common_flash'); 
                    if (trim($layout) !== "") {
                     
                          include_once $layout;   } 
                  ?>

    
          </div>
        </div>
    
<div class="container">
  <h3 class="client-title">Favourite Brands</h3>
  <h4 class="title-subline">The High Quality Products</h4>
  <div id="brand_carouse" class="owl-carousel brand-logo">
    <div class="item text-center"> <a href="#"><img src="<?php echo base_url('axxets/shop/image/brand/brand1.png')?>" alt="Disney" class="img-responsive" /></a> </div>
    <div class="item text-center"> <a href="#"><img src="<?php echo base_url('axxets/shop/image/brand/brand2.png')?>" alt="Dell" class="img-responsive" /></a> </div>
    <div class="item text-center"> <a href="#"><img src="<?php echo base_url('axxets/shop/image/brand/brand3.png')?>" alt="Harley" class="img-responsive" /></a> </div>
    <div class="item text-center"> <a href="#"><img src="<?php echo base_url('axxets/shop/image/brand/brand4.png')?>" alt="Canon" class="img-responsive" /></a> </div>
    <div class="item text-center"> <a href="#"><img src="<?php echo base_url('axxets/shop/image/brand/brand5.png')?>" alt="Canon" class="img-responsive" /></a> </div>
    <div class="item text-center"> <a href="#"><img src="<?php echo base_url('axxets/shop/image/brand/brand6.png')?>" alt="Canon" class="img-responsive" /></a> </div>
    <div class="item text-center"> <a href="#"><img src="<?php echo base_url('axxets/shop/image/brand/brand7.png')?>" alt="Canon" class="img-responsive" /></a> </div>
    <div class="item text-center"> <a href="#"><img src="<?php echo base_url('axxets/shop/image/brand/brand8.png')?>" alt="Canon" class="img-responsive" /></a> </div>
    <div class="item text-center"> <a href="#"><img src="<?php echo base_url('axxets/shop/image/brand/brand9.png')?>" alt="Canon" class="img-responsive" /></a> </div>
    <div class="item text-center"> <a href="#"><img src="<?php echo base_url('axxets/shop/image/brand/brand5.png')?>" alt="Canon" class="img-responsive" /></a> </div>
  </div>
</div>
<footer>
  <div class="cms_searvice">
    <div class="container">
      <div class="row">
        <div class="col-md-3 ">
          <div class="cms-block1 z-depth-5">
            <h4>Free Shipping</h4>
            <p>All order over $150</p>
          </div>
        </div>
        <div class="col-md-3 ">
          <div class="cms-block2">
            <h4>30 Days Returns</h4>
            <p>Money Back Guarantee</p>
          </div>
        </div>
        <div class="col-md-3 ">
          <div class="cms-block3">
            <h4>24/7 Support</h4>
            <p>Feel free to Contact us</p>
          </div>
        </div>
        <div class="col-md-3 ">
          <div class="cms-block4">
            <h4>Online Shopping </h4>
            <p>Save Up to 70% on Store</p>
          </div>
        </div>
      </div>
    </div>
  </div>
  <div class="container">
    <div class="row">
      <div class="col-sm-3 footer-block">
        <h5 class="footer-title">INFORMATION</h5>
        <ul class="list-unstyled ul-wrapper">
          <li><a href="<?php echo site_url('homeshop/about_us') ?>">About Us</a></li>
      
          <li><a href="<?php echo site_url('homeshop/privacy_policy') ?>">Privacy Policy</a></li>
          <li><a href="<?php echo site_url('homeshop/terms_of_service') ?>">Terms & Conditions</a></li>
       
        </ul>
      </div>
      <div class="col-sm-3 footer-block">
        <h5 class="footer-title">MY ACCOUNT</h5>
        <ul class="list-unstyled ul-wrapper">
          <li><a href="<?php echo site_url('homeshop/contact_us') ?>">Contact Us</a></li>
          <li><a href="<?php echo site_url('site/login') ?>">Sign In</a></li>
          <li><a href="<?php echo site_url('site/register') ?>">Sign Up</a></li>
        <!--   <li><a href="#">Order History</a></li>
          <li><a href="#">Wish List</a></li>
          <li><a href="#">Newsletter</a></li>
          <li><a href="#">Gift Certificates</a></li> -->
       
        <!--   <li><a href="#">Affiliates</a></li> -->
        </ul>
      </div>
      <div class="col-sm-3 footer-block">
        <h5 class="footer-title">Extras</h5>
        <ul class="list-unstyled ul-wrapper">
        
           <li><a href="#">Newsletter</a></li>
          <li><a href="#">Sitemap</a></li>
         
          <li><a href="#">Help & FAQs</a></li>
         
        </ul>
      </div>
      <div class="col-sm-3 footer-block">
        <div class="content_footercms_right">
          <div class="footer-contact">
            <h5 class="contact-title footer-title">Contact Us</h5>
            <ul class="ul-wrapper">
              <li><i class="fa fa-map-marker"></i><span class="location2">Offices Addresss:<br>
                <?php echo config_item('company_name') ?></br>
                <?php echo config_item('company_address') ?>
              </li>
              <li><i class="fa fa-envelope"></i><span class="mail2"><a href="#">info@localhost.com</a><br>
              </span></li>
              <li><i class="fa fa-mobile"></i><span class="phone2">
                +91 0987-654-321</span></li>
            </ul>
          </div>
        </div>
      </div>
    </div>
  </div>
  <div class="footer-bottom">
    <div id="bottom-footer">
      <ul class="footer-link">
        <li><a href="<?php echo site_url('homeshop/new-purchase') ?>">Home</a></li>
        <li><a href="<?php echo site_url('homeshop/about_us') ?>">About</a></li>
     
        <li><a href="#">Blog</a></li>
        <li><a href="<?php echo site_url('homeshop/contact_us') ?>">Contact</a></li>
      </ul>
      <div class="copyright"> Copyright - <a class="yourstore" href="http://www.lionode.com/"> <?php echo config_item('company_name') ?> &copy; 2019 </a></div>
      <div class="footer-bottom-cms">
        <div class="footer-payment">
          <ul>
            <li class="mastero"><a href="#"><img alt="" src="<?php echo base_url('axxets/shop/image/payment/mastero.jpg')?>"></a></li>
            <li class="visa"><a href="#"><img alt="" src="<?php echo base_url('axxets/shop/image/payment/visa.jpg')?>"></a></li>
            <li class="currus"><a href="#"><img alt="" src="<?php echo base_url('axxets/shop/image/payment/currus.jpg')?>"></a></li>
            <li class="discover"><a href="#"><img alt="" src="<?php echo base_url('axxets/shop/image/payment/discover.jpg')?>"></a></li>
          </ul>
        </div>
      </div>
    </div>
  </div>
  <a id="scrollup">Scroll</a>
</footer>

<script src="javascript/jquery.parallax.js"></script> 
<script>
    jQuery(document).ready(function ($) {
        $('.parallax').parallax();
    });
</script>
</body>
</html>
