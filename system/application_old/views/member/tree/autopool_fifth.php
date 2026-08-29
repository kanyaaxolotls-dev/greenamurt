
<div class="card bg-secondary shadow">   
        <div class="card-header bg-white border-0"> 
                <div class="row align-items-center">
                    <div class="col-9">
                          <h4 class="mb-0"><?php echo $title ?></h4>
                    </div>
                <div class="col-3 text-right"> 
                   <a href="<?php echo site_url('member/index')?>" class="btn btn-sm btn-primary">Home</a>
                </div> 
            </div>
        </div> 
<div class="table-responsive">
    <table class="table align-items-center table-flush">
        <thead class="thead-light">      
            <tr>
                <th>S.N.</th>
                <th>User Id</th>
                <th>Downline</th>
                 <th>Full Name</th>
                <th>Date</th>
                <th>Upgrade Amount</th>
                <th>Status</th>
            </tr>
        </thead>
        <tbody>
            <?php  $this->plan_model->auto_pool_fifth($this->session->user_id); ?>
        </tbody>
       
    </table>
</div>
</div>
</div>
</div>
</div><!-- end of panel-->
