<div class="col">
        <div class="card bg-secondary shadow">  
            <div class="card-header bg-white border-0">  
                <div class="row align-items-center">
                    <div class="col-8">  
                          <h3 class="mb-0"><?php echo $title ?></h3>
                    </div>
                    <div class="col-4 text-right">  
                     
                       <a href="<?php echo site_url('admin')?>" class="btn btn-sm btn-primary">Home</a>
                    </div> 
                </div>  
            </div>  
        <?php echo form_open_multipart('admin/rm_generate'); ?>
        <div class="card-body row">
            <div class="form-group col-md-6 col-12">
                <label for="news_title" class="form-control-label">Userid <span class="text-danger"> * </span></label>
                <div class="">
                    <input type="text" name="userid" placeholder="Enter Userid" class="form-control form-control-alternative form-control-alternative-alternative" />
                </div>
            </div>
            <div class="form-group col-md-6 col-12">
                <label for="news_details" class="form-control-label">RM Code <span class="text-danger"> * </span></label>
                <div class="">
                    <input type="text" name="rm_code" value="<?php echo $rm_code ?>" class="form-control form-control-alternative form-control-alternative-alternative" />
                </div>
            </div>
            <div class="form-group">
                <div class="col-sm-offset-1 col-sm-8">
                    <button type="submit" class="btn btn-success">Assign Code</button>
                </div>
            </div>
        </div>
<?php echo form_close(); ?>
</div>
</div>
