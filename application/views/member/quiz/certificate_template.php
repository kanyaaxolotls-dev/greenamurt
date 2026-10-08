<?php
// Auto-clean the pre-printed black line from certificate template image file if present
$possible_paths = [
    FCPATH . 'assets/images/certificate_template.png',
    FCPATH . 'system/assets/images/certificate_template.png',
    APPPATH . '../system/assets/images/certificate_template.png',
    APPPATH . '../assets/images/certificate_template.png'
];
foreach ($possible_paths as $p) {
    if (file_exists($p) && is_writable($p) && !file_exists($p . '.cleaned_v2')) {
        $img = @imagecreatefrompng($p);
        if ($img) {
            $w = imagesx($img);
            $h = imagesy($img);
            $y1 = (int)($h * 0.46);
            $y2 = (int)($h * 0.51);
            $x1 = (int)($w * 0.20);
            $x2 = (int)($w * 0.80);
            for ($y = $y1; $y <= $y2; $y++) {
                for ($x = $x1; $x <= $x2; $x++) {
                    $rgb = imagecolorat($img, $x, $y);
                    $r = ($rgb >> 16) & 0xFF;
                    $g = ($rgb >> 8) & 0xFF;
                    $b = $rgb & 0xFF;
                    if ($r < 150 && $g < 150 && $b < 150) {
                        $aboveRgb = imagecolorat($img, $x, max(0, $y - 7));
                        $ar = ($aboveRgb >> 16) & 0xFF;
                        $ag = ($aboveRgb >> 8) & 0xFF;
                        $ab = $aboveRgb & 0xFF;
                        $belowRgb = imagecolorat($img, $x, min($h - 1, $y + 7));
                        $br = ($belowRgb >> 16) & 0xFF;
                        $bg = ($belowRgb >> 8) & 0xFF;
                        $bb = $belowRgb & 0xFF;
                        if ($ar > 200 && $br > 200) {
                            $patchColor = imagecolorallocate($img, (int)(($ar + $br)/2), (int)(($ag + $bg)/2), (int)(($ab + $bb)/2));
                            for ($dy = -3; $dy <= 3; $dy++) {
                                imagesetpixel($img, $x, $y + $dy, $patchColor);
                            }
                        }
                    }
                }
            }
            @imagepng($img, $p);
            @imagedestroy($img);
            @file_put_contents($p . '.cleaned_v2', '1');
        }
    }
}
?>
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

        body {
            background: #2b2b2b;
            margin: 0;
            padding: 0;
            font-family: 'Poppins', sans-serif;
            display: flex;
            flex-direction: column;
            align-items: center;
            min-height: 100vh;
            overflow-x: hidden;
        }
        
        /* UI Bar */
        .top-bar {
            background: #111;
            padding: 15px;
            text-align: center;
            width: 100%;
            position: sticky;
            top: 0;
            z-index: 1000;
            box-shadow: 0 4px 10px rgba(0,0,0,0.5);
            display: flex;
            justify-content: center;
            gap: 12px;
            flex-wrap: wrap;
        }
        .btn-download {
            background: #18b76a;
            color: white;
            border: none;
            padding: 12px 25px;
            font-weight: 700;
            border-radius: 6px;
            cursor: pointer;
            font-size: 15px;
            transition: all 0.3s ease;
        }
        .btn-download:hover {
            background: #149957;
            transform: translateY(-2px);
        }
        .btn-print {
            background: #ffffff;
            color: #111111;
            border: none;
            padding: 12px 25px;
            font-weight: 700;
            border-radius: 6px;
            cursor: pointer;
            font-size: 15px;
            transition: all 0.3s ease;
        }
        .btn-print:hover {
            background: #e2e2e2;
            transform: translateY(-2px);
        }

        /* Responsive Wrapper */
        .cert-outer-wrapper {
            width: 100%;
            max-width: 1140px;
            display: flex;
            justify-content: center;
            align-items: flex-start;
            padding: 30px 15px;
            box-sizing: border-box;
        }

        /* Certificate Workspace */
        #certificate-area {
            position: relative;
            width: 1100px;
            height: 778px;
            background: #fff;
            overflow: hidden;
            box-shadow: 0 10px 40px rgba(0,0,0,0.6);
            transform-origin: top center;
            transition: transform 0.2s ease-out;
            flex-shrink: 0;
        }

        .bg-img {
            width: 100%;
            height: 100%;
            position: absolute;
            top: 0;
            left: 0;
            z-index: 1;
        }

        /* Seamless mask to completely erase any pre-printed black line from template */
        .name-line-mask {
            position: absolute;
            z-index: 2;
            top: 378px;
            left: 290px;
            width: 520px;
            height: 6px;
            background: #faf7ef;
            pointer-events: none;
        }

        /* DYNAMIC CANDIDATE NAME - Clean text with transparent background */
        .dynamic-name {
            position: absolute;
            z-index: 10;
            top: 358px;
            left: 50%;
            transform: translateX(-50%);
            width: 750px;
            text-align: center;
            font-family: 'Cinzel', 'Poppins', serif;
            font-size: 28px;
            font-weight: 800;
            color: #8b0000;
            letter-spacing: 2px;
            text-transform: uppercase;
            line-height: 1.2;
            background: transparent;
        }
        .dynamic-name .name-text {
            background: transparent;
            padding: 0;
            box-shadow: none;
            display: inline-block;
        }

        /* 5 Data Boxes Overlays */
        .box-val-duration,
        .box-val-period,
        .box-val-examdate,
        .box-val-score,
        .box-val-batch {
            position: absolute;
            z-index: 10;
            top: 572px;
            font-weight: 700;
            color: #8b0000;
            text-align: center;
        }
        .box-val-duration { left: 158px; width: 100px; font-size: 12px; }
        .box-val-period { left: 322px; width: 110px; font-size: 11px; }
        .box-val-examdate { left: 495px; width: 120px; font-size: 12px; }
        .box-val-score { left: 685px; width: 110px; font-size: 12px; }
        .box-val-batch { left: 852px; width: 120px; font-size: 11px; }

        /* Bottom Row Overlays */
        .qr-box-overlay {
            position: absolute; z-index: 10; top: 602px; left: 66px; width: 78px; height: 78px;
            display: flex; align-items: center; justify-content: center; background: #fff;
        }
        .val-issued-date {
            position: absolute; z-index: 10; top: 648px; left: 535px; width: 140px;
            font-size: 13px; font-weight: 700; color: #1a3c34; text-align: center;
        }

        @media print {
            .top-bar { display: none !important; }
            body { background: #fff !important; margin: 0 !important; padding: 0 !important; }
            .cert-outer-wrapper { padding: 0 !important; width: 100% !important; max-width: none !important; }
            #certificate-area {
                transform: none !important;
                margin: 0 !important;
                box-shadow: none !important;
            }
        }
    </style>
