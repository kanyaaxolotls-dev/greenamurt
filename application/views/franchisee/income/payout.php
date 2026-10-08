<div class="card-header bg-white border-0">
    <div class="row align-items-center">
        <div class="col-9">
            <h4 class="mb-0"><?php echo $title; ?></h4>
        </div>
        <div class="col-3 text-right">
            <a href="<?php echo site_url('franchisee') ?>" class="btn btn-sm btn-primary">Home</a>
        </div>
    </div>
</div>
<div class="card-body">
    <form method="POST" action="<?php echo site_url('franchisee/payout'); ?>">
        <div class="row">
            <div class="col-md-12">
                <input type="date" name="from" class="form-control" value="<?= $from ?>">
            </div>
        </div>
        <div class="row mt-3">
            <div class="col text-right">
                <button type="submit" class="btn w-100 btn-success">Search</button>
            </div>
        </div>
    </form>

    <div class="table-responsive mt-3">
        <table class="table align-items-center table-flush" id="example">
            <thead class="thead-light">
                <tr>
                    <th scope="col">SN</th>
                    <th scope="col">Amount</th>
                    <th scope="col">Tax</th>
                    <th scope="col">Net Payout</th>
                    <th scope="col">Date</th>
                    <th scope="col">Status</th>
                </tr>
            </thead>
            <?php
            $sn = 1;
            foreach ($withdraw as $e) {
                $clr = $e['status'] === "Paid" ? 'success' : 'warning';
                $net_pay = $e['amount'] - $e['tax'];
            ?>
                <tr>
                    <td class="text-dark"><?php echo $sn++; ?></td>
                    <td class="text-dark"><?php echo '₹ ' . $e['amount']; ?></td>
                    <td class="text-dark"><?php echo '₹ ' . $e['tax']; ?></td>
                    <td class="text-dark"><?php echo '₹ ' . $net_pay; ?></td>
                    <td class="text-dark"><?php echo $e['date']; ?></td>
                    <td class="text-dark"><span class="btn btn-sm btn-<?= $clr ?>"><?php echo $e['status']; ?></span></td>
                </tr>
            <?php } ?>
        </table>
    </div>
</div>
