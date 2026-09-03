<style>
    :root {
        --primary-blue: #1e3a8a;
        --emerald: #10b981;
        --gold: #f59e0b;
        --bg-soft: #f8fafc;
    }

    /* Hero Background Logic */
    .exam-container {
        background-color: var(--bg-soft);
        min-height: 100vh;
        padding-bottom: 50px;
        font-family: 'Inter', sans-serif;
    }

    .hero-section {
        background: linear-gradient(135deg, #1e3a8a 0%, #1e40af 100%);
        padding: 60px 0 120px 0;
        position: relative;
        overflow: hidden;
        color: white;
    }

    .hero-section::after {
        content: "";
        position: absolute;
        top: 50%;
        left: 50%;
        width: 600px;
        height: 600px;
        background-image: url('<?php echo base_url("uploads/nadi_illustration.png"); ?>');
        background-size: contain;
        background-repeat: no-repeat;
        opacity: 0.03; /* Faint Hero Watermark */
        transform: translate(-50%, -50%);
        pointer-events: none;
    }

    /* Stats & Cards */
    .stat-card {
        background: white;
        border-radius: 16px;
        padding: 20px;
        text-align: center;
        border: 1px solid #e2e8f0;
        transition: transform 0.3s ease;
        box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.05);
    }

    .stat-card:hover { transform: translateY(-5px); }

    .stat-icon {
        font-size: 24px;
        margin-bottom: 10px;
        display: block;
    }

    /* Progress Timeline */
    .timeline-container {
        display: flex;
        justify-content: space-between;
        max-width: 600px;
        margin: 40px auto;
        position: relative;
    }

    .timeline-step {
        text-align: center;
        z-index: 2;
        flex: 1;
    }

    .step-dot {
        width: 30px;
        height: 30px;
        border-radius: 50%;
        background: #e2e8f0;
        margin: 0 auto 10px;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 12px;
        font-weight: bold;
    }

    .step-dot.active { background: var(--emerald); color: white; box-shadow: 0 0 15px rgba(16, 185, 129, 0.4); }
    .step-dot.current { background: var(--primary-blue); color: white; }

    /* Instructions Card */
    .instruction-card {
        background: rgba(255, 255, 255, 0.8);
        backdrop-filter: blur(10px);
        border: 1px solid rgba(255, 255, 255, 0.3);
        border-radius: 20px;
        padding: 30px;
    }

    /* Success Box */
    .motivation-box {
        background: linear-gradient(to right, #ecfdf5, #f0fdf4);
        border-left: 5px solid var(--emerald);
        padding: 25px;
        border-radius: 12px;
    }

    /* The Exam Questions Styling */
    .question-box {
        background: white;
        border-radius: 16px;
        padding: 25px;
        margin-bottom: 20px;
        border: 1px solid #edf2f7;
    }

    .radio-option {
        padding: 12px 15px;
        border: 1px solid #e2e8f0;
        border-radius: 8px;
        margin-bottom: 10px;
        cursor: pointer;
        transition: all 0.2s;
        display: block;
    }

    .radio-option:hover { background: #f1f5f9; border-color: var(--primary-blue); }

    .btn-premium {
        background: linear-gradient(to bottom, #10b981, #059669);
        color: white;
        padding: 18px 45px;
        font-size: 20px;
        font-weight: 700;
        border-radius: 12px;
        border: none;
        box-shadow: 0 10px 25px rgba(16, 185, 129, 0.3);
        transition: all 0.3s;
    }

    .btn-premium:hover {
        box-shadow: 0 15px 30px rgba(16, 185, 129, 0.4);
        transform: scale(1.02);
    }
    
    .hero-section {
        background: linear-gradient(135deg, #1e3a8a 0%, #1e40af 100%);
        padding: 60px 0 120px 0;
        position: relative;
        overflow: hidden;
        color: #ffffff !important; /* Force all text to white */
    }

    .hero-section h1 {
        color: #ffffff !important;
        font-weight: 800 !important; /* Extra Bold */
        text-shadow: 0px 4px 10px rgba(0, 0, 0, 0.2); /* Premium depth shadow */
        letter-spacing: -1px;
    }

    .hero-section p.lead {
        color: #ffffff !important;
        font-weight: 600 !important; /* Semi-Bold for subtitle */
        opacity: 0.95;
    }
</style>
<div class="exam-container">
    
    <!-- Hero Section -->
    <div class="hero-section text-center">
        <div class="container">
            <!--<img src="<?php echo base_url('uploads/nadi_illustration.png'); ?>" alt="Nadi Vigyan" style="width: 80px; margin-bottom: 20px;">-->
            <h1 class="display-4 fw-bold mb-3">Official Nadi Vigyan Certification Exam</h1>
            <p class="lead mb-0" style="opacity: 0.9;">Test your knowledge of Ancient Ayurvedic Pulse Diagnosis and earn your credentials.</p>
        </div>
    </div>

    <div class="container" style="margin-top: -60px;">
        
        <!-- Statistics Grid -->
        <div class="row g-4 mb-5">
            <div class="col">
                <div class="stat-card">
                    <span class="stat-icon">📘</span>
                    <h6 class="text-muted mb-1">Total Questions</h6>
                    <div class="h5 fw-bold">50 MCQs</div>
                </div>
            </div>
            <div class="col">
                <div class="stat-card">
                    <span class="stat-icon">⏱</span>
                    <h6 class="text-muted mb-1">Duration</h6>
                    <div class="h5 fw-bold">60 Minutes</div>
                </div>
            </div>
            <div class="col">
                <div class="stat-card">
                    <span class="stat-icon">🎯</span>
                    <h6 class="text-muted mb-1">Passing Score</h6>
                    <div class="h5 fw-bold">35 / 50</div>
                </div>
            </div>
            <div class="col">
                <div class="stat-card">
                    <span class="stat-icon">🏆</span>
                    <h6 class="text-muted mb-1">Relevance</h6>
                    <div class="h5 fw-bold">Govt. Standards</div>
                </div>
            </div>
            <div class="col">
                <div class="stat-card">
                    <span class="stat-icon">📜</span>
                    <h6 class="text-muted mb-1">Result</h6>
                    <div class="h5 fw-bold">Digital Certificate</div>
                </div>
            </div>
        </div>
        <!-- NEW SEPARATE PREPARATION CARD -->
        <div class="row mb-4">
            <div class="col-lg-8 mx-auto">
                <div class="card shadow-lg border-0" style="border-radius: 20px; background: linear-gradient(135deg, #ffffff 0%, #f0fdf4 100%);">
                    <div class="card-body p-4">
                        <div class="d-flex align-items-center justify-content-between flex-wrap">
                            <div class="d-flex align-items-center mb-2">
                                <div class="bg-soft-success p-3 rounded-circle me-3" style="background: #ecfdf5;">
                                    <span style="font-size: 30px;">📖</span>
                                </div>
                                <div>
                                    <h4 class="fw-bold mb-1 text-dark">Official Preparation Center</h4>
                                    <p class="text-muted small mb-0">Study the Nadi Vigyan Answer Key before starting your exam.</p>
                                </div>
                            </div>
                            <div class="ms-auto">
                                <a href="<?php echo site_url('member/answer_sheet'); ?>" target="_blank" class="btn btn-lg px-4 shadow-sm" style="background: #f59e0b; color: white; font-weight: 700; border-radius: 12px; transition: 0.3s;">
                                    <i class="fa fa-book-open me-2"></i> VIEW ANSWER KEY
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <!-- END PREPARATION CARD -->
        <!-- Progress Preview -->
        <div class="timeline-container">
            <div class="timeline-step">
                <div class="step-dot active">✓</div>
                <small class="text-muted">Registration</small>
            </div>
            <div class="timeline-step">
                <div class="step-dot active">✓</div>
                <small class="text-muted">Payment</small>
            </div>
            <div class="timeline-step">
                <div class="step-dot current">3</div>
                <small class="fw-bold">Examination</small>
            </div>
            <div class="timeline-step">
                <div class="step-dot">4</div>
                <small class="text-muted">Generation</small>
            </div>
        </div>

        <div class="row pt-4">
            <div class="col-lg-8 mx-auto">
                
                <!-- Welcome & Instructions -->
                <div id="examLanding">
                    <div class="instruction-card shadow-sm mb-4">
                        <h4 class="fw-bold mb-4 text-primary"><i class="fa fa-info-circle me-2"></i>Candidate Instructions</h4>
                        <div class="row">
                            <div class="col-md-6">
                                <ul class="list-unstyled">
                                    <li class="mb-3">✅ Read every question carefully before answering.</li>
                                    <li class="mb-3">✅ Select only one correct answer per question.</li>
                                    <li class="mb-3">✅ Ensure you complete all 50 questions.</li>
                                </ul>
                            </div>
                            <div class="col-md-6">
                                <ul class="list-unstyled">
                                    <li class="mb-3">⚠️ Do not refresh the browser during the exam.</li>
                                    <li class="mb-3">🌐 Maintain a stable internet connection.</li>
                                    <li class="mb-3">🔒 Answers cannot be changed after submission.</li>
                                </ul>
                            </div>
                        </div>

                        <div class="motivation-box mt-4">
                            <h5 class="fw-bold text-success mb-2">Congratulations in Advance!</h5>
                            <p class="mb-0 text-dark" style="font-size: 15px; line-height: 1.6;">
                                You are one step away from becoming a <strong>Certified Nadi Vigyan Practitioner</strong>. This examination evaluates your understanding of pulse diagnosis, Ayurveda fundamentals, Direct Selling concepts, and Binary Plan knowledge. Stay confident and give your best.
                            </p>
                        </div>
                    </div>

                    <div class="text-center py-4">
                        <button class="btn btn-premium btn-lg shadow" onclick="startExam()">
                            🚀 Start Certification Exam
                        </button>
                        <p class="text-muted mt-3 small">Once the exam begins, the timer starts immediately.</p>
                    </div>
                </div>
            
                <!-- Inside your #examForm div -->
                <div id="examForm" style="display:none;">
                    <form action="<?php echo site_url('member/process_quiz') ?>" method="post">
                        
                        <div class="d-flex justify-content-between align-items-center mb-4">
                            <h4 class="fw-bold text-primary">Nadi Vigyan MCQs</h4>
                            <div class="badge bg-danger p-3 h5 mb-0" id="timer">Time Remaining: 60:00</div>
                        </div>
                
                        <?php 
                        $display_number = 1;
                        foreach ($questions as $original_id => $details): 
                        ?>
                            <div class="question-box shadow-sm">
                                <p class="h5 fw-bold mb-3"><?php echo $display_number; ?>. <?php echo $details['q']; ?></p>
                                
                                <?php foreach ($details['options'] as $val => $text): ?>
                                    <label class="radio-option">
                                        <!-- We keep the original ID so your process_quiz function still works perfectly -->
                                        <input type="radio" name="q[<?php echo $original_id; ?>]" value="<?php echo $val; ?>" required> 
                                        <?php echo $val; ?>) <?php echo $text; ?>
                                    </label>
                                <?php endforeach; ?>
                            </div>
                        <?php 
                        $display_number++;
                        endforeach; 
                        ?>
                
                        <div class="text-center mt-5">
                            <button type="submit" class="btn btn-premium px-5">
                                Finish & Submit Certification Exam
                            </button>
                        </div>
                    </form>
                </div>
                <!-- Actual Exam Form (Hidden initially) -->
<!--                <div id="examForm" style="display:none;">-->
<!--                    <form action="<?php echo site_url('member/process_quiz') ?>" method="post">-->
                        
<!--                        <div class="d-flex justify-content-between align-items-center mb-4">-->
<!--                            <h4 class="fw-bold text-primary">Nadi Vigyan MCQs</h4>-->
<!--                            <div class="badge bg-danger p-3 h5 mb-0" id="timer">Time Remaining: 60:00</div>-->
<!--                        </div>-->

                        <!-- Question Loop - Question 1 -->
<!--                       <div class="question-box shadow-sm">-->
<!--    <p class="h5 fw-bold mb-3">1. नाडी परीक्षणात मुख्यतः किती दोष पाहिले जातात?</p>-->
<!--    <label class="radio-option"><input type="radio" name="q[1]" value="A"> A) 2</label>-->
<!--    <label class="radio-option"><input type="radio" name="q[1]" value="B"> B) 3</label>-->
<!--    <label class="radio-option"><input type="radio" name="q[1]" value="C"> C) 4</label>-->
<!--    <label class="radio-option"><input type="radio" name="q[1]" value="D"> D) 5</label>-->
<!--</div>-->

