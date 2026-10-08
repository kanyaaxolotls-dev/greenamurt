    <div class="col"> 
          <div class="card bg-secondary shadow">  
            <div class="card-header bg-white border-0"> 
              <div class="row align-items-center">
                <div class="col-8">
                  <h3 class="mb-0"><?php echo $breadcum ?></h3>
                </div>
                <div class="col-4 text-right">
                  <a href="<?php echo site_url('admin')?>" class="btn btn-sm btn-primary">Home</a>
                </div>
              </div>
            </div>
            <div class="card-body">
                <?php echo form_open('users/search') ?>
                
                <div class="pl-lg-4">
                  <div class="row">
                    <div class="col-lg-6">
                      <div class="form-group">
                        <label class="form-control-label" for="input-username">Distributor ID</label>
                        <input type="text" class="form-control form-control-alternative" placeholder="Username" id="userid" name="userid">
                        
                      </div>
                    </div>
                    <div class="col-lg-6">
                      <div class="form-group">
                        <label>Phone No</label>
                        <input type="text" class="form-control form-control-alternative" placeholder="+91-" id="phone" name="phone">
                      </div>
                    </div>
                  </div>

                  <div class="row">
                    <div class="col-lg-6">
                      <div class="form-group">
                        <label>Join Start Date</label>
                        <input type="text" class="form-control" placeholder="dd/mm/YYYY" id="startdate" name="startdate">
                       
                      </div>
                    </div>

                    <div class="col-lg-6">
                      <div class="form-group">
                        <label>Join Start Date</label>
                        <input type="text" class="form-control" placeholder="dd/mm/YYYY" id="startdate" name="startdate">
                       
                      </div>
                    </div>
                    </div>
                    
                    <div class="row">
                        <div class="col-lg-6">
                          <div class="form-group">
                            <label>Email ID</label>
                            <input type="text" class="form-control form-control-alternative" id="email" name="email">
                          </div>
                        </div>
                        <div class="col-lg-6">
                          <div class="form-group">
                            <label>Sponsor ID</label>
                            <input type="text" class="form-control form-control-alternative" id="email" name="email">
                          </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-lg-6">
                            <input type="submit" class="btn btn-success" value="Search" onclick="this.value='Searching..'">
                        </div>
                    </div>
            </div>

            <?php echo form_close() ?>
        </div>
    </div>
</div>
