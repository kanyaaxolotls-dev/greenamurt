   <div class="col">
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
            <div class="card-body"> 
                <div class="table-responsive">
                   <table class="table align-items-center table-flush" id="example">
                        <thead class="thead-dark">
                        <tr>
                            <td>S.N.</td>
                            <td>Product Name</td>
                            <td>Member</td>
                            <td>Phone</td>
                            <td>Qty</td>
                            <td>Total Cost</td>
                            <td>Address</td>
                            <td>Admin Status</td>
                            <td>Delivery Date</td>
                            <td>Action</td>
                        </tr>
                        </thead>
                        <tbody>
                        <?php
                            $sn = 1;
                            foreach ($data as $data):
                                $userinfo = $this->db_model->select_multi('*', 'member', array('id' => $data->userid));
                                if($data->admin_status == 'Waiting'){
                                    $clr = 'warning';
                                } elseif($data->admin_status == 'Approved'){
                                    $clr = 'success';
                                } else{
                                    $clr = 'danger';
                                }
                        ?>
                            <tr class='bg-secondary'>
                                <td class=''><?php echo $sn++ ?></td>
                                <td class=''><?php echo $this->db_model->select('prod_name', 'product', array('id' => $data->product_id)); ?></td>
                                <td class=''><?php echo $userinfo->name.' | '.$data->userid ?></td>
                                <td class=''><?php echo $userinfo->phone ?></td>
                                <td class=''><?php echo $data->qty ?></td>
                                <td class=''><?php echo config_item('currency') . $data->cost ?></td>
                                <td class=''><?php echo $userinfo->state.' '.$userinfo->city.' '.$userinfo->address ?></td>
                                <td class=''><span class="btn btn-sm btn-<?= $clr ?>"><?php echo $data->admin_status ?></span></td>
                                <td class=''><?php echo $data->deliver_date ?></td>
                                <td class=''><a class="btn btn-sm btn-primary" href="<?= base_url('franchisee/invoice/'.$data->orderid); ?>">View Order</a></td>
                            </tr>
                        <?php endforeach; ?>
                        </tbody>
                    </table>

                </div>
            </div>
        </div>
</div></div></div></div>