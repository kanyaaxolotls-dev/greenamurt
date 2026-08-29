
    <div class="card bg-secondary shadow">   
        <div class="card-header bg-white border-0"> 
                <div class="row align-items-center">
                    <div class="col-8">
                          <h3 class="mb-0"><?php echo $title ?></h3>
                    </div> 
                <div class="col-4 text-right"> 
                    <a href="<?php echo site_url('franchisee')?>" class="btn btn-sm btn-primary">Home</a>
                </div>
            </div>
        </div> 
    <div class="card-body"> 

    <table class="table align-items-center table-flush" id="example">
    <thead class="thead-dark">
    <tr>
        <th>SN</th>
        <th>Userid</th>
        <th>Phone Number</th>
        <th>Orderid</th>
        <th>Payment Method</th>
        <th>Total Amt</th>
        <th>Date</th>
        <th>Actions</th>
    </tr>
    </thead>
        <?php
            $sn = 1;
            foreach ($orders as $e) {
        ?>
            <tr class="bg-secondary">
                <td class="text-white"><?php echo $sn++; ?></td>
                <td class="text-white"><?php echo $e['userid']; ?></td>
                <td class="text-white"><a href="tel:+91 <?php echo $this->db_model->select('phone','member',array('id' => $e['userid']));; ?>"><?php echo $this->db_model->select('phone','member',array('id' => $e['userid']));; ?></a></td>
                <td class="text-white">#<?php echo $e['orderid']; ?></td>
                <td class="text-white"><?php echo $e['payment_method']; ?></td>
                <td class="text-white"><?php echo config_item('currency') . $this->db_model->sum('cost','product_sale',array('orderid' => $e['orderid']));; ?></td>
                <td class="text-white"><?php echo $e['date']; ?></td>
                <td class="text-white">
                    <a href="<?php echo site_url('franchisee/order_view/'.$e['orderid']); ?>" class="btn btn-info btn-sm">View Order</a>
                    <?php if($e['status'] == 'Processing'){ ?>
                    <!--<a href="<?php echo site_url('franchisee/order_deliver/'.$e['orderid']); ?>" class="btn btn-success btn-sm">Deliver</a>-->
                    <a class="btn btn-success btn-sm" data-toggle="modal" data-target="#myModal"
                       onclick="setDeliverOrder('<?php echo $e['orderid'] ?>')">Deliver</a>
                    <?php }else{ ?>
                    <a class="btn btn-success btn-sm" href="<?= base_url('franchisee/invoice_view2/'.$e['orderid']) ?>">View invoice</a>
                    <?php } ?>
                </td>
            </tr>
        <?php } ?>
    </table>
</div>
</div>

<div class="modal fade" id="myModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="exampleModalLabel">Delivery Details</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
     <?php echo form_open_multipart('franchisee/order_deliver/', ['id' => 'deliverForm']) ?>
    <div class="modal-body text-center">
        <p class="text-dark mb-0">Are you sure you want to mark this order as delivered?</p>

        <input type="hidden" name="orderid" id="deliverid" />
        <input type="hidden" name="tdetail" value="N/A" />
        <input type="hidden" name="otp" value="0000" />
    </div>

    <div class="modal-footer justify-content-center">
        <button type="button" class="btn btn-sm btn-secondary" data-dismiss="modal">Cancel</button>
        <button type="submit" class="btn btn-sm btn-success">Yes, Deliver Now</button>
    </div>
<?php echo form_close(); ?>
    </div>
  </div>
</div>

</div></div></div>
<script>
function setDeliverOrder(orderId) {
    document.getElementById('deliverid').value = orderId;
    document.getElementById('deliverForm').action = '<?= base_url('franchisee/order_deliver/') ?>' + orderId;
}
</script>
