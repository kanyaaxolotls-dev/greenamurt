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
<div class="row">
    <div align="center">
        <div class="fa fa-excel-o" style="font-size: 100px"></div>
        <br/>
    </div>
    <div class="col-sm-4">
        <?php echo form_open() ?>
        <label>Select Your Database</label><br/>
        <select class="form-control form-control-alternative form-control-alternative-alternative" name="table">
            <?php
            $tables = $this->db->list_tables();

            foreach ($tables as $table) {
                echo '<option>' . $table . '</option>';
            } ?>
        </select><br/>
        <button type="submit" class="btn btn-primary">
            Export to Excel &rarr;
        </button>
        <?php echo form_close() ?>
    </div>
</div>

</div>
</div>
</div>
