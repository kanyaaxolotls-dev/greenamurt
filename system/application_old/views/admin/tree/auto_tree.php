<?php
$top_id = $id;

// Map pool names
$pool_names = [
    'autopool_1' => 'Silver',
    'autopool_2' => 'Gold',
    'autopool_3' => 'Platinum',
    'autopool_4' => 'Diamond'
];

// Detect current pool
$current_pool      = $pool ?? 'autopool_1';
$current_pool_name = $pool_names[$current_pool] ?? 'My Team';
?>


<div class="row m-3" style="border-radius: 1em">
    <div class="col-12">
        <div class="bg-white">
            <div class="card-header">
                <div class="row mb-2">
                    <div class="col-sm-4">
                        <h4 class="card-title"><?= $title ?></h4>
                    </div>
                    <div class="col-sm-8">
                        <div class="text-sm-end">
                            <?php
                                $userid = $this->session->user_id;
                                $top_id1 = $this->db_model->select('id', 'autopool_1', ['userid' => $userid]) ?? 0;
                                $top_id2 = $this->db_model->select('id', 'autopool_2', ['userid' => $userid]) ?? 0;
                                $top_id3 = $this->db_model->select('id', 'autopool_3', ['userid' => $userid]) ?? 0;
                                $top_id4 = $this->db_model->select('id', 'autopool_4', ['userid' => $userid]) ?? 0;

                                $buttons = [
                                    ['pool' => 'autopool_1', 'id' => $top_id1, 'name' => 'Silver'],
                                    ['pool' => 'autopool_2', 'id' => $top_id2, 'name' => 'Gold'],
                                    ['pool' => 'autopool_3', 'id' => $top_id3, 'name' => 'Platinum'],
                                    ['pool' => 'autopool_4', 'id' => $top_id4, 'name' => 'Diamond'],
                                ];
                            ?>

                            <?php foreach ($buttons as $btn): 
                                $is_active = ($btn['pool'] == $current_pool) ? 'btn-primary' : 'btn-success';
                            ?>
                                <a class="btn btn-sm <?= $is_active ?>" 
                                   href="<?= site_url('tree/auto_tree_admin/'.$btn['id'].'/'.$btn['pool']) ?>">
                                   <?= $btn['name'] ?>
                                </a>
                            <?php endforeach; ?>
                        </div>
                    </div>
                </div>
            </div>
            <div class="card-body">
                <div class="table-responsive bg-white">
                    <table class="table align-items-center">
                        <thead class="thead-light">
                            <tr>
                                <td colspan="8" align="center" class="text-dark">
                                    <?php if($this->uri->segment('3')>0){ ?>
                                        <a href="#" onclick="history.back();">
                                            <i class="fas fa-angle-double-down sm"></i>
                                        </a>
                                    <?php } ?>
                                    <br>
                                    <?php 
                                    $U = $this->plan_model->create_tree2($pool, $top_id);
                                    echo $U['data'].$U['id'];
                                    ?>
                                    <br/>
                                    <img src="<?php echo base_url('uploads/site_img/line_bg.gif') ?>" class="img-responsive">
                                </td>
                            </tr>
                        </thead>
                        <tbody> 
                            <tr> 
                                <td colspan="4" align="center" class='text-dark'>
                                    <?php 
                                    $A = $this->plan_model->create_tree2($pool, $U['A'], $U['id'], 'A');
                                    echo $A['data'].$A['id']; 
                                    ?>
                                    <br/>
                                    <img src="<?php echo base_url('uploads/site_img/line_sm.gif') ?>" class="img-responsive" style="width: 50%;">
                                </td>
                                <td colspan="4" align="center" class='text-dark'>
                                    <?php 
                                    $B = $this->plan_model->create_tree2($pool, $U['B'], $U['id'], 'B');
                                    echo $B['data'].$B['id']; 
                                    ?>
                                    <br/>
                                    <img src="<?php echo base_url('uploads/site_img/line_sm.gif') ?>" class="img-responsive" style="width: 50%;">
                                </td>
                            </tr>
                            <tr>
                                <td colspan="2" align="center" class='text-dark'>
                                    <?php 
                                    $A_1 = $this->plan_model->create_tree2($pool, $A['A'], $A['id'], 'A');
                                    echo $A_1['data'].$A_1['id']; 
                                    ?>
                                    <br/>
                                    <img src="<?php echo base_url('uploads/site_img/line_sm.gif') ?>" class="img-responsive" style="width: 80%;">
                                </td>
                                <td colspan="2" align="center" class='text-dark'>
                                    <?php 
                                    $A_2 = $this->plan_model->create_tree2($pool, $A['B'], $A['id'], 'B');
                                    echo $A_2['data'].$A_2['id']; 
                                    ?>
                                    <br/>
                                    <img src="<?php echo base_url('uploads/site_img/line_sm.gif') ?>" class="img-responsive" style="width: 80%;">
                                </td>
                                <td colspan="2" align="center" class='text-dark'>
                                    <?php 
                                    $B_1 = $this->plan_model->create_tree2($pool, $B['A'], $B['id'], 'A');
                                    echo $B_1['data'].$B_1['id']; 
                                    ?>
                                    <br/>
                                    <img src="<?php echo base_url('uploads/site_img/line_sm.gif') ?>" class="img-responsive" style="width: 80%;">
                                </td>
                                <td colspan="2" align="center" class='text-dark'>
                                    <?php 
                                    $B_2 = $this->plan_model->create_tree2($pool, $B['B'], $B['id'], 'B');
                                    echo $B_2['data'].$B_2['id']; 
                                    ?>
                                    <br/>
                                    <img src="<?php echo base_url('uploads/site_img/line_sm.gif') ?>" class="img-responsive" style="width: 80%;">
                                </td>
                            </tr>
                            <tr>
                                <td align="left" class='text-dark'>
                                    <?php 
                                    $A1_1 = $this->plan_model->create_tree2($pool, $A_1['A'], $A_1['id'], 'A');
                                    echo $A1_1['data'].$A1_1['id']; 
                                    ?>
                                </td>
                                <td align="right" class='text-dark'>
                                    <?php 
                                    $A1_2 = $this->plan_model->create_tree2($pool, $A_1['B'], $A_1['id'], 'B');
                                    echo $A1_2['data'].$A1_2['id']; 
                                    ?>
                                </td>
                                <td align="left" class='text-dark'>
                                    <?php 
                                    $A2_1 = $this->plan_model->create_tree2($pool, $A_2['A'], $A_2['id'], 'A');
                                    echo $A2_1['data'].$A2_1['id']; 
                                    ?>
                                </td>
                                <td align="right" class='text-dark'>
                                    <?php 
                                    $A2_2 = $this->plan_model->create_tree2($pool, $A_2['B'], $A_2['id'], 'B');
                                    echo $A2_2['data'].$A2_2['id']; 
                                    ?>
                                </td>
                                <td align="left" class='text-dark'>
                                    <?php 
                                    $B1_1 = $this->plan_model->create_tree2($pool, $B_1['A'], $B_1['id'], 'A');
                                    echo $B1_1['data'].$B1_1['id']; 
                                    ?>
                                </td>
                                <td align="right" class='text-dark'>
                                    <?php 
                                    $B1_2 = $this->plan_model->create_tree2($pool, $B_1['B'], $B_1['id'], 'B');
                                    echo $B1_2['data'].$B1_2['id']; 
                                    ?>
                                </td>
                                <td align="left" class='text-dark'>
                                    <?php 
                                    $B2_1 = $this->plan_model->create_tree2($pool, $B_2['A'], $B_2['id'], 'A');
                                    echo $B2_1['data'].$B2_1['id']; 
                                    ?>
                                </td>
                                <td align="right" class='text-dark'>
                                    <?php 
                                    $B2_2 = $this->plan_model->create_tree2($pool, $B_2['B'], $B_2['id'], 'B');
                                    echo $B2_2['data'].$B2_2['id']; 
                                    ?>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>
