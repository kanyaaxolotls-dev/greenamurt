<div class="col">
        <?php if ($this->session->flashdata('success')): ?>
        <div class="alert alert-success"><?= $this->session->flashdata('success') ?></div>
    <?php endif; ?>

    <?php if ($this->session->flashdata('error')): ?>
        <div class="alert alert-danger"><?= $this->session->flashdata('error') ?></div>
    <?php endif; ?>

    <div class="card bg-secondary shadow">
        <div class="card-header bg-white border-0">
            <div class="row align-items-center">
                <div class="col-8">
                    <h3 class="mb-0"><?php echo $title; ?></h3>
                </div>
                <div class="col-4 text-right">  
                    <a href="<?php echo site_url('site/rewards_achievers') ?>" class="btn btn-sm btn-warning">All</a>
                    <a href="<?php echo site_url('site/rewards_achievers/D') ?>" class="btn btn-sm btn-success">Delivered</a>
                    <a href="<?php echo site_url('site/rewards_achievers/P') ?>" class="btn btn-sm btn-danger">Pending</a>
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
                            <th>user id</th>
                            <th>Reward Name</th>
                            <th>Status</th>
                            <th>Date</th>
                            <th>Delivered Date</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                    <?php foreach ($data as $s): ?>
                        <tr>
                            <td><?= $s['id']; ?></td>
                            <td><?= $s['name']; ?></td>
                             <td><?= $s['userid']; ?></td>
                            <td><?= $s['reward_gift']; ?></td>
                            <td> <?php if ($s['status'] == 'Pending'): ?>
                                    <span class="btn btn-danger btn-sm"><?= $s['status']; ?></span>
                                <?php else: ?>
                                    <span class="btn btn-success btn-sm"><?= $s['status']; ?></span>
                                <?php endif; ?>
                            </td>
                            <td><?= date('d-m-Y', strtotime($s['date'])); ?></td>
                            <td>
                                <?php if ($s['status'] == 'Pending'): ?>
        NA
                                <?php else: ?>
                                    <?= date('d-m-Y', strtotime($s['paid_date'])); ?>
                                <?php endif; ?>
                            </td>

                            <td>
                                <?php if ($s['status'] == 'Pending'): ?>
                                    <button class="btn btn-primary btn-sm" onclick="deliverNow(<?= $s['id'] ?>)">Deliver Now</button>
                                <?php else: ?>
                                    <span class="btn btn-success btn-sm">Delivered</span>
                                <?php endif; ?>
                            </td>


                        </tr>
                    <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

<!-- Deliver Modal -->
<div class="modal fade" id="deliverModal" tabindex="-1" role="dialog">
  <div class="modal-dialog" role="document">
    <form method="post" action="<?= base_url('site/update_status') ?>">
      <input type="hidden" name="id" id="deliver_id">
      <input type="hidden" name="status" value="Delivered">

      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title">Confirm Delivery</h5>
          <button type="button" class="close" data-dismiss="modal"><span>&times;</span></button>
        </div>

        <div class="modal-body">
          <p>Are you sure you want to <b>deliver this reward?</b></p>
        </div>

        <div class="modal-footer">
          <button type="submit" class="btn btn-success">Yes, Deliver</button>
          <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancel</button>
        </div>
      </div>
    </form>
  </div>
</div>



<script>
function deliverNow(id) {
    $("#deliver_id").val(id);
    $("#deliverModal").modal("show");
}

</script>


