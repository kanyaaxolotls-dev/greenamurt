<?php
    $invoice_data = $result;
    $fran_data    = $this->db_model->select_multi('*','franchisee',array('id' => $this->session->fran_id));
    $user_data    = $this->db_model->select_multi('*','member',array('id' => $invoice_data->userid));

?>
    <div class="card bg-secondary shadow">   
        <div class="card-header bg-white border-0"> 
                <div class="row align-items-center">
                    <div class="col-8">
                          <h3 class="mb-0"><?php echo $title ?></h3>
                    </div> 
                <div class="col-4 text-right"> 
                    <a href="<?php echo site_url('franchisee/sale-history')?>" class="btn btn-sm btn-primary">Back</a>
                </div>
            </div>
        </div> 
    <div class="card-body table-responsive"> 
        <table class="table table-bordered">
            <thead>
                <tr class="text-center">
                    <th colspan="2" class="bordered padded">
                        <h5 class="text-white"><strong>TAX INVOICE</strong></h5>
                        <div class="row">
                            <div class='col-3'>
                                <img src="<?= base_url('uploads/logo.png') ?>" height="100px" />
                            </div>
                            <div class='col-9'>
                                <h6 class='text-left text-white'><strong><?= config_item('company_name') ?></strong></h6>
                                <!--<p class='text-left'>C-1303 13th Floor ARV Imperia, <br>Behind Dharmavat Petrol Pump, Pisoli, Pune 411060</p>-->
                                <p class='text-left'><?php echo config_item('company_address') ?></p>
                            </div>
                        </div>
                    </th>
                </tr>
            </thead>
            <tbody>
                <tr>
                    <td class="bordered padded">
                        <?php  
                            $formattedDate = date('d-m-Y', strtotime($invoice_data->date));
                            $day   = date('d', strtotime($invoice_data->date));
                            $month = date('m', strtotime($invoice_data->date));
                        ?>
                        <p><strong>Invoice No.:</strong> ABK/<?= $day.'/'.$month.'/'.$invoice_data->orderid; ?></p>
                    </td>
                    <td class="bordered padded text-right">
                        <p><strong>Date: </strong><?= $formattedDate ?></p>
                    </td>
                </tr>
                <tr>
                    <td class="bordered padded" style="width: 50%;">
                        <p><strong>Bill By :</strong></p>
                        <p class='m-0'><?= $fran_data->business_name ?></p>
                        <p class='m-0'><?= $fran_data->phone ?></p>
                        <p class='m-0'><?= $fran_data->address ?>,<br><?= $fran_data->country .' - '.$fran_data->pincode ?>.</p>
                        <p><strong>GSTIN:</strong> <?= $fran_data->gstin ?></p>
                        
                    </td>
                    <td class="bordered padded" style="width: 50%;">
                        <p><strong>Bill To :</strong></p>
                        <p class='m-0'><?= $user_data->name ?></p>
                        <p class='m-0'><?= $user_data->phone ?></p>
                        <p class="m-0"><?= $user_data->country ?>, <?= $user_data->state ?>, </p>
                        <p class="m-0"><?= $user_data->city ?>, </p>
                        <p class="m-0"><?= $user_data->address ?> .</p>
                    </td>
                </tr>
                <tr>
                    <td colspan="2" class="bordered padded">
                        <table class="table table-bordered">
                            <thead>
                                <tr>
                                    <th>Sr.No.</th>
                                    <th>Material Description</th>
                                    <!--<th>HSN</th>-->
                                    <th>Price</th>
                                    <th>Qty</th>
                                    <th>Total</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php 
                                    $data       = unserialize($result->stock_data);
                                    $price      = unserialize($result->stock_data_price); 
                                    $i = 0;
                                    foreach ($data as $key => $val){
                                        $i++;
                                        $total    += ($price[$key] * $val);
                                        $prod_data = $this->db_model->select_multi('*', 'product', array('id' => $key));
                                ?>
                                <tr>
                                    <td><?= $i ?></td>
                                    <td><?= $prod_data->prod_name ?></td>
                                    <!--<td><?= $prod_data->hsn_code ?></td>-->
                                    <td class="text-right"><?php echo config_item('currency') . number_format($price[$key], 2) ?></td>
                                    <td class="text-center"><?php echo $val ?></td>
                                    <td class="text-right"><?php echo config_item('currency') . number_format($price[$key] * $val, 2) ?></td>
                                </tr>
                                <?php } ?>
                                <tr>
                                    <td colspan="4" class="text-right"><strong>Grand Total</strong></td>
                                    <td><strong><?= config_item('currency') . number_format($total, 2) ?></strong></td>
                                </tr>
                                <tr>
                                    <td colspan="5" class="text-right"><em>INVOICE VALUE IN WORDS: <?= $this->db_model->amount_inword($total) ?> Only</em></td>
                                </tr>
                            </tbody>
                        </table>
                    </td>
                </tr>
                <tr>
                    <td colspan="2" class="bordered padded text-center">
                        <p><em>This is a computer-generated document hence no signature required</em></p>
                    </td>
                </tr>
            </tbody>
        </table>
        </div>
        <div class="card-footer">
              <a target="_blank" href="<?php echo site_url('franchisee/print_fran_invoice/' . $this->uri->segment(3)) ?>"
                       class="btn btn-success btn-sm">Print</a>
        </div>
    </div>
</div>