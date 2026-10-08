<div class="container-fluid">
<div class="card bg-secondary shadow">
    <div class="card-header border-0">
        <div class="row align-items-center">
            <div class="col-11">
                <h4 class="mb-0"><?php echo $title ?></h4>
            </div>
            <div class="col-1">
                <a href="<?php echo site_url('Franch/fran_request') ?>" class="btn btn-sm btn-primary">Back</a>
            </div>
        </div>
    </div>

    <div class="card-body">
        <div class="table-responsive">
            <table class="table align-items-center table-flush">
                <thead class="thead-light">
                    <tr>
                        <th>Sr. No.</th>
                        <th>Item Description</th>
                        <th>QTY</th>
                        <th style="text-align:right">Item Price</th>
                        <th style="text-align:right">Sub-total</th>
                    </tr>
                </thead>
                <?php $i=0;$total_amt = 0; foreach ($orders as $items): $i++;$total_amt += $items['item_price'] * $items['quantity']; ?>
                    <tr>
                        <td><?= $i; ?></td>
                        <td><?php echo $this->db_model->select('prod_name','product',array('id' => $items['prod_id'])); ?></td>
                        <td><?php echo $items['quantity']; ?></td>
                        <td style="text-align:right"><?php echo config_item('currency').$items['item_price']; ?></td>
                        <td style="text-align:right"><?php echo config_item('currency').$items['item_price'] * $items['quantity']; ?></td>
                    </tr>
                <?php endforeach; ?>
                <tr>
                    <td colspan="4"></td>
                    <td style="text-align: right;"><strong>Total : </strong><?php echo config_item('currency') . $total_amt; ?></td>
                </tr>
            </table>
            <hr>
        </div>
    </div>
</div>
</div>
