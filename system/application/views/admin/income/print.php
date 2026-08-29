<!DOCTYPE html>
<html lang="en">
  
<head>
  <meta charset="utf-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
  <title>
    Admin Dashboard | <?php echo config_item('company_name') ?>
  </title>
  <!-- Favicon -->

  <link href=" <?php echo base_url('axxets/assets/img/brand/favicon.png') ?>" rel="icon" type="image/png">
  <!-- Fonts -->
  <link href="https://fonts.googleapis.com/css?family=Open+Sans:300,400,600,700" rel="stylesheet">
  <!-- Icons -->

  <link href="<?php echo base_url('axxets/assets/js/plugins/nucleo/css/nucleo.css') ?>" rel="stylesheet" />
  <link href="<?php echo base_url('axxets/assets/js/plugins/@fortawesome/fontawesome-free/css/all.min.css')?>" rel="stylesheet" />
  <!-- CSS Files -->
  <link href="<?php echo base_url('axxets/assets/css/argon-dashboard.css?v=1.1.0')?>" rel="stylesheet" />
  <script src="jquery.animated-bg.js"></script>
   <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.2.1/jquery.min.js"
            type="text/javascript"></script>
<script type="text/javascript" src="https://cdn.datatables.net/v/dt/dt-1.10.18/datatables.min.js"></script>
<script type="text/javascript" src="https://code.jquery.com/jquery-3.3.1.js"></script>
<script type="text/javascript" src="https://cdn.datatables.net/1.10.19/js/jquery.dataTables.min.js"></script>
<script type="text/javascript" src="https://code.jquery.com/jquery-3.3.1.js"></script>
<script type="text/javascript" src="https://cdn.datatables.net/1.10.19/js/jquery.dataTables.min.js"></script>
<script type="text/javascript" src="https://cdn.datatables.net/buttons/1.6.1/css/buttons.dataTables.min.css"></script>
<script type="text/javascript" src="https://cdn.datatables.net/1.10.20/css/jquery.dataTables.min.css"></script>
<script type="text/javascript" src="https://cdn.datatables.net/buttons/1.6.1/js/dataTables.buttons.min.js"></script>
<script type="text/javascript" src="https://code.jquery.com/jquery-3.3.1.js"></script>
<script type="text/javascript" src="https://cdn.datatables.net/1.10.20/js/jquery.dataTables.min.js"></script>
<script type="text/javascript" src="https://cdn.datatables.net/buttons/1.6.1/js/dataTables.buttons.min.js"></script>
<script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/jszip/3.1.3/jszip.min.js"></script>
<script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.53/pdfmake.min.js"></script>
<script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.53/vfs_fonts.js"></script>
<script type="text/javascript" src="https://cdn.datatables.net/buttons/1.6.1/js/buttons.html5.min.js"></script>
<script type="text/javascript" src="https://cdn.datatables.net/buttons/1.6.1/js/buttons.print.min.js"></script>
    
        <script src="<?php echo base_url('axxets/admin/jquery.min.js')?>"></script>
     <script src="<?php echo base_url('axxets/admin/bootstrap.min.js')?>"></script>


</head>
<body>
    <?php
    $this->db->order_by('date','DESC'); 
        $this->db->group_by('userid,date'); 
        $this->db->select_sum('amount');
        $this->db->select('id,userid,date');
        $this->db->where('status', 'Un-Paid');
        $data = $this->db->get('withdraw_request')->result();
     
    ?>
    
<div class="container">
<div class="table-responsive">
<table id="example" class="table table-striped table-bordered">
   <thead>
      <tr>
        <th>S.N.</th>
        <th>Id</th>
        <th>Name</th>
        <th>Bank Name</th>
        <th>Bank Acc No.</th>
        <th>IFSC</th>
        <th>PAN.</th>
        <th>Net Payable</th>
                   
       
      </tr>
   </thead>
   <tbody>
<?php
       $sn = 1;
        foreach ($data as $e) {
              $udata=$this->db_model->select_multi('*', 'member', array('id' => $e->userid));
            $ubank=$this->db_model->select_multi('*', 'member_profile', array('userid' => $e->userid));
            $total = $e->amount;
              $a= $a+$total;
                   

                    ?>
   
      <tr>
         <td><?php echo $sn++; ?></td>
                        <td><?php echo config_item('ID_EXT') . $e->userid ?></td>
                       
                        
                        <td><?php echo $udata->name ?></td>   
                        
          <td><?php echo $ubank->bank_name ?> </td>
            <td><?php echo $ubank->bank_ac_no ?> </td>
       
      <td><?php echo $ubank->bank_ifsc ?></td>
                         <td><?php echo $ubank->tax_no ?></td>
                          <td><?php echo $total ?></td>
   
    </tr>
   
   
      <?php } ?>
       <tr style="background-color: #5b9bd1; color:#fff">
        <!--  <td></td> <td></td> <td></td> <td></td>  <td></td>  -->
         <td align="right" colspan="7">Total Payable</td>
         <td><?php echo config_item('currency') . number_format($a) ?></td>
      
         </tr>
   
   </tbody>

</table>
</div>
</div>
</body>
</html>
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
<!-- <script type="text/javascript">
    $(document).ready(function() {
    $('#example').DataTable( {
        dom: 'Bfrtip',
        buttons: [
            'copy', 'csv', 'excel', 'pdf', 'print'
        ]
    } );
} );
</script> -->
<!-- <script>
  $(function () {
    $('#example1').DataTable()
    $('#example1').DataTable({
      'paging'      : true,
      'lengthChange': false,
      'searching'   : false,
      'ordering'    : true,
      'info'        : true,
      'autoWidth'   : false
    })
  })
</script>

<script>
$('#example').DataTable({
    ordering: true,"autoWidth": false,
    paging: true,
    searching: true,
    dom: 'Bftripl',
    buttons: [
    {
            extend: 'excelHtml5',
            title: 'User List',
            message: ""


        },
        {
            extend: 'csvHtml5',
            title: 'User List',
             customize: function (csv) {
                 return ""+csv;
              }
        },
        {
            extend: 'pdfHtml5',
            title: 'User List',
            customize: function ( doc ) {
                            doc.content.splice( 0, 0, {
                                text: "" 
                            } );
            }
        },
        
        {
            extend: 'print',
            title: 'User List',
            message: ""

        },
        
        
        //'excelHtml5',
        //'csvHtml5',
        //'pdfHtml5'
    ] 

}); 
</script> -->
