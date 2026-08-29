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
            <div class="card-body table-responsive">
                <table id="datatable-buttons" class="table align-middle table-nowrap table-check">
                    <thead class="table-light">
                        <tr>
                            <th style="width: 20px;" class="align-middle">
                                <div class="form-check font-size-16">
                                    <input class="form-check-input" type="checkbox" id="checkAll">
                                    <label class="form-check-label" for="checkAll"></label>
                                </div>
                            </th>
                            <th class="align-middle">Userid</th>
                            <th class="align-middle">Date</th>
                            <th class="align-middle">Type</th>
                            <th class="align-middle">ref_id</th>
                            <th class="align-middle">Amount</th>
                            <th class="align-middle">Status</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php 
                            foreach ($earning as $e) { 
                                $admin_per  = config_item('admin_charges');
                                $tds_per    = config_item('payout_tax');
                        ?>
                            <tr>
                                <td>
                                    <div class="form-check font-size-16">
                                        <input class="form-check-input" type="checkbox" id="orderidcheck01">
                                        <label class="form-check-label" for="orderidcheck01"></label>
                                    </div>
                                </td>
                                <td><?php echo $e['userid']; ?></td>
                                <td><?php echo $e['date']; ?></td>
                                <td><?php echo $e['type']; ?></td>
                                <td><?php echo $e['ref_id']; ?></td>
                                <td><?php echo config_item('currency') . $e['amount']; ?></td>
                                <td>
                                    <?php if ($e['status'] == "Paid") { ?>
                                        <span class="badge bg-soft-success text-success"><?php echo $e['status']; ?></span>
                                    <?php } else { ?>
                                        <span class="badge bg-soft-danger text-danger"><?php echo $e['status']; ?></span>
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
