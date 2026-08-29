<?php

$top_id = $this->session->user_id;
$this->db->select('id, transfer_from, transfer_to, amount, time')
         ->from('transfer_balance_records')
         ->where('transfer_to', htmlentities($top_id))
         ->or_where('transfer_from', htmlentities($top_id));
$data = $this->db->get()->result();
?>
<div class="card bg-secondary shadow">  
    <div class="card-header bg-white border-0"> 
        <div class="row align-items-center">
            <div class="col-9">
                <h4 class="mb-0"><?php echo $title ?></h4>
            </div>
            <div class="col-3 text-right"> 
                <a href="<?php echo site_url('member/index') ?>" class="btn btn-sm btn-primary">Home</a>
            </div>
        </div>
    </div>
    <div class="card-body"> 
        <div class="table-responsive">
            <table class="table align-items-center table-flush">
                <thead class="thead-light">
                    <tr>
                        <th>S.N.</th>
                        <th>Transferred From</th>
                        <th>Transferred To</th>
                        <th>Amount</th>
                        <th>Status</th>
                        <th>Date</th>
                    </tr>
                </thead>
                <tbody>
                <?php
                $sn = 1;
                foreach ($data as $e) {
                    // Determine credit/debit status
                    $status = $e->transfer_to == $top_id ? 'Credit' : 'Debit';
                    ?>
                    <tr>
                        <td><?php echo $sn++; ?></td>
                        <td><?php echo $e->transfer_from; ?></td>
                        <td><?php echo $e->transfer_to; ?></td>
                        <td><?php echo config_item('currency') . $e->amount; ?></td>
                        <td><?php echo $status; ?></td>
                        <td><?php echo $e->time; ?></td>
                    </tr>
                <?php } ?>
                </tbody>
            </table>
        </div>
    </div>
</div>
