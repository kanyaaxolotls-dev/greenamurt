<?php
$summary = isset($wallet_summary) ? $wallet_summary : $this->db_model->get_wallet_summary($this->session->user_id);
$min_withdraw = floatval(config_item('min_withdraw'));
$admin_fee_pct = floatval(config_item('admin_charges'));
$tds_pct = floatval(config_item('payout_tax'));
?>

<!-- Wallet Summary Cards -->
<div class="row mb-4">
    <div class="col-xl-3 col-md-6 col-12 mb-2">
        <div class="card card-h-100 shadow-sm border-0" style="background: linear-gradient(135deg, #1e3c72 0%, #2a5298 100%); color: white; border-radius: 10px;">
            <div class="card-body p-3">
                <span class="text-white-50 text-uppercase font-size-12 fw-bold">Total Earned</span>
                <h3 class="mt-2 mb-0 text-white font-weight-bold"><?= config_item('currency') . number_format($summary['total_earned'], 2); ?></h3>
                <small class="text-white-50">Accumulated Lifetime Earnings</small>
            </div>
        </div>
    </div>
    <div class="col-xl-3 col-md-6 col-12 mb-2">
        <div class="card card-h-100 shadow-sm border-0" style="background: linear-gradient(135deg, #11998e 0%, #38ef7d 100%); color: white; border-radius: 10px;">
            <div class="card-body p-3">
                <span class="text-white-50 text-uppercase font-size-12 fw-bold">Available Balance</span>
                <h3 class="mt-2 mb-0 text-white font-weight-bold"><?= config_item('currency') . number_format($summary['available_balance'], 2); ?></h3>
                <small class="text-white-50">Ready for Withdrawal / Transfer</small>
            </div>
        </div>
    </div>
    <div class="col-xl-3 col-md-6 col-12 mb-2">
        <div class="card card-h-100 shadow-sm border-0" style="background: linear-gradient(135deg, #f7971e 0%, #ffd200 100%); color: #333; border-radius: 10px;">
            <div class="card-body p-3">
                <span class="text-dark text-uppercase font-size-12 fw-bold" style="opacity: 0.7;">Pending / Held</span>
                <h3 class="mt-2 mb-0 text-dark font-weight-bold"><?= config_item('currency') . number_format($summary['pending_withdrawal'], 2); ?></h3>
                <small class="text-dark" style="opacity: 0.7;">Under Verification by Admin</small>
            </div>
        </div>
    </div>
    <div class="col-xl-3 col-md-6 col-12 mb-2">
        <div class="card card-h-100 shadow-sm border-0" style="background: linear-gradient(135deg, #8e2de2 0%, #4a00e0 100%); color: white; border-radius: 10px;">
            <div class="card-body p-3">
                <span class="text-white-50 text-uppercase font-size-12 fw-bold">Total Withdrawn</span>
                <h3 class="mt-2 mb-0 text-white font-weight-bold"><?= config_item('currency') . number_format($summary['total_withdrawn'], 2); ?></h3>
                <small class="text-white-50">Successfully Paid Out</small>
            </div>
        </div>
    </div>
</div>

