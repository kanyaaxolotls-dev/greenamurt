
    <div class="card bg-secondary shadow">  
        <div class="card-header bg-white border-0"> 
        <div class="row align-items-center">
            <div class="col-8">
                  <h3 class="mb-0"><?php echo $title;?></h3>
            </div>
        <div class="col-4 text-right"> 
            <a href="<?php echo site_url('cart/new-purchase')?>" class="btn btn-sm btn-primary">Back to Shop</a>
        </div>
    </div>
</div> 
<div class="card-body">
<?php 
$data = $this->db_model->select_multi('*', 'product', array('id' =>$this->uri->segment(3)));
 
?>
   <div class="row">
      <div class="col-sm-6">

           <img src="<?php echo $data->image ? base_url('uploads/' . $data->image) : base_url('uploads/default.jpg'); ?>"
             class="img-responsive img-thumbnail" style="">
      </div>

      <div class="col-sm-6">
          <h2 class="text-white" style="font-"><?php echo $data->prod_name ?> </h2>
            <h3 class="main-price text-white">Product Price: <?php echo config_item('currency') . $data->prod_price ?></h3>
            <h3 class="main-price text-white">Franchisee Price : <?php echo config_item('currency') . $data->franchisee_price ?></h3>
             <div class="panel panel-border panel-default">
                            <div class="panel-heading">
                                <a role="button">
                                    <h4 class="panel-title text-white">
                                    Product Description
                                   <?php echo $data->prod_desc ; ?>
                                    </h4>
                                </a>
                            </div>
                        
                        <div class="row pick-size">
                            <div class="col-md-6 col-sm-6">
                                PV : <?php echo $data->pv ?>
                            </div>
                            <div class="col-md-6 col-sm-6">
                                Available Stock:  <?php echo $data->qty ?>
                            </div>
                        </div>
                        <br>
 
                        
              </div>
              <div class="panel panel-footer">
                <div class="row"> 

                        <div class="col-md-3 col-sm-3">
                            <a href="<?php echo site_url('cart/buy_2/' . $data->id) ?>" class="btn btn-danger btn-xs">Add to Cart</a>
                        </div>
                </div>
              </div>
          </div>

</div>
</div><!-- end of panel-->
</div><!-- end of main panel-->