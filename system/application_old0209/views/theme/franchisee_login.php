<?php echo form_open() ?>
<div class="container py-5">
    <div class="row justify-content-center">
        <div class="col-lg-5 col-md-7">
            <div class="text-center mb-4">
                <h2 class="fw-bold text-dark">Welcome! Franchisee</h2>
                <p class="text-muted">Login to continue</p>
            </div>
            <div class="card shadow rounded-4">
                <div class="card-body p-4">
                    <?php echo validation_errors('<div class="alert alert-danger">', '</div>') ?>
                    <?php echo $this->session->flashdata('site_flash') ?>                
                    <div class="mb-3">
                        <label for="user" class="form-label">Username</label>
                        <input type="text" name="username" id="user" class="form-control form-control-lg" placeholder="Enter username" required>
                    </div>

                    <div class="mb-4">
                        <label for="password" class="form-label">Password</label>
                        <input type="password" name="password" id="password" class="form-control form-control-lg" placeholder="Enter password" required>
                    </div>

                    <div class="d-grid">
                        <button type="submit" class="btn btn-success btn-lg">
                            <i class="fas fa-sign-in-alt me-2"></i> Login
                        </button>
                    </div>
                    
                </div>
            </div>
        </div>
    </div>
</div>
<?php echo form_close(); ?>
