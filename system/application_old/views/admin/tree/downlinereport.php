<?php

$top_id = $this->uri->segment('3') ? $this->uri->segment('3') : config_item('top_id');
$data   = $this->db_model->select_multi('total_a, total_b, total_c, total_d, total_e, paid_a, paid_b, paid_c, paid_d, paid_e, rank, mypv, total_a_pv, total_b_pv, total_c_pv, A, B, C, D, E', 'member', array('id' => htmlentities($top_id)));
?>
<div class="col"> 
          <div class="card bg-secondary shadow">   
            <div class="card-header bg-white border-0"> 
              <div class="row align-items-center">
                <div class="col-lg-6">
                  <h3 class="mb-0"><?php echo $title;?></h3>
                </div>
                <div class="col-lg-6 text-right"> 
                   Company Id: <?php echo $top_id ?>
                </div>
              </div>
            </div>
            <div class="card-body">
        <div class="row">
        <?php if (config_item('leg') == "1") { ?>
          <div class="table-responsive">
              <table class="table align-items-center table-flush">
                    <thead>
                    <tr>
                        <th scope="col">Type </th>
                         <th scope="col">Under Me</th>
                    </tr>
                    </thead>
                    <tbody>
                    <tr>
                        <td style="color: #90111a;">Total Users:</td>
                        <td><?php echo $data->total_a ?> </td>
                    </tr>
                    <tr>
                        <td style="color: #90111a;">Total Business Value:</td>
                        <td><?php echo $data->total_a_pv ?></td>
                    </tr>
                    <tr>
                        <td style="color: #90111a;">Own Purchase Value:</td>
                        <td colspan="2"><?php echo $data->mypv ?> PV/BV</td>
                    </tr>
                    </tbody>
                </table>
            </div>
        <?php } ?>
        <?php if (config_item('leg') == "2") { ?>
             <div class="table-responsive">
              <table class="table align-items-center table-flush">
                    <thead>
                    <tr>
                        <th scope="col">Type</th>
                         <th scope="col">Left Side</th>
                         <th scope="col">Right Side</th>
                    </tr>
                    </thead>
                    <tbody>
                    <tr>
                          <td>Total Users:</td>
                        <td><?php echo $data->total_a ?> </td>
                        <td><?php echo $data->total_b ?></td>
                    </tr>
                    <tr>
                        <td>Total Business Value:</td>
                        <td><?php echo $data->total_a_pv ?></td>
                        <td><?php echo $data->total_b_pv ?></td>
                    </tr>
                    <tr>
                       <td>Total Carry Forward:</td>
                        <td><?php echo($data->total_a - $data->paid_a) ?></td>
                        <td><?php echo($data->total_b - $data->paid_b) ?></td>
                    </tr>
                    <tr>
                        <td>Downline ID:</td>
                        <td><?php echo $data->A ? config_item('ID_EXT') . $data->A : 'No User' ?></td>
                        <td><?php echo $data->B ? config_item('ID_EXT') . $data->B : 'No User' ?></td>
                    </tr>
                    <tr>
                        <td>Own Purchase Value:</td>
                        <td colspan="2"><?php echo $data->mypv ?> PV/BV</td>
                    </tr>
                    </tbody>
                  
                </table>
            </div>
        <?php } ?>
        <?php if (config_item('leg') == "3") { ?>
            <div class="table-responsive">
                <table class="table table-bordered">
                    <thead>
                    <tr>
                        <td></td>
                        <td>A Side</td>
                        <td>B Side</td>
                        <td>C Side</td>
                    </tr>
                    </thead>
                    <tbody>
                    <tr>
                        <td>Total Users:</td>
                        <td><?php echo $data->total_a ?> </td>
                        <td><?php echo $data->total_b ?></td>
                        <td><?php echo $data->total_c ?></td>
                    </tr>
                    <tr>
                        <td>Total Business Value:</td>
                        <td><?php echo $data->total_a_pv ?></td>
                        <td><?php echo $data->total_b_pv ?></td>
                        <td><?php echo $data->total_c_pv ?></td>
                    </tr>
                    <tr>
                        <td>Downline ID:</td>
                        <td><?php echo $data->A ? config_item('ID_EXT') . $data->A : 'No User' ?></td>
                        <td><?php echo $data->B ? config_item('ID_EXT') . $data->B : 'No User' ?></td>
                        <td><?php echo $data->C ? config_item('ID_EXT') . $data->C : 'No User' ?></td>
                    </tr>
                    <tr>
                        <td>Own Purchase Value:</td>
                        <td colspan="2"><?php echo $data->mypv ?> PV/BV</td>
                    </tr>
                    </tbody>
                </table>
            </div>
        <?php } ?>
        <?php if (config_item('leg') == "4") { ?>
            <div class="table-responsive">
                <table class="table table-bordered">
                    <thead>
                    <tr>
                        <td></td>
                        <td>A Side</td>
                        <td>B Side</td>
                        <td>C Side</td>
                        <td>D Side</td>
                    </tr>
                    </thead>
                    <tbody>
                    <tr>
                        <td>Total Users:</td>
                        <td><?php echo $data->total_a ?> </td>
                        <td><?php echo $data->total_b ?></td>
                        <td><?php echo $data->total_c ?></td>
                        <td><?php echo $data->total_d ?></td>
                    </tr>
                    <tr>
                        <td>Downline ID:</td>
                        <td><?php echo $data->A ? config_item('ID_EXT') . $data->A : 'No User' ?></td>
                        <td><?php echo $data->B ? config_item('ID_EXT') . $data->B : 'No User' ?></td>
                        <td><?php echo $data->C ? config_item('ID_EXT') . $data->C : 'No User' ?></td>
                        <td><?php echo $data->D ? config_item('ID_EXT') . $data->D : 'No User' ?></td>
                    </tr>
                    </tbody>
                </table>
            </div>
        <?php } ?>
        <?php if (config_item('leg') == "5") { ?>
            <div class="table-responsive">
                <table class="table table-bordered">
                    <thead>
                    <tr>
                        <td></td>
                        <td>A Side</td>
                        <td>B Side</td>
                        <td>C Side</td>
                        <td>D Side</td>
                        <td>E Side</td>
                    </tr>
                    </thead>
                    <tbody>
                    <tr>
                        <td>Total Users:</td>
                        <td><?php echo $data->total_a ?> </td>
                        <td><?php echo $data->total_b ?></td>
                        <td><?php echo $data->total_c ?></td>
                        <td><?php echo $data->total_d ?></td>
                        <td><?php echo $data->total_e ?></td>
                    </tr>
                    <tr>
                        <td>Downline ID:</td>
                        <td><?php echo $data->A ? config_item('ID_EXT') . $data->A : 'No User' ?></td>
                        <td><?php echo $data->B ? config_item('ID_EXT') . $data->B : 'No User' ?></td>
                        <td><?php echo $data->C ? config_item('ID_EXT') . $data->C : 'No User' ?></td>
                        <td><?php echo $data->D ? config_item('ID_EXT') . $data->D : 'No User' ?></td>
                        <td><?php echo $data->E ? config_item('ID_EXT') . $data->E : 'No User' ?></td>
                    </tr>
                    </tbody>
                </table>
            </div>
        <?php } ?>
    </div>
   
</div>
<div class="card-footer">
    <a href="<?php echo site_url('admin') ?>" class="btn btn-sm btn-primary">&larr; Go Back</a>
</div>
</div>
</div>