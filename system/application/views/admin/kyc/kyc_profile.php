  <div class="col">
          <div class="card">
            <!-- Card header -->
            <div class="card-header border-0">
              <h3 class="mb-0">KYC Member List</h3>
            </div>
            <!-- Light table -->
            <div class="table-responsive">
              <table class="table align-items-center table-flush" id="example">
                  <?php if ($this->session->flashdata('success_message')): ?>
                        <div class="alert alert-success" id="flash-message" role="alert">
                            <?php echo $this->session->flashdata('success_message'); ?>
                        </div>
                    <?php endif; ?>
                    <script>
                        setTimeout(function() {
                            $('#flash-message').fadeOut('fast');
                        }, 2000);
                    </script>

                <thead class="thead-light">
                  <tr>
                    <th scope="col" class="sort" data-sort="name">id</th>
                    <th scope="col">Name</th>
                    <th scope="col" class="sort">Userid</th>
                    <th scope="col">Kyc Status</th>
                    <th scope="col">Action</th>
                  </tr>
                </thead>
               <tbody class="list">
    <?php foreach ($members as $e) { ?>
        <tr> 
    <td><?php echo $e['id']; ?></td>
    <td><?php echo $e['name']; ?></td>
    <td><?php echo $e['userid']; ?></td>
                
   <td style="color: <?php 
    switch ($e['kyc_status']) {
        case '1':
            echo 'blue';
            break;
        case '2':
            echo 'green'; 
            break;
        case '3':
            echo 'red'; 
            break;
        case '0':
            echo 'black'; 
            break;
        default:
            echo 'black'; 
    }
?>">
    <?php 
    switch ($e['kyc_status']) {
        case '1':
            echo 'InProcess';
            break;
        case '2':
            echo 'Verified';
            break;
        case '3':
            echo 'Rejected';
            break;
        case '0':
            echo 'Pending';
            break;
        default:
            echo 'Unknown';
    }
    ?>
</td>
<td>
        <a href="<?php echo base_url('Users/view_doc/') . $e['userid']; ?>" class="btn btn-sm btn-primary">View</a>
    </td>

   
</tr>

    <?php } ?>
</tbody>

              </table>
            </div>

            <!-- Card footer --> 
            <div class="card-footer py-2">
             <?php echo $this->pagination->create_links(); ?>
            </div>
          </div>
