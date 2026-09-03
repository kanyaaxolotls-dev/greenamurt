
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
                                                <form action="<?php echo site_url('member/transfer_epin') ?>" method="POST">
                                                  
                                                    <div class="col-lg-3 col-md-4">
                                                        <div class="mb-3">
                                                            <input type="text" class="form-control" placeholder="Enter User Id " onchange="get_user_name('#to', '#spn_to')" value="<?php echo set_value('to') ?>" class="form-control" name="to" required="">
                                                        </div>
                                                    </div>

                                                     <div class="col-lg-3 col-md-4">
                                                        <div class="mb-3">
                                                           <!--  <input type="text" value="<?php echo set_value('amount') ?>"
                                                                class="form-control"  name="amount" placeholder="Enter Amount" required=""> -->
                                                             <select class="form-control form-control-alternative" id="amount" name="amount">
                                                                <?php foreach ($products as $val) {
                                                                    echo '<option value="' . $val['prod_price'] . '">' . $val['prod_name'] . '. Price :' . config_item('currency') . number_format($val['prod_price'] + ($val['prod_price'] * $val['gst'] / 100), 2) . ' </option>';
                                                                }?>
                                                            </select>
                                                        </div>
                                                    </div>
                                                     <div class="col-lg-3 col-md-4">
                                                        <div class="mb-3">
                                                            <input type="text" placeholder="How many epin to transfer" value="<?php echo set_value('qty') ?>" class="form-control" name="qty">
                                                        </div>
                                                    </div>
                                                    
                                                    <div class="col-lg-3 col-md-4">
                                                        <div class="mb-3">
                                                          <input type="password" class="form-control" placeholder="Enter Transaction Password" name="trans_password">
                                                        </div>
                                                    </div>

                                                      <div class="col-lg-4 col-md-6">
                                                        <div class="mb-3">
                                                            <button class="btn btn-primary waves-effect btn-label waves-light" type="submit" name="submit"><i class="bx bx-smile label-icon"></i>Transfer to Wallet</button>

                                                        </div>
                                                        If E-pin is issued To You Then only you can transfer it To another person. 
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


