<div class="col">
    <div class="card bg-secondary shadow">  
        <div class="card-header bg-white border-0"> 
            <div class="row align-items-center">
                <div class="col-8">
                    <h3 class="mb-0"><?= $title; ?> </h3>
                </div>
                <div class="col-4 text-right"> 
                    <a href="<?php echo site_url('income/withdraws_list/Paid')?>" class="btn btn-sm btn-success">Paid</a>
                    <a href="<?php echo site_url('income/withdraws_list/Hold')?>" class="btn btn-sm btn-warning">Hold</a>
                    <a href="<?php echo site_url('income/withdraws_list/Un-Paid')?>" class="btn btn-sm btn-danger">Un-Paid</a>
                    <a href="<?php echo site_url('income/withdraws_list/Reject')?>" class="btn btn-sm btn-danger">Reject</a>
                </div> 
            </div>
        </div>
        
        <div class="card-body">
            <!-- Date Range Filter Form -->
            <form method="post" action="<?php echo site_url('income/withdraws_list/'.$typee); ?>" class="mb-4">
                <div class="row">
                    <div class="col-md-3">
                        <div class="form-group">
                            <label for="fname">Name</label>
                            <input type="text" class="form-control" id="fname" name="fname" placeholder="Enter full name" value="<?php echo isset($_POST['fname']) ? $_POST['fname'] : '' ?>">
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="form-group">
                            <label for="adhar_no">Adhar No.</label>
                            <input type="text" class="form-control" id="adhar_no" name="adhar_no" placeholder="Enter adhar no" value="<?php echo isset($_POST['adhar_no']) ? $_POST['adhar_no'] : '' ?>">
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="form-group">
                            <label for="from_date">From Date</label>
                            <input type="date" class="form-control" id="from_date" name="from_date" value="<?php echo isset($_POST['from_date']) ? $_POST['from_date'] : '' ?>">
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="form-group">
                            <label for="to_date">To Date</label>
                            <input type="date" class="form-control" id="to_date" name="to_date" value="<?php echo isset($_POST['to_date']) ? $_POST['to_date'] : '' ?>">
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group">
                            <label>&nbsp;</label>
                            <button type="submit" class="btn btn-primary btn-block">Filter</button>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group">
                            <label>&nbsp;</label>
                            <a href="<?php echo site_url('income/withdraws_list/'.$typee); ?>" class="btn btn-secondary btn-block">Reset</a>
                        </div>
                    </div>
                </div>
            </form>
            <div class="table-responsive">
                <form id="bulkActionForm" method="post" action="<?php echo site_url('income/process_payouts'); ?>">
                    <input type="hidden" id="selectedIds" name="selected_ids" value="">
                    <input type="hidden" id="status" name="status" value="">
                    <?php if($typee == 'Un-Paid' or $typee == 'Hold'){ ?>
                    <button type="button" class="btn btn-success mb-3" onclick="submitForm('Paid')">Paid selected ids</button>
                    <button type="button" class="btn btn-warning mb-3" onclick="submitForm('Hold')">Hold selected ids</button>
                    <button type="button" class="btn btn-danger mb-3" onclick="submitForm('Reject')">Reject selected ids</button>
                    <!--<a href="<?= base_url('income/update_payout_new'); ?>"  class="btn btn-info mb-3" onclick="return confirm('Are you sure you want to generate the payout?');">Generate Payout</a>-->
                    <a href="<?= base_url('cron'); ?>"  class="btn btn-info mb-3" onclick="return confirm('Are you sure you want to generate the payout?');">Generate Payout</a>
                    <?php } ?>
                    <table class="table align-items-center table-flush" id="example">
                        <thead>
                        <tr>
                            <?php if($typee == 'Un-Paid' or $typee == 'Hold'){ ?>
                            <th>
                                <input type="checkbox" id="selectAllCheckbox">
                            </th>
                            <?php } ?>
                            <th scope="col">S.N.</th>
                            <th scope="col">Userid</th>
                            <th scope="col">Adhar NO</th>
                            <th scope="col">Name</th>
                            <th scope="col">Phone</th>
                            <th scope="col">Amount</th>
                            <?php if(config_item('admin_charges') > 0){ ?>
                            <th scope="col">Admin Charge ( <?php echo config_item('admin_charges').'%' ?> )</th>
                            <?php } ?>
                            <th scope="col">Tds (<?php echo config_item('payout_tax').'%' ?>)</th>
                            <th scope="col">Payable Amount</th>
                            <th scope="col">Bank Name</th>
                            <th scope="col">Account Number</th>
                            <th scope="col">IFSC</th>
                            <th scope="col">Branch Name</th>
                            <?php if($typee == 'Paid' or empty($typee) or $typee == NULL or $typee == 'All'){ ?>
                            <th scope="col">TID</th>
                            <?php } ?>
                            <th scope="col">Date</th>
                            <th scope="col">Action</th>
                        </tr>
                        </thead>
                        <tbody>
                        <?php
                            $sn = 1;
                            $totalAmount = 0;
                            $totalAdminCharge = 0;
                            $totalTds = 0;
                            
                            foreach ($data as $e) {
                                $bank_data   = $this->db_model->select_multi('*', 'member_profile', array('userid' => $e->userid));
                                $user_data   = $this->db_model->select_multi('*', 'member', array('id' => $e->userid));
                                
                                // Calculate charges dynamically from configuration
                                $admin_pct = floatval(config_item('admin_charges'));
                                $tds_pct   = floatval(config_item('payout_tax'));
                                $admin_charge_amount = round(($e->amount * $admin_pct) / 100.0, 2);
                                $tds_amount          = round(($e->amount * $tds_pct) / 100.0, 2);
                                $main_amount         = round($e->amount - $admin_charge_amount - $tds_amount, 2);
                                
                                // Update totals
                                $totalAmount += $main_amount;
                                $totalAdminCharge += $admin_charge_amount;
                                $totalTds += $tds_amount;
                        ?>
                        <tr>
                            <?php if($typee == 'Un-Paid' or $typee == 'Hold'){ ?>
                            <td>
                                <input type="checkbox" class="rowCheckbox" value="<?php echo $e->id; ?>">
                            </td>
                            <?php } ?>
                            <td><?php echo $sn++; ?></td>
                            <td><?php echo  config_item('ID_EXT') . $bank_data->userid ?></td>
                            <td><?php echo !empty($bank_data->aadhar_no) ? $bank_data->aadhar_no : '<span style="color: red;">Not Provided</span>'; ?></td>
                            <td><?php echo $user_data->name ?></td>
                            <td><?php echo $user_data->phone ?></td>
                            <td><?php echo config_item('currency') . $e->amount ?></td>
                            <?php if(config_item('admin_charges') > 0){ ?>
                            <td><?php echo config_item('currency') . number_format($admin_charge_amount, 2) ?></td>
                            <?php } ?>
                            <td><?php echo config_item('currency') . number_format($tds_amount, 2) ?></td>
                            <td><?php echo config_item('currency') . number_format($main_amount, 2) ?></td>
                            <td><?php echo !empty($bank_data->bank_name) ? $bank_data->bank_name : '<span style="color: red;">Not Provided</span>'; ?></td>
                            <td><?php echo !empty($bank_data->bank_ac_no) ? $bank_data->bank_ac_no : '<span style="color: red;">Not Provided</span>'; ?></td>
                            <td><?php echo !empty($bank_data->bank_ifsc) ? $bank_data->bank_ifsc : '<span style="color: red;">Not Provided</span>'; ?></td>
                            <td><?php echo !empty($bank_data->bank_branch) ? $bank_data->bank_branch : '<span style="color: red;">Not Provided</span>'; ?></td>
                            <?php if($typee == 'Paid' or empty($typee) or $typee == NULL or $typee == 'All'){ ?>
                            <td><?php echo $e->tid ?></td>
                            <?php } ?>
                            <td><?php echo $e->date ?></td>
                            <td>
                                <?php if($e->status == 'Un-Paid'){ ?>
                                    <a href="javascript:void(0)" onclick="payPayment('<?php echo $e->id ?>')" class="btn text-white btn-success btn-md">Transfer</a>
                                    <!--<a data-toggle="modal" data-target="#myModal" onclick="document.getElementById('payid').value='<?php echo $e->id ?>'" class="btn text-white btn-success btn-md">Pay</a>-->
                                    <a href="javascript:void(0)" onclick="holdPayment('<?php echo $e->id ?>')" class="btn btn-warning btn-md">Hold</a>
                                <?php }elseif($e->status == 'Hold'){ ?>
                                <a href="<?php echo site_url('income/unhold/' . $e->id) ?>" class="btn btn-success btn-md">Un-Hold</a>
                                <?php }else{ ?>
                                <a class="btn btn-primary text-white btn-md">Transferred</a>
                                <?php } ?>
                            </td>
                        </tr>
                        <?php } ?>
                        </tbody>
                        <!-- Table Footer for Totals -->
                        <tfoot>
                            <tr>
                                <td colspan="6" class="text-right"><strong>Total:</strong></td>
                                <td><strong><?php echo config_item('currency') . number_format(array_sum(array_column($data, 'amount')), 2); ?></strong></td>
                                <?php if(config_item('admin_charges') > 0){ ?>
                                <td><strong><?php echo config_item('currency') . number_format($totalAdminCharge, 2); ?></strong></td>
                                <?php } ?>
                                <td><strong><?php echo config_item('currency') . number_format($totalTds, 2); ?></strong></td>
                                <td><strong><?php echo config_item('currency') . number_format($totalAmount, 2); ?></strong></td>
                                <td colspan="5"></td>
                                <td></td>
                            </tr>
                        </tfoot>
                    </table>
                </form>
            </div>
        </div>
        <div class="card-footer">
            <a href="<?php echo site_url('income/view-earning') ?>" class="btn btn-sm btn-primary">&larr; Go Back</a>
        </div>
    </div>