<div class="row">
    <div class="col-lg-12"> 
        <div class="card shadow-sm border-0">
            <div class="card-header bg-white border-bottom">
                <h4 class="card-title mb-1"><?php echo $title ?></h4>
                <p class="card-title-desc text-muted mb-0">Submit a payout request from your available wallet balance.</p>
            </div>

            <div class="card-body">
                <div class="alert alert-info py-2 font-size-13 mb-4">
                    <i class="fas fa-info-circle me-1"></i>
                    <strong>Withdrawal Rules:</strong> Minimum withdrawal: <strong><?= config_item('currency') . number_format($min_withdraw, 2) ?></strong> | 
                    TDS: <strong><?= $tds_pct ?>%</strong> | 
                    Admin Fee: <strong><?= $admin_fee_pct ?>%</strong> | 
                    Total Deductions: <strong><?= ($tds_pct + $admin_fee_pct) ?>%</strong>
                </div>

                <form action="<?php echo site_url('wallet/withdraw_payouts') ?>" method="POST" id="withdrawForm">
                    <div class="row">
                        <div class="col-lg-4 col-md-6 mb-3">
                            <label class="form-label fw-bold">Withdrawal Amount (<?= config_item('currency') ?>)</label>
                            <input type="number" step="1" name="amount" id="withdraw_amt" required class="form-control" placeholder="Enter amount (Min <?= $min_withdraw ?>)" min="<?= $min_withdraw ?>" max="<?= $summary['available_balance'] ?>">
                            <small class="text-muted">Available: <?= config_item('currency') . number_format($summary['available_balance'], 2) ?></small>
                        </div>

                        <div class="col-lg-4 col-md-6 mb-3">
                            <label class="form-label fw-bold">Payout Destination</label>
                            <select class="form-control" name="pay_type">
                                <option value="other">Bank Account (Configured in KYC)</option>
                                <option value="upi">UPI ID</option>
                                <?php if (config_item('wallet_type') !== "No"){ ?>
                                    <option value="product_wallet">Self Product / Shopping Wallet</option>
                                <?php } ?> 
                            </select>
                        </div>

                        <div class="col-lg-4 col-md-12 mb-3">
                            <label class="form-label">&nbsp;</label>
                            <div>
                                <button class="btn btn-primary waves-effect waves-light w-100 py-2" type="submit" name="submit" <?= ($summary['available_balance'] < $min_withdraw) ? 'disabled' : '' ?>>
                                    <i class="bx bx-check-circle me-1"></i> Submit Withdrawal Request
                                </button>
                            </div>
                        </div>   
                    </div>

                    <!-- Live Breakdown Preview Box -->
                    <div class="row mt-2" id="breakdown_box" style="display: none;">
                        <div class="col-lg-8">
                            <div class="p-3 bg-light rounded border">
                                <h6 class="fw-bold mb-2">Estimated Payout Calculation:</h6>
                                <div class="d-flex justify-content-between font-size-13 mb-1">
                                    <span>Gross Requested Amount:</span>
                                    <span class="fw-bold" id="prev_gross">₹0.00</span>
                                </div>
                                <div class="d-flex justify-content-between font-size-13 mb-1 text-danger">
                                    <span>TDS (<?= $tds_pct ?>%):</span>
                                    <span id="prev_tds">- ₹0.00</span>
                                </div>
                                <div class="d-flex justify-content-between font-size-13 mb-1 text-danger">
                                    <span>Admin Fee (<?= $admin_fee_pct ?>%):</span>
                                    <span id="prev_admin">- ₹0.00</span>
                                </div>
                                <hr class="my-1">
                                <div class="d-flex justify-content-between font-size-14 text-success fw-bold">
                                    <span>Net Bank / UPI Transfer:</span>
                                    <span id="prev_net">₹0.00</span>
                                </div>
                            </div>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const amtInput = document.getElementById('withdraw_amt');
    const box = document.getElementById('breakdown_box');
    const tdsPct = <?= $tds_pct ?>;
    const adminPct = <?= $admin_fee_pct ?>;

    if (amtInput) {
        amtInput.addEventListener('input', function() {
            const val = parseFloat(this.value);
            if (val > 0) {
                const tds = (val * tdsPct) / 100.0;
                const admin = (val * adminPct) / 100.0;
                const net = val - tds - admin;

                document.getElementById('prev_gross').innerText = '₹' + val.toFixed(2);
                document.getElementById('prev_tds').innerText = '- ₹' + tds.toFixed(2);
                document.getElementById('prev_admin').innerText = '- ₹' + admin.toFixed(2);
                document.getElementById('prev_net').innerText = '₹' + net.toFixed(2);
                box.style.display = 'block';
            } else {
                box.style.display = 'none';
            }
        });
    }
});
</script>
