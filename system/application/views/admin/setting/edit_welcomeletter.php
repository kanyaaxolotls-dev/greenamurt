<?php
$welcome_letter = file_get_contents(FCPATH . '/uploads/welcome_letter.txt');
?>
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
    <div class="col-md-12">
        <label>Design Your Welcome Letter Very Carefully</label>
        <div class="alert alert-info">
            <strong>Note: </strong><br/>
            {{member_name}} : Member Name<br/>
            {{member_phone}} : Member Phone No<br/>
            {{member_email}} : Member Email ID<br/>
            {{member_add}} : Member Address<br/>
            {{member_sponsor}} : Sponsor ID<br/>
            {{member_join_date}} : Member Join Date<br/>
            {{member_purchased}} : Purchased Package<br/>
        </div>
        <textarea class="form-control form-control-alternative form-control-alternative-alternative" id="editor"
                  name="welcome_letter"><?php echo set_value('welcome_letter', $welcome_letter) ?></textarea>
    </div>

     <div class="col-md-6"><br/>
        <input type="submit" class="btn btn-success" value="Update" onclick="this.value='Updating..'">
    </div>


    <?php echo form_close() ?>

</div>
</div>
</div>
