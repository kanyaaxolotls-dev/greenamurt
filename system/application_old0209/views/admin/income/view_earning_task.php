  <div class="col">
    <div class="card shadow">  
        <div class="card-header bg-white border-0"> 
            <div class="row align-items-center">
            <div class="col-8">
                          <h3 class="mb-0">Task Earning</h3>
            </div>
            <div class="nav-wrapper">
                  <ul class="nav nav-pills nav-fill flex-column flex-md-row" id="tabs-icons-text" role="tablist">
                      <li class="nav-item">
                          <a class="nav-link mb-sm-3 mb-md-0 active" id="tabs-icons-text-1-tab" data-toggle="tab" href="#tabs-icons-text-1" role="tab" aria-controls="tabs-icons-text-1" aria-selected="true">Sponsor Level Income</a>
                      </li>
                      <li class="nav-item">
                          <a class="nav-link mb-sm-3 mb-md-0" id="tabs-icons-text-2-tab" data-toggle="tab" href="#tabs-icons-text-2" role="tab" aria-controls="tabs-icons-text-2" aria-selected="false">Level income</a>
                      </li>
                      <li class="nav-item">
                          <a class="nav-link mb-sm-3 mb-md-0" id="tabs-icons-text-3-tab" data-toggle="tab" href="#tabs-icons-text-3" role="tab" aria-controls="tabs-icons-text-3" aria-selected="false">Refferal / direct income</a>
                      </li>
                      <li class="nav-item">
                          <a class="nav-link mb-sm-3 mb-md-0"  href="<?php echo site_url('admin/index')?>">Home</a>
                      </li>
                  </ul>
                </div>
            </div> 
         </div>
      <div class="tab-content" id="myTabContent">
              
            <div class="tab-pane fade show active" id="tabs-icons-text-1" role="tabpanel" aria-labelledby="tabs-icons-text-1-tab">

                <div class="description card-body">
                   <!--fetch dafta-->
                   <?php 
                        $this->db->select('id, userid, amount, type, ref_id, date,status, pair_match,levlno')->from('earning')->where('type ','Task Sponsor Level Inc')->limit($config['per_page'], $page);
                        $data= $this->db->get()->result_array();
                        ?>
                        
<div class="table-responsive">
   <table class="table align-items-center table-flush">
        <thead class="thead-light">

        <tr>
            <th scope="col">SN</th>
            <th scope="col">User ID</th>
            <th scope="col">Amount</th>
            <th scope="col">Type</th>
            <th scope="col">Ref ID</th> 
            <th scope="col">Date</th>
            <th scope="col">Status</th>
            <th scope="col">Pair Match</th>
            <th scope="col">Actions</th>
        </tr>
    </thead>
        <?php
        $sn = 1;
        foreach ( $data as $e) { ?>
            <tr>
                <td><?php echo $sn++; ?></td>
                <td><a href="<?php echo site_url('users/user_detail/' . $e['userid']) ?>"
                       target="_blank"><?php echo config_item('ID_EXT') . $e['userid']; ?></a></td>
                <td><?php echo config_item('currency') . $e['amount']; ?></td>
                <td><?php echo $e['type']; ?></td>
                <td><?php echo $e['ref_id'] ? config_item('ID_EXT') . $e['ref_id'] : ""; ?></td>
                <td><?php echo $e['date']; ?></td>
                <td><?php echo $e['status']; ?></td>
                <td><?php echo $e['levlno']; ?></td>
                <td>
                    <a href="<?php echo site_url('income/edit_task_earning/' . $e['id']); ?>"><img src="https://img.icons8.com/cotton/20/000000/edit.png"></a>
                    <a onclick="return confirm('Are you sure you want to delete this Record ?')"
                       href="<?php echo site_url('income/remove_earning/' . $e['id']); ?>"
                      ><img src="https://img.icons8.com/color/20/000000/clear-symbol.png"></a> 
                </td>
            </tr>
        <?php } ?>
    </table>
