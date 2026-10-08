<?php
    include ('head.php');
?>
            <!-- Body Content -->
            <div id="page-content">
                <!-- Page Title -->
                <div class="page section-header text-center mb-0">
                    <div class="page-title">
                        <div class="wrapper"><h1 class="page-width">Login</h1></div>
                    </div>
                </div>
                <!-- End Page Title -->
                <!-- Breadcrumbs -->
                <div class="bredcrumbWrap bredcrumbWrapPage bredcrumb-style2 text-center">
                    <div class="container breadcrumbs">
                        <a href="" title="Back to the home page">Home</a><span aria-hidden="true">|</span><span class="title-bold">Login</span>
                    </div>
                </div>
                <!-- End Breadcrumbs -->
                <?php
                    $sesss_otp = $this->session->userdata('otp_data');
                    if($sesss_otp == NULL){
                       $action = 'send_otp';
                       $stat   = 'required';
                    }
                    else{
                        $action = 'login';
                        $stat   = 'readonly';
                    }
                ?>
                <div class="container">
                    <div class="row">
                        <!-- Main Content -->
                        <div class="col-12 col-sm-12 col-md-12 col-lg-12 box">
                            <div class="mb-4">
                                <!--<form method="post" action="<?php echo base_url('home/'.$action); ?>">-->
                                <form method="post" action="<?php echo base_url('home/login'); ?>">
                                    <h3 class="text-center">Registered Customers</h3>
                                        <?php
                                            echo validation_errors('<div class="alert alert-danger">', '</div>');
                                            echo $this->session->flashdata('common_flash');
                                        ?>
                                        <div class="row">
                                            <div class="col-12">
                                                <div class="form-group">
                                                    <label>Userid <span class="text-danger">*</span></label>
                                                    <input type="text" name="username" placeholder="" value="<?php echo $sesss_otp['phone'] ?>" <?php echo $stat ?>>                        	
                                                </div>
                                            </div>
                                            <!--<div class="col-4" style="margin-top:2.3em">-->
                                            <!--    <div class="form-group">-->
                                            <!--        <?php if($sesss_otp == NULL){ ?>-->
                                            <!--        <input type="submit" class="bg-dark text-white" value="Send OTP">-->
                                            <!--        <?php }else{ ?>-->
                                            <!--        <a class="bg-success p-2 btn w-100 text-white">Otp Sent</a>-->
                                            <!--        <?php } ?>-->
                                            <!--    </div>    -->
                                            <!--</div>-->
                                        </div>
                                    <div class="row">
                                        <div class="col-12 col-sm-12 col-md-12 col-lg-12">
                                            <div class="form-group">
                                                <label>Password <span class="text-danger">*</span></label>
                                                <input type="password" name="password" placeholder=""/>
                                                <!--<input type="hidden" name="gen_otp" value="<?php echo $sesss_otp['otp'] ?>" />-->
                                            </div>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="text-left col-12">
                                            <input type="submit" class="btn mb-3" value="LogIn">
                                            <a class="btn mb-3 bg-success" href="<?php echo base_url('site/register'); ?>">Register</a>
                                        </div>
                                    </div>
                                </form>
                            </div>
                        </div>
                        <!-- End Main Content -->
                    </div>
                </div>
            </div>
            <!-- End Body Content -->

  <?php
    include ('footer.php');
?>

