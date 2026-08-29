<!DOCTYPE html>
<html class="no-js" lang="en">
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
    <meta http-equiv="x-ua-compatible" content="ie=edge" />
    <title>Coming Soon Style <?php echo config_item('company_name') ?></title>
    <meta name="description" content="description" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <link rel="shortcut icon" href="<?php echo base_url('uploads/logo.png') ?>" />
    <link rel="stylesheet" href="<?php echo base_url('assets2/css/plugins.css') ?>" />
    <link rel="stylesheet" href="<?php echo base_url('assets2/css/style.css') ?>" />
    <link rel="stylesheet" href="<?php echo base_url('assets2/css/responsive.css') ?>" />
</head>
    <body class="page-template template-password coming-soon-style1" style='background-image:url("<?php echo base_url('assets2/images/coming-soon/coming-soon1.jpg') ?>");'>
        <div id="pre-loader"><img src="<?php echo base_url('assets2/images/loader.gif') ?>" alt="Loading..." /></div>
        <div class="pageWrapper">
            <div id="page-content" style="height:100vh;">
                <div class="password-page-wrap">
                    <div class="password-table">
                        <div class="password-page password-cell text-center">
                            <!--<header class="password-header">-->
                            <!--    <div class="password-header__inner">-->
                            <!--        <div class="password-login"><button type="button" class="btn btn-primary link--action" data-bs-toggle="modal" data-bs-target="#LoginModal">Login Directly From Here !</button></div>-->
                            <!--    </div>-->
                            <!--</header>-->
                            <div class="password-main text-center">
                                <div class="password-main__inner"> 
                                    <a href="#" class="site-header__logo-image"><img src="<?php echo base_url('uploads/logo.png') ?>" alt="logo" width="130" /></a>
                                    <h2 class="password__title">We're Coming Soon</h2>
                                    <p class="password-message">We will launch it soon at the end of this week</p>
                                    <form method="post" action="#" id="contact_form" accept-charset="UTF-8" class="contact-form">
                                        <p class="password__form-heading h4">We are working very hard to give you the best experience possible!</p>
                                        <div class="input-group password__input-group">
                                            <input type="email" name="contact[email]" id="Email" class="input-group__field" placeholder="Email address" required />
                                            <span class="input-group__btn"><button type="submit" name="commit" class="btn"> <span>Notify me</span> </button></span>
                                        </div>
                                    </form>
                                    <div class="password-social-sharing">
                                        <div class="social-sharing">
                                            <p class="password__form-heading h4 text-uppercase">Spread the word</p>
                                            <ul class="list--inline justify-content-center">
                                                <li><a class="social-icons__link text-black me-3" href="#" target="_blank" data-bs-toggle="tooltip" data-bs-placement="top" title="Share on Facebook"><i class="icon an an-facebook align-middle"></i><span class="share-title align-middle">Facebook</span></a></li>
                                                <li><a class="social-icons__link text-black me-3" href="#" target="_blank" data-bs-toggle="tooltip" data-bs-placement="top" title="Share on Twitter"><i class="icon an an-twitter align-middle"></i><span class="share-title align-middle">Tweet</span></a></li>
                                                <li><a class="social-icons__link text-black me-3" href="#" target="_blank" data-bs-toggle="tooltip" data-bs-placement="top" title="Share on Pinterest"><i class="icon an an-pinterest-p align-middle"></i><span class="share-title align-middle">Pin it</span></a></li>
                                                <li><a class="social-icons__link text-black me-3" href="#" target="_blank" data-bs-toggle="tooltip" data-bs-placement="top" title="Share on Instagram"><i class="icon an an-instagram align-middle"></i><span class="share-title align-middle">Instagram</span></a></li>
                                            </ul>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="modal fade" id="LoginModal" tabindex="-1" aria-hidden="true">
                                <div class="modal-dialog" style="max-width:400px;">
                                    <div class="modal-content" style="background-color:rgba(255,255,255,.9);">
                                        <button type="button" class="btn-close modal__close" data-bs-dismiss="modal" aria-label="Close" data-bs-toggle="tooltip" data-bs-placement="bottom" title="Close"></button>
                                        <div class="modal-body">
                                            <p class="password__form-heading h4 mt-1 mb-3">Login Here</p>
                                            <form method="post" action="" id="login_form" accept-charset="UTF-8" class="storefront-password-form">
                                                <label class="label--hidden">Password</label>
                                                <div class="input-group password__input-group">
                                                    <input type="password" name="password" id="Password" class="input-group__field input--content-color" placeholder="Your password" required />
                                                    <span class="input-group__btn"><button type="submit" name="commit" class="btn btn--narrow">Enter</button></span> 
                                                </div>
                                            </form>
                                            <p>Are you the store owner? <a href="<?php echo base_url('site/admin') ?>" class="text-link">Log in here</a></p>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>    
                </div>
            </div>
            <script src="<?php echo base_url('assets2/js/plugins.js') ?>"></script>
            <script src="<?php echo base_url('assets2/js/main.js') ?>"></script>
        </div>
    </body>

</html>