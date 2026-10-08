    <div class="header bg-gradient-primary py-7 py-lg-7">
      <div class="container">
        <div class="header-body text-center mb-7">
          <div class="row justify-content-center">
            <div class="col-lg-5 col-md-7">
              <h1 class="text-red">Registration is Failed ! Payment Not Made</h1>
              
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
  <div class="container mt--9 pb-5">

    <?php echo $this->session->flashdata('site_flash') ?>
    <div class="row">
        Dear <?php echo $this->session->_user_name_ ?>,<br/>
        We are sorry that, we are unable to complete your registration at this moment. Please try again from the
        begining.
        <hr/>
    </div>
    <div class="row" align="center">
        <a href="<?php echo site_url('site/register') ?>" class="btn btn-primary btn-lg">Register Again</a>
    </div>
</div>

