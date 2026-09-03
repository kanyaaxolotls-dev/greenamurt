 <div class="col">
    <div class="card bg-secondary shadow">  
        <div class="card-header bg-white border-0"> 
        <div class="row align-items-center">
            <div class="col-8">
                  <h3 class="mb-0"><?php echo $data['title'] ?></h3>
            </div>
        <div class="col-4 text-right"> 
            <a href="<?php echo site_url('admin')?>" class="btn btn-sm btn-primary">Home</a>
        </div>
    </div>
</div>
<div class="card-body">
       
            <?php echo form_open('users/topup_member') ?>
            <div class="col-sm-6">
                <label>User ID</label>
                <input type="text" value="<?php echo $this->uri->segment(3) ? $this->uri->segment(3) : '' ?>"
                       class="form-control form-control-alternative form-control-alternative-alternative" id="userid" name="userid">
            </div>
            <div class="col-sm-6">
                <label>Amount to Topup</label>
                <input type="text" class="form-control form-control-alternative form-control-alternative-alternative" id="amt" name="amt">
            </div>
            <div class="col-sm-2"><br/>
                <input type="submit" class="btn btn-success" value="TopUp" onclick="this.value='Working..'">
            </div>
            <?php echo form_close() ?>
        </div>

</div></div>