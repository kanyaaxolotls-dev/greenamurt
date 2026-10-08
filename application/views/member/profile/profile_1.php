<!--Added : 1. GST no input section  and  Readonly attribute was removed from this input tag so that client can re-enter GST no again 
            2. Profile pic input added and for displaying also one section is added
            


-->


<?php $data= $this->db_model->select_multi('*', 'member_profile', array('userid' => $this->session->user_id));
// var_dump($data);
// echo "<br>";
// echo "<br>";
// echo "<br>";
// var_dump($my);
// die;
?>
<div class="bg-secondary shadow">   
        <div class="card-header  border-0"> 
                <div class="row align-items-center">
                    <div class="col-8">
                          <h3 class="mb-0"><?php echo $title ?></h3>
                    </div>
                <div class="col-4 text-right"> 
                    <a href="<?php echo site_url('cart/new-purchase')?>" class="btn btn-sm btn-primary">Home</a>
                </div>
            </div>
        </div>
<div class="card-body"> 

<?php echo form_open_multipart() ?>
<div class="row">
    <div class="col-md-4 col-sm-12">
        <label>My Name<span style="color: red">*</span></label>
        <input type="text" class="form-control" name="my_name"
               value="<?php echo set_value('my_name', $this->session->name) ?>">
    </div>
    <div class="col-md-4 col-sm-12">
        <label>My Phone No<span style="color: red">*</span></label>
        <input type="text" class="form-control" name="my_phone"
               value="<?php echo set_value('my_phone', $my->phone) ?>">
    </div>
    <div class="col-md-4 col-sm-12">    
      <label>Pan / Tax No<span style="color: red">*</span></label>
        <input type="text" class="form-control" name="tax_no" value="<?php echo set_value('tax_no', $data->tax_no) ?>">
    </div>
    

</div>

<div class="row">
     <div class="col-md-4 col-sm-12">
        <label>Bank Name</label>
        <input type="text" class="form-control" name="bank_name"
               value="<?php echo set_value('bank_name', $data->bank_name) ?>">
    </div>
     <div class="col-md-4 col-sm-12">
     <label>Bank A/C No <span style="color:red">*</span></label>
    <?php if($data->bank_ac_no == ""){ ?>
    <input type="text" required class="form-control" name="bank_ac_no"
           value="<?php echo set_value('bank_ac_no', $data->bank_ac_no) ?>">
      <?php }else{ ?>
        <input type="text" class="form-control" name="bank_ac_no"
           value="<?php echo set_value('bank_ac_no', $data->bank_ac_no) ?>">
           <?php } ?>
    </div>
     <div class="col-md-4 col-sm-12">
     <label>Bank IFSC Code <span style="color:red">*</span></label>
    <?php if($data->bank_ifsc == ""){ ?>
    <input required type="text" class="form-control" name="bank_ifsc"
           value="<?php echo set_value('bank_ifsc', $data->bank_ifsc) ?>">
           <?php }else{ ?>
            <input type="text" class="form-control" name="bank_ifsc"
           value="<?php echo set_value('bank_ifsc', $data->bank_ifsc) ?>">  
           <?php } ?>
    </div>
</div>

<div class="row">
       <div class="col-md-4 col-sm-12">
          <label>Bank Branch Name</label>
          <input type="text" class="form-control" name="bank_branch"
                 value="<?php echo set_value('bank_branch', $data->bank_branch) ?>">
      </div>
      <!--  <div class="col-md-4 col-sm-12">
          <label>Bitcoin Address</label>
          <input type="text" class="form-control" name="btc_address"
                 value="<?php echo set_value('btc_address', $data->btc_address) ?>">
      </div> -->
       <div class="col-md-4 col-sm-12">
          <label>Nominee Name</label>
          <input type="text" class="form-control" name="nominee_name"
                 value="<?php echo set_value('nominee_name', $data->nominee_name) ?>">
      </div>

       <div class="col-md-4 col-sm-12">    
        <label>Nominee Address</label>
          <input type="text" class="form-control" name="nominee_add"
                 value="<?php echo set_value('nominee_add', $data->nominee_add) ?>">
      </div>
