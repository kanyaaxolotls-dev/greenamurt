<!DOCTYPE html>
<html lang="en">
<head> 
  <meta charset="utf-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
  <title>
    Member | <?php echo config_item('company_name') ?>
  </title>
  <!-- Favicon -->

  <link href=" <?php echo base_url('axxets/member/shopp/img/brand/favicon.png') ?>" rel="icon" type="image/png">
  <!-- Fonts -->
  <link href="https://fonts.googleapis.com/css?family=Open+Sans:300,400,600,700" rel="stylesheet">
  <!-- Icons -->

  <link href="<?php echo base_url('axxets/member/Modern/js/plugins/nucleo/css/nucleo.css') ?>" rel="stylesheet" />
  <link href="<?php echo base_url('axxets/member/Modern/css/sky.css') ?>" rel="stylesheet" />
  <link href="<?php echo base_url('axxets/member/Modern/js/plugins/@fortawesome/fontawesome-free/css/all.min.css')?>" rel="stylesheet" />
  <!-- CSS files for toaster -->
  <link href="<?php echo base_url('axxets/member/Modern/css/jquery.toast.min.css')?>" rel="stylesheet" type="text/css">
  
 <!-- CSS Files -->
  <link href="<?php echo base_url('axxets/member/Modern/css/argon-dashboard.css?v=1.1.0')?>" rel="stylesheet" />

<link rel="stylesheet" href="//code.jquery.com/ui/1.12.1/themes/base/jquery-ui.css">

</head>

<body id="home"> 
  <?php  
          $this->db->select('rank,id,name,sponsor');
          $this->db->where('id', config_item('IT_EXT') . $this->session->user_id);
          $q = $this->db->get('member');
          $data1 = $q->result_array();      ?>


    <nav class="navbar navbar-vertical fixed-left navbar-expand-md navbar-light bg-purple" id="sidenav-main">
      <div class="container-fluid">
      <!-- Toggler -->
         <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#sidenav-collapse-main" aria-controls="sidenav-main" aria-expanded="false" aria-label="Toggle navigation">
          <span class="navbar-toggler-icon"></span>
        </button>
      <!-- Brand -->
      <div class="sidenav-header " >
        <a class="navbar-brand pt-0" href="https://eagalsit.com/">
        <img src="<?php echo base_url('uploads/logo.png') ?>" class="navbar-brand-img" alt="...">
        </a>
      </div>
      
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
                <img alt="Image placeholder" src="<?php echo base_url('axxets/member/Modern/img/theme/team-1-800x800.jpg')?>
                ">
              </span>
            </div>
          </a>
          <div class="dropdown-menu dropdown-menu-arrow dropdown-menu-right">
            <div class=" dropdown-header noti-title">
              <h6 class="text-overflow m-0">Welcome! </h6>
            </div>
             
              <a href="#!" class="dropdown-item">
                <i class="ni ni-user-run"></i>
                <span><?php echo "User Id [".$this->session->user_id."]" ?></span>
              </a>

            
              <a href="#!" class="dropdown-item">
                <i class="ni ni-user-run"></i>
                <span>Sponsor:<?php echo $data1[0]['sponsor'] ?></span>
              </a>
              <div class="dropdown-divider"></div>
                <a href="<?php echo site_url('member/proile') ?>" class="dropdown-item">
                <i class="ni ni-single-02"></i>
                <span>My profile</span>
              </a>
              <a href="<?php echo site_url('member/settings') ?>" class="dropdown-item">
                <i class="ni ni-settings-gear-65"></i>
                <span>Settings</span>
              </a>
              <a href="#" class="dropdown-item">
                <i class="ni ni-calendar-grid-58"></i>
                <span>Activity</span>
              </a>
              <a href="#" class="dropdown-item">
                <i class="ni ni-support-16"></i>
                <span>Support</span>
              </a>
              
            <div class="dropdown-divider"></div>
            <a href="<?php echo site_url('site/login') ?>" class="dropdown-item">
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
              <a href="https://eagalsit.com/">
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
        <!-- Navigation --> 
        <ul class="navbar-nav">
   
          <li class="nav-item"> 
          <a class=" nav-link active " href="<?php echo site_url('member') ?>"> <i class="ni ni-palette text-primary"></i> Dashboard
            </a>
          </li>
         <li class="nav-item">
            <a class="nav-link " href="<?php echo site_url('member/business-plan') ?>">
              <i class="ni ni-planet text-white"></i> Business Plan
            </a>
          </li>
       <!--     <li class="nav-item">
            <a class="nav-link " href="<?php echo site_url('member/welcome-letter') ?>">
              <i class="ni ni-pin-3 text-orange"></i> Welcome Letter
            </a> 
          </li> -->
         
          <!-- Epin Menu -->
          
          <?php if (config_item('enable_epin') == "Yes" && config_item('plantype') != "Referral Plan") { ?>
            
          <li class="nav-item"> 
              <a class="nav-link " data-toggle="collapse" href="#componentsExamples" aria-expanded="false" class="collapsed">
              <i class="ni ni-settings-gear-65 text-primary"></i> E-Pin<b class="caret"></b></a>
                <div class="navbar-nav collapse" id="componentsExamples" aria-expanded="false" >
                    <ul class="nav">
                    <!--  <li class="nav-item">-->
                    <!--<a class="nav-link " href="<?php echo site_url('member/generate-epin') ?>">-->
                    <!--    <i class="ni ni-planet text-white"></i> Create E-Pin-->
                    <!--   </a>-->
                    <!--  </li>-->
                      <li class="nav-item">
                        <a class="nav-link " href="<?php echo site_url('member/unused-epin') ?>">
                          <i class="ni ni-planet text-white"></i> Unused E-Pin
                        </a>
                      </li>
                      <li class="nav-item">
                        <a class="nav-link " href="<?php echo site_url('member/used-epin') ?>">
                          <i class="ni ni-planet text-white"></i> Used E-Pin
                        </a>
                      </li>
                       <li class="nav-item">
                        <a class="nav-link " href="<?php echo site_url('member/transfer-epin') ?>">
                          <i class="ni ni-planet text-white"></i> Transfer E-Pin
                        </a>
                      </li>


                   </ul>
                </div>
          </li>
          <?php } ?> 

          <!-- Wallet Menu -->

          <li class="nav-item"> 
              <a class="nav-link " data-toggle="collapse" href="#wallet" aria-expanded="false" class="collapsed">
              <i class="ni ni-support-16 text-primary"></i> E-Wallet<b class="caret"></b></a>
                <div class="navbar-nav collapse" id="wallet" aria-expanded="false" >
                    <ul class="nav">
                      <!--<li class="nav-item">-->
                      <!--  <a class="nav-link " href="<?php echo site_url('member/topup-wallet') ?>">-->
                      <!--    <i class="ni ni-planet text-white"></i> Add Money-->
                      <!--  </a>-->
                      <!--</li> -->
               <!--        <li class="nav-item">
                        <a class="nav-link " href="<?php echo site_url('wallet/transfer-balance') ?>">
                          <i class="ni ni-planet text-white"></i> Transfer Balance
                        </a>
                      </li>
                      <li class="nav-item">
                        <a class="nav-link " href="<?php echo site_url('wallet/wallet_transactions') ?>">
                          <i class="ni ni-planet text-white"></i> Transfer Report
                        </a>
                      </li> -->
                      <!-- <li class="nav-item">-->
                      <!--  <a class="nav-link " href="<?php echo site_url('wallet/trans_history') ?>">-->
                      <!--    <i class="ni ni-planet text-white"></i> Transaction Details-->
                      <!--  </a>-->
                      <!--</li>-->
                    <?php if (config_item('user_withdraw') == "Yes") { ?>
                      <li class="nav-item">
                          <a class="nav-link " href="<?php echo site_url('wallet/withdraw-payouts') ?>">
                        <i class="ni ni-planet text-white"></i> Withdraw Request
                        </a>
                      </li>
                      <?php } ?>
                      
                   </ul>
                </div>
          </li> 

          <!-- earning & payout --> 
 
          <?php if (config_item('enable_help_plan') !== "Yes") { ?>
            <li class="nav-item"> 
              <a class="nav-link " data-toggle="collapse" href="#payout" aria-expanded="false" class="collapsed">
              <i class="ni ni-calendar-grid-58 text-primary"></i> Earning & Payout<b class="caret"></b></a>
                <div class="navbar-nav collapse" id="payout" aria-expanded="false" >
                    <ul class="nav">
                      <li class="nav-item">
                        <a class="nav-link " href="<?php echo site_url('member/view-earning') ?>">
                          <i class="ni ni-planet text-white"></i> My Earnings 
                        </a>
                      </li>
                      <?php if($config['plantype']=='Binary ROI') { ?>
                      <li class="nav-item">
                        <a class="nav-link " href="<?php echo site_url('member/binary_roi_earning') ?>">
                          <i class="ni ni-planet text-white"></i> Binary ROI
                        </a>
                      </li> 
                    <?php } ?>
                      <!--  <li class="nav-item">-->
                      <!--  <a class="nav-link " href="<?php echo site_url('member/matching_earning') ?>">-->
                      <!--    <i class="ni ni-planet text-white"></i> Matching Income-->
                      <!--  </a>-->
                      <!--</li> -->
                    
                      <!--<li class="nav-item">-->
                      <!--    <a class="nav-link " href="<?php echo site_url('member/roi_earning') ?>">-->
                      <!--      <i class="ni ni-planet text-white"></i> Daily Booster-->
                      <!--    </a>-->
                      <!--  </li> -->
                      
                      <li class="nav-item">
                        <a class="nav-link " href="<?php echo site_url('member/search-earning') ?>">
                          <i class="ni ni-planet text-white"></i> Search Earning
                        </a>
                      </li>
                      <?php if (config_item('enable_reward') == "Yes") { ?>
                      <li class="nav-item">
                        <a class="nav-link " href="<?php echo site_url('member/my-rewards') ?>">
                          <i class="ni ni-planet text-white"></i> My Rewards
                        </a>
                      </li>
                      <?php } ?>
                      <li class="nav-item">
                        <a class="nav-link " href="<?php echo site_url('wallet/withdrawal-list') ?>">
                          <i class="ni ni-planet text-white"></i> Payout Report
                        </a>
                      </li>
                    <!--    <li class="nav-item">
                        <a class="nav-link " href="<?php echo site_url('member/daily_roi_earning') ?>">
                          <i class="ni ni-planet text-white"></i> Daily ROI
                        </a>
                      </li> -->
                       <!-- <li class="nav-item">
                        <a class="nav-link " href="<?php echo site_url('member/daily_level_roi_earning') ?>">
                          <i class="ni ni-planet text-white"></i> Daily Level ROI
                        </a>
                      </li> -->

                   </ul>
                </div>
            </li>
          <?php } ?>

          <!-- Genealogy --> 
           <li class="nav-item"> 
              <a class="nav-link " data-toggle="collapse" href="#geneology" aria-expanded="false" class="collapsed"
