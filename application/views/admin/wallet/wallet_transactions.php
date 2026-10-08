
    <div class="col"> 
          <div class="card bg-secondary shadow">  
            <div class="card-header bg-white border-0"> 
              <div class="row align-items-center">
                <div class="col-10">
                  <h3 class="mb-0">Wallet Transaction History</h3>
                </div>
                <div class="col-2 text-right">
                    <a href="<?php echo site_url('admin')?>" class="btn btn-sm btn-primary">Home</a>
                </div>
              </div>
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table align-items-center table-flush" id="example">
                        <thead class="table-light">
                            <tr>
                                <th class="align-middle">Sr</th>
                                <th class="align-middle">User ID</th>
                                <th class="align-middle">Type</th>
                                <th class="align-middle">Amount</th>
                                <th class="align-middle">Ref. Id</th>
                                <th class="align-middle">Other</th>
                                <th class="align-middle">Date</th>
                              
                            </tr>
                        </thead>
                        <tbody>
                            <?php 
                                foreach ($w_tras as $wt) { 
                                    $null = '<span class=text-danger>No data</span>';
                            ?>
                            <tr>
                               <td><a href="javascript: void(0);" class="text-body fw-bold"> 
                                <?php echo $wt->id; ?></a> </td>
                                <td><?php echo $wt->userid; ?></td>
                                <td>
                                    <?php if ($wt->type =='Debit'){ ?>
                                       <span class="btn btn-danger btn-sm">  <?php echo $wt->type; ?></span>
                                    <?php } else{ ?>
                                         <span class="btn btn-success btn-sm">  <?php echo $wt->type; ?></span>
                                    <?php } ?>    
                                   </td>
                                 <td>
                                   <i class="fas fa-rupee-sign me-1"></i> <?php echo $wt->total_amount; ?>
                                </td>
                                <td><?php echo !empty($wt->ref_id) ? $wt->ref_id : $null; ?></td>
                                <td><?php echo !empty($wt->other) ? $wt->other : $null; ?></td>
                                <td>
                                    <?php echo $wt->created_date; ?>
                                </td>
                            </tr>
                           <?php } ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
    
    
    
    