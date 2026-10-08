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
                <div class="col-sm-4">
                    <label>Earning Name</label>
                    <select class="form-control form-control-alternative form-control-alternative-alternative" name="income_name">
                        <option>Level Income</option>
                        <option>Matching Income</option>
                        <option>Direct Income</option>
                        <option>Other Income</option>
                    </select>
                </div>
                <div class="col-sm-4">
                    <label>Level No</label>
                    <select class="form-control form-control-alternative form-control-alternative-alternative" name="level_no">
                        <option selected>1</option>
                        <option>2</option>
                        <option>3</option>
                        <option>4</option>
                        <option>5</option>
                        <option>6</option>
                        <option>7</option>
                        <option>8</option>
                        <option>9</option>
                        <option>10</option>
                        <option>11</option>
                        <option>12</option>
                        <option>13</option>
                        <option>14</option>
                        <option>15</option>
                        <option>16</option>
                        <option>17</option>
                        <option>18</option>
                        <option>19</option>
                        <option>20</option>
                    </select>
                </div>
                <div class="col-sm-4">
                    <label>Total Member</label>
                    <input type="text" class="form-control form-control-alternative form-control-alternative-alternative" value="<?php echo set_value('total_member', $result->total_member) ?>"
                           name="total_member">
                </div>
                <div class="col-sm-4">
                    <label>Earning Amount</label>
                    <input type="text" class="form-control form-control-alternative form-control-alternative-alternative" value="<?php echo set_value('amount') ?>" name="amount">
                </div>
                <div class="col-sm-4">
                    <label>Achieve Duration <br/> </label>
                    <input type="text" class="form-control form-control-alternative form-control-alternative-alternative" value="<?php echo set_value('income_duration', '0') ?>"
                           name="income_duration"><span style="font-size: 11px">( Within how many days he/she should achieve this  ? 0 for no duration )</span>
                </div>
                <div class="col-sm-4"><br/>
                    <input type="submit" class="btn btn-success" value="Save" onclick="this.value='Saving..'">
                </div>
            </div><!------------- MANAGE REWARDS -------------------------------->
            <?php echo form_close() ?>


             <div class="table-responsive">
                <table class="table align-items-center table-flush">
                   <thead class="thead-light">
                    <tr>
                        <th scope="col">SN</th>
                        <th scope="col">Income Name</th>
                        <th scope="col">Level No</th>
                        <th scope="col">Amount</th>
                        <th scope="col">#</th>
                    </tr>
                    </thead>
                    <?php
                    $sn = 1;
                    foreach ($result as $e) { ?>
                        <tr>
                            <td><?php echo $sn++; ?></td>
                            <td><?php echo $e->income_name; ?></td>
                            <td><?php echo $e->level_no; ?></td>
                            <td><?php echo config_item('currency') . $e->amount; ?></td>
                            <td>
                                <a href="<?php echo site_url('income/edit-level-wise-income/' . $e->id); ?>"
                                   class="btn btn-info btn-sm glyphicon glyphicon-pencil">Edit</a>
                                <a onclick="return confirm('Are you sure you want to delete this Income ?')"
                                   href="<?php echo site_url('income/remove-level-wise-income/' . $e->id); ?>"
                                   class="btn btn-danger btn-sm glyphicon glyphicon-remove">Delete</a>
                            </td>
                        </tr> 
                    <?php } ?>
                </table>
            </div>
    </div>
    <div class="card-footer">
         <small class="text-muted">Warning ! if you don't know what you are doing. Please do not modify this setting. Call our support
    team if you have any query or you want to learn to how to use this setting. From here you can set when an user will
    get commission and on which condition basis.</small>

</div>
</div>
</div>