>              <i class="ni ni-single-02 text-primary"></i> Genealogy Lookup<b class="caret"></b></a>
                <div class="navbar-nav collapse" id="geneology" aria-expanded="false" >
                    <ul class="nav">
                      <?php if (config_item('gen_list')=="Yes"){ ?>
                      
                     <!-- <li class="nav-item">
                       <a class="nav-link " href="<?php echo site_url('tree/generation_list') ?>">
                         <i class="ni ni-planet text-white"></i> Generation List
                     </a>
                      </li>-->
                    <!--   <li class="nav-item">
                        <a class="nav-link " href="<?php echo site_url('tree/sponsor_level_list') ?>">
                         <i class="ni ni-planet text-white"></i> Level Wise Team
                        </a> 
                      </li> -->
                      <?php if($config['plantype'] = "Single Leg with Autol") { ?>
                   <!--    <li class="nav-item">
                        <a class="nav-link " href="<?php echo site_url('tree/autopool_one') ?>">
                         <i class="ni ni-planet text-white"></i> Silver Club
                        </a> 
                      </li> 
                       <li class="nav-item">
                        <a class="nav-link " href="<?php echo site_url('tree/autopool_second') ?>">
                         <i class="ni ni-planet text-white"></i> Gold Club
                        </a> 
                      </li> 
                       <li class="nav-item">
                        <a class="nav-link " href="<?php echo site_url('tree/autopool_third') ?>">
                         <i class="ni ni-planet text-white"></i> Pearl Club
                        </a> 
                      </li> 
                       <li class="nav-item">
                        <a class="nav-link " href="<?php echo site_url('tree/autopool_fourth') ?>">
                         <i class="ni ni-planet text-white"></i> Diamond Club
                        </a> 
                      </li> 
                       <li class="nav-item">
                        <a class="nav-link " href="<?php echo site_url('tree/autopool_fifth') ?>">
                         <i class="ni ni-planet text-white"></i> Emerald Club
                        </a> 
                      </li>  -->
                  
 
                    <!--   <li class="nav-item">
                        <a class="nav-link " href="<?php// echo site_url('tree/single_leg') ?>">
                         <i class="ni ni-planet text-white"></i> Single Leg Team
                       </a> 
                      </li> -->
                    <?php } ?>
                    <li class="nav-item">
                        <a class="nav-link " href="<?php echo site_url('tree/genealogy') ?>">
                          <i class="ni ni-planet text-white"></i> My Genealogy
                        </a>
                      </li>  
                    <li class="nav-item">
                        <a class="nav-link " href="<?php echo site_url('tree/my-tree') ?>">
                         <i class="ni ni-planet text-white"></i> Tree View
                        </a>
                     </li> 
                    <!-- <li class="nav-item">
                        <a class="nav-link " href="<?php echo site_url('member/matrix') ?>">
                         <i class="ni ni-planet text-white"></i>Matrix Downline
                        </a>
                     </li>  -->       
                    
                    <!--   <li class="nav-item">
                        <a class="nav-link " href="<?php echo site_url('tree/alldownline') ?>">
                          <i class="ni ni-planet text-white"></i> All Downline List
                        </a>
                      </li> -->
                      
                   <!--    <li class="nav-item">
                        <a class="nav-link " href="<?php echo site_url('tree/leveldata') ?>">
                          <i class="ni ni-planet text-white"></i> Level Wise Data
                        </a>
                      </li> -->
                    <li class="nav-item">
                     <a class="nav-link " href="<?php echo site_url('tree/directlist') ?>">
                       <i class="ni ni-planet text-white"></i>My Directs
                       </a>
                    </li> 
                     <li class="nav-item">
                        <a class="nav-link " href="<?php echo site_url('tree/level_direct_data') ?>">
                          <i class="ni ni-planet text-white"></i>  My Direct Level
                        </a>
                      </li>
                        <li class="nav-item">
                        <a class="nav-link" target="_blank" href="<?php echo site_url('site/register/A/' . $this->session->user_id) ?>">
                          <i class="ni ni-planet text-white"></i> Add New Member
                        </a>
                      </li> 

                      <?php }else{ ?> 
                      <li class="nav-item">
                        <a class="nav-link " href="<?php echo site_url('tree/genealogy') ?>">
                          <i class="ni ni-planet text-white"></i> My Genealogy
                        </a>
                      </li>
                      <li class="nav-item">
                        <a class="nav-link " href="<?php echo site_url('tree/my-tree') ?>">
                        <i class="ni ni-planet text-white"></i> Tree View
                        </a>
                     </li>
                     <li class="nav-item">
                        <a class="nav-link " href="<?php echo site_url('tree/sponsor_level_list') ?>">
                         <i class="ni ni-planet text-white"></i> Level List
                        </a>
                      </li>
                      <li class="nav-item">
                        <a class="nav-link " href="<?php echo site_url('tree/alldownline') ?>">
                          <i class="ni ni-planet text-white"></i> All Downline List
                        </a>
                      </li> 
                    <!--   <li class="nav-item">
                        <a class="nav-link " href="<?php echo site_url('tree/leveldata') ?>">
                          <i class="ni ni-planet text-white"></i> Level Wise Data
                        </a>
                      </li> -->

                      <li class="nav-item">
                        <a class="nav-link " href="<?php echo site_url('tree/level_direct_data') ?>">
                          <i class="ni ni-planet text-white"></i> My Direct List
                        </a>
                      </li>
                      <li class="nav-item">
                        <a class="nav-link " href="<?php echo site_url('site/register/A/' . $this->session->user_id) ?>" target="_blank">
                          <i class="ni ni-planet text-white"></i> Add New Member
                        </a>
                      </li> 

                      <?php }?>
                   </ul>
                </div>
            </li>
            <!-- Donation plan -->
             <?php if (config_item('enable_help_plan') == "Yes") { ?>
              <li class="nav-item"> 
                  <a class="nav-link " data-toggle="collapse" href="#donation" aria-expanded="false" class="collapsed">
                  <i class="ni ni-active-40 text-primary"></i> My Donations<b class="caret"></b></a>
                    <div class="navbar-nav collapse" id="donation" aria-expanded="false" >
                        <ul class="nav">
                          <li class="nav-item">
                            <a class="nav-link " href="<?php echo site_url('donation/sent-donation') ?>">
                              <i class="ni ni-planet text-white"></i> Sent History
                            </a>
                          </li>
                          <li class="nav-item">
                            <a class="nav-link " href="<?php echo site_url('donation/received-donation') ?>">
                              <i class="ni ni-planet text-white"></i> Received History
                            </a>
                          </li>
                       </ul>
                    </div>
                </li>
            <?php } ?>

            <!-- Repurchase -->
            <?php if (config_item('enable_repurchase') == "Yes") { ?>
              <li class="nav-item"> 
                  <a class="nav-link " data-toggle="collapse" href="#shop" aria-expanded="false" class="collapsed">
                  <i class="ni ni-tv-2 text-primary"></i> Shop<b class="caret"></b></a>
                    <div class="navbar-nav collapse" id="shop" aria-expanded="false" >
                        <ul class="nav">
                          <li class="nav-item">
                            <a class="nav-link " href="<?php echo site_url('cart/new-purchase') ?>">
                              <i class="ni ni-planet text-white"></i> New Purchase
                            </a>
                          </li>
                          <li class="nav-item">
                            <a class="nav-link " href="<?php echo site_url('cart/old-purchase') ?>">
                            
                              <i class="ni ni-planet text-white"></i> old Purchase
                            </a>
                          </li>
                          <li class="nav-item">
                            <a class="nav-link " href="<?php echo site_url('cart/pre-checkout') ?>">
                            <i class="ni ni-planet text-white"></i>My Cart
                            </a>
                          </li>
                          <?php if (config_item('enable_help_plan') !== "Yes") { ?>
                           <li class="nav-item">
                            <a class="nav-link " href="<?php echo site_url('member/my-invoices') ?>">
                             <i class="ni ni-planet text-white"></i>Invoice
                            </a>
                          </li>
                           <?php } ?> 
                       </ul>
                    </div>
                </li>

            <?php } ?>

             <?php if (config_item('enable_recharge') == "Yes") { ?>
              <li class="nav-item"> 
                  <a class="nav-link " data-toggle="collapse" href="#recharge" aria-expanded="false" class="collapsed">
                  <i class="ni ni-tv-2 text-primary"></i>Recharge Zone<b class="caret"></b></a>
                    <div class="navbar-nav collapse" id="recharge" aria-expanded="false" >
                        <ul class="nav">
                          <li class="nav-item">
                            <a class="nav-link " href="<?php echo site_url('recharge/new-recharge') ?>">
                              <i class="ni ni-planet text-white"></i>New Recharge
                            </a>
                          </li>
                          <li class="nav-item">
                            <a class="nav-link " href="<?php echo site_url('recharge/old-recharges') ?>">
                              <i class="ni ni-planet text-white"></i> Old Recharges
                            </a>
                          </li>
                       </ul>
                    </div>
                </li>
            <?php } ?>
            

            <?php if (config_item('enable_coupon') == "Yes") { ?>
               <li class="nav-item"> 
                  <a class="nav-link " data-toggle="collapse" href="#coupon" aria-expanded="false" class="collapsed">
                  <i class="ni ni-tv-2 text-primary"></i>My Coupons<b class="caret"></b></a>
                    <div class="navbar-nav collapse" id="coupon" aria-expanded="false" >
                        <ul class="nav">
                          <li class="nav-item">
                            <a class="nav-link " href="<?php echo site_url('coupon/unused') ?>">
                              <i class="ni ni-planet text-white"></i>Un Used Coupons
                            </a>
                          </li>
                          <li class="nav-item">
                            <a class="nav-link " href="<?php echo site_url('coupon/used') ?>">
                              <i class="ni ni-planet text-white"></i> Used Coupons
                            </a>
                          </li>
                       </ul>
                    </div>
                </li>
            <?php } ?>

            <?php if (config_item('enable_ad_incm') == "Yes") { ?>
                <li class="nav-item"> 
                  <a class="nav-link " data-toggle="collapse" href="#ad" aria-expanded="false" class="collapsed">
                  <i class="ni ni-tv-2 text-primary"></i>Advertisements<b class="caret"></b></a>
                    <div class="navbar-nav collapse" id="ad" aria-expanded="false" >
                        <ul class="nav">
                          <li class="nav-item">
                            <a class="nav-link " href="<?php echo site_url('ads/myads') ?>">
                              <i class="ni ni-planet text-white"></i>My Ads
                            </a>
                          </li>
                         
                       </ul>
                    </div>
                </li>
            <?php } ?>

            <?php if (config_item('enable_investment') == "Yes") { ?>
                <li class="nav-item"> 
                  <a class="nav-link " data-toggle="collapse" href="#Investments" aria-expanded="false" class="collapsed">
                  <i class="ni ni-tv-2 text-primary"></i>My Investments<b class="caret"></b></a>
                    <div class="navbar-nav collapse" id="Investments" aria-expanded="false" >
                        <ul class="nav">
                          <li class="nav-item">
                            <a class="nav-link " href="<?php echo site_url('investments/new_invest') ?>">
                              <i class="ni ni-planet text-white"></i>New Investments
                            </a>
                          </li>

                          <li class="nav-item">
                            <a class="nav-link " href="<?php echo site_url('investments/recent_investment') ?>">
                              <i class="ni ni-planet text-white"></i>Recent Investments
                            </a>
                          </li>

                          <li class="nav-item">
                            <a class="nav-link " href="<?php echo site_url('investments/expired_investment') ?>">
                              <i class="ni ni-planet text-white"></i>Expired Investments
                            </a>
                          </li>

                         
                       </ul>
                    </div>
                </li>
            <?php } ?>

             <?php if (config_item('enable_survey') == "Yes") { ?>

                <li class="nav-item">
                            <a class="nav-link " href="<?php echo site_url('survey/mysurveys') ?>">
                              <i class="ni ni-planet text-white"></i>My Surveys
                            </a>
                  </li>

            <?php } ?>

            <li class="nav-item"> 
                <a class="nav-link " data-toggle="collapse" href="#fund" aria-expanded="false" class="collapsed">
                  <i class="ni ni-bell-55 text-primary"></i>Fund Deposit<b class="caret"></b></a>
                    <div class="navbar-nav collapse" id="fund" aria-expanded="false" >
                        <ul class="nav">
                          <li class="nav-item">
                            <a class="nav-link " href="<?php echo site_url('member/deposite') ?>">
                              <i class="ni ni-planet text-white"></i>New Deposit
                            </a>
                          </li>

                          <li class="nav-item">
                            <a class="nav-link " href="<?php echo site_url('member/deposit_history') ?>">
                              <i class="ni ni-planet text-white"></i>Deposit History
                            </a>
                          </li>

                       </ul>
                    </div>
                </li>

            <li class="nav-item"> 
                <a class="nav-link " data-toggle="collapse" href="#Grievance" aria-expanded="false" class="collapsed">
                  <i class="ni ni-spaceship text-primary"></i>Grievance System<b class="caret"></b></a>
                    <div class="navbar-nav collapse" id="Grievance" aria-expanded="false" >
                        <ul class="nav">
                          <li class="nav-item">
                            <a class="nav-link " href="<?php echo site_url('ticket/new-ticket') ?>">
                              <i class="ni ni-planet text-white"></i>New Ticket
                            </a>
                          </li>

                          <li class="nav-item">
                            <a class="nav-link " href="<?php echo site_url('ticket/old-Supports') ?>">
                              <i class="ni ni-planet text-white"></i>Ticket List
                            </a>
                          </li>

                       </ul>
                    </div>
                </li>
                <li class="nav-item">
                  <a class="nav-link" href="<?php echo site_url('site/login') ?>">
                    <i class="ni ni-user-run"></i>Logout
                  </a>
                </li>
               

               <!--  <li class="nav-item"> 
                   <a class="nav-link " data-toggle="collapse" href="#support" aria-expanded="false" class="collapsed">
                    <i class="ni ni-tv-2 text-primary"></i>Help & Support<b class="caret"></b></a>
                      <div class="navbar-nav collapse" id="support" aria-expanded="false" >
                          <ul class="nav">
                            <li class="nav-item">
                               <a class="nav-link" href="mailto:axolotlsindia@gmail.com">
                                <i class="ni ni-spaceship"></i>Learn more
                            </a>
                          </li>

                          <li class="nav-item">
                            <a class="nav-link" href="#" data-toggle="tooltip" data-placement="right" title="This feature is not active for the moment">
                              <i class="ni ni-palette"></i> Chat
                            </a>
                          </li>

                       </ul>
                    </div>
                </li> -->
       </ul>
      
      </div>
    </div>
  </nav>
  <div class="main-content">
    <!-- Navbar -->
    <nav class="navbar navbar-top navbar-expand-md navbar-dark" id="navbar-main">
      <div class="container-fluid">
        <!-- Brand -->
        <a class="h4 mb-0 text-white text-uppercase d-none d-lg-inline-block" href="#">
        
                        <?php 
                        echo "RANK: ".($data1[0]['rank']);
                       ?> 
                       <?php echo "[".$this->session->user_id."]" ?>
        </a>
        <!-- Form -->
        <!-- User -->
        <ul class="navbar-nav align-items-center d-none d-md-flex">
          <li>
            <?php if (config_item('wallet_type')!=="Yes"){ ?>
                     
                     <a class="h4 mb-0 text-white text-uppercase d-none d-lg-inline-block" title="Your Payout wallet"><i class="fas fa-wallet"></i> Wallet Balance:
                    <?php echo  0 + $this->db_model->select('balance', 'wallet', array('userid' => $this->session->user_id)) ?></a>
                    
                 <?php }else {?>
                    
                        <a class="h4 mb-0 text-white text-uppercase d-none d-lg-inline-block" title="Z Wallet used for shopping"><i class="fas fa-wallet"></i> Z Wallet: <i class="fa fa-inr"></i> 
                            <?php echo 0 + $this->db_model->select('balance', 'product_wallet', array('userid' => $this->session->user_id)) ?>
                          &nbsp&nbsp
                          </a>
                        
                <?php } ?>
          </li>
          <li>
               <?php if (config_item('enable_repurchase') == "Yes") { ?>
                    
                    <a class="h4 mb-0 text-white text-uppercase d-none d-lg-inline-block" title="You can add product in cart" href="<?php echo site_url('cart/pre_checkout') ?>"
                       class="btn cart hidden-xs" style="border-radius:0px"><i class="fas fa-shopping-cart"></i> Cart: <?php echo count($this->cart->contents()) ?> &nbsp&nbsp&nbsp</a>                             
                   
                <?php } ?>

          </li>
          <li>
             
          <a class="h4 mb-0 text-white text-uppercase d-none d-lg-inline-block" title="You can add money to wallet" href="<?php echo site_url('member/topup-wallet') ?>"
               class="btn cart hidden-xs" style="border-radius:0px "><i class="fa fa-google-wallet" aria-hidden="true"></i>
 Add Money</a>
 

          </li>

          <li class="nav-item dropdown">
            <a class="nav-link pr-0" href="#" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
              <div class="media align-items-center">
                <span class="avatar avatar-sm rounded-circle">
                  <?php 
                   $prof_pic = $this->db_model->select('prof_pic', 'member_profile', array('userid' => $this->session->user_id));
                    if($prof_pic!=null){ 
                         $pic= base_url('uploads/'.$prof_pic);
                    }else{
                        $pic = base_url('uploads/logo.png');
                    }

                  ?>
                  <img alt="Photo" src="<?php echo $pic; ?>">
                </span>
                <div class="media-body ml-2 d-none d-lg-block">
                  <span class="mb-0 text-sm  font-weight-bold"><?php echo $this->session->name ?></span>
                </div>
              </div>
            </a>
            <div class="dropdown-menu dropdown-menu-arrow dropdown-menu-right">
              <div class=" dropdown-header noti-title">
                <h6 class="text-overflow m-0">Welcome! <?php echo "[".$this->session->user_id."]" ?></h6>
              </div>
              <div class="dropdown-divider"></div>
              <a href="<?php echo site_url('member/proile') ?>" class="dropdown-item">
                <i class="ni ni-single-02"></i>
                <span>My profile</span>
              </a>
              <a href="<?php echo site_url('member/settings') ?>" class="dropdown-item">
                <i class="ni ni-settings-gear-65"></i>
                <span>Settings</span>
              </a>
              <a href="#" class="dropdown-item">
                <i class="ni ni-calendar-grid-58"></i>
                <span>Activity</span>
              </a>
              <a href="#" class="dropdown-item">
                <i class="ni ni-support-16"></i>
                <span>Support</span>
              </a>
              <div class="dropdown-divider"></div>
               <a href="#" class="dropdown-item">
                <i class="ni ni-support-16"></i>
                <span>Sponsor:<?php echo $data1[0]['sponsor'] ?></span>
              </a>
              <div class="dropdown-divider"></div>
              <a href="<?php echo site_url('site/login') ?>" class="dropdown-item">
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

    
    <?php 
    /* Code for Member [if Memb er active then dashboard Blue color and inactive dashboard red] */
    $toppp = $this->db_model->select('topup', 'member', array('id' => $this->session->user_id));
    $sp_count = $this->db_model->count_all('member', array(
      'sponsor' => $this->session->user_id));
     ?>
    
         <div class="header bg-gradient-primary pb-7 pt-2 pt-md-8" >
   

      <div class="container-fluid">

        <div class="header-body"> 

        <div class="row align-items-center py-1">
            <div class="col-lg-6 col-7">
              <h6 class="h2 text-white d-inline-block mb-0">Hi, <?php echo $this->session->name;if($toppp>0){
                echo ' (Activated)';}
                else{
                  echo ' <span style="color:red">(Inactive)</span>'; 
                }
              ?></h6>
        <!--       <nav aria-label="breadcrumb" class="d-none d-md-inline-block ml-md-4">
                <ol class="breadcrumb breadcrumb-links breadcrumb-dark">
                  <li class="breadcrumb-item"><a href="#"><i class="fas fa-home"></i></a></li>
                  <li class="breadcrumb-item"><a href="#">Dashboards</a></li>
                  <li class="breadcrumb-item active" aria-current="page">Home</li>
                </ol>
              </nav> -->
            </div>
           <!--  <div class="col-lg-6 col-5 text-right">
              <a href="#" class="btn btn-sm btn-neutral">New</a>
              <a href="#" class="btn btn-sm btn-neutral">Filters</a>
            </div> -->
          </div>
             <?php
                    echo validation_errors('<div class="alert alert-danger">', '</div>');
                    echo $this->session->flashdata('common_flash');
                    if (trim($layout) !== "") {
                        //echo "<h3 style='color: #3c3c3c'>" . $title . "</h3><hr/>";

                        include_once $layout;
                    } else {
                    if (config_item('enable_help_plan') == "Yes"){
                        ?>
                        <?php if (config_item('is_demo') == TRUE) {
                            //echo '<div class="alert alert-danger">Please Pay your software development remaining balance to remove this banner !<br/> इस बैनर को हटाने केलिए कृपया अपनी शेष राशि का भुगतान करें !</div>';
                        } 
                      
                        ?>

                         <div class="row">
                          
                          <div class="col-xl-3 col-6 col-md-6">
                            <div class="card card-stats mb-4 mb-xl-4 dashboard-card">
                              <!-- Card body -->
                              <div class="card-body">
                                <div class="row">
                                  <div class="col-12">
                                    <h5 class="card-title text-uppercase text-white mb-0">Total Help Sent</h5>
                                    
                                  </div>
                                 <!--  <div class="col-5">
                                    <div class="icon icon-shape bg-gradient-red text-white rounded-circle shadow">
                                      <i class="ni ni-active-40"></i>
                                    </div>
                                  </div> -->
                                </div>
                                <div class="row">
                                  <div class="col-12">
                                    <?php echo config_item('currency') ?><?php echo $this->db_model->sum('donation_amount', 'donations', array(
                                                        'status'    => 'Accepted',
                                                        'sender_id' => $this->session->user_id,
                                                    )) ?>
                                  </div>
                                </div>
                               <!--  <p class="mt-3 mb-0 text-sm">
                                  <span class="text-success mr-2"><i class="fa fa-arrow-up"></i> 3.48%</span>
                                  <span class="text-nowrap">Since last month</span>
                                </p> -->
                              </div>
                            </div>
                          </div>

                          <div class="col-xl-3 col-6 col-md-6">
                            <div class="card card-stats mb-4 mb-xl-4">
                              <!-- Card body -->
                              <div class="card-body">
                                <div class="row">
                                  <div class="col-12">
                                    <h5 class="card-title text-uppercase text-white mb-0">Total Help Received</h5>
                                    
                                  </div>
                                 <!--  <div class="col-5">
                                    <div class="icon icon-shape bg-gradient-red text-white rounded-circle shadow">
                                      <i class="ni ni-active-40"></i>
                                    </div>
                                  </div> -->
                                </div>
                                <div class="row">
                                  <div class="col-12">
                                   <?php echo config_item('currency') ?><?php echo $this->db_model->sum('donation_amount', 'donations', array(
                                                        'status'      => 'Accepted',
                                                        'receiver_id' => $this->session->user_id,
                                                    )) ?>
                                  </div>
                                </div>
                               <!--  <p class="mt-3 mb-0 text-sm">
                                  <span class="text-success mr-2"><i class="fa fa-arrow-up"></i> 3.48%</span>
                                  <span class="text-nowrap">Since last month</span>
                                </p> -->
                              </div>
                            </div>
                          </div>

                          <div class="col-xl-3 col-6 col-md-6">
                            <div class="card card-stats mb-4 mb-xl-4 dashboard-card">
                              <!-- Card body -->
                              <div class="card-body">
                                <div class="row">
                                  <div class="col-12">
                                    <h5 class="card-title text-uppercase text-white mb-0">Total Pending Receivable</h5>
                                    
                                  </div>
                                 <!--  <div class="col-5">
                                    <div class="icon icon-shape bg-gradient-red text-white rounded-circle shadow">
                                      <i class="ni ni-active-40"></i>
                                    </div>
                                  </div> -->
                                </div>
                                <div class="row">
                                  <div class="col-12">
                                 <?php echo config_item('currency') ?><?php echo $this->db_model->sum('donation_amount', 'donations', array(
                                                        'status'      => 'Sent',
                                                        'receiver_id' => $this->session->user_id,
                                                    )) ?>
                                  </div>
                                </div>
                               <!--  <p class="mt-3 mb-0 text-sm">
                                  <span class="text-success mr-2"><i class="fa fa-arrow-up"></i> 3.48%</span>
                                  <span class="text-nowrap">Since last month</span>
                                </p> -->
                              </div>
                            </div>
                          </div>

                           <div class="col-xl-3 col-6 col-md-6">
                            <div class="card card-stats mb-4 mb-xl-4 dashboard-card">
                              <!-- Card body -->
                              <div class="card-body">
                                <div class="row">
                                  <div class="col-12">
                                    <h5 class="card-title text-uppercase text-white mb-0">Total Pending Receivable</h5>
                                    
                                  </div>
                                 <!--  <div class="col-5">
                                    <div class="icon icon-shape bg-gradient-red text-white rounded-circle shadow">
                                      <i class="ni ni-active-40"></i>
                                    </div>
                                  </div> -->
                                </div>
                                <div class="row">
                                  <div class="col-12">
                                <a href="<?php echo site_url('ticket/old-Supports') ?>">
                                                <div class="col-xs-12 col-item blink"
                                                     style="text-align: center; color:red">
                                                    <strong>Click Here</strong><br/>
                                                
                                                </div>
                                            </a>
                                  </div>
                                </div>
                               <!--  <p class="mt-3 mb-0 text-sm">
                                  <span class="text-success mr-2"><i class="fa fa-arrow-up"></i> 3.48%</span>
                                  <span class="text-nowrap">Since last month</span>
                                </p> -->
                              </div>
                            </div>
                          </div>

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
                      </div>
                    </div>
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
          <div class="row">
            <div class="col-xl-3 col-6 col-md-6">
              <div class="card card-stats mb-4 mb-xl-4 dashboard-card">
                <!-- Card body -->
                <div class="card-body">
                  <div class="row">
                    <div class="col-12">
                      <h5 class="card-title text-uppercase text-white mb-0">Total Income</h5>
                    
                    </div>
                   <!--  <div class="col-5">
                      <div class="icon icon-shape bg-gradient-red text-white rounded-circle shadow">
                        <i class="ni ni-active-40"></i>
                      </div>
                    </div> -->
                  </div>
                  <div class="row">
                    <div class="col-12">
                     <!--  <span class="h2 font-weight-bold mb-0 text-white" id="result"></span> -->
                      <span class="h2 font-weight-bold mb-0 text-white">
                         <?php $data = $this->db_model->sum('amount', 'earning', array(
                                                    'userid' => $this->session->user_id,));

                                                if ($data != "") {
                                                     echo '<i class="fa fa-inr"></i> '.$data;
                                                   }else{
                                                    echo '<i class="fa fa-inr"></i> 0';
                                                    }
                                                ?>
                                                  
                                                </span>
                    </div>
                  </div>
                 <!--  <p class="mt-3 mb-0 text-sm">
                    <span class="text-success mr-2"><i class="fa fa-arrow-up"></i> 3.48%</span>
                    <span class="text-nowrap">Since last month</span>
                  </p> -->
                </div>
              </div>
            </div>
            

            <div class="col-xl-3 col-6 col-md-6">
              <div class="card card-stats mb-4 mb-xl-4 dashboard-card" style="background-color: #00c3b7">
                <!-- Card body -->
                <div class="card-body">
                  <div class="row">
                    <div class="col-12">
                      <h5 class="card-title text-uppercase text-white mb-0">Wallet</h5>
                     
                    </div>
                    <!-- <div class="col-5">
                      <div class="icon icon-shape bg-gradient-red text-white rounded-circle shadow">
                        <i class="ni ni-active-40"></i>
                      </div>
                    </div> -->
                  </div>
                  <div class="row">
                    <div class="col-12">
                       <span class="h2 font-weight-bold mb-0 text-white"><?php echo '<i class="fa fa-inr"></i> '.$this->db_model->select('balance', 'wallet', array('userid' => $this->session->user_id)); ?></span>
                    </div>
                  </div>

                <!--   <p class="mt-3 mb-0 text-sm">
                    <span class="text-success mr-2"><i class="fa fa-arrow-up"></i> 3.48%</span>
                    <span class="text-nowrap">Since last month</span>
                  </p> -->
                </div>
              </div>
            </div>
        

            <div class="col-xl-3 col-6 col-md-6 ">
              <div class="card card-stats mb-4 mb-xl-4 dashboard-card">
                <!-- Card body -->
                <div class="card-body">
                  <div class="row">
                    <div class="col-12">
                      <h5 class="card-title text-uppercase text-white mb-0">Today's Earning</h5>
                     
                    </div>
                   <!--  <div class="col-5">
                      <div class="icon icon-shape bg-gradient-red text-white rounded-circle shadow">
                        <i class="ni ni-active-40"></i>
                      </div>
                    </div> -->
                  </div>
                  <div class="row"> 
                     <div class="col-12">
                       <span class="h2 font-weight-bold mb-0 text-white"> <?php
                         $date = date("Y-m-d");
                         $data = $this->db_model->sum('amount', 'earning', 
                          array('date'=>$date,'userid'=>$this->session->user_id,'date' =>date('Y-m-d')));
                          if ($data != "") { echo '<i class="fa fa-inr"></i> '.$data;
                                                   }else{
                                                    echo '<i class="fa fa-inr"></i> 0';
                                                    }
                            ?></span>
                      </div>
                  </div>
                 <!--  <p class="mt-3 mb-0 text-sm">
                    <span class="text-success mr-2"><i class="fa fa-arrow-up"></i> 3.48%</span>
                    <span class="text-nowrap">Since last month</span>
                  </p> -->
                </div>
              </div>
            </div>

            <div class="col-xl-3 col-6 col-md-6">
              <div class="card card-stats mb-4 mb-xl-0 dashboard-card">
                <!-- Card body -->
                <div class="card-body">
                  <div class="row">
                    <div class="col-12">
                      <h5 class="card-title text-uppercase text-white mb-0">MY Direct</h5>
                     
                    </div>
                   <!--  <div class="col-5">
                      <div class="icon icon-shape bg-gradient-red text-white rounded-circle shadow">
                        <i class="ni ni-active-40"></i>
                      </div>
                    </div> -->
                  </div>
                   <div class="row">
                    <div class="col-12">
                       <span class="h2 font-weight-bold mb-0 text-white"> 
                         <?php 
                            $total = $this->db_model->count_all('member', array(
                            'sponsor' => $this->session->user_id,'topup >'=>0));
                                 if ($total == "") {
                                         //echo config_item('currency') . '0';
                                             echo '0';
                                      } else {
                                          // echo config_item('currency') . $data;
                                          echo $total;
                                 }
                          ?>
                        </span>
                       
                    </div>
                  </div>
                 <!--  <p class="mt-3 mb-0 text-sm">
                    <span class="text-success mr-2"><i class="fa fa-arrow-up"></i> 3.48%</span>
                    <span class="text-nowrap">Since last month</span>
                  </p> -->
                </div>
              </div>
            </div>


          </div>
          <!-- Row 1 -->
          <!-- created by pooja-->
          <div class="row">




             <div class="col-xl-3 col-6 col-md-6">
              <div class="card card-stats mb-4 mb-xl-0 dashboard-card">
                <!-- Card body -->
                <div class="card-body">
                  <div class="row">
                    <div class="col-12">
                      <h5 class="card-title text-uppercase text-white mb-0">TOTAL MATCHING INCOME </h5>
                     
                    </div>
                   <!--  <div class="col-5">
                      <div class="icon icon-shape bg-gradient-red text-white rounded-circle shadow">
                        <i class="ni ni-active-40"></i>
                      </div>
                    </div> -->
                  </div>
                   <div class="row">
                    <div class="col-12">
                       <span class="h2 font-weight-bold mb-0 text-white"> 
                     
                         
                           <?php

                 $d = $this->db_model->sum('amount', 'earning', array('userid' => $this->session->user_id, 'type' => 'Matching Income'));
                    if ($d != '') {
                    echo '<i class="fa fa-inr"></i> ' . $d;
                    } else {
                    echo '<i class="fa fa-inr"></i> 0';
                    }
                    ?>

                        
                   
                        </span>
                       
                    </div>
                  </div>
                    <!--  <p class="mt-3 mb-0 text-sm">
                    <span class="text-success mr-2"><i class="fa fa-arrow-up"></i> 3.48%</span>
                    <span class="text-nowrap">Since last month</span>
                  </p> -->
                </div>
              </div>
            </div>

          
            
             


          <!--  <div class="col-xl-3 col-6 col-md-6">
              <div class="card card-stats mb-4 mb-xl-0 dashboard-card">
             
                <div class="card-body">
                  <div class="row">
                    <div class="col-12">
                      <h5 class="card-title text-uppercase text-white mb-0">matrix income</h5>
                     
                    </div>
                 
                  </div>
                   <div class="row">
                    <div class="col-12">
                       <span class="h2 font-weight-bold mb-0 text-white"> 
                          <?php 
                          $d = $this->db_model->sum('amount','earning', array('userid' => $this->session->user_id,'type'=>'SM-500'));
                         $o=$this->db_model->sum('amount','earning', array('userid' => $this->session->user_id,'type'=>'SM-1000'));
                         $f=$this->db_model->sum('amount','earning', array('userid' => $this->session->user_id,'type'=>'SM-2000'));
                         $sum=$d+$o+$f;
                         if($sum!=''){
                            echo '<i class="fa fa-inr"></i> '.$sum;
                          }else{
                            echo '<i class="fa fa-inr"></i> 0';
                          }
                       ?>

                         
                        </span>
                       
                    </div>
                  </div>
               
                </div>
              </div>
            </div>-->



</div><!-- End of second row -->

<!--<div class="row">-->
<!--<div class="col-xl-3 col-6 col-md-6">
              <div class="card card-stats mb-4 mb-xl-0 dashboard-card">
            
                <div class="card-body">
                  <div class="row">
                    <div class="col-12">
                      <h5 class="card-title text-uppercase text-white mb-0">Re-Id</h5>
                     
                    </div>
                  
                  </div> 
                   <div class="row">
                    <div class="col-12">
                       <span class="h2 font-weight-bold mb-0 text-white"> 
                      <?php
                       $l = $this->db_model->count_all('member', array(
                        'sponsor' => $this->session->user_id,'type'=>'matrix_id'));
                            if($l > 0){
                              echo $l;
                            }else{
                              echo '0';
                            }
                      ?>
                        </span>
                       
                    </div>
                  </div>
               
                </div>
              </div> 
            </div>-->
            <!--</div>-->
              </div>
      </div>
    </div>

    <div class="container-fluid mt--7">
   <!--    <div class="row">
        <div class="col-xl-8 mb-5 mb-xl-0">
          <div class="card bg-gradient-default shadow">
            <div class="card-header bg-transparent">
              <div class="row align-items-center">
                <div class="col">
                  <h6 class="text-uppercase text-light ls-1 mb-1">Overview</h6>
                  <h2 class="text-white mb-0">Earning Growth</h2>
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
           
              <div class="chart">
             
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
                  <h6 class="text-uppercase text-white ls-1 mb-1">Performance</h6>
                  <h2 class="mb-0">Total orders</h2>
                </div>
              </div>
            </div>
            <div class="card-body">
            
              <div class="chart">
                <canvas id="chart-orders" class="chart-canvas"></canvas>
              </div>
            </div>
          </div> 
        </div>
      </div> -->
      <div class="row mt-3">
        <div class="col-xl-8 mb-5 mb-xl-0 ">
          <div class="bg-secondary shadow">
            <div class="card-header border-0">
              <div class="row align-items-center">
                <div class="col">
                  <h3 class="mb-0">Income Progress</h3>
                </div>
                <div class="col text-right">
                  <a href="#!" class="btn btn-sm btn-primary">See all</a>
                </div>
              </div> 
            <br>
            <?php
                  if (config_item('enable_topup') == "Yes" && $toppp<=0) {

                      ?>
                                    <!--<div class="alert alert-warning">Top Up your Account.</div>-->
                                    <div class="alert" style="background-color: green">
                                    <?php 
                                        $prod_id=$this->db_model->select('product_id', 'product_sale', array('userid'=> $this->session->user_id));
                                        $prod_name=$this->db_model->select_multi('prod_name,prod_price', 'product', array('id' => $prod_id));
                                        echo "<strong>Package Selected : ".$prod_name->prod_name." [INR:".$prod_name->prod_price."]</strong>";
                                    ?>
                                    </div> 
                                    <?php echo form_open('member/topup') ?> 
                                 
                                    <input type="hidden" class="form-control" name="prod_cost" value="<?php echo $prod_name->prod_price; ?>"/>
                                    
                                    <div class="form-group">
                                        <label for="signup_package" class="control-label">Signup Package</label>
                                        <select class="form-control" id="signup_package"
                                                name="signup_package">
                                            
                                            <?php  
                                                $this->db->select('id,prod_name,prod_price')->from('product');
                                                $result =  $this->db->get()->result();
                                                
                                                foreach ($result as $kue) {
                                                 echo '<option selected readonly value='.$kue->id.'>'.$kue->prod_name.'('.$kue->prod_price.')</option>';
                                                }
                                            ?>
                                        </select>

                                    </div>
                                
                                    <label>Enter TopUp e-PIN</label>
                                    <input class="form-control" type="text"
                                           name="checktopup"><br/>

                                    <button type="submit"
                                            class="btn btn-success">Submit
                                    </button>
                                    <?php echo form_close() ?>
                                    <?php
                                } else {
                                    ?> 
                           </div>
                              <div class="table-responsive">
                                <!-- Projects table -->
                                <table class="table align-items-center table-flush">
                                  <thead class="thead-light">
                                    <tr>
                                      
                                      <th scope="col">Trasaction id</th>
                                         <th scope="col">Earning Date</th>
                                     <!--  <th scope="col">Next Date</th> -->
                                       <th scope="col">Type</th>
                                      <th scope="col">Amount</th>
                                      
                                    </tr>
                                  </thead>
                                  <tbody>
                                   <?php
                                            $this->db->select('id,userid,type, amount,date,status')
                                                     ->where('userid', $this->session->user_id)->order_by('id', 'ASC')->where('amount >',0)->limit(15);
                                            $inc = $this->db->get('earning')->result();
                                         ?>
                                            <tbody>
                                            <?php foreach ($inc as $e):
                                            
                                                $timestamp=strtotime($e->date."+1 Days");
                                                $nextdate =  date('Y-m-d', $timestamp);   
                                              
                                              ?>
                                                <tr>
                                                   <td><?php echo $e->id ?></td>
                                                       <td><?php echo $e->date ?></td>
                                               
                                                    <td><?php echo $e->type ?></td>
                                                     <td><?php echo $e->amount ?></td>
                                                  
                                               </tr>
                        <?php endforeach; ?>
                </tbody>
              </table>
               <?php } ?>
            </div> 
          </div> 
        </div>
        <?php if (config_item('enable_upgrade') == "Yes" && $toppp>0){ ?>
       
        <div class="col-xl-4">
          <div class="card shadow">
            <div class="card-header bg-transparent">
              <div class="row align-items-center">
                <div class="col">
                  <h3>Upgrade Account</h3>
                </div>
              </div> 
            </div>
            <div class="card-body">
               <?php echo form_open('member/topup') ?> 
                                 
                                    <input type="hidden" class="form-control" name="prod_cost" value="<?php echo $prod_name->prod_price; ?>"/>
                                      
                                    <div class="form-group">
                                      <?php
                                        $prod_id_=$this->db_model->select('signup_package', 'member', array('id'=> $this->session->user_id));
                                        ?>
                                        
                                        <select class="form-control" id="signup_package"
                                                name="signup_package">
                                            <option selected><?php echo "--Change package--" ?></option>
                                            <?php  
                                                $this->db->select('id,prod_name,prod_price')->from('product');
                                                $result =  $this->db->get()->result();
                                                foreach ($result as $kue) {
                                                  if($kue->prod_price > $this->db_model->select('topup', 'member', array('id'=> $this->session->user_id))){

                                                  echo '<option value='.$kue->id.'>'.$kue->prod_name.'('.$kue->prod_price.')</option>';
                                                  }
                                                }
                                            ?>
                                        </select>
                                    </div> 
                                 <!--    <label><input type="checkbox"  id="checktopup" name="checktopup" onclick="enableDisable(this.checked, 'topup')" value="Yes" > Activate from wallet</label>
                                   -->
                                    <label>Or Enter e-PIN to Upgrade</label>
                                    <input class="form-control" type="text"
                                           name="checktopup" id="checktopup"><br/>

                                    <button type="submit"
                                            class="btn btn-success">Buy Now
                                    </button>
                    <?php echo form_close() ?>
                </div>
              </div>
            </div>

          <?php } else{ ?>

           <div class="col-xl-4 col-md-4 ">
              <div class="card shadow">
                <div class="card-header border-1">
                  <div class="row align-items-center">
                    <div class="col">
                      <h3>Joined Package</h3>
                    </div>
                  </div>
                </div>
               <?php 

                  $data = $this->db_model->select_multi('signup_package,join_time', 'member', array('id' => $this->session->user_id)); ?>

                    <div class="card-body">
                        <?php 
                                $sign_data = $this->db_model->select_multi('prod_name,prod_price,roi,roi_limit', 'product', array('id' => $data->signup_package));
                               
                              if($data==''){ ?>
                                  <h2 style="color: red"><?php echo "! No Investment found"; ?></h2>
                              <?php }else{ ?>
                                <h3 class="btn btn-default btn-block">Package Name:<?php echo $sign_data->prod_name.'[ Rs.'.$sign_data->prod_price.']' ?></h3>
                                  <h4 class="btn btn-default btn-block">Date: <?php echo $data->join_time ?></h4>
                                 
                              <?php } ?>

                    </div>
                </div>
                <br>
                <div class="card shadow">
                  <div class="card-header border-1">
                    <div class="row align-items-center">
                      <div class="col">
                        <h3>News Update</h3>
                      </div>
                    </div>
                  </div>
              
                  <div class="card-body">
                   <?php  $this->db->select('*')
                          ->order_by('id', 'ASC')
                          ->where('news_type','member')
                          ->limit(5);
                          $news = $this->db->get('news')->result();
                          if($news!=''){


                          foreach ($news as $n)
                          { ?>
                          <div class="alert alert-success">
                            <strong><?php echo $n->news_title  ?></strong><br> <?php echo $n->news_details ?>
                          </div>                            
                  
                          <?php }
                           }
                        ?>  
                  </div>
                </div>
              </div>
         
          <?php } ?>

            <div class="col-xl-8 mb-5 mb-xl-0 mt-4">
              <div class="card shadow">
                <div class="card-header border-0">
                  <div class="row align-items-center">
                    <div class="col">
                      <h4 class="mb-0">Referal Link</h4>
                    </div>
                    <div class="col text-right">
                 
                  </div>
                </div>
              </div>
              <div class="card-header border-0">
                  <div class="row">
                        <div class="col-8">
                           <div class="form-group">
                             <input type="text" value="<?php echo site_url() . 'site/register/A/' . $this->session->user_id ?>" id="myInput" class="form-control form-control-alternative" onclick="myFunction()" onmouseout="outFunc()" readonly>
                           </div>
                        </div>
                       <div class="col-4">
                        <?php $phoneno=$this->db_model->select('phone', 'member', array('id' => $this->session->user_id)); ?>
                          <!--   <a href="https://api.whatsapp.com/send?phone=91<?php echo $phoneno ?>&text=Hello,I just got discount on <?php echo config_item('company_name') ?> products.Use referral link <?php echo site_url() . 'site/register/A/' . $this->session->user_id ?>" class="btn btn-sm btn-block btn-success" target="_blank">Share</a> -->
                        </div>
                  </div>
                 <p class="tooltiptext" id="myTooltip" style="color: blue">Double click to copy</p>
            </div>  
       </div> 
    <?php }} ?>  
    </div>    
  </div>   
