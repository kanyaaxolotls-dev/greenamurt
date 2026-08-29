<div class="col">
    <div class="card bg-secondary shadow">
        <div class="card-header bg-white border-0">
        <div class="row align-items-center">
            <div class="col-8">
                  <h3 class="mb-0">Pay Earning</h3>
            </div>
        <div class="col-4 text-right">
            <a href="<?php echo site_url('admin') ?>" class="btn btn-sm btn-primary">Home</a>
        </div>
    </div>
</div>
<div class="card-body">

<?php echo form_open('income/pay_post_earning') ?>
<div class="row">
<!-- ================================================================================================================ -->
    <div class="col-sm-3">
        <label>Income Type</label>
        <select class="form-control" name="income_name">
        <option value="sponsor inc">sponsor inc</option>
            <?php 
//             foreach (config_item('income_name') as $key => $val) {
//     echo '<option value="' . $key . '">' . $val . '</option>';
// }
?>
        </select>
    </div>
    <div class="col-sm-3">
        <label>Amount</label>
        <input type="text" class="form-control" id="amount" name="amount">
    </div>
    <div class="col-sm-3">

                        
                        <label>Userid</label>          
                         <input type="text" onchange="get_user_name('#sponsor', '#spn_res')" class="form-control form-control-alternative"
                               value=""
                               id="sponsor"
                               name="sponsor" > 

                            <!--    <input type="text" onchange="get_user_name('#sponsor', '#spn_res')" class="form-control"
                               id="sponsor" name="sponsor" placeholder="1235"> -->

                       
                            
                        
                    </div>
    <div class="col-sm-3" >
        <div class="form-group" >
           <label>Customer Name</label>    
           <label type="text" id="spn_res" name="spn_res" class="form-control" value="1"></label> 
           <!-- <input type="hidden" class="form-control" id="spn_res" name="spn_res" value="<?php echo set_value('spn_res')?>">   -->
        </div>
    </div>

<div class="col-sm-12"><br/>
        <input type="submit" class="btn btn-sm btn-primary" value="Submit" onclick="return confirm('Are you sure want to add this paypout..?')">
</div>


<div>
    <br>
</br>
</div>
    <?php echo form_close() ?>
    
 <div class="table-responsive">
   <table class="table align-items-center table-flush">
        <thead class="thead-light">

        <tr>
            <th scope="col">SN</th>
            <th scope="col">User ID</th>
            <th scope="col">Amount</th>
            <th scope="col">Type</th>
            <th scope="col">Ref ID</th>
            <th scope="col">Date</th>

        </tr>
    </thead>
        <?php
$sn = 1;
foreach ($earning as $e) {?>
            <tr>
                <td><?php echo $sn++; ?></td>
                <td><a href="<?php echo site_url('users/user_detail/' . $e['userid']) ?>"
                       target="_blank"><?php echo config_item('ID_EXT') . $e['userid']; ?></a></td>
                <td><?php echo config_item('currency') . $e['amount']; ?></td>
                <td><?php echo $e['type']; ?></td>
                <td><?php echo $e['ref_id']?></td>
                <td><?php echo $e['date']; ?></td>
     
              
            </tr>
        <?php }?>
    </table>
</div>
<div class="pull-right">
    <?php echo $this->pagination->create_links(); ?>
</div>
<div class="card-footer">
    <a href="<?php echo site_url('income/search-earning') ?>" class="btn btn-sm btn-primary">&larr; Go Back</a>
</div>
</div>
    </div>
    </div>
<!-- </div> -->

<script type="text/javascript">
    function toogle_div(id1, id2) {
        if ($(id2).prop("checked") == true) {
            $(id1).hide('slow');
        } else {
            $(id1).show('slow');
        }
    }

    function show() {
        $('#form').hide('slow');
        $('#load').show('slow');
    }

    function get_user_name(id, result) {
        var id = $(id).val();
        $.get("<?php echo site_url('site/get_user_name/') ?>" + id, function (data) {
            $(result).html(data);
        });
    }

</script>

<!-- 
<script type='text/javascript'>

var BASE_URL = "<?php echo base_url(); ?>";

  $( "#autouser" ).autocomplete({
      
        source: function( request, response ) {
          // Fetch data
          $.ajax({
            url: BASE_URL + "Income/userList",
            type: 'post',
            dataType: "json",
            data: {
              search: request.term
            },
            success: function( data ) {
              response( data );
            }
          });
        },
        select: function (event, ui) {
          // Set selection
          $('#autouser').val(ui.item.label); // display the selected text
          $('#userid3').val(ui.item.value);
          $('#userid4').val(ui.item.value);  // save selected id to input
          return false;
        }
      });

</script> -->

<!-- <script type="text/javascript">
    function get_user_name(id, result) {
        var id = $(id).val();
        $.get("<?php echo site_url('site/get_user_name/') ?>" + id, function (data) {
        $(result).html(data);
    });
}
</script> -->


                        <!-- <script type="text/javascript">
                        function copyValue(){

                        var dropboxvalue = document.getElementById('mydropbox').value;
                        document.getElementById('txtSecond').value = dropboxvalue;

                        }
                        </script>
                        <script type="text/javascript">
                        function populateSecondTextBox() {
                        document.getElementById('txtSecond').value = document.getElementById('userid').value;
                        } </script> -->

<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>

    <script src="https://ajax.googleapis.com/ajax/libs/jqueryui/1.12.1/jquery-ui.min.js"></script>