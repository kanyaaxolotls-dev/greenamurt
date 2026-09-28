                    <div class="row">
                            <div class="col-12">
                                <div class="page-title-box d-sm-flex align-items-center justify-content-between">
                                    <h4 class="mb-sm-0 font-size-18"><?php echo $title ?></h4>

                                    <div class="page-title-right">
                                        <ol class="breadcrumb m-0">
                                            <li class="breadcrumb-item"><a href="javascript: void(0);">Wallet</a></li>
                                            <li class="breadcrumb-item active"><?php echo $title ?></li>
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
                                                                <h4 class="card-title mb-1"><?php echo $title ?></h4>
                                                                <p class="card-title-desc text-muted mb-0">Track all your withdrawal requests, deductions, and payout status.</p>
                                                            </div>
                                                            <div class="col-sm-6 text-sm-end">
                                                                <a href="<?php echo site_url('wallet/withdraw_fund'); ?>" class="btn btn-primary btn-sm waves-effect waves-light">
                                                                    <i class="bx bx-plus font-size-14 align-middle me-1"></i> New Withdrawal
                                                                </a>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="card-body table-responsive">
                                                            <table id="datatable-buttons" class="table table-hover align-middle table-nowrap">
                                                            <thead class="table-light">
                                                                <tr>
                                                                    <th class="align-middle">#</th>
                                                                    <th class="align-middle">Ref TID</th>
                                                                    <th class="align-middle">Gross Amount</th>
                                                                    <?php if(config_item('admin_charges') > 0){ ?>
                                                                    <th class="align-middle">Admin Fee</th>
                                                                    <?php } ?>
                                                                    <th class="align-middle">TDS Tax</th>
                                                                    <th class="align-middle">Net Payout</th>
                                                                    <th class="align-middle">Request Date</th> 
                                                                    <th class="align-middle">Paid/Processed Date</th> 
                                                                    <th class="align-middle">Status</th>
                                                                </tr>
                                                            </thead> 
                                                            <tbody>
                                                            <?php 
                                                                $sn = 1;
                                                                $admin_pct = (float)config_item('admin_charges');
                                                                $tds_pct   = (float)config_item('payout_tax');

                                                                foreach ($withdraw_request as $we) {
                                                                    $gross = (float)$we['amount'];
                                                                    $admin_chrg = isset($we['admin_tax']) && $we['admin_tax'] > 0 ? (float)$we['admin_tax'] : round(($gross * $admin_pct) / 100, 2);
                                                                    $tds_chrg   = isset($we['tds_tax']) && $we['tds_tax'] > 0 ? (float)$we['tds_tax'] : round(($gross * $tds_pct) / 100, 2);
                                                                    $net_payout = isset($we['net_paid']) && $we['net_paid'] > 0 ? (float)$we['net_paid'] : round($gross - $admin_chrg - $tds_chrg, 2);
                                                                    $status     = $we['status'];
                                                            ?>
                                                                <tr>
                                                                    <td><?php echo $sn++; ?></td>
                                                                    <td><span class="badge bg-light text-dark font-monospace"><?php echo !empty($we['tid']) ? $we['tid'] : 'WRQ-'.$we['id']; ?></span></td>
                                                                    <td class="fw-bold text-dark"><i class="fas fa-rupee-sign"></i> <?php echo number_format($gross, 2); ?></td>
                                                                    <?php if($admin_pct > 0){ ?>
                                                                    <td class="text-danger">
                                                                        -<i class="fas fa-rupee-sign"></i> <?php echo number_format($admin_chrg, 2); ?> 
                                                                        <small class="text-muted">(<?php echo $admin_pct; ?>%)</small>
                                                                    </td>
                                                                    <?php } ?>
                                                                    <td class="text-danger">
                                                                        -<i class="fas fa-rupee-sign"></i> <?php echo number_format($tds_chrg, 2); ?> 
                                                                        <small class="text-muted">(<?php echo $tds_pct; ?>%)</small>
                                                                    </td>
                                                                    <td class="fw-bold text-success fs-6"><i class="fas fa-rupee-sign"></i> <?php echo number_format($net_payout, 2); ?></td>
                                                                    <td><i class="bx bx-calendar text-muted me-1"></i><?php echo $we['date']; ?></td>
                                                                    <td>
                                                                        <?php if(!empty($we['paid_date'])){ ?> 
                                                                            <span class="text-success"><i class="bx bx-check-circle me-1"></i><?php echo $we['paid_date']; ?></span>
                                                                        <?php } else { ?> 
                                                                            <span class="text-muted fst-italic">Pending</span>
                                                                        <?php } ?>
                                                                    </td>
                                                                    <td>
                                                                        <?php if($status == 'Paid'){ ?>
                                                                            <span class="badge bg-success font-size-12 px-2 py-1"><i class="bx bx-check-double me-1"></i> Paid</span>
                                                                        <?php } elseif($status == 'Rejected'){ ?>
                                                                            <span class="badge bg-danger font-size-12 px-2 py-1" data-bs-toggle="tooltip" title="<?php echo !empty($we['reject_reason']) ? htmlspecialchars($we['reject_reason']) : 'Rejected by Admin (Refunded to Wallet)'; ?>">
                                                                                <i class="bx bx-x-circle me-1"></i> Rejected (Refunded)
                                                                            </span>
                                                                            <?php if(!empty($we['reject_reason'])){ ?>
                                                                                <div class="small text-danger mt-1"><i class="bx bx-info-circle me-1"></i><?php echo htmlspecialchars($we['reject_reason']); ?></div>
                                                                            <?php } ?>
                                                                        <?php } elseif($status == 'Hold'){ ?>
                                                                            <span class="badge bg-warning text-dark font-size-12 px-2 py-1"><i class="bx bx-pause-circle me-1"></i> On Hold</span>
                                                                        <?php } else { ?>
                                                                            <span class="badge bg-info font-size-12 px-2 py-1"><i class="bx bx-time-five me-1"></i> Processing</span>
                                                                        <?php } ?>
                                                                    </td>
                                                                </tr>
                                                            <?php } ?>
                                                            </tbody>
                                                        </table>
                                                    </div>
                                                </div>
                                            </div> <!-- end col -->
                                        </div> <!-- end row -->

