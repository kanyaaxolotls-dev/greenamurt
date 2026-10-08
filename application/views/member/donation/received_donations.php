<?php
/***************************************************************************************************
 * Copyright (c) 2017. by Haren Sarma, Exolim
 * This project is developed and maintained by Exolim IT Services Pvt Ltd.
 * Nobody is permitted to modify the source or any part of the project without permission.
 * Project Developer: Haren Sarma
 * Developed for: Exolim IT Services Pvt Ltd
 **************************************************************************************************/
?>
<div class="table-responsive">
    <table class="table table-striped table-bordered">
        <tr>
            <th>SN</th>
            <th>Level</th>
            <th>Amount</th>
            <th>Sender Id</th>
            <th>Transaction Detail</th>
            <th>Transaction Date</th>
        </tr>
        <?php
        $sn = 1;
        foreach ($result as $e) { ?>
            <tr>
                <td><?php echo $sn++; ?></td>
                <td>
                    Stage <?php echo $this->db_model->select('donation_level', 'donation_package', array('id' => $e->donation_pack)); ?></td>
                <td><?php echo config_item('currency') . $e->donation_amount; ?></td>
                <td><?php echo config_item('ID_EXT') . $e->sender_id; ?></td>
                <td><?php echo $e->trid; ?></td>
                <td><?php echo $e->tdate; ?></td>
            </tr>
        <?php } ?>
    </table>
</div>
<div class="pull-right">
    <?php echo $this->pagination->create_links(); ?>
</div>