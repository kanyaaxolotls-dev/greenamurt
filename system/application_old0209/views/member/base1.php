<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Welcome <?php echo $this->session->name ?> | <?php echo config_item('company_name'); ?></title>

    
    <link href="<?php echo base_url('axxets/site/default/css/bootstrap.min.css') ?>" rel="stylesheet"/>
    <link href="<?php echo base_url('axxets/site/default/css/font-awesome.css') ?>" rel="stylesheet"/>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css"
          rel="stylesheet"
          type="text/css"/>
    <link href="<?php echo base_url('axxets/member/theme.css') ?>"
          rel="stylesheet" id="rt_style_components"
          type="text/css"/>
    <link rel="stylesheet"
          href="//code.jquery.com/ui/1.12.1/themes/eggplant/jquery-ui.css">

    <link rel="stylesheet" type="text/css"
          href="<?php echo base_url('axxets/member/custom.css') ?>">
    <link rel="stylesheet" type="text/css"
          href="<?php echo base_url(config_item('member_theme')) ?>" rel="stylesheet" id="rt_style_components"
          type="text/css"/> 
    
    <!-- favicon -->
    <link rel="shortcut icon"
          href="<?php echo base_url('uploads/favicon.ico') ?>"/>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.2.1/jquery.min.js"
            type="text/javascript"></script>
    <script src="axxets/member/custom.js"
            type="text/javascript"></script>

   <!-- Google Map With API live-->
    <script async defer src="https://maps.googleapis.com/maps/api/js?key=AIzaSyDY2fxOmeuNjJbL6NfuJ09b5-SY015-4Fo&callback=initMap">
    </script>
    <script src="<?php echo base_url('axxets/member/geomap_custom.js') ?>"
            type="text/javascript"></script>
    <!-- Sorting Table -->
    <link rel="stylesheet" type="text/css"
          href="https://cdn.datatables.net/1.10.19/css/jquery.dataTables.min.css" rel="stylesheet" id="rt_style_components"
          type="text/css"/> 
    
    <script type="text/javascript" src="https://code.jquery.com/jquery-3.3.1.js"></script>
    <script type="text/javascript" src="https://cdn.datatables.net/1.10.19/js/jquery.dataTables.min.js"></script>

    <link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.7.2/css/all.css" integrity="sha384-fnmOCqbTlWIlj8LyTjo7mOUStjsKC4pOpQbqyi7RrhN7udi9RwhKkMHpvLbHG9Sr" crossorigin="anonymous">

    <!-- Breaking news -->
    <link rel="stylesheet" href="<?php echo base_url('axxets/member/breaking-news-ticker.css') ?>">
    <script src="<?php echo base_url('axxets/member/breaking-news-ticker.min.js') ?>"></script>

   <!-- My downline count--> 
      <script type="text/javascript">
                jQuery(function($){
                       $('#result').load('<?php echo site_url('tree/alldownline') ?> #intro');
                });
    </script>

</head>
<body>


