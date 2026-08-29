<?php echo form_open() ?>

 <div class="header bg-gradient-primary py-5 py-lg-7">
      <div class="container">
        <div class="header-body text-center mb-7">
          <div class="row justify-content-center">
            <div class="col-lg-8 col-md-8">
              <h1 class="text-white">Reset your password</h1>
             
            </div>
          </div>
        </div> 
      </div>
      <div class="separator separator-bottom separator-skew zindex-100">
        <svg x="0" y="0" viewBox="0 0 2560 100" preserveAspectRatio="none" version="1.1" xmlns="http://www.w3.org/2000/svg">
          <polygon class="fill-default" points="2560 0 2560 100 0 100"></polygon>
        </svg>
      </div>
    </div>
  <div class="container mt--7 pb-5">

      <div class="row justify-content-center">
        <div class="col-lg-4 col-md-4">
          <div class="card bg-secondary shadow border-0">
          
            <div class="card-body px-lg-5 py-lg-5">

                <?php echo validation_errors('<div class="alert alert-danger">', '</div>') ?>
                <?php echo $this->session->flashdata('site_flash') ?>
                <div class="form-group col-sm-12">
                    <label for="user" class="control-label">User ID / Username</label>
                    <input type="text" class="form-control" id="user" name="user" value="<?php echo set_value('id')?>">
                </div>
                <div class="form-group col-sm-12">
                   <button class="btn btn-primary" type="submit">Reset</button>
                    <a href="<?php echo site_url('site/login') ?>">Want to Login ?</a>
                </div>
            </div>
        </div>
    </div>
</div>
<?php echo form_close() ?>


