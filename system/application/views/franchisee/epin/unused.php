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
    <div class="card-body"> 

 <div class="table-responsive">
        <table class="table align-items-center table-flush">
        <thead class="thead-light">
        <tr>
            <th>SN</th>
            <th>Epin</th>
            <th>Amount</th>
            <th>Issue To</th>
            <th>Date</th>
            <th>Type</th>
            <th>Actions</th>
        </tr>
    </thead>
        <?php
        $sn = 1;
        foreach ($epin as $e) { ?>
            <tr>
                <td><?php echo $sn++; ?></td>
                <td><?php echo $e['epin']; ?></td>
                <td><?php echo $e['amount']; ?></td>
                <td><?php echo config_item('ID_EXT') . $e['issue_to']; ?></td>
                <td><?php echo $e['generate_time']; ?></td>
                <td><?php echo $e['type']; ?></td>
                <td>
                    <a target="_blank" href="<?php echo site_url('site/register/epin/' . $e['epin']); ?>"
                       class="btn btn-success btn-xs">Add Member</a>
                </td>
            </tr>
        <?php } ?>
    </table>
</div>
</div><div class="card-footer">
    <?php echo $this->pagination->create_links(); ?>
</div>
</div>

</div>
</div>

</div></div>