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
	echo form_open('setting/earning-setting', $attributes); ?>
    <?php //echo form_open() 
	//print_r( config_item('income_name') ); ?>
    <div class="row">
            <div class="col-sm-4">
                <label class="requiredlbl">Level No</label>
                <select class="form-control form-control-alternative form-control-alternative-alternative" name="levlno" id="levlno">
				    <?php for($i=0; $i<=$MAX_LEVEL; $i++) { ?>
						<option value="<?php echo $i; ?>" ><?php echo $i; ?></option>
                    <?php } ?>
                </select>
            </div>
            <div class="col-sm-4">
                <label class="requiredlbl">Target amount (Rs.)</label>
                <input type="text"  class="form-control form-control-alternative form-control-alternative-alternative validate" value="<?php echo set_value('target_amount') ?>" name="target_amount" content="money" mandatory="true" maxlength="10" >
            </div>
            <div class="col-sm-4"> 
                <label class="requiredlbl">Payout cycle </label> <br/>
                <select class="form-control form-control-alternative form-control-alternative-alternative" name="payout_cycle" id="payout_cycle">
					<option value="daily" >Daily</option>
					<option value="weekly">Weekly</option>
					<option value="monthly">Monthly</option>
					<option value="yearly">Yearly</option>
                </select>
            </div>
    </div>
    <div class="row">
        <div class="col-sm-4">
            <label class="requiredlbl">No.of Times </label>
                <input type="text"  class="form-control form-control-alternative form-control-alternative-alternative validate" value="<?php echo set_value('no_of_times') ?>" name="no_of_times" content="number" mandatory="true" maxlength="10">
            
        </div>
		<div class="col-sm-4">
            <label class="requiredlbl">Earning Type</label>
             <select class="form-control form-control-alternative form-control-alternative-alternative" name="earning_type" id="earning_type">
			      <?php $income_name_array = config_item('income_name'); ?>
			        <?php if( is_array($income_name_array) && count($income_name_array)>0 ) { ?> 
						<?php foreach($income_name_array as $earning_type_val) { ?>
							<option value="<?php echo $earning_type_val; ?>" ><?php echo $earning_type_val; ?></option>
						<?php }?>
					<?php }?>
					
             </select>			
            
        </div>
		
        <div class="col-sm-3"><br/>
            <!--<input type="button" class="btn btn-success" value="Save" onclick="this.value='Saving..'">-->
			<button id="addNewButton" class="btn btn-success"><span class="save-button glyphicon glyphicon-floppy-disk">&nbsp;Save</span></button>
        </div>
  
    </div><!------------- MANAGE REWARDS -------------------------------->
  <?php echo form_close() ?>
<br>
<div class="table-responsive">
                <table class="table align-items-center table-flush">
                   <thead class="thead-light">
        <tr>
            <th>SN</th>
            <th>Level No</th>
            <th>Target amount</th>
            <th>Payout cycle</th>
            <th>No.of Times </th>
			 <th>Earning Type </th>
            <th>#Action</th>
        </tr>
    </thead>
        <?php
        $sn = 1;
        foreach ($result as $e) { ?>
            <tr>
                <td><?php echo $sn++; ?></td>
                <td><?php echo $e->levlno; ?></td>
                <td><?php echo $e->target_amount; ?></td>
                <td><?php echo $e->payout_cycle; ?></td>
                <td><?php echo $e->no_of_times; ?></td>
				<td><?php echo $e->earning_type; ?></td>
                <td>
                    <a href="<?php echo site_url('setting/edit-earning/' . $e->id); ?>"
                       class="btn btn-info btn-sm glyphicon glyphicon-pencil">Edit</a>
                    <a onclick="return confirm('Are you sure you want to delete this Income ?')"
                       href="<?php echo site_url('setting/remove-earning/' . $e->id); ?>"
                       class="btn btn-danger btn-sm glyphicon glyphicon-remove">Delete</a>
                </td>
            </tr>
        <?php } ?> 
    </table>
</div>
</div>
</div>
</div>
<style>
  .requiredlbl:after {
    content:" *";
    color: red;
  }
</style>
 

<script type="text/javascript">
 // document.getElementById('based_on').addEventListener('change', function() {
 //        if (this.value == "Other") {
 //            document.getElementById('totalMemPV').disabled = false;
           
 //        } else {
 //            document.getElementById('totalMemPV').disabled = true;
              
 //        }
 //    });
  
 


</script>

