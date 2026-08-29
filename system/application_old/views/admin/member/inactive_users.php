<div class="col">
          <div class="card">
            <!-- Card header -->
            <div class="card-header border-0">
              <h3 class="mb-0">In-Active members</h3>
            </div>
            <!-- Light table -->
            <div class="table-responsive">
              <table class="table align-items-center table-flush">
                <thead class="thead-light">
                  <tr>
                    <th scope="col" class="sort" data-sort="name">Member Name</th>
                    <th scope="col" class="sort" data-sort="name">Sponsored count</th>
                    <th scope="col" class="sort" data-sort="name">Package</th>
                     <th scope="col" class="sort" data-sort="name">Rank</th>
                    <th scope="col" class="sort" data-sort="budget">Sponsor</th>
                    
                    <th scope="col" class="sort" data-sort="status">Phone</th>
   
                    <th scope="col" class="sort" data-sort="completion">Date Of Join</th>
                   
                  </tr>
                </thead>
                <tbody class="list">
                    <?php
                $sn = 1;
                foreach ($members as $e)
                
                { 
                    $count=$this->db_model->count_all('member', array(
                        'sponsor' =>$e['id']));
                
                    if($count < 2){
                        ?>
                  <tr> 
                    <td>
                      <div class="media align-items-center">
                        <a href="#" class="avatar rounded-circle mr-3">
                            <?php $prof_pic = $this->db_model->select('prof_pic', 'member_profile', array('userid' => $e['id'])); 
                            if($prof_pic==''){ ?>
                                <img alt="Photo" src="<?php echo base_url('uploads/logo.png')?>"/>
                            <?php }else{?>
                            <img alt="Photo" src="<?php echo base_url('uploads/'.$prof_pic)?>"/>
                            <?php } ?>
                        </a>

                        <div class="media-body">
                          <span class="name mb-0 text-sm">
                              <a href="<?php echo site_url('users/user_detail/' . $e['id']) ?>"
                               target="_blank"><?php echo config_item('ID_EXT') . $e['id']; ?></a>
                                <?php echo $e['name']; ?>
                          </span>
                        </div>
                      </div>
                    </td>
                    <td>
                        <?php echo $count; ?>
                    </td>
                    <td>
                         <?php $topup=$this->db_model->select('topup','member',array('id'=>$e['id'])); 
                            echo $topup;
                         ?>
                         
                    </td>
                    <td>
                         <?php $club=$this->db_model->select('rank','member',array('id'=>$e['id'])); 
                            echo $club;
                         ?>
                         
                    </td>
                    <td class="budget">
                      <a href="<?php echo site_url('users/user_detail/' . $e['sponsor']) ?>"
                               target="_blank"><?php echo $e['sponsor'] ? config_item('ID_EXT') . $e['sponsor'] : ''; ?></a>
                    </td> 
                   
                    <td>
                        <?php echo $e['phone']; ?>
                    </td>
                 
                    <td> 
                      <?php echo $e['join_time']; ?>
                    </td>
                    
                  </tr>
              <?php } } ?>
                </tbody>
              </table>
            </div>

            <!-- Card footer --> 
            <div class="card-footer py-2">
             <?php echo $this->pagination->create_links(); ?>
            </div>
          </div>
