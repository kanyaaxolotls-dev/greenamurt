
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
        <div class="row">
            <div class="col-1"><a class="btn btn-danger btn-sm" href="<?php echo site_url('tree/autopool_team/deluxe') ?>">Deluxe</a></div>
            <div class="col-1"><a class="btn btn-danger btn-sm" href="<?php echo site_url('tree/autopool_team/premium') ?>">Premium </a></div>
            <div class="col-1"><a class="btn btn-danger btn-sm" href="<?php echo site_url('tree/autopool_team/silver') ?>">Silver</a></div>
            <div class="col-1"><a class="btn btn-danger btn-sm" href="<?php echo site_url('tree/autopool_team/gold') ?>">Gold</a></div>
            <div class="col-2"><a class="btn btn-danger btn-sm" href="<?php echo site_url('tree/autopool_team/supergold') ?>">Super Gold</a></div>
            <div class="col-1"><a class="btn btn-danger btn-sm" href="<?php echo site_url('tree/autopool_team/diamond') ?>">Diamond</a></div>

        </div><br>  


    <!--     <div class="row "> 
                <div class="col mb-1 mb-xl-0"><a class="btn btn-success btn-sm" href="<?php echo site_url('tree/autopool_team/'.$this->uri->segment(3).'/1') ?>">Level 1</a></div>
                <div class="col mb-1 mb-xl-0"><a class="btn btn-success btn-sm" href="<?php echo site_url('tree/autopool_team/'.$this->uri->segment(3).'/2') ?>">Level 2</a></div>
                <div class="col mb-1 mb-xl-0"><a class="btn btn-success btn-sm" href="<?php echo site_url('tree/autopool_team/'.$this->uri->segment(3).'/3') ?>">Level 3</a></div>
                <div class="col mb-1 mb-xl-0"><a class="btn btn-success btn-sm" href="<?php echo site_url('tree/autopool_team/'.$this->uri->segment(3).'/4') ?>">Level 4</a></div>
                <div class="col mb-1 mb-xl-0"><a class="btn btn-success btn-sm" href="<?php echo site_url('tree/autopool_team/'.$this->uri->segment(3).'/5') ?>">Level 5</a></div>
                <div class="col mb-1 mb-xl-0"><a class="btn btn-success btn-sm" href="<?php echo site_url('tree/autopool_team/'.$this->uri->segment(3).'/6') ?>">Level 6</a></div>
                <div class="col mb-1 mb-xl-0"><a class="btn btn-success btn-sm" href="<?php echo site_url('tree/autopool_team/'.$this->uri->segment(3).'/7') ?>">Level 7</a></div>
          
        </div>  -->
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
                        <?php $this->plan_model->autopool_team($this->session->user_id) ?>
                    </tbody>
                    <tfoot>
                            <th></th>
                            <th></th>
                            <th></th>
                            <th></th>
                            <th></th>
                            <th>TOTAL LEVEL <?php echo $this->uri->segment(4)?> BUSINESS : </th>
                            <th>
                                 <?php 
                                        // if($this->uri->segment(3)=='silver'){
                                        //     $type="Starter Income";
                                        // }
                                
                                        if($this->uri->segment(3)=='deluxe'){
                                            $type="Deluxe Income";
                                        }
                                        if($this->uri->segment(3)=='premium'){
                                            $type="Premium Income";
                                        }
                                        if($this->uri->segment(3)=='silver'){
                                            $type="Silver Income";
                                        }
                                        if($this->uri->segment(3)=='gold'){
                                            $type="Gold Income";
                                        }
                                        if($this->uri->segment(3)=='supergold'){
                                            $type="Super Income";
                                        }
                                        if($this->uri->segment(3)=='diamond'){
                                            $type="Diamond Income";
                                        }
                                
                                 echo config_item('currency') ?><?php 
                                 echo $this->db_model->sum('business', 'earning', array(
                                                        'userid'    => $this->session->user_id,
                                                        'type' => $type,
                                                        'levlno' => $this->uri->segment(4),
                                                    )) ?>.00

                            </th>  
                    </tfoot>
                    
                </table>
            </div>
        </div>
</div>
</div>
</div> 
</div> 
</div> 