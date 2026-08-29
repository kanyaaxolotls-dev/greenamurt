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
                <label>Counting Based On</label>
                <select class="form-control form-control-alternative form-control-alternative-alternative" name="based_on" id="based_on">
                    <option value="Pair" selected>Pair ( Bv Match )</option>
                </select>
            </div>
            <div class="col-sm-6">
                <label>Rank Name</label>
                <input type="text"  class="form-control form-control-alternative form-control-alternative-alternative" value="<?php echo set_value('rank_name') ?>" name="rank_name">
            </div>
            <div class="col-sm-6 mt-2"> 
                <label>Achieve Duration <br/> </label>
                <input type="text" class="form-control form-control-alternative form-control-alternative-alternative" value="<?php echo set_value('rank_duration', '0') ?>" name="rank_duration"><span style="font-size: 11px">( Within how many days he/she should achieve this  ? 0 for no duration )</span>
            </div>
        <div class="col-sm-6 mt-2">
            <label>Total Pair</label>
            <input type="text" class="form-control form-control-alternative form-control-alternative-alternative" id="totalMemPV" value="<?php echo set_value('total_member', '0') ?>"name="total_member"/><span style="font-size: 11px">(How many members required to achive rank )</span> 
        </div>
    </div>
    <div class="row">
        <div class="col-sm-12 mt-2">
            <label>Reward Amount</label>
            <input type="number"  class="form-control form-control-alternative form-control-alternative-alternative"  name="amount">
        </div>
        <div class="col-sm-3 mt-4">
            <input type="submit" class="btn btn-success" value="Save" onclick="this.value='Saving..'">
        </div>
    </div>
  <?php echo form_close() ?>
<br>
<div class="table-responsive">
       <table class="table align-items-center table-flush" id="example">
            <thead class="thead-light">
        <tr>
            <th>SN</th>
            <th>Income Name</th>
            <th>Amount</th>
            <th>Pair</th>
            <th>Duration</th> 
            <th>#Action</th>
        </tr>
    </thead>
        <?php
        $sn = 1;
        foreach ($result as $e) { ?>
            <tr>
                <td><?php echo $sn++; ?></td>
                <td><?php echo $e->rank_name; ?></td>
                <td><?php echo '₹ '.$e->amount; ?></td>
                <td><?php echo $e->total_member ; ?></td>
                <td><?php echo $e->rank_duration ; ?></td>
                <td>
                    <a href="<?php echo site_url('setting/edit-rank/' . $e->id); ?>"
                       class="btn btn-info btn-sm glyphicon glyphicon-pencil">Edit</a>
                    <a onclick="return confirm('Are you sure you want to delete this Income ?')"
                       href="<?php echo site_url('setting/remove-rank/' . $e->id); ?>"
                       class="btn btn-danger btn-sm glyphicon glyphicon-remove">Delete</a>
                </td>
            </tr>
        <?php } ?> 
    </table>
</div>
</div>
</div>
</div>


