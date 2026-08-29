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
        <div class="col-sm-12">
            <label>Image *</label>
            <input type="file" class="form-control" name="popup_image" required accept="image/*">
        </div>
        <div class="col-sm-12"><br/>
            <input type="submit" class="btn btn-success" value="Add New Popup" onclick="this.value='Adding..'">
        </div>
    </div>

    <?php echo form_close() ?>
    </div>
   

    <div class="table-responsive">
    <table class="table table-striped table-bordered">
        <tr>
            <th>SN</th>
            <th>Image</th>
            <th>Actions</th>
        </tr>
        <?php
        $sn = 1;
        foreach ($popups as $e) { ?>
            <tr>
                <td><?php echo $sn++; ?></td>
                <td class="text-center"><img src="<?php echo $e['img']; ?>" style="height: 10em;width: 10em" /></td>
                <td>
                    <a onclick="return confirm('Are you sure you want to delete this popup ?')" href="<?php echo site_url('admin/popup_remove/' . $e['id']); ?>" class="btn btn-danger btn-sm">Delete</a>
                </td>
            </tr>
        <?php } ?>
    </table>

</div>
</div>
</div>


