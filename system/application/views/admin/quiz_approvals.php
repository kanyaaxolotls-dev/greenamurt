<div class="row">
    <div class="col-12">
        <div class="card">
            <div class="card-header">
                <h4 class="card-title">Pending Quiz Payments (₹ 8900)</h4>
            </div>
            <div class="card-body">
                <table class="table table-bordered table-striped" id="datatable">
                    <thead>
                        <tr>
                            <th>User ID</th>
                            <th>Member Name</th>
                            <th>Transaction ID</th>
                            <th>Receipt</th>
                            <th>Date</th>
                            <th>Status</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach($requests as $r): ?>
                        <tr>
                            <td><?php echo $r->userid; ?></td>
                            <td><?php echo $r->name; ?><br><small><?php echo $r->phone; ?></small></td>
                            <td><?php echo $r->txn_id; ?></td>
                            <td>
                                <a href="<?php echo base_url('uploads/'.$r->receipt_file); ?>" target="_blank">
                                    <img src="<?php echo base_url('uploads/'.$r->receipt_file); ?>" width="100">
                                </a>
                            </td>
                            <td><?php echo $r->created_at; ?></td>
                            <td>
                                <span class="badge bg-<?php echo ($r->status=='Pending') ? 'warning' : ($r->status=='Approved' ? 'success' : 'danger'); ?>">
                                    <?php echo $r->status; ?>
                                </span>
                            </td>
                            <td>
                                <?php if($r->status == 'Pending'): ?>
                                    <a href="<?php echo site_url('admin/update_quiz_status/'.$r->id.'/Approved'); ?>" class="btn btn-sm btn-success">Approve</a>
                                    <a href="<?php echo site_url('admin/update_quiz_status/'.$r->id.'/Rejected'); ?>" class="btn btn-sm btn-danger">Reject</a>
                                <?php else: ?>
                                    ---
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