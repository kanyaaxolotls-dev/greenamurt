
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
