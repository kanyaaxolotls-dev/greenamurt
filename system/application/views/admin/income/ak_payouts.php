 <div class="col">
    <div class="card">
        <div class="card-body">
            <?php echo form_open('income/pay_cycle'); ?>
            <div class="row">
            <!--    <div class="col-sm-4">-->
            <!--        <label>Select Earning Type</label>-->
            <!--        <select class="form-control" name="pay_type" required>-->
            <!--            <option value="" disabled selected>Select Income Type</option>-->
                        <?php 
                            // if($type){
                            //     echo "<option value='$type' selected>$type</option>";
                            // } 
                        ?>
                <!--        <option value="Matching Income">Matching Income</option>-->
                <!--        <option value="Matching Sponsor Inc">Matching Sponsor Inc</option>-->
                <!--    </select>-->
                <!--</div>-->
                <div class="col-sm-6">
                    <label>Start Date</label>
                    <input type="date" required class="form-control" id="sdate" name="sdate" value="<?= $sdate ?>">
                </div>
                <div class="col-sm-6">
                    <label>End Date</label>
                    <input type="date" required class="form-control" id="edate" name="edate" value="<?= $edate ?>">
                </div>
                <div class="col-sm-6 mt-3">
                    <button type="submit"  class="btn btn-success">Show</button>
                </div>
            </div>
            <?php echo form_close(); ?>
        </div>
        <div class="card-header bg-white border-0">
            <div class="row align-items-center">
                <div class="col-8">
                    <h3 class="mb-0"><?= $title ?></h3>
                </div>
                <div class="col-4 text-right">
                    <?php if($data){ ?>
                    <?php echo form_open('cron/gen_cron'); ?>
                        <input type="hidden" name="pay_type" value="<?= $type ?>">
                        <input type="hidden" name="sdate" value="<?= $sdate ?>">
                        <input type="hidden" name="edate" value="<?= $edate ?>">
                        <button type="submit"  class="btn btn-md btn-danger" onclick="return confirm('Are you sure ?')">Generate Payout</button>
                    <?php echo form_close(); ?>
                    <?php } else{ ?>
                    <a href="<?php echo site_url('admin')?>" class="btn btn-sm btn-primary">Home </a>
                    <?php } ?>
                </div>
            </div>
        </div>
        <div class="table-responsive">
            <table id="example" class="table align-items-center table-flush">
                <thead class="thead-light">
                    <tr>
                        <th scope="col">S.N.</th>
                        <th scope="col">User ID</th>
                        <th scope="col">Amount <?php echo config_item('currency')?> </th>
                        <th scope="col">Type</th>
                        <th scope="col">Date</th>
                    </tr>
                </thead>
                <tbody>
                <?php $sn = 1; foreach ($data as $e) { ?>
                    <tr>
                        <td><?php echo $sn++; ?></td>
                        <td><?php echo config_item('ID_EXT') . $e['userid']; ?></td>
                        <td><?php echo $e['amount']; ?></td>
                        <td><?php echo $e['type']; ?></td>
                        <td><?php echo $e['date']; ?></td>
                    </tr>
                <?php } ?>
                </tbody>
            </table>
        </div>
    </div>
</div>
    
    