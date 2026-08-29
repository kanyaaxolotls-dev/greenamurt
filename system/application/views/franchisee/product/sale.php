<div class="col">
    <div class="card bg-secondary shadow">
        <div class="card-header bg-white border-0">
            <div class="row align-items-center">
                <div class="col-8">
                    <h3 class="mb-0"><?php echo $title ?></h3>
                </div>
                <div class="col-4 text-right">
                    <a href="<?php echo site_url('franchisee')?>" class="btn btn-sm btn-primary">Home</a>
                </div>
            </div>
        </div>
        <div class="card-body">
            <?php echo form_open('franchisee/sale_product') ?>
            <div class="row">
                <div class="col-sm-5">
                    <div class="form-group">
                        <label>Product Name</label>
                        <select name="pname" id="pname" class="form-control form-control-alternative">
                            <?php
                            $this->db->select('product_id, available_qty')->where('franchisee_id', $this->session->fran_id)->where('available_qty >', 0);
                            $data = $this->db->get('franchisee_stock')->result();
                            foreach ($data as $e) {
                                $prod_name = $this->db_model->select('prod_name', 'product', array('id' => $e->product_id));
                                $dealer_price = $this->db_model->select('dealer_price', 'product', array('id' => $e->product_id));
                                echo '<option value="' . $e->product_id . '" data-price="' . $dealer_price . '">' . 
                                     $prod_name . 
                                     ' (Available Qty : ' . $e->available_qty . ') ' . 
                                     '[ Price : ' . $dealer_price . ' ]</option>';
                            }
                            ?>
                        </select>
                    </div>
                </div>
                <div class="col-sm-6">
                    <div class="form-group">
                        <br>
                        <button type="button" onclick="populate()" class="btn btn-primary">Fetch details</button>
                    </div>
                </div>
            </div>

            <div class="row" id="hidden" style="display: none">
                <div class="col-sm-5">
                    <div class="form-group">
                        <label>Member ID [ <span id="spn_res" style="color: red;"></span> ]</label>
                        <input oninput="get_user_name('#sponsor', '#spn_res')" id="sponsor" type="text" class="form-control form-control-alternative" name="userid" placeholder="Enter Member id" required>
                    </div>
                    <div class="form-group">
                        <label>Order Type</label>
                        <select name="order_type" class="form-control form-control-alternative" required>
                            <option value="" disabled selected>Select order type</option>
                            <option value="Activation">Activation</option>
                            <option value="Repurchase">Repurchase</option>
                        </select>
                    </div>
                </div>
            </div>

            <div class="row">
                <div class="col-8">
                    <div id="res"></div>
                </div>
            </div>

            <!-- Bill Preview Section -->
            <div class="card bg-secondary shadow mt-3">
                <div class="card-header bg-white border-0">
                    <h4 class="mb-0">Bill Preview</h4>
                </div>
                <div class="card-body table-responsive">
                    <table class="table table-bordered">
                        <thead>
                            <tr>
                                <th>Product Name</th>
                                <th>Quantity</th>
                                <th>Amount</th>
                                <th>Action</th>
                            </tr>
                        </thead>
                        <tbody id="bill-preview">
                            <!-- Product rows will be dynamically added here -->
                        </tbody>
                        <tfoot>
                            <tr>
                                <th colspan="2">Total</th>
                                <th id="total-amount">0.00</th>
                                <th></th>
                            </tr>
                        </tfoot>
                    </table>
                </div>
            </div>

            <!-- Hidden Inputs for Bill Data -->
            <input type="hidden" name="product_ids" id="product_ids">
            <input type="hidden" name="quantities" id="quantities">
            <input type="hidden" name="amounts" id="amounts">

            <div class="row">
                <div class="col-2">
                    <button class="btn btn-primary mt-3" name="submit" value="add">Sale</button>
                </div>
            </div>
            <?php echo form_close() ?>
        </div>
    </div>
</div>

<script>
    function get_user_name(id, result) {
        var myString = $(id).val();
        var id = myString.replace(/\D/g, "");
        $.get("<?php echo site_url('site/get_user_name/') ?>" + id, function (data) {
            $(result).html(data);
        });
    }

    function removeRow(id) {
        $(id).remove();
        updateTotalAmount();
    }

    function populate() {
        $("#hidden").show('slow');
        var prod_id = $("#pname").val();
        var prod_title = $("#pname option:selected").text();
        var price = $("#pname option:selected").data('price');

        // Add product row to the preview
        var rowHtml = '<tr id="row-' + prod_id + '">' +
                      '<td>' + prod_title + '</td>' +
                      '<td><input style="max-width: 60px" class="form-control" value="1" name="qty[]" oninput="updateRowTotal(this)" data-price="' + price + '"></td>' +
                      '<td class="text-right"><span class="row-total">' + price + '</span></td>' +
                      '<td><a href="javascript:;" onclick="removeRow(\'#row-' + prod_id + '\')" class="btn btn-sm glyphicon glyphicon-remove">remove</a></td>' +
                      '</tr>';

        $("#bill-preview").append(rowHtml);
        updateTotalAmount();
    }

    function updateRowTotal(element) {
        var row = $(element).closest('tr');
        var qty = $(element).val();
        var price = $(element).data('price');
        var total = qty * price;

        $(row).find('.row-total').text(total.toFixed(2));
        updateTotalAmount();
    }

    function updateTotalAmount() {
        var total = 0;
        var product_ids = [];
        var quantities = [];
        var amounts = [];

        $('#bill-preview tr').each(function () {
            var row = $(this);
            var qty = row.find('input[name="qty[]"]').val();
            var amount = row.find('.row-total').text();
            var product_id = row.attr('id').replace('row-', '');

            product_ids.push(product_id);
            quantities.push(qty);
            amounts.push(amount);

            total += parseFloat(amount) || 0;
        });

        $('#total-amount').text(total.toFixed(2));

        // Update hidden inputs with bill data
        $('#product_ids').val(product_ids.join(','));
        $('#quantities').val(quantities.join(','));
        $('#amounts').val(amounts.join(','));
    }
</script>
