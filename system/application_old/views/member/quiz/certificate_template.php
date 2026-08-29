<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Certification - <?= $user->name; ?></title>
    
    <!-- Premium Fonts -->
    <link href="https://fonts.googleapis.com/css2?family=Playball&family=Poppins:wght@300;400;500;600;700;800&family=Cinzel:wght@400;700;900&display=swap" rel="stylesheet">
    
    <!-- Capture Library -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/html2canvas/1.4.1/html2canvas.min.js"></script>

    <style>
        :root {
            --dark-green: #1a3c34;
            --maroon: #8b0000;
            --gold: #b8860b;
        }

        body { background: #444; margin: 0; padding: 0; font-family: 'Poppins', sans-serif; display: flex; flex-direction: column; align-items: center; }
        
        /* UI Bar */
        .top-bar {
            background: #111; padding: 15px; text-align: center; width: 100%;
            position: sticky; top: 0; z-index: 1000; box-shadow: 0 4px 10px rgba(0,0,0,0.5);
        }
        .btn-download {
            background: #18b76a; color: white; border: none; padding: 12px 30px;
            font-weight: 700; border-radius: 6px; cursor: pointer; font-size: 16px; transition: 0.3s;
        }
        .btn-download:hover { background: #149957; transform: scale(1.05); }

        /* Certificate Workspace */
        #certificate-area {
            position: relative;
            width: 1100px;
            height: 778px;
            background: #fff;
            margin: 40px 0;
            overflow: hidden;
            box-shadow: 0 0 50px rgba(0,0,0,0.5);
        }

        .bg-img { width: 100%; height: 100%; position: absolute; top: 0; left: 0; z-index: 1; }

        /* Absolute Overlay Layers */
        .layer { position: absolute; z-index: 2; width: 100%; text-align: center; left: 0; pointer-events: none; }

        /* 1. Main Header */
        .company-name { top: 65px; font-family: 'Cinzel', serif; font-size: 44px; font-weight: 900; color: var(--dark-green); letter-spacing: 1px; }
        .subtitle { top: 135px; font-family: 'Poppins', sans-serif; font-size: 15px; font-weight: 600; color: #333; letter-spacing: 1px; }
        
        /* 2. Health Wealth Safety Ribbon */
        .tagline-ribbon {
            top: 175px; height: 28px; display: flex; justify-content: center; align-items: center;
            background: linear-gradient(to right, transparent, var(--dark-green) 20%, var(--dark-green) 80%, transparent);
        }
        .tagline-text { color: #fff; font-size: 13px; font-weight: 600; word-spacing: 15px; }
        .tagline-text span { color: var(--gold); }

        /* 3. Certificate Title */
        .main-title { top: 235px; font-family: 'Cinzel', serif; font-size: 72px; font-weight: 900; color: var(--maroon); letter-spacing: 5px; }
        .of-completion { 
            top: 318px; font-family: 'Cinzel', serif; font-size: 22px; font-weight: 700; color: #333;
            display: flex; justify-content: center; align-items: center; gap: 15px;
        }
        .of-completion::before, .of-completion::after { content: ""; width: 80px; height: 2px; background: var(--gold); }

        /* 4. Nadi Training Ribbon */
        .nadi-ribbon {
            top: 365px; width: 500px; left: 300px; background: var(--dark-green);
            color: white; font-size: 20px; font-weight: 800; padding: 8px 0;
            clip-path: polygon(5% 0%, 95% 0%, 100% 50%, 95% 100%, 5% 100%, 0% 50%);
            letter-spacing: 2px;
        }

        /* 5. Certify That */
        .proudly { top: 420px; font-family: 'Playball', cursive; font-size: 22px; color: #444; }

        /* 6. USER NAME */
        .user-name {
            top: 450px; font-family:  'arial', sans-serif; font-size: 55px;
            color: var(--dark-green); padding-bottom: 5px;
        }

        /* 7. Paragraph */
        .content-para {
            top: 555px; padding: 0 20px; font-size: 15.5px; line-height: 1.6; color: #333;
        }
        .content-para b { color: var(--maroon); font-weight: 700; }

        .appreciation {
            top: 665px; font-family: 'Poppins', sans-serif; font-style: italic; font-size: 14.5px; color: #444;
        }

        /* 8. Bottom Data Row */
        .data-row {
            position: absolute; bottom: 125px; width: 100%; display: flex;
            justify-content: space-between; padding: 0 100px; box-sizing: border-box; z-index: 3;
        }
        .data-group { text-align: center; border-top: 1px solid #aaa; padding-top: 5px; min-width: 140px; }
        .data-label { display: block; font-size: 11px; font-weight: 800; color: var(--dark-green); text-transform: uppercase; }
        .data-value { font-size: 14px; font-weight: 700; color: var(--maroon); }

        /* 9. Signatures */
        .sig-row {
            position: absolute; bottom: 35px; width: 100%; display: flex;
            justify-content: space-between; padding: 0 140px; box-sizing: border-box; z-index: 3;
        }
        .sig-box { text-align: center; border-top: 2px solid var(--dark-green); min-width: 200px; padding-top: 5px; }
        .sig-label { font-size: 14px; font-weight: 700; color: #333; }
     .content-para{
    position:absolute;
    top:525px;
    left:50%;
    transform:translateX(-50%);
    width:820px;              /* increase width */
    text-align:center;

    font-family:'Poppins',sans-serif;
    font-size:17px;
    line-height:1.6;
    color:#333;
    font-weight:400;
}

.highlight-red{
    color:#8b0000;
    font-weight:700;
}

.highlight-green{
    color:#1a3c34;
    font-weight:700;
}

    </style>
</head>
<body>

    <div class="top-bar">
        <button class="btn-download" onclick="downloadImage()">Download as Premium Image (PNG)</button>
        <button class="btn-download" style="background: white; color: black; margin-left: 10px;" onclick="window.print()">Print PDF</button>
    </div>

    <div id="certificate-area">
        <!-- Redesigned Blank Background -->
        <img src="<?= base_url('assets/images/certificate_template.png'); ?>" class="bg-img">
            
        <!-- TEXT CONTENT -->
        <div class="layer main-title">CERTIFICATE</div>
        <div class="layer of-completion">OF COMPLETION</div>

        <div class="layer nadi-ribbon">NADI TRAINING PROGRAMME</div>

        <div class="layer proudly">This is to proudly certify that</div>

        <!-- DYNAMIC NAME -->
        <div class="layer user-name"><?= strtoupper($user->name); ?></div>

        <!-- MAIN PARAGRAPH -->
        <!--<div class="layer content-para">-->
        <!--    has successfully completed the <b>"Nadi Training Programme"</b> conducted by -->
        <!--    <b>GA Harbal Multitrade Private Limited</b> and has demonstrated satisfactory -->
        <!--    knowledge and practical skills in <b>Nadi Parikshan (Pulse Diagnosis).</b>-->
        <!--</div>-->
       <div class="layer content-para">
             has successfully completed the
            <span class="highlight-red">"Nadi Training Programme"</span>
            conducted by
            <span class="highlight-green">GA Harbal Multitrade Private Limited</span>,
            and has demonstrated satisfactory knowledge and practical skills in
            <span class="highlight-red">Nadi Parikshan (Pulse Diagnosis).</span>
        </div>

        <div class="layer appreciation">
            We appreciate the participant's dedication and commitment <br> towards holistic health and Ayurvedic wellness.
        </div>

        <!-- DYNAMIC DATA FIELDS -->
        <div class="data-row">
            <div class="data-group">
                <span class="data-label">Certificate No. :</span>
                <span class="data-value"><?= $certificate_no; ?></span>
            </div>
            <div class="data-group">
                <span class="data-label">Date :</span>
                <span class="data-value"><?= $display_date; ?></span>
            </div>
            <div class="data-group">
                <span class="data-label">Place :</span>
                <span class="data-value"><?= $place ?? 'MAHARASHTRA'; ?></span>
            </div>
            <div class="data-group">
                <span class="data-label">Duration :</span>
                <span class="data-value"><?= $duration ?? '60 MINUTES'; ?></span>
            </div>
        </div>

        <!-- SIGNATURE LABELS -->
        <div class="sig-row">
            <div class="sig-box"><span class="sig-label">Trainer Signature</span></div>
            <div class="sig-box"><span class="sig-label">Managing Director</span></div>
        </div>
    </div>

    <script>
    function downloadImage() {
        const cert = document.getElementById('certificate-area');
        html2canvas(cert, {
            scale: 3, // Very high quality for printing
            useCORS: true,
            allowTaint: true,
            backgroundColor: null
        }).then(canvas => {
            const link = document.createElement('a');
            link.download = 'GA-Nadi-Certification-<?= $user->member_id; ?>.png';
            link.href = canvas.toDataURL("image/png");
            link.click();
        });
    }
    </script>

</body>
</html>