<!--<div class="question-box shadow-sm">-->
<!--    <p class="h5 fw-bold mb-3">2. वात दोषाचे प्रमुख गुणधर्म कोणते?</p>-->
<!--    <label class="radio-option"><input type="radio" name="q[2]" value="A"> A) उष्ण</label>-->
<!--    <label class="radio-option"><input type="radio" name="q[2]" value="B"> B) स्निग्ध</label>-->
<!--    <label class="radio-option"><input type="radio" name="q[2]" value="C"> C) चल</label>-->
<!--    <label class="radio-option"><input type="radio" name="q[2]" value="D"> D) गुरु</label>-->
<!--</div>-->

<!--<div class="question-box shadow-sm">-->
<!--    <p class="h5 fw-bold mb-3">3. पित्त दोषाचा संबंध कोणाशी असतो?</p>-->
<!--    <label class="radio-option"><input type="radio" name="q[3]" value="A"> A) अग्नी</label>-->
<!--    <label class="radio-option"><input type="radio" name="q[3]" value="B"> B) जल</label>-->
<!--    <label class="radio-option"><input type="radio" name="q[3]" value="C"> C) पृथ्वी</label>-->
<!--    <label class="radio-option"><input type="radio" name="q[3]" value="D"> D) आकाश</label>-->
<!--</div>-->

