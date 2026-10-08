

<div class="container mt-5">
    <!-- Display the table -->
    <div class="table-responsive">
        <table class="table table-bordered">
            <thead>
                <tr>
                    <th>UserID</th>
                    <th>Name</th>
                    <th>Product Id</th>
                    <th>Quantity</th>
                    <th>Item Price</th>
                    <th>GST</th>
                    <th>GST Amount</th>
                    <th>Status</th>
                    <th>Date</th>
                    <th>Action</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($req as $items): ?>
                    <tr>
                        <td><?php echo $items['userid']; ?></td>
                        <td><?php echo $items['franchisee_id']; ?></td>
                        <td><?php echo $items['product_id']; ?></td>
                        <td><?php echo $items['qty']; ?></td>
                        <td><?php echo config_item('currency') . $items['cost']; ?></td>
                        <td><?php echo $items['gst'] . "%"; ?></td>
                        <td><?php echo config_item('currency') . ($items['cost'] * ($items['gst'] / 100)); ?></td>
                        <td><?php echo $items['status']; ?></td>
                        <td><?php echo $items['date']; ?></td>

                    </tr>
                <?php endforeach; ?>
                <tr>
                    <td colspan="5" align="right"><strong>Total</strong></td>
                    <td><?php echo config_item('currency') . $this->cart->format_number($this->cart->total()); ?></td>
                </tr>
            </tbody>
        </table>
    </div>

</div>

