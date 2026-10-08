


 <div class="offset-xl-2 col-xl-8 col-lg-12 col-md-12 col-sm-12 col-12 padding">
     <?php foreach($invoice_data as $inv) { ?>
    <button class="btn btn-secondary btn-sm mb-2" onClick="window.print()"> Print Invoice</button>
        <div class="card">
              <div class="card-header">
                        <div class="row mb-1">
                               <div class="col-sm-5">
                                    <img src="<?php echo base_url('uploads/logo.png') ?>" width="200"/>
                                </div>
                           <!--     <div class="col-sm-7 d-flex flex-wrap">-->
                           <!--         <h5><?php echo config_item('company_name'); ?></h5>-->
                           <!--         <address><?php echo config_item('company_address'); ?></address>-->
                           <!--         <h6>Date: <?php echo $today = date("F j, Y, g:i a");?></h6>-->
                           <!--</div><!-- end col-->
                               <div class="col-sm-7 d-flex flex-wrap flex-column">
                                    <h5>VERA HERBAL</h5>
                                    <address style="white-space: pre-line;">
                                Indira Nagar, near petrol pump 
                                Tahsil: Ralegaon
                                District: Yavatmal, Maharashtra - 445402
                                Email: veraherbal.info@gmail.com
                                    </address>
                                    <h6>Date: <?php echo date("F j, Y, g:i a"); ?></h6>
                                </div>
                        </div> 
                </div>
         <div class="card-body ">
          <h2 class="text-center">Invoice</h2>
             <div class="row mb-4">
                 <div class="col-sm-7">
                     <h6 class="mb-3">Billing to:</h6>
                     <h6 class="text-dark mb-1"><?php echo $inv['name']; ?></h6>
                     <div>Address: <?php echo $inv['address']; ?></div>
                     <div>City: <?php echo $inv['city']; ?>, State: <?php echo $inv['state']; ?></div>
                     <div>Country: <?php echo $inv['country']; ?></div>
                     <div>Email: <?php echo $inv['email']; ?></div>
                     <div>Phone: <?php echo $inv['phone']; ?></div>
                 </div>
                 <div class="col-sm-5">
                   <h6> Inovice Date: </h6><p class="mb-1"><?php echo $inv['date']; ?></p>

                    <h6>Invoice No: </h6><p class="mb-1 text-uppercase"> #INV000<?php echo $inv['orderid']; ?> </p>

                    <h6>Payment Method: </h6><p class="mb-1"><?php echo $inv['payment_method']; ?></p>

                     <!--  $userinfo = $this->db_model->select_multi('*', 'product_item_sale', array('order_id' => $this->session->_order_id_)); -->
                      
                 </div>
             </div>

     <?php } ?>
                                                    <div class="card-body table-responsive">
                                                            <table id="" class="table align-middle table-nowrap table-check">
                     <thead>
                         <tr>
                             <th class="center">#</th>
                             <th >Item</th>
                             <th style="text-align:right">Description</th>
                             <th style="text-align:right">Price</th>
                              <th style="text-align:right">Qty</th>
                             <th style="text-align:right">GST</th>
                             <th style="text-align:right">Cost</th>
                            <th style="text-align:right">Total</th>
                         </tr>
                     </thead>   
                     <tbody>
                        <?php $i = 1; 

                            foreach ($this->cart->contents() as $items): ?>

                                <?php echo form_hidden('rowid[]', $items['rowid']); ?>

                                <tr>
                                    <td><?php echo $items['id']; ?></td>
                                    <td><?php echo $items['name']; ?></td>
                                    <td><?php echo $items['prod_desc'];?></td>
                                    <td style="text-align:right"><?php echo $this->cart->format_number($items['price']); ?></td>
                                    <td style="text-align:right"><?php echo $items['qty']; ?></td>

                                    <td style="text-align:right"><?php 
                                        echo $this->cart->format_number($items['gst_amt'] * $items['qty']); ?></td>
                                    <td style="text-align:right"><?php echo $this->cart->format_number($items['qty'] * $items['price']); ?></td>
                                     <td style="text-align:right"><?php echo $this->cart->format_number($items['qty'] *$items['price']); ?></td>
                                </tr>

                                <?php $i++; ?>

                            <?php endforeach; ?>
                     </tbody>
                 </table>  
             </div>
             <div class="row">
                 <div class="col-lg-8 col-sm-5">
                 </div>
                 <div class="col-lg-4 col-sm-5 ml-auto">
                     <table class="table table-clear">
                         <tbody>
                            <tr>
                                
                            <td class="right"><strong>Total Value:</strong></td>
                            <td class="right"  style="text-align: right;">
                                <?php echo $this->cart->format_number($this->cart->total()); ?>
                            </td>
                            </tr>
 
                         </tbody>
                     </table>
                 </div>
                  
             </div>
            <?php $this->cart->destroy(); ?>
         </div>
         <div class="card-footer bg-white text-center">
             <p class="mb-0"><?php echo config_item('company_name') ?>, <?php echo config_item('company_address') ?></p>
         </div>
        
     </div>
 </div>
