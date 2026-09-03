<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>Admin Login | <?php echo config_item('company_name') ?></title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
  <link rel="icon" href="<?php echo base_url('uploads/logo.png') ?>" type="image/png">
  <style>
    body {
      background: #f1f3f6;
    }
    .login-card {
      border: none;
      border-radius: 10px;
      box-shadow: 0 5px 15px rgba(0,0,0,0.1);
      padding: 2rem;
      background-color: #fff;
    }
    .form-control:focus {
      box-shadow: none;
      border-color: #5e72e4;
    }
    .btn-primary {
      background-color: #5e72e4;
      border: none;
    }
    .btn-primary:hover {
      background-color: #324cdd;
    }
  </style>
</head>
<body>

<div class="container d-flex align-items-center justify-content-center min-vh-100">
  <div class="col-md-6 col-lg-5">
    <div class="text-center mb-4">
      <img src="<?php echo base_url('uploads/logo.png') ?>" height="60" alt="Logo">
      <h4 class="mt-2">Admin Dashboard Login</h4>
    </div>

    <?php echo validation_errors('<div class="alert alert-danger">', '</div>'); ?>
    <?php echo $this->session->flashdata('admin_flash'); ?>
    <?php if (config_item('is_demo') != TRUE): ?>
      <div class="alert alert-danger">
        Please Pay your remaining balance to remove this banner!<br>
        इस बैनर को हटाने के लिए कृपया अपनी शेष राशि का भुगतान करें!
      </div>
    <?php endif; ?>

    <div class="login-card">
      <h5 class="text-center mb-3">Sign in to your account</h5>
      <?php echo form_open('site/admin', array('class' => '')) ?>
        <div class="mb-3">
          <input type="text" name="username" class="form-control" placeholder="Username" required>
        </div>
        <div class="mb-3">
          <input type="password" name="password" id="password" class="form-control" placeholder="Password" required>
        </div>
        <div class="form-check mb-3">
          <input class="form-check-input" type="checkbox" onclick="togglePassword()">
          <label class="form-check-label">Show Password</label>
        </div>
        <div class="d-grid gap-2">
          <button type="submit" class="btn btn-primary">Login</button>
          <button type="reset" class="btn btn-outline-secondary">Reset</button>
        </div>
      <?php echo form_close(); ?>

    </div>
  </div>
</div>

<script>
  function togglePassword() {
    const pass = document.getElementById("password");
    pass.type = pass.type === "password" ? "text" : "password";
  }
</script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
