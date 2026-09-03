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
        <div class="fa fa-database" style="font-size: 100px"></div>
        <br/><br>
        <p>
            <a href="<?php echo site_url('setting/export_final') ?>" class="btn btn-danger"> Export to SQL &rarr;</a>
        </p>
    </div>
</div>
</div>
</div>
</div>