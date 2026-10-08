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
        <div class="form-group">
            <div class="row">
                    <div class="col-sm-6">
                        <label>Task Name</label>
                        <input type="text" class="form-control" name="task_name"
                               value="<?php echo set_value('task_name', $data->task_name) ?>">
                    </div>

                    <div class="col-sm-6">
                        <label>description</label>
                        <input type="text" class="form-control" name="description"
                               value="<?php echo set_value('description', $data->description) ?>">
                    </div>

                    <div class="col-sm-6">
                        <label>category</label>
                        <input type="text" class="form-control" name="category"
                               value="<?php echo set_value('category', $data->category) ?>">
                    </div>
                    <div class="col-sm-6">
                        <label>link</label>
                        <input type="text" class="form-control" name="link"
                               value="<?php echo set_value('link', $data->link) ?>">
                    </div>
           

                    <div class="col-sm-6">
                        <label>amount</label>
                        <input type="text" class="form-control" name="amount"
                               value="<?php echo set_value('amount', $data->amount) ?>">
                    </div>

                    <div class="col-sm-6">
                        <label>expiry date</label>
                        <input type="text" class="form-control" name="expiry_date"
                               value="<?php echo set_value('expiry_date', $data->expiry_date) ?>">
                    </div>

                    <div class="col-sm-6">
                        <label>activation date</label>
                        <input type="text" class="form-control" name="activation_date"
                               value="<?php echo set_value('activation_date', $data->activation_date) ?>">
                    </div>

                    <div class="col-sm-6">
                        <label>created date</label>
                        <input type="text" class="form-control" name="created_date"
                               value="<?php echo set_value('created_date', $data->created_date) ?>">
                    </div>
                    <div class="col-sm-6">
                        <br>
                        <h3>Task image</h3>                        
                        <label>(Please leave blank if you do not want to change image)</label>
                        <input type="file" name="img">
                        <img src="<?php echo $data->image ? base_url('uploads/'.$data->image) : base_url('uploads/default.jpg'); ?>"class="img-thumbnail img-responsive img-rounded" style="width:32%;height:65%">

                    </div>
                    

                    

                   
                    <div class="col-sm-6">
                        <label>Status </label>
                        <input type="text" class="form-control" name="status"
                               value="<?php echo set_value('status', $data->status) ?>">
                    </div>
                  
                    </div>
            </div>
        </div>

<div class="card-footer">
    <div class="col-sm-12"><br/>
        <input type="submit" class="btn btn-primary" value="Update" onclick="this.value='Updating..'">
    </div>
</div>

<?php echo form_close() ?>

</div>