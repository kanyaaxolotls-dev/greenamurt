<div class=" bg-secondary shadow">   
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
         <thead class="thead-light">
        <tr>
            <th>SN</th>
            <th>userid</th>
            <th>Investment Amount</th>
            <th>Profit Earned</th>
            <th>Date</th>
        </tr>
    </thead>
        <?php
        $sn = 1;
        if($earning==false){?>

            <tr><td><h3 style="color: red;">No data found !</h3></td></tr>
        <?php } 
        foreach ($earning as $e) { ?>
            <tr>
                <td><?php echo $sn++; ?></td>
                <td><?php echo $e['userid']; ?></td>
                <td><?php echo config_item('currency') .$this->db_model->sum('upgrade_amt', 'upgrad_level', array('userid' => $this->session->user_id)); ?></td>
                <td><?php echo config_item('currency') . $e['amount'];  ?></td>
                <td><?php echo $e['date']; ?></td>
               
            </tr>
        <?php } ?>
    </table>
</div>
</div>
<div class="card-footer">
     <a class="btn btn-primary btn-info btn-sm" href="<?php echo site_url('member/search-earning') ?>">Back</a>
    <?php echo $this->pagination->create_links(); ?>
</div>
