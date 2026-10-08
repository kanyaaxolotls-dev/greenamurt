
<div class="col">
    <div class="card bg-secondary shadow">  
        <div class="card-header bg-white border-0"> 
        <div class="row align-items-center">
            <div class="col-8"> 
                  <h3 class="mb-0">Tax Report</h3>
            </div>
        <div class="col-4 text-right"> 
            <a href="<?php echo site_url('admin')?>" class="btn btn-sm btn-primary">Home</a>
        </div> 
    </div>
</div> 
<div class="card-body">
    <div class="py-4">
        <form action="<?= base_url('income/tax-report') ?>" method="post">
            <div class="row">
                <div class="col-md-4 col-sm-12">
                    <label for="exampleSelect">Userid :</label>
                    <input type="text" placeholder="Enter userid" name="type" class="form-control" value="<?= $type ?>"> 
                </div>
                <div class="col-md-4 col-sm-6 col-6">
                    <label for="exampleSelect">Start Date :</label>
                    <input type="date" name="start_date" class="form-control" value="<?= $start_date ?>"> 
                </div>
                <div class="col-md-4 col-sm-6 col-6">
                    <label for="exampleSelect">End Date :</label>
                    <input type="date" name="end_date" class="form-control" value="<?= $end_date ?>"> 
                </div>
                <div class="col-12 mt-3">
                    <input type="submit" value="Search" class="btn btn-success w-100"> 
                </div>
            </div>
        </form>
    </div>

<hr/>
<div class="table-responsive">
<table id="example" class="table table-bordered table-striped">
    <thead>
        <tr>
            <th>S.N.</th>
            <th>User ID</th>
            <th>Full Name</th>
            <th>PAN No.</th>
            <th>Payout Amount</th>
            <th>Net Paid</th>
            <th>TDS (%)</th>
            <th>Date</th> 
            <th>TDS (Amt.)</th> 
            <!--<th>#</th>-->
        </tr>
    </thead>
    <tbody>
    <?php 
        $sn = 1;
        foreach ($data as $e) {
    ?>
    <tr>
        <td><?php echo $sn++; ?></td>
        <td><?php echo $e->userid;?></td>
        <td><?php echo $this->db_model->select('name','member',array('id' => $e->userid));?></td>
        <td>
            <?php 
                $this->db->where('userid', $e->userid);
                $data1 = $this->db->get('member_profile')->result();
                foreach ($data1 as $e1) {
                    echo $e1->tax_no;
                }
            ?>
        </td>
        <td><?php echo $e->amount ?></td>
        <td><?php echo ($e->amount - $e->tax_amount) ?></td>
        <td><?php echo $e->tax_percnt ?></td>
        <td><?php echo $e->date ?></td>
        <!--<td><a href="<?php echo site_url('income/tax-remove/' . $e->id) ?>" class="btn btn-danger btn-xs" onclick="return confirm('Are you sure want to delete this record ?')">Delete</a></td>-->
        <td><?php echo $e->tax_amount ?></td>
    </tr>
    <?php } ?>
    </tbody>
    <tfoot> 
    <tr class="border">
        <td></td>
        <td></td>
        <td></td>
        <td></td>
        <td></td>
        <td></td>
        <td></td>
        <td></td>
        <!--<td></td>-->
        <td class="h3 text-dark">Total : <?php echo $sum_tax_amt ?></td>
    </tr>
    </tfoot>
</table>

</div>
</div>
</div>
</div>