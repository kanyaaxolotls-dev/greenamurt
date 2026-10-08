                      <div class="row">
                            <div class="col-xl-12 col-md-12">
                                <!-- card -->
                                <div class="card card-h-100">
                                    <div class="card-header"> <h5> Utility and Bill Payment System</h5></div>
                                    <!-- card body -->
                                    <div class="card-body">
                                       
                                        <div class="row"> 
                                            <div class="col-md-4">
                                                 <div class="card"> 
                                                    <div class="card-header">
                                                        <h4 class="card-title">Recharge Panel</h4>
                                                    </div> 
                                                    <div class="card-body"> 
                                                            <form  action="<?php echo site_url('recharge/recharge') ?>" method="POST">
                                                                    <div class="mb-3">
                                                                        <label class="form-label">Select Operators</label>
                                                                        <select class="form-select" name="op_code">
                                                                            <?php   foreach ($rech_operators as $rech){   ?>
                                                                                <option value="<?php echo $rech['op_code']; ?>"><?php echo $rech['op_name']; ?></option>
                                                                            <?php } ?>
                                                                        </select>
                                                                    </div>
                                                                    <div class="mb-3">
                                                                          <label class="form-label">Mobile no.</label>
                                                                         <input class="form-control" type="text" name="number" placeholder="Enter <?php echo $this->uri->segment(3);?> no." required>
                                                                     </div>

                                                                    <div class="mb-3">
                                                                       <label class="form-label">Amount</label>
                                                                          <input type="number" min="10" max="4999" data-pristine-min-message="recharge amount should be min 10 - max 4999/-" required class="form-control" name="recharge_amount" placeholder="Min. Rs.10 - Rs. 4999" />
                                                                 
                                                                        <input class="form-control" type="text" name="circle_code" hidden>
                                                                       
                                                                        <input class="form-control" type="text" name="account" hidden>
                                                                    </div>
                                                          
                                                                        <div class="mb-3">       
                                                                             <button class="btn btn-primary waves-effect btn-label waves-light" type="submit"><i class="bx bx-smile label-icon"></i> Proceed</button>

                                                                             <a href="<?php echo site_url('recharge/new-recharge') ?>"> Back </a>
                                                                        </div>
                                                                    
                                                            </form> 
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="col-md-8">
                                             
                                                <div class="card">
                                                    <div class="card-header">
                                                        <h4 class="card-title">Offers & Plans</h4>
                                                    </div>
                                                    <div class="card-body">
                                                        <div class="table-light">
                                                            <table class="table">
                                                                <thead>
                                                                    <tr class="text-center">
                                                                        <th>Sr</th>
                                                                        <th>Operator</th>
                                                                        <th>Plan Name</th>
                                                                        <th>Desciption</th>
                                                                        <th>Action</th>
                                                                    </tr>
                                                                </thead>
                                                                <tbody>
                                                                    <tr>
                                                                        <th scope="row">1</th>
                                                                        <td><img src="https://seeklogo.com/images/A/airtel-logo-439F62AEA0-seeklogo.com.png" width="30px"></td>
                                                                        <td>Plan 249/-</td>
                                                                        <td>Truely Unlimited Calls | Data: 1 GB/day | 100 SMS daily</td>
                                                                        <td><a href="" class="btn btn-success btn-sm">Add to buy</a> </td>
                                                                    </tr>

                                                                     <tr>
                                                                        <th scope="row">1</th>
                                                                        <td><img src="https://www.indiantelevision.com/sites/default/files/styles/smartcrop_800x800/public/images/tv-images/2020/09/09/vi.jpg?itok=mY9gLigd" width="30px"></td>
                                                                        <td>Plan 349/-</td>
                                                                        <td>Truely Unlimited Calls | Data: 2 GB/day | 100 SMS daily</td>
                                                                        <td><a href="" class="btn btn-success btn-sm">Add to buy</a> </td>
                                                                    </tr>

                                                                     <tr>
                                                                        <th scope="row">1</th>
                                                                        <td><img src="https://logodix.com/logo/1785397.png" width="30px"></td>
                                                                        <td>Plan 299/-</td>
                                                                        <td>Truely Unlimited Calls | Data: 1.5 GB/day | 100 SMS daily</td>
                                                                        <td><a href="" class="btn btn-success btn-sm">Add to buy</a> </td>
                                                                    </tr>
                                                                 
                                                                </tbody>
                                                            </table>
                                                        </div>
                                                    </div>
                                                    <!-- end card body -->
                                                </div>
                                                <!-- end card -->
                        
                                            </div>
                                        </div>
                                     
                                    </div>
                                </div>
                            </div> 
                        </div>
