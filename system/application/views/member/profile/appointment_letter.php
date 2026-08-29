<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Appointment Letter</title>
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <!-- Bootstrap 5 CDN -->
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
  <link href="https://fonts.googleapis.com/css2?family=Roboto&display=swap" rel="stylesheet">
   <script src="https://cdnjs.cloudflare.com/ajax/libs/html2canvas/1.3.2/html2canvas.min.js"></script>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
  <style>
    body {
      font-family: 'Roboto', sans-serif;
      background-color: white;
    }
    .abks-letter-wrapper {
      max-width: 900px;
      margin: 30px auto;
      background-color: white;
      border: 1px solid #ddd;
      box-shadow: 0 0 10px rgba(0,0,0,0.1);
    }
    .abks-header {
      background: linear-gradient(to right, #8BC34A, #4CAF50);
      color: white;
      padding: 30px 20px 80px;
      clip-path: polygon(0 0, 100% 0, 100% 65%, 0 85%)
    }
    .abks-header h1 {
      font-size: 28px;
      margin-bottom: 10px;
    }
    .abks-date {
      text-align: end;
      font-size: 14px;
    }
    .abks-content-section {
      padding: 30px;
    }
    .abks-title {
      font-size: 16px;
      font-weight: bold;
      margin-bottom: 10px;
    }
    .abks-paragraph {
      font-size: 14px;
      line-height: 1.6;
    }
    .abks-footer {
      height: 40px;
      background: linear-gradient(to right, #8BC34A, #4CAF50);
    }
    .abks-signature-area {
      margin-top: 40px;
    }
    @media (max-width: 768px) {
      .abks-from-to .col-md-6 {
        margin-bottom: 20px;
      }
      .abks-date {
        text-align: start;
      }
    }
  </style>
</head>
<body>

<div class="abks-letter-wrapper" id="my-div">
  <div class="abks-header text-white">
    <div class="d-flex justify-content-between align-items-center">
      <h1 class="m-0"></h1>
      <div class="abks-date">
          <img src="<?= base_url('uploads/logo.png'); ?>" height="80" />
          <p class="text-center mt-2">Date: <?= date('d/m/Y'); ?></p>
         </div>
    </div>
  </div>

  <div class="abks-content-section">
    <div class="row abks-from-to">
      <div class="col-md-6">
        <div class="abks-title">From:</div>
        <p><strong>ABKS TRUHERBZ PVT. LTD</strong><br>
        +91 7620711818<br>
        abkstruherbz@gmail.com<br>
        Orange Corner, Behind Reliance Smart Bazaar,<br>
        Sangamner, Dist. Ahmednagar, Maharashtra – 422605<br>
        <a href="https://www.abkstruherbz.in" target="_blank">www.abkstruherbz.in</a></p>
      </div>
      <div class="col-md-6">
        <div class="abks-title">To:</div>
        <p><strong><?= $my_data->name ?></strong><br>
        +91 <?= $my_data->phone ?><br>
        <?= $my_data->email ?><br>
        <?= $my_data->address ?>,<br>
        <?= $my_data->city ?>,<br>
        <?= $my_data->pincode ?></p>
      </div>
    </div>

    <p class="abks-title">Subject: Appointment as Direct Seller</p>

    <div class="abks-paragraph">
      <p>Dear <?= $my_data->name ?>,</p>
      <p>We are pleased to inform you that you have been appointed as a Direct Seller of Abks Truherbz, a direct selling company engaged in the distribution of herbal, wellness, and health products.</p>
      <p>This appointment is subject to the following terms and conditions:</p>

      <p><strong>1. Role & Responsibility:</strong><br>
      As a Direct Seller, you will promote and sell Abks Truherbz products to customers and also have the opportunity to sponsor and train new direct sellers.</p>

      <p><strong>2. Non-Employment:</strong><br>
      This appointment does not constitute an employment relationship. You will act as an independent distributor and will not be considered an employee or agent of the company.</p>

      <p><strong>3. Code of Conduct:</strong><br>
      You are expected to abide by the rules and policies of the company, including fair selling practices, truthful product representation, and ethical behavior.</p>

      <p><strong>4. Compensation:</strong><br>
      Your earnings will be based on the volume of sales and the incentive structure as outlined in the company’s compensation plan.</p>

      <p><strong>5. Termination:</strong><br>
      This appointment may be terminated by either party at any time with written notice.</p>

      <p>We look forward to a successful association and mutual growth.</p>
      <p>Please acknowledge your acceptance by signing and returning a copy of this letter.</p>

      <p>Warm regards,</p>

      <p><strong>[Authorized Signatory]</strong><br>
      Designation<br>
      <strong>ABKS TRUHERBZ</strong></p>

      <hr class="my-4">

      <h6>Acknowledgment</h6>
      <p>
        I, <?= $my_data->name ?>, hereby accept the terms and conditions of this appointment as a Direct Seller of Abks Truherbz.
      </p>

      <div class="abks-signature-area">
        <p>Signature: ____________________________</p>
        <p>Date: <?= $my_data->join_time ?></p>
      </div>
    </div>
  </div>

  <div class="abks-footer"></div>
</div>
<div class="d-flex justify-content-center my-3">
  <button onclick="downloadDiv()" class="btn btn-success btn-sm">Download Your Appointment Letter</button>
</div>

<script>
  function downloadDiv() {
    html2canvas(document.querySelector("#my-div")).then(canvas => {
      var link = document.createElement("a");
      document.body.appendChild(link);
      link.download = "my-appointment_letter.png";
      link.href = canvas.toDataURL();
      link.target = '_blank';
      link.click();
    });
  }
</script>
</body>
</html>
