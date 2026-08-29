<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8"/>
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta content="width=device-width, initial-scale=1" name="viewport"/>
    <meta name="author" content="Exolim"/>
    <meta name="robots" content="noindex, nofollow">
    <title>Management Dashboard | <?php echo config_item('company_name') ?></title>
    <link rel="stylesheet" type="text/css"
          href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css">
    <link href="https://fonts.googleapis.com/css?family=Open+Sans:400,300,600,700&subset=all"
          rel="stylesheet"
          type="text/css"/>
    <style type="text/css">
        table, tr { border : 1px solid #ccc }
    </style>
</head>
<body onload="print()">
<p>&nbsp;</p> 
<div class="container-fluid">
    <div class="row table-responsive">
        <table class="table table-border" align="center" style="max-width: 800px">
            <tr style="background-color: #66a3d8">
                <td>
                    <img src="<?php echo base_url('uploads/logo.png'); ?>" width="200"/>
                </td>
                <td align="right"><p><strong><?php echo config_item('company_name') ?>, </strong><br>
                <?php echo config_item('company_address') ?><br>

                </p></td>
            </tr>
            <tr>
                    <h4 style="text-align: center;"><strong>INVOICE</strong></h4>
                
            </tr>
            
            <tr>

                <td style="padding-top: 5px;">
                   <!-- <strong>Bill By:</strong><br/>--><?php //echo nl2br($result->company_address) ?>
                    
                    <strong>Invoice No.</strong>: <?php echo $this->uri->segment(3) ?><br>
                    <strong>Invoice Date</strong>: <?php echo $this->db_model->select('date', 'invoice', array('orderid' => $this->uri->segment(3))); ?><br>
                     <strong><?php if (($total - $result->paid_amt) <= 0) {
                        echo '<h1 align="left" style="color: green;">PAID</h1>';
                    } else {
                        echo '<h2 align="left" style="color: red">UNPAID</h2>';
                    } ?></strong>
                    
                </td>
                <td style="padding-top: 5px;">
                
                    <strong>Billing & Shipping Address</strong><br/>Member Id: <?php echo $this->db_model->select('userid', 'invoice', array('orderid' => $this->uri->segment(3))); ?>
                </td>


            </tr>
            <tr>
                <td colspan="2">
                    <table class="table table-striped">
                        <tr>
                            <td>Item Name</td>
                            <td>Qty</td>
                            <td>Price</td>
                            
                            <td>GST%</td>
                            <td>Rate</td>
                            <td align="right" >Total</td>
                        </tr>
                        <?php
                        foreach ($result1 as $result) {
                           
                        
                        foreach (unserialize($result->invoice_data) as $data => $val):
                            $tax = unserialize($result->invoice_data_tax);
                            $qty = unserialize($result->invoice_data_qty);
                            $total = $val ;
                            if($tax[$data] != 0){ 
                               $gstAmount = ($total * $tax[$data]) / 100;
                            }
                            else{
                                $gstAmount = 0;
                            }
                            $totalPrice = $total + $gstAmount;
                            $gst = $tax[$data]/100;
                            $gst_price = $total * ($tax[$data] / 100);
                            ?>
                            <tr>
                                
                                <td><?php echo $data ?></td>
                                 <td><?php echo $qty[$data] ?></td>
                                <td><?php echo config_item('currency') . $total ?></td>
                                <td><?php echo intval($tax[$data]) ?></td>
                                <!-- <td><?php echo $tax[$data] ?></td> -->
                                <td><?php echo config_item('currency') .round($gst_price,2) ?></td>
                                <td align="right" ><?php echo config_item('currency') . (($totalPrice * $qty[$data])) ?></td>
                            </tr>
                        <?php endforeach; 
                    }?>
                        <tr>
                            <td align="right" colspan="6"><strong>Total
                                    Payable: </strong> <?php echo config_item('currency') . $this->db_model->sum('total_amt', 'invoice', array('orderid'=>$result->orderid)); ?></td>
                        </tr>
                        <tr>
                            <td align="right" colspan="6"><strong>Paid
                                    Amount: </strong> <?php echo config_item('currency') . $this->db_model->sum('total_amt', 'invoice', array('orderid'=>$result->orderid)); ?>
                            </td>
                        </tr>
                      <!--  <tr>
                            <td align="right" colspan="5"><strong>Due
                                    Balance: </strong> <?php echo config_item('currency') . (number_format($total - $result->paid_amt, 2)) ?>
                            </td>
                        </tr> --> 
                    </table>
                    * This is an electronically generated invoice, hence no further signature is required.
                </td>
            </tr>
        </table>
        <div align="center">
            <?php /* if (($total - $result->paid_amt) <= 0) {
                echo '<h1 align="center" style="color: green">PAID</h1>';
            } else {
                echo '<h2 align="center" style="color: red">NOT FULLY PAID</h2>';
            } */?>
        </div>
    </div>
</div>
</body>
</html>