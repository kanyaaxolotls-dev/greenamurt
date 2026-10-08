  <div class="col">
    <div class="card bg-secondary shadow">  
        <div class="card-header bg-white border-0"> 
        <div class="row align-items-center">
            <div class="col-8">
                  <h3 class="mb-0">Search E-Pin / Value Key</h3>
            </div>
        <div class="col-4 text-right"> 
            <a href="<?php echo site_url('admin')?>" class="btn btn-sm btn-primary">Home</a>
        </div>
    </div>
</div>
<div class="card-body">
<?php echo form_open() ?>
<div class="row">
    <div class="col-md-4">
    <div class="form-group">
            <input placeholder="Enter User ID" class="form-control form-control-alternative form-control-alternative-alternative" name="uid">
    </div>
    </div> 
    
    <div class="col-md-4">
    <div class="form-group">
            <input class="form-control form-control-alternative form-control-alternative-alternative" name="epin" placeholder="Enter e-PIN">
    </div>
    </div>
    <div class="col-md-4">
    <div class="form-group">
            <input type="submit" class="btn btn-primary" value="Search" onclick="this.value='Searching..'">
    </div>
    </div>
</div>
<?php echo form_close() ?>
<div class="table-responsive">
    <table class="table align-items-center table-flush" id="example">
        <thead class="thead-light">

        <tr>
            <th scope="col">SN</th>
            <th scope="col">Epin</th>
            <th scope="col">Amount</th>
            <th scope="col">Issued To</th>
            <th scope="col">Transferred By</th>
            <th scope="col">Used By</th>
            <th scope="col">Used Date</th>
            <th scope="col">Actions</th>
        </tr>
        <?php
        $sn = 1;
        foreach ($epin as $e) { ?>
            <tr>
                <td><?php echo $sn++; ?></td>
                <td><?php echo $e['epin']; ?></td>
                <td><?php echo $e['amount']; ?></td>
                <td><?php echo $e['issue_to'] ? config_item('ID_EXT') . $e['issue_to'] : ''; ?></td>
                <td><?php echo $e['transfer_by'] ? config_item('ID_EXT') . $e['transfer_by'] : ''; ?></td>
                <td><?php echo $e['used_by'] ? config_item('ID_EXT') . $e['used_by'] : ''; ?></td>
                <td><?php echo $e['used_time']; ?></td>
                <td>
                    <a href="<?php echo site_url('admin/epin/edit/' . $e['id']); ?>"
                       ><img src="https://img.icons8.com/cotton/20/000000/edit.png"></a>
                    <a onclick="return confirm('Are you sure you want to delete this epin ?')"
                       href="<?php echo site_url('admin/epin/remove/' . $e['id']); ?>"><img src="https://img.icons8.com/color/20/000000/clear-symbol.png"></a>
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
