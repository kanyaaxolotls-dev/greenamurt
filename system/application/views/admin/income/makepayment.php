<div class="col">
    <div class="card bg-secondary shadow">  
        <div class="card-header bg-white border-0"> 
            <div class="row align-items-center">
                <div class="col-6">
                    <h3 class="mb-0"><?= $title; ?> </h3>
                </div>
                <div class="col-6 text-right"> 
                    <a href="<?php echo site_url('income/withdraws_list/All')?>" class="btn btn-sm btn-outline-dark <?= ($typee == 'All' || empty($typee)) ? 'active' : '' ?>">All</a>
                    <a href="<?php echo site_url('income/withdraws_list/Un-Paid')?>" class="btn btn-sm btn-info <?= ($typee == 'Un-Paid') ? 'active' : '' ?>">Un-Paid / Pending</a>
                    <a href="<?php echo site_url('income/withdraws_list/Hold')?>" class="btn btn-sm btn-warning <?= ($typee == 'Hold') ? 'active' : '' ?>">Hold</a>
                    <a href="<?php echo site_url('income/withdraws_list/Paid')?>" class="btn btn-sm btn-success <?= ($typee == 'Paid') ? 'active' : '' ?>">Paid / Transferred</a>
                    <a href="<?php echo site_url('income/withdraws_list/Reject')?>" class="btn btn-sm btn-danger <?= ($typee == 'Reject' || $typee == 'Rejected') ? 'active' : '' ?>">Rejected</a>
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
                    <button type="button" class="btn btn-success mb-3" onclick="submitForm('Paid')">Mark Selected as Paid</button>
                    <button type="button" class="btn btn-warning mb-3" onclick="submitForm('Hold')">Hold Selected</button>
                    <a href="<?= site_url('cron/newcron2'); ?>"  class="btn btn-info mb-3" onclick="return confirm('Are you sure you want to run income calculation?');">Calculate Incomes</a>
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
                            <th scope="col">User ID</th>
                            <th scope="col">Name</th>
                            <th scope="col">Phone</th>
                            <th scope="col">Gross Amount</th>
                            <th scope="col">Admin Fee (<?php echo config_item('admin_charges').'%' ?>)</th>
                            <th scope="col">TDS (<?php echo config_item('payout_tax').'%' ?>)</th>
                            <th scope="col">Net Payable</th>
                            <th scope="col">Bank / UPI</th>
                            <th scope="col">Account No / UPI ID</th>
                            <th scope="col">IFSC</th>
                            <th scope="col">Date</th>
                            <th scope="col">Status / Details</th>
                            <th scope="col">Action</th>
                        </tr>
                        </thead>
                        <tbody>
                        <?php
                            $sn = 1;
                            $totalGross = 0;
                            $totalAdminCharge = 0;
                            $totalTds = 0;
                            $totalNet = 0;
                            
                            foreach ($data as $e) {
                                $bank_data   = $this->db_model->select_multi('*', 'member_profile', array('userid' => $e->userid));
                                $user_data   = $this->db_model->select_multi('*', 'member', array('id' => $e->userid));
                                
                                $gross = floatval($e->amount);
                                $admin_pct = floatval(config_item('admin_charges'));
                                $tds_pct   = floatval(config_item('payout_tax'));
                                
                                $admin_charge_amount = isset($e->admin_tax) && floatval($e->admin_tax) > 0 ? floatval($e->admin_tax) : round(($gross * $admin_pct) / 100.0, 2);
                                $tds_amount          = isset($e->tds_tax) && floatval($e->tds_tax) > 0 ? floatval($e->tds_tax) : round(($gross * $tds_pct) / 100.0, 2);
                                $main_amount         = isset($e->net_paid) && floatval($e->net_paid) > 0 ? floatval($e->net_paid) : round($gross - $admin_charge_amount - $tds_amount, 2);
                                
                                $totalGross += $gross;
                                $totalAdminCharge += $admin_charge_amount;
                                $totalTds += $tds_amount;
                                $totalNet += $main_amount;
                        ?>
                        <tr>
                            <?php if($typee == 'Un-Paid' or $typee == 'Hold'){ ?>
                            <td>
                                <input type="checkbox" class="rowCheckbox" value="<?php echo $e->id; ?>">
                            </td>
                            <?php } ?>
                            <td><?php echo $sn++; ?></td>
                            <td><strong><?php echo config_item('ID_EXT') . (!empty($bank_data->userid) ? $bank_data->userid : $e->userid); ?></strong></td>
                            <td><?php echo !empty($user_data->name) ? $user_data->name : 'N/A'; ?></td>
                            <td><?php echo !empty($user_data->phone) ? $user_data->phone : 'N/A'; ?></td>
                            <td><strong><?php echo config_item('currency') . number_format($gross, 2); ?></strong></td>
                            <td><?php echo config_item('currency') . number_format($admin_charge_amount, 2); ?></td>
                            <td><?php echo config_item('currency') . number_format($tds_amount, 2); ?></td>
                            <td><strong class="text-success"><?php echo config_item('currency') . number_format($main_amount, 2); ?></strong></td>
                            <td><?php echo (!empty($bank_data->bank_name)) ? $bank_data->bank_name : (isset($e->withdraw_in) && $e->withdraw_in == 'upi' ? 'UPI' : 'Bank'); ?></td>
                            <td><?php echo (!empty($bank_data->bank_ac_no)) ? $bank_data->bank_ac_no : (!empty($bank_data->upi_id) ? $bank_data->upi_id : '<span class="text-danger">Not Provided</span>'); ?></td>
                            <td><?php echo (!empty($bank_data->bank_ifsc)) ? $bank_data->bank_ifsc : '-'; ?></td>
                            <td><?php echo $e->date ?></td>
                            <td>
                                <?php if($e->status == 'Paid'){ ?>
                                    <span class="badge badge-success">Paid</span>
                                    <?php if(!empty($e->tid)){ ?><br><small class="text-muted">TID: <?= $e->tid ?></small><?php } ?>
                                <?php } elseif($e->status == 'Hold'){ ?>
                                    <span class="badge badge-warning">Hold</span>
                                    <?php if(!empty($e->hold_reason)){ ?><br><small class="text-danger"><?= $e->hold_reason ?></small><?php } ?>
                                <?php } elseif($e->status == 'Reject' || $e->status == 'Rejected'){ ?>
                                    <span class="badge badge-danger">Rejected</span>
                                    <?php if(!empty($e->reject_reason)){ ?><br><small class="text-danger"><?= $e->reject_reason ?></small><?php } ?>
                                <?php } else { ?>
                                    <span class="badge badge-info"><?= $e->status ?></span>
                                <?php } ?>
                            </td>
                            <td>
                                <?php if($e->status == 'Un-Paid' || $e->status == 'Pending'){ ?>
                                    <a href="javascript:void(0)" onclick="payPayment('<?php echo $e->id ?>')" class="btn text-white btn-success btn-sm" title="Approve & Pay">Transfer</a>
                                    <a href="javascript:void(0)" onclick="holdPayment('<?php echo $e->id ?>')" class="btn btn-warning btn-sm" title="Put on Hold">Hold</a>
                                    <a href="javascript:void(0)" onclick="rejectPayment('<?php echo $e->id ?>')" class="btn btn-danger btn-sm" title="Reject & Refund to Wallet">Reject</a>
                                <?php } elseif($e->status == 'Hold'){ ?>
                                    <a href="javascript:void(0)" onclick="payPayment('<?php echo $e->id ?>')" class="btn text-white btn-success btn-sm">Transfer</a>
                                    <a href="<?php echo site_url('income/unhold/' . $e->id) ?>" class="btn btn-info btn-sm">Un-Hold</a>
                                    <a href="javascript:void(0)" onclick="rejectPayment('<?php echo $e->id ?>')" class="btn btn-danger btn-sm">Reject</a>
                                <?php } else { ?>
                                    <span class="text-muted font-size-12">Completed</span>
                                <?php } ?>
                            </td>
                        </tr>
                        <?php } ?>
                        </tbody>
                        <!-- Table Footer for Totals -->
                        <tfoot>
                            <tr style="background:#f4f6f9; font-weight:bold;">
                                <td colspan="<?= ($typee == 'Un-Paid' or $typee == 'Hold') ? '5' : '4' ?>" class="text-right">Total:</td>
                                <td><?php echo config_item('currency') . number_format($totalGross, 2); ?></td>
                                <td><?php echo config_item('currency') . number_format($totalAdminCharge, 2); ?></td>
                                <td><?php echo config_item('currency') . number_format($totalTds, 2); ?></td>
                                <td class="text-success"><?php echo config_item('currency') . number_format($totalNet, 2); ?></td>
                                <td colspan="5"></td>
                            </tr>
                        </tfoot>
                    </table>
                </form>
            </div>
        </div>
        <div class="card-footer">
            <a href="<?php echo site_url('income/view-earning') ?>" class="btn btn-sm btn-primary">&larr; Go to Earning Records</a>
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

