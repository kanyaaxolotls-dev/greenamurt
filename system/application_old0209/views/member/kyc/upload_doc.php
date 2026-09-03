 
<div class="card">
     <?php 
        $readonly = ($my_profile->kyc_status == 1 || $my_profile->kyc_status == 2) ? 'disabled' : '';
    ?>
                                            <div class="card-header">
                                                 <p class="card-title fs-5 mb-4">
                                                            Your KYC status is 
                                                            <?php
                                                                $kyc_status   = $my_profile->kyc_status;
                                                                $kyc_message  = '';
                                                                $status_class = '';
                                                            
                                                                if ($kyc_status == 1) {
                                                                    $kyc_message  = 'Inprocess';
                                                                    $status_class = 'text-primary';
                                                                } elseif ($kyc_status == 2) {
                                                                    $kyc_message  = 'Verified';
                                                                    $status_class = 'text-success';
                                                                } elseif ($kyc_status == 3) {
                                                                    $kyc_message  = 'Rejected due to ' . $my_profile->kyc_message;
                                                                    $status_class = 'text-danger';
                                                                } else {
                                                                    $kyc_message  = 'Incomplete';
                                                                    $status_class = 'text-danger';
                                                                }
                                                            ?>
                                                            <span class="<?php echo $status_class; ?>"><?php echo $kyc_message; ?></span>
                                                        </p>
                                                            <h4 class="card-title">Upload your documents</h4>
                                                        </div>
                                                        <div class="card-body">
                                                           <form action="<?php echo base_url('member/file_upload_details')?>" enctype="multipart/form-data" class="" method="POST">
                                                                <div class="row justify-content-center">
                                                                    <div class="col-md-6 mb-4">
                                                                        <label>Adhar Front  </label>
                                                                        <div class="form-group">
                                                                            <input name="id_proof" type="file" class="form-control" multiple="multiple" <?php echo $readonly;  ?> >
                                                                        </div>
                                                                    </div>
                                                                    <div class="col-md-6 mb-4">
                                                                        <label>Adhar Back </label>
                                                                        <div class="form-group">
                                                                            <input name="adhar_back" type="file" class="form-control" multiple="multiple" <?php echo $readonly; ?> >
                                                                        </div>
                                                                    </div>
                                                                    <div class="col-md-6">
                                                                        <label>Pan Card </label>
                                                                        <div class="form-group">
                                                                            <input name="add_proof" type="file" class="form-control" multiple="multiple" <?php echo $readonly; ?> value="<?php echo $my_profile->add_proof ? base_url('uploads/'.$my_profile->add_proof) : base_url('uploads/default.jpg'); ?>" >
                                                                        </div>
                                                                    </div>
                                                            
                                                                    <div class="col-md-6 mb-4">
                                                                        <label>Profile pic</label>
                                                                        <div class="form-group">
                                                                            <input name="prof_pic" type="file" class="form-control" multiple="multiple" <?php echo $readonly; ?> >
                                                                        </div>
                                                                    </div>
                                                                    
                                                                    <div class="col-md-6">
                                                                        <div class="form-group mb-3">
                                                                            <label class="form-label">PAN No.</label>
                                                                            <input type="text" minlength="10" maxlength="10" placeholder="Enter pan no." class="form-control" name="tax_no" value="<?php echo set_value('tax_no', $my_profile->tax_no) ?>"/>
                                                                             <div class="invalid-feedback">
                                                                                Please provide a valid PAN no.
                                                                            </div>
                                                                        </div>
                                                                    </div>
                                                                    <div class="col-md-6">
                                                                        <div class="form-group mb-3">
                                                                            <label class="form-label">Aadhar No.</label>
                                                                            <input type="text" minlength="8" maxlength="17" placeholder="Enter adhar no." class="form-control" name="aadhar_no" value="<?php echo set_value('aadhar_no', $my_profile->aadhar_no) ?>" required/>
                                                                              <div class="invalid-feedback">
                                                                                Please provide a valid Aadhar no.
                                                                            </div>
                                                                        </div>
                                                                    </div> 
                                                                   
<div class="col-md-6">
    <div class="form-group mb-3">
        <label class="form-label">IFSC Code</label>
<input type="text" minlength="3" class="form-control" id="bank_ifsc" placeholder="Enter bank ifsc" name="bank_ifsc" value="<?php echo set_value('bank_ifsc', $my_profile->bank_ifsc) ?>" required/>
        <div class="invalid-feedback">
            Please provide a valid IFSC code.
        </div>
    </div>
</div>

<div class="col-md-6 mb-3">
    <label for="choices-single-groups" class="form-label">Bank Name</label>
    <input type="text" minlength="2" placeholder="Enter bank name" class="form-control" id="bank_name" name="bank_name" value="<?php echo set_value('bank_name', $my_profile->bank_name) ?>" required />
