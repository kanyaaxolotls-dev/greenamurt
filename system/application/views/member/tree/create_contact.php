                    <!-- start page title -->
                        <div class="row">
                            <div class="col-12">
                                <div class="page-title-box d-sm-flex align-items-center justify-content-between">
                                    <h4 class="mb-sm-0 font-size-18"><?php echo $title; ?></h4>

                                    <div class="page-title-right">
                                        <ol class="breadcrumb m-0">
                                            <li class="breadcrumb-item"><a href="javascript: void(0);">Contacts</a></li>
                                            <li class="breadcrumb-item active"><?php echo $title; ?></li>
                                        </ol>
                                    </div>

                                </div>
                            </div>
                        </div>
                        <!-- end page title -->

                        <div class="row">
                            <div class="col-xl-12">
                                <div class="card">
                                    <div class="card-header">
                                        <h4 class="card-title"><?php echo $title; ?></h4>
                                        <p class="card-title-desc">You can creat your leads or contacts for business</p>
                                    </div>
                                    <div class="card-body">
                                        <form action="<?php echo site_url('contacts/create_contact') ?>" method="POST" class="needs-validation">
                                            <div class="row">
                                                <div class="col-md-6">
                                                    <div class="mb-3">
                                                        <label class="form-label" for="validationCustom01">First name</label>
                                                        <input type="text" class="form-control" name="fname" id="validationCustom01" placeholder="First name" required>
                                                        <div class="valid-feedback">
                                                            Looks good!
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="col-md-6">
                                                    <div class="mb-3">
                                                        <label class="form-label" for="validationCustom02">Last name</label>
                                                        <input type="text" class="form-control" name="lname" id="validationCustom02" placeholder="Last name" required>
                                                        <div class="valid-feedback">
                                                            Looks good!
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="row">
                                                <div class="col-md-6">
                                                    <div class="mb-3">
                                                        <label class="form-label" for="validationCustom05">Phone</label>
                                                        <input type="text" class="form-control" name="phone" id="validationCustom05" placeholder="Phone" required>
                                                        <div class="invalid-feedback">
                                                            Please provide a valid phone.
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="col-md-6">
                                                    <div class="mb-3">
                                                        <label class="form-label" for="validationCustom05">Email</label>
                                                        <input type="text" class="form-control" name="email" id="validationCustom05" placeholder="Email" required>
                                                        <div class="invalid-feedback">
                                                            Please provide a valid email.
                                                        </div>
                                                    </div>
                                                </div>

                                            </div>
                                            <div class="row">
                                                <div class="col-md-6">
                                                    <div class="mb-3">
                                                        <label class="form-label" for="validationCustom03">City</label>
                                                        <input type="text" class="form-control" name="city" id="validationCustom03" placeholder="City" required>
                                                        <div class="invalid-feedback">
                                                            Please provide a valid city.
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="col-md-6">
                                                  <div class="form-group mb-3">
                                                            <label class="form-label"> State</label>
                                                            <select required="" class="form-control form-select" name="state">
                                                                <option value="">Select State</option>
                                                                <option value="mh">Maharashtra</option>
                                                                <option value="kr">Karanatka</option>
                                                                <option value="gj">Gujrat</option>
                                                            </select>
                                                        </div>
                                                </div>

                                                 <div class="col-md-6">
                                                  <div class="form-group mb-3">
                                                            <label class="form-label"> Status</label>
                                                            <select required="" class="form-control form-select" name="status">
                                                                <option value="">Select Contact type</option>
                                                                <option value="New Lead">New Lead</option>
                                                                <option value="Client">Client</option>
                                                                <option value="Rejected">Rejected</option>
                                                                 <option value="Other">Other</option>
                                                            </select>
                                                        </div>
                                                </div>
                                            </div>
                                            <button class="btn btn-primary" type="submit">Create New Contact</button>
                                        </form>
                                    </div>
                                </div>
                                <!-- end card -->
                            </div> <!-- end col -->
        
                        </div>
                        <!-- end row -->