                    <div class="row">
                            <div class="col-lg-12"> 
                                <div class="card">
                                    <div class="card-header">
                                        <h4 class="card-title"><?php echo $title ?></h4>
                                        <p class="card-title-desc">You can add money using E Pin or Payment Gateway</p>
                                    </div>
                                    <!-- end card header -->

                                    <div class="card-body">
                                            <div class="row">
                                                <form action="<?php echo site_url('wallet/topup_epin_wallet') ?>" method="POST">
                                                    <div class="col-md-2 col-md-3">
                                                        <div class="mb-3">
                                                           <input class="form-check-input" type="radio" name="paytype" value="epin" checked> E-Pin
                                                           <!--<input class="form-check-input" type="radio"  name="paytype" value="pgateway"> Payment Gateway-->
                                                       </div>
                                                    </div>
                                                    <div class="col-lg-3 col-md-4">
                                                        <div class="mb-3">
                                                            <input type="text" class="form-control" name="epin" placeholder="Enter valid ePin" required="">
                                                        </div>
                                                    </div>
                                                    <div class="col-lg-3 col-md-4">
                                                        <div class="mb-3">
                                                           <select class="form-select" name="addTo" required>
                                                            <option value="" disabled>Select</option>
                                                            <option value="toMain" selected>To Main Wallet</option>
                                                            <!--<option value="toProdW">To Product Wallet</option>-->
                                                        </select>
                                                        </div>
                                                    </div>

                                                      <div class="col-lg-4 col-md-6">
                                                        <div class="mb-3">
                                                            <button class="btn btn-primary waves-effect btn-label waves-light" type="submit"><i class="bx bx-smile label-icon"></i> Add Money to wallet</button>
                                                          
                                                        </div>
                                                    </div>   
                                                </form>
                                            </div>
                                     
                                            <!-- end row -->


                                    </div>
                                    <!-- end card body -->
                                </div>
                                <!-- end card -->
                            </div>
                            <!-- end col -->
                   </div>