<!--<div class="question-box shadow-sm">-->
<!--    <p class="h5 fw-bold mb-3">4. कफ दोषाचा प्रमुख गुण कोणता?</p>-->
<!--    <label class="radio-option"><input type="radio" name="q[4]" value="A"> A) रूक्ष</label>-->
<!--    <label class="radio-option"><input type="radio" name="q[4]" value="B"> B) स्थिर</label>-->
<!--    <label class="radio-option"><input type="radio" name="q[4]" value="C"> C) चल</label>-->
<!--    <label class="radio-option"><input type="radio" name="q[4]" value="D"> D) तीक्ष्ण</label>-->
<!--</div>-->

<!--<div class="question-box shadow-sm">-->
<!--    <p class="h5 fw-bold mb-3">5. नाडी परीक्षण कोणत्या हातावर केले जाते?</p>-->
<!--    <label class="radio-option"><input type="radio" name="q[5]" value="A"> A) डावा</label>-->
<!--    <label class="radio-option"><input type="radio" name="q[5]" value="B"> B) उजवा</label>-->
<!--    <label class="radio-option"><input type="radio" name="q[5]" value="C"> C) दोन्ही</label>-->
<!--    <label class="radio-option"><input type="radio" name="q[5]" value="D"> D) पाय</label>-->
<!--</div>-->

<!--<div class="question-box shadow-sm">-->
<!--    <p class="h5 fw-bold mb-3">6. पुरुषांची नाडी सामान्यतः कोणत्या हातावर तपासतात?</p>-->
<!--    <label class="radio-option"><input type="radio" name="q[6]" value="A"> A) डावा</label>-->
<!--    <label class="radio-option"><input type="radio" name="q[6]" value="B"> B) उजवा</label>-->
<!--    <label class="radio-option"><input type="radio" name="q[6]" value="C"> C) दोन्ही</label>-->
<!--    <label class="radio-option"><input type="radio" name="q[6]" value="D"> D) कोणताही</label>-->
<!--</div>-->

<!--<div class="question-box shadow-sm">-->
<!--    <p class="h5 fw-bold mb-3">7. स्त्रियांची नाडी सामान्यतः कोणत्या हातावर तपासतात?</p>-->
<!--    <label class="radio-option"><input type="radio" name="q[7]" value="A"> A) डावा</label>-->
<!--    <label class="radio-option"><input type="radio" name="q[7]" value="B"> B) उजवा</label>-->
<!--    <label class="radio-option"><input type="radio" name="q[7]" value="C"> C) दोन्ही</label>-->
<!--    <label class="radio-option"><input type="radio" name="q[7]" value="D"> D) कोणताही</label>-->
<!--</div>-->

<!--<div class="question-box shadow-sm">-->
<!--    <p class="h5 fw-bold mb-3">8. वात नाडीची गती कशासारखी वर्णन केली जाते?</p>-->
<!--    <label class="radio-option"><input type="radio" name="q[8]" value="A"> A) हंस</label>-->
<!--    <label class="radio-option"><input type="radio" name="q[8]" value="B"> B) सर्प</label>-->
<!--    <label class="radio-option"><input type="radio" name="q[8]" value="C"> C) बेडूक</label>-->
<!--    <label class="radio-option"><input type="radio" name="q[8]" value="D"> D) सिंह</label>-->
<!--</div>-->

