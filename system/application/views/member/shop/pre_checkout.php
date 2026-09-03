<!-- Start page title -->
<div class="row">
    <div class="col-12">
        <div class="page-title-box d-sm-flex align-items-center justify-content-between">
            <h4 class="mb-sm-0 font-size-18">Cart (<?php echo $this->cart->total_items();?>)</h4>
            <div class="page-title-right">
                <ol class="breadcrumb m-0">
                    <li class="breadcrumb-item"><a href="javascript: void(0);">Ecommerce</a></li>
                    <li class="breadcrumb-item active">Cart</li>
                </ol>
            </div>
        </div>
    </div>
</div>
<!-- End page title -->

<div class="row">
    <div class="col-xl-9">
        <div class="card">
            <div class="card-body">
                <?php if ($this->cart->contents()) { ?>
                <form enctype="multipart/form-data" class="needs-validation" action="<?php echo base_url('cart/update')?>" method="POST">
                    <div class="table-responsive">
                        <table class="table align-middle mb-0">
                            <thead class="table-light">
                                <tr>
                                    <th>Product Photo</th>
                                    <th>Product Name</th>
                                    <th>MRP</th>
                                    <th>Price</th>
                                    <th>GST (%)</th>
                                    <th>Tax Amt.</th>
                                    <th>Quantity</th>
                                    <th colspan="2">Total</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach ($this->cart->contents() as $items): ?>
                                <tr>
                                    <td>
                                        <input type="hidden" name="rowid[]" value="<?php echo $items['rowid']; ?>"> 
                                        <img src="<?php echo base_url('uploads/' . ($items['image'] ? $items['image'] : 'default.jpg')) ?>" alt="product-img" class="avatar-md" />
                                    </td>
                                    <td><h5 class="font-size-14 text-truncate"><a class="text-dark"><?php echo $items['name']; ?></a></h5></td>
                                    <td><?php echo $this->cart->format_number($items['prod_price']); ?></td>
                                    <td><?php echo $this->cart->format_number($items['dealer_price']); ?></td>
                                    <td><?php echo $this->cart->format_number($items['gst']); ?></td>
                                    <td><?php echo $items['dealer_price'] * ($items['gst'] * $items['qty'] / 100); ?></td>
                                    <td><input type="text" class="form-control" name="qty[]" value="<?php echo $items['qty']; ?>" /></td>
                                    <td><?php echo $items['subtotal']; ?></td>
                                    <td><button type="submit" class="btn btn-danger btn-sm text-white"><i class="fas fa-joint font-size-14"></i></button></td>
                                </tr>
                                <?php endforeach; ?>
                            </tbody>
                        </table>
                    </div>
                </form>
                <?php } ?>
                <div class="row mt-4">
                    <div class="col-sm-4">
                        <a href="<?php echo site_url('cart/new-purchase') ?>" class="btn btn-secondary"><i class="mdi mdi-arrow-left me-1"></i> Continue Shopping</a>
                    </div>
                    <div class="col-sm-8">
                        <em class="text-muted text-capitalize">In Words: <?php echo $total; ?> Only</em>
                    </div>
                </div>
            </div>
        </div>
    </div>
    
    <!-- Order Summary -->
    <div class="col-xl-3">
        <div class="card">
            <div class="card-body">
                <h4 class="card-title mb-2">Your Order Amount</h4>
                <div class="table-responsive">
                    <table class="table mb-0">
                        <tbody>
                            <tr>
                                <h1 class="text-success"><span id="total-amount"><?php echo $this->cart->format_number($this->cart->total()); ?></span><small>/-</small></h1>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
        <!-- Payment and Coupon Section -->
        <div class="card">
            <div class="card-body">
                <h5 class="card-title mb-4">Payment Method</h5>
                <form action="<?php echo site_url('cart/checkout') ?>" method="post" name="razorpayform">
                    <div class=" mb-3">
                        <label class="form-check-label mb-2" for="userid">Userid [ <span id="spn_res" style="color: red;"><?= $this->db_model->select('name', 'member', array('id' => $this->session->user_id)) ?></span> ]</label>
                        <input class="form-control" oninput="get_user_name('#sponsor', '#spn_res')" type="text" name="userid" id="sponsor" value="<?= $this->session->user_id ?>" placeholder="Enter userid">
                    </div>
                    
                    <?php if($this->db_model->select('franchisee', 'global_setting', array('id' => 1)) == 1){ ?>
                    <!-- City Selection -- >
                    <div class="mb-3">
                        <label for="taluka-select" class="form-label">Select City</label>
                        <select class="form-control" id="taluka-select">
                            <option value="">Select City</option>
                            <?php foreach ($talukas as $t): ?>
                                <option value="<?= $t->taluka ?>"><?= $t->taluka ?></option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                    
                    <!-- Franchisee Selection -- >
                    <div class="mb-3">
                        <label for="franchisee" class="form-label">Select Franchisee</label>
                        <select class="form-control" name="franchisee_id" id="franchisee" required disabled>
                            <option value="">Select Franchisee</option>
                            <?php foreach ($franchisee as $f): ?>
                                <option value="<?= $f->id ?>" data-taluka="<?= $f->taluka ?>">
                                    <?= $f->name ?> (<?= $f->taluka ?>, <?= $f->district ?>, <?= $f->state ?>)
                                </option>
                            <?php endforeach; ?>
                        </select>
                    </div> -->
                    <?php } ?>
                    <!--
                    <div class="form-check mb-3">
                        <input class="form-check-input" type="radio" name="paymentmethod" value="wallet" checked>
                        <label class="form-check-label">Cash Wallet [ <span class="text-success"><?= $this->db_model->select('balance', 'wallet', array('userid' => $this->session->user_id)) ?></span> ]</label>
                    </div>-->
                    <div class="form-check mb-3">
                            <input class="form-check-input" type="radio" name="paymentmethod" value="product_wallet" checked>
                        <label class="form-check-label">Repurchase Wallet [ <span class="text-success"><?= $this->db_model->select('balance', 'product_wallet', array('userid' => $this->session->user_id)) ?></span> ]</label>
                    </div>
                    <input type="hidden" name="amount" id="hidden-total" value="<?php echo $this->cart->format_number($this->cart->total()); ?>" />
                    <button id="checkoutBtn" class="btn btn-success mt-1"><i class="mdi mdi-cart-arrow-right me-1"></i> Checkout</button>
                </form>
            </div>
        </div>
    </div>
