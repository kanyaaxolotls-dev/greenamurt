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
    <div class="state-overview">
        <a class="btn btn-danger" href="<?php echo site_url('setting/export_db') ?>">Database Export   
        </a>
        <a class="btn btn-success" href="<?php echo site_url('setting/import_db') ?>">
         Database Import
        </a>
         <a  class="btn btn-primary" href="<?php echo site_url('setting/excel') ?>">
            Export to Excel
        </a>
       

    </div>
</div>
</div>
</div>
</div>