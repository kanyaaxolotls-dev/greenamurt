
<!DOCTYPE html>
<html lang="en">
  
<head>
  <meta charset="utf-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">

  <!-- <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css"
          rel="stylesheet" type="text/css"/>
  <link rel="stylesheet" type="text/css" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css">
  <link href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-switch/3.3.4/css/bootstrap2/bootstrap-switch.min.css" rel="stylesheet" type="text/css"/> -->
 <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.2.1/jquery.min.js" type="text/javascript"></script>
  <link href="<?php echo base_url('axxets/assets/js/plugins/@fortawesome/fontawesome-free/css/all.min.css')?>" rel="stylesheet" />
  <link href="<?php echo base_url('axxets/assets/js/plugins/nucleo/css/nucleo.css') ?>" rel="stylesheet" />
  <link href="<?php echo base_url('axxets/admin/argon-dashboard.css')?>" rel="stylesheet" /> 
  <script src="jquery.animated-bg.js"></script>

 <!--  -->
<script type="text/javascript">
$(document).ready(function() {
    $('#example').DataTable( {
        dom: 'Bfrtip',
        buttons: [
             'csv', 'excel', 'pdf', 'print'
        ]
    } );
} );
</script>
  <!-- Sorting Table -->
    <link rel="stylesheet" type="text/css"
          href="https://cdn.datatables.net/1.10.19/css/jquery.dataTables.min.css" rel="stylesheet" id="rt_style_components"
          type="text/css"/> 
            <script type="text/javascript" src="https://cdn.datatables.net/1.10.19/js/jquery.dataTables.min.js"></script>
            <script type="text/javascript" src="https://code.jquery.com/jquery-3.3.1.js"></script>
            <script type="text/javascript" src="https://cdn.datatables.net/1.10.19/js/jquery.dataTables.min.js"></script>
            <script type="text/javascript" src="https://cdn.datatables.net/buttons/1.5.6/js/dataTables.buttons.min.js"></script>
            <script type="text/javascript" src="https://cdn.datatables.net/buttons/1.5.6/js/buttons.flash.min.js"></script>
            <script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/jszip/3.1.3/jszip.min.js"></script>
            <script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.53/pdfmake.min.js"></script>
            <script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.53/vfs_fonts.js"></script>
            <script type="text/javascript" src="https://cdn.datatables.net/buttons/1.5.6/js/buttons.html5.min.js"></script>
            <script type="text/javascript" src="https://cdn.datatables.net/buttons/1.5.6/js/buttons.print.min.js"></script>
            <script type="text/javascript" src="https://cdn.datatables.net/1.10.19/css/jquery.dataTables.min.css"></script>
            <script type="text/javascript" src="https://cdn.datatables.net/buttons/1.5.6/css/buttons.dataTables.min.css"></script>



            
            </head>
             <body>
             
              
            <div class="table-responsive"> 
           
              <table id="example" class="table table-striped table-bordered align-items-center table-flush">
                <thead  class="thead-light">
               
                  <tr>
                    <th scope="col">SN</th>
                    <th scope="col">User ID</th>
                    <th scope="col">Name</th> 
                    <th scope="col">Sponsor ID</th>
                    <th scope="col">Phone</th>
                    <?php if (config_item('enable_investment') == "Yes") {
                        echo '<th>Total Investments</th>';
                    }
                    ?>
                    <th scope="col">Join Date</th>
                    <th scope="col">Joining Product</th>
                    <th scope="col">Total Sponsors</th>
                    <th scope="col">Total Downline</th>
                    <th scope="col">Actions</th>
                  </tr>
                </thead>
                <tbody>

                     <?php
                     $this->db->select('id, name, phone, sponsor, join_time,signup_package,total_a, total_b, total_c, total_d, total_e')
                 ->from('member'); 

        $this->db->limit($config['per_page'], $page);

        $data = $this->db->get()->result_array();
                    $sn = 1;
                    foreach ($data as $e) { 
                        $query = $this->db->query("SELECT * FROM member where sponsor=".$e['id']);
                ?>
                  <tr> 
                  <!--   <th scope="row">
                      <div class="media align-items-center">
                        <a href="#" class="avatar rounded-circle mr-3">
                          <img alt="Image placeholder" src="../assets/img/theme/bootstrap.jpg">
                        </a>
                        <div class="media-body">
                          <span class="mb-0 text-sm">Argon Design System</span>
                        </div>
                      </div>
                    </th> -->
                <td><?php echo $sn++; ?></td>
                <td><a href="<?php echo site_url('users/user_detail/' . $e['id']) ?>"
                       target="_blank"><?php echo config_item('ID_EXT') . $e['id']; ?></a></td>
                <td><?php echo $e['name']; ?></td>
                <td><a href="<?php echo site_url('users/user_detail/' . $e['sponsor']) ?>"
                       target="_blank"><?php echo $e['sponsor'] ? config_item('ID_EXT') . $e['sponsor'] : ''; ?></td>
                <td><?php echo $e['phone']; ?></td>
                <?php if (config_item('enable_investment') == "Yes") {
                    echo '<td>' . $e['topup'] . '</td>';
                } 
                ?>
                <td><?php echo $e['join_time']; ?></td>
                <td><?php  echo $signup_package_prod =  $this->db_model->select('prod_name', 'product', array('id' => $e['signup_package']));?></td>
                <td><?php echo $query->num_rows(); ?></td>
                <td><?php echo($e['total_a'] + $e['total_b'] + $e['total_c'] + $e['total_d'] + $e['total_e']); ?></td>
                <td>
                      <div class="dropdown">
                        <a class="btn btn-sm btn-icon-only text-light" href="#" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                          <i class="fas fa-ellipsis-v"></i>
                        </a>
                        <div class="dropdown-menu dropdown-menu-right dropdown-menu-arrow">
                             <?php if (config_item('enable_topup') == "Yes") { ?>
                          <a class="dropdown-item" href="<?php echo site_url('users/topup_member/' . $e['id']); ?>">Topup</a>
                           <?php } ?>
                          <a class="dropdown-item" href="<?php echo site_url('users/edit_user/' . $e['id']); ?>">Edit</a>
                          <a class="dropdown-item" href="<?php echo site_url('users/user_detail/' . $e['id']); ?>">View</a>
                          <a class="dropdown-item" onclick="return confirm('Are you sure you want to delete this Member ?')"  href="<?php echo site_url('users/remove_member/' . $e['id']); ?>">Delete</a>
                          <a class="dropdown-item" href="<?php echo site_url('users/login_member/' . $e['id']); ?>" target="_blank">Login</a> 
                        </div> 
                      </div>
                    </td>
                  </tr>
                <?php } ?>          
                </tbody>
              </table>
            </div>
       
            <div class="card-footer py-4">
              <nav aria-label="...">
                <ul class="pagination justify-content-start mb-0">
                  <li class="page-item disabled">
                    <a href="<?php echo site_url('admin/index') ?>" class="btn btn-sm btn-primary">&larr; Go Back</a>
                    
                  </li>
                </ul>

                <ul class="pagination justify-content-end mb-0">
                  <li class="page-item disabled">
                    <a class="page-link" href="#" tabindex="-1">
                      <i class="fas fa-angle-left"></i>
                      <span class="sr-only"><?php echo $this->pagination->create_links(); ?></span>
                    </a>
                  </li>
                </ul>
              </nav>
            </div>



            <script src="<?php// echo base_url('axxets/assets/js/plugins/jquery/dist/jquery.min.js')?>"></script>
  <script src="<?php echo base_url('axxets/assets/js/plugins/bootstrap/dist/js/bootstrap.bundle.min.js')?>"></script>
  <!--   Optional JS   -->
  <script src="<?php //echo base_url('axxets/assets/js/plugins/chart.js/dist/Chart.min.js')?>"></script>
  <script src="<?php //echo base_url('axxets/assets/js/plugins/chart.js/dist/Chart.extension.js')?>"></script>
  <!--   Argon JS   -->
<!--   <script src="<?php// echo base_url('axxets/assets/js/argon-dashboard.min.js?v=1.1.0')?>"></script>
  <script src="https://cdn.trackjs.com/agent/v3/latest/t.js"></script>
   <script src="<?php //echo base_url('axxets/assets/js/plugins/bootstrap-datepicker/dist/js/bootstrap-datepicker.min.js')?>"></script> -->
  <script>
    window.TrackJS &&
      TrackJS.install({
        token: "ee6fab19c5a04ac1a32a645abde4613a",
        application: "argon-dashboard-free"
      });
  </script>
  <script type="text/javascript">
    $(document).ready(function(){
      $('.animated-bg').animatedbg({
        // options here
      });
    });

  </script>
        </body>
      </html>