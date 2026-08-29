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
    <?php echo form_open() ?>
    <div class="col-sm-12">
        <label>Category Name</label>
        <input type="text" class="form-control" value="<?php echo set_value('cat_name', $result->cat_name) ?>"
               name="cat_name">
    </div>
    <input type="hidden" name="id" value="<?php echo $result->id ?>">
    <div class="col-sm-12">
        <label>Category Description</label>
        <textarea id="editor" class="form-control"
                  name="cat_desc"><?php echo set_value('cat_desc', $result->cat_description) ?></textarea>
    </div>
    <div class="col-sm-6 mb-3"><br/>
        <input type="submit" class="btn btn-success" value="Update" onclick="this.value='Updating..'">
    </div>
    <?php echo form_close() ?>
</div>
</div>


