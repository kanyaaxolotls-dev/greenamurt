
    <div class=" bg-secondary shadow">   
        <div class="card-header bg-white border-0"> 
                <div class="row align-items-center">
                    <div class="col-9">
                          <h4 class="mb-0"><?php echo $title ?></h4>
                    </div>
                <div class="col-3 text-right"> 
                    
                    <a href="<?php echo site_url('member/index')?>" class="btn btn-sm btn-primary">Home</a>
                </div>
            </div>
        </div>
        <?php if($error = $this->session->flashdata('msg')){ ?>
       <p style="color: green;"><strong>Success!</strong> <?php echo  $error; ?><p>
        <?php } ?>
      
        <div class="container">
            <div class="form-group">
                  <?php echo form_open('member/send') ?>
                  <div class="row">
                    <div class="col-sm-12 col-md-7">
                        <label>Your Email id *</label>
                        <input type="email" name="from" class="form-control" placeholder="Enter Email" required><br>
                    </div>
                    <div class="col-sm-12 col-md-7">
                        <label>Subject *</label>
                        <input type="text" name="Subject" class="form-control" placeholder="Subject" required><br>
                    </div>
                    <div class="col-sm-12 col-md-7">
                        <label>Describe your problem *</label>
                        <textarea name="message" class="form-control" placeholder="Enter message here" required></textarea><br>
                            
                        </textarea>
                    </div>
                    <div class="col-sm-12 col-md-7">
                        <br/>
                        <button type="submit" class="btn btn-primary btn-block">Send Message</button>
                        <br/><br/>
                    </div>
                      </div>

                <?php echo form_close() ?>
            </div>
            
        </div>
  </div>
