<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>

<div class="card bg-secondary shadow">
    <div class="card-header border-0">
        <div class="row align-items-center">
            <div class="col-11">
                <h4 class="mb-0"><?php echo $title ?></h4>
            </div>
            <div class="col-1">
                <a href="<?php echo site_url('franchisee/orders') ?>" class="btn btn-sm btn-primary">Back to orders</a>
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
                        <th style="text-align:right">GST</th>
                        <th style="text-align:right">Sub-Total</th>
                    </tr>
                </thead>
                <?php $i=0;$total_amt = 0; foreach ($orders as $items): $i++;$total_amt += $items['cost']; ?>
                    <tr>
                        <td><?= $i; ?></td>
                        <td><?php echo $this->db_model->select('prod_name','product',array('id' => $items['product_id'])); ?></td>
                        <td><?php echo $items['qty']; ?></td>
                        <td style="text-align:right"><?php echo config_item('currency').$items['cost']; ?></td>
                        <td style="text-align:right"><?php echo $items['gst'] . "%"; ?></td>
                        <td style="text-align:right"><?php echo config_item('currency') .$items['cost']; ?></td>
                    </tr>
                <?php endforeach; ?>
                <tr>
                    <td colspan="5"></td>
                    <td style="text-align: right;"><strong>Total : </strong><?php echo config_item('currency') . $total_amt; ?></td>
                </tr>
            </table>
            <hr>
            <div style="text-align:right">
                <a href="tel:+91 <?php echo $phone; ?>" id="send_request_btn" class="btn btn-danger btn-md">Call Now &rarr;</a>
                <!--<a href="<?= base_url('franchisee/order_deliver/'.$this->uri->segment(3)); ?>" id="send_request_btn" class="btn btn-success btn-md">Deliver Now&rarr;</a>-->
                <a class="btn btn-success btn-md" data-toggle="modal" data-target="#myModal"
                            onclick="document.getElementById('deliverid').value='<?php echo $orderid ?>'"
                           >Deliver</a>
            </div>
        </div>
    </div>
</div>
<div class="modal fade" id="myModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered" role="document">
    <div class="modal-content">
    
      <div class="modal-header">
        <h5 class="modal-title" id="exampleModalLabel">Confirm Delivery</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>

      <?php echo form_open_multipart('franchisee/order_deliver/'.$orderid) ?>
      <div class="modal-body text-center">
        <p class="text-dark mb-0">Are you sure you want to mark this order as delivered?</p>

        <!-- Hidden Fields -->
        <input type="hidden" name="tdetail" value="N/A" />
        <input type="hidden" name="otp" value="0000" />
      </div>

      <div class="modal-footer justify-content-center">
        <button type="button" class="btn btn-sm btn-secondary" data-dismiss="modal">Cancel</button>
        <button type="submit" class="btn btn-sm btn-success">Yes, Deliver Now</button>
      </div>

      <?php echo form_close() ?>
    </div>
  </div>
</div>
