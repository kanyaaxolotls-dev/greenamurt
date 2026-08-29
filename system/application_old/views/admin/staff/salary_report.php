<div class="col">
        <div class="card bg-secondary shadow">  
            <div class="card-header bg-white border-0"> 
                <div class="row align-items-center">
                    <div class="col-8"> 
                          <h3 class="mb-0">Staff Salary Report</h3>
                    </div>
                    <div class="col-4 text-right">  
                     
                       <a href="<?php echo site_url('admin')?>" class="btn btn-sm btn-primary">Home</a>
                    </div> 
                </div>  
            </div>  
        <div class="card-body">

  <div class="table-responsive">
              <table class="table align-items-center table-flush">
                <thead class="thead-light">
        <tr>
            <th scope="col">S.N.</th>
            <th scope="col">Staff Name</th> 
            <th scope="col">Salary</th>
            <th scope="col">Salary Month</th>
            <th scope="col">Pay Date</th>
            <th scope="col">Actions</th>
        </tr>
    </thead>
        <?php $sn = 1;
        foreach ($salary as $s) { ?>
            <tr>
                <td><?php echo $sn++; ?></td>
                <td><?php echo $this->db_model->select('name', 'staffs', array('id' => $s->staff_id)); ?></td>
                <td><?php echo config_item('currency') . $s->salary; ?></td>
                <td><?php echo $s->month; ?>/<?php echo $s->year; ?></td>
                <td><?php echo $s->paydate; ?></td>
                <td>
                    <a href="<?php echo site_url('staff/edit_salary/' . $s->id); ?>"
                       class="btn btn-info btn-sm">Edit</a>
                    <a onclick="return confirm('Are you sure want to delete this staff ?')"
                       href="<?php echo site_url('staff/remove_salary/' . $s->id); ?>" class="btn btn-danger btn-sm">Delete</a>
                </td>
            </tr>
        <?php } ?>
    </table>
    <?php echo $this->pagination->create_links() ?>
</div>

</div>
</div>
</div>