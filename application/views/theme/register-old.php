
<?php echo form_open('', 'id="registrationForm"'); ?>
<!-- <body data-layout="horizontal"> -->
        <div class="auth-page" >
            <div class="container-fluid p-0">
                <div class="row g-0" onclick="get_user_name('#sponsor', '#spn_res')">
                    <div class="col-xxl-7 col-lg-7 col-md-12">
                        <div class="auth-full-page-content d-flex p-sm-5 p-4">
                            <div class="w-100">
                                <div class="d-flex flex-column h-100">
                                    <div class=" text-center">
                                        <a class="d-block auth-logo">
                                            <img src="<?= base_url('uploads/logo.png') ?>" alt="" height="50"> <span class="logo-txt"><?php echo config_item('company_name') ?> </span>
                                        </a>
                                    </div>
                                    <div class="auth-content my-auto">
                                         <?php echo validation_errors('<div class="alert alert-danger">', '</div>') ?>
                                         <?php echo $this->session->flashdata('site_flash') ?>

                                          <div class="text-center">
                                            <h5 class="mb-0 text-success my-3">Free Registration !</h5>
                                            <p class="text-muted mt-2">Sign up to continue to <?php echo config_item('company_name') ?></p>
                                        </div> 

                                       <div class="row"> 
                                                <div class="col-md-6">
                                                    <!--
                                                    <div class="mb-3">
                                                        <label class="form-label" for="validationCustom01">Sponsor id <span class="text-danger">*</span> [ <span id="spn_res" style="color: red;"></span> ]</label>
                                                         <input type="text" oninput="get_user_name('#sponsor', '#spn_res')" class="form-control form-control-alternative"
                                                            value="<?php if ($this->uri->segment(3) !== "epin") {
                                                                $uri4 = $this->uri->segment(4);
                                                            };
                                                            echo set_value('sponsor', $uri4)?>"
                                                           id="sponsor"
                                                           required
                                                           name="sponsor" 
                                                           placeholder="Sponsor Id"
                                                           title = "Enter correct  number" >
                                                    </div>-->
                                                    <div class="mb-3">
                                                        <label class="form-label" for="validationCustom01">Sponsor id <span class="text-danger">*</span> [ <span id="spn_res" style="color: red;"></span> ]</label>
                                                        <?php
                                                        $uri4 = ($this->uri->segment(3) !== "epin") ? $this->uri->segment(4) : '';
                                                        $sponsor_value = set_value('sponsor', $uri4);
                                                        if (empty($sponsor_value)) {
                                                            $sponsor_value = '557037';
                                                        }
                                                        ?>

                                                        <input type="text" 
                                                            oninput="get_user_name('#sponsor', '#spn_res')" 
                                                            class="form-control form-control-alternative"
                                                            value="<?php echo $sponsor_value; ?>"
                                                            id="sponsor"
                                                            name="sponsor"
                                                            required
                                                            placeholder="Sponsor Id"
                                                            title="Enter correct number">
                                                    </div>
                                                </div>
                                                
                                                <?php if (config_item('leg') !== "1" && config_item('show_placement_id') == "Yes") { ?>
                                                    <div class="col-sm-6">
                                                        <label for="placement id" class="control-label">Placement Id [ <span id="position_res" style="color: red;"></span> ]</label>
                                                        <input type="number" class="form-control form-control-alternative" 
                                                           required
                                                           oninput="get_position_user_name('#position', '#position_res')"
                                                           id="position" value="<?php echo $this->uri->segment(5) ?>"
                                                           name="position" id="position" 
                                                           title = "Under id - Where you want to place the ID"
                                                           placeholder="Under / Placement"
                                                           pattern="^[0-9]{1,8}$" >
                                                        <label for="position" class="control-label"><span id="psn_res" style="color: red; font-weight: bold"></span></label>
                                                    </div>
        
                                                <?php } ?>
                                        
                                                <?php if (config_item('show_leg_choose') == "Yes") { ?>
                                                <div class="form-group col-sm-6">
                                                     <label for="placement id" class="control-label">Placement Leg <span class="text-danger">*</span></label>
                                                      <input type="hidden"  name="leg" value="<?php echo  $this->uri->segment(3) ?>">
                                                        <select class="form-control" id="leg" name="leg">
                                                                <?php 
                                                                foreach ($leg as $key => $val) {
                                                                    if($key == $this->uri->segment(3)){
                                                                        echo '<option value="' . $key . '" selected>' . $val . ' </option>';
                                                                    }
                                                                    else{
                                                                        echo '<option value="' . $key . '">' . $val . ' </option>';
                                                                    }
                                                            }
                                                            ?>
                                                        </select>
                                                </div>
                                                <?php } ?>
                                                
                                                <div class="col-md-6">
                                                    <div class="mb-3">
                                                        <label class="form-label" for="validationCustom01">Full Name <span class="text-danger">*</span></label>
                                                        <input type="text" class="form-control" id="validationCustom01" name="name" placeholder="Enter Full name" required>
                                                        <div class="valid-feedback">
                                                            Looks good!
                                                        </div>
                                                    </div>
                                                </div>
                                                 <div class="col-md-6">
                                                    <div class="mb-3">
                                                        <label class="form-label" for="validationCustom02">Phone <span class="text-danger">*</span></label>
                                              
                                                         <input type="tel" 
                                                            pattern="[6-9][0-9]{9}"
                                                            title="Enter valid 10-digit Indian mobile number starting with 6,7,8, or 9" 
                                                            class="form-control form-control-alternative" 
                                                            id="phone" name="phone" 
                                                            placeholder="Phone Number" required>

                                                        <div class="valid-feedback">
                                                            Looks good!
                                                        </div>
                                                    </div>
                                                </div>

                                                <!--<div class="col-md-6">-->
                                                <!--    <div class="mb-3">-->
                                                <!--        <label class="form-label" for="validationCustom05">Email</label>-->
                                                <!--          <input type="email" class="form-control form-control-alternative"-->
                                                <!--               value="<?php echo set_value('email') ?>" id="email"-->
                                                <!--               name="email"-->
                                                <!--               placeholder="Enter Your Email"-->
                                                <!--               title="Enter proper Email-ID"-->
                                                <!--               pattern ="[A-Za-z_.0-9-]+@{1}[a-z]+([.]{1}[a-z]{2,4})+" >-->
                                                <!--        <div class="invalid-feedback">-->
                                                <!--            Please provide a valid email.-->
                                                <!--        </div>-->
                                                <!--    </div>-->
                                                <!--</div>-->
                                            <?php   if (config_item('enable_epin') == "Yes" && config_item('free_registration') == "No") { ?> 

                                                <div class="col-md-6 mb-3" id="e_pin">

                                                 <label class="form-label" for="validationCustom02">E-Pin</label>
                                                     <input type="text" value="<?php if (trim($this->uri->segment(3)) == "epin") {
                                                                echo set_value('epin', $this->uri->segment(4));
                                                              }?>"
                                                              class="form-control" 
                                                               id="epin"
                                                               name="epin" 
                                                               placeholder="ePin"
                                                               pattern="^[0-9]{1,8}$"
                                                               title="Recheck ePin before submitting" 
                                                               required >
                                                </div>
                                            <?php } ?>

                                            <?php 
                                            if (config_item('enable_pg') == "Yes" && config_item('free_registration') == "No") {
                                                ?>
                                                  <div class="col-md-6 mb-3" id="e_pin">
                                            <label class="form-label" for="validationCustom02">Select Online </label>
                                            <div class="form-group col-sm-6" id="amt_to_pay">
                                                <div class="custom-control custom-checkbox mb-3">
                                                  <input class="custom-control-input" name="pg" id="pg" type="checkbox">
                                                  <label class="custom-control-label" for="pg">Online Pay</label>
                                                </div>
                                              </div>
                                              </div>
                                           <?php }?>
 
                                            <?php if (config_item('show_join_product') == "Yes") { ?>

                                                <div class="col-sm-6 mb-3">
                                                    <label for="product" class="control-label">Sign Up Product</label> 
                                                    <select class="form-control form-control-alternative" id="product" name="product">
                                                        <option value="" selected disabled>Select Package </option>
                                                        <?php foreach ($products as $val) {
                                                            echo '<option value="' . $val['id'] . '">' . $val['prod_name'] . '. Price :' . config_item('currency') . number_format($val['prod_price'] + ($val['prod_price'] * $val['gst'] / 100), 2) . ' </option>';
                                                        }?>
                                                    </select>
                                                </div>
                                            <?php } ?>

                                            <div class="col-sm-6 mb-3">
                                                <label for="product" class="control-label">Sign Up Product n</label> 
                                                <select class="form-control form-control-alternative" id="join_package" name="join_package" required>
                                                    <option value="" selected disabled>Select Package </option>
                                                    <?php foreach ($products as $val) {

                                                        echo '<option value="' . $val['id'] . '|' . $val['prod_price'] . '">' 
                                                        . $val['prod_name'] 
                                                        . ' Price: ' . config_item('currency') 
                                                        . number_format($val['prod_price'] + ($val['prod_price'] * $val['gst'] / 100), 2) 
                                                        . '</option>';

                                                    }?>
                                                </select>
                                                <input type="hidden"  name="free_join" id="free_join" value="">
                                            </div>
                                            <div class="col-md-6">
                                                <div class="mb-3">
                                                    <label class="form-label" for="validationCustom05">Login Password <span class="text-danger">*</span></label>
                                                    <input type="text" class="form-control" id="validationCustom05" placeholder=" Password" required name="password">
                                                    <div class="invalid-feedback">
                                                        Please provide a valid Password.
                                                    </div>
                                                </div>
                                            </div>

                                        
                                            <?php
                                                if (config_item('leg') == "1") {
                                                    echo form_hidden('leg', 'A');
                                                } 
                                                ?>

                                                <!--<div class="col-md-6">-->
                                                <!--    <div class="mb-3">-->
                                                <!--        <label class="form-label" for="validationCustom04">Country</label>-->
                                                <!--        <select class="form-control" id="validationCustom03" data-trigger name="country" id="choices-single-groups">-->
                                                <!--            <option selected readonly  value="India">India</option>-->
                                                <!--        </select>-->
                                                <!--        <div class="invalid-feedback">-->
                                                <!--            Please provide a valid Country.-->
                                                <!--        </div>-->
                                                <!--    </div>-->
                                                <!--</div>-->
                                                <!--<div class="col-md-6">-->
                                                <!--    <div class="mb-3">-->
                                                <!--        <label class="form-label" for="validationCustom04">State</label>-->
                                                <!--          <select class="form-control" id="validationCustom03" data-trigger name="state" id="choices-single-groups" required>-->
                                                <!--              <option value="" disabled selected>Select State</option>-->
                                                <!--              <option value="Andhra Pradesh">Andhra Pradesh</option>-->
                                                <!--              <option value="Arunachal Pradesh">Arunachal Pradesh</option>-->
                                                <!--              <option value="Assam">Assam</option>-->
                                                <!--              <option value="Bihar">Bihar</option>-->
                                                <!--              <option value="Chhattisgarh">Chhattisgarh</option>-->
                                                <!--              <option value="Goa">Goa</option>-->
                                                <!--              <option value="Gujarat">Gujarat</option>-->
                                                <!--              <option value="Haryana">Haryana</option>-->
                                                <!--              <option value="Himachal Pradesh">Himachal Pradesh</option>-->
                                                <!--              <option value="Jharkhand">Jharkhand</option>-->
                                                <!--              <option value="Karnataka">Karnataka</option>-->
                                                <!--              <option value="Kerala">Kerala</option>-->
                                                <!--              <option value="Madhya Pradesh">Madhya Pradesh</option>-->
                                                <!--              <option value="Maharashtra">Maharashtra</option>-->
                                                <!--              <option value="Manipur">Manipur</option>-->
                                                <!--              <option value="Meghalaya">Meghalaya</option>-->
                                                <!--              <option value="Mizoram">Mizoram</option>-->
                                                <!--              <option value="Nagaland">Nagaland</option>-->
                                                <!--              <option value="Odisha">Odisha</option>-->
                                                <!--              <option value="Punjab">Punjab</option>-->
                                                <!--              <option value="Rajasthan">Rajasthan</option>-->
                                                <!--              <option value="Sikkim">Sikkim</option>-->
                                                <!--              <option value="Tamil Nadu">Tamil Nadu</option>-->
                                                <!--              <option value="Telangana">Telangana</option>-->
                                                <!--              <option value="Tripura">Tripura</option>-->
                                                <!--              <option value="Uttar Pradesh">Uttar Pradesh</option>-->
                                                <!--              <option value="Uttarakhand">Uttarakhand</option>-->
                                                <!--              <option value="West Bengal">West Bengal</option>-->
                                                <!--              <option value="Andaman and Nicobar Islands">Andaman and Nicobar Islands</option>-->
                                                <!--              <option value="Chandigarh">Chandigarh</option>-->
                                                <!--              <option value="Dadra and Nagar Haveli">Dadra and Nagar Haveli</option>-->
                                                <!--              <option value="Daman and Diu">Daman and Diu</option>-->
                                                <!--              <option value="Delhi">Delhi</option>-->
                                                <!--              <option value="Lakshadweep">Lakshadweep</option>-->
                                                <!--              <option value="Puducherry">Puducherry</option>-->
                                                <!--        </select>-->
                                                <!--        <div class="invalid-feedback">-->
                                                <!--            Please provide a valid State.-->
                                                <!--        </div>-->
                                                <!--    </div>-->
                                                <!--</div>-->
                                                <!--<div class="col-md-6">-->
                                                <!--    <div class="mb-3">-->
                                                <!--        <label class="form-label" for="validationCustom04">City</label>-->
                                                <!--        <input type="text" class="form-control" id="validationCustom04"  name="city" placeholder="City" required>-->
                                                <!--        <div class="invalid-feedback">-->
                                                <!--            Please provide a valid City.-->
                                                <!--        </div>-->
                                                <!--    </div>-->
                                                <!--</div>-->
                                                <!--<div class="col-md-6">-->
                                                <!--    <div class="mb-3">-->
                                                <!--        <label class="form-label" for="validationCustom04">Address</label>-->
                                                <!--        <input type="text" class="form-control" id="validationCustom05"  name="address" placeholder="Address">-->
                                                <!--        <div class="invalid-feedback">-->
                                                <!--            Please provide a valid address.-->
                                                <!--        </div>-->
                                                <!--    </div>-->
                                                <!--</div>-->
                                                <!--<div class="col-md-6">-->
                                                <!--    <div class="mb-3">-->
                                                <!--        <label class="form-label" for="validationCustom06">Pincode</label>-->
                                                <!--        <input type="text" class="form-control" id="validationCustom06"  name="pincode" placeholder="Pincode" required>-->
                                                <!--        <div class="invalid-feedback">-->
                                                <!--            Please provide a valid address.-->
                                                <!--        </div>-->
                                                <!--    </div>-->
                                                <!--</div>-->
                                              <!--<div class="col-md-6">-->
                                              <!--      <div class="mb-3">-->
                                              <!--          <label class="form-label" for="validationCustom04">Pincode</label>-->
                                              <!--          <input type="text" class="form-control" id="validationCustom09"  name="pincode" placeholder="Pincode" required>-->
                                              <!--          <div class="invalid-feedback">-->
                                              <!--              Please provide a valid Pincode.-->
                                              <!--          </div>-->
                                              <!--      </div>-->
                                              <!--  </div>-->
                                            </div>
                                            <div class="row">
                                                <!-- <div class="col-md-6">-->
                                                <!--    <div class="mb-3">-->
                                                <!--        <label class="form-label" for="validationCustom02">Adhar Number</label>-->
                                                <!--        <input type="text" class="form-control" id="validationCustom02" name="adhar" placeholder="Adhar Number" required>-->
                                                <!--        <div class="valid-feedback">-->
                                                <!--            Please provide a valid Adhar Number.-->
                                                <!--        </div>-->
                                                <!--    </div>-->
                                                <!--</div>-->
                                                <!--
                                                <div class="col-md-6">
                                                    <div class="mb-3">
                                                        <label class="form-label" for="validationCustom05">Pan Number</label>
                                                        <input type="text" class="form-control" id="validationCustom05" pattern="[A-Z]{5}[0-9]{4}[A-Z]{1}" title="Please enter a valid PAN number (e.g., ABCDE1234F)" placeholder="Pan Number" required name="pan">
                                                        <div class="invalid-feedback">
                                                            Please provide a valid Pan Number.
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="col-md-6">
                                                    <div class="mb-3">
                                                        <label class="form-label" for="validationCustom04">Resident address</label>
                                                        <input type="text" class="form-control" id="validationCustom05"  name="address" placeholder="Address">
                                                        <div class="invalid-feedback">
                                                            Please provide a valid address.
                                                        </div>
                                                    </div>
                                                </div>-->
                                                <!--<div class="col-md-6">-->
                                                <!--    <div class="mb-3">-->
                                                <!--        <label class="form-label" for="bank_name">Bank Name <span class="text-danger">*</span></label>-->
                                                <!--        <input type="text" class="form-control" id="bank_name" placeholder="Bank Name" required name="bank_name">-->
                                                <!--    </div>-->
                                                <!--</div>-->
                                                <!--<div class="col-md-6">-->
                                                <!--    <div class="mb-3">-->
                                                <!--        <label class="form-label" for="ifsc">IFSC Code <span class="text-danger">*</span></label>-->
                                                <!--        <input type="text" class="form-control" id="ifsc" placeholder="IFSC Code" required name="ifsc">-->
                                                <!--    </div>-->
                                                <!--</div>-->
                                                <!--<div class="col-md-6">-->
                                                <!--    <div class="mb-3">-->
                                                <!--        <label class="form-label" for="acc_name">Account No. <span class="text-danger">*</span></label>-->
                                                <!--        <input type="text" class="form-control" id="acc_name" placeholder="Account No." required name="acc_name">-->
                                                <!--    </div>-->
                                                <!--</div>-->
                                                <!--<div class="col-md-6">-->
                                                <!--    <div class="mb-3">-->
                                                <!--        <label class="form-label" for="branch_name">Branch Name <span class="text-danger">*</span></label>-->
                                                <!--        <input type="text" class="form-control" id="branch_name" placeholder="Branch Name" required name="branch_name">-->
                                                <!--    </div>-->
                                                <!--</div>-->
                                                
                                                <!--
                                                <div class="col-md-6">
                                                    <div class="mb-3">
                                                        <label class="form-label" for="validationCustom05">Transaction Password <span class="text-danger">*</span></label>
                                                        <input type="text" class="form-control" id="validationCustom05" placeholder="Confirm Password" required name="tran_pass">
                                                        <div class="invalid-feedback">
                                                            Please provide a valid Password.
                                                        </div>
                                                    </div>
                                                </div>-->
                                            </div>
                                            <div class="row">
                                                <div class="col-lg-12">
                                                    <div class="mb-3">
                                                        <div class="form-check">
                                                            <input type="checkbox" class="form-check-input" id="invalidCheck" required checked>
                                                          <p class="mb-0">By registering you agree to the <?= config_item('company_name'); ?> <a href="#" class="text-primary">Terms of Use</a></p>
                                                            <div class="invalid-feedback">
                                                                You must agree before submitting.
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                            
                                            <div class="mb-3">
                                                <button class="btn btn-primary w-100 waves-effect waves-light" id="registerButton" type="submit">Register</button>
                                            
                                            </div>
                                        </form>
                                        <div class="mt-5 text-center">
                                            <p class="text-muted mb-0">Already have an account ? <a href="<?php echo site_url('site/login');?>"  class="text-primary fw-semibold"> Login </a> </p>
                                        </div>
                                    </div>
                                    <div class="mt-4 mt-md-5 text-center">
                                       <p class="mb-0">© <script>document.write(new Date().getFullYear())</script> <?php echo config_item('company_name') ?>   <br></p>
                                    </div>

                                </div>
                            </div>
                        </div>
                        <!-- end auth full page content -->
                    </div>
                    <!-- end col -->
                    <div class="col-xxl-5 col-lg-5 col-md-12 h-120">
                        <div class="auth-bg pt-md-5 p-4 d-flex">
                            <div class="bg-overlay"></div>
                            <ul class="bg-bubbles">
                                <li></li>
                                <li></li>
                                <li></li>
                                <li></li>
                                <li></li>
                                <li></li>
                                <li></li>
                                <li></li>
                                <li></li>
                                <li></li>
                            </ul>
                            <!-- end bubble effect -->
                            <div class="row justify-content-center align-items-end">
                                <div class="col-xl-7 h-100">
                                    <div class="p-0 p-sm-4 px-xl-0">
                                        <div id="reviewcarouselIndicators" class="carousel slide" data-bs-ride="carousel">
                                            <!--<div class="carousel-indicators auth-carousel carousel-indicators-rounded justify-content-center mb-0">-->
                                            <!--    <button type="button" data-bs-target="#reviewcarouselIndicators" data-bs-slide-to="0" class="active" aria-current="true" aria-label="Slide 1">-->
                                                    <!--<img src="assets/images/users/avatar-1.jpg" class="avatar-md img-fluid rounded-circle d-block" alt="...">-->
                                            <!--    </button>-->
                                            <!--    <button type="button" data-bs-target="#reviewcarouselIndicators" data-bs-slide-to="1" aria-label="Slide 2">-->
                                                    <!--<img src="assets/images/users/avatar-2.jpg" class="avatar-md img-fluid rounded-circle d-block" alt="...">-->
                                            <!--    </button>-->
                                            <!--    <button type="button" data-bs-target="#reviewcarouselIndicators" data-bs-slide-to="2" aria-label="Slide 3">-->
                                                    <!--<img src="assets/images/users/avatar-3.jpg" class="avatar-md img-fluid rounded-circle d-block" alt="...">-->
                                            <!--    </button>-->
                                            <!--</div>-->
                                            <!-- end carouselIndicators -->
                                            <div class="carousel-inner">
                                                

                                                <div class="carousel-item active">
                                                    <div class="testi-contain text-center text-white">
                                                        <i class="bx bxs-quote-alt-left text-success display-6"></i>
                                                        <h4 class="mt-4 fw-medium lh-base text-white">“Remember, this is just the start. What’s coming ahead is bigger, brighter, and far more powerful than anything you’ve imagined.”</h4>
                                                        <div class="mt-4 pt-1 pb-5 mb-5">
                                                            <h5 class="font-size-16 text-white">Mr. Ashokrao Khot</h5>
                                                            <p class="mb-0 text-white-50">Manager</p>
                                                        </div>
                                                    </div>
                                                </div>

                                                <div class="carousel-item">
                                                    <div class="testi-contain text-center text-white">
                                                        <i class="bx bxs-quote-alt-left text-success display-6"></i>
                                                        <h4 class="mt-4 fw-medium lh-base text-white">“Today is the beginning of a journey. The best days, the biggest achievements, and the highest victories are still ahead.”</h4>
                                                        <div class="mt-4 pt-1 pb-5 mb-5">
                                                            <h5 class="font-size-16 text-white">Mrs.Pratiksha Thakare</h5>
                                                            <p class="mb-0 text-white-50">Director</p>
                                                        </div>
                                                    </div>
                                                </div>
                                                
                                                <div class="carousel-item">
                                                    <div class="testi-contain text-center text-white">
                                                        <i class="bx bxs-quote-alt-left text-success display-6"></i>
                                                        <h4 class="mt-4 fw-medium lh-base text-white">“Every success story begins with a small step. This is that step… and the best part of your journey is still on the way.”</h4>
                                                        <div class="mt-4 pt-1 pb-5 mb-5">
                                                            <h5 class="font-size-16 text-white">Mrs.Monali Umate</h5>
                                                            <p class="mb-0 text-white-50">Director</p>
                                                        </div>
                                                    </div>
                                                </div>
                                                
                                                 <div class="carousel-item">
                                                    <div class="testi-contain text-center text-white">
                                                        <i class="bx bxs-quote-alt-left text-success display-6"></i>
                                                        <h4 class="mt-4 fw-medium lh-base text-white">“Your journey has only begun. The universe has something extraordinary planned for you—the best is yet to come.”</h4>
                                                        <div class="mt-4 pt-1 pb-5 mb-5">
                                                            <h5 class="font-size-16 text-white">Mr.Ganesh Bhosale</h5>
                                                            <p class="mb-0 text-white-50">Technical</p>
                                                        </div>
                                                    </div>
                                                </div>
                                                
                                            </div>
                                            <!-- end carousel-inner -->
                                        </div>
                                        <!-- end review carousel -->
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <!-- end col -->
                </div>
                <!-- end row -->
            </div>
            <!-- end container fluid -->
        </div>
  <?php echo form_close(); ?>
 
    <script>
        document.getElementById('registrationForm').addEventListener('submit', function(event) {
            document.getElementById('registerButton').disabled = true;
            setTimeout(function() {
                document.getElementById('registerButton').disabled = false;
            }, 5000);
        });
    </script>

  <script type="text/javascript">
    function toogle_div(id1, id2) {
        if ($(id2).prop("checked") == true) {
            $(id1).hide('slow');
        } else {
            $(id1).show('slow');
        }
    }

    function show() {
        $('#form').hide('slow');
        $('#load').show('slow');
    }


    function get_user_name(id, result) {
        var myString = $(id).val();
        // let numbersOnly = myString.replace(/\D/g, "");
        var id =  myString.replace(/\D/g, "");
        $.get("<?php echo site_url('site/get_user_name/') ?>" + id, function (data) {
            $(result).html(data);
        });
    }
    
    function get_position_user_name(id, result) {
        var myString = $(id).val();
        // let numbersOnly = myString.replace(/\D/g, "");
        var id =  myString.replace(/\D/g, "");
        $.get("<?php echo site_url('site/get_user_name/') ?>" + id, function (data) {
            $(result).html(data);
        });
    }

