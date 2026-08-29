<script type="text/javascript">
$(document).ready(function() {
    $('#data-table').DataTable( {
        dom: 'Bfrtip',
        buttons: [ 
             'csv', 'excel', 'pdf', 'print'
        ]
    } );


} ); 


</script>  

<?php 

      
    //$this->db->select_sum('cost');
    // $this->db->where('status', 'Processing');
    // $this->db->select('product_id')
    //      ->from('product_sale')
    // $data  = $this->db->get();
   // var_dump($data);die();
    ?>
 
     <?php if (config_item('payment_api') !== "None") { ?>
        <a href="<?php echo site_url('income/payall-gateway') ?>" class="pull-right btn btn-success">Pay
                    All Using Gateway &rarr;</a> 
            <?php } ?>
<div class="col">
        <div class="card bg-secondary shadow">  
            <div class="card-header bg-white border-0"> 
                <div class="row align-items-center">
                    <div class="col-8"> 
                          <h3 class="mb-0"><?php echo $title?></h3>
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

                <tr style="font-weight: bold;font-size: 12px;">
                    <th scope="col">S.N.</th>
                    <th scope="col">User ID</th>
                    <th scope="col">PAN No </th>
                    <th scope="col">Date</th>
                    <th scope="col">Amount  <?php echo config_item('currency')?></th>
                    <th scope="col">Tax(%)</th>
                    <th scope="col">Tax Amount</th>
                    <th scope="col">Status</th>
                    
                  <!--   <td>#</td>  -->
                </tr>
                </thead>
                <tbody style="font-size: 12px;">
                <?php
                $sn = 1;
                foreach ($data->result() as $e) {
                    // Find User Id who dont have PAN
                    $taxNOpan  = $this->db_model->select_multi('tax_no', 'member_profile', array('userid' => $e->userid));

                    ?>
                    <tr>
                        <td><?php echo $sn++; ?></td>
                        <td><?php $fullname  = $this->db_model->select_multi('name', 'member', array('id' => $e->userid));
                            echo $fullname->name;
                        ?></td>
                        <td><?php echo $taxNOpan->tax_no ?></td>
                        <td><?php echo $e->date ?></td>
                       
                        
                        <td><?php echo $e->amount ?></td>
                        <td style="color: green"><?php 
                        
                        if ($taxNOpan->tax_no=='N/A' or $taxNOpan->tax_no==''){
                            echo config_item('payout_tax2') .'%';   
                            $total= $e->amount * (1 - (0.01 * (config_item('payout_tax2') +  config_item('admin_charges'))));
                          
                        }else{
                            echo config_item('payout_tax') .'%';
                           $total= $e->amount * (1 - (0.01 * (config_item('payout_tax') +  config_item('admin_charges'))));
                        }

                        ?></td>
                        <td><?php 
                            $total_tax= $e->amount - $total;
                            echo $total_tax;
                        ?></td>

                         <td style="color: red"><?php echo $e->status;?></td>
                     
                       <!--  <td><a data-toggle="modal" data-target="#myModal"
                               onclick="document.getElementById('payid').value='<?php echo $e->id ?>'"
                               class="btn btn-primary btn-xs">Pay</a>
                            <a href="<?php echo site_url('income/hold/' . $e->id) ?>" class="btn btn-warning btn-xs" onclick="return confirm('Are you sure want to delete this payout ?')">Hold</a>
                            <a href="<?php echo site_url('income/remove/' . $e->id) ?>" class="btn btn-danger btn-xs"
                               onclick="return confirm('Are you sure want to delete this payout ?')">Delete</a>
                        </td> -->
                    </tr>


                <?php } ?>
                </tbody>
</table>
</div>
</div>
</div>       
</div>

