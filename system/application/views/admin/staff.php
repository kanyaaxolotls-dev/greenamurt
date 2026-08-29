<!--
 
=========================================================
* Argon Dashboard - v1.1.0
=========================================================

* Product Page: https://www.creative-tim.com/product/argon-dashboard
* Copyright 2019 Creative Tim (https://www.creative-tim.com)
* Licensed under MIT (https://github.com/creativetimofficial/argon-dashboard/blob/master/LICENSE.md)

* Coded by Creative Tim

========================== ===============================

* The above copyright notice and this permission notice shall be included in all copies or substantial portions of the Software. -->
<?php
//if ($this->login->check_session() == FALSE) {
  //  header('HTTP/1.0 404 Not Found', TRUE, 404);
    //exit('Page Not Found !');
//}
?>
 

<!DOCTYPE html>
<html lang="en">
 
<head>
  <meta charset="utf-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
  <title>
    Admin Dashboard | <?php echo config_item('company_name') ?>
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
  
</head>


<body class="bg-default">
  <div class="main-content">
      <!-- Navbar -->
    <nav class="navbar navbar-top navbar-horizontal navbar-expand-md navbar-dark">
      <div class="container px-4">
        <a class="navbar-brand" href="<?php echo base_url('../index.php') ?>">
          <img src="<?php echo base_url('uploads/logo.png') ?>" />
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
            <!--<li class="nav-item">-->
            <!--  <a class="nav-link nav-link-icon" href="<?php echo base_url('../index.php') ?>">-->
            <!--    <i class="ni ni-planet"></i>-->
            <!--    <span class="nav-link-inner--text">Home</span>-->
            <!--  </a>-->
            <!--</li>-->
            <li class="nav-item">
              <a class="nav-link nav-link-icon" href="<?php echo site_url('site/admin') ?>">
                <i class="ni ni-key-25"></i>
                <span class="nav-link-inner--text">Admin</span>
              </a>
            </li>
            <li class="nav-item">
              <a class="nav-link nav-link-icon" href="<?php echo site_url('site/staff') ?>">
                <i class="ni ni-key-25"></i>
                <span class="nav-link-inner--text">Staff</span>
              </a>
            </li>
            <!-- <li class="nav-item">
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
            <li class="nav-item">
              <a class="nav-link nav-link-icon" href="<?php echo site_url('site/franchisee') ?>">
                <i class="ni ni-single-02"></i>
                <span class="nav-link-inner--text">Franchisee</span>
              </a>
            </li> -->
            <!--<li class="nav-item">-->
            <!--  <a class="nav-link nav-link-icon" href="<?php echo site_url('homeshop/new-purchase') ?>">-->
            <!--    <i class="ni ni-single-02"></i>-->
            <!--    <span class="nav-link-inner--text">Shop</span>-->
            <!--  </a>-->
            <!--</li>-->
          </ul>
        </div>
      </div>
    </nav>



     <?php echo form_open() ?>
    <div class="header bg-gradient-primary py-7 py-lg-8" style="background: linear-gradient(87deg, #d54d7a 0, #ff064c 100%) !important; ">
      <div class="container">
        <div class="header-body text-center mb-4">
          <div class="row justify-content-center">
            <div class="col-lg-5 col-md-6">
              <h1 class="text-white">Staff Login</h1>
               <?php echo validation_errors('<div class="alert alert-danger">', '</div>') ?>
                <?php echo $this->session->flashdata('admin_flash') ?>
            </div>
          </div>
        </div>
      </div>
      <div class="separator separator-bottom separator-skew zindex-100">
        <svg x="0" y="0" viewBox="0 0 2560 100" preserveAspectRatio="none" version="1.1" xmlns="http://www.w3.org/2000/svg">
          <polygon class="fill-default" points="2560 0 2560 100 0 100"></polygon>
        </svg>
      </div>
    </div>
  <div class="container mt--8 pb-5">

      <div class="row justify-content-center">
        <div class="col-lg-5 col-md-7">
          <?php echo validation_errors('<div class="alert alert-danger">', '</div>') ?>
          <?php echo $this->session->flashdata('site_flash') ?>
          <?php if (config_item('is_demo') != TRUE) {
               echo '<div class="alert alert-danger">Please Pay your remaining balance to remove this banner !<br/> इस बैनर को हटाने के लिए कृपयाअपनी शेष राशि का भुगतान करें !</div>';
          } ?>
          <div class="card bg-secondary shadow border-0">
           <!--  <div class="card-header bg-transparent pb-5">
              <div class="text-muted text-center mt-2 mb-3"><small>Sign in with</small></div>
              <div class="btn-wrapper text-center">
                <a href="#" class="btn btn-neutral btn-icon">
                  <span class="btn-inner--icon"><img src="../assets/img/icons/common/github.svg"></span>
                  <span class="btn-inner--text">Github</span>
                </a>
                <a href="#" class="btn btn-neutral btn-icon">
                  <span class="btn-inner--icon"><img src="../assets/img/icons/common/google.svg"></span>
                  <span class="btn-inner--text">Google</span>
                </a>
              </div>
            </div> -->
            <div class="card-body px-lg-4 py-lg-5">
              <div class="text-center text-muted mb-4">
                <small>Sign in with credentials</small>
              </div>
            
                <div class="formbox">
                  <?php echo form_open('site/admin', array('class' => 'form-horizontal')) ?>
                    <div class="form-group">
                        <!-- <label for="user" class="col-sm-6 col-6 control-label">Username</label> -->
                        <div class="col-sm-12">
                            <input type="text" class="form-control form-control-alternative" id="username" name="username" placeholder="Username" required="">
                        </div>
                    </div>
                    <div class="form-group">
                        <!-- <label for="pass" class="col-sm-12  col-6 control-label">Password</label> -->
                        <div class="col-sm-12">
                            <input type="password" class="form-control form-control-alternative" id="password" name="password" placeholder="Password"
                                   required="">
                        </div>
                    </div>
                    <div class="form-group last">
                        <div class="col-sm-offset-3 col-sm-9">
                            <button type="submit" class="btn btn-warning btn-sm">Login</button>
                            <button type="reset" class="btn btn-default btn-sm">Reset</button>
                        </div>
                    </div>
                    <?php echo form_close() ?>

              </div>
                 
            </div>
          </div>
          <div class="row mt-3">
            <div class="col-6">
              <a href="<?php echo site_url('site/forgotpw') ?>" class="text-light"><small>Forgot password?</small></a>
            </div>
            <!-- <div class="col-6 text-right">
              <a href="<?php echo site_url('site/register') ?>" class="text-light"><small>Create new account</small></a>
            </div> -->
          </div>
        </div>
      </div>
   
    </div>
<?php echo form_close() ?>
