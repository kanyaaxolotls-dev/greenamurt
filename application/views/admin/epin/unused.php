<div class="col">
    <div class="card bg-secondary shadow">  
        <div class="card-header bg-white border-0">  
        <div class="row align-items-center">
            <div class="col-8">
                  <h3 class="mb-0"><?php echo $title ?></h3>
            </div>
        <div class="col-4 text-right"> 
            <a href="<?php echo site_url('admin')?>" class="btn btn-sm btn-primary">Home</a>
        </div>
    </div>
</div> 
<div class="card-body">
    <?php
    // Calculate totals
    $total_epins_count = count($epin);  
    $total_amount_sum  = 0;
    
    foreach ($epin as $e) {
        $total_amount_sum += $e['amount'];
    }
    ?>
    
    <div class="row mb-3">
        <!-- Total Epins Card -->
        <div class="col-md-6">
            <div class="card text-white bg-secondary shadow">
                <div class="card-body">
                    <h5 class="card-title">Total Epins</h5>
                    <h3><?= $total_epins_count ?></h3>
                </div>
            </div>
        </div>
    
        <!-- Total Amount Card -->
        <div class="col-md-6">
            <div class="card text-white bg-secondary shadow">
                <div class="card-body">
                    <h5 class="card-title">Total Amount</h5>
                    <h3>₹<?= number_format($total_amount_sum, 2) ?></h3>
                </div>
            </div>
        </div>
    </div>

    <form id="bulkActionForm" method="post" action="<?php echo site_url('admin/process_epin_delete'); ?>">
        <input type="hidden" id="selectedIds" name="selected_ids" value="">
        <input type="hidden" id="status" name="status" value="">
        <button type="button" class="btn btn-danger mb-3" onclick="submitForm('Paid')">Delete selected epins</button>
        <table class="table align-items-center table-flush" id="example">
            <thead class="thead-light">
                <tr>
                    <th>
                        <input type="checkbox" id="selectAllCheckbox">
                    </th>
                    <th scope="col">SN</th>
                    <th scope="col">Epin</th>
                    <th scope="col">Amount</th>
                    <th scope="col">Issue To</th>
                    <th scope="col">Date</th>
                    <th scope="col">Type</th>
                    <th scope="col">Actions</th>
                </tr>
            </thead>
            <?php  $sn = 1; foreach ($epin as $e) { ?>
            <tr>
                <td>
                    <input type="checkbox" class="rowCheckbox" value="<?php echo $e['id']; ?>">
                </td>
                <td><?php echo $sn++; ?></td>
                <td><?php echo $e['epin']; ?></td>
                <td><?php echo $e['amount']; ?></td>
                <td><?php echo config_item('ID_EXT') . $e['issue_to']; ?></td>
                <td><?php echo $e['generate_time']; ?></td>
                <td><?php echo $e['type']; ?></td>
                <td>
                    <a class="btn btn-primary btn-sm" href="<?php echo site_url('admin/epin/edit/' . $e['id']); ?>">Edit</a>
                    <a class="btn btn-danger btn-sm" onclick="return confirm('Are you sure you want to delete this epin ?')" href="<?php echo site_url('admin/epin/remove/' . $e['id']); ?>">Delete</a>
                </td>
            </tr>
            <?php } ?>
        </table>
    </form>
</div>
<!--<div class="pull-right">-->
<!--    <?php echo $this->pagination->create_links(); ?>-->
<!--</div>-->
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




