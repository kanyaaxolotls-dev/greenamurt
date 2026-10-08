<div class="card shadow">   
    <div class="card-header bg-white border-0"> 
            <div class="row align-items-center">
                <div class="col-11">
                      <h4 class="mb-0"><?php echo $title ?></h4>
                </div>
            <div class="col-1 text-right"> 
             
              <a href="<?php echo site_url('member/index')?>" class="btn btn-sm btn-primary">Home</a>
            </div>
        </div>
    </div><hr>

    <div class="table-responsive p-2">
        <table id="datatable" class="table align-middle table-nowrap table-check">
            <thead class="thead-dark">
                <tr>
                    <th>SN</th>
                    <th>Date</th>
                    <th>Amount</th>
                    <th>Type</th>
                    <th>Transcation Number</th>
                    <th>Status</th>
                </tr>
            </thead>
            <?php
                $sn = 1;
                $this->db->select('*')  ->from('deposite')->where('userid',$this->session->user_id);
                $data = $this->db->get()->result();
                foreach ($data as $e) { 
            ?>
            <tr>
                <td><?php echo $sn++; ?></td>
                <td><?php echo $e->date; ?></td>
                <td><?php echo $e->amount; ?></td>
                <td><?php echo $e->type; ?></td>
                <td><?php echo $e->tnumber; ?></td>
                <td><?php echo $e->status; ?></td>
            </tr>
            <?php }
             ?>
        </table>
    </div>

    <div class="card-footer">
        <?php echo $this->pagination->create_links(); ?>
    </div>
</div>
