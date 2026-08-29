
    <div class="card bg-secondary shadow">   
        <div class="card-header bg-white border-0"> 
                <div class="row align-items-center">
                    <div class="col-8">
                          <h3 class="mb-0"><?php echo $title ?></h3>
                    </div>
                <div class="col-4 text-right"> 
                  <a href="<?php echo site_url('member/index')?>" class="btn btn-sm btn-primary">Home</a>
                </div>
            </div>
        </div>
<div class="table-responsive">
    <table class="table align-items-center table-flush">
        <thead class="thead-light">
        <tr>
            <th>SN</th>
            <th>Product Name</th>
            <th>Price</th>
            <th>Qty</th>
            <th>Order Date</th>
            <th>Delivery Date</th>
            <th>Sold By</th>
            <th>Status</th>
        </tr>
        </thead>
        <?php
        $this->db->select('id, product_id, status, cost, qty, deliver_date, date, franchisee_id')->from('product_sale')
        ->where('userid', $this->session->user_id)->limit($config['per_page'], $page);
        $data   = $this->db->get()->result();

        $sn = 1;
        
        foreach ($data as $e) {
           ?>
            <tr>
                <td><?php echo $sn++; ?></td>
                <td><?php echo $this->db_model->select('prod_name', 'product', array('id' => $e->product_id)); ?></td>
                <td><?php echo config_item('currency') . $e->cost; ?></td>
                <td><?php echo $e->qty; ?></td>
                <td><?php echo $e->date; ?></td>
                <td><?php echo $e->deliver_date; ?></td>
                <td><?php echo $e->franchisee_id; ?></td>
                <td><?php echo $e->status; ?></td>
            </tr>
        <?php } ?>
    </table>
</div>
</div>
</div>

<div class="card-footer">
    <?php echo $this->pagination->create_links(); ?>
</div>

</div>
</div>
</div><!-- end of panel-->
</div><!-- end of main panel-->