            <div class="row">

                            <div class="col-12">
                                <div class="page-title-box d-sm-flex align-items-center justify-content-between">
                                    <h4 class="mb-sm-0 font-size-18">Welcome! <?php echo $this->db_model->select('name', 'member', array('id' => $this->session->user_id)); ?></h4>
                                    </li>
                                    <div class="page-title-right">
                                        <ol class="breadcrumb m-0">
                                            <li class="breadcrumb-item"><a href="javascript: void(0);">Dashboard</a></li>
                                            <li class="breadcrumb-item active"><?php echo $breadcrumb; ?></li>
                                            <li> 
                                        </ol>
                                    </div>

                                </div>
                            </div>
                        </div>
                    <div class="row">
                            <div class="col-xl-4 col-md-6">
                                <!-- card -->
                                <div class="card card-h-100">
                                    <!-- card body -->
                                    <div class="card-body">
                                        <div class="d-flex align-items-center">
                                            <div class="flex-grow-1">
                                                <span class="text-muted mb-3 lh-1 d-block text-truncate">Total Epins</span>
                                                <h4 class="mb-3"> 
                                                    <span class="counter-value" data-target="<?php echo $this->db_model->count_all('epin', array('issue_to' => $this->session->user_id)); ?>"></span>
                                                </h4>
                                            </div>
                                            <div class="flex-shrink-0 text-end dash-widget">
                                                <div id="mini-chart1" data-colors='["#1c84ee", "#33c38e"]' class="apex-charts"></div>
                                            </div>
                                        </div>
                                    </div><!-- end card body -->
                                </div><!-- end card -->
                            </div><!-- end col -->
        
                            <div class="col-xl-4 col-md-6">
                                <!-- card -->
                                <div class="card card-h-100">
                                    <!-- card body -->
                                    <div class="card-body">
                                        <div class="d-flex align-items-center">
                                            <div class="flex-grow-1">
                                                <span class="text-muted mb-3 lh-1 d-block text-truncate">Active Epins</span>
                                                <h4 class="mb-3">
                                                     
                                                    <span class="counter-value" data-target="<?php echo $this->db_model->count_all('epin', array('issue_to' => $this->session->user_id,'status' => 'Un-used')); ?>">
                                                    </span>
                                                </h4>
                                            </div>
                                            <div class="flex-shrink-0 text-end dash-widget">
                                                <div id="mini-chart2" data-colors='["#1c84ee", "#33c38e"]' class="apex-charts"></div>
                                            </div>
                                        </div>
                                    </div><!-- end card body -->
                                </div><!-- end card -->
                            </div><!-- end col--> 
        
                            <div class="col-xl-4 col-md-12">
                                <!-- card -->
                                <div class="card card-h-100">
                                    <!-- card body -->
                                    <div class="card-body"> 
                                        <div class="d-flex align-items-center">
                                            <div class="flex-grow-1">
                                                <span class="text-muted mb-3 lh-1 d-block text-truncate">Used Epins</span>
                                                <h4 class="mb-3">
                                                     <span class="counter-value" data-target="<?php echo $this->db_model->count_all('epin', array('issue_to' => $this->session->user_id,'status' => 'Used')); ?>"></span>
                                                </h4>
                                            </div>
                                            <div class="flex-shrink-0 text-end dash-widget">
                                                <div id="mini-chart3" data-colors='["#1c84ee", "#33c38e"]' class="apex-charts"></div>
                                            </div>
                                        </div> 
                                    </div><!-- end card body -->
                                </div><!-- end card -->
                            </div><!-- end col -->

                </div><!-- end row-->
                
                <div class="row p-2">
                    <div class="card">
                            <div class="row bg-light  p-3 ">
                                <div class="col-sm-12">
                                    <h4 class="card-title ">Send Epin Request</h4>
                                </div>
                            </div>
                    <form action="<?php echo site_url('member/epin_request')?>" method="post" class="p-4" enctype="multipart/form-data">
                      

            <div class="row">
                <div class="col-sm-6">
                    <label>Enter Number Of Epins</label>
                     <input type="number" name='npins' placeholder="Enter Number Of Epins" class="p-2 form-control form-control-alternative mb-4" required >
                </div>
                <div class="col-sm-6">
                    <label>Select Your Epin Value</label>
                    <select class="p-2 form-control form-control-alternative mb-3" name="amount" >
                       <?php  
                           $this->db->select('*')->from('product')->where('show_on_regform','Yes')->where('id !=',3);
                           $result =  $this->db->get()->result();          
                           foreach ($result as $kue) {
                               echo '<option selected readonly value='.$kue->prod_price.'>'.$kue->prod_name.'('.$kue->prod_price.')</option>';
                           }
                       ?>
                    </select>
                </div>
                <div class="col-sm-6">
                    <label>How you made Payment</label>
                    <select class="form-control form-control-alternative" id="type" name="type" required>
                        <option value="" selected disabled>Select Payment Method</option>
                        <option value="Cash">Cash</option>
                        <option value="Online">Online / Upi</option>
                    </select>
                </div>
                <div class="col-sm-6" id="qrCode" style="display:none;">
                    <label>Scan QR Code to Pay</label>
                    <div class="mt-2">
                        <!-- QR Code Image -->
                        <img src="<?php echo base_url('uploads/qr_code.png') ?>"height="150px" width="150px" alt="QR Code" class="img-fluid">
                    </div>
                </div>

            </div>
            <div id="admDivCheck" style="display:none;" class="row mt-3"> 
                <!--<div class="col-sm-6">-->
                <!--    <label>Transcation Number</label>-->
                <!--    <input type="text" class="form-control form-control-alternative" value="" name="tnumber">-->
                <!--</div>-->
                 <div class="col-sm-6">
                    <label>Add Recepit</label>
                    <input type="file" name='file'  class="p-2 form-control form-control-alternative mb-4" accept="image/*">
                </div>
            </div>
                <input type="submit" value="Send Request" class="btn btn-m btn-success w-100 mt-3">
                </form>
                </div> 
                </div> 
                </div> 
                
                <div class="row p-2">
                    <div class="card">
                            <div class="row bg-light  p-3 ">
                                <div class="col-sm-11">
                                    <h4 class="card-title ">Latest 10 EPINS</h4>
                                </div>
                                <div class="col-sm-1">
                                    <a class="btn btn-sm btn-primary" href="<?php echo base_url('member/unused-epin'); ?>">See All</a>
                                </div>
                            </div>   
                        <div class="card-body table-responsive">
                                <table id="datatable" class="table align-middle table-nowrap table-check">
                                 <thead class="table-light">
                                <tr>
                                    <th class="align-middle">Sn.</th>
                                    <th class="align-middle">Date</th>
                                    <th class="align-middle">Epin</th>
                                    <th class="align-middle">E-Pin Value</th>
                                    <th class="align-middle">Issue To</th>
                                    <th class="align-middle">E-Pin Type</th>
                                    <th class="align-middle">Action</th>
                                </tr>
                                </thead>
                                <tbody>
                                     <?php
                                        $sn = 1;
                                        foreach ($epin as $e) { ?>
                                            <tr>
                                                <td><?php echo $sn++; ?></td>
                                                <td><?php echo $e['generate_time']; ?></td>
                                                <td><?php echo $e['epin']; ?></td>
                                                <td><?php echo $e['amount']; ?></td>
                                                <td><?php echo config_item('ID_EXT') . $e['issue_to']; ?></td>
                                                <td><?php echo $e['type']; ?></td>
                                                <!--<td><a href="" class="btn btn-primary btn-sm "   data-bs-toggle="modal" data-bs-target=".bs-example-modal-sm2" onclick="document.getElementById('deliverid').value='<?php echo $e['epin'] ?>'">Use Now</a></td>-->
                                                <td><a href="" class="btn btn-primary btn-sm "   data-bs-toggle="modal" data-bs-target=".bs-example-modal-sm2" onclick="document.getElementById('deliverid').value='<?php echo $e['epin'] ?>'">Use Now</a></td>
                                            </tr>
                                        <?php } ?>
                            </table>
                        </div>
            </div>
        </div>
