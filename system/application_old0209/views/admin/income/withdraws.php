USDT
<div class="col">
    <div class="card bg-secondary shadow">  
        <div class="card-header bg-white border-0"> 
        <div class="row align-items-center">
            <div class="col-8">
                  <h3 class="mb-0">Hold Payment List</h3>
            </div>
        <div class="col-4 text-right"> 
            <a href="<?php echo site_url('income/withdraws_list/Paid')?>" class="btn btn-sm btn-success">Paid</a>
            <a href="<?php echo site_url('income/withdraws_list/Hold')?>" class="btn btn-sm btn-warning">Hold</a>
            <a href="<?php echo site_url('income/withdraws_list/Un-Paid')?>" class="btn btn-sm btn-danger">Un-Paid</a>
        </div> 
    </div>
</div>
<div class="card-body">
<div class="table-responsive">
    <table class="table align-items-center table-flush" id="example">
        <thead>
        <tr>
            <th scope="col">S.N.</th>
            <th scope="col">PAN NO</th>
            <th scope="col">Name</th>
            <th scope="col">Phone</th>
            <th scope="col">Amount</th>
            <th scope="col">Admin Charge</th>
            <th scope="col">Tds </th>
            <th scope="col">Payable Amount</th>
            <th scope="col">UPI</th>
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
                $bank_data   = $this->db_model->select_multi('*', 'member_profile', array('userid' => $e->userid));
                $user_data   = $this->db_model->select_multi('*', 'member', array('id' => $e->userid));
                $tax         = config_item('admin_charges') + config_item('payout_tax');
                $main_amount = $e->amount - ($e->amount * $tax) / 100;
        ?>
        <tr>
            <td><?php echo $sn++; ?></td>
            <td><?php echo  $bank_data->tax_no ?></td>
            <td><?php echo $user_data->name ?></td>
            <td><?php echo $user_data->phone ?></td>
            <td><?php echo config_item('currency') . $e->amount ?></td>
            <td><?php echo config_item('admin_charges').'%' ?></td>
            <td><?php echo config_item('payout_tax').'%' ?></td>
            <td><?php echo config_item('currency') . $main_amount ?></td>
            <td style="font-weight: bold;"><?php echo !empty($bank_data->upi_id) ? $bank_data->upi_id : '<span style="color: red;">Not Provided</span>'; ?></td>
            <td><?php echo !empty($bank_data->bank_name) ? $bank_data->bank_name : '<span style="color: red;">Not Provided</span>'; ?></td>
            <td><?php echo !empty($bank_data->bank_ac_no) ? $bank_data->bank_ac_no : '<span style="color: red;">Not Provided</span>'; ?></td>
            <td><?php echo !empty($bank_data->bank_ifsc) ? $bank_data->bank_ifsc : '<span style="color: red;">Not Provided</span>'; ?></td>
            <td><?php echo !empty($bank_data->bank_branch) ? $bank_data->bank_branch : '<span style="color: red;">Not Provided</span>'; ?></td>
            <td><?php echo $e->date ?></td>
            <td>
                <?php if($e->status == 'Un-Paid'){ ?>
                    <a data-toggle="modal" data-target="#myModal" onclick="document.getElementById('payid').value='<?php echo $e->id ?>'" class="btn text-white btn-success btn-md">Pay</a>
                    <a href="<?php echo site_url('income/hold/' . $e->id) ?>" class="btn btn-warning btn-md">Hold</a>
                <?php }elseif($e->status == 'Hold'){ ?>
                <a href="<?php echo site_url('income/unhold/' . $e->id) ?>" class="btn btn-success btn-md">Un-Hold</a>
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
                <button type="button" class="close" data-dismiss="modal">&times;</button>
                <h4 class="modal-title">Payout Detail</h4>
            </div>
            <div class="modal-body">
                <?php echo form_open('income/pay') ?>
                <label>Enter Transaction Detail</label>
                <input type="hidden" name="payid" value="" id="payid">
                <textarea class="form-control" name="tdetail"></textarea>
                <div class="pull-right">
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