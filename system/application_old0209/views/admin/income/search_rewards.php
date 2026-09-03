<div class="col">
    <div class="card bg-secondary shadow">  
        <div class="card-header bg-white border-0"> 
        <div class="row align-items-center">
            <div class="col-8"> 
                  <h3 class="mb-0">Search Reward</h3>
            </div>
        <div class="col-4 text-right"> 
            <a href="<?php echo site_url('admin')?>" class="btn btn-sm btn-primary">Home</a>
        </div> 
    </div>
</div> 
<div class="card-body">


    <form method="post" action="<?php echo site_url('income/search_rewards') ?>">
    <div class="row">   
        <div class="col-sm-3">
            <label>User Id</label>
            <input type="text" name="user_id" class="form-control">
        </div>
        <div class="col-sm-3">
            <label>Status</label>
            <select name="status" class="form-control">
                <option selected>All</option>
                <option>Pending</option>
                <option>Delivered</option>
            </select>
        </div>
        <div class="col-sm-3">
            <label>Start Date</label>
            <input type="text" readonly class="form-control datepicker" name="sdate">
        </div>
        <div class="col-sm-3">
            <label>End Date</label>
            <input type="text" readonly class="form-control datepicker" name="edate">
          
        </div>
        <div class="col-sm-3"><br>
              <button type="submit" class="btn btn-success">Search &rarr;</button>
        </div>
        </div>
    </form>

</div>
</div>
</div>
