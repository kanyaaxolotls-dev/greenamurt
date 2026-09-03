<style>
    body {
    background: #f4f7fb;
}

.auth-page {
    min-height: 100vh;
}

.card {
    border-radius: 15px;
}

.form-control {
    height: 55px;
}

.btn-primary {
    height: 50px;
    font-size: 16px;
}

@media (max-width: 768px) {
    .card {
        margin: 20px 0;
    }

    .card-body {
        padding: 25px;
    }

    img {
        width: 100px;
    }
}
</style>
<div class="auth-page">
    <?php echo form_open(); ?>

    <div class="container-fluid">
        <div class="row justify-content-center align-items-center min-vh-100">
            <div class="col-xl-4 col-lg-5 col-md-6 col-sm-8 col-11">

                <div class="card shadow-lg border-0 rounded-4">
                    <div class="card-body p-4 p-md-5">

                        <!-- Logo -->
                        <div class="text-center mb-4">
                            <a href="<?php echo base_url('site/login'); ?>">
                                <img src="<?php echo base_url('uploads/logo.png') ?>" class="img-fluid" width="140">
                            </a>
                        </div>

                        <!-- Validation -->
                        <?php echo validation_errors('<div class="alert alert-danger">', '</div>'); ?>
                        <?php echo $this->session->flashdata('site_flash'); ?>

                        <?php if (config_item('is_demo') != TRUE) { ?>
                            <div class="alert alert-danger">
                                Please Pay your remaining balance to remove this banner!<br>
                                इस बैनर को हटाने के लिए कृपया अपनी शेष राशि का भुगतान करें।
                            </div>
                        <?php } ?>

                        <div class="text-center mb-4">
                            <h4>Welcome Back!</h4>
                            <p class="text-muted">
                                Sign in to continue to
                                <?php echo config_item('company_name'); ?>
                            </p>
                        </div>

                        <!-- Username -->
                        <div class="form-floating mb-3">
                            <input type="text"
                                   class="form-control"
                                   id="user"
                                   name="username"
                                   placeholder="Username"
                                   required>
                            <label>Username</label>
                        </div>

                        <!-- Password -->
                        <div class="form-floating mb-3 position-relative">
                            <input type="password"
                                   class="form-control"
                                   id="password"
                                   name="password"
                                   placeholder="Password"
                                   required>

                            <label>Password</label>

                            <button type="button"
                                    onclick="myFunction()"
                                    class="btn btn-link position-absolute top-50 end-0 translate-middle-y me-2 border-0">
                                <i class="mdi mdi-eye-outline"></i>
                            </button>
                        </div>

                        <!-- Remember -->
                        <div class="d-flex justify-content-between align-items-center mb-4">
                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" id="remember-check">
                                <label class="form-check-label" for="remember-check">
                                    Remember me
                                </label>
                            </div>

                            <a href="<?php echo site_url('site/forgotpw'); ?>">
                                Forgot Password?
                            </a>
                        </div>

                        <!-- Login -->
                        <button class="btn btn-primary w-100 mb-3" type="submit">
                            Log In
                        </button>

                        <!-- Signup -->
                        <div class="text-center">
                            Don't have an account?
                            <a href="<?php echo site_url('site/register'); ?>">
                                Sign Up
                            </a>
                        </div>

                    </div>
                </div>

                <div class="text-center mt-4">
                    © <?php echo date('Y'); ?>
                    <?php echo config_item('company_name'); ?>
                </div>

            </div>
        </div>
    </div>

    <?php echo form_close(); ?>
</div>