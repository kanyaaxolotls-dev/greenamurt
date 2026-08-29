<div class="col"> 
    <div class="card bg-secondary shadow">   
        <div class="card-header bg-white border-0"> 
                <div class="row align-items-center">
                    <div class="col-8">
                          <h3 class="mb-0"><?php echo $title ?></h3>
                    </div>
                <div class="col-4 text-right"> 
                    <a href="<?php echo site_url('cart/new-purchase')?>" class="btn btn-sm btn-primary">Home</a>
                </div>
            </div>
        </div>
    <div class="card-body"> 

<div class="table-responsive">
    <table class="table align-items-center table-flush">
        <thead class="thead-light">

                <tr>
                    <th>Item Description</th>
                    <th>QTY</th>
                    <th style="text-align:right">Item Price</th>
                      <th style="text-align:right">GST</th>
                    <th style="text-align:right">Sub-Total</th>
                </tr>
            </thead>
    <?php $i = 1; ?>

    <?php foreach ($this->cart->contents() as $items): ?>

        <?php echo form_hidden('rowid[]', $items['rowid']); ?>

        <tr>
            <td>
                <?php echo $items['name']; ?>

                <?php if ($this->cart->has_options($items['rowid']) == TRUE) : ?>

                    <p>
                        <?php foreach ($this->cart->product_options($items['rowid']) as $option_name => $option_value): ?>

                            <strong><?php echo $option_name; ?>:</strong> <?php echo $option_value; ?><br/>

                        <?php endforeach; ?>
                    </p>

                <?php endif; ?>

            </td>
            <td><?php echo $items['qty']; ?></td>

            <td style="text-align:right"><?php echo config_item('currency') . $this->cart->format_number($items['price']); ?></td>
            <td style="text-align:right"><?php echo config_item('currency') . $this->cart->format_number($items['qty']*$items['gst']); ?></td>
             <td style="text-align:right"><?php echo config_item('currency') . $this->cart->format_number($items['subtotal']+$items['qty']*$items['gst']); ?></td>
        </tr>

        <?php $i++; ?>

    <?php endforeach; ?>

    <tr>
        <td colspan="3"></td>
        <td class="right"><strong>Total</strong></td>
      <td class="right"  style="text-align: right;"><?php echo config_item('currency') . $this->cart->format_number($this->cart->total()+$items['qty']*$items['gst']); ?></td>
    </tr>

</table>
<?php
$this->cart->destroy();
?>
</div>
</div>
</div>
</div>
</div><!-- end of panel-->
</div><!-- end of main panel-->
<!--<a href="javascript:;" class="btn btn-xs btn-primary" onclick="print()">Print</a>-->