</div>

<div class="modal fade" id="holdModal" role="dialog">
    <div class="modal-dialog modal-sm">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal">&times;</button>
                <h4 class="modal-title">Hold Payment</h4>
            </div>
            <div class="modal-body">
                <?php echo form_open('income/hold') ?>
                <input type="hidden" name="holdid" id="holdid">
                <label>Enter Reason for Hold</label>
                <textarea class="form-control" name="hold_reason" required></textarea>
                <div class="pull-right mt-2">
                    <button type="submit" class="btn btn-warning">Hold Now</button>
                </div>
                <?php echo form_close() ?>
            </div>
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
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

<script>
    function holdPayment(id) {
    Swal.fire({
        title: 'Hold Payment',
        input: 'textarea',
        inputLabel: 'Reason for Hold',
        inputPlaceholder: 'Enter reason...',
        inputAttributes: { 'aria-label': 'Enter reason...' },
        showCancelButton: true,
        confirmButtonText: 'Hold Now',
        preConfirm: (reason) => {
            if (!reason) {
                Swal.showValidationMessage('Reason is required');
            }
            return reason;
        }
    }).then((result) => {
        if (result.isConfirmed) {
            $.post("<?= site_url('income/hold_ajax') ?>", { id: id, reason: result.value }, function(res) {
                Swal.fire('Success', res.message, 'success').then(() => {
                    location.reload(); // reload same page with filters intact
                });
            }, 'json');
        }
    });
}
function payPayment(id) {
    Swal.fire({
        title: 'Enter Transaction Detail',
        input: 'textarea',
        inputPlaceholder: 'Transaction ID / Details...',
        showCancelButton: true,
        confirmButtonText: 'Pay Now',
        preConfirm: (detail) => {
            if (!detail) {
                Swal.showValidationMessage('Transaction detail required');
            }
            return detail;
        }
    }).then((result) => {
        if (result.isConfirmed) {
            $.post("<?= site_url('income/pay_ajax') ?>", { id: id, detail: result.value }, function(res) {
                console.log("Raw response:", res);
                if (res.status === 'success') {
                    Swal.fire('Success', res.message, 'success').then(() => {
                        location.reload();
                    });
                } else {
                    Swal.fire('Error', res.message || 'Something went wrong', 'error');
                }
            }, 'json')
            .fail(function(xhr) {
                console.error("AJAX Error:", xhr.responseText);
                Swal.fire('Error', 'Invalid server response, check console', 'error');
            });
        }
    });
}
</script>