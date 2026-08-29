
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
    <div class="card-body"> 
        <div class="row "> 
                <div class="col mb-1 mb-xl-0"><a class="btn btn-success btn-sm" href="<?php echo site_url('tree/sponsor_level_list/1') ?>">Level 1</a></div>
                <div class="col mb-1 mb-xl-0"><a class="btn btn-success btn-sm" href="<?php echo site_url('tree/sponsor_level_list/2') ?>">Level 2</a></div>
                <div class="col mb-1 mb-xl-0"><a class="btn btn-success btn-sm" href="<?php echo site_url('tree/sponsor_level_list/3') ?>">Level 3</a></div>
                <div class="col mb-1 mb-xl-0"><a class="btn btn-success btn-sm" href="<?php echo site_url('tree/sponsor_level_list/4') ?>">Level 4</a></div>
                <div class="col mb-1 mb-xl-0"><a class="btn btn-success btn-sm" href="<?php echo site_url('tree/sponsor_level_list/5') ?>">Level 5</a></div>
                <div class="col mb-1 mb-xl-0"><a class="btn btn-success btn-sm" href="<?php echo site_url('tree/sponsor_level_list/6') ?>">Level 6</a></div>
                <div class="col mb-1 mb-xl-0"><a class="btn btn-success btn-sm" href="<?php echo site_url('tree/sponsor_level_list/7') ?>">Level 7</a></div>
                <div class="col mb-1 mb-xl-0"><a class="btn btn-success btn-sm" href="<?php echo site_url('tree/sponsor_level_list/8') ?>">Level 8</a></div>
                <div class="col mb-1 mb-xl-0"><a class="btn btn-success btn-sm" href="<?php echo site_url('tree/sponsor_level_list/9') ?>">Level 9</a></div>
                <div class="col mb-1 mb-xl-0"><a class="btn btn-success btn-sm" href="<?php echo site_url('tree/sponsor_level_list/10') ?>">Level 10</a></div> 
        </div> 
        <br>
        <div class="row"> 
            <div class="table-responsive">
                <table class="table align-items-center table-flush">
                    <thead class="thead-light">
                        <tr>
                            <th>S.N.</th>
                            <th>User Id</th>
                            <th>Name</th>
                            <th>Join Date</th>
                            <th>Sponsor ID</th>
                           
                            <th>Status</th>
                            <th>Joined Package</th>
                           
                        </tr>
                    </thead>
                    <tbody> 
                        <?php $this->plan_model->sponsor_level_list($this->session->user_id) ?>
                    </tbody>
                    <tfoot>
                            <th></th>
                            <th></th>
                            <th></th>
                            <th></th>
                            <th></th>
                            <th></th>
                           
                    </tfoot>
                    
                </table>
            </div>
        </div>
</div>
</div>
</div> 
</div> 
</div> 