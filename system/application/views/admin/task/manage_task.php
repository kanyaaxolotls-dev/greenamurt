<div class="col">
    <div class="card bg-secondary shadow">  
        <div class="card-header bg-white border-0"> 
        <div class="row align-items-center">
            <div class="col-8">
                  <h3 class="mb-0"><?php echo $title;?></h3>
            </div>
        <div class="col-4 text-right"> 
            <a href="<?php echo site_url('admin/index')?>" class="btn btn-sm btn-primary">Home</a>
        </div>
    </div>

<div class="card-body">

           <div class="table-responsive">
              <table class="table align-items-center table-flush">
                <thead class="thead-light">
                    <tr>
                        <th scope="col">SN</th>
                        <th scope="col">Task Name</th>
                        <th scope="col">Description</th>
                        <th scope="col">Category</th>
                        <th scope="col">Link</th>
                        <th scope="col">amount</th>
                        <th scope="col">Expiry date</th>
                        <th scope="col">Activation Date</th>
                        <th scope="col">Created date</th>
                        <th scope="col">Image </th>
                        <th scope="col">status </th>
                        <th scope="col">Actions</th>

                    </tr>
                    <?php
                    $sn = 1;
                    foreach ($prod as $e) { ?> 
                        <tr>
                            <td><?php echo $sn++; ?></td>
                            <td><?php echo $e['task_name']; ?></td>
                            <td><?php echo $e['description']; ?></td>
                            <td><?php echo $e['category']; ?></td>
                            <td><?php echo $e['link']; ?></td>
                            <td><?php echo $e['amount']; ?></td>
                            <td><?php echo $e['expiry_date']; ?></td>
                            <td><?php echo $e['activation_date']; ?></td>
                            <td><?php echo $e['created_date']; ?></td>
                            <td>
                                <img src="<?php echo $e['image'] ? base_url('uploads/' . $e['image']) : base_url('uploads/default.jpg'); ?>"
                                     class="img-thumbnail img-responsive" style="max-height: 100px"></td>
                          
                                     <td><?php echo $e['status']; ?></td>

                            <td>
                            
                                <!-- <a href="<?php //echo site_url('users/view_task/' . $e['id']); ?>"><img src="https://img.icons8.com/color/20/000000/visible.png"></a> -->
                                <a href="<?php echo site_url('users/edit_task/' . $e['id']); ?>"><img src="https://img.icons8.com/cotton/20/000000/edit.png"></a>
                                <a onclick="return confirm('Are you sure you want to delete this Product ?')"
                                   href="<?php echo site_url('users/remove_task/'. $e['id']); ?>"
                                   ><img src="https://img.icons8.com/color/20/000000/clear-symbol.png"></a>
                            </td>
                        </tr>
                    <?php } ?>
                </table>
                <div class="pull-right">
                    <?php echo $this->pagination->create_links(); ?>
                </div>
            </div>
</div>
</div>
</div> 