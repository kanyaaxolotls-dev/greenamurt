<div class="container">
  <div class="row">
    <div class="col-md-offset-1 col-md-10">
      <h3 class="contactus-title">You Have Got Questions We have Got Answers</h3>
      <p class="text-center contact-desc">make a type specimen book. It has survived not only five centuries, but also the leap into electronic typesetting, remaining essentially unchanged. It was popularised in the 1960s with the release of Letraset sheets containing Lorem Ipsum passages.</p>
    </div>
  </div>
  <div class="row">
    <div class="col-sm-4">
      <div class="complaint">
        <h2 class="tf">Tel</h2>
        <div class="call-info">0123 456 789 / 0123 456 788</div>
      </div>
    </div>
    <div class="col-sm-4">
      <div class="email">
        <h2 class="tf">Mail</h2>
        <div class="email-info">info@<?php echo $_SERVER['HTTP_HOST'] ?></div>
      </div>
    </div>
    <div class="col-sm-4">
      <div class="time">
        <h2 class="tf">Time</h2>
        <div class="time-info">Mon – Sat: 9:00 – 18:00</div>
      </div>
    </div>
  </div>
  <div class="main-form">
    <h3 class="contactus-title">Leave Message</h3>
    <div class="row">
      <form name="contactform" method="POST" action="contact-form-handler.php">
        <div class="col-sm-6">
          <input type="text" required name="name" placeholder="Name">
        </div>
        <div class="col-sm-6 ">
          <input type="email" required name="email" placeholder="Email">
        </div>
        <div class="col-sm-6 ">
          <input type="text" required name="phone" placeholder="Phone Number">
        </div>
        <div class="col-sm-6 ">
          <input type="text" required name="subject" placeholder="Subject">
        </div>
        <div class="col-xs-12 ">
          <textarea required name="message" placeholder="Message" rows="3" cols="30"></textarea>
        </div>
        <div class="col-xs-12  text-center">
          <div class="commun-btn">
            <button type="submit" name="submit" class="btn">Submit</button>
          </div>
        </div>
      </form>
    </div>
  </div>

</div>