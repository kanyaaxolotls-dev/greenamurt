<?php
if ($this->login->check_session() == FALSE) {
    header('HTTP/1.0 404 Not Found', TRUE, 404);
    exit('Page Not Found !');
}
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

  <link href=" <?php echo base_url('axxets/assets/img/brand/favicon.png') ?>" rel="icon" type="image/png">
  <!-- Fonts -->
  <link href="https://fonts.googleapis.com/css?family=Open+Sans:300,400,600,700" rel="stylesheet">
  <!-- Icons -->

  <link href="<?php echo base_url('axxets/assets/js/plugins/nucleo/css/nucleo.css') ?>" rel="stylesheet" />
  <link href="<?php echo base_url('axxets/assets/js/plugins/@fortawesome/fontawesome-free/css/all.min.css')?>" rel="stylesheet" />
  <!-- CSS Files -->
  <link href="<?php echo base_url('axxets/admin/argon-dashboard.css')?>" rel="stylesheet" />
   <link href="https://cdn.datatables.net/1.11.3/css/jquery.dataTables.min.css" rel="stylesheet" />
  <?php
  if ( isset( $loadCSS ) && ! empty( $loadCSS ) ) {
    $cssArray = explode( ",", $loadCSS );
    foreach ( $cssArray as $css ) {
      echo '<link rel="stylesheet" type="text/css" href="' . base_url( 'axxets/admin/' . $css ) . '" media="all" />' . "\n";
    }
  }
?>
<script>   var BASEURL = "<?php echo site_url()?>"; </script>
 <script src="jquery.animated-bg.js"></script>
  
   <?php
    if (!isset($this->session->designation)) {
        $payout      = $this->db_model->sum('amount', 'earning');
        $paid_payout = $this->db_model->sum('amount', 'withdraw_request', array('status' => 'Paid'));
        if ($paid_payout == "") {
            $paid_payout = 0;
        }
        $pending_payout = $this->db_model->sum('amount', 'withdraw_request', array('status' => 'Pending'));
        if ($pending_payout == "") {
            $pending_payout = 0;
        }
        $earnnings = $this->db_model->sum('topup', 'member');
    }

      $start = date('Y-m-01 00:00:00');
      $end   = date('Y-m-t 23:59:59');
      
    ?>
    
</head>

