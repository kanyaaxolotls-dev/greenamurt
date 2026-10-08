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
     <?php echo form_open() ?>
<div class="row">
   
    <div class="col-sm-6">
        <label>Name</label>
        <input type="text" class="form-control" value="<?php echo set_value('name', $result->name) ?>" name="name">
    </div>
    <div class="col-sm-6">
        <label>Email ID</label>
        <input type="text" class="form-control" value="<?php echo set_value('email', $result->email) ?>" name="email">
    </div>
    <div class="col-sm-6">
        <label>Current Password</label>
        <input type="password" required class="form-control" value="<?php echo set_value('password') ?>"
               name="password">
    </div>
    <div class="col-sm-6">
        <label>New Password</label>
        <input type="password" class="form-control" value="<?php echo set_value('newpass') ?>" name="newpass">
    </div>
    <div class="col-sm-6"><br/>
        <input type="submit" class="btn btn-success" value="Update" onclick="this.value='Updating..'">
    </div>
    <?php echo form_close() ?>
</div>
</div>
</div>

