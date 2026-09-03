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
    <div class="py-4">
        <?php $url_redirect = $this->uri->segment(2) ?>
        <form action="<?= base_url('product/'.$url_redirect) ?>" method="post">
            <div class="row">
                <div class="col-md-4 col-sm-12">
                    <label for="exampleSelect">Select Product :</label>
                    <select class="form-control" name="type">
                        <option value="" disabled selected>Select Product</option>
                        <?php
                            if($type != null or $type != ''){
                                $pname = $this->db_model->select('prod_name','product',array('id' => $type));
                                echo "<option value=\"" . $type . "\" selected>" . $pname . "</option>";
                            }
                            $this->db->select('*');
                            $this->db->from('product');
                            $query  = $this->db->get();
                            $data2  = $query->result_array();
                            foreach($data2 as $tr){
                        ?>
                        <option value="<?= $tr['id'] ?>"><?= $tr['prod_name'] ?></option>
                        <?php } ?>
                    </select>
                </div>
                <div class="col-md-4 col-sm-6 col-6">
                    <label for="exampleSelect">Start Date :</label>
                    <input type="date" name="start_date" class="form-control" value="<?= $start_date ?>"> 
                </div>
                <div class="col-md-4 col-sm-6 col-6">
                    <label for="exampleSelect">End Date :</label>
                    <input type="date" name="end_date" class="form-control" value="<?= $end_date ?>"> 
                </div>
                <div class="col-12 mt-3">
                    <input type="submit" class="btn btn-success w-100"> 
                </div>
            </div>
        </form>
    </div>
    <div class="table-responsive">
    <form id="bulkActionForm" method="post" action="<?php echo site_url('product/process_multi_delivery'); ?>">
        <input type="hidden" id="selectedIds" name="selected_ids" value="">
        <input type="hidden" id="status" name="status" value="">
        <?php if($list_type == 'Processing'){ ?>
        <button type="button" class="btn btn-info mb-3" onclick="submitForm('Paid')">Deliver selected ids</button>
        <?php } ?>
        <table class="table align-items-center table-flush" id="example">
            <thead class="thead-light">
        <tr>
            <?php if($list_type == 'Processing'){ ?>
            <th>
                <input type="checkbox" id="selectAllCheckbox">
            </th>
            <?php } ?>
            <th scope="col">SN</th>
            <th scope="col">User ID</th>
            <th scope="col">Full Name</th>
            <th scope="col">Order id</th>
            <th scope="col">Cost</th>
            <th scope="col">Order Date</th>
            <th scope="col">Status</th>
            <th scope="col">Action</th>
        </tr>
    </thead> 
        <?php  
     
        $sn = 1;
        foreach ($orders as $e) { 
              $this->db->select('product_id')->from('product_sale')->where(array('id' => $e->id));
              $p_id = $this->db->get()->result();

          ?>
            <tr>
                <?php if($list_type == 'Processing'){ ?>
                <td>
                    <input type="checkbox" class="rowCheckbox" value="<?php echo $e->id; ?>">
                </td>
                <?php } ?>
                <td><?php echo $sn++; ?></td>
                <td><?php echo config_item('ID_EXT') . $e->userid; ?></td>
                <td><?php echo $this->db_model->select('name', 'member', array('id' => $e->userid)); ?></td>
                <td><a href="<?php echo site_url('product/view_order/' . $e->orderid); ?>">#<?php echo $e->orderid;?></a></td>
                <td><?php echo config_item('currency') . $this->db_model->sum('cost', 'product_sale', array('orderid' => $e->orderid)); ?></td>  
                <td><?php echo $e->date; ?></td> 
                <td><?php echo $e->status; ?></td>  
                <td>
                    <div class="dropdown">
                        <a class="btn btn-sm btn-icon-only text-light" href="#" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                          <i class="fas fa-ellipsis-v"></i>
                        </a>
                        <div class="dropdown-menu dropdown-menu-right dropdown-menu-arrow">
                           <?php if ($e->status !== "Completed") { ?>
                             <a class="dropdown-item" data-toggle="modal" data-target="#myModal"
                            onclick="document.getElementById('deliverid').value='<?php echo $e->orderid ?>'"
                           >Deliver</a>
                            <?php } ?>
                            <a class="dropdown-item" href="<?php echo site_url('product/view_order/' . $e->orderid); ?>"
                               >View</a>
                            <a class="dropdown-item" onclick="return confirm('Are you sure you want to delete this Order ?')"
                               href="<?php echo site_url('product/remove_order/' . $e->orderid); ?>" >Delete</a>

                        </div>
                    </div> 
                </td>
               
            </tr>
        <?php } ?>
    </table>
    </form>
</div>

<!-- Modal -->
<div class="modal fade" id="myModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="exampleModalLabel">Delivery Details</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
       <?php echo form_open('product/deliver') ?>
      <div class="modal-body">
       
                <label>Enter Delivery Detail (eg: Tracking No)</label>
                <input type="hidden" name="deliverid" value="" id="deliverid">
                <textarea class="form-control" name="tdetail"></textarea>
                   
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-sm btn-secondary" data-dismiss="modal">Close</button>
         <button type="submit" class="btn btn-sm btn-success">Deliver Now</button>
      </div>

      <?php echo form_close() ?>
    </div>
  </div>
</div>



</div>
</div>
</div>

<script>
    function submitForm(status) {
        const selectedIds = Array.from(document.querySelectorAll('.rowCheckbox:checked'))
            .map(checkbox => checkbox.value)
            .join(',');

        if (!selectedIds) {
            alert('Please select at least one ID.');
            return;
        }
        document.getElementById('selectedIds').value = selectedIds;
        document.getElementById('status').value = status;
        document.getElementById('bulkActionForm').submit();
    }
</script>
<script>
    document.addEventListener('DOMContentLoaded', function () {
        const selectAllCheckbox  = document.getElementById('selectAllCheckbox');
        const rowCheckboxes      = document.querySelectorAll('.rowCheckbox');
        const selectedIdsInput   = document.getElementById('selectedIds');

        selectAllCheckbox.addEventListener('change', function () {
            rowCheckboxes.forEach(checkbox => {
                checkbox.checked = selectAllCheckbox.checked;
            });
            updateSelectedIds();
        });

        rowCheckboxes.forEach(checkbox => {
            checkbox.addEventListener('change', function () {
                if (!this.checked) {
                    selectAllCheckbox.checked = false;
                }
                if ([...rowCheckboxes].every(cb => cb.checked)) {
                    selectAllCheckbox.checked = true;
                }
                updateSelectedIds();
            });
        });

        function updateSelectedIds() {
            const selectedIds = [...rowCheckboxes]
                .filter(checkbox => checkbox.checked)
                .map(checkbox => checkbox.value)
                .join(',');
            selectedIdsInput.value = selectedIds;
        }
    });
</script>








