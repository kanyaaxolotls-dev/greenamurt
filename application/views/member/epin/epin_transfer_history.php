
                        <!-- start page title -->
                        <div class="row">
                            <div class="col-12">
                                <div class="page-title-box d-sm-flex align-items-center justify-content-between">
                                    <h4 class="mb-sm-0 font-size-18"><?php echo $title ?> </h4>

                                    <div class="page-title-right">
                                        <ol class="breadcrumb m-0">
                                            <li class="breadcrumb-item"><a href="javascript: void(0);">Epin</a></li>
                                            <li class="breadcrumb-item active">Epin Transfer History</li>
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
                                                                <p class="card-title-desc">This is data for my <?php echo $title ?>
                                                                   
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
                                                              
                                                                <th class="align-middle">Sn.</th>
                                                                <th class="align-middle">Tranfer Time</th>
                                                                <th class="align-middle">Epin</th>
                                                                <th class="align-middle">E-Pin Value</th>
                                                                <th class="align-middle">Tranfer To</th>
                                                                <th class="align-middle">E-Pin Type</th>
                                                                <th class="align-middle">Status</th>
                                                            </tr>
                                                            </thead>
                        
                                                            <tbody>

                                                                 <!-- The text field -->
                                                                
                                                                 <?php
                                                                    $sn = 1;

                                                                    foreach ($epin as $e) { ?>
                                                                        <tr>
                                                                            <td><?php echo $sn++; ?></td>
                                                                            <td><?php echo $e['transfer_time']; ?></td>
                                                                            <td><?php echo $e['epin']; ?></td>
                                                                            <td><?php echo $e['amount']; ?></td>
                                                                            <td><?php echo config_item('ID_EXT') . $e['issue_to']; ?></td>
                                                                            
                                                                            <td><?php echo $e['type']; ?></td>
                                                                           <td><?php echo $e['status']; ?></td>
                                                                        </tr>
                                                                    <?php } ?>
                                                           
                                                            </tbody>
                                                        </table>
                                                    </div>
                                                </div>
                                                <!-- end cardaa -->
                                            </div> <!-- end col -->
                                        </div> <!-- end row -->


            <script type="text/javascript">
            function copyTextPin() {
              /* Get the text field */
              var copyText = document.getElementById("myInput");

              /* Select the text field */
              copyText.select();
              copyText.setSelectionRange(0, 99999); /* For mobile devices */

               /* Copy the text inside the text field */
              navigator.clipboard.writeText(copyText.value);

              /* Alert the copied text */
              alert("Copied epin " + copyText.value);

            } 
            </script>