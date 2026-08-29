 
 <div class="col"> 
    <div class="card shadow">   
        <div class="card-header bg-white border-0"> 
                <div class="row align-items-center">
                    <div class="col-9">
                          <h4 class="mb-0"><?php echo $title ?></h4>
                    </div>
                <div class="col-3 text-right"> 
                    <a href="<?php echo site_url('member/index')?>" class="btn btn-sm btn-primary">Home</a>
                </div>
            </div>
        </div>
 
    <div class="table-responsive">
        <table class="table align-items-center table-flush">
            <thead class="bg-info">
            <tr>
                <th>SN</th>
                <th>Ticket Subject</th>
                <th>Date</th>
                <th>Status</th>
                <th>#</th>
            </tr>
        </thead>
            <?php
            $sn = 1;
            $this->db->select('*')->from('ticket')->where(array('userid' =>$this->session->user_id));
            $data = $this->db->get()->result();
       
            foreach ($data as $e) {
              
             ?>
                <tr>
                    <td><?php echo $sn++; ?></td>
                    <td><?php echo $e->ticket_title; ?></td>
                    <td><?php echo $e->date; ?></td>
                    <td><?php echo $e->status; ?></td>
                    <td><a href="<?php echo site_url('ticket/view/' . $e->id) ?>" class="btn btn-sm btn-danger">View</a>
                    </td>
                </tr>
            <?php 
                } 
            ?>
        </table>
    </div>

    <div class="card-footer">
        <?php echo $this->pagination->create_links(); ?>
    </div>
</div>
</div>