                <div class="container-fluid">

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
                                                                <h4 class="card-title">My Purchase History</h4>
                                                                <p class="card-title-desc">This is data for my orders history
                                                                   
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
                                                                <th class="align-middle">Order ID</th>
                                                                <th class="align-middle">Cusotmer Name</th>
                                                                <th class="align-middle">Date</th>
                                                                <th class="align-middle">Total</th>
                                                                <th class="align-middle">Payment Status</th>
                                                                <th class="align-middle">Payment Method</th>
                                                                <th class="align-middle">Order Status</th>
                                                                <!--<th class="align-middle">Address</th>
                                                                <th class="align-middle">Action</th>-->
                                                            </tr>
                                                            </thead> 
                        
                                                            <tbody>
                                                                <?php foreach ($purchase_data as $dt) { ?>
                                                                <tr>
                                                                    <td>
                                                                        <div class="form-check font-size-16">
                                                                            <input class="form-check-input" type="checkbox" id="orderidcheck01">
                                                                            <label class="form-check-label" for="orderidcheck01"></label>
                                                                        </div>
                                                                    </td>
                                                                   <td><a href="javascript: void(0);" class="text-body fw-bold"># <?php echo $dt['orderid']; ?></a> </td>
                                                                    <td><?php echo $dt['name']; ?></td>
                                                                    <td> 
                                                                        <?php echo $dt['date']; ?>
                                                                    </td>
                                                                    <td>
                                                                       <i class="fas fa-rupee-sign me-1"></i> <?php echo $dt['cost']; ?>
                                                                    </td>
                                                                    <td>
                                                                        <span class="badge badge-pill badge-soft-success font-size-12"><?php echo $dt['payment_sataus']; ?></span>
                                                                    </td>
                                                                    <td>
                                                                        <i class="fas fa-money-bill me-1"></i> <?php echo $dt['payment_method']; ?>
                                                                    </td>
                                                                    <td>
                                                                        <i class="fas fa-info me-1"></i> <?php echo $dt['status']; ?>
                                                                    </td>
                                                                     <!-- <td>
                                                                        <i class="fas fa-map-marker-alt"></i> <?php echo $dt['address']; ?>
                                                                    </td>-->

                                                                    <!-- <td> -->
                                                                        <!-- Button trigger modal -->
                                                                      <!--   <button type="button" class="btn btn-primary btn-sm btn-rounded" data-bs-toggle="modal" data-bs-target=".orderdetailsModal">
                                                                            View Details
                                                                        </button> -->
                                                                    <!-- </td> -->
                                                                    <!--<td>
                                                                        <div class="d-flex gap-3">

                                                                           <a href="<?php echo site_url('member/invoice_view/' . $dt['orderid']); ?>" class="text-success"><i class="mdi mdi-printer-eye font-size-18"></i></a>
                                                                            <a href="javascript:void(0);" class="text-success"><i class="mdi mdi-pencil font-size-18"></i></a>
                                                                            <a href="javascript:void(0);" class="text-danger"><i class="mdi mdi-delete font-size-18"></i></a>
                                                                        </div>
                                                                    </td>-->
                                                                </tr>
                                                               <?php } ?>
                                                           
                                                            </tbody>
                                                        </table>
                                                    </div>
                                                </div>
                                                <!-- end cardaa -->
                                            </div> <!-- end col -->
                                        </div> <!-- end row -->

                    </div> <!-- container-fluid -->
             
             