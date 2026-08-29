<div class="bg-secondary shadow">   
        <div class="card-header bg-white border-0"> 
                <div class="row align-items-center">
                    <div class="col-9">
                          <h4 class="mb-0"><?php echo $title ?></h4>
                    </div> 
                <div class="col-3 text-right"> 
                   <a href="<?php echo site_url('member/index')?>" class="btn btn-sm btn-primary">Home</a>
                </div>
            </div> 
        </div>

<div class="card-body">  
    <?php echo form_open('member/task_search') ?>
    <div class="row">
    <div class="col-sm-4">
        <label>Income Type</label>
        <select class="form-control form-control-alternative" name="task_name">
            <option selected>All</option>
            <?php foreach (config_item('task_name') as $key => $val) {
                echo '<option value="' . $key . '">' . $val . '</option>';
            } ?>
        </select>
    </div>
    <div class="col-sm-3">
        <label>Start Date</label>
        <input type="text" class="form-control form-control-alternative datepicker" placeholder="dd/mm/yyyy" id="startdate" name="startdate">
    </div>
    <div class="col-sm-3">
        <label>End Date</label>
        <input type="text" class="form-control form-control-alternative datepicker" placeholder="dd/mm/yyyy" id="enddate" name="enddate">
    </div>
    <div class="col-sm-2"><br/>
        <input type="submit" class="btn btn-primary btn-block" value="Get My Earning" onclick="this.value='Searching..'">
    </div>
</div>
    <?php echo form_close() ?>
</div>
</div>
