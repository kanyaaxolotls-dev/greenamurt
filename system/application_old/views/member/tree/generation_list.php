<div class="card shadow fadein">  
    <div class="card-header bg-white border-0"> 
        <div class="row align-items-center">
            <div class="col-11">
                <h4 class="mb-0"><?php echo $title ?></h4>
            </div>
            <div class="col-1 text-right"> 
                <a href="<?php echo site_url('member/index')?>" class="btn btn-sm btn-primary">Home</a>
            </div>
        </div>
    </div>
    <?php

    ?>
    <div class="card-body">
        <div class="ui-container mb-4">
            <div class="col-md-4">
                <div class="form-group">
                    <select id='SelctLevel' class="form-control">
                        <option value="">Select Level</option>
                        <?php for ($i = 1; $i <= $gen_level; $i++): ?>
                            <option value="<?php echo $i; ?>">Level <?php echo $i; ?></option>
                        <?php endfor; ?>
                        <option value="all" selected>All List</option> 
                    </select>
                </div>
            </div>
        </div>

        <?php

        ?>

        <!-- Level-wise tables (hidden by default) -->
        <?php foreach ($levelData as $level => $members): ?>
            <div class="ui-container level-container" id="level<?php echo $level; ?>" style="display: none;">
                <div class="table table-responsive shadow p-2 mt-3">
                    <table class="table align-middle table-nowrap table-check" id="datatable<?php echo $level; ?>">
                        <thead class="table-light">
                            <tr>
                                <th>Sr.no</th>
                                <th>Name</th>
                                <th>ID</th>
                                <th>Sponsor Id</th>
                                <th>Signup package</th>
                                <th>Join Date</th>
                                <?php if ($show_position) { ?>
                                <th>Side</th>
                                <th>Placement Id</th>
                                <?php } ?>
                                <th>Status</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php $sn = 1; ?>
                            <?php 
                                foreach ($members as $member): 
                                    $prod_sale   = $this->db_model->sum('cost', 'product_sale', array('userid' => $member->id)); 
                                    $isActive    = ($prod_sale != 0) && ($prod_sale >= $active_topup);
                                    $status      = $isActive ? 'Active' : 'Inactive';
                                    $clr         = $isActive ? 'success' : 'danger';
                            ?>
                                <?php $sidee = ($member->placement_leg == "A") ? 'Left' : 'Right'; ?>
                                <tr>
                                    <td><?php echo $sn++; ?></td>
                                    <td><?php echo $member->name; ?></td>
                                    <td>
                                        <a href="<?php echo base_url('tree/generation_list/' . $member->id); ?>">
                                            <?php echo config_item('ID_EXT') . $member->id; ?>
                                        </a>
                                    </td>
                                    <td><?php echo config_item('ID_EXT') . $member->sponsor; ?></td>
                                    <td>
                                        <?php 
                                            $package = $this->db_model->select('prod_name', 'product', array('id' => $member->signup_package));
                                            echo $package ? $package : '<span class="text-danger">Inactive</span>'; 
                                        ?>
                                    </td>
                                    <td><?php echo $member->join_time; ?></td>
                                    <?php if ($show_position) { ?>
                                    <td><?php echo $sidee; ?></td>
                                    <td><?php echo config_item('ID_EXT') . $member->position; ?></td>
                                    <?php } ?>
                                    <td><span class="text-<?= $clr ?>"><?= $status ?></span></td>
                                </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        <?php endforeach; ?>
    
        <!-- All members table (shown by default) -->
        <div class="ui-container" id="all">
            <div class="table table-responsive shadow p-2 mt-3">
                <table class="table align-middle table-nowrap table-check" id="datatable">
                    <thead class="table-light">
                        <tr>
                            <th>Sr.no</th>
                            <th>Name</th>
                            <th>ID</th>
                            <th>Sponsor Id</th>
                            <th>Signup package</th>
                            <th>Join Date</th>
                            <?php if ($show_position) { ?>
                            <th>Side</th>
                            <th>Placement Id</th>
                            <?php } ?>
                            <th>Status</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php $i = 0; ?>
                            <?php 
                                foreach ($allMembers as $member): 
                                    $prod_sale   = $this->db_model->sum('cost', 'product_sale', array('userid' => $member->id)); 
                                    $isActive    = ($prod_sale != 0) && ($prod_sale >= $active_topup);
                                    $status1     = $isActive ? 'Active' : 'Inactive';
                                    $clr1        = $isActive ? 'success' : 'danger';
                            ?>
                            <?php 
                                $i++;
                                $sidee  = ($member->placement_leg == "A") ? 'Left' : 'Right';
                                $status = $member->signup_package ? 'Active' : 'Inactive';
                                $clr    = $member->signup_package ? 'success' : 'danger';
                            ?>
                            <tr>
                                <td><?php echo $i; ?></td>
                                <td><?php echo $member->name; ?></td>
                                <td>
                                    <a href="<?php echo base_url('tree/generation_list/' . $member->id); ?>">
                                        <?php echo config_item('ID_EXT') . $member->id; ?>
                                    </a>
                                </td>
                                <td><?php echo config_item('ID_EXT') . $member->sponsor; ?></td>
                                <td>
                                    <?php 
                                        $package = $this->db_model->select('prod_name', 'product', array('id' => $member->signup_package));
                                        echo $package ? $package : '<span class="text-danger">Inactive</span>'; 
                                    ?>
                                </td>
                                <td><?php echo $member->join_time; ?></td>
                                <?php if ($show_position) { ?>
                                <td><?php echo $sidee; ?></td>
                                <td><?php echo config_item('ID_EXT') . $member->position; ?></td>
                                <?php } ?>
                                <td><span class="text-<?= $clr1 ?>"><?= $status1 ?></span></td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

<script>
    $(document).ready(function() {
        $('.level-container').hide();
        $('#all').show();
        $('#SelctLevel').change(function() {
            var selectedLevel = $(this).val();
            $('.level-container').hide();
            $('#all').hide();
            if (selectedLevel === 'all') {
                $('#all').show();
            } else if (selectedLevel) {
                $('#level' + selectedLevel).show();
            }
        });
    });
</script>