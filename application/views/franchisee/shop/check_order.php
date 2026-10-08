<div class="card bg-secondary shadow">

    <!-- SEARCH BOX -->
    <div class="card-header bg-white">
        <h4>Search Order</h4>
        <div class="row">
            <div class="col-md-3">
                <input type="text" id="userid" class="form-control" placeholder="Enter User ID">
            </div>
            <div class="col-md-3">
                <input type="text" id="orderid" class="form-control" placeholder="Enter Order ID">
            </div>
            <div class="col-md-3">
                <button class="btn btn-primary" onclick="searchOrder()">Search</button>
            </div>
        </div>
        <p id="msg" class="text-danger mt-2"></p>
    </div>

    <!-- ORDER TABLE -->
    <div class="card-body">
        <table class="table align-items-center table-flush" id="example">
            <thead class="thead-dark">
            <tr>
                <th>SN</th>
                <th>User ID</th>
                <th>Phone</th>
                <th>Order ID</th>
                <th>Payment Method</th>
                <th>Total Amt</th>
                <th>Date</th>
                <th>Actions</th>
            </tr>
            </thead>

            <tbody id="orderData"></tbody>
        </table>
    </div>
</div>

<!-- DELIVERY MODAL -->
<div class="modal fade" id="myModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered" role="document">
    <div class="modal-content">

      <div class="modal-header">
        <h5 class="modal-title" id="exampleModalLabel">Delivery Details</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>

      <form id="deliverForm" method="post">
        <div class="modal-body text-center">
            <p class="text-dark mb-0">Are you sure you want to mark this order as delivered?</p>
            <input type="hidden" name="orderid" id="deliverid" />
            <input type="hidden" name="tdetail" value="N/A" />
            <input type="hidden" name="otp" value="0000" />
        </div>

        <div class="modal-footer justify-content-center">
            <button type="button" class="btn btn-sm btn-secondary" data-dismiss="modal">Cancel</button>
            <button type="submit" class="btn btn-sm btn-success">Yes, Deliver Now</button>
        </div>
      </form>

    </div>
  </div>
</div>

<!-- AJAX SCRIPT -->
<script>
function searchOrder() {

    let userid  = $("#userid").val();
    let orderid = $("#orderid").val();

    $("#msg").html("");
    $("#orderData").html("<tr><td colspan='8' class='text-center text-white'>Searching...</td></tr>");

    $.ajax({
        url: "<?= base_url('franchisee/search_orders') ?>",
        type: "POST",
        data: { userid: userid, orderid: orderid },
        dataType: "JSON",
        success: function (res) {

            if(!res.status){
                $("#msg").html(res.message);
                $("#orderData").html("");
                return;
            }

            let html = "";
            let sn = 1;

            res.data.forEach(function(e){

                html += `
                    <tr class="bg-secondary text-white">
                        <td>${sn++}</td>
                        <td>${e.userid}</td>
                        <td>${e.phone}</td>
                        <td>#${e.orderid}</td>
                        <td>${e.payment_method}</td>
                        <td><?= config_item('currency') ?>${e.total_amount}</td>
                        <td>${e.date}</td>
                        <td>
                            <a class="btn btn-info btn-sm">${e.status}</a>
                `;

                if(e.status === "Processing"){
                    html += `
                        <a href="<?php echo site_url('franchisee/order_view/'.$e['orderid']); ?>" class="btn btn-info btn-sm">View Order</a>
                        
                        <a class="btn btn-success btn-sm" data-toggle="modal" data-target="#myModal" onclick="setDeliverOrder('${e.orderid}')">Deliver</a>
                    `;
                } else {
                    html += `
                        <a class="btn btn-success btn-sm" 
                           href="<?= base_url('franchisee/invoice_view2/') ?>${e.orderid}">
                           View Invoice
                        </a>
                    `;
                }

                html += `</td></tr>`;
            });

            $("#orderData").html(html);
        }
    });
}

function setDeliverOrder(orderId) {
    document.getElementById('deliverid').value = orderId;
    document.getElementById('deliverForm').action = '<?= base_url('franchisee/order_deliver/') ?>' + orderId;
}
</script>
