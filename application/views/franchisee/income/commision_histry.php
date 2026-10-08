<div class="col">
    <div class="card bg-secondary shadow">  
        <div class="card-header bg-white border-0"> 
            <div class="row align-items-center">
                <div class="col-9">
                      <h4 class="mb-0"><?php echo $title;?></h3>
                </div>
                <div class="col-3 text-right"> 
                    <a href="<?php echo site_url('franchisee')?>" class="btn btn-sm btn-primary">Home</a>
                </div>
            </div>
        </div> 
        <div class="card-body">
            <form method="POST" action="<?php echo site_url('franchisee/commision_histry'); ?>">
                <div class="row">
                    <div class="col-md-6">
                        <input type="date" name="from" class="form-control" value="<?= $start_week_date ?>">
                    </div>
                    <div class="col-md-6">
                        <input type="date" name="to" class="form-control" value="<?= $end_week_date ?>">
                    </div>
                </div>
                <div class="row my-3">
                    <div class="col text-right">
                        <button type="submit" class="btn w-100 btn-success">Search</button>
                    </div>
                </div>
            </form>
            <div class="table-responsive">
              <table class="table align-items-center table-flush" id="example">
                <thead class="thead-light">
                    <tr>
                        <th scope="col">SN</th>
                        <th scope="col">Amount</th>
                        <th scope="col">Type</th>
                        <th scope="col">Status</th>
                        <th scope="col">Orderid</th>
                        <th scope="col">Date</th>
                    </tr>
                </thead>
                    <?php
                        $sn = 1;
                        foreach ($earning as $e) {
                            if($e['status'] == "Paid"){
                                $clr = 'success';
                            } else{
                                $clr = 'warning';
                            }
                    ?> 
                    <tr>
                        <td class="text-dark"><?php echo $sn++; ?></td>
                        <td class="text-dark">₹ <?php echo $e['amount']; ?></td>
                        <td class="text-dark"><?php echo $e['type']; ?></td>
                        <td class="text-dark"><span class="btn btn-sm btn-<?= $clr ?>"><?php echo $e['status']; ?></span></td>
                        <td class="text-dark">#<?php echo $e['orderid']; ?></td>
                        <td class="text-dark"><?php echo $e['date']; ?></td>
                    </tr>
                    <?php } ?>
                </table>
            </div>
        </div>
    </div>
</div> 
</div>
</div>
</div>
