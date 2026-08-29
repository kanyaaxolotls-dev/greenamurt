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
 <?php $attributes = array('name' => 'frm_earning_settings', 'id' => 'frm_earning_settings');
	echo form_open('setting/edit-earning/'.$result->id, $attributes); ?>
<div class="row">
    <div class="col-sm-4">
       <label class="requiredlbl">Level No</label>
		<select class="form-control form-control-alternative form-control-alternative-alternative" name="levlno" id="levlno">
			<?php for($i=0; $i<=$MAX_LEVEL; $i++) { ?>
				<option value="<?php echo $i; ?>" <?php if($result->levlno==$i): echo 'selected="selected"'; endif; ?> ><?php echo $i; ?></option>
			<?php } ?>
		</select>
    </div>
    <div class="col-sm-4">
		 <label class="requiredlbl">Target amount (Rs.)</label>
         <input type="text"  class="form-control form-control-alternative form-control-alternative-alternative validate" value="<?php echo set_value('target_amount', $result->target_amount) ?>" name="target_amount" content="money" mandatory="true" maxlength="10" >
    </div>
    <div class="col-sm-4">
       <label class="requiredlbl">Payout cycle </label> <br/>
		<select class="form-control form-control-alternative form-control-alternative-alternative" name="payout_cycle" id="payout_cycle">
			<option value="daily" <?php if($result->payout_cycle=='daily'): echo 'selected="selected"'; endif; ?> >Daily</option>
			<option value="weekly" <?php if($result->payout_cycle=='weekly'): echo 'selected="selected"'; endif; ?>>Weekly</option>
			<option value="monthly" <?php if($result->payout_cycle=='monthly'): echo 'selected="selected"'; endif; ?>>Monthly</option>
			<option value="yearly" <?php if($result->payout_cycle=='yearly'): echo 'selected="selected"'; endif; ?> >Yearly</option>
		</select>
    </div>
    <input type="hidden" name="id" value="<?php echo $result->id ?>">
    <div class="col-sm-4">
		 <label class="requiredlbl">No.of Times </label>
         <input type="text"  class="form-control form-control-alternative form-control-alternative-alternative validate" value="<?php echo set_value('no_of_times',$result->no_of_times) ?>" name="no_of_times" content="number" mandatory="true" maxlength="10">
    </div>
	<div class="col-sm-4">
            <label class="requiredlbl">Earning Type</label>
             <select class="form-control form-control-alternative form-control-alternative-alternative" name="earning_type" id="earning_type">
			      <?php $income_name_array = config_item('income_name'); ?>
			        <?php if( is_array($income_name_array) && count($income_name_array)>0 ) { ?> 
						<?php foreach($income_name_array as $earning_type_val) { ?>
							<option value="<?php echo $earning_type_val; ?>" <?php if($result->earning_type==$earning_type_val): echo 'selected="selected"'; endif; ?> ><?php echo $earning_type_val; ?></option>
						<?php }?>
					<?php }?>
					
             </select>			
            
        </div>
    
   <div class="col-sm-4"><br/>
       <!-- <input type="submit" class="btn btn-success" value="Update" onclick="this.value='Updating..'"> -->
	   <button id="addNewButton" class="btn btn-success"><span class="save-button glyphicon glyphicon-floppy-disk">&nbsp;Save</span></button>
    </div>
  
</div>
  <?php echo form_close() ?>
  </div>
</div>
</div>
