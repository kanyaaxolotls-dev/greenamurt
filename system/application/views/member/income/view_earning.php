<div class="row">
    <div class="col-12">
        <div class="page-title-box d-sm-flex align-items-center justify-content-between">
            <h4 class="mb-sm-0 font-size-18"><?php echo $title ?></h4>
            <div class="page-title-right">
                <ol class="breadcrumb m-0">
                    <li class="breadcrumb-item"><a href="javascript:void(0);">Member</a></li>
                    <li class="breadcrumb-item active"><?php echo $title ?></li>
                </ol>
            </div>
        </div>
    </div>
</div>

<div class="row">
    <div class="col-12">
        <div class="card">
            <div class="card-header">
                <div class="row mb-2">
                    <div class="col-sm-4">
                        <h4 class="card-title"><?php echo $title ?></h4>
                        <p class="card-title-desc">This is earning list</p>
                    </div>
                </div>
            </div>
            <div class="px-4 py-2">
                <form action="<?= base_url('member/view_earning/') ?>" method="post">
                    <div class="row">
                        <div class="col-md-4 col-sm-12">
                            <label for="exampleSelect">Income Type :</label>
                            <select class="form-control" name="type">
                                <option value="" disabled selected>Select Income Type</option>
                            <?php
                                $this->db->select('type, SUM(amount) as total_amount');
                                $this->db->from('earning');
                                $this->db->group_by('type');
                                $this->db->order_by('type','desc');
                                $query = $this->db->get();
                                $data  = $query->result_array();
                                foreach($data as $tr){
                                    if($tr['type'] == $type){
                                        $state = 'selected';
                                    }
                                    else{
                                        $state = '';
                                    }
                            ?>
                                <option value="<?= $tr['type'] ?>" <?= $state ?>><?= $tr['type'] ?></option>
                            <?php } ?>
                            </select>
                        </div>
                        <div class="col-md-4 col-sm-6 col-6">
                            <label for="exampleSelect">Start Date :</label>
                            <input type="date" name="start_date" value="<?= $sdate ?>" class="form-control"> 
                        </div>
                        <div class="col-md-4 col-sm-6 col-6">
                            <label for="exampleSelect">End Date :</label>
                            <input type="date" name="end_date" value="<?= $edate ?>" class="form-control"> 
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
                        <div class="p-2 border rounded bg-soft-primary">
                            <small class="text-primary d-block font-weight-bold">Grand Total</small>
                            <strong class="text-primary font-size-16"><?= config_item('currency') . number_format($grand_total ?? 0, 2) ?></strong>
                        </div>
                    </div>
                </div>
            </div>
            <?php } ?>
            <div class="card-body table-responsive">
                <table id="datatable-buttons" class="table align-middle table-nowrap table-check">
                    <thead class="table-light">
                        <tr>
                            <th class="align-middle">#</th>
                            <th class="align-middle">Date</th>
                            <th class="align-middle">Income Type</th>
                            <th class="align-middle">Ref / Sponsor</th>
                            <th class="align-middle">Level</th>
                            <th class="align-middle">Pair Match</th>
                            <th class="align-middle">Tx ID</th>
                            <th class="align-middle">Amount</th>
                            <th class="align-middle">Status</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php 
                            $sn = 1;
                            foreach ($earning as $e) { 
                        ?>
                            <tr>
                                <td><?php echo $sn++; ?></td>
                                <td><?php echo $e['date']; ?></td>
                                <td><span class="badge bg-soft-info text-info font-size-12"><?php echo htmlspecialchars($e['type']); ?></span></td>
                                <td><?php echo !empty($e['ref_id']) ? config_item('ID_EXT') . $e['ref_id'] : '-'; ?></td>
                                <td><?php echo (!empty($e['levlno']) && $e['levlno'] > 0) ? 'Level ' . $e['levlno'] : '-'; ?></td>
                                <td><?php echo (!empty($e['pair_match']) && $e['pair_match'] > 0) ? $e['pair_match'] . ' Pair(s)' : '-'; ?></td>
                                <td><small class="text-muted font-monospace"><?php echo !empty($e['secret']) ? htmlspecialchars($e['secret']) : ('TX-' . $e['id']); ?></small></td>
                                <td><strong class="text-success"><?php echo config_item('currency') . number_format($e['amount'], 2); ?></strong></td>
                                <td>
                                    <?php if ($e['status'] == "Paid") { ?>
                                        <span class="badge bg-soft-success text-success"><?php echo $e['status']; ?></span>
                                    <?php } else { ?>
                                        <span class="badge bg-soft-warning text-warning"><?php echo $e['status']; ?></span>
                                    <?php } ?>
                                </td>
                            </tr>
                        <?php } ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
