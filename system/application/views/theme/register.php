<?php echo form_open('', 'id="registrationForm"'); ?>
<div class="auth-page">
    <div class="container-fluid p-0">
        <div class="row g-0 justify-content-center">
            <div class="col-xxl-6 col-lg-7 col-md-10">
                <div class="auth-full-page-content d-flex p-sm-5 p-4 bg-white shadow rounded-4 mt-5">
                    <div class="w-100">
                        <div class="text-center mb-4">
                            <img src="<?= base_url('uploads/logo.png') ?>" alt="" height="80">
                            <h4 class="mt-3">Create Your Account</h4>
                            <!-- Requirement 5: Single Leg Motivation -->
                            <div class="alert alert-info py-2 mb-2" style="font-size: 0.8rem;">
                                <i class="fa fa-info-circle"></i> Join our <strong>Global Single Leg</strong> team today and get spillover power automatically!
                            </div>
                            <p class="text-muted">Fill in the details or leave blank for defaults.</p>
                        </div>

                        <?php echo validation_errors('<div class="alert alert-danger">', '</div>') ?>
                        <?php if ($this->session->flashdata('site_flash')): ?>
                            <?= $this->session->flashdata('site_flash'); ?>
                        <?php endif; ?>

                        <div class="row">
                            <!-- Optional Sponsor Section -->
                            <div class="col-md-6 mb-3" id="sponsor_sec">
                                <label class="form-label fw-bold">Sponsor ID</label>
                                <input type="text" id="sponsor" name="sponsor" class="form-control border-primary" 
                                       placeholder="Leave blank for Default" oninput="get_user_name('#sponsor', '#spn_res')">
                                <small id="spn_res" class="text-success"></small>
                            </div>

                            <!-- Optional Placement Section -->
                            <div class="col-md-6 mb-3" id="placement_sec">
                                <label class="form-label fw-bold">Placement ID</label>
                                <input type="text" id="position" name="position" class="form-control" 
                                       placeholder="Leave blank for Auto-Fill" oninput="get_user_name('#position', '#pos_res')">
                                <small id="pos_res" class="text-success"></small>
                            </div>

                            <hr class="my-3">

                            <!-- Identity Section -->
                            <div class="col-md-6 mb-3">
                                <label class="form-label">Full Name</label>
                                <input type="text" class="form-control" id="name" name="name" placeholder="Your Name" required>
                            </div>

                            <div class="col-md-6 mb-3">
                                <label class="form-label">Phone Number</label>
                                <input type="tel" class="form-control" id="phone" name="phone" placeholder="Mobile Number" required>
                            </div>
                            
                            <div class="col-md-6 mb-3">
                                <label class="form-label">Email Address</label>
                                <input type="email" class="form-control" id="email" name="email" placeholder="email@example.com" required>
                            </div>
                                
                            <div class="row pe-0">
                                <div class="col-md-6 mb-3">
                                    <label class="form-label">Country</label>
                                    <select name="country_id" id="country" class="form-select border-primary" required>
                                        <option value="">Select Country</option>
                                        <?php foreach($countries as $c): ?>
                                            <option value="<?= $c['id'] ?>"><?= $c['name'] ?></option>
                                        <?php endforeach; ?>
                                    </select>
                                </div>
                            
                                <div class="col-md-6 mb-3">
                                    <label class="form-label">State</label>
                                    <select name="state_id" id="state" class="form-select border-primary" disabled required>
                                        <option value="">Select State</option>
                                    </select>
                                </div>
                            
                                <div class="col-md-6 mb-3">
                                    <label class="form-label">District</label>
                                    <select name="district_id" id="district" class="form-select border-primary" disabled required>
                                        <option value="">Select District</option>
                                    </select>
                                </div>
                            
                                <div class="col-md-6 mb-3">
                                    <label class="form-label">Tehsil</label>
                                    <select name="tehsil_id" id="tehsil" class="form-select border-primary" disabled required>
                                        <option value="">Select Tehsil</option>
                                    </select>
                                </div>
                            </div>

                            <!-- Package Selection: First one selected by default -->
                            <div class="col-md-12 mb-3">
                                <label class="form-label fw-bold text-primary">Sign Up Package</label>
                                <span class="badge bg-success ms-2">Retail Profit up to 30%</span>
                                <select class="form-select form-select-lg border-primary" id="join_package" name="join_package">
                                    <?php 
                                    $count = 0;
                                    foreach ($products as $val): 
                                        $selected = ($count == 0) ? 'selected' : '';
                                    ?>
                                        <option value="<?= $val['id'] . '|' . $val['prod_price'] ?>" <?= $selected ?>>
                                            <?= $val['prod_name'] ?> - <?= config_item('currency') . number_format($val['prod_price'], 2) ?>
                                        </option>
                                    <?php $count++; endforeach; ?>
                                </select>
                            </div>

                            <div class="col-md-12 mb-4">
                                <label class="form-label fw-bold text-dark" id="pass_label">Password <span class="text-danger">*</span></label>
                                <input type="password" class="form-control border-primary" name="password" placeholder="Create a Password" required>
                                <small class="text-muted" id="pass_help">Must be a unique password for this ID.</small>
                            </div>

                            <div class="col-12">
                                <button class="btn btn-primary btn-lg w-100 shadow-sm" id="submitBtn" type="submit">Complete Registration</button>
                            </div>
                        </div>

                        <div class="mt-4 text-center">
                            <p class="text-muted">Already have an account? <a href="<?= site_url('site/login'); ?>" class="fw-bold">Login</a></p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

<script>
$(function () {

    function resetDropdown(selector, text) {
        $(selector)
            .html('<option value="">'+text+'</option>')
            .prop('disabled', true);
    }

    $('#country').change(function () {
        let id = $(this).val();
        resetDropdown('#state','Select State');
        resetDropdown('#district','Select District');
        resetDropdown('#tehsil','Select Tehsil');
        if(id=='') return;

        $.get("<?= site_url('site/get_states/') ?>"+id,function(res){
            let data=JSON.parse(res);
            let html='<option value="">Select State</option>';
            $.each(data,function(i,row){
                html+='<option value="'+row.id+'">'+row.name+'</option>';
            });
            $('#state').html(html).prop('disabled',false);
        });
    });

    $('#state').change(function(){
        let id=$(this).val();
        resetDropdown('#district','Select District');
        resetDropdown('#tehsil','Select Tehsil');
        if(id=='') return;

        $.get("<?= site_url('site/get_districts/') ?>"+id,function(res){
            let data=JSON.parse(res);
            let html='<option value="">Select District</option>';
            $.each(data,function(i,row){
                html+='<option value="'+row.id+'">'+row.name+'</option>';
            });
            $('#district').html(html).prop('disabled',false);
        });
    });

    $('#district').change(function(){
        let id = $(this).val();
        resetDropdown('#tehsil','Select Tehsil');
        if(id=='') return;

        $.get("<?= site_url('site/get_tehsils/') ?>" + id, function(res){
            let data = JSON.parse(res);
            let html = '<option value="">Select Tehsil</option>';
            $.each(data, function(i, row){
                html += '<option value="'+row.id+'">'+row.name+'</option>';
            });
            $('#tehsil').html(html).prop('disabled', false);
        });
    });

});
</script>
<?php echo form_close(); ?>