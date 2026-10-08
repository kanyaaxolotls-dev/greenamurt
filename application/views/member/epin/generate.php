<div class="row">
    <div class="col-lg-12"> 
        <div class="card">
            <div class="card-header">
                <h4 class="card-title"><?php echo $title ?></h4>
                <p class="card-title-desc">You can generate E-pin using Cash Or Repurchase wallet</p>
            </div>
            <!-- end card header -->
            <div class="card-body">
                <div class="row">
                    <form action="<?php echo site_url('member/generate-epin') ?>" method="POST">
                        <!--<p class="text-danger">10% Charges applicable</p>-->
                        <div class="col-lg-4 col-md-4">
                            <div class="mb-3">
                              <input type="text" value="<?php echo set_value('userid') ?>" class="form-control" placeholder="Enter Userid i.e. 1001" name="userid" onchange="get_user_name('#sponsor', '#spn_res')" required  id="sponsor">
                               <h6 id="spn_res" style="color: green;"></h6>
                            </div>
                        </div>
                        <div class="col-lg-4 col-md-4">
                            <div class="mb-3">
                                <!--<input type="text" class="form-control" value="" placeholder="Enter Amount / For free e-pin enter 0" name="amount" >-->
                                <select class="form-select" name="amount" required>
                                    <option value="" selected disabled>Select E-pin Amount</option>
                                    <?php
                                    	$this->db->select('*')->from('product')->where('status', 'Selling')->order_by('dealer_price', 'ASC');
    		                            $data = $this->db->get()->result();
    		                            foreach($data as $tr){
                                    ?>
                                    <option value="<?= $tr->dealer_price ?>"><?= $tr->dealer_price ?></option>
                                    <?php } ?>
                                </select>
                            </div>
                        </div>
                        <div class="col-lg-4 col-md-4">
                            <div class="mb-3">
                                <select class="form-select" name="number">
                                    <option value="" selected disabled>Select</option>
                                    <option value="1" selected>1</option>
                                    <option value="2">2</option>
                                    <option value="3">3</option>
                                    <option value="4">4</option>
                                    <option value="5">5</option>
                                    <option value="10">10</option>
                                    <option value="20">20</option>
                                    <option value="30">30</option>
                                    <option value="50">50</option>
                                    <option value="100">100</option>
                                    <option value="200">200</option>
                                </select>
                            </div>
                        </div>
                        <div class="col-lg-4 col-md-4">
                            <div class="form-check mb-3">
                                <input class="form-check-input" type="radio" name="paymentmethod" value="wallet" checked>
                                <label class="form-check-label">Cash Wallet [ <span class="text-success"><?= $this->db_model->select('balance', 'wallet', array('userid' => $this->session->user_id)) ?></span> ]</label>
                            </div>
                            <div class="form-check mb-3">
                                <input class="form-check-input" type="radio" name="paymentmethod" value="product_wallet">
                                <label class="form-check-label">Repurchase Wallet [ <span class="text-success"><?= $this->db_model->select('balance', 'product_wallet', array('userid' => $this->session->user_id)) ?></span> ]</label>
                            </div>
                        </div>
                        <!--<div class="col-lg-4 col-md-4">-->
                        <!--    <div class="mb-3">-->
                        <!--      <input type="password" class="form-control" placeholder="Enter Transaction Password" name="trans_password">-->
                        <!--    </div>-->
                        <!--</div>-->
                        <div class="col-lg-4 col-md-6">
                            <div class="mb-3">
                                <button class="btn btn-primary waves-effect btn-label waves-light" onclick="this.value='Please Wait..'" type="submit"><i class="bx bx-smile label-icon"></i> Create an E-Pins</button>
                                <a href="<?php echo site_url('member/unused-epin') ?>" class="btn btn-warning waves-effect btn-label waves-light" type="submit"><i class="bx bx-smile label-icon"></i> View All E-Pins</a>
                            </div>
                        </div>   
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<script type="text/javascript">
    function toogle_div(id1, id2) {
        if ($(id2).prop("checked") == true) {
            $(id1).hide('slow');
        } else {
            $(id1).show('slow');
        }
    }

    function show() {
        $('#form').hide('slow');
        $('#load').show('slow');
    }

    function get_user_name(id, result) {
        var id = $(id).val();
        $.get("<?php echo site_url('site/get_user_name/') ?>" + id, function (data) {
            $(result).html(data);
        });
    }
</script>