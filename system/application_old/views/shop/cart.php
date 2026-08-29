<?php
    include ('head.php');
?>
        <!-- Body Content -->
            <div id="page-content">
                <!-- Page Title -->
                <div class="page section-header text-center">
                    <div class="page-title">
                        <div class="wrapper"><h1 class="page-title">My Cart</h1></div>
                    </div>
                </div>
                <!-- End Page Title -->
                <div class="container">
                    <div class="row">
                        <!-- Main Content -->
                        <div class="col-12 col-sm-12 col-md-12 col-lg-12 main-col">
                            <?php echo validation_errors('<div class="alert alert-danger">', '</div>') ?>
                            <?php echo $this->session->flashdata('site_flash') ?>
                            <div class="alert alert-success text-uppercase" role="alert">
                                <i class="icon an an-truck icon-large"></i> &nbsp;<strong>Congratulations!</strong> You've got free shipping!
                            </div>
                            <form action="#" method="post" class="cart style2">
                                <table>
                                    <thead class="cart__row cart__header">
                                        <tr>
                                            <th colspan="2" class="text-center">Product</th>
                                            <th class="text-center">Price</th>
                                            <th class="text-center">Quantity</th>
                                            <th class="text-center">Total</th>
                                            <th class="action">&nbsp;</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php
                                            $subtotal = 0;
                                            $total    = 0;
                                            $sub_bvs  = 0;
                                            
                                            foreach ($data as $product_id => $data) {
                                                $price      = $this->db_model->select('dealer_price', 'product', array('id' => $product_id));
                                                $mrp        = $this->db_model->select('prod_price', 'product', array('id' => $product_id));
                                                $img        = $this->db_model->select('image', 'product', array('id' => $product_id));
                                                $t_bv       = $this->db_model->select('pv', 'product', array('id' => $product_id));
                                                $name       = $this->db_model->select('prod_name', 'product', array('id' => $product_id));
                                                $tot        = $mrp * $data['qty'];
                                                $subtotal  += $tot;
                                                $tot2       = $price * $data['qty'];
                                                $tot22      = $t_bv  * $data['qty'];
                                                $total     += $tot2;
                                                $sub_bvs   += $tot22; 
                                        
                                        ?>

                                        <tr class="cart__row border-bottom line1 cart-flex border-top">
                                            <td class="cart__image-wrapper cart-flex-item">
                                                <a href="<?php echo base_url('home/view_product/'.$product_id) ?>"><img class="cart__image blur-up lazyload" data-src="<?php echo base_url('uploads/' . ($img ? $img : 'default.jpg')) ?>" src="<?php echo base_url('uploads/' . ($img ? $img : 'default.jpg')) ?>" alt="<?php echo $name; ?>" /></a>
                                            </td>
                                            <td class="cart__meta small--text-left cart-flex-item">
                                                <div class="list-view-item__title">
                                                    <a href="<?php echo base_url('home/view_product/'.$product_id) ?>" ><?php echo $name; ?></a>
                                                </div>
                                                <div class="cart__meta-text">
                                                    Qty : <?php echo $data['qty']; ?><br>MRP : <?php echo $mrp; ?><br>
                                                </div>
                                            </td>
                                            <td class="cart__price-wrapper cart-flex-item text-center">
                                                <span class="money"><?php echo config_item('currency') . number_format($price); ?></span>
                                            </td>
                                            <td class="cart__update-wrapper cart-flex-item text-center">
                                                <div class="cart__qty text-center">
                                                    <div class="qtyField">
                                                        <a class="qtyBtn minus" href="<?php echo base_url('home/remove_qty/'.$product_id);?>"><i class="icon an an-minus"></i></a>
                                                        <input class="cart__qty-input qty" type="text" name="updates[]" value="<?php echo $data['qty']; ?>" pattern="[0-9]*" />
                                                        <a class="qtyBtn plus" href="<?php echo base_url('home/add_qty/'.$product_id);?>"><i class="icon an an-plus"></i></a>
                                                    </div>
                                                </div>
                                            </td>
                                            <td class="small--hide cart-price text-center">
                                                <span class="money"><?php echo config_item('currency') . number_format($tot2); ?></span>
                                            </td>
                                            <td class="text-center small--hide"><a href="<?php echo base_url('home/remove_from_cart/'.$product_id);?>" class="btn bg-danger cart__remove" data-bs-toggle="tooltip" data-bs-placement="top" title="Remove item"><i class="icon an an-times"></i></a></td>
                                        </tr>
                                        <?php } ?>
                                    </tbody>
                                    <tfoot>
                                        <tr>
                                            <td colspan="3" class="text-start"><a href="<?php echo base_url('home') ?>" class="btn bg-success"><i class="icon an an-chevron-circle-left"></i> Continue shopping</a></td>
                                            <td colspan="3" class="text-end">
                                                <a href="<?php echo base_url('home/clear_cart/');?>" name="clear" class="btn  bg-danger"><i class="icon an an-times"></i> Clear Shoping Cart</a>
                                                <!--<button type="submit" name="update" class="btn btn--link btn--small cart-continue ml-2"><i class="icon an an-sync"></i> Update Cart</button>-->
                                            </td>
                                        </tr>
                                    </tfoot>
                                </table> 
                            </form>                   
                        </div>

                        <div class="container mt-4">
                            <div class="row">
                                <div class="col-12 col-sm-12 col-md-12 col-lg-12 cart__footer">
                                    <div class="solid-border">	
                                        <div class="row border-bottom pb-2">
                                            <span class="col-12 col-sm-6 cart__subtotal-title">Subtotal</span>
                                            <span class="col-12 col-sm-6 text-right"><span class="money"><?php echo config_item('currency') . number_format($subtotal) ?></span></span>
                                        </div>
                                        <div class="row border-bottom pb-2 pt-2">
                                            <span class="col-12 col-sm-6 cart__subtotal-title">Total Points(BV)</span>
                                            <span class="col-12 col-sm-6 text-right"><?php echo $sub_bvs;?></span>
                                        </div>
                                        <div class="row border-bottom pb-2 pt-2">
                                            <span class="col-12 col-sm-6 cart__subtotal-title">Shipping</span>
                                            <span class="col-12 col-sm-6 text-right">Free shipping</span>
                                        </div>
                                        <div class="row border-bottom pb-2 pt-2">
                                            <span class="col-12 col-sm-6 cart__subtotal-title"><strong>Grand Total</strong></span>
                                            <span class="col-12 col-sm-6 cart__subtotal-title cart__subtotal text-right"><span class="money"><?php echo config_item('currency') .number_format($total) ?></span></span>
                                        </div>
                                        <div class="cart__shipping">Shipping &amp; taxes calculated at checkout</div>
                                        <div class="customCheckbox cart_tearm">
                                            <input type="checkbox" value="allen-vela" id="1532947863384-0">
                                            <label for="1532947863384-0">I agree with the terms and conditions</label>
                                        </div>
                                        <a href="<?php echo base_url('home/pre_checkout') ?>" id="cartCheckout" class="btn btn--small-wide checkout">Proceed To Checkout</a>
                                        <div class="paymnet-img"><img src="<?php echo base_url('assets2/images/payment-img.jpg') ?>" alt="Payment" /></div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <!-- End Main Content -->
                    </div>
                </div>
            </div>
            <!-- End Body Content -->
<?php
    include ('footer.php');
?>