</script>


<script>
var x = document.getElementById("latitude");
var y= document.getElementById("longitude");

function getLocation() {
  if (navigator.geolocation) {
    navigator.geolocation.getCurrentPosition(showPosition, showError);
  } else { 
    x.innerHTML = "Geolocation is not supported by this browser.";
  }
}

function showPosition(position) {
   x.setAttribute('value', position.coords.latitude);
   y.setAttribute('value', position.coords.longitude);

}

function showError(error) {
  switch(error.code) {
    case error.PERMISSION_DENIED:
      x.innerHTML = "User denied the request for Geolocation."
      break;
    case error.POSITION_UNAVAILABLE:
      x.innerHTML = "Location information is unavailable."
      break;
    case error.TIMEOUT:
      x.innerHTML = "The request to get user location timed out."
      break;
    case error.UNKNOWN_ERROR:
      x.innerHTML = "An unknown error occurred."
      break;
  }
}
</script>

<script>
    document.getElementById('join_package').addEventListener('change', function() {
        
        const selectedValue = this.value;
        const [id, price] = selectedValue.split('|'); 
        
        if (parseInt(id) === 3 && parseFloat(price) === 5000) 
        {
            document.getElementById('free_join').value = 'yes';
        } else {
            document.getElementById('free_join').value = 'no';
        }

        //console.log("Free Join:", document.getElementById('free_join').value); // for debug
    });
</script>