<div id="ui" class="ui">

    <!--header start-->
    <header id="header" class="ui-header">

        <div class="navbar-header">
            <!--logo start-->
            <a href="<?php echo site_url('member') ?>" class="navbar-brand">
                <span class="logo"><img
                            style="max-width: 100px; max-height: 36px"
                            src="<?php echo base_url('uploads/logo.png') ?>"
                            alt="Logo"/></span>
            </a>
            <!--logo end-->
        </div>

        <div class="navbar-collapse nav-responsive-disabled">

            <!--toggle buttons start-->
            <ul class="nav navbar-nav">
                <li>
                    <a class="toggle-btn" data-toggle="ui-nav" href="">
                        <i class="fa fa-bars"></i>
                      <!--   <?php
                            $this->db->select('rank,id,name');
                            $this->db->where('id', config_item('IT_EXT') . $this->session->user_id);
                            $q = $this->db->get('member');
                            $data1 = $q->result_array();
                            echo($data1[0]['name'].'<br>');
                        ?> -->

                    </a>
                </li>

            </ul>
            <!-- toggle buttons end -->
            <!--notification start-->
            <ul class="nav navbar-nav navbar-right ">
               
                <?php if (config_item('wallet_type')!=="Yes"){ ?>
                     <li class="wallet">
                     <strong class="wallet-balance"><i class="fas fa-wallet"></i> Wallet Balance: <i class="fa fa-rupee"></i>
                    <?php echo  0 + $this->db_model->select('balance', 'wallet', array('userid' => $this->session->user_id)) ?></strong>
                    </li>
                 <?php }else {?>
                     <li class="product-wallet-balance" style="margin-top: 13px">
                        <strong><i class="fas fa-wallet"></i> Product Wallet Balance: <i class="fa fa-rupee"></i>
                            <?php echo 0 + $this->db_model->select('balance', 'product_wallet', array('userid' => $this->session->user_id)) ?>
                        </strong>
                    </li>
                <?php } ?>
                    
                <li style="margin-top: 12px;" id="today">
                    <span style="padding:15px;"><?php  echo "Today is : ". date("F d, Y");?></span>
                </li>
                 <?php if (config_item('enable_repurchase') == "Yes") { ?>
                    <li>
                    <a href="<?php echo site_url('cart/pre_checkout') ?>"
                       class="btn cart hidden-xs" style="border-radius:0px"><i class="fas fa-shopping-cart"></i> Cart: <?php echo count($this->cart->contents()) ?> </a>                             </li>
                <?php } ?>
      
                <li>
                     
                    <a href="<?php echo site_url('member/topup-wallet') ?>"
                     class="btn cart hidden-xs" style="border-radius:0px "><i class="fas fa-wallet"></i> Add Wallet</a>
                     
                </li>
                <li class="dropdown language-switch">
                   <!-- <div id="google_translate_element"></div>
                    <script type="text/javascript">
                        function googleTranslateElementInit() {
                            new google.translate.TranslateElement({
                                pageLanguage: 'en',
                                includedLanguages: 'ar,bn,en,gu,hi,kn,mr,ms,pa,ta,te',
                                layout: google.translate.TranslateElement.InlineLayout.SIMPLE,
                                autoDisplay: false
                            }, 'google_translate_element');
                        }
                    </script>
                    <script type="text/javascript"
                            src="//translate.google.com/translate_a/element.js?cb=googleTranslateElementInit"></script>-->
                </li>
               
                <li class="dropdown dropdown-usermenu">
                    <a href="#" class=" dropdown-toggle" data-toggle="dropdown"
                       aria-expanded="true">
                        <span class="hidden-sm hidden-xs"
                              style="font-weight: bold">
                            <?php echo $this->session->name ?></span>
                        <span class="caret hidden-sm hidden-xs"></span>
                    </a>
                    <ul class="dropdown-menu dropdown-menu-usermenu pull-right">
                        <li><a href="<?php echo site_url('member/settings') ?>"><i
                                        class="fa fa-cogs"></i> Settings</a>
                        </li>
                        <li><a href="<?php echo site_url('member/proile') ?>"><i
                                        class="fa fa-user"></i> Profile</a>
                        </li>
                        <li class="divider"></li>
                        <li><a href="<?php echo site_url('member/logout') ?>"><i
                                        class="fa fa-sign-out"></i> Log Out</a>
                        </li>
                    </ul>
                </li>
            </ul>
            <!--notification end-->

        </div>

    </header> 
    <!--header end-->

    <!--sidebar start-->
    <aside id="aside" class="ui-aside">
        <ul class="nav" ui-nav>
            <li class="active welcome_box">
               
                          
                         <h4 style="color: #fff;"> <i class="fa fa-trophy"></i>
                       <?php 
                        $this->db->select('rank,id,name,sponsor');
                        $this->db->where('id', config_item('IT_EXT') . $this->session->user_id);
                        $q = $this->db->get('member');
                        $data1 = $q->result_array();
                    //   echo($data1[0]['name'].'<br>');
                        echo($data1[0]['rank']);
                       
                       ?>  
                       <span style="font-size: small;"><?php 
                         $sp_name = $this->db_model->select_multi('name', 'member', array('id' => $data1[0]['sponsor']));
                         echo "<br>Sponsor: ".$sp_name->name;
                         ?></span>
                   </h4>
                       
                     
                
            </li>
            <li class="left-menu">
                <a
                        href="<?php echo site_url('member') ?>"><i
                            class="fa fa-home"></i><span>Dashboard</span></a>
            </li>
             <li class="left-menu">
                <a href="<?php echo site_url('member/business-plan') ?>"><i
                            class="fa fa-home"></i><span>Business Plan</span></a>
            </li>

           
              <li class="left-menu">
                <a href=""><i class=" fa fa-cog" aria-hidden="true"></i><span>My Profile</span><i
                            class="fa fa-angle-right pull-right"></i></a>
                <ul class="nav nav-sub">
                     <li><a href="<?php echo site_url('member/welcome-letter') ?>"><i class="fa fa-file-text-o"></i><span> Acceptance Letter</span></a></li>
                    <li><a href="<?php echo site_url('member/proile') ?>"><span>Profile Details</span></a></li>
                    <li><a href="<?php echo site_url('member/settings') ?>"><span>Change Password</span></a></li>
                </ul>
            </li>
        
            <?php if (config_item('enable_epin') == "Yes") { ?>
                <li class="left-menu">
                    <a href="#">
                        <i class="fa fa-key"></i><span>e-PINs</span><i
                                class="fa fa-angle-right pull-right"></i></a>
                    <ul class="nav nav-sub">
                         <li><a href="<?php echo site_url('member/generate-epin') ?>"><span>Create New e-PIN</span></a>
                        </li>
                        <li><a href="<?php echo site_url('member/unused-epin') ?>"><span>Active e-PINs</span></a></li>
                        <li><a href="<?php echo site_url('member/used-epin') ?>"><span>In-active e-PINs</span></a></li>
                        <li><a href="<?php  echo site_url('member/transfer-epin') ?>"><span>Transfer e-PIN</span></a>
                        </li>
                       
                    </ul>
                </li>
            <?php } ?>
            
             <li class="left-menu">
                <a href=""><i class=" fa fa-cog" aria-hidden="true"></i><span>E-Wallet</span><i
                            class="fa fa-angle-right pull-right"></i></a>
                <ul class="nav nav-sub">
                   <li><a href="<?php echo site_url('wallet/transfer-balance') ?>"><span>Wallet Transfer</span></a></li>
                   <li><a href="<?php echo site_url('wallet/balance-transfer-list') ?>"><span>Transfer Report</span></a></li>
                    <?php if (config_item('user_withdraw') == "Yes") { ?>
                    <li><a href="<?php echo site_url('wallet/withdraw-payouts') ?>"><span>Withdraw payout request</span></a>
                    </li><?php } ?>
                </ul>
            </li>

            <?php if (config_item('enable_help_plan') !== "Yes") { ?>
                <li class="left-menu">
                    <a href=""><i class="fa fa-money"></i><span>Earnings & Payouts</span><i
                                class="fa fa-angle-right pull-right"></i></a>
                    <ul class="nav nav-sub">
                        <li><a href="<?php echo site_url('member/view-earning') ?>"><span>My Earnings</span></a></li>
                        <li><a href="<?php echo site_url('member/search-earning') ?>"><span>Find Earnings</span></a>
                        </li>
                        <?php if (config_item('enable_reward') == "Yes") { ?>
                            <li><a href="<?php echo site_url('member/my-rewards') ?>"><span>Rewards Achieved</span></a></li>
                        <?php } ?>
                      
                       
                        <li>
                            <a href="<?php echo site_url('wallet/withdrawal-list') ?>"><span>Payout Report</span></a>
                        </li>
                     
                    </ul>
                </li>
            <?php } ?>
            


            <li class="left-menu">
                <a href=""><i
                            class="fa fa-sitemap"></i><span>Genealogy Lookup</span><i
                            class="fa fa-angle-right pull-right"></i></a>
                <ul class="nav nav-sub">
                    <li><a href="<?php echo site_url('tree/genealogy') ?>"><span>My Genealogy</span></a></li>
                    <?php if (config_item('gen_list')=="Yes"){ ?>
                    <li><a href="<?php echo site_url('tree/generation_list') ?>"><span>Generation List</span></a></li>
                    <?php } ?>
                    <li><a href="<?php echo site_url('tree/my-tree') ?>"><span>Tree View</span></a></li>
                    <li><a href="<?php echo site_url('tree/alldownline') ?>"><span>All Downline List</span></a>
                  <!--  <li><a href="<?php //echo site_url('tree/directlist') ?>"><span>Direct Referrer List</span></a>-->
                         <li><a href="<?php echo site_url('tree/leveldata') ?>"><span>Level Wise Data</span></a></li>
                   <li><a href="<?php echo site_url('tree/level_direct_data') ?>"><span>My Direct List</span></a></li>

                   
                    </li>
                   
                    <li>
                        <a target="_blank"
                           href="<?php echo site_url('site/register/A/' . $this->session->user_id) ?>"><span>Add Member</span></a>
                    </li>
                </ul>
            </li>
            <?php if (config_item('enable_help_plan') == "Yes") { ?>
                <li class="left-menu">
                    <a href=""><i
                                class="fa fa-gift"></i><span>My Donations</span><i
                                class="fa fa-angle-right pull-right"></i></a>
                    <ul class="nav nav-sub">
                        <li><a href="<?php echo site_url('donation/sent-donation') ?>"><span>Sent History</span></a>
                        </li>
                        <li><a href="<?php echo site_url('donation/received-donation') ?>"><span>Received History</span></a>
                        </li>
                    </ul>
                </li>
            <?php } ?>
            <?php if (config_item('enable_repurchase') == "Yes") { ?>
                <li class="left-menu">
                    <a href=""><i class="fa fa-shopping-cart"></i><span>My Purchases</span><i
                                class="fa fa-angle-right pull-right"></i></a>
                    <ul class="nav nav-sub">
                        <li><a href="<?php echo site_url('cart/new-purchase') ?>"><span>New Purchase</span></a></li>
                        <li><a href="<?php echo site_url('cart/old-purchase') ?>"><span>Old Purchases</span></a></li>
                        <li><a href="<?php echo site_url('cart/pre-checkout') ?>"><span>My Cart</span></a></li>
                            <?php if (config_item('enable_help_plan') !== "Yes") { ?>
                        <li>
                            <a href="<?php echo site_url('member/my-invoices') ?>"><i
                                        class="fa fa-print"></i><span> Product Invoices</span></a>
                        </li>
                    <?php } ?>
                            </ul>
                        </li>
            <?php } ?>
            <?php if (config_item('enable_recharge') == "Yes") { ?>
                <li class="left-menu">
                    <a href=""><i
                                class="fa fa-mobile-phone"></i><span>Recharge Zone</span><i
                                class="fa fa-angle-right pull-right"></i></a>
                    <ul class="nav nav-sub">
                        <li><a href="<?php echo site_url('recharge/new-recharge') ?>"><span>New Recharge</span></a></li>
                        <li><a href="<?php echo site_url('recharge/old-recharges') ?>"><span>Old Recharges</span></a>
                        </li>
                    </ul>
                </li>
            <?php } ?>

            <?php if (config_item('enable_coupon') == "Yes") { ?>
                <li class="left-menu">
                    <a href=""><i
                                class="fa fa-code"></i><span>My Coupons</span><i
                                class="fa fa-angle-right pull-right"></i></a>
                    <ul class="nav nav-sub">
                        <li><a href="<?php echo site_url('coupon/unused') ?>"><span>Un Used Coupons</span></a></li>
                        <li><a href="<?php echo site_url('coupon/used') ?>"><span>Used Coupons</span></a></li>
                    </ul>
                </li>
            <?php } ?>
            <?php if (config_item('enable_ad_incm') == "Yes") { ?>
                <li class="left-menu">
                    <a href=""><i
                                class="fa fa-bullhorn"></i><span>Advertisements</span><i
                                class="fa fa-angle-right pull-right"></i></a>
                    <ul class="nav nav-sub">
                        <li><a href="<?php echo site_url('ads/myads') ?>"><span>My Ads</span></a></li>
                    </ul>
                </li>
            <?php } ?>
            <?php if (config_item('enable_investment') == "Yes") { ?>
                <li class="left-menu">
                    <a href=""><i
                                class="fa fa-bitcoin"></i><span>My Investments</span><i
                                class="fa fa-angle-right pull-right"></i></a>
                    <ul class="nav nav-sub">
                        <li><a href="<?php echo site_url('investments/new_invest') ?>"><span>New Investments</span></a>
                        </li>
                        <li>
                            <a href="<?php echo site_url('investments/recent_investment') ?>"><span>Recent Investments</span></a>
                        </li>
                        <li>
                            <a href="<?php echo site_url('investments/expired_investment') ?>"><span>Expired Investments</span></a>
                        </li>
                    </ul>
                </li>
            <?php } ?>
            <?php if (config_item('enable_survey') == "Yes") { ?>

                <li class="left-menu"><a href="<?php echo site_url('survey/mysurveys') ?>"><i class="fa fa-list"></i>
                        <span>My Surveys</span></a>
                </li>

            <?php } ?>
             <li class="left-menu">
                            <a href=""><i class="fa fa-money"></i><span>Fund Deposit</span><i class="fa fa-angle-right pull-right"></i></a>
                                <ul class="nav nav-sub">
                                    <li> <a href="<?php echo site_url('member/deposit') ?>"><span>Fund Deposit</span></a></li>
                                    <li><a href="<?php echo site_url('member/deposit_history') ?>"><span>Deposit History</span></a></li>
                                </ul>
            </li>

            <li class="left-menu">
                <a href=""><i class="fa fa-question"></i><span>Grievance Portal?</span><i
                            class="fa fa-angle-right pull-right"></i></a>
                <ul class="nav nav-sub">
                    <li><a href="<?php echo site_url('ticket/new-ticket') ?>"><span>New Support Request</span></a></li>
                    <li><a href="<?php echo site_url('ticket/old-Supports') ?>"><span>List Tickets</span></a></li>
                </ul>
            </li>
          
            <li class="left-menu"><a href="<?php echo site_url('member/logout') ?>"><i
                            class="fa fa-sign-out"></i> Log Out</a></li>
            </li>
        </ul>
    </aside>
    <!--sidebar end-->

    <!--main content start-->
    <div id="content" class="ui-content ui-content-aside-overlay">
        <div class="ui-content-body">

            <div class="ui-container">

                <div class="row">
                    <?php
                    echo validation_errors('<div class="alert alert-danger">', '</div>');
                    echo $this->session->flashdata('common_flash');
                    if (trim($layout) !== "") {
                        echo "<h3 style='color: #3c3c3c'>" . $title . "</h3><hr/>";
                        include_once $layout;
                    } else {

                    if (config_item('enable_help_plan') == "Yes"){
                        ?>
                        <?php if (config_item('is_demo') == TRUE) {
                            //echo '<div class="alert alert-danger">Please Pay your software development remaining balance to remove this banner !<br/> इस बैनर को हटाने केलिए कृपया अपनी शेष राशि का भुगतान करें !</div>';
                        } 
                      
                        ?>
                        <div class="row">

                            <div class="col-sm-6 col-lg-3">
                                <div class="panel panel-default-light panel-card border-default rounded helpbtn1">
                                    <div class="panel-heading">
                                        <div class="panel-title">Total Help Sent:
                                        </div>
                                    </div><!-- /.panel-heading -->

                                    <div class="panel-body">
                                        <div class="row">
                                            <div class="col-xs-12 col-item">
                                                <strong><?php echo config_item('currency') ?><?php echo $this->db_model->sum('donation_amount', 'donations', array(
                                                        'status'    => 'Accepted',
                                                        'sender_id' => $this->session->user_id,
                                                    )) ?></strong>
                                            </div><!-- /.col-xs-6 -->
                                        </div><!-- /.row -->
                                    </div><!-- /.panel-body -->
                                </div><!-- /.panel -->
                            </div><!-- /.col-sm-6 -->

                            <div class="col-sm-6 col-lg-3">
                                <div class="panel panel-default-light panel-card border-default rounded helpbtn2">
                                    <div class="panel-heading">
                                        <div class="panel-title">Total Help Received:
                                        </div>
                                    </div><!-- /.panel-heading -->

                                    <div class="panel-body">
                                        <div class="row">
                                            <div class="col-xs-12 col-item">
                                                <strong><?php echo config_item('currency') ?><?php echo $this->db_model->sum('donation_amount', 'donations', array(
                                                        'status'      => 'Accepted',
                                                        'receiver_id' => $this->session->user_id,
                                                    )) ?></strong>
                                            </div><!-- /.col-xs-6 -->
                                        </div><!-- /.row -->
                                    </div><!-- /.panel-body -->
                                </div><!-- /.panel -->
                            </div><!-- /.col-sm-6 -->

                            <div class="col-sm-6 col-lg-3">
                                <div class="panel panel-default-light panel-card border-default rounded helpbtn3">
                                    <div class="panel-heading">
                                        <div class="panel-title">Total Pending Receivable
                                        </div>
                                    </div><!-- /.panel-heading -->

                                    <div class="panel-body">
                                        <div class="row">
                                            <div class="col-xs-12 col-item">
                                                <strong><?php echo config_item('currency') ?><?php echo $this->db_model->sum('donation_amount', 'donations', array(
                                                        'status'      => 'Sent',
                                                        'receiver_id' => $this->session->user_id,
                                                    )) ?></strong>
                                            </div><!-- /.col-xs-6 -->
                                        </div><!-- /.row -->
                                    </div><!-- /.panel-body -->
                                </div><!-- /.panel -->
                            </div><!-- /.col-sm-6 -->

                            <div class="col-sm-6 col-lg-3">
                                <div class="panel panel-default-light panel-card border-default rounded helpbtn4">
                                    <div>
                                        <br/>
                                    </div><!-- /.panel-heading -->

                                    <div class="panel-body">
                                        <div class="row">
                                            <a href="<?php echo site_url('ticket/old-Supports') ?>">
                                                <div class="col-xs-12 col-item blink"
                                                     style="text-align: center; color:red">
                                                    <strong>Click Here</strong><br/>
                                                    <span>for Support</span>
                                                </div>
                                            </a>
                                            <!-- /.col-xs-6 -->
                                        </div><!-- /.row -->
                                    </div><!-- /.panel-body -->
                                </div><!-- /.panel -->
                            </div><!-- /.col-sm-6 -->
                        </div><!-- /.row -->

                        <div class="row">
                            <div class="col-sm-5">
                                <h3>Please Send Donations to:</h3>
                                <table class="table table-bordered table-striped alert-info">
                                    <tr style="font-weight: 900; background-color: green; color:#383e52">
                                        <td>Send to Id</td>
                                        <td>Phone No</td>
                                        <td>Amount</td>
                                        <td>#</td>
                                    </tr>
                                    <?php
                                    $this->db->select('id,receiver_id, donation_amount')->from('donations')
                                             ->where(array(
                                                 'status'         => 'Sent',
                                                 'sender_id'      => $this->session->user_id,
                                                 'expiry_date >=' => date('Y-m-d'),
                                             ))
                                             ->order_by('id', 'DESC')->limit(10);
                                    $no   = 1;
                                    $data = $this->db->get()->result();
                                    foreach ($data as $res) {
                                        echo '<tr>
                                            <td>' . config_item('ID_EXT') . $res->receiver_id . '</td>
                                            <td>' . $this->db_model->select('phone', 'member', array('id' => $res->receiver_id)) . '</td>
                                            <td>' . config_item('currency') . $res->donation_amount . '</td>
                                            <td><a href="javascript:;" onclick="document.getElementById(\'id\').value=\'' . $res->id . '\'" 
                                            data-toggle="modal" 
                                            data-target="#myModal" 
                                            class="btn btn-xs btn-primary">Send</a></td>
                                        </tr>';
                                    }
                                    ?>
                                </table>
                            </div>
                            <div class="col-sm-7">
                                <h3>Confirm Donations:</h3>
                                <table class="table table-bordered table-striped alert-warning">
                                    <tr style="font-weight: 900; background-color: red; color:#383e52">
                                        <td>Sender Id</td>
                                        <td>Phone No</td>
                                        <td>Amount</td>
                                        <td>Transaction Detail</td>
                                        <td>#</td>
                                    </tr>
                                    <?php
                                    $this->db->select('id,sender_id, donation_amount, trid')->from('donations')
                                             ->where(array(
                                                 'status'      => 'Waiting',
                                                 'receiver_id' => $this->session->user_id,
                                             ))
                                             ->order_by('id', 'DESC')->limit(10);
                                    $no   = 1;
                                    $data = $this->db->get()->result();
                                    foreach ($data as $res) {
                                        echo '<tr>
                                            <td>' . config_item('ID_EXT') . $res->sender_id . '</td>
                                            <td>' . $this->db_model->select('phone', 'member', array('id' => $res->sender_id)) . '</td>
                                            <td>' . config_item('currency') . $res->donation_amount . '</td>
                                            <td>' . $res->trid . '</td>
                                            <td><a href="donation/approve-donation/' . $res->id . '" onclick="return confirm(\'Are you sure, you have received this payment and want to confirm ?\')" 
                                            class="btn btn-xs btn-success">Accept
                                            </a></td>
                                        </tr>';
                                    }
                                    ?>
                                </table>
                            </div>
                        </div><!-- /.row -->

                        <div id="myModal" class="modal fade" role="dialog">
                            <div class="modal-dialog">
                                <div class="modal-content">
                                    <div class="modal-header">
                                        <button type="button" class="close" data-dismiss="modal">&times;</button>
                                        <h4 class="modal-title">Send Donation</h4>
                                    </div>
                                    <div class="modal-body">
                                        <p>
                                            <?php echo form_open('donation/send-donation') ?>
                                            <input type="hidden" name="id" id="id" value="">
                                            <label>Enter Transaction Detail (Optional)</label><br/>
                                            <textarea name="tdetail" class="form-control"></textarea><br/>
                                            <button class="btn btn-primary">Submit</button>
                                            <?php echo form_close() ?>
                                        </p>
                                    </div>
                                    <div class="modal-footer">
                                        <button type="button" class="btn btn-default" data-dismiss="modal">Close
                                        </button>
                                    </div>
                                </div>
                            </div>
                        </div>
                    <?php } else{ ?>
                    <?php if (config_item('is_demo') == TRUE) {
                        //echo '<div class="alert alert-danger">This Website/Software will expire soon, Please clear due balance !<br/> इस बैनर को हटाने के  लिए कृपया अपनी शेष राशि का भुगतान करें !</div>';
                    } ?>
                    <!--task states start-->
                    <div class="col-md-12 col-sm-12">
                        <div class="panel panel-default">
                            <header class="panel-heading">
                                Hello, 

                                <?php echo $this->session->name ?>[
                                    ID
                                    : <?php echo config_item('IT_EXT') . $this->session->user_id ?> ]
                                       [ Rank:  <?php 
                                        $this->db->select('rank');
                                        $this->db->where('id', config_item('IT_EXT') . $this->session->user_id);
                                        $q = $this->db->get('member');
                                        $data1 = $q->result_array();
                                        echo($data1[0]['rank']);
                                       ?> ]
                               
                                
                                    <span class="tools pull-right">
                                         <span style="float: left;"><?php  
                                        $data=$this->db_model->select_multi('bank_ac_no,tax_no,bank_ifsc', 'member_profile', array('userid' => $this->session->user_id));
                                        if($data->bank_ac_no ='' or $data->tax_no='' or $data->bank_ifsc=''){ ?>
                                        <span style="color: red;"> <?php  echo "Kindly complete your KYC/Bank Details";?></span> 
                                        <?php
                                        }else
                                        { ?>
                                            <span style="color: #fff;">
                                          Active <i class="fas fa-skiing-nordic"></i>
                                           <?php
                                        }
                                        ?>
                                        </span>
                                    </span>
                                            <a class="collapse-box fa fa-chevron-down"
                                               href="javascript:;"></a>
                                            <a class="close-box fa fa-times"
                                               href="javascript:;"></a>

                                        </span>

                                
                            </header>
                            <div class="panel-body">
                                <div class="row w-states">
                                    <div class="panel">
                                        <div class="panel-body">
                                            <?php 
                                                
                                                $data = $this->db_model->select_multi('aadhar_no,bank_ifsc,bank_ac_no,add_proof,id_proof', 'member_profile', array('userid' => $this->session->user_id));
                                            ?>

                                            <div class="breaking-news-ticker" id="example" style="font-size: small;border-width: 0px;">
                                                  <div class="bn-label">Flash Updates</div>
                                                  <div class="bn-news" style="color: #a979d1">
                                                    <ul>
                                                    <li><a href="#"><?php  if($data->aadhar_no=="" or $bank_ifsc=="" or $add_proof=="" or $id_proof=""){
                                                    echo "KYC IS NOT COMPETED. KINDLY UPDATE YOUR KYC TO AVOID DELAYING PAYOUTS"; }else{
                                                        echo "Keep your profile up to date!";
                                                    } ?></a></li>
                                                    <li style="text-transform: uppercase; ">2. As. Govt. guidline, distributor should submit their KYC.</li>
                
                                                    </ul>
                                                  </div>
                                                <div class="bn-controls">
                                                    <button><span class="bn-arrow bn-prev"></span></button>
                                                    <button><span class="bn-action"></span></button>
                                                    <button><span class="bn-arrow bn-next"></span></button>
                                                  </div>
                                                </div>

                                                <script type="text/javascript"> $('#example').breakingNews();
                                                    $('#example').breakingNews({
                                                      effect: 'fade'
                                                    });

                                                </script>
                                           

                                        </div>
                                    </div>

                                </div>
                                <div class="row w-states">
                                    <div class="col-md-2 col-xs-6">
                                        <div class="card">
                                        <a href="#"
                                           class="btn purple-btn btn-block">
                                            <span class="value" id="result">
                                      
                                            </span>  
                                              <span class="info">My Downline</span>
                                        </a>
                                    </div>
                                    </div>
                                    <div class="col-md-2 col-xs-6">
                                        <a href="#"
                                           class="btn purple-btn btn-block">
                                            <span class="value" id="dw"><?php

                                                echo $this->db_model->count_all('member', array('sponsor' => $this->session->user_id));
                                                ?></span>
                                            <span class="info">Direct/Sponsored</span>
                                        </a>
                                    </div>
                                    <div class="col-md-2 col-xs-6">
                                        <a href="#"
                                           class="btn purple-btn btn-block">
                                            <span class="value"><i class="fa fa-rupee"></i> <?php $data = $this->db_model->sum('amount', 'earning', array('userid' => $this->session->user_id));
                                                if ($data <= "0") {
                                                    //echo config_item('currency') . '0';
                                                    echo '0';

                                                } else {
                                                    //echo config_item('currency') . $data;
                                                     echo $data;
                                                } ?></span>
                                            <span class="info">Total Earning</span>

                                        </a>
                                    </div>
                                    <div class="col-md-2 col-xs-6">
                                        <a href="#"
                                           class="btn purple-btn btn-block">
                                            <span class="value"><i class="fa fa-rupee"></i> 
                                                <?php echo $this->db_model->select('balance', 'wallet', array('userid' => $this->session->user_id)); ?></span>
                                            <span class="info">Amount in Wallet</span>
                                        </a>
                                    </div>
                                        <div class="col-md-2 col-xs-6">
                                        <a href="#"
                                           class="btn purple-btn btn-block margin0">
                                            <span class="value"><i class="fa fa-rupee"></i>  <?php $data = $this->db_model->sum('amount', 'withdraw_request', array(
                                                    'userid' => $this->session->user_id,
                                                    'status' => 'Un-Paid',
                                                ));
                                                if ($data == "") {
                                                    //echo config_item('currency') . '0';
                                                    echo '0';
                                                } else {
                                                   // echo config_item('currency') . $data;
                                                     echo  $data;
                                                }
                                                ?></span>
                                            <span class="info">In-Process payment</span>
                                            
                                        </a>
                                    </div>
                                    <div class="col-md-2 col-xs-6">
                                        <a href="#"
                                           class="btn purple-btn btn-block margin0">
                                            <span class="value"><i class="fa fa-rupee"></i>  <?php $data = $this->db_model->sum('amount', 'withdraw_request', array(
                                                    'userid' => $this->session->user_id,
                                                    'status' => 'Paid',
                                                ));
                                                if ($data == "") {
                                                    //echo config_item('currency') . '0';
                                                     echo  '0';
                                                } else {
                                                    //echo config_item('currency') . $data;
                                                    echo $data;
                                                }
                                                ?></span>
                                            <span class="info">Amount in Bank</span>
                                        </a>
                                    </div>
                                
                                </div>
                               <?php if (config_item('enable_repurchase') == "Yes") { ?>
                                <br>
                                <div class="row">
                                    <div class="col-md-4 col-xs-6">
                                        <a href="#"
                                           class="btn purple-btn btn-block">
                                            <span class="value" style="font-size: 18px;"><i class="fa fa-rupee"></i>  <?php $data = $this->db_model->sum('amount', 'earning', array(
                                                    'userid' => $this->session->user_id,
                                                    'status' => 'Paid',
                                                    'type'=>'Repurchase Income',
                                                ));
                                                if ($data == "") {
                                                    //echo config_item('currency') . '0';
                                                     echo  '0';
                                                } else {
                                                    //echo config_item('currency') . $data;
                                                    echo $data;
                                                }
                                                ?></span><br>
                                            <span class="info">RE-PURCHASE INCOME</span>
                                        </a>
                                    </div>
                                       <div class="col-md-4 col-xs-6">
                                        <a href="#"
                                           class="btn purple-btn btn-block ">
                                             <span class="value" style="font-size: 18px;"><i class="fa fa-rupee"></i>  <?php $data = $this->db_model->sum('amount', 'earning', array(
                                                    'userid' => $this->session->user_id,
                                                    'status' => 'Pending',
                                                    'type'=>'Repurchase Income',
                                                ));
                                                if ($data == "") {
                                                    //echo config_item('currency') . '0';
                                                     echo  '0';
                                                } else {
                                                    //echo config_item('currency') . $data;
                                                    echo $data;
                                                }
                                                ?></span><br>
                                            <span class="info">PENDING REPURCHASE EARNINGS</span>
                                        </a>
                                    </div>
                                       <div class="col-md-4 col-xs-6">
                                           <a href="#"
                                           class="btn purple-btn btn-block ">
                                             <span class="value" style="font-size: 18px;"><i class="fa fa-rupee"></i>  <?php $data = $this->db_model->sum('cost', 'product_sale', array(
                                                    'userid' => $this->session->user_id,
                                                   
                                                 
                                                ));
                                                if ($data == "") {
                                                    //echo config_item('currency') . '0';
                                                     echo  '0';
                                                } else {
                                                    //echo config_item('currency') . $data;
                                                    echo $data;
                                                }
                                                ?></span><br>
                                            <span class="info">PURCHASE VALUE</span>
                                        </a>
                                    </div>
                                </div>
                            <?php } ?>
                            </div>
                           
                        </div>
                    </div>
                    <!--task states end-->

                    <!--charts start--> 
                    <div class="col-md-8 col-sm-8">
                        <div class="panel panel-default latestearning">
                              <header class="panel-heading">
                              Latest Commission
                                <span class="tools pull-right">
                                            <a class="collapse-box fa fa-chevron-down"
                                               href="javascript:;"></a>
                                            <a class="close-box fa fa-times"
                                               href="javascript:;"></a>
                                        </span>
                            </header>

                            <div class="panel-body" style="min-height: 385px">
                                <?php
                                if (config_item('enable_topup') == "Yes" && $this->db_model->select('topup', 'member', array('id' => $this->session->user_id)) <= '0') {

                                    ?>

                                    <!--<div class="alert alert-warning">Top Up your Account.</div>-->
                                    <div class="alert" style="background-color: #99fc99">
                                    <?php 
                                        $prod_id=$this->db_model->select('product_id', 'product_sale', array('userid'=> $this->session->user_id));
                                        $prod_name=$this->db_model->select_multi('prod_name,prod_price', 'product', array('id' => $prod_id));
                                        echo "<strong>Package Selected : ".$prod_name->prod_name." [INR:".$prod_name->prod_price."]</strong>";
                                    ?>
                                    </div>
                                    <hr/>
                                    <?php echo form_open('member/topup') ?> 
                                 
                                    <input type="hidden" class="form-control" name="prod_cost" value="<?php echo $prod_name->prod_price; ?>"/>
                                    
                                    <div class="form-group">
                                        <label for="signup_package" class="control-label">Signup Package</label>
                                        <select class="form-control" id="signup_package"
                                                name="signup_package">
                                            <option selected><?php echo $prod_name->prod_name ?></option>
                                            <?php  
                                                $this->db->select('id,prod_name,prod_price')->from('product');
                                                $result =  $this->db->get()->result();
                                                
                                                foreach ($result as $kue) {
                                                 echo '<option value='.$kue->id.'>'.$kue->prod_name.'('.$kue->prod_price.')</option>';
                                                }
                                            ?>
                                        </select>

                                    </div>
                                
                                    <label>Enter TopUp e-PIN</label>
                                    <input class="form-control" type="text"
                                           name="topup"><br/>

                                    <button type="submit"
                                            class="btn btn-success">Submit
                                    </button>
                                    <?php echo form_close() ?>
                                    <?php
                                } else {
                                    ?> 
                                   <!-- <strong>Latest Earnings</strong>-->
                                    
                                    <div class="table-responsive" style="margin: -5px;">
                                        <table class="table table-hovered table-striped">
                                            <thead>
                                            <tr>
                                                 <td>Id</td>
                                                <td>Date</td>
                                                <td>Earning Type</td>
                                                <td>Level/pair</td>
                                                <td>Amount</td>

                                                
                                            </tr>
                                            </thead>
                                            <?php
                                            $this->db->select('id,type, amount, date,levlno')
                                                     ->where('userid', $this->session->user_id)->order_by('id', 'DESC')
                                                     ->limit(15);
                                            $inc = $this->db->get('earning')->result();

                                            ?>
                                            <tbody>
                                            <?php foreach ($inc as $e): ?>
                                                <tr>
                                                    <td><?php echo $e->id ?></td>
                                                    <td><?php echo $e->date ?></td>
                                                    <td><?php echo $e->type ?></td>
                                                     <td><?php echo $e->levlno ?></td>
                                                    <td><?php echo config_item('currency') . $e->amount ?></td>
                                                    
                                                    
                                                </tr>
                                            <?php endforeach; ?>
                                            </tbody>
                                        </table>

                                    </div>

                                <?php } ?>
                            </div>
                        </div>
                    </div>
                    
                    <div class="col-md-4 col-sm-4">
                        <div class="panel panel-default">
                          <div class="panel-heading" >
                               Flash Updates
                                <span class="tools pull-right">
                                            <a class="collapse-box fa fa-chevron-down"
                                               href="javascript:;"></a>
                                            <a class="close-box fa fa-times"
                                               href="javascript:;"></a>
                                        </span>
                            </div>
                            <div class="panel-body" style="min-height: auto">
                                     <div class="table-responsive">
                                        <table class="table table-hovered table-bordered">
                                            
                                            <?php
                                            $this->db->select('news_title, news_details')
                                                     ->order_by('id', 'DESC')
                                                     ->limit(6);
                                            $inc = $this->db->get('news')->result();

                                            ?>
                                            <tbody>
                                            <?php foreach ($inc as $e): ?>
                                                <tr>
                                                    <td><span class="blink"><img src="http://www.wtsda-region5.com/icon_new.png" width="30px"> <?php echo $e->news_title ?></span></td>
                                                   
                                                </tr>
                                            <?php endforeach; ?>
                                            </tbody>
                                        </table>

                                    </div>
                            </div>
                        </div>
                        <div class="panel panel-default">
                            <div class="panel-heading">Geo Chart</div>
                                <div class="panel-body">
                                    <div id="map" style="min-height: 280px;"></div>

                                </div>
                        </div>


                    </div>

                         <div class="col-md-8 col-sm-8">
                                <div class="panel panel-default">
                                    <header class="panel-heading">
                                        Referral Link
                                                :
                                        <span class="tools pull-right">
                                                    <a class="collapse-box fa fa-chevron-down"
                                                       href="javascript:;"></a>
                                                    <a class="close-box fa fa-times"
                                                       href="javascript:;"></a>
                                                </span>
                                    </header>
                                    <div class="panel-body" style="height: auto">
                                        <input type="text" value="<?php echo site_url() . 'site/register/A/' . $this->session->user_id ?>" id="myInput" class="form-control" onclick="myFunction()" onmouseout="outFunc()" readonly>
                                        <span class="tooltiptext" id="myTooltip" style="color: blue">Double click to copy</span>

                                    </div>
                                </div>

                        </div>

                </div>
                <?php }
                } ?>
            </div>
        </div>


    </div>

