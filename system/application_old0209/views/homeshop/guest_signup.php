<div class="panel panel-body">
 <div class="container"> 
 <div class="text-center text-muted mb-4">
               <h1> Checkout as Guest</h1>
              </div>

      <?php if (config_item('disable_registration') !== "Yes") { ?>
    <?php echo form_open() ?>


    <!-- Page content -->
    <div class="container mt--8 pb-5"> 
       <?php echo validation_errors('<div class="alert alert-danger">', '</div>') ?>
                <?php echo $this->session->flashdata('site_flash') ?>
                
      <!-- Table -->
      <div class="row justify-content-center">
        <div class="col-lg-6 col-md-6">
          <div class="card bg-secondary shadow border-0">
            <div class="card-body px-lg-5 py-lg-3">
              
              <div class="formbox">

                  <div id="map"></div>
                  <div class="row">
                    <div class="form-group col-sm-6">
                      
                            <input type="text" class="form-control form-control-alternative" id="name" name="name" placeholder="Your name"
                                       placeholder="Mr Xyz">
                           
                    </div>

                    <div class="form-group col-sm-6">
                            <input type="text" class="form-control form-control-alternative" id="phone" name="phone" placeholder="Phone Number i.e.999999999">
                    </div>

                    <div class="form-group col-sm-6">
                       <!--    <label for="email" class="control-label">Email Id.</label> -->
                        <input type="email" class="form-control form-control-alternative" value="<?php echo set_value('email') ?>" id="email"
                               name="email"
                               placeholder="name@domain.com">
                    </div>

                   
                
                    <?php 
                    if (config_item('enable_pg') == "Yes" && config_item('free_registration') == "No") {
                        ?>
                        
                        <div class="form-group col-sm-6">
                            <label for="epin" class="control-label" style="color: #3a80d7">Payment Gateway</label><br/>
                            <input type="checkbox" value="yes" id="pg" class="form-control form-control-alternative" name="pg" onclick="toogle_div('#e_pin', '#pg')"> Pay Online
                        </div>
                      </div>
                        <?php
                    }
                    
                    ?>
                   
                      <div class="form-group col-sm-6">
                          <!--   <label for="address_1" class="control-label">Address Line 1<span style="color: red;">*</span></label> -->
                            <!-- <input type="text" class="form-control" value="<?php echo set_value('address_1') ?>" id="address_1"
                                   name="address_1"> -->

                                    <input type="text" class="form-control form-control-alternative" placeholder="Address i.e. Pune" id="address_1"
                                   name="address_1">
                        </div>
                      

                        <div class="form-group col-sm-6">
                              <!--   <label for="address_2" class="control-label">Address Line 2</label> -->
                                <input type="text" class="form-control form-control-alternative" value="<?php echo set_value('address_2') ?>" id="address_2"  placeholder="Address2 i.e.India"
                                       name="address_2">
                        </div>

                        <div class="form-group col-sm-6">
                         <!--    <label for="password" class="control-label">Password<span style="color: red;">*</span></label> -->
                           <!--  <input type="password" class="form-control" value="<?php echo set_value('password') ?>" id="password"
                                   name="password"> -->
                                    <input type="password" class="form-control form-control-alternative" id="password"
                                   name="password" placeholder="Password *****" >
                       </div>
                       <div class="form-group col-sm-6">
                        <!--     <label for="password_2" class="control-label">Retype Password<span style="color: red;"> *</span></label> -->
                           <!--  <input type="password" class="form-control" value="<?php echo set_value('password_2') ?>"
                                   id="password_2"
                                   name="password_2"> -->
                          <input type="password" class="form-control form-control-alternative" 
                                   id="password_2" placeholder="Re enter Password *****" 
                                   name="password_2">
                        </div>
                    
                    
                    <div class="form-group col-sm-12">
                        <label for="policy" class="control-label"><input type="checkbox" value="" checked> By clicking on register , you agree to our terms and that you have read our data us policy. </label>
                           
                        <button class="btn btn-primary" type="submit" onclick="show()">Register</button>
                        <!--  <a href="<?php echo site_url('homeshop/pre_checkout') ?>" class="btn btn-success">Checkout &rarr;</a>
 -->
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




</div>
</div>



<script type="text/javascript">

    function show() {
        $('#form').hide('slow');
        $('#load').show('slow');
    }

    

</script>