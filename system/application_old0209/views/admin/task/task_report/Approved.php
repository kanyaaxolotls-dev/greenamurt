
<!-- <script type="text/javascript">
$(document).ready(function() {
    $('#data-table').DataTable( {
        dom: 'Bfrtip',
        buttons: [
             'csv', 'excel', 'pdf', 'print'
        ]
    } );
} );
</script>   -->

<?php 
    // $this->db->order_by('date','DESC'); 
    $this->db->group_by('userid,proceed_date'); 
    $this->db->select('*');
    // $this->db->select('id,userid,date');
    $this->db->where('status','Approved');
    $data = $this->db->get('task_process')->result();
    ?>
     
<div class="col">

<div class="card">  
    <div class="card-header bg-white border-0"> 

        <div class="row align-items-center">
            <div class="col-8">
                      <h3 class="mb-0">Approved Report</h3>           


            <?php echo form_open('users/search_task') ?>
    </div>
            
            <div class="col-4 text-right"> 
            
                <a href="<?php echo site_url('admin')?>" class="btn btn-sm btn-primary">Home</a>
                <!-- <a target="_blank" href="<?php // echo //site_url('users/search_status') ?>" class="btn btn-danger btn-sm">Search</a>  -->
                <?php if (config_item('payment_api') !== "None") { ?>
                    <!-- <a href="<?php //echo site_url('income/payall-gateway') ?>" class="pull-right btn btn-success">Pay All Using Gateway</a>  -->
                <?php } ?>
            </div> 
        </div>
    </div>
	<!-- id	userid	proceed_date	date	image	status -->
      <div class="table-responsive">
       <table id="data-table" class="table align-items-center table-flush">
        <thead class="thead-light">
                <thead>
                <tr>
                    <th scope="col">id</th>
                    <th scope="col">User ID</th>
                    <th scope="col">Task Name</th>
             
                 
                    <th scope="col">proceed_date & Time</th>
                    <th scope="col">date</th>
                    <!-- <th scope="col">Admin (%)</th> -->
                   
                    <th scope="col">image</th>
                    <th scope="col">status</th>
                   
                    <!-- <th scope="col">Action</th>  -->
                </tr>
                </thead>
                <tbody>
                <?php
                $sn = 1;
                foreach ($data as $e) {
                    // Find User Id who dont have PAN
                    $tablexNOpan  = $this->db_model->select_multi('tax_no', 'member_profile', array('userid' => $e->userid));

                    ?>
                    <tr>
                    <td><?php echo $sn++; ?></td>

<td><?php echo config_item('ID_EXT') . $e->userid ?></td>
<td><?php echo $e->task_id ?></td>

<!--   <td><?php echo $taxNOpan->tax_no ?></td> -->
<td><?php echo $e->proceed_date ?></td>
<td><?php echo $e->complete_date ?></td>

<td><?php echo $e->image ?></td>
                       <!--   <td style="color: red"><?php 
                         // if (config_item('auto_debit')>0){ 
                         //        echo $amt_wallet=$total * config_item('auto_debit')/100;
                         //    }else{
                         //        echo $amt_wallet=0;
                         //    }
                         ?> </td> -->
                        <td><?php 
                                              echo $e->status 


                        ?></td>

                        <!-- <td><?php //echo $e->date ?></td> -->
                     <!--    <td style="font-size:12px;">

                            <?php
                            echo '<strong>Name:</strong> '. $this->db_model->select('name', 'member', array('id' => $e->userid)) . "<br/>";
                            $data = $this->db_model->select_multi('tax_no,bank_ac_no, bank_name, bank_ifsc, bank_branch, btc_address, tcc_address', 'member_profile', array('userid' => $e->userid));
                            echo $data->bank_name ? '<strong>Bank Name:</strong> ' . $data->bank_name . '<br/>' : '';
                            echo $data->bank_ac_no ? '<strong>A/C No:</strong> ' . $data->bank_ac_no . '<br/>' : '';
                            echo $data->bank_ifsc ? '<strong>IFSC:</strong> ' . $data->bank_ifsc . '<br/>' : '';
                            echo $data->bank_branch ? '<strong>Bank Branch:</strong> ' . $data->bank_branch . '<br/>' : '';
                            
                            echo $data->tax_no ? 'PAN:' .'<strong id="a">'. $data->tax_no . '</strong>'. '<br/>' : '';
                            
                            //echo $data->btc_address ? '<strong>BTC Add:</strong> ' . $data->btc_address . '<br/>' : '';
                            //echo $data->tcc_address ? '<strong>TCC Add:</strong> ' . $data->tcc_address . '<br/>' : '';
                            ?>
                        </td> -->
                  
                       
                    </tr>
                <?php } ?>
                </tbody>
</table>
</div>

<!-- <div class="card-footer">
<a href="<?php //echo site_url('income/view-earning') ?>" class="btn btn-sm btn-primary">&larr; Go Back</a>
</div> -->
</div></div>


