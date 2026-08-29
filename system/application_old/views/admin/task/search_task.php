<div class="col">
    <div class="card bg-secondary shadow">  
        <div class="card-header bg-white border-0"> 
        <div class="row align-items-center">
            <div class="col-8"> 
                  <h3 class="mb-0">Search Task</h3>
            </div>
        <div class="col-4 text-right"> 
            <a href="<?php echo site_url('admin')?>" class="btn btn-sm btn-primary">Home</a>
        </div> 
    </div>
</div> 
<div class="card-body">

<?php echo form_open('users/search_task') ?>
<div class="row">

    <div class="col-sm-3">
        <label>Income Type</label>
        <select class="form-control" name="task_name">
            <option selected>All</option>
            <?php foreach (config_item('task_name') as $key => $val) {
                echo '<option value="' . $key . '">' . $val . '</option>';
            } ?>
        </select>
    </div>
    <div class="col-sm-3">
        <label>User ID</label>
        <input type="text" class="form-control" id="userid" name="userid">
    </div>
    <div class="col-sm-3">
        <label>Start Date</label>
        <input type="text" class="form-control datepicker" readonly id="startdate" name="startdate">
    </div>
    <div class="col-sm-3">
        <label>End Date</label>
        <input type="text" class="form-control datepicker" readonly id="enddate" name="enddate">
    </div>
    <div class="col-sm-12"><br/>
        <input type="submit" class="btn btn-sm btn-primary" value="Search" onclick="this.value='Searching..'">
    </div>

    <?php echo form_close() ?>
</div>

</div>
</div>
</div>
