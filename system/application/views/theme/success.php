  <div class="row g-0"> 
                    <div class="col-12">
                        <div class="auth-full-page-content d-flex p-sm-5 p-4">
                            <div class="w-100">
                                <div class="d-flex flex-column h-100">
                                    <div class="mb-4 mb-md-5 text-center">
                                       <a href="index.html" class="d-block auth-logo">
                                            <img src="<?php echo base_url('uploads/logo.png') ?>" class="img-fluid" alt="" height="80" width="120">
                                        </a>
                                         <span class="logo-txt text-muted"><?php echo config_item('company_name') ?></span>
                                    </div>
                                    <div class="auth-content my-auto">
                                        <div class="text-center">
                                            <div class="avatar-lg mx-auto">
                                                <div class="avatar-title rounded-circle bg-light">
                                                    <i class="bx bx-mail-send h2 mb-0 text-primary"></i>
                                                </div>
                                            </div>
                                            <div class="p-2 mt-4">
                                                <h4>Success !</h4>
                                                <p class="text-muted">
                                                    <?php echo $this->session->flashdata('site_flash') ?>
                                                        Dear <?php echo $this->session->_user_name_ ?>,<br/>
                                                        Thank you for becoming distributor of  <?php echo config_item('company_name') ?> team.
                                                        Your member login details as bellow: 
                                                    <hr/>
                                                    <!--<strong>Your Sponsor ID :</strong> <?php echo config_item('ID_EXT') . $this->session->_sponsor_ ?><br/>-->
                                                    <strong>Your User ID :</strong> <?php echo config_item('ID_EXT') . $this->session->_user_id_ ?><br/>
                                                    <strong>Login Password :</strong> <em><?= $this->db_model->select('pass', 'member', array('id' => $this->session->_user_id_)); ?></em><br/>
                                                    <!--<strong>Transaction Password :</strong> <em><?= $this->db_model->select('trans_password', 'member', array('id' => $this->session->_user_id_)); ?></em>--->
                                                </p>
                                                <div class="mt-4">
                                                    <a href="<?php echo site_url('site/auto_login') ?>" class="btn btn-primary">Login to your account</a>
                                                    <a href="<?php echo site_url('site/register') ?>" class="btn btn-primary">Create New Member</a>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <!--<div class="mt-4 mt-md-5 text-center">-->
                                    <!--    <p class="mb-0">© <script>document.write(new Date().getFullYear())</script> Dason   . Crafted with <i class="mdi mdi-heart text-danger"></i> by Axolotls</p>-->
                                    <!--</div>-->
                                </div>
                            </div>
                        </div>
                        <!-- end auth full page content -->
                    </div>
                    <!-- end col -->
                    <!--<div class="col-xxl-9 col-lg-8 col-md-7">-->
                    <!--    <div class="auth-bg pt-md-5 p-4 d-flex">-->
                            <!--<div class="bg-overlay"></div>-->
                            <!--<ul class="bg-bubbles">-->
                            <!--    <li></li>-->
                            <!--    <li></li>-->
                            <!--    <li></li>-->
                            <!--    <li></li>-->
                            <!--    <li></li>-->
                            <!--    <li></li>-->
                            <!--    <li></li>-->
                            <!--    <li></li>-->
                            <!--    <li></li>-->
                            <!--    <li></li>-->
                            <!--</ul>-->
                            <!-- end bubble effect -->
                <!--            <div class="row justify-content-center align-items-end">-->
                <!--                <div class="col-xl-7">-->
                <!--                    <div class="p-0 p-sm-4 px-xl-0">-->
                <!--                        <div id="reviewcarouselIndicators" class="carousel slide" data-bs-ride="carousel">-->
                <!--                            <div class="carousel-indicators auth-carousel carousel-indicators-rounded justify-content-center mb-0">-->
                <!--                                <button type="button" data-bs-target="#reviewcarouselIndicators" data-bs-slide-to="0" class="active" aria-current="true" aria-label="Slide 1">-->
                <!--                                    <img src="assets/images/users/avatar-1.jpg" class="avatar-md img-fluid rounded-circle d-block" alt="...">-->
                <!--                                </button>-->
                <!--                                <button type="button" data-bs-target="#reviewcarouselIndicators" data-bs-slide-to="1" aria-label="Slide 2">-->
                <!--                                    <img src="assets/images/users/avatar-2.jpg" class="avatar-md img-fluid rounded-circle d-block" alt="...">-->
                <!--                                </button>-->
                <!--                                <button type="button" data-bs-target="#reviewcarouselIndicators" data-bs-slide-to="2" aria-label="Slide 3">-->
                <!--                                    <img src="assets/images/users/avatar-3.jpg" class="avatar-md img-fluid rounded-circle d-block" alt="...">-->
                <!--                                </button>-->
                <!--                            </div>-->
                                            <!-- end carouselIndicators -->
                <!--                            <div class="carousel-inner">-->
                <!--                                <div class="carousel-item active">-->
                <!--                                    <div class="testi-contain text-center text-white">-->
                <!--                                        <i class="bx bxs-quote-alt-left text-success display-6"></i>-->
                <!--                                        <h4 class="mt-4 fw-medium lh-base text-white">“I feel confident-->
                <!--                                            imposing change-->
                <!--                                            on myself. It's a lot more progressing fun than looking back.-->
                <!--                                            That's why-->
                <!--                                            I ultricies enim-->
                <!--                                            at malesuada nibh diam on tortor neaded to throw curve balls.”-->
                <!--                                        </h4>-->
                <!--                                        <div class="mt-4 pt-1 pb-5 mb-5">-->
                <!--                                            <h5 class="font-size-16 text-white">Richard Drews-->
                <!--                                            </h5>-->
                <!--                                            <p class="mb-0 text-white-50">Web Designer</p>-->
                <!--                                        </div>-->
                <!--                                    </div>-->
                <!--                                </div>-->

                <!--                                <div class="carousel-item">-->
                <!--                                    <div class="testi-contain text-center text-white">-->
                <!--                                        <i class="bx bxs-quote-alt-left text-success display-6"></i>-->
                <!--                                        <h4 class="mt-4 fw-medium lh-base text-white">“Our task must be to-->
                <!--                                            free ourselves by widening our circle of compassion to embrace-->
                <!--                                            all living-->
                <!--                                            creatures and-->
                <!--                                            the whole of quis consectetur nunc sit amet semper justo. nature-->
                <!--                                            and its beauty.”</h4>-->
                <!--                                        <div class="mt-4 pt-1 pb-5 mb-5">-->
                <!--                                            <h5 class="font-size-16 text-white">Rosanna French-->
                <!--                                            </h5>-->
                <!--                                            <p class="mb-0 text-white-50">Web Developer</p>-->
                <!--                                        </div>-->
                <!--                                    </div>-->
                <!--                                </div>-->

                <!--                                <div class="carousel-item">-->
                <!--                                    <div class="testi-contain text-center text-white">-->
                <!--                                        <i class="bx bxs-quote-alt-left text-success display-6"></i>-->
                <!--                                        <h4 class="mt-4 fw-medium lh-base text-white">“I've learned that-->
                <!--                                            people will forget what you said, people will forget what you-->
                <!--                                            did,-->
                <!--                                            but people will never forget-->
                <!--                                            how donec in efficitur lectus, nec lobortis metus you made them-->
                <!--                                            feel.”</h4>-->
                <!--                                        <div class="mt-4 pt-1 pb-5 mb-5">-->
                <!--                                            <h5 class="font-size-16 text-white">Ilse R. Eaton</h5>-->
                <!--                                            <p class="mb-0 text-white-50">Manager-->
                <!--                                            </p>-->
                <!--                                        </div>-->
                <!--                                    </div>-->
                <!--                                </div>-->
                <!--                            </div>-->
                                            <!-- end carousel-inner -->
                <!--                        </div>-->
                                        <!-- end review carousel -->
                <!--                    </div>-->
                <!--                </div>-->
                <!--            </div>-->
                <!--        </div>-->
                <!--    </div>-->
                    <!-- end col -->
                <!--</div>-->
                <!-- end row -->

 <!--<div class="header bg-gradient-primary py-7 py-lg-7">-->
 <!--     <div class="container">-->
 <!--       <div class="header-body text-center mb-7">-->
 <!--         <div class="row justify-content-center">-->
 <!--           <div class="col-lg-5 col-md-5">-->
 <!--             <h1 class="text-white">Registration Done!</h1>-->
             
 <!--           </div>-->
 <!--         </div>-->
 <!--       </div>-->
 <!--     </div>-->
 <!--     <div class="separator separator-bottom separator-skew zindex-100">-->
 <!--       <svg x="0" y="0" viewBox="0 0 2560 100" preserveAspectRatio="none" version="1.1" xmlns="http://www.w3.org/2000/svg">-->
 <!--         <polygon class="fill-default" points="2560 0 2560 100 0 100"></polygon>-->
 <!--       </svg>-->
 <!--     </div>-->
 <!--   </div>-->
 <!-- <div class="container mt--7 pb-5">-->

 <!--     <div class="row justify-content-center">-->
 <!--       <div class="col-lg-5 col-md-7">-->
 <!--         <div class="card bg-secondary shadow border-0">-->
          
 <!--           <div class="card-body px-lg-5 py-lg-5">-->
             
 <!--               <?php echo $this->session->flashdata('site_flash') ?>-->
 <!--                   Dear <?php echo $this->session->_user_name_ ?>,<br/>-->
 <!--                  Thank you for becoming distributor of  <?php echo config_item('company_name') ?> team.-->
 <!--                   Your member login details as bellow: -->
 <!--                   <hr/>-->
            
 <!--                   <strong>Your Sponsor ID :</strong> <?php echo config_item('ID_EXT') . $this->session->_sponsor_ ?><br/>-->
 <!--                   <strong>Your User ID :</strong> <?php echo config_item('ID_EXT') . $this->session->_user_id_ ?><br/>-->
 <!--                   <strong>Your Password :</strong> <em>******</em>-->
                   
               
 <!--           </div>-->
 <!--         </div>-->
 <!--         <div class="row mt-3">-->
 <!--           <div class="col-6">-->
 <!--             <a href="<?php echo site_url('site/auto_login') ?>" class="btn btn-success">Login to your account</a>-->
 <!--           </div>-->
 <!--           <div class="col-6 text-right">-->
 <!--             <a href="<?php echo site_url('site/register') ?>" class="btn btn-primary">Create New Member</a>-->
 <!--           </div>-->
 <!--         </div>-->
 <!--       </div>-->
 <!--     </div>-->
   
 <!--   </div>-->

