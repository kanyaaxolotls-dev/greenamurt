<?php $ak_global = $this->db_model->select_multi('*', 'global_setting', array('id' => 1)); ?>
<!doctype html>  
<html lang="en">    
<head> 
        <meta charset="utf-8" />
        <title> Member | <?php echo config_item('company_name') ?></title>
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <meta content="Themesbrand" name="author" />
        <!-- App favicon -->
        <link rel="shortcut icon" href="<?php echo base_url('uploads/favicon.ico')?>">
                
        <!-- plugin css -->

        <link href="<?php echo base_url('assets/libs/bootstrap-touchspin/jquery.bootstrap-touchspin.min.css')?>" rel="stylesheet" />

         <!-- DataTables -->
        <link href="<?php echo base_url('assets/libs/datatables.net-bs4/css/dataTables.bootstrap4.min.css')?>" rel="stylesheet" type="text/css" />

        <!-- dropzone css -->
        <link href="<?php echo base_url('assets/libs/dropzone/min/dropzone.min.css')?>" rel="stylesheet" type="text/css" />

        <!-- preloader css -->
        <link rel="stylesheet" href="<?php echo base_url('assets/css/preloader.min.css')?>" type="text/css" />

        <!-- choices css -->
        <link href="<?php echo base_url('assets/libs/choices.js/public/assets/styles/choices.min.css')?>" rel="stylesheet" type="text/css" />

        <!-- datepicker css -->
        <link rel="stylesheet" href="<?php echo base_url('assets/libs/flatpickr/flatpickr.min.css')?>" type="text/css">
          <!-- Sweet Alert-->
        <link href="<?php echo base_url('assets/libs/sweetalert2/sweetalert2.min.css')?>" rel="stylesheet" type="text/css" />

        <!-- Bootstrap Css -->
        <link href="<?php echo base_url('assets/css/bootstrap.min.css')?>" id="bootstrap-style" rel="stylesheet" type="text/css" />
        <!-- Icons Css -->
        <link href="<?php echo base_url('assets/css/icons.min.css')?>" rel="stylesheet" type="text/css" />
        <!-- App Css-->
        <link href="<?php echo base_url('assets/css/app.min.css')?>" id="app-style" rel="stylesheet" type="text/css" />

        <link href="<?php echo base_url('assets/css/custom.css')?>" id="app-style" rel="stylesheet" type="text/css" />
        <!--<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.4/jquery.min.js"></script>-->
        <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
        <script src="https://cdn.datatables.net/1.10.25/js/jquery.dataTables.min.js"></script>
        <!--<link rel="stylesheet" href="https://cdn.datatables.net/1.10.25/css/jquery.dataTables.min.css">-->
        <script src="<?php echo base_url('axxets/member/Modern/js/generation_plan.js')?>"></script>
        <style>
            .card, .footer, .simplebar-content-wrapper, .card-header:first-child{
                <?= $ak_global->card_body; ?>
            }
        </style>
    </head>

    <body data-topbar="dark" >
<?php if (isset($active_popup) && !empty($active_popup->img)): ?>
    <div id="loginPromoOverlay" style="position: fixed !important; top: 0; left: 0; width: 100%; height: 100%; background: rgba(0,0,0,0.85); z-index: 9999999 !important; display: flex; flex-direction: column; justify-content: center; align-items: center;">
        <div id="promoContainer" style="position: relative; max-width: 80%; text-align: center;">
            <button style="position: absolute; top: -20px; right: -20px; background: #ff4757; color: white; border: none; border-radius: 50%; width: 40px; height: 40px; cursor: pointer; font-size: 20px; font-weight: bold; z-index: 100;" onclick="this.parentElement.parentElement.style.display='none'">&times;</button>
            <img src="<?= $active_popup->img; ?>" style="max-width: 100%; max-height: 80vh; border: 5px solid #fff; border-radius: 15px; box-shadow: 0 0 40px rgba(0,0,0,0.5);">
            <div style="margin-top: 15px; color: #fff; font-weight: bold; font-size: 18px;">
                Closing in <span id="promo_count">10</span>s
            </div>
        </div>
    </div>

    <script>
        var promoTime = 10;
        var promoInterval = setInterval(function() {
            promoTime--;
            var el = document.getElementById('promo_count');
            if(el) el.textContent = promoTime;
            if (promoTime <= 0) {
                var overlay = document.getElementById('loginPromoOverlay');
                if(overlay) overlay.style.display = 'none';
                clearInterval(promoInterval);
            }
        }, 1000);
    </script>
