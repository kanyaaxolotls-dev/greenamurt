<div class="col">
          <div class="card">
            <!-- Card header -->
            <div class="card-header border-0">
              <h3 class="mb-0">Club Royalty report</h3>
            </div>
            <!-- Light table -->
            <div class="table-responsive">
              <table class="table align-items-center table-flush">
                <thead class="thead-light">
                  <tr>
                    <th scope="col" class="sort" data-sort="name">Member Name</th>
                    <th scope="col" class="sort" data-sort="name">Package</th>
                    <th scope="col" class="sort" data-sort="budget">Sponsor</th>
                    <th scope="col" class="sort" data-sort="status">Phone</th>
                    <th scope="col" class="sort" data-sort="status">Rank</th>
                     <th scope="col" class="sort" data-sort="completion">Date Of Join</th>
                     <th scope="col" class="sort" data-sort="status">Total Royalty</th>
                  </tr>
                </thead>
                <tbody class="list">
                    <?php
                $sn = 1;
                foreach ($members as $e) {
                //var_dump($e['userid']);
                    
                    ?>
                  <tr> 
                    <th scope="row">
                      <div class="media align-items-center">
                        <a href="#" class="avatar rounded-circle mr-3">
                            <?php $prof_pic = $this->db_model->select('prof_pic', 'member_profile', array('userid' => $e['userid'])); 
                            if($prof_pic==''){ ?>
                                <img alt="Photo" src="<?php echo base_url('uploads/logo.png')?>"/>
                            <?php }else{?>
                            <img alt="Photo" src="<?php echo base_url('uploads/'.$prof_pic)?>"/>
                            <?php } ?>
                        </a>

                        <div class="media-body">
                          <span class="name mb-0 text-sm">
                              <a href="<?php echo site_url('users/user_detail/' . $e['userid']) ?>"
                               target="_blank"><?php echo config_item('ID_EXT') . $e['userid']; ?></a>
                                <?php echo $e['name']; ?>
                          </span>
                        </div>
                      </div>
                    </th>
                    <td>
                         <?php echo $topup=$this->db_model->select('topup','member',array('id'=>$e['userid'])); 
                            
                         ?>
                         
                    </td>
                   
                    <td class="budget">
                    <?php echo $sponsor=$this->db_model->select('sponsor','member',array('id'=>$e['userid'])); ?>
                    </td> 
                   
                    <td>
                        <?php echo $phone=$this->db_model->select('phone','member',array('id'=>$e['userid'])); ?>
                    </td>
                   <td>
                   <?php echo $rank=$this->db_model->select('rank','member',array('id'=>$e['userid'])); ?></td>
                    <td> 
                      <?php echo $join_time=$this->db_model->select('join_time','member',array('id'=>$e['userid'])); ?>
                    </td>
                    <td>
                    <?php $sum=$this->db_model->sum('amount', 'earning', array('userid' =>$e['userid'],'type'=>'club Income'));
                    //var_dump($sum);
                        if($sum != NULL){
                            echo $sum;
                        }
                        else{
                            echo '0';
                        }
                        ?>
                    </td>
               
                  </tr>
              <?php } ?>
                </tbody>
              </table>
            </div>

            <!-- Card footer --> 
            <div class="card-footer py-2">
             <?php echo $this->pagination->create_links(); ?>
            </div>
          </div>
