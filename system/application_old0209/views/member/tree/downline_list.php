<div class="row">
    <div class="col-12">
        <div class="page-title-box d-sm-flex align-items-center justify-content-between">
            <h4 class="mb-sm-0 font-size-18"><?= $title ?></h4>
            <div class="page-title-right">
                <ol class="breadcrumb m-0">
                    <li class="breadcrumb-item"><a href="javascript: void(0);">Team</a></li>
                    <li class="breadcrumb-item active"><?= $title ?></li>
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
                        <h4 class="card-title"><?= $title ?></h4>
                        <p class="card-title-desc"><?= $title ?></p>
                    </div>
                </div>
            </div>
            <div class="card-body table-responsive">
                <table class="table align-middle table-nowrap table-check" id="datatable">
                    <thead class="table-light">
                        <tr>
                            <th>Sr.no</th>
                            <th>Name</th>
                            <th>ID</th>
                            <th>Sponsor Id</th>
                            <th>Package Amount</th>
                            <th>Join Date</th>
                            <th>Activation Date</th>
                            <th>Status</th>
                            <?php if($show_position){ ?>
                            <th>Position</th>
                            <th>Side</th>
                            <th>Left</th>
                            <th>Right</th>
                            <?php } ?>
                        </tr>
                    </thead>
                    <tbody>
                        <?php 
                            $i = 0;
                            if (!empty($ak_data)) {  
                                foreach ($ak_data as $member) { 
                                    $i++;
                                    $member_data = $this->db_model->select_multi('*','member',array('id' => $member));
                                    $prod_sale   = $this->db_model->sum('cost', 'product_sale', array('userid' => $member));
                                    if($prod_sale >= $active_topup and $prod_sale != 0){
                                        $status = 'Active';
                                        $clr    = 'success';
                                        $date   = htmlspecialchars($member_data->activation_date);
                                    }else{
                                        $status = 'Inactive';
                                        $clr    = 'danger';
                                        $date   = 'Not Activated';
                                    }
                                    
                                    if($member_data->A == 0){
                                        $a = '<span class=text-danger>Blank</span>';
                                    }else{
                                        $a = config_item('ID_EXT').$member_data->A;
                                    }
                                    
                                    if($member_data->B == 0){
                                        $b = '<span class=text-danger>Blank</span>';
                                    }else{
                                        $b = config_item('ID_EXT').$member_data->B;
                                    }
                                    
                                    if($member_data->placement_leg == "A"){
                                        $sidee = 'Left';
                                    } else{
                                        $sidee = 'Right';
                                    }
                        ?>
                        <tr>
                            <td><?= $i ?></td>
                            <td><?= $member_data->name ?></td>
                            <td><?= config_item('ID_EXT').$member ?></td>
                            <td><?= config_item('ID_EXT').$member_data->sponsor ?></td>
                            <td><span class="text-<?= $clr ?>"><?= $member_data->topup ?></span></td>
                            <td><?= $member_data->join_time ?></td>
                            <td><span class="text-<?= $clr ?>"><?= $date ?></span></td>
                            <td><span class="text-<?= $clr ?>"><?= $status ?></span></td>
                            <?php if($show_position){ ?>
                            <td><?= config_item('ID_EXT').$member_data->position ?></td>
                            <td><?= $sidee ?></td>
                            <td><?= $a ?></td>
                            <td><?= $b ?></td>
                            <?php } ?>
                        </tr>
                        <?php 
                                }  
                            } else {
                                echo "<tr><td colspan='10' class='text-danger text-center'>No members found</td></tr>";
                            }
                        ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
