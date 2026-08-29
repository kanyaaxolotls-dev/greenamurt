
<?php echo form_open('adm_franchisee/update_stock') ?>
 <div class="col">
    <div class="card bg-secondary shadow">  
        <div class="card-header bg-white border-0"> 
                <div class="row align-items-center">
                    <div class="col-8">
                          <h3 class="mb-0"><?php echo $title ; ?></h3>
                    </div>
                <div class="col-4 text-right">
                    <a href="<?php echo site_url('admin/index')?>" class="btn btn-sm btn-primary">Home</a>
                </div>
            </div>
        </div> 
<div class="card-body">
<div class="row">
    <div class="col-sm-4">
        
        <select class="form-control" name="fname" id="fname">
            <option selected value="" required>Choose Pick-up Centre</option>
            <?php foreach ($fran as $data): ?>
                <option value="<?php echo $data->id ?>"><?php echo $data->name ?> (Business
                    Name: <?php echo $data->business_name ?>
                    /
                    Username: <?php echo $data->username ?>
                    )
                </option>
            <?php endforeach; ?>
        </select>
    </div>
    <div class="col-sm-4">
        <?php 
            $this->db->select('id, prod_name, prod_price,dealer_price')->from('product');
            $product     = $this->db->get()->result();
        ?>
          <select class="form-control" name="pname" id="pname">
            <option selected value="" required>Choose One</option>
            <?php foreach ($product as $pr): ?>
                <option value="<?php echo $pr->prod_name ?>"><?php echo $pr->prod_name ?>
                </option>
            <?php endforeach; ?>
        </select>
    </div>
    <div class="col-sm-2">
        
        <button type="button" onclick="populate()" class="btn btn-info">Populate</button>
    </div>

</div><br/>
<div class="row">
    
</div>
   

<div class="row" id="hidden" style="display: none">
    <div class="col-sm-12">
        <h3 class="hr_divider"><br/>
        </h3>
        <p style="margin-top:auto;">
        <h6 class="heading-small text-muted mb-4">Stock information</h6>
        <div id="res"></div>
        <div class="row">
        <div class="col-sm-4">
            <label>Total Amount</label>
            <input type="text" class="form-control form-control-alternative" name="total_amt" id="total_amt" value="<?php echo $pr->prod_price?>">
        </div>
        <div class="col-sm-4">
            <label>Paid Amount</label>
            <input type="text" class="form-control form-control-alternative" name="paid_amt" id="paid_amt" value="0">
        </div>
        <div class="col-sm-2"><label> <br></label>
        <button class="btn btn-success" name="submit" value="add">Transfer Stock</button>
        </div>
    </div>
        </p>
    </div>
</div>
</div>
</div>
</div>
<script>
    $(function () {
        $("#pname").autocomplete({
            source: '<?php echo site_url('cron/get_products') ?>'
        });
    });

    function remove(id) { 
        $(id).remove();
    }

    function populate() {
        var fran_id = $('#fname').val();
        var prod_name = $('#pname').val();
        var id = Math.floor(Math.random() * 20);

        if (prod_name == "" || fran_id == "") {
            alert('Please enter product name and Select Pick-up Centre');
        } else {
            $.post("<?php echo site_url('cron/get_stock_qty/') ?>", {fran_id: fran_id, prod: prod_name})
                .done(function (data) {
                    $("#hidden").show('slow');
                    $("#res").append(
                        '<div class="row" id="' + id + '"> <div class="col-sm-4"> <label>Product Name</label> <input readonly name="prod_name[]" class="form-control form-control-alternative" value="' + prod_name + '"></div>' +
                        '<div class="col-sm-3"> <label> Add Stock</label> <input class="form-control form-control-alternative" value="1" name="qty[]"></div><div class="col-sm-2">  <br/><a href="javascript:;" onclick="remove(\'#' + id + '\')" class="btn btn-danger">x</a></div> <br/><small>(This Pick-up Centre currently has: ' + data + ' nos available.)</small></div></div><hr/>'
                    );
                });
        }
    }
</script>
<?php echo form_close() ?>

