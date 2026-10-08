<?php

$top_id = $this->uri->segment('3') ? $this->uri->segment('3') : config_item('top_id');

?>
<div class="col"> 
          <div class="card bg-secondary shadow">  
            <div class="card-header bg-white border-0"> 
              <div class="row align-items-center">
                <div class="col-lg-6">
                  <h3 class="mb-0">Sponsor List</h3>
                </div>
                <div class="col-lg-6 text-right"> 
                    <form method="post" class="form-inline" action="<?php echo site_url('tree/referred-list') ?>">
                         <div class="col-lg-6">
                        <div class="form-group">
                            <input type="text" name="top_id" class="form-control-sm form-control-alternative" placeholder="Userid" >
                        </div>
                    </div>
                     <div class="col-lg-6">
                        <div class="form-group">
                            <button class="btn btn-sm btn-primary" type="submit">Search</button>
                        </div>
                    </div>
                    </form>
                </div>
              </div>
            </div> 
            <div class="card-body">
                  <div class="table-responsive">
                      <table class="table align-items-center table-flush">
                        <thead>
                        <tr>
                            <th scope="col">S.N.</th>
                            <th scope="col">Name</th>
                            <th scope="col">User Id</th>
                            <th scope="col">Join Date</th>
                            <th scope="col">Total Downline</th>
                            <th scope="col">Sponsor</th>
                        </tr>
                        </thead>
                        <tbody>
                        <?php $this->plan_model->ref_list($top_id) ?>
                        </tbody>
                    </table>
                </div>
            </div>

        </div>
</div>
