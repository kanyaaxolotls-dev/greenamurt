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
    if ($this->uri->segment(3) === NULL){
       $id = $this->session->user_id;
    } else {
       $id = $this->uri->segment(3);
    }
    ?>
    <div class="card-body">
        <div class="ui-container mb-4">
            <div class="col-md-4">
                <div class="form-group">
                    <select id='SelctLevel' class="form-control">
                        <option value="" selected>Select Level</option>
                        <?php for ($i = 1; $i <= 10; $i++): ?>
                            <option value="<?php echo $i; ?>">Level <?php echo $i; ?></option>
                        <?php endfor; ?>
                        <option value="all">See All List</option> 
                    </select>
                </div>
            </div>
        </div>

        <?php
        $levelData = [];
        $currentLevel = [$id];
        for ($level = 1; $level <= 10; $level++) {
            if (empty($currentLevel)) {
                break;
            }
            $placeholders = implode(',', array_fill(0, count($currentLevel), '?'));
            $sql = "SELECT * FROM member WHERE position IN ($placeholders)";
            $query = $this->db->query($sql, $currentLevel);
            $currentLevel = [];
            foreach ($query->result() as $row) {
                $levelData[$level][] = $row;
                $currentLevel[] = $row->id;
            }
        }

        foreach ($levelData as $level => $members) {
            echo '<div class="ui-container" id="level' . $level . '">';
            echo '<div class="table table-responsive shadow p-2 mt-3">';
            echo '<table class="table align-middle table-nowrap table-check" id="datatable' . $level . '">';
            echo '<thead class="table-light">';
            echo '<tr>';
            echo '<th>Sr.no</th>';
            echo '<th>Name</th>';
            echo '<th>ID</th>';
            echo '<th>Placement Id</th>';
            echo '<th>Sponsor Id</th>';
            echo '<th>Signup package</th>';
            echo '<th>Join Date</th>';
            echo '<th>Status</th>';
            echo '</tr>';
            echo '</thead>';
            echo '<tbody>';
            $sn = 1;
            foreach ($members as $member) {
                echo '<tr>';
                echo '<td>' . $sn++ . '</td>';
                echo '<td>' . $member->name . '</td>';
                echo '<td><a href="' . base_url('tree/generation_list/' . $member->id) . '">' . config_item('ID_EXT') . $member->id . '</a></td>';
                echo '<td>' . config_item('ID_EXT') . $member->position . '</td>';
                echo '<td>' . config_item('ID_EXT') . $member->sponsor . '</td>';
                echo '<td>' . ($this->db_model->select('prod_name', 'product', array('id' => $member->signup_package)) ?? '<span class=text-danger>Inactive</span>') . '</td>';
                echo '<td>' . $member->join_time . '</td>';
                echo '<td>' . ($member->topup > 0 ? '<span class=text-success>Active</span>' : '<span class=text-danger>Inactive</span>') . '</td>';
                echo '</tr>';
                $ak_data[] = $member->id;
            }
            echo '</tbody>';
            echo '</table>';
            echo '</div>';
            echo '</div>';
        }
        ?>
    
        <div class="ui-container" id="all">
            <div class="table table-responsive shadow p-2 mt-3">
                <table class="table align-middle table-nowrap table-check" id="datatable">
                    <thead class="table-light">
                        <tr>
                            <th>Sr.no</th>
                            <th>Name</th>
                            <th>ID</th>
                            <th>Placement Id</th>
                            <th>Sponsor Id</th>
                            <th>Signup package</th>
                            <th>Join Date</th>
                            <th>Status</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php 
                            $i = 0;
                            foreach ($ak_data as $member) { 
                                $i++;
                                $member_data = $this->db_model->select_multi('*','member',array('id' => $member));
                                if($member_data->signup_package){
                                    $status = 'Active';
                                    $clr    = 'success';
                                }else{
                                    $status = 'Inctive';
                                    $clr    = 'danger';
                                }
                        ?>
                        <tr>
                            <td><?= $i ?></td>
                            <td><?= $member_data->name ?></td>
                            <td><a href="https://bhakti-shakti.in/system/tree/generation_list/<?= $member ?>"><?= config_item('ID_EXT').$member ?></a></td>
                            <td><?= config_item('ID_EXT').$member_data->position ?></td>
                            <td><?= config_item('ID_EXT').$member_data->sponsor ?></td>
                            <td><span class="text-<?= $clr ?>"><?= $status ?></span></td>
                            <td><?= $member_data->join_time ?></td>
                            <td><span class="text-<?= $clr ?>"><?= $status ?></span></td>
                        </tr>
                        <?php } ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
