 <div class="header bg-gradient-primary py-7 py-lg-7">
      <div class="container">
        <div class="header-body text-center mb-7">
          <div class="row justify-content-center">
            <div class="col-lg-5 col-md-5">
              <h1 class="text-white">Sucessfully Placed order!</h1>
             
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
        <div class="col-lg-5 col-md-7">
          <div class="card bg-secondary shadow border-0">
          
            <div class="card-body px-lg-5 py-lg-5">
             
                <?php echo $this->session->flashdata('site_flash') ?>
                    Dear <?php echo $this->session->_user_name_ ?>,<br/>
                   Thank you for becoming distributor of  <?php echo config_item('company_name') ?> team.
                    Your member login details as bellow: 
                    <hr/>
            
                    <strong>Your Sponsor ID :</strong> <?php echo config_item('ID_EXT') . $this->session->_sponsor_ ?><br/>
                    <strong>Your User ID :</strong> <?php echo config_item('ID_EXT') . $this->session->_user_id_ ?><br/>
                    <strong>Your Password :</strong> <em>******</em>
                   
               
            </div>
          </div>
          <div class="row mt-3">
            <div class="col-6">
              <a href="<?php echo site_url('site/auto_login') ?>" class="btn btn-success">Login to your account</a>
            </div>
            <div class="col-6 text-right">
              <a href="<?php echo site_url('site/register') ?>" class="btn btn-primary">Create New Member</a>
            </div>
          </div>
        </div>
      </div>
   
    </div>

