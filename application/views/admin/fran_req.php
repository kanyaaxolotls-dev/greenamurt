
<div class="col">
    <div class="card">  
        <div class="card-header bg-white border-0"> 
            <div class="row align-items-center">
                <div class="col-8">
                    <h3 class="mb-0">Pick-up Centre Request View</h3>
                </div>
                <div class="col-4 text-right"> 
                    <a href="<?php echo site_url('Franch/fran_request')?>" class="btn btn-sm btn-primary">All</a>
                    <a href="<?php echo site_url('Franch/fran_request/Pending')?>" class="btn btn-sm btn-warning">Pending</a>
                    <a href="<?php echo site_url('Franch/fran_request/Approve')?>" class="btn btn-sm btn-success">Approved</a>
                    <a href="<?php echo site_url('Franch/fran_request/Reject')?>" class="btn btn-sm btn-danger">Rejected</a>
                </div> 
            </div>
        </div>

        <div class="table-responsive">
            <table id="example" class="table align-items-center table-flush">
                <thead class="thead-light">
                    <tr>
                        <th>Pick-up Centre Id</th>
                        <th>Name</th>
                        <th>Order Id</th>
                        <th>Products</th>
                        <th>Payment Proof</th>
                        <th>Total Amount</th>
                        <th>Date</th>
                        <th>Action</th>
                    </tr>
                </thead>
                <tbody>
                    <?php 
                        foreach ($req as $items):
                            if($items['img'] == null){
                                $pic = '404.png';
                            }else{
                                $pic = $items['img'];
                            }
                    ?>
                        <tr>
                            <td><?php echo $items['userid']; ?></td>
                            <td><?php echo $this->db_model->select('name','franchisee',array('id' => $items['userid'])); ?></td>
                            <td><?php echo $items['orderid']; ?></td>
                            <td><?php echo $this->db_model->count_all('fran_req',array('orderid' => $items['orderid'])); ?></td>
                            <td><a target="_" href="<?= base_url('uploads/'.$pic) ?>">View</a></td>
                            <td><?php echo config_item('currency') . $items['total_price']; ?></td>
                            <td><?php echo $items['date']; ?></td>
                            <td>
                                <a href="<?= base_url('franch/view_order/'.$items['orderid']) ?>" class="btn btn-sm btn-info approve-btn">View Order</a>
                                <?php if($items['status'] == 'Pending'){ ?>
                                <a href="<?= base_url('franch/approve/'.$items['orderid']) ?>" class="btn btn-sm btn-success approve-btn">Approve</a>
                                <a href="<?= base_url('franch/reject/'.$items['orderid']) ?>" class="btn btn-sm btn-danger reject-btn">Reject</a>
                                <?php }else{ ?>
                                <button class="btn btn-sm btn-secondary"><?= $items['status'].'ed' ?></button>
                                <?php } ?>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>

        <div class="card-footer">
            <a href="<?php echo site_url('admin')?>" class="btn btn-sm btn-primary">&larr; Go Back</a>
        </div>
    </div>
</div>

