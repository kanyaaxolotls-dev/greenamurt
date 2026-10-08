<div class="col">
    <div class="card bg-secondary shadow">
        <div class="card-header bg-white border-0">
            <div class="row align-items-center">
                <div class="col-8">
                    <h3 class="mb-0">Sponsor Income</h3>
                </div>
                <div class="col-4 text-right">
                    <a href="<?php echo site_url('admin')?>" class="btn btn-sm btn-primary">Home</a>
                </div>
            </div>
        </div>
        <div class="card-body">


            <table class="table align-items-center table-flush">
                <thead class="thead-light">
                    <tr>
                        <th scope="col">S.N.</th>
                        <th scope="col">User ID</th>
                        <th scope="col">User Name</th>
                        <th scope="col">matching income</th>
                        <th scope="col">tds</th>
                        <th scope="col">admin charges</th>
                        <th scope="col">sponsor income(5% of M.I)</th>
                        <th scope="col">paid sponsor inc</th>

                    </tr>
                </thead>
                <?php



        $sn = 1; 
        foreach ($earning as $e) { ?>
                <tr>
                    <td><?php echo $sn++; ?></td>
                    <td><?php echo $e['id']; ?></td>
                    <td><?php echo $this->db_model->select('name', 'member', array('id' =>$e['id'])); ?></td>
                    <td><?php 
                              $this->db->select('id')->where('sponsor', $e['id']);
                              $data = $this->db->get('member')->result();
                              // $data = $this->db_model->select_multi('id', 'earning', array('sponsor>='=>$e['userid']));
                              
                              foreach($data as $i){
                              
                              $this->db->select("userid,type,sum(amount) AS total")
                              ->group_start()
                              ->where('type', 'Matching Income')
                              ->where('userid',$i->id)
                              ->group_end()
                              ->group_by(array('userid', 'type'));
                              $data = $this->db->get('earning')->result();
                              foreach($data as $d){
                              if($d->total != ""){

                                $sp_amt=$d->total*(5/100);
            $admin=$sp_amt*(config_item('admin_charges')/100);
            $tds=$sp_amt*(config_item('payout_tax')/100);
            $cal_amt=$sp_amt-($admin+$tds);
            echo $d->total;
                              }
                              }  
                             
                              
                              }
                              if($data->total == ""){
                                echo 0;
                            }


          ?></td>
                    <td><?php echo config_item('payout_tax'); ?></td>
                    <td><?php echo config_item('admin_charges'); ?></td>
                    <td><?php
            if($cal_amt != 0){
                echo $cal_amt;
                $cal_amt =NULL;
            }else{
                echo 0;
            }
            
            ?></td>

                    <td><?php $o=$this->db_model->sum('amount', 'earning', array('userid'=>$e['id'],'type'=>'sponsor inc'));
            if($o != ""){
                echo $o;
            }else{
                echo "0";
            }
            ?></td>

                </tr>
                <?php } ?>
            </table>

        </div>

    </div>
</div>