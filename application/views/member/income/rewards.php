<div class="row">
    <div class="col-12">
        <div class="page-title-box d-sm-flex align-items-center justify-content-between">
            <h4 class="mb-sm-0 font-size-18"><?php echo $title ?></h4>

            <div class="page-title-right">
                <ol class="breadcrumb m-0">
                    <li class="breadcrumb-item"><a href="javascript: void(0);">Member</a></li>
                    <li class="breadcrumb-item active"><?php echo $title ?></li>
                </ol>
            </div>

        </div>
    </div>
</div>

<div class="row">
<div class="col-12">
<div class="card">
        <div class="card-header">
                 <div class="row mb-2">
                    <div class="col-sm-4">
                        <h4 class="card-title"><?php echo $title ?></h4>
                        <p class="card-title-desc">This is earning list
                           
                        </p>
                    </div>
                    <div class="col-sm-8">
                        <div class="text-sm-end">
                            <button type="button" class="btn btn-success btn-rounded waves-effect waves-light mb-2 me-2"><i class="mdi mdi-plus me-1"></i> Add New Order</button>
                        </div>
                    </div><!-- end col-->
                </div>
            </div>
            <div class="card-body">
                    <table id="datatable-buttons" class="table align-middle table-nowrap table-check">
                     <thead class="table-light">
                  
                    <tr>
                        <th style="width: 20px;" class="align-middle">
                            <div class="form-check font-size-16">
                                <input class="form-check-input" type="checkbox" id="checkAll">
                                <label class="form-check-label" for="checkAll"></label>
                            </div>
                        </th>
                        <th class="align-middle">Userid</th>
                        <th class="align-middle">Reward Name</th>
                        <th class="align-middle">Date</th>
                        <th class="align-middle">Paid date</th>
                        <!--<th class="align-middle">Trans. Id</th>-->
                        <th class="align-middle">Status</th>
                    </tr>
                    </thead> 

                    <tbody>
                        <?php  $sn = 1;
                        
                            foreach ($rewards as $re) { ?>
                            <tr>
                             <td>
                                <div class="form-check font-size-16">
                                    <input class="form-check-input" type="checkbox" id="orderidcheck01">
                                    <label class="form-check-label" for="orderidcheck01"></label>
                                </div>
                            </td>
                            <td><?php echo $sn++; ?></td>
                            <td><?php echo $re['reward_gift']; ?></td>
                            <td><?php echo $re['date']; ?></td>
                            <td>
                                <?php if ($re['status'] == 'Pending'): ?>
                                        NA
                                <?php else: ?>
                                    <?= date('d-m-Y', strtotime($re['paid_date'])); ?>
                                <?php endif; ?>
                            </td>
                            <td>
                                <?php if ($re['status'] == 'Pending'): ?>
                                    <span class="btn btn-danger btn-sm"><?= $re['status']; ?></span>
                                <?php else: ?>
                                    <span class="btn btn-success btn-sm"><?= $re['status']; ?></span>
                                <?php endif; ?>
                            
                            </td>
                        </tr>
                        <?php } ?>
                    </tbody>
                </table>
            </div>
        </div>
        <!-- end cardaa -->
    </div> <!-- end col -->
</div> <!-- end row -->
