<!DOCTYPE html>
<html lang="en">
 
<head>
  <meta charset="utf-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
  <title>
    Welcome | <?php echo config_item('company_name') ?>
  </title>
  <!-- Favicon -->

  <link href=" <?php echo base_url('axxets/member/Modern/img/brand/favicon.png') ?>" rel="icon" type="image/png">
  <!-- Fonts -->
  <link href="https://fonts.googleapis.com/css?family=Open+Sans:300,400,600,700" rel="stylesheet">
  <!-- Icons --> 

  <link href="<?php echo base_url('axxets/member/Modern/js/plugins/nucleo/css/nucleo.css') ?>" rel="stylesheet" />
  <link href="<?php echo base_url('axxets/member/Modern/js/plugins/@fortawesome/fontawesome-free/css/all.min.css')?>" rel="stylesheet" />
  <!-- CSS Files -->
  <link href="<?php echo base_url('axxets/member/Modern/css/argon-dashboard.css?v=1.1.0')?>" rel="stylesheet" />

  <script src="https://apis.google.com/js/platform.js" async defer></script>
  <meta name="google-signin-client_id" content="18584371115-jboo35s76h18n08n8jmsdn2qjikfjaf5.apps.googleusercontent.com">
  
</head>


<body class="bg-default">
  <div class="main-content"> 
    <!-- Navbar -->
    <nav class="navbar navbar-top navbar-horizontal navbar-expand-md navbar-dark">
      <div class="container px-4">
        <a class="navbar-brand" href="<?php echo  config_item('siteurl') ?>">
          <img src="<?php echo base_url('uploads/logo.png') ?>"/>
        </a>
        <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbar-collapse-main" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
          <span class="navbar-toggler-icon"></span>
        </button>
        <div class="collapse navbar-collapse" id="navbar-collapse-main">
          <!-- Collapse header -->
          <div class="navbar-collapse-header d-md-none">
            <div class="row">
              <div class="col-6 collapse-brand">
                <a href="<?php echo base_url('../index.php') ?>">
                  <img src="<?php echo base_url('uploads/logo.png') ?>"> 
                </a>
              </div>
              <div class="col-6 collapse-close">
                <button type="button" class="navbar-toggler" data-toggle="collapse" data-target="#navbar-collapse-main" aria-controls="sidenav-main" aria-expanded="false" aria-label="Toggle sidenav">
                  <span></span>
                  <span></span>
                </button>
              </div>
            </div>
          </div>
          <!-- Navbar items -->
          <ul class="navbar-nav ml-auto">
            <li class="nav-item">
              <a class="nav-link nav-link-icon" href="<?php echo  config_item('siteurl') ?>">
                <i class="ni ni-planet"></i>
                <span class="nav-link-inner--text">Home</span>
              </a>
            </li>
            <li class="nav-item">
              <a class="nav-link nav-link-icon" href="<?php echo site_url('site/register') ?>">
                <i class="ni ni-circle-08"></i>
                <span class="nav-link-inner--text">Sign up</span>
              </a>
            </li>
            <li class="nav-item">
              <a class="nav-link nav-link-icon" href="<?php echo site_url('site/login') ?>">
                <i class="ni ni-key-25"></i>
                <span class="nav-link-inner--text">Login</span>
              </a>
            </li>
           <!--  <li class="nav-item">
              <a class="nav-link nav-link-icon" href="<?php echo site_url('site/franchisee') ?>">
                <i class="ni ni-single-02"></i>
                <span class="nav-link-inner--text">Franchisee</span>
              </a>
            </li> -->
           <!--  <li class="nav-item">
              <a class="nav-link nav-link-icon" href="<?php echo site_url('mobileshop/home') ?>">
                <i class="ni ni-single-02"></i>
                <span class="nav-link-inner--text">Shop</span>
              </a>
            </li> -->
          </ul>
        </div>
      </div>
    </nav>
    <!-- Header -->
      <!-- Header -->

      <!-- Page content -->
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
 

    <footer class="py-5">
      <div class="container">
        <div class="row align-items-center justify-content-xl-between">
          <div class="col-xl-6">
            <div class="copyright text-center text-xl-left text-muted">
              © 2021 <a href="<?php echo  config_item('siteurl') ?>" class="font-weight-bold ml-1" target="_blank"><?php echo config_item('company_name') ?></a>
            </div>
          </div>
          <!-- <div class="col-xl-6">
            <ul class="nav nav-footer justify-content-center justify-content-xl-end">
              <li class="nav-item">
                <a href="https://www.creative-tim.com" class="nav-link" target="_blank">Creative Tim</a>
              </li>
              <li class="nav-item">
                <a href="https://www.creative-tim.com/presentation" class="nav-link" target="_blank">About Us</a>
              </li>
              <li class="nav-item">
                <a href="http://blog.creative-tim.com" class="nav-link" target="_blank">Blog</a>
              </li>
              <li class="nav-item">
                <a href="https://github.com/creativetimofficial/argon-dashboard/blob/master/LICENSE.md" class="nav-link" target="_blank">MIT License</a>
              </li>
            </ul>
          </div>
        </div> -->
      </div>
    </footer>
  </div>
  <!--   Core   -->
  <script src="<?php echo base_url('axxets/member/Modern/js/plugins/jquery/dist/jquery.min.js')?>"></script>
  <script src="<?php echo base_url('axxets/member/Modern/js/plugins/bootstrap/dist/js/bootstrap.bundle.min.js')?>"></script>
  <!--   Optional JS   -->
  <!--   Argon JS   -->
  <script src="<?php echo base_url('axxets/member/Modern/js/argon-dashboard.min.js?v=1.1.0')?>"></script>
  <script src="https://cdn.trackjs.com/agent/v3/latest/t.js"></script>
  <script>
    window.TrackJS &&
      TrackJS.install({
        token: "ee6fab19c5a04ac1a32a645abde4613a",
        application: "argon-dashboard-free"
      });
  </script>
</body>

</html>