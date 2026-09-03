  <div class="col">
    <div class="card shadow">  
        <div class="card-header bg-white border-0"> 
            <div class="row align-items-center">
                    <div class="col-8">
                          <h3 class="mb-0">View Report</h3>
                    </div>
                <div class="col-4 text-right"> 
                    <a href="<?php echo site_url('income/view_earning')?>" class="btn btn-sm btn-primary">Home</a>
                </div> 
            </div> 
      </div>
 <div class="table-responsive">
  <table class="table align-items-center table-flush" id="example">
    <thead class="thead-light">
        <tr>
            <th scope="col">SN</th>
            <th scope="col">User ID</th>
            <th scope="col">Amount</th>
            <th scope="col">Tax amount</th>
            <th scope="col">Tax percent</th>
           
        </tr>
    </thead>
    <tbody>
        <?php
        $sn = 1;
        foreach ($orders as $e) {
            ?>
            <tr>
                <td><?php echo $sn++; ?></td> 
                <td><?php echo $e->id; ?></td> 
                <td><?php echo $e->amount; ?></td>  
                <td><?php echo $e->tax_amount; ?></td> 
                <td><?php echo $e->tax_percnt; ?></td> 
              
            </tr>
        <?php } ?>
    </tbody>
</table>

</div>
<div class="pull-right">
    <?php echo $this->pagination->create_links(); ?>
</div>
<div class="card-footer">
    <a href="<?php echo site_url('income/search-earning') ?>" class="btn btn-sm btn-primary">&larr; Go Back</a>
</div>
</div>