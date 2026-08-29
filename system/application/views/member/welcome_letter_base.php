<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Welcome <?php echo $this->session->name ?> | <?php echo config_item('company_name') ?></title>

    <link rel="stylesheet" type="text/css"
          href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css"
          rel="stylesheet"
          type="text/css"/>
    <link href="<?php echo base_url('axxets/member/theme.css') ?>"
          rel="stylesheet" id="rt_style_components"
          type="text/css"/>
    <link rel="stylesheet"
          href="//code.jquery.com/ui/1.12.1/themes/eggplant/jquery-ui.css">

    <link rel="stylesheet" type="text/css"
          href="<?php echo base_url('axxets/member/custom.css') ?>">
    <!-- favicon -->
    <link rel="shortcut icon"
          href="<?php echo base_url('uploads/favicon.ico') ?>"/>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.2.1/jquery.min.js"
            type="text/javascript"></script>
</head>
<body>

    <!--main content start-->
        <div class="container-fluid">
                <div class="row">
                    <?php
                    echo validation_errors('<div class="alert alert-danger">', '</div>');
                    echo $this->session->flashdata('common_flash');
                    if (trim($layout) !== "") {
                        //echo "<h3 style='color: #3c3c3c'>" . $title . "</h3><hr/>";
                        include_once $layout;
                    } 
                    ?>
                    </div>
                </div>
     
</body>
</html>
