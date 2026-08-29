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
                
                <?php if(!$payment && !$pending_payment): ?>
                    <h3 class="fw-bold mb-3">Nadi Vigyan Certification Quiz</h3>
                    <p class="text-muted">Unlock your dashboard by completing the official certification.</p>
                    
                    <!--<div class="h5 text-primary mb-4">Exam Fee: <strong>₹ 8900</strong></div>-->
                    <div class="mb-4">
                        <div class="text-muted">Official Certification Fee</div>
                        <h2 class="text-primary fw-bold mb-0">₹ 8,900</h2>
                    </div>
                    <!-- Scanner Message -->
                    <div id="payment_area">
                        <div class="qr-container mb-3">
                            <img src="<?php echo base_url('uploads/qr_scanner.jpeg'); ?>" style="width:200px; border-radius: 10px;">
                        </div>
                        <p class="text-danger fw-bold">Scan the QR code to pay & unlock your exam.</p>
                        
                        <div class="mt-4">
                            <button class="btn btn-success btn-lg btn-paid shadow" onclick="$('#payment_area').hide(); $('#upload_form').fadeIn();">
                                <i class="fa fa-check-circle me-2"></i> I HAVE PAID
                            </button>
                            <a href="<?php echo site_url('member'); ?>" class="btn btn-link text-secondary">Cancel</a>
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

                <?php elseif($pending_payment): ?>
                    <div class="py-4">
                        <i class="fa fa-clock text-warning display-4 mb-3"></i>
                        <h4 class="fw-bold">Payment Under Review</h4>
                        <p class="text-muted">Your payment of ₹ 8900 is being verified by our team.<br>Once approved, you can start the quiz immediately.</p>
                        <a href="<?php echo site_url('member'); ?>" class="btn btn-outline-primary mt-3">Back to Home</a>
                    </div>

                <?php elseif($payment && !$result): ?>
                    <div class="py-4">
                        <i class="fa fa-unlock-alt text-success display-4 mb-3"></i>
                        <h3 class="fw-bold text-success">Access Granted!</h3>
                        <p class="mb-4">Your payment is verified. You are now ready to take the Certification Exam.</p>
                        <a href="<?php echo site_url('member/start_quiz') ?>" class="btn btn-lg btn-primary px-5 shadow">START QUIZ NOW</a>
                    </div>

                <?php elseif($result): ?>
                    <div class="py-4">
                        <div class="display-3 mb-3">🎉</div>
                        <h2 class="fw-bold">Congratulations!</h2>
                        <p class="h5 mb-4">You have successfully cleared the Nadi Vigyan Quiz.</p>
                        <div class="bg-light p-3 rounded mb-4 d-inline-block">
                            Your Score: <strong class="text-primary h4"><?php echo $result->score; ?> / 50</strong>
                        </div>
                        <br>
                        <!--<a href="#" class="btn btn-info btn-lg shadow"><i class="fa fa-download"></i> Download Certificate</a>-->
                        <a href="<?php echo site_url('member/certificate') ?>" target="_blank" class="btn btn-info btn-lg shadow rounded-pill px-4">
                            <i class="fa fa-download me-2"></i> Download Official Certificate
                        </a>
                    </div>
                <?php endif; ?>

            </div>
        </div>
    </div>
</div>