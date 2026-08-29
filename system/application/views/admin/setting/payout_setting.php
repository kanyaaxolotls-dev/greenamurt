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
     <?php echo form_open() ?>
            <div class="row">
               
               

                <div class="col-sm-4">
                    <label>Payout TDS (%)</label>
                    <input type="text" class="form-control" value="<?php echo set_value('payout_tax', config_item('payout_tax')) ?>"
                           name="payout_tax">
                </div>
                <div class="col-sm-4">
                    <label>Non PAN. TDS (%)</label>
                    <input type="text" class="form-control" value="<?php echo set_value('payout_tax2', config_item('payout_tax2')) ?>"
                           name="payout_tax2">
                </div>
                <div class="col-sm-4">
                    <label>Admin Charges(%)</label>
                    <input type="text" class="form-control" value="<?php echo set_value('admin_charges', config_item('admin_charges')) ?>"
                           name="admin_charges">
                </div>

                <div class="col-sm-4">
                    <label>Allow User to Withdraw Fund</label>
                    <select class="form-control" name="user_withdraw">
                        <option selected><?php echo config_item('user_withdraw') ?></option>
                        <option>Yes</option>
                        <option>No</option>
                    </select>
                </div>
                <div class="col-sm-4">
                    <label>Min amount allowed to Withdraw (in <?php echo config_item('currency') ?> )</label>
                    <input type="text" class="form-control"
                           value="<?php echo set_value('min_withdraw', config_item('min_withdraw')) ?>"
                           name="min_withdraw">
                </div>
                <div class="col-sm-4">
                    <label>Use Payment API</label>
                    <select class="form-control" name="payment_api">
                        <option selected><?php echo config_item('payment_api') ?></option>
                        <option>None</option>
                        <option>Block.io</option>
                    </select>
                </div>
                <div class="col-sm-4">
                    <label>Multi Wallet?</label>
                    <select class="form-control" name="wallet_type">
                        <option selected><?php echo config_item('wallet_type') ?></option>
                        <option>Yes</option>
                        <option>No</option>
                    </select>
                </div>
                <div class="col-sm-4">
                    <label>Want to Enable generation plan?</label>
                    <select class="form-control" name="gen_list">
                        <option selected><?php echo config_item('gen_list') ?></option>
                        <option>Yes</option>
                        <option>No</option>
                    </select>
                </div>

                <div class="col-sm-4">
                    <label>Auto debit for repurchase?</label>
                     <input type="text" class="form-control"
                           value="<?php echo set_value('auto_debit', config_item('auto_debit')) ?>"
                           name="auto_debit" value="0">  
                </div>
                <div class="col-sm-4">  
                    <label>Rank Bonus?</label>
                     <select class="form-control" name="rank_bonus">
                        <option selected><?php echo config_item('rank_bonus') ?></option>
                        <option>Yes</option>
                        <option>No</option>
                    </select>
                    
                </div>
                <div class="col-sm-4">
                    <label>Profit Percentage</label>
                    <input type="text" class="form-control" value="<?php echo set_value('percentage', config_item('percentage')) ?>"
                           name="percentage">
                </div>


                <div class="col-sm-4"><br/>
                    <input type="submit" class="btn btn-primary" value="Update" onclick="this.value='Updating..'">
                </div>
              
            </div>
              <?php echo form_close() ?>
          </div>
      </div>
    </div>
</div>