<!--<div class="question-box shadow-sm">-->
<!--    <p class="h5 fw-bold mb-3">9. पित्त नाडी कशासारखी असते?</p>-->
<!--    <label class="radio-option"><input type="radio" name="q[9]" value="A"> A) सर्प</label>-->
<!--    <label class="radio-option"><input type="radio" name="q[9]" value="B"> B) कावळा</label>-->
<!--    <label class="radio-option"><input type="radio" name="q[9]" value="C"> C) बेडूक</label>-->
<!--    <label class="radio-option"><input type="radio" name="q[9]" value="D"> D) मोर</label>-->
<!--</div>-->

<!--<div class="question-box shadow-sm">-->
<!--    <p class="h5 fw-bold mb-3">10. कफ नाडी कशासारखी असते?</p>-->
<!--    <label class="radio-option"><input type="radio" name="q[10]" value="A"> A) हंस</label>-->
<!--    <label class="radio-option"><input type="radio" name="q[10]" value="B"> B) सर्प</label>-->
<!--    <label class="radio-option"><input type="radio" name="q[10]" value="C"> C) बेडूक</label>-->
<!--    <label class="radio-option"><input type="radio" name="q[10]" value="D"> D) उंदीर</label>-->
<!--</div>-->

<!--<div class="question-box shadow-sm">-->
<!--    <p class="h5 fw-bold mb-3">11. वाताचे किती उपप्रकार आहेत?</p>-->
<!--    <label class="radio-option"><input type="radio" name="q[11]" value="A"> A) 3</label>-->
<!--    <label class="radio-option"><input type="radio" name="q[11]" value="B"> B) 4</label>-->
<!--    <label class="radio-option"><input type="radio" name="q[11]" value="C"> C) 5</label>-->
<!--    <label class="radio-option"><input type="radio" name="q[11]" value="D"> D) 6</label>-->
<!--</div>-->

<!--<div class="question-box shadow-sm">-->
<!--    <p class="h5 fw-bold mb-3">12. प्राण वायू हा कोणाचा उपप्रकार आहे?</p>-->
<!--    <label class="radio-option"><input type="radio" name="q[12]" value="A"> A) पित्त</label>-->
<!--    <label class="radio-option"><input type="radio" name="q[12]" value="B"> B) वात</label>-->
<!--    <label class="radio-option"><input type="radio" name="q[12]" value="C"> C) कफ</label>-->
<!--    <label class="radio-option"><input type="radio" name="q[12]" value="D"> D) रक्त</label>-->
<!--</div>-->

<!--<div class="question-box shadow-sm">-->
<!--    <p class="h5 fw-bold mb-3">13. समान वायूचे मुख्य कार्य काय?</p>-->
<!--    <label class="radio-option"><input type="radio" name="q[13]" value="A"> A) पचन</label>-->
<!--    <label class="radio-option"><input type="radio" name="q[13]" value="B"> B) श्वसन</label>-->
<!--    <label class="radio-option"><input type="radio" name="q[13]" value="C"> C) झोप</label>-->
<!--    <label class="radio-option"><input type="radio" name="q[13]" value="D"> D) बोलणे</label>-->
<!--</div>-->

<!--<div class="question-box shadow-sm">-->
<!--    <p class="h5 fw-bold mb-3">14. पित्ताचे किती उपप्रकार आहेत?</p>-->
<!--    <label class="radio-option"><input type="radio" name="q[14]" value="A"> A) 5</label>-->
<!--    <label class="radio-option"><input type="radio" name="q[14]" value="B"> B) 4</label>-->
<!--    <label class="radio-option"><input type="radio" name="q[14]" value="C"> C) 6</label>-->
<!--    <label class="radio-option"><input type="radio" name="q[14]" value="D"> D) 3</label>-->
<!--</div>-->

<!--<div class="question-box shadow-sm">-->
<!--    <p class="h5 fw-bold mb-3">15. पाचक पित्त कुठे कार्य करते?</p>-->
<!--    <label class="radio-option"><input type="radio" name="q[15]" value="A"> A) हृदय</label>-->
<!--    <label class="radio-option"><input type="radio" name="q[15]" value="B"> B) आमाशय</label>-->
<!--    <label class="radio-option"><input type="radio" name="q[15]" value="C"> C) त्वचा</label>-->
<!--    <label class="radio-option"><input type="radio" name="q[15]" value="D"> D) डोळे</label>-->
<!--</div>-->

<!--<div class="question-box shadow-sm">-->
<!--    <p class="h5 fw-bold mb-3">16. कफाचे किती उपप्रकार आहेत?</p>-->
<!--    <label class="radio-option"><input type="radio" name="q[16]" value="A"> A) 4</label>-->
<!--    <label class="radio-option"><input type="radio" name="q[16]" value="B"> B) 5</label>-->
<!--    <label class="radio-option"><input type="radio" name="q[16]" value="C"> C) 6</label>-->
<!--    <label class="radio-option"><input type="radio" name="q[16]" value="D"> D) 7</label>-->
<!--</div>-->

<!--<div class="question-box shadow-sm">-->
<!--    <p class="h5 fw-bold mb-3">17. तर्पक कफ कुठे असतो?</p>-->
<!--    <label class="radio-option"><input type="radio" name="q[17]" value="A"> A) मेंदू</label>-->
<!--    <label class="radio-option"><input type="radio" name="q[17]" value="B"> B) पोट</label>-->
<!--    <label class="radio-option"><input type="radio" name="q[17]" value="C"> C) त्वचा</label>-->
<!--    <label class="radio-option"><input type="radio" name="q[17]" value="D"> D) पाय</label>-->
<!--</div>-->

