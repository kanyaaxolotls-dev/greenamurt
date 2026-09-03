<div class="col">
    <div class="card bg-secondary shadow">  
        <div class="card-header bg-white border-0"> 
        <div class="row align-items-center">
            <div class="col-8">
                  <h3 class="mb-0">Hold Payment List</h3>
            </div>
        <div class="col-4 text-right"> 
            <a href="<?php echo site_url('income/fran_payout/Paid')?>" class="btn btn-sm btn-success">Paid</a>
            <a href="<?php echo site_url('income/fran_payout/Hold')?>" class="btn btn-sm btn-warning">Hold</a>
            <a href="<?php echo site_url('income/fran_payout/Un-Paid')?>" class="btn btn-sm btn-danger">Un-Paid</a>
        </div> 
    </div>
</div>
<div class="card-body">
<div class="table-responsive">
    <table class="table align-items-center table-flush" id="example">
        <thead>
        <tr>
            <th scope="col">S.N.</th>
            <th scope="col">Userid</th>
            <th scope="col">Name</th>
            <th scope="col">Phone</th>
            <th scope="col">Amount</th>
            <th scope="col">Charges</th>
            <th scope="col">Payable Amount</th>
            <th scope="col">Bank Name</th>
            <th scope="col">Account Number</th>
            <th scope="col">IFSC</th>
            <th scope="col">Branch Name</th>
            <th scope="col">Date</th>
            <th scope="col">Action</th>
        </tr>
        </thead>
        <tbody>
        <?php
            $sn = 1;
            foreach ($data as $e) {
                $user_data   = $this->db_model->select_multi('*', 'franchisee', array('id' => $e->userid));
                $tax         = $e->tax;
                $main_amount = $e->amount - $tax;
        ?>
        <tr>
            <td><?php echo $sn++; ?></td>
            <td><?php echo $user_data->id ?></td>
            <td><?php echo $user_data->business_name ?></td>
            <td><?php echo $user_data->phone ?></td>
            <td><?php echo config_item('currency') . $e->amount ?></td>
            <td><?php echo config_item('currency') . $tax ?></td>
            <td><?php echo config_item('currency') . $main_amount ?></td>
            <td><?php echo !empty($user_data->bank_name) ? $user_data->bank_name : '<span style="color: red;">Not Provided</span>'; ?></td>
            <td><?php echo !empty($user_data->acc_number) ? $user_data->acc_number : '<span style="color: red;">Not Provided</span>'; ?></td>
            <td><?php echo !empty($user_data->bank_ifsc) ? $user_data->bank_ifsc : '<span style="color: red;">Not Provided</span>'; ?></td>
            <td><?php echo !empty($user_data->bank_branch) ? $user_data->bank_branch : '<span style="color: red;">Not Provided</span>'; ?></td>
            <td><?php echo $e->date ?></td>
            <td>
                <?php if($e->status == 'Un-Paid'){ ?>
                    <a data-toggle="modal" data-target="#myModal" onclick="document.getElementById('payid').value='<?php echo $e->id ?>'" class="btn text-white btn-success btn-md">Pay</a>
                    <a href="<?php echo site_url('income/fran_hold/' . $e->id) ?>" class="btn btn-warning btn-md">Hold</a>
                <?php }elseif($e->status == 'Hold'){ ?>
                <a href="<?php echo site_url('income/fran_unhold/' . $e->id) ?>" class="btn btn-success btn-md">Un-Hold</a>
                <?php }else{ ?>
                <a class="btn btn-primary text-white btn-md">Action Performed</a>
                <?php } ?>
            </td>
        </tr>
        <?php } ?>
        </tbody>
    </table>
</div>
</div>
<div class="card-footer">
    <a href="<?php echo site_url('income/view-earning') ?>" class="btn btn-sm btn-primary">&larr; Go Back</a>
</div>
</div>
</div>


<div class="modal fade" id="myModal" role="dialog">
    <div class="modal-dialog modal-sm">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title">Payout Detail</h4>
            </div>
            <div class="modal-body">
                <?php echo form_open('income/fran_pay') ?>
                <label>Enter Transaction Detail</label>
                <input type="hidden" name="payid" value="" id="payid">
                <textarea class="form-control" name="tdetail" placeholder="Enter payout details"></textarea>
                <div class="pull-right  mt-3">
                    <button type="submit" class="btn btn-success">Pay Now</button>
                </div>
                <?php echo form_close() ?>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
            </div>
        </div>
    </div>
</div>