<?php endif; ?>
    <!-- <body data-layout="horizontal"> -->
        <!-- Begin page -->
        <div id="layout-wrapper">
            <header id="page-topbar">
                <div class="navbar-header bg-dark">
                    <div class="d-flex">
                        <!-- LOGO -->
                        <div class="navbar-brand-box bg-light d-none d-lg-block">
                            <a href="" class="logo logo-dark">
                                <span class="logo-sm">
                                    <img src="<?php echo base_url('uploads/logo.png') ?>" alt="" height="30" class="img-thumbnail" width="60"> 
                                </span>
                                <span class="logo-lg">
                                    <img src="<?php echo base_url('uploads/logo.png') ?>" alt="" height="50"  class="img-thumbnail" width="60"> <span class="logo-txt"></span>
                                </span>
                            </a> 

                            <a href="" class="logo logo-light d-flex justify-content-center">
                                <span class="logo-sm">
                                    <!--<img src="<?php echo base_url('uploads/logo.png') ?>" alt="" height="30" class="img-thumbnail" width="60">-->
                                </span>
                                <span class="logo-lg">
                                    <img src="<?php echo base_url('uploads/logo.png') ?>" alt="" height="70" width="70"> <span class="logo-txt"></span>
                                </span>
                            </a>
                        </div>
                        <button type="button" class="btn btn-sm px-3 font-size-16 header-item" id="vertical-menu-btn">
                            <i class="fa fa-fw fa-bars"></i>
                        </button>
                    </div>

                    <div class="d-flex">
                        <div class="dropdown  ms-1">
                            <button type="button" class="btn header-item noti-icon position-relative" id="page-header-notifications-dropdown"
                            data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                               <!-- akki 10-11-2025 <i class="fas fa-wallet"> <?php echo $get_balance = $this->db_model->select('balance', 'wallet', array('userid' => $this->session->user_id)); ?></i>-->
                               <i class="fas fa-wallet"> <?php echo $get_balance = $this->db_model->select('balance', 'product_wallet', array('userid' => $this->session->user_id)); ?></i>
                                <span class="badge bg-danger rounded-pill"></span>
                            </button>
                        </div> 
  
                        <div class="dropdown d-inline-block">
                            <button type="button" class="btn header-item bg-soft-light border-start border-end" id="page-header-user-dropdown"data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                <img class="rounded-circle header-profile-user" src="
                                <?php
                                   $picc = $this->db_model->select('prof_pic', 'member_profile', array('userid' => $this->session->user_id)) ;
                                   echo $picc ? base_url('uploads/'.$picc) : base_url('uploads/logo.png'); 
                                ?>
                                " alt="Profile Pic" >
                                <span class="d-none d-xl-inline-block ms-1 fw-medium"><?php echo $this->db_model->select('name', 'member', array('id' => $this->session->user_id)); ?> </span>
                                <i class="mdi mdi-chevron-down d-none d-xl-inline-block"></i>
                            </button>
                            <div class="dropdown-menu dropdown-menu-end">
                                <!-- item-->
                                <a class="dropdown-item" href="<?php echo site_url('member/proile') ?>"><i class="mdi mdi-face-profile font-size-16 align-middle me-1"></i> Profile</a>
                                 <a class="dropdown-item" href="<?php echo site_url('member/settings') ?>"><i class="mdi mdi-lock font-size-16 align-middle me-1"></i>Password</a>  
                                <div class="dropdown-divider"></div>
                                <a class="dropdown-item" href="<?php echo site_url('member/logout') ?>"><i class="mdi mdi-logout font-size-16 align-middle me-1"></i> Logout</a>
                            </div>
                        </div>

                    </div>
                </div> 
            </header> 

            <!-- ========== Left Sidebar Start ========== -->
            <div class="vertical-menu">

                <div data-simplebar class="h-100">

                    <!--- Sidemenu -->
                    <div id="sidebar-menu"> 
                        <ul class="metismenu list-unstyled" id="side-menu">
                            <li class="menu-title" data-key="t-menu">Menu</li>
                            <!--
                            <li>
                                <a href="<?php echo site_url('site/register/A/' . $this->session->user_id) ?>" data-key="t-addnew" target="_blank">
                                    <i class="mdi mdi-account-plus"></i>
                                    <span data-key="t-dashboard">Add new member</span>
                                </a>
                            </li>-->
                            <?php
                            $parents = $this->db->where('is_child', 0)->where('status', 'Active')->order_by('position', 'ASC')->get('tbl_member_option')->result();

                            foreach ($parents as $parent):
                                $children = $this->db->where('is_child', $parent->id)->where('status', 'Active')->order_by('position', 'ASC')->get('tbl_member_option')->result();
                            ?>
                                <li>
                                    <?php if (!empty($children)): ?>
                                        <a href="javascript: void(0);" class="has-arrow">
                                            <i data-feather="<?= $parent->image; ?>"></i>
                                            <span data-key="t-dashboard"><?= $parent->name; ?></span>
                                        </a>
                                        <ul class="sub-menu" aria-expanded="false">
                                            <?php foreach ($children as $child): ?>
                                                <li>
                                                    <a href="<?php echo site_url($child->url); ?>" data-key="t-user-grid">
                                                        <?= $child->name; ?>
                                                    </a>
                                                </li>
                                            <?php endforeach; ?>
                                        </ul>
                                    <?php else: ?>
                                        <a href="<?php echo site_url($parent->url); ?>">
                                            <i data-feather="<?= $parent->image; ?>"></i>
                                            <span data-key="t-dashboard"><?= $parent->name; ?></span>
                                        </a>
                                    <?php endif; ?>
                                </li>
                            <?php endforeach; ?>

                            <!-- You can still keep any static menu items below if needed -->
                            <li>
                                <a href="<?php echo site_url('site/register/A/' . $this->session->user_id) ?>" data-key="t-addnew" target="_blank">
                                    <i class="mdi mdi-account-plus"></i>
                                    <span data-key="t-dashboard">Add new member</span>
                                </a>
                            </li>
                            <li>
                                <a href="<?php echo site_url('member/quiz_center') ?>">
                                    <i class="mdi mdi-school"></i>
                                    <span>Nadi Vigyan Quiz</span>
                                </a>
                            </li>
                            <li>
                                <a href="<?php echo site_url('member/logout') ?>" data-key="t-addnew">
                                    <i class="mdi mdi-logout text-danger"></i>
                                    <span data-key="t-dashboard">Logout</span>
                                </a>
                            </li>
                        </ul>

                    </div>
                </div>
            </div>
            <!-- Left Sidebar End -->
            <!-- ============================================================== -->
            <!-- Start right Content here -->
            <!-- ============================================================== -->
            
              
            <div class="main-content"> 

                <div class="page-content">
                    <div class="container-fluid">           

                <?php
                    $prod_sale = $this->db_model->sum('cost', 'product_sale',array('userid' => $detail->id));
                    if($prod_sale >= $ak_global->active_topup){
                        $msgg = 'Active';
                        $clrr = 'success';
                    }else{
                        $msgg = 'Inactive';
                        $clrr = 'danger';
                    }
                    if($this->uri->segment(2) != 'proile'){
                      echo validation_errors('<div class="alert alert-danger">', '</div>');
                      echo $this->session->flashdata('common_flash');
                    }

                    if (trim($layout) !== "") {
                        //echo "<h3 style='color: #3c3c3c'>" . $title . "</h3><hr/>";

                        include_once $layout;
                    } else{

                        ?>
                        <!-- start page title --> 
                        <div class="row">

                            <div class="col-12">
                                <div class="page-title-box d-sm-flex align-items-center justify-content-between">
                                    <h4 class="mb-sm-0 font-size-18">Welcome! <?php echo $detail->name; ?> <?php echo "| " . $detail->id ?> | <span class='text-<?= $clrr ?>'><?= $msgg ?></span></h4>
                                    </li>
                                    <div class="page-title-right">
                                        <ol class="breadcrumb m-0">
                                            <li class="breadcrumb-item"><a href="javascript: void(0);">Dashboard</a></li>
                                            <li class="breadcrumb-item active">Welcome ! <?php echo $detail->name; ?> <?php echo "| " . $detail->id ?></li>
                                            <li> 
                                        </ol>
                                    </div>

                                </div>
                            </div>
                        </div>
                        <!-- end page title -->

                        <!-- New  Start-->
                        <div class="row">
                            <div class="col-xl-2 col-md-2">
                                <!-- card -->
                                <div class="card card-h-100">
                                    <!-- card body -->
                                    <div class="card-body">
                                        <div class="d-flex align-items-center">
                                            <div class="flex-grow-1">
                                                <span class="mb-3 lh-1 d-block text-dark">Matching Income</span>
                                                <h4 class="mb-3">
                                                    <i class="fas fa-rupee-sign"></i>
                                                    <span>
                                                        <?php 
                                                            
                                                            /*$matching_income = $this->db_model->sum('amount', 'earning', array('userid' => $this->session->user_id,'type'=> 'Matching Income'));
                                                            echo $matching_income ? $matching_income : 0;*/
                                                             $total_pairs    = $this->db_model->select('total_pairs', 'member', ['id' => $this->session->user_id]);
                                                             echo $total_pairs ? $total_pairs : 0;
                                                        ?> 
                                                    </span>
                                                </h4>
                                            </div>
                                            
                                        </div>
                                    </div><!-- end card body -->
                                </div><!-- end card -->
                            </div><!-- end col -->

                            <div class="col-xl-2 col-md-2">
                                <!-- card -->
                                <div class="card card-h-100">
                                    <!-- card body -->
                                    <div class="card-body">
                                        <div class="d-flex align-items-center">
                                            <div class="flex-grow-1">
                                                <span class="mb-3 lh-1 d-block text-dark">Sponsor Income</span>
                                                <h4 class="mb-3">
                                                    <i class="fas fa-rupee-sign"></i>
                                                    <?php 
                                                        $this->db->like('type', 'Sponsor Income'); // LIKE condition
                                                        $this->db->where('userid', $this->session->user_id);
                                                        $this->db->select_sum('amount');
                                                        $query = $this->db->get('earning');

                                                        $sponsor_income = 0;
                                                        if ($query && $query->num_rows() > 0) {
                                                            $result = $query->row();
                                                            $sponsor_income = $result->amount ?? 0;
                                                        }

                                                        echo $sponsor_income;
                                                    ?> 
                                                </h4>
                                            </div>
                                            
                                        </div>
                                    </div><!-- end card body -->
                                </div><!-- end card -->
                            </div><!-- end col -->

                            <div class="col-xl-2 col-md-2">
                                <!-- card -->
                                <div class="card card-h-100">
                                    <!-- card body -->
                                    <div class="card-body">
                                        <div class="d-flex align-items-center">
                                            <div class="flex-grow-1">
                                                <span class="mb-3 lh-1 d-block text-dark">Repurchase Income</span>
                                                <h4 class="mb-3">
                                                    <i class="fas fa-rupee-sign"></i>
                                                    <span>
                                                        <?php 
                                                            $repurchase_income = $this->db_model->sum('amount', 'earning', array('userid' => $this->session->user_id,'type'=> 'Repurchase Income'));
                                                            echo $repurchase_income ? $repurchase_income : 0;
                                                        ?> 
                                                    </span> 
                                                </h4>
                                            </div>
                                            
                                        </div>
                                    </div><!-- end card body -->
                                </div><!-- end card -->
                            </div><!-- end col -->
                            
                            <div class="col-xl-2 col-md-2">
                                <!-- card -->
                                <div class="card card-h-100">
                                    <!-- card body -->
                                    <div class="card-body">
                                        <div class="d-flex align-items-center">
                                            <div class="flex-grow-1">
                                                <span class="mb-3 lh-1 d-block text-dark">Royalty Income</span>
                                                <h4 class="mb-3">
                                                    <i class="fas fa-rupee-sign"></i>
                                                    <span>
                                                        <?php 
                                                            $total_royalty_inc = $this->db_model->sum('amount', 'earning', array('type LIKE' => '%royalty%','userid' => $this->session->user_id)); 
                                                            echo $total_royalty_inc ? $total_royalty_inc : 0;
                                                        ?> 
                                                    </span> 
                                                </h4>
                                            </div>
                                            
                                        </div>
                                    </div><!-- end card body -->
                                </div><!-- end card -->
                            </div><!-- end col -->

                            <div class="col-xl-2 col-md-2">
                                <!-- card -->
                                <div class="card card-h-100">
                                    <!-- card body -->
                                    <div class="card-body">
                                        <div class="d-flex align-items-center">
                                            <div class="flex-grow-1">
                                                <span class="mb-3 lh-1 d-block text-dark">Total Earning</span>
                                                <h4 class="mb-3">
                                                    <i class="fas fa-rupee-sign"></i>
                                                    <span>
                                                        <?php
                                                            //echo $this->db_model->sum('amount', 'earning',array('userid' => $this->session->user_id)); 
                                                            $total_payout = $this->db_model->sum('amount', 'earning', array('userid' => $this->session->user_id));
                                                            $tax = config_item('admin_charges') + config_item('payout_tax');
                                                            $total_pay_amt = ($total_payout > 0) ? $total_payout - ($total_payout * $tax / 100) : 0;
                                                            echo $total_pay_amt;
                                                        ?>
                                                    </span> 
                                                </h4>
                                            </div>
                                            
                                        </div>
                                    </div><!-- end card body -->
                                </div><!-- end card -->
                            </div><!-- end col -->
        
                            <div class="col-xl-2 col-md-2">
                                <!-- card -->
                                <div class="card card-h-100">
                                    <!-- card body -->
                                    <div class="card-body">
                                        <div class="d-flex align-items-center">
                                            <div class="flex-grow-1">
                                                <span class="mb-3 lh-1 d-block text-dark">Award Reward</span>
                                                <h4 class="mb-3">
                                                    <span>
                                                    <?php 
                                                        $current_reward = "-";
                                                        $reward_rank    = $this->db_model->select('reward_rank', 'member', ['id' => $this->session->user_id]);
                                                        if($reward_rank){
                                                            $current_reward = $this->db_model->select('reward_gift', 'reward_setting', ['reward_name' => $reward_rank]);
                                                        }
                                                        echo $current_reward;
                                                        
                                                    ?> 
                                                    </span>
                                                </h4>
                                            </div>
                                            
                                        </div>
                                    </div><!-- end card body -->
                                </div><!-- end card -->
                            </div><!-- end col--> 
                            
                        </div>
                    <!-- New  end-->

                        <div class="row">
                            <div class="col-xl-2 col-md-2">
                                <!-- card -->
                                <div class="card card-h-100">
                                    <!-- card body -->
                                    <div class="card-body">
                                        <div class="d-flex align-items-center">
                                            <div class="flex-grow-1">
                                                <span class="mb-3 lh-1 d-block text-dark">Total Team</span>
                                                <h4 class="mb-3">
                                                    <?php
                                                        $t_member   =  $this->db_model->get_total_count($this->session->user_id,120);
                                                        #echo "<pre>";print_r($this->db->last_query());die();
                                                        $t_active   = $this->db_model->get_active_count($this->session->user_id,120);
                                                        $t_inactive = $t_member - $t_active;
                                                    ?>
                                                    <span class="text-dark"><?= $t_member ?></span> | 
                                                    <span class="text-success"><?= $t_active ?></span> |
                                                    <span style="color:red"><?= $t_inactive ?></span>
                                                    <!--<span class="counter-value" data-target="<?php echo $sum; ?>"></span>-->
                                                <!--    <span class="badge bg-soft-success text-success">+$20.9k</span>-->
                                                <!--    <span class="ms-1 text-muted font-size-13">Since last week</span>-->
                                                <!--</div>-->
                                            </div>
                                            
                                        </div>
                                    </div><!-- end card body -->
                                </div><!-- end card -->
                            </div><!-- end col -->
        
                            

                            <div class="col-xl-2 col-md-2">
                                <!-- card -->
                                <div class="card card-h-100">
                                    <!-- card body -->
                                    <div class="card-body">
                                        <div class="d-flex align-items-center">
                                            <div class="flex-grow-1">
                                                <span class="mb-3 lh-1 d-block text-dark">Total Left Team</span>
                                                <h4 class="mb-3">
                                                    <span><?php echo $detail->total_a; ?> </span>
                                                </h4>
                                            </div>
                                        </div>
                                    </div><!-- end card body -->
                                </div><!-- end card -->
                            </div><!-- end col -->
        
                            <div class="col-xl-2 col-md-2">
                                <!-- card -->
                                <div class="card card-h-100">
                                    <!-- card body -->
                                    <div class="card-body">
                                        <div class="d-flex align-items-center">
                                            <div class="flex-grow-1">
                                                <span class="mb-3 lh-1 d-block text-dark">Total Right Team</span>
                                                <h4 class="mb-3">
                                                    <span><?php echo $detail->total_b; ?> </span>
                                                </h4>
                                            </div>
                                        </div>
                                    </div><!-- end card body -->
                                </div><!-- end card -->
                            </div><!-- end col--> 

                            <div class="col-xl-2 col-md-2">
                                <!-- card -->
                                <div class="card card-h-100">
                                    <!-- card body -->
                                    <div class="card-body">
                                        <div class="d-flex align-items-center">
                                            <div class="flex-grow-1">
                                                <span class="text-muted mb-3 lh-1 d-block text-truncate">Total Direct</span>
                                                <h4 class="mb-3">
                                                                                                                                                                                                                                    
                                                    <span class="counter-value" data-target="   <?php
                                                           $this->db->select('id'); $this->db->from('member');$this->db->where('sponsor',$this->session->user_id);echo $num_results = $this->db->count_all_results(); ?>">
                                                    </span>
                                                </h4>
                                            </div>
                                           
                                        </div>
                                    </div><!-- end card body -->
                                </div><!-- end card -->
                            </div><!-- end col--> 


        
                            <div class="col-xl-2 col-md-2">
                                <!-- card -->
                                <div class="card card-h-100">
                                    <!-- card body -->
                                    <div class="card-body"> 
                                        <div class="d-flex align-items-center">
                                            <div class="flex-grow-1">
                                                <span class="text-muted mb-3 lh-1 d-block text-truncate">ID Topup Fund</span>
                                                <h4 class="mb-3">
                                                    <i class="fas fa-rupee-sign"></i> <span class="counter-value" data-target="<?php echo $reserve_fund = $this->db_model->select('reserve_fund', 'member', array('id' => $this->session->user_id)); ?>"></span> 
                                                </h4>
                                               
                                            </div>
                                            
                                        </div> 
                                    </div><!-- end card body -->
                                </div><!-- end card -->
                            </div><!-- end col -->
                            
                            <div class="col-xl-2 col-md-2">
                                <!-- card -->
                                                <?php
                                                    // $date = new DateTime($detail->activation_date);
                                                    // $date->modify('+28 days');
                                                    // $date_after_28_days = $date->format('d/m/Y');
                                                    // $today = new DateTime();
                                                    // $interval = $today->diff($date);
                                                    // $remaining_days = $interval->invert ? 0 : $interval->days;
                                                    // if($remaining_days <= 0){
                                                    //     $cllr = 'danger';
                                                    //     $txxt = 'Inactive';
                                                    // }else{
                                                    //     $cllr = 'success';
                                                    //     $txxt = 'Active';
                                                    // }
                                                ?>
                                <div class="card card-h-100 ">
                                    <!-- card body -->
                                    <div class="card-body">
                                        <div class="d-flex align-items-center">
                                            <div class="flex-grow-1">
                                                <span class="mb-3 lh-1 d-block text-dark">Social Welfare Fund</span>
                                                <h4 class="mb-3">
                                                <i class="fas fa-rupee-sign"></i> 
                                                <span>
                                                    <?php 
                                                        $social_welfare_fund = $this->db_model->sum('amount', 'social_welfare_fund',array('userid' => $this->session->user_id));
                                                        echo $social_welfare_fund ? $social_welfare_fund : 0;
                                                    ?> 
                                                    </span> 
                                                </h4>
                                            </div>
                                            
                                        </div>
                                    </div><!-- end card body -->
                                </div><!-- end card -->
                            </div><!-- end col -->     
                        </div><!-- end row-->

                    

                        <div class="row">
                        <div class="col-lg-6 col-md-12">
                                 <div class="card">
                                   <div class="card-header align-items-center d-flex">
                                        <h4 class="card-title mb-0 flex-grow-1">Refferal Link</h4>
                                    </div>
                                    <div id="text-to-copy" class='px-4 pt-4 h6 text-center'>
                                        <?php echo site_url('site/register/A/' . $this->session->user_id) ?>
                                    </div>
                                    <div class="card-body px-4">
                                        <a onclick="share()"  class="btn btn-md btn-success w-100 text-light">Share Link</a>
                                        <a class="btn btn-md btn-info mt-3 light-dark w-100" onclick="copyText()">Copy Link</a>
                                    </div>
                                   </div>
                            </div>

                            <!-- end row-->
                            <div class="col-xl-6">
                                <div class="card">
                                    
                                    <div class="card-header align-items-center d-flex">
                                        <h4 class="card-title mb-0 flex-grow-1"><?php $topup = $this->db_model->select('topup', 'member', array('id' =>  $this->session->user_id));if(1 == 0 ){ echo "Activation";}else{ echo "News Details : ";} ?></h4>
                                <!--         <div class="flex-shrink-0">
                                            <select class="form-select form-select-sm mb-0 my-n1" disabled >
                                                <option value="Today" selected="">Today</option>
                                                <option value="Yesterday">Yesterday</option>
                                                <option value="Week">Last Week</option>
                                                <option value="Month">Last Month</option>
                                            </select>
                                        </div> -->
                                    </div><!-- end card header -->

                                    <div class="card-body px-0">
                                         <?php 
                                           if (1 == 0 ){ 
                                         ?>
                                        <div class="px-3 chat-conversation" data-simplebar style="height: auto;">  
                                        
                                        <form action="<?php echo site_url('member/activation') ?>"  method="post" >
                                           
                                        <div class="mb-3">
                                            <label class="form-check-label mb-3" for="formRadios1">Select Package :</label>
                                            <select class="form-control" id="signup_package" name="signup_package" required>
                                                <option value="" disabled selected>Select Package</option>
                                                <?php foreach ($products_data as $pd) { ?>

                                                    <option value="<?php echo $pd['id']; ?>"><?php echo $pd['prod_name']; ?> [ Rs.<?php echo $pd['dealer_price']; ?> ]</option>
                                                <?php } ?>
                                            </select>  
                                        </div>                
                        <input class="form-check-input" type="radio" name="paymethod" id="formRadios1" value="epin" checked >
                        <label class="form-check-label" for="formRadios1" onclick="changeText(this)">E-Pin</label>
                        
                        <input class="form-check-input ml-3" type="radio" name="paymethod"  id="formRadios2" value="wallet">
                        <label class="form-check-label" for="formRadios1">Wallet</label>
                        
                        <div class="d-flex flex-wrap gap-2 mb-3 mt-3">     
                        <input type="text" class="form-control" name= "epin" id="epin" placeholder="Epin / Amount" required="">                                
                        </div>
                                         <div class="mb-3">
                                               <button class="btn btn-primary waves-effect btn-label waves-light" onclick="this.value='Please Wait..'" type="submit"><i class="bx bx-smile label-icon"></i> Proceed</button>

                                          </div>
                                        </form>
                                       
                                        </div>
                                         <div class="card-footer">
                                             <p class="text-danger">Note : If you select payment method as epin enter Epin and for wallet enter amount.</p>
                                         </div>   
                                     <?php } else { ?>
                                   
                                        <div class="card-body">
                                            <?php 
                                              $news = $this->db_model->select_multi('*', 'news');
                                            //   var_dump($news->news_title);
                                            ?>
                                            <!--<strong > News  </strong><hr>-->
                                            <strong > <?php echo $news->news_title ?> : </strong><hr>
                                            <p class="card-text"><?php echo $news->news_details ?></p>
                                        </div> 
                                        </div>
                                       
                                     <?php } ?>
                                    </div>
                             
                                </div>
                                <!-- end card -->
                            </div>
                        </div>
                        <!-- end row--> 

                        <div class="row p-2">
                            
                            <!-- end col -->
                            
<div class="col-xl-6">
    <div class="card">
        <div class="card-header align-items-center d-flex">
            <h4 class="card-title mb-0 flex-grow-1">Rewards</h4>
        </div>

        <div class="card-body px-0">
            <div class="px-3" data-simplebar style="max-height: 386px;">
                <?php 
                // User total pairs
                $user_total_pairs = (int) $this->db_model->select(
                    'total_pairs',
                    'member',
                    array('id' => $this->session->user_id)
                );

                // remaining pairs (this will reduce reward by reward)
                $remaining_pairs = $user_total_pairs;

                // rewards ordered properly (VERY IMPORTANT)
                $this->db->order_by('id', 'ASC'); // or required pairs ASC
                $all_rewards = $this->db->get('rank_system')->result();

                foreach ($all_rewards as $rewards) { 
                    
                    $required_pairs = (int) $rewards->total_member;
                ?>
                
                <div class="d-flex align-items-center pb-4">
                    <div class="avatar-md me-4"></div>

                    <div class="flex-grow-1">
                        <h5 class="font-size-15 mb-1">
                            <a href="#" class="text-dark">
                                <?php echo $rewards->rank_name; ?>
                                [ <?php echo $required_pairs; ?> Pairs ]
                            </a>
                        </h5>

                        <?php if ($remaining_pairs >= $required_pairs) { ?>
                            <span class="text-success fw-bold">
                                🎉 Reward Achieved
                            </span>
                            <?php 
                                // deduct pairs after achievement
                                $remaining_pairs -= $required_pairs;
                            ?>
                        <?php } else { ?>
                            <span class="text-muted">
                                Remaining Pairs:
                                <strong class="text-danger">
                                    <?php echo ($required_pairs - $remaining_pairs); ?>
                                </strong>
                            </span>
                        <?php } ?>
                    </div>

                    <div class="flex-shrink-0 text-end">
                        <?php if ($remaining_pairs >= $required_pairs) { ?>
                            <i class="bx bx-check-circle font-size-24 text-success"></i>
                        <?php } else { ?>
                            <i class="bx bx-lock font-size-24 text-danger"></i>
                        <?php } ?>
                    </div>

                </div>

                <?php } ?> 
            </div>
        </div>
    </div>
</div>

                            <div class="col-xl-6">
                                <div class="card">
                                    <div class="card-header align-items-center d-flex">
                                        <h4 class="card-title mb-0 flex-grow-1">My Team</h4>
                                        <!--<div class="flex-shrink-0">-->
                                        <!--    <div class="dropdown">-->
                                        <!--        <a class=" dropdown-toggle" href="#" id="dropdownMenuButton2"-->
                                        <!--            data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false">-->
                                        <!--            <span class="text-muted">All Members<i class="mdi mdi-chevron-down ms-1"></i></span>-->
                                        <!--        </a>-->
                        
                                                <!--<div class="dropdown-menu dropdown-menu-end" aria-labelledby="dropdownMenuButton2">-->
                                                <!--    <a class="dropdown-item" href="#">Members</a>-->
                                                <!--    <a class="dropdown-item" href="#">New Members</a>-->
                                                <!--    <a class="dropdown-item" href="#">Old Members</a>-->
                                                <!--</div>-->
                                        <!--    </div>-->
                                        <!--</div>-->
                                    </div><!-- end card header -->

                                    <div class="card-body px-0">
                                        <div class="px-3" data-simplebar style="max-height: 386px;">
                                            <?php foreach ($all_member as $all_m) {  ?>
                                               
                                           
                                             <div class="d-flex align-items-center pb-4">
                                                <div class="avatar-md me-4">
                                                    <img src="
                                                    <?php
                                                        echo $all_m['prof_pic'] ? base_url('uploads/'.$all_m['prof_pic']) : base_url('uploads/logo.png'); 
                                                    ?>
                                                    " class="img-fluid" alt="">
                                                   
                                                </div>
                                                <div class="flex-grow-1">
                                                    <h5 class="font-size-15 mb-1"><a href="#" class="text-dark"><?php echo $all_m['name']; ?> 
                                                    [ <?php echo $all_m['city'] ?> ]</a></h5>
                                                    <span class="text-muted"><?php echo $all_m['rank']; ?></span>
                                                </div>
                                                <div class="flex-shrink-0 text-end">
                                                    <div class="dropdown align-self-start">
                                                        <a class="dropdown-toggle" href="#" role="button" data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                                            <i class="bx bx-dots-vertical-rounded font-size-24 text-dark"></i>
                                                        </a>
                                                       <!--  <div class="dropdown-menu">
                                                            <a class="dropdown-item" href="#">Copy</a>
                                                            <a class="dropdown-item" href="#">Save</a>
                                                            <a class="dropdown-item" href="#">Forward</a>
                                                            <a class="dropdown-item" href="#">Delete</a>
                                                        </div> -->
                                                    </div>
                                                </div>
                                            </div>
                                             <?php } ?> 

                                        </div>
                                    </div>
                                    <!-- end card body -->
                                </div>
                                <!-- end card -->
                            </div>
                            
                            <div class="col-xl-6">
                                <!-- card -->
                                <div class="card">
                                    <!-- card body -->
                                    <div class="card-body">
                                        <div class="d-flex flex-wrap align-items-center mb-4">
                                            <h5 class="card-title me-2">Earning Overview</h5> 
                                            <a class="btn btn-secondary btn-sm" href="<?php echo base_url('member/earning_dashboard');?>"> View Earning</a>
                                        </div>

                                        <div class="row align-items-center">
                                            <div class="col-xl-12">
                                                <div>
                                                    <div id="market-overview" data-colors='["#1c84ee", "#33c38e"]' class="apex-charts"></div>
                                                    
                                                </div>
                                            </div>
                                            <div class="col-xl-4">
                                                <div class="p-4">
                                                    <?php foreach ($earning_data as $ed) {?>
                                                                
                                                        <div class="mt-0"> 
                                                            <div class="d-flex align-items-center">
                                                           
                                                                <div class="flex-grow-1 ms-3">
                                                                    <span class="font-size-14"><?php echo $ed['type']; ?></span>
                                                                </div>
            
                                                                <div class="flex-shrink-0">
                                                                    <span class="badge rounded-pill badge-soft-success font-size-12 fw-medium"> <i class="fas fa-rupee-sign"></i>
                                                                    <?php
                                                                       $tr = $this->db_model->sum('amount', 'earning',array('userid' => $this->session->user_id,'type' => $ed['type']));
                                                                       echo $tr;
                                                                    ?>
                                                                    </span>
                                                                </div>
                                                            </div>
                                                        </div>  
                                                    <?php } ?>

                                                    <!--<div class="mt-4 pt-2">-->
                                                   
                                                    <!--      <a class="btn btn-primary btn-sm" href="<?php echo base_url('member/earning_dashboard');?>"> View Earning</a>-->
                                                          
                                                    <!--</div>-->
        
                                                </div>
                                            </div>
                                        </div> 
                                    </div>
                                    <!-- end card -->
                                </div>
                                <!-- end col -->
                            </div> 
                            <!-- end col -->
                        </div><!-- end row -->
                    </div>
                    <!-- container-fluid -->
                </div>
                <!-- End Page-content -->
                <?php  } ?>
                <footer class="footer">
                    <div class="container-fluid">
                        <div class="row">
                            <div class="col-sm-6">
                                <script>document.write(new Date().getFullYear())</script> © <?php echo config_item('company_name') ?>.
                            </div>
                            <!--<div class="col-sm-6">-->
                            <!--    <div class="text-sm-end d-none d-sm-block">-->
                            <!--        Powered by <a href="#!" class="text-decoration-underline">zestPRO</a>-->
                            <!--    </div>-->
                            <!--</div>-->
                        </div>
                    </div>

                </footer>
            </div>
            <!-- end main content-->
            
        </div>
        <!-- END layout-wrapper -->

        
        <!-- Right Sidebar -->
        <div class="right-bar">
            <div data-simplebar class="h-100">
                <div class="rightbar-title d-flex align-items-center bg-dark p-3">

                    <h5 class="m-0 me-2 text-white">Theme Customizer</h5>

                    <a href="javascript:void(0);" class="right-bar-toggle ms-auto">
                        <i class="mdi mdi-close noti-icon"></i>
                    </a>
                </div>

                <!-- Settings -->
                <hr class="m-0" />

                <div class="p-4">
                    <h6 class="mb-3">Layout</h6>
                    <div class="form-check form-check-inline">
                        <input class="form-check-input" type="radio" name="layout"
                            id="layout-vertical" value="vertical">
                        <label class="form-check-label" for="layout-vertical">Vertical</label>
                    </div>
                    <div class="form-check form-check-inline">
                        <input class="form-check-input" type="radio" name="layout"
                            id="layout-horizontal" value="horizontal">
                        <label class="form-check-label" for="layout-horizontal">Horizontal</label>
                    </div>

                    <h6 class="mt-4 mb-3 pt-2">Layout Mode</h6>

                    <div class="form-check form-check-inline">
                        <input class="form-check-input" type="radio" name="layout-mode"
                            id="layout-mode-light" value="light">
                        <label class="form-check-label" for="layout-mode-light">Light</label>
                    </div>
                    <div class="form-check form-check-inline">
                        <input class="form-check-input" type="radio" name="layout-mode"
                            id="layout-mode-dark" value="dark">
                        <label class="form-check-label" for="layout-mode-dark">Dark</label>
                    </div>

                    <h6 class="mt-4 mb-3 pt-2">Layout Width</h6>

                    <div class="form-check form-check-inline">
                        <input class="form-check-input" type="radio" name="layout-width"
                            id="layout-width-fuild" value="fuild" onchange="document.body.setAttribute('data-layout-size', 'fluid')">
                        <label class="form-check-label" for="layout-width-fuild">Fluid</label>
                    </div>
                    <div class="form-check form-check-inline">
                        <input class="form-check-input" type="radio" name="layout-width"
                            id="layout-width-boxed" value="boxed" onchange="document.body.setAttribute('data-layout-size', 'boxed'),document.body.setAttribute('data-sidebar-size', 'sm')">
                        <label class="form-check-label" for="layout-width-boxed">Boxed</label>
                    </div>

                    <h6 class="mt-4 mb-3 pt-2">Layout Position</h6>

                    <div class="form-check form-check-inline">
                        <input class="form-check-input" type="radio" name="layout-position"
                            id="layout-position-fixed" value="fixed" onchange="document.body.setAttribute('data-layout-scrollable', 'false')">
                        <label class="form-check-label" for="layout-position-fixed">Fixed</label>
                    </div>
                    <div class="form-check form-check-inline">
                        <input class="form-check-input" type="radio" name="layout-position"
                            id="layout-position-scrollable" value="scrollable" onchange="document.body.setAttribute('data-layout-scrollable', 'true')">
                        <label class="form-check-label" for="layout-position-scrollable">Scrollable</label>
                    </div>

                    <h6 class="mt-4 mb-3 pt-2">Topbar Color</h6>

                    <div class="form-check form-check-inline">
                        <input class="form-check-input" type="radio" name="topbar-color"
                            id="topbar-color-light" value="light" onchange="document.body.setAttribute('data-topbar', 'light')">
                        <label class="form-check-label" for="topbar-color-light">Light</label>
                    </div>
                    <div class="form-check form-check-inline">
                        <input class="form-check-input" type="radio" name="topbar-color"
                            id="topbar-color-dark" value="dark" onchange="document.body.setAttribute('data-topbar', 'dark')">
                        <label class="form-check-label" for="topbar-color-dark">Dark</label>
                    </div>

                    <h6 class="mt-4 mb-3 pt-2 sidebar-setting">Sidebar Size</h6>

                    <div class="form-check sidebar-setting">
                        <input class="form-check-input" type="radio" name="sidebar-size"
                            id="sidebar-size-default" value="default" onchange="document.body.setAttribute('data-sidebar-size', 'lg')">
                        <label class="form-check-label" for="sidebar-size-default">Default</label>
                    </div>
                    <div class="form-check sidebar-setting">
                        <input class="form-check-input" type="radio" name="sidebar-size"
                            id="sidebar-size-compact" value="compact" onchange="document.body.setAttribute('data-sidebar-size', 'md')">
                        <label class="form-check-label" for="sidebar-size-compact">Compact</label>
                    </div>
                    <div class="form-check sidebar-setting">
                        <input class="form-check-input" type="radio" name="sidebar-size"
                            id="sidebar-size-small" value="small" onchange="document.body.setAttribute('data-sidebar-size', 'sm')">
                        <label class="form-check-label" for="sidebar-size-small">Small (Icon View)</label>
                    </div>

                    <h6 class="mt-4 mb-3 pt-2 sidebar-setting">Sidebar Color</h6>

                    <div class="form-check sidebar-setting">
                        <input class="form-check-input" type="radio" name="sidebar-color"
                            id="sidebar-color-light" value="light" onchange="document.body.setAttribute('data-sidebar', 'light')">
                        <label class="form-check-label" for="sidebar-color-light">Light</label>
                    </div>
                    <div class="form-check sidebar-setting">
                        <input class="form-check-input" type="radio" name="sidebar-color"
                            id="sidebar-color-dark" value="dark" onchange="document.body.setAttribute('data-sidebar', 'dark')">
                        <label class="form-check-label" for="sidebar-color-dark">Dark</label>
                    </div>
                    <div class="form-check sidebar-setting">
                        <input class="form-check-input" type="radio" name="sidebar-color"
                            id="sidebar-color-brand" value="brand" onchange="document.body.setAttribute('data-sidebar', 'brand')">
                        <label class="form-check-label" for="sidebar-color-brand">Brand</label>
                    </div>

                    <h6 class="mt-4 mb-3 pt-2">Direction</h6>

                    <div class="form-check form-check-inline">
                        <input class="form-check-input" type="radio" name="layout-direction"
                            id="layout-direction-ltr" value="ltr">
                        <label class="form-check-label" for="layout-direction-ltr">LTR</label>
                    </div>
                    <div class="form-check form-check-inline">
                        <input class="form-check-input" type="radio" name="layout-direction"
                            id="layout-direction-rtl" value="rtl">
                        <label class="form-check-label" for="layout-direction-rtl">RTL</label>
                    </div>

                </div>


            </div> <!-- end slimscroll-menu-->
        </div>
        <!-- /Right-bar -->

        <!-- Right bar overlay-->
        <div class="rightbar-overlay"></div>
        <?php if ($this->session->userdata('show_popup') == 1): ?>
                <style>
                #loginPopupOverlay {
                    position: fixed;
                    top: 0; left: 0;
                    width: 100%;
                    height: 100%;
                    background: rgba(0,0,0,0.7);
                    display: flex;
                    justify-content: center;
                    align-items: center;
                    z-index: 99999;
                }

                #loginPopupImage {
                    width: 70%;
                    max-width: 600px;
                    cursor: pointer;
                    border-radius: 8px;
                }
            </style>

            <!--<div id="loginPopupOverlay">-->
            <!--    <img id="loginPopupImage" src="<?= base_url('uploads/Welcome_Page.png'); ?>">-->
            <!--</div>-->

            <script>
                document.getElementById('loginPopupOverlay').addEventListener('click', function() {
                    document.getElementById('loginPopupOverlay').style.display = 'none';
                });
            </script>

        <?php 
            // Remove after showing once
            $this->session->unset_userdata('show_popup'); 
            endif; 
        ?>

       
        <!-- JAVASCRIPT -->
        <script src="<?php echo base_url('assets/libs/jquery/jquery.min.js')?>"></script>
        <script src="<?php echo base_url('assets/libs/bootstrap/js/bootstrap.bundle.min.js')?>"></script>
        <script src="<?php echo base_url('assets/libs/metismenu/metisMenu.min.js')?>"></script>
        <script src="<?php echo base_url('assets/libs/simplebar/simplebar.min.js')?>"></script>
        <script src="<?php echo base_url('assets/libs/node-waves/waves.min.js')?>"></script>
        <script src="<?php echo base_url('assets/libs/feather-icons/feather.min.js')?>"></script>

        <!-- pace js -->
        <script src="<?php echo base_url('assets/libs/pace-js/pace.min.js')?>"></script>

         <!-- jquery.vectormap js-->
        <script src="<?php echo base_url('assets/libs/admin-resources/jquery.vectormap/jquery-jvectormap-1.2.2.min.js');?>"></script>
        <script src="<?php echo base_url('assets/libs/admin-resources/jquery.vectormap/maps/jquery-jvectormap-world-mill-en.js');?>"></script>
        <script src="<?php echo base_url('assets/libs/admin-resources/jquery.vectormap/maps/jquery-jvectormap-us-merc-en.js');?>"></script>
        <script src="<?php echo base_url('assets/libs/admin-resources/jquery.vectormap/maps/jquery-jvectormap-in-mill-en.js');?>"></script>
        <script src="<?php echo base_url('assets/libs/admin-resources/jquery.vectormap/maps/jquery-jvectormap-au-mill-en.js');?>"></script>
        <script src="<?php echo base_url('assets/libs/admin-resources/jquery.vectormap/maps/jquery-jvectormap-us-il-chicago-mill-en.js');?>"></script>
        <script src="<?php echo base_url('assets/libs/admin-resources/jquery.vectormap/maps/jquery-jvectormap-uk-mill-en.js');?>"></script>
        <script src="<?php echo base_url('assets/libs/admin-resources/jquery.vectormap/maps/jquery-jvectormap-ca-lcc-en.js');?>"></script>
 
        <!-- Init js-->
        <script type="text/javascript">
    
           ! function(a) {
            "use strict";


                function n() {}
                    n.prototype.init = function() {
                        
                        a("#world-map-markers").vectorMap({
                            map: "world_mill_en",
                            normalizeFunction: "polynomial",
                            hoverOpacity: .7,
                            hoverColor: !1,
                            regionStyle: {
                                initial: {
                                    fill: "#e9e9ef"
                                }
                            },
                            markerStyle: {
                                initial: {
                                    r: 9,
                                    fill: "#1c84ee",
                                    "fill-opacity": .9,
                                    stroke: "#fff",
                                    "stroke-width": 7,
                                    "stroke-opacity": .4
                                },
                                hover: {
                                    stroke: "#fff",
                                    "fill-opacity": 1,
                                    "stroke-width": 1.5
                                }
                            },
                            backgroundColor: "transparent",              
 
                           markers: [{
                                latLng:'<?php echo $loc->latlng; ?>',
                                name:'<?php echo $datm->name; ?>',
                           }]

                           


                        }), a("#usa-vectormap").vectorMap({
                            map: "us_merc_en",
                            backgroundColor: "transparent",
                            regionStyle: {
                                initial: {
                                    fill: "#1c84ee"
                                }
                            }
                        }), a("#india-vectormap").vectorMap({
                            map: "in_mill_en",
                            backgroundColor: "transparent",
                            regionStyle: {
                                initial: {
                                    fill: "#1c84ee"
                                }
                            }
                        }), a("#australia-vectormap").vectorMap({
                            map: "au_mill_en",
                            backgroundColor: "transparent",
                            regionStyle: {
                                initial: {
                                    fill: "#1c84ee"
                                }
                            }
                        })
                    }, a.VectorMap = new n, a.VectorMap.Constructor = n
                }(window.jQuery),
                function() {
                    "use strict";
                    window.jQuery.VectorMap.init()
                }();
        </script>


        <!-- apexcharts -->
        <script src="<?php echo base_url('assets/libs/apexcharts/apexcharts.min.js')?>"></script>
        <script src="<?php echo base_url('assets/js/pages/apexcharts.init.js')?>"></script>

        <!-- jquery.vectormap css -->
        <link href="<?php echo base_url('assets/libs/admin-resources/jquery.vectormap/jquery-jvectormap-1.2.2.css')?>" rel="stylesheet" type="text/css" />

        <!-- form validation -->
       <script src="<?php echo base_url('assets/js/pages/form-validation.init.js')?>"></script>

        <!-- pristine js -->
       <script src="<?php echo base_url('assets/libs/pristinejs/pristine.min.js')?>"></script>

        <!-- dashboard init -->
        <script src="<?php echo base_url('assets/js/pages/dashboard.init.js')?>"></script>

        <script src="<?php echo base_url('assets/js/app.js')?>"></script>

       
        <!-- choices js -->
        <script src="<?php echo base_url('assets/libs/choices.js/public/assets/scripts/choices.min.js')?>"></script>

        <!-- datepicker js -->
        <script src="<?php echo base_url('assets/libs/flatpickr/flatpickr.min.js')?>"></script>
          

       <!--  <script src="<?php echo base_url('assets/libs/%40fullcalendar/core/main.min.js')?>"></script>
        <script src="<?php echo base_url('assets/libs/%40fullcalendar/bootstrap/main.min.js')?>"></script>
        <script src="<?php echo base_url('assets/libs/%40fullcalendar/daygrid/main.min.js')?>"></script>
        <script src="<?php echo base_url('assets/libs/%40fullcalendar/timegrid/main.min.js')?>"></script>
        <script src="<?php echo base_url('assets/libs/%40fullcalendar/interaction/main.min.js')?>"></script>
 -->
         <!-- init js -->
        <script src="<?php echo base_url('assets/js/pages/form-advanced.init.js')?>"></script>

         <!-- dropzone js -->
        <script src="<?php echo base_url('assets/libs/dropzone/min/dropzone.min.js')?>"></script>

         <!-- Required datatable js -->
        <script src="<?php echo base_url('assets/libs/datatables.net/js/jquery.dataTables.min.js')?>"></script>
        <script src="<?php echo base_url('assets/libs/datatables.net-bs4/js/dataTables.bootstrap4.min.js')?>"></script>
        <!-- Buttons examples -->
        <script src="<?php echo base_url('assets/libs/datatables.net-buttons/js/dataTables.buttons.min.js')?>"></script>
        <script src="<?php echo base_url('assets/libs/datatables.net-buttons-bs4/js/buttons.bootstrap4.min.js')?>"></script>
        <script src="<?php echo base_url('assets/libs/jszip/jszip.min.js')?>"></script>
        <script src="<?php echo base_url('assets/libs/pdfmake/build/pdfmake.min.js')?>"></script>
        <script src="<?php echo base_url('assets/libs/pdfmake/build/vfs_fonts.js')?>"></script>
        <script src="<?php echo base_url('assets/libs/datatables.net-buttons/js/buttons.html5.min.js')?>"></script>
        <script src="<?php echo base_url('assets/libs/datatables.net-buttons/js/buttons.print.min.js')?>"></script>
        <script src="<?php echo base_url('assets/libs/datatables.net-buttons/js/buttons.colVis.min.js')?>"></script>
 
         <!-- Calendar init -->
        <script src="<?php echo base_url('assets/js/pages/calendar.init.js')?>"></script>
        
        <!-- Bootrstrap touchspin -->
        <script src="<?php echo base_url('assets/libs/bootstrap-touchspin/jquery.bootstrap-touchspin.min.js')?>"></script>

        <script src="<?php echo base_url('assets/js/pages/ecommerce-cart.init.js')?>"></script>
        <!-- Responsive examples -->
        <script src="<?php echo base_url('assets/libs/datatables.net-responsive/js/dataTables.responsive.min.js')?>"></script>
        <script src="<?php echo base_url('assets/libs/datatables.net-responsive-bs4/js/responsive.bootstrap4.min.js')?>"></script>

        <!-- Datatable init js -->
        <script src="<?php echo base_url('assets/js/pages/datatables.init.js')?>"></script>    
        <!-- Custom js -->
        <script src="<?php echo base_url('assets/js/pages/custom.js')?>"></script>  

         <!-- Session timeout js -->
        <script src="<?php echo base_url('assets/libs/%40curiosityx/bootstrap-session-timeout/index.js')?>"></script>

        <!-- Session timeout init js -->
        <script src="<?php echo base_url('assets/js/pages/session-timeout.init.js')?>"></script>

        <!-- notification init -->
        <script src="<?php echo base_url('assets/js/pages/notification.init.js')?>"></script>

           <!-- Sweet Alerts js -->
        <script src="<?php echo base_url('assets/libs/sweetalert2/sweetalert2.min.js')?>"></script>

        <!-- Sweet alert init js-->
        <script src="<?php echo base_url('assets/js/pages/sweetalert.init.js')?>"></script>