</div>


    <!--footer start-->
    <div id="footer"
         style="">
        <?php echo date('Y') ?> &copy; <?php echo config_item('company_name') ?>
    </div>
    <!--footer end-->
    
<!--main content end-->


<!-- inject:js -->
<script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js"
        type="text/javascript"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery.nicescroll/3.7.6/jquery.nicescroll.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/autosize.js/4.0.0/autosize.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/modernizr/2.8.3/modernizr.min.js"></script>
<!-- endinject -->
<script src="<?php echo base_url('axxets/member/custom.js') ?>"></script>
<script src="<?php echo base_url('axxets/member/theme.js') ?>"></script>
<script src="https://code.jquery.com/ui/1.12.1/jquery-ui.js"></script>
<script src="//cdn.ckeditor.com/4.7.3/standard/ckeditor.js"></script>
<script>
    CKEDITOR.replace('editor');
</script>
<script>
    $(document).ready(function () {
        $('[data-toggle="popover"]').popover({html: true, placement: "top"});
    });
</script>
<script>
    $(function () {
        $(".datepicker").datepicker({
            dateFormat: "yy-mm-dd",
            yearRange: "-70:+70",
            changeMonth: true,
            changeYear: true,
            defaultDate: 0,
            showOptions: {direction: "down"},
        });
    });
</script>

</body>
</html>
