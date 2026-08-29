    <div class="auth-page">
            <div class="container-fluid p-0">
                <div class="row g-0">
                    <div class="col-xxl-6 col-lg-6 col-md-6">
                        <div class="auth-full-page-content d-flex p-sm-5 p-4">
                            <div class="w-100">
                                <div class="d-flex flex-column h-100">
                                     <div class="mb-4 mb-md-5 text-center">
                                        <a href="<?php echo base_url('site/login');?>" class="d-block auth-logo">
                                            <img src="<?php echo base_url('uploads/logo.png') ?>" class="img-fluid" alt="" height="80" width="120">
                                        </a>
                                         <span class="logo-txt text-muted"><?php echo config_item('company_name') ?></span>
                                    </div>
                                    <div class="auth-content my-auto">
                                        <div class="text-center">
                                            <h5 class="mb-0">Reset Password</h5>
                                          
                                        </div>
                                       
                                       <?php echo validation_errors('<div class="alert alert-danger">', '</div>') ?>
                                        <?php echo $this->session->flashdata('site_flash') ?>

                                         <!--<form class="mt-4" method="POST" action="<?php echo base_url('site/get_otp')?>">-->
                                         <!-- <div class="row">-->
                                         <!--   <div class="col-md-8">-->
                                         <!--     <label for="user">Enter Mobile number/Userid</label>-->
                                         <!--     <input type="text" id="username" class="form-control" value="<?php echo $_SESSION["_user_id_"]; ?>" name="username">-->
                                         <!--   </div>-->
                                         <!--   <div class="col-md-4">-->
                                         <!--     <button type="submit" class="btn btn-warning mt-4">Get OTP </button>-->
                                         <!--   </div>-->
                                         <!-- </div>-->
                                         <!-- </form>-->

                                        <form class="mt-4" method="POST" action="<?php echo base_url('site/send_details')?>">
                                        <div class="form-group col-sm-12">
                                            <label for="user">Enter Mobile number / Userid</label>
                                            <input type="text" id="phone" class="form-control" name="phone" placeholder="Enter mobile number / Userid">
                                            <!--<label for="user" class="control-label">Enter OTP (Valid for 15 Min)</label>-->
                                            <!--<input type="text" class="form-control" id="otp" name="otp">-->
                                            <!--<label for="user" class="control-label">Enter New Password</label>-->
                                            <!--<input type="password" class="form-control" id="password" name="password">-->
                                            <!-- <label for="user" class="control-label">Re-Enter New Password</label>-->
                                            <!--<input type="password" class="form-control" id="cpassword" name="cpassword">-->
                                        </div>
                                            <div class="mb-3 mt-4">
                                                <button class="btn btn-primary w-100 waves-effect waves-light" type="submit">Reset</button>
                                            </div>
                                      </form>
                                        
                                        <div class="mt-5 text-center">
                                            <p class="text-muted mb-0">Remember It ?  <a href="<?php echo site_url('site/login') ?>" class="text-primary fw-semibold"> Sign In </a> </p>
                                        </div>
                                    </div>
                                    <div class="mt-4 mt-md-5 text-center">
                                        <p class="mb-0">© <script>document.write(new Date().getFullYear())</script> <?php echo config_item('company_name') ?>   <br> Crafted with <i class="mdi mdi-heart text-danger"></i> <?php echo config_item('company_name') ?></p>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <!-- end auth full page content -->
                    </div>
                    <!-- end col -->
                    <div class="col-xxl-6 col-lg-6 col-md-6">
                        <div class="auth-bg pt-md-5 p-4 d-flex">
                            <div class="bg-overlay"></div>
                            <ul class="bg-bubbles">
                                <li></li>
                                <li></li>
                                <li></li>
                                <li></li>
                                <li></li>
                                <li></li>
                                <li></li>
                                <li></li>
                                <li></li>
                                <li></li>
                            </ul>
                            <!-- end bubble effect -->
                            <div class="row justify-content-center align-items-end">
                                <div class="col-xl-7">
                                    <div class="p-0 p-sm-4 px-xl-0">
                                        <div id="reviewcarouselIndicators" class="carousel slide" data-bs-ride="carousel">
                                            <div class="carousel-indicators auth-carousel carousel-indicators-rounded justify-content-center mb-0">
                                                <button type="button" data-bs-target="#reviewcarouselIndicators" data-bs-slide-to="0" class="active" aria-current="true" aria-label="Slide 1">
                                                    <img src="assets/images/users/avatar-1.jpg" class="avatar-md img-fluid rounded-circle d-block" alt="...">
                                                </button>
                                                <button type="button" data-bs-target="#reviewcarouselIndicators" data-bs-slide-to="1" aria-label="Slide 2">
                                                    <img src="assets/images/users/avatar-2.jpg" class="avatar-md img-fluid rounded-circle d-block" alt="...">
                                                </button>
                                                <button type="button" data-bs-target="#reviewcarouselIndicators" data-bs-slide-to="2" aria-label="Slide 3">
                                                    <img src="assets/images/users/avatar-3.jpg" class="avatar-md img-fluid rounded-circle d-block" alt="...">
                                                </button>
                                            </div>
                                            <!-- end carouselIndicators -->
                                            <div class="carousel-inner">
                                                <div class="carousel-item active">
                                                    <div class="testi-contain text-center text-white">
                                                        <i class="bx bxs-quote-alt-left text-success display-6"></i>
                                                        <h4 class="mt-4 fw-medium lh-base text-white">“I feel confident
                                                            imposing change
                                                            on myself. It's a lot more progressing fun than looking back.
                                                            That's why
                                                            I ultricies enim
                                                            at malesuada nibh diam on tortor neaded to throw curve balls.”
                                                        </h4>
                                                        <div class="mt-4 pt-1 pb-5 mb-5">
                                                            <h5 class="font-size-16 text-white">Richard Drews
                                                            </h5>
                                                            <p class="mb-0 text-white-50">Web Designer</p>
                                                        </div>
                                                    </div>
                                                </div>

                                                <div class="carousel-item">
                                                    <div class="testi-contain text-center text-white">
                                                        <i class="bx bxs-quote-alt-left text-success display-6"></i>
                                                        <h4 class="mt-4 fw-medium lh-base text-white">“Our task must be to
                                                            free ourselves by widening our circle of compassion to embrace
                                                            all living
                                                            creatures and
                                                            the whole of quis consectetur nunc sit amet semper justo. nature
                                                            and its beauty.”</h4>
                                                        <div class="mt-4 pt-1 pb-5 mb-5">
                                                            <h5 class="font-size-16 text-white">Rosanna French
                                                            </h5>
                                                            <p class="mb-0 text-white-50">Web Developer</p>
                                                        </div>
                                                    </div>
                                                </div>

                                                <div class="carousel-item">
                                                    <div class="testi-contain text-center text-white">
                                                        <i class="bx bxs-quote-alt-left text-success display-6"></i>
                                                        <h4 class="mt-4 fw-medium lh-base text-white">“I've learned that
                                                            people will forget what you said, people will forget what you
                                                            did,
                                                            but people will never forget
                                                            how donec in efficitur lectus, nec lobortis metus you made them
                                                            feel.”</h4>
                                                        <div class="mt-4 pt-1 pb-5 mb-5">
                                                            <h5 class="font-size-16 text-white">Ilse R. Eaton</h5>
                                                            <p class="mb-0 text-white-50">Manager
                                                            </p>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                            <!-- end carousel-inner -->
                                        </div>
                                        <!-- end review carousel -->
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <!-- end col -->
                </div>
                <!-- end row -->
            </div>
            <!-- end container fluid -->
        </div>
<script type="text/javascript">
  $('#btnonOtp').click(function()
{
    $.ajax({
        url: test.php,
        type:'POST',
        data:
        {
            // The key is 'mobile'. This will be the same key in $_POST[] that holds the mobile number value.
            mobile: $('#mobile').val()
        },
        success: function(msg)
        {
            alert('OTP Sent');
        }               
    });
});

</script>
<?php echo form_open() ?>

<?php echo form_close() ?>


