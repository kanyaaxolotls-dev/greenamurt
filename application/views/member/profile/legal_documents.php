<style>
  body {
    margin: 0;
    font-family: Arial, sans-serif;
    background: #f2f2f2;
  }

  .slider {
    max-width: 100%;
    background: #fff;
    position: relative;
    overflow: hidden;
  }

  .slides {
    display: flex;
    transition: transform 0.4s ease-in-out;
  }

  .slide {
    min-width: 100%;
    box-sizing: border-box;
    text-align: center;
    padding: 10px;
  }

  /* 🔥 UPDATED IMAGE SIZE */
  .slide img {
    width: 100%;
    height: 85vh;        /* bigger & normal view */
    object-fit: contain;
  }

  .caption {
    margin-top: 8px;
    padding: 6px;
    background: #eee;
    font-size: 14px;
    word-break: break-all;
  }

  .prev, .next {
    position: absolute;
    top: 50%;
    transform: translateY(-50%);
    background: rgba(0,0,0,0.6);
    color: #fff;
    border: none;
    padding: 10px 14px;
    cursor: pointer;
    font-size: 18px;
  }

  .prev { left: 5px; }
  .next { right: 5px; }

  /* Mobile responsive */
  @media (max-width: 600px) {
    .caption {
      font-size: 12px;
    }
    .prev, .next {
      padding: 8px 10px;
    }
    .slide img {
      height: 70vh;   /* better fit on mobile */
    }
  }
</style>

<div class="slider">
  <div class="slides">

    <div class="slide">
      <img src="<?php echo base_url('uploads/legal_documents/GST Certificate.jpg'); ?>" alt="GST Certificate">
      <div class="caption">GST Certificate</div>
    </div>

    <div class="slide">
      <img src="<?php echo base_url('uploads/legal_documents/ESI Approval.jpg'); ?>" alt="ESI Approval">
      <div class="caption">ESI Approval</div>
    </div>

    <div class="slide">
      <img src="<?php echo base_url('uploads/legal_documents/Daimora Udhyog Aadhar.jpg'); ?>" alt="Daimora Udhyog Aadhar">
      <div class="caption">Daimora Udhyog Aadhar</div>
    </div>

    <div class="slide">
      <img src="<?php echo base_url('uploads/legal_documents/Daimora Tan Certificate.jpg'); ?>" alt="Certificate">
      <div class="caption">Certificate</div>
    </div>

    <div class="slide">
      <img src="<?php echo base_url('uploads/legal_documents/Certificate of Incorporation.jpg'); ?>" alt="Certificate of Incorporation">
      <div class="caption">Certificate of Incorporation</div>
    </div>

    <div class="slide">
      <img src="<?php echo base_url('uploads/legal_documents/STU India.jpg'); ?>" alt="STU India">
      <div class="caption">STU India</div>
    </div>

    <div class="slide">
      <img src="<?php echo base_url('uploads/legal_documents/Pancard.jpg'); ?>" alt="Pancard">
      <div class="caption">Pancard</div>
    </div>

  </div>

  <button class="prev">&#10094;</button>
  <button class="next">&#10095;</button>
</div>

<script>
  let index = 0;
  const slides = document.querySelector('.slides');
  const totalSlides = document.querySelectorAll('.slide').length;
  let autoSlide;

  function updateSlider() {
    slides.style.transform = 'translateX(' + (-index * 100) + '%)';
  }

  function startAutoSlide() {
    autoSlide = setInterval(() => {
      index = (index + 1) % totalSlides;
      updateSlider();
    }, 3000); // 3 seconds
  }

  function stopAutoSlide() {
    clearInterval(autoSlide);
  }

  document.querySelector('.next').addEventListener('click', () => {
    stopAutoSlide();
    index = (index + 1) % totalSlides;
    updateSlider();
    startAutoSlide();
  });

  document.querySelector('.prev').addEventListener('click', () => {
    stopAutoSlide();
    index = (index - 1 + totalSlides) % totalSlides;
    updateSlider();
    startAutoSlide();
  });

  // Start auto slide
  startAutoSlide();
</script>

