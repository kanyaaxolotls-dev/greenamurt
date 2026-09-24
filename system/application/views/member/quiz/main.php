<style>
.quiz-card{
    position:relative;
    overflow:hidden;
    border:none;
    border-radius:20px;
    background:#fff;
    box-shadow:0 20px 50px rgba(0,0,0,.08);
}

/* Card Content */

.card-body{
    position:relative;
    z-index:2;
    padding:55px;
}

/* Watermark Image */

.watermark{
    position:absolute;
    inset:0;

    width:75%;
    height:75%;

    margin:auto;

    object-fit:contain;

    opacity:.08;

    pointer-events:none;

    z-index:1;
}

/* Heading */

.card-body h3{
    font-weight:700;
    color:#2c3e50;
}

.card-body p.text-muted{
    font-size:16px;
}

/* Price */

.card-body h2{
    font-size:23px;
    font-weight:700;
}

/* QR Box */

.qr-container{
    background:#fff;
    padding:22px;
    border-radius:20px;
    display:inline-block;
    box-shadow:0 10px 30px rgba(0,0,0,.08);
}

.qr-container img{
    width:220px;
    border-radius:12px;
}

/* Paid Button */

.btn-paid{
    background:#18b76a;
    border:none;
    border-radius:12px;
    padding:14px 40px;
    font-size:20px;
    font-weight:600;
    box-shadow:0 12px 30px rgba(24,183,106,.35);
}

.btn-paid:hover{
    transform:translateY(-2px);
}

.text-danger{
    font-size:18px;
}

.btn-link{
    font-size:18px;
    text-decoration:none;
}
</style>

