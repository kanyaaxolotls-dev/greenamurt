<div class="col"> 
          <div class="card bg-secondary shadow">
              <div class="card-header bg-white border-0"> 
              <div class="row align-items-center">
                <div class="col-lg-6">
                    <h3 class="mb-0">Recent Investment</h3>
                </div>
            </div>
        </div>
   <div class="card-body">

<div class="table-responsive">
    <table class="table table-striped table-bordered">
        <tr>
            <th>SN</th>
            <th>Package Name</th>
            <th>Invst Amount</th>
            <th>Date</th>
            <th>Status</th>
            <th>Actions</th>
        </tr>
        <?php
        $sn = 1;
        foreach ($result as $e) { ?>
            <tr>
                <td><?php echo $sn++; ?></td>
                <td><?php echo $this->db_model->select('pack_name', 'investment_pack', array('id' => $e->pack_id)); ?></td>
                <td><?php echo config_item('currency') . $e->amount; ?></td>
                <td><?php echo $e->date; ?></td>
                <td style="font-weight: bold; color: orange"><?php echo $e->status; ?></td>
                <td>
                    <a class="btn btn-info btn-xs" href="<?php echo site_url('investments/pdf_invoice/' . $e->id) ?>" target="_blank">Receipt</a>
                </td>
            </tr>
        <?php } ?>
    </table>
</div>
<div class="pull-right">
    <?php echo $this->pagination->create_links(); ?>
</div>

</div></div></div></div></div></div>