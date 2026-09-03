

<!-- Multi wallet  system -->
<?php
// if (config_item('wallet_type') =="Yes"){

?>
   <div class="col"> 
          <div class="card bg-secondary shadow">  
            <div class="card-header bg-white border-0"> 
              <div class="row align-items-center">
                <div class="col-lg-6">
                  <h3 class="mb-0"><?= $title ?></h3>
                </div> 
                <div class="col-lg-6 text-right"> 
                    <a href="<?php echo site_url('admin')?>" class="btn btn-sm btn-primary">Home</a>
                </div>
              </div>
            </div> 
            <div class="card-body">
                <?php echo form_open() ?>
                
                <div class="row">
                    <div class="col-sm-4">
                        <label>Enter User ID</label>
                        <input type="text" class="form-control form-control-alternative" id="uid" name="uid">
                    </div>
                    <div class="col-sm-6">
                        <br/>
                        <button type="button" onclick="populate()" class="btn btn-success">Get balance</button>
                    </div>
                </div>
                <div class="row" id="hidden" style="display: none">
                   
                    <div class="col-sm-4">
                   
                      <h3><span  style="color: #32c861">Wallet Balance:
                          <i class="fa fa-rupee"></i> <span id="qty"></span></span>
                      </h3>
                       <h3><span  style="color: #32c861">name : 
                          <span id="username"></span></span>
                      </h3>
                       <label>Add or Deduct Balance:</label>
                       <input type="text" name="balance" required class="form-control" value="0"><br/>
                       <button class="btn btn-success" name="submit" value="add">Add</button>
                       <button class="btn btn-danger" name="submit" value="remove">Remove</button>
                     
                    </div> 
                </div>
                
                <script type="text/javascript">
                    function populate() {
                        var uid = $('#uid').val();
                        if (uid == "") {
                            alert('Please enter User ID');
                        } else {
                            // Retrieve wallet balance
                            $.get("<?php echo site_url('wallet/get_product_wallet_balance/') ?>" + uid, function (balance) {
                                $("#hidden").show('slow');
                                $("#qty").html(balance);
                            });
                
                            // Retrieve user name
                            $.get("<?php echo site_url('site/get_user_name/') ?>" + uid, function (name) {
                                $("#username").html(name); // Update the username span with the retrieved name
                            });
                        }
                    }
                </script>
                <?php echo form_close() ?>

            </div>
            <div class="card-footer">Note: Here you can ADD/REMOVE Wallet balance from members product wallet</div>
          </div>
        </div>
    

<?php 
// } 
?>


