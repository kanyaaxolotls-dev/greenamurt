

   <?php if (config_item('disable_registration') !== "Yes") {?>
    <?php echo form_open() ?>


   <div class="header bg-gradient-primary py-7 py-lg-7">
      <div class="container">
        <div class="header-body text-center mb-5">
          <div class="row justify-content-center">
            <div class="col-lg-5 col-md-6">
              <h1 class="text-white">Hey! Join us today</h1>
              <p class="text-lead text-light">You can create new account in simple step.</p>
            </div>
          </div>
        </div>
      </div>
      
      <div class="separator separator-bottom separator-skew zindex-100">
        <svg x="0" y="0" viewBox="0 0 2560 100" preserveAspectRatio="none" version="1.1" xmlns="http://www.w3.org/2000/svg">
          <polygon class="fill-default" points="2560 0 2560 100 0 100"></polygon>
        </svg>
      </div>
    </div>
    <!-- Page content -->
    <div class="container mt--8 pb-5">
      <?php echo validation_errors('<div class="alert alert-danger">', '</div>') ?>
       <?php echo $this->session->flashdata('site_flash') ?>
      <!-- Table -->
      <div class="row justify-content-center">
        <div class="col-lg-8 col-md-8">
          <div class="card bg-secondary shadow border-0">
           <!--  <div class="card-header bg-transparent pb-5">
              <div class="text-muted text-center mt-2 mb-4"><small>Sign up with</small></div>
              <div class="text-center">
                <a href="#" class="btn btn-neutral btn-icon mr-4">
                  <span class="btn-inner--icon"><img src="../assets/img/icons/common/github.svg"></span>
                  <span class="btn-inner--text">Github</span>
                </a>
                <a href="#" class="btn btn-neutral btn-icon">
                  <span class="btn-inner--icon"><img src="../assets/img/icons/common/google.svg"></span>
                  <span class="btn-inner--text">Google</span>
                </a>
              </div>
            </div> -->


            <div class="card-body px-lg-5 py-lg-3">
             <!--  <div class="text-center text-muted mb-4">
                <small>Sign up with credentials</small>
              </div> -->

              <br>
              <div class="formbox">

                  <div id="map"></div>
                  <div class="row">
                  <div class="col-sm-6">
                    <div class="form-group">
                       <div class="input-group input-group-alternative mb-3">
                       <div class="input-group-prepend">
                          <span class="input-group-text"><i class="ni ni-hat-3"></i></span>

                       </div>
                        <input  
                        required
                        pattern = '^[A-Za-z]+([\ A-Za-z]+)*'
                        placeholder="Your name"
                        title = "Enter correct name, no number ,no symbols "
                        type="text"
                        class="form-control form-control-alternative"
                        id="name" name="name"
                        
                        
                        >
                        </div>

                        <!-- oninvalid="setCustomValidity('Enter correct name, no number ,no symbols');"
                        onchange="try{setCustomValidity('Enter name only')}catch(e){}" -->


                    </div>
                  </div>

                  <div class="col-sm-6"> 
                    <div class="form-group">

                        <div class="input-group input-group-alternative mb-3">
                         <div class="input-group-prepend">
                            <span class="input-group-text"><i class="ni ni-hat-3"></i></span>
                         </div>


                         <input type="text" onchange="get_user_name('#sponsor', '#spn_res')" class="form-control form-control-alternative"
                               value="<?php if ($this->uri->segment(3) !== "epin") {
                                                    $uri4 = $this->uri->segment(4);
                                                }
                                                    ;
                            echo set_value('sponsor', $uri4)?>"
                               id="sponsor"
                               required
                               name="sponsor" 
                               placeholder="Sponsor Id"
                               title = "Enter correct  number"
                               pattern='^[0-9]{1,10}$'
                               
                               >

                            <!--    <input type="text" onchange="get_user_name('#sponsor', '#spn_res')" class="form-control"
                               id="sponsor" name="sponsor" placeholder="1235"> -->

                            </div>
                            <small id="spn_res" style="color: red;"></small>

                    </div>
                  </div>


                    <div class="form-group col-sm-6">

                       <!--  <input type="text" class="form-control" value="<?php echo set_value('phone') ?>" id="phone" name="phone" placeholder="9xxxxxxxxx"> -->

                        <input type="tel" 
                        
                        pattern="(?<!\d)\d{10}(?!\d)"
                        class="form-control form-control-alternative" 
                        id="phone" name="phone" 
                        placeholder="Phone Number i.e.1234567890" 
                        minlength="10" maxlength="10" required>

                  

                    </div>
                    <small id="ph_res" style="color: red;"></small>


                    <!-- onchange="try{setCustomValidity('Enter correct phone number, no name ,no symbols')}catch(e){}" -->

                    <div class="form-group col-sm-6">
                       <!--    <label for="email" class="control-label">Email Id.</label> -->
                        <input type="email" class="form-control form-control-alternative"
                                value="<?php echo set_value('email') ?>" id="email"
                               name="email"
                               placeholder="name@domain.com"
                               required
                               title="Enter proper Email-ID"
                               pattern ="[A-Za-z_.0-9-]+@{1}[a-z]+([.]{1}[a-z]{2,4})+" >
                    </div>
                    


                <?php if (config_item('leg') !== "1" && config_item('show_placement_id') == "Yes" && config_item('autopool_registration') == "No") {
        ?>

                        <div class="form-group col-sm-6">
                            
                            <input type="number" class="form-control form-control-alternative" 
                                    onchange="get_user_name('#position', '#psn_res')"
                                    required
                                   id="position" value="<?php echo $uri4 = $this->uri->segment(4) ?>"
                                   name="position" id="position" 
                                   title = "Under id - Where you want to place the ID"
                                   placeholder="Under / Placement"
                                   pattern="^[0-9]{1,8}$" 
                                   
                                   >
                                   <label for="position" class="control-label"><span
                                        id="psn_res"
                                        style="color: red; font-weight: bold"></span></label>
                        </div>

                        <?php
}
    if (config_item('leg') == "1") {
        echo form_hidden('leg', 'A');
    } else {
        if (config_item('show_leg_choose') == "Yes" && config_item('autopool_registration') == "No") {
            ?>
                             <div class="form-group col-sm-6">

                        <select class="form-control" id="leg" name="leg">
                            <?php if (trim($this->uri->segment(3)) !== "" && trim($this->uri->segment(3)) !== "epin") {
                echo '<option selected>' . $this->uri->segment(3) . '</option>';
            }?>
                            <?php foreach ($leg as $key => $val) {
                echo '<option value="' . $key . '">' . $val . ' </option>';
            }?>
                        </select>
                    </div>

                            <?php }
    }
    if (config_item('show_join_product') == "Yes") {
        ?>

                        <div class="form-group col-sm-6">
                         <!--    <label for="product" class="control-label">Sign Up Product</label> -->
                            <select class="form-control form-control-alternative" id="product" name="product">
                                <?php foreach ($products as $val) {
            echo '<option value="' . $val['id'] . '">' . $val['prod_name'] . '. Price :' . config_item('currency') . number_format($val['prod_price'] + ($val['prod_price'] * $val['gst'] / 100), 2) . ' </option>';
        }?>

                            </select>
                        </div>

                        <?php
}

    if (config_item('show_join_product') == "No" && config_item('free_registration') == "No") {
        ?>

                        <div class="form-group col-sm-6" id="amt_to_pay">
                            <!-- <label for="amt_to_pay" class="control-label">Amount You Want to Pay ?</label> -->
                            <input type="text" required value="<?php echo set_value('amt_to_pay') ?>" class="form-control form-control-alternative"
                                   id="amt_to_pay"
                                   name="amt_to_pay" placeholder="10"
                                   
                                   pattern="^[0-9]{1,8}$"
                                   title="Recheck amount before submitting" 
                                   >
                        </div>

                    <?php }
    if (config_item('enable_epin') == "Yes" && config_item('free_registration') == "No") {
        ?>
                        <div class="form-group col-sm-6" id="e_pin">

                            <input type="number" value="<?php if (trim($this->uri->segment(3)) == "epin") {
                                    echo set_value('epin', $this->uri->segment(4));
                                  }?>"
                                  class="form-control" 
                                   id="epin"
                                   name="epin" 
                                   placeholder="ePin"
                                   pattern="^[0-9]{1,8}$"
                                   title="Recheck ePin before submitting" 
                                   required
                                  
                                   >
                        </div>
                    <?php }
    if (config_item('enable_pg') == "Yes" && config_item('free_registration') == "No") {
        ?>
                       <!--  <div class="form-group">
                           <input type="checkbox" value="yes" id="pg" class="form-control form-control-alternative" name="pg" onclick="toogle_div('#e_pin', '#pg')"> Pay Online
                        </div> -->
                        <div class="form-group col-sm-6" id="amt_to_pay">
                        <div class="custom-control custom-checkbox mb-3">
                          <input class="custom-control-input" name="pg" id="pg" type="checkbox">
                          <label class="custom-control-label" for="pg">Online Pay</label>
                        </div>
                      </div>
                       <?php }?>

                      <div class="form-group col-sm-6">
                          <!--   <label for="address_1" class="control-label">Address Line 1<span style="color: red;">*</span></label> -->
                            <!-- <input type="text" class="form-control" value="<?php echo set_value('address_1') ?>" id="address_1"
                                   name="address_1"> -->

                                    <input type="text"
                                     class="form-control form-control-alternative"
                                      placeholder="Address i.e. New York" 
                                      id="address_1"
                                   name="address_1"
                                     required
                                   >
                        </div>
                        <div class="form-group col-sm-6">
                              <!--   <label for="address_2" class="control-label">Address Line 2</label> -->
                                <input type="text" 
                                class="form-control form-control-alternative" 
                                value="<?php echo set_value('address_2') ?>" 
                                id="address_2"  
                                placeholder="Address2 i.e.USA"
                                       name="address_2"
                                         required
                                   >
                        </div>
                        <!-- <div class="form-group col-sm-6">
                          <input type="text" class="form-control form-control-alternative" id="key"
                                   name="key" placeholder="USDT Address" >
                       </div> -->

                        <div class="form-group col-sm-6">
                         <!--    <label for="password" class="control-label">Password<span style="color: red;">*</span></label> -->
                           <!--  <input type="password" class="form-control" value="<?php echo set_value('password') ?>" id="password"
                                   name="password"> -->
                                    <input required type="password" class="form-control form-control-alternative" id="password"
                                   name="password" placeholder="Password *****" >
                       </div>
                       <div class="form-group col-sm-6">
                        <!--     <label for="password_2" class="control-label">Retype Password<span style="color: red;"> *</span></label> -->
                           <!--  <input type="password" class="form-control" value="<?php echo set_value('password_2') ?>"
                                   id="password_2"
                                   name="password_2"> -->
                          <input required type="password" class="form-control form-control-alternative"
                                   id="password_2" placeholder="Re enter Password *****"
                                   name="password_2">
                        </div>
                    <div class="form-group col-sm-12">
                        <label for="policy" class="control-label"><input type="checkbox" value="" checked> By clicking on register , you agree to our terms and that you have read our data us policy. </label>

                        <button class="btn" data-toggle="modal" data-target="#myModal" type="submit" onclick="show()">Register</button>

                    </div>
              </div>

              </div>

                    <!-- End of form -->
            </div>
          </div>
        </div>
      </div>
    </div>
  <?php echo form_close();
} else {
    echo "<h3 align='center' style='margin: 10%'> Registration is disabled for maintanance. Please come later.</h3>";
} ?>




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
        var id = $(id).val();
        $.get("<?php echo site_url('site/get_user_name/') ?>" + id, function (data) {
            $(result).html(data);
        });
    }

</script>