<script>
function share() {
  navigator.share({
    title: 'Page Title',
    text: 'Click On This Link To Join With Me  !!',
    url: "<?php echo site_url('site/register/A/' . $this->session->user_id) ?>"
  })
  .then(() => console.log('Successful share'))
  .catch((error) => console.log('Error sharing:', error));
}
</script>
<script>
function copyText() {
  var textToCopy = document.getElementById('text-to-copy').innerText;
  var tempInput = document.createElement('input');
  tempInput.setAttribute('value', textToCopy);
  document.body.appendChild(tempInput);
  tempInput.select();
  document.execCommand('copy');
  document.body.removeChild(tempInput);
  alert('Link copied!');
}
</script>
<script>
    $(document).ready(function() {
  $('#datatable1').DataTable();
  $('#datatable2').DataTable();
  $('#datatable3').DataTable();
  $('#datatable4').DataTable();
  $('#datatable5').DataTable();
  $('#datatable6').DataTable();
  $('#datatable7').DataTable();
  $('#datatable8').DataTable();
  $('#datatable9').DataTable();
  $('#datatable10').DataTable();
});

</script>
<script>
let hiddenTime = null;
let logoutTimer = null;


document.addEventListener("visibilitychange", function () {
    if (document.hidden) {

        
        hiddenTime = Date.now();

       
        logoutTimer = setTimeout(function () {
            window.location.href = "<?= site_url('member/logout') ?>";
        }, 5 * 60 * 1000); 

    } else {

        
        if (hiddenTime !== null) {
            let diff = (Date.now() - hiddenTime) / 1000; 

            if (diff >= 300) { 
                window.location.href = "<?= site_url('member/logout') ?>";
            }
        }

       
        clearTimeout(logoutTimer);
        hiddenTime = null;
    }
});
</script>

    </body>
<!-- Mirrored from themesbrand.com/dason/layouts/default/index.html by HTTrack Website Copier/3.x [XR&CO'2014], Sat, 04 Dec 2021 09:13:14 GMT -->
</html>