<!--  Small modal example -->
<div class="modal fade bs-example-modal-sm2 h-100" tabindex="-1" role="dialog" aria-labelledby="mySmallModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-sm">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="mySmallModalLabel">Epin Activation</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body w-100">
                <form action="<?php echo site_url('member/epin_activation_by_member')?>" method="post">
                    <div class="h2 w-100 text-right">
                      <small  id="spn_res" style="color: black;text-align:center;width:100%">User Name : </small>
                    </div>
                    <input type="hidden" name="epin" value="" id="deliverid"> 
                    <input type="text" name='user_id' placeholder="Enter Userid" class="p-2 w-100 mb-3" required id="sponsor" oninput="get_user_name('#sponsor', '#spn_res')">
                     <select class="p-2 w-100 mb-3" id="signup_package" name="signup_package" >
                        <?php  
                            $this->db->select('id,prod_name,dealer_price')->from('product')->where('show_on_regform','Yes');
                            $result =  $this->db->get()->result();          
                            foreach ($result as $kue) {
                                echo '<option selected readonly value='.$kue->id.'>'.$kue->prod_name.'('.$kue->dealer_price.')</option>';
                            }
                        ?>
                    </select>
                    <input type="hidden" name="signup_package" id="signup_package_hidden">
                    <!--<input type="password" class="p-2 w-100 mb-3" placeholder="Enter Transaction Password" name="trans_password">-->
                    <input type="submit" id="submit_btn" value="Activate User" class="btn btn-m btn-success w-100">
                </form>
            </div>
        </div><!-- /.modal-content -->
    </div><!-- /.modal-dialog -->
