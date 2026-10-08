<div class="col">
        <div class="card bg-secondary shadow">  
            <div class="card-header bg-white border-0"> 
                <div class="row align-items-center">
                    <div class="col-8"> 
                          <h3 class="mb-0">Payment Deposite History</h3>
                    </div>
                   <div class="col-4 text-right">  
                        <a href="<?php echo site_url('wallet/support')?>" class="btn btn-sm btn-warning">Processing</a>
                        <a href="<?php echo site_url('wallet/support/A')?>" class="btn btn-sm btn-success">Approved</a>
                        <a href="<?php echo site_url('wallet/support/R')?>" class="btn btn-sm btn-danger">Rejected</a>
                    </div>
                </div>  
            </div>  
        <div class="card-body">
        <div class="table-responsive" >
            <table class="table align-items-center table-flush" id="example">
                <thead class="thead-light">
                <th scope="col">SN</th>
                <th scope="col">User Id</th>
                <th scope="col">Name</th>
                <th scope="col">Date</th>
                <th scope="col">Amount</th>
                <!--<th scope="col">Type</th>-->
                <th scope="col">Document</th>
                 <!--<th scope="col">Transcation Number</th>-->
                <th scope="col">Status</th>
                <th scope="col">Action</th>
               <!--  <th>#</th> -->
            </thead>
            <?php
            $sn = 1;
            foreach ($data as $e) { ?>
                <tr>
                    <td><?php echo $sn++; ?></td>
                    <td><?php echo $e->userid; ?></td>
                    <td><?php echo $this->db_model->select('name', 'member', array('id' => $e->userid)); ?></td>
                    <td><?php echo $e->date; ?></td>
                    <td><?php echo $e->amount; ?></td>
                    <!--<td><?php echo $e->type; ?></td>-->
                    <!--<td><img src="<?php echo $e->recepit ? base_url('uploads/'.$e->recepit) : base_url('uploads/default.jpg'); ?>"></td>-->
                    <td>
                        <a target="_blank" href="<?php echo $e->recepit ? base_url('uploads/'.$e->recepit) : base_url('uploads/default.jpg'); ?>">View<a/><span style="font-weight:bolder"> OR </span>
                        <a target="_blank" href="<?php echo $e->recepit ? base_url('uploads/'.$e->recepit) : base_url('uploads/default.jpg'); ?>" download>Download<a/>
                    </td>
                    <!--<td><?php echo $e->tnumber; ?></td>-->
                    <td><?php echo $e->status; ?></td>
                    <td>
                        <?php if($e->status == 'pending'){ ?>
                        <a href="<?php echo site_url('wallet/approve_fund_request/' . $e->id) ?>" class="btn btn-sm btn-success">Approve</a>
                        <a href="<?php echo site_url('wallet/reject_fund_request/' . $e->id) ?>" class="btn btn-sm btn-danger">Reject</a>
                        <?php }else{ ?>
                        <a class="btn btn-sm btn-secondary">Action Performed</a>
                        <?php } ?>
                    </td> 
                </tr>
            <?php } ?>
        </table>
    </div>
    <div class="pull-right">
        <?php echo $this->pagination->create_links(); ?>
    </div>
</div>
</div>

</div>
</div>