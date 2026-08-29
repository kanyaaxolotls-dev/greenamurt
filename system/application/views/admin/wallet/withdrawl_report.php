<?php

$top_id = $this->uri->segment('3') ? $this->uri->segment('3') : config_item('top_id');
$status = $this->uri->segment('4') ? $this->uri->segment('4') : '';
$sdate  = $this->uri->segment('5') ? $this->uri->segment('5') : '';
$edate  = $this->uri->segment('6') ? $this->uri->segment('6') : '';
$this->db->where('userid', htmlentities($top_id));
if ($status !== "") {
    $this->db->where('status', $status);
}
if ($sdate !== "") {
    $this->db->where('date >=', $sdate);
}
if ($edate !== "") {
    $this->db->where('date <=', $edate);
}

$this->db->limit(100);
$data = $this->db->get('withdraw_request')->result();
?>
 <div class="col"> 
          <div class="card">  
            <div class="card-header bg-white border-0"> 
              <div class="row align-items-center">
                <div class="col-10">
                  <h3 class="mb-0">Withdraw Fund History</h3>
                </div>
                <div class="col-2 text-right">
                    <!--  <form method="post" action="<?php echo site_url('wallet/wallet_transactions') ?>">
                        <div class="form-group">
                            <input type="text" name="top_id" class="form-control" placeholder="User Id">
                        </div>
                        <div class="form-group">
                            <button class="btn btn-sm btn-success" type="submit">Search</button>
                        </div>
                    </form> -->
                    <a href="<?php echo site_url('admin')?>" class="btn btn-sm btn-primary">Home</a>

                </div>
              </div>
            </div>
            <div class="card">
                <div class="table-responsive">
                  <table class="table align-items-center table-flush">
                    <thead class="thead-light">
                        <tr>
                            <th scope="col">S.N.</th>
                            <th scope="col">User ID</th>
                            <th scope="col">Amount</th>
                            <th scope="col">Tax Deducted</th>
                            <th scope="col">Date</th>
                            <th scope="col">Status</th>
                        
                        </tr>
                        </thead>
                        <tbody>
                        <?php
                        $sn = 1;
                        foreach ($data as $e) {

                            ?>
                            <tr>
                                <td><?php echo $sn++; ?></td>
                                <td><?php echo $e->userid ?></td>
                                <td><?php echo config_item('currency') . $e->amount ?></td>
                                <td><?php echo config_item('currency') . $e->tax ?></td>
                                <td><?php echo $e->date ?></td>
                                <td><?php echo $e->status ?></td>
                               
                            </tr>
                        <?php } ?>
                        </tbody>
                    </table>
                </div>
        </div>
</div>
      