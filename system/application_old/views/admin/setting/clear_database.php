<div class="col">
    <div class="card bg-secondary shadow">  
        <div class="card-header bg-white border-0"> 
        <div class="row align-items-center">
            <div class="col-8"> 
                  <h3 class="mb-0"><?php echo $title?></h3>
            </div>
        <div class="col-4 text-right"> 
            <a href="<?php echo site_url('admin/index')?>" class="btn btn-sm btn-primary">Home</a>
        </div> 
    </div>
</div> 
<div class="card-body">
       <?php echo form_open() ?>
        <div class="row">
         
            <div class="col-sm-4">
              <div class="form-group">
                <label>Administrative Password</label>
                <input type="password" required class="form-control form-control-alternative" value="<?php echo set_value('password') ?>"
                       name="password">
                     </div>
            </div>
             <div class="col-sm-8">
                <div class="form-group"><br>
                <input type="checkbox" required  name="agree"> I know that If I click the below button, All data will be deleted & not recoverable.
              </div>
            </div>
          </div>
          <div class="row">
          
            <div class="col-sm-12"><br/>
                <input type="submit" class="btn btn-success" value="Reset Database">
            </div>
       
        </div>
     <?php echo form_close() ?>
</div>
<div class="card-footer">
     <small class="text-muted">Notice: Before you click on "Reset My Database", Please keep a backup of your existing database which will ensure further safety if anything goes wrong. <a href="<?php echo site_url('setting/export-final') ?>">Click Here for Backup &rarr;</a>
     </small>
</div>
</div>
</div>
