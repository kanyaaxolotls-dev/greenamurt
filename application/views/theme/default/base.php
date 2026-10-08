<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <title><?php echo config_item('company_name') ?></title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
    <meta name="description" content=""/>
    <!-- css -->
    <link href="<?php echo base_url('axxets/site/default/css/bootstrap.min.css') ?>" rel="stylesheet"/>
    <!-- <link href="<?php echo base_url('axxets/site/default/css/style.css') ?>" rel="stylesheet"/> -->
<link href="<?php echo base_url('axxets/member/dark.css') ?>" rel="stylesheet"/> 
    <link rel="stylesheet" type="text/css"
          href="<?php echo base_url(config_item('member_theme')) ?>" rel="stylesheet" id="rt_style_components"
          type="text/css"/> 
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css"
          rel="stylesheet"
          type="text/css"/>

    <script src="<?php echo base_url('axxets/member/geomap_custom.js') ?>"
            type="text/javascript"></script>

</head>
<body>
<div id="wrapper" class="home-page">
    <!--<div class="topbar">
        <div class="container">
            <div class="row">
                <div class="col-md-12">
                    <p class="pull-left hidden-xs"><i class="fa fa-lock"></i><span>SSL Secured</span>
                    </p>
                    <p class="pull-right"></p>
                </div>
            </div>  
        </div>
    </div>-->
    <!-- start header -->
    <?php ?>
    <header>
        <div class="navbar navbar-default navbar-static-top">
            <div class="container">
                <div class="navbar-header">
                    <button type="button" class="navbar-toggle" data-toggle="collapse" data-target=".navbar-collapse">
                        <span class="icon-bar"></span>
                        <span class="icon-bar"></span>
                        <span class="icon-bar"></span>
                    </button>
                    <a class="navbar-brand" href=""><img src="<?php echo base_url('uploads/logo.png') ?>"
                                                         style="max-height: 50px" alt="logo"/></a>
                </div>
                <div class="navbar-collapse collapse ">
                    <ul class="nav navbar-nav">
                        <!--<li class="active"><a href="http://<?php echo $_SERVER['HTTP_HOST'] ?>
                        ">Home</a></li>-->
                        <li><a href="http://<?php echo $_SERVER['HTTP_HOST'] ?>" >Home</a></li>
                        <li><a href="<?php echo site_url('site/register') ?>">Register</a></li>
                        <li><a href="<?php echo site_url('site/login') ?>">Login</a></li>
                        <li><a href="<?php echo site_url('site/franchisee') ?>">Retailer Login</a></li>
                        <li><a href="<?php echo site_url('homeshop/new-purchase') ?>">Shop</a></li>
                    </ul>
                </div>
            </div>
        </div>
    </header>
    <!-- end header --> 
    <?php if (trim($layout) == "") { ?>
        <div style="margin: 10%">
            <h2 align="center">Welcome to <?php echo config_item('company_name') ?></h2>
            <div align="center">Please click above to login or sign up</div>
        </div>
    <?php }
    else {
        include_once(APPPATH . "views/theme/" . $layout);
    } ?>
  <!--  <footer>
        <div class="container">
            <div class="row">
                Copyright &copy; All right reserved by <?php //echo config_item('company_name') ?>
            </div>
        </div>
    </footer>-->
</div>
<a href="#" class="scrollup"><i class="fa fa-angle-up active"></i></a>
<!-- javascript
    ================================================== -->
<!-- Placed at the end of the document so the pages load faster -->
<script src="<?php echo base_url('axxets/site/default/js/jquery.js') ?>"></script>
<script src="<?php echo base_url('axxets/site/default/js/bootstrap.min.js') ?>"></script>
 <script src="https://maps.googleapis.com/maps/api/js?key=AIzaSyDY2fxOmeuNjJbL6NfuJ09b5-SY015-4Fo&libraries=places&callback=initAutocomplete"
         async defer></script>
</body>
</html>
