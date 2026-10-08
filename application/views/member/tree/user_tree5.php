<?php


 $top_id = $this->uri->segment('3') ? $this->uri->segment('3') : $this->session->user_id;

?>
<style>
.popover-content {
      width: 15em; 
    font-size: 15px; 
    display:flex;
    flex-direction:column;
    justify-content: center;
    color:darkblue;
}
.popover-item {
    display: flex;
    justify-content: space-between;
}

.popover-title {
    flex-grow: 1;
    margin-right: 5px; /* Adjust margin as needed */
}

.popover-value {
    flex-shrink: 0;
}

</style>

                        <div class="row">
                            <div class="col-12">
                                <div class="page-title-box d-sm-flex align-items-center justify-content-between">
                                    <h4 class="mb-sm-0 font-size-18">RAJSHAKTI Tree</h4>

                                    <div class="page-title-right">
                                        <ol class="breadcrumb m-0">
                                            <li class="breadcrumb-item"><a href="javascript: void(0);">Tree</a></li>
                                            <li class="breadcrumb-item active">View</li>
                                        </ol>
                                    </div>

                                </div>
                            </div>
                        </div>

                        <div class="row">
                                <div class="col-12">
                                        <div class=" bg-white"  >

                                                <div class="card-header">
                                                         <div class="row mb-2">
                                                            <div class="col-sm-4">
                                                                <h4 class="card-title">Tree View</h4>
                                                                <p class="card-title-desc">This is my Tree View of team who complete 60 or more pairs </p>
                                                            </div>
                                                            <div class="col-sm-8">
                                                                <div class="text-sm-end">
                                                                    <span class="p-2 text-dark">
                                                                      <img src="<?php echo base_url('uploads/site_img/green.png') ?>" height="25">Active
                                                                      <img src="<?php echo base_url('uploads/site_img/red.png') ?>" height="25">Inactive
                                                                      <img src="<?php echo base_url('uploads/site_img/new.png') ?>" height="25">Blank
                                                                    </span>
                                                                </div>
                                                            </div><!-- end col-->
                                                        </div>
                                                    </div>
                                                    <div class="card-body">
                                                          
                                                                <?php if (config_item('leg') == "1") { ?>
                                                               
                                                                    <div class="table-responsive">
                                                                            <table class="table align-items-center">
                                                                            <thead class="thead-light">
                                                                                <tr>
                                                                                    <td colspan="3"> <?php $U = $this->plan_model->create_tree5($top_id);
                                                                                        echo $U['data'] ?></td>
                                                                                </tr>
                                                                            </thead>
                                                                            <?php

                                                                            $this->db->select('id, name, total_a, mypv, topup, total_a_pv, my_img')->where('sponsor', $top_id);
                                                                            $data = $this->db->get('member')->result();

                                                                            foreach ($data as $e) {
                                                                                $sp_count=$this->db_model->count_all('member', array('sponsor' =>$e->id));
                                                                               
                                                                                if ($sp_count < 2) {
                                                                                    $color = 'red';
                                                                                } else {
                                                                                    $color = 'green';
                                                                                }

                                                                                $myimg = $e->my_img ? base_url('uploads/' . $e->my_img) : base_url('uploads/site_img/' . $color . '.png');
                                                                                echo '
                                                                                <tr> <td style="border-left: 4px dashed #006aeb;"><span style="color: #006aeb"> -----------></span>
                                                                                  <span style="text-align: center"><a href="' . site_url('tree/my_tree/' . $e->id) . '" style="text-decoration: none; color: ' . $color . '; margin: 5px" data-toggle="popover" data-trigger="hover" data-html="true" data-placement="top" title="<div align=\'left\'><strong>' . config_item('ID_EXT') . $e->id . '</strong><hr/>Total Downline:' . ($e->total_a) . '<br/>Total BV: ' . ($e->total_a_pv) . '<br/> My Business: ' . $e->mypv . '</div>"><img class="img-circle" style="max-height: 70px" src="' . $myimg . '"><br/>' . $e->name . '<br/>(' . config_item('ID_EXT') . $e->id . ')</a></span> 
                                                                                </td>
                                                                                </tr>';
                                                                            }
                                                                            ?> 
                                                                        </table>   
                                                                    </div>
                                                                <?php } ?>
                                                                <!-- end of 1 leg tree <!-->
                                                                <!-- start of 2 leg <!-->
                                                                  <?php if (config_item('leg') == "2") { ?>
                                                                     <div class="table-responsive bg-white">
                                                                                <table class="table align-items-center">
                                                                                <thead class="thead-light">
                                                                                <tr>
                                                                                    <td colspan="8" align="center" class="text-dark">
                                                                                         <?php if($this->uri->segment('3')>0){ ?> <a href="#" onclick="history.back();">
                                                                                            <i class="fas fa-angle-double-down sm"></i></a>
                                                                                      
                                                                                         <?php } ?><br>
                                                                                        <?php $U = $this->plan_model->create_tree5($top_id);
                                                                                        echo $U['data'].$U['id'] ?>
                                                                                       
                                                                                        <br/><img src="<?php echo base_url('uploads/site_img/line_bg.gif') ?>" class="img-responsive">


                                                                                    </td>
                                                                                </tr>
                                                                                </thead>
                                                             
                                                                                <tbody> 
                                                                                <tr> 
                                                                                    <td colspan="4"
                                                                                        align="center" class='text-dark'>
                                                                                        <?php $A = $this->plan_model->create_tree5($U['A'], $U['id'], 'A');
                                                                                        echo $A['data'].$A['id'] ?><br/><img src="<?php echo base_url('uploads/site_img/line_sm.gif') ?>"class="img-responsive" style="width: 50%;">
                                                                                    </td>
                                                                                    <td colspan="4"
                                                                                        align="center" class='text-dark'><?php $B = $this->plan_model->create_tree5($U['B'], $U['id'], 'B');
                                                                                        echo $B['data'].$B['id'] ?><br/><img src="<?php echo base_url('uploads/site_img/line_sm.gif') ?>" class="img-responsive" style="width: 50%;">
                                                                                    </td>

                                                                                </tr>
                                                                                <tr>
                                                                                    <td colspan="2"
                                                                                        align="center" class='text-dark'><?php $A_1 = $this->plan_model->create_tree5($A['A'], $A['id'], 'A');
                                                                                        echo $A_1['data'].$A_1['id'] ?><br/><img src="<?php echo base_url('uploads/site_img/line_sm.gif') ?>" class="img-responsive" style="width: 80%;">

                                                                                    </td>
                                                                                    <td colspan="2"
                                                                                        align="center" class='text-dark'><?php $A_2 = $this->plan_model->create_tree5($A['B'], $A['id'], 'B');
                                                                                        echo $A_2['data'].$A_2['id'] ?><br/><img src="<?php echo base_url('uploads/site_img/line_sm.gif') ?>" class="img-responsive" style="width: 80%;">
                                                                                    </td>
                                                                                    <td colspan="2"
                                                                                        align="center" class='text-dark'><?php $B_1 = $this->plan_model->create_tree5($B['A'], $B['id'], 'A');
                                                                                        echo $B_1['data'].$B_1['id'] ?><br/><img src="<?php echo base_url('uploads/site_img/line_sm.gif') ?>"
                                                                                                                      class="img-responsive" style="width: 80%;">
                                                                                    </td>
                                                                                    <td colspan="2"
                                                                                        align="center" class='text-dark'><?php $B_2 = $this->plan_model->create_tree5($B['B'], $B['id'], 'B');
                                                                                        echo $B_2['data'].$B_2['id'] ?><br/><img src="<?php echo base_url('uploads/site_img/line_sm.gif') ?>"
                                                                                                                      class="img-responsive" style="width: 80%;">
                                                                                    </td>
                                                                                </tr>
                                                                                <tr>
                                                                                    <td align="left" class='text-dark'><?php $A1_1 = $this->plan_model->create_tree5($A_1['A'], $A_1['id'], 'A');
                                                                                        echo $A1_1['data'].$A1_1['id'] ?></td>
                                                                                    <td align="right" class='text-dark'><?php $A1_2 = $this->plan_model->create_tree5($A_1['B'], $A_1['id'], 'B');
                                                                                        echo $A1_2['data'].$A1_2['id']?></td>
                                                                                    <td align="left" class='text-dark'><?php $A2_1 = $this->plan_model->create_tree5($A_2['A'], $A_2['id'], 'A');
                                                                                        echo $A2_1['data'].$A2_1['id']?></td>
                                                                                    <td align="right" class='text-dark'><?php $A2_2 = $this->plan_model->create_tree5($A_2['B'], $A_2['id'], 'B');
                                                                                        echo $A2_2['data'].$A2_2['id'] ?></td>
                                                                                    <td align="left" class='text-dark'><?php $B1_1 = $this->plan_model->create_tree5($B_1['A'], $B_1['id'], 'A');
                                                                                        echo $B1_1['data'].$B1_1['id'] ?></td>
                                                                                    <td align="right" class='text-dark'><?php $B1_2 = $this->plan_model->create_tree5($B_1['B'], $B_1['id'], 'B');
                                                                                        echo $B1_2['data'].$B1_2['id'] ?></td>
                                                                                    <td align="left" class='text-dark'><?php $B2_1 = $this->plan_model->create_tree5($B_2['A'], $B_2['id'], 'A');
                                                                                        echo $B2_1['data'].$B2_1['id'] ?></td>
                                                                                    <td align="right" class='text-dark'><?php $B2_2 = $this->plan_model->create_tree5($B_2['B'], $B_2['id'], 'B');
                                                                                        echo $B2_2['data'].$B2_2['id'] ?></td>
                                                                                </tr>
                                                                                </tbody>
                                                                            </table>
                                                                        </div>
                                                                    </div>
                                                                <?php } ?>
                                                                <!-- end of 2 leg <!-->

                                                                 <!-- Start of 3 leg <!-->
                                                                 <?php if (config_item('leg') == "3") { ?>
                                                                    <div class="col-sm-12">
                                                                        <div class="table-responsive">
                                                                                <table class="table align-items-center">
                                                                                <thead class="thead-light">
                                                                                <tr>
                                                                                    <td colspan="9" align="center"><?php $U = $this->plan_model->create_tree5($top_id);
                                                                                        echo $U['data'].$U['id'] ?><br/><img src="<?php echo base_url('uploads/site_img/line_bg.gif') ?>"
                                                                                                                    class="img-responsive">
                                                                                    </td>
                                                                                </tr>
                                                                            </thead>
                                                                                 <tbody>
                                                                                <tr>
                                                                                    <td colspan="3"
                                                                                        align="center"><?php $A = $this->plan_model->create_tree5($U['A'], $U['id'], 'A');
                                                                                        echo $A['data'].$A['id'] ?><br/><img src="<?php echo base_url('uploads/site_img/line_sm.gif') ?>"
                                                                                                                    class="img-responsive">
                                                                                    </td>
                                                                                    <td colspan="3"
                                                                                        align="center"><?php $B = $this->plan_model->create_tree5($U['B'], $U['id'], 'B');
                                                                                        echo $B['data'].$B['id'] ?><br/><img src="<?php echo base_url('uploads/site_img/line_sm.gif') ?>"
                                                                                                                    class="img-responsive">
                                                                                    </td>
                                                                                    <td colspan="3"
                                                                                        align="center"><?php $C = $this->plan_model->create_tree5($U['C'], $U['id'], 'C');
                                                                                        echo $C['data'].$C['id'] ?><br/><img src="<?php echo base_url('uploads/site_img/line_sm.gif') ?>"
                                                                                                                    class="img-responsive">
                                                                                    </td>
                                                                                </tr>
                                                                                <tr>
                                                                                    <td colspan="1"
                                                                                        align="left"><?php $A_1 = $this->plan_model->create_tree5($A['A'], $A['id'], 'A');
                                                                                        echo $A_1['data'].$A_1['id'] ?><br/>
                                                                                    </td>
                                                                                    <td colspan="1"
                                                                                        align="center"><?php $A_2 = $this->plan_model->create_tree5($A['B'], $A['id'], 'B');
                                                                                        echo $A_2['data'].$A_2['id'] ?><br/>
                                                                                    </td>
                                                                                    <td colspan="1"
                                                                                        align="right"><?php $A_3 = $this->plan_model->create_tree5($A['C'], $A['id'], 'C');
                                                                                        echo $A_3['data'].$A_3['id'] ?><br/>
                                                                                    </td>
                                                                                    <td colspan="1"
                                                                                        align="left"><?php $B_1 = $this->plan_model->create_tree5($B['A'], $B['id'], 'A');
                                                                                        echo $B_1['data'].$B_1['id'] ?><br/>
                                                                                    </td>
                                                                                    <td colspan="1"
                                                                                        align="center"><?php $B_2 = $this->plan_model->create_tree5($B['B'], $B['id'], 'B');
                                                                                        echo $B_2['data'].$B_2['id'] ?><br/>
                                                                                    </td>
                                                                                    <td colspan="1"
                                                                                        align="right"><?php $B_3 = $this->plan_model->create_tree5($B['C'], $B['id'], 'C');
                                                                                        echo $B_3['data'].$B_3['id'] ?><br/>
                                                                                    </td>
                                                                                    <td colspan="1"
                                                                                        align="left"><?php $C_1 = $this->plan_model->create_tree5($C['A'], $C['id'], 'A');
                                                                                        echo $C_1['data'].$C_1['id'] ?><br/>
                                                                                    </td>
                                                                                    <td colspan="1"
                                                                                        align="center"><?php $C_2 = $this->plan_model->create_tree5($C['B'], $C['id'], 'B');
                                                                                        echo $C_2['data'].$C_2['id'] ?><br/>
                                                                                    </td>
                                                                                    <td colspan="1"
                                                                                        align="right"><?php $C_3 = $this->plan_model->create_tree5($C['C'], $C['id'], 'C');
                                                                                        echo $C_3['data'].$C_3['id'] ?><br/>
                                                                                    </td>
                                                                                </tr>
                                                                                </tbody>
                                                                            </table>
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                                <?php } ?>
                                                                <!-- end of 3 leg <!-->

                                                                <!-- Start of 4 leg <!-->
                                                                 <?php if (config_item('leg') == "4") { ?>
                                                                        <div class="col-sm-12">
                                                                            <div class="table-responsive">
                                                                                    <table class="table align-items-center table-flush">

                                                                                    <tbody>
                                                                                    <tr>
                                                                                        <td colspan="16" align="center"><?php $U = $this->plan_model->create_tree5($top_id);
                                                                                            echo $U['data'].$U['id']  ?><br/><img src="<?php echo base_url('uploads/site_img/line_bg.gif') ?>"
                                                                                                                        class="img-responsive">
                                                                                        </td>
                                                                                    </tr>
                                                                                    <tr>
                                                                                        <td colspan="4"
                                                                                            align="center"><?php $A = $this->plan_model->create_tree5($U['A'], $U['id'], 'A');
                                                                                            echo $A['data'].$A['id']  ?><br/><img src="<?php echo base_url('uploads/site_img/line_sm.gif') ?>"
                                                                                                                        class="img-responsive">
                                                                                        </td>
                                                                                        <td colspan="4"
                                                                                            align="center"><?php $B = $this->plan_model->create_tree5($U['B'], $U['id'], 'B');
                                                                                            echo $B['data'].$B['id']  ?><br/><img src="<?php echo base_url('uploads/site_img/line_sm.gif') ?>"
                                                                                                                        class="img-responsive">
                                                                                        </td>
                                                                                        <td colspan="4"
                                                                                            align="center"><?php $C = $this->plan_model->create_tree5($U['C'], $U['id'], 'C');
                                                                                            echo $C['data'].$C['id']  ?><br/><img src="<?php echo base_url('uploads/site_img/line_sm.gif') ?>"
                                                                                                                        class="img-responsive">
                                                                                        </td>
                                                                                        <td colspan="4"
                                                                                            align="center"><?php $D = $this->plan_model->create_tree5($U['D'], $U['id'], 'D');
                                                                                            echo $D['data'].$D['id']  ?><br/><img src="<?php echo base_url('uploads/site_img/line_sm.gif') ?>"
                                                                                                                        class="img-responsive">
                                                                                        </td>
                                                                                    </tr>
                                                                                    <tr>
                                                                                        <td colspan="1"
                                                                                            align="left"><?php $A_1 = $this->plan_model->create_tree5($A['A'], $A['id'], 'A');
                                                                                            echo $A_1['data'].$A_1['id']  ?><br/>
                                                                                        </td>
                                                                                        <td colspan="1"
                                                                                            align="center"><?php $A_2 = $this->plan_model->create_tree5($A['B'], $A['id'], 'B');
                                                                                            echo $A_2['data'].$A_2['id']  ?><br/>
                                                                                        </td>
                                                                                        <td colspan="1"
                                                                                            align="center"><?php $A_3 = $this->plan_model->create_tree5($A['C'], $A['id'], 'C');
                                                                                            echo $A_3['data'].$A_3['id'] ?><br/>
                                                                                        </td>
                                                                                        <td colspan="1"
                                                                                            align="right"><?php $A_4 = $this->plan_model->create_tree5($A['D'], $A['id'], 'D');
                                                                                            echo $A_4['data'].$A_4['id'] ?><br/>
                                                                                        </td>
                                                                                        <td colspan="1"
                                                                                            align="left"><?php $B_1 = $this->plan_model->create_tree5($B['A'], $B['id'], 'A');
                                                                                            echo $B_1['data'].$B_1['id'] ?><br/>
                                                                                        </td>
                                                                                        <td colspan="1"
                                                                                            align="center"><?php $B_2 = $this->plan_model->create_tree5($B['B'], $B['id'], 'B');
                                                                                            echo $B_2['data'].$B_2['id'] ?><br/>
                                                                                        </td>
                                                                                        <td colspan="1"
                                                                                            align="center"><?php $B_3 = $this->plan_model->create_tree5($B['C'], $B['id'], 'C');
                                                                                            echo $B_3['data'].$B_3['id'] ?><br/>
                                                                                        </td>
                                                                                        <td colspan="1"
                                                                                            align="right"><?php $B_4 = $this->plan_model->create_tree5($B['D'], $B['id'], 'D');
                                                                                            echo $B_4['data'].$B_4['id'] ?><br/>
                                                                                        </td>
                                                                                        <td colspan="1"
                                                                                            align="left"><?php $C_1 = $this->plan_model->create_tree5($C['A'], $C['id'], 'A');
                                                                                            echo $C_1['data'].$C_1['id'] ?><br/>
                                                                                        </td>
                                                                                        <td colspan="1"
                                                                                            align="center"><?php $C_2 = $this->plan_model->create_tree5($C['B'], $C['id'], 'B');
                                                                                            echo $C_2['data'].$C_2['id'] ?><br/>
                                                                                        </td>
                                                                                        <td colspan="1"
                                                                                            align="center"><?php $C_3 = $this->plan_model->create_tree5($C['C'], $C['id'], 'C');
                                                                                            echo $C_3['data'].$C_3['id'] ?><br/>
                                                                                        </td>
                                                                                        <td colspan="1"
                                                                                            align="right"><?php $C_4 = $this->plan_model->create_tree5($C['D'], $C['id'], 'D');
                                                                                            echo $C_4['data'].$C_4['id'] ?><br/>
                                                                                        </td>

                                                                                        <td colspan="1"
                                                                                            align="left"><?php $D_1 = $this->plan_model->create_tree5($D['A'], $D['id'], 'A');
                                                                                            echo $D_1['data'].$D_1['id'] ?><br/>
                                                                                        </td>
                                                                                        <td colspan="1"
                                                                                            align="center"><?php $D_2 = $this->plan_model->create_tree5($D['B'], $D['id'], 'B');
                                                                                            echo $D_2['data'].$D_2['id'] ?><br/>
                                                                                        </td>
                                                                                        <td colspan="1"
                                                                                            align="center"><?php $D_3 = $this->plan_model->create_tree5($D['C'], $D['id'], 'C');
                                                                                            echo $D_3['data'].$D_3['id'] ?><br/>
                                                                                        </td>
                                                                                        <td colspan="1"
                                                                                            align="right"><?php $D_4 = $this->plan_model->create_tree5($D['D'], $D['id'], 'D');
                                                                                            echo $D_4['data'].$D_4['id'] ?><br/>
                                                                                        </td>
                                                                                    </tr>
                                                                                    </tbody>
                                                                                </table>
                                                                            </div>
                                                                        </div>
                                                                    </div>
                                                                    <?php } ?>
                                                                  
                                                                <!-- end of 4 leg -->
                                                                <!-- start of 5 leg -->
                                                                      <?php if (config_item('leg') == "5") { ?>
                                                                        <div class="col-sm-12">
                                                                            <div class="table-responsive">
                                                                                    <table class="table align-items-center table-flush">
                                                                                   
                                                                                    <tbody>
                                                                                    <tr>
                                                                                        <td colspan="20" align="center"><?php $U = $this->plan_model->create_tree5($top_id);
                                                                                            echo $U['data'].$U['id']  ?><br/><img src="<?php echo base_url('uploads/site_img/line_bg.gif') ?>"
                                                                                                                        class="img-responsive">
                                                                                        </td>
                                                                                    </tr> 
                                                                                    <tr>
                                                                                        <td colspan="4"
                                                                                            align="center"><?php $A = $this->plan_model->create_tree5($U['A'], $U['id'], 'A');
                                                                                            echo $A['data'].$A['id']  ?><br/>
                                                                                        </td>
                                                                                        <td colspan="4"
                                                                                            align="center"><?php $B = $this->plan_model->create_tree5($U['B'], $U['id'], 'B');
                                                                                            echo $B['data'].$B['id']  ?><br/>
                                                                                        </td>
                                                                                        <td colspan="4"
                                                                                            align="center"><?php $C = $this->plan_model->create_tree5($U['C'], $U['id'], 'C');
                                                                                            echo $C['data'].$C['id']  ?><br/>
                                                                                        </td>
                                                                                        <td colspan="4"
                                                                                            align="center"><?php $D = $this->plan_model->create_tree5($U['D'], $U['id'], 'D');
                                                                                            echo $D['data'].$D['id']  ?><br/>
                                                                                        </td>
                                                                                        <td colspan="4"
                                                                                            align="center"><?php $E = $this->plan_model->create_tree5($U['E'], $U['id'], 'E');
                                                                                            echo $E['data'].$E['id']  ?><br/>
                                                                                        </td>
                                                                                    </tr>
                                                                                    </tbody>
                                                                                </table>
                                                                            </div>
                                                                        </div>
                                                                    <?php } ?>
                                                                <!-- end of 5 leg -->

                                                    </div>
                                        </div>
                                </div>
                        </div>
        </div>
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script>
$(document).ready(function(){
    $('.hover-element').popover({
        trigger: 'hover',
        html: true,
        placement: 'top',
        content: function () {
            var contentData = $(this).data('content').split('|');
            var contentHtml = '<div class="popover-content"> <h4 class="text-center text-danger">User Details<hr></h4>';
            contentData.forEach(function(item) {
                var parts = item.split(':');
                var title = parts[0].trim();
                var value = parts[1].trim();
                contentHtml += '<div class="popover-item">' +
                                   '<div class="popover-title">' + title + '</div>' +
                                   '<div class="popover-value">' + value + '</div>' +
                               '</div>';
            });
            contentHtml += '</div>';
            return contentHtml;
        }
    });
});

</script>



</div><!-- end of panel-->
