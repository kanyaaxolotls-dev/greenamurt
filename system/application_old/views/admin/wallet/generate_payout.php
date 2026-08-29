 <div class="col"> 
          <div class="card bg-secondary shadow">  
            <div class="card-header bg-white border-0"> 
              <div class="row align-items-center">
                <div class="col-lg-6">
                  <h3 class="mb-0">Generate Payout</h3>
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
                        <label>Select Payout Type</label>
                         <select class="form-control" name="pay_type">
                                <option value="all">All Payout</option>
                                <option value="binary_roi">Binary ROI</option> 
                                <option value="roi">ROI</option>
                                <option value="repurchase">REPURCHASE</option>
                                <option value="binary">BINARY</option>
                                <option value="sponsor">Sponsor Income</option>
                              
                                <option value="profit">Profit Income</option>
                                <?php if(config_item('rank_bonus')=="Yes"){ ?>
                                <option value="rank_bonus">Rank Bonus</option>
                                
                           
                                <?php } ?> 

                        </select> 
                    </div>
                    <div class="col-sm-4">
                        <label>Enter Payout Password</label>
                        <input type="password" required class="form-control" id="password" name="password">
                    </div>
                    <div class="col-sm-4">
                        <br/>
                        <button type="submit" onclick="return confirm('Are you sure ?')" type="submit" class="btn btn-success">Calculate
                        </button>
                    </div>
            
                </div>
           
          </div>
          <div class="card-footer">
              <!--     <strong>Note :</strong> Now you can generate payout for all members.<br> -->
                 <strong>Warning!</strong> This is direct update members/distributors wallet & can not be fully reversible. Make sure that you are going to make all members payout here.

            </div>
    <?php echo form_close() ?>
</div>


