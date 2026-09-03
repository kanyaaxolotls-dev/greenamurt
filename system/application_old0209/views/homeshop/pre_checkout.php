<div class="panel panel-body">
 <div class="container"> 
    <?php if ($this->cart->contents()) { ?><?php echo form_open('homeshop/update'); ?>
        <div class="panel panel-default">
            <div class="panel-heading">
                <h2>Checkout Shopping Cart</h2>
                <span style="float: right;">Hello! <?php if($this->session->name==''){
                    echo "Guest";
                } else{echo $this->session->name;} ?></span>
            </div>
        <div class="panel-body">
           <div class="table-responsive">
                <table class="table table-responsive">

                <tr style="background-color: #ff8f00;">
                    <th>Select</th>
                    <th>QTY</th>
                    <th>Item Description</th>
                    <th style="text-align:right">Item Price</th>
                    <th style="text-align:right">Sub-Total</th>
                </tr>

                <?php $i = 1; ?>

                <?php foreach ($this->cart->contents() as $items): ?>

                    <?php echo form_hidden('rowid[]', $items['rowid']); ?>

                    <tr>
                        <td style="width: 60px;"><?php echo form_submit('', 'Update your Cart'); ?></td>

                        <td style="width: 100px;"><?php echo form_input(array('name'      => 'qty[]]',
                                                        'value'     => $items['qty'],
                                                        'maxlength' => '3',
                                                        'size'      => '1',
                                                        'class'     =>'form-control',
                                                        'type'      =>'number',
                                                        'width'     =>'100%',
                                                  )); ?>
                                                      
                                                       
                                                  </td>
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
                        <td style="text-align:right"><?php echo config_item('currency') . $this->cart->format_number($items['price']); ?></td>
                        <td style="text-align:right"><?php echo config_item('currency') . $this->cart->format_number($items['subtotal']); ?></td>
                    </tr> 

                    <?php $i++; ?>

                <?php endforeach; ?>

                <tr>
                    <td colspan="2"></td>
                    <td class="right" style="text-align: right;"></td>
                    <td class="right"  style="text-align: right;"><strong>Total</strong></td>
                    <td class="right"  style="text-align: right;"><?php echo config_item('currency') . $this->cart->format_number($this->cart->total()); ?></td>
                </tr>

            </table> 
 
          </div>  

           <a style="text-align:left;"  href="<?php echo site_url('homeshop/new-purchase') ?>" class="btn btn-primary">Shop More &rarr;</a>
          <a href="<?php echo site_url('cart/pre_checkout') ?>" class="btn btn-success">Checkout &rarr;</a>
               <a href="<?php echo site_url('homeshop/guest_signup') ?>" class="btn btn-success">Guest Checkout &rarr;</a>
        </div> 
        <?php echo form_close();
    }
    else {

        echo '<h3 align="center" style="color:red"> <i class="fa fa-frown-o" aria-hidden="true"></i> You have no item in your cart</h3>';?>
         <a href="<?php echo site_url('homeshop/new-purchase') ?>" class="btn btn-success"><i class="fas fa-cart-arrow-down"></i> Shop More Products &rarr;</a>
     <?php } ?>
   

</div>
</div>
</div>
