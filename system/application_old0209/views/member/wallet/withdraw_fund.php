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
                                                <form action="<?php echo site_url('wallet/withdraw_payouts') ?>" method="POST">
                                                  
                                                    <div class="col-lg-3 col-md-4">
                                                        <div class="mb-3">
                                                             <input type="text" name="amount" required class="form-control form-control-alternative" placeholder="Minimum Withdrawal Amount Is<?= config_item('min_withdraw') ?>">

                                                        </div>
                                                    </div>

                                                     <div class="col-lg-3 col-md-4">
                                                        <div class="mb-3">
                                                             <select class="form-control form-control-alternative" name="pay_type">
                                                                 <option value="other">In Bank Account</option>
                                                                <?php  if (config_item('wallet_type')!=="No"){ ?>
                                                                    <option value="<?php echo $this->session->user_id ?>">Self Product Wallet</option>
                                                                <?php } ?> 
                                                                   <option value="upi">UPI</option>
                                                            </select>
                                                        </div>
                                                    </div>

                                                    <!--<div class="col-lg-3 col-md-4">-->
                                                    <!--    <div class="mb-3">-->
                                                    <!--      <input type="password" class="form-control" placeholder="Enter Transaction Password" name="trans_password">-->
                                                    <!--    </div>-->
                                                    <!--</div> -->

                                                      <div class="col-lg-4 col-md-6">
                                                        <div class="mb-3">
                                                            <button class="btn btn-primary waves-effect btn-label waves-light" type="submit" name="submit"><i class="bx bx-smile label-icon"></i>Withdraw request</button>
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
