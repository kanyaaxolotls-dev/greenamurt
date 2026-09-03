
                <div class="container-fluid"> 
                        <!-- start page title -->
                        <div class="row">
                            <div class="col-12">
                                <div class="page-title-box d-sm-flex align-items-center justify-content-between">
                                    <h4 class="mb-sm-0 font-size-18"><?php echo $title ?></h4>

                                    <div class="page-title-right">
                                        <ol class="breadcrumb m-0">
                                            <li class="breadcrumb-item"><a href="javascript: void(0);"><?php echo $title ?></a></li>
                                            <li class="breadcrumb-item active"><?php echo $breadcrumb; ?></li>
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
                                                            
                                                            </div>
                                                            <!--<div class="col-sm-8">-->
                                                            <!--    <div class="text-sm-end">-->
                                                            <!--        <button type="button" class="btn btn-success btn-rounded waves-effect waves-light mb-2 me-2"><i class="mdi mdi-plus me-1"></i> Add New Member</button>-->
                                                            <!--    </div>-->
                                                            <!--</div>-->
                                                            <!-- end col-->
                                                        </div>
                                                    </div>
                                                    <div class="card-body">
                                                    <div class="card-body table-responsive">
                                                            <table id="datatable-buttons" class="table align-middle table-nowrap table-check">

                                                                </thead>
                        
                                                                <tbody>
                                                                 <?php if (config_item('leg') == "1") { ?>
                                                                            <tr>
                                                                                <td>Total Users:</td>
                                                                                <td><?php echo $data->total_a ?> </td>
                                                                            </tr>
                                                                            <tr>
                                                                                <td>Total Business Value:</td>
                                                                                <td><?php echo $data->total_a_pv ?></td>
                                                                            </tr>
                                                                            <tr>
                                                                                <td>Own Purchase Value:</td>
                                                                                <td><?php echo $data->mypv ?> PV/BV</td>
                                                                            </tr>
                                                                            <tr>
                                                                                <td>Total Business:</td>
                                                                                <td><?php echo $data->total_a_investment ?></td>
                                                                            </tr>
                                                                    <?php } ?>

                                                                    <?php if (config_item('leg') == "2") { ?>
            
                                                                            <table class="table align-items-center">
                                                                            <thead class="thead-light">
                                                                                <tr>
                                                                                    <th>Title</th>
                                                                                    <th>Left Side</th>
                                                                                    <th>Right Side</th>
                                                                                </tr>
                                                                                </thead>
                                                                                <tbody>
                                                                                <tr>
                                                                                    <td>Total Users:</td>
                                                                                    <td><?php echo $data->total_a ?> </td>
                                                                                    <td><?php echo $data->total_b ?></td>
                                                                                </tr>
                                                                                <?php if (config_item('enable_investment') !== "Yes") { ?>
                                                                                    <tr>
                                                                                        <td>Total Business Value:</td>
                                                                                        <td><?php echo $data->total_a_pv ?></td>
                                                                                        <td><?php echo $data->total_b_pv ?></td>
                                                                                    </tr>
                                                                                <?php } else { ?>
                                                                                    <tr>
                                                                                        <td>Total Investments:</td>
                                                                                        <td><?php echo config_item('currency') . $data->total_a_investment ?></td>
                                                                                        <td><?php echo config_item('currency') . $data->total_b_investment ?></td>
                                                                                    </tr>

                                                                                <?php } ?>
                                                                                <tr>
                                                                                    <td>Total Carry Forward:</td>
                                                                                    <td><?php echo($data->total_a_pv - $data->paid_a_pv) ?></td>
                                                                                    <td><?php echo($data->total_b_pv - $data->paid_b_pv) ?></td>
                                                                                </tr>
                                                                                <tr>
                                                                                    <td>Downline ID:</td>
                                                                                    <td><?php echo $data->A ? config_item('ID_EXT') . $data->A : 'No User' ?></td>
                                                                                    <td><?php echo $data->B ? config_item('ID_EXT') . $data->B : 'No User' ?></td>
                                                                                </tr>
                                                                                <?php if (config_item('enable_investment') !== "Yes") { ?>
                                                                                    <tr>
                                                                                        <td>Own Purchase Value:</td>
                                                                                        <td colspan="2" class="text-right"><?php echo $data->mypv ?> PV/BV</td>
                                                                                    </tr>
                                                                                <?php } ?>
                                                                                </tbody>
                                                                            </table>
                                                                       
                                                                    <?php } ?> 

                                                                     <?php if (config_item('leg') == "3") { ?>
                                                                            <div class="table-responsive">
                                                                        <table class="table align-items-center table-flush">
                                                                        <thead class="thead-light">
                                                                                    <tr>
                                                                                        <th>Team</th>
                                                                                        <th>A Side</th>
                                                                                        <th>B Side</th>
                                                                                        <th>C Side</th>
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
                                                                                <table class="table align-items-center table-flush">
                                                                                <thead class="thead-light">
                                                                                    <tr>
                                                                                        <th>Team</th>
                                                                                        <th>A Side</th>
                                                                                        <th>B Side</th>
                                                                                        <th>C Side</th>
                                                                                        <th>D Side</th>
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
                                                                                        <td>Total Business Value:</td>
                                                                                        <td><?php echo $data->total_a_pv ?></td>
                                                                                        <td><?php echo $data->total_b_pv ?></td>
                                                                                        <td><?php echo $data->total_c_pv ?></td>
                                                                                        <td><?php echo $data->total_d_pv ?></td>
                                                                                        
                                                                                        
                                                                                    </tr>
                                                                                    <tr>
                                                                                        <td>Downline ID:</td>
                                                                                        <td><?php echo $data->A ? config_item('ID_EXT') . $data->A : 'No User' ?></td>
                                                                                        <td><?php echo $data->B ? config_item('ID_EXT') . $data->B : 'No User' ?></td>
                                                                                        <td><?php echo $data->C ? config_item('ID_EXT') . $data->C : 'No User' ?></td>
                                                                                        <td><?php echo $data->D ? config_item('ID_EXT') . $data->D : 'No User' ?></td>
                                                                                    </tr>
                                                                                        <tr>
                                                                                        <td style="color: #90111a; font-weight: bold">Own Purchase Value:</td>
                                                                                        <td colspan="2"><?php echo $data->mypv ?> PV/BV</td>
                                                                                    </tr>
                                                                                    </tbody>
                                                                                </table>
                                                                            </div>
                                                                            
                                                                        <?php } ?>
                                                                        <?php if (config_item('leg') == "5") { ?>
                                                                           
                                                                               <div class="table-responsive">
                                                                                    <table class="table align-items-center table-flush">
                                                                                    <thead class="thead-light">
                                                                                   
                                                                                    <tr>
                                                                                        <th>Team :</th>
                                                                                        <th>A Side</th>
                                                                                        <th>B Side</th>
                                                                                        <th>C Side</th>
                                                                                        <th>D Side</th>
                                                                                        <th>E Side</th>
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
                                                                                        <td>TPV:</td>
                                                                                        <td><?php echo $data->total_a_pv ?></td>
                                                                                        <td><?php echo $data->total_b_pv ?></td>
                                                                                        <td><?php echo $data->total_c_pv ?></td>
                                                                                        <td><?php echo $data->total_d_pv ?></td>
                                                                                        <td><?php echo $data->total_e_pv ?></td>
                                                                                        
                                                                                    </tr>
                                                                                    <tr>
                                                                                        <td>Downline ID:</td>
                                                                                        <td><?php echo $data->A ? config_item('ID_EXT') . $data->A : 'No User' ?></td>
                                                                                        <td><?php echo $data->B ? config_item('ID_EXT') . $data->B : 'No User' ?></td>
                                                                                        <td><?php echo $data->C ? config_item('ID_EXT') . $data->C : 'No User' ?></td>
                                                                                        <td><?php echo $data->D ? config_item('ID_EXT') . $data->D : 'No User' ?></td>
                                                                                        <td><?php echo $data->E ? config_item('ID_EXT') . $data->E : 'No User' ?></td>
                                                                                    </tr>
                                                                                        <tr>
                                                                                        <td>Own Purchase Value:(PV)</td>
                                                                                        <td colspan="2"><?php echo $data->mypv ?> PV/BV</td>
                                                                                    </tr>
                                                                                 

                                                                                    </tbody>
                                                                                </table>
                                                                            </div>
                                                                            
                                                                        <?php } ?>



                                                                </tbody>
                                                            </table>
                                                    </div>
                                                </div>
                                                <!-- end cardaa -->
                                            </div> <!-- end col -->
                                        </div> <!-- end row -->