</div>

<div class="col-md-6">
    <div class="form-group mb-3">
        <label class="form-label">Account No.</label>
        <input type="text" minlength="3" placeholder="Enter bank account" class="form-control" name="bank_ac_no" value="<?php echo set_value('bank_ac_no', $my_profile->bank_ac_no) ?>" required />
        <div class="invalid-feedback">
            Please provide a valid Account No.
        </div>
    </div>
</div>

<div class="col-md-6">
    <div class="form-group mb-3">
        <label class="form-label">Branch Name</label>
        <input type="text" minlength="3" class="form-control" id="bank_branch" placeholder="Enter bank branch" name="bank_branch" value="<?php echo set_value('bank_branch', $my_profile->bank_branch) ?>" required />
        <div class="invalid-feedback">
            Please provide a valid Branch Name.
        </div>
    </div>
</div>
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script>
var ifscFound = false;
var typingTimer;

$('#bank_ifsc').on('input', function() {
    clearTimeout(typingTimer);
    var ifsc = $(this).val().trim();
    ifscFound = false; // reset found status
    removeInvalid(); // remove red border if typing again

    if (ifsc.length >= 5) {
        typingTimer = setTimeout(function() {
            $.ajax({
                url: 'https://ifsc.razorpay.com/' + ifsc,
                method: 'GET',
                success: function(response){
                    $('#bank_name').val(response.BANK);
                    $('#bank_branch').val(response.BRANCH);
                    ifscFound = true;
                },
                error: function(){
                    $('#bank_name').val('');
                    $('#bank_branch').val('');
                    ifscFound = false;
                }
            });
        }, 500); // wait for 0.5 seconds after typing
    } else {
        $('#bank_name').val('');
        $('#bank_branch').val('');
    }
});

$('#bank_ifsc').on('blur', function() {
    if (!ifscFound && $(this).val().length > 0) {
        markInvalid();
    }
});

