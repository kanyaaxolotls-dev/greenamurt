                      <!-- start page title -->
                        <div class="row">
                            <div class="col-12">
                                <div class="page-title-box d-sm-flex align-items-center justify-content-between">
                                    <h4 class="mb-sm-0 font-size-18">Products</h4>

                                    <div class="page-title-right">
                                        <ol class="breadcrumb m-0">
                                            <li class="breadcrumb-item"><a href="javascript: void(0);">Ecommerce</a></li>
                                            <li class="breadcrumb-item active">Products</li>
                                        </ol>
                                    </div>

                                </div>
                            </div>
                        </div>
                        <!-- end page title -->

                      <div class="row">
                            <div class="col-lg-12">
                                <div class="row mb-3">
                                    <div class="col-xl-4 col-sm-6 col-xs-6">
                                        <div class="mt-2">
                                            <h5><?php echo $this->db_model->select("cat_name", 'product_categories', array('id' => $this->uri->segment(3))); 
                                        ?></h5>
                                        </div>
                                    </div>
                                    <div class="col-lg-8 col-sm-6">
                                        <form action="<?php echo site_url('cart/search') ?>" method="POST" class="mt-4 mt-sm-0 float-sm-end d-sm-flex align-items-center">
                                            <div class="search-box me-2">
                                                <div class="position-relative">
                                                    <input type="text" class="form-control border-0" name="search" placeholder="Search...">
                                                    <i class="bx bx-search-alt search-icon"></i>
                                                </div>
                                            </div>
                                             <ul class="nav nav-pills product-view-nav justify-content-end mt-3 mt-sm-0">
                                                  
                                                <li class="nav-item">
                                                    <a class="nav-link active" class="grid"><i class="bx bx-grid-alt"></i></a>
                                                </li>
                                                <li class="nav-item">
                                                    <a class="nav-link" class="list"><i class="bx bx-list-ul"></i></a>
                                                </li>
                                            </ul>
                                        </form>
                                    </div>
                                </div>
                                <div class="row">
                                  <?php 
                                    if (isset($product_top) && count ($product_top)) { 
                                    foreach ($product_top as $e) {  ?>
                                        <div class="col-sm-12 col-md-6 col-lg-3 ">
                                          <div class="card">
                                            <img class="card-img-top" src="<?php echo base_url('uploads/' . ($e->image ? $e->image : 'default.jpg')) ?>" alt="Card image cap" height="320px">
                                                <div class="card-body">
                                                  <h5 class="card-title"><?php echo $e->prod_name; ?> [Stock: <?php echo $e->qty; ?>]</h5>
                                                   <h6 class="my-0"><span class="text-muted me-2"><del><?php echo config_item('currency') ?><?php echo number_format($e->prod_price); ?> </del></span> <b>
                                                       <?php echo config_item('currency') ?>
                                                       <?php 
                                                          $gstAmount = ($e->dealer_price * $e->gst) / 100;
                                                          $totalPrice = $e->dealer_price + $gstAmount;
                                                          echo number_format($totalPrice);
                                                        ?>
                                                    </b></h6>
                                                </div>
                                                <div class="card-footer">
                                                    <p class="text-success h5">
                                                        BV : <?= $e->pv  ?>
                                                    </p>
                                                <div class="d-flex justify-content-between align-items-end mt-1">
                                                 <p class="text-muted mb-0">
                                                        <i class="bx bxs-star text-warning"></i>
                                                        <i class="bx bxs-star text-warning"></i>
                                                        <i class="bx bxs-star text-warning"></i>
                                                        <i class="bx bxs-star text-warning"></i>
                                                        <i class="bx bxs-star text-warning"></i>
                                                    </p>
                                                </div>
                                                 <div class="text-left">
                                                     <a href="<?php echo site_url('cart/buy_2/' . $e->id) ?>" type="button" class="btn btn-primary btn-sm waves-effect waves-light mt-2"><i class="bx bx-cart me-2"></i> Add to Cart</a>
                                                     <a href="<?php echo site_url('cart/view_product/' . $e->id) ?>" type="button" class="btn btn-success btn-sm waves-effect waves-light mt-2 ml-4"><i class="bx bx-eye me-2"></i> View Product</a>
                                                </div>  
                                            </div>
                                          </div>
                                        </div>
                                     <?php } }else{ echo "<h2>! No products Found</h2>";} ?>
                                </div>
                            </div>
                        </div>
                        <!-- end row -->
                         <script>
                          $(document).ready(function(){
                            $("#myInput").on("keyup", function() {
                              var value = $(this).val().toLowerCase();
                              $("#myTable tr").filter(function() {
                                $(this).toggle($(this).text().toLowerCase().indexOf(value) > -1)
                              });
                            });
                          });
                          </script> 