<script>
    function submitForm(status) {
        const selectedCheckboxes = document.querySelectorAll('.rowCheckbox:checked');
        const selectedIds = Array.from(selectedCheckboxes)
            .map(checkbox => checkbox.value)
            .join(',');

        if (!selectedIds) {
            Swal.fire('Notice', 'Please select at least one record.', 'warning');
            return;
        }

        Swal.fire({
            title: 'Confirm Action',
            text: `Are you sure you want to mark selected payout(s) as ${status}?`,
            icon: 'question',
            showCancelButton: true,
            confirmButtonText: 'Yes, proceed'
        }).then((result) => {
            if (result.isConfirmed) {
                document.getElementById('selectedIds').value = selectedIds;
                document.getElementById('status').value = status;
                document.getElementById('bulkActionForm').submit();
            }
        });
    }

    document.addEventListener('DOMContentLoaded', function () {
        const selectAllCheckbox = document.getElementById('selectAllCheckbox');
        if (selectAllCheckbox) {
            selectAllCheckbox.addEventListener('change', function () {
                const rowCheckboxes = document.querySelectorAll('.rowCheckbox');
                rowCheckboxes.forEach(checkbox => {
                    checkbox.checked = selectAllCheckbox.checked;
                });
            });
        }
    });

    function holdPayment(id) {
        Swal.fire({
            title: 'Hold Payment',
            input: 'textarea',
            inputLabel: 'Reason for Hold',
            inputPlaceholder: 'Enter reason for hold...',
            showCancelButton: true,
            confirmButtonText: 'Hold Now',
            confirmButtonColor: '#f1b44c',
            preConfirm: (reason) => {
                if (!reason) {
                    Swal.showValidationMessage('Reason is required');
                }
                return reason;
            }
        }).then((result) => {
            if (result.isConfirmed) {
                $.post("<?= site_url('income/hold_ajax') ?>", { id: id, reason: result.value }, function(res) {
                    if (res && res.status === 'success') {
                        Swal.fire('Success', res.message, 'success').then(() => {
                            location.reload();
                        });
                    } else {
                        Swal.fire('Error', (res && res.message) ? res.message : 'Something went wrong', 'error');
                    }
                }, 'json')
                .fail(function(xhr) {
                    console.error("AJAX Error:", xhr.responseText);
                    Swal.fire('Error', 'Invalid server response', 'error');
                });
            }
        });
    }

    function rejectPayment(id) {
        Swal.fire({
            title: 'Reject Withdrawal',
            text: 'This will reject the request and immediately refund the full requested amount back to the member\'s wallet.',
            input: 'textarea',
            inputLabel: 'Rejection Reason',
            inputPlaceholder: 'Enter reason for rejection (e.g. Incorrect bank details)...',
            icon: 'warning',
            showCancelButton: true,
            confirmButtonText: 'Reject & Refund',
            confirmButtonColor: '#f46a6a',
            preConfirm: (reason) => {
                if (!reason) {
                    Swal.showValidationMessage('Rejection reason is required');
                }
                return reason;
            }
        }).then((result) => {
            if (result.isConfirmed) {
                $.post("<?= site_url('income/reject_ajax') ?>", { id: id, reason: result.value }, function(res) {
                    if (res && res.status === 'success') {
                        Swal.fire('Refunded', res.message, 'success').then(() => {
                            location.reload();
                        });
                    } else {
                        Swal.fire('Error', (res && res.message) ? res.message : 'Something went wrong', 'error');
                    }
                }, 'json')
                .fail(function(xhr) {
                    console.error("AJAX Error:", xhr.responseText);
                    Swal.fire('Error', 'Invalid server response', 'error');
                });
            }
        });
    }

    function payPayment(id) {
        Swal.fire({
            title: 'Approve & Mark Transferred',
            input: 'textarea',
            inputPlaceholder: 'Transaction ID / Bank Ref No (optional)...',
            icon: 'question',
            showCancelButton: true,
            confirmButtonText: 'Confirm Transfer',
            confirmButtonColor: '#34c38f'
        }).then((result) => {
            if (result.isConfirmed) {
                $.post("<?= site_url('income/pay_ajax') ?>", { id: id, detail: result.value || '' }, function(res) {
                    if (res && res.status === 'success') {
                        Swal.fire('Transferred', res.message, 'success').then(() => {
                            location.reload();
                        });
                    } else {
                        Swal.fire('Error', (res && res.message) ? res.message : 'Something went wrong', 'error');
                    }
                }, 'json')
                .fail(function(xhr) {
                    console.error("AJAX Error:", xhr.responseText);
                    Swal.fire('Error', 'Invalid server response', 'error');
                });
            }
        });
    }
</script>