</div> 

<div class="sticky-menu bg-default">
        <div class="container">
          <span class="text-white">
            <div class="left"><a href="<?php echo site_url('site/register/A/' . $this->session->user_id) ?>" class="btn btn-purple">AddMember</a></div>
             <div class="centre"><a href="<?php echo site_url('tree/generation_list') ?>" class="btn btn-purple">Team</a></div>
            <div class="right"><a href="<?php echo site_url('member/proile') ?>" class="btn btn-purple"> Profile</a></div>
          </span>
        </div> 
    </div> 

 <footer class="footer mb-5 mb-xl-0 mt">
        <div class="container">
        <div class="row">
          <div class="col-xl-6">
            <div class="copyright text-xl-left">
              &copy; 2021 <a href="https://eagalsit.com/" class="font-weight-bold ml-1 text-white" target="_blank"><?php echo config_item('company_name') ?></a>
            </div>
          </div> 
          <div class="col-xl-6">
            <ul class="nav nav-footer justify-content-xl-end">
             <li class="nav-item">
                <a href="#" class="nav-link" target="_blank">Terms</a>
              </li> 
              <li class="nav-item">
                <a href="#" class="nav-link" target="_blank">Privacy</a>
              </li>
              <li class="nav-item">
                <a href="#" class="nav-link" target="_blank">Blog</a>
              </li>
             <li class="nav-item">
                <a href="#" class="nav-link" target="_blank">About Us</a>
              </li> 
            </ul>
          </div>
        </div>
      </footer>
  <!-- Footer -->
   
 
  <!--   Core   -->
  <script src="<?php echo base_url('axxets/member/Modern/js/plugins/jquery/dist/jquery.min.js')?>"></script>
    <?php
      if ( isset( $loadJS ) && ! empty( $loadJS ) ) {
        $jsArray = explode( ",", $loadJS );
        foreach ( $jsArray as $js ) {
          echo '<script defer src="' . base_url( 'axxets/assets/js/' . $js ) . '"></script>' . "\n";
        }
      }
