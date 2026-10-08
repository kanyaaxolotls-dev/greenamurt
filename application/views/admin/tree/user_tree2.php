<?php

$top_id = $this->uri->segment('3') ? $this->uri->segment('3') : config_item('top_id');

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
    <div class="col"> 
          <div class="card bg-secondary shadow">  
            <div class="card-header bg-white border-0"> 
              <div class="row align-items-center">
                <div class="col-8">
                      <form method="post" class="form-inline" action="<?php echo site_url('tree/user_tree') ?>">
                        <div class="form-group">
                            <input type="text" name="top_id" class="form-control form-control-alternative" placeholder="Enter User id">
                        </div>
                        <div class="form-group"> 
                            <button class="btn btn-sm btn-primary" type="submit">Search</button>
                        </div>
                        
                    </form> 
                </div>
                <div class="col-4 text-right">
                   <span style="float: right;">
                      <img src="<?php echo base_url('uploads/site_img/green.png') ?>" height="25">Active
                      <img src="<?php echo base_url('uploads/site_img/red.png') ?>" height="25">Inactive
                      <img src="<?php echo base_url('uploads/site_img/new.png') ?>" height="25">Blank
                </span>
                </div>
              </div>
            </div>
            <div class="card-body">


    <div class="row">
        <div>
            <h3>RAJLAXMI Tree</h3>
        </div>
        <?php if (config_item('leg') == "1") { ?>
            <div class="hr_divider" style="text-align: center"><p>&nbsp;</p>
                <div class="table-responsive" style="overflow-x: auto; text-align: left">
                    <table align="center" class="table" style="max-width: 500px">
                        <tr>
                            <td colspan="3" class="alert alert-warning"> <?php $U = $this->plan_model->create_tree2($top_id);
                                echo $U['data'] ?></td>
                        </tr>
                        <?php

                        $this->db->select('id, name, total_a, mypv, topup, total_a_pv, my_img')->where('sponsor', $top_id);
                        $data = $this->db->get('member')->result();

                        foreach ($data as $e) {
                            if ($e->topup == "0.00") {
                                $color = 'red';
                            } else {
                                $color = 'green';
                            }

                            $myimg = $e->my_img ? base_url('uploads/' . $e->my_img) : base_url('uploads/site_img/' . $color . '.png');
                            echo '
    <tr>
                            <td></td>
                            <td></td>
                            <td style="border-left: 4px dashed #006aeb;"><span style="color: #006aeb"> -----------></span>
                          <span style="text-align: center"><a href="' . site_url('tree/user_tree/' . $e->id) . '" style="text-decoration: none; color: ' . $color . '; margin: 5px" data-toggle="popover" data-trigger="hover" data-html="true" data-placement="top" title="<div align=\'left\'><strong>' . config_item('ID_EXT') . $e->id . '</strong><hr/>Total Downline:' . ($e->total_a) . '<br/>Total BV: ' . ($e->total_a_pv) . '<br/> My Business: ' . $e->mypv . '</div>"><img class="img-circle" style="max-height: 70px" src="' . $myimg . '"><br/>' . $e->name . '<br/>(' . config_item('ID_EXT') . $e->id . ')</a></span> 
    </td>
                        </tr>';
                        }
                        ?>

                    </table>
                </div>
            </div>
        <?php } ?>
        <?php if (config_item('leg') == "2") { ?>
            <div class="col-sm-12">
                <div class="table-responsive">
                    <table class="table table-borderless">
                        <tbody>
                        <tr>
                            <td colspan="8" align="center"><?php $U = $this->plan_model->create_tree2($top_id);
                                echo $U['data'].$U['id'] ?><br/><img src="<?php echo base_url('uploads/site_img/line_bg.gif') ?>"
                                                            class="img-responsive">
                            </td>
                        </tr>
                        <tr>
                            <td colspan="4"
                                align="center"><?php $A = $this->plan_model->create_tree2($U['A'], $U['id'], 'A');
                                echo $A['data'].$A['id'] ?><br/><img src="<?php echo base_url('uploads/site_img/line_sm.gif') ?>"
                                                            class="img-responsive">
                            </td>
                            <td colspan="4"
                                align="center"><?php $B = $this->plan_model->create_tree2($U['B'], $U['id'], 'B');
                                echo $B['data'].$B['id'] ?><br/><img src="<?php echo base_url('uploads/site_img/line_sm.gif') ?>"
                                                            class="img-responsive">
                            </td>
                        </tr>
                        <tr>
                            <td colspan="2"
                                align="center"><?php $A_1 = $this->plan_model->create_tree2($A['A'], $A['id'], 'A');
                                echo $A_1['data'].$A_1['id'] ?><br/><img src="<?php echo base_url('uploads/site_img/line_sm.gif') ?>"
                                                              class="img-responsive">
                            </td>
                            <td colspan="2"
                                align="center"><?php $A_2 = $this->plan_model->create_tree2($A['B'], $A['id'], 'B');
                                echo $A_2['data'].$A_2['id'] ?><br/><img src="<?php echo base_url('uploads/site_img/line_sm.gif') ?>"
                                                              class="img-responsive">
                            </td>
                            <td colspan="2"
                                align="center"><?php $B_1 = $this->plan_model->create_tree2($B['A'], $B['id'], 'A');
                                echo $B_1['data'].$B_1['id'] ?><br/><img src="<?php echo base_url('uploads/site_img/line_sm.gif') ?>"
                                                              class="img-responsive">
                            </td>
                            <td colspan="2"
                                align="center"><?php $B_2 = $this->plan_model->create_tree2($B['B'], $B['id'], 'B');
                                echo $B_2['data'].$B_2['id'] ?><br/><img src="<?php echo base_url('uploads/site_img/line_sm.gif') ?>"
                                                              class="img-responsive">
                            </td>
                        </tr>
                        <tr>
                            <td align="left"><?php $A1_1 = $this->plan_model->create_tree2($A_1['A'], $A_1['id'], 'A');
                                echo $A1_1['data'] ?></td>
                            <td align="right"><?php $A1_2 = $this->plan_model->create_tree2($A_1['B'], $A_1['id'], 'B');
                                echo $A1_2['data'] ?></td>
                            <td align="left"><?php $A2_1 = $this->plan_model->create_tree2($A_2['A'], $A_2['id'], 'A');
                                echo $A2_1['data'] ?></td>
                            <td align="right"><?php $A2_2 = $this->plan_model->create_tree2($A_2['B'], $A_2['id'], 'B');
                                echo $A2_2['data'] ?></td>
                            <td align="left"><?php $B1_1 = $this->plan_model->create_tree2($B_1['A'], $B_1['id'], 'A');
                                echo $B1_1['data'] ?></td>
                            <td align="right"><?php $B1_2 = $this->plan_model->create_tree2($B_1['B'], $B_1['id'], 'B');
                                echo $B1_2['data'] ?></td>
                            <td align="left"><?php $B2_1 = $this->plan_model->create_tree2($B_2['A'], $B_2['id'], 'A');
                                echo $B2_1['data'] ?></td>
                            <td align="right"><?php $B2_2 = $this->plan_model->create_tree2($B_2['B'], $B_2['id'], 'B');
                                echo $B2_2['data'] ?></td>
                        </tr>
                        </tbody>
                    </table>
                </div>
            </div>
        <?php } ?>
        <?php if (config_item('leg') == "3") { ?>
            <div class="col-sm-12">
                <div class="table-responsive">
                    <table class="table table-borderless">
                        <tbody>
                        <tr>
                            <td colspan="9" align="center"><?php $U = $this->plan_model->create_tree2($top_id);
                                echo $U['data'] ?><br/><img src="<?php echo base_url('uploads/site_img/line_bg.gif') ?>"
                                                            class="img-responsive">
                            </td>
                        </tr>
                        <tr>
                            <td colspan="3"
                                align="center"><?php $A = $this->plan_model->create_tree2($U['A'], $U['id'], 'A');
                                echo $A['data'] ?><br/><img src="<?php echo base_url('uploads/site_img/line_sm.gif') ?>"
                                                            class="img-responsive">
                            </td>
                            <td colspan="3"
                                align="center"><?php $B = $this->plan_model->create_tree2($U['B'], $U['id'], 'B');
                                echo $B['data'] ?><br/><img src="<?php echo base_url('uploads/site_img/line_sm.gif') ?>"
                                                            class="img-responsive">
                            </td>
                            <td colspan="3"
                                align="center"><?php $C = $this->plan_model->create_tree2($U['C'], $U['id'], 'C');
                                echo $C['data'] ?><br/><img src="<?php echo base_url('uploads/site_img/line_sm.gif') ?>"
                                                            class="img-responsive">
                            </td>
                        </tr>
                        <tr>
                            <td colspan="1"
                                align="left"><?php $A_1 = $this->plan_model->create_tree2($A['A'], $A['id'], 'A');
                                echo $A_1['data'] ?><br/>
                            </td>
                            <td colspan="1"
                                align="center"><?php $A_2 = $this->plan_model->create_tree2($A['B'], $A['id'], 'B');
                                echo $A_2['data'] ?><br/>
                            </td>
                            <td colspan="1"
                                align="right"><?php $A_3 = $this->plan_model->create_tree2($A['C'], $A['id'], 'C');
                                echo $A_3['data'] ?><br/>
                            </td>
                            <td colspan="1"
                                align="left"><?php $B_1 = $this->plan_model->create_tree2($B['A'], $B['id'], 'A');
                                echo $B_1['data'] ?><br/>
                            </td>
                            <td colspan="1"
                                align="center"><?php $B_2 = $this->plan_model->create_tree2($B['B'], $B['id'], 'B');
                                echo $B_2['data'] ?><br/>
                            </td>
                            <td colspan="1"
                                align="right"><?php $B_3 = $this->plan_model->create_tree2($B['C'], $B['id'], 'C');
                                echo $B_3['data'] ?><br/>
                            </td>
                            <td colspan="1"
                                align="left"><?php $C_1 = $this->plan_model->create_tree2($C['A'], $C['id'], 'A');
                                echo $C_1['data'] ?><br/>
                            </td>
                            <td colspan="1"
                                align="center"><?php $C_2 = $this->plan_model->create_tree2($C['B'], $C['id'], 'B');
                                echo $C_2['data'] ?><br/>
                            </td>
                            <td colspan="1"
                                align="right"><?php $C_3 = $this->plan_model->create_tree2($C['C'], $C['id'], 'C');
                                echo $C_3['data'] ?><br/>
                            </td>
                        </tr>
                        </tbody>
                    </table>
                </div>
            </div>
        <?php } ?>
        <?php if (config_item('leg') == "4") { ?>
            <div class="col-sm-12">
                <div class="table-responsive">
                    <table class="table table-borderless">
                        <tbody>
                        <tr>
                            <td colspan="16" align="center"><?php $U = $this->plan_model->create_tree2($top_id);
                                echo $U['data'] ?><br/><img src="<?php echo base_url('uploads/site_img/line_bg.gif') ?>"
                                                            class="img-responsive">
                            </td>
                        </tr>
                        <tr>
                            <td colspan="4"
                                align="center"><?php $A = $this->plan_model->create_tree2($U['A'], $U['id'], 'A');
                                echo $A['data'] ?><br/><img src="<?php echo base_url('uploads/site_img/line_sm.gif') ?>"
                                                            class="img-responsive">
                            </td>
                            <td colspan="4"
                                align="center"><?php $B = $this->plan_model->create_tree2($U['B'], $U['id'], 'B');
                                echo $B['data'] ?><br/><img src="<?php echo base_url('uploads/site_img/line_sm.gif') ?>"
                                                            class="img-responsive">
                            </td>
                            <td colspan="4"
                                align="center"><?php $C = $this->plan_model->create_tree2($U['C'], $U['id'], 'C');
                                echo $C['data'] ?><br/><img src="<?php echo base_url('uploads/site_img/line_sm.gif') ?>"
                                                            class="img-responsive">
                            </td>
                            <td colspan="4"
                                align="center"><?php $D = $this->plan_model->create_tree2($U['D'], $U['id'], 'D');
                                echo $D['data'] ?><br/><img src="<?php echo base_url('uploads/site_img/line_sm.gif') ?>"
                                                            class="img-responsive">
                            </td>
                        </tr>
                        <tr>
                            <td colspan="1"
                                align="left"><?php $A_1 = $this->plan_model->create_tree2($A['A'], $A['id'], 'A');
                                echo $A_1['data'] ?><br/>
                            </td>
                            <td colspan="1"
                                align="center"><?php $A_2 = $this->plan_model->create_tree2($A['B'], $A['id'], 'B');
                                echo $A_2['data'] ?><br/>
                            </td>
                            <td colspan="1"
                                align="center"><?php $A_3 = $this->plan_model->create_tree2($A['C'], $A['id'], 'C');
                                echo $A_3['data'] ?><br/>
                            </td>
                            <td colspan="1"
                                align="right"><?php $A_4 = $this->plan_model->create_tree2($A['D'], $A['id'], 'D');
                                echo $A_4['data'] ?><br/>
                            </td>
                            <td colspan="1"
                                align="left"><?php $B_1 = $this->plan_model->create_tree2($B['A'], $B['id'], 'A');
                                echo $B_1['data'] ?><br/>
                            </td>
                            <td colspan="1"
                                align="center"><?php $B_2 = $this->plan_model->create_tree2($B['B'], $B['id'], 'B');
                                echo $B_2['data'] ?><br/>
                            </td>
                            <td colspan="1"
                                align="center"><?php $B_3 = $this->plan_model->create_tree2($B['C'], $B['id'], 'C');
                                echo $B_3['data'] ?><br/>
                            </td>
                            <td colspan="1"
                                align="right"><?php $B_4 = $this->plan_model->create_tree2($B['D'], $B['id'], 'D');
                                echo $B_4['data'] ?><br/>
                            </td>
                            <td colspan="1"
                                align="left"><?php $C_1 = $this->plan_model->create_tree2($C['A'], $C['id'], 'A');
                                echo $C_1['data'] ?><br/>
                            </td>
                            <td colspan="1"
                                align="center"><?php $C_2 = $this->plan_model->create_tree2($C['B'], $C['id'], 'B');
                                echo $C_2['data'] ?><br/>
                            </td>
                            <td colspan="1"
                                align="center"><?php $C_3 = $this->plan_model->create_tree2($C['C'], $C['id'], 'C');
                                echo $C_3['data'] ?><br/>
                            </td>
                            <td colspan="1"
                                align="right"><?php $C_4 = $this->plan_model->create_tree2($C['D'], $C['id'], 'D');
                                echo $C_4['data'] ?><br/>
                            </td>

                            <td colspan="1"
                                align="left"><?php $D_1 = $this->plan_model->create_tree2($D['A'], $D['id'], 'A');
                                echo $D_1['data'] ?><br/>
                            </td>
                            <td colspan="1"
                                align="center"><?php $D_2 = $this->plan_model->create_tree2($D['B'], $D['id'], 'B');
                                echo $D_2['data'] ?><br/>
                            </td>
                            <td colspan="1"
                                align="center"><?php $D_3 = $this->plan_model->create_tree2($D['C'], $D['id'], 'C');
                                echo $D_3['data'] ?><br/>
                            </td>
                            <td colspan="1"
                                align="right"><?php $D_4 = $this->plan_model->create_tree2($D['D'], $D['id'], 'D');
                                echo $D_4['data'] ?><br/>
                            </td>
                        </tr>
                        </tbody>
                    </table>
                </div>
            </div>
        <?php } ?>
        <?php if (config_item('leg') == "5") { ?>
            <div class="col-sm-12">
                <div class="table-responsive">
                    <table class="table table-borderless">
                        <tbody>
                        <tr>
                            <td colspan="20" align="center"><?php $U = $this->plan_model->create_tree2($top_id);
                                echo $U['data'] ?><br/><img src="<?php echo base_url('uploads/site_img/line_bg.gif') ?>"
                                                            class="img-responsive">
                            </td>
                        </tr>
                        <tr>
                            <td colspan="4"
                                align="center"><?php $A = $this->plan_model->create_tree2($U['A'], $U['id'], 'A');
                                echo $A['data'] ?><br/>
                            </td>
                            <td colspan="4"
                                align="center"><?php $B = $this->plan_model->create_tree2($U['B'], $U['id'], 'B');
                                echo $B['data'] ?><br/>
                            </td>
                            <td colspan="4"
                                align="center"><?php $C = $this->plan_model->create_tree2($U['C'], $U['id'], 'C');
                                echo $C['data'] ?><br/>
                            </td>
                            <td colspan="4"
                                align="center"><?php $D = $this->plan_model->create_tree2($U['D'], $U['id'], 'D');
                                echo $D['data'] ?><br/>
                            </td>
                            <td colspan="4"
                                align="center"><?php $E = $this->plan_model->create_tree2($U['E'], $U['id'], 'E');
                                echo $E['data'] ?><br/>
                            </td>
                        </tr>
                        </tbody>
                    </table>
                </div>
            </div>
        <?php } ?>
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