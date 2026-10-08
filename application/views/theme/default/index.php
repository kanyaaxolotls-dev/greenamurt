
<!doctype html>  
<html lang="en">    
<!-- Mirrored from themesbrand.com/dason/layouts/default/index.html by HTTrack Website Copier/3.x [XR&CO'2014], Sat, 04 Dec 2021 09:13:14 GMT -->
<head> 

        <meta charset="utf-8" />
        <title> Member | <?php echo config_item('company_name') ?></title>
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <meta content="Premium Multipurpose Admin & Dashboard Template" name="description" />
        <meta content="Themesbrand" name="author" />
        <!-- App favicon -->
        <link rel="shortcut icon" href="<?php echo base_url('assets/images/favicon.ico')?>">

        <!-- plugin css -->
        <link href="<?php echo base_url('assets/libs/admin-resources/jquery.vectormap/jquery-jvectormap-1.2.2.css')?>" rel="stylesheet" type="text/css" />
         <link href="<?php echo base_url('assets/libs/%40fullcalendar/core/main.min.css')?>" rel="stylesheet" type="text/css" />
        <link href="<?php echo base_url('assets/libs/%40fullcalendar/daygrid/main.min.css')?>" rel="stylesheet" type="text/css" />
        <link href="<?php echo base_url('assets/libs/%40fullcalendar/bootstrap/main.min.css')?>" rel="stylesheet" type="text/css" />
        <link href="<?php echo base_url('assets/libs/%40fullcalendar/timegrid/main.min.css')?>" rel="stylesheet" type="text/css" />


        <link href="<?php echo base_url('assets/libs/bootstrap-touchspin/jquery.bootstrap-touchspin.min.css')?>" rel="stylesheet" />

         <!-- DataTables -->
        <link href="<?php echo base_url('assets/libs/datatables.net-bs4/css/dataTables.bootstrap4.min.css')?>" rel="stylesheet" type="text/css" />
        <link href="<?php echo base_url('assets/libs/datatables.net-buttons-bs4/css/buttons.bootstrap4.min.css')?>" rel="stylesheet" type="text/css" />

        <!-- Responsive datatable examples -->
        <link href="<?php echo base_url('assets/libs/datatables.net-responsive-bs4/css/responsive.bootstrap4.min.css')?>" rel="stylesheet" type="text/css" /> 

          <!-- dropzone css -->
        <link href="<?php echo base_url('assets/libs/dropzone/min/dropzone.min.css')?>" rel="stylesheet" type="text/css" />

        <!-- preloader css -->
        <link rel="stylesheet" href="<?php echo base_url('assets/css/preloader.min.css')?>" type="text/css" />

        <!-- choices css -->
        <link href="<?php echo base_url('assets/libs/choices.js/public/assets/styles/choices.min.css')?>" rel="stylesheet" type="text/css" />
        <!-- datepicker css -->
        <link rel="stylesheet" href="<?php echo base_url('assets/libs/flatpickr/flatpickr.min.css')?>" type="text/css">
 
        <!-- Bootstrap Css -->
        <link href="<?php echo base_url('assets/css/bootstrap.min.css')?>" id="bootstrap-style" rel="stylesheet" type="text/css" />
        <!-- Icons Css -->
        <link href="<?php echo base_url('assets/css/icons.min.css')?>" rel="stylesheet" type="text/css" />
        <!-- App Css-->
        <link href="<?php echo base_url('assets/css/app.min.css')?>" id="app-style" rel="stylesheet" type="text/css" />

       
    </head>

    <body data-topbar="dark">
        
 
      <?php if (trim($layout) == "") { ?>
        <div style="margin: 10%">
            <h2 align="center">Welcome to <?php echo config_item('company_name') ?></h2>
            <div align="center">Please click above to login or sign up</div>
        </div>
        <?php }
        else {
            include_once(APPPATH . "views/theme/" . $layout);
        } ?>

        <!-- JAVASCRIPT -->
        <script src="<?php echo base_url('assets/libs/jquery/jquery.min.js')?>"></script>
        <script src="<?php echo base_url('assets/libs/bootstrap/js/bootstrap.bundle.min.js')?>"></script>
        <script src="<?php echo base_url('assets/libs/metismenu/metisMenu.min.js')?>"></script>
        <script src="<?php echo base_url('assets/libs/simplebar/simplebar.min.js')?>"></script>
        <script src="<?php echo base_url('assets/libs/node-waves/waves.min.js')?>"></script>
        <script src="<?php echo base_url('assets/libs/feather-icons/feather.min.js')?>"></script>
        <!-- pace js -->
        <script src="<?php echo base_url('assets/libs/pace-js/pace.min.js')?>"></script>

        <script src="<?php echo base_url('assets/js/pages/pass-addon.init.js')?>"></script>

        <script src="<?php echo base_url('assets/js/pages/feather-icon.init.js')?>"></script>
        <script>
        function myFunction() {
          var x = document.getElementById("password");
          if (x.type === "password") {
            x.type = "text";
          } else {
            x.type = "password";
          }
        }
        </script>
    </body>


<!-- Mirrored from themesbrand.com/dason/layouts/default/auth-login.html by HTTrack Website Copier/3.x [XR&CO'2014], Sat, 04 Dec 2021 09:13:55 GMT -->
</html>