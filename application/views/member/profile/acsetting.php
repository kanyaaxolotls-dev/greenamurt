
    <div class="card bg-light shadow">   
        <div class="card-header bg-white border-0"> 
                <div class="row align-items-center">
                    <div class="col-10">
                          <h3 class="mb-0"><?php echo $title ?></h3>
                    </div>
                <div class="col-2 text-right"> 
                   <a href="<?php echo site_url('member/index')?>" class="btn btn-sm btn-primary">Home</a>
                </div>
            </div>
        </div>
    <div class="card-body"> 

<?php echo form_open() ?>
<div class="row">
		<div class="col-md-6">

			    <label>Your Current Password:</label>
			    <input type="password" name="oldpass" placeholder="Enter your current password" required class="form-control form-control-alternative">
		</div>
		<div class="col-md-6">

			    <label>Your New Password:</label>
			    <input type="password" name="newpass" placeholder="Create your new password" required class="form-control form-control-alternative">
		</div>
		<div class="col-md-12"><br/>
			    <label>Retype New Password:</label>
			    <input type="password" name="repass" placeholder="Confirm your password" required class="form-control form-control-alternative">
			  
		</div>
		<div class="col-12"><br/>
			  <button class="btn btn-success input-lg" name="submit" value="add">Change Your Password</button>
		</div>
</div>	
<?php echo form_close() ?>

</div>
</div>
</div></div></div>