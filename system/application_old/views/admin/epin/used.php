  <div class="col">
    <div class="card bg-secondary shadow">  
        <div class="card-header bg-white border-0"> 
        <div class="row align-items-center">
            <div class="col-8">
                  <h3 class="mb-0">Used E-Pin / Value Key</h3>
            </div>
        <div class="col-4 text-right"> 
            <a href="<?php echo site_url('admin')?>" class="btn btn-sm btn-primary">Home</a>
        </div>
    </div>
</div>
<div class="card-body">
    <?php
    // Calculate totals
    $total_epins_count = count($epin);  
    $total_amount_sum  = 0;
    
    foreach ($epin as $e) {
        $total_amount_sum += $e['amount'];
    }
    ?>
    
    <div class="row mb-3">
        <!-- Total Epins Card -->
        <div class="col-md-6">
            <div class="card text-white bg-secondary shadow">
                <div class="card-body">
                    <h5 class="card-title">Total Epins</h5>
                    <h3><?= $total_epins_count ?></h3>
                </div>
            </div>
        </div>
    
        <!-- Total Amount Card -->
        <div class="col-md-6">
            <div class="card text-white bg-secondary shadow">
                <div class="card-body">
                    <h5 class="card-title">Total Amount</h5>
                    <h3>₹<?= number_format($total_amount_sum, 2) ?></h3>
                </div>
            </div>
        </div>
    </div>
    
   <table class="table align-items-center table-flush" id="example">
        <thead class="thead-light">

        <tr>
            <th scope="col">SN</th>
            <th scope="col">Epin</th>
            <th scope="col">Amount</th>
            <th scope="col">Used By</th>
            <th scope="col">Used Date</th>
            <th scope="col">Type</th>
            <th scope="col">Actions</th>
        </tr>
    </thead>
        <?php
        $sn = 1;
        foreach ($epin as $e) { ?>
            <tr>
                <td><?php echo $sn++; ?></td>
                <td><?php echo $e['epin']; ?></td>
                <td><?php echo $e['amount']; ?></td>
                <td><?php echo config_item('ID_EXT') . $e['used_by']; ?></td>
                <td><?php echo $e['used_time']; ?></td>
                <td><?php echo $e['type']; ?></td>
                <td>
                    <a href="<?php echo site_url('admin/epin/edit/' . $e['id']); ?>"
                       ><img src="https://img.icons8.com/cotton/20/000000/edit.png"></a>
                    <a onclick="return confirm('Are you sure you want to delete this epin ?')"
                       href="<?php echo site_url('admin/epin/remove/' . $e['id']); ?>"><img src="https://img.icons8.com/color/20/000000/clear-symbol.png"></a>
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
