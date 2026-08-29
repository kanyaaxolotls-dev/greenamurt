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

    <div class="card-body">
        <div class="ui-container mb-4">
            <table class="table table-bordered">
                <thead>
                    <tr>
                        <th>Level</th>
                        <th>Required Team</th>
                        <th>My Team</th>
                        <th>Status</th>
                    </tr>
                </thead>
                <tbody>
                <?php
                    $total_rows     = 10; 
                    $leg            = 2;
                    $members_needed = $leg;
                    $i              = 1; 
                    $prev_my_team   = 0;
                    while ($i <= $total_rows) {
                        $my_team           = $this->db_model->get_total_count($this->session->user_id, $i);
                        $remaining_members = max(0, $my_team - $prev_my_team);
                        $status            = ($remaining_members >= $members_needed) ? 'Completed' : 'Incomplete'; 
                        $status_color      = ($status == 'Completed') ? 'text-success' : 'text-danger';
                        echo "<tr>";
                        echo "<td>{$i}</td>";
                        echo "<td>{$members_needed}</td>";
                        echo "<td>{$remaining_members}</td>";
                        echo "<td class='{$status_color}'>{$status}</td>";
                        echo "</tr>";
                        $members_needed *= $leg;
                        $prev_my_team = $my_team; 
                        $i++; 
                    }
                ?>
                </tbody>
            </table>
        </div>
    </div>
</div>