</div>

<script>
    function get_user_name(id, result) {
        var myString = $(id).val();
        var id       =  myString.replace(/\D/g, "");
        $.get("<?php echo site_url('site/get_user_name/') ?>" + id, function (data) {
            $(result).html(data);
        });
    }
</script>

<script>
    document.addEventListener("DOMContentLoaded", function() {
        const talukaSelect = document.getElementById("taluka-select");
        const franchiseeSelect = document.getElementById("franchisee");

        const originalOptions = Array.from(franchiseeSelect.options).filter(option => option.value !== "");

        // Initially disable franchisee select
        franchiseeSelect.disabled = true;

        // Clear and hide franchisee options initially
        franchiseeSelect.innerHTML = '<option value="">Select Franchisee</option>';

        talukaSelect.addEventListener("change", function() {
            const selectedTaluka = this.value;
            franchiseeSelect.innerHTML = '<option value="">Select Franchisee</option>';

            if (selectedTaluka !== "") {
                franchiseeSelect.disabled = false;

                // Filter and append options that match selected taluka
                originalOptions.forEach(opt => {
                    if (opt.getAttribute("data-taluka") === selectedTaluka) {
                        franchiseeSelect.appendChild(opt.cloneNode(true));
                    }
                });
            } else {
                franchiseeSelect.disabled = true;
            }
        });
    });
</script>

