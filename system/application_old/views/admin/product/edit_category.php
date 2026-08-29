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

             <?php echo form_open() ?> 
                <div class="row"> 
                <div class="col-sm-6">
                    <div class="form-group">
                            <label>Category Name*</label>
                            <input type="text" class="form-control form-control-alternative" value="<?php echo set_value('cat_name', $data->cat_name) ?>"
                                   name="cat_name">
                        </div>
                     </div>
                 <div class="col-sm-6">
                  <div class="form-group">
                    <input type="hidden" name="id" value="<?php echo $data->id ?>">
                   
                        <label>Parent Category</label>
                        <select class="form-control form-control-alternative" name="parent_cat">
                            <option value="<?php echo $data->parent_cat ?>"
                                    selected><?php echo $this->db_model->select('cat_name', 'product_categories', array('id' => $data->parent_cat)) ?></option>
                            <?php foreach ($parents as $val) {
                                echo '<option value="' . $val['id'] . '">' . $val['cat_name'] . '</option>';
                            } ?>
                        </select>
                    </div>
                   </div>
                </div>   
                <div class="row">
                    <div class="col-sm-6">
                         <div class="form-group">
                            <label>Category Description</label>
                            <textarea class="form-control form-control-alternative" id="editor"
                                      name="description"><?php echo set_value('description', $data->description) ?></textarea>
                        </div>
                    </div>
                    <div class="col-sm-6"> 
                        <div class="form-group"><br><br>
                            <input type="submit" class="btn btn-success" value="Update" onclick="this.value='Updating..'"></div>
                    </div>
                </div>
        
             <?php echo form_close() ?>
    </div>
  </div>
   </div>


