
<?php echo form_open_multipart() ?>

    <div class="card bg-secondary shadow fadein">  
        <div class="card-header bg-white border-0"> 
                <div class="row align-items-center">
                    <div class="col-9">
                          <h4 class="mb-0"><?php echo $title ?></h4>
                    </div>
             <!--    <div class="col-3 text-right"> 
                    <a href="<?php //echo site_url('member/index')?>" class="btn btn-sm btn-primary">Home</a>
                </div> -->
            </div>
        </div>
    <div class="card-body">
        <div class="row">
            <div class="col-sm-4">
                <label>Company Name</label>
                <div class="form-group">
                    <input type="text" class="form-control form-control-alternative" value=""
                           placeholder="Company Name" name="name">
                </div>
            </div>
            <div class="col-sm-4">
                <label>Address</label>
                <div class="form-group">
                    <input type="textarea" value="address" class="form-control form-control-alternative" placeholder="Address" name="address">
                </div>
            </div>
              <div class="col-sm-4">
                <label>State</label>
                <div class="form-group">
                    <input type="text" value="state" class="form-control form-control-alternative" placeholder="state" name="state">
                </div>
            </div>
              <div class="col-sm-4">
                <label>Country</label>
                <div class="form-group">
                    <input type="text" value="country" class="form-control form-control-alternative" placeholder="country" name="country">
                </div>
            </div>

        
            <div class="col-sm-4">
                <label>Email</label>
                <div class="form-group">
                    <input type="email" value="email" placeholder="Email"
                           class="form-control form-control-alternative" name="email">
                </div>
            </div>
            <div class="col-sm-4">
                <label>Phone</label>
                <div class="form-group">
                    <input type="text" value="phone" placeholder="Phone Number"
                           class="form-control form-control-alternative" name="phone">
                </div>
            </div>
             <div class="col-sm-4">
                <label>Franchisee Username</label>
                <div class="form-group">
                    <input type="text" value="username" placeholder="Franchisee Username"
                           class="form-control form-control-alternative" name="username">
                </div>
            </div>
              <div class="col-sm-4">
                <label>Add Logo</label>
                <input type="file" name="img">
            </div>

           
        </div>
        <div class="row">
             <div class="col-sm-4"><br>
               <div class="form-group">
                   <input type="submit" class="btn btn-primary  btn-block" value="submit">
                </div>
            </div>
        </div>
    </div>
 </div>
</div>  
</div>  
<?php echo form_close() ?>
