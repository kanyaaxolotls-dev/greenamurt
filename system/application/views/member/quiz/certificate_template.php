<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Certification - <?= htmlspecialchars($user->name); ?></title>
    
    <!-- Premium Fonts -->
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600;700;800&family=Cinzel:wght@700;900&display=swap" rel="stylesheet">
    
    <!-- Capture Library -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/html2canvas/1.4.1/html2canvas.min.js"></script>

    <style>
        :root {
            --dark-green: #1a3c34;
            --maroon: #8b0000;
            --gold: #b8860b;
        }

        body { background: #333; margin: 0; padding: 0; font-family: 'Poppins', sans-serif; display: flex; flex-direction: column; align-items: center; }
        
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

        /* DYNAMIC FIELD OVERLAYS FOR PRE-PRINTED TEMPLATE */
        .dynamic-name {
            position: absolute;
            z-index: 10;
            top: 368px;
            left: 50%;
            transform: translateX(-50%);
            width: 600px;
            text-align: center;
            font-family: 'Cinzel', 'Poppins', serif;
            font-size: 32px;
            font-weight: 800;
            color: #8b0000;
            letter-spacing: 2px;
            text-transform: uppercase;
        }

        /* 5 Data Boxes Overlays */
        .box-val-duration {
            position: absolute; z-index: 10; top: 573px; left: 158px; width: 100px;
            font-size: 12px; font-weight: 700; color: #8b0000; text-align: center;
        }
        .box-val-period {
            position: absolute; z-index: 10; top: 573px; left: 322px; width: 110px;
            font-size: 11px; font-weight: 700; color: #8b0000; text-align: center;
        }
        .box-val-examdate {
            position: absolute; z-index: 10; top: 573px; left: 495px; width: 120px;
            font-size: 12px; font-weight: 700; color: #8b0000; text-align: center;
        }
        .box-val-score {
            position: absolute; z-index: 10; top: 573px; left: 685px; width: 110px;
            font-size: 12px; font-weight: 700; color: #8b0000; text-align: center;
        }
        .box-val-batch {
            position: absolute; z-index: 10; top: 573px; left: 852px; width: 120px;
            font-size: 11px; font-weight: 700; color: #8b0000; text-align: center;
        }

        /* Bottom Row Overlays */
        .qr-box-overlay {
            position: absolute; z-index: 10; top: 602px; left: 66px; width: 78px; height: 78px;
            display: flex; align-items: center; justify-content: center; background: #fff;
        }
        .val-cert-no {
            position: absolute; z-index: 10; top: 663px; left: 158px; width: 220px;
            font-size: 11px; font-weight: 800; color: #8b0000; text-align: left;
        }
        .val-issued-date {
            position: absolute; z-index: 10; top: 648px; left: 535px; width: 140px;
            font-size: 13px; font-weight: 700; color: #1a3c34; text-align: center;
        }
    </style>
</head>
<body>

    <div class="top-bar">
        <button class="btn-download" onclick="downloadImage()">Download as Premium Image (PNG)</button>
        <button class="btn-download" style="background: white; color: black; margin-left: 10px;" onclick="window.print()">Print PDF</button>
    </div>

    <div id="certificate-area">
        <!-- Pre-printed Blank Graphic Template -->
        <img src="<?= base_url('assets/images/certificate_template.png'); ?>" class="bg-img" alt="Certificate Background">

        <!-- DYNAMIC CANDIDATE NAME -->
        <div class="dynamic-name"><?= strtoupper(htmlspecialchars($user->name)); ?></div>

        <!-- 5 DATA BOX OVERLAYS -->
        <div class="box-val-duration"><?= htmlspecialchars($duration ?? '60 MINS'); ?></div>
        <div class="box-val-period"><?= htmlspecialchars($display_date); ?></div>
        <div class="box-val-examdate"><?= htmlspecialchars($display_date); ?></div>
        <div class="box-val-score"><?= htmlspecialchars($score ?? 'PASS'); ?></div>
        <div class="box-val-batch"><?= htmlspecialchars($certificate_no); ?></div>

        <!-- QR CODE OVERLAY -->
        <div class="qr-box-overlay">
            <img src="https://api.qrserver.com/v1/create-qr-code/?size=76x76&data=<?= urlencode(site_url('member/certificate')); ?>" alt="QR Code" style="width: 76px; height: 76px;">
        </div>

        <!-- BOTTOM DATA OVERLAYS -->
        <div class="val-cert-no"><?= htmlspecialchars($certificate_no); ?></div>
        <div class="val-issued-date"><?= htmlspecialchars($display_date); ?></div>
    </div>

    <script>
    function downloadImage() {
        const cert = document.getElementById('certificate-area');
        html2canvas(cert, {
            scale: 3, // High resolution quality for printing
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