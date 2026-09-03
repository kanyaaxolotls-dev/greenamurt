
<?php echo form_open() ?>

 <div class="col"> 
          <div class="card bg-secondary shadow">  
            <div class="card-header bg-white border-0"> 
              <div class="row align-items-center">
                <div class="col-10">
                  <h3 class="mb-0">Withdraw Fund Request</h3>
                </div>
                <div class="col-2 text-right">
                    <!--  <form method="post" action="<?php echo site_url('wallet/wallet_transactions') ?>">
                        <div class="form-group">
                            <input type="text" name="top_id" class="form-control" placeholder="User Id">
                        </div>
                        <div class="form-group">
                            <button class="btn btn-sm btn-success" type="submit">Search</button>
                        </div>
                    </form> -->
                    <a href="<?php echo site_url('admin')?>" class="btn btn-sm btn-primary">Home</a>

                </div>
              </div>
            </div>
            <div class="card-body">
            <div class="pl-lg-4">
                <div class="row">
                    <div class="col-sm-6">
                         <div class="form-group">
                            
                            <input type="text" class="form-control form-control-alternative" id="uid" name="userid" placeholder="Reciepient User Id">
                        </div>
                    </div>
                    <div class="col-sm-6">
                         <div class="form-group">
                            <button type="button" onclick="populate()" class="btn btn-primary btn-sm">Get Details</button>
                        </div>
                    </div>
                </div>
                <div class="row" id="hidden" style="display: none">
                    <div class="col-sm-6">
                        <strong style="color: #0cc745">Available Wallet Balance:<?php echo config_item('currency') ?> <span id="qty"></span></strong>
                        
                        <p style="margin-top:10px;">
                           <label class="form-control-label">Enter Amount to withdraw:</label>
                            <input type="text" name="amount" required class="form-control form-control-alternative" value="1"><br/>
                            <button  type="submit" class="btn btn-success" name="submit" value="add">Withdraw</button>
                        </p>
                    </div>
                </div>
                </div>
                </div>
            </div>
        </div>

                <script type="text/javascript">
                    function populate() {
                        var uid = $('#uid').val();
                        if (uid == "") {
                            alert('Please enter User ID');
                        } else {
                            $.get("<?php echo site_url('cron/get_wallet_balance/') ?>" + uid, function (data) {
                                $("#hidden").show('slow');
                                $("#qty").html(data);
                            });
                        }
                    }
                </script>
                <?php echo form_close() ?>

