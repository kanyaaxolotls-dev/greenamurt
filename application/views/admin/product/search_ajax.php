<?php
if(isset($_POST['date']) && isset($_POST['product'])) {
    $date = $_POST['date'];
    $product = $_POST['product'];

    // Assuming you are using CodeIgniter's database methods, adjust this query accordingly
    $query = $this->db->query("SELECT * FROM product_sale WHERE DATE(date) = '$date' AND product_id IN (SELECT id FROM product WHERE prod_name = '$product')");

    if ($query->num_rows() > 0) {
        // If records are found, display them in the table format
        foreach ($query->result() as $row) {
            echo '<tr>
                    <td>' . $row->sn . '</td>
                    <td>' . config_item('ID_EXT') . $row->userid . '</td>
                    <td>' . $this->db_model->select('name', 'member', array('id' => $row->userid)) . '</td>
                    <td>' . $row->product_name . '</td>
                    <td>' . config_item('currency') . $row->cost . '</td>
                    <td>' . $row->cost . '</td>
                    <td>' . $row->quantity . '</td>
                    <td>' . $row->orderid . '</td>
                    <td>' . $row->date . '</td>
                    <td>' . $row->status . '</td>
                    <td>
                        <a href="#" class="btn btn-sm btn-primary">View</a>
                        <a href="#" class="btn btn-sm btn-danger">Delete</a>
                    </td>
                  </tr>';
        }
    } else {
        // If no data is found, display a message or an empty table row
        echo '<tr><td colspan="11">No records found.</td></tr>';
    }
} else {
    // If date and product are not set, return an error message or handle the scenario accordingly
    echo '<tr><td colspan="11">Invalid request.</td></tr>';
}
?>