<!--<div class="question-box shadow-sm">-->
<!--    <p class="h5 fw-bold mb-3">18. नाडी परीक्षणाचा सर्वोत्तम वेळ कोणता?</p>-->
<!--    <label class="radio-option"><input type="radio" name="q[18]" value="A"> A) सकाळ</label>-->
<!--    <label class="radio-option"><input type="radio" name="q[18]" value="B"> B) दुपार</label>-->
<!--    <label class="radio-option"><input type="radio" name="q[18]" value="C"> C) संध्याकाळ</label>-->
<!--    <label class="radio-option"><input type="radio" name="q[18]" value="D"> D) रात्र</label>-->
<!--</div>-->

<!--<div class="question-box shadow-sm">-->
<!--    <p class="h5 fw-bold mb-3">19. नाडी परीक्षणात किती बोटांचा वापर होतो?</p>-->
<!--    <label class="radio-option"><input type="radio" name="q[19]" value="A"> A) 1</label>-->
<!--    <label class="radio-option"><input type="radio" name="q[19]" value="B"> B) 2</label>-->
<!--    <label class="radio-option"><input type="radio" name="q[19]" value="C"> C) 3</label>-->
<!--    <label class="radio-option"><input type="radio" name="q[19]" value="D"> D) 4</label>-->
<!--</div>-->

<!--<div class="question-box shadow-sm">-->
<!--    <p class="h5 fw-bold mb-3">20. तर्जनी बोट कोणता दोष दर्शवते?</p>-->
<!--    <label class="radio-option"><input type="radio" name="q[20]" value="A"> A) वात</label>-->
<!--    <label class="radio-option"><input type="radio" name="q[20]" value="B"> B) पित्त</label>-->
<!--    <label class="radio-option"><input type="radio" name="q[20]" value="C"> C) कफ</label>-->
<!--    <label class="radio-option"><input type="radio" name="q[20]" value="D"> D) रक्त</label>-->
<!--</div>-->

<!--<div class="question-box shadow-sm">-->
<!--    <p class="h5 fw-bold mb-3">21. Direct Selling म्हणजे काय?</p>-->
<!--    <label class="radio-option"><input type="radio" name="q[21]" value="A"> A) दुकानातून विक्री</label>-->
<!--    <label class="radio-option"><input type="radio" name="q[21]" value="B"> B) ग्राहकाला थेट विक्री</label>-->
<!--    <label class="radio-option"><input type="radio" name="q[21]" value="C"> C) घाऊक विक्री</label>-->
<!--    <label class="radio-option"><input type="radio" name="q[21]" value="D"> D) ऑनलाइन जाहिरात</label>-->
<!--</div>-->

<!--<div class="question-box shadow-sm">-->
<!--    <p class="h5 fw-bold mb-3">22. Direct Selling मध्ये मध्यस्थ असतो का?</p>-->
<!--    <label class="radio-option"><input type="radio" name="q[22]" value="A"> A) हो</label>-->
<!--    <label class="radio-option"><input type="radio" name="q[22]" value="B"> B) नाही</label>-->
<!--    <label class="radio-option"><input type="radio" name="q[22]" value="C"> C) कधी कधी</label>-->
<!--    <label class="radio-option"><input type="radio" name="q[22]" value="D"> D) नेहमी</label>-->
<!--</div>-->

<!--<div class="question-box shadow-sm">-->
<!--    <p class="h5 fw-bold mb-3">23. Direct Seller चे मुख्य काम काय?</p>-->
<!--    <label class="radio-option"><input type="radio" name="q[23]" value="A"> A) उत्पादनाची माहिती देणे</label>-->
<!--    <label class="radio-option"><input type="radio" name="q[23]" value="B"> B) शेती करणे</label>-->
<!--    <label class="radio-option"><input type="radio" name="q[23]" value="C"> C) वाहन चालवणे</label>-->
<!--    <label class="radio-option"><input type="radio" name="q[23]" value="D"> D) बँकिंग</label>-->
<!--</div>-->

<!--<div class="question-box shadow-sm">-->
<!--    <p class="h5 fw-bold mb-3">24. ग्राहक समाधान का महत्त्वाचे?</p>-->
<!--    <label class="radio-option"><input type="radio" name="q[24]" value="A"> A) पुनर्खरेदीसाठी</label>-->
<!--    <label class="radio-option"><input type="radio" name="q[24]" value="B"> B) करासाठी</label>-->
<!--    <label class="radio-option"><input type="radio" name="q[24]" value="C"> C) बँकेसाठी</label>-->
<!--    <label class="radio-option"><input type="radio" name="q[24]" value="D"> D) वाहतुकीसाठी</label>-->
<!--</div>-->

<!--<div class="question-box shadow-sm">-->
<!--    <p class="h5 fw-bold mb-3">25. Direct Selling मध्ये उत्पन्न कशातून मिळते?</p>-->
<!--    <label class="radio-option"><input type="radio" name="q[25]" value="A"> A) उत्पादन विक्री</label>-->
<!--    <label class="radio-option"><input type="radio" name="q[25]" value="B"> B) कर</label>-->
<!--    <label class="radio-option"><input type="radio" name="q[25]" value="C"> C) कर्ज</label>-->
<!--    <label class="radio-option"><input type="radio" name="q[25]" value="D"> D) अनुदान</label>-->
<!--</div>-->

<!--<div class="question-box shadow-sm">-->
<!--    <p class="h5 fw-bold mb-3">26. Network Building म्हणजे?</p>-->
<!--    <label class="radio-option"><input type="radio" name="q[26]" value="A"> A) टीम तयार करणे</label>-->
<!--    <label class="radio-option"><input type="radio" name="q[26]" value="B"> B) घर बांधणे</label>-->
<!--    <label class="radio-option"><input type="radio" name="q[26]" value="C"> C) रस्ता बांधणे</label>-->
<!--    <label class="radio-option"><input type="radio" name="q[26]" value="D"> D) संगणक दुरुस्ती</label>-->
<!--</div>-->

