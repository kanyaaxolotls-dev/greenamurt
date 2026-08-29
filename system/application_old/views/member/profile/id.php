<!DOCTYPE html>
<html>
<head>
  <title>Download ID Card</title>
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <script src="https://cdnjs.cloudflare.com/ajax/libs/html2canvas/1.3.2/html2canvas.min.js"></script>
  <style>
    .abks-card {
      width: 300px;
      height:39em;
      margin: 20px auto;
      border: 1px solid #ccc;
      border-radius: 10px;
      box-shadow: 0 0 10px rgba(0,0,0,0.1);
      overflow: hidden;
    }

    .abks-header {
      background: #e5f1e3;
      padding: 10px;
      text-align: center;
      
    }

    .abks-logo {
      width: 120px;
    }

    .abks-profile {
      width: 100px;
      height: 100px;
      border-radius: 50%;
      border: 4px solid #184d36;
      object-fit: cover;
      /*margin-top: -50px;*/
    }

    .abks-front-details {
      background: #184d36;
      color: white;
      text-align: center;
      padding: 15px;
    }

    .abks-role {
      background: #0aa368;
      display: inline-block;
      padding: 3px 15px;
      border-radius: 15px;
      font-size: 12px;
      margin-bottom: 5px;
    }

    .abks-front-details p {
      margin: 3px 0;
      font-size: 14px;
    }

    .abks-back-section {
      background: white;
      padding: 15px;
      font-size: 13px;
    }

    .abks-label {
      font-weight: 600;
      color: #0d3b66;
    }

    .abks-signature {
      border: 1px solid #ccc;
      padding: 5px;
      text-align: center;
      margin-top: 10px;
      font-style: italic;
    }

    .abks-footer {
      background: #184d36;
      color: white;
      font-size: 12px;
      text-align: center;
      padding: 10px;
    }

    .abks-card {
      width: 300px;
      height: 39em;
      margin: 20px auto;
      border: 1px solid #ccc;
      border-radius: 10px;
      box-shadow: 0 0 10px rgba(0,0,0,0.1);
      overflow: hidden;
      display: flex;
      flex-direction: column;
      justify-content: space-between;
      position: relative;
    }

    .abks-header {
      background: #e5f1e3;
      padding: 10px;
      text-align: center;
    }

    .abks-profile-wrapper {
      flex-grow: 1;
      display: flex;
      align-items: center;
      justify-content: center;
      padding: 10px 0;
      background: #e5f1e3;
    }

    .abks-profile {
      width: 100px;
      height: 100px;
      border-radius: 50%;
      border: 4px solid #184d36;
      object-fit: cover;
    }

    .abks-front-details {
      background: #184d36;
      color: white;
      text-align: center;
      padding: 15px;
    }

    .abks-role {
      background: #0aa368;
      display: inline-block;
      padding: 3px 15px;
      border-radius: 15px;
      font-size: 12px;
      margin-bottom: 5px;
    }

  </style>
</head>
<body style="background:#f8f9fa;">

<?php
  $mimg = $this->db_model->select('prof_pic', 'member_profile', array('userid' => $this->session->user_id));
  $img = ($mimg == "" or $mimg == NULL) ? "logo.png" : $mimg;
?>

<div class="main_id">
  <div id="my-div" class="my-3">

    <!-- Front Side -->
<!-- Front Side -->
<div class="abks-card">
  <div class="abks-header">
    <img src="<?php echo base_url('uploads/logo.png'); ?>" class="abks-logo" alt="Logo">
  </div>

  <div class="abks-profile-wrapper">
    <img src="<?php echo base_url('uploads/'.$img); ?>" class="abks-profile" alt="Profile">
  </div>

  <div class="abks-front-details">
    <div class="abks-role"><?= $detail->rank; ?></div>
    <h5 class="mb-0 text-white"><?php echo $detail->name; ?></h5>
    <p>ID: <?php echo config_item('ID_EXT').$detail->id; ?></p>
    <p><?php echo $this->session->email; ?></p>
    <p><?php echo $this->session->phone; ?></p>
  </div>
</div>


    <!-- Back Side -->
    <div class="abks-card">
      <div class="abks-header">
        <img src="<?php echo base_url('uploads/logo.png'); ?>" class="abks-logo" alt="Logo">
        <p class="fw-bold text-primary mb-1"><?= config_item('company_name') ?></p>
        <p style="font-size: 12px;">Address: <?= config_item('company_address') ?></p>
        <p style="font-size: 12px;"><b>Email:</b> <?php echo config_item('web_email') ?></p>
        <p style="font-size: 12px;"><b>Phone:</b>  +91 <?php echo config_item('web_phone') ?></p>
        <p style="font-size: 12px;"><b>Website:</b>  <?= $_SERVER['HTTP_HOST']; ?></p>
      </div>
      <div class="abks-back-section">
        <p><span class="abks-label">Holder's Address:</span> <?php echo $detail->city; ?></p>
        <p><span class="abks-label">Designation:</span> <?= $detail->rank; ?></p>
        <p><span class="abks-label">Joining Date:</span> <?php echo $detail->join_time; ?></p>
        <div class="abks-signature"><?php echo $detail->name; ?><br><small>Holder's Signature</small></div>
      </div>
      <!--<div class="abks-footer">-->
      <!--  Scan for more info<br>-->
      <!--  <img src="<?php echo base_url('uploads/qr_sample.png'); ?>" alt="QR" width="80">-->
      <!--</div>-->
    </div>

  </div>
</div>

<div class="d-flex justify-content-center my-3">
  <button onclick="downloadDiv()" class="btn btn-primary btn-sm">Download Your ID Card</button>
</div>

<script>
  function downloadDiv() {
    html2canvas(document.querySelector("#my-div")).then(canvas => {
      var link = document.createElement("a");
      document.body.appendChild(link);
      link.download = "my-id.png";
      link.href = canvas.toDataURL();
      link.target = '_blank';
      link.click();
    });
  }
</script>
</body>
</html>
