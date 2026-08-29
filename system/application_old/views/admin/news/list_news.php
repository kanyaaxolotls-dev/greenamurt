<div class="col">
        <div class="card bg-secondary shadow">  
            <div class="card-header bg-white border-0">  
                <div class="row align-items-center">
                    <div class="col-8"> 
                          <h3 class="mb-0">List News</h3>
                    </div>
                    <div class="col-4 text-right">  
                     
                       <a href="<?php echo site_url('admin')?>" class="btn btn-sm btn-primary">Home</a>
                    </div> 
                </div>  
            </div>  
        <div class="card-body">
<div class="table-responsive">
        <table class="table align-items-center table-flush">

            <thead class="thead-light">  <tr>
            <th scope="col">ID</th>
            <th scope="col">Title</th>
            <th scope="col">Description</th>
            <!--<th scope="col">Photo</th>-->
            <th scope="col">Type</th>
            <th scope="col">Actions</th>
            <th scope="col"></th>  </tr>
            </thead>

        <?php foreach ($data as $s) { ?>
            <tr>
                <td><?php echo $s['id']; ?></td>
                <td style="width: 300px"><?php echo $s['news_title']; ?></td>
                <td style="width: 300px"><?php echo $s['news_details']; ?></td>
                <!--<td> <img src="http://localhost/MLM_BINGEN/login/uploads/<?php// echo $s['news_pic']; ?>" height="80px" height="100px"></td>-->
               

                <td><?php echo $s['news_type']; ?></td>
                <td>
                    <a href="<?php echo site_url('news/edit/' . $s['id']); ?>" class="btn btn-info btn-sm">Edit</a>
                 
                
                       <a onclick="return confirm('Are you sure want to delete this news ?')"
                       href="<?php echo site_url('news/delete/' . $s['id']); ?>"
                       class="btn btn-danger btn-sm">Delete</a>
                </td>
            </tr>
        <?php } ?>
    </table>
</div>
</div>
</div>

</div>
