
<div class="alert alert-danger">
    <strong>Warning !</strong> Do not change anything if you dont know anything!
</div>
<div class="col">
    <div class="card bg-secondary shadow">  
        <div class="card-header bg-white border-0"> 
        <div class="row align-items-center">
            <div class="col-8">
                  <h3 class="mb-0"><?php echo $title;?></h3>
            </div>
        <div class="col-4 text-right"> 
            <a href="<?php echo site_url('admin/index')?>" class="btn btn-sm btn-primary">Home</a>
        </div>
    </div>
</div> 
<div class="card-body">

    <?php echo form_open() ?>
   
          <div class="card-header">
              <a data-toggle="collapse" href="#collapse1">Company Setting</a>
          </div>
          <div id="collapse1" class="panel-collapse collapse">
            <div class="card-body">
                <div class="row">
                    <div class="col-sm-6">
                    <label>Company Name</label>
                    <input type="text" class="form-control form-control-alternative" value="<?php echo set_value('company_name', config_item('company_name')) ?>" name="company_name">
                    </div>
                    <div class="col-sm-6">
                    <label>Company Address</label>
                    <input class="form-control form-control-alternative" value="<?php echo set_value('company_address', config_item('company_address')) ?>"
                        name="company_address">
                  </div>
                    <div class="col-sm-6 mt-2">
                    <label>Mobile Number</label>
                    <input class="form-control form-control-alternative" value="<?php echo set_value('web_phone', config_item('web_phone')) ?>"
                        name="web_phone">
                  </div>
                    <div class="col-sm-6 mt-2">
                    <label>Email</label>
                    <input class="form-control form-control-alternative" value="<?php echo set_value('web_email', config_item('web_email')) ?>"
                        name="web_email">
                  </div>
                  <div class="col-sm-6 mt-2">
                    <label>Bank Name</label>
                    <input class="form-control form-control-alternative" value="<?php echo set_value('bank_name', config_item('bank_name')) ?>"
                        name="bank_name">
                  </div>
                  <div class="col-sm-6 mt-2">
                    <label>Account Name</label>
                    <input class="form-control form-control-alternative" value="<?php echo set_value('account_name', config_item('account_name')) ?>"
                        name="account_name">
                  </div>
                  <div class="col-sm-6 mt-2">
                    <label>Company Account Number</label>
                    <input class="form-control form-control-alternative" value="<?php echo set_value('account_number', config_item('account_number')) ?>"
                        name="account_number">
                  </div>
                  <div class="col-sm-6 mt-2">
                    <label>IFSC Code</label>
                    <input class="form-control form-control-alternative" value="<?php echo set_value('ifsc_Code', config_item('ifsc_Code')) ?>"
                        name="ifsc_Code">
                  </div>
                  <div class="col-sm-6 mt-2">
                    <label>Bank Branch</label>
                    <input class="form-control form-control-alternative" value="<?php echo set_value('bank_branch', config_item('bank_branch')) ?>"
                        name="bank_branch">
                  </div>
                  <div class="col-sm-6 mt-2">
                    <label>Select Template</label>
                        <select class="form-control form-control-alternative" name="select_temp">
                            <option selected><?php echo set_value('select_temp', config_item('select_temp')) ?></option>
                            <option>Eccomerce</option>
                            <option>Eccomerce Without Functionality</option>
                            <option>Static</option>
                        </select>
                  </div>
                  <div class="col-sm-6 mt-2">
                    <label>Website Status</label>
                        <select class="form-control form-control-alternative" name="web_status">
                            <option selected><?php echo set_value('web_status', config_item('web_status')) ?></option>
                            <option>Coming Soon</option>
                            <option>Live</option>
                            <option>Suspend</option>
                        </select>
                  </div>
            </div>
            </div>
           
          </div>
     

        <div class="card-header">
              <a data-toggle="collapse" href="#collapse2">Currency Setting</a>
          </div>
        <div id="collapse2" class="panel-collapse collapse">
            <div class="card-body">
                <div class="row">
                 <div class="col-sm-4">
                    <label>ID Prefix</label>
                    <input type="text" class="form-control form-control-alternative" value="<?php echo set_value('id_ext', config_item('ID_EXT')) ?>"
                   name="id_ext">
                </div>
                <div class="col-sm-4">
                    <label>Currency Sign</label>
                    <input type="text" class="form-control form-control-alternative" value="<?php echo set_value('currency', config_item('currency')) ?>"
                           name="currency">
                </div>
                <div class="col-sm-4">
                    <label>Currency ISO Code (3 Character)</label>
                    <input type="text" class="form-control form-control-alternative"
                           value="<?php echo set_value('iso_currency', config_item('iso_currency')) ?>"
                           name="iso_currency">
                </div>
            </div>
            </div>
            
        </div>


        <!-- Plan setting -->
          <div class="card-header">
              <a data-toggle="collapse" href="#collapse3">Plan Setting</a>
           
          </div>
          <div id="collapse3" class="panel-collapse collapse">
            <div class="card-body">
                <h6 class="heading-small text-muted mb-4">Plan & Master Setting</h6>
                <div class="row">
                    <div class="col-sm-4 col-4">
                        <label>Legs in Tree ?</label>
                        <select class="form-control form-control-alternative" name="leg">
                            <option selected><?php echo set_value('leg', config_item('leg')) ?></option>
                            <option>1</option>
                            <option>2</option>
                            <option>3</option>
                            <option>4</option>
                            <option>5</option>
                            

                        </select>
                    </div>
                    <div class="col-sm-4 col-4">
                        <label>Leg option at Sign Up ?</label>
                        <select class="form-control form-control-alternative" name="show_leg_choose">
                            <option selected><?php echo set_value('show_leg_choose', config_item('show_leg_choose')) ?></option>
                            <option>Yes</option>
                            <option>No</option>
                        </select>
                    </div>
                    <div class="col-sm-4 col-4">
                        <label>Placement ID at Sign Up ?</label>
                        <select class="form-control form-control-alternative" name="show_placement_id">
                            <option selected><?php echo set_value('show_placement_id', config_item('show_placement_id')) ?></option>
                            <option>Yes</option>
                            <option>No</option>
                        </select>
                    </div>
                </div>
                <h6 class="heading-small text-muted mb-4">Product & PIN Setting</h6>
                <div class="row">

                    <div class="col-sm-4">
                        <label>Autopool Registration ?</label>
                        <select class="form-control form-control-alternative" name="autopool_registration">
                            <option selected><?php echo set_value('autopool_registration', config_item('autopool_registration')) ?></option>
                            <option>Yes</option>
                            <option>No</option>
                        </select>
                    </div>
                    <div class="col-sm-4">
                        <label>Joining Products at Signup ?</label>
                        <select class="form-control form-control-alternative" name="show_join_product">
                            <option selected><?php echo set_value('show_join_product', config_item('show_join_product')) ?></option>
                            <option>Yes</option>
                            <option>No</option>
                        </select>
                    </div>
                    <div class="col-sm-4">
                        <label>Enable e-PIN ?</label>
                        <select class="form-control form-control-alternative" name="enable_epin">
                            <option selected><?php echo set_value('enable_epin', config_item('enable_epin')) ?></option>
                            <option>Yes</option>
                            <option>No</option>
                        </select>
                    </div>
                </div> 
                  <h6 class="heading-small text-muted mb-4">Signup Setting</h6>
                <div class="row">
                    <div class="col-sm-4">
                        <label>Enable Payment Gateway ?</label>
                        <select class="form-control form-control-alternative" name="enable_pg">
                            <option selected><?php echo set_value('enable_pg', config_item('enable_pg')) ?></option>
                            <option>Yes</option>
                            <option>No</option>
                        </select>
                    </div>
                    <div class="col-sm-4">
                        <label>Free Signup ?</label>
                        <select class="form-control form-control-alternative" name="free_registration">
                            <option selected><?php echo set_value('free_registration', config_item('free_registration')) ?></option>
                            <option>Yes</option>
                            <option>No</option>
                        </select>
                    </div>
                    <div class="col-sm-4">
                        <label>Enable Top-Up ?</label>
                        <select class="form-control form-control-alternative" name="enable_topup">
                            <option selected><?php echo set_value('enable_topup', config_item('enable_topup')) ?></option>
                            <option>Yes</option>
                            <option>No</option>
                        </select>
                    </div>
                     

                </div>
                <h6 class="heading-small text-muted mb-4">Comission Setting</h6>
                <div class="row">
                    <div class="col-sm-4">
                        <label>Income on Top-Up ?</label>
                        <select class="form-control form-control-alternative" name="give_income_on_topup">
                            <option selected><?php echo set_value('give_income_on_topup', config_item('give_income_on_topup')) ?></option>
                            <option>Yes</option>
                            <option>No</option>
                        </select>
                    </div>
                    <div class="col-sm-4">
                        <label>Gap Commission on Repurchase ?</label>
                        <select class="form-control form-control-alternative" name="enable_gap_commission">
                            <option selected><?php echo set_value('enable_gap_commission', config_item('enable_gap_commission')) ?></option>
                            <option>Yes</option>
                            <option>No</option>
                        </select>
                    </div>
                    <div class="col-sm-4">
                        <label>SMS on Registration ?</label>
                        <select class="form-control form-control-alternative" name="sms_on_join">
                            <option selected><?php echo set_value('sms_on_join', config_item('sms_on_join')) ?></option>
                            <option>Yes</option>
                            <option>No</option>
                        </select>
                    </div>
                </div>
                <h6 class="heading-small text-muted mb-4">Product Setting</h6>
                <div class="row">
                    <div class="col-sm-4">
                        <label>Auto products as Delivered ?</label>
                        <select class="form-control form-control-alternative" name="prevent_join_product_entry">
                            <option selected
                                    value="<?php echo set_value('prevent_join_product_entry', config_item('prevent_join_product_entry')) ?>"><?php echo set_value('prevent_join_product_entry', config_item('prevent_join_product_entry')) ?></option>
                            <option>Yes</option>
                            <option>No</option> 
                        </select>
                    </div>
                  
                    <div class="col-sm-4">
                        <label>Hold Signup process ?</label>
                        <select class="form-control form-control-alternative" name="disable_registration">
                            <option selected
                                    value="<?php echo set_value('disable_registration', config_item('disable_registration')) ?>"><?php echo set_value('disable_registration', config_item('disable_registration')) ?></option>
                            <option>Yes</option>
                            <option>No</option>
                        </select>
                    </div>
                    
                    <div class="col-sm-4">
                        <label>Task Based Plan?</label>
                        <select class="form-control form-control-alternative" name="task_based">
                            <option selected
                                    value="<?php echo set_value('task_based', config_item('task_based')) ?>"><?php echo set_value('task_based', config_item('task_based')) ?></option>
                            <option>Yes</option>
                            <option>No</option>
                        </select>
                    </div>
                    
                    <div class="col-sm-4">
                        <label>Auto Payout?</label>
                        <select class="form-control form-control-alternative" name="auto_payout">
                            <option selected
                                    value="<?php echo set_value('auto_payout', config_item('auto_payout')) ?>"><?php echo set_value('auto_payout', config_item('auto_payout')) ?></option>
                            <option>Yes</option>
                            <option>No</option>
                        </select>
                    </div>
                </div>
                <h6 class="heading-small text-muted mb-4">Income Setting</h6>
                <div class="row">

                    <div class="col-6">
                        <label>Give Fix Income (Not Product/Service Based Income)</label>
                        <select class="form-control form-control-alternative" name="fix_income">
                            <option selected
                                    value="<?php echo set_value('fix_income', config_item('fix_income')) ?>"><?php echo set_value('fix_income', config_item('fix_income')) ?></option>
                            <option>Yes</option>
                            <option>No</option>
                        </select>
                    </div>

                    <div class="col-sm-6">
                        <label>SMS API
                        <p>(Format: https://apiurl.com?no={{phone}}&msg={{msg}}&other_parameters.)
                        </label>
                        <input type="text" class="form-control form-control-alternative" value="<?php echo set_value('sms_api', config_item('sms_api')) ?>"
                               name="sms_api">
                    </div>

                </div>

            </div>
            
        </div>

        <!-- Add on setting -->
          <div class="card-header">
              <a data-toggle="collapse" href="#collapse4">Add on Package Setting</a>
          </div>
          <div id="collapse4" class="panel-collapse collapse">
            <div class="card-body">
                 <div class="row">
                <div class="col-sm-4">
                        <label>Enable Repurchase System ?</label>
                        <select class="form-control form-control-alternative" name="enable_repurchase">
                            <option selected
                                    value="<?php echo set_value('enable_repurchase', config_item('enable_repurchase')) ?>"><?php echo set_value('enable_repurchase', config_item('enable_repurchase')) ?></option>
                            <option>Yes</option>
                            <option>No</option>
                        </select>
                    </div>
                    <div class="col-sm-4">
                        <label>Enable Coupon ?</label>
                        <select class="form-control form-control-alternative" name="enable_coupon">
                            <option selected
                                    value="<?php echo set_value('enable_coupon', config_item('enable_coupon')) ?>"><?php echo set_value('enable_coupon', config_item('enable_coupon')) ?></option>
                            <option>Yes</option>
                            <option>No</option>
                        </select>
                    </div>
                    <div class="col-sm-4">
                        <label>Enable Advertisement Income ?</label>
                        <select class="form-control form-control-alternative" name="enable_ad_incm">
                            <option selected
                                    value="<?php echo set_value('enable_ad_incm', config_item('enable_ad_incm')) ?>"><?php echo set_value('enable_ad_incm', config_item('enable_ad_incm')) ?></option>
                            <option>Yes</option>
                            <option>No</option>
                        </select>
                    </div>
                </div>
                <div class="row">
                    <div class="col-sm-4">
                        <label>Enable Survey ?</label>
                        <select class="form-control form-control-alternative" name="enable_survey">
                            <option selected
                                    value="<?php echo set_value('enable_survey', config_item('enable_survey')) ?>"><?php echo set_value('enable_survey', config_item('enable_survey')) ?></option>
                            <option>Yes</option>
                            <option>No</option>
                        </select>
                    </div>
                    <div class="col-sm-4">
                        <label>Enable Recharge Module ?</label>
                        <select class="form-control form-control-alternative" name="enable_recharge">
                            <option selected
                                    value="<?php echo set_value('enable_recharge', config_item('enable_recharge')) ?>"><?php echo set_value('enable_recharge', config_item('enable_recharge')) ?></option>
                            <option>Yes</option>
                            <option>No</option>
                        </select>
                    </div>
                    <div class="col-sm-4">
                        <label>Enable Rewards ?</label>
                        <select class="form-control form-control-alternative" name="enable_reward">
                            <option selected
                                    value="<?php echo set_value('enable_reward', config_item('enable_reward')) ?>"><?php echo set_value('enable_reward', config_item('enable_reward')) ?></option>
                            <option>Yes</option>
                            <option>No</option>
                        </select>
                    </div>
                </div>
                <div class="row">
                    <div class="col-sm-4">
                        <label>Enable Product & Services ?</label>
                        <select class="form-control form-control-alternative" name="enable_product">
                            <option selected
                                    value="<?php echo set_value('enable_product', config_item('enable_product')) ?>"><?php echo set_value('enable_product', config_item('enable_product')) ?></option>
                            <option>Yes</option>
                            <option>No</option>
                        </select>
                    </div>
                    <div class="col-sm-4">
                        <label>Enable Help Plan ?</label>
                        <select class="form-control form-control-alternative" name="enable_help_plan">
                            <option selected
                                    value="<?php echo set_value('enable_help_plan', config_item('enable_help_plan')) ?>"><?php echo set_value('enable_help_plan', config_item('enable_help_plan')) ?></option>
                            <option>Yes</option>
                            <option>No</option>
                        </select>
                    </div>
                    <div class="col-sm-4">
                        <label>Enable Investment Plan ?</label>
                        <select class="form-control form-control-alternative" name="enable_investment">
                            <option selected
                                    value="<?php echo set_value('enable_investment', config_item('enable_investment')) ?>"><?php echo set_value('enable_investment', config_item('enable_investment')) ?></option>
                            <option>Yes</option>
                            <option>No</option>
                        </select>
                    </div>
                </div>
                <div class="row">
                    <div class="col-sm-4">
                        <label>Investment Type ?</label>
                        <select class="form-control form-control-alternative" name="investment_mode">
                            <option selected
                                    value="<?php echo set_value('investment_mode', config_item('investment_mode')) ?>"><?php echo set_value('investment_mode', config_item('investment_mode')) ?></option>
                            <option>AUTO</option>
                            <option>EPIN</option>
                            <option>MANUAL</option>
                        </select>
                    </div>
                     <div class="col-sm-4"> 
                        <label>Upgrade Plan?</label>
                        <select class="form-control form-control-alternative" name="enable_upgrade">
                            <option selected
                                    value="<?php echo set_value('enable_upgrade', config_item('enable_upgrade')) ?>"><?php echo set_value('enable_upgrade', config_item('enable_upgrade')) ?></option>
                            <option>Yes</option>
                            <option>No</option>
                        </select>
                    </div>

                      <div class="col-sm-4">
                    
                          <label for="sel1">Select Theme</label>
                          <select class="form-control form-control-alternative" name="member_theme">
                            <option  value="<?php echo 'axxets/member/member_new_theme.css'; ?>">Blue</option>
                            <option  value="<?php echo 'axxets/member/member_blue.css'; ?>" selected>Navy Blue</option>
                            <option  value="<?php echo 'axxets/member/dark.css'; ?>">Dark</option>
                         </select>
                   
                    </div>
                </div>
            </div>
           
          </div>
       
        <!-- Cron setting starts -->
        <div class="card-header">
              <a data-toggle="collapse" href="#collapse5">Cron Setting</a>
          </div>
        <div id="collapse5" class="panel-collapse collapse">
            <div class="card-body">
                <div class="row">
                     <div class="col-sm-4">
                        <label>Minute</label>
                         <select class="form-control form-control-alternative" name="cron_minutes">
                            <option selected
                                    value="<?php echo set_value('cron_minutes', config_item('cron_minutes')) ?>"><?php echo set_value('cron_minutes', config_item('cron_minutes')) ?></option>
                                    <option value="*">
                                        Once Per Minute(*)
                                    </option>
                                    <option value="*/2">
                                        Once Per Two Minutes(*/2)
                                    </option>
                                    <option value="*/5">
                                        Once Per Five Minutes(*/5)
                                    </option>
                                    <option value="*/10">
                                        Once Per Ten Minutes(*/10)
                                    </option>
                                    <option value="*/15">
                                        Once Per Fifteen Minutes(*/15)
                                    </option>
                                    <option value="0,30">
                                        Once Per Thirty Minutes(0,30)
                                    </option>
                                    <option value="0">
                                        :00 (At the beginning of the hour.) (0)
                                    </option>
                                    <option value="1">
                                        :01 (1)
                                    </option>
                                    <option value="2">
                                        :02 (2)
                                    </option>
                                    <option value="3">
                                        :03 (3)
                                    </option>
                                    <option value="4">
                                        :04 (4)
                                    </option>
                                    <option value="5">
                                        :05 (5)
                                    </option>
                                    <option value="6">
                                        :06 (6)
                                    </option>
                                    <option value="7">
                                        :07 (7)
                                    </option>
                                    <option value="8">
                                        :08 (8)
                                    </option>
                                    <option value="9">
                                        :09 (9)
                                    </option>
                                    <option value="10">
                                        :10 (10)
                                    </option>
                                    <option value="11">
                                        :11 (11)
                                    </option>
                                    <option value="12">
                                        :12 (12)
                                    </option>
                                    <option value="13">
                                        :13 (13)
                                    </option>
                                    <option value="14">
                                        :14 (14)
                                    </option>
                                    <option value="15">
                                        :15 (At one quarter past the hour.) (15)
                                    </option>
                                    <option value="16">
                                        :16 (16)
                                    </option>
                                    <option value="17">
                                        :17 (17)
                                    </option>
                                    <option value="18">
                                        :18 (18)
                                    </option>
                                    <option value="19">
                                        :19 (19)
                                    </option>
                                    <option value="20">
                                        :20 (20)
                                    </option>
                                    <option value="21">
                                        :21 (21)
                                    </option>
                                    <option value="22">
                                        :22 (22)
                                    </option>
                                    <option value="23">
                                        :23 (23)
                                    </option>
                                    <option value="24">
                                        :24 (24)
                                    </option>
                                    <option value="25">
                                        :25 (25)
                                    </option>
                                    <option value="26">
                                        :26 (26)
                                    </option>
                                    <option value="27">
                                        :27 (27)
                                    </option>
                                    <option value="28">
                                        :28 (28)
                                    </option>
                                    <option value="29">
                                        :29 (29)
                                    </option>
                                    <option value="30">
                                        :30 (At half past the hour.) (30)
                                    </option>
                                    <option value="31">
                                        :31 (31)
                                    </option>
                                    <option value="32">
                                        :32 (32)
                                    </option>
                                    <option value="33">
                                        :33 (33)
                                    </option>
                                    <option value="34">
                                        :34 (34)
                                    </option>
                                    <option value="35">
                                        :35 (35)
                                    </option>
                                    <option value="36">
                                        :36 (36)
                                    </option>
                                    <option value="37">
                                        :37 (37)
                                    </option>
                                    <option value="38">
                                        :38 (38)
                                    </option>
                                    <option value="39">
                                        :39 (39)
                                    </option>
                                    <option value="40">
                                        :40 (40)
                                    </option>
                                    <option value="41">
                                        :41 (41)
                                    </option>
                                    <option value="42">
                                        :42 (42)
                                    </option>
                                    <option value="43">
                                        :43 (43)
                                    </option>
                                    <option value="44">
                                        :44 (44)
                                    </option>
                                    <option value="45">
                                        :45 (At one quarter until the hour.) (45)
                                    </option>
                                    <option value="46">
                                        :46 (46)
                                    </option>
                                    <option value="47">
                                        :47 (47)
                                    </option>
                                    <option value="48">
                                        :48 (48)
                                    </option>
                                    <option value="49">
                                        :49 (49)
                                    </option>
                                    <option value="50">
                                        :50 (50)
                                    </option>
                                    <option value="51">
                                        :51 (51)
                                    </option>
                                    <option value="52">
                                        :52 (52)
                                    </option>
                                    <option value="53">
                                        :53 (53)
                                    </option>
                                    <option value="54">
                                        :54 (54)
                                    </option>
                                    <option value="55">
                                        :55 (55)
                                    </option>
                                    <option value="56">
                                        :56 (56)
                                    </option>
                                    <option value="57">
                                        :57 (57)
                                    </option>
                                    <option value="58">
                                        :58 (58)
                                    </option>
                                    <option value="59">
                                        :59 (59)
                                    </option>
                        </select>
                    </div>
                    <div class="col-sm-4">
                        <label>Hour</label>
                        <select class="form-control form-control-alternative" name="cron_hours">
                         <option selected
                                    value="<?php echo set_value('cron_hours', config_item('cron_hours')) ?>"><?php echo set_value('cron_hours', config_item('cron_hours')) ?></option>
                                 <option value="*">
                                        Every Hour
                                            (*)
                                    </option>
                                    <option value="*/2">
                                        Every Other Hour
                                            (*/2)
                                    </option>
                                    <option value="*/3">
                                        Every Third Hour
                                            (*/3)
                                    </option>
                                    <option value="*/4">
                                        Every Fourth Hour
                                            (*/4)
                                    </option>
                                    <option value="*/6">
                                        Every Sixth Hour
                                            (*/6)
                                    </option>
                                    <option value="0,12">
                                        Every Twelve Hours
                                            (0,12)
                                    </option>
                                    <option value="0">
                                        12:00 a.m.
                                        Midnight
                                            (0)
                                    </option>
                                    <option value="1">
                                        1:00 a.m. (1)
                                    </option>
                                    <option value="2">
                                        2:00 a.m. (2)
                                    </option>
                                    <option value="3">
                                        3:00 a.m. (3)
                                    </option>
                                    <option value="4">
                                        4:00 a.m. (4)
                                    </option>
                                    <option value="5">
                                        5:00 a.m. (5)
                                    </option>
                                    <option value="6">
                                        6:00 a.m. (6)
                                    </option>
                                    <option value="7">
                                        7:00 a.m. (7)
                                    </option>
                                    <option value="8">
                                        8:00 a.m. (8)
                                    </option>
                                    <option value="9">
                                        9:00 a.m. (9)
                                    </option>
                                    <option value="10">
                                        10:00 a.m. (10)
                                    </option>
                                    <option value="11">
                                        11:00 a.m. (11)
                                    </option>
                                    <option value="12">
                                        12:00 p.m.
                                        Noon
                                            (12)
                                    </option>
                                    <option value="13">
                                        1:00 p.m. (13)
                                    </option>
                                    <option value="14">
                                        2:00 p.m. (14)
                                    </option>
                                    <option value="15">
                                        3:00 p.m. (15)
                                    </option>
                                    <option value="16">
                                        4:00 p.m. (16)
                                    </option>
                                    <option value="17">
                                        5:00 p.m. (17)
                                    </option>
                                    <option value="18">
                                        6:00 p.m. (18)
                                    </option>
                                    <option value="19">
                                        7:00 p.m. (19)
                                    </option>
                                    <option value="20">
                                        8:00 p.m. (20)
                                    </option>
                                    <option value="21">
                                        9:00 p.m. (21)
                                    </option>
                                    <option value="22">
                                        10:00 p.m. (22)
                                    </option>
                                    <option value="23">
                                        11:00 p.m. (23)
                                    </option>
                        </select>
                        
                    </div>
                    <div class="col-sm-4">
                        <label>Day</label>
                                <select class="form-control form-control-alternative" name="cron_day">
                               <option selected
                                    value="<?php echo set_value('cron_day', config_item('cron_day')) ?>"><?php echo set_value('cron_day', config_item('cron_day')) ?></option>
                                    <option value="*">
                                        Every Day
                                            (*)
                                    </option>
                                    <option value="*/2">
                                        Every Other Day
                                            (*/2)
                                    </option>
                                    <option value="1,15">
                                        On the 1st and 15th of the Month
                                            (1,15)
                                    </option>
                                    
                                    <option value="1">
                                        1st (1)
                                    </option>
                                    <option value="2">
                                        2nd (2)
                                    </option>
                                    <option value="3">
                                        3rd (3)
                                    </option>
                                    <option value="4">
                                        4th (4)
                                    </option>
                                    <option value="5">
                                        5th (5)
                                    </option>
                                    <option value="6">
                                        6th (6)
                                    </option>
                                    <option value="7">
                                        7th (7)
                                    </option>
                                    <option value="8">
                                        8th (8)
                                    </option>
                                    <option value="9">
                                        9th (9)
                                    </option>
                                    <option value="10">
                                        10th (10)
                                    </option>
                                    <option value="11">
                                        11th (11)
                                    </option>
                                    <option value="12">
                                        12th (12)
                                    </option>
                                    <option value="13">
                                        13th (13)
                                    </option>
                                    <option value="14">
                                        14th (14)
                                    </option>
                                    <option value="15">
                                        15th (15)
                                    </option>
                                    <option value="16">
                                        16th (16)
                                    </option>
                                    <option value="17">
                                        17th (17)
                                    </option>
                                    <option value="18">
                                        18th (18)
                                    </option>
                                    <option value="19">
                                        19th (19)
                                    </option>
                                    <option value="20">
                                        20th (20)
                                    </option>
                                    <option value="21">
                                        21st (21)
                                    </option>
                                    <option value="22">
                                        22nd (22)
                                    </option>
                                    <option value="23">
                                        23rd (23)
                                    </option>
                                    <option value="24">
                                        24th (24)
                                    </option>
                                    <option value="25">
                                        25th (25)
                                    </option>
                                    <option value="26">
                                        26th (26)
                                    </option>
                                    <option value="27">
                                        27th (27)
                                    </option>
                                    <option value="28">
                                        28th (28)
                                    </option>
                                    <option value="29">
                                        29th (29)
                                    </option>
                                    <option value="30">
                                        30th (30)
                                    </option>
                                    <option value="31">
                                        31st (31)
                                    </option>
                                </select>
                    </div>
                </div>
            
            
            
            
            
                <div class="row">
                     <div class="col-sm-4">
                        <label>Month</label>
                         <select class="form-control form-control-alternative" name="cron_month">
                            <option selected
                                    value="<?php echo set_value('cron_month', config_item('cron_month')) ?>"><?php echo set_value('cron_month', config_item('cron_month')) ?></option>
                                    <option value="*">
                                        Every Month
                                            (*)
                                    </option>
                                    <option value="*/2">
                                        Every Other Month
                                            (*/2)
                                    </option>
                                    <option value="*/4">
                                        Every Third Month
                                            (*/4)
                                    </option>
                                    <option value="1,7">
                                        Every Six Months
                                            (1,7)
                                    </option>
                                    
                                    <option value="1">
                                        January
                                            (1)
                                    </option>
                                    <option value="2">
                                        February
                                            (2)
                                    </option>
                                    <option value="3">
                                        March
                                            (3)
                                    </option>
                                    <option value="4">
                                        April
                                            (4)
                                    </option>
                                    <option value="5">
                                        May
                                            (5)
                                    </option>
                                    <option value="6">
                                        June
                                            (6)
                                    </option>
                                    <option value="7">
                                        July
                                            (7)
                                    </option>
                                    <option value="8">
                                        August
                                            (8)
                                    </option>
                                    <option value="9">
                                        September
                                            (9)
                                    </option>
                                    <option value="10">
                                        October
                                            (10)
                                    </option>
                                    <option value="11">
                                        November
                                            (11)
                                    </option>
                                    <option value="12">
                                        December
                                            (12)
                                    </option>
                        </select>
                    </div>
                    <div class="col-sm-4">
                        <label>Weekday</label>
                        <select class="form-control form-control-alternative" name="cron_weekday">
                         <option selected
                                    value="<?php echo set_value('cron_weekday', config_item('cron_weekday')) ?>"><?php echo set_value('cron_weekday', config_item('cron_weekday')) ?></option>
                                <option value="*">
                                        Every Day
                                            (*)
                                    </option>
                                    <option value="1-5">
                                        Every Weekday
                                            (1-5)
                                    </option>
                                    <option value="0,6">
                                        Every Weekend Day
                                            (6,0)
                                    </option>
                                    <option value="1,3,5">
                                        Every Monday, Wednesday, and Friday
                                            (1,3,5)
                                    </option>
                                    <option value="2,4">
                                        Every Tuesday and Thursday
                                            (2,4)
                                    </option>
                                    <option value="0">
                                        Sunday
                                            (0)
                                    </option>
                                    <option value="1">
                                        Monday
                                            (1)
                                    </option>
                                    <option value="2">
                                        Tuesday
                                            (2)
                                    </option>
                                    <option value="3">
                                        Wednesday
                                            (3)
                                    </option>
                                    <option value="4">
                                        Thursday
                                            (4)
                                    </option>
                                    <option value="5">
                                        Friday
                                            (5)
                                    </option>
                                    <option value="6">
                                        Saturday
                                            (6)
                                    </option>
                        </select>
                        
                    </div>
                    <div class="col-sm-4">
                        
                               
                    </div>
                </div>
            
            
            
            </div>
            
        </div>
  <!-- Cron setting ends -->



       </div>
  
 <div class="card-footer">

        <div class="row">   
                            <div class="col-6">
                                <label>Secret Password</label> (This is not admin password)
                                <input type="password" class="form-control form-control-alternative" name="dev_pass">
                            </div>
                            <div class="col-sm-4"><br/>
                                <input type="submit" class="btn btn-success" value="Update"
                                       onclick="this.value='Updating..'">
                            </div>
                            
            
            <?php echo form_close() ?>
        </div>

 </div>
</div>

</div>
