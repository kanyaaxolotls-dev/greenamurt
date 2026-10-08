<?php $ak_global = $this->db_model->select_multi('*', 'global_setting', array('id' => 1)); ?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <title>
        Franchisee Dashboard | <?php echo config_item('company_name') ?>
    </title>
    <link href="https://cdn.datatables.net/1.11.3/css/jquery.dataTables.min.css" rel="stylesheet" />
    <link href="https://cdn-icons-png.flaticon.com/128/18809/18809388.png" rel="icon" type="image/png">
    <link href="https://fonts.googleapis.com/css?family=Open+Sans:300,400,600,700" rel="stylesheet">
    <link href="<?php echo base_url('axxets/member/Modern/js/plugins/nucleo/css/nucleo.css') ?>" rel="stylesheet" />
    <link href="<?php echo base_url('axxets/member/Modern/js/plugins/@fortawesome/fontawesome-free/css/all.min.css')?>" rel="stylesheet" />
    <link href="<?php echo base_url('axxets/member/Modern/css/jquery.toast.min.css')?>" rel="stylesheet" type="text/css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
    <link href="<?php echo base_url('axxets/member/Modern/css/argon-dashboard.css?v=1.1.0')?>" rel="stylesheet" />
    <style>
        .bg-gradient-primary, .bg-secondary, .footer {
            <?= $ak_global->fran_theme; ?>
        }
    </style>
</head>
  
