<?php
    $filename = $this->uri->segment(2);
    if(1 > 2){
        $b_class = 'template-index diva template-index-diva';
    }
    else{
        $b_class = 'template-collection shop-sidebar-drawer diva';
    }
    $cart_data    = $this->session->userdata('cart_data');
    $wish_data    = $this->session->userdata('wishlist');
    $web_log_data = $this->session->userdata('web_user_data');
?>
<!DOCTYPE html>
<html class="no-js" lang="en">
    <head>
        <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
        <meta http-equiv="x-ua-compatible" content="ie=edge" />
        <title><?php echo config_item('company_name') ?></title>
        <meta name="description" content="description" />
        <meta name="viewport" content="width=device-width, initial-scale=1" />
        <!-- Favicon -->
        <link rel="shortcut icon" href="<?php echo base_url('uploads/logo.png') ?>" />
        <!-- Plugins CSS -->
        <link rel="stylesheet" href="<?php echo base_url('assets2/css/plugins.css') ?>" />
        <!-- Main Style CSS -->
        <link rel="stylesheet" href="<?php echo base_url('assets2/css/style.css') ?>" />
        <link rel="stylesheet" href="<?php echo base_url('assets2/css/responsive.css') ?>" />
        <style>
            .logo-img {
                height: 125px;
            }
            
            @media (max-width: 576px) {
                .logo-img {
                    height: 40px;
                }
            }
                    @keyframes blink {
            0% {
                color: red;
            }
            25% {
                color: green;
            }
            50% {
                color: purple;
            }
            75% {
                color: pink;
            }
            100% {
                color: blue;
            }
        }

        .blink-text {
            font-size: 24px;
            font-weight: bolder;
            animation: blink 1s infinite;
        }
        </style>
    </head>

    <!-- template-collection shop-sidebar-drawer diva -->

    <body class="<?php echo $b_class ?>">
        <!-- Page Loader -->
        <div id="pre-loader"><img src="<?php echo base_url('assets2/images/loader.gif') ?>" alt="Loading..." /></div>
        <!-- End Page Loader -->
        <!-- Page Wrapper -->
        <div class="pageWrapper">

            <!-- Main Header -->
            <div class="header-section classicHeader clearfix animated hdr-sticky">
                <!-- Desktop Header -->
                <div class="header-1 classic-style">
                    <!-- Top Header -->
                    <div class="top-header">
                        <div class="container-fluid">
                            <div class="row">
                                <div class="col-10 col-sm-8 col-md-7 col-lg-4">
                                    <p class="phone-no float-start"><i class="icon an an-phone me-1"></i><a href="tel:+91<?php echo config_item('web_phone') ?>">+91 <?php echo config_item('web_phone') ?></a></p>
                                </div>
                                <div class="col-sm-4 col-md-4 col-lg-4 d-none d-md-none d-lg-block">
                                    <div class="text-center">
                                        <p class="top-header_middle-text"><?php echo config_item('company_name') ?></p>
                                    </div>
                                </div>
                                <div class="col-2 col-sm-4 col-md-5 col-lg-4 text-end d-none d-sm-block d-md-block d-lg-block">
                                    <div class="header-social">
                                        <ul class="justify-content-end list--inline social-icons">
                                            <li><a class="social-icons__link" href="#" target="_blank" data-bs-toggle="tooltip" data-bs-placement="bottom" title="Facebook"><i class="icon an an-facebook"></i> <span class="icon__fallback-text">Facebook</span></a></li>
                                            <li><a class="social-icons__link" href="#" target="_blank" data-bs-toggle="tooltip" data-bs-placement="bottom" title="Twitter"><i class="icon an an-twitter"></i> <span class="icon__fallback-text">Twitter</span></a></li>
                                            <li><a class="social-icons__link" href="#" target="_blank" data-bs-toggle="tooltip" data-bs-placement="bottom" title="Pinterest"><i class="icon an an-pinterest-p"></i> <span class="icon__fallback-text">Pinterest</span></a></li>
                                            <li><a class="social-icons__link" href="#" target="_blank" data-bs-toggle="tooltip" data-bs-placement="bottom" title="Instagram"><i class="icon an an-instagram"></i> <span class="icon__fallback-text">Instagram</span></a></li>
                                            <li><a class="social-icons__link" href="#" target="_blank" data-bs-toggle="tooltip" data-bs-placement="bottom" title="YouTube"><i class="icon icon an an-youtube"></i> <span class="icon__fallback-text">YouTube</span></a></li>
                                        </ul>
                                    </div>
                                </div>
                                <div class="col-2 col-sm-4 col-md-5 col-lg-4 text-end d-block d-sm-none d-md-none d-lg-none">
                                    <!-- Mobile User Links -->
                                    <div class="user-menu-dropdown">
                                        <span class="user-menu"><i class="an an-user-alt"></i></span>
                                        <ul class="customer-links list-inline" style="display:none;">
                                            <li class="item"><a href="<?php echo base_url('site/login');?>">Login</a></li>
                                            <li class="item"><a href="<?php echo base_url('site/register');?>">Register</a></li>
                                           <li class="item"><a href="<?php echo base_url('home/account');?>">Account</a></li>
                                        </ul>
                                    </div>
                                    <!-- End Mobile User Links -->
                                </div>
                            </div>
                        </div>
                    </div>
                    <!-- End Top Header -->

                    <!-- Header -->
                    <div class="header-wrap d-flex">
                        <div class="container-fluid">
                            <div class="row align-items-center">
                                <div class="col-4 col-sm-4 col-md-4 col-lg-8 d-block d-lg-none">
                                    <button type="button" class="btn--link site-header__menu js-mobile-nav-toggle mobile-nav--open" data-bs-toggle="tooltip" data-bs-placement="bottom" title="Menu"><i class="icon an an-times"></i><i class="icon an an-bars"></i></button>
                                    <!-- Mobile Search -->
                                    <!-- End Mobile Search -->
                                </div>
                                <!-- Desktop Logo -->
                                <div class="logo col-4 col-sm-4 col-md-4 col-lg-2 align-self-center">
                                    <a href="#">
                                        <img src="<?php echo base_url('uploads/logo.png') ?>" alt="Logo" title="<?php echo config_item('company_name') ?>" height="60px" class="logo-img" />
                                    </a>
                                </div>
                                <!-- End Desktop Logo -->
                                <!-- Desktop Navigation -->
                                <div class="col-2 col-sm-3 col-md-3 col-lg-8 d-none d-lg-block">
                                    <!-- Desktop Menu -->
                                    <nav class="grid__item" id="AccessibleNav">
                                        <ul id="siteNav" class="d-flex flex-wrap site-nav medium center hidearrow">
                                            <li class="lvl1 parent dropdown" >
                                                <a href="<?php echo base_url('home') ?>" class="text-<?php if($this->uri->segment(2) == NULL){ echo 'danger';} ?>">Home </a>
                                            </li>
                                            <li class="lvl1 parent dropdown">
                                                <a href="#">Shop <i class="an an-angle-down"></i></a>
                                                <ul class="dropdown">
                                                    <?php
                                                        $this->db->select('*');
                                                        $this->db->from('product_categories');
                                                        $categoriess = $this->db->get()->result_array();
                                                        foreach($categoriess as $catt){
                                                    ?>
                                                    <li class="item"><a href="<?= base_url('home/products/'.$catt['id']) ?>"><?= $catt['cat_name'] ?></a></li>
                                                    <?php } ?>
                                                </ul>
                                            </li>
                                            <li class="lvl1 parent dropdown">
                                                <a href="<?php echo base_url('home/about') ?>" class="text-<?php if($this->uri->segment(2) == 'about'){ echo 'danger';} ?>">About Us </a>
                                            </li>
                                            <li class="lvl1 parent dropdown">
                                                <a href="<?php echo base_url('home/contact') ?>" class="text-<?php if($this->uri->segment(2) == 'contact'){ echo 'danger';} ?>">Contact Us </a>
                                            </li>
                                            <li class="lvl1 parent dropdown">
                                                <a href="<?php echo base_url('home/docs') ?>" class="text-<?php if($this->uri->segment(2) == 'docs'){ echo 'danger';} ?>">Documents </a>
                                            </li>
                                            <li class="lvl1 parent dropdown">
                                                <a href="<?php echo base_url('home/privacy') ?>" class="text-<?php if($this->uri->segment(2) == 'privacy'){ echo 'danger';} ?>">Privacy Policy </a>
                                            </li>
                                            <!--<li class="lvl1 parent dropdown">-->
                                            <!--    <a href="<?php echo base_url('site/login') ?>">Login</a>-->
                                            <!--</li>-->
                                            
                                             <li class="lvl1 parent dropdown">
                                                <a href="#">Login <i class="an an-angle-down"></i></a>
                                                <ul class="dropdown">
                                                    <li><a href="<?php echo base_url('site/login') ?>" class="site-nav">User Login</a></li>
                                                    <li><a href="<?php echo base_url('site/franchisee') ?>" class="site-nav">Franchisee Login</a></li>
                                                </ul>
                                            </li>
                                            
                                            <li class="lvl1 parent dropdown">
                                                <a href="<?php echo base_url('site/register') ?>" class="blink-text">Free Registration</a>
                                            </li>
                                            <!-- <li class="lvl1 parent dropdown">-->
                                            <!--    <a href="#">Policie's <i class="an an-angle-down"></i></a>-->
                                            <!--    <ul class="dropdown">-->
                                            <!--        <li><a href="<?php echo base_url('home/privacy') ?>" class="site-nav">Privacy Policy</a></li>-->
                                                
                                            <!--        <li><a href="<?php echo base_url('home/vision') ?>" class="site-nav">Vision & Mission</a></li>-->
                                                
                                            <!--        <li><a href="<?php echo base_url('home/genral') ?>" class="site-nav">General Ethics</a></li>-->
                                              
                                            <!--        <li><a href="<?php echo base_url('home/salient') ?>" class="site-nav">Salient Features</a></li>-->
                                               
                                            <!--        <li><a href="<?php echo base_url('home/return') ?>" class="site-nav">Returns & Exchange</a></li>-->
                                               
                                            <!--        <li><a href="<?php echo base_url('home/violent') ?>" class="site-nav">Violent Instances</a></li>-->
                                                
                                            <!--        <li><a href="<?php echo base_url('home/computation') ?>" class="site-nav">Computation Commissions</a></li>-->
                                            <!--    </ul>-->
                                            <!--</li>-->
                                        </ul>
                                    </nav>
                                    <!-- End Desktop Menu -->
                                </div>
                                <!-- End Desktop Navigation -->
                                <!-- Right Side -->
                                <div class="col-4 col-sm-4 col-md-4 col-lg-2">
                                    <div class="right-action d-flex-align-center justify-content-end">
                                        <!-- User Links -->
                                        <div class="item user-menu-dropdown d-none d-sm-block d-md-block d-lg-block">
                                            <span class="user-menu" data-bs-toggle="tooltip" data-bs-placement="bottom" title="Account"><i class="icon an an-user-alt"></i></span>
                                            <ul class="customer-links list-inline" style="display:none;">
                                                <li class="item"><a href="<?php echo base_url('site/login');?>" target="_blank">Login</a></li>
                                                <li class="item"><a href="<?php echo base_url('site/register');?>" target="_blank">Register</a></li>
                                                <li class="item"><a href="<?php echo base_url('home/account');?>">Account</a></li>
                                            </ul>
                                        </div>
                                        <!-- End User Links -->
                                        <!-- Wishlist -->
                                        <div class="item site-header-wishlist">
                                            <a href="<?php echo base_url('home/wishlist') ?>" class="wishlist-trigger" data-bs-toggle="tooltip" data-bs-placement="bottom" title="Wishlist">
                                                <i class="icon an an-heart"></i>
                                                <span id="WishCount" class="site-header-wish-count">
                                                    <?php 
                                                        if($wish_data == NULL){
                                                            echo  0;
                                                        }
                                                        else{
                                                           echo count($wish_data); 
                                                        }
                                                    ?>
                                                </span>
                                            </a>
                                        </div>
                                        <!-- End Wishlist -->
                                        <!-- Minicart -->
                                        <div class="item site-cart" data-bs-toggle="tooltip" data-bs-placement="bottom" title="Cart">
                                            <a href="#" class="site-header__cart btn-minicart" data-bs-toggle="modal" data-bs-target="#minicart-drawer">
                                                <i class="icon an an-shopping-bag"></i>
                                                    <span id="CartCount" class="site-header__cart-count">
                                                        <?php 
                                                            if($cart_data == NULL){
                                                                echo  0;
                                                            }
                                                            else{
                                                               echo count($cart_data);
                                                            }
                                                        ?>
                                                    </span>
                                            </a>  
                                        </div>
                                        <!-- End Minicart -->
                                    </div>
                                </div>
                                <!-- End Right Side -->
                            </div>
                        </div>
                    </div>
                    <!-- End Header -->
                </div>
                <!-- End Desktop Header -->
            </div>
            <!-- End Main Header -->

            <!-- Mobile Menu -->
            <div class="mobile-nav-wrapper fixed-top" role="navigation">
                <div class="closemobileMenu">
                    <i class="icon an an-times-circle closemenu"></i> Close Menu</div>
                    <ul id="MobileNav" class="mobile-nav">
                        <li class="lvl1 parent dropdown">
                            <a href="<?php echo base_url('home') ?>">Home </a>
                        </li>
                        <li class="lvl1 parent dropdown">
                            <a href="<?php echo base_url('home/products') ?>">Shop </a>
                        </li>
                        <li class="lvl1 parent dropdown">
                            <a href="<?php echo base_url('home/about') ?>">About Us </a>
                        </li>
                        <li class="lvl1 parent dropdown">
                            <a href="<?php echo base_url('home/contact') ?>">Contact Us </a>
                        </li>
                        <li class="lvl1 parent dropdown">
                            <a href="<?php echo base_url('home/docs') ?>">Document</a>
                        </li>
                        <!--<li class="lvl1 parent dropdown">-->
                        <!--    <a href="<?php echo base_url('home/privacy') ?>">Privacy Policy </a>-->
                        <!--</li>-->
                        <li class="lvl1 parent dropdown">
                            <a href="<?php echo base_url('site/login') ?>">Login</a>
                        </li>
                        <li class="lvl1 parent dropdown">
                            <a href="<?php echo base_url('site/register') ?>">Register</a>
                        </li>
                    </ul>
                </div>
            </div>
            <!-- End Mobile Menu -->
            
            