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
        <tr>
            <th scope="col">SN</th>
            <th scope="col">Name</th>
            <th scope="col">Username</th>
            <th scope="col">password</th>
            <th scope="col">Pick-up Centre id</th>
            <th scope="col">Store Name</th>
            <!--<th scope="col">Country</th>-->
            <!--<th scope="col">State</th>-->
            <th scope="col">eMail</th>
            <th scope="col">Phone</th>
            <!--<th scope="col">Address</th>-->
            <th scope="col">Status</th>
            <th scope="col">#</th>
        </tr>
    </thead>
        <?php
        $sn = 1;
        foreach ($fran as $e) { ?>
            <tr>
                <td><?php echo $sn++; ?></td>
                <td><?php echo $e['name']; ?></td>
                <td><?php echo $e['username']; ?></td>
                <td><?php echo $e['password']; ?></td>
                <td><?php echo $e['id']; ?></td>
                <td><?php echo $e['business_name']; ?></td>
                <!--<td><?php echo $e['country']; ?></td>-->
                <!--<td><?php echo $e['state']; ?></td>-->
                <td><?php echo $e['email']; ?></td>
                <td><?php echo $e['phone']; ?></td>
                <!--<td><?php echo $e['address']; ?></td>-->
                <td><?php echo $e['status']; ?></td>
                <td>
                    <a href="<?php echo site_url('adm-franchisee/edit-fran/' . $e['id']); ?>"
                       class="btn btn-info btn-sm">Edit</a>
                    <a onclick="return confirm('Are you sure you want to delete this epin ?')"
                       href="<?php echo site_url('adm-franchisee/remove-fran/' . $e['id']); ?>"
                       class="btn btn-danger btn-sm">Delete</a>
                </td>
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
