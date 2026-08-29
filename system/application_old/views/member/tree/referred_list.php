<div class="row">
    <div class="col-12">
        <div class="page-title-box d-sm-flex align-items-center justify-content-between">
            <h4 class="mb-sm-0 font-size-18">My Direct Team</h4>
            <div class="page-title-right">
                <ol class="breadcrumb m-0">
                    <li class="breadcrumb-item"><a href="javascript: void(0);">Team</a></li>
                    <li class="breadcrumb-item active">Direct List</li>
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
                        <h4 class="card-title">My Direct Team</h4>
                        <p class="card-title-desc">This is my Direct team list</p>
                    </div>
                </div>
            </div>
            <div class="px-4 py-2">
                <form action="<?= base_url('tree/direct_list') ?>" method="post">
                    <div class="row">
                        <div class="col-md-4 col-sm-12">
                            <label for="incomeType">Date Type:</label>
                            <select class="form-control" name="type" id="incomeType">
                                <option value="join_time" <?= ($type == 'join_time') ? 'selected' : '' ?>>Join Date</option>
                                <option value="activation_date" <?= ($type == 'activation_date') ? 'selected' : '' ?>>Activation Date</option>
                            </select>
                        </div>
                        <div class="col-md-4 col-sm-6 col-6">
                            <label for="startDate">Start Date:</label>
                            <input type="date" name="start_date" value="<?= $sdate ?? date('Y-m-d') ?>" class="form-control" id="startDate">
                        </div>
                        <div class="col-md-4 col-sm-6 col-6">
                            <label for="endDate">End Date:</label>
                            <input type="date" name="end_date" value="<?= $edate ?? date('Y-m-d') ?>" class="form-control" id="endDate">
                        </div>
                        <div class="col-12 mt-3">
                            <input type="submit" class="btn btn-success w-100">
                            <a href="<?= base_url('tree/direct_list'); ?>" class="btn btn-danger w-100 mt-2">Reset</a>
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
                            <th class="align-middle">Sr.No.</th>
                            <th class="align-middle">Username</th>
                            <th class="align-middle">Userid</th>
                            <th class="align-middle">Package Amount</th>
                            <th class="align-middle">Date of Join</th>
                            <th class="align-middle">Activation Date</th>
                            <th class="align-middle">Status</th>
                            <?php if ($show_position) { ?>
                            <th class="align-middle">Position</th>
                            <th class="align-middle">Side</th>
                            <th class="align-middle">Left</th>
                            <th class="align-middle">Right</th>
                            <?php } ?>
                        </tr>
                    </thead>
                    <tbody>
                        <?php $sn = 1; ?>
                        <?php foreach ($data as $e): ?>
                            <?php
                                $prod_sale   = $this->db_model->sum('cost', 'product_sale', array('userid' => $e['id']));
                                $sidee       = ($e['placement_leg'] == "A") ? 'Left' : 'Right';
                                $isActive    = ($prod_sale != 0) && ($prod_sale >= $active_topup);
                                $status      = $isActive ? 'Active' : 'Inactive';
                                $clr         = $isActive ? 'success' : 'danger';
                                $date        = $isActive ? htmlspecialchars($e['activation_date']) : 'Not Activated';
                                $member_data = $this->db_model->select_multi('*','member',array('id' => $e['id']));

                                $a = ($member_data->A == 0) ? '<span class="text-danger">Blank</span>' : config_item('ID_EXT') . $member_data->A;
                                $b = ($member_data->B == 0) ? '<span class="text-danger">Blank</span>' : config_item('ID_EXT') . $member_data->B;
                            ?>
                            <tr>
                                <td>
                                    <div class="form-check font-size-16">
                                        <input class="form-check-input" type="checkbox" id="orderidcheck<?= $sn ?>">
                                        <label class="form-check-label" for="orderidcheck<?= $sn ?>"></label>
                                    </div>
                                </td>
                                <td><?= $sn++ ?></td>
                                <td><?= htmlspecialchars($e['name']) ?></td>
                                <td><?= config_item('ID_EXT') . htmlspecialchars($e['id']) ?></td>
                                <td><?= htmlspecialchars($e['topup']) ?></td>
                                <td><?= htmlspecialchars($e['join_time']) ?></td>
                                <td class="text-<?= $clr ?>"><?= $date ?></td>
                                <td class="text-<?= $clr ?>"><?= htmlspecialchars($status) ?></td>
                                <?php if ($show_position) { ?>
                                <td><?= config_item('ID_EXT') . htmlspecialchars($e['position']) ?></td>
                                <td><?= $sidee ?></td>
                                <td><?= $a ?></td>
                                <td><?= $b ?></td>
                                <?php } ?>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