</div>
<div class="row">
     <div class="col-md-4 col-sm-12">    
      <label>Nominee Relation</label>
        <input type="text" class="form-control" name="nominee_relation"
               value="<?php echo set_value('nominee_relation', $data->nominee_relation) ?>">
    </div>
     <div class="col-md-4 col-sm-12">
        <label>Date of Birth</label>
        <input type="text" class="form-control datepicker" name="date_of_birth"
               value="<?php echo set_value('date_of_birth', $data->date_of_birth) ?>">
    </div>
     
    <div class="col-md-4 col-sm-12">
        <label>My Email Id</label>
        <input type="text" class="form-control" name="my_email"
               value="<?php echo set_value('my_email', $my->email) ?>">
    </div>


</div>
<div class="row">
 <div class="col-md-4 col-sm-12">
    <label>Aadhar No</label>
    <input type="text" class="form-control" name="aadhar_no"
           value="<?php echo set_value('aadhar_no', $data->aadhar_no) ?>">
</div>
<!--  <div class="col-md-4 col-sm-12">
    <label>GSTIN</label>
    <input type="text" class="form-control" name="gstin" value="<?php echo set_value('gstin', $data->gstin) ?>">
</div> -->

 <div class="col-md-4 col-sm-12">
    <label>Address Proof</label>
     <input type="file" class="form-control" name="add_proof" >
</div>
 <div class="col-md-4 col-sm-12">
 <label>ID Proof</label>
 <input type="file" class="form-control" name="id_proof">
</div>
</div>

<div class="row">
 <div class="col-md-4 col-sm-12">
    <label>Current Password <span style="color: red">*</span></label>
    <input required type="password" class="form-control" name="oldpass" placeholder="******">
</div>
<div class="col-md-4 col-sm-12">    
      <label>GST No<span style="color: red">*</span></label>
      <?php if($data->gstin == "" || $data->gstin == "NA"){ ?>
        <input required type="text" class="form-control" name="gstin" 
        value="<?php echo set_value('gstin', $data->gstin) ?>">
        <?php }else{ ?>
        <!--Note: only "readonly" was removed from below input tag so that client can re-enter GST no again 
            input readonly type="text" class="form-control" name="gstin" -->
            <input type="text" class="form-control" name="gstin"
           value="<?php echo set_value('gstin', $data->gstin) ?>">  
           <?php } ?>
    </div>
    <div class="col-md-4 col-sm-12">
    <label>Profile Pic</label>
     <input type="file" class="form-control" name="prof_pic" >
</div>

   
</div>
<br>
<div class="row">

        <div class="col-md-4 col-sm-4 col-xs-12"><label>Address Proof:</label><br>
            <img src="<?php echo $data->add_proof ? base_url('uploads/'.$data->add_proof) : base_url('uploads/default.jpg'); ?>"
                class="img-thumbnail img-responsive img-rounded" style="width:90%;height:90%">
        </div>
        
        <br>

        <div class="col-md-4 col-sm-4 col-xs-12"><label>Id proof:</label> <br>

            <img src="<?php echo $data->id_proof ? base_url('uploads/'.$data->id_proof) : base_url('uploads/default.jpg'); ?>"
                class="img-thumbnail img-responsive img-rounded" style="width:90%;height:90%">
        </div>

        <div class="col-md-4 col-sm-4 col-xs-12"><label>Profile Pic:</label> <br>

            <img src="<?php echo $data->prof_pic ? base_url('uploads/'.$data->prof_pic) : base_url('uploads/default.jpg'); ?>"
                class="img-thumbnail img-responsive img-rounded" style="width:90%;height:90%">
        </div>
</div>

<div class="row">
 <div class="col-md-4 col-sm-12">
    <br/>
    
    <button type="submit" class="btn btn-primary">Update Profile</button>
</div>
</div>

<?php echo form_close() ?>


</div>
</div>
</div>
</div>
</div>
