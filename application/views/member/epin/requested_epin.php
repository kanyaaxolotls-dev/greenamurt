
                        <!-- start page title -->
                        <div class="row">
                            <div class="col-12">
                                <div class="page-title-box d-sm-flex align-items-center justify-content-between">
                                    <h4 class="mb-sm-0 font-size-18">My Orders</h4>

                                    <div class="page-title-right">
                                        <ol class="breadcrumb m-0">
                                            <li class="breadcrumb-item"><a href="javascript: void(0);">Ecommerce</a></li>
                                            <li class="breadcrumb-item active">Orders</li>
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
                                                                <h4 class="card-title">My Requested Epins </h4>
                                                                <p class="card-title-desc">This is the data for my requested E-PINs history.
                                                                   
                                                                </p>
                                                            </div>
                                                            <div class="col-sm-8">
                                                                <div class="text-sm-end">
                                                                  <!--   <button type="button" class="btn btn-success btn-rounded waves-effect waves-light mb-2 me-2"><i class="mdi mdi-plus me-1"></i> Add New Order</button> -->
                                                                </div>
                                                            </div><!-- end col-->
                                                        </div>
                                                    </div>
                                                    <div class="card-body table-responsive">
                                                            <table id="datatable-buttons" class="table align-middle table-nowrap table-check">
                                                             <thead class="table-light">
                                                          
                                                            <tr>
                                                                <th class="align-middle">SN</th>
                                                                <th class="align-middle">Epin Number</th>
                                                                <th class="align-middle">Amount</th>
                                                                <th class="align-middle">Payment Method</th>
                                                                <th class="align-middle">Image</th>
                                                                <th class="align-middle">Status</th>
                                                                <th class="align-middle">Date</th>
                                                                
                                                            </tr>
                                                            </thead>
                        
                                                            <tbody>
                                                                 <?php
                                                                    $sn = 1;
                                                                    foreach ($epin as $e) { ?>
                                                                        <tr>
                                                                            <td><?php echo $sn++; ?></td>
                                                                            <td><?php echo $e['epins']; ?></td>
                                                                            <td><?php echo $e['amount']; ?></td>
                                                                            <td><?php echo $e['payment_method']; ?></td>
                                                                            <td>
                                                                                <?= !empty($e['file'])
                                                                                    ? '<a href="' . base_url('uploads/' . $e['file']) . '" target="_blank">View File</a>'
                                                                                    : 'NA' ?>
                                                                            </td>

                                                                            <td>
                                                                                <?= $e['status'] == 0
                                                                                    ? '<span class="badge bg-danger">Pending</span>'
                                                                                    : '<span class="badge bg-success">Accepted</span>' ?>
                                                                            </td>

                                                                            <td><?php echo $e['date']; ?></td>
                                                                        </tr>
                                                                    <?php } ?>
                                                            </tbody>
                                                        </table>
                                                    </div>
                                                </div>
                                                <!-- end cardaa -->
                                            </div> <!-- end col -->
                                        </div> <!-- end row -->

