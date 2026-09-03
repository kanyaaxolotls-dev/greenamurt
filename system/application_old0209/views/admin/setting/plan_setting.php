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
             
                <div class="col-sm-3">  
                    <label>Choose Plan?</label>
                     <select class="form-control form-control-alternative" name="plantype">
                        <option selected><?php echo config_item('plantype') ?></option>
                        <option>None</option>
                        <option>Referral Plan</option>
                        <option>Binary</option>
                        <option>Referral</option>
                        <option>Binary ROI</option>
                        <option>Single Leg with Autopool</option>
                    </select>
                    
                </div>
              
              <!--   <div class="col-sm-4">
                    <label>Non PAN. TDS (%)</label>
                    <input type="text" class="form-control form-control-alternative" value="<?php //echo set_value('payout_tax2', config_item('payout_tax2')) ?>"
                           name="payout_tax2">
                </div> -->
              


                <div class="col-sm-4"><br/>
                    <input type="submit" class="btn btn-primary" value="Update" onclick="this.value='Updating..'">
                </div>
              
            </div>
              <?php echo form_close() ?>
          </div>
      </div>
    </div>