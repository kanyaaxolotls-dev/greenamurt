  <div class="col">
    <div class="card shadow">  
    <div class="p-4">
        <form action="<?= base_url('income/view-earning') ?>" method="post">
            <div class="row">
                <div class="col-md-4 col-sm-12 col-12">
                    <label for="exampleSelect">Select Earning Type :</label>
                    <select class="form-control" name="type">
                        <option value="" disabled selected>Select</option>
                        <?php
                            if($type != null or $type != ''){
                                echo "<option value=\"" . $type . "\" selected>" . $type . "</option>";
                            }
                            $this->db->select('*');
                            $this->db->from('earning');
                            $this->db->group_by('type');
                            $query  = $this->db->get();
                            $data2  = $query->result_array();
                            foreach($data2 as $tr){
                        ?>
                        <option value="<?= $tr['type'] ?>"><?= $tr['type'] ?></option>
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
        <div class="card-header bg-white border-0"> 
            <div class="row align-items-center">
                    <div class="col-8">
                          <h3 class="mb-0">View Earning</h3>
                    </div>
                <div class="col-4 text-right"> 
                    <a href="<?php echo site_url('income/view_earning')?>" class="btn btn-sm btn-primary">Home</a>
                </div> 
            </div> 
      </div>
 <div class="table-responsive">
   <table class="table align-items-center table-flush" id="example">
        <thead class="thead-light">

        <tr>
            <th scope="col">SN</th>
            <th scope="col">User ID</th>
            <th scope="col">Name</th>
            <th scope="col">Amount</th>
            <th scope="col">Type</th>
            <th scope="col">Ref ID</th> 
            <th scope="col">Date</th>
            <!--<th scope="col">Pair Match</th>-->
            <!--<th scope="col">Actions</th>-->
        </tr>
    </thead>
            <?php
                $sn = 1;
                foreach ($earning as $e) { 
                    $user_name = $this->db_model->select('name', 'member', array('id' => $e['userid']));
            ?>
            <tr>
                <td><?php echo $sn++; ?></td>
                <td><a href="<?php echo site_url('users/user_detail/' . $e['userid']) ?>" target="_blank"><?php echo config_item('ID_EXT') . $e['userid']; ?></a></td>
                <td><?php echo $user_name; ?></td>
                <td><?php echo config_item('currency') . $e['total_amount']; ?></td>
                <td><?php echo $e['type']; ?></td>
                <td><?php echo $e['ref_id'] ? config_item('ID_EXT') . $e['ref_id'] : ""; ?></td>
                <td><?php echo $e['date']; ?></td>
                <!--<td><?php echo $e['levlno']; ?></td>-->
                <!--<td>-->
                <!--    <a href="<?php echo site_url('income/edit_earning/' . $e['id']); ?>"><img src="https://img.icons8.com/cotton/20/000000/edit.png"></a>-->
                <!--    <a onclick="return confirm('Are you sure you want to delete this Record ?')"-->
                <!--       href="<?php echo site_url('income/remove_earning/' . $e['id']); ?>"-->
                <!--      ><img src="https://img.icons8.com/color/20/000000/clear-symbol.png"></a> -->
                <!--</td>-->
            </tr>
        <?php } ?>
    </table>
</div>
<div class="pull-right">
    <?php echo $this->pagination->create_links(); ?>
</div>
<div class="card-footer">
    <a href="<?php echo site_url('income/search-earning') ?>" class="btn btn-sm btn-primary">&larr; Go Back</a>
</div>
</div>