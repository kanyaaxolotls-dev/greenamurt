     <br>
     <?php echo form_open() ?>
    <div class="header bg-gradient-primary py-7 py-lg-7">
      <div class="container">
        <div class="header-body text-center mb-7">
          <div class="row justify-content-center">
            <div class="col-lg-5 col-md-7">
              <h1 class="text-white">Welcome!</h1>
              <p class="text-lead text-light">You can login or create and account.</p>
              
            </div>
          </div>
        </div>
      </div>

    </div>
  <div class=" mt--9 pb-5">
     <?php echo validation_errors('<div class="alert alert-danger">', '</div>') ?>
          <?php echo $this->session->flashdata('site_flash') ?>
          <?php if (config_item('is_demo') != TRUE) {
               echo '<div class="alert alert-danger">Please Pay your remaining balance to remove this banner !<br/> इस बैनर को हटाने के लिए कृपयाअपनी शेष राशि का भुगतान करें !</div>';
          } ?>
      <div class="justify-content-center">
        
        <div class="col-lg-6 col-md-7">
         
          <div class="card bg-secondary shadow border-0">
           <!--  <div class="card-header bg-transparent pb-5">
              <div class="text-muted text-center mt-2 mb-3"><small>Sign in with</small></div>
              <div class="btn-wrapper text-center">
                <a href="#" class="btn btn-neutral btn-icon">
                  <span class="btn-inner--icon"><img src="../assets/img/icons/common/github.svg"></span>
                  <span class="btn-inner--text">Github</span>
                </a>
                <a href="#" class="btn btn-neutral btn-icon">
                  <span class="btn-inner--icon"><img src="../assets/img/icons/common/google.svg"></span>
                  <span class="btn-inner--text">Google</span>
                </a>
              </div>
            </div> -->

            <div class="card-body px-lg-5 py-lg-5">
              
                <div class="formbox">
                 <?php //echo validation_errors('<div class="alert alert-danger">', '</div>') ?>
                  <?php //echo $this->session->flashdata('site_flash') ?>
                  <?php if (config_item('is_demo') == TRUE) {
                 //     echo '<div class="alert alert-danger">Please Pay your remaining balance to remove this banner !<br/> इस बैनर को हटाने के लिए कृपयाअपनी शेष राशि का भुगतान करें !</div>';
                  } ?>
                  <div class="form-group">
                     <div class="input-group input-group-alternative">
                      <div class="input-group-prepend">
                        <span class="input-group-text"><i class="ni ni-email-83"></i></span>
                      </div>
                      <input type="text" required class="form-control form-control-alternative" id="phone" name="phone" placeholder="Mobile No">
                    </div>
                  </div>
                  <div class="form-group">
                      <div class="input-group input-group-alternative">
                        <div class="input-group-prepend">
                          <span class="input-group-text"><i class="ni ni-lock-circle-open"></i></span>
                        </div>
                        <input type="password" required class="form-control" id="password" name="password" placeholder="Password">
                      </div>
                  </div>
                  <div class="custom-control custom-control-alternative custom-checkbox">
                    <input class="custom-control-input" id=" customCheckLogin" type="checkbox">
                    <label class="custom-control-label" for=" customCheckLogin">
                      <span class="text-muted">Remember me</span>
                    </label>
                  </div>
                  <div class="text-center">
                      <button class="btn btn-primary my-4">Login</button>
                      
                  </div>
              </div>
                 <small><a href="<?php echo site_url('site/register') ?>">No an member? Sign up here</a></small>
            </div>
          </div>
          <div class="row mt-3">
            <div class="col-6">
              <a href="<?php echo site_url('site/forgotpw') ?>" class="text-light"><small>Forgot password?</small></a>
            </div>
            <div class="col-6 text-right">
              <a href="<?php echo site_url('site/register') ?>" class="text-light"><small>Create new account</small></a>
            </div>
          </div>
        </div>
      </div>
   
    </div>
<?php echo form_close() ?>