<body class="">
  <nav class="navbar navbar-vertical fixed-left navbar-expand-md navbar-light" id="sidenav-main" style="background-color: #fff!important">
    <div class="container-fluid">
      <!-- Toggler -->
      <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#sidenav-collapse-main" aria-controls="sidenav-main" aria-expanded="false" aria-label="Toggle navigation">
        <span class="navbar-toggler-icon"></span>
      </button>
      <!-- Brand -->
      <a class="navbar-brand pt-0" href="<?php echo  config_item('siteurl') ?>">
        <img src="<?php echo base_url('uploads/logo.png') ?>" class="navbar-brand-img" alt="...">

      </a>
      <!-- User -->
      <ul class="nav align-items-center d-md-none">
        <li class="nav-item dropdown">
          <a class="nav-link nav-link-icon" href="#" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
            <i class="ni ni-bell-55"></i>
          </a>
          <div class="dropdown-menu dropdown-menu-arrow dropdown-menu-right" aria-labelledby="navbar-default_dropdown_1">
            <a class="dropdown-item" href="#">Action</a>
            <a class="dropdown-item" href="#">Another action</a>
            <div class="dropdown-divider"></div>
            <a class="dropdown-item" href="#">Something else here</a>
          </div>
        </li>
        <li class="nav-item dropdown">
          <a class="nav-link" href="#" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
            <div class="media align-items-center">
              <span class="avatar avatar-sm rounded-circle">
                <img alt="Image placeholder" src="<?php echo base_url('axxets/assets/img/theme/team-1-800x800.jpg')?>">
              </span>
            </div>
          </a>
          <div class="dropdown-menu dropdown-menu-arrow dropdown-menu-right">
            <div class=" dropdown-header noti-title">
              <h6 class="text-overflow m-0">Welcome!</h6>
            </div>
            <a href="<?php echo site_url('admin/setting') ?>" class="dropdown-item">
              <i class="ni ni-settings-gear-65"></i>
              <span>Settings</span> 
            </a>
            <a href="#" class="dropdown-item">
              <i class="ni ni-calendar-grid-58"></i>
              <span>Activity</span>
            </a>
            <a href="" class="dropdown-item">
              <i class="ni ni-support-16"></i>
              <span>Support</span>
            </a>
            <div class="dropdown-divider"></div>
            <a href="<?php echo site_url('admin/logout') ?>" class="dropdown-item">
              <i class="ni ni-user-run"></i>
              <span>Logout</span>
            </a>
          </div>
        </li>
      </ul>
      <!-- Collapse -->
      <div class="collapse navbar-collapse" id="sidenav-collapse-main">
        <!-- Collapse header -->
        <div class="navbar-collapse-header d-md-none">
          <div class="row">
            <div class="col-6 collapse-brand">
              <a href="<?php echo  config_item('siteurl') ?>">
                <img src="<?php echo base_url('uploads/logo.png') ?>" class="navbar-brand-img">
              </a>
            </div>
            <div class="col-6 collapse-close">
              <button type="button" class="navbar-toggler" data-toggle="collapse" data-target="#sidenav-collapse-main" aria-controls="sidenav-main" aria-expanded="false" aria-label="Toggle sidenav">
                <span></span>
                <span></span>
              </button>
            </div>
          </div>
        </div>
        <!-- Form -->
        <form class="mt-4 mb-3 d-md-none">
          <div class="input-group input-group-rounded input-group-merge">
            <input type="search" class="form-control form-control-rounded form-control-prepended" placeholder="Search" aria-label="Search">
            <div class="input-group-prepend">
              <div class="input-group-text">
                <span class="fa fa-search"></span>
              </div>
            </div>
          </div>
        </form>
        <!-- Navigation -->
        
        <ul class="navbar-nav">
            
            <?php 
                $role_name   = $this->db_model->select('role_id', 'admin', array('id' => $this->session->admin_id));
                $current_url = uri_string(); $menu = $this->db_model->get_task_menu($role_name);
                $i = 0;
                foreach ($menu as $item):
                    $i++;
                    $parent           = $item['parent'];
                    $children         = $item['children'];
                    $is_parent_active = (trim($current_url, '/') == trim($parent->url, '/'));
                    $is_child_active  = false;
                    foreach ($children as $child) {
                        if (trim($current_url, '/') == trim($child->url, '/')) {
                            $is_child_active = true;
                            break;
                        }
                    }
                    $active = $is_parent_active || $is_child_active;
            ?>
            <?php if (count($children) > 0): ?>
           <li class="nav-item"> 
                <a class="nav-link " data-toggle="collapse" href="#menu<?= $i; ?>" aria-expanded="false" class="collapsed">
                    <i class="<?= $parent->img ?>"></i><span><?= $parent->name ?></span> <b class="caret"></b>
                </a>
                <div class="navbar-nav collapse" id="menu<?= $i; ?>" aria-expanded="false" >
                    <ul class="nav">
                        <?php foreach ($children as $child): 
                            $child_active = (trim($current_url, '/') == trim($child->url, '/'));
                        ?>
                        <li class="nav-item">
                            <a class="nav-link " href="<?= base_url($child->url) ?>">
                                <i class="<?= $child->img ?>"></i><?= $child->name ?>
                            </a>
                        </li>
                        <?php endforeach; ?>
                    </ul>
                </div>
            </li>
            <?php else: ?>
            <li class="nav-item">
                <a class="nav-link " href="<?= base_url($parent->url) ?>">
                    <i class="<?= $parent->img ?>"></i> <span><?= $parent->name ?></span>
                </a>
            </li>
            <?php endif; ?>
            <?php endforeach; ?>
            <!--<li class="nav-item">-->
            <!--    <a class="nav-link" href="<?php echo site_url('admin/quiz_payment_requests') ?>">-->
            <!--        <i class="ni ni-check-bold text-green"></i> -->
            <!--        <span class="nav-link-text">Quiz Approvals</span>-->
            <!--    </a>-->
            <!--</li>-->
            <li class="nav-item">
                <a class="nav-link" data-toggle="collapse" href="#quiz_menu" aria-expanded="false">
                    <i class="ni ni-check-bold text-green"></i> 
                    <span class="nav-link-text">Quiz Management</span>
                </a>
                <div class="collapse" id="quiz_menu">
                    <ul class="nav nav-sm flex-column">
                        <li class="nav-item">
                            <a class="nav-link" href="<?php echo site_url('admin/quiz_payment_requests') ?>">
                                Pending Approvals
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="<?php echo site_url('admin/manual_quiz_approve') ?>">
                                Manual Quiz Access
                            </a>
                        </li>
                    </ul>
                </div>
            </li>
            <li class="nav-item">
                <a class="nav-link" href="<?php echo site_url('admin/manage_popup') ?>">
                    <i class="ni ni-image text-primary"></i> 
                    <span class="nav-link-text">Manage Login Popup</span>
                </a>
            </li>
        </ul>
        
      </div>
    </div>
  </nav>

  <div class="main-content">
             
          <!-- Navbar -->
          <nav class="navbar navbar-top navbar-expand-md navbar-dark" id="navbar-main">
            <div class="container-fluid">
              <!-- Brand -->
              <a class="h4 mb-0 text-white text-uppercase d-none d-lg-inline-block" href="<?php echo site_url('admin/index')?>" style="font-weight:bolder">Dashboard</a>
              <!-- Form -->
            <?php
            $role_id = $this->db_model->select('role_id', 'admin', ['id' => $this->session->userdata('admin_id')]);
            $tasks   = $this->db_model->select('tasks', 'tbl_roles', ['id' => $role_id]);
            $task_ids = explode(',', $tasks);
            ?>
               <?php if (in_array('3', $task_ids)){ ?>
                <form class="navbar-search navbar-search-dark form-inline mr-3 d-none d-md-flex ml-lg-auto" action="<?php echo site_url('users/search') ?>" method="POST">
                  <div class="form-group mb-0">
                    <div class="input-group input-group-alternative">
                      <div class="input-group-prepend">
                        <span class="input-group-text"><i class="fas fa-search"></i></span>
                      </div>
                      <input class="form-control" placeholder="Search" type="text" name="userid" href="javascript:;">
                    </div>
                  </div>
                </form>
              <?php } ?>

        <!-- User -->
        <ul class="navbar-nav align-items-center d-none d-md-flex">
        
        <?php if (in_array('11', $task_ids)): ?>
            <li class="nav-item">
                <a class="nav-link" href="<?php echo site_url('product/pending-orders') ?>">
                    <button type="button" class="btn btn-danger">
                        <span>Orders</span>
                        <span class="badge badge-white">
                            <?php echo $this->db_model->count_all('product_sale', ['status' => 'Processing']); ?>
                        </span>
                    </button>
                </a>
            </li>
        <?php endif; ?>
          <li class="nav-item dropdown">
            <a class="nav-link pr-0" href="#" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
              <div class="media align-items-center">
                <span class="avatar avatar-sm rounded-circle">
                  <img alt="Image placeholder" src="<?php echo base_url('axxets/assets/img/theme/team-4-800x800.jpg')?>">
                </span>
                <div class="media-body ml-2 d-none d-lg-block">
                  <span class="mb-0 text-sm  font-weight-bold"><?php echo $this->session->name ?> </span>
                </div>
              </div>
            </a>
            <div class="dropdown-menu dropdown-menu-arrow dropdown-menu-right">
              <div class=" dropdown-header noti-title">
                <h6 class="text-overflow m-0">Welcome! <span style="color: red"><?php echo date('d/m/y h:i A', $this->session->last_login) ?></span></h6>
              </div>
              <a href="<?php echo site_url('admin/setting') ?>" class="dropdown-item">
                <i class="ni ni-settings-gear-65"></i>
                <span>Settings</span>
              </a>
              <a href="#" class="dropdown-item">
                <i class="ni ni-calendar-grid-58"></i>
                <span>Activity</span>
              </a>
              <a href="#" class="dropdown-item" target="_blank">
                <i class="ni ni-support-16"></i>
                <span>Support</span> 
              </a>
              <a href="<?php echo base_url('uploads/Business Plan.pdf') ?>" class="dropdown-item" target="_blank">
                  <i class="ni ni-support-16"></i>
                  <span>Project Plan Pdf</span> 
              </a>
              <div class="dropdown-divider"></div>
              <a href="<?php echo site_url('admin/logout') ?>" class="dropdown-item">
                <i class="ni ni-user-run"></i>
                <span>Logout</span>
              </a>
            </div>
          </li>
        </ul>
      </div>
    </nav>
    <!-- End Navbar -->
    <!-- Header -->
    
    <div class="header bg-gradient-primary pb-8 pt-5 pt-md-8">
          <?php
                echo validation_errors('<div class="alert alert-danger">', '</div>');
                echo $this->session->flashdata('common_flash');
                if (trim($layout) !== "") {
                    require_once($layout);
                } else if (isset($this->session->designation)) {

                    echo '<h1 class="h4 mb-0 text-white text-uppercase d-none d-lg-inline-block" style="padding-left:4%"> Hi ' . $this->session->name . '</h1>';
                } else { ?>
                    <?php if (config_item('is_demo') == TRUE) {
                       // echo '<div class="alert alert-danger">Please Pay your software development remaining balance to remove this banner !<br/> इस बैनर को हटाने के लिए कृपया अपनी शेष राशि का भुगतान करें !</div>';
              } ?>
      <div class="container-fluid">

        <div class="header-body">

          <!-- Card stats -->
          <div class="row">
            <div class="col-xl-3 col-lg-6">
              <div class="card card-stats mb-4 mb-xl-0">
                <div class="card-body">
                  <div class="row">
                    <div class="col">
                      <h5 class="card-title text-uppercase text-muted mb-0">Today's Sale</h5>
                      <span class="h2 font-weight-bold mb-0">
                        <?php 
                             
                            $today_sale = $this->db_model->sum('cost', 'product_sale', array('epin_amount >' => '0','date' => date('Y-m-d')));
                            echo $today_sale + 0;
                            #print_r($this->db->last_query());die();

                        ?>
                        </span>
                    </div>
                    <div class="col-auto">
                      <div class="icon icon-shape bg-yellow text-white rounded-circle shadow">
                        <i class="fas fa-users"></i>
                      </div>
                    </div>
                  </div>
                  <p class="mt-3 mb-0 text-muted text-sm">
                    <span class="text-success mr-2"><i class="fa fa-arrow-up"></i> 12%</span>
                    <span class="text-nowrap">Since Today</span>
                  </p>
                </div>
              </div>
            </div>
            <!---->
            <div class="col-xl-3 col-lg-6">
              <div class="card card-stats mb-4 mb-xl-0">
                <div class="card-body">
                  <div class="row">
                    <div class="col">
                      <h5 class="card-title text-uppercase text-muted mb-0">Total Sale</h5>
                      <span class="h2 font-weight-bold mb-0">
                        <?php 
                            
                            $sale = $this->db_model->sum('cost', 'product_sale', array('epin_amount >' => '0'));
                             echo $sale + 0; 
                        ?>
                        </span>
                    </div>
                    <div class="col-auto">
                      <div class="icon icon-shape bg-danger text-white rounded-circle shadow">
                        <i class="fas fa-chart-bar"></i>
                      </div>
                    </div>
                  </div>
                  <p class="mt-3 mb-0 text-muted text-sm">
                    <span class="text-success mr-2"><i class="fa fa-arrow-up"></i> 3.48%</span>
                    <span class="text-nowrap">Since last month</span>
                  </p>
                </div>
              </div>
            </div>
            <!---->
            <div class="col-xl-3 col-lg-6">
              <div class="card card-stats mb-4 mb-xl-0">
                <div class="card-body">
                  <div class="row">
                    <div class="col">
                      <h5 class="card-title text-uppercase text-muted mb-0">Today Payout</h5>
                      <span class="h2 font-weight-bold mb-0">
                        <?php
                         #echo $today_payout = $this->db_model->sum('amount', 'withdraw_request', array('date' => date('Y-m-d'))); 
                         echo $today_payout      = $this->db_model->sum('amount', 'earning', array('date' => date('Y-m-d')));
                         ?>
                      </span>
                    </div>
                    <div class="col-auto">
                      <div class="icon icon-shape bg-warning text-white rounded-circle shadow">
                        <i class="fas fa-chart-pie"></i>
                      </div>
                    </div>
                  </div>
                  <p class="mt-3 mb-0 text-muted text-sm">
                    <span class="text-danger mr-2"><i class="fas fa-arrow-down"></i> 3.48%</span>
                    <span class="text-nowrap">Since last week</span>
                  </p>
                </div>
              </div>
            </div>
            <div class="col-xl-3 col-lg-6">
              <div class="card card-stats mb-4 mb-xl-0">
                <div class="card-body">
                  <div class="row">
                    <div class="col">
                      <h5 class="card-title text-uppercase text-muted mb-0">Payout</h5>
                      <span class="h2 font-weight-bold mb-0"><?php if ($payout == "") {$payout = 0;}echo $payout ?></span>
                    </div>
                    <div class="col-auto">
                      <div class="icon icon-shape bg-yellow text-white rounded-circle shadow">
                        <i class="fas fa-users"></i>
                      </div>
                    </div>
                  </div>
                  <p class="mt-3 mb-0 text-muted text-sm">
                    <span class="text-warning mr-2"><i class="fas fa-arrow-down"></i> 1.10%</span>
                    <span class="text-nowrap">Since yesterday</span>
                    <span class="text-nowrap" style="color:black;"><b>( Matching + Sponsor = Daily Cutout )</b></span>
                  </p>
                </div>
              </div>
            </div>
            
            <div class="col-xl-3 col-lg-6 mt-3">
              <div class="card card-stats mb-4 mb-xl-0">
                <div class="card-body">
                  <div class="row">
                    <div class="col">
                      <h5 class="card-title text-uppercase text-muted mb-0">Today's Repurchase Sale</h5>
                      <span class="h2 font-weight-bold mb-0">
                        <?php 
                            echo 0 + $this->db_model->sum('cost', 'product_sale', array('type !=' => 'topup','date' => date('Y-m-d')));
                        ?>
                             </span>
                    </div>
                    <div class="col-auto">
                      <div class="icon icon-shape bg-info text-white rounded-circle shadow">
                        <i class="fas fa-percent"></i>
                      </div>
                    </div>
                  </div>
                  <p class="mt-3 mb-0 text-muted text-sm">
                    <span class="text-success mr-2"><i class="fas fa-arrow-up"></i> 12%</span>
                    <span class="text-nowrap">Since last mc</span>
                  </p>
                </div>
              </div>
            </div>
            
            <div class="col-xl-3 col-lg-6 mt-3">
              <div class="card card-stats mb-4 mb-xl-0">
                <div class="card-body">
                  <div class="row">
                    <div class="col">
                      <h5 class="card-title text-uppercase text-muted mb-0">Monthly Repurchase Payout</h5>
                        <span class="h2 font-weight-bold mb-0">
                            <?php 
                                $where3 = array('type LIKE' => '%Repurchase Income%','date >='  => $start,'date <='  => $end);
                                $monthly_repurchase_payout = $this->db_model->sum('amount','earning',$where3);
                                echo $monthly_repurchase_payout + 0;
                            ?>
                        </span>
                    </div>
                    <div class="col-auto">
                      <div class="icon icon-shape bg-info text-white rounded-circle shadow">
                        <i class="fas fa-percent"></i>
                      </div>
                    </div>
                  </div>
                  <p class="mt-3 mb-0 text-muted text-sm">
                    <span class="text-danger mr-2"><i class="fas fa-arrow-up"></i> 12%</span>
                    <span class="text-nowrap">Since last Weel</span>
                  </p>
                </div>
              </div>
            </div>

            <div class="col-xl-3 col-lg-6 mt-3">
              <div class="card card-stats mb-4 mb-xl-0">
                <div class="card-body">
                  <div class="row">
                    <div class="col">
                      <h5 class="card-title text-uppercase text-muted mb-0">TOTAL REPURCHASE PAYOUT</h5>
                      <span class="h2 font-weight-bold mb-0">
                        <?php 
                            $total_repurchase_inc = $this->db_model->sum('amount', 'earning', array('type LIKE' => '%Repurchase Income%'));
                            echo $total_repurchase_inc + 0; 
                        ?>
                             </span>
                    </div>
                    <div class="col-auto">
                      <div class="icon icon-shape bg-info text-white rounded-circle shadow">
                        <i class="fas fa-percent"></i>
                      </div>
                    </div>
                  </div>
                  <p class="mt-3 mb-0 text-muted text-sm">
                    <span class="text-danger mr-2"><i class="fas fa-arrow-up"></i> 12%</span>
                    <span class="text-nowrap">Since yesterday</span>
                  </p>
                </div>
              </div>
            </div>

            <div class="col-xl-3 col-lg-6 mt-3">
              <div class="card card-stats mb-4 mb-xl-0">
                <div class="card-body">
                  <div class="row">
                    <div class="col">
                      <h5 class="card-title text-uppercase text-muted mb-0">BALANCE REPURCHASE FUND</h5>
                      <span class="h2 font-weight-bold mb-0">
                        <?php 
                              $total_repurchase_inc = $this->db_model->sum('amount', 'earning', array('type' => 'Repurchase Income'));
                              echo $total_repurchase_inc + 0;
                        ?>
                             </span>
                    </div>
                    <div class="col-auto">
                      <div class="icon icon-shape bg-danger text-white rounded-circle shadow">
                        <i class="fas fa-chart-bar"></i>
                      </div>
                    </div>
                  </div>
                  <p class="mt-3 mb-0 text-muted text-sm">
                    <span class="text-success mr-2"><i class="fas fa-arrow-up"></i> 12%</span>
                    <span class="text-nowrap">Since today</span>
                  </p>
                </div>
              </div>
            </div>

            <div class="col-xl-3 col-lg-6 mt-3">
              <div class="card card-stats mb-4 mb-xl-0">
                <div class="card-body">
                  <div class="row">
                    <div class="col">
                      <h5 class="card-title text-uppercase text-muted mb-0"> CUSTOMERS</h5>
                      <span class="h2 font-weight-bold mb-0"><?php echo $this->db_model->count_all('member') ?></span>
                    </div>
                    <div class="col-auto">
                      <div class="icon icon-shape bg-yellow text-white rounded-circle shadow">
                        <i class="fas fa-users"></i>
                      </div>
                    </div>
                  </div>
                  <p class="mt-3 mb-0 text-muted text-sm">
                    <span class="text-danger mr-2"><i class="fas fa-arrow-down"></i> 3.48%</span>
                    <span class="fas fa-users">Since last week</span>
                  </p>
                </div>
              </div>
            </div>

            <div class="col-xl-3 col-lg-6 mt-3">
              <div class="card card-stats mb-4 mb-xl-0">
                <div class="card-body">
                  <div class="row">
                    <div class="col">
                      <h5 class="card-title text-uppercase text-muted mb-0">TOTAL GREEN ID</h5>
                      <span class="h2 font-weight-bold mb-0"><?php echo $this->db_model->count_all('member',array('topup >' => 0)); ?></span>
                    </div>
                    <div class="col-auto">
                      <div class="icon icon-shape bg-danger text-white rounded-circle shadow">
                        <i class="fas fa-chart-bar"></i>
                      </div>
                    </div>
                  </div>
                  <p class="mt-3 mb-0 text-muted text-sm">
                    <span class="text-success mr-2"><i class="fas fa-arrow-down"></i> 3.48%</span>
                    <span class="text-nowrap">Since last month</span>
                  </p>
                </div>
              </div>
            </div>

            <div class="col-xl-3 col-lg-6 mt-3">
              <div class="card card-stats mb-4 mb-xl-0">
                <div class="card-body">
                  <div class="row">
                    <div class="col">
                      <h5 class="card-title text-uppercase text-muted mb-0">MONTHLY ROYALTY PAYMENT</h5>
                      <span class="h2 font-weight-bold mb-0">
                        <?php

                            $where2 = array('type LIKE' => '%royalty%','date >='  => $start,'date <='  => $end);
                            $monthly_royalty_inc = $this->db_model->sum('amount','earning',$where2);
                            echo $monthly_royalty_inc + 0;
                        ?>
                      </span>
                    </div>
                    <div class="col-auto">
                      <div class="icon icon-shape bg-warning text-white rounded-circle shadow">
                        <i class="fas fa-chart-pie"></i>
                      </div>
                    </div>
                  </div>
                  <p class="mt-3 mb-0 text-muted text-sm">
                    <span class="text-danger mr-2"><i class="fas fa-arrow-down"></i> 3.48%</span>
                    <span class="text-nowrap">Since last week</span>
                  </p>
                </div>
              </div>
            </div>

            <div class="col-xl-3 col-lg-6 mt-3">
              <div class="card card-stats mb-4 mb-xl-0">
                <div class="card-body">
                  <div class="row">
                    <div class="col">
                      <h5 class="card-title text-uppercase text-muted mb-0">TOTAL ROYALTY PAYOUT</h5>
                      <span class="h2 font-weight-bold mb-0">
                        <?php 

                          #$where2 = array('type LIKE' => '%royalty%','date >='  => $start,'date <='  => $end);
                          $total_royalty_inc = $this->db_model->sum('amount','earning',array('type LIKE' => '%royalty%'));
                          echo $total_royalty_inc + 0;
                          
                        ?>
                      </span>
                    </div>
                    <div class="col-auto">
                      <div class="icon icon-shape bg-yellow text-white rounded-circle shadow">
                        <i class="fas fa-users"></i>
                      </div>
                    </div>
                  </div>
                  <p class="mt-3 mb-0 text-muted text-sm">
                    <span class="text-danger mr-2"><i class="fas fa-arrow-down"></i> 1.10%</span>
                    <span class="text-nowrap">Since last yesterday</span>
                  </p>
                </div>
              </div>
            </div>

            <div class="col-xl-3 col-lg-6 mt-3">
              <div class="card card-stats mb-4 mb-xl-0">
                <div class="card-body">
                  <div class="row">
                    <div class="col">
                      <h5 class="card-title text-uppercase text-muted mb-0">TOTAL SW FUND</h5>
                      <span class="h2 font-weight-bold mb-0">
                        <?php 
                          $social_welfare_fund = $this->db_model->sum('amount','social_welfare_fund');
                          echo $social_welfare_fund + 0; 
                        ?>
                      </span>
                    </div>
                    <div class="col-auto">
                      <div class="icon icon-shape bg-warning text-white rounded-circle shadow">
                        <i class="fas fa-chart-pie"></i>
                      </div>
                    </div>
                  </div>
                  <p class="mt-3 mb-0 text-muted text-sm">
                    <span class="text-danger mr-2"><i class="fas fa-arrow-down"></i> 3.48%</span>
                    <span class="text-nowrap">Since last week</span>
                  </p>
                </div>
              </div>
            </div>

            <div class="col-xl-3 col-lg-6 mt-3">
              <div class="card card-stats mb-4 mb-xl-0">
                <div class="card-body">
                  <div class="row">
                    <div class="col">
                      <h5 class="card-title text-uppercase text-muted mb-0">TOTAL RETOPUP FUND</h5>
                      <span class="h2 font-weight-bold mb-0">
                          <?php  echo $total_retopup_fund = $this->db_model->sum('reserve_fund', 'member', array('reserve_fund >' => 0)); ?>
                      </span>
                    </div>
                    <div class="col-auto">
                      <div class="icon icon-shape bg-info text-white rounded-circle shadow">
                        <i class="fas fa-percent"></i>
                      </div>
                    </div>
                  </div>
                  <p class="mt-3 mb-0 text-muted text-sm">
                    <span class="text-danger mr-2"><i class="fas fa-arrow-down"></i> 1.10%</span>
                    <span class="text-nowrap">Since last yesterday</span>
                  </p>
                </div>
              </div>
            </div>

            <div class="col-xl-3 col-lg-6 mt-3">
              <div class="card card-stats mb-4 mb-xl-0">
                <div class="card-body">
                  <div class="row">
                    <div class="col">
                      <h5 class="card-title text-uppercase text-muted mb-0">PROFFETIONAL CHARGES</h5>
                      <span class="h2 font-weight-bold mb-0">0</span>
                    </div>
                     <div class="col">
                      <h5 class="card-title text-uppercase text-muted mb-0">TDS COLLECTED</h5>
                      <span class="h2 font-weight-bold mb-0">0</span>
                    </div>
                     <div class="col">
                      <h5 class="card-title text-uppercase text-muted mb-0">GST</h5>
                      <span class="h2 font-weight-bold mb-0">0</span>
                    </div>
                    <!--<div class="col-auto">
                      <div class="icon icon-shape bg-warning text-white rounded-circle shadow">
                        <i class="fas fa-chart-pie"></i>
                      </div>
                    </div>-->
                  </div>
                    <p class="mt-3 mb-0 text-muted text-sm">
                      
                    </p>
                </div>
              </div>
            </div>
            
            <a class="col-xl-3 col-lg-6 mt-3" href="<?= base_url('Wallet/support') ?>"> 
              <div class="card card-stats mb-4 mb-xl-0">
                <div class="card-body">
                  <div class="row">
                    <div class="col">
                      <h5 class="card-title text-uppercase text-muted mb-0">Deposit Requests</h5>
                      <span class="h2 font-weight-bold mb-0">
                        <?php 
                             echo $this->db_model->count_all('deposite',array('status' => 'pending'));
                        ?>
                             </span>
                    </div>
                    <div class="col-auto">
                      <div class="icon icon-shape bg-yellow text-white rounded-circle shadow">
                        <i class="fas fa-users"></i>
                      </div>
                    </div>
                  </div>
                  <p class="mt-3 mb-0 text-muted text-sm">
                    <span class="text-danger mr-2"><i class="fas fa-arrow-up"></i>1.10%</span>
                    <span class="text-nowrap">Since yesterday</span>
                  </p>
                </div>
              </div>
            </div>
            
          </a>
        </div>
      </div>
    </div>
    <div class="container-fluid mt--7">

      <div class="row">
        <div class="col-xl-8 mb-5 mb-xl-0">
          <div class="card bg-gradient-default shadow">
            <div class="card-header bg-transparent">
              <div class="row align-items-center">
                <div class="col">
                  <h6 class="text-uppercase text-light ls-1 mb-1">Overview</h6>
                  <h2 class="text-white mb-0">Sales value</h2>
                </div>
                <div class="col">
                  <ul class="nav nav-pills justify-content-end">
                    <li class="nav-item mr-2 mr-md-0" data-toggle="chart" data-target="#chart-sales" data-update='{"data":{"datasets":[{"data":[0, 20, 10, 30, 15, 40, 20, 60, 60]}]}}' data-prefix="$" data-suffix="k">
                      <a href="#" class="nav-link py-2 px-3 active" data-toggle="tab">
                        <span class="d-none d-md-block">Month</span>
                        <span class="d-md-none">M</span>
                      </a>
                    </li>
                    <li class="nav-item" data-toggle="chart" data-target="#chart-sales" data-update='{"data":{"datasets":[{"data":[0, 20, 5, 25, 10, 30, 15, 40, 40]}]}}' data-prefix="$" data-suffix="k">
                      <a href="#" class="nav-link py-2 px-3" data-toggle="tab">
                        <span class="d-none d-md-block">Week</span>
                        <span class="d-md-none">W</span>
                      </a>
                    </li>
                  </ul>
                </div>
              </div>
            </div>
            <div class="card-body">
              <!-- Chart -->
              <div class="chart">
                <!-- Chart wrapper -->
                <canvas id="chart-sales" class="chart-canvas"></canvas>
              </div>
            </div>
          </div>
        </div>
        <div class="col-xl-4">
          <div class="card shadow">
            <div class="card-header bg-transparent">
              <div class="row align-items-center">
                <div class="col">
                  <h6 class="text-uppercase text-muted ls-1 mb-1">Performance</h6>
                  <h2 class="mb-0">Total orders</h2>
                </div>
              </div>
            </div>
            <div class="card-body">
              <!-- Chart -->
              <div class="chart">
                <canvas id="chart-orders" class="chart-canvas"></canvas>
              </div>
            </div>
          </div>
        </div> 
      </div>

      <div class="row mt-5">
        <div class="col-xl-8 mb-5 mb-xl-0"> 
          <div class="card shadow">
            <div class="card-header border-0">
              <div class="row align-items-center">
                <div class="col">
                  <h3 class="mb-0">Latest Customers</h3>
                </div>
                <div class="col text-right">
                  <a href="<?php echo site_url('users/view-members') ?>" class="btn btn-sm btn-primary">See all</a>
                </div>
              </div>
            </div>
            <div class="table-responsive">
              <!-- Projects table -->
              <table class="table align-items-center table-flush">
                <thead class="thead-light">
                  <tr>
                    <th scope="col">Direct Seller Id</th>
                    <!-- <th scope="col">Id.</th> -->
                    <th scope="col">Full Name</th>
                    <th scope="col">Referal Id</th>
                    <th scope="col">Contact No.</th>
                    <th scope="col">Date</th>

                  </tr>
                </thead>
                <tbody>
                  <?php
                        $sn = 1;
                        foreach ($members as $e) { ?>
                           <tr>
                              <th scope="row">
                                <a href="<?php echo site_url('users/user_detail/' . $e['id']) ?>" target="_blank"><?php echo config_item('ID_EXT') . $e['id']; ?></a>
                              </th>
                              <td><?php echo $e['name']; ?></td>
                              <td>
                                <a href="<?php echo site_url('users/user_detail/' . $e['sponsor']) ?>"
                                     target="_blank"><?php echo $e['sponsor'] ? config_item('ID_EXT') . $e['sponsor'] : ''; ?>
                              </td>
                              <td><?php echo $e['phone']; ?></td>
                              <td><?php echo $e['join_time']; ?></td>
                           </tr>
                  <?php } ?>

                </tbody>
              </table>
            </div>
          </div>
        </div>
        <div class="col-xl-4">
          <div class="card shadow">
            <div class="card-header border-0">
              <div class="row align-items-center"> 
                <div class="col">
                  <h3 class="mb-0">Buy Features</h3>
                </div>
                <div class="col text-right">
                  <a href="#!" class="btn btn-sm btn-primary">See all</a>
                </div>
              </div>
            </div>
            <div class="table-responsive">
              <!-- Projects table -->
              <table class="table align-items-center table-flush">
                <thead class="thead-light">
                  <tr>
                     <th scope="col">Id</th>
                    <th scope="col">Name</th>
                    <th scope="col">Rank</th>
                  </tr>
                </thead>
                <tbody>
                 <?php
                   $top_earning = $this->db_model->select_multi('userid', 'earning', array('amount>='=>'0'));
                   foreach ($top_earning as $value) {
                     $user_name = $this->db_model->select_multi('id,name,rank', 'member', array('id '=> $value));
                  ?>
                  <tr>
                    <td>
                      <?php
                       echo $user_name->id;
                      ?>
                    </td>
                      <td>
                      <?php
                       echo $user_name->name;
                      ?>
                    </td>
                     <td>
                      <?php
                       echo $user_name->rank;
                      ?>
                    </td>

                    
                    <?php } ?>
                  </tr>
                </tbody>
              </table>
            </div>
          </div>
        </div>
      <?php } ?><!-- End of main body -->

      </div>

    </div>
      <br>
      <!-- Footer -->
      
    </div>
  </div>

  <!--   Core   -->
  <script src="<?php echo base_url('axxets/assets/js/plugins/jquery/dist/jquery.min.js')?>"></script>
    <?php
