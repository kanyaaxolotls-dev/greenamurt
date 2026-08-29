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

           <div class="table-responsive">
              <table class="table align-items-center table-flush">
                <thead class="thead-light">
                    <tr>
                        <th scope="col">SN</th>
                        <th scope="col">Name</th>
                        <th scope="col">Price + Tax</th>
                        <th scope="col">Image</th>
                        <th scope="col">Qty Avlb.</th>
                        <th scope="col">Qty Sold</th>
                        <th scope="col">Joining Product ?</th>
                        <th scope="col">Actions</th>
                    </tr>
                    <?php
                    $sn = 1;
                    foreach ($prod as $e) { ?> 
                        <tr>
                            <td><?php echo $sn++; ?></td>
                            <td><?php echo $e['prod_name']; ?></td>
                            <td><?php echo config_item('currency') . " " . $this->common_model->filter($e['prod_price'] + ($e['prod_price'] * $e['gst'] / 100), 'decimal'); ?></td>
                            <td>
                                <img src="<?php echo $e['image'] ? base_url('uploads/' . $e['image']) : base_url('uploads/default.jpg'); ?>"
                                     class="img-thumbnail img-responsive" style="max-height: 100px"></td>
                            <td><?php echo $e['qty']; ?></td>
                            <td><?php echo $e['sold_qty']; ?></td>
                            <td><?php echo $e['show_on_regform']; ?></td>
                            <td>

                                <a href="<?php echo site_url('product/view/' . $e['id']); ?>"><img src="https://img.icons8.com/color/20/000000/visible.png"></a>
                                <a href="<?php echo site_url('product/edit/' . $e['id']); ?>"><img src="https://img.icons8.com/cotton/20/000000/edit.png"></a>
                                 <a href="<?php echo site_url('adm-franchisee/publish/'. $e['id']);?>" class="btn btn-sm btn-primary">Publish</a>
                               <!--  <a onclick="return confirm('Are you sure you want to delete this Product ?')"
                                   href="<?php //echo site_url('product/remove/' . $e['id']); ?>"
                                   ><img src="https://img.icons8.com/color/20/000000/clear-symbol.png"></a> -->
                            </td>
                        </tr>
                    <?php } ?>
                </table>
                <div class="pull-right">
                    <?php echo $this->pagination->create_links(); ?>
                </div>
            </div>
</div>
</div>
</div> 
