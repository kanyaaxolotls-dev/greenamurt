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
                                                <form action="<?php echo site_url('wallet/transfer_balance') ?>" method="POST">
                                                   <div class="col-md-2 col-md-3">
                                                        <div class="mb-3">
                                                           <input class="form-check-input" type="radio" name="paytype" value="wallet" checked> To Main Wallet
                                                           <input class="form-check-input" type="radio"  name="paytype" value="product_wallet"> To Repurchase Wallet
                                                       </div>
                                                    </div>
                                                    <div class="h2 w-100 text-right">
                                                      <small  id="spn_res" style="color: black;text-align:center;width:100%">User Name : </small>
                                                    </div>
                                                    <div class="col-lg-3 col-md-4">
                                                        <div class="mb-3">
                                                            <input type="text" class="form-control" name="transferid" placeholder="Enter User Id" required="" id="sponsor" oninput="get_user_name('#sponsor', '#spn_res')">
                                                        </div>
                                                    </div>

                                                     <div class="col-lg-3 col-md-4">
                                                        <div class="mb-3">
                                                            <input type="text" class="form-control" name="amount" placeholder="Enter Amount" required="">
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


 <script type="text/javascript">
    function get_user_name(id, result) {
        var myString = $(id).val();
        // let numbersOnly = myString.replace(/\D/g, "");
        var id =  myString.replace(/\D/g, "");
        $.get("<?php echo site_url('site/get_user_name/') ?>" + id, function (data) {
            $(result).html(data);
        });
    }
</script>