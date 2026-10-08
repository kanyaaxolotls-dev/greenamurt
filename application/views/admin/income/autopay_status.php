<div class="col">
    <div class="card bg-secondary shadow <?php echo $status ?>">  
        <div class="card-header bg-white border-0"> 
        <div class="row align-items-center"> 
            <div class="col-8"> 
                  <h3 class="mb-0">Status</h3>
            </div>
        <div class="col-4 text-right"> 
            <a href="<?php echo site_url('income/view_earning')?>" class="btn btn-sm btn-primary">Home</a>
        </div> 
    </div> 
</div>  
<div class="card-body">

        <?php if ($status == "success") { ?>
            <p> Your company fund has withdrawn and credited to user accounts successfully. There is nothing left to do
                from your end. Happy Business !

            </p>
        <?php } else { ?>
            <p>Oops ! We found some error while processing your transactions. Please check if your
                <strong><?php echo config_item('payment_api') ?></strong> accounts has enough fund to cover your
                transactions or not. Also check payment gateway setting if everything is properly set or not.</p>
        <?php } ?>
        <?php if (isset($this->session->skip_autopay)) { ?>
            <div class="alert alert-danger">Some User do not have their <?php echo $this->session->skip_autopay ?>
                updated in their account. Their payment has not made.
            </div>
        <?php }
        unset($_SESSION['skip_autopay']); ?>
    </div>
</div>
</div>
