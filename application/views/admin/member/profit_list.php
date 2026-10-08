<div class="col">
          <div class="card">
            <!-- Card header -->
            <div class="card-header border-0">
              <h3 class="mb-0">Member List</h3>
            </div>
            <!-- Light table -->
            <div class="table-responsive">
              <table class="table align-items-center table-flush">
                <thead class="thead-light">
        <tr>
            <th>SN</th>
            <th>userid</th>
            <th>Investment Amount</th>
            <th>Profit Earned</th>
            <th>Date</th>
        </tr>
    </thead>
    <tbody class="list">
        <?php
        $sn = 1;
        if($members==false){?>

            <tr><td><h3 style="color: red;">No data found !</h3></td></tr>
        <?php } 
        foreach ($members as $e) { ?>
            <tr>
                <td><?php echo $sn++; ?></td>
                <td><?php echo $e['userid']; ?></td>
                <td><?php echo config_item('currency') .$this->db_model->sum('upgrade_amt', 'upgrad_level', array('userid' =>$e['userid'])); ?></td>
                <td><?php echo config_item('currency') . $e['amount'];  ?></td>
                <td><?php echo $e['date']; ?></td>
               
            </tr>
        <?php } ?>
        </tbody>
    </table>
</div>
</div>
<div class="card-footer">
     <a class="btn btn-primary btn-info btn-sm" href="<?php echo site_url('member/search-earning') ?>">Back</a>
    <?php echo $this->pagination->create_links(); ?>
</div>

