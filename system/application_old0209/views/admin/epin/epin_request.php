<div class="col">
        <div class="card bg-secondary shadow">  
            <div class="card-header bg-white border-0"> 
                <div class="row align-items-center">
                    <div class="col-8"> 
                          <h3 class="mb-0"><?php echo $title ?></h3>
                    </div>
                    <div class="col-4 text-right">  
                     
                       <a href="<?php echo site_url('admin/index')?>" class="btn btn-sm btn-primary">Home</a>
                    </div> 
                </div>  
            </div>  
        <div class="card-body">
  <div class="table-responsive">
              <table class="table align-items-center table-flush" id="example">
            <thead class="thead-light">
            <th scope="col">SN</th>
            <th scope="col">User Id</th>
            <th scope="col">Amount</th>
            <th scope="col">Payment Proof</th>
            <th scope="col">Number Of Epins</th>
            <th scope="col">Action</th>
            <th scope="col">Date</th>
        </thead>
        <?php
        $sn = 1;
        $this->db->select('*')->from('epin_request')->where('status',0);
        $data = $this->db->get()->result();
        foreach ($data as $e) { ?>
            <tr>
                <td><?php echo $sn++; ?></td>
                <td><?php echo $e->userid; ?></td>
                <td><?php echo $e->amount; ?></td>
                <td><a href="<?= base_url('uploads/'.$e->file) ?>" target="_">View File</a></td>
                <td><?php echo $e->epins; ?></td>
                <td>
                    <form action="<?php echo base_url('admin/epinn_approve') ?>" method="post">
                        <input type="hidden"  value="<?php echo $e->id; ?>" name="id">
                        <input type="hidden"  value="<?php echo $e->userid; ?>" name="userid">
                        <input type="hidden"  value="<?php echo $e->amount; ?>" name="amount">
                        <input type="hidden"  value="<?php echo $e->epins; ?>" name="number">
                        <input type="hidden"  value="<?php echo 'Single Use'; ?>" name="type">
                        <input type="submit" class="btn btn-sm btn-success" value="Accept">
                    </form>
                </td>
                <td><?php echo $e->date; ?></td>
            </tr>
        <?php } ?>
    </table>
</div>
<div class="pull-right">
    <?php echo $this->pagination->create_links(); ?>
</div>
</div>
</div>

</div>
</div>