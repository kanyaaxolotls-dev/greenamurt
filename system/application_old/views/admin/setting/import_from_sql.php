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
  <?php echo form_open_multipart() ?>
  <div class="row">
    <div class="col-sm-4">
      
        <label>Select Your Sql Database (.txt or .sql)</label>
            <div class="input-group">
                      <div class="custom-file">
                        <input type="file" class="custom-file-input" name="file" id="file"
                          aria-describedby="inputGroupFileAddon01">
                        <label class="custom-file-label" for="file" name="file">Choose Photo</label>
                      </div>
            </div>

    </div>
     <div class="col-sm-4"><br>
        <button type="submit" class="btn btn-danger"
                onclick="return confirm('Alert !!! This may break your database. Use at your own responsibility.')">
            Import &rarr;
        </button>
    </div>
</div>
    <?php echo form_close() ?>
  

</div>
</div>
</div>
