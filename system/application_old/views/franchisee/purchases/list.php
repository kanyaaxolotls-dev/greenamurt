
    <div class="card bg-secondary shadow">   
        <div class="card-header bg-white border-0"> 
                <div class="row align-items-center">
                    <div class="col-8">
                          <h3 class="mb-0"><?php echo $title ?></h3>
                    </div>
                <div class="col-4 text-right"> 
                  <a href="<?php echo site_url('franchisee')?>" class="btn btn-sm btn-primary">Home</a>
                </div>
            </div>
        </div>
        <div class="table-responsive">
            <table class="table align-items-center table-flush" id="example">
                <thead>
                    <tr>
                        <th>Sr. No.</th>
                        <th>Name</th>
                        <th>Order Id</th>
                        <th>Total Products</th>
                        <th>Payment Proof</th>
                        <th>Total Amount</th>
                        <th>Status</th>
                        <th>Date</th>
                        <th>Action</th>
                    </tr>
                </thead>
                <tbody class="text-dark">
                    <?php 
                        $sn = 1;
                        foreach ($req as $items):
                            if($items['img'] == null){
                                $pic = '404.png';
                            }else{
                                $pic = $items['img'];
                            }
                            if($items['status'] == 'Approve'){
                                $clr = 'success';
                            } elseif($items['status'] == 'Reject'){
                                $clr = 'danger';
                            } else{
                                $clr = 'warning';
                            }
                    ?>
                        <tr>
                            <td><?php echo $sn++; ?></td>
                            <td><?php echo $this->db_model->select('name','franchisee',array('id' => $items['userid'])); ?></td>
                            <td><?php echo $items['orderid']; ?></td>
                            <td><?php echo $this->db_model->count_all('fran_req',array('orderid' => $items['orderid'])); ?></td>
                            <td><a target="_" href="<?= base_url('uploads/'.$pic) ?>">View</a></td>
                            <td><?php echo config_item('currency') . $items['total_price']; ?></td>
                            <td class="text-<?= $clr; ?>"><?php echo  $items['status']; ?></td>
                            <td><?php echo $items['date']; ?></td>
                            <td>
                                <a href="<?= base_url('franchisee/view_order/'.$items['orderid']) ?>" class="btn btn-sm btn-info approve-btn">View Order</a>
                                <?php if($items['status'] == 'Pending'){ ?>
                                <a href="<?= base_url('franchisee/approve/'.$items['orderid']) ?>" class="btn btn-sm btn-success approve-btn">Approve</a>
                                <a href="<?= base_url('franchisee/reject/'.$items['orderid']) ?>" class="btn btn-sm btn-danger reject-btn">Reject</a>
                                <?php }else{ ?>
                                <button class="btn btn-sm btn-secondary"><?= $items['status'].'ed' ?></button>
                                <?php } ?>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>

</div>
</div>
</div><!-- end of panel-->
</div><!-- end of main panel-->








