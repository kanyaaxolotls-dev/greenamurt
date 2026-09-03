<div class="col">
    <div class="card bg-secondary shadow">  
        <div class="card-header bg-white border-0"> 
        <div class="row align-items-center">
            <div class="col-8">
                  <h3 class="mb-0"><?php echo $title ?></h3>
            </div>
        <div class="col-4 text-right"> 
            <a href="<?php echo site_url('admin/index')?>" class="btn btn-sm btn-primary">Home</a>
        </div>
    </div>
</div>
<div class="card-body">
    <?php echo form_open_multipart() ?>
        <!--<div class="row">-->
        <!--        <div class="col-sm-3">-->
        <!--            <label>SMTP Host</label>-->
        <!--            <div class="form-group">-->
        <!--            <input type="text" class="form-control" value="<?php echo set_value('smtp_host', config_item('smtp_host')) ?>"-->
        <!--                   name="smtp_host">-->
        <!--               </div>-->
        <!--        </div>-->
        <!--        <div class="col-sm-3">-->
        <!--            <label>SMTP User</label> <div class="form-group">-->
        <!--            <input type="text" class="form-control" value="<?php echo set_value('smtp_user', config_item('smtp_user')) ?>"-->
        <!--                   name="smtp_user"></div>-->
        <!--        </div>-->
        <!--        <div class="col-sm-3">-->
        <!--            <label>SMTP Pass</label> <div class="form-group">-->
        <!--            <input type="text" class="form-control" value="<?php echo set_value('smtp_pass', config_item('smtp_pass')) ?>"-->
        <!--                   name="smtp_pass"></div>-->
        <!--        </div>-->
        <!--         <div class="col-sm-3">-->
        <!--            <label>SMTP Port (SSL Only)</label> <div class="form-group">-->
        <!--            <input type="text" class="form-control" value="<?php echo set_value('smtp_port', config_item('smtp_port')) ?>"-->
        <!--                   name="smtp_port"></div>-->
        <!--        </div>-->
        <!--   </div>-->
        <div class="row">
            <!-- Logo upload with preview (Images only) -->
            <div class="col-sm-3">
                <input type="hidden"  value="<?php echo config_item('dev_pass') ?>" name="pass">
                <label>Logo</label>
                <div class="input-group">
                    <div class="custom-file">
                        <input type="file" class="custom-file-input" name="logo" id="logo" accept="image/*">
                        <label class="custom-file-label" for="logo">Choose logo</label>
                    </div>
                </div>
                <?php if (file_exists(FCPATH . 'uploads/logo.png')): ?>
                    <img src="<?php echo base_url('uploads/logo.png') ?>" alt="Logo Preview" style="width: 100px; height:100px; padding-top: 10px;">
                <?php endif; ?>
            </div>
        
            <!-- Favicon upload with preview (Images only) -->
            <div class="col-sm-3">
                <label>Favicon</label>
                <div class="input-group">
                    <div class="custom-file">
                        <input type="file" class="custom-file-input" name="favicon" id="favicon" accept="image/*">
                        <label class="custom-file-label" for="favicon">Choose Favicon</label>
                    </div>
                </div>
                <?php if (file_exists(FCPATH . 'uploads/favicon.ico')): ?>
                    <img src="<?php echo base_url('uploads/favicon.ico') ?>" alt="Favicon Preview"style="width: 100px; height:100px; padding-top: 10px;">
                <?php endif; ?>
            </div>

            <!-- QR code upload with preview (Images only) -->
            <div class="col-sm-3">
                <label>QR Code</label>
                <div class="input-group">
                    <div class="custom-file">
                        <input type="file" class="custom-file-input" name="qr_code" id="qr_code" accept="image/*">
                        <label class="custom-file-label" for="qr_code">Choose QR code</label>
                    </div>
                </div>
                <?php if (file_exists(FCPATH . 'uploads/qr_code.png')): ?>
                    <img src="<?php echo base_url('uploads/qr_code.png') ?>" alt="QR Code Preview" style="width: 100px; height:100px; padding-top: 10px;">
                <?php endif; ?>
            </div>
            
            <!-- Business plan upload with preview (PDF only) -->
            <div class="col-sm-3">
                <label>Business Plan</label>
                <div class="input-group">
                    <div class="custom-file">
                        <input type="file" class="custom-file-input" name="business_plan" id="business_plan" accept="application/pdf">
                        <label class="custom-file-label" for="business_plan">Choose Business Plan</label>
                    </div>
                </div>
                <?php if (file_exists(FCPATH . 'uploads/business_plan.pdf')): ?>
                    <a href="<?php echo base_url('uploads/business_plan.pdf') ?>" target="_blank">View Business Plan</a>
                <?php endif; ?>
            </div>
    
            <div class="col-sm-4" style='padding-top:10px'>
                <div class="form-group">
                    <input type="submit" class="btn btn-primary" value="Update your changes" onclick="this.value='Updating..'">
                </div>
            </div>
        </div>
    <?php echo form_close() ?>
        </div> 
    </div>
</div>
