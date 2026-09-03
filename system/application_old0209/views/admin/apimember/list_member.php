  <div class="col">
          <div class="card">
            <form action="update_wallet" method="post">  
            <!-- Card header -->
            <div class="card-header border-0">
                <div class="row align-items-center">
                    <div class="col-8">
                        <h3 class="mb-0">Member List</h3>
                    </div>
                    <div class="col-4 text-right">
                        <button type="submit" class="btn btn-sm btn-primary mb-10">Update Wallet</button>
                    </div>
                    <div>
                        <p class='text-info text-center'><?php echo $this->session->flashdata('message');?></p>
                    </div>
                </div>
            </div>
            
            <!--Card Body  Light table -->
            <div class='card-body'>
            <div class="table-responsive">
              <table class="table align-items-center table-flush">
                <thead class="thead-light">
              

                  <tr>
                    <th scope="col" class="sort" data-sort="name">Member Name</th>
                    <th scope="col" class="sort" data-sort="status">Phone</th>
                    <th scope="col" class="sort" data-sort="completion">Date Of Join</th>
                    <th scope="col" class="sort">Wallet Balance</th>
                     <th scope="col" class="sort">Add Balance</th>
                  </tr>
                </thead>
                <tbody class="list">
                    <?php
                $sn = 1;
                foreach ($members as $e) { ?>
                  <tr> 
                    <td scope="row">
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
                        <?php echo $e['phone']; ?>
                    </td>
                    <td> 
                      <?php echo $e['join_time']; ?>
                    </td>
                    
                    <td>
                       <?php echo $e['wallet_balance']; ?> 
                    </td>
                    <td>
                   <?php  $id=$e['id'];
                  
                    $amt= $this->db_model->select('amount', 'withdraw_request', array('userid' => $e['id'],'status'=>'Un-Paid')); 
                          ?>
                        <input type='text' readonly name='bal[<?php echo $e['phone']; ?>]' value='<?php echo $amt;?>'/>
                        <input type='hidden' name='uid[<?php echo $e['id']; ?>]' value='<?php echo $e['id'];?>'/>
                    </td>
                  </tr>
              <?php } ?>
                </tbody>
              </table>
            </div>
            </div>
            </form>
            
            <!-- Card footer --> 
            <div class="card-footer py-4">
              <nav aria-label="...">
                <?php echo $this->pagination->create_links(); ?>  
              </nav>
            </div>
          </div>