</div><!-- /.modal -->
<!--
 <script type="text/javascript">
    function get_user_name(id, result) {
        var myString = $(id).val();
        // let numbersOnly = myString.replace(/\D/g, "");
        var id =  myString.replace(/\D/g, "");
        $.get("<?php echo site_url('site/get_user_name/') ?>" + id, function (data) {
            $(result).html(data);
        });
    }
</script>    -->
<script type="text/javascript">
    function get_user_name(id, result) {
        var myString = $(id).val();
        var uid = myString.replace(/\D/g, "");

        $.get("<?php echo site_url('site/get_user_name_for_epin/') ?>" + uid, function(res) {

            var data = JSON.parse(res);

            if (data.status == 'success') {
                $(result).html(data.name).css("color","green");

                if (data.activation_type == 'free') {
                    $("#signup_package").val("");          // No Package Select
                    $("#signup_package_hidden").val(""); 
                    $("#signup_package").prop("disabled", true);
                    $("#submit_btn").prop("disabled", true);
                    $(result).append(" <span style='color:blue'>(Free Registered)</span>");

                } else if (data.activation_type == 'paid') {
                    $("#signup_package").val(data.join_package); // Auto select package
                    $("#signup_package").prop("disabled", true);
                    $("#signup_package_hidden").val(data.join_package); 
                    $("#submit_btn").prop("disabled", false);
                    $(result).append(" <span style='color:green'>(Paid User)</span>");
                }

            } else {
                $(result).html(data.message).css("color","red");
                $("#signup_package_hidden").val($("#signup_package").val());
                $("#signup_package").val("").prop("disabled", false); // Allow user to choose package
            }
        });
    }
</script>   
                
        <script src="https://ajax.googleapis.com/ajax/libs/jquery/2.2.0/jquery.min.js"></script>
        
        <script>
        $("#type").on("change",function(){"Online"===$(this).val()?$("#admDivCheck").show():$("#admDivCheck").hide()});
        // $("#type").on("change",function(){"Wallet"===$(this).val()?$("#admDdivCheck").show():$("#admDdivCheck").hide()});
        // $("#type").on("change",function(){"NEFT"===$(this).val()?$("#admDdivCheck").show():$("#admDdivCheck").hide()});
        // $("#type").on("change",function(){"Bank"===$(this).val()?$("#admDdivCheck").show():$("#admDdivCheck").hide()});
        $("#type").on("change",function(){"Cash"===$(this).val()?$("#admDdivCheck").show():$("#admDdivCheck").hide()});
        // $("#type").on("change",function(){"Cheque"===$(this).val()?$("#admDdivCheck").show():$("#admDdivCheck").hide()});
        </script>

        <script>
            
            document.getElementById('type').addEventListener('change', function() {
                const qr = document.getElementById('qrCode');
                if(this.value === 'Online'){
                    qr.style.display = 'block';
                } else {
                    qr.style.display = 'none';
                }
            });
        </script>

  
