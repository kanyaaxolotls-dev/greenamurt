<!-- Include Bootstrap CSS in your HTML head -->
<link href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">

<!-- Additional CSS for Print -->
<style>
    .container {
        margin-top: 30px;
    }
    @media print {
        .print-btn, form {
            display: none;
        }
        #orderContainer {
            width: 100%;
        }
        .row {
            display: flex;
        }
        .col-sm-6, .col-md-4 {
            width: 100%;
        }
    }
    .row {
        margin-top: 20px;
    }
</style>

<div class="py-4">
    <?php $url_redirect = $this->uri->segment(2); ?>
    <form action="<?= base_url('admin/' . $url_redirect) ?>" method="post">
        <div class="row">
            <div class="col-md-3 col-sm-12">
                <label for="productSelect">Select Product :</label>
                <select class="form-control" name="type" id="productSelect">
                    <option value="" disabled selected>Select Product</option>
                    <?php
                        if ($type) {
                            $pname = $this->db_model->select('prod_name', 'product', array('id' => $type));
                            echo "<option value=\"" . $type . "\" selected>" . $pname . "</option>";
                        }
                        $this->db->select('*');
                        $this->db->from('product');
                        $query = $this->db->get();
                        $data2 = $query->result_array();
                        foreach ($data2 as $tr) {
                    ?>
                    <option value="<?= $tr['id'] ?>"><?= $tr['prod_name'] ?></option>
                    <?php } ?>
                </select>
            </div>
            <div class="col-md-3 col-sm-12">
                <label for="orderTypeSelect">Select Order Type :</label>
                <select class="form-control" name="type2" id="orderTypeSelect">
                    <option value="" disabled selected>Select order type</option>
                    <?php if ($type2) { ?>
                        <option value="<?= $type2 ?>" selected><?= $type2 ?></option>
                    <?php } ?>
                    <option value="Regular">Regular</option>
                    <option value="Auto">Auto Repurchase</option>
                    <option value="Arkbatti">Arkbatti</option>
                </select>
            </div>
            <div class="col-md-3 col-sm-6 col-6">
                <label for="startDate">Start Date :</label>
                <input type="date" name="start_date" class="form-control" id="startDate" value="<?= $start_date ?>"> 
            </div>
            <div class="col-md-3 col-sm-6 col-6">
                <label for="endDate">End Date :</label>
                <input type="date" name="end_date" class="form-control" id="endDate" value="<?= $end_date ?>"> 
            </div>
            <div class="col-12 mt-3">
                <input type="submit" class="btn btn-success w-100" value="Search"> 
            </div>
        </div>
    </form>
</div>

<button class="btn btn-primary mt-4 mb-3 float-right print-btn" onclick="printOrders()">Print Orders</button>

<div id="orderContainer" class="container mt-3">
    <div class="row">
        <?php foreach ($orders as $order): ?>
            <div class="col-sm-6 col-md-4">
                <div class="card mb-4">
                    <div class="card-header text-white">
                        <h5 class="card-title mb-0">Order ID: <?php echo $order->orderid; ?></h5>
                    </div>
                    <div class="card-body">
                        <p class="mb-1"><strong>Name:</strong> <?php echo $order->name; ?></p>
                        <p class="mb-1"><strong>City:</strong> <?php echo $order->city; ?></p>
                        <p class="mb-1"><strong>Address:</strong> <?php echo $order->address; ?></p>
                        <p class="mb-1"><strong>Pincode:</strong> <?php echo $order->pincode; ?></p>
                    </div>
                </div>
            </div>
        <?php endforeach; ?>
    </div>
</div>

<script>
    function printOrders() {
        window.print();
    }
</script>
