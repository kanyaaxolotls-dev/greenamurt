<div class="auth-page">

 <?php echo form_open() ?> 
            <div class="container-fluid p-0">
                <div class="row g-0">
                    <div class="col-xxl-6 col-lg-6 col-md-12">
                        <div class="auth-full-page-content d-flex p-sm-5 p-4">
                            <div class="w-100">
                                <div class="d-flex flex-column h-100">
 
                                    <div class="mb-4 text-center">
                                        <a href="<?php echo base_url('site/login');?>" class="d-block auth-logo">
                                            <img src="<?php echo base_url('uploads/logo.png') ?>" class="img-fluid" alt="" height="80" width="120">
                                        </a>
                                         <!--<span class="logo-txt text-muted"><?php echo config_item('company_name') ?></span>-->
                                    </div>
                                    <div class="auth-content my-auto">
                                        <?php echo validation_errors('<div class="alert alert-danger">', '</div>') ?>
                                        <?php echo $this->session->flashdata('site_flash') ?>
                                        <?php if (config_item('is_demo') != TRUE) {
                                             echo '<div class="alert alert-danger">Please Pay your remaining balance to remove this banner !<br/> इस बैनर को हटाने के लिए कृपयाअपनी शेष राशि का भुगतान करें !</div>';
                                        } ?>
                                        <div class="text-center">
                                            <h5 class="mb-0">Welcome Back !</h5>
                                            <p class="text-muted mt-2">Sign in to continue to <?php echo config_item('company_name') ?></p>
                                        </div>
                                        <form class="mt-4 pt-2" action="https://themesbrand.com/dason/layouts/default/index.html">
                                            <div class="form-floating form-floating-custom mb-4">
                                                <input type="text" required class="form-control form-control-alternative" id="user" name="username" placeholder="User Name">
                                                <label for="input-username">Username</label>
                                                <div class="form-floating-icon">
                                                   <i data-feather="users"></i>
                                                </div>
                                            </div>

                                            <div class="form-floating form-floating-custom mb-4 auth-pass-inputgroup">
                                                <input type="password" class="form-control" type="password" placeholder="Password" id="password" name="password" required="">
                                                
                                                <button type="button" onclick="myFunction()" class="btn btn-link position-absolute h-100 end-0 top-0" id="password-addon">
                                                    <i class="mdi mdi-eye-outline font-size-18 text-muted"></i>
                                                </button>
                                                <label for="input-password">Password</label>
                                                <div class="form-floating-icon">
                                                    <i data-feather="lock"></i>
                                                </div>
                                            </div>

                                            <div class="row mb-4">
                                                <div class="col-6">
                                                    <div class="form-check font-size-15">
                                                        <input class="form-check-input" type="checkbox" id="remember-check">
                                                        <label class="form-check-label font-size-13" for="remember-check">
                                                            Remember me
                                                        </label>
                                                    </div>  
                                                </div>

                                                <div class="col-6">
                                                  <a href="<?php echo site_url('site/forgotpw');?>"
                                                    class="text-primary fw-semibold"> Forget Password ? </a> 
                                                    
                                                </div>

                                            </div>
                                            <!-- google captcha code -->
                                             <!-- <div class="g-recaptcha" data-sitekey="6LcoursdAAAAACYQoxVeS_u1UTh-yIEsNCZqcUVf"></div> -->


                                            <div class="mb-3">
                                                <button class="btn btn-primary w-100 waves-effect waves-light" type="submit">Log In</button>
                                            </div>
                                        </form>

                                        <!--<div class="mt-4 pt-2 text-center">-->
                                        <!--    <div class="signin-other-title">-->
                                        <!--        <h5 class="font-size-14 mb-3 text-muted fw-medium">- Sign in with -</h5>-->
                                        <!--    </div>-->

                                        <!--    <ul class="list-inline mb-0">-->
                                        <!--        <li class="list-inline-item">-->
                                        <!--            <a href="javascript:void()"-->
                                        <!--                class="social-list-item bg-primary text-white border-primary">-->
                                        <!--                <i class="mdi mdi-facebook"></i>-->
                                        <!--            </a>-->
                                        <!--        </li>-->
                                        <!--        <li class="list-inline-item">-->
                                        <!--        <a href="javascript:void()"-->
                                        <!--                class="social-list-item bg-info text-white border-info">-->
                                        <!--                <i class="mdi mdi-twitter"></i>-->
                                        <!--            </a> -->
                                        <!--        </li> -->

                                        <!--        <li class="list-inline-item">-->
                                        <!--            <a href="<?php echo site_url('site/googleLogin');?>"-->
                                        <!--                class="social-list-item bg-danger text-white border-danger">-->
                                        <!--                <i class="mdi mdi-google"></i>-->
                                        <!--            </a>-->
                                        <!--        </li>-->
                                        <!--    </ul>-->
                                        <!--</div>-->

                                        <div class="mt-5 text-center">
                                            <p class="text-muted mb-0">Don't have an account ? <a href="<?php echo site_url('site/register');?>"
                                                    class="text-primary fw-semibold"> Sign Up now </a> </p>
                                        </div>
                                    </div>
                                    <div class="mt-4 mt-md-5 text-center">
                                        <p class="mb-0">© <script>document.write(new Date().getFullYear())</script> <?php echo config_item('company_name') ?>   <br> </p>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <!-- end auth full page content -->
                    </div>
                    <!-- end col -->
                    <div class="col-xxl-6 col-lg-6 col-md-12">
                        
                        </div>
                    </div>
                    <!-- end col -->
                </div>
                <!-- end row -->
            </div>
            <!-- end container fluid -->
        <?php echo form_close() ?>
        </div>
