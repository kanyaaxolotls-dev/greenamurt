   <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>
   <script>
    $(function () {
        $("#cname").change(function () {
            if ($(this).val() == "member") {
                $("#displayuserid").show();
            } else {
                $("#displayuserid").hide();
            }
             if ($(this).val() == "customer") {
                $("#displaycustomer").show();
            } else {
                $("#displaycustomer").hide();
            }
        });
    });
</script>
<!-- <script>
$(document).ready(function(){
$('#save_btn').on('click', function(event) {
   
    var product = $('#product option:selected').val();
     var quantity = $.trim($('#quantity').val());
      var price = $.trim($('#price').val());
       var payable_amount = $.trim($('#payable_amount').val());
    var numRows = $('#tableBody').find('tr').length;
    if(product.length > 0) {
        var newRow = $('<tr><td></td><td></td><td></td><td></td><td></td></tr>');
        var cols = newRow.children();
        cols.eq(0).text(numRows+1);
        cols.eq(1).text(product);
        cols.eq(2).text(quantity);
        cols.eq(3).text(price);
        cols.eq(4).text(payable_amount);
        newRow.appendTo('#rows');
    }
    return false;
});
});
</script>
 -->

   <div class="col">
   <div class="card bg-secondary shadow">   
        <div class="card-header bg-white border-0"> 
                <div class="row align-items-center">
                    <div class="col-8">
                          <h3 class="mb-0"><?php echo $title ?></h3>
                    </div> 
                <div class="col-4 text-right"> 
                    <a href="<?php echo site_url('member/index')?>" class="btn btn-sm btn-primary">Home</a>
                </div>
            </div>
        </div> 
    <div class="card-body"> 
        <?php echo form_open('Franchisee/add_quotation') ?>

            <?php
                $tid = $this->db->select('id')->order_by('id','desc')->limit(1)->get('temp')->row('id');
                $id = $tid +1;
            ?>
        <input type="hidden" class="form-control" name="id" id="id" value="<?php echo $id;?>"> 

                <div class="row">
                    <div class="col-sm-6">
                        <div class="form-group">
                            <label>Customer Type</label>
                            <select name="cname" id="cname" class="form-control form-control-alternative">
                            <option value="" selected>Choose Customer Type</option>
                              <option value="customer">Customer</option>
                              <option value="member">Member</option>
                            </select>
                        </div>
                    </div>
                     <div class="col-sm-6" id="displayuserid" style="display: none">
                        <div class="form-group">
                            <label>Userid</label>
                            <input type="text" class="form-control form-control-alternative" name="userid" id="userid" >
                        </div>
                    </div>
                </div>
                <div class="row" id="displaycustomer" style="display: none">
                    <div class="col-sm-4">
                        <div class="form-group">
                            <label>Name</label>
                            <input type="text" class="form-control form-control-alternative" name="name" id="name">
                        </div>
                    </div>
                    <div class="col-sm-4">
                        <div class="form-group">
                            <label>Email</label>
                            <input type="Email" class="form-control form-control-alternative" name="email" id="email">
                        </div>
                    </div>
                    <div class="col-sm-4">
                        <div class="form-group">
                            <label>Phone Number</label>
                            <input type="text" class="form-control form-control-alternative" name="phone" id="phone">
                        </div>
                    </div>
                    <div class="col-sm-4">
                        <div class="form-group">
                            <label>Address 1</label>
                            <input type="textarea" class="form-control form-control-alternative" name="address1" id="address1">
                        </div>
                    </div>
                     <div class="col-sm-4">
                        <div class="form-group">
                            <label>Address 2</label>
                            <input type="textarea" class="form-control form-control-alternative" name="address2" id="address2">
                        </div>
                    </div>
                </div>
                
                
                <button type="button" class="btn btn-danger btn-block" data-toggle="collapse" data-target="#addproduct">Add Product</button><br>
                <div class="form-group collapse" id="addproduct">
                    <input type="hidden" name="gst" id="gst" value="y" onclick=""> 
                <div class="row">
                      <div class="col-sm-2">
                        <div class="form-group">
                            <label>Select Product</label>
                            <select name="product" class="form-control" id="product" onChange="getServiceAmount()" autofocus>
                                <option value="">Select Service</option>
                                <?php
                                    $product_record = $this->db->get_where('product', array('publish_by' => $this->session->name))->result_array();
                                   // var_dump($product_record);die();
                                    foreach($product_record as $value)
                                    {
                                ?>  
                                <option <?php if($value['id'] == $record[0]['id']){ ?> selected="selected" <?php }?> value="<?php echo $value['id'];?>"><?php echo $value['prod_name'];?></option>
                                <?php
                                    }
                                ?>
                            </select>
                        </div>
                    </div>

                        <?php
                             $amount = $this->db->get_where("product",array('id' => $record[0]['id']))->result_array();
                           // $amount = $this->db->select("prod_price","product",array('id' => $record[0]['id']))->result_array();
                        ?>
                    <div class="col-sm-2">
                          <label>Product Price</label>
                        <!-- <div class="form-group" id="price" name="price"> -->
                            <input type="text" class="form-control" name="price" id="price" placeholder="Price" value="<?php echo $amount[0]['prod_price'];?>" onBlur="getTotalAmountGST()"> 

                        </div>
                          <div class="col-sm-2">
                        <div class="form-group">
                            <label>Quantity</label>
                          <!--   <input type="text" class="form-control form-control-alternative" name="qty" id="qty" onKeyUp="multiply()"> -->
                          <input type="text" class="form-control" name="quantity" id="quantity" placeholder="Quantity" value="1" onBlur="getTotalAmountGST()"> 
                        </div>
                    </div>
                    
                      
                    <div class="col-md-2">
                            <label>GST</label> 
                            <input type="text" class="form-control" name="gst_per" id="gst_per" placeholder="GST" value="<?php echo $amount[0]['gst'];?>" onBlur="getServiceGST()"> 
                        </div>
                         <div class="col-sm-2">
                            <div class="form-group">
                                <label>Total Price</label>
                                  <input type="text" class="form-control" name="payable_amount" id="payable_amount" placeholder="Total" value="<?php echo $amount[0]['amount'];?>" readonly> 
                        </div>
                    </div>
                    <div class="col-sm-2">
                        <div class="form-group">
                            <br>
                            <button type="button" class="btn btn-primary" id="save_btn" >Add</button>
                           <!--  <input type="button" name="order" class="btn btn-success" id="btn" value="order"> -->
                        </div>
                    </div>
             
                    
                    </div>
                      </div>
                 

               
            </div>


            <div class="container">
                <div class="table-responsive">
                    <table id="example1" class="table controls controls-row table-bordered">
                        <thead>
                            <tr>
                                <th>Sr.No</th>
                              <th>Product Name</th>
                              <th>Qunatity</th>
                              <th>Product Price</th>
                               <th>GST</th>
                              <th>Total Price</th>
                             <th>Action</th>
                            </tr>
                        </thead>
                        <tbody id="rows">
                            <tr>
                            <td></td>                                           
                            <td></td>                                            
                            <td></td>                                            
                            <td></td>                                            
                            <td></td>
                            <td></td>
                            <td></td>                                                                                    
                        </tr>       
                        </tbody>
                    </table>
                </div>
            </div>

                 
      
              
              
                 <div class="row">
                    <div class="col-2">
                      
                          <input type="submit" class="btn btn-primary" name="submit" value="save">
                    </div>
                </div>

                <?php echo form_close() ?>
            </div>
        </div></div></div>
