<?php
    include ('head.php');
?>
            <!-- Body Content -->
            <div id="page-content">
                <!-- Page Title -->
                <div class="page section-header text-center mb-0">
                    <div class="page-title">
                        <div class="wrapper"><h1 class="page-width">Contact Us</h1></div>
                    </div>
                </div>
                <!-- End Page Title -->
                <!-- Breadcrumbs -->
                <div class="bredcrumbWrap bredcrumbWrapPage bredcrumb-style2 text-center mb-0">
                    <div class="container breadcrumbs">
                        <a href="" title="Back to the home page">Home</a><span aria-hidden="true">|</span><span class="title-bold">Contact Us</span>
                    </div>
                </div>
                <!-- End Breadcrumbs -->

                <div class="container-fluid px-0">
                    <div class="row g-0">
                        <div class="col-12 col-sm-12 col-md-12 col-lg-6 p-0">
                            <div class="map-section map">
                                <iframe src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d3758.990881482486!2d74.20003477581494!3d19.584891335798602!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x3bdd003103afd161%3A0x1fa9e5102686c19f!2sOrange%20Corner%20Rd%2C%20Suyog%20Colony%2C%20Sangamner%2C%20Maharashtra%20422605!5e0!3m2!1sen!2sin!4v1736146000770!5m2!1sen!2sin" width="600" height="610" style="border:0;" allowfullscreen="" loading="lazy" referrerpolicy="no-referrer-when-downgrade"></iframe>
                            </div>
                        </div>
                        <div class="col-12 col-sm-12 col-md-12 col-lg-6 justify-content-center align-items-center flex-wrap px-3 px-sm-5 pt-4 pb-2 mb-md-5 mb-lg-0 mb-sm-5 mb-5">
                            <h2 class="text-center">DROP US A LINE</h2>
                            <p class="text-center">We value your input and are here to help. If you have any questions, feedback, or concerns, please don't hesitate to contact us using the information provided below. Our dedicated team is ready to assist you. </p>
                            <?php 
                                echo validation_errors('<div class="alert alert-danger">', '</div>');
                                echo $this->session->flashdata('common_flash');
                            ?>
                            <!-- Contact Form -->
                            <div class="formFeilds contact-form form-vertical">
                                <form action="<?php echo base_url('home/contact_us'); ?>" method="post" class="contact-form">	
                                    <div class="row">
                                        <div class="col-12 col-sm-12 col-md-6 col-lg-6">
                                            <div class="form-group">
                                                <input type="text"  name="name" class="form-control" placeholder="Name" required/>
                                            </div>
                                        </div>
                                        <div class="col-12 col-sm-12 col-md-6 col-lg-6">                               
                                            <div class="form-group">
                                                <input type="email" name="email" class="form-control" placeholder="Email" required/>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-12 col-sm-12 col-md-6 col-lg-6">
                                            <div class="form-group">
                                                <input class="form-control" type="tel"  name="phone" pattern="[0-9\-]*" placeholder="Phone Number"  required/>
                                            </div>
                                        </div>
                                        <div class="col-12 col-sm-12 col-md-6 col-lg-6">
                                            <div class="form-group">
                                                <input type="text" name="subject" class="form-control" placeholder="Subject" required/>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-12 col-sm-12 col-md-12 col-lg-12">
                                            <div class="form-group">
                                                <textarea name="message" class="form-control" rows="4" placeholder="Your Message..." required></textarea>
                                            </div>
                                        </div>  
                                    </div>
                                    <div class="row">
                                        <div class="col-12 col-sm-12 col-md-12 col-lg-12">
                                            <div class="form-group mailsendbtn mb-0">	
                                                <input class="btn btn-primary" type="submit" name="contactus" value="Send Message" />
                                                <div class="loading"><img class="img-fluid" src="assets/images/ajax-loader.gif" alt="loading"></div>
                                            </div>
                                        </div>
                                    </div>
                                </form>
                                <div class="response-msg"></div>
                            </div>
                            <!-- End Contact Form -->

                            <div class="contact-details">
                                <hr />
                                <div class="row">
                                    <div class="col-12 col-sm-12 col-md-6 col-lg-6">
                                        <ul class="addressFooter" style="list-style: none;">
                                            <li>
                                                <i class="icon an an-map-marker"></i>
                                                <p><?php echo config_item('company_address') ?></p>
                                            </li>
                                            <!--<li class="phone">-->
                                            <!--    <i class="icon an an-phone-volume"></i>-->
                                            <!--    <p><a href="tel:<?php echo config_item('web_phone') ?>">+91 <?php echo config_item('web_phone') ?></a></p>-->
                                            <!--</li>-->
                                            <li class="email">
                                                <i class="icon an an-envelope"></i>
                                                <p><a href="mailto:<?php echo config_item('web_email') ?>"><?php echo config_item('web_email') ?></a></p>
                                            </li>
                                        </ul>
                                    </div>
                                    <div class="col-12 col-sm-12 col-md-6 col-lg-6">
                                        <div class="open-hours">
                                            <strong class="pb-1 d-inline-block">Opening Hours</strong><br>
                                            Mon - Sat : 10 am - 7 pm<br>
                                            Sunday: 10 am - 5 pm
                                        </div>
                                    </div>
                                </div>
                                <ul class="list--inline site-footer__social-icons social-icons mt-lg-0 mt-md-0 mt-3">
                                    <li><a class="social-icons__link d-inline-block" href="#" target="_blank" data-bs-toggle="tooltip" data-bs-placement="top" title="Facebook"><i class="icon an an-facebook"></i></a></li>
                                    <li><a class="social-icons__link d-inline-block" href="#" target="_blank" data-bs-toggle="tooltip" data-bs-placement="top" title="Twitter"><i class="icon an an-twitter"></i> <span class="icon__fallback-text">Twitter</span></a></li>
                                    <li><a class="social-icons__link d-inline-block" href="#" target="_blank" data-bs-toggle="tooltip" data-bs-placement="top" title="Pinterest"><i class="icon an an-pinterest-p"></i> <span class="icon__fallback-text">Pinterest</span></a></li>
                                    <li><a class="social-icons__link d-inline-block" href="#" target="_blank" data-bs-toggle="tooltip" data-bs-placement="top" title="Instagram"><i class="icon an an-instagram"></i> <span class="icon__fallback-text">Instagram</span></a></li>
                                    <li><a class="social-icons__link d-inline-block" href="#" target="_blank" data-bs-toggle="tooltip" data-bs-placement="top" title="Tumblr"><i class="icon an an-tumblr"></i> <span class="icon__fallback-text">Tumblr</span></a></li>
                                    <li><a class="social-icons__link d-inline-block" href="#" target="_blank" data-bs-toggle="tooltip" data-bs-placement="top" title="YouTube"><i class="icon an an-youtube"></i> <span class="icon__fallback-text">YouTube</span></a></li>
                                    <li><a class="social-icons__link d-inline-block" href="#" target="_blank" data-bs-toggle="tooltip" data-bs-placement="top" title="Vimeo"><i class="icon an an-vimeo"></i> <span class="icon__fallback-text">Vimeo</span></a></li>
                                </ul>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <!-- End Body Content -->

<?php
      include ('footer.php');
?>