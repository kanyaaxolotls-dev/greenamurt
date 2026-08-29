
 <div class="col">
    <div class="card bg-secondary shadow">  
        <div class="card-header bg-white border-0"> 
            <div class="row align-items-center">
                <div class="col-8">
                      <h3 class="mb-0">Transfer fund to User Wallet</h3>
                </div>
                <div class="col-4 text-right"> 
                    <a href="<?php echo site_url('admin')?>" class="btn btn-sm btn-primary">Home</a>
                </div>
            </div>
        </div>
        <div class="card-body"> 
              <?php echo form_open() ?>
                <div class="pl-lg-4">
                      <div class="row">
                        <div class="col-sm-6">
                            <label>To User Id</label>
                            <input type="text" class="form-control form-control-alternative" id="uid" name="userid">
                        </div>
                        <div class="col-sm-6">
                            <br/>
                            <button type="button" onclick="populate()" class="btn btn-primary">GET WALLET FUND</button>
                        </div>
                    </div>

                    <div class="row" id="hidden" style="display: none">
                        <div class="col-sm-6"> Available Wallet Balance: <?php echo config_item('currency') ?> <span id="qty"></span>
                           
                            <p style="margin-top:1px;">
                                <label>Enter Amount:</label>
                                <input type="text" name="amount" required class="form-control form-control-alternative" value="1"><br/>
                                <label>Transfer to User ID:</label>
                                <input type="text" name="transferid" required class="form-control form-control-alternative"><br/>
                                <button class="btn btn-primary" name="submit" value="add">Transfer</button>
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