<body class=""> 
    <?php 
        $this->db->select('rank,id,name,sponsor');
        $this->db->where('id', config_item('IT_EXT') . $this->session->fran_id);
        $q = $this->db->get('member');
        $data1 = $q->result_array(); 
    ?>
    <nav class="navbar navbar-vertical fixed-left navbar-expand-md navbar-light bg-white" id="sidenav-main" style="border-right: 1px solid grey">
        <div class="container-fluid">
            <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#sidenav-collapse-main" aria-controls="sidenav-main" aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>
            <a class="navbar-brand pt-0" href="<?php echo base_url('franchisee/index') ?>">
                <img src="<?php echo base_url('uploads/logo.png'); ?>" class="" style="width: 100%" alt="...">
            </a>
            <div class="collapse navbar-collapse bg-gradient-primary" id="sidenav-collapse-main">
                <div class="navbar-collapse-header d-md-none">
                    <div class="row">
                        <div class="col-6 collapse-brand">
                            <a href="<?php echo base_url('franchisee/index') ?>">
                                <img src="<?php echo base_url('uploads/logo.png') ?>">
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
                <ul class="navbar-nav">
                    <li class="nav-item active">
                        <a class="nav-link" href="<?php echo site_url('franchisee') ?>">
                            <i class="ni ni-tv-2 text-primary"></i> Dashboard
                        </a>
                    </li>
                    <li class="nav-item active">
                        <a class="nav-link" href="<?php echo site_url('franchisee/profile') ?>">
                            <i class="ni ni-tv-2 text-primary"></i> Profile
                        </a>
                    </li>
                    <?php if (config_item('enable_repurchase') == "Yes") { ?>
                        <li class="nav-item"> 
                            <a class="nav-link " data-toggle="collapse" href="#shop" aria-expanded="false">
                            <i class="ni ni-tv-2 text-primary"></i> Shop<b class="caret"></b></a>
                            <div class="navbar-nav collapse" id="shop" aria-expanded="false" >
                                <ul class="nav">
                                    <li class="nav-item">
                                        <a class="nav-link " href="<?php echo site_url('franchisee/new-purchase') ?>">
                                            <i class="ni ni-planet text-white"></i> New Purchase
                                        </a>
                                    </li>
                                    <li class="nav-item">
                                        <a class="nav-link " href="<?php echo site_url('franchisee/pre_checkout') ?>">
                                        <i class="ni ni-planet text-white"></i>My Cart
                                        </a>
                                    </li>
                                </ul>
                            </div>
                        </li>
                        <!--<li class="nav-item"> -->
                        <!--    <a class="nav-link " data-toggle="collapse" href="#pur_req" aria-expanded="false">-->
                        <!--    <i class="ni ni-tv-2 text-primary"></i>Purchase Requests<b class="caret"></b></a>-->
                        <!--    <div class="navbar-nav collapse" id="pur_req" aria-expanded="false" >-->
                        <!--        <ul class="nav">-->
                        <!--            <li class="nav-item">-->
                        <!--                <a class="nav-link " href="<?php echo site_url('franchisee/purchase_request') ?>">-->
                        <!--                    <i class="ni ni-planet text-white"></i> Requests-->
                        <!--                </a>-->
                        <!--            </li>-->
                        <!--        </ul>-->
                        <!--    </div>-->
                        <!--</li>-->
                        <li class="nav-item"> 
                            <a class="nav-link " data-toggle="collapse" href="#pur_req1" aria-expanded="false">
                            <i class="ni ni-tv-2 text-primary"></i>Wallet<b class="caret"></b></a>
                            <div class="navbar-nav collapse" id="pur_req1" aria-expanded="false" >
                                <ul class="nav">
                                    <li class="nav-item">
                                        <a class="nav-link " href="<?php echo site_url('franchisee/payout') ?>">
                                            <i class="ni ni-planet text-white"></i> Payout
                                        </a>
                                    </li>
                                    <li class="nav-item">
                                        <a class="nav-link " href="<?php echo site_url('franchisee/commision_histry') ?>">
                                            <i class="ni ni-planet text-white"></i> Commision
                                        </a>
                                    </li>
                                    <li class="nav-item">
                                        <a class="nav-link " href="<?php echo site_url('franchisee/wallet_transaction') ?>">
                                            <i class="ni ni-planet text-white"></i> Wallet Transactions
                                        </a>
                                    </li>
                                </ul>
                            </div>
                        </li>
                    <?php } ?>

                    <li class="nav-item">
                        <a class="nav-link collapsed" data-toggle="collapse" href="#productSales1" aria-expanded="false">
                            <i class="ni ni-tv-2 text-primary"></i> Product Managment <b class="caret"></b>
                        </a>
                        <div class="collapse" id="productSales1" aria-expanded="false">
                            <ul class="nav">
                                <li class="nav-item">
                                    <a class="nav-link" href="<?php echo site_url('franchisee/old_purchases') ?>">
                                        <i class="ni ni-planet text-blue"></i>Old Purchases
                                    </a>
                                </li>
                                <li class="nav-item">
                                    <a class="nav-link" href="<?php echo site_url('franchisee/my_stocks') ?>">
                                        <i class="ni ni-planet text-blue"></i>Product stock
                                    </a>
                                </li>
                            </ul>
                        </div>
                    </li>

                    <li class="nav-item">
                        <a class="nav-link collapsed" data-toggle="collapse" href="#productSales" aria-expanded="false">
                            <i class="ni ni-tv-2 text-primary"></i> Order Managment <b class="caret"></b>
                        </a>
                        <div class="collapse" id="productSales" aria-expanded="false">
                            <ul class="nav">
                                <!--
                                <li class="nav-item">
                                    <a class="nav-link" href="<?php echo site_url('franchisee/orders/Processing') ?>">
                                        <i class="ni ni-planet text-blue"></i>Pending Orders
                                    </a>
                                </li>
                                <li class="nav-item">
                                    <a class="nav-link" href="<?php echo site_url('franchisee/orders/Completed') ?>">
                                        <i class="ni ni-planet text-blue"></i>Completed Orders
                                    </a>
                                </li>
                                <li class="nav-item">
                                    <a class="nav-link" href="<?php echo site_url('franchisee/orders/') ?>">
                                        <i class="ni ni-planet text-blue"></i>All Orders
                                    </a>
                                </li>-->
                                <li class="nav-item">
                                    <a class="nav-link" href="<?php echo site_url('franchisee/check_order/') ?>">
                                        <i class="ni ni-planet text-blue"></i>Check Order
                                    </a>
                                </li>
                            </ul>
                        </div>
                    </li>

                    <li class="nav-item">
                        <a class="nav-link collapsed" data-toggle="collapse" href="#productSales2" aria-expanded="false">
                            <i class="ni ni-tv-2 text-primary"></i> Product Sales <b class="caret"></b>
                        </a>
                        <div class="collapse" id="productSales2" aria-expanded="false">
                            <ul class="nav">
                                <li class="nav-item">
                                    <a class="nav-link" href="<?php echo site_url('franchisee/sale-product') ?>">
                                        <i class="ni ni-planet text-blue"></i> New Sale
                                    </a>
                                </li>
                                <li class="nav-item">
                                    <a class="nav-link" href="<?php echo site_url('franchisee/sale-history') ?>">
                                        <i class="ni ni-planet text-blue"></i> Sales History
                                    </a>
                                </li>
                            </ul>
                        </div>
                    </li>

                    <li class="nav-item">
                        <a class="nav-link text-danger" href="<?php echo site_url('franchisee/logout') ?>">
                            <i class="ni ni-tv-2 text-danger"></i> Logout
                        </a>
                    </li>
                </ul>
                <hr class="my-3">
                <h6 class="navbar-heading text-white">Help & Support</h6>
                <ul class="navbar-nav mb-md-3">
                    <li class="nav-item">
                        <a class="nav-link" href="#">
                            <i class="ni ni-spaceship"></i> Learn more
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="#">
                            <i class="ni ni-palette"></i> Chat
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="#">
                            <i class="ni ni-ui-04"></i> Helpline
                        </a>
                    </li>
                </ul>
            </div>
        </div>
    </nav>
    <div class="main-content">
        <nav class="navbar navbar-top navbar-expand-md navbar-dark" id="navbar-main">
            <div class="container-fluid">
                <a class="h4 mb-0 text-white d-none d-lg-inline-block" href="#">
                    <?php echo 'USERNAME : '.$this->db_model->select('username','franchisee', array('id' => $this->session->fran_id));?>
                </a>
                <ul class="navbar-nav align-items-center d-none d-md-flex">
                    <li>
                        <a class="h4 mb-0 text-white text-uppercase d-none d-lg-inline-block" title="Your Payout wallet"><i class="fas fa-wallet"></i> Wallet Balance:
                            <?php echo  0 + $this->db_model->select('wallet', 'franchisee', array('id' => $this->session->fran_id)) ?>
                        </a>
                    </li>
                    <li class="nav-item dropdown">
                        <a class="nav-link pr-0" href="#" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                            <div class="media align-items-center">
                                <span class="avatar avatar-sm rounded-circle" style="position: relative; display: inline-block;">
                                    <?php 
                                    $prof_pic = $this->db_model->select('prof_pic', 'member_profile', array('id' => $this->session->fran_id));
                                    ?>
                                    <img alt="Photo" src="https://cdn-icons-png.flaticon.com/128/4140/4140037.png" style="position: absolute; top: 0; left: 0; width: 100%; height: 100%;">
                                </span>
                                <div class="media-body ml-2 d-none d-lg-block">
                                    <span class="mb-0 text-sm  font-weight-bold"><?php echo $this->session->name ?></span>
                                </div>
                            </div>
                        </a>
                        <div class="dropdown-menu dropdown-menu-arrow dropdown-menu-right">
                            <div class=" dropdown-header noti-title">
                                <h6 class="text-overflow h5 m-0">Welcome !  <?php echo $this->db_model->select('username','franchisee', array('id' => $this->session->fran_id));?></h6>
                            </div>
                            <div class="dropdown-divider"></div>
                            <a href="<?php echo site_url('franchisee/profile') ?>" class="dropdown-item">
                                <i class="ni ni-single-02"></i>
                                <span>My profile</span>
                            </a>
                            <div class="dropdown-divider"></div>
                            <a href="<?php echo site_url('franchisee/logout') ?>" class="dropdown-item">
                                <i class="ni ni-user-run"></i>
                                <span>Logout</span>
                            </a>
                        </div> 
                    </li>
                </ul>
            </div> 
        </nav> 
        <div class="header bg-gradient-primary pb-8 pt-5 pt-md-8">
            <div class="container-fluid">
                <?php
                    echo validation_errors('<div class="alert alert-danger">', '</div>');
                    echo $this->session->flashdata('common_flash');
                    if (trim($layout) !== "") {
                        include_once $layout;
                    } else {
                ?>
                <div class="header-body"> 
                    <div class="row">
                        <div class="col-xl-3 col-lg-6 col-xs-12 col">
                            <div class="card card-stats mb-4 mb-xl-0">
                                <div class="card-body">
                                    <div class="row">
                                        <div class="col">
                                            <h5 class="card-title text-uppercase text-muted mb-0">Total Sale</h5>
                                            <span class="h2 font-weight-bold mb-0">
                                                ₹ <?php echo $this->db_model->sum('cost','product_sale', array('franchisee_id' => $this->session->fran_id)) + 0; ?></span>
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
                        
                        <div class="col-xl-3 col-lg-6 col-xs-12 col">
                            <div class="card card-stats mb-4 mb-xl-0">
                                <div class="card-body">
                                    <div class="row">
                                        <div class="col">
                                            <h5 class="card-title text-uppercase text-muted mb-0">Available Stock</h5>
                                            <span class="h2 font-weight-bold mb-0">₹  <?php  echo $total_stock;  ?></span>
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
                        
                        <div class="col-xl-3 col-lg-6 col-xs-12 col">
                            <div class="card card-stats mb-4 mb-xl-0">
                                <div class="card-body">
                                    <div class="row">
                                        <div class="col">
                                            <h5 class="card-title text-uppercase text-muted mb-0">Sold Stock</h5>
                                            <span class="h2 font-weight-bold mb-0">₹ <?php echo $sold_stock; ?></span>
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
                        
                        <div class="col-xl-3 col-lg-6 col-xs-12 col">
                            <div class="card card-stats mb-4 mb-xl-0">
                                <div class="card-body">
                                    <div class="row">
                                        <div class="col">
                                            <h5 class="card-title text-uppercase text-muted mb-0">Total Orders</h5>
                                            <span class="h2 font-weight-bold mb-0">
                                            <?= $this->db->select('COUNT(*) as total_sales')->from('product_sale')->where('franchisee_id', $this->session->fran_id)->group_by('orderid')->get()->num_rows(); ?>
                                            </span>
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
                                    </p>
                                </div>
                            </div>
                        </div>
                        
                        <div class="col-xl-3 col-lg-6 col-xs-12 col mt-3">
                            <div class="card card-stats mb-4 mb-xl-0">
                                <div class="card-body">
                                    <div class="row">
                                        <div class="col">
                                            <h5 class="card-title text-uppercase text-muted mb-0">Pending Orders</h5>
                                            <span class="h2 font-weight-bold mb-0">
                                                <?= $this->db->select('COUNT(*) as total_sales')->from('product_sale')->where('status', 'Processing')->where('franchisee_id', $this->session->fran_id)->group_by('orderid')->get()->num_rows(); ?>
                                            </span>
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
                                    </p>
                                </div>
                            </div>
                        </div>
                        
                        <div class="col-xl-3 col-lg-6 col-xs-12 col mt-3">
                            <div class="card card-stats mb-4 mb-xl-0">
                                <div class="card-body">
                                    <div class="row">
                                        <div class="col">
                                            <h5 class="card-title text-uppercase text-muted mb-0">Delivered Orders</h5>
                                            <span class="h2 font-weight-bold mb-0">
                                                <?= $this->db->select('COUNT(*) as total_sales')->from('product_sale')->where('status', 'Completed')->where('franchisee_id', $this->session->fran_id)->group_by('orderid')->get()->num_rows(); ?>
                                            </span>
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
                                    </p>
                                </div>
                            </div>
                        </div>
                        
                        <div class="col-xl-3 col-lg-6 col-xs-12 col mt-3">
                            <div class="card card-stats mb-4 mb-xl-0">
                                <div class="card-body">
                                    <div class="row">
                                        <div class="col">
                                            <h5 class="card-title text-uppercase text-muted mb-0">Todays Sale</h5>
                                            <span class="h2 font-weight-bold mb-0">
                                            ₹ <?php echo $this->db_model->sum('cost','product_sale', array('franchisee_id' => $this->session->fran_id,'date' => date('Y-m-d'))) + 0; ?></span>
                                            </span>
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
                                    </p>
                                </div>
                            </div>
                        </div>
                        
                        <div class="col-xl-3 col-lg-6 col-xs-12 col mt-3">
                            <div class="card card-stats mb-4 mb-xl-0">
                                <div class="card-body">
                                    <div class="row">
                                        <div class="col">
                                            <h5 class="card-title text-uppercase text-muted mb-0">My Comission</h5>
                                            <span class="h2 font-weight-bold mb-0"> 
                                            ₹ <?php echo $this->db_model->sum('amount','franchisee_earning', array('userid' => $this->session->fran_id)) + 0; ?></span>
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
                                        <span class="text-nowrap">Since last month</span>
                                    </p>
                                </div>
                            </div>
                        </div>
                    </div>
                    <!---
                    <div class="row mt-4">
                        <div class="col-12">
                            <div class="card bg-white text-dark">
                                <div class="card-header bg-light">
                                    Latest Orders
                                </div>
                                <div class="card-body table-responsive">
                                    <table class="table">
                                        <thead>
                                            <tr>
                                                <th>Id</th>
                                                <th>Userid</th>
                                                <th>Order No</th>
                                                <th>Total Amount</th>
                                                <th>Date</th>
                                                <th>Action</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <?php
                                                $sn = 0;
                                                foreach ($orders as $e) {
                                                    $sn++;
                                            ?>
                                            <tr>
                                                <td><?= $sn ?></td>
                                                <td><?= $e['userid'] ?></td>
                                                <td>#<?= $e['orderid'] ?></td>
                                                <td><?= '₹ '.$this->db_model->sum('cost', 'product_sale', array('orderid' => $e['orderid'])) ?></td>
                                                <td><?= $e['date'] ?></td>
                                                <td><a href="<?php echo site_url('franchisee/order_view/'.$e['orderid']); ?>" class="btn btn-info btn-sm">View Order</a></td>
                                            </tr>
                                            <?php } ?>
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>-->
                    
                </div>
            </div>
        </div>
        <?php } ?>
        <footer class="footer fixed-bottom">
            <div class="copyright text-center text-xl-left text-muted">
                <a class="p-2">&copy; <?= date('Y') ?> <?php echo config_item('company_name') ?></a>
            </div>
        </footer>
    </div>
    <script src="<?php echo base_url('axxets/member/Modern/js/plugins/jquery/dist/jquery.min.js')?>"></script>
    <script src="<?php echo base_url('axxets/member/Modern/js/plugins/bootstrap/dist/js/bootstrap.bundle.min.js')?>"></script>
    <script src="<?php echo base_url('axxets/member/Modern/js/plugins/chart.js/dist/Chart.min.js')?>"></script>
    <script src="<?php echo base_url('axxets/member/Modern/js/plugins/chart.js/dist/Chart.extension.js')?>"></script>
    <script src="<?php echo base_url('axxets/member/Modern/js/plugins/bootstrap-datepicker/dist/js/bootstrap-datepicker.min.js')?>"></script>
    <script src="http://code.jquery.com/jquery-1.11.1.min.js"></script>
    <script src="<?php echo base_url('axxets/member/Modern/js/plugins/toaster/jquery.toast.js')?>"></script>
    <script src="<?php echo base_url('axxets/member/Modern/js/argon-dashboard.min.js?v=1.1.0')?>"></script>
    <script src="https://cdn.trackjs.com/agent/v3/latest/t.js"></script>
    <script src="<?php echo base_url('axxets/member/Modern/js/generation_plan.js')?>"></script>
    <script type="text/javascript">
        function myFunction() {
            var x = document.getElementById("snackbar");
            x.className = "show";
            setTimeout(function(){ x.className = x.className.replace("show", ""); }, 3000);
        } 
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
                pageLength: 20  
            });
        });
    </script> 
</body>
</html>