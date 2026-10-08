<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Preparation Guide - Nadi Vigyan</title>
    <link href="https://fonts.googleapis.com/css2?family=Cinzel:wght@700&family=Poppins:wght@400;600;700&display=swap" rel="stylesheet">
    <style>
        body { background: #f0f2f5; margin: 0; padding: 20px; font-family: 'Poppins', sans-serif; color: #333; }
        
        .document-page {
            max-width: 1000px;
            margin: 30px auto;
            background: #fff;
            padding: 60px;
            border-top: 10px solid #1e3a8a; /* Navy Accent */
            box-shadow: 0 10px 30px rgba(0,0,0,0.1);
            position: relative;
        }

        .header { text-align: center; border-bottom: 2px solid #f59e0b; padding-bottom: 20px; margin-bottom: 40px; }
        .header h1 { font-family: 'Cinzel', serif; color: #1e3a8a; margin: 0; font-size: 28px; }
        .header p { color: #f59e0b; font-weight: 700; text-transform: uppercase; margin: 5px 0 0; font-size: 14px; letter-spacing: 1px; }

        .watermark {
            position: absolute; top: 50%; left: 50%; transform: translate(-50%, -50%);
            width: 60%; opacity: 0.02; pointer-events: none; z-index: 0;
        }

        .q-row {
            margin-bottom: 25px;
            padding: 15px;
            border-radius: 10px;
            background: #fdfdfd;
            border: 1px solid #eee;
            position: relative;
            z-index: 1;
        }

        .q-text { font-weight: 700; color: #1e3a8a; font-size: 16px; margin-bottom: 10px; display: block; }
        
        .ans-box {
            display: inline-block;
            background: #ecfdf5; /* Light Emerald */
            border-left: 4px solid #10b981; /* Emerald Green */
            padding: 8px 15px;
            color: #065f46;
            font-weight: 600;
            font-size: 15px;
            border-radius: 4px;
        }

        .ans-label { color: #10b981; margin-right: 10px; font-weight: 800; }

        .footer { text-align: center; margin-top: 50px; font-size: 12px; color: #999; border-top: 1px solid #eee; padding-top: 20px; }

        .no-print { text-align: center; margin-bottom: 20px; }
        .btn-action {
            background: #1e3a8a; color: white; padding: 12px 25px; border: none; 
            border-radius: 6px; font-weight: bold; cursor: pointer; text-decoration: none;
        }

        @media print {
            .no-print { display: none; }
            body { background: white; padding: 0; }
            .document-page { box-shadow: none; margin: 0; border-top: none; }
        }
    </style>
</head>
<body>

    <div class="no-print">
        <button class="btn-action" onclick="window.print()">Print Study Guide</button>
        <a href="javascript:window.close()" class="btn-action" style="background: #64748b;">Close Window</a>
    </div>

    <div class="document-page">
        <!-- Background Logo -->
        <img src="<?php echo base_url('uploads/nadi_illustration.png'); ?>" class="watermark">

        <div class="header">
            <h1>OFFICIAL CERTIFICATION PREPARATION</h1>
            <p>Nadi Vigyan & Ayurvedic Pulse Diagnosis Answer Key</p>
        </div>

        <div style="margin-bottom: 40px; padding: 15px; background: #fffbeb; border: 1px solid #fef3c7; border-radius: 8px; font-size: 14px;">
            <strong>Confidential:</strong> This document is for registered candidates only. Questions in the actual exam will appear in a randomized sequence.
        </div>

        <?php foreach($questions as $id => $data): ?>
            <div class="q-row">
                <span class="q-text"><?php echo $id; ?>. <?php echo $data['q']; ?></span>
                <div class="ans-box">
                  Correct Answer: <?php echo $data['ans']; ?>
                </div>
            </div>
        <?php endforeach; ?>

        <div class="footer">
            © <?php echo date('Y'); ?> GA HARBAL MULTITRADE PRIVATE LIMITED<br>
            Authorized Training Material - Not for Distribution
        </div>
    </div>

</body>
</html>