</div>
                         
                  </div>
                 </div>
      

                    <div class="tab-pane fade" id="tabs-icons-text-2" role="tabpanel" aria-labelledby="tabs-icons-text-2-tab">
                        <div class="description card-body">
                        <!--fetch dafta-->
                        <?php   $this->db->select('id, userid, amount,status, type, ref_id, date, pair_match,levlno')->from('earning')->where('type ','Task Level Income')->limit($config['per_page'], $page);
                                $data= $this->db->get()->result_array();
                                
                        ?>
                                
        <div class="table-responsive">
           <table class="table align-items-center table-flush">
                <thead class="thead-light">
        
                <tr>
                    <th scope="col">SN</th>
                    <th scope="col">User ID</th>
                    <th scope="col">Amount</th>
                    <th scope="col">Type</th>
                    <th scope="col">Ref ID</th> 
                    <th scope="col">Date</th>
                    <th scope="col">Status</th>
                    <th scope="col">Pair Match</th>
                    <th scope="col">Actions</th>
                </tr>
            </thead>
                <?php
                $sn = 1;
                foreach ( $data as $e) { ?>
                    <tr>
                        <td><?php echo $sn++; ?></td>
                        <td><a href="<?php echo site_url('users/user_detail/' . $e['userid']) ?>"
                               target="_blank"><?php echo config_item('ID_EXT') . $e['userid']; ?></a></td>
                        <td><?php echo config_item('currency') . $e['amount']; ?></td>
                        <td><?php echo $e['type']; ?></td>
                        <td><?php echo $e['ref_id'] ? config_item('ID_EXT') . $e['ref_id'] : ""; ?></td>
                        <td><?php echo $e['date']; ?></td>
                        <td><?php echo $e['status']; ?></td>

                        <td><?php echo $e['levlno']; ?></td>
                        <td>
                            <a href="<?php echo site_url('income/edit_task_earning/' . $e['id']); ?>"><img src="https://img.icons8.com/cotton/20/000000/edit.png"></a>
                            <a onclick="return confirm('Are you sure you want to delete this Record ?')"
                               href="<?php echo site_url('income/remove_earning/' . $e['id']); ?>"
                              ><img src="https://img.icons8.com/color/20/000000/clear-symbol.png"></a> 
                        </td>
                    </tr>
                <?php } ?>
            </table>
        </div>
    </div>
</div>
                    
    <div class="tab-pane fade" id="tabs-icons-text-3" role="tabpanel" aria-labelledby="tabs-icons-text-3-tab">
   <div class="description">
  <?php 
   $this->db->select('id, userid, amount, type,status, ref_id, date, pair_match,levlno')->from('earning')->where('type ','Task Referral Reward')->limit($config['per_page'], $page);
   $data= $this->db->get()->result_array();
   ?>
                    
<div class="table-responsive">
<table class="table align-items-center table-flush">
    <thead class="thead-light">

    <tr>
        <th scope="col">SN</th>
        <th scope="col">User ID</th>
        <th scope="col">Amount</th>
        <th scope="col">Type</th>
        <th scope="col">Ref ID</th> 
        <th scope="col">Date</th>
        <th scope="col">Status</th>
        <th scope="col">Pair Match</th>
        <th scope="col">Actions</th>
    </tr>
</thead>
    <?php
    $sn = 1;
    foreach ( $data as $e) { ?>
        <tr>
            <td><?php echo $sn++; ?></td>
            <td><a href="<?php echo site_url('users/user_detail/' . $e['userid']) ?>"
                   target="_blank"><?php echo config_item('ID_EXT') . $e['userid']; ?></a></td>
            <td><?php echo config_item('currency') . $e['amount']; ?></td>
            <td><?php echo $e['type']; ?></td>
            <td><?php echo $e['ref_id'] ? config_item('ID_EXT') . $e['ref_id'] : ""; ?></td>
            <td><?php echo $e['date']; ?></td>
            <td><?php echo $e['status']; ?></td>

            <td><?php echo $e['levlno']; ?></td>
            <td>
                <a href="<?php echo site_url('income/edit_task_earning/' . $e['id']); ?>"><img src="https://img.icons8.com/cotton/20/000000/edit.png"></a>
                <a onclick="return confirm('Are you sure you want to delete this Record ?')"
                   href="<?php echo site_url('income/remove_earning/' . $e['id']); ?>"
                  ><img src="https://img.icons8.com/color/20/000000/clear-symbol.png"></a> 
            </td>
        </tr>
    <?php } ?>
</table>
</div>
                  
            </div>
          </div>
          </div> 
 
<div class="pull-right">
    <?php echo $this->pagination->create_links(); ?>
</div>
<div class="card-footer">
    <a href="<?php echo site_url('income/search-earning') ?>" class="btn btn-sm btn-primary">&larr; Go Back</a>
</div>
</div></div>