<div class="row">
    <div class="col-12">
        <div class="card">
            <div class="card-header">
                <h4 class="card-title">Payment Approvals / Activation Requests</h4>
            </div>
            <div class="card-body">
                <table class="table table-bordered table-striped" id="datatable">
                    <thead>
                        <tr>
                            <th>User ID</th>
                            <th>Member Name</th>
                            <th>Package</th>
                            <th>Transaction ID</th>
                            <th>Receipt</th>
                            <th>Date</th>
                            <th>Status</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach($requests as $r): 
                            $pkg_id = !empty($r->signup_package) ? $r->signup_package : (!empty($r->join_package) ? $r->join_package : 1);
                            $pkg_name = !empty($r->prod_name) ? $r->prod_name : ($pkg_id == 1 ? 'Health Package' : 'Health Package 2');
                            $pkg_price = !empty($r->prod_price) ? floatval($r->prod_price) : ($pkg_id == 1 ? 8900 : 4450);
                        ?>
                        <tr>
                            <td><strong><?php echo $r->userid; ?></strong></td>
                            <td><?php echo $r->name; ?><br><small class="text-muted"><?php echo $r->phone; ?></small></td>
                            <td>
                                <strong><?php echo $pkg_name; ?></strong> (₹ <?php echo number_format($pkg_price); ?>)<br>
                                <?php if($pkg_id == 1): ?>
                                    <span class="badge bg-info text-white"><i class="fa fa-graduation-cap"></i> Package 1 (Exam & Certificate)</span>
                                <?php else: ?>
                                    <span class="badge bg-success text-white"><i class="fa fa-bolt"></i> Package 2 (Direct Activation)</span>
                                <?php endif; ?>
                            </td>
                            <td><?php echo $r->txn_id; ?></td>
                            <td>
                                <a href="<?php echo base_url('uploads/'.$r->receipt_file); ?>" target="_blank">
                                    <img src="<?php echo base_url('uploads/'.$r->receipt_file); ?>" width="90" class="img-thumbnail">
                                </a>
                            </td>
                            <td><?php echo date('d-m-Y H:i', strtotime($r->created_at)); ?></td>
                            <td>
                                <span class="badge bg-<?php echo ($r->status=='Pending') ? 'warning' : ($r->status=='Approved' ? 'success' : 'danger'); ?>">
                                    <?php echo $r->status; ?>
                                </span>
                            </td>
                            <td>
                                <?php if($r->status == 'Pending'): ?>
                                    <a href="<?php echo site_url('admin/update_quiz_status/'.$r->id.'/Approved'); ?>" class="btn btn-sm btn-success" onclick="return confirm('Approve payment for User ID <?php echo $r->userid; ?>?');">Approve</a>
                                    <a href="<?php echo site_url('admin/update_quiz_status/'.$r->id.'/Rejected'); ?>" class="btn btn-sm btn-danger" onclick="return confirm('Reject payment for User ID <?php echo $r->userid; ?>?');">Reject</a>
                                <?php else: ?>
                                    <span class="text-muted">Completed</span>
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