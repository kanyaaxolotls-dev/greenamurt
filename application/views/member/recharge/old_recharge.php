  <div class="col"> 
    <div class="card bg-secondary shadow">   
        <div class="card-header bg-white border-0"> 
                <div class="row align-items-center">
                    <div class="col-8">
                          <h3 class="mb-0"><?php echo $title ?></h3>
                    </div>
                <div class="col-4 text-right"> 
                     <a href="<?php echo site_url('member/index')?>" class="btn btn-sm btn-primary">Home</a>
                </div>
            </div>
        </div>

<div class="table-responsive">
    <table class="table align-items-center table-flush">
        <tr>
            <th>SN</th>
            <th>Service Type</th>
            <th>Recharge No</th>
            <th>Amount</th>
            <th>Area / Operator</th>
            <th>Date</th>
        </tr>
        <?php
        $sn = 1;
        foreach ($rcg as $e) {
            ?>
            <tr>
                <td><?php echo $sn++; ?></td>
                <td><?php echo $e->service_type; ?></td>
                <td><?php echo $e->recharge_no; ?></td>
                <td><?php echo $e->amount; ?></td>
                <td><?php echo $e->area; ?><br/>(<?php echo $e->operator; ?>)</td>
                <td><?php echo date('d/m/Y', $e->time); ?></td>
            </tr>
        <?php } ?>
    </table>
</div>
<div class="pull-right">
    <?php echo $this->pagination->create_links(); ?>
</div>
 
 </div>
</div>
</div><!-- end of panel-->
</div><!-- end of main panel-->