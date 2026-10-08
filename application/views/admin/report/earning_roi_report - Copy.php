<?php
$top_id = $this->session->user_id;
$status = $this->uri->segment('3') ? $this->uri->segment('3') : '';
$s_payment_date  = $this->uri->segment('4') ? $this->uri->segment('4') : '';
$e_payment_date  = $this->uri->segment('5') ? $this->uri->segment('5') : '';
$this->db->where('userid', htmlentities($top_id));
if ($status !== "") {

    $this->db->where('status', $status);
}
if ($s_payment_date !== "") {
 
    $this->db->where('payment_date >=', $sdate);
}
if ($e_payment_date !== "") {
    $this->db->where('payment_date <=', $e_payment_date);
}

$data = $this->db->get('earning_roi')->result();
?>
   <div class="col"> 
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
  
        <form method="post" action="">
            <div class="row">
                    <div class="col">
                        <div class="form-group">
                            <select name="status" class="form-control form-control-alternative">
                                <option selected>Select</option>
                                <option value="processed">Processed</option>
                                <option value="pending">Pending</option>
                                <option value="completed">Completed</option>
                            </select>
                        </div>
                    </div>
                    <div class="col">
                          <div class="form-group">
                            <div class="input-group input-group-alternative">
                                <div class="input-group-prepend">
                                    <span class="input-group-text"><i class="ni ni-calendar-grid-58"></i></span>
                                </div>
                                <input class="form-control datepicker" placeholder="Select date" type="text" name="s_payment_date">
                            </div>
                        </div>


                    </div>
                    <div class="col">
                     <div class="form-group">
                            <div class="input-group input-group-alternative">
                                <div class="input-group-prepend">
                                    <span class="input-group-text"><i class="ni ni-calendar-grid-58"></i></span>
                                </div>
                                <input class="form-control datepicker" placeholder="Select date" type="text" name="e_payment_date">
                            </div>
                        </div>
                       
                    </div>
                    <div class="col">
                        <button type="submit" class="btn btn-primary btn-block">Search</button>
                    </div>
             </div>
        </form>
   

 <div class="table-responsive">
        <table class="table align-items-center table-flush">
        <thead class="thead-light">
        <tr>
            <th>S.N.</th>
            <th>User ID</th>
			<th>Income Type</th>
            <th>Amount <?php config_item('currency') ?></th>
            <th>ROI <?php config_item('currency') ?></th>
            <th>ROI Frequency</th>
            <th>ROI Limit</th>
            <th>Payment Date</th>
            <th>Status</th>
        </tr>
        </thead>
        <tbody>
        <?php
        $sn = 1;
        foreach ($data as $e) {?>
            <tr>
                <td><?php echo $sn++; ?></td>
                <td><?php echo config_item('ID_EXT') . $e->userid ?></td>
                <td><?php echo $e->income_type; ?></td>
                <td><?php echo $e->amount; ?></td>
                <td><?php echo $e->roi; ?></td>
                <td><?php echo $e->roi_frequency; ?></td>
                
                <td><?php echo $e->roi_limit; ?></td>
				<td><?php echo $e->payment_date; ?></td>
                <td><?php echo $e->status ?></td>
            </tr>
        <?php } ?>
        </tbody>
    </table>
</div>
</div>
</div>
</div>
</div>
</div></div>