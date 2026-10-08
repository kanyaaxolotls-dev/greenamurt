<div class="col">
    <div class="card bg-secondary shadow">  
        <div class="card-header bg-white border-0"> 
            <div class="row align-items-center">
                <div class="col-8"> 
                      <h3 class="mb-0">Sale Manager</h3>
                </div>
                <div class="col-4 text-right">  
                 
                   <a href="<?php echo site_url('admin')?>" class="btn btn-sm btn-primary">Home</a>
                </div> 
            </div> 
        </div>  
    
    <div class="p-4">
        <form action="<?= base_url('accounting/total_sale') ?>" method="post">
            <div class="row">
                <div class="col-md-6 col-sm-6 col-6">
                    <label for="exampleSelect">Start Date :</label>
                    <input type="date" name="start_date" class="form-control" value="<?= $start_date ?>"> 
                </div>
                <div class="col-md-6 col-sm-6 col-6">
                    <label for="exampleSelect">End Date :</label>
                    <input type="date" name="end_date" class="form-control" value="<?= $end_date ?>"> 
                </div>
                <div class="col-12 mt-3">
                    <input type="submit" class="btn btn-success w-100"> 
                </div>
            </div>
        </form>
    </div>
    <!--<div class="card-body">-->
    
    <div class="row hr_divider px-3">
    <div class="col-sm-12 table-responsive">
<table class="table table-striped" id="example">
    <thead>
        <tr style="font-weight: 900">
            <td>Title</td>
            <td>Amount</td>
        </tr>
    </thead>
    <tbody>
        <tr>
            <td>Total BV</td>
            <td>: <?= $total_bv ?></td>
        </tr>
        <tr>
            <td>Total Sale</td>
            <td>: ₹ <?= $total_sale ?></td>
        </tr>
        <tr>
            <td>Total Incentive</td>
            <td>: ₹ <?= $total_incentive ?></td>
        </tr>
    </tbody>
</table>
    </div>
   <a href="<?= base_url('admin') ?>" class="btn btn-xs btn-danger ml-3 my-3">&larr; Go Back</a>
</div>
    
    <!--<div class="row p-4">-->
    <!--    <div class="col-4">-->
    <!--      <div class="card card-stats mb-4 mb-xl-0">-->
    <!--        <div class="card-body">-->
    <!--          <div class="row">-->
    <!--            <div class="col">-->
    <!--              <h5 class="card-title text-uppercase text-muted mb-0">Total BV</h5>-->
    <!--              <span class="h2 font-weight-bold mb-0">-->
                <?php 
    // <!--                     echo $total_bv;-->
     ?>
    <!--                     </span>-->
    <!--            </div>-->
    <!--            <div class="col-auto">-->
    <!--              <div class="icon icon-shape bg-info text-white rounded-circle shadow">-->
    <!--                <i class="fas fa-percent"></i>-->
    <!--              </div>-->
    <!--            </div>-->
    <!--          </div>-->
    <!--          <p class="mt-3 mb-0 text-muted text-sm">-->
    <!--            <span class="text-success mr-2"><i class="fas fa-arrow-up"></i> 12%</span>-->
    <!--            <span class="text-nowrap">Since Date</span>-->
    <!--          </p>-->
    <!--        </div>-->
    <!--      </div>-->
    <!--    </div>-->
    <!--    <div class="col-4">-->
    <!--      <div class="card card-stats mb-4 mb-xl-0">-->
    <!--        <div class="card-body">-->
    <!--          <div class="row">-->
    <!--            <div class="col">-->
    <!--              <h5 class="card-title text-uppercase text-muted mb-0">Total Sales</h5>-->
    <!--              <span class="h2 font-weight-bold mb-0">-->
                   <?php
                //   echo $total_sale;
                 ?>
    <!--                     </span>-->
    <!--            </div>-->
    <!--            <div class="col-auto">-->
    <!--              <div class="icon icon-shape bg-info text-white rounded-circle shadow">-->
    <!--                <i class="fas fa-percent"></i>-->
    <!--              </div>-->
    <!--            </div>-->
    <!--          </div>-->
    <!--          <p class="mt-3 mb-0 text-muted text-sm">-->
    <!--            <span class="text-success mr-2"><i class="fas fa-arrow-up"></i> 12%</span>-->
    <!--            <span class="text-nowrap">Since Date</span>-->
    <!--          </p>-->
    <!--        </div>-->
    <!--      </div>-->
    <!--    </div>-->
    <!--    <div class="col-4">-->
    <!--      <div class="card card-stats mb-4 mb-xl-0">-->
    <!--        <div class="card-body">-->
    <!--          <div class="row">-->
    <!--            <div class="col">-->
    <!--              <h5 class="card-title text-uppercase text-muted mb-0">Total Incentive</h5>-->
    <!--              <span class="h2 font-weight-bold mb-0">-->
              <?php
                        // echo $total_incentive;
                ?>
    <!--                     </span>-->
    <!--            </div>-->
    <!--            <div class="col-auto">-->
    <!--              <div class="icon icon-shape bg-info text-white rounded-circle shadow">-->
    <!--                <i class="fas fa-percent"></i>-->
    <!--              </div>-->
    <!--            </div>-->
    <!--          </div>-->
    <!--          <p class="mt-3 mb-0 text-muted text-sm">-->
    <!--            <span class="text-success mr-2"><i class="fas fa-arrow-up"></i> 12%</span>-->
    <!--            <span class="text-nowrap">Since Date</span>-->
    <!--          </p>-->
    <!--        </div>-->
    <!--      </div>-->
    <!--    </div>-->
    <!--</div>-->
    
    <!--</div>-->

</div>
</div>