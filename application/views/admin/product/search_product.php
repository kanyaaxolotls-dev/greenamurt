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


    <?php echo form_open('product/search') ?>
   <div class="row">
    <div class="col-sm-3">
        <label>Category</label>
        <select class="form-control" name="category">
            <option selected>All</option>
            <?php foreach ($parents as $val) {
                echo '<option value="' . $val['id'] . '">' . $val['cat_name'] . '</option>';
            } ?>
        </select>
    </div>
    <div class="col-sm-3">
        <label>Product Name</label>
        <input type="text" class="form-control" id="pname" name="pname">
    </div>
    <div class="col-sm-3">
        <label>Selling Status</label>
        <select class="form-control" name="status">
            <option selected>All</option>
            <option>Selling</option>
            <option>Not-Selling</option>
        </select>
    </div>
    <div class="col-sm-3">
        <label>Sign Up Product</label>
        <select class="form-control" name="is_sign_up">
            <option selected>All</option>
            <option>Yes</option>
            <option>No</option> 
        </select>
    </div>
    <div class="col-sm-12"><br/>
        <input type="submit" class="btn btn-success" value="Search" onclick="this.value='Searching..'">
    </div>
    <?php echo form_close() ?>
</div>
</div>
</div>
</div>