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
        <?php if (!empty($category_totals)) { ?>
        <div class="px-4 py-2">
            <div class="row">
                <?php foreach ($category_totals as $ct) { ?>
                    <div class="col-md-3 col-sm-6 mb-2">
                        <div class="p-2 border rounded bg-light">
                            <small class="text-muted d-block"><?= htmlspecialchars($ct['type']) ?></small>
                            <strong class="text-success font-size-16"><?= config_item('currency') . number_format($ct['cat_total'], 2) ?></strong>
                        </div>
                    </div>
                <?php } ?>
                <div class="col-md-3 col-sm-6 mb-2">
                    <div class="p-2 border rounded bg-light" style="border-left: 4px solid #5e72e4 !important;">
                        <small class="text-primary d-block font-weight-bold">Grand Total</small>
                        <strong class="text-primary font-size-16"><?= config_item('currency') . number_format($grand_total ?? 0, 2) ?></strong>
                    </div>
                </div>
            </div>
        </div>
        <?php } ?>
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
            <th scope="col">Level</th>
            <th scope="col">Pair Match</th>
            <th scope="col">Tx Reference</th>
            <th scope="col">Date</th>
            <th scope="col">Status</th>
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
                <td><strong class="text-success"><?php echo config_item('currency') . number_format($e['total_amount'], 2); ?></strong></td>
                <td><span class="badge badge-info"><?php echo htmlspecialchars($e['type']); ?></span></td>
                <td><?php echo !empty($e['ref_id']) ? config_item('ID_EXT') . $e['ref_id'] : "-"; ?></td>
                <td><?php echo (!empty($e['levlno']) && $e['levlno'] > 0) ? 'Level ' . $e['levlno'] : '-'; ?></td>
                <td><?php echo (!empty($e['pair_match']) && $e['pair_match'] > 0) ? $e['pair_match'] . ' Pair(s)' : '-'; ?></td>
                <td><small class="text-muted font-monospace"><?php echo !empty($e['secret']) ? htmlspecialchars($e['secret']) : ('TX-' . ($e['id'] ?? '')); ?></small></td>
                <td><?php echo $e['date']; ?></td>
                <td>
                    <?php if (($e['status'] ?? '') == "Paid") { ?>
                        <span class="badge badge-success"><?php echo $e['status']; ?></span>
                    <?php } else { ?>
                        <span class="badge badge-warning"><?php echo $e['status'] ?? 'Pending'; ?></span>
                    <?php } ?>
                </td>
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