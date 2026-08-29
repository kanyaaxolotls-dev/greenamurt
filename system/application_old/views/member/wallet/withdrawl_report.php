                    <div class="row">
                            <div class="col-12">
                                <div class="page-title-box d-sm-flex align-items-center justify-content-between">
                                    <h4 class="mb-sm-0 font-size-18"><?php echo $title ?></h4>

                                    <div class="page-title-right">
                                        <ol class="breadcrumb m-0">
                                            <li class="breadcrumb-item"><a href="javascript: void(0);">Member</a></li>
                                            <li class="breadcrumb-item active"><?php echo $title ?></li>
                                        </ol>
                                    </div>

                                </div>
                            </div>
                        </div>

                        <div class="row">
                                <div class="col-12">
                                        <div class="card">
                                                    <div class="card-header">
                                                        <div class="row mb-2">
                                                            <div class="col-sm-4">
                                                                <h4 class="card-title"><?php echo $title ?></h4>
                                                                <p class="card-title-desc">This is earning list
                                                                   
                                                                </p>
                                                            </div>
                                                            <div class="col-sm-8">
                                                                <div class="text-sm-end">
                                                                    <!-- <button type="button" class="btn btn-success btn-rounded waves-effect waves-light mb-2 me-2"><i class="mdi mdi-plus me-1"></i> Add New Order</button> -->
                                                                </div>
                                                            </div><!-- end col-->
                                                        </div>
                                                    </div>
                                                    <div class="card-body table-responsive">
                                                            <table id="datatable-buttons" class="table align-middle table-nowrap table-check">
                                                            <thead class="table-light">
                                                                <tr>
                                                                    <th style="width: 20px;" class="align-middle">
                                                                        <div class="form-check font-size-16">
                                                                            <input class="form-check-input" type="checkbox" id="checkAll">
                                                                            <label class="form-check-label" for="checkAll"></label>
                                                                        </div>
                                                                    </th>
                                                                    <th>S.N.</th> 
                                                                    <th>User ID</th>
                                                                    <th>Amount</th>
                                                                    <?php if(config_item('admin_charges') > 0){ ?>
                                                                    <th>Admin charges</th>
                                                                    <?php } ?>
                                                                    <th>TDS</th>
                                                                    <th>Net Payout</th>
                                                                    <!--<th>Type</th>-->
                                                                    <th>Date</th> 
                                                                    <th>Transaction Detail</th>
                                                                    <th>Paid Date</th> 
                                                                    <th>Status</th>
                                                                </tr>
                                                            </thead> 
                                                            <tbody>
                                                            <?php 
                                                                $sn = 1;
                                                                foreach ($withdraw_request as $we) {
                                                                    $admin_per  = config_item('admin_charges');
                                                                    $tds_per    = config_item('payout_tax');
                                                                    $admin_chrg = ($admin_per / 100) * $we['amount'];
                                                                    $tds_chrg   = ($tds_per / 100) * $we['amount'];
                                                                    $net_payout = $we['amount'] - ($admin_chrg + $tds_chrg);
                                                            ?>
                                                                <tr>
                                                                     <td>
                                                                        <div class="form-check font-size-16">
                                                                            <input class="form-check-input" type="checkbox" id="orderidcheck01">
                                                                            <label class="form-check-label" for="orderidcheck01"></label>
                                                                        </div>
                                                                    </td>
                                                                    <td><?php echo $sn++; ?></td>
                                                                    <td><?php echo $we['userid']; ?></td>
                                                                    <td><i class="fas fa-rupee-sign"></i> <?php echo $we['amount']; ?></td>
                                                                    <?php if(config_item('admin_charges') > 0){ ?>
                                                                    <td><?php echo $admin_chrg.' (<span class=text-danger>'.$admin_per.'%</span>)'; ?></td>
                                                                    <?php } ?>
                                                                    <td><?php echo $tds_chrg.' (<span class=text-danger>'.$tds_per.'%</span>)'; ?></td>
                                                                    <td><i class="fas fa-rupee-sign"></i> <?php echo $net_payout; ?></td>
                                                                    <!--<td><?php echo $we['type']; ?></td>-->
                                                                    <td><?php echo $we['date']; ?></td>
                                                                    <td><?php echo $we['tid']; ?></td>
                                                                    <td><?php if($we['paid_date'] !=''){?> 
                                                                        <?php echo $we['paid_date'];}else{ ?> <?php echo "Not yet";} ?>
                                                                    </td>
                                                                    <td>
                                                                        <button type="button" class="btn btn-sm btn-danger waves-effect waves-light">
                                                                            <i class="bx bx-loader bx-spin font-size-16 align-middle me-2"></i> <?php echo $we['status']; ?>
                                                                        </button>
                                                                    </td>
                                                                </tr>
                                                            <?php } ?>
                                                            </tbody>
                                                        </table>
                                                    </div>
                                                </div>
                                            </div> <!-- end col -->
                                        </div> <!-- end row -->

