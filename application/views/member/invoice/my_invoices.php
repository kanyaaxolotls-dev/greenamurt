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
                                                                    <th>Sn.</th>
                                                                    <th>Invoice Name</th>
                                                                    <th>Order ID</th>
                                                                    <th>Total Amt</th>
                                                                    <th>Paid Amt</th>
                                                                    <th>Date</th>
                                                                    <th>Actions</th>
                                                            </tr>
                                                            </thead> 
                        
                                                            <tbody> 
                                                                <?php
                                                                    $sn = 1;

                                                                    foreach ($invoice as $e) { ?>
                                                                        <tr>
                                                                             <td>
                                                                                <div class="form-check font-size-16">
                                                                                    <input class="form-check-input" type="checkbox" id="orderidcheck01">
                                                                                    <label class="form-check-label" for="orderidcheck01"></label>
                                                                                </div>
                                                                            </td>
                                                                            <td><?php echo $sn++; ?></td>
                                                                            <td><?php echo $e->invoice_name; ?></td>
                                                                            <td>#<?php echo $e->orderid; ?></td>
                                                                            <td><?php echo config_item('currency') . $e->total_amt; ?></td>
                                                                            <td><?php echo config_item('currency') . $e->paid_amt; ?></td>
                                                                            <td><?php echo $e->date; ?></td>
                                                                            <td>
                                                                                <a target="_blank" href="<?php echo site_url('member/invoice_view/' . $e->orderid); ?>" class="btn btn-success btn-sm"><i class="fa fa-print" aria-hidden="true"></i></a>
                                                                            </td>
                                                                        </tr>
                                                                    <?php } ?>
                                                            </tbody>
                                                        </table>
                                                    </div>
                                                </div>
                                                <!-- end cardaa -->
                                            </div> <!-- end col -->
                                        </div> <!-- end row -->
 
 
  