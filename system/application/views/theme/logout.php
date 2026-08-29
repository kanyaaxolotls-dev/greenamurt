<div class="container-fluid">
    <div class="row justify-content-center align-items-center min-vh-100">
        <div class="col-xl-4 col-lg-5 col-md-6 col-sm-8 col-11">

            <div class="card shadow-lg border-0 rounded-4">
                <div class="card-body p-4 p-md-5">

                    <div class="text-center mb-4">
                        <a href="<?php echo site_url('site/login');?>" class="d-block auth-logo">
                            <img src="<?php echo base_url('assets/images/logo-sm.svg')?>" alt="" height="45">
                            <h4 class="mt-3"><?php echo config_item('company_name'); ?></h4>
                        </a>
                    </div>

                    <div class="text-center">
                        <div class="avatar-xl mx-auto mb-4">
                            <div class="avatar-title bg-light text-primary h1 rounded-circle">
                                <i class="bx bxs-user"></i>
                            </div>
                        </div>

                        <h4>You are Logged Out</h4>

                        <p class="text-muted">
                            Thank you for using
                            <strong><?php echo config_item('company_name'); ?></strong>
                        </p>

                        <a href="<?php echo site_url('site/login'); ?>"
                           class="btn btn-primary w-100 mt-3">
                            Sign In
                        </a>

                        <div class="mt-4">
                            <p class="mb-0">
                                Don't have an account?
                                <a href="<?php echo site_url('site/register'); ?>" class="fw-semibold">
                                    Signup
                                </a>
                            </p>
                        </div>
                    </div>

                </div>
            </div>

            <div class="text-center mt-4">
                © <script>document.write(new Date().getFullYear())</script>
                <?php echo config_item('company_name'); ?>
            </div>

        </div>
    </div>
</div>