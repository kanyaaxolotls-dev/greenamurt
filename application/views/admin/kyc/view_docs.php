<div class="col">
    <div class="card">
        <!-- Card header -->
       <div class="card-header border-0">
    <h3 class="mb-4">Document Images</h3>
    <?php foreach ($docs as $doc) { ?>
        <span class="mb-0 d-inline-block">
            <?php if ($doc['kyc_status'] == 1): ?>
                <form action="<?php echo base_url('Users/approve_kyc'); ?>" method="POST" class="d-inline">
                    <input type="hidden" name="userid" value="<?php echo $doc['userid']; ?>">
                    <button type="submit" class="btn btn-sm btn-success">Approve</button>
                </form>
                <button type="button" class="btn btn-sm btn-danger d-inline" onclick="openRejectModal('<?php echo $doc['userid']; ?>')">Reject</button>

            <?php elseif ($doc['kyc_status'] == 2): ?>
                <button type="button" class="btn btn-sm btn-danger d-inline" onclick="openRejectModal('<?php echo $doc['userid']; ?>')">Reject</button>

            <?php elseif ($doc['kyc_status'] == 3): ?>
                <form action="<?php echo base_url('Users/approve_kyc'); ?>" method="POST" class="d-inline">
                    <input type="hidden" name="userid" value="<?php echo $doc['userid']; ?>">
                    <button type="submit" class="btn btn-sm btn-success">Approve</button>
                </form>
            <?php endif; ?>
        </span>
    <?php } ?>
</div>

        <!-- Light table -->
        <div class="card-body">
            <div class="row">
                <?php foreach ($docs as $doc) { ?>
                    <div class="col-lg-3 mb-4">
                        <div class="card">
                        <h3 class="card-title">Adhar front</h3>
                            <img class="card-img-top" src="<?php echo base_url('uploads/' . $doc['id_proof']); ?>" alt="Aadhar Card Back Image">
                            <div class="card-body">
                            </div>
                        </div>
                    </div>
                    <div class="col-lg-3 mb-4">
                        <div class="card">
                        <h3 class="card-title">Adhar back</h3>
                            <img class="card-img-top" src="<?php echo base_url('uploads/' . $doc['adhar_back']); ?>" alt="not available">
                            <div class="card-body">
                            </div>
                        </div>
                    </div>
                    <div class="col-lg-3 mb-4">
                        <div class="card">
                        <h3 class="card-title">Pan Card</h3>
                            <img class="card-img-top" src="<?php echo base_url('uploads/' . $doc['add_proof']); ?>" alt="Profile Picture">
                            <div class="card-body">
                            </div>
                        </div>
                    </div>
                    <div class="col-lg-3 mb-4">
                        <div class="card">
                        <h3 class="card-title">Photo</h3>
                            <img class="card-img-top" src="<?php echo base_url('uploads/' . $doc['prof_pic']); ?>" alt="not available">
                            <div class="card-body">
                            </div>
                        </div>
                    </div>
                    
                <?php } ?>
            </div>
        </div>
    </div>
</div>

<!-- Modal for Reject Reason -->

<div class="modal fade" id="rejectModal" tabindex="-1" role="dialog" aria-labelledby="rejectModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="rejectModalLabel">Rejection Reason</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <form id="rejectForm" action="<?php echo base_url('Users/reject_kyc'); ?>" method="POST">
                <div class="modal-body">
                    <input type="hidden" name="userid" id="rejectUserId">
                    <div class="form-group">
                        <label for="reason">Reason for rejection</label>
                        <textarea class="form-control" id="reason" name="reason" required></textarea>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                    <button type="submit" class="btn btn-danger">Submit</button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
  function openRejectModal(userid) {
    document.getElementById('rejectUserId').value = userid;
    $('#rejectModal').modal('show');
  }
</script>
