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
    
    $this->db->order_by('date','DESC'); 
    $this->db->group_by('userid,date'); 
    $this->db->select_sum('amount');
    $this->db->select('id,userid,date,type,tid');
    $this->db->where('status', 'Un-Paid');
    $this->db->where('tid !=',NULL);
    $data = $this->db->get('withdraw_request')->result();
    ?>
     
<div class="col">

<div class="card">  
    <div class="card-header bg-white border-0"> 
        <div class="row align-items-center">
            <div class="col-8">
                      <h3 class="mb-0">Processing Report</h3>
            </div>
            <div class="col-4 text-right"> 
                <a href="<?php echo site_url('admin')?>" class="btn btn-sm btn-primary">Home</a>
                
            </div> 
        </div>
    </div>

      <div class="table-responsive">
       <table id="data-table" class="table align-items-center table-flush">
        <thead class="thead-light">
                <thead>
                <tr>
                    <th scope="col">S.N.</th>
                    <th scope="col">User ID</th>
                 
                    <th scope="col">Amount  <?php echo config_item('currency')?></th>
                    <th scope="col">Tax(%)</th>
                    <th scope="col">Admin (%)</th>
                   
                    <th scope="col">Net Payable</th>
                    <th scope="col">Date</th>
                    
                    <th scope="col">type</th>
                    <th scope="col">transaction id</th>
                </tr>
                </thead>
                <tbody>
                <?php
                $sn = 1;
                foreach ($earning as $e) {
                    // Find User Id who dont have PAN
                    $tablexNOpan  = $this->db_model->select_multi('tax_no', 'member_profile', array('userid' => $e->userid));

                    ?>
                    <tr>
                        <td><?php echo $sn++; ?></td>
                        <td><?php echo config_item('ID_EXT') . $e->userid ?></td>
                      <!--   <td><?php echo $taxNOpan->tax_no ?></td> -->
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
                         <td style="color: green"><?php echo config_item('admin_charges').'%'; ?> </td>
                       <!--   <td style="color: red"><?php 
                         // if (config_item('auto_debit')>0){ 
                         //        echo $amt_wallet=$total * config_item('auto_debit')/100;
                         //    }else{
                         //        echo $amt_wallet=0;
                         //    }
                         ?> </td> -->
                        <td><?php 
                            $adminchrg=($e->amount * config_item('admin_charges') / 100);
                            echo "<strong>".$netpay= $total-$amt_wallet."</strong>"; 
                            $netpay= $total-$amt_wallet
                        ?></td>
                        <td><?php echo $e->date ?></td>
                        <td><?php echo $e->type ?></td>
                        <td><?php echo $e->tid ?></td>
                </tr>
            <?php } ?> 
            </tbody>
</table>
</div>

<div class="card-footer">
<a href="<?php echo site_url('income/view-earning') ?>" class="btn btn-sm btn-primary">&larr; Go Back</a>
</div>
</div>