</div>
</div>
<script>
        $(document).ready(function(){           
            $("#save_btn").click(function() {
            var product         = document.getElementById("product").value;             
            var price           = document.getElementById("price").value;
            var quantity        = document.getElementById("quantity").value;
            var gst_per         = document.getElementById("gst_per").value;
            var payable_amount  = document.getElementById("payable_amount").value;
            var id  = document.getElementById("id").value;              
         
            
                $.ajax({                
                    type: 'POST',
                    url: '<?php echo base_url('franchisee/add_quotation_temp');?>',                        
                    data: "product=" + product + "&price=" + price + "&quantity=" + quantity + "&gst_per=" + gst_per + "&payable_amount=" + payable_amount + "&gst=" +gst+ "&id=" + id,
                    success: function (res)
                    {       
                        $('#rows').html(res);
                        document.getElementById('product').value='';
                        document.getElementById('price').value='';
                        document.getElementById('quantity').value='';
                        document.getElementById('gst_per').value='';
                        document.getElementById('payable_amount').value='';     
                        //document.getElementById('amc_amount').value=''; 
                                        
                        document.getElementById("product").focus();                                                                                                         
                    }                   
                }); 
        
            });
        });
    </script>   
        
    <script>
        function getServiceAmount()
        {       
            var product = document.getElementById("product").value;     
            var gst = document.getElementById("gst").checked;
            $.ajax({                
                type: 'POST',
                url: '<?php echo base_url('franchisee/getServiceAmount');?>',                      
                data: "product=" + product + "&gst=" + gst, 
                success: function (res)
                {       
                    $('#price').val(res);                                                                                                           
                    $('#quantity').val('1');                                                                                                            
                    $('#payable_amount').val(res);                                                                                                          
                }                   
            }); 

            $.ajax({                
                type: 'POST',
                url: '<?php echo base_url('franchisee/getServiceGST');?>',                     
                data: "product=" + product + "&gst=" + gst, 
                success: function (res)
                {       
                    $('#gst_per').val(res); 
                    getTotalAmountGST();    
                }                   
            });

            getAMCAmount(); 
        }
    </script>
    
    <script>
        function getServiceAmount1()
        {       
            var product = document.getElementById("product1").value;

            $.ajax({                
                type: 'POST',
                url: '<?php echo base_url('franchisee/getServiceAmount1');?>',                     
                data: "product=" + product, 
                success: function (res)
                {   
                    $('#amount').val(res);                                                                                                                                                                                                                      
                }                   
            });     
        }
    </script>
    
    <script>
        function getTotalAmount()
        {       
            var price = document.getElementById("price").value;     
            var quantity = document.getElementById("quantity").value;
            var payable_amount1 = Number(price) * Number(quantity);
            $('#payable_amount').val(payable_amount1);                  
        }
    </script>

    <script>
        function getTotalAmountGST()
        {       
            var price = document.getElementById("price").value;     
            var quantity = document.getElementById("quantity").value;
            var gst_per = document.getElementById("gst_per").value;
            var gst_amount = (Number(price) * Number(quantity) * Number(gst_per)) / 100;
            var payable_amount1 = (Number(price) * Number(quantity)) + gst_amount;
            $('#payable_amount').val(payable_amount1);      
            getAMCAmount(); 
        }
    </script>
    <script type="text/javascript">
        function delete_quotation_temp(id)
        {    
            if(confirm('Sure To Delete This Entry ?'))
            {
                var id = id;                    
                 $.ajax({               
                    url: '<?php echo base_url('franchisee/delete_quotation_temp');?>', 
                    data:"id="+id,
                    type:'POST',                
                    success:function(data){                     
                        $('#rows').html(data);
                                    
                        }   
                    });          
            }   
        }
    </script>
  <!-- <script>
        function getAMCAmount()
        {       
            var amc = document.getElementById("amc").checked;
            var product = document.getElementById("product").value;         
            var quantity = document.getElementById("quantity").value;           
            if(amc == true)
            {
                $("#Display").show();           
                
                    $.ajax({                
                        url: '<?php// echo base_url('index.php/admin/getAMCAmount');?>',  
                        data:"service="+service+"&quantity="+quantity,
                        type:'POST',                
                        success:function(data){     
                            $('#amc_amount').val(data);                                 
                            }   
                        }); 
                
            }       
            if(amc == false){
                $("#Display").hide();
                $('#amc_amount').val('0');
            }   
        }

    </script> -->
  
  


<script>
  $.validate({
    lang: 'en'
  });
</script>