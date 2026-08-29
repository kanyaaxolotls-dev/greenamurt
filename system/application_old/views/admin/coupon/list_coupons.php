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
    <?php echo form_open('coupon/search') ?>
    <div class="row p-3"> 
    <div class="col-sm-6">
        <label>Select Category</label>
        <select class="form-control" name="coupon_cat">
            <option selected value="All">All</option>
            <?php
            foreach ($cats as $e) {
                echo '<option value="' . $e->id . '">' . $e->cat_name . '</option>';
            }
            ?>
        </select>
    </div>
    <div class="col-sm-6">
        <label>Status</label>
        <select class="form-control" name="status">
            <option selected value="All">All</option>
            <option value="Used">Used</option>
            <option value="Un-Used">Un-Used</option>
            <option value="Use Request">Requested for Use</option>
        </select>
    </div>
    <div class="col-sm-12">
        <label>User ID</label>
        <input class="form-control" name="userid" type="text">
    </div>
    <div class="col-sm-6 mt-3">
        <input type="submit" class="btn btn-success" value="Search" onclick="this.value='Searching..'">
    </div>
    <?php echo form_close() ?>

<div class="table-responsive p-3">
    <table class="table table-striped table-bordered">
        <tr>
            <th>SN</th>
            <th>Coupon</th>
            <th>Userid</th>
            <th>Coupon Amt</th>
            <th>Status</th>
            <th>Actions</th>
        </tr>
        <?php
        $sn = 1;
        foreach ($result as $e) { ?>
            <tr>
                <td><?php echo $sn++; ?></td>
                <td><?php echo $e->coupon; ?></td>
                <td><?php echo $e->userid; ?></td>
                <td><?php echo config_item('currency') . $e->coupon_amt; ?></td>
                <td><?php echo $e->status; ?></td>
                <td>
                    <?php if ($e->status == "Use Request") { ?>
                        <a onclick="return confirm('Are you sure you want to mark this Coupon as used ?')"
                           href="<?php echo site_url('coupon/mark_use/' . $e->id); ?>" class="btn btn-info btn-xs">Mark
                            as
                            Used</a>
                    <?php } ?>
                    <a onclick="return confirm('Are you sure you want to delete this Coupon ?')"
                       href="<?php echo site_url('coupon/remove_coupon/' . $e->id); ?>" class="btn btn-danger btn-xs">Delete</a>
                </td>
            </tr>
        <?php } ?>
    </table>
</div>
<div class="pull-right">
    <?php echo $this->pagination->create_links(); ?>
</div>
</div>



