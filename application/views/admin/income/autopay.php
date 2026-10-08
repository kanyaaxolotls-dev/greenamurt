<div class="col">
    <div class="card bg-secondary shadow">  
        <div class="card-header bg-white border-0"> 
        <div class="row align-items-center">
            <div class="col-8"> 
                  <h3 class="mb-0">Confirmation!</h3>
            </div>
        <div class="col-4 text-right"> 
            <a href="<?php echo site_url('income/view_earning')?>" class="btn btn-sm btn-primary">Home</a>
        </div> 
    </div>
</div>   
<div class="card-body">
      <div class="row">  
            <div class="col-sm-4">
                Total Payable Balance:
            </div>
            <div class="col-sm-5">
                <?php echo config_item('currency') . $payable_balance ?>
            </div>
            <div class="col-sm-2">
                 <a href="<?php echo site_url('income/payall_gateway/accept') ?>"
           onclick="return confirm('Are you sure ? This cannot be reversed.')" class="btn btn-primary">Confirm
            and Proceed &rarr;</a>
            </div>
      </div>
</div>
<div class="card-footer">
      <small>If you click on confirm and proceed button, <strong><?php echo config_item('payment_api') ?></strong> gateway
            will be 
            used to process all your payments. If your Company Account has enough fund, then The funds will be used to
            pay for user earning and will auto debit from your company account and will auto credit to user account.
        </small>
</div>
</div>
</div>