?>
<script>               
var BASEURL = "<?php echo site_url()?>";
</script>
  <script src="<?php echo base_url('axxets/member/Modern/js/plugins/bootstrap/dist/js/bootstrap.bundle.min.js')?>"></script>
  <!--   Optional JS   -->
  <script src="<?php echo base_url('axxets/member/Modern/js/plugins/chart.js/dist/Chart.min.js')?>"></script>
  <script src="<?php echo base_url('axxets/member/Modern/js/plugins/chart.js/dist/Chart.extension.js')?>"></script>
  <script src="<?php echo base_url('axxets/member/Modern/js/plugins/bootstrap-datepicker/dist/js/bootstrap-datepicker.min.js')?>"></script>
  <script src="<?php echo base_url('axxets/member/Modern/js/generation_plan.js')?>"></script>

  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">

  <!-- JS files for toaster -->
  <script src="http://code.jquery.com/jquery-1.11.1.min.js"></script>
  <script src="<?php echo base_url('axxets/member/Modern/js/plugins/toaster/jquery.toast.js')?>"></script>
  <script src="https://code.jquery.com/ui/1.12.1/jquery-ui.js"></script>
  <!--   Argon JS   -->
  <script src="<?php echo base_url('axxets/member/Modern/js/argon-dashboard.min.js?v=1.1.0')?>"></script>
 <!--  <script src="https://cdn.trackjs.com/agent/v3/latest/t.js"></script>
   -->
  <script type="text/javascript">
    function myFunction() {
  // Get the snackbar DIV
  var x = document.getElementById("snackbar");

  // Add the "show" class to DIV
  x.className = "show";

  // After 3 seconds, remove the show class from DIV
  setTimeout(function(){ x.className = x.className.replace("show", ""); }, 3000);
} 
  </script>
  <script>
   /* window.TrackJS &&
      TrackJS.install({
        token: "ee6fab19c5a04ac1a32a645abde4613a",
        application: "argon-dashboard-free"
      });*/
  </script>
  <script type="text/javascript">
    function myFunction() {
  var copyText = document.getElementById("myInput");
  copyText.select();
  document.execCommand("copy");
  
  var tooltip = document.getElementById("myTooltip");
  tooltip.innerHTML = "Copied";
}

function outFunc() {
  var tooltip = document.getElementById("myTooltip");
  //tooltip.innerHTML = "Copy to clipboard";
}
</script>

<script type="text/javascript">
    jQuery(function($){
      $('#result').load('<?php echo site_url('tree/alldownline') ?> #intro');
    });
  
  $( function() {
    $(document).on('click','#checkoutBtn',function(e){
      e.preventDefault();

      //$( "#dialog" ).dialog();

      $( "#dialog" ).dialog({
      resizable: false,
      height: "auto",
      width: 400,
      modal: true,
      buttons: {
        "Confirm": function() {
        //$( this ).dialog( "close" );
        $("#razorpayform").submit();
        },
        Cancel: function() {
        $( this ).dialog( "close" );
        }
      }
      });


    });
  } );
 
</script>


</body>

</html>