<!--<div class="question-box shadow-sm">-->
<!--    <p class="h5 fw-bold mb-3">27. Follow-up का आवश्यक आहे?</p>-->
<!--    <label class="radio-option"><input type="radio" name="q[27]" value="A"> A) ग्राहक टिकवण्यासाठी</label>-->
<!--    <label class="radio-option"><input type="radio" name="q[27]" value="B"> B) खर्च वाढवण्यासाठी</label>-->
<!--    <label class="radio-option"><input type="radio" name="q[27]" value="C"> C) वेळ घालवण्यासाठी</label>-->
<!--    <label class="radio-option"><input type="radio" name="q[27]" value="D"> D) कर भरण्यासाठी</label>-->
<!--</div>-->

<!--<div class="question-box shadow-sm">-->
<!--    <p class="h5 fw-bold mb-3">28. Presentation म्हणजे?</p>-->
<!--    <label class="radio-option"><input type="radio" name="q[28]" value="A"> A) उत्पादन माहिती सादरीकरण</label>-->
<!--    <label class="radio-option"><input type="radio" name="q[28]" value="B"> B) परीक्षा</label>-->
<!--    <label class="radio-option"><input type="radio" name="q[28]" value="C"> C) खेळ</label>-->
<!--    <label class="radio-option"><input type="radio" name="q[28]" value="D"> D) लेखा</label>-->
<!--</div>-->

<!--<div class="question-box shadow-sm">-->
<!--    <p class="h5 fw-bold mb-3">29. Prospecting म्हणजे?</p>-->
<!--    <label class="radio-option"><input type="radio" name="q[29]" value="A"> A) संभाव्य ग्राहक शोधणे</label>-->
<!--    <label class="radio-option"><input type="radio" name="q[29]" value="B"> B) उत्पादन बनवणे</label>-->
<!--    <label class="radio-option"><input type="radio" name="q[29]" value="C"> C) पॅकिंग</label>-->
<!--    <label class="radio-option"><input type="radio" name="q[29]" value="D"> D) वितरण</label>-->
<!--</div>-->

<!--<div class="question-box shadow-sm">-->
<!--    <p class="h5 fw-bold mb-3">30. Direct Selling मध्ये नैतिकता आवश्यक आहे का?</p>-->
<!--    <label class="radio-option"><input type="radio" name="q[30]" value="A"> A) हो</label>-->
<!--    <label class="radio-option"><input type="radio" name="q[30]" value="B"> B) नाही</label>-->
<!--    <label class="radio-option"><input type="radio" name="q[30]" value="C"> C) कधी कधी</label>-->
<!--    <label class="radio-option"><input type="radio" name="q[30]" value="D"> D) गरज नाही</label>-->
<!--</div>-->

<!--<div class="question-box shadow-sm">-->
<!--    <p class="h5 fw-bold mb-3">31. Binary Plan मध्ये किती Legs असतात?</p>-->
<!--    <label class="radio-option"><input type="radio" name="q[31]" value="A"> A) 1</label>-->
<!--    <label class="radio-option"><input type="radio" name="q[31]" value="B"> B) 2</label>-->
<!--    <label class="radio-option"><input type="radio" name="q[31]" value="C"> C) 3</label>-->
<!--    <label class="radio-option"><input type="radio" name="q[31]" value="D"> D) 4</label>-->
<!--</div>-->

<!--<div class="question-box shadow-sm">-->
<!--    <p class="h5 fw-bold mb-3">32. Binary Plan मधील Legs कोणते?</p>-->
<!--    <label class="radio-option"><input type="radio" name="q[32]" value="A"> A) Left आणि Right</label>-->
<!--    <label class="radio-option"><input type="radio" name="q[32]" value="B"> B) Up आणि Down</label>-->
<!--    <label class="radio-option"><input type="radio" name="q[32]" value="C"> C) Front आणि Back</label>-->
<!--    <label class="radio-option"><input type="radio" name="q[32]" value="D"> D) East आणि West</label>-->
<!--</div>-->

<!--<div class="question-box shadow-sm">-->
<!--    <p class="h5 fw-bold mb-3">33. Pair म्हणजे?</p>-->
<!--    <label class="radio-option"><input type="radio" name="q[33]" value="A"> A) Left व Right व्यवसायाची जुळणी</label>-->
<!--    <label class="radio-option"><input type="radio" name="q[33]" value="B"> B) फक्त Left</label>-->
<!--    <label class="radio-option"><input type="radio" name="q[33]" value="C"> C) फक्त Right</label>-->
<!--    <label class="radio-option"><input type="radio" name="q[33]" value="D"> D) ग्राहक</label>-->
<!--</div>-->

<!--<div class="question-box shadow-sm">-->
<!--    <p class="h5 fw-bold mb-3">34. Binary Bonus कशावर आधारित असतो?</p>-->
<!--    <label class="radio-option"><input type="radio" name="q[34]" value="A"> A) Pair</label>-->
<!--    <label class="radio-option"><input type="radio" name="q[34]" value="B"> B) Rank</label>-->
<!--    <label class="radio-option"><input type="radio" name="q[34]" value="C"> C) Age</label>-->
<!--    <label class="radio-option"><input type="radio" name="q[34]" value="D"> D) Gender</label>-->
<!--</div>-->

