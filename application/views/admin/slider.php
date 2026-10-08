<?php echo form_open_multipart(); ?>
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
</div> 
<div class="card-body">

    <div class="form-group row">
        <div class="col-sm-6">
            <label>Banner Title *</label>
            <input type="text" class="form-control" name="title">
        </div>
        <div class="col-sm-6">
            <label>Banner Description *</label>
            <input type="text" class="form-control" name="desc">
        </div>
        <div class="col-sm-6">
            <label>Position *</label>
            <select class="form-control" name="position">
                <option value="left">Left</option>
                <option value="right">Right</option>
                <option value="center">Center</option>
            </select>
        </div>
        <div class="col-sm-6">
            <label>Banner For</label>
            <select class="form-control" name="link1" id="dropdown" onchange="updateOptions()">
                <option value="">Selct Link Type</option>
                <option value="cat">Category</option>
                <option value="prod">Product</option>
            </select>
        </div>
        <div class="col-sm-12">
            <label>Select Option</label>
            <select id="selectOptions" class="form-control" name="link2">
                <option>Select an option</option>
            </select>
        </div>
        <div class="col-sm-12">
            <label>Image *</label>
            <input type="file" class="form-control" name="img" required accept="image/*">
        </div>
        <div class="col-sm-12"><br/>
            <input type="submit" class="btn btn-success" value="Add New Banner" onclick="this.value='Adding..'">
        </div>

    </div>

    <?php echo form_close() ?>
    </div>
   

    <div class="table-responsive">
    <table class="table table-striped table-bordered">
        <tr>
            <th>SN</th>
            <th>Banner Title</th>
            <th>Position</th>
            <th>Description</th>
            <th>Actions</th>
        </tr>
        <?php
        $sn = 1;
        foreach ($cat as $e) { ?>
            <tr>
                <td><?php echo $sn++; ?></td>
                <td><?php echo $e['title']; ?></td>
                <td><?php echo $e['side']; ?></td>
                <td><?php echo strip_tags($e['sdesc']); ?></td>
                <td>
                    <a onclick="return confirm('Are you sure you want to delete this Banner ?')" href="<?php echo site_url('admin/slider_remove/' . $e['id']); ?>" class="btn btn-danger btn-sm">Delete</a>
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

    <script>
        function updateOptions() {
            var selectedValue = document.getElementById("dropdown").value;
            var selectElement = document.getElementById("selectOptions");
            selectElement.innerHTML = '';
            if (selectedValue === "cat") {
                var options = [
                    <?php
                        foreach ($category as $e){
                            echo '"' . $e['cat_name'] . '",';
                        }
                        
                    ?>
                    ];
            } 
            else if (selectedValue === "prod") {
                var options = [
                    <?php
                        foreach ($product as $e2){
                            echo '"' . $e2['prod_name'] . '",';
                        }
                        
                    ?>
                    ];
            } 
            else {
                var options = ["Select Link Option First"];
            }
            options.forEach(function (optionText) {
                var option = document.createElement("option");
                option.text = optionText;
                selectElement.add(option);
            });
        }
    </script>


