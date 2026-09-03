<div class="col">
        <div class="card bg-secondary shadow">  
            <div class="card-header bg-white border-0">  
                <div class="row align-items-center">
                    <div class="col-8">  
                          <h3 class="mb-0"><?php echo $title ?></h3>
                    </div>
                    <div class="col-4 text-right">  
                     
                       <a href="<?php echo site_url('admin')?>" class="btn btn-sm btn-primary">Home</a>
                    </div> 
                </div>  
            </div> 
            <h2 class="text-danger text-center my-2 mt-3">Enter incomes values separated by comma ex: 10,5,6,8</h2>
        <?php echo form_open_multipart('admin/set_incomes'); ?>
        <div class="card-body row">
            <?php foreach($autopool_incomes as $tr){ ?>
            <div class="form-group col-md-6 col-12">
                <label for="news_title" class="form-control-label"><?= $tr['name'] ?>  <span class="text-danger"> * </span></label>
                <div class="">
                    <input type="text" required name="auto_<?= $tr['id'] ?>" value="<?= $tr['income'] ?>" placeholder="Enter incomes values separated by comma ex: 10,5,6,8" class="form-control form-control-alternative form-control-alternative-alternative" pattern="^(\d+(\.\d+)?)(,\d+(\.\d+)?)*$" title="Enter a list of numbers separated by commas, e.g., 10,5.5,6,8. No spaces or alphabetic characters." />
                </div>
            </div>
            <?php } ?>
            <div class="col-12">
                <button type="submit" class="btn btn-success">Update incomes</button>
            </div>
        </div>
<?php echo form_close(); ?>
</div>
</div>
