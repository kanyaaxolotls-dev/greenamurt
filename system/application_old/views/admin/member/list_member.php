  <div class="col">
          <div class="card">
            <!-- Card header -->
            <div class="card-header border-0">
              <h3 class="mb-0">Member List</h3>
            </div>
            
            <div class="px-4 pb-4">
                <form action="<?= base_url('users/view_members') ?>" method="post">
                    <div class="row">
                        <div class="col-md-4 col-sm-12">
                            <label for="exampleSelect">Select Joining Product :</label>
                            <select class="form-control" name="type">
                                <option value="" disabled selected>Select Joining Product</option>
                                <?php
                                    if($type != null or $type != ''){
                                        $pname = $this->db_model->select('prod_name','product',array('id' => $type));
                                        echo "<option value=\"" . $type . "\" selected>" . $pname . "</option>";
                                    }
                                    $this->db->select('*');
                                    $this->db->from('product');
                                    $query  = $this->db->get();
                                    $data2  = $query->result_array();
                                    foreach($data2 as $tr){
                                ?>
                                <option value="<?= $tr['id'] ?>"><?= $tr['prod_name'] ?></option>
                                <?php } ?>
                            </select>
                        </div>
                        <div class="col-md-4 col-sm-6 col-6">
                            <label for="exampleSelect">Start Date :</label>
                            <input type="date" name="start_date" class="form-control" value="<?= $start_date ?>"> 
                        </div>
                        <div class="col-md-4 col-sm-6 col-6">
                            <label for="exampleSelect">End Date :</label>
                            <input type="date" name="end_date" class="form-control" value="<?= $end_date ?>"> 
                        </div>
                        <div class="col-12 mt-3">
                            <input type="submit" class="btn btn-success w-100"> 
                        </div>
                    </div>
                </form>
            </div>
            
            <!-- Light table -->
            <div class="table-responsive">
              <table class="table align-items-center table-flush" id="example">
                <thead class="thead-light">
                  <tr>
                    <th scope="col" class="sort" data-sort="name">SN</th>
                    <th scope="col" class="sort" data-sort="name">Member Name</th>
                    <th scope="col" class="sort" data-sort="status">Password</th>
                    <th scope="col" class="sort" data-sort="status">Transaction Password</th>
                    <th scope="col" class="sort" data-sort="name">Package</th>
                     <th scope="col" class="sort" data-sort="name">Rank</th>
                    <th scope="col" class="sort" data-sort="budget">Sponsor</th>
                    <th scope="col" class="sort" data-sort="budget">Position</th>
                    <th scope="col" class="sort" data-sort="status">Phone</th>
                    <th scope="col" class="sort" data-sort="status">Wallet</th>
                    <th>Kyc Status</th>
                    <th scope="col">Status</th>
                    <!--<th scope="col">Whatsapp</th>-->
                    <th scope="col" class="sort" data-sort="completion">Date Of Join</th>
                    <th scope="col" class="sort" data-sort="completion">Activation Date</th>
                    <th scope="col">Action</th>
                  </tr>
                </thead>
                <tbody class="list">
                    <?php
                $sn = 1;
                foreach ($members as $e) { ?>
                  <tr> 
                    <th><?= $sn++; ?></th>
                    <th scope="row">
                      <div class="media align-items-center">
                        <a href="#" class="avatar rounded-circle mr-3">
                            <?php $prof_pic = $this->db_model->select('prof_pic', 'member_profile', array('userid' => $e['id'])); 
                            if($prof_pic==''){ ?>
                                <img alt="Photo" src="<?php echo base_url('uploads/logo.png')?>" height="100%"/>
                            <?php }else{?>
                            <img alt="Photo" src="<?php echo base_url('uploads/'.$prof_pic)?>" height="100%"/>
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
                    </th>
                    <td>
                        <?php echo $e['pass']; ?>
                    </td>
                    <td>
                        <?php echo $e['trans_password']; ?>
                    </td>
                    <td>
                        <?php 
                            $topup = $this->db_model->sum('cost', 'product_sale', array('userid' => $e['id'])); 
                            $ch_tp = $this->db_model->select('active_topup', 'global_setting', array('id' => 1)); 
                            if($topup >= $ch_tp){
                                $date = $e['activation_date'];
                                $clr  = 'success';
                            } else{
                                $clr  = 'danger';
                                $date = 'Inactive';
                            }
                            echo $e['topup'];
                        ?>
                    </td>
                    <td>
                         <?php 
                            echo $e['rank'];
                         ?>
                         
                    </td>
                    <td class="budget">
                      <a href="<?php echo site_url('users/user_detail/' . $e['sponsor']) ?>" target="_blank"><?php echo $e['sponsor'] ? config_item('ID_EXT') . $e['sponsor'] : ''; ?></a>
                    </td> 
                    <td class="budget">
                      <a href="<?php echo site_url('users/user_detail/' . $e['position']) ?>" target="_blank"><?php echo $e['position'] ? config_item('ID_EXT') . $e['position'] : ''; ?></a>
                    </td> 
                   
                    <td>
                        <?php echo $e['phone']; ?>
                    </td>
                    <td>
                        <?php echo $this->db_model->select('balance', 'wallet', array('userid' => $e['id'])); ?>
                    </td>
                    <td>
                            <?php 
                                $kyc_stat = $this->db_model->select('kyc_status', 'member_profile', array('userid' => $e['id']));
                                if(!empty($kyc_stat) && $kyc_stat == 2){ ?>
                                <span class="badge badge-dot mr-4">
                                    <i class="bg-success"></i>
                                     </span>
                                <?php } else { ?>
                                     <span class="badge badge-dot mr-4">
                                    <i class="bg-danger"></i>
                                     </span>
                                <?php } ?>
                        </td>
                    <td>
                      <span class="badge badge-dot mr-4">
                        <span class="status"><?php if($topup >= $ch_tp){?> <i class="bg-success"></i> <?php }else{?> <i class="bg-danger"></i><?php } ?></span>
                      </span>
                    </td>
                    <!--<td>-->
                    <!--<a href="<?php echo base_url('users/send_msg/'.$e['id']) ?>"><img src="https://cdn-icons-png.flaticon.com/128/15707/15707820.png" height="40px"></a>-->
                    <!--</td>-->
                    <td> 
                      <?php echo $e['join_time']; ?>
                    </td>
                    <td class="text-<?= $clr ?>"> 
                      <?php echo $date; ?>
                    </td>
                    <td class="text-right">
                      <div class="dropdown">
                        <a class="btn btn-sm btn-icon-only text-light" href="#" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                          <i class="fas fa-ellipsis-v"></i>
                        </a>
                        <div class="dropdown-menu dropdown-menu-right dropdown-menu-arrow">
                            <a href="<?php echo site_url('users/login_member/' . $e['id']); ?>" target="_blank" class="dropdown-item">Login</a>
                            <a class="dropdown-item" href="<?php echo site_url('users/user_detail/' . $e['id']); ?>">View</a>
                            <a href="<?php echo site_url('users/edit_user/' . $e['id']); ?>" class="dropdown-item">Edit</a>
                            <a onclick="return confirm('Are you sure you want to delete this Member ?')" href="<?php echo site_url('users/remove_member/' . $e['id']); ?>" class="dropdown-item">Delete</a>
                        </div>
                      </div>
                    </td>
                  </tr>
              <?php } ?>
                </tbody>
              </table>
            </div>

          </div>
