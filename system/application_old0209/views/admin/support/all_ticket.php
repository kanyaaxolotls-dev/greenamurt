    <div class="col">
        <div class="card bg-secondary shadow">  
            <div class="card-header bg-white border-0"> 
                <div class="row align-items-center">
                    <div class="col-8"> 
                        <h3 class="mb-0">View Support Ticket</h3>
                    </div>
                    <div class="col-4 text-right">  
                        <a href="<?php echo site_url('admin/index')?>" class="btn btn-sm btn-primary">Home</a>
                    </div> 
                </div>  
            </div>  
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table align-items-center table-flush" id="example">
                        <thead class="thead-light">
                            <tr>
                                <th scope="col">SN</th>
                                <th scope="col">Userid</th>
                                <th scope="col">Ticket Subject</th>
                                <th scope="col">Date</th>
                                <th scope="col" style="background-color: #d6e9c6">Status</th>
                                <th scope="col">#</th>
                            </tr>
                        </thead>
                        <?php
                        $sn = 1;
                        foreach ($data as $e) { ?>
                        <tr>
                            <td><?php echo $sn++; ?></td>
                            <td><?php echo $e->userid; ?></td>
                            <td><?php echo $e->ticket_title; ?></td>
                            <td><?php echo $e->date; ?></td>
                            <td style="color: green"><?php echo $e->status; ?></td>
                            <td><a href="<?php echo site_url('ticket/view/' . $e->id) ?>" class="btn btn-sm btn-danger">View</a>
                            </td>
                        </tr>
                        <?php } ?>
                    </table>
                </div>
            </div>
        </div>
    </div>

