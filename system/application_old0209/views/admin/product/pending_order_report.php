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
            <div class="table-responsive">
              <table class="table align-items-center table-flush">
                <thead class="thead-light">
            <tr>
                <th scope="col">SN</th>
                <th scope="col">User ID</th>
                <th scope="col">Customer Name</th>
                <th scope="col">Product</th>
                <th scope="col">Cost</th>
                <th scope="col">Order Date</th>
                <th scope="col">Qty</th>
                <th scope="col">Sold By</th>
                <th scope="col">Status</th>
                
            </tr>
        </thead>
        <?php
        $sn = 1;
        foreach ($orders as $e) { ?>
            <tr>
                <td><?php echo $sn++; ?></td>
                <td><?php echo config_item('ID_EXT') . $e->userid; ?></td>
                <td><?php echo $this->db_model->select('name', 'member', array('id' => $e->userid)); ?></td>
                <td><?php echo $this->db_model->select('prod_name', 'product', array('id' => $e->product_id)); ?></td>
                <td><?php echo config_item('currency') . $e->cost; ?></td>  
                <td><?php echo $e->date; ?></td> 
                <td><?php echo $e->qty; ?></td> 
                <td><?php echo $e->franchisee_id; ?></td> 
                <td><?php echo $e->status; ?></td> 
              
            </tr>
        <?php } ?>
    </table>
    <div class="pull-right">
        <?php echo $this->pagination->create_links(); ?>
    </div>
</div>
<div class="modal fade" id="myModal" role="dialog">
    <div class="modal-dialog modal-sm">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal">&times;</button>
                <h4 class="modal-title">Delivery Detail</h4>
            </div>
            <div class="modal-body">
                <?php echo form_open('product/deliver') ?>
                <label>Enter Delivery Detail (eg: Tracking No)</label>
                <input type="hidden" name="deliverid" value="" id="deliverid">
                <textarea class="form-control" name="tdetail"></textarea>
                <div class="pull-right">
                <button type="submit" class="btn btn-success">Deliver Now</button>
                </div>
                <?php echo form_close() ?>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
            </div>
        </div>
    </div>
</div>
</div>
</div>
</div>