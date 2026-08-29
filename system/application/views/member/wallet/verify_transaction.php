
<?php echo form_open() ?>
<div class="col"> 
    <div class="card bg-secondary shadow">  
        <div class="card-header bg-white border-0"> 
                <div class="row align-items-center">
                    <div class="col-9">
                          <h4 class="mb-0"><?php echo $title ?></h4>
                    </div>
                <div class="col-3 text-right"> 
                    <a href="<?php echo site_url('member/index')?>" class="btn btn-sm btn-primary">Home</a>
                </div>
            </div>
        </div>
    <div class="card-body"> 

    
    <div class="row">

       <?php 
                 if (config_item('sms_on_join') == "Yes"):

                $data = $this->db_model->select_multi("id,name, phone, password", 'member', array('id' => $userid));
                $this->session->set_userdata('_user_id_', $data->id,300);
                
                $phone=$data->phone;

                $otp = rand(1000, 99999);
                $this->session->set_userdata('otp', $otp,300);   

                $sms = rawurlencode("Dear " . $data->name . " , OTP for password reset is : " . $otp . ". OTP will expire in 15 min. \nwww." . $_SERVER['HTTP_HOST']."");

              
                $this->common_model->sms($phone, $sms);
                endif;

                
                 $this->session->set_flashdata('site_flash', '<div class="alert alert-success">OTP has been sent your mobile number </div>');
                 redirect(site_url('wallet/withdraw-payouts'));
                 
            ?>
        </div>

    </div>
    </div>
  </div>
  </div>
</div>
<?php echo form_close() ?>



            