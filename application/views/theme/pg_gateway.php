
  <div class="header bg-gradient-primary pb-8 pt-7 pt-md-8">
    
        <div class="header-body">
           <div class="row justify-content-md-center">
            <div class="col col-lg-2">
                             
             </div>
                <div class="card" >  
                    <div class="card-header bg-white border-0"> 
                            <div class="row align-items-center">
                                <div class="col-8">
                                      <p class="mb-0">Proceed for Payment</p>
                                </div>
                            <div class="col-4 text-right"> 
                                <a href="<?php echo site_url('site/failed_registration')?>" class="btn btn-sm btn-primary">Cancel</a>
                            </div> 
                        </div>
                    </div>
           
                <div class="col-md-auto">
                 <div class="card-body">              
    
         <?php
            if (config_item('enable_paypal') == "Yes"):
                ?>
                       
                            <form action="https://www.paypal.com/cgi-bin/webscr" method="post">
                                <input type="hidden" name="cmd" value="_xclick">
                                <input type="hidden" name="business" value="<?php echo config_item('paypal_email') ?>">
                                <input type="hidden" name="item_name"
                                       value="<?php echo $this->db_model->select('prod_name', 'product', array('id' => $this->session->_product_)); ?>">
                                <input type="hidden" name="item_number" value="<?php echo $this->session->_product_ ?>">
                                <input type="hidden" name="invoice" value="<?php echo $this->session->_inv_id_ ?>">
                                <input type="hidden" name="amount" value="<?php echo $this->session->_price_ ?>">
                                <input type="hidden" name="first_name"
                                       value="<?php echo $this->session->_user_name_ ?>">
                                <input type="hidden" name="address1" value="<?php echo $this->session->_address_ ?>">
                                <input type="hidden" name="night_phone_a" value="<?php echo $this->session->_phone_ ?>">
                                <input type="hidden" name="notify_url"
                                       value="<?php echo site_url('gateway/paypal_ipn/' . $this->session->_type_) ?>">
                                <input type="hidden" name="cancel_return"
                                       value="<?php echo site_url('gateway/status/paypal') ?>">
                                <input type="hidden" name="return"
                                       value="<?php echo site_url('gateway/status/paypal') ?>">
                                <input type="hidden" name="quantity" value="1">
                                <input type="hidden" name="currency_code"
                                       value="<?php echo config_item('paypal_currency') ?>">
                                <input type="hidden" name="email" value="<?php echo $this->session->_email_ ?>">
                                <button type="submit" class="btn btn-success"> Via PayPal</button>
                            </form>
                     
                <?php
            endif; ?>
        </div>
 
        <div class="col-3">
             <?php
            if (config_item('enable_instamojo') == "Yes"):
                ?>
                            <a href="<?php echo site_url('gateway/instamojo_start') ?>">
                             
                        <img src="https://static.im-cdn.com/assets/images/logo.d8e416049537.jpg" width="200"> </a>
            <?php endif; ?>

        </div>

        <div class="col-3">
             <?php
            if (config_item('enable_paytm') == "Yes"):
                ?>
              
                            <a class="btn btn-success" href="<?php echo site_url('https://securegw-stage.paytm.in/theia/processTransaction') ?>" >Via Paytm</a>
                            
                      
            <?php endif; ?>

        </div>
        <div class="col-3">
             <?php
            if (config_item('enable_block_io') == "Yes"):
                ?>
                <div class="panel panel-primary">
                    <div class="panel-heading">
                        <h4 class="panel-title">
                            <a data-toggle="collapse" data-parent="#accordion" href="#collapse2">
                                Bitcoin / Dogecoin / Litecoin - Block.io &rarr;</a>
                        </h4>
                    </div>
                    <div id="collapse2" class="panel-collapse collapse">
                        <div class="panel-body">
                            <a href="<?php echo site_url('gateway/block_io_start') ?>" class="btn btn-danger">Pay Now
                                &rarr;</a>
                        </div>
                    </div>
                </div>
            <?php endif; ?>
        </div>
        <div class="col-3">
             <?php if (config_item('enable_razorpay') == "Yes"): ?>
               

             <?php endif; ?> 

        </div>

        <div class="col"> 
                    <?php
            if (config_item('enable_payumoney') == "Yes"):
                //$this->load->view('payu_pay');
             //  var_dump($this->session->_phone_);die();
                ?>
            <div class="panel panel-primary">
                    <div class="panel-heading">
                        <h4 class="panel-title">
                            <a>
                               Pay Via Payu</a>
                        </h4> 
                    </div> 
                   
                        <div class="panel-body">
                            <form action="https://sandboxsecure.payu.in/_payment" method="post">
                                <input type="hidden" name="key" value="<?php echo config_item('payumoney_key') ?>"/>
                                <input type="hidden" name="hash" value="<?php echo config_item('payumoney_salt') ?>"/>
                                <input type="hidden" name="txnid" value="<?php echo $this->session->_user_id_ ?>"/>
                                <input name="amount" type="hidden" value="<?php echo $this->session->_price_; ?>"/>
                                <input type="hidden" name="firstname" id="firstname"
                                       value="<?php echo $this->session->_user_name_; ?>"/>
                                <input type="hidden" name="email" id="email"
                                       value="<?php echo $this->session->_email_; ?>"/>
                                <input type="hidden" name="phone" value="<?php echo $this->session->_phone_; ?>"/>
                                <input name="productinfo" type="hidden"
                                       value="<?php echo $this->db_model->select('prod_name', 'product', array('id' => $this->session->_product_)); ?>">
                                <input type="hidden" name="surl"
                                       value="<?php echo site_url('gateway/status/payumoney') ?>"/>
                                <input type="hidden" name="furl"
                                       value="<?php echo site_url('gateway/status/payumoney') ?>"/>
                                <input type="hidden" name="service_provider" value="payu_paisa"/>
                                <input type="hidden" name="lastname" id="lastname" value=""/>
                                <button class="btn btn-primary" type="submit">Pay with PayuMoney &rarr;</button>

                            </form>
                        </div>
                    
                </div>
            <?php endif; ?>
        </div>

</div>
<div class="col col-lg-2">
</div>
</div>
</div>
</div>
</div>


             