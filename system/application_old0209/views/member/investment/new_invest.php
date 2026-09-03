<div class="col"> 
    <div class="card bg-secondary shadow">  
        <div class="card-header bg-white border-0"> 
            <div class="row align-items-center">
            <div class="col-lg-6">
            <h3 class="mb-0">Your Wallet have   <strong><?php echo config_item('currency') . $this->db_model->select('balance', 'wallet', array('userid' => $this->session->user_id)) ?></strong> <a href="<?php echo site_url('member/topup_wallet') ?>" class="btn btn-sm btn-primary">Add More Fund</a></h3>
            </div> 
            <div class="col-lg-6 text-right"> 
                    <a href="<?php echo site_url('admin')?>" class="btn btn-sm btn-primary">Home</a>
            </div>
        </div>
    </div> 
<div class="card-body">
   <div class="table-responsive">
    <table class="table table-striped table-bordered">
        <tr>
            <th>SN</th>
            <th>Package Name</th>
            <th>Amount</th>
            <th>ROI</th>
            <th>Actions</th>
        </tr>
        <?php
        $sn = 1;
        foreach ($result as $e) { ?>
            <tr>
                <td><?php echo $sn++; ?></td>
                <td><?php echo $e->pack_name; ?></td>
                <td style="background-color: #3c763d; color: #fff"><?php echo $e->amount ? config_item('currency') . $e->amount : 'Any Amount'; ?></td>
                <?php if ($e->based == "Fixed") { ?>
                    <td><?php echo config_item('currency') . $e->roi; ?></td>
                <?php } else { ?>
                    <td><?php echo $e->roi; ?> %</td>
                <?php } ?>
                <td>
                    <a href="javascript:;"
                       onclick="document.getElementById('amt_div').style.display=<?php if ($e->amount > 0) {
                           echo "'none'";
                       } else echo "'block'"; ?>;
                       document.getElementById('id').value='<?php echo $e->id ?>';document.getElementById('amt').value='<?php echo $e->amount ? $e->amount : '' ?>'; document.getElementById('ccode').innerHTML='<?php echo $e->pack_name ?>'"
                       data-toggle="modal" data-target="#modal-notification"
                       class="btn btn-info btn-sm">Invest Here</a>
                       
                       <!-- <button type="button" id="coupon" class="btn btn-warning btn-sm" data-toggle="modal" data-target="#modal-notification">Notification</button> -->
                </td>
            </tr>
        <?php } ?>
    </table>
</div>
<div class="pull-right">
    <?php echo $this->pagination->create_links(); ?>
</div> 

<div class="modal fade" id="modal-notification" tabindex="-1" role="dialog" aria-labelledby="modal-notification" aria-hidden="true">
    <div class="modal-dialog modal-danger modal-dialog-centered modal-" role="document">
        <div class="modal-content bg-gradient-danger">
            
            <div class="modal-header">
                <h6 class="modal-title" id="modal-title-notification">Your attention is required</h6>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">×</span>
                </button>
            </div>
            
            <div class="modal-body">                
                <div class="py-3 text-center">
                    <i class="ni ni-bell-55 ni-2x"></i>
                    <h4 class="heading mt-4">You should read this!</h4>
                        <?php echo form_open('investments/do-it') ?>
                        <input type="hidden" class="form-control" name="id" id="id" value=""><br/>
                            <div id="amt_div">
                                <label>Investment Amount (In <?php echo config_item('currency') ?>) :</label>
                                <input type="text" class="form-control" name="amount" value="" id="amt"><br/>
                            </div>
                            <?php if (config_item('investment_mode') == "EPIN") { ?>

                                <label>Investment e-PIN (Value must be same as amount) :</label>
                                <input type="text" class="form-control" name="epin"><br/>

                            <?php } ?>
                            <button type="submit" class="btn btn-success">Click to Invest &rarr;</button>
                        <?php echo form_close() ?>
                </div>
                
            </div>
            
            <div class="modal-footer">
                <button type="button" class="btn btn-white">Ok, Got it</button>
                <button type="button" class="btn btn-link text-white ml-auto" data-dismiss="modal">Close</button>
            </div>
            
        </div>
    </div>

</div>

</div></div></div></div></div></div>