<div class="col">
    <div class="card bg-secondary shadow">  
        <div class="card-header bg-white border-0"> 
        <div class="row align-items-center">
            <div class="col-8"> 
                  <h3 class="mb-0">Reward Management</h3>
            </div>
        <div class="col-4 text-right"> 
            <a href="<?php echo site_url('admin')?>" class="btn btn-sm btn-primary">Home</a>
        </div> 
    </div>
</div> 
<div class="card-body">
    <div class="table-responsive">
        <table class="table align-items-center table-flush">
        <thead class="thead-light">
        <tr>
            <th scope="col">S.N.</th>
            <th scope="col">User ID</th>
            <th scope="col">Reward Name</th>
            <th scope="col">Achieve Date</th>
            <th scope="col">Sponsors</th>
            <th scope="col">Paid Date</th>
            <th scope="col">Status</th>
            <th scope="col">Reward Amt.</th>
            <th scope="col">#Action</th>
        </tr>
        </thead>
        <tbody>
        <?php
        $sn = 1;
        foreach ($data as $e) {

            ?>
            <tr>
                <td><?php echo $sn++; ?></td>
                <td><?php echo config_item('ID_EXT') . $e->userid ?></td>
                <td><?php echo $this->db_model->select('reward_name', 'reward_setting', array('id' => $e->reward_id)) ?></td>
                <td><?php echo $e->date ?></td>
                
                <td> <?php echo $sp_count = $this->db_model->count_all('member', array(
                    'sponsor' => $e->userid,)); ?></td>
                    <td><?php echo $e->paid_date ?></td>
                <td><?php echo $e->tid ?></td>
                 <td><?php echo $this->db_model->select('reward_amt', 'reward_setting', array('id' => $e->reward_id)) ?></td>
                <td>
                    <?php if ($e->status == "Pending") { ?>
                        <a data-toggle="modal" data-target="#myModal"
                           onclick="document.getElementById('payid').value='<?php echo $e->id ?>'"
                           class="btn btn-primary btn-sm">Pay</a>
                    <?php } ?>
                    <a href="<?php echo site_url('income/reward_remove/' . $e->id) ?>" class="btn btn-danger btn-sm"
                       onclick="return confirm('Are you sure want to delete this reward ?')">Delete</a></td>
            </tr>
        <?php } ?>
        </tbody>
    </table>
</div>
<br><br><br><br>
<div class="modal fade" id="myModal" role="dialog">
    <div class="modal-dialog modal-sm">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal">&times;</button>
                <h4 class="modal-title">Delivery Detail</h4>
            </div>
            <div class="modal-body">
                <?php echo form_open('income/reward_pay') ?>
                <label>Enter Delivery/Courier Detail</label>
                <input type="hidden" name="payid" value="" id="payid">
                <textarea class="form-control" name="tdetail"></textarea>
                <div class="pull-right">
                    <button type="submit" class="btn btn-success">Pay Reward Now</button>
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

