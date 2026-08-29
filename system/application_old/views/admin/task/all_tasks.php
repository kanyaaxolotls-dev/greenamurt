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
    
    $this->db->where('status','Executed');
    $data = $this->db->get('task_process')->result();
    ?>
     
<div class="col">

<div class="card">  
    <div class="card-header bg-white border-0"> 
        <div class="row align-items-center">
            <div class="col-8">
                      <h3 class="mb-0">All Tasks</h3>
            </div>
            <div class="col-4 text-right"> 
                <a href="<?php echo site_url('admin')?>" class="btn btn-sm btn-primary">Home</a>
                <!-- <a target="_blank" href="<?php //echo site_url('income/printout-withdraw-list') ?>" class="btn btn-danger btn-sm">Print Payout List &rarr;</a>  -->
                <?php if (config_item('payment_api') !== "None") { ?>
                    <!-- <a href="<?php echo site_url('income/payall-gateway') ?>" class="pull-right btn btn-success">Pay All Using Gateway</a>  -->
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
                   
                    <th scope="col">Action</th> 
                </tr>
                </thead>
                <tbody>
                <?php
                $sn = 1;
                foreach ($data as $e) {
                    // Find User Id who dont have PAN
                    $tablexNOpan  = $this->db_model->select_multi('tax_no', 'member_profile', array('userid' => $e->userid));
                    $p=$this->db_model->select_multi('sponsor,position', 'member', array('id' =>$e->userid)); 
                    ?>
                    <tr>
                        <td><?php echo $sn++; ?></td>

                        <td><?php echo config_item('ID_EXT') . $e->userid ?></td>
                        <td><?php echo $e->task_id ?></td>

                        <td><?php echo $e->proceed_date ?></td>
                        <td><?php echo $e->complete_date ?></td>

                        <td><?php echo $e->image ?></td>
                      
                        <td><?php 
                                 echo $e->status;
                        ?>
                        </td>

                        <td>

                      <?php  echo form_open('users/approve');?>
                    <input type="hidden" name="uid" value="<?php echo $e->userid ?>">
            
                    <input type="hidden" name="sp_id" value="<?php echo $p->sponsor;?>">
                    <input type="hidden" name="pos_id" value="<?php echo $p->position;?>">

                    <input type="hidden" name="tid" value="<?php echo $e->task_id ?>">
                    <button type='submit' onclick='confirm("dO you want to approve?")' class="btn btn-success btn-sm">Approve</button>
                   <?php echo form_close();?>
                            <!-- <a data-toggle="modal" data-target="#myModal"
                               onclick="document.getElementById('payid').value='<?php echo $e->id ?>'"
                               class="btn btn-success btn-sm">Approve</a> -->
                            <a href="<?php echo site_url('users/reject/' . $e->id) ?>" class="btn btn-warning btn-sm" onclick="return confirm('Are you sure want to delete this payout ?')">Reject</a>
                          
                           </td>
                    </tr>
                <?php } ?>
                </tbody>
</table>
</div>

<!-- <div class="card-footer">
<a href="<?php //echo site_url('income/view-earning') ?>" class="btn btn-sm btn-primary">&larr; Go Back</a>
</div> -->
</div>


<div class="modal fade" id="myModal" data-toggle="modal" role="dialog">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <!-- <h5 class="modal-title" id="modal-title-default">Payout Transaction details</h5> -->
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">×</span>
                </button>
            </div>


            <div class="modal-body">
            <?php echo form_open('earning/delete_invest');?>
<input type="hidden" name="iamt" value="<?php echo $e['upgrade_amt'];?>">
<input type="hidden" name="id" value="<?php echo $e['userid'] ?>">
<input type="hidden" name="uid" value="<?php echo $e['id'];?>">

<button type='submit' onclick='confirm("Really Want To close Investment? Investment amount will added in Users wallet..")' class='btn btn-sm btn-danger'>Close investment?</button>
<?php echo form_close();?>
        </div>
    </div>
</div>
</div></div>