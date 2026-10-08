
<div class="card mt-5 bg-secondary shadow">
    <div class="card-header border-0">
        <div class="row align-items-center">
            <div class="col-10">
                <h4 class="mb-0"><?php echo $title ?></h4>
            </div>
            <div class="col-2">
                <a href="<?php echo site_url('franchisee/new-purchase') ?>" class="btn btn-sm btn-primary">Shop</a>
            </div>
        </div>
    </div>

    <div class="card-body">
        <?php if ($this->cart->contents()) { ?>
            <form enctype="multipart/form-data" class="needs-validation" action="<?php echo base_url('franchisee/update')?>" method="POST">
                <input type="hidden" name="order_id" value="<?php echo $order_id; ?>"> 
                <div class="table-responsive">
                    <table class="table align-items-center table-flush">
                        <thead class="thead-light">
                            <tr>
                                <th>QTY</th>
                                <th>Item Description</th>
                                <th style="text-align:right">Item Price</th>
                                <th style="text-align:right">GST</th>
                                <th style="text-align:right">GST Amount</th>
                                <th style="text-align:right">Sub-Total</th>
                                <th style="text-align:right">Action</th>
                            </tr>
                        </thead>
                        <?php foreach ($this->cart->contents() as $items): ?>
                            <?php echo form_hidden('rowid[]', $items['rowid']); ?>
                            <tr>
                                <td>
                                    <div class="d-flex justify-content-between">
                                        <button class="btn btn-sm btn-primary decrease-qty" data-rowid="<?php echo $items['rowid']; ?>"style="width:40px;">-</button>
                                        <input type="text" class="form-control qty-input mr-2 w-100" name="qty[]" value="<?php echo $items['qty']; ?>">
                                        <button class="btn btn-sm btn-primary increase-qty" data-rowid="<?php echo $items['rowid']; ?>"style="width:40px;">+</button>
                                    </div>
                                </td>
                                <td>
                                    <?php echo $items['name']; ?>
                                    <?php if ($this->cart->has_options($items['rowid']) == TRUE) : ?>
                                        <p>
                                            <?php foreach ($this->cart->product_options($items['rowid']) as $option_name => $option_value): ?>
                                                <strong><?php echo $option_name; ?>:</strong> <?php echo $option_value; ?><br/>
                                            <?php endforeach; ?>
                                        </p>
                                    <?php endif; ?>
                                </td>
                                <td style="text-align:right"><?php echo config_item('currency') . $this->cart->format_number($items['price']); ?></td>
                                <td style="text-align:right"><?php echo $items['gst'] . "%"; ?></td>
                                <td style="text-align:right">
                                    <?php
                                        $per = $items['price'] * ($items['gst'] / 100);
                                        echo config_item('currency') . $per;
                                    ?>
                                </td>
                                <td style="text-align:right"><?php echo config_item('currency') . $this->cart->format_number($items['subtotal']); ?></td>
                                <td style="text-align:right">
                                <a href="<?php echo base_url('shop/remove_item/'.$items['rowid']); ?>" class="btn btn-danger btn-sm">Remove</a>
                            </td>
                            </tr>
                        <?php endforeach; ?>
                        <tr>
                            <td colspan="6"></td>
                            <td style="text-align: right;"><strong>Total : </strong><?php echo config_item('currency') . $this->cart->format_number($this->cart->total()); ?></td>
                        </tr>
                    </table>
                    <hr>
                    </form>
                    <div class="container">
                        <div class="row justify-content-center">
                            <div class="col-md-6">
                                <div class="card ">
                                    <div class="card-body">
                                        <form enctype="multipart/form-data" class="needs-validation" action="<?php echo base_url('franchisee/checkout')?>" method="POST" novalidate>
                                            <div class="mb-3">
                                                <p class="text-dark h5 text-center">UPI ID: <span id="upiId">admin@ybl</span> <br> <a class="btn mt-2 btn-sm btn-success" onclick="copyUpiId()">Copy</a></p>
                                                <label for="pay_proof" class="form-label text-dark h5">Upload Proof of Payment Of <span class="text-danger h4"> Rs. <?= $this->cart->format_number($this->cart->total()) ?></span> </label>
                                                <input type="file" class="form-control border" name="img" id="pay_proof" accept=".png, .jpg, .jpeg" required>
                                                <div class="invalid-feedback">Please select a file.</div>
                                                <div class="col-sm-12 mt-2">
                                                    <label for="inputLevel" class="text-dark">Send request to <span class="text-danger">*</span></label>
                                                    <?php 
                                                        $my_level_data = $this->db_model->select('level', 'franchisee', array('id' => $this->session->fran_id));
                                                        $my_level      = !empty($my_level_data) ? $my_level_data : 0;
                                                    
                                                        $levels = [
                                                            0 => 'Admin',
                                                            1 => 'State',
                                                            2 => 'District',
                                                            3 => 'Taluka',
                                                            4 => 'Homeshopee'
                                                        ];
                                                    ?>
                                                    <select class="form-control" id="inputLevel" name="level" required>
                                                        <option value="" disabled selected>-- select one -- </option>
                                                        <option value="0">Admin</option>
                                                        <?php
                                                            if ($my_level == 0) {
                                                                foreach ($levels as $key => $label) {
                                                                    if ($key != 0) {
                                                                        echo "<option value=\"$key\">$label</option>";
                                                                    }
                                                                }
                                                            } 
                                                            // elseif ($my_level == 1) {
                                                            //     echo "<option value=\"0\">State</option>";
                                                            // } 
                                                            elseif ($my_level == 2) {
                                                                echo "<option value=\"1\">State</option>";
                                                            } elseif ($my_level == 3) {
                                                                echo "<option value=\"2\">District</option>";
                                                            } elseif ($my_level == 4) {
                                                                echo "<option value=\"3\">Taluka</option>";
                                                                // for ($i = 1; $i <= 3; $i++) {
                                                                //     echo "<option value=\"$i\">{$levels[$i]}</option>";
                                                                // }
                                                            }
                                                        ?>
                                                    </select>
                                                </div>
                                            </div>
                                            <input type="submit" id="send_request_btn" class="btn btn-primary" value="Request Now">
                                        </form>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            
        <?php } else { ?>
            <h3 align="center" style="color:red"> <i class="fa fa-frown-o" aria-hidden="true"></i> You have no item in your cart</h3>
            <a href="<?php echo site_url('franchisee/new-purchase') ?>" class="btn btn-success btn-block"><i class="fas fa-cart-arrow-down"></i> Shop More Products &rarr;</a>
        <?php } ?>
    </div>
