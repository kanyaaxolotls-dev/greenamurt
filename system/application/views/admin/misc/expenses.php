<div class="col">
    <div class="card bg-secondary shadow">  
        <div class="card-header bg-white border-0"> 
        <div class="row align-items-center">
            <div class="col-8"> 
                  <h3 class="mb-0">Manage Expenses</h3>
            </div>
        <!--<div class="col-4 text-right"> -->
        <!--    <a href="<?php echo site_url('admin/index')?>" class="btn btn-sm btn-primary">Home</a>-->
        <!--</div>  -->
        <div class="col-4 text-right"> 
            <a href="javascript:;" data-toggle="modal" data-target="#myModal" class="btn btn-success btn-m pull-right"><i class="fa fa-chrome"></i>Add Expense</a>
        </div>  
    </div>
</div> 
<div class="card-body"> 

 <div class="table-responsive">
              <table class="table align-items-center table-flush">
                <thead class="thead-light">
        <tr>
            <th scope="col">SN</th>
            <th scope="col">Expense Name</th>
            <th scope="col">Amount</th>
            <th scope="col">Detail</th>
            <th scope="col">Date</th>
            <th scope="col">Actions</th>
        </tr>
    </thead>
        <?php
        $sn = 1; 
        foreach ($expense as $e) { ?>
            <tr>
                <td><?php echo $sn++; ?></td>
                <td><?php echo $e->expense_name; ?></td>
                <td><?php echo config_item('currency') . $e->amount; ?></td>
                <td><?php echo $e->detail; ?></td>
                <td><?php echo $e->date; ?></td>
                <td>
                    <a onclick="return confirm('Are you sure you want to delete this Expense Entry ?')"
                       href="<?php echo site_url('admin/expense_remove/' . $e->id); ?>" class="btn btn-danger btn-xs">Delete</a>
                </td>
            </tr>
        <?php } ?>
    </table>
</div>
<div class="modal fade" id="myModal" role="dialog">
    <div class="modal-dialog modal-md">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal">&times;</button>
                <!--<h4 class="modal-title">Make New Purchase Entry</h4>-->
            </div>
            <div class="modal-body">
                <?php echo form_open_multipart('admin/add_expense') ?>
                <div class="row">
                    <div class="form-group col-sm-6">
                        <label>Expense Name <span class="text-danger">*</span></label>
                        <input required type="text" name="ename" class="form-control">
                    </div>
                    <div class="form-group col-sm-6">
                        <label> Amount (in <?php echo config_item('currency') ?>) <span class="text-danger">*</span></label>
                        <input required type="text" class="form-control" name="eamount">
                    </div>
                    <div class="form-group col-sm-6">
                        <label>Expense Date <span class="text-danger">*</span></label>
                        <input type="text" readonly value="<?php echo date('Y-m-d') ?>" name="edate" class="form-control datepicker">
                    </div>
                    <div class="form-group col-sm-6">
                        <label>Detail </label>
                        <input  type="text" class="form-control" name="edetail">
                    </div>
                </div>
                <button type="submit" class="btn btn-primary">Add Expense</button>
                <?php echo form_close() ?>
            </div>
        </div>
    </div>
</div>
<div class="pull-right">
    <?php echo $this->pagination->create_links(); ?>
</div>
</div>
</div>
</div>
