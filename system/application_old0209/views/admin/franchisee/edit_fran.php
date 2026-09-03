    <script type="text/javascript" src="<?php echo base_url('axxets/countries.js') ?>"></script>
    <?php echo form_open() ?>
 
    <div class="col">
        <div class="card bg-secondary shadow">  
            <div class="card-header bg-white border-0"> 
                <div class="row align-items-center">
                    <div class="col-8"> 
                          <h3 class="mb-0"><?php echo $title ?></h3>
                    </div>
                    <div class="col-4 text-right">  
                     
                       <a href="<?php echo site_url('admin')?>" class="btn btn-sm btn-primary">Home</a>
                    </div> 
                </div> 
            </div>  
            <div class="card-body">
                <div class="row">
                    <div class="col-sm-6">
                        <label>Pick-up Centre Name <span class="text-danger">*</span></label>
                        <input type="text" class="form-control form-control-alternative" value="<?php echo $data->name ?>" required name="name">
                    </div>
                    <div class="col-sm-6">
                        <label>Store / Business Name</label>
                        <input type="text" class="form-control form-control-alternative" value="<?php echo $data->business_name ?>" name="business_name">
                    </div>
                    <div class="col-sm-6 mt-2">
                        <label>GSTIN</label>
                        <input type="text" class="form-control form-control-alternative" value="<?php echo $data->gstin ?>" name="gstin">
                    </div>
                    <div class="col-sm-6">
                        <label>Commision <span class="text-danger">*</span></label>
                        <input type="text" class="form-control form-control-alternative" name="cmsn" placeholder="Enter Commision">
                    </div>
                    <input type="hidden" name="id" value="<?php echo $data->id ?>">
                    <div class="col-sm-6 mt-2">
                        <label>Email ID</label>
                        <input type="text" class="form-control form-control-alternative" value="<?php echo $data->email ?>" name="email">
                    </div>
                    <div class="col-sm-6 mt-2">
                        <label>Phone <span class="text-danger">*</span></label>
                        <input type="text" class="form-control form-control-alternative" value="<?php echo $data->phone ?>" name="phone">
                    </div>
                    <div class="col-sm-6 mt-2">
                        <label>State <span class="text-danger">*</span></label> 
                        <input type="text" class="form-control form-control-alternative" value="<?php echo $data->state ?>" name="state">
                    </div>
                    <div class="col-sm-6 mt-2">
                        <label>District <span class="text-danger">*</span></label> 
                        <input type="text" class="form-control form-control-alternative" value="<?php echo $data->district ?>" name="district">
                    </div>
                    <div class="col-sm-6 mt-2">
                        <label>Taluka <span class="text-danger">*</span></label> 
                        <input type="text" class="form-control form-control-alternative" value="<?php echo $data->taluka ?>" name="taluka">
                    </div>
                    <div class="col-sm-6 mt-2">
                        <label>Pincode</label>
                        <input type="text" class="form-control form-control-alternative" value="<?php echo $data->pincode ?>" name="pincode">
                    </div>
                    <div class="col-sm-6 mt-2">
                        <label>Address</label>
                        <input type="text" class="form-control form-control-alternative" value="<?php echo $data->address ?>" name="address">
                    </div>
                    <div class="col-sm-6 mt-2">
                        <label>Password</label>
                        <input type="password" class="form-control form-control-alternative" value="<?php echo $data->password ?>" name="password">
                    </div>
                    <div class="col-sm-6 mt-2">
                        <label for="inputLevel">Pick-up Centre Level <span class="text-danger">*</span></label>
                        <select class="form-control" id="inputLevel" name="level" required disabled>
                            <option value="" disabled selected>-- select one -- </option>
                            <option value="1" <?php if($data->level == 1){ echo "selected"; } ?>>State</option>
                            <option value="2" <?php if($data->level == 2){ echo "selected"; } ?>>District</option>
                            <option value="3" <?php if($data->level == 3){ echo "selected"; } ?>>Homeshopee</option>
                            <option value="4" <?php if($data->level == 4){ echo "selected"; } ?>>Company shopee</option>
                        </select>
                    </div>
                    
                    <div class="col-sm-6 mt-2">
                        <label>Status</label>
                        <select class="form-control form-control-alternative" name="status">
                            <option selected> <?php echo $data->status ?></option>
                            <option>Active</option>
                            <option>Inactive</option>
                            <option>Terminated</option>
                        </select>
                    </div>
                    
                    <div class="col-sm-6 mt-2" <?php if ($data->level != 3) { echo 'style="display: none;"'; } ?> id="districtFranDiv">
                        <?php $under_dist_franch = $this->db_model->select('business_name', 'franchisee', array('id' => $data->under_district_id)); ?>
                        <label for="inputLevel">Select District Franchise <span class="text-danger">*</span></label>
                        <select class="form-control" id="under_district_fran" name="under_district_fran" required>
                            <option value="" disabled selected><?php echo $under_dist_franch; ?></option>
                        
                        </select>
                    </div>
                
                    <div class="col-sm-6"><br/>
                        <input type="submit" class="btn btn-success" value="Update" onclick="this.value='Updating..'">
                    </div>
                    <?php echo form_close() ?>
                </div>
            </div>
        </div>
    </div>

    <script language="javascript">
        populateCountries("country", "state");
    </script>



