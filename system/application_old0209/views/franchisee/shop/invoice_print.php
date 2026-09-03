<?php
    $invoice_data = $this->db_model->select_multi('*', 'invoice', array('orderid' => $id));
    $fran_data    = $this->db_model->select_multi('*','franchisee',array('id' => $this->session->fran_id));
    $user_data    = $this->db_model->select_multi('*','member',array('id' => $invoice_data->userid));
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Invoice</title>
    <link href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">
    <style>
        .bordered {
            border: 1px solid black;
        }
        .padded {
            padding: 10px;
        }
        .text-right {
            text-align: right;
        }
    </style>
    <script>
        window.onload = function() {
            window.print();
        }
    </script>
</head>
<body>
    <div class="container my-5">
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
                                <p class='text-left text-white'><strong>
                                    CLIFE GLOBAL <br>
                                    GST: 29BVSPV1289E1ZR</strong><br>
                                    <?php echo config_item('company_address') ?>
                                </p>
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
                        <p><strong>Invoice No.:</strong> CLG/<?= $day.'/'.$month; ?></p>
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
                                    <th rowspan="2">Sr.No.</th>
                                    <th rowspan="2">Material Description</th>
                                    <th rowspan="2">HSN</th>
                                    <th rowspan="2">Price</th>
                                    <th rowspan="2">Qty</th>
                                    <th colspan="2">GST</th>
                                    <th rowspan="2">Total</th>
                                </tr>
                                <tr>
                                    <th>Rate</th>
                                    <th>Amount</th>
                                </tr>
                            </thead>
                            <tbody>
                            <?php
                                $i = 0;
                                $grandTotal = 0;
                                foreach($result as $invoice_data){
                                    $i++;
                                    foreach (unserialize($invoice_data->invoice_data) as $data => $val){
                                        $tax   = unserialize($invoice_data->invoice_data_tax);
                                        $qty   = unserialize($invoice_data->invoice_data_qty);
                                        $hsn   = $this->db_model->select_multi('*', 'product', array('prod_name' => $data))->hsn_code;
                        
                                        // Calculate GST
                                        $gstRate = $tax[$data];
                                        $subtotal = $val * $qty[$data];
                                        $gstAmount = ($subtotal * $gstRate) / 100;
                                        $totalPrice = $subtotal + $gstAmount;
                                        $grandTotal += $totalPrice;
                            ?>
                                    <tr>
                                        <td><?= $i ?></td>
                                        <td><?= $data ?></td>
                                        <td><?= $hsn ?></td>
                                        <td><?= number_format($val, 2) ?></td>
                                        <td><?= $qty[$data] ?></td>
                                        <td><?= number_format($gstRate, 2) ?>%</td>
                                        <td><?= number_format($gstAmount, 2) ?></td>
                                        <td><?= number_format($totalPrice, 2) ?></td>
                                    </tr>
                            <?php }} ?>
                                <tr>
                                    <td colspan="7" class="text-right"><strong>Grand Total</strong></td>
                                    <td><strong><?= config_item('currency') . number_format($grandTotal, 2) ?></strong></td>
                                </tr>
                                <tr>
                                    <td colspan="8" class="text-right"><em>INVOICE VALUE IN WORDS: <?= $this->db_model->amount_inword($grandTotal) ?> Only</em></td>
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
    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.5.2/dist/umd/popper.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
</body>
</html>
