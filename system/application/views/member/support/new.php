
    <div class=" shadow">   
        <div class="card-header bg-white border-0"> 
                <div class="row align-items-center">
                    <div class="col-12">
                          <h4 class="mb-0"><?php echo $title ?></h4>
                    </div>
            </div>
        </div>
           
      
        <div class="container p-4">
            <div class="form-group">
                  <?php echo form_open() ?>
                  <div class="row">
                    <div class="col-sm-12 col-md-12">
                        <label>Subject <span class="text-danger">*</span></label>
                        <!--<input type="text" class="form-control form-control-alternative" value="<?php echo set_value('ticket_title') ?>" name="ticket_title">-->
                       <select class="form-control form-control-alternative" id="ticket_title" name="ticket_title" required>
                            <option value="" selected disabled>Select support option</option>
                            <option value="Payout">Payout</option>
                            <option value="Online/Upi">KYC/profile update</option>
                            <option value="Products">Products</option>
                            <option value="Inquiry">Inquiry</option>
                            <option value="Franchisee">Franchisee</option>
                            <option value="Customer Service">Customer Service</option>
                        </select>
                    </div>
                    <div class="col-sm-12 col-md-12 mt-3">
                        <label>Describe your problem <span class="text-danger">*</span></label>
                        <textarea rows="10" class="form-control form-control-alternative" id="editor" name="ticket_data" placeholder="Describe your problem"></textarea>
                    </div>
                    <div class="col-sm-12 col-md-12 my-2">
                        <button type="submit" class="btn btn-primary">Submit</button>
                    </div>
                      </div>

                <?php echo form_close() ?>
            </div>
            
        </div>
  </div>