<!--<div class="question-box shadow-sm">-->
<!--    <p class="h5 fw-bold mb-3">35. Weak Leg म्हणजे?</p>-->
<!--    <label class="radio-option"><input type="radio" name="q[35]" value="A"> A) कमी व्यवसाय असलेली बाजू</label>-->
<!--    <label class="radio-option"><input type="radio" name="q[35]" value="B"> B) जास्त व्यवसाय असलेली बाजू</label>-->
<!--    <label class="radio-option"><input type="radio" name="q[35]" value="C"> C) नवीन सदस्य</label>-->
<!--    <label class="radio-option"><input type="radio" name="q[35]" value="D"> D) ग्राहक</label>-->
<!--</div>-->

<!--<div class="question-box shadow-sm">-->
<!--    <p class="h5 fw-bold mb-3">36. Strong Leg म्हणजे?</p>-->
<!--    <label class="radio-option"><input type="radio" name="q[36]" value="A"> A) जास्त व्यवसाय असलेली बाजू</label>-->
<!--    <label class="radio-option"><input type="radio" name="q[36]" value="B"> B) कमी व्यवसाय असलेली बाजू</label>-->
<!--    <label class="radio-option"><input type="radio" name="q[36]" value="C"> C) रिकामी बाजू</label>-->
<!--    <label class="radio-option"><input type="radio" name="q[36]" value="D"> D) ग्राहक</label>-->
<!--</div>-->

<!--<div class="question-box shadow-sm">-->
<!--    <p class="h5 fw-bold mb-3">37. Binary Plan मध्ये Spillover होऊ शकतो का?</p>-->
<!--    <label class="radio-option"><input type="radio" name="q[37]" value="A"> A) हो</label>-->
<!--    <label class="radio-option"><input type="radio" name="q[37]" value="B"> B) नाही</label>-->
<!--    <label class="radio-option"><input type="radio" name="q[37]" value="C"> C) कधी कधी</label>-->
<!--    <label class="radio-option"><input type="radio" name="q[37]" value="D"> D) माहिती नाही</label>-->
<!--</div>-->

<!--<div class="question-box shadow-sm">-->
<!--    <p class="h5 fw-bold mb-3">38. Pair Ratio चे उदाहरण?</p>-->
<!--    <label class="radio-option"><input type="radio" name="q[38]" value="A"> A) 1:1</label>-->
<!--    <label class="radio-option"><input type="radio" name="q[38]" value="B"> B) 2:1</label>-->
<!--    <label class="radio-option"><input type="radio" name="q[38]" value="C"> C) 3:2</label>-->
<!--    <label class="radio-option"><input type="radio" name="q[38]" value="D"> D) वरील सर्व</label>-->
<!--</div>-->

<!--<div class="question-box shadow-sm">-->
<!--    <p class="h5 fw-bold mb-3">39. Binary Tree मध्ये पहिल्या स्तरावर किती सदस्य?</p>-->
<!--    <label class="radio-option"><input type="radio" name="q[39]" value="A"> A) 1</label>-->
<!--    <label class="radio-option"><input type="radio" name="q[39]" value="B"> B) 2</label>-->
<!--    <label class="radio-option"><input type="radio" name="q[39]" value="C"> C) 3</label>-->
<!--    <label class="radio-option"><input type="radio" name="q[39]" value="D"> D) 4</label>-->
<!--</div>-->

<!--<div class="question-box shadow-sm">-->
<!--    <p class="h5 fw-bold mb-3">40. Binary Plan चा मुख्य फायदा?</p>-->
<!--    <label class="radio-option"><input type="radio" name="q[40]" value="A"> A) टीम वाढ</label>-->
<!--    <label class="radio-option"><input type="radio" name="q[40]" value="B"> B) शेती</label>-->
<!--    <label class="radio-option"><input type="radio" name="q[40]" value="C"> C) वाहन</label>-->
<!--    <label class="radio-option"><input type="radio" name="q[40]" value="D"> D) बँक</label>-->
<!--</div>-->

<!--<div class="question-box shadow-sm">-->
<!--    <p class="h5 fw-bold mb-3">41. Carry Forward म्हणजे?</p>-->
<!--    <label class="radio-option"><input type="radio" name="q[41]" value="A"> A) उरलेला व्यवसाय पुढे नेणे</label>-->
<!--    <label class="radio-option"><input type="radio" name="q[41]" value="B"> B) रद्द करणे</label>-->
<!--    <label class="radio-option"><input type="radio" name="q[41]" value="C"> C) विक्री</label>-->
<!--    <label class="radio-option"><input type="radio" name="q[41]" value="D"> D) खरेदी</label>-->
<!--</div>-->

<!--<div class="question-box shadow-sm">-->
<!--    <p class="h5 fw-bold mb-3">42. Binary मध्ये Balance का महत्त्वाचे?</p>-->
<!--    <label class="radio-option"><input type="radio" name="q[42]" value="A"> A) Pair मिळण्यासाठी</label>-->
<!--    <label class="radio-option"><input type="radio" name="q[42]" value="B"> B) करासाठी</label>-->
<!--    <label class="radio-option"><input type="radio" name="q[42]" value="C"> C) पगारासाठी</label>-->
<!--    <label class="radio-option"><input type="radio" name="q[42]" value="D"> D) वाहतुकीसाठी</label>-->
<!--</div>-->

<!--<div class="question-box shadow-sm">-->
<!--    <p class="h5 fw-bold mb-3">43. Left Leg रिकामी असल्यास Pair मिळेल का?</p>-->
<!--    <label class="radio-option"><input type="radio" name="q[43]" value="A"> A) हो</label>-->
<!--    <label class="radio-option"><input type="radio" name="q[43]" value="B"> B) नाही</label>-->
<!--    <label class="radio-option"><input type="radio" name="q[43]" value="C"> C) कधी कधी</label>-->
<!--    <label class="radio-option"><input type="radio" name="q[43]" value="D"> D) माहिती नाही</label>-->
<!--</div>-->

