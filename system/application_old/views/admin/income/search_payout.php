<?php
/***************************************************************************************************

 **************************************************************************************************/

$top_id = $this->uri->segment('3') ? $this->uri->segment('3') : config_item('top_id');
$status = $this->uri->segment('4') ? $this->uri->segment('4') : '';
$sdate  = $this->uri->segment('5') ? $this->uri->segment('5') : '';
$edate  = $this->uri->segment('6') ? $this->uri->segment('6') : '';
$this->db->where('userid', htmlentities($top_id));
if ($status !== "") {
    $this->db->where('status', $status);
}
if ($sdate !== "") {
    $this->db->where('paid_date >=', $sdate);
}
if ($edate !== "") {
    $this->db->where('paid_date <=', $edate);
}
$this->db->limit(100);
$data = $this->db->get('withdraw_request')->result();
?>
<div class="col">
    <div class="card bg-secondary shadow">  
        <div class="card-header bg-white border-0"> 
        <div class="row align-items-center">
            <div class="col-8">
                  <h3 class="mb-0">Search Payout</h3>
            </div>
        <div class="col-4 text-right"> 
            <a href="<?php echo site_url('admin')?>" class="btn btn-sm btn-primary">Home</a>
        </div> 
    </div>
</div>
<div class="card-body">

    <form method="post" action="">
        <div class="row">
        <div class="form-group col-sm-3"> 
            
            <label>Enter User Id</label>
            <input type="text" required name="top_id" class="form-control form-control-alternative form-control-alternative-alternative">
            
        </div>
        <div class="form-group col-sm-3">
            <label>Status</label>
            <select name="status" class="form-control form-control-alternative form-control-alternative-alternative">
                <option selected>Paid</option>
                <option>Un-Paid</option>
                <option>Hole</option>
            </select>
        </div>
        <div class="form-group col-sm-2">
            <label>Start Payout Date</label>
            <input type="text" readonly class="form-control datepicker" name="sdate">
        </div>
        <div class="form-group col-sm-2">
            <label>End Payout Date</label>
            <input type="text" readonly class="form-control datepicker" name="edate">
           
        </div>
         <div class="form-group col-sm-2">
            <br>
              <button type="submit" class="btn btn-primary btn-lg">Search</button>
         </div>
     </div>
    </form>

<div class="table-responsive">
   <table class="table align-items-center table-flush">
        <thead class="thead-light">
        
            <th scope="col">S.N.</th>
            <th scope="col">User ID</th>
            <th scope="col">Amount</th>
            <th scope="col">Tax Deducted</th>
            <th scope="col">Date</th>
            <th scope="col">Status</th>
            <th scope="col">Transaction Detail</th>
            <th scope="col">#Action</th>
        
        </thead>
        <tbody>
        <?php
        $sn = 1;
        foreach ($data as $e) {
            ?>
            <tr>
                <td><?php echo $sn++; ?></td>
                <td><?php echo config_item('ID_EXT') . $e->userid ?></td>
                <td><?php echo config_item('currency') . $e->amount ?></td>
                <td><?php echo config_item('currency') . $e->tax ?></td>
                <td><?php echo $e->date ?></td>
                <td><?php echo $e->status ?></td>
                <td><?php echo $e->tid ?></td>
                <td>
                    <?php if ($e->status == "Un-Paid") { ?>
                        <a data-toggle="modal" data-target="#myModal"
                           onclick="document.getElementById('payid').value='<?php echo $e->id ?>'"
                           class="btn btn-primary btn-sm">Pay</a>
                    <?php } ?>
                    <?php if ($e->status == "Hold") { ?>
                        <a href="<?php echo site_url('income/unhold/' . $e->id) ?>" class="btn btn-success btn-xs">Un-Hold</a>
                    <?php } else { ?>
                        <a href="<?php echo site_url('income/hold/' . $e->id) ?>"
                           class="btn btn-success btn-sm">Hold</a>

                    <?php } ?>
                    <?php if ($e->status == "Paid") { ?>
                        <a href="<?php echo site_url('income/unpay/' . $e->id) ?>"
                           class="btn btn-success btn-sm">Un-Pay</a>
                    <?php } ?>

                    <a href="<?php echo site_url('income/remove/' . $e->id) ?>" class="btn btn-danger btn-sm"
                       onclick="return confirm('Are you sure want to delete this payout ?')">Delete</a></td>
            </tr>
        <?php } ?>
        </tbody>
    </table>
</div>
</div>
</div>
<div class="modal fade" id="myModal" role="dialog">
    <div class="modal-dialog modal-sm">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal">&times;</button>
                <h4 class="modal-title">Payout Detail</h4>
            </div>
            <div class="modal-body">
                <?php echo form_open('income/pay') ?>
                <label>Enter Transaction Detail</label>
                <input type="hidden" name="payid" value="" id="payid">
                <textarea class="form-control form-control-alternative form-control-alternative-alternative" name="tdetail"></textarea>
                <div class="pull-right">
                    <button type="submit" class="btn btn-success">Pay Now</button>
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