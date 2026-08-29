                <div class="container-fluid">

                        <!-- start page title -->
                        <div class="row">
                            <div class="col-12">
                                <div class="page-title-box d-sm-flex align-items-center justify-content-between">
                                    <h4 class="mb-sm-0 font-size-18"><?php echo $title; ?></h4>

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
                                                                <h4 class="card-title"><?php echo $title; ?> </h4>
                                                                <p class="card-title-desc">This is data for my wallet history
                                                                   
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
                                                                <th style="width: 20px;" class="align-middle">
                                                                    <div class="form-check font-size-16">
                                                                        <input class="form-check-input" type="checkbox" id="checkAll">
                                                                        <label class="form-check-label" for="checkAll"></label>
                                                                    </div> 
                                                                </th>
                                                                <th class="align-middle">Sr</th>
                                                                <th class="align-middle">User ID</th>
                                                                <th class="align-middle">Type</th>
                                                                <th class="align-middle">Amount</th>
                                                                <th class="align-middle">Ref. Id</th>
                                                                <th class="align-middle">Details</th>
                                                                <th class="align-middle">Date</th>
                                                              
                                                            </tr>
                                                            </thead>
                        
                                                            <tbody>
                                                                <?php foreach ($w_tras as $wt) { ?>
                                                                <tr>
                                                                    <td>
                                                                        <div class="form-check font-size-16">
                                                                            <input class="form-check-input" type="checkbox" id="orderidcheck01">
                                                                            <label class="form-check-label" for="orderidcheck01"></label>
                                                                        </div>
                                                                    </td>
                                                                   <td><a href="javascript: void(0);" class="text-body fw-bold"># 
                                                                    <?php echo $wt->id; ?></a> </td>
                                                                    <td><?php echo $wt->userid; ?></td>
                                                                    <td>
                                                                        <?php if ($wt->type =='Debit'){ ?>
                                                                           <span class="badge badge-pill badge-soft-danger font-size-12">  <?php echo $wt->type; ?></span>
                                                                        <?php } else{ ?>
                                                                             <span class="badge badge-pill badge-soft-success font-size-12">  <?php echo $wt->type; ?></span>
                                                                        <?php } ?>    
                                                                       </td>
                                                                     <td>
                                                                       <i class="fas fa-rupee-sign me-1"></i> <?php echo $wt->amount; ?>
                                                                    </td>
                                                                    <td><?php echo $wt->ref_id; ?></td>
                                                                    <td><?php echo $wt->other; ?></td>
                                                                    <td>
                                                                        <?php echo $wt->created_date; ?>
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

                    </div> <!-- container-fluid -->
             
             