
                        <!-- start page title -->
                        <div class="row">
                            <div class="col-12">
                                <div class="page-title-box d-sm-flex align-items-center justify-content-between">
                                    <h4 class="mb-sm-0 font-size-18">My Orders</h4>

                                    <div class="page-title-right">
                                        <ol class="breadcrumb m-0">
                                            <li class="breadcrumb-item"><a href="javascript: void(0);">Ecommerce</a></li>
                                            <li class="breadcrumb-item active">Orders</li>
                                        </ol>
                                    </div>

                                </div>
                            </div>
                        </div>

                        <div class="row">
                                <div class="col-12">
                                        <div class="card">
                                                <div class="card-header">
                                                         <div class="row mb-2">
                                                            <div class="col-sm-4">
                                                                <h4 class="card-title">My customers </h4>
                                                                <p class="card-title-desc">This is data for my customers history
                                                                   
                                                                </p>
                                                            </div>
                                                            <div class="col-sm-8">
                                                                <div class="text-sm-end">
                                                                  <!--   <button type="button" class="btn btn-success btn-rounded waves-effect waves-light mb-2 me-2"><i class="mdi mdi-plus me-1"></i> Add New Order</button> -->
                                                                </div>
                                                            </div><!-- end col-->
                                                        </div>
                                                    </div>
                                                    <div class="card-body table-responsive">
                                                            <table id="datatable-buttons" class="table align-middle table-nowrap table-check">
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
                                                                            <td><a href="" class="btn btn-primary btn-sm "   data-bs-toggle="modal" data-bs-target=".bs-example-modal-sm2" onclick="document.getElementById('deliverid').value='<?php echo $e['epin'] ?>'">Use Now</a></td>
                                                                        </tr>
                                                                    <?php } ?>
                                                            </tbody>
                                                        </table>
                                                    </div>
                                                </div>
                                                <!-- end cardaa -->
                                            </div> <!-- end col -->
                                        </div> <!-- end row -->
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
</script>-->
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
