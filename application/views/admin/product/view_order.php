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
                <tr style="font-weight: bold">
                    <td>Product Name</td>
                    <td>User ID</td>
                    <td>Customer Name</td>
                    <td>Product Price</td>
                    <td>Qty</td>
                    <td>Total Cost</td>
                    <td>Order Date</td>
                    <td>Status</td>
                </tr>
            </thead>
            <tbody>
                <?php
                    $grand_total = 0;
                    foreach($orders as $order){
                        $dealer_price  = $this->db_model->select('dealer_price', 'product', array('id' => $order->product_id));
                        $product_name  = $this->db_model->select('prod_name', 'product', array('id' => $order->product_id));
                        $customer_name = $this->db_model->select('name', 'member', array('id' => $order->userid));
                        $total         = $dealer_price * $order->qty;
                        $grand_total   += $total;
                ?>
                <tr>
                    <td><?php echo $product_name; ?></td>
                    <td><?php echo $order->userid; ?></td>
                    <td><?php echo $customer_name; ?></td>
                    <td><?php echo $dealer_price; ?></td>
                    <td><?php echo $order->qty; ?></td>
                    <td><?php echo config_item('currency') . number_format($total, 2); ?></td>
                    <td><?php echo $order->date; ?></td>
                    <td><?php echo $order->status; ?></td>
                </tr>
                <?php } ?>
                <tr style="font-weight: bold; background: #f1f1f1;">
                    <td colspan="7" class="text-right">Total Order Amount</td>
                    <td><?php echo config_item('currency') . number_format($grand_total, 2); ?></td>
                </tr>
            </tbody>
        </table>
    </div>
</div>
<div class="card-footer">
    <a href="javascript:history.back()" class="btn btn-sm btn-primary">Go Back</a>
</div>
</div>
</div>