if ( isset( $loadJS ) && ! empty( $loadJS ) ) {
  $jsArray = explode( ",", $loadJS );
  foreach ( $jsArray as $js ) {
    echo '<script defer src="' . base_url( 'axxets/assets/js/' . $js ) . '"></script>' . "\n";
  }
}
?>
 <script src="<?php echo base_url('axxets/assets/js/plugins/bootstrap-datepicker/dist/js/bootstrap-datepicker.min.js')?>"></script>
  <script src="<?php echo base_url('axxets/assets/js/plugins/bootstrap/dist/js/bootstrap.bundle.min.js')?>"></script>
    
  <!--   Optional JS   -->
  <script src="<?php echo base_url('axxets/assets/js/plugins/chart.js/dist/Chart.min.js')?>"></script>
  <script src="<?php echo base_url('axxets/assets/js/plugins/chart.js/dist/Chart.extension.js')?>"></script>
  <!--   Argon JS   -->
  <script src="<?php echo base_url('axxets/assets/js/argon-dashboard.min.js?v=1.1.0')?>"></script>
  <script src="https://cdn.trackjs.com/agent/v3/latest/t.js"></script>

  <script>
    window.TrackJS &&
      TrackJS.install({
        token: "ee6fab19c5a04ac1a32a645abde4613a",
        application: "argon-dashboard-free"
      });
  </script>
  <script type="text/javascript">
    $(document).ready(function(){
      $('.animated-bg').animatedbg({
        // options here
      });
    });

  </script>
  
    
  <script src="https://unpkg.com/bootstrap-table/dist/bootstrap-table.min.js"></script>

<script src="https://cdn.datatables.net/1.10.23/js/jquery.dataTables.min.js"></script>
<script src="https://cdn.datatables.net/buttons/1.6.5/js/dataTables.buttons.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jszip/3.1.3/jszip.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.53/pdfmake.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.53/vfs_fonts.js"></script>
<script src="https://cdn.datatables.net/buttons/1.6.5/js/buttons.html5.min.js"></script>
<script src="https://cdn.datatables.net/buttons/1.6.5/js/buttons.print.min.js"></script>

<script>
  $(document).ready(function() {
    $('#example').DataTable({
      dom: 'Bfrtip',
      buttons: [
        'excel', 'pdf', 'print'
      ],
      pageLength: 50 // Set the page length to 50 records per page
    });
  });
</script>
  
  <script>
    window.TrackJS &&
      TrackJS.install({
        token: "ee6fab19c5a04ac1a32a645abde4613a",
        application: "argon-dashboard-free"
      });
  </script>
  <script type="text/javascript">
    $(document).ready(function(){
      $('.animated-bg').animatedbg({
        // options here
      });
    });

  </script>
</body> 

</html>