</head>
<body>

    <div class="top-bar">
        <button class="btn-download" onclick="downloadImage()">Download as Premium Image (PNG)</button>
        <button class="btn-print" onclick="window.print()">Print PDF</button>
    </div>

    <div class="cert-outer-wrapper" id="certOuterWrapper">
        <div id="certificate-area">
            <!-- Pre-printed Blank Graphic Template -->
            <img src="<?= base_url('assets/images/certificate_template.png?v=' . time()); ?>" class="bg-img" alt="Certificate Background">

            <!-- Clean line eraser to remove black line underneath -->
            <div class="name-line-mask"></div>

            <!-- DYNAMIC CANDIDATE NAME -->
            <div class="dynamic-name"><span class="name-text"><?= strtoupper(htmlspecialchars($user->name)); ?></span></div>

            <!-- 5 DATA BOX OVERLAYS -->
            <div class="box-val-duration"><span><?= htmlspecialchars($duration ?? '60 MINS'); ?></span></div>
            <div class="box-val-period"><span><?= htmlspecialchars($display_date); ?></span></div>
            <div class="box-val-examdate"><span><?= htmlspecialchars($display_date); ?></span></div>
            <div class="box-val-score"><span><?= htmlspecialchars($score ?? 'PASS'); ?></span></div>
            <div class="box-val-batch"><span><?= htmlspecialchars($certificate_no); ?></span></div>

            <!-- QR CODE OVERLAY -->
            <div class="qr-box-overlay">
                <img src="https://api.qrserver.com/v1/create-qr-code/?size=76x76&data=<?= urlencode(site_url('member/certificate')); ?>" alt="QR Code" style="width: 76px; height: 76px;">
            </div>

            <!-- BOTTOM DATA OVERLAYS -->
            <div class="val-issued-date"><span><?= htmlspecialchars($display_date); ?></span></div>
        </div>
    </div>

    <script>
    function scaleCertificate() {
        const cert = document.getElementById('certificate-area');
        const wrapper = document.getElementById('certOuterWrapper');
        if (!cert || !wrapper) return;

        const availableWidth = wrapper.clientWidth - 30;
        if (availableWidth < 1100) {
            const scale = availableWidth / 1100;
            cert.style.transform = `scale(${scale})`;
            cert.style.marginBottom = `-${778 * (1 - scale)}px`;
        } else {
            cert.style.transform = 'none';
            cert.style.marginBottom = '0px';
        }
    }

    window.addEventListener('resize', scaleCertificate);
    window.addEventListener('DOMContentLoaded', scaleCertificate);
    scaleCertificate();

    function downloadImage() {
        const cert = document.getElementById('certificate-area');
        const prevTransform = cert.style.transform;
        const prevMargin = cert.style.marginBottom;

        // Temporarily reset scaling for full high-res canvas rendering
        cert.style.transform = 'none';
        cert.style.marginBottom = '0px';

        html2canvas(cert, {
            scale: 3, // High resolution quality for printing
            useCORS: true,
            allowTaint: true,
            backgroundColor: null
        }).then(canvas => {
            cert.style.transform = prevTransform;
            cert.style.marginBottom = prevMargin;

            const link = document.createElement('a');
            link.download = 'GA-Nadi-Certification-<?= $user->member_id; ?>.png';
            link.href = canvas.toDataURL("image/png");
            link.click();
        }).catch(err => {
            cert.style.transform = prevTransform;
            cert.style.marginBottom = prevMargin;
            console.error('Error generating image:', err);
        });
    }
    </script>

</body>
</html>