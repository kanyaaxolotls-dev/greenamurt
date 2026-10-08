<div class="col">
    <div class="card bg-secondary shadow">
        <div class="card-header bg-white border-0">
            <div class="row align-items-center">
                <div class="col-8">
                    <h3 class="mb-0">List of Staff</h3>
                </div>
                <div class="col-4 text-right">
                    <a href="<?php echo site_url('admin') ?>" class="btn btn-sm btn-primary">Home</a>
                </div>
            </div>
        </div>

        <div class="card-body">
            <div class="table-responsive">
                <table class="table align-items-center table-flush" id="example">
                    <thead class="thead-light">
                        <tr>
                            <th>ID</th>
                            <th>Username</th>
                            <th>Name</th>
                            <th>Email</th>
                            <th>Role</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                    <?php foreach ($data as $s): ?>
                        <tr>
                            <td><?= $s['id']; ?></td>
                            <td><?= $s['username']; ?></td>
                            <td><?= $s['name']; ?></td>
                            <td><?= $s['email']; ?></td>
                            <td><?= $this->db_model->select('name', 'tbl_roles', ['id' => $s['role_id']]); ?></td>
                            <td>
                                <button class="btn btn-info btn-sm" onclick='openEditModal(<?= json_encode($s) ?>)'>Edit</button>
                                <a href="<?= site_url('staff/remove/' . $s['id']) ?>" 
                                   onclick="return confirm('Are you sure want to delete this staff?')" 
                                   class="btn btn-danger btn-sm">Delete</a>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

<!-- Edit Modal -->
<div class="modal fade" id="editStaffModal" tabindex="-1" role="dialog" aria-labelledby="editModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-lg" role="document">
    <form method="post" action="<?= site_url('staff/update') ?>">
      <input type="hidden" name="id" id="edit_id">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title" id="editModalLabel">Edit Staff Details</h5>
          <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span>&times;</span>
          </button>
        </div>

        <div class="modal-body">
          <div class="row">
            <div class="col-md-4">
              <label>Username</label>
              <input type="text" name="username" id="edit_username" class="form-control" placeholder="Enter username">
            </div>
            <div class="col-md-4">
              <label>Name</label>
              <input type="text" name="name" id="edit_name" class="form-control" placeholder="Enter name">
            </div>
            <div class="col-md-4">
              <label>Email</label>
              <input type="email" name="email" id="edit_email" class="form-control" placeholder="Enter email">
            </div>
            <div class="col-md-6 mt-3">
              <label>Designation</label>
              <select name="role_id" id="edit_role_id" class="form-control">
                <?php foreach ($roles as $r): ?>
                  <option value="<?= $r['id'] ?>"><?= $r['name'] ?></option>
                <?php endforeach; ?>
              </select>
            </div>
            <div class="col-md-6 mt-3">
              <label>New Password (leave blank if unchanged)</label>
              <input type="password" name="password" class="form-control" placeholder="Enter new password">
            </div>
          </div>
        </div>

        <div class="modal-footer">
          <button type="submit" class="btn btn-success">Update</button>
          <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancel</button>
        </div>
      </div>
    </form>
  </div>
</div>

<script>
function openEditModal(staff) {
    $('#edit_id').val(staff.id);
    $('#edit_username').val(staff.username);
    $('#edit_name').val(staff.name);
    $('#edit_email').val(staff.email);
    $('#edit_role_id').val(staff.role_id);
    $('#editStaffModal').modal('show');
}
</script>