// Functions to handle invalid style
function markInvalid() {
    $('#bank_ifsc').addClass('is-invalid');
}
function removeInvalid() {
    $('#bank_ifsc').removeClass('is-invalid');
}
</script>


        
                                                                     <div class="col-md-12">
                                                                        <div class="form-group mb-3">
                                                                            <label class="form-label">UPI ID</label>
                                                                            <input type="text" placeholder="Enter UPI no." class="form-control" name="upi_id" value="<?php echo set_value('upi_id', $my_profile->upi_id) ?>"/>
                                                                        </div>
                                                                    </div> 
                                                                    
                                                                    <div class="col-md-12 ">
                                                                        <div class="form-group ">
                                                                            <button type="submit" class="form-control btn-primary"  >Submit</button>
                                                                        </div>
                                                                    </div>
                                                                    
                                                                </div>
                                                            </form>

                                                                <div class="mt-4" id="overview-chart" data-colors='["#1c84ee"]' class="apex-charts" dir="ltr">
                                                                    <?php 
                                                                        if ($my_profile->add_proof !==''){ 
                                                                           $success="btn-success";  
                                                                        }
                                                                        else{ 
                                                                           $success="btn-danger"; 
                                                                        } 
                                                                        
                                                                        if ($my_profile->id_proof !==''){
                                                                            $success1="btn-success"; 
                                                                        }
                                                                        else{ 
                                                                            $success1="btn-danger"; 
                                                                        } 
                                                                        
                                                                        if ($my_profile->prof_pic !==''){ 
                                                                           $success2="btn-success"; 
                                                                        }
                                                                        else{ 
                                                                            $success2="btn-danger"; 
                                                                        } 
                                                                        
                                                                        if ($my_profile->adhar_back !==''){ 
                                                                           $success3="btn-success"; 
                                                                        }
                                                                        else{ 
                                                                            $success3="btn-danger"; 
                                                                        } 
                                                                    ?>

                                                                    <!-- Small modal button -->
                                                                     <button type="button" class="btn <?php echo $success1; ?> waves-effect btn-label waves-light" data-bs-toggle="modal" data-bs-target=".bs-example-modal-sm1"><i class="bx bx-check-double label-icon">
                                                                         
                                                                     </i> Adhar Card Front <i class="fas fa-eye"></i></button>

                                                                        <!--  Small modal example -->
                                                                        <div class="modal fade bs-example-modal-sm1" tabindex="-1" role="dialog" aria-labelledby="mySmallModalLabel" aria-hidden="true">
                                                                            <div class="modal-dialog modal-sm">
                                                                                <div class="modal-content">
                                                                                    <div class="modal-header">
                                                                                        <h5 class="modal-title" id="mySmallModalLabel">Document View</h5>
                                                                                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                                                                    </div>
                                                                                    <div class="modal-body">
                                                                                        <img class="card-img img-fluid" src="<?php echo $my_profile->id_proof ? base_url('uploads/'.$my_profile->id_proof) : base_url('uploads/default.jpg'); ?>" alt="ID Proof">
                                                                                        <p class="text-center">Adhar Card Front</p>
                                                                                    </div>
                                                                                </div><!-- /.modal-content -->
                                                                            </div><!-- /.modal-dialog -->
                                                                        </div><!-- /.modal -->

                          
                                                                    <!-- Small modal button -->
                                                                     <button type="button" class="btn <?php echo $success3; ?> waves-effect btn-label waves-light" data-bs-toggle="modal" data-bs-target=".bs-example-modal-sm4">
                                                                         <i class="bx bx-check-double label-icon"></i> Adhar Card Back <i class="fas fa-eye"></i></button>

                                                                        <!--  Small modal example -->
                                                                        <div class="modal fade bs-example-modal-sm4" tabindex="-1" role="dialog" aria-labelledby="mySmallModalLabel" aria-hidden="true">
                                                                            <div class="modal-dialog modal-sm">
                                                                                <div class="modal-content">
                                                                                    <div class="modal-header">
                                                                                        <h5 class="modal-title" id="mySmallModalLabel">Document View</h5>
                                                                                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                                                                    </div>
                                                                                    <div class="modal-body">
                                                                                        <img class="card-img img-fluid" src="<?php echo $my_profile->adhar_back ? base_url('uploads/'.$my_profile->adhar_back) : base_url('uploads/default.jpg'); ?>" alt="ID Proof">
                                                                                        <p class="text-center">Adhar Card Back</p>
                                                                                    </div>
                                                                                </div><!-- /.modal-content -->
                                                                            </div><!-- /.modal-dialog -->
                                                                        </div><!-- /.modal -->

                                                                    <!-- Small modal button -->
                                                                     <button type="button" class="btn <?php echo $success; ?> waves-effect btn-label waves-light" data-bs-toggle="modal" data-bs-target=".bs-example-modal-sm"><i class="bx bx-check-double label-icon">
                                                                         
                                                                     </i> Pan Card <i class="fas fa-eye"></i></button>

                                                                        <!--  Small modal example -->
                                                                        <div class="modal fade bs-example-modal-sm" tabindex="-1" role="dialog" aria-labelledby="mySmallModalLabel" aria-hidden="true">
                                                                            <div class="modal-dialog modal-sm">
                                                                                <div class="modal-content">
                                                                                    <div class="modal-header">
                                                                                        <h5 class="modal-title" id="mySmallModalLabel">Document View</h5>
                                                                                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                                                                    </div>
                                                                                    <div class="modal-body">
                                                                                        <img class="card-img img-fluid" src="<?php echo $my_profile->add_proof ? base_url('uploads/'.$my_profile->add_proof) : base_url('uploads/default.jpg'); ?>" alt="Address Proof">

                                                                                        <p class="text-center">Pan Card</p>
                                                                                    </div>
                                                                                </div><!-- /.modal-content -->
                                                                            </div><!-- /.modal-dialog -->
                                                                        </div><!-- /.modal -->
                                                                        
                                                                        <!-- Small modal button -->
                                                                     <button type="button" class="btn <?php echo $success2; ?> waves-effect btn-label waves-light" data-bs-toggle="modal" data-bs-target=".bs-example-modal-sm2"><i class="bx bx-check-double label-icon"></i> Profile pic
 <i class="fas fa-eye"></i></button>

                                                                        <!--  Small modal example -->
                                                                        <div class="modal fade bs-example-modal-sm2" tabindex="-1" role="dialog" aria-labelledby="mySmallModalLabel" aria-hidden="true">
                                                                            <div class="modal-dialog modal-sm">
                                                                                <div class="modal-content">
                                                                                    <div class="modal-header">
                                                                                        <h5 class="modal-title" id="mySmallModalLabel">Document View</h5>
                                                                                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                                                                    </div>
                                                                                    <div class="modal-body">
                                                                                        <img class="card-img img-fluid" src="<?php echo $my_profile->prof_pic ? base_url('uploads/'.$my_profile->prof_pic) : base_url('uploads/default.jpg'); ?>" alt="ID Proof">
                                                                                        <p class="text-center">Profile pic
</p>
                                                                                    </div>
                                                                                </div><!-- /.modal-content -->
                                                                            </div><!-- /.modal-dialog -->
                                                                        </div><!-- /.modal -->
                                                                </div>  
                                                         </div> <!-- end col -->
                                                </div> <!-- end row -->