</div>

<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>
<script>
    function copyUpiId() {
        var upiIdText  = document.getElementById("upiId");
        var textArea   = document.createElement("textarea");
        textArea.value = upiIdText.textContent.trim();
        document.body.appendChild(textArea);
        textArea.select();
        document.execCommand("copy");
        document.body.removeChild(textArea);
        alert("UPI ID copied to clipboard: " + textArea.value);
    }
</script>

<script type="text/javascript">
    $(document).ready(function() {
        $(".decrease-qty").click(function() {
            var inputField = $(this).siblings(".qty-input");
            var qty = parseInt(inputField.val());
            if (qty > 1) {
                qty--;
                inputField.val(qty);
                updateSubtotal($(this));
            }
        });
    
        $(".increase-qty").click(function() {
            var inputField = $(this).siblings(".qty-input");
            var qty = parseInt(inputField.val());
            qty++;
            inputField.val(qty);
            updateSubtotal($(this));
        });
    
        function updateSubtotal(button) {
            var row = button.closest("tr");
            var price = parseFloat(row.find(".item-price").text().replace(/[^\d.]/g, ''));
            var qty = parseInt(row.find(".qty-input").val());
            var gst = parseFloat(row.find(".item-gst").text());
            var subtotal = price * qty + (price * gst * qty / 100);
            row.find(".item-subtotal").text(subtotal.toFixed(2));
        }
    });
</script>
