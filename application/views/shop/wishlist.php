<?php
    include ('head.php');
?>

            <!-- Body Content -->
            <div id="page-content">
                <!--Page Title-->
                <div class="page section-header text-center">
                    <div class="page-title">
                        <div class="wrapper"><h1 class="page-width">My WishList</h1></div>
                    </div>
                </div>
                <!--End Page Title-->

                <div class="container">
                    <div class="row">
                        <div class="col-12 col-sm-12 col-md-12 col-lg-12 main-col">
                            <form action="#">
                                <div class="wishlist-table table-content table-responsive">
                                    <table class="table table-bordered">
                                        <thead>
                                            <tr>
                                                <th class="product-name text-center alt-font">Remove</th>
                                                <th class="product-price text-center alt-font">Images</th>
                                                <th class="product-name alt-font">Product</th>
                                                <th class="product-price text-center alt-font">Unit Price</th>
                                                <th class="stock-status text-center alt-font">Stock Status</th>
                                                <th class="product-subtotal text-center alt-font">Add to Cart</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                        <?php
                                            foreach ($data as $product_id) {
                                                $price = $this->db_model->select('dealer_price', 'product', array('id' => $product_id));
                                                $img   = $this->db_model->select('image', 'product', array('id' => $product_id));
                                                $name  = $this->db_model->select('prod_name', 'product', array('id' => $product_id));
                                                $qty   = $this->db_model->select('qty', 'product', array('id' => $product_id));
                                                if($qty >= 0){
                                                    $txt  = 'In stock';
                                                    $stat = 'in';
                                                }
                                                else{
                                                    $txt  = 'Out Of stock';
                                                    $stat = 'out';
                                                }
                                        ?>
                                            <tr>
                                                <td class=" text-center">
                                                    <a href="<?php echo base_url('home/remove_from_wishlist/'.$product_id);?>"><i class="icon an an-times" data-bs-toggle="tooltip" data-bs-placement="top" title="Remove"></i></a></td>
                                                <td class="product-thumbnail text-center">
                                                    <a href="<?php echo base_url('home/view_product/'.$product_id) ?>" >
                                                        <img src="<?php echo base_url('uploads/' . ($img ? $img : 'default.jpg')) ?>" src="<?php echo base_url('uploads/' . ($img ? $img : 'default.jpg')) ?>" alt="" title="" />
                                                    </a>
                                                </td>
                                                <td class="product-name"><h4 class="no-margin"><a href="<?php echo base_url('home/view_product/'.$product_id) ?>" ><?php echo $name; ?></a></h4></td>
                                                <td class="product-price text-center"><span class="amount"><?php echo config_item('currency') .$price; ?></span></td>
                                                <td class="stock text-center">
                                                    <span class="<?php echo $stat ?>-stock"><?php echo $txt ?></span>
                                                </td>
                                                <td class="product-subtotal text-center">
                                                    <?php if($stat == 'in'){ ?>
                                                    <a href='<?php echo base_url('home/add_to_cart/'.$product_id) ?>'  class='btn btn-small'>Add To Cart</a>
                                                    <?php }else{ ?>
                                                    <button type='button' class='btn btn-small soldOutBtn'>Out Of stock</button>
                                                    <?php } ?>
                                                </td>
                                            </tr>
                                            <?php } ?>
                                            <!--<tr>-->
                                            <!--    <td class="product-remove text-center"><a href="#"><i class="icon an an-times" data-bs-toggle="tooltip" data-bs-placement="top" title="Remove"></i></a></td>-->
                                            <!--    <td class="product-thumbnail text-center">-->
                                            <!--        <a href="<?php echo base_url('home/view_product/'.$product_id) ?>" ><img src="<?php echo base_url('assets4/images/product-images/product-image3-1.jpg') ?>" alt="" title="" /></a>-->
                                            <!--    </td>-->
                                            <!--    <td class="product-name"><h4 class="no-margin"><a href="<?php echo base_url('home/view_product/'.$product_id) ?>" >Sueded Cotton Pant in Khaki</a></h4></td>-->
                                            <!--    <td class="product-price text-center"><span class="amount">$150.00</span></td>-->
                                            <!--    <td class="stock text-center">-->
                                            <!--        <span class="out-stock">Out Of stock</span>-->
                                            <!--    </td>-->
                                            <!--    <td class="product-subtotal text-center"><button type="button" class="btn btn-small soldOutBtn">Out Of stock</button></td>-->
                                            <!--</tr>-->
                                        </tbody>
                                    </table>
                                </div>
                            </form>                   
                        </div>
                    </div>
                </div>

            </div>
            <!-- End Body Content -->

<?php
    include ('footer.php');
?>