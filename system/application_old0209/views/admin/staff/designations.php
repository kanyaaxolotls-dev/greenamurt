<div class="col">
    <div class="card bg-secondary shadow">
        <div class="card-header bg-white border-0">
            <div class="row align-items-center">
                <div class="col-8">
                    <h3 class="mb-0">Manage Designation</h3>
                </div>
                <div class="col-4 text-right">
                    <a href="<?php echo site_url('admin') ?>" class="btn btn-sm btn-primary">Home</a>
                    <button class="btn btn-sm btn-success" onclick="openAddModal()">Add Designation</button>
                </div>
            </div>
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table class="table align-items-center table-flush" id="example">
                    <thead class="thead-light">
                        <tr>
                            <th>SN</th>
                            <th>Designation Name</th>
                            <th>Salary</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php 
                            $sn = 1; 
                            $role_name   = $this->db_model->select('role_id', 'admin', array('id' => $this->session->admin_id));
                            $tasks       = $this->db_model->select('tasks', 'tbl_roles', array('id' => $role_name));
                            foreach ($result as $e): 
                        ?>
                        <tr>
                            <td><?= $sn++ ?></td>
                            <td><?= $e->name ?></td>
                            <td><?= config_item('currency') . $e->payscale ?></td>
                            <td>
                                <?php if($e->id > 2 or $role_name == 1){ ?>
                                    <button class="btn btn-info btn-sm" onclick='openEditModal(<?= json_encode($e) ?>)'>Edit</button>
                                <?php } ?>
                                <?php if($e->id > 2 ){ ?>
                                <!--<button class="btn btn-danger btn-sm" onclick="confirmDelete(<?= $e->id ?>)">Delete</button>-->
                                <?php } ?>
                            </td>
                        </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

<!-- Modal -->
<div class="modal fade" id="designationModal" tabindex="-1" aria-hidden="true">
  <div class="modal-dialog modal-xl">
    <div class="modal-content bg-secondary shadow">
      <div class="modal-header">
        <h5 class="modal-title" id="modalTitle">Add Designation</h5>
        <button type="button" class="close" data-dismiss="modal">&times;</button>
      </div>
      <?php echo form_open('staff/designations/save', ['id' => 'designationForm']); ?>
      <div class="modal-body">
        <input type="hidden" name="edit_id" id="edit_id">

        <div class="row">
          <div class="col-sm-6">
            <label>Designation Name</label>
            <input type="text" class="form-control" name="des_name" id="des_name" required>
          </div>
          <div class="col-sm-6">
            <label>Payscale (In decimal)</label>
            <input type="text" class="form-control" name="payscale" id="payscale" required>
          </div>
        </div>

        <?php
        $role_name = $this->db_model->select('role_id', 'admin', array('id' => $this->session->admin_id));
        $tasks_str = $this->db_model->select('tasks', 'tbl_roles', array('id' => $role_name));
        $tasks = explode(',', $tasks_str); // Convert tasks string to array
        $menu = $this->db->order_by('position', 'asc')->get_where('tbl_task_manager', ['status' => 1])->result_array();
        ?>
        
        <div class="row mt-4">
          <div class="col-sm-12">
            <label>Select Menu Permissions</label>
            <div class="permission-tree pl-3">
              <?php
              foreach ($menu as $item):
                if ($item['child_of'] == 0 && in_array($item['id'], $tasks)):
                  $children = $this->db->get_where('tbl_task_manager', [
                    'child_of' => $item['id'],
                    'status' => 1
                  ])->result_array();
              ?>
              <div class="parent-item mb-2">
                <div class="custom-control custom-checkbox">
                  <input type="checkbox" class="custom-control-input parent-checkbox"
                         id="menu_<?= $item['id'] ?>" name="menu_permissions[]"
                         value="<?= $item['id'] ?>" data-child-group="child_group_<?= $item['id'] ?>">
                  <label class="custom-control-label font-weight-bold"
                         for="menu_<?= $item['id'] ?>"><?= $item['name'] ?></label>
                </div>
        
                <?php
                $hasVisibleChildren = false;
                foreach ($children as $child) {
                  if (in_array($child['id'], $tasks)) {
                    $hasVisibleChildren = true;
                    break;
                  }
                }
                ?>
        
                <?php if ($hasVisibleChildren): ?>
                <div class="child-items pl-4 mt-2" id="child_group_<?= $item['id'] ?>">
                  <?php foreach ($children as $child): ?>
                    <?php if (in_array($child['id'], $tasks)): ?>
                      <div class="custom-control custom-checkbox mb-2">
                        <input type="checkbox" class="custom-control-input child-checkbox"
                               id="menu_<?= $child['id'] ?>" name="menu_permissions[]"
                               value="<?= $child['id'] ?>" data-parent="menu_<?= $item['id'] ?>">
                        <label class="custom-control-label"
                               for="menu_<?= $child['id'] ?>"><?= $child['name'] ?></label>
                      </div>
                    <?php endif; ?>
                  <?php endforeach; ?>
                </div>
                <?php endif; ?>
              </div>
              <?php endif; endforeach; ?>
            </div>
          </div>
        </div>

      </div>
      <div class="modal-footer">
        <button type="submit" class="btn btn-success" id="modalSubmit">Save</button>
        <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
      </div>
      <?php echo form_close(); ?>
    </div>
  </div>
</div>

<!-- Scripts -->
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
function resetModal() {
  $('#edit_id').val('');
  $('#des_name').val('');
  $('#payscale').val('');
  $('input[type=checkbox]').prop('checked', false);
  $('#modalTitle').text('Add Designation');
  $('#modalSubmit').val('Create');
}

function openAddModal() {
  resetModal();
  $('#designationModal').modal('show');
}

function openEditModal(data) {
  resetModal();
  $('#edit_id').val(data.id);
  $('#des_name').val(data.name);
  $('#payscale').val(data.payscale);
  $('#modalTitle').text('Edit Designation');
  $('#modalSubmit').val('Update');

  let selectedTasks = data.tasks ? data.tasks.split(',') : [];
  selectedTasks.forEach(id => {
    $('#menu_' + id.trim()).prop('checked', true);
  });

  $('.child-checkbox:checked').each(function () {
    let parentId = $(this).data('parent');
    $('#' + parentId).prop('checked', true);
  });

  $('#designationModal').modal('show');
}

function confirmDelete(id) {
  Swal.fire({
    title: 'Are you sure?',
    text: "You want to delete this Designation!",
    icon: 'warning',
    showCancelButton: true,
    confirmButtonColor: '#d33',
    cancelButtonColor: '#6c757d',
    confirmButtonText: 'Yes, delete it!'
  }).then((result) => {
    if (result.isConfirmed) {
      window.location.href = "<?= site_url('staff/remove-des/') ?>" + id;
    }
  });
}

// Checkbox dependencies
$('.parent-checkbox').change(function () {
  var group = $(this).data('child-group');
  $('#' + group + ' .child-checkbox').prop('checked', this.checked);
});

$('.child-checkbox').change(function () {
  var parentId = $(this).data('parent');
  var parent = $('#' + parentId);
  var group = parent.data('child-group');
  var all = $('#' + group + ' .child-checkbox');
  var checked = $('#' + group + ' .child-checkbox:checked');
  parent.prop('checked', checked.length > 0);
});
</script>
