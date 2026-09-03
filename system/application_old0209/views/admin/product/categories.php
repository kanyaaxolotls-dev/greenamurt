<?php echo form_open_multipart(); ?>
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

    <div class="form-group row">
        <div class="col-sm-6">
            <label>Category Name*</label>
            <input type="text" class="form-control" name="cat_name">
        </div>
        <div class="col-sm-6">
            <label>Parent Category</label>
            <select class="form-control" name="parent_cat">
                <option value="" selected>No Parent</option>
                <?php foreach ($parents as $val) {
                    echo '<option value="' . $val['id'] . '">' . $val['cat_name'] . '</option>';
                } ?>
            </select>
        </div>
        <div>&nbsp;</div>
        <div class="col-sm-12">
            <label>Description</label>
            <textarea class="form-control" id="editor"
                      name="description"><?php echo set_value('description') ?></textarea>
        </div>
                <div class="col-sm-12">
            <label>Image *</label>
            <input type="file" class="form-control" name="img" required accept="image/*">
        </div>
        <div class="col-sm-12"><br/>
            <input type="submit" class="btn btn-success" value="Create New Category" onclick="this.value='Creating..'">
        </div>

    </div>

    <?php echo form_close() ?>
    </div>
   

    <div class="table-responsive">
    <table class="table table-striped table-bordered">
        <tr>
            <th>SN</th>
            <th>Category Title</th>
            <th>Parent</th>
            <th>Description</th>
            <th>Actions</th>
        </tr>
        <?php
        $sn = 1;
        foreach ($cat as $e) { ?>
            <tr>
                <td><?php echo $sn++; ?></td>
                <td><?php echo $e['cat_name']; ?></td>
                <td><?php echo $this->db_model->select('cat_name', 'product_categories', array('id' => $e['parent_cat'])); ?></td>
                <td><?php echo strip_tags($e['description']); ?></td>
                <td>
                    <a href="<?php echo site_url('admin/category/edit/' . $e['id']); ?>" class="btn btn-info btn-sm">Edit</a>
                    <a onclick="return confirm('Are you sure you want to delete this Category ?')"
                       href="<?php echo site_url('admin/category/remove/' . $e['id']); ?>"
                       class="btn btn-danger btn-sm">Delete</a>
                </td>
            </tr>
        <?php } ?>
    </table>
    <div class="pull-right">
        <?php echo $this->pagination->create_links(); ?>
    </div>
</div>
</div>
</div>
