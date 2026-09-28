<div class="container-fluid">

    <!-- start page title -->
    <div class="row">
        <div class="col-12">
            <div class="page-title-box d-sm-flex align-items-center justify-content-between">
                <h4 class="mb-sm-0 font-size-18"><?php echo $title; ?></h4>

                <div class="page-title-right">
                    <ol class="breadcrumb m-0">
                        <li class="breadcrumb-item"><a href="javascript: void(0);">Wallet</a></li>
                        <li class="breadcrumb-item active"><?php echo $title; ?></li>
                    </ol>
                </div>

            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-12">
            <div class="card shadow-sm border-0">
                <div class="card-header bg-white border-bottom">
                    <div class="row align-items-center">
                        <div class="col-sm-6">
                            <h4 class="card-title mb-1"><?php echo $title; ?></h4>
                            <p class="card-title-desc text-muted mb-0">Detailed ledger of all credits, debits, refunds, and earnings in your E-Wallet.</p>
                        </div>
                        <div class="col-sm-6 text-sm-end">
                            <a href="<?php echo site_url('wallet/withdraw_fund'); ?>" class="btn btn-primary btn-sm waves-effect waves-light">
                                <i class="bx bx-wallet font-size-14 align-middle me-1"></i> E-Wallet Dashboard
                            </a>
                        </div>
                    </div>
                </div>
                <div class="card-body table-responsive">
                    <table id="datatable-buttons" class="table table-hover align-middle table-nowrap">
                        <thead class="table-light">
                            <tr>
                                <th class="align-middle" style="width: 50px;">#</th>
                                <th class="align-middle">Transaction Type</th>
                                <th class="align-middle">Amount</th>
                                <th class="align-middle">Reference ID</th>
                                <th class="align-middle">Description / Remarks</th>
                                <th class="align-middle">Date & Time</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php 
                            $sn = 1;
                            if (!empty($w_tras)) {
                                foreach ($w_tras as $wt) { 
                                    $is_credit = (strcasecmp($wt->type, 'Credit') === 0);
                            ?>
                            <tr>
                                <td><?php echo $sn++; ?></td>
                                <td>
                                    <?php if ($is_credit) { ?>
                                        <span class="badge bg-success font-size-12 px-2 py-1"><i class="bx bx-down-arrow-alt me-1"></i> Credit</span>
                                    <?php } else { ?>
                                        <span class="badge bg-danger font-size-12 px-2 py-1"><i class="bx bx-up-arrow-alt me-1"></i> Debit</span>
                                    <?php } ?>    
                                </td>
                                <td class="fw-bold <?php echo $is_credit ? 'text-success' : 'text-danger'; ?> fs-6">
                                    <?php echo $is_credit ? '+' : '-'; ?><i class="fas fa-rupee-sign ms-1"></i> <?php echo number_format((float)$wt->amount, 2); ?>
                                </td>
                                <td><span class="badge bg-light text-dark font-monospace"><?php echo !empty($wt->ref_id) ? htmlspecialchars($wt->ref_id) : 'TXN-'.$wt->id; ?></span></td>
                                <td><span class="text-secondary"><?php echo htmlspecialchars($wt->other); ?></span></td>
                                <td><i class="bx bx-time-five text-muted me-1"></i><?php echo $wt->created_date; ?></td>
                            </tr>
                            <?php 
                                } 
                            } else { ?>
                            <tr>
                                <td colspan="6" class="text-center py-4 text-muted">
                                    <i class="bx bx-receipt font-size-24 d-block mb-2 text-secondary"></i>
                                    No transactions found in your wallet history.
                                </td>
                            </tr>
                            <?php } ?>
                        </tbody>
                    </table>
                </div>
            </div>
            <!-- end card -->
        </div> <!-- end col -->
    </div> <!-- end row -->

</div> <!-- container-fluid -->