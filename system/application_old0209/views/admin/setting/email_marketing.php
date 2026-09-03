<div class="col">
    <div class="card bg-secondary shadow">  
        <div class="card-header bg-white border-0"> 
        <div class="row align-items-center">
            <div class="col-8">
                  <h3 class="mb-0"><?php echo $title;?></h3>
            </div>
        <div class="col-4 text-right"> 
            <a href="<?php echo site_url('admin/index')?>" class="btn btn-sm btn-primary">Home</a>
        </div>
    </div>
</div> 
<div class="card-body">
     <?php echo form_open() ?>
<div class="row">
   
    <div class="col-sm-6">
        <label>Enter Email IDs (Separated by Commas)</label>
        <input type="text" class="form-control form-control-alternative form-control-alternative-alternative" name="email_id" id="email_id">
      
        <input onclick="disable('#all', '#email_id')" type="checkbox" id="all" name="all" value="1"> Send eMail to All
        Member
    </div>
    <div class="col-sm-6">
        <label>Subject</label>
        <input type="text" class="form-control form-control-alternative form-control-alternative-alternative" name="subject">
    </div>
    <div class="col-sm-12">
        <label>Mail Content</label>
        <textarea name="msg" class="form-control form-control-alternative form-control-alternative-alternative" id="editor"></textarea>
    </div>
    <div class="col-sm-6"><br/>
        <input type="submit" class="btn btn-success" value="Send" onclick="this.value='Sending..'">
    </div>
   
</div>
 <?php echo form_close() ?>
 </div>
</div>
</div>

<script type="text/javascript">
    function disable(id, id2) {
        if ($(id).prop("checked") == true) {
            $(id2).hide('slow');
        } else {
            $(id2).show('slow');
        }
    }
</script>