<?php
    include ('head.php');
?>

            <!-- Body Content -->
            <div id="page-content">
                <!-- Page Title -->
                <div class="page section-header text-center mb-0">
                    <div class="page-title">
                        <div class="wrapper"><h1 class="page-width">My Account</h1></div>
                    </div>
                </div>
                <!-- End Page Title -->
                <div class="bredcrumbWrap bredcrumbWrapPage bredcrumb-style2 text-center">
                    <div class="container breadcrumbs">
                        <a href="index-2.html" title="Back to the home page">Home</a><span aria-hidden="true">|</span><span class="title-bold">My Account</span>
                    </div>
                </div>

                <div class="container">
                    <div class="row mb-4 mb-lg-5 pb-lg-5">
                        <div class="col-xl-2 col-lg-2 col-md-12 md-margin-20px-bottom">
                            <!-- Nav tabs -->
                            <ul class="nav flex-column dashboard-list mb-4 mb-lg-0" role="tablist">
                                <li><a class="nav-link active" data-bs-toggle="tab" href="#dashboard">Dashboard</a></li>
                                <li><a class="nav-link" data-bs-toggle="tab" href="#orders">Orders</a></li>
                                <li><a class="nav-link" data-bs-toggle="tab" href="#account-details">Account details</a></li>
                                <li><a class="nav-link" data-bs-toggle="tab" href="#wishlist">Wishlist</a></li>
                                <li><a class="nav-link"  href="<?php echo base_url('home/cart') ?>">My Cart</a></li>
                                <li><a class="nav-link" href="<?php echo base_url('home/logout') ?>">logout</a></li>
                            </ul>
                            <!-- End Nav tabs -->
                        </div>

                        <div class="col-xs-10 col-lg-10 col-md-12">
                            <!-- Tab panes -->
                            <div class="tab-content dashboard-content padding-30px-all md-padding-15px-all" style="">
                                <!-- Dashboard -->
                                <div id="dashboard" class="tab-pane fade active show">
                                    <h3>Dashboard </h3>
                                    <h5>Hi, <span class="text-danger">Akshay Narwade</span></h5>
                                    <div class="row user-profile mt-4">
                                        <div>
                                            <ul class="profile-order mt-3 mt-lg-0">
                                                <li>
                                                    <h3 class="mb-1 text-primary">
                                                        <?php 
                                                            $all_ordr = $this->db_model->count_all('product_sale', array('userid' => $web_log_data['phone'])); 
                                                            echo str_pad($all_ordr, 2, '0', STR_PAD_LEFT);; 
                                                        ?>
                                                    </h3>
                                                    All Orders
                                                </li>
                                                <li>
                                                    <h3 class="mb-1 text-warning">
                                                        <?php 
                                                            $pnd_ordr = $this->db_model->count_all('product_sale', array('userid' => $web_log_data['phone'],'status' =>'Processing')); 
                                                            echo str_pad($all_ordr, 2, '0', STR_PAD_LEFT);; 
                                                        ?>
                                                    </h3>
                                                    Pending Orders
                                                </li>
                                                <li>
                                                    <h3 class="mb-1 text-success">
                                                        <?php 
                                                            $all_ordr = $this->db_model->count_all('product_sale', array('userid' => $web_log_data['phone'],'status' =>'Completed')); 
                                                            echo str_pad($all_ordr, 2, '0', STR_PAD_LEFT); 
                                                        ?>
                                                    </h3>
                                                    Completed Orders
                                                </li>
                                                <li>
                                                    <h3 class="mb-1">
                                                        <?php 
                                                            $purchase = $this->db_model->sum('cost', 'product_sale', array('userid' => $web_log_data['phone'])); 
                                                            echo config_item('currency').str_pad($purchase, 2, '0', STR_PAD_LEFT);; 
                                                        ?>
                                                    </h3>
                                                    Total Purchase
                                                </li>
                                            </ul>
                                        </div>
                                    </div>
                                    <div class="table-responsive mt-4">
                                        <table class="table">
                                            <thead class="alt-font">
                                                <tr>
                                                    <th>Order</th>
                                                    <th>Product</th>
                                                    <th>Date</th>
                                                    <th>Status</th>
                                                    <th>Total</th>
                                                    <th>Actions</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                <?php 
                                                    foreach($orders as $tr){ 
                                                        $name  =  $this->db_model->select('prod_name', 'product', array('id' => $tr['product_id']));
                                                        if($tr['status'] == 'Processing'){
                                                            $clr = 'warning';
                                                        }
                                                        else{
                                                           $clr = 'success'; 
                                                        }
                                                ?>
                                                <tr>
                                                    <td><?php echo '#'.$tr['id'] ?></td>
                                                    <td><?php echo $name ?></td>
                                                    <td><?php echo $tr['date'] ?></td>
                                                    <td class="text-<?php echo $clr ?>"><?php echo $tr['status'] ?></td>
                                                    <td><?php echo config_item('currency').$tr['cost'] ?> </td>
                                                    <td><a class="view" href="<?php echo base_url('home/view_order/'.$tr['id']); ?>">View</a></td>
                                                </tr>
                                                <?php } ?>
                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                                <!-- End Dashboard -->

                                <!-- Orders -->
                                <div id="orders" class="product-order tab-pane fade">
                                    <h3>Orders</h3>
                                    <div class="table-responsive">
                                        <table class="table">
                                            <thead class="alt-font">
                                                <tr>
                                                    <th>Order</th>
                                                    <th>Product</th>
                                                    <th>Date</th>
                                                    <th>Status</th>
                                                    <th>Total</th>
                                                    <th>Actions</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                <?php 
                                                    foreach($orders as $tr){ 
                                                        $name  =  $this->db_model->select('prod_name', 'product', array('id' => $tr['product_id']));
                                                        if($tr['status'] == 'Processing'){
                                                            $clr = 'warning';
                                                        }
                                                        else{
                                                           $clr = 'success'; 
                                                        }
                                                ?>
                                                <tr>
                                                    <td><?php echo '#'.$tr['id'] ?></td>
                                                    <td><?php echo $name ?></td>
                                                    <td><?php echo $tr['date'] ?></td>
                                                    <td class="text-<?php echo $clr ?>"><?php echo $tr['status'] ?></td>
                                                    <td><?php echo config_item('currency').$tr['cost'] ?> </td>
                                                    <td><a class="view" href="<?php echo base_url('home/view_order/'.$tr['id']); ?>">View</a></td>
                                                </tr>
                                                <?php } ?>
                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                                <!-- End Orders -->

                                <!-- Account Details -->
                                <div id="account-details" class="tab-pane fade">
                                    <h3>Account details </h3>
                                    <div class="account-login-form bg-light-gray padding-20px-all">
                                        <form>
                                            <fieldset>
                                                <div class="row">
                                                    <div class="form-group col-md-6 col-lg-6 col-xl-6 required">
                                                        <label for="input-firstname">Full Name <span class="required-f">*</span></label>
                                                        <input name="name" required value="<?php echo $userss->name ?>" id="input-firstname" type="text">
                                                    </div>
                                                    <div class="form-group col-md-6 col-lg-6 col-xl-6 required">
                                                        <label for="input-email">E-Mail <span class="required-f">*</span></label>
                                                        <input name="email"  value="<?php echo $userss->email ?>" id="input-email" type="email">
                                                    </div>
                                                    <div class="form-group col-md-6 col-lg-6 col-xl-6 required">
                                                        <label for="input-telephone">Mobile <span class="required-f">*</span></label>
                                                        <input name="phone" readonly value="<?php echo $userss->phone ?>" id="input-telephone" type="tel">
                                                    </div>
                                                    <div class="form-group col-md-6 col-lg-6 col-xl-6">
                                                        <label for="input-company">Country</label>
                                                        <input name="" value="India" id="input-company" type="text" readonly>
                                                    </div>
                                                    <div class="form-group col-md-6 col-lg-6 col-xl-6">
                                                        <label for="input-company">State</label>
                                                        <input name="state" required value="<?php echo $userss->state ?>" id="input-company" type="text">
                                                    </div>
                                                    <div class="form-group col-md-6 col-lg-6 col-xl-6 required">
                                                        <label for="input-address-1">City <span class="required-f">*</span></label>
                                                        <input name="city" required value="<?php echo $userss->city ?>" id="input-address-1" type="text">
                                                    </div>
                                                    <div class="form-group col-md-6 col-lg-6 col-xl-6">
                                                        <label for="input-address-2">Local Address <span class="required-f">*</span></label>
                                                        <input name="address" value="<?php echo $userss->address ?>" id="input-address-2" type="text">
                                                    </div>
                                                    <div class="form-group col-md-6 col-lg-6 col-xl-6 required">
                                                        <label for="input-postcode">Post Code <span class="required-f">*</span></label>
                                                        <input name="pincode" required value="<?php echo $userss->pincode ?>" id="input-postcode" type="text">
                                                    </div>
                                                </div>
                                            </fieldset>
                                            <button type="submit" class="btn margin-15px-top btn-primary">Update</button>
                                        </form>
                                    </div>
                                </div>
                                <!-- End Account Details -->

                                <!-- Downloads -->
                                <div id="wishlist" class="product-wishlist tab-pane fade">
                                    <h3>My Wishlist</h3>
                                    <!-- Grid Product -->
                                    <div class="grid-products grid--view-items wishlist-grid mt-4">
                                        <div class="row">
                                            <?php
                                                foreach ($wishlist as $product_id) {
                                                    $price = $this->db_model->select('dealer_price', 'product', array('id' => $product_id));
                                                    $mrp   = $this->db_model->select('prod_price', 'product', array('id' => $product_id));
                                                    $img   = $this->db_model->select('image', 'product', array('id' => $product_id));
                                                    $img1  = $this->db_model->select('image1', 'product', array('id' => $product_id));
                                                    $name  = $this->db_model->select('prod_name', 'product', array('id' => $product_id));
                                                    $qty   = $this->db_model->select('qty', 'product', array('id' => $product_id));
                                                    if($qty >= 15){
                                                        $txt  = 'In stock';
                                                        $stat = '1';
                                                        $msg  = 'In Stock';
                                                    }
                                                    elseif($qty >= 0 and $qty < 15){
                                                        $txt  = 'In stock';
                                                        $stat = '2';
                                                        $msg  = 'Low in stock'; 
                                                    }
                                                    else{
                                                        $txt  = 'Out Of stock';
                                                        $stat = '3';
                                                        $msg  = 'Out Of stock';
                                                    }
                                            ?>
                                            <div class="col-6 col-sm-4 col-md-3 col-lg-3 item">
                                                <div class="product-image">
                                                    <a href="<?php echo base_url('home/view_product/'.$product_id) ?>">
                                                        <img class="primary blur-up lazyload" data-src="<?php echo base_url('uploads/' . ($img ? $img : 'default.jpg')) ?>" src="<?php echo base_url('uploads/' . ($img ? $img : 'default.jpg')) ?>" alt="image" title="product"   height="260px"/>
                                                        <img class="hover blur-up lazyload" data-src="<?php echo base_url('uploads/' . ($img1 ? $img1 : 'default.jpg')) ?>" src="<?php echo base_url('uploads/' . ($img1 ? $img1 : 'default.jpg')) ?>" alt="image" title="product" height="260px"/>
                                                        <div class="product-labels rectangular">
                                                            <span class="lbl pr-label<?php echo $stat ?>">
                                                                <?php echo $msg ?>
                                                            </span>
                                                        </div>
                                                    </a>
                                                </div>
                                                <div class="product-details text-center">
                                                    <div class="product-name">
                                                        <a href="<?php echo base_url('home/view_product/'.$product_id) ?>"><?php echo $name; ?></a>
                                                    </div>
                                                    <div class="product-price">
                                                        <span class="old-price"><?php echo config_item('currency') .$mrp; ?></span>
                                                        <span class="price"><?php echo config_item('currency') .$price; ?></span>
                                                    </div>
                                                    <form method="post" action="" class="cart-form mt-3" enctype="multipart/form-data">
                                                        <?php if($stat != '3'){ ?>
                                                        <a href='<?php echo base_url('home/add_to_cart/'.$product_id) ?>'  class='btn btn-small'>Add To Cart</a>
                                                        <?php }else{ ?>
                                                        <button type='button' class='btn btn-small soldOutBtn'>Out Of stock</button>
                                                        <?php } ?>
                                                    </form>
                                                </div>
                                            </div>
                                            <?php } ?>
                                        </div>
                                    </div>
                                    <!-- End Grid Product-->
                                </div>
                                <!-- End Downloads -->
                            </div>
                            <!-- End Tab panes -->
                        </div>
                    </div>
                </div>
                <!-- End Body Container -->
            </div>
            <!-- End Body Content -->
<?php
    include ('footer.php');
?>

