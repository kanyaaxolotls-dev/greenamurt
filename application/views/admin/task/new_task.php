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

<div class="card-body">

    <?php echo form_open_multipart() ?>
    <div class="form-group">
       <div class="card" style="background-color: #f7fafc;">
          <div class="card-header"> Task Information</div>
            <div class="card-body">
                <div class="row">
                <div class="col-sm-4">
                    <label class="prolabel">Task Name</label>
                    <input type="text" class="form-control form-control-alternative" name="task_name" value="<?php echo set_value('task_name') ?>">
                </div>
                <div class="col-sm-4">
                    <label class="prolabel">Task Category</label>
                    <select class="form-control form-control-alternative" name="category">
                       <option>--select--</option>
                       <option value="image">Image</option>
                       <option value="video">Video</option>
                       <option value="link">Link</option>
                    </select>
                </div>
                  <div class="col-sm-4">
                        <label class="prolabel">Task Description</label>
                        <textarea class="form-control form-control-alternative" id="editor" name="task_desc"><?php echo set_value('task_desc') ?></textarea>
                    </div>
                </div> 
            </div>
        </div>
        <br>
          <div class="card" style="background-color: #f7fafc;">
            <div class="card-header">task description</div>
                <div class="card-body">
                    <div class="row">
                            <div class="col-sm-4">
                                <label class="prolabel">Task Earning</label>
                                <input type="Number" class="form-control form-control-alternative" name="task_price" value="<?php echo set_value('task_price') ?>">
                            </div>
                            <div class="col-sm-4">
                                <label class="prolabel">Activation Date</label>
                                <input type="date" class="form-control form-control-alternative" name="active_date" value="<?php echo set_value('active_date') ?>">
                            </div>
                            <div class="col-sm-4"> 
                                <label class="prolabel">Expiry Date</label>
                                <input type="date" class="form-control form-control-alternative" name="expire_date" value="<?php echo set_value('expire_date') ?>">
                            </div>
                    </div>
                </div>
            </div>
        <br>
        <div class="card" style="background-color: #f7fafc;">
            <div class="card-header">task description</div>
                <div class="card-body">
                    <div class="row">
                            <div class="col-sm-4">
                                <label class="prolabel">Link</label>
                                 <textarea class="form-control form-control-alternative" id="editor" name="task_link"><?php echo set_value('task_link') ?></textarea>
                            </div>
                            <div class="col-sm-4">
                                <label class="prolabel">Image</label>
                                <input type="file" name="img">
                            </div>
                            <div class="col-sm-4"> 
                                <label class="prolabel">Status</label>
                                <select class="form-control form-control-alternative" name="status">
                                <option>--select--</option>
                                <option value="Active">Active</option>
                                <option value="Inactive">Inactive</option>
                                <option value="Cancel">Cancel</option>
                             </select>
                            </div>
                    </div>
                </div>
            </div>
            <br>
        <div class="card" style="background-color: #f7fafc;">
            <div class="card-header">task commission</div>
                <div class="card-body">
                <div class="row">
                    <div class="col-sm-4">
                        <label>Direct Income (In <?php echo config_item('currency') ?>)</label>
                        <input type="text" class="form-control" name="direct_income"
                               value="">
                    </div>
                    <div class="col-sm-4">
                        <label>Level Income (In <?php echo config_item('currency') ?>)</label>
                        <input type="text" class="form-control" placeholder="Comma separate each level income. eg: 12,3,4"
                               name="level_income" value="">
                    </div>
                    <div class="col-sm-4">
                    <label>Sponsor Amount (In <?php echo config_item('currency') ?>)</label>
                        <input type="text" class="form-control form-control-alternative" name="sponser_level_inc" value="">
                    </div>
            </div>
            </div>
            </div>
    </div>



         <input type="submit" class="btn btn-success pull-right" value="Create Product" onclick="this.value='Creating..'">
    <?php echo form_close() ?>
    </div>






</div>
 
</div>
