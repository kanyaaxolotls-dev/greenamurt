    <div class="col"> 
      <div class="row">
        <div class="col-xl-12 order-xl-1">
          <div class="card bg-secondary shadow">
            <div class="card-header bg-white border-0">
              <div class="row align-items-center">
                <div class="col-8">
                  <h3 class="mb-0">My account</h3>
                </div>
                <div class="col-4 text-right">
                  <a href="<?php echo site_url('users/edit_user/' .$data->id); ?>" class="btn btn-sm btn-primary">Update Profile</a>
                </div>
              </div>
            </div>
            <div class="card-body">
              
                <h6 class="heading-small text-muted mb-4">User information</h6>
                <div class="pl-lg-4">
                 
                  <div class="row">
                    <div class="col-lg-6">
                      <div class="form-group">
                        <label  class="form-control-label" for="input-username">Email ID: </label> <?php echo $data->email ?>
                      </div>
                    </div>
                    <div class="col-lg-6">
                      <div class="form-group">
                        <label  class="form-control-label" for="input-username">Address: </label>  <?php echo $data->address ?>
                      </div>
                    </div>
                  </div>

                  <div class="row">
                    <div class="col-lg-6">
                      <div class="form-group">
                       <label  class="form-control-label" for="input-username">Joining Date </label> <?php echo $data->join_time ?>
                      </div>
                    </div>
                    <div class="col-lg-6">
                      <div class="form-group">
                        <label  class="form-control-label" for="input-username">Sponsor Id: </label>  <?php echo config_item('ID_EXT') . $data->sponsor ?>
                      </div>
                    </div>
                  </div>
                  
                  <div class="row">
                    <div class="col-lg-6">
                      <div class="form-group">
                        <label  class="form-control-label" for="input-username">Placement Id. </label> <?php echo config_item('ID_EXT') . $data->position ?>
                      </div>
                    </div>
                    <div class="col-lg-6">
                      <div class="form-group">
                        <label  class="form-control-label" for="input-username">Joining Package. </label>   <?php echo $this->db_model->select('prod_name', 'product', array('id' => $data->signup_package)) ?>
                      </div>
                    </div>
                  </div>


                <div class="row">
                    <div class="col-lg-6">
                      <div class="form-group">
                         <label class="form-control-label" for="input-address">PAN Number</label>
                       <?php echo $profile_data->tax_no ?? 'N/A' ?>
                      
                      </div>
                    </div>
                    <div class="col-lg-6">
                      <div class="form-group">
                       <label class="form-control-label" for="input-address">Aadhar No:</label>
                        <?php echo $profile_data->aadhar_no ?>
                      </div>
                    </div>
                  </div>
                </div>
                 
              
            <h6 class="heading-small text-muted mb-4">Bank information</h6>
               <div class="pl-lg-4">
                <div class="row">
                    <div class="col-md-6">
                      <div class="form-group">
                        <label class="form-control-label" for="input-address">Bank Name:</label>
                       <?php echo $profile_data->bank_name ?>
                      </div>
                    </div>
                    <div class="col-md-6">
                      <div class="form-group">
                        <label class="form-control-label" for="input-address">Bank A/C No:</label>
                       <?php echo $profile_data->bank_ac_no ?>
                      </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-6">
                      <div class="form-group">
                        <label class="form-control-label" for="input-address">IFSC Code:</label>
                       <?php echo $profile_data->bank_ifsc ?>
                      </div>
                    </div>
                    <div class="col-md-6">
                      <div class="form-group">
                        <label class="form-control-label" for="input-address">Bank Branch Name:</label>
                       <?php echo $profile_data->bank_branch ?>
                      </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-6">
                      <div class="form-group">
                        <label class="form-control-label" for="input-address">Nominee Name:</label>
                       <?php echo $profile_data->nominee_name ?>
                      </div>
                    </div>
                    <div class="col-md-6">
                      <div class="form-group">
                        <label class="form-control-label" for="input-address">Nominee Relation:</label>
                       <?php echo $profile_data->nominee_add ?>
                      </div> 
                    </div>
                    <div class="col-md-6">
                      <div class="form-group">
                        <label class="form-control-label" for="input-address">Nominee Address:</label>
                       <?php echo $profile_data->nominee_relation ?>
                      </div> 
                    </div>
                    <div class="col-md-6">
                      <div class="form-group">
                        <label class="form-control-label" for="input-address">Nominee DOB:</label>
                       <?php echo $profile_data->nominee_dob ?>
                      </div> 
                    </div>
                </div>
            </div>
            </div>
          </div>
        </div>
      </div>
    </div>