<div class="row pt-4">
    <div class="col-md-7 mx-auto">
        <div class="card quiz-card">
            <img
                src="<?= base_url('uploads/nadi_illustration.png'); ?>"
                class="watermark"
                alt="Nadi Vigyan Illustration">
        
            <div class="card-body text-center p-5">
                
                <?php 
                $is_package1 = (!isset($pkg_id) || $pkg_id == 1);
                ?>

                <?php if(!$payment && !$pending_payment && !$is_active): ?>
                    <?php if($is_package1): ?>
                        <h3 class="fw-bold mb-3">Nadi Vigyan Certification Quiz</h3>
                        <p class="text-muted">Unlock your dashboard by completing the official certification.</p>
                        <div class="mb-4">
                            <div class="text-muted">Official Certification Fee</div>
                            <h2 class="text-primary fw-bold mb-0">₹ <?php echo isset($fee) && $fee > 0 ? number_format($fee) : '8,900'; ?></h2>
                        </div>
                    <?php else: ?>
                        <h3 class="fw-bold mb-3">Account Activation</h3>
                        <p class="text-muted">Complete your payment to activate your account and access your dashboard.</p>
                        <div class="mb-4">
                            <div class="text-muted">Package Activation Fee (<?php echo isset($pkg_name) ? $pkg_name : 'Health Package 2'; ?>)</div>
                            <h2 class="text-primary fw-bold mb-0">₹ <?php echo isset($fee) && $fee > 0 ? number_format($fee) : '4,450'; ?></h2>
                        </div>
                    <?php endif; ?>

                    <!-- Scanner Message -->
                    <div id="payment_area">
                        <div class="qr-container mb-3">
                            <img src="<?php echo base_url('uploads/qr_scanner.jpeg'); ?>" style="width:200px; border-radius: 10px;">
                        </div>
                        <p class="text-danger fw-bold">Scan the QR code to pay & submit your payment proof.</p>
                        
                        <div class="mt-4">
                            <button class="btn btn-success btn-lg btn-paid shadow" onclick="$('#payment_area').hide(); $('#upload_form').fadeIn();">
                                <i class="fa fa-check-circle me-2"></i> I HAVE PAID
                            </button>
                        </div>
                        <div class="mt-3 pt-3 border-top">
                            <span class="text-muted">Or activate using E-Pin:</span>
                            <a href="<?php echo site_url('member/unused-epin'); ?>" class="btn btn-outline-success btn-sm ms-2">
                                <i class="fa fa-key me-1"></i> E-Pin Activation
                            </a>
                        </div>
                    </div>

                    <!-- Modern Upload Form -->
                    <form id="upload_form" style="display:none;" action="<?php echo site_url('member/submit_quiz_payment') ?>" method="post" enctype="multipart/form-data">
                        <h4 class="mb-3">Submit Payment Details</h4>
                        <div class="text-start mb-3">
                            <label class="form-label">Transaction ID / UTR No.</label>
                            <input type="text" name="txn_id" class="form-control form-control-lg" placeholder="Enter 12 digit UTR Number" required>
                        </div>
                        <div class="text-start mb-4">
                            <label class="form-label">Upload Payment Screenshot</label>
                            <input type="file" name="receipt" class="form-control" required>
                        </div>
                        <button type="submit" class="btn btn-primary btn-lg w-100">Submit for Approval</button>
                        <button type="button" class="btn btn-link btn-sm mt-2" onclick="$('#upload_form').hide(); $('#payment_area').show();">Go Back</button>
                    </form>

                <?php elseif($pending_payment && !$is_active): ?>
                    <div class="py-4">
                        <i class="fa fa-clock text-warning display-4 mb-3"></i>
                        <h4 class="fw-bold">Payment Under Review</h4>
                        <?php if($is_package1): ?>
                            <p class="text-muted">Your payment of ₹ <?php echo isset($fee) && $fee > 0 ? number_format($fee) : '8,900'; ?> is being verified by our team.<br>Once approved, you can start the quiz immediately.</p>
                        <?php else: ?>
                            <p class="text-muted">Your payment of ₹ <?php echo isset($fee) && $fee > 0 ? number_format($fee) : '4,450'; ?> is being verified by our team.<br>Once approved by admin, your account will be activated immediately.</p>
                        <?php endif; ?>
                        <a href="<?php echo site_url('member/unused-epin'); ?>" class="btn btn-success mt-3 me-2"><i class="fa fa-key me-1"></i> Activate with E-Pin</a>
                        <a href="<?php echo site_url('member'); ?>" class="btn btn-outline-primary mt-3">Back to Home</a>
                    </div>

                <?php elseif(!$is_package1 && ($is_active || $payment)): ?>
                    <div class="py-4">
                        <div class="display-3 mb-3 text-success"><i class="fa fa-check-circle"></i></div>
                        <h2 class="fw-bold text-success">Account Active!</h2>
                        <p class="h5 text-muted mb-4">Your account is fully activated. You can now access all dashboard features.</p>
                        <a href="<?php echo site_url('member'); ?>" class="btn btn-lg btn-success px-5 shadow rounded-pill">
                            <i class="fa fa-tachometer-alt me-2"></i> Go to Dashboard
                        </a>
                    </div>

                <?php elseif($is_package1 && $payment && !$result): ?>
                    <div class="py-4">
                        <i class="fa fa-unlock-alt text-success display-4 mb-3"></i>
                        <h3 class="fw-bold text-success">Access Granted!</h3>
                        <p class="mb-4">Your payment is verified. You are now ready to take the Certification Exam.</p>
                        <div class="d-flex justify-content-center align-items-center gap-3 flex-wrap">
                            <a href="<?php echo site_url('member/start_quiz') ?>" class="btn btn-lg btn-primary px-4 shadow">
                                <i class="fa fa-play-circle me-1"></i> START QUIZ NOW
                            </a>
                            <a href="<?php echo site_url('member/skip_quiz') ?>" class="btn btn-lg btn-warning px-4 shadow text-white" onclick="return confirm('Are you sure you want to skip the exam and activate your account directly?');">
                                <i class="fa fa-forward me-1"></i> SKIP EXAM
                            </a>
                        </div>
                    </div>

                <?php elseif($is_package1 && $result): ?>
                    <div class="py-4">
                        <div class="display-3 mb-3">🎉</div>
                        <h2 class="fw-bold">Congratulations!</h2>
                        <p class="h5 mb-4">You have successfully cleared the Nadi Vigyan Quiz.</p>
                        <div class="bg-light p-3 rounded mb-4 d-inline-block">
                            Your Score: <strong class="text-primary h4"><?php echo $result->score; ?> / 50</strong>
                        </div>
                        <br>
                        <a href="<?php echo site_url('member/certificate') ?>" target="_blank" class="btn btn-info btn-lg shadow rounded-pill px-4 me-2">
                            <i class="fa fa-download me-2"></i> Download Official Certificate
                        </a>
                        <a href="<?php echo site_url('member'); ?>" class="btn btn-outline-primary btn-lg shadow rounded-pill px-4">
                            Dashboard
                        </a>
                    </div>
                <?php endif; ?>

            </div>
        </div>
    </div>
</div>