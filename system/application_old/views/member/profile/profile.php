         <!-- ============================================================== -->
            <!-- Start right Content here -->
            <!-- ============================================================== -->
                        <div class="row">
                            <div class="col-xl-12">
                                <div class="profile-user"></div>
                            </div>
                        </div>

                        <div class="row">
                           <div class="profile-content">
                               <div class="row align-items-end">
                                    <div class="col-sm">
                                        <div class="d-flex align-items-end mt-3 mt-sm-0">
                                            <div class="flex-shrink-0">
                                                <div class="avatar-xl me-3">
                                                      <img class="img-fluid d-block img-thumbnail" src="<?php echo $my_profile->prof_pic ? base_url('uploads/'.$my_profile->prof_pic) : base_url('uploads/default.jpg'); ?>" alt="Profile Pic" >

                                                </div>
                                            </div>
                                            <div class="flex-grow-1 mt-5">
                                                <div>
                                                    <h5 class="font-size-16 mb-1">Name: <?php echo $my->name; ?></h5>
                                                    <p class="text-muted font-size-13 mb-2 pb-2">Designation: <?php echo $my->rank; ?></p>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                               </div>
                           </div>
                        </div>

                        <div class="row">
                            <div class="col-lg-12">
                               <div class="card bg-transparent shadow-none">
                                   <div class="card-body">
                                        <ul class="nav nav-tabs-custom card-header-tabs border-top mt-2" id="pills-tab" role="tablist">
                                            <li class="nav-item">
                                                <a class="nav-link px-3" data-bs-toggle="tab" href="#overview" role="tab">Overview</a>
                                            </li>
                                            <li class="nav-item">
                                                <a class="nav-link px-3 active" data-bs-toggle="tab" href="#post" role="tab">Edit Profile</a>
                                            </li>
                                        </ul>
                                   </div>
                               </div>
                                <?php  echo $this->session->flashdata('common_flash'); ?>
                            </div>

                        </div>

                        <div class="row">
                            <div class="col-xl-12 col-lg-12">
                                <div class="tab-content">
                                    <div class="tab-pane" id="overview" role="tabpanel">
                                        <div class="card">
                                            <div class="card-header">
                                                <h5 class="card-title mb-0">About Me</h5>
                                            </div>

                                            <div class="card-body">
                                                <div>
                                                    <div class="pb-1">

                                                        <dl class="row mb-0">
                                                            <dt class="col-sm-3">Name:</dt>
                                                            <dd class="col-sm-9"><?php echo $my->name ?></dd>
                        
                                                            <dt class="col-sm-3">Date of Birth</dt>
                                                            <dd class="col-sm-9"><?php echo $my_profile->date_of_birth; ?></dd>
                                                           
                        
                                                            <dt class="col-sm-3">Mobile Number:</dt>
                                                            <dd class="col-sm-9"><?php echo $my->phone; ?></dd>
                        
                                                            <dt class="col-sm-3 text-truncate">Address:</dt>
                                                            <dd class="col-sm-9"><?php echo $my->city.' , '.$my->state.' , '.$my->address.' .'; ?></dd>
                        
                                                            <dt class="col-sm-3">Email Id</dt>
                                                            <dd class="col-sm-9"><?php echo $my->email; ?></dd>
                                                            
                                                        </dl>
                                                    </div>
                                                </div>
                                            </div>
                                            <!-- end card body -->
                                        </div>
                                        <!-- end card -->


                                          <div class="card">
                                            <div class="card-header">
                                                <h5 class="card-title mb-0">Professional Information</h5>
                                            </div>

                                            <div class="card-body">
                                                <div>
                                                    <div class="pb-3">

                                                        <dl class="row mb-0">
                                                            <dt class="col-sm-3">Rank :</dt>
                                                            <dd class="col-sm-9"><?php echo $my->rank ?></dd>
                        
                                                            <dt class="col-sm-3">Date of Joining</dt>
                                                            <dd class="col-sm-9"><?php echo $my->join_time ?></dd>
                                                           
                                                            <dt class="col-sm-3">Referral Name :</dt>
                                                            <dd class="col-sm-9"> <?php echo $this->db_model->select('name', 'member', array('id' => $my->sponsor)); ?></dd>

                                                            <dt class="col-sm-3">Account Status :</dt>
                                                            <dd class="col-sm-9"><?php echo $my->status ?></dd>
                        
                                                            <dt class="col-sm-3 text-truncate">Last Updated :</dt>
                                                            <dd class="col-sm-9"><?php echo $my->updated_at; ?></dd>
                        
                                                            <!--<dt class="col-sm-3">Activation Pakage:</dt>-->
                                                            <!--<dd class="col-sm-9"><?php echo $my->updated_at; ?></dd>-->
                                                            <dt class="col-sm-3">Activation Package :</dt>
                                                            <dd class="col-sm-9"><?php echo ucfirst($prod_name = $this->db_model->select('prod_name', 'product', array('id' => $my->signup_package))); ?></dd>
                                                            
                                                        </dl>
                                                    </div>
                                                </div>
                                            </div>
                                            <!-- end card body -->
                                        </div>
                                        <!-- end card -->

                                        <div class="card">
                                            <div class="card-header">
                                                <h5 class="card-title mb-0">Financial Information</h5>
                                            </div>

                                            <div class="card-body">
                                                <div>
                                                    <div class="pb-3">

                                                        <dl class="row mb-0">
                                                            <dt class="col-sm-3">Bank Details.:</dt>
                                                            <dd class="col-sm-9"><?php echo "A/N.:".$my_profile->bank_ac_no ?> | <?php echo "IFSC:".$my_profile->bank_ifsc ?></dd>
                        
                                                            <dt class="col-sm-3">PAN Card No.</dt>
                                                            <dd class="col-sm-9"><?php echo $my_profile->tax_no; ?></dd>
                                                           
                        
                                                            <dt class="col-sm-3">Aadhar No.:</dt>
                                                            <dd class="col-sm-9"><?php echo $my_profile->aadhar_no; ?></dd>
                        
                                                            <dt class="col-sm-3">UPI No.:</dt>
                                                            <dd class="col-sm-9"><?php echo $my_profile->upi_id; ?></dd>
                                                           
                                                            <dt class="col-sm-3">Nominee Details:</dt>
                                                            <dd class="col-sm-9"><?php echo "Nominee Name: ".$my_profile->nominee_name; ?> | <?php echo "Nominee Address: ".$my_profile->nominee_add; ?></dd>
                                                            
                                                        </dl>
                                                    </div>
                                                </div>
                                            </div>
                                            <!-- end card body -->
                                        </div>
                                        <!-- end card -->


                                    </div>
                                    <!-- end tab pane -->

                                    <div class="tab-pane active" id="post" role="tabpanel">
                                       
                                            <div class="card">
                                                <div class="card-header">
                                                    <h4 class="card-title">Edit - My Profile</h4>
                                                    <p class="card-title-desc">You can Fill or edit your details to update </p>
                                                </div>
                                                <div class="card-body">
                                                    <form enctype="multipart/form-data" class="needs-validation" action="<?php echo base_url('member/personal_info')?>" method="POST">
                                                        <div class="row">
                                                            <div class="col-md-6">
                                                                <div class="mb-3">
                                                                    <label class="form-label" for="validationCustom01">Full Name</label>
                                                                    <input type="text" class="form-control" id="validationCustom01" placeholder="Full name" value="<?php echo set_value('name', $my->name) ?>" name="name" required readonly>
                                                                    <div class="valid-feedback">
                                                                        Looks good!
                                                                    </div>
                                                                </div>
                                                            </div>
                                                            <div class="col-md-6">
                                                                <div class="mb-3">
                                                                    <label class="form-label" for="validationCustom01">DOB</label>
                                                                    <input type="date" class="form-control" id="validationCustom01" placeholder="DOB" value="<?php echo set_value('date_of_birth', $my_profile->date_of_birth)   ?>" name="date_of_birth"  >
                                                                    <div class="valid-feedback">
                                                                        Looks good!
                                                                    </div>
                                                                </div>
                                                            </div>
                                                            <div class="col-md-6">
                                                                <div class="form-group mb-3">
                                                                    <label class="form-label">Contact Number</label>
                                                                    <input type="number" minlength="10" maxlength="10" class="form-control" data-pristine-min-message="Mobile no. should be 10 digit" name="phone" value="<?php echo set_value('phone', $my->phone) ?>" required />
                                                                    <div class="invalid-feedback">
                                                                        Please provide a valid contact no.
                                                                    </div>
                                                                </div>
                                                            </div>
                                                            <div class="col-md-6">
                                                                <div class="form-group mb-3">
                                                                    <label class="form-label">Email</label>
                                                                    <input type="email"  class="form-control" data-pristine-min-message="Invalid Email" name="email" value="<?php echo $my->email ?>" />
                                                                    <div class="invalid-feedback">
                                                                        Please provide a valid contact no.
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </div>
                                                        <div class="row">
                                                           <div class="col-md-6">
                                                                <div class="mb-3">
                                                                    <label class="form-label" for="validationCustom03">Country</label>
                                                                  <!--   <input type="text" class="form-control" id="validationCustom03" name="country" placeholder="Country" value="<?php echo set_value('country', $my->country) ?>" required> -->
                                                                      <select class="form-control" id="validationCustom03" data-trigger name="country"  
                                                                        id="choices-single-groups">
                                                                        <option selected readonly  value="India">India</option>
                                                                        
                                                                    </select>
                                                                    <div class="invalid-feedback">
                                                                        Please provide a valid Country.
                                                                    </div>
                                                                </div>
                                                            </div>
                                                            <div class="col-md-6">
                                                                <div class="mb-3">
                                                                    <label class="form-label" for="validationCustom04">State</label>
                                                                    <!-- <input type="text" class="form-control" id="validationCustom04" name="state"placeholder="State" value="<?php echo set_value('state', $my->state) ?>" required> -->
                                                                    <select class="form-control" id="validationCustom03" data-trigger name="state"  id="choices-single-groups">
                                                                        <option value="">Select State</option>
                                                                        <option value="<?php echo $my->state ?>" selected ><?php echo $my->state ?></option>
                                                                        <option value="Andhra Pradesh">Andhra Pradesh</option>
                                                                        <option value="Arunachal Pradesh">Arunachal Pradesh</option>
                                                                        <option value="Assam">Assam</option>
                                                                        <option value="Bihar">Bihar</option>
                                                                        <option value="Chhattisgarh">Chhattisgarh</option>
                                                                        <option value="Goa">Goa</option>
                                                                        <option value="Gujarat">Gujarat</option>
                                                                        <option value="Haryana">Haryana</option>
                                                                        <option value="Himachal Pradesh">Himachal Pradesh</option>
                                                                        <option value="Jharkhand">Jharkhand</option>
                                                                        <option value="Karnataka">Karnataka</option>
                                                                        <option value="Kerala">Kerala</option>
                                                                        <option value="Madhya Pradesh">Madhya Pradesh</option>
                                                                        <option value="Maharashtra">Maharashtra</option>
                                                                        <option value="Manipur">Manipur</option>
                                                                        <option value="Meghalaya">Meghalaya</option>
                                                                        <option value="Mizoram">Mizoram</option>
                                                                        <option value="Nagaland">Nagaland</option>
                                                                        <option value="Odisha">Odisha</option>
                                                                        <option value="Punjab">Punjab</option>
                                                                        <option value="Rajasthan">Rajasthan</option>
                                                                        <option value="Sikkim">Sikkim</option>
                                                                        <option value="Tamil Nadu">Tamil Nadu</option>
                                                                        <option value="Telangana">Telangana</option>
                                                                        <option value="Tripura">Tripura</option>
                                                                        <option value="Uttar Pradesh">Uttar Pradesh</option>
                                                                        <option value="Uttarakhand">Uttarakhand</option>
                                                                        <option value="West Bengal">West Bengal</option>
                                                                        <option value="Andaman and Nicobar Islands">Andaman and Nicobar Islands</option>
                                                                        <option value="Chandigarh">Chandigarh</option>
                                                                        <option value="Dadra and Nagar Haveli">Dadra and Nagar Haveli</option>
                                                                        <option value="Daman and Diu">Daman and Diu</option>
                                                                        <option value="Delhi">Delhi</option>
                                                                        <option value="Lakshadweep">Lakshadweep</option>
                                                                        <option value="Puducherry">Puducherry</option>
                                                                    </select>
                                                                    <div class="invalid-feedback">
                                                                        Please provide a valid state.
                                                                    </div>
                                                                </div>
                                                            </div>
                                                             <div class="col-md-6">
                                                                <div class="mb-3">
                                                                    <label class="form-label" for="validationCustom03">City</label>
                                                                    <input type="text" class="form-control" id="validationCustom03" name="city" placeholder="City" value="<?php echo set_value('city', $my->city) ?>"  >
                                                                    <div class="invalid-feedback">
                                                                        Please provide a valid city.
                                                                    </div>
                                                                </div>
                                                            </div>
                                                             <div class="col-md-6">
                                                                <div class="mb-3">
                                                                    <label class="form-label" for="validationCustom03">Local Address</label>
                                                                    <input type="text" class="form-control" id="validationCustom03" name="address" placeholder="Local Address" value="<?php echo $my->address ?>"  >
                                                                    <div class="invalid-feedback">
                                                                        Please provide a valid Local Address.
                                                                    </div>
                                                                </div>
                                                            </div>
                                                            <!--
                                                             <div class="col-md-12">
                                                                <div class="mb-3">
                                                                    <label class="form-label" for="validationCustom03">Transaction Password</label>
                                                                    <input type="text" class="form-control" placeholder="Enter Transaction Password" name="trans_password" required>
                                                                </div>
                                                            </div>-->
                                                            <!--<div class="col-md-6">-->
                                                            <!--<div class="form-group mb-3">-->
                                                            <!--    <label>Password (required)</label>-->
                                                            <!--    <input type="password" id="pwd" name="password" placeholder="Enter password"  required data-pristine-required-message="Please Enter a password" data-pristine-pattern= "/^(?=.*?[A-Z])(?=.*?[a-z])(?=.*?[0-9]).{8,}$/" data-pristine-pattern-message="Minimum 8 characters, at least one uppercase letter, one lowercase letter and one number" class="form-control" value="" />-->
                                                            <!--    </div>-->
                                                            <!--</div>-->
                                                            <!--<div class="col-md-6">-->
                                                            <!--    <div class="form-group mb-3">-->
                                                            <!--        <label>Retype password</label>-->
                                                            <!--        <input type="password" data-pristine-equals="#pwd" placeholder="Re-enter password"  name="repassword" data-pristine-equals-message="Passwords don't match" value="" class="form-control" food/>-->
                                                            <!--    </div>-->
                                                            <!--</div>-->

                                                            <div class="col-md-2">
                                                                <div class="form-group mb-3">
                                                                    <button type="submit" class="form-control btn-primary" /> Submit</button>
                                                                </div>
                                                            </div>

                                                        </div>
                                                    </form>
                                                    </div>
                                                </div>

                                               <!--<div class="card">-->
                                                    <!--<div class="card-header">-->
                                                    <!--    <h4 class="card-title">Edit - Bank Details</h4>-->
                                                    <!--    <p class="card-title-desc">You can Fill or edit your details to update </p>-->
                                                    <!--</div>-->
                                                <!--<div class="card-body">-->

                                                    <!--<form enctype="multipart/form-data" class="needs-validation" action="<?php echo base_url('member/financial_details')?>" method="POST">-->
                                                      
                                                    <!--    <h5 class="card-title mb-4">Bank Details & KYC</h5>-->
                                                    <!--        <div class="row">-->
                                                    <!--        <div class="col-md-6 mb-3">-->
                                                    <!--                <label for="choices-single-groups" class="form-label">Bank Name</label>-->
                                                    <!--                <input type="text" minlength="2" placeholder="Enter bank name" class="form-control" name="bank_name" value="<?php echo set_value('bank_name', $my_profile->bank_name) ?>" />-->
                                                    <!--        </div>-->

                                                    <!--        <div class="col-md-6">-->
                                                    <!--            <div class="form-group mb-3">-->
                                                    <!--                <label class="form-label">Account No.</label>-->
                                                    <!--                <input type="text" minlength="3" placeholder="Enter bank account" class="form-control" name="bank_ac_no" value="<?php echo set_value('bank_ac_no', $my_profile->bank_ac_no) ?>" />-->
                                                    <!--                 <div class="invalid-feedback">-->
                                                    <!--                    Please provide a valid Account No.-->
                                                    <!--                </div>-->
                                                    <!--            </div>-->
                                                    <!--        </div>-->
                                                    
                                                    <!--         <div class="col-md-6">-->
                                                    <!--            <div class="form-group mb-3">-->
                                                    <!--                <label class="form-label">Branch Name.</label>-->
                                                    <!--                <input type="text" minlength="3" class="form-control" placeholder="Enter bank branch" name="bank_branch" value="<?php echo set_value('bank_branch', $my_profile->bank_branch) ?>"/>-->
                                                    <!--                 <div class="invalid-feedback">-->
                                                    <!--                    Please provide a valid Branch Name.-->
                                                    <!--                </div>-->
                                                    <!--            </div>-->
                                                    <!--        </div>-->
                                                           
                                                    <!--        <div class="col-md-6">-->
                                                    <!--            <div class="form-group mb-3">-->
                                                    <!--                <label class="form-label">IFSC Code</label>-->
                                                    <!--                <input type="text" minlength="3" class="form-control" placeholder="Enter bank ifsc"  name="bank_ifsc" value="<?php echo set_value('bank_ifsc', $my_profile->bank_ifsc) ?>"/>-->
                                                    <!--                 <div class="invalid-feedback">-->
                                                    <!--                    Please provide a valid IFSC code.-->
                                                    <!--                </div>-->
                                                    <!--            </div>-->
                                                    <!--        </div>-->

                                                    <!--        <div class="col-md-6">-->
                                                    <!--            <div class="form-group mb-3">-->
                                                    <!--                <label class="form-label">PAN No.</label>-->
                                                    <!--                <input type="text" minlength="10" maxlength="10" placeholder="Enter pan no." class="form-control" name="tax_no" value="<?php echo set_value('tax_no', $my_profile->tax_no) ?>"/>-->
                                                    <!--                 <div class="invalid-feedback">-->
                                                    <!--                    Please provide a valid PAN no.-->
                                                    <!--                </div>-->
                                                    <!--            </div>-->
                                                    <!--        </div>-->
                                                    <!--        <div class="col-md-6">-->
                                                    <!--            <div class="form-group mb-3">-->
                                                    <!--                <label class="form-label">Aadhar No.</label>-->
                                                    <!--                <input type="text" minlength="8" maxlength="17" placeholder="Enter adhar no." class="form-control" name="aadhar_no" value="<?php echo set_value('aadhar_no', $my_profile->aadhar_no) ?>"/>-->
                                                    <!--                  <div class="invalid-feedback">-->
                                                    <!--                    Please provide a valid Aadhar no.-->
                                                    <!--                </div>-->
                                                    <!--            </div>-->
                                                    <!--        </div> -->
                                                    <!--         <div class="col-md-12">-->
                                                    <!--            <div class="form-group mb-3">-->
                                                    <!--                <label class="form-label">UPI ID</label>-->
                                                    <!--                <input type="text" placeholder="Enter UPI no." class="form-control" name="upi_id" value="<?php echo set_value('upi_id', $my_profile->upi_id) ?>"/>-->
                                                    <!--            </div>-->
                                                    <!--        </div> -->
                                                    <!--         <div class="col-md-12">-->
                                                    <!--            <div class="mb-3">-->
                                                    <!--                <label class="form-label" for="validationCustom03">Transaction Password</label>-->
                                                    <!--                <input type="text" class="form-control" placeholder="Enter Transaction Password" name="trans_password" required>-->
                                                    <!--            </div>-->
                                                    <!--        </div>-->
                                                    <!--          <div class="col-md-2">-->
                                                    <!--            <div class="form-group mb-3">-->
                                                    <!--                <button type="submit" class="form-control btn-primary" /> Submit</button>-->
                                                    <!--            </div>-->
                                                    <!--        </div>-->

                                                    <!--        </div>-->
                                                    <!--     </form>-->
                                                <!--    </div>-->
                                                <!--</div>-->

                                                <div class="card">
                                                    <div class="card-header">
                                                        <h4 class="card-title">Nominee & Other Details</h4>
                                                        <p class="card-title-desc">You can Fill or edit your details to update </p>
                                                    </div>
                                                    <div class="card-body">
                                                         <form enctype="multipart/form-data" class="needs-validation" action="<?php echo base_url('member/nominee_details')?>" method="POST">
                                                            <div class="row">
                                                                 <div class="col-md-6">
                                                                    <div class="form-group mb-3">
                                                                        <label class="form-label">Nominee Name.</label>
                                                                        <input type="text"  class="form-control" name="nominee_name" value="<?php echo set_value('nominee_name', $my_profile->nominee_name) ?>"/>
                                                                         <div class="invalid-feedback">
                                                                            Please provide a valid Nominee Name.
                                                                        </div>
                                                                    </div>
                                                                </div>

                                                                 <div class="col-md-6">
                                                                    <div class="form-group mb-3">
                                                                        <label class="form-label">Nomineee Relation</label>
                                                                        <input type="text" class="form-control" name="nominee_add" value="<?php echo set_value('nominee_add', $my_profile->nominee_add) ?>"/>
                                                                         <div class="invalid-feedback">
                                                                            Please provide a valid Nomineee Relation.
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                                <div class="col-md-6">
                                                                    <div class="form-group mb-3">
                                                                        <label class="form-label">Nomineee Address</label>
                                                                        <input type="text" class="form-control" name="nominee_relation" value="<?php echo set_value('nominee_relation', $my_profile->nominee_relation) ?>"/>
                                                                         <div class="invalid-feedback">
                                                                            Please provide a valid Nomineee Address.
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                                 <div class="col-md-6">
                                                                     <div class="mb-3">
                                                                        <label class="form-label">DOB Of Nominee</label>
                                                                        <input type="date" class="form-control" name="nominee_dob" id="datepicker-datetime" value="<?php echo  $my_profile->nominee_dob ?>">
                                                                    </div>
                                                                </div>
                                                            </div>
                                                            <!-- <div class="col-md-12">-->
                                                            <!--    <div class="mb-3">-->
                                                            <!--        <label class="form-label" for="validationCustom03">Transaction Password</label>-->
                                                            <!--        <input type="text" class="form-control" placeholder="Enter Transaction Password" name="trans_password" required>-->
                                                            <!--    </div>-->
                                                            <!--</div>-->

                                                            <div class="row">
                                                                <div class="col-lg-12">
                                                                    <div class="mb-3">
                                                                        <div class="form-check">
                                                                            <input type="checkbox" class="form-check-input" id="invalidCheck" required checked>
                                                                            <label class="form-check-label" for="invalidCheck">Agree to terms and conditions</label>
                                                                            <div class="invalid-feedback">
                                                                                You must agree before submitting.
                                                                            </div>
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                                 <div class="col-md-2">
                                                                    <div class="form-group mb-3">
                                                                     <button type="submit" class="form-control btn-primary" /> Submit</button>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </form>
                                                        </div>
                                                    </div>

                                             
                                    </div>
                                    <!-- end tab pane -->
                                </div>
                                <!-- end tab content -->
                            </div>
                            <!-- end col -->


                            <!-- end col -->
                        </div>
                        <!-- end row -->
                        
                    </div> <!-- container-fluid -->
                </div>
                <!-- End Page-content -->

