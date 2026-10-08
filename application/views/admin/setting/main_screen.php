<div class="col">
    <div class="card bg-secondary shadow">  
        <div class="card-header bg-white border-0"> 
        <div class="row align-items-center">
            <div class="col-8">
                  <h3 class="mb-0"><?php echo $title;?></h3>
            </div>
        <div class="col-4 text-right"> 
            <a href="<?php echo site_url('admin/index')?>" class="btn btn-sm btn-primary">Home</a>
        </div>
    </div>
</div> 
<div class="card-body">
        <div class="container">
          <div class="row">
            <div class="col">
              <span>
                <a class="btn btn-primary btn-lg" href="<?php echo site_url('setting/common-setting') ?>">

                     Common Setting  </a>
                 </span>
            </div>
            <div class="col-sm">
              <span>   <a class="btn btn-primary btn-lg" href="<?php echo site_url('setting/advance-setting') ?>">Advance Setting</a></span>
            </div>
            <div class="col-sm">
              <span> <a class="btn btn-primary btn-lg" href="<?php echo site_url('setting/payout-setting') ?>">Payout Setting</a></span>
            </div>
            <div class="col-sm">
              <span> <a class="btn btn-primary btn-lg" href="<?php echo site_url('setting/payment-gateway') ?>">Payment Gateway Setup</a></span>
            </div>
            <div class="col-sm">
              <span> <a class="btn btn-primary btn-lg" href="<?php echo site_url('setting/plan-setting') ?>">Plan Setup</a></span>
            </div>

          </div>
        </div>

</div>
</div>
 
