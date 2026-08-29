<div class="col">
        <div class="card bg-secondary shadow">  
            <div class="card-header bg-white border-0">  
                <div class="row align-items-center">
                    <div class="col-8">  
                          <h3 class="mb-0">Add News</h3>
                    </div>
                    <div class="col-4 text-right">  
                     
                       <a href="<?php echo site_url('admin')?>" class="btn btn-sm btn-primary">Home</a>
                    </div> 
                </div>  
            </div>  
        <div class="card-body">

    <?php echo form_open_multipart('news/add_news', array("class" => "form-horizontal")); ?>

        <div class="form-group">
            <label for="news_title" class="form-control-label">Title<span class="text-danger">*</span></label>
            <div class="col-md-8">
                <input type="text" name="news_title" value="<?php echo set_value('news_title'); ?>" class="form-control form-control-alternative form-control-alternative-alternative"
                       id="news_title"/>
                <span class="text-danger"><?php echo form_error('news_title'); ?></span>
            </div>
        </div>

        <div class="form-group">
            <label for="news_details" class="form-control-label">Description<span class="text-danger">*</span></label>
            <div class="col-md-8">
                <textarea name="news_details" class="form-control form-control-alternative form-control-alternative-alternative" id="news_details"><?php echo set_value('news_details'); ?></textarea>
                <span class="text-danger"><?php echo form_error('news_details'); ?></span>
            </div>
        </div>
    
            <!--<label for="news_pic" class="form-control-label">Photo</label>-->
            <!--<div class="col-md-8">-->
            <!--    <div class="input-group">-->
            <!--          <div class="custom-file">-->
            <!--            <input type="file" class="custom-file-input" name="news_pic" id="news_pic"-->
            <!--              aria-describedby="inputGroupFileAddon01">-->
            <!--            <label class="custom-file-label" for="news_pic" name="news_pic">Choose Photo</label>-->
            <!--          </div>-->
            <!--        </div>-->
            <!--</div>-->
 
        <div class="form-group">
            <label for="news_type" class="form-control-label">News For</label>
            <div class="col-md-8">
                <input name="news_type" id="news_type2" type="radio" checked="true" value="Member"/> Member only
                <input name="news_type" id="news_type1" type="radio" value="Public"/> Public
                
            </div>
        </div>

        <div class="form-group">
            <div class="col-sm-offset-1 col-sm-8">
                <button type="submit" class="btn btn-success">Publish News</button>
            </div>
        </div>
 
<?php echo form_close(); ?>
</div>
</div>
</div>
