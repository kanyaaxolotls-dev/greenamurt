<div class=" bg-secondary shadow">   
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
            <th>SN</th>
            <th>Userid</th>
            <th>Task id</th>
            <th>Task Name</th>
            <th>Start date</th>
            <th>Expired date</th>
            <th>Amount</th>
        </tr>
    </thead>
        <?php
        $sn = 1;
        if($task==false){?>

            <tr><td><h3 style="color: red;">No data found !</h3></td></tr>
        <?php } 
        foreach ($task as $e) { 
             $data=$this->db_model->select_multi('task_name,id,activation_date,expiry_date,amount','task_management',array('id'=>$e->task_id));
           // var_dump($e->id);
            ?>
        
            <tr>
                <td><?php echo $sn++; ?></td>
               <td><?php echo $e->userid; ?></td>
               <td><?php echo $e->task_id; ?></td>
               <td><?php echo $data->task_name; ?></td>
               <td><?php echo $data->activation_date; ?></td>
               <td><?php echo $data->expiry_date; ?></td>
               <td><?php echo $data->amount; ?></td>
            </tr>
        <?php } ?>
    </table>
</div>
</div>
<div class="card-footer">
     <a class="btn btn-primary btn-info btn-sm" href="<?php echo site_url('member/search-earning') ?>">Back</a>
    <?php echo $this->pagination->create_links(); ?>
</div>
