<div class="col">
    <div class="card bg-secondary shadow">  
        <div class="card-header bg-white border-0"> 
        <div class="row align-items-center"> 
            <div class="col-8">
                  <h3 class="mb-0">Matching List</h3>
            </div>
        <div class="col-4 text-right"> 
            <a href="<?php echo site_url('admin')?>" class="btn btn-sm btn-primary">Home</a>
        </div> 
    </div>
</div>
<div class="card-body">
 
 <div class="table-responsive">
       <table id="data-table" class="table align-items-center table-flush">
        <thead class="thead-light">
        <tr>
      
                  <th scope="col">S.N.</th>
                  <th scope="col">Name</th>
                    <th scope="col">User ID</th>
                 <th scope="col">Investment  <?php echo "(".config_item('currency').")"?></th>
                 <th scope="col">Earned Amount</th>
                 <th scope="col">Date</th>
                   <th scope="col">Profit Amount</th>
              
                    <th scope="col">Action</th> 
                  </tr>
     
    </thead>
        <?php
 
        $sn = 1; 
        foreach ($earning as $e) {
          $duration=strtotime($e['upgrade_at']);
       
          $date = strtotime("+30 day",$duration);
          $backdate = date('Y-m-d',$date); 
   
          if($e['upgrade_at']<=$backdate){
          ?>
        <tr>
            <td><?php echo $sn++; ?></td>
                    <td >
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
                                <?php echo $this->db_model->select('name','member',array('id' => $e['userid'])); ?>
                          </span>
                        </div>
                      </div>
                    </td>
                    <td>
                    <?php echo $e['userid'];?>
                    </td>
                    <td>
                         <?php echo $e['upgrade_amt'];
                         ?>
                         
                    </td>
                    <td>
                    <?php $amt=$this->db_model->sum('amount', 'earning', array('userid' =>$e['userid'],'type'=>'Profit Income','ref_id'=>$e['id']));
                    if($amt==""){
                    echo '0';
                    }else{
                      echo $amt;
                    }?>
                    </td>
                    <td>
                         <?php echo $e['upgrade_at'];?>
                         
                    </td>
               
                    <td>
                    <?php echo form_open('users/upgrade') ?> 
                    <!-- <input type="Hidden" name="id" value="<?php echo $e['userid'] ?>">
                    <input type="number" name="amt"> -->

                    <input type="hidden" name="id" value="<?php echo $e['userid'] ?>">
                  <?php $cal_amt=$e['upgrade_amt']*(config_item('percentage')/100)?>
                    <input type="text" name="amt" value="<?php echo $cal_amt ?>">
                    <input type="hidden" name="uid" value="<?php echo $e['id'];?>">
                  
                    </td>
             
                    <td>
                   <?php $date=$this->db_model->select('date','earning',array('userid' => $e['userid'],'ref_id'=>$e['id']));
                   if($date == date('Y-m-d')){
                    echo '<i class="fa fa-check" aria-hidden="true" style="color:green"></i> Paid';
                   }
                   else{?>
                   <button type='submit' onclick='confirm("Are You Want To Submit Amount?")' class='btn btn-sm btn-success'>Submit</button>
                   <?php }?>
                   <?php echo form_close()?>
                   <?php
                   echo form_open('users/delete_invest');?>
                  <input type="hidden" name="iamt" value="<?php echo $e['upgrade_amt'];?>">
                  <input type="hidden" name="id" value="<?php echo $e['userid'] ?>">
                  <input type="hidden" name="uid" value="<?php echo $e['id'];?>">
                
                 <button type='submit' onclick='confirm("Really Want To close Investment? Investment amount will added in Users wallet..")' class='btn btn-sm btn-danger'>Close investment?</button>
                   <?php echo form_close();
                   ?>

                    
                    
                    
           
                  
                    </td>
                    
                   
                  </tr>
        <?php } }?>
    </table>
</div>
</div>
<div class="card-footer">
<a href="<?php echo site_url('income/search-earning') ?>" class="btn btn-xs btn-su">&larr; Go Back</a>
    <?php echo $this->pagination->create_links(); ?>
</div>
</div>
</div>