<!--<div class="question-box shadow-sm">-->
<!--    <p class="h5 fw-bold mb-3">44. Binary Plan मध्ये Teamwork आवश्यक आहे का?</p>-->
<!--    <label class="radio-option"><input type="radio" name="q[44]" value="A"> A) हो</label>-->
<!--    <label class="radio-option"><input type="radio" name="q[44]" value="B"> B) नाही</label>-->
<!--    <label class="radio-option"><input type="radio" name="q[44]" value="C"> C) कधी कधी</label>-->
<!--    <label class="radio-option"><input type="radio" name="q[44]" value="D"> D) गरज नाही</label>-->
<!--</div>-->

<!--<div class="question-box shadow-sm">-->
<!--    <p class="h5 fw-bold mb-3">45. Pair Bonus म्हणजे?</p>-->
<!--    <label class="radio-option"><input type="radio" name="q[45]" value="A"> A) Pair झाल्यावर मिळणारे उत्पन्न</label>-->
<!--    <label class="radio-option"><input type="radio" name="q[45]" value="B"> B) ग्राहक बोनस</label>-->
<!--    <label class="radio-option"><input type="radio" name="q[45]" value="C"> C) कर बोनस</label>-->
<!--    <label class="radio-option"><input type="radio" name="q[45]" value="D"> D) प्रवास बोनस</label>-->
<!--</div>-->

<!--<div class="question-box shadow-sm">-->
<!--    <p class="h5 fw-bold mb-3">46. Binary Plan मध्ये Growth कशावर अवलंबून असते?</p>-->
<!--    <label class="radio-option"><input type="radio" name="q[46]" value="A"> A) टीम व्यवसाय</label>-->
<!--    <label class="radio-option"><input type="radio" name="q[46]" value="B"> B) वय</label>-->
<!--    <label class="radio-option"><input type="radio" name="q[46]" value="C"> C) लिंग</label>-->
<!--    <label class="radio-option"><input type="radio" name="q[46]" value="D"> D) गाव</label>-->
<!--</div>-->

<!--<div class="question-box shadow-sm">-->
<!--    <p class="h5 fw-bold mb-3">47. Binary Structure मध्ये प्रत्येक सदस्याला किती Direct Position?</p>-->
<!--    <label class="radio-option"><input type="radio" name="q[47]" value="A"> A) 2</label>-->
<!--    <label class="radio-option"><input type="radio" name="q[47]" value="B"> B) 3</label>-->
<!--    <label class="radio-option"><input type="radio" name="q[47]" value="C"> C) 4</label>-->
<!--    <label class="radio-option"><input type="radio" name="q[47]" value="D"> D) 5</label>-->
<!--</div>-->

<!--<div class="question-box shadow-sm">-->
<!--    <p class="h5 fw-bold mb-3">48. Volume म्हणजे?</p>-->
<!--    <label class="radio-option"><input type="radio" name="q[48]" value="A"> A) व्यवसाय मूल्य</label>-->
<!--    <label class="radio-option"><input type="radio" name="q[48]" value="B"> B) वजन</label>-->
<!--    <label class="radio-option"><input type="radio" name="q[48]" value="C"> C) उंची</label>-->
<!--    <label class="radio-option"><input type="radio" name="q[48]" value="D"> D) वेळ</label>-->
<!--</div>-->

<!--<div class="question-box shadow-sm">-->
<!--    <p class="h5 fw-bold mb-3">49. Matching Bonus कोणाला मिळतो?</p>-->
<!--    <label class="radio-option"><input type="radio" name="q[49]" value="A"> A) पात्र सदस्यांना</label>-->
<!--    <label class="radio-option"><input type="radio" name="q[49]" value="B"> B) ग्राहकांना</label>-->
<!--    <label class="radio-option"><input type="radio" name="q[49]" value="C"> C) दुकानदारांना</label>-->
<!--    <label class="radio-option"><input type="radio" name="q[49]" value="D"> D) चालकांना</label>-->
<!--</div>-->

<!--<div class="question-box shadow-sm">-->
<!--    <p class="h5 fw-bold mb-3">50. Binary Plan मध्ये Leadership महत्त्वाची आहे का?</p>-->
<!--    <label class="radio-option"><input type="radio" name="q[50]" value="A"> A) हो</label>-->
<!--    <label class="radio-option"><input type="radio" name="q[50]" value="B"> B) नाही</label>-->
<!--    <label class="radio-option"><input type="radio" name="q[50]" value="C"> C) कधी कधी</label>-->
<!--    <label class="radio-option"><input type="radio" name="q[50]" value="D"> D) गरज नाही</label>-->
<!--</div>-->

<!--                        <div class="text-center mt-5">-->
<!--                            <button type="submit" class="btn btn-premium px-5">-->
<!--                                Finish & Submit Certification Exam-->
<!--                            </button>-->
<!--                        </div>-->
<!--                    </form>-->
<!--                </div>-->

            </div>
        </div>
    </div>
</div>

<script>
    function startExam() {
        document.getElementById('examLanding').style.display = 'none';
        document.getElementById('examForm').style.display = 'block';
        window.scrollTo(0, 0);
        startTimer(3600); // 60 minutes
    }

    function startTimer(duration) {
        var timer = duration, minutes, seconds;
        setInterval(function () {
            minutes = parseInt(timer / 60, 10);
            seconds = parseInt(timer % 60, 10);

            minutes = minutes < 10 ? "0" + minutes : minutes;
            seconds = seconds < 10 ? "0" + seconds : seconds;

            document.getElementById('timer').textContent = "Time Remaining: " + minutes + ":" + seconds;

            if (--timer < 0) {
                alert("Time up! Your exam is being submitted.");
                document.forms[0].submit();
            }
        }, 1000);
    }
</script>