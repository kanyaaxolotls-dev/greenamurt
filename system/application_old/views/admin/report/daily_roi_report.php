<div class="col"> 
    <div class="card bg-secondary shadow">  
        <div class="card-header bg-white border-0"> 
                <div class="row align-items-center">
                    <div class="col-9">
                          <h4 class="mb-0"><?php echo $title ?></h4>
                    </div>
                <div class="col-3 text-right"> 
                   <a href="<?php echo site_url('admin/index')?>" class="btn btn-sm btn-primary">Home</a>
                </div>
            </div>
        </div>
    <div class="card-body"> 
  
        <form method="post" action="" onsubmit="return false;">
            <div class="row">
                    <div class="col">
                        <div class="form-group">
                            <select name="status" id="status" class="form-control form-control-alternative">
                                <option value="">Select</option>
                                <option value="pending">Pending</option>
                                <option value="paid">Paid</option>
                            </select>
                        </div>
                    </div>
                    <div class="col">
                          <div class="form-group">
                            <div class="input-group input-group-alternative">
                                <div class="input-group-prepend">
                                    <span class="input-group-text"><i class="ni ni-calendar-grid-58"></i></span>
                                </div>
                                <input class="form-control datepicker" placeholder="Select date" type="text" name="s_date" id="s_date" >
                            </div>
                        </div>


                    </div>
                    <div class="col">
                     <div class="form-group">
                            <div class="input-group input-group-alternative">
                                <div class="input-group-prepend">
                                    <span class="input-group-text"><i class="ni ni-calendar-grid-58"></i></span>
                                </div>
                                <input class="form-control datepicker" placeholder="Select date" type="text" name="e_date" id="e_date">
                            </div>
                        </div>
                       
                    </div>
					 
                    <div class="col">
					
                        <button type="submit" class="btn btn-primary btn-block" name="search" id="search">Search</button>
                    </div>
					<div class="col">
						<button type="reset" class="btn btn-danger" name="res" id="res">Reset</button>
					 </div>
					
             </div>
        </form>
   

 <div class="table-responsive">
        <table class="table align-items-center table-flush" id="dailyroilists" width="100%" cellspacing="0">
        <thead class="thead-light">
        <tr>
            <th>User ID</th>
			<th>Date</th>
			<th>No.of ROI</th>
            <th>Amount <?php config_item('currency') ?></th>
            <th>Tax(5%) <?php config_item('currency') ?></th>
            <th>Admin(10%) <?php config_item('currency') ?></th>
            <th>Net Amt.</th>
            <th>Type</th>
            <th>Status</th>
        </tr>
        </thead>
        
    </table>
</div>

</div>
</div